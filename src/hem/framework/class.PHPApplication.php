<?

/**
 * Include the classes used by the framework
 */
require_once('def.PHPApplication.php');


/**
 * PHP Application Framework Class
 *
 * Changes:
 *
 * 08.11.2004: Added function redirectToHomeApp()
 * 
 *
 * TODO: Describe me!
 * TODO: Check the config stuff created by kabir
 *       Should be done with a unique method (via globals, or via $params array)
 *       We want to differentiate betwwen Proj wide Conf and App conf
 *       App conf should be hidden from user
 *
 *  Config array:
 *  $param =  array(
 *
 *    '' => '',
 *    '' => '',
 *                  )
 *
 * Errormessages:
 * APP_FAILED -> Very bad, no database connection
 * NO_CSS_FOUND -> Themes support Activated, but no CSS file found
 *
 * @param array Array with the config for application
 * @author M.J.Kabir
 * @author Martin Loitzl <martin@loitzl.com>
 */

class PHPApplication
{
  function PHPApplication($param = null)
  {
    global $ON, $OFF, $TEMPLATE_DIR, $DEFAULT_LANGUAGE, $HEM_VERSION;
    //    global $MESSAGES
    global $DEFAULT_MESSAGE, $REL_APP_PATH, $REL_APP_ROOT,
      $REL_TEMPLATE_DIR;  //<-- TODO: check paths

    $this->app_name_ = $this->setDefault($param['app_name'], null);
    $this->app_breadcrumbs_ = Array();
    $this->app_version_ = $this->setDefault($param['app_version'], null);
    $this->app_type_ = $this->setDefault($param['app_type'], 'WEB');
    $this->app_db_url_ = $this->setDefault($param['app_db_url'], null);
    $this->app_debug_mode_ = $this->setDefault($param['app_debugger'], $OFF);
    $this->auto_connect_ = $this->setDefault($param['app_auto_connect'], TRUE);

    $this->error_ = array();
    $this->authorized_ = FALSE;
    $this->language_ = $DEFAULT_LANGUAGE;
    $this->check_browser_language_ = $this->setDefault($param['app_check_browser_language'], TRUE);
    $this->base_url_ = sprintf("%s%s", $this->getServer(), $REL_TEMPLATE_DIR);
    $this->app_path_ = $REL_APP_PATH;
    $this->app_root_ = $REL_APP_ROOT;
    $this->template_dir_ = $TEMPLATE_DIR;

    $this->error_file_ = $this->setDefault($param['app_error_file'], null);
    $this->label_file_ = $this->setDefault($param['app_label_file'], null);
    $this->message_file_ =$this->setDefault($param['app_message_file'], null);

    $this->user_auth_ = $this->setDefault($param['app_authentication'], FALSE);
    $this->user_auto_auth_ = $this->setDefault($param['app_auto_authenticate'], FALSE);
    $this->auth_admin_ = $this->setDefault($param['app_admin_auth'], FALSE);
    $this->user_auth_dsn_ = $this->setDefault($param['app_auth_dsn'], FALSE);
    $this->user_auth_logout_page_ = $this->setDefault($param['app_exit_point'], 'index.php');
    $this->user_auth_session_name_ = $this->setDefault($param['app_session_name'], 'PHPSESSION');

    $this->app_themes_ = $this->setDefault($param['app_themes'], FALSE);

	$this->hem_version_ = $HEM_VERSION;

    $this->render_boxes_ = $this->setDefault((isset($param['render_boxes'])?$param['render_boxes']:'TRUE'), FALSE);

    if (defined("DEBUGGER_LOADED") && $this->app_debug_mode_ == $ON)
      {
	if(empty($param['debug_color'])) 
	  {
	    $param['debug_color'] = 'red';
	  }
	$this->debugger_ = new Debugger ( array(
					       'color' => $param['debug_color'],
					       'prefix' => $this->app_name_,
					       'buffer' => $OFF) );
      }
    
    if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $this->check_browser_language_ == TRUE)
      {
	$language_code = trim(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
	if($language_code == 'de') $this->language_ = "DE";
	switch ($language_code)
	  {
	  case 'en':
	    $this->language_ = "US";
	    break;
	  case 'de':
	    $this->language_ = "DE";
	    break;
	  default:
	    break;
	  }
      }
     
    $this->setErrorHandler();

    if(!empty($this->app_db_url_) && $this->auto_connect_ && !$this->connect())
      {
	echo !empty($this->app_db_url_);
	echo $this->auto_connect_;
	echo $this->connect();
	die("APP_FAILED");
	$this->showPopup('APP_FAILED');
      }	  
    

    if(strstr($this->getAppType(), 'WEB'))
      {
	// TODO: Include LiveUser here!!! --> Done: clean up!

	if(defined("AUTH_HANDLER_LOADED") && $this->user_auth_)
	  {
	    $this->setAuthHandler();
	  }
	
	// Abstraction cause Admin Part in LiveUser is still beta
	if(defined("ADMIN_AUTH_HANDLER_LOADED") && $this->auth_admin_ && $this->user_auth_dsn_)
	  {
	    $this->setAdminAuthHandler();
	  }
	
	if($this->user_auto_auth_ && !$this->isAuthenticated()) $this->reauthenticate();

	if($this->user_auth_)
	  {
	    $this->user_id_ = (!is_null($this->getSessionField('SESSION_USER_ID', null))) ? $this->getSessionField('SESSION_USER_ID', null)
	      : $this->setUID($this->auth_handler_->getUID()) ;
	  }

	// TODO: Setup User Object here Done: --> programm User class!
	if($this->auto_connect_ && $this->dbi_->isConnected() && $this->user_auth_ )
	  {
	    $this->user_ = new User($this->user_id_, $this->dbi_);
	  }
	
	if(defined("THEME_HANDLER_LOADED") && $this->app_themes_ )
	  {
	    if($this->auto_connect_ && $this->dbi_->isConnected())
	      $this->theme_ = new Theme($this->dbi_);
	    else
	      $this->theme_ = null;
	  }


	if($this->user_auth_)
	  {
	    $user_language = $this->user_->getLanguageID();
	    if($user_language)
	      {
		$this->language_ = $user_language;
	      }
	  }
      }

    $this->has_error_ = null;

    $this->setMessageHandler();
    $this->setLabelHandler();
    $this->setActivityLog();

    if (get_magic_quotes_gpc()) {
      
      //      echo "Magic Quotes GPC are on";
      // Yes? Strip the added slashes
      
      //      $_REQUEST = array_map('stripslashes', $_REQUEST);
      $_GET = array_map(array('PHPApplication', 'stripslashes_deep'), $_GET);
      $_POST = array_map(array('PHPApplication', 'stripslashes_deep'), $_POST);
      $_COOKIE = array_map(array('PHPApplication', 'stripslashes_deep'), $_COOKIE);
      
    }
    //    else
    //      echo "Magic Quotes GPC are off";


  }

