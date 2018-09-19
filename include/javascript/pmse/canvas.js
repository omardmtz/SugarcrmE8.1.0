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
/*global jCore, $, HiddenField, TextareaField, TextField, ItemMatrixField,
 PROJECT_LOCKED_VARIABLES, SUGAR_URL, RestProxy, ComboboxField, adamUID,
 PROJECT_MODULE, project, MessagePanel, PROJECT_LOCKED_VARIABLES, PMSE.Form, PMSE.Window,
 PMSE.Menu, AdamContainerDropBehavior, AdamProject, Tree, translate, sprintf, LabelField,
 PMSE_DESIGNER_FORM_TRANSLATIONS
*/
var PMSE = PMSE || {};
/**
 * @class AdamCanvas
 * Class to handle the designer canvas
 *
 * @constructor
 * Creates a new AdamCanvas object
 * @param {Object} options
 */
var AdamCanvas = function (options) {
    jCore.Canvas.call(this, options);
    /**
     * Diagram ID
     * @type {null}
     */
    this.dia_id = null;
    /**
     * Project ID
     * @type {String}
     */
    this.projectUid = "";
    /**
     * Asssociation with current Project
     * @type {AdamProject}
     */
    this.project = null;

    this.currentMenu = null;

    this.modal = null;

    this.isClicked = false;

    /**
     * BPMN General Rules for validations,
     * @type {object}
     *
     */
    this.bpmnRules = {
        AdamEvent : {
            start :[
                {
                    id : '106107',
                    type: 1,
                    family: 6,
                    familyType: 1,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_START_EVENT_OUTGOING'),
                    rules: [
                        {
                            compare: '>',
                            value: 0,
                            direction: 'outgoing',
                            element: 'sequenceFlow'
                        }
                    ]
                }
            ],
            end: [
                {
                    id : '106108',
                    type: 1,
                    family: 6,
                    familyType: 3,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_END_EVENT_INCOMING'),
                    rules: [
                        {
                            compare: '>',
                            value: 0,
                            direction: 'incoming',
                            element: 'sequenceFlow'
                        }
                    ]
                }
            ],
            intermediate: [
                {
                    id : '106109',
                    type: 1,
                    family: 6,
                    familyType: 2,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_INTERMEDIATE_EVENT_INCOMING'),
                    rules: [
                        {
                            compare: '>',
                            value: 0,
                            direction: 'incoming',
                            element: 'sequenceFlow'
                        }
                    ]
                },
                {
                    id : '106112',
                    type: 1,
                    family: 6,
                    familyType: 2,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_INTERMEDIATE_EVENT_OUTGOING'),
                    rules: [
                        {
                            compare: '=',
                            value: 1,
                            direction: 'outgoing',
                            element: 'sequenceFlow'
                        }
                    ]
                }
            ],
            boundary: [
                {
                    id : '106115',
                    type: 1,
                    family: 6,
                    familyType: 4,
                    familySubType: 1,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_BOUNDARY_EVENT_OUTGOING'),
                    rules: [
                        {
                            compare: '=',
                            value: 1,
                            direction: 'outgoing',
                            element: 'sequenceFlow'

                        }
                    ]
                }
            ]
        },
        AdamActivity : {
            task: [
                {
                    id : '105101',
                    type: 1,
                    family: 5,
                    familyType: 1,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_ACTIVITY_INCOMING'),
                    rules: [
                        {
                            compare: '>',
                            value: 0,
                            direction: 'incoming',
                            element: 'sequenceFlow'
                        }
                    ]
                },
                {
                    id : '105102',
                    type: 1,
                    family: 5,
                    familyType: 1,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_ACTIVITY_OUTGOING'),
                    rules: [
                        {
                            compare: '>',
                            value: 0,
                            direction: 'outgoing',
                            element: 'sequenceFlow'
                        }
                    ]
                }
            ],
            scripttask: [
                {
                    id : '105101',
                    type: 1,
                    family: 5,
                    familyType: 1,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_ACTIVITY_INCOMING'),
                    rules: [
                        {
                            compare: '>',
                            value: 0,
                            direction: 'incoming',
                            element: 'sequenceFlow'
                        }
                    ]
                },
                {
                    id : '105102',
                    type: 1,
                    family: 5,
                    familyType: 1,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_ACTIVITY_OUTGOING'),
                    rules: [
                        {
                            compare: '>',
                            value: 0,
                            direction: 'outgoing',
                            element: 'sequenceFlow'
                        }
                    ]
                },
                {
                    id : '105103',
                    type: 1,
                    family: 5,
                    familyType: 1,
                    familySubType: 1,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_ACTIVITY_SCRIPT_TASK'),
                    rules: [
                        {
                            compare: '!=',
                            value: '',
                            type: 'script_type'
                        }
                    ]
                }
            ]
        },
        AdamGateway: {
            diverging : [
                {
                    id : '107101',
                    type: 1,
                    family: 7,
                    familyType: 1,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_GATEWAY_DIVERGING_INCOMING'),
                    rules: [
                        {
                            compare: '>=',
                            value: 1,
                            direction: 'incoming',
                            element: 'sequenceFlow'
                        }
                    ]
                },
                {
                    id : '107102',
                    type: 1,
                    family: 7,
                    familyType: 1,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_GATEWAY_DIVERGING_OUTGOING'),
                    rules: [
                        {
                            compare: '>',
                            value: 1,
                            direction: 'outgoing',
                            element: 'sequenceFlow'
                        }
                    ]
                }
            ],
            converging : [
                {
                    id : '107201',
                    type: 1,
                    family: 7,
                    familyType: 2,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_GATEWAY_CONVERGING_INCOMING'),
                    rules: [
                        {
                            compare: '>',
                            value: 1,
                            direction: 'incoming',
                            element: 'sequenceFlow'
                        }
                    ]
                },
                {
                    id : '107202',
                    type: 1,
                    family: 7,
                    familyType: 2,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_GATEWAY_CONVERGING_OUTGOING'),
                    rules: [
                        {
                            compare: '=',
                            value: 1,
                            direction: 'outgoing',
                            element: 'sequenceFlow'
                        }
                    ]
                }
            ],
            mixed : [
                {
                    id : '107301',
                    type: 1,
                    family: 7,
                    familyType: 3,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_GATEWAY_MIXED_INCOMING'),
                    rules: [
                        {
                            compare: '>',
                            value: 1,
                            direction: 'incoming',
                            element: 'sequenceFlow'
                        }
                    ]
                },
                {
                    id : '107302',
                    type: 1,
                    family: 7,
                    familyType: 3,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_GATEWAY_MIXED_OUTGOING'),
                    rules: [
                        {
                            compare: '>',
                            value: 1,
                            direction: 'outgoing',
                            element: 'sequenceFlow'
                        }
                    ]
                }
            ]

        }/*,
        AdamArtifact: {
            textannotation : [
                {
                    id : '109101',
                    type: 1,
                    family: 9,
                    familyType: 1,
                    familySubType: 0,
                    action: 1,
                    message: translate('LBL_PMSE_MESSAGE_ERROR_ANNOTATION'),
                    rules: [
                        {
                            compare: '>',
                            value: 0,
                            direction: 'none',
                            element: 'associationLine'
                        }
                    ]
                }
            ]
        }*/
    };

    AdamCanvas.prototype.initObject.call(this, options);
};
AdamCanvas.prototype = new jCore.Canvas();
/**
 * Object Type
 * @type {String}
 */
