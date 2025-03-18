<?php


class ChangeUser extends PHPApplication

{

  function ChangeUser($params)
  {
    PHPApplication::PHPApplication($params);
  }


  function run()
  {
    global $HOME_APP, $HOME_APP_LABEL;

    // Set Application Title and Breadcrumbs
    $this->app_name_ = $this->getLabelText("APPLICATION_TITLE");
    $this->app_breadcrumbs_[] = Array(
				      'url' => $HOME_APP,
				      'label' => $HOME_APP_LABEL[$this->language_],
				      );
    
    $this->app_breadcrumbs_[] = Array(
				      'url' => "user_mgr/run.UserManager.php",
				      'label' => $this->getLabelText("APPLICATION_TITLE"),
				      );
 

    $this->changeUserDriver();

  }

  function changeUserDriver()
  {

    $user_id = $this->getGetRequestField('uid', null);

    // We change another User, TODO: check right
    if(!is_null($user_id) && $this->auth_handler_->checkRight(CHANGE_OTHER_USERS))
      {
	$this->user_to_change_ =& new User($user_id, $this->dbi_);
      }
    // We add a user
    elseif(is_null($user_id) && $this->auth_handler_->checkRight(CHANGE_OTHER_USERS))
      {
	$this->user_to_change_ =& new User(0, $this->dbi_); 
      }
    // We change our selves data
    else
      {
	$this->user_to_change_ = $this->user_;
      }


    if($this->getPostRequestField('step', null) == '1')
      {
	$this->submitted_data = $this->getPostRequestField('data', null);

	//	$this->dumpArray($this->submitted_data);
	// Error Checks:	
	if(empty($this->submitted_data['auth_user_id']) && $this->admin_auth_handler_->userNameExists($this->submitted_data['user_name']))
	  {
	    $this->addSessionMessage("USER_EXISTS");
	    $this->submitted_data['user_name'] = '';
	    $this->changeForm();
	  }
	elseif(empty($this->submitted_data['auth_user_id']) && empty($this->submitted_data['user_name']))
	  {
	    $this->addSessionMessage("NO_USER_NAME");
	    $this->changeForm();
	  }
	elseif($this->submitted_data['new_password'] !== $this->submitted_data['retype_password'])
	  {
	    $this->addSessionMessage("PASSWORDS_DIDNT_MATCH");
	    $this->changeForm();
	  }	  
	elseif(empty($this->submitted_data['auth_user_id']) && (empty($this->submitted_data['new_password']) || empty($this->submitted_data['retype_password'])))
	  {
	    $this->addSessionMessage("PASSWORD_TOO_SHORT");
	    $this->changeForm();
	  }

	else
	  {
	    // Right checks:
	    if( $this->submitted_data['auth_user_id'] == $this->getUID() )
	      {
		$this->changeUserData();
	      }
	    elseif($this->auth_handler_->checkRight(CHANGE_OTHER_USERS)) // check if user has right to change other users
	      {
		$this->changeUserData();
	      }
	    else
	      {
		$this->addSessionMessage("DO_NOT_CHANGE_OTHER_USER");
		$this->changeForm();
	      }
	  }
      }
    else 
      {
	$this->changeForm();
      }
  }
  

  function changeForm()
  {
    global $CHANGE_USER_TEMPLATE;

    $this->showScreen($CHANGE_USER_TEMPLATE, 'displayChangeForm', $this->getAppName());
  }

