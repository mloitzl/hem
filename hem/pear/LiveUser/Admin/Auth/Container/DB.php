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
 * DB admin container for maintaining Auth/DB
 *
 * @package  LiveUser
 * @category authentication
 */

/**
 * Require parent class definition and PEAR::DB class.
 */
require_once 'LiveUser/Admin/Auth/Common.php';
require_once 'DB.php';

/**
 * Simple DB-based complexity driver for LiveUser.
 *
 * Description:
 * This admin class provides the following functionalities
 * - adding users
 * - removing users
 * - update user data (auth related: username, pwd, active)
 * - adding rights
 * - removing rights
 * - get all users
 *
 * ATTENTION:
 * This class is only experimental. API may change. Use it at your own risk.
 *
 * @author  Bjoern Kraus <krausbn@php.net>
 * @version $Id: DB.php,v 1.62 2004/07/31 10:10:55 lsmith Exp $
 * @package LiveUser
 * @category authentication
 */
class LiveUser_Admin_Auth_Container_DB extends LiveUser_Admin_Auth_Common
{
    /**
     * The DSN that was used to connect to the database (set only if no
     * existing connection object has been reused).
     *
     * @access private
     * @var    string
     */
    var $dsn = null;

    /**
     * PEAR::DB connection object.
     *
     * @access private
     * @var    object
     */
    var $dbc = null;

    /**
     * Auth table
     * Table where the auth data is stored.
     *
     * @access public
     * @var    string
     */
    var $authTable = 'liveuser_users';

    /**
     * Columns of the auth table.
     * Associative array with the names of the auth table columns.
     * The 'auth_user_id', 'handle' and 'passwd' fields have to be set.
     * 'lastlogin', 'is_active', 'owner_user_id' and 'owner_group_id' are optional.
     * It doesn't make sense to set only one of the time columns without the
     * other.
     *
     * @access public
     * @var    array
     */
    var $authTableCols = array(
        'required' => array(
            'auth_user_id' => array('name' => 'auth_user_id', 'type' => ''),
            'handle'       => array('name' => 'handle',       'type' => ''),
            'passwd'       => array('name' => 'passwd',       'type' => ''),
        ),
        'optional' => array(
            'lastlogin'    => array('name' => 'lastlogin',    'type' => ''),
            'is_active'    => array('name' => 'is_active',    'type' => '')
        )
    );

    /**
     * Constructor
     *
     * The second parameters expects an array containing the parameters
     * for the given container.
     *
     * This class expects only the array containing
     * configuration options of the auth container you wish
     * to administrate. This is done in case you have several
     * DB based auth containers.
     *
     * See PEAR::DB documentation for DSN specifications.
     *
     * @see    LiveUser::factory The configuration array is explained there
     * @access protected
     * @param  array  full liveuser conf array
     * @return void
     */
    function LiveUser_Admin_Auth_Container_DB(&$connectOptions, $name = null)
    {
        parent::LiveUser_Admin_Auth_Common($connectOptions, $name);
        if (is_array($connectOptions)) {
            if (isset($connectOptions['connection'])  &&
                    DB::isConnection($connectOptions['connection'])
            ) {
                $this->dbc     = &$connectOptions['connection'];
                $this->init_ok = true;
            } elseif (isset($connectOptions['dsn'])) {
                $this->dsn = $connectOptions['dsn'];
                $options = null;
                if (isset($connectOptions['options'])) {
                    $options = $connectOptions['options'];
                }
                $options['portability'] = DB_PORTABILITY_ALL;
                $this->dbc =& DB::connect($connectOptions['dsn'], $options);
                if (!DB::isError($this->dbc)) {
                    $this->init_ok = true;
                }
            }
        }
    } // end func LiveUser_Admin_Auth_DB

