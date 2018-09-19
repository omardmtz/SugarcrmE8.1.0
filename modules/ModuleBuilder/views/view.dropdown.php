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
require_once 'modules/ModuleBuilder/MB/ModuleBuilder.php';

class ViewDropdown extends SugarView
{
    protected $defaultParams = array(
        'refreshTree' => false,
        'package_name' => 'studio',
        'view_package' => 'studio',
        'view_module' => '',
        'dropdown_lang' => '',
        'dropdown_name' => '',
        'dropdown_role' => '',
        'field' => '',
        'new' => false
    );

    protected $template = 'modules/ModuleBuilder/tpls/MBModule/dropdown.tpl';

    /**
     * @see SugarView::_getModuleTitleParams()
     * @param bool $browserTitle
     * @return array
     */
    protected function _getModuleTitleParams($browserTitle = false)
    {
        return array(
            translate('LBL_MODULE_NAME', 'Administration'),
            ModuleBuilderController::getModuleTitle(),
        );
    }

    function display()
    {
        $ajax = new AjaxCompose();

        $args = array(
            'view_package' => $this->request->getValidInputRequest('view_package', 'Assert\ComponentName'),
            'view_module' => $this->request->getValidInputRequest('view_module', 'Assert\ComponentName'),
            'dropdown_lang' => $this->request->getValidInputRequest('dropdown_lang', 'Assert\Language'),
            'dropdown_name' => $this->request->getValidInputRequest('dropdown_name', 'Assert\ComponentName'),
            'dropdown_role' => $this->request->getValidInputRequest('dropdown_role', 'Assert\Guid'),
            'field' => $this->request->getValidInputRequest('field'),
            'new' => $this->request->getValidInputRequest('new'),
        );

        $params = $this->parseArguments($args);
        $smarty = $this->generateSmarty($params);

        if (isset($_REQUEST['refreshTree'])) {
            $mbt = new DropDownTree();
            $ajax->addSection('west', $mbt->getName(), $mbt->fetchNodes());
            $smarty->assign('refreshTree', true);
        }

        $smarty->assign(
            'deleteImage',
            SugarThemeRegistry::current()->getImage(
                'delete_inline',
                '',
                null,
                null,
                '.gif',
                translate('LBL_MB_DELETE')
            )
        );
        $smarty->assign(
            'editImage',
            SugarThemeRegistry::current()->getImage('edit_inline', '', null, null, '.gif', translate('LBL_EDIT'))
        );
        $smarty->assign('action', 'savedropdown');
        $smarty->assign('fromNewField', !empty($_REQUEST['is_new_field']));
        $body = $smarty->fetch($this->template);
        $ajax->addSection('east2', translate('LBL_SECTION_DROPDOWNED'), $body);
        echo $ajax->getJavascript();
    }

    function generateSmarty($params)
    {
        $vardef = array();
        $package_name = 'studio';
        $my_list_strings = return_app_list_strings_language($params['dropdown_lang']);

        $smarty = new Sugar_Smarty();

        //if we are using ModuleBuilder then process the following
        if (!empty($params['view_package']) && $params['view_package'] != 'studio') {
            $mb = new ModuleBuilder();
            $module = $mb->getPackageModule($params['view_package'], $params['view_module']);
            $package_name = $mb->packages[$params['view_package']]->name;
            $module->getVardefs();

            $vardef = (!empty($module->mbvardefs->fields[$params['dropdown_name']])) ? $module->mbvardefs->fields[$params['dropdown_name']] : array();
            $module->mblanguage->generateAppStrings(false);
            $my_list_strings = array_merge(
                $my_list_strings,
                $module->mblanguage->appListStrings[$params['dropdown_lang'] . '.lang.php']
            );
            $smarty->assign('module_name', $module->name);
        }

        $module_name = !empty($module->name) ? $module->name : '';
        $module_name = (empty($module_name) && !empty($params['view_module'])) ? $params['view_module'] : $module_name;

        foreach ($my_list_strings as $key => $value) {
            if (!is_array($value)) {
                unset($my_list_strings[$key]);
            }
        }

        $json = getJSONobj();

        $required_items = array();
        if ($params['dropdown_name'] && empty($params['new'])) {
            $name = $params['dropdown_name'];

            // handle the case where we've saved a dropdown in one language, and
            // now attempt to edit it for another language. The $name exists,
            // but $my_list_strings[$name] doesn't for now, we just treat it as
            // if it was new. A better approach might be to use the first language
            // version as a template for future languages
            if (!isset($my_list_strings[$name])) {
                $my_list_strings[$name] = array();
            }

            // Handle required elements of a drop down
            $required_items = getRequiredDropdownListItemsByDDL($name);

            $selected_dropdown = (!empty($vardef['options']) && !empty($my_list_strings[$vardef['options']])) ? $my_list_strings[$vardef['options']] : $my_list_strings[$name];
            $smarty->assign('ul_list', 'list = ' . $json->encode(array_keys($selected_dropdown)));
            $smarty->assign(
                'dropdown_name',
                (!empty($vardef['options']) ? $vardef['options'] : $params['dropdown_name'])
            );
            $smarty->assign('name', $params['dropdown_name']);
            $smarty->assign('options', $selected_dropdown);
        } else {
            $smarty->assign('ul_list', 'list = {}');

            if ($params['dropdown_name']) {
                $dropdown_name = $params['dropdown_name'];
            } else {
                //we should try to find a name for this dropdown based on the field name.
                //ensure this dropdown name does not already exist
                $dropdown_name = $params['field'] . '_list';
                $i = 0;
                while (isset($my_list_strings[$dropdown_name])) {
                    $dropdown_name = $params['field'] . '_' . ++$i;
                }
            }

            $smarty->assign('dropdown_name', $dropdown_name);
        }

        $smarty->assign('required_items', json_encode($required_items));
        $smarty->assign('module_name', $module_name);
        $smarty->assign('selected_lang', $params['dropdown_lang']);
        $smarty->assign('available_languages', get_languages());
        $smarty->assign('roles', $this->getAvailableRoleList($params['dropdown_name']));
        $smarty->assign('package_name', $package_name);
        $smarty->assign('new', !empty($params['new']));

        return $smarty;
    }

    protected function parseArguments($args)
    {
        $params = array_merge($this->defaultParams, $args);

        if (!$params['dropdown_lang']) {
            $params['dropdown_lang'] = $GLOBALS['locale']->getAuthenticatedUserLanguage();
        }

        if (empty($params['dropdown_name'])) {
            $params['new'] = true;
            if (!empty($params['field'])) {
                $params['dropdown_name'] = $params['field'] . '_list';
            }
        }

        return $params;
    }

    /**
     * Returns list of roles with marker indicating whether role specific metadata for the given dropdown field exists
     *
     * @param string $name Dropdown field name
     * @return array
     */
    protected function getAvailableRoleList($name)
    {
        $manager = MetaDataManager::getManager();
        return MBHelper::getAvailableRoleList(function (array $params) use ($manager, $name) {
            return $manager->hasEditableDropdownFilter($name, $params['role']);
        });
    }
}
