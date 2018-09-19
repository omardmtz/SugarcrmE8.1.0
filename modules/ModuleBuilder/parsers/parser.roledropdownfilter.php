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
 * Parser for role based dropdowns. Stores dropdown filter data.
 */
class ParserRoleDropDownFilter extends ModuleBuilderParser
{
    /**
     * Saves $data to the $name dropdown for the $role name
     *
     * @param $fieldName
     * @param $role
     * @param $data
     * @return boolean
     */
    public function handleSave($fieldName = null, $role = null, $data = null)
    {
        $path = $this->getFilePath($fieldName, $role);
        $dir = dirname($path);
        if (!SugarAutoLoader::ensureDir($dir)) {
            $GLOBALS['log']->error("ParserRoleDropDownFilter :: Cannot create directory $dir");
            return false;
        }
        $result = write_array_to_file(
            "role_dropdown_filters['{$fieldName}']",
            $this->convertFormData($data),
            $path
        );
        if ($result) {
            $this->rebuildExtension($role);
            MetaDataManager::refreshSectionCache(MetaDataManager::MM_EDITDDFILTERS, array(), array(
                'role' => $role,
            ));
        }
        return $result;
    }

    /**
     * Returns a file path to the file that stores options for a given role and a dropdown name
     *
     * @param string $fieldName Dropdown field name
     * @param string $role Role ID
     * @return string
     */
    protected function getFilePath($fieldName, $role)
    {
        return 'custom/Extension/application/Ext/DropdownFilters/roles/' . $role . '/' . $fieldName . '.php';
    }

    /**
     * Converts form data to internal representation
     *
     * @param array $data Form data
     * @return array Internal representation
     */
    protected function convertFormData($data)
    {
        $converted = array();
        $blank = translate('LBL_BLANK', 'ModuleBuilder');
        foreach ($data as $key => $item) {
            if ($key === $blank) {
                $key = '';
            }

            $converted[$key] = (bool) $item;
        }

        return $converted;
    }

    protected function rebuildExtension($role)
    {
        SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
        $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
        $moduleInstaller = new $moduleInstallerClass();
        $moduleInstaller->silent = true;
        $moduleInstaller->rebuild_role_dropdown_filters($role);
    }
}
