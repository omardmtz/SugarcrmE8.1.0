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
<script language='javascript'>
iframe = parent.document.getElementById('style_preview');
if(iframe) {
    tail='&r='+Math.round(Math.random*10000);
	iframe.src = 'index.php?module=ModuleBuilder&action=portalpreview&to_pdf=1' + tail;
}
{/literal}
parent.document.getElementById('uploadLabel').innerHTML = '{$mod.LBL_SP_UPLOADED}';
</script>
