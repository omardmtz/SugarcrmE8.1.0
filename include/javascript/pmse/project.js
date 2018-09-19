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
/*global SUGAR_URL, $, AdamCanvas, RestClient, window, setInterval, PropertiesGrid,
 AdamEvent, AdamActivity, AdamGateway, AdamArtifact, AdamFlow, setSelectedNode,
 CommandSingleProperty, CommandAnnotationResize, CommandConnectionCondition, MessagePanel,
 jCore, translate
*/
/**
 * @class AdamProject
 * This class represents a Sugar Project
 *
 * @constructor
 * Create a new version of the class
 */
var callbackCS;
var AdamProject = function (settings) {
    /**
     * Unique Identifier for the project
     * @type {String}
     */
    this.uid = null;
    /**
     * Project Name
     * @type {String}
     */
    this.name = null;
    /**
     * Project Description
     * @type {String}
     */
    this.description = null;
    /**
     * Canvas associated to the project
     * @type {AdamCanvas}
     */
    this.canvas = null;
    /**
     * REST End Point associated to this project
     * @type {String}
     */
    this.url = '/rest/v10/Project/';
    /**
     * RestClient library object
     * @type {Object}
     */
    this.restClient = null;
    /**
     * Stores if the project was loaded correctly
     * @type {Boolean}
     */
    this.loaded = false;
    /**
     * Stores if the project has elements without save
     * @type {Boolean}
     */
    this.isDirty = false;
    /**
     * Stores id the  project is waiting for a response
     * @type {Boolean}
     */
    this.isWaitingResponse = false;
    /**
     * Stores the interval of time for auto save feature
     * @type {Number}
     */
    this.saveInterval = 30000;
    this.showWarning = false;
    /**
     * Object Structure to save elements without save
     * @type {Array}
     */
    this.dirtyElements = [
        {
            activities: {},
            gateways: {},
            events: {},
            artifacts: {},
            flows: {}

        },
        {
            activities: {},
            gateways: {},
            events: {},
            artifacts: {},
            flows: {}
        }
    ];
    /**
     * Grid that contains the current selected element's properties
     * @type {Object} This object is a used from the jquery propi plugin
     */
    this.propertiesGrid = null;
    /**
     * Object that contains project's metadata.
     * @type {Object}
     */
    this._metadata = {};

    this.process_definition = {};
    AdamProject.prototype.preinit.call(this, settings);
};
/**
 * Object type
 * @type {String}
 */
AdamProject.prototype.type = "AdamProject";
/**
 * Initializes the AdamProject.
 */
AdamProject.prototype.preinit = function (settings) {
    var defaults = {
        metadata: []
    };

    jQuery.extend(true, defaults, settings);

    this.setMetadata(defaults.metadata);
};
/**
 * Returns the project uid
 * @return {String}
 */
AdamProject.prototype.getUid = function () {
    return this.uid;
};
/**
 * Sets the project uid
 * @param value
 */
AdamProject.prototype.setUid = function (value) {
    this.uid = value;
};
/**
 * Sets the project name
 * @param value
 */
AdamProject.prototype.setName = function (value) {
    var $title, $title_box;
    value = Handlebars.Utils.escapeExpression(value);
    this.name = value;
    $title = $('#ProjectTitle');
    $title_box = $('#txt-title');
    $title.html(value);
    $title_box.html(value);
    //console.log("name", value);
};
/**
 * Sets the project description
 * @param  {String} description [description]
 */
AdamProject.prototype.setDescription = function (description) {
    this.description = description;
    return this;
};
/**
 * Sets the canvas related to the project
 * @param {AdamCanvas} value
 * @return {*}
 */
AdamProject.prototype.setCanvas = function (value) {
    if (value instanceof AdamCanvas) {
        this.canvas = value;
        this.canvas.setProject(this);
        $(this.canvas.html).on('selectelement', this.onSelectElementHandler(this.canvas));
    } else {
        this.canvas = null;
    }
    return this;
};
/**
 * Sets the RestClient object associated to this project
 * @param {Object} rc
 * @return {*}
 */
AdamProject.prototype.setRestClient = function (rc) {
    if (rc instanceof RestClient) {
        this.restClient = rc;
    }
    return this;
};
/**
 * Sets the REST url associated to this project
 * @param {String} url
 * @return {*}
 */
AdamProject.prototype.setRestURL = function (url) {
    this.url = url;
    return this;
};
/**
 * Sets the time interval used to save automatically
 * @param {Number} interval Expressed in miliseconds
 * @return {*}
 */
AdamProject.prototype.setSaveInterval = function (interval) {
    this.saveInterval = interval;
    return this;
};
/**
 * Loads the project through a REST call
 * @param {String} id
 * @return {Boolean}
 */
AdamProject.prototype.load = function (id, callback) {
    var status = false,
        self = this,
        url,
        attributes = {};
    if (typeof id !== 'undefined') {
        this.uid = id;
    }
    url = App.api.buildURL("pmse_Project/project/" + this.uid, null, null);
    attributes = {};

    App.api.call('read', url, attributes, {
        success: function (data) {
//            console.log(data);
            self.loadProject(data);
            status = true;
//            console.log(success);
            if (callback && callback.success) {
                callback.success.call(this, data);
            }
            if (canvas){
                canvas.bpmnValidation();
                //jQuery(".pane.ui-layout-center").append(countErrors);
            }
        },
        error: function (err) {
            //TODO Process HERE error at loading project
        }
        /*if (canvas) {

            console.log(1);
        }*/
    });
    return status;
};
/**
 * Initialize the project cycle: saving and updates
 */
AdamProject.prototype.init = function () {
    var self;
    self = this;
    if (this.loaded) {
        window.onbeforeunload = function () {
            if (self.isDirty && !self.showWarning) {
                return true;
            }
        };
        setInterval(function () {
            self.save();
        }, this.saveInterval);
        this.propertiesGrid = new PropertiesGrid('#properties-grid');
        this.canvas.commandStack.setHandler(AdamProject.prototype.updateUndoRedo);
    }
};

