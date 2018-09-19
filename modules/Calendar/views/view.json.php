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

class CalendarViewJson extends SugarView 
{

    public function display()
    {    
        if (!isset($this->view_object_map['jsonData']) || !is_array($this->view_object_map['jsonData'])) {
            $GLOBALS['log']->fatal("JSON data has not been passed from Calendar controller");
            sugar_cleanup(true);
        }
        
        $jsonData = $this->view_object_map['jsonData'];
        
        ob_clean();
        echo json_encode($jsonData);
    }
}

?>
