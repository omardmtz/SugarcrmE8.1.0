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
/*global translate, $, document, window, SUGAR_FLAVOR, AdamProject, AdamCanvas, AdamEvent,
 AdamGateway, AdamActivity, AdamArtifact, AdamFlow, getAutoIncrementName, jCore,
 location, SUGAR_URL, adamUID, RestClient, SUGAR_REST, parseInt, SUGAR_AJAX_URL,
 PROJECT_LOCKED_VARIABLES, Tree, PROJECT_LOCKED_VARIABLES
 */
var project,
    canvas,
    PROJECT_MODULE = 'Leads',
    items,
    myLayout,
    adamUID,
    PROJECT_LOCKED_VARIABLES = [],
    PMSE_DECIMAL_SEPARATOR = '.',
    PMSE_DESIGNER_FORM_TRANSLATIONS = {
        ERROR_INVALID_EMAIL: translate('LBL_PMSE_ADAM_UI_ERROR_INVALID_EMAIL'),
        ERROR_INVALID_INTEGER: translate('LBL_PMSE_ADAM_UI_ERROR_INVALID_INTEGER'),
        ERROR_REQUIRED_FIELD: translate('LBL_PMSE_ADAM_UI_ERROR_REQUIRED_FIELD'),
        ERROR_COMPARISON: translate('LBL_PMSE_ADAM_UI_ERROR_COMPARISON'),
        ERROR_REGEXP: translate('LBL_PMSE_ADAM_UI_ERROR_REGEXP'),
        ERROR_TEXT_LENGTH: translate('LBL_PMSE_ADAM_UI_ERROR_TEXT_LENGTH'),
        ERROR_CHECKBOX_VALUES: translate('LBL_PMSE_ADAM_UI_ERROR_CHECKBOX_VALUES'),
        ERROR_TEXT: translate('LBL_PMSE_ADAM_UI_ERROR_TEXT'),
        ERROR_DATE : translate('LBL_PMSE_ADAM_UI_ERROR_DATE '),
        ERROR_PHONE: translate('LBL_PMSE_ADAM_UI_ERROR_PHONE'),
        ERROR_FLOAT: translate('LBL_PMSE_ADAM_UI_ERROR_FLOAT'),
        ERROR_DECIMAL: translate('LBL_PMSE_ADAM_UI_ERROR_DECIMAL'),
        ERROR_URL: translate('LBL_PMSE_ADAM_UI_ERROR_URL'),

        TITLE_BUSINESS_RULE_EVALUATION: translate('LBL_PMSE_ADAM_UI_TITLE_BUSINESS_RULE_EVALUATION'),
        LBL_BUSINESS: translate('LBL_PMSE_ADAM_UI_LBL_BUSINESS'),
        LBL_OPERATOR: translate('LBL_PMSE_ADAM_UI_LBL_OPERATOR'),
        LBL_UNIT: translate('LBL_PMSE_ADAM_UI_LBL_UNIT'),
        LBL_RESPONSE: translate('LBL_PMSE_LABEL_RESPONSE'),
        LBL_LOGIC_OPERATORS: translate('LBL_PMSE_ADAM_UI_LBL_LOGIC_OPERATORS'),
        LBL_GROUP: translate('LBL_PMSE_ADAM_UI_LBL_GROUP'),
        LBL_OPERATION: translate('LBL_PMSE_ADAM_UI_LBL_OPERATION'),
        LBL_DIRECTION: translate('LBL_PMSE_ADAM_UI_LBL_DIRECTION'),
        LBL_MODULE: translate('LBL_PMSE_FORM_LABEL_MODULE'),
        LBL_FIELD: translate('LBL_PMSE_LABEL_FIELD'),
        LBL_VALUE: translate('LBL_PMSE_LABEL_VALUE'),
        LBL_TARGET_MODULE: translate('LBL_PMSE_FORM_OPTION_TARGET_MODULE'),
        LBL_VARIABLE: translate('LBL_PMSE_ADAM_UI_LBL_VARIABLE'),
        LBL_NUMBER: translate('LBL_PMSE_ADAM_UI_LBL_NUMBER'),
        TITLE_MODULE_FIELD_EVALUATION: translate('LBL_PMSE_ADAM_UI_TITLE_MODULE_FIELD_EVALUATION'),
        TITLE_FORM_RESPONSE_EVALUATION: translate('LBL_PMSE_ADAM_UI_TITLE_FORM_RESPONSE_EVALUATION'),
        TITLE_SUGAR_DATE: translate('LBL_PMSE_ADAM_UI_TITLE_SUGAR_DATE'),
        TITLE_FIXED_DATE: translate('LBL_PMSE_ADAM_UI_TITLE_FIXED_DATE'),
        TITLE_UNIT_TIME: translate('LBL_PMSE_ADAM_UI_TITLE_UNIT_TIME'),
        LBL_FORM: translate('LBL_PMSE_LABEL_FORM'),
        LBL_STATUS: translate('LBL_PMSE_LABEL_STATUS'),
        LBL_APPROVED: translate('LBL_PMSE_LABEL_APPROVED'),
        LBL_REJECTED: translate('LBL_PMSE_LABEL_REJECTED'),
        BUTTON_SUBMIT: translate('LBL_PMSE_BUTTON_ADD'),
        BUTTON_CANCEL: translate('LBL_PMSE_BUTTON_CANCEL')
    },
    listPanelError = new ErrorListPanel({
            id : 'panel-Errors',
            onClickItem : function (listPanel, listItem, type, messageId){
                var shape, shapeId, canvas;
                canvas = jCore.getActiveCanvas();
                shapeId = listItem.getErrorId();
                shape = canvas.customShapes.find('id', shapeId);
                if (shape) {
                    shape.canvas.emptyCurrentSelection();
                    shape.canvas.addToSelection(shape);
                    //to disable textbox of label
                    if (shape.canvas.currentLabel) {
                        shape.canvas.currentLabel.loseFocus();
                    }
                    //for property grids
                    shape.canvas.project.updatePropertiesGrid(shape);
                }
            }
        });

    var countErrors = document.getElementById("countErrors");
    //labelErrors.id = "labelErros";
    /*countErrors.className = "btn btn-danger dropdown-toggle";
    countErrors.id = "countErrors";
    jQuery(countErrors).css({
        display : "none",
        marginLeft : "900px",
        position : "fixed"

    }).click(function(){
        myLayout.toggle('east');
    });*/

