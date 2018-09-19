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
{if $parentFieldArray.TEAM_COUNT > 1}
<span id='div_{$parentFieldArray.ID}_teams'>
{$parentFieldArray.$col}<a href="#" style='text-decoration:none;' onMouseOver="javascript:toggleMore('div_{$parentFieldArray.ID}_teams','img_{$parentFieldArray.ID}_teams', 'Teams', 'DisplayInlineTeams', 'team_set_id={$parentFieldArray.TEAM_SET_ID}&team_id={$parentFieldArray.TEAM_ID}');"  onFocus="javascript:toggleMore('div_{$parentFieldArray.ID}_teams','img_{$parentFieldArray.ID}_teams', 'Teams', 'DisplayInlineTeams', 'team_set_id={$parentFieldArray.TEAM_SET_ID}');" id='more_feather'>+</a>
</span>
{else}
{$parentFieldArray.$col}
{/if}