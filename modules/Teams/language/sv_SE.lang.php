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
    'ERR_ADD_RECORD' => 'Ett objektnummer måste specificeras för att lägga till användare till teamet.',
    'ERR_DUP_NAME' => 'Team namnet existerar redan, var snäll och välj en annan.',
    'ERR_DELETE_RECORD' => 'Ett objektnummer måste specificeras för att radera teamet.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Fel. Det valda teamet ({0}) är ett team som du har valt att radera. Välj ett annat.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Fel. Du kan inte radera en användare vars privata team inte har blivit raderad.',
    'LBL_DESCRIPTION' => 'Beskrivning',
    'LBL_GLOBAL_TEAM_DESC' => 'Globalt synlig',
    'LBL_INVITEE' => 'Teammedlemmar',
    'LBL_LIST_DEPARTMENT' => 'Avdelning',
    'LBL_LIST_DESCRIPTION' => 'Beksrivning',
    'LBL_LIST_FORM_TITLE' => 'Lista team',
    'LBL_LIST_NAME' => 'Namn',
    'LBL_FIRST_NAME' => 'Förnamn:',
    'LBL_LAST_NAME' => 'Efternamn:',
    'LBL_LIST_REPORTS_TO' => 'Rapporterar till',
    'LBL_LIST_TITLE' => 'Titel',
    'LBL_MODULE_NAME' => 'Team',
    'LBL_MODULE_NAME_SINGULAR' => 'Lag',
    'LBL_MODULE_TITLE' => 'Team: Hem',
    'LBL_NAME' => 'Namn på team:',
    'LBL_NAME_2' => 'Team namn (2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Primärt Team namn',
    'LBL_NEW_FORM_TITLE' => 'Nytt team',
    'LBL_PRIVATE' => 'Privat',
    'LBL_PRIVATE_TEAM_FOR' => 'Privat team för:',
    'LBL_SEARCH_FORM_TITLE' => 'Sök team',
    'LBL_TEAM_MEMBERS' => 'Teammedlemmar',
    'LBL_TEAM' => 'Team',
    'LBL_USERS_SUBPANEL_TITLE' => 'Användare',
    'LBL_USERS' => 'Användare',
    'LBL_REASSIGN_TEAM_TITLE' => 'Det finns poster tilldelade till följande team: <b>{0}</B><br />Före radering måste du omplacera posterna till ett nytt team. Välj ett team att användas som ersättare.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Omplacera',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Omplacera [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Fortsätt och uppdatera berörda poster att ersättas av det nya teamet?',
    'LBL_REASSIGN_TABLE_INFO' => 'Uppdaterar tabell {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operationen lyckades och har slutförts.',
    'LNK_LIST_TEAM' => 'Team',
    'LNK_LIST_TEAMNOTICE' => 'Team notiser',
    'LNK_NEW_TEAM' => 'Skapa team',
    'LNK_NEW_TEAM_NOTICE' => 'Skapa team notis',
    'NTC_DELETE_CONFIRMATION' => 'Är du säker på att du vill radera posten?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Är du säker på att du vill ta bort användarens medlemskap?',
    'LBL_EDITLAYOUT' => 'Redigera layout' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Team-baserade behörigheter',
    'LBL_TBA_CONFIGURATION_DESC' => 'Aktivera teamtillgång och hantera åtkomst av modul.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Aktivera team-baserade behörigheter',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Välj moduler för att',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Genom att aktivera team-baserade behörigheter kan du tilldela särskilda behörigheter till grupper och användare för enskilda moduler genom rollhantering.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Genom att inaktivera team-baserade rättigheter till en modul betyder att modulen omvanldar alla data associerade med de team-baserade rättigheterna för denna modul liksom alla processdefinitioner eller processer som använder sig av dessa. Detta innefattar även roller som används under "Owner & Selected team" för denna modul och alla team-baserade rättigheter till data i denna modul.
Vi rekommenderar också att Du använder Dig av Quick Repair och Rebuild-verktygen för att rensa systemcachen efter att ha inaktiverat rättigheter för alla moduler.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Warning:</strong> Genom att spärra team-baserade rättigheter för en modul kommer alla data som hör ihop med team-baserade rättigheter för denna modul att återgå till grundinställningen liksom alla processdefinitioner eller processer som använder dessa. Detta innefattar även alla roller som används av "Ägare & utvalda team"-alternativet för denna modul och alla team-baserade rättigheter till data i denna modul. Vi rekommenderar också att 'du använder Snabbreparations- och Återuppbyggnadsverktygen för att tömma systemcache efter att ha spärrat team-baserade rättigheter för en modul.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Genom att inaktivera teambaserade rättigheter till en modul betyder det att modulen omvandlar alla data associerade med de teambaserade rättigheterna för denna modul liksom alla processdefinitioner eller processer som använder sig av dessa. Detta innefattar även roller som används under "Owner & Selected team" för denna modul och alla teambaserade rättigheter till data i denna modul.
Vi rekommenderar också att Du använder Dig av Quick Repair och Rebuild-verktygen för att rensa systemcachen efter att ha inaktiverat rättigheter för alla moduler.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Warning:</strong> Genom att spärra teambaserade rättigheter för en modul kommer alla data som hör ihop med teambaserade rättigheter för denna modul att återgå till grundinställningen liksom alla processdefinitioner eller processer som använder dessa. Detta innefattar även alla roller som används av "Ägare & utvalda team"-alternativet för denna modul och alla teambaserade rättigheter till data i denna modul. Vi rekommenderar också att Du använder Snabbreparations- och Återuppbyggnadsverktygen för att tömma systemcachen efter att ha spärrat teambaserade rättigheter för en modul.
STR
,
);
