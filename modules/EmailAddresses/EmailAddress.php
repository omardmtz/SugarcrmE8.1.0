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
 * Stub class, exists only to allow Link class easily use the SugarEmailAddress class
 */
class EmailAddress extends SugarEmailAddress
{
	var $disable_row_level_security = true;

    /**
     * Called by DuplicateCheck api to remove email_addr_bean_rel records created in the process
     * @param string $id
     * @param string $module
     */
    public function deleteLinks($id, $module)
    {
        // Need to correct this to handle the Employee/User split
        $module = $this->getCorrectedModule($module);
        $query = "update email_addr_bean_rel set deleted = 1 WHERE bean_id = '".$this->db->quote($id)."' AND bean_module = '".$this->db->quote($module)."'";
        $this->db->query($query);
    }
}
