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
 * Container for medium-complexity rights managements.
 *
 * @package  LiveUser
 * @category authentication
 */

define('LIVEUSER_GROUP_TYPE_ALL',   1);
define('LIVEUSER_GROUP_TYPE_ROLE',  2);
define('LIVEUSER_GROUP_TYPE_USER',  3);

/**
 * Require the parent class definition
 */

require_once 'LiveUser/Admin/Perm/Container/DB_Simple.php';

/**
 * This is a PEAR::DB admin class for the LiveUser package.
 *
 * It takes care of managing the permission part of the package.
 *
 * A PEAR::DB connection object can be passed to the constructor to reuse an
 * existing connection. Alternatively, a DSN can be passed to open a new one.
 *
 * Requirements:
 * - Files "common.php", "Container/DB_Medium.php" in directory "Perm"
 * - Array of connection options must be passed to the constructor.
 *   Example: array("server" => "localhost", "user" => "root",
 *   "password" => "pwd", "database" => "AllMyPreciousData")
 *
 * @author  Christian Dickmann <dickmann@php.net>
 * @author  Markus Wolff <wolff@21st.de>
 * @author  Matt Scifo <mscifo@php.net>
 * @author  Arnaud Limbourg <arnaud@php.net>
 * @version $Id: DB_Medium.php,v 1.65 2004/07/31 10:10:55 lsmith Exp $
 * @package LiveUser
 */
class LiveUser_Admin_Perm_Container_DB_Medium extends LiveUser_Admin_Perm_Container_DB_Simple
{
    /**
     * Constructor
     *
     * @access protected
     * @param  array  full liveuser conf array
     * @return void
     */
    function LiveUser_Admin_Perm_Container_DB_Medium(&$connectOptions)
    {
        $this->LiveUser_Admin_Perm_Container_DB_Simple($connectOptions);
    }

    /**
     * Add a group to the database
     *
     * @access public
     * @param  string  name of group constant
     * @param  string  name of group
     * @param  string description of group
     * @param  string define name for the group
     * @param  array optional fields (array('name'=>'value'))
     * @param  array custom fields (array('name'=>'value'))
     * @return mixed   integer (group_id) or MDB2 Error object
     */
    function addGroup($group_name, $group_description = null, $define_name = null,
        $optionalFields = array(), $customFields = array())
    {
        if (!$this->init_ok) {
            return false;
        }

        // Get next group ID
        $groupId = $this->dbc->nextId($this->prefix . 'groups');

        if (DB::isError($groupId)) {
            return $groupId;
        }

        $col = $val = array();

        if (isset($this->groupTableCols['optional']) && sizeof($optionalFields) > 0) {
            foreach ($optionalFields as $alias => $value) {
                $col[] = $this->groupTableCols['optional'][$alias]['name'];
                $val[] = $this->dbc->quoteSmart($value);
            }
        }

        if (isset($this->groupTableCols['custom']) && sizeof($customFields) > 0) {
            foreach ($customFields as $alias => $value) {
                $col[] = $this->groupTableCols['custom'][$alias]['name'];
                $val[] = $this->dbc->quoteSmart($value);
            }
        }

        if (is_array($col) && count($col) > 0) {
            $col = ',' . implode(',', $col);
            $val = ',' . implode(',', $val);
        }

        // Insert Group into Groups table
        $query = '
            INSERT INTO
                ' . $this->prefix . 'groups

                (
                ' . $this->groupTableCols['required']['group_id']['name'] . ',
                ' . $this->groupTableCols['required']['group_define_name']['name'] . '
                ' . $col . '
                )

            VALUES
                (
                ' . $this->dbc->quoteSmart($groupId) . ',
                ' . $this->dbc->quoteSmart($define_name) . '
                ' . $val . ')';

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        // Insert Group translation into Translations table
        $result = $this->addTranslation(
            $groupId,
            LIVEUSER_SECTION_GROUP,
            $this->getCurrentLanguage(),
            $group_name,
            $group_description
        );

        if (DB::isError($result)) {
            return $result;
        }

        return $groupId;
    }

