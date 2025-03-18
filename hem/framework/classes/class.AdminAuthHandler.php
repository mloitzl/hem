<?php
define('ADMIN_AUTH_HANDLER_LOADED', TRUE);


// Include the API we're abstracting here
require_once 'LiveUser/Admin/Perm/Container/DB_Medium.php';
require_once 'LiveUser/Admin/Auth/Container/DB.php';
require_once 'DB.php';

class AdminAuthHandler 
{

  var $version_ = "1.0.0";


  function AdminAuthHandler($conf)
  {
   
    //    $this->conf_ = $conf;
    //    print_r($this->conf_);

    $this->init($conf);
    
  }

  // TODO: change setting 					    'authTable'     => 'liveuser_users',

  function init($conf)
  {
    global $APP_AUTH_DSN, $DB_PREFIX;

    $this->complex_conf = 
      array(
	    'autoInit' => false,
	    'session'  => array(
				'name'     => $conf['auth_session_name'],
				'varname'  => 'ludata'
				),
	    'login' => array(
			     'method'   => 'post',
			     'username' => 'handle',
			     'password' => 'passwd',
			     'force'    => false,
			     'function' => '',
			     'remember' => 'rememberMe'
			     ),
	    'logout' => array(
			      'trigger'  => 'logout',
			      'redirect' => $conf['auth_exit_page'],
			      'destroy'  => true,
			      'method' => 'get',
			      'function' => ''
			      ),
	    'authContainers' => array(
				      array(
					    'type'          => 'DB',
					    'name'          => 'DB_Local',
					    'loginTimeout'  => 0,
					    'expireTime'    => 3600,
					    'idleTime'      => 1800,
					    'dsn'           => $conf['auth_dsn'],
					    'allowDuplicateHandles' => 0,
					    'authTable'     => $DB_PREFIX."liveuser_users",
    					    'authTableCols' => array(
								     'required' => array(
											 'auth_user_id' => array('type' => 'text', 'name' => 'auth_user_id'),
											 'handle'       => array('type' => 'text', 'name' => 'handle'),
											 'passwd'       => array('type' => 'text', 'name' => 'passwd')
											 ),
								     'optional' => array(
											 'lastlogin'      => array('type' => 'timestamp', 'name' => 'lastlogin'),
											 'is_active'      => array('type' => 'boolean',   'name' => 'is_active'),
											 'owner_user_id'  => array('type' => 'integer',   'name' => 'owner_user_id'),
											 'owner_group_id' => array('type' => 'integer',   'name' => 'owner_group_id')
											 )
								     )
					    
					    )
				      ),
	    'permContainer' => array(
				     'dsn'        => $conf['auth_dsn'],
				     'type'       => 'DB_Medium',
				     'prefix'     => $DB_PREFIX.'liveuser_'
				     )
	    );

    $connect_options = array(
			     'dsn' => $conf['auth_dsn']
			     );


    $this->perm_ = & new LiveUser_Admin_Perm_Container_DB_Medium($connect_options, $this->complex_conf);
    $this->auth_ = & new LiveUser_Admin_Auth_Container_DB($connect_options, $this->complex_conf['authContainers'][0]);

    $this->perm_->setCurrentLanguage('en');
  }


  function addUser($uid = '', $handle = '', $password = null, $active = false, $auth_container = null)
  {
    if(is_null($auth_container)) $auth_container = $this->complex_conf['authContainers'][0]['name'];

    if(!empty($uid) && !empty($handle) && is_object($this->auth_) && is_object($this->perm_))
      {
	$optional_fields = array(
				 'is_active' => $active
				 );
	$user_auth_id = $this->auth_->addUser($handle, $password, $optional_fields, null, $uid);
	
	if(PEAR::isError($user_auth_id))
	  {
	    return FALSE;
	    echo "<pre>";
	    print_r($user_auth_id);
	    echo "</pre>";
	  }
	else 
	  {
	    $user_perm_id = $this->perm_->addUser($user_auth_id, $auth_container,  LIVEUSER_USER_TYPE_ID);
	    
	    if(PEAR::isError($user_perm_id))
	      {
		return FALSE;
		echo "<pre>";
		print_r($user_perm_id);
		echo "</pre>";
	      }
	    else return $user_auth_id;
	  }
      }
    else return FALSE; 
  }



