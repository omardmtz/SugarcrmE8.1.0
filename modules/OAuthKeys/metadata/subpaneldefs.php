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


$layout_defs['OAuthKeys'] = array(
	// list of what Subpanels to show in the DetailView
	'subpanel_setup' => array(
		'tokens' => array(
			'order' => 30,
			'module' => 'OAuthTokens',
			'sort_order' => 'asc',
			'sort_by' => 'token_ts',
			'subpanel_name' => 'ForKeys',
			'get_subpanel_data' => 'tokens',
			'title_key' => 'LBL_TOKENS',
			'top_buttons' => array(
			),

		),
    )
);
