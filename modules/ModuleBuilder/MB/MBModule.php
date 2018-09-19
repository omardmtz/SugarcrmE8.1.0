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

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;
use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

define('MB_TEMPLATES', 'include/SugarObjects/templates');
define('MB_IMPLEMENTS', 'include/SugarObjects/implements');

require_once 'modules/ModuleBuilder/parsers/constants.php';

class MBModule
{
    public $name = '' ;
    public $config = array(
        'team_security' => 1,
        'assignable' => 1,
        'taggable' => 1,
        'acl' => 1,
        'has_tab' => 1,
        'studio' => 1,
        'audit' => 1,
        'activity_enabled' => 0, //activity stream disabled by default for new modules
    );
    public $mbpublicdefs ;
    public $errors = array ( ) ;
    public $path = '' ;
    public $implementable = array (
    'team_security' => 'Team Security' ,
    'has_tab' => 'Navigation Tab' ) ;
    public $always_implement = array ( 'assignable' => 'Assignable' , 'acl' => 'Access Controls' , 'studio' => 'Studio Support' , 'audit' => 'Audit Table' ) ;
    public $iTemplate = array(
        'team_security',
        'assignable',
        'taggable',
    );

    public $config_md5 = null ;

    /**
     * The metadata converter object, used to keep the peace between BWC and Sidecar
     * formatted metadata
     * 
     * @var MetaDataConverter
     */
    public $mdc;

    /**
     * @var MBLanguage
     */
    public $mblanguage;

    function __construct ($name , $path , $package , $package_key)
    {
        global $mod_strings;
    	$this->config [ 'templates' ] = array ( 'basic' => 1 ) ;

        $this->name = MBModule::getDBName ( $name ) ;
        $this->key_name = $package_key . '_' . $name ;
        $this->package = $package ;
        $this->package_key = $package_key ;
        $this->package_path = $path ;

        $this->implementable = array (
        'team_security' => !empty($mod_strings[ 'LBL_TEAM_SECURITY' ]) ? $mod_strings[ 'LBL_TEAM_SECURITY' ] : true,
        'has_tab' => !empty($mod_strings[ 'LBL_NAV_TAB' ]) ? $mod_strings[ 'LBL_NAV_TAB' ] : false) ;
        $this->path = $this->getModuleDir () ;
        //		$this->mbrelationship = new MBRelationship($this->name, $this->path, $this->key_name);
        $this->relationships = new UndeployedRelationships ( $this->path ) ;
        $this->mbvardefs = new MBVardefs ( $this->name, $this->path, $this->key_name ) ;

        $this->load () ;
    }

    function getDBName ($name)
    {
        return preg_replace ( "/[^\w]+/", "_", $name ) ;
    }

    function getModuleName()
    {
        return $this->name;
    }

    function getPackageName()
    {
        return $this->package;
    }

    /**
     * @return UndeployedRelationships
     */
    function getRelationships()
    {
        return $this->relationships;
    }

    /**
     * Loads the module based on the module name
     *
     */
    function load ()
    {
        if (file_exists ( $this->path . '/config.php' ))
        {
            include FileLoader::validateFilePath($this->path . '/config.php');
            $this->config = $config ;
            $this->normalizeConfig();
        }
        $label = (!empty ($this->config ['label'])) ? $this->config ['label'] : $this->name;
        $label_singular = !empty($this->config['label_singular']) ? $this->config['label_singular'] : $label;
        $this->mblanguage = new MBLanguage ($this->name, $this->path, $label, $this->key_name, $label_singular);
        foreach ( $this->iTemplate as $temp )
        {
            if (! empty ( $this->config [ $temp ] ))
            {
                $this->mbvardefs->iTemplates [ $temp ] = 1 ;
                $this->mblanguage->iTemplates [ $temp ] = $temp ;
            }
        }
        $this->mbvardefs->templates = $this->config [ 'templates' ] ;
        $this->mblanguage->templates = $this->config [ 'templates' ] ;
        $this->mbvardefs->load () ;
        $this->mblanguage->load () ;

        $this->verifyMetadata();

    }

    /**
     * Ensures the config matches current expectations.
     * The config may or may not match the current expectation if the package was exported from an older version
     * or existed before a version upgrade.
     */
    protected function normalizeConfig()
    {
        if (empty($this->config['label_singular']) && !empty($this->config['label'])) {
            $this->config['label_singular'] = $this->config ['label'];
        }
    }

    function addTemplate ($template)
    {
        $this->config [ 'templates' ] [ $template ] = 1 ;
    }

    function getModuleDir ()
    {
        return $this->package_path . '/modules/' . $this->name ;
    }

    function removeTemplate ($template)
    {
        unset ( $this->config [ 'templates' ] [ $template ] ) ;
    }

    function getVardefs ($by_group = false)
    {
        $this->mbvardefs->updateVardefs ( $by_group ) ;
        return $this->mbvardefs->getVardefs () ;
    }

    function addField ($vardef)
    {
        $this->mbvardefs->addFieldVardef ( $vardef ) ;
    }

