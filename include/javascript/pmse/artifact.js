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
/*global jCore, AdamShape, AdamArtifactResizeBehavior, $, PMSE.Action
 */
var PMSE = PMSE || {};
/**
 * @class AdamArtifact
 * Handle BPMN Text Annotations
 *
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 */
var AdamArtifact = function (options) {
    AdamShape.call(this, options);
    /**
     * Defines the type artifact
     * @type {String}
     */
    this.art_type = null;
    /**
     * Defines the unique identifier
     * @type {String}
     */
    this.art_uid = null;
    /**
     * Defines the atifact's category associated
     * @type {String}
     */
    this.art_category_ref = null;

    AdamArtifact.prototype.initObject.call(this, options);
};
AdamArtifact.prototype = new AdamShape();

/**
 * Defines the object type
 * @type {String}
 */
AdamArtifact.prototype.type = "AdamArtifact";
AdamArtifact.prototype.adamArtifactResizeBehavior = null;

/**
 * Add resize behavior factory for extend  the regular behavior clases
 * @param {String} type
 * @returns {TextAnnotationResizeBehavior}
 */
AdamArtifact.prototype.resizeBehaviorFactory = function (type) {
    if (type === 'adamArtifactResize') {
        if (!this.adamArtifactResizeBehavior) {
            this.adamArtifactResizeBehavior = new AdamArtifactResizeBehavior();
        }
        return this.adamArtifactResizeBehavior;
    } else {
        return AdamShape.prototype.resizeBehaviorFactory.call(this, type);
    }
};

/**
 * Initialize the object with the default values
 * @param {Object} options
 */
AdamArtifact.prototype.initObject = function (options) {
    var defaults = {
        art_type: 'TEXTANNOTATION'
    };
    $.extend(true, defaults, options);
    this.setArtifactType(defaults.art_type);
    if (defaults.art_name) {
        this.setName(defaults.art_name);
    }
    this.setArtifactUid(defaults.art_uid || null);
    this.setCategoryRef(defaults.art_category_ref || null);
};

/**
 * Sets the artifact type property
 * @param {String} type
 * @return {*}
 */
AdamArtifact.prototype.setArtifactType = function (type) {
    this.art_type = type;
    return this;
};

/**
 * Sets the artifact's category reference
 * @param {String} value
 * @return {*}
 */
AdamArtifact.prototype.setCategoryRef = function (value) {
    this.art_category_ref = value;
    return this;
};

/**
 * Sets the artifact unique identifier
 * @param {String} value
 * @return {*}
 */
AdamArtifact.prototype.setArtifactUid = function (value) {
    this.art_uid = value;
    return this;
};

/**
 * Returns the clean object to be sent to the backend
 * @return {Object}
 */
AdamArtifact.prototype.getDBObject = function () {
    var name = this.getName();
    return {
        art_uid: this.art_uid,
        art_name: name,
        art_type: this.art_type,
        art_category_ref: this.art_category_ref,
        bou_x: this.x,
        bou_y: this.y,
        bou_width: this.width,
        bou_height: this.height,
        bou_container: 'bpmnDiagram',
        element_id: this.canvas.dia_id
    };
};

/**
 * Extends the createHTML method to customize css classes
 * @return {String}
 */
AdamArtifact.prototype.createHTML = function () {
    AdamShape.prototype.createHTML.call(this);
    this.style.addClasses(['adam_artifact']);
    return this.html;
};

/**
 * Extends the paint method to draw text annotation lines
 */
AdamArtifact.prototype.paint = function () {
    var layerName = "border-layer",
        layer = this.findLayer(this.id + "Layer-" + layerName),
        borderDiv;

    if (!layer) {
        this.createLayer({
            layerName: layerName,
            x: 0,
            y:0
        });
        layer = this.findLayer(this.id + "Layer-" + layerName);
        borderDiv = document.createElement('div');
        borderDiv.className = "adam-artifact-annotation-border";
        layer.html.appendChild(borderDiv);
    }
};


AdamArtifact.prototype.getArtifactType = function () {
    return this.art_type;
};

AdamArtifact.prototype.getContextMenu = function () {
    var deleteAction,
        self = this;

    deleteAction = new PMSE.Action({
        text: 'Delete',
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

    return {
        items: [deleteAction]
    };
};