  /**
   * Returns the uid of the autenticated User
   *
   * @return int uid
   */
  function getUID()
  {
    return $this->user_id_;
  }
 /**
  * Sets the Session Variable uid
  *
  * @param int user id
  */
  function setUID($uid = null)
  {
    $this->setSessionField('SESSION_USER_ID', $uid);
    $this->user_id_ = $uid;
  }

  /**
   * Creates the AuthHandler object ($this->auth_handler_)
   *
   * @return void
   */
  function setAuthHandler()
  {
    // TODO: create conf here;
    $conf = array(
		  'auth_dsn' => $this->user_auth_dsn_,
		  'auth_exit_page' =>  $this->user_auth_logout_page_,
		  'auth_session_name' => $this->user_auth_session_name_
		  );


    $this->auth_handler_ =& new AuthHandler($conf);
  }

 /**
  * Creates the AdminAuthHandler object
  *
  * @return int uid
  */
  function setAdminAuthHandler()
  {
    $conf = array(
		  'auth_dsn' => $this->user_auth_dsn_,
		  'auth_exit_page' =>  $this->user_auth_logout_page_,
		  'auth_session_name' => $this->user_auth_session_name_
		  );


    $this->admin_auth_handler_ = new AdminAuthHandler($conf);

    //    print_r($this->admin_auth_handler_);

  }

  /**
   * Returns if the user authentication is valid
   *
   * Returns TRUE if current User is authenticated
   * FALSE if session, or other timeout has occurred
   * FALSE if not logged in
   *
   * @see LiveUser Docs for details
   *
   * @return boolean
   */
  function isAuthenticated()
  {
    
    //    return (($this->user_auto_auth_ ? $this->auth_handler_->isAuthenticated() : FALSE));
    return $this->auth_handler_->isAuthenticated();
    // && !is_null($this->getSessionField('SESSION_USER_ID', null)) );
  }

  /**
   * Redirects user to $HOME_APP
   *
   *
   *
   * @return void
   */
  function redirectToHomeApp()
  {
    global $HOME_APP;

    if(isset($HOME_APP) && !empty($HOME_APP))
      header("Location: $HOME_APP");
  }


 /**
  * Redirects the user to the Login Applikation
  *
  *
  * @return void
  */
  function reauthenticate()
  {
    global $AUTHENTICATION_URL;
    header("Location: $AUTHENTICATION_URL?url=".urlencode($this->self_url_));
  }

 /**
  * Returns the base url of the Application
  *
  *
  * @return string base_url
  */
  function getBaseUrl()
  {
    return $this->base_url_;
  }

 /**
  * Returns thee Server Part of the url
  *
  * with protocol, FQDN [and port]
  *
  * @return string server with all stuff
  */
  function getServer()
  {
    $this->setUrl();
    return $this->server_;
  }

 /**
  * Returns the relative Path of the Application
  *
  *
  * @return string path relative to document root
  */
  function getAppPath()
  {
    return $this->app_path_;
  }

