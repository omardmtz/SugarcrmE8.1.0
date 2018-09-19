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


class pmse_InboxDashlet extends DashletGeneric {
    public function __construct($id, $def = null)
    {
		global $current_user, $app_strings;
		require 'modules/pmse_Inbox/metadata/dashletviewdefs.php';

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'pmse_Inbox');

        $this->searchFields = $dashletData['pmse_InboxDashlet']['searchFields'];
        $this->columns = $dashletData['pmse_InboxDashlet']['columns'];

        $this->seedBean = array();//new pmse_Inbox();
    }
}
