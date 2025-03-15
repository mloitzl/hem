<?php
  /**
   * Class for managing Evaluation Findings
   *
   *
   * @author Martin Loitzl, martin@loitzl.com
   *
   */

class Finding extends DBObject
{

  /**
   * The name of the table with the finding data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'finding';

  /**
   * The name of the table with the evaluator manager finding association
   *
   * @access private
   * @var    string
   */
  var $associacion_table_ = 'manager_evaluator_finding';


  /**
   * The name of the DB table, where the ratings are stored
   *
   * @access private
   * @var string
   */
  var $rating_table_ = 'finding_rate';

  /**
   * The fields of the finding table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'fId' => 'text',
			     'fText' => 'text',
			     'pId' => 'text',
			     'uId' => 'text',
			     'heurId' => 'text',
			     'fPositive' => 'text',
			     'fManagerFinding' => 'text',
			     'fTimestamp' => 'text',
			     'fLastEditedTimestamp' => 'text',
			     'fOrder' => 'text',
			     );
  
  /**
   * The Primary Key of the finding table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'fId';

  
  /**#@+
   * Properties of the object defined @see $table_fields_
   *
   * @var string
   */
  var $fId = null;
  var $fText = null;
  var $pId = null;
  var $uId = null;
  var $heurId = null;
  var $fPositive = null;
  var $fManagerFinding = null;
  var $fTimestamp = null;
  var $fLastEditedTimestamp = null;
  var $fOrder = null;
  /**#@-*/
  
  /**
   * Constructor
   *
   *
   *
   * @param int Finding to instantiate
   * @param object Database handle
   */

  function Finding($finding_id = null, & $dbh)
  {
    global $FINDING_TABLE, $FINDING_ASSOCIATION_TABLE, $RATING_TABLE;

    if(isset($FINDING_TABLE)) $this->table_ = $FINDING_TABLE;
    if(isset($FINDING_ASSOCIATION_TABLE)) $this->associacion_table_ = $FINDING_ASSOCIATION_TABLE;
    if(isset($RATING_TABLE)) $this->rating_table_ = $RATING_TABLE;

    DBObject::DBObject($finding_id, $dbh);
  }


  function swapFindings($finding_id_1, $finding_id_2)
  {
    $finding_1 = & new Finding($finding_id_1, $this->dbh_);
    $finding_2 = & new Finding($finding_id_2, $this->dbh_);
    

    if( !empty($finding_1->fOrder) && !empty($finding_2->fOrder) )
      {
	$data_1 = array(
			'fId' => $finding_1->fId,
			'fOrder' => $finding_2->fOrder,
			);
	
	$data_2 = array(
			'fId' => $finding_2->fId,
			'fOrder' => $finding_1->fOrder,
			);
	
	$finding_1->updateData($data_1);
	$finding_2->updateData($data_2);
	
	return TRUE;
      }
    else
      {
	return FALSE;
      }
  }


  function getSuccessorFindingId($finding_id)
  {
    $finding = & new Finding($finding_id, $this->dbh_);
    if($finding->init_ok_)
      {
	if($finding->fManagerFinding == 'Y')
	  {
	    $is_manager_finding = 'Y';
	    // if two managers are merging, the user id can change
	    $finding->uId = null;
	  }
	else
	  $is_manager_finding = 'N';

	$successor_id = $finding->getAllFindingIds($finding->pId, $finding->uId, null, null, ($finding->fOrder + 1), $is_manager_finding);
	
	return $successor_id[0];
      }
    else
      return FALSE;
  }

  function getPredecessorFindingId($finding_id)
  {
    $finding = & new Finding($finding_id, $this->dbh_);
    if($finding->init_ok_)
      {
	if($finding->fManagerFinding == 'Y')
	  {
	    $is_manager_finding = 'Y';
	    // if two managers are merging, the user id can change
	    $finding->uId = null;
	  }
	else
	  {
	    $is_manager_finding = 'N';
	  }
	//	echo "looking for predecessor of $finding->fId (mgrF: $finding->fManagerFinding) <br>";
	$predecessor_id = $finding->getAllFindingIds($finding->pId, $finding->uId, null, null, ($finding->fOrder - 1), $finding->fManagerFinding);
	
	return $predecessor_id[0];
      }
    else
      return FALSE;
  }


