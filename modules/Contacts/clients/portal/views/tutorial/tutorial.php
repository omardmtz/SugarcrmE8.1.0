<?php
//FILE SUGARCRM flav=ent
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
/*********************************************************************************
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$viewdefs['Contacts']['portal']['view']['tutorial'] = array(
    'record' => array( //Record layout is used for the Portal profile
        'version' => 1,
        'intro' => 'LBL_PORTAL_TOUR_PROFILE_INTRO',
        'content' => array(
            array(
                'name' => '.btn-primary[name="edit_button"]',
                'text' => 'LBL_PORTAL_TOUR_PROFILE_EDIT',
                'full' => true,
            ),
            array(
                'name' => '.record-label[data-name="preferred_language"]',
                'text' => 'LBL_PORTAL_TOUR_PROFILE_LANGUAGE',
                'full' => true,
            ),
            array(
                'name' => 'li#userActions',
                'text' => 'LBL_PORTAL_TOUR_PROFILE_RETURN',
                'full' => true,
            ),
        )
    ),
);
