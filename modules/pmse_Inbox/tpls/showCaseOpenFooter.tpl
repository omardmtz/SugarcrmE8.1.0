{*
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
*}
<!---------------  START WORKFLOW SHOWCASE ------------>
<form action="index.php?module=ProcessMaker&action=routeCase" method="POST">
{sugar_csrf_form_token}
    <input type="hidden" name="cas_id" id="cas_id" value="{$cas_id}"/>
    <input type="hidden" name="cas_index" id="cas_index" value="{$cas_index}"/>
    <input type="hidden" name="cas_current_user_id" id="cas_current_user_id" value="{$cas_current_user_id}"/>
    <input type="hidden" name="act_adhoc_behavior" id="act_adhoc_behavior" value="{$act_adhoc_behavior}"/>
    <input type="hidden" name="act_adhoc_assignment" id="act_adhoc_assignment" value="{$act_adhoc_assignment}"/>
<!---------------  END WORKFLOW SHOWCASE ------------>