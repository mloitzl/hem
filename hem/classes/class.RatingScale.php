<?php
// This class aggregates a number of heuristics
require_once 'class.RatingScaleValue.php';

// This class aggreagates a Translator
require_once 'class.Translation.php';
  /**
   * Class for managing Rating Scales
   *
   *
   * @author Martin Loitzl, martin@loitzl.com
   *
   */

class RatingScale extends DBObject
{

  /**
   * The name of the table with the RatingScale data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'rating_scale';

  /**
   * The fields of the RatingScale table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'scaleId' => 'text',
			     'scaleTitleId' => 'text',
			     );
  
  /**
   * The Primary Key of the RatingScale table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'scaleId';

  
 /**#@+
  * Properties of the object defined @see $table_fields_
  *
  * @var string
  */
  var $scaleId = null;
  var $scaleTitleId = null;
 /**#@-*/


  /**
   * Constructor
   *
   *
   *
   * @param int RatingScake to instantiate
   * @param object Database handle
   */

  function RatingScale($scale_id, & $dbh)
  {
    global $RATINGSCALE_TABLE, $LANGUAGES;

    if(isset($LANGUAGES) && is_array($LANGUAGES))
      {
	$this->languages_ = $LANGUAGES;
	$this->translator_ = & new Translation(0, $dbh);
      }
    else
      {
	$this->error_ = 'RatingScale(): No languages defined, or not in correct format';
      }

    if(isset($RATINGSCALE_TABLE)) $this->table_ = $RATINGSCALE_TABLE;

    DBObject::DBObject($scale_id, $dbh);
  }



  function getRatingScale()
  {
    $ratingscalevalue_array = array();

    // Get Heuristic ids belonging to this set
    $dummy_scale_value = & new RatingScaleValue(0, $this->dbh_);
    $value_ids = $dummy_scale_value->getAllRatingScaleValueIds(
							       array(
								     'scaleId' => $this->id_,
								     ),
							       'scaleValue'
							       );
    if(is_array($value_ids))
      {
	foreach($value_ids as $key => $current_value_id)
	  {
	    $current_value_object = & new RatingScaleValue($current_value_id, $this->dbh_);
	    $ratingscalevalue_array[$current_value_id] = $current_value_object->getRatingScaleValue();
	  }
      }

    // Assemble return Array
    $return_array = array(
			  'scaleId' => $this->id_,
			  'title_translation' => array(
						       'trans_id' => $this->scaleTitleId,
						       ),
			  );

    // Add Translations to return Array
    if(!$return_array['title_translation'] = $this->translator_->getTranslationArray($this->scaleTitleId))
      $this->error_ = $this->translator_->getError();
    
    // Add Heuristocs to return Array
    $return_array['values'] = $ratingscalevalue_array;

    return $return_array;
  }

  /**
   * Returns all Available Rating Scale Ids
   *
   * Returns all Rating Scale Ids, that match the filter
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
  function getAllRatingScaleIds($filters = array(), $order_column = null, $order_direction = 'ASC')
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
	    $this->setError('No scales found');
	    return FALSE;
	  }
      }
  }
  
  function exportScaleData()
  {
    $return_array = Array();
    
    if($this->init_ok_)
      {
	$scale_data = $this->getData();

	$return_array[0]['data'] = $scale_data;
	$return_array[0]['translations']['scaleTitleId'] = $this->translator_->exportTranslation($scale_data['scaleTitleId']);
      }

    return $return_array;
  }


  function exportScaleValueData()
  {
    $return_array = Array();
    
    if($this->init_ok_)
      {
	$dummy_scale_value = & new RatingScaleValue(0, $this->dbh_);
	$value_ids = $dummy_scale_value->getAllRatingScaleValueIds(
								   array(
									 'scaleId' => $this->id_,
									 ),
								   'scaleValue'
								   );
	if(is_array($value_ids))
	  {
	    $i=0;
	    foreach($value_ids as $key => $current_value_id)
	      {
		$current_value_object = & new RatingScaleValue($current_value_id, $this->dbh_);
		$current_scale_value_data = $current_value_object->getData();
		$return_array[$i]['data'] = $current_scale_value_data;
		$return_array[$i]['translations']['scaleValueCaptionId'] = $this->translator_->exportTranslation($current_scale_value_data['scaleValueCaptionId']);
		$i++;
	      }
	  }
      }

    return $return_array;
  }
  
}
?>