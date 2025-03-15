<?
error_reporting(E_ALL);

$HEURISTIC_TABLE = 'heuristic';
$HEURISTICSET_TABLE = 'heuristic_set';
$TRANSLATION_TABLE = 'translation';

$DB_PREFIX = 'test_';

$HEURISTIC_TABLE = $DB_PREFIX . $HEURISTIC_TABLE;
$HEURISTICSET_TABLE = $DB_PREFIX . $HEURISTICSET_TABLE;
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
require_once 'class.HeuristicSet.php';


echo "Testing Heuristic Set Class";

$util = &new Util();
$dbi = new DBI($dsn);
if (!$dbi->isConnected()) {
	echo "DB not OK :-( <br/>" . $dbi->getError();
}


$test_data = array(
	'hSetId' => '999',
	'title_translation' => array(
			'trans_id' => '4721',
			'US' => 'Test Heuristic Set',
			'DE' => 'Test Heuristik Satz',
		),
	'description_translation' => array(
		'trans_id' => '4722',
		'US' => 'Description for Test Heuristic Set',
		'DE' => 'Beschreibung f&uuml;r Test Heuristic Satz',
	),
	'heuristics' => array(
		'99998' => array(
			'hId' => '99998',
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
			'hSetId' => '999'
		),
		'99999' => array(
			'hId' => '99999',
			'title_translation' => array(
					'trans_id' => '4713',
					'US' => 'Another Test Heuristic',
					'DE' => 'Eine andere Test Heuristik',
				),
			'description_translation' => array(
				'trans_id' => '4714',
				'US' => 'Another Description for Test Heuristic',
				'DE' => 'Eine andere Beschreibung f&uuml;r Test Heuristic',
			),
			'hOrder' => '2',
			'hSetId' => '999'
		),
	),
);

$util->dumpArray($test_data);

$heur_set =& new HeuristicSet($test_data['hSetId'], $dbi);

echo "-------------- <br/>";
echo "Retrieved Data after first store:<br/>";
$heur_set->storeHeuristicSet($test_data);

$util->dumpArray($heur_set->getHeuristicSet());


$test_data = array(
	'hSetId' => '999',
	'title_translation' => array(
			'trans_id' => '4721',
			'US' => 'Test Heuristic Set changed',
			'DE' => 'Test Heuristik Satz ge&auml;ndert',
		),
	'description_translation' => array(
		'trans_id' => '4722',
		'US' => 'Description for Test Heuristic Set',
		'DE' => 'Beschreibung f&uuml;r Test Heuristic Satz ge&auml;ndert',
	),
	'heuristics' => array(
		'89997' => array(
			'hId' => '89997',
			'title_translation' => array(
					'trans_id' => '4717',
					'US' => 'An additional Test Heuristic',
					'DE' => 'Eine weitere Test Heuristik',
				),
			'description_translation' => array(
				'trans_id' => '4718',
				'US' => 'Description for additional Test Heuristic',
				'DE' => 'Beschreibung f&uuml;r eine weitere Test Heuristic',
			),
			'hOrder' => '3',
			'hSetId' => '999'
		),
		'89998' => array(
			'hId' => '89998',
			'title_translation' => array(
					'trans_id' => '4711',
					'US' => 'Test Heuristic',
					'DE' => 'Test Heuristik ge&auml;ndert',
				),
			'description_translation' => array(
				'trans_id' => '4712',
				'US' => 'Description for Test Heuristic',
				'DE' => 'Beschreibung f&uuml;r Test Heuristic ge&auml;ndert',
			),
			'hOrder' => '1',
			'hSetId' => '999'
		),
		'89999' => array(
			'hId' => '89999',
			'title_translation' => array(
					'trans_id' => '4713',
					'US' => 'Another Test Heuristic',
					'DE' => 'Eine andere Test Heuristik ge&auml;ndert',
				),
			'description_translation' => array(
				'trans_id' => '4714',
				'US' => 'Another Description for Test Heuristic',
				'DE' => 'Eine andere Beschreibung f&uuml;r Test Heuristic ge&auml;ndert',
			),
			'hOrder' => '2',
			'hSetId' => '999'
		),
	),
);


$heur_set->storeHeuristicSet($test_data);

echo "retrieved data after secon store():<br/>";
$util->dumpArray($heur_set->getHeuristicSet());

$util->dumpArray($heur_set->getHeuristicSetIds());


// echo $heur_set->getError();
// echo "Set deleted <br/>";

/*
$heur_set_new =& new HeuristicSet($test_data['hSetId'], $dbi);
$heur_set_new->removeHeuristicSet();

$heur_set_new->init();

if($heur_set_new->init_ok_)
  $util->dumpArray($heur_set_new->getHeuristicSet());
else
   echo "array empty";
*/
?>