  function getAllFindingIds($project_id = null, $user_id = null, $order_column = null, $order_direction = 'ASC', $order_id = null, $is_manager_finding = null, $is_positive = null)
  {
    $query = "SELECT $this->table_primary_key_ FROM $this->table_ ";

    $where = array();

    if(!is_null($project_id))
      array_push($where, "pId = '$project_id'");
    if(!is_null($user_id))
      array_push($where, "uId = '$user_id'");
    if(!is_null($order_id))
      array_push($where, "fOrder = '$order_id'");
    if($is_manager_finding == 'Y')
      array_push($where, "fManagerFinding = 'Y'");
    else if($is_manager_finding == 'N')
      array_push($where, "fManagerFinding = 'N'");
    if($is_positive == 'Y')
      array_push($where, "fPositive = 'Y'");
    else if($is_positive == 'N')
      array_push($where, "fPositive = 'N'");

      


    if(!empty($where))
      $query.= ' WHERE ' . implode(' AND ' , $where);

    if(!is_null($order_column))
      {
	$query.= "ORDER BY $order_column ";
      }
    if($order_direction == 'DESC')
      {
	$query.= "DESC";
      }

    //    echo $query."<br>";
    $result = $this->dbh_->query($query);
    
    if(($this->dbh_->hasError()) || ($result->numRows() < 1))
      {
	$this->setError('getAllFindingIds(): DB returned 0 rows ');
	return FALSE;
      }
    else
      {
	$id_array = array();
	while ($row = $result->fetchRow())
	  {
	    array_push($id_array, $row->{$this->table_primary_key_});
	  }
	return $id_array;
      }
  }



  /**
   * Frees a slot in the fOrder Array
   *
   * Begins with last Finding and goes up till the Finding subjected in the parameter
   * Every Finding Order is incremented till end
   *
   */
  function freeSpaceAfterFindingId($finding_id)
  {
    $finding = & new Finding($finding_id, $this->dbh_);
	
    /*    echo "<pre>";
    print_r($finding->data_array_);
    echo "</pre>";*/

    $user_id = $finding->uId;
    $project_id = $finding->pId;

    // The last finding, thi is where to stop
    //    echo $finding->fManagerFinding."?<br/>";
    // Different managers can merge findings, so if manager finding -> user id = null
    if($finding->fManagerFinding == 'Y')
      $user_id = null;
    $last_finding_id = $finding->getLastFindingId($user_id, $project_id, $finding->fManagerFinding );
    // We begin with the last finding as current Finding
    $current_finding = & new Finding($last_finding_id, $this->dbh_);

    // and loop until we have reached the subjected one
    //    echo "Looping till $current_finding->fId neq $finding->fId <br>";
    $i=0;
    while($current_finding->fId !== $finding->fId && ($i <25))
      {
	//	echo "Loop:".$i++."| cur: $current_finding->fId neq to be freed: $finding->fId ?<br/>";
	//	echo "<pre>";
	//	print_r($current_finding->data_array_);
	//	echo "</pre>";

	// get the previous of the current one
	$next_finding_id = $current_finding->getPredecessorFindingId($current_finding->fId);
	// Set id and Order + 1 ...
	$data['fId'] = $current_finding->fId;
	$data['fOrder'] = $current_finding->fOrder + 1;
	// ...and set the data in DB
	$current_finding->updateData($data);
	// set the next finding as the current one
	$current_finding = & new Finding($next_finding_id, $this->dbh_);
      }
  }


  function deleteFinding()
  {
    // Fill the Space in the order array
    $this->fillSpaceFromFindingId($this->fId);

    $this->deleteData();
  }



  /**
   * Fill the hole in the fOrder Array caused by deleting
   *
   * Begins with the last Finding and goes up unitil the deleted finding is reached 
   *
   *
   */
  function fillSpaceFromFindingId($finding_id)
  {
    // The finding we want to delete and tears the hole
    $finding = & new Finding($finding_id, $this->dbh_);
    
    $user_id = $this->uId;
    $project_id = $this->pId;

    // The last finding, thi is where to stop
    // Different managers can merge findings, so if manager finding -> user id = null
    if($finding->fManagerFinding == 'Y')
      $user_id = null;
    // The last Finding
    $last_finding_id = $this->getLastFindingId($user_id, $project_id, $finding->fManagerFinding);
    // We start with the last one...
    $current_finding = & new Finding($last_finding_id, $this->dbh_);

    // ... and loop until we've reached the one to be deleted
    while($current_finding->fId !== $finding->fId)
      {
	// Get the next Finding we want to update
	$next_finding_id = $current_finding->getPredecessorFindingId($current_finding->fId);
	// Set Id and Order - 1 ---
	$data['fId'] = $current_finding->fId;
	$data['fOrder'] = $current_finding->fOrder - 1;
	// ... and update the Database
	$current_finding->updateData($data);
	// set the next Finding as current finding
	$current_finding = & new Finding($next_finding_id, $this->dbh_);
      }    
  }


