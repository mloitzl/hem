<?php
class Setup extends PHPApplication
{

  function run()
  {
    //    $this->dumpArray($_REQUEST);
    //    $this->dumpArray($_SERVER);

    $get_cmd = $this->getGetRequestField('cmd', null);
    $step = $this->getPostRequestField('step', null);

    $test_mail = $this->getPostRequestField('testmail', null);
    $db_init = $this->getPostRequestField('db_init', null);

    $this->config_file_ = "../conf/conf.host.php";

    if(!is_null($step))
      {
	switch ($step)
	  {
	  case 1:
	    $this->showFileAccessScreen();
	    break;
	  case 2:
	    $conf_filename = dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/conf/conf.host.php";
	    $image_db_filename = dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/image_db/";
	    $reports_filename = dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/reports/";


	    if(!is_writeable($conf_filename) || !is_writeable($image_db_filename) || !is_writeable($reports_filename))
	      $this->showFileAccessScreen();
	    else
	      $this->showPathScreen();
	    break;
	  case 3:
	    if(!$this->writePathsToConfigFile())
	      $this->showPathScreen();
	    else
	      $this->showSMTPScreen();
	    break;
	  case 4:
	    if(!$this->writeSMTPToConfigFile())
	      $this->showSMTPScreen();
	    if(!is_null($test_mail))
	      {
		$this->sendTestMail();
		$this->showSMTPScreen();
	      }
	    else
	      $this->showDatabaseScreen();
	    break;
	  case 5:
	    $this->showDBSetupScreen();
	    break;
	  case 6:
	    $config_ok = $this->writeDBToConfigFile();
	    $connection_ok = $this->checkDataBaseSetup();
	    if(!is_null($db_init))
	      $this->dataBaseInit();
	    if(!$config_ok || !$connection_ok)
	      $this->showDBSetupScreen();
	    else
	      $this->showFinalScreen();
	    break;
	  default:
	    $this->showFirstScreen();
	    break;
	  }
        }
    else
      {
	switch ($get_cmd)
	  {
	  case 'bla':
	    echo "Not implemented";
	    break;
	  default:
	    $this->showFirstScreen();
	    break;
	  }
      }
  }

  function showFirstScreen()
  {
    global $FIRST_SCREEN_TEMPLATE;
    
    $this->showScreen($FIRST_SCREEN_TEMPLATE, 'displayFirstScreen', $this->getAppName());    
  }


  function displayFirstScreen(& $tpl)
  {
    global $PHP_SELF;
    
    $message_text = '';

    // Server Environment

    // WebServer

    // PHP Version

    $tpl->setVar(array(
		       'FORM_ACTION' => $PHP_SELF, 
		       'FORM_METHOD' => "POST", 
		       'LABEL_PROCEED' => $this->getLabelText('LABEL_PROCEED_STEP_ACCESS_RIGHTS'),
		       'FIRST_SCREEN_TITLE' => $this->getLabelText('HEM_SETUP_TITLE'),
		       'MESSAGE_AGENDA' => $this->getMessageText('MESSAGE_AGENDA'),
		       )
		 );


    $this->debug("Reached function ");

    return TRUE;
  }

  function showPathScreen()
  {
    global $PATH_SCREEN_TEMPLATE;
    
    $this->showScreen($PATH_SCREEN_TEMPLATE, 'displayPathScreen', $this->getAppName());    
  }


  function displayPathScreen(& $tpl)
  {
    global $PHP_SELF;
    
    $message_text = '';

    $current_settings = $this->getConfigSettings();

    //    $this->dumpArray($current_settings);

    // Server Environment

    // WebServer

    // PHP Version

    $tpl->setCurrentBlock('main_block');

    $current_user_dir = $this->unQuote($current_settings['$USER_DIR']);

    $tpl->setVar(array(
		       'FORM_ACTION' => $PHP_SELF, 
		       'FORM_METHOD' => "POST", 
		       'LABEL_ABSOLUTE_PATH' => $this->getLabelText('LABEL_ABSOLUTE_PATH'),
		       'VALUE_ABSOLUTE' => (is_int(strpos($current_settings['$DOC_ROOT'], '$_SERVER')) && strpos($current_settings['$DOC_ROOT'], '$_SERVER') == 0) ? $_SERVER['DOCUMENT_ROOT']:$this->unQuote($current_settings['$DOC_ROOT']),
		       'LABEL_RELATIVE_PATH' => $this->getLabelText('LABEL_RELATIVE_PATH'),
		       'HOSTNAME' => "http://".$_SERVER['HTTP_HOST'],
		       'TEXTFIELD_SIZE_REL_PATH' => (69 - strlen("http://".$_SERVER['HTTP_HOST'])),
		       'VALUE_RELATIVE' => !empty($current_user_dir) ? $current_user_dir : dirname(dirname($_SERVER['SCRIPT_NAME'])),
		       'LABEL_PROCEED' => $this->getLabelText('LABEL_PROCEED_STEP_SMTP'),
		       'PATH_SCREEN_TITLE' => $this->getLabelText('PATH_SCREEN_TITLE'),
		       'MESSAGE_PATHS' => $this->getMessageText('MESSAGE_PATHS'),
		       )
		 );

    $tpl->parseCurrentBlock();

    return TRUE;
  }


