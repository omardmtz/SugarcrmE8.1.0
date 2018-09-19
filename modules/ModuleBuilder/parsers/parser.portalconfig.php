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

use Sugarcrm\Sugarcrm\Security\Crypto\CSPRNG;


class ParserModifyPortalConfig extends ModuleBuilderParser
{

    /**
     * Sets up Portal.
     *
     * @param array $settings (optional) the array of portal settings.
     */
    public function setUpPortal(array $settings = array())
    {
        // Initialize `MySettings_tab` (setting containing the list of module
        // tabs) if not set.
        $tabController = new TabController();
        $tabController->getPortalTabs();

        $portalFields = array('defaultUser', 'appName', 'logoURL', 'serverUrl',
            'maxQueryResult', 'maxSearchQueryResult');
        $portalConfig = $this->getDefaultPortalSettings();

        foreach ($portalFields as $field) {
            if (isset($settings[$field])) {
                $portalConfig[$field] = $settings[$field];
            }
        }

        $portalConfig['appStatus'] = 'online';
        $portalConfig['on'] = 1;

        $this->savePortalSettings($portalConfig);
        $this->setUpUser();

        $this->refreshCache();
    }

    /**
     * Refreshes cache so that module metadata is rebuilt.
     */
    protected function refreshCache()
    {
        MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_SERVERINFO), ['base']);
        MetaDataManager::refreshCache(array('portal'));
    }

    /**
     * Unsets Portal.
     */
    public function unsetPortal()
    {
        $portalConfig = array(
            'appStatus' => 'offLine',
            'on' => 0
        );
        $this->savePortalSettings($portalConfig);
        $this->removeOAuthForPortalUser();
        MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_SERVERINFO), ['base']);
    }

    /**
     * Gets the default Portal settings.
     *
     * @return array the array containing default Portal settings.
     */
    private function getDefaultPortalSettings()
    {
        $portalConfig = array(
            'platform' => 'portal',
            'debugSugarApi' => true,
            'logLevel' => 'ERROR',
            'logWriter' => 'ConsoleWriter',
            'logFormatter' => 'SimpleFormatter',
            'metadataTypes' => array(),
            'defaultModule' => 'Cases',
            'orderByDefaults' => array(
                'Cases' => array(
                    'field' => 'case_number',
                    'direction' => 'desc'
                ),
                'Bugs' => array(
                    'field' => 'bug_number',
                    'direction' => 'desc'
                ),
                'Notes' => array(
                    'field' => 'date_modified',
                    'direction' => 'desc'
                ),
                'KBContents' => array(
                    'field' => 'date_modified',
                    'direction' => 'desc'
                )
            )
        );
        if (inDeveloperMode()) {
            $portalConfig['logLevel'] = 'DEBUG';
        }
        return $portalConfig;
    }

    /**
     * Toggles portal state (enabled/disabled).
     */
    public function handleSave()
    {
        if (isset($_REQUEST['appStatus']) && $_REQUEST['appStatus'] == 'true') {
            $this->setUpPortal($_REQUEST);
        } else {
            $this->unsetPortal();
        }
    }

    /**
     * Sets up portal user.
     */
    private function setUpUser()
    {
        $user = $this->getPortalUser();
        $role = $this->getPortalACLRole();

        if (!($user->check_role_membership($role->name))) {
            $user->load_relationship('aclroles');
            $user->aclroles->add($role);
            $user->save();
        }
    }

    /**
     * Saves Portal settings in the database.
     *
     * @param array $portalConfig an array containing Portal settings to save.
     */
    private function savePortalSettings(array $portalConfig)
    {
        //TODO: Remove after we resolve issues with test associated to this
        $GLOBALS['log']->info('Updating portal config');
        foreach ($portalConfig as $fieldKey => $fieldValue) {
            if (is_string($fieldValue)) {
                $fieldValue = SugarCleaner::stripTags($fieldValue);
            }
            // TODO: category should be `support`, platform should be `portal`
            $admin = $this->getAdministrationBean();
            if ($admin->saveSetting('portal', $fieldKey, $fieldValue, 'support') === false) {
                $GLOBALS['log']->fatal("Error saving portal config var $fieldKey, orig: "
                    . print_r($fieldValue, true) . " , json:".json_encode($fieldValue));
            }
        }

        // Verify the existence of the javascript config file
        if (!file_exists('portal2/config.js')) {
            SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
            $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
            $moduleInstallerClass::handlePortalConfig();
        }
    }

    /**
     * TODO build me a dependency injection
     * @return Administration the Administration class.
     */
    protected function getAdministrationBean()
    {
        return BeanFactory::newBean('Administration');
    }

    /**
     * Removes OAuth key of Portal user.
     */
    public function removeOAuthForPortalUser()
    {
        // Try to retrieve the portal user. If exists, check for
        // corresponding oauth2 and mark deleted.
        $portalUserName = "SugarCustomerSupportPortalUser";
        $id = BeanFactory::newBean('Users')->retrieve_user_id($portalUserName);
        if ($id) {
            $clientSeed = BeanFactory::newBean('OAuthKeys');
            $clientBean = $clientSeed->fetchKey('support_portal', 'oauth2');
            if ($clientBean) {
                $clientSeed->mark_deleted($clientBean->id);
            }
        }
    }

    /**
     * Creates Portal User.
     *
     * @return User the portal user.
     */
    public function getPortalUser()
    {
        $portalUserName = "SugarCustomerSupportPortalUser";
        $id = BeanFactory::newBean('Users')->retrieve_user_id($portalUserName);
        if (!$id) {
            $user = BeanFactory::newBean('Users');
            $user->user_name = $portalUserName;
            $user->title = 'Sugar Customer Support Portal User';
            $user->description = $user->title;
            $user->first_name = "";
            $user->last_name = $user->title;
            $user->status = 'Active';
            $user->receive_notifications = 0;
            $user->is_admin = 0;
            $random = CSPRNG::getInstance()->generate(32, true);
            $user->authenicate_id = md5($random);
            $user->user_hash = User::getPasswordHash($random);
            $user->default_team = '1';
            $user->created_by = '1';
            $user->portal_only = '1';
            $user->save();
            $id = $user->id;

            // set user id in system settings
            Administration::getSettings()->saveSetting('supportPortal', 'RegCreatedBy', $id);
        }
        $this->createOAuthForPortalUser();
        $resultUser = BeanFactory::getBean('Users', $id);
        return $resultUser;
    }

    /**
     * Creates the oauthkey record for this portal user if it doesn't exist.
     */
    public function createOAuthForPortalUser()
    {
        $clientSeed = BeanFactory::newBean('OAuthKeys');
        $clientBean = $clientSeed->fetchKey('support_portal', 'oauth2');
        if (!$clientBean) {
            $newKey = BeanFactory::newBean('OAuthKeys');
            $newKey->oauth_type = 'oauth2';
            $newKey->c_secret = '';
            $newKey->client_type = 'support_portal';
            $newKey->c_key = 'support_portal';
            $newKey->name = 'OAuth Support Portal Key';
            $newKey->description = 'This OAuth key is automatically created by the OAuth2.0 system to enable logins to the serf-service portal system in Sugar.';
            $newKey->save();
        }
    }

    /**
     * Creates Portal role and sets ACLS
     *
     * @return ACLRole
     */
    public function getPortalACLRole()
    {
        global $mod_strings;
        $allowedModules = array('Bugs', 'Cases', 'Notes', 'KBContents', 'Contacts');
        $allowedActions = array('edit', 'admin', 'access', 'list', 'view');
        $role = BeanFactory::newBean('ACLRoles');
        $role->retrieve_by_string_fields(array('name' => 'Customer Self-Service Portal Role'));
        if (empty($role->id)) {
            $role->name = "Customer Self-Service Portal Role";
            $role->description = $mod_strings['LBL_PORTAL_ROLE_DESC'];
            $role->save();
            $roleActions = $role->getRoleActions($role->id);
            foreach ($roleActions as $moduleName => $actions) {
                // enable allowed moduels
                if (isset($actions['module']['access']['id']) && !in_array($moduleName, $allowedModules)) {
                    $role->setAction($role->id, $actions['module']['access']['id'], ACL_ALLOW_DISABLED);
                } elseif (isset($actions['module']['access']['id']) && in_array($moduleName, $allowedModules)) {
                    $role->setAction($role->id, $actions['module']['access']['id'], ACL_ALLOW_ENABLED);
                } else {
                    foreach ($actions as $action => $actionName) {
                        if (isset($actions[$action]['access']['id'])) {
                            $role->setAction($role->id, $actions[$action]['access']['id'], ACL_ALLOW_DISABLED);
                        }
                    }
                }
                if (in_array($moduleName, $allowedModules)) {
                    $role->setAction($role->id, $actions['module']['access']['id'], ACL_ALLOW_ENABLED);
                    $role->setAction($role->id, $actions['module']['admin']['id'], ACL_ALLOW_ALL);
                    foreach ($actions['module'] as $actionName => $action) {
                        if (in_array($actionName, $allowedActions)) {
                            $aclAllow = ACL_ALLOW_ALL;
                        } else {
                            $aclAllow = ACL_ALLOW_NONE;
                        }
                        if ($moduleName == 'KBContents' && $actionName == 'edit') {
                            $aclAllow = ACL_ALLOW_NONE;
                        }
                        if ($moduleName == 'Contacts') {
                            if ($actionName == 'edit' || $actionName == 'view') {
                                $aclAllow = ACL_ALLOW_OWNER;
                            } else {
                                $aclAllow = ACL_ALLOW_NONE;
                            }

                        }
                        if ($actionName != 'access') {
                            $role->setAction($role->id, $action['id'], $aclAllow);
                        }

                    }
                }
            }
        }
        return $role;
    }
}
