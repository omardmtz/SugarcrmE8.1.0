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
$viewdefs['DocumentRevisions']['DetailView'] = array(
    'templateMeta' => array('maxColumns' => '2',
                            'form' => array(
                                'buttons' => array(),
                                'hidden'=>array('<input type="hidden" name="old_id" value="{$fields.document_revision_id.value}">')), 
                            'widths' => array(
                                array('label' => '10', 'field' => '30'), 
                                array('label' => '10', 'field' => '30')
                                ),
        ),
    'panels' => 
    array (
        '' => 
        array (
            array (
                'document_name',
                'latest_revision',
            ),
            
            array (
                'revision',
            ),
            
            array (
                'filename',
                'doc_type',
            ),
            
            array (
                array (
                    'name' => 'date_entered',
                    'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                ),
            ),

            array (
                'change_log',
            ),
        ),
    ),
);