/**
 * Save project if is dirty and is not waiting response
 */
AdamProject.prototype.save = function (callback) {
    var self = this,
        url,
        attributes = {};
    callback = callback || {};
    if (this.isDirty && !this.isWaitingResponse) {
        this.isWaitingResponse = true;
        url = App.api.buildURL("pmse_Project/project/" + this.uid, null, null);
        //console.log(url);
        attributes = {
            data: this.getDirtyObject(),
            id: this.uid,
            operation: "update",
            wrapper: "Project"
        };
        App.api.call('update', url, attributes, {
            success: function (data) {
                self.isWaitingResponse = false;
                if (data.success) {
                    self.updateDirtyProject();
                    if (typeof callback.success === 'function') {
                        callback.success();
                    }
                } else {
                    self.mergeDirtyElements();
                    //TODO Process HERE the failure at saving time
                }
            },
            error: function (err) {
                self.isWaitingResponse = false;
                self.mergeDirtyElements();
                self.isDirty = false;
                //TODO Process HERE the failure at saving time
                if (typeof callback.error === 'function') {
                    callback.error();
                }
            }
        });
    } else {
        if (typeof callback.success === 'function') {
            callback.success();
        }
    }
};

/**
 * Loads the project from a JSON response
 * @param {Object} response
 * @private
 */
AdamProject.prototype.loadProject = function (response) {
    var diagram, i, result;
    if (response.project) {
        diagram = response.project.diagram[0];
        this.setName(response.project.prj_name);
        this.setDescription(response.project.prj_description);

        this.process_definition.pro_module = response.project.process_definition.pro_module;
        this.process_definition.pro_status = response.project.process_definition.pro_status;
        this.process_definition.pro_locked_variables = response.project.process_definition.pro_locked_variables;
        this.process_definition.pro_terminate_variables = response.project.process_definition.pro_terminate_variables;
        this.script_tasks = response.project.script_tasks;

        this.canvas.setDiaUid(diagram.dia_uid);

        for (i = 0; i < diagram.activities.length; i += 1) {
            this.loadShape(diagram.activities[i], 'AdamActivity');
        }

        for (i = 0; i < diagram.events.length; i += 1) {
            this.loadShape(diagram.events[i], 'AdamEvent');
        }

        for (i = 0; i < diagram.gateways.length; i += 1) {
            this.loadShape(diagram.gateways[i], 'AdamGateway');
        }

        for (i = 0; i < diagram.artifacts.length; i += 1) {
            this.loadShape(diagram.artifacts[i], 'AdamArtifact');
        }

        for (i = 0; i < diagram.flows.length; i += 1) {
            this.loadFlow(diagram.flows[i]);
        }

        this.loaded = true;
    } else {
        this.loaded = false;
    }
};
/**
 * Add Element to the list of unsaved elements
 * @param {Object} element
 * @private
 */
AdamProject.prototype.addElement = function (element) {
    var obj,
        pk_name,
        list,
        i,
        currentElement,
        elements = element.relatedElements.length > 0 ? element.relatedElements : [element];

    for (i = 0; i < elements.length; i += 1) {
        currentElement = elements[i];
        list = this.getUpdateList(currentElement.type);

        // Handle remove cases here
        if (list[currentElement.id] && list[currentElement.id].action === 'REMOVE') {
            delete list[currentElement.id];
            continue;
        }

        // Handle fetching of the proper object
        if (currentElement instanceof jCore.JCoreObject) {
            obj = currentElement.getDBObject();
        } else {
            pk_name = this.formatProperty(currentElement.type, 'uid');
            currentElement.relatedObject[pk_name] = currentElement.id;
            obj = currentElement.relatedObject.getDBObject();
        }
        obj.action = 'CREATE';
        list[currentElement.id] = obj;
    }
    this.isDirty = true;
    this.updateToolbar();
};
/**
 * Removes element(s) for the unsaved list
 * @param {Array} updateElement
 * @private
 */
AdamProject.prototype.removeElement = function (updateElement) {
    var object,
        dirtyEmptyCounter,
        element,
        i,
        pk_name,
        list,
        emptyObject = {};
    for (i = 0; i < updateElement.length; i += 1) {
        element = updateElement[i];

        pk_name = this.formatProperty(element.type, 'uid');
        list = this.getUpdateList(element.type);
        if (list[element.id]) {
            if (list[element.id].action === 'CREATE') {
                delete list[element.id];
            } else {
                list[element.id].action = 'REMOVE';
            }
        } else {
            object = {action: 'REMOVE'};
            object[pk_name] = element.id;
            list[element.id] = object;
        }
    }
    this.isDirty = true;
    if (!this.isWaitingResponse) {
        dirtyEmptyCounter = true;
        dirtyEmptyCounter = dirtyEmptyCounter && (this.dirtyElements[0].activities === emptyObject);
        dirtyEmptyCounter = dirtyEmptyCounter && (this.dirtyElements[0].gateways === emptyObject);
        dirtyEmptyCounter = dirtyEmptyCounter && (this.dirtyElements[0].events === emptyObject);
        dirtyEmptyCounter = dirtyEmptyCounter && (this.dirtyElements[0].artifacts === emptyObject);
        dirtyEmptyCounter = dirtyEmptyCounter && (this.dirtyElements[0].flows === emptyObject);
        if (dirtyEmptyCounter) {
            this.isDirty = false;
        }
    }
    this.updateToolbar();
};
/**
 * Updates the information of the unsaved elements
 * @param {Array} updateElement
 * @private
 */
