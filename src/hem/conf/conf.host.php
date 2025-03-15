<?php
// HEM MAIN Config File
//
//  IMPORTANT: Do not use whitespaces before Variable Names
//             HEM Setup will not work correctly in this case!

// The absolute Path to the Doc Root of the Web Server
// Note: Set manually if the global does not fit.
$DOC_ROOT = $_SERVER['DOCUMENT_ROOT']; // Written by HEM Setup March 15, 2025, 4:11 pm
// The relative path under Doc Root, where the hem directory is
$USER_DIR =  '/hem';  // Written by HEM Setup March 15, 2025, 4:11 pm
// Not used!
$PROJECT_NAME =  '';  // Written by HEM Setup March 15, 2025, 4:11 pm

// Should debugging be activated?
$DEBUGGER = FALSE;


// ## Database Connection ##

// Database Management System to use (mysql, or sqlite)
$USE_DATABASE = 'mysql'; // Written by HEM Setup March 15, 2025, 4:11 pm

// 1. MySQL
// Hostname
$AUTH_DB_HOST = 'mariadb'; // Written by HEM Setup March 15, 2025, 4:11 pm

// Database name
$AUTH_DB_NAME = 'hem'; // Written by HEM Setup March 15, 2025, 4:11 pm

// 2. SQLite
$SQLITE_DB_FILE = 'hem.sqlite?mode=0666';


// Tables prefix
// Note: Not used now, leave this untouched!
$DB_PREFIX = "test_";

// Not used
$APP_DB_HOST = '';
$APP_DB_NAME = '';

// Username for Database connection
$AUTH_DB_USER = ''; // Written by HEM Setup March 15, 2025, 4:11 pm
// password for database connection
$AUTH_DB_PASS = ''; // Written by HEM Setup March 15, 2025, 4:11 pm


// Not used
$APP_DB_USER = 'test';
$APP_DB_PASS = 'test';


// Directory where HEM stores its Images
// Two posibilites:
// o Leave this variable untouched and set Web Server as owner for the Directory 
//    {HEM_ROOT}/image_db
// o Change to some other path outside the Web Servers Doc Root and set 777,
//   this is insecure, cause other users can change contents of that directory 

$IMAGE_DB_DIR = $DOC_ROOT . $USER_DIR . "/image_db";
//$IMAGE_DB_DIR = "/some/asbolute/path/to/image_db";


// e-mail setup
// o hostname of smtp server
// o The sender address to use, when sending mails
// o (OPTIONAL) SMTP Authentication username
// o (OPTIONAL) SMTP Authentication passwoird

$SMTP_HOST = 'localhost'; // Written by HEM Setup March 15, 2025, 4:11 pm
$SMTP_SENDER_ADDRESS = 'noreply@some.domain'; // Written by HEM Setup March 15, 2025, 4:11 pm
$SMTP_USERNAME = ''; // Written by HEM Setup March 15, 2025, 4:11 pm
$SMTP_PASS = ''; // Written by HEM Setup March 15, 2025, 4:11 pm


?>