  function addFinding($data)
  {
    if(!empty($data['after']))
      $finding = & new Finding($data['after'], $this->dbh_);
    else
      $finding = & new Finding($this->getLastFindingId($data['uId'], $data['pId'], $data['fManagerFinding']), $this->dbh_);

    // Finding has a successor, so the new findings order is the successors order
    if($successor_finding_id = $finding->getSuccessorFindingId($finding->fId))
      {
	$successor_finding = & new Finding($successor_finding_id, $this->dbh_);
	$data['fOrder'] = $successor_finding->fOrder;
      }
    // Finding has no successor (so it is the last one), new order is last ones + 1
    else
      {
	$data['fOrder'] = $finding->fOrder + 1;
      }

    /*    echo "<pre>";
    print_r ($data);
    echo "</pre>";*/

    // Free Space in the order array
    //    echo "Calling freespaceafterfId".$data['after']."<br>";
    $this->freeSpaceAfterFindingId($data['after']);

    $this->addData($data);
  }


  function getLastFindingId($user_id = null, $project_id, $is_manager_finding = 'N')
  {
    //    echo "??".$is_manager_finding."??<br/>";

    $query = "SELECT fId"
      . " FROM $this->table_"
      . " WHERE pId = '$project_id'";
    
    if(!is_null($user_id))
      $query.=" AND uId = '$user_id'"; 

    if($is_manager_finding == 'Y')
      $query.= " AND fManagerFinding = 'Y'";
    else
      $query.= " AND fManagerFinding = 'N'";


    $query.= " ORDER BY fOrder DESC";
   
    //    echo $query."<br/>";

    $result = $this->dbh_->query($query);
    
    if(($this->dbh_->hasError()) || ($result->numRows() < 1))
      {
	return FALSE;
      }
    else
      {
	$row = $result->fetchRow();
	return $row->fId;
      } 
  }


  function getAttachedFindingIds($manager_finding_id)
  {
    $query = "SELECT efId FROM $this->associacion_table_ WHERE mfId = '$manager_finding_id'";

    $result = $this->dbh_->query($query);

    $return_array = array();

    if(($this->dbh_->hasError()) || ($result->numRows() < 1))
      {
	return FALSE;
      }
    else
      {
	while($row = $result->fetchRow())
	  {
	    array_push($return_array, $row->efId);
	  }
	return $return_array;
      }
  }


  function detachAllFindingsFromManagerFinding($manager_finding_id)
  {
    $query = "DELETE FROM $this->associacion_table_ WHERE mfId = '$manager_finding_id'";

    $result = $this->dbh_->query($query);
    
    if($this->dbh_->hasError())
      {
	return FALSE;
      }
    else
      {
	return TRUE;
      }     
  }


  function attachFindingsToManagerFinding($manager_finding_id, $evaluator_finding_ids)
  {
    if(is_array($evaluator_finding_ids))
      {
	$error_flag = 0;
	$now = date('YmdHms',time());

	while($current_evaluator_finding_id = array_pop($evaluator_finding_ids))
	  {
	    $some_id = $this->dbh_->getUniqueId();
	    $query = "INSERT INTO $this->associacion_table_ (aId, mfId, efId, aDate) VALUES ('$some_id', '$manager_finding_id', '$current_evaluator_finding_id', '$now')";

	    $result = $this->dbh_->query($query);
	    if($this->dbh_->hasError())
	      $error_flag = 1;
	  }
	if($error_flag)
	  return FALSE;
	else 
	  return TRUE;
	
      }
    else
      return FALSE;
  }


  function getRatingsForFinding($user_id, $finding_id)
  {
    $query = "SELECT scaleId, scaleValueId FROM $this->rating_table_ "
      ." WHERE uid = '$user_id' "
      ." AND fId = '$finding_id' ";
    
    $result = $this->dbh_->query($query);

    $return_array = array();

    if(($this->dbh_->hasError()) || ($result->numRows() < 1))
      {
	return FALSE;
      }
    else
      {
	while($row = $result->fetchRow())
	  {
	    $return_array[$row->scaleId] = $row->scaleValueId ;
	  }
	return $return_array;
      }
  }


