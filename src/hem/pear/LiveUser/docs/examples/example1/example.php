<?php
/**
 * Test for the LiveUser class
 * ===============================
 *
 * This example sets up an authorization system using the LiveUser
 * class. You don't have to use this to use the LiveUser class(es), but
 * this way you don't need to take care of the login-process, storing
 * the user object in a session and more...
 *
 * This example is intended to be used with the auth XML driver.
 * No permission management is supported.
 *
 * @author Bj�rn Kraus <krausbn@php.net>
 * @version $Id: example.php,v 1.8 2004/04/27 00:25:23 mscifo Exp $
 **/
error_reporting(E_ALL);

// Get LiveUser configuration array
require_once 'conf.php';

if ($xml_is_readable == false || $xml_is_writable == false) {
    die('<p style="color: red; text-align: center;">The XML file isn\'t readable/writable. Add the right permissions to it and then try again.</p>');
}

// The error handling stuff is not needed and used only for debugging
// while LiveUser is not yet mature
PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, 'eHandler');

function eHandler($errObj)
{
    echo('<hr /><span style="color: red">' . $errObj->getMessage() . ':<br />'. $errObj->getUserinfo() . '</span><hr />');
}

// Create new LiveUser (LiveUser) object.
// We�ll only use the auth container, permissions are not used.
$LU =& LiveUser::factory($liveuserConfig);

$username = (isset($_REQUEST['handle'])) ? $_REQUEST['handle'] : NULL;
$password = (isset($_REQUEST['passwd'])) ? $_REQUEST['passwd'] : NULL;
$logout = (isset($_REQUEST['logout'])) ? $_REQUEST['logout'] : FALSE;
$LU->init($username, $password, $logout);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>Example 1</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <style type="text/css">
    <!--
    body {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000000;
        background-color: #FFFFFF
    }

    table {
        border: 1px solid #000;
        border-top: 0px;
        border-right: 0px;
        border-spacing: 0px;
        border-collapse: collapse;
    }

    table td {
        width: 100px;
        border-top: 1px solid #000;
        border-right: 1px solid #000;
        padding: 5px;
    }

        .center {
           text-align: center;
    }
    .center table {
           margin: auto;
    }
    -->
    </style>
</head>

<body>
<?php
// Check if the user has logged in successfully
if (!$LU->isLoggedIn()) {
    if (!$username) {
?>
    <form name="loginform" method="post" action="example.php">
    <div class="center">
        <table width="300" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td colspan="2"><b>Example login</b></td>
            </tr>
            <tr>
                <td>Handle:</td>
                <td>
                    <input type="text" name="handle" maxlength="80" value="" />
                </td>
            </tr>
             <tr>
                <td>Password:</td>
                <td>
                    <input type="password" name="passwd" maxlength="80" value="" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="center">
                        <input type="submit" value="Login" />
                    </div>
                </td>
            </tr>
        </table>
    </div>
    </form>
<?php
    // The user couldn't login, so let's check if the reason was that
    // he's not yet been declared "valid" by an administrator.
    } else if ($LU->isInactive()) {
?>
        <h3>Sorry kid, but one of our admins has yet approved
       your user status. Please be patient. Don't call us -
       we'll call you.</h3>
      <p align="center"><a href="example.php?logout=1">Logout</a></p>
<?php
    } else {
?>
      <h3>Sorry, we can't let you in. Check if the spelling of
      your handle and password is correct.</h3>
      <p align="center"><a href="example.php?logout=1">Logout</a></p>
<?php
    }
?>
        <p>&nbsp;</p>
        <p><i>Login Data for this Example:</i></p>
        <table>
            <tr>
                <td style="text-align: center; font-weight: bold;">Handle</th>
                <td style="text-align: center; font-weight: bold;">Password</th>
            </tr>
            <tr>
            <td>father</td>
                <td>father</td>
            </tr>
            <tr>
            <td>mother</td>
                <td>mother</td>
            </tr>
            <tr>
                <td>child</td>
                <td>child</td>
    </tr>
    </table>
<?php
} else {
?>
    <h2 align="center">User logged in: <?php echo $LU->getProperty('handle'); ?></h2>
    <h3>Congrats, you're in</h3>
    <p align="center"><a href="example.php?logout=1">Logout</a></p>
<?php
}
// Just some more debug output with no further relevance
echo '<hr />Handle:';
print_r($LU->getProperty('handle'));
echo '<br />User Type:';
print_r($LU->getProperty('userType'));

echo '<hr /><pre>';
print_r($LU);
echo '<hr />';
print_r($_SESSION);
echo '<hr />';
print_r($_REQUEST);
echo '</pre>';
?>
</body>
</html>