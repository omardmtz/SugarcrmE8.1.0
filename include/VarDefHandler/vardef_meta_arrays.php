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

//holds various filter arrays for displaying vardef dropdowns
//You can add your own if you would like

$vardef_meta_array = array(
    // Things that should be handled for ALL cases
    'all' => array(
        'inclusion' => array(),
        'exclusion' => array(
            'module' => [
                'DataPrivacy',
            ],
        ),
        'inc_override' => array(),
        'ex_override' => array(
            'name' => array(
                // Remove the following fields from all lists
                'pmse_bpmnactivity_link',
                'pmse_bpmnartifact_link',
                'pmse_bpmnbound_link',
                'pmse_bpmndata_link',
                'pmse_bpmndiagram_link',
                'pmse_bpmndocumentation_link',
                'pmse_bpmnevent_link',
                'pmse_bpmnextension_link',
                'pmse_bpmnflow_link',
                'pmse_bpmngateway_link',
                'pmse_bpmnlane_link',
                'pmse_bpmnlaneset_link',
                'pmse_bpmnparticipant_link',
                'pmse_bpmnprocess_link',
                'pmse_bpmflow_link',
                'pmse_bpmthread_link',
                'pmse_bpmnotes_link',
                'pmse_bpmrelateddependency_link',
                'pmse_bpmactivityuser_link',
                'pmse_bpmeventdefinition_link',
                'pmse_bpmgatewaydefinition_link',
                'pmse_bpmactivitydefinition_link',
                'pmse_bpmactivitystep_link',
                'pmse_bpmformaction_link',
                'pmse_bpmdynaform_link',
                'pmse_bpmprocessdefinition_link',
                'pmse_bpmconfig_link',
                'pmse_bpmgroup_link',
                'pmse_bpmgroupuser_link',
                'dataprivacy',
            ),
            'module' => [
                'DataPrivacy',
            ],
        ),
    ),
    'standard_display' => array(
        'inclusion' => array(//end inclusion
        ),
        'exclusion' => array(
            'type' => array('id'),
            'name' => array('parent_type', 'deleted'),
            'workflow' => array(false),
            //end exclusion
        ),
        'inc_override' => array(
            'type' => array('team_list'),
            //end inc_override
        ),
        'ex_override' => array(//end ex_override
        )
        //end standard_display
    ),
//////////////////////////////////////////////////////////////////
    'normal_trigger' => array(
        'inclusion' => array(//end inclusion
        ),
        'exclusion' => array(
            'type' => array('id', 'link', 'datetime', 'date', 'datetimecombo'),
            'custom_type' => array('id', 'link', 'datetime', 'date', 'datetimecombo'),
            'name' => array('assigned_user_name', 'parent_type', 'deleted', 'filename', 'file_mime_type', 'file_url'),
            'source' => array('non-db'),
            'workflow' => array(false),
            //end exclusion
        ),
        'inc_override' => array(
            'type' => array('team_list', 'assigned_user_name'),
            'name' => array('email1', 'assigned_user_id'),
            //end inc_override
        ),
        'ex_override' => array(
            'name' => array('team_name'),
            //end ex_override
        )

        //end normal_trigger
    ),
    //////////////////////////////////////////////////////////////////
    'normal_date_trigger' => array(
        'inclusion' => array(//end inclusion
        ),
        'exclusion' => array(
            'type' => array('id', 'link'),
            'custom_type' => array('id', 'link'),
            'name' => array('assigned_user_name', 'parent_type', 'deleted', 'filename', 'file_mime_type', 'file_url'),
            'source' => array('non-db'),
            'workflow' => array(false),
            //end exclusion
        ),
        'inc_override' => array(
            'type' => array('team_list', 'assigned_user_name'),
            'name' => array('email1', 'assigned_user_id'),
            //end inc_override
        ),
        'ex_override' => array(
            'name' => array('team_name', 'account_name'),
            //end ex_override
        )

        //end normal_trigger
    ),
//////////////////////////////////////////////////////////////////
    'time_trigger' => array(
        'inclusion' => array(//end inclusion
        ),
        'exclusion' => array(
            'type' => array('id', 'link', 'team_list', 'time'),
            'custom_type' => array('id', 'link', 'team_list', 'time'),
            'workflow' => array(false),
            'name' => array(
                'parent_type',
                'team_name',
                'assigned_user_name',
                'parent_type',
                'deleted',
                'filename',
                'file_mime_type',
                'file_url'
            ),
            'source' => array('non-db'),
            //end exclusion
        ),
        'inc_override' => array(//end inc_override
        ),
        'ex_override' => array(
            'name' => array('date_entered'),
            //end ex_override
        )

        //end time_trigger
    ),
//////////////////////////////////////////////////////////////////
    'action_filter' => array(
        'inclusion' => array(//end inclusion
        ),
        'exclusion' => array(
            'type' => array('id', 'link', 'datetime', 'time'),
            'custom_type' => array('id', 'link', 'datetime', 'time'),
            'source' => array('non-db'),
            'name' => array(
                'created_by',
                'parent_type',
                'deleted',
                'assigned_user_name',
                'deleted',
                'filename',
                'file_mime_type',
                'file_url',
                'resource_id'
            ),
            'readonly' => array(true),
            'workflow' => array(false),
            'auto_increment' => array(true),
            'calculated' => array(true),
            //end exclusion
        ),
        'inc_override' => array(
            'type' => array('team_list'),
            'name' => array(
                'assigned_user_id',
                'time_start',
                'date_start',
                'email1',
                'date_due',
                'is_optout'
            ),
            //end inc_override
        ),
        'ex_override' => array(
            'name' => array('team_name', 'account_name'),
            //end ex_override
        )

        //end action_filter
    ),
//////////////////////////////////////////////////////////////////
    'rel_filter' => array(
        'inclusion' => array(
            'type' => array('link'),
            //end inclusion
        ),
        'exclusion' => array(
            'name' => array(
                'direct_reports',
                'accept_status',
                'team_count_link',
                'activities',
                'team_link',
                'email_addresses_primary',
                'email_addresses',
                'archived_emails',
                'reportees',
                'tasks_parent',
                'locked_fields_link',
            ),
            'module' => array(
                'Forecasts',
                'Documents',
                'Products',
                'CampaignLog',
            ),
            'workflow' => array(false),
            //end exclusion
        ),
        'inc_override' => array(
            'name' => array('accounts', 'account', 'member_of'),
            //end inc_override
        ),
        'ex_override' => array(
            //'link_type' => array('one'),
            'name' => array('users'),
            'module' => array('Users'),
            //end ex_override
        )

        //end rel_filter
    ),
///////////////////////////////////////////////////////////
    'trigger_rel_filter' => array(
        'inclusion' => array(
            'type' => array('link'),
            //end inclusion
        ),
        'exclusion' => array(
            'name' => array('direct_reports', 'accept_status', 'archived_emails'),
            'workflow' => array(false),
            //end exclusion
        ),
        'inc_override' => array(
            'name' => array(),
            //end inc_override
        ),
        'ex_override' => array(
            'name' => array(
                'users',
                'emails',
                'product_bundles',
                'email_addresses',
                'email_addresses_primary',
                'emailmarketing',
                'tracked_urls',
                'queueitems',
                'log_entries',
                'contract_types',
                'locked_fields_link',
            ),
            'module' => array(
                'Users',
                'Teams',
                'CampaignLog',
            ),
            //end ex_override
        )

        //end trigger_rel_filter
    ),
///////////////////////////////////////////////////////////
    'alert_rel_filter' => array(
        'inclusion' => array(
            'type' => array('link'),
            //end inclusion
        ),
        'exclusion' => array(
            'name' => array('direct_reports', 'accept_status', 'archived_emails'),
            'workflow' => array(false),
            //end exclusion
        ),
        'inc_override' => array(
            'name' => array(),
            //end inc_override
        ),
        'ex_override' => array(
            'name' => array(
                'users',
                'emails',
                'product_bundles',
                'email_addresses',
                'email_addresses_primary',
                'emailmarketing',
                'tracked_urls',
                'queueitems',
                'log_entries',
                'contract_types',
                'reports_to_link',
                'locked_fields_link',
            ),
            'module' => array(
                'Users',
                'Teams',
                'CampaignLog',
                'Releases',
            ),
            //end ex_override
        )

        //end alert_rel_filter
    ),
///////////////////////////////////////////////////////////
    'template_filter' => array(
        'inclusion' => array(//end inclusion
        ),
        'exclusion' => array(
            'type' => array('id', 'link'),
            'custom_type' => array('id', 'link'),
            'source' => array('non-db'),
            'workflow' => array(false),
            'name' => array(
                'created_by',
                'parent_type',
                'deleted',
                'assigned_user_name',
                'filename',
                'file_mime_type',
                'file_url'
            ),
            //end exclusion
        ),
        'inc_override' => array(
            'name' => array(
                'assigned_user_id',
                'assigned_user_name',
                'modified_user_id',
                'modified_by_name',
                'created_by',
                'created_by_name',
                'full_name',
                'email1',
                'team_name',
                'shipper_name'
            ),
            'type' => array(
                'relate'
            ),
            //end inc_override
        ),
        'ex_override' => array(
            'name' => array('team_id'),
            //end ex_override
        )

        //end template_filter
    ),
//////////////////////////////////////////////////////////////
    'alert_trigger' => array(
        'inclusion' => array(//end inclusion
        ),
        'exclusion' => array(
            'type' => array('id', 'link', 'datetime', 'date'),
            'custom_type' => array('id', 'link', 'datetime', 'date'),
            'name' => array('assigned_user_name', 'parent_type', 'deleted', 'filename', 'file_mime_type', 'file_url'),
            'source' => array('non-db'),
            'workflow' => array(false),
            //end exclusion
        ),
        'inc_override' => array(
            'type' => array('team_list', 'assigned_user_name'),
            'name' => array('full_name'),
            //end inc_override
        ),
        'ex_override' => array(
            'name' => array('team_name', 'account_name'),
            //end ex_override
        )

        //end alert_trigger
    ),
//////////////////////////////////////////////////////////////////
    'template_rel_filter' => array(
        'inclusion' => array(
            'type' => array('link'),
            //end inclusion
        ),
        'exclusion' => array(
            'name' => array('direct_reports', 'accept_status'),
            'workflow' => array(false),
            //end exclusion
        ),
        'inc_override' => array(
            'name' => array(),
            //end inc_override
        ),
        'ex_override' => array(
            'name' => array(
                'users',
                'email_addresses',
                'email_addresses_primary',
                'emailmarketing',
                'tracked_urls',
                'queueitems',
                'log_entries',
                'reports_to_link'
            ),
            'module' => array(
                'Users',
                'Teams',
                'CampaignLog'
            ),
            //end ex_override
        )

        //end template_rel_filter
    ),
);