    function addFieldObject ($field)
    {
        $vardef = $field->get_field_def () ;
		$this->mbvardefs->mergeVardefs();
		$existingVardefs = $this->mbvardefs->getVardefs () ;

                // default unified_search is false, but if the fts is enabled, we need it to be true
                if (!empty($field->supports_unified_search) && isset($vardef['full_text_search']) && !empty($vardef['full_text_search']['enabled'])) {
                    $vardef['unified_search'] = true;
                }

		//Merge with the existing vardef if it already exists
		if(!empty($existingVardefs['fields'][$vardef['name']])){
		    // if we edited non-db field, do not override its source
		    if(isset($existingVardefs['fields'][$vardef['name']]['source']) && $existingVardefs['fields'][$vardef['name']]['source'] == 'non-db') {
		        unset($vardef['source']);
		    }
			$vardef = array_merge( $existingVardefs['fields'][$vardef['name']], $vardef);
		}
        if (! empty ( $vardef [ 'source' ] ) && $vardef [ 'source' ] == 'custom_fields')
            unset ( $vardef [ 'source' ] ) ;

        if (!empty($vardef['default'])) {
            $vardef['default'] = htmlspecialchars_decode($vardef['default'], ENT_QUOTES);
        }

	    $this->mbvardefs->load();
        $this->addField ( $vardef ) ;
        $this->mbvardefs->save();
    }

    function deleteField ($name)
    {
        $this->mbvardefs->deleteField ( $name ) ;
    }

    function fieldExists ($name = '' , $type = '')
    {
        $vardefs = $this->getVardefs();
        if (! empty ( $vardefs ))
        {
            if (empty ( $type ) && empty ( $name ))
                return false ; else if (empty ( $type ))
                return ! empty ( $vardefs [ 'fields' ] [ $name ] ) ; else if (empty ( $name ))
            {
                foreach ( $vardefs [ 'fields' ] as $def )
                {
                    if ($def [ 'type' ] == $type)
                        return true ;
                }
                return false ;
            } else
                return (! empty ( $vardefs [ 'fields' ] [ $name ] ) && ($vardefs [ 'fields' ] [ $name ] [ 'type' ] == $type)) ;
        } else
        {
            return false ;
        }
    }

    function getModStrings ($language = 'en_us')
    {
        return $this->mblanguage->getModStrings ( $language ) ;
    }

    function setModStrings ($language = 'en_us' , $mod_strings)
    {
        $language .= '.lang.php' ;
        $this->mblanguage->strings [ $language ] = $mod_strings ;
    }

	function setLabel ($language = 'en_us' , $key , $value)
    {
    	$language .= '.lang.php' ;
        $this->mblanguage->strings [ $language ] [ $key ] = $value ;
        //Ensure this key exists in all languages
        foreach ($this->mblanguage->strings as $lang => $values) {
        	if (empty($values[$key])) {
        		$this->mblanguage->strings[$lang][$key] = $value;
        	}
        }
    }

    function deleteLabel ($language = 'en_us' , $key)
    {
   		foreach ($this->mblanguage->strings as $lang => $values) {
        	if (!empty($values[$key])) {
        		unset($this->mblanguage->strings[$lang][$key]);
        	}
        }
    }

    /**
     * Required for an MB module to work with Dynamic fields
     */
	function addLabel ( $displayLabel)
    {
        $this->setLabel('en_us', $this->getDBName($displayLabel, false), translate($displayLabel));
        $this->save();
    }

    function getLabel ($language = 'en_us' , $key)
    {
        $language .= '.lang.php' ;
        if (empty ( $this->mblanguage->strings [ $language ] [ $key ] ))
        {

            return '' ;
        }
        return $this->mblanguage->strings [ $language ] [ $key ] ;

    }

    function getAppListStrings ($language = 'en_us')
    {
        return $this->mblanguage->getAppListStrings ( $language ) ;
    }

    function setAppListStrings ($language = 'en_us' , $app_list_strings)
    {
        $language .= '.lang.php' ;
        $this->mblanguage->appListStrings [ $language ] = $app_list_strings ;
    }

    function setDropDown ($language = 'en_us' , $key , $value)
    {
        $language .= '.lang.php' ;
        $this->mblanguage->appListStrings [ $language ] [ $key ] = $value ;
    }

    function deleteDropDown ($language = 'en_us' , $key)
    {
        $language .= '.lang.php' ;
        unset ( $this->mblanguage->appListStrings [ $language ] [ $key ] ) ;
    }

    function save ()
    {
        $this->path = $this->getModuleDir () ;
        if (mkdir_recursive ( $this->path ))
        {

            $this->setConfigMD5 () ;
            $old_config_md5 = $this->config_md5 ;
            $this->saveConfig () ;
            $this->getVardefs () ;
            $this->mbvardefs->save ( $this->key_name ) ;
            $this->relationships->save () ;
            $this->copyMetaData () ;
            $this->copyDashlet () ;
            $this->copyViews() ;
            // Bug 56675 - Clients directory not copied over
            // When clients were split apart from metadata, there was no accounting
            // for that here. This accounts for that
            $this->copyClients();
            // End bug 56675
            if (0 != strcmp ( $old_config_md5, $this->config_md5 ))
            {
                $this->mblanguage->reload () ;
            }
            $this->mblanguage->label = $this->config [ 'label' ] ;
            $this->mblanguage->label_singular = !empty($this->config['label_singular']) ? $this->config['label_singular'] : $this->config['label'];
            //pass in the key_name incase it has changed mblanguage will check if it is different and handle it accordingly
            $this->mblanguage->save ( $this->key_name ) ;

            if (! file_exists ( $this->package_path . "/icons/icon_" . ucfirst ( $this->key_name ) . ".gif" ))
            {
                $this->createIcon () ;
            }
            $this->errors = array_merge ( $this->errors, $this->mbvardefs->errors ) ;

        }
    }

