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
/*global jCore,
 */
/**
 * @class CommandAdam
 * Extends jCore.Command to handle changes of custom shapes
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} receiver
 * @param {Array} propertyNames
 * @param {Array} newValues
 *
 */
var CommandAdam = function (receiver, propertyNames, newValues) {
    var oldValues = [],
        i;
    jCore.Command.call(this, receiver);
    for (i = 0; i < propertyNames.length; i += 1) {
        oldValues.push(receiver[propertyNames[i]]);
    }
    /**
     * Defines the old values
     * @type {Array}
     * @private
     */
    this.oldValues = oldValues;
    /**
     * Defines the new values
     * @type {Array}
     * @private
     */
    this.newValues = newValues;
    /**
     * Define the property names
     * @type {Array}
     * @private
     */
    this.propertyNames = propertyNames;
};
CommandAdam.prototype = new jCore.Command();
/**
 * Define the object type
 * @type {String}
 */
CommandAdam.prototype.type = 'CommandAdam';
/**
 * Execute the command
 */
CommandAdam.prototype.execute = function () {
    var e;
    for (e = 0;  e < this.newValues.length; e += 1) {
        this.receiver[this.propertyNames[e]] = this.newValues[e];
    }
    this.canvas.triggerCommandAdam(this.receiver, this.propertyNames, this.oldValues, this.newValues);
};
/**
 * Execute de UNDO action
 */
CommandAdam.prototype.undo = function () {
    var e;
    for (e = 0;  e < this.newValues.length; e += 1) {
        this.receiver[this.propertyNames[e]] = this.oldValues[e];
    }
    this.canvas.triggerCommandAdam(this.receiver, this.propertyNames, this.newValues, this.oldValues);
};
/**
 * Execute de REDO action
 */
CommandAdam.prototype.redo = function () {
    this.execute();
};

var AdamShapeLayerCommand = function (receiver, options) {
    jCore.Command.call(this, receiver);
    this.layers = [];
    this.updateType = null;
    this.beforeStyle = [];
    this.beforeValues = [];
    this.afterStyle = [];
    this.afterValues = [];
    this.beforeName = null;
    this.afterName = null;
    this.propertyNames = [];
    AdamShapeLayerCommand.prototype.initObject.call(this, receiver, options);
};

AdamShapeLayerCommand.prototype = new jCore.Command();

AdamShapeLayerCommand.prototype.type = "AdamShapeLayerCommand";

