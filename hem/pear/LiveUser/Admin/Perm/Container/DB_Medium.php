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
 * @version $Id: DB_Medium.php,v 1.54 2004/06/08 20:41:25 arnaud Exp $
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
    function LiveUser_Admin_Perm_Container_DB_Medium(&$connectOptions, $conf = null)
    {
        $this->LiveUser_Admin_Perm_Container_DB_Simple($connectOptions);

        if (!is_null($conf)) {
            $this->conf = $conf;
            $this->prefix = $this->conf['permContainer']['prefix'];
        }
    }

    /**
     * Add a group to the database
     *
     * @access public
     * @param  string  name of group constant
     * @param  string  name of group
     * @param  string description of group
     * @param  boolean activate group?
     * @param  integer group type (one of LIVEUSER_GROUP_TYPE_*)
     * @param  string define name for the group
     * @param  array custom fields (array('name'=>array('value'=>'foo'))
     * @return mixed   integer (group_id) or DB Error object
     */
    function addGroup($group_name, $group_description = null, $active = false,
        $define_name = null, $group_type = LIVEUSER_GROUP_TYPE_ALL, $customFields = array())
    {
        // Get next group ID
        $group_id = $this->dbc->nextId($this->prefix . 'groups');

        if (DB::isError($group_id)) {
            return $group_id;
        };

        $col = $val = '';
        if (sizeof($customFields) > 0) {
            foreach ($customFields as $k => $v) {
                $col[] = $k;
                $val[] = $this->dbc->quoteSmart($v['value']);
            }
        }

        if (is_array($col) && count($col) > 0) {
            $col = ',' . implode(',', $col);
            $val = ',' . implode(',', $val);
        }

        // Insert Group into Groupstable
        $query = 'INSERT INTO
                  ' . $this->prefix . 'groups
                  (group_id, group_type, group_define_name, is_active'.$col.')
                VALUES
                  (
                    ' . (int)$group_id . ',
                    ' . (int)$group_type . ',
                    ' . $this->dbc->quoteSmart($define_name) . ',
                    ' . $this->dbc->quoteSmart(($active ? 'Y' : 'N')) . '
                  '.$val.')';

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

        // Insert Group translation into Translations table
        $result = $this->addTranslation(
            $group_id,
            LIVEUSER_SECTION_GROUP,
            $this->getCurrentLanguage(),
            $group_name,
            $group_description
        );

        if (DB::isError($result)) {
            return $result;
        };

        return $group_id;
    }

    /**
     * Deletes a group from the database
     *
     *
     * @access public
     * @param  integer id of deleted group
     * @return mixed   boolean or DB Error object
     */
    function removeGroup($group_id)
    {
        // Delete user assignments
        $query = 'DELETE FROM
                  ' . $this->prefix . 'groupusers
                WHERE
                  group_id = ' . (int)$group_id;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

        // Delete group rights
        $query = 'DELETE FROM
                  ' . $this->prefix . 'grouprights
                WHERE
                  group_id = ' . (int)$group_id;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

        // Delete group itself
        $query = 'DELETE FROM
                  ' . $this->prefix . 'groups
                WHERE
                  group_id = ' . (int)$group_id;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

        // Delete group translations
        $result = $this->removeTranslation($group_id, LIVEUSER_SECTION_GROUP, $this->getCurrentLanguage(), true);

        if (DB::isError($result)) {
            return $result;
        };

        return true;
    }


    /**
     * Update group
     *
     * @access public
     * @param  string  id of group
     * @param  string  name of group
     * @param  string description of group
     * @param  boolean activate group?
     * @param  integer group type (one of LIVEUSER_GROUP_TYPE_*)
     * @param  string define name for the group
     * @param  array custom fields (array('name'=>array('value'=>'foo'))
     * @return mixed   boolean or DB Error object
     */
    function updateGroup($group_id, $group_name, $group_description = null,
        $active = null, $define_name = null, $group_type = null, $customFields = array())
    {
        $update = false;
        if(!is_null($active)) {
            $updateValues[] = 'is_active      = ' . $this->dbc->quoteSmart(($active ? 'Y' : 'N'));
        }
        if (!is_null($define_name)) {
            $updateValues[] = 'define_name     = ' . $this->dbc->quoteSmart($define_name);
        }
        if (!is_null($group_type)) {
            $updateValues[] = 'group_type     = ' . (int)$group_type;
        }
        if (sizeof($customFields) > 0) {
            foreach ($customFields as $k => $v) {
                $updateValues[] =
                    $k . ' = ' . $this->dbc->quoteSmart($v['value']);
            }
        }
        if ($update) {
            $query = 'UPDATE
                      ' . $this->prefix . 'groups
                    SET '
                      . implode(', ', $updateValues) . 
                    'WHERE
                      group_id = ' . (int)$group_id;

            $result = $this->dbc->query($query);

            if (DB::isError($result)) {
                return $result;
            };
        }

        // Update Group translation into Translations table
        $result = $this->updateTranslation(
            $group_id,
            LIVEUSER_SECTION_GROUP,
            $this->getCurrentLanguage(),
            $group_name,
            $group_description
        );

        if (DB::isError($result)) {
            return $result;
        };

        return true;
    }

    /**
     * Activate group
     *
     * @access public
     * @param integer id of group
     * @return mixed  boolean or DB Error object or false
     */
    function activateGroup($group_id)
    {
        if (!is_numeric($group_id)) {
            return false;
        }

        $query = 'UPDATE
                  ' . $this->prefix . 'groups
                SET
                  is_active = \'Y\'
                WHERE
                  group_id = ' . (int)$group_id;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

        return true;
    }

    /**
     * Deactivate group
     *
     * @access public
     * @param  integer id of group
     * @return mixed   boolean or DB Error object
     */
    function deactivateGroup($group_id)
    {
        $query = 'UPDATE
                  ' . $this->prefix . 'groups
                SET
                  is_active = \'N\'
                WHERE
                  group_id = ' . (int)$group_id;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

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
    function grantGroupRight($group_id, $right_id)
    {
        //return if this group already has right
        $query = 'SELECT
                  count(*)
                FROM
                  ' . $this->prefix . 'grouprights
                WHERE
                  group_id = ' . (int)$group_id . ' AND
                  right_id = ' . (int)$right_id;

        $count = $this->dbc->getOne($query);

        if (DB::isError($count) || $count != 0) {
            return false;
        };

        $query = 'INSERT INTO
                  ' . $this->prefix . 'grouprights
                  (group_id, right_id, right_level)
                VALUES
                  (
                    ' . (int)$group_id . ',
                    ' . (int)$right_id . ', '.LIVEUSER_MAX_LEVEL.'
                  )';

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

        // Job done ...
        return true;
    }

    /**
     * Revoke right from group
     *
     * @access public
     * @param  integer id of group
     * @param  integer id of right
     * @return mixed   boolean or DB Error object
     */
    function revokeGroupRight($group_id, $right_id = null)
    {
        $query = 'DELETE FROM
                  ' . $this->prefix . 'grouprights
                WHERE
                  group_id = ' . (int)$group_id;
        if (!is_null($right_id)) {
            $query .= ' AND
              right_id = ' . (int)$right_id;
        }

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

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
    function updateGroupRight($group_id, $right_id, $right_level)
    {
        $query = 'UPDATE
                  ' . $this->prefix . 'grouprights
                SET
                  right_level = ' . (int)$right_level . '
                WHERE
                  group_id = ' . (int)$group_id . ' AND
                  right_id = ' . (int)$right_id;

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

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
    function addUserToGroup($permId, $group_id)
    {
        $query = 'SELECT COUNT(*)
                  FROM ' . $this->prefix . 'groupusers
                WHERE
                    perm_user_id=' . (int)$permId . '
                AND
                    group_id=' . $group_id;

        $res = $this->dbc->getOne($query);

        if ($res > 0) {
            return false;
        }

        $query = 'INSERT INTO
                  ' . $this->prefix . 'groupusers
                  (group_id, perm_user_id)
                VALUES
                  (
                    ' . (int)$group_id . ',
                    ' . (int)$permId . '
                  )';

        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

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
    function removeUserFromGroup($permId, $group_id = null)
    {
        $query = 'DELETE FROM
                  ' . $this->prefix . 'groupusers
                WHERE
                  perm_user_id  = ' . (int)$permId;

        if (!is_null($group_id)) {
            $query .= ' AND group_id = ' . (int)$group_id;
        }
        $result = $this->dbc->query($query);

        if (DB::isError($result)) {
            return $result;
        };

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
    function getUserFromGroup($group_id)
    {
        $query = 'SELECT perm_user_id FROM
                  ' . $this->prefix . 'groupusers
                WHERE
                  group_id = ' . (int)$group_id;

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
        $fields = '';
        if (isset($this->groupTableCols['custom']) && sizeof($this->groupTableCols['custom']) > 0 ) {
            foreach ($this->groupTableCols['custom'] as $alias => $field_data) {
                $customFields[] = $field_data['name'] . ' AS ' . $alias;
            }
            $fields  = ',';
            $fields .= implode(',', $customFields);
        }

        $query = 'SELECT
                  groups.group_id           AS group_id,
                  groups.group_type         AS group_type,
                  groups.group_define_name  AS define_name,
                  groups.owner_user_id      AS owner_user_id,
                  groups.owner_group_id     AS owner_group_id,
                  translations.name         AS name,
                  translations.description  AS description,
                  groups.is_active          AS is_active
                  ' . $fields .'
                FROM ';

        if (isset($options['where_user_id'])
                 && is_numeric($options['where_user_id'])
        ) {
            $query .= $this->prefix . 'groupusers groupusers,';
        }

        $query .= $this->prefix . 'groups groups,
                  ' . $this->prefix . 'translations translations
                WHERE';

        if (isset($options['where_user_id'])
                 && is_numeric($options['where_user_id'])
        ) {
            $query .= ' groupusers.perm_user_id = ' .
                      (int)$options['where_user_id'] . ' AND
                      groupusers.group_id = groups.group_id AND';
        }

        if (isset($options['where_group_id'])
                 && is_numeric($options['where_group_id'])
        ) {
            $query .= ' groups.group_id = ' . (int)$options['where_group_id'] . ' AND';
        }

        if (isset($options['where_group_type'])
                 && is_numeric($options['where_group_type'])
        ) {
            $query .= ' groups.group_type = ' . (int)$options['where_group_type'] . ' AND';
        }

        if (isset($options['where_owner_user_id'])
                && is_numeric($options['where_owner_user_id'])
        ) {
            $query .= ' groups.owner_user_id = ' . (int)$options['where_owner_user_id'] . ' AND';
        }

        if (isset($options['where_owner_group_id'])
                && is_numeric($options['where_owner_group_id'])
        ) {
            $query .= ' groups.owner_group_id = ' . (int)$options['where_owner_group_id'] . ' AND';
        }

        if (isset($options['where_is_active'])
                && is_string($options['where_is_active'])
        ) {
            $query .= ' groups.is_active = ' . $options['where_is_active'] . ' AND';
        }

        $query .= ' translations.section_id = groups.group_id AND
                  translations.section_type = '.LIVEUSER_SECTION_GROUP . ' AND
                  translations.language_id = ' . (int)$this->_langs[$this->getCurrentLanguage()];

        $groups = $this->dbc->getAll($query, null, DB_FETCHMODE_ASSOC);

        if (DB::isError($groups)) {
            return $groups;
        };

        $_groups = array();
        if (is_array($groups)) {
            foreach($groups as $key => $value) {
                if (isset($options['with_rights'])) {
                    $_options = $options;
                    $_options['where_group_id'] = $value['group_id'];
                    $value['rights'] = $this->getRights($_options);
                };
                $_groups[$value['group_id']] = $value;
            };
        };

        return $_groups;
    }
}
?>