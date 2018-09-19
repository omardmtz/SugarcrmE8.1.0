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
    'ERR_ADD_RECORD' => 'Ha d&#39;especificar un número de registre per a afegir a aquest usuari a l´equip.',
    'ERR_DUP_NAME' => 'El nom de l&#39;equip ja existeix, posa un altre.',
    'ERR_DELETE_RECORD' => 'Per suprimir aquest equip, heu d&#39;especificar un número de registre.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Error. El equip seleccionat <b>({0})</b> és un equip que ha decidit eliminar. Si us plau seleccioneu un altre equip.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Error. No pot esborrar un usuari si l&#39;equip del privat no ha estat eliminat.',
    'LBL_DESCRIPTION' => 'Descripció:',
    'LBL_GLOBAL_TEAM_DESC' => 'Visibilitat Global',
    'LBL_INVITEE' => 'Membres de l&#39;equip',
    'LBL_LIST_DEPARTMENT' => 'Departament',
    'LBL_LIST_DESCRIPTION' => 'Descripció',
    'LBL_LIST_FORM_TITLE' => 'Llista d&#39;equips',
    'LBL_LIST_NAME' => 'Nom',
    'LBL_FIRST_NAME' => 'Nom:',
    'LBL_LAST_NAME' => 'Cognoms:',
    'LBL_LIST_REPORTS_TO' => 'Informa A',
    'LBL_LIST_TITLE' => 'Títol',
    'LBL_MODULE_NAME' => 'Equips',
    'LBL_MODULE_NAME_SINGULAR' => 'Equip',
    'LBL_MODULE_TITLE' => 'Equips: inici',
    'LBL_NAME' => 'Nom de l&#39;equip:',
    'LBL_NAME_2' => 'Nom de l&#39;equip(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Nom d&#39;Equip Principal',
    'LBL_NEW_FORM_TITLE' => 'Nou equip',
    'LBL_PRIVATE' => 'Privat',
    'LBL_PRIVATE_TEAM_FOR' => 'Equip privat per:',
    'LBL_SEARCH_FORM_TITLE' => 'Cerca d&#39;equips',
    'LBL_TEAM_MEMBERS' => 'Membres de l&#39;equip',
    'LBL_TEAM' => 'Equips:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Usuaris',
    'LBL_USERS' => 'Usuaris',
    'LBL_REASSIGN_TEAM_TITLE' => 'Hi ha registres assignats a l&#39;equip següent (s): <b>{0}</b><br>Abans d&#39;eliminar l&#39;equip (s), primer ha de reassignar aquests registres a un nou equip. Seleccioneu un equip per a ser utilitzat com a reemplaçament.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Reassignar',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Reassignar [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Procedir a actualizar els registres amb el nou equip?',
    'LBL_REASSIGN_TABLE_INFO' => 'Actualitzant taula (0)',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'L&#39;operació ha estat completada satisfactoriament.',
    'LNK_LIST_TEAM' => 'Equips',
    'LNK_LIST_TEAMNOTICE' => 'Notícies d´Equip',
    'LNK_NEW_TEAM' => 'Crear equip',
    'LNK_NEW_TEAM_NOTICE' => 'Nova noticia d´equip',
    'NTC_DELETE_CONFIRMATION' => 'Esteu segur que voleu suprimir aquest registre?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Està segur que desitja treure aquest usuari de l´equip?',
    'LBL_EDITLAYOUT' => 'Editar disseny' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Permisos basats en equip',
    'LBL_TBA_CONFIGURATION_DESC' => 'Permetre l&#39;accés de l&#39;equip i gestionar l&#39;accés per mòdul.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Permetre permisos basats en equip',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Seleccioneu els mòduls a habilitar',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Permetre permisos basats en equip us permetrà assignar drets específics d&#39;accés als equips i usuaris per a mòduls individuals, mitjançant la Gestió de rols.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Impossibilitar els permisos basats en equips per a un mòdul invertirà totes les dades associades amb els permisos basats en equip per a aquest mòdul, incloses les definicions de processos o els processos que utilitzen la funció. Això inclou tots els rols que utilitzen l'opció "Propietari i equip seleccionat" per a aquest mòdul i qualsevol permís basat en equip per als registres d'aquest mòdul. També us recomanem que utilitzeu l'eina de reconstrucció i reparació ràpida per esborrar la memòria cau de sistema després d'impossibilitar els permisos basats en equips per a qualsevol mòdul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Avís:</strong> Impossibilitar els permisos basats en equips per a un mòdul invertirà totes les dades associades amb els permisos basats en equip per a aquest mòdul, incloses les definicions de processos o els processos que utilitzen la funció. Això inclou tots els rols que utilitzen l'opció "Propietari i equip seleccionat" per a aquest mòdul i qualsevol permís basat en equip per als registres d'aquest mòdul. També us recomanem que utilitzeu l'eina de reconstrucció i reparació ràpida per esborrar la memòria cau de sistema després d'impossibilitar els permisos basats en equips per a qualsevol mòdul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Impossibilitar els permisos basats en equips per a un mòdul invertirà totes les dades associades amb els permisos basats en equip per a aquest mòdul, incloses les definicions de processos o els processos que utilitzen la funció. Això inclou tots els rols que utilitzen l'opció "Propietari i equip seleccionat" per a aquest mòdul i qualsevol permís basat en equip per als registres d'aquest mòdul. També us recomanem que utilitzeu l'eina de reconstrucció i reparació ràpida per esborrar la memòria cau de sistema després d'impossibilitar els permisos basats en equips per a qualsevol mòdul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Avís:</strong> Impossibilitar els permisos basats en equips per a un mòdul invertirà totes les dades associades amb els permisos basats en equip per a aquest mòdul, incloses les definicions de processos o els processos que utilitzen la funció. Això inclou tots els rols que utilitzen l'opció "Propietari i equip seleccionat" per a aquest mòdul i qualsevol permís basat en equip per als registres d'aquest mòdul. També us recomanem que utilitzeu l'eina de reconstrucció i reparació ràpida per esborrar la memòria cau de sistema després d'impossibilitar els permisos basats en equips per a qualsevol mòdul.
STR
,
);
