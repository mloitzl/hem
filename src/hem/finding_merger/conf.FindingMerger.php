<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APPLICATION_NAME = 'FindingMerger';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/finding_merger';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;

$PROJECT_OVERVIEW_TEMPLATE = 'projects.html';
$MERGE_TEMPLATE = 'merge.html';
$CONFIRMATION_TEMPLATE = 'confirm.html';
$CHOOSE_SCREENSHOT_TEMPLATE = 'screenshot.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.FindingMerger.php';

$ERROR_FILE = 'errors.FindingMerger.php';
$MESSAGE_FILE = 'messages.FindingMerger.php';
$LABEL_FILE = 'labels.FindingMerger.php';
?>