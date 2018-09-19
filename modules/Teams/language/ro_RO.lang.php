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
    'ERR_ADD_RECORD' => 'Trebuie să specificaţi un număr de inregistrare pentru a adăuga un utilizator in această echipă.',
    'ERR_DUP_NAME' => 'Numele echipei exista deja, vă rugăm să alegeţi altul.',
    'ERR_DELETE_RECORD' => 'Trebuie să specifici un număr de înregistrare pentru a șterge echipa.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Eroare. Echipa selectata((0)) este o echipa care aţi ales să o ştergeţi. Vă rugăm să selectaţi o altă echipă',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Eroare. Nu poti şterge un utilizator privat a cărui echipă nu a fost ştearsa.',
    'LBL_DESCRIPTION' => 'Descriere',
    'LBL_GLOBAL_TEAM_DESC' => 'Vizibilitare globala',
    'LBL_INVITEE' => 'Membrii echipei',
    'LBL_LIST_DEPARTMENT' => 'Departament',
    'LBL_LIST_DESCRIPTION' => 'Descriere',
    'LBL_LIST_FORM_TITLE' => 'Lista echipa',
    'LBL_LIST_NAME' => 'Nume',
    'LBL_FIRST_NAME' => 'Nume:',
    'LBL_LAST_NAME' => 'Prenume:',
    'LBL_LIST_REPORTS_TO' => 'Raporteaza lui',
    'LBL_LIST_TITLE' => 'Titlu:',
    'LBL_MODULE_NAME' => 'Echipe',
    'LBL_MODULE_NAME_SINGULAR' => 'Echipa',
    'LBL_MODULE_TITLE' => 'Echipe: Acasa',
    'LBL_NAME' => 'Nume echipa',
    'LBL_NAME_2' => 'Nume echipa(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Nume echipa principala',
    'LBL_NEW_FORM_TITLE' => 'Echipa noua',
    'LBL_PRIVATE' => 'Privat',
    'LBL_PRIVATE_TEAM_FOR' => 'Echipa privata pentru:',
    'LBL_SEARCH_FORM_TITLE' => 'Cautare echipa:',
    'LBL_TEAM_MEMBERS' => 'Membrii echipei',
    'LBL_TEAM' => 'Echipe',
    'LBL_USERS_SUBPANEL_TITLE' => 'Utilizatori',
    'LBL_USERS' => 'Utilizatori',
    'LBL_REASSIGN_TEAM_TITLE' => 'Există înregistrări atribuite următoarii echipe : (0)<br />Înainte de a şterge echipa , trebuie să realocaţi aceste inregistrari pentru o nouă echipă. Selectaţi o echipa pentru a fi folosita ca înlocuitor.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Realoca',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Realoca[Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Continuaţi să actualizeze înregistrările afectate pentru a utiliza noua echipa?',
    'LBL_REASSIGN_TABLE_INFO' => 'Tabela de actualizare{0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operaţiunea s-a finalizat cu succes.',
    'LNK_LIST_TEAM' => 'Echipe',
    'LNK_LIST_TEAMNOTICE' => 'Anunturile Echipei',
    'LNK_NEW_TEAM' => 'Creeare Echipa',
    'LNK_NEW_TEAM_NOTICE' => 'Creare Anunţ Echipă',
    'NTC_DELETE_CONFIRMATION' => 'Esti sigur ca vrei sa stergi aceasta inregistrare?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Esti sigura ca vrei sa stergi acest angajat \\ membru?',
    'LBL_EDITLAYOUT' => 'Editeaza Plan General' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Permisiuni bazate pe echipă',
    'LBL_TBA_CONFIGURATION_DESC' => 'Permiteţi accesul echipei şi gestionaţă accesul după modul.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Activaţi permisiunile bazate pe echipă',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Selectaţi modulele de activat',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Activarea permisiunilor bazate pe echipă vă va permite să atribuiţi drepturi specifice de acces echipelor şi utilizatorilor pentru module individuale, prin Gestionarea rolurilor.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Dezactivarea permisiunilor bazate pe echipă pentru un modul va returna datele asociate permisiunilor bazate pe echipă pentru respectivul
 modul, inclusiv Definiţiile de proces sau Procesele ce folosesc funcţia. Acestea includ Rolurile care folosesc
 opţiunea "Proprietar şi echipă selectată" pentru modulul respectiv şi datele permisiunilor bazate pe echipă pentru înregistrările din modulul respectiv.
 De asemenea, vă recomandăm să utilizaţi instrumentul Reparare şi reconstruire rapidă pentru a goli memoria cache a sistemului după ce dezactivaţi
 permisiunile bazate pe echipă pentru orice modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Avertizare:</strong> Dezactivarea permisiunilor bazate pe echipă pentru un modul va returna datele asociate
 permisiunilor bazate pe echipă pentru respectivul modul, inclusiv Definiţiile de proces sau Procesele ce folosesc funcţia. Acestea
 includ Rolurile care folosesc opţiunea „Proprietar şi echipă selectată" pentru modulul respectiv şi datele permisiunilor bazate pe echipă
 pentru înregistrările din modulul respectiv. De asemenea, vă recomandăm să utilizaţi instrumentul Reparare şi reconstruire rapidă pentru a goli memoria cache a sistemului după
 ce dezactivaţi permisiunile bazate pe echipă pentru orice modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Dezactivarea permisiunilor bazate pe echipă pentru un modul va returna datele asociate permisiunilor bazate pe echipă pentru respectivul
 modul, inclusiv Definiţiile de proces sau Procesele ce folosesc funcţia. Acestea includ Rolurile care folosesc
 opţiunea „Proprietar şi echipă selectată" pentru modulul respectiv şi datele permisiunilor bazate pe echipă pentru înregistrările din modulul respectiv.
 De asemenea, vă recomandăm să utilizaţi instrumentul Reparare şi reconstruire rapidă pentru a goli memoria cache a sistemului după ce dezactivaţi
 permisiunile bazate pe echipă pentru orice modul. Dacă nu aveţi acces la instrumentul Reparare şi reconstruire rapidă, contactaţi un administrator care are
 acces la meniul Reparare.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Avertizare:</strong> Dezactivarea permisiunilor bazate pe echipă pentru un modul va returna datele asociate
 permisiunilor bazate pe echipă pentru respectivul modul, inclusiv Definiţiile de proces sau Procesele ce folosesc funcţia. Acestea
 includ Rolurile care folosesc opţiunea "Proprietar şi echipă selectată" pentru modulul respectiv şi datele permisiunilor bazate pe echipă pentru
 înregistrările din modulul respectiv. De asemenea, vă recomandăm să utilizaţi instrumentul Reparare şi reconstruire rapidă pentru a goli memoria cache a sistemului după 
ce dezactivaţi permisiunile bazate pe echipă pentru orice modul. Dacă nu aveţi acces la instrumentul Reparare şi reconstruire rapidă, contactaţi
 un administrator care are acces la meniul Reparare.
STR
,
);
