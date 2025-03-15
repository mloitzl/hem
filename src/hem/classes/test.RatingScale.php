<?
error_reporting(E_ALL);

$RATINGSCALEVALUE_TABLE = 'rating_scale_value';
$RATINGSCALE_TABLE = 'rating_scale';
$TRANSLATION_TABLE = 'translation';

$DB_PREFIX = 'test_';

$RATINGSCALEVALUE_TABLE = $DB_PREFIX . $RATINGSCALEVALUE_TABLE;
$RATINGSCALE_TABLE = $DB_PREFIX . $RATINGSCALE_TABLE;
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
require_once 'class.RatingScale.php';

echo "Testing RatingScale Class";

$util = &new Util();
$dbi = new DBI($dsn);
if (!$dbi->isConnected()) {
	echo "DB not OK :-( <br/>" . $dbi->getError();
}


$test_data = array(
	'scaleId' => '4p1spew3dm5axucc6wmyrhdvz1rq8npw',
);

$scale =& new RatingScale($test_data['scaleId'], $dbi);

$util->dumpArray($scale->getRatingScale());

?>