AdamProject.prototype.updateElement = function (updateElement) {
    var element,
        i,
        shape,
        object,
        list;
    for (i = 0; i < updateElement.length; i += 1) {
        element = updateElement[i];
        shape = element.relatedObject;

        object = this.formatObject(element);
        list = this.getUpdateList(element.type);
        if (list[element.id]) {
            $.extend(true, list[element.id], object);
            if (element.type === 'Connection') {
                list[element.id].flo_state = object.flo_state;
            }
        } else {
            object.action = "UPDATE";
            list[element.id] = object;
        }
    }
    this.isDirty = true;
    this.updateToolbar();
};
/**
 * Gets the dirty object to send through REST
 * @return {Object}
 * @private
 */
AdamProject.prototype.getDirtyObject = function () {
    var dirtyObj;
    dirtyObj = this.dirtyElements[0];
    dirtyObj.prj_uid = this.getUid();
    return dirtyObj;
};
/**
 * Merge the actual and temporal dirty element arrays
 * @private
 */
AdamProject.prototype.mergeDirtyElements = function () {
    //TODO Merge Dirty Elements Array and Clean TMP Dirty Object
    this.updateToolbar();
};
/**
 * Update the actual and temporal dirty element arrays
 * @private
 */
AdamProject.prototype.updateDirtyProject = function () {
    this.isDirty = false;
    //this.dirtyElements[0] = {};
    this.dirtyElements[0] = this.dirtyElements[1];
    this.dirtyElements[1] = {
        activities: {},
        events: {},
        gateways: {},
        flows: {},
        artifacts: {}
    };
    this.updateToolbar();
};
/**
 * Gets the changed fields from an object
 * @param {Object} element
 * @return {Object}
 */
AdamProject.prototype.formatObject = function (element) {
    var i,
        field,
        formattedElement = {},
        property;
    formattedElement[this.formatProperty(element.type, 'uid')] = element.id;

    if (element.adam) {
        for (i = 0; i < element.fields.length;  i += 1) {
            field = element.fields[i];
            formattedElement[field.field] = field.newVal;
        }
    } else {
        for (i = 0; i < element.fields.length;  i += 1) {
            field = element.fields[i];
            property = this.formatProperty(element.type, field.field);
            if (property === "element_uid") {
                field.newVal = field.newVal.id;
            } //else if (property === "bou_x") {
//                field.newVal = element.relatedObject.absoluteX;
//            } else if (property === "bou_y") {
//                field.newVal = element.relatedObject.absoluteY;
//            }
            formattedElement[property] = field.newVal;
        }
    }

    return formattedElement;
};
/**
 * Returns the list where the element will be added/updated
 * @param {String} type
 * @return {*}
 * @private
 */
AdamProject.prototype.getUpdateList = function (type) {
    var listName = {
            "AdamActivity" : "activities",
            "AdamGateway" : "gateways",
            "AdamEvent" : "events",
            "AdamFlow" : "flows",
            "AdamArtifact" : "artifacts",
            "Connection" : "flows"
        },
        dirtyArray;
    dirtyArray = (this.isWaitingResponse) ? 1 : 0;
    return this.dirtyElements[dirtyArray][listName[type]];
};
/**
 * Returns the field name formated
 * @param {String} type Object Type
 * @param {String }property  Property name
 * @return {String}
 */
AdamProject.prototype.formatProperty = function (type, property) {
    var prefixes = {
            "AdamActivity" : "act",
            "AdamGateway" : "gat",
            "AdamEvent" : "evn",
            "AdamArtifact" : "art"
        },
        map = {
            // map for shapes
            x: "bou_x",
            y: "bou_y",
            width: "bou_width",
            height: "bou_height"
        },
        out;

    if (type === "AdamFlow" || type === 'Connection') {
        out = "flo_" + property;
    } else if (map[property]) {
        out = map[property];
    } else {
        out = prefixes[type] + '_' + property;
    }
    return out;
};

/**
 * Loads the AdamShape into the diagram
 * @param {Object} shape
 * @param {String} type
 */
