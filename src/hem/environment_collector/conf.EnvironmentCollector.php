<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APPLICATION_NAME = 'EnvironmentCollector';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/environment_collector';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
//$TEMPLATE_DIR = $APP_ROOT . '/heur_mgr';
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$ADD_ENVIRONMENT_DATA_TEMPLATE = 'add_environment_data.html';
$PROJECT_OVERVIEW_TEMPLATE = 'projects.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.EnvironmentCollector.php';

$ERROR_FILE = 'errors.EnvironmentCollector.php';
$MESSAGE_FILE = 'messages.EnvironmentCollector.php';
$LABEL_FILE = 'labels.EnvironmentCollector.php';

?>