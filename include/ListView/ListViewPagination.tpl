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


{assign var="alt_start" value=$navStrings.start}
{assign var="alt_next" value=$navStrings.next}
{assign var="alt_prev" value=$navStrings.previous}
{assign var="alt_end" value=$navStrings.end}

	<tr class='pagination'  role='presentation'>
		<td colspan='{if $prerow}{$colCount+1}{else}{$colCount}{/if}'>
			<table border='0' cellpadding='0' cellspacing='0' width='100%' class='paginationTable'>
				<tr>
					<td nowrap="nowrap" width='2%' class='paginationActionButtons'>
						{if $prerow}

                        {sugar_action_menu id=$link_select_id params=$selectLink}
					
						{/if}

						{sugar_action_menu id=$link_action_id params=$actionsLink}

                        {if $actionDisabledLink ne ""}<div class='selectActionsDisabled' id='select_actions_disabled_{$action_menu_location}'>
                            {$actionDisabledLink}
                            {if $actionsLink.buttons|@count gt 1}<span class='ab'></span>{else}&nbsp;{/if}
                        {/if}
                        {$selectedObjectsSpan}
					</td>
					<td  nowrap='nowrap' width='1%' align="right" class='paginationChangeButtons'>
						{if $pageData.urls.startPage}
							<button type='button' id='listViewStartButton_{$action_menu_location}' name='listViewStartButton' title='{$navStrings.start}' class='button' {if $prerow}onclick='return sListView.save_checks(0, "{$moduleString}");'{else} onClick='location.href="{$pageData.urls.startPage}"' {/if}>
								{sugar_getimage name="start" ext=".png" alt=$navStrings.start other_attributes='align="absmiddle" border="0" ' alt ="$alt_start"}
							</button>
						{else}
							<button type='button' id='listViewStartButton_{$action_menu_location}' name='listViewStartButton' title='{$navStrings.start}' class='button' disabled='disabled'>
								{sugar_getimage name="start_off" ext=".png" alt=$navStrings.start other_attributes='align="absmiddle" border="0" '}
							</button>
						{/if}
						{if $pageData.urls.prevPage}
							<button type='button' id='listViewPrevButton_{$action_menu_location}' name='listViewPrevButton' title='{$navStrings.previous}' class='button' {if $prerow}onclick='return sListView.save_checks({$pageData.offsets.prev}, "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.prevPage}"'{/if}>
								{sugar_getimage name="previous" ext=".png" alt=$navStrings.previous other_attributes='align="absmiddle" border="0" ' alt ="$alt_prev"}
							</button>
						{else}
							<button type='button' id='listViewPrevButton_{$action_menu_location}' name='listViewPrevButton' class='button' title='{$navStrings.previous}' disabled='disabled'>
								{sugar_getimage name="previous_off" ext=".png" alt=$navStrings.previous other_attributes='align="absmiddle" border="0" '}
							</button>
						{/if}
							<span class='pageNumbers'>({if $pageData.offsets.lastOffsetOnPage == 0}0{else}{$pageData.offsets.current+1}{/if} - {$pageData.offsets.lastOffsetOnPage} {$navStrings.of} {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$pageData.offsets.total}{if $pageData.offsets.lastOffsetOnPage != $pageData.offsets.total}+{/if}{/if})</span>
						{if $pageData.urls.nextPage}
							<button type='button' id='listViewNextButton_{$action_menu_location}' name='listViewNextButton' title='{$navStrings.next}' class='button' {if $prerow}onclick='return sListView.save_checks({$pageData.offsets.next}, "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.nextPage}"'{/if}>
								{sugar_getimage name="next" ext=".png" alt=$navStrings.next other_attributes='align="absmiddle" border="0" ' alt ="$alt_next"}
							</button>
						{else}
							<button type='button' id='listViewNextButton_{$action_menu_location}' name='listViewNextButton' class='button' title='{$navStrings.next}' disabled='disabled'>
								{sugar_getimage name="next_off" ext=".png" alt=$navStrings.next other_attributes='align="absmiddle" border="0" '}
							</button>
						{/if}
						{if $pageData.urls.endPage  && $pageData.offsets.total != $pageData.offsets.lastOffsetOnPage}
							<button type='button' id='listViewEndButton_{$action_menu_location}' name='listViewEndButton' title='{$navStrings.end}' class='button' {if $prerow}onclick='return sListView.save_checks("end", "{$moduleString}")' {else} onClick='location.href="{$pageData.urls.endPage}"'{/if}>
								{sugar_getimage name="end" ext=".png" alt=$navStrings.end other_attributes='align="absmiddle" border="0" ' alt ="$alt_end"}
							</button>
						{elseif !$pageData.offsets.totalCounted || $pageData.offsets.total == $pageData.offsets.lastOffsetOnPage}
							<button type='button' id='listViewEndButton_{$action_menu_location}' name='listViewEndButton' title='{$navStrings.end}' class='button' disabled='disabled'>
							 	{sugar_getimage name="end_off" ext=".png" alt=$navStrings.end other_attributes='align="absmiddle" '}
							</button>
						{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>