AdamCanvas.prototype.type = "AdamCanvas";
/**
 * Returns the project id
 * @return {String}
 */
AdamCanvas.prototype.getProjectUid = function () {
    return this.projectUid;
};
/**
 * Returns the type of element
 * @return {String}
 */
AdamCanvas.prototype.getType = function () {
    return this.type;
};
/**
 * Set the diagram id
 * @param {String} id
 * @return {*}
 */
AdamCanvas.prototype.setDiaUid = function (id) {
    this.dia_id = id;
    return this;
};
/**
 * Set the project id
 * @param value
 * @return {*}
 */
AdamCanvas.prototype.setProjectUid = function (value) {
    this.projectUid = value;
    return this;
};
/**
 * Asssociate the AdamProject Object
 * @param {AdamProject} value
 * @return {*}
 */
AdamCanvas.prototype.setProject = function (value) {
    this.project = value;
    return this;
};

AdamCanvas.prototype.setCurrentMenu = function (obj) {
    if (this.currentMenu) {
        this.currentMenu.hide();
    }
    this.currentMenu = obj;
    return this;
};

/**
 * Initialize the default options
 * @param {Object} options
 */
AdamCanvas.prototype.initObject = function (options) {
    var defaultOptions = {
        projectUid : null
    };
    this.modal = new PMSE.Modal();
    $.extend(true, defaultOptions, options);
    this.setProjectUid(defaultOptions.projectUid)
        .setDiaUid(defaultOptions.dia_id);
};
AdamCanvas.prototype.showModal = function () {
    this.modal.show();
    return this;
};
AdamCanvas.prototype.hideModal = function () {
    this.modal.hide();
    return this;
};
/**
 * Extends the JCoreObject property to configure the context menus
 * @return {Array}
 */
