<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$LOGIN_BOX_TEMPLATE ='login_box.html';

$APPLICATION_NAME = 'LOGINBOX';
$APP_DIR = '/login';

global $REL_APP_ROOT, $APP_ROOT;

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$LOGIN_APPLICATION = $REL_APP_ROOT ."/". "login/run.login.php";

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$ERROR_FILE = 'errors.LoginBox.php';
$MESSAGE_FILE = 'messages.LoginBox.php';
$LABEL_FILE = $APP_ROOT . $APP_DIR ."/". 'labels.LoginBox.php';


?>