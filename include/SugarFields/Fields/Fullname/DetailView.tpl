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
{if strlen({{sugarvar key='value' string=true}}) <= 0}
{assign var="value" value={{sugarvar key='default_value' string=true}} }
{else}
{assign var="value" value={{sugarvar key='value' string=true}} }
{/if}
<span id='{{sugarvar key='name'}}'>{{sugarvar key='value'}}</span>
&nbsp;&nbsp;
<span class="id-ff">
    <a id="btn_vCardButton" title="{$APP.LBL_VCARD}" href="#">{sugar_getimage alt=$app_strings.LBL_ID_FF_VCARD name="id-ff-vcard" ext=".png"}</a>
</span>
{{if !empty($displayParams.enableConnectors)}}
{if !empty($value)}
{{sugarvar_connector view='DetailView'}}
{/if}
{{/if}}

{literal}
<script type="text/javascript">
    $("#btn_vCardButton").click(function(e){
        {/literal}
        window.location.assign('index.php?module={$module}&action=vCard&record={$fields.id.value}&to_pdf=true');
        {literal}

        if (e.preventDefault) {
            e.preventDefault();
        }
    });
</script>
{/literal}