    /**
     * Adds a new user to Auth/DB.
     *
     * @access  public
     * @param   string  Handle (username).
     * @param   string  Password.
     * @param   array   Array of optional fields values to be added array('alias' => ''value')
     * @param   array   Array of custom fields values to be added array('alias' => ''value')
     * @param   mixed   If specificed no new ID will be automatically generated instead
     * @return  mixed   Users auth ID on success, DB error if not, false if not initialized
     */
    function addUser($handle, $password = '',
        $optionalFields = array(), $customFields = array(), $authId = null)
    {
        if (!$this->init_ok) {
            return false;
        }

        // Generate new user ID
        if (is_null($authId)) {
            $authId = $this->dbc->nextId($this->authTable, true);
        }

        // is_active, owner_user_id and owner_group_id are optional
        $col = $val = array();

        if (isset($this->authTableCols['optional']) && sizeof($optionalFields) > 0) {
            foreach ($optionalFields as $alias => $value) {
                $col[] = $this->authTableCols['optional'][$alias]['name'];
                if ($alias == 'is_active') {
                    $value = ($value ? 'Y' : 'N');
                }
                $val[] = $this->dbc->quoteSmart($value);
            }
        }

        if (isset($this->authTableCols['custom']) && sizeof($customFields) > 0) {
            foreach ($customFields as $alias => $value) {
                $col[] = $this->authTableCols['custom'][$alias]['name'];
                $val[] = $this->dbc->quoteSmart($value);
            }
        }

        if (is_array($col) && count($col) > 0) {
            $col = ',' . implode(',', $col);
            $val = ',' . implode(',', $val);
        }

        // Register new user in auth table
        $query = '
            INSERT INTO
                ' . $this->authTable . '
                (
                ' . $this->authTableCols['required']['auth_user_id']['name'] . ',
                ' . $this->authTableCols['required']['handle']['name']  . ',
                ' . $this->authTableCols['required']['passwd']['name']  . '
                ' . $col . '
                )
            VALUES
                (
                ' . $this->dbc->quoteSmart($authId) . ',
                ' . $this->dbc->quoteSmart($handle) . ',
                ' . $this->dbc->quoteSmart($this->encryptPW($password)) . '
                ' . $val . '
                )';

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        return $authId;
    } // end func addUser

    /**
     * Removes an existing user from Auth/DB.
     *
     * @access  public
     * @param   string   Auth user ID of the user that should be removed.
     * @return  mixed    True on success, DB error if not.
     */
    function removeUser($authId)
    {
        if (!$this->init_ok) {
            return false;
        }

        // Delete user from auth table (DB/Auth)
        $query = '
            DELETE FROM
                ' . $this->authTable . '
            WHERE
                '.$this->authTableCols['required']['auth_user_id']['name'].'='.$this->dbc->quoteSmart($authId);

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        return true;
    } // end func removeUser

    /**
     * Changes user data in auth table.
     *
     * @access  public
     * @param   string   Auth user ID.
     * @param   string   Handle (username) (optional).
     * @param   string   Password (optional).
     * @param   array   Array of optional fields values to be added array('alias' => ''value')
     * @param   array    Array of custom fields values to be updated
     * @return  mixed    True on success, DB error if not.
     */
    function updateUser($authId, $handle = '', $password = '',
        $optionalFields = array(), $customFields = array())
    {
        if (!$this->init_ok) {
            return false;
        }

        $updateValues = array();
        // Create query.
        $query = '
            UPDATE
                ' . $this->authTable . '
            SET ';

        if (!empty($handle)) {
            $updateValues[] =
                $this->authTableCols['required']['handle']['name'] . ' = ' . $this->dbc->quoteSmart($handle);
        }

        if (!empty($password)) {
            $updateValues[] =
                $this->authTableCols['required']['passwd']['name'] . ' = '
                    . $this->dbc->quoteSmart($this->encryptPW($password));
        }

        if (isset($this->authTableCols['optional']) && sizeof($optionalFields) > 0) {
            foreach ($optionalFields as $alias => $value) {
                if ($alias == 'is_active') {
                    $value = ($value ? 'Y' : 'N');
                }
                $updateValues[] = $this->authTableCols['optional'][$alias]['name'] . '=' .
                    $this->dbc->quoteSmart($value);
            }
        }

        if (isset($this->authTableCols['custom']) && sizeof($customFields) > 0) {
            foreach ($customFields as $alias => $value) {
                $updateValues[] = $this->authTableCols['custom'][$alias]['name'] . '=' .
                    $this->dbc->quoteSmart($value);
            }
        }

        if (count($updateValues)) {
            $query .= implode(', ', $updateValues);
        } else {
            return false;
        }

        $query .= ' WHERE
            ' . $this->authTableCols['required']['auth_user_id']['name'] . ' = ' . $this->dbc->quoteSmart($authId);

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        return true;
    }

