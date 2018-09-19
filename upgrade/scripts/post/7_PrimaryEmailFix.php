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
 * Fix users with no primary emails
 */
class SugarUpgradePrimaryEmailFix extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        //Fetch all users without primary email
        $result = $this->db->query("SELECT u.id AS user_id FROM users u WHERE u.id NOT IN (SELECT e.bean_id FROM email_addr_bean_rel e WHERE e.primary_address=1 AND e.deleted=0) AND u.deleted=0");

        while ($row = $this->db->fetchByAssoc($result)) {
            $user = BeanFactory::newBean('Users');
            $user->retrieve($row['user_id']);

            if (empty($user->email)) {
                continue;
            }

            $skip = false;
            foreach($user->email as $emailAddress) {
                if ($emailAddress['primary_address']) {
                    $skip = true;
                    break;
                }
            }

            if ($skip) {
                continue;
            }

            //First email will be primary
            $emailAddress = $user->email[0];

            $user->emailAddress->addAddress(
                $emailAddress['email_address'],
                true, 
                $emailAddress['reply_to_address'], 
                $emailAddress['invalid_email'], 
                $emailAddress['opt_out'], 
                $emailAddress['email_address_id']
            );

            $user->emailAddress->save($user->id, $user->module_dir);
        }
    }
}
