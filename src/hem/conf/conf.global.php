<?php

require_once('conf.host.php');
require_once('constants.app.php');
require_once('constants.groups.php');
require_once('constants.rights.php');
require_once('constants.project.php');
require_once('constants.activitylog.php');

error_reporting(E_ALL);

// TODO: Recheck project wide settings
// --- begin proj wide ---

$HEM_VERSION = "0.4.1";

$APP_ROOT = $DOC_ROOT . $USER_DIR . $PROJECT_NAME;
$REL_APP_ROOT = $USER_DIR . $PROJECT_NAME;
$REL_REPORTS_DIR = $REL_APP_ROOT . '/reports';
$REPORTS_DIR = $DOC_ROOT . $USER_DIR . $PROJECT_NAME . '/reports';


$PEAR_DIR = $APP_ROOT . '/pear';
$APP_FRAMEWORK_DIR = $APP_ROOT . '/framework';
$APP_CLASSES_DIR = $APP_ROOT . '/classes';

$HOME_APP = $REL_APP_ROOT . '/home/run.Home.php';
$HOME_APP_LABEL['DE'] = 'Start';
$HOME_APP_LABEL['US'] = 'Home';

$PATH = $PEAR_DIR . PATH_SEPARATOR .
	$APP_FRAMEWORK_DIR . PATH_SEPARATOR .
	$APP_CLASSES_DIR;

ini_set('include_path', PATH_SEPARATOR .
	$PATH . PATH_SEPARATOR .
	ini_get('include_path'));


$LANGUAGES = array(
	'US',
	'DE',
);

$DEFAULT_LANGUAGE = 'US';

$SESSION_NAME = 'HEM';

$AUTHENTICATION_URL = $REL_APP_ROOT . "/" . 'login/run.login.php';


if ($USE_DATABASE == 'mysql')
	$AUTH_DB_URL = "mysql://$AUTH_DB_USER:$AUTH_DB_PASS@$AUTH_DB_HOST/$AUTH_DB_NAME";
else if ($USE_DATABASE == 'sqlite')
	$AUTH_DB_URL = 'sqlite:///' . $APP_ROOT . '/' . $SQLITE_DB_FILE;


//$AUTH_DB_URL= 'sqlite:////tmp/test2.db?mode=0666';
//echo $AUTH_DB_URL."<br />";


//$APP_DB_URL = "mysql://$APP_DB_USER:$APP_DB_PASS@$APP_DB_HOST/$APP_DB_NAME";
$APP_DB_URL = $AUTH_DB_URL;

$USER_PREF_TBL = $DB_PREFIX . "user_pref";
$USER_ATTR_TBL = $DB_PREFIX . "user_attributes";
$TEMPLATE_PREF_ID = "1";
$LANGUAGE_PREF_ID = "2";

$HEURISTICSET_TABLE = $DB_PREFIX . 'heuristic_set';
$HEURISTIC_TABLE = $DB_PREFIX . 'heuristic';

$ENVIRONMENT_TABLE = $DB_PREFIX . 'environment';
$ENVIRONMENT_ATTRIBUTES_TABLE = $DB_PREFIX . 'environment_attributes';
$ENVIRONMENT_DATA_TABLE = $DB_PREFIX . 'environment_data';

$PROJECT_TABLE = $DB_PREFIX . 'project';
$PROJECT_USER_TABLE = $DB_PREFIX . 'project_user';

$ACTIVITY_TABLE = $DB_PREFIX . 'activity';

$THUMBNAIL_MAX_WIDTH = '150';
$ANNOTATED_SCREENSHOT_WIDTH = '600';
$GENERATE_THUMBNAILS = TRUE;
$THUMBNAIL_PREFIX = 'tn_';
$IMAGE_TABLE = $DB_PREFIX . "screenshot";
$FULLSIZE_IMAGE_POPUP = TRUE;

$FINDING_TABLE = $DB_PREFIX . "finding";
$FINDING_ASSOCIATION_TABLE = $DB_PREFIX . "manager_evaluator_finding";
$RATING_TABLE = $DB_PREFIX . "finding_rate";

$TRANSLATION_TABLE = $DB_PREFIX . "translation";

$RATINGSCALE_TABLE = $DB_PREFIX . "rating_scale";
$RATINGSCALEVALUE_TABLE = $DB_PREFIX . "rating_scale_value";
$RATINGSCHEME_TABLE = $DB_PREFIX . "ratingscheme";
$RATINGSCHEME_SCALE_TABLE = $DB_PREFIX . "ratingscheme_scale";

$LOGO_URL = $REL_APP_ROOT . '/templates/img/logo40.gif';
$MASTER_TEMPLATE = "index.html";
$LOGIN_BOX_TEMPLATE = "login.html";
$POP_UP_MASTER_TEMPLATE = "index_small.html";
$MASTER_TEMPLATE_DIR = $APP_ROOT . "/templates";
$REL_MASTER_TEMPLATE_DIR = $REL_APP_ROOT . "/templates";
$DEFAULT_CSS = $REL_APP_ROOT . '/templates/martin.css';

$FORGOTTEN_PASSWORD_APP = 'user_mgr/run.ForgottenPassword.php';

$TABLE_HEADING_COLOR = 'cccccc';
$TABLE_ROW_COLOR_1 = 'ffffaa';
$TABLE_ROW_COLOR_2 = 'ffffc0';

define('TRUE', 1);
define('FALSE', 0);
$ON = TRUE;
$OFF = FALSE;

$PHP_SELF = $_SERVER['PHP_SELF'];

$BOXES = array(
	'LOGIN_BOX' => array(
		'class_file' => 'login/class.LoginBox.php',
		'class_name' => 'LoginBox',
		'get_function' => 'getLoginBox'
	),
	'MENU_BOX' => array(
		'class_file' => 'menu/class.MenuBox.php',
		'class_name' => 'MenuBox',
		'get_function' => 'getMenuBox'
	),
	'BREADCRUMB_BOX' => array(
		'class_file' => 'breadcrumb_navigation/class.BreadcrumbNavigation.php',
		'class_name' => 'BreadcrumbNavigation',
		'get_function' => 'getBreadcrumbNavigation'
	),
);


require_once 'class.DBObject.php';
require_once 'class.Util.php';

require_once 'class.User.php';

//TODO: refactor these to DBObject!!
require_once 'class.Heuristic.php';
require_once 'class.HeuristicSet.php';

require_once 'class.Project.php';

require_once 'class.Environment.php';
require_once 'class.EnvironmentAttribute.php';
require_once 'class.EnvironmentData.php';

require_once 'class.Screenshot.php';

require_once 'class.Finding.php';

require_once 'class.Translation.php';

require_once 'class.RatingScheme.php';
require_once 'class.RatingScale.php';
require_once 'class.RatingScaleValue.php';

require_once 'class.ReportHelper.php';
?>