  function showFileAccessScreen()
  {
    global $FILE_SCREEN_TEMPLATE;
    
    $this->showScreen($FILE_SCREEN_TEMPLATE, 'displayFileAccessScreen', $this->getAppName());    
  }


  function displayFileAccessScreen(& $tpl)
  {
    global $PHP_SELF;
    
    $message_text = '';

    // Server Environment

    // WebServer

    // PHP Version

    $some_notwriteable = 0;

    //    $conf_filename = dirname(dirname($_SERVER['PATH_TRANSLATED'])).$this->config_file_;
    $this->debug($this->config_file_);
    if(!is_writeable($this->config_file_))
      {
	$some_notwriteable = 1;
	$tpl->setCurrentBlock('message_block');
	$tpl->setVar(array(
			   'MESSAGE_FILES' => $this->getMessageText('MESSAGE_FILES_CONF'),
			   )
		     );
	$tpl->parseCurrentBlock();
      }

    $image_db_filename = dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/image_db/";
    $this->debug($image_db_filename);
    if(!is_writeable($image_db_filename))
      {
	$some_notwriteable = 1;
	$tpl->setCurrentBlock('message_block');
	$tpl->setVar(array(
			   'MESSAGE_FILES' => $this->getMessageText('MESSAGE_FILES_IMAGE_DB'),
			   )
		     );
	$tpl->parseCurrentBlock();
      }

    $reports_filename = dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/reports/";
    $this->debug($reports_filename);
    if(!is_writeable($reports_filename))
      {
	$some_notwriteable = 1;
	$tpl->setCurrentBlock('message_block');
	$tpl->setVar(array(
			   'MESSAGE_FILES' => $this->getMessageText('MESSAGE_FILES_REPORTS'),
			   )
		     );
	$tpl->parseCurrentBlock();
      }


    if(!$some_notwriteable)
      {
	$tpl->setCurrentBlock('message_block');
	$tpl->setVar(array(
			   'MESSAGE_FILES' => $this->getMessageText('MESSAGE_FILES_OK'),
			   )
		     );
	$tpl->parseCurrentBlock();
      }

    $tpl->setCurrentBlock('main_block');

    $tpl->setVar(array(
		       'FORM_ACTION' => $PHP_SELF, 
		       'FORM_METHOD' => "POST", 
		       'LABEL_PROCEED' => $this->getLabelText('LABEL_PROCEED_STEP_PATHS'),
		       'FILES_SCREEN_TITLE' => $this->getLabelText('FILES_SCREEN_TITLE'),
		       )
		 );

    $tpl->parseCurrentBlock();

    return TRUE;
  }


  function showSMTPScreen()
  {
    global $SMTP_SCREEN_TEMPLATE;
    
    $this->showScreen($SMTP_SCREEN_TEMPLATE, 'displaySMTPScreen', $this->getAppName());    
  }


  function displaySMTPScreen(& $tpl)
  {
    global $PHP_SELF;
    
    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
	while($msg = array_pop($messages))
	  {
	    $message_text.=$msg;
	  }
      }

    if(!is_null($this->getSessionField('smtp_error', null)))
      $message_text.=$this->getMessageText("SERVER_ANSWER").":&nbsp;".$this->getSessionField('smtp_error', null);

    $tpl->setVar('MESSAGES', $message_text);

    $current_settings = $this->getConfigSettings();
    
    //    $this->dumpArray($current_settings);

    $current_smtp_host = $this->unQuote($current_settings['$SMTP_HOST']);
    $current_smtp_sender_address = $this->unQuote($current_settings['$SMTP_SENDER_ADDRESS']);
    $current_smtp_username = $this->unQuote($current_settings['$SMTP_USERNAME']);
    $current_smtp_pass = $this->unQuote($current_settings['$SMTP_PASS']);


    $tpl->setCurrentBlock('main_block');

    $tpl->setVar(array(
		       'FORM_ACTION' => $PHP_SELF, 
		       'FORM_METHOD' => "POST", 
		       'LABEL_SMTP_HOST' => $this->getLabelText('LABEL_SMTP_HOST'),
		       'VALUE_SMTP_HOST' => !empty($current_smtp_host) ? $current_smtp_host : 'localhost',
		       'LABEL_SMTP_SENDER' => $this->getLabelText('LABEL_SMTP_SENDER'),
		       'VALUE_SMTP_SENDER' => !empty($current_smtp_sender_address) ? $current_smtp_sender_address : 'noreply@some.domain',
		       'LABEL_SMTP_USER' => $this->getLabelText('LABEL_SMTP_USER'),
		       'VALUE_SMTP_USER' => !empty($current_smtp_username) ? $current_smtp_username : '',
		       'LABEL_SMTP_PASS' => $this->getLabelText('LABEL_SMTP_PASS'),
		       'VALUE_SMTP_PASS' => !empty($current_smtp_pass) ? $current_smtp_pass : '',
		       'LABEL_SEND_TESTMAIL' => $this->getLabelText('LABEL_SEND_TESTMAIL'),
		       'LABEL_TO' => $this->getLabelText('LABEL_TO'),
		       'VALUE_TEST_RECIPIENT' => $this->getSessionField('test_recipient', ''),
		       'LABEL_PROCEED' => $this->getLabelText('LABEL_PROCEED_STEP_DBCONNECTION'),
		       'SMTP_SCREEN_TITLE' => $this->getLabelText('SMTP_SCREEN_TITLE'),
		       'MESSAGE_PATHS' => $this->getMessageText('MESSAGE_PATHS'),
		       )
		 );

