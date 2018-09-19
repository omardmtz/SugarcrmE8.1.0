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

class SugarUpgradeAclRolesRegisterUserAclRoleSets extends UpgradeScript
{
    public $order = 2200;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.6.0', '>=')) {
            $this->log('ACL role sets should be already registered, skipping');
            return;
        }

        $registrar = new AclRoleSetRegistrar();

        $result = $this->db->query("SELECT id FROM users where deleted = 0");
        while ($row = $this->db->fetchByAssoc($result)) {
            $user = BeanFactory::retrieveBean('Users', $row['id']);
            if ($user) {
                $this->log('Registering ACL role sets for user ' . $user->id);
                $registrar->registerAclRoleSet($user);
            }
        }
    }
}