  function addPermUser($user_auth_id)
  {
    $auth_container = $this->complex_conf['authContainers'][0]['name'];
    $this->perm_->addUser($user_auth_id, $auth_container,  LIVEUSER_USER_TYPE_ID);
  }


  function removeUser($auth_user_id)
  {
    if (is_object($this->auth_) && is_object($this->perm_)) {
      $this->removeUserFromAllGroups($auth_user_id);
      
      $permId = $this->getPermUserId($auth_user_id);

      $result = $this->auth_->removeUser($auth_user_id);
      
      if (PEAR::isError($result)) {
	return FALSE;
	echo "<pre>";
	print_r($result);
	echo "</pre>";
      }
      
      return $this->perm_->removeUser($permId);
    }
    return FALSE;
  }

  /**
   * Gets all User Data from Auth Part of LiveUser
   *
   * Never use this if auth user does'nt exist, returns all users in this case
   *
   * $filters = array(
   *        'email' => array('name' => 'email', 'op' => '=', 'value' => 'fleh@example.comDBUpdated', 'cond' => 'AND'),
   *        'name'  => array('name' => 'name',  'op' => '=', 'value' => 'asdfDBUpdated', 'cond' => '')
   *
   *
   * @param   string  user id from Liveuser (auth_user_id)
   */
  function getUserData($user_id = null, $user_name = null, $custom_fields = null)
  {
    // getUsers($filters = array(), $order = null, $rekey = false)
    $filter = array();
    if(!is_null($user_id) && !is_null($user_name))
      {
	$filter = array(
			'auth_user_id' => array('name' => 'auth_user_id', 'op' => '=', 'value' => $user_id, 'cond' => 'AND', 'type' => ''),
			'handle' => array('name' => 'handle', 'op' => '=', 'value' => $user_name, 'cond' => '')
			);
      }
    elseif(!is_null($user_id))
      {	
	$filter = array(
			'auth_user_id' => array('name' => 'auth_user_id', 'op' => '=', 'value' => $user_id, 'cond' => '', 'type' => ''),
			);
      }
    elseif(!is_null($user_name))
      {
	$filter = array(
			'handle' => array('name' => 'handle', 'op' => '=', 'value' => $user_name, 'cond' => '', 'type' => '')
			);
      }

    return $this->auth_->getUsers($filter, $custom_fields);
  }


  function userIdExists($user_id = null)
  {
    if(!is_null($user_id))
      {
	$user_data = $this->getUserdata($user_id);
	if(empty($user_data))
	  return FALSE;
	else
	  return TRUE;
      }
    else return FALSE;
  }

  function userNameExists($user_name = null)
  {
    if(!is_null($user_name))
      {
	$user_data = $this->getUserdata(null, $user_name);
	//	print_r($user_data);
	if(empty($user_data))
	  return FALSE;
	else
	  return TRUE;
      }
    else return FALSE;
  }


  function isActive($user_id = null)
  {
    if(!is_null($user_id))
      {
	$user_data = $this->getUserData($user_id);
	
	if(!PEAR::isError($user_data) && !empty($user_data) && $user_data[0]['is_active'] == TRUE)
	  return TRUE;
	else if(PEAR::isError($user_data))
	  {
	    echo "<pre>";
	    print_r($user_data);
	    echo "</pre>";
	    return FALSE;
	  }
	else
	  return FALSE;
      }
  }

  function getUserName($user_id = null)
  {
    if(is_null($user_id))
      return FALSE;
    else
      {
	$user_data = $this->getUserData($user_id);
	return (!empty($user_data)) ? $user_data[0]['handle'] : FALSE;
      }
  }


  /**
   * Gets all Groups the User is in
   *
   *
   * @param   string  user id from Liveuser (auth_user_id)
   */
  function getGroups($user_id = null, $auth_container = null)
  {
    
    if(is_null($auth_container)) $auth_container = $this->complex_conf['authContainers'][0]['name'];

    return (!is_null($user_id)) ? 
      $this->perm_->getGroups(
			      array(
				    'where_user_id' => $this->perm_->getPermUserId($user_id, $auth_container)
				    )
			      ) :
      FALSE;
  }