    /**
     * Gets all users with handle, passwd, auth_user_id
     * lastlogin, is_active and individual rights.
     *
     * The array will look like this:
     * <code>
     * $userData[0]['auth_user_id'] = 'wujha433gawefawfwfiuj2ou9823r98h';
     *             ['handle']       = 'myLogin';
     *             ['passwd']       = 'd346gs2gwaeiuhaeiuuweijfjuwaefhj';
     *             ['lastlogin']    = 1254801292; (Unix timestamp)
     *             ['is_active']    = 1; (1 = yes, 0 = no)
     *             ['owner_user_id']    = 1;
     *             ['owner_group_id']   = 1;
     * </code>
     *
     * Filters can be either complex or simple.
     *
     * In their simple form you just need to pass an associative array
     * with key/value, the key will be the table field name and value the value
     * you are searching. It will consider that you want an do to do a
     * field=value comparison, every additional filter will be appended with AND
     *
     * The complicated form of filters is to pass an array such as
     *
     * array(
     *     'fieldname' => array('op' => '>', 'value' => 'dummy', 'cond' => ''),
     *     'fieldname' => array('op' => '<', 'value' => 'dummy2', 'cond' => 'OR'),
     * );
     *
     * It can then build relatively complex queries. If you need joins or more
     * complicated queries than that please consider using an alternative
     * solution such as PEAR::DB_DataObject
     *
     * Any aditional field will be returned. The array key will be of the same
     * case it is given.
     *
     *  $cols = array('myField');
     *
     * e.g.: getUsers(null, $cols) will return
     *
     * <code>
     * $userData[0]['auth_user_id'] = 'wujha433gawefawfwfiuj2ou9823r98h';
     *             ['handle']       = 'myLogin';
     *             ['passwd']       = 'd346gs2gwaeiuhaeiuuweijfjuwaefhj';
     *             ['myField']      = 'value';
     * </code>
     *
     * @access  public
     * @param   array  filters to apply to fetched data
     * @param   string  if not null 'ORDER BY $order' will be appended to the query
     * @param   boolean will return an associative array with the auth_user_id
     *                  as the key by using DB::getAssoc() instead of DB::getAll()
     * @return  mixed  Array with user data or DB error.
     */
    function getUsers($filters = array(), $order = null, $rekey = false)
    {
        if (!$this->init_ok) {
            return false;
        }

        $fields = $where = '';
        $customFields = array();

        if (isset($this->authTableCols['optional']['lastlogin'])) {
            $customFields[] = $this->authTableCols['optional']['lastlogin']['name'] . ' AS lastlogin';
        }

        if (isset($this->authTableCols['optional']) && sizeof($this->authTableCols['optional']) > 0) {
            foreach ($this->authTableCols['optional'] as $alias => $field_data) {
                if ($alias == 'is_active') {
                    $field_data['name'] = "CASE {$this->authTableCols['optional']['is_active']['name']}
                                WHEN 'Y' THEN 1
                                WHEN 'N' THEN 0 END";
                }
                $customFields[] = $field_data['name'] . ' AS ' . $alias;
            }
        }

        if (isset($this->authTableCols['custom']) && sizeof($this->authTableCols['custom']) > 0) {
            foreach ($this->authTableCols['custom'] as $alias => $field_data) {
                $customFields[] = $field_data['name'] . ' AS ' . $alias;
            }
        }

        if (sizeof($customFields > 0)) {
              $fields  = ',';
              $fields .= implode(',', $customFields);	
        }

        if (sizeof($filters) > 0) {
            $where = ' WHERE';
            foreach ($filters as $f => $v) {
                if (is_array($v)) {
                    $cond = ' ' . $v['cond'];
                    $where .= ' ' . $v['name'] . $v['op'] . $this->dbc->quote($v['value'], $v['type']) . $cond;
                } else {
                    $cond = ' AND';
                    $where .= " $f=$v" . $cond;
                }
            }
            $where = substr($where, 0, -(strlen($cond)));
        }

        if (!is_null($order)) {
            $order = ' ORDER BY ' . $order;
        }

        // First: Get all data from auth table.
        $query = '
            SELECT
                ' . $this->authTableCols['required']['auth_user_id']['name'] . ' AS auth_user_id,
                ' . $this->authTableCols['required']['handle']['name'] . ' AS handle,
                ' . $this->authTableCols['required']['passwd']['name'] . ' AS passwd
                ' . $fields . '
            FROM
                ' . $this->authTable
            . $where
            . $order;

        if ($rekey) {
            $res = $this->dbc->getAssoc($query, false, array(), DB_FETCHMODE_ASSOC);
        } else {
            $res = $this->dbc->getAll($query, array(), DB_FETCHMODE_ASSOC);
        }

        return $res;
    }
}
?>