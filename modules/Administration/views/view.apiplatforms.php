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


class AdministrationViewApiplatforms extends SugarView
{
    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
        if (!is_admin($GLOBALS['current_user'])) {
            sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
        }
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        $configurator = new Configurator();
        $platforms = [];
        $file_loc = "custom/Extension/application/Ext/Platforms/custom_api_platforms.php";
        if (SugarAutoLoader::fileExists($file_loc)) {
            require $file_loc;
        }

        $allPlatforms = MetaDataManager::getPlatformList();
        $api_platforms = array_map(function($platform) use ($platforms){
            return [
                'name' => $platform,
                'custom' => in_array($platform, $platforms)
            ];
        }, $allPlatforms);
        $this->ss->assign('api_platforms', json_encode($api_platforms));
        $this->ss->assign('mod', $GLOBALS['mod_strings']);
        $this->ss->assign('APP', $GLOBALS['app_strings']);
        $this->ss->assign('deleteImage',
            SugarThemeRegistry::current()->getImage('delete_inline', 'style="border:1px sold red"', null, null, '.gif', translate('LBL_MB_DELETE'))
        );
        $this->ss->assign('helpImage',
            SugarThemeRegistry::current()->getImage(
                'helpInline',
                'onclick="showApiPlatformHelp(this)"',
                null,
                null,
                '.png',
                translate('LBL_HELP')
            )
        );

        echo getClassicModuleTitle(
            "Administration",
            [
                "<a href='index.php?module=Administration&action=index'>" . translate('LBL_MODULE_NAME') . "</a>",
                translate('LBL_CONFIGURE_CUSTOM_API_PLATFORMS'),
            ],
            false
        );

        echo $this->ss->fetch('modules/Administration/templates/ApiPlatforms.tpl');
    }
}
