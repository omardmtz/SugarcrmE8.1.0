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

require_once 'modules/ModuleBuilder/parsers/constants.php';

class MBLanguage{
		var $iTemplates = array();
		var $templates = array();

    /**
     * @var string[][]
     */
    public $appListStrings = array();

    /*
     * Package path
     * @var string
     */
    protected $packagePath;

    public function __construct($name, $path, $label, $key_name, $label_singular)
    {
        $this->path = $path;
        $this->name = $name;
        $this->key_name = $key_name;
        $this->label = $label;
        $this->label_singular = $label_singular;
        $this->packagePath = $this->getPackagePath($path);
    }

		function load(){
			$this->generateModStrings();
			$this->generateAppStrings();
		}

		function loadStrings($file)
        {
            $module = strtoupper($this->name);
            $object_name = strtoupper($this->key_name);
            $_object_name = strtolower($this->name);
			if(!file_exists($file))return;

			$d = dir($file);
			while($e = $d->read()){
				if(substr($e, 0, 1) != '.' && is_file($file . '/' . $e)){
					include FileLoader::validateFilePath($file.'/'. $e);
					if(empty($this->strings[$e])){

						$this->strings[$e] = $mod_strings;
					}else{
						$this->strings[$e] = array_merge($this->strings[$e], $mod_strings);
					}
				}
			}
		}

    public function loadAppListStrings($file){
        if (!file_exists($file)) {
            return;
        }
        //we may not need this when loading in the app strings, but there is no harm
        //in setting it.
        $object_name = strtolower($this->key_name);

        $d = dir($file);
        while ($e = $d->read()) {
            if(substr($e, 0, 1) != '.' && is_file($file . '/' . $e)) {
                include FileLoader::validateFilePath($file.'/'. $e);
                if (empty($this->appListStrings[$e])) {
                    $this->appListStrings[$e] = $app_list_strings;
                } else {
                    $this->appListStrings[$e] = array_merge($this->appListStrings[$e], $app_list_strings);
                }
            }
        }
    }

		function generateModStrings(){
			$this->strings = array();
			$this->loadTemplates();

			foreach($this->iTemplates as $template=>$val){
				$file = MB_IMPLEMENTS . '/' . $template . '/language';
				$this->loadStrings($file);
			}
			foreach($this->templates as $template=>$val){
				$file = MB_TEMPLATES . '/' . $template . '/language';
				$this->loadStrings($file);
			}
			$this->loadStrings($this->path . '/language');
		}

		function getModStrings($language='en_us'){
			$language .= '.lang.php';
			if(!empty($this->strings[$language]) && $language != 'en_us.lang.php'){
			    return sugarLangArrayMerge($this->strings['en_us.lang.php'], $this->strings[$language]);
			}
			if(!empty($this->strings['en_us.lang.php']))return $this->strings['en_us.lang.php'];
			$empty = array();
			return $empty;
		}
		function getAppListStrings($language='en_us'){
			$language .= '.lang.php';
			if(!empty($this->appListStrings[$language]) && $language != 'en_us.lang.php'){
			    return sugarLangArrayMerge($this->appListStrings['en_us.lang.php'], $this->appListStrings[$language]);
			}
			if(!empty($this->appListStrings['en_us.lang.php']))return $this->appListStrings['en_us.lang.php'];
			$empty = array();
			return $empty;
		}

		function generateAppStrings($buildFromTemplate = true){
			$this->appListStrings = array('en_us.lang.php'=>array());
			//By default, generate app strings for the current language as well.
			$this->appListStrings[$GLOBALS [ 'current_language' ] . ".lang.php"] = array();

			if($buildFromTemplate){
				//go through the templates application strings and load anything that is needed
				foreach($this->iTemplates as $template=>$val){
					$file = MB_IMPLEMENTS . '/' . $template . '/language/application';
					$this->loadAppListStrings($file);
				}
				foreach($this->templates as $template=>$val){
					$file = MB_TEMPLATES . '/' . $template . '/language/application';
					$this->loadAppListStrings($file);
				}
			}
            $this->loadAppListStrings($this->packagePath . '/language/application');
		}

