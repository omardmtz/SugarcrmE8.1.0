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

<h3>{$MOD.LBL_REPAIR_DATABASE_DIFFERENCES}</h3>
<p>{$MOD.LBL_REPAIR_DATABASE_TEXT}</p>
<form name="RepairDatabaseForm" method="post">
{sugar_csrf_form_token}
<input type="hidden" name="module" value="Administration"/>
<input type="hidden" name="action" value="repairDatabase"/>
<input type="hidden" name="raction" value="execute"/>
<textarea name="sql" rows="24" cols="150" id="repairsql">{$qry_str}</textarea>
<br/>
<input type="button" class="button" value="{$MOD.LBL_REPAIR_DATABASE_EXECUTE}" onClick="document.RepairDatabaseForm.raction.value='execute'; document.RepairDatabaseForm.submit();"/>
<input type="button" class="button" value="{$MOD.LBL_REPAIR_DATABASE_EXPORT}" onClick="document.RepairDatabaseForm.raction.value='export'; document.RepairDatabaseForm.submit();"/>