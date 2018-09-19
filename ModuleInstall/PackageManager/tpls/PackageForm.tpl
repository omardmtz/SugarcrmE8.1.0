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
{if $ERR_SUHOSIN == true}
{$APP_STRINGS.ERR_SUHOSIN}
{else}
{$scripts}
{$TREEHEADER}
{literal}

<style type="text/css">
#demo { width:100%; }
#demo .yui-content {
    padding:1em; /* pad content container */
}
.list {list-style:square;width:500px;padding-left:16px;}
.list li{padding:2px;font-size:8pt;}

/* hide the tab content while loading */
.tab-content{display:none;}

pre {
   font-size:11px;
}

#tabs1 {width:100%;}
#tabs1 .yui-ext-tabbody {border:1px solid #999;border-top:none;}
#tabs1 .yui-ext-tabitembody {display:none;padding:10px;}

/* default loading indicator for ajax calls */
.loading-indicator {
	font-size:8pt;
	background-image:url('../../resources/images/grid/loading.gif?v={VERSION_MARK}');
	background-repeat: no-repeat;
	background-position: left;
	padding-left:20px;
}
/* height of the rows in the grids */
.ygrid-row {
    height:27px;
}
.ygrid-col {
    height:27px !important;
}
</style>
{/literal}
{$INSTALLED_PACKAGES_HOLDER}
<br>

<form action='{$form_action}' method="post" name="installForm">
<input type=hidden name="release_id">
{$hidden_fields}
<div id='server_upload_div'>
{$INSTALL_ERRORS}
{$MODULE_SELECTOR}
<div id='search_results_div'></div>
</div>
</form>
<div id='local_upload_div'>
{$FORM_1_PLACE_HOLDER}
</div>

{if $module_load == 'true'}
<div id='upload_table'>
<table width='100%'><tr><td><div id='patch_downloads' class='ygrid-mso' style='height:205px;'></div></td></tr></table>
</div>

{literal}<script>
//PackageManager.toggleView('browse');
</script>
{/literal}
{/if}
{/if}
