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
    'LBL_ROLE' => 'Rol',
'help'=>array(
    'package'=>array(
            'create'=>'Proporcione un <b> nombre </b> para el paquete. El nombre debe comenzar con una letra y solo puede contener letras, números y guiones bajos. No se pueden usar caracteres especiales y no debe contener espacios. (Ejemplo: HR_Management) <br/><br/> Puede proporcionar <b> Autor </b> y <b>Descripción </b> como información para el paquete. <br/><br/> Haga clic en <b>Guardar</b> para crear el paquete.',
            'modify'=>'Las propiedades y posibles acciones para el <b>Paquete</b> aparecerán aquí.<br><br>Puede modificar el <b>Nombre</b>, <b>Autor</b> y <b>Descripción</b> del paquete, así como ver y personalizar todos los módulos contenidos en el paquete. <br><br> Haga clic en <b>Nuevo Módulo</b> para crear un módulo para el paquete.<br><br> Si el paquete contiene al menos un módulo, usted puede <b>Publicar</b> y <b>Desplegar</b> el paquete, así como <b>Exportar</b> las personalizaciones realizadas en el paquete.',
            'name'=>'Este es el <b>Nombre</b> del paquete actual. <br/><br/> El nombre debe comenzar con una letra y solo puede contener letras, números y guiones bajos. No deben utilizarse caracteres especiales y no debe contener espacios. (Ejemplo: HR_Management)',
            'author'=>'Este es el <b> Autor </b> que se muestra durante la instalación como el nombre de la entidad que creó el paquete. <br><br> El autor podría ser un individuo o una empresa.',
            'description'=>'Esta es la <b>Descripción</b> del paquete que se muestra durante la instalación.',
            'publishbtn'=>'Haga clic en <b>Publicar</b> para guardar todos los datos introducidos y crear un archivo .zip que es una versión instalable del paquete.<br><br> Use el <b>Cargador de Módulos</b> para cargar el archivo .zip e instalar el paquete.',
            'deploybtn'=>'Haga clic en <b>Desplegar</b> para guardar todos los datos introducidos e instalar el paquete, incluyendo todos los módulos, en la instancia actual.',
            'duplicatebtn'=>'Haga clic en <b>Duplicar </b> para copiar el contenido del paquete en un paquete nuevo y mostrar el nuevo paquete. <br/> <br/> Para el nuevo paquete, se genera un nuevo nombre automáticamente añadiendo un número al final del nombre del paquete utilizado para duplicarlo. Puede cambiar el nombre del nuevo paquete introduciendo un <b>Nombre</b> nuevo y después haciendo clic en <b>Guardar</b>.',
            'exportbtn'=>'Haga clic en <b>Exportar</b> para crear un archivo .zip que contiene las personalizaciones realizadas en el paquete. <br><br> El archivo generado no es una versión instalable del paquete. <br><br> Use el <b >Cargador de módulos</b> para importar el archivo .zip y para que el paquete, incluidas las personalizaciones, aparezca en el Generador de módulos.',
            'deletebtn'=>'Haga clic en <b> Eliminar </b> para eliminar este paquete y todos los archivos relacionados con este paquete.',
            'savebtn'=>'Haga clic en <b>Guardar</b> para guardar todos los datos relacionados con el paquete.',
            'existing_module'=>'Haga clic en el icono del <b>Módulo</b> para editar las propiedades y personalizar los campos, las relaciones y los diseños asociados con el módulo.',
            'new_module'=>'Haga clic en <b>Nuevo Módulo</b> para crear un nuevo módulo para este paquete.',
            'key'=>'Esta <b>Clave </b> de 5 letras, alfanumérica se usa como prefijo para todos los directorios, los nombres de clases y las tablas de base de datos para todos los módulos en el paquete actual. <br><br> La clave se utiliza para lograr la singularidad de los nombres de las tablas.',
            'readme'=>'Haga clic para añadir el texto <b>Léame</b> para este paquete. <br><br> El Léame estará disponible en el momento de la instalación.',

),
    'main'=>array(

    ),
    'module'=>array(
        'create'=>'Proporcione un <b>Nombre</b> para el módulo. La <b>Etiqueta</b> que proporcione aparecerá en la pestaña de navegación. <br/><br/> Elija si desea ver una pestaña de navegación para el módulo, para ello, seleccione la casilla de <b> Pestaña de navegación</b>. <br/><br/> Revise la casilla de <b>Seguridad del Equipo</b> para tener un campo de selección del Equipo dentro de los registros del módulo. <br/><br/> A continuación, seleccione el tipo de módulo que desea crear. <br/><br/> Seleccione un tipo de plantilla. Cada plantilla contiene un conjunto específico de campos, así como los diseños pre-definidos, para usar como base de su módulo. <br/><br/> Haga clic en <b>Guardar </b> para crear el módulo.',
        'modify'=>'Puede modificar las propiedades del módulo y personalizar los <b>Campos</b>, <b>Relaciones</b> y <b>Diseños</b> relacionados con el módulo.',
        'importable'=>'Compruebe la casilla de <b> Importables </b> la cual permitirá la importación de datos sobre este módulo. <br><br> Un enlace para el Asistente de importación aparecerá en el panel de accesos directos en el módulo. El Asistente de importación facilita la importación de datos de fuentes externas en el módulo personalizado.',
        'team_security'=>'Verificar la casilla de <b>Seguridad del equipo </b> le permitirá habilitar la seguridad del equipo para este módulo. <br/><br/> Si la seguridad del equipo está activada, el campo de selección del equipo aparecerá dentro de los registros en el módulo ',
        'reportable'=>'Al marcar esta casilla se podrán realizar informes de este módulo.',
        'assignable'=>'Al marcar esta casilla permitirá asignar a un usuario seleccionado un registro del módulo.',
        'has_tab'=>'Al comprobar la <b> Pestaña de Navegación </b> proporcionará una pestaña de navegación para el módulo.',
        'acl'=>'Al marcar esta casilla habilitará los controles de acceso en este módulo, incluyendo la seguridad en el nivel de campo.',
        'studio'=>'Al marcar esta casilla le permitirá a los administradores personalizar este módulo dentro de Studio.',
        'audit'=>'Al marcar esta casilla habilitará la Auditoría de este módulo. Los cambios en ciertos campos se registrarán de modo que los administradores pueden revisar el historial de cambios.',
        'viewfieldsbtn'=>'Haga clic en <b> Ver Campos </b> para ver los campos asociados con el módulo y para crear y editar los campos personalizados.',
        'viewrelsbtn'=>'Haga clic en <b> Ver Relaciones </b> para ver las relaciones asociadas a este módulo y crear nuevas relaciones.',
        'viewlayoutsbtn'=>'Haga clic en <b> Ver Diseños </b> para ver los diseños para el módulo y para personalizar la organización de campo dentro de los diseños.',
        'viewmobilelayoutsbtn' => 'Haga clic en <b>Observar Diseños Móviles</b> para ver diseños móviles para el módulo y personalizar el campo de acuerdo a los diseños.',
        'duplicatebtn'=>'Haga clic en <b> Duplicados </b> para copiar las propiedades del módulo en un nuevo módulo y para mostrar el nuevo módulo. <br/><br/> Para el nuevo módulo, se generará un nuevo nombre automáticamente y se añadirá un número al final del nombre del módulo utilizado para crearlo.',
        'deletebtn'=>'Haga clic en <b> Eliminar </b> para eliminar este módulo.',
        'name'=>'Este es el <b> Nombre </b> del módulo actual. <br/><br/> El nombre debe ser alfanumérico y debe comenzar con una letra y sin espacios. (Ejemplo: HR_Management)',
        'label'=>'Esta es la <b> Etiqueta </b> que aparecerá en la pestaña de navegación para el módulo. ',
        'savebtn'=>'Haga clic en <b> Guardar </b> para guardar todos los datos introducidos en relación con el módulo.',
        'type_basic'=>'La plantilla <b> Básica </b> cuenta con campos básicos, como el Nombre, Asignado a, Equipo, Fecha de creación y los campos de Descripción.',
        'type_company'=>'El tipo de plantilla de la <b>Empresa</b> dispone de campos específicos a la organización, como por ejemplo Nombre de la Empresa, Sector, Dirección Fiscal. <br/><br />Utilice esta plantilla para crear módulos que son similares a los módulos estándares de Cuentas.',
        'type_issue'=>'El tipo de plantilla de <b>Problemas</b> disponde de campos específicos a incidencias y casos, como por ejemplo, Número, Estado, Prioridad, Descripción. <br /><br/>Utilice esta plantilla para crear módulos que sean similares a los módulos estándares de Incidencias y Seguimiento de Casos.',
        'type_person'=>'El tipo de plantilla de <b>Persona</b> disponde de campos específicos para las personas, como por ejemplo, Saludo, Título, Nombre, Dirección, Número de Teléfono. <br /><br />Utilice esta plantilla para crear módulos que sean similares a los módulos estándares de Contactos y Clientes Potenciales.',
        'type_sale'=>'El tipo de plantilla de <b>Venta</b> dispone de campos específicos a las oportunidades, como por ejemplo, Origen del Potencial, Fase, Cantidad y Probabilidad.<br /><br />Utilice esta plantilla para crear módulos que sean similares a los módulos estándares de Oportunidades.',
        'type_file'=>'El tipo de plantilla de <b>Archivo</b> dispone de campos específicos de Documentos, como por ejemplo, Nombre del Archivo, Tipo de Documento y Fecha de Publicación. <br><br>Utilice esta plantilla para crear módulos que sean similares a los módulos estándares de Documentos.',

    ),
    'dropdowns'=>array(
        'default' => 'Todos los <b>Despligues</b> para la aplicación se listan aquí.<br><br>Se pueden usar las listas desplegables para campos en cualquier módulo.<br><br> Para realizar cambios en una lista desplegable existente, haga clic en el nombre de la lista.<br><br>Haga clic en <b> Añadir Lista Desplegable</b> para crear una nueva.',
        'editdropdown'=>'Las Listas desplegables se pueden utilizar en los campos estándares o personalizados desplegables en cualquier módulo.<br><br> Proporcionar un <b>Nombre</b> para la lista desplegable.<br><br> Si se instala algún paquete de idioma en la aplicación, se puede seleccionar el<b>Idioma</b> que se utilizará para los elementos de la lista.<br><br> en el campo del <b>Nombre del elemento </b>, proporcione un nombre para la opción de la lista desplegable. Este nombre no aparecerá en la lista desplegable que es visible para los usuarios.<br><br>En el campo <b>Mostrar etiqueta</b>, proporcione una etiqueta que será visible para los usuarios.<br><br> Después de proporcionar el nombre del elemento y la etiqueta de visualización, haga clic en <b>Añadir</b>para añadir el elemento a la lista desplegable.<br><br> Para volver a ordenar los elementos de la lista, arrastrar y soltar elementos en las posiciones deseadas.<br><br>Para editar la etiqueta visible de un elemento haga clic en el<b>icono Edición</b> e ingrese una nueva etiqueta. Para eliminar un elemento de la lista desplegable, haga clic en el <b> Ícono Eliminar</b>.<br><br> Para deshacer un cambio realizado en una etiqueta de visualización, haga clic en <b>Deshacer</b>. Para rehacer un cambio que se ha deshecho, haga clic en<b>Rehacer</b>.<br><br> Haga clic en <b>Guardar</b> para guardar la lista desplegable.',

    ),
    'subPanelEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Subpanel</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the Subpanel.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Haga clic en <b>Guardar y Desplegar</b> para guardar los cambios que ha realizado y para que se activen en el módulo.',
        'historyBtn'=> 'Haga clic en <b>Ver Historial</b> para ver y restaurar un diseño previamente guardado del historial.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar Diseño Predeterminado</b> para restablecer una vista a su diseño original.',
        'Hidden' 	=> 'Los campos <b>Oculto</b> no aparecen en el subpanel.',
        'Default'	=> 'Los campos <b>Por defecto</b> aparecen en el subpanel.',

    ),
    'listViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Available</b> column contains fields that a user can select in the Search to create a custom ListView. <br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Haga clic en <b>Guardar y Desplegar</b> para guardar los cambios que ha realizado y para que se activen en el módulo.',
        'historyBtn'=> 'Haga clic en<b>Ver Historial</b> para ver y restaurar un diseño guardado previamente en el historial.<br><br><b>Restaurar</b> dentro de <b>Ver Historial</b> restaura la colocación del campo dentro de diseños guardados previamente. Para cambiar las etiquetas de campo, haga clic en el icono Editar situado junto a cada campo.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar diseño predeterminado</b> para restablecer una vista a su diseño original. <br><br><b>Restaurar el diseño predeterminado</b> solo restaura la ubicación del campo dentro del diseño original. Para cambiar las etiquetas de campo, haga clic en el icono Editar junto a cada campo.',
        'Hidden' 	=> 'Campos<b>Ocultos</b> que no están disponibles actualmente para que los usuarios loS vean en Listviews.',
        'Available' => 'Los campos<b>Disponibes</b> no se muestran por defecto, pero se pueden agregar a las Vistas de Lista por los usuarios.',
        'Default'	=> 'Los campos<b>Por defecto</b>aparecen en las Vistas de Lista que no son personalizadas por usuarios.'
    ),
    'popupListViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>ListView</b> appear here.<br><br>The <b>Default</b> column contains the fields that are displayed in the ListView by default.<br/><br/>The <b>Hidden</b> column contains fields that can be added to the Default or Available column.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    ,
        'savebtn'	=> 'Haga clic en <b>Guardar y Desplegar</b> para guardar los cambios que ha realizado y para que se activen en el módulo.',
        'historyBtn'=> 'Haga clic en<b>Ver Historial</b> para ver y restaurar un diseño guardado previamente en el historial.<br><br><b>Restaurar</b> dentro de <b>Ver Historial</b> restaura la colocación del campo dentro de diseños guardados previamente. Para cambiar las etiquetas de campo, haga clic en el icono Editar situado junto a cada campo.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar diseño predeterminado</b> para restablecer una vista a su diseño original. <br><br><b>Restaurar el diseño predeterminado</b> solo restaura la ubicación del campo dentro del diseño original. Para cambiar las etiquetas de campo, haga clic en el icono Editar junto a cada campo.',
        'Hidden' 	=> 'Campos<b>Ocultos</b> que no están disponibles actualmente para que los usuarios loS vean en Listviews.',
        'Default'	=> 'Los campos<b>Por defecto</b>aparecen en las Vistas de Lista que no son personalizadas por usuarios.'
    ),
    'searchViewEditor'=>array(
        'modify'	=> 'All of the fields that can be displayed in the <b>Search</b> form appear here.<br><br>The <b>Default</b> column contains the fields that will be displayed in the Search form.<br/><br/>The <b>Hidden</b> column contains fields available for you as an admin to add to the Search form.'
    . '<br/><br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_dependent.png"/>Indicates a Dependent field that may or may not be visible based on the value of a formula.<br/><!--not_in_theme!--><img src="themes/default/images/SugarLogic/icon_calculated.png" /> Indicates a Calculated field whose value will be automatically determined based on a formula.'
    . '<br/><br/>This configuration applies to popup search layout in legacy modules only.',
        'savebtn'	=> 'Haga clic en <b>Guardar y Desplegar</b> para guardar los cambios y hacerlos activos',
        'Hidden' 	=> 'Los campos<b>Ocultos</b> no aparecen en el la Búsqueda.',
        'historyBtn'=> 'Haga clic en<b>Ver Historial</b> para ver y restaurar un diseño guardado previamente en el historial.<br><br><b>Restaurar</b> dentro de <b>Ver Historial</b> restaura la colocación del campo dentro de diseños guardados previamente. Para cambiar las etiquetas de campo, haga clic en el icono Editar situado junto a cada campo.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar diseño predeterminado</b> para restablecer una vista a su diseño original. <br><br><b>Restaurar el diseño predeterminado</b> solo restaura la ubicación del campo dentro del diseño original. Para cambiar las etiquetas de campo, haga clic en el icono Editar junto a cada campo.',
        'Default'	=> 'Los campos<b>Por defecto</b> aparecen en la Búsqueda.'
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
        'saveBtn'	=> 'Haga clic en <b>Guardar</b>para preservar los cambios realizados en el diseño desde la última vez que lo guardó.<br><br>Los cambios no se mostrarán en el módulo hasta que no se desplieguen los cambios guardados.',
        'historyBtn'=> 'Haga clic en<b>Ver Historial</b> para ver y restaurar un diseño guardado previamente en el historial.<br><br><b>Restaurar</b> dentro de <b>Ver Historial</b> restaura la colocación del campo dentro de diseños guardados previamente. Para cambiar las etiquetas de campo, haga clic en el icono Editar situado junto a cada campo.',
        'historyRestoreDefaultLayout'=> 'Haga clic en <b>Restaurar diseño predeterminado</b> para restablecer una vista a su diseño original. <br><br><b>Restaurar el diseño predeterminado</b> solo restaura la ubicación del campo dentro del diseño original. Para cambiar las etiquetas de campo, haga clic en el icono Editar junto a cada campo.',
        'publishBtn'=> 'Haga clic en <b>Guardar y Desplegar</b> para guardar todos los cambios realizados en el diseño desde la última vez que lo guardó, y para activar los cambios en el módulo. <br><br>El diseño se mostrará de forma inmediata en el módulo.',
        'toolbox'	=> 'La <b>Caja de Herramientas</b> contiene la <b>Papelera de Reciclaje</b>, elementos de diseño adicionales y el conjunto de campos disponibles para añadir al diseño.<br/><br/>Los elementos de diseño y los campos en el cuadro de herramientas se pueden arrastrar y soltar en el diseño y los elementos de diseño, y los campos se pueden arrastrar y soltar desde el diseño en el cuadro de herramientas.<br><br>Los elementos de diseño son <b>Paneles</b> y <b>Filas</b>. Agregar una fila nueva o un panel nuevo para el diseño proporciona ubicaciones adicionales en el diseño de los campos.<br/><br/> Arrastre y suelte cualquiera de los campos en el cuadro de herramientas o el diseño en una posición de campo ocupado para cambiar las ubicaciones de los dos campos.<br/><br/>El campo de <b>Relleno</b> crea un espacio en blanco en el diseño en el que se coloca.',
        'panels'	=> 'El área de <b>Diseño</b> proporciona una vista del aspecto que tendrá la presentación dentro del módulo cuando se implementen los cambios realizados en el diseño.<br/><br/>Puede cambiar la posición de los campos, filas y paneles arrastrándolos y soltándolos en el lugar deseado.<br/><br/>Elimine los elementos arrastrándolos y soltándolos en la <b>Papelera de Reciclaje</b>en la Caja de herramientas, o añada nuevos elementos y campos arrastrándolos desde la <b>Caja de Herramientas</b> y colocándolos en el lugar deseado del diseño.',
        'delete'	=> 'Arrastre y suelte cualquier elemento aquí para eliminarlo del diseño',
        'property'	=> 'Edite la <b>Etiqueta</b> que se muestra para este campo. <br> <br><b> Ancho </b> proporciona un valor de ancho en píxeles para los módulos Sidecar y como un porcentaje del ancho de tabla para módulos de compatibilidad con versiones anteriores.',
    ),
    'fieldsEditor'=>array(
        'default'	=> 'Los <b>Campos</b> que están disponibles para el módulo se enumeran aquí por Nombre de campo.<br><br>Los campos personalizados creados para el módulo aparecen arriba de los campos que están disponibles para el módulo por defecto.<br><br>Para editar un campo, haga clic en el<b>Nombre de Campo</b>.<br/><br/>Para crear un nuevo campo, haga clic en <b>Agregar Campo</b>.',
        'mbDefault'=>'Los <b>Campos</b> que están disponibles para el módulo se enumeran aquí por Nombre de campo.<br><br> Para configurar las propiedades de un campo, haga clic en el Nombre de campo. <br><br>>Para crear un nuevo campo, haga clic en <b>Agregar Campo</b>. La etiqueta junto con las otras propiedades del campo nuevo se pueden editar después de la creación haciendo clic en el nombre de campo.<br><br>Después de desplegar el módulo, los nuevos campos creados en el Generador de Módulos se consideran campos estándar en el módulo desplegado en Studio.',
        'addField'	=> 'Seleccionar <b>Tipo de Datos</b> para el campo nuevo. El tipo que seleccione determinará qué tipo de caracteres se podrán introducir para el campo. Por ejemplo, sólo los números enteros se pueden introducir en los campos que son de datos enteros.<br><br> Proporcione un <b>Nombre</b> para el campo. El nombre debe ser alfanumérico y no debe contener espacios. Los guiones bajos son válidos.<br><br> La <b>Etiqueta Visible</b>es la etiqueta que aparecerá para los campos en los diseños de los módulos.  La <b>Etiqueta del Sistema</b> se utiliza para hacer referencia al campo en el código.<br><br> Dependiendo del tipo de datos seleccionado para el campo, se pueden establecer algunas o todas de las siguientes propiedades para el campo:<br><br> <b>Texto de Ayuda</b> aparece temporalmente mientras un usuario se desplaza sobre el campo, y se puede utilizar para solicitar al usuario el tipo de entrada que desee.<br><br> <b>Comentario de texto</b> sólo se ve dentro de Studio y/o Generador de Módulos, y se puede utilizar para describir el campo para los administradores.<br><br> <b>Valor Predeterminado</b> aparecerá en el campo. Los usuarios pueden introducir un nuevo valor en el campo o utilizar el valor por defecto.<br><br> Seleccione la casilla de verificación de <b>Actualización Masiva</b> con el fin de poder utilizar la función de actualización masiva para el campo.<br><br> El valor de <b>Tamaño Máximo</b>determina el número máximo de caracteres que se pueden introducir en el campo.<br><br> Seleccione la casilla de verificación de <b>Campo Requerido</b> con el fin de hacer que el campo sea necesario. Se debe proporcionar un valor para el campo para poder guardar un registro que contenga el campo.<br><br> Seleccione la casilla de verificación <b>Notificable</b>para permitir que el campo que sea utilizado para los filtros y para la visualización de los datos en los Informes.<br><br> Seleccione la casilla de verificación de <b>Auditoría</b>para poder realizar un seguimiento de los cambios en el campo en el Registro de cambios.<br><br>Seleccione una opción en el campo de <b>Importación</b>para permitir, prohibir o exigir que el campo sea importado en el Asistente de importación.<br><br>Seleccione una opción en el campo de <b>Combinación de Duplicados</b>para activar o desactivar la Combinación de Duplicados y las características Encontrar Duplicados.<br><br> Se pueden establecer propiedades adicionales para ciertos tipos de datos.',
        'editField' => 'Las propiedades de este campo se pueden personalizar.<br><br>Haga clic en <b>Clonar</b> para crear un nuevo campo con las mismas propiedades.',
        'mbeditField' => 'La <b>Etiqueta de Visualización</b> de una plantilla de campo se puede personalizar. Las otras propiedades del campo no se pueden personalizar.<br><br>Haga clic en <b>Clonar</b> para crear un nuevo campo con las mismas propiedades.<br><br> Para eliminar un plantilla de campo para que no se muestre en el módulo, elimine el campo de los <b>Diseños</b> apropiados.'

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Personalizaciones de exportación realizadas en Studio mediante la creación de paquetes que se pueden cargar en otra instancia de Sugar a través del <b>Cargador de Módulos</b>.<br><br>Primero, proporcione un <b>Nombre de Paquete</b>. Puede Proporcionar el <b>Autor</b> y la <b>Descripción</b> como también la información del paquete.<br><br>Seleccione el módulo(s)que contiene las personalizaciones que desea exportar. Se mostrarán sólo los módulos que contienen personalizaciones para que usted los seleccione.<br><br>Luego haga clic en <b>Exportar</b>para crear un archivo zip. para el paquete que contiene las personalizaciones.',
        'exportCustomBtn'=>'Haga clic en <b>Exportar</b>para crear un archivo .zip para el paquete que contiene las personalizaciones que desea exportar.',
        'name'=>'Este es el <b>Nombre</b> del paquete. Este nombre se mostrará durante la instalación.',
        'author'=>'Este es el <b> Autor </b> que se muestra durante la instalación como el nombre de la entidad que creó el paquete. El autor puede ser un individuo o una empresa.',
        'description'=>'Esta es la <b>Descripción</b> del paquete que se muestra durante la instalación.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bienvenidos al área de <b>Herramientas para Desarrolladores</b>. <br/><br/> Utilice las herramientas dentro de esta área para crear y administrar módulos y campos estándares y personalizados.',
        'studioBtn'	=> 'Utilice <b>Studio</b> para personalizar los módulos desplegados.',
        'mbBtn'		=> 'Use el <b>Generador de módulos</b> para crear nuevos módulos.',
        'sugarPortalBtn' => 'Utilice el <b>Editor del Portal de Sugar</b> para administrar y personalizar el Portal de Sugar.',
        'dropDownEditorBtn' => 'Utilice el <b>Editor desplegable</b> para añadir y editar listas desplegables globales para campos desplegables.',
        'appBtn' 	=> 'Utilice el modo de aplicación para personalizar las propiedades del programa, por ejemplo, cuántos informes TPS se muestran en la página de inicio',
        'backBtn'	=> 'Volver al paso anterior.',
        'studioHelp'=> 'Utilice <b>Studio</b> para determinar qué y cómo se muestra la información en los módulos.',
        'studioBCHelp' => 'indica que el módulo es un módulo compatible con versiones anteriores',
        'moduleBtn'	=> 'Haga clic para editar este módulo.',
        'moduleHelp'=> 'Los componentes que se pueden personalizar para el módulo aparecen aquí.<br><br>Haga clic en un icono para seleccionar el componente que desea editar.',
        'fieldsBtn'	=> 'Crear y personalizar los <b>Campos</b> para almacenar la información en el módulo.',
        'labelsBtn' => 'Editar las <b> Etiquetas</b> para mostrar los campos y otros títulos de este módulo.'	,
        'relationshipsBtn' => 'Añadir o visualizar <b>Relaciones</b> existentes para el módulo.' ,
        'layoutsBtn'=> 'Personalice el módulo <b>Diseños</b>. Los Diseños son las diferentes vistas del módulo que contiene los campos.<br><br> Puede determinar qué campos aparecen y cómo se organizan en cada presentación.',
        'subpanelBtn'=> 'Determine qué campos aparecen en los módulo de <b>Subpaneles</b>.',
        'portalBtn' =>'Personalice el módulo <b>Diseños</b> que aparece en el<b>Portal de Sugar</b>.',
        'layoutsHelp'=> 'El módulo <b>Diseños</b> que puede ser personalizado aparece aquí.<br><br>Los diseños muestran los campos y los datos de campo.<br><br> Haga clic en un icono para seleccionar el diseño que desea editar.',
        'subpanelHelp'=> 'Los <b>Subpaneles</b> del módulo que pueden ser personalizados aparecen aquí.<br><br> Haga clic en un icono para seleccionar el módulo que desea editar.',
        'newPackage'=>'Haga clic en Nuevo paquete para crear un nuevo paquete.',
        'exportBtn' => 'Haga clic en <b> Exportar personalizaciones </b> para crear y descargar un paquete que contenga las personalizaciones realizadas en Studio para los módulos específicos.',
        'mbHelp'    => 'Utilice <b>Generador de Módulos</b> para crear paquetes que contengan módulos personalizados basados ​​en objetos estándar o personalizados.',
        'viewBtnEditView' => 'Personalizar el diseño del módulo <b>Editar Vista</b>.<br><br> El formulario de Editar Vista contiene campos de entrada para capturar los datos introducidos por el usuario.',
        'viewBtnDetailView' => 'Personalizar el diseño del módulo <b>Vista de Detalles</b>.<br><br> La Vista de Detalles muestra datos de campo introducidos por el usuario.',
        'viewBtnDashlet' => 'Personalizar el <b>Sugar Dashlet</b> del módulo, incluida la Vista de Lista de Sugar Dashlet y el Buscador.<br><br> Sugar Dashlet estará disponible para ser agregado a las páginas en el módulo Inicio.',
        'viewBtnListView' => 'Personalizar el diseño de la <b>Vista de Lista</b> del módulo. <br><br> Los resultados de la búsqueda aparecen en la Vista de Lista.',
        'searchBtn' => 'Personalizar el módulo de<b>Vista de Lista</b> de la Presentación.<br><br>Determine qué campos se puede utilizar para filtrar registros que aparecen en la Vista de Lista.',
        'viewBtnQuickCreate' =>  'Personalizar diseño del módulo <b>Creación Rápida</b>.<br><br>El formulario Creación Rápida aparece en subpaneles y en el módulo de mensajes de correo electrónico.',

        'searchHelp'=> 'Los Formularios de <b>Búsqueda</b> que se pueden personalizar aparecen aquí.<br><br> Los formularios de Búsqueda contienen campos para filtrar registros.<br><br> Haga clic en un icono para seleccionar el diseño de búsqueda para editar.',
        'dashletHelp' =>'Los diseños de <b>Sugar Dashlet</b>que se pueden personalizar aparecen aquí.<br><br> Sugar Dashlet estará disponible para agregar a las páginas en el módulo de Inicio.',
        'DashletListViewBtn' =>'Los diseños de <b>Vista de Lista de Sugar Dashlet </b> muestran registros en función de los filtros de búsqueda de Sugar Dashlet.',
        'DashletSearchViewBtn' =>'La <b>Búsqueda de Sugar Dashlet</b> filtra los registros de la vista de lista de Sugar Dashlet.',
        'popupHelp' =>'Los diseños de <b>Ventana emergente</b> que pueden personalizarse aparecen aquí.<br>',
        'PopupListViewBtn' => 'El diseño de <b>Vista de Lista de Ventana emergente</b> se utiliza para ver una lista de registros al seleccionar uno o más registros para relacionarlos con el registro actual.',
        'PopupSearchViewBtn' => 'El diseño de <b>Búsqueda de Ventana emergente</b> les permite a los usuarios buscar registros para relacionarlos con un registro actual y aparece arriba de de la vista de lista de la ventana emergente en la misma ventana. Los módulos heredados usan este diseño para la búsqueda de ventana emergente mientras los módulos de Sidecar usan la configuración de los diseños de Búsqueda.',
        'BasicSearchBtn' => 'Personalizar el formulario de <b>Búsqueda Básica</b>que aparece en la pestaña da de Búsqueda Básica en la zona de búsqueda para el módulo.',
        'AdvancedSearchBtn' => 'Personalizar el formulario de <b>Búsqueda Avanzada</b> que aparece en la pestaña de búsqueda avanzada en el área de la búsqueda para el módulo.',
        'portalHelp' => 'Administrar y personalizar el <b>Portal de Sugar</b>.',
        'SPUploadCSS' => 'Cargar <b>Hoja de Estilo</b> para el Portal de Sugar.',
        'SPSync' => '<b>Sincronizar</b> personalizaciones con la instancia del Portal de Sugar.',
        'Layouts' => 'Personalizar los <b>Diseños</b> de los módulos del Portal de Sugar.',
        'portalLayoutHelp' => 'Los módulos dentro del Portal de Sugar aparecen en esta área.<br><br> Seleccione un módulo para editar los<b>Diseños</b>.',
        'relationshipsHelp' => 'Todas las <b>Releaciones</b> que existen entre el módulo y otros módulos desplegados aparecen aquí.<br><br> El <b>Nombre</b> de la Relación es el nombre generado por el sistema para la relación.<br><br>El <b>Módulo Primario</b> es el módulo propietario de las relaciones. Por ejemplo, todas las propiedades de las relaciones para las que el módulo de Cuentas es el módulo principal se almacenan en las tablas de la base de datos de las cuentas.<br><br>El <b>Tipo</b> es el tipo de relación entre el módulo primario y el <b>Módulo Relacionado</b>.<br><br>Haga clic en un título de la columna para ordenar por columna.<br><br>Haga clic en una fila de la tabla de relaciones para ver las propiedades asociadas a la relación.<br><br>Haga clic en <b>Añadir Relación</b> para crear una nueva relación.<br><br>Las relaciones pueden ser creadas entre dos módulos desplegados.',
        'relationshipHelp'=>'Las <b>Relaciones</b>se pueden crear entre el módulo y otro módulo desplegado.<br><br> Las relaciones se expresan visualmente a través de subpaneles y relacionan campos en los registros del módulo.<br><br>Seleccione uno de los siguientes <b>Tipos</b> de relaciones para el módulo:<br><br> <b>Uno-a-Uno</b> - Ambos registros de los dos módulos contienen los campos relacionados.<br><br> <b>Uno-a-Muchos</b> - El registro del módulo Primario contendrá un subpanel, y el Registro del Módulo Relacionado contendrá un campo de relación.<br><br> <b>Muchos-a-Muchos</b> - Ambos registros de módulos mostrarán subpaneles.<br><br> Seleccione el <b>Módulo Relacionado</b> para la relación. <br><br>Si el tipo de relación implica subpaneles, seleccione la vista subpanel para los módulos correspondientes.<br><br> Haga clic en <b>Guardar</b> para crear la relación.',
        'convertLeadHelp' => "Aquí usted puede agregar módulos a la pantalla de convertir diseño  y modificar las configuraciones de los ya existentes.<br/><br/>        
<b>Ordenar:</b><br/>
Contactos, Cuentas y Oportunidades deben mantener su orden. Puede volver a ordenar los módulos arrastrando sus filas en la tabla.<br/>
<br/>
<b>Dependencia:</b><br/>
Si se incluye Oportunidades, Cuentas puede requerirse o eliminarse de convertir diseño.
<br/><br />       
 <b>Módulo:</b> El nombre del módulo.<br/><br/>        <b>Requerido:</b>Módulos requeridos deben ser creados o seleccionados antes de que el lead se puede convertir.<br/><br/>
<b>Copia de Datos:</b>Si se selecciona, los campos de la lead se copiarán en los campos con el mismo nombre en los nuevos registros creados.<br/><br/>
 <b>Eliminar:</b> Elimine este módulo del diseño de conversión.<br/><br/>        ",
        'editDropDownBtn' => 'Editar un desplegable global',
        'addDropDownBtn' => 'Añadir un nuevo desplegable global',
    ),
    'fieldsHelp'=>array(
        'default'=>'Los <b>Campos</b>en el módulo se describen aquí por Nombre de campo.<br><br>La plantilla de módulo incluye un conjunto predeterminado de campos.<br><br>Para crear un nuevo campo, haga clic en<b>Añadir Campo</b>.<br><br>Para editar un campo, haga clic en el <b>Nombre de Campo</b>.<br/><br/> Después de desplegar el módulo, los nuevos campos creados en el Constructor de Módulos, junto con los campos de la plantilla, se consideran campos estándar en Studio.',
    ),
    'relationshipsHelp'=>array(
        'default'=>'Las <b>Relaciones</b> que se han creado entre el módulo y otros módulos aparecen aquí.<br><br>El <b>Nombre</b> de la Relación es el nombre generado por el sistema para la relación.<br><br>El <b>Módulo Primario</b>> es el módulo que es propietario de las relaciones. Las propiedades de las relaciones se almacenan en las tablas de la base de datos pertenecientes al módulo primario.<br><br>El <b>Tipo</b> es el tipo de relación entre el módulo primario y el <b>Módulo Relacionado</b>.<br><br> Haga clic en un título de la columna para ordenar por la columna.<br><br> Haga clic en una fila en la tabla de relaciones para ver y editar las propiedades asociadas con la relación.<br><br>Haga Clic en <b>Añadir Relación</b> para crear una nueva relación.',
        'addrelbtn'=>'puntero del mouse sobre ayuda para añadir relación..',
        'addRelationship'=>'Las <b>Relaciones</b> pueden crearse entre el módulo y otro módulo personalizado o un módulo desplegado.<br><br> Las relaciones se expresan visualmente a través de subpaneles y relacionan campos en los registros del módulo.<br><br>Seleccione uno de los siguientes <b>Tipos</b> de relaciones para los módulos:<br><br> <b>Uno-para-Uno</b> - Ambos registros de los dos módulos contienen los campos relacionados.<br><br> <b>Uno-para-Muchos</b> - El registro del Módulo Primario contendrá un subpanel, y el Registro del Módulo Relacionado contendrá un campo de relación.<br><br> <b>Muchos-para-Muchos</b> - Ambos registros de módulos mostrarán subpaneles.<br><br> Seleccione el <b>Módulo Relacionado</b> de la relación. <br><br>Si el tipo de relación implica subpaneles, seleccione la vista subpanel para los módulos correspondientes.<br><br> Haga clic en <b>Guardar</b> para crear la relación.',
    ),
    'labelsHelp'=>array(
        'default'=> 'Las <b>Etiquetas</b> de los campos y otros títulos en el módulo pueden cambiarse.<br><br>Edite la etiqueta haciendo clic en el campo, introduciendo una nueva etiqueta y haciendo clic en <b>Guardar</b>.<br><br>Si se instala algún paquete de idiomas en la aplicación, puede seleccionar el <b>Idioma</b> que se utilizará para las etiquetas.',
        'saveBtn'=>'Haga clic en <b>Guardar</b> para guardar los cambios.',
        'publishBtn'=>'Haga clic en <b>Guardar y Desplegar</b> para guardar todos los cambios y activarlos.',
    ),
    'portalSync'=>array(
        'default' => 'Introduzca el <b>URL del Portal de Sugar</b> de la instancia del portal para actualizar y haga clic en<b>Ir</b>.<br><br>A continuación, introduzca un nombre de usuario válido y la contraseña de Sugar y haga clic en<b>Iniciar sincronización</b>.<br><br> Las personalizaciones realizadas en los <b>Diseños</b> del Portal de Sugar, junto con la <b>Hoja de Estilo</b> si uno se ha cargado, serán transferidas a la instancia de portal especificada.',
    ),
    'portalConfig'=>array(
           'default' => '',
       ),
    'portalStyle'=>array(
        'default' => 'Puede personalizar el aspecto del Portal de Sugar mediante hojas de estilo.<br><br>Seleccione una <b>Hoja de Estilo </b> para cargar.<br><br> La hoja de estilo se desplegará en el Portal de Sugar la próxima vez que se implemente una sincronización.',
    ),
),

