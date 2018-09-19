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
/*global jCore, $, AdamShape */
/**
 * @class AdamFlow
 * Handle the designer flows
 *
 * @constructor
 * Create a new flow object
 * @param {Object} options
 */
var AdamFlow = function (options) {
    jCore.Connection.call(this, options);
    /**
     * Unique Idenfier
     * @type {String}
     */
    this.flo_uid = null;
    /**
     * Defines the connecion/flow type
     * @type {String}
     */
    this.flo_type = null;
    /**
     * Defines the connection/flow name
     * @type {String}
     */
    this.flo_name = null;
    /**
     * Unique Identifier of the source shape
     * @type {String}
     */
    this.flo_element_origin = null;
    /**
     * Defines the type of shape for the source
     * @type {String}
     */
    this.flo_element_origin_type = null;
    /**
     * Unique Identifier of the target shape
     * @type {String}
     */
    this.flo_element_dest = null;
    /**
     * Defines the type of shape for the target
     * @type {String}
     */
    this.flo_element_dest_type = null;
    /**
     * Defines if the flow was followed inmediately
     * @type {Boolean}
     */
    this.flo_is_inmediate = null;
    /**
     * Defines the condition to follow the flow
     * @type {String}
     */
    this.flo_condition = null;
    /**
     * X1 Coordinate
     * @type {Number}
     */
    this.flo_x1 = null;
    /**
     * Y1 Coordinate
     * @type {Number}
     */
    this.flo_y1 = null;
    /**
     * X2 Coordinate
     * @type {Number}
     */
    this.flo_x2 = null;
    /**
     * Y2 Coordinate
     * @type {Number}
     */
    this.flo_y2 = null;
    /**
     * Array of segments that conform the connection
     * @type {Array}
     */
    this.flo_state = null;

    this.label = null;

    AdamFlow.prototype.initObject.call(this, options);
};
AdamFlow.prototype = new jCore.Connection();
/**
* Defines the object type
* @type {String}
*/
AdamFlow.prototype.type = "Connection";  //TODO Replace this type by AdamFlow when jCore will be updated

/**
 * Initialize the object with default values
 * @param {Object} options
 */
AdamFlow.prototype.initObject = function (options) {
    var  defaults = {
        flo_type: 'SEQUENCE',
        flo_is_inmediate: true,
        flo_x1: 0,
        flo_y1: 0,
        flo_x2: 0,
        flo_y2: 0,
        name: ''
    };
    $.extend(true, defaults, options);
    this.setFlowType(defaults.flo_type)
        .setFlowUid(defaults.flo_uid)
        .setIsInmediate(defaults.flo_is_inmediate)
        .setOriginPoint(defaults.flo_x1, defaults.flo_y1)
        .setTargetPoint(defaults.flo_x2, defaults.flo_y2);

    this.setFlowName(defaults.name || null);
    this.setFlowOrigin(defaults.flo_element_origin || null, defaults.flo_element_origin_type || null);
    this.setFlowTarget(defaults.flo_element_dest || null, defaults.flo_element_dest_type || null);
    this.setFlowCondition(defaults.flo_condition || null);
    this.setFlowState(defaults.flo_state || null);
};

/**
 * Returns the flow's name
 * @return {String}
 */
AdamFlow.prototype.getName = function () {
    return this.flo_name;
};

AdamFlow.prototype.setName = function (name) {
    //if (typeof name !== 'undefined') {
    if (name) {
        this.flo_name = name;
    }
    return this;
};

/**
 * Returns the flow conditions
 * @return {String}
 */
AdamFlow.prototype.getFlowCondition = function () {
    return this.flo_condition;
};

/**
 * Defines the unique identiier property
 * @param {String} value
 * @return {*}
 */
AdamFlow.prototype.setFlowUid = function (value) {
    this.flo_uid = value;
    return this;
};

/**
 * Defines the connection type
 * @param {String} type
 * @return {*}
 */
AdamFlow.prototype.setFlowType = function (type) {
    this.flo_type = type;
    return this;
};

/** Return Flow Type
 *
 * @returns {String}
 */
AdamFlow.prototype.getFlowType = function () {
    return this.flo_type;
};

/**
 * Sets the inmediately behavior of the connection
 * @param {Boolean} value
 * @return {*}
 */
AdamFlow.prototype.setIsInmediate = function (value) {
    //if (_.isBoolean(value)) {
    if (value instanceof Boolean) {
        this.flo_is_inmediate = value;
    }
    return this;
};

