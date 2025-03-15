<?php
error_reporting(E_ALL);

$PROJECT_TABLE = 'project';
$PROJECT_USER_TABLE = 'project_user';
$USER_ATTR_TBL = 'user_attributes';
$ENVIRONMENT_DATA_TABLE = 'environment_data';
$ENVIRONMENT_TABLE = 'environment';
$ENVIRONMENT_ATTRIBUTES_TABLE = 'environment_attributes';
$TRANSLATION_TABLE = 'translation';
$RATINGSCHEME_TABLE = 'ratingscheme';
$RATINGSCHEME_SCALE_TABLE = 'ratingscheme_scale';

$DB_PREFIX = 'test_';

$PROJECT_TABLE = $DB_PREFIX . $PROJECT_TABLE;
$PROJECT_USER_TABLE = $DB_PREFIX . $PROJECT_USER_TABLE;
$USER_ATTR_TBL = $DB_PREFIX . $USER_ATTR_TBL;
$ENVIRONMENT_DATA_TABLE = $DB_PREFIX . $ENVIRONMENT_DATA_TABLE;
$ENVIRONMENT_TABLE = $DB_PREFIX . $ENVIRONMENT_TABLE;
$ENVIRONMENT_ATTRIBUTES_TABLE = $DB_PREFIX . $ENVIRONMENT_ATTRIBUTES_TABLE;
$TRANSLATION_TABLE = $DB_PREFIX . $TRANSLATION_TABLE;
$RATINGSCHEME_TABLE = $DB_PREFIX . $RATINGSCHEME_TABLE;
$RATINGSCHEME_SCALE_TABLE = $DB_PREFIX . $RATINGSCHEME_SCALE_TABLE;
$RATINGSCALEVALUE_TABLE = $DB_PREFIX . "rating_scale_value";
$FINDING_TABLE = $DB_PREFIX . "finding";
$FINDING_ASSOCIATION_TABLE = $DB_PREFIX . "manager_evaluator_finding";
$RATING_TABLE = $DB_PREFIX . "finding_rate";

$dsn = 'mysql://test:test@localhost/test';

$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

$USER_DIR = '/martin';
$PROJECT_NAME = '/hem';

$APP_ROOT = $DOC_ROOT . $USER_DIR . $PROJECT_NAME;
$REL_APP_ROOT = $USER_DIR . $PROJECT_NAME;

$INCLUDE_DIR = $APP_ROOT . '/classes';
$FRAMEWORK_DIR = $APP_ROOT . '/framework';
$PEAR_DIR = $APP_ROOT . '/pear';
$FRAMEWORK_CLASSES_DIR = $FRAMEWORK_DIR . '/classes';

$PATH = $INCLUDE_DIR . ':' .
  $FRAMEWORK_DIR . ':' .
  $PEAR_DIR . ':' .
  $FRAMEWORK_CLASSES_DIR;

ini_set('include_path', ':' .
  $PATH . ':' .
  ini_get('include_path'));

$PHP_SELF = $_SERVER['PHP_SELF'];

$LANGUAGES = array(
  'US',
  'DE',
);

require_once 'DB.php';
require_once 'class.Util.php';
require_once 'class.DBI.php';
require_once 'class.DBObject.php';
require_once 'class.ReportHelper.php';


$util = &new Util();


$dbi = new DBI($dsn);
if ($dbi->isConnected() == FALSE) {
  echo "DB not OK :-( <br/>" . $dbi->getError();
}


//$project_id = '3ytio8q8itiwfank4i6riceea1yn2qly';
$project_id = '907o83fsr8i0mj202d0zrz1uvr127p7g';
$rep = new ReportHelper($project_id, $dbi);

if ($rep->init_ok_) {
  $util->dumpArray($rep->project_->data_array_);

  $util->dumpArray($rep->getUserFullNames());
  $util->dumpArray($rep->getUserInitials());

  $util->dumpArray($rep->getUsersEnvironment());
  $util->dumpArray($rep->getEnvironmentAttributes('DE'));

  $util->dumpArray($rep->getProjectTitle('DE'));

  $util->dumpArray($rep->getRatingScheme('DE'));

  $util->dumpArray($rep->getAggregatedFindingsOrdered());


  foreach ($rep->getUserFullNames() as $user_id => $value) {
    $util->dumpArray($rep->getFindingListForUser($user_id));
  }
}

?>