AdamProject.prototype.loadShape = function (shape, type) {
    var customShape, shapeType, behavior, message, uid, marker, direction, addShape = true, activity;
    switch (type) {
    case 'AdamEvent':
        uid = shape.evn_uid;
        shapeType = shape.evn_type.toLowerCase();
        if (shapeType === 'boundary') {
            shapeType = 'intermediate';
        }
        behavior = shape.evn_behavior.toLowerCase();
        //behavior = shape.evn_behavior;
        message = shape.evn_message;
        marker = message || shape.evn_marker;
        marker = marker.toLowerCase();
        customShape = new AdamEvent({
            id: uid,
            canvas : this.canvas,
            width : 33,
            height : 33,
            style: {
                cssClasses: [""]
            },
            labels: [{
                message: shape.evn_name || '',
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
                        'adam-shape-50-event-' + shapeType,
                        'adam-shape-75-event-' + shapeType,
                        'adam-shape-100-event-' + shapeType,
                        'adam-shape-125-event-' + shapeType,
                        'adam-shape-150-event-' + shapeType
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
                        'adam-marker-50-' + shapeType  + '-' + behavior + '-' + marker,
                        'adam-marker-75-' + shapeType  + '-' + behavior + '-' + marker,
                        'adam-marker-100-' + shapeType  + '-' + behavior + '-' + marker,
                        'adam-marker-125-' + shapeType  + '-' + behavior + '-' + marker,
                        'adam-marker-150-' + shapeType  + '-' + behavior + '-' + marker
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
            drop : {type: 'connection'},
            evn_name: shape.evn_name,
            evn_type: shape.evn_type.toLowerCase(),
            evn_marker: shape.evn_marker,
            evn_behavior: behavior,
            evn_message: message,
            evn_uid: uid
        });
        if (shape.evn_type === 'BOUNDARY') {
            addShape = false;
            activity = this.canvas.getCustomShapes().find('id', shape.evn_attached_to);
            if (activity) {
                activity.activityContainerBehavior.addToContainer(
                    activity,
                    customShape,
                    parseInt(shape.bou_x, 10),
                    parseInt(shape.bou_y, 10),
                    true
                );
                customShape.attachListeners();
            }
        }
        break;
    case 'AdamActivity':
        uid = shape.act_uid;
        marker = shape.act_task_type;
        shapeType = shape.act_type;
        if (shape.act_task_type === 'USERTASK') {
            customShape = new AdamActivity({
                id: uid,
                act_uid: uid,
                canvas : this.canvas,
                width: parseInt(shape.bou_width, 10),
                height: parseInt(shape.bou_height, 10),
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
                        message : shape.act_name || "",
                        //x : 10,
                        //y: 10,
                        width : 0,
                        height : 0,
                        orientation : 'horizontal',
                        position : {
                            location: 'center',
                            diffX : 0,
                            diffY : 0

                        },
                        updateParent : true,
                        updateParentOnLoad: false
                    }
                ],
                markers: [
                    {
                        markerType: marker,
                        x: 5,
                        y: 5,
                        markerZoomClasses: [
                            "adam-marker-50-" + marker.toLowerCase(),
                            "adam-marker-75-" + marker.toLowerCase(),
                            "adam-marker-100-" + marker.toLowerCase(),
                            "adam-marker-125-" + marker.toLowerCase(),
                            "adam-marker-150-" + marker.toLowerCase()
                        ]
                    }
                ],
                act_type: shapeType,
                act_task_type: marker,
                act_name: shape.act_name,
                act_script: shape.act_script,
                act_script_type: shape.act_script_type,
                act_default_flow: shape.act_default_flow ? shape.gat_default_flow : 0,
                minHeight: 50,
                minWidth: 100,
                maxHeight: 300,
                maxWidth: 400
            });
        } else {
            customShape = new AdamActivity({
                id: uid,
                act_uid: uid,
                canvas : this.canvas,
                width: parseInt(shape.bou_width, 10),
                height: parseInt(shape.bou_height, 10),
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
                            'adam-shape-50-activity-scripttask-' + shape.act_script_type.toLowerCase(),
                            'adam-shape-75-activity-scripttask-' + shape.act_script_type.toLowerCase(),
                            'adam-shape-100-activity-scripttask-' + shape.act_script_type.toLowerCase(),
                            'adam-shape-125-activity-scripttask-' + shape.act_script_type.toLowerCase(),
                            'adam-shape-150-activity-scripttask-' + shape.act_script_type.toLowerCase()
                        ]
                    }
                ],
                connectAtMiddlePoints: true,
                drag: 'customshapedrag',
                labels : [
                    {
                        message : shape.act_name || "",
                        position : {
                            location: 'bottom',
                            diffX : 0,
                            diffY : 4

                        },
                        updateParent : false
                    }
                ],
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
                drop : {type: 'connection'},
                // drop : {
                //     type: 'connectioncontainer',
                //     selectors : ["#AdamEventBoundary", '.adam_boundary_event']
                // },
                act_type: shapeType,
                act_task_type: marker,
                act_name: shape.act_name,
                act_script: shape.act_script,
                act_script_type: shape.act_script_type
            });
        }
        break;
    case 'AdamGateway':
        uid = shape.gat_uid;
        shapeType = shape.gat_type.toLowerCase();
        direction = shape.gat_direction.toLowerCase();

        customShape = new AdamGateway({
            id: uid,
            gat_uid: uid,
            canvas : this.canvas,
            width : 45,
            height : 45,
            gat_type: shapeType,
            gat_direction: direction,
            gat_name: shape.gat_name,
            style: {
                cssClasses: [""]
            },
            labels : [
                {
                    message : shape.gat_name || "",
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
                        'adam-shape-50-gateway-' + shapeType,
                        'adam-shape-75-gateway-' + shapeType,
                        'adam-shape-100-gateway-' + shapeType,
                        'adam-shape-125-gateway-' + shapeType,
                        'adam-shape-150-gateway-' + shapeType
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
            drop : {type: 'connection'},
            gat_default_flow: (shape.gat_default_flow) ? shape.gat_default_flow : 0
        });
        break;
    case 'AdamArtifact':
        uid = shape.art_uid;
        shapeType = shape.art_type;
        customShape = new AdamArtifact({
            id: uid,
            art_uid: uid,
            canvas : this.canvas,
            width: parseInt(shape.bou_width, 10),
            height: parseInt(shape.bou_height, 10),
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
                    message : shape.at_name || "",
                    width : 0,
                    height : 0,
                    //orientation:'vertical',
                    position: {
                        location : 'center-right',
                        diffX : 0,
                        diffY : 0
                    },
                    updateParent : true
                }
            ],
            drop : {type: 'connection'},
            art_type: shapeType,
            art_name: shape.art_name
        });
        break;
    }
    if (addShape) {
        this.canvas.addElement(customShape, parseInt(shape.bou_x, 10), parseInt(shape.bou_y, 10), true);
        customShape.attachListeners();
        this.canvas.updatedElement = customShape;
    }
};


/**
 * Loads the connection into the diagram
 * @param {Object} conn
 */
