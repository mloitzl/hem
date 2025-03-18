<?php
require_once 'class.Translation.php';

class Heuristic extends DBObject
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
  var $table_ = 'heuristic';
  
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
				      'hId' => 'text',
				      'hTitleId' => 'text',
				      'hDescriptionId' => 'text',
				      'hSetId' => 'text',
				      'hOrder' => 'text',
				      );
  
  /**
   * The Primary Key of the heuristic table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'hId';

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
  var $hId = null;
  var $hTitleId = null;
  var $hDescriptionId = null;
  var $hSetId = null;
  var $hOrder = null;
 /**#@-*/


  /**
   * Constructor
   *
   *
   *
   * @param int Heuristic to instantiate
   * @param object Database handle
   */

  function Heuristic($heuristic_id, & $dbh)
  {
    global $HEURISTIC_TABLE, $LANGUAGES;

    //    $this->dbh_ = $dbh;

    if(isset($LANGUAGES) && is_array($LANGUAGES))
      {
	$this->languages_ = $LANGUAGES;
	//	print_r($dbh);
	$this->translator_ = & new Translation(0, $dbh);
      }
    else
      {
	$this->error_ = 'Heuristic(): No languages defined, or not in correct format';
      }

    $this->table_ = (isset($HEURISTIC_TABLE)) ? $HEURISTIC_TABLE : $this->table_;     

    DBObject::DBObject($heuristic_id, $dbh);
  }


/**
   * Sets the Heuristic Data stored in Database as member variables
   * and returns Heursitic Data as associative array
   *
   * Sets the init_ok_ property according to the success
   *
   * @return mixed associative array or FALSE
   */

  function getHeuristic()
  {
    if(!$this->init_ok_)
      $this->init();

    $return_array = array(
			  'hId' => $this->id_,
			  'title_translation' => array(
						       'trans_id' => $this->hTitleId,
						       ),
			  'description_translation' => array(
						       'trans_id' => $this->hDescriptionId,
						       ),
			  'hOrder' => $this->hOrder,
			  'hSetId' => $this->hSetId,
			  );
    
    if(!$return_array['title_translation'] = $this->translator_->getTranslationArray($this->hTitleId))
      $this->error_ = $this->translator_->getError();
    if(!$return_array['description_translation'] = $this->translator_->getTranslationArray($this->hDescriptionId))
      $this->error_ = $this->translator_->getError();

    return $return_array;
  }

 /**
  * Adds a Heuristic according to the data in the passed array
  *
  * @param array An associative Array with the data, indices are the fields defined in class
  * @return mixed associative array or FALSE
  */
  
  function storeHeuristic($data = null)
  {
    if(!is_null($data) && is_array($data))
      {
	$title_translations = $data['title_translation'];
	$description_translations = $data['description_translation'];
	
	$data = array(
		      'hId' => $data['hId'],
		      'hSetId' => $data['hSetId'],
		      'hOrder' => $data['hOrder'],
		      );

	if(is_array($title_translations))
	  {
	    $this->translator_->storeTranslationArray($title_translations);
	    $data['hTitleId'] = $title_translations['trans_id'];
	  }
	else
	  {
	    $this->error_ = "storeHeuristic(): Title Translations not given, or not in correct format";
	    return FALSE;
	  }

	if(is_array($description_translations))
	  {
	    $this->translator_->storeTranslationArray($description_translations);
	    $data['hDescriptionId'] = $description_translations['trans_id'];
	  }
	else
	  {
	    $this->error_ = "storeHeuristic(): Description Translations not given, or not in correct format";
	    return FALSE;
	  }

	if($this->init_ok_)
	  $this->updateData($data);
	else
	  $this->addData($data);
      }
    else
      {
	$this->error_ = "storeHeuristic(): No data given, or not in correct format";
	return FALSE;
      }
  }
  
  
  /**
   * Deletes an Heuristic and all Translations used by it
   *
   * Deletes the Heuristic with the given id
   * TODO: Check! This is not without side effects!
   *       passing an ID to this method changes the identity of the whole object!
   *
   * @param int Id of the heuristic
   * @return boolean TRUE on success
   */
  function removeHeuristic($heur_id = null)
  {
    if($this->init_ok_ && is_null($heur_id))
      {
	$heur_id = $this->id_;
      }
    else
      {
	$this->id_ = $heur_id;
	$this->init();
	if(!$this->init_ok_)
	  $heur_id = null;
      }
    if(!is_null($heur_id))
      {
	$this->translator_->removeTranslation($this->hTitleId);
	$this->translator_->removeTranslation($this->hDescriptionId);
	
	$this->deleteData();
      }
    else
      {
	$this->error_ = "removeHeuristic(): No valid Id given";
	return FALSE;
      }
  }



  /**
   * Returns all Available Heuristic Ids
   *
   * Returns all Heuristic Ids, that match the filter
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
  function getHeuristicIds($filters = array(), $order_column = null, $order_direction = 'ASC')
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
	    $this->setError('No Heuristics found');
	    return FALSE;
	  }
      }
  }

}
?>