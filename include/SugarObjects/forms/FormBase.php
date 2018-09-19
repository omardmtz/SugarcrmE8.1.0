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

/**
 * FormBase.php
 *
 * @author Collin Lee
 *
 * This is an abstract class to provide common functionality across the form base code used in the application.
 *
 * @see LeadFormBase.php, ContactFormBase.php, MeetingFormBase, CallFormBase.php
 */

abstract class FormBase {


/**
 * isSaveFromDCMenu
 *
 * This is a function to help assist in determining if a save operation has been performed from the DCMenu (the shortcut bar
 * up top available for most themes).
 *
 * @return bool Boolean value indicating whether or not the save operation was triggered from DCMenu
 */
protected function isSaveFromDCMenu()
{
    return (isset($_POST['from_dcmenu']) && $_POST['from_dcmenu']);
}


/**
 * isEmptyReturnModuleAndAction
 *
 * This is a function to help assist in determining if a save operation has been performed without a return module and action specified.
 * This will likely be the case where we use AJAX to change the state of a record, but wish to keep the user remaining on the same view.
 * For example, this is true when closing Calls and Meetings from dashlets or from from subpanels.
 *
 * @return bool Boolean value indicating whether or not a return module and return action are specified in request
 */
protected function isEmptyReturnModuleAndAction()
{
    return empty($_POST['return_module']) && empty($_POST['return_action']);
}

/**
 * Gets related module name from a rel link
 * @param SugarBean $focus
 * @return string
 */
protected function getRelatedModuleName($focus)
{
    $relate_to = null;
    if (!empty($_REQUEST['relate_to'])) {
        $rel_link = $_REQUEST['relate_to'];
        if (!$focus->load_relationship($rel_link)) {
            //Try to find the link in this bean based on the relationship
            foreach ($focus->field_defs as $key => $def) {
                if (isset($def['type']) && $def['type'] == 'link' && isset($def['relationship']) && $def['relationship'] == $rel_link) {
                    $rel_link = $key;
                    if ($focus->load_relationship($rel_link)) {
                        break;
                    }
                }
            }
        }
        if ($focus->$rel_link) {
            $relate_to = $focus->$rel_link->getRelatedModuleName();
        }
    }
    return $relate_to;
}

}
 
