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
$dictionary['Meeting'] = array('table' => 'meetings','activity_enabled'=>true,
	'unified_search' => true, 'full_text_search' => true, 'unified_search_default_enabled' => true,
	'comment' => 'Meeting activities'
                               ,'fields' => array (
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_SUBJECT',
    'required' => true,
    'type' => 'name',
    'dbType' => 'varchar',
	'unified_search' => true,
    'full_text_search' => array('enabled' => true, 'searchable' => true, 'boost' => 1.43),
    'len' => '50',
    'comment' => 'Meeting name',
    'importable' => 'required',
  ),
  'accept_status' => array (
    'name' => 'accept_status',
    'vname' => 'LBL_ACCEPT_STATUS',
    'type' => 'varchar',
    'dbType' => 'varchar',
    'len' => '20',
    'source'=>'non-db',
  ),
  //bug 39559
  'set_accept_links' => array (
    'name' => 'set_accept_links',
    'vname' => 'LBL_ACCEPT_LINK',
    'type' => 'varchar',
    'dbType' => 'varchar',
    'len' => '20',
    'source'=>'non-db',
  ),
  'location' =>
  array (
    'name' => 'location',
    'vname' => 'LBL_LOCATION',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Meeting location',
    'full_text_search' => array('enabled' => true, 'searchable' => true, 'boost' => 0.36),
  ),
  'password' =>
  array (
    'name' => 'password',
    'vname' => 'LBL_PASSWORD',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Meeting password',
    'studio' => array('wirelesseditview'=>false, 'wirelessdetailview'=>false, 'wirelesslistview'=>false, 'wireless_basic_search'=>false),
    'dependency' => 'isInEnum($type,getDD("extapi_meeting_password"))',
  ),
  'join_url' =>
  array (
    'name' => 'join_url',
    'vname' => 'LBL_URL',
    'type' => 'varchar',
    'len' => '600',
    'comment' => 'Join URL',
    'studio' => 'false',
    'reportable' => false,
  ),
  'host_url' =>
  array (
    'name' => 'host_url',
    'vname' => 'LBL_HOST_URL',
    'type' => 'varchar',
    'len' => '600',
    'comment' => 'Host URL',
    'studio' => 'false',
    'reportable' => false,
  ),
  'displayed_url' =>
  array (
    'name' => 'displayed_url',
    'vname' => 'LBL_DISPLAYED_URL',
    'type' => 'url',
    'len' => '400',
    'comment' => 'Meeting URL',
    'studio' => array('wirelesseditview'=>false, 'wirelessdetailview'=>false, 'wirelesslistview'=>false, 'wireless_basic_search'=>false),
    'dependency' => 'and(isAlpha($type),not(equal($type,"Sugar")))',
  ),
  'creator' =>
  array (
    'name' => 'creator',
    'vname' => 'LBL_CREATOR',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Meeting creator',
    'studio' => 'false',
  ),
  'external_id' =>
  array (
    'name' => 'external_id',
    'vname' => 'LBL_EXTERNALID',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Meeting ID for external app API',
    'studio' => 'false',
   ),
  'duration_hours' =>
  array (
    'name' => 'duration_hours',
    'vname' => 'LBL_DURATION_HOURS',
    'type' => 'int',
    'comment' => 'Duration (hours)',
    'importable' => 'required',
    'required' => true,
    'massupdate' => false,
    'studio' => false,
    'processes' => true,
    'default' => 0,
    'group'=>'end_date',
    'group_label' => 'LBL_DATE_END',
  ),
  'duration_minutes' =>
  array (
    'name' => 'duration_minutes',
    'vname' => 'LBL_DURATION_MINUTES',
    'type' => 'enum',
    'dbType' => 'int',
    'options' => 'duration_intervals',
    'group'=>'end_date',
    'group_label' => 'LBL_DATE_END',
    'len' => '2',
    'comment' => 'Duration (minutes)',
    'required' => true,
    'massupdate' => false,
    'studio' => false,
    'processes' => true,
    'default' => 0,
  ),
  'date_start' =>
  array (
    'name' => 'date_start',
    'vname' => 'LBL_CALENDAR_START_DATE',
    'type' => 'datetimecombo',
    'dbType' => 'datetime',
    'comment' => 'Date of start of meeting',
    'importable' => 'required',
    'required' => true,
    'massupdate' => false,
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
    'validation' => array('type' => 'isbefore', 'compareto' => 'date_end', 'blank' => false),
    'studio' => array('recordview' => false, 'wirelesseditview'=>false),
    'full_text_search' => array('enabled' => true, 'searchable' => false),
  ),

  'date_end' =>
  array (
    'name' => 'date_end',
    'vname' => 'LBL_CALENDAR_END_DATE',
    'type' => 'datetimecombo',
    'dbType' => 'datetime',
    'massupdate' => false,
    'comment' => 'Date meeting ends',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
    'studio' => array('recordview' => false, 'wirelesseditview'=>false), // date_end is computed by the server from date_start and duration
	'readonly' => true,
    'full_text_search' => array('enabled' => true, 'searchable' => false),
    'group'=>'end_date',
    'group_label' => 'LBL_DATE_END',
  ),
  'parent_type' =>
  array (
    'name' => 'parent_type',
    'vname'=>'LBL_PARENT_TYPE',
    'type' =>'parent_type',
    'dbType' => 'varchar',
    'group'=>'parent_name',
    'options'=> 'parent_type_display',
    'len' => 100,
    'comment' => 'Module meeting is associated with',
    'studio' => array('searchview'=>false, 'wirelesslistview'=>false),
  ),
  'status' =>
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'len' => 100,
    'options' => 'meeting_status_dom',
    'comment' => 'Meeting status (ex: Planned, Held, Not held)',
    'default' => 'Planned',
    'duplicate_on_record_copy' => 'no',
    'full_text_search' => array('enabled' => true, 'searchable' => false),
  ),
  'type' =>
   array (
     'name' => 'type',
     'vname' => 'LBL_TYPE',
     'type' => 'enum',
     'len' => 255,
     'function' => 'getMeetingsExternalApiDropDown',
     'comment' => 'Meeting type (ex: WebEx, Other)',
     'options' => 'eapm_list',
     'default'	=> 'Sugar',
     'massupdate' => false,
   	 'studio' => array('wireless_basic_search'=>false),
   ),
  // Bug 24170 - Added only to allow the sidequickcreate form to work correctly
  'direction' =>
  array (
    'name' => 'direction',
    'vname' => 'LBL_DIRECTION',
    'type' => 'enum',
    'len' => 100,
    'options' => 'call_direction_dom',
    'comment' => 'Indicates whether call is inbound or outbound',
    'source' => 'non-db',
    'importable' => 'false',
    'massupdate'=>false,
    'reportable'=>false,
	'studio' => 'false',
  ),
  'parent_id' =>
  array (
    'name' => 'parent_id',
    'vname'=>'LBL_PARENT_ID',
    'type' => 'id',
    'group'=>'parent_name',
    'reportable'=>false,
    'comment' => 'ID of item indicated by parent_type',
    'studio' => array('searchview'=>false),
  ),
  'reminder_checked' => array(
    'name' => 'reminder_checked',
    'vname' => 'LBL_POPUP_REMINDER',
    'type' => 'bool',
    'source' => 'non-db',
    'comment' => 'checkbox indicating whether or not the reminder value is set (Meta-data only)',
    'massupdate' => false,
    'studio' => false,
   ),
  'reminder_time' =>
  array (
    'name' => 'reminder_time',
    'vname' => 'LBL_POPUP_REMINDER_TIME',
    'type' => 'enum',
    'dbType' => 'int',
    'options' => 'reminder_time_options',
    'reportable' => false,
    'massupdate' => false,
    'default'=> -1,
    'comment' => 'Specifies when a reminder alert should be issued; -1 means no alert; otherwise the number of seconds prior to the start',
    'studio' => array('recordview' => false, 'wirelesseditview' => false),
  ),
  'email_reminder_checked' => array(
    'name' => 'email_reminder_checked',
    'vname' => 'LBL_EMAIL_REMINDER',
    'type' => 'bool',
    'source' => 'non-db',
    'comment' => 'checkbox indicating whether or not the email reminder value is set (Meta-data only)',
    'massupdate' => false,
    'studio' => false,
   ),
  'email_reminder_time' =>
      array (
          'name' => 'email_reminder_time',
          'vname' => 'LBL_EMAIL_REMINDER_TIME',
          'type' => 'enum',
          'dbType' => 'int',
          'options' => 'reminder_time_options',
          'reportable' => false,
          'massupdate' => false,
          'default'=> -1,
          'comment' => 'Specifies when a email reminder alert should be issued; -1 means no alert; otherwise' .
              ' the number of seconds prior to the start',
      ),
      'email_reminder_sent' => array(
          'name' => 'email_reminder_sent',
          'vname' => 'LBL_EMAIL_REMINDER_SENT',
          'default' => 0,
          'type' => 'bool',
          'comment' => 'Whether email reminder is already sent',
          'studio' => false,
          'massupdate'=> false,
      ),
        'outlook_id' =>
  array (
    'name' => 'outlook_id',
    'vname' => 'LBL_OUTLOOK_ID',
    'type' => 'varchar',
    'len' => '255',
    'reportable' => false,
      'comment' => 'When the Sugar Plug-in for Microsoft Outlook syncs an Outlook appointment, this is the Outlook appointment item ID',
      'studio' => false,
  ),
   'sequence' =>
  array (
    'name' => 'sequence',
    'vname' => 'LBL_SEQUENCE',
    'type' => 'int',
    'len' => '11',
    'reportable' => false,
    'default'=>0,
    'comment' => 'Meeting update sequence for meetings as per iCalendar standards',
      'studio' => false,
  ),
  'contact_name' =>
  array (
    'name' => 'contact_name',
    'rname' => 'name',
    'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
    'id_name' => 'contact_id',
    'massupdate' => false,
    'vname' => 'LBL_CONTACT_NAME',
    'type' => 'relate',
    'link'=>'contacts',
    'table' => 'contacts',
    'isnull' => 'true',
    'module' => 'Contacts',
    'join_name' => 'contacts',
    'dbType' => 'varchar',
    'source'=>'non-db',
    'len' => 36,
    'importable' => 'false',
    'studio' => false,
    ),

  'contacts' =>
  array (
  	'name' => 'contacts',
    'type' => 'link',
    'module' => 'Contacts',
    'relationship' => 'meetings_contacts',
    'source'=>'non-db',
		'vname'=>'LBL_CONTACTS',
  ),
   'parent_name'=>
 	array(
		'name'=> 'parent_name',
		'parent_type'=>'record_type_display' ,
		'type_name'=>'parent_type',
		'id_name'=>'parent_id',
		'vname'=>'LBL_LIST_RELATED_TO',
		'type'=>'parent',
		'group'=>'parent_name',
		'source'=>'non-db',
		'options'=> 'parent_type_display',
        'studio' => true,
		),
  'users' =>
  array (
  	'name' => 'users',
    'type' => 'link',
    'relationship' => 'meetings_users',
    'source'=>'non-db',
		'vname'=>'LBL_USERS',
  ),
  'accept_status_users' => array(
      'massupdate' => false,
      'name' => 'accept_status_users',
      'type' => 'enum',
      'studio' => 'false',
      'source' => 'non-db',
      'vname' => 'LBL_ACCEPT_STATUS',
      'options' => 'dom_meeting_accept_status',
      'importable' => 'false',
      'link' => 'users',
      'rname_link' => 'accept_status',
  ),
  'accounts' =>
  array (
  	'name' => 'accounts',
    'type' => 'link',
    'relationship' => 'account_meetings',
    'source'=>'non-db',
		'vname'=>'LBL_ACCOUNT',
  ),
        'revenuelineitems' => array(
            'name' => 'revenuelineitems',
            'type' => 'link',
            'relationship' => 'revenuelineitem_meetings',
            'module' => 'RevenueLineItems',
            'bean_name' => 'RevenueLineItem',
            'source' => 'non-db',
            'vname' => 'LBL_REVENUELINEITEMS',
            'workflow' => false
        ),
        'products' => array(
            'name' => 'products',
            'type' => 'link',
            'relationship' => 'product_meetings',
            'module' => 'Products',
            'bean_name' => 'Product',
            'source' => 'non-db',
            'vname' => 'LBL_PRODUCTS',
        ),
    'bugs' =>
    array (
        'name' => 'bugs',
        'type' => 'link',
        'relationship' => 'bug_meetings',
        'source'=>'non-db',
        'vname'=>'LBL_BUGS',
        'module'=>'Bugs',
    ),
  'leads' =>
  array (
    'name' => 'leads',
    'type' => 'link',
    'relationship' => 'meetings_leads',
    'source'=>'non-db',
        'vname'=>'LBL_LEADS',
  ),
  'project'=> array (
    'name' => 'project',
    'type' => 'link',
    'relationship' => 'projects_meetings',
    'source' => 'non-db',
    'vname' => 'LBL_PROJECTS'
  ),
  'opportunity' =>
  array (
  	'name' => 'opportunity',
    'type' => 'link',
    'relationship' => 'opportunity_meetings',
    'source'=>'non-db',
		'vname'=>'LBL_OPPORTUNITY',
  ),
  'prospects' =>
  array(
      'name'         => 'prospects',
      'type'         => 'link',
      'relationship' => 'prospect_meetings',
      'source'       => 'non-db',
      'vname'        => 'LBL_PROSPECTS',
      'module'       => 'Prospects',
  ),
  'quotes' =>
  array(
      'name' => 'quotes',
      'type' => 'link',
      'relationship' => 'quote_meetings',
      'source'=>'non-db',
      'vname'=>'LBL_QUOTES',
  ),
  'cases' =>
  array (
  	'name' => 'cases',
    'type' => 'link',
    'relationship' => 'case_meetings',
    'source'=>'non-db',
		'vname'=>'LBL_CASE',
  ),
    'notes' =>
  array (
  	'name' => 'notes',
    'type' => 'link',
    'relationship' => 'meetings_notes',
    'module'=>'Notes',
    'bean_name'=>'Note',
    'source'=>'non-db',
		'vname'=>'LBL_NOTES',
  ),
	'contact_id' => array(
		'name' => 'contact_id',
        'type' => 'relate',
        'link' => 'contacts',
        'rname' => 'id',
        'vname' => 'LBL_CONTACT_ID',
		'source' => 'non-db',
        'studio' => false,
	),
	'repeat_type' =>
	array(
		'name' => 'repeat_type',
		'vname' => 'LBL_CALENDAR_REPEAT_TYPE',
		'type' => 'enum',
		'len' => 36,
		'options' => 'repeat_type_dom',
		'comment' => 'Type of recurrence',
		'importable' => 'false',
		'massupdate' => false,
		'reportable' => false,
		'studio' => 'false',
	),
	'repeat_interval' =>
	array(
		'name' => 'repeat_interval',
		'vname' => 'LBL_CALENDAR_REPEAT_INTERVAL',
		'type' => 'int',
		'len' => 3,
		'default' => 1,
		'comment' => 'Interval of recurrence',
		'importable' => 'false',
		'massupdate' => false,
		'reportable' => false,
		'studio' => 'false',
	),
	'repeat_dow' =>
	array(
		'name' => 'repeat_dow',
		'vname' => 'LBL_CALENDAR_REPEAT_DOW',
		'type' => 'varchar',
		'len' => 7,
		'comment' => 'Days of week in recurrence',
		'importable' => 'false',
		'massupdate' => false,
		'reportable' => false,
		'studio' => 'false',
	),
	'repeat_until' =>
	array(
		'name' => 'repeat_until',
		'vname' => 'LBL_CALENDAR_REPEAT_UNTIL_DATE',
        'type' => 'date',
		'comment' => 'Repeat until specified date',
		'importable' => 'false',
		'massupdate' => false,
		'reportable' => false,
		'studio' => 'false',
	),
	'repeat_count' =>
	array(
		'name' => 'repeat_count',
		'vname' => 'LBL_CALENDAR_REPEAT_COUNT',
		'type' => 'int',
		'len' => 7,
		'comment' => 'Number of recurrence',
		'importable' => 'false',
		'massupdate' => false,
		'reportable' => false,
		'studio' => 'false',
	),
        'repeat_selector' =>
            array(
                'name' => 'repeat_selector',
                'vname' => 'LBL_CALENDAR_REPEAT_SELECTOR',
                'type' => 'enum',
                'len' => 36,
                'options' => 'repeat_selector_dom',
                'comment' => 'Repeat selector',
                'importable' => 'false',
                'massupdate' => false,
                'reportable' => false,
                'studio' => 'false',
                'visibility_grid' => array(
                    'trigger' => 'repeat_type',
                    'values' => array(
                        '' => array(
                            'None',
                        ),
                        'Daily' => array(
                            'None',
                        ),
                        'Weekly' => array(
                            'None',
                        ),
                        'Monthly' => array(
                            'None',
                            'Each',
                            'On',
                        ),
                        'Yearly' => array(
                            'None',
                            'On',
                        ),
                    ),
                ),
            ),
        'repeat_days' =>
            array(
                'name' => 'repeat_days',
                'vname' => 'LBL_CALENDAR_REPEAT_DAYS',
                'type' => 'varchar',
                'len' => 128,
                'comment' => 'Days of month',
                'importable' => 'false',
                'massupdate' => false,
                'reportable' => false,
                'studio' => 'false',
            ),
        'repeat_ordinal' =>
            array(
                'name' => 'repeat_ordinal',
                'vname' => 'LBL_CALENDAR_REPEAT_ORDINAL',
                'type' => 'enum',
                'len' => 36,
                'options' => 'repeat_ordinal_dom',
                'comment' => 'Repeat ordinal value',
                'importable' => 'false',
                'massupdate' => false,
                'reportable' => false,
                'studio' => 'false',
            ),
        'repeat_unit' =>
            array(
                'name' => 'repeat_unit',
                'vname' => 'LBL_CALENDAR_REPEAT_UNIT',
                'type' => 'enum',
                'len' => 36,
                'options' => 'repeat_unit_dom',
                'comment' => 'Repeat unit value',
                'importable' => 'false',
                'massupdate' => false,
                'reportable' => false,
                'studio' => 'false',
            ),
	'repeat_parent_id' =>
	array(
		'name' => 'repeat_parent_id',
		'vname' => 'LBL_REPEAT_PARENT_ID',
		'type' => 'id',
		'len' => 36,
		'comment' => 'Id of the first element of recurring records',
		'importable' => 'false',
		'massupdate' => false,
		'reportable' => false,
		'studio' => 'false',
	),
    'recurrence_id' => array(
        'name' => 'recurrence_id',
        'vname' => 'LBL_CALENDAR_RECURRENCE_ID',
        'type' => 'datetime',
        'dbType' => 'datetime',
        'comment' => 'Recurrence ID of meeting. Original meeting start date',
            'importable' => false,
            'exportable' => false,
            'massupdate' => false,
            'studio' => false,
            'processes' => false,
            'visible' => false,
            'reportable' => false,
            'hideacl' => true,
        ),
	'recurring_source' =>
	array(
		'name' => 'recurring_source',
		'vname' => 'LBL_RECURRING_SOURCE',
		'type' => 'varchar',
		'len' => 36,
		'comment' => 'Source of recurring meeting',
		'importable' => false,
		'massupdate' => false,
		'reportable' => false,
		'studio' => false,
	),
  'send_invites' => array(
    'name' => 'send_invites',
    'vname' => 'LBL_SEND_INVITES',
    'type' => 'bool',
    'source' => 'non-db',
    'comment' => 'checkbox indicating whether or not to send out invites (Meta-data only)',
    'massupdate' => false,
   ),
        'invitees' => array(
            'name' => 'invitees',
            'source' => 'non-db',
            'type' => 'collection',
            'vname' => 'LBL_INVITEES',
            'links' => array(
                'contacts',
                'leads',
                'users',
            ),
            'order_by' => 'name:asc',
            'studio' => false,
        ),
    'auto_invite_parent' => array(
        'name' => 'auto_invite_parent',
        'type' => 'bool',
        'source' => 'non-db',
        'comment' => 'Flag to allow for turning off auto invite of parent record -  (Meta-data only)',
        'massupdate' => false,
    ),
    'task_parent' => array(
        'name' => 'task_parent',
        'type' => 'link',
        'relationship' => 'task_meetings_parent',
        'source' => 'non-db',
        'reportable' => false,
    ),
    'contact_parent' => array(
        'name' => 'contact_parent',
        'type' => 'link',
        'relationship' => 'contact_meetings_parent',
        'source' => 'non-db',
        'reportable' => false,
    ),
    'lead_parent' => array(
        'name' => 'lead_parent',
        'type' => 'link',
        'relationship' => 'lead_meetings',
        'source' => 'non-db',
        'reportable' => false
    ),
        'kbcontents_parent' => array(
            'name' => 'kbcontents_parent',
            'type' => 'link',
            'relationship' => 'kbcontent_meetings',
            'source' => 'non-db',
            'vname' => 'LBL_KBDOCUMENTS',
            'reportable' => false,
        ),
),
 'relationships' => array (
	  'meetings_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'meetings_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'meetings_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')

	,'meetings_notes' => array('lhs_module'=> 'Meetings', 'lhs_table'=> 'meetings', 'lhs_key' => 'id',
							  'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Meetings')
	),
    'acls' => array('SugarACLOpi' => true, 'SugarACLStatic' => true),


    'indices' => array (
       array('name' =>'idx_mtg_name', 'type'=>'index', 'fields'=>array('name')),
       array('name' =>'idx_meet_par_del', 'type'=>'index', 'fields'=>array('parent_id','parent_type','deleted')),
       array('name' => 'idx_meet_stat_del', 'type' => 'index', 'fields'=> array('assigned_user_id', 'status', 'deleted')),
       array('name' => 'idx_meet_date_start', 'type' => 'index', 'fields'=> array('date_start')),
       array('name' => 'idx_meet_recurrence_id', 'type' => 'index', 'fields' => array('recurrence_id')),
       array('name' => 'idx_meet_date_start_end_del', 'type' => 'index', 'fields'=> array('date_start', 'date_end', 'deleted')),
       array(
           'name' => 'idx_meet_repeat_parent_id',
           'type' => 'index',
           'fields' => array('repeat_parent_id', 'deleted'),
       ),
       // due to pulls from client side to check if there are reminders to handle.
       array('name' => 'idx_meet_date_start_reminder', 'type' => 'index', 'fields'=> array('date_start', 'reminder_time')),

                                                   )
//This enables optimistic locking for Saves From EditView
	,'optimistic_locking'=>true,

    'duplicate_check' => array(
        'enabled' => false
    ),
);

VardefManager::createVardef('Meetings','Meeting', array('default', 'assignable',
'team_security',
));

//boost value for full text search
$dictionary['Meeting']['fields']['description']['full_text_search']['boost'] = 0.55;

