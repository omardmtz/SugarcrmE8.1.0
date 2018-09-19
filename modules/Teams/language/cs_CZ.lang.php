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
    'ERR_ADD_RECORD' => 'Musíte zadat číslo záznamu, pro připojení tohoto uživatele do týmu.',
    'ERR_DUP_NAME' => 'Jméno týmu už existuje!',
    'ERR_DELETE_RECORD' => 'Musíte zadat číslo záznamu pro smazání tohoto týmu.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Chyba. Vybraný tým <b>({0})</b> je tým, který jste vybral pro smazání.Prosím vyberte jiný tým.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Chyba: Nemůžete smazat uživatele, jehož soukromý tým nebyl vymazán.',
    'LBL_DESCRIPTION' => 'Popis:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globálně viditelný',
    'LBL_INVITEE' => 'Členové týmu',
    'LBL_LIST_DEPARTMENT' => 'Oddělení',
    'LBL_LIST_DESCRIPTION' => 'Popis',
    'LBL_LIST_FORM_TITLE' => 'Tým List',
    'LBL_LIST_NAME' => 'Název',
    'LBL_FIRST_NAME' => 'Jméno:',
    'LBL_LAST_NAME' => 'Příjmení:',
    'LBL_LIST_REPORTS_TO' => 'Nadřízený',
    'LBL_LIST_TITLE' => 'Titul',
    'LBL_MODULE_NAME' => 'Týmy',
    'LBL_MODULE_NAME_SINGULAR' => 'Tým',
    'LBL_MODULE_TITLE' => 'Týmy: Hlavní stránka',
    'LBL_NAME' => 'Jméno týmu:',
    'LBL_NAME_2' => 'Jméno týmu(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Název primárního týmu',
    'LBL_NEW_FORM_TITLE' => 'Nový tým',
    'LBL_PRIVATE' => 'Soukromý',
    'LBL_PRIVATE_TEAM_FOR' => 'Soukromý tým pro:',
    'LBL_SEARCH_FORM_TITLE' => 'Vyhledání týmu',
    'LBL_TEAM_MEMBERS' => 'Členové týmu',
    'LBL_TEAM' => 'Týmy:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Uživatelé',
    'LBL_USERS' => 'Uživatelé',
    'LBL_REASSIGN_TEAM_TITLE' => 'Našel jsem záznamy přiřazené na tento tým: <b>{0}</b><br>Před vymazáním vyberte tým , na který budou záznamy převedeny.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Přerozdělit',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Přerozdělit',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Mají se převést vybrané záznamy do nového týmu?',
    'LBL_REASSIGN_TABLE_INFO' => 'Aktualizovat tabulku {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operace proběhla špičkově!',
    'LNK_LIST_TEAM' => 'Týmy',
    'LNK_LIST_TEAMNOTICE' => 'Upozornění týmu',
    'LNK_NEW_TEAM' => 'Vytvořit tým',
    'LNK_NEW_TEAM_NOTICE' => 'Vytvořit týmovou poznámku',
    'NTC_DELETE_CONFIRMATION' => 'Jste si jist, že chcete smazat tento záznam?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Jsi si jist, že chceš odebrat toto členství?',
    'LBL_EDITLAYOUT' => 'Úprava rozvržení' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Týmová oprávnění',
    'LBL_TBA_CONFIGURATION_DESC' => 'Povolte přístup týmu a spravujte přístup modulem.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Povolte týmová oprávnění',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Vyberte moduly, které budou povoleny',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Povolení týmových oprávnění vám umožní pomocí Správy rolí přiřazovat týmům a uživatelům specifická přístupová práva pro jednotlivé moduly.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Zakázání týmových oprávnění pro modul vrátí jakákoli původní data spojená s týmovými oprávněními pro daný modul, včetně jakýchkoli definic procesů nebo procesů využívajících funkci. Zahrnuje to jakékoli role využívající možnost „Vlastník a vybraný tým“ a jakákoli data týmových oprávnění pro záznamy v daném modulu. Po zakázání týmových oprávnění pro jakýkoli modul také doporučujeme použít k vymazání mezipaměti vašeho systému nástroj Rychlá oprava a obnova.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Varování:</strong> Zakázání týmových oprávnění pro modul vrátí jakákoli původní data spojená s týmovými oprávněními pro daný modul, včetně jakýchkoli definic procesů nebo procesů využívajících funkci. Zahrnuje to jakékoli role využívající možnost „Vlastník a vybraný tým“ a jakákoli data týmových oprávnění pro záznamy v daném modulu. Po zakázání týmových oprávnění pro jakýkoli modul také doporučujeme použít k vymazání mezipaměti vašeho systému nástroj Rychlá oprava a obnova.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Zakázání týmových oprávnění pro modul vrátí jakákoli původní data spojená s týmovými oprávněními pro daný modul, včetně jakýchkoli definic procesů nebo procesů využívajících funkci. Zahrnuje to jakékoli role využívající možnost „Vlastník a vybraný tým“ a jakákoli data týmových oprávnění pro záznamy v daném modulu. Po zakázání týmových oprávnění pro jakýkoli modul také doporučujeme použít k vymazání mezipaměti vašeho systému nástroj Rychlá oprava a obnova. Nemáte-li přístup k použití nástroje Rychlá oprava a obnova, obraťte se na správce, aby vám dal přístup k nabídce Opravit.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Varování:</strong> Zakázání týmových oprávnění pro modul vrátí jakákoli původní data spojená s týmovými oprávněními pro daný modul, včetně jakýchkoli definic procesů nebo procesů využívajících funkci. Zahrnuje to jakékoli role využívající možnost „Vlastník a vybraný tým“ a jakákoli data týmových oprávnění pro záznamy v daném modulu. Po zakázání týmových oprávnění pro jakýkoli modul také doporučujeme použít k vymazání mezipaměti vašeho systému nástroj Rychlá oprava a obnova. Nemáte-li přístup k použití nástroje Rychlá oprava a obnova, obraťte se na správce, aby vám dal přístup k nabídce Opravit.
STR
,
);