AdamCanvas.prototype.getContextMenu = function () {
    var f, w,
        hiddenTerminateField,
        hiddenNameModule,
        itemMatrix,
        fnTerminateFields,
        fieldsItems,
        processName,
        processDescription,
        comboModulesFields,
        comboModules,
        comboOperators,
        criteriaField,
        proxyModule,
        callbackModule,
        saveAction,
        refreshAction,
        zoom50Action,
        zoom75Action,
        zoom100Action,
        zoom125Action,
        zoom150Action,
        wAlert,
        fAlert,
        proOldModuleField,
        oldModule,
        proxyConfirm,
        proModuleField,
        alertLabel,
        message,
        result,
        mp2,
        modules,
        data,
        errorModulem,
        checkModuleAndSaveData,
        cancelInformation,
        proLockedFieldBKP,
        url;

    /** FORM MODULES **/
    hiddenNameModule = new HiddenField({name: 'pro_module'});

    processName = new TextField({
        name: 'prj_name',
        label: translate('LBL_PMSE_LABEL_PROCESS_NAME'),
        required: true
    });
    processDescription = new TextareaField({
        name: 'prj_description',
        label: translate('LBL_PMSE_LABEL_DESCRIPTION')
    });

    itemMatrix = new ItemMatrixField({
        jtype: 'itemmatrix',
        label: translate('LBL_PMSE_LABEL_LOCKED_FIELDS'),
        name: 'pro_locked_variables',
        submit: true,
        fieldWidth: 350,
        fieldHeight: 90,
        visualStyle : 'table',
        nColumns: 2
    });
    criteriaField = new CriteriaField({
        name: 'pro_terminate_variables',
        label: translate('LBL_PMSE_LABEL_TERMINATE_PROCESS'),
        dateFormat: App.date.getUserDateFormat(),
        timeFormat: App.user.getPreference("timepref"),
        required: false,
        fieldWidth: 414,
        fieldHeight: 80,
        decimalSeparator: SUGAR.App.config.defaultDecimalSeparator,
        numberGroupingSeparator: SUGAR.App.config.defaultNumberGroupingSeparator,
        currencies: project.getMetadata("currencies"),
        operators: {
            logic: true,
            group: true
        },
        constant: false/*,
        decimalSeparator: PMSE_DECIMAL_SEPARATOR*/
    });

    fieldsItems = function (value, initial) {
        App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
        var val = new SugarProxy({
            url: 'pmse_Project/CrmData/fields/' + value,
            //restClient: this.canvas.project.restClient,
            uid: '',
            callback: null
        }),
            modulesFields;
         val.getData({call_type:'RR'}, {
            success: function (modulesFields) {
                hiddenNameModule.setValue(value);
                if (initial !== undefined) {
                    var lockedFields = AdamCanvas.prototype.expandLockedFields(
                                           PROJECT_LOCKED_VARIABLES,
                                           modulesFields.groupFieldsMap
                                       );
                    itemMatrix.setList(modulesFields.result, lockedFields);
                } else {
                    itemMatrix.setList(modulesFields.result);
                }
                App.alert.dismiss('upload');
                w.html.style.display = 'inline';
            }
        });

    };

    comboModules = new ComboboxField({
        jtype: 'combobox',
        label: translate('LBL_PMSE_FORM_LABEL_MODULE'),
        name: 'comboModules',
        submit: false,
        readOnly: true,
        change: function () {
            return fieldsItems(this.value);
        },
        proxy: new SugarProxy({
            url: 'pmse_Project/CrmData/modules',
            //restClient: this.canvas.project.restClient,
            uid: '',
            callback: null
        })
    });

    proxyModule = new SugarProxy({
        url: 'pmse_Project/CrmData/project/' + adamUID,
        //restClient: this.canvas.project.restClient,
        uid: adamUID,
        callback: null
    });

    callbackModule = {
        'loaded' : function (data) {
            App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
            //w.style.display = 'none';
            //$('.adam-window').hide();
            var arrOperator = [
                {'value': 'equal', 'text': '='}
            ],
                modules;

            var options = [];
            criteriaField.setModuleEvaluation({
                dataURL: "pmse_Project/CrmData/related/" + PROJECT_MODULE,
                dataRoot: "result",
                fieldDataURL: 'pmse_Project/CrmData/fields/{{MODULE}}',
                fieldDataRoot: "result"
            });
            processName.setValue(project.name);

            //modulesList = App.metadata.getModules();
            //for( var property in modulesList ){
            //    if (modulesList[property].favoritesEnabled) {
            //        options.push({'value': property, 'text': property});
            //    }
            //}
            //options.sort(function(a, b){
            //    var nameA=a.text.toLowerCase(), nameB=b.text.toLowerCase();
            //    if (nameA < nameB) //sort string ascending
            //        return -1
            //    if (nameA > nameB)
            //        return 1
            //    return 0 //default return value (no sorting)
            //});
            //comboModules.setOptions(options);
            //comboModules.setValue(PROJECT_MODULE || options[0].value);

            comboModules.proxy.getData(null, {
                success: function (modules) {
                    comboModules.setOptions(modules.result);
                    comboModules.setValue(PROJECT_MODULE || modules.result[0].value);
                    processName.setValue(project.name);
                    processDescription.setValue(project.description);
                    criteriaField.setValue(project.process_definition.pro_terminate_variables);
                    PROJECT_LOCKED_VARIABLES = project.process_definition.pro_locked_variables.slice();
                    fieldsItems(PROJECT_MODULE || modules.result[0].value, true);
                    itemMatrix.setLockedFields(PROJECT_LOCKED_VARIABLES);
                    oldModule = comboModules.value;

                }
            });

        },
        'submit' : function (data) {
            if (processName.value !== project.name) {
                url = App.api.buildURL('pmse_Project', null, null, {
                    filter: [{'name':processName.value}]
                });
                App.api.call("read", url, null, {
                    success:function (a) {
                      if (a.records.length === 0) {
                            checkModuleAndSaveData(data);
                        } else {
                            var mp = new MessagePanel({
                                title: 'Error',
                                wtype: 'Error',
                                message: translate('LBL_PMSE_MESSAGE_THEPROCESSNAMEALREADYEXISTS', 'pmse_Project', processName.value)//response.message
                            });
                            mp.show();
                        }
                    }
                });
            } else {
                checkModuleAndSaveData(data);
            }
        }
    };

    checkModuleAndSaveData = function (oldData) {

        if (comboModules.value !== oldModule) {
            //PROJECT_LOCKED_VARIABLES_BPK = oldData.pro_locked_variables;
            proLockedFieldBKP = oldData.pro_locked_variables;
            PROJECT_LOCKED_VARIABLES = itemMatrix.getLockedField();
            mp2.show();
        } else {
            data = {
                description: processDescription.value,
                pro_terminate_variables:criteriaField.value,
                pro_locked_variables: oldData.pro_locked_variables
            };
            if (processName.value !== null && processName.value !== '') {
                data = {
                    name: processName.value,
                    description: processDescription.value,
                    pro_locked_variables: oldData.pro_locked_variables,
                    pro_terminate_variables:criteriaField.value
                };
                project.setName(PROJECT_NAME = processName.value);
            }

            project.process_definition.pro_terminate_variables=criteriaField.value;
            project.setDescription(PROJECT_DESCRIPTION = processDescription.value);
            proxyModule.sendData(data);
            //LOCKED VARIABLES
            PROJECT_LOCKED_VARIABLES = itemMatrix.getLockedField();
            project.process_definition.pro_locked_variables=itemMatrix.getLockedField();
            w.close();
        }
    };

    proxyConfirm = new SugarProxy({
        url: 'pmse_Project/CrmData/putData/' + adamUID,
        //restClient: this.canvas.project.restClient,
        uid: adamUID,
        callback: null
    });
    //proxyConfirm.restClient.setRestfulBehavior(SUGAR_REST);
    //if (!SUGAR_REST) {
    //    proxyConfirm.restClient.setBackupAjaxUrl(SUGAR_AJAX_URL);
    //}

    proModuleField = new HiddenField({
        name: 'pro_new_module'
    });
    proOldModuleField = new HiddenField({
        name: 'pro_old_module'
    });
    alertLabel  = new LabelField({
        name: 'lblAlert',
        label: translate('LBL_PMSE_FORM_LABEL_THE_WARNING'),
        options: {
            marginLeft : 35
        }
    });


    mp2 = new MessagePanel({
        title: "Module change warning",
        wtype: 'Confirm',
        message: translate('LBL_PMSE_MESSAGE_REMOVE_ALL_START_CRITERIA'),
        buttons: [
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_OK'),
                handler: function () {
                    data = {
                        prj_name: processName.value,
                        prj_description: processDescription.value,
                        pro_locked_variables: proLockedFieldBKP,
                        pro_module: comboModules.value
                    };
                    project.setDescription(PROJECT_DESCRIPTION = processDescription.value);
                    project.setName(PROJECT_NAME = processName.value);
                    proxyModule.sendData(data);
                    //NAME MODULE
                    PROJECT_MODULE = comboModules.value;
                    //LOCKED VARIABLES
                    //PROJECT_LOCKED_VARIABLES = itemMatrix.getLockedField();
                    project.canvas.cleanAllFlowConditions();
                    //Submit change modules
                    data = {
                        pro_new_module: PROJECT_MODULE,
                        pro_old_module: oldModule
                    };
                    proxyConfirm.sendData(data, {
                        //success: function (xhr, response) {
                        success: function (response) {
                            //TODO SUCCESS ALERT
                            if (!response.success) {
                                errorModule = new MessagePanel({
                                    title: "Error",
                                    wtype: 'Error',
                                    message: translate('LBL_PMSE_ADAM_ENGINE_ERROR_UPDATEBPMFLOW')
                                });
                                errorModule.show();
                            } else {
                                w.close();
                            }

                        },
                        failure: function (xhr, response) {
                            //TODO FAILURE ALERT
                        }
                    });
                    mp2.hide();
                }
            },
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                handler: function () {
                    comboModules.removeOptions();
                    comboModules.proxy.getData(null, {
                        success: function (modules) {
                            processName.setValue(project.name);
                            processDescription.setValue(project.description);
                            comboModules.setOptions(modules.result);
                            comboModules.setValue(oldModule);
                            criteriaField.setValue(project.process_definition.pro_terminate_variables);
                            PROJECT_LOCKED_VARIABLES = project.process_definition.pro_locked_variables.slice();
                            fieldsItems(PROJECT_MODULE || modules.result[0].value, true);
                            itemMatrix.setLockedFields(PROJECT_LOCKED_VARIABLES);
                            oldModule = PROJECT_MODULE;
                            mp2.hide();

                        }}


                    );

                }
            }
        ]
    });

    f = new PMSE.Form({
        items: [
            processName,
            processDescription,
            comboModules,
            criteriaField,
            itemMatrix,
            hiddenNameModule
        ],
        //closeContainerOnSubmit: true,
        buttons: [
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_SAVE'),
                handler: function () {
                    f.submit();
                },
                cssClasses: ['btn', 'btn-primary']
            },
            {
                jtype: 'normal',
                caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                handler: function () {
                    if (f.isDirty()) {
                        cancelInformation =  new MessagePanel({
                            title: "Confirm",
                            wtype: 'Confirm',
                            message: translate('LBL_PMSE_MESSAGE_CANCEL_CONFIRM'),
                            buttons: [
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_YES'),
                                    handler: function () {
                                        PROJECT_LOCKED_VARIABLES = project.process_definition.pro_locked_variables.slice();
                                        cancelInformation.close();
                                        w.close();
                                    }
                                },
                                {
                                    jtype: 'normal',
                                    caption: translate('LBL_PMSE_BUTTON_NO'),
                                    handler: function () {
                                        cancelInformation.close();
                                    }
                                }
                            ]
                        });
                        cancelInformation.show();
                    } else {
                        w.close();
                    }
                },
                cssClasses: ['btn btn-invisible btn-link']
            }
        ],
        callback: callbackModule,
        proxy: null,
        language: PMSE_DESIGNER_FORM_TRANSLATIONS
    });

    w = new PMSE.Window({
        width: 690,
        height: 450,
        modal: true,
        title: translate('LBL_PMSE_CONTEXT_MENU_PROCESS_DEFINITION')
    });
    w.addPanel(f);
    /** END FORM MODULES **/

    saveAction  = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_SAVE'),
        cssStyle : 'adam-menu-icon-save',
        handler: function () {
            project.save();
            jCore.getActiveCanvas().RemoveCurrentMenu();
        },
        disabled: !project.isDirty
    });

    refreshAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_REFRESH'),
        cssStyle : 'adam-menu-icon-refresh',
        handler: function () {
            document.location.reload(true);
        }
    });

    zoom50Action = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_50'),
        cssStyle : '',
        handler: function () {
            jCore.getActiveCanvas().applyZoom(1);
            $('#zoom').val(1);
        },
        selected: (jCore.getActiveCanvas().getZoomFactor() === 0.5)
    });

    zoom75Action = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_75'),
        cssStyle : '',
        handler: function () {
            jCore.getActiveCanvas().applyZoom(2);
            $('#zoom').val(2);
        },
        selected: (jCore.getActiveCanvas().getZoomFactor() === 0.75)
    });

    zoom100Action = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_100'),
        cssStyle : '',
        handler: function () {
            jCore.getActiveCanvas().applyZoom(3);
            $('#zoom').val(3);
        },
        selected: (jCore.getActiveCanvas().getZoomFactor() === 1)
    });

    zoom125Action = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_125'),
        cssStyle : '',
        handler: function () {
            jCore.getActiveCanvas().applyZoom(4);
            $('#zoom').val(4);
        },
        selected: (jCore.getActiveCanvas().getZoomFactor() === 1.25)
    });

    zoom150Action = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_150'),
        cssStyle : '',
        handler: function () {
            jCore.getActiveCanvas().applyZoom(5);
            $('#zoom').val(5);
        },
        selected: (jCore.getActiveCanvas().getZoomFactor() === 1.5)
    });

    return {
        items: [
            new PMSE.Action({
                text: translate('LBL_PMSE_CONTEXT_MENU_PROCESS_DEFINITION'),
                cssStyle: 'adam-menu-icon-configure',
                handler : function () {
                    w.show();
                    w.html.style.display = 'none';
                }
            }),
            {
                jtype: 'separator'
            },
            saveAction,
            refreshAction,
            {
                label: translate('LBL_PMSE_CONTEXT_MENU_ZOOM'),
                icon: 'adam-menu-icon-zoom',
                menu: {
                    items: [
                        zoom50Action,
                        zoom75Action,
                        zoom100Action,
                        zoom125Action,
                        zoom150Action
                    ]
                }

            }
        ]
    };
};