  /**
   * Adds a group
   *
   *
   * @param   string  name of group
   * @param   string  description of group
   * @param   boolean  active flag
   */
  function addGroup($group_name, $group_description, $active = FALSE)
  {
    if (DB::isError($this->perm_))
      {    
	echo $status->getMessage();
	
      }
    return $this->perm_->addGroup($group_name, $group_description, $active);
  }

  /**
   *  Removes User from all groups and adds him to all groups until
   *  the one passed to the function
   *
   *  @param   int  user id from Liveuser (auth_user_id)
   *  @param   array  group ids to add user to
   *
   */
  function updateGroupMembership($user_id = null, $group_ids = null, $auth_container = null )
  {
    if(is_null($auth_container)) $auth_container = $this->complex_conf['authContainers'][0]['name'];

    if(!is_null($user_id) && !is_null($group_ids))
      {
	$res = $this->removeUserFromAllGroups($user_id);
	if(!$res)
	  return FALSE;
	else
	  {
	    while($current_group_id = array_pop($group_ids))
	      {
		$res = $this->perm_->addUserToGroup($this->perm_->getPermUserId($user_id, $auth_container), $current_group_id);
		if(PEAR::isError($res))
		  return FALSE;
	      }
	    return TRUE;
	  }
      }
    else
      {
	return FALSE;
      }
  }
  
  /**
   *  Updates the users password, and checks the old one
   *
   *  TODO: Old Password Check not yet implemented
   *
   *  @param   string  user id from LiveUser (auth_user_id)
   *  @param   string  old password
   *  @param   string  new password
   *  @param   string  new password retyped
   */
  function updatePassword($user_id, $old_pw, $new_pw, $retyped_pw)
  {
    $user_data = $this->getUserData($user_id);
    $user_name = $user_data[0]['handle'];
    
    if( $new_pw != $retyped_pw )
      {
	return FALSE;
      }
    else
      {
	if(!is_null($old_pw))
	  {
	    // TODO: check if password matches the one in database
	    if(1)
	      {
		$result = $this->auth_->updateUser($user_id, null, $new_pw);
		if(PEAR::isError($result))
		  return FALSE;
		else
		  return TRUE;
	      }
	    else
	      {
		return FALSE;
	      }
	  }
	else
	  {
	    // is an admin change, so just do it
	    $result = $this->auth_->updateUser($user_id, null, $new_pw);
	    if(PEAR::isError($result))
	      return FALSE;
	    else
	      return TRUE;
	  }
      }
    return TRUE;
  }


  /**
   *  Updates the username (handle from LiveUser)
   *
   *  @param   string  user id from LiveUser (auth_user_id)
   *  @param   string  user name from LiveUser (handle)
   *  @param   string  new password
   *  @param   string  new password retyped
   */
  function updateUserName($user_id, $user_name, $active_flag = null)
  {
    if(!is_null($active_flag) && $active_flag == TRUE)
      $opt_fields = array('is_active' => '1');
    elseif(!is_null($active_flag) && $active_flag == FALSE)
      $opt_fields = array('is_active' => '0');
    else
      $opt_fields = array();
    if(PEAR::isError($this->auth_->updateUser($user_id, $user_name, null, $opt_fields)))
      return FALSE;
    else
      return TRUE;
  }


  /**
   * Returns all Auth Users (LiveUser)
   *
   * Returns all User ids, that are active, or not
   *
   * @return array User ids and user_names
   */
  function getAllUsers()
  {
    $auth_users = $this->auth_->getUsers();
    /*
     $text.= "<pre>";
     $text.= var_export($auth_users, TRUE);
     $text.= "</pre>";
     echo $text;
     $text = '';
     echo "Size of auth_users array: ".sizeof($auth_users)."<br/>";
    */
    $i=0;
    while($i < sizeof($auth_users))
      {
	$return_array[$i]['user_id'] = $auth_users[$i]['auth_user_id'];
	$return_array[$i]['user_name'] = $auth_users[$i]['handle'];

	$i++;
      }
    return $return_array;
  }


