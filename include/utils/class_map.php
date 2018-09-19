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

/**
 * This defines a bunch of core classes and where they can be loaded from
 * Only non PSR-0 classes need to be named here, other classes will be found automatically
 */
$class_map = array(
    'XTemplate'=>'vendor/XTemplate/xtpl.php',
    'Javascript'=>'include/javascript/javascript.php',
    'ListView'=>'include/ListView/ListView.php',
    'CustomSugarView' => 'custom/include/MVC/View/SugarView.php',
    'Sugar_Smarty' => 'include/SugarSmarty/Sugar_Smarty.php',
    'HTMLPurifier_Bootstrap' => 'vendor/HTMLPurifier/HTMLPurifier.standalone.php',
    'SugarCurrency'=>'include/SugarCurrency/SugarCurrency.php',
    'SugarRelationshipFactory' => 'data/Relationships/RelationshipFactory.php',
    'DBManagerFactory' => 'include/database/DBManagerFactory.php',
    'Localization' => 'include/Localization/Localization.php',
    'TimeDate' => 'include/TimeDate.php',
    'SugarDateTime' => 'include/SugarDateTime.php',
    'SugarBean' => 'data/SugarBean.php',
    'LanguageManager' => 'include/SugarObjects/LanguageManager.php',
    'VardefManager' => 'include/SugarObjects/VardefManager.php',
    'MetaDataManager' => 'include/MetaDataManager/MetaDataManager.php',
    'TemplateText' => 'modules/DynamicFields/templates/Fields/TemplateText.php',
    'TemplateField' => 'modules/DynamicFields/templates/Fields/TemplateField.php',
    'SugarEmailAddress' => 'include/SugarEmailAddress/SugarEmailAddress.php',
    'JSON' => 'include/JSON.php',
    'LoggerManager' => 'include/SugarLogger/LoggerManager.php',
    'ACLController' => 'modules/ACL/ACLController.php',
    'ACLJSController' => 'modules/ACL/ACLJSController.php',
    'Administration' => 'modules/Administration/Administration.php',
    'OutboundEmail' => 'include/OutboundEmail/OutboundEmail.php',
    'MailerFactory' => 'modules/Mailer/MailerFactory.php',
    'LogicHook' => 'include/utils/LogicHook.php',
    'LegacyJsonServer' => 'include/utils/LegacyJsonServer.php',
    'SugarTheme' => 'include/SugarTheme/SugarTheme.php',
    'SugarThemeRegistry' => 'include/SugarTheme/SugarTheme.php',
    'SugarModule' => 'include/MVC/SugarModule.php',
    'SugarApplication' => 'include/MVC/SugarApplication.php',
    'ControllerFactory' => 'include/MVC/Controller/ControllerFactory.php',
    'ViewFactory' => 'include/MVC/View/ViewFactory.php',
    'BeanFactory' => 'data/BeanFactory.php',
    'Audit' => 'modules/Audit/Audit.php',
    'Link2' => 'data/Link2.php',
    'SugarJobQueue' => 'include/SugarQueue/SugarJobQueue.php',
    'EmbedLinkService' => 'include/EmbedLinkService.php',
    'SugarApi' => 'include/api/SugarApi.php',
    'ParseCSV' => 'vendor/parsecsv/php-parsecsv/parsecsv.lib.php'
);