    function copyDashlet() {
    	$templates = array_reverse ( $this->config [ 'templates' ], true ) ;
        foreach ( $templates as $template => $a )
        {
            if (file_exists ( MB_TEMPLATES . '/' . $template . '/Dashlets/Dashlet' ))
            {

            	$this->copyMetaRecursive ( MB_TEMPLATES . '/' . $template . '/Dashlets/Dashlet', $this->path . '/Dashlets/' . $this->key_name . 'Dashlet/' ) ;

            }
        }
    }

    function copyViews() {
        $templates = array_reverse ( $this->config [ 'templates' ], true ) ;
        foreach ( $templates as $template => $a )
        {
            if (file_exists ( MB_TEMPLATES . '/' . $template . '/views' ))
            {
                $this->copyMetaRecursive ( MB_TEMPLATES . '/' . $template . '/views', $this->path . '/views/' ) ;
            }
        }
    }

    function copyCustomFiles ( $from , $to )
    {
    	$d = dir ( $from ) ;
        while ( $filename = $d->read () )
        {
        	if (substr ( $filename, 0, 1 ) == '.')
            	continue ;
           	if ( $filename != 'metadata' && $filename != 'Dashlets' && $filename != 'relationships' && $filename != 'language' && $filename != 'config.php' && $filename != 'relationships.php' && $filename != 'vardefs.php' )
           		copy_recursive ( "$from/$filename" , "$to/$filename" ) ;
        }
    }

    function copyMetaData ()
    {
        $templates = array_reverse ( $this->config [ 'templates' ], true ) ;
        foreach ( $templates as $template => $a )
        {
            if (file_exists ( MB_TEMPLATES . '/' . $template . '/metadata' ))
            {
                $this->copyMetaRecursive ( MB_TEMPLATES . '/' . $template . '/metadata', $this->path . '/metadata/' ) ;

            }
        }
    }

    function copyMetaRecursive ($from , $to , $overwrite = false)
    {
        if (! file_exists ( $from ))
            return ;
        if (is_dir ( $from ))
        {
            $findArray = array ( '<module_name>' , '<_module_name>' , '<MODULE_NAME>' , '<object_name>' , '<_object_name>' , '<OBJECT_NAME>' );
            $replaceArray = array ( $this->key_name , strtolower ( $this->key_name ) , strtoupper ( $this->key_name ) ,
            						$this->key_name , strtolower ( $this->key_name ) , strtoupper ( $this->key_name ) );
        	mkdir_recursive ( $to ) ;
            $d = dir ( $from ) ;

            // Clean up to to make sure the path is clean
            $to = rtrim($to, '/') . '/';
            while ( $e = $d->read () )
            {
                if (substr ( $e, 0, 1 ) == '.')
                    continue ;
                $nfrom = $from . '/' . $e ;
                $nto = $to . str_replace ( 'm-n-', $this->key_name, $e ) ;
                if (is_dir ( $nfrom ))
                {
                    $this->copyMetaRecursive ( $nfrom, $nto, $overwrite ) ;
                } else
                {
                    if ($overwrite || ! file_exists ( $nto ))
                    {
                        $contents = file_get_contents ( $nfrom ) ;
                        $contents = str_replace ( $findArray, $replaceArray, $contents ) ;
                        $fw = sugar_fopen ( $nto, 'w' ) ;
                        fwrite ( $fw, $contents ) ;
                        fclose ( $fw ) ;
                    }
                }
            }

        }
    }

    /**
     * Bug 56675
     *
     * Copies the clients directory from the sugar object this module is based on.
     * This method is inspired heavily by copyMetaData as at one time the client
     * view defs were actually part of metadata.
     *
     * Bug 57259
     *
     * Only copy mobile clients because all clients would include portal clients
     *
     * Adapted for Sugar7 to include base clients as well.
     */
    public function copyClients() {
        $templates = array_reverse($this->config['templates'], true);
        foreach ($templates as $template => $a) {
            foreach (array('base', 'mobile') as $client) {
                if (file_exists(MB_TEMPLATES . '/' . $template . '/clients/' . $client)) {
                    $this->copyMetaRecursive(MB_TEMPLATES . '/' . $template . '/clients/' . $client, $this->path . '/clients/' . $client);
                }
            }
        }
    }

    /**
     * Ensures that all required metadata files exist or copies them from the templates if they don't exist.
     */
    protected function verifyMetadata() {
        //Abort if we have never been saved
        if (empty($this->name) || !file_exists($this->path))
            return;

        $rebuildClients = false;
        foreach (array('base', 'mobile') as $client) {
            if (!file_exists($this->path . '/clients/' . $client)) {
                $rebuildClients = true;
                break;
            }
        }
        if ($rebuildClients) {
            $this->copyClients();
        }
    }

    function saveConfig ()
    {
        $header = file_get_contents ( 'modules/ModuleBuilder/MB/header.php' ) ;
        if (! write_array_to_file ( 'config', $this->config, $this->path . '/config.php', 'w', $header ))
        {
            $this->errors [] = 'Could not save config to ' . $this->path . '/config.php' ;
        }
        $this->setConfigMD5 () ;
    }

    function setConfigMD5 ()
    {
        if (file_exists ( $this->path . '/config.php' ))
            $this->config_md5 = md5 ( base64_encode ( serialize ( $this->config ) ) ) ;
    }

