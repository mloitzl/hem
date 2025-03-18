<?php
  //require('');
error_reporting(E_ALL);


$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

$USER_DIR = '/martin';
$PROJECT_NAME = '/hem';

$APP_ROOT = $DOC_ROOT . $USER_DIR . $PROJECT_NAME;

$PEAR_DIR = $APP_ROOT . '/pear';
$APP_FRAMEWORK_DIR = $APP_ROOT . '/framework';
$APP_FRAMEWORK_CLASSES_DIR = $APP_FRAMEWORK_DIR . '/classes';


$PATH = $PEAR_DIR.":".
  $APP_FRAMEWORK_DIR.":".
  $APP_FRAMEWORK_CLASSES_DIR;

ini_set( 'include_path' , ':' . 
	 $PATH . ':' .
	 ini_get( 'include_path' ));

require_once('class.User.php');
require_once('DB.php');
require_once('class.DBI.php');
require_once('class.Util.php');
require_once($APP_ROOT.'/conf/constants.groups.php');

$dsn = 'mysql://test:test@localhost/testlu';

$DB_PREFIX = "test_";
$USER_PREF_TBL = $DB_PREFIX . "user_pref";
$USER_ATTR_TBL = $DB_PREFIX . "user_attributes";
$TEMPLATE_PREF_ID = "1";
$LANGUAGE_PREF_ID = "2";

$util = & new Util();

$dbi = new DBI($dsn);
if($dbi->isConnected() == FALSE)
  {
    echo "DB not OK :-( <br/>" . $dbi->getError();
  }





$user_id = '02ec099f2d602cc4968c5267970be132';

$user = new User($user_id, $dbi);

$util->dumpArray($user);

echo $user->getLanguageId();
echo "<br/>";
echo $user->getThemeId();
echo "<br/>";
echo $user->setThemeId('02ec099f2d602cc49c099f2d6nwa8a5f');
echo "<br/>";
echo $user->setLanguageId('US');
echo $user->setLanguageId('DE');
echo $user->setLanguageId('DE');





?>