/**
 * Set the context menu creation
 * @param {Object} element
 * @param {Number} x
 * @param {Number} y
 */
AdamCanvas.prototype.onRightClickHandler = function (element, x, y) {
    var contextMenu, factoryCMenu;
    factoryCMenu = element.getContextMenu();
    if (factoryCMenu.items) {
        factoryCMenu.canvas = this;
        contextMenu = new PMSE.Menu(factoryCMenu);
        contextMenu.setParent(element);
        contextMenu.show(x, y);
    } else {
        this.RemoveCurrentMenu();
    }
    //element.preventDefault();
};
/**
 * Create a dropBehaviorFactory to insert the custom DropBehaviors
 * @param type
 * @param selectors
 * @return {*}
 */
AdamCanvas.prototype.dropBehaviorFactory = function (type, selectors) {
    var out;
    if (type === 'container') {
        if (!this.containerDropBehavior) {
            this.containerDropBehavior = new AdamContainerDropBehavior(selectors);
        }
        out = this.containerDropBehavior;
    } else {
        out = jCore.BehavioralElement.prototype.dropBehaviorFactory.call(this, type, selectors);
    }
    return out;
};

/**
 * Define the action when the element is created into the canvas
 * @param {Object} element
 */
AdamCanvas.prototype.onCreateElementHandler = function (element) {
    this.RemoveCurrentMenu();
    if (this.project instanceof AdamProject) {
        this.project.addElement(element);

        var items2 = this.getDiagramTree();
        Tree.treeReload('tree', items2);
    }
    this.bpmnValidation();
};
/**
 *  Define the action when the element is updated into the canvas
 * @param element
 */
