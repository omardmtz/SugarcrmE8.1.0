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
 * The DashletManager is a way for searching for Dashlets installed on the current system as well as providing a method for accessing
 * a specific Dashlets information. It also allows for instantiating an instance of a Dashlet.
 * @author mitani
 * @api
 */
class DashletManager
{
	private static $dashletCache = array();
	private static $dashDefs = array();

	/**
	 * All methods should be called statically prevent instantiation of this class
	 */
	private function __construct()
	{
	}

	/**
	 * Allows for searching for a specific dashlet available on the installation to add to a given layout. Search may filter on name, category and type
	 * Returns an array in the format
	 * array(
	 * 	'dashlet1-id'=>array('icon'=>icon-image-path ,'name'=>name of dashlet,  'description'=>description of dashlet),
	 *	'dashlet2-id'=>array('icon'=>icon-image-path ,'name'=>name of dashlet,  'description'=>description of dashlet),
	 *	'dashlet3-id'=>array('icon'=>icon-image-path ,'name'=>name of dashlet,  'description'=>description of dashlet),
	 *	...
	 * );
	 * @param string $name - name to search for by default it searches returns all dashlets
	 * @param string $module - module of dashlet to search in by default it searches all modules
	 * @param string $category - the category that the dashlet falls into. Acceptable values: module, portal, charts, tools, misc
	 * @param string $type - type of dashlet to search for Standard, FocusBean by default it searches for both. Acceptable values: standard, bean, both
	 * @static
	 * @return Associative Array containing search results keyed by dashlet id
	 *
	 */
	public static function search(
	    $name = null,
	    $module = null,
	    $category = null,
	    $type = null)
	{
	}

	/**
	 * Provides information for a given dashlet in the form of
	 * array(
	 * 		'name'=> dashlet name
	 * 		'type'=> dashlet type
	 * 		'category'=> dashlet category
	 * 		'description'=> description
	 *		'author'=> author
	 *		'version'=>version
	 *		'date_published'=>date published
	 *		...
	 * ),
	 * @param GUID $dashletID - ID of the dashlet you wish to get information on
	 * @static
	 * @return Associative Array containg all meta-data about a given dashlet
	 */
	public static function info(
	    $dashletID
	    )
	{
	}

	/**
	 * Returns an instance of a Dashlet based on the provided DashletID
	 * @param GUID $dashletID - ID of Dashlet to be instantiated
	 * @param GUID $subpanelDefID - ID from the definiiton
	 * @return Dashlet
	 */

    public static function getDashlet(
        $dashletID,
        $options = array()
    ) {
	    DashletManager::_loadCache();
	    require_once(DashletManager::$dashletCache[$dashletID]['file']);
        if(empty($options)){
            $options = (isset(DashletManager::$dashletCache[$dashletID]['options'])) ? DashletManager::$dashletCache[$dashletID]['options'] : array();
        }
        $dashletClassName = DashletManager::$dashletCache[$dashletID]['class'];
        $dashlet = new $dashletClassName(rand(0, 100000), $options);

        return $dashlet;
	}


	public static function getDashletFromSubDef(
	    $subdefID,
	    $focusBean
	    )
	{
        if(empty(DashletManager::$dashDefs[$focusBean->module_dir])){
            $dashletdefs = array();
            $filePath = SugarAutoLoader::existingCustomOne('modules/' . $focusBean->module_dir . '/metadata/subdashdefs.php');
            if(!empty($filePath)) {
                include $filePath;
            }
            if(!empty($dashletdefs)){
                DashletManager::$dashDefs[$focusBean->module_dir] = $dashletdefs;
            }
        }

        if(isset($_SESSION['dlets'][$subdefID]))$subdefID = $_SESSION['dlets'][$subdefID];
        if(isset(DashletManager::$dashDefs[$focusBean->module_dir]['dashlets'][$subdefID])){
            $dashID = DashletManager::$dashDefs[$focusBean->module_dir]['dashlets'][$subdefID]['type'];
            $options = !empty(DashletManager::$dashDefs[$focusBean->module_dir]['dashlets'][$subdefID]['options'])? DashletManager::$dashDefs[$focusBean->module_dir]['dashlets'][$subdefID]['options']:array();
            $dashlet =  DashletManager::getDashlet($dashID, $options);
            $_SESSION['dlets'][$dashlet->id] = $subdefID;
            return $dashlet;
        }else{
            throw new Exception('Dashlet Not Found : ' . $subdefID, '4002');
        }
	}


	private static function _loadCache(
	    $refresh = false
	    )
	{
		if($refresh || !is_file(sugar_cached('dashlets/dashlets.php'))) {
            $dc = new DashletCacheBuilder();
            $dc->buildCache();
		}

		$dashletsFiles = array();
		require_once(sugar_cached('dashlets/dashlets.php'));
		DashletManager::$dashletCache = $dashletsFiles;

	}
}