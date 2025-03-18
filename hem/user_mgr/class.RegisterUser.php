<?php


class RegisterUser extends PHPApplication

{

  function RegisterUser($params)
  {
    PHPApplication::PHPApplication($params);
  }


  function run()
  {
    $this->registerUserDriver();
  }

  function registerUserDriver()
  {
    $this->debug("RegisterUser Driver");

    $uid = $this->getGetRequestField('uid', null);

    if(!is_null($uid))
      {
	if($this->admin_auth_handler_->isActive($uid))
	  {
	    $this->addSessionMessage('USER_IS_ACTIVE');
	    $this->showExitPage();
	  }
	else
	  $this->registerForm();
      }
    else
      {
	$submit = $this->getPostRequestField('Submit', null);
	$this->debug($submit);
	
	if(!is_null($submit))
	  $this->doRegisterUser();
	else
	  $this->registerForm();
      }
  }
  

  function registerForm()
  {
    global $REGISTER_USER_TEMPLATE;

    $this->showScreen($REGISTER_USER_TEMPLATE, 'displayRegisterForm', $this->getAppName());
  }



  function doRegisterUser()
  {

    $submitted_data = $this->getPostRequestField('data', null);
    $this->dumpArray($submitted_data);


    if(empty($submitted_data['auth_user_id']) && $this->admin_auth_handler_->userNameExists($submitted_data['user_name']))
      {
	$this->addSessionMessage('USERNAME_ALREADY_EXISTS');
	$this->registerForm();
      }

    else if($submitted_data['new_password'] !== $submitted_data['retype_password'])
      {
	$this->addSessionMessage('PASSWORDS_DIDNT_MATCH');
	$this->registerForm();
      }

    else if(empty($submitted_data['new_password']) || empty($submitted_data['retype_password']))
      {
	$this->addSessionMessage('PASSWORD_TOO_SHORT');
	$this->registerForm();
      }
    else
      {
	//TODO:  Do Registration
	$this->user_to_change_ =& new User($submitted_data['auth_user_id'], $this->dbi_);
	$result = $this->user_to_change_->updateData($submitted_data);
	$this->admin_auth_handler_->updatePassword(
						   $submitted_data['auth_user_id'], 
						   null , 
						   $submitted_data['new_password'], 
						   $submitted_data['retype_password']
						   );	
	
      }

  }

  function changeUserData()
  {
    $submitted_data = $this->getPostRequestField('data', null);
  
    $this->user_to_change_ = new User($submitted_data['auth_user_id'], $this->dbi_);

    if($this->admin_auth_handler_->isActive($this->user_to_change_->user_id_))
      $this->debug("USER $this->user_to_change_->first_name $this->user_to_change_->last_name already active");

  
    if($this->user_to_change_->init_ok_ == TRUE)
      $this->debug("User exists");
    else
      $this->debug("User doesn't exist");


    if($this->user_to_change_->init_ok_ == TRUE)
      {
	// Change users attributes
	$result = $this->user_to_change_->updateData($submitted_data);
      }
    else
      {
	// Add users attributes
	$result =  $this->user_to_change_->addData($submitted_data);
      }

    if($result == TRUE)
      {
	$this->debug("Language ID:".$this->user_to_change_->getLanguageId());
	$this->debug("Submitted Language ID:". $submitted_data['language']);

	$this->debug("Theme ID:".$this->user_to_change_->getThemeId());



	$this->user_to_change_->setLanguageId($submitted_data['language']);
	//$this->user_to_change_->setLanguageId('US');
	

	if($this->auth_handler_->checkRight(CHANGE_GROUP_MEMBERSHIP))
	  {
	    if($this->admin_auth_handler_->updateGroupMemberShip($submitted_data['auth_user_id'], $submitted_data['group']) == FALSE)
	      $this->debug("Could not update Group membership");
	  }

	

	if($this->auth_handler_->checkRight(CHANGE_OTHER_PASSWORD))
	  {
	    $this->admin_auth_handler_->updatePassword(
						       $submitted_data['auth_user_id'], 
						       null , 
						       $submitted_data['new_password'], 
						       $submitted_data['retype_password']
						       );
	  }
	else
	  {
	    $this->admin_auth_handler_->updatePassword(
						       $submitted_data['auth_user_id'], 
						       $submitted_data['old_password'] , 
						       $submitted_data['new_password'], 
						       $submitted_data['retype_password']
						       );
	  }

	if($this->auth_handler_->checkRight(CHANGE_OTHER_USERNAME))
	  {
	    if(isset($submitted_data['is_active']) && $submitted_data['is_active'] == 'on')
	      $submitted_data['is_active'] = TRUE;
	    else
	      $submitted_data['is_active'] = FALSE;

	    $this->admin_auth_handler_->updateUserName(
						   $submitted_data['auth_user_id'], 
						   $submitted_data['user_name'],
						   $submitted_data['is_active']
						   );
	    //	    $this->dumpArray($submitted_data);
	  }

	if(!is_null($this->getPostRequestField('url', null)))
	  {
	    //	    $this->debug($this->getPostRequestField('url', null));
	    //	    $this->debugArray($this->getPostRequestField('data', null));
	    //	    $this->dumpArray($this->admin_auth_handler_->getUserData($submitted_data['auth_user_id']));
	    $this->addSessionMessage("USER_CHANGED");
	    header("Location: ".$this->getPostRequestField('url', null)."");
	  }
	else
	  {
	    //	TODO: Can this ever happen?
	    // echo "now what?!?!";
	  }
      }
    else
      {
	// TODO: Drop a message, that something went wrong
	print_r($this->getPostRequestField('data', null));
	$this->debug($result);
      }
  }
  

