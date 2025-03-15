<?php

$DEBUGGER_CLASS = 'classes/class.Debugger.php';
$DBI_CLASS = 'classes/class.DBI.php';
$DBOBJECT_CLASS = 'classes/class.DBObject.php';
$HANDLER_CLASS = 'classes/class.Handler.php';
$MESSAGE_HANDLER_CLASS = 'classes/class.MessageHandler.php';
$ERROR_HANDLER_CLASS = 'classes/class.ErrorHandler.php';
$LABEL_HANDLER_CLASS = 'classes/class.LabelHandler.php';
$FORM_VALIDATOR_CLASS = 'classes/class.FormValidator.php';
$AUTH_HANDLER_CLASS = 'classes/class.AuthHandler.php';
$ADMIN_AUTH_HANDLER_CLASS = 'classes/class.AdminAuthHandler.php';
$TEMPLATE_HANDLER_CLASS = 'classes/class.TemplateHandler.php';
$USER_CLASS = 'classes/class.User.php';
$THEME_CLASS = 'classes/class.Theme.php';
$ACTIVITYLOG_CLASS = 'classes/class.ActivityLog.php';

define(FALSE, 'FALSE');
define(TRUE, 'TRUE');



if(!defined("DEBUGGER_LOADED") && !empty($DEBUGGER_CLASS))
  {
    include_once $DEBUGGER_CLASS;
  }

if(!defined("DBI_LOADED") && !empty($DBI_CLASS))
  {
    include_once $DBI_CLASS;
  }

if(!defined("DBOBJECT_LOADED") && !empty($DBOBJECT_CLASS))
  {
    include_once $DBOBJECT_CLASS;
  }

if(!defined("HANDLER_LOADED") && !empty($HANDLER_CLASS))
  {
    include_once $HANDLER_CLASS;
  }

if(!defined("MESSAGE_HANDLER_LOADED") && !empty($MESSAGE_HANDLER_CLASS))
  {
    include_once $MESSAGE_HANDLER_CLASS;
  }

if(!defined("ERROR_HANDLER_LOADED") && !empty($ERROR_HANDLER_CLASS))
  {
    include_once $ERROR_HANDLER_CLASS;
  }

if(!defined("LABEL_HANDLER_LOADED") && !empty($LABEL_HANDLER_CLASS))
  {
    include_once $LABEL_HANDLER_CLASS;
  }

if(!defined("FORM_VALIDATOR_LOADED") && !empty($FORM_VALIDATOR_CLASS))
  {
    include_once $FORM_VALIDATOR_CLASS;
  }

if(!defined("AUTH_HANDLER_LOADED") && !empty($AUTH_HANDLER_CLASS))
  {
    include_once $AUTH_HANDLER_CLASS;
  }

if(!defined("ADMIN_AUTH_HANDLER_LOADED") && !empty($ADMIN_AUTH_HANDLER_CLASS))
  {
    include_once $ADMIN_AUTH_HANDLER_CLASS;
  }

if(!defined("TEMPLATE_HANDLER_LOADED") && !empty($TEMPLATE_HANDLER_CLASS))
  {
    include_once $TEMPLATE_HANDLER_CLASS;
  }

if(!defined("USER_CLASS_LOADED") && !empty($USER_CLASS))
  {
    include_once $USER_CLASS;
  }

if(!defined("THEME_HANDLER_LOADED") && !empty($THEME_CLASS))
  {
    include_once $THEME_CLASS;
  }

if(!defined("ACTIVITYLOG_LOADED") && !empty($ACTIVITYLOG_CLASS))
  {
    include_once $ACTIVITYLOG_CLASS;
  }

?>