AdamProject.prototype.loadFlow = function (conn) {
    var sourceObj,
        targetObj,
        startPoint,
        endPoint,
        sourcePort,
        targetPort,
        connection,
        segmentMap = {
            'SEQUENCE' : 'regular',
            'MESSAGE' : 'segmented',
            'DATAASSOCIATION' : 'dotted',
            'ASSOCIATION' : 'dotted',
            'DEFAULT' : 'regular',
            'CONDITIONAL' : 'regular'
        },
        srcDecorator = {
            'SEQUENCE' : 'adam-decorator',
            'MESSAGE' : 'adam-decorator_message',
            'DATAASSOCIATION' : 'adam-decorator',
            'ASSOCIATION' : 'adam-decorator_',
            'DEFAULT' : 'adam-decorator_default',
            'CONDITIONAL' : 'adam-decorator_conditional'
        },
        destDecorator = {
            'SEQUENCE' : 'adam-decorator',
            'MESSAGE' : 'adam-decorator_message',
            'DATAASSOCIATION' : 'adam-decorator_association',
            'ASSOCIATION' : 'adam-decorator_association',
            'DEFAULT' : 'adam-decorator',
            'CONDITIONAL' : 'adam-decorator'
        };

    sourceObj = this.getElementByUid(conn.flo_element_origin);
    targetObj = this.getElementByUid(conn.flo_element_dest);

    startPoint = new jCore.Point(conn.flo_x1, conn.flo_y1);
    endPoint = new jCore.Point(conn.flo_x2, conn.flo_y2);

    sourcePort = new jCore.Port({
        width: 10,
        height: 10
    });

    targetPort = new jCore.Port({
        width: 10,
        height: 10
    });

    sourceObj.addPort(sourcePort, startPoint.x - sourceObj.absoluteX, startPoint.y - sourceObj.absoluteY);
    targetObj.addPort(targetPort, endPoint.x - targetObj.absoluteX, endPoint.y - targetObj.absoluteY, false, sourcePort);
    connection = new AdamFlow({
        id : conn.flo_uid,
        srcPort : sourcePort,
        destPort : targetPort,
        canvas : this.canvas,
        segmentStyle : segmentMap[conn.flo_type],
        flo_type : conn.flo_type,
        name : conn.flo_name,
        flo_condition : conn.flo_condition,
        flo_state : conn.flo_state,
        flo_uid: conn.flo_uid
    });

    connection.setSrcDecorator(new jCore.ConnectionDecorator({
        decoratorPrefix : srcDecorator[conn.flo_type],
        decoratorType : "source",
        style : {
            cssClasses: []
        },
        width : 11,
        height : 11,
        canvas : this.canvas,
        parent : connection
    }));

    connection.setDestDecorator(new jCore.ConnectionDecorator({
        decoratorPrefix : destDecorator[conn.flo_type],
        decoratorType : "target",
        style : {
            cssClasses : []
        },
        width : 11,
        height : 11,
        canvas : this.canvas,
        parent : connection
    }));

    //connection.connect();
    connection.setSegmentMoveHandlers();

    //add the connection to the canvas, that means insert its html to
    // the DOM and adding it to the connections array

    this.canvas.addConnection(connection);

    // Filling AdamFlow fields
    connection.setTargetShape(targetPort.parent);
    connection.setOriginShape(sourcePort.parent);
    connection.savePoints();

    // now that the connection was drawn try to create the intersections
    connection.checkAndCreateIntersectionsWithAll();

    //attaching port listeners
    sourcePort.attachListeners(sourcePort);
    targetPort.attachListeners(targetPort);
};


/**
 * Returns the shape into the diagram
 * @param {String} uid
 * @return {Object|undefined}
 */

AdamProject.prototype.getElementByUid = function (uid) {
    var element, shapes, i, activity;

    element = this.canvas.getCustomShapes().find('id', uid);
    if (!element) {
        shapes = this.canvas.getCustomShapes();
        for (i = 0; i < shapes.getSize(); i += 1) {
            if (shapes.get(i).getType() === 'AdamActivity') {
                activity = shapes.get(i);
                element = activity.getChildren().find('id', uid);
                if (element) {
                    break;
                }
            }
        }
    }
    return element;
};



AdamProject.prototype.updateToolbar = function () {
    var $title, $title_box, $savebutton, $undobutton, $redobutton, value, undo, redo, undoClass, redoClass;
    $title = $('#ProjectTitle');
    $title_box = $('#txt-title');
    $savebutton = $('#ButtonSave > i');
    $savebutton.removeClass();
    if (this.isDirty) {
        //value = "*" + this.name;
        $savebutton.addClass('fa fa-save save-on');
    } else {
        //value = this.name;
        $savebutton.addClass('fa fa-save save-off');
    }
    $title.html(this.name);
    $title_box.html(this.name);
};

AdamProject.prototype.updateUndoRedo = function () {
    var undo, redo, undoClass, redoClass, $undobutton, $redobutton;

    $undobutton = $('#ButtonUndo > i');
    $redobutton = $('#ButtonRedo > i');

    undo = (this.canvas.commandStack.getUndoSize() > 0);
    redo = (this.canvas.commandStack.getRedoSize() > 0);

    $undobutton.removeClass();
    undoClass = (undo) ? 'fa fa-undo undo-on' : 'fa fa-undo undo-off';
    $undobutton.addClass(undoClass);

    $redobutton.removeClass();
    redoClass = (redo) ? 'fa fa-undo fa-flip-horizontal undo-on' : 'fa fa-undo fa-flip-horizontal undo-off';
    $redobutton.addClass(redoClass);

    //this.canvas.commandStack.debug(true);
};

AdamProject.prototype.onCanvasClick = function () {
//    this.propertiesGrid.forceFocusOut();
};

