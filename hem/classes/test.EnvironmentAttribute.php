<?
error_reporting(E_ALL);

$ENVIRONMENT_ATTRIBUTES_TABLE = 'environment_attributes';
$TRANSLATION_TABLE = 'translation';

$DB_PREFIX = 'test_';

$ENVIRONMENT_ATTRIBUTES_TABLE = $DB_PREFIX . $ENVIRONMENT_ATTRIBUTES_TABLE;
$TRANSLATION_TABLE = $DB_PREFIX . $TRANSLATION_TABLE;

$dsn = 'mysql://test:test@localhost/testlu';

$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

$USER_DIR = '/martin';
$PROJECT_NAME = '/hem';

$APP_ROOT = $DOC_ROOT . $USER_DIR . $PROJECT_NAME;
$REL_APP_ROOT = $USER_DIR . $PROJECT_NAME;

$INCLUDE_DIR = $APP_ROOT . '/classes';
$FRAMEWORK_DIR = $APP_ROOT . '/framework';
$FRAMEWORK_CLASSES_DIR = $FRAMEWORK_DIR . '/classes';

$PATH = $INCLUDE_DIR . ':' .
  $FRAMEWORK_DIR . ':' .
  $FRAMEWORK_CLASSES_DIR;

ini_set( 'include_path' , ':' . 
	 $PATH . ':' .
	 ini_get( 'include_path' ));

$PHP_SELF = $_SERVER['PHP_SELF'];

$LANGUAGES = array(
		   'US',
		   'DE',
		   );

require_once 'DB.php';
require_once 'class.Util.php';
require_once 'class.DBI.php';
require_once 'class.DBObject.php';
require_once 'class.EnvironmentAttribute.php';


echo "Testing EnvironmentAttribute Class";

$util = & new Util();
$dbi = new DBI($dsn);
if(!$dbi->isConnected())
  {
    echo "DB not OK :-( <br/>" . $dbi->getError();
  }


$test_data = array(
		   'envAttributeId' => '999',
		   'title_translation' => array(
					   'trans_id' => '004711',
					   'US' => 'Test Attribute',
					   'DE' => 'Test Attribute',
					   ),
		   'envId' => '11',
		   'envOrder' => '2',
		   'envAttributeType' => 'text',
		   'envAttributeValues' => 'NULL',
		   );

$util->dumpArray($test_data);

$attr = new EnvironmentAttribute($test_data['envAttributeId'], $dbi);

$attr->storeAttribute($test_data);

$util->dumpArray($attr->getAttribute());

$test_data = array(
		   'envAttributeId' => '999',
		   'title_translation' => array(
					   'trans_id' => '004711',
					   'US' => 'Test Attribute changed',
					   'DE' => 'Test Attribute changed',
					   ),
		   'envId' => '12',
		   'envOrder' => '1',
		   'envAttributeType' => 'select',
		   'envAttributeValues' => 'a, b, c, d',
		   );


$attr->storeAttribute($test_data);

$util->dumpArray($attr->getAttribute());

$attr->removeAttribute();
?>