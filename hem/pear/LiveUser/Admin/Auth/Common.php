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
 * Base class for authentication backends.
 *
 * @author   Lukas Smith <smith@backendmedia.com>
 * @version  $Id: Common.php,v 1.22 2004/06/15 13:28:09 lsmith Exp $
 * @package  LiveUser
 * @category authentication
 */
class LiveUser_Admin_Auth_Common
{
    /**
     * Indicates if backend module initialized correctly. If yes,
     * true, if not false. Backend module won't initialize if the
     * init value (usually an object or resource handle that
     * identifies the backend to be used) is not of the required
     * type.
     *
     * @access public
     * @var    boolean
     */
    var $init_ok = false;

    /**
     * Set posible encryption modes.
     *
     * @access private
     * @var    array
     */
    var $encryptionModes = array('MD5'   => 'MD5',
                                 'RC4'   => 'RC4',
                                 'PLAIN' => 'PLAIN',
                                 'SHA1'  => 'SHA1');

    /**
     * Defines the algorithm used for encrypting/decrypting
     * passwords. Default: "MD5".
     *
     * @access private
     * @var    string
     */
    var $passwordEncryptionMode = 'MD5';


    /**
     * The name associated with this auth container. The name is used
     * when adding users from this container to the reference table
     * in the permission container. This way it is possible to see
     * from which auth container the user data is coming from.
     *
     * @var    string
     * @access public
     */
    var $name = null;

    /**
     * Class constructor. Feel free to override in backend subclasses.
     *
     * @access protected
     */
    function LiveUser_Admin_Auth_Common(&$connectOptions, $name = null)
    {
        if (is_array($connectOptions)) {
            foreach ($connectOptions as $key => $value) {
                if (isset($this->$key)) {
                    $this->$key = $value;
                }
            }
        }
        if (!is_null($name)) {
            $this->name = $name;
            $this->authTable = $this->name['authTable'];
        }
    }

    /**
     * Decrypts a password so that it can be compared with the user
     * input. Uses the algorithm defined in the passwordEncryptionMode
     * property.
     *
     * @access public
     * @param  string password as an encrypted string
     * @return string The decrypted password
     */
    function decryptPW($encryptedPW)
    {
        $decryptedPW = 'Encryption type not supported.';

        switch (strtoupper($this->passwordEncryptionMode)) {
            case 'PLAIN':
                $decryptedPW = $encryptedPW;
                break;
            case 'MD5':
                // MD5 can't be decoded, so return the string unmodified
                $decryptedPW = $encryptedPW;
                break;
            case 'RC4':
                if (!is_object($this->rc4)) {
                    @include_once 'Crypt/Rc4.php';
                    if (!class_exists('Crypt_RC4')) {
                        return LiveUser::raiseError(LIVEUSER_ERROR_NOT_SUPPORTED, null, null,
                            'Please install Crypt_RC4 to use this feature');
                    }
                    $this->rc4 =& new Crypt_RC4('LiveUserMagicKey');
                }
                $decryptedPW = $encryptedPW;
                $this->rc4->decrypt($decryptedPW);
                break;
            case 'SHA1':
                // SHA1 can't be decoded, so return the string unmodified
                $decryptedPW = $encryptedPW;
                break;
        }

        return $decryptedPW;
    }

    /**
     * Encrypts a password for storage in a backend container.
     * Uses the algorithm defined in the passwordEncryptionMode
     * property.
     *
     * @access public
     * @param string  password as plain text
     * @return string The encrypted password
     */
    function encryptPW($plainPW)
    {
        $encryptedPW = 'Encryption type not supported.';

        switch (strtoupper($this->passwordEncryptionMode)) {
            case 'PLAIN':
                $encryptedPW = $plainPW;
                break;
            case 'MD5':
                $encryptedPW = md5($plainPW);
                break;
            case 'RC4':
                if (!is_object($this->rc4)) {
                    @include_once 'Crypt/Rc4.php';
                    if (!class_exists('Crypt_RC4')) {
                        return LiveUser::raiseError(LIVEUSER_ERROR_NOT_SUPPORTED, null, null,
                            'Please install Crypt_RC4 to use this feature');
                    }
                    $this->rc4 =& new Crypt_RC4('LiveUserMagicKey');
                }
                $encryptedPW = $plainPW;
                $this->rc4->crypt($encryptedPW);
                break;
            case 'SHA1':
                if (!function_exists('sha1')) {
                    return LiveUser::raiseError(LIVEUSER_ERROR_NOT_SUPPORTED, null, null,
                        'SHA1 function doesn\'t exist. Upgrade your PHP version.');
                }
                $encryptedPW = sha1($plainPW);
                break;
        }

        return $encryptedPW;
    }

