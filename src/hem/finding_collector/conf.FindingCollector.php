<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APPLICATION_NAME = 'FindingCollector';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/finding_collector';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;

$CHANGE_FINDING_TEMPLATE = 'change_finding.html';
$EVALUATION_TEMPLATE = 'evaluation.html';
$PROJECT_OVERVIEW_TEMPLATE = 'projects.html';
$FINDING_OVERVIEW_TEMPLATE = 'findings.html';
$CONFIRMATION_TEMPLATE = 'confirm.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.FindingCollector.php';

$ERROR_FILE = 'errors.FindingCollector.php';
$MESSAGE_FILE = 'messages.FindingCollector.php';
$LABEL_FILE = 'labels.FindingCollector.php';
?>