    /**
     * Deletes a group from the database
     *
     * @access public
     * @param  integer id of deleted group
     * @return mixed   boolean or MDB2 Error object
     */
    function removeGroup($groupId)
    {
        // Delete user assignments
        $query = 'DELETE FROM
                  ' . $this->prefix . 'groupusers
                WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                    . $this->dbc->quoteSmart($groupId);

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        // Delete group rights
        $query = 'DELETE FROM
                  ' . $this->prefix . 'grouprights
                WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                    . $this->dbc->quoteSmart($groupId);

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        // Delete group itself
        $query = 'DELETE FROM
                  ' . $this->prefix . 'groups
                WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                      . (int)$groupId;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        // Delete group translations
        $result = $this->removeTranslation($groupId, LIVEUSER_SECTION_GROUP, $this->getCurrentLanguage(), true);

        if (DB::isError($result)) {
            return $result;
        }

        return true;
    }


    /**
     * Update group
     *
     * @access public
     * @param  string  id of group
     * @param  string  name of group
     * @param  string description of group
     * @param  string define name for the group
     * @param  array optional fields (array('name'=>'value'))
     * @param  array custom fields (array('name'=>'value'))
     * @return mixed   boolean or MDB2 Error object
     */
    function updateGroup($groupId, $group_name, $group_description = null,
        $define_name = null, $optionalFields = array(), $customFields = array())
    {
        if (!$this->init_ok) {
            return false;
        }

        $updateValues = array();

        if (!empty($define_name)) {
            $updateValues[] =
                $this->groupTableCols['required']['group_define_name']['name'] . ' = '
                    . $this->dbc->quoteSmart($define_name);
        }

        if (isset($this->groupTableCols['optional']) && sizeof($optionalFields) > 0) {
            foreach ($optionalFields as $alias => $value) {
                $updateValues[] = $this->groupTableCols['optional'][$alias]['name'] . '=' .
                    $this->dbc->quoteSmart($value);
            }
        }

        if (isset($this->groupTableCols['custom']) && sizeof($customFields) > 0) {
            foreach ($customFields as $alias => $value) {
                $updateValues[] = $this->groupTableCols['custom'][$alias]['name'] . '=' .
                    $this->dbc->quoteSmart($value);
            }
        }

        if (!empty($updateValues)) {
            $query = 'UPDATE
                      ' . $this->prefix . 'groups
                    SET
                      ' . implode(', ', $updateValues) . '
                    WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                    . $this->dbc->quoteSmart($groupId);

            $result = $this->dbc->query($query);

            if (DB::isError($result)) {
                return $result;
            }
        }

        // Update Group translation into Translations table
        $result = $this->updateTranslation(
            $groupId,
            LIVEUSER_SECTION_GROUP,
            $this->getCurrentLanguage(),
            $group_name,
            $group_description
        );

        if (DB::isError($result)) {
            return $result;
        }

        return true;
    }

    /**
     * Activate group
     *
     * @access public
     * @param integer id of group
     * @return mixed  boolean or DB Error object or false
     */
    function activateGroup($groupId)
    {
        if (!is_numeric($groupId)) {
            return false;
        }

        $query = 'UPDATE
                  ' . $this->prefix . 'groups
                SET
                  is_active = \'Y\'
                WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                      . (int)$groupId;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        return true;
    }

    /**
     * Deactivate group
     *
     * @access public
     * @param  integer id of group
     * @return mixed   boolean or DB Error object
     */
    function deactivateGroup($groupId)
    {
        $query = 'UPDATE
                  ' . $this->prefix . 'groups
                SET
                  is_active = \'N\'
                WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                      . (int)$groupId;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        return true;
    }

    /**
     * Grant right to group
     *
     * @access public
     * @param  integer id of group
     * @param  integer id of right
     * @return mixed   boolean or DB Error object
     */
    function grantGroupRight($groupId, $rightId)
    {
        //return if this group already has right
        $query = 'SELECT
                  count(*)
                FROM
                  ' . $this->prefix . 'grouprights
                WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                    . (int)$groupId . ' AND
                  right_id = ' . (int)$rightId;

        $count = $this->dbc->getOne($query);

        if (DB::isError($count) || $count != 0) {
            return false;
        }

        $query = 'INSERT INTO
                  ' . $this->prefix . 'grouprights
                  (' . $this->groupTableCols['required']['group_id']['name'] . ', right_id, right_level)
                VALUES
                  (
                    ' . (int)$groupId . ',
                    ' . (int)$rightId . ', '.LIVEUSER_MAX_LEVEL.'
                  )';

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        // Job done ...
        return true;
    }

