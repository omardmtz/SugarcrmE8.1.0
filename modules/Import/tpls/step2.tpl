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

.link {
    text-decoration:underline
}
</style>
{/literal}


{$INSTRUCTION}

<div class="hr"></div>

<form enctype="multipart/form-data" name="importstep2" method="POST" action="index.php" id="importstep2">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="Import">
<input type="hidden" name="custom_delimiter" value="{$CUSTOM_DELIMITER}">
<input type="hidden" name="custom_delimiter_other" value="{$CUSTOM_DELIMITER_OTHER}">
<input type="hidden" name="custom_enclosure" value="{$CUSTOM_ENCLOSURE}">
<input type="hidden" name="source" value="{$SOURCE}">
<input type="hidden" name="source_id" value="{$SOURCE_ID}">
<input type="hidden" name="action" value="Confirm">
<input type="hidden" name="current_step" value="{$CURRENT_STEP}">
<input type="hidden" name="import_module" value="{$IMPORT_MODULE}">
<input type="hidden" name="from_admin_wizard" value="{$smarty.request.from_admin_wizard}">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td align="left" scope="row" colspan="4" style="padding-left: 10px;">{$SAMPLE_URL} &nbsp;{sugar_help text=$MOD.LBL_SAMPLE_URL_HELP}</td>
        </tr>
        <tr>
            <td scope="row" colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td scope="row" colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td align="left" scope="row" colspan="3"><label for="userfile">{$MOD.LBL_SELECT_FILE}</label> <input type="hidden" /><input size="20" id="userfile" name="userfile" type="file"/> &nbsp;{sugar_help text=$MOD.LBL_FILE_UPLOAD_WIDGET_HELP}</td>
        </tr>
        <tr>
            <td scope="row" colspan="4"><div class="hr">&nbsp;</div></td>
        </tr>
        <tr>
            <td scope="row" colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td scope="row" colspan="3">
                <h3>{$MOD.LBL_IMPORT_TYPE}&nbsp;</h3></td>
          </tr>
          <tr>
            <td scope="row" colspan="3">
                <input id="import_create" class="radio" type="radio" name="type" value="import" checked="checked" />
                &nbsp;<label for="type">{$MOD.LBL_IMPORT_BUTTON}</label> &nbsp;{sugar_help text=$MOD.LBL_CREATE_BUTTON_HELP}
            </td>
          </tr>
          <tr>
            <td scope="row" colspan="3">
                <input id="import_update" class="radio" type="radio" name="type" id="type" value="update" />
                &nbsp;<label for="type">{$MOD.LBL_UPDATE_BUTTON}</label> &nbsp;{sugar_help text=$MOD.LBL_UPDATE_BUTTON_HELP}
            </td>
          </tr>
	</table>
    <br>
    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="preset-settings">
          {foreach from=$custom_mappings item=item name=custommappings}
          {capture assign=mapping_label}{$MOD.LBL_CUSTOM_MAPPING_}{$item|upper}{/capture}
          <tr>
            <td colspan="3" scope="row"><input class="radio" type="radio" id="source" name="source" value="{$item}" />
              &nbsp;<label for="source">{$mapping_label}</label></td>
          </tr>
          {/foreach}

          {if !empty($custom_imports) || !empty($published_imports)}
          <tr>
            <td scope="row" colspan="3">
                <h3>{$MOD.LBL_PUBLISHED_SOURCES}&nbsp;{sugar_help text=$savedMappingHelpText}</h3></td>
          </tr>
          
          <tr id="custom_import_{$smarty.foreach.saved.index}" class="preset-row preset-none">
            <td scope="row" colspan="4" class="preset-label">
                <input class="radio" type="radio" name="source" value=""/>
                &nbsp;{$MOD.LBL_NONE}
            </td>

          </tr>
          {/if}
          {foreach from=$custom_imports key=key item=item name=saved}
          <tr id="custom_import_{$smarty.foreach.saved.index}" class="preset-row preset-custom">
            <td scope="row" colspan="2" width="10%" class="preset-label">
                <input class="radio" type="radio" name="source" value="custom:{$item.IMPORT_ID}"/>
                &nbsp;{$item.IMPORT_NAME}
            </td>
            <td scope="row" class="preset-actions">
                {if $is_admin}
                <input type="button" name="publish" value="{$MOD.LBL_PUBLISH}" class="button" publish="yes"
                    onclick="publishMapping(this, 'yes','{$item.IMPORT_ID}');">
                {/if}
                <input type="button" name="delete" value="{$MOD.LBL_DELETE}" class="button"
					onclick="if(confirm('{$MOD.LBL_DELETE_MAP_CONFIRMATION}')){literal}{{/literal} deleteMapping('custom_import_{$smarty.foreach.saved.index}', '{$item.IMPORT_ID}' );{literal}}{/literal}">
            </td>
          </tr>
          {/foreach}

          {foreach from=$published_imports key=key item=item name=published}
          <tr id="published_import_{$smarty.foreach.published.index}" class="preset-row preset-custom">
            <td scope="row" colspan="2" class="preset-label">
                <input class="radio" type="radio" name="source" value="custom:{$item.IMPORT_ID}"/>
                &nbsp;{$item.IMPORT_NAME}
            </td>
            <td scope="row" class="preset-actions">
                {if $is_admin}
                <input type="button" name="publish" value="{$MOD.LBL_UNPUBLISH}" class="button" publish="no"
                    onclick="publishMapping(this, 'no','{$item.IMPORT_ID}');">
                <input type="button" name="delete" value="{$MOD.LBL_DELETE}" class="button"
                    onclick="if(confirm('{$MOD.LBL_DELETE_MAP_CONFIRMATION}')){literal}{{/literal}deleteMapping('published_import_{$smarty.foreach.published.index}','{$item.IMPORT_ID}' );{literal}}{/literal}">
                {/if}
            </td>
          </tr>
          {/foreach}
    </table>
</td>
</tr>
</table>

<br>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
  <td align="left">
        {if $displayBackBttn}
            <input title="{$MOD.LBL_BACK}"  class="button" type="submit" name="button" value="  {$MOD.LBL_BACK}  " id="goback">&nbsp;
        {/if}
      <input title="{$MOD.LBL_NEXT}"  class="button" type="submit" name="button" value="  {$MOD.LBL_NEXT}  " id="gonext">
    </td>
</tr>
</table>
<script>
{$JAVASCRIPT}
</script>  
</form>
