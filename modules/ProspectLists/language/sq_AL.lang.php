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

$mod_strings = array (
  // Dashboard Names
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Paneli i listës së listave të objektivave',

  'LBL_MODULE_NAME' => 'Listat e synuara',
  'LBL_MODULE_NAME_SINGULAR' => 'Lista e synuar',
  'LBL_MODULE_ID'   => 'Listat e synuara',
  'LBL_MODULE_TITLE' => 'listat e synuara: Ballina',
  'LBL_SEARCH_FORM_TITLE' => 'Kërkimi i listave të synuara',
  'LBL_LIST_FORM_TITLE' => 'Listat e synuara',
  'LBL_PROSPECT_LIST_NAME' => 'Lista e synuar',
  'LBL_NAME' => 'Emri',
  'LBL_ENTRIES' => 'të hyrat e përgjithshme',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Lista e synimeve',
  'LBL_LIST_ENTRIES' => 'objektivat në list',
  'LBL_LIST_DESCRIPTION' => 'Përshkrim',
  'LBL_LIST_TYPE_NO' => 'Lloji',
  'LBL_LIST_END_DATE' => 'pëfrundimi i të dhënës',
  'LBL_DATE_ENTERED' => 'Data e krijimit',
  'LBL_MARKETING_ID' => 'Marketingu i identifikimit',
  'LBL_DATE_MODIFIED' => 'Të dhënat e modifikuara',
  'LBL_MODIFIED' => 'Modifikuar nga',
  'LBL_CREATED' => 'Krijuar nga',
  'LBL_TEAM' => 'Grupi',
  'LBL_ASSIGNED_TO' => 'drejtuar',
  'LBL_DESCRIPTION' => 'Përshkrim',
  'LNK_NEW_CAMPAIGN' => 'krijo fushatë',
  'LNK_CAMPAIGN_LIST' => 'fushatat',
  'LNK_NEW_PROSPECT_LIST' => 'Krijo listë synimesh',
  'LNK_PROSPECT_LIST_LIST' => 'Shiko listat e synimeve',
  'LBL_MODIFIED_BY' => 'Modifikuar nga',
  'LBL_CREATED_BY' => 'Krijuar nga',
  'LBL_DATE_CREATED' => 'të dhëna të krijuara',
  'LBL_DATE_LAST_MODIFIED' => 'të dhëna të modifikuara',
  'LNK_NEW_PROSPECT' => 'krijo synim',
  'LNK_PROSPECT_LIST' => 'synimet',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'listat e synuara',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktet',
  'LBL_LEADS_SUBPANEL_TITLE' => 'udhëheq',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'synimet',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'llogaritë',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Fushatat',
  'LBL_COPY_PREFIX' =>'kopje nga',
  'LBL_USERS_SUBPANEL_TITLE' =>'përdoruesit',
  'LBL_TYPE' => 'Lloji',
  'LBL_LIST_TYPE' => 'Lloji',
  'LBL_LIST_TYPE_LIST_NAME'=>'Lloji',
  'LBL_NEW_FORM_TITLE'=>'Listë e synuar e re',
  'LBL_MARKETING_NAME'=>'emri i marketingut',
  'LBL_MARKETING_MESSAGE'=>'dërgo mesazhe marketingu',
  'LBL_DOMAIN_NAME'=>'emri i domenit',
  'LBL_DOMAIN'=>'jo meila në domen',
  'LBL_LIST_PROSPECTLIST_NAME'=>'Emri',
	'LBL_MORE_DETAIL' => 'Më tepër detaje' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'Një {{module_name}} përbëhet nga një grup individësh ose organizatash që dëshiron të përfshish në ose të përjashtosh nga një {{campaigns_singular_module}} tregtimi masiv. {{plural_module_name}} mund të përmbajë një numër dhe kombinim të pacaktuar personash të synuar, {{contacts_module}}, {{leads_module}}, përdoruesish dhe {{accounts_module}}. Personat e synuar mund të grupohen në një {{module_name}} sipas një grupi kriteresh të paracaktuara si grupmosha, vendndodhja gjeografike ose mënyrat e shpenzimit. {{plural_module_name}} përdoren në {{campaigns_module}} e tregtimit masiv, i cili mund të konfigurohet në modulin {{campaigns_module}}.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'Një {{module_name}} përbëhet nga një grup individësh ose organizatash që dëshiron të përfshish në ose të përjashtosh nga një {{campaigns_singular_module}} tregtimi masiv. - Redakto fushat e këtij regjistrimi duke klikuar një fushë individuale ose butonin "Redakto". - Shiko ose modifiko lidhjet e regjistrimeve të tjera në nënpanele, përfshirë {{campaigns_singular_module}}, duke lëvizur panelin e poshtëm majtas te "Pamja e të dhënave". - Bëj dhe shiko komentet e përdoruesve dhe regjistro historikun e ndryshimeve në {{activitystream_singular_module}} duke lëvizur panelin e poshtëm majtas tek "Transmetimi i aktiviteteve". - Ndiq ose bëje të preferuar këtë regjistrim duke përdorur ikonat në të djathtë të emrit të regjistrimit. - Veprime shtesë disponohen në menynë me zbritje të veprimeve në të djathë të butonit "Redakto".',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'Një {{module_name}} përbëhet nga një grup individësh ose organizatash që dëshiron të përfshish në ose të përjashtosh nga një {{campaigns_singular_module}} tregtimi masiv. 

Për të krijuar një {{module_name}}: 
1. Jep vlerat për fushat sipas dëshirës.
- Fushat e shënuara me "Patjetër" duhet të plotësohen para se të ruhen. 
- Kliko "Trego më shumë" për të paraqitur fushat shtesë nëse është e nevojshme. 
2. Kliko "Ruaj" për të finalizuar regjistrimin e ri dhe për t&#39;u kthyer në faqen e mëparshme. 
3. Pas ruajtjes, përdor nënpanelet e disponueshme në pamjen e regjistrimit të personit të synuar për të shtuar marrës të {{campaigns_singular_module}}.',
);
