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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Sihtmärgiloendite loendi töölaud',

  'LBL_MODULE_NAME' => 'Eesmärgiloendid',
  'LBL_MODULE_NAME_SINGULAR' => 'Eesmärgiloend',
  'LBL_MODULE_ID'   => 'Eesmärgiloendid',
  'LBL_MODULE_TITLE' => 'Eesmärgiloendid: avaleht',
  'LBL_SEARCH_FORM_TITLE' => 'Eesmärgiloendite otsing',
  'LBL_LIST_FORM_TITLE' => 'Eesmärgiloendid',
  'LBL_PROSPECT_LIST_NAME' => 'Eesmärgiloend:',
  'LBL_NAME' => 'Nimi',
  'LBL_ENTRIES' => 'Kirjeid kokku',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Eesmärgiloend',
  'LBL_LIST_ENTRIES' => 'Eesmärgid loendis',
  'LBL_LIST_DESCRIPTION' => 'Kirjeldus',
  'LBL_LIST_TYPE_NO' => 'Tüüp',
  'LBL_LIST_END_DATE' => 'Lõppkuupäev',
  'LBL_DATE_ENTERED' => 'Loomiskuupäev',
  'LBL_MARKETING_ID' => 'Turunduse ID',
  'LBL_DATE_MODIFIED' => 'Muutmiskuupäev',
  'LBL_MODIFIED' => 'Muutja',
  'LBL_CREATED' => 'Looja',
  'LBL_TEAM' => 'Meeskond',
  'LBL_ASSIGNED_TO' => 'Määratud kasutajale',
  'LBL_DESCRIPTION' => 'Kirjeldus',
  'LNK_NEW_CAMPAIGN' => 'Loo kampaania',
  'LNK_CAMPAIGN_LIST' => 'Kampaaniad',
  'LNK_NEW_PROSPECT_LIST' => 'Loo eesmärgiloend',
  'LNK_PROSPECT_LIST_LIST' => 'Vaata eesmärgiloendeid',
  'LBL_MODIFIED_BY' => 'Muutja',
  'LBL_CREATED_BY' => 'Looja',
  'LBL_DATE_CREATED' => 'Loomiskuupäev',
  'LBL_DATE_LAST_MODIFIED' => 'Muutmiskuupäev',
  'LNK_NEW_PROSPECT' => 'Loo eesmärk',
  'LNK_PROSPECT_LIST' => 'Eesmärgid',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'Eesmärgiloendid',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktid',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Müügivihjed',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'Eesmärgid',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Kontod',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Kampaaniad',
  'LBL_COPY_PREFIX' =>'Koopia',
  'LBL_USERS_SUBPANEL_TITLE' =>'Kasutajad',
  'LBL_TYPE' => 'Tüüp',
  'LBL_LIST_TYPE' => 'Tüüp',
  'LBL_LIST_TYPE_LIST_NAME'=>'Tüüp',
  'LBL_NEW_FORM_TITLE'=>'Uus eesmärgiloend',
  'LBL_MARKETING_NAME'=>'Turunduse nimi',
  'LBL_MARKETING_MESSAGE'=>'E-posti turunduse sõnum',
  'LBL_DOMAIN_NAME'=>'Domeeni nimi',
  'LBL_DOMAIN'=>'Domeenile pole e-kirju',
  'LBL_LIST_PROSPECTLIST_NAME'=>'Nimi',
	'LBL_MORE_DETAIL' => 'Täpsemalt' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{module_name}} sisaldab üksikisikute või organisatsioonide kogumit, kelle soovite lausturundusse {{campaigns_singular_module}} kaasata või sellest välistada. {{plural_module_name}} võib sisaldada mis tahes arvu ja kombinatsiooni eesmärke, {{contacts_module}}, {{leads_module}}, kasutajaid ja {{accounts_module}}. Eesmärgid võivad olla rühmitatud moodulisse {{module_name}} eelmääratletud kriteeriumide kogumi alusel, nagu vanuserühm, geograafiline asukoht või tarbimisharjumused. Mooduleid {{plural_module_name}} kasutatakse e-posti lausturunduses {{campaigns_module}}, mida saab konfigureerida moodulis {{campaigns_module}}.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{module_name}} sisaldab üksikisikute või organisatsioonide kogumit, kelle soovite lausturundusse {{campaigns_singular_module}} kaasata või sellest välistada.

- Redigeerige kirje välju, klõpsates individuaalsel väljal või nupul Redigeeri.
- Vaadake või muutke alampaneelides linke teistele kirjetele, sh {{campaigns_singular_module}} saajatele, valides alumisel vasakpoolsel paanil kuva Andmevaade.
- Koostage ja vaadake kasutaja kommentaare ning salvestage muutuse ajalugu moodulis {{activitystream_singular_module}}, valides alumisel vasakpoolsel paanil kuva Tegevuste voog.
- Jälgige või lisage see kirje lemmikute hulka, kasutades kirje nimest paremal asuvaid ikoone.
- Täiendavad toimingud on saadaval tegevuste rippmenüüs, mis asub nupust Redigeeri paremal.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{module_name}} sisaldab üksikisikute või organisatsioonide kogumit, kelle soovite lausturundusse {{campaigns_singular_module}} kaasata või sellest välistada.

Mooduli {{module_name}} loomiseks tehke järgmist.
1. Esitage väljade väärtused soovi järgi.
 - Väljad märkega Kohustuslik tuleb täita enne salvestamist.
 - Vajaduse korral lisaväljade avaldamiseks klõpsake suvandit Kuva rohkem.
2. Uue kirje lõpetamiseks ja eelmisele lehele naasmiseks klõpsake nuppu Salvesta.
3. Pärast salvestamist kasutage eesmärgi kirje vaates saadaolevaid alampaneele, et lisada {{campaigns_singular_module}} saajaid.',
);
