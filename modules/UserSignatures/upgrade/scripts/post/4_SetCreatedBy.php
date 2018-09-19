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
class SugarUpgradeSetCreatedBy extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_DB;

    /**
     * Copy the value of the user_id field to the created_by field for all UserSignatures records.
     */
    public function run()
    {
        if (!version_compare($this->from_version, '7.2.1', '<')) {
            return;
        }
        $seed = BeanFactory::newBean('UserSignatures');
        $sql = "UPDATE "
            . $seed->getTableName()
            . " SET created_by=user_id, modified_user_id="
            . $GLOBALS['db']->quoted($GLOBALS['current_user']->id)
            . ", date_modified="
            . $GLOBALS['db']->quoted($GLOBALS['timedate']->nowDb())
            . " WHERE created_by IS NULL OR created_by = '' OR created_by <> user_id";
        $GLOBALS['db']->query($sql);
    }
}
