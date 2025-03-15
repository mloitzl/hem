<?php
  //require('');
error_reporting(E_ALL);


$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

$USER_DIR = '/martin';
$PROJECT_NAME = '/hem';

$APP_ROOT = $DOC_ROOT . $USER_DIR . $PROJECT_NAME;

$PEAR_DIR = $APP_ROOT . '/pear';
$APP_FRAMEWORK_DIR = $APP_ROOT . '/framework';
$APP_FRAMEWORK_CLASSES_DIR = $APP_FRAMEWORK_DIR . '/classes';


$PATH = $PEAR_DIR.":".
  $APP_FRAMEWORK_DIR.":".
  $APP_FRAMEWORK_CLASSES_DIR;

ini_set( 'include_path' , ':' . 
	 $PATH . ':' .
	 ini_get( 'include_path' ));

require_once('class.AdminAuthHandler.php');
require_once('DB.php');
require_once($APP_ROOT.'/conf/constants.groups.php');
require_once('class.Util.php');

$util = & new Util();

$conf = array(
	      'auth_dsn' => 'mysql://test:test@localhost/testlu',
	      'auth_exit_page' =>  '',
	      'auth_session_name' => '' 
	      );

$aah = new AdminAuthHandler($conf);


print_r($aah->listGroups());

$user_id = '29214857b12575501c5c731353c7217e';
$group_id = '1';


$user_name = 'martin';
echo "User $user_name exists? ";
if($aah->userNameExists($user_name))
  echo "Yes<br/>";
else
  echo "No<br/>";

$user_name = 'martinasasdasd';
echo "User $user_name exists? ";
if($aah->userNameExists($user_name))
  echo "Yes<br/>";
else
  echo "No<br/>";

$user_id = '1234';
echo "UserId $user_id exists? ";
if($aah->userIdExists($user_id))
  echo "Yes<br/>";
else
  echo "No<br/>";

$user_id = '29214857b12575501c5c731353c7217e';
echo "UserId $user_id exists? ";
if($aah->userIdExists($user_id))
  echo "Yes<br/>";
else
  echo "No<br/>";

$user_id = "29214857b12575501c5c731353c7217e";
$user_name = $aah->getUserName($user_id);
echo "$user_id has user_name: $user_name <br/>";
$util->dumpArray($aah->getUserData($user_id));

$user_id = "1";
$user_name = $aah->getUserName($user_id);
echo "$user_id has user_name: $user_name <br/>";
$util->dumpArray($aah->getUserData($user_id));

//print_r($aah->updateGroupMembership($user_id, $group_id));




?>