AdamCanvas.prototype.onChangeElementHandler = function (element) {
    if (this.project instanceof AdamProject) {
        this.project.updateElement(element);

        var items2 = this.getDiagramTree();
        Tree.treeReload('tree', items2);
        if (element.length === 1) {
            this.project.updatePropertiesGrid(
                element[0].type !== 'Connection' ? this.customShapes.find('id', element[0].id)
                    : this.connections.find('id', element[0].id)
            );
        }
    }
};
/**
 * Define the action when the element is deleted from the canvas
 * @param element
 */
AdamCanvas.prototype.onRemoveElementHandler = function (element) {
    var i, items, sizeItems, item;
    if (this.project instanceof AdamProject) {
        this.project.removeElement(element);

        var items2 = this.getDiagramTree();
        Tree.treeReload('tree', items2);
        this.project.updatePropertiesGrid();
    }
    if (listPanelError){
        if (listPanelError.items.length){
            for ( i = 0 ; i < element.length ; i+=1 ) {
                if (!(element[i].type === "Connection")){
                    item = listPanelError.getItemById(element[i].getID());
                    if (item){
                        listPanelError.removeItemById(element[i].getID());
                    }
                }
            }
        }
    }
    this.bpmnValidation();
    if (countErrors){
        if (listPanelError.getItems().length){
            $("#error-div").show();
            countErrors.style.display = "block";
            sizeItems = listPanelError.getAllErros();
            countErrors.textContent = sizeItems === 1 ? sizeItems + translate('LBL_PMSE_BPMN_WARNING_SINGULAR_LABEL') : sizeItems + translate('LBL_PMSE_BPMN_WARNING_LABEL');
        } else {
            countErrors.textContent = "0" + translate('LBL_PMSE_BPMN_WARNING_SINGULAR_LABEL');
            $("#error-div").hide();
        }
    }
};
/**
 * Throws an event when the CommandAdam is executed
 * @param {Object} receiver
 * @param {Array} propertyNames
 * @param {Array} oldValues
 * @param {Array} newValues
 */