  function changeUserData()
  {
    //    $this->dumpArray($this->submitted_data);
  
    $this->user_to_change_ =& new User($this->submitted_data['auth_user_id'], $this->dbi_);
  
    if($this->user_to_change_->init_ok_ == TRUE)
      {
	// Change users attributes
	$result = $this->user_to_change_->updateData($this->submitted_data);
	//	echo "Updating User<br/>";
      }
    else
      {
	// Give User a ID
	$this->submitted_data['auth_user_id'] = $this->dbi_->getUniqueId();
	// Add users attributes
	$result =  $this->user_to_change_->addData($this->submitted_data);
	//	echo "Adding User<br/>";
      }

    if($result == TRUE)
      {
	$this->debug("Language ID:".$this->user_to_change_->getLanguageId());
	$this->debug("Submitted Language ID:". $this->submitted_data['language']);
	$this->debug("Theme ID:".$this->user_to_change_->getThemeId());

	$this->user_to_change_->setLanguageId($this->submitted_data['language']);
	//$this->user_to_change_->setLanguageId('US');
	

	if($this->auth_handler_->checkRight(CHANGE_OTHER_USERNAME))
	  {
	    if(isset($this->submitted_data['is_active']) && $this->submitted_data['is_active'] == 'on')
	      $this->submitted_data['is_active'] = TRUE;
	    else
	      $this->submitted_data['is_active'] = FALSE;
	    
	    $this->debug("User ".$this->submitted_data['user_name']." exists? ".$this->admin_auth_handler_->userNameExists($this->submitted_data['user_name']));
	    
	    if($this->user_to_change_->init_ok_ && $this->admin_auth_handler_->userIdExists($this->submitted_data['auth_user_id']))
	      {
		//		$this->writeln("Updating username".$this->submitted_data['user_name']);
		$this->admin_auth_handler_->updateUserName(
							   $this->submitted_data['auth_user_id'], 
							   $this->submitted_data['user_name'],
							   $this->submitted_data['is_active']
							   );
	      }
	    else
	      {
		//		$this->writeln("Adding username");
		$this->admin_auth_handler_->addUser(
						    $this->submitted_data['auth_user_id'],
						    $this->submitted_data['user_name'],
						    $this->submitted_data['new_password'],
						    $this->submitted_data['is_active']
						    );
	      }
	  }




	if($this->auth_handler_->checkRight(CHANGE_GROUP_MEMBERSHIP))
	  {
	    $this->debug("Changing Group");
	    if($this->submitted_data['group'] > 0)
	      {
		$groups = array();
		$i = 0;
		while($i < $this->submitted_data['group'])
		  {
		    array_push($groups, $i+1);
		    $i++;
		  }
		//		$this->dumpArray($groups);

		if(!$this->admin_auth_handler_->getPermUserId($this->submitted_data['auth_user_id']))

		  $this->admin_auth_handler_->addUser(
						      $this->submitted_data['auth_user_id'],
						      $this->submitted_data['user_name'],
						      $this->submitted_data['new_password'],
						      $this->submitted_data['is_active']
						      );
	      	    
		if(!$this->admin_auth_handler_->updateGroupMemberShip($this->submitted_data['auth_user_id'], $groups))
		  $this->debug("Could not update Group membership");
	      }
	  }

	
	if($this->auth_handler_->checkRight(CHANGE_OTHER_PASSWORD) && !empty($this->submitted_data['new_password']))
	  {
	    $this->admin_auth_handler_->updatePassword(
						       $this->submitted_data['auth_user_id'], 
						       null , 
						       $this->submitted_data['new_password'], 
						       $this->submitted_data['retype_password']
						       );
	  }
	else
	  {
	    $this->admin_auth_handler_->updatePassword(
						       $this->submitted_data['auth_user_id'], 
						       $this->submitted_data['old_password'] , 
						       $this->submitted_data['new_password'], 
						       $this->submitted_data['retype_password']
						       );
	  }

	if(!is_null($this->getPostRequestField('url', null)))
	  {
	    $this->addSessionMessage("USER_CHANGED");
	    header("Location: ".$this->getPostRequestField('url', null)."");
	  }
	else
	  {
	    //	TODO: Can this ever happen?
	    echo "now what?!?!";
	  }
      }
    else
      {
	// TODO: Drop a message, that something went wrong
	echo "Something went completly wrong<br/>";
	$this->writeln($this->user_to_change_->getError());
	//	$this->dumpArray($this->getPostRequestField('data', null));
	$this->debug($result);
      }
  }
  