var getAutoIncrementName = function (type, targetElement) {
    var i, j, k = canvas.getCustomShapes().getSize(), element, exists, index = 1, auxMap = {
        AdamUserTask: translate('LBL_PMSE_ADAM_DESIGNER_TASK'),
        AdamScriptTask: translate('LBL_PMSE_ADAM_DESIGNER_ACTION'),
        AdamEventLead: translate('LBL_PMSE_ADAM_DESIGNER_LEAD_START_EVENT'),
        AdamEventOpportunity: translate('LBL_PMSE_ADAM_DESIGNER_OPPORTUNITY_START_EVENT'),
        AdamEventDocument: translate('LBL_PMSE_ADAM_DESIGNER_DOCUMENT_START_EVENT'),
        AdamEventOtherModule: translate('LBL_PMSE_ADAM_DESIGNER_OTHER_MODULE_EVENT'),
        AdamEventTimer: translate('LBL_PMSE_ADAM_DESIGNER_WAIT_EVENT'),
        AdamEventMessage: translate('LBL_PMSE_ADAM_DESIGNER_MESSAGE_EVENT'),
        AdamEventReceiveMessage: translate('LBL_PMSE_ADAM_DESIGNER_MESSAGE_EVENT'),
        AdamEventBoundary: translate('LBL_PMSE_ADAM_DESIGNER_BOUNDARY_EVENT'),
        AdamGatewayExclusive: translate('LBL_PMSE_ADAM_DESIGNER_EXCLUSIVE_GATEWAY'),
        AdamGatewayParallel: translate('LBL_PMSE_ADAM_DESIGNER_PARALLEL_GATEWAY'),
        AdamEventEnd: translate('LBL_PMSE_ADAM_DESIGNER_END_EVENT'),
        AdamTextAnnotation: translate('LBL_PMSE_ADAM_DESIGNER_TEXT_ANNOTATION')
    };

    for (i = 0; i < k; i += 1) {
        exists = false;
        for (j = 0; j < k; j += 1) {
            element =  canvas.getCustomShapes().get(j);
            if (element.getName() === auxMap[type] + " # " + (i + 1)) {
                exists = !(targetElement && targetElement === element);
                break;
            }
        }
        if (!exists) {
            break;
        }
    }

    return auxMap[type] + " # " + (i + 1);
};

