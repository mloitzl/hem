<?
error_reporting(E_ALL);

$RATINGSCALEVALUE_TABLE = 'rating_scale_value';
$TRANSLATION_TABLE = 'translation';

$DB_PREFIX = 'test_';

$RATINGSCALEVALUE_TABLE = $DB_PREFIX . $RATINGSCALEVALUE_TABLE;
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
require_once 'class.RatingScaleValue.php';


echo "Testing RatingScaleValue Class";

$util = &new Util();
$dbi = new DBI($dsn);
if (!$dbi->isConnected()) {
	echo "DB not OK :-( <br/>" . $dbi->getError();
}


$test_data = array(
	'scaleValueId' => '2sbs1q69e15gvfvkuiddvdgfzxx5ra00',
	'title_translation' => array(
			'trans_id' => '123_4711',
			'US' => 'Test Value',
			'DE' => 'Test Wert',
		),
	'scaleValue' => '10',
	'scaleId' => '321',
);

$util->dumpArray($test_data);

$value = new RatingScaleValue($test_data['scaleValueId'], $dbi);

$util->dumpArray($value->getRatingScaleValue());

/*
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
*/
?>