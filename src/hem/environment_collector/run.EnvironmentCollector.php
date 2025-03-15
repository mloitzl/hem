<?php

require_once "conf.EnvironmentCollector.php";

$thisApp =& new EnvironmentCollector(
				  array(
					'app_name'=>$APPLICATION_NAME,
					'app_version'=>'1.0.0',
					'app_type'=>'WEB',
					'app_db_url'=>$AUTH_DB_URL,
					'app_auth_dsn' => $AUTH_DB_URL,
					'app_exit_point' => '',
					'app_authentication' => TRUE,
					'app_auto_authenticate'=>TRUE,
					'app_auto_check_session'=>FALSE,
					'app_auto_connect'=>TRUE,
					'app_debugger'=> $DEBUGGER,
					'app_themes' => TRUE,
					'app_check_browser_language' => FALSE,
					'app_admin_auth' => TRUE,
					'app_session_name' => $SESSION_NAME,
					'app_label_file' => $LABEL_FILE,
					'app_error_file' => $ERROR_FILE,
					'app_message_file' => $MESSAGE_FILE,
					)
				  );

$thisApp->bufferDebugging();
$thisApp->run();
$thisApp->dumpDebugInfo();

?>