    /**
     * Function returns the inquired value if it exists in the class.
     *
     * @access public
     * @param  string   Name of the property to be returned.
     * @return mixed    null, a value or an array.
     */
    function getProperty($what)
    {
        $that = null;
        if (isset($this->$what)) {
            $that = $this->$what;
        }
        return $that;
    }

    /**
     * Adds a new user to Auth.
     *
     * @access  public
     * @param   string   Handle (username).
     * @param   string   Password (optional).
     * @param   boolean  Sets the user active (1) or not (0) (optional).
     * @param   mixed    If specificed no new ID will be automatically generated instead
     * @param   integer ID of the owning user.
     * @param   integer ID of the owning group.
     * @param   mixed   If specificed no new ID will be automatically generated instead
     * @param   array   Array of custom fields to be added
     * @return  mixed    Users auth ID on success, PEAR error if not.
     */
    function addUser($handle, $password = '', $active = true, $owner_user_id = null,
                    $owner_group_id = null, $authId = null, $customFields = array())
    {
        return LiveUser::raiseError(LIVEUSER_ERROR_NOT_SUPPORTED, null, null,
            'addUser(): Method not supported by this container');
    }

    /**
     * Removes an existing user from Auth.
     *
     * @access  public
     * @param   string   Auth user ID of the user that should be removed.
     * @return  mixed    True on success, error object if not.
     */
    function removeUser($authId)
    {
        return LiveUser::raiseError(LIVEUSER_ERROR_NOT_SUPPORTED, null, null,
            'removeUser(): Method not supported by this container');
    }

    /**
     * Changes user data in auth table.
     *
     * @access  public
     * @param   string   Auth user ID.
     * @param   string   Handle (username) (optional).
     * @param   string   Password (optional).
     * @param   boolean  Sets the user active (1) or not (0) (optional).
     * @param   integer  ID of the owning user.
     * @param   integer  ID of the owning group.
     * @param   array   Array of custom fields to be updated.
     * @return  mixed    True on success, error object if not.
     */
    function updateUser($authId, $handle = '', $password = '', $active = null,
                $owner_user_id = null, $owner_group_id = null, $customFields = array())
    {
        return LiveUser::raiseError(LIVEUSER_ERROR_NOT_SUPPORTED, null, null,
            'updateUser(): Method not supported by this container');
    }

    /**
     * Gets all users with handle, passwd, authId,
     * lastlogin, is_active and individual rights.
     *
     * The array will look like this:
     * <code>
     * $userData[0]['auth_user_id']       = 'wujha433gawefawfwfiuj2ou9823r98h';
     *             ['handle']       = 'myLogin';
     *             ['passwd']     = 'd346gs2gwaeiuhaeiuuweijfjuwaefhj';
     *             ['lastlogin']    = 1254801292; (Unix timestamp)
     *             ['is_active']     = 1; (1 = yes, 0 = no)
     * </code>
     *
     * @access  public
     * @param   array  filters to apply to fetched data
     * @param   array  custom fields you wane to be returned
     * @return  mixed  Array with user data or error object.
     */
    function getUsers($filters = array(), $customFields = array())
    {
        return LiveUser::raiseError(LIVEUSER_ERROR_NOT_SUPPORTED, null, null,
            'getUsers(): Method not supported by this container');
    }
}
