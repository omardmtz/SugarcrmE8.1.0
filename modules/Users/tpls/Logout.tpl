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
{sugar_getscript file="sidecar/minified/sidecar.min.js"}
{sugar_getscript file="cache/config.js"}
{sugar_getscript file="cache/include/javascript/sugar_grp7.min.js"}
<script language="javascript">
    var App;
    App = SUGAR.App.init({ldelim}
    {rdelim});
    App.logout();
    document.location = "{$REDIRECT_URL}";
</script>
