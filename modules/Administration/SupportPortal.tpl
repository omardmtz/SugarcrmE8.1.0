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


{if $helpFileExists}
<html {$langHeader}>
<head>
<title>{$title}</title>
{$styleSheet}
<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
</head>
<body onLoad='window.focus();'>
<table width='100%'>
<tr>
    <td align='right'>
        <a href='javascript:window.print()'>{$MOD.LBL_HELP_PRINT}</a> - 
        <a href='mailto:?subject="{$MOD.LBL_SUGARCRM_HELP}&body={$currentURL|escape:url}'>{$MOD.LBL_HELP_EMAIL}</a> -
        <a href='#' onmousedown="createBookmarkLink('{$MOD.LBL_SUGARCRM_HELP} - {$moduleName}', '{$currentURL|escape:url}')">{$MOD.LBL_HELP_BOOKMARK}</a>
    </td>
</tr>
</table>
<table class='edit view'>
<tr>
    <td>{include file="$helpPath"}</td>
</tr>
</table>
{literal}
<script type="text/javascript" language="JavaScript">
<!--
function createBookmarkLink(title, url){
    if (document.all)
        window.external.AddFavorite(url, title);
    else if (window.sidebar)
        window.sidebar.addPanel(title, url, "")
}
-->
</script>
{/literal}
</body>
</html>	
{else}
<IFRAME frameborder="0" marginwidth="0" marginheight="0" bgcolor="#FFFFFF" SRC="{$iframeURL}" TITLE="{$iframeURL}" NAME="SUGARIFRAME" ID="SUGARIFRAME" WIDTH="100%" height="1000"></IFRAME>
{/if}