/**
 * Sets the origin point
 * @param {Number} x
 * @param {Number} y
 * @return {*}
 */
AdamFlow.prototype.setOriginPoint = function (x, y) {
    this.flo_x1 = x;
    this.flo_y1 = y;
    return this;
};

/**
 * Sets the target point
 * @param {Number} x
 * @param {Number} y
 * @return {*}
 */
AdamFlow.prototype.setTargetPoint = function (x, y) {
    this.flo_x2 = x;
    this.flo_y2 = y;
    return this;
};

/**
 * Sets the connection label
 * @param {String} name
 * @return {*}
 */
AdamFlow.prototype.setFlowName = function (name) {
    this.flo_name = name;
    return this;
};

/**
 * Set the shape origin using input data
 * @param {String} code
 * @param {String} type
 * @return {*}
 */
AdamFlow.prototype.setFlowOrigin = function (code, type) {
    this.flo_element_origin = code;
    this.flo_element_origin_type = type;
    return this;
};

/**
 * Set the shape target using input data
 * @param {String} code
 * @param {String} type
 * @return {*}
 */
AdamFlow.prototype.setFlowTarget = function (code, type) {
    this.flo_element_dest = code;
    this.flo_element_dest_type = type;
    return this;
};

/**
 * Sets the flow conditions
 * @param value
 * @return {*}
 */
AdamFlow.prototype.setFlowCondition = function (value) {
    this.flo_condition = value;
    return this;
};

/**
 * Sets the array of segments that conform the connection
 * @param {Array} state
 * @return {*}
 */
AdamFlow.prototype.setFlowState = function (state) {
    this.flo_state = state;
    return this;
};

/**
 * Sets the origin data from a Shape
 * @param {AdamShape} shape
 * @return {*}
 */
AdamFlow.prototype.setOriginShape = function (shape) {
    var data;
    if (shape instanceof AdamShape) {
        data = this.getNativeType(shape);
        this.flo_element_origin = data.code;
        this.flo_element_origin_type = data.type;
    }
    return this;
};

/**
 * Sets the target data from a Shape
 * @param {AdamShape} shape
 * @return {*}
 */
AdamFlow.prototype.setTargetShape = function (shape) {
    var data;
    if (shape instanceof AdamShape) {
        data = this.getNativeType(shape);
        this.flo_element_dest = data.code;
        this.flo_element_dest_type = data.type;
    }
    return this;
};

/**
 * Returns the clean object to be sent to the backend
 * @return {Object}
 */
AdamFlow.prototype.getDBObject = function () {
    var typeMap = {
            regular: 'SEQUENCE',
            segmented: 'MESSAGE',
            dotted: 'ASSOCIATION'
        },
        state = this.getPoints();
    return {
        flo_uid : this.flo_uid,
        flo_type : typeMap[this.segmentStyle],
        flo_name : this.flo_name,
        flo_element_origin : this.flo_element_origin,
        flo_element_origin_type : this.flo_element_origin_type,
        flo_element_dest : this.flo_element_dest,
        flo_element_dest_type : this.flo_element_dest_type,
        flo_is_inmediate : this.flo_is_inmediate,
        flo_condition : this.flo_condition,
        flo_state : state
    };
};

/**
 * Converts the type to be sent to backend
 * @param {AdamShape} shape
 * @return {Object}
 */
AdamFlow.prototype.getNativeType = function (shape) {
    var type = shape.getType(),
        code;
    switch (shape.getType()) {
    case 'AdamActivity':
        type = "bpmnActivity";
        code = shape.act_uid;
        break;
    case 'AdamGateway':
        type = "bpmnGateway";
        code = shape.gat_uid;
        break;
    case 'AdamEvent':
        type = 'bpmnEvent';
        code = shape.evn_uid;
        break;
    case 'AdamArtifact':
        type = "bpmnArtifact";
        code = shape.art_uid;
        break;
    }
    return {
        "type" : type,
        "code" : code
    };
};

AdamFlow.prototype.showMoveHandlers = function () {
    jCore.Connection.prototype.showMoveHandlers.call(this);
    this.canvas.updatedElement = [{
        relatedObject: this
    }];
    $(this.html).trigger('selectelement');

    return this;
};

/**
 * Get Segment Width
 * @returns {Number}
 */
AdamFlow.prototype.getSegmentHeight = function (index) {
    return Math.abs(this.lineSegments.get(index).endPoint.y
        - this.lineSegments.get(index).startPoint.y);
};
/**
 * Get Segment Width
 * @returns {Number}
 */
