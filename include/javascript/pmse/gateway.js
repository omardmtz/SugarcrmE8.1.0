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
/*global AdamShape, $, CommandDefaultFlow, PMSE.Action, translate, PMSE.Window, PMSE_DECIMAL_SEPARATOR,
 PROJECT_MODULE, PMSE.Form, MessagePanel, CommandSingleProperty, PMSE_DESIGNER_FORM_TRANSLATIONS,AdamShapeLayerCommand
*/
var PMSE = PMSE || {};
/**
 * @class AdamGateway
 * Class to handle gateways
 * @extend AdamShape
 *
 * @constructor
 * Create a new gateway object
 * @param options
 */
var AdamGateway;
AdamGateway = function (options) {
    AdamShape.call(this, options);
    /**
     * Gateway id
     * @type {String}
     */
    this.gat_uid = null;
    /**
     * Gateway type, accept only: 'exclusive' and 'parallel' values
     * @type {String}
     */
    this.gat_type = null;
    /**
     * Gateway Direction, accept only 'unspecified', 'converging', 'diverging', 'mixed'
     * @type {String}
     */
    this.gat_direction = null;
    /**
     * Instantiate property
     * @type {String}
     */
    this.gat_instantiate = null;
    /**
     * Event Gatewat Type property
     * @type {String}
     */
    this.gat_event_gateway_type = null;
    /**
     * Activation Count property
     * @type {Number}
     */
    this.gat_activation_count = null;
    /**
     * WaitingForStart property
     * @type {Boolean}
     */
    this.gat_waiting_for_start = null;
    /**
     * Default Flow property
     * @type {null}
     */
    this.gat_default_flow = null;

    AdamGateway.prototype.initObject.call(this, options);
};

/**
 * Point the prototype to the AdamShape Object
 * @type {AdamShape}
 */
AdamGateway.prototype = new AdamShape();
/**
 * Defines the object type
 * @type {String}
 */
AdamGateway.prototype.type = 'AdamGateway';
/**
 * Initialize the AdamGateway object
 * @param options
 */
AdamGateway.prototype.initObject = function (options) {
    var defaults = {
        gat_direction: 'UNSPECIFIED',
        gat_instantiate: false,
        gat_event_gateway_type: 'NONE',
        gat_activation_count: 0,
        gat_waiting_for_start: true,
        gat_type: 'EXCLUSIVE',
        gat_default_flow: 0
    };
    $.extend(true, defaults, options);
    this.setGatewayUid(defaults.gat_uid)
        .setGatewayType(defaults.gat_type)
        .setDirection(defaults.gat_direction)
        .setInstantiate(defaults.gat_instantiate)
        .setEventGatewayType(defaults.gat_event_gateway_type)
        .setActivationCount(defaults.gat_activation_count)
        .setWaitingForStart(defaults.gat_waiting_for_start)
        .setDefaultFlow(defaults.gat_default_flow);
    if (defaults.gat_name) {
        this.setName(defaults.gat_name);
    }
};

/**
 * Sets the Gateway ID
 * @param id
 * @return {*}
 */

AdamGateway.prototype.setGatewayUid = function (id) {
    this.gat_uid = id;
    return this;
};
/**
 * Sets the gateway type
 * @param type
 * @return {*}
 */
AdamGateway.prototype.setGatewayType = function (type) {
    var defaultTypes = {
        exclusive: 'EXCLUSIVE',
        parallel: 'PARALLEL',
        inclusive: 'INCLUSIVE',
        eventbased: 'EVENTBASED'
    };
    if (defaultTypes[type]) {
        this.gat_type = defaultTypes[type];

    }
    return this;
};
/**
 * Sets the Gateway direction
 * @param direction
 * @return {*}
 */
AdamGateway.prototype.setDirection = function (direction) {
    var defaultDir = {
        unspecified: 'UNSPECIFIED',
        diverging: 'DIVERGING',
        converging: 'CONVERGING',
        mixed: 'MIXED'
    };
    if (defaultDir[direction]) {
        this.gat_direction = defaultDir[direction];
    }
    return this;
};
/**
 * Sets the instantiate property
 * @param value
 * @return {*}
 */
AdamGateway.prototype.setInstantiate = function (value) {
    this.gat_instantiate = value;
    return this;
};
/**
 * Sets the event_gateway_type property
 * @param value
 * @return {*}
 */
AdamGateway.prototype.setEventGatewayType = function (value) {
    this.gat_event_gateway_type = value;
    return this;
};
/**
 * Sets the activation_count property
 * @param value
 * @return {*}
 */
