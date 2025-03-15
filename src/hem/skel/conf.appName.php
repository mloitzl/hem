<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APP_TEMPLATE ='some.html';

$APPLICATION_NAME = 'appName';
$DEFAULT_LANGUAGE = 'US';

$TEMPLATE_DIR = $APP_ROOT . '/login';
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$ON = TRUE;
$OFF = FALSE;


require_once 'class.PHPApplication.php';
require_once 'class.appName.php';

$ERROR_FILE = 'errors.appName.php';
$MESSAGE_FILE = 'messages.appName.php';
$LABEL_FILE = 'labels.appName.php';

?>