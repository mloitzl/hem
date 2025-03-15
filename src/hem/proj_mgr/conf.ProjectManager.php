<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APP_TEMPLATE ='some.html';

$APPLICATION_NAME = 'ProjectManager';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/proj_mgr';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
//$TEMPLATE_DIR = $APP_ROOT . '/heur_mgr';
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$ADD_PROJECT_TEMPLATE = 'add_project.html';
$PROJECT_OVERVIEW_TEMPLATE = 'projects.html';
$PROJECT_IMPORT_FORM_TEMPLATE = 'project_import_form.html';
$DELETE_PROJECT_TEMPLATE = 'delete_project.html';
$PROJECT_IMPORT_RESULTS_TEMPLATE = 'project_import_results.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.ProjectManager.php';

$ERROR_FILE = 'errors.ProjectManager.php';
$MESSAGE_FILE = 'messages.ProjectManager.php';
$LABEL_FILE = 'labels.ProjectManager.php';

?>