<?php
// This class aggregates a number of heuristics
require_once 'class.Heuristic.php';

// This class aggreagates a Translator
require_once 'class.Translation.php';

class HeuristicSet extends DBObject
{

  /**
   * The name of the table with the data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'heuristic_set';

  /**
   * The fields of the table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'hSetId' => 'text',
			     'hSetTitleId' => 'text',
			     'hSetDescriptionId' => 'text'
			     );
  
  /**
   * The Primary Key of the project table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'hSetId';

  
 /**#@+
  * Properties of the object defined @see $table_fields_
  *
  * @var string
  */
  var $hSetId = null;
  var $hSetTitleId = null;
  var $hSetDescriptionId = null;
 /**#@-*/


  /**
   * Constructor
   *
   *
   *
   * @param int Project to instantiate
   * @param object Database handle
   */
  function HeuristicSet($heur_set_id = null, & $dbh)
  {
    global $HEURISTICSET_TABLE, $LANGUAGES;

    $this->dbh_ = $dbh;

    if(isset($LANGUAGES) && is_array($LANGUAGES))
      {
	$this->languages_ = $LANGUAGES;
	//	print_r($this->languages_);
	$this->translator_ = & new Translation(0, $this->dbh_);
      }
    else
      {
	$this->error_ = 'Heuristic(): No languages defined, or not in correct format';
      }

    $this->table_ = (!empty($HEURISTICSET_TABLE)) ? $HEURISTICSET_TABLE : $this->table_ ;

    DBObject::DBObject($heur_set_id, $this->dbh_);
  }


  function getHeuristicSet()
  {
    $heuristic_array = array();

    // Get Heuristic ids belonging to this set
    $dummy_heuristic = & new Heuristic(0, $this->dbh_);
    $heuristic_ids = $dummy_heuristic->getHeuristicIds(
						       array(
							     'hSetId' => $this->id_,
							     ),
						       'hOrder'
						       );
    if(is_array($heuristic_ids))
      {
	foreach($heuristic_ids as $key => $current_heuristic_id)
	  {
	    $current_heuristoc_object = & new Heuristic($current_heuristic_id, $this->dbh_);
	    $heuristic_array[$current_heuristic_id] = $current_heuristoc_object->getHeuristic();
	  }
      }

    // Assemble return Array
    $return_array = array(
			  'hSetId' => $this->id_,
			  'title_translation' => array(
						       'trans_id' => $this->hSetTitleId,
						       ),
			  'description_translation' => array(
						       'trans_id' => $this->hSetDescriptionId,
						       ),
			  );

    // Add Translations to return Array
    if(!$return_array['title_translation'] = $this->translator_->getTranslationArray($this->hSetTitleId))
      $this->error_ = $this->translator_->getError();
    if(!$return_array['description_translation'] = $this->translator_->getTranslationArray($this->hSetDescriptionId))
      $this->error_ = $this->translator_->getError();
    
    // Add Heuristocs to return Array
    $return_array['heuristics'] = $heuristic_array;

    return $return_array;
  }




  /**
   * Stores an Heuristic Set
   * 
   * Removes all Heuristics in that set 
   * and stores and assigns the new ones with the 
   * Heuristoc Set afterwards
   * It also stores Description and Title Translations of the Set
   *
   * @param array Associative Array with the data
   * @return boolean TRUE on success, FALSE otherwise
   */
  function storeHeuristicSet($data)
  {
    if(is_array($data))
      {
	// Remove all Heuristics from set
	$this->removeHeuristicsFromSet();
	
	// Store new Heuristics
	if(isset($data['heuristics']))
	  $heuristics = $data['heuristics'];
	else
	  $heuristics = array();
	
	$dummy_heuristic = & new Heuristic(0, $this->dbh_);
	foreach ($heuristics as $key => $current_heuristic)
	  {
	    // Fragile Data, lets be sure they are set correctly
	    // an error here and the Heurictic will never be found again
	    $current_heuristic['hSetId'] = $data['hSetId'];
	    $dummy_heuristic->storeHeuristic($current_heuristic);	    
	  }

	// Store Heuristic Set
	$title_translations = $data['title_translation'];
	$description_translations = $data['description_translation'];
	
	$data = array(
		      'hSetId' => $data['hSetId'],
		      );
	
	// Store Title Translations
	if(is_array($title_translations))
	  {
	    $this->translator_->storeTranslationArray($title_translations);
	    $data['hSetTitleId'] = $title_translations['trans_id'];
	  }
	else
	  {
	    $this->error_ = "storeHeuristicSet(): Title Translations not given, or not in correct format";
	    return FALSE;
	  }

	// Store Description Translations
	if(is_array($description_translations))
	  {
	    $this->translator_->storeTranslationArray($description_translations);
	    $data['hSetDescriptionId'] = $description_translations['trans_id'];
	  }
	else
	  {
	    $this->error_ = "storeHeuristicSet(): Description Translations not given, or not in correct format";
	    return FALSE;
	  }
	
	// Store Heuristic Set Data
	if($this->init_ok_)
	  $this->updateData($data);
	else
	  $this->addData($data);
	
      }
    else
      {
	$this->error_ = "storeHeuristicSet(): data is not an array, or malformed";
	return FALSE;
      }
  }


