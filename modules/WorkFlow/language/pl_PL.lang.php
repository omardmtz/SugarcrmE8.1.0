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
  'LBL_MODULE_NAME' => 'Definicje workflow',
  'LBL_MODULE_NAME_SINGULAR' => 'Definicja workflow',
  'LBL_MODULE_ID' => 'Workflow',  
  'LBL_MODULE_TITLE' => 'Workflow: Strona główna',
  'LBL_SEARCH_FORM_TITLE' => 'Wyszukiwanie workflow',
  'LBL_LIST_FORM_TITLE' => 'Lista workflow',
  'LBL_NEW_FORM_TITLE' => 'Utwórz definicje workflow',
  'LBL_LIST_NAME' => 'Nazwa',
  'LBL_LIST_TYPE' => 'Moment uruchomienia:',
  'LBL_LIST_BASE_MODULE' => 'Moduł docelowy:',
  'LBL_LIST_STATUS' => 'Status',
  'LBL_NAME' => 'Nazwa:',
  'LBL_DESCRIPTION' => 'Opis:',
  'LBL_TYPE' => 'Moment uruchomienia:',
  'LBL_STATUS' => 'Status:',
  'LBL_BASE_MODULE' => 'Moduł docelowy:',
  'LBL_LIST_ORDER' => 'Kolejność przetwarzania:',
  'LBL_FROM_NAME' => 'Nazwa nadawcy:',
  'LBL_FROM_ADDRESS' => 'Adres nadawcy:',  
  'LNK_NEW_WORKFLOW' => 'Utwórz definicje workflow',
  'LNK_WORKFLOW' => 'Lista definicji workflow', 
  
  
  'LBL_ALERT_TEMPLATES' => 'Szablony alertów',
  'LBL_CREATE_ALERT_TEMPLATE' => 'Utwórz szablon alertu:',
  'LBL_SUBJECT' => 'Temat:',
  
  'LBL_RECORD_TYPE' => 'Dotyczy:',
 'LBL_RELATED_MODULE'=> 'Moduł powiązany:',
  
  
  'LBL_PROCESS_LIST' => 'Kolejność workflow',
	'LNK_ALERT_TEMPLATES' => 'Szablony wiadomości e-mail dla alertu',
	'LNK_PROCESS_VIEW' => 'Kolejność workflow',
  'LBL_PROCESS_SELECT' => 'Wybierz moduł:',
  'LBL_LACK_OF_TRIGGER_ALERT'=> 'Uwaga: aby rozpocząć ten workflow wymagane jest utworzenie warunku jego wykonania',
  'LBL_LACK_OF_NOTIFICATIONS_ON'=> 'Uwaga: w celu wysłania alertów należy ustawić opcje serwera SMTP w panelu administracyjnym > Ustawienia e-mail. ',
  'LBL_FIRE_ORDER' => 'Kolejność przetwarzania:',
  'LBL_RECIPIENTS' => 'Odbiorcy',
  'LBL_INVITEES' => 'Zaproszeni',
  'LBL_INVITEE_NOTICE' => 'Uwaga: niezbędne jest wybranie co najmniej jednego zaproszenia w celu utworzenia tego. ',
  'NTC_REMOVE_ALERT' => 'Czy na pewno chcesz usunąć ten workflow?',
  'LBL_EDIT_ALT_TEXT' => 'Cały tekst',
  'LBL_INSERT' => 'Wstaw',
  'LBL_SELECT_OPTION' => 'Wybierz jedną z opcji.',
  'LBL_SELECT_VALUE' => 'Wybierz wartość.',
  'LBL_SELECT_MODULE' => 'Wybierz moduł powiązany.',
  'LBL_SELECT_FILTER' => 'Należy wybrać pole, według którego moduł powiązany będzie filtrowany.',
  'LBL_LIST_UP' => 'góra',
  'LBL_LIST_DN' => 'dół',
  'LBL_SET' => 'Ustaw',
  'LBL_AS' => 'na',
  'LBL_SHOW' => 'Pokaż',
  'LBL_HIDE' => 'Ukryj',
  'LBL_SPECIFIC_FIELD' => 'określone pole',
  'LBL_ANY_FIELD' => 'dowolne pole',
  'LBL_LINK_RECORD'=>'Link do rekordu',
  'LBL_INVITE_LINK'=>'Link do zaproszenia do spotkania/rozmowy telefonicznej',
  'LBL_PLEASE_SELECT'=>'Wybierz',
  'LBL_BODY'=>'Treść:',
  'LBL__S'=>' ',
  'LBL_ALERT_SUBJECT'=>'ALERT WORKFLOW',
  'LBL_ACTION_ERROR'=>'Ta akcja nie może zostać wykonana. Zmodyfikuj pola modułów i ich wartości, tak aby były poprawne.',
  'LBL_ACTION_ERRORS'=>'Uwaga: Jedna lub wiele akcji zawiera błędy.',
  'LBL_ALERT_ERROR'=>'Ten alert nie może zostać wysłany. Zmodyfikuj wszystkie opcje, tak aby były poprawne.',
  'LBL_ALERT_ERRORS'=>'Uwaga: Jeden lub wiele alertów zawiera błędy.',
  'LBL_TRIGGER_ERROR'=>'Ten warunek zawiera nieprawidłowe wartości i nie spowoduje uruchomienia.',
  'LBL_TRIGGER_ERRORS'=>'Uwaga: Jeden lub wiele warunków zawiera błędy.',
  'LBL_UP' => 'Góra' /*for 508 compliance fix*/,
  'LBL_DOWN' => 'Dół' /*for 508 compliance fix*/,
  'LBL_EDITLAYOUT' => 'Edytuj układ' /*for 508 compliance fix*/,
  'LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW' => array('workflow' => 'Workflow'),
  'LBL_EMAILTEMPLATES_TYPE' => 'Typ',

  // Workflow sunsetting message, updated for 7.9
  'LBL_WORKFLOW_SUNSET_NOTICE' => '<strong>Uwaga:</strong> Funkcje przepływu pracy w systemie Sugar oraz zarządzanie przepływem pracy zostaną usunięte w przyszłej wersji aplikacji Sugar. Klienci edycji Sugar Enterprise powinni zacząć korzystać z możliwości dostępnych w ramach opcji Advanced Workflow. Kliknij <a href="http://www.sugarcrm.com/wf-eol" target="_blank"> tutaj</a>, aby uzyskać więcej informacji.',
);