  function displayChangeForm(& $tpl)
  {
    global $PHP_SELF;

    if($this->user_to_change_->init_ok_)
      $this->debug("Username to change:" .$this->admin_auth_handler_->getUserName($this->user_to_change_->user_id_));

    // Output session message box
    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
	while($msg = array_pop($messages))
	  {
	    $message_text.=$msg;
	  }
      }
    
    $tpl->setCurrentBlock('main_block');

    if($this->user_to_change_->init_ok_)
      {
	$tpl->setVar('CHANGE_USER_DATA_TITLE', $this->getLabelText('CHANGE_USER_TITLE').":");
	$this->app_name_ = $this->getLabelText('CHANGE_USER_TITLE');
	$this->app_breadcrumbs_[] = Array(
					  'url' => $_SERVER['REQUEST_URI'],
					  'label' =>  $this->admin_auth_handler_->getUserName($this->user_to_change_->user_id_),
					  );
	$this->app_breadcrumbs_[] = Array(
					  'label' =>  $this->getLabelText('CHANGE_USER_TITLE'),
					  );

      }
    else
      {
	$tpl->setVar('CHANGE_USER_DATA_TITLE', $this->getLabelText('ADD_USER_TITLE'));
	$this->app_name_ = $this->getLabelText('ADD_USER_TITLE');
	$this->app_breadcrumbs_[] = Array(
					  'label' =>  $this->getLabelText('ADD_USER_TITLE'),
					  );
      }
    
    $tpl->setVar('CHANGE_USER_DATA_USERNAME', ($this->user_to_change_->init_ok_) ? $this->admin_auth_handler_->getUserName($this->user_to_change_->user_id_) : '');
    
    $tpl->setVar('MESSAGES', $message_text);
    
    $tpl->setVar(array(
		       'SELF_PATH' => $PHP_SELF
		       )
		 );
    if(!is_null($url = $this->getGetRequestField('url', null)))
      $tpl->setVar('REDIRECT_URL', $url);
    elseif(!is_null($url = $this->getPostRequestField('url', null)))
      $tpl->setVar('REDIRECT_URL', $url);
    else
      $tpl->setVar('REDIRECT_URL', $PHP_SELF."?uid=".$this->user_to_change_->user_id_);


    $tpl->setVar(array(
		       'LABEL_FIRSTNAME' => $this->getLabelText('FIRST_NAME'),
		       'LABEL_LASTNAME' => $this->getLabelText('LAST_NAME'),
		       'LABEL_EMAIL' => $this->getLabelText('EMAIL'),
		       'LABEL_STREET' => $this->getLabelText('STREET'),
		       'LABEL_NO' => $this->getLabelText('NO'),
		       'LABEL_CITY' => $this->getLabelText('CITY'),
		       'LABEL_ZIP' => $this->getLabelText('ZIP'),
		       'LABEL_COUNTRY' => $this->getLabelText('COUNTRY'),
		       'LABEL_PHONE' => $this->getLabelText('PHONE'),
		       'LABEL_OLD_PASSWORD' => $this->getLabelText('LABEL_OLD_PASSWORD'),
		       'LABEL_NEW_PASSWORD' => $this->getLabelText('LABEL_NEW_PASSWORD'),
		       'LABEL_RETYPE_PASSWORD' => $this->getLabelText('LABEL_RETYPE_PASSWORD')
		       )
		 );
    $tpl->setVar(array(
		       'SUBMIT_BUTTON' => $this->getLabelText('SUBMIT_BUTTON'),
		       'CANCEL_BUTTON' => $this->getLabelText('CANCEL_BUTTON')
		       )
		 );

