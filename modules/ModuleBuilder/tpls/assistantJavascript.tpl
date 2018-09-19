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
<script>
{literal}
if(typeof(Assistant)!="undefined" && Assistant.mbAssistant){
	//Assistant.mbAssistant.render(document.body);
{/literal}
{if $userPref }
	Assistant.processUserPref("{$userPref}");
{/if}
{if $assistant.key && $assistant.group}
	Assistant.mbAssistant.setBody(SUGAR.language.get('ModuleBuilder','assistantHelp').{$assistant.group}.{$assistant.key});
{/if}
{literal}
	if(Assistant.mbAssistant.visible){
		Assistant.mbAssistant.show();
		}
}
{/literal}
</script>
