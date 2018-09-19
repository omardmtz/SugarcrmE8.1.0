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


class SugarFieldHtml extends SugarFieldBase {
   
    function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex){
        $vardef['value'] = $this->getVardefValue($vardef);
        
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('DetailView'));
    }
    
    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex){
    	$vardef['value'] = $this->getVardefValue($vardef);
				
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('DetailView'));
    }
    
	function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
		$vardef['value'] = $this->getVardefValue($vardef);
				
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('DetailView'));    
    }
    
    function getVardefValue($vardef){
        if(empty($vardef['value'])){
            if(!empty($vardef['default']))
                return from_html($vardef['default']);
            elseif(!empty($vardef['default_value']))
                return from_html($vardef['default_value']);
        } else {
            return from_html($vardef['value']);
        }
    }

    /**
     * Normalizes a default value
     *
     * @param mixed $value The value to normalize
     * @return string
     */
    public function normalizeDefaultValue($value) {
        return htmlspecialchars_decode($value, ENT_QUOTES);
    }
}
