<?php
define('DBOBJECT_LOADED', TRUE);

  /**
   * Abstract Class for DB objects
   *
   *
   * @author Martin Loitzl, martin@loitzl.com
   *
   */

class DBObject
{

  /**
   * Set this TRUE if you want to debug Database queries
   * Caution: May result in lots of output!
   *
   * @access private
   * @var string
   */
  var $query_debug_ = FALSE;

  /**
   * Database Handle
   *
   * @access private
   * @var    object
   */
  var $dbh_ = null;

  /**
   * The name of the table with the project data
   *
   * @access private
   * @var    string
   */
  var $table_ = null;

  /**
   * The id of the Object
   *
   * @access private
   * @var    int
   */
  var $id_ = null;

  /**
   * The fields of the db table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array();
  
  /**
   * The Primary Key of the db table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = null;

  /**
   * Flag if Project with id was initialized correctly
   *
   * @access protected
   * @var    string
   */
  var $init_ok_ = FALSE;

  /**
   * Holds an associative array with the  table data
   *
   * @access private
   * @var    array
   */
  var $data_array_ = array();


  /**
   * Flag if Object has an error
   *
   * @access private
   * @var    boolean
   */
  var $error_ = FALSE;

  /**
   * Constructor
   *
   *
   *
   * @param int table to instantiate
   * @param object Database handle
   */

  function DBObject($id = null, & $dbh)
  {
    // This has to be set in child class
    /*    global $TABLE;

    if(isset($TABLE)) $this->table_ = TABLE;*/


    $this->dbh_ = $dbh;

    if($id !== 0)
      $this->id_ = $id;

    if(!is_null($this->id_)) $this->init();
  }


 /**
   * Initialisaion of object
   *
   * Sets the init_ok_ property according to the success
   * 
   */

  function init()
  {
    if($this->getData() != FALSE)
      $this->init_ok_ = TRUE;
    else
      $this->init_ok_ = FALSE;
  }

 /**
   * Sets the Data stored in Database as member variables
   * and returns Data as associative array
   *
   * Sets the init_ok_ property according to the success
   *
   * @return mixed associative array or FALSE
   */

