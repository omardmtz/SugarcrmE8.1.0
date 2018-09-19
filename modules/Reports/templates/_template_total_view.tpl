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
{php}
// start template_total_table code
global $mod_strings;
require_once('modules/Reports/templates/templates_reports.php');
$reporter = $this->get_template_vars('reporter');
$total_header_row = $reporter->get_total_header_row(); 
$total_row = $reporter->get_summary_total_row();
if ( isset($total_row['group_pos'])) {
	$args['group_pos'] = $total_row['group_pos'];
} // if
if ( isset($total_row['group_column_is_invisible'])) {
	$args['group_column_is_invisible'] = $total_row['group_column_is_invisible'];
} // if
	$reporter->layout_manager->setAttribute('no_sort',1);
	echo get_form_header( $mod_strings['LBL_GRAND_TOTAL'],"", false); 
	template_header_row($total_header_row,$args);
{/php}
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="list view">
{if ($show_pagination neq "")}
{$pagination_data}
{/if}
<tr height="20">
{if ($isSummaryCombo)}
<td scope="col" align='center'  valign=middle nowrap>&nbsp;</td>
{/if}
{if ($isSummaryComboHeader)}
<td><span id="img_{$divId}"><a href="javascript:expandCollapseComboSummaryDiv('{$divId}')"><img width="8" height="8" border="0" absmiddle="" alt=$mod_strings.LBL_SHOW src="{$image_path}advanced_search.gif"/></a></span></td>
{/if}
{php}
	$count = 0;
	$this->assign('count', $count);
{/php}
{foreach from=$header_row key=module item=cell}
	{if (($args.group_column_is_invisible != "") && ($args.group_pos eq $count))}
{php}	
	$count = $count + 1;
	$this->assign('count', $count);
{/php}
	{ else }
	{if $cell eq ""}
{php}	
	$cell = "&nbsp;";
	$this->assign('cell', $cell);
{/php}
	{/if}
	
	<td scope="col" align='center'  valign=middle nowrap>	
	
	{$cell}
	{/if}
{/foreach}
</tr>
{php}
	if (! empty($total_row)) {
	template_list_row($total_row);
{/php}
	<tr height=20 class="{$row_class}" onmouseover="setPointer(this, '{$rownum}', 'over', '{$bg_color}', '{$hilite_bg}', '{$click_bg}');" onmouseout="setPointer(this, '{$rownum}', 'out', '{$bg_color}', '{$hilite_bg}', '{$click_bg}');" onmousedown="setPointer(this, '{$rownum}', 'click', '{$bg_color}', '{$hilite_bg}', '{$click_bg}');">
	{if ($isSummaryComboHeader)}
	<td><span id="img_{$divId}"><a href="javascript:expandCollapseComboSummaryDiv('{$divId}')"><img width="8" height="8" border="0" absmiddle="" alt=$mod_strings.LBL_SHOW src="{$image_path}advanced_search.gif"/></a></span></td>
	{/if}
	{php}
		$count = 0;
		$this->assign('count', $count);
	{/php}
	{foreach from=$column_row.cells key=module item=cell}
		{if (($column_row.group_column_is_invisible != "") && ($count|in_array:$column_row.group_pos)) }
	{php}	
		$count = $count + 1;
		$this->assign('count', $count);
	{/php}
		{ else }
		{if $cell eq ""}
	{php}	
		$cell = "&nbsp;";
		$this->assign('cell', $cell);
	{/php}
		{/if}		
		<td width="{$width}%" valign=TOP class="{$row_class}" bgcolor="{$bg_color}" scope="row">
		
		{$cell}
		{/if}
	{/foreach}
	</tr>
	
{php}
	} else {
	echo template_no_results();
	}
echo template_end_table($args);
	// end template_total_table code
//template_total_table($reporter);
template_query_table($reporter);
{/php}