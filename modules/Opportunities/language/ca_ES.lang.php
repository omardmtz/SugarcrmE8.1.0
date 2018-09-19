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
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Quadre de comandament del llistat d&#39;oportunitats',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Quadre de comandament del registre d&#39;oportunitats',

    'LBL_MODULE_NAME' => 'Oportunitats',
    'LBL_MODULE_NAME_SINGULAR' => 'Oportunitat',
    'LBL_MODULE_TITLE' => 'Oportunitats: inici',
    'LBL_SEARCH_FORM_TITLE' => 'Cerca d&#39;oportunitats',
    'LBL_VIEW_FORM_TITLE' => 'Vista d&#39;oportunitats',
    'LBL_LIST_FORM_TITLE' => 'Llista d&#39;oportunitats',
    'LBL_OPPORTUNITY_NAME' => 'Nom de l&#39;oportunitat:',
    'LBL_OPPORTUNITY' => 'Oportunitat:',
    'LBL_NAME' => 'Nom Oportunitat',
    'LBL_INVITEE' => 'Contactes',
    'LBL_CURRENCIES' => 'Monedes',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Nom',
    'LBL_LIST_ACCOUNT_NAME' => 'Nom del compte',
    'LBL_LIST_DATE_CLOSED' => 'Data de tancament prevista',
    'LBL_LIST_AMOUNT' => 'Probable',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Quantitat',
    'LBL_ACCOUNT_ID' => 'ID del compte',
    'LBL_CURRENCY_RATE' => 'Divisa',
    'LBL_CURRENCY_ID' => 'ID Moneda',
    'LBL_CURRENCY_NAME' => 'Nom de la moneda',
    'LBL_CURRENCY_SYMBOL' => 'Símbol de la moneda',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Oportunitat - Actualitzar Moneda',
    'UPDATE_DOLLARAMOUNTS' => 'Actualitza les quantitats en dòlars americans',
    'UPDATE_VERIFY' => 'Verificar Quantitats',
    'UPDATE_VERIFY_TXT' => 'Verificar que els valors de les quantitats en les oportunitats son números decimals vàlids amb només caràcters numérics (0-9) i decimals(.)',
    'UPDATE_FIX' => 'Corregir Quantitats',
    'UPDATE_FIX_TXT' => 'Intenta corregir qualsevol quantitat no vàlida creant un número decimal vàlid a partir de la quantitat actual. Es fa una còpia de seguretat de totes les quantitats modificades en el camp de la base de dades amount_backup. Si realitzeu aquesta operació i observeu alguna incidència, no la torneu a repetir sense restaurar els valors previs des de la còpia de seguretat, doncs en cas contrari, podria sobreescriure la còpia de seguretat amb les noves dades no vàlides.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Actualitza les quantitats en Dòlars EEUU per les oportunitats basades en el canvi actual de monedes. Aquest valor es fa servir per calcular gràfics i vistes de llistes de quantitats monétaries.',
    'UPDATE_CREATE_CURRENCY' => 'Creació d&#39;una moneda nova:',
    'UPDATE_VERIFY_FAIL' => 'Verificació de l&#39;error del registre:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Quantitat actual:',
    'UPDATE_VERIFY_FIX' => 'La correcció donaria',
    'UPDATE_INCLUDE_CLOSE' => 'Inclou els registres tancats',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Nova quantitat:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Nova moneda:',
    'UPDATE_DONE' => 'Fet',
    'UPDATE_BUG_COUNT' => 'Incidències detectades que s&#39;han intentat solucionar:',
    'UPDATE_BUGFOUND_COUNT' => 'Incidències detectades:',
    'UPDATE_COUNT' => 'Registres actualitzats:',
    'UPDATE_RESTORE_COUNT' => 'Quantitats de registre restaurades:',
    'UPDATE_RESTORE' => 'Restaurar Quantitats',
    'UPDATE_RESTORE_TXT' => 'Restaura els valors de les quantitats des de les còpies de seguretat creades durant la correcció.',
    'UPDATE_FAIL' => 'No s&#39;ha pogut actualitzar; ',
    'UPDATE_NULL_VALUE' => 'La quantitat es NULL, establint-la a 0;',
    'UPDATE_MERGE' => 'Unificar Monedes',
    'UPDATE_MERGE_TXT' => 'Unifica múltiples monedes en una única moneda. Si detecta que hi ha múltiples registres de tipus moneda per la mateixa moneda, pot unificar-les. Això també unificará les monedes per la resta de mòduls.',
    'LBL_ACCOUNT_NAME' => 'Nom del compte:',
    'LBL_CURRENCY' => 'Moneda:',
    'LBL_DATE_CLOSED' => 'Data de tancament prevista:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Data prevista de tancament',
    'LBL_TYPE' => 'Tipus:',
    'LBL_CAMPAIGN' => 'Campanya:',
    'LBL_NEXT_STEP' => 'Pas següent:',
    'LBL_LEAD_SOURCE' => 'Origen del client potencial',
    'LBL_SALES_STAGE' => 'Etapa de vendes:',
    'LBL_SALES_STATUS' => 'Estat',
    'LBL_PROBABILITY' => 'Probabilitat (%):',
    'LBL_DESCRIPTION' => 'Descripció:',
    'LBL_DUPLICATE' => 'Possible oportunitat duplicada',
    'MSG_DUPLICATE' => 'El registre per l´oportunitat que va a crear podría ser un duplicat d´un altre registre d´oportunitat existent. Els registres d´oportunitat amb noms similars es llisten a continuació.<br>Faci clic en Guardar per continuar amb la creació d´aquesta oportunitat, o en Cancelar per tornar al mòdul sense crear l´oportunitat.',
    'LBL_NEW_FORM_TITLE' => 'Crear oportunitat',
    'LNK_NEW_OPPORTUNITY' => 'Crea oportunitat',
    'LNK_CREATE' => 'Crear tracte',
    'LNK_OPPORTUNITY_LIST' => 'Oportunitats',
    'ERR_DELETE_RECORD' => 'Per suprimir l&#39;oportunitat, heu d&#39;especificar un número de registre.',
    'LBL_TOP_OPPORTUNITIES' => 'Les Meves Principals Oportunitats',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'Està segur de que vol eliminar aquest contacte de l´oportunitat?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'Està segur de que vol eliminar aquesta oportunitat del projecte?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Oportunitats',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activitats',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Històrial',
    'LBL_RAW_AMOUNT' => 'Quantitat bruta',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Clients potencials',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contactes',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Documents',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projectes',
    'LBL_ASSIGNED_TO_NAME' => 'Assignat a:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Usuari assignat',
    'LBL_LIST_SALES_STAGE' => 'Etapa de Vendes',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Les Meves Oportunitats Tancades',
    'LBL_TOTAL_OPPORTUNITIES' => 'Total d´Oportunitats',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Oportunitats guanyades tancades',
    'LBL_ASSIGNED_TO_ID' => 'Usuari assignat:',
    'LBL_CREATED_ID' => 'Creat per ID',
    'LBL_MODIFIED_ID' => 'Modificat per ID',
    'LBL_MODIFIED_NAME' => 'Modificat per nom d&#39;usuari',
    'LBL_CREATED_USER' => 'Creat per usuari',
    'LBL_MODIFIED_USER' => 'Modificar per usuari',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Campanya',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Projectes',
    'LABEL_PANEL_ASSIGNMENT' => 'Assignació',
    'LNK_IMPORT_OPPORTUNITIES' => 'Importa oportunitats',
    'LBL_EDITLAYOUT' => 'Editar disseny' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'ID de campanya',
    'LBL_OPPORTUNITY_TYPE' => 'Tipus d&#39;oportunitat',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Nom d&#39;usuari assignat',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID d&#39;usuari assignat',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Modificat per ID',
    'LBL_EXPORT_CREATED_BY' => 'Creat per ID',
    'LBL_EXPORT_NAME' => 'Nom',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Els correus electrònics de contactes relacionats',
    'LBL_FILENAME' => 'Adjunt:',
    'LBL_PRIMARY_QUOTE_ID' => 'Pressupost primària',
    'LBL_CONTRACTS' => 'Contractes',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Contractes',
    'LBL_PRODUCTS' => 'Productes',
    'LBL_RLI' => 'Línia d&#39;impostos articles',
    'LNK_OPPORTUNITY_REPORTS' => 'Informes d&#39;oportunitats',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Pressuposts',
    'LBL_TEAM_ID' => 'ID de l&#39;equip',
    'LBL_TIMEPERIODS' => 'Períodes de temps',
    'LBL_TIMEPERIOD_ID' => 'TimePeriod ID',
    'LBL_COMMITTED' => 'Compromès',
    'LBL_FORECAST' => 'Incloure en la previsió',
    'LBL_COMMIT_STAGE' => 'Etapa compromesa',
    'LBL_COMMIT_STAGE_FORECAST' => 'Previsió',
    'LBL_WORKSHEET' => 'Full de càlcul',

    'TPL_RLI_CREATE' => 'Una oportunitat ha de tenir associada una línia d&#39;ingressos.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Crear un element de línia d&#39;ingressos.',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Elements de línies d&#39;oferta',
    'LBL_RLI_SUBPANEL_TITLE' => 'Línia d&#39;ingressos per articles',

    'LBL_TOTAL_RLIS' => '# del Total de Línies d&#39;ingressos per articles',
    'LBL_CLOSED_RLIS' => '# Línies d&#39;ingressos tancades',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'No pot esborrar oportunitats que continguin línies d&#39;ingressos tancades',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Un o més dels registres seleccionats conté línies d&#39;ingressos tancades, i no es pot esborrar.',
    'LBL_INCLUDED_RLIS' => '# d&#39;articles de línees d&#39;ingressos incloses',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Pressupostos',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Jerarquia d&#39;oportunitats',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Definiu el camp de data de tancament esperada als registres d&#39;oportunitat resultants per què siguin les dates de tancament més primerenques o més tardanes del Elements de línies d&#39;ingressos existents.',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Pipeline tota és',

    'LBL_OPPORTUNITY_ROLE'=>'Rol d&#39;oportunitat',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notes',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Si feu clic a Confirma, suprimireu TOTES les dates de previsió i canviareu la vista d&#39;oportunitats. Si açò no és el que volíeu, feu clic a cancel·la per tornar a la configuració anterior.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'En fer clic a Confirmar eliminareu TOTES les dades de les previsions i canviareu la visualització d&#39;oportunitats. '
        .'També es desactivaran TOTES les definicions de procés amb un mòdul de destinació d&#39;elements de línia d&#39;ingressos. '
        .'Si no ho voleu fer, feu clic a cancel·la per tornar a la configuració anterior.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Si tots els elements de línies d&#39;ingressos estan tancats i al menys un s&#39;ha guanyat,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'la fase de vendes d&#39;oportunitats es configura com "Tancat guanyat".',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Si tots els elements de la línia d&#39;ingressos estan a la fase de venda "Tancat perdut",',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'la fase de vendes d&#39;oportunitats es configura com "Tancat perdut".',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Si encara hi queda qualsevol línia d&#39;ingressos oberta,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'l&#39;oportunitat es marcarà amb la fase de venda menys avançada.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Després d&#39;iniciar aquest canvi, les notes de resum dels elements de línies d&#39;ingressos es construiran en segon terme. Quan les notes estiguin completes i disponibles, s&#39;enviarà una notificació a l&#39;adreça de correu electrònic del seu perfil. Si la instància s&#39;ha configurat per {{forecasts_module}}, Sugar també us enviarà una notificació quan els registres del {{module_name}} se sincronitzen amb el mòdul {{forecasts_module}} i estan disponibles per al nou {{forecasts_module}}. Teniu en compte que la instància s&#39;ha de configurar per enviar correus electrònics a Administració > Configuració de correu electrònic perquè s&#39;enviïn les notificacions.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Després d&#39;iniciar aquest canvi, es crearan els registres dels elements de línies d&#39;ingressos en segon terme per a cada {{module_name}} existent. Quan els elements de línia d&#39;ingressos estiguin complets  i disponibles, s&#39;enviarà una notificació a l&#39;adreça de correu electrònic del seu perfil. Si la instància s&#39;ha configurat per {{forecasts_module}}, Sugar també us enviarà una notificació quan els registres del {{module_name}} se sincronitzen amb el mòdul {{forecasts_module}} i estan disponibles per al nou {{forecasts_module}}. Teniu en compte que la instància s&#39;ha de configurar per enviar correus electrònics a Administració > Configuració de correu electrònic perquè s&#39;enviïn les notificacions.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'El mòdul {{plural_module_name}} li permet monitoritzar les vendes individuals de principi a fí. Cada registre de {{module_name}} representa una venda potencial i inclou dades relevants per a la venda així com altres registres importants com ara {{quotes_module}}, {{contacts_module}}, etc. Un {{module_name}} normalment prograssarà mitjançant les diferents etapes de venda, fins que es marque com a "guanyada" o "perduda". Els {{plural_module_name}} es poden aprofitar fins i tot més utilitzant el mòdul de {{forecasts_singular_module}} de Sugar per tal d&#39;entendre i predir tendències en les vendes, a més de dirigir el treball per tal d&#39;aconseguir quotes de venda.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'El mòdul {{plural_module_name}} li permet monitoritzar les vendes individuals i elements de línea que pertanyen a aquestes vendes de principi a fí. Cada registre de {{module_name}} representa una venda potencial i inclou dades relevants per a la venda així com altres registres importants, com ara {{quotes_module}}, {{contacts_module}}, etc. 

