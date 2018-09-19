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

// Hack for ActivityStream. MetadataManager does not supporrt submodules yet.

$viewdefs['Activities']['base']['filter']['activity'] = array(
    'create'               => false,
    'default_filter' => 'all_records',
    'quicksearch_field'    => array(),
    'quicksearch_priority' => 2,
    'filters'              => array(
        array(
            'id'                => 'all_records',
            'name'              => 'LBL_LISTVIEW_FILTER_ALL',
            'filter_definition' => array(),
            'editable'          => false
        ),
        array(
            'id'                => 'messages_for_create',
            'name'              => 'LBL_ACTIVITY_CREATE',
            'filter_definition' => array(
                '$or' => array(
                    array('activity_type' => 'create'),
                    array('activity_type' => 'attach'),
                ),
            ),
            'editable'          => false
        ),
        array(
            'id'                => 'messages_for_update',
            'name'              => 'LBL_ACTIVITY_UPDATE',
            'filter_definition' => array(
                'activity_type' => 'update',
            ),
            'editable'          => false
        ),
        array(
            'id'                => 'messages_for_link',
            'name'              => 'LBL_ACTIVITY_LINK',
            'filter_definition' => array(
                'activity_type' => 'link',
            ),
            'editable'          => false
        ),
        array(
            'id'                => 'messages_for_unlink',
            'name'              => 'LBL_ACTIVITY_UNLINK',
            'filter_definition' => array(
                'activity_type' => 'unlink',
            ),
            'editable'          => false
        ),
        array(
            'id'                => 'messages_for_post',
            'name'              => 'LBL_ACTIVITY_POST',
            'filter_definition' => array(
                'activity_type' => 'post',
            ),
            'editable'          => false
        ),
    ),
);