AdamGateway.prototype.setActivationCount = function (value) {
    this.gat_activation_count = value;
    return this;
};
/**
 * Sets the waiting_for_start property
 * @param value
 * @return {*}
 */
AdamGateway.prototype.setWaitingForStart = function (value) {
    this.gat_waiting_for_start = value;
    return this;
};
/**
 * Sets te default_flow property
 * @param value
 * @return {*}
 */
AdamGateway.prototype.setDefaultFlow = function (value) {
    if (this.html) {
        AdamShape.prototype.setDefaultFlow.call(this, value);
        this.canvas.triggerCommandAdam(this, ['gat_default_flow'], [this.gat_default_flow], [value]);
    }
    this.gat_default_flow = value;
    return this;
};
/**
 * Returns an object ready to save to DB
 * @return {Object}
 */
AdamGateway.prototype.getDBObject = function () {
    var name = this.getName();
    return {
        gat_uid: this.gat_uid,
        gat_name: name,
        gat_type: this.gat_type,
        gat_direction: this.gat_direction,
        gat_instantiate: this.gat_instantiate,
        gat_event_gateway_type: this.gat_event_gateway_type,
        gat_activation_count: this.gat_activation_count,
        gat_waiting_for_start: this.gat_waiting_for_start,
        gat_default_flow: this.gat_default_flow,
        bou_x: this.x,
        bou_y: this.y,
        bou_width: this.width,
        bou_height: this.height,
        bou_container: 'bpmnDiagram',
        element_id: this.canvas.dia_id
    };
};

AdamGateway.prototype.getDirection = function () {
    return this.gat_direction;
};

AdamGateway.prototype.getGatewayType = function () {
    return this.gat_type;
};

