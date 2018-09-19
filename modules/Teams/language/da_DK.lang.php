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
    'ERR_ADD_RECORD' => 'Du skal angive et postnummer for at tilføje en bruger til dette team.',
    'ERR_DUP_NAME' => 'Team navn eksistere allerede, venligst vælg et andet.',
    'ERR_DELETE_RECORD' => 'Du skal angive et postnummer for at slette dette team.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Fejl. Det valgte team <b>({0})</b> er et team, du har valgt at slette. Vælg et andet team.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Fejl. Du kan ikke slette en bruger vis private team ikke er blevet slettet først.',
    'LBL_DESCRIPTION' => 'Beskrivelse:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globalt synlig',
    'LBL_INVITEE' => 'Teammedlemmer',
    'LBL_LIST_DEPARTMENT' => 'Afdeling',
    'LBL_LIST_DESCRIPTION' => 'Beskrivelse',
    'LBL_LIST_FORM_TITLE' => 'Teamliste',
    'LBL_LIST_NAME' => 'Navn',
    'LBL_FIRST_NAME' => 'Fornavn:',
    'LBL_LAST_NAME' => 'Efternavn:',
    'LBL_LIST_REPORTS_TO' => 'Rapporterer til',
    'LBL_LIST_TITLE' => 'Titel',
    'LBL_MODULE_NAME' => 'Team',
    'LBL_MODULE_NAME_SINGULAR' => 'Team',
    'LBL_MODULE_TITLE' => 'Team: Startside',
    'LBL_NAME' => 'Teamnavn:',
    'LBL_NAME_2' => 'Teamnavn(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Navn på primært team',
    'LBL_NEW_FORM_TITLE' => 'Nyt team',
    'LBL_PRIVATE' => 'Privat',
    'LBL_PRIVATE_TEAM_FOR' => 'Privat team for:',
    'LBL_SEARCH_FORM_TITLE' => 'Søg efter team',
    'LBL_TEAM_MEMBERS' => 'Teammedlemmer',
    'LBL_TEAM' => 'Team:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Brugere',
    'LBL_USERS' => 'Brugere',
    'LBL_REASSIGN_TEAM_TITLE' => 'Der er tildelt poster til følgende team: <b>{0}</b><br>Før du sletter teamene, skal du omfordele disse poster til et nyt team. Vælg et team, der skal bruges som erstatning.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Omfordel',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Omfordel [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Fortsæt for at opdatere de berørte poster, der skal bruge det nye team?',
    'LBL_REASSIGN_TABLE_INFO' => 'Opdaterer tabellen {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Handlingen blev fuldført.',
    'LNK_LIST_TEAM' => 'Team',
    'LNK_LIST_TEAMNOTICE' => 'Teammeddelelser',
    'LNK_NEW_TEAM' => 'Opret team',
    'LNK_NEW_TEAM_NOTICE' => 'Opret teammeddelelse',
    'NTC_DELETE_CONFIRMATION' => 'Er du sikker på, at du vil slette denne post?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Er du sikker på, at du vil fjerne denne brugers medlemskab?',
    'LBL_EDITLAYOUT' => 'Rediger layout' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Teambaserede tilladelser',
    'LBL_TBA_CONFIGURATION_DESC' => 'Aktiverer teamadgang, og administrer adgang pr. modul.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Aktiver team-baserede tilladelser',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Vælg moduler der skal aktiveres',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Aktivering af team-baserede tilladelser vil gøre dig i stand til at tildele specifikke adgangsrettigheder til teams og brugere af individuelle moduler gennem rollestyring.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Deaktivering af team-baserede tilladelser for et modul vil still alle data i forbindelse med team-baserede tilladelser til dette modul tilbage, herunder alle procesdefinitioner eller processer, der bruger funktionen. Dette omfatter alle roller, der anvender indstillingen "Ejer & valgte team" til dette modul, og alle team-baserede tilladelsesdata for poster i det pågældende modul. Det anbefales også, at du bruger værktøjet til hurtig reparation og genopbygning for at rydde systemcachen efter deaktivering af team-baserede tilladelser for ethvert modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Advarsel:</strong> Deaktivering af team-baserede tilladelser for et modul vil still alle data i forbindelse med team-baserede tilladelser til dette modul tilbage, herunder alle procesdefinitioner eller processer, der bruger funktionen. Dette omfatter alle roller, der anvender indstillingen "Ejer & valgte team" til dette modul, og alle team-baserede tilladelsesdata for poster i det pågældende modul. Det anbefales også, at du bruger værktøjet til hurtig reparation og genopbygning for at rydde systemcachen efter deaktivering af team-baserede tilladelser for ethvert modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Deaktivering af team-baserede tilladelser for et modul vil still alle data i forbindelse med team-baserede tilladelser til dette modul tilbage, herunder alle procesdefinitioner eller processer, der bruger funktionen. Dette omfatter alle roller, der anvender indstillingen "Ejer & valgte team" til dette modul, og alle team-baserede tilladelsesdata for poster i det pågældende modul. Det anbefales også, at du bruger værktøjet til hurtig reparation og genopbygning for at rydde systemcachen efter deaktivering af team-baserede tilladelser for ethvert modul. Hvis du ikke har adgang til hurtig reparation og genopretning, så kontakt en administrator med adgang til reparationsmenuen.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Advarsel:</strong> Deaktivering af team-baserede tilladelser for et modul vil still alle data i forbindelse med team-baserede tilladelser til dette modul tilbage, herunder alle procesdefinitioner eller processer, der bruger funktionen. Dette omfatter alle roller, der anvender indstillingen "Ejer & valgte team" til dette modul, og alle team-baserede tilladelsesdata for poster i det pågældende modul. Det anbefales også, at du bruger værktøjet til hurtig reparation og genopbygning for at rydde systemcachen efter deaktivering af team-baserede tilladelser for ethvert modul. Hvis du ikke har adgang til hurtig reparation og genopretning, så kontakt en administrator med adgang til reparationsmenuen.
STR
,
);
