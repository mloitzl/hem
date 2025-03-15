<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$MENU_BOX_TEMPLATE ='menu_box.html';

$APPLICATION_NAME = 'MENUBOX';
$APP_DIR = '/menu';

global $REL_APP_ROOT, $APP_ROOT;

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;


$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$ERROR_FILE = 'errors.MenuBox.php';
$MESSAGE_FILE = 'messages.LoginBox.php';
$LABEL_FILE = $APP_ROOT . $APP_DIR ."/". 'labels.MenuBox.php';


?>