AdamGateway.prototype.getContextMenu = function () {
    var configurateAction,
        deleteAction,
        exclusiveAction,
        parallelAction,
        inclusiveAction,
        eventbasedAction,
        exclusiveActive = this.gat_type === 'EXCLUSIVE',
        parallelActive = this.gat_type === 'PARALLEL',
        inclusiveActive = this.gat_type === 'INCLUSIVE',
        eventbasedActive = this.gat_type === 'EVENTBASED',
        defaultflowAction,
        elements = this.getDestElements(),
        defaultflowActive = (elements.length > 1) ? false : true,
        defaultflownoneAction,
        defaultflowItems = [],
        name,
        convert,
        items = [],
        self = this,
        i,
        shape,
        port,
        connection,
        direction,
        noneDirectionAction,
        convergingDirectionAction,
        divergingDirectionAction,
        mixedDirectionAction,
        directionActive,
        unspecifiedDirectionActive = this.gat_direction === 'UNSPECIFIED',
        divergingDirectionActive = this.gat_direction === 'DIVERGING',
        convergingDirectionActive = this.gat_direction === 'CONVERGING',
        mixedDirectionActive = this.gat_direction === 'MIXED',
        handle = function (id) {
            return function () {
                var cmd = new CommandDefaultFlow(self, id);
                cmd.execute();
                self.canvas.commandStack.add(cmd);
            };
        };

    deleteAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_DELETE'),
        cssStyle: 'adam-menu-icon-delete',
        handler: function () {
            var shape;
            shape = self.canvas.customShapes.find('id', self.id);
            if (shape) {
                shape.canvas.emptyCurrentSelection();
                shape.canvas.addToSelection(shape);
                shape.canvas.removeElements();
            }
        }
    });

    configurateAction  = this.createConfigureAction();

    exclusiveAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_EXCLUSIVE_GATEWAY'),
        cssStyle : 'adam-menu-icon-gateway-exclusive',
        handler: function () {
            self.updateGatewayType('EXCLUSIVE');
        },
        selected: exclusiveActive
    });

    parallelAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_PARELLEL_GATEWAY'),
        cssStyle : 'adam-menu-icon-gateway-parallel',
        handler: function () {
            self.updateGatewayType('PARALLEL');
        },
        selected: parallelActive
    });

    inclusiveAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_INCLUSIVE_GATEWAY'),
        cssStyle : 'adam-menu-icon-gateway-inclusive',
        handler: function () {
            self.updateGatewayType('INCLUSIVE');
        },
        selected: inclusiveActive
    });

    eventbasedAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_EVENT_BASED_GATEWAY'),
        cssStyle : 'adam-menu-icon-gateway-eventbase',
        handler: function () {
            self.updateGatewayType('EVENTBASED');
        },
        selected: eventbasedActive
    });
    if (elements.length > 1) {
        defaultflownoneAction = new PMSE.Action({
            text: translate('LBL_PMSE_CONTEXT_MENU_NONE'),
            cssStyle : 'adam-menu-icon-none',
            handler: handle(''),
            selected: (self.gat_default_flow !== 0) ? false : true
        });

        defaultflowItems.push(defaultflownoneAction);

        //for (i = 0; i < elements.length; i += 1) {
        for (i = 0; i < this.getPorts().getSize(); i += 1) {
            port = this.getPorts().get(i);
            connection = port.connection;
            if (connection.srcPort.parent.getID() === this.getID()) {
                shape = connection.destPort.parent;
                switch (shape.getType()) {
                case 'AdamActivity':
                    name = (shape.getName() !== '') ? shape.getName() : translate('LBL_PMSE_CONTEXT_MENU_DEFAULT_TASK');
                    break;
                case 'AdamEvent':
                    name = (shape.getName() !== '') ? shape.getName() : translate('LBL_PMSE_CONTEXT_MENU_DEFAULT_EVENT');
                    break;
                case 'AdamGateway':
                    name = (shape.getName() !== '') ? shape.getName() : translate('LBL_PMSE_CONTEXT_MENU_DEFAULT_GATEWAY');
                    break;
                }
                defaultflowItems.push(
                    new PMSE.Action({
                        text: name,
                        cssStyle: self.getCanvas().getTreeItem(shape).icon,
                        handler: handle(connection.getID()),
                        selected: (self.gat_default_flow === connection.getID()) ? true : false
                    })
                );
            }
            //shape = elements[i];
        }
        defaultflowActive = (this.gat_type === 'PARALLEL'
            || this.gat_type === 'EVENTBASED') ? true : false;

        defaultflowAction = {
            label: translate('LBL_PMSE_CONTEXT_MENU_DEFAULT_FLOW'),
            icon: 'adam-menu-icon-default-flow',
            disabled: defaultflowActive,
            menu: {
                items: defaultflowItems
            }
        };
    }

    items.push(exclusiveAction);
    items.push(parallelAction);
    if (this.gat_direction !== 'CONVERGING') {
        items.push(inclusiveAction);
        items.push(eventbasedAction);
    }


    convert = {
        label: translate('LBL_PMSE_CONTEXT_MENU_CONVERT'),
        icon: 'adam-menu-icon-convert',
        menu: {
            items: items
        }
    };
    items = [];
    directionActive = (this.gat_direction);
    noneDirectionAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_UNSPECIFIED'),
        cssStyle : 'adam-menu-icon-none',
        handler: function () {
            self.updateDirection('UNSPECIFIED');
        },
        selected: unspecifiedDirectionActive
    });
    convergingDirectionAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_CONVERGING'),
        cssStyle : 'adam-menu-icon-gateway-converging',
        handler: function () {
            self.updateDirection('CONVERGING');
            self.cleanFlowConditions();

        },
        selected: convergingDirectionActive
    });
    divergingDirectionAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_DIVERGING'),
        cssStyle : 'adam-menu-icon-gateway-diverging',
        handler: function () {
            self.updateDirection('DIVERGING');
        },
        selected: divergingDirectionActive
    });
    mixedDirectionAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_MIXED'),
        cssStyle : 'adam-menu-icon-gateway-mixed',
        handler: function () {
            self.updateDirection('MIXED');
        },
        selected: mixedDirectionActive
    });
    direction = {
        label: translate('LBL_PMSE_CONTEXT_MENU_DIRECTION'),
        icon: 'adam-menu-icon-gateway-direction',
        menu: {
            items: [
                //noneDirectionAction,
                convergingDirectionAction,
                divergingDirectionAction
                //mixedDirectionAction
            ]
        }

    };

    if (elements.length > 1) {
        if (configurateAction.size > 0) {
            items.push(
                configurateAction.action,
                {
                    jtype: 'separator'
                }
            );
        }
        if (this.getGatewayType() !== 'EVENTBASED' &&  this.getGatewayType() !== 'INCLUSIVE') {
            items.push(
                direction,
                {
                    jtype: 'separator'
                }
            );
        }
        items.push(
            convert,
            {
                jtype: 'separator'
            },
            defaultflowAction,
            {
                jtype: 'separator'
            },
            deleteAction
        );
    } else {
        if (configurateAction.size > 0) {
            items.push(
                configurateAction.action,
                {
                    jtype: 'separator'
                }
            );
        }
        if (this.getGatewayType() !== 'EVENTBASED' &&  this.getGatewayType() !== 'INCLUSIVE') {
            items.push(
                direction,
                {
                    jtype: 'separator'
                }
            );
        }
        items.push(
            convert,
            {
                jtype: 'separator'
            },
            deleteAction
        );
    }

    return {
        items: items
    };
};