  function removeHeuristicSet($heur_set_id = null)
  {
    //    echo "removeHeuristicSet() id to delete: $heur_set_id<br/>";
    //    echo "removeHeuristicSet() my own ID: $this->id_<br/>";
    

    if(is_null($heur_set_id))
      {
	$heur_set_id = $this->id_;
      }
    if(!is_null($heur_set_id))
      {
	//	$this->toggleQueryDebug();
	$this->removeHeuristicsFromSet();
	$this->translator_->removeTranslation($this->hSetTitleId);
	$this->translator_->removeTranslation($this->hSetDescriptionId);
	
	$this->deleteData();
      }
    else
      {
	$this->error_ = "removeHeuristicSet(): No valid Id given";
	return FALSE;
      }
  }


  function removeHeuristicsFromSet()
  {
    //    echo "calling getHeuristicSet now, my id =  ($this->id_)<br/>";
    $set = $this->getHeuristicSet();
    $old_heuristic_ids = array_keys($set['heuristics']);
    
    //    print_r($old_heuristic_ids);

    foreach($old_heuristic_ids as $key => $current_heuristic_id_to_remove)
      {
	$current_heuristic_to_remove = & new Heuristic($current_heuristic_id_to_remove, $this->dbh_);
	$current_heuristic_to_remove->removeHeuristic();
      }
  }


  /**
   * Returns all Available Heuristic Set Ids
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
   * @return array HeuristicSet ids
   */
  function getHeuristicSetIds($filters = array(), $order_column = null, $order_direction = 'ASC')
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
	    $this->setError('No Heuristics Sets found');
	    return FALSE;
	  }
      }
  }


  function exportHeuristicSetData()
  {
    $return_array = Array();

    if($this->init_ok_)
      {
	$return_array[0]['data'] = $this->getData();
	$return_array[0]['translations']['hSetTitleId'] = $this->translator_->exportTranslation($return_array[0]['data']['hSetTitleId']);
	$return_array[0]['translations']['hSetDescriptionId'] = $this->translator_->exportTranslation($return_array[0]['data']['hSetDescriptionId']);
      }


    return $return_array;
  }


  function exportHeuristicData()
  {
    $return_array = array();
    
    if($this->init_ok_)
      {
	// Get Heuristic ids belonging to this set
	$dummy_heuristic = & new Heuristic(0, $this->dbh_);
	$heuristic_ids = $dummy_heuristic->getHeuristicIds(
							   array(
								 'hSetId' => $this->id_,
								 ),
							   'hOrder'
							   );
	if(is_array($heuristic_ids))
	  {
	    $i = 0;
	    foreach($heuristic_ids as $key => $current_heuristic_id)
	      {
		$current_heuristic_object = & new Heuristic($current_heuristic_id, $this->dbh_);
		$return_array[$i]['data'] = $current_heuristic_object->getData();
		$return_array[$i]['translations']['hTitleId'] = $this->translator_->exportTranslation($return_array[$i]['data']['hTitleId']);
		$return_array[$i]['translations']['hDescriptionId'] = $this->translator_->exportTranslation($return_array[$i]['data']['hDescriptionId']);

		$i++;
	      }
	  }
      }
    
    return $return_array;
  }


}
?>