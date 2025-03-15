<?php



class InviteUser extends PHPApplication

{

  function InviteUser($params)
  {
    PHPApplication::PHPApplication($params);
  }


  function run()
  {
    $this->inviteUserDriver();

  }

  function inviteUserDriver()
  {
    $submitted_data = $this->getPostRequestField('data', null);

    if($submitted_data['cmd'] == 'invite')
      {
	if($this->admin_auth_handler_->userNameExists($submitted_data['username']) == TRUE )
	  {
	    $this->addSessionMessage('USER_EXISTS');
	    $this->inviteForm();
	  }
	else
	  {
	    $this->doInviteUser($submitted_data);
	  }
      }
    else
      {
	$this->inviteForm();
      }
  }
  
  function doInviteUser($data)
  {
    //    $this->dumpArray($data);

    $user_id = $this->admin_auth_handler_->addUser($this->getUniqueId(), $data['username']);

    $user_data = array(
		       'auth_user_id' => $user_id,
		       'first_name' => $data['firstname'],
		       'last_name' => $data['lastname'],
		       'email' => $data['email']
		       );

    $result =  $this->user_->addData($user_data);


    // TODO: Write Mail

    echo "Added User: " .$data['username']. " with uid: ". $user_id . "<br/>";

    echo "<script type=\"text/javascript\" language=\"Javascript\">"
      ."self.close()"
      ."</script>";

    echo "<a href=\"javascript:self.close()\">close</a>";

  }


  function inviteForm()
  {
    global $INVITE_USER_TEMPLATE, $POP_UP_APP;

    $this->showScreen($INVITE_USER_TEMPLATE, 'displayInviteForm', $this->getAppName(), $POP_UP_APP);
  }

  function displayInviteForm(& $tpl)
  {
    global $PHP_SELF;

    $this->debug("Display Invite Form");

    $submitted_data = $this->getPostRequestField('data', null);

    // Page Title
    $tpl->setVar('INVITE_USER_TITLE', $this->getLabelText('INVITE_USER_TITLE'));

    // Session Messages
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
    $tpl->setVar('MESSAGES', $message_text);

    // Form stuff
    $tpl->setVar(array(
		       'FORM_NAME' => 'inviteUser',
		       'FORM_METHOD' =>'POST',
		       'FORM_ACTION' => $PHP_SELF
		       )
		 );

    // Username:
    $tpl->setCurrentBlock('input_text_block');
    $tpl->setVar(array(
		       'LABEL_TEXT_BLOCK' => $this->getLabelText('USERNAME'),
		       'INPUT_NAME' => 'data[username]',
		       'INPUT_VALUE' => $submitted_data['username']
		       )
		 );
    $tpl->parseCurrentBlock();


    // FirstName
    $tpl->setCurrentBlock('input_text_block');
    $tpl->setVar(array(
		       'LABEL_TEXT_BLOCK' => $this->getLabelText('FIRST_NAME'),
		       'INPUT_NAME' => 'data[firstname]',
		       'INPUT_VALUE' => $submitted_data['firstname']
		       )
		 );
    $tpl->parseCurrentBlock();
    // LastName
    $tpl->setCurrentBlock('input_text_block');
    $tpl->setVar(array(
		       'LABEL_TEXT_BLOCK' => $this->getLabelText('LAST_NAME'),
		       'INPUT_NAME' => 'data[lastname]',
		       'INPUT_VALUE' => $submitted_data['lastname']
		       )
		 );
    $tpl->parseCurrentBlock();
    // E-Mail adress
    $tpl->setCurrentBlock('input_text_block');
    $tpl->setVar(array(
		       'LABEL_TEXT_BLOCK' => $this->getLabelText('EMAIL'),
		       'INPUT_NAME' => 'data[email]',
		       'INPUT_VALUE' => $submitted_data['email']
		       )
		 );
    $tpl->parseCurrentBlock();

    $tpl->setCurrentBlock('input_hidden_block');
    $tpl->setVar(array(
		       'INPUT_NAME' => 'data[cmd]',
		       'INPUT_VALUE' => 'invite'
		       )
		 );
    $tpl->parseCurrentBlock();


    // Buttons
    $tpl->setVar(array(
		       'SUBMIT_BUTTON' => $this->getLabelText('SUBMIT_BUTTON'),
		       'CANCEL_BUTTON' => $this->getLabelText('CANCEL_BUTTON')
		       )
		 );


    return TRUE;
  }
  
  
}



?>