AdamProject.prototype.updatePropertiesGrid = function (element) {
    if (!element) {
        this.propertiesGrid.clear();
        return;
    }
    var aux,
        readOnly,
        options,
        setup = {
            id: element.getID(),
            width: "100%",
            rows:  [
                {
                    name: "uid",
                    label: translate("LBL_PMSE_PROPERTY_GRID_UID"),
                    value: element.getID(),
                    type: "text",
                    readOnly: true
                },
                {
                    name: "name",
                    label: translate("LBL_PMSE_PROPERTY_GRID_NAME"),
                    value: element.getName(),
                    type: "text",
                    readOnly: element.type === 'Connection' ? false: true
                }
            ],
            onRowDeselected: function () {
                jCore.getActiveCanvas().currentLabel = false;
            },
            onRowsInitialized: function () {
                if (jCore.getActiveCanvas()) {
                    jCore.getActiveCanvas().currentLabel = false;
                }
            },
            onChangeDiscarded: function () {
                jCore.getActiveCanvas().currentLabel = false;
            },
            onViewMode: function () {
                jCore.getActiveCanvas().currentLabel = false;
            },
            onEditMode: function (data) {
                if (element.type !== 'Connection') {
                    jCore.getActiveCanvas().currentLabel = jCore.getActiveCanvas().customShapes.find('id', data.id).label;
                } else {
                    jCore.getActiveCanvas().currentLabel = jCore.getActiveCanvas().connections.find('id', data.id).label;
                }
            }
        };
    if (!((element.type === 'AdamEvent' && element.evn_type === 'BOUNDARY') || element.type === 'Connection')) {
        setup.rows.push({
            name: "x",
            label: translate("LBL_PMSE_PROPERTY_GRID_X"),
            value: element.getX(),
            type: "text",
            validate: "integer",
            readOnly: true
        });
        setup.rows.push({
            name: "y",
            label: translate("LBL_PMSE_PROPERTY_GRID_Y"),
            value: element.getY(),
            type: "text",
            validate: "integer",
            readOnly: true
        });
    }
    if (element.type !== 'AdamEvent' && element.type !== 'AdamGateway' && element.type !== 'Connection') {

        readOnly = (element.act_task_type ==="SCRIPTTASK") ? true:false;
        setup.rows.push({
            name: 'width',
            label: translate('LBL_PMSE_PROPERTY_GRID_WIDTH'),
            type: 'text',
            validate: 'integer',
            value: element.getWidth(),
            readOnly: true
        });
        setup.rows.push({
            name: 'height',
            label: translate('LBL_PMSE_PROPERTY_GRID_HEIGHT'),
            type: 'text',
            validate: 'integer',
            value: element.getHeight(),
            readOnly: true
        });
    }
    switch (element.type) {
    case 'AdamActivity':
        setup.rows.push({
            name: 'act_cancel_remaining_instances',
            label: translate('LBL_PMSE_PROPERTY_GRID_CANCEL_REMAINING_INSTANCES'),
            type: 'yesNo',
            value: element.getCancelRemainingInstances(),
            readOnly: true
        });
        setup.rows.push({
            name: 'act_is_for_compensation',
            label: translate('LBL_PMSE_PROPERTY_GRID_COMPENSATION'),
            type: 'yesNo',
            yesNoValueMode: 'int',
            value: element.getIsForCompensation(),
            readOnly: true
        });
        setup.rows.push({
            name: 'act_completion_quantity',
            label: translate('LBL_PMSE_PROPERTY_GRID_COMPLETION_QUANTITY'),
            value: element.getCompletionQuantity(),
            type: 'text',
            validate: 'integer',
            readOnly: true
        });
        setup.rows.push({
            name: 'act_is_global',
            label: translate('LBL_PMSE_PROPERTY_GRID_GLOBAL'),
            type: 'yesNo',
            value: element.getIsGlobal(),
            readOnly: true
        });
        setup.rows.push({
            name: 'act_referer',
            label: translate('LBL_PMSE_PROPERTY_GRID_REFERER'),
            value: element.act_referer,
            type: 'text',
            readOnly: true
        });
        setup.rows.push({
            name: 'act_start_quantity',
            label: translate('LBL_PMSE_PROPERTY_GRID_START_QUANTITY'),
            value: element.getStartQuantity(),
            type: 'text',
            validate: 'integer'
        });
        setup.rows.push({
            name: "type",
            label: translate("LBL_PMSE_PROPERTY_GRID_TYPE"),
            value: element.getActivityType(),
            type: "text",
            readOnly: true
        });
        setup.rows.push({
            name: 'act_task_type',
            label: translate('LBL_PMSE_PROPERTY_GRID_TASK_TYPE'),
            options: [
                {
                    label: translate("LBL_PMSE_PROPERTY_GRID_USER_TASK"),
                    value: "USERTASK",
                    selected: element.act_task_type
                },
                {
                    label: translate("LBL_PMSE_PROPERTY_GRID_SCRIPT_TASK"),
                    value: "SCRIPTTASK",
                    selected: element.act_task_type
                }
            ],
            type: 'select',
            readOnly: true
        });

        if (element.act_task_type !== 'SCRIPTTASK') {
                /*setup.rows.push({
                    name: 'act_implementation',
                    label: 'Implementation',
                    type: 'text',
                    value: element.getImplementation()
                });
                setup.rows.push({
                    name: 'act_instantiate',
                    label: 'Instantiate',
                    type: 'yesNo',
                    value: element.getInstantiate()
                });*/
        } else {
            setup.rows.push({
                name: 'act_script',
                label: translate('LBL_PMSE_PROPERTY_GRID_SCRIPT'),
                type: 'text',
                value: element.getScript(),
                readOnly: true
            });
            setup.rows.push({
                name: 'act_script_type',
                label: translate('LBL_PMSE_PROPERTY_GRID_SCRIPT_TYPE'),
                type: 'text',
                value: element.getScriptType(),
                readOnly: true
            });
        }
        if (element.act_loop_type.toLowerCase() !== 'none') {
            setup.rows.push({
                name: 'act_loop_behavior',
                label: translate('LBL_PMSE_PROPERTY_GRID_LOOP_BEHAVIOR'),
                type: 'text',
                value: element.act_loop_behavior,
                readOnly: true
            });
            setup.rows.push({
                name: 'act_loop_cardinality',
                label: translate('LBL_PMSE_PROPERTY_GRID_LOOP_CARDINALITY'),
                type: 'text',
                validate: 'integer',
                value: element.act_loop_cardinality,
                readOnly: true
            });
            setup.rows.push({
                name: 'act_loop_maximum',
                label: translate('LBL_PMSE_PROPERTY_GRID_LOOP_MAXIMUM'),
                type: 'text',
                validate: 'integer',
                value: element.act_loop_maximum,
                readOnly: true
            });
            aux = element.act_loop_type.toUpperCase();
            setup.rows.push({
                name: 'act_loop_type',
                label: translate('LBL_PMSE_PROPERTY_GRID_LOOP_TYPE'),
                type: 'select',
                options: [
                    {
                        label: translate("LBL_PMSE_PROPERTY_GRID_NONE"),
                        value: "NONE",
                        selected: aux
                    },
                    {
                        label: translate("LBL_PMSE_PROPERTY_GRID_STANDARD"),
                        value: "STANDARD",
                        selected: aux
                    },
                    {
                        label: translate("LBL_PMSE_PROPERTY_GRID_MULTI_INSTANCE_PARALLEL"),
                        value: "PARALLEL",
                        selected: aux
                    },
                    {
                        label: translate("LBL_PMSE_PROPERTY_GRID_MULTI_INSTANCE_SEQUENCIAL"),
                        value: "SEQUENCIAL",
                        selected: aux
                    }
                ],
                readOnly: true
            });
        }
        break;
    case 'AdamGateway':
        if (element.gat_type === 'EVENTBASED') {
            readOnly: true
            options = [{
                label: translate('LBL_PMSE_PROPERTY_GRID_UNSPECIFIED'),
                value: "UNSPECIFIED",
                selected: element.gat_direction
            }];
        } else {
            readOnly: true
            options = [
//                {
//                    label: 'Unspecified',
//                    value: "UNSPECIFIED",
//                    selected: element.gat_direction
//                },

                {
                    label: translate('LBL_PMSE_PROPERTY_GRID_CONVERGING'),
                    value: 'CONVERGING',
                    selected: element.gat_direction
                },
                {
                    label: translate('LBL_PMSE_PROPERTY_GRID_DIVERGING'),
                    value: 'DIVERGING',
                    selected: element.gat_direction
                }
//                {
//                    label: 'Mixed',
//                    value: 'MIXED',
//                    selected: element.gat_direction
//                }
            ];
        }

        setup.rows.push({
            name: 'gat_direction',
            label: translate('LBL_PMSE_PROPERTY_GRID_DIRECTION'),
            type: 'select',
            readOnly: true,
            options: options
        });
        setup.rows.push({
            name: "type",
            label: translate("LBL_PMSE_PROPERTY_GRID_TYPE"),
            type: "text",
            value: element.gat_type,
            readOnly: true
        });
        break;
    case 'AdamEvent':
        if (element.evn_type === 'INTERMEDIATE' || element.evn_type === 'BOUNDARY') {
            setup.rows.push({
                name: "action",
                label: translate("LBL_PMSE_PROPERTY_GRID_ACTION"),
                type: "select",
                options: [
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_SEND_MESSAGE'),
                        value: 'MESSAGE-THROW',
                        selected: element.evn_marker + '-' + element.evn_behavior
                    },
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_RECEIVE_MESSAGE'),
                        value: 'MESSAGE-CATCH',
                        selected: element.evn_marker + '-' + element.evn_behavior
                    },
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_TIMER'),
                        value: 'TIMER-CATCH',
                        selected: element.evn_marker + '-CATCH'
                    }
                ],
                readOnly: true
            });
        }
        if (element.evn_type === 'START' || element.evn_type === 'BOUNDARY') {
            setup.rows.push({
                name: "evn_is_interrupting",
                label: translate("LBL_PMSE_PROPERTY_GRID_INTERUPTING"),
                value: element.evn_is_interrupting,
                type: "yesNo",
                readOnly: true
            });
        }
        if (element.evn_type !== 'START' && element.evn_marker === "MESSAGE") {
            setup.rows.push({
                name: 'evn_message',
                label: translate('LBL_PMSE_PROPERTY_GRID_MESSAGE'),
                type: 'text',
                value: element.evn_message,
                readOnly: true
            });
            setup.rows.push({
                name: 'evn_operation_implementation',
                label: translate('LBL_PMSE_PROPERTY_GRID_OPERATION_IMPLEMENTATION_REF'),
                type: 'text',
                value: element.evn_operation_implementation,
                readOnly: true
            });
            setup.rows.push({
                name: 'evn_operation_name',
                label: translate('LBL_PMSE_PROPERTY_GRID_OPERATION_NAME'),
                type: 'text',
                value: element.evn_operation_name,
                readOnly: true
            });
        }
        switch (element.evn_type) {
        case 'START':
            setup.rows.push({
                name: "listen",
                label: translate("LBL_PMSE_PROPERTY_GRID_LISTEN"),
                type: "select",
                options: [
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_LEAD'),
                        value: 'Leads',
                        selected: element.evn_message
                    },
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_DOCUMENT'),
                        value: 'Documents',
                        selected: element.evn_message
                    },
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_OPPORTUNITY'),
                        value: 'Opportunities',
                        selected: element.evn_message
                    },
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_OTHER_MODULE'),
                        value: '',
                        selected: element.evn_message
                    }
                ],
                readOnly: true
            });
            break;
        case 'INTERMEDIATE':
            if (element.evn_marker === 'TIMER') {
                setup.rows.push({
                    name: 'evn_time_cycle',
                    label: translate('LBL_PMSE_PROPERTY_GRID_TIME_CYCLE'),
                    type: 'text',
                    value: element.evn_time_cycle,
                    readOnly: true
                });
                setup.rows.push({
                    name: 'evn_time_date',
                    label: translate('LBL_PMSE_PROPERTY_GRID_TIME_DATE'),
                    type: 'text',
                    value: element.evn_time_date,
                    readOnly: true
                });
                setup.rows.push({
                    name: 'evn_time_duration',
                    label: translate('LBL_PMSE_PROPERTY_GRID_TIME_DURATION'),
                    type: 'text',
                    value: element.evn_time_duration,
                    readOnly: true
                });
            }
            break;
        case 'END':
            setup.rows.push({
                name: 'result',
                label: translate('LBL_PMSE_PROPERTY_GRID_RESULT'),
                type: 'select',
                options: [
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_EMPTY'),
                        value: 'EMPTY',
                        selected: element.evn_marker
                    },
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_MESSAGE'),
                        value: 'MESSAGE',
                        selected: element.evn_marker
                    },
                    {
                        label: translate('LBL_PMSE_PROPERTY_GRID_TERMINATE'),
                        value: 'TERMINATE',
                        selected: element.evn_marker
                    }
                ],
                readOnly: true
            });
            break;
        case 'BOUNDARY':
            break;
        }
        setup.rows.push({
            name: "type",
            label: translate("LBL_PMSE_PROPERTY_GRID_TYPE"),
            value: element.evn_type,
            type: "text",
            readOnly: true
        });
        break;
    case 'Connection':
