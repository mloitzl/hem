<?php
define('USER_CLASS_LOADED', TRUE);

class User extends DBObject
{

  var $table_ = null;

  var $id_ = null;

  var $table_fields_ = array(
			     'auth_user_id' => 'text',
			     'first_name' => 'text',
			     'last_name' => 'text',
			     'email' => 'text',
			     'street' => 'text',
			     'no' => 'text',
			     'city' => 'text',
			     'zip' => 'text',
			     'country' => 'text',
			     'phone' => 'text',
			     'comment' => 'text'
			     );

  var $table_primary_key_ = 'auth_user_id';
  
  function User($uid = null, & $dbh)
  {
    global $USER_ATTR_TBL;
    global $USER_PREF_TBL, $TEMPLATE_PREF_ID, $LANGUAGE_PREF_ID;

    if(isset($USER_ATTR_TBL)) $this->table_ = $USER_ATTR_TBL;
    if(!is_null($uid)) $this->id_ = $uid;
    //    $this->user_attr_table_ = $USER_ATTR_TBL;
    $this->user_pref_table_ = $USER_PREF_TBL;
    $this->template_pref_id_ = $TEMPLATE_PREF_ID;
    $this->language_pref_id_ = $LANGUAGE_PREF_ID;

    $this->user_id_ = $uid;
    //    $this->dbh_ = $dbh;

    //    $this->user_attr_table_primary_key_ = 'auth_user_id';

    /*    $this->user_tbl_fields_ = array(
				    'auth_user_id' => 'text',
				    'first_name' => 'text',
				    'last_name' => 'text',
				    'email' => 'text',
				    'street' => 'text',
				    'no' => 'text',
				    'city' => 'text',
				    'zip' => 'text',
				    'country' => 'text',
				    'phone' => 'text',
				    'comment' => 'text'
				    );*/
    $this->init_ok_ = FALSE;

    //    if($this->user_id_ != null ) $this->init();
    DBObject::DBObject($uid, $dbh);

    // setup User
    // TODO: setUserName, email,...

    // TODO: set Users Theme
  }


  /*  function init()
  {
    $this->getThemeID();
    if($this->getUserdata() == TRUE)
      $this->init_ok_ = TRUE;
      }*/
  
  /*  function getUserFieldList()
  {
    return array_keys($this->user_tbl_fields_);
    }*/

  
  /*  function makeUpdateKeyValuePairs($fields = null, $data = null)
  {
    $set_values = array();
    
    if(($fields != null) && ($data != null))
      {
	while(list($k, $v) = each($fields))
	  {
	    if(!strcmp($v, 'text'))
	      {
		// ignore empty fields, cause empty fields could overwrite data!
		if(isset($data[$k]) && !empty($data[$k]))
		  {
		    $v = $this->dbh_->quote(addslashes($data[$k]));
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
    }*/
  
  /*  function getUserData()
  {
    return $this->getData();
    }*/
  
  /*  function getUserData()
  {
    $fields = $this->getUserFieldList();
    $fields_string = implode(',', $fields);

    if($this->user_id_ != null)
      {
	$query = "SELECT $fields_string FROM $this->user_attr_table_ WHERE auth_user_id = '$this->user_id_'";

	$result = $this->dbh_->query($query);

	if( !$this->dbh_->hasError() && ($result->numRows() > 0) )
	  {
	    $row = $result->fetchRow();

	    foreach($fields as $f)
	      {
		$this->$f = $row->$f;
		$this->user_data_array_[$f] = $row->$f;
	      }
	    return TRUE;
	  }
	return FALSE;
      }
    return FALSE;
    }*/

  /*  function addUserData($data = null)
  {
    $fields = $this->user_tbl_fields_;
    $fields_string = implode(',', $this->getUserFieldList());

    $value_list = array();
    
    if($data != null)
      {
	while (list ($k, $v) = each($fields))
	  {
	    if(!strcmp($v, 'text'))
	      {
		$value_list[] = $this->dbh_->quote(addslashes($data[$k]));
	      }
	    else
	      {
		$value_list[] = $data[$k];
	      }
	  }
	$value_string = implode(',', $value_list);

	$query = "INSERT INTO $this->user_attr_table_ ($fields_string) VALUES ($value_string)";

	$result = $this->dbh_->query($query);

	if($this->dbh_->hasError())
	  return FALSE;
	else return TRUE;

	echo $query;
      }
    else return FALSE;
    }*/

  /*  function updateUserData($data = null)
  {
    //    if( ($data != null) && ($this->user_id_ == $data['auth_user_id']) )
    if( ($data != null) )
      {
	$fields = $this->user_tbl_fields_;

	$key_value_pairs = $this->makeUpdateKeyValuePairs($fields, $data);

	//	$query = "UPDATE $this->user_attr_table_ SET $key_value_pairs WHERE auth_user_id = '$this->user_id_'";
	// fix for admin changes: Right has to be already checked in calling object
	$query = "UPDATE $this->user_attr_table_ SET $key_value_pairs WHERE auth_user_id = '".$data['auth_user_id']."'";

	$result = $this->dbh_->query($query);

	if($this->dbh_->hasError())
	  return FALSE;
	else return TRUE;
      }
    // TODO: Admin changes here!
    else
      return FALSE;
      }*/


