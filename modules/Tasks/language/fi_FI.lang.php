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
  'LBL_TASKS_LIST_DASHBOARD' => 'Tehtäväluettelon työpöytä',

  'LBL_MODULE_NAME' => 'Tehtävät',
  'LBL_MODULE_NAME_SINGULAR' => 'Tehtävä',
  'LBL_TASK' => 'Tehtävät:',
  'LBL_MODULE_TITLE' => 'Tehtävät: Etusivu',
  'LBL_SEARCH_FORM_TITLE' => 'Tehtävien haku',
  'LBL_LIST_FORM_TITLE' => 'Tehtävälista',
  'LBL_NEW_FORM_TITLE' => 'Luo Tehtävä',
  'LBL_NEW_FORM_SUBJECT' => 'Aihe:',
  'LBL_NEW_FORM_DUE_DATE' => 'Eräpäivä:',
  'LBL_NEW_FORM_DUE_TIME' => 'Erääntymispäivä:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => 'Sulje',
  'LBL_LIST_SUBJECT' => 'Aihe',
  'LBL_LIST_CONTACT' => 'Yhteystiedot',
  'LBL_LIST_PRIORITY' => 'Prioriteetti',
  'LBL_LIST_RELATED_TO' => 'Liittyvät',
  'LBL_LIST_DUE_DATE' => 'Eräpäivä',
  'LBL_LIST_DUE_TIME' => 'Erääntymisen aika',
  'LBL_SUBJECT' => 'Aihe:',
  'LBL_STATUS' => 'Tila:',
  'LBL_DUE_DATE' => 'Eräpäivä:',
  'LBL_DUE_TIME' => 'Erääntymisen aika:',
  'LBL_PRIORITY' => 'Prioriteetti:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => 'Eräpäivä ja -aika',
  'LBL_START_DATE_AND_TIME' => 'Aloituspäivä ja -aika',
  'LBL_START_DATE' => 'Aloituspäivä:',
  'LBL_LIST_START_DATE' => 'Aloituspäivä',
  'LBL_START_TIME' => 'Aloitusaika:',
  'LBL_LIST_START_TIME' => 'Aloitusaika',
  'DATE_FORMAT' => '(vvvv-kk-pp)',
  'LBL_NONE' => 'Tyhjä',
  'LBL_CONTACT' => 'Kontakti:',
  'LBL_EMAIL_ADDRESS' => 'Sähköpostiosoite:',
  'LBL_PHONE' => 'Puhelin:',
  'LBL_EMAIL' => 'Sähköpostiosoite:',
  'LBL_DESCRIPTION_INFORMATION' => 'Kuvauksen tiedot',
  'LBL_DESCRIPTION' => 'Kuvaus:',
  'LBL_NAME' => 'Nimi:',
  'LBL_CONTACT_NAME' => 'Yhteystiedon nimi',
  'LBL_LIST_COMPLETE' => 'Valmis:',
  'LBL_LIST_STATUS' => 'Tila',
  'LBL_DATE_DUE_FLAG' => 'Ei eräpäivää',
  'LBL_DATE_START_FLAG' => 'Ei aloituspäivää',
  'ERR_DELETE_RECORD' => 'Tietuenumero tulee määritellä, jotta voit poistaa kontaktin.',
  'ERR_INVALID_HOUR' => 'Anna tunti välillä 0-24',
  'LBL_DEFAULT_PRIORITY' => 'Keskisuuri',
  'LBL_LIST_MY_TASKS' => 'Omat avoimet tehtävät',
  'LNK_NEW_TASK' => 'Luo tehtävä',
  'LNK_TASK_LIST' => 'Näytä tehtävät',
  'LNK_IMPORT_TASKS' => 'Tuo tehtäviä',
  'LBL_CONTACT_FIRST_NAME'=>'Kontaktin etunimi',
  'LBL_CONTACT_LAST_NAME'=>'Kontaktin sukunimi',
  'LBL_LIST_ASSIGNED_TO_NAME' => 'Vastuuhenkilö',
  'LBL_ASSIGNED_TO_NAME'=>'Vastuuhenkilö:',
  'LBL_LIST_DATE_MODIFIED' => 'Muokattu viimeksi',
  'LBL_CONTACT_ID' => 'Kontaktin ID:',
  'LBL_PARENT_ID' => 'Vanhemman ID:',
  'LBL_CONTACT_PHONE' => 'Kontaktin puhelinnumero:',
  'LBL_PARENT_NAME' => 'Vanhemman tyyppi:',
  'LBL_ACTIVITIES_REPORTS' => 'Toimintakertomus',
  'LBL_EDITLAYOUT' => 'Muokkaa asettelua' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => 'Yleiskatsaus',
  'LBL_HISTORY_SUBPANEL_TITLE' => 'Muistiot',
  'LBL_REVENUELINEITEMS' => 'Tuoterivit',
  //For export labels
  'LBL_DATE_DUE' => 'Eräpäivä',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Vastuuhenkilön nimi',
  'LBL_EXPORT_ASSIGNED_USER_ID' => 'Vastuuhenkilö',
  'LBL_EXPORT_MODIFIED_USER_ID' => 'Muokkaajan ID',
  'LBL_EXPORT_CREATED_BY' => 'Tekijän ID',
  'LBL_EXPORT_PARENT_TYPE' => 'Liittyvät moduuliin',
  'LBL_EXPORT_PARENT_ID' => 'Liittyvät ID:hen',
  'LBL_TASK_CLOSE_SUCCESS' => 'Tehtävä suljettiin.',
  'LBL_ASSIGNED_USER' => 'Vastuuhenkilö',

    'LBL_NOTES_SUBPANEL_TITLE' => 'Muistiot',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}}-moduuli tallentaa tietoja vapaamuotoisista toiminnoista, "to do" -kohteista, ja muista tehtävistä mitkä vaativat suoritusta. {{module_name}} voi liittää yhteen tietueeseen useimmissa moduuleissa "flex relate" -kentän kautta, sekä myös yhteen {{contacts_singular_module}}. {{plural_module_name}} voi luoda Sugarissa {{plural_module_name}}-moduulissa, kopioinnilla, tuomalla {{plural_module_name}}, yms. Kun {{module_name}} on luotu, voit katsoa ja muokata {{module_name}} tietoja {{plural_module_name}}-näkymässä. {{module_name}} tiedoista riippuen, saattaa olla mahdollista muokata {{module_name}} kalenterimoduulista. Jokainen {{module_name}} voidaan linkittää muihin Sugarin tietueisiin, kuten {{accounts_module}}, {{contacts_module}}, {{opportunities_module}}, jne.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}}-moduuli tallentaa tietoja vapaamuotoisista toiminnoista, "to do" -kohteista ja muista tehtävistä, jotka edellyttävät suoritusta.