//        if (element.flo_type !== "ASSOCIATION") {
//            setup.rows.push({
//                name: "flo_condition",
//                label: 'Conditions',
//                type: 'text',
//                value: element.flo_condition,
//                readOnly:true
//            });
//        }
        setup.rows.push({
            name: 'flo_type',
            label: translate('LBL_PMSE_PROPERTY_GRID_TYPE'),
            type: 'text',
            value: element.flo_type,
            readOnly: true
        });
        break;
    }

    setup.onValueChanged = function (e) {
        var command = null,
            aux,
            elm,
            mp,
            valid;
        //check if the row name is set and it is different than an empty string

        if (typeof e.fieldName === 'undefined'  || e.fieldName === '') {
            throw new Error('missing name for ' + e.fieldLabel);
        }

        elm = (element.type !== 'Connection')
            ? element.canvas.customShapes.find('id', e.id)
            : element.canvas.connections.find('id', e.id);

        switch (e.fieldName) {
        case 'name':
            valid = element.canvas.validateName(element.label, e.value);
            if (!valid.valid) {
                mp = new MessagePanel({
                    title: 'Error',
                    wtype: 'Error',
                    message: valid.message
                });
                mp.show();
                break;
            }
            command = new jCore.CommandEditLabel(element.label, e.value);
            break;
        case 'x':
        case 'y':
            element.setOldX(elm.getX());
            element.setX(e.fieldName === 'x' ? e.value : elm.getX());
            element.setOldY(elm.getY());
            element.setY(e.fieldName === 'y' ? e.value : elm.getY());
            command = new jCore.CommandMove((new jCore.ArrayList()).insert(elm));
            break;
        case 'width':
        case 'height':
            element.setOldX(element.getX());
            element.setOldY(element.getY());
            element.oldWidth = element.getWidth();
            element.setWidth(e.fieldName === 'width' ? e.value : element.getWidth());
            element.oldHeight = element.getHeight();
            element.setHeight(e.fieldName === 'height' ? e.value : element.getHeight());
            if (element.type === 'AdamArtifact') {
                command = new CommandAnnotationResize(element);
            } else {
                command = new jCore.CommandResize(element);
            }
            break;
        case 'act_task_type':
            element.updateTaskType(e.value);
            break;
        case 'listen':
            element.updateEventMarker({
                evn_message: e.value,
                evn_marker: "MESSAGE",
                evn_behavior: "CATCH"
            });
            break;
        case 'action':
            aux = e.value.split("-");
            element.updateEventMarker({
                evn_marker: aux[0],
                evn_behavior: aux[1]
            });
            break;
        case 'result':
            element.updateEventMarker({
                evn_marker: e.value,
                evn_behavior: "THROW"
            });
            break;
        case 'flo_condition':
            command = new CommandConnectionCondition(element, e.value);
            break;
        default:
            command = new CommandSingleProperty(element, {
                propertyName: e.fieldName,
                before: elm[e.fieldName],
                after: e.value
            });
        }
        if (command) {
            command.execute();
            element.getCanvas().commandStack.add(command);
        }
    };

    //this.propertiesGrid.load(setup);
};