AdamShapeLayerCommand.prototype.initObject = function (receiver, options) {
    var i, newZoom, css, marker, type;
    this.updateType = options.type;
    this.layers = options.layers;
    switch (this.updateType) {
    case 'changetypegateway':

        this.beforeStyle.push(this.layers[0].zoomSprites);
        newZoom = [];
        for (i = 0; i < this.beforeStyle[0].length; i += 1) {
            newZoom.push('adam-shape-' + ((i * 25) + 50) + '-gateway-' + options.changes.toLowerCase());
        }
        this.afterStyle.push(newZoom);
        this.propertyNames.push('gat_type');
        this.beforeValues.push(receiver['gat_type']);
        this.afterValues.push(options.changes);
        if (parseInt(receiver['gat_default_flow']) !== 0 &&
            (options.changes === 'PARALLEL'
                || options.changes === 'EVENTBASED')) {

            this.propertyNames.push('gat_default_flow');
            this.beforeValues.push(receiver['gat_default_flow']);
            this.afterValues.push(0);
        }
        if (options.changes === 'EVENTBASED') {
            this.propertyNames.push('gat_direction');
            this.beforeValues.push(receiver['gat_direction']);
            this.afterValues.push('UNSPECIFIED');
        }

        break;
    case 'changeeventmarker':
        this.beforeStyle.push(this.layers[0].zoomSprites);
        newZoom = [];
        marker = (options.changes.evn_message  && (options.changes.evn_message !== "")) ? options.changes.evn_message : options.changes.evn_marker;
        type = (this.receiver.evn_type === "BOUNDARY") ? 'INTERMEDIATE' : this.receiver.evn_type;
        for (i = 0; i < this.beforeStyle[0].length; i += 1) {
            css = 'adam-marker-' + ((i * 25) + 50) + '-' + type.toLowerCase();
            css += '-' + options.changes.evn_behavior.toLowerCase() + '-';
            css += marker.toLowerCase();
            newZoom.push(css);
        }
        this.afterStyle.push(newZoom);
        if (typeof options.changes.evn_behavior !== 'undefined') {
            this.propertyNames.push('evn_behavior');
            this.beforeValues.push(this.receiver['evn_behavior']);
            this.afterValues.push(options.changes.evn_behavior);
        }
        if (typeof options.changes.evn_marker !== 'undefined') {
            this.propertyNames.push('evn_marker');
            this.beforeValues.push(this.receiver['evn_marker']);
            this.afterValues.push(options.changes.evn_marker);
        }

        this.beforeName = this.afterName = receiver.getName();

        if (options.changes.evn_name !== undefined) {
            this.afterName = options.changes.evn_name;
        }

        this.propertyNames.push('evn_message');
        this.beforeValues.push(this.receiver['evn_message']);
        this.afterValues.push(options.changes.evn_message || '');

        break;
    case 'changeeventtype':
        this.beforeStyle.push(this.layers[0].zoomSprites);
        newZoom = [];
        for (i = 0; i < this.beforeStyle[0].length; i += 1) {
            newZoom.push('adam-shape-' + ((i * 25) + 50) + '-event-' + options.changes.evn_type.toLowerCase());
        }
        this.afterStyle.push(newZoom);
        newZoom = [];
        this.beforeStyle.push(this.layers[1].zoomSprites);
        for (i = 0; i < this.beforeStyle[1].length; i += 1) {
            css = 'adam-marker-' + ((i * 25) + 50) + '-';
            css += options.changes.evn_type.toLowerCase() + '-';
            css += options.changes.evn_behavior.toLowerCase() + '-';
            css += options.changes.evn_marker.toLowerCase();
            newZoom.push(css);
        }
        this.afterStyle.push(newZoom);
        if (typeof options.changes.evn_type !== 'undefined') {
            this.propertyNames.push('evn_type');
            this.beforeValues.push(this.receiver['evn_type']);
            this.afterValues.push(options.changes.evn_type);
        }
        if (typeof options.changes.evn_behavior !== 'undefined') {
            this.propertyNames.push('evn_behavior');
            this.beforeValues.push(this.receiver['evn_behavior']);
            this.afterValues.push(options.changes.evn_behavior);
        }
        if (typeof options.changes.evn_marker !== 'undefined') {
            this.propertyNames.push('evn_marker');
            this.beforeValues.push(this.receiver['evn_marker']);
            this.afterValues.push(options.changes.evn_marker);
        }
        if (typeof options.changes.evn_message !== 'undefined') {
            this.propertyNames.push('evn_message');
            this.beforeValues.push(this.receiver['evn_message']);
            this.afterValues.push(options.changes.evn_message);
        }

        break;
    case 'changescripttypeactivity':
        this.beforeStyle.push(this.layers[0].zoomSprites);
        newZoom = [];
        for (i = 0; i < this.beforeStyle[0].length; i += 1) {
            newZoom.push('adam-shape-' + ((i * 25) + 50) + '-activity-scripttask-' + options.changes.toLowerCase());
        }
        this.afterStyle.push(newZoom);
        this.propertyNames.push('act_script_type');
        this.beforeValues.push(receiver['act_script_type']);
        this.afterValues.push(options.changes);
        break;
    }
};

AdamShapeLayerCommand.prototype.switchDefaultFlow = function (id, newVal, oldVal) {
    var connection, updatedElement;
    connection = this.receiver.canvas.connections.find('id', id);
    connection.changeFlowType(newVal.toLowerCase());
    connection.setFlowType(newVal);
    updatedElement = [{
        id: connection.getID(),
        type: connection.type,
        relatedObject: connection,
        fields: [{
            field: "type",
            newVal: newVal,
            oldVal: oldVal
        }]
    }];
    this.receiver.getCanvas().triggerDefaultFlowChangeEvent(updatedElement);
};