AdamGateway.prototype.createConfigureAction = function () {
    var action,
        w,
        wHeight = 500,
        wWidth = 750,
        f,
        i,
        connection,
        criteriaItems = [],
        canvas = this.canvas,
        oldCondition,
        oldValues,
        numFlowCriteria = 0,
        criteriaName,
        criteriaLabel,
        disabled,
        cancelInformation,
        root = this,
        proxy,
        flows;
    proxy = new SugarProxy({
        url: 'pmse_Project/GatewayDefinition/' + this.id,
        //restClient: this.canvas.project.restClient,
        uid: this.id,
        callback: null
    });
    w = new PMSE.Window({
        width: wWidth,
        height: wHeight,
        modal: true,
        title: translate('LBL_PMSE_FORM_TITLE_GATEWAY') + ': ' + this.getName()
    });
    for (i = 0; i < this.getPorts().getSize(); i += 1) {
        connection = this.getPorts().get(i).connection;
        if (this.gat_default_flow !== connection.getID()
                && connection.flo_element_origin === this.getID()
                && connection.flo_type !== 'DEFAULT') {
            numFlowCriteria += 1;

        }
    }

    f = new PMSE.Form({
        //items: criteriaItems,
        proxy: proxy,
        buttons: [
            {
                jtype: 'submit',
                caption: translate('LBL_PMSE_BUTTON_SAVE'),
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
        callback: {
            submit: function (data, other) {
                var array = [];

                //TODO UPDATE FLOW CONDITION
                $.each(data, function (key, val) {
                    connection = canvas.connections.find('id', key.split("-")[1]);
//                    oldCondition =  connection.getFlowCondition();

//                        oldValues = {
//                            condition: connection.getFlowCondition(),
//                            type: connection.getFlowType(),
//                            priority: 0
//                        };
                    val =  (val !== '[]') ? val : '';
                    connection.setFlowCondition(val);
//                        connection.canvas.triggerFlowConditionChangeEvent(connection, oldValues);
                    array.push({flo_uid: connection.id, flo_condition: connection.getFlowCondition()});
                });

                proxy.sendData(array);
                w.close();
            },
            loaded: function (data) {
                root.canvas.emptyCurrentSelection();

                //make criteria fields sortable
                $(f.body).sortable({
                    connectWith: ".adam-field",
                    handle: '.adam-form-label',
                    stop: function (event, ui) {

                        root.reorderItem(f, ui.item.attr('id'));
                    },
                    start: function (event, ui) {
                        var fields, i;
                        fields = f.items;
                        for (i = 0; i < fields.length; i += 1) {
                            fields[i].closePanel();
                        }
                        $('.multiple-item-panel').hide();
                        $(f.body).css('cursor', 'move');
                    }
                });
                $(f.body).on("mouseover", '.adam-field', function (e) {
                    $(f.body).sortable("enable");
                    $(f.body).css('cursor', 'row-resize');
                    e.stopPropagation();
                });
                $(f.body).on("mouseover", '.multiple-item-container', function (e) {
                    $(f.body).sortable("disable");
                    $(f.body).css('cursor', 'default');
                    e.stopPropagation();
                });

                flows = data.data;
                if (data && data.data) {
                    for (i = 0; i < flows.length; i += 1) {
                        connection = root.canvas.getConnections().find('id', flows[i].flo_uid);
                        criteriaName = (connection.getName()
                            && connection.getName() !== '')
                            ? connection.getName() : connection.getDestPort().parent.getName();
                        criteriaLabel = translate('LBL_PMSE_FORM_LABEL_CRITERIA') + ' (' + criteriaName + ')';
                        criteriaItems.push(
                            {
                                jtype: 'criteria',
                                name: 'condition-' + connection.getID(),
                                label: criteriaLabel,
                                required: false,
                                value: connection.getFlowCondition(),
                                fieldWidth: 420,
                                fieldHeight: 128,
                                decimalSeparator: SUGAR.App.config.defaultDecimalSeparator,
                                numberGroupingSeparator: SUGAR.App.config.defaultNumberGroupingSeparator,
                                currencies: project.getMetadata("currencies"),
                                dateFormat: App.date.getUserDateFormat(),
                                timeFormat: App.user.getPreference("timepref"),
                                operators: {
                                    logic: true,
                                    group: true,
                                    aritmetic: false,
                                    comparison: false
                                },
                                evaluation: {
                                    module: {
                                        dataURL: 'pmse_Project/CrmData/related/' + project.process_definition.pro_module,
                                        dataRoot: 'result',
                                        fieldDataURL: 'pmse_Project/CrmData/fields/{{MODULE}}',
                                        fieldDataURLAttr: {
                                            call_type: 'GT'
                                        },
                                        fieldDataRoot: "result",
                                        fieldTypeField: "type"
                                    },
                                    form: {
                                        dataURL: "pmse_Project/CrmData/activities/" + project.uid,
                                        dataRoot: 'result'
                                    },
                                    business_rule: {
                                        dataURL: 'pmse_Project/CrmData/businessrules/' + project.uid,
                                        dataRoot: 'result'
                                    },
                                    user: {
                                        defaultUsersDataURL: "pmse_Project/CrmData/defaultUsersList",
                                        defaultUsersDataRoot: "result",
                                        userRolesDataURL: "pmse_Project/CrmData/rolesList",
                                        userRolesDataRoot: "result",
                                        usersDataURL: "pmse_Project/CrmData/users",
                                        usersDataRoot: "result"
                                    }
                                },
                                constant: false
                            }
                        );
                    }
                }
                f.setItems(criteriaItems);
                for (i = 0; i < f.items.length; i += 1) {
                    html = f.items[i].getHTML();
                    $(html).find("select, input, textarea").focus(f.onEnterFieldHandler(f.items[i]));
                    f.body.appendChild(html);
                }
                ///end sortable field implementation

                f.proxy = null;
                App.alert.dismiss('upload');
                w.html.style.display = 'inline';

            }
        },
        language: PMSE_DESIGNER_FORM_TRANSLATIONS
    });

    w.addPanel(f);
    disabled = (this.gat_type === 'PARALLEL'
        || this.gat_type === 'EVENTBASED' || this.gat_direction === 'CONVERGING') ? true : false;

    action = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_SETTINGS'),
        cssStyle: 'adam-menu-icon-configure',
        handler: function () {
            root.saveProject(root, App, w);
        },
        disabled: disabled
    });

    return {size: numFlowCriteria, action: action};
};

