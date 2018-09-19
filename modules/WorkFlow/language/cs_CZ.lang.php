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
/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
  'LBL_MODULE_NAME' => 'Definice Workflow',
  'LBL_MODULE_NAME_SINGULAR' => 'Definice WorkFlow',
  'LBL_MODULE_ID' => 'WorkFlow',  
  'LBL_MODULE_TITLE' => 'Workflow: Domů',
  'LBL_SEARCH_FORM_TITLE' => 'Vyhledání Workflow',
  'LBL_LIST_FORM_TITLE' => 'Seznam Workflow',
  'LBL_NEW_FORM_TITLE' => 'Vytvořit definici Workflow',
  'LBL_LIST_NAME' => 'Název',
  'LBL_LIST_TYPE' => 'Kdy se vykoná:',
  'LBL_LIST_BASE_MODULE' => 'Cílový Modul:',
  'LBL_LIST_STATUS' => 'Stav',
  'LBL_NAME' => 'Název:',
  'LBL_DESCRIPTION' => 'Popis:',
  'LBL_TYPE' => 'Kdy se vykoná:',
  'LBL_STATUS' => 'Stav:',
  'LBL_BASE_MODULE' => 'Cílový Modul:',
  'LBL_LIST_ORDER' => 'Pořadí provedení:',
  'LBL_FROM_NAME' => 'Od (Jméno):',
  'LBL_FROM_ADDRESS' => 'Od (adresa):',  
  'LNK_NEW_WORKFLOW' => 'Vytvořit definici Workflow',
  'LNK_WORKFLOW' => 'Seznam definic Workflow', 
  
  
  'LBL_ALERT_TEMPLATES' => 'Šablony upozornění',
  'LBL_CREATE_ALERT_TEMPLATE' => 'Vytvoření šablony upozornění:',
  'LBL_SUBJECT' => 'Předmět:',
  
  'LBL_RECORD_TYPE' => 'Aplikovat na:',
 'LBL_RELATED_MODULE'=> 'Připojený Modul:',
  
  
  'LBL_PROCESS_LIST' => 'Sekvence workflow',
	'LNK_ALERT_TEMPLATES' => 'Šablony e-mailových upozornění',
	'LNK_PROCESS_VIEW' => 'Sekvence workflow',
  'LBL_PROCESS_SELECT' => 'prosím vyberte modul:',
  'LBL_LACK_OF_TRIGGER_ALERT'=> 'Poznámka: Pro funkčnost tohoto workflow musíte vytvořit spouštěč',
  'LBL_LACK_OF_NOTIFICATIONS_ON'=> 'Poznámka: Pro odeslání upozornění je nutné nastavit SMTP Server v sekci Admin > Nastavení e-mailu.',
  'LBL_FIRE_ORDER' => 'Pořadí zpracování:',
  'LBL_RECIPIENTS' => 'příjemci',
  'LBL_INVITEES' => 'Pozvaní',
  'LBL_INVITEE_NOTICE' => 'Upozornění: Musíte vybrat alespoň jednoho pozvaného',
  'NTC_REMOVE_ALERT' => 'Jste si jist(a), že chcete odebrat tuto workflow?',
  'LBL_EDIT_ALT_TEXT' => 'Alternativní text',
  'LBL_INSERT' => 'Vložit',
  'LBL_SELECT_OPTION' => 'Prosím vyberte možnost.',
  'LBL_SELECT_VALUE' => 'Musíte zadat hodnotu.',
  'LBL_SELECT_MODULE' => 'Prosím vyberte související modul.',
  'LBL_SELECT_FILTER' => 'Musíte vybrat pole, podle kterého se bude filtrovat související modul.',
  'LBL_LIST_UP' => 'nahoru',
  'LBL_LIST_DN' => 'dolu',
  'LBL_SET' => 'Nastav',
  'LBL_AS' => 'jako',
  'LBL_SHOW' => 'ukaž',
  'LBL_HIDE' => 'skryj',
  'LBL_SPECIFIC_FIELD' => 'specifické pole',
  'LBL_ANY_FIELD' => 'nějaké pole',
  'LBL_LINK_RECORD'=>'Vazba na záznam',
  'LBL_INVITE_LINK'=>'Vytvořit pozvánku na schůzku/hovor',
  'LBL_PLEASE_SELECT'=>'Prosím vyberte',
  'LBL_BODY'=>'Tělo:',
  'LBL__S'=>'&#39;s',
  'LBL_ALERT_SUBJECT'=>'UPOZORNĚNÍ WORKFLOW',
  'LBL_ACTION_ERROR'=>'Tento požadavek nemůže být proveden. Upravte požadavek takovým způsobem, aby všechna pole byla platná.',
  'LBL_ACTION_ERRORS'=>'Upozornění: Jeden, nebo více níže uvedených požadavků obsahují chyby.',
  'LBL_ALERT_ERROR'=>'Toto upozornění nemůže být provedeno. Změnte upozornění tak, aby všechna pole byla platná.',
  'LBL_ALERT_ERRORS'=>'Upozornění: Jedno, nebo více upozornění obsahují chyby.',
  'LBL_TRIGGER_ERROR'=>'Upozornění: Tento spouštěč obsahuje neplatné hodnoty a nebude proveden.',
  'LBL_TRIGGER_ERRORS'=>'Upozornění: Jeden, nebo více spouštěčů níže obsahují chyby.',
  'LBL_UP' => 'Nahoru' /*for 508 compliance fix*/,
  'LBL_DOWN' => 'Dolů' /*for 508 compliance fix*/,
  'LBL_EDITLAYOUT' => 'Úprava rozvržení' /*for 508 compliance fix*/,
  'LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW' => array('workflow' => 'Pracovní postup'),
  'LBL_EMAILTEMPLATES_TYPE' => 'Typ',

  // Workflow sunsetting message, updated for 7.9
  'LBL_WORKFLOW_SUNSET_NOTICE' => '<strong>Poznámka:</strong> Funkce Sugar Workflow a Workflow Management budou v budoucí verzi aplikace Sugar odebrány. Zákazníci s edicí Sugar Enterprise by měli začít používat funkce, které poskytuje Sugar Advanced Workflow. Další informace získáte kliknutím na <a href="http://www.sugarcrm.com/wf-eol" target="_blank">here</a>.',
);

