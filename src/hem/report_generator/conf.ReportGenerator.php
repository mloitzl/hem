<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APP_TEMPLATE ='some.html';

$APPLICATION_NAME = 'ReportPreview';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/report_generator';
$EXPORT_TEMPLATE = 'report_template.html';


$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
//$TEMPLATE_DIR = $APP_ROOT . '/heur_mgr';
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$PREVIEW_REPORT_TEMPLATE = 'preview_report.html';
$REPORT_OVERVIEW_TEMPLATE = 'reports.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.ReportGenerator.php';

$ERROR_FILE = 'errors.ReportGenerator.php';
$MESSAGE_FILE = 'messages.ReportGenerator.php';
$LABEL_FILE = 'labels.ReportGenerator.php';

$REPORT_LABEL_FILE = 'labels.Report.php';

?>