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
    // Dashboard Names
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'Cuadro de mando de la lista de oportunidades',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'Cuadro de mando de la lista de oportunidades',

    'LBL_MODULE_NAME' => 'Oportunidades',
    'LBL_MODULE_NAME_SINGULAR' => 'Oportunidad',
    'LBL_MODULE_TITLE' => 'Oportunidades: Inicio',
    'LBL_SEARCH_FORM_TITLE' => 'Búsqueda de Oportunidades',
    'LBL_VIEW_FORM_TITLE' => 'Vista de Oportunidades',
    'LBL_LIST_FORM_TITLE' => 'Lista de Oportunidades',
    'LBL_OPPORTUNITY_NAME' => 'Nombre Oportunidad:',
    'LBL_OPPORTUNITY' => 'Oportunidad:',
    'LBL_NAME' => 'Nombre Oportunidad',
    'LBL_INVITEE' => 'Contactos',
    'LBL_CURRENCIES' => 'Monedas',
    'LBL_LIST_OPPORTUNITY_NAME' => 'Nombre',
    'LBL_LIST_ACCOUNT_NAME' => 'Cuenta',
    'LBL_LIST_DATE_CLOSED' => 'Fecha Cierre',
    'LBL_LIST_AMOUNT' => 'Probable',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'Cantidad convertida',
    'LBL_ACCOUNT_ID' => 'ID de Cuenta',
    'LBL_CURRENCY_RATE' => 'Tipo de Cambio',
    'LBL_CURRENCY_ID' => 'ID de Moneda',
    'LBL_CURRENCY_NAME' => 'Nombre de Moneda',
    'LBL_CURRENCY_SYMBOL' => 'Símbolo de Moneda',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'Oportunidad - Actualizar Moneda',
    'UPDATE_DOLLARAMOUNTS' => 'Actualizar Cantidades en Dólares EEUU',
    'UPDATE_VERIFY' => 'Verificar Cantidades',
    'UPDATE_VERIFY_TXT' => 'Verifica que los valores de las cantidades en las oportunidades son números decimales válidos con sólo caracteres numéricos (0-9) y decimales(.)',
    'UPDATE_FIX' => 'Corregir Cantidades',
    'UPDATE_FIX_TXT' => 'Intenta corregir cualquier cantidad no válida creando un número decimal válido a partir de la cantidad actual. Antes realiza una copia de seguridad de todas las cantidades modificadas en el campo de base de datos amount_backup. Si tras la correción detecta incidencias, no vuelva a realizar esta operación sin restaurar los valores previos desde la copia de seguridad ya que si no sobrescribirá la copia de seguridad con nuevos datos no válidos.',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'Actualiza las cantidades en Dólares EEUU para las oportunidades basadas en el conjunto actual de cambios de moneda. Este valor se usa para calcular gráficas y vistas de listas de cantidades monetarias.',
    'UPDATE_CREATE_CURRENCY' => 'Creación de nueva moneda:',
    'UPDATE_VERIFY_FAIL' => 'Fallo de verificación de registro:',
    'UPDATE_VERIFY_CURAMOUNT' => 'Cantidad actual:',
    'UPDATE_VERIFY_FIX' => 'La corrección daría',
    'UPDATE_INCLUDE_CLOSE' => 'Registros cerrados incluidos',
    'UPDATE_VERIFY_NEWAMOUNT' => 'Nueva cantidad:',
    'UPDATE_VERIFY_NEWCURRENCY' => 'Nueva moneda:',
    'UPDATE_DONE' => 'Hecho',
    'UPDATE_BUG_COUNT' => 'Problemas detectados cuya resolución se ha intentado:',
    'UPDATE_BUGFOUND_COUNT' => 'Problemas detectados:',
    'UPDATE_COUNT' => 'Registros actualizados:',
    'UPDATE_RESTORE_COUNT' => 'Registros con cantidades restauradas:',
    'UPDATE_RESTORE' => 'Restaurar Cantidades',
    'UPDATE_RESTORE_TXT' => 'Restaura los valores de las cantidades desde la copia de seguridad creada durante la corrección.',
    'UPDATE_FAIL' => 'No ha podido actualizarse -',
    'UPDATE_NULL_VALUE' => 'La cantidad es NULL, estableciéndola a 0 -',
    'UPDATE_MERGE' => 'Unificar Monedas',
    'UPDATE_MERGE_TXT' => 'Unifica múltiples monedas en una única moneda. Si detecta que hay múltiples registros de moneda para la misma moneda, puede unificarlas. Esto también unificará las monedas para el resto de módulos.',
    'LBL_ACCOUNT_NAME' => 'Cuenta:',
    'LBL_CURRENCY' => 'Moneda:',
    'LBL_DATE_CLOSED' => 'Fecha de Cierre Prevista:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'Fecha Prevista de Cierre Timestamp',
    'LBL_TYPE' => 'Tipo:',
    'LBL_CAMPAIGN' => 'Campaña:',
    'LBL_NEXT_STEP' => 'Próximo paso:',
    'LBL_LEAD_SOURCE' => 'Toma de contacto',
    'LBL_SALES_STAGE' => 'Etapa de ventas:',
    'LBL_SALES_STATUS' => 'Estado',
    'LBL_PROBABILITY' => 'Probabilidad (%):',
    'LBL_DESCRIPTION' => 'Descripción:',
    'LBL_DUPLICATE' => 'Posible oportunidad duplicada',
    'MSG_DUPLICATE' => 'El registro de oportunicad que va a crear podría ser un duplicado de otro registro de oportunidad existente. Los registros de oportunidad con nombres similares aparecen en una lista a continuación.<br>Haga clic en Guardar para continuar con la creación de esta oportunidad, o en Cancelar para volver al módulo sin crear la oportunidad.',
    'LBL_NEW_FORM_TITLE' => 'Nueva Oportunidad',
    'LNK_NEW_OPPORTUNITY' => 'Nueva Oportunidad',
    'LNK_CREATE' => 'Nueva Venta',
    'LNK_OPPORTUNITY_LIST' => 'Ver Oportunidades',
    'ERR_DELETE_RECORD' => 'Debe especificar un número de registro para eliminar la oportunidad.',
    'LBL_TOP_OPPORTUNITIES' => 'Mis Principales Oportunidades',
    'NTC_REMOVE_OPP_CONFIRMATION' => '¿Está seguro de que desea eliminar este contacto de la oportunidad?',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => '¿Está seguro de que desea eliminar esta oportunidad del proyecto?',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Oportunidades',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Actividades',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'Historial',
    'LBL_RAW_AMOUNT' => 'Importe Bruto',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Clientes Potenciales',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contactos',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'Documentos',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Proyectos',
    'LBL_ASSIGNED_TO_NAME' => 'Asignado a:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'Usuario Asignado',
    'LBL_LIST_SALES_STAGE' => 'Etapa de Ventas',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'Mis Oportunidades Cerradas',
    'LBL_TOTAL_OPPORTUNITIES' => 'Oportunidades Totales',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'Oportunidades Ganadas',
    'LBL_ASSIGNED_TO_ID' => 'Usuario Asignado:',
    'LBL_CREATED_ID' => 'Creado por ID',
    'LBL_MODIFIED_ID' => 'Modificado por ID',
    'LBL_MODIFIED_NAME' => 'Modificada por Nombre de Usuario',
    'LBL_CREATED_USER' => 'Usuario Creado',
    'LBL_MODIFIED_USER' => 'Usuario Modificado',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'Oportunidad de campaña',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'Proyectos',
    'LABEL_PANEL_ASSIGNMENT' => 'Asignación',
    'LNK_IMPORT_OPPORTUNITIES' => 'Importar Oportunidades',
    'LBL_EDITLAYOUT' => 'Editar diseño' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'Id de campaña',
    'LBL_OPPORTUNITY_TYPE' => 'Tipo de oportunidad',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'Nombre del usuario asignado',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID del usuario asignado',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'Modificado por ID',
    'LBL_EXPORT_CREATED_BY' => 'Creado por ID',
    'LBL_EXPORT_NAME' => 'Nombre',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'Emails de contactos relacionados',
    'LBL_FILENAME' => 'Adjunto',
    'LBL_PRIMARY_QUOTE_ID' => 'Presupuesto Principal',
    'LBL_CONTRACTS' => 'Contratos',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'Contratos',
    'LBL_PRODUCTS' => 'Elementos presupuestados',
    'LBL_RLI' => 'Línea de Ingreso',
    'LNK_OPPORTUNITY_REPORTS' => 'Ver Informes de Oportunidades',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'Presupuestos',
    'LBL_TEAM_ID' => 'ID de Equipo',
    'LBL_TIMEPERIODS' => 'Periodos de Tiempo',
    'LBL_TIMEPERIOD_ID' => 'ID de Periodo de tiempo',
    'LBL_COMMITTED' => 'Objectivo Alcanzado',
    'LBL_FORECAST' => 'Incluido en Previsión',
    'LBL_COMMIT_STAGE' => 'Fase de Compromiso',
    'LBL_COMMIT_STAGE_FORECAST' => 'Previsión',
    'LBL_WORKSHEET' => 'Hoja de Trabajo',

    'TPL_RLI_CREATE' => 'Una oportunidad debe tener una Línea de Ingreso asociada.',
    'TPL_RLI_CREATE_LINK_TEXT' => 'Crear una línea de ingreso',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'Elementos presupuestados',
    'LBL_RLI_SUBPANEL_TITLE' => 'Líneas de Ingreso',

    'LBL_TOTAL_RLIS' => 'nº Total de Líneas de Ingreso',
    'LBL_CLOSED_RLIS' => 'nº de Líneas de Ingreso Cerradas',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'No puede eliminar Oportunidades que contienen Líneas de Ingreso cerradas',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'Uno o más de los registros seleccionados contienen Líneas de Ingreso cerradas y no pueden ser eliminados.',
    'LBL_INCLUDED_RLIS' => 'Nº de artículos incluidos en líneas de ingresos',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'Presupuestos',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'Jerarquía de oportunidades',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'Configure el campo de Fecha de cierre prevista en los registros de Oportunidades resultantes para que sean las fechas de cierre más tempranas o más tardías de las líneas de ingresos existentes',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'Total de Pipeline es',

    'LBL_OPPORTUNITY_ROLE'=>'Rol en Oportunidad',
    'LBL_NOTES_SUBPANEL_TITLE' => 'Notas',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'Si hace clic en Confirmar borrará TODOS los datos de Previsiones y cambiará la Vista de Oportunidades. Si esto no es lo que desea hacer, haga clic en cancelar para volver a la configuración anterior.',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'Al hacer clic en Confirmar, borrará TODOS los datos de previsiones y cambiará la vista Oportunidades. '
        .'Además, se deshabilitarán TODAS las definiciones de procesos con un módulo de público objetivo de las líneas de ingresos. '
        .'Si no es lo que desea, haga clic en Cancelar para volver a la configuración anterior.',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'Si todos las líneas de ingresos están cerrados y al menos se ha ganado uno,',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'la Fase de ventas de oportunidades se define como "Ganada".',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'Si todas las líneas de ingresos están en la Fase de ventas "Cerrado perdido",',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'la Fase de ventas de oportunidades se define como "Perdida".',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'Si alguna línea de ingresos todavía está abierta,',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'la Oportunidad se marcará con la Fase de ventas menos avanzada.',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'Tras iniciar este cambio, las notas resumen de las líneas de ingresos se construirán en segundo plano. Cuando las notas se completen y estén disponibles, se enviará una notificación a la dirección de correo electrónico de su perfil de usuario. Si su instancia está configurada como {{forecasts_module}}, Sugar también le enviará una notificación cuando sus registros {{module_name}} se sincronicen con el módulo {{forecasts_module}} y estén disponibles para nuevos {{forecasts_module}}. Tenga en cuenta que su instancia se debe configurar para enviar correos electrónicos en Admin > Configuración de correo electrónico para que se envíen las notificaciones.',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'Tras iniciar este cambio, se crearán registros de las líneas de ingresos para cada {{module_name}} existente en segundo plano. Cuando los Elementos de las líneas de ingresos se completen y estén disponibles, se enviará una notificación a la dirección de correo electrónico de su perfil de usuario. Tenga en cuenta que su instancia se debe configurar para enviar correos electrónicos en Admin > Configuración de correo electrónico para que se envíen las notificaciones.',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'El módulo {{plural_module_name}} le permite monitorizar las ventas individuales de principio a fin. Cada registro de {{module_name}} representa una venta potencial e incluye datos relevantes de la venta, así como otros registros importantes, como {{quotes_module}}, {{contacts_module}}, etc. Un {{module_name}} normalmente progresará por las distintas etapas de venta, hasta que se marque como "Ganada" o "Perdida". Los {{plural_module_name}} se pueden aprovechar aun más utilizando el módulo de {{forecasts_singular_module}} de Sugar para entender y predecir tendencias de ventas, además de dirigir el trabajo para conseguir las cuotas de venta.',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'El módulo {{plural_module_name}} le permite monitorizar ventas individuales y elementos de línea que pertenecen a dichas ventas de principio a fin. Cada registro de {{module_name}} representa una venta potencial e incluye datos relevantes de la venta, así como otros registros importantes, como {{quotes_module}}, {{contacts_module}}, etc.