  function displayRegisterForm(& $tpl)
  {
    global $PHP_SELF;

    $this->debug("Display Register Form");

    $uid = $this->getGetRequestField('uid', null);

    // Form has been submitted but something is wrong
    $submitted_data = $this->getPostRequestField('data', null);

    // User has a user_id, has been invited
    if(!empty($submitted_data['auth_user_id']))
      $uid = $submitted_data['auth_user_id'];

    if(!is_null($uid))
      $user_to_register = &new User($uid, $this->dbi_);

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

    $tpl->setVar(array(
		       'REGISTER_USER_TITLE' => $this->getLabelText('REGISTER_USER_TITLE'),
		       'MESSAGES' => $message_text
		       )
		 );

    $tpl->setVar(array(
		       'SELF_PATH' => $PHP_SELF,
		       )
		 );
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
		       'LABEL_NEW_PASSWORD' => $this->getLabelText('LABEL_NEW_PASSWORD'),
		       'LABEL_RETYPE_PASSWORD' => $this->getLabelText('LABEL_RETYPE_PASSWORD')
		       )
		 );

    if(is_null($uid) && is_null($submitted_data))
      {
	$tpl->setCurrentBlock('user_name_block');
	$tpl->setVar('LABEL_USERNAME', $this->getlabelText('USERNAME'));
	$tpl->parseCurrentBlock();
      }

    $tpl->setVar(array(
		       'SUBMIT_BUTTON' => $this->getLabelText('SUBMIT_BUTTON'),
		       'CANCEL_BUTTON' => $this->getLabelText('CANCEL_BUTTON')
		       )
		 );

    $this->debug("uid:".$uid);
    if(!is_null($uid) && $user_to_register->init_ok_ == TRUE)
      {
	$this->debug("inside !is_null($uid)");
	$tpl->setVar(array(
		       'FIRST_NAME' => $user_to_register->first_name,
		       'LAST_NAME' => $user_to_register->last_name,
		       'EMAIL' => $user_to_register->email,
		       'STREET' => $user_to_register->street,
		       'NO' => $user_to_register->no,
		       'CITY' => $user_to_register->city,
		       'ZIP' => $user_to_register->zip,
		       'COUNTRY' => $user_to_register->country,
		       'PHONE' => $user_to_register->phone
		       )
		 );
      }

    // Form has been submitted, but something is wrong, user has not been invited
    if(is_null($uid) && !is_null($submitted_data))
      {
 	$this->debug("inside is_null(\$uid)");
	$tpl->setVar(array(
		       'FIRST_NAME' => $submitted_data['first_name'],
		       'LAST_NAME' => $submitted_data['last_name'],
		       'EMAIL' => $submitted_data['email'],
		       'STREET' => $submitted_data['street'],
		       'NO' => $submitted_data['no'],
		       'CITY' => $submitted_data['city'],
		       'ZIP' => $submitted_data['zip'],
		       'COUNTRY' => $submitted_data['country'],
		       'PHONE' => $submitted_data['phone']
		       )
		 );	
	$tpl->setCurrentBlock('user_name_block');
	$tpl->setVar(array(
			   'LABEL_USERNAME' => $this->getlabelText('USERNAME'),
			   'USERNAME' => $submitted_data['user_name'],
			   )
		     );
	$tpl->parseCurrentBlock();
      }

    // User object has user_id_ when user has been invited
    if(!is_null($uid))
      $tpl->setVar('USER_ID', $user_to_register->user_id_);
    
    // Language  
    $tpl->setCurrentBlock('language_drop_down_block');
    $tpl->setVar(array(
		       'SELECT_NAME_LANGUAGE' => 'data[language]',
		       'LABEL_LANGUAGE' => $this->getLabelText('LABEL_LANGUAGE')
		       )
		 );

    // Language German
    if(!is_null($uid) && $user_to_register->getLanguageID() == 'DE')
      $tpl->setCurrentBlock('language_selected_drop_down_option_block');
    else
      $tpl->setCurrentBlock('language_drop_down_option_block');

    $tpl->setVar('OPTION_VALUE_LANGUAGE', 'DE' );
    $tpl->setVar('OPTION_TEXT_LANGUAGE', $this->getLabelText('LABEL_GERMAN_LANGUAGE'));  
    
    $tpl->parseCurrentBlock();

    // Language English
    if(!is_null($uid) && $user_to_register->getLanguageID() == 'US')
      $tpl->setCurrentBlock('language_selected_drop_down_option_block');
    else
      $tpl->setCurrentBlock('language_drop_down_option_block');

    $tpl->setVar('OPTION_VALUE_LANGUAGE', 'US' );
    $tpl->setVar('OPTION_TEXT_LANGUAGE', $this->getLabelText('LABEL_ENGLISH_LANGUAGE'));  
    
    $tpl->parseCurrentBlock();

    $tpl->parseCurrentBlock('language_drop_down_block');

    return TRUE;
  }


  function showExitPage()
  {
    global $EXIT_PAGE_TEMPLATE;
    
    $this->showScreen($EXIT_PAGE_TEMPLATE, 'displayExitPage', $this->getAppName());
  }


  function displayExitPage(& $tpl)
  {
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

    $tpl->setVar('EXIT_PAGE_TITLE', $this->getLabelText('EXIT_PAGE_TITLE'));
    $tpl->setVar('MESSAGES', $message_text);


    return TRUE;
  }
  
  
}



?>