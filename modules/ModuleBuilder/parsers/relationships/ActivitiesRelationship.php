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
 * Class to manage the metadata for a One-To-Many Relationship
 * The One-To-Many relationships created by this class are a combination of a subpanel and a custom relate field
 * The LHS (One) module will receive a new subpanel for the RHS module. The subpanel gets its data ('get_subpanel_data') from a link field that references a new Relationship
 * The RHS (Many) module will receive a new relate field to point back to the LHS
 *
 * OOB modules implement One-To-Many relationships as:
 *
 * On the LHS (One) side:
 * A Relationship of type one-to-many in the rhs modules vardefs.php
 * A link field in the same vardefs.php with 'relationship'= the relationship name and 'source'='non-db'
 * A subpanel which gets its data (get_subpanel_data) from the link field
 *
 * On the RHS (Many) side:
 * A Relate field in the vardefs, formatted as in this example, which references a link field:
 * 'name' => 'account_name',
 * 'rname' => 'name',
 * 'id_name' => 'account_id',
 * 'vname' => 'LBL_ACCOUNT_NAME',
 * 'join_name'=>'accounts',
 * 'type' => 'relate',
 * 'link' => 'accounts',
 * 'table' => 'accounts',
 * 'module' => 'Accounts',
 * 'source' => 'non-db'
 * A link field which references the shared Relationship
 */
class ActivitiesRelationship extends OneToManyRelationship
{

	protected static $subpanelsAdded = array();
	protected static $labelsAdded = array();

	/*
     * Constructor
     * @param array $definition Parameters passed in as array defined in parent::$definitionKeys
     * The lhs_module value is for the One side; the rhs_module value is for the Many
     */
    function __construct ($definition)
    {
        parent::__construct ( $definition ) ;
    }

    /*
     * BUILD methods called during the build
     */

	/*
     * Define the labels to be added to the module for the new relationships
     * @return array    An array of system value => display value
     */
    function buildLabels($update = false)
    {
        $labelDefinitions = array ( ) ;
        if (!$this->relationship_only )
        {
            if (!isset(ActivitiesRelationship::$labelsAdded[$this->lhs_module])) {
                foreach(getTypeDisplayList() as $typeDisplay)
                {
                    $labelDefinitions [] = array (
                        'module' => 'application',
                        'system_label' => $typeDisplay,
                        'display_label' => array(
                            $this->lhs_module => $this->lhs_label ? $this->lhs_label : ucfirst($this->lhs_module)
                        ),
                    );
                }
            }

            $rhs_display_label = translate($this->rhs_module);

            $lhs_display_label = translate($this->lhs_module);

            $labelDefinitions[] = array (
                'module' => $this->lhs_module ,
                'system_label' => 'LBL_' . strtoupper($this->relationship_name . '_FROM_' . $this->getRightModuleSystemLabel()) . '_TITLE',
                'display_label' => $rhs_display_label
            );
            $labelDefinitions[] = array(
                'module' => $this->rhs_module,
                'system_label' => 'LBL_' . strtoupper($this->relationship_name . '_FROM_' . $this->getLeftModuleSystemLabel()) . '_TITLE',
                'display_label' => $lhs_display_label
            );

            ActivitiesRelationship::$labelsAdded[$this->lhs_module] = true;
        }
        return $labelDefinitions ;
    }


	/*
     * @return array    An array of field definitions, ready for the vardefs, keyed by module
     */
    function buildVardefs ( )
    {
        $vardefs = array ( ) ;

        $vardefs [ $this->rhs_module ] [] = $this->getLinkFieldDefinition ( $this->lhs_module, $this->relationship_name ) ;
        $vardefs [ $this->lhs_module ] [] = $this->getLinkFieldDefinition ( $this->rhs_module, $this->relationship_name ) ;


        return $vardefs ;
    }

