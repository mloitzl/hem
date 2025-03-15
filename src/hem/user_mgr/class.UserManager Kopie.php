<?php

class UserManager extends PHPApplication
{

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
				      'url' => $_SERVER['SCRIPT_NAME'],
				      'label' => $this->getLabelText("APPLICATION_TITLE"),
				      );
    $this->userManagerDriver();
  }


  function userManagerDriver()
  {
    $cmd = $this->getGetRequestField('cmd', null);

    $form = $this->getPostRequestField('form_id', null);
    $answer = $this->getPostRequestField('Yes', null);

    switch ($form)
      {
      case 'confirm':
	if(!is_null($answer))
	  $this->doDeleteUser();
	break;
      }

    switch ($cmd)
      {
      case 'delUser':
	$this->getConfirmation();
	break;
      default:
	$this->userOverview();
	break;
      }
  }


  function userOverview()
  {
    global $USER_OVERVIEW_TEMPLATE;

    $this->showScreen($USER_OVERVIEW_TEMPLATE, 'displayUserOverview', $this->getAppName());

  }


  function displayUserOverview(& $tpl)
  {
    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
	while($msg = array_pop($messages))
	  {
	    $message_text.=$msg;
	  }
      }    

    $user_object =& new User(0, $this->dbi_);
    
    $user_ids = $user_object->getAllUserIds();

    $tpl->setVar(array(
		       'LABEL_USER' => $this->getLabelText('LABEL_USER'),
		       'LABEL_OPS' => $this->getLabelText('LABEL_OPS'),
		       'LABEL_STATE' => $this->getLabelText('LABEL_STATE'),
		       )
		 );


    if(is_array($user_ids))
      {
	$i = 0;
	while($current_user_id = array_pop($user_ids))
	  {
	    $current_user_object =& new User($current_user_id, $this->dbi_);

	    if($this->admin_auth_handler_->isActive($current_user_object->user_id_))
	      $user_state = $this->getLabelText('LABEL_ACTIVE');
	    else
	      $user_state = $this->getLabelText('LABEL_INACTIVE');

	    $tpl->setCurrentBlock('user_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($i%2?'odd':'even'),
			       'USER_FIRST_NAME' => $current_user_object->first_name,
			       'USER_LAST_NAME' => $current_user_object->last_name,
			       'USER_NAME' => $this->admin_auth_handler_->getUsername($current_user_object->user_id_),
			       'EDIT_URL' => "user_mgr/run.ChangeUser.php?uid=".$current_user_object->auth_user_id."&url=".$_SERVER['PHP_SELF'],
			       'DELETE_URL' => $_SERVER['PHP_SELF']."?cmd=delUser&uid=".$current_user_object->auth_user_id."&url=".$_SERVER['PHP_SELF'],
			       'LABEL_CHANGE_USER' => $this->getlabelText('LABEL_CHANGE_USER'),
			       'LABEL_DELETE_USER' => $this->getlabelText('LABEL_DELETE_USER'),
			       'STATE_ID' =>($this->admin_auth_handler_->isActive($current_user_object->user_id_)?'id="active"':'id="inactive"'),
			       'USER_STATE' => $user_state
			       )
			 );
	    
	    $tpl->parseCurrentBlock();
	    $i++;
	  }

      }


    $tpl->setVar(array(
		       'LABEL_ADD_USER' => $this->getLabelText('LABEL_ADD_USER'), 
		       'ADD_URL' => "user_mgr/run.ChangeUser.php?url=".$_SERVER['PHP_SELF'],
		       )
		 );

   $tpl->setVar(array(
		       'LABEL_INVITE_USER' => $this->getLabelText('LABEL_INVITE_USER'), 
		       'INVITE_URL' => "user_mgr/run.InviteUser.php"
		       )
		 );


    $tpl->setVar(array(
		       'USER_OVERVIEW_TITLE' => $this->getLabelText('USER_OVERVIEW_TITLE')
		       )
		 );

    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );


    return TRUE;
  }

  function doDeleteuser()
  {
    $this->debug("Deleting User");

    $uid = $this->getPostRequestField('uid', null);

    $this->debug("uid to delete: ".$uid);

    if(!is_null($uid))
      {
	$this->user_->deleteData($uid);
	$this->admin_auth_handler_->removeUser($uid);
	//	$this->dumpArray($perm_uid = $this->admin_auth_handler_->getPermUserid($uid));
	//	$this->dumpArray($this->admin_auth_handler_->getAuthUserid($perm_uid));
      }

  }


  function getConfirmation()
  {
    global $CONFIRMATION_TEMPLATE;

    $this->showScreen($CONFIRMATION_TEMPLATE, 'displayConfirmationForm', $this->getAppName());
  }

  function displayConfirmationForm(& $tpl)
  {
    $cmd = $this->getGetRequestField('cmd', null);
    $uid = $this->getGetRequestField('uid', null);

    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
	while($msg = array_pop($messages))
	  {
	    $message_text.=$msg;
	  }
      }    

    if(!is_null($cmd) && $cmd == 'delUser')
      {
	$tpl->setVar(array(
			   'CONFIRM_MESSAGE' => $this->getMessageText('CONFIRM_MESSAGE')
			   )
		     );

	if(!is_null($uid))
	  {
	    $user_to_delete = & new User($uid, $this->dbi_);
	    $tpl->setVar(array(
			       'USER_FIRST_NAME' => $user_to_delete->first_name,
			       'USER_LAST_NAME' => $user_to_delete->last_name,
			       'USER_NAME' => $this->admin_auth_handler_->getUserName($user_to_delete->user_id_)
			       )
			 );
	    $tpl->setVar(array(
			       'USER_ID' => $uid
			       )
			 );
    
	  }
	$tpl->setVar(array(
			   'LABEL_YES' => $this->getLabelText('LABEL_YES'),
			   'LABEL_NO' => $this->getLabelText('LABEL_NO')
			   )
		     );


      }
    
    $this->app_breadcrumbs_[] = Array(
				      'label' => $this->getLabelText("CONFIRM_TITLE"),
				      );

    $tpl->setVar(array(
		       'FORM_ACTION' => $_SERVER['PHP_SELF'],
		       'CONFIRM_TITLE' => $this->getLabelText('CONFIRM_TITLE')
		       )
		 );

    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );


    
    return TRUE;
  }

}





?>