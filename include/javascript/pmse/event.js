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
/*global AdamShape, $, PMSE.Action, translate, AdamShapeLayerCommand, RestProxy,
 SUGAR_URL, CriteriaField, PMSE_DECIMAL_SEPARATOR, ComboboxField, HiddenField,
 TextField, PROJECT_MODULE, CheckboxField, DateField, RadiobuttonField, PMSE.Form,
 PMSE_DESIGNER_FORM_TRANSLATIONS, PMSE.Window, MessagePanel
*/
var PMSE = PMSE || {};
/**
 * @class AdamEvent
 * Handle BPMN Events
 * @extend AdamShape
 *
 * @constructor
 * Create a new event
 * @param {Object} options
 */
var AdamEvent = function (options) {
    AdamShape.call(this, options);
    /**
     * Defines the alphanumeric unique code
     * @type {String}
     */
    this.evn_uid = null;
    /**
     * Defines the event type
     * @type {String}
     */
    this.evn_type = null;
    /**
     * Defines the event marker supported
     * @type {String}
     */
    this.evn_marker = null;
    /**
     * Defines id the event interrups or not the execution
     * @type {Boolean}
     */
    this.evn_is_interrupting = true;
    /**
     * Defines the activity attachec when the event is a boundary element
     * @type {String}
     */
    this.evn_attached_to = null;
    /**
     * Defines if the event can cancel the activity attached to
     * @type {Boolean}
     */
    this.evn_cancel_activity = false;
    /**
     * Define the activity related when event is playing as transactional event
     * @type {String}
     */
    this.evn_activity_ref = null;
    /**
     * Defines if the event needs to wait for completation status
     * @type {Boolean}
     */
    this.evn_wait_for_completion = true;
    /**
     * Defines the error name when event is playing like an error event
     * @type {String}
     */
    this.evn_error_name = null;
    /**
     * Defines the error code when event is playing like an error event
     * @type {String}
     */
    this.evn_error_code = null;
    /**
     * Defines the escalation name when event is playing like an escalation event
     * @type {String}
     */
    this.evn_escalation_name = null;
    /**
     * Defines the escalation name when event is playing like an escalation event
     * @type {String}
     */
    this.evn_escalation_code = null;
    /**
     * Defines the condition on the event
     * @type {String}
     */
    this.evn_condition = null;
    /**
     * Defines the message association
     * @type {String}
     */
    this.evn_message = null;
    /**
     * Defines the operation tom be executed when event is used like a transactional event
     * @type {String}
     */
    this.evn_operation_name = null;
    /**
     * XXXX
     * @type {String}
     */
    this.evn_operation_implementation = null;
    /**
     * Defines the date to be executed a timer event
     * @type {String}
     */
    this.evn_time_date = null;
    /**
     * Defines the time cycle to be executed a timer event
     * @type {String}
     */
    this.evn_time_cycle = null;
    /**
     * Defines the duration of the timer event
     * @type {String}
     */
    this.evn_time_duration = null;
    /**
     * Define the behavior of the event. Valid values are: CATCH, THROW
     * @type {String}
     */
    this.evn_behavior = null;

    /**
     * Defines the order of the boundary event when is attached to an activity
     * @type {Number}
     */
    this.numberRelativeToActivity = 0;

    AdamEvent.prototype.initObject.call(this, options);
};

/**
 * Point the prototype to the AdamShaoe object
 * @type {AdamShape}
 */
AdamEvent.prototype = new AdamShape();

/**
 * Defines the object type
 * @type {String}
 */
AdamEvent.prototype.type = "AdamEvent";

AdamEvent.ACTION_TYPES = {
    START_LEAD: 0,
    START_OPPORTUNITY: 1,
    START_DOCUMENT: 2,
    START_OTHER: 3,
    INTERMEDIATE_RECEIVE_MESSAGE: 4,
    INTERMEDIATE_SEND_MESSAGE: 5,
    INTERMEDIATE_TIMER: 6,
    END_EMPTY: 7,
    END_SEND_MESSAGE: 8,
    END_TERMINATE: 9,
    BOUNDARY_RECEIVE_MESSAGE: 10,
    BOUNDARY_TIMER: 11
};

AdamEvent.ACTION_TYPE = [
    {
        text: 'LBL_PMSE_CONTEXT_MENU_LEADS',
        cssStyle: 'adam-menu-icon-event-leads',
        evn_marker: 'MESSAGE',
        evn_message: 'Leads',
        evn_behavior: 'CATCH',
        evn_type: 'START',
        evn_message: 'Leads',
        nameIdentifier: 'AdamEventLead'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_OPPORTUNITIES',
        cssStyle: 'adam-menu-icon-event-opportunities',
        evn_marker: 'MESSAGE',
        evn_message: 'Opportunities',
        evn_behavior: 'CATCH',
        evn_type:'START',
        evn_message: 'Opportunities',
        nameIdentifier: 'AdamEventOpportunity'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_DOCUMENTS',
        cssStyle: 'adam-menu-icon-event-documents',
        evn_marker: 'MESSAGE',
        evn_message: 'Documents',
        evn_behavior: 'CATCH',
        evn_type: 'START',
        evn_message: 'Documents',
        nameIdentifier: 'AdamEventDocument'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_OTHER_MODULES',
        cssStyle: '',
        evn_marker: 'MESSAGE',
        evn_message: '',
        evn_behavior: 'CATCH',
        evn_type: 'START',
        evn_message: '',
        nameIdentifier: 'AdamEventOtherModule'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_RECEIVE_MESSAGE',
        cssStyle: 'adam-menu-icon-event-recive-message',
        evn_marker: 'MESSAGE',
        evn_behavior: 'CATCH',
        evn_type: 'INTERMEDIATE',
        nameIdentifier: 'AdamEventReceiveMessage'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_SEND_MESSAGE',
        cssStyle: 'adam-menu-icon-event-send-message',
        evn_marker: 'MESSAGE',
        evn_behavior: 'THROW',
        evn_type: 'INTERMEDIATE',
        nameIdentifier: 'AdamEventMessage'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_TIMER',
        cssStyle: 'adam-menu-icon-event-timer',
        evn_marker: 'TIMER',
        evn_behavior: 'CATCH',
        evn_type: 'INTERMEDIATE',
        nameIdentifier: 'AdamEventTimer'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_DO_NOTHING',
        cssStyle: '',
        evn_marker: 'EMPTY',
        evn_behavior: 'THROW',
        evn_type: 'END'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_SEND_MESSAGE',
        cssStyle: 'adam-menu-icon-event-send-message',
        evn_marker: 'MESSAGE',
        evn_behavior: 'THROW',
        evn_type: 'END'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_TERMINATE_PROCESS',
        cssStyle: 'adam-menu-icon-event-terminate-process',
        evn_marker: 'TERMINATE',
        evn_behavior: 'THROW',
        evn_type: 'END'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_RECEIVE_MESSAGE',
        cssStyle: 'adam-menu-icon-event-recive-message',
        evn_marker: 'MESSAGE',
        evn_behavior: 'CATCH',
        evn_type: 'BOUNDARY'
    },
    {
        text: 'LBL_PMSE_CONTEXT_MENU_TIMER',
        cssStyle: 'adam-menu-icon-event-timer',
        evn_marker: 'TIMER',
        evn_behavior: 'CATCH',
        evn_type: 'BOUNDARY'
    }
];