'assistantHelp'=>array(
    'package'=>array(
            //custom begin
            'nopackages'=>'Para empezar a trabajar en un proyecto, haga clic en<b>Paquete Nuevo</b> para crear un nuevo paquete para albergar a su módulo personalizado. <br/><br/> Cada paquete puede contener uno o más módulos.<br/><br/> Por ejemplo, es posible que desee crear un paquete que contenga un módulo personalizado que se relacione con el módulo de Cuentas estándar. O bien, es posible que desee crear un paquete que contenga varios módulos nuevos que funcionen juntos como un proyecto y que estén relacionados entre sí y con otros módulos que ya están en la aplicación.',
            'somepackages'=>'Un <b>paquete</b> actúa como un contenedor de módulos personalizados, los cuales son parte de un proyecto. El paquete puede contener uno o más <b>módulos</b> personalizados que pueden estar relacionados entre sí o con otros módulos de la aplicación.<br/><br/>Después de crear un paquete para su proyecto, puede crear módulos para el paquete de inmediato, o puede volver al Generador de Módulos en otro momento para completar el proyecto.<br><br> Cuando el proyecto esté terminado, puede<b>Desplegar</b> el paquete para instalar los módulos personalizados dentro de la aplicación.',
            'afterSave'=>'Su nuevo paquete debe contener al menos un módulo. Puede crear uno o más módulos personalizados para el paquete.<br/><br/>Haga clic en <b>Nuevo Módulo</b> para crear un módulo personalizado para este paquete.<br/><br/> Después de crear al menos un módulo, puede publicar o distribuir el paquete para que esté disponible para la instancia y/o instancias de otros usuarios.<br/><br/>Para implementar el paquete en un solo paso dentro de su instancia de Sugar, haga clic en <b>Implementar</b>.<br><br> Haga clic en <b>Publicar</b> para guardar el paquete como un archivo.zip. Después de que el archivo.zip se guarda en el sistema, utilice el<b>Cargador de Módulos</b> para cargar e instalar el paquete dentro de la instancia de Sugar. <br/><br/> Puede distribuir el archivo a otros usuarios para que los puedan cargar e instalar dentro de sus propias instancias de Sugar.',
            'create'=>'Un <b>paquete</b> actúa como un contenedor para módulos personalizados, los cuales son parte de un proyecto. El paquete puede contener uno o más <b>módulos</b> personalizados que pueden estar relacionados entre sí o con otros módulos de la aplicación.<br/><br/>Después de crear un paquete para su proyecto, puede crear módulos para el paquete de inmediato, o puede volver al generador de módulo en otro momento para completar el proyecto.',
            ),
    'main'=>array(
        'welcome'=>'Utilice las <b>Herramientas para Desarrolladores</b>para crear y administrar módulos y campos estándares y personalizados. <br/><br/>Para gestionar los módulos de la aplicación, haga clic en <b>Studio</b>. <br/><br/>Para crear módulos personalizados, haga clic en <b>Generador de módulo</b>.',
        'studioWelcome'=>'Todos los módulos instalados, incluyendo los objetos estándares y cargados por el módulo, se pueden personalizar en Studio.'
    ),
    'module'=>array(
        'somemodules'=>"Dado que el paquete actual contiene al menos un módulo, puede <b>Desplegar</b> los módulos en el paquete dentro de la instancia de Sugar o <b>Publicar</b> el paquete para que sea instalado en la instancia actual de Sugar u otra instancia utilizando el <b>Cargador de Módulo</b>.<br/><br/>Para instalar el paquete directamente dentro de su instancia de Sugar, haga clic en <b>Desplegar</b>.<br><br>Para crear un archivo .zip para el paquete que se puede cargar y e instalar dentro de la instancia actual de Sugar  y en otras instancias utilizando el <b>Cargador de Módulo</b>, haga clic en <b>Publicar</b>.<br/><br/>Usted puede generar los módulos de este paquete en etapas, y publicarlos o desplegarlos cuando esté listo para hacerlo. <br/><br/> Después de publicar o desplegar un paquete, puede realizar cambios en las propiedades del paquete y personalizar los módulos aún más. Luego vuelva a publicar o desplegar el paquete para aplicar los cambios." ,
        'editView'=> 'Aquí puede editar los campos existentes. Puede eliminar cualquiera de los campos existentes o añadir campos disponibles en el panel de la izquierda.',
        'create'=>'Al elegir el tipo de <b>Tipo</b> de módulo que desea crear, tenga en cuenta los tipos de campos que le gustaría tener en el módulo. <br/><br/>Cada plantilla de módulo contiene un conjunto de campos relacionados con el tipo de módulo descrito por el título.<br/><br/><b>Básico</b> - Proporciona campos básicos que aparecen en los módulos estándares, tales como Nombre, Asignado a, Equipo, Fecha de creación y Descripción.<br/><br/> <b>Empresa</b> - Proporciona los campos específicos de la organización, tales como Nombre de la Empresa, Industria y dirección de facturación. Utilice esta plantilla para crear módulos que sean similares al módulo de Cuentas estándar.<br/><br/> <b>Persona</b> -Proporciona campos individuales específicos, tales como Saludo, Título, Nombre, Dirección y Número de teléfono. Utilice esta plantilla para crear módulos que sean similares a los contactos estándares y módulos de Leads.<br/><br/><b>Problema</b> - Proporciona campos de errores de casos específicos, como Número, Estado, Prioridad y Descripción. Utilice esta plantilla para crear módulos que sean similares a las casos estándares y módulos de seguimiento de errores.<br/><br/>Nota: Después de crear el módulo, puede editar las etiquetas de los campos proporcionados por la plantilla, así como crear campos personalizados para agregar a los diseños de los módulos.',
        'afterSave'=>'Personalizar el módulo para satisfacer sus necesidades mediante la edición y creación de campos, el establecimiento de relaciones con otros módulos y la organización de los campos dentro de los diseños.<br/><br/>Para ver los campos de la plantilla y gestionar campos personalizados en el módulo, haga clic en <b>Ver Campos</b>.<br/><br/>Para crear y gestionar las relaciones entre el módulo y otros módulos, ya sean módulos ya en la aplicación o de otros módulos personalizados dentro del mismo paquete, haga clic en <b>Ver Relaciones</b>.<br/><br/>Para editar los diseños de módulos, haga clic en <b>Ver Diseños</b>. Puede cambiar los diseños de Vista en detalle, Editar Vista y Vista de Lista del módulo del mismo modo que para los módulos que ya están en la aplicación dentro de Studio.<br/><br/> Para crear un módulo con las mismas propiedades que el módulo actual, haga clic en<b>Duplicar</b>. Puede personalizar aún más el nuevo módulo.',
        'viewfields'=>'Los campos del módulo se pueden personalizar para satisfacer sus necesidades.<br/><br/>No se pueden eliminar campos estándares, pero puede quitarlos de los diseños apropiados dentro de las páginas de Diseños.<br/><br/>Puede crear rápidamente nuevos campos que tengan propiedades similares a las de los campos existentes haciendo clic <b>Clonar</b> en el formulario de <b>Propiedades.</b>Introduzca las nuevas propiedades y, a continuación, haga clic en.<b>Guardar</b>.<br/><br/>Se recomienda establecer todas las propiedades de los campos estándares y campos personalizados antes de publicar e instalar el paquete que contiene el módulo personalizado.',
        'viewrelationships'=>'Puede crear relaciones de varios a varios entre el módulo actual y otros módulos en el paquete, y/o entre el módulo actual y los módulos ya instalados en la aplicación.<br><br> Para crear uno-a-muchos y uno-a-uno, crear campos de <b>Relacionar</b> y <b>Relación Flex</b> para los módulos.',
        'viewlayouts'=>'Usted puede controlar los campos que están disponibles para la captura de datos en el campo <b>Editar Vista</b>.  También se puede controlar lo que muestran los datos dentro de la <b>Vista de Detalle</b>. Las vistas no tienen que coincidir. <br/><br/>Se muestra el formulario Creación rápida cuando se hace clic en la opción <b>Crear</b> de un subpanel de módulo. Por defecto, el diseño del formulario de <b>Creación Rápida</b> es el mismo que el diseño predeterminado de <b>Editar Vista<br/>. Usted puede personalizar el formulario Creación Rápida de manera que contenga menos campos menos y/o diferentes al diseño de Editar vista.<br><br>Puede determinar la seguridad del módulo usando la personalización de diseño, junto con <b>Administración de funciones</b>.<br><br>',
        'existingModule' =>'Después de la creación y personalización de este módulo, se pueden crear módulos adicionales o volver al paquete de<b>Publicación</b> o <b>Implementar</b> el paquete.<br><br>Para crear módulos adicionales, haga clic en<b>Duplicar</b> para crear un módulo con las mismas propiedades que el módulo actual, o navegue de vuelta al paquete y haga clic en<b>Módulo Nuevo</b>.<br><br>Si usted está listo para <b>Publicar</b> o <b>Implementar</b> el paquete que contiene este módulo, vaya de nuevo a el paquete para realizar estas funciones. Usted puede publicar e implementar paquetes que contengan al menos un módulo.',
        'labels'=> 'Las etiquetas de los campos estándar, así como los campos personalizados se pueden cambiar. Cambiar las etiquetas de campo no afectará los datos almacenados en los campos.',
    ),
    'listViewEditor'=>array(
        'modify'	=> 'Hay tres columnas que se muestran a la izquierda. La columna "Predeterminada" contiene los campos que se muestran en una List View de forma predeterminada, la columna "Disponible" contiene los campos que el usuario puede elegir para crear una List View personalizada, y la columna "Oculto" contiene los campos disponibles para usted como administrador ya sea para añadir a las columnas predeterminada o disponible para su utilización por parte de los usuarios, pero que están actualmente desactivados.',
        'savebtn'	=> 'Al hacer clic en <b>Guardar</b> se guardarán todos los cambios y se activarán.',
        'Hidden' 	=> 'Campos ocultos son campos que no están disponibles actualmente para que los usuarios lo usen en las vistas de lista.',
        'Available' => 'Los campos disponibles son campos que no se muestran por defecto, pero se pueden habilitar por los usuarios.',
        'Default'	=> 'Campos predeterminados se muestran a los usuarios que no han creado la configuración de vista de lista personalizada.'
    ),

    'searchViewEditor'=>array(
        'modify'	=> 'Hay dos columnas que se muestran a la izquierda. La columna "Por Defecto" contiene los campos que se mostrarán en la vista de búsqueda, y la columna "Oculto" contiene los campos disponibles para usted como administrador para agregar a la vista.',
        'savebtn'	=> 'Al hacer clic en <b>Guardar y Desplegar</b> guardará todos los cambios y los hará activos.',
        'Hidden' 	=> 'Campos ocultos son campos que no se muestran en la vista de búsqueda.',
        'Default'	=> 'Los campos predeterminados se mostrarán en la vista de búsqueda.'
    ),
    'layoutEditor'=>array(
        'default'	=> 'Hay dos columnas que se muestran a la izquierda. La columna de la derecha, con la etiqueta de Diseño actual o Vista preliminar del diseño, es donde se cambia el diseño del módulo. La columna de la izquierda, que se titula Caja de herramientas, contiene elementos y herramientas útiles para su uso durante la edición del diseño.<br/><br/>Si el área de diseño se titula Disposición actual, entonces usted está trabajando sobre una copia del diseño actualmente utilizado por el módulo de pantalla.<br/><br/>Si se titula Vista previa del diseño entonces usted está trabajando en una copia creada con anterioridad mediante un clic en el botón Guardar, que ya podría haber sido cambiada con respecto a la versión vista por los usuarios de este módulo.',
        'saveBtn'	=> 'Al hacer clic en este botón guarda el diseño para que pueda conservar los cambios. Cuando regrese a este módulo iniciará a partir de este diseño modificado. Su diseño, sin embargo, no será visto por los usuarios del módulo hasta que haga clic en el botón Guardar y Publicar.',
        'publishBtn'=> 'Haga clic en este botón para desplegar el diseño. Esto significa que este diseño será visto inmediatamente por los usuarios de este módulo.',
        'toolbox'	=> 'La caja de herramientas contiene una variedad de características útiles para la edición de diseños, que incluye un área de basura, un conjunto de elementos adicionales y un conjunto de campos disponibles. Cualquiera de estos se puede arrastrar y soltar en el diseño.',
        'panels'	=> 'Esta área muestra cómo los usuarios de este módulo verán su diseño cuando se implemente.<br/><br/>Puede cambiar la posición de los elementos tales como campos, filas y paneles arrastrándolos y soltándolos, eliminar elementos arrastrándolos y soltándolos en el área de la basura en la caja de herramientas, o añadir nuevos elementos arrastrándolos desde la caja de herramientas y soltándolos en el diseño en la posición deseada.'
    ),
    'dropdownEditor'=>array(
        'default'	=> 'Hay dos columnas que se muestran a la izquierda. La columna de la derecha, con la etiqueta de Diseño actual o Vista preliminar del diseño, es donde se cambia el diseño del módulo. La columna de la izquierda, que se titula Caja de herramientas, contiene elementos y herramientas útiles para su uso durante la edición del diseño.<br/><br/>Si el área de diseño se titula Disposición actual, entonces usted está trabajando sobre una copia del diseño actualmente utilizado por el módulo de pantalla.<br/><br/>Si se titula Vista previa del diseño entonces usted está trabajando en una copia creada con anterioridad mediante un clic en el botón Guardar, que ya podría haber sido cambiada con respecto a la versión vista por los usuarios de este módulo.',
        'dropdownaddbtn'=> 'Haciendo clic en este botón se añade un nuevo elemento a la lista desplegable.',

    ),
    'exportcustom'=>array(
        'exportHelp'=>'Las personalizaciones realizadas en Studio dentro de esta instancia pueden agruparse mediante la creación de paquetes que se pueden cargar en otra instancia. <br><br>Proporcione un <b>Nombre de Paquete</b>. Puede proporcionar información del <b>Autor</b> y <b>Descripción</b> para el paquete. 
<br><br>Seleccione el módulo que contenga las personalizaciones que desea exportar. Sólo los módulos que contienen personalizaciones aparecerán para que usted los seleccione.<br><br>Haga clic en <b>Exportar</b>para crear un archivo .zip para el paquete que contiene las personalizaciones. El archivo .zip puede cargarse en otra instancia mediante el <b>Cargador de Módulo</b>.',
        'exportCustomBtn'=>'Haga clic en <b>Exportar</b>para crear un archivo .zip para el paquete que contiene las personalizaciones que desea exportar.',
        'name'=>'El <b>Nombre</b> del paquete se mostrará en el Cargador de Módulo después de que el paquete se cargue para su instalación en Studio.',
        'author'=>'El <b>Autor</b>es el nombre de la entidad que creó el paquete. El autor puede ser un individuo o una empresa.<br><br> El Autor se mostrará en el Cargador de Módulo después de que el paquete se cargue para su instalación en Studio.
',
        'description'=>'La<b>Descripción</b> del paquete se mostrará en el Cargador de Módulo después de que el paquete se cargue para su instalación en Studio.',
    ),
    'studioWizard'=>array(
        'mainHelp' 	=> 'Bienvenidos al área de <b>Herramientas para Desarrolladores</b>. <br/><br/> Utilice las herramientas dentro de esta área para crear y administrar módulos y campos estándares y personalizados.',
        'studioBtn'	=> 'Utilice <b>Studio</b> para personalizar los módulos instalados cambiando la disposición del campo, seleccionando los campos que estén disponibles y creando campos de datos personalizados.',
        'mbBtn'		=> 'Use el <b>Generador de módulos</b> para crear nuevos módulos.',
        'appBtn' 	=> 'Utilice el modo de aplicación para personalizar las propiedades incluidas en el programa, por ejemplo, cómo muchos de los informes TPS que se muestran en la página de inicio',
        'backBtn'	=> 'Volver al paso anterior.',
        'studioHelp'=> 'Use <b>Studio</b> para personalizar los módulos instalados.',
        'moduleBtn'	=> 'Haga clic para editar este módulo.',
        'moduleHelp'=> 'Seleccione el componente del módulo que desea editar',
        'fieldsBtn'	=> 'Editar la información que se almacena en el módulo mediante el control de los <b> Campos </b> del módulo. <br/><br/> Puede editar y crear campos personalizados aquí.',
        'layoutsBtn'=> 'Personalizar los <b> Diseños </b> de las vistas de búsqueda de Edición, Detalle y Lista.',
        'subpanelBtn'=> 'Editar la información que se muestra en los subpaneles de los módulos.',
        'layoutsHelp'=> 'Seleccione un <b>Diseño a editar</b>.<br/<br/> Para cambiar el diseño que contiene los campos de datos para introducir los datos, haga clic en<b>Vista de Edición</b>.<br/><br/>Para cambiar el diseño que muestra los datos introducidos en los campos en la vista de edición, haga clic en <b>Vista de Detalle</b>.<br/><br/>Para cambiar las columnas que aparecen en la lista predeterminada, haga clic en <b>Vista de Lista</b>.<br/><br/>Para cambiar los diseños del formulario de búsqueda Básico y Avanzado, haga clic en <b>Buscar</b>.',
        'subpanelHelp'=> 'Seleccione un <b>Subpanel</b> para editar.',
        'searchHelp' => 'Seleccione un diseño de Búsqueda para editar.',
        'labelsBtn'	=> 'Editar las <b>etiquetas</b> para mostrar los valores en este módulo.',
        'newPackage'=>'Haga clic en Nuevo paquete para crear un nuevo paquete.',
        'mbHelp'    => '<b>Bienvenido al Generador de Módulo.</b><br/><br/>Utiice<b>Generador de Módulo</b>para crear paquetes que contengan módulos personalizados basados en objetos estándar o personalizados. <br/><br/> Para empezar, haga clic en <b>Paquete Nuevo</b>para crear un nuevo paquete, o seleccione un paquete para editar.<br/><br/> Un <b>Paquete</b>actúa como un contenedor de módulos personalizados, los cuales son parte de un proyecto. El paquete puede contener uno o más módulos personalizados que pueden estar relacionados entre sí o con los módulos de la aplicación.<br/><br/> Ejemplos: Es posible que desee crear un paquete que contiene un módulo personalizado que se relaciona con el módulo de Cuentas estándar. O bien, es posible que desee crear un paquete que contiene varios módulos nuevos que funcionan juntos como un proyecto y que están relacionados entre sí y con los módulos de la aplicación.',
        'exportBtn' => 'Haga clic en <b> Exportar personalizaciones </b> para crear un paquete que contenga personalizaciones realizadas en Studio para módulos específicos.',
    ),

),
//HOME
'LBL_HOME_EDIT_DROPDOWNS'=>'Editor de Listas Desplegables',

