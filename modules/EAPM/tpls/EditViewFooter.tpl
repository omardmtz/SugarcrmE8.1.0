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

<script type="text/javascript" src="{sugar_getjspath file='modules/EAPM/EAPMEdit.js'}"></script>
<script type="text/javascript" src="{sugar_getjspath file='cache/include/externalAPI.cache.js'}"></script>
<script type="text/javascript">
EAPMFormName = 'EditView';
EAPMOAuthNotice = '{$MOD.LBL_OAUTH_SAVE_NOTICE}';
EAPMBAsicAuthNotice = '{$MOD.LBL_BASIC_SAVE_NOTICE}';
YAHOO.util.Event.onDOMReady(function() {ldelim}
EAPMEditStart(
{if is_admin($current_user) } true {else} false {/if}
);
{rdelim});
</script>
