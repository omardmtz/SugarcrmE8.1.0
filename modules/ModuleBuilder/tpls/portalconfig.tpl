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
<link rel="stylesheet" href="{sugar_getjspath file='include/javascript/select2/select2.css'}"/>
<script language='javascript' src="{sugar_getjspath file='include/javascript/select2/select2.js'}"></script>
<form id='0' name='0'>
{sugar_csrf_form_token}
    <table class='tabform' width='100%' cellpadding=4>

        <tr>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_CONFIGURE}:
            </td>
            <td colspan='1' nowrap>
                <input type="checkbox" name="appStatus" {if $appStatus eq 'online'}checked{/if} class='portalField' id="appStatus" value="online"/>
                {$mod.LBL_PORTAL_ENABLE}
            </td>
        </tr>
        {if $appStatus eq 'online'}
        <tr>
            <td>&nbsp;</td>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_SITE_URL} <a href="{$siteURL}/portal/index.html" target="_blank">{$siteURL}/portal/index.html</a>
            </td>
        </tr>
        {/if}
        <tr>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_LOGO_URL}: {sugar_help text=$mod.LBL_CONFIG_PORTAL_URL}
            </td>
            <td colspan='1' nowrap>
                <input class='portalProperty portalField' id='logoURL' name='logoURL' value='{$logoURL}' size=60>
            </td>
        </tr>
        <tr>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_LIST_NUMBER}:<span class="required">*</span>
            </td>
            <td colspan='1' nowrap>
                <input class='portalProperty portalField' id='maxQueryResult' name='maxQueryResult' value='{$maxQueryResult}' size=4>
            </td>
        </tr>
        <tr>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_DEFAULT_ASSIGN_USER}:
            </td>
            <td colspan='1' nowrap class="defaultUser">
                <select data-placeholder="{$mod.LBL_USER_SELECT}" class="portalProperty portalField" id='defaultUser' data-name='defaultUser' >
                {foreach from=$userList item=user key=userId}
                    <option value="{$userId}" {if $userId == $defaultUser}selected{/if}>{$user}</option>
                {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td colspan='2' nowrap>
                <input type='button' class='button' id='gobutton' value='{$mod.LBL_BTN_SAVE}'>
            </td>
        </tr>

    </table>
</form>
<div>

    {if $disabledDisplayModules}
    <br>
    <p>
        {$mod.LBL_PORTAL_DISABLED_MODULES}
    <ul>
        {foreach from=$disabledDisplayModulesList item=modName}
            <li>{$modName}</li>
        {/foreach}
    </ul>
    </p>
    <p>
        {$mod.LBL_PORTAL_ENABLE_MODULES}
    </p>
    {/if}

</div>
{literal}

<script language='javascript'>
    // Hack: In iframe and jquery's getting loaded twice so $ doesn't seem to have select2 plugin
    jQuery('#defaultUser').select2({
        placeholder: "{$mod.LBL_USER_SELECT}",
        allowClear: true,
        width: '50%'
    });
    addToValidateRange(0, "maxQueryResult", "int", true,{/literal}"{$mod.LBL_PORTAL_LIST_NUMBER}"{literal},1,100);
    addToValidateUrl(0, 'logoURL', 'alphanumeric', false, {/literal}"{$mod.LBL_PORTAL_LOGO_URL}"{literal});
    $('#gobutton').click(function(event){
        var $field, fields, props, i, key, val;
        fields = $('.portalField');
        props = {};

        for(i=0; i<fields.length; i++) {
            $field = $(fields[i]);
            key = $field.attr('name') || $field.data('name');
            val = $field.val();
            // select2 copies over attributes (including .portalField class) to a temp element and
            // so we end up with an extra fields element; so here we ignore if not both key/val
            if(key) props[key] = val;

            if ($field.is(':checked')) {
                // We look for both: isset, and, 'true' on other side ('online' still considered falsy!)
                props[key] = 'true';
            }
        }
        retrieve_portal_page($.param(props));
    });
    function retrieve_portal_page(props) {
        if (validate_form(0,'')) {
            ModuleBuilder.getContent("module=ModuleBuilder&action=portalconfigsave&" + props);
            removeFromValidate(0, 'maxQueryResult');
            removeFromValidate(0, 'logoURL');
        }
    }
</script>
{/literal}