 /**
  * Returns the fully qualified application Path
  *
  * Without parameters passed to the script, use $PHP_SELF here
  *
  * @return string fully qualified application path
  */
  function getFQAP()
  {
    // get fully qualified App Path
    return sprintf("%s%s", $this->server_, $this->app_path_);
  }

 /**
  * Returns the fully qualified application Path with the passed relative Application path
  *
  * Without parameters passed to the script, use @see $this->self_url_ here
  *
  * @param string an relative application path
  * @return string fully qualified application path
  */
  function getFQAN($thisApp = null)
  {
    return sprintf("%s/%s", $this->getFQAP(), $thisApp);
  }

 /**
  * Returns the template directory for this application
  *
  *
  * @return string template directory opf this application
  */
  function getTemplateDir()
  {
    return $this->template_dir_;
  }

 /**
  * Sets the url member variables
  *
  * Sets $this->server_ with protocol, fqdn and port
  * Sets $this->self_url_ with scriptname and parameters (uses $REQUEST_URI)
  *
  * @return void
  */
  function setUrl()
  {
    $row_protocol = $this->getEnvironment('SERVER_PROTOCOL');
    $port = $this->getEnvironment('SERVER_PORT');

    if($port == 80)
      {
	$port = null;
      }
    else
      {
	$port = ':' . $port;
      }

    $protocol = strtolower(substr($row_protocol, 0, strpos($row_protocol, '/')));
    $this->server_ = sprintf("%s://%s%s",
			     $protocol,
			     $this->getEnvironment('SERVER_NAME'),
			     $port);

    $this->self_url_ = sprintf("%s://%s%s%s",
			       $protocol,
			       $this->getEnvironment('SERVER_NAME'),
			       $port,
			       $this->getEnvironment('REQUEST_URI'));

  }

  /**
   * Returns $this->self_url:
   *
   *
   * @return the url of the current app, with request
   */
  function getSelfUrl()
  {
    return $this->self_url_;
  }


 /**
  * Terminates the Application
  *
  * Terminates the database handle
  * TODO: Shoul terminate LiveUser? ($this->auth_handler_)
  *
  * @return void
  */
  function terminate()
  {
    if($isset($this->dbi_))
      {
	if($this->dbi_->connected_)
	  {
	    $this->dbi_->disconnect();
	  }
      }
    // TODO: Integrate LiveUser here!!
    session_destroy();
    exit;
  }

 /**
  * Sets the ErrorHandler
  *
  * Sets $this->err_handler_ a multilanguage ErrorTextHandler
  *
  * @return void
  */
  function setErrorHandler()
  {
    if(defined("ERROR_HANDLER_LOADED"))
      {
	$this->err_handler_ = new ErrorHandler(
					       array(
						     'name' => $this->app_name_,
						     'language' => $this->language_,
						     'file' => $this->error_file_,
						     )
					       );
      }
  }

 /**
  * Returns the translation of an Error Message
  *
  * Returns the Message for the given code, translated to the current set language
  * @see $this->language_
  *
  * @param string error code
  * @return void
  */
  function getErrorText($code)
  {
    return $this->err_handler_->getMessage($code);
  }

 /**
  * Display an JavaScript PopUp Message
  *
  * Pops up a JavaScript Window with the transaltion of the given Error Message Code
  * Goes back one step in History if flag is 0
  * Closes window if flag is 'close'
  * Just displays the popup for everything else
  *
  * @param string error code
  * @param string flag
  * @return string Javascript Code for the PopUp
  */
  function showPopup($code, $flag = 0)
  {
    return (defined('ERROR_HANDLER_LOADED')) ?
      $this->err_handler_->alert($code, $flag) : FALSE;
  }

 /**
  * Sets the MessageHandler
  *
  * Sets $this->msg_handler_ a multilanguage MessageTextHandler
  *
  * @return void
  */
  function setMessageHandler()
  {
    if(defined("MESSAGE_HANDLER_LOADED"))
      {
	$this->msg_handler_ = new MessageHandler( 
					       array(
						     'name' => $this->app_name_,
						     'language' => $this->language_,
						     'file' => $this->message_file_,
						     )
					       );
      }
  }

 /**
  * Returns the translation of a Message
  *
  * Returns the Message for the given code, translated to the current set language
  * @see $this->language_
  *
  * @param string error code
  * @return void
  */
  function getMessageText($code)
  {
    return $this->msg_handler_->getMessage($code);
  }

 /**
  * Sets the LabelHandler
  *
  * Sets $this->lbl_handler_ a multilanguage LabelTextHandler
  *
  * @return void
  */
 function setLabelHandler()
  {
    if(defined("LABEL_HANDLER_LOADED"))
      {
	$this->lbl_handler_ = new LabelHandler(
					       array(
						     'name' => $this->app_name_,
						     'language' => $this->language_,
						     'file' => $this->label_file_,
						     )
					       );
      }
  }

