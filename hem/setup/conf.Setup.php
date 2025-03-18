<?php

$NUMBER_OF_DB_TABLES = 43;

// Variables that are not sufficiently defined yet
$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
$USER_DIR = dirname(dirname($_SERVER['SCRIPT_NAME']));
$PROJECT_NAME = '';

$APP_ROOT = $DOC_ROOT . $USER_DIR . $PROJECT_NAME;
$REL_APP_ROOT = $USER_DIR . $PROJECT_NAME;
$REPORTS_DIR = $DOC_ROOT . $USER_DIR . $PROJECT_NAME . '/reports';


$PEAR_DIR = $APP_ROOT . '/pear';
$APP_FRAMEWORK_DIR = $APP_ROOT . '/framework';
$APP_CLASSES_DIR = $APP_ROOT . '/classes';

$HOME_APP = $REL_APP_ROOT . '/home/run.Home.php';

$PATH = $PEAR_DIR . PATH_SEPARATOR .
  $APP_FRAMEWORK_DIR . PATH_SEPARATOR .
  $APP_CLASSES_DIR;

$AUTH_DB_URL = $APP_DB_URL = '';
$SESSION_NAME = 'HEM_SETUP';
$DEBUGGER = FALSE;

$DEFAULT_CSS = $REL_APP_ROOT . '/templates/default.css';
$MASTER_TEMPLATE = "index.html";
$MASTER_TEMPLATE_DIR = $APP_ROOT . "/templates";
$LOGO_URL = $REL_APP_ROOT . '/templates/img/logo40.gif';

ini_set('include_path', PATH_SEPARATOR .
  $PATH . PATH_SEPARATOR .
  ini_get('include_path'));


//require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$FIRST_SCREEN_TEMPLATE = 'first.html';
$PATH_SCREEN_TEMPLATE = 'paths.html';
$FILE_SCREEN_TEMPLATE = 'files.html';
$SMTP_SCREEN_TEMPLATE = 'smtp.html';
$DB_SCREEN_TEMPLATE = 'db.html';
$DB_SETUP_SCREEN_TEMPLATE = 'db_setup.html';
$FINAL_SCREEN_TEMPLATE = 'final.html';

$APPLICATION_NAME = 'Setup';
$DEFAULT_LANGUAGE = 'DE';

//$TEMPLATE_DIR = $APP_ROOT . '/setup';
$TEMPLATE_DIR = dirname($_SERVER['PATH_TRANSLATED']);
//$REL_TEMPLATE_DIR = $REL_MASTER_TEMPLATE_DIR;

$ON = TRUE;
$OFF = FALSE;

$MYSQL_DUMPFILE = 'mysql_create.sql';

require_once 'class.PHPApplication.php';
require_once 'class.Setup.php';

$ERROR_FILE = 'errors.Setup.php';
$MESSAGE_FILE = 'messages.Setup.php';
$LABEL_FILE = 'labels.Setup.php';

?>