    protected function getLinkFieldDefinition ($sourceModule , $relationshipName, $right_side = false, $vname = '', $id_name = false)
    {
        $vardef = array ( ) ;
        $vardef [ 'name' ] = $relationshipName;
        $vardef [ 'type' ] = 'link' ;
        $vardef [ 'relationship' ] = $relationshipName ;
        $vardef [ 'source' ] = 'non-db' ;
        $vardef [ 'module' ] = $sourceModule ;
        $vardef [ 'bean_name' ] = BeanFactory::getObjectName($sourceModule) ;
        $vardef [ 'vname' ] = strtoupper("LBL_{$relationshipName}_FROM_{$sourceModule}_TITLE");
        return $vardef ;
    }

    /*
     * Don't add fields to layouts we will use the flex relate
     * @return array    An array of module => fieldname
     */
    function buildFieldsToLayouts ()
    {
        return array();
    }

 	function buildSubpanelDefinitions ()
    {
        if ($this->relationship_only || isset(ActivitiesRelationship::$subpanelsAdded[$this->lhs_module]))
            return array () ;

        ActivitiesRelationship::$subpanelsAdded[$this->lhs_module] = true;
        $relationshipName = substr($this->relationship_name, 0, strrpos($this->relationship_name, '_'));
        return array( $this->lhs_module => array (
        			  'activities' => $this->buildActivitiesSubpanelDefinition ( $relationshipName ),
        			  'history' => $this->buildHistorySubpanelDefinition ( $relationshipName ) ,
        			));
    }

    /*
     * @return array    An array of relationship metadata definitions
     */
    function buildRelationshipMetaData ()
    {
        $relationshipName = $this->definition['relationship_name'];
        $relMetadata = array ( ) ;
        $relMetadata['lhs_module'] = $this->definition['lhs_module'] ;
        $relMetadata['lhs_table'] = $this->getTablename($this->definition['lhs_module']) ;
        $relMetadata['lhs_key'] = 'id' ;
        $relMetadata['rhs_module'] = $this->definition['rhs_module'] ;
        $relMetadata['rhs_table'] = $this->getTablename($this->definition['rhs_module']) ;
        $relMetadata['relationship_role_column_value'] = $this->definition['lhs_module'] ;
            
        if ( $this->definition['rhs_module'] != 'Emails' ) {
            $relMetadata['rhs_key'] = 'parent_id';
            $relMetadata['relationship_type'] = 'one-to-many';
            $relMetadata['relationship_role_column'] = 'parent_type';
        } else {
            $relMetadata['rhs_key'] = 'id';
            $relMetadata['relationship_type'] = 'many-to-many';
            $relMetadata['join_table'] = 'emails_beans';
            $relMetadata['join_key_rhs'] = 'email_id';
            $relMetadata['join_key_lhs'] = 'bean_id';
            $relMetadata['relationship_role_column'] = 'bean_module';
        }

    	return array( $this->lhs_module => array(
    		'relationships' => array ($relationshipName => $relMetadata),
    		'fields' => '', 'indices' => '', 'table' => '')
    	) ;
    }

/*
     * Shortcut to construct an Activities collection subpanel
     * @param AbstractRelationship $relationship    Source relationship to Activities module
     */
    protected function buildActivitiesSubpanelDefinition ( $relationshipName )
    {
		return array (
            'order' => 10 ,
            'sort_order' => 'desc' ,
            'sort_by' => 'date_start' ,
            'title_key' => 'LBL_ACTIVITIES_SUBPANEL_TITLE' ,
            'type' => 'collection' ,
            'subpanel_name' => 'activities' , //this value is not associated with a physical file
            'module' => 'Activities' ,
            'top_buttons' => array (
                array ( 'widget_class' => 'SubPanelTopCreateTaskButton' ) ,
                array ( 'widget_class' => 'SubPanelTopScheduleMeetingButton' ) ,
                array ( 'widget_class' => 'SubPanelTopScheduleCallButton' ) ,
                array ( 'widget_class' => 'SubPanelTopComposeEmailButton' ) ) ,
                'collection_list' => array (
                    'meetings' => array (
                        'module' => 'Meetings' ,
                        'subpanel_name' => 'ForActivities' ,
                        'get_subpanel_data' => $relationshipName. '_meetings' ) ,
                    'tasks' => array (
                        'module' => 'Tasks' ,
                        'subpanel_name' => 'ForActivities' ,
                        'get_subpanel_data' => $relationshipName. '_tasks' ) ,
                    'calls' => array (
                        'module' => 'Calls' ,
                        'subpanel_name' => 'ForActivities' ,
                        'get_subpanel_data' => $relationshipName. '_calls' ) ) ) ;
    }

