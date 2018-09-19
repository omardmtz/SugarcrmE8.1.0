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
  'LBL_MODULE_NAME' => 'Правила бізнес-процесів',
  'LBL_MODULE_TITLE' => 'Правила бізнес-процесів',
  'LBL_MODULE_NAME_SINGULAR' => 'Правило бізнес-процесу',

  'LBL_RST_UID' => 'Бізнес-правило (ID)',
  'LBL_RST_TYPE' => 'Тип бізнес-правила',
  'LBL_RST_DEFINITION' => 'Визначення бізнес-правила',
  'LBL_RST_EDITABLE' => 'Бізнес-правила для редагування',
  'LBL_RST_SOURCE' => 'Джерело бізнес-правила',
  'LBL_RST_SOURCE_DEFINITION' => 'Визначення джерела бізнес-правила',
  'LBL_RST_MODULE' => 'Основний модуль',
  'LBL_RST_FILENAME' => 'Назва файлу бізнес-правила',
  'LBL_RST_CREATE_DATE' => 'Дата створення бізнес-правила',
  'LBL_RST_UPDATE_DATE' => 'Дата оновлення бізнес-правила',

    'LNK_LIST' => 'Переглянути процес обробки бізнес-правил',
    'LNK_NEW_PMSE_BUSINESS_RULES' => 'Створити процес обробки бізнес-правила',
    'LNK_IMPORT_PMSE_BUSINESS_RULES' => 'Імпортувати процес обробки бізнес-правил',

    'LBL_PMSE_TITLE_BUSINESS_RULES_BUILDER' => 'Конструктор бізнес-правил',

    'LBL_PMSE_LABEL_DESIGN' => 'Змоделювати',
    'LBL_PMSE_LABEL_EXPORT' => 'Експорт',
    'LBL_PMSE_LABEL_DELETE' => 'Видалити',

    'LBL_PMSE_SAVE_EXIT_BUTTON_LABEL' => 'Зберегти й вийти',
    'LBL_PMSE_SAVE_DESIGN_BUTTON_LABEL' => 'Зберегти та змоделювати',
    'LBL_PMSE_IMPORT_BUTTON_LABEL' => 'Імпорт',

    'LBL_PMSE_MY_BUSINESS_RULES' => 'Мої процеси',
    'LBL_PMSE_ALL_BUSINESS_RULES' => 'Всі процеси обробки бізнес-правил',

    'LBL_PMSE_BUSINESS_RULES_SINGLE_HIT' => 'Процес одиночної обробки бізнес-правил',

    'LBL_PMSE_BUSINESS_RULES_IMPORT_TEXT' => 'Автоматичне створення запису нового процесу обробки бізнес-правила шляхом імпорту *.pbr файлу з файлової системи.',
    'LBL_PMSE_BUSINESS_RULES_IMPORT_SUCCESS' => 'Процес обробки шаблону Email успішно імпортований до системи.',
    'LBL_PMSE_BUSINESS_RULES_EMPTY_WARNING' => 'Будь ласка, виберіть припустимий *.pbr файл.',

    'LBL_PMSE_MESSAGE_LABEL_UNSUPPORTED_DATA_TYPE' => 'Непідтримуваний тип даних.',
    'LBL_PMSE_MESSAGE_LABEL_DEFINE_COLUMN_TYPE' => 'Будь ласка, спочатку визначте тип стовпця.',
    'LBL_PMSE_MESSAGE_LABEL_EMPTY_RETURN_VALUE' => 'Результат "Повернення" - порожньо',
    'LBL_PMSE_MESSAGE_LABEL_MISSING_EXPRESSION_OR_OPERATOR' => 'відсутній вираз або оператор',
    'LBL_PMSE_MESSAGE_LABEL_DELETE_ROW' => 'Ви дійсно бажаєте видалити цей набір правил?',
    'LBL_PMSE_MESSAGE_LABEL_MIN_ROWS' => 'У таблиці рішень має бути принаймні 1 рядок',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONDITIONS_COLS' => 'У таблиці рішень має бути принаймні 1 стовпчик умов',
    'LBL_PMSE_MESSAGE_LABEL_MIN_CONCLUSIONS_COLS' => 'У таблиці рішень має бути принаймні 1 стовпчик висновків',
    'LBL_PMSE_MESSAGE_LABEL_CHANGE_COLUMN_TYPE' => 'Значення, пов’язані із цією змінною буде вилучено. Продовжити?',
    'LBL_PMSE_MESSAGE_LABEL_REMOVE_VARIABLE' => 'Справді видалити цю змінну?',

    'LBL_PMSE_LABEL_CONDITIONS' => 'Умови',
    'LBL_PMSE_LABEL_RETURN' => 'Повернення',
    'LBL_PMSE_LABEL_CONCLUSIONS' => 'Висновки',
    'LBL_PMSE_LABEL_CHANGE_FIELD' => 'Змінити поле',
    'LBL_PMSE_LABEL_RETURN_VALUE' => 'Повернути значення',

    'LBL_PMSE_TOOLTIP_ADD_CONDITION' => 'Додати умову',
    'LBL_PMSE_TOOLTIP_ADD_CONCLUSION' => 'Додати висновок',
    'LBL_PMSE_TOOLTIP_ADD_ROW' => 'Додати рядок',
    'LBL_PMSE_TOOLTIP_REMOVE_COLUMN' => 'Вилучити стовпець',
    'LBL_PMSE_TOOLTIP_REMOVE_CONDITION' => 'Видалити умову',
    'LBL_PMSE_TOOLTIP_REMOVE_CONCLUSION' => 'Видалити висновок',
    'LBL_PMSE_TOOLTIP_REMOVE_COL_DATA' => 'Видалити дані в стовпці',

    'LBL_PMSE_DROP_DOWN_CHECKED' => 'Так',
    'LBL_PMSE_DROP_DOWN_UNCHECKED' => 'Ні',
    'LBL_PMSE_IMPORT_BUSINESS_RULES_FAILURE' => 'Не вдалося створити запис процесу обробки бізнес-правила з файлу',

    'LBL_PMSE_MESSAGE_REQUIRED_FIELDS_BUSINESSRULES' => 'Це бізнес-правило недійсне, оскільки воно використовує недійсні поля або поля, які не знайдено у вашій версії SugarCRM. Виправте наведені нижче помилки та збережіть бізнес-правило.',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_EDIT' => 'Це бізнес-правило використовується у визначенні процесів. Справді редагувати бізнес-правило?',
    'LBL_PMSE_PROCESS_BUSINESS_RULES_DELETE' => "Це бізнес-правило не можна видалити, оскільки воно використовується у визначенні процесів.",
);
