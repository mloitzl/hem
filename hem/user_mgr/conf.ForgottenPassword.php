<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APPLICATION_NAME = 'ForgottenPassword';
$APP_DIR = '/user_mgr';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

// TODO: simplify!
//$APP_DSN = $AUTH_DB_URL;

$USERNAME_TEMPLATE = 'user_name.html';
$RESULT_TEMPLATE = 'result.html';


require_once 'class.PHPApplication.php';
require_once 'class.ForgottenPassword.php';

$ERROR_FILE = 'errors.ForgottenPassword.php';
$MESSAGE_FILE = 'messages.ForgottenPassword.php';
$LABEL_FILE = 'labels.ForgottenPassword.php';



?>