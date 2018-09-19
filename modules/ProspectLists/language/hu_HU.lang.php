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

$mod_strings = array (
  // Dashboard Names
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => 'Céllisták listája műszerfal',

  'LBL_MODULE_NAME' => 'Cél listák',
  'LBL_MODULE_NAME_SINGULAR' => 'Cél lista',
  'LBL_MODULE_ID'   => 'Cél listák',
  'LBL_MODULE_TITLE' => 'Cél listák: Főoldal',
  'LBL_SEARCH_FORM_TITLE' => 'Cél listák keresés',
  'LBL_LIST_FORM_TITLE' => 'Cél listák',
  'LBL_PROSPECT_LIST_NAME' => 'Cél lista:',
  'LBL_NAME' => 'Név',
  'LBL_ENTRIES' => 'Összes bejegyzés',
  'LBL_LIST_PROSPECT_LIST_NAME' => 'Cél lista',
  'LBL_LIST_ENTRIES' => 'Célok listája',
  'LBL_LIST_DESCRIPTION' => 'Leírás',
  'LBL_LIST_TYPE_NO' => 'Típus',
  'LBL_LIST_END_DATE' => 'Befejezés dátuma',
  'LBL_DATE_ENTERED' => 'Kezdés dátuma',
  'LBL_MARKETING_ID' => 'Marketing azonosító',
  'LBL_DATE_MODIFIED' => 'Módosítás dátuma',
  'LBL_MODIFIED' => 'Módosította',
  'LBL_CREATED' => 'Létrehozta',
  'LBL_TEAM' => 'Csapat',
  'LBL_ASSIGNED_TO' => 'Felelős',
  'LBL_DESCRIPTION' => 'Leírás',
  'LNK_NEW_CAMPAIGN' => 'Kampány létrehozása',
  'LNK_CAMPAIGN_LIST' => 'Kampányok',
  'LNK_NEW_PROSPECT_LIST' => 'Cél lista létrehozása',
  'LNK_PROSPECT_LIST_LIST' => 'Cél listák megtekintése',
  'LBL_MODIFIED_BY' => 'Módosította',
  'LBL_CREATED_BY' => 'Létrehozta',
  'LBL_DATE_CREATED' => 'Létrehozás dátuma',
  'LBL_DATE_LAST_MODIFIED' => 'Módosítás dátuma',
  'LNK_NEW_PROSPECT' => 'Cél létrehozása',
  'LNK_PROSPECT_LIST' => 'Célok',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => 'Cél listák',
  'LBL_CONTACTS_SUBPANEL_TITLE' => 'Kapcsolatok',
  'LBL_LEADS_SUBPANEL_TITLE' => 'Ajánlások',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'Célok',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => 'Kliensek',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => 'Kampányok',
  'LBL_COPY_PREFIX' =>'Másolata',
  'LBL_USERS_SUBPANEL_TITLE' =>'Felhasználók',
  'LBL_TYPE' => 'Típus',
  'LBL_LIST_TYPE' => 'Típus',
  'LBL_LIST_TYPE_LIST_NAME'=>'Típus',
  'LBL_NEW_FORM_TITLE'=>'Új cél lista',
  'LBL_MARKETING_NAME'=>'Marketingnév',
  'LBL_MARKETING_MESSAGE'=>'Email marketing üzenet',
  'LBL_DOMAIN_NAME'=>'Domain név',
  'LBL_DOMAIN'=>'Nincsenek emailek a domainhez',
  'LBL_LIST_PROSPECTLIST_NAME'=>'Név',
	'LBL_MORE_DETAIL' => 'Részletek' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => 'A {{module_name}} lista modul olyan személyeket és szervezeteket listáz, amelyeket meg lehet célozni marketing {{campaigns_singular_module}}-al. A Cél lista modulok jelölteket, {{contacts_module}}at, Ajánlás leadeket és {{accounts_module}}et tartalmaznak, melyeket igény szerint Cél lista modulba csoportosít a rendszer, pl. életkor, földrajzi elhelyezkedés, vagy költési szokások alapján. A Cél lista modulok email marketingben használatosak {{campaigns_module}} során, melyeket a {{campaigns_module}} modulban lehet konfigurálni.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'A {{module_name}} modul olyan személyeket és szervezeteket listáz, amelyeket meg lehet célozni marketing kampánnyal {{campaigns_singular_module}}. 
- Szerkessze ennek a rekordnak a mezőit külön-külön, vagy kattintson a Szerkesztés gombra! 
-Tekintse meg, vagy szerkessze a linkeket a bal alsó "Adatnézet" kapcsoló használatával, {{campaigns_singular_module}} beleértve! 
-Olvassa el, vagy írjon felhasználói hozzászólásokat a "Tevékenységfolyam" opcióval! A rekord neve mellett található ikonok segítségével jelölje be kedvencének a tartalmat, vagy kövesse annak utóéletét az {{activitystream_singular_module}} modulban! 
-Egyéb tevékenységek a Szerkesztés gombtól jobbra szereplő műveleti gomb legördülő menüjében találhatók.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'A {{module_name}} modul olyan személyeket és szervezeteket listáz, amelyeket meg lehet célozni marketing kampánnyal {{campaigns_singular_module}}. 

{{module_name}} létrehozásához: 
1. töltse ki a mezőket! 
- A kötelező mezők kitöltése nélkül mentés nem lehetséges. 
- Ha hiányzó mezőt talál, kattintson a "Több mutatása" opcióra a további mezők felfedéséhez! 
2. Kattintson a "Mentés" gombra a rekord mentéséhez és a korábbi nézetre való visszatéréshez! Amennyiben a "Mentés és megjelenítés" opciót választja, rekordnézetben fog megjelenni. A "Mentés és új létrehozása" paranccsal közvetlen új modulok létrehozatali oldalára továbbítódik. 
3. Mentés után az alpanelek segítségével jelölje ki a {{campaigns_singular_module}} címzetteket.',
);