 /**
  * Returns the translation of a Label
  *
  * Returns the Message for the given code, translated to the current set language
  * @see $this->language_
  *
  * @param string error code
  * @return void
  */
 function getLabelText($code)
  {
    return $this->lbl_handler_->getMessage($code);
  }


 /**
  * Sets the ActivityLog Class
  *
  * Sets $this->activity_log_
  *
  * @return void
  */
  function setActivityLog()
  {
    $this->activity_log_ =& new ActivityLog(0, $this->dbi_);
  }



/**
  * Logs an activity
  *
  * Logs th the activity with the given type, types are defines
  *
  *
  * @param string type of activity
  * @param string user
  * @param string description of activity, some optional text
  * @return void
  */
  function logActivity($act_type, $act_user = null, $act_description = null)
  {
    $this->activity_log_->logActivity($act_type, $act_user, $act_description);
  }
  

  function logInCallback()
  {
  }

  function logOutCallback()
  {
    //    global $APP_DB_URL;
    /*    header("Location: http://www.google.it");
    echo '<script>alert(\'LogoutCallback called\');</script>';

    $dbi = new DBI($APP_DB_URL);
    $activity_log =& new ActivityLog(0, $dbi);
    $activity_log->logActivity(ACT_LOGOUT, $this->getSessionField('SESSION_USER_ID', ''), 'logged out');*/
    //    $instance->logActivity(ACT_LOGOUT);

    //    $this->showPopup('LogoutCallback called');
  }


 /**
  * Displays the banner of the debugger
  *
  *
  *
  * @return void
  */
  // TODO: check if really needed. Banner is printed automatically by debugger class
  function showDebuggerBanner()
  {
    global $ON;

    if ( defined("DEBUGGER_LOADED") && $this->app_debug_mode_ == $ON)
      {
	$this->debugger_->printBanner();
      }
  }

 /**
  * Sets buffered debugging mode
  *
  * The Debuggeroutput is written to a buffer,
  * use @see dumpDebugInfo() to output the buffer
  *
  * @return void
  */
  function bufferDebugging()
  {
    global $ON;

    if( defined("DEBUGGER_LOADED") && $this->app_debug_mode_ == $ON )
    {
      $this->debugger_->setBuffer();
    }
  }

 /**
  * Prints out the Debug info collected in the debugger buffer
  *
  *
  *
  * @return string debug info
  */
  function dumpDebugInfo()
  {
    global $ON;

    if ( defined("DEBUGGER_LOADED") && $this->app_debug_mode_ == $ON)
      {
	$this->debugger_->flushBuffer();
      }
  }

 /**
  * Add a Debugmessage
  *
  * If App debugger is set to $ON, the message is added to the debugger,
  * if @see bufferDebugging() is set, output is collected in the debugger buffer
  *
  * @param string the messagae with the debugging information
  * @return void
  */
  function debug($msg)
  {
    global $ON;
    
    if( defined("DEBUGGER_LOADED") &&  $this->app_debug_mode_ == $ON)
      {
	$this->debugger_->write($msg);
      }
  }

 /**
  * Add an array to debug
  *
  * If App debugger is set to $ON, the array is passed the debugger,
  * if @see bufferDebugging() is set, output is collected in the debugger buffer
  *
  * @param array the array with the debugging information
  * @return void
  */
  function debugArray($hash = null)
  {
    global $ON;

    if( defined("DEBUGGER_LOADED") &&  $this->app_debug_mode_ == $ON)
      {
	$this->debugger_->debugArray($hash);
      }
  }

 /**
  * This function does the whole stuff
  *
  * Overwrite this function in the application derived from thsi Framework
  *
  * @return void
  */
  function run()
  {
    // implement me!!!
    $this->writeln("You need to overwrite this function (PHPApplication::run())!");
  }


 /**
  * Connect to the database
  *
  * Initalializes the Database abstraction object and connects it with the dsn given in
  * $this->app_db_url_
  *
  * Errors that ocurred are can be got with $this->dbi_->hasError() and $this->dbi_->getError()
  * Don't forget to call $this->dbi_->resetError() after error handling was done
  *
  * @returns boolean TRUE if connection is ok, FALSE otherwise
  */
  function connect($db_url = null)
  {
    if(empty($db_url))
      {
	$db_url = $this->app_db_url_;
      }
    
    if(defined("DBI_LOADED") && !empty($this->app_db_url_))
      {
	$this->dbi_ = new DBI($db_url);
	return $this->dbi_->isConnected();
      }
  }

 /**
  * Disconnect from the database
  *
  *
  * @return boolean TRUE if disconnected correctly
  */
  function disconnect()
  {
    $this->dbi_->disconnect();
    return !$this->dbi_->isConnected();
  }

 /**
  * Gets the Version of the Application
  *
  *
  * @return string application version
  */
  function getAppVersion()
  {
    return $this->app_version_;
  }

 /**
  * Gets the name of the Application
  *
  *
  * @return string application name
  */
  function getAppName()
  {
    return $this->app_name_;
  }


