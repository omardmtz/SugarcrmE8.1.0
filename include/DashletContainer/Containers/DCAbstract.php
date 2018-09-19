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
 * 
 * This class provides the base implementation of the DashletContainer. It handles the passing of data to the DCMetaDataParser. The DCMetaDataParser loads and stores meta-data shared between all Dashlet Container Layouts.
 * This information includes which Dashlets are avaialble and basic grouping and positioning. This allows for switching between Dashlet
 * Container Layouts and a basic preservation of the Layouts. Each DashletContainer may also store their own custom meta-data specifying DashletContainer specific layout information
 * @author mitani
 *
 */

abstract class DashletContainer 
{
	/**
	 * The focus bean that is utilized by dashlets
	 *
	 * @var SugarBean focus
	 */
	private $focus = null;
	
	/**
	 * Id of the current container
	 *
	 * @var GUID ID
	 */
	private $id = null;
	
	/**
	 * Instance of the DCMetaDataParser
	 *
	 * @var DCMetaDataParser metaDataParser
	 */
	protected $metaDataParser = null;
	
	/**
	 * Constructor of DashletContainer. It will also instantiate the metaDataParser and layout 
	 *
	 * @param string $metaDataFile - file Path to the meta-data file for the DashletContainer
	 */
	public function __construct()
	{	
	}
	
	/**
	 * Sets the focus bean for the container
	 *
	 * @param SugarBean $bean - the primary focus bean to be used in the Dashlet Container 
	 */
	public function setFocusBean(
	    $bean
	    )
	{
		$this->focus = $bean;
	}
	
	/** 
	 * Returns the containers ID
	 *
	 * @return GUID id
	 */
	public function getID()
	{
		return $this->id;
	}
	
	/**
	 * This function allows for adding a dashlet to a layout.
	 * It calls on DCMetaDataParser for adding the dashlet to the DashletContainer meta-data 
	 * (allowing for the same dashlets to be rendered between different DashletContainers)
	 * 
	 * @param GUID $dashletID - id of the dashlet (not the instance id of the dashlet)
	 * @param GUID $group - id of the group to add the dashlet to
	 * @param int $position - position in the group to add the dashlet to
	 * @param Associative Array $layoutParams - any layout params for the Dashlet Container Layout
	 * @return bool - success or failure of add
	 */
	public function addDashlet(
	    $dashletID, 
	    $group, 
	    $position, 
	    $layoutParams = null
	    )
	{	
	}
	
	/**
	 * Removes a dashlet from a Dashlet Container and the respective Dashlet Container Layout.
	 * 
	 * @param GUID $id - id of the Dashlet instance to remove
	 * @param Associative Array $layoutParams - any layout params specific to this DashletContainer
	 * @return bool - success or failure of remove
	 */
	public function removeDashlet(
	    $id, 
	    $layoutParams = null
	    )
	{	
	}
	
	/**
	 * Moves a dashlet from one position to another in a Dashlet Contianer and it's respective Dashlet Container Layout
	 * 
	 * @param GUID $id - id of the Dashlet instance to move
	 * @param GUID $group - group to move it to 
	 * @param int $position - position in the group to move it to 
	 * @param Associative Array $layoutParams - any layout params to be passed to the Dashely Container Layout
	 * @return bool - success or failure of move
	 */
	public function moveDashlet(
	    $id, 
	    $group, 
	    $position, 
	    $layoutParams = null
	    )
	{	
	}
	
	/**
	 * Returns an associative array containing the HTML code as well as any JS files that need to be loaded in order to render
	 * the container. If there is a function to call onload it should be handled inside one of the JS files using the YUI Event Handler
	 * 
	 * @return Associative Array ('html'=>html code , 'jsfiles'=>array('js1', 'js2', ...));
	 */
	abstract public function getLayout();
	
	/**
	 * Responds to any AJAX response made by JSDCManager. 
	 * 
	 * It expects requests in the following format
	 * 
	 * array(
	 * 	'dashlets'=>array(
	 * 		'dashlet1-id'=>array('method'=>function to call on, 'data'=>data to pass into the function),
	 * 		'dashlet2-id'=>array('method'=>function to call on, 'data'=>data to pass into the function),	
	 * 		...
	 * 	)
	 * )
	 * 
	 * 
	 * It will return the data in the following format 
	 * 
	 * array(
	 * 'dashlets'=>
	 * 		array( 	
	 * 				'dashlet1-id'=> response <string | array of data>
	 * 				'dashlet2-id'=>response <string | array of data> 
	 * 				...
	 * 		)
	 * 'DCM'=>array(
	 * 		'status'=>200 - uses sames responses as  HTML status response
	 * 		'response'=> string or array of data for Dashlet Container Manager to process on the JS side
	 * 		
	 * )
	 * 
	 * 
	 * )
	 *
	 * @return JSON Data
	 */
	public function getAJAXResponse()
	{	
	}
}