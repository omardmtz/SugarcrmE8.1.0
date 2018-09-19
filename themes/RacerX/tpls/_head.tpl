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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html {$langHeader}>
<head>
<link rel="SHORTCUT ICON" href="{$FAVICON_URL}">
<meta http-equiv="Content-Type" content="text/html; charset={$APP.LBL_CHARSET}">
<title>{$SYSTEM_NAME}</title>
{$SUGAR_CSS}
{if $AUTHENTICATED}
<link rel='stylesheet' href='{sugar_getjspath file="vendor/ytree/TreeView/css/folders/tree.css"}'/>
<link rel='stylesheet' href='{sugar_getjspath file="styleguide/assets/css/nvd3.css"}'/>
<link rel='stylesheet' href='{sugar_getjspath file="styleguide/assets/css/sucrose.css"}'/>
{/if}
{$SUGAR_JS}

{sugar_getscript file="include/javascript/mousetrap/mousetrap.min.js"}

{literal}
<script type="text/javascript">
<!--
SUGAR.themes.theme_name      = '{/literal}{$THEME}{literal}';
SUGAR.themes.hide_image      = '{/literal}{sugar_getimagepath file="hide.gif"}{literal}';
SUGAR.themes.show_image      = '{/literal}{sugar_getimagepath file="show.gif"}{literal}';
SUGAR.themes.loading_image      = '{/literal}{sugar_getimagepath file="img_loading.gif"}{literal}';
if ( YAHOO.env.ua )
    UA = YAHOO.env.ua;
-->


</script>


    <script type="text/javascript">
        if (window.parent && typeof(window.parent.SUGAR) !== 'undefined' && typeof(window.parent.SUGAR.App) !== 'undefined') {
            // update bwc context
            var app = window.parent.SUGAR.App;
            if (app.additionalComponents.sweetspot) {
                Mousetrap.bind('esc', function(e) {
                    app.additionalComponents.sweetspot.hide()
                    return false;
                });
                Mousetrap.bind('mod+shift+space', function(e) {
                    app.additionalComponents.sweetspot.show()
                    return false;
                });
            }
        }
    </script>
{/literal}
</head>
