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
/*global jCore, $

*/
/**
 * @class AdamConnectionDragBehavior
 * Handle the DragBehavior for Shapes
 *
 * @constructor
 * Creates a new object instance
 */
var AdamConnectionDragBehavior = function () {
    jCore.ConnectionDragBehavior.call(this);
};
AdamConnectionDragBehavior.prototype = new jCore.ConnectionDragBehavior();
/**
 * Defines the object type
 * @type {String}
 */
AdamConnectionDragBehavior.prototype.type = "AdamConnectionDragBehavior";

/**
 * Define the functionality associated with drag events
 * @param customShape
 * @return {Function}
 */
//AdamConnectionDragBehavior.prototype.onDrag = function (customShape) {
//    return function (e, ui) {
//        var canvas = customShape.getCanvas(),
//            endPoint = new jCore.Point();
//        if (canvas.connectionSegment) {
//            //remove the connection segment in order to create another one
//            $(canvas.connectionSegment.getHTML()).remove();
//        }
//
//        e.pageX = e.pageX || e.originalEvent.pageX;
//        e.pageY = e.pageY || e.originalEvent.pageY;
//
//        //Determine the point where the mouse currently is
//        endPoint.x = e.pageX - canvas.getX() + canvas.getLeftScroll();
//        endPoint.y = e.pageY - canvas.getY() + canvas.getTopScroll();
//
//        //creates a new segment from where the helper was created to the
//        // currently mouse location
//
////        canvas.connectionSegment = new jCore.Segment({
////            startPoint : customShape.startConnectionPoint,
////            endPoint : endPoint,
////            parent : canvas,
////            zOrder: jCore.Style.MAX_ZINDEX * 2
////        });
////        //We make the connection segment point to helper in order to get
////        // information when the drop occurs
////        canvas.connectionSegment.pointsTo = customShape;
////        //create HTML and paint
////        //canvas.connectionSegment.createHTML();
////        canvas.connectionSegment.paint();
//        console.log("Connection onDrag", e.pageX, e.pageY);
//    };
//};


/**
 * @class CustomShapeDragBehavior
 * Encapsulates the drag behavior of a custom shape (with ports and connections)
 * , it also encapsulates the behavior for multiple drag
 * @extends DragBehavior
 *
 * @constructor Creates a new instance of the class
 *
 */
var AdamShapeDragBehavior = function () {
    jCore.CustomShapeDragBehavior.call(this);
};
AdamShapeDragBehavior.prototype = new jCore.CustomShapeDragBehavior();

/**
 * Type of the instances
 * @property {String}
 */
AdamShapeDragBehavior.prototype.type = "AdamShapeDragBehavior";

/**
 * Attach the drag behavior and ui properties to the corresponding shape
 * @param {CustomShape} customShape
 */
AdamShapeDragBehavior.prototype.attachDragBehavior = function (customShape) {
    var dragOptions,
        $customShape = $(customShape.getHTML());
    dragOptions = {
        revert : false,
        helper : "none",
        cursorAt : false,
        revertDuration : 0,
        disable : false,
        grid : [1, 1],
        start : this.onDragStart(customShape),
        drag : this.onDrag(customShape, true),
        stop : this.onDragEnd(customShape, true),
        containment: "parent",
        scroll: false

    };
    $customShape.draggable(dragOptions);
};