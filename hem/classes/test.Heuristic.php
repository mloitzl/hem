<?
error_reporting(E_ALL);

$HEURISTIC_TABLE = 'heuristic';
$TRANSLATION_TABLE = 'translation';

$DB_PREFIX = 'test_';

$HEURISTIC_TABLE = $DB_PREFIX . $HEURISTIC_TABLE;
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
require_once 'class.Heuristic.php';


echo "Testing Heuristic Class";

$util = &new Util();
$dbi = new DBI($dsn);
if (!$dbi->isConnected()) {
	echo "DB not OK :-( <br/>" . $dbi->getError();
}


$test_data = array(
	'hId' => '999',
	'title_translation' => array(
			'trans_id' => '4711',
			'US' => 'Test Heuristic',
			'DE' => 'Test Heuristik',
		),
	'description_translation' => array(
		'trans_id' => '4712',
		'US' => 'Description for Test Heuristic',
		'DE' => 'Beschreibung f&uuml;r Test Heuristic',
	),
	'hOrder' => '1',
	'hSetId' => '9'
);

$util->dumpArray($test_data);

$heur = new Heuristic($test_data['hId'], $dbi);

$heur->storeHeuristic($test_data);

$util->dumpArray($heur->getHeuristic());

$test_data = array(
	'hId' => '999',
	'title_translation' => array(
			'trans_id' => '4711',
			'US' => 'Test Heuristic changed',
			'DE' => 'Test Heuristik ge&auml;ndert',
		),
	'description_translation' => array(
		'trans_id' => '4712',
		'US' => 'Description for Test Heuristic changed',
		'DE' => 'Beschreibung f&uuml;r Test Heuristic ge&auml;ndert',
	),
	'hOrder' => '1',
	'hSetId' => '9'
);

$heur->storeHeuristic($test_data);

$util->dumpArray($heur->getHeuristic());

$heur->removeHeuristic();
?>