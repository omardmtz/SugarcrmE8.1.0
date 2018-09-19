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
  'LBL_MODULE_NAME' => 'Pārdošana',
  'LBL_MODULE_TITLE' => 'Pārdošana: Sākums',
  'LBL_SEARCH_FORM_TITLE' => 'Darījuma meklēšana',
  'LBL_VIEW_FORM_TITLE' => 'Pārdošanas skatījums',
  'LBL_LIST_FORM_TITLE' => 'Darījumu saraksts',
  'LBL_SALE_NAME' => 'Pārdošanas nosaukums:',
  'LBL_SALE' => 'Pārdošana:',
  'LBL_NAME' => 'Darījuma nosaukums',
  'LBL_LIST_SALE_NAME' => 'Nosaukums',
  'LBL_LIST_ACCOUNT_NAME' => 'Uzņēmuma nosaukums',
  'LBL_LIST_AMOUNT' => 'Summa',
  'LBL_LIST_DATE_CLOSED' => 'Aizvērt',
  'LBL_LIST_SALE_STAGE' => 'Pārdošanas posms',
  'LBL_ACCOUNT_ID'=>'Uzņēmuma ID',
  'LBL_TEAM_ID' =>'Darba grupas ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Pārdošana - Valūtas jauninājums',
  'UPDATE_DOLLARAMOUNTS' => 'Atjaunot summas ASV dolāros',
  'UPDATE_VERIFY' => 'Pārbaudīt summas',
  'UPDATE_VERIFY_TXT' => 'Pārbauda vai summas darījumos ir derīgi decimāli skaitļi kuri satur tikai skaitliskas rakstu zīmes un decimālo punktu(.)',
  'UPDATE_FIX' => 'Izlabot summas',
  'UPDATE_FIX_TXT' => 'Mēģina izlabot jebkuras nepareizās summas, veidojot pareizu decimālo formu pašreizējai summai. Jebkura modificētā summa ir dublēta datubāzes laukā - amount_backup. Ja izpildot šo darbību, pamanāt kļūdas, pirms darbības atkārtotas izpildes atjaunojiet summas no dublējuma. Pretējā gadījumā dublējums var tikt pārrakstīts ar nederīgiem datiem.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Atjaunināt pamatvalūtas summas darījumiem, balstoties uz patreizējajiem valūtu kursiem. Šī vērtība tiek lietota lai aprēķinātu grafikus un saraksta skatījuma valūtu summas.',
  'UPDATE_CREATE_CURRENCY' => 'Veido jaunu valūtu:',
  'UPDATE_VERIFY_FAIL' => 'Ieraksta pārbaude neveiksmīga:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Patreizējā summa:',
  'UPDATE_VERIFY_FIX' => 'Pēc izlabošanas summa būs',
  'UPDATE_INCLUDE_CLOSE' => 'Iekļaut aizvērtos ierakstus',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Jaunā summa:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Jaunā valūta:',
  'UPDATE_DONE' => 'Pabeigts',
  'UPDATE_BUG_COUNT' => 'Atrastas kļūdas, tika mēģināts tās izlabot:',
  'UPDATE_BUGFOUND_COUNT' => 'Atrastas kļūdas:',
  'UPDATE_COUNT' => 'Atjaunināti ieraksti:',
  'UPDATE_RESTORE_COUNT' => 'Reģistrētas atjaunotas summas:',
  'UPDATE_RESTORE' => 'Atjaunot daudzumus',
  'UPDATE_RESTORE_TXT' => 'Atjauno summu vērtības no dublikātiem, kuri izveidoti salabošanas laikā.',
  'UPDATE_FAIL' => 'Nevar atjaunināt -',
  'UPDATE_NULL_VALUE' => 'Summa ir NULL, tiek iestatīta uz 0 -',
  'UPDATE_MERGE' => 'Sapludināt valūtas',
  'UPDATE_MERGE_TXT' => 'Sapludināt vairākas valūtas vienā valūtā. Ja ir vairāki valūtas ieraksti vienai valūtai, tie tiks sapludināti kopā. Šī darbība sapludinās valūtas visos citos moduļos.',
  'LBL_ACCOUNT_NAME' => 'Uzņēmuma nosaukums:',
  'LBL_AMOUNT' => 'Summa:',
  'LBL_AMOUNT_USDOLLAR' => 'Summa ASV dolāros:',
  'LBL_CURRENCY' => 'Valūta:',
  'LBL_DATE_CLOSED' => 'Paredzēts noslēgšanas datums',
  'LBL_TYPE' => 'Tips:',
  'LBL_CAMPAIGN' => 'Kampaņa:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Interesenti',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projekti',  
  'LBL_NEXT_STEP' => 'Nākamais solis:',
  'LBL_LEAD_SOURCE' => 'Interesenta avots:',
  'LBL_SALES_STAGE' => 'Pārdošanas posms:',
  'LBL_PROBABILITY' => 'Varbūtība (%):',
  'LBL_DESCRIPTION' => 'Apraksts',
  'LBL_DUPLICATE' => 'Iespējams pārdošanas dublikāts',
  'MSG_DUPLICATE' => 'Pārdošanas ieraksts kuru Jūs veidojat iespējams ir dublikāts jau esošam pārdošanas ierakstam.  Pārdošanas ieraksti, kuri satur līdzīgus nosaukumus ir izkārtoti zemāk<br>Klikšķiniet Saglabāt, lai turpinātu veidot jauno pārdošanu, vai klikšķiniet Atcelt, lai atgrieztos modulī neveidojot pārdošanu.',
  'LBL_NEW_FORM_TITLE' => 'Izveidot pārdošanu',
  'LNK_NEW_SALE' => 'Izveidot pārdošanu',
  'LNK_SALE_LIST' => 'Pārdošana',
  'ERR_DELETE_RECORD' => 'Jānorāda ieraksta numurs, lai dzēstu darījumu.',
  'LBL_TOP_SALES' => 'Mans svarīgākais atvērtais darījums',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Vai tiešām vēlaties izņemt kontaktu no pārdošanas?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Vai tiešām vēlaties izņemt šo pārdošanu no projekta?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Darbības',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Vēsture',
    'LBL_RAW_AMOUNT'=>'Neapstrādāta summa',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktpersonas',
	'LBL_ASSIGNED_TO_NAME' => 'Piešķirts lietotājam:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Piešķirtais lietotājs',
  'LBL_MY_CLOSED_SALES' => 'Mani aizvērtie darījumi',
  'LBL_TOTAL_SALES' => 'Pārdošanu kopskaits',
  'LBL_CLOSED_WON_SALES' => 'Iegūtie darījumi',
  'LBL_ASSIGNED_TO_ID' =>'Piešķirts lietotājam ID:',
  'LBL_CREATED_ID'=>'Izveidoja ID',
  'LBL_MODIFIED_ID'=>'Modificēja ID',
  'LBL_MODIFIED_NAME'=>'Modificētāja lietotājs',
  'LBL_SALE_INFORMATION'=>'Pārdošanas informācija',
  'LBL_CURRENCY_ID'=>'Valūtas ID',
  'LBL_CURRENCY_NAME'=>'Valūtas nosaukums',
  'LBL_CURRENCY_SYMBOL'=>'Valūtas simbols',
  'LBL_EDIT_BUTTON' => 'Rediģēt',
  'LBL_REMOVE' => 'Noņemt',
  'LBL_CURRENCY_RATE' => 'Valūtas kurss',

);