AdamCanvas.prototype.triggerCommandAdam = function (receiver, propertyNames, oldValues, newValues) {
    var fields = [],
        i;

    for (i = 0; i < propertyNames.length; i += 1) {
        fields.push({
            field: propertyNames[i],
            newVal: newValues[i],
            oldVal: oldValues[i]
        });
    }

    this.updatedElement = [{
        fields: fields,
        id: receiver.id,
        relatedObject: receiver,
        type: receiver.type,
        adam: true
    }];
    $(this.html).trigger('changeelement');
};

AdamCanvas.prototype.triggerFlowConditionChangeEvent = function (element, oldValues) {
    this.updatedElement = [{
        id: element.id,
        type: element.type,
        fields: [{
            field: "condition",
            oldVal: oldValues.condition,
            newVal: element.getFlowCondition()
        },
            {
                field: "type",
                oldVal: oldValues.type,
                newVal: element.getFlowType()
            }]
    }];
    $(this.html).trigger('changeelement');
};

/**
 * Overwrite XXX
 * @param {Object} element
 * @param {String} oldText
 * @param {String} newText
 */
AdamCanvas.prototype.triggerTextChangeEvent = function (element, oldText, newText) {
    var valid, reg, e, nText, mp;
    reg = /<[^\s]/g;
    nText = newText.trim();
    e = reg.test(nText);
    if (e) {
        nText = nText.replace(/</g, '< ');
    }
    valid = this.validateName(element, nText);
    if (!valid.valid) {
        element.parent.updateLabelsPosition(true, true);
        element.parent.setName(oldText);
        mp = new MessagePanel({
            title: 'Error',
            wtype: 'Error',
            message: valid.message
        });
        mp.show();
        return;
    }

    this.updatedElement = [{
        id : element.parent.id,
        type : element.parent.type,
        relatedObject: element.parent,
        fields : [{
            field : "name",
            oldVal : oldText,
            newVal : nText
        }]
    }];
    element.parent.setName(nText);
    $(this.html).trigger("changeelement");
};

AdamCanvas.prototype.triggerDefaultFlowChangeEvent = function (elements) {
    this.updatedElement = elements;
    $(this.html).trigger("changeelement");
};

AdamCanvas.prototype.triggerConnectionConditionChangeEvent = function (element, fields) {
    this.updatedElement = [{
        id : element.id,
        type : element.type,
        relatedObject: element,
        fields : fields
    }];
    $(this.html).trigger("changeelement");
};

/**
 * Fires the {@link Canvas#event-changeelement} event, and elaborates the structure of the object that will
 * be passed to the handlers, the structure contains the following fields (considering old values and new values):
 *
 * - x
 * - y
 * - parent (the shape that is parent of this shape)
 * - state (of the connection)
 *
 * @param {Port} port The port updated
 * @chainable
 */

AdamCanvas.prototype.triggerPortChangeEvent = function (port) {
    // check if this port is source or dest
    var direction = port.connection.srcPort.getID() === port.getID() ?
            "src" : "dest",
        map = {
            src: {
                x: "x1",
                y: "y1",
                parent: "element_origin",
                type: 'element_origin_type'
            },
            dest: {
                x: "x2",
                y: "y2",
                parent: "element_dest",
                type: 'element_dest_type'
            }
        },
        point,
        state,
        zomeedState = [],
        i;

    // save the points of the new connection
    port.connection.savePoints();
    state = port.connection.getPoints();

    for (i = 0; i < state.length; i += 1) {
        point = port.connection.points[i];
        zomeedState.push(new jCore.Point(point.x / this.zoomFactor, point.y / this.zoomFactor));
    }
    point = direction === "src" ? zomeedState[0] : zomeedState[state.length - 1];

    this.updatedElement = [{
        id: port.connection.getID(),
        type: port.connection.type,
        fields: [
            {
                field: map[direction].x,
                oldVal: point.x,        // there's no old value
                newVal: point.x
            },
            {
                field: map[direction].y,
                oldVal: point.y,        // there's no old value
                newVal: point.y
            },
            {
                field: map[direction].parent,
                oldVal: (port.getOldParent()) ? port.getOldParent().getID() : null,
                newVal: port.getParent().getID()
            },
            {
                field: map[direction].type,
                oldVal: port.connection.getNativeType(port.getParent()).type,
                newVal: port.connection.getNativeType(port.getParent()).type
            },
            {
                field: "state",
                oldVal: port.connection.getOldPoints(),
                newVal: zomeedState
            },
            {
                field: "condition",
                oldVal: "",
                newVal: port.connection.getFlowCondition()
            }
        ],
        relatedObject: port
    }];
//    this.triggerConnectionStateChangeEvent(port.connection);
    $(this.html).trigger('changeelement');
};

AdamCanvas.prototype.RemoveCurrentMenu = function () {
    if (this.currentMenu) {
        this.currentMenu.hide();
    }
};

AdamCanvas.prototype.onSelectElementHandler = function (element) {
    this.hideAllFocusedLabels();
    this.project.onCanvasClick();
    this.RemoveCurrentMenu();
};


