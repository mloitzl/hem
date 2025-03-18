<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APP_TEMPLATE ='some.html';

$APPLICATION_NAME = 'RatingScaleManager';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/ratingscale_mgr';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
//$TEMPLATE_DIR = $APP_ROOT . '/heur_mgr';
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$ADD_RATINGSCALE_TEMPLATE = 'add_ratingscale.html';
$RATINGSCALE_OVERVIEW_TEMPLATE = 'ratingscales.html';
$CONFIRMATION_TEMPLATE = 'confirm.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.RatingScaleManager.php';

$ERROR_FILE = 'errors.RatingScaleManager.php';
$MESSAGE_FILE = 'messages.RatingScaleManager.php';
$LABEL_FILE = 'labels.RatingScaleManager.php';

?>