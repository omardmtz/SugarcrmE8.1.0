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

{if $controls}

<div class="clear"></div>

<div style='float:left; width: 50%;' class="calendarButtons">
{foreach name=tabs from=$tabs key=k item=tab}
	<input type="button" class="button" {if $view == $tab} selected {/if} id="{$tabs_params[$tab].id}" title="{$tabs_params[$tab].title}" value="{$tabs_params[$tab].title}" onclick="{$tabs_params[$tab].link}">
{/foreach}
</div>

<div style="float:left; text-align: right; width: 50%; font-size: 12px;"  class="calendarButtons">
	{if $view == "shared"}
		<button id="userListButtonId" type="button" class="button" onclick="javascript: CAL.toggle_shared_edit('shared_cal_edit');">{$MOD.LBL_EDIT_USERLIST}</button>
	{/if}
	{if $view != 'year' && !$print}
	<span class="dateTime">
					<img border="0" src="{$cal_img}" alt="{$APP.LBL_ENTER_DATE}" id="goto_date_trigger" align="absmiddle">
					<input type="hidden" id="goto_date" name="goto_date" value="{$current_date}">
					<script type="text/javascript">
					Calendar.setup ({literal}{{/literal}
						inputField : "goto_date",
						ifFormat : "%m/%d/%Y",
						daFormat : "%m/%d/%Y",
						button : "goto_date_trigger",
						singleClick : true,
						dateStr : "{$current_date}",
						step : 1,
						onUpdate: goto_date_call,
						startWeekday: {$start_weekday},
						weekNumbers:false
					{literal}}{/literal});
					{literal}
					YAHOO.util.Event.onDOMReady(function(){
						YAHOO.util.Event.addListener("goto_date","change",goto_date_call);
					});
					function goto_date_call(){
						CAL.goto_date_call();
					}
					{/literal}
					</script>
	</span>
	{/if}
	<input type="button" id="cal_settings" class="button" onclick="CAL.toggle_settings()" value="{$MOD.LBL_SETTINGS}">
</div>

<div style='clear: both;'></div>

{/if}


<div class="{if $controls}monthHeader{/if}">
	<div style='float: left; width: 20%;'>{$previous}</div>
	<div style='float: left; width: 60%; text-align: center;'><h3>{$date_info}</h3></div>
	<div style='float: right;'>{$next}</div>
	<br style='clear:both;'>
</div>
