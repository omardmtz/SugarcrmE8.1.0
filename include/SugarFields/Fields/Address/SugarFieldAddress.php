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
 * SugarFieldAddress.php
 * SugarFieldAddress translates and displays fields from a vardef definition into different formats
 * for EditViews and DetailViews.  A sample invocation from a Meta-Data file is as follows:
 * 
 *  array (
 * 	   'name' => 'primary_address_street',
 *	   'type' => 'address',
 *	   'displayParams'=>array('key'=>'primary'),
 *  ),
 * 
 * Where name is set to the field for ACL verification, type is set to 'address'
 * to override the default field type and the displayParams array includes the key
 * for the address field.  Assumptions are made that the vardefs.php contains address
 * elements with the corresponding names. There is the optional displayParam index
 * 'copy' that accepts as value the key of the other address fields.  In our
 * example we may enable copying from the primary address fields with:
 * 
 *  array (
 * 	   'name' => 'altaddress_street',
 *	   'type' => 'address',
 *	   'displayParams'=>array('key'=>'alt', 'copy'=>'primary'),
 *  ),
 * 
 */
class SugarFieldAddress extends SugarFieldBase {

    function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        global $app_strings;
        if(!isset($displayParams['key'])) {
           $GLOBALS['log']->debug($app_strings['ERR_ADDRESS_KEY_NOT_SPECIFIED']);	
           $this->ss->trigger_error($app_strings['ERR_ADDRESS_KEY_NOT_SPECIFIED']);
           return;
        }
        
        //Allow for overrides.  You can specify a Smarty template file location in the language file.
        if(isset($app_strings['SMARTY_ADDRESS_DETAILVIEW'])) {
           $tplCode = $app_strings['SMARTY_ADDRESS_DETAILVIEW'];
           return $this->fetch($tplCode);	
        }
        
        return $this->fetch($this->findTemplate('DetailView'));
    }
    
    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);        
        global $app_strings;
        if(!isset($displayParams['key'])) {
           $GLOBALS['log']->debug($app_strings['ERR_ADDRESS_KEY_NOT_SPECIFIED']);	
           $this->ss->trigger_error($app_strings['ERR_ADDRESS_KEY_NOT_SPECIFIED']);
           return;
        }
        
        //Allow for overrides.  You can specify a Smarty template file location in the language file.
        if(isset($app_strings['SMARTY_ADDRESS_EDITVIEW'])) {
           $tplCode = $app_strings['SMARTY_ADDRESS_EDITVIEW'];
           return $this->fetch($tplCode);	
        }       

        return $this->fetch($this->findTemplate('EditView'));      
    }
    
}
?>