function renderProject (prjCode) {
    var pmseCurrencies, currencies, sugarCurrencies, currentCurrency, i;

    // initialize the error sidebar
    listPanelError.title = App.lang.get('LBL_PMSE_BPMN_WARNING_PANEL_TITLE', 'pmse_Project');
    listPanelError.appendTo('#div-bpmn-error');

    adamUID = prjCode;

    //RESIZE OPTIONS
    if ($('#container').length) {
        $('#container').height($(window).height() - $('#container').offset().top - $('#footer').height() - 46);
    }
    $(window).resize(function () {
        if ($('#container').length) {
            $('#container').height($(window).height() - $('#content').offset().top - $('#footer').height() - 46);
        }

    });

    //LAYOUT
    myLayout = $('#container').layout({
        east: {
            size: 300,
            maxSize: 300,
            minSize: 200,
            /*childOptions: {
                center__paneSelector:   ".east-center",
                south__paneSelector:    ".east-south",
                south__size: '50%'
            },*/
            initClosed: false,
            onresize: function () {
                listPanelError.resizeWidthTitleItems();
            }
        },
        north: {
            size: 44,
            spacing_open: 0,
            closable: false,
            slidable: false,
            resizable: false
        },
        north__showOverflowOnHover:	true
    });
    $('#container').zIndex(1);

    $('.ui-layout-north').css('overflow', 'hidden');

    pmseCurrencies = [];
    currencies = SUGAR.App.metadata.getCurrencies();
    for (currID in currencies) {
        if (currencies.hasOwnProperty(currID)) {
            if (currencies[currID].status === 'Active') {
                pmseCurrencies.push({
                    id: currID,
                    iso: currencies[currID].iso4217,
                    name: currencies[currID].name,
                    rate: parseFloat(currencies[currID].conversion_rate),
                    preferred: currID === SUGAR.App.user.getCurrency().currency_id,
                    symbol: currencies[currID].symbol
                });
            }
        }
    }
    project = new AdamProject({
        metadata: [
            {
                name: "teamsDataSource",
                data: {
                    url: "pmse_Project/CrmData/teams/public",
                    root: "result"
                }
            },
            {
                name: "datePickerFormat",
                data: SUGAR.App.date.toDatepickerFormat(SUGAR.App.user.attributes.preferences.datepref)
            },
            {
                name: "fieldsDataSource",
                data: {
                    url: "pmse_Project/CrmData/allRelated/{MODULE}",
                    root: "result"
                }
            },
            {
                name: "targetModuleFieldsDataSource",
                data: {
                    url: "pmse_Project/CrmData/fields/{MODULE}",
                    root: "result"
                }
            },
            {
                name: "currencies",
                data: pmseCurrencies
            }
        ]
    });

    canvas = new AdamCanvas({
        name : 'Adam',
        id: "jcore_designer",
        container : "regular",
        readOnly : false,
        drop : {
            type : "container",
            selectors : ["#AdamEventDocument", "#AdamEventLead",
                "#AdamEventOpportunity", "#AdamEventTimer", "#AdamEventMessage", "#AdamEventEnd",
                "#AdamGatewayExclusive", "#AdamGatewayParallel", "#AdamUserTask", "#AdamScriptTask",
                "#AdamTextAnnotation", ".custom_shape", "#AdamEventReceiveMessage", "#AdamEventOtherModule" ]
        },
        copyAndPasteReferences: {
            AdamEvent: AdamEvent,
            AdamGateway: AdamGateway,
            AdamActivity: AdamActivity,
            AdamArtifact: AdamArtifact,
            AdamFlow: AdamFlow
        },
        toolbarFactory: function (id) {
            var customShape = null,
                name = getAutoIncrementName(id);
            switch (id) {
            case "AdamEventLead":
                customShape = new AdamEvent({
                    canvas : this,
                    width : 33,
                    height : 33,
                    style: {
                        cssClasses: [""]
                    },
                    evn_name: name,
                    evn_type: 'start',
                    evn_marker: 'MESSAGE',
                    evn_behavior: 'catch',
                    evn_message: 'Leads',
                    labels: [{
                        message: '',
                        position : {
                            location : "bottom",
                            diffX : 0,
                            diffY : 0
                        }
                    }],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-event-start',
                                'adam-shape-75-event-start',
                                'adam-shape-100-event-start',
                                'adam-shape-125-event-start',
                                'adam-shape-150-event-start'
                            ]
                        },
                        {
                            layerName : "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-marker-50-start-catch-leads',
                                'adam-marker-75-start-catch-leads',
                                'adam-marker-100-start-catch-leads',
                                'adam-marker-125-start-catch-leads',
                                'adam-marker-150-start-catch-leads'
                            ]
                        }

                    ],
                    drag: 'customshapedrag',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case "AdamEventOpportunity":
                customShape = new AdamEvent({
                    canvas : this,
                    width : 33,
                    height : 33,
                    style: {
                        cssClasses: [""]
                    },
                    evn_name: name,
                    evn_type: 'start',
                    evn_marker: 'MESSAGE',
                    evn_behavior: 'catch',
                    evn_message: 'Opportunities',
                    labels: [{
                        message: '',
                        position : {
                            location : "bottom",
                            diffX : 0,
                            diffY : 0
                        }
                    }],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-event-start',
                                'adam-shape-75-event-start',
                                'adam-shape-100-event-start',
                                'adam-shape-125-event-start',
                                'adam-shape-150-event-start'
                            ]
                        },
                        {
                            layerName : "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-marker-50-start-catch-opportunities',
                                'adam-marker-75-start-catch-opportunities',
                                'adam-marker-100-start-catch-opportunities',
                                'adam-marker-125-start-catch-opportunities',
                                'adam-marker-150-start-catch-opportunities'
                            ]
                        }
                    ],
                    drag: 'customshapedrag',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case "AdamEventDocument":
                customShape = new AdamEvent({
                    canvas : this,
                    width : 33,
                    height : 33,
                    style: {
                        cssClasses: [""]
                    },
                    evn_name: name,
                    evn_type: 'start',
                    evn_marker: 'MESSAGE',
                    evn_behavior: 'catch',
                    evn_message: 'Documents',
                    labels: [{
                        message: 'Document Start Event',
                        position : {
                            location : "bottom",
                            diffX : 0,
                            diffY : 0
                        }
                    }],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-event-start',
                                'adam-shape-75-event-start',
                                'adam-shape-100-event-start',
                                'adam-shape-125-event-start',
                                'adam-shape-150-event-start'
                            ]
                        },
                        {
                            layerName : "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-marker-50-start-catch-documents',
                                'adam-marker-75-start-catch-documents',
                                'adam-marker-100-start-catch-documents',
                                'adam-marker-125-start-catch-documents',
                                'adam-marker-150-start-catch-documents'
                            ]
                        }
                    ],
                    drag: 'customshapedrag',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case "AdamEventOtherModule":
                customShape = new AdamEvent({
                    canvas : this,
                    width : 33,
                    height : 33,
                    style: {
                        cssClasses: [""]
                    },
                    evn_name: name,
                    evn_type: 'start',
                    evn_marker: 'MESSAGE',
                    evn_behavior: 'catch',
                    evn_message: '',
                    labels: [{
                        message: 'Other Start Event',
                        position : {
                            location : "bottom",
                            diffX : 0,
                            diffY : 0
                        }
                    }],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-event-start',
                                'adam-shape-75-event-start',
                                'adam-shape-100-event-start',
                                'adam-shape-125-event-start',
                                'adam-shape-150-event-start'
                            ]
                        },
                        {
                            layerName : "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-marker-50-start-catch-message',
                                'adam-marker-75-start-catch-message',
                                'adam-marker-100-start-catch-message',
                                'adam-marker-125-start-catch-message',
                                'adam-marker-150-start-catch-message'
                            ]
                        }
                    ],
                    drag: 'customshapedrag',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case 'AdamEventTimer':
                customShape = new AdamEvent({
                    canvas : this,
                    width : 33,
                    height : 33,
                    style: {
                        cssClasses: [""]
                    },
                    evn_name: name,
                    evn_type: 'intermediate',
                    evn_marker: 'TIMER',
                    evn_behavior: 'catch',
                    evn_message: '',
                    labels: [{
                        message: '',
                        position : {
                            location : "bottom",
                            diffX : 0,
                            diffY : 0
                        }
                    }],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-event-intermediate',
                                'adam-shape-75-event-intermediate',
                                'adam-shape-100-event-intermediate',
                                'adam-shape-125-event-intermediate',
                                'adam-shape-150-event-intermediate'
                            ]
                        },
                        {
                            layerName : "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-marker-50-intermediate-catch-timer',
                                'adam-marker-75-intermediate-catch-timer',
                                'adam-marker-100-intermediate-catch-timer',
                                'adam-marker-125-intermediate-catch-timer',
                                'adam-marker-150-intermediate-catch-timer'
                            ]
                        }
                    ],
                    drag: 'customshapedrag',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case 'AdamEventMessage':
                customShape = new AdamEvent({
                    canvas : this,
                    width : 33,
                    height : 33,
                    style: {
                        cssClasses: [""]
                        //                              cssProperties : {
                        //                                  "border": "1px solid black"
                        //                              }
                    },
                    evn_name: name,
                    evn_type: 'intermediate',
                    evn_marker: 'MESSAGE',
                    evn_behavior: 'throw',
                    evn_message: '',
                    labels: [{
                        message: '',
                        position : {
                            location : "bottom",
                            diffX : 0,
                            diffY : 0
                        }
                    }],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-event-intermediate',
                                'adam-shape-75-event-intermediate',
                                'adam-shape-100-event-intermediate',
                                'adam-shape-125-event-intermediate',
                                'adam-shape-150-event-intermediate'
                            ]
                        },
                        {
                            layerName : "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-marker-50-intermediate-throw-message',
                                'adam-marker-75-intermediate-throw-message',
                                'adam-marker-100-intermediate-throw-message',
                                'adam-marker-125-intermediate-throw-message',
                                'adam-marker-150-intermediate-throw-message'
                            ]
                        }
                    ],
                    drag: 'customshapedrag',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case 'AdamEventReceiveMessage':
                customShape = new AdamEvent({
                    canvas : this,
                    width : 33,
                    height : 33,
                    style: {
                        cssClasses: [""]
                        //                              cssProperties : {
                        //                                  "border": "1px solid black"
                        //                              }
                    },
                    evn_name: name,
                    evn_type: 'intermediate',
                    evn_marker: 'MESSAGE',
                    evn_behavior: 'catch',
                    evn_message: '',
                    labels: [{
                        message: '',
                        position : {
                            location : "bottom",
                            diffX : 0,
                            diffY : 0
                        }
                    }],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-event-intermediate',
                                'adam-shape-75-event-intermediate',
                                'adam-shape-100-event-intermediate',
                                'adam-shape-125-event-intermediate',
                                'adam-shape-150-event-intermediate'
                            ]
                        },
                        {
                            layerName : "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-marker-50-intermediate-catch-message',
                                'adam-marker-75-intermediate-catch-message',
                                'adam-marker-100-intermediate-catch-message',
                                'adam-marker-125-intermediate-catch-message',
                                'adam-marker-150-intermediate-catch-message'
                            ]
                        }
                    ],
                    drag: 'customshapedrag',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case 'AdamUserTask':
                customShape = new AdamActivity({
                    canvas : this,
                    width: 100,
                    height: 50,
                    container : 'activity',
                    style: {
                        cssClasses: ['']
                    },
                    layers: [
                        {
                            /* added by mauricio */
                            // since the class bpmn_activity has border and
                            // moves the activity, then move it a few pixels
                            // back to make it look pretty
                            x: -2,
                            y: -2,
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: ['adam-activity-task']
                            }
                        }
                    ],
                    connectAtMiddlePoints: true,
                    drag: 'customshapedrag',
                    resizeBehavior: "activityResize",
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 8,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {
                        //type: 'connectioncontainer',
                        type: 'connection'
                        //selectors : ["#AdamEventBoundary", '.adam_boundary_event']
                    },
                    labels : [
                        {
                            message : "",
                            //x : 10,
                            //y: 10,
                            width : 0,
                            height : 0,
                            orientation: 'horizontal',
                            position: {
                                location: 'center',
                                diffX : 0,
                                diffY : 0

                            },
                            updateParent : true
                        }
                    ],
                    markers: [
                        {
                            markerType: 'USERTASK',
                            x: 5,
                            y: 5,
                            markerZoomClasses: [
                                "adam-marker-50-usertask",
                                "adam-marker-75-usertask",
                                "adam-marker-100-usertask",
                                "adam-marker-125-usertask",
                                "adam-marker-150-usertask"
                            ]
                        }
                    ],
                    act_type: 'TASK',
                    act_task_type: 'USERTASK',
                    act_name: name,
                    minHeight: 50,
                    minWidth: 100,
                    maxHeight: 300,
                    maxWidth: 400
                });
                break;
            case 'AdamScriptTask':
                customShape = new AdamActivity({
                    canvas : this,
                    width: 35,
                    height: 35,
                    container : 'activity',
                    style: {
                        cssClasses: ['']
                    },
                    layers: [
                        {
                            /* added by mauricio */
                            // since the class bpmn_activity has border and
                            // moves the activity, then move it a few pixels
                            // back to make it look pretty
                            x: -2,
                            y: -2,
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: ['adam-activity-task']
                            }
                        },
                        {
                            x: -2,
                            y: -2,
                            layerName: "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-activity-scripttask-none',
                                'adam-shape-75-activity-scripttask-none',
                                'adam-shape-100-activity-scripttask-none',
                                'adam-shape-125-activity-scripttask-none',
                                'adam-shape-150-activity-scripttask-none'
                            ]
                        }
                    ],
                    connectAtMiddlePoints: true,
                    drag: 'customshapedrag',
                    //resizeBehavior: "activityResize",
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    // drop : {
                    //     type: 'connectioncontainer',
                    //     selectors : ["#AdamEventBoundary", '.adam_boundary_event']
                    // },
                    drop : {type: 'connection'},
                    labels : [
                        {
                            message : "",
                            position: {
                                location: 'bottom',
                                diffX : 1,
                                diffY : 4
                            },
                            updateParent : false
                        }
                    ],
                    act_type: 'TASK',
                    act_task_type: 'SCRIPTTASK',
                    act_name: name, //name
                    act_script_type: 'NONE'
                });
                break;
            case 'AdamEventBoundary':
                customShape = new AdamEvent({
                    canvas : this,
                    width : 33,
                    height : 33,
                    style: {
                        cssClasses: [""]
                    },
                    evn_name: name,
                    evn_type: 'boundary',
                    evn_marker: 'TIMER',
                    evn_behavior: 'catch',
                    evn_message: '',
                    labels: [{
                        message: '',
                        position : {
                            location : "bottom",
                            diffX : 0,
                            diffY : 0
                        }
                    }],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-event-intermediate',
                                'adam-shape-75-event-intermediate',
                                'adam-shape-100-event-intermediate',
                                'adam-shape-125-event-intermediate',
                                'adam-shape-150-event-intermediate'
                            ]
                        },
                        {
                            layerName : "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-marker-50-intermediate-catch-timer',
                                'adam-marker-75-intermediate-catch-timer',
                                'adam-marker-100-intermediate-catch-timer',
                                'adam-marker-125-intermediate-catch-timer',
                                'adam-marker-150-intermediate-catch-timer'
                            ]
                        }
                    ],
                    drag: 'customshapedrag',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case 'AdamEventEnd':
                customShape = new AdamEvent({
                    canvas : this,
                    width : 33,
                    height : 33,
                    style: {
                        cssClasses: [""]
                        //                              cssProperties : {
                        //                                  "border": "1px solid black"
                        //                              }
                    },
                    evn_name: name,
                    evn_type: 'end',
                    evn_marker: 'EMPTY',
                    evn_behavior: 'throw',
                    evn_message: '',
                    labels: [{
                        message: '',
                        position : {
                            location : "bottom",
                            diffX : 0,
                            diffY : 0
                        }
                    }],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-event-end',
                                'adam-shape-75-event-end',
                                'adam-shape-100-event-end',
                                'adam-shape-125-event-end',
                                'adam-shape-150-event-end'
                            ]
                        },
                        {
                            layerName : "second-layer",
                            priority: 3,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-marker-50-end-throw-empty',
                                'adam-marker-75-end-throw-empty',
                                'adam-marker-100-end-throw-empty',
                                'adam-marker-125-end-throw-empty',
                                'adam-marker-150-end-throw-empty'
                            ]
                        }
                    ],
                    drag: 'customshapedrag',
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                    //drop : {type: 'adamconnection'}
                });
                break;
            case 'AdamGatewayExclusive':
                customShape = new AdamGateway({
                    canvas : this,
                    width : 45,
                    height : 45,
                    gat_type: 'exclusive',
                    gat_direction: 'diverging',
                    gat_name: name,

                    style: {
                        cssClasses: [""]
                    },
                    labels : [
                        {
                            message : "",
                            position : {
                                location : "bottom",
                                diffX : 0,
                                diffY : 0
                            }
                        }

                    ],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-gateway-exclusive',
                                'adam-shape-75-gateway-exclusive',
                                'adam-shape-100-gateway-exclusive',
                                'adam-shape-125-gateway-exclusive',
                                'adam-shape-150-gateway-exclusive'
                            ]
                        }
                    ],
                    connectAtMiddlePoints: true,
                    drag: 'regular',
                    resizeBehavior: "no",
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case 'AdamGatewayParallel':
                customShape = new AdamGateway({
                    canvas : this,
                    width : 45,
                    height : 45,
                    gat_type: 'parallel',
                    gat_direction: 'diverging',
                    gat_name: name,
                    style: {
                        cssClasses: [""]
                    },
                    labels : [
                        {
                            message : "",
                            position : {
                                location : "bottom",
                                diffX : 0,
                                diffY : 0
                            }
                        }

                    ],
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true,
                            style: {
                                cssClasses: []
                            },
                            zoomSprites : [
                                'adam-shape-50-gateway-parallel',
                                'adam-shape-75-gateway-parallel',
                                'adam-shape-100-gateway-parallel',
                                'adam-shape-125-gateway-parallel',
                                'adam-shape-150-gateway-parallel'
                            ]
                        }
                    ],
                    connectAtMiddlePoints: true,
                    drag: 'regular',
                    resizeBehavior: "no",
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 4,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    drop : {type: 'connection'}
                });
                break;
            case "AdamTextAnnotation":
                customShape = new AdamArtifact({
                    canvas : this,
                    width: 100,
                    height: 50,
                    style: {
                        cssClasses: []
                    },
                    layers: [
                        {
                            layerName : "first-layer",
                            priority: 2,
                            visible: true
                        }
                    ],
                    connectAtMiddlePoints: true,
                    drag: 'regular',
                    //resizeBehavior: "yes",
                    resizeBehavior: "adamArtifactResize",
                    resizeHandlers: {
                        type: "Rectangle",
                        total: 8,
                        resizableStyle: {
                            cssProperties: {
                                'background-color': "rgb(0, 255, 0)",
                                'border': '1px solid black'
                            }
                        },
                        nonResizableStyle: {
                            cssProperties: {
                                'background-color': "white",
                                'border': '1px solid black'
                            }
                        }
                    },
                    labels : [
                        {
                            message : "",
                            width : 0,
                            height : 0,
                            position: {
                                location : 'center',
                                diffX : 0,
                                diffY : 0
                            },
                            updateParent : true
                        }
                    ],
                    drop : {type: 'connection'},
                    art_type: 'TEXTANNOTATION',
                    art_name: name
                });
                break;
            }

            return customShape;
        }
    });
    canvas.attachListeners();

    jCore.setActiveCanvas(canvas);


    $("#adam_toolbar span[type=draggable]").draggable(
        {
            revert: "invalid",
            helper: function() {
                return $(this).clone().removeAttr('rel').css('zIndex', 5).show().appendTo('body');
            },
            cursor: "move"
        }
    );

    /*$("#adam_toolbar span").hover(function (e) {
     var $div = $('<div id="nToolTip"></div>'),
     tip =  $(this).attr('tooltip');
     //if (typeof tip !== 'undefined' && tip !== '') {
     if (tip !== undefined && tip !== '') {
     $div.addClass('adam-tooltip-message');
     $div.html($(this).attr('tooltip'));
     $div.appendTo($(this).parent());
     $div.css('width', '80px');
     $div.css({top: $(this).height() + 10, left: e.pageX - ($div.width() / 2), position: 'absolute'});
     }
     }, function (e) {
     $('#nToolTip').remove();
     });*/

    /*$('#adam_toolbar').find('.btn-close-designer').click(function (e) {
        e.preventDefault();
        //App.router.navigate('Home' , {trigger: true, replace: true });
        App.utils.tooltip.hide($('.btn-close-designer'));
        App.router.goBack();
        *//*var ieOrigin, baseUrl;
        if (!window.location.origin) {
            ieOrigin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port : '');
        }
        baseUrl = App.config.siteUrl || (window.location.origin || ieOrigin) + window.location.pathname;
        window.location = baseUrl;*//*
    });*/

    $('#ProjectTitle').hover(function (e) {
        $('.icon-edit-title').css('display', 'block');
    }, function (e) {
        $('.icon-edit-title').css('display', 'none');
    }).click(function (e) {
        e.preventDefault();
        $('#ProjectTitle').css('display', 'none');
        $('.icon-edit-title').css('display', 'block');
        $('#txt-title').css('display', 'block').focus().val($('#ProjectTitle').html());
    });

    var save_name = function() {
        $('#ProjectTitle').css('display', 'block');
        $('#txt-title').css('display', 'none');
        if ($('#ProjectTitle').html() != $('#txt-title').val()){
            $('#ProjectTitle').html(Handlebars.Utils.escapeExpression($('#txt-title').val()));
            url = App.api.buildURL('pmse_Project', null, {id: project.uid});
            attributes = {name: Handlebars.Utils.escapeExpression($('#txt-title').val())};
            App.alert.show('saving', {level: 'process', title: 'LBL_SAVING', autoclose: false});
            App.api.call('update', url, attributes, {
                success: function (data) {
                    App.alert.dismiss('saving');
                },
                error: function (err) {
                }
            });
        }
    };
    $('#txt-title').focusout(function (e) {
        if ($.trim($('#txt-title').val()) !== '') {
            save_name();
        }
    }).keypress(function(e) {
        if(e.which == 13) {
            if ($.trim(this.value) != '') {
                App.alert.dismiss('error-project-name');
                save_name();
            }
            else {
                App.alert.show('error-project-name', {
                    level: 'warning',
                    messages: translate('LBL_PMSE_PROJECT_NAME_EMPTY','pmse_Project'),
                    autoClose: false
                });
            }
        }
    });

    $('#ButtonUndo').click(function () {
        jCore.getActiveCanvas().commandStack.undo();
        jCore.getActiveCanvas().RemoveCurrentMenu();
    });

    $('#ButtonRedo').click(function () {
        jCore.getActiveCanvas().commandStack.redo();
        jCore.getActiveCanvas().RemoveCurrentMenu();
    });

    $('#ButtonSave').click(function () {
        project.save();
        jCore.getActiveCanvas().RemoveCurrentMenu();
    });

    //HANDLE ZOOM DROPDOWN
    $('#zoom').change(function (e) {
        var newZoomValue;
        newZoomValue = parseInt($(this).val());
        jCore.getActiveCanvas().applyZoom(newZoomValue);
        jCore.getActiveCanvas().bpmnValidation();
        $('.ui-layout-north').css('overflow', 'hidden');
    }).mouseenter(function() {
        $('.ui-layout-north').css('overflow', 'visible');
    });

    project.setUid(prjCode);

    project.setSaveInterval(20000);
