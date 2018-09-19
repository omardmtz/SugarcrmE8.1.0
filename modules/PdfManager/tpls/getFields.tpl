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
{html_options name="field" id="field" selected=$selectedField values=$fieldsForSelectedModule options=$fieldsForSelectedModule onChange="SUGAR.PdfManager.loadFields(YAHOO.util.Dom.get('base_module').value, this.value)"}{if $fieldsForSubModule} {html_options name="subField" id="subField" values=$fieldsForSubModule options=$fieldsForSubModule}{/if} <input type="button" class="button" name="pdfManagerInsertField" id="pdfManagerInsertField" value="{sugar_translate module="PdfManager" label="LBL_BTN_INSERT"}" onclick="SUGAR.PdfManager.insertField(YAHOO.util.Dom.get('field'), YAHOO.util.Dom.get('subField'));" />