    function save($key_name, $duplicate = false, $rename = false)
    {
        $header = file_get_contents('modules/ModuleBuilder/MB/header.php');
        $save_path = $this->path . '/language';
        mkdir_recursive($save_path);
        foreach ($this->strings as $lang => $values) {
            //Check if the module Label or Singular Label has changed.
            $mod_strings = return_module_language(str_replace('.lang.php', '', $lang), 'ModuleBuilder');
            $required = array(
                'LBL_LIST_FORM_TITLE' => $this->label . " " . $mod_strings['LBL_LIST'],
                'LBL_MODULE_NAME' => $this->label,
                'LBL_MODULE_TITLE' => $this->label,
                'LBL_MODULE_NAME_SINGULAR' => $this->label_singular,
                'LBL_HOMEPAGE_TITLE' => $mod_strings['LBL_HOMEPAGE_PREFIX'] . " " . $this->label,
                //FOR GENERIC MENU
                'LNK_NEW_RECORD' => $mod_strings['LBL_CREATE'] . " " . $this->label_singular,
                'LNK_LIST' => $mod_strings['LBL_VIEW'] . " " . $this->label,
                'LNK_IMPORT_' . strtoupper($this->key_name) => translate('LBL_IMPORT') . " " . $this->label,
                'LBL_SEARCH_FORM_TITLE' => $mod_strings['LBL_SEARCH'] . " " . $this->label_singular,
                'LBL_HISTORY_SUBPANEL_TITLE' => $mod_strings['LBL_HISTORY'],
                'LBL_ACTIVITIES_SUBPANEL_TITLE' => $mod_strings['LBL_ACTIVITIES'],
                'LBL_' . strtoupper($this->key_name) . '_SUBPANEL_TITLE' => $this->label,
                'LBL_NEW_FORM_TITLE' => $mod_strings['LBL_NEW'] . " " . $this->label_singular,
                'LNK_IMPORT_VCARD' => translate('LBL_IMPORT') . " " . $this->label_singular . ' vCard',
                'LBL_IMPORT' => translate('LBL_IMPORT') . " " . $this->label,
                'LBL_IMPORT_VCARDTEXT' => "Automatically create a new {$this->label_singular} record by importing a vCard from your file system.",
            );
            foreach ($required as $k => $v) {
                $values[$k] = $v;
            }
            write_array_to_file('mod_strings', $values, $save_path . '/' . $lang, 'w', $header);
        }
        $app_save_path = $this->packagePath . '/language/application';
        sugar_mkdir($app_save_path, null, true);
        $key_changed = ($this->key_name != $key_name);

        foreach ($this->appListStrings as $lang => $values) {
            // Load previously created modules data
            $app_list_strings = array();
            $neededFile = $app_save_path . '/' . $lang;
            if (file_exists($neededFile)) {
                include FileLoader::validateFilePath($neededFile);
            }

            if (!$duplicate) {
                unset($values['moduleList'][$this->key_name]);
            }

            $values = sugarLangArrayMerge($values, $app_list_strings);
            $values['moduleList'][$key_name] = $this->label;
            $values['moduleListSingular'][$key_name] = $this->label_singular;

            $appFile = $header . "\n";
            $this->getGlobalAppListStringsForMB($values);
            foreach ($values as $key => $array) {
                if ($duplicate) {
                    //keep the original when duplicating
                    $appFile .= override_value_to_string_recursive2('app_list_strings', $key, $array);
                }
                $okey = $key;
                if ($key_changed) {
                    $key = str_replace(strtolower($this->key_name), strtolower($key_name), $key);
                }
                // if we aren't duplicating or the key has changed let's add it
                if (!$duplicate || $okey != $key) {
                    if ($rename && isset($this->appListStrings[$lang][$key])) {
                        $arr = $this->appListStrings[$lang][$key];
                    } else {
                        $arr = $array;
                    }
                    $appFile .= override_value_to_string_recursive2('app_list_strings', $key, $arr);
                }
            }
            sugar_file_put_contents_atomic($app_save_path . '/' . $lang, $appFile);
        }
    }

    /**
     * Deletes the given module data from the package $app_list_strings
     *
     * @param string $module Module name
     */
    public function delete($module)
    {
        $header = file_get_contents('modules/ModuleBuilder/MB/header.php');
        $app_save_path = $this->path . '/../../language/application';
        foreach ($this->appListStrings as $lang => $values) {
            $file = $app_save_path . '/' . $lang;
            if (file_exists($file)) {
                $app_list_strings = array();
                include $file;
                unset(
                    $app_list_strings['moduleList'][$module],
                    $app_list_strings['moduleListSingular'][$module]
                );

                $contents = $header;
                foreach ($app_list_strings as $key => $array) {
                    $contents .= override_value_to_string_recursive2('app_list_strings', $key, $array);
                }
                sugar_file_put_contents_atomic($file, $contents);
            }
        }
    }

		/**
		*  If there is no this dropdown list  in  custom\modulebuilder\packages\$package\language\application\$lang.lang.php ,
		*  we will include it from global app_list_string array into custom\modulebuilder\packages\$package\language\application\$lang.lang.php
		*  when we create a dropdown filed  and the value is created in MB.(#20728 )
		**/
		function getGlobalAppListStringsForMB(&$values){
			//Ensure it comes from MB
			if(!empty($_REQUEST['view_package']) && !empty($_REQUEST['type']) && $_REQUEST['type'] == 'enum'  && !empty($_REQUEST['options'])){
				if(!isset($values[$_REQUEST['options']])){
					global $app_list_strings;
					if(!empty($app_list_strings[$_REQUEST['options']])){
						$values[$_REQUEST['options']]  = $app_list_strings[$_REQUEST['options']];
					}
				}
			}
		}

		function build($path){
			if(file_exists($this->path.'/language/'))
			copy_recursive($this->path.'/language/', $path . '/language/');
		}

        public function loadTemplates()
        {
            if (empty($this->templates)) {
                $configFile = $this->path . '/config.php';
                if (file_exists($configFile)) {
                    $config = FileLoader::varFromInclude($configFile, 'config');
                    $this->templates = $config['templates'];
                    $this->iTemplates = array();
                }
            }
        }

		/**
		 * Reset the templates and load the language files again.  This is called from
		 * MBModule->save() once the config file has been written.
		 */
		function reload(){
			$this->templates = null;
			$this->load();
		}

    /**
     * Attempts to translate the given label if it is contained in this
     * undeployed module's language strings
     *
     * @param string $label Label to translate
     * @param string $language Language to use to translate the label
     * @return string
     */
    public function translate($label, $language = "en_us")
    {
        $language = $language . ".lang.php";
        if (isset($this->strings[$language][$label])) {
            return $this->strings[$language][$label];
        }

        if (isset($this->appListStrings[$language][$label])) {
            return $this->appListStrings[$language][$label];
        }

        return $label;
    }

    /**
     * Get package path from module path
     * @param string $path Module path
     * @return false|string
     */
    protected function getPackagePath($path)
    {
        $split = preg_split('#/#', $this->path);
        return isset($split[3]) ? MB_PACKAGE_PATH . '/' . $split[3] : false;
    }
}