    if($this->user_to_change_->init_ok_ == TRUE)
      {
	$tpl->setVar(array(
		       'FIRST_NAME' => $this->user_to_change_->first_name,
		       'LAST_NAME' => $this->user_to_change_->last_name,
		       'EMAIL' => $this->user_to_change_->email,
		       'STREET' => $this->user_to_change_->street,
		       'NO' => $this->user_to_change_->no,
		       'CITY' => $this->user_to_change_->city,
		       'ZIP' => $this->user_to_change_->zip,
		       'COUNTRY' => $this->user_to_change_->country,
		       'PHONE' => $this->user_to_change_->phone,
		       'USER_ID' => $this->user_to_change_->user_id_
		       )
		 );
      }
    if($this->getPostRequestField('step', null) == '1')
      {
	$tpl->setVar(array(
		       'FIRST_NAME' => $this->submitted_data['first_name'],
		       'LAST_NAME' => $this->submitted_data['last_name'],
		       'EMAIL' => $this->submitted_data['email'],
		       'STREET' => $this->submitted_data['street'],
		       'NO' => $this->submitted_data['no'],
		       'CITY' => $this->submitted_data['city'],
		       'ZIP' => $this->submitted_data['zip'],
		       'COUNTRY' => $this->submitted_data['country'],
		       'PHONE' => $this->submitted_data['phone'],
		       'USER_ID' => $this->submitted_data['auth_user_id']
		       )
		 );	



      }

    if($this->auth_handler_->checkRight(VIEW_MANAGER_DATA))
      {
	$tpl->setCurrentBlock('manager_content');
	$tpl->setVar('LABEL_COMMENT', $this->getLabelText('COMMENT'));
	if($this->user_to_change_->init_ok_ == TRUE)
	  {
	    $tpl->setVar('COMMENT', $this->user_to_change_->comment);
	    $tpl->parseCurrentBlock();
	  }
	elseif($this->getPostRequestField('step', null) == '1')
	  {
	    $tpl->setVar('COMMENT', $this->submitted_data['comment']);
	    $tpl->parseCurrentBlock();
	  }
      }

    if($this->auth_handler_->checkRight(CHANGE_OTHER_USERNAME))
      {
	$tpl->setCurrentBlock('user_name_block');
	$tpl->setVar('LABEL_USERNAME', $this->getlabelText('USERNAME'));

	if($this->user_to_change_->init_ok_)
	  $tpl->setVar('USERNAME', $this->admin_auth_handler_->getUserName($this->user_to_change_->user_id_));
	elseif($this->getPostRequestField('step', null) == '1')
	  $tpl->setVar('USERNAME', $this->submitted_data['user_name']);

	$tpl->parseCurrentBlock();
      }
    

    //    $this->dumpArray($this->admin_auth_handler_->isActive($this->user_to_change_->user_id_));
    if($this->auth_handler_->checkRight(CHANGE_ACTIVE_FLAG))
      {
	if($this->user_to_change_->init_ok_ && $this->admin_auth_handler_->isActive($this->user_to_change_->user_id_))
	  {
	    $tpl->setCurrentBlock('checkbox_checked_block');
	  }
	elseif($this->getPostRequestField('step', null) == '1' && isset($this->submitted_data['is_active']) && $this->submitted_data['is_active'] == 'on')
	  {
	    $tpl->setCurrentBlock('checkbox_checked_block');
	  }
	else
	  {
	    $tpl->setCurrentBlock('checkbox_block');
 	  }
	$tpl->setVar('LABEL_ACTIVE', $this->getlabelText('ACTIVE'));
	$tpl->parseCurrentBlock();
      }
  

    // Language Drop Down:
    $tpl->setCurrentBlock('language_drop_down_block');
    $tpl->setVar(array(
		       'SELECT_NAME_LANGUAGE' => 'data[language]',
		       'LABEL_LANGUAGE' => $this->getLabelText('LABEL_LANGUAGE')
		       )
		 );

    // --> German
    if($this->user_to_change_->init_ok_ && $this->user_to_change_->getLanguageID() == 'DE')
      $tpl->setCurrentBlock('language_selected_drop_down_option_block');
    elseif($this->getPostRequestField('step', null) == '1' && $this->submitted_data['language'] == 'DE')
      $tpl->setCurrentBlock('language_selected_drop_down_option_block');
    else
      $tpl->setCurrentBlock('language_drop_down_option_block');

