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
$viewdefs['KBContents']['base']['layout']['subpanels'] = array (
  'components' => array (
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_LOCALIZATIONS_SUBPANEL_TITLE',
          'override_subpanel_list_view' => 'subpanel-for-localizations',
          'override_paneltop_view' => 'panel-top-for-localizations',
          'context' => array(
              'link' => 'localizations',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_REVISIONS_SUBPANEL_TITLE',
          'override_subpanel_list_view' => 'subpanel-for-revisions',
          'override_paneltop_view' => 'panel-top-for-revisions',
          'context' => array(
              'link' => 'revisions',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_NOTES_SUBPANEL_TITLE',
          'context' => array(
              'link' => 'notes',
          ),
      ),
  ),
);
