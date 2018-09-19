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
<div class='wizard' width='100%' >
	<div align='left' id='export'>{$actions}</div>

	<div>{$question}</div>
	<div id="Buttons">

	<table align="center" cellspacing="7" width="90%"><tr>
		{counter start=0 name="buttonCounter" print=false assign="buttonCounter"}
		{foreach from=$buttons item='button' key='buttonName'}
			{if $buttonCounter > 5}
				</tr><tr>
				{counter start=0 name="buttonCounter" print=false assign="buttonCounter"}
			{/if}
			{ if !isset($button.size)}
				{assign var='buttonsize' value=''}
			{else}
				{assign var='buttonsize' value=$button.size}
			{/if}
			<td {if isset($button.help)}id="{$button.help}"{/if} width="16%" name=helpable" style="padding: 5px;"  valign="top" align="center">
			     <table onclick='{if $button.action|substr:0:11 == "javascript:"}{$button.action|substr:11}{else}ModuleBuilder.getContent("{$button.action}");{/if}' 
			         class='wizardButton' onmousedown="ModuleBuilder.buttonDown(this);return false;" onmouseout="ModuleBuilder.buttonOut(this);">
			         <tr>
						<td align="center"><a class='studiolink' href="javascript:void(0)" >
						{if isset($button.imageName)}
                            {if isset($button.altImageName)}
                                {sugar_image name=$button.imageTitle width=$button.size height=$button.size image=$button.imageName altimage=$button.altImageName}
                            {else}
                                {sugar_image name=$button.imageTitle width=$button.size height=$button.size image=$button.imageName}                            
                            {/if}
						{else}
							{sugar_image name=$button.imageTitle width=$button.size height=$button.size}
						{/if}</a></td>
					 </tr>
					 <tr>
						 <td align="center"><a class='studiolink' id='{$button.linkId}' href="javascript:void(0)">
						 {if (isset($button.imageName))}
							 {$button.imageTitle}
						 {else}
							 {if (isset($button.label))}
								 {$button.label}
							 {else}
								 {$buttonName}
							 {/if}
						 {/if}</a></td>
				     </tr>
				 </table>
			</td>
			{counter name="buttonCounter"}
		{/foreach}
	</tr></table>
<!-- Hidden div for hidden content so IE doesn't ignore it -->
<div style="float:left; left:-100px; display: hidden;">&nbsp;
	{literal}
	<style type='text/css'>
		.wizard { padding: 5px; text-align:center; font-weight:bold}
		.title{ color:#990033; font-weight:bold; padding: 0px 5px 0px 0px; font-size: 20pt}
		.backButton {position:absolute; left:10px; top:35px}
	</style>
    {/literal}

	<script>
	ModuleBuilder.helpRegisterByID('export', 'input');
	ModuleBuilder.helpRegisterByID('Buttons', 'td');
	ModuleBuilder.helpSetup('studioWizard','{$defaultHelp}');
	</script>
</div>
{include file='modules/ModuleBuilder/tpls/assistantJavascript.tpl'}