- Per tal d&#39;editar els camps d&#39;aquest registre, feu clic en un camp individual, o en el botó Editar.
-Vegeu o modifiqueu els enllaços amb altres registres en el subpanel cambiant el panel inferior de l&#39;esquerra a "Visualització de Dades".
-Escriviu i vegeu comentàris d&#39;usuàris i l&#39;historial de canvis del registre en {{activitystream_singular_module}} canviant el panel inferior de l&#39;esquerra a "Fluxe d&#39;Activitats".
-Seguiu o marqueu com a preferit aquest registre mitjançant les icones a la dreta del nom del registre. 
-Hi ha accions addicionals en el menú desplegable d&#39;accions a la dreta del botó Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'El mòdul {{plural_module_name}} li permet monitoritzar les vendes individuals i elements de línea que pertanyen a aquestes vendes de principi a fí. Cada registre de {{module_name}} representa una venda potencial i inclou dades relevants per a la venda així com altres registres importants, com ara {{quotes_module}}, {{contacts_module}}, etc. 

Per tal de crear un {{module_name}}:
1. Proporcioneu els valors per als camps com desitgeu.
-Els camps marcats com a "Obligatoris" han de ser complerts abans de guardar.
-Feu clic en "Mostreu més" per a mostrar els camps addicionals si fòs necessàri.
2. Feu clic en "Guardar" per tal de finalitzar el registre i tornar a la pàgina anterior.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Sincronitzar amb Marketo&reg;',
    'LBL_MKTO_ID' => 'ID Marketo Lead',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => '10 millors oportunitats de venda',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Mostra les 10 millors oportunitat de venda a un gràfic de bombolles',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Les meves oportunitats',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Les oportunitats del meu equip",
);
