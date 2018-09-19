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

$viewdefs['Feedbacks']['base']['view']['feedback'] = array(
    'panels' => array(
        array(
            'label' => 'LBL_FEEDBACK_CSAT',
            'fields' => array(
                array(
                    'name' => 'feedback_csat',
                    'type' => 'rating',
                    'rate' => 5,
                    'required' => true,
                    'template' => 'edit',
                    'css_class' => 'field-rating',
                ),
            ),
        ),
        array(
            'fields' => array(
                array(
                    'name' => 'feedback_text',
                    'type' => 'textarea',
                    'template' => 'edit',
                    'required' => true,
                    'css_class' => 'feedback-text',
                    'placeholder' => 'LBL_FEEDBACK_TEXT_PLACEHOLDER',
                ),
            ),
        ),
    ),
);
