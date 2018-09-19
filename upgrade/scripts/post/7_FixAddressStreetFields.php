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

class SugarUpgradeFixAddressStreetFields extends UpgradeScript
{
    public $order = 7250;
    public $type = self::UPGRADE_ALL;

    public function run() {
        if (version_compare($this->from_version, '7.2.1', '>=')) {
            return;
        }
        $this->upgradeFieldsMetaDataTable();
        $this->upgradeVardefsInUndeployedCustomModules();
        $this->upgradeVardefsInDeployedModules();
    }

    /**
     * Find custom address fields added to default modules (ones that show up in the field_meta_data table)
     * and upgrade them
     */
    public function upgradeFieldsMetaDataTable() {
        //Get unupgraded address_street fields (fields whose names follow the pattern %_street_c)
        $query = "SELECT id FROM fields_meta_data WHERE type <> 'text' AND deleted = 0 AND name LIKE '%_street_c'";
        $db = DBManagerFactory::getInstance();
        $result = $db->query($query);
        $updatedStreets = array();
        while ($street = $db->fetchByAssoc($result, false)) {
            //Search for a matching city row to add robustness (in case the user created a field named like %_street_c
            //that isn't actually a street address field)
            $uniqueNameIdx = strpos($street['id'], '_street_c');
            $uniqueName = substr($street['id'], 0, $uniqueNameIdx);
            $cityName = $db->quoted($uniqueName . '_city_c');
            $query = "SELECT id FROM fields_meta_data WHERE deleted = 0 AND id = $cityName";
            $result2 = $db->query($query);
            $city = $db->fetchByAssoc($result2, false);
            if ($city) {
                $updatedStreets[] = $db->quoted($street['id']);
            }
        }

        $updatedStreets = implode(',', $updatedStreets);
        if (!empty($updatedStreets)) {
            $query = "UPDATE fields_meta_data SET type = 'text', ext3 = 'varchar' WHERE id IN ($updatedStreets)";
            $db->query($query);
        }
    }

    /**
     * Find custom address fields added to modules in the modulebuilder that have yet to be deployed
     */
    public function upgradeVardefsInUndeployedCustomModules() {
        foreach (glob('custom/modulebuilder/packages/*/modules/*/vardefs.php') as $file) {
            require $file;
            if (!empty($vardefs['fields'])) {
                //Save opening string so we can put it back later
                $fileString = file_get_contents($file);
                $openingString = substr($fileString, 0, strpos($fileString, '$vardefs'));

                //Find all custom street fields
                foreach($vardefs['fields'] as $fieldName => &$field) {
                    if ($this->validateStreetField($vardefs['fields'], $fieldName)) {
                        //Field is an address street. Proceed to update the street vardef
                        $field['type'] = 'text';
                        $field['dbType'] = 'varchar';
                    }
                }

                //Put the updated contents back into the file
                sugar_file_put_contents_atomic($file,
                    $openingString . "\n" . '$vardefs = ' . var_export($vardefs, true) . ";\n"
                );
            }
        }
    }

    /**
     * Find custom address fields added to modules that modulebuilder created and deployed
     */
    public function upgradeVardefsInDeployedModules() {
        //Created for access to a field's vardef_map
        $tempField = new TemplateField();

        foreach (glob('modules/*/vardefs.php') as $file) {
            //Get module name from file name
            $fileParts = explode('/', $file);
            $module = $fileParts[1];

            $fieldDefs = VardefManager::getFieldDefs($module);
            if (!empty($fieldDefs)) {
                //Set up vardef extension save mechanism
                $bean = BeanFactory::newBean($module);
                if (empty($bean)) {
                    continue;
                }
                $df = new DynamicField($module);
                $df->setup($bean);


                //Find all custom street fields
                foreach ($fieldDefs as $fieldName => $field) {
                    if ($this->validateStreetField($fieldDefs, $fieldName)) {
                        $upgradeField = new stdClass();
                        $upgradeField->type = 'text';
                        $upgradeField->dbType = 'varchar';
                        $upgradeField->name = $fieldName;
                        $upgradeField->vardef_map = $tempField->vardef_map;
                        $upgradeField->vardef_map['dbType'] = 'dbType';
                        $df->saveExtendedAttributes($upgradeField, array());
                    }
                }
            }
        }
    }

    /**
     * Given a list of vardefs, return true if the var specified by $varName is an address street field.
     * $varName is an address street field if its name follows the pattern "*_street" (there must not be anything
     * following "_street") and there is a matching "*_city" (also with nothing trailing) also in the list of vardefs
     *
     * @param $varList list of vardefs
     * @param $varName name of vardef we are validating
     * @return boolean
     */
    public function validateStreetField($varList, $varName) {
        if (($uniqueNameEndIdx = strpos($varName, '_street')) !== false &&
            (substr($varName, -7) === '_street')) {
            //Found a field named like a street would be
            //First make sure it is not already upgraded
            if (isset($varList[$varName]['type']) && $varList[$varName]['type'] === 'text') {
                return false;
            }

            //Next make sure it is a street by checking for the matching city field
            $uniqueName = substr($varName, 0, $uniqueNameEndIdx);
            $cityName = $uniqueName . '_city';
            if (!empty($varList[$cityName])) {
                return true;
            }
        }
        //If we made it here, either the var isn't named like a street, or a matching city was not found
        return false;
    }
}
