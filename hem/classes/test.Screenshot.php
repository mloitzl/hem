<?php
require_once 'conf.Tests.php';
require_once 'class.Screenshot.php';

$IMAGE_DB_DIR = $APP_ROOT. "/image_db";

$THUMBNAIL_MAX_WIDTH = '300';
$GENERATE_THUMBNAILS = TRUE;
$THUMBNAIL_PREFIX = 'tn_';

$IMAGE_TABLE = $DB_PREFIX . "screenshot";

if(isset($_POST['go']) && $_POST['go'] == '1')
  {
    $img_obj = & new Screenshot(0, $dbi, $util);
    
    $img_obj->addImage($_POST['data'], $_FILES['userfile']);

  }
else
  {
    echo "<form action='".$_SERVER['PHP_SELF']."' method='post' enctype='multipart/form-data'>";
    echo "File:<input type='file' name='userfile'  size='40' /><br/>";
    echo "sTimestamp:<input type='text' name='data[sTimestamp]' value='".date('YmdHms',time())."' /><br/>";
    echo "sId<input type='text' name='data[sId]' value='".$util->getUniqueId()."'><br>\n";
    echo "fId<input type='text' name='data[fId]' value=''><br>\n";
    echo "sKind<input type='text' name='data[sKind]' value='anotated'><br>\n";
    echo "<input type='hidden' name='go' value='1'>";
    echo "<input type='hidden' name='MAX_FILE_SIZE' value='10000000' />";
    echo "<input type='Submit'>";
    echo "<input type='Reset'>";
    echo "</form>";
  }


?>