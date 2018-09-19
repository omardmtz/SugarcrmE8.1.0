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
$module_name = 'Campaigns';
$viewdefs[$module_name]['base']['menu']['header'] = array(
    array(
        'route'=>'#bwc/index.php?module=Campaigns&action=WizardHome&return_module=Campaigns&return_action=index',
        'label' =>'LNL_NEW_CAMPAIGN_WIZARD',
        'acl_action'=>'create',
        'acl_module'=>$module_name,
        'icon' => 'fa-magic',
    ),
    array(
        'route'=>'#bwc/index.php?module=Campaigns&action=EditView&return_module=Campaigns&return_action=index',
        'label' =>'LNK_NEW_CAMPAIGN',
        'acl_action'=>'create',
        'acl_module'=>$module_name,
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#bwc/index.php?module=Campaigns&action=index&return_module=Campaigns&return_action=index',
        'label' =>'LNK_CAMPAIGN_LIST',
        'acl_action'=>'list',
        'acl_module'=>$module_name,
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#bwc/index.php?module=Campaigns&action=newsletterlist&return_module=Campaigns&return_action=index',
        'label' =>'LBL_NEWSLETTERS',
        'acl_action'=>'list',
        'acl_module'=>$module_name,
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#bwc/index.php?module=EmailTemplates&action=EditView&return_module=EmailTemplates&return_action=DetailView',
        'label' =>'LNK_NEW_EMAIL_TEMPLATE',
        'acl_action'=>'create',
        'acl_module'=>'EmailTemplates',
        'icon' => 'fa-plus',
    ),
    array(
        'route'=>'#bwc/index.php?module=EmailTemplates&action=index',
        'label' =>'LNK_EMAIL_TEMPLATE_LIST',
        'acl_action'=>'list',
        'acl_module'=>'EmailTemplates',
        'icon' => 'fa-bars',
    ),
    array(
        'route'=>'#bwc/index.php?module=Campaigns&action=WizardEmailSetup&return_module=Campaigns&return_action=index',
        'label' =>'LBL_EMAIL_SETUP_WIZARD',
        'acl_action'=>'admin',
        'acl_module'=>$module_name,
        'icon' => 'fa-cog',
    ),
    array(
        'route'=>'#bwc/index.php?module=Campaigns&action=CampaignDiagnostic&return_module=Campaigns&return_action=index',
        'label' =>'LBL_DIAGNOSTIC_WIZARD',
        'acl_action'=>'edit',
        'acl_module'=>$module_name,
        'icon' => 'fa-bar-chart-o',
    ),
    array(
        'route'=>'#bwc/index.php?module=Campaigns&action=WebToLeadCreation&return_module=Campaigns&return_action=index',
        'label' =>'LBL_WEB_TO_LEAD',
        'acl_action'=>'edit',
        'acl_module'=>$module_name,
        'icon' => 'fa-plus',
    ),
);
