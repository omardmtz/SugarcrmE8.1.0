{{*
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
*}}
{{if $rowData.TEAM_COUNT > 1}}
<span id='div_{{$rowData.ID}}_teams'>
{{$rowData.$col}}<a href="#" style='text-decoration:none;' onMouseOver="javascript:toggleMore('div_{{$rowData.ID}}_teams','img_{{$rowData.ID}}_teams', 'Teams', 'DisplayInlineTeams', 'team_set_id={{$rowData.TEAM_SET_ID}}&team_id={{$rowData.TEAM_ID}}');"  onFocus="javascript:toggleMore('div_{{$rowData.ID}}_teams','img_{{$rowData.ID}}_teams', 'Teams', 'DisplayInlineTeams', 'team_set_id={{$rowData.TEAM_SET_ID}}');" id='div_{{$rowData.ID}}_teams'>+</a>
</span>
{{else}}
{{$rowData.$col}}
{{/if}}
