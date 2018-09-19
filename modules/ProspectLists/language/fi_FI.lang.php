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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Tavoiteluettelojen luettelon työpöytä',

  'LBL_MODULE_NAME' => 'Tavoitelistat',
  'LBL_MODULE_NAME_SINGULAR' => 'Tavoitelista',
  'LBL_MODULE_ID'   => 'Tavoitelistat',
  'LBL_MODULE_TITLE' => 'Tavoitelistat: Etusivu',
  'LBL_SEARCH_FORM_TITLE' => 'Tavoitelistojen Haku',
  'LBL_LIST_FORM_TITLE' => 'Tavoitelistat',
  'LBL_PROSPECT_LIST_NAME' => 'Tavoitelista:',
  'LBL_NAME' => 'Nimi',
  'LBL_ENTRIES' => 'Merkintöjä yhteensä',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Tavoitelista',
  'LBL_LIST_ENTRIES' => 'Tavoitteita listassa',
  'LBL_LIST_DESCRIPTION' => 'Kuvaus',
  'LBL_LIST_TYPE_NO' => 'Tyyppi',
  'LBL_LIST_END_DATE' => 'Päättymispäivä',
  'LBL_DATE_ENTERED' => 'Luontipäivä',
  'LBL_MARKETING_ID' => 'Markkinointitunnus',
  'LBL_DATE_MODIFIED' => 'Muokattu viimeksi',
  'LBL_MODIFIED' => 'Muokannut',
  'LBL_CREATED' => 'Luonut',
  'LBL_TEAM' => 'Tiimi',
  'LBL_ASSIGNED_TO' => 'Vastuuhenkilö:',
  'LBL_DESCRIPTION' => 'Kuvaus',
  'LNK_NEW_CAMPAIGN' => 'Luo kampanja',
  'LNK_CAMPAIGN_LIST' => 'Kampanjat',
  'LNK_NEW_PROSPECT_LIST' => 'Luo tavoitelista',
  'LNK_PROSPECT_LIST_LIST' => 'Näytä tavoitelistat',
  'LBL_MODIFIED_BY' => 'Muuttanut',
  'LBL_CREATED_BY' => 'Luonut',
  'LBL_DATE_CREATED' => 'Luotu',
  'LBL_DATE_LAST_MODIFIED' => 'Muutettu',
  'LNK_NEW_PROSPECT' => 'Luo tavoite',
  'LNK_PROSPECT_LIST' => 'Tavoitteet',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'Tavoitelistat',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kontaktit',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Liidit',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'Tavoitteet',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Asiakkaat',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Kampanjat',
  'LBL_COPY_PREFIX' =>'Kopio',
  'LBL_USERS_SUBPANEL_TITLE' =>'Käyttäjät',
  'LBL_TYPE' => 'Tyyppi',
  'LBL_LIST_TYPE' => 'Tyyppi',
  'LBL_LIST_TYPE_LIST_NAME'=>'Tyyppi',
  'LBL_NEW_FORM_TITLE'=>'Uusi tavoitelista',
  'LBL_MARKETING_NAME'=>'Markkinointinimi',
  'LBL_MARKETING_MESSAGE'=>'Sähköpostimarkkinointiviesti',
  'LBL_DOMAIN_NAME'=>'Verkkotunnus',
  'LBL_DOMAIN'=>'Ei sähköposteja verkkotunnukseen',
  'LBL_LIST_PROSPECTLIST_NAME'=>'Nimi',
	'LBL_MORE_DETAIL' => 'Tarkemmin' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{module_name}} on kokoelma henkilöitä tai organisaatioita, jotka haluat sisällyttää tai olla sisällyttämättä {{campaigns_singular_module}}-massamarkkinointikampanjoihin.
{{plural_module_name}} voi sisältää useita tavoitteita, {{contacts_module}}, {{leads_module}}, käyttäjiä ja {{accounts_module}}. Tavoitteita voi ryhmittää {{module_name}} an tavoitteiden kriteerien, kuten ikäryhmän, sijainnin, tai kulutuskäytäntöjen mukaan. {{plural_module_name}}-moduuleja käytetään {{campaigns_module}}-massasähköpostimarkkinointiin, joita voi hallita {{campaigns_module}}-moduulissa.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{module_name}} on kokoelma henkilöitä tai organisaatioita, jotka haluat sisällyttää tai olla sisällyttämättä massamarkkinointi{{campaigns_singular_module}}.
- Muokkaa tämän tietueen kenttiä painamalla itse kenttää tai Muokkaa-painiketta.

- Näytä tai muokkaa linkityksiä muihin alipaneelien tietueisiin, kuten {{campaigns_singular_module}} n vastaanottajiin, valitsemalla alavasemmalla oleva paneeli tietonäkymään.

- Luo ja lue käyttäjäkommentteja ja tietueen historiaa {{activitystream_singular_module}}-näkymässä. Aktiviteettivirran saat valitsemalla alavasemmalla oleva paneeli ‘Aktiviteettivirta’-näkymään.

- Seuraa tai merkkaa tämä tietue suosikiksi käyttämällä tietueen nimen oikealta puolelta löytyviä kuvakkeita.

- Muut toiminnot löytyvät ‘Muokkaa’-painikkeen oikealla puolella olevasta ‘Toiminnot’-valikosta.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{module_name}} on kokoelma henkilöitä tai organisaatioita, jotka haluat sisällyttää tai olla sisällyttämättä massamarkkinointi{{campaigns_singular_module}}. 

{{module_name}}-moduulin luominen:
1. Syötä kentille arvoja.
- "Pakollisiksi" merkityt kentät pitää täyttää ennen tallentamista
- Saat esille lisää kenttiä tarvittaessa napsauttamalla "Näytä lisää".
2. Paina "Tallenna" luodaksesi uuden tietueen. Tämän jälkeen palaat edelliselle sivulle.
3. Tallennuksen jälkeen käytä tavoitteen tietuenäkymässä olevia alipaneeleita lisätäksesi {{campaigns_singular_module}} an vastaanottajia.',
);