    /*
     * Shortcut to construct a History collection subpanel
     * @param AbstractRelationship $relationship    Source relationship to Activities module
     */
    protected function buildHistorySubpanelDefinition ( $relationshipName )
    {
        return array (
            'order' => 20 ,
            'sort_order' => 'desc' ,
            'sort_by' => 'date_modified' ,
            'title_key' => 'LBL_HISTORY' ,
            'type' => 'collection' ,
            'subpanel_name' => 'history' , //this values is not associated with a physical file.
            'module' => 'History' ,
            'top_buttons' => array (
                array ( 'widget_class' => 'SubPanelTopCreateNoteButton' ) ,
				array ( 'widget_class' => 'SubPanelTopArchiveEmailButton'),
                array ( 'widget_class' => 'SubPanelTopSummaryButton' ) ) ,
                'collection_list' => array (
                    'meetings' => array (
                        'module' => 'Meetings' ,
                        'subpanel_name' => 'ForHistory' ,
                        'get_subpanel_data' => $relationshipName. '_meetings' ) ,
                    'tasks' => array (
                        'module' => 'Tasks' ,
                        'subpanel_name' => 'ForHistory' ,
                        'get_subpanel_data' => $relationshipName. '_tasks' ) ,
                    'calls' => array (
                        'module' => 'Calls' ,
                        'subpanel_name' => 'ForHistory' ,
                        'get_subpanel_data' => $relationshipName. '_calls' ) ,
                    'notes' => array (
                        'module' => 'Notes' ,
                        'subpanel_name' => 'ForHistory' ,
                        'get_subpanel_data' => $relationshipName. '_notes' ) ,
                    'emails' => array (
                        'module' => 'Emails' ,
                        'subpanel_name' => 'ForHistory' ,
                        'get_subpanel_data' => $relationshipName. '_emails' ) ) )  ;
    }

