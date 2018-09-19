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

<div style="padding:5px" height="100%">
	<div>
		<a class="listViewThLinkS1" href="javascript:SUGAR.routing.ui.addRule();">{$app_strings.LBL_ROUTING_ADD_RULE}</a> <a class="listViewThLinkS1" href="javascript:SUGAR.routing.ui.addRule();">{sugar_getimage alt=$app_strings.LBL_ADD name="plus" ext=".gif" other_attributes='align="absmiddle" border="0" '}</a>
	</div>
	<br />
	<div id="rulesList" style="background:#fff; overflow:auto; margin:5px; padding:2px; border:1px solid #ccc;">{$savedRules}</div>
	<div>
		<i>{$app_strings.LBL_ROUTING_SUB_DESC}</i>
	</div>
</div>
