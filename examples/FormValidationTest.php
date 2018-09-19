<?php
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
?>
<script type="text/javascript" src="../include/javascript/sugar_3.js"></script>

<form name='test'>
<table>
<tr><td>*Name:</td><td><input type='text' name='name'></td></tr>
<tr><td>*Email:</td><td><input type='text' name='email'></td></tr>
<tr><td>Address:</td><td><input type='text' name='add'></td></tr>
<tr><td>Time:</td><td><input type='text' name='time'></td></tr>
<tr><td>Date:</td><td><input type='text' name='date'></td></tr>
<tr><td>Amount:</td><td>$<input type='text' name='amount'></td></tr>

</table>
<input type='button' name='test' value='Test' onclick="check_form('test');">
</form>
<script>
addToValidate('test','email', 'email', true, 'EMAIL');
addToValidate('test','name', '', true, 'NAME');
addToValidate('test','add', '', false, 'ADDRESS');
addToValidate('test','time', 'time', false, 'TIME');
addToValidate('test','date', 'date', true, 'DATE');
addToValidate('test','amount', 'numeric', false, 'AMOUNT');
</script>
