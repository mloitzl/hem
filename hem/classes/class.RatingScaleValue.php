<?php
require_once 'class.Translation.php';

  /**
   * Class for managing Rating Scale Values
   *
   *
   * @author Martin Loitzl, martin@loitzl.com
   *
   */

class RatingScaleValue extends DBObject
{

  /**
   * The name of the table with the Rating Scale Value data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'rating_scale_value';

  /**
   * The fields of the Rating Scale Value table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'scaleValueId' => 'text',
			     'scaleValue' => 'text',
			     'scaleValueCaptionId' => 'text',
			     'scaleId' => 'text',
			     );
  
  /**
   * The Primary Key of the Rating Scale Value table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'scaleValueId';

  
 /**#@+
  * Properties of the object defined @see $table_fields_
  *
  * @var string
  */
  var $scaleValueId = null;
  var $scaleValue = null;
  var $scaleValueCaptionId = null;
  var $scaleId = null;
 /**#@-*/


  /**
   * Constructor
   *
   *
   *
   * @param int Rating Scale Value to instantiate
   * @param object Database handle
   */

  function RatingScaleValue($value_id, & $dbh)
  {
    global $RATINGSCALEVALUE_TABLE, $LANGUAGES;

    if(isset($LANGUAGES) && is_array($LANGUAGES))
      {
	$this->languages_ = $LANGUAGES;
	$this->translator_ = & new Translation(0, $dbh);
      }
    else
      {
	$this->error_ = 'RatingScaleValue(): No languages defined, or not in correct format';
      }

    if(isset($RATINGSCALEVALUE_TABLE)) $this->table_ = $RATINGSCALEVALUE_TABLE;


    DBObject::DBObject($value_id, $dbh);
  }


  function getRatingScaleValue()
  {
    if(!$this->init_ok_)
      $this->init();
    
    $return_array = array(
			  'scaleValueId' => $this->id_,
			  'title_translation' => array(
						       'trans_id' => $this->scaleValueCaptionId,
						       ),
			  'scaleValue' => $this->scaleValue,
			  'scaleId' => $this->scaleId,
			  );
    
    if(!$return_array['title_translation'] = $this->translator_->getTranslationArray($this->scaleValueCaptionId))
      $this->error_ = $this->translator_->getError();
    
    return $return_array;
  }
  

  /**
   * Returns all Available Rating Scale Value Ids
   *
   * Returns all Rating Scale Value Ids, that match the filter
   * 2 Usages:
   * 1: $filter = array(
   *          'fieldname' => array('op' => '>', 'value' => 'dummy', 'cond' => ''),
   *          'fieldname' => array('op' => '<', 'value' => 'dummy2', 'cond' => 'OR'),
   *          );
   * 2: $filter = array(
   *          'fieldname' => 'value',
   *          'another_fieldname' => 'another_value',
   *          );
   * Filter implementation taken From LiveUser (AdminAuth::getUsers())
   * @param array filter
   * @return array Rating Scale ids
   */
  function getAllRatingScaleValueIds($filters = array(), $order_column = null, $order_direction = 'ASC')
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
	    $this->setError('No scale values found');
	    return FALSE;
	  }
      }
  }


}
?>