  function removeUserFromAllGroups($user_id = null, $auth_container = null )
  {
    if(is_null($auth_container)) $auth_container = $this->complex_conf['authContainers'][0]['name'];

    if(!is_null($user_id))
      {
    	$res = $this->perm_->removeUserFromGroup($this->perm_->getPermUserId($user_id, $auth_container), null);
	if(PEAR::isError($res))
	  return FALSE;
	else
	  return TRUE;
      }
    else
      return FALSE;
  }

  function getAuthUserId($perm_uid)
  {
    if(is_object($this->perm_))
      {
	$auth_user_data = $this->perm_->getAuthUserId($perm_uid);
	return $auth_user_data['auth_user_id'];
      }
    else
      return FALSE;
  }

  function getPermUserId($auth_uid)
  {
    if(is_object($this->perm_))
      return $this->perm_->getPermUserId($auth_uid,  $this->complex_conf['authContainers'][0]['name']);
    else
      return FALSE;
  }


  /**
   * Updates the groups that have the given right
   *
   *
   * @param array array with group ids
   * @param int The right to grant
   * @return boolean TRUE if everything went well
   */
  function updateGroupRights($group_ids, $right_id = null)
  {
    $error = FALSE;
    if(!is_null($right_id) && is_array($group_ids))
      {
	   
	$groups_to_reset = $this->getGroupsWithRight($right_id);

	while($current_reset_group_id = array_pop($groups_to_reset))
	  {
	    $result = $this->perm_->revokeGroupRight($current_reset_group_id, $right_id);
	    if(PEAR::isError($result))
	      {
		$error = TRUE;
	      }   
	  }

	while($current_group_id = array_pop($group_ids))
	  {
	    $result = $this->perm_->grantGroupRight($current_group_id, $right_id);
	    
	    if(PEAR::isError($result))
	      {
		$error = TRUE;
	      }
	  }
	if(!$error)
	  return TRUE;
	else
	  return FALSE;
      }
    return FALSE;
  }


  function hasGroupRight($group_id, $right_id)
  {
    $groups_with_right = $this->getGroupsWithRight($right_id);

    if($groups_with_right)
      {
	while($current_group = array_pop($groups_with_right))
	  {
	    if($current_group == $group_id)
	      return TRUE;
	    
	  }
      }

    return FALSE;
  }


  function getGroupsWithRight($right_id)
  {
    $groups = $this->perm_->getGroups(array(
				     'with_rights' => "Y"
				     )
			       );

    $return_groups = array();
    while($current_group = array_pop($groups))
      {
	if(!empty($current_group['rights']))
	  {
	    while($current_right = array_pop($current_group['rights']))
	      {
		//		echo "<pre>";
		//		print_r($current_right);
		//		echo "</pre>";
		if($current_right['right_id'] == $right_id)
		  array_push($return_groups, $current_group['group_id']);
	      }
	  }
      }
    return (!empty($return_groups)) ? $return_groups : FALSE;
  }


  // Helper functions -> thumb output
  function listGroups()
  {
    $groups = $this->perm_->getGroups(array(
				     'where_is_active' => "'Y'"
				     )
			       );

    $text = '';
    $text.= "<pre>Groups";
    $text.= var_export($groups, TRUE);
    $text.= "</pre>";
    
    return $text;

  }

  function listRights()
  {
    echo "<pre>";
    //    print_r($this->perm_);
    echo "</pre>";


    $rights = $this->perm_->getRights();

    $text = '';
    $text.= "<pre>Rights";
    $text.= var_export($rights, TRUE);
    $text.= "</pre>";
    
    return $text;
  }
  
  
  function listUsers()
  {
    // Get Auth Users
    $auth_users = $this->auth_->getUsers();
    
    $text = '';
    $text.= "<pre>AuthUsers:";
    $text.= var_export($auth_users, TRUE);
    $text.= "</pre>";
    
    // Get Perm Users
    $perm_users = $this->perm_->getUsers();
    $text.= "<pre>PermUsers:";
    $text.= var_export($perm_users, TRUE);
    $text.= "</pre>";

    return $text;
  }  
  

  function apiVersion()
  {
    return $this->version_;
  }



}
