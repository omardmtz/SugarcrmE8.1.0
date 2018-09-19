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
    'ERR_ADD_RECORD' => 'U dient een recordnummer op te geven om een gebruiker toe te wijzen aan dit team.',
    'ERR_DUP_NAME' => 'Teamnaam bestaat reeds, kies a.u.b. een andere teamnaam.',
    'ERR_DELETE_RECORD' => 'U dient een recordnummer op te geven om om dit team te verwijderen.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Error. Het geselecteerde team <b>({0})</b> is een team dat u wilt verwijderen. Selecteer a.u.b. een ander team.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Error. Een gebruiker mag niet worden verwijderd wanneer zijn privé-team niet is verwijderd.',
    'LBL_DESCRIPTION' => 'Beschrijving:',
    'LBL_GLOBAL_TEAM_DESC' => 'Zichtbaar voor iedereen',
    'LBL_INVITEE' => 'Teamleden',
    'LBL_LIST_DEPARTMENT' => 'Afdeling',
    'LBL_LIST_DESCRIPTION' => 'Beschrijving',
    'LBL_LIST_FORM_TITLE' => 'Teamlijst',
    'LBL_LIST_NAME' => 'Naam',
    'LBL_FIRST_NAME' => 'Voornaam:',
    'LBL_LAST_NAME' => 'Achternaam:',
    'LBL_LIST_REPORTS_TO' => 'Rapporteert aan',
    'LBL_LIST_TITLE' => 'Titel',
    'LBL_MODULE_NAME' => 'Teams',
    'LBL_MODULE_NAME_SINGULAR' => 'Team',
    'LBL_MODULE_TITLE' => 'Teams: Start',
    'LBL_NAME' => 'Teamnaam:',
    'LBL_NAME_2' => 'Teamnaam (2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Primaire teamnaam',
    'LBL_NEW_FORM_TITLE' => 'Nieuw Team',
    'LBL_PRIVATE' => 'Privé',
    'LBL_PRIVATE_TEAM_FOR' => 'Privé-team voor:',
    'LBL_SEARCH_FORM_TITLE' => 'Team zoeken',
    'LBL_TEAM_MEMBERS' => 'Teamleden',
    'LBL_TEAM' => 'Teams:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Gebruikers',
    'LBL_USERS' => 'Gebruikers',
    'LBL_REASSIGN_TEAM_TITLE' => 'Er zijn records toegewezen aan de volgende team(s): <b>{0}</b><br>Voor het verwijderen van de  team(s), moet u de records toewijzen aan een ander team. Selecteer een team als vervangend team.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Toewijzen',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Toewijzen [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Wilt u het nieuwe team bijwerken in bovenstaande records?',
    'LBL_REASSIGN_TABLE_INFO' => 'Bijwerken tabel {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Opdracht met succes afgerond.',
    'LNK_LIST_TEAM' => 'Teams',
    'LNK_LIST_TEAMNOTICE' => 'Teamberichten',
    'LNK_NEW_TEAM' => 'Nieuw Team',
    'LNK_NEW_TEAM_NOTICE' => 'Nieuw Teambericht',
    'NTC_DELETE_CONFIRMATION' => 'Weet u het zeker dat u dit record wilt verwijderen?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Weet u het zeker dat u het teamlidmaatschap van deze gebruiker wilt verwijderen?',
    'LBL_EDITLAYOUT' => 'Wijzig layout' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Op teams gebaseerde machtigingen',
    'LBL_TBA_CONFIGURATION_DESC' => 'Teamtoegang inschakelen en toegang beheren per module.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Op teams gebaseerde machtigingen inschakelen',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Selecteer modules om in te schakelen',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Met op teams gebaseerde machtigingen kunt u via Rolbeheer specifieke toegangsrechten aan teams en gebruikers toekennen voor individuele modules.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Op teams gebaseerde machtigingen voor een module overschrijven alle gegevens die aan op teams gebaseerde machtigingen zijn gekoppeld voor die module, waaronder Procesdefinities of Processen die de functie gebruiken. Dit is inclusief Rollen die de optie "Eigenaar & Geselecteerd team" gebruiken voor die module en eventuele op teams gebaseerde machtigingen voor records in die module. We raden u aan het middel Snel repareren en opnieuw opbouwen te gebruiken om uw systeemcache te legen nadat u op teams gebaseerde machtigingen hebt uitgeschakeld voor een module.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Waarschuwing:</strong> Op teams gebaseerde machtigingen voor een module overschrijven alle gegevens die aan op teams gebaseerde machtigingen zijn gekoppeld voor die module, waaronder Procesdefinities of Processen die de functie gebruiken. Dit is inclusief Rollen die de optie "Eigenaar & Geselecteerd team" gebruiken voor die module en eventuele op teams gebaseerde machtigingen voor records in die module. We raden u aan het middel Snel repareren en opnieuw opbouwen te gebruiken om uw systeemcache te legen nadat u op teams gebaseerde machtigingen hebt uitgeschakeld voor een module.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Op teams gebaseerde machtigingen voor een module overschrijven alle gegevens die aan op teams gebaseerde machtigingen zijn gekoppeld voor die module, waaronder Procesdefinities of Processen die de functie gebruiken. Dit is inclusief Rollen die de optie "Eigenaar & Geselecteerd team" gebruiken voor die module en eventuele op teams gebaseerde machtigingen voor records in die module. We raden u aan het middel Snel repareren en opnieuw opbouwen te gebruiken om uw systeemcache te legen nadat u op teams gebaseerde machtigingen hebt uitgeschakeld voor een module. Neem contact op met een beheerder die toegang heeft tot het menu Reparatie als u geen toegang heeft tot Snel repareren en opnieuw opbouwen.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Waarschuwing:</strong>Op teams gebaseerde machtigingen voor een module overschrijven alle gegevens die aan op teams gebaseerde machtigingen zijn gekoppeld voor die module, waaronder Procesdefinities of Processen die de functie gebruiken. Dit is inclusief Rollen die de optie "Eigenaar & Geselecteerd team" gebruiken voor die module en eventuele op teams gebaseerde machtigingen voor records in die module. We raden u aan het middel Snel repareren en opnieuw opbouwen te gebruiken om uw systeemcache te legen nadat u op teams gebaseerde machtigingen hebt uitgeschakeld voor een module. Neem contact op met een beheerder die toegang heeft tot het menu Reparatie als u geen toegang heeft tot Snel repareren en opnieuw opbouwen.
STR
,
);
