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


$viewdefs['PdfManager'] =
array (
  'EditView' =>
  array (
    'templateMeta' =>
    array (
        'form' => array(
                            'footerTpl' => 'modules/PdfManager/tpls/EditViewFooter.tpl',
                            'enctype'=>'multipart/form-data',
                            'hidden' => array(
                                '<input type="hidden" name="base_module_history" id="base_module_history" value="{$fields.base_module.value}">',
                            )
                        ),
      'maxColumns' => '2',
      'widths' =>
      array (
        0 =>
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 =>
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' => array (
          array (
              'file' => 'modules/PdfManager/javascript/PdfManager.js',
          ),
      ),
      'useTabs' => false,
      'syncDetailEditViews' => false,
    ),
    'panels' =>
    array (
      'default' =>
      array (
        0 =>
        array (
          0 => 'name',
          1 =>
          array (
            'name' => 'team_name',
            'displayParams' =>
            array (
              'display' => true,
            ),
          ),
        ),
        1 =>
        array (
          0 => array(   'name' => 'description',
                        'displayParams' => array('rows' => 1)
                    ),
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'base_module',
            'label' => 'LBL_BASE_MODULE',
            'popupHelp' => 'LBL_BASE_MODULE_POPUP_HELP',
            'displayParams' =>
            array (
                'field' => array (
                    'onChange' => 'SUGAR.PdfManager.loadFields(this.value, \'\');',
                ),
            ),
          ),
          1 =>
          array (
            'name' => 'published',
            'label' => 'LBL_PUBLISHED',
            'popupHelp' => 'LBL_PUBLISHED_POPUP_HELP',
          ),
        ),
        3 =>
        array (
          0 =>
          array (
            'name' => 'field',
            'label' => 'LBL_FIELD',
            'customCode' => '{include file="modules/PdfManager/tpls/getFields.tpl"}',
            'popupHelp' => 'LBL_FIELD_POPUP_HELP',
          ),
        ),
        4 =>
        array (
          0 =>
          array (
            'name' => 'body_html',
            'label' => 'LBL_BODY_HTML',
            'popupHelp' => 'LBL_BODY_HTML_POPUP_HELP',
          ),
        ),
        5 =>
        array (
            0 => array(
                'name' => 'header_title',
            ),
            1 => array(
                'name' => 'header_text',
            ),
        ),
        6 =>
        array (
            0 => array(
                'name' => 'header_logo',
                'popupHelp' => 'LBL_HEADER_LOGO_POPUP_HELP',
            ),
            1 => array(
                'name' => 'footer_text',
            ),
        ),
      ),
      'lbl_editview_panel1' =>
      array (
        0 =>
        array (
          0 =>
          array (
            'name' => 'author',
            'label' => 'LBL_AUTHOR',
          ),
          1 =>
          array (
            'name' => 'title',
            'label' => 'LBL_TITLE',
          ),
        ),
        1 =>
        array (
          0 =>
          array (
            'name' => 'subject',
            'label' => 'LBL_SUBJECT',
          ),
          1 =>
          array (
            'name' => 'keywords',
            'label' => 'LBL_KEYWORDS',
            'popupHelp' => 'LBL_KEYWORDS_POPUP_HELP'
          ),
        ),
      ),
    ),
  ),
);
