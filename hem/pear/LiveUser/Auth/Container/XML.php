<?php
// LiveUser: A framework for authentication and authorization in PHP applications
// Copyright (C) 2002-2003 Markus Wolff
//
// This library is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.
//
// This library is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public
// License along with this library; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * XML driver for authentication
 *
 * @package  LiveUser
 * @category authentication
 */

/**
 * Require parent class definition and XML::Tree class.
 */
require_once 'LiveUser/Auth/Common.php';
require_once 'XML/Tree.php';

/**
 * Class LiveUser_Auth_Container_XML
 *
 * Description:
 * This is a XML backend driver for the LiveUser class.
 *
 * @author  Bj�rn Kraus <krausbn@php.net>
 * @version $Id: XML.php,v 1.19 2004/06/17 19:07:17 arnaud Exp $
 * @package LiveUser
 * @category authentication
 */
class LiveUser_Auth_Container_XML extends LiveUser_Auth_Common
{
    /**
     * XML file in which the auth data is stored.
     * @var string
     * @access private
     */
    var $file = '';

    /**
     * XML::Tree object.
     * @var object
     * @access private
     */
    var $tree = null;

    /**
     * XML::Tree object of the user logged in.
     * @var object
     * @access private
     * @see readUserData()
     */
    var $userObj = null;

    /**
     * Class constructor.
     *
     * @see    LiveUser::factory()
     * @access protected
     * @param  array     configuration array
     * @return void
     */
    function LiveUser_Auth_Container_XML(&$connectOptions)
    {
      if (is_array($connectOptions)) {
            $this->LiveUser_Auth_Common($connectOptions);
            if (!is_file($this->file)) {
                if (is_file(getenv('DOCUMENT_ROOT') . $this->file)) {
                    $this->file = getenv('DOCUMENT_ROOT') . $this->file;
                } else {
                    $this->_error = LiveUser::raiseError(LIVEUSER_ERROR_MISSING_DEPS, null, null,
                        "Auth initialisation failed. Can't find xml file.");
                }
            }
            if ($this->file) {
                if (class_exists('XML_Tree')) {
                    $tree =& new XML_Tree($this->file);
                    $err =& $tree->getTreeFromFile();
                    if (PEAR::isError($err)) {
                        return $err;
                    } else {
                        $this->tree = $tree;
                        $this->init_ok = true;
                    }
                } else {
                    $this->_error = LiveUser::raiseError(LIVEUSER_ERROR_MISSING_DEPS, null, null,
                        "Auth initialisation failed. Can't find XML_Tree class.");
                }
            } else {
                    $this->_error = LiveUser::raiseError(LIVEUSER_ERROR_MISSING_DEPS, null, null,
                        "Auth initialisation failed. Can't find xml file.");
            }
        }
    }

    /**
     * properly disconnect from resources
     *
     * @access  public
     */
    function disconnect()
    {
        $this->tree = null;
        $this->userObj = null;
    }

    /**
     * LiveUser_Auth_Container_XML::_updateUserData()
     *
     * Writes current values for user back to the database.
     * This method does nothing in the base class and is supposed to
     * be overridden in subclasses according to the supported backend.
     *
     * @access private
     */
    function _updateUserData()
    {
        if (!$this->init_ok || !$this->userObj) {
            return false;
        }

        $data = array('lastLogin' => $this->currentLogin);

        $index = 0;
        foreach ($this->userObj->children as $value) {
            if (in_array($value->name, array_keys($data))) {
                $el =& $this->userObj->getElement(array($index));
                $el->setContent($data[$value->name]);
            }
            $index++;
        }

        $success = false;
        do {
          $fp = fopen($this->file, 'wb');
          if (!$fp) {
              $errorMsg = "Auth freeze failure. Failed to open the xml file.";
              break;
          }
          if (!flock($fp, LOCK_EX)) {
              $errorMsg = "Auth freeze failure. Couldn't get an exclusive lock on the file.";
              break;
          }
          if (!fwrite($fp, $this->tree->get())) {
              $errorMsg = "Auth freeze failure. Write error when writing back the file.";
              break;
          }
          @fflush($fp);
          $success = true;
        } while (false);

        @flock($fp, LOCK_UN);
        @fclose($fp);

        if (!$success) {
            return LiveUser::raiseError(LIVEUSER_ERROR, null, null, $errorMsg);
        }

        return $success;
    }

