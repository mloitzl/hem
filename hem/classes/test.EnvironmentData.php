<?
error_reporting(E_ALL);

$ENVIRONMENT_DATA_TABLE = 'environment_data';
$TRANSLATION_TABLE = 'translation';

$DB_PREFIX = 'test_';

$ENVIRONMENT_DATA_TABLE = $DB_PREFIX . $ENVIRONMENT_DATA_TABLE;
$TRANSLATION_TABLE = $DB_PREFIX . $TRANSLATION_TABLE;

$dsn = 'mysql://test:test@localhost/test';

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
require_once 'class.EnvironmentData.php';


echo "Testing EnvironmentData Class";

$util = &new Util();
$dbi = new DBI($dsn);
if (!$dbi->isConnected()) {
	echo "DB not OK :-( <br/>" . $dbi->getError();
}

$user_id = '1';
$project_id = '10';

$test_data = array(
	"1" => array(
		"envDataId" => "101",
		"pId" => "10",
		"envAttributeId" => "1",
		"envAttributeData" => "Netscape 4.7",
		"envDataOwnerId" => "1",
	),
	"2" => array(
		"envDataId" => "102",
		"pId" => "10",
		"envAttributeId" => "2",
		"envAttributeData" => "Windows 98",
		"envDataOwnerId" => "1",
	),
	"3" => array(
		"envDataId" => "103",
		"pId" => "10",
		"envAttributeId" => "3",
		"envAttributeData" => "xDSL@stundent",
		"envDataOwnerId" => "1",
	),
	"4" => array(
		"envDataId" => "104",
		"pId" => "10",
		"envAttributeId" => "4",
		"envAttributeData" => "800x400",
		"envDataOwnerId" => "1",
	),
	"5" => array(
		"envDataId" => "105",
		"pId" => "10",
		"envAttributeId" => "5",
		"envAttributeData" => "changed",
		"envDataOwnerId" => "1",
	),
);

$data_obj = new EnvironmentData(0, $dbi);

$data_obj->storeAttributeDataArray($test_data);

$util->dumpArray($data_obj->getAttributeDataForUserAndProject($user_id, $project_id));

/*
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

*/
?>