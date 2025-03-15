<?php

class loginApp extends PHPApplication
{
  
  function run()
  {
    global $MIN_USERNAME_SIZE, $MIN_PASSWORD_SIZE, $MAX_ATTEMPTS;
    global $APP_MENU;

    $url = $this->getRequestField('url');    

    $this->debug("Login attempts : " . $this->getSessionField('SESSION_ATTEMPTS'));

    if($this->isAuthenticated())
      {
	$this->debug("User already authenticated");
	$this->debug("Redirecting to $url");
	$url = (isset($url)) ? $url : 'none';
	$this->setUID($this->auth_handler_->getUID());
	// TODO: Log Users Acitivity
	$this->logActivity(ACT_LOGIN, $this->getPostRequestField('handle', null), 'Succesfully logged in');
	if($url == 'none')
	  $this->redirectToHomeApp();
	else
	  header("Location: $url");
      }
    else
      {
	// TODO: Log Users Acitivity
	$this->debug("Authentication failed");
	$this->setSessionField('SESSION_ATTEMPTS', $this->getSessionField('SESSION_ATTEMPTS', '0') +1 );
	$this->logActivity(ACT_LOGIN,
			   $this->getPostRequestField('handle', 'none'),
			   'has to logged in, or login failed '. $this->getSessionField('SESSION_ATTEMPTS', '0').' times'
			   );
	$this->displayLogin();
      }
  }
  
  function warn()
  {
    global $WARNING_URL;
    $this->debug("Came to warn the user $WARNING_URL");
    header("Location: $WARNING_URL");
  }
  
  function displayLogin()
  {
   global $LOGIN_TEMPLATE;

   $this->showScreen($LOGIN_TEMPLATE, 'displayLoginScreen', $this->getAppName());

  }

  function displayLoginScreen(& $tpl)
  {
    global $MAX_ATTEMPTS;
    global $email, $url;
    global $PHP_SELF, $FORGOTTEN_PASSWORD_APP;
    
    $this->debug("Now in Display function");


    $url = $this->getGetRequestField('url', null);

    // Do not redirect to logout url!!!
    $url = str_replace("&logout=1", "", $url);

    $this->debug($url);

    if(0)
      //$this->getSessionField("SESSION_ATTEMPTS") > $MAX_ATTEMPTS)
      {
	$this->warn();
      }

    $this->debug("Display login dialog box");

    $tpl->setCurrentBlock('main_block');
    
    $tpl->setVar(array(
			    'SELF_PATH' => $PHP_SELF,
			    'PAGE_TITLE' => $this->getAppName(),
			    'ATTEMPTS' => $this->getSessionField("SESSION_ATTEMPTS"),
			    'USERNAME' => $this->getRequestField('handle', ''),
			    'LABEL_USERNAME' => $this->getLabelText('LABEL_USERNAME'),
			    'LABEL_PASSWORD' => $this->getLabelText('LABEL_PASSWORD'),
			    'FORGOTTEN_PASSWORD_APP' => $FORGOTTEN_PASSWORD_APP,
			    'LOGIN_BUTTON' => $this->getLabelText('LOGIN_BUTTON'),
			    'CANCEL_BUTTON' => $this->getLabelText('CANCEL_BUTTON'),
			    'LABEL_FORGOTTEN_PASSWORD' => $this->getLabelText('FORGOTTEN_PASSWORD_APP'),
			    'BASE_URL' => sprintf("%s", $this->getBaseUrl()),
			    'REDIRECT_URL' => sprintf("%s", $url)
			    ));

    $tpl->parseCurrentBlock();

    return 1;
    }
}




?>