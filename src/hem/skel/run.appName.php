<?php
require_once "conf.appName.php";

$count = 0;
$thisApp = new appName(
			array(
			      'app_name' => $APPLICATION_NAME,
			      'app_version'=>'1.0.0',
			      'app_type'=>'WEB',
			      'app_auth_dsn' => $AUTH_DB_URL,
			      'app_db_url'=>$APP_DB_URL,
			      'app_authentication' => TRUE,
			      'app_auto_authenticate' => TRUE,
			      'app_admin_auth' => TRUE,
			      'app_exit_point' => $_SERVER['SCRIPT_NAME'],
			      'app_session_name' => $SESSION_NAME,
			      'app_auto_connect'=>TRUE,
			      'app_debugger' => $DEBUGGER,
			      'app_themes' => TRUE,
			      'app_check_browser_language' => TRUE,
			      'app_label_file' => $LABEL_FILE,
			      'app_error_file' => $ERROR_FILE,
			      'app_message_file' => $MESSAGE_FILE,
			      )
			);

$thisApp->bufferDebugging();
$thisApp->run();
$thisApp->dumpDebugInfo();
?>