/**
 * Initialize the object with default values
 * @param {Object} options
 */
AdamEvent.prototype.initObject = function (options) {
    var defaults = {
        evn_is_interrupting: true,
        evn_message: '',
        evn_marker: 'EMPTY',
        evn_type: 'start',
        evn_behavior: 'catch'
    };
    $.extend(true, defaults, options);
    this.setEventUid(defaults.evn_uid)
        .setEventType(defaults.evn_type)
        .setEventMarker(defaults.evn_marker)
        .setEventMessage(defaults.evn_message)
        .setBehavior(defaults.evn_behavior)
        .setCondition(defaults.evn_condition)
        .setAttachedTo(defaults.evn_attached_to)
        .setIsInterrupting(defaults.evn_is_interrupting);
    if (defaults.evn_name) {
        this.setName(defaults.evn_name);
    }
};

/**
 * Sets the event uid property
 * @param {String} id
 * @return {*}
 */
AdamEvent.prototype.setEventUid = function (id) {
    this.evn_uid = id;
    return this;
};

/**
 * Sets the event type property
 * @param {String} type
 * @return {*}
 */
AdamEvent.prototype.setEventType = function (type) {
    var defaultTypes = {
        start: 'START',
        end: 'END',
        intermediate: 'INTERMEDIATE',
        boundary: 'BOUNDARY'
    };
    if (defaultTypes[type]) {
        this.evn_type = defaultTypes[type];
    }
    return this;
};

/**
 * Sets the event marker property
 * @param {String} marker
 * @return {*}
 */
AdamEvent.prototype.setEventMarker = function (marker) {
    this.evn_marker = marker;
    return this;
};

/**
 * Sets if the event interrups the execution or not
 * @param {Boolean} value
 * @return {*}
 */
AdamEvent.prototype.setIsInterrupting = function (value) {
    //if (_.isBoolean(value)) {
    if (value instanceof Boolean) {
        this.evn_is_interrupting = value;
    }
    return this;
};

/**
 * Sets the event behavior property
 * @param {String} behavior
 * @return {*}
 */
AdamEvent.prototype.setBehavior = function (behavior) {
    var defaultBehaviors = {
        "catch": 'CATCH',
        "throw": 'THROW'
    };
    if (defaultBehaviors[behavior]) {
        this.evn_behavior = defaultBehaviors[behavior];
    }
    return this;
};

/**
 * Sets the activity id where the event is attached to
 * @param {String} value
 * @param {Boolean} [cancel]
 * @return {*}
 */
AdamEvent.prototype.setAttachedTo = function (value, cancel) {
    //if (typeof cancel !== 'undefined') {
    if (cancel !== undefined) {
        //if (_.isBoolean(cancel)) {
        if (cancel instanceof Boolean) {
            this.evn_cancel_activity = cancel;
        }
    } else {
        this.evn_cancel_activity = this.evn_cancel_activity || false;
    }
    this.evn_attached_to = value;
    return this;
};

/**
 * Destroy a event
 * @returns {AdamEvent}
 */
AdamEvent.prototype.destroy = function () {
    if (this.getType() === 'AdamEvent' && this.getEventType() === 'BOUNDARY') {
        if (this.parent.boundaryPlaces && this.numberRelativeToActivity !==  null) {
            this.parent.boundaryPlaces
                .get(this.numberRelativeToActivity)
                .available = true;
            this.parent.boundaryArray.remove(this);

        }
    }
    return this;
};

/**
 * Sets the event message
 * @param {String} msg
 * @return {*}
 */
AdamEvent.prototype.setEventMessage = function (msg) {
    this.evn_message = msg;
    return this;
};

/**
 * Sets the event condition property
 * @param {String} value
 * @return {*}
 */
AdamEvent.prototype.setCondition = function (value) {
    this.evn_condition = value;
    return this;
};

/**
 * Set the compensation properties
 * @param {String} activity
 * @param {Boolean} wait
 * @return {*}
 */
AdamEvent.prototype.setCompensationActivity = function (activity, wait) {
    //if (typeof wait !== 'undefined') {
    if (wait) {
        //if (_.isBoolean(wait)) {
        if (wait instanceof Boolean) {
            this.evn_wait_for_completion = wait;
        }
    } else {
        this.evn_wait_for_completion = this.evn_wait_for_completion || false;
    }
    this.evn_activity_ref = activity;
    return this;
};

/**
 * Sets the error properties
 * @param {String} name  Error Name
 * @param {String} code  Error Code
 * @return {*}
 */
AdamEvent.prototype.setEventError = function (name, code) {
    this.evn_error_name = name;
    this.evn_error_code = code;
    return this;
};

/**
 * Sets the escalation properties
 * @param {String} name Escalation Name
 * @param {String} code Escalation Code
 * @return {*}
 */
AdamEvent.prototype.setEventEscalation = function (name, code) {
    this.evn_escalation_name = name;
    this.evn_escalation_code = code;
    return this;
};

/**
 * Sets the event operation properties
 * @param {String} name
 * @param {String} implementation
 * @return {*}
 */
AdamEvent.prototype.setEventOperation = function (name, implementation) {
    this.evn_operation_name = name;
    this.evn_operation_implementation = implementation;
    return this;
};

/**
 * Sets the event timer properties
 * @param {String} date
 * @param {String} cycle
 * @param {String} duration
 * @return {*}
 */
AdamEvent.prototype.setEventTimer = function (date, cycle, duration) {
    this.evn_time_date = date;
    this.evn_time_cycle = cycle;
    this.evn_time_duration = duration;
    return this;
};

/**
 * Sets te default_flow property
 * @param value
 * @return {*}
 */
AdamEvent.prototype.setDefaultFlow = function (value) {
    AdamShape.prototype.setDefaultFlow.call(this, value);
    this.evn_default_flow = value;
    return this;
};

/**
 * Returns the clean object to be sent to the backend
 * @return {Object}
 */
