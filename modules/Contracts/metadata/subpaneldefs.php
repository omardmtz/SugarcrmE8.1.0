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

$layout_defs['Contracts'] = array( 
    // sets up which panels to show, in which order, and with what linked_fields 
    'subpanel_setup' => array(
        'contracts_documents' => array(
            'order' => 10,
            'module' => 'Documents',
            'sort_order' => 'asc',
            'sort_by' => 'document_name',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'function:get_contract_documents',
            'set_subpanel_data' => 'contracts_documents',
            'title_key' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
            'fill_in_additional_fields' => true,
            'refresh_page' => 1,            
        ),
        'history' => array(
            'order' => 20,
            'sort_order' => 'desc',
            'sort_by' => 'notes.date_entered',
            'title_key' => 'LBL_NOTES_SUBPANEL_TITLE',
            'type' => 'collection',
            'subpanel_name' => 'history',   //this values is not associated with a physical file.
            'header_definition_from_subpanel'=> 'meetings',
            'module'=>'History',
            
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateNoteButton'),
            ),    
                    
            'collection_list' => array(    
                'notes' => array(
                    'module' => 'Notes',
                    'subpanel_name' => 'default',
                    'get_subpanel_data' => 'notes',
                ),
            ),
        ),
        'contacts' => array(
            'order' => 30,
            'module' => 'Contacts',
            'sort_order' => 'asc',
            'sort_by' => 'last_name, first_name',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'contacts',
            'add_subpanel_data' => 'contact_id',
            'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect'),
            ),
        ),
        'products' => array(
            'order' => 40,
            'module' => 'Products',
            'sort_order' => 'desc',
            'sort_by' => 'date_purchased',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'products',
            'add_subpanel_data' => 'product_id',
            'title_key' => 'LBL_PRODUCTS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopSelectButton'),
            ),
        ),
        'quotes' => array(
            'order' => 50,
            'module' => 'Quotes',
            'sort_order' => 'desc',
            'sort_by' => 'date_quote_expected_closed',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'quotes',
            'get_distinct_data'=> true,
            'add_subpanel_data' => 'quote_id',
            'title_key' => 'LBL_QUOTES_SUBPANEL_TITLE',
            'top_buttons' => array(
                array(
                    'widget_class' => 'SubPanelTopSelectButton',
                    'popup_module' => 'Quotes',
                    'mode' => 'MultiSelect', 
                    'initial_filter_fields' => array('account_id' => 'account_id'), //'account_name' => 'account_name'),   
                ),
            ),
        ),
    ),
);
