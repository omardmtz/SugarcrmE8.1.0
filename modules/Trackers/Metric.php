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

class Metric {

    public function __construct($type, $name)
    {
        $this->_name = $name;
        $this->_type = $type;
        $this->_mutable = $name == 'monitor_id' ? false : true;
    }
    
    function type() {
        return $this->_type;	
    }
    
    function name() {
        return $this->_name;
    }
    
    function isMutable() {
        return $this->_mutable;	
    }
    
}
