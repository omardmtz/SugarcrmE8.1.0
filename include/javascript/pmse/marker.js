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
/*global jCore, $ */
/**
 * @class AdamMarker
 * Handle Activity Markers
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 */
var AdamMarker = function (options) {
    jCore.Shape.call(this, options);
    /**
     * Defines the positions of the markers
     * @type {Array}
     * @private
     */
    this.positions = ['left top', 'center top', 'right top',
        'left bottom', 'center bottom', 'right bottom'];
    /**
     * Defines the offset of the markers
     * @type {Array}
     * @private
     */
    this.offset =  ['5 5', '0 5', '0 0', '5 -1', '0 -1', '-5 -1'];
    /**
     * Define the marker type property
     * @type {null}
     */
    this.markerType = null;
    AdamMarker.prototype.initObject.call(this, options);
};
AdamMarker.prototype = new jCore.Shape();
/**
 * Defines the object type
 * @type {String}
 */
AdamMarker.prototype.type = 'AdamMarker';

/**
 * Initialize the object with the default values
 * @param {Object} options
 */
AdamMarker.prototype.initObject = function (options) {
    var defaults = {
        canvas: null,
        parent: null,
        position: 0,
        width: 19,
        height: 19,
        markerZoomClasses: [],
        markerType: null
    };
    $.extend(true, defaults, options);
    this.setParent(defaults.parent)
        .setPosition(defaults.position)
        .setHeight(defaults.height)
        .setWidth(defaults.width)
        .setMarkerZoomClasses(defaults.markerZoomClasses)
        .setMarkerType(defaults.markerType);
};

/**
 * Applies zoom to the Marker
 * @return {*}
 */
AdamMarker.prototype.applyZoom = function () {
    var newSprite;
    this.removeAllClasses();
    this.setProperties();
    newSprite = this.markerZoomClasses[this.parent.canvas.zoomPropertiesIndex];
    this.html.className = newSprite;
    this.currentZoomClass = newSprite;
    return this;
};

/**
 * Create the HTML for the marker
 * @return {*}
 */
AdamMarker.prototype.createHTML = function () {
    jCore.Shape.prototype.createHTML.call(this);

    this.html.id = this.id;
    this.setProperties();
    this.html.className = this.markerZoomClasses[
        this.parent.canvas.getZoomPropertiesIndex()
    ];
    this.currentZoomClass = this.html.className;
    this.parent.html.appendChild(this.html);
    return this.html;
};

/**
 * Updates the painting of the marker
 * @param update
 */
AdamMarker.prototype.paint = function (update) {
    if (this.getHTML() === null || update) {
        this.createHTML();
    }
    $(this.html).position({
        of: $(this.parent.html),
        my: this.positions[this.position],
        at: this.positions[this.position],
        offset: this.offset[this.position],
        collision: 'none'
    });
};

/**
 * Sets the marker type property
 * @param {String} newType
 * @return {*}
 */
AdamMarker.prototype.setMarkerType = function (newType) {
    this.markerType = newType;
    return this;
};

/**
 * Sets the position of the marker
 * @param {Number} newPosition
 * @return {*}
 */
AdamMarker.prototype.setPosition = function (newPosition) {
    if (newPosition !== null && typeof newPosition === 'number') {
        this.position = newPosition;
    }
    return this;
};

/**
 * Sets the parent of the marker
 * @param {AdamActivity} newParent
 * @return {*}
 */
AdamMarker.prototype.setParent = function (newParent) {
    this.parent = newParent;
    return this;
};

/**
 * Sets the elements class
 * @param eClass
 * @return {*}
 */
AdamMarker.prototype.setEClass = function (eClass) {
    this.currentZoomClass = eClass;
    return this;
};

/**
 * Sets the array of zoom classes
 * @param {Object} classes
 * @return {*}
 */
AdamMarker.prototype.setMarkerZoomClasses = function (classes) {
    this.markerZoomClasses = classes;
    return this;
};

/**
 * Sets the marker HTML properties
 * @return {*}
 */
AdamMarker.prototype.setProperties = function () {
    this.html.style.width = this.width * this.parent.getCanvas().getZoomFactor() + 'px';
    this.html.style.height = this.height * this.parent.getCanvas().getZoomFactor() + 'px';
    return this;
};

/**
 * Remove all classes of HTML
 * @return {*}
 */
AdamMarker.prototype.removeAllClasses = function () {
    this.html.className = '';
    return this;
};

AdamMarker.prototype.setElementClass = function (newClassArray) {
    var newSprite;
    this.setEClass(newClassArray);
    this.removeAllClasses();
    this.applyZoom();
    return this;
};