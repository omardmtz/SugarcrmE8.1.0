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
 * Registrar of user ACL role sets
 */
class AclRoleSetRegistrar
{
    /**
     * Registers current ACL role set of the user
     *
     * @param User $user
     */
    public function registerAclRoleSet(User $user)
    {
        $user->load_relationship('acl_role_sets');

        $previousRoleSet = $this->getUserRoleSet($user);
        $roles = $this->getUserRoles($user);
        if ($roles) {
            $currentRoleSet = $this->getRoleSetByRoles($roles);
            $user->acl_role_sets->add($currentRoleSet);
        } else {
            $user->acl_role_sets->delete($user->id);
        }

        if ($previousRoleSet) {
            $previousRoleSet->cleanUp();
        }
    }

    /**
     * Returns user's ACL role set
     *
     * @param User $user
     * @return ACLRoleSet
     */
    protected function getUserRoleSet(User $user)
    {
        $roleSets = $user->acl_role_sets->getBeans();
        return array_shift($roleSets);
    }

    /**
     * Returns user's ACL roles
     *
     * @param User $user
     * @return ACLRole[]
     */
    protected function getUserRoles(User $user)
    {
        return ACLRole::getUserRoles($user->id, false);
    }

    /**
     * Returns existing or new role set corresponding to the given set of roles
     *
     * @param ACLRole[] $roles
     * @return ACLRoleSet
     */
    protected function getRoleSetByRoles(array $roles)
    {
        $roleSet = ACLRoleSet::findByRoles($roles);
        if (!$roleSet) {
            $roleSet = ACLRoleSet::createFromRoles($roles);
        }

        return $roleSet;
    }
}
