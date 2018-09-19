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
 * Fix Import Maps created in 6.x
 */
class SugarUpgradeRepairImportMaps extends UpgradeScript
{
    public $order = 7900;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if (version_compare($this->from_version, '7.0', '>=')) {
            return;
        }

        $query = "SELECT im.id as id FROM import_maps im WHERE im.deleted != 1";
        $importMaps = $this->db->query($query);

        while ($row = $this->db->fetchByAssoc($importMaps)) {
            $this->repairImportMap($row['id']);
        }
    }

    private function repairImportMap($id)
    {
        $importMap = BeanFactory::getBean('Import_1', $id);

        // Get these back properly
        $importMap->delimiter = html_entity_decode($importMap->delimiter);
        $importMap->enclosure = html_entity_decode($importMap->enclosure);
        $importMap->content = html_entity_decode($importMap->content);
        $importMap->default_values = html_entity_decode($importMap->default_values);

        $content = $this->getMapping($importMap->content);
        $defaultValues = $this->getMapping($importMap->default_values);
        // Set the content/default_values with the new code
        $importMap->setMapping($content);
        $importMap->setDefaultValues($defaultValues);

        $importMap->save(
            $importMap->assigned_user_id,
            $importMap->name,
            $importMap->module,
            $importMap->source,
            $importMap->has_header,
            $importMap->delimiter,
            $importMap->enclosure
        );
    }

    /**
     * Get the mapping array from the old 6.x format
     *
     * @param $content - Content as plain text
     * @return array - Mapping array key->value
     */
    private function getMapping($content)
    {
        $mapping = array();
        if (!empty($content)) {
            $pairs = explode('&', $content);
            foreach ($pairs as $pair) {
                list($name, $value) = explode('=', $pair);
                $mapping[trim($name)] = $value;
            }
        }
        return $mapping;
    }
}
