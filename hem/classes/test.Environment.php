<?
error_reporting(E_ALL);

$ENVIRONMENT_TABLE = 'environment';
$ENVIRONMENT_ATTRIBUTES_TABLE = 'environment_attributes';
$TRANSLATION_TABLE = 'translation';

$DB_PREFIX = 'test_';

$ENVIRONMENT_TABLE = $DB_PREFIX . $ENVIRONMENT_TABLE;
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
require_once 'class.Environment.php';


echo "Testing Environment Class";

$util = & new Util();
$dbi = new DBI($dsn);
if(!$dbi->isConnected())
  {
    echo "DB not OK :-( <br/>" . $dbi->getError();
  }


$test_data = array(
		   'envId' => '00999',
		   'title_translation' => array(
					   'trans_id' => '004721',
					   'US' => 'Test Environment',
					   'DE' => 'Test Umgebung',
					   ),
		   'description_translation' => array(
						      'trans_id' => '004722',
						      'US' => 'Description for Test Environment',
						      'DE' => 'Beschreibung f&uuml;r Test Umgebung',
						      ),
		   'attributes' => array(
					 '0099998' => array(
							    'envAttributeId' => '0099998',
							    'title_translation' => array(
											 'trans_id' => '004711',
											 'US' => 'Test Attribute',
											 'DE' => 'Test Attribut',
											 ),
							    'envId' => '00999',
							    'envOrder' => '2',
							    'envAttributeType' => 'text',
							    'envAttributeValues' => 'NULL',
							    ),
					 '0099999' => array(
							    'envAttributeId' => '0099999',
							    'title_translation' => array(
											 'trans_id' => '004712',
											 'US' => 'Another Test Attribute',
											 'DE' => 'Ein anderes Test Attribut',
											 ),
							    'envId' => '00999',
							    'envOrder' => '1',
							    'envAttributeType' => 'select',
							    'envAttributeValues' => 'NULL',
							),					 
					 ),
		   );

$util->dumpArray($test_data);

$env =& new Environment($test_data['envId'], $dbi);

echo "<hr/>";
echo "Storing:<br/>";

$env->storeEnvironment($test_data);

echo "<hr/>";
echo "Retrieved Data after first store:<br/>";
$util->dumpArray($env->getEnvironment());

if($env->getEnvironment() !== $test_data)
  echo "<hr>storeEnvironent: ERROR<hr>";


$test_data = array(
		   'envId' => '00999',
		   'title_translation' => array(
						'trans_id' => '004721',
						'US' => 'Test Environment changed',
						'DE' => 'Test Umgebung ge&auml;ndert',
						),
		   'description_translation' => array(
						      'trans_id' => '004722',
						      'US' => 'Description for Test Environment changed',
						      'DE' => 'Beschreibung f&uuml;r Test Umgebung ge&auml;ndert',
						      ),
		   'attributes' => array(
					 '0099998' => array(
							    'envAttributeId' => '0099998',
							    'title_translation' => array(
											 'trans_id' => '004711',
											 'US' => 'Test Attribute changed',
											 'DE' => 'Test Attribut anders',
											 ),
							    'envId' => '00999',
							    'envOrder' => '2',
							    'envAttributeType' => 'text',
							    'envAttributeValues' => 'NULL',
							    ),
					 '0099999' => array(
							    'envAttributeId' => '0099999',
							    'title_translation' => array(
											 'trans_id' => '004712',
											 'US' => 'Another Test Attribute changed',
											 'DE' => 'Ein anderes Test Attribut anderscht',
											 ),
							    'envId' => '00999',
							    'envOrder' => '1',
							    'envAttributeType' => 'select',
							    'envAttributeValues' => 'NULL',
							    ),					 
					 '0099997' => array(
							    'envAttributeId' => '0099997',
							    'title_translation' => array(
											 'trans_id' => '004713',
											 'US' => 'Another Test Attribute 2',
											 'DE' => 'Ein anderes Test Attribut 2',
											 ),
							    'envId' => '00999',
							    'envOrder' => '3',
							    'envAttributeType' => 'text',
							    'envAttributeValues' => 'NULL',
							    ),					 
					 ),
		   );



$env->storeEnvironment($test_data);

echo "retrieved data after secon store():<br/>";
$util->dumpArray($env->getEnvironment());

$util->dumpArray($env->getEnvironmentIds());


$env_new =& new Environment($test_data['envId'], $dbi);
//$env_new->removeEnvironment();

$env_new->init();

if($env_new->init_ok_)
  $util->dumpArray($env_new->getEnvironment());
else
   echo "array empty";

?>