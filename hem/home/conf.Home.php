<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APPLICATION_NAME = 'Home';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/home';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;

$HOME_SCREEN_TEMPLATE = 'home.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.Home.php';

$ERROR_FILE = 'errors.Home.php';
$MESSAGE_FILE = 'messages.Home.php';
$LABEL_FILE = 'labels.Home.php';
?>