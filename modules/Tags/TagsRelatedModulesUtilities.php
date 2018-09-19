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
 * The TagsRelatedModulesUtilities is a helper class to the Tag Class
 **/
class TagsRelatedModulesUtilities
{
    /**
     * Returns an array of fields for 'taggable' modules
     *
     * @return array
     */
    public static function getRelatedFields()
    {
        $fields = array();
        foreach ($GLOBALS['beanList'] as $module => $bean) {
            // Do not allow tags on the Tags module
            if ($module === "Tags") {
                continue;
            }

            // Enforce the tag relationship to sidecar modules only
            if (!isModuleBWC($module)) {
                $object = BeanFactory::getObjectName($module);
                $relName = strtolower($module) . "_tags";
                $linkField = VardefManager::getLinkFieldForRelationship($module, $object, $relName);
                if ($linkField) {
                    $name = strtolower($module) . '_link';
                    $fields[$name] = array(
                        'name' => $name,
                        'vname' => $module,
                        'type' => 'link',
                        'relationship' => $relName,
                        'source' => 'non-db',
                    );
                }
            }
        }
        return $fields;
    }
}