//    project.setRestClient(new RestClient());
//    project.restClient.setRestfulBehavior(SUGAR_REST);
//    if (!SUGAR_REST) {
//        project.restClient.setBackupAjaxUrl(SUGAR_AJAX_URL);
//    }
    project.setCanvas(canvas);

    //project.loadProject({"success":true,"project":{"prj_id":"4","prj_uid":"78321684452559b5c660eb6043512161","prj_name":"Discount Approval Process","prj_target_namespace":"","prj_expression_language":"","prj_type_language":"","prj_exporter":"","prj_exporter_version":"","prj_create_date":"2013-10-09 14:07:24","prj_update_date":"2013-10-09 14:07:24","prj_author":"Administrator","prj_author_version":"","prj_original_source":"","prj_description":"","pro_uid":"57263330652559b5c826868012742456","diagram":[{"dia_id":"4","dia_uid":"78940909052559b5c6fd5a1016579214","prj_id":"4","dia_name":"Discount Approval Process","dia_is_closable":"0","activities":[{"act_uid":"67241953552559b848cae89075038926","act_name":"Supervisor approval","act_type":"TASK","act_is_for_compensation":"0","act_start_quantity":"1","act_completion_quantity":"1","act_task_type":"USERTASK","act_implementation":"","act_instantiate":"0","act_script_type":"","act_script":"","act_loop_type":"NONE","act_test_before":"0","act_loop_maximum":"0","act_loop_condition":"","act_loop_cardinality":"0","act_loop_behavior":"NONE","act_is_adhoc":"0","act_is_collapsed":"1","act_completion_condition":"","act_ordering":"PARALLEL","act_cancel_remaining_instances":"1","act_protocol":"","act_method":"","act_is_global":"0","act_referer":"0","act_default_flow":"0","act_master_diagram":"0","bou_x":"163","bou_y":"217","bou_width":"101","bou_height":"51","bou_container":"bpmnDiagram"},{"act_uid":"10469697952559baa8cb528043138032","act_name":"VP Sales approval","act_type":"TASK","act_is_for_compensation":"0","act_start_quantity":"1","act_completion_quantity":"1","act_task_type":"USERTASK","act_implementation":"","act_instantiate":"0","act_script_type":"","act_script":"","act_loop_type":"NONE","act_test_before":"0","act_loop_maximum":"0","act_loop_condition":"","act_loop_cardinality":"0","act_loop_behavior":"NONE","act_is_adhoc":"0","act_is_collapsed":"1","act_completion_condition":"","act_ordering":"PARALLEL","act_cancel_remaining_instances":"1","act_protocol":"","act_method":"","act_is_global":"0","act_referer":"0","act_default_flow":"0","act_master_diagram":"0","bou_x":"474","bou_y":"217","bou_width":"101","bou_height":"51","bou_container":"bpmnDiagram"},{"act_uid":"62788289352559c358d0b29096355536","act_name":"Remove Discount","act_type":"TASK","act_is_for_compensation":"0","act_start_quantity":"1","act_completion_quantity":"1","act_task_type":"SCRIPTTASK","act_implementation":"","act_instantiate":"0","act_script_type":"CHANGE_FIELD","act_script":"","act_loop_type":"NONE","act_test_before":"0","act_loop_maximum":"0","act_loop_condition":"","act_loop_cardinality":"0","act_loop_behavior":"NONE","act_is_adhoc":"0","act_is_collapsed":"1","act_completion_condition":"","act_ordering":"PARALLEL","act_cancel_remaining_instances":"1","act_protocol":"","act_method":"","act_is_global":"0","act_referer":"0","act_default_flow":"0","act_master_diagram":"0","bou_x":"747","bou_y":"386","bou_width":"35","bou_height":"35","bou_container":"bpmnDiagram"},{"act_uid":"3195682035255a350085cd5006873156","act_name":"taxable by amount","act_type":"TASK","act_is_for_compensation":"0","act_start_quantity":"1","act_completion_quantity":"1","act_task_type":"SCRIPTTASK","act_implementation":"","act_instantiate":"0","act_script_type":"BUSINESS_RULE","act_script":"","act_loop_type":"NONE","act_test_before":"0","act_loop_maximum":"0","act_loop_condition":"","act_loop_cardinality":"0","act_loop_behavior":"NONE","act_is_adhoc":"0","act_is_collapsed":"1","act_completion_condition":"","act_ordering":"PARALLEL","act_cancel_remaining_instances":"1","act_protocol":"","act_method":"","act_is_global":"0","act_referer":"0","act_default_flow":"0","act_master_diagram":"0","bou_x":"746","bou_y":"94","bou_width":"35","bou_height":"35","bou_container":"bpmnDiagram"},{"act_uid":"6913391785255a365086ef7092175235","act_name":"set Taxes","act_type":"TASK","act_is_for_compensation":"0","act_start_quantity":"1","act_completion_quantity":"1","act_task_type":"SCRIPTTASK","act_implementation":"","act_instantiate":"0","act_script_type":"CHANGE_FIELD","act_script":"","act_loop_type":"NONE","act_test_before":"0","act_loop_maximum":"0","act_loop_condition":"","act_loop_cardinality":"0","act_loop_behavior":"NONE","act_is_adhoc":"0","act_is_collapsed":"1","act_completion_condition":"","act_ordering":"PARALLEL","act_cancel_remaining_instances":"1","act_protocol":"","act_method":"","act_is_global":"0","act_referer":"0","act_default_flow":"0","act_master_diagram":"0","bou_x":"881","bou_y":"217","bou_width":"35","bou_height":"35","bou_container":"bpmnDiagram"}],"events":[{"evn_uid":"74909115852559b718cada6002065023","evn_name":"discount > 0","evn_type":"START","evn_marker":"MESSAGE","evn_is_interrupting":"1","evn_attached_to":"0","evn_cancel_activity":"0","evn_activity_ref":"0","evn_wait_for_completion":"1","evn_error_name":"","evn_error_code":"","evn_escalation_name":"","evn_escalation_code":"","evn_condition":"","evn_message":"OPPORTUNITY","evn_operation_name":"","evn_operation_implementation_ref":"","evn_time_date":"","evn_time_cycle":"","evn_time_duration":"","evn_behavior":"CATCH","bou_x":"92","bou_y":"224","bou_width":"33","bou_height":"33","bou_container":"bpmnDiagram"},{"evn_uid":"98548435352559bee8cd6c5023187280","evn_name":"Postive Response","evn_type":"INTERMEDIATE","evn_marker":"MESSAGE","evn_is_interrupting":"1","evn_attached_to":"0","evn_cancel_activity":"0","evn_activity_ref":"0","evn_wait_for_completion":"1","evn_error_name":"","evn_error_code":"","evn_escalation_name":"","evn_escalation_code":"","evn_condition":"","evn_message":"","evn_operation_name":"","evn_operation_implementation_ref":"","evn_time_date":"","evn_time_cycle":"","evn_time_duration":"","evn_behavior":"THROW","bou_x":"634","bou_y":"93","bou_width":"33","bou_height":"33","bou_container":"bpmnDiagram"},{"evn_uid":"38369812152559bf28cdb93097943566","evn_name":"Negative Response","evn_type":"INTERMEDIATE","evn_marker":"MESSAGE","evn_is_interrupting":"1","evn_attached_to":"0","evn_cancel_activity":"0","evn_activity_ref":"0","evn_wait_for_completion":"1","evn_error_name":"","evn_error_code":"","evn_escalation_name":"","evn_escalation_code":"","evn_condition":"","evn_message":"","evn_operation_name":"","evn_operation_implementation_ref":"","evn_time_date":"","evn_time_cycle":"","evn_time_duration":"","evn_behavior":"THROW","bou_x":"634","bou_y":"385","bou_width":"33","bou_height":"33","bou_container":"bpmnDiagram"},{"evn_uid":"71081809952559c408d12a7083856779","evn_name":"Discount approved","evn_type":"END","evn_marker":"EMPTY","evn_is_interrupting":"1","evn_attached_to":"0","evn_cancel_activity":"0","evn_activity_ref":"0","evn_wait_for_completion":"1","evn_error_name":"","evn_error_code":"","evn_escalation_name":"","evn_escalation_code":"","evn_condition":"","evn_message":"","evn_operation_name":"","evn_operation_implementation_ref":"","evn_time_date":"","evn_time_cycle":"","evn_time_duration":"","evn_behavior":"THROW","bou_x":"980","bou_y":"93","bou_width":"33","bou_height":"33","bou_container":"bpmnDiagram"},{"evn_uid":"75177863452559c428d1398075861600","evn_name":"Discount Rejected","evn_type":"END","evn_marker":"EMPTY","evn_is_interrupting":"1","evn_attached_to":"0","evn_cancel_activity":"0","evn_activity_ref":"0","evn_wait_for_completion":"1","evn_error_name":"","evn_error_code":"","evn_escalation_name":"","evn_escalation_code":"","evn_condition":"","evn_message":"","evn_operation_name":"","evn_operation_implementation_ref":"","evn_time_date":"","evn_time_cycle":"","evn_time_duration":"","evn_behavior":"THROW","bou_x":"852","bou_y":"385","bou_width":"33","bou_height":"33","bou_container":"bpmnDiagram"}],"gateways":[{"gat_uid":"33930715852559ba18cb386097070569","gat_name":"Approved","gat_type":"EXCLUSIVE","gat_direction":"DIVERGING","gat_instantiate":"0","gat_event_gateway_type":"NONE","gat_activation_count":"0","gat_waiting_for_start":"1","gat_default_flow":"","bou_x":"299","bou_y":"218","bou_width":"45","bou_height":"45","bou_container":"bpmnDiagram"},{"gat_uid":"12045436152559ba68cb446075495919","gat_name":"Discount > 20%","gat_type":"EXCLUSIVE","gat_direction":"DIVERGING","gat_instantiate":"0","gat_event_gateway_type":"NONE","gat_activation_count":"0","gat_waiting_for_start":"1","gat_default_flow":"","bou_x":"384","bou_y":"218","bou_width":"45","bou_height":"45","bou_container":"bpmnDiagram"},{"gat_uid":"98266465752559bc98cc8c4082811194","gat_name":"Approved?","gat_type":"EXCLUSIVE","gat_direction":"DIVERGING","gat_instantiate":"0","gat_event_gateway_type":"NONE","gat_activation_count":"0","gat_waiting_for_start":"1","gat_default_flow":"","bou_x":"628","bou_y":"218","bou_width":"45","bou_height":"45","bou_container":"bpmnDiagram"},{"gat_uid":"4132778515255a35c086cc0062984198","gat_name":"Exclusive Gateway # 1","gat_type":"EXCLUSIVE","gat_direction":"DIVERGING","gat_instantiate":"0","gat_event_gateway_type":"NONE","gat_activation_count":"0","gat_waiting_for_start":"1","gat_default_flow":"","bou_x":"874","bou_y":"87","bou_width":"45","bou_height":"45","bou_container":"bpmnDiagram"}],"artifacts":[],"flows":[{"flo_uid":"93919815552559b968cb261049132150","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"74909115852559b718cada6002065023","flo_element_origin_type":"bpmnEvent","flo_element_origin_port":"0","flo_element_dest":"67241953552559b848cae89075038926","flo_element_dest_type":"bpmnActivity","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"125","flo_y1":"241","flo_x2":"161","flo_y2":"241","flo_state":[{"x":125,"y":241},{"x":161,"y":241}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"58736614452559bbd8cb941097457829","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"67241953552559b848cae89075038926","flo_element_origin_type":"bpmnActivity","flo_element_origin_port":"0","flo_element_dest":"33930715852559ba18cb386097070569","flo_element_dest_type":"bpmnGateway","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"266","flo_y1":"241","flo_x2":"299","flo_y2":"241","flo_state":[{"x":266,"y":241},{"x":299,"y":241}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"44171218052559bc08cbe23018876066","flo_type":"SEQUENCE","flo_name":"yes","flo_element_origin":"33930715852559ba18cb386097070569","flo_element_origin_type":"bpmnGateway","flo_element_origin_port":"0","flo_element_dest":"12045436152559ba68cb446075495919","flo_element_dest_type":"bpmnGateway","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"[{\"expModule\":null,\"expField\":\"67241953552559b848cae89075038926\",\"expOperator\":\"equals\",\"expValue\":\"Approve\",\"expType\":\"CONTROL\",\"expLabel\":\"Supervisor approval == Approved\"}]","flo_x1":"344","flo_y1":"241","flo_x2":"385","flo_y2":"241","flo_state":[{"x":344,"y":241},{"x":385,"y":241}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"96395047652559bc48cc7e2003356896","flo_type":"SEQUENCE","flo_name":"yes","flo_element_origin":"12045436152559ba68cb446075495919","flo_element_origin_type":"bpmnGateway","flo_element_origin_port":"0","flo_element_dest":"10469697952559baa8cb528043138032","flo_element_dest_type":"bpmnActivity","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"[{\"expDirection\":\"after\",\"expFieldType\":\"Float\",\"expModule\":\"Opportunities\",\"expField\":\"discount_c\",\"expOperator\":\"major_than\",\"expValue\":20,\"expType\":\"MODULE\",\"expLabel\":\"discount > 20\"}]","flo_x1":"430","flo_y1":"241","flo_x2":"472","flo_y2":"241","flo_state":[{"x":430,"y":241},{"x":472,"y":241}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"36174649152559bcb8ccc29007209158","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"10469697952559baa8cb528043138032","flo_element_origin_type":"bpmnActivity","flo_element_origin_port":"0","flo_element_dest":"98266465752559bc98cc8c4082811194","flo_element_dest_type":"bpmnGateway","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"577","flo_y1":"241","flo_x2":"628","flo_y2":"241","flo_state":[{"x":577,"y":241},{"x":628,"y":241}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"77982702952559bf08cdac9044203138","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"98266465752559bc98cc8c4082811194","flo_element_origin_type":"bpmnGateway","flo_element_origin_port":"0","flo_element_dest":"98548435352559bee8cd6c5023187280","flo_element_dest_type":"bpmnEvent","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"[{\"expModule\":null,\"expField\":\"10469697952559baa8cb528043138032\",\"expOperator\":\"equals\",\"expValue\":\"Approve\",\"expType\":\"CONTROL\",\"expLabel\":\"VP Sales approval == Approved\"}]","flo_x1":"651","flo_y1":"218","flo_x2":"651","flo_y2":"126","flo_state":[{"x":651,"y":218},{"x":651,"y":126}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"57222647052559bf48cdf88032628623","flo_type":"DEFAULT","flo_name":"","flo_element_origin":"98266465752559bc98cc8c4082811194","flo_element_origin_type":"bpmnGateway","flo_element_origin_port":"0","flo_element_dest":"38369812152559bf28cdb93097943566","flo_element_dest_type":"bpmnEvent","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"651","flo_y1":"263","flo_x2":"651","flo_y2":"385","flo_state":[{"x":651,"y":263},{"x":651,"y":385}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"41934232452559bf98ce468001159637","flo_type":"DEFAULT","flo_name":"No","flo_element_origin":"33930715852559ba18cb386097070569","flo_element_origin_type":"bpmnGateway","flo_element_origin_port":"0","flo_element_dest":"38369812152559bf28cdb93097943566","flo_element_dest_type":"bpmnEvent","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"322","flo_y1":"263","flo_x2":"634","flo_y2":"402","flo_state":[{"x":322,"y":263},{"x":322,"y":402},{"x":634,"y":402}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"37745387752559c4a8d18f7045281496","flo_type":"DEFAULT","flo_name":"","flo_element_origin":"12045436152559ba68cb446075495919","flo_element_origin_type":"bpmnGateway","flo_element_origin_port":"0","flo_element_dest":"98548435352559bee8cd6c5023187280","flo_element_dest_type":"bpmnEvent","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"408","flo_y1":"218","flo_x2":"634","flo_y2":"110","flo_state":[{"x":408,"y":218},{"x":408,"y":110},{"x":634,"y":110}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"78542356652559ca28d1d26022179105","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"38369812152559bf28cdb93097943566","flo_element_origin_type":"bpmnEvent","flo_element_origin_port":"0","flo_element_dest":"62788289352559c358d0b29096355536","flo_element_dest_type":"bpmnActivity","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"667","flo_y1":"402","flo_x2":"745","flo_y2":"402","flo_state":[{"x":667,"y":402},{"x":745,"y":402}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"95416419952559cb18d2075083852689","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"62788289352559c358d0b29096355536","flo_element_origin_type":"bpmnActivity","flo_element_origin_port":"0","flo_element_dest":"75177863452559c428d1398075861600","flo_element_dest_type":"bpmnEvent","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"784","flo_y1":"402","flo_x2":"852","flo_y2":"402","flo_state":[{"x":784,"y":402},{"x":852,"y":402}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"9978622765255a3540865f5094054060","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"98548435352559bee8cd6c5023187280","flo_element_origin_type":"bpmnEvent","flo_element_origin_port":"0","flo_element_dest":"3195682035255a350085cd5006873156","flo_element_dest_type":"bpmnActivity","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"667","flo_y1":"110","flo_x2":"744","flo_y2":"110","flo_state":[{"x":667,"y":110},{"x":744,"y":110}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"2382848165255a36e087981092771775","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"3195682035255a350085cd5006873156","flo_element_origin_type":"bpmnActivity","flo_element_origin_port":"0","flo_element_dest":"4132778515255a35c086cc0062984198","flo_element_dest_type":"bpmnGateway","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"783","flo_y1":"110","flo_x2":"874","flo_y2":"110","flo_state":[{"x":783,"y":110},{"x":874,"y":110}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"5523208895255a381087db2059903911","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"4132778515255a35c086cc0062984198","flo_element_origin_type":"bpmnGateway","flo_element_origin_port":"0","flo_element_dest":"6913391785255a365086ef7092175235","flo_element_dest_type":"bpmnActivity","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"[{\"expFieldType\":null,\"expDirection\":null,\"expModule\":null,\"expField\":\"17\",\"expOperator\":\"equals\",\"expValue\":\"TAXABLE\",\"expType\":\"BUSINESS_RULES\",\"expLabel\":\"taxable by amount == &TAXABLE&\"}]","flo_x1":"897","flo_y1":"132","flo_x2":"897","flo_y2":"215","flo_state":[{"x":897,"y":132},{"x":897,"y":215}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"8969864515255a386088056037308854","flo_type":"DEFAULT","flo_name":"","flo_element_origin":"4132778515255a35c086cc0062984198","flo_element_origin_type":"bpmnGateway","flo_element_origin_port":"0","flo_element_dest":"71081809952559c408d12a7083856779","flo_element_dest_type":"bpmnEvent","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"919","flo_y1":"110","flo_x2":"980","flo_y2":"110","flo_state":[{"x":919,"y":110},{"x":980,"y":110}],"flo_eval_priority":"0","prj_id":"4"},{"flo_uid":"5527879075255a43908b389026760340","flo_type":"SEQUENCE","flo_name":"","flo_element_origin":"6913391785255a365086ef7092175235","flo_element_origin_type":"bpmnActivity","flo_element_origin_port":"0","flo_element_dest":"71081809952559c408d12a7083856779","flo_element_dest_type":"bpmnEvent","flo_element_dest_port":"0","flo_is_inmediate":"","flo_condition":"","flo_x1":"918","flo_y1":"233","flo_x2":"997","flo_y2":"126","flo_state":[{"x":918,"y":233},{"x":997,"y":233},{"x":997,"y":126}],"flo_eval_priority":"0","prj_id":"4"}],"pools":[],"lanes":[],"participants":[],"data":[]}],"process_definition":{"pro_module":"Opportunities","pro_status":"ACTIVE","pro_locked_variables":[],"pro_terminate_variables":false}}});
    //console.log('que pasa');
    project.load(prjCode, {
        success: function() {
            $.extend(canvas, {'name': project.name});

            PROJECT_MODULE = project.process_definition.pro_module;
            //PROJECT_LOCKED_VARIABLES = project.process_definition.pro_locked_variables.slice();

            /*items = canvas.getDiagramTree();
            Tree.treeReload('tree', items);*/
            project.init();
        }
    });
}

//jQuery(".pane ui-layout-center").append(countErrors);

