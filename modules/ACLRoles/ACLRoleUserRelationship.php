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
 * Relationship between ACL Roles and Users which maintains ACL Role Sets
 */
class ACLRoleUserRelationship extends M2MRelationship
{
    /**
     * @var AclRoleSetRegistrar
     */
    protected $registrar;

    /**
     * {@inheritDoc}
     */
    public function add($lhs, $rhs, $additionalFields = array())
    {
        $result = parent::add($lhs, $rhs, $additionalFields);
        if ($result) {
            $this->registerUserAclRoles($rhs);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($lhs, $rhs, $save = true)
    {
        $result = parent::remove($lhs, $rhs, $save);
        if ($result) {
            $this->registerUserAclRoles($rhs);
        }

        return $result;
    }

    /**
     * Registers current set of user's roles
     *
     * @param User $user
     */
    protected function registerUserAclRoles(User $user)
    {
        if (!$this->registrar) {
            $this->registrar = new AclRoleSetRegistrar();
        }

        $this->registrar->registerAclRoleSet($user);
    }
}
