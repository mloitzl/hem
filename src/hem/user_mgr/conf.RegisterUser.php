<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$REGISTER_USER_TEMPLATE = 'register_user.html';
$EXIT_PAGE_TEMPLATE = 'exit.html';
 
$APPLICATION_NAME = 'Register User';
$APP_DIR = '/user_mgr';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

// TODO: simplify!
//$APP_DSN = $AUTH_DB_URL;

require_once 'class.PHPApplication.php';
require_once 'class.RegisterUser.php';

$ERROR_FILE = 'errors.RegisterUser.php';
$MESSAGE_FILE = 'messages.RegisterUser.php';
$LABEL_FILE = 'labels.RegisterUser.php';



?>