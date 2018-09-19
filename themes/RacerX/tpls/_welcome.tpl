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

{if $AUTHENTICATED}
<div id="welcome">
    {$APP.NTC_WELCOME}, <strong><a id="welcome_link" href='index.php?module=Users&action=EditView&record={$CURRENT_USER_ID}'>{$CURRENT_USER}</a></strong> <span>|</span> <a id="logout_link" href='{$LOGOUT_LINK}' class='utilsLink'>{$LOGOUT_LABEL}</a> 
</div>
{/if}