    $tpl->parseCurrentBlock();

    return TRUE;
  }



  function showDBSetupScreen()
  {
    global $DB_SETUP_SCREEN_TEMPLATE;
    
    $this->showScreen($DB_SETUP_SCREEN_TEMPLATE, 'displayDBSetupScreen', $this->getAppName());    
  }


  function displayDBSetupScreen(& $tpl)
  {
    global $PHP_SELF, $APP_ROOT;
    global $NUMBER_OF_DB_TABLES;

    
    $message_text = '';

    $dbms = $this->getPostRequestField('dbms', null);


    if(is_null($dbms))
      $dbms = $this->getGetRequestField('dbms', null);

    $current_settings = $this->getConfigSettings();

    if($dbms == 'sqlite')
      $dsn= 'sqlite:///'.$APP_ROOT.'/'.$this->unQuote($current_settings['$SQLITE_DB_FILE']);
    else
      $dsn = "mysql://".$this->unQuote($current_settings['$AUTH_DB_USER']).":".$this->unQuote($current_settings['$AUTH_DB_PASS'])."@".$this->unQuote($current_settings['$AUTH_DB_HOST'])."/".$this->unQuote($current_settings['$AUTH_DB_NAME']);


    require_once 'DB.php';
    $dbh = new DBI($dsn);

//    $this->dumpArray($current_settings);

    if($dbms == 'sqlite')
      {
		$error = FALSE;
		
		if(!$dbh->connected_)
			$error = TRUE;
		else
			$result = $dbh->query("SELECT name FROM sqlite_master WHERE type = 'table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type = 'table'");
	
//		$this->writeln("Connected:" . $dbh->connected_);
	
		if(!$error || !$dbh->hasError())
		{
			$tpl->setCurrentBlock('db_init_block');
	
			if(!$result || $error || ($result->numRows() != $NUMBER_OF_DB_TABLES))
			  	$tpl->setVar('LABEL_DB_INIT', $this->getLabelText('LABEL_DB_INIT'));
			else
		  		$tpl->setVar('LABEL_DB_INIT', $this->getLabelText('LABEL_DB_REINIT'));
	
			$tpl->parseCurrentBlock();
	
		}
		
		$tpl->setCurrentBlock('sqlite_block');

		if(!$dbh->hasError())
	  	{
		    $tpl->setVar(array(
			       	'MESSAGE_SQLITE_CONNECTION' => $this->getMessageText('MESSAGE_SQLITE_CONNECTION_GOOD'),
			       	)
			 	);
	    }
		else
	  	{
//	  		$this->writeln($dbh->getError());
//	  		$this->writeln($dsn);
			$send_user_back = 0;
			
			if(extension_loaded('SQLite'))
			{
		    	$tpl->setVar(array(
			       	'MESSAGE_SQLITE_CONNECTION' => $this->getMessageText('MESSAGE_SQLITE_CONNECTION_NOT_GOOD'),
			       	)
			 	);
		 		$send_user_back = 1;
			}
			else
			{
				$tpl->setVar(array(
			       	'MESSAGE_SQLITE_CONNECTION' => $this->getMessageText('MESSAGE_NOSQLITE'),
			       	)
			 	);
			 	$send_user_back = 1;
			}		 
			 
	  	}

		if($send_user_back)
		{	
			$tpl->setCurrentBlock('sqlite_back_block');
			$tpl->setVar('LABEL_BACK', $this->getLabelText('LABEL_BACK'));
			$tpl->parseCurrentBlock();
		}
		else
		{
			$tpl->setCurrentBlock('sqlite_proceed_block');
			$tpl->setVar('LABEL_PROCEED_SQLITE', $this->getLabelText('LABEL_PROCEED_STEP_FINAL'));			
			$tpl->parseCurrentBlock();
		}


		$tpl->setVar(array(
		   		'FORM_ACTION_SQLITE' => $PHP_SELF, 
		   		'FORM_METHOD_SQLITE' => "POST", 
			   	'DB_SETUP_SCREEN_TITLE' => $this->getLabelText('DB_SETUP_SCREEN_TITLE'),
		   	)
	     );
	
		$tpl->parseCurrentBlock();
      }
    else
      {
		$error = FALSE;
		
		if(!$dbh->connected_)
			$error = TRUE;
		else
			$result = $dbh->query("SHOW TABLES");
		
		
		if($result && !$error && !$dbh->hasError())
	  	{
		    $tpl->setCurrentBlock('db_init_block');

	    	if($error || ($result->numRows() != $NUMBER_OF_DB_TABLES))
		      	$tpl->setVar('LABEL_MYSQL_DB_INIT', $this->getLabelText('LABEL_DB_INIT'));
	    	else
	      		$tpl->setVar('LABEL_MYSQL_DB_INIT', $this->getLabelText('LABEL_DB_REINIT'));
	    
	    	$tpl->parseCurrentBlock();
	    
	    	$tpl->setCurrentBlock('mysql_block');
	    
	    	$tpl->setVar(array(
			       	'MESSAGE_MYSQL_CONNECTION' => $this->getMessageText('MESSAGE_MYSQL_CONNECTION_GOOD'),
			       	'LABEL_DB_TABLES' => $this->getLabelText('LABEL_DB_TABLES'),
			       	'VALUE_DB_TABLES' => $result->numRows(),
			       	)
			 	);
	    	$tpl->setCurrentBlock('db_init_block');
	  	}
		else
	  	{
		    $tpl->setVar(array(
			       	'MESSAGE_MYSQL_CONNECTION' => $this->getMessageText('MESSAGE_MYSQL_CONNECTION_NOT_GOOD'),
			       	'LABEL_DB_TABLES' => $this->getLabelText('LABEL_DB_ERROR'),
			       	'VALUE_DB_TABLES' => $dbh->getError(),
			       	)
			 	);
	  	}
	
	$tpl->setVar(array(
			   'FORM_ACTION' => $PHP_SELF, 
			   'FORM_METHOD' => "POST",
			   'LABEL_HOSTNAME' => $this->getLabelText('LABEL_HOSTNAME'),
			   'VALUE_HOSTNAME' => $this->unQuote($current_settings['$AUTH_DB_HOST']),
			   'LABEL_DBNAME' => $this->getLabelText('LABEL_DBNAME'),
			   'VALUE_DBNAME' => $this->unQuote($current_settings['$AUTH_DB_NAME']),
			   'LABEL_USERNAME' => $this->getLabelText('LABEL_USERNAME'),
			   'VALUE_USERNAME' => $this->unQuote($current_settings['$AUTH_DB_USER']),
			   'LABEL_PASS' => $this->getLabelText('LABEL_PASS'),
			   'VALUE_PASS' => $this->unQuote($current_settings['$AUTH_DB_PASS']),
			   'LABEL_PROCEED_MYSQL' => $this->getLabelText('LABEL_PROCEED_STEP_FINAL'),
			   'DB_SETUP_SCREEN_TITLE' => $this->getLabelText('DB_SETUP_SCREEN_TITLE'),
			   'MESSAGE_PATHS' => $this->getMessageText('MESSAGE_PATHS'),
			   )
		     );
	
	$tpl->parseCurrentBlock();
      }

    return TRUE;
  }


  function checkDataBaseSetup()
  {
    global $NUMBER_OF_DB_TABLES, $APP_ROOT;

    $current_settings = $this->getConfigSettings();

    $dbms = $this->getPostRequestField('dbms', null);
    if(is_null($dbms))
      $dbms = $this->getGetRequestField('dbms', null);
    
    if($dbms == 'sqlite')
    {
      $dsn= 'sqlite:///'.$APP_ROOT.'/'.$this->unQuote($current_settings['$SQLITE_DB_FILE']);
      $query = "SELECT name FROM sqlite_master WHERE type = 'table' ";
//      		"UNION ALL SELECT name FROM sqlite_temp_master WHERE type = 'table'";
    }
    else 
    {
      $dsn = "mysql://".$this->unQuote($current_settings['$AUTH_DB_USER']).":".$this->unQuote($current_settings['$AUTH_DB_PASS'])."@".$this->unQuote($current_settings['$AUTH_DB_HOST'])."/".$this->unQuote($current_settings['$AUTH_DB_NAME']);
      $query = "SHOW TABLES";
    }
    
    require_once 'DB.php';
    $dbh = new DBI($dsn);
    
    if($dbh->hasError() || !$dbh->connected_)
    {
      	return FALSE;
    }

    if($dbms == 'sqlite' && $dbh->connected_)
    	return TRUE;
    	

    $result = $dbh->query($query);

    if(!$result || $result->numRows() != $NUMBER_OF_DB_TABLES)
      return FALSE;

    return TRUE;
  }


  function dataBaseInit()
  {
    $dbms = $this->getPostRequestField('dbms', null);

    if(is_null($dbms))
      $dbms = $this->getGetRequestField('dbms', null);

    $current_settings = $this->getConfigSettings();

    $this->debug("Inititialising Database");

    if($dbms == 'mysql')
      {
	// Init MySQL Database
	
	$dsn = "mysql://".$this->unQuote($current_settings['$AUTH_DB_USER']).":".$this->unQuote($current_settings['$AUTH_DB_PASS'])."@".$this->unQuote($current_settings['$AUTH_DB_HOST'])."/".$this->unQuote($current_settings['$AUTH_DB_NAME']);
	
	
	// drop old tables

	$drop_query = "DROP TABLE `test_activity`, `test_environment`, `test_environment_attributes`, `test_environment_data`, `test_finding`, `test_finding_rate`, `test_heuristic`, `test_heuristic_set`, `test_liveuser_applications`, `test_liveuser_applications_seq`, `test_liveuser_area_admin_areas`, `test_liveuser_areas`, `test_liveuser_areas_seq`, `test_liveuser_group_subgroups`, `test_liveuser_grouprights`, `test_liveuser_groups`, `test_liveuser_groups_seq`, `test_liveuser_groupusers`, `test_liveuser_languages`, `test_liveuser_languages_seq`, `test_liveuser_perm_users`, `test_liveuser_perm_users_seq`, `test_liveuser_right_implied`, `test_liveuser_right_scopes`, `test_liveuser_rights`, `test_liveuser_rights_seq`, `test_liveuser_translations`, `test_liveuser_userrights`, `test_liveuser_users`, `test_liveuser_users_seq`, `test_manager_evaluator_finding`, `test_project`, `test_project_user`, `test_rating_scale`, `test_rating_scale_value`, `test_ratingscheme`, `test_ratingscheme_scale`, `test_report_element`, `test_screenshot`, `test_themes`, `test_translation`, `test_user_attributes`, `test_user_pref`;";
	  
	require_once 'DB.php';
	$dbh = new DBI($dsn);
	  
	$result = $dbh->query($drop_query);
	
	if(!PEAR::isError($result))
	  if($this->importMySQLDump($dbh))
	    return TRUE;
	  else
	    return FALSE;
	else
	  {
	    echo $result->getMessage().", ".$result->getUserInfo()."<br />";
	  }
	// import dump
      }
    else if($dbms == 'sqlite')
      {
	// Init SQLite Database

	// move original file to hem.sqlite

      }

  }



  function showDataBaseScreen()
  {
    global $DB_SCREEN_TEMPLATE;
    
    $this->showScreen($DB_SCREEN_TEMPLATE, 'displayDataBaseScreen', $this->getAppName());    
  }


  function displayDataBaseScreen(& $tpl)
  {
    global $PHP_SELF;
    
    $message_text = '';

    // Server Environment

    // WebServer

    // PHP Version


    $current_settings = $this->getConfigSettings();
    
    //    $this->dumpArray($current_settings);

    $tpl->setCurrentBlock('main_block');

    if($this->unQuote($current_settings['$USE_DATABASE']) == 'sqlite')
      $tpl->setVar('SQLITE_CHECKED', 'checked="checked"');
    else
      $tpl->setVar('MYSQL_CHECKED', 'checked="checked"');

      
    
    $tpl->setVar(array(
		       'FORM_ACTION' => $PHP_SELF, 
		       'FORM_METHOD' => "POST", 
		       'MESSAGE_MYSQL' => $this->getMessageText('MESSAGE_MYSQL'),
		       'MESSAGE_SQLITE' => $this->getMessageText('MESSAGE_SQLITE'),
		       'LABEL_PROCEED' => $this->getLabelText('LABEL_PROCEED_STEP_DB_SETUP'),
		       'DB_SCREEN_TITLE' => $this->getLabelText('DB_SCREEN_TITLE'),
		       'MESSAGE_PATHS' => $this->getMessageText('MESSAGE_PATHS'),
		       )
		 );
    
    $tpl->parseCurrentBlock();

    return TRUE;
  }


  function showFinalScreen()
  {
    global $FINAL_SCREEN_TEMPLATE;
    
    $this->showScreen($FINAL_SCREEN_TEMPLATE, 'displayFinalScreen', $this->getAppName());    
  }



  function displayFinalScreen(& $tpl)
  {
    global $PHP_SELF;
    
    $message_text = '';

    $tpl->setVar(array(
		       'FINAL_SCREEN_TITLE' => $this->getLabelText('FINAL_SCREEN_TITLE'),
		       'MESSAGE_SETUP_DEACTIVATION' => $this->getMessageText('MESSAGE_SETUP_DEACTIVATION'),
		       )
		 );
    
    $tpl->parseCurrentBlock();

    return TRUE;
  }

  function writePathsToConfigFile()
  {

    $absolute_path = $this->getPostRequestField('absolute', null);
    $relative_path = $this->getPostRequestField('relative', null);

    if(!is_null($absolute_path))
      {
	$handle = fopen($this->config_file_, "r");
	
	$out_buffer = '';
	while (!feof($handle)) {
	  $buffer = fgets($handle, 4096);
	  if(substr(trim($buffer),0,2)!='//' && $buffer !== "\n")
	  //	  if(!preg_match('/\/\//', $buffer) && $buffer !== "\n")
	    {
	      $this->debug("Searching: ".$buffer." pos: ".strpos($buffer, '$DOC_ROOT'));

	      if(is_int(strpos($buffer, '$DOC_ROOT')) && strpos($buffer, '$DOC_ROOT') == 0)
		{
		  $this->debug("Changed DocRoot setting");

		  if($absolute_path == $_SERVER['DOCUMENT_ROOT'])
		    $out_buffer.= '$DOC_ROOT = $_SERVER[\'DOCUMENT_ROOT\']; // Written by HEM Setup '.date("F j, Y, g:i a")."\n";
		  else
		    $out_buffer.= "$DOC_ROOT = \"$absolute\"; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
		}
	      else if(is_int(strpos($buffer, '$USER_DIR')) && strpos($buffer, '$USER_DIR') == 0)
		{
		  $this->debug("Changed User_dir setting");
		  if(!is_null($relative_path))
		    $out_buffer.= '$USER_DIR'." =  '$relative_path';  // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
		  else
		    $out_buffer.= '$USER_DIR'." =  '';  // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
		}
	      else if(is_int(strpos($buffer, '$PROJECT_NAME')) && strpos($buffer, '$PROJECT_NAME') == 0)
		{
		  $out_buffer.= '$PROJECT_NAME'." =  '';  // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
		}

	      else
		{
		  $out_buffer.= $buffer;
		}

	    }
	  else
	    $out_buffer.=$buffer;
	}
	fclose($handle);
	
	$out_handle = fopen($this->config_file_, "w");
	if(!@fwrite($out_handle, $out_buffer))
	  {
	    $this->addSessionMessage('COULD_NOT_WRITE_CONFIG_FILE');
	    $this->debugArray($this->getAllSessionMessages());
	    $this->debug($out_buffer);

	    return FALSE;
	  }

	fclose($out_handle);
      }
    

    return TRUE;
  }

  function writeSMTPToConfigFile()
  {

    $smtp_host = $this->getPostRequestField('smtp_host', null);
    $smtp_sender = $this->getPostRequestField('smtp_sender', null);
    $smtp_user = $this->getPostRequestField('smtp_user', null);
    $smtp_pass = $this->getPostRequestField('smtp_pass', null);

    if(!is_null($smtp_host))
      {
	$handle = fopen($this->config_file_, "r");
	
	$out_buffer = '';
	while (!feof($handle)) {
	  $buffer = fgets($handle, 4096);
	  if(substr(trim($buffer),0,2)!='//' && $buffer !== "\n")
	    //	  if(!preg_match('/\/\//', $buffer) && $buffer !== "\n")
	    {
	      $this->debug("Searching: ".$buffer." pos: ".strpos($buffer, '$DOC_ROOT'));
	      
	      if(is_int(strpos($buffer, '$SMTP_HOST')) && strpos($buffer, '$SMTP_HOST') == 0)
		{
		  $this->debug("Changed Smtp Host setting");
		  
		  $out_buffer.= '$SMTP_HOST'." = '$smtp_host'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
		}
	      else if(is_int(strpos($buffer, '$SMTP_SENDER_ADDRESS')) && strpos($buffer, '$SMTP_SENDER_ADDRESS') == 0)
		{
		  $this->debug("Changed Smtp Sender setting");
		  
		  $out_buffer.= '$SMTP_SENDER_ADDRESS'." = '$smtp_sender'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
		}
	      else if(is_int(strpos($buffer, '$SMTP_USERNAME')) && strpos($buffer, '$SMTP_USERNAME') == 0)
		{
		  $this->debug("Changed Smtp user setting");
		  
		  $out_buffer.= '$SMTP_USERNAME'." = '$smtp_user'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
		}
	      else if(is_int(strpos($buffer, '$SMTP_PASS')) && strpos($buffer, '$SMTP_PASS') == 0)
		{
		  $this->debug("Changed Smtp pass setting");

		  $out_buffer.= '$SMTP_PASS'." = '$smtp_pass'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
		}
	      else
		$out_buffer.=$buffer;

	    }
	  else
	    $out_buffer.=$buffer;
	}
	fclose($handle);
	
	$out_handle = fopen($this->config_file_, "w");
	if(!@fwrite($out_handle, $out_buffer))
	  {
	    $this->addSessionMessage('COULD_NOT_WRITE_CONFIG_FILE');
	    $this->debugArray($this->getAllSessionMessages());
	    $this->debug($out_buffer);

	    return FALSE;
	  }
	  fclose($out_handle);
	  
      }
    return TRUE;
  }


  // Sqlite is not DONE!!
  function writeDBToConfigFile()
  {
    $dbms = $this->getPostRequestField('dbms', null);

    if(!is_null($dbms) && $dbms == 'mysql')
      {
		$this->debug("Writing Config for MySQL");

		$hostname = $this->getPostRequestField('hostname', null);
		$dbname = $this->getPostRequestField('dbname', null);
		$mysql_user = $this->getPostRequestField('username', null);
		$mysql_pass = $this->getPostRequestField('pass', null);

		if(!is_null($hostname))
	  	if($this->writeMySQLConfig($hostname, $dbname, $mysql_user, $mysql_pass))
		    return TRUE;
	  	else
		    return FALSE;
      	}
    else if(!is_null($dbms) && $dbms == 'sqlite')
      	{
      		if($this->writeSQLiteConfig())
      			return TRUE;
      		else
      			return FALSE;
      	}
    else
      return FALSE;
  }


  function writeMySQLConfig($hostname, $dbname, $mysql_user, $mysql_pass)
  {
    $handle = fopen($this->config_file_, "r");
    
    $out_buffer = '';
    while (!feof($handle)) {
      $buffer = fgets($handle, 4096);
      if(substr(trim($buffer),0,2)!='//' && $buffer !== "\n")
	{
	  $this->debug("Searching: ".$buffer." pos: ".strpos($buffer, '$USE_DATABASE'));
	  
	  if(is_int(strpos($buffer, '$USE_DATABASE')) && strpos($buffer, '$USE_DATABASE') == 0)
	    {
	      $this->debug("Changed DB setting");
	      
	      $out_buffer.= '$USE_DATABASE'." = 'mysql'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
	    }
	  else if(is_int(strpos($buffer, '$AUTH_DB_HOST')) && strpos($buffer, '$AUTH_DB_HOST') == 0)
	    {
	      $this->debug("Changed Host setting");
	      
	      $out_buffer.= '$AUTH_DB_HOST'." = '$hostname'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
	    }
	  else if(is_int(strpos($buffer, '$AUTH_DB_NAME')) && strpos($buffer, '$AUTH_DB_NAME') == 0)
	    {
	      $this->debug("Changed DB Name setting");
	      
	      $out_buffer.= '$AUTH_DB_NAME'." = '$dbname'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
	    }
	  else if(is_int(strpos($buffer, '$AUTH_DB_USER')) && strpos($buffer, '$AUTH_DB_USER') == 0)
	    {
	      $this->debug("Changed DB Username setting");
	      
	      $out_buffer.= '$AUTH_DB_USER'." = '$mysql_user'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
	    }
	  else if(is_int(strpos($buffer, '$AUTH_DB_PASS')) && strpos($buffer, '$AUTH_DB_PASS') == 0)
	    {
	      $this->debug("Changed DB Password setting");
	      
	      $out_buffer.= '$AUTH_DB_PASS'." = '$mysql_pass'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
	    }
	  else
	    $out_buffer.=$buffer;
	  
	}
      else
	$out_buffer.=$buffer;
    }
    fclose($handle);
    
    $out_handle = fopen($this->config_file_, "w");
    if(!@fwrite($out_handle, $out_buffer))
      {
	$this->addSessionMessage('COULD_NOT_WRITE_CONFIG_FILE');
	$this->debugArray($this->getAllSessionMessages());
	$this->debug($out_buffer);
	
	return FALSE;
      }
    fclose($out_handle);
    
    return TRUE;
  }
  

  function writeSQLiteConfig()
  {
    $handle = fopen($this->config_file_, "r");
    
    $out_buffer = '';
    while (!feof($handle)) {
      $buffer = fgets($handle, 4096);
      if(substr(trim($buffer),0,2)!='//' && $buffer !== "\n")
	{
	  $this->debug("Searching: ".$buffer." pos: ".strpos($buffer, '$USE_DATABASE'));
	  
	  if(is_int(strpos($buffer, '$USE_DATABASE')) && strpos($buffer, '$USE_DATABASE') == 0)
	    {
	      $this->debug("Changed DB setting");
	      
	      $out_buffer.= '$USE_DATABASE'." = 'sqlite'; // Written by HEM Setup ".date("F j, Y, g:i a")."\n";
	    }
	  else
	    $out_buffer.=$buffer;
	  
	}
      else
	$out_buffer.=$buffer;
    }
    fclose($handle);
    
    $out_handle = fopen($this->config_file_, "w");
    if(!@fwrite($out_handle, $out_buffer))
      {
	$this->addSessionMessage('COULD_NOT_WRITE_CONFIG_FILE');
	$this->debugArray($this->getAllSessionMessages());
	$this->debug($out_buffer);
	
	return FALSE;
      }
    fclose($out_handle);
    
    return TRUE;
  }



  function unQuote($string)
  {
    if(substr($string, 0, 1) == "'")
      return str_replace("'", '', $string);
    else if(substr($string, 0, 1) == '"')
      return str_replace('"', '', $string);
    else
      return $string;
  }


  /**
   * Parses a config file and returns a hash, where the key is the variablename 
   * and the value the value of that variable
   *
   * Taken from the comments in http://at.php.net/file
   * Stripping off the comments added by martin@loitzl.com
   *
   * @return array config settings
   */
  function getConfigSettings()
  {
    $array_tmp = file($this->config_file_);
    foreach($array_tmp as $v)
      {
	if ((substr(trim($v),0,1)!=';') && (substr_count($v,'=')>=1))
	  {//Line mustn't start with a ';' and must contain at least one '=' symbol.
	    $pos = strpos($v, '=');
	    // Strip off comments in value
	    $value_parts = explode(';', trim(substr($v, $pos+1)));
	    $config[trim(substr($v,0,$pos))] = $value_parts[0];
	  }
      }
    unset($array_tmp);

    return $config;
  }

    
  function sendTestMail()
  {
    require_once 'Mail.php';

    $test_recipient = $this->getPostRequestField('test_recipient', null);

    if(!is_null($test_recipient))
      {
	$this->setSessionField('test_recipient', $test_recipient);

	$current_settings = $this->getConfigSettings();
	
	$smtp_username = $this->unQuote($current_settings['$SMTP_USERNAME']);
	
	if(empty($smtp_username))
	  $do_auth = FALSE;
	else
	  $do_auth = TRUE;
	echo $do_auth;


	$params = Array(
			'host' => $this->unQuote($current_settings['$SMTP_HOST']),
			'auth' => $do_auth,
			'username' => $this->unQuote($current_settings['$SMTP_USERNAME']),
			'password' => $this->unQuote($current_settings['$SMTP_PASS']),
			);

	//	$this->dumpArray($params);

	$mail_factory = Mail::factory('smtp', $params);
	
	$headers['From'] = $this->unQuote($current_settings['$SMTP_SENDER_ADDRESS']);
	$headers['To'] = $test_recipient;
	$headers['Subject'] = 'Test message from HEM Setup';


	//	$this->dumpArray($headers);

	$status = $mail_factory->send($test_recipient, $headers, 'This is a Test');
	if(PEAR::isError($status))
	  $this->setSessionField('smtp_error', $status->getMessage());
	else
	  $this->addSessionMessage('TEST_MAIL_SENT');
      }
    else
      $this->addSessionMessage('NO_RECIPIENT_GIVEN');
  }


  /**
   * Based on bigdump.php from ???
   * TODO: Add Credits
   *
   *
   */
  function importMySQLDump(& $dbh)
  {
    global $MYSQL_DUMPFILE;
    
    define ("MAX_LINE_LENGTH",65536);
 
    if($file=fopen($MYSQL_DUMPFILE,"rt"))
      {
	$query="";
	$queries=0;
	$linenumber = 0;
	$comment[0]="#";        
	$comment[1]="-- ";
	//    $totalqueries=$_REQUEST["totalqueries"];
	//    $linenumber=$_REQUEST["start"];
	$querylines=0;
	$totalqueries=0;
	$inparents=false;
	$error = false;
	//    while (($linenumber<$_REQUEST["start"]+$linespersession || $query!="") 
	//	   && ((!$gzipmode && $dumpline=fgets($file, MAX_LINE_LENGTH)) || ($gzipmode && $dumpline=gzgets($file, MAX_LINE_LENGTH))))
	while ( $dumpline=fgets($file, MAX_LINE_LENGTH))
	  { 
	    // Handle DOS and Mac encoded linebreaks (I don't know if it will work on Win32 or Mac Servers)
	    
	    $dumpline=ereg_replace("\r\n$", "\n", $dumpline);
	    $dumpline=ereg_replace("\r$", "\n", $dumpline);
	    
	    // DIAGNOSTIC
	    //	    echo ("<p>Line $linenumber: $dumpline</p>\n");
	    
	    // Skip comments and blank lines only if NOT in parents
	    
	    if (!$inparents)
	      { 
		$skipline=false;
		reset($comment);
		foreach ($comment as $comment_value)
		  { 
		    if (!$inparents && (trim($dumpline)=="" || strpos ($dumpline, $comment_value) === 0))
		      {
			$skipline=true;
			break;
		      }
		  }
		if ($skipline)
		  {
		    $linenumber++;
		    continue;
		  }
	      }
	    
	    // Remove double back-slashes from the dumpline prior to count the quotes ('\\' can only be within strings)
	    
	    $dumpline_deslashed = str_replace ("\\\\","",$dumpline);
	    
	    // Count ' and \' in the dumpline to avoid query break within a text field ending by ;
	    // Please don't use double quotes ('"')to surround strings, it wont work
	    
	    $parents=substr_count ($dumpline_deslashed, "'")-substr_count ($dumpline_deslashed, "\\'");
	    if ($parents % 2 != 0)
	      $inparents=!$inparents;
	    
	    // Add the line to query
	    
	    $query .= $dumpline;
	    
	    // Don't count the line if in parents (text fields may include unlimited linebreaks)
	    
	    if (!$inparents)
	      $querylines++;
	    
	    // Execute query if end of query detected (; as last character) AND NOT in parents
	    
	    if (ereg(";$",trim($dumpline)) && !$inparents)
	      {
		if(isset($_ENV['LANG']) && stristr($_ENV['LANG'], 'utf-8'))
		  {
		    // Server is configured utf-8
		    if(!$this->isUTF8($query))
		      $query = utf8_encode($query);
		  }
		else
		  {
		    // Server has some other charset, decode UTF-8
		    if($this->isUTF8($query))
		      $query = utf8_decode($query);
		  }

		if (!$dbh->query(trim($query)))
		  {
		    echo ("<p class=\"error\">Error at the line $linenumber: ". trim($dumpline)."</p>\n");
		    echo ("<p>Query: ".trim($query)."</p>\n");
		    echo ("<p>MySQL: ".mysql_error()."</p>\n");
		    $error=true;
		    break;
		  }
		$totalqueries++;
		$queries++;
		$query="";
		$querylines=0;
	      }
	    $linenumber++;
	  }
	if(!$error)
	  return TRUE;
	else
	  return FALSE;
      }
    else
      return FALSE;
  }

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

}
?>