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
<!-- Sidecar Config -->
<script type="text/javascript" src="cache/config.js"></script>
<!-- CSS -->
{foreach from=$css_url item=url}
    <link rel="stylesheet" href="{$url}"/>
{/foreach}
<style>
    h2{literal}{line-height: 100%;}{/literal}
    body{literal}{padding-top: 0px;}{/literal}
</style>
<div id="portal_themeroller" style="">
    <div id="alerts" class="alert-top">
        <div class="alert alert-process">
            <strong>
                <div class="loading">
                    {$LBL_LOADING}<i class="l1">&#46;</i><i class="l2">&#46;</i><i class="l3">&#46;</i>
                </div>
            </strong>
        </div>
    </div>
    <div class="content">
    </div>
</div>




{literal}

<script language="javascript">
SUGAR.App.config.platform = 'portal';

// set our auth Token
SUGAR.App.sugarAuthStore.set('AuthAccessToken', {/literal}'{$token}'{literal});

// bootstrap token
(function (app) {
    app.augment("theme", {
        initTheme:function (authAccessToken) {
            app.AUTH_ACCESS_TOKEN = authAccessToken;
            app.AUTH_REFRESH_TOKEN = authAccessToken;
            app.init({
                el:"#portal_themeroller",
                contentEl:".content"
            });
            return app;
        }
    });
})(SUGAR.App);
// Reset app if it already exists
if (App){
    App.destroy();
}
// Call initTheme with the session id as token
var App = SUGAR.App.theme.initTheme({/literal}'{$token}'{literal});

// should already be logged in to sugar, don't need to log in to sidecar.
App.api.isAuthenticated = function () {
    return true;
};

// Disabling the app sync complete event which starts sidecars competing router
App.events.off("app:sync:complete");
//force app sync and load the appropriate view on success
App.sync(
        {
            callback:function (data) {
                $('#alerts').empty();
                App.controller.loadView({
                    layout:'themeroller',
                    create:true
                });
            },
            err:function (data) {
                console.log("app sync error");
            }
        }
);

</script>
{/literal}