    /*
     * Builds views for sidecar dashlets
     * @return array an array of files and file contents to write
     */
    protected function buildSidecarDashletMeta( $relationshipName )
    {
        $fileBase = "<?php\n/* File autogenerated by SugarCRM in ActivitesRelationship.php / buildSidecarDashletMeta */\n\n";
        $files = array();
        $files['clients/base/views/history/history.php'] = $fileBase .
            "\$coreDefs = MetaDataFiles::loadSingleClientMetadata('view','history');
\$coreDefs['dashlets'][0]['filter']['module'] = array('{$this->lhs_module}');
\$coreDefs['tabs'][0]['link'] = '{$relationshipName}_meetings';
\$coreDefs['tabs'][1]['link'] = '{$relationshipName}_emails';
\$coreDefs['tabs'][2]['link'] = '{$relationshipName}_calls';
\$coreDefs['custom_toolbar']['buttons'][0]['buttons'][0]['params']['link'] = '{$relationshipName}_emails';
\$viewdefs['{$this->lhs_module}']['base']['view']['history'] = \$coreDefs;\n";

        $files['clients/base/views/planned-activities/planned-activities.php'] = $fileBase .
            "\$coreDefs = MetaDataFiles::loadSingleClientMetadata('view','planned-activities');
\$coreDefs['dashlets'][0]['filter']['module'] = array('{$this->lhs_module}');
\$coreDefs['tabs'][0]['link'] = '{$relationshipName}_meetings';
\$coreDefs['tabs'][1]['link'] = '{$relationshipName}_calls';
\$coreDefs['custom_toolbar']['buttons'][0]['buttons'][0]['params']['link'] = '{$relationshipName}_meetings';
\$coreDefs['custom_toolbar']['buttons'][0]['buttons'][1]['params']['link'] = '{$relationshipName}_calls';
\$viewdefs['{$this->lhs_module}']['base']['view']['planned-activities'] = \$coreDefs;\n";

        $files['clients/base/views/attachments/attachments.php'] = $fileBase .
            "\$coreDefs = MetaDataFiles::loadSingleClientMetadata('view','attachments');
\$coreDefs['dashlets'][0]['filter']['module'] = array('{$this->lhs_module}');
\$coreDefs['dashlets'][0]['config']['link'] = '{$relationshipName}_notes';
\$coreDefs['dashlets'][0]['preview']['link'] = '{$relationshipName}_notes';
\$viewdefs['{$this->lhs_module}']['base']['view']['attachments'] = \$coreDefs;\n";

        $files['clients/base/views/active-tasks/active-tasks.php'] = $fileBase .
            "\$coreDefs = MetaDataFiles::loadSingleClientMetadata('view','active-tasks');
\$coreDefs['dashlets'][0]['filter']['module'] = array('{$this->lhs_module}');
\$coreDefs['custom_toolbar']['buttons'][0]['buttons'][0]['params']['link'] = '{$relationshipName}_tasks';
\$coreDefs['tabs'][0]['link'] = '{$relationshipName}_tasks';
\$coreDefs['tabs'][1]['link'] = '{$relationshipName}_tasks';
\$viewdefs['{$this->lhs_module}']['base']['view']['active-tasks'] = \$coreDefs;\n";

        $files['clients/base/views/inactive-tasks/inactive-tasks.php'] = $fileBase .
            "\$coreDefs = MetaDataFiles::loadSingleClientMetadata('view','inactive-tasks');
\$coreDefs['dashlets'][0]['filter']['module'] = array('{$this->lhs_module}');
\$coreDefs['custom_toolbar']['buttons'][0]['buttons'][0]['params']['link'] = '{$relationshipName}_tasks';
\$coreDefs['tabs'][0]['link'] = '{$relationshipName}_tasks';
\$coreDefs['tabs'][1]['link'] = '{$relationshipName}_tasks';
\$viewdefs['{$this->lhs_module}']['base']['view']['inactive-tasks'] = \$coreDefs;\n";

        return array($this->lhs_module => $files);
    }

    public function buildSidecarSubpanelDefinitions()
    {
        $baseRelName = substr($this->relationship_name, 0, strrpos($this->relationship_name, '_'));
        $label = 'LBL_'.strtoupper($this->rhs_module).'_SUBPANEL_TITLE';
        $linkName = $baseRelName.'_'.strtolower($this->rhs_module);
        switch ($this->rhs_module) {
            case 'Calls':
                $order = 110;
                break;
            case 'Meetings':
                $order = 120;
                break;
            case 'Notes':
                $order = 130;
                break;
            case 'Tasks':
                $order = 140;
                break;
            case 'Emails':
                $order = 150;
                break;
            default:
                $order = 160;
                $GLOBALS['log']->error("Unexpected activity relationship for module {$this->rhs_module}");
        }

        $subpanels[$this->lhs_module][] = array(
            'order' => $order,
            'module' => $this->rhs_module,
            'subpanel_name' => 'default',
            'sort_order' => 'desc',
            'sort_by' => 'date_modified',
            'title_key' => $label,
            'get_subpanel_data' => $linkName,
        );

        return $subpanels;
    }

    public function buildClientFiles()
    {
        $relationshipName = substr($this->relationship_name, 0, strrpos($this->relationship_name, '_'));

        return $this->buildSidecarDashletMeta($relationshipName);
    }
}