  // Overwritten from DBObject! cause we've more than one table to cleanup
  function deleteUserData($user_id = null)
  {
    if(!is_null($user_id))
      {
	$query = "DELETE FROM $this->table_ WHERE $this->table_primary_key_ ='$user_id'";
	$result = $this->dbh_->query($query);
	if($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
	$query = "DELETE FROM $this->user_pref_table_ WHERE $this->user_attr_table_primary_key_ ='$user_id'";
	$result = $this->dbh_->query($query);
	if($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
      }
  }


  function getThemeID()
  {
     // check if already set, so no db query needed
    if(!empty($this->theme_id_)) 
      {
	return $this->theme_id_;
      }
    else 
      {
	$query = "SELECT value FROM $this->user_pref_table_ WHERE ".
	  "pref_id = $this->template_pref_id_ AND ".
	  "auth_user_id = ".$this->dbh_->quote($this->user_id_)."";
	
	$result = $this->dbh_->query($query);
	if($result != null && $result->numRows() > 0) 
	  {
	    $row = $result->fetchRow();
	    $this->theme_id_ = $row->value;
	    return $this->theme_id_;
	  }
	return FALSE;
      }
  }

  function setThemeID($theme_id)
  {
    if(($this->getThemeID() == FALSE) && !empty($this->user_id_ )) 
      {
	$query = "INSERT INTO $this->user_pref_table_ ".
	  "(auth_user_id, pref_id, value) ".
	  "VALUES ".
	  "(".
	  $this->dbh_->quote($this->user_id_).", ".
	  $this->template_pref_id_.", ".
	  $this->dbh_->quote($theme_id).
	  ")";
	//	echo $query;
      }
    else if($this->getThemeID() != $theme_id && !empty($this->user_id_ ))
      {
	$query = "UPDATE $this->user_pref_table_ SET value = ".
	  $this->dbh_->quote($theme_id) ." WHERE ".
	  "auth_user_id = ".$this->dbh_->quote($this->user_id_)." AND ".
	  "pref_id = ".$this->template_pref_id_;
	//	echo $query;
      }
    else
      {
	$query = "";
      }
 
    if (!empty($query))
      {
	$result = $this->dbh_->query($query);
	if($result == TRUE) return TRUE;
	else return FALSE;
      }
    else
      {
	return FALSE;
      }
  }

  function getLanguageID()
  {
    // check if already set, so no db query needed
    if(!empty($this->language_id_)) 
      {
	return $this->language_id_;
      }
    else 
      {
	$query = "SELECT value FROM $this->user_pref_table_ WHERE ".
	  "pref_id = $this->language_pref_id_ AND ".
	  "auth_user_id = ".$this->dbh_->quote($this->user_id_)."";
	
	$result = $this->dbh_->query($query);
	if($result != null && $result->numRows() > 0) 
	  {
	    $row = $result->fetchRow();
	    $this->language_id_ = $row->value;

	    return $this->language_id_;
	  }
	return FALSE;
      }
  }

  function setLanguageID($language_id)
  {
    // TODO: Very very weird: if works only with '0', not with 0, nore FALSE!!!
    // But it works with setThemeId() ?!?!?!?
    if(($this->getLanguageID() == '0') && !empty($this->user_id_ )) 
      {
	$query = "INSERT INTO $this->user_pref_table_ ".
	  "(auth_user_id, pref_id, value) ".
	  "VALUES ".
	  "(".
	  $this->dbh_->quote($this->user_id_).", ".
	  $this->language_pref_id_.", ".
	  $this->dbh_->quote($language_id).
	  ")";
	//	echo $query;
      }
    else if($this->getLanguageID() != $language_id && !empty($this->user_id_ ))
      {
	$query = "UPDATE $this->user_pref_table_ SET value = ".
	  $this->dbh_->quote($language_id) ." WHERE ".
	  "auth_user_id = ".$this->dbh_->quote($this->user_id_)." AND ".
	  "pref_id = ".$this->language_pref_id_;
	//	echo $query;
      }
    else
      {
	$query = "";
      }
 
    if (!empty($query))
      {
	$result = $this->dbh_->query($query);
	if($result == TRUE) return TRUE;
	else return FALSE;
      }
    else
      {
	return FALSE;
      }
  }


  function getEmail()
  {
    return (isset($this->email)) ? $this->email : '';
  }

  /**
   * Returns all User Ids
   *
   * Returns an array of user ids sorted by lastname
   *
   * @return array User Ids
   */
  function getAllUserIds()
  {
    $query = "SELECT $this->table_primary_key_ FROM $this->table_ ORDER BY last_name";

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
	    $this->setError('No Users found');
	    return FALSE;
	  }
      }
  }

  function exportUserData()
  {
    $user_data = $this->getData();

    $return_array[0]['data'] = $user_data;

    return $return_array;
  }




  function setEmail()
  {

  }


  function getUserName()
  {

  }


  function getRealName()
  {

  }



  /*  function setError($msg = null) 
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
  
  function isErrorType($type = null) 
  {
    return ($this->error_type_ == $type) ? TRUE : FALSE ;
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
  */


  
}


?>