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
 * Update user_preferences: move saved queries from "global" into separate categories
 */
class SugarUpgradeReorganizeUserPreferences extends UpgradeScript
{
    public $order = 5000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.2.2', '>=')) {
            return;
        }

        $qty = 100;
        $chunk = 0;

        $bean = BeanFactory::newBean('UserPreferences');

        do {
            $fetchCount = 0;

            $query = new SugarQuery();
            $query->from($bean);
            $query->limit($qty);
            $query->offset($chunk * $qty);
            $query->where()->equals('category', 'global');
            $query->select(array('id', 'assigned_user_id', 'contents'));
            $rows = $query->execute();

            foreach ($rows as $row) {
                $fetchCount++;

                $preferences = @unserialize(base64_decode($row['contents']));

                if (!empty($preferences)) {
                    foreach ($preferences as $key => $value) {
                        if (substr($key, -1) == 'Q') {
                            $insert = array(
                                'category' => "sq_$key",
                                'deleted' => 0,
                                'assigned_user_id' => $row['assigned_user_id'],
                                'contents' => base64_encode(serialize($value)),
                            );

                            $bean->populateFromRow($insert);
                            $bean->save();
                            unset($preferences[$key]);
                        }
                    }

                    $insert = array(
                        'id' => $row['id'],
                        'category' => 'global',
                        'deleted' => 0,
                        'assigned_user_id' => $row['assigned_user_id'],
                        'contents' => base64_encode(serialize($preferences)),
                    );
                    $bean->populateFromRow($insert);
                    $bean->save();
                }
            }

            $chunk++;

        } while ($fetchCount == $qty);
    }
}