AdamGateway.prototype.reorderItem = function (form, itemId) {
    var i,
        item,
        array,
        oldPos,
        newPos,
        aux;
    for (i = 0;  i < form.items.length; i += 1) {
        item = form.items[i];
        if (itemId === item.id) {
            oldPos = i;
            break;
        }
    }
    array = $('.adam-panel-body > div').map(function () {
        return this.id;
    }).get();
    for (i = 0;  i < array.length; i += 1) {
        if (itemId === array[i]) {
            newPos = i;
            break;
        }
    }
    aux = form.items[newPos];
    form.items[newPos] = form.items[oldPos];
    form.items[oldPos] = aux;
};

AdamGateway.prototype.cleanFlowConditions = function () {
    var i, port, connection, oldValues;
    for (i = 0; i < this.getPorts().getSize(); i += 1) {
        port = this.getPorts().get(i);
        connection = port.connection;
        if (connection.srcPort.parent.getID() === this.getID()) {
            oldValues = {
                condition: connection.getFlowCondition(),
                type: connection.getFlowType()
            };
            connection.setFlowCondition('');
            connection.canvas.triggerFlowConditionChangeEvent(connection, oldValues);
        }
    }
};

AdamGateway.prototype.updateGatewayType = function (newType) {
    var layer,
        updateCommand;

    layer = this.getLayers().get(0);
    updateCommand = new AdamShapeLayerCommand(
        this,
        {
            layers: [layer],
            type: 'changetypegateway',
            changes: newType
        }
    );
    updateCommand.execute();

    this.canvas.commandStack.add(updateCommand);
    return this;
};

AdamGateway.prototype.updateDirection = function (newDirection) {
    var command = new CommandSingleProperty(this, {
        propertyName: 'gat_direction',
        before: this.gat_direction,
        after: newDirection
    });
    command.execute();
    //this.getCanvas().commandStack.add(command);
};

AdamGateway.prototype.updateDefaultFlow = function (destID) {
    this.gat_default_flow = destID;
};

