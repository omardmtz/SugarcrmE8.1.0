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

$mod_strings = array(
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => 'Muistiinpanoluettelon työpöytä',

    'ERR_DELETE_RECORD' => 'Tietuenumero tulee määritellä, jotta voit poistaa asiakkaan.',
    'LBL_ACCOUNT_ID' => 'Asiakkaan ID:',
    'LBL_CASE_ID' => 'Palvelupyynnön ID:',
    'LBL_CLOSE' => 'Sulje:',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => 'Kontaktin ID:',
    'LBL_CONTACT_NAME' => 'Kontaktin nimi:',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Muistiot',
    'LBL_DESCRIPTION' => 'Kuvaus',
    'LBL_EMAIL_ADDRESS' => 'Sähköpostiosoite:',
    'LBL_EMAIL_ATTACHMENT' => 'Sähköpostin liite',
    'LBL_EMAIL_ATTACHMENT_FOR' => 'Sähköpostin liite',
    'LBL_FILE_MIME_TYPE' => 'Mime-tyyppi',
    'LBL_FILE_EXTENSION' => 'Tiedostotunniste',
    'LBL_FILE_SOURCE' => 'Lähdetiedosto',
    'LBL_FILE_SIZE' => 'Tiedoston koko',
    'LBL_FILE_URL' => 'Tiedoston URL',
    'LBL_FILENAME' => 'Liite:',
    'LBL_LEAD_ID' => 'Liidin ID:',
    'LBL_LIST_CONTACT_NAME' => 'Kontakti',
    'LBL_LIST_DATE_MODIFIED' => 'Päivitetty',
    'LBL_LIST_FILENAME' => 'Liite',
    'LBL_LIST_FORM_TITLE' => 'Muistiolista',
    'LBL_LIST_RELATED_TO' => 'Liittyy',
    'LBL_LIST_SUBJECT' => 'Aihe',
    'LBL_LIST_STATUS' => 'Tila',
    'LBL_LIST_CONTACT' => 'Kontakti',
    'LBL_MODULE_NAME' => 'Muistiot',
    'LBL_MODULE_NAME_SINGULAR' => 'Muistio',
    'LBL_MODULE_TITLE' => 'Muistiot: Etusivu',
    'LBL_NEW_FORM_TITLE' => 'Luo muistio tai lisää liite',
    'LBL_NEW_FORM_BTN' => 'Lisää muistio',
    'LBL_NOTE_STATUS' => 'Muistio',
    'LBL_NOTE_SUBJECT' => 'Aihe:',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Muistiot ja liitteet',
    'LBL_NOTE' => 'Muistio:',
    'LBL_OPPORTUNITY_ID' => 'Myyntimahdollisuuden ID:',
    'LBL_PARENT_ID' => 'Vanhemman ID:',
    'LBL_PARENT_TYPE' => 'Kannan tyyppi',
    'LBL_EMAIL_TYPE' => 'Sähköpostin tyyppi',
    'LBL_EMAIL_ID' => 'Sähköpostin tunnus',
    'LBL_PHONE' => 'Puhelin:',
    'LBL_PORTAL_FLAG' => 'Näytä Portalissa?',
    'LBL_EMBED_FLAG' => 'Upota sähköpostiin?',
    'LBL_PRODUCT_ID' => 'Tarjotun tuotteen ID:',
    'LBL_QUOTE_ID' => 'Tarjouksen ID:',
    'LBL_RELATED_TO' => 'Liittyy:',
    'LBL_SEARCH_FORM_TITLE' => 'Muistioiden haku',
    'LBL_STATUS' => 'Tila',
    'LBL_SUBJECT' => 'Aihe',
    'LNK_IMPORT_NOTES' => 'Tuo merkintöjä',
    'LNK_NEW_NOTE' => 'Luo merkintä tai liite',
    'LNK_NOTE_LIST' => 'Näytä merkinnät',
    'LBL_MEMBER_OF' => 'Jäsen:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Vastuuhenkilö',
    'LBL_OC_FILE_NOTICE' => 'Kirjaudu palvelimeen nähdäksesi tiedoston',
    'LBL_REMOVING_ATTACHMENT' => 'Poistetaan liitettä ...',
    'ERR_REMOVING_ATTACHMENT' => 'Liitteen poistaminen epäonnistui...',
    'LBL_CREATED_BY' => 'Luoja',
    'LBL_MODIFIED_BY' => 'Muokkaaja',
    'LBL_SEND_ANYWAYS' => 'Sähköpostissa ei ole aihetta. Lähetä/tallenna siitä huolimatta?',
    'LBL_LIST_EDIT_BUTTON' => 'Muokkaa',
    'LBL_ACTIVITIES_REPORTS' => 'Toimintakertomus',
    'LBL_PANEL_DETAILS' => 'Tiedot',
    'LBL_NOTE_INFORMATION' => 'Yleiskatsaus',
    'LBL_MY_NOTES_DASHLETNAME' => 'Muistiot',
    'LBL_EDITLAYOUT' => 'Muokkaa asettelua' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => 'Etunimi',
    'LBL_LAST_NAME' => 'Sukunimi',
    'LBL_EXPORT_PARENT_TYPE' => 'Liittyy moduuliin',
    'LBL_EXPORT_PARENT_ID' => 'Liittyvän tietueen ID',
    'LBL_DATE_ENTERED' => 'Luontipäivä',
    'LBL_DATE_MODIFIED' => 'Muokattu viimeksi',
    'LBL_DELETED' => 'Poistettu',
    'LBL_REVENUELINEITEMS' => 'Tuoterivit',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}}-moduuli koostuu yksittäisistä {{plural_module_name}}-tietueista, jotka sisältävät tekstiä tai kyseistä tietuetta koskevan liitteen. {{module_name}}-tietueita voidaan liittää yhteen tietueeseen useimmissä moduuleissa flex relate -kentän kautta ja ne voivat liittyä myös yhteen {{contacts_singular_module}} in. {{plural_module_name}} voivat sisältää yleistä tekstiä tietueesta tai tietueeseen liittyvän liitteen. Voit luoda {{plural_module_name}}-tietueen Sugarissa eri tavoin, kuten {{plural_module_name}}-moduulin kautta, tuomalla {{plural_module_name}}-moduulin, Historia-alipaneelin kautta jne. Kun {{module_name}} on luotu, voit näyttää ja muokata {{module_name}} n tietoja {{plural_module_name}}-tietuenäkymän kautta. Kukin {{module_name}}-tietue voidaan sitten liittää muihin Sugarin tietueisiin, kuten {{accounts_module}}, {{contacts_module}}, {{opportunities_module}} ja moniin muihin.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}}-moduuli sisältää yksittäisiä {{plural_module_name}}-tietueita, jotka koostuvat tekstistä tai liitetiedostosta.

- Muokkaa tietueen kenttiä klikkaamalla itse kenttää tai Muokkaa-painiketta.

- Muokkaa linkkejä muihin teitueisiin valitsemalla alavasemmalla oleva paneeli ‘tietonäkymään’.

- Luo ja lue käyttäjäkommentteja ja tietueen historiaa {{activitystream_singular_module}}-näkymässä. Aktiviteettivirran saat esiin valitsemalla alavasemmalla olevan paneelin aktiviteettivirta-näkymään.

- Seuraa tai merkkaa tämä tietue suosikiksi käyttämällä tietueen nimen oikealta puolelta löytyviä kuvakkeita.

- Muut toiminnot löytyvät ‘Muokkaa’-painikkeen oikealla puolella olevasta ‘Toiminnot’-valikosta.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{module_name}}-moduulin luominen:
1. Syötä kentille arvoja. 
- Pakollisiksi merkityt kentät pitää täyttää ennen tallentamista. 
- Saat esille lisää kenttiä tarvittaessa napsauttamalla "Näytä lisää".
2. Paina "Tallenna" luodaksesi uuden tietueen. Tämän jälkeen palaat edelliselle sivulle.',
);