- Para editar los campos de este registro haga clic en un campo individual o en el botón Editar.
- Vea o modifique los enlaces con otros registros en los subpaneles cambiando el panel izquierdo inferior a "Vista de datos".
- Escriba y vea comentarios de usuarios y el historial de cambios del registro en el {{activitystream_singular_module}} cambiando el panel izquierdo inferior a "Flujo de Actividades".
- Siga o marque como favorito este registro mediante los iconos a la derecha del nombre del registro.
- Hay acciones adicionales en el menú desplegable de acciones a la derecha del botón de Editar.',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'El módulo {{plural_module_name}} le permite monitorizar ventas individuales y las líneas relacionadas con esas ventas de principio a fin. Cada registro de {{module_name}} representa una venta potencial e incluye datos relevantes para la venta, así como otros registros importantes, como {{quotes_module}}, {{contacts_module}}, etc.

Para crear un {{module_name}}:
1. Proporcione valores para los campos tal y como desee.
 - Los campos marcados como "Obligatorios" se deben completar antes de guardar.
 - Haga clic en "Mostrar más" para mostrar campos adicionales si fuera necesario.
2. Haga clic en "Guardar" para finalizar el nuevo registro y volver a la página anterior.',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'Sincronización con Marketo&reg;',
    'LBL_MKTO_ID' => 'ID de cliente potencial de Marketo',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => '10 principales Oportunidades de venta',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'Muestra las diez principales Oportunidades en un gráfico de burbujas.',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'Mis oportunidades',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "Las oportunidades de mi equipo",
);
