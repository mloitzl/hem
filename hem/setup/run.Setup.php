<?php
  // die("The setup is deactivated. Go to \"setup/run.Setup.php\" and activate the second line");
require_once "conf.Setup.php";

$thisApp = new Setup(
			array(
			      'app_name' => $APPLICATION_NAME,
			      'app_version'=>'1.0.0',
			      'app_type'=>'WEB',
			      'app_auth_dsn' => $AUTH_DB_URL,
			      'app_db_url'=>$APP_DB_URL,
			      'app_authentication' => FALSE,
			      'app_auto_authenticate' => FALSE,
			      'app_admin_auth' => FALSE,
			      'app_exit_point' => $_SERVER['SCRIPT_NAME'],
			      'app_session_name' => $SESSION_NAME,
			      'app_auto_connect' => FALSE,
			      'app_debugger' => $DEBUGGER,
			      'debug_color' => 'blue',
			      'app_themes' => TRUE,
			      'app_check_browser_language' => TRUE,
			      'app_label_file' => $LABEL_FILE,
			      'app_error_file' => $ERROR_FILE,
			      'app_message_file' => $MESSAGE_FILE,
			      'render_boxes' => FALSE, 
			      )
			);

$thisApp->bufferDebugging();
$thisApp->debug("This is $thisApp->app_name_ application.");
$thisApp->run();
$thisApp->dumpDebugInfo();

?>