 /**
  * Gets the Type of Application
  *
  * Mostly WEB, but SHELL is also possible since PHP is now also usable for shell scripting
  *
  * @return string application version
  */
  function getAppType()
  {
    return $this->app_type_;
  }


 /**
  * Add an Error
  *
  * Adds an Error message to $this->error_ and sets $this->has_error_ TRUE
  *
  * @return boolean TRUE if everything was fine
  */
  // TODO: Convert this to a Session Messaging System!
  function setError($err = null)
  {
    if(isset($err))
      {
	array_push($this->error_, $err);
	$this->has_error_ = TRUE;
	return TRUE;
      }
    else 
      {
	return FALSE;
      }
  }

 /**
  * Returns TRUE if the Application has one or more Errors
  *
  * @return boolean TRUE if one or more errors occurred
  */
  function hasError()
  {
    return $this->has_error_;
  }

 /**
  * Reset Error buffer
  *
  * Resets the $this->error_ and sets $this->has_error_ FALSE
  * Don't forget to call it after ErrorHandling was done
  *
  * @return boolean TRUE if everything was fine
  */
  function resetError()
  {
    $this->error_ = null;
    $this->has_error_ = FALSE;
  }

 /**
  * Returns an Error
  *
  * Returns an Error at a time, call it as long @see $this->hasError() returns TRUE
  *
  * @return mixed FALSE if no errors are registered, an error otherwise
  */
  function getError()
  {
    if( $err=array_pop($this->error_) == null)
      {
	$this->has_error_ = FALSE;
	$this->error_ = null;
	return FALSE;
      }
    else
      {
	return $err;
      }
  }

 /**
  * Returns all Errors
  *
  * Returns all errors as array
  *
  * @return array the array with the errors
  */
  function getErrorArray()
  {
    return $this->error_;
  }


 /**
  * Dumps an Array
  *
  * Prints an Array in a pretty manner using <pre> tags, if App is an WEB Aplication
  * Don't use it in a production system, it is against the idea of Templating and Themes
  *
  * @param array An array to dump, an object can also be used
  * @return void
  */
  function dumpArray($a)
  {
    if(strstr($this->getAppType(), 'WEB'))
      {
	echo "<pre>";
	print_r($a);
	echo "</pre>";
      }
    else
      {
	print_r($a);
      }
  }

 /**
  * Dump myself
  *
  * Dumps the application object in its current state in a pretty manner using <pre> Tags
  * Very large output, not for production systems, it is against the idea of Templating and Themes
  *
  * @return void
  */
  function dump()
  {
    if(strstr($this->getAppType(), 'WEB'))
      {
	echo "<pre>";
	print_r($this);
	echo "</pre>";
      }
    else
      {
	print_r($this);
      }
  }

 /**
  * Output a message
  *
  * Writes a meessage with the correct newline character (<br/> for WEB, \n for SHELL)
  * Can be set with config settings $WWW_NEWLINE and $NEWLINE
  *
  * @param string A message
  * @return void
  */
  function writeln($msg = null)
  {
    global $WWW_NEWLINE;
    global $NEWLINE;

    if(!isset($WWW_NEWLINE)) $WWW_NEWLINE = "<br/>";
    if(!isset($NEWLINE)) $NEWLINE = "\n";
    //    $this->debug((strstr($this->getAppType(), 'WEB')) ? $WWW_NEWLINE : $NEWLINE);

    echo $msg;
    echo (strstr($this->getAppType(), 'WEB')) ? $WWW_NEWLINE : $NEWLINE;
  }

 /**
  * Shows a Status Page with a message
  *
  * Display a Status Page with the given Message using the Template given by
  * the config setting $STATUS_TEMPLATE
  * Sets the Template Varibales RETURN_URL to $app_path (a string of $this->app_path_ and $return_url)
  * and BASE_URL to, guess, $this->getBaseUrl() ;-)
  *
  * @param string A message
  * @param string A return url
  * @return void
  */
  // TODO: check if we really need this, should be done by the planned Session Messaging tool
  function showStatus($msg = null, $return_url = null)
  {
    global $STATUS_TEMPLATE;
    $tpl =& new HTML_Template_IT($this->template_dir_);

    $tpl->loadTemplatefile($STATUS_TEMPLATE, true, true);

    $tpl->setVariable('STATUS_MESSAGE', $msg );

    if(!preg_match('/^http:/', $return_url) && (!preg_match('/^\//', $return_url)))
      {
	$appPath = sprintf("%s/%s", $this->app_path_, $return_url);
      }
    else
      {
	$appPath = $return_url;
      }

    $tpl->setVariable('RETURN_URL', $app_path);
    $tpl->setVariable('BASE_URL', $this->getBaseUrl());

    $tpl->get();
  }


 /**
  * Returns the localized Date
  *
  * Returns the date given by $timestap (or current date, if left empty) in a correct localized form
  * FIXME: Not very elegant, could be config setting
  *
  * @param string usix timestamp
  * @return string localized date
  */
  function getLocDate($timestamp = null)
  {
    switch ($this->language_)
      {
      case 'US':
	return date('Y-m-d', $timestamp);
	break;
      case 'DE':
	return date('d.m.Y', $timestamp);
	break;
      default:
	return date('Y-m-d', $timestamp);
	break;
      }
  }