    $tpl->setVar('OPTION_VALUE_LANGUAGE', 'DE' );
    $tpl->setVar('OPTION_TEXT_LANGUAGE', $this->getLabelText('LABEL_GERMAN_LANGUAGE'));  
    
    $tpl->parseCurrentBlock();

    // --> English
    if($this->user_to_change_->init_ok_ && $this->user_to_change_->getLanguageID() == 'US')
      $tpl->setCurrentBlock('language_selected_drop_down_option_block');
    elseif($this->getPostRequestField('step', null) == '1' && $this->submitted_data['language'] == 'US')
      $tpl->setCurrentBlock('language_selected_drop_down_option_block');
    else
      $tpl->setCurrentBlock('language_drop_down_option_block');

    $tpl->setVar('OPTION_VALUE_LANGUAGE', 'US' );
    $tpl->setVar('OPTION_TEXT_LANGUAGE', $this->getLabelText('LABEL_ENGLISH_LANGUAGE'));  
    
    $tpl->parseCurrentBlock();

    $tpl->parseCurrentBlock('language_drop_down_block');



    if($this->auth_handler_->checkRight(CHANGE_GROUP_MEMBERSHIP))
      {
	$users_groups = $this->admin_auth_handler_->getGroups($this->user_to_change_->user_id_);

	asort($users_groups);

	$max_group = array_pop($users_groups);
	
	// TODO: This is a hack, change me
	// If User data don't exist, default setting is Evaluator, otherwise it would be Admin
	if(!$this->user_to_change_->init_ok_)
	  $max_group['group_id'] = EVALUATOR;
	if(!$this->user_to_change_->init_ok_ && $this->getPostRequestField('step', null) == '1')
	  $max_group['group_id'] = $this->submitted_data['group'];

	
	$this->debug("GroupID: ".$max_group['group_id']);
	
	$tpl->setCurrentBlock('group_drop_down_block');
	$tpl->setVar(array(
			   'SELECT_NAME_GROUP' => 'data[group]',
			   'LABEL_GROUP' => $this->getLabelText('LABEL_GROUP')
			   )
		     );
	
	if($max_group['group_id'] == EVALUATOR)
	  {
	    $tpl->setCurrentBlock('group_selected_drop_down_option_block');
	  }
	else
	  {
	    $tpl->setCurrentBlock('group_drop_down_option_block');
	  }
	$tpl->setVar('OPTION_VALUE_GROUP', EVALUATOR );
	$tpl->setVar('OPTION_TEXT_GROUP', $this->getLabelText('LABEL_EVALUATOR_GROUP'));
	$tpl->parseCurrentBlock();
	
	
	if($max_group['group_id'] == MANAGER)
	  {
	    $this->debug('Group MANAGER');
	    $tpl->setCurrentBlock('group_selected_drop_down_option_block');
	  }
	else
	  {
	    $tpl->setCurrentBlock('group_drop_down_option_block');
	  }
	$tpl->setVar('OPTION_VALUE_GROUP', MANAGER );
	$tpl->setVar('OPTION_TEXT_GROUP', $this->getLabelText('LABEL_MANAGER_GROUP'));
	$tpl->parseCurrentBlock();
	
	
	
	if($max_group['group_id'] == ADMIN)
	  {
	    $this->debug('Group ADMIN');
	    $tpl->setCurrentBlock('group_selected_drop_down_option_block');
	  }
	else
	  {
	    $tpl->setCurrentBlock('group_drop_down_option_block');
	  }
	$tpl->setVar('OPTION_VALUE_GROUP', ADMIN );
	$tpl->setVar('OPTION_TEXT_GROUP', $this->getLabelText('LABEL_ADMIN_GROUP'));
	$tpl->parseCurrentBlock();

      }
    
    return TRUE;
  }
  
  
}



?>