AdamShapeLayerCommand.prototype.execute = function () {
    var i;
    for (i = 0; i < this.layers.length; i += 1) {
        this.layers[i].zoomSprites = this.afterStyle[i];
        this.layers[i].paint();
    }
    for (i = 0; i < this.propertyNames.length; i += 1) {
        this.receiver[this.propertyNames[i]] = this.afterValues[i];
        if (this.propertyNames[i] === 'gat_default_flow') {
            this.switchDefaultFlow(this.beforeValues[i], 'SEQUENCE', 'DEFAULT');
        }
        if (this.receiver.getType() === 'AdamGateway') {
            this.receiver.updateFlowConditions();
        }

    }
    if (this.afterName !== null) {
        this.receiver.setName(this.afterName).label.textField.value = this.afterName;
    }
    this.canvas.triggerCommandAdam(this.receiver, this.propertyNames, this.beforeValues, this.afterValues);
};

AdamShapeLayerCommand.prototype.undo = function () {
    var i;
    for (i = 0; i < this.layers.length; i += 1) {
        this.layers[i].zoomSprites = this.beforeStyle[i];
        this.layers[i].paint();
    }
    for (i = 0; i < this.propertyNames.length; i += 1) {
        this.receiver[this.propertyNames[i]] = this.beforeValues[i];
        if (this.propertyNames[i] === 'gat_default_flow') {
            this.switchDefaultFlow(this.beforeValues[i], 'DEFAULT', 'SEQUENCE');
        }
    }
    if (this.beforeName !== null) {
        this.receiver.setName(this.beforeName).label.textField.value = this.beforeName;
    }
    this.canvas.triggerCommandAdam(this.receiver, this.propertyNames, this.afterValues, this.beforeValues);
};

AdamShapeLayerCommand.prototype.redo = function () {
    this.execute();
};

var AdamShapeMarkerCommand = function (receiver, options) {
    jCore.Command.call(this, receiver);

    this.updateType = null;
    this.markers = [];
    this.beforeMarkerStyle = [];
    this.beforeMarkerType = [];
    this.afterMarkerStyle = [];
    this.afterMarkerType = [];
    this.beforeValues = [];
    this.afterValues = [];
    this.propertyNames = [];

    AdamShapeMarkerCommand.prototype.initObject.call(this, receiver, options);
};

AdamShapeMarkerCommand.prototype = new jCore.Command();

AdamShapeMarkerCommand.prototype.type = 'AdamShapeMarkerCommand';

AdamShapeMarkerCommand.prototype.initObject = function (receiver, options) {
    var i, newZoom;

    this.updateType = options.type;
    this.markers = options.markers;

    switch (this.updateType) {
    case 'changeactivitymarker':
        this.beforeMarkerStyle.push(this.markers[0].markerZoomClasses);
        newZoom = [];
        for (i = 0; i < this.beforeMarkerStyle[0].length; i += 1) {
            newZoom.push('adam-marker-' + ((i * 25) + 50) + '-' + options.changes.taskType.toLowerCase());
        }
        this.afterMarkerStyle.push(newZoom);
        this.beforeMarkerType.push(this.receiver['act_task_type']);
        this.afterMarkerType.push(options.changes.taskType);

        this.propertyNames.push('act_task_type');
        this.beforeValues.push(this.receiver['act_task_type']);
        this.afterValues.push(options.changes.taskType);

        break;
    }
};

AdamShapeMarkerCommand.prototype.execute = function () {
    var i;

    for (i = 0; i < this.markers.length; i += 1) {
        this.markers[i].markerType = this.afterMarkerType[i];
        this.markers[i].markerZoomClasses = this.afterMarkerStyle[i];
        this.markers[i].paint(true);
    }
    for (i = 0; i < this.propertyNames.length; i += 1) {
        this.receiver[this.propertyNames[i]] = this.afterValues[i];
    }
    this.canvas.triggerCommandAdam(this.receiver, this.propertyNames, this.beforeValues, this.afterValues);
};

AdamShapeMarkerCommand.prototype.undo = function () {
    var i;
    for (i = 0; i < this.markers.length; i += 1) {
        this.markers[i].markerType = this.beforeMarkerType[i];
        this.markers[i].markerZoomClasses = this.beforeMarkerStyle[i];
        this.markers[i].paint(true);
    }
    for (i = 0; i < this.propertyNames.length; i += 1) {
        this.receiver[this.propertyNames[i]] = this.beforeValues[i];
    }
    this.canvas.triggerCommandAdam(this.receiver, this.propertyNames, this.afterValues, this.beforeValues);
};

AdamShapeMarkerCommand.prototype.redo = function () {
    this.execute();
};