AdamEvent.prototype.getDBObject = function () {
    var container,
        element_id,
        name = this.getName();
    if (this.evn_type === 'BOUNDARY') {
        container = 'bpmnActivity';
        element_id = this.evn_attached_to;
    } else {
        container = 'bpmnDiagram';
        element_id = this.canvas.dia_id;
    }
    return {
        evn_uid: this.evn_uid,
        evn_name: name,
        evn_type: this.evn_type,
        evn_marker: this.evn_marker,
        evn_is_interrupting: this.evn_is_interrupting,
        evn_attached_to: this.evn_attached_to,
        evn_cancel_activity: this.evn_cancel_activity,
        evn_activity_ref: this.evn_activity_ref,
        evn_wait_for_completion: this.evn_wait_for_completion,
        evn_error_name: this.evn_error_name,
        evn_error_code: this.evn_error_code,
        evn_escalation_name: this.evn_escalation_name,
        evn_escalation_code: this.evn_escalation_code,
        evn_condition: this.evn_condition,
        evn_message: this.evn_message,
        evn_operation_name: this.evn_operation_name,
        evn_operation_implementation: this.evn_operation_implementation,
        evn_time_date: this.evn_time_date,
        evn_time_cycle: this.evn_time_cycle,
        evn_time_duration: this.evn_time_duration,
        evn_behavior: this.evn_behavior,
        bou_x: this.x,
        bou_y: this.y,
        bou_width: this.width,
        bou_height: this.height,
        bou_container: container,
        element_id: element_id
    };
};

/**
 * Attach the event to an activity
 * @return {*}
 */
AdamEvent.prototype.attachToActivity = function () {
    var numBou = this.parent.getAvailableBoundaryPlace();
    if (numBou !== false) {
        this.parent.setBoundary(this, numBou);
        this.setNumber(numBou);
    } else {
        this.destroy();
        this.saveAndDestroy();
    }
    return this;
};

/**
 * Sets the number/order of the current event when is attached to an activity
 * @param {Number} num
 * @return {*}
 */
AdamEvent.prototype.setNumber = function (num) {
    this.numberRelativeToActivity = num;
    return this;
};

/**
 * Create HTML css classes to identify events
 */
AdamEvent.prototype.createHTML = function () {
    AdamShape.prototype.createHTML.call(this);
    if (this.evn_type === "BOUNDARY") {
        this.style.addClasses(['adam_boundary_event']);
    } else {
        this.style.addClasses(['adam_event', 'adam_droppable']);
    }
    return this.html;
};

AdamEvent.prototype.getEventType = function () {
    return this.evn_type;
};

AdamEvent.prototype.getEventMarker = function () {
    return this.evn_marker;
};

AdamEvent.prototype.getEventMessage = function () {
    return this.evn_message;
};

AdamEvent.prototype._isSelectedAction = function (definition) {
    switch (definition.evn_type) {
        case 'START':
            return (this.evn_marker === definition.evn_marker) &&
                (this.evn_behavior === definition.evn_behavior) &&
                (this.evn_type === definition.evn_type) &&
                (definition.evn_message === '' ?
                    (this.evn_message === definition.evn_message || this.evn_message === null)
                    : this.evn_message === definition.evn_message);
        case 'INTERMEDIATE':
        case 'END':
        case 'BOUNDARY':
            return (this.evn_marker === definition.evn_marker) &&
                (this.evn_behavior === definition.evn_behavior) &&
                (this.evn_type === definition.evn_type);
        default:
            throw new Error("_isSelectedAction(): Invalid definition evn_type.");
    }
};

AdamEvent.prototype._getActionHandler = function (definition) {
    var self = this;
    return function () {
        var cfg = {};
        var name;

        if (definition.nameIdentifier) {
            cfg.evn_name  = getAutoIncrementName(definition.nameIdentifier, self);
        }

        switch (definition.evn_type) {
            case 'START':
                cfg.message = definition.evn_message;
            case 'END':
            case 'BOUNDARY':
                cfg.evn_marker = definition.evn_marker;
                cfg.evn_behavior = definition.evn_behavior;
                self.updateEventMarker(cfg);
                break;
            case 'INTERMEDIATE':
                // Clear the event criteria when the user switches action on an INTERMEDIATE event
                // Makes use of the evn_uid field to identity the event.
                App.alert.show(
                    'change_script_type_confirmation',
                    {
                        level: 'confirmation',
                        messages: translate('LBL_PMSE_CHANGE_ACTION_TYPE_CONFIRMATION'),
                        onConfirm: function() {
                            cfg.evn_marker = definition.evn_marker;
                            cfg.evn_behavior = definition.evn_behavior;
                            var proxy = new SugarProxy({
                                url: 'pmse_Project/CrmData/clearEventCriteria/' + self.id,
                                uid: self.id,
                                callback: null
                            });
                            proxy.sendData(null, {
                                success: function() {
                                    self.updateEventMarker(cfg);
                                }
                            });
                        }
                    });
                break;

            default:
                throw new Error("_getActionHandler(): Invalid definition evn_type.");
        }
    };
};

AdamEvent.prototype._createAction = function (type) {
    var actionDefinition = AdamEvent.ACTION_TYPE[type];
    var actionCFG = {};

    if (!actionDefinition) {
        throw new Error("_createAction(): Invalid type.");
    }

    actionCFG.text = translate(actionDefinition.text);
    actionCFG.cssStyle = actionDefinition.cssStyle;
    actionCFG.selected = this._isSelectedAction(actionDefinition);
    actionCFG.handler = this._getActionHandler(actionDefinition);

    return new PMSE.Action(actionCFG);
};

AdamEvent.prototype.getContextMenu = function () {
    var deleteAction,
        startAction, intermediateAction, endAction,
        modulesMenu, typeMenu,
        self = this,
        configureAction,
        mitems = [];

    configureAction = this.createConfigureAction();

    startAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_START_EVENT'),
        handler: function () {
            self.updateEventType('START');
        },
        selected: (this.evn_type === 'START')
    });

    intermediateAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_INTERMEDIATE_EVENT'),
        handler: function () {
            self.updateEventType('INTERMEDIATE');
        },
        selected: (this.evn_type === 'INTERMEDIATE')
    });

    endAction = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_END_EVENT'),
        handler: function () {
            self.updateEventType('END');
        },
        selected: (this.evn_type === 'END')
    });

    typeMenu = {
        items: [
            startAction,
            intermediateAction,
            endAction
        ]
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

    modulesMenu = {
        label: '',
        menu: {
            items: []
        }
    };
    switch (this.evn_type) {
    case 'START':
        modulesMenu.label = translate('LBL_PMSE_CONTEXT_MENU_LISTEN');
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.START_LEAD));
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.START_OPPORTUNITY));
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.START_DOCUMENT));
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.START_OTHER));
        break;
    case 'INTERMEDIATE':
        modulesMenu.label = translate('LBL_PMSE_CONTEXT_MENU_ACTION');
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.INTERMEDIATE_RECEIVE_MESSAGE));
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.INTERMEDIATE_SEND_MESSAGE));
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.INTERMEDIATE_TIMER));
        break;
    case 'END':
        modulesMenu.label = translate('LBL_PMSE_CONTEXT_MENU_RESULT');
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.END_EMPTY));
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.END_SEND_MESSAGE));
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.END_TERMINATE));
        break;
    case 'BOUNDARY':
        modulesMenu.label = translate('LBL_PMSE_CONTEXT_MENU_EVENT');
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.BOUNDARY_RECEIVE_MESSAGE));
        modulesMenu.menu.items.push(this._createAction(AdamEvent.ACTION_TYPES.BOUNDARY_TIMER));
        break;
    }
    modulesMenu.icon = 'adam-menu-icon-convert';

    mitems.push(
        configureAction,
        {
            jtype: 'separator'
        }
    );
    if (this.evn_type !== 'START') {
        mitems.push(
            modulesMenu,
            {
                jtype: 'separator'
            }
        );
    }
    mitems.push(deleteAction);

    return {
        items: mitems
    };
};