//ASSISTANT
'LBL_AS_SHOW' => 'Mostrar el Asistente en el futuro.',
'LBL_AS_IGNORE' => 'Ignorar el Asistente en el futuro.',
'LBL_AS_SAYS' => 'El Asistente Sugiere:',

//STUDIO2
'LBL_MODULEBUILDER'=>'Generador de Módulos',
'LBL_STUDIO' => 'Studio',
'LBL_DROPDOWNEDITOR' => 'Editor de Listas Desplegables',
'LBL_EDIT_DROPDOWN'=>'Editar Lista Desplegable',
'LBL_DEVELOPER_TOOLS' => 'Herramientas para el Desarrollador',
'LBL_SUGARPORTAL' => 'Editor del Portal Sugar',
'LBL_SYNCPORTAL' => 'Portal de Sincronización',
'LBL_PACKAGE_LIST' => 'Lista de Paquetes',
'LBL_HOME' => 'Inicio',
'LBL_NONE'=>'-Ninguno-',
'LBL_DEPLOYE_COMPLETE'=>'Despliegue completado',
'LBL_DEPLOY_FAILED'   =>'Ha ocurrido un error durante el proceso de despliegue. Es posible que su paquete no haya sido instalado correctamente',
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
'LBL_FILTER_SEARCH' => "Búsqueda",
'LBL_FILLER'=>'(relleno)',
'LBL_FIELDS'=>'Campos',
'LBL_FAILED_TO_SAVE' => 'Fallo Al Guardar',
'LBL_FAILED_PUBLISHED' => 'Fallo Al Publicar',
'LBL_HOMEPAGE_PREFIX' => 'Mi',
'LBL_LAYOUT_PREVIEW'=>'Vista Preliminar del Diseño',
'LBL_LAYOUTS'=>'Diseños',
'LBL_LISTVIEW'=>'Vista de Lista',
'LBL_RECORDVIEW'=>'Ver Registro',
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
'LBL_STAGING_AREA' => 'Área de Almacenamiento Temporal (arrastre y suelte elementos aquí)',
'LBL_SUGAR_FIELDS_STAGE' => 'Campos Sugar (haga clic en los elementos para agregarlos al área de almacenamiento temporal)',
'LBL_SUGAR_BIN_STAGE' => 'Papelera Sugar (haga clic en los elementos para agregarlos al área de almacenamiento temporal)',
'LBL_TOOLBOX' => 'Caja de Herramientas',
'LBL_VIEW_SUGAR_FIELDS' => 'Ver Campos Sugar',
'LBL_VIEW_SUGAR_BIN' => 'Ver Papelera Sugar',
'LBL_QUICKCREATE' => 'Creación Rápida',
'LBL_EDIT_DROPDOWNS' => 'Editar una Lista Desplegable Global',
'LBL_ADD_DROPDOWN' => 'Agregar una nueva Lista Desplegable Global',
'LBL_BLANK' => '-vacío-',
'LBL_TAB_ORDER' => 'Órden de Tabulación',
'LBL_TAB_PANELS' => 'Mostrar paneles como pestañas',
'LBL_TAB_PANELS_HELP' => 'Mostrar cada panel como su propia pestaña en lugar de hacer que aparezcan todos en una pantalla',
'LBL_TABDEF_TYPE' => 'Tipo de Visualización:',
'LBL_TABDEF_TYPE_HELP' => 'Seleccione la forma en la que se debe mostra esta sección. Esta opción únicamente tendrá efecto si ha habilitado el modo pestañas para esta vista.',
'LBL_TABDEF_TYPE_OPTION_TAB' => 'Pestaña',
'LBL_TABDEF_TYPE_OPTION_PANEL' => 'Panel',
'LBL_TABDEF_TYPE_OPTION_HELP' => 'Seleccione Panel para que el panel se muestre en la vista inicial o en la vista del panel anterior que se haya seleccionado como Pestaña.  <br/>Seleccione Pestaña para mostrar el panel en una pestaña independiente. Cuando se ha seleccionado un panel como Pestaña, los siguientes paneles seleccionados como Panel se mostrarán en la vista de dicha pestaña.  <br/>Siempre que seleccione un panel como Pestaña será el primer panel a mostrar en dicha Pestaña.  <br/>Si se selecciona como Pestaña el segundo panel o posteriores, el primer panel se establecerá automáticamente como Pestaña si se hubiera seleccionado anteriormente como Panel.',
'LBL_TABDEF_COLLAPSE' => 'Contraído',
'LBL_TABDEF_COLLAPSE_HELP' => 'Seleccione para que por defecto el estado del panel sea contraído.',
'LBL_DROPDOWN_TITLE_NAME' => 'Nombre',
'LBL_DROPDOWN_LANGUAGE' => 'Idioma',
'LBL_DROPDOWN_ITEMS' => 'Elementos de Lista',
'LBL_DROPDOWN_ITEM_NAME' => 'Nombre del Elemento',
'LBL_DROPDOWN_ITEM_LABEL' => 'Etiqueta de Visualización',
'LBL_SYNC_TO_DETAILVIEW' => 'Sincroniza con Vista de Detalle',
'LBL_SYNC_TO_DETAILVIEW_HELP' => 'Seleccione esta opción para sincronizar el diseño de la Vista de Edición con el correspondiente diseño de la Vista de Detalle. Los campos y su colocación en la Vista de Detalle serán sincronizados y guardados automáticamente en la Vista de Detalle al pulsar en Guardar or Guardar y Desplegar en la Vista de Edición. No se podrán realizar cambios en el diseño de la Vista de Detalle.',
'LBL_SYNC_TO_DETAILVIEW_NOTICE' => 'Esta Vista de Detalle está sincronizada con la Vista de Edición correspondiente. <br> Los campos y la colocación del campo en esta Vista de Detalle reflejan los campos y colocación sobre el campo en la Vista de Edición. <br> Los cambios en la Vista de Detalle no pueden guardarse ni desplegarse desde esta página. Realizar los cambios o quitar sincronización en los diseños de la vista de edición. ',
'LBL_COPY_FROM' => 'Copiar desde',
'LBL_COPY_FROM_EDITVIEW' => 'Copiar de la Vista de Edición',
'LBL_DROPDOWN_BLANK_WARNING' => 'Los valores son necesarios tanto para el nombre del elemento y la etiqueta de visualización. Para agregar un elemento en blanco, haga clic en Agregar, sin entrar en ningún valor para el nombre del elemento y la etiqueta de visualización.',
'LBL_DROPDOWN_KEY_EXISTS' => 'Clave ya existe en la lista',
'LBL_DROPDOWN_LIST_EMPTY' => 'La lista debe contener al menos 1 elemento habilitado',
'LBL_NO_SAVE_ACTION' => 'No se ha podido encontrar la opción de guardar para esta vista.',
'LBL_BADLY_FORMED_DOCUMENT' => 'Studio2:establecer ubicación: documento mal constituido',
// @TODO: Remove this lang string and uncomment out the string below once studio
// supports removing combo fields if a member field is on the layout already.
'LBL_INDICATES_COMBO_FIELD' => '** Indica un campo de combinación. Un campo de combinación es una colección de campos individuales. Por ejemplo, "Dirección" es un campo de combinación que contiene "dirección de la calle", "ciudad", "Código postal", "Estado" y "País".<br><br> Haga doble clic sobre un campo de combinación para ver qué campos contiene.',
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
'LBL_FORMULA_BUILDER' => 'Generador de Fórmulas',
'LBL_FORMULA_INVALID' => 'Fórmula No Válida',
'LBL_FORMULA_TYPE' => 'La fórmula debe ser del tipo',
'LBL_NO_FIELDS' => 'No se han encontrado Campos',
'LBL_NO_FUNCS' => 'No se han encontrado Funciones',
'LBL_SEARCH_FUNCS' => 'Buscar Funciones...',
'LBL_SEARCH_FIELDS' => 'Buscar Campos...',
'LBL_FORMULA' => 'Fórmula',
'LBL_DYNAMIC_VALUES_CHECKBOX' => 'Dependiente',
'LBL_DEPENDENT_DROPDOWN_HELP' => 'Arrastre los elementos de la lista de opciones desplegable dependiente, disponibles a la izquierda, hacia una de las listas de la derecha para hacer que la opción esté disponible cuando la opción principal se seleccione. Si no hay elementos en la opción principal, cuando se selecciona la opción principal, la lista desplegable dependiente no se mostrará.',
'LBL_AVAILABLE_OPTIONS' => 'Opciones Disponibles',
'LBL_PARENT_DROPDOWN' => 'Lista Desplegable Principal',
'LBL_VISIBILITY_EDITOR' => 'Editor de Visibilidad',
'LBL_ROLLUP' => 'Rollup',
'LBL_RELATED_FIELD' => 'Campo Relacionado',
'LBL_CONFIG_PORTAL_URL'=>'URL de la imagen de logo personalizado. Las dimensiones recomendadas del logotipo son 163 × 18 píxeles.',
'LBL_PORTAL_ROLE_DESC' => 'No elimine este rol. El rol del Portal de Autoservicio para el Cliente lo genera el mismo sistema durante la activación del Portal de Sugar. Utilice los controles de acceso dentro de este rol para habilitar y/o deshabilitar los módulos de Gestor de Incidencias, Casos o Base de conocimiento en el Portal de Sugar. Para evitar un comportamiento desconocido e imprevisible del sistema, no modifique otros controles de acceso para este rol. En caso de eliminación accidental de este rol, vuelva a crearlo desactivando y activando el Portal de Sugar.',

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
'LBL_QUESTION_EDIT' => 'Seleccione un módulo para editar.',
'LBL_QUESTION_LAYOUT' => 'Seleccione un diseño a editar.',
'LBL_QUESTION_SUBPANEL' => 'Seleccione un subpanel para editar.',
'LBL_QUESTION_SEARCH' => 'Seleccione un diseño de búsqueda para editar.',
'LBL_QUESTION_MODULE' => 'Seleccione un componente del módulo para editar.',
'LBL_QUESTION_PACKAGE' => 'Seleccione un paquete para editar, o cree un nuevo paquete.',
'LBL_QUESTION_EDITOR' => 'Seleccione una herramienta.',
'LBL_QUESTION_DROPDOWN' => 'Seleccione una lista desplegable para editar, o cree una nueva lista desplegable.',
'LBL_QUESTION_DASHLET' => 'Seleccione un diseño de dashlet para editar.',
'LBL_QUESTION_POPUP' => 'Seleccione un diseño emergente para editar.',
//CUSTOM FIELDS
'LBL_RELATE_TO'=>'Relacionado Con',
'LBL_NAME'=>'Nombre',
'LBL_LABELS'=>'Etiquetas',
'LBL_MASS_UPDATE'=>'Actualización Masiva',
'LBL_AUDITED'=>'Auditoría',
'LBL_CUSTOM_MODULE'=>'Módulo',
'LBL_DEFAULT_VALUE'=>'Valor Predeterminado',
'LBL_REQUIRED'=>'Requerido',
'LBL_DATA_TYPE'=>'Tipo',
'LBL_HCUSTOM'=>'PERSONALIZADO',
'LBL_HDEFAULT'=>'PREDETERMINADA',
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
'LBL_SECTION_DEPLOY' => 'Desplegar',
'LBL_SECTION_MODULE' => 'Módulo',
'LBL_SECTION_VISIBILITY_EDITOR'=>'Editar Visibilidad',
//WIZARDS

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Por Defecto',
'LBL_HIDDEN'=>'Oculto',
'LBL_AVAILABLE'=>'Disponible',
'LBL_LISTVIEW_DESCRIPTION'=>'A continuación se muestran tres columnas. La columna <b>Por Defecto</b> contiene los campos que se muestran en una vista de lista por defecto. La columna <b>Adicional</b> contiene campos que un usuario puede elegir a la hora de crear una vista personalizada. La columna <b>Disponible</b> muestra columnas disponibles para usted como administrador para añadirlas a las columnas Por Defecto o Adicional para que sean usadas por los usuarios.',
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
'LBL_BTN_COPY_FROM' => 'Copiar desde…',
'LBL_BTN_ADDCOLS'=>'Agregar Columnas',
'LBL_BTN_ADDROWS'=>'Agregar Filas',
'LBL_BTN_ADDFIELD'=>'Agregar Campo',
'LBL_BTN_ADDDROPDOWN'=>'Agregar Lista Desplegable',
'LBL_BTN_SORT_ASCENDING'=>'Ordenar Ascendete',
'LBL_BTN_SORT_DESCENDING'=>'Ordenar Descendente',
'LBL_BTN_EDLABELS'=>'Editar Etiquetas',
'LBL_BTN_UNDO'=>'Deshacer',
'LBL_BTN_REDO'=>'Repetir',
'LBL_BTN_ADDCUSTOMFIELD'=>'Agregar Campo Personalizado',
'LBL_BTN_EXPORT'=>'Exportar Personalizaciones',
'LBL_BTN_DUPLICATE'=>'Duplicar',
'LBL_BTN_PUBLISH'=>'Publicar',
'LBL_BTN_DEPLOY'=>'Desplegar',
'LBL_BTN_EXP'=>'Exportar',
'LBL_BTN_DELETE'=>'Eliminar',
'LBL_BTN_VIEW_LAYOUTS'=>'Ver Diseños',
'LBL_BTN_VIEW_MOBILE_LAYOUTS'=>'Ver Diseños para Móviles',
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

