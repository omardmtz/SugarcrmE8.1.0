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
  'LBL_MODULE_NAME' => 'Vendes',
  'LBL_MODULE_TITLE' => 'Venda: inici',
  'LBL_SEARCH_FORM_TITLE' => 'Cerca de vendes',
  'LBL_VIEW_FORM_TITLE' => 'Vista de vendes',
  'LBL_LIST_FORM_TITLE' => 'Llista de vendes',
  'LBL_SALE_NAME' => 'Nom de Venda:',
  'LBL_SALE' => 'Venda:',
  'LBL_NAME' => 'Nom de la venda',
  'LBL_LIST_SALE_NAME' => 'Nom',
  'LBL_LIST_ACCOUNT_NAME' => 'Nom del compte',
  'LBL_LIST_AMOUNT' => 'Quantitat',
  'LBL_LIST_DATE_CLOSED' => 'Tancament',
  'LBL_LIST_SALE_STAGE' => 'Etapa de Vendes',
  'LBL_ACCOUNT_ID'=>'ID del compte',
  'LBL_TEAM_ID' =>'ID de l&#39;equip',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'Venda - Actualització de Moneda',
  'UPDATE_DOLLARAMOUNTS' => 'Actualitza les quantitats en dòlars americans',
  'UPDATE_VERIFY' => 'Verificar Quantitats',
  'UPDATE_VERIFY_TXT' => 'Verifica que els valors de les quantitats en les vendes son números decimals vàlids amb només caràcters numérics (0-9) i decimals(.)',
  'UPDATE_FIX' => 'Corregir Quantitats',
  'UPDATE_FIX_TXT' => 'Intenta corregir qualsevol quantitat no vàlida creant un número decimal vàlid a partir de la quantitat actual. Es fa una còpia de seguretat de totes les quantitats modificades en el camp de la base de dades amount_backup. Si realitzeu aquesta operació i observeu alguna incidència, no la torneu a repetir sense restaurar els valors previs des de la còpia de seguretat, doncs en cas contrari, podria sobreescriure la còpia de seguretat amb les noves dades no vàlides.',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'Actualitza les quantitats en Dólars EEUU per les vendes basades en el conjunt actual de canvis de moneda. Aquest valor s´usa per calcular gràfiques i vistes de llistes de quantitats monetàries.',
  'UPDATE_CREATE_CURRENCY' => 'Creació d&#39;una moneda nova:',
  'UPDATE_VERIFY_FAIL' => 'Verificació de l&#39;error del registre:',
  'UPDATE_VERIFY_CURAMOUNT' => 'Moneda Actual:',
  'UPDATE_VERIFY_FIX' => 'La correcció donaria',
  'UPDATE_INCLUDE_CLOSE' => 'Inclou els registres tancats',
  'UPDATE_VERIFY_NEWAMOUNT' => 'Nova Quantitat:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'Nova Moneda:',
  'UPDATE_DONE' => 'Fet',
  'UPDATE_BUG_COUNT' => 'Incidències detectades que s&#39;han intentat solucionar:',
  'UPDATE_BUGFOUND_COUNT' => 'Incidències detectades:',
  'UPDATE_COUNT' => 'Registres Actualitzats:',
  'UPDATE_RESTORE_COUNT' => 'Quantitats de registre restaurades:',
  'UPDATE_RESTORE' => 'Restaurar Quantitats',
  'UPDATE_RESTORE_TXT' => 'Restaura els valors de les quantitats des de les còpies de seguretat creades durant la correcció.',
  'UPDATE_FAIL' => 'No s&#39;ha pogut actualitzar; ',
  'UPDATE_NULL_VALUE' => 'La quantitat es NULL, establint-la a 0;',
  'UPDATE_MERGE' => 'Unificar Monedes',
  'UPDATE_MERGE_TXT' => 'Unifica múltiples monedes en una única moneda. Si detecta que hi ha múltiples registres de tipus de moneda per la mateixa moneda, pot unificarles. Això també unificarà les monedes per a la resta de mòduls.',
  'LBL_ACCOUNT_NAME' => 'Nom del compte:',
  'LBL_AMOUNT' => 'Quantitat:',
  'LBL_AMOUNT_USDOLLAR' => 'Import en Dólars EUA:',
  'LBL_CURRENCY' => 'Moneda:',
  'LBL_DATE_CLOSED' => 'Data de tancament prevista:',
  'LBL_TYPE' => 'Tipus:',
  'LBL_CAMPAIGN' => 'Campanya:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Clients potencials',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projects',  
  'LBL_NEXT_STEP' => 'Pas següent:',
  'LBL_LEAD_SOURCE' => 'Origen del client potencial:',
  'LBL_SALES_STAGE' => 'Etapa de Vendes:',
  'LBL_PROBABILITY' => 'Probabilitat (%):',
  'LBL_DESCRIPTION' => 'Descripció:',
  'LBL_DUPLICATE' => 'Possible venda duplicada',
  'MSG_DUPLICATE' => 'El registre per a la venda que crearà podría ser un duplicat en un altre registe de venda existent. Els registres de venda amb noms similars s&#39;enumeren a continuació.<br>Faci clic a Guardar per continuar amb la creació d&#39;aquesta venda, o en Cancelar per tornar al mòdul sense crear la venda.',
  'LBL_NEW_FORM_TITLE' => 'Crear venda',
  'LNK_NEW_SALE' => 'Crear venda',
  'LNK_SALE_LIST' => 'Venta',
  'ERR_DELETE_RECORD' => 'Per suprimir la venda, heu d&#39;especificar un número de registre.',
  'LBL_TOP_SALES' => 'Les Meves Principals Vendes Obertes',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'Està segur de que vol eliminar aquest contacte de la venda?',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'Està segur de que vol eliminar aquesta venda del projecte?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Activitats',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'Històrial',
    'LBL_RAW_AMOUNT'=>'Quantitat bruta',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contactes',
	'LBL_ASSIGNED_TO_NAME' => 'Usuari:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'Usuari assignat',
  'LBL_MY_CLOSED_SALES' => 'Les Meves Vendes Tancades',
  'LBL_TOTAL_SALES' => 'Vendes Totals',
  'LBL_CLOSED_WON_SALES' => 'Vendes guanyades tancades',
  'LBL_ASSIGNED_TO_ID' =>'Assignat a ID',
  'LBL_CREATED_ID'=>'Creat per ID',
  'LBL_MODIFIED_ID'=>'Modificat per ID',
  'LBL_MODIFIED_NAME'=>'Modificat per nom d&#39;usuari',
  'LBL_SALE_INFORMATION'=>'Informació sobre la Venda',
  'LBL_CURRENCY_ID'=>'ID Moneda',
  'LBL_CURRENCY_NAME'=>'Nom de la moneda',
  'LBL_CURRENCY_SYMBOL'=>'Símbol de la moneda',
  'LBL_EDIT_BUTTON' => 'Editar',
  'LBL_REMOVE' => 'Suprimir',
  'LBL_CURRENCY_RATE' => 'Tipus de canvi',

);

