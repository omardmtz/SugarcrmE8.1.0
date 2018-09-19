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


<table cellpadding='0' cellspacing='0' width='100%' border='0' class='list View'>
{assign var="link_select_id" value="selectLinkTop"}
{assign var="link_action_id" value="actionLinkTop"}
{include file='include/ListView/ListViewPagination.tpl'}

	<tr height='20'>
	    {if !empty($quickViewLinks)}
		<th scope='col' width='1%'>&nbsp;</th>
		{/if}
		{counter start=0 name="colCounter" print=false assign="colCounter"}
		{foreach from=$displayColumns key=colHeader item=params}
			<th scope='col' width='{$params.width}%' >
				<span sugar="sugar{$colCounter}"><div style='white-space: nowrap;'width='100%' align='{$params.align|default:'left'}'>
                {if $params.sortable|default:true}
	                <a href='{$pageData.urls.orderBy}{$params.orderBy|default:$colHeader|lower}' class='listViewThLinkS1' title=$arrowAlt>{sugar_translate label=$params.label module=$pageData.bean.moduleDir}&nbsp;&nbsp;
					{if $params.orderBy|default:$colHeader|lower == $pageData.ordering.orderBy}
						{if $pageData.ordering.sortOrder == 'ASC'}
							{capture assign="imageName"}arrow_down.$arrowExt{/capture}
                            {capture assign="alt_sort"}{sugar_translate label='LBL_ALT_SORT_DESC'}{/capture}
							{sugar_getimage name=$imageName width=$arrowWidth height=$arrowHeight attr='align="absmiddle" border="0" ' alt="$alt_sort"}
						{else}
							{capture assign="imageName"}arrow_up.$arrowExt{/capture}
                            {capture assign="alt_sort"}{sugar_translate label='LBL_ALT_SORT_ASC'}{/capture}
							{sugar_getimage name=$imageName width=$arrowWidth height=$arrowHeight attr='align="absmiddle" border="0" ' alt="$alt_sort"}
						{/if}
					{else}
						{capture assign="imageName"}arrow.$arrowExt{/capture}
                        {capture assign="alt_sort"}{sugar_translate label='LBL_ALT_SORT'}{/capture}
						{sugar_getimage name=$imageName width=$arrowWidth height=$arrowHeight attr='align="absmiddle" border="0" ' alt="$alt_sort"}
					{/if}
                    </a>
				{else}
					{sugar_translate label=$params.label module=$pageData.bean.moduleDir}
				{/if}
				</div></span sugar='sugar{$colCounter}'>
			</th>
			{counter name="colCounter"}
		{/foreach}
	</tr>

	{foreach name=rowIteration from=$data key=id item=rowData}
		{if $smarty.foreach.rowIteration.iteration is odd}
			{assign var='_rowColor' value=$rowColor[0]}
		{else}
			{assign var='_rowColor' value=$rowColor[1]}
		{/if}
		<tr height='20' class='{$_rowColor}S1'>
			{if !empty($quickViewLinks)}
			<td width='1%' nowrap>
				{if $pageData.access.edit && $pageData.bean.moduleDir != "Employees"}
					<a title='{$editLinkString}' id="edit-{$rowData.ID}" href="javascript:parent.SUGAR.App.router.navigate(parent.SUGAR.App.router.buildRoute('{$pageData.bean.moduleDir}', '{$rowData.ID}'), {$smarty.ldelim}trigger: true{$smarty.rdelim});" title="{sugar_translate label="LBL_EDIT_INLINE"}">{sugar_getimage name="edit_inline.gif" attr='border="0" '}</a>
				{/if}
			</td>
			{/if}
			{counter start=0 name="colCounter" print=false assign="colCounter"}
			{foreach from=$displayColumns key=col item=params}
				<td scope='row' align='{$params.align|default:'left'}' {if $params.nowrap}nowrap='nowrap' {/if}valign='top'><span sugar="sugar{$colCounter}b">
					{if $col == 'NAME' || $params.bold}<b>{/if}
				    {if $params.link && !$params.customCode}
						<{$pageData.tag.$id[$params.ACLTag]|default:$pageData.tag.$id.MAIN} href="#" onMouseOver="javascript:lvg_nav('{if $params.dynamic_module}{$rowData[$params.dynamic_module]}{else}{$params.module|default:$pageData.bean.moduleDir}{/if}', '{$rowData[$params.id]|default:$rowData.ID}', 'd', {$smarty.foreach.rowIteration.iteration}, this);"  onFocus="javascript:lvg_nav('{if $params.dynamic_module}{$rowData[$params.dynamic_module]}{else}{$params.module|default:$pageData.bean.moduleDir}{/if}', '{$rowData[$params.id]|default:$rowData.ID}', 'd', {$smarty.foreach.rowIteration.iteration}, this);">
						{/if}
					{if $params.customCode}
						{sugar_evalcolumn_old var=$params.customCode rowData=$rowData}
					{else}
                       {sugar_field parentFieldArray=$rowData vardef=$params displayType=ListView field=$col}
					{/if}
					{if empty($rowData.$col)}&nbsp;{/if}
					{if $params.link && !$params.customCode}
						</{$pageData.tag.$id[$params.ACLTag]|default:$pageData.tag.$id.MAIN}>
                    {/if}
                    {if $col == 'NAME' || $params.bold}</b>{/if}
				</span sugar='sugar{$colCounter}b'></td>
				{counter name="colCounter"}
			{/foreach}
	    	</tr>
	{foreachelse}
	<tr height='20' class='{$rowColor[0]}S1'>
	    <td colspan="{$colCount}">
	        <em>{$APP.LBL_NO_DATA}</em>
	    </td>
	</tr>
	{/foreach}
{assign var="link_select_id" value="selectLinkBottom"}
{assign var="link_action_id" value="actionLinkBottom"}
{include file='include/ListView/ListViewPagination.tpl'}
</table>
<script type='text/javascript'>
{literal}
    function lvg_nav(m, id, act, offset, t) {
        t = $(t);
        if (t.attr('href') !== '#') {
            return;
        }
        switch (act) {
            case 'pte':
                act = 'ProjectTemplatesEditView';
                break;
            case 'd':
                act = 'DetailView';
                break;
            default:
                act = 'EditView';
                break;
        }
{/literal}
        var url = '#bwc/index.php?module=' + m + '&offset=' + offset + '&stamp={$pageData.stamp}&return_module=' +
            m +'&action=' + act + '&record=' + id;
{literal}
        t.attr('href', location.origin + location.pathname + url);
        if (!_.isUndefined(parent.SUGAR) && !_.isUndefined(parent.SUGAR.App.view)) {
            parent.SUGAR.App.controller.layout.getComponent('bwc').convertToSidecarLink(t);
        }
    }
{/literal}
{literal}function lvg_dtails(id){{/literal}return SUGAR.util.getAdditionalDetails( '{$pageData.bean.moduleDir}',id, 'adspan_'+id);{literal}}{/literal}
{if $contextMenus}
	{$contextMenuScript}
{/if}
</script>