    /**
     * Delete a right
     *
     * @access public
     * @param  integer id of right
     * @return mixed   boolean or DB Error object
     */
    function removeRight($rightId)
    {
        $res = $this->revokeGroupRight($rightId);

        if (!$res) {
            return false;
        }

        parent::removeRight($rightId);
    }

    /**
     * Revoke right from group
     *
     * @access public
     * @param  integer id of group
     * @param  integer id of right
     * @return boolean true on success or false on failure
     */
    function revokeGroupRight($groupId, $rightId = null)
    {
        $query = 'DELETE FROM
                  ' . $this->prefix . 'grouprights
                WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                      . (int)$groupId;
        if (!is_null($rightId)) {
            $query .= ' AND
              right_id = ' . (int)$rightId;
        }

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return false;
        }

        // Job done ...
        return true;
    }

    /**
     * Update right level of groupRight
     *
     * @access public
     * @param  integer id of group
     * @param  integer id of right
     * @param  integer right level
     * @return mixed   boolean or DB Error object
     */
    function updateGroupRight($groupId, $rightId, $right_level)
    {
        $query = 'UPDATE
                  ' . $this->prefix . 'grouprights
                SET
                  right_level = ' . (int)$right_level . '
                WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                    . (int)$groupId . ' AND
                  right_id = ' . (int)$rightId;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        // Job done ...
        return true;
    }

    /**
     * Add User to Group
     *
     * @access public
     * @param  string  id of user
     * @param  integer id of group
     * @return mixed   boolean or DB Error object or false if
     *                 user already belongs to the group
     */
    function addUserToGroup($permId, $groupId)
    {
        $query = 'SELECT COUNT(*)
                  FROM ' . $this->prefix . 'groupusers
                WHERE
                    perm_user_id=' . (int)$permId . '
                AND
                    ' . $this->groupTableCols['required']['group_id']['name'] . '='
                        . (int)$groupId;

        $res = $this->dbc->getOne($query);

        if ($res > 0) {
            return false;
        }

        $query = 'INSERT INTO
                  ' . $this->prefix . 'groupusers
                  (' . $this->groupTableCols['required']['group_id']['name'] . ', perm_user_id)
                VALUES
                  (
                    ' . (int)$groupId . ',
                    ' . (int)$permId . '
                  )';

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        // Job done ...
        return true;
    }

    /**
     * Remove User from Group
     *
     * @access public
     * @param  string  id of user
     * @param  integer id of group
     * @return mixed   boolean or DB Error object
     */
    function removeUserFromGroup($permId, $groupId = null)
    {
        $query = 'DELETE FROM
                  ' . $this->prefix . 'groupusers
                WHERE
                  perm_user_id  = ' . (int)$permId;

        if (!is_null($groupId)) {
            $query .= ' AND ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                . (int)$groupId;
        }
        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        }

        // Job done ...
        return true;
    }

    /**
     * get all perm_user_id from Group
     *
     * @access public
     * @param  integer id of group
     * @return mixed   boolean or DB Error object
     */
    function getUsersFromGroup($groupId)
    {
        $query = 'SELECT perm_user_id FROM
                  ' . $this->prefix . 'groupusers
                WHERE
                  ' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                    . (int)$groupId;

        $result = $this->dbc->getCol($query);

        return $result;
    }

    /**
     * Get list of all groups
     *
     * This method accepts the following options...
     *  'where_user_id' = [PERM_USER_ID],
     *  'where_group_id' = [GROUP_ID],
     *  'where_is_active' = [BOOLEAN],
     *  'with_rights' = [BOOLEAN]
     *
     * @access public
     * @param  array  an array determining which fields and conditions to use
     * @return mixed array or DB Error object
     */
    function getGroups($options = null)
    {
        if (!$this->init_ok) {
            return false;
        }

        $fields = '';
        $customFields = array();

        $types = array(
            $this->groupTableCols['required']['group_id']['type'],
            $this->groupTableCols['required']['group_define_name']['type'],
        );

        if (isset($this->groupTableCols['optional']['group_type'])) {
            $customFields[] = $this->groupTableCols['optional']['group_type']['name'] . ' AS group_type';
        }

        if (isset($this->groupTableCols['optional']['is_active'])) {
             $customFields[] = "CASE {$this->groupTableCols['optional']['is_active']['name']}
                                WHEN 'Y' THEN 1
                                WHEN 'N' THEN 0 END
                                    AS is_active";
        }

        if (isset($this->groupTableCols['optional']['owner_user_id'])) {
            $customFields[] = $this->groupTableCols['optional']['owner_user_id']['name'] . ' AS owner_user_id';
        }

        if (isset($this->groupTableCols['optional']['owner_group_id'])) {
            $customFields[] = $this->groupTableCols['optional']['owner_group_id']['name'] . ' AS owner_group_id';
        }

        if (isset($this->groupTableCols['custom']) && sizeof($this->groupTableCols['custom']) > 0) {
            foreach ($this->groupTableCols['custom'] as $alias => $field_data) {
                $customFields[] = $field_data['name'] . ' AS ' . $alias;
            }
        }

        if (sizeof($customFields > 0)) {
              $fields  = ',';
              $fields .= implode(', groups.', $customFields);	
        }

        $query = 'SELECT
                groups.' . $this->groupTableCols['required']['group_id']['name'] . ' AS group_id,
                groups.' . $this->groupTableCols['required']['group_define_name']['name']  . ' AS group_define_name,
                  translations.name         AS name,
                  translations.description  AS description
                ' . $fields . '
            FROM';

        if (isset($options['where_user_id'])
                && is_numeric($options['where_user_id'])) {
            $query .= ' ' . $this->prefix . 'groupusers groupusers,';
        }

        $query .= ' ' . $this->prefix . 'groups groups,
                  ' . $this->prefix . 'translations translations
                WHERE';

        if (isset($options['where_user_id'])
                && is_numeric($options['where_user_id'])) {
            $query .= ' groupusers.perm_user_id = ' . $this->dbc->quote($options['where_user_id'], 'integer') . ' AND
                      groupusers.' . $this->groupTableCols['required']['group_id']['name']
                        . ' = groups.' . $this->groupTableCols['required']['group_id']['name'] . ' AND';
        }

        if (isset($options['where_group_id'])
                 && is_numeric($options['where_group_id'])) {
            $query .= ' groups.' . $this->groupTableCols['required']['group_id']['name'] . ' = '
                . $this->dbc->quoteSmart($options['where_group_id']) . ' AND';
        }

        if (isset($options['where_group_type'])
                 && is_numeric($options['where_group_type'])
        ) {
            $query .= ' groups.' . $this->groupTableCols['optional']['group_type']['name'] . ' = '
                . $this->dbc->quoteSmart($options['where_group_type']) . ' AND';
        }

        if (isset($options['where_owner_user_id'])
                && is_numeric($options['where_owner_user_id'])) {
            $query .= ' groups.' . $this->groupTableCols['optional']['owner_user_id']['name'] . ' = '
                . $this->dbc->quoteSmart($options['where_owner_user_id']) . ' AND';
        }

        if (isset($options['where_owner_group_id'])
                && is_numeric($options['where_owner_group_id'])) {
            $query .= ' groups.' . $this->groupTableCols['optional']['owner_group_id']['name'] . ' = '
                . $this->dbc->quoteSmart($options['where_owner_group_id']) . ' AND';
        }

        if (isset($options['where_is_active'])
                && is_string($options['where_is_active'])) {
            $query .= ' groups.' . $this->groupTableCols['optional']['is_active']['name'] . ' = '
                . $this->dbc->quoteSmart($options['where_owner_group_id']) . ' AND';
        }

        $query .= ' translations.section_id = groups.group_id AND translations.section_type = ' . LIVEUSER_SECTION_GROUP . ' AND translations.language_id = ' . $this->dbc->quote($this->_langs[$this->getCurrentLanguage(, 'integer')]);

       $_groups = $this->dbc->getAll($query, null, DB_FETCHMODE_ASSOC);

        if (DB::isError($_groups)) {
            return $_groups;
        }

        $groups = array();
        if (is_array($_groups)) {
            foreach($_groups as $key => $value) {
                if (isset($options['with_rights'])) {
                    $_options = $options;
                    $_options['where_group_id'] = $value['group_id'];
                    $value['rights'] = $this->getRights($_options);
                }
                $groups[$value['group_id']] = $value;
            }
        }

        return $groups;
    }
}
?>