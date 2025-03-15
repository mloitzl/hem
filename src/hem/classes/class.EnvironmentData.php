<?php
class EnvironmentData extends DBObject
{

  /**
   * The name of the table with the environment attribute data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'environment_data';

  /**
   * The fields of the environment attribute data table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'envDataId' => 'text',
			     'pId' => 'text',
			     'envAttributeId' => 'text',
			     'envAttributeData' => 'text',
			     'envDataOwnerId' => 'text',
			     );
  
  /**
   * The Primary Key of the environment attribute data table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'envDataId';
  var $env_attribute_id_ = 'envAttributeId';

  
 /**#@+
  * Properties of the object defined @see $table_fields_
  *
  * @var string
  */
  var $envDataId = null;
  var $pId = null;
  var $envAttributeId = null;
  var $envAttributeData = null;
  var $envDataOwnerId = null;
 /**#@-*/


  function EnvironmentData($env_data_id = null, & $dbh)
  {
    global $ENVIRONMENT_DATA_TABLE;

    if(isset($ENVIRONMENT_DATA_TABLE)) $this->table_ = $ENVIRONMENT_DATA_TABLE;

    DBObject::DBObject($env_data_id, $dbh);
  }

  function getEnvironmentDataIds($user_id = null, $project_id = null)
  {
    $query = "SELECT $this->table_primary_key_, $this->env_attribute_id_  FROM $this->table_ ";

    $where = Array();
    
    if(!is_null($user_id))
      array_push($where, "envDataOwnerId = '".$user_id."'");
    if(!is_null($project_id))
      array_push($where, "pId = '".$project_id."'");

    if(!empty($where))
      $where = " WHERE " . implode(" AND ", $where);
    else
      $where = " WHERE 1";
    
    $query.= $where;

    //    echo $query."<BR>";

    $result = $this->dbh_->query($query);

    $return_array = array();
    
    if($this->dbh_->hasError())
      {
	$this->setError($this->dbh_->getError());
	$this->dbh_->resetError();
	return $return_array;
      }
    else
      {
	if($result->numRows() > 0)
	  {
	    while($row = $result->fetchRow())
	      {
		//		array_push($return_array, $row->{$this->table_primary_key_});
		$return_array[] = $row->{$this->table_primary_key_};
	      }
	    return $return_array;
	  }
	else
	  {
	    $this->setError('No Environments found');
	    return $return_array;
	  }
      }
  }


  function getAttributeDataForUserAndProject($user_id = null, $project_id = null)
  {    
    // deactivated 'cause EnvironmentData::getEnvironmentDataIds() is now more flexible
    if(1 || !is_null($user_id) && !is_null($project_id))
      {
	$attribute_data = array();
	$attribute_ids = $this->getEnvironmentDataIds($user_id, $project_id);

	foreach($attribute_ids as $current_attribute_id)
	  {
	    $this->id_ = $current_attribute_id;
	    $this->init();
	    $attribute_data[$this->envAttributeId] = $this->getData();
	  }

	return $attribute_data;

	/* Another way to implement this:
	for($i=0; $i< sizeof($attribute_ids); $i++)
	  {
	    $this->id_ = $attribute_ids[$i];
	    $this->init();
	    $attribute_data[$this->envAttributeId] = $this->data_array_;
	  }
	*/
      }
    else
      return FALSE;
  }


  function storeAttributeDataArray($data_array = null)
  {
    if(!is_null($data_array))
      {
	$data_id_array = Array();
	foreach($data_array as $id => $attribute_data)
	  {
	    $data_id_array[] = $attribute_data['envDataId'];
	  }
	$this->removeAttributeData($data_id_array);
	foreach($data_array as $data_id => $attribute_data)
	  {
	    $this->addData($attribute_data);
	  }
      }
    else
      {
	$this->setError("storeAttributeDataArray(): received null- Array");
	return FALSE;
      }
  }
					    

  function removeAttributeData($id_array)
  {
    foreach($id_array as $value)
      {
	//	echo "Deleteing id: $value";
	$this->deleteData($value);
      }

  }

  function getAttributeDataForProject($project_id)
  {
    $return_array = Array();

    $attribute_ids = $this->getEnvironmentDataIds(null, $project_id);
    
    foreach($attribute_ids as $current_attribute_id)
      {
	$this->id_ = $current_attribute_id;
	$this->init();
	
	$return_array[] = $this->getData();
      }
    
    return $return_array;
  }
  

  function exportEnvironmentAttributeData($project_id)
  {
    $return_array = Array();

    $attribute_data = $this->getAttributeDataForProject($project_id);

    if(!empty($attribute_data) && is_array($attribute_data))
      {
	foreach($attribute_data as $current_data_item)
	  {
	    $return_array[]['data'] = $current_data_item;
	  }
      }

    return $return_array;
  }


  function deleteAttributeData($project_id)
  {
    if(!empty($project_id))
      {
	$query = "DELETE FROM $this->table_ WHERE pId = '$project_id'";
	
	$result = $this->dbh_->query($query);
	
	if(!$this->dbh_->hasError())
	  return TRUE;
      }
    else
      return FALSE;
  }


}
?>
