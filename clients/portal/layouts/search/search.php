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

$viewdefs['portal']['layout']['search'] = array (
  'components' => array (
    array (
      'layout' => array (
        'components' => array (
          array (
            'layout' => array (
              'components' => array (
                array (
                  'view' => 'results-headerpane',
                ),
                array (
                  'view' => 'results',
                ),
              ),
              'type' => 'simple',
              'name' => 'main-pane',
              'span' => 8,
            ),
          ),
          array (
            'layout' => array (
              'components' => array (
              ),
              'type' => 'simple',
              'name' => 'dashboard-pane',
              'span' => 4,
            ),
          ),
          array (
            'layout' => array (
              'components' => array (
                array (
                  'layout' => 'preview',
                ),
              ),
              'type' => 'simple',
              'name' => 'preview-pane',
              'span' => 8,
            ),
          ),
        ),
        'type' => 'default',
        'name' => 'sidebar',
        'span' => 12,
      ),
    ),
  ),
  'type' => 'simple',
  'name' => 'base',
  'span' => 12,
);
