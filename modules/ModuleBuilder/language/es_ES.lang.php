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
    'LBL_LOADING' => 'Cargando ...' /*for 508 compliance fix*/,
    'LBL_HIDEOPTIONS' => 'Ocultar Opciones' /*for 508 compliance fix*/,
    'LBL_DELETE' => 'Eliminar' /*for 508 compliance fix*/,
    'LBL_POWERED_BY_SUGAR' => 'Desarrollado por SugarCRM' /*for 508 compliance fix*/,
    'LBL_ROLE' => 'Función',
'help'=>array(
    'package'=>array(
            'create'=>'Proporcionar un <b> nombre </b> para el paquete. El nombre que introduzca debe comenzar por una letra y solo puede estar formado por letras, números y guiones bajos. No se pueden utilizar espacios ni otros caracteres especiales. (Ejemplo: HR_Management) <br/><br/> Puede proporcionar <b> Autor </b> y <b> Descripción </b> como información para el paquete. <br/><br/> Haga clic en <b>Guardar</b> para crear el paquete.',
            'modify'=>'Las propiedades y posibles acciones para el <b>paquete</b> aparecerá aquí. <br><br> Puede modificar el <b>nombre</b>, <b>Autor</b> y <b>Descripción</b> del paquete, así como ver y personalizar todos los módulos contenidos en el mismo. <br><br> Haga clic en <b>nuevo módulo</b> para crear un módulo para el paquete.<br><br> Si el paquete contiene al menos un módulo, puede <b>publicar</b> y <b>implementar</b> el paquete, así como <b>exportar</b> las personalizaciones realizadas en el paquete.',
            'name'=>'Este es el <b>nombre</b> del paquete actual. <br/><br/> El nombre que introduzca debe ser estar formado por letras, números y guiones bajos. No se pueden utilizar espacios ni otros caracteres especiales. (Ejemplo: HR_Management)',
            'author'=>'Este es el <b> Autor </b> que se muestra durante la instalación como el nombre de la entidad que creó el paquete. <br><br> El autor podría ser un individuo o una empresa.',
            'description'=>'Esta es la <b>Descripción</b> del paquete que se muestra durante la instalación.',
            'publishbtn'=>'Haga clic en <b>Publicar</b> para guardar todos los datos introducidos y crear un archivo .zip que es una versión instalable del paquete.<br><br> Use el <b>Cargador de Módulos</b> para cargar el archivo .zip e instalar el paquete.',
            'deploybtn'=>'Haga clic en <b>Implementar</b> para guardar todos los datos introducidos y para instalar el paquete, incluyendo todos los módulos en la instancia actual.',
            'duplicatebtn'=>'Haga clic en <b>Duplicar</b> para copiar el contenido del paquete en un paquete nuevo y para mostrar el nuevo paquete. <br/><br/> Para el nuevo paquete, un nuevo nombre es generado automáticamente añadiendo un número al final del nombre del paquete utilizado para duplicarlo. Puede cambiar el nombre del nuevo paquete mediante la introducción de un <b>nombre</b> nuevo y después haciendo clic en <b>Guardar</b>.',
            'exportbtn'=>'Haga clic en <b>Exportar</b> para crear un archivo .zip que contiene las personalizaciones realizadas en el paquete. <br><br> El archivo generado no es una versión instalable del paquete. <br><br> Use el <b >Cargador de módulos</b> para importar el archivo .zip y tener el paquete, incluidas las personalizaciones, aparecen en el Generador de módulos.',
            'deletebtn'=>'Haga clic en <b>Eliminar</b> para eliminar este paquete y todos los archivos relacionados con este paquete.',
            'savebtn'=>'Haga clic en <b>Guardar</b> para guardar todos los datos introducidos relacionados con el paquete.',
            'existing_module'=>'Haga clic en el icono del <b>Módulo</b> para editar las propiedades y personalizar los campos, las relaciones y diseños asociados con el módulo.',
            'new_module'=>'Haga clic en <b>Nuevo módulo </b> para crear un nuevo módulo para este paquete.',
            'key'=>'Estas 5 letras, <b>clave</b> alfanuméricas se usa como prefijo para todos los directorios, los nombres de clases y las tablas de base de datos para todos los módulos en el paquete actual. <br><br>La clave se utiliza en un esfuerzo para lograr la singularidad de los nombres de las tablas.',
            'readme'=>'Haga clic para añadir <b> Léame </b> de texto para este paquete. <br><br>El léame estará disponible en el momento de la instalación.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Proporcionar un <b>Nombre</b> para el módulo. La <b>etiqueta</b> que le proporcione aparecerá en la pestaña de navegación. <br/><br/> Elija si desea ver una pestaña de navegación para el módulo seleccionando la casilla de <b> Pestaña de navegación </b>. <br/><br/> Revise la casilla de <b>equipo de seguridad</b> para tener un campo de selección del equipo dentro de los registros del módulo. <br/><br/> A continuación, seleccione el tipo de módulo que desea crear. <br/><br/> Seleccione un tipo de plantilla. Cada plantilla contiene un conjunto específico de los campos, así como los diseños predefinidos que puede usar como base de su módulo. <br/><br/> Haga clic en <b>Guardar </b> para crear el módulo.',
        'modify'=>'Puede modificar las propiedades del módulo o personalizar los <b>Campos</b>, <b>Relaciones</b> y <b>Diseños</b> relacionados con el módulo.',
        'importable'=>'Marque la casilla <b>Importables </b> para permitir la importación de datos de este módulo. <br><br> Un enlace de Asistente de importación aparecerá en el panel de accesos directos en el módulo. El Asistente de importación facilita la importación de datos de fuentes externas en el módulo personalizado.',
        'team_security'=>'Marque la casilla de <b>Equipo de seguridad </b> para habilitar el equipo de  seguridad para este módulo. <br/><br/> Si el equipo de seguridad está activado, el campo de selección del equipo aparecerá dentro de los registros en el módulo ',
        'reportable'=>'Marque esta casilla para poder realizar informes de este módulo.',
        'assignable'=>'Marque esta casilla para poder asignar un registro del módulo a un usuario seleccionado.',
        'has_tab'=>'Marque <b>Pestaña de Navegación </b> para proporcionar una pestaña de navegación para el módulo.',
        'acl'=>'Marque esta casilla para habilitar los controles de acceso en este módulo, incluyendo la seguridad a nivel de campo.',
        'studio'=>'Marque esta casilla para permitir a los administradores personalizar este módulo dentro de Studio.',
        'audit'=>'Marque  esta casilla para habilitar la auditoría de este módulo. Los cambios en ciertos campos se registrarán de modo que los administradores pueden revisar el historial de cambios.',
        'viewfieldsbtn'=>'Haga clic en <b>Ver Campos </b> para ver los campos asociados con el módulo y para crear y editar los campos personalizados.',
        'viewrelsbtn'=>'Haga clic en <b>Ver Relaciones </b> para ver las relaciones asociadas con este módulo y crear nuevas relaciones.',
        'viewlayoutsbtn'=>'Haga clic en <b>Ver Diseños </b> para ver los diseños para el módulo y para personalizar la organización de campos dentro de los diseños.',
        'viewmobilelayoutsbtn' => 'Haga clic en <b>Ver diseños móviles</b> para ver los diseños móviles de módulos y personalizar la distribución de campos en dichos diseños.',
        'duplicatebtn'=>'Haga clic en <b>Duplicados </b> para copiar las propiedades del módulo en un nuevo módulo y para mostrar el nuevo módulo. <br/><br/> En el nuevo módulo, un nuevo nombre se genera automáticamente añadiendo un número al final del nombre del módulo utilizado para crearlo.',
        'deletebtn'=>'Haga clic en <b>Eliminar </b> para eliminar este módulo.',
        'name'=>'Este es el <b>Nombre </b> del módulo actual. <br/><br/>El nombre debe ser alfanumérico y debe comenzar con una letra y sin espacios. (Ejemplo: HR_Management)',
        'label'=>'Esta es la <b>Etiqueta </b> que aparecerá en la pestaña de navegación para el módulo. ',
        'savebtn'=>'Haga clic en <b> Guardar</b> para guardar todos los datos introducidos en relación con el módulo.',
        'type_basic'=>'La plantilla <b>Básica </b> cuenta con campos básicos, como el nombre, Asignado a, Equipo, Fecha de creación y los campos de Descripción.',
        'type_company'=>'El tipo de plantilla <b>Empresa</b> ofrece campos específicos según la organización, como por ejemplo Nombre de la empresa, Sector y Dirección de facturación.<br/><br/>Utilice esta plantilla para crear módulos similares a los módulos estándar de contabilidad.',
        'type_issue'=>'El tipo de plantilla <b>Asunto</b> ofrece campos específicos según los casos e incidencias, como por ejemplo Número, Estado, Prioridad y Descripción.<br/><br/>Utilice esta plantilla para crear módulos similares a los módulos estándar de seguimiento de casos e incidencias.',
        'type_person'=>'El tipo de plantilla <b>Persona</b> ofrece campos específicos para individuos, como por ejemplo Saludo, Título, Nombre, Dirección y Número de teléfono.<br/><br/>Utilice esta plantilla para crear módulos similares a los módulos estándar de Contactos y Clientes potenciales.',
        'type_sale'=>'El tipo de plantilla de <b>Venta</b> dispone de campos específicos a las oportunidades, como por ejemplo, Toma de contacto, Fase, Cantidad y Probabilidad.<br /><br />Utilice esta plantilla para crear módulos que sean similares al módulo estándar de Oportunidades.',
        'type_file'=>'El tipo de plantilla de <b>Archivo</b> dispone de campos específicos de Documentos, como por ejemplo, Nombre de archivo, tipo de Documento y Fecha de publicación. <br><br>Utilice esta plantilla para crear módulos que sean similares al módulo estándar de Documentos.',

    ),
    'dropdowns'=>array(
        'default' => 'Todas las <b>Listas desplegables</b>para la aplicación se enumeran aquí.<br><br>Se pueden usar las listas desplegables para campos en cualquier módulo.<br><br>Para realizar cambios en una lista desplegable, haga clic en el nombre de la lista desplegable.<br><br>Haga clic en <b>Añadir lista desplegable</b> para crear una nueva lista desplegable.',
        'editdropdown'=>'Las listas desplegables pueden utilizarse para campos desplegables estándar o personalizados en cualquier módulo.<br><br>Proporcione un <b>Nombre</b> para la lista desplegable.<br><br>Si hay paquetes de idioma instalados en la aplicación, puede seleccionar el <b>Idioma</b> a utilizar en los elementos de lista. <br><br>En el campo de <b>Nombre del elemento</b>, proporcione un nombre para la opción en la lista desplegable. Este nombre no aparecerá en la lista desplegable visible para los usuarios. <br><br>En el campo de <b>Etiqueta de la pantalla</b>, proporcione una etiqueta que será visible para los usuarios. <br> <br>Después proporcionar el nombre del artículo y la etiqueta a mostrar, haga clic en <b>Agregar</b> para agregar el elemento a la lista desplegable. <br><br>Para reordenar los elementos de la lista, arrastre y suelte los elementos en las posiciones deseadas. <br> <br>Para editar la etiqueta de pantalla de un elemento, haga clic en el <b>Icono de editar</b> y escriba una nueva etiqueta. Para eliminar un elemento de la lista desplegable, haga clic en el <b>Icono Borrar</b>. <br><br>Para deshacer un cambio hecho en una etiqueta de pantalla, haga clic en <b>Deshacer</b>.  Para rehacer un cambio que se deshizo, haga clic en <b>Rehacer</b>. <br><br>Haga clic en <b>Guardar</b> para guardar la lista desplegable.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Haga clic en <b>Guardar e implementar</b> para guardar los cambios que ha realizado y para que estén aplicados en el módulo.',
        'historyBtn'=> 'Haga clic en <b>Ver historial</b> para ver y restaurar un diseño previamente guardado del historial.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar diseño por defecto</b> para restablecer una vista a su diseño original.',
        'Hidden' 	=> '<b>Oculto</b> los campos no aparecerán en el subpanel.',
        'Default'	=> '<b>Por defecto</b> los campos aparecen en el subpanel.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Haga clic en <b>Guardar e implementar</b> para guardar los cambios que ha realizado y para que estén aplicados en el módulo.',
        'historyBtn'=> 'Haga clic en <b>Ver historial</b> para ver y restaurar una disposición previamente guardada del historial.<br><br><b>Restaurar</b> dentro de la <b>Ver historial</b> restaura la ubicación del campo dentro de configuraciones previamente guardadas. Para cambiar las etiquetas de campo, haga clic en el icono de edición junto a cada campo.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar diseño por defecto</b> para restablecer una vista a su diseño original. <br><br><b>Restaurar el diseño por defecto</b> solo restaura la ubicación del campo dentro del diseño original. Para cambiar las etiquetas de campo, haga clic en el icono Editar junto a cada campo.',
        'Hidden' 	=> 'Los campos <b>ocultos</b> no están disponibles actualmente para los usuarios ver en Vistas de lista.',
        'Available' => 'Los campos <b>disponibles</b> no se muestran por defecto, pero los usuarios pueden añadirlos en Vistas de lista.',
        'Default'	=> 'Los campos <b>por defecto</b> aparecen en Vistas de listas que no están personalizadas por los usuarios.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Haga clic en <b>Guardar e implementar</b> para guardar los cambios que ha realizado y para que estén aplicados en el módulo.',
        'historyBtn'=> 'Haga clic en <b>Ver historial</b> para ver y restaurar una disposición previamente guardada del historial.<br><br><b>Restaurar</b> dentro de la <b>Ver historial</b> restaura la ubicación del campo dentro de configuraciones previamente guardadas. Para cambiar las etiquetas de campo, haga clic en el icono de edición junto a cada campo.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar diseño por defecto</b> para restablecer una vista a su diseño original. <br><br><b>Restaurar el diseño por defecto</b> solo restaura la ubicación del campo dentro del diseño original. Para cambiar las etiquetas de campo, haga clic en el icono Editar junto a cada campo.',
        'Hidden' 	=> 'Los campos <b>ocultos</b> no están disponibles actualmente para los usuarios ver en Vistas de lista.',
        'Default'	=> 'Los campos <b>por defecto</b> aparecen en Vistas de listas que no están personalizadas por los usuarios.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Al hacer clic en <b>Guardar e implementar</b> se guardan todos los cambios y se activan',
        'Hidden' 	=> 'Los campos <b>ocultos</b> no aparecen en la búsqueda.',
        'historyBtn'=> 'Haga clic en <b>Ver historial</b> para ver y restaurar una disposición previamente guardada del historial.<br><br><b>Restaurar</b> dentro de la <b>Ver historial</b> restaura la ubicación del campo dentro de configuraciones previamente guardadas. Para cambiar las etiquetas de campo, haga clic en el icono de edición junto a cada campo.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar diseño por defecto</b> para restablecer una vista a su diseño original. <br><br><b>Restaurar el diseño por defecto</b> solo restaura la ubicación del campo dentro del diseño original. Para cambiar las etiquetas de campo, haga clic en el icono Editar junto a cada campo.',
        'Default'	=> 'Los campos <b>por defecto</b> aparecen en la búsqueda.'
    ),
    'layoutEditor'=>array(
        'defaultdetailview'=>'The <b>Layout</b> area contains the fields that are currently displayed within the <b>DetailView</b>.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'defaultquickcreate'=>'The <b>Layout</b> area contains the fields that are currently displayed within the <b>QuickCreate</b> form.<br><br>The QuickCreate form appears in the subpanels for the module when the Create button is clicked.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        //this defualt will be used for edit view
        'default'	=> 'The <b>Layout</b> area contains the fields that are currently displayed within the <b>EditView</b>.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        //this defualt will be used for edit view
        'defaultrecordview'   => 'The <b>Layout</b> area contains the fields that are currently displayed within the <b>Record View</b>.<br/><br/>The <b>Toolbox</b> contains the <b>Recycle Bin</b> and the fields and layout elements that can be added to the layout.<br><br>Make changes to the layout by dragging and dropping elements and fields between the <b>Toolbox</b> and the <b>Layout</b> and within the layout itself.<br><br>To remove a field from the layout, drag the field to the <b>Recycle Bin</b>. The field will then be available in the Toolbox to add to the layout.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'saveBtn'	=> 'Haga clic en <b>Guardar</b> para conservar los cambios realizados en el diseño desde la última vez que guardó. <br><br>Los cambios no se visualizarán en el módulo hasta que implemente los cambios guardados.',
        'historyBtn'=> 'Haga clic en <b>Ver historial</b> para ver y restaurar una disposición previamente guardada del historial.<br><br><b>Restaurar</b> dentro de la <b>Ver historial</b> restaura la ubicación del campo dentro de configuraciones previamente guardadas. Para cambiar las etiquetas de campo, haga clic en el icono de edición junto a cada campo.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar diseño por defecto</b> para restablecer una vista a su diseño original. <br><br><b>Restaurar el diseño por defecto</b> solo restaura la ubicación del campo dentro del diseño original. Para cambiar las etiquetas de campo, haga clic en el icono Editar junto a cada campo.',
        'publishBtn'=> 'Haga clic en <b>Guardar e implementar</b> para guardar todos los cambios realizados en el diseño desde la última vez que lo guardó y para activar los cambios en el módulo. <br><br>El diseño se mostrará inmediatamente en el módulo.',
        'toolbox'	=> 'La <b>caja de herramientas</b> contiene la <b>Papelera de reciclaje</b>, el conjunto de campos disponibles y elementos de diseño adicionales para agregar en el diseño. <br/><br/> Los campos y los elementos de diseño del cuadro de herramientas pueden arrastrarse y soltarse en el diseño, y los campos y elementos del diseño pueden arrastrarse y soltarse en la caja de herramientas. <br><br>Los elementos del diseño son <b>paneles</b> y <b>filas</b>. Agregar una fila nueva o un nuevo panel en el diseño proporciona ubicaciones adicionales en el diseño de los campos. <br/><br/>Arrastre y suelte cualquiera de los campos a la caja de herramientas o el diseño en un campo ocupado para intercambiar las ubicaciones de los dos campos. <br/><br/> El campo <b>Relleno</b> crea espacio en blanco en el diseño donde se coloca.',
        'panels'	=> 'El área de <b>Diseño</b> proporciona una vista del aspecto que tendrá la presentación dentro del módulo de activación de los cambios realizados en el diseño.<br/><br/>Puede cambiar la posición de los campos, filas y paneles arrastrándolos y soltándolos en el lugar deseado.<br/><br/>Elimine los elementos arrastrándolos y soltándolos en la <b>Papelera de reciclaje</b>en la caja de herramientas, o añada nuevos elementos y campos arrastrándolos desde la <b>Caja de herramientas</b>y soltándolos en el lugar deseado en el diseño.',
        'delete'	=> 'Arrastre y suelte cualquier elemento aquí para quitarlo del diseño',
        'property'	=> 'Edite la <b>etiqueta</b> que se muestra para este campo.<br><br><b>Ancho</b> proporciona un valor del ancho en píxeles para módulos Sidecar y como un porcentaje del ancho de la tabla para módulos compatibles hacia atrás.',
    ),
    'fieldsEditor'=>array(
        'default'	=> 'Los <b>Campos</b> que están disponibles para el módulo se enumeran aquí por Nombre de campo.<br><br>Los campos personalizados creados para el módulo aparecen por encima de los campos que están disponibles para el módulo por defecto.<br><br>Para editar un campo, haga clic en el<b>Nombre de Campo</b>.<br/><br/>Para crear un nuevo campo, haga clic en <b>Agregar campo</b>.',
        'mbDefault'=>'Los <b>Campos</b> que están disponibles para el módulo se enumeran aquí por Nombre de campo.<br><br> Para configurar las propiedades de un campo, haga clic en el nombre de campo. <br><br>Para crear un nuevo campo, haga clic en <b>Agregar Campo</b>. La etiqueta junto con las otras propiedades del campo nuevo se pueden editar después de la creación haciendo clic en el nombre de campo.<br><br>Después de implementar el módulo, los nuevos campos creados en el módulo Constructor se consideran campos estándar en el módulo implementado en Studio.',
        'addField'	=> 'Seleccione <b>Tipo de dato</b> para un campo nuevo. El tipo que seleccione determinará qué tipo de caracteres se pueden introducir para el campo. Por ejemplo, solo los números que son números enteros pueden ingresarse en los campos que son de tipo entero.<br><br> Proporcione un <b>Nombre</b> para el campo. El nombre debe ser alfanumérico y no debe contener espacios. Los guiones bajos son válidos.<br><br>La <b>Etiqueta visible</b>es la etiqueta que aparecerá para los campos en los diseños de los módulos. La <b>Etiqueta del sistema</b> se utiliza para hacer referencia al campo en el código.<br><br> Según el tipo de datos seleccionado para el campo, algunas o todas las propiedades a continuación pueden establecerse para el campo:<br><br> <b>Texto de ayuda</b> aparece temporalmente mientras un usuario se desplaza sobre el campo y se puede utilizar para solicitar al usuario el tipo de entrada que desee.<br><br> <b>Comentario de texto</b> solo se ve dentro de Studio o módulo Constructor, y se puede utilizar para describir el campo para los administradores.<br><br> <b>Valor predeterminado</b> aparecerá en el campo. Los usuarios pueden introducir un nuevo valor en el campo o utilizar el valor por defecto.<br><br> Seleccione la casilla de verificación de <b>Actualización masiva</b> para poder utilizar la función de actualización masiva para el campo.<br><br> El valor de <b>Tamaño máximo</b>determina el número máximo de caracteres que se pueden introducir en el campo.<br><br> Seleccione la casilla de verificación de <b>Campo requerido</b> con el fin de hacer que el campo sea necesario. Un valor se debe proporcionar en el campo con el fin de guardar un registro que contenga el campo.<br><br> Seleccione la casilla de verificación de <b>Notificable</b>para permitir que el campo que se utilizará para los filtros y para la visualización de los datos en los Informes.<br><br> Seleccione la casilla de verificación de <b>Auditoría</b>para poder seguir los cambios en el campo en el registro de cambios.<br><br>Seleccione una opción en el campo de <b>Importación</b>para permitir, prohibir o exigir que el campo se importe en el Asistente de importación.<br><br>Seleccione una opción en el campo de <b>Combinación de duplicados</b> para activar o desactivar la Combinación de duplicados y encontrar duplicados.<br><br>Las propiedades adicionales se pueden establecer para ciertos tipos de datos.',
        'editField' => 'Las propiedades de este campo pueden personalizarse. <br><br>Haga clic en <b>clonar</b> para crear un nuevo campo con las mismas propiedades.',
        'mbeditField' => 'La <b>Etiqueta de pantalla</b> de un campo de plantilla se puede personalizar. Las otras propiedades del campo no se pueden personalizar.<br><br>Haga clic en <b>Clonar</b> para crear un nuevo campo con las mismas propiedades.<br><br> Para eliminar un campo de plantilla para que no se muestre en el módulo, elimine el campo de los <b>diseños</b> apropiados.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Exporte personalizaciones realizadas en Studio mediante la creación de paquetes que se pueden cargar en otra instancia de Sugar a través del <b>Cargador de módulos</b>.<br><br>Primero, Proporcione un <b>Nombre de paquete</b>. Puede proporcionar el <b>Autor</b> y la <b>Descripción</b> como también la información del paquete.<br><br>Seleccione los módulos que contienen las personalizaciones que desea exportar. Solo aparecerán los módulos que contienen personalizaciones para que usted seleccione.<br><br>Luego haga clic en <b>Exportar</b>para crear un archivo .zip para el paquete que contiene las personalizaciones.',
        'exportCustomBtn'=>'Haga clic en <b>exportar</b> para crear un archivo .zip para el paquete que contiene las personalizaciones que desea exportar.',
        'name'=>'Este es el <b>nombre</b> del paquete. Este nombre se mostrará durante la instalación.',
        'author'=>'Este es el <b>Autor</b> que se muestra durante la instalación como el nombre de la entidad que creó el paquete. El autor puede ser un individuo o una empresa.',
        'description'=>'Esta es la <b>Descripción</b> del paquete que se muestra durante la instalación.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bienvenido al área de <b>Herramientas para desarrolladores</b>. <br/> <br/> Utilice las herramientas de este área para crear y administrar los campos y los módulos estándar y personalizados.',
        'studioBtn'	=> 'Utilice <b>Studio</b> para personalizar módulos implementados.',
        'mbBtn'		=> 'Utilice el <b> Contructor de módulos </b> para crear nuevos módulos.',
        'sugarPortalBtn' => 'Utilice el <b>Editor del Portal de Sugar</b> para administrar y personalizar el Portal de Sugar.',
        'dropDownEditorBtn' => 'Utilice el <b>Editor desplegable</b> para añadir y editar menús desplegables globales para los campos de listas desplegables.',
        'appBtn' 	=> 'En el Modo de aplicación se pueden personalizar diversas propiedades del programa, como por ejemplo cuántos informes TPS se muestran en la página principal',
        'backBtn'	=> 'Volver al paso anterior.',
        'studioHelp'=> 'Utilice <b>Studio</b> para determinar qué y cómo se muestra la información en los módulos.',
        'studioBCHelp' => ' indica que el módulo es un módulo compatible hacia atrás',
        'moduleBtn'	=> 'Haga clic para editar este módulo.',
        'moduleHelp'=> 'Los componentes que se pueden personalizar para el módulo aparecen aquí. <br><br>Haga clic en un icono para seleccionar el componente que desea editar.',
        'fieldsBtn'	=> 'Cree y personalice los <b>campos</b> para almacenar información en el módulo.',
        'labelsBtn' => 'Edite las <b>etiquetas</b> que se muestran para los campos y otros títulos en el módulo.'	,
        'relationshipsBtn' => 'Agregar nuevo o ver <b>relaciones</b> existentes del módulo.' ,
        'layoutsBtn'=> 'Personalice los <b>diseños</b> del módulo.  Los diseños son las diferentes vistas del módulo que contiene campos.<br><br>Puede determinar los campos que aparecen y cómo están organizados en cada diseño.',
        'subpanelBtn'=> 'Determine qué campos aparecen en los <b>subpaneles</b> en el módulo.',
        'portalBtn' =>'Personalice los <b>diseños</b> del módulo que aparecen en el <b>Portal de Sugar</b>.',
        'layoutsHelp'=> 'Los <b>diseños</b> del módulo que pueden personalizarse aparecen aquí. <br><br>Los diseños muestran campos y los datos de campo. <br><br>Haga clic en un icono para seleccionar el diseño para editar.',
        'subpanelHelp'=> 'Los <b>subpaneles</b> del módulo que pueden personalizarse aparecen aquí.<br><br>Haga clic en un ícono para seleccionar el módulo para editar.',
        'newPackage'=>'Haga clic en <b>paquete nuevo</b> para crear un nuevo paquete.',
        'exportBtn' => 'Haga clic en <b> Exportar personalizaciones </b> para crear un paquete que contiene las personalizaciones realizadas en Studio para módulos específicos.',
        'mbHelp'    => 'Utilice el <b>Generador de módulos</b> para crear paquetes con módulos personalizados basados en objetos estándar o personalizados.',
        'viewBtnEditView' => 'Personalice el diseño de <b>Vista de edición</b> del módulo. <br><br>La Vista de edición es el formulario que contiene campos de entrada para capturar datos ingresados por el usuario.',
        'viewBtnDetailView' => 'Personalice el diseño de <b>Vista detallada</b> del módulo. <br><br>La vista detallada muestra los datos del campo introducidos por el usuario.',
        'viewBtnDashlet' => 'Personalice el <b>Dashlet de Sugar</b>, del módulo, incluidos la Búsqueda y la Vista de lista del Dashlet de Sugar. <br><br>El Dashlet de Sugar estará disponible para añadirse a las páginas en el módulo de inicio.',
        'viewBtnListView' => 'Personalice el diseño del módulo de <b>Vista de lista</b>. <br><br>La búsqueda de resultados aparece en la Vista de lista.',
        'searchBtn' => 'Personalizar diseños de <b>Búsqueda</b> del módulo. <br><br>Determinar qué campos se pueden utilizar para filtrar los registros que aparecen en la Vista de lista.',
        'viewBtnQuickCreate' =>  'Personalizar el diseño de <b>Creación rápida</b> del módulo. <br><br>El formulario de Creación rápida aparece en subpaneles y en el módulo de correos electrónicos.',

        'searchHelp'=> 'Los formularios de <b>búsqueda</b> que pueden personalizarse aparecen aquí. <br><br>Los formularios de búsqueda tienen campos para filtrar registros. <br> <br>Haga clic en un icono para seleccionar el diseño de búsqueda para editar.',
        'dashletHelp' =>'Los diseños del <b>Dashlet de Sugar</b> que pueden personalizarse aparecen aquí. <br><br>El dashlet de Sugar estará disponible para añadir a las páginas en el módulo de Inicio.',
        'DashletListViewBtn' =>'La <b>Vista de lista del Dashlet de Sugar</b> muestra los registros basados en los filtros de búsqueda del Dashlet de Sugar.',
        'DashletSearchViewBtn' =>'El <b>Dashlet de búsqueda de Sugar</b> filtra los registros para la vista de lista del Dashlet de Sugar.',
        'popupHelp' =>'Los diseños de la <b>ventana emergente</b> que pueden personalizarse aparecen aquí. <br>',
        'PopupListViewBtn' => 'El diseño de <b>Vista de lista de ventana emergente</b> se usa para ver una lista de registros cuando se selecciona uno o más registros para relacionarse con el registro actual.',
        'PopupSearchViewBtn' => 'El diseño de <b>Búsqueda de ventana emergente</b> permite a los usuarios buscar los registros para relacionarlos con un registro actual y aparece sobre la lista de vista de ventana emergente en la misma ventana. Los módulos heredados utilizan este diseño para realizar búsquedas emergentes mientras que los módulos Sidecar utilizan la configuración de diseño de búsqueda.',
        'BasicSearchBtn' => 'Personalice el formulario de <b>Búsqueda básica</b> que aparece en la pestaña de búsqueda básica en el área de búsqueda del módulo.',
        'AdvancedSearchBtn' => 'Personalice el formulario de <b>Búsqueda avanzada</b> que aparece en la pestaña de búsqueda avanzada en el área de búsqueda del módulo.',
        'portalHelp' => 'Administre y personalice el <b>Portal de Sugar</b>.',
        'SPUploadCSS' => 'Cargue una <b>Hoja de estilo</b> para el Portal de Sugar.',
        'SPSync' => 'Personalizaciones de <b>sincronización</b> en la instancia del Portal de Sugar.',
        'Layouts' => 'Personalice los <b>diseños</b> de los módulos del Portal de Sugar.',
        'portalLayoutHelp' => 'Los módulos dentro del Portal de Sugar aparecen en esta área. <br><br>Seleccione un módulo para editar los <b>diseños</b>.',
        'relationshipsHelp' => 'Todas las <b>Relaciones</b> que existen entre el módulo y otros módulos implementados aparecen aquí.<br><br> El <b>Nombre</b> de la relación es el nombre generado por el sistema para la relación.<br><br>El <b>Módulo primario</b> es el módulo al que le pertenecen las relaciones. Por ejemplo, todas las propiedades de las relaciones para las que el módulo de Cuentas funciona como el módulo principal se almacenan en las tablas de la base de datos de Cuentas.<br><br>El <b>Tipo</b> es el tipo de relación entre el módulo primario y el <b>Módulo relacionado</b>.<br><br>Haga clic en el título de una columna para ordenar según la columna.<br><br>Haga clic en una fila de la tabla de relaciones para ver las propiedades asociadas con la relación.<br><br>Haga clic en <b>Añadir relación</b> para crear una nueva relación.<br><br>Las relaciones pueden crearse entre dos módulos implementados.',
        'relationshipHelp'=>'Las <b>relaciones</b> pueden crearse entre el módulo y otro módulo implementado. Las relaciones <br><br>se expresan visualmente a través de subpaneles y relacionan los campos en los registros del módulo. <br> <br>Seleccione uno de los <b>tipos</b> siguientes de relaciones para el módulo: <br><br><b>Uno a uno</b>, los registros de ambos módulos contendrán campos relacionados. <br> <br><b>Uno a muchos</b>, el registro del módulo principal contendrá un subpanel y el del módulo relacionado tendrá un campo relacionado. <br><br><b>Muchos a muchos</b>, los registros de ambos módulos mostrarán subpaneles. <br> <br>Seleccione el <b>Módulo relacionado</b> para la relación. <br><br>Si el tipo de relación involucra subpaneles, seleccione la vista del subpanel para los módulos apropiados. <br> <br>Haga clic en <b>Guardar</b> para crear la relación.',
        'convertLeadHelp' => "Aquí usted puede agregar módulos a la pantalla de diseño de conversión y modificar la configuración de los ya existentes.<br/><br/>
<b>Orden:</b><br/>
Contactos, Cuentas y Oportunidades deben mantener su orden. Puede reordenar cualquier otro módulo al arrastrar su fila en la tabla.<br/><br/>
<b>Dependencia:</b><br/>
Si se incluye Oportunidades, Cuentas debe ser requerido o eliminado del diseño de conversión.<br/><br/>
<b>Módulo:</b> El Nombre del módulo.<br/><br/>
<b>Requerido:</b>los módulos requeridos deben crearse o seleccionarse antes de que se pueda convertir el cliente potencial.<br/><br/>
<b>Copia de datos:</b>Si se selecciona, los campos del cliente potencial se copiarán en los campos con el mismo nombre en los registros creados recientemente.<br/><br/>
<b>Eliminar:</b> Elimine este módulo desde el diseño de conversión.<br/><br/>        ",
        'editDropDownBtn' => 'Editar una Lista desplegable global',
        'addDropDownBtn' => 'Agregar una nueva Lista desplegable global',
    ),
    'fieldsHelp'=>array(
        'default'=>'Los <b>campos</b> del módulo aparecen aquí por nombre de campo. <br><br>La plantilla de módulo incluye un conjunto determinado de campos. <br> <br>Para crear un nuevo campo, haga clic en <b>Agregar campo</b>. <br><br>Para editar un campo, haga clic en el <b>Nombre de campo</b>. <br/><br/> Después de que el módulo se implementa, los nuevos campos creados en el generador del módulo, junto con los campos de la plantilla, se consideran campos estándar en Studio.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'Las <b>relaciones</b> que se han creado entre el módulo y otros módulos aparecen aquí. <br><br>El <b>nombre</b> de la relación es el nombre generado por el sistema para la relación. <br><br>El <b>Módulo principal</b> es el módulo que posee de las relaciones. Las propiedades de la relación se almacenan en las tablas de la base de datos que pertenecen al módulo principal. <br><br>El <b>tipo</b> es el tipo de relación que existe entre el módulo principal y el <b>Módulo relacionado</b>.<br><br>Haga clic en un título de columna para ordenar por la columna. <br><br>Haga clic en una fila de la tabla de relación para ver y editar las propiedades asociadas con la relación. <br><br>Haga clic en <b>Agregar relación</b> para crear una nueva relación.',
        'addrelbtn'=>'coloque el ratón sobre ayuda para agregar una relación.',
        'addRelationship'=>'Las <b>Relaciones</b> pueden crearse entre el módulo y otro módulo personalizado o un módulo implementado.<br><br> Las relaciones se expresan visualmente a través de subpaneles y relacionan campos en los registros del módulo.<br><br>Seleccione uno de los siguientes <b>Tipos</b> de relaciones para el módulo:<br><br> <b>Uno a uno</b>, los registros de ambos módulos contendrán campos relacionados. <br> <br><b>Uno a muchos</b>, el registro del módulo principal contendrá un subpanel y el del módulo relacionado tendrá un campo relacionado. <br><br><b>Muchos a muchos</b>, los registros de ambos módulos mostrarán subpaneles. <br> <br>Seleccione el <b>Módulo relacionado</b> para la relación. <br><br>Si el tipo de relación incluye subpaneles, seleccione la vista del subpanel para los módulos apropiados. <br> <br>Haga clic en <b>Guardar</b> para crear la relación.',
    ),
    'labelsHelp'=>array(
        'default'=> 'Las <b>Etiquetas</b> de los campos y otros títulos en el módulo pueden cambiarse.<br><br>Edite la etiqueta haciendo clic en el campo, introduciendo una nueva etiqueta y haciendo clic en <b>Guardar</b>.<br><br>Si hay algún paquete de idioma instalado en la aplicación, podrá seleccionar qué <b>Idioma</b> utilizará para las etiquetas.',
        'saveBtn'=>'Haga clic en <b>Guardar</b> para guardar todos los cambios.',
        'publishBtn'=>'Haga clic en <b>Guardar e implementar</b> para guardar todos los cambios y activarlos.',
    ),
    'portalSync'=>array(
        'default' => 'Introduzca la <b>URL del Portal de Sugar</b> de la instancia del portal para actualizar y haga clic en <b>Ir</b>. <br><br>Luego introduzca un nombre de usuario y contraseña válidos de Sugar y haga clic en <b>Iniciar sincronización</b>. <br> <br>Las personalizaciones realizadas en los <b>diseños</b> del Portal de sugar, junto con la <b>Hoja de estilo</b> si se subió alguna, se transferirán a la instancia del portal especificada.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Puede personalizar el aspecto del Portal de Sugar mediante una hoja de estilo. <br><br>Seleccione una <b>Hoja de estilo</b> para cargar. <br> <br>La hoja de estilo se implementará en el Portal de Sugar la próxima vez que se realice una sincronización.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Para empezar a trabajar en un proyecto, haga clic en <b>Nuevo paquete</b> para crear un nuevo paquete para albergar módulos personalizados. <br/><br/>Cada paquete puede contener uno o más módulos. <br/><br/>Por ejemplo, quizá desee crear un paquete que contenga un módulo personalizado que esté relacionado con el módulo de cuentas estándar. O, usted puede crear un paquete que contenga varios módulos nuevos que trabajen juntos como un proyecto y que estén relacionados entre sí y con otros módulos que ya están en la aplicación.',
            'somepackages'=>'Un <b>paquete</b> actúa como un contenedor para módulos personalizados, todos los cuales forman parte de un proyecto. El paquete puede contener uno o más <b>módulos</b> personalizados que pueden estar relacionados entre sí o con otros módulos de la aplicación. <br/> <br/> Después de crear un paquete para su proyecto, puede crear módulos para el paquete inmediatamente, o puede volver al constructor de módulos en otro momento posterior para completar el proyecto. <br><br>Cuando el proyecto se completa, puede <b>implementar</b> el paquete para instalar los módulos personalizados dentro de la aplicación.',
            'afterSave'=>'Su nuevo paquete debe contener al menos un módulo. Puede crear uno o más módulos personalizados para el paquete. <br/> <br/>Haga clic en <b>Nuevo módulo</b> para crear un módulo personalizado para este paquete. <br/> <br/> Después de crear, al menos un módulo, puede publicar o implementar el paquete para ponerlo a disposición de su instancia o la instancia de otros usuarios. <br/><br/> Para implementar el paquete en un solo paso dentro de su instancia de Sugar, haga clic en <b>Implementar</b>. <br>Haga clic en <br><b>Publicar</b> para guardar el paquete como un archivo .zip. Después de guardar el archivo .zip en el sistema, utilice el <b>Cargador de módulos</b> para cargar e instalar el paquete dentro de su instancia de Sugar. <br/><br/>Puede distribuir el archivo a otros usuarios para que puedan cargarlo e instalarlo en sus propias instancias de Sugar.',
            'create'=>'Un <b>paquete</b> actúa como contenedor de módulos personalizados, todos los cuales forman parte de un proyecto. El paquete puede contener uno o más <b>módulos</b> personalizados que puedan estar relacionados entre sí o con otros módulos en la aplicación.<br/><br/>Después de crear un paquete para su proyecto, puede crear módulos para el paquete inmediatamente, o puede volver al Constructor de módulos en más adelante para completar el proyecto.',
            ),
    'main'=>array(
        'welcome'=>'Utilice las <b>Herramientas para desarrolladores</b> para crear y administrar los campos y los módulos estándar y personalizados. <br/> <br/> Para administrar los módulos de la aplicación, haga clic en <b>Studio</b>. <br/><br/> Para crear módulos personalizados, haga clic en el <b>Constructor de módulos</b>.',
        'studioWelcome'=>'Todos los módulos instalados actualmente, incluidos los objetos estándar y cargados mediante el módulo, pueden personalizarse en Studio.'
    ),
    'module'=>array(
        'somemodules'=>"Puesto que el paquete actual contiene, al menos, un módulo, puede <b>Implementar</b> los módulos del paquete dentro de su instancia de Sugar o <b>Publicar</b> el paquete para instalarse en la instancia de Sugar actual o en otra instancia con el <b>Cargador del módulo</b>. <br/> <br/>Para instalar el paquete directamente en su instancia de Sugar, haga clic en <b>Implementar</b>.<br><br>Para crear un archivo .zip para el paquete que puede cargarse e instalarse dentro de la instancia actual de Sugar y otras instancias mediante el <b>Cargador de módulos</b>, haga clic en <b>Publicar</b>.<br/><br/> Puede construir los módulos para este paquete en etapas y publicarlos o implementarlos cuando esté listo para hacerlo. <br/> <br/> Después de publicar o implementar un paquete, puede realizar cambios en las propiedades del paquete y personalizar aún más los módulos. Luego, vuelva a publicar o redistribuya el paquete para aplicar los cambios." ,
        'editView'=> 'Aquí puede editar los campos existentes. Puede eliminar cualquiera de los campos existentes o añadir campos disponibles en el panel izquierdo.',
        'create'=>'Al elegir el <b>Tipo</b> de módulo que desea crear, tenga en cuenta los tipos de campos que le gustaría tener dentro del módulo. <br/><br/> Cada plantilla de módulo contiene un conjunto de campos que pertenecen al tipo de módulo descrito en el título de. <br/><br/> <b>Básico</b>: proporciona campos básicos que aparecen en módulos estándar, como los campos de nombre, asignado a, equipo, fecha de creación y descripción. <br/><br/><b>Empresa</b>: ofrece campos específicos de la organización, tales como nombre de la empresa, industria y dirección de facturación. Utilice esta plantilla para crear módulos que son similares al estándar módulo de cuentas. <br/><br/> <b>Persona</b>: proporciona campos individuales especiales, tales como saludo, título, nombre, dirección y número de teléfono.  Utilice esta plantilla para crear módulos que sean similares a los módulos estándar de contactos y clientes potenciales. <br/><br/> <b>Problema</b>: proporciona campos casos y errores específicos, como número, estado, prioridad y descripción. Utilice esta plantilla para crear módulos similares a los módulos de los casos estándar y de seguimiento de Incidencia. <br/><br/> Nota: Después de crear el módulo, usted puede editar las etiquetas de los campos de la plantilla, así como crear campos personalizados para agregar a los diseños del módulo.',
        'afterSave'=>'Personalice el módulo para satisfacer sus necesidades mediante la edición y la creación de campos, al establecer relaciones con otros módulos y al arreglar campos dentro de los diseños.<br/><br/>Para ver los campos de la plantilla y administrar campos personalizados dentro del módulo, haga clic en <b>Campos de vista</b>.<br/><br/>Para crear y administrar las relaciones entre el módulo y otros módulos, ya sea que son módulos que están en la aplicación u otros módulos que están dentro del mismo paquete, haga clic en <b>Vista de relaciones</b>. <br/><br/>Para editar los diseños del módulo, haga clic en <b>Ver diseños</b>. Puede cambiar los diseños de Vista de detalle, Vista de edición y Vista de lista para el módulo como lo haría para los módulos que ya están en la aplicación Studio.<br/><br/>Para crear un módulo con las mismas propiedades que el módulo actual, haga clic en <b>Duplicar</b>. Puede personalizar más el módulo nuevo.',
        'viewfields'=>'Los campos del módulo pueden personalizarse para satisfacer sus necesidades.<br/><br/> No se pueden borrar campos estándar, pero puede quitarlos de las distribuciones apropiadas dentro de las páginas de diseños. <br/><br/>Puede crear rápidamente nuevos campos que tienen propiedades similares a los campos existentes al hacer clic en <b>Clonar</b> en el formulario de <b>Propiedades</b>. Introduzca cualquier propiedad nueva y haga clic en <b>Guardar</b>. <br/><br/> Se recomienda establecer todas las propiedades de los campos estándar y campos personalizados antes de publicar e instalar el paquete que contiene el módulo personalizado.',
        'viewrelationships'=>'Puede crear relaciones de muchos a muchos entre el módulo actual y otros módulos del paquete, o entre el módulo actual y módulos ya instalados en la aplicación. <br><br>Para crear relaciones uno a muchos y uno a uno, cree campos de <b>Relacionar</b> y <b>Relación Flex</b> para los módulos.',
        'viewlayouts'=>'Puede controlar qué campos están disponibles para la captura de datos en la <b>Vista de edición</b>. También puede controlar qué datos se muestran en la <b>Vista de detalle</b>. No es necesario que las vistas coincidan. <br/><br/>El formulario de creación rápida aparece cuando se hace clic en <b>Crear</b> en un subpanel del módulo. Por defecto, el diseño del formulario de <b>Creación rápida</b> es igual al diseño por defecto de <b>Vista de edición</b>. Puede personalizar el formulario de creación rápida para que contenga menos o diferentes campos que el diseño de la Vista de edición. <br><br>Puede determinar la seguridad de módulo mediante la personalización de diseño junto con la <b>Administración de roles</b>.<br><br>',
        'existingModule' =>'Después de crear y personalizar este módulo, puede crear módulos adicionales o volver al paquete para <b>publicar</b> o <b>implementar</b> el paquete.<br><br>Para crear módulos adicionales, haga clic en <b>Duplicar</b> para crear un módulo con las mismas propiedades que el módulo actual, o navegue hacia el paquete y haga clic en <b>Nuevo módulo</b>.<br><br>Si está listo para <b>publicar</b> o <b>implementar</b> el paquete que contiene este módulo, navegue hacia el paquete para realizar estas funciones. Puede publicar e implementar los paquetes que contienen al menos un módulo.',
        'labels'=> 'Pueden cambiarse las etiquetas de los campos estándar así como campos personalizados. El cambio de las etiquetas de campo no afectará a los datos almacenados en los campos.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Hay tres columnas que aparecen a la izquierda. La columna "Por defecto" contiene los campos que se muestran en una vista de lista de forma predeterminada, la columna "Disponible" contiene campos que un usuario puede utilizar para crear una vista de lista personalizada, y la columna de "Ocultos" contiene campos disponibles para que usted como administrador pueda agregarla en las columnas por defecto o Disponibles para que las utilicen los usuarios, pero actualmente están desactivados.',
        'savebtn'	=> 'Haga clic en <b>Guardar</b> para guardar todos los cambios y activarlos.',
        'Hidden' 	=> 'Los campos ocultos son campos que no están actualmente disponibles para que los usuarios los utilicen en las vistas de lista.',
        'Available' => 'Los campos disponibles son campos que no se muestran por defecto, pero los usuarios pueden activarlos.',
        'Default'	=> 'Los campos por defecto se muestran a los usuarios que no han creado una configuración de vista de lista personalizada.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Hay dos columnas que aparecen a la izquierda. La columna de "Por defecto" contiene los campos que se mostrarán en la vista de búsqueda, y la columna de "Ocultos" contiene los campos disponibles para usted como administrador para agregar a la vista.',
        'savebtn'	=> 'Hacer clic en <b>Guardar e implementar</b> guardará todos los cambios y los activará.',
        'Hidden' 	=> 'Los campos ocultos son campos que no se mostrarán en la vista de búsqueda.',
        'Default'	=> 'Los campos por defecto se mostrarán en la vista de búsqueda.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Se muestran dos columnas a la izquierda. La columna de la derecha, con etiqueta de Diseño actual o Previsualización de diseño, es donde se cambia el diseño del módulo. La columna de la izquierda, con nombre: Caja de herramientas, contiene herramientas y elementos útiles para la edición del diseño. <br/><br/>Si el área de diseño tiene el título de Diseño actual está trabajando en una copia del diseño que actualmente se está utilizando en el módulo.<br/><br/>Si tiene el título de Previsualización de diseño, está trabajando en una copia creada anteriormente mediante un clic en el botón Guardar, que ya podría haberse guardado desde la versión que visualizan los usuarios en este módulo.',
        'saveBtn'	=> 'Hacer clic en este botón guarda el diseño para que pueda conservar los cambios. Cuando regrese a este módulo se iniciará desde este diseño cambiado. Sin embargo, su diseño no será visto por los usuarios del módulo hasta que haga clic en el botón Guardar y Publicar.',
        'publishBtn'=> 'Haga clic en este botón para implementar el diseño. Esto significa que los usuarios de este módulo verán este diseño inmediatamente.',
        'toolbox'	=> 'La caja de herramientas contiene una variedad de características útiles para la edición de diseños, incluida un área de reciclaje, un conjunto de elementos adicionales y un conjunto de campos disponibles. Cualquiera de estos elementos puede arrastrarse y soltarse en el diseño.',
        'panels'	=> 'Este área muestra cómo se verá su diseño para los usuarios de este módulo cuando esté implementado. <br/><br/>Puede reposicionar elementos como campos, filas y paneles al arrastrarlos y soltarlos; puede eliminar los elementos arrastrándolos y soltándolos en el área de reciclaje de la caja de herramientas, o añadir elementos nuevos arrastrándolos desde la caja de herramientas y soltándolos en el diseño donde lo desee.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Se muestran dos columnas a la izquierda. La columna de la derecha, con etiqueta de Diseño actual o Previsualización de diseño, es donde se cambia el diseño del módulo. La columna de la izquierda, con nombre: Caja de herramientas, contiene herramientas y elementos útiles para la edición del diseño. <br/><br/>Si el área de diseño tiene el título de Diseño actual está trabajando en una copia del diseño que actualmente se está utilizando en el módulo.<br/><br/>Si tiene el título de Previsualización de diseño, está trabajando en una copia creada anteriormente mediante un clic en el botón Guardar, que ya podría haberse guardado desde la versión que visualizan los usuarios en este módulo.',
        'dropdownaddbtn'=> 'Hacer clic en este botón añade un nuevo elemento en la lista desplegable.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Las personalizaciones hechas en Studio dentro de esta instancia pueden empaquetarse e implementarse en otra instancia.  <br><br>Proporcione un <b>nombre de paquete</b>.  Puede proporcionar información de <b>autor</b> y la <b>descripción</b> para el paquete. <br><br>Seleccione los módulos que contienen las personalizaciones para exportar. (Solo podrá seleccionar entre los módulos que contienen personalizaciones). <br><br>Haga clic en <b>exportar</b> para crear un archivo .zip para el paquete que contiene las personalizaciones.  El archivo .zip se puede cargar en otra instancia a través del <b>Cargador de módulos</b>.',
        'exportCustomBtn'=>'Haga clic en <b>exportar</b> para crear un archivo .zip para el paquete que contiene las personalizaciones que desea exportar.
',
        'name'=>'El <b>nombre</b> del paquete aparecerá en el Cargador del módulo después de que se carga el paquete para la instalación en Studio.',
        'author'=>'El <b>autor</b> es el nombre de la entidad que creó el paquete. El autor puede ser un individuo o una empresa.<br><br>El autor se mostrará en el Cargador del módulo después de que se cargue el paquete para la instalación en Studio.
',
        'description'=>'La <b>Descripción</b> del paquete se mostrará en el cargador del módulo después de que el paquete se cargue para la instalación en Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bienvenido al área de <b>Herramientas para desarrolladores</b>. <br/> <br/> Utilice las herramientas de este área para crear y administrar los campos y los módulos estándar y personalizados.',
        'studioBtn'	=> 'Utilice <b>Studio</b> para personalizar módulos instalados al cambiar la disposición del campo, al seleccionar qué campos estarán disponibles y al crear campos de datos personalizados.',
        'mbBtn'		=> 'Utilice el <b> Contructor de módulos </b> para crear nuevos módulos.',
        'appBtn' 	=> 'Utilice el modo de aplicación para personalizar las propiedades incluidas en el programa, por ejemplo, cómo muchos de los informes TPS que se muestran en la página de inicio',
        'backBtn'	=> 'Volver al paso anterior.',
        'studioHelp'=> 'Use el <b>Studio</b> para personalizar los módulos instalados.',
        'moduleBtn'	=> 'Haga clic para editar este módulo.',
        'moduleHelp'=> 'Seleccione el componente del módulo que desea editar',
        'fieldsBtn'	=> 'Editar información que se almacena en el módulo mediante el control de los <b> campos </b> del módulo. <br/><br/> Puede editar y crear campos personalizados aquí.',
        'layoutsBtn'=> 'Personalizar los <b>Diseños</b> de las vistas de edición, detalle, listado y búsqueda.',
        'subpanelBtn'=> 'Editar la información que se muestra en estos subpaneles de los módulos.',
        'layoutsHelp'=> 'Seleccione un <b>diseño para editar</b>. < br / <br/> Para cambiar el esquema que contiene los campos de datos para introducir datos, haga clic en <b>Editar vista</b>. <br/><br/> Para cambiar el diseño que muestra los datos introducidos en los campos en la vista de edición, haga clic en <b>Vista de detalles</b>. <br/> <br/> Para cambiar las columnas que aparecen en la lista por defecto, haga clic en <b>Vista de lista</b>.<br/><br/> Para cambiar los diseños del formulario de búsqueda básica y avanzada, haga clic en <b>Buscar</b>.',
        'subpanelHelp'=> 'Seleccione un <b>Subpanel</b> para editar.',
        'searchHelp' => 'Seleccione un diseño de <b>Búsqueda</b> para editar.',
        'labelsBtn'	=> 'Editar las <b>etiquetas</b> que se mostrarán en los valores en este módulo.',
        'newPackage'=>'Haga clic en <b>paquete nuevo</b> para crear un nuevo paquete.',
        'mbHelp'    => '<b>Bienvenido al Constructor de Módulos.</b><br/><br/>Utilice el <b>Constructor de Módulos</b> para crear paquetes que contengan módulos personalizados basados en objetos estándar o personalizados. <br/><br/>Para comenzar, haga clic en <b>Paquete nuevo</b> para crear un paquete nuevo o seleccione un paquete para editarlo.<br/><br/> Un <b>paquete</b> actúa como contenedor de módulos personalizados, todos los cuales forman parte de un proyecto. El paquete puede contener uno o más módulos personalizados que pueden estar relacionados entre sí en la aplicación. <br/><br/>Ejemplos: es posible que desee crear un paquete que contenga un módulo personalizado que esté relacionado al módulo estándar de Cuentas. O, quizá desee crear un paquete que contenga varios módulos nuevos que funcionen como un proyecto y que estén relacionados entre sí y con módulos de la aplicación.',
        'exportBtn' => 'Haga clic en <b>Exportar personalizaciones </b> para crear un paquete que contiene las personalizaciones realizadas en Studio para módulos específicos.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Editor de Listas Desplegables',

//ASSISTANT
'LBL_AS_SHOW' => 'Mostrar el Asistente en el futuro.',
'LBL_AS_IGNORE' => 'Ignorar el Asistente en el futuro.',
'LBL_AS_SAYS' => 'El Asistente Sugiere:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Constructor de Módulos',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Editor de Listas Desplegables',
'LBL_EDIT_DROPDOWN'=>'Editar Lista Desplegable',
'LBL_DEVELOPER_TOOLS' => 'Herramientas de Desarrollo',
'LBL_SUGARPORTAL' => 'Editor del Portal Sugar',
'LBL_SYNCPORTAL' => 'Sincronizar Portal',
'LBL_PACKAGE_LIST' => 'Lista de Paquetes',
'LBL_HOME' => 'Inicio',
'LBL_NONE'=>'-Ninguno-',
'LBL_DEPLOYE_COMPLETE'=>'Implementación completada',
'LBL_DEPLOY_FAILED'   =>'Ha ocurrido un error durante el proceso de implementación. Es posible que su paquete no se haya instalado correctamente',
'LBL_ADD_FIELDS'=>'Agregar Campos Personalizados',
'LBL_AVAILABLE_SUBPANELS'=>'Subpaneles Disponibles',
'LBL_ADVANCED'=>'Avanzada',
'LBL_ADVANCED_SEARCH'=>'Búsqueda Avanzada',
'LBL_BASIC'=>'Básica',
'LBL_BASIC_SEARCH'=>'Búsqueda Básica',
'LBL_CURRENT_LAYOUT'=>'Diseño',
'LBL_CURRENCY' => 'Moneda',
'LBL_CUSTOM' => 'Personalizado',
'LBL_DASHLET'=>'Dashlet de Sugar',
'LBL_DASHLETLISTVIEW'=>'Vista de Lista de Sugar Dashlet',
'LBL_DASHLETSEARCH'=>'Búsqueda de Sugar Dashlet',
'LBL_POPUP'=>'Vista Emergente',
'LBL_POPUPLIST'=>'Vista de Lista Emergente',
'LBL_POPUPLISTVIEW'=>'Vista de Lista Emergente',
'LBL_POPUPSEARCH'=>'Búsqueda Emergente',
'LBL_DASHLETSEARCHVIEW'=>'Búsqueda de Sugar Dashlet',
'LBL_DISPLAY_HTML'=>'Mostrar Código HTML',
'LBL_DETAILVIEW'=>'Vista Detallada',
'LBL_DROP_HERE' => '[Soltar Aquí]',
'LBL_EDIT'=>'Editar',
'LBL_EDIT_LAYOUT'=>'Editar Diseño',
'LBL_EDIT_ROWS'=>'Editar Filas',
'LBL_EDIT_COLUMNS'=>'Editar Columnas',
'LBL_EDIT_LABELS'=>'Editar Etiquetas',
'LBL_EDIT_PORTAL'=>'Editar Portal para',
'LBL_EDIT_FIELDS'=>'Editar Campos',
'LBL_EDITVIEW'=>'Vista de Edición',
'LBL_FILTER_SEARCH' => "Buscar",
'LBL_FILLER'=>'(relleno)',
'LBL_FIELDS'=>'Campos',
'LBL_FAILED_TO_SAVE' => 'Fallo Al Guardar',
'LBL_FAILED_PUBLISHED' => 'Fallo Al Publicar',
'LBL_HOMEPAGE_PREFIX' => 'Mi',
'LBL_LAYOUT_PREVIEW'=>'Vista Preliminar del Diseño',
'LBL_LAYOUTS'=>'Diseños',
'LBL_LISTVIEW'=>'Vista de Lista',
'LBL_RECORDVIEW'=>'Vista de Registro',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_NEW_PACKAGE' => 'Nuevo Paquete',
'LBL_NEW_PANEL'=>'Nuevo Panel',
'LBL_NEW_ROW'=>'Nueva Fila',
'LBL_PACKAGE_DELETED'=>'Paquete Eliminado',
'LBL_PUBLISHING' => 'Publicando ...',
'LBL_PUBLISHED' => 'Publicado',
'LBL_SELECT_FILE'=> 'Seleccionar Archivo',
'LBL_SAVE_LAYOUT'=> 'Guardar Diseño',
'LBL_SELECT_A_SUBPANEL' => 'Seleccione un Subpanel',
'LBL_SELECT_SUBPANEL' => 'Seleccione Subpanel',
'LBL_SUBPANELS' => 'Subpaneles',
'LBL_SUBPANEL' => 'Subpanel',
'LBL_SUBPANEL_TITLE' => 'Título:',
'LBL_SEARCH_FORMS' => 'Búsqueda',
'LBL_STAGING_AREA' => 'Área de Diseño (arrastre y suelte elementos aquí)',
'LBL_SUGAR_FIELDS_STAGE' => 'Campos Sugar (haga clic en los elementos para agregarlos al área de diseño)',
'LBL_SUGAR_BIN_STAGE' => 'Papelera Sugar (haga clic en los elementos para agregarlos al área de diseño)',
'LBL_TOOLBOX' => 'Caja de Herramientas',
'LBL_VIEW_SUGAR_FIELDS' => 'Ver Campos Sugar',
'LBL_VIEW_SUGAR_BIN' => 'Ver Papelera Sugar',
'LBL_QUICKCREATE' => 'Creación Rápida',
'LBL_EDIT_DROPDOWNS' => 'Editar una Lista Desplegable Global',
'LBL_ADD_DROPDOWN' => 'Agregar una nueva Lista Desplegable Global',
'LBL_BLANK' => '-vacío-',
'LBL_TAB_ORDER' => 'Orden de Tabulación',
'LBL_TAB_PANELS' => 'Habilitar pestañas',
'LBL_TAB_PANELS_HELP' => 'Cuando se activan las pestañas, use el menú desplegable "tipo" <br/> para que cada sección defina cómo se mostrará (pestaña o panel)',
'LBL_TABDEF_TYPE' => 'Tipo de Visualización',
'LBL_TABDEF_TYPE_HELP' => 'Seleccione la forma en la que se debe mostrar esta sección. Esta opción únicamente tendrá efecto si ha habilitado el modo pestañas para esta vista.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Pestaña',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Panel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Seleccione Panel para que el panel se muestre en la vista de diseño. Seleccione Pestaña para mostrar el panel en una pestaña independiente dentro del diseño. Cuando se ha seleccionado un panel como Pestaña, los siguientes paneles seleccionados como Panel se mostrarán en la vista de dicha pestaña. <br/>Siempre que seleccione un panel como Pestaña, se abrirá una nueva pestaña para el siguiente panel. Si se selecciona como Pestaña el segundo panel o posteriores, el primer panel se establecerá automáticamente como Pestaña.',
'LBL_TABDEF_COLLAPSE' => 'Contraer',
'LBL_TABDEF_COLLAPSE_HELP' => 'Seleccione para que por defecto el estado del panel sea contraído.',
'LBL_DROPDOWN_TITLE_NAME' => 'Nombre',
'LBL_DROPDOWN_LANGUAGE' => 'Idioma',
'LBL_DROPDOWN_ITEMS' => 'Elementos de Lista',
'LBL_DROPDOWN_ITEM_NAME' => 'Nombre del Elemento',
'LBL_DROPDOWN_ITEM_LABEL' => 'Etiqueta de Visualización',
'LBL_SYNC_TO_DETAILVIEW' => 'Sincronizar con Vista de Detalle',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Seleccione esta opción para sincronizar el diseño de la Vista de Edición con el correspondiente diseño de la Vista de Detalle. Los campos y su colocación en la Vista de Detalle<br>se sincronizarán y guardarán automáticamente en la Vista de Detalle al pulsar en Guardar o Guardar e implementar en la Vista de Edición.<br>No se podrán realizar cambios en el diseño de la Vista de Detalle.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Esta Vista de detalle se sincroniza con la Vista de edición correspondiente. <br> Campos y su colocación en este Vista de detalle reflejan los campos y colocación en la Vista de edición. <br> Cambios en la Vista de detalle no se pueden guardar ni implementar en esta pagina. Realice los cambios o elimine la sincronización en la vista de edición. ',
'LBL_COPY_FROM' => 'Copiar desde',
'LBL_COPY_FROM_EDITVIEW' => 'Copiar de la Vista de Edición',
'LBL_DROPDOWN_BLANK_WARNING' => 'Los valores son necesarios tanto para el nombre del elemento y la etiqueta de visualización. Para agregar un elemento en blanco, haga clic en Agregar, sin entrar en ningún valor para el nombre del elemento y la etiqueta de visualización.',
'LBL_DROPDOWN_KEY_EXISTS' => 'La Clave ya existe en el listado',
'LBL_DROPDOWN_LIST_EMPTY' => 'La lista debe contener al menos un elemento habilitado',
'LBL_NO_SAVE_ACTION' => 'No se encontró la acción guardar para esta vista.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establishLocation: documento creado erróneamente',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Indica un campo combinado. Un campo combinado es un conjunto de campos individuales. Por ejemplo, "Dirección" es un campo combinado que contiene "Calle", "Ciudad", "Código Postal", "Provincia/Estado" y "País".<br><br>Haga doble clic en el campo combinado para ver qué campos contiene.',
'LBL_COMBO_FIELD_CONTAINS' => 'contiene:',

'LBL_WIRELESSLAYOUTS'=>'Diseños para Móviles',
'LBL_WIRELESSEDITVIEW'=>'Vista de Edición para Móviles',
'LBL_WIRELESSDETAILVIEW'=>'Vista de Detalle para Móviles',
'LBL_WIRELESSLISTVIEW'=>'Vista de Lista para Móviles',
'LBL_WIRELESSSEARCH'=>'Búsqueda para Móviles',

'LBL_BTN_ADD_DEPENDENCY'=>'Agregar Dependencia',
'LBL_BTN_EDIT_FORMULA'=>'Editar Fórmula',
'LBL_DEPENDENCY' => 'Dependencia',
'LBL_DEPENDANT' => 'Dependiente',
'LBL_CALCULATED' => 'Valor Calculado',
'LBL_READ_ONLY' => 'Sólo Lectura',
'LBL_FORMULA_BUILDER' => 'Constructor de Fórmulas',
'LBL_FORMULA_INVALID' => 'Fórmula No Válida',
'LBL_FORMULA_TYPE' => 'La fórmula debe ser del tipo',
'LBL_NO_FIELDS' => 'No se han encontrado Campos',
'LBL_NO_FUNCS' => 'No se han encontrado Funciones',
'LBL_SEARCH_FUNCS' => 'Buscar Funciones...',
'LBL_SEARCH_FIELDS' => 'Buscar Campos...',
'LBL_FORMULA' => 'Fórmula',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Dependiente',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Arrastre los elementos de la lista de opciones disponibles a la izquierda para una de las listas de la derecha para hacer que la opción está disponible cuando se seleccione la opción principal. Si no hay elementos en la opción principal, cuando se seleccione esta, la lista desplegable de dependientes no se mostrará.',
'LBL_AVAILABLE_OPTIONS' => 'Opciones disponibles',
'LBL_PARENT_DROPDOWN' => 'Lista desplegable de la opción principal',
'LBL_VISIBILITY_EDITOR' => 'Editor de visibilidad',
'LBL_ROLLUP' => 'Rollup',
'LBL_RELATED_FIELD' => 'Campo relacionado',
'LBL_CONFIG_PORTAL_URL'=>'URL a la imagen de logotipo personalizado. Las dimensiones recomendadas del logotipo son 163 × 18 píxeles.',
'LBL_PORTAL_ROLE_DESC' => 'No elimine este rol. El rol del portal de autoservicio para clientes lo genera el mismo sistema durante la activación del Portal de Sugar. Utilice los controles de acceso dentro de este rol para habilitar y/o deshabilitar los módulos de Gestor de Incidencias, Casos o Base de conocimiento en el Portal de Sugar. Para evitar un comportamiento desconocido e imprevisible del sistema, no modifique otros controles de acceso para este rol. En caso de eliminación accidental de este rol, vuelva a crearlo desactivando y activando el Portal de Sugar.',

//RELATIONSHIPS
'LBL_MODULE' => 'Módulo',
'LBL_LHS_MODULE'=>'Módulo Principal',
'LBL_CUSTOM_RELATIONSHIPS' => '* relación creada en Studio',
'LBL_RELATIONSHIPS'=>'Relaciones',
'LBL_RELATIONSHIP_EDIT' => 'Editar Relación',
'LBL_REL_NAME' => 'Nombre',
'LBL_REL_LABEL' => 'Etiqueta',
'LBL_REL_TYPE' => 'Tipo',
'LBL_RHS_MODULE'=>'Módulo Relacionado',
'LBL_NO_RELS' => 'Sin Relaciones',
'LBL_RELATIONSHIP_ROLE_ENTRIES'=>'Condición Opcional' ,
'LBL_RELATIONSHIP_ROLE_COLUMN'=>'Columna',
'LBL_RELATIONSHIP_ROLE_VALUE'=>'Valor',
'LBL_SUBPANEL_FROM'=>'Subpanel de',
'LBL_RELATIONSHIP_ONLY'=>'No se creará ningún elemento visible para esta relación ya que existía anteriormente una relación visible entre estos dos módulos.',
'LBL_ONETOONE' => 'Uno a Uno',
'LBL_ONETOMANY' => 'Uno a Muchos',
'LBL_MANYTOONE' => 'Muchos a Uno',
'LBL_MANYTOMANY' => 'Muchos a Muchos',

//STUDIO QUESTIONS
'LBL_QUESTION_FUNCTION' => 'Seleccione una función o componente.',
'LBL_QUESTION_MODULE1' => 'Seleccione un módulo.',
'LBL_QUESTION_EDIT' => 'Seleccione un módulo a editar.',
'LBL_QUESTION_LAYOUT' => 'Seleccione un diseño a editar.',
'LBL_QUESTION_SUBPANEL' => 'Seleccione un subpanel a editar.',
'LBL_QUESTION_SEARCH' => 'Seleccione un diseño de búsqueda a editar.',
'LBL_QUESTION_MODULE' => 'Seleccione un componente de módulo a editar.',
'LBL_QUESTION_PACKAGE' => 'Seleccione un paquete a editar, o cree un nuevo paquete.',
'LBL_QUESTION_EDITOR' => 'Seleccione una herramienta.',
'LBL_QUESTION_DROPDOWN' => 'Seleccione una lista desplegable a editar, o cree una nueva lista desplegable.',
'LBL_QUESTION_DASHLET' => 'Seleccione un diseño de dashlet a editar.',
'LBL_QUESTION_POPUP' => 'Seleccione un diseño emergente a editar.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Relacionado Con',
'LBL_NAME'=>'Nombre',
'LBL_LABELS'=>'Etiquetas',
'LBL_MASS_UPDATE'=>'Actualización Masiva',
'LBL_AUDITED'=>'Auditado',
'LBL_CUSTOM_MODULE'=>'Módulo',
'LBL_DEFAULT_VALUE'=>'Valor Por Defecto',
'LBL_REQUIRED'=>'Requerido',
'LBL_DATA_TYPE'=>'Tipo',
'LBL_HCUSTOM'=>'PERSONALIZADO',
'LBL_HDEFAULT'=>'POR DEFECTO',
'LBL_LANGUAGE'=>'Idioma:',
'LBL_CUSTOM_FIELDS' => '* campo creado en Studio',

//SECTION
'LBL_SECTION_EDLABELS' => 'Editar Etiquetas',
'LBL_SECTION_PACKAGES' => 'Paquetes',
'LBL_SECTION_PACKAGE' => 'Paquete',
'LBL_SECTION_MODULES' => 'Módulos',
'LBL_SECTION_PORTAL' => 'Portal',
'LBL_SECTION_DROPDOWNS' => 'Listas Desplegables',
'LBL_SECTION_PROPERTIES' => 'Propiedades',
'LBL_SECTION_DROPDOWNED' => 'Editar Lista Desplegable',
'LBL_SECTION_HELP' => 'Ayuda',
'LBL_SECTION_ACTION' => 'Acción',
'LBL_SECTION_MAIN' => 'Principal',
'LBL_SECTION_EDPANELLABEL' => 'Editar Etiqueta de Panel',
'LBL_SECTION_FIELDEDITOR' => 'Editar Campo',
'LBL_SECTION_DEPLOY' => 'Implementar',
'LBL_SECTION_MODULE' => 'Módulo',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Editar Visibilidad',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Por Defecto',
'LBL_HIDDEN'=>'Oculto',
'LBL_AVAILABLE'=>'Disponible',
'LBL_LISTVIEW_DESCRIPTION'=>'A continuación se muestran tres columnas. La columna <b>Por Defecto</b> contiene los campos que se muestran en una lista por defecto. La columna <b>Adicional</b> contiene campos que un usuario puede elegir a la hora de crear una vista personalizada. La columna <b>Disponible</b> muestra columnas disponibles para usted como administrador, o bien para añadirlas a las columnas Por Defecto, o a las Adicionales para que las utilicen los usuarios.',
'LBL_LISTVIEW_EDIT'=>'Editor de Listas',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Vista Preliminar',
'LBL_MB_RESTORE'=>'Restaurar',
'LBL_MB_DELETE'=>'Eliminar',
'LBL_MB_COMPARE'=>'Comparar',
'LBL_MB_DEFAULT_LAYOUT'=>'Diseño por Defecto',

//END WIZARDS

//BUTTONS
'LBL_BTN_ADD'=>'Agregar',
'LBL_BTN_SAVE'=>'Guardar',
'LBL_BTN_SAVE_CHANGES'=>'Guardar Cambios',
'LBL_BTN_DONT_SAVE'=>'Descartar Cambios',
'LBL_BTN_CANCEL'=>'Cancelar',
'LBL_BTN_CLOSE'=>'Cerrar',
'LBL_BTN_SAVEPUBLISH'=>'Guardar y Desplegar',
'LBL_BTN_NEXT'=>'Siguiente',
'LBL_BTN_BACK'=>'Anterior',
'LBL_BTN_CLONE'=>'Clonar',
'LBL_BTN_COPY' => 'Copiar',
'LBL_BTN_COPY_FROM' => 'Copiar desde...',
'LBL_BTN_ADDCOLS'=>'Agregar Columnas',
'LBL_BTN_ADDROWS'=>'Agregar Filas',
'LBL_BTN_ADDFIELD'=>'Agregar Campo',
'LBL_BTN_ADDDROPDOWN'=>'Agregar Lista Desplegable',
'LBL_BTN_SORT_ASCENDING'=>'Ordenar en orden ascendente',
'LBL_BTN_SORT_DESCENDING'=>'Ordenar en orden descendente',
'LBL_BTN_EDLABELS'=>'Editar Etiquetas',
'LBL_BTN_UNDO'=>'Deshacer',
'LBL_BTN_REDO'=>'Rehacer',
'LBL_BTN_ADDCUSTOMFIELD'=>'Agregar Campo Personalizado',
'LBL_BTN_EXPORT'=>'Exportar Personalizaciones',
'LBL_BTN_DUPLICATE'=>'Duplicar',
'LBL_BTN_PUBLISH'=>'Publicar',
'LBL_BTN_DEPLOY'=>'Implementar',
'LBL_BTN_EXP'=>'Exportar',
'LBL_BTN_DELETE'=>'Eliminar',
'LBL_BTN_VIEW_LAYOUTS'=>'Ver Diseños',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Ver diseños móviles',
'LBL_BTN_VIEW_FIELDS'=>'Ver Campos',
'LBL_BTN_VIEW_RELATIONSHIPS'=>'Ver Relaciones',
'LBL_BTN_ADD_RELATIONSHIP'=>'Agregar Relación',
'LBL_BTN_RENAME_MODULE' => 'Cambiar el Nombre del Módulo',
'LBL_BTN_INSERT'=>'Insertar',
//TABS

//ERRORS
'ERROR_ALREADY_EXISTS'=> 'Error: El Campo Ya Existe',
'ERROR_INVALID_KEY_VALUE'=> "Error: Valor de Clave No Válido: [&#39;]",
'ERROR_NO_HISTORY' => 'No se han encontrado archivos en el historial',
'ERROR_MINIMUM_FIELDS' => 'El diseño debe contener al menos un campo',
'ERROR_GENERIC_TITLE' => 'Ha ocurrido un error',
'ERROR_REQUIRED_FIELDS' => '¿Está seguro de que desea continuar? Los siguientes campos requeridos no se encuentran en el diseño:',
'ERROR_ARE_YOU_SURE' => '¿Está seguro de que desea continuar?',

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Los siguientes campos contienen valores calculados que no serán recalculados en tiempo real en la Vista de Edición de SugarCRM Mobile:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Los siguientes campos contienen valores calculados que no se recalcularán en tiempo real en la Vista de Edición del Portal de SugarCRM:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Los siguientes módulos están deshabilitados:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Si desea habilitarlos en el portal, habilítelos <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">aquí</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Configurar el Portal',
    'LBL_PORTAL_THEME' => 'Tema del Portal',
    'LBL_PORTAL_ENABLE' => 'Habilitar',
    'LBL_PORTAL_SITE_URL' => 'Su portal está disponible en:',
    'LBL_PORTAL_APP_NAME' => 'Nombre de la Aplicación',
    'LBL_PORTAL_LOGO_URL' => 'URL del Logotipo',
    'LBL_PORTAL_LIST_NUMBER' => 'Número de registros a mostrar en la lista',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Número de registros a mostrar en la lista de detalles',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Número de registros a mostrar en la Búsqueda Global',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Valor asignado por defecto para nuevos registros del portal',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Diseños de Portal',
'LBL_SYNCP_WELCOME'=>'Introduzca la URL de la instancia de portal que desea actualizar.',
'LBL_SP_UPLOADSTYLE'=>'Seleccione la hoja de estilos a cargar desde su equipo.<br> La hoja de estilos se utilizará en el Portal de Sugar la próxima vez que realice una sincronización.',
'LBL_SP_UPLOADED'=> 'Subido',
'ERROR_SP_UPLOADED'=>'Asegúrese de que está cargando una hoja de estilos CSS.',
'LBL_SP_PREVIEW'=>'Aquí tiene una vista preliminar de la apariencia que tendrá el Portal de Sugar usando la hoja de estilos.',
'LBL_PORTALSITE'=>'URL de Portal de Sugar:',
'LBL_PORTAL_GO'=>'Ir',
'LBL_UP_STYLE_SHEET'=>'Cargar Hoja de Estilos',
'LBL_QUESTION_SUGAR_PORTAL' => 'Seleccione el diseño de Portal de Sugar a editar.',
'LBL_QUESTION_PORTAL' => 'Seleccione el diseño de portal a editar.',
'LBL_SUGAR_PORTAL'=>'Editor del Portal de Sugar',
'LBL_USER_SELECT' => '-- Seleccionar --',

//PORTAL PREVIEW
'LBL_CASES'=>'Casos',
'LBL_NEWSLETTERS'=>'Boletines de Noticias',
'LBL_BUG_TRACKER'=>'Seguimiento de Incidencias',
'LBL_MY_ACCOUNT'=>'Mi Cuenta',
'LBL_LOGOUT'=>'Salir',
'LBL_CREATE_NEW'=>'Crear Nuevo',
'LBL_LOW'=>'Baja',
'LBL_MEDIUM'=>'Media',
'LBL_HIGH'=>'Alta',
'LBL_NUMBER'=>'Número:',
'LBL_PRIORITY'=>'Prioridad:',
'LBL_SUBJECT'=>'Asunto',

//PACKAGE AND MODULE BUILDER
'LBL_PACKAGE_NAME'=>'Nombre del Paquete:',
'LBL_MODULE_NAME'=>'Nombre del Módulo:',
'LBL_MODULE_NAME_SINGULAR' => 'Nombre del Módulo Singular:',
'LBL_AUTHOR'=>'Autor:',
'LBL_DESCRIPTION'=>'Descripción:',
'LBL_KEY'=>'Clave:',
'LBL_ADD_README'=>'Léame',
'LBL_MODULES'=>'Módulos:',
'LBL_LAST_MODIFIED'=>'Última Modificación:',
'LBL_NEW_MODULE'=>'Nuevo Módulo',
'LBL_LABEL'=>'Etiqueta:',
'LBL_LABEL_TITLE'=>'Etiqueta',
'LBL_SINGULAR_LABEL' => 'Etiqueta Individual',
'LBL_WIDTH'=>'Anchura',
'LBL_PACKAGE'=>'Paquete:',
'LBL_TYPE'=>'Tipo:',
'LBL_TEAM_SECURITY'=>'Seguridad de Equipos',
'LBL_ASSIGNABLE'=>'Asignable',
'LBL_PERSON'=>'Persona',
'LBL_COMPANY'=>'Empresa',
'LBL_ISSUE'=>'Incidencia',
'LBL_SALE'=>'Venta',
'LBL_FILE'=>'Archivo',
'LBL_NAV_TAB'=>'Pestaña de Navegación',
'LBL_CREATE'=>'Crear',
'LBL_LIST'=>'Lista',
'LBL_VIEW'=>'Vista',
'LBL_LIST_VIEW'=>'Vista de Lista',
'LBL_HISTORY'=>'Ver Historial',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Restaurar la disposición por defecto',
'LBL_ACTIVITIES'=>'Flujo de actividad',
'LBL_SEARCH'=>'Buscar',
'LBL_NEW'=>'Nuevo',
'LBL_TYPE_BASIC'=>'básica',
'LBL_TYPE_COMPANY'=>'empresa',
'LBL_TYPE_PERSON'=>'persona',
'LBL_TYPE_ISSUE'=>'incidencia',
'LBL_TYPE_SALE'=>'venta',
'LBL_TYPE_FILE'=>'archivo',
'LBL_RSUB'=>'Este es el subpanel que se mostrará en su módulo',
'LBL_MSUB'=>'Este es el subpanel que su módulo proporciona para que sea mostrado por el módulo relacionado',
'LBL_MB_IMPORTABLE'=>'Permitir las importaciones',

// VISIBILITY EDITOR
'LBL_VE_VISIBLE'=>'visible',
'LBL_VE_HIDDEN'=>'oculto',
'LBL_PACKAGE_WAS_DELETED'=>'[[package]] ha sido eliminado',

//EXPORT CUSTOMS
'LBL_EC_TITLE'=>'Exportar Personalizaciones',
'LBL_EC_NAME'=>'Nombre del Paquete:',
'LBL_EC_AUTHOR'=>'Autor:',
'LBL_EC_DESCRIPTION'=>'Descripción:',
'LBL_EC_KEY'=>'Clave:',
'LBL_EC_CHECKERROR'=>'Seleccione un módulo.',
'LBL_EC_CUSTOMFIELD'=>'campos personalizados',
'LBL_EC_CUSTOMLAYOUT'=>'diseños personalizados',
'LBL_EC_CUSTOMDROPDOWN' => 'desplegable(s) personalizados',
'LBL_EC_NOCUSTOM'=>'No se ha personalizado ningún módulo.',
'LBL_EC_EXPORTBTN'=>'Exportar',
'LBL_MODULE_DEPLOYED' => 'El módulo se ha implementado.',
'LBL_UNDEFINED' => 'no definido',
'LBL_EC_CUSTOMLABEL'=>'etiqueta(s) personalizada(s)',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Error al recuperar datos',
'LBL_AJAX_TIME_DEPENDENT' => 'Hay en progreso una acción dependiente del tiempo. Espere e inténtelo de nuevo en unos instantes.',
'LBL_AJAX_LOADING' => 'Cargando...',
'LBL_AJAX_DELETING' => 'Eliminando...',
'LBL_AJAX_BUILDPROGRESS' => 'Construcción En Progreso...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Implementación En Progreso...',
'LBL_AJAX_FIELD_EXISTS' =>'El nombre del campo que ha introducido ya existe. Introduzca un nuevo nombre para el campo.',
//JS
'LBL_JS_REMOVE_PACKAGE' => '¿Está seguro de que desea quitar este paquete? Esto eliminará permanentemente todos los archivos asociados con este paquete.',
'LBL_JS_REMOVE_MODULE' => '¿Está seguro de que desea quitar este módulo? Esto eliminará permanentemente todos los archivos asociados con este módulo.',
'LBL_JS_DEPLOY_PACKAGE' => 'Cualquier personalización que haya realizado en Studio se sobreescribirá cuando este módulo se implemente de nuevo. ¿Está seguro de que desea proceder?',

'LBL_DEPLOY_IN_PROGRESS' => 'Implementando Paquete',
'LBL_JS_VALIDATE_NAME'=>'Nombre - Debe comenzar por una letra y estar formado por letras, números y guiones bajos. No se pueden utilizar espacios ni otros caracteres especiales.',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'La clave del paquete ya existe',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'El Nombre del Paquete ya existe',
'LBL_JS_PACKAGE_NAME'=>'Nombre del paquete – Debe comenzar por una letra y estar formado por letras, números y guiones bajos. No se pueden utilizar espacios ni otros caracteres especiales.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Clave: debe ser alfanumérica y empezar por una letra.',
'LBL_JS_VALIDATE_KEY'=>'Clave: debe ser alfanumérica, empezar por una letra y no contener espacios.',
'LBL_JS_VALIDATE_LABEL'=>'Introduzca la etiqueta que se utilizará como Nombre Visible de este módulo',
'LBL_JS_VALIDATE_TYPE'=>'Seleccione el tipo de módulo que quiere construir de la lista anterior',
'LBL_JS_VALIDATE_REL_NAME'=>'Nombre: debe ser alfanumérico y sin espacios',
'LBL_JS_VALIDATE_REL_LABEL'=>'Etiqueta: agregue la etiqueta que se mostrará sobre el subpanel',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => '¿Está seguro de que desea eliminar este elemento necesario de la lista desplegable? Esto puede afectar la funcionalidad de la aplicación.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => '¿Está seguro de que desea eliminar este elemento de la lista desplegable? Eliminar el estado Ganado o Perdido puede hacer que el módulo de Previsión de Ventas no funcione adecuadamente',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => '¿Está seguro de que quiere eliminar el estado de Nueva Venta? Esto puede puede provocar problemas en el workflow de Líneas de Ingreso en el módulo Oportunidades.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => '¿Está seguro de que quiere eliminar el estado de Venta en Progreso? Borrar este estado puede provocar problemas en el workflow de Líneas de Ingreso en el módulo Oportunidades.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => '¿Está seguro de que desea eliminar el estado Ganado? Eliminar este estado puede provocar que el módulo de Previsión de Ventas no funcione adecuadamente',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => '¿Está seguro de que desea eliminar el estado Perdido? Eliminar este estado puede provocar que el módulo de Previsión de Ventas no funcione adecuadamente',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'¿Está seguro de que desea eliminar esta relación?<br>Nota: Es posible que esta operación tarde unos minutos en completarse.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Esto hará la relación permanente. ¿Está seguro de que desea implementar esta relación?',
'LBL_CONFIRM_DONT_SAVE' => 'Se han hecho cambios desde que guardó por última vez, ¿desea guardarlos?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => '¿Guardar Cambios?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Es posible que los datos se trunquen y ésto no podrá deshacerse, ¿está seguro de que desea continuar?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Seleccione el tipo de datos apropiado acorde con el tipo de datos que será introducido en el campo.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Configurar el campo como texto de búsqueda completo.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Impulsar es el proceso de mejorar la pertinencia de los campos de un registro. <br /> Los campos con un nivel de impulso más alto recibirán mayor peso cuando se realiza la búsqueda. Cuando se realiza una búsqueda, la coincidencia de registros que contienen campos con un mayor peso aparecerá más arriba en los resultados de la búsqueda. <br/> El valor por defecto es 1.0 que representa un estímulo neutro. Para aplicar un impulso positivo se acepta cualquier valor flotante superior a 1. Para aplicar un impulso negativo utilice valores inferiores a 1. Por ejemplo, un valor de 1,35 impulsará positivamente un campo en un 135 %. Con un valor de 0,60 se aplicará un impulso negativo. <br />Tenga en cuenta que en las versiones anteriores era necesario realizar un reindexado de texto de completo. Esto ya no es necesario.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Sí</b>: El campo se incluirá en una operación de importación.<br><b>No</b>: El campo no se incluirá en ninguna importación.<br><b>Requerido</b>: Debe de suministrarse un valor para el campo en toda importación.',
'LBL_POPHELP_PII'=>'Este campo se marcará automáticamente para realizar una auditoría y estará disponible en la visualización de Información personal.<br>Los campos de información personal se pueden borrar permanentemente cuando el registro esté relacionado con una solicitud de borrado por privacidad de datos.<br>La eliminación se realiza mediante el módulo de Privacidad de datos, y la pueden ejecutar los administradores o los usuarios con el rol de Administrador de privacidad de datos.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Introduzca un número para la Anchura, como medida en píxeles.<br> La escala de la imagen cargada se ajustará a esta Anchura.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Introduzca un número para la Altura, como medida en píxeles.<br> La escala de la imagen cargada se ajustará a esta Altura.',
'LBL_POPHELP_DUPLICATE_MERGE'=>'<b>Enabled</b>: The field will appear in the Merge Duplicates feature, but will not be available to use for the filter conditions in the Find Duplicates feature.<br><b>Disabled</b>: The field will not appear in the Merge Duplicates feature, and will not be available to use for the filter conditions in the Find Duplicates feature.'
. '<br><b>In Filter</b>: The field will appear in the Merge Duplicates feature, and will also be available in the Find Duplicates feature.<br><b>Filter Only</b>: The field will not appear in the Merge Duplicates feature, but will be available in the Find Duplicates feature.<br><b>Default Selected Filter</b>: The field will be used for a filter condition by default in the Find Duplicates page, and will also appear in the Merge Duplicates feature.'
,
'LBL_POPHELP_CALCULATED'=>"Create a formula to determine the value in this field.<br>"
   . "Workflow definitions containing an action that are set to update this field will no longer execute the action.<br>"
   . "Fields using formulas will not be calculated in real-time in "
   . "the Sugar Self-Service Portal or "
   . "Mobile EditView layouts.",

'LBL_POPHELP_DEPENDENT'=>"Create a formula to determine whether this field is visible in layouts.<br/>"
        . "Dependent fields will follow the dependency formula in the browser-based mobile view, <br/>"
        . "but will not follow the formula in the native applications, such as Sugar Mobile for iPhone. <br/>"
        . "They will not follow the formula in the Sugar Self-Service Portal.",
'LBL_POPHELP_GLOBAL_SEARCH'=>'Seleccione esta opción para utilizar este campo en la búsqueda de registros desde la Búsqueda Global en este módulo.',
//Revert Module labels
'LBL_RESET' => 'Restablecer',
'LBL_RESET_MODULE' => 'Restablecer Módulo',
'LBL_REMOVE_CUSTOM' => 'Quitar Personalizaciones',
'LBL_CLEAR_RELATIONSHIPS' => 'Eliminar Relaciones',
'LBL_RESET_LABELS' => 'Restablecer Etiquetas',
'LBL_RESET_LAYOUTS' => 'Restablecer diseños',
'LBL_REMOVE_FIELDS' => 'Quitar Campos Personalizados',
'LBL_CLEAR_EXTENSIONS' => 'Eliminar Extensiones',

'LBL_HISTORY_TIMESTAMP' => 'Fecha y hora',
'LBL_HISTORY_TITLE' => 'historial',

'fieldTypes' => array(
                'varchar'=>'Campo de Texto',
                'int'=>'Entero',
                'float'=>'Coma flotante',
                'bool'=>'Casilla de Verificación',
                'enum'=>'Desplegable',
                'multienum' => 'Selección Múltiple',
                'date'=>'Fecha',
                'phone' => 'Teléfono',
                'currency' => 'Moneda',
                'html' => 'HTML',
                'radioenum' => 'Radio',
                'relate' => 'Relacionar',
                'address' => 'Dirección',
                'text' => 'Área de Texto',
                'url' => 'URL',
                'iframe' => 'IFrame',
                'image' => 'Imagen',
                'encrypt'=>'Encriptado',
                'datetimecombo' =>'Fecha y hora',
                'decimal'=>'Decimal',
),
'labelTypes' => array(
    "" => "Etiquetas de uso frecuente",
    "all" => "Todas las etiquetas",
),

'parent' => 'Relación Flex',

'LBL_ILLEGAL_FIELD_VALUE' =>"Las claves de un desplegable no pueden contener comillas.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Está seleccionando este elemento para su eliminación de la lista desplegable. Cualquier campo desplegable que use esta lista con este elemento como valor ya no mostrará dicho valor, y el valor ya no podrá ser seleccionado en los campos desplegables. ¿Está seguro de que desea continuar?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Todos los Módulos',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (relacionado {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Copiar del diseño',
);