AdamEvent.prototype.updateEventType = function (newType) {
    var layer1, layer2, updateCommand, newChanges;

    layer1 = this.getLayers().get(0);
    layer2 = this.getLayers().get(1);

    switch (newType) {
    case 'START':
        newChanges = {
            evn_type: newType,
            evn_behavior: 'CATCH',
            evn_marker: 'MESSAGE',
            evn_message: ''
        };
        break;
    case 'INTERMEDIATE':
        newChanges = {
            evn_type: newType,
            evn_behavior: 'CATCH',
            evn_marker: 'TIMER',
            evn_message: ''
        };
        break;
    case 'END':
        newChanges = {
            evn_type: newType,
            evn_behavior: 'THROW',
            evn_marker: 'TERMINATE',
            evn_message: ''
        };
        break;
    }

    updateCommand = new AdamShapeLayerCommand(
        this,
        {
            layers: [layer1, layer2],
            type: 'changeeventtype',
            changes: newChanges
        }
    );
    updateCommand.execute();

    this.canvas.commandStack.add(updateCommand);
    return this;
};

AdamEvent.prototype.updateEventMarker = function (options) {
    var layer,
        updateCommand;

    layer = this.getLayers().get(1);
    updateCommand = new AdamShapeLayerCommand(
        this,
        {
            layers: [layer],
            type: 'changeeventmarker',
            changes: options
        }
    );
    updateCommand.execute();

    this.canvas.commandStack.add(updateCommand);
    return this;
};

/**
 * Creates a new CriteriaField for ConfigureActions.
 *
 * @return {Object} The constructed CriteriaField object.
 */
AdamEvent.prototype._makeCriteriaField = function() {
    // Start with the shared settings.
    var configObject = {
        name: 'evn_criteria',
        label: translate('LBL_PMSE_FORM_LABEL_CRITERIA'),
        required: false,
        fieldWidth: 414,
        decimalSeparator: SUGAR.App.config.defaultDecimalSeparator,
        numberGroupingSeparator: SUGAR.App.config.defaultNumberGroupingSeparator,
        currencies: project.getMetadata('currencies'),
        dateFormat: App.date.getUserDateFormat(),
        timeFormat: App.user.getPreference('timepref'),
    };

    var extraConfig = {};

    switch (this.evn_type){
        case 'START':
            extraConfig = {
                fieldHeight: 80,
                panelContext: '#container',
                operators: {
                    logic: true,
                    group: true
                },
                constant: false
            };
            break;
        case 'INTERMEDIATE':
            if (this.evn_marker === 'MESSAGE' && this.evn_behavior !== 'THROW') {
                extraConfig = {
                    operators: {
                        logic: true,
                        group: true
                    },
                    constant: false,
                    evaluation: {
                        module: {
                            dataURL: 'pmse_Project/CrmData/related/' + PROJECT_MODULE,
                            dataRoot: 'result',
                            fieldDataURL: 'pmse_Project/CrmData/fields/{{MODULE}}',
                            fieldDataRoot: 'result'
                        },
                        user: {
                            defaultUsersDataURL: 'pmse_Project/CrmData/defaultUsersList',
                            defaultUsersDataRoot: 'result',
                            userRolesDataURL: 'pmse_Project/CrmData/rolesList',
                            userRolesDataRoot: 'result',
                            usersDataURL: 'pmse_Project/CrmData/users',
                            usersDataRoot: 'result'
                        }
                    }
                };
            }
            if (this.evn_marker === 'TIMER') {
                extraConfig =
                    {
                        fieldHeight: 80,
                        operators:
                            {
                                arithmetic: ['+', '-']
                            },
                        constant:
                            {
                                datetime: true,
                                timespan: true
                            },
                        variable:
                            {
                                dataURL: project.getMetadata('fieldsDataSource')
                                    .url.replace('{MODULE}', project.process_definition.pro_module),
                                dataRoot: project.getMetadata('fieldsDataSource').root,
                                dataFormat: 'hierarchical',
                                dataChildRoot: 'fields',
                                textField: 'text',
                                valueField: 'value',
                                typeField: 'type',
                                typeFilter: ['Date', 'Datetime'],
                                moduleTextField: 'text',
                                moduleValueField: 'value'
                            }
                    };
            }
            break;
    }

    $.extend(configObject, extraConfig);

    return new CriteriaField(configObject);
};