'ERROR_CALCULATED_MOBILE_FIELDS' => 'Los siguientes campos contienen valores calculados que no serán recalculados en tiempo real en la Vista de Edición para Móviles de SugarCRM:',
'ERROR_CALCULATED_PORTAL_FIELDS' => 'Los siguientes campos contienen valores calculados que no serán recalculados en tiempo real en la Vista de Edición del Portal de SugarCRM:',

//SUGAR PORTAL
    'LBL_PORTAL_DISABLED_MODULES' => 'Los siguientes módulos están deshabilitados:',
    'LBL_PORTAL_ENABLE_MODULES' => 'Si desea habilitarlos en el portal, por favor, habilitelos <a id="configure_tabs" target="_blank" href="./index.php?module=Administration&amp;action=ConfigureTabs">aquí</a>.',
    'LBL_PORTAL_CONFIGURE' => 'Configure el Portal',
    'LBL_PORTAL_THEME' => 'Tema del Portal',
    'LBL_PORTAL_ENABLE' => 'Habilitar',
    'LBL_PORTAL_SITE_URL' => 'El sitio del portal está disponible en:',
    'LBL_PORTAL_APP_NAME' => 'Nombre de la Aplicación',
    'LBL_PORTAL_LOGO_URL' => 'URL del Logo',
    'LBL_PORTAL_LIST_NUMBER' => 'Número de registros para mostrar en la lista',
    'LBL_PORTAL_DETAIL_NUMBER' => 'Número de campos para mostrar en la Vista de Detalle',
    'LBL_PORTAL_SEARCH_RESULT_NUMBER' => 'Número de resultados para mostrar en la Búsqueda Global',
    'LBL_PORTAL_DEFAULT_ASSIGN_USER' => 'Asignado por defecto para nuevos registros del portal',

