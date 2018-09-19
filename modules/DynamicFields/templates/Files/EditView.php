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
// adding custom fields:
if(isset($focus->custom_fields)){
/*
$test is set to focus to increment the reference count 
since it appears that the reference count was off by 1 
*/
$test =& $focus;
$focus->custom_fields->bean =& $focus;
$focus->custom_fields->populateXTPL($xtpl, 'edit');
}

?>