AdamProject.prototype.onSelectElementHandler = function (canvas) {
    var that = this;
    return function () {
        //that.propertiesGrid.clear();
        if (canvas.getCurrentSelection().getSize() === 1 || canvas.updatedElement[0].relatedObject.type === 'Connection') {
            if (canvas.updatedElement[0].relatedObject.type !== 'Connection') {
                canvas.project.updatePropertiesGrid(canvas.getCurrentSelection().get(0));
                setSelectedNode(canvas.getCurrentSelection().get(0));
            } else {
                canvas.project.updatePropertiesGrid(canvas.updatedElement[0].relatedObject);
            }
        }
    };
};

AdamProject.prototype.addMetadata = function (metadataName, config, replaceIfExists) {
    var meta, proxy;
    config = config || {};
    if (typeof config !== "object") {
        throw new Error("addMetadata(): the second (which is optional) must be an object or null.");
    }
    if (!this._metadata[metadataName] || replaceIfExists) {
        meta = this._metadata[metadataName] = {};
        if (typeof config.dataURL === "string" && config.dataURL) {
            meta.dataURL = config.dataURL;
            meta.dataRoot = config.dataRoot;
            proxy = new SugarProxy();
            proxy.url = config.dataURL;
            proxy.getData(null, {
                success: function (data) {
                    meta.data = config.dataRoot ? data[config.dataRoot] : data;
                    if (typeof config.success === "function") {
                        config.success(meta.data);
                    }
                }
            });
            return;
        } else if (config.data) {
            meta.data = config.data
        }
    }

    if (typeof config.success === "function") {
        config.success(this._metadata[metadataName].data);
    }

    return this;
};

AdamProject.prototype.setMetadata = function (metadata) {
    var i, metadataName;
    if(!jQuery.isArray(metadata)) {
        throw new Error("setMetadata(): The parameter must be an array.");
    }
    for (i = 0; i < metadata.length; i++) {
        if (typeof metadata[i] !== 'object') {
            throw new Error("setMetadata(): All the elements of the array parameter must be objects.");
        }
        if (metadataName = metadata[i].name) {
            this.addMetadata(metadataName, metadata[i], true);
        }
    }

    return this;
};

AdamProject.prototype.getMetadata = function (metadataName) {
    return (this._metadata[metadataName] && this._metadata[metadataName].data) || null;
};

AdamProject.prototype.dispose = function () {
    // TODO: dispose the project completely
    jQuery('body > .adam-modal').remove();
    jCore.dispose();
}
