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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Quadre de comandament del llistat de llistes d&#39;objectius',

  'LBL_MODULE_NAME' => 'Llistes d&#39;objectius',
  'LBL_MODULE_NAME_SINGULAR' => 'Llista d&#39;objectius',
  'LBL_MODULE_ID'   => 'Llistes d&#39;objectius',
  'LBL_MODULE_TITLE' => 'Llistes d&#39;objectius: inici',
  'LBL_SEARCH_FORM_TITLE' => 'Cerca de llistes d&#39;objectius',
  'LBL_LIST_FORM_TITLE' => 'Llistes d&#39;objectius',
  'LBL_PROSPECT_LIST_NAME' => 'Llistes de Públic Objectiu:',
  'LBL_NAME' => 'Nom',
  'LBL_ENTRIES' => 'Total d´Entrades',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Llista d&#39;objectius',
  'LBL_LIST_ENTRIES' => 'Públic Objectiu en la Lista',
  'LBL_LIST_DESCRIPTION' => 'Descripció',
  'LBL_LIST_TYPE_NO' => 'Tipus',
  'LBL_LIST_END_DATE' => 'Data de finalització',
  'LBL_DATE_ENTERED' => 'Data de creació',
  'LBL_MARKETING_ID' => 'Id de Màrqueting',
  'LBL_DATE_MODIFIED' => 'Data Modificació',
  'LBL_MODIFIED' => 'Modificat per',
  'LBL_CREATED' => 'Creada per',
  'LBL_TEAM' => 'Equip',
  'LBL_ASSIGNED_TO' => 'Assignada a',
  'LBL_DESCRIPTION' => 'Descripció',
  'LNK_NEW_CAMPAIGN' => 'Crear Campanya',
  'LNK_CAMPAIGN_LIST' => 'Campanyes',
  'LNK_NEW_PROSPECT_LIST' => 'Crear Llista de Públic Objectiu',
  'LNK_PROSPECT_LIST_LIST' => 'Veure llistes d&#39;objectius',
  'LBL_MODIFIED_BY' => 'Modificat per',
  'LBL_CREATED_BY' => 'Creada per',
  'LBL_DATE_CREATED' => 'Data de creació',
  'LBL_DATE_LAST_MODIFIED' => 'Data de modificació',
  'LNK_NEW_PROSPECT' => 'Crear Públic Objectiu',
  'LNK_PROSPECT_LIST' => 'Objectius',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'Llistes d&#39;objectius',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contactes',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Clients potencials',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'Objectius',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Comptes',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Campanyes',
  'LBL_COPY_PREFIX' =>'Copia de',
  'LBL_USERS_SUBPANEL_TITLE' =>'Usuaris',
  'LBL_TYPE' => 'Tipus',
  'LBL_LIST_TYPE' => 'Tipus',
  'LBL_LIST_TYPE_LIST_NAME'=>'Tipus',
  'LBL_NEW_FORM_TITLE'=>'Nova llista d&#39;objectius',
  'LBL_MARKETING_NAME'=>'Nom de màrqueting',
  'LBL_MARKETING_MESSAGE'=>'Missatge de Màrqueting per Correu',
  'LBL_DOMAIN_NAME'=>'Nom de Domini',
  'LBL_DOMAIN'=>'No hi ha correus per aquest domini',
  'LBL_LIST_PROSPECTLIST_NAME'=>'Nom',
	'LBL_MORE_DETAIL' => 'Més detalls' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{module_name}} consisteix en una col·lecció d&#39;individus o organitzacions que voleu incloure o excloure en un màrqueting massiu {{campaigns_singular_module}}.{{plural_module_name}} pot contenir qualsevol nombre i qualsevol combinació d&#39;objectius, {{contacts_module}}, {{leads_module}}, Usuaris, i {{accounts_module}}. Els objectius es poden agrupar en un {{module_name}} segons un conjunt de criteris predeterminats, com ara el grup d&#39;edat, la ubicació geogràfica, o els hàbits de despesa. {{plural_module_name}} s&#39;utilitzen en el màrqueting de correu electrònic massiu {{campaigns_module}} que es pot configurar en el mòdul {{campaigns_module}}.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'El mòdul {{module_name}} consisteix en una col·lecció de persones o organitzacions que voleu incloure o excloure d&#39;una {{campaigns_singular_module}} de màrqueting massiva.

- Editeu els camps d&#39;aquest registre fent clic en un camp individual o amb el botó Edita.
- Vegeu o modifiqueu enllaços a altres registres als subpanells, inclosos els destinataris de {{campaigns_singular_module}}, mitjançant la commutació de la subfinestra inferior esquerra a la "Vista de dades".
- Feu i vegeu comentaris d&#39;usuari i l&#39;historial de canvis del registre al {{activitystream_singular_module}} mitjançant la commutació de la subfinestra inferior esquerra al "Canal d&#39;activitat".
- Feu el seguiment d&#39;aquest favorit o marqueu-lo com a favorit amb les icones que hi ha a la dreta del nom del registre.
- Hi ha accions addicionals disponibles al menú desplegable d&#39;accions a la dreta del botó Edita.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'El mòdul {{module_name}} consisteix en una col·lecció de persones o organitzacions que voleu incloure o excloure d&#39;una {{campaigns_singular_module}} de màrqueting massiva.

Per crear un {{module_name}}:
1. Proporcioneu els valors desitjats per als camps.
 - Els camps marcats "Obligatori" s&#39;han de completar abans de desar.
 - Feu clic a "Mostra més" per exposar camps addicionals si és necessari.
2. Feu clic a "Desa" per finalitzar el nou registre i tornar a la pàgina anterior.
3. Després de desar, feu servir els subpanells disponibles a la vista de registres de l&#39;objectiu per afegir destinataris de {{campaigns_singular_module}}.',
);
