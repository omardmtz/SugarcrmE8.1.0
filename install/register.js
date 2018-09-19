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
// $Id: register.js 8223 2005-10-03 23:55:49Z lam $

function submitbutton()
{
   var form = document.mosForm;
   var r = new RegExp("[^0-9A-Za-z]", "i");

   if (form.email1.value != "")
   {
      var myString = form.email1.value;
      var pattern = /(\W)|(_)/g;
      var adate = new Date();
      var ms = adate.getMilliseconds();
      var sec = adate.getSeconds();
      var mins = adate.getMinutes();
      ms = ms.toString();
      sec = sec.toString();
      mins = mins.toString();
      newdate = ms + sec + mins;
   
      var newString = myString.replace(pattern,"");
      newString = newString + newdate;
      //form.username.value = newString;
      //form.password.value = newString;
      //form.password2.value = newString;
   }

   // do field validation
   if (form.name.value == "")
   {
      form.name.focus();
      alert( "Please provide your name" );
      return false;
   }
   else if (form.email1.value == "")
   {
      form.email1.focus();
      alert( "Please provide your email address" );
      return false;
   }
   else
   {
      form.submit();
   }

   document.appform.submit();
   window.focus();
}
