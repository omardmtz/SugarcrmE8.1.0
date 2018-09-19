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
    'ERR_ADD_RECORD' => 'Lai pievienotu lietotāju darba grupai, nepieciešams norādīt ieraksta numuru.',
    'ERR_DUP_NAME' => 'Darba grupas nosaukums jau pastāv, lūdzu izvēlieties citu.',
    'ERR_DELETE_RECORD' => 'Darba grupas izdzēšanai ir jānorāda ieraksta numurs.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Kļūda.  Norādītā darba grupa <b>({0})</b> ir izvēlēta dzēšanai. Lūdzu norādiet citu darba grupu.',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Kļūda.  Nevar izdzēst lietotāju, ja tam ir neizdzēsta privāta darba grupa.',
    'LBL_DESCRIPTION' => 'Apraksts:',
    'LBL_GLOBAL_TEAM_DESC' => 'Globāli redzams',
    'LBL_INVITEE' => 'Darba grupas dalībnieki',
    'LBL_LIST_DEPARTMENT' => 'Departaments',
    'LBL_LIST_DESCRIPTION' => 'Apraksts',
    'LBL_LIST_FORM_TITLE' => 'Darba grupu saraksts',
    'LBL_LIST_NAME' => 'Nosaukums',
    'LBL_FIRST_NAME' => 'Vārds:',
    'LBL_LAST_NAME' => 'Uzvārds:',
    'LBL_LIST_REPORTS_TO' => 'Vadītājs',
    'LBL_LIST_TITLE' => 'Amats',
    'LBL_MODULE_NAME' => 'Darba grupas',
    'LBL_MODULE_NAME_SINGULAR' => 'Darba grupa',
    'LBL_MODULE_TITLE' => 'Darba grupas: Sākums',
    'LBL_NAME' => 'Darba grupas nosaukums:',
    'LBL_NAME_2' => 'Darba grupas nosaukums(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Primārais darba grupas nosaukums',
    'LBL_NEW_FORM_TITLE' => 'Jauna darba grupa',
    'LBL_PRIVATE' => 'Privāts',
    'LBL_PRIVATE_TEAM_FOR' => 'Privāta darba grupa priekš:',
    'LBL_SEARCH_FORM_TITLE' => 'Darba grupas meklēšana',
    'LBL_TEAM_MEMBERS' => 'Darba grupas dalībnieki',
    'LBL_TEAM' => 'Darba grupas:',
    'LBL_USERS_SUBPANEL_TITLE' => 'Lietotāji',
    'LBL_USERS' => 'Lietotāji',
    'LBL_REASSIGN_TEAM_TITLE' => 'Pastāv sekojošām darba grupām piešķirti ieraksti: <b>{0}</b><br> Lai izdzēstu darba grupu, vispirms tai piesaistītie ieraksti ir jāpiešķir citai darba grupai. Norādiet darba grupu, kura aizvietos izdzēsto.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Piešķirt',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Piešķirt [Alt+R]',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Vai izpildīt ierakstu atjaunināšanu, lai apstiprinātu jaunās darba grupas lietošanu?',
    'LBL_REASSIGN_TABLE_INFO' => 'Atjaunina tabulu {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Darbība veiksmīgi pabeigta.',
    'LNK_LIST_TEAM' => 'Darba grupas',
    'LNK_LIST_TEAMNOTICE' => 'Darba grupas ziņojumi',
    'LNK_NEW_TEAM' => 'Izveidot darba grupu',
    'LNK_NEW_TEAM_NOTICE' => 'Izveidot darba grupas ziņojumu',
    'NTC_DELETE_CONFIRMATION' => 'Vai tiešām vēlaties dzēst šo ierakstu?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Vai tiešām vēlaties izņemt lietotāju no darba grupas?',
    'LBL_EDITLAYOUT' => 'Rediģēt izkārtojumu' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => 'Darba grupas atļaujas',
    'LBL_TBA_CONFIGURATION_DESC' => 'Iespējojiet darba grupas piekļuvi un pārvaldiet piekļuvi pēc moduļa.',
    'LBL_TBA_CONFIGURATION_LABEL' => 'Iespējot darba grupas atļaujas',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => 'Atlasīt moduļus iespējošanai',
    'LBL_TBA_CONFIGURATION_TITLE' => 'Iespējojot darba grupas atļaujas, jūs varēsiet piešķirt īpašas piekļuves tiesības darba grupām un lietotājiem atsevišķos moduļos, izmantojot Lomu pārvaldību.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
Atspējojot darba grupas atļaujas modulim, tiks atgriezti visi dati, kas ir saistīti ar darba grupas atļaujām šajā modulī, tostarp jebkādas Procesu definīcijas vai Procesi, kas izmanto šo funkciju. Tas ietver jebkādas Lomas, kas izmanto opciju "Īpašnieks un atlasītā darba grupa" šim modulim, un jebkādas darba grupas atļaujas datu ierakstiem šajā modulī. Tāpat iesakām izmantot rīku Ātrais remonts un pārbūve, lai izdzēstu sistēmas kešatmiņu pēc darba grupas atļauju atspējošanas jebkurā modulī.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>Brīdinājums:</strong> Atspējojot darba grupas atļaujas modulim, tiks atgriezti visi dati, kas ir saistīti ar darba grupas atļaujām šajā modulī, tostarp jebkādas Procesu definīcijas vai Procesi, kas izmanto šo funkciju. Tas ietver jebkādas Lomas, kas izmanto opciju "Īpašnieks un atlasītā darba grupa" šim modulim, un jebkādas darba grupas atļaujas datu ierakstiem šajā modulī. Tāpat iesakām izmantot rīku Ātrais remonts un pārbūve, lai izdzēstu sistēmas kešatmiņu pēc darba grupas atļauju atspējošanas jebkurā modulī.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
Atspējojot darba grupas atļaujas modulim, tiks atgriezti visi dati, kas ir saistīti ar darba grupas atļaujām šajā modulī, tostarp jebkādas Procesu definīcijas vai Procesi, kas izmanto šo funkciju. Tas ietver jebkādas Lomas, kas izmanto opciju "Īpašnieks un atlasītā darba grupa" šim modulim, un jebkādas darba grupas atļaujas datu ierakstiem šajā modulī. Tāpat iesakām izmantot rīku Ātrais remonts un pārbūve, lai izdzēstu sistēmas kešatmiņu pēc darba grupas atļauju atspējošanas jebkurā modulī. Ja jums nav piekļuves rīkam Ātrais remonts un pārbūve, sazinieties ar administratoru, kam ir piekļuve Remonta izvēlnei.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>Brīdinājums:</strong> Atspējojot darba grupas atļaujas modulim, tiks atgriezti visi dati, kas ir saistīti ar darba grupas atļaujām šajā modulī, tostarp jebkādas Procesu definīcijas vai Procesi, kas izmanto šo funkciju. Tas ietver jebkādas Lomas, kas izmanto opciju "Īpašnieks un atlasītā darba grupa" šim modulim, un jebkādas darba grupas atļaujas datu ierakstiem šajā modulī. Tāpat iesakām izmantot rīku Ātrais remonts un pārbūve, lai izdzēstu sistēmas kešatmiņu pēc darba grupas atļauju atspējošanas jebkurā modulī. Ja jums nav piekļuves rīkam Ātrais remonts un pārbūve, sazinieties ar administratoru, kam ir piekļuve Remonta izvēlnei.
STR
,
);