- Muokkaa tietueen kenttiä klikkaamalla itse kenttää tai Muokkaa-painiketta.

- Muokkaa linkkejä muihin teitueisiin valitsemalla alavasemmalla oleva paneeli ‘tietonäkymään’.

- Luo ja lue käyttäjäkommentteja ja tietueen historiaa {{activitystream_singular_module}}-näkymässä. Aktiviteettivirran saat esiin valitsemalla alavasemmalla olevan paneelin ‘aktiviteettivirta’-näkymään.

- Seuraa tai merkkaa tämä tietue suosikiksi käyttämällä tietueen nimen oikealta puolelta löytyviä kuvakkeita.

- Muut toiminnot löytyvät ‘Muokkaa’-painikkeen oikealla puolella olevasta ‘Toiminnot’-valikosta.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}}-moduuli tallentaa tietoja vapaamuotoisista toiminnoista, "to do" -kohteista ja muista tehtävistä, jotka edellyttävät suoritusta.

{{module_name}}-moduulin luominen:
1. Syötä kentille arvoja.
- Pakollisiksi merkityt kentät pitää täyttää ennen kuin tietue voidaan luoda.
- Saat esille lisää kenttiä tarvittaessa napsauttamalla "Näytä lisää".
2. Paina ‘Tallenna’ luodaksesi tietueen. Tämän jälkeen palaat edelliselle sivulle.',

);