'LBL_PORTAL'=>'Portal',
'LBL_PORTAL_LAYOUTS'=>'Diseños del Portal',
'LBL_SYNCP_WELCOME'=>'Por favor, introduzca el URL de la instancia del portal que desea actualizar.',
'LBL_SP_UPLOADSTYLE'=>'Seleccione la hoja de estilo para cargar desde su equipo.<br> La hoja de estilo será utilizada en el Portal de Sugar la próxima vez que realice una sincronización.',
'LBL_SP_UPLOADED'=> 'Subido',
'ERROR_SP_UPLOADED'=>'Por favor, asegúrese de que está cargando una hoja de estilo css.',
'LBL_SP_PREVIEW'=>'Aquí tiene una vista preliminar de la apariencia que tendrá el Portal de Sugar al usar la hoja de estilo.',
'LBL_PORTALSITE'=>'URL del Portal de Sugar: ',
'LBL_PORTAL_GO'=>'Adelante',
'LBL_UP_STYLE_SHEET'=>'Cargar Hoja de Estilo',
'LBL_QUESTION_SUGAR_PORTAL' => 'Seleccione un diseño del Portal de Sugar para editar.',
'LBL_QUESTION_PORTAL' => 'Seleccione un diseño del portal a editar.',
'LBL_SUGAR_PORTAL'=>'Editor del Portal de Sugar',
'LBL_USER_SELECT' => '--Seleccionar--',

