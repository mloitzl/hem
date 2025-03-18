<?php
error_reporting(E_ALL);

$dsn = 'mysql://test:test@localhost/testlu';

$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

$USER_DIR = '/martin';
$PROJECT_NAME = '/hem';

$APP_ROOT = $DOC_ROOT . $USER_DIR . $PROJECT_NAME;
$REL_APP_ROOT = $USER_DIR . $PROJECT_NAME;

$INCLUDE_DIR = $APP_ROOT . '/classes';
$FRAMEWORK_CLASSES_DIR = $APP_ROOT . '/framework/classes';

$PATH = $INCLUDE_DIR . ":" . $FRAMEWORK_CLASSES_DIR;

$DB_PREFIX = "test_";

ini_set( 'include_path' , ':' . 
	 $PATH . ':' .
	 ini_get( 'include_path' ));

$PHP_SELF = $_SERVER['PHP_SELF'];

require_once 'DB.php';
require_once 'class.DBI.php';
require_once 'class.DBObject.php';
require_once 'class.Util.php';
require_once 'class.Screenshot.php';

$util = new Util();

$dbi = new DBI($dsn);
if($dbi->isConnected() == FALSE)
  {
    echo "DB not OK :-( <br/>" . $dbi->getError();
  }

?>