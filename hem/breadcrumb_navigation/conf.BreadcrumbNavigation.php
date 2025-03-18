<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$BREADCRUMB_TEMPLATE ='breadcrumb_navigation.html';

$APPLICATION_NAME = 'BREADCRUMBNAVIGATION';
$APP_DIR = '/breadcrumb_navigation';

global $REL_APP_ROOT, $APP_ROOT;

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;


$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$LABEL_FILE = $APP_ROOT . $APP_DIR ."/". 'labels.BreadcrumbNavigation.php';
?>