/**
 * @event rightclick
 * Handler for the custom event rightclick, this event fires when an element
 * has been right clicked. It executes the hook #onRightClickHandler
 * @param {Canvas} canvas
 */
AdamCanvas.prototype.onRightClick = function (canvas) {
    return function (event, e, element) {
        if (e) {
            var x = e.pageX - canvas.x + canvas.leftScroll,
                y = e.pageY - canvas.y + canvas.topScroll;
            canvas.updatedElement = element;
            canvas.hideAllFocusedLabels();
            if (element.family !== 'Canvas') {
                canvas.emptyCurrentSelection();
                canvas.addToSelection(element);
            }

            canvas.onRightClickHandler(canvas.updatedElement, x, y);
        }

    };
};

AdamCanvas.prototype.onClickHandler = function (canvas, x, y) {
    this.RemoveCurrentMenu();
    this.project.onCanvasClick();
};

/**
 * Obtain the corresponding icon
 * @param {String} shape
 * @returns {String}
 */
AdamCanvas.prototype.getTreeItem = function (shape) {
    var cls  = '',
        name = '',
        item = {};

    switch (shape.getType()) {

    case 'AdamActivity':
        switch (shape.getActivityType()) {
        case 'TASK':
            cls = 'adam-tree-icon-user-task';
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'Task';
            break;
        default:
            cls = 'adam-tree-icon-user-task';
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'Task';
            break;
        }
        break;
    case 'AdamEvent':
        switch (shape.getEventType()) {
        case 'START':
            cls = 'adam-tree-icon-start';
            if (shape.getEventMessage() !== null
                        && shape.getEventMessage() !== '') {
                if (shape.getEventMessage() === 'Opportunities') {
                    cls = 'adam-tree-icon-start-opportunities';
                } else if (shape.getEventMessage() === 'Leads') {
                    cls = 'adam-tree-icon-start-leads';
                } else if (shape.getEventMessage() === 'Documents') {
                    cls = 'adam-tree-icon-start-documents';
                }
            }
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'Start';
            break;
        case 'INTERMEDIATE':
            if (shape.getEventMarker() !== null
                    && shape.getEventMarker() !== '') {
                if (shape.getEventMarker() === 'TIMER') {
                    cls = 'adam-tree-icon-intermediate-timer';
                } else {
                    cls = 'adam-tree-icon-intermediate-message';
                }
            }
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'Intermediate';
            break;
        case 'BOUNDARY':
            cls = 'adam-tree-icon-intermediate-boundary';
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'Boundary';
            break;
        case 'END':
            cls = 'adam-tree-icon-end';
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'End';
            break;
        }
        break;
    case 'AdamGateway':
        if (shape.getGatewayType() === 'PARALLEL') {
            cls = 'adam-tree-icon-gateway-parallel';
        } else {
            cls = 'adam-tree-icon-gateway-exclusive';
        }
        name = (shape.getName() && shape.getName() !== '') ?
                shape.getName() : 'Gateway';
        break;
    case 'AdamData':
        if (shape.getDataType() === 'DATAOBJECT') {
            cls = 'bpmn_icon_dataobject';
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'Data Object';
        } else {
            cls = 'bpmn_icon_datastore';
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'Data Store';
        }
        break;
    case 'AdamArtifact':
        if (shape.getArtifactType() === 'TEXTANNOTATION') {
            cls = 'adam-tree-icon-textannotation';
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'Text Annotation';
        } else {
            cls = 'bpmn_icon_group';
            name = (shape.getName() && shape.getName() !== '') ?
                    shape.getName() : 'Group';
        }
        break;
    }
    item = {
        name: name,
        icon: cls,
        id:   shape.getID()
    };
    return item;
};

AdamCanvas.prototype.buildRecursiveNode = function (root, canvas) {
    var i,
        items = [],
        item,
        elem;
    //sorting childrens by x or y depends of orientation
    canvas.children.sort(function (a, b) {
//        if ((canvas.getType() === 'bpmnPool'
//            || canvas.getType() === 'bpmnLane')
//            && canvas.getOrientation() === 'VERTICAL'){
//            return a.y-b.y
//        }
        return a.x - b.x;
    });

    for (i = 0; i < canvas.children.getSize(); i += 1) {
        elem = canvas.children.get(i);
        if (elem.type !== 'MultipleSelectionContainer') {
            item = this.getTreeItem(elem);
            if (elem.children.getSize() > 0) {
                this.buildRecursiveNode(item, elem);
            }
            items.push(item);
        }
    }
    $.extend(root, {'items': items});
};
/**
 * Get the diagram Tree
 * @returns {Object}
 */
AdamCanvas.prototype.getDiagramTree = function () {
    var diaTree = [],
        tree = {
            //name: this.getName()
            name:  this.name
            //icon:'bpmn_icon_pool',
            //selected:true;
        };
    this.buildRecursiveNode(tree, this);
    diaTree.push(tree);
    return diaTree;
};

AdamCanvas.prototype.addConnection = function (conn) {
    jCore.Canvas.prototype.addConnection.call(this, conn);
    // Only disconnect and reconnect if we are not coming from an undo action
    // Otherwise, the lines will redraw from the *last* last state of the line
    if (conn.flo_state && conn.inUndo !== true) {
        conn.disconnect(true).connect({
            algorithm: 'user',
            points: conn.flo_state
        });
        conn.setSegmentMoveHandlers();
    }

};

