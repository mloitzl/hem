<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APP_TEMPLATE ='some.html';

$APPLICATION_NAME = 'EnvironmentManager';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/environment_mgr';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
//$TEMPLATE_DIR = $APP_ROOT . '/heur_mgr';
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$ADD_ENVIRONMENT_TEMPLATE = 'add_environment.html';
$ENVIRONMENT_OVERVIEW_TEMPLATE = 'environments.html';
$CONFIRMATION_TEMPLATE = 'confirm.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.EnvironmentManager.php';

$ERROR_FILE = 'errors.EnvironmentManager.php';
$MESSAGE_FILE = 'messages.EnvironmentManager.php';
$LABEL_FILE = 'labels.EnvironmentManager.php';

?>