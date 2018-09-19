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
/*global jCore

 */
/**
 * @class AdamActivityContainerBehavior
 * Handle the behavior when an activity acts like a container
 *
 * @constructor
 * Create a new instance of the object
 */
var AdamActivityContainerBehavior = function () {};
AdamActivityContainerBehavior.prototype = new jCore.RegularContainerBehavior();
/**
 * Defines the object type
 * @type {String}
 */
AdamActivityContainerBehavior.prototype.type = "AdamActivityContainerBehavior";

/**
 * Adds a shape into the container
 * @param {AdamActivity} container
 * @param {AdamEvent} shape
 * @param {Number} x
 * @param {Number} y
 * @param {Number} topLeftCorner
 */
AdamActivityContainerBehavior.prototype.addToContainer = function (container,
                                                               shape, x, y,
                                                               topLeftCorner) {
    var shapeLeft = 0,
        shapeTop = 0,
        shapeWidth,
        shapeHeight,
        canvas,
        topLeftFactor = (topLeftCorner === true) ? 0 : 1;

    if (container.family === "Canvas") {
        canvas = container;
    } else {
        canvas = container.canvas;
    }

    shapeWidth = shape.getZoomWidth();
    shapeHeight = shape.getZoomHeight();

    shapeLeft += x - (shapeWidth / 2) * topLeftFactor;
    shapeTop += y - (shapeHeight / 2) * topLeftFactor;

    shapeLeft /= container.zoomFactor;
    shapeTop /= container.zoomFactor;

    shape.setParent(container);
    container.getChildren().insert(shape);
    this.addShape(container, shape, shapeLeft, shapeTop);

    // fix the zIndex of this shape and it's children
    shape.fixZIndex(shape, 0);


    // adds the shape to either the customShape arrayList or the regularShapes
    // arrayList if possible
    canvas.addToList(shape);

    //setting boundary position
    if (shape.getType() === 'AdamEvent' && shape.evn_type === 'BOUNDARY') {
        shape.setAttachedTo(container.act_uid);
        container.boundaryArray.insert(shape);
        if (container.boundaryPlaces.isEmpty()) {
            container.makeBoundaryPlaces(shape);
        }
        shape.attachToActivity();
    }
    //container.updateDimensions(10);
};