//PORTAL PREVIEW
'LBL_CASES'=>'Casos',
'LBL_NEWSLETTERS'=>'Boletines Informativos',
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
'LBL_SINGULAR_LABEL' => 'Etiqueta en Singular',
'LBL_WIDTH'=>'Ancho',
'LBL_PACKAGE'=>'Paquete:',
'LBL_TYPE'=>'Tipo:',
'LBL_TEAM_SECURITY'=>'Seguridad del Equipo',
'LBL_ASSIGNABLE'=>'Asignable',
'LBL_PERSON'=>'Persona',
'LBL_COMPANY'=>'Compañía',
'LBL_ISSUE'=>'Incidencia',
'LBL_SALE'=>'Venta',
'LBL_FILE'=>'Archivo',
'LBL_NAV_TAB'=>'Pestaña de Navegación',
'LBL_CREATE'=>'Crear',
'LBL_LIST'=>'Lista',
'LBL_VIEW'=>'Vista',
'LBL_LIST_VIEW'=>'Vista de Lista',
'LBL_HISTORY'=>'Ver Historial',
'LBL_RESTORE_DEFAULT_LAYOUT'=>'Restaurar el diseño predeterminado',
'LBL_ACTIVITIES'=>'Actividades',
'LBL_SEARCH'=>'Buscar',
'LBL_NEW'=>'Nuevo',
'LBL_TYPE_BASIC'=>'básica',
'LBL_TYPE_COMPANY'=>'compañía',
'LBL_TYPE_PERSON'=>'persona',
'LBL_TYPE_ISSUE'=>'incidencia',
'LBL_TYPE_SALE'=>'venta',
'LBL_TYPE_FILE'=>'archivo',
'LBL_RSUB'=>'Este es el subpanel que se mostrará en su módulo',
'LBL_MSUB'=>'Este es el subpanel que su módulo proporciona para que sea mostrado por el módulo relacionado',
'LBL_MB_IMPORTABLE'=>'Permitir la importación',

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
'LBL_EC_CHECKERROR'=>'Por favor, seleccione un módulo.',
'LBL_EC_CUSTOMFIELD'=>'campos personalizados',
'LBL_EC_CUSTOMLAYOUT'=>'diseños personalizados',
'LBL_EC_CUSTOMDROPDOWN' => 'Menú desplegable personalizado',
'LBL_EC_NOCUSTOM'=>'No se ha personalizado ningún módulo.',
'LBL_EC_EXPORTBTN'=>'Exportar',
'LBL_MODULE_DEPLOYED' => 'El módulo ha sido desplegado.',
'LBL_UNDEFINED' => 'no definido',
'LBL_EC_CUSTOMLABEL'=>'etiquetas personalizadas',