  function getData()
  {
    $fields = $this->getFieldList();
    $fields_string = implode(',', $fields);

    if(!is_null($this->id_))
      {
	$query = "SELECT $fields_string FROM $this->table_ WHERE $this->table_primary_key_ = '$this->id_'";

	if($this->query_debug_) echo $query."<br/>";

	$result = $this->dbh_->query($query);

	if( (!$this->dbh_->hasError()) && ($result->numRows() > 0) )
	  {
	    $row = $result->fetchRow();

	    foreach($fields as $f)
	      {
		//		$this->$f = htmlspecialchars(stripslashes($row->$f));
		//		$this->data_array_[$f] = htmlspecialchars(stripslashes($row->$f));
		$this->$f = htmlspecialchars($row->$f);
		$this->data_array_[$f] = htmlspecialchars($row->$f);
	      }
	    return $this->data_array_;
	  }
	elseif($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
	else
	  {
	    $this->setError('Database returned a zero length result');
	    return FALSE;
	  }
      }
    else
      {
	$this->setError('No Primary Key given');
	return FALSE;
      }
  }

 /**
  * Adds Data according to the data in the passed array
  *
  *
  *
  * @param array An associative Array with the data, indices are the fields defined in class
  * @return mixed associative array or FALSE
  */
  
  function addData($data = null)
  {
    $fields = $this->table_fields_;
    $fields_string = implode(',', $this->getFieldList());

    $value_list = array();
    
    if($data != null)
      {
	while (list ($k, $v) = each($fields))
	  {
	    if(!strcmp($v, 'text'))
	      {
		$value_list[] = $this->dbh_->quote($data[$k]);
	      }
	    else
	      {
		$value_list[] = $data[$k];
	      }
	  }
	$value_string = implode(',', $value_list);

	$query = "INSERT INTO $this->table_ ($fields_string) VALUES ($value_string)";

	if($this->query_debug_)	echo $query."<br/>";

	$result = $this->dbh_->query($query);

	if($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
	else return TRUE;
      }
    else
     {
	$this->setError('No data given');
	return FALSE;
      }
  }



 /**
  * Updates Data
  *
  * Its up to the programmer to check, if anyone changes Data he does'nt own.
  *
  * @param array Associative Array with the data
  * @return boolean the result of the query or FALSE
  */

  function updateData($data = null)
  {
    if( ($data != null) )
      {
	$fields = $this->table_fields_;

	$key_value_pairs = $this->makeUpdateKeyValuePairs($fields, $data);

	// TODO: check WHERE clause. is it possible to change other cats? --> needs a seperate instance
	$query = "UPDATE $this->table_ SET $key_value_pairs WHERE $this->table_primary_key_  = '".$data[$this->table_primary_key_]."'";

	if($this->query_debug_) echo $query."<br/>";

	$result = $this->dbh_->query($query);
	if($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
	else
	  return TRUE;
      }
    else
      {
	$this->setError('No data given');
	return FALSE;
      }
  }

  /**
   * Deletes Data
   *
   * Deletes the Data with the given id
   *
   * @param int Id of the Data
   * @return boolean TRUE on success
   */
  function deleteData($id = null)
  {
    if($this->init_ok_ && is_null($id))
      {
	$id = $this->id_;
      }
    if(!is_null($id))
      {
	$query = "DELETE FROM $this->table_ WHERE $this->table_primary_key_  = '$id'";
	
	if($this->query_debug_) echo $query;

	$result = $this->dbh_->query($query);
	if($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
	else
	  return TRUE;
      }
    else
      {
	$this->setError('No Id given');
	return FALSE;
      }
  }

 /**
  * Returns the sql Key Value Pairs for the update operation
  *
  * Ignores empty fields, so no data can be overwritten
  *
  *
  * @param array Array of tablefields
  * @param array Associative Array with the data
  * @return mixed string with the key value sql part or FALSE
  */
 function makeUpdateKeyValuePairs($fields = null, $data = null)
  {
    $set_values = array();

    if(($fields != null) && ($data != null))
    {
      while(list($k, $v) = each($fields))
	{
	  if(!strcmp($v, 'text'))
	    {
	      // ignore empty fields, cause empty fields could overwrite data!
	      // TODO: Check if it has any side effects
	      //	      if(isset($data[$k]) && !empty($data[$k]))
	      if(isset($data[$k]))
		{
		  $v = $this->dbh_->quote($data[$k]);
		  $set_values[] = "$k = $v";
		}
	    }
	  else
	    {
	      // ignore empty fields, cause empty fields could overwrite data!
	      if(isset($data[$k]) && !empty($data[$k]))
		{
		  $set_values[] = "$k = $data[$k]";
		}
	    }
	}
     return implode(', ', $set_values);
    }
    else return FALSE;
  }

  /**
   * Returns the List of fields the Project has
   *
   *
   * @return boolean Active flag
   */
  function getFieldList()
  {
    return array_keys($this->table_fields_);
  }

  // Error Handling stuff
  // TODO: Documentation
  function setError($msg = null) 
  {
    global $TABLE_DOES_NOT_EXIST, $TABLE_UNKNOWN_ERROR;
    $this->error_ = $msg;
    
    if(strpos($msg, 'no such table'))
      {
	$this->error_type_ = $TABLE_DOES_NOT_EXIST;
      }
    else 
      {
	$this->error_type_ = $TABLE_UNKNOWN_ERROR;
      }
  }

  function hasError()
  {
    return $this->isError();
  }

  function isError()
  {
    return (!empty($this->error_)) ? TRUE : FALSE ;
  }

 function getError()
  {
    return $this->error_;
  }

  function resetError()
  {
    $this->error_ = null;
    $this->error_type_ = null;
  }

  function toggleQueryDebug()
  {
    $this->query_debug_ = !$this->query_debug_;
  }

}
?>