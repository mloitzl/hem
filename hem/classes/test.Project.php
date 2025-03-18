<?php
error_reporting(E_ALL);

$PROJECT_TABLE = 'project';
$PROJECT_USER_TABLE = 'project_user';


$DB_PREFIX = 'test_';

$PROJECT_TABLE = $DB_PREFIX . $PROJECT_TABLE;
$PROJECT_USER_TABLE = $DB_PREFIX . $PROJECT_USER_TABLE;
$TRANSLATION_TABLE = $DB_PREFIX . "translation";
$FINDING_TABLE = $DB_PREFIX . "finding";
$FINDING_ASSOCIATION_TABLE = $DB_PREFIX . "manager_evaluator_finding";
$RATING_TABLE = $DB_PREFIX . "finding_rate";
$ENVIRONMENT_DATA_TABLE = $DB_PREFIX . "environment_data";
$USER_ATTR_TBL = $DB_PREFIX . "user_attributes";

$dsn = 'mysql://test:test@localhost/test';

$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

$USER_DIR = '/martin';
$PROJECT_NAME = '/hem';

$APP_ROOT = $DOC_ROOT . $USER_DIR . $PROJECT_NAME;
$REL_APP_ROOT = $USER_DIR . $PROJECT_NAME;

$INCLUDE_DIR = $APP_ROOT . '/classes';
$PEAR_DIR = $APP_ROOT . '/pear';
$FRAMEWORK_DIR = $APP_ROOT . '/framework';
$FRAMEWORK_CLASSES_DIR = $FRAMEWORK_DIR . '/classes';

$PATH = $INCLUDE_DIR . ':' .
  $PEAR_DIR . ':' .
  $FRAMEWORK_DIR . ':' .
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
require_once 'class.DBI.php';
require_once 'class.DBObject.php';
require_once 'class.Project.php';


$dbi = new DBI($dsn);
if ($dbi->isConnected() == FALSE) {
  echo "DB not OK :-( <br/>" . $dbi->getError();
}



$test_data_1 = array(
  'pId' => 'ecxy3b6ilw0xa3n9d4oha40ba0cple4y',
);


$proj_new = new Project($test_data_1['pId'], $dbi);


if ($proj_new->hasError()) {
  echo $proj_new->getError();
  $proj_new->resetError();
} else {
  echo "<pre>";
  print_r($proj_new->getProjectData());
  echo "</pre>";
  echo "<pre>";
  print_r($proj_new->exportProject());
  echo "</pre>";
}


/*
$proj_new->setProjectPhase('1');
if($proj_new->hasError())
  {
    echo $proj_new->getError();
    $proj_new->resetError();
  }
else
  {
    echo $proj_new->getProjectPhase()."<br/>";
  }


$proj_new->setHeuristicSet('abcde');
if($proj_new->hasError())
  {
    echo $proj_new->getError();
    $proj_new->resetError();
  }
else
  {
    echo $proj_new->getHeuristicSet()."<br/>";
  }

$proj_new->addUserToProject('1');
$proj_new->addUserToProject('2');
$user_ids = $proj_new->getAllUserIdsFromProject();
if($proj_new->hasError())
  {
    echo $proj_new->getError();
    $proj_new->resetError();
  }
else
  {
    echo "<pre>";
    print_r($user_ids);
    echo "</pre>";
  }
$proj_new->removeUserFromProject('1');
$proj_new->removeUserFromProject('2');


$proj_new->addUserToProject('uomwlzcrlh7sbmrd5r385obd4ic6vkyu');

if($proj_new->isUserInProject('uomwlzcrlh7sbmrd5r385obd4ic6vkyu'))
  echo "uomwlzcrlh7sbmrd5r385obd4ic6vkyu is in Project<br/>";
else
  echo "uomwlzcrlh7sbmrd5r385obd4ic6vkyu is not in Project<br/>";

if($proj_new->isUserInProject('fake'))
  echo "fake is in Project<br/>";
else
  echo "fake is not in Project<br/>";


$proj_new->removeUserFromProject('uomwlzcrlh7sbmrd5r385obd4ic6vkyu');


$proj_new->deleteData($test_data_2['pId']);

if($proj_new->hasError())
  {
    echo $proj_new->getError();
    $proj_new->resetError();
  }
else
  {
    echo "<pre>";
    print_r($proj_new->getData());
    echo "</pre>";
  }

*/


?>