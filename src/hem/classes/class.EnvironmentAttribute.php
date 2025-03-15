<?php
require_once 'class.Translation.php';

class EnvironmentAttribute extends DBObject
{

  /**
   * Database Handle
   *
   * @access private
   * @var    object
   */
  var $dbh_ = null;

  /**
   * The name of the table with the heuristic data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'environment_attributes';
  
  /**
   * The id of the Heuristic
   *
   * @access private
   * @var    int
   */
  var $id_ = null;

  /**
   * The fields of the heuristic table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'envAttributeId' => 'text',
			     'envId' => 'text',
			     'envOrder' => 'text',
			     'envAttributeNameId' => 'text',
			     'envAttributeType' => 'text',
			     'envAttributeValues' => 'text',
			     );
  
  /**
   * The Primary Key of the heuristic table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'envAttributeId';

  /**
   * Flag if heuristic with id was initialized correctly
   *
   * @access protected
   * @var    string
   */
  var $init_ok_ = FALSE;

  /**
   * Holds an associative array with the heuristic data
   *
   * @access private
   * @var    array
   */
  var $data_array_ = array();
  
 /**#@+
  * Properties of the object defined see $heuristic_table_fields_
  *
  * @var string
  */
  var $envAttributeId = null;
  var $envId = null;
  var $envOrder = null;
  var $envAttributeNameId = null;
  var $envAttributeType = null;
  var $envAttributeValues = null;
 /**#@-*/


  /**
   * Constructor
   *
   *
   *
   * @param int Heuristic to instantiate
   * @param object Database handle
   */

  function EnvironmentAttribute($attribute_id, & $dbh)
  {
    global $ENVIRONMENT_ATTRIBUTES_TABLE, $LANGUAGES;

    if(isset($LANGUAGES) && is_array($LANGUAGES))
      {
	$this->languages_ = $LANGUAGES;
	$this->translator_ = & new Translation(0, $dbh);
      }
    else
      {
	$this->error_ = 'Heuristic(): No languages defined, or not in correct format';
      }

    $this->table_ = (isset($ENVIRONMENT_ATTRIBUTES_TABLE)) ? $ENVIRONMENT_ATTRIBUTES_TABLE : $this->table_;     

    DBObject::DBObject($attribute_id, $dbh);
  }


/**
   * Sets the Attribute data stored in Database as member variables
   * and returns Attribute Data as associative array
   *
   * Sets the init_ok_ property according to the success
   *
   * @return mixed associative array or FALSE
   */

  function getAttribute()
  {
    if(!$this->init_ok_)
      $this->init();

    $return_array = array(
			  'envAttributeId' => $this->id_,
			  'title_translation' => array(
						       'trans_id' => $this->envAttributeNameId,
						       ),
			  'envId' => $this->envId,
			  'envOrder' => $this->envOrder,
			  'envAttributeType' => $this->envAttributeType,
			  'envAttributeValues' => $this->envAttributeValues,
			  );
    
    if(!$return_array['title_translation'] = $this->translator_->getTranslationArray($this->envAttributeNameId))
      $this->error_ = $this->translator_->getError();

    return $return_array;
  }

 /**
  * Adds a Attribute according to the data in the passed array
  *
  * @param array An associative Array with the data, indices are the fields defined in class
  * @return mixed associative array or FALSE
  */
  
  function storeAttribute($data = null)
  {
    if(!is_null($data) && is_array($data))
      {
	$title_translations = $data['title_translation'];
	
	$data = array(
		      'envAttributeId' => $data['envAttributeId'],
		      'envId' => $data['envId'],
		      'envOrder' => $data['envOrder'],
		      'envAttributeType' => $data['envAttributeType'],
		      'envAttributeValues' => $data['envAttributeValues'],
		      );

	if(is_array($title_translations))
	  {
	    $this->translator_->storeTranslationArray($title_translations);
	    $data['envAttributeNameId'] = $title_translations['trans_id'];
	  }
	else
	  {
	    $this->error_ = "storeHeuristic(): Title Translations not given, or not in correct format";
	    return FALSE;
	  }

	if($this->init_ok_)
	  $this->updateData($data);
	else
	  $this->addData($data);

	// Data of Object is now dirty, needs to be reloaded from Database
	$this->init_ok_ = FALSE;
      }
    else
      {
	$this->error_ = "storeAttribute(): No data given, or not in correct format";
	return FALSE;
      }
  }
  
  
  /**
   * Deletes an Attribute and all Translations used by it
   *
   * Deletes the Heuristic with the given id
   * TODO: Check! This is not without side effects!
   *       passing an ID to this method changes the identity of the whole object!
   *
   * @param int Id of the heuristic
   * @return boolean TRUE on success
   */
  function removeAttribute($attr_id = null)
  {
    if($this->init_ok_ && is_null($attr_id))
      {
	$attr_id = $this->id_;
      }
    else
      {
	$this->id_ = $attr_id;
	$this->init();
	if(!$this->init_ok_)
	  $attr_id = null;
      }
    if(!is_null($attr_id))
      {
	$this->translator_->removeTranslation($this->envAttributeNameId);
	
	$this->deleteData();
      }
    else
      {
	$this->error_ = "removeAttribute(): No valid Id given";
	return FALSE;
      }
  }



  /**
   * Returns all Available Attribute Ids
   *
   * Returns all Attribute Ids, that match the filter
   * 2 Usages:
   * 1: $filter = array(
   *          'fieldname' => array('op' => '>', 'value' => 'dummy', 'cond' => ''),
   *          'fieldname' => array('op' => '<', 'value' => 'dummy2', 'cond' => 'OR'),
   *          );
   * 2: $filter = array(
   *          'fieldname' => 'value',
   *          'another_fieldname' => 'another_value',
   *          );
   * Filter implementation taken From LiveUser (AdminAuth::getUsers())   *
   * @param array filter
   * @return array Rating Scale ids
   */
  function getAttributeIds($filters = array(), $order_column = null, $order_direction = 'ASC')
  {
    $where = '';
    $order = '';

    if (sizeof($filters) > 0) 
      {
	$where = ' WHERE';
	foreach ($filters as $f => $v) 
	  {
	    if (is_array($v)) 
	      {
		$cond = ' ' . $v['cond'];
		$where .= ' ' . $v['name'] . $v['op'] . $this->dbh_->quote($v['value']) . $cond;
	      } 
	    else 
	      {
		$cond = ' AND';
		$where .= " $f=". $this->dbh_->quote($v) . "" . $cond;
	      }
	  }
	$where = substr($where, 0, -(strlen($cond)));
      }

    if(!is_null($order_column))
      {
	$order = " ORDER BY $order_column ";
	if($order_direction !== 'ASC')
	  $order.= 'DESC';
      }

    $query = "SELECT $this->table_primary_key_ FROM $this->table_ ";

    $query.= $where;
    $query.= $order;

    $result = $this->dbh_->query($query);
    if($this->dbh_->hasError())
      {
	$this->setError($this->dbh_->getError());
	$this->dbh_->resetError();
	return FALSE;
      }
    else
      {
	if($result->numRows() > 0)
	  {
	    $return_array = array();
	    while($row = $result->fetchRow())
	      {
		array_push($return_array, $row->{$this->table_primary_key_});
	      }
	    return $return_array;
	  }
	else
	  {
	    $this->setError('No Attributes found');
	    return FALSE;
	  }
      }
  }

}
?>