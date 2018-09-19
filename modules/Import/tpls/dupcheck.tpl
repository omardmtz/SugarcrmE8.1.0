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
{literal}
<style>
<!--

#DupeCheck{
    border: none;
    box-shadow:none;
}

#selected_indices
{
    padding-left:30px;
    padding-right:30px;
}


-->
</style>
{/literal}

{$INSTRUCTION}
<form enctype="multipart/form-data" real_id="importstepdup" id="importstepdup" name="importstepdup" method="POST" action="index.php">
{sugar_csrf_form_token}

{foreach from=$smarty.request key=k item=v}
    {if $k neq 'current_step'}
        {if is_array($v)}
            {foreach from=$v key=k1 item=v1}
                <input type="hidden" name="{$k}[]" value="{$v1}">
            {/foreach}
        {else}
            <input type="hidden" name="{$k}" value="{$v}">
        {/if}
    {/if}
{/foreach}

<input type="hidden" name="module" value="Import">
<input type="hidden" name="import_type" value="{$smarty.request.import_type}">
<input type="hidden" name="type" value="{$smarty.request.type}">
<input type="hidden" name="file_name" value="{$smarty.request.tmp_file}">
<input type="hidden" name="source_id" value="{$SOURCE_ID}">
<input type="hidden" name="to_pdf" value="1">
<input type="hidden" name="display_tabs_def">
<input type="hidden" id="enabled_dupes" name="enabled_dupes" value="">
<input type="hidden" id="disabled_dupes" name="disabled_dupes" value="">
<input type="hidden" id="current_step" name="current_step" value="{$CURRENT_STEP}">

   <div class="hr"></div>
    <div>
    <table border="0" cellpadding="30" id="importTable" style="width:60% !important;">
    <tr>
        <td  width="40%" colspan="2">
           <table id="DupeCheck" class="themeSettings edit view noBorder" style='margin-bottom:0px;' border="0" cellspacing="10" cellpadding="0"  width = '100%'>
                <tr>
                    <td align="right">
                        <div id="enabled_div" class="enabled_tab_workarea">
                        </div>
                    </td>
                    <td align="left">
                        <div id="disabled_div" class="disabled_tab_workarea">
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
     <div class="hr"></div>
    <span><strong><label for="save_map_as">{$MOD.LBL_SAVE_MAPPING_AS}</label></strong> {sugar_help text=$MOD.LBL_SAVE_MAPPING_HELP}</span>
            <span >
                <input type="text" name="save_map_as" id="save_map_as" value="" style="width: 20em" maxlength="254">
            </span>
    </div>
<br />

<table width="100%" cellpadding="2" cellspacing="0" border="0">
    <tr>
        <td align="left">
            <input title="{$MOD.LBL_BACK}"  id="goback" class="button" type="submit" name="button" value="  {$MOD.LBL_BACK}  ">&nbsp;
            <input title="{$MOD.LBL_IMPORT_NOW}"  id="importnow" class="button" type="button" name="button" value="  {$MOD.LBL_IMPORT_NOW}  ">
        </td>
    </tr>
</table>
</form>


