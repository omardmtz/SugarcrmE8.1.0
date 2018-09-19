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

$module_name = 'pmse_Project';
$viewdefs[$module_name] = 
array (
  'base' => 
  array (
    'view' => 
    array (
      'record' => 
      array (
          'buttons' => array(
              array(
                  'type' => 'button',
                  'name' => 'cancel_button',
                  'label' => 'LBL_CANCEL_BUTTON_LABEL',
                  'css_class' => 'btn-invisible btn-link',
                  'showOn' => 'edit',
                  'events' => array(
                      'click' => 'button:cancel_button:click',
                  ),
              ),
              array(
                  'type' => 'rowaction',
                  'event' => 'button:save_button:click',
                  'name' => 'save_button',
                  'label' => 'LBL_SAVE_BUTTON_LABEL',
                  'css_class' => 'btn btn-primary',
                  'showOn' => 'edit',
                  'acl_action' => 'edit',
              ),
              array(
                  'type' => 'actiondropdown',
                  'name' => 'main_dropdown',
                  'primary' => true,
                  'showOn' => 'view',
                  'buttons' => array(
                      array(
                          'type' => 'rowaction',
                          'event' => 'button:edit_button:click',
                          'name' => 'edit_button',
                          'label' => 'LBL_EDIT_BUTTON_LABEL',
                          'acl_action' => 'edit',
                      ),
                      array(
                          'type' => 'rowaction',
                          'event' => 'button:open_designer:click',
                          'name' => 'open_designer',
                          'label' => 'LBL_PMSE_LABEL_DESIGN',
                          'acl_action' => 'view',
                      ),
                      array(
                          'type' => 'rowaction',
                          'event' => 'button:export_process:click',
                          'name' => 'export_process',
                          'label' => 'LBL_PMSE_LABEL_EXPORT',
                          'acl_action' => 'view',
                      ),
                      array(
                          'type' => 'shareaction',
                          'name' => 'share',
                          'label' => 'LBL_RECORD_SHARE_BUTTON',
                          'acl_action' => 'view',
                      ),
                      array(
                          'type' => 'divider',
                      ),
                      array(
                          'type' => 'rowaction',
                          'event' => 'button:duplicate_button:click',
                          'name' => 'duplicate_button',
                          'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
                          'acl_module' => $module_name,
                          'acl_action' => 'create',
                      ),
                      array(
                          'type' => 'divider',
                      ),
                      array(
                          'type' => 'rowaction',
                          'event' => 'button:delete_button:click',
                          'name' => 'delete_button',
                          'label' => 'LBL_DELETE_BUTTON_LABEL',
                          'acl_action' => 'delete',
                      ),
                  ),
              ),
              array(
                  'name' => 'sidebar_toggle',
                  'type' => 'sidebartoggle',
              ),
          ),
        'panels' => 
        array (
          0 => 
          array (
            'name' => 'panel_header',
            'label' => 'LBL_RECORD_HEADER',
            'header' => true,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'picture',
                'type' => 'avatar',
                'width' => 42,
                'height' => 42,
                'dismiss_label' => true,
                'readonly' => true,
              ),
              1 => 'name',
              2 => 
              array (
                'name' => 'favorite',
                'label' => 'LBL_FAVORITE',
                'type' => 'favorite',
                'readonly' => true,
                'dismiss_label' => true,
              ),
              3 => 
              array (
                'name' => 'follow',
                'label' => 'LBL_FOLLOW',
                'type' => 'follow',
                'readonly' => true,
                'dismiss_label' => true,
              ),
            ),
          ),
          1 => 
          array (
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'newTab' => false,
            'panelDefault' => 'expanded',
            'fields' => array(
              array(
                'name' => 'prj_module',
                'studio' => 'visible',
                'label' => 'LBL_PRJ_MODULE',
              ),
              'assigned_user_name',
              array(
                'name' => 'prj_status',
                'span' => 6,
              ),
            ),
          ),
          2 => 
          array (
            'name' => 'panel_hidden',
            'label' => 'LBL_SHOW_MORE',
            'hide' => true,
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'newTab' => false,
            'panelDefault' => 'expanded',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'description',
                'span' => 12,
              ),
              1 => 'date_modified',
              2 => 'date_entered',
            ),
          ),
        ),
        'templateMeta' => 
        array (
          'useTabs' => false,
        ),
      ),
    ),
  ),
);