  function removeRatings($user_id = null, $finding_id = null)
  {
    $del_query ="DELETE FROM $this->rating_table_ ";

    $where = Array();
    if(!is_null($user_id))
      $where[] = " uId = '$user_id' ";
    if(!is_null($finding_id))
      $where[] = " fId = '$finding_id' ";

    if(!empty($where))
      {
	$where = implode(" AND ", $where);
	$where = " WHERE " . $where;

	$del_query = $del_query . $where;

	$result = $this->dbh_->query($del_query);

	//	echo $del_query."<br>";

	if(!$this->dbh_->hasError())
	  return TRUE;
      }
    return FALSE;
  }


  function storeRatings($user_id, $ratings_array)
  {
    if( is_array($ratings_array) && sizeof($ratings_array) > 0 )
      {
	foreach($ratings_array as $finding_id => $finding_data)
	  {
	    // Cleanup DB first, so we do not need to distinguish between update and insert operations (simplicity rules ;-)
	    $this->removeRatings($user_id, $finding_id);
	    
	    foreach($finding_data as $scale_id => $scale_value_id)
	      {
		// Insert the data
		$ins_query = "INSERT INTO $this->rating_table_ "
		  ." (uId, fId, scaleId, scaleValueId) VALUES "
		  ." ('$user_id', '$finding_id' , '$scale_id', '$scale_value_id') ";
		
		$result = $this->dbh_->query($ins_query);
	      }
	  }
	return TRUE;
      }
    else
      {
	$this->error_ = "storeRatings(): Data is not in a valid format or has zero length";
	return FALSE;
      }
  }

  function deleteFindingData($project_id)
  {
    if(!empty($project_id))
      {
	$finding_ids = $this->getAllFindingIds($project_id);
	
	foreach($finding_ids as $current_finding_id)
	  {
	    $this->id_ = $current_finding_id;
	    $this->init();
	    
	    if($this->init_ok_)
	      {
		if($this->fManagerFinding == 'Y')
		  {
		    $this->detachAllFindingsFromManagerFinding($current_finding_id);
		    $this->removeRatings(null, $current_finding_id);
		  }
		$this->deleteData();
	      }
	  }
      }
  }
  


  function exportFindingData($project_id)
  {
    $return_array = Array();

    if(!empty($project_id))
      {
	// Export Findings
	$finding_ids = $this->getAllFindingIds($project_id);

	foreach($finding_ids as $current_finding_id)
	  {
	    $this->id_ = $current_finding_id;
	    $this->init();

	    
	    $return_array[]['data'] = $this->getData();
	  }


	// Export
      }
    return $return_array;
  }


  function exportFindingMapping($project_id)
  {
    $return_array = Array();

    if(!empty($project_id))
      {
	$finding_ids = $this->getAllFindingIds($project_id, null, null, 'ASC', null, 'Y');
	
	foreach($finding_ids as $current_finding_id)
	  {
	    $query = "SELECT * FROM $this->associacion_table_ WHERE mfId = '$current_finding_id'";
	    
	    $result = $this->dbh_->query($query);
	    
	    if( !$this->dbh_->hasError() && $result->numRows() > 0)
	      {
		while($result->fetchInto($row, DB_FETCHMODE_ASSOC))
		  {
		    $return_array[]['data'] = $row;
		  }
	      }
	  }
	
      }
    return $return_array;
  }

  function exportFindingRatings($project_id)
  {
    $return_array = Array();

    if(!empty($project_id))
      {
	$finding_ids = $this->getAllFindingIds($project_id, null, null, 'ASC', null, 'Y');
	
	foreach($finding_ids as $current_finding_id)
	  {
	    $query = "SELECT * FROM $this->rating_table_ WHERE fId = '$current_finding_id'";
	    
	    $result = $this->dbh_->query($query);
	    
	    if( !$this->dbh_->hasError() && $result->numRows() > 0)
	      {
		while($result->fetchInto($row, DB_FETCHMODE_ASSOC))
		  {
		    $return_array[]['data'] = $row;
		  }
	      }
	  }
	
      }
    return $return_array;
  }


  function findingMerged($fid = null)
  {
    if(is_null($fid))
      $fid = $this->fId;

    $query = "SELECT aId FROM $this->associacion_table_ WHERE efId = '$fid'";

    $result = $this->dbh_->query($query);
    if(!$this->dbh_->hasError() && $result->numRows() > 0)
      return TRUE;
    else
      {
	return FALSE;
      }
  }


}

?>