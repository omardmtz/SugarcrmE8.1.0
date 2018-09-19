<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

/**
 * ACL Role Set, represents unique set of ACL roles
 */
class ACLRoleSet extends Basic
{
    public $object_name = 'ACLRoleSet';
    public $module_name = 'ACLRoleSets';
    public $module_dir = 'ACLRoles';
    public $table_name = 'acl_role_sets';
    public $disable_custom_fields = true;
    public $hash;

    /**
     * Finds existing role set by set of roles
     *
     * @param ACLRole[] $roles
     * @return static|null
     */
    public static function findByRoles(array $roles)
    {
        $hash = self::getHashByRoles($roles);
        return self::loadByHash($hash, true);
    }

    /**
     * Creates new role set from set of roles
     *
     * If there's deleted role set, marks it as non-deleted
     *
     * @param ACLRole[] $roles
     * @return static
     */
    public static function createFromRoles(array $roles)
    {
        $hash = self::getHashByRoles($roles);
        $instance = self::loadByHash($hash, false);
        if ($instance) {
            $instance->mark_undeleted($instance->id);
        } else {
            $instance = BeanFactory::newBean('ACLRoleSets');
            $instance->hash = $hash;
            $instance->save();
        }

        $instance->load_relationship('acl_roles');
        $instance->acl_roles->add($roles);

        return $instance;
    }

    /**
     * Returns hash for the given set of roles, or NULL if the set is empty
     *
     * @param ACLRole[] $roles
     * @return string|null
     */
    protected static function getHashByRoles(array $roles)
    {
        if (!$roles) {
            return null;
        }

        $ids = array_map(function (ACLRole $role) {
            return $role->id;
        }, $roles);
        sort($ids);

        $ids = implode('_', $ids);
        return md5($ids);
    }

    /**
     * Loads existing role set by hash, or returns NULL if not found
     *
     * @param string $hash Role set hash
     * @param boolean $deleted Whether to look only deleted = 0
     *
     * @return SugarBean|null
     */
    protected static function loadByHash($hash, $deleted)
    {
        $query = new SugarQuery();
        $query->select('id');
        $query->from(new self, array('add_deleted' => $deleted))
            ->where()->equals('hash', $hash);
        $data = $query->execute();
        if ($data) {
            $row = array_shift($data);
            return BeanFactory::retrieveBean('ACLRoleSets', $row['id'], array(), $deleted);
        }

        return null;
    }

    /**
     * Cleans up role set after it was unassigned from user
     */
    public function cleanUp()
    {
        if (!$this->isAssigned()) {
            $this->mark_deleted($this->id);
        }
    }

    /**
     * Checks if role set is assigned to any user
     *
     * @return boolean
     */
    protected function isAssigned()
    {
        $query = new SugarQuery();
        $query->select()->setCountQuery();
        $query->from(new User)
            ->where()->equals('acl_role_set_id', $this->id);
        $data = $query->execute();
        $row = array_shift($data);
        $count = array_shift($row);

        return $count > 0;
    }
}
