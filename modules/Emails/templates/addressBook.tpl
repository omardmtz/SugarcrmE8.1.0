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
<div style='padding:5px;'>
    <button class="button" onclick="SUGAR.email2.addressBook.selectContactsDialogue();" id="selectContacts">{sugar_translate label="LBL_ADD_ENTRIES"}</button>
</div>
<div id="contactsFilterDiv" class="addressbookSearch">
<span> {$app_strings.LBL_EMAIL_ADDRESS_BOOK_FILTER}:&nbsp;<input size="10" type="text" class='input' id="contactsFilter" onkeyup="SUGAR.email2.addressBook.filter(this);">
	       <button class="button" onclick="SUGAR.email2.addressBook.clear();"> 
	       {$app_strings.LBL_CLEAR_BUTTON_LABEL} </button>
</span>
</div>
<div id="contacts"></div>
<div class="addressbookSearch">
<span >
    {$app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD_LIST}:&nbsp;<input size="10" class="input" type="text" id="addListField" name="addListField" align="absmiddle">
</span>
 <button class="button" align="absmiddle" onclick="SUGAR.email2.addressBook.addMailingList();" style="padding-bottom: 2px">
        {$app_strings.LBL_EMAIL_ADDRESS_BOOK_ADD} </button>
</div>
<div id="lists"></div>
<div id="contactsMenu"></div>
<div id="listsMenu"></div>
