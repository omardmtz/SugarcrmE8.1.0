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
    'ERR_ADD_RECORD' => 'Du må oppgi et registernummer for å legge til en bruker til denne gruppen.',
    'ERR_DUP_NAME' => 'Team navnet eksisterer allerede, velg et annet.',
    'ERR_DELETE_RECORD' => 'Du må oppgi et registernummer for å slette denne gruppen.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Feil. Det valgte teamet ({0}) er et team du har valgt å slette. Vennligst velg et annet team.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Feil. Du kan ikke slette en bruker som ikke har slettet sitt private team.',
    'LBL_DESCRIPTION' => 'Beskrivelse:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globalt synlig',
    'LBL_INVITEE' => 'Gruppemedlemmer',
    'LBL_LIST_DEPARTMENT' => 'Avdeling',
    'LBL_LIST_DESCRIPTION' => 'Beskrivelse',
    'LBL_LIST_FORM_TITLE' => 'Gruppeliste',
    'LBL_LIST_NAME' => 'Navn',
    'LBL_FIRST_NAME' => 'Fornavn:',
    'LBL_LAST_NAME' => 'Etternavn:',
    'LBL_LIST_REPORTS_TO' => 'Rapporterer til',
    'LBL_LIST_TITLE' => 'Tittel',
    'LBL_MODULE_NAME' => 'Grupper',
    'LBL_MODULE_NAME_SINGULAR' => 'Team',
    'LBL_MODULE_TITLE' => 'Grupper: Hjem',
    'LBL_NAME' => 'Gruppenavn:',
    'LBL_NAME_2' => 'Team navn(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Primært Team Navn',
    'LBL_NEW_FORM_TITLE' => 'Ny gruppe',
    'LBL_PRIVATE' => 'Privat',
    'LBL_PRIVATE_TEAM_FOR' => 'Privat gruppe for:',
    'LBL_SEARCH_FORM_TITLE' => 'Søk gruppe',
    'LBL_TEAM_MEMBERS' => 'Gruppemedlemmer',
    'LBL_TEAM' => 'Grupper:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Brukere',
    'LBL_USERS' => 'Brukere',
    'LBL_REASSIGN_TEAM_TITLE' => 'Det er poster tilordnet følgende team: {0}<br />Før du sletter team, må du først tilordne disse postene til et nytt team. Velg et team som skal brukes istedet.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Tildele',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Tildele [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Fortsett å oppdatere de berørte postene for å benytte det nye teamet?',
    'LBL_REASSIGN_TABLE_INFO' => 'Oppdatere tabellen {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operasjon er vellykket fullført.',
    'LNK_LIST_TEAM' => 'Grupper',
    'LNK_LIST_TEAMNOTICE' => 'Gruppemeldinger',
    'LNK_NEW_TEAM' => 'Opprett gruppe',
    'LNK_NEW_TEAM_NOTICE' => 'Opprett gruppemeldinger',
    'NTC_DELETE_CONFIRMATION' => 'Er du sikker på at du vil slette denne oppføringen?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Er du sikker på at du vil fjerne denne brukerens medlemskap?',
    'LBL_EDITLAYOUT' => 'Redigér oppsett' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Gruppebaserte tillatelser',
    'LBL_TBA_CONFIGURATION_DESC' => 'Aktiver gruppetilgang og administrer tilgang etter modulen.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Aktiver gruppebaserte tillatelser',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Velg moduler du vil aktivere',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Ved hjelp av aktivering av gruppebaserte tillatelser kan du tilordne spesifikke tilgangsrettigheter til grupper og brukere for individuelle moduler via Rollestyring.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Deaktivering av gruppebaserte tillatelser for en modul vil tilbakestille data tilknyttet gruppebaserte tillatelser for den modulen, herunder prosessdefinisjoner eller prosesser som bruker funksjonen. Dette inkluderer roller som bruker alternativet "Eier og valgt gruppe" for den modulen samt gruppebaserte tillatelsesdata for poster i den modulen. Vi anbefaler også at du bruker verktøyet for hurtigreparasjon og gjenoppbygging for å tømme systemets hurtigbuffer etter deaktivering av gruppebaserte tillatelser for en modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Advarsel:</strong> Deaktivering av gruppebaserte tillatelser for en modul vil tilbakestille data tilknyttet gruppebaserte tillatelser for den modulen, herunder prosessdefinisjoner eller prosesser som bruker funksjonen. Dette inkluderer roller som bruker alternativet "Eier og valgt gruppe" for den modulen samt gruppebaserte tillatelsesdata for poster i den modulen. Vi anbefaler også at du bruker verktøyet for hurtigreparasjon og gjenoppbygging for å tømme systemets hurtigbuffer etter deaktivering av gruppebaserte tillatelser for en modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Deaktivering av gruppebaserte tillatelser for en modul vil tilbakestille data tilknyttet gruppebaserte tillatelser for den modulen, herunder prosessdefinisjoner eller prosesser som bruker funksjonen. Dette inkluderer roller som bruker alternativet "Eier og valgt gruppe" for den modulen samt gruppebaserte tillatelsesdata for poster i den modulen. Vi anbefaler også at du bruker verktøyet for hurtigreparasjon og gjenoppbygging for å tømme systemets hurtigbuffer etter deaktivering av gruppebaserte tillatelser for en modul. Hvis du ikke har tilgang til verktøyet for hurtigreparasjon og gjenoppbygging, ta kontakt med en administrator med tilgang til reparasjonsmenyen.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Advarsel:</strong> Deaktivering av gruppebaserte tillatelser for en modul vil tilbakestille data tilknyttet gruppebaserte tillatelser for den modulen, herunder prosessdefinisjoner eller prosesser som bruker funksjonen. Dette inkluderer roller som bruker alternativet "Eier og valgt gruppe" for den modulen samt gruppebaserte tillatelsesdata for poster i den modulen. Vi anbefaler også at du bruker verktøyet for hurtigreparasjon og gjenoppbygging for å tømme systemets hurtigbuffer etter deaktivering av gruppebaserte tillatelser for en modul. Hvis du ikke har tilgang til verktøyet for hurtigreparasjon og gjenoppbygging, ta kontakt med en administrator med tilgang til reparasjonsmenyen.
STR
,
);