    function build ($basepath)
    {
        global $app_list_strings;
        $path = $basepath . '/modules/' . $this->key_name ;
        if (mkdir_recursive ( $path ))
        {
            $this->createClasses ( $path ) ;
            $this->createMenu ( $path ) ;
            $this->copyCustomFiles ( $this->path , $path ) ;
            $this->copyMetaRecursive ( $this->path . '/metadata/', $path . '/metadata/', true ) ;
            $this->copyMetaRecursive ( $this->path . '/Dashlets/' . $this->key_name . 'Dashlet/',
            						   $path . '/Dashlets/' . $this->key_name . 'Dashlet/', true ) ;

            // Add in clients directory building
            $this->copyMetaRecursive($this->path . '/clients/', $path . '/clients/', true);
            $app_list_strings['moduleList'][$this->key_name] = $this->mblanguage->label;
            $this->relationships->build ( $basepath ) ;
            $this->mblanguage->build ( $path ) ;
        }
    }

    /**
     * Generate module classes:
     * - <MODULE_NAME>_sugar.php Bean controller that shouldn't be customized
     * - <MODULE_NAME>.php Bean controller where to put customizations
     * - vardefs.php Bean vardefs
     * - studio.php This file adds support for Studio
     *
     * @param string $path Path to the custom module.
     */
    function createClasses($path)
    {
        $class = array();
        $class['name'] = $this->key_name;
        $class['table_name'] = strtolower($class['name']);
        $class['extends'] = 'Basic';
        $class['requires'] = array();

        $class['team_security'] = !empty($this->config['team_security']);

        $class['audited'] = !empty($this->config['audit']);

        $class['activity_enabled'] = !empty($this->config['activity_enabled']);

        $class['acl'] = !empty($this->config['acl']);

        $class['templates'] = "'basic'";
        foreach ($this->iTemplate as $template) {
            if (!empty($this->config[$template])) {
                $class['templates'] .= ",'$template'";
            }
        }
        foreach ($this->config['templates'] as $template => $a) {
            if ($template == 'basic') {
                continue;
            }
            $class['templates'] .= ",'$template'";
            $class['extends'] = ucFirst($template);
            $class['requires'][] = MB_TEMPLATES . '/' . $template . '/' . ucfirst($template) . '.php';
        }
        $class['importable'] = $this->config['importable'];
        $this->mbvardefs->updateVardefs();
        $class['fields'] = $this->mbvardefs->vardefs['fields'];
        $class['fields_string'] = var_export_helper($this->mbvardefs->vardef['fields']);
        $relationship = array();
        $class['relationships'] = var_export_helper($this->mbvardefs->vardef['relationships']);
        $smarty = new Sugar_Smarty ();
        $smarty->left_delimiter = '{{';
        $smarty->right_delimiter = '}}';
        $smarty->assign('class', $class);

        //Generate <MODULE_NAME>_sugar.php
        $fp = sugar_fopen($path . '/' . $class['name'] . '_sugar.php', 'w');
        fwrite($fp, $smarty->fetch('modules/ModuleBuilder/tpls/MBModule/Class.tpl'));
        fclose($fp);
        //Generate vardefs.php
        $fp = sugar_fopen($path . '/vardefs.php', 'w');
        fwrite($fp, $smarty->fetch('modules/ModuleBuilder/tpls/MBModule/vardef.tpl'));
        fclose($fp);

        //Generate <MODULE_NAME>.php
        if (!file_exists($path . '/' . $class['name'] . '.php')) {
            $fp = sugar_fopen($path . '/' . $class['name'] . '.php', 'w');
            fwrite($fp, $smarty->fetch('modules/ModuleBuilder/tpls/MBModule/DeveloperClass.tpl'));
            fclose($fp);
        }
        if (!file_exists($path . '/metadata')) {
            mkdir_recursive($path . '/metadata');
        }
        //Generate studio.php
        if (!empty($this->config['studio'])) {
            $fp = sugar_fopen($path . '/metadata/studio.php', 'w');
            fwrite($fp, $smarty->fetch('modules/ModuleBuilder/tpls/MBModule/Studio.tpl'));
            fclose($fp);
        } else if (file_exists($path . '/metadata/studio.php')) {
            unlink($path . '/metadata/studio.php');
        }
    }

    function createMenu($path)
    {
        $s = new Sugar_Smarty();
        $s->assign('moduleName', $this->key_name);
        $s->assign('showVcard', isset($this->config['templates']['person']));
        $s->assign('showImport', $this->config['importable']);

        $menu = $s->fetch(
            'modules/ModuleBuilder/tpls/clients/base/menus/header/header.tpl'
        );

        $target = "$path/clients/base/menus/header/header.php";

        mkdir_recursive(
            dirname($target)
        );

        $fp = sugar_fopen($target, 'w');
        fwrite($fp, $menu);
        fclose($fp);
    }

    function addInstallDefs (&$installDefs)
    {
        $name = $this->key_name ;
        $installDefs [ 'copy' ] [] = array ( 'from' => '<basepath>/SugarModules/modules/' . $name , 'to' => 'modules/' . $name ) ;
        $installDefs [ 'beans' ] [] = array ( 'module' => $name , 'class' => $name , 'path' => 'modules/' . $name . '/' . $name . '.php' , 'tab' => $this->config [ 'has_tab' ] ) ;
        $this->relationships->addInstallDefs ( $installDefs ) ;
    }

