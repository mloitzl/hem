<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APPLICATION_NAME = 'RatingCollector';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/rating_collector';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;


$PROJECT_OVERVIEW_TEMPLATE = 'projects.html';
$FINDING_OVERVIEW_TEMPLATE = 'findings.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.RatingCollector.php';

$ERROR_FILE = 'errors.RatingCollector.php';
$MESSAGE_FILE = 'messages.RatingCollector.php';
$LABEL_FILE = 'labels.RatingCollector.php';
?>