AdamEvent.prototype.createConfigureAction = function () {
    var action, w, f, proxy, items, wWidth, wHeight, changeModule, initialValue = null, disabled = false,
        oldModule, mp, cancelInformation, actiontimerType, durationRadio, i,
        repeatEveryCombo, everyOptions, repeatEveryNumberCombo, cyclicDate, fixedRadio, cyclicRadio, incrementWasClicked = false,
        durationTextField, unitComboBox, fixedDate, incrementCkeck, durationTextField2, unitComboBox2, operationCombo, criteria,
        root = this, hiddenParams, hiddenFn, callback = {}, ddlModules, ddlEmailTemplate, aTemplate, criteriaField, emailTemplates, datecriteria;

    //Event PMSE.Form PMSE.Proxy
    proxy = new SugarProxy({
        url:'pmse_Project/EventDefinition/' + this.id,
        uid: this.id,
        callback: null
    });

    changeModule = false;
    switch (this.evn_message) {
        case 'Leads':
            initialValue = 'Leads';
            break;
        case 'Opportunities':
            initialValue = 'Opportunities';
            break;
        case 'Documents':
            initialValue = 'Documents';
            break;
    }

    switch (this.evn_type) {
    case 'START':
        criteriaField = this._makeCriteriaField();

        ddlModules = new ComboboxField({
            jtype: 'combobox',
            name: 'evn_module',
            label: translate('LBL_PMSE_FORM_LABEL_MODULE'),
            required: true,
            readOnly: !changeModule,
            initialValue: initialValue,
            helpTooltip: {
                message: translate('LBL_PMSE_FORM_TOOLTIP_EVENT_MODULE')
            },
            change: function (combo, newValue, oldValue) {
                if (criteriaField.getItems().length > 0) {
                    mp.show(newValue, oldValue);
                } else {
                    //criteriaField.setRelatedModulesDataURL('pmse_Project/CrmData/related/' + this.value);//criteriaField.setBaseModule(this.value);
                    criteriaField.setModuleEvaluation({
                        dataURL: "pmse_Project/CrmData/related/" + this.value,
                        dataRoot: 'result',
                        textField: "text",
                        valueField: "value",
                        fieldDataURL: 'pmse_Project/CrmData/fields/{{MODULE}}',
                        fieldDataRoot: 'result',
                        fieldTextField: "text",
                        fieldValueField: "value",
                        fieldTypeField: "type"
                    })
                }
            },
            related: 'modules',
            //deprecated
            proxy: new SugarProxy({
                url: 'pmse_Project/CrmData/related_modules/' + PROJECT_MODULE,
                //restClient: this.canvas.project.restClient,
                uid: PROJECT_MODULE,
                callback: null
            })
        });
        items = [
            ddlModules,
            {
                jtype: 'combobox',
                name: 'evn_params',
                label: translate('LBL_PMSE_FORM_LABEL_APPLIES_TO'),
                options: [
                    {
                        text: translate('LBL_PMSE_FORM_OPTION_SELECT'),
                        value: ''
                    },
                    {
                        text: translate('LBL_PMSE_FORM_OPTION_NEW_RECORDS_ONLY'),
                        value: 'new'
                    },
                    {
                        text: translate('LBL_PMSE_FORM_OPTION_UPDATED_RECORDS_ONLY'),
                        value: 'updated'
                    },
                    {
                        text: translate('LBL_PMSE_FORM_OPTION_UPDATED_RECORDS_ONLY_AU'),
                        value: 'allupdates'
                    }
                ],
                required: true,
                change: function() {
                    criteriaField._panel._evaluationPanels.module.getItem('field').fireDependentFields();
                    criteriaField.isValid();
                },
                helpTooltip: {
                    message: translate('LBL_PMSE_FORM_TOOLTIP_WHEN_START_EVENT')
                }
            },
            criteriaField
        ];
        wHeight = 280;
        wWidth = 690;
        callback = {
            loaded: function (data) {
                root.canvas.emptyCurrentSelection();
                ddlModules.proxy.getData(null,{
                    success: function(modules) {
                        ddlModules.setValue(root.evn_message || (modules.result[0].value || null));
                        oldModule = data.evn_module;
                        criteriaField.setModuleEvaluation({
                            dataURL: "pmse_Project/CrmData/related/" + ddlModules.value,
                            dataRoot: 'result',
                            textField: "text",
                            valueField: "value",
                            fieldDataURL: 'pmse_Project/CrmData/fields/{{MODULE}}',
                            fieldDataRoot: 'result',
                            fieldTextField: "text",
                            fieldValueField: "value",
                            fieldTypeField: "type"
                        }).setUserEvaluation({
                            defaultUsersDataURL: 'pmse_Project/CrmData/defaultUsersList',
                            defaultUsersDataRoot: 'result',
                            defaultUsersValueField: "value",
                            userRolesDataURL: 'pmse_Project/CrmData/rolesList',
                            userRolesDataRoot: 'result',
                            usersDataURL: 'pmse_Project/CrmData/users',
                            usersDataRoot: 'result',
                            usersValueField: "value"
                        });
                        App.alert.dismiss('upload');
                        w.html.style.display = 'inline';
                        criteriaField.isValid();
                    }
                });
            }
        };
        mp = {
            _messagePanel: null,
            show: function(comboNewValue, comboOldValue) {
                this._messagePanel = new MessagePanel({
                    title: "Module change warning",
                    wtype: 'Confirm',
                    message: translate('LBL_PMSE_MESSAGE_REMOVE_ALL_START_CRITERIA'),
                    buttons: [
                        {
                            jtype: 'normal',
                            caption: translate('LBL_PMSE_BUTTON_OK'),
                            handler: function () {
                                //criteriaField.clear().setRelatedModulesDataURL("pmse_Project/CrmData/related/" + comboNewValue); //criteriaField.clear().setBaseModule(ddlModules.value);
                                criteriaField.clear().setModuleEvaluation({
                                    dataURL: "pmse_Project/CrmData/related/" + comboNewValue,
                                    dataRoot: 'result',
                                    textField: "text",
                                    valueField: "value",
                                    fieldDataURL: 'pmse_Project/CrmData/fields/{{MODULE}}',
                                    fieldDataRoot: 'result',
                                    fieldTextField: "text",
                                    fieldValueField: "value",
                                    fieldTypeField: "type"
                                });
                                mp.hide();
                            }
                        },
                        {
                            jtype: 'normal',
                            caption: translate('LBL_PMSE_BUTTON_CANCEL'),
                            handler: function () {
                                ddlModules.setValue(comboOldValue);
                                mp.hide();
                            }
                        }
                    ]
                });
                this._messagePanel.show();
            },
            hide: function() {
                if (this._messagePanel) {
                    this._messagePanel.hide();
                }
            }
        };
        break;
    case 'INTERMEDIATE':
        if (this.evn_marker === 'MESSAGE') {
            if (this.evn_behavior === 'THROW') {
                ddlEmailTemplate = new ComboboxField({
                    jtype: 'combobox',
                    required: true,
                    //related: 'templates',
                    name: 'evn_criteria',
                    label: translate('LBL_PMSE_FORM_LABEL_EMAIL_TEMPLATE'),
                    proxy: new SugarProxy({
                        url: 'pmse_Project/CrmData/emailtemplates',
                        uid: "",
                        callback: null
                    })
                });
                ddlModules = new ComboboxField({
                    jtype: 'combobox',
                    required: true,
                    //related: 'beans',
                    name: 'evn_module',
                    value: initialValue,
                    initialValue: initialValue,
                    readOnly: true,
                    label: translate('LBL_PMSE_FORM_LABEL_MODULE'),
                    proxy: new SugarProxy({
                        url: 'pmse_Project/CrmData/modules',
                        uid: "",
                        callback: null
                    }),
                    change: function () {
                        ddlEmailTemplate.proxy.uid = this.value;
                        ddlEmailTemplate.proxy.url = 'pmse_Project/CrmData/emailtemplates/' + this.value;
                        ddlEmailTemplate.removeOptions();
                        aTemplate = [{'text': translate('LBL_PMSE_FORM_OPTION_SELECT'), 'value': ''}];
                        ddlEmailTemplate.proxy.getData(null,{
                            success: function(emailTemplates){
                                aTemplate = aTemplate.concat(emailTemplates.result);

                                //if(emailTemplates && emailTemplates.success) {
                                ddlEmailTemplate.setOptions(aTemplate);
                            }
                        });

                        //}
                    }
                });
                hiddenParams = new HiddenField({name: 'evn_params'});
                hiddenFn = function () {
                    var parentForm = this.parent, address = {};

                    //address['to'] = parentForm.items[2].getObject();
                    //address['cc'] = parentForm.items[3].getObject();
                    //address['bcc'] = parentForm.items[4].getObject();
                    address.to = parentForm.items[2].getObject();
                    address.cc = parentForm.items[3].getObject();
                    address.bcc = parentForm.items[4].getObject();
                    hiddenParams.setValue(JSON.stringify(address));
                };
                items = [
                    ddlModules,
                    ddlEmailTemplate,
                    {
                        jtype: 'emailpicker',
                        label: translate('LBL_PMSE_FORM_LABEL_EMAIL_TO'),
                        name: 'address_to',
                        required: true,
                        submit: false,
                        fieldWidth: 350,
                        change: hiddenFn,
                        suggestionItemName: 'fullName',
                        suggestionItemAddress: 'emailAddress',
                        suggestionDataURL: "pmse_Project/CrmData/emails/{$0}",
                        suggestionDataRoot: "result",
                        teams: project.getMetadata('teams') || []
                    },
                    {
                        jtype: 'emailpicker',
                        label: translate('LBL_PMSE_FORM_LABEL_EMAIL_CC'),
                        name: 'address_cc',
                        required: false,
                        submit: false,
                        fieldWidth: 350,
                        change: hiddenFn,
                        suggestionItemName: 'fullName',
                        suggestionItemAddress: 'emailAddress',
                        suggestionDataURL: "pmse_Project/CrmData/emails/{$0}",
                        suggestionDataRoot: "result",
                        teams: project.getMetadata('teams') || []
                    },
                    {
                        jtype: 'emailpicker',
                        label: translate('LBL_PMSE_FORM_LABEL_EMAIL_BCC'),
                        name: 'address_bcc',
                        required: false,
                        submit: false,
                        fieldWidth: 350,
                        change: hiddenFn,
                        suggestionItemName: 'fullName',
                        suggestionItemAddress: 'emailAddress',
                        suggestionDataURL: "pmse_Project/CrmData/emails/{$0}",
                        suggestionDataRoot: "result",
                        teams: project.getMetadata('teams') || []
                    },
                    hiddenParams
                ];
                wHeight = 380;
                wWidth = 600;
                callback = {
                    loaded: function (data) {
                        var params = null, i, emailPickerFields = [], dataSource, auxProxy;
                        root.canvas.emptyCurrentSelection();
                        if (data && data.evn_params) {
                            try {
                                params = JSON.parse(data.evn_params);
                            } catch (e) {}
                            if (params) {
                                hiddenParams.setValue(data.evn_params);
                                for (i = 0; i < f.items.length; i += 1) {
                                    switch (f.items[i].name) {
                                    case 'address_to':
                                        f.items[i].setValue(params.to);
                                        emailPickerFields.push(i);
                                        break;
                                    case 'address_cc':
                                        f.items[i].setValue(params.cc);
                                        emailPickerFields.push(i);
                                        break;
                                    case 'address_bcc':
                                        f.items[i].setValue(params.bcc);
                                        emailPickerFields.push(i);
                                        break;
                                    }
                                }
                            }
                        }

                        ddlModules.proxy.getData(null, {
                            success: function(params) {
                                if (params && params.result) {
                                    ddlModules.setOptions(params.result);
                                    ddlModules.setValue(data.evn_module || PROJECT_MODULE);
                                }

                                ddlEmailTemplate.proxy.uid = ddlModules.value;
                                ddlEmailTemplate.proxy.url = 'pmse_Project/CrmData/emailtemplates/' + ddlModules.value;
                                aTemplate = [{'text': translate('LBL_PMSE_FORM_OPTION_SELECT'), 'value': ''}];
                                ddlEmailTemplate.proxy.getData(null, {
                                    success: function(params2) {
                                        aTemplate = aTemplate.concat(params2.result);
                                        ddlEmailTemplate.setOptions(aTemplate);
                                        if (params2 && params2.result) {
                                            ddlEmailTemplate.setValue(data.evn_criteria || ((params2.result[0] && params2.result[0].value) || null));
                                        }
                                        App.alert.dismiss('upload');
                                        w.html.style.display = 'inline';
                                    }
                                });
                            }
                        });

                        //We load the teams
                        project.addMetadata("teams", {
                            dataURL: project.getMetadata("teamsDataSource").url,
                            dataRoot: project.getMetadata("teamsDataSource").root,
                            success: function(data) {
                                var i;
                                if(emailPickerFields.length) {
                                    for (i = 0; i < emailPickerFields.length; i += 1) {
                                        f.items[emailPickerFields[i]].setTeamTextField("text");
                                        f.items[emailPickerFields[i]].setTeams(data);
                                    }
                                } else {
                                    for (i = 0; i < f.items.length; i += 1) {
                                        switch (f.items[i].name) {
                                        case 'address_to':
                                        case 'address_cc':
                                        case 'address_bcc':
                                            f.items[i].setTeamTextField("text");
                                            f.items[i].setTeams(data);
                                            break;
                                        }
                                    }
                                }

                            }
                        });

                        project.addMetadata("roles", {
                            dataURL: 'pmse_Project/CrmData/rolesList',
                            dataRoot: "result",
                            success: function (data) {
                                var i;
                                if (emailPickerFields.length) {
                                    for (i = 0; i < emailPickerFields.length; i += 1) {
                                        f.items[emailPickerFields[i]].setRoleTextField("text");
                                        f.items[emailPickerFields[i]].setRoles(data);
                                    }
                                } else {
                                    for (i = 0; i < f.items.length; i += 1) {
                                        switch (f.items[i].name) {
                                        case 'address_to':
                                        case 'address_cc':
                                        case 'address_bcc':
                                            f.items[i].setRoleTextField("text");
                                            f.items[i].setRoles(data);
                                            break;
                                        }
                                    }
                                }
                            }
                        });

                        auxProxy = new SugarProxy({
                            url: 'pmse_Project/CrmData/related/' + PROJECT_MODULE
                        });
                        auxProxy.getData({cardinality: 'one'}, {
                            success: function (data) {
                                var i;
                                data = data.result;
                                data.unshift({value: "", text: "Select..."});
                                if (emailPickerFields.length) {
                                    for (i = 0; i < emailPickerFields.length; i += 1) {
                                        f.items[emailPickerFields[i]].setModules(data);
                                    }
                                } else {
                                    for (i = 0; i < f.items.length; i += 1) {
                                        switch (f.items[i].name) {
                                        case 'address_to':
                                        case 'address_cc':
                                        case 'address_bcc':
                                            f.items[i].setModules(data);
                                            break;
                                        }
                                    }
                                }
                            }
                        });
                    },
                    submit: function (data) {

                    }
                };
            } else {
                items = [
                    this._makeCriteriaField()
                ];
                wHeight = 185;
                wWidth = 690;
                callback = {
                    loaded: function (data) {
                        root.canvas.emptyCurrentSelection();
                        App.alert.dismiss('upload');
                        w.html.style.display = 'inline';
                    }
                };
            }
        }
        if (this.evn_marker === 'TIMER') {
            actiontimerType = new HiddenField({name: 'evn_timer_type'});

            durationTextField  = new NumberField(
                {
                    jtype: 'text',
                    //validators: [
                    //    {
                    //        jtype: 'integer',
                    //        errorMessage: translate('LBL_PMSE_ADAM_UI_ERROR_INVALID_INTEGER')
                    //    }
                    //],
                    name: 'evn_duration_criteria',
                    label: translate('LBL_PMSE_FORM_LABEL_DURATION'),
                    required: true,
                    helpTooltip: {
                        message: translate('LBL_PMSE_FORM_TOOLTIP_DURATION')
                    },
                    fieldWidth: '50px'
                    //readOnly: true
                }
            );

            unitComboBox = new ComboboxField(
                {
                    //jtype: 'combobox',
                    name: 'evn_duration_params',
                    label: translate('LBL_PMSE_FORM_LABEL_UNIT'),
                    options: [
                        { text: translate('LBL_PMSE_FORM_OPTION_DAYS'), value: 'day'},
                        { text: translate('LBL_PMSE_FORM_OPTION_HOURS'), value: 'hour'},
                        { text: translate('LBL_PMSE_FORM_OPTION_MINUTES'), value: 'minute'}
                    ],
                    initialValue: 'hour'
                    //required: true
                    //readOnly: true
                }
            );

            everyOptions = [];
            for (i = 1; i <= 30; i += 1) {
                everyOptions.push({text: translate(i), value: i});
            }

            repeatEveryNumberCombo = new ComboboxField(
                {
                    //jtype: 'combobox',
                    name: 'evn_cyclic_repeat_every',
                    label: translate('LBL_PMSE_LABEL_REPEATSEVERY'),
                    options: everyOptions,
                    initialValue: 1,
                    required: true
                    //readOnly: true
                }
            );

            cyclicDate  = new DateField(
                {
                    name: 'evn_cyclic_date',
                    label: translate('LBL_PMSE_LABEL_BEGINS'),
                    required: true,
                    fieldWidth: '100px',
                    readOnly: true
                }
            );

            durationRadio = new RadiobuttonField({
                jtype: 'radio',
                name: 'evn_timer_type',
                label: translate('LBL_PMSE_FORM_LABEL_DURATION'),
                value : true,
                labelAlign: 'right',
                onClick: function (e, ui) {
                    actiontimerType.setValue('duration');

                    durationTextField.enable();
                    unitComboBox.enable();
                    datecriteria.disable();
                    datecriteria.clear();
                    datecriteria.isValid();
                    //fixedDate.disable();
                    //incrementCkeck.disable();
                    //durationTextField2.disable();
                    //unitComboBox2.disable();
                    //operationCombo.disable();
                    //repeatEveryCombo.disable();
                    //repeatEveryNumberCombo.disable();
                    //cyclicDate.disable();

                }
            });
            fixedRadio = new RadiobuttonField({
                jtype: 'radio',
                name: 'evn_timer_type',
                label: translate('LBL_PMSE_FORM_LABEL_FIXED_DATE'),
                reverse : true,
                labelAlign: 'right',
                onClick: function (e, ui) {
                    actiontimerType.setValue('fixed date');
                    durationTextField.disable();
                    unitComboBox.disable();
                    datecriteria.enable();
                }
            });

            datecriteria = this._makeCriteriaField();

            cyclicRadio = new RadiobuttonField({
                jtype: 'radio',
                name: 'evn_timer_type',
                label: translate('LBL_PMSE_LABEL_CYCLIC'),
                reverse : true,
                labelAlign: 'right',
                onClick: function (e, ui) {
                    actiontimerType.setValue('cyclic');
                    durationTextField.disable();
                    unitComboBox.disable();
                }

            });

            items = [
                actiontimerType,
                durationRadio,
                durationTextField,
                unitComboBox,
                fixedRadio,
                datecriteria
            ];
            wHeight = 450;
            wWidth = 690;
            callback = {
                loaded: function (data) {
                    root.canvas.emptyCurrentSelection();
                    switch (data.evn_params) {
                    case 'fixed date':
                        durationRadio.setValue(false);
                        fixedRadio.setValue(true);
                        actiontimerType.setValue('fixed date');
                        durationTextField.disable();
                        unitComboBox.disable();
                        datecriteria.enable();

                        break;
                    case 'cyclic':
                        actiontimerType.setValue('cyclic');
                        durationTextField.disable();
                        unitComboBox.disable();
                        break;
                    default:
                        actiontimerType.setValue('duration');
                        durationRadio.setValue(true);
                        fixedRadio.setValue(false);
                        durationTextField.enable();
                        durationTextField.setValue(data.evn_criteria || '');
                        unitComboBox.enable();
                        unitComboBox.setValue(data.evn_params || 'minute');
                        datecriteria.disable();
                        break;
                    }
                    App.alert.dismiss('upload');
                    w.html.style.display = 'inline';
                },
                submit: function (data) {

                }
            };
        }
        break;
    case 'END':
        if (this.evn_marker === 'MESSAGE') {
            ddlEmailTemplate = new ComboboxField({
                jtype: 'combobox',
                name: 'evn_criteria',
                label: translate('LBL_PMSE_FORM_LABEL_EMAIL_TEMPLATE'),
                proxy: new SugarProxy({
                    url: 'pmse_Project/CrmData/emailtemplates',
                    uid: "",
                    callback: null
                })
            });
            ddlModules = new ComboboxField({
                jtype: 'combobox',
                required: true,
                name: 'evn_module',
                initialValue: initialValue,
                readOnly: true,
                label: translate('LBL_PMSE_FORM_LABEL_MODULE'),
                proxy: new SugarProxy({
                    url: 'pmse_Project/CrmData/modules',
                    uid: "",
                    callback: null
                }),
                change: function () {
                    ddlEmailTemplate.proxy.uid = this.value;
                    ddlEmailTemplate.proxy.url = 'pmse_Project/CrmData/emailtemplates/' + this.value;
                    ddlEmailTemplate.removeOptions();
                    aTemplate = [{'text': translate('LBL_PMSE_FORM_OPTION_SELECT'), 'value': ''}];
                    ddlEmailTemplate.proxy.getData(null, {
                        success: function (emailTemplates) {
                            aTemplate = aTemplate.concat(emailTemplates.result);
                            ddlEmailTemplate.setOptions(aTemplate);
                        }
                    });
                }
            });
            hiddenParams = new HiddenField({name: 'evn_params'});
            hiddenFn = function () {
                var parentForm = this.parent, address = {};
                address.to = parentForm.items[2].getObject();
                address.cc = parentForm.items[3].getObject();
                address.bcc = parentForm.items[4].getObject();
                hiddenParams.setValue(JSON.stringify(address));
            };
            items = [
                ddlModules,
                ddlEmailTemplate,
                {
                    jtype: 'emailpicker',
                    label: translate('LBL_PMSE_FORM_LABEL_EMAIL_TO'),
                    name: 'address_to',
                    required: true,
                    submit: false,
                    fieldWidth: 350,
                    change: hiddenFn,
                    suggestionItemName: 'fullName',
                    suggestionItemAddress: 'emailAddress',
                    suggestionDataURL: "pmse_Project/CrmData/emails/{$0}",
                    suggestionDataRoot: "result",
                    teams: project.getMetadata('teams') || []
                },
                {
                    jtype: 'emailpicker',
                    label: translate('LBL_PMSE_FORM_LABEL_EMAIL_CC'),
                    name: 'address_cc',
                    required: false,
                    submit: false,
                    fieldWidth: 350,
                    change: hiddenFn,
                    suggestionItemName: 'fullName',
                    suggestionItemAddress: 'emailAddress',
                    suggestionDataURL: "pmse_Project/CrmData/emails/{$0}",
                    suggestionDataRoot: "result",
                    teams: project.getMetadata('teams') || []
                },
                {
                    jtype: 'emailpicker',
                    label: translate('LBL_PMSE_FORM_LABEL_EMAIL_BCC'),
                    name: 'address_bcc',
                    required: false,
                    submit: false,
                    fieldWidth: 350,
                    change: hiddenFn,
                    suggestionItemName: 'fullName',
                    suggestionItemAddress: 'emailAddress',
                    suggestionDataURL: "pmse_Project/CrmData/emails/{$0}",
                    suggestionDataRoot: "result",
                    teams: project.getMetadata('teams') || []
                },
                hiddenParams
            ];
            wHeight = 380;
            wWidth = 600;
            callback = {
                loaded: function (data) {
                    var params = null, i, emailPickerFields = [], dataSource, auxProxy;
                    root.canvas.emptyCurrentSelection();
                    if (data && data.evn_params) {
                        try {
                            params = JSON.parse(data.evn_params);
                        } catch (e) {
                        }
                        if (params) {
                            hiddenParams.setValue(data.evn_params);
                            for (i = 0; i < f.items.length; i += 1) {
                                switch (f.items[i].name) {
                                    case 'address_to':
                                        f.items[i].setValue(params.to);
                                        emailPickerFields.push(i);
                                        break;
                                    case 'address_cc':
                                        f.items[i].setValue(params.cc);
                                        emailPickerFields.push(i);
                                        break;
                                    case 'address_bcc':
                                        f.items[i].setValue(params.bcc);
                                        emailPickerFields.push(i);
                                        break;
                                }
                            }
                        }
                    }

                    ddlModules.proxy.getData(null, {
                        success: function (params) {
                            if (params && params.result) {
                                ddlModules.setOptions(params.result);
                                ddlModules.setValue(data.evn_module || ((params.result[0] && params.result[0].value) || null));
                            }

                            ddlEmailTemplate.proxy.uid = ddlModules.value;
                            ddlEmailTemplate.proxy.url = 'pmse_Project/CrmData/emailtemplates/' + ddlModules.value;
                            aTemplate = [{'text': translate('LBL_PMSE_FORM_OPTION_SELECT'), 'value': ''}];
                            ddlEmailTemplate.proxy.getData(null, {
                                success: function(params) {
                                    aTemplate = aTemplate.concat(params.result);
                                    ddlEmailTemplate.setOptions(aTemplate);
                                    App.alert.dismiss('upload');
                                    w.html.style.display = 'inline';
                                }
                            });
                        }
                    });

                    //We load the teams
                    project.addMetadata("teams", {
                        dataURL: project.getMetadata("teamsDataSource").url,
                        dataRoot: project.getMetadata("teamsDataSource").root,
                        success: function (data) {
                            var i;
                            if (emailPickerFields.length) {
                                for (i = 0; i < emailPickerFields.length; i += 1) {
                                    f.items[emailPickerFields[i]].setTeamTextField("text");
                                    f.items[emailPickerFields[i]].setTeams(data);
                                }
                            } else {
                                for (i = 0; i < f.items.length; i += 1) {
                                    switch (f.items[i].name) {
                                        case 'address_to':
                                        case 'address_cc':
                                        case 'address_bcc':
                                            f.items[i].setTeamTextField("text");
                                            f.items[i].setTeams(data);
                                            break;
                                    }
                                }
                            }

                        }
                    });

                    project.addMetadata("roles", {
                        dataURL: 'pmse_Project/CrmData/rolesList',
                        dataRoot: "result",
                        success: function (data) {
                            var i;
                            if (emailPickerFields.length) {
                                for (i = 0; i < emailPickerFields.length; i += 1) {
                                    f.items[emailPickerFields[i]].setRoleTextField("text");
                                    f.items[emailPickerFields[i]].setRoles(data);
                                }
                            } else {
                                for (i = 0; i < f.items.length; i += 1) {
                                    switch (f.items[i].name) {
                                        case 'address_to':
                                        case 'address_cc':
                                        case 'address_bcc':
                                            f.items[i].setRoleTextField("text");
                                            f.items[i].setRoles(data);
                                            break;
                                    }
                                }
                            }
                        }
                    });

                    auxProxy = new SugarProxy({
                        url: 'pmse_Project/CrmData/related/' + PROJECT_MODULE
                    });
                    auxProxy.getData({cardinality: 'one'}, {
                        success: function (data) {
                            var i;
                            data = data.result;
                            data.unshift({value: "", text: "Select..."});
                            if (emailPickerFields.length) {
                                for (i = 0; i < emailPickerFields.length; i += 1) {
                                    f.items[emailPickerFields[i]].setModules(data);
                                }
                            } else {
                                for (i = 0; i < f.items.length; i += 1) {
                                    switch (f.items[i].name) {
                                        case 'address_to':
                                        case 'address_cc':
                                        case 'address_bcc':
                                            f.items[i].setModules(data);
                                            break;
                                    }
                                }
                            }
                        }
                    });
                },
                submit: function (data) {

                }
            };
        }
        break;
    }

    f = new PMSE.Form({
        proxy: proxy,
        closeContainerOnSubmit: true,
        items: items,
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
                    $('.hasDatepicker').datepicker('hide');
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
        callback: callback,
        language: PMSE_DESIGNER_FORM_TRANSLATIONS
    });

    w = new PMSE.Window({
        width: wWidth,
        height: wHeight,
        modal: true,
        title: translate('LBL_PMSE_FORM_TITLE_LABEL_EVENT') + ': ' + this.getName()
    });
    w.addPanel(f);

    if (this.evn_type === 'BOUNDARY') {
        disabled = true;
    }

    if (this.evn_type === 'END' && this.evn_marker !== 'MESSAGE') {
        disabled = true;
    }

    action = new PMSE.Action({
        text: translate('LBL_PMSE_CONTEXT_MENU_SETTINGS'),
        cssStyle : 'adam-menu-icon-configure',
        handler: function () {
            root.saveProject(root, App, w);
        },
        disabled: disabled
    });

    return action;
};

/**
 * Stringifies the AdamEvent object
 * @return {Object}
 */
AdamEvent.prototype.stringify = function () {
    var inheritedJSON = AdamShape.prototype.stringify.call(this),
        thisJSON = {
            //evn_type: this.getType(),
            evn_marker: this.getEventMarker(),
            evn_message: this.getEventMessage(),
            //evn_behavior: this.evn_behavior,
            evn_condition: this.evn_condition,
            evn_attached_to: this.evn_attached_to,
            evn_is_interrupting: this.evn_is_interrupting,
            evn_behavior: this.evn_behavior
        };
    $.extend(true, inheritedJSON, thisJSON);
    return inheritedJSON;
};
