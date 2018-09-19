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

<form id='StudioWizard' name='StudioWizard'>
{sugar_csrf_form_token}
<input type='hidden' name='module' value='ModuleBuilder'>
<input type='hidden' name='action' value='portalsyncsync'>
<table class='tabform' width='100%' cellpadding=4>
<tr>
<td colspan='2'>{$welcome}</td>
</tr>
<tr>
<td colspan='2' nowrap>
{$mod.LBL_PORTALSITE}
<input name='portalURL' id='portalURL' value='{$options}' size=60>
<input type='button' class='button' id='gobutton' value='{$mod.LBL_PORTAL_GO}'>
</td>
</tr>
<tr>
<td colspan='2'>
    {if strcmp($options, 'https://') != 0 || strcmp($options, 'http://') != 0 && $options != 'https://'}
		<iframe title='{$options}' style='border:0' id='portal_iframe' height='250' scrolling='auto'></iframe>
	{/if}
</td>
</tr>

</table>
</form>

{literal}
<script>
ModuleBuilder.helpSetup('portalSync','default');
</script>

<script language='javascript'>
function handleKeyDown(event) {
	e = getEvent(event);
	eL = getEventElement(e);
	if ((kc = e["keyCode"])) { 
        if(kc == 13) {
           retrieve_portal_page();
		   freezeEvent(e);
		}
	}
}//handleKeyDown()

function getEvent(event) {
	return (event ? event : window.event);
}//getEvent

function getEventElement(e) {
	return (e.srcElement ? e.srcElement: (e.target ? e.target : e.currentTarget));
}//getEventElement

function freezeEvent(e) {
	if (e.preventDefault) e.preventDefault();
	e.returnValue = false;
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
	return false;
}//freezeEvent

function retrieve_portal_page() {
	ModuleBuilder.getContent("module=ModuleBuilder&action=portalsyncsync&portalURL=" + document.StudioWizard.portalURL.value)
}

function load_portal_url() {
    var url = document.getElementById('portalURL').value + '/portal_sync.php';
    if(/http(s)?:\/\/\/portal_sync.php/.test(url)) {
       return;
    }
    
	var iframe = document.getElementById('portal_iframe');
	try {
	  iframe.src=url;
	} catch(e) {

	}
}

document.getElementById('portalURL').onkeydown = handleKeyDown;
document.getElementById('gobutton').onclick = retrieve_portal_page;
load_portal_url();
</script>
{/literal}