//AJAX STATUS
'LBL_AJAX_FAILED_DATA' => 'Error al recuperar los datos',
'LBL_AJAX_TIME_DEPENDENT' => 'Hay una acción dependiente del tiempo en progreso. Por favor, espere e inténtelo de nuevo en unos instantes.',
'LBL_AJAX_LOADING' => 'Cargando...',
'LBL_AJAX_DELETING' => 'Eliminando...',
'LBL_AJAX_BUILDPROGRESS' => 'Generación En Progreso...',
'LBL_AJAX_DEPLOYPROGRESS' => 'Despliegue En Progreso...',
'LBL_AJAX_FIELD_EXISTS' =>'El nombre del campo que ha introducido ya existe. Por favor, introduzca un nuevo nombre para el campo.',
//JS
'LBL_JS_REMOVE_PACKAGE' => '¿Está seguro de que desea quitar este paquete? Esto eliminará permanentemente todos los archivos asociados con este paquete.',
'LBL_JS_REMOVE_MODULE' => '¿Está seguro de que desea quitar este módulo? Esto eliminará permanentemente todos los archivos asociados con este módulo.',
'LBL_JS_DEPLOY_PACKAGE' => 'Cualquier personalización que haya realizado en Studio será sobrescrita cuando este módulo sea desplegado de nuevo. ¿Está seguro de que desea continuar?',

