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
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => 'Vanzari',
  'LBL_MODULE_TITLE' => 'Vanzari:Acasa',
  'LBL_SEARCH_FORM_TITLE' => 'Cautare Vanzari',
  'LBL_VIEW_FORM_TITLE' => 'Vedere vanzari',
  'LBL_LIST_FORM_TITLE' => 'Lista Vanzari',
  'LBL_SALE_NAME' => 'Nume vanzari',
  'LBL_SALE' => 'Vanzari',
  'LBL_NAME' => 'Nume vanzari',
  'LBL_LIST_SALE_NAME' => 'Nume',
  'LBL_LIST_ACCOUNT_NAME' => 'Nume Cont',
  'LBL_LIST_AMOUNT' => 'Suma, valoare',
  'LBL_LIST_DATE_CLOSED' => 'inchis',
  'LBL_LIST_SALE_STAGE' => 'Etapa vanzare',
  'LBL_ACCOUNT_ID'=>'Cont id',
  'LBL_TEAM_ID' =>'ID Echipă',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Vanzari - Actualizare Moneda',
  'UPDATE_DOLLARAMOUNTS' => 'Update Sume Dolari U. S.',
  'UPDATE_VERIFY' => 'Verifica sumele',
  'UPDATE_VERIFY_TXT' => 'Verifică dacă valorile in suma de vânzări sunt valabile numerele zecimale  numai cu caractere numerice (0-9) şi numărul de zecimale (.)',
  'UPDATE_FIX' => 'Sume fixe',
  'UPDATE_FIX_TXT' => 'Încercările de a rezolva orice sume incorecte, prin crearea unui zecimal valid din valoarea actuală. Orice sumă modificata este susţinuta în domeniul baza de date amount_backup . Dacă rulaţi acest anunţ şi observati probleme, nu-l rulaţi din nou fără restaurarea din backup, deoarece se poate suprascrie  cu noile date incorecte.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Actualizaţi sumele pentru dolarul american pentru vânzări pe baza ratelor actuale valutar stabilite. Această valoare este folosită pentru a calcula grafice şi Lista Vizualizare Sume valutare.',
  'UPDATE_CREATE_CURRENCY' => 'Creează monedă nouă:',
  'UPDATE_VERIFY_FAIL' => 'Verificare a inregistrarii esuata',
  'UPDATE_VERIFY_CURAMOUNT' => 'Cantitate suma:',
  'UPDATE_VERIFY_FIX' => 'Efectuand Depanare ne va da',
  'UPDATE_INCLUDE_CLOSE' => 'Include si Inregistrarile Inchise',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Suma Noua:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Moneda noua:',
  'UPDATE_DONE' => 'Terminat',
  'UPDATE_BUG_COUNT' => 'Probleme gasite si incercate sa fie rezolvate',
  'UPDATE_BUGFOUND_COUNT' => 'Probleme gasite:',
  'UPDATE_COUNT' => 'Inregistrari actualizate',
  'UPDATE_RESTORE_COUNT' => 'Inregistrari sume restaurate',
  'UPDATE_RESTORE' => 'Restabileste sume',
  'UPDATE_RESTORE_TXT' => 'Restabileste valoarea sumelor din valorile de rezerva create in timpul depanarii',
  'UPDATE_FAIL' => 'Nu au fost putut fi actualizate -',
  'UPDATE_NULL_VALUE' => 'Suma este NULA sabilind-o 0 -',
  'UPDATE_MERGE' => 'Imbina monede',
  'UPDATE_MERGE_TXT' => 'Îmbina mai multe monede într-o monedă unică. Dacă există mai multe înregistrări monedă pentru aceeaşi monedă, imbina împreună. Acest lucru va imbina, de asemenea, monedele din toate celelalte module.',
  'LBL_ACCOUNT_NAME' => 'Numele Contului',
  'LBL_AMOUNT' => 'Suma:',
  'LBL_AMOUNT_USDOLLAR' => 'Suma USD:',
  'LBL_CURRENCY' => 'Moneda',
  'LBL_DATE_CLOSED' => 'Data la care se asteapta sa se inchida',
  'LBL_TYPE' => 'Tip',
  'LBL_CAMPAIGN' => 'Campanie',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Antete',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Proiecte',  
  'LBL_NEXT_STEP' => 'Urmatorul Pas',
  'LBL_LEAD_SOURCE' => 'Sursa principala',
  'LBL_SALES_STAGE' => 'Sadiul Vanzarilor',
  'LBL_PROBABILITY' => 'Probabilitate (%):',
  'LBL_DESCRIPTION' => 'Descriere',
  'LBL_DUPLICATE' => 'Posibil Vanzari Duplicate',
  'MSG_DUPLICATE' => 'Inregistrarea vanzarilor pe care sunteţi pe cale de a o crea ar putea fi un duplicat al unei înregistrări de vanzari care există deja. Inregistrarile contului care conţin nume similare sunt enumerate mai jos.<br />Faceţi clic pe Salvare pentru a continua crearea ascestei Vanzari noi, sau faceţi clic pe Revocare pentru a reveni la modul fără a crea Vanzarea.',
  'LBL_NEW_FORM_TITLE' => 'Creeaza vanzare',
  'LNK_NEW_SALE' => 'Creeaza Vanzare',
  'LNK_SALE_LIST' => 'Vanzari',
  'ERR_DELETE_RECORD' => 'Trebuie să specifici un număr de înregistrare pentru a șterge vânzarea.',
  'LBL_TOP_SALES' => 'Cea mai deschisa vanzare',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Sunteți sigur că vreți să ștergeți acest contact din vânzare?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Sunteți sigur că vreți să ștergeți această vânzare din proiect?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Activitati',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Istoric',
    'LBL_RAW_AMOUNT'=>'Suma Bruta',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contacte',
	'LBL_ASSIGNED_TO_NAME' => 'Utilizator:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Atribuit utilizatorului',
  'LBL_MY_CLOSED_SALES' => 'Vanzarile mele inchise',
  'LBL_TOTAL_SALES' => 'Total Vanzari',
  'LBL_CLOSED_WON_SALES' => 'Vanzari castigate inchise',
  'LBL_ASSIGNED_TO_ID' =>'Atribuit catre ID',
  'LBL_CREATED_ID'=>'Creat de ID',
  'LBL_MODIFIED_ID'=>'Modificat după ID',
  'LBL_MODIFIED_NAME'=>'Modificat după nume utilizator',
  'LBL_SALE_INFORMATION'=>'Informatii Vanzare',
  'LBL_CURRENCY_ID'=>'Valabilitate Id',
  'LBL_CURRENCY_NAME'=>'Nume Moneda',
  'LBL_CURRENCY_SYMBOL'=>'Simbol Moneda',
  'LBL_EDIT_BUTTON' => 'Editeaza',
  'LBL_REMOVE' => 'Eliminare',
  'LBL_CURRENCY_RATE' => 'Curs de schimb valutar',

);

