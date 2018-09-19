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
$viewdefs['Cases']['base']['layout']['subpanels'] = array (
  'components' => array (
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_CALLS_SUBPANEL_TITLE',
          'context' => array(
              'link' => 'calls',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_MEETINGS_SUBPANEL_TITLE',
          'context' => array(
              'link' => 'meetings',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_TASKS_SUBPANEL_TITLE',
          'context' => array(
              'link' => 'tasks',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_NOTES_SUBPANEL_TITLE',
          'context' => array(
              'link' => 'notes',
          ),
      ),
      array (
          'layout' => 'subpanel',
          'label' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
          'context' => array (
              'link' => 'documents',
          ),
      ),
      array (
          'layout' => 'subpanel',
          'label' => 'LBL_CONTACTS_SUBPANEL_TITLE',
          'override_paneltop_view' => 'panel-top-for-cases',
          'override_subpanel_list_view' => 'subpanel-for-cases',
          'context' => array (
              'link' => 'contacts',
          ),
      ),
      array (
          'layout' => 'subpanel',
          'label' => 'LBL_BUGS_SUBPANEL_TITLE',
          'context' => array (
              'link' => 'bugs',
          ),
      ),
      array (
          'layout' => 'subpanel',
          'label' => 'LBL_PROJECTS_SUBPANEL_TITLE',
          'context' => array (
              'link' => 'project',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_EMAILS_SUBPANEL_TITLE',
          'override_subpanel_list_view' => 'subpanel-for-cases-archived-emails',
          'context' => array (
              'link' => 'archived_emails',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_KBCONTENTS_SUBPANEL_TITLE',
          'override_paneltop_view' => 'panel-top-for-cases',
          'context' => array(
              'link' => 'kbcontents',
          ),
      ),
  ),
);