    function getNodes ()
    {

        $lSubs = array ( ) ;
        $psubs = $this->getProvidedSubpanels () ;
        foreach ( $psubs as $sub )
        {
            $subLabel = $sub ;
            if ($subLabel == 'default')
            {
                $subLabel = $GLOBALS [ 'mod_strings' ] [ 'LBL_DEFAULT' ] ;
            }
            $lSubs [] = array ( 'name' => $subLabel , 'type' => 'list' , 'action' => 'module=ModuleBuilder&MB=true&action=editLayout&view=ListView&view_module=' . $this->name . '&view_package=' . $this->package . '&subpanel=' . $sub . '&subpanelLabel=' . $subLabel . '&local=1' ) ;
        }

		$popups = array( );
        $popups [] = array('name' => translate('LBL_POPUPLISTVIEW') , 'type' => 'popuplistview' , 'action' => 'module=ModuleBuilder&action=editLayout&view=popuplist&view_module=' . $this->name . '&view_package=' . $this->package );
		$popups [] = array('name' => translate('LBL_POPUPSEARCH') , 'type' => 'popupsearch' , 'action' => 'module=ModuleBuilder&action=editLayout&view=popupsearch&view_module=' . $this->name . '&view_package=' . $this->package );

        $layouts = array (
            array ( 'name' => translate('LBL_RECORDVIEW') , 'type' => 'record' , 'action' => 'module=ModuleBuilder&MB=true&action=editLayout&view='.MB_RECORDVIEW.'&view_module=' . $this->name . '&view_package=' . $this->package ) ,
            array ( 'name' => translate('LBL_LISTVIEW') , 'type' => 'list' , 'action' => 'module=ModuleBuilder&MB=true&action=editLayout&view='.MB_LISTVIEW.'&view_module=' . $this->name . '&view_package=' . $this->package ) ,
            array ( 'name' => translate('LBL_POPUP') , 'type' => 'Folder', 'children' => $popups, 'action' => 'module=ModuleBuilder&MB=true&action=wizard&view=popup&view_module=' . $this->name . '&view_package=' . $this->package  ),
            );

        $children = array (
        	array ( 'name' => translate('LBL_FIELDS') , 'action' => 'module=ModuleBuilder&action=modulefields&view_module=' . $this->name . '&view_package=' . $this->package ) ,
        	array ( 'name' => translate('LBL_LABELS') , 'action' => 'module=ModuleBuilder&action=modulelabels&view_module=' . $this->name . '&view_package=' . $this->package ) ,
        	array ( 'name' => translate('LBL_RELATIONSHIPS') , 'action' => 'module=ModuleBuilder&action=relationships&view_module=' . $this->name . '&view_package=' . $this->package ) ,
        	array ( 'name' => translate('LBL_LAYOUTS') , 'type' => 'Folder' , 'action' => "module=ModuleBuilder&action=wizard&view_module={$this->name}&view_package={$this->package}&MB=1" , 'children' => $layouts ) ,
        	array ( 'name' => translate('LBL_WIRELESSLAYOUTS') , 'type' => 'Folder' , 'action' => "module=ModuleBuilder&action=wizard&view=wirelesslayouts&view_module={$this->name}&view_package={$this->package}&MB=1" , 'children' => $this->getWirelessLayouts() )
        	) ;

        if (count ( $lSubs ) > 0)
        {
            $children [] = array ( 'name' => translate('LBL_AVAILABLE_SUBPANELS') , 'type' => 'folder' , 'children' => $lSubs ) ;
        }

        $nodes = array ( 'name' => $this->name , 'children' => $children , 'action' => 'module=ModuleBuilder&action=module&view_module=' . $this->name . '&view_package=' . $this->package ) ;

        return $nodes ;
    }

    function getWirelessLayouts ()
    {
        $nodes [ translate ('LBL_WIRELESSEDITVIEW') ] = array (
            'name' => translate('LBL_WIRELESSEDITVIEW') ,
            'type' => MB_WIRELESSEDITVIEW,
            'action' => 'module=ModuleBuilder&MB=true&action=editLayout&view='.MB_WIRELESSEDITVIEW."&view_module={$this->name}&view_package={$this->package}" ,
            'imageTitle' => 'EditView' ,
            'help' => "viewBtn".MB_WIRELESSEDITVIEW ,
            'size' => '48'
        ) ;
        $nodes [ translate('LBL_WIRELESSDETAILVIEW') ] = array (
            'name' => translate('LBL_WIRELESSDETAILVIEW') ,
            'type' => MB_WIRELESSDETAILVIEW,
            'action' => 'module=ModuleBuilder&MB=true&action=editLayout&view='.MB_WIRELESSDETAILVIEW."&view_module={$this->name}&view_package={$this->package}" ,
            'imageTitle' => 'DetailView' ,
            'help' => "viewBtn".MB_WIRELESSDETAILVIEW ,
            'size' => '48'
        ) ;
        $nodes [ translate('LBL_WIRELESSLISTVIEW') ] = array (
            'name' => translate('LBL_WIRELESSLISTVIEW') ,
            'type' => MB_WIRELESSLISTVIEW,
            'action' => 'module=ModuleBuilder&MB=true&action=editLayout&view='.MB_WIRELESSLISTVIEW."&view_module={$this->name}&view_package={$this->package}" ,
            'imageTitle' => 'ListView' ,
            'help' => "viewBtn".MB_WIRELESSLISTVIEW ,
            'size' => '48'
        ) ;
    	return $nodes ;
    }

