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
<script type="text/javascript">
    {if $SHOW_NON_EDITABLE_FIELDS_ALERT}
    {literal}
    app.alert.show('non_editable_employee_fields', {
        level: 'info',
        messages: '{/literal}{$NON_EDITABLE_FIELDS_MSG}{literal}',
        autoClose: false
    });
    {/literal}
    {/if}
</script>
{{include file='include/EditView/header.tpl'}}
