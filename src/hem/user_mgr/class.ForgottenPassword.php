<?php

class ForgottenPassword extends PHPApplication
{

  function run()
  {

    $this->forgottenPasswordDriver();
  }


  function forgottenPasswordDriver()
  {
    global $PHP_SELF, $HOME_APP;
    
    $cmd = $this->getGetRequestField('cmd', null);

    $form = $this->getPostRequestField('form_id', null);

    $answer = $this->getPostRequestField('Yes', null);

    // Action to take after adding the finding
    $action = $this->getPostRequestField('action', null);

    //Url to redirect user to, afterwards
    $url = $this->getPostRequestField('url', null);
    
    $data = $this->getPostRequestField('data', null);
    $project_id = $data['pId'];

    $this->debug($action);


    if(!is_null($form))
      {
	switch ($form)
	  {
	  case 'forgot_password':
	    //	    $this->dumpArray($_REQUEST);
	    $this->doResetPassword();
	    $this->resultPage();
	    break;
	  default:
	    break;
	  }
      }
    else
      {
	switch ($cmd)
	  {
	  default:
	    $this->enterUserName();
	    break;
	  }
      }
  }

  function doResetPassword()
  {
    //    $this->dumpArray($_REQUEST);

    $user_name = $this->getPostRequestField('username', null);

    if(!is_null($user_name))
      {
	$user_data = $this->admin_auth_handler_->getUserData(null, $user_name, null);

	//	$this->dumpArray($user_data);

	$new_pass = $this->getUniqueId(8);

	$this->debug($new_pass);

	$user_to_reset = & new User($user_data[0]['auth_user_id'], $this->dbi_);

	if($this->sendPasswordMail($user_to_reset->email, $user_data[0]['handle'], $new_pass))
 	  {
	    $this->admin_auth_handler_->updatePassword($user_data[0]['auth_user_id'], null, $new_pass, $new_pass);
	  }
	else
	  {
	    return FALSE;
	  }
      }
  }


  function sendPasswordMail($recipient, $username, $password)
  {
    global $SMTP_HOST, $SMTP_SENDER_ADDRESS, $SMTP_USERNAME, $SMTP_PASS;

    require_once 'Mail.php';

    if(empty($SMTP_USERNAME))
      $do_auth = FALSE;
    else
      $do_auth = TRUE;
    
    $params = Array(
		    'host' => $SMTP_HOST,
		    'auth' => $do_auth,
		    'username' => $SMTP_USERNAME,
		    'password' => $SMTP_PASS,
		    );
    
    //	$this->dumpArray($params);
    
    $mail_factory = Mail::factory('smtp', $params);
    
    $headers['From'] = $SMTP_SENDER_ADDRESS;
    $headers['To'] = $recipient;
    $headers['Subject'] = 'HEM Password has been reset';
    
    
    //	$this->dumpArray($headers);
    
    $message_text = 'Your password on '.$this->getFQAP().' has been reset. Username: '.$username.' new password: '.$password.'';

    if(!empty($recipient))
      {
	$status = $mail_factory->send($recipient, $headers, $message_text);
	if(PEAR::isError($status))
	  {
	    $this->mail_error_ =  $status->getMessage();
	    return FALSE;
	  }
	else
	  return TRUE;
      }
    else
      {
	$this->mail_error_ =  "Empty recipient";
	return FALSE;
      }
  }


  function enterUserName()
  {
    global $USERNAME_TEMPLATE;
    
    $this->showScreen($USERNAME_TEMPLATE, 'displayEnterUserName', $this->getAppName());
  }


  function displayEnterUserName(& $tpl)
  {
    global $PHP_SELF;



    $tpl->setVar(array(
		       'FORM_ACTION' => $PHP_SELF,
		       'FORM_METHOD' => 'POST',
		       'FORGOT_PASSWORD_MESSAGE' => $this->getMessageText('FORGOT_PASSWORD_MESSAGE'),
		       'LABEL_USERNAME' => $this->getLabelText('LABEL_USERNAME'), 
		       'LABEL_SUBMIT' => $this->getLabelText('LABEL_SUBMIT'), 
		       'FORGOTTENPASSWORD_TITLE' => $this->getLabelText('FORGOTTENPASSWORD_TITLE'), 
		       )
		 );


    return TRUE;
  }


  function resultPage()
  {
    global $RESULT_TEMPLATE;
    
    $this->showScreen($RESULT_TEMPLATE, 'displayResultPage', $this->getAppName());
  }


  function displayResultPage(& $tpl)
  {
    global $PHP_SELF;


    if(!empty($this->mail_error_))
      $tpl->setVar(array(
			 'RESULT_MESSAGE' => $this->getMessageText('NEGATIVE_RESULT_MESSAGE'),
			 'LABEL_ERROR_MESSAGE' =>  $this->getLabelText('LABEL_ERROR_MESSAGE'),
			 'ERROR_MESSAGE' => $this->mail_error_
			 )
		   );
    else
           $tpl->setVar(array(
			 'RESULT_MESSAGE' => $this->getMessageText('RESULT_MESSAGE'),
			 )
		   );


    return TRUE;
  }

}
?>