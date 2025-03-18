<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APP_TEMPLATE ='some.html';

$APPLICATION_NAME = 'RatingSchemeManager';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/ratingscheme_mgr';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
//$TEMPLATE_DIR = $APP_ROOT . '/heur_mgr';
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$ADD_RATINGSCHEME_TEMPLATE = 'add_ratingscheme.html';
$RATINGSCHEME_OVERVIEW_TEMPLATE = 'ratingschemes.html';
$CONFIRMATION_TEMPLATE = 'confirm.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.RatingSchemeManager.php';

$ERROR_FILE = 'errors.RatingSchemeManager.php';
$MESSAGE_FILE = 'messages.RatingSchemeManager.php';
$LABEL_FILE = 'labels.RatingSchemeManager.php';
?>