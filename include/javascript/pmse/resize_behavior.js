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
 * @class AdamActivityResizeBehavior
 * Defines the resize behavior for the activities
 *
 * @constructor
 * Create a new Resize Behavior object
 */
var AdamActivityResizeBehavior = function () {
};
AdamActivityResizeBehavior.prototype = new jCore.RegularResizeBehavior();
/**
 * Defines the object type
 * @type {String}
 */
AdamActivityResizeBehavior.prototype.type = "AdamActivityResizeBehavior";


/**
 * Sets a shape's container to a given container
 * @param container
 * @param shape
 */
AdamActivityResizeBehavior.prototype.onResizeStart = function (shape) {
    return function (e, ui) {
        var zoomFactor = shape.canvas.getZoomFactor();
        $(this).resizable("option", "minHeight", shape.getMinHeight() * zoomFactor);
        $(this).resizable("option", "minWidth", shape.getMinWidth() * zoomFactor);

        $(this).resizable("option", "maxHeight", shape.getMaxHeight() * zoomFactor);
        $(this).resizable("option", "maxWidth", shape.getMaxWidth() * zoomFactor);

        shape.canvas.hideAllFocusedLabels();
        jCore.RegularResizeBehavior
            .prototype.onResizeStart.call(this, shape);

    };
};
/**
 * Removes shape from its current container
 * @param shape
 */
AdamActivityResizeBehavior.prototype.onResize = function (shape) {
    //RegularResizeBehavior.prototype.onResize.call(this, shape);

    return function (e, ui) {
        var i,
            port,
            canvas = shape.canvas;
        shape.setPosition(ui.position.left / canvas.zoomFactor,
            ui.position.top / canvas.zoomFactor);
        shape.setDimension(ui.size.width / canvas.zoomFactor,
            ui.size.height / canvas.zoomFactor);

        // fix the position of the shape's ports (and the positions and port
        // position of its children)
        // parameters (shape, resizing, root)
        shape.fixConnectionsOnResize(shape.resizing, true);

        // fix the labels positions on resize (on x = true and y = true)
        shape.updateLabelsPosition(true, true);
        //shape.updateBoundaryPositions(false);

        for (i = 0; i < shape.markersArray.getSize(); i += 1) {
            shape.markersArray.get(i).paint();
        }

    };

};

/**
 * Adds a shape to a given container
 * @param container
 * @param shape
 */
AdamActivityResizeBehavior.prototype.onResizeEnd = function (shape) {
    return function (e, ui) {
        var i, size, port, canvas = shape.canvas;
        jCore.RegularResizeBehavior.prototype.onResizeEnd.call(this, shape)(e, ui);

        for (i = 0, size = shape.getPorts().getSize(); i < size; i += 1) {
            port = shape.getPorts().get(i);
            //canvas.triggerUpdatePortPositionEvent(port);
            canvas.triggerPortChangeEvent(port);
        }
        //shape.label.setLabelPosition(shape.label.location, shape.label.diffX, shape.label.diffY);
        //shape.fixConnectionsOnResize(shape.resizing, true);
        //shape.refreshChildrenPositions(true);
        //shape.refreshConnections(false, true);
       // shape.showAllChilds();
    };

};

/**
 * Updates the min height and max height of the JQqueryUI's resizable plugin
 * @param shape
 */
AdamActivityResizeBehavior.prototype.updateResizeMinimums = function (shape) {
    var minW,
        minH,
        children = shape.getChildren(),
    //limits = children.getDimensionLimit(),
        limits,
        margin = 15,
        $shape = $(shape.getHTML()),
        i,
        child,
        childWithoutBoundaries = new jCore.ArrayList();

    for (i = 0; i < children.getSize(); i += 1) {
        child = children.get(i);
        if (!(child.type === 'AdamEvent' && child.evn_type === 'BOUNDARY')) {
            childWithoutBoundaries.insert(child);
        }
    }
    limits = childWithoutBoundaries.getDimensionLimit();
        // TODO: consider the labels width and height
//    if (subProcess.label.orientation === 'vertical') {
//        minW = Math.max(limits[1], Math.max(labelH, subProcess.label.height)) +
//            margin + 8;
//        minH = Math.max(limits[2], Math.max(labelW, subProcess.label.width)) +
//            margin;
//    } else {
//        minW = Math.max(limits[1], Math.max(labelW, subProcess.label.width)) +
//            margin;
//        minH = Math.max(limits[2], Math.max(labelH, subProcess.label.height)) +
//            margin + 8;
//    }

    minW = limits[1] + margin;
    minH = limits[2] + margin;

    // update jQueryUI's minWidth and minHeight
    $shape.resizable('option', 'minWidth', minW);
    $shape.resizable('option', 'minHeight', minH);
};