 /**
  * Returns the localized Time
  *
  * Returns the time given by $timestap (or current time, if left empty) in a correct localized form
  * FIXME: Not very elegant, could be config setting
  *
  * @param string usix timestamp
  * @return string localized time
  */
  function getLocTime($timestamp = null)
  {
    switch ($this->language_)
      {
      case 'US':
	return date('g:i:s a', $timestamp);
	break;
      case 'DE':
	return date('H:i:s', $timestamp);
	break;
      default:
	return date('Y-m-d', $timestamp);
	break;
      }
  }

 /**
  * Returns the given Key of the Environment
  *
  * Returns the value of the Environment given by key, or null if not set
  *
  * @see $_SERVER in PHP Docs
  *
  * @param string key
  * @return string value
  */
  function getEnvironment($key)
  {
    return !empty($_SERVER[$key]) ? $_SERVER[$key] : null;
  }


 /**
  * Returns the given Key of POST Variables
  *
  * It returns the $default (or null, when left empty) if not set
  * @see $_POST in PHP Docs
  *
  * @param string post field
  * @param string default to return if post field is empty
  * @return string value
  */
  // for security reasons. only requestfield is not good for sensitive data
  // can be tricked with url hacking
  function getPostRequestField($field, $default)
  {
    return (! empty($_POST[$field] )) ? $_POST[$field] : $default;
  }

 /**
  * Returns the given Key of GET Variables
  *
  * It returns the $default (or null, when left empty) if not set
  * @see $_GET in PHP Docs
  *
  * @param string get field
  * @param string default to return if get field is empty
  * @return string value
  */
  function getGetRequestField($field, $default)
  {
    return (! empty($_GET[$field] )) ? $_GET[$field] : $default;
  }

 /**
  * Returns the given Key of $_REQUEST Variables
  *
  * It returns the $default (or null, when left empty) if not set
  * @see $_REQUEST in PHP Docs
  *
  * Use it with care, cause submitted forms can be easily tricked by url parameters
  *
  * @param string request field
  * @param string default to return if request field is empty
  * @return string value
  */
  function getRequestField($field, $default = null)
  {
    return (! empty($_REQUEST[$field] )) ? $_REQUEST[$field] : $default;
  }

 /**
  * Returns the given Key of $_SESSION Variable
  *
  * It returns the $default (or null, when left empty) if not set
  * @see $_SESSION in PHP Docs
  *
  *
  * @param string session field
  * @param string default to return if request field is empty
  * @return string value
  */
  function getSessionField($field, $default = null)
  {
    return (! empty($_SESSION[$field] )) ? $_SESSION[$field] : $default;
  }

 /**
  * Sets the given Key of $_SESSION Variable
  *
  * It returns the $default (or null, when left empty) if not set
  * @see $_SESSION in PHP Docs
  *
  *
  * @param string session field
  * @param string value for the field
  * @return void
  */
  function setSessionField($field, $value = null)
  {
    $_SESSION[$field] = $value;
  }


 /**
  * Session Messaging, add message
  *
  * Adds a (translated) message to a serialzed array kept in the session environment, fo later output
  * @see $this->getAllSessionMessages() for retrieving messages
  *
  * FIXME: Is translatio necessary here?
  * @param string the message
  * @return void
  */
  function addSessionMessage($msg)
  {
    if(isset($_SESSION['MSG']) && !empty($_SESSION['MSG']))
      {
	$session_ser_msgs = $_SESSION['MSG'];
	$msgs = unserialize(stripslashes($session_ser_msgs));
	array_push($msgs, $this->getMessageText($msg));
	//	print_r($msgs);
	$ser_msgs = addslashes(serialize($msgs));
	$_SESSION['MSG'] = $ser_msgs;
      }
    else
      {
	$msgs = array();
	array_push($msgs, $this->getMessageText($msg));
	// print_r($msgs);
	$ser_msgs = addslashes(serialize($msgs));
	$_SESSION['MSG'] = $ser_msgs;
      }
  }

 /**
  * Returns TRUE if there are session messages left
  *
  * If there is a session variable called MSG, it returns TRUE
  *
  * @return boolean TRUE if messages are set
  */
  function hasSessionMessages()
  {
    return (!empty($_SESSION['MSG'])) ? TRUE : FALSE;
  }

 /**
  * Returns the messages as array
  *
  * Returns all session (translated)messages as array, false if nothing available
  *
  * @return mixed message array or FALSE if emtpy
  */
  function getAllSessionMessages()
  {
    if(isset($_SESSION['MSG']) && !empty($_SESSION['MSG']))
      {
	$return_msgs = array();
	$session_ser_msgs = $_SESSION['MSG'];
	$msgs = unserialize(stripslashes($session_ser_msgs));
	while($msg = array_pop($msgs))
	  {
	    $return_msgs[] = $msg;
	  }
	$_SESSION['MSG'] = null;
	return $return_msgs;
      }
    else
      {
	return FALSE;
      }
  }