    function getProvidedSubpanels ()
    {
        $this->providedSubpanels = array () ;

        $subpanelDir = $this->getModuleDir () . '/metadata/subpanels/' ;
        if (file_exists ( $subpanelDir ))
        {
            $f = dir ( $subpanelDir ) ;

            while ( $g = $f->read () )
            {
                // sanity check to confirm that this is a usable subpanel...
                if (substr ( $g, 0, 1 ) != '.' && AbstractRelationships::validSubpanel ( $subpanelDir . $g ))
                {
                    $subname = str_replace ( '.php', '', $g ) ;
                    $this->providedSubpanels [ $subname ] = $subname ;
                }
            }
        }

        return $this->providedSubpanels;
    }

    public static function getTypes()
    {
        $types = array ( ) ;
        $d = dir ( MB_TEMPLATES ) ;
        while ( $e = $d->read () )
        {
            if (substr ( $e, 0, 1 ) != '.')
            {
                $types [ $e ] = $e ;
            }
        }

        return $types ;
    }

    function rename ($new_name)
    {
        $oldModDir = $this->getModuleDir();
        $old_name = $this->key_name;
        $this->name = $new_name ;
        $this->key_name = $this->package_key . '_' . $this->name ;
        if ($old_name === $this->key_name) {
            return;
        }
        $newModDir = $this->getModuleDir();
        if (file_exists($newModDir)) {
            return false ;
        }
        // clean data based on old relationship name before dir is renamed.
        $this->removeRelationships();
        $renamed = rename($oldModDir, $newModDir);
        if ($renamed)
        {
            $this->renameRelationships($newModDir, $old_name, $this->key_name);
            $this->renameMetaData($newModDir, $old_name);
            $this->renameLanguageFiles($newModDir);

        }
        return $renamed ;
    }

    private function removeRelationships()
    {
        $relationships = $this->getRelationships();
        foreach ($relationships->getRelationshipList() as $relationshipName) {
            $relationships->delete($relationshipName);
        }
    }

    public function renameRelationships($newModDir, $oldModName, $newModName)
    {
        if ($oldModName === $newModName) {
            return;
        }
        //bug 39598 Relationship Name Is Not Updated If Module Name Is Changed In Module Builder
        // and BR-4147 lhs module name was not updated when module name is changed
        $relationships = new UndeployedRelationships($newModDir);
        $relationships->renameModule($oldModName, $newModName);
        $relationships->save();
    }

	function renameLanguageFiles ($new_dir , $duplicate = false)
    {

        $this->mblanguage->name = $this->name ;
        $this->mblanguage->path = $new_dir ;
        $this->mblanguage->generateAppStrings () ;
        $this->mblanguage->save ( $this->key_name, $duplicate, true) ;
    }

    /**
     * Rename module name in metadata
     * @param string $new_dir
     * @param string $old_name
     */
    public function renameMetaData ($new_dir, $old_name)
    {
        $GLOBALS [ 'log' ]->debug ( 'MBModule.php->renameMetaData: new_dir=' . $new_dir ) ;
        if (! file_exists ( $new_dir ))
            return ;
        $dir = dir ( $new_dir ) ;
        while ( $e = $dir->read () )
        {
            if (substr ( $e, 0, 1 ) != '.')
            {
                if (is_dir ( $new_dir . '/' . $e ))
                {
                    $this->renameMetaData ( $new_dir . '/' . $e,  $old_name) ;
                }
                if (is_file ( $new_dir . '/' . $e ))
                {
                    $contents = file_get_contents ( $new_dir . '/' . $e ) ;
                    $search_array = array(
                        '/(\$module_name[ ]*=[ ]*\').*(\'[ ]*;)/',
                        '/(\$_module_name[ ]*=[ ]*\').*(\'[ ]*;)/',
                        '/(\$MODULE_NAME[ ]*=[ ]*\').*(\'[ ]*;)/',
                        '/(\$object_name[ ]*=[ ]*\').*(\'[ ]*;)/',
                        '/(\$_object_name[ ]*=[ ]*\').*(\'[ ]*;)/',
                        '/(\$OBJECT_NAME[ ]*=[ ]*\').*(\'[ ]*;)/'
                    );
                    $replace_array = array(
                        '$1' . $this->key_name . '$2',
                        '$1' . strtolower ( $this->key_name ) . '$2',
                        '$1' . strtoupper ( $this->key_name ) . '$2',
                        '$1' . $this->key_name . '$2',
                        '$1' . strtolower ( $this->key_name ) . '$2',
                        '$1' . strtoupper ( $this->key_name ) . '$2',
                    );
                    $contents = preg_replace($search_array, $replace_array, $contents);
                    $search_array = array(
                        "{$old_name}_",
                        "{$old_name}Dashlet"
                    );
                    $replace_array = array(
                        $this->key_name . '_',
                        $this->key_name . 'Dashlet'
                    );
                    $contents = str_replace($search_array, $replace_array, $contents );


                    if ("relationships.php" == $e)
                    {
                        if (!empty($this->config['label'])) {
                            $oldLabel = translate($old_name);
                            $newLabel = $this->config['label'];
                            $contents = $this->replaceDefinition('lhs_label', $oldLabel, $newLabel, $contents);
                            $contents = $this->replaceDefinition('rhs_label', $oldLabel, $newLabel, $contents);
                        }
                    }

                    $fp = sugar_fopen ( $new_dir . '/' . $e, 'w' ) ;
                    fwrite ( $fp, $contents ) ;
                    fclose ( $fp ) ;
                }
            }
        }
    }

