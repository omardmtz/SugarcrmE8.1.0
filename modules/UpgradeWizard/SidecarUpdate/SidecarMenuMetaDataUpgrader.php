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

require_once 'modules/UpgradeWizard/SidecarUpdate/SidecarAbstractMetaDataUpgrader.php';

class SidecarMenuMetaDataUpgrader extends SidecarAbstractMetaDataUpgrader
{
    protected $isExt = false;

    /**
     * name of the menu var
     * @var string
     */
    protected $menuName;

    public function setLegacyViewdefs()
    {
        global $current_language;

        $GLOBALS['mod_strings'] = return_module_language($current_language, $this->module);
        SugarACL::setACL($this->module, array(new SidecarMenuMetaDataUpgraderACL()));
        $module_menu = null;
        include $this->fullpath;

        if($this->basename === 'globalControlLinks'){
            if(isset($global_control_links)){
                $module_menu = $global_control_links;
                $this->deleteOld = false;
            }
        }

        SugarACL::resetACLs($this->module);
        $this->legacyViewdefs = $module_menu;
    }

    public function convertLegacyViewDefsToSidecar()
    {
        if(empty($this->legacyViewdefs)) {
            return true;
        }
        // Upgrading globalcontrollinks to profileaction metadata
        if($this->basename === 'globalControlLinks'){
            $newMenu = $this->metaDataConverter->fromLegacyProfileActions($this->legacyViewdefs);
        }else{
            $this->isExt = (substr($this->fullpath, 0, 16) == 'custom/Extension');
            $newMenu = $this->metaDataConverter->fromLegacyMenu($this->module, $this->legacyViewdefs, $this->isExt);
        }
        if(empty($newMenu['data'])) {
            return true;
        }
        $this->sidecarViewdefs = $newMenu['data'];
        $this->menuName = $newMenu['name'];
    }

    public function handleSave()
    {
        if(empty($this->sidecarViewdefs)) {
            return true;
        }
        if($this->isExt) {
            $newExtLocation = "custom/Extension/modules/{$this->module}/Ext/clients/base/menus/header/";
            if (!is_dir($newExtLocation)) {
                sugar_mkdir($newExtLocation, null, true);
            }

            $content = "<?php \n";
            foreach($this->sidecarViewdefs as $menuItem) {
                $content .= "\${$this->menuName}[] = ".var_export($menuItem, true).";\n";
            }
            return sugar_file_put_contents($newExtLocation . "/" . $this->filename, $content);
        } elseif($this->basename === 'globalControlLinks'){
            return $this->handleSaveArray($this->menuName, "custom/clients/base/views/profileactions/profileactions.php");
        } else {
            return $this->handleSaveArray($this->menuName, "custom/modules/{$this->module}/clients/base/menus/header/header.php");
        }
    }
}

/**
 * This is a mock ACL so that Menu files that have ACLs won't do weird things
 */
class SidecarMenuMetaDataUpgraderACL extends SugarACLStrategy
{
    public function checkAccess($module, $action, $context)
    {
        return true;
    }
}