 /**
  *  Returns $value if set, $default if not
  *
  * Checks if $value is set and returns it, returns $default if not set
  * TODO: Defaults should be set via class declarations
  * @param mixed Value to check
  * @param mixed default to return
  * @return mixed $value if set, $default if not
  */
  function setDefault($value, $default)
  {
    return (isset($value)) ? $value : $default ;
  }


 /**
  * Gets fileextension
  *
  * Returns everything after the first point in the string passed
  *
  * @param string filename
  * @return string extension
  */
  function getFileExtension($filename)
  {
    return substr(basename($filename), strpos(basename($filename), ".") + 1);
  }

 /**
  * Displays a screen
  *
  * Sets up the template given in $template_file, and calls $func with a reference
  * to the earlier set up template. In this function the Template Variables should be set.
  * Page is only displayed if $func returns TRUE.
  * Uses $POP_UP_MASTER_TEMPLATE and a new window if $pop_up_flag is TRUE
  *
  * @param string the template file to use
  * @param string display function to call
  * @param string the name of the application FIXME: not really used, use $this->getAppName()
  * @param boolean pop up flag, TRUE if screen is a popup
  * @return void
  */
  function showScreen($template_file, $func = null, $app_name = null, $pop_up_flag = FALSE )
  {
    $template =new TemplateHandler($this->template_dir_);
    $this->setupScreen($template, $template_file);

    //    $this->debug("Template File: ".$template_file);

    if ($func != null)
      {
	$this->debug("Calling Display function");
	$status = $this->$func($template);
      }
    if($status == TRUE)
      {
	$this->debug("Showing Page");
	$this->showPage($template->get(), $pop_up_flag);
      }
  }

 /**
  * Sets up the Screen to display
  *
  * Initializes the Template object with the template file
  *
  * @param object Reference to the template object
  * @param string the template file to set up
  */
  function setupScreen(&$t, $template_file)
  {
    $t->loadTemplatefile($template_file, true, true);
  }

 /**
  * Displays the page
  *
  * Sets up the Mastertemplate $MASTER_TEMPLATE in $MASTER_TEMPLATE_DIR, and sets:
  * BASE_URL to $this->getBaseURL(),
  * LOGO_URL to $LOGO_URL,
  * CSS_FILE to $this->getCSS(),
  * PAGE_TITLE to $this->getAppName(),
  * LOGIN to $this->getLoginBox(),
  * CONTENT to $content from $this->showScreen())
  *
  * @param string the content to fill into the master template
  * @param boolean Flag if page is a pop up page
  * @return void
  */
  // TODO: implement th whole Theme Story! This one just does nothing!
  function showPage($content = null, $pop_up_flag = FALSE)
  {
    global $MASTER_TEMPLATE_DIR, $MASTER_TEMPLATE;
    global $LOGO_URL;
    global $POP_UP_MASTER_TEMPLATE;


    if($this->app_themes_ == TRUE)
      {
	if(isset($MASTER_TEMPLATE_DIR) && (isset($MASTER_TEMPLATE) || isset($POP_UP_MASTER_TEMPLATE)))
	  {
	    //	    $this->debug("Showing template");
	    $this->mTempl = new TemplateHandler($MASTER_TEMPLATE_DIR);
	    if($pop_up_flag == TRUE)
	      {
		$this->setupScreen($this->mTempl, $POP_UP_MASTER_TEMPLATE);
	      }
	    else
	      {
		$this->setupScreen($this->mTempl, $MASTER_TEMPLATE);
	      }

	    $this->mTempl->setVar(array(
					'BASE_URL' => $this->getBaseURL(),
					'LOGO_URL' => $LOGO_URL,
					'CSS_FILE' => $this->getCSS(),
					'PAGE_TITLE' => $this->getAppName(),
					'CONTENT' => $content,
					'HEM_VERSION' => $this->hem_version_,
					));
	    $boxes = $this->getBoxes();
	    if(is_array($boxes))
	      {
		while(list($key, $value) = each($boxes))
		  {
		    $this->mTempl->setVar($key, $value);
		  }
		//		$this->dumpArray($boxes);
	      }
	    
	    $this->mTempl->show();
	  }
      }
    else
      {
	echo $content;
      }
  }


  function getBoxes()
  {
    global $BOXES, $APP_ROOT ;
    
    $return_array = array();
    if(isset($BOXES) && is_array($BOXES) && $this->render_boxes_)
      {
	$boxes = array_keys($BOXES);
	//	$this->debugArray($boxes);
	while($current_box_index = array_pop($boxes))
	  {
	    $current_box = $BOXES[$current_box_index];
	    $file_to_require = $APP_ROOT . "/". $current_box['class_file'];
	    if(file_exists($file_to_require) && is_file($file_to_require))
	      {
		//		$this->debug("File ".$APP_ROOT . "/". $current_box['class_file']." exists");
		require_once $APP_ROOT . "/" . $current_box['class_file'];

		$current_class = $current_box['class_name'];
		$box_object = new $current_class($this);
		$return_array[$current_box_index] = $box_object->$current_box['get_function']();
		//		$this->debug($box_object->$current_box['get_function']());
	      }
	    else
	      $this->debug("File ".$APP_ROOT . "/". $current_box['class_file']." doesnt exist");
	  }
	return $return_array;
      }
  }