AdamFlow.prototype.getSegmentWidth = function (index) {
    return Math.abs(this.lineSegments.get(index).endPoint.x
        - this.lineSegments.get(index).startPoint.x);
};
/**
 * Get Label Coordinates
 * @returns {Point}
 */

AdamFlow.prototype.getLabelCoordinates = function () {
    var  x, y, index = 0, diffX, diffY, i, max;
    max = (this.getSegmentWidth(0) > this.getSegmentHeight(0)) ?
            this.getSegmentWidth(0) : this.getSegmentHeight(0);

    for (i = 1; i < this.lineSegments.getSize(); i += 1) {
        diffX = this.getSegmentWidth(i);
        diffY = this.getSegmentHeight(i);
        if (diffX > max + 1) {
            max = diffX;
            index = i;
        } else if (diffY > max + 1) {
            max = diffY;
            index = i;
        }
    }
    diffX = (this.lineSegments.get(index).endPoint.x
        - this.lineSegments.get(index).startPoint.x) / 2;
    diffY = (this.lineSegments.get(index).endPoint.y
        - this.lineSegments.get(index).startPoint.y) / 2;
    x = this.lineSegments.get(index).startPoint.x + diffX;
    y = this.lineSegments.get(index).startPoint.y + diffY;

    return new jCore.Point(x, y);
};

/**
 * Connects two Adam Figures
 * @returns {Connection}
 */
AdamFlow.prototype.connect = function (options) {
    var labelPoint;
    jCore.Connection.prototype.connect.call(this, options);
//    labelPoint = this.getLabelCoordinates();
//
//    this.label = new jCore.Label({
//        message: this.getName(),
//        canvas: this.canvas,
//        parent: this,
//        position: {
//            location: "center",
//            diffX: labelPoint.getX() / this.canvas.zoomFactor,
//            diffY: labelPoint.getY() / this.canvas.zoomFactor
//
//        }
//    });
//    this.html.appendChild(this.label.getHTML());
    return this;
};

AdamFlow.prototype.changeFlowType = function (type) {
    var segmentStyle, destDecorator,
        typeMap = {
            'default': {
                srcPrefix: 'adam-decorator_default',
                destPrefix: 'adam-decorator'
            },
            'conditional': {
                srcPrefix: 'adam-decorator_conditional',
                destPrefix: 'adam-decorator'
            },
            'sequence': {
                srcPrefix: 'adam-decorator',
                destPrefix: 'adam-decorator'
            }
        }, srcDecorator;

    if (type === 'association') {
        segmentStyle = "dotted";
        destDecorator = "con-none";
    } else {
        segmentStyle = "regular";
    }
    this.setSegmentStyle(segmentStyle);
    this.originalSegmentStyle = segmentStyle;

    if (type === 'association') {
        if (srcDecorator &&  this.srcDecorator) {
            this.srcDecorator
                .setDecoratorPrefix(srcDecorator);
        } else {
            this.srcDecorator
                .setDecoratorPrefix("adam-decorator");

        }
        this.srcDecorator.paint();
    } else {
        this.srcDecorator.setDecoratorPrefix(typeMap[type].srcPrefix)
            .setDecoratorType("source")
            .paint();

        this.destDecorator.setDecoratorPrefix(typeMap[type].destPrefix)
            .setDecoratorType("target")
            .paint();
        this.disconnect()
            .connect()
            .setSegmentMoveHandlers()
            .checkAndCreateIntersectionsWithAll();
        return this;
    }


    if (destDecorator && this.srcDecorator) {
        this.destDecorator
            .setDecoratorPrefix(destDecorator);
    } else {
        this.destDecorator
            .setDecoratorPrefix("adam-decorator");
    }
    this.srcDecorator.paint();
    this.disconnect();
    this.connect();
    return this;
};

AdamFlow.prototype.saveAndDestroy = function () {
    jCore.Connection.prototype.saveAndDestroy.call(this);
    if (this.getFlowType() === 'DEFAULT') {
        this.getSrcPort().getParent().updateDefaultFlow("");
    }
};

AdamFlow.prototype.createHTML = function () {
    var that;
    if (this.html === null) {
        that = this;
        jCore.Connection.prototype.createHTML.call(this);
        $(this.html).addClass('adam-flow').on('click', '.line', function () {
            that.fixZIndex();
            $(that.destDecorator.getHTML()).trigger('click');
        });
    }
    return this.html;
};