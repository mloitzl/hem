<?php
require_once("conf/conf.global.php");

$directory_main_app = $REL_APP_ROOT . "/" . "home/run.Home.php";

//if(file_exists($directory_main_app))
  header("Location: $directory_main_app");
//else
//  header("Location: $HOME_APP");
?>