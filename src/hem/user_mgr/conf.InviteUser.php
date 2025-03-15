<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$INVITE_USER_TEMPLATE ='invite_user.html';

$APPLICATION_NAME = 'Invite User';
$APP_DIR = '/user_mgr';
$POP_UP_APP = TRUE;


$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

// TODO: simplify!
//$APP_DSN = $AUTH_DB_URL;

require_once 'class.PHPApplication.php';
require_once 'class.InviteUser.php';

$ERROR_FILE = 'errors.InviteUser.php';
$MESSAGE_FILE = 'messages.InviteUser.php';
$LABEL_FILE = 'labels.InviteUser.php';

?>