 /**
  * Gets the CSS file for the Master template
  *
  * Checks if user has a preferred Theme (CSS), if this->app_themes_ is TRUE
  * returns $DEFAULT_CSS if user has no preferred css or if this->app_themes_ is FALSE
  *
  * @return mixed the css filename, or FALSE
  */
  function getCSS()
  {
    global $DEFAULT_CSS;
    // Get users preferenced css

    if( $this->auto_connect_ && $this->user_auto_auth_ && ($this->user_id_ != null) && isset($this->theme_) )
      {
	$user_theme_id = $this->user_->getThemeID();
	if( $this->user_->getThemeID() )
	  {
//	    $this->debug("This Users CSS: ".$this->theme_->getThemeCSS($user_theme_id));
	    return $this->theme_->getThemeCSS($user_theme_id);
	    //return 1; $this->theme_->getCSSFile();
	  }
	else
	  {
	    return (isset($DEFAULT_CSS)) ? $DEFAULT_CSS : FALSE;
	  }
      }
    else
      {
	if(isset($DEFAULT_CSS))
	  {
	    return $DEFAULT_CSS;
	  }
	else
	  {
	    $this->setError($NO_CSS_FOUND);
	    return FALSE;
	  }
      }
  }

 /**
  * Gets the login box for the master template
  *
  * Sets up $LOGIN_BOX_TEMPLATE from $MASTER_TEMPLATE_DIR and sets
  * TODO: describe variables here
  * TODO: Deprecated
  * @return string the content of the login box
  */
  // TODO: finish this one!
  function _getLoginBox()
  {
    global $MASTER_TEMPLATE_DIR, $LOGIN_BOX_TEMPLATE;
    $this->lTempl = new TemplateHandler($MASTER_TEMPLATE_DIR);
    $this->setupScreen($this->lTempl, $LOGIN_BOX_TEMPLATE);

    $this->lTempl->setVar(array(
				'ACTION' => 'post',
				'' => ''
				)
			  );

    if($this->user_auto_auth_ && $this->isAuthenticated())
      {
	$content = "<div align='right'>"; 
	$content.= "You are <a href='user_mgr/run.ChangeUser.php?url=".$_SERVER['PHP_SELF']."'>"
	  .$this->user_->first_name ." ".$this->user_->last_name
	  ."</a><br/>";
	$content.= "Go to <a href='framework/run.sampleApp.php'>sampleApp</a><br/>";
	$content.= "<a href='".$_SERVER['PHP_SELF']."?logout=1'>Logout</a><br/>";
	$content.= "</div>";
	return $content;

      }
    else
      return $this->lTempl->get();

  }

  /**
   * Returns TRUE if string is utf-8 encoded
   *
   * Taken from a anonymous post in a php.net posting
   *
   * @param string String to check
   * @return boolean TRUE if string is utf-8, false otherwise
   */
  function isUTF8($string)
  {
    if(!empty($string))
      {
	if (is_array($string))
	  {
	    $enc = implode('', $string);
	    return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
	  }
	else
	  {
	    return (utf8_encode(utf8_decode($string)) == $string);
	  }   
      }
    return FALSE;
  }


  /**
   * Returns a unique id of $length, or 32, if no length given
   *
   * taken from http://www.zend.com/codex.php?id=308&single=1
   *
   * @param int lenght lenght of the id
   * @param string pool pool of characters used for creation
   * @return string a string of 32 characters, that should be unique
   */

  function getUniqueId($length=32, $pool="")
  {
    // set pool of possible char
    if($pool == ""){
      //      $pool = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $pool = "abcdefghijklmnopqrstuvwxyz";
      $pool .= "0123456789";
    }// end if
    mt_srand ((double) microtime() * 1000000);
    $unique_id = "";
    for ($index = 0; $index < $length; $index++) {
      $unique_id .= substr($pool, (mt_rand()%(strlen($pool))), 1);
    }// end for
    return($unique_id);
  }// end get_unique_id


 /**
   * Strips slashes from a given array or string recursively
   *
   * taken from http://www.php.net/manual/en/function.get-magic-quotes-gpc.php
   *
   * @param mixed string or array to strip slashes from
   * @return mixed array or string with stripped slashes
   */
  function stripslashes_deep($value)
  {
    $value = is_array($value) ?
      
      array_map(array('PHPApplication', 'stripslashes_deep'), $value) :
      
      stripslashes($value);
    
    return $value;
  }
  
}
?>