    /**
     * Replaces parameter which has old value with the new value in relationship definition
     *
     * @param string $param Parameter name
     * @param mixed $oldValue Old value
     * @param mixed $newValue New value
     * @param string $contents Relationship definition source contents
     *
     * @return string
     */
    protected function replaceDefinition($param, $oldValue, $newValue, $contents)
    {
        $param = preg_quote(var_export($param, true), '/');
        $oldValue = preg_quote(var_export($oldValue, true), '/');
        $newValue = var_export($newValue, true);

        return preg_replace('/(' . $param . '\s*=>\s*)' . $oldValue . '/', '$1' . $newValue, $contents);
    }

    function copy ($new_name)
    {
        $old = $this->getModuleDir () ;

        $count = 0 ;
        $old_name = $this->key_name;
        $this->name = $new_name ;
        $this->key_name = $this->package_key . '_' . $this->name ;
        $new = $this->getModuleDir () ;
        while ( file_exists ( $new ) )
        {
            $count ++ ;
            $this->name = $new_name . $count ;
            $this->key_name = $this->package_key . '_' . $this->name ;
            $new = $this->getModuleDir () ;
        }

        $new = $this->getModuleDir () ;
        $copied = copy_recursive ( $old, $new ) ;

        if ($copied)
        {
            $this->renameRelationships($new, $old_name, $this->key_name);
            $this->renameMetaData ( $new , $old_name) ;
            $this->renameLanguageFiles ( $new, true ) ;
        }
        return $copied ;

    }

    function delete ()
    {
        $this->load();

        // remove module language data from the package
        $this->mblanguage->delete($this->key_name) ;

        // remove module icons from the package
        $icons = array(
            'icon_' . ucfirst($this->key_name) . '_32.png',
            $this->key_name . '.gif',
            'Create' . $this->key_name . '.gif',
            'icon_' . $this->key_name . '_bar_32.png',
        );
        foreach ($icons as $icon) {
            $file = $this->package_path . '/icons/' . $icon;
            if (file_exists($file)) {
                unlink($file);
            }
        }

        // remove the module itself
        return rmdir_recursive($this->getModuleDir());
    }

    /**
     * Populates object from request
     *
     * @param Request $request
     */
    public function populateFromPost(Request $request = null)
    {
        if (!$request) {
            $request = InputValidation::getService();
        }

        foreach ($this->implementable as $key => $_) {
            $this->config[$key] = (bool) $request->getValidInputRequest($key);
        }

        foreach ( $this->always_implement as $key => $value )
        {
            $this->config [ $key ] = true ;
        }

        $type = $request->getValidInputRequest('type');
        if ($type) {
            $this->addTemplate($type);
        }

        $label = $request->getValidInputRequest('label');
        if ($label) {
            $label = htmlspecialchars_decode($label, ENT_QUOTES);
            $label = SugarCleaner::cleanHtml($label, false);
            $this->config['label'] = $label == '' ? $this->name : $label;
        }

        $singularLabel = $request->getValidInputRequest('label_singular');
        if ($singularLabel) {
            $label = htmlspecialchars_decode($singularLabel, ENT_QUOTES);
            $label = SugarCleaner::cleanHtml($label, false);
            $this->config['label_singular'] = $label == '' ? $this->name : $label;
        }

        $this->config['importable'] = (bool) $request->getValidInputRequest('importable');

    }

    /**
     * Return viewdefs for a subpanel by name.
     *
     * @param string $subpanelName Name of the subpanel to get the defs for
     * @param string $client The client to get the defs for
     * @param boolean $bwc Is this a BWC request?
     * @return array
     */
    public function getAvailableSubpanelDef($subpanelName, $client = 'base', $bwc = false)
    {
        if (empty($client)) {
            $client = 'base';
        }
        $filepath = $this->getSubpanelFilePath($subpanelName, $client, $bwc);
        $var = $bwc ? 'subpanel_layout' : 'viewdefs';
        if (file_exists($filepath)) {
            include FileLoader::validateFilePath($filepath);
            if (isset($$var) && is_array($$var)) {
                return $$var;
            }
        }
        return array();
    }


    /**
     * Saves a viewdef to the correct path
     *
     * @param string $subpanelName Name of the subpanel to get the defs for
     * @param array $viewdef The defs to save
     * @param string $client The client to get the defs for
     * @param boolean $bwc Is this a BWC request?
     */
    public function saveAvailableSubpanelDef($subpanelName, array $viewdef, $client = 'base', $bwc = false)
    {
        if (empty($client)) {
            $client = 'base';
        }

        $filepath = $this->getSubpanelFilePath($subpanelName, $client, $bwc);

        $dir = dirname($filepath);
        if (!file_exists($dir) && !mkdir_recursive($dir)) {
            throw new \RuntimeException(sprintf("Could not make directory %s for subpanel %s"), $dir, $subpanelName);
        }

        $GLOBALS['log']->debug("About to save this file to $filepath");
        $GLOBALS['log']->debug(print_r($viewdef, true));

        if ($bwc) {
            $array = 'subpanel_layout';
        } else {
            $array = "viewdefs['{$this->key_name}']['{$client}']['view']['{$subpanelName}']";
        }

        write_array_to_file($array, $viewdef, $filepath);
    }

