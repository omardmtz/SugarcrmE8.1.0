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
// $Id: dbConfig.js 6337 2005-07-22 00:47:29Z bob $

function togglePasswordRetypeRequired() {
   var theForm = document.forms[0];
   var elem = document.getElementById('password_retype_required');

   if( theForm.setup_db_create_sugarsales_user.checked ){
      elem.style.display = '';
      // theForm.setup_db_sugarsales_user.focus();
      theForm.setup_db_username_is_privileged.checked = "";
      theForm.setup_db_username_is_privileged.disabled = "disabled";
      toggleUsernameIsPrivileged();
   }
   else {
      elem.style.display = 'none';
      theForm.setup_db_username_is_privileged.disabled = "";
   }
}

function toggleDropTables(){
   var theForm = document.forms[0];

   if( theForm.setup_db_create_database.checked ){
      theForm.setup_db_drop_tables.checked = '';
      theForm.setup_db_drop_tables.disabled = "disabled";
   }
   else {
      theForm.setup_db_drop_tables.disabled = '';
   }
}

function toggleUsernameIsPrivileged(){
   var theForm = document.forms[0];
   var elem = document.getElementById('privileged_user_info');

   if( theForm.setup_db_username_is_privileged.checked ){
      elem.style.display = 'none';
   }
   else {
      elem.style.display = '';
   }
}
