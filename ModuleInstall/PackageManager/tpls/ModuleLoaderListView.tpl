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

<table id='fileviewtable'>
	<tr height='20'>
		{counter start=0 name="colCounter" print=false assign="colCounter"}
		<th scope='col' width='5' nowrap="nowrap">view/hide</th>
		{foreach from=$displayColumns key=colHeader item=params}
			{if $params.show}
			<th scope='col' width='{$params.width}%'>
				<span sugar="sugar{$colCounter}"><div style='white-space: normal;'width='100%' align='{$params.align|default:'left'}'>
					{sugar_translate label=$params.label module='Administration'}
				</div></span sugar='sugar{$colCounter}'>
			</th>
			{/if}
				{counter name="colCounter"}
		{/foreach}
		<th scope='col' width='5' nowrap="nowrap">Select</th>
	</tr>
		{foreach name=rowIteration from=$data key=package_id item=package}
		{if $smarty.foreach.rowIteration.iteration is odd}
			{assign var='_bgColor' value=$bgColor[0]}
			{assign var='_rowColor' value=$rowColor[0]}
		{else}
			{assign var='_bgColor' value=$bgColor[1]}
			{assign var='_rowColor' value=$rowColor[1]}
		{/if}

			<tr id='package_tr_{$package_id}' height='20' class='{$_rowColor}S1'>
			<td scope='row' align='left' valign='top'><a class="listViewTdToolsS1" onclick="PackageManager.toggle_div('{$package_id}')" valign='top'><span id='span_toggle_package_{$package_id}'>{sugar_getimage name="advanced_search" ext=".gif" width="8" height="8" alt=$app_strings.LBL_ADVANCED_SEARCH other_attributes='border="0" '}&nbsp;</span></a></td>
			{counter start=0 name="colCounter" print=false assign="colCounter"}
			{foreach from=$displayColumns key=col item=params}
				<td scope='row' align='{$params.align|default:'left'}' valign='top'><span sugar="sugar{$colCounter}b">
					{if $params.show}
					{$package.$col}
					{/if}
				</span sugar='sugar{$colCounter}b'>
				</td>
				{counter name="colCounter"}
			{/foreach}
			<td scope='row' align='left' valign='top'><a class="listViewTdToolsS1" onclick="PackageManager.select_package('{$package_id}')" valign='top'>Select</a></td>
	    	</tr>
	    	<tr><td colspan="5"><table id='release_table_{$package_id}' style='display:none'>
	    	{foreach name=releaseIteration from=$package.releases key=release_id item=release}
		    	<tr id='release_tr_{$release_id}' height='20' class='{$_rowColor}S1'>
				{counter start=0 name="colCounter" print=false assign="colCounter"}
				{foreach from=$secondaryDisplayColumns key=col item=params}
					<td scope='row' align='{$params.align|default:'left'}' valign='top'><span sugar="sugar{$colCounter}b">
						{$release.$col}
					</span sugar='sugar{$colCounter}b'>
					</td>
					{counter name="colCounter"}
				{/foreach}
				<td scope='row' align='left' valign='top'><a class="listViewTdToolsS1" onclick="PackageManager.select_release('{$release_id}')" valign='top'>Select</a></td>
		    	</tr>
		    {/foreach}
		    </table></td></tr>
	 	
	{/foreach}
	
</table>
