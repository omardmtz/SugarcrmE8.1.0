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
  'DetailView' =>
  array (
    'templateMeta' =>
    array (
      'form' =>
      array (
        'buttons' =>
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => array('customCode' => '<input type="button" value="{$MOD.LBL_PREVIEW}" name="pdf_preview" onclick="document.location=\'index.php?module=PdfManager&record={$fields.id.value}&action=sugarpdf&sugarpdf=pdfmanager&pdf_template_id={$fields.id.value}&pdf_preview=1\'" class="button" title="{$MOD.LBL_PREVIEW}" id="pdf_preview">'),
        ),
        'hideAudit' => true,
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
          1 => 'team_name',
        ),
        1 =>
        array (
          0 => 'description',
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'base_module',
            'studio' => 'visible',
            'label' => 'LBL_BASE_MODULE',
          ),
          1 =>
          array (
            'name' => 'published',
            'studio' => 'visible',
            'label' => 'LBL_PUBLISHED',
          ),
        ),
        3 =>
        array (
          0 =>
          array (
            'name' => 'body_html',
            'studio' => 'visible',
            'label' => 'LBL_BODY_HTML',
            'customCode' => '{$fields.body_html.value|from_html}',
          ),
        ),
        4 =>
        array (
            0 => 'header_title',
            1 => 'header_text',
        ),
        5 =>
        array (
            0 => 'header_logo',
            1 => 'footer_text',
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
          ),
        ),
      ),
    ),
  ),
);
