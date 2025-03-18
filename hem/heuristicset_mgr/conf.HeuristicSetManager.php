<?php
require_once('../conf/conf.global.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

$APP_TEMPLATE ='some.html';

$APPLICATION_NAME = 'HeuristicSetManager';
$DEFAULT_LANGUAGE = 'US';

$APP_DIR = '/heuristicset_mgr';

$REL_APP_PATH = $REL_APP_ROOT . $APP_DIR;

$TEMPLATE_DIR = $APP_ROOT . $APP_DIR;
$REL_TEMPLATE_DIR = $REL_APP_ROOT . $APP_DIR;
//$TEMPLATE_DIR = $APP_ROOT . '/heur_mgr';
// TODO: check --> $REL_TEMPLATE_DIR = $USER_DIR . $PROJECT_NAME . '/login';

$ADD_HEURISTICSET_TEMPLATE = 'add_heuristicset.html';
$HEURISTICSET_OVERVIEW_TEMPLATE = 'heuristicsets.html';
$CONFIRMATION_TEMPLATE = 'confirm.html';
$HEURISTICHELP_TEMPLATE = 'heuristics.html';

$ON = TRUE;
$OFF = FALSE;

require_once 'class.PHPApplication.php';
require_once 'class.HeuristicSetManager.php';

$ERROR_FILE = 'errors.HeuristicSetManager.php';
$MESSAGE_FILE = 'messages.HeuristicSetManager.php';
$LABEL_FILE = 'labels.HeuristicSetManager.php';

?>