    function getLocalSubpanelDef ($panelName)
    {

    }

    function createIcon ()
    {
        $icondir = $this->package_path . "/icons" ;
        mkdir_recursive ( $icondir ) ;
        $template = "" ;
        foreach ( $this->config [ 'templates' ] as $temp => $val )
            $template = $temp ;
		copy ( "themes/RacerX/images/icon_{$template}_32.png", "$icondir/icon_" . ucfirst ( $this->key_name ) . "_32.png" ) ;
        copy ( "include/SugarObjects/templates/$template/icons/$template.gif", "$icondir/" . $this->key_name . ".gif" ) ;
        if (file_exists("include/SugarObjects/templates/$template/icons/Create$template.gif"))
        	copy ( "include/SugarObjects/templates/$template/icons/Create$template.gif", "$icondir/Create" . $this->key_name . ".gif" ) ;
        if (file_exists("include/SugarObjects/templates/$template/icons/{$template}_bar_32.png"))
        	copy ( "include/SugarObjects/templates/$template/icons/{$template}_bar_32.png", "$icondir/icon_{$this->key_name}_bar_32.png" ) ;
    }

    function removeFieldFromLayouts ( $fieldName )
    {
        // hardcoded list of types for now, as also hardcoded in a different form in getNodes
        // TODO: replace by similar mechanism to StudioModule to determine the list of available views for this module
        $views = array ('recordview' , 'editview' , 'detailview' , 'listview' , 'basic_search' , 'advanced_search' , 'dashlet' , 'popuplist');
    	foreach ($this->getWirelessLayouts() as $layout)
    		$views[] = $layout['type'];

    	foreach ($views as $type )
        {
            $parser = ParserFactory::getParser( $type , $this->name , $this->package ) ;
            if ($parser->removeField ( $fieldName ) )
                $parser->handleSave(false) ; // don't populate from $_REQUEST, just save as is...
        }
		//Remove the fields in subpanel
        $psubs = $this->getProvidedSubpanels() ;
        foreach ( $psubs as $sub )
        {
			$parser = ParserFactory::getParser( MB_LISTVIEW , $this->name, $this->package ,  $sub) ;
			if ($parser->removeField ( $fieldName ) )
	            $parser->handleSave(false) ;
        }
    }

    /**
     * Returns an array of fields defs with all the link fields for this module.
     * @return array
     */
    public function getLinkFields(){
        $list = $this->relationships->getRelationshipList();
        $field_defs = array();
        foreach($list as $name){
            $rel = $this->relationships->get($name);
            $relFields = $rel->buildVardefs();
            $relDef = $rel->getDefinition();
            $relLabels = $rel->getLabels();
            $relatedModule = $this->key_name == $relDef['rhs_module'] ? $relDef['lhs_module'] : $relDef['rhs_module'];
            if (!empty($relFields[$this->key_name]))
            {
                //Massage the result of getVardefs to look like field_defs
                foreach($relFields[$this->key_name] as $def) {
                    $def['module'] = $relatedModule;
                    $def['translated_label'] = empty($relLabels[$this->key_name][$def['vname']]) ?
                        $name : $relLabels[$this->key_name][$def['vname']];
                    $field_defs[$def['name']] = $def;
                }
            }
        }

        return $field_defs;
    }

    /**
     * Returns a TemplateField object by name
     * Returns a TemplateField object by name or null if field not exists. If type not set use text type as default
     *
     * @param string $name
     * @return TemplateField|null
     *
     */
    public function getField($name)
    {
        $field = null;
        $varDefs = $this->getVardefs();
        if (isset($varDefs['fields'][$name])){
            $fieldVarDefs = $varDefs['fields'][$name];
            if (!isset($fieldVarDefs['type'])){
                $fieldVarDefs['type'] = 'varchar';
            }
            $field = get_widget($fieldVarDefs['type']);
            foreach($fieldVarDefs AS $key => $opt){
                $field->$key = $opt;
            }
        }
        return $field;
    }

    /**
     * remove team field in recordview when team_security is disabled
     */
    public function cleanupLayout()
    {
        if(empty($this->config['team_security'])){
            $this->removeFieldFromLayouts("team_name");
        }
    }

    /**
     * Gets the sugar object template type for this module
     *
     * @return string
     */
    public function getModuleType()
    {
        // The last key in the template array it the type
        $templateKeys = array_keys($this->config['templates']);
        return end($templateKeys);
    }

    /**
     * Gets the MetadataConverter object
     * 
     * @return MetaDataConverter
     */
    public function getMetadataConverter()
    {
        if ($this->mdc === null) {
            $this->mdc = new MetaDataConverter();
        }

        return $this->mdc;
    }

    /**
     * Gets a new subpanel path from the name of the subpanel and client
     * 
     * @param string $name The subpanel name
     * @param string $client The client
     * @param boolean $bwc Is this a BWC request?
     * @return string
     */
    public function getSubpanelFilePath($name, $client, $bwc = false)
    {
        $dir = $this->getModuleDir();
        if ($bwc === true) {
            $path = "$dir/metadata/subpanels/$name.php";
        } else {
            // Only convert the name if it needs it, as it may have already been done
            if (strpos($name, 'subpanel-') === false) {
                $mdc = $this->getMetadataConverter();
                $name = $mdc->fromLegacySubpanelName($name);
            }
            $path = "$dir/clients/$client/views/$name/$name.php";
        }

        return $path;
    }
}
