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

require_once 'include/utils.php';

use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;
use Sugarcrm\Sugarcrm\SearchEngine\AdminSettings;
use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Config;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Parser\XmlIdpMetadataParser;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdministrationController extends SugarController
{
    public function action_savetabs()
    {


        global $current_user, $app_strings, $modInvisList;

        if (!is_admin($current_user)) {
            sugar_die($app_strings['ERR_NOT_ADMIN']);
        }

        // handle the tabs listing
        $enabled_tabs = json_decode(html_entity_decode(InputValidation::getService()->getValidInputRequest('enabled_tabs'), ENT_QUOTES));
        // Add Home back in so that it always appears first in Sugar 7
        array_unshift($enabled_tabs, 'Home');
        $tabs = new TabController();
        $tabs->set_system_tabs($enabled_tabs);
        $tabs->setPortalTabs(array_values(array_intersect($enabled_tabs, $tabs->getAllPortalTabs())));
        $tabs->set_users_can_edit(isset($_REQUEST['user_edit_tabs']) && $_REQUEST['user_edit_tabs'] == 1);

        // handle the subpanels
        if (isset($_REQUEST['disabled_tabs'])) {
            $disabledTabs = json_decode(html_entity_decode(InputValidation::getService()->getValidInputRequest('disabled_tabs'), ENT_QUOTES));
            $disabledTabsKeyArray = TabController::get_key_array($disabledTabs);
            //Never show Project subpanels if Project module is hidden
            if (!in_array('project', $disabledTabsKeyArray) && in_array('Project', $modInvisList)) {
                $disabledTabsKeyArray[] = 'project';
            }
            // if RLI is hidden, always hide the RLI subpanel.
            if (!in_array('revenuelineitems', $disabledTabsKeyArray) && in_array('RevenueLineItems', $modInvisList)) {
                $disabledTabsKeyArray[] = 'revenuelineitems';
            }
            SubPanelDefinitions::set_hidden_subpanels($disabledTabsKeyArray);
        }
        
        // Only rebuild the relevent metadata sections.
        MetaDataManager::refreshSectionCache(MetaDataManager::MM_MODULESINFO, array('base'));
        MetaDataManager::refreshSectionCache(MetaDataManager::MM_HIDDENSUBPANELS, array('base'));

        if (!headers_sent()) {
            header("Location: index.php?module=Administration&action=ConfigureTabs");
        }
    }

    public function action_savelanguages()
    {
        global $sugar_config;
        $disabled_langs = json_decode(html_entity_decode(InputValidation::getService()->getValidInputRequest('disabled_langs'), ENT_QUOTES));
        $enabled_langs = json_decode(html_entity_decode(InputValidation::getService()->getValidInputRequest('enabled_langs'), ENT_QUOTES));

        if (count($sugar_config['languages']) === count($disabled_langs)) {
            sugar_die(translate('LBL_CAN_NOT_DISABLE_ALL_LANG'));
        } else {
            $cfg = $this->getConfigurator();
            if (in_array($sugar_config['default_language'], $disabled_langs)) {
                reset($enabled_langs);
                $cfg->config['default_language'] = current($enabled_langs);
            }
            if (in_array($GLOBALS['current_user']->preferred_language, $disabled_langs)) {
                $GLOBALS['current_user']->preferred_language = current($enabled_langs);
                $GLOBALS['current_user']->save();
            }
            $cfg->config['disabled_languages'] = join(',', $disabled_langs);
            // TODO: find way to enforce order
            $cfg->handleOverride();

            // Clear the metadata cache so changes to languages are picked up right away
            MetaDataManager::refreshLanguagesCache($enabled_langs);
            $repair = new RepairAndClear();
            $repair->clearLanguageCache();
        }

        //Call Ping API to refresh the language list.
        die("
            <script>
            var app = window.parent.SUGAR.App;
            app.api.call('read', app.api.buildURL('ping'));
            app.router.navigate('#bwc/index.php?module=Administration&action=Languages', {trigger:true, replace:true});
            </script>"
        );
    }

    public function action_updatewirelessenabledmodules()
    {
        require_once('modules/Administration/Forms.php');

        global $app_strings, $current_user, $moduleList;

        if (!is_admin($current_user)) sugar_die($app_strings['ERR_NOT_ADMIN']);

        $cfg = $this->getConfigurator();
        $cfg->saveConfig();

        if (isset($_REQUEST['enabled_modules']) && !empty ($_REQUEST['enabled_modules']))
        {
            $updated_enabled_modules = array();
            $wireless_module_registry = array();

            $file = 'include/MVC/Controller/wireless_module_registry.php';

            if (file_exists($file)) {
                require $file;
            }

            foreach (explode (',', $_REQUEST['enabled_modules']) as $moduleName)
            {
                $moduleDef = array_key_exists($moduleName, $wireless_module_registry) ? $wireless_module_registry[$moduleName] : array();
                $updated_enabled_modules [ $moduleName ] = $moduleDef;
            }

            $filename = create_custom_directory('include/MVC/Controller/wireless_module_registry.php');

            mkdir_recursive ( dirname ( $filename ) ) ;
            write_array_to_file ( 'wireless_module_registry', $updated_enabled_modules, $filename );
            foreach($moduleList as $mod){
                sugar_cache_clear("CONTROLLER_wireless_module_registry_$mod");
            }
            //Users doesn't appear in the normal module list, but its value is cached on login.
            sugar_cache_clear("CONTROLLER_wireless_module_registry_Users");
            sugar_cache_put('wireless_module_registry_keys', array_keys($updated_enabled_modules));
            sugar_cache_reset();

            // Bug 59121 - Clear the metadata cache for the mobile platform
            MetaDataManager::refreshCache(array('mobile'));
        }

        echo "true";
    }

    /**
     * Save FTS configuration and schedule FTS (re)index
     */
    public function action_ScheduleFTSIndex()
    {
        list($type, $config) = $this->getFtsSettingsFromRequest($_REQUEST);

        // Save current configuration first
        $this->saveFtsConfig($type, $config);

        $clearData = !empty($_REQUEST['clearData']) ? true : false;
        $modules = !empty($_REQUEST['modules']) ? explode(",", $_REQUEST['modules']) : array();

        try {
            $result = SearchEngine::getInstance()->scheduleIndexing($modules, $clearData);
        } catch (Exception $e) {
            $result = false;
        }

        // TODO: add visual feedback in UI if this returns false
        echo json_encode(array('success' => $result));

        if (!empty($e)) {
            throw $e;
        }
    }

    /**
     * Check FTS connect connection
     */
    public function action_checkFTSConnection()
    {
        list($type, $config) = $this->getFtsSettingsFromRequest($_REQUEST);
        $valid = $this->verifyFtsConnectivity($type, $this->mergeFtsConfig($type, $config));

        // Set label
        if ($valid) {
            $status = $GLOBALS['mod_strings']['LBL_FTS_CONN_SUCCESS'];
        } else {
            $status = $GLOBALS['mod_strings']['LBL_FTS_CONN_UNKNOWN_FAILURE'];
        }

        echo json_encode(array('valid' => $valid, 'status' => $status));
        sugar_cleanup(true);
    }

    /**
     * Get the list of modules from the request parameters.
     * @param $modules string the
     * @return array
     */
    public function getModuleList($modules)
    {
        $list = array();
        if (isset($modules)) {
            return explode(',', $modules);
        }
        return $list;
    }
    /**
     * action_saveglobalsearchsettings
     *
     * This method handles saving the selected modules to display in the Global Search Settings.
     *
     */
    public function action_saveglobalsearchsettings()
    {
        global $current_user, $app_strings;

        if (!is_admin($current_user)) {
            sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
        }

        // Check connectivity before saving
        list($type, $config) = $this->getFtsSettingsFromRequest($_REQUEST);
        $valid = $this->verifyFtsConnectivity($type, $this->mergeFtsConfig($type, $config));

        // Save configuration
        $this->saveFtsConfig($type, $config, $valid);

        // Update the module vardefs to enable/disable fts
        $enabledModules = $this->getModuleList($_REQUEST['enabled_modules']);
        $disabledModules = $this->getModuleList($_REQUEST['disabled_modules']);
        $ftsAdmin = new AdminSettings();
        $ftsAdmin->saveFTSModuleListSettings($enabledModules, $disabledModules);

        // Refresh the server info & module list sections of the metadata
        MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_SERVERINFO, MetaDataManager::MM_MODULES));

        if (!$valid) {
            echo $GLOBALS['mod_strings']['LBL_FTS_CONNECTION_INVALID'];
        } else {
            echo "true";
        }
    }

    /**
     * action_saveunifiedsearchsettings
     *
     * This method handles saving the selected modules to display in the Unified Search Settings.
     * It instantiates an instance of UnifiedSearchAdvanced and then calls the saveGlobalSearchSettings
     * method.
     *
     */
    public function action_saveunifiedsearchsettings()
    {
        global $current_user, $app_strings;

        if (!is_admin($current_user)) {
            sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
        }

        try {
            $unifiedSearchAdvanced = new UnifiedSearchAdvanced();
            $unifiedSearchAdvanced->saveGlobalSearchSettings();

            // Refresh the server info & module list sections of the metadata
            MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_SERVERINFO, MetaDataManager::MM_MODULES));

            echo "true";
        } catch (Exception $ex) {
            echo "false";
        }
    }

    /*
     * action_callRebuildSprites
     *
     * This method is responsible for actually running the SugarSpriteBuilder class to rebuild the sprites.
     * It is called from the ajax request issued by RebuildSprites.php.
     */
    public function action_callRebuildSprites()
    {
        global $current_user;
        $this->view = 'ajax';
        if(function_exists('imagecreatetruecolor'))
        {
            if(is_admin($current_user))
            {
                require_once('modules/UpgradeWizard/uw_utils.php');
                rebuildSprites(false);
            }
        } else {
            echo $mod_strings['LBL_SPRITES_NOT_SUPPORTED'];
            $GLOBALS['log']->error($mod_strings['LBL_SPRITES_NOT_SUPPORTED']);
        }
    }

    /**
     * Save FTS configuration
     * @param string $type
     * @param array $config
     * @return array
     */
    protected function saveFtsConfig($type, array $config)
    {
        $config = $this->mergeFtsConfig($type, $config);

        $cfg = $this->getConfigurator();
        $cfg->config['full_text_engine'] = '';
        $cfg->saveConfig();
        $cfg->config['full_text_engine'] = array($type => $config);
        $cfg->handleOverride();

        SugarConfig::getInstance()->clearCache();
        return $config;
    }

    /**
     * Merge current FTS config with the new passed parameters:
     *
     * We want to merge the current $sugar_config settings with those passed in
     * to be able to add additional parameters which are currently not supported
     * in the UI (i.e. additional curl settings for elastic search for auth)
     *
     * @param array $config
     * @return array
     */
    protected function mergeFtsConfig($type, $newConfig)
    {
        $currentConfig = SugarConfig::getInstance()->get("full_text_engine.{$type}", array());
        return array_merge($currentConfig, $newConfig);
    }

    /**
     *
     * @param array $request
     * @return array
     */
    protected function getFtsSettingsFromRequest(array $request)
    {
        $type = empty($request['type']) ? '' : $request['type'];
        $config = array('host' => '', 'port' => '');
        foreach (array_keys($config) as $key) {
            if (!empty($request[$key])) {
                $config[$key] = $request[$key];
            }
        }
        return array($type, $config);
    }

    /**
     * Verify FTS connectivity
     * @param string $type
     * @param array $config
     * @return boolean
     */
    protected function verifyFtsConnectivity($type, array $config)
    {
        $engine = SearchEngine::newEngine($type, $this->mergeFtsConfig($type, $config));
        return $engine->isAvailable(true);
    }

    /**
     * Map package ACL roles to the instance's ones
     */
    public function action_UpgradeWizard_map_roles()
    {
        $this->view = 'maproles';
    }

    /**
     * send SAML metadata
     */
    public function action_exportMetaDataFile()
    {
        $settings = $this->getSamlSettings();
        $metadata = [];
        try {
            $metadata = $settings->getSPMetadata();
            $errors = $settings->validateMetadata($metadata);
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
        }

        if (!empty($errors)) {
            $this->addErrorLogMessage($errors);
            $response = new RedirectResponse('index.php?module=Administration&action=PasswordManager');
        } else {
            $response = new Response($metadata);
            $disposition = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'metadata.xml'
            );
            $response->headers->set('Content-Disposition', $disposition);
        }
        $response->send();
        $this->terminate();
    }

    /**
     * parse import saml xml file
     */
    public function action_parseImportSamlXmlFile()
    {
        $response = new JsonResponse([
            'error' => $this->translateModuleError('WRONG_IMPORT_FILE_NOT_FOUND_ERROR'),
        ], Response::HTTP_BAD_REQUEST);

        if ($file = $this->getUploadedMetadataFile()) {
            $errors = [];
            $parseResult = $this->getParsedIdPMetadata((string) $file);

            if (!$parseResult) {
                $errors[] = $this->translateModuleError('WRONG_IMPORT_METADATA_INVALID_SOURCE_ERROR');
            }
            if (empty($parseResult['idp']['entityId'])) {
                $errors[] = $this->translateModuleError('WRONG_IMPORT_XML_FILE_NO_MAIN_SECTION_ERROR');
            }
            if (empty($parseResult['idp']['singleSignOnService'])) {
                $errors[] = $this->translateModuleError('WRONG_IMPORT_XML_FILE_NO_IDP_SECTION_ERROR');
            }

            if (!$errors) {
                $x509cert = '';
                // in case of multiple certs we need just the signing one
                if (!empty($parseResult['idp']['x509certMulti']['signing'][0])) {
                    $x509cert = $parseResult['idp']['x509certMulti']['signing'][0];
                } elseif (!empty($parseResult['idp']['x509cert'])) {
                    $x509cert = $parseResult['idp']['x509cert'];
                }
                $response->setStatusCode(Response::HTTP_OK);
                $response->setData([
                    'SAML_loginurl' => !empty($parseResult['idp']['singleSignOnService']['url']) ?
                        $parseResult['idp']['singleSignOnService']['url'] : '',
                    'SAML_login_binding' => !empty($parseResult['idp']['singleSignOnService']['binding']) ?
                        $parseResult['idp']['singleSignOnService']['binding'] : '',
                    'SAML_SLO' => !empty($parseResult['idp']['singleLogoutService']['url']) ?
                        $parseResult['idp']['singleLogoutService']['url'] : '',
                    'SAML_SLO_binding' => !empty($parseResult['idp']['singleLogoutService']['binding']) ?
                        $parseResult['idp']['singleLogoutService']['binding'] : '',
                    'SAML_idp_entityId' => !empty($parseResult['idp']['entityId']) ?
                        $parseResult['idp']['entityId'] : '',
                    'SAML_X509Cert' => $x509cert,
                ]);
            } else {
                $response->setData(['error' => implode(', ', $errors)]);
            }
        }

        $response->send();
        $this->terminate();
    }


    /**
     * Updates the list of configured custom api platforms.
     */
    public function action_saveApiPlatforms()
    {
        global $current_user;

        if (!is_admin($current_user)) {
            sugar_die($app_strings['ERR_NOT_ADMIN']);
        }

        $api_platforms = json_decode(
            html_entity_decode(
                InputValidation::getService()->getValidInputRequest('custom_api_platforms'),
                ENT_QUOTES
            )
        );

        $file_loc = "custom/Extension/application/Ext/Platforms/custom_api_platforms.php";

        $out = "<?php\n // created: " . date('Y-m-d H:i:s') . "\n";
        foreach ($api_platforms as $platform) {
            $out .= '$platforms[] = ' . var_export($platform, true) . ";\n";
        }

        mkdir_recursive(dirname($file_loc));
        sugar_file_put_contents_atomic($file_loc, $out);

        $repairAndClear = new RepairAndClear();
        $repairAndClear->rebuildExtensions();
    }

    /**
     * @return UploadedFile|null
     */
    protected function getUploadedMetadataFile()
    {
        $files = new FileBag($_FILES);
        if ($files->has('import_metadata_file') && $files->get('import_metadata_file')->isValid()) {
            return $files->get('import_metadata_file');
        }
        return null;
    }

    /**
     * Wrapper for getting parsed contents of IdP Metadata from file.
     *
     * @param string $file
     * @return array
     */
    protected function getParsedIdPMetadata($file)
    {
        return \OneLogin_Saml2_IdPMetadataParser::parseFileXML($file);
    }

    /**
     * stop sugar workflow and send response
     */
    protected function terminate()
    {
        exit();
    }

    /**
     * Translate error
     * @param $key
     * @return string
     */
    protected function translateModuleError($key)
    {
        return translate($key, 'Administration');
    }

    /**
     * add error log message
     * @param array $errors
     */
    protected function addErrorLogMessage(array $errors)
    {
        global $log;
        $log->error('Export SAML metadata errors: ' . implode(', ', $errors));
    }

    /**
     * create OneLogin_Saml2_Settings with predefined config
     * @return \OneLogin_Saml2_Settings
     */
    protected function getSamlSettings()
    {
        $config = (new Config(\SugarConfig::getInstance()))->getSAMLConfig();
        // Need to fill up some IdP settings with dummy values to allow export of SP metadata when SAML isn't set up.
        if (empty($config['idp']['entityId'])) {
            $config['idp']['entityId'] = $config['sp']['entityId'];
        }
        if (empty($config['idp']['singleLogoutService']['url'])) {
            $config['idp']['singleSignOnService']['url'] = $config['sp']['assertionConsumerService']['url'];
        }
        // Unset security for default SAML settings
        if (empty($config['idp']['x509cert'])) {
            unset($config['security']);
        }

        return new \OneLogin_Saml2_Settings($config);
    }

    /**
     * Factory method to mock Configurator
     *
     * @return Configurator
     */
    protected function getConfigurator()
    {
        return new Configurator();
    }
}
