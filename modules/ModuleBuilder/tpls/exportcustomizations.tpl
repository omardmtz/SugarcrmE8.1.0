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

<form name="exportcustom" id="exportcustom">
{sugar_csrf_form_token}
<input type='hidden' name='module' value='ModuleBuilder'>
<input type='hidden' name='action' value='ExportCustom'>
<div align="left">
{if !$nb_mod}
    <input type="button" class="button" name="exportCustomBtn" value="{$mod_strings.LBL_BTN_BACK}" onclick="ModuleBuilder.getContent('module=ModuleBuilder&action=wizard')">
    <input disabled="disabled" type="submit" class="button" name="exportCustomBtn" value="{$mod_strings.LBL_EC_EXPORTBTN}">
{else}
    <input type="submit" class="button" name="exportCustomBtn" value="{$mod_strings.LBL_EC_EXPORTBTN}" onclick="return check_form('exportcustom');">
{/if}
</div>

{if !$nb_mod}
    <br>
    <h3 class="required">{$mod_strings.LBL_EC_NOCUSTOM}</h3>
{/if}

<br>
    <table class="mbTable">
    <tbody>
    <tr>
    	<td class="mbLBL">
    		<b><font color="#ff0000">*</font> {$mod_strings.LBL_EC_NAME} </b>
    	</td>
    	<td>
    		<input type="text" value="" size="50" name="name"/>
    	</td>
    </tr>
    <tr>
    	<td class="mbLBL">
    		<b>{$mod_strings.LBL_EC_AUTHOR} </b>
    	</td>
    	<td>
    		<input type="text" value="" size="50" name="author"/>
    	</td>
    </tr>
    <tr>
    	<td class="mbLBL">
    		<b>{$mod_strings.LBL_EC_DESCRIPTION} </b>
    	</td>
    	<td>
    		<textarea rows="3" cols="60" name="description"></textarea>
    	</td>
    </tr>
    <tr>
    	<td height="100%"/>
    	<td/>
    </tr>
    </tbody>
	</table>
	
    <table border="0" CELLSPACING="15" WIDTH="100%" class="checkboxset">
        <TR><TD><input type="hidden" name="hiddenCount"></TD></TR>
        {foreach from=$modules key=k item=i}
        
        <TR>
            <TD><h3 style='margin-bottom:20px;'>{if $i != ""}<INPUT type="checkbox" name="modules[]" value={$k}>{/if}{$moduleList[$k]}</h3></TD>
            <TD VALIGN="top">
            {foreach from=$i item=j}
            {$j}<br>
            {/foreach}
            </TD>
        </TR>
        
        {/foreach} 
    </table>
    <br> 
</form>

<script type="text/javascript">
ModuleBuilder.helpRegister('exportcustom');
ModuleBuilder.helpSetup('exportcustom','exportHelp');
addToValidate('exportcustom', 'modules[]', 'checkboxset', true, '{$mod_strings.LBL_EC_CHECKERROR}');
addToValidate('exportcustom', 'name', 'DBName', true, '{$mod_strings.LBL_JS_PACKAGE_NAME}');
</script>
{include file='modules/ModuleBuilder/tpls/assistantJavascript.tpl'}
