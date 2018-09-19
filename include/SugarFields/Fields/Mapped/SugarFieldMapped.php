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
 * Class SugarFieldMapped
 * For use on fields such as tag_lower, which need to be on the database,
 * but whose values will always be dependent on another field
 *
 * In the vardef for a mapped field, two additional properties besides 'type' => 'mapped'
 * must be defined:
 *  'parentField' => The field type that this field is based on
 *  'mapFunction' => A function that is defined on the parentField's type that performs the mapping
 */
class SugarFieldMapped extends SugarFieldBase
{
    /**
     * Override of parent apiSave to force the custom save to be run from API
     * @param SugarBean $bean
     * @param array     $params
     * @param string    $field
     * @param array     $properties
     */
    public function apiSave(SugarBean $bean, array $params, $field, $properties) {
        // Mapped fields needs to have something to map from.
        if (empty($properties['mapFunction']) || empty($properties['parentField'])) {
            return;
        }

        // First make sure the parent field exists on this bean
        if (isset($bean->field_defs[$properties['parentField']])) {
            $sfh = new SugarFieldHandler();
            $sf = $sfh->getSugarField($bean->field_defs[$properties['parentField']]['type']);
            if (method_exists($sf, $properties['mapFunction'])) {
                $bean->{$field} = $sf->{$properties['mapFunction']}($bean->{$properties['parentField']});
            }
        }
    }
}
