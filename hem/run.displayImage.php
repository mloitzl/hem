<?
require_once('conf/conf.global.php');
require_once('classes/class.DBI.php');


$util =& new Util();

$dbi = new DBI($APP_DB_URL);

/*$tra =& new Transaction(0, $dbi); 

$tra_items = $tra->getTransactionsOfInterval($DOWNLOAD_TIME);

if($dbi->isConnected() == FALSE)
  {
    echo "DB not OK :-( <br/>" . $dbi->getError();
    }*/

if(isset($_GET['bid']))
  $im_id = $_GET['bid'];
else
  $im_id = 0;

if( isset($_GET['tn']) )
  $tn_flag = $_GET['tn'];
 else
   $tn_flag = 0;

$wm_flag = FALSE;

/*for($i=0; $i < sizeof($tra_items); $i++)
{
  if(in_array($im_id,$tra_items[$i]))
    $wm_flag = FALSE;
    }*/

//$util->dumpArray($tra_items);

$img = & new Screenshot($im_id, $dbi, $util, $tn_flag, $wm_flag);

$img->displayImage();


?>