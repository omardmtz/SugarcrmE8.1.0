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
<script src="{sugar_getjspath file='modules/Administration/javascript/Administration.js'}"></script>
<script src="{sugar_getjspath file='modules/Administration/javascript/Async.js'}"></script>

<div>
	{$mod.LBL_REPAIRXSS_INSTRUCTIONS}
</div>
<br>

<div id="cleanXssMain">
	{$beanDropDown} <div id="repairXssButton" style="display:none;">
		<input type="button" class="button" onclick="SUGAR.Administration.RepairXSS.executeRepair();" value="   {$mod.LBL_EXECUTE}   ">
	</div>
</div>
<br>

<div id="repairXssDisplay" style="display:none;">
	<input size='5' type="text" disabled id="repairXssCount" value="0"> {$mod.LBL_REPAIRXSS_COUNT}
</div>
<br>

<div id="repairXssResults" style="display:none;">
	<input size='5' type="text" disabled id="repairXssResultCount" value="0"> {$mod.LBL_REPAIRXSS_REPAIRED}
</div>