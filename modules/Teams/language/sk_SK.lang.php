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
    'ERR_ADD_RECORD' => 'Musíte zadať číslo záznamu, pre pripojenie užívateľa do tímu.',
    'ERR_DUP_NAME' => 'Názov tímu už existuje, skúste nejaké iné.',
    'ERR_DELETE_RECORD' => 'K odstráneniu tohto tímu musíte zadať číslo záznamu.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Chyba. Vybraný tým <b>({0})</b> je tým, který ste vybrali k vymazaniu. Prosím vyberte iný tím.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Nemôže vymazať užívateľa, ktorého súkromný tím nebol vymazaný.',
    'LBL_DESCRIPTION' => 'Popis:',
    'LBL_GLOBAL_TEAM_DESC' => 'Viditeľný globálne',
    'LBL_INVITEE' => 'Členovia tímu',
    'LBL_LIST_DEPARTMENT' => 'Oddelenie',
    'LBL_LIST_DESCRIPTION' => 'Popis',
    'LBL_LIST_FORM_TITLE' => 'Zoznam tímu',
    'LBL_LIST_NAME' => 'Názov',
    'LBL_FIRST_NAME' => 'Meno',
    'LBL_LAST_NAME' => 'Priezvisko',
    'LBL_LIST_REPORTS_TO' => 'Nadriadený',
    'LBL_LIST_TITLE' => 'Titul',
    'LBL_MODULE_NAME' => 'Tímy',
    'LBL_MODULE_NAME_SINGULAR' => 'Tím',
    'LBL_MODULE_TITLE' => 'Tímy: Hlavná stránka',
    'LBL_NAME' => 'Názov tímu:',
    'LBL_NAME_2' => 'Názov tímu(2)',
    'LBL_PRIMARY_TEAM_NAME' => 'Primárne meno tímu',
    'LBL_NEW_FORM_TITLE' => 'Nový tím',
    'LBL_PRIVATE' => 'Súkromné',
    'LBL_PRIVATE_TEAM_FOR' => 'Súkromný tím pre:',
    'LBL_SEARCH_FORM_TITLE' => 'Prehľadávanie tímu',
    'LBL_TEAM_MEMBERS' => 'Členovia tímu',
    'LBL_TEAM' => 'Tímy',
    'LBL_USERS_SUBPANEL_TITLE' => 'Užívatelia',
    'LBL_USERS' => 'Užívatelia',
    'LBL_REASSIGN_TEAM_TITLE' => 'Tu sú záznamy priradené tomuto tímu:<b>{0}</b><br>Pred vymazaním tímu musíte najprv priradiť tento záznam novému tímu. Vyberte náhradný tím.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Preradiť',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Preradiť',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Pokračovať v aktualizácii dotknutých záznamov, k použitiu v novom tíme?',
    'LBL_REASSIGN_TABLE_INFO' => 'Aktualizovať tabuľku {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operácia prebehla úspešne.',
    'LNK_LIST_TEAM' => 'Tímy',
    'LNK_LIST_TEAMNOTICE' => 'Tímové oznamy',
    'LNK_NEW_TEAM' => 'Vytvoriť team',
    'LNK_NEW_TEAM_NOTICE' => 'Vytvoriť tímový oznam',
    'NTC_DELETE_CONFIRMATION' => 'Skutočne, chcete vymazať tento záznam?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Ste si istý, že chcete odobrať členstvo tomuto uživateľovi?',
    'LBL_EDITLAYOUT' => 'Upraviť rozloženie' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Tímové oprávnenia',
    'LBL_TBA_CONFIGURATION_DESC' => 'Povoľte tímový prístup a spravujte prístup pomocou modulu.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Povoľte tímové oprávnenia',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Vyberte moduly, ktoré chcete povoliť',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Povolenie tímových oprávnení vám umožní pomocou správy rol priradiť k tímom a používateľom konkrétne prístupové oprávnenia pre jednotlivé moduly.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Zakázanie tímových oprávnení vráti všetky údaje súvisiace s tímovými 
oprávneniami pre daný
 modul vrátane všetkých definícií procesov alebo procesov, ktoré používajú túto funkciu. Zahŕňa to všetky roly používajúce
 možnosť „Vlastník a vybraný tím“ a všetky údaje tímových oprávnení pre záznamy uložené v tomto module.
 Taktiež odporúčame, aby ste po zakázaní tímových oprávnení pre ktorýkoľvek modul, použili nástroj Rýchla oprava a obnova
 a vyčistili vyrovnávaciu pamäť systému.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Upozornenie:</strong> Zakázanie tímových oprávnení vráti všetky údaje
súvisiace s
 tímovými oprávneniami pre daný modul vrátane definícií procesov alebo procesov, ktoré používajú túto funkciu. Zahŕňa to 
 všetky roly používajúce možnosť „Vlastník a vybraný tím“ a všetky údaje tímových oprávnení
 pre záznamy uložené v tomto module. Taktiež odporúčame, aby ste po zakázaní tímových oprávnení
pre ktorýkoľvek modul, 
 použili nástroj Rýchla oprava a obnova a vyčistili vyrovnávaciu pamäť systému.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Zakázanie tímových oprávnení vráti všetky údaje súvisiace s tímovými 
oprávneniami pre daný
 modul vrátane všetkých definícií procesov alebo procesov, ktoré používajú túto funkciu. Zahŕňa to všetky roly používajúce
 možnosť „Vlastník a vybraný tím“ a všetky údaje tímových oprávnení pre záznamy uložené v tomto module.
 Taktiež odporúčame, aby ste po zakázaní tímových oprávnení pre ktorýkoľvek modul, použili nástroj Rýchla oprava a obnova
 a vyčistili vyrovnávaciu pamäť systému. Ak nemáte prístup k nástroju Rýchla oprava a obnova, kontaktujte správcu a požiadajte
 o prístup k ponuke Oprava.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Upozornenie:</strong> Zakázanie tímových oprávnení vráti všetky údaje súvisiace s tímovými 
oprávneniami pre daný
 modul vrátane všetkých definícií procesov alebo procesov, ktoré používajú túto funkciu. Zahŕňa to všetky roly používajúce
 možnosť „Vlastník a vybraný tím“ a všetky údaje tímových oprávnení pre záznamy uložené v tomto module.
 Taktiež odporúčame, aby ste po zakázaní tímových oprávnení pre ktorýkoľvek modul, použili nástroj Rýchla oprava a obnova
 a vyčistili vyrovnávaciu pamäť systému. Ak nemáte prístup k nástroju Rýchla oprava a obnova, kontaktujte správcu a požiadajte
 o prístup k ponuke Oprava.
STR
,
);