AdamCanvas.prototype.hideAllFocusedLabels =  function () {
    var size = this.customShapes.getSize(),
        i,
        shape;
    for (i = 0; i < size; i += 1) {
        shape = this.customShapes.get(i);
        shape.labels.get(0).loseFocus();
    }
    return true;
};

AdamCanvas.prototype.validateName = function (element, newText) {
    var shape = element.parent, shape_aux,
        limit = this.getCustomShapes().getSize(),
        i, msg = '', rt = true, nText = newText.trim(), str;
//    if (shape.type === 'AdamActivity') {
    if (nText === '') {
        if (shape.type === 'AdamActivity') {
            msg = translate('LBL_PMSE_MESSAGE_ACTIVITY_NAME_EMPTY');
            rt = false;
        }
    } else {
        for (i = 0; i < limit; i += 1) {
            shape_aux = this.getCustomShapes().get(i);
            if ((shape_aux.getID() !== shape.getID()) && (shape_aux.type === shape.type)) {
//                    if (shape_aux.getType() === 'AdamActivity') {
                if (shape_aux.getName().toUpperCase() === nText.toUpperCase()) {
//                            t += 1;
                    str = translate('LBL_PMSE_MESSAGE_ACTIVITY_NAME_ALREADY_EXISTS');
                    msg = str.replace('%s', nText);
                    rt = false;
                    break;
                }
//                    }
            }
        }
//            if (t > 1) {
//                msg = sprintf(translate('LBL_PMSE_MESSAGE_TASKNAMEALREADYEXISTS'), newText);
//                rt = false;
//            }
    }
//    }

    return {
        valid : rt,
        message : msg
    };
};
AdamCanvas.prototype.validatePositions = function (shape, coordinates) {
    var result = true;
    if (coordinates.y < shape.getZoomHeight() / 2) {
        result = false;
    }

    if (coordinates.y > (this.getHeight() - (shape.getZoomHeight() / 2) - 30)) {
        result = false;
    }

    if (coordinates.x < shape.getZoomWidth() / 2) {
        result = false;
    }
    if (coordinates.x > (this.getWidth() - (shape.getZoomWidth() / 2) - 50)) {
        result = false;
    }

    return result;
};

AdamCanvas.prototype.cleanAllFlowConditions = function () {
    var cleaned = 0,
        flow = this.connections.asArray(),
        i;
    for (i = 0; i < flow.length; i += 1) {
        if (flow[i].flo_condition !== '') {
            flow[i].flo_condition = '';
            cleaned += 1;
        }
    }
    return cleaned;
};

/**
 * Validate diagram respect BPMN 2.0 rules
 * @returns {BPMNCanvas}
 */
AdamCanvas.prototype.bpmnValidation = function () {
    var i, j,
        shape,
        rulesObject = this.bpmnRules,
        family,
        rules,
        message,
        testCount,
        objArray,
        sw;
    for (i = 0; i < this.getCustomShapes().getSize(); i += 1) {
        objArray = [];
        shape = this.getCustomShapes().get(i);
        family = shape.getFamilyNumber(shape);
        switch (family) {
            case 5:
                if ((shape.getActivityType() === 'TASK'
                    || shape.getActivityType() === 'SUBPROCESS')
                    && shape.getActivityTaskType() != 'SCRIPTTASK') {
                    objArray = rulesObject[shape.getType()]
                        ['task'];
                } else if (shape.getActivityTaskType() === 'SCRIPTTASK') {
                    objArray = rulesObject[shape.getType()]
                        ['scripttask'];
                    objArray[2].rules[0].value = shape.getActivityScriptType();
                }
                break;
            case 6:
                if (rulesObject[shape.getType()] &&
                    rulesObject[shape.getType()]
                        [shape.getEventType().toLowerCase()]){
                    objArray = rulesObject[shape.getType()]
                        [shape.getEventType().toLowerCase()];
                }
                break;
            case 7:
                switch (shape.getDirection()) {
                    case 'CONVERGING':
                        objArray = rulesObject[shape.getType()]
                            ['converging'];
                        break;
                    case 'DIVERGING':
                        objArray = rulesObject[shape.getType()]
                            ['diverging'];
                        break;
                    case 'MIXED':
                        objArray = rulesObject[shape.getType()]
                            ['mixed'];
                        break;
                }

                break;
            case 9:
                if (rulesObject[shape.getType()] &&
                    rulesObject[shape.getType()]
                        [shape.getArtifactType().toLowerCase()]){
                    objArray = rulesObject[shape.getType()]
                        [shape.getArtifactType().toLowerCase()];
                }
                break;
        }
        shape.attachErrorToShape(objArray);
    }
    return this;
};

/*
 * Add group locked fields to existing locked fields
 * @returns {Array}
 */
AdamCanvas.prototype.expandLockedFields = function(lockedFields, groupFieldsMap) {
    var retLockedFields = [];

    lockedFieldsLength = lockedFields.length;
    for (i = 0; i < lockedFieldsLength; i++) {
        // If there is a group field for existing locked field then add that group field
        // to the list of locked fields
        if ((groupFieldsMap[lockedFields[i]]) && (_.indexOf(retLockedFields, groupFieldsMap[lockedFields[i]]) == -1)) {
            retLockedFields.push(groupFieldsMap[lockedFields[i]]);
        };
        // add the existing locked field back into the return array of locked fields
        if ($.inArray(lockedFields[i], retLockedFields) == -1) {
            retLockedFields.push(lockedFields[i]);
        };
    };

    return retLockedFields;
};