'LBL_DEPLOY_IN_PROGRESS' => 'Desplegando Paquete',
'LBL_JS_VALIDATE_NAME'=>'Nombre: debe ser alfanumérico, sin espacios, comenzar con una letra, y puede contener guiones bajos. No se pueden utilizar otros caracteres especiales.',
'LBL_JS_VALIDATE_PACKAGE_KEY'=>'La Clave del paquete ya existe',
'LBL_JS_VALIDATE_PACKAGE_NAME'=>'El Nombre del Paquete ya existe',
'LBL_JS_PACKAGE_NAME'=>'Nombre del paquete - debe empezar con una letra y sólo puede consistir de letras, números y guiones bajos. No se permiten espacios u otros caracteres especiales.',
'LBL_JS_VALIDATE_KEY_WITH_SPACE'=>'Clave: debe ser alfanumérica y comenzar con una letra.',
'LBL_JS_VALIDATE_KEY'=>'Clave: debe ser alfanumérica, comenzar con una letra, y sin espacios.',
'LBL_JS_VALIDATE_LABEL'=>'Por favor, introduzca la etiqueta que se utilizará como Nombre Visible de este módulo',
'LBL_JS_VALIDATE_TYPE'=>'Por favor, seleccione el tipo de módulo que quiere crear de la lista anterior',
'LBL_JS_VALIDATE_REL_NAME'=>'Nombre: debe ser alfanumérico y sin espacios',
'LBL_JS_VALIDATE_REL_LABEL'=>'Etiqueta: por favor, agregue la etiqueta que se mostrará sobre el subpanel',

// Dropdown lists
'LBL_JS_DELETE_REQUIRED_DDL_ITEM' => '¿Está seguro de que desea eliminar este elemento requerido de la lista desplegable? Esto puede afectar la funcionalidad de la aplicación.',

// Specific dropdown list should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_DDL_NAME)
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_SALES_STAGE_DOM' => '¿Está seguro de que desea eliminar este elemento de la lista desplegable? La eliminación de las etapas Cerradas Perdidas o Cerradas Ganadas causará que el módulo de Previsión no funcione correctamente',

// Specific list items should be:
// LBL_JS_DELETE_REQUIRED_DDL_ITEM_(UPPERCASE_ITEM_NAME)
// Item name should have all special characters removed and spaces converted to
// underscores
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_NEW' => '¿Está seguro de que desea borrar el estatus de Nuevas Ventas? Borrar el estatus puede causar un funcionamiento no adecuado de los elementos de la Línea de Ingresos del módulo de Oportunidades.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_IN_PROGRESS' => '¿Está seguro de que desea borrar el estatus de Progreso de las ventas? Borrar el estatus puede causar el funcionamiento no adecuado de los elementos de la Línea de Ingresos del módulo de Oportunidades.',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_WON' => '¿Está seguro de que desea eliminar la etapa de ventas Cerradas Ganadas? Eliminar esta etapa hará que el módulo de Previsión no funcione correctamente',
'LBL_JS_DELETE_REQUIRED_DDL_ITEM_CLOSED_LOST' => '¿Está seguro de que desea eliminar la etapa de ventas Cerradas Perdidas? Eliminar esta etapa hará que el módulo de Previsión no funcione correctamente',

//CONFIRM
'LBL_CONFIRM_FIELD_DELETE'=>'Deleting this custom field will delete both the custom field and all the data related to the custom field in the database. The field will be no longer appear in any module layouts.'
        . ' If the field is involved in a formula to calculate values for any fields, the formula will no longer work.'
        . '\\n\\nThe field will no longer be available to use in Reports; this change will be in effect after logging out and logging back in to the application. Any reports containing the field will need to be updated in order to be able to be run.'
        . '\\n\\nDo you wish to continue?',
'LBL_CONFIRM_RELATIONSHIP_DELETE'=>'¿Está seguro de que desea eliminar esta relación?
<br>Nota: Esta operación puede demorar unos minutos.',
'LBL_CONFIRM_RELATIONSHIP_DEPLOY'=>'Esto hará la relación permantente. ¿Está seguro de que desea desplegar esta relación?',
'LBL_CONFIRM_DONT_SAVE' => 'Hay cambios que todavía no han sido guardados, ¿desea guardarlos ahora?',
'LBL_CONFIRM_DONT_SAVE_TITLE' => '¿Guardar Cambios?',
'LBL_CONFIRM_LOWER_LENGTH' => 'Los datos pueden ser truncados y esto no podrá deshacerse, ¿está seguro de que desea continuar?',

//POPUP HELP
'LBL_POPHELP_FIELD_DATA_TYPE'=>'Seleccione el tipo de datos apropiado acorde con el tipo de datos que será introducido en el campo.',
'LBL_POPHELP_FTS_FIELD_CONFIG' => 'Configure el campo para búsquedas de texto completo.',
'LBL_POPHELP_FTS_FIELD_BOOST' => 'Impulsar es el proceso para mejorar la pertinencia de los campos de un registro. <br /> Los campos con un nivel de impulso más alto recibirán mayor peso cuando se realiza la búsqueda. Cuando se realiza una búsqueda, la coincidencia de registros que contienen campos con un peso mayor aparecerá más arriba en los resultados de la búsqueda. <br /> El valor predeterminado es 1,0, lo que vendría a ser un impulso neutro. Para aplicar un impulso positivo se acepta cualquier valor flotante superior a 1. Para aplicar un impulso negativo utilice valores inferiores a 1. Por ejemplo, un valor de 1,35 impulsará positivamente un campo en un 135 %. Si utiliza un valor de 0,60 se aplicará un impulso negativo. <br/>Tenga en cuenta que en las versiones anteriores era necesario realizar un reindexado de búsqueda de texto completo. Esto ya no es necesario.',
'LBL_POPHELP_IMPORTABLE'=>'<b>Sí</b>: El campo será incluido en una operación de importación.<br><b>No</b>: El campo no será incluido en una importación.<br><b>Requerido</b>: Se debe suministrar un valor para el campo en toda importación.',
'LBL_POPHELP_PII'=>'Este campo se marcará automáticamente para realizar una auditoría y estará disponible en la visualización de Información personal.<br>Los campos de información personal se pueden borrar permanentemente cuando el registro esté relacionado con una solicitud de borrado por privacidad de datos.<br>La eliminación se realiza mediante el módulo de Privacidad de datos, y la pueden ejecutar los administradores o los usuarios con el rol de Administrador de privacidad de datos.',
'LBL_POPHELP_IMAGE_WIDTH'=>'Introduzca un número para el Ancho, como medida en píxeles.<br> La imagen subida se ajustará a ese Ancho.',
'LBL_POPHELP_IMAGE_HEIGHT'=>'Introduzca un número para la Altura, como medida en píxeles.<br> La imagen subida se ajustará a esa Altura.',
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
'LBL_POPHELP_GLOBAL_SEARCH'=>'Seleccione esta opción para utilizar este campo para la búsqueda de registros desde la Búsqueda Global en este módulo.',
//Revert Module labels
'LBL_RESET' => 'Restablecer',
'LBL_RESET_MODULE' => 'Restablecer Módulo',
'LBL_REMOVE_CUSTOM' => 'Quitar Personalizaciones',
'LBL_CLEAR_RELATIONSHIPS' => 'Limpiar Relaciones',
'LBL_RESET_LABELS' => 'Restablecer Eqiquetas',
'LBL_RESET_LAYOUTS' => 'Restablecer Diseños',
'LBL_REMOVE_FIELDS' => 'Quitar Campos Personalizados',
'LBL_CLEAR_EXTENSIONS' => 'Limpiar Extensiones',

'LBL_HISTORY_TIMESTAMP' => 'Registro de la Hora',
'LBL_HISTORY_TITLE' => 'historial',

'fieldTypes' => array(
                'varchar'=>'Campo de Texto',
                'int'=>'Entero',
                'float'=>'Flotante',
                'bool'=>'Casilla de Verificación',
                'enum'=>'Lista Desplegable',
                'multienum' => 'Selección Múltiple',
                'date'=>'Fecha',
                'phone' => 'Teléfono',
                'currency' => 'Moneda',
                'html' => 'HTML',
                'radioenum' => 'Radio',
                'relate' => 'Relacionado',
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

'LBL_ILLEGAL_FIELD_VALUE' =>"Las claves de una lista desplegable no pueden contener comillas.",
'LBL_CONFIRM_SAVE_DROPDOWN' =>"Está seleccionando este elemento para su eliminación de la lista desplegable. Cualquier campo desplegable que use esta lista con este elemento como valor ya no mostrará dicho valor, y el valor ya no podrá seleccionarse en los campos desplegables. ¿Está seguro de que desea continuar?",
'LBL_POPHELP_VALIDATE_US_PHONE'=>"Select to validate this field for the entry of a 10-digit<br>" .
                                 "phone number, with allowance for the country code 1, and<br>" .
                                 "to apply a U.S. format to the phone number when the record<br>" .
                                 "is saved. The following format will be applied: (xxx) xxx-xxxx.",
'LBL_ALL_MODULES'=>'Todos los Módulos',
'LBL_RELATED_FIELD_ID_NAME_LABEL' => '{0} (relacionado {1} ID)',
'LBL_HEADER_COPY_FROM_LAYOUT' => 'Copiar del diseño',
);
