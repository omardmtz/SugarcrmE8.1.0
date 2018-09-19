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


class LeadsVarDefHandler extends VarDefHandler
{
    /**
     * Overriden to filter legacy pre-5.1 calls and meetings 
     * @see VarDefHandler::get_vardef_array()
     */
    public function get_vardef_array(
        $use_singular = false,
        $remove_dups = false,
        $use_field_name = false,
        $use_field_label = false,
        $visible_only = false,
        $mlink = true
    ) {
        $options_array = parent::get_vardef_array(
            $use_singular,
            $remove_dups,
            $use_field_name,
            $use_field_label,
            $visible_only,
            $mlink
        );
        if ($this->meta_array_name == 'rel_filter')
            unset($options_array['calls_parent'], $options_array['meetings_parent']);
        return $options_array;
    }
}