    /**
     * LiveUser_Auth_Container_XML::readUserData()
     *
     * Reads auth_user_id, password from the xml file
     * If only $handle is given, it will read the data
     * from the first user with that handle and return
     * true on success.
     * If $handle and $passwd are given, it will try to
     * find the first user with both handle and passwd
     * matching and return true on success (this allows
     * multiple users having the same handle but different
     * passwords - yep, some people want this).
     * If no match is found, false is being returned.
     *
     * @param string    $userHandle      Handle of the current user.
     * @param mixed     $userPasswd  Can be a string with an
     *                               unencrypted pwd or false.
     * @return boolean
     */
    function _readUserData($userHandle, $userPasswd = false)
    {
        if (!$this->init_ok) {
            return false;
        }
        $success = false;
        $index = 0;

        foreach ($this->tree->root->children as $user) {
            $userId = '';
            $handle = '';
            $password = '';
            $lastLogin = 0;
            $isActive = '';

            foreach ($user->children as $value) {
                if (isset(${$value->name})) {
                    ${$value->name} = $value->content;
                }
            }

            if ($userHandle == $handle) {
                if ($userPasswd !== false) {
                    if ($this->encryptPW($userPasswd) == $password) {
                        $success = true;
                        break;
                    }
                } else {
                    $success = true;
                    break;
                }
            }

            $index++;
        }

        // If a user was found, read data into class variables and save
        // the tree object for faster access in the other functions.
        if ($success) {
            $this->handle       = $handle;
            $this->passwd       = $this->decryptPW($password);
            $this->isActive     = ($isActive == 'Y' ? true : false);
            $this->authUserId   = $userId;
            $this->lastLogin    = (!empty($lastLogin) ? $lastLogin : 0);
            $this->userObj      =& $this->tree->root->getElement(array($index));
        }

        return $success;
    }

    /**
     * LiveUser_Auth_Container_MDB::userExists()
     *
     * Helper function that checks if there is a user in
     * the database who's matching the given parameters.
     * If $checkHandle is given and $checkPW is set to
     * false, it only checks if a user with that handle
     * exists. If only $checkPW is given and $checkHandle
     * is set to false, it will check if there exists a
     * user with that password. If both values are set to
     * anything but false, it will find the first user in
     * the database with both values matching.
     * Please note:
     * - If no match was found, the return value is false
     * - If a match was found, the auth_user_id from the database
     *   is being returned
     * Whatever is returned, please keep in mind that this
     * function only searches for the _first_ occurence
     * of the search values in the database. So when you
     * have multiple users with the same handle, only the
     * ID of the first one is returned. Same goes for
     * passwords. Searching for both password and handle
     * should be pretty safe, though - having more than
     * one user with the same handle/password combination
     * in the database would be pretty stupid anyway.
     *
     * @param boolean $checkHandle  The handle (username) to search
     * @param boolean $checkPW      The password to check against
     * @return mixed
     */
    function userExists($checkHandle=false,$checkPW=false)
    {
        if (!$this->init_ok) {
            return false;
        }
        foreach ($this->tree->root->children as $user) {
            $handle = '';
            $password = '';

            foreach ($user->children as $value) {
                if (isset(${$value->name})) {
                    ${$value->name} = $value->content;
                }
            }

            if ($checkHandle !== false && $checkPW === false) {
                // only search for the first user with the given handle
                if ($checkHandle == $handle) {
                    return true;
                }
            } elseif ($checkHandle === false && $checkPW !== false) {
                // only search for the first user with the given password
                if ($this->encryptPW($checkPW) == $password) {
                    return true;
                }
            } else {
                // check for a user with both handle and password matching
                if ($checkHandle == $handle) {
                    if ($this->encryptPW($checkPW) == $password) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
?>