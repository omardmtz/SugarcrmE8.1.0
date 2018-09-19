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

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;
use Sugarcrm\Sugarcrm\IdentityProvider\Authentication;

class ViewModulefields extends SugarView
{
    var $mbModule;

    /**
     * @var Authentication\Config
     */
    protected $idpConfig;

    /**
     * @inheritdoc
     */
    public function __construct($bean = null, $view_object_map = array(), Request $request = null)
    {
        $this->idpConfig = new Authentication\Config(\SugarConfig::getInstance());
        parent::__construct($bean, $view_object_map, $request);
    }

    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;

    	return array(
    	   translate('LBL_MODULE_NAME','Administration'),
    	   ModuleBuilderController::getModuleTitle(),
    	   );
    }

	function display()
	{
        $smarty = new Sugar_Smarty();
        global $mod_strings;
        $bak_mod_strings=$mod_strings;
        $smarty->assign('mod_strings', $mod_strings);

        $module_name = $this->request->getValidInputRequest('view_module', 'Assert\ComponentName');

        global $current_language;
        $module_strings = return_module_language($current_language, $module_name);

        $fieldsData = array();
        $customFieldsData = array();

        //use fieldTypes variable to map field type to displayed field type
        $fieldTypes = $mod_strings['fieldTypes'];
        //add datetimecombo type field from the vardef overrides to point to Datetime type
        $fieldTypes['datetime'] = $fieldTypes['datetimecombo'];

        if(!isset($_REQUEST['view_package']) || $_REQUEST['view_package'] == 'studio') {
            //$this->loadPackageHelp($module_name);
            $studioClass = new stdClass;
            $studioClass->name = $module_name;

            $objectName = BeanFactory::getObjectName($module_name);

            VardefManager::loadVardef($module_name, $objectName, true);
            global $dictionary;
            $f = array($mod_strings['LBL_HCUSTOM']=>array(), $mod_strings['LBL_HDEFAULT']=>array());

            foreach($dictionary[$objectName]['fields'] as $def) {
                if (!$this->isValidStudioField($def)) {
                    continue;
                }
                if (!empty($def['vname'])) {
                    $def['label'] = translate($def['vname'], $module_name);
                } elseif (!empty($def['label'])) {
                    $def['label'] = translate($def['label'], $module_name);
                } else {
                    $def['label'] = $def['name'];
                }

				//Custom relate fields will have a non-db source, but custom_module set
            	if(isset($def['source']) && $def['source'] == 'custom_fields' || isset($def['custom_module'])) {
                   $f[$mod_strings['LBL_HCUSTOM']][$def['name']] = $def;
                   $def['custom'] = true;
                } else {
                   $f[$mod_strings['LBL_HDEFAULT']][$def['name']] = $def;
                   $def['custom'] = false;
                }

                $def['type'] = isset($fieldTypes[$def['type']]) ? $fieldTypes[$def['type']] : ucfirst($def['type']);
                $fieldsData[] = $def;
                $customFieldsData[$def['name']] = $def['custom'];
            }
            $studioClass->mbvardefs->vardefs['fields'] = $f;
            $smarty->assign('module', $studioClass);

            $package = new stdClass;
            $package->name = '';
            $smarty->assign('package', $package);
            global $current_user;
            $sortPreferences = $current_user->getPreference('fieldsTableColumn', 'ModuleBuilder');
            $smarty->assign('sortPreferences', $sortPreferences);
            $smarty->assign('fieldsData', getJSONobj()->encode($fieldsData));
            $smarty->assign('customFieldsData', getJSONobj()->encode($customFieldsData));
            $smarty->assign('studio', true);
            $ajax = new AjaxCompose();
            $ajax->addCrumb($mod_strings['LBL_STUDIO'], 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard")');
            $ajax->addCrumb(translate($module_name), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view_module='.$module_name.'")');
            $ajax->addCrumb($mod_strings['LBL_FIELDS'], '');
            $ajax->addSection('center', $mod_strings['LBL_EDIT_FIELDS'],$smarty->fetch('modules/ModuleBuilder/tpls/MBModule/fields.tpl'));
            $_REQUEST['field'] = '';

            echo $ajax->getJavascript();
        } else {
            require_once('modules/ModuleBuilder/MB/ModuleBuilder.php');
            $mb = new ModuleBuilder();
            $packName = $this->request->getValidInputRequest('view_package', 'Assert\ComponentName');
            $mb->getPackage($packName);
            $package = $mb->packages[$packName];

            $package->getModule($module_name);
            $this->mbModule = $package->modules[$module_name];
            // We need the type to determine true custom fields
            $moduleType = $this->mbModule->getModuleType();
            $this->loadPackageHelp($module_name);
            $this->mbModule->getVardefs(true);
            $this->mbModule->mbvardefs->vardefs['fields'] = array_reverse($this->mbModule->mbvardefs->vardefs['fields'], true);
            $loadedFields = array();

            if(file_exists($this->mbModule->path. '/language/'.$current_language.'.lang.php'))
            {
                include FileLoader::validateFilePath($this->mbModule->path .'/language/'.$current_language.'.lang.php');
                $this->mbModule->setModStrings($current_language,$mod_strings);
            }elseif(file_exists($this->mbModule->path. '/language/en_us.lang.php')){
                include($this->mbModule->path .'/language/en_us.lang.php');
                $this->mbModule->setModStrings('en_us',$mod_strings);
            }

            foreach($this->mbModule->mbvardefs->vardefs['fields'] as $k=>$v)
            {
                if ($k != $this->mbModule->name)
                {
                    foreach($v as $field => $def)
                    {
                        if (in_array($field, array_keys($this->mbModule->mbvardefs->vardefs['fields'][$this->mbModule->name])))
                        {
                            $this->mbModule->mbvardefs->vardefs['fields'][$k][$field] = $this->mbModule->mbvardefs->vardefs['fields'][$this->mbModule->name][$field];

                            unset($this->mbModule->mbvardefs->vardefs['fields'][$this->mbModule->name][$field]);
                        }
                    }
                }
            }

            foreach($this->mbModule->mbvardefs->vardefs['fields'] as $k=>$v)
            {
                if($k != $module_name)
                {
                    $titleLBL[$k]=translate("LBL_".strtoupper($k),'ModuleBuilder');
                }else{
                    $titleLBL[$k]=$k;
                }
                foreach($v as $field => $def)
                {
                	if (isset($loadedFields[$field]))
                    {
                	   unset($this->mbModule->mbvardefs->vardefs['fields'][$k][$field]);
                    } else {
                       $this->mbModule->mbvardefs->vardefs['fields'][$k][$field]['label'] = isset($def['vname']) && isset($this->mbModule->mblanguage->strings[$current_language.'.lang.php'][$def['vname']]) ? $this->mbModule->mblanguage->strings[$current_language.'.lang.php'][$def['vname']] : $field;
                       // It's only custom if the module name is the same as the key AND not the same as the module type
                       $custom = $k == $this->mbModule->name && $this->mbModule->name != $moduleType;
                       $customFieldsData[$field] = $custom ? true : false;
                       $loadedFields[$field] = true;

                       $type = $this->mbModule->mbvardefs->vardefs['fields'][$k][$field]['type'];
                       $this->mbModule->mbvardefs->vardefs['fields'][$k][$field]['type'] = isset($fieldTypes[$type]) ? $fieldTypes[$type] : ucfirst($type);
                       if ($this->isValidStudioField($this->mbModule->mbvardefs->vardefs['fields'][$k][$field])) {
                           $fieldsData[] = $this->mbModule->mbvardefs->vardefs['fields'][$k][$field];
                       }
                    }
                }
            }

            $this->mbModule->mbvardefs->vardefs['fields'][$module_name] = $this->cullFields($this->mbModule->mbvardefs->vardefs['fields'][$module_name]);

            $smarty->assign('fieldsData', getJSONobj()->encode($fieldsData));
            $smarty->assign('customFieldsData', getJSONobj()->encode($customFieldsData));
            global $current_user;
            $sortPreferences = $current_user->getPreference('fieldsTableColumn', 'ModuleBuilder');
            $smarty->assign('sortPreferences', $sortPreferences);
            $smarty->assign('title', $titleLBL);
            $smarty->assign('package', $package);
            $smarty->assign('module', $this->mbModule);
            $smarty->assign('editLabelsMb','1');
            $smarty->assign('studio', false);

            $ajax = new AjaxCompose();
            $ajax->addCrumb($bak_mod_strings['LBL_MODULEBUILDER'], 'ModuleBuilder.main("mb")');
            $ajax->addCrumb($package->name,'ModuleBuilder.getContent("module=ModuleBuilder&action=package&package='.$package->name.'")');
            $ajax->addCrumb($module_name, 'ModuleBuilder.getContent("module=ModuleBuilder&action=module&view_package='.$package->name.'&view_module='. $module_name . '")');
            $ajax->addCrumb($bak_mod_strings['LBL_FIELDS'], '');
            $ajax->addSection('center', $bak_mod_strings["LBL_FIELDS"],$smarty->fetch('modules/ModuleBuilder/tpls/MBModule/fields.tpl'));
            $_REQUEST['field'] = '';

            echo $ajax->getJavascript();


        }
    }

    function loadPackageHelp(
        $name
        )
    {
        $this->mbModule->help['default'] = (empty($name))?'create':'modify';
        $this->mbModule->help['group'] = 'module';
        $this->mbModule->help['group'] = 'module';
    }

    function cullFields(
        $def
        )
    {
        if(!empty($def['parent_id']))
            unset($def['parent_id']);
        if(!empty($def['parent_type']))
            unset($def['parent_type']);
        if(!empty($def['currency_id']))
            unset($def['currency_id']);
        return $def;
    }

    function isValidStudioField(
        $def
        )
	{
        if ($this->idpConfig->isIDMModeEnabled() && !empty($def['idm_mode_disabled'])) {
            return false;
        }

    	if (isset($def['studio'])) {
            if (is_array($def [ 'studio' ]))
            {
    			if (isset($def['studio']['editField']) && $def['studio']['editField'] == true)
                    return true;
    			if (isset($def['studio']['required']) && $def['studio']['required'])
                    return true;

    		} else
    		{
    			if ($def['studio'] == 'visible')
                    return true;
                if ($def['studio'] == 'hidden' || $def['studio'] == 'false' || !$def['studio'] )
                    return false;
            }
        }
    	if (empty($def ['source']) || $def ['source'] == 'db' || $def ['source'] == 'custom_fields')
		{
            if (strtolower($def['type']) != 'id' && (empty($def ['dbType']) || $def ['dbType'] != 'id')) {
                return true;
            }
		}

		return false;
	}
}
