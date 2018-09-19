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

class DataPrivacyHooks
{
    /**
     * The data privacy object needs to keep the fields_to_erase up to date when relationships are changed
     *
     * @param DataPrivacy $dp
     * @param string $event
     * @param array $params
     */
    public static function unlinkRecordsFromErase(DataPrivacy $dp, $event, $params = array())
    {
        $dp->relatedRecordRemoved($params['link'], $params['related_id']);
    }
}
