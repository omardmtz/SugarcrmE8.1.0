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
var jCore = (function ($, window) {

    var isCtrl = false,             // if the control key is pressed
        isShift = false,            // if the shift key is pressed
        activeCanvas = null,        // pointer to the active canvas
        ArrayList,
        Point,
        Geometry,
        Graphics,
        Utils,
        Command,
        CommandStack,
        CommandResize,
        CommandConnect,
        CommandReconnect,
        CommandSegmentMove,
        CommandMove,
        CommandCreate,
        CommandSwitchContainer,
        CommandDelete,
        CommandPaste,
        CommandEditLabel,
        ContainerBehavior,
        RegularContainerBehavior,
        NoContainerBehavior,
        DragBehavior,
        RegularDragBehavior,
        NoDragBehavior,
        ConnectionDragBehavior,
        CustomShapeDragBehavior,
        ResizeBehavior,
        RegularResizeBehavior,
        NoResizeBehavior,
        DropBehavior,
        ConnectionDropBehavior,
        NoDropBehavior,
        ContainerDropBehavior,
        ConnectionContainerDropBehavior,
        Color,
        Style,
        JCoreObject,
        Handler,
        ReadOnlyLayer,
        ResizeHandler,
        SegmentMoveHandler,
        Port,
        Router,
        ManhattanConnectionRouter,
        ConnectionDecorator,
        Connection,
        BehavioralElement,
        Layer,
        Shape,
        Label,
        CustomShape,
        Segment,
        RegularShape,
        Polygon,
        Rectangle,
        Oval,
        Arc,
        MultipleSelectionContainer,
        Intersection,
        Snapper,
        Canvas,
        version = '1.2';  //jCore Version

    /**
     * @class ArrayList
     * Construct a List similar to Java's ArrayList that encapsulates methods for
     * making a list that supports operations like get, insert and others.
     *
     *      some examples:
     *      var item,
     *          arrayList = new ArrayList();
     *      arrayList.getSize()                 // 0
     *      arrayList.insert({                  // insert an object
 *          id: 100,
 *          width: 100,
 *          height: 100
 *      });
     *      arrayList.getSize();                // 1
     *      arrayList.asArray();                // [{id : 100, ...}]
     *      item = arrayList.find('id', 100);   // finds the first element with an id that equals 100
     *      arrayList.remove(item);             // remove item from the arrayList
     *      arrayList.getSize();                // 0
     *      arrayList.isEmpty();                // true because the arrayList has no elements
     *
     * @constructor Returns an instance of the class ArrayList
     */
    ArrayList = function () {
        /**
         * The elements of the arrayList
         * @property {Array}
         * @private
         */
        var elements = [],
            /**
             * The size of the array
             * @property {number} [size=0]
             * @private
             */
            size = 0,
            index,
            i;
        return {

            /**
             * The ID of this ArrayList is generated using the function Math.random
             * @property {number} id
             */
            id: Math.random(),
            /**
             * Gets an element in the specified index or undefined if the index
             * is not present in the array
             * @param {number} index
             * @returns {Object / undefined}
             */
            get : function (index) {
                return elements[index];
            },
            /**
             * Inserts an element at the end of the list
             * @param {Object}
             * @chainable
             */
            insert : function (item) {
                elements[size] = item;
                size += 1;
                return this;
            },
            /**
             * Inserts an element in a specific position
             * @param {Object} item
             * @chainable
             */
            insertAt: function(item, index) {
                elements.splice(index, 0, item);
                size = elements.length;
                return this;
            },
            /**
             * Removes an item from the list
             * @param {Object} item
             * @return {boolean}
             */
            remove : function (item) {
                index = this.indexOf(item);
                if (index === -1) {
                    return false;
                }
                //swap(elements[index], elements[size-1]);
                size -= 1;
                elements.splice(index, 1);
                return true;
            },
            /**
             * Gets the length of the list
             * @return {number}
             */
            getSize : function () {
                return size;
            },
            /**
             * Returns true if the list is empty
             * @returns {boolean}
             */
            isEmpty : function () {
                return size === 0;
            },
            /**
             * Returns the first occurrence of an element, if the element is not
             * contained in the list then returns -1
             * @param {Object} item
             * @return {number}
             */
            indexOf : function (item) {
                for (i = 0; i < size; i += 1) {
                    if (item.id === elements[i].id) {
                        return i;
                    }
                }
                return -1;
            },
            /**
             * Returns the the first object of the list that has the
             * specified attribute with the specified value
             * if the object is not found it returns undefined
             * @param {string} attribute
             * @param {string} value
             * @return {Object / undefined}
             */
            find : function (attribute, value) {
                var i,
                    current;
                for (i = 0; i < elements.length; i += 1) {
                    current = elements[i];
                    if (current[attribute] === value) {
                        return current;
                    }
                }
                return undefined;
            },

            /**
             * Returns true if the list contains the item and false otherwise
             * @param {Object} item
             * @return {boolean}
             */
            contains : function (item) {
                if (this.indexOf(item) !== -1) {
                    return true;
                }
                return false;
            },
            /**
             * Sorts the list using compFunction if possible, if no compFunction
             * is passed as an parameter then it returns false (the list is not sorted)
             * @param {Function} compFunction
             * @return {boolean}
             */
            sort : function (compFunction) {
                var returnValue = false;
                if (compFunction) {
                    elements.sort(compFunction);
                    returnValue = true;
                }
                return returnValue;
            },
            /**
             * Returns the list as an array
             * @return {Array}
             */
            asArray : function () {
                return elements;
            },
            /**
             * Returns the first element of the list
             * @return {Object}
             */
            getFirst : function () {
                return elements[0];
            },
            /**
             * Returns the last element of the list
             * @return {Object}
             */
            getLast : function () {
                return elements[size - 1];
            },

            /**
             * Returns the last element of the list and deletes it from the list
             * @return {Object}
             */
            popLast : function () {
                var lastElement;
                size -= 1;
                lastElement = elements[size];
                elements.splice(size, 1);
                return lastElement;
            },
            /**
             * Returns an array with the objects that determine the minimum size
             * the container should have
             * The array values are in this order TOP, RIGHT, BOTTOM AND LEFT
             * @return {Array}
             */
            getDimensionLimit : function () {
                var result = [100000, -1, -1, 100000],
                    objects = [undefined, undefined, undefined, undefined];
                //number of pixels we want the inner shapes to be
                //apart from the border

                for (i = 0; i < size; i += 1) {
                    if (result[0] > elements[i].y) {
                        result[0] = elements[i].y;
                        objects[0] = elements[i];

                    }
                    if (result[1] < elements[i].x + elements[i].width) {
                        result[1] = elements[i].x + elements[i].width;
                        objects[1] = elements[i];
                    }
                    if (result[2] < elements[i].y + elements[i].height) {
                        result[2] = elements[i].y + elements[i].height;
                        objects[2] = elements[i];
                    }
                    if (result[3] > elements[i].x) {
                        result[3] = elements[i].x;
                        objects[3] = elements[i];
                    }
                }
                return result;
            },
            /**
             * Clears the content of the arrayList
             * @chainable
             */
            clear : function () {
                if (size !== 0) {
                    elements = [];
                    size = 0;
                }
                return this;
            },
            /**
             * Returns the canvas of an element if possible
             * @return {Canvas / undefined}
             */
            getCanvas : function () {
                return (this.getSize() > 0) ? this.get(0).getCanvas() : undefined;
            }
        };
    };

// Declarations created to instantiate in NodeJS environment
    if (typeof exports !== 'undefined') {
        module.exports = ArrayList;
//    var _ = require('../../lib/underscore/underscore.js');
    }

    /**
     * @class Point
     * Class to represent points in the jCore library
     *
     *        // i.e.
     *        var p = new Point(100, 100);
     *
     * @constructor Creates an instance of this class
     * @param {number} xCoordinate x-coordinate of the point
     * @param {number} yCoordinate y-coordinate of the point
     * @return {Point}
     */
    Point = function (xCoordinate, yCoordinate) {
        /**
         * x coordinate of the point in the plane
         */
        this.x = xCoordinate;
        /**
         * y coordinate of the point in the plane
         */
        this.y = yCoordinate;
    };

    /**
     * Type of this class
     * @property {String}
     */
    Point.prototype.type = "Point";

    /**
     * Returns the X coordinate
     * @property {number}
     **/
    Point.prototype.getX = function () {
        return this.x;
    };

    /**
     * Returns the Y coordinate
     * @property {number}
     **/
    Point.prototype.getY = function () {
        return this.y;
    };

    /**
     * Adds `other` point to `this` point and returns a new point with those coordinates.
     *
     *      // i.e.
     *      var p1 = new Point(3, 5),
     *          p2 = new Point(2, 3);
     *      p1.add(p2);     // new Point(5, 8)
     *
     * @param {Point} other Point to be added to the current point
     * @returns {Point}
     */
    Point.prototype.add = function (other) {
        return new Point(this.x + other.x, this.y + other.y);
    };

    /**
     * Subtracts the other point to the one that called the function.
     *
     *      // i.e.
     *      var p1 = new Point(3, 5),
     *          p2 = new Point(2, 3);
     *      p1.subtract(p2);     // new Point(1, 2)
     *
     * @param {Point} other Point to be added to the current point
     * @returns {Point}
     */
    Point.prototype.subtract = function (other) {
        return new Point(this.x - other.x, this.y - other.y);
    };

    /**
     * Multiplies the point with a scalar k.
     *
     *      // i.e.
     *      var p1 = new Point(3, 5),
     *          k = 3;
     *      p1.multiply(k);     // new Point(9, 15)
     *
     * @param {number} k
     * @return {Point}
     */
    Point.prototype.multiply = function (k) {
        return new Point(this.x * k, this.y * k);
    };

    /**
     * Determine if the points are equal.
     *
     *      // i.e.
     *      var p1 = new Point(3, 5),
     *          p2 = new Point(2, 3),
     *          p3 = new Point(3, 5);
     *      p1.equals(p2);     // false
     *      p1.equals(p3);     // true
     *      p1.equals(p1);     // true
     *
     * @param {Point} other Point to be compared with the current point
     * @returns {boolean}
     */
    Point.prototype.equals = function (other) {
        return (Math.abs(this.x - other.x) < Geometry.eps) &&
        (Math.abs(this.y - other.y) < Geometry.eps);
    };

    /**
     * Determine the distance between two Points
     *
     *      // i.e.
     *      // distance = sqrt(pow(x1 - x2, 2) + pow(y1 - y2, 2))
     *      var p1 = new Point(3, 5),
     *          p2 = new Point(2, 3);
     *      p1.getDistance(p2);         // sqrt(1 + 4)
     *
     * @param {Point} other Point to be calculated from current point
     * @returns {number}
     **/
    Point.prototype.getDistance = function (other) {
        return Math.sqrt(
            (this.x - other.x) * (this.x - other.x) +
            (this.y - other.y) * (this.y - other.y)
        );
    };

    /**
     * Determine the squared distance between two Points
     *
     *      // i.e.
     *      // distance = sqrt(pow(x1 - x2, 2) + pow(y1 - y2, 2))
     *      // but since it's the squared distance then
     *      // distance = pow(distance, 2)
     *      var p1 = new Point(3, 5),
     *          p2 = new Point(2, 3);
     *      p1.getSquaredDistance(p2);         // (1 + 4)
     *
     * @param {Point} other Point to be calculated from current point
     * @returns {number}
     **/
    Point.prototype.getSquaredDistance = function (other) {
        return (this.x - other.x) * (this.x - other.x) +
        (this.y - other.y) * (this.y - other.y);
    };

    /**
     * Determine the manhattan distance between two Points
     *
     *      // i.e.
     *      var p1 = new Point(3, 5),
     *          p2 = new Point(2, 3);
     *      p1.getManhattanDistance(p2);         // (1 + 2)
     *
     * @param {Point} other Point to be calculated from current point
     * @returns {number}
     **/
    Point.prototype.getManhattanDistance = function (other) {
        return Math.abs(this.x - other.x) + Math.abs(this.y - other.y);
    };

    /**
     * Makes this a copy of the point named other
     *
     *      // i.e.
     *      var p1 = new Point(3, 5),
     *          cloneP1;
     *      cloneP1 = p1.clone();       // cloneP1 is Point(3, 5)
     *
     * @param {Point} other Point to be cloned
     * @returns {Point} This point
     */
    Point.prototype.clone = function () {
        return new Point(this.x, this.y);
    };

// Declarations created to instantiate in NodeJS environment
    if (typeof exports !== 'undefined') {
        module.exports = Point;
    }

    /**
     * @class Geometry
     * A little object that encapsulates most geometry functions used in the designer, most of the examples
     * are in 'spec/draw/geometry.spec.js'
     *
     * @singleton
     */
    Geometry = {
        /**
         * The number pi
         * @property {number} [pi=Math.acos(-1)]
         */
        pi : Math.acos(-1),
        /**
         * Epsilon used for the correctness in comparison of float numbers
         * @property {number} [eps=1e-8]
         */
        eps : 1e-8,
        /**
         * Calculates the cross product of 2-dimensional vectors
         * @param {Point} p1
         * @param {Point} p2
         * @return {number}
         */
        cross : function (p1, p2) {
            return p1.x * p2.y - p1.y * p2.x;
        },
        /**
         * Calculates the SIGNED area of a parallelogram given three points, these three points are the points
         * that conforms the triangle that is half of the parallelogram, so. the area of the triangle
         * defined with these points is half the returned number (this method can return negative values)
         *
         *      // i.e.
         *      var p1 = new Point(0, 0),
         *          p2 = new Point(0, 1),
         *          p3 = new Point(1, 0),
         *          parallelogramArea,
         *          triangleArea;
         *
         *      parallelogramArea = Geometry.area(p1, p2, p3)   // -1 (area of the parallelogram)
         *      triangleArea = parallelogramArea / 2            // -0.5 (area of the triangle)
         *
         * @param {Point} p1
         * @param {Point} p2
         * @param {Point} p3
         * @return {number}
         */
        area : function (p1, p2, p3) {
            var auxP2 = p2.clone(),
                auxP3 = p3.clone();
            return this.cross(auxP2.subtract(p1), auxP3.subtract(p1));
        },
        /**
         * Determines if the point P is on segment AB
         * @param {Point} P
         * @param {Point} A
         * @param {Point} B
         * @return {boolean}
         */
        onSegment : function (P, A, B) {
            return (Math.abs(this.area(A, B, P)) < this.eps &&
            P.x >= Math.min(A.x, B.x) && P.x <= Math.max(A.x, B.x) &&
            P.y >= Math.min(A.y, B.y) && P.y <= Math.max(A.y, B.y));
        },
        /**
         * Checks if two perpendicular segments intersect, if so it returns the intersection point,
         * (this method only allows the perpendicular segment to be parallel to the x and y axis)
         * @param {Point} A
         * @param {Point} B
         * @param {Point} C
         * @param {Point} D
         * @return {Object}
         */
        perpendicularSegmentIntersection : function (A, B, C, D) {
            var clone,
                returnValue = null;

            // swap the segments if possible
            if (A.x > B.x || A.y > B.y) {
                clone = A.clone();
                A = B.clone();
                B = clone;
            }

            if (C.x > D.x || C.y > D.y) {
                clone = C.clone();
                C = D.clone();
                D = clone;
            }

            if (A.x === B.x) {
                if (C.y === D.y && C.x < A.x && A.x < D.x &&
                    A.y < C.y && C.y < B.y) {
                    returnValue = new Point(A.x, C.y);
                }
            } else if (A.y === B.y) {
                if (C.x === D.x && A.x < C.x && C.x < B.x &&
                    C.y < A.y && A.y < D.y) {
                    returnValue = new Point(C.x, A.y);
                }
            }
            return returnValue;
        },
        /**
         * Determines if segment AB intersects with segment CD (won't check infinite intersections),
         * if `strict` is set to `true` then it'll consider the case when one end of a segment is right in the
         * other segment
         * @param {Point} A
         * @param {Point} B
         * @param {Point} C
         * @param {Point} D
         * @param {boolean} [strict]
         * @return {boolean}
         */
        segmentIntersection : function (A, B, C, D, strict) {

            var area1 = this.area(C, D, A),
                area2 = this.area(C, D, B),
                area3 = this.area(A, B, C),
                area4 = this.area(A, B, D),
                returnValue;
            if (((area1 > 0 && area2 < 0) || (area1 < 0 && area2 > 0)) &&
                ((area3 > 0 && area4 < 0) || (area3 < 0 && area4 > 0))) {
                return true;
            }

            returnValue = false;
            if (strict) {
                if (area1 === 0 && this.onSegment(A, C, D)) {
                    returnValue = true;
                } else if (area2 === 0 && this.onSegment(B, C, D)) {
                    returnValue = true;
                } else if (area3 === 0 && this.onSegment(C, A, B)) {
                    returnValue = true;
                } else if (area4 === 0 && this.onSegment(D, A, B)) {
                    returnValue = true;
                }
            }
            return returnValue;
        },
        /**
         * Checks if two segments intersect, if so it returns the intersection point
         * @param {Point} A
         * @param {Point} B
         * @param {Point} C
         * @param {Point} D
         * @return {Point}
         */
        segmentIntersectionPoint: function (A, B, C, D) {
            return A.add((B.subtract(A))
                .multiply(this.cross(C.subtract(A), D.subtract(A)) /
                this.cross(B.subtract(A), D.subtract(C))));
        },
        /**
         * Determines whether a point is in a given rectangle or not given its
         * upperLeft and bottomRight corner (consider that a rectangle is turned in the y-axis)
         * @param {Point} point
         * @param {Point} upperLeft
         * @param {Point} bottomRight
         * @return {boolean}
         */
        pointInRectangle : function (point, upperLeft, bottomRight) {
            return (point.x >= upperLeft.x && point.x <= bottomRight.x &&
            point.y >= upperLeft.y && point.y <= bottomRight.y);
        },
        /**
         * Determines whether a point is in a circle or not given its center and
         * radius
         * @param {Point} point
         * @param {Point} center
         * @param {number} radius
         * @returns {boolean}
         */
        pointInCircle : function (point, center, radius) {
            return center.getDistance(point) <= radius;
        },
        /**
         * Determine whether a point is inside a rhombus or not given its center
         * and its points in clockwise order
         * @param {Point} point
         * @param {Array} rhombus
         * @param {Point} center
         * @return {boolean}
         */
        pointInRhombus : function (point, rhombus, center) {
            var i,
                j = rhombus.length - 1;
            for (i = 0; i < rhombus.length; j = i, i += 1) {
                if (this.segmentIntersection(center, point,
                        rhombus[j], rhombus[i], true) &&
                    this.onSegment(point, rhombus[j], rhombus[i]) === false) {
                    return false;
                }
            }
            return true;
        }
    };

// Declarations created to instantiate in NodeJS environment
    if (typeof exports !== 'undefined') {
        module.exports = Geometry;
    }


    /**
     * @class Utils
     * A class that has utilities used in the library like coordinates converter,
     * unique id generator, and shape locator.
     *
     * @singleton
     */

    Utils = {
        /**
         * Converts the coordinates `xCoord` and `yCoord` (assuming that xCoord and yCoord are pageCoordinates)
         * or the page coordinates gathered from the object `e` if there is no `xCoord` or `yCoord` to
         * `shape` coordinates, this new coordinate also considers the scroll done in the canvas
         *
         *      // i.e.
         *      // Let's assume that:
         *      // the canvas coordinates are [100, 100] and that it has no scroll
         *      // the shape coordinates are [100, 100] (inside the canvas)
         *      // e is an object containing page.X = 300, page.Y = 300
         *      Utils.pageCoordinatesToShapeCoordinates(shape, e)  // new Point(100, 100) respect to the shape
         *
         *
         * @param {Object} shape
         * @param {Object} e
         * @param {number} [xCoord]
         * @param {number} [yCoord]
         * @return {Point} a point relative to the canvas
         */
        pageCoordinatesToShapeCoordinates : function (shape, e, xCoord, yCoord) {
            var coordinates,
                x = (!xCoord) ? e.pageX : xCoord,
                y = (!yCoord) ? e.pageY : yCoord,
                canvas = shape.getCanvas();
            x += canvas.getLeftScroll() - shape.getAbsoluteX() - canvas.getX();
            y += canvas.getTopScroll() - shape.getAbsoluteY() - canvas.getY();
            coordinates = new Point(x, y);
            return coordinates;
        },
        /**
         * Converts the coordinates of the `shape` to page coordinates, this method
         * also considers the scroll of the canvas in the calculation
         *
         *      // i.e.
         *      // Let's assume that:
         *      // the canvas coordinates are [100, 100] and that it has no scroll
         *      // the shape coordinates are [100, 100] (inside the canvas)
         *      Utils.getPointRelativeToPage(shape)     // new Point(200, 200) respect to the page
         *
         * @param {Object} shape
         * @return {Point} a point relative to the page
         */
        getPointRelativeToPage : function (shape) {
            var canvas = shape.getCanvas(),
                x = shape.absoluteX + canvas.getX() - canvas.getLeftScroll() +
                    shape.zoomWidth / 2,
                y = shape.absoluteY + canvas.getY() - canvas.getTopScroll() +
                    shape.zoomHeight / 2;
            return new Point(x, y);
        },
        /**
         * Generates a random string of 32 characters
         *      // i.e.
         *      Utils.generateUniqueId()        //87945323950cb672d438a84031660752
         *      Utils.generateUniqueId()        //61920554050cb6736438a91037314125
         * @return {string}
         */
        generateUniqueId : function () {
            var rand = function (min, max) {
                    // Returns a random number
                    //
                    // version: 1109.2015
                    // discuss at: http://phpjs.org/functions/rand
                    // +   original by: Leslie Hoare
                    // +   bugfixed by: Onno Marsman
                    // %          note 1: See the commented out code below for a
                    // version which will work with our experimental
                    // (though probably unnecessary) srand() function)
                    // *     example 1: rand(1, 1);
                    // *     returns 1: 1

                    // fix for jsLint
                    // from: var argc = arguments.length;
                    if (typeof min === "undefined") {
                        min = 0;
                    }
                    if (typeof max === "undefined") {
                        max = 999999999;
                    }
                    return Math.floor(Math.random() * (max - min + 1)) + min;
                },
                uniqid = function (prefix, more_entropy) {
                    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
                    // +    revised by: Kankrelune (http://www.webfaktory.info/)
                    // %        note 1: Uses an internal counter (in php_js global) to avoid collision
                    // *     example 1: uniqid();
                    // *     returns 1: 'a30285b160c14'
                    // *     example 2: uniqid('foo');
                    // *     returns 2: 'fooa30285b1cd361'
                    // *     example 3: uniqid('bar', true);
                    // *     returns 3: 'bara20285b23dfd1.31879087'
                    if (typeof prefix === 'undefined') {
                        prefix = "";
                    }

                    var retId,
                        formatSeed = function (seed, reqWidth) {
                            var tempString = "",
                                i;
                            seed = parseInt(seed, 10).toString(16); // to hex str
                            if (reqWidth < seed.length) { // so long we split
                                return seed.slice(seed.length - reqWidth);
                            }
                            if (reqWidth > seed.length) { // so short we pad
                                // jsLint fix
                                tempString = "";
                                for (i = 0; i < 1 + (reqWidth - seed.length); i += 1) {
                                    tempString += "0";
                                }
                                return tempString + seed;
                            }
                            return seed;
                        };

                    // BEGIN REDUNDANT
                    if (!this.php_js) {
                        this.php_js = {};
                    }
                    // END REDUNDANT
                    if (!this.php_js.uniqidSeed) { // init seed with big random int
                        this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
                    }
                    this.php_js.uniqidSeed += 1;

                    retId = prefix; // start with prefix, add current milliseconds hex string
                    retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
                    retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
                    if (more_entropy) {
                        // for more entropy we add a float lower to 10
                        retId += (Math.random() * 10).toFixed(8).toString();
                    }

                    return retId;
                },
                sUID;

            do {
                sUID = uniqid(rand(0, 999999999), true);
                sUID = sUID.replace('.', '0');
            } while (sUID.length !== 32);

            return sUID;
        }
    };

    /**
     * @class Command
     * Abstract class command which declares some abstract methods such as
     * execute (redo) and inverseExecute (undo) a command.
     *
     * A command is implemented in the library as follows:
     *
     * - The command must define a method execute which does the operation desired (i.e. commandDelete's execute
     *      method deletes shapes and connections from a canvas).
     * - The command must define a method undo which undoes what the method execute did.
     * - The command must define a method redo which simply calls the execute method (redo must do the
     *      same operation as execute).
     *
     * Finally to execute and save the command let's use the {@link Canvas#property-commandStack} property that any
     * canvas has so:
     *
     *      // i.e.
     *      // let's assume that canvas is an instance of the class Canvas
     *      // let's create an instance of commandDelete
     *      // let's assume that config has the correct configuration options of this command
     *      var command = new CommandDelete(config)
     *      // let's add the command to the canvas's commandStack
     *      canvas.commandStack.add(command);
     *      // finally let's execute the command
     *      command.execute();      // this line actually removes the shapes!
     *
     *      // if we want to undo the last command
     *      canvas.commandStack.undo();     // this line recreates the shapes
     *
     *      // if we want to redo the last command
     *      canvas.commandStack.redo();     // this line removes the shapes again
     *
     * @abstract
     * @constructor Creates an instance of the class command
     * @param {Object} receiver The object that will execute the command
     */
    Command = function (receiver) {

        /**
         * The object that executes the command
         * @property {Object}
         */
        this.receiver = receiver;
        /**
         * Reference to the canvas
         * @property {Canvas}
         */
        this.canvas = (!receiver) ? null : receiver.getCanvas();
    };

    /**
     * Family of this command
     * @property {String}
     */
    Command.prototype.family = "Command";

    /**
     * Executes the command
     * @template
     * @protected
     */
    Command.prototype.execute = function (stopTrigger) {
    };

    /**
     * InverseExecutes the command (a.k.a. undo)
     * @template
     * @protected
     */
    Command.prototype.undo = function (stopTrigger) {
    };

    /**
     * Executes the command (a.k.a. redo)
     * @template
     * @protected
     */
    Command.prototype.redo = function (stopTrigger) {
    };

    /**
     * @class CommandStack
     * Command stack stores the commands executed to perform undos and redos, it consists of 2 stacks:
     *
     * - undoStack (represented as an array)
     * - redoStack (represented as an array)
     *
     * Every time an undo or redo action is executed the stacks automatically get updated and the function passed
     * during the instantiation is called.
     *
     *      // i.e.
     *      // let's assume that commandCreateInstance is an instance of CommandCreate
     *      // let's assume that commandResizeInstance is an instance of CommandResize
     *
     *      // first let's create the stacks (max size of the redo stack is 5)
     *      var commandStack = new CommandStack(5);
     *
     *      // commandStack.add() inserts the command to the undoStack (emptying the redo stack too)
     *      commandStack.add(commandCreateInstance);
     *      commandStack.add(commandResizeInstance);
     *      commandStack.add(commandResizeInstance);
     *      commandStack.add(commandResizeInstance);
     *      commandStack.add(commandResizeInstance);
     *
     *      // at this point the redo stack is full (we defined a max size of 5), so the following add will remove the
     *      // last element of the stack (which is commandCreateInstance) and the undoStack will only consist of
     *      // command resize instances
     *      commandStack.add(commandResizeInstance);
     *
     *      // whenever an undo operation is executed in the commandStack, the first command (which is the last command
     *      // in the undoStack) executes its undo operation and the command is removed from the undoStack and pushed to
     *      // the redoStack, graphically:
     *
     *      // Let's define an stack graphically as '[['
     *      // if an element (e1) is pushed to the stack the stack becomes: [[e1
     *      // if an element (e2) is pushed to the stack the stack becomes: [[e1, e2
     *      // if an element (e3) is pushed to the stack the stack becomes: [[e1, e2, e3
     *      // if an element is removed from the stack the stack becomes: [[e1, e2
     *      // Note the direction of the stack, if it's defined as ']]'
     *      // the operations executed above turn the stack into:
     *      // e1]]; e2, e1]]; e3, e2, e1]]; e2, e1]]
     *
     *      // Let's alias commandResizeInstance as cRI and commandCreateInstance cCI.
     *      // With the example defined above of commandResizeInstance, the following line does:
     *      // pre state:
     *      // undoStack = [[cRI_1, cRI_2, cRI_3, cRI_4, cRI_5
     *      // redoStack = ]]
     *      // post state:
     *      // undoStack = [[cRI_1, cRI_2, cRI_3, cRI_4
     *      // redoStack = cRI_5]]
     *      commandStack.undo();
     *
     *      // executing undo again leads to:
     *      // pre state:
     *      // undoStack = [[cRI_1, cRI_2, cRI_3, cRI_4
     *      // redoStack = cRI_5]]
     *      // post state:
     *      // undoStack = [[cRI_1, cRI_2, cRI_3
     *      // redoStack = cRI_4, cRI_5]]
     *      commandStack.undo();
     *
     *      // executing redo leads to:
     *      // pre state:
     *      // undoStack = [[cRI_1, cRI_2, cRI_3
     *      // redoStack = cRI_4, cRI_5]]
     *      // post state:
     *      // undoStack = [[cRI_1, cRI_2, cRI_3, cRI_4
     *      // redoStack = cRI_5]]
     *      commandStack.redo();
     *
     *      // adding a new command to the stack empties the redo stack so:
     *      // pre state:
     *      // undoStack = [[cRI_1, cRI_2, cRI_3, cRI_4
     *      // redoStack = cRI_5]]
     *      // post state:
     *      // undoStack = [[cRI_1, cRI_2, cRI_3, cRI_4, cCI_1
     *      // redoStack = ]]
     *      commandStack.add(commandCreateInstance);
     *
     * @constructor Creates an instance of the class CommandStack
     * @param {number} stackSize The maximum number of operations to be saved
     * @param {Function} successCallback Function to be executed after add, undo or redo,
     * `this` will refer to the object itself, not the constructor
     */
    CommandStack = function (stackSize, successCallback) {

        var undoStack,
            redoStack,
            maxSize;

        /**
         * Stacks that contains commands (when pushed to the undoStack)
         * @property {Array} [undoStack=[]]
         * @private
         */
        undoStack = [];
        /**
         * Stacks that contains commands (when pushed to the redoStack)
         * @property {Array} [redoStack=[]]
         * @private
         */
        redoStack = [];
        /**
         * Maximum size of the undo stack
         * @property {number} [maxSize=20]
         * @private
         */
        maxSize = stackSize || 20;

        /**
         * Empties the redo stack (when a new event is added to the undoStack)
         * @private
         */
        function emptyRedoStack() {
            redoStack = [];
        }

        /**
         * Handler to be called when a special action occurs
         */
        function onSuccess() {
//        // debug
//        console.log("onSuccess was called");
//        console.log(this.getUndoSize() + " " + this.getRedoSize());
        }

        if (successCallback && {}.toString.call(successCallback) === '[object Function]') {
            onSuccess = successCallback;
        }

        return {
            /**
             * Adds an action (command) to the undoStack
             * @param {Command} action
             */
            add: function (action) {
                emptyRedoStack();
                undoStack.push(action);
                if (undoStack.length > maxSize) {
                    // got to the max size of the stack
                    undoStack.shift();
                }
                onSuccess();
            },
            /**
             * Adds an action (command) to the redoStack
             * @param {Command} action
             */
            addToRedo: function (action) {
                redoStack.push(action);
            },
            /**
             * Undoes the last action executing undoStack's first item undo
             * @return {boolean}
             */
            undo: function () {
                var action;     // action to be inverse executed

                if (undoStack.length === 0) {
                    return false;
                }

                action = undoStack.pop();

                // inverse execute the action
                action.undo();

                redoStack.unshift(action);

                // execute on success handler
                onSuccess();
                return true;
            },
            /**
             * Redoes the last action executing redoStack's first item redo
             * @return {boolean}
             */
            redo: function () {
                var action;     // action to be inverse executed

                if (redoStack.length === 0) {
                    return false;
                }

                action = redoStack.shift();

                // execute the action
                action.redo();

                undoStack.push(action);

                // execute on success handler
                onSuccess();
                return true;
            },
            /**
             * Clear both stacks
             */
            clearStack: function () {
                redoStack = [];
                undoStack = [];
            },
            /**
             * Debugging method to show the state of each stack
             * @param {boolean} showDetailed
             */
            debug: function (showDetailed) {
                var i;
                console.log("Debugging command stack:");
                console.log("Undo stack size: " + undoStack.length);
                if (showDetailed) {
                    for (i = 0; i < undoStack.length; i += 1) {
                        console.log((i + 1) + ") " + undoStack[i].type);
                    }
                }
                console.log("Redo stack size: " + redoStack.length);
                if (showDetailed) {
                    for (i = 0; i < redoStack.length; i += 1) {
                        console.log((i + 1) + ") " + redoStack[i].type);
                    }
                }
            },
            /**
             * Gets the size of the redo stack
             * @return {Number}
             */
            getRedoSize: function () {
                return redoStack.length;
            },
            /**
             * Gets the size of the redo stack
             * @return {Number}
             */
            getUndoSize: function () {
                return undoStack.length;
            },
            /**
             * Sets the onSuccess handler of this object
             * @param successCallback
             * @chainable
             */
            setHandler: function (successCallback) {
                if (successCallback && {}.toString.call(successCallback) === '[object Function]') {
                    onSuccess = successCallback;
                }
                return this;
            }
        };
    };

    /**
     * @class CommandResize
     * Class CommandResize determines the actions executed when some shapes are resized (redo) and the actions
     * executed when they're resized back (undo).
     *
     * Instances of this class are created in {@link RegularResizeBehavior#event-resizeEnd}.
     * @extends Command
     *
     * @constructor Creates an instance of the class CommandResize
     * @param {Object} receiver The object that will execute the command
     */
    CommandResize = function (receiver) {
        Command.call(this, receiver);

        /**
         * Object that represents the state of the shape before changing
         * its dimension
         * @property {Object}
         */
        this.before = {
            x: this.receiver.getOldX(),
            y: this.receiver.getOldY(),
            width: this.receiver.getOldWidth(),
            height: this.receiver.getOldHeight()
        };

        /**
         * Object that represents the state of the shape after changing
         * its dimension
         * @property {Object}
         */
        this.after = {
            x: this.receiver.getX(),
            y: this.receiver.getY(),
            width: this.receiver.getWidth(),
            height: this.receiver.getHeight()
        };
    };

    CommandResize.prototype = new Command();

    /**
     * Type of command of this object
     * @property {String}
     */
    CommandResize.prototype.type = "CommandResize";

    /**
     * Executes the command.
     * The steps are:
     *
     * 1. Set the new position and dimension of the shape (using `this.after`)
     * 2. Fix its connections on resize
     * 3. Trigger the dimension change event
     * 4. Trigger the position change event
     *
     * @chainable
     */
    CommandResize.prototype.execute = function () {
        var shape = this.receiver,
            canvas = shape.getCanvas();
        shape.setPosition(this.after.x, this.after.y)
            .setDimension(this.after.width, this.after.height);
        shape.fixConnectionsOnResize(shape.resizing, true);
        canvas.triggerDimensionChangeEvent(shape, this.before.width,
            this.before.height, this.after.width, this.after.height);
        if ((this.after.x !== this.before.x) || (this.after.y !== this.before.y)) {
            canvas.triggerPositionChangeEvent(
                [shape],
                [{
                    x : this.before.x,
                    y : this.before.y
                }],
                [{
                    x : this.after.x,
                    y : this.after.y
                }]
            );
        }
        return this;
    };

    /**
     * Inverse executes a command a.k.a undo.
     * The steps are:
     *
     * 1. Set the new position and dimension of the shape (using `this.before`)
     * 2. Fix its connections on resize
     * 3. Trigger the dimension change event
     * 4. Trigger the position change event
     *
     * @chainable
     */
    CommandResize.prototype.undo = function () {
        var shape = this.receiver,
            canvas = shape.getCanvas();
        shape.setPosition(this.before.x, this.before.y)
            .setDimension(this.before.width, this.before.height);
        shape.fixConnectionsOnResize(shape.resizing, true);
        canvas.triggerDimensionChangeEvent(shape, this.after.width,
            this.after.height, this.before.width, this.before.height);
        if ((this.after.x !== this.before.x) || (this.after.y !== this.before.y)) {
            canvas.triggerPositionChangeEvent(
                [shape],
                [{
                    x : this.after.x,
                    y : this.after.y
                }],
                [{
                    x : this.before.x,
                    y : this.before.y
                }]
            );
        }
        return this;
    };

    /**
     * Executes the command a.k.a redo.
     * @chainable
     */
    CommandResize.prototype.redo = function () {
        this.execute();
        return this;
    };

    /**
     * @class CommandConnect
     * Class CommandConnect determines the actions executed when a connection is created (redo) and the actions
     * executed when it's destroyed (undo).
     *
     * Instances of this class are created in {@link Canvas#removeElements}.
     *
     * @extends Command
     *
     * @constructor Creates an instance of the class CommandConnect.
     * @param {Object} receiver The object that will execute the command
     */
    CommandConnect = function (receiver) {
        Command.call(this, receiver);
    };

    CommandConnect.prototype = new Command();

    /**
     * Type of command
     * @property {String}
     */
    CommandConnect.prototype.type = "CommandConnect";

    /**
     * Build the connection.
     * The steps are:
     *
     * 1. Insert the ports in their respective parents (shapes)
     * 2. Append the html of the ports
     * 3. Add the connection html to the canvas
     * 4. Trigger the create event *
     * @param {Boolean} fromUndo
     * @chainable
     */
    CommandConnect.prototype.buildConnection = function(fromUndo) {
        var connection = this.receiver,
            canvas = connection.canvas,
            srcPort = connection.getSrcPort(),
            destPort = connection.getDestPort();

        // save the ports in its parents' ports array
        srcPort.parent.ports.insert(srcPort);
        destPort.parent.ports.insert(destPort);

        // append the html of the ports to its parents (customShapes)
        srcPort.parent.html.appendChild(srcPort.getHTML());
        destPort.parent.html.appendChild(destPort.getHTML());

        // This tells the addElement action to not redraw connecting lines after
        // placement back on the canvase
        connection.inUndo = fromUndo === true;

        // add the connection to the canvas (its html is appended)
        canvas.addConnection(connection);

        // Undo what was done up above to keep this object clean
        connection.inUndo = !connection.inUndo;
        canvas.updatedElement = connection;
        return connection;
    };
    /**
     * Executes the command.
     * 1. Call buildConnection method
     * 2.  Trigger the create event *
     * @chainable
     */
    CommandConnect.prototype.execute = function () {
        var connection = this.buildConnection();
        connection.canvas.triggerCreateEvent(connection, []);
        return this;
    };

    /**
     * Inverse executes the command a.k.a. undo.
     * The steps are:
     *
     * 1. Save the connection (detach it from the DOM)
     * 2. Trigger the remove event
     *
     * @chainable
     */
    CommandConnect.prototype.undo = function () {
        this.receiver.saveAndDestroy();
        //this.receiver.canvas.triggerRemoveEvent(this.receiver, []);
        //fix triggerRemoveEvent only receive an array of shapes as parameters
        this.receiver.canvas.triggerRemoveEvent(this.receiver, [this.receiver]);
        return this;
    };

    /**
     * Executes the command a.k.a. redo by calling `this.execute`
     * @chainable
     */
    CommandConnect.prototype.redo = function () {
        this.execute();
        return this;
    };

    /**
     * @class CommandReconnect
     * Class CommandReconnect determines the actions executed when a connection is reconnected, i.e. when a connection
     * source port or end port are dragged to another shape or another position in the same shape (redo)
     * and the actions executed to revert the last drag to another shape or another position in the same shape (undo).
     *
     * Instances of this class are created in {@link ConnectionDropBehavior#onDrop}.
     * @extends Command
     *
     * @constructor Creates an instance of the class CommandReconnect
     * @param {Object} receiver The object that will execute the command
     */
    CommandReconnect = function (receiver) {
        Command.call(this, receiver);

        /**
         * Object that represents the state of the shape before changing
         * its dimension
         * @property {Object}
         */
        this.before = {
            x: this.receiver.getOldX(),
            y: this.receiver.getOldY(),
            parent: this.receiver.getOldParent()
        };

        /**
         * Object that represents the state of the shape after changing
         * its dimension
         * @property {Object}
         */
        this.after = {
            x: this.receiver.getX(),
            y: this.receiver.getY(),
            parent: this.receiver.getParent()
        };
    };

    CommandReconnect.prototype = new Command();

    /**
     * Type of command.
     * @property {String}
     */
    CommandReconnect.prototype.type = "CommandReconnect";

    /**
     * Executes the command
     * The steps are:
     *
     * 1. Hide the currentConnection of the canvas if there's one
     * 2. If the new parent of the dragged port is different than the old parent
     *      - Remove the port from its old parent
     *      - Add the port to the new parent
     * 3. If the new parent of the dragged port is equal to the old parent
     *      - Redefine its position in the shape
     * 4. Reconnect the connection (using the new ports) and check for intersections
     * 4. Trigger the port change event
     *
     * @chainable
     */
    CommandReconnect.prototype.execute = function () {

        var port = this.receiver,
            parent = this.after.parent,
            oldParent = this.before.parent;

        // hide the connection if its visible
        if (parent.canvas.currentConnection) {
            parent.canvas.currentConnection.hidePortsAndHandlers();
            parent.canvas.currentConnection = null;
        }

        if (parent.getID() !== oldParent.getID()) {
            oldParent.removePort(port);
//        parent.addPort(port, this.after.x, this.after.y, true);
            port.canvas.regularShapes.insert(port);
        } else {
            parent.definePortPosition(port,
                new Point(this.after.x + Math.round(port.getWidth() / 2),
                    this.after.y + Math.round(port.getHeight()/2)));
        }

        port.connection
            .disconnect()
            .connect()
            .setSegmentMoveHandlers()
            .checkAndCreateIntersectionsWithAll();

        // custom trigger
        this.receiver.canvas.triggerPortChangeEvent(port);
        return this;
    };

    /**
     * Inverse executes a command i.e. undo.
     * The steps are:
     *
     * 1. Hide the currentConnection of the canvas if there's one
     * 2. If the old parent of the port is different than the new parent
     *      - Remove the port from its new parent
     *      - Add the port to the old parent
     * 3. If the old parent of the port is equal to the new parent
     *      - Redefine its position in the shape
     * 4. Reconnect the connection (using the new ports) and check for intersections
     * 4. Trigger the port change event
     *
     * @chainable
     */
    CommandReconnect.prototype.undo = function () {
        var port = this.receiver,
            parent = this.after.parent,
            oldParent = this.before.parent;

        // hide the connection if its visible
        if (parent.canvas.currentConnection) {
            parent.canvas.currentConnection.hidePortsAndHandlers();
            parent.canvas.currentConnection = null;
        }

        if (parent.getID() !== oldParent.getID()) {
            parent.removePort(port);
            oldParent.addPort(port, this.before.x, this.before.y, true);
            port.canvas.regularShapes.insert(port);
        } else {
            parent.definePortPosition(port,
                new Point(this.before.x + Math.round(port.getWidth() / 2),
                    this.before.y + Math.round(port.getHeight()/2)));
        }

        port.connection
            .disconnect()
            .connect()
            .setSegmentMoveHandlers()
            .checkAndCreateIntersectionsWithAll();

        // custom trigger
        this.receiver.canvas.triggerPortChangeEvent(port);
        return this;
    };

    /**
     * Inverse executes a command i.e. undo
     * @chainable
     */
    CommandReconnect.prototype.redo = function () {
        this.execute();
        return this;
    };

    /**
     * @class CommandSegmentMove
     * Class CommandSegmentMove determines the actions executed when a segment is moved through its move handler (redo)
     * and the actions executed when the segment is moved back (undo).
     *
     * Instances of this class are created in {@link SegmentMoveHandler#event-dragEnd}.
     * @extends Command
     *
     * @constructor Creates an instance of the class CommandSegmentMove
     * @param {Connection} receiver The object that will execute the command
     * @param {Object} options Initialization options
     * @cfg {Array} [oldPoints=[]] Array of old points of the connection
     * @cfg {Array} [newPoints=[]] Array of new points of the connection
     */
    CommandSegmentMove = function (receiver, options) {
        Command.call(this, receiver);

        /**
         * Array of points that represent the state of the connection before moving the segment move handler
         * @property {Array}
         */
        this.oldPoints = [];

        /**
         *  Array of points that represent the state of the connection after moving the segment move handler
         * @property {Array}
         */
        this.newPoints = [];

        CommandSegmentMove.prototype.initObject.call(this, options);
    };

    CommandSegmentMove.prototype = new Command();

    /**
     * Type of command of this object
     * @property {String}
     */
    CommandSegmentMove.prototype.type = "CommandResize";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} options The object that contains old points and new points
     * @private
     */
    CommandSegmentMove.prototype.initObject = function (options) {
        var defaults = {
                oldPoints: [],
                newPoints: []
            },
            i,
            point;
        $.extend(true, defaults, options);
        this.oldPoints = [];
        for (i = 0; i < defaults.oldPoints.length; i += 1) {
            point = defaults.oldPoints[i];
            this.oldPoints.push(new Point(point.x, point.y));
        }
        this.newPoints = [];
        for (i = 0; i < defaults.newPoints.length; i += 1) {
            point = defaults.newPoints[i];
            this.newPoints.push(new Point(point.x, point.y));
        }
    };

    /**
     * There's a common behavior between execute and inverseExecute in
     * this command so merge both files and use a parameter to choose
     * between execute and inverseExecute.
     * The steps are:
     *
     * 1. Select the connection by triggering a click in its destination decorator
     * 2. Hide the ports and handlers of the connection and reconnect the connection using points
     * 3. Check and create the intersections and show the ports of the connection
     * 4. Trigger the segment move event
     *
     * @private
     * @chainable
     */
    CommandSegmentMove.prototype.common = function (action) {
        var connection = this.receiver;
        // trigger targetSpriteDecorator onClick
        $(connection.destDecorator.getHTML()).trigger('click');

        connection.hidePortsAndHandlers();
        connection.disconnect(true).connect({
            algorithm: 'user',
            points: this[action]
        });

        // delete and create handlers to avoid
        // the creation of two handlers in
        // connection.checkAndCreateIntersectionsWithAll()
        connection.setSegmentMoveHandlers();

        // create intersections with all other connections
        connection.checkAndCreateIntersectionsWithAll();

        // show the ports and handlers again
        connection.showPortsAndHandlers();

        // trigger event
        connection.canvas.triggerConnectionStateChangeEvent(connection);
        return this;
    };

    /**
     * Executes a command
     * @chainable
     */
    CommandSegmentMove.prototype.execute = function () {
        this.common("newPoints");
        return this;
    };

    /**
     * Inverse executes the command a.k.a. undo
     * @chainable
     */
    CommandSegmentMove.prototype.undo = function () {
        this.common("oldPoints");
        return this;
    };

    /**
     * Executes the command a.k.a. redo
     * @chainable
     */
    CommandSegmentMove.prototype.redo = function () {
        this.execute();
        return this;
    };

    /**
     * @class CommandMove
     * Encapsulates the action of moving an element
     *
     *              //i.e.
     *              var command = new CommandMove(shape);
     * @extends Command
     *
     * @constructor
     * Creates an instance of CommandMove
     * @param {Object} receiver The object that will perform the action
     */
    CommandMove = function (receiver) {
        Command.call(this, receiver);
        this.before = null;
        this.after = null;
        this.relatedShapes = [];
        CommandMove.prototype.initObject.call(this, receiver);
    };

    CommandMove.prototype = new Command();
    /**
     * Type of the instances of this class
     * @property {String}
     */
    CommandMove.prototype.type = "CommandMove";

    /**
     * Initializes the command parameters
     * @param {JCoreObject} receiver The object that will perform the action
     */
    CommandMove.prototype.initObject = function (receiver) {
        var i,
            beforeShapes = [],
            afterShapes = [];
        for (i = 0; i < receiver.getSize(); i += 1) {
            this.relatedShapes.push(receiver.get(i));
            beforeShapes.push({
                x : receiver.get(i).getOldX(),
                y : receiver.get(i).getOldY()
            });
            afterShapes.push({
                x : receiver.get(i).getX(),
                y : receiver.get(i).getY()
            });
        }
        this.before = {
            shapes : beforeShapes
        };
        this.after = {
            shapes : afterShapes
        };
        //first time do not has been dragged
        this.isDragged =  true;
    };

    /**
     * Executes the command, changes the position of the element, and if necessary
     * updates the position of its children, and refreshes all connections
     */
    CommandMove.prototype.execute = function () {
        var i,
            shape;

        for (i = 0; i < this.relatedShapes.length; i += 1) {
            shape = this.relatedShapes[i];
            if (!this.isDragged) {
                shape.setPosition(this.after.shapes[i].x, this.after.shapes[i].y);
            }
            shape.refreshChildrenPositions(true);
            shape.refreshConnections(false, this.isDragged);

        }
        this.isDragged =  false;
        this.canvas.triggerPositionChangeEvent(this.relatedShapes,
            this.before.shapes, this.after.shapes);
    };

    /**
     * Returns to the state before the command was executed
     */
    CommandMove.prototype.undo = function () {
        var i,
            shape;
        for (i = 0; i < this.relatedShapes.length; i += 1) {
            shape = this.relatedShapes[i];
            shape.setPosition(this.before.shapes[i].x, this.before.shapes[i].y)
                .refreshChildrenPositions(true);
            shape.refreshConnections(false, this.isDragged);
        }
        this.canvas.triggerPositionChangeEvent(this.relatedShapes,
            this.after.shapes, this.before.shapes);
    };

    /**
     *  Executes the command again after an undo action has been done
     */
    CommandMove.prototype.redo = function () {
        this.execute();
    };

    /**
     * @class CommandCreate
     * Class CommandCreate determines the actions executed when some shapes are created (redo) and the actions
     * executed when they're destroyed (undo).
     *
     * Instances of this class are created in {@link ConnectionDropBehavior#onDrop}.
     * @extends Command
     *
     * @constructor Creates an instance of the class CommandCreate
     * @param {Object} receiver The object that will execute the command
     *
     */
    CommandCreate = function (receiver) {
        Command.call(this, receiver);

        /**
         * Object that represents the state of the receiver before
         * it was created
         * @property {Object}
         */
        this.before = null;

        /**
         * Object that represents the state of the receiver after
         * it was created
         * @property {Object}
         */
        this.after = null;

        CommandCreate.prototype.initObject.call(this, receiver);
    };

    CommandCreate.prototype = new Command();

    /**
     * Type of command
     * @property {String}
     */
    CommandCreate.prototype.type = "CommandCreate";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} receiver
     * @private
     */
    CommandCreate.prototype.initObject = function (receiver) {
        this.before = {
        };
        this.after = {
            x: receiver.getX(),
            y: receiver.getY(),
            parent: receiver.getParent()
        };
    };

    /**
     * Executes the command.
     * The steps are:
     *
     * 1. Insert the current shape to the children of its parent if it's possible
     * 2. Append it to the HTML of its parent
     * 3. Add the shape to either `canvas.customShapes` or `canvas.regularShapes`
     * 4. Trigger the create event
     *
     * @chainable
     */
    CommandCreate.prototype.execute = function () {

        // execute the trigger
        var shape = this.receiver,
            parent = shape.parent;

        // append the html to its parent
        // NOTE: in the first execution (in containerDropBehavior) the html is
        // already in the parent so the following line appends it again (html
        // is not created)

        // note that during the execution of this command the next line may called twice (one in
        // RegularContainerBehavior.addToContainer and the other here) so check if it's not
        // already in its children
        if (!parent.getChildren().contains(shape)) {
            parent.getChildren().insert(shape);
        }
        this.after.parent.html.appendChild(shape.getHTML());
        shape.canvas.addToList(shape);
        shape.showOrHideResizeHandlers(false);
        shape.canvas.triggerCreateEvent(shape, []);
        return this;
    };

    /**
     * Inverse executes the command a.k.a. undo
     *
     * The steps are:
     *
     * 1. Remove the current shape from the children of its parent if it's possible
     * 2. Remove its HTML (detach it from the DOM)
     * 4. Trigger the remove event
     *
     * @chainable
     */
    CommandCreate.prototype.undo = function () {
        this.receiver.parent.getChildren().remove(this.receiver);
        this.receiver.saveAndDestroy();
        //this.receiver.canvas.triggerRemoveEvent(this.receiver, []);
        //fix triggerRemoveEvent only receive an array of shapes as parameters
        this.receiver.canvas.triggerRemoveEvent(this.receiver, [this.receiver]);
        return this;
    };

    /**
     * Executes the command a.k.a redo
     * @chainable
     */
    CommandCreate.prototype.redo = function () {
        this.execute();
        return this;
    };

    /**
     * @class CommandSwitchContainer
     * Class that encapsulates the action of switching containers
     *
     *              //i.e.
     *              var command = new CommandSwitchContainer(arrayOfShapes);
     * @extends Command
     *
     * @constructor
     * Creates an instance of this command
     * @param {Array} shapesAdded array of shapes that are going to switch container
     */
    CommandSwitchContainer = function (shapesAdded) {
        Command.call(this, shapesAdded[0].shape);
        /**
         * Properties of the object before the command is executed
         * @property {Object}
         */
        this.before = null;
        /**
         * Properties of the object after the command is executed
         * @property {Object}
         */
        this.after = null;
        /**
         * Reference to all objects involved in this command
         * @type {Array}
         */
        this.relatedShapes = [];
        CommandSwitchContainer.prototype.initObject.call(this, shapesAdded);
    };

    CommandSwitchContainer.prototype = new Command();

    /**
     * Type of the instances of this command
     * @property {String}
     */
    CommandSwitchContainer.prototype.type = "CommandSwitchContainer";

    /**
     * Initializer of the command
     * @param {Array} shapesAdded array of shapes that are going to switch container
     */
    CommandSwitchContainer.prototype.initObject = function (shapesAdded) {
        var i,
            shape,
            beforeShapes = [],
            afterShapes = [];

        for (i = 0; i < shapesAdded.length; i += 1) {
            shape = shapesAdded[i];
            this.relatedShapes.push(shape.shape);
            beforeShapes.push({
                parent : shape.shape.parent,
                x : shape.shape.getOldX(),
                y : shape.shape.getOldY(),
                topLeft: true
            });
            afterShapes.push({
                parent : shape.container,
                x : shape.x,
                y : shape.y,
                topLeft : shape.topLeft
            });
        }

        this.before = {
            shapes : beforeShapes
        };

        this.after = {
            shapes : afterShapes
        };
    };

    /**
     * The command execution implementation, updates the parents, and if necessary,
     * updates the children positions and connections.
     */
    CommandSwitchContainer.prototype.execute = function () {
        var i,
            shape;
        for (i = 0; i < this.relatedShapes.length;  i += 1) {
            shape = this.relatedShapes[i];
            this.before.shapes[i].parent.swapElementContainer(
                shape,
                this.after.shapes[i].parent,
                this.after.shapes[i].x,
                this.after.shapes[i].y,
                this.after.shapes[i].topLeft
            );
            shape.refreshChildrenPositions()
                .refreshConnections();
        }
        this.canvas.triggerParentChangeEvent(this.relatedShapes,
            this.before.shapes, this.after.shapes);
    };

    /**
     * Returns to the state before this command was executed
     */
    CommandSwitchContainer.prototype.undo = function () {
        var i,
            shape;
        for (i = 0; i < this.relatedShapes.length;  i += 1) {
            shape = this.relatedShapes[i];
            this.before.shapes[i].parent.swapElementContainer(
                shape,
                this.before.shapes[i].parent,
                this.before.shapes[i].x,
                this.before.shapes[i].y,
                this.before.shapes[i].topLeft
            );
            shape.refreshChildrenPositions()
                .refreshConnections();
        }
        this.canvas.triggerParentChangeEvent(this.relatedShapes,
            this.after.shapes, this.before.shapes);
    };

    /**
     *  Executes the command again after an undo action has been done
     */
    CommandSwitchContainer.prototype.redo = function () {
        this.execute();
    };

    /**
     * @class CommandDelete
     * Class CommandDelete determines the actions executed when some shapes are deleted (redo) and the actions
     * executed when they're recreated (undo).
     *
     * Instances of this class are created in {@link Canvas#removeElements}.
     * @extends Command
     *
     * @constructor Creates an instance of the class CommandDelete
     * @param {Object} receiver The object that will execute the command
     */
    CommandDelete = function (receiver) {
        Command.call(this, receiver);

        /**
         * A stack of commandsConnect
         * @property {Array}
         */
        this.stackCommandConnect = [];

        /**
         * ArrayList that represents the selection that was active before deleting the elements
         * @property {ArrayList}
         */
        this.currentSelection = new ArrayList();

        /**
         * Reference to the current connection in the canvas
         * @property {Connection}
         */
        this.currentConnection = null;

        /**
         * List of all the elements related to the commands
         * @property {Array}
         */
        this.relatedElements = [];

        CommandDelete.prototype.initObject.call(this, receiver);
    };

    CommandDelete.prototype = new Command();

    /**
     * Type of command
     * @property {String}
     */
    CommandDelete.prototype.type = "CommandDelete";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} receiver The object that will execute the command
     * @private
     */
    CommandDelete.prototype.initObject = function (receiver) {
        var i,
            shape;

        // move the current selection to this.currentSelection array
        for (i = 0; i < receiver.getCurrentSelection().getSize() > 0; i += 1) {
            shape = receiver.getCurrentSelection().get(i);
            this.currentSelection.insert(shape);
        }

        // save the currentConnection of the canvas if possible
        if (receiver.currentConnection) {
            this.currentConnection = receiver.currentConnection;
        }

    };
    /**
     * Saves and destroys connections and shapes
     * @private
     * @param {Object} shape
     * @param {boolean} root True if `shape` is a root element in the tree
     * @param {boolean} [fillArray] If set to true it'll fill `this.relatedElements` with the objects erased
     * @return {boolean}
     */
    CommandDelete.prototype.saveAndDestroy = function (shape, root, fillArray) {
        var i,
            child,
            parent,
            children = null,
            connection,
            canvas = shape.canvas;

        if (shape.hasOwnProperty("children")) {
            children = shape.children;
        }

        // special function to be called as an afterwards
        // BIG NOTE: doesn't have to delete html
        if (shape.destroy) {
            shape.destroy();
        }

        for (i = 0; i < children.getSize(); i += 1) {
            child = children.get(i);
            this.saveAndDestroy(child, false, fillArray);
        }

        while (shape.ports && shape.ports.getSize() > 0) {
            connection = shape.ports.getFirst().connection;
            if (fillArray) {
                this.relatedElements.push(connection);
            }

            this.stackCommandConnect.push(
                new CommandConnect(connection)
            );
            connection.saveAndDestroy();
        }

        // remove from the children array of its parent
        if (root) {
            parent = shape.parent;
            parent.getChildren().remove(shape);
            if (parent.isResizable()) {
                parent.resizeBehavior.updateResizeMinimums(shape.parent);
            }

            // remove from the currentSelection and from either the customShapes
            // arrayList or the regularShapes arrayList
            //canvas.removeFromList(shape);

            // remove the html only from the root
            shape.html = $(shape.html).detach()[0];
        }
        if (fillArray) {
            this.relatedElements.push(shape);
        }
        // remove from the currentSelection and from either the customShapes
        // arrayList or the regularShapes arrayList
        canvas.removeFromList(shape);
        return true;
    };

    /**
     * Executes the command
     * The steps are:
     *
     * 1. Retrieve the old currentSelection (saved in `this.initObject()`)
     * 2. Remove the shapes (detaching them from the DOM)
     * 3. Remove the currentConnection if there's one
     * 4. Trigger the remove event
     *
     * @chainable
     */
    CommandDelete.prototype.execute = function () {
        var shape,
            i,
            canvas = this.receiver,
            currentConnection,
            stringified,
            fillArray = false,
            mainShape = null;
        if (this.relatedElements.length === 0) {
            fillArray = true;
        }

        canvas.emptyCurrentSelection();

        // copy from this.currentConnection
        for (i = 0; i < this.currentSelection.getSize(); i += 1) {
            shape = this.currentSelection.get(i);
            canvas.addToSelection(shape);
        }
        if (canvas.currentSelection.getSize() === 1) {
            mainShape = shape;
        }
        // remove the elements in the canvas current selection

        stringified = [];
        while (canvas.getCurrentSelection().getSize() > 0) {
            shape = canvas.getCurrentSelection().getFirst();


//        // TESTING JSON
//        canvas.stringifyTest(JSON.stringify(
//            shape.stringify()
//        ));

            this.saveAndDestroy(shape, true, fillArray);

            stringified.push(shape.stringify());
//        this.saveAndDestroy(shape, true);

        }
//    // TESTING JSON
//    canvas.stringifyTest(JSON.stringify(
//        stringified
//    ));

        // destroy the currentConnection
        canvas.currentConnection = this.currentConnection;
        currentConnection = canvas.currentConnection;
        if (currentConnection) {

//        // TESTING JSON
//        canvas.stringifyTest(JSON.stringify(
//            this.currentConnection.stringify()
//        ));

            // add to relatedElements just in the case when only a connection is
            // selected and deleted
            this.relatedElements.push(currentConnection);

            this.stackCommandConnect.push(
                new CommandConnect(currentConnection)
            );

            currentConnection.saveAndDestroy();
            currentConnection = null;
        }
        canvas.triggerRemoveEvent(mainShape, this.relatedElements);
        return this;
    };

    /**
     * Inverse executes the command a.k.a. undo
     *
     * The steps are:
     *
     * 1. Retrieve the old currentSelection (saved in `this.initObject()`)
     * 2. Restore the shapes (attaching them to the DOM)
     * 3. Restore the currentConnection if there was one
     * 4. Trigger the create event
     *
     * @chainable
     */
    CommandDelete.prototype.undo = function () {
        // undo recreates the shapes
        var i,
            shape,
        //mainShape = this.receiver.currentSelection.getFirst();
            mainShape = this.currentSelection.getFirst();

        for (i = 0; i < this.currentSelection.getSize(); i += 1) {
            shape = this.currentSelection.get(i);

            // add to the canvas array of regularShapes and customShapes
            shape.canvas.addToList(shape);

            // add to the children of the parent
            shape.parent.getChildren().insert(shape);
            shape.parent.html.appendChild(shape.getHTML());
            ResizeBehavior.prototype.updateResizeMinimums(shape.parent);

            shape.showOrHideResizeHandlers(false);
        }
        // reconnect using the stack of commandConnect
        for (i = this.stackCommandConnect.length - 1; i >= 0; i -= 1) {
            this.stackCommandConnect[i].buildConnection(true);
        }

        this.receiver.triggerCreateEvent(mainShape, this.relatedElements);
        return this;
    };

    /**
     * Executes the command (a.k.a redo)
     * @chainable
     */
    CommandDelete.prototype.redo = function () {
        this.execute();
        return this;
    };

    /**
     * @class CommandPaste
     * Class CommandPaste determines the actions executed when some shapes are pasted (redo) and the actions
     * executed when they're removed (undo).
     *
     * Instances of this class are created in {@link Canvas#paste}.
     * @extends Command
     *
     * @constructor Creates an instance of the class CommandPaste
     * @param {Object} receiver The object that will execute the command
     * @param {Object} options Initialization options
     * @cfg {Array} [stackCommandConnect=[]] Array of commands connect
     * @cfg {Array} [stackCommandCreate=[]] Array of commands create
     */
    CommandPaste = function (receiver, options) {

        Command.call(this, receiver);

        /**
         * A stack of commandsConnect (for connections)
         * @property {Array}
         */
        this.stackCommandConnect = [];

        /**
         * A stack of commandsCreate (for shapes)
         * @property {Array}
         */
        this.stackCommandCreate = [];

        CommandPaste.prototype.initObject.call(this, receiver, options);
    };

    CommandPaste.prototype = new Command();

    /**
     * Type of command
     * @property {String}
     */
    CommandPaste.prototype.type = "CommandPaste";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} receiver The object that will execute the command
     * @private
     */
    CommandPaste.prototype.initObject = function (receiver, options) {
        var i,
            shape,
            defaults = {
                stackCommandConnect: [],
                stackCommandCreate: []
            };

        $.extend(true, defaults, options);

        this.stackCommandConnect = defaults.stackCommandConnect;
        this.stackCommandCreate = defaults.stackCommandCreate;

    };

    /**
     * Executes the command.
     * The steps are:
     *
     * 1. Execute the redo operation for each command create
     * 2. Execute the redo operation for each command connect
     *
     * @chainable
     */
    CommandPaste.prototype.execute = function () {
        var i,
            command;
        for (i = 0; i < this.stackCommandCreate.length; i += 1) {
            command = this.stackCommandCreate[i];
            command.redo();
        }
        for (i = 0; i < this.stackCommandConnect.length; i += 1) {
            command = this.stackCommandConnect[i];
            command.redo();
        }
        return this;
    };

    /**
     * Inverse executes the command a.k.a. undo.
     * The steps are:
     *
     * 1. Execute the undo operation for each command create
     * 2. Execute the undo operation for each command connect
     *
     * @chainable
     */
    CommandPaste.prototype.undo = function () {
        var i,
            command;
        for (i = 0; i < this.stackCommandCreate.length; i += 1) {
            command = this.stackCommandCreate[i];
            command.undo();
        }
        for (i = 0; i < this.stackCommandConnect.length; i += 1) {
            command = this.stackCommandConnect[i];
            command.undo();
        }
        return this;
    };

    /**
     * Executes the command a.k.a redo
     * @chainable
     */
    CommandPaste.prototype.redo = function () {
        this.execute();
        return this;
    };

    /**
     * @class CommandEditLabel
     * Encapsulates the action of editing a label
     *
     *                  //i.e.
     *                  // var command = new CommandEditLabel(label, "new message");
     * @extends Command
     *
     * @constructor
     * Creates an instance of this command
     * @param {Label} receiver The object that will perform the action
     * @param {String} newMessage
     */
    CommandEditLabel = function (receiver, newMessage) {
        Command.call(this, receiver);
        this.before = null;
        this.after = null;
        CommandEditLabel.prototype.initObject.call(this, receiver, newMessage);
    };

    CommandEditLabel.prototype = new Command();
    /**
     * Type of the instances
     * @property {String}
     */
    CommandEditLabel.prototype.type = "CommandEditLabel";
    /**
     * Initializes the command
     * @param {Label} receiver The object that will perform the action
     * @param {String} newMessage
     */
    CommandEditLabel.prototype.initObject = function (receiver, newMessage) {
        var parentHeight = 0,
            parentWidth = 0;
        if (receiver.parent) {
            parentHeight = receiver.parent.height;
            parentWidth = receiver.parent.width;
        }
        this.before = {
            message : receiver.message,
            width : receiver.width,
            height : receiver.height,
            parentHeight : parentHeight,
            parentWidth : parentWidth
        };
        this.after = {
            message : newMessage,
            width : 0,
            height : 0,
            parentHeight: parentWidth,
            parentWidth: parentHeight
        };
    };
    /**
     * Executes the command, sets the new message updates the dimensions and its,
     * parent if necessary
     */
    CommandEditLabel.prototype.execute = function (stopTrigger) {
        this.receiver.setMessage(this.after.message);
        this.receiver.updateDimension();
        if (this.after.width === 0) {
            this.after.width = this.receiver.width;
            this.after.height = this.receiver.height;
            if (this.after.parentWidth !== 0) {
                this.after.parentWidth = this.receiver.parent.width;
                this.after.parentHeight = this.receiver.parent.height;
            }
        }
        this.receiver.paint();
        if (!stopTrigger) {
            this.receiver.canvas.triggerTextChangeEvent(this.receiver,
                this.before.message, this.after.message);
            if ((this.after.parentWidth !== this.before.parentWidth) ||
                (this.before.parentHeight !== this.after.parentHeight)) {
                this.receiver.canvas.triggerDimensionChangeEvent
                (
                    this.receiver.parent,
                    this.before.parentWidth,
                    this.before.parentHeight,
                    this.after.parentWidth,
                    this.after.parentHeight
                );
            }
        }
    };
    /**
     * Returns to the previous state before executing the command
     */
    CommandEditLabel.prototype.undo = function (stopTrigger) {
        this.receiver.setMessage(this.before.message);

        if (this.receiver.parent) {
            this.receiver.parent.setDimension(this.before.parentWidth,
                this.before.parentHeight);
        }
        this.receiver.setDimension(this.before.width, this.before.height);
        this.receiver.updateDimension();
        this.receiver.paint();
        this.receiver.canvas.triggerTextChangeEvent(this.receiver,
            this.after.message, this.before.message);
        if ((this.after.parentWidth !== this.before.parentWidth) &&
            (this.before.parentHeight !== this.after.parentHeight)) {
            this.receiver.canvas.triggerDimensionChangeEvent(this.receiver.parent,
                this.after.parentWidth, this.after.parentHeight,
                this.before.parentWidth, this.before.parentHeight);
        }
    };
    /**
     * Executes the command again after an undo action has been done
     */
    CommandEditLabel.prototype.redo = function () {
        this.execute();
    };

    /**
     * @abstract
     * @class ContainerBehavior
     * Object that encapsulates the container of shapes, this is an abstract class,
     * so all its methods should be implemented by its subclasses

     * @constructor
     * Creates a new instance of the class
     */
    ContainerBehavior  = function () {
    };
    /**
     * Type of the instances
     * @property {String}
     */
    ContainerBehavior.prototype.type = "ContainerBehavior";
    /**
     * Family of the instances
     * @property {String}
     */
    ContainerBehavior.prototype.family = "ContainerBehavior";
    /**
     * @abstract
     * Sets a shape's container to a given container
     * @param {BehavioralElement} container element using this behavior
     * @param {Shape} shape shape to be added
     * @template
     * @protected
     */
    ContainerBehavior.prototype.addToContainer = function (container, shape, x, y,
                                                           topLeftCorner) {
    };
    /**
     * @abstract
     * Removes shape from its current container
     * @param {Shape} shape shape to be removed
     * @template
     * @protected
     */
    ContainerBehavior.prototype.removeFromContainer = function (shape) {
    };
    /**
     * @abstract
     * Adds a shape to a given container
     * @param {BehavioralElement} container container element using this behavior
     * @param {Shape} shape shape to be added to the container
     * @template
     * @protected
     */
    ContainerBehavior.prototype.addShape = function (container, shape, x, y) {
    };
    /**
     * Returns whether a shape is a container or not
     * @return {boolean}
     */
    ContainerBehavior.prototype.isContainer = function () {
        return false;
    };


    /**
     * @class RegularContainerBehavior
     * Encapsulates the behavior of a regular container
     * @extends ContainerBehavior
     *
     * @constructor
     * Creates a new instance of the class
     */

    RegularContainerBehavior = function () {
    };

    RegularContainerBehavior.prototype = new ContainerBehavior();
    /**
     * Type of the instances
     * @property {String}
     */
    RegularContainerBehavior.prototype.type = "RegularContainerBehavior";
    /**
     * Adds a shape to a given container given its coordinates
     * @param {BehavioralElement} container container using this behavior
     * @param {Shape} shape shape to be added
     * @param {number} x x coordinate where the shape will be added
     * @param {number} y y coordinate where the shape will be added
     * @param {boolean} topLeftCorner Determines whether the x and y coordinates
     * will be considered from the top left corner or from the center
     */
    RegularContainerBehavior.prototype.addToContainer = function (container,
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

        shapeLeft /= canvas.zoomFactor;
        shapeTop /= canvas.zoomFactor;

        shape.setParent(container);
        container.getChildren().insert(shape);
        this.addShape(container, shape, shapeLeft, shapeTop);

        // fix the zIndex of this shape and it's children
        shape.fixZIndex(shape, 0);

        // fix resize minWidth and minHeight and also fix the dimension
        // of this shape (if a child made it grow)
        container.updateDimensions(10);

        // adds the shape to either the customShape arrayList or the regularShapes
        // arrayList if possible
        canvas.addToList(shape);
    };
    /**
     * Removes a shape from the container implementing this behavior
     * @param {Shape} shape shape to be removed
     */
    RegularContainerBehavior.prototype.removeFromContainer = function (shape) {
        var parent = shape.parent;
        parent.getChildren().remove(shape);
        if (parent.isResizable()) {
            parent.resizeBehavior.updateResizeMinimums(shape.parent);
        }
        shape.parent = null;
    };
    /**
     * Sets the position of the shape, and append its html
     * @param {BehavioralElement} container element implementing this behavior
     * @param {Shape} shape shape added to the container
     * @param {number} x x coordinate of the position that will be set relative to
     * the container
     * @param {number} y y coordinate of the position that will be set relative to
     * the container
     * @chainable
     */
    RegularContainerBehavior.prototype.addShape = function (container, shape, x,
                                                            y) {
        shape.setPosition(x, y);
        //insert the shape HTML to the DOM
        //console.log(container.html);
        //console.log(shape.getHTML());
        container.getHTML().appendChild(shape.getHTML());

        shape.paint();
        shape.updateHTML();
        return this;

    };


    /**
     * @class NoContainerBehavior
     * Encapsulates the behavior of elements that has no container behavior, useful
     * for implementing the strategy pattern
     * @extends ContainerBehavior
     *
     *
     * @constructor
     * Creates a new instance of the class
     */
    NoContainerBehavior = function () {
    };

    NoContainerBehavior.prototype = new ContainerBehavior();
    /**
     * Type of the instances
     * @property {String}
     */
    NoContainerBehavior.prototype.type = "NoContainerBehavior";

    /**
     * @abstract
     * @class DragBehavior
     * Abstract class that encapsulates the drag behavior of an object
     *
     * @constructor Creates a new instance of the class
     *
     */
    DragBehavior = function () {
    };

    /**
     * Type of the object
     * @property {String}
     */
    DragBehavior.prototype.type = "DragBehavior";
    /**
     * Family of the object
     * @property {String}
     */
    DragBehavior.prototype.family = "DragBehavior";


    /**
     * Attach the drag listener and its corresponding ui properties to the shape
     * @param {Shape} shape
     */
    DragBehavior.prototype.attachDragBehavior = function (shape) {
        var dragOptions,
            $shape = $(shape.getHTML());
        dragOptions = {
            revert : false,
            helper : "none",
            cursorAt : false,
            revertDuration : 0,
            grid : [1, 1],
            start : this.onDragStart(shape),
            drag : this.onDrag(shape),
            stop : this.onDragEnd(shape)
        };
        $shape.draggable(dragOptions);
    };
    /**
     * @event dragStart
     * @abstract drag start handler, function that runs when the drag start event occurs,
     * it should return a function so that any implementation should go inside the
     * return
     * @param {Shape} shape current shape being dragged
     * @template
     * @protected
     */
    DragBehavior.prototype.onDragStart = function (shape) {
        return function (e, ui) {
        };
    };

    /**
     * @event drag
     * Drag handler, function that runs when dragging is occurring,
     * it should return a function so that any implementation should go inside the
     * return
     * @param {Shape} shape shape being dragged
     * @template
     * @protected
     */
    DragBehavior.prototype.onDrag = function (shape) {
        return function (e, ui) {
        };
    };

    /**
     * @event dragEnd
     * Drag end handler, function that runs when the drag end event occurs,
     * it should return a function so that any implementation should go inside the
     * return
     * @param {Shape} shape
     * @template
     * @protected
     */
    DragBehavior.prototype.onDragEnd = function (shape) {
        return function (e, ui) {
        };
    };
    /**
     * @abstract Executes the hook Function for the drag start event
     * @template
     * @protected
     */
    DragBehavior.prototype.dragStartHook = function (hookFunction) {
    };

    /**
     * @abstract Executes the hook function for the drag event
     * @template
     * @protected
     */
    DragBehavior.prototype.dragHook = function (hookFunction) {
    };
    /**
     * @abstract Executes the hook function for the drag end event
     * @template
     * @protected
     */
    DragBehavior.prototype.dragEndHook = function () {
    };

    /**
     * @class RegularDragBehavior
     * Class that encapsulates the regular drag behavior of a shape
     * @extends DragBehavior
     *
     * @constructor Creates a new instance of the class
     *
     */
    RegularDragBehavior = function () {
    };

    RegularDragBehavior.prototype = new DragBehavior();
    /**
     * Type of the object
     * @property {String}
     */
    RegularDragBehavior.prototype.type = "RegularDragBehavior";
    /**
     * Attach the drag behavior to a given shape
     * @param {Shape} shape
     */
    RegularDragBehavior.prototype.attachDragBehavior = function (shape) {
        var $shape = $(shape.getHTML());
        DragBehavior.prototype.attachDragBehavior.call(this, shape);
        $shape.draggable({'cursor' : "move"});
    };
    /**
     * On drag start handler, initializes everything that is needed for a shape to
     * be dragged
     * @param {Shape} shape
     * @return {Function}
     */
    RegularDragBehavior.prototype.onDragStart = function (shape) {
        return function (e, ui) {
            var canvas = shape.canvas,
                currentLabel = canvas.currentLabel,
                selectedShape,
                i;

            // hide the current connection if there was one
            canvas.hideCurrentConnection();
            if (currentLabel) {
                currentLabel.loseFocus();
                $(currentLabel.textField).focusout();
            }

            if (!canvas.currentSelection.contains(shape)) {
                canvas.emptyCurrentSelection();     /* ALSO DECREASES THE Z-INDEX */
                canvas.addToSelection(shape);
            }

            // added by mauricio
            // these lines must be here and not in the top (currentSelection
            // is updated in the if above)
            for (i = 0; i < canvas.currentSelection.getSize(); i += 1) {
                selectedShape = canvas.currentSelection.get(i);
                selectedShape.setOldX(selectedShape.getX());
                selectedShape.setOldY(selectedShape.getY());
                selectedShape.setOldParent(selectedShape.getParent());
            }

            // increase shape's ancestors zIndex
            shape.increaseParentZIndex(shape.getParent());
            return true;
        };
    };
    /**
     * On drag handler, sets the position of the shape to current position of the
     * shape in the screen
     * @param {Shape} shape
     * @return {Function}
     */
    RegularDragBehavior.prototype.onDrag = function (shape) {
        return function (e, ui) {

            shape.setPosition(ui.helper.position().left,
                ui.helper.position().top);

            // show or hide the snappers
            shape.canvas.showOrHideSnappers(shape);
        };
    };
    /**
     * On drag end handler, set the final position of the shape and fires the
     * command move
     * @param {Shape} shape
     * @return {Function}
     */
    RegularDragBehavior.prototype.onDragEnd = function (shape) {
        return function (e, ui) {
            var command;
//        var currentSelection = shape.getCanvas().getCurrentSelection();
            shape.setPosition(ui.helper.position().left,
                ui.helper.position().top);

            // decrease the zIndex of the oldParent of this shape
            shape.decreaseParentZIndex(shape.oldParent);

            shape.dragging = false;

            // hide the snappers
            shape.canvas.verticalSnapper.hide();
            shape.canvas.horizontalSnapper.hide();
            if (!shape.changedContainer) {
                command = new CommandMove(shape);
                command.execute();
                shape.canvas.commandStack.add(command);
            }
            shape.changedContainer = false;
            // update current selection zIndex
//        for (i = 0; i < currentSelection.getSize(); i += 1) {
//            shape = currentSelection.get(i);
//            shape.increaseZIndex();
//        }
        };
    };

    /**
     * @class NoDragBehavior
     * Class that encapsulates the drag behavior corresponding to the elements that
     * cannot be dragged
     * @extends DragBehavior
     *
     * @constructor Creates a new instance of the class
     *
     */
    NoDragBehavior = function () {
    };

    NoDragBehavior.prototype = new DragBehavior();
    /**
     * Type of the instances
     * @property {String}
     */
    NoDragBehavior.prototype.type = "NoDragBehavior";

    /**
     * On drag start handler, this method prevents drag from occurring
     * @param {Shape} shape
     * @return {Function}
     */
    NoDragBehavior.prototype.onDragStart = function (shape) {
        // hide the current connection if there was one
        return function (e, ui) {
            shape.canvas.hideCurrentConnection();
            return false;
        };
    };

    /**
     * @class ConnectionDragBehavior
     * Class that encapsulates the behavior for a connection drag.
     * A connection drag behavior means that instead of moving a shape when dragging
     * occurs, it creates a connection segment that let's us connect to shapes
     * @extends DragBehavior
     *
     * @constructor Creates a new instance of the class
     *
     */
    ConnectionDragBehavior = function () {
    };

    ConnectionDragBehavior.prototype = new DragBehavior();
    /**
     * Type of the instances
     * @property {String}
     */
    ConnectionDragBehavior.prototype.type = "ConnectionDragBehavior";
    /**
     * Attach the drag behavior and ui properties to the corresponding shape
     * @param {Shape} shape
     */
    ConnectionDragBehavior.prototype.attachDragBehavior = function (shape) {
        var $shape = $(shape.getHTML()),
            dragOptions;
        dragOptions = {
            helper : shape.createDragHelper,
            cursorAt : {top : 0, left : 0},
            revert : true
        };
        DragBehavior.prototype.attachDragBehavior.call(this, shape);
        $shape.draggable(dragOptions);
        $shape.draggable('enable');
    };
    /**
     * On drag start handler, initializes all properties needed to start a
     * connection drag
     * @param {CustomShape} customShape
     * @return {Function}
     */
    ConnectionDragBehavior.prototype.onDragStart = function (customShape) {
        return function (e, ui) {
            var canvas  = customShape.canvas,
                currentLabel = canvas.currentLabel;

            // empty the current selection so that the segment created by the
            // helper is always on top
            customShape.canvas.emptyCurrentSelection();

            if (currentLabel) {
                currentLabel.loseFocus();
                $(currentLabel.textField).focusout();
            }
            if (customShape.family !== "CustomShape") {
                return false;
            }
            customShape.setOldX(customShape.getX());
            customShape.setOldY(customShape.getY());
            customShape.startConnectionPoint.x += customShape.getAbsoluteX();
            customShape.startConnectionPoint.y += customShape.getAbsoluteY();
//        customShape.increaseParentZIndex(customShape.parent);
            return true;

        };
    };
    /**
     * On drag handler, creates a connection segment from the shape to the current
     * mouse position
     * @param {CustomShape} customShape
     * @return {Function}
     */
    ConnectionDragBehavior.prototype.onDrag = function (customShape) {
        return function (e, ui) {
            var canvas = customShape.getCanvas(),
                endPoint = new Point();
            if (canvas.connectionSegment) {
                //remove the connection segment in order to create another one
                $(canvas.connectionSegment.getHTML()).remove();
            }

            //Determine the point where the mouse currently is
            endPoint.x = e.pageX - canvas.getX() + canvas.getLeftScroll();
            endPoint.y = e.pageY - canvas.getY() + canvas.getTopScroll();

            //creates a new segment from where the helper was created to the
            // currently mouse location

            canvas.connectionSegment = new Segment({
                startPoint : customShape.startConnectionPoint,
                endPoint : endPoint,
                parent : canvas,
                zOrder: Style.MAX_ZINDEX * 2
            });
            //We make the connection segment point to helper in order to get
            // information when the drop occurs
            canvas.connectionSegment.pointsTo = customShape;
            //create HTML and paint
            //canvas.connectionSegment.createHTML();
            canvas.connectionSegment.paint();
        };

    };
    /**
     * On drag end handler, deletes the connection segment created while dragging
     * @param {CustomShape} customShape
     * @return {Function}
     */
    ConnectionDragBehavior.prototype.onDragEnd = function (customShape) {
        return function (e, ui) {
            if (customShape.canvas.connectionSegment) {
                //remove the connection segment left
                $(customShape.canvas.connectionSegment.getHTML()).remove();
            }
            customShape.setPosition(customShape.getOldX(), customShape.getOldY());
            customShape.dragging = false;
        };
    };

    /**
     * @class CustomShapeDragBehavior
     * Encapsulates the drag behavior of a custom shape (with ports and connections)
     * , it also encapsulates the behavior for multiple drag
     * @extends DragBehavior
     *
     * @constructor Creates a new instance of the class
     *
     */
    CustomShapeDragBehavior = function () {
    };

    CustomShapeDragBehavior.prototype = new DragBehavior();
    /**
     * Type of the instances
     * @property {String}
     */
    CustomShapeDragBehavior.prototype.type = "CustomShapeDragBehavior";
    /**
     * Attach the drag behavior and ui properties to the corresponding shape
     * @param {CustomShape} customShape
     */
    CustomShapeDragBehavior.prototype.attachDragBehavior = function (customShape) {
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
            stop : this.onDragEnd(customShape, true)
        };
        $customShape.draggable(dragOptions);
    };

//TODO Encapsulates behaviors for multiple drag, and simple custom shape drag
//TODO Initialize all oldX and oldY values
    /**
     * On drag start handler, it uses the {@link RegularDragBehavior}.onDragStart
     * method to initialize the drag, but also initializes other properties
     * @param {CustomShape} customShape
     * @return {Function}
     */
    CustomShapeDragBehavior.prototype.onDragStart = function (customShape) {
        return function (e, ui) {
            RegularDragBehavior.prototype.onDragStart.call(this,
                customShape)(e, ui);

            customShape.previousXDragPosition = customShape.getX();
            customShape.previousYDragPosition = customShape.getY();

            if (customShape.canvas.snapToGuide) {
                //init snappers
                customShape.canvas.startSnappers(e);
            }

        };
    };
    /**
     * Procedure executed while dragging, it takes care of multiple drag, moving
     * connections, updating positions and children of the shapes being dragged
     * @param {CustomShape} customShape shape being dragged
     * @param {boolean} root return whether this is the shape where the drag started
     * @param {number} childDiffX x distance needed for the non-root shapes to move
     * @param {number} childDiffY y distance needed for the non-root shapes to move
     * @param {Object} e jQuery object containing the properties when a drag event
     * occur
     * @param {Object} ui JQuery UI object containing the properties when a drag
     * event occur
     */
    CustomShapeDragBehavior.prototype.onDragProcedure = function (customShape, root,
                                                                  childDiffX, childDiffY,
                                                                  e, ui) {
        var i,
            j,
            sibling,
            diffX,
            diffY,
            port,
            child,
            connection,
            shape1,
            shape2,
            canvas = customShape.canvas;
        // shapes
        if (root) {
            if (customShape.canvas.snapToGuide) {
                customShape.canvas.processGuides(e, ui, customShape);
            }
            customShape.setPosition(ui.helper.position().left / canvas.zoomFactor,
                ui.helper.position().top / canvas.zoomFactor);
            //console.log(customShape.x+','+customShape.y);
            diffX = customShape.x - customShape.previousXDragPosition;
            diffY = customShape.y - customShape.previousYDragPosition;

            customShape.previousXDragPosition = customShape.x;
            customShape.previousYDragPosition = customShape.y;

            for (i = 0; i < customShape.canvas.currentSelection.getSize(); i += 1) {
                sibling = customShape.canvas.currentSelection.get(i);
                if (sibling.id !== customShape.id) {
                    sibling.setPosition(sibling.x + diffX, sibling.y + diffY);
                }
            }
        } else {
            customShape.setPosition(customShape.x, customShape.y);
        }

        // children
        if (root) {
            for (i = 0; i < customShape.canvas.currentSelection.getSize(); i += 1) {
                sibling = customShape.canvas.currentSelection.get(i);
                for (j = 0; j < sibling.children.getSize(); j += 1) {
                    child = sibling.children.get(j);
                    CustomShapeDragBehavior.prototype.onDragProcedure.call(this, child,
                        false, diffX, diffY, e, ui);
                }
            }
        } else {
            for (i = 0; i < customShape.children.getSize(); i += 1) {
                child = customShape.children.get(i);
                CustomShapeDragBehavior.prototype.onDragProcedure.call(this, child,
                    false, childDiffX, childDiffY, e, ui);
            }
        }

        // connections
        if (root) {
            for (i = 0; i < customShape.canvas.currentSelection.getSize(); i += 1) {
                sibling = customShape.canvas.currentSelection.get(i);
                for (j = 0; j < sibling.ports.getSize(); j += 1) {
                    //for each port update its absolute position and repaint its connection
                    port = sibling.ports.get(j);
                    connection = port.connection;

                    port.setPosition(port.x, port.y);

                    if (customShape.canvas.sharedConnections.
                            find('id', connection.getID())) {
                        // move the segments of this connections
                        if (connection.srcPort.parent.getID() ===
                            sibling.getID()) {
                            // to avoid moving the connection twice
                            // (two times per shape), move it only if the shape
                            // holds the sourcePort
                            connection.move(diffX * canvas.zoomFactor,
                                diffY * canvas.zoomFactor);
                        }
                    } else {
                        connection
                            // repaint: false
                            //.setSegmentColor(Color.GREY, false)
                            //.setSegmentStyle("regular", false) // repaint: false
                            .disconnect()
                            .connect();
                    }
                }
            }
        } else {
            for (i = 0; i < customShape.ports.getSize(); i += 1) {
                //for each port update its absolute position and repaint its connection
                port = customShape.ports.get(i);
                connection = port.connection;
                shape1 = connection.srcPort.parent;
                shape2 = connection.destPort.parent;

                port.setPosition(port.x, port.y);

                if (customShape.canvas.sharedConnections.
                        find('id', connection.getID())) {
                    // to avoid moving the connection twice
                    // (two times per shape), move it only if the shape
                    // holds the sourcePort
                    if (connection.srcPort.parent.getID() ===
                        customShape.getID()) {
                        connection.move(childDiffX * canvas.zoomFactor,
                            childDiffY * canvas.zoomFactor);
                    }
                } else {
                    connection
                        // repaint: false
                        .setSegmentColor(Color.GREY, false)
                        .setSegmentStyle("regular", false)
                        .disconnect()
                        .connect();
                }
            }
        }
    };
    /**
     * On drag handler, calls the drag procedure while the dragging is occurring,
     * and also takes care of the snappers
     * @param {CustomShape} customShape shape being dragged
     * @param {boolean} root return whether this is the shape where the drag started
     * @param {number} childDiffX x distance needed for the non-root shapes to move
     * @param {number} childDiffY y distance needed for the non-root shapes to move
     * @return {Function}
     */
    CustomShapeDragBehavior.prototype.onDrag = function (customShape, root,
                                                         childDiffX, childDiffY) {
        var self = this;
        return function (e, ui) {

            // call to dragEnd procedure
            self.onDragProcedure(customShape, root, childDiffX,
                childDiffY, e, ui);

        };
    };
    /**
     * Procedure executed on drag end, it takes care of multiple drag, moving
     * connections, updating positions and children of the shapes being dragged
     * @param {CustomShape} customShape shape being dragged
     * @param {boolean} root return whether this is the shape where the drag started
     * @param {Object} e jQuery object containing the properties when a drag event
     * occur
     * @param {Object} ui JQuery UI object containing the properties when a drag
     * event occur
     */
    CustomShapeDragBehavior.prototype.dragEndProcedure = function (customShape,
                                                                   root, e, ui) {
        var i,
            j,
            sibling,
            port,
            child,
            connection,
            shape1,
            shape2,
            canvas = customShape.canvas;

        // shapes
        if (root) {

            // the difference between this segment of code and the segment of code
            // found in dragProcedure is that it's not needed to move the shapes
            // anymore using differentials
            customShape.setPosition(ui.helper.position().left / canvas.zoomFactor,
                ui.helper.position().top / canvas.zoomFactor);
            customShape.wasDragged = true;

//        for (i = 0; i < customShape.canvas.currentSelection.getSize();
//                i += 1) {
//            sibling = customShape.canvas.currentSelection.get(i);
//           sibling.setPosition(sibling.x, sibling.y);
//        }

        } else {
            customShape.setPosition(customShape.x, customShape.y);
        }

        // children
        if (root) {
            for (i = 0; i < customShape.canvas.currentSelection.getSize();
                 i += 1) {
                sibling = customShape.canvas.currentSelection.get(i);
                for (j = 0; j < sibling.children.getSize(); j += 1) {
                    child = sibling.children.get(j);
                    child.changedContainer = true;
                    CustomShapeDragBehavior.prototype.dragEndProcedure.call(this,
                        child, false, e, ui);
                }
            }
        } else {
            for (i = 0; i < customShape.children.getSize(); i += 1) {
                child = customShape.children.get(i);
                CustomShapeDragBehavior.prototype.dragEndProcedure.call(this,
                    child, false, e, ui);
            }
        }

        // connections
        if (root) {
            for (i = 0; i < customShape.canvas.currentSelection.getSize();
                 i += 1) {
                sibling = customShape.canvas.currentSelection.get(i);
                for (j = 0; j < sibling.ports.getSize(); j += 1) {

                    // for each port update its absolute position and repaint
                    // its connection
                    port = sibling.ports.get(j);
                    connection = port.connection;

                    port.setPosition(port.x, port.y);

                    if (customShape.canvas.sharedConnections.
                            find('id', connection.getID())) {
                        // move the segments of this connections
                        if (connection.srcPort.parent.getID() ===
                            sibling.getID()) {
                            // to avoid moving the connection twice
                            // (two times per shape), move it only if the shape
                            // holds the sourcePort
                            connection.disconnect(true).connect({
                                algorithm: 'user',
                                points: connection.points,
                                dx: parseFloat($(connection.html).css('left')),
                                dy: parseFloat($(connection.html).css('top'))
                            });
                            connection.checkAndCreateIntersectionsWithAll();
                        }
                    } else {
                        connection
                            // repaint: false
                            .setSegmentColor(connection.originalSegmentColor, false)
                            .setSegmentStyle(connection.originalSegmentStyle, false);
                        //.disconnect()
                        //.connect();
                        connection.setSegmentMoveHandlers();
                        connection.checkAndCreateIntersectionsWithAll();
                    }
                }
            }
        } else {
            for (i = 0; i < customShape.ports.getSize(); i += 1) {
                //for each port update its absolute position and repaint
                //its connection
                port = customShape.ports.get(i);
                connection = port.connection;
                shape1 = connection.srcPort.parent;
                shape2 = connection.destPort.parent;

                port.setPosition(port.x, port.y);
                if (customShape.canvas.sharedConnections.
                        find('id', connection.getID())) {
                    // to avoid moving the connection twice
                    // (two times per shape), move it only if the shape
                    // holds the sourcePort
                    if (connection.srcPort.parent.getID() ===
                        customShape.getID()) {
                        connection.checkAndCreateIntersectionsWithAll();
                    }
                } else {
                    connection
                        // repaint: false
                        .setSegmentColor(connection.originalSegmentColor, false)
                        .setSegmentStyle(connection.originalSegmentStyle, false)
                        .disconnect()
                        .connect();
                    connection.setSegmentMoveHandlers();
                    connection.checkAndCreateIntersectionsWithAll();
                }
            }
        }

    };
    /**
     * On drag end handler, ot calls drag end procedure, removes the snappers and,
     * fires the command move if necessary
     * @param {CustomShape} customShape
     * @return {Function}
     */
    CustomShapeDragBehavior.prototype.onDragEnd = function (customShape) {
        var command,
            self = this;
        return function (e, ui) {

            // call to dragEnd procedure
            self.dragEndProcedure(customShape, true, e, ui);

            customShape.dragging = false;

            // hide the snappers
            customShape.canvas.verticalSnapper.hide();
            customShape.canvas.horizontalSnapper.hide();

            if (!customShape.changedContainer) {

                command = new CommandMove(customShape.canvas.currentSelection);
                command.execute();
                customShape.canvas.commandStack.add(command);
            }
            customShape.changedContainer = false;

            // decrease the zIndex of the oldParent of customShape
            customShape.decreaseParentZIndex(customShape.oldParent);
        };
    };

    /**
     * @abstract
     * @class ResizeBehavior
     * Abstract class which inherited classes' instances are used for delegation of the resize behavior of a shape.
     *
     * @constructor Creates an instance of the class ResizeBehavior
     */
    ResizeBehavior = function () {
    };

    /**
     * The type of each instance of this class.
     * @property {String}
     */
    ResizeBehavior.prototype.type = "ResizeBehavior";

    /**
     * The family of each instance of this class.
     * @property {String}
     */
    ResizeBehavior.prototype.family = "ResizeBehavior";


    /**
     * Initialize JQueryUI's resize plugin
     * @param {Shape} shape
     */
    ResizeBehavior.prototype.init = function (shape) {
        var $shape = $(shape.getHTML()),
            shapeResizeOptions = {
                handles: shape.getHandlesIDs(),
                disable: false,
                start: this.onResizeStart(shape),
                resize: this.onResize(shape),
                stop: this.onResizeEnd(shape)
            };
        $shape.resizable(shapeResizeOptions);

        //initialize resizable on parent
        $(shape.parent.getHTML()).resizable({disabled: true});
        // update the min height and min width of the parent
        this.updateResizeMinimums(shape.parent);
    };

    /**
     * @abstract
     * @event resizeStart
     * Abstract method to be implemented in inherited classes
     * @param {Shape} shape
     */
    ResizeBehavior.prototype.onResizeStart = function (shape) {
    };
    /**
     * @abstract
     * @event resize
     * Abstract method to be implemented in inherited classes
     * @param {Shape} shape
     */
    ResizeBehavior.prototype.onResize = function (shape) {
    };
    /**
     * @abstract
     * @event resizeEnd
     * Abstract method to be implemented in inherited classes
     * @param {Shape} shape
     */
    ResizeBehavior.prototype.onResizeEnd = function (shape) {
    };

///**
// * Sets a shape's container to a given container
// * @param container
// * @param shape
// */
//ResizeBehavior.prototype.resizeStartHook = function () {
//};
///**
// * Removes shape from its current container
// * @param shape
// */
//ResizeBehavior.prototype.resizeHook = function () {
//};
///**
// * Adds a shape to a given container
// * @param container
// * @param shape
// */
//ResizeBehavior.prototype.resizeEndHook = function () {
//};

    /**
     * Updates the minimum height and maximum height of the JQqueryUI's resizable plugin.
     * @param {Shape} shape
     * @chainable
     */
    ResizeBehavior.prototype.updateResizeMinimums = function (shape) {
        var minW,
            minH,
            children = shape.getChildren(),
            limits = children.getDimensionLimit(),
            margin = 15,
            $shape = $(shape.getHTML());

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
        if (typeof $shape.resizable("instance") != 'undefined') {
            $shape.resizable('option', 'minWidth', minW);
            $shape.resizable('option', 'minHeight', minH);
        } else {
            $shape.resizable({
                minWidth:minW,
                minHeight:minH
            });
        }
        return this;
    };

    /**
     * @class RegularResizeBehavior
     * Class that encapsulates the regular resize behavior of a shape
     * @extends ResizeBehavior
     *
     * @constructor Creates a new instance of the class RegularResizeBehavior
     */
    RegularResizeBehavior = function () {
    };

    RegularResizeBehavior.prototype = new ResizeBehavior();

    /**
     * The type of each instance of this class
     * @property {String}
     */
    RegularResizeBehavior.prototype.type = "RegularResizeBehavior";

    /**
     * Initialize JQueryUI's resizable plugin
     * @param {Shape} shape
     */
    RegularResizeBehavior.prototype.init = function (shape) {
        var $shape = $(shape.getHTML());
        ResizeBehavior.prototype.init.call(this, shape);
        $shape.resizable('enable');
        shape.applyStyleToHandlers('resizableStyle');

        // hide its handles (jQueryUI's resizable shows the handles by default)
        shape.showOrHideResizeHandlers(false);
    };

    /**
     * @event resizeStart
     * ResizeStart event fired when the user resizes a shape.
     * It does the following:
     *
     * - Save old values (for the undo-redo stack)
     * - Empties the {@link Canvas#property-currentSelection}, and adds `shape` to that arrayList
     * - Hides the resize handlers of the shape
     *
     * @param {Shape} shape
     */
    RegularResizeBehavior.prototype.onResizeStart = function (shape) {
        return function (e, ui) {
            shape.resizing = true;
            shape.dragging = false;

            shape.oldWidth = shape.width;
            shape.oldHeight = shape.height;
            shape.oldX = shape.x;
            shape.oldY = shape.y;
            shape.oldAbsoluteX = shape.absoluteX;
            shape.oldAbsoluteY = shape.absoluteY;

            if (shape.ports) {
                shape.initPortsChange();
            }

            if (shape.canvas.currentSelection.getSize() > 1) {
                // empty current selection and add this item to the currentSelection
                shape.canvas.emptyCurrentSelection();
                shape.canvas.addToSelection(shape);
            }
            shape.showOrHideResizeHandlers(false);

            // calculate percentage of each label in each axis
            shape.calculateLabelsPercentage();
            return true;
        };
    };

    /**
     * @event resize
     * Resize event fired when the user is resizing a shape.
     * It does the following:
     *
     * - Sets the position and dimensions of the shape
     * - Fixes the ports of `shape` and from the its children (recursively)
     * - Updates the position of its labels
     *
     * @param {Shape} shape
     */
    RegularResizeBehavior.prototype.onResize = function (shape) {
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
            shape.updateLabelsPosition();
        };
    };

    /**
     * @event resizeEnd
     * ResizeEnd event fired when the user stops resizing a shape.
     * It does the following:
     *
     * - Shows the handlers of `shape`
     * - Updates the dimension of its parent (this shape might have outgrown the shape)
     * - Creates an instance of {@link CommandResize} to add it to the undo-redo stack
     *
     * @param {Shape} shape
     */
    RegularResizeBehavior.prototype.onResizeEnd = function (shape) {
        return function (e, ui) {
            var i,
                label,
                command;
            shape.resizing = false;

            // last resize
            RegularResizeBehavior.prototype.onResize.call(this, shape)(e, ui);

            // show the handlers again
            shape.showOrHideResizeHandlers(true);

            // update the dimensions of the parent if possible (a shape might
            // have been resized out of the dimensions of its parent)
            shape.parent.updateDimensions(10);

            if (shape.ports) {
                shape.firePortsChange();
            }

            // TESTING COMMANDS
            command = new CommandResize(shape);
            shape.canvas.commandStack.add(command);
            command.execute();
            for (i = 0; i < shape.labels.getSize(); i += 1) {
                label = shape.labels.get(i);
                label.setLabelPosition(label.location, label.diffX, label.diffY);
            }

            return true;
        };
    };

    /**
     * @class NoResizeBehavior
     * Class that encapsulates the regular resize behavior of a shape when it's not supposed to be resizable
     * @extends ResizeBehavior
     *
     * @constructor Creates a new instance of the class RegularResizeBehavior
     */
    NoResizeBehavior = function () {
    };

    NoResizeBehavior.prototype = new ResizeBehavior();

    /**
     * The type of each instance of this class.
     * @property {String}
     */
    NoResizeBehavior.prototype.type = "NoResizeBehavior";

    /**
     * Initialize JQueryUI's resize plugin (disables the resizable plugin).
     * @param {Shape} shape
     */
    NoResizeBehavior.prototype.init = function (shape) {
        var $shape = $(shape.getHTML());
        // Replacing the way this is disabled on JQueryUI 1.11.4
        try {
            $shape.resizable('destroy');
        } catch(e) {}
        $shape.removeClass('ui-state-disabled');
        shape.applyStyleToHandlers('nonResizableStyle');
        shape.showOrHideResizeHandlers(false);
    };

    /**
     * Overwrites the method {@link ResizeBehavior#updateResizeMinimums} since
     * a shape that is not resizable shouldn't update its resize minimums.
     * @param {Shape} shape
     */
    NoResizeBehavior.prototype.updateResizeMinimums = function (shape) {
    };

    /**
     * @class DropBehavior
     * Abstract class where all the drop behavior classes inherit from
     * Strategy Pattern
     * @constructor
     *  Creates a new instance of the class
     * @param {Array} [selectors=[]] css selectors that the drop behavior
     * will accept
     */
    DropBehavior = function (selectors) {
        /**
         * css selectors that the used for the drop behaviors beside the defaults
         * @property {Array}
         */
        this.selectors = selectors || [];
    };
    /**
     * Type of the instances
     * @property {String}
     */
    DropBehavior.prototype.type = "DropBehavior";
    /**
     * Family of the instances
     * @property {String}
     */
    DropBehavior.prototype.family = "DropBehavior";
    /**
     * Default css selectors for the drop behavior
     * @property {String}
     */
    DropBehavior.prototype.defaultSelector = "";
    /**
     * Attach the drop behaviors and assign the handlers to the corresponding shape
     * @param {Shape} shape
     */
    DropBehavior.prototype.attachDropBehavior = function (shape) {
        var $shape = $(shape.getHTML()),
            dropOptions = {
                accept: this.defaultSelector,
                drop: this.onDrop(shape),
                over: this.onDragEnter(shape),
                out : this.onDragLeave(shape),
                greedy : true
            };
        $shape.droppable(dropOptions);
    };

    /**
     * @event dragEnter
     * @abstract Handler for the drag enter event
     * @param {Shape} shape
     * @template
     * @protected
     */
    DropBehavior.prototype.onDragEnter = function (shape) {
        return function (e, ui) {
        };
    };

    /**
     * @event dragLeave
     * @abstract Handler for the drag leave event
     * @param {Shape} shape
     * @template
     * @protected
     */
    DropBehavior.prototype.onDragLeave = function (shape) {
        return function (e, ui) {
        };
    };

    /**
     * @event drop
     * @abstract Handler for the on drop event
     * @param {Shape} shape
     * @template
     * @protected
     */
    DropBehavior.prototype.onDrop = function (shape) {
        return function (e, ui) {
        };
    };
    /**
     * Sets the selectors that the drop behavior will accept
     * @param {Array} selectors css selectors
     * @param {boolean} overwrite determines whether the default selectors will be
     * overridden or not
     * @chainable
     */
    DropBehavior.prototype.setSelectors = function (selectors, overwrite) {
        var currentSelectors = "",
            index,
            i;
        if (selectors) {
            this.selectors = selectors;
        }
        if (!overwrite) {
            currentSelectors = this.defaultSelector;
            index = 0;
        } else if (selectors.length > 0) {
            currentSelectors = selectors[0];
            index = 1;
        }
        for (i = index; i < selectors.length; i += 1) {
            currentSelectors += "," + this.selectors[i];
        }
        return this;
    };
    /**
     * Updates the accepted drop selectors
     * @param {Shape} shape
     * @param {Array} selectors
     * @chainable
     */
    DropBehavior.prototype.updateSelectors = function (shape, selectors) {
        var $shape = $(shape.getHTML()),
            currentSelectors,
            i;
        if (selectors) {
            this.selectors = selectors;
        }
//    if (!overwrite) {
//        currentSelectors = $shape.droppable("option", "accept");
//        console.log(currentSelectors);
//    }
        if (this.selectors.length > 0) {
            currentSelectors = this.selectors[0];
        }
        for (i = 1; i < this.selectors.length; i += 1) {
            currentSelectors += ',' + this.selectors[i];
        }
        $shape.droppable({"accept" : currentSelectors});
        return this;
    };

    /**
     * Hook for the drag enter handler
     * @template
     * @protected
     */
    DropBehavior.prototype.dragEnterHook = function () {
        return true;
    };

    /**
     * Hook for the drag leave handler
     * @template
     * @protected
     */
    DropBehavior.prototype.dragLeaveHook = function () {
        return true;
    };

    /**
     * Hook for the drop handler, executes before the on drop handler logic
     * @param {Shape} shape
     * @param {Object} e jQuery object that contains the properties on the
     * drop event
     * @param {Object} ui jQuery object that contains the properties on the
     * drop event
     * @template
     * @protected
     */
    DropBehavior.prototype.dropStartHook = function (shape, e, ui) {
        return true;
    };
    /**
     * Hook for the on drop handler
     * @param {Shape} shape
     * @param {Object} e jQuery object that contains the properties on the
     * drop event
     * @param {Object} ui jQuery object that contains the properties on the
     * drop event
     * @template
     * @protected
     */
    DropBehavior.prototype.dropHook = function (shape, e, ui) {
        return true;
    };
    /**
     * Hook for the on drop handler, executes after the drop logic has concluded
     * @param {Shape} shape
     * @param {Object} e jQuery object that contains the properties on the
     * drop event
     * @param {Object} ui jQuery object that contains the properties on the
     * drop event
     * @template
     * @protected
     */
    DropBehavior.prototype.dropEndHook = function (shape, e, ui) {
        return true;
    };

    /**
     * @class ConnectionDropBehavior
     * Class that encapsulates the drop behavior for dropped connections in shapes
     * @extends DropBehavior
     *
     * @constructor
     * Creates a new instance of the class
     * @param selectors
     */
    ConnectionDropBehavior = function (selectors) {
        DropBehavior.call(this, selectors);
    };

    ConnectionDropBehavior.prototype = new DropBehavior();
    /**
     * Type of the instances
     * @property {String}
     */
    ConnectionDropBehavior.prototype.type = "ConnectionDropBehavior";
    /**
     * Defaults selectors for this drop behavior
     * @property {String}
     */
    ConnectionDropBehavior.prototype.defaultSelector = ".custom_shape,.port";

    /**
     * Sets the selectors for this drop behavior including the defaults
     * @param selectors
     * @param overwrite
     * @return {*}
     */
    ConnectionDropBehavior.prototype.setSelectors = function (selectors, overwrite) {
        DropBehavior.prototype.setSelectors.call(this, selectors, overwrite);
        this.selectors.push(".port");
        this.selectors.push(".custom_shape");

        return this;
    };
    /**
     * Drag enter hook for this drop behavior, marks that a shape is over a
     * droppable element
     * @param {Shape} shape
     * @return {Function}
     */
    ConnectionDropBehavior.prototype.onDragEnter = function (shape) {
        return function (e, ui) {
            shape.entered = true;
        };
    };

    /**
     * Drag leave hook for this drop behavior, marks that a shape has left a
     * droppable element
     * @param {Shape} shape
     * @return {Function}
     */
    ConnectionDropBehavior.prototype.onDragLeave = function (shape) {
        return function (e, ui) {
            shape.entered = false;
        };
    };
    /**
     * On drop handler for this drop behavior, creates a connection between the
     * droppable element and the dropped element, or move ports among those shapes
     * @param {Shape} shape
     * @return {Function}
     */
    ConnectionDropBehavior.prototype.onDrop = function (shape) {
        var that = this;
        return function (e, ui) {
            var canvas  = shape.getCanvas(),
            //regularShapes = shape.canvas.regularShapes,
                id = ui.draggable.attr('id'),
            //port = regularShapes.find('id', id),
                x,
                y,
                currLeft,
                currTop,
                startPoint,
                sourceShape,
                sourcePort,
                endPort,
                endPortXCoord,
                endPortYCoord,
                connection,
                currentConnection = canvas.currentConnection,
                srcPort,
                dstPort,
                port,
                success = false,
                command;
            shape.entered = false;
            if (!shape.dropBehavior.dropStartHook(shape, e, ui)) {
                return false;
            }
            if (shape.getConnectionType() === "none") {
                return true;
            }
            //shape.entered = false;
            if (currentConnection) {
                srcPort = currentConnection.srcPort;
                dstPort = currentConnection.destPort;
                if (srcPort.id === id) {
                    port = srcPort;
                } else if (dstPort.id === id) {
                    port = dstPort;
                } else {
                    port = null;
                }
            }
            if (ui.helper && ui.helper.attr('id') === "drag-helper") {

                //if its the helper then we need to create two ports and draw a
                // connection
                //we get the points and the corresponding shapes involved
                startPoint = shape.canvas.connectionSegment.startPoint;
                sourceShape = shape.canvas.connectionSegment.pointsTo;
                //determine the points where the helper was created
                if (sourceShape.parent && sourceShape.parent.id === shape.id) {
                    return true;
                }
                sourceShape.setPosition(sourceShape.oldX, sourceShape.oldY);

                startPoint.x -= sourceShape.absoluteX;
                startPoint.y -= sourceShape.absoluteY;

                //create the ports
                sourcePort = new Port({
                    width: 8,
                    height: 8
                });
                endPort = new Port({
                    width: 8,
                    height: 8
                });

                //determine the position where the helper was dropped
                endPortXCoord = ui.offset.left - shape.canvas.getX() -
                shape.getAbsoluteX() + shape.canvas.getLeftScroll();
                endPortYCoord = ui.offset.top - shape.canvas.getY() -
                shape.getAbsoluteY() + shape.canvas.getTopScroll();

                // add ports to the corresponding shapes
                // addPort() determines the position of the ports
                sourceShape.addPort(sourcePort, startPoint.x, startPoint.y);
                shape.addPort(endPort, endPortXCoord, endPortYCoord,
                    false, sourcePort);

                //add ports to the canvas array for regularShapes
                //shape.canvas.regularShapes.insert(sourcePort).insert(endPort);

                //create the connection
                connection = new Connection({
                    srcPort : sourcePort,
                    destPort: endPort,
                    canvas : shape.canvas,
                    segmentStyle: shape.connectionType
                });

//            console.log(sourcePort.direction);
//            console.log(endPort.direction);

                //set its decorators
                connection.setSrcDecorator(new ConnectionDecorator({
                    width: 11,
                    height: 11,
                    canvas: canvas,
                    decoratorPrefix: "con_normal",
                    decoratorType: "source",
                    parent: connection
                }));
                connection.setDestDecorator(new ConnectionDecorator({
                    width: 11,
                    height: 11,
                    canvas: canvas,
                    decoratorPrefix: "con_normal",
                    decoratorType: "target",
                    parent: connection
                }));

                connection.canvas.commandStack.add(new CommandConnect(connection));

                //connect the two ports
                connection.connect();
                connection.setSegmentMoveHandlers();

                // / fixes the zIndex of the connection
                //connection.fixZIndex();

                //add the connection to the canvas, that means insert its html to
                // the DOM and adding it to the connections array
                canvas.addConnection(connection);

                // now that the connection was drawn try to create the intersections
                connection.checkAndCreateIntersectionsWithAll();

                //attaching port listeners
                sourcePort.attachListeners(sourcePort);
                endPort.attachListeners(endPort);

                // finally trigger createEvent
                canvas.triggerCreateEvent(connection, []);
            } else if (port) {

                port.setOldParent(port.getParent());
                port.setOldX(port.getX());
                port.setOldY(port.getY());

                x = ui.position.left;
                y = ui.position.top;
                port.setPosition(x, y);
                shape.dragging = false;
                if (shape.getID() !== port.parent.getID()) {
                    port.parent.removePort(port);
                    currLeft = ui.offset.left - canvas.getX() -
                    shape.absoluteX + shape.canvas.getLeftScroll();
                    currTop = ui.offset.top - canvas.getY() -
                    shape.absoluteY + shape.canvas.getTopScroll();
                    shape.addPort(port, currLeft, currTop, true);
                    canvas.regularShapes.insert(port);
                } else {
                    shape.definePortPosition(port, port.getPoint(true));
                }

                // LOGIC: when portChangeEvent is triggered it gathers the state
                // of the connection but since at this point there's only a segment
                // let's paint the connection, gather the state and then disconnect
                // it (the connection is later repainted on, I don't know how)
                port.connection.connect();
                canvas.triggerPortChangeEvent(port);
                port.connection.disconnect();

                command = new CommandReconnect(port);
                port.canvas.commandStack.add(command);
            }
//        shape.dropBehavior.dropEndHook(shape, e, ui);
            return false;
        };
    };

    /**
     * @class  NoDropBehavior
     * Encapsulates the drop behavior representing an object that can't be droppable
     * @extends DropBehavior
     *
     * @constructor
     * Creates a new instance of the class
     */
    NoDropBehavior = function (selectors) {
        DropBehavior.call(this, selectors);
    };

    NoDropBehavior.prototype = new DropBehavior();
    /**
     * Type of the instances
     * @property {String}
     */
    NoDropBehavior.prototype.type = "NoDropBehavior";
    /**
     * Attach the drop behavior, sets the accepted elements to none
     * @param {Shape} shape
     */
    NoDropBehavior.prototype.attachDropBehavior = function (shape) {
        var $shape = $(shape.getHTML());
        DropBehavior.prototype.attachDropBehavior.call(this, shape);
        $shape.droppable('option', 'accept', "");
    };

    /**
     * @class ContainerDropBehavior
     * Encapsulates the drop behavior of a container
     * @extends DropBehavior
     *
     * @constructor
     * Creates a new instance of the class
     * @param {Array} [selectors=[]] css selectors that this drop behavior will
     * accept
     */
    ContainerDropBehavior = function (selectors) {
        DropBehavior.call(this, selectors);
    };

    ContainerDropBehavior.prototype = new DropBehavior();
    /**
     * Type of the instances
     * @property {String}
     */
    ContainerDropBehavior.prototype.type = "ContainerDropBehavior";
    /**
     * Default selectors for this drop behavior
     * @property {String}
     */
    ContainerDropBehavior.prototype.defaultSelector = ".custom_shape";

    /**
     * On drop handler for this drop behavior, creates shapes when dropped from the
     * toolbar, or move shapes among containers
     * @param {Shape} shape
     * @return {Function}
     */
    ContainerDropBehavior.prototype.onDrop = function (shape) {
        return function (e, ui) {
            var customShape = null,
                canvas = shape.getCanvas(),
                selection,
                sibling,
                i,
                command,
                coordinates,
                id,
                shapesAdded =  [],
                containerBehavior = shape.containerBehavior;
            if (canvas.readOnly) {
                return false;
            }

            shape.entered = false;
            if (ui.helper && ui.helper.attr('id') === "drag-helper") {
                return false;
            }
            id = ui.draggable.attr('id');
            customShape = canvas.toolBarShapeFactory(id);
            if (customShape === null) {

                customShape = canvas.customShapes.find('id', id);

                if (!customShape || !shape.dropBehavior.dropHook(shape, e, ui)) {
                    return false;
                }

                if (!(customShape.parent &&
                    customShape.parent.id === shape.id)) {

                    selection = canvas.currentSelection;
                    for (i = 0; i < selection.getSize(); i += 1) {
                        sibling = selection.get(i);
//                    sibling.oldParent = sibling.parent;
//                    sibling.oldX = sibling.x;
//                    sibling.oldY = sibling.y;
//                    sibling.oldAbsoluteX = sibling.absoluteX;
//                    sibling.oldAbsoluteY = sibling.absoluteY;
                        coordinates = Utils.getPointRelativeToPage(sibling);
                        coordinates = Utils
                            .pageCoordinatesToShapeCoordinates(shape, null,
                            coordinates.x, coordinates.y);

//                    sibling.parent.containerBehavior.removeFromContainer(sibling);
//                    shape.addElement(sibling, coordinates.x,
//                        coordinates.y, false);
                        shapesAdded.push({
                            shape : sibling,
                            container : shape,
                            x : coordinates.x,
                            y : coordinates.y,
                            topLeft : false
                        });


//                    sibling.parent.swapElementContainer(sibling, shape,
//                        coordinates.x, coordinates.y, false);
//                    shape.swapElementContainer()
//                    sibling.fixZIndex(sibling, 0);
//                    sibling.changeParent(sibling.oldX, sibling.oldY,
//                        sibling.oldAbsoluteX, sibling.oldAbsoluteY,
//                        sibling.oldParent, sibling.canvas);
                        //console.log(shape.children.getSize());
                    }
                    command = new CommandSwitchContainer(shapesAdded);
                    command.execute();
                    canvas.commandStack.add(command);
                    canvas.multipleDrop = true;

                }

                // fix resize minWidth and minHeight and also fix the dimension
                // of this shape (if a child made it grow)

                shape.updateDimensions(10);


                canvas.updatedElement = null;
            } else {
                coordinates = Utils.pageCoordinatesToShapeCoordinates(shape, e);
                shape.addElement(customShape, coordinates.x, coordinates.y,
                    customShape.topLeftOnCreation);
                customShape.attachListeners();

                //since it is a new element in the designer, we triggered the
                //custom on create element event
                canvas.updatedElement = customShape;

                // create the command for this new shape
                command = new CommandCreate(customShape);
                canvas.commandStack.add(command);
                command.execute();
                //shape.updateSize();
            }
        };
    };

    /**
     * @class ConnectionContainerDropBehavior
     * Class that encapsulates the drop behaviors for containers that can also be
     * connected
     * @extends DropBehavior
     *
     * @constructor
     * Creates a new instance of the class
     * @param {Array} [selectors=[]] css selectors that this drop behavior will
     * accept
     */
    ConnectionContainerDropBehavior = function (selectors) {
        DropBehavior.call(this, selectors);
    };


    ConnectionContainerDropBehavior.prototype = new DropBehavior();
    /**
     * Type of the instances
     * @property {String}
     */
    ConnectionContainerDropBehavior.prototype.type = "ConnectionContainerDropBehavior";
    /**
     * Default selectors for this drop behavior
     * @property {String}
     */
    ConnectionContainerDropBehavior.prototype.defaultSelector =
        ".custom_shape,.port";
    /**
     * Set the selectors for this drop behavior including the default selectors
     * @param {Array} selectors css selectors
     * @param {boolean} overwrite
     * @return {*}
     */
    ConnectionContainerDropBehavior.prototype.setSelectors = function (selectors,
                                                                       overwrite) {
        DropBehavior.prototype.setSelectors.call(this, selectors, overwrite);
        this.selectors.push(".port");
        this.selectors.push(".custom_shape");
        return this;
    };
    /**
     * On drop handler for this drop behavior, determines whether to create a
     * connection or add a shape to the container that is using this drop behavior
     * @param {Shape} shape
     * @return {Function}
     */
    ConnectionContainerDropBehavior.prototype.onDrop = function (shape) {
        return function (e, ui) {
            if (!ConnectionDropBehavior.prototype.onDrop.call(this, shape)(e, ui)) {
                ContainerDropBehavior.prototype.onDrop.call(this, shape)(e, ui);
            }

        };
    };



    /**
     * @class Color
     * This class holds the representation and operations of RGBa representation of color,
     * it's very useful if we want to save color constants as an instance and later get the representation
     * in CSS.
     *
     *      //i.e.
     *      var color = new Color(
     *          128,    // red
     *          128,    // green
     *          128,    // blue
     *          1       // opacity
     *      )
     *
     * @constructor Creates an instance of this class.
     * @param {number} red
     * @param {number} green
     * @param {number} blue
     * @param {number} opacity
     * @return {Color}
     */
    Color = function (red, green, blue, opacity) {
        /**
         * Red value of the RGB Color
         * @property {number} [red=0]
         */
        this.red = (!red) ? 0 : red;
        /**
         * Green value of the RGB Color
         * @property {number} [green=0]
         */
        this.green = (!green) ? 0 : green;
        /**
         * Blue value of the RGB Color
         * @property {number} [blue=0]
         */
        this.blue = (!blue) ? 0 : blue;
        /**
         * Opacity of the RGB Color
         * @property {number} [opacity=1]
         */
        this.opacity = (!opacity) ? 1 : opacity;
    };

    /**
     * Type of this class
     * @property {String}
     */
    Color.prototype.type = "Color";

    /**
     * Constant for the color grey
     * @property {Color} [GREY=new Color(192, 192, 192, 1)]
     */
    Color.GREY = new Color(192, 192, 192, 1);

    /**
     * Returns the red value of the RGB Color
     * @returns {number}
     */
    Color.prototype.getRed = function () {
        return this.red;
    };

    /**
     * Returns the green value of the RGB Color
     * @returns {number}
     */
    Color.prototype.getGreen = function () {
        return this.green;
    };

    /**
     * Returns the blue value of the RGB Color
     * @returns {number}
     */
    Color.prototype.getBlue = function () {
        return this.blue;
    };

    /**
     * Returns the opacity of the RGB Color
     * @returns {number}
     */
    Color.prototype.getOpacity = function () {
        return this.opacity;
    };

    /**
     * Sets the red value of the RGB Color
     * @param {number} newRed
     * @chainable
     */
    Color.prototype.setRed = function (newRed) {
        if (typeof newRed === "number" && newRed >= 0 && newRed <= 255) {
            this.red = newRed;
        }
        return this;
    };

    /**
     * Sets the green value of the RGB Color
     * @param {number} newRed
     * @chainable
     */
    Color.prototype.setGreen = function (newGreen) {
        if (typeof newGreen === "number" && newGreen >= 0 && newGreen <= 255) {
            this.green = newGreen;
        }
        return this;
    };

    /**
     * Sets the blue value of the RGB Color
     * @param {number} newBlue
     * @chainable
     */
    Color.prototype.setBlue = function (newBlue) {
        if (typeof newBlue === "number" && newBlue >= 0 && newBlue <= 255) {
            this.blue = newBlue;
        }
        return this;
    };

    /**
     * Sets the opacity of the RGB Color
     * @param {number} newOpacity
     * @chainable
     */
    Color.prototype.setOpacity = function (newOpacity) {
        if (typeof newOpacity === "number" && newOpacity >= 0 && newOpacity <= 255) {
            this.opacity = newOpacity;
        }
        return this;
    };

    /**
     * Returns the css representation of the RGB color
     *      //i.e.
     *      var color = new Color(10, 20, 30, 0.1);
     *      color.getCSS();         // "rgba(10, 20, 30, 0.1)"
     * @returns {String}
     */
    Color.prototype.getCSS = function () {
        var css = "rgba(" + this.red + "," + this.green + "," + this.blue +
            "," + this.opacity + ")";
        return css;
    };

    /**
     * @class Style
     * Class that represent the style of a an object, {@link JCoreObject} creates an instance of this class so every
     * class that inherits from {@link JCoreObject} has an instance of this class.
     *
     *      // i.e
     *      // Let's assume that 'shape' is a CustomShape
     *      var style = new Style({
 *          cssClasses: [
 *              'sprite-class', 'marker-class', ...
 *          ],
 *          cssProperties: {
 *              border: 1px solid black,
 *              background-color: grey,
 *              ...
 *          },
 *          belongsTo: shape
 *      })
     *
     * @constructor Creates a new instance of this class
     * @param {Object} options
     * @cfg {Array} [cssClasses=[]] the classes that `this.belongsTo` has
     * @cfg {Object} [cssProperties={}] the css properties that `this.belongsTo` has
     * @cfg {Object} [belongsTo=null] a pointer to the owner of this instance
     */
    Style = function (options) {

        /**
         * JSON Object used to map each of the css properties of the object,
         * this object has the same syntax as the object passed to jQuery.css()
         *      cssProperties: {
     *          background-color: [value],
     *          border: [value],
     *          ...
     *      }
         * @property {Object}
         */
        this.cssProperties = null;

        /**
         * Array of all the classes of this object
         *      cssClasses = [
         *          'class_1',
         *          'class_2',
         *          ...
         *      ]
         * @property {Array}
         */
        this.cssClasses = null;

        /**
         * Pointer to the object to whom this style belongs to
         * @property {Object}
         */
        this.belongsTo = null;


        Style.prototype.initObject.call(this, options);
    };


    /**
     * The type of this class
     * @property {String}
     */
    Style.prototype.type = "Style";

    /**
     * Constant for the max z-index
     * @property {number} [MAX_ZINDEX=100]
     */
    Style.MAX_ZINDEX = 100;

    /**
     * Instance initializer which uses options to extend the config options to
     * initialize the instance
     * @private
     * @param {Object} options
     */
    Style.prototype.initObject = function (options) {
        var defaults = {
            cssClasses: [],
            cssProperties: {},
            belongsTo: null
        };
        $.extend(true, defaults, options);
        this.cssClasses = defaults.cssClasses;
        this.cssProperties = defaults.cssProperties;
        this.belongsTo = defaults.belongsTo;
    };

    /**
     * Applies cssProperties and cssClasses to `this.belongsTo`
     * @chainable
     */
    Style.prototype.applyStyle = function () {

        if (!this.belongsTo.html) {
            throw new Error("applyStyle(): can't apply style to an" +
            " object with no html");
        }

        var i,
            class_i;

        // apply the cssProperties
        $(this.belongsTo.html).css(this.cssProperties);

        // apply saved classes
        for (i = 0; i < this.cssClasses.length; i += 1) {
            class_i = this.cssClasses[i];
            if (!$(this.belongsTo.html).hasClass(class_i)) {
                $(this.belongsTo.html).addClass(class_i);
            }
        }
        return this;
    };

    /**
     * Extends the property `cssProperties` with a new object and also applies those new properties
     * @param {Object} properties
     * @chainable
     */
    Style.prototype.addProperties = function (properties) {
        $.extend(true, this.cssProperties, properties);
        $(this.belongsTo.html).css(properties);
        return this;
    };

    /**
     * Gets a property from `this.cssProperties` using jQuery or `window.getComputedStyle()`
     * @param {String} property
     * @return {String}
     */
    Style.prototype.getProperty = function (property) {
        return this.cssProperties[property] ||
        $(this.belongsTo.html).css(property) ||
        window.getComputedStyle(this.belongsTo.html, null)
            .getPropertyValue(property);
    };

    /**
     * Removes ´properties´ from the ´this.cssProperties´, also disables those properties from
     * the HTMLElement
     * @param {Object} properties
     * @chainable
     */
    Style.prototype.removeProperties = function (properties) {
        var property,
            i;
        for (i = 0; i < properties.length; i += 1) {
            property = properties[i];
            if (this.cssProperties.hasOwnProperty(property)) { // JS Code Convention
                $(this.belongsTo.html).css(property, "");   // reset inline style
                delete this.cssProperties[property];
            }
        }
        return this;
    };

    /**
     * Adds new classes to ´this.cssClasses´ array
     * @param {Array} cssClasses
     * @chainable
     */
    Style.prototype.addClasses = function (cssClasses) {
        var i,
            cssClass;
        if (cssClasses && cssClasses instanceof Array) {
            for (i = 0; i < cssClasses.length; i += 1) {
                cssClass = cssClasses[i];
                if (typeof cssClass === "string") {
                    if (this.cssClasses.indexOf(cssClass) === -1) {
                        this.cssClasses.push(cssClass);
                        $(this.belongsTo.html).addClass(cssClass);
                    }
                } else {
                    throw new Error("addClasses(): array element is not of type string");
                }
            }
        } else {
            throw new Error("addClasses(): parameter must be of type Array");
        }
        return this;
    };

    /**
     * Removes classes from ´this.cssClasses´ array, also removes those classes from
     * the HTMLElement
     * @param {Array} cssClasses
     * @chainable
     */
    Style.prototype.removeClasses = function (cssClasses) {

        var i,
            index,
            cssClass;
        if (cssClasses && cssClasses instanceof Array) {
            for (i = 0; i < cssClasses.length; i += 1) {
                cssClass = cssClasses[i];
                if (typeof cssClass === "string") {
                    index = this.cssClasses.indexOf(cssClass);
                    if (index !== -1) {
                        $(this.belongsTo.html).removeClass(this.cssClasses[index]);
                        this.cssClasses.splice(index, 1);
                    }
                } else {
                    throw new Error("removeClasses(): array element is not of " +
                    "type string");
                }
            }
        } else {
            throw new Error("removeClasses(): parameter must be of type Array");
        }
        return this;
    };

    /**
     * Removes all the classes from ´this.cssClasses´ array
     * @param {Array} cssClasses
     * @chainable
     */
    Style.prototype.removeAllClasses = function () {
        this.cssClasses = [];
        $(this.belongsTo.html).removeClass();
        return this;
    };

    /**
     * Checks if the class is a class stored in ´this.cssClasses´
     * @param cssClass
     * @return {boolean}
     */
    Style.prototype.containsClass = function (cssClass) {
        return this.cssClasses.indexOf(cssClass) !== -1;
    };

    /**
     * Returns an array with all the classes of ´this.belongsTo´
     * @return {Array}
     */
    Style.prototype.getClasses = function () {
        return this.cssClasses;
    };

    /**
     * Serializes this instance
     * @return {Object}
     * @return {Array} return.cssClasses
     */
    Style.prototype.stringify = function () {
        return {
            cssClasses: this.cssClasses
//        cssProperties: this.cssProperties
        };
    };



    /**
     * @class JCoreObject
     * This class contains the common behavior of the main families of classes
     * in the library
     * This class should never be instantiated since its just an abstraction of
     * properties that classes in the library share
     *
     *      //i.e.
     *      //We will set the properties defined in this class, to a custom shape
     *      var customShape = new CustomShape({
 *          id : "someid",
 *          canvas : someCanvas //assuming there is a canvas instance
 *          style: { //style options regarding the objects
 *              cssProperties: {}, //These are the style properties we want the
 *              //object to have
 *              cssClasses: ["someclass"] //css classes that will be applied
 *              to the object
 *          },
 *          //now we set the width and height
 *          width : 30,
 *          height : 50,
 *          //and the coordinates we want the shape to be positioned
 *          x : 10,
 *          y : 5,
 *          //z-index of the element
 *          zOrder : 1,
 *          //set to true if we want to make it visible
 *          visible : true,
 *
 *      });
     *
     * @constructor
     * Creates an instance of the class
     * @param {Object} options options for initializing the object
     * @cfg {String} id id that will be assigned to the element
     * @cfg {Object} [style={
 *     cssProperties: {},
 *     cssClasses: []
 * }] style properties and classes that we want to assign to the element
     * @cfg {Canvas} [canvas=null] canvas associated to the element
     * @cfg {number} [width=0] width of the element
     * @cfg {number} [height=0] height of the element
     * @cfg {number} [x=0] x coordinate of the element
     * @cfg {number} [y=0] y coordinate of the element
     * @cfg {number} [zOrder=1] z-Index applied to the element
     * @cfg {boolean} [visible=true] Determines whether an element will be visible
     */

    JCoreObject = function (options) {
        /**
         * Object unique id
         * @property {String}
         */
        this.id = null;
//    console.log(this.id);
        /**
         * Reference to the canvas of the given object
         * @property {Canvas}
         */
        this.canvas = null;

        /**
         * HTMLNode of the object
         * @property {*}
         */
        this.html = null;
        /**
         * Style specifications for the object
         * @property {Style}
         */
        this.style = null;
        /** Width of the object
         * @property {number}
         */
        this.width = 0;
        /** Height of the object
         * @property {number}
         */
        this.height = 0;
        /**
         * previous width of the JCoreObject
         * @property {number}
         */
        this.oldWidth = 0;
        /**
         * previous height of the JCoreObject
         * @property {number}
         */
        this.oldHeight = 0;
        /** x coordinate of the object
         * @property {number}
         */
        this.x = 0;
        /** y coordinate of the object
         * @property {number}
         */
        this.y = 0;
        /**
         * previous x coordinate of the JCoreObject
         * @property {number}
         */
        this.oldX = 0;
        /**
         * previous y coordinate of the JCoreObject
         * @property {number}
         */
        this.oldY = 0;

        /**
         * The x coordinate relative to the canvas
         * @property {number}
         */
        this.absoluteX = 0;
        /**
         * The y coordinate relative to the canvas
         * @property {number}
         */
        this.absoluteY = 0;
        /**
         * Previous x coordinate relative to the canvas
         * @property {number}
         */
        this.oldAbsoluteX = 0;
        /**
         * Previous y coordinate relative to the canvas
         * @property {number}
         */
        this.oldAbsoluteY = 0;
        /**
         * Zoom in x
         * @property {number}
         */
        this.zoomX = 0;

        /**
         * Zoom in y
         * @property {number}
         */
        this.zoomY = 0;

        /**
         * Width after the zoom
         * @property {number}
         */
        this.zoomWidth = 0;

        /**
         * Height after the zoom
         * @property {number}
         */
        this.zoomHeight = 0;

        /** zOrder of the Canvas
         * @property {number}
         */
        this.zOrder = 1;
        /**
         * Denote whether the current object is visible or hidden
         * @property {boolean}
         */
        this.visible = true;
        /**
         * Property that encapsulates the drag behavior of the object
         * @property {DragBehavior}
         */
        this.dragBehavior = null;
        /**
         * Saved options is a copy of the default initializer extended
         * with the parameter <options>
         * @property {Object}
         */
        this.savedOptions = {};
        JCoreObject.prototype.initObject.call(this, options);

    };

    /**
     * Denotes the type of the object
     * @property {String}
     */
    JCoreObject.prototype.type = "JCoreObject";
    /**
     * Determines the family that an object is part of
     * @property {String}
     */
    JCoreObject.prototype.family = "CoreObject";
    /**
     * @abstract Method for applying the styles and preform tasks related to the
     * view of the object
     */
    JCoreObject.prototype.paint = function () {
    };

    /**
     * @abstract Method for attaching listeners for a given set of events to the
     * object
     */
    JCoreObject.prototype.attachListeners = function () {
    };
    /**
     * Initializes the element with the options given
     * @param {Object} options options for initializing the object
     */
    JCoreObject.prototype.initObject = function (options) {
        var defaultOptions = {
            id : (options && options.id) || Utils.generateUniqueId(),
            style : {
                cssProperties: {},
                cssClasses: []
            },
            width : 0,
            height : 0,
            x : 0,
            y : 0,
            zOrder : 1,
            visible : true,
            canvas : null
        };
        $.extend(true, defaultOptions, options);
        this.setID(defaultOptions.id)
            .setStyle(new Style({
                belongsTo: this,
                cssProperties: defaultOptions.style.cssProperties,
                cssClasses: defaultOptions.style.cssClasses
            }))
            .setCanvas(defaultOptions.canvas)
            .setDimension(defaultOptions.width, defaultOptions.height)
            .setPosition(defaultOptions.x, defaultOptions.y)
            .setZOrder(defaultOptions.zOrder)
            .setVisible(defaultOptions.visible)
            .setSavedOptions(defaultOptions);
    };

    /**
     * Creates a div element for the jCore object
     * @return {HTMLElement}
     */
    JCoreObject.prototype.createHTMLDiv = function () {
        return document.createElement("div");
    };
    /**
     * Creates the basic html node structure for the given object using its
     * previously defined properties
     * @return {HTMLElement}
     */
    JCoreObject.prototype.createHTML = function () {

        if (!this.html) {
            this.html = this.createHTMLDiv();
            this.html.id = this.id;

            // if this shape had some style saved in the init
            // then call apply style first
            this.style.applyStyle();

            this.style.addProperties({
                position: "absolute",
                left: this.zoomX,
                top: this.zoomY,
                width: this.zoomWidth,
                height: this.zoomHeight,
                zIndex: this.zOrder
            });

        }
        return this.html;
    };

    /**
     * Sets the position of the JCoreObject to a given pair of coordinates
     * @param {Number} newX new x coordinate for the JCoreObject
     * @param {Number} newY new y coordinate for the JCoreObject
     * @chainable
     */
    JCoreObject.prototype.setPosition = function (newX, newY) {
        this.setX(newX);
        this.setY(newY);
        return this;
    };

    /**
     * Sets the dimension of the JCoreObject to a given width and height
     * @param {Number} newWidth new width of the JCoreObject
     * @param {Number} newHeight new height of the JCoreObject
     * @chainable
     */
    JCoreObject.prototype.setDimension = function (newWidth, newHeight) {
        this.setWidth(newWidth);
        this.setHeight(newHeight);
        return this;
    };

    /**
     * Sets the x coordinate of the JCoreObject, returns true if successful
     * @param {Number} newX
     * @chainable
     */
    JCoreObject.prototype.setX = function (newX) {
        if (typeof newX === "number") {
            newX = Math.round(newX);
            this.x = newX;
            if (this.canvas) {
                this.zoomX = this.x * this.canvas.zoomFactor;
            } else {
                this.zoomX = this.x;
            }
            this.setAbsoluteX();
            if (this.html) {
                this.style.addProperties({left: this.zoomX});
//            this.html.style.left = this.zoomX + "px";
            }
        } else {
            throw new Error("setX : parameter newX is not a number");
        }

        return this;
    };
    /**
     * Sets the y coordinate of the JCoreObject, returns true if successful
     * @param {Number} newY
     * @chainable
     */
    JCoreObject.prototype.setY = function (newY) {
        if (typeof newY === "number") {
            newY = Math.round(newY);
            this.y = newY;
            if (this.canvas) {
                this.zoomY = this.y * this.canvas.zoomFactor;
            } else {
                this.zoomY = this.y;
            }
            this.setAbsoluteY();
            if (this.html) {
                this.style.addProperties({top: this.zoomY});
//            this.html.style.top = this.zoomY + "px";
            }
        }
        return this;
    };

    /**
     * Sets the x coordinate of the JCoreObject relative to the canvas
     * @chainable
     */
    JCoreObject.prototype.setAbsoluteX = function () {
        if (!this.parent) {
            this.absoluteX = this.zoomX;
        } else {
            this.absoluteX = this.zoomX + this.parent.absoluteX;
        }
        return this;
    };

    /**
     * Sets the value to an old X reference
     * @param {Number} newX
     * @chainable
     */
    JCoreObject.prototype.setOldX = function (newX) {
        if (typeof newX === "number") {
            this.oldX = newX;
        }
        return this;
    };
    /**
     * Sets the value to an old y reference
     * @param {Number} newY
     * @chainable
     */
    JCoreObject.prototype.setOldY = function (newY) {
        if (typeof newY === "number") {
            this.oldY = newY;
        }
        return this;
    };
    /**
     * Sets the y coordinate of the JCoreObject relative to the canvas
     * @chainable
     */
    JCoreObject.prototype.setAbsoluteY = function () {
        if (!this.parent) {
            this.absoluteY = this.zoomY;
        } else {
            this.absoluteY = this.zoomY + this.parent.absoluteY;
        }
        return this;
    };

    /**
     * Sets the width of the JCoreObject, returns true if successful
     * @param {Number} newWidth
     * @chainable
     */
    JCoreObject.prototype.setWidth = function (newWidth) {
        var intPart;
        if (typeof newWidth === "number" && newWidth >= 0) {
            this.width = newWidth;
            if (this.canvas) {
                this.zoomWidth = this.width * this.canvas.zoomFactor;
                intPart = Math.floor(this.zoomWidth);
                this.zoomWidth = (intPart % 2 === 0) ? intPart + 1 : intPart;
            } else {
                this.zoomWidth = this.width;
            }
            if (this.html) {
                this.style.addProperties({width: this.zoomWidth});
//            this.html.style.width = this.zoomWidth + "px";
            }
        }
        return this;
    };

    /**
     * Sets the height of the JCoreObject, returns true if successful
     * @param {Number} newHeight
     * @chainable
     */
    JCoreObject.prototype.setHeight = function (newHeight) {
        var intPart;
        if (typeof newHeight === "number" && newHeight >= 0) {
            this.height = newHeight;

            if (this.canvas) {
                this.zoomHeight = this.height * this.canvas.zoomFactor;
                intPart = Math.floor(this.zoomHeight);
                this.zoomHeight = (intPart % 2 === 0) ? intPart + 1 : intPart;
            } else {
                this.zoomHeight = this.height;
            }
            if (this.html) {
                this.style.addProperties({height: this.zoomHeight});
//            this.html.style.height = this.zoomHeight + "px";
            }
        }
        return this;
    };

    /**
     * Sets the zOrder of this element
     * @param {Number} newZOrder
     * @chainable
     */
    JCoreObject.prototype.setZOrder = function (newZOrder) {
        if (typeof newZOrder === "number" && newZOrder > 0) {
            this.zOrder = newZOrder;
            if (this.html) {
                this.style.addProperties({zIndex: this.zOrder});
//            this.html.style.zIndex = this.zOrder;
            }
        }
        return this;
    };

    /**
     * Sets the canvas for the current object
     * @param {Canvas} newCanvas
     * @returns {JCoreObject}
     */
    JCoreObject.prototype.setCanvas = function (newCanvas) {
        if (newCanvas && newCanvas.family === "Canvas") {
            this.canvas = newCanvas;
        }
        return this;
    };

    /**
     * Sets the style object for the current JCoreObject
     * @param {Style} newStyle
     * @chainable
     */
    JCoreObject.prototype.setStyle = function (newStyle) {
        if (newStyle.type && newStyle.type === "Style") {
            this.style = newStyle;
        }
        return this;
    };
    /**
     * Sets this element to be visible or not if it has html, just sets the display
     * property to inline or none
     * @param {boolean} newVisible
     * @chainable
     */
    JCoreObject.prototype.setVisible = function (newVisible) {

        if (typeof newVisible === "boolean") {
            this.visible = newVisible;
            if (this.html) {
                if (newVisible) {
                    this.style.addProperties({display: "inline"});
//                this.html.style.display = "inline";
                } else {
                    this.style.addProperties({display: "none"});
//                this.html.style.display = "none";
                }
            }
        }
        return this;
    };
    /**
     * Sets the id of this element and updates the html if there is such
     * @param {String} newID
     * @chainable
     */
    JCoreObject.prototype.setID = function (newID) {
        this.id = newID;
        if (this.html) {
            this.html.id = this.id;
        }
        return this;
    };
    /**
     * Returns the ID of this JCoreObject
     * @returns {String}
     */
    JCoreObject.prototype.getID = function () {
        return this.id;
    };
    /**
     * Returns the HTML Representation,  if there is none, then creates one with
     * the current properties of the object
     * @returns {HTMLElement}
     */
    JCoreObject.prototype.getHTML = function () {
        if (this.html === null) {
            return this.createHTML();
        }
        return this.html;
    };
    /**
     * Returns the zOrder of this JCoreObject
     * @returns {Number}
     */
    JCoreObject.prototype.getZOrder = function () {
        return this.zOrder;
    };
    /**
     * Returns the canvas related to this object
     * @returns {Canvas}
     */
    JCoreObject.prototype.getCanvas = function () {
        return this.canvas;
    };

    /**
     * Returns the width of the JCoreObject
     * @returns {Number}
     */
    JCoreObject.prototype.getWidth = function () {
        return this.width;
    };

    /**
     * Returns the height of the JCoreObject
     * @returns {Number}
     */
    JCoreObject.prototype.getHeight = function () {
        return this.height;
    };
    /**
     * Returns the x coordinate of the JCoreObject
     * @returns {Number}
     */
    JCoreObject.prototype.getX = function () {
        return this.x;
    };
    /**
     * Returns the y coordinate of the JCoreObject
     * @returns {Number}
     */
    JCoreObject.prototype.getY = function () {
        return this.y;
    };

    /**
     * Returns the x coordinate relative to the canvas of this object
     * @return {Number}
     */
    JCoreObject.prototype.getAbsoluteX = function () {
        return this.absoluteX;
    };
    /**
     * Returns the y coordinate relative to the canvas of this object
     * @return {Number}
     */
    JCoreObject.prototype.getAbsoluteY = function () {
        return this.absoluteY;
    };

    /**
     * Returns the style of this JCoreObject
     * @return {Style}
     */
    JCoreObject.prototype.getStyle = function () {
        return this.style;
    };
    /**
     * Returns if the object is visible or not
     * @return {Boolean}
     */
    JCoreObject.prototype.getVisible = function () {
        return this.visible;
    };
    /**
     * Gets the x coordinate relative to the zoom scale
     * @return {Number}
     */
    JCoreObject.prototype.getZoomX = function () {
        return this.zoomX;
    };

    /**
     * Gets the y coordinate relative to the zoom scale
     * @return {Number}
     */
    JCoreObject.prototype.getZoomY = function () {
        return this.zoomY;
    };

    /**
     * Gets the width relative to the zoom scale
     * @return {Number}
     */
    JCoreObject.prototype.getZoomWidth = function () {
        return this.zoomWidth;
    };

    /**
     * Gets the height relative to the zoom scale
     * @return {Number}
     */
    JCoreObject.prototype.getZoomHeight = function () {
        return this.zoomHeight;
    };

    /**
     * Retrieves the previous value for coordinate x
     * @return {Number}
     */
    JCoreObject.prototype.getOldX = function () {
        return this.oldX;
    };

    /**
     * Retrieves the previous value for coordinate y
     * @return {Number}
     */
    JCoreObject.prototype.getOldY = function () {
        return this.oldY;
    };

    /**
     * Retrieves the previous value for width
     * @return {Number}
     */
    JCoreObject.prototype.getOldWidth = function () {
        return this.oldWidth;
    };

    /**
     * Retrieves the previous value for height
     * @return {Number}
     */
    JCoreObject.prototype.getOldHeight = function () {
        return this.oldHeight;
    };

    /**
     * Save the options (options is the defaults options extension with the
     * user parameter for default initialization)
     * @param {Object} options
     */
    JCoreObject.prototype.setSavedOptions = function (options) {
        this.savedOptions = options;
        return this;
    };

    /**
     * Stringifies the basic data of this shape and the drag behavior of this shape
     * @return {Object}
     */
    JCoreObject.prototype.stringify = function () {
        return {
            id: this.getID(),
            x: this.getX(),
            y: this.getY(),
            width: this.getWidth(),
            height: this.getHeight(),
//        family: this.family,
            type: this.type,
            style: this.getStyle().stringify(),
            drag: this.savedOptions.drag
        };
    };
    /**
     * JSON parser for creating JCoreObjects
     * @param {Object} json
     */
    JCoreObject.prototype.parseJSON = function (json) {
        this.initObject(json);
    };
    /**
     * Number that represents the top direction
     * @property {number}
     */
    JCoreObject.prototype.TOP = 0;
    /**
     * Number that represents the right direction
     * @property {number}
     */
    JCoreObject.prototype.RIGHT = 1;
    /**
     * Number that represents the bottom direction
     * @property {Number}
     */
    JCoreObject.prototype.BOTTOM = 2;
    /**
     * Number that represents the left direction
     * @property {Number}
     */
    JCoreObject.prototype.LEFT = 3;
    /**
     * Number that represents horizontal direction
     * @property {Number}
     */
    JCoreObject.prototype.HORIZONTAL = 0;
    /**
     * Number that represents  vertical direction
     * @property {Number}
     */
    JCoreObject.prototype.VERTICAL = 1;
    /**
     * Number of zoom scales available
     * @property {Number}
     */
    JCoreObject.prototype.ZOOMSCALES = 5;

    /**
     * @abstract
     * @class Handler
     * Abstract class which provides methods to represent a handler.
     * @extends JCoreObject
     *
     * @constructor Creates an instance of the class Handler (for inheritance purposes only).
     * @param {Object} options Initialization options.
     */
    Handler = function (options) {
        JCoreObject.call(this, options);

        /**
         * Representation of this handler.
         * @property {Object}
         */
        this.representation = null;

        /**
         * The parent of this handler.
         * @property {Shape}
         */
        this.parent = null;

        /**
         * Color of this handler.
         * @property {Color}
         */
        this.color = null;

        /**
         * The orientation of this handler.
         * @property {string}
         */
        this.orientation = null;
    };

    Handler.prototype = new JCoreObject();

    /**
     * Sets the parent of this handler
     * @param newParent
     * @chainable
     */
    Handler.prototype.setParent = function (newParent) {
        this.parent = newParent;
        return this;
    };

    /**
     * Gets the parent of this handler
     * @return {Shape}
     */
    Handler.prototype.getParent = function () {
        return this.parent;
    };

    /**
     * Sets the representation of this handler
     * @param representation
     * @chainable
     */
    Handler.prototype.setRepresentation = function (representation) {
        this.representation = representation;
        return this;
    };

    /**
     * Gets the representation of this handler
     * @return {Object}
     */
    Handler.prototype.getRepresentation = function () {
        return this.representation;
    };

    /**
     * Sets the orientation of this handler
     * @param newOrientation
     * @chainable
     */
    Handler.prototype.setOrientation = function (newOrientation) {
        this.orientation = newOrientation;
        return this;
    };

    /**
     * Gets the orientation of this handler
     * @return {string}
     */
    Handler.prototype.getOrientation = function () {
        return this.orientation;
    };

    /**
     * Paint the handler method which will call `this.representation.paint()`
     * @chainable
     */
    Handler.prototype.paint = function () {
        // paint the representation (by default a rectangle)
        this.representation.paint.call(this);

        // apply predefined style
        this.style.applyStyle();

        return this;
    };

    /**
     * The color representation of this object
     * @param {Color} newColor
     * @chainable
     */
    Handler.prototype.setColor = function (newColor) {
        this.color = newColor;
        return this;
    };

    /**
     * Get the color representation of this object
     * @return {Color}
     */
    Handler.prototype.getColor = function () {
        return this.color;
    };

    /**
     * @class ResizeHandler
     * Defines a class resize handler to represent handlers used with jQueryUI' resizable plugin, currently
     * it has only support for rectangle resize handler (oval resize handlers were implemented but apparently
     * jQueryUI won't accept a child of the designated HTMLElement to be used as the resize handler).
     *
     * An example of use:
     *
     *      // i.e.
     *      // let's assume that shape is an instance of the class Shape
     *      // let's assume that rectangle is an instance of the class Rectangle
     *
     *      var resizableStyle = {
 *              cssProperties: {
 *                  'background-color': "rgb(0, 255, 0)",
 *                  'border': '1px solid black'
 *              }
 *          },
     *          nonResizableStyle = {
 *              cssProperties: {
 *                  'background-color': "white",
 *                  'border': '1px solid black'
 *              }
 *          },
     *          resizeHandler;
     *
     *      resizeHandler = new ResizeHandler({
 *          width: 8,
 *          height: 8,
 *          parent: shape,
 *          orientation: 'nw'                   // see jQueryUI's resizable plugin 'handles' option
 *          representation: rectangle,
 *          resizableStyle: resizableStyle,
 *          nonResizableStyle: nonResizableStyle,
 *          zOrder: 2
 *      });
     *
     * @extend Handler
     * @constructor Creates an instance of resize handler.
     * @param {Object} options
     * @cfg {number} [width=4] The width of this resize handler.
     * @cfg {number} [height=4] The height of this resize handler.
     * @cfg {Shape} [parent=null] The parent of this resize handler.
     * @cfg {string} [orientation=null] The orientation of this resize handler.
     * @cfg {string} [representation=null] The representation of this resize handler.
     * @cfg {Object} [resizableStyle={}] The parameters to create an instance of the class Style used
     * when the object is resizable.
     * @cfg {Object} [nonResizableStyle={}] The parameters to create an instance of the class Style used
     * when the object is not resizable.
     * @cfg {number} [zOrder=2] The z-index of this resize handler.
     */
    ResizeHandler = function (options) {

        Handler.call(this, options);

        /**
         * Category of this resize handler
         * @type {"resizable"/"nonresizable"}
         */
        this.category = null;

        /**
         * Denotes whether the resize handle is visible or not.
         * @property boolean
         */
        this.visible = false;

        /**
         * JSON used to create an instance of the class Style used when the object is resizable.
         * @property {Object}
         */
        this.resizableStyle = null;

        /**
         * JSON used to create an instance of the class Style used when the object is not resizable.
         * @property {Object}
         */
        this.nonResizableStyle = null;

        // set defaults
        ResizeHandler.prototype.initObject.call(this, options);
    };

    ResizeHandler.prototype = new Handler();

    /**
     * The type of each instance of this class.
     * @property {String}
     */
    ResizeHandler.prototype.type = "ResizeHandler";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} options The object that contains the config
     * @private
     */
    ResizeHandler.prototype.initObject = function (options) {

        var defaults = {
            width: 4,
            height: 4,
            parent: null,
            orientation: null,
            representation: null,
            resizableStyle: {},
            nonResizableStyle: {},
            zOrder: 2
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaults, options);

        // add default zIndex to this handler
        if (defaults.resizableStyle.cssProperties) {
            defaults.resizableStyle.cssProperties.zIndex = defaults.zOrder;
        }
        if (defaults.nonResizableStyle.cssProperties) {
            defaults.nonResizableStyle.cssProperties.zIndex = defaults.zOrder;
        }

        // init
        this.setParent(defaults.parent)
            .setWidth(defaults.width)
            .setHeight(defaults.height)
            .setOrientation(defaults.orientation)
            .setRepresentation(defaults.representation)
            .setResizableStyle(defaults.resizableStyle)
            .setNonResizableStyle(defaults.nonResizableStyle);

        // create the id
        this.id = defaults.orientation + defaults.parent.id + "resizehandler";
    };

    /**
     * Sets the parent of this handler
     * @param {Shape} newParent
     * @chainable
     */
    ResizeHandler.prototype.setParent = function (newParent) {
        this.parent = newParent;
        return this;
    };

    /**
     * Gets the parent of this handler.
     * @return {Shape}
     */
    ResizeHandler.prototype.getParent = function () {
        return this.parent;
    };

    /**
     * Paints this resize handler by calling it's parent's `paint` and setting
     * the visibility of this resize handler
     * @chainable
     */
    ResizeHandler.prototype.paint = function () {
        if (!this.html) {
            throw new Error("paint(): This handler has no html");
        }

        // this line paints the representation (by default a rectangle)
        Handler.prototype.paint.call(this);

        this.setVisible(this.visible);
        return this;
    };

    /**
     * Sets the category of the resizeHandler (also adds the needed class to
     * make the element resizable)
     * @param newCategory
     * @chainable
     */
    ResizeHandler.prototype.setCategory = function (newCategory) {
        if (typeof newCategory === "string") {
            this.category = newCategory;
        }
        if (this.category === "resizable") {
            this.color = new Color(0, 255, 0);
            this.style.addClasses([
                "ui-resizable-handle", "ui-resizable-" + this.orientation
            ]);
        } else {
            this.color = new Color(255, 255, 255);
            this.style.removeClasses([
                "ui-resizable-handle", "ui-resizable-" + this.orientation
            ]);
        }
        return this;
    };


    /**
     * Sets the resizable style of this shape by creating an instance of the class Style
     * @param {Object} style
     * @chainable
     */
    ResizeHandler.prototype.setResizableStyle = function (style) {
        this.resizableStyle = new Style({
            belongsTo: this,
            cssProperties: style.cssProperties,
            cssClasses: style.cssClasses
        });
        return this;
    };

    /**
     * Sets the non resizable style for this shape by creating an instance of the class Style
     * @param {Object} style
     * @chainable
     */
    ResizeHandler.prototype.setNonResizableStyle = function (style) {
        this.nonResizableStyle = new Style({
            belongsTo: this,
            cssProperties: style.cssProperties,
            cssClasses: style.cssClasses
        });
        return this;
    };

    /**
     * @class SegmentMoveHandler
     * Represents the handler to move a segment (the handlers are visible when a decorator of the parent of this
     * segment is clicked on)
     *
     * An example of use:
     *
     *      // i.e.
     *      // let's assume that segment is an instance of the class Segment
     *      // let's assume that rectangle is an instance of the class Rectangle
     *
     *      segmentMoveHandler = new SegmentMoveHandler({
 *          width: 8,
 *          height: 8,
 *          parent: segment,
 *          orientation: 0                      // corresponds to a vertical segment
 *          representation: rectangle,
 *          color: new Color(255, 0, 0)         // red !!
 *      });
     * @extend Handler
     * @constructor Creates an instance of the class SegmentMoveHandler
     * @param {Object} options
     * @cfg {number} [width=4] The width of this segment move handler.
     * @cfg {number} [height=4] The height of this segment move handler.
     * @cfg {Shape} [parent=null] The parent of this segment move handler.
     * @cfg {number} [orientation=null] The orientation of this segment move handler.
     * @cfg {string} [representation=null] The representation of this segment move handler.
     * @cfg {number} [color=new Color(0, 255, 0)] The color of this segment move handler (green).
     */
    SegmentMoveHandler = function (options) {

        Handler.call(this, options);

        /**
         * Orientation of this segment move handler (useful to do the drag).
         * @property {number}
         */
        this.orientation = null;

        /**
         * Denotes whether the SegmentMove point is visible or not.
         * @property {boolean} [visible=false]
         */
        this.visible = false;

        /**
         * The default zOrder of this handler.
         * @property {number} [zOrder=2]
         */
        this.zOrder = 2;

        // set defaults
        SegmentMoveHandler.prototype.initObject.call(this, options);
    };

    SegmentMoveHandler.prototype = new Handler();

    /**
     * Type of each instance of this class
     * @property {String}
     */
    SegmentMoveHandler.prototype.type = "SegmentMoveHandler";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} options The object that contains the config
     * @private
     */
    SegmentMoveHandler.prototype.initObject = function (options) {

        var defaults = {
            width: 4,
            height: 4,
            parent: null,
            orientation: null,
            representation : new Rectangle(),
            color: new Color(0, 255, 0)
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaults, options);

        // init
        this.setWidth(defaults.width)
            .setHeight(defaults.height)
            .setParent(defaults.parent)
            .setColor(defaults.color)
            .setOrientation(defaults.orientation)
            .setRepresentation(defaults.representation);
    };

    /**
     * Paints this resize handler by calling it's parent's `paint` and setting
     * the visibility of this resize handler.
     * @chainable
     */
    SegmentMoveHandler.prototype.paint = function () {
        // before it was: Rectangle.prototype.paint.call(this);
        Handler.prototype.paint.call(this);
        this.setVisible(this.visible);
        return this;
    };

    /**
     * Attaches listeners to the segmentMoveHandler, by default it creates the click,
     * mouseDown and draggable events.
     * @param {SegmentMoveHandler} handler
     * @chainable
     */
    SegmentMoveHandler.prototype.attachListeners = function (handler) {
        var $handler = $(handler.html);
        $handler.on('click', handler.onClick(handler));
        $handler.on('mousedown', handler.onMouseDown(handler));
        $handler.draggable({
            start: handler.onDragStart(handler),
            drag: handler.onDrag(handler),
            stop: handler.onDragEnd(handler),
            axis: (handler.orientation === handler.HORIZONTAL) ? "y" : "x"
            //containment: handler.parent.parent.html
        });
        return this;
    };

    /**
     * @event mousedown
     * MouseDown callback fired when the user mouse downs on the `handler`
     * @param {SegmentMoveHandler} handler
     */
    SegmentMoveHandler.prototype.onMouseDown = function (handler) {
        return function (e, ui) {
            // This is done to avoid the start of a selection in the canvas
            // handler > segment > connection > canvas
            handler.parent.parent.canvas.draggingASegmentHandler = true;
            //e.stopPropagation();
        };
    };

    /**
     * @event click
     * Click callback fired when the user clicks on the handler
     * @param {SegmentMoveHandler} handler
     */
    SegmentMoveHandler.prototype.onClick = function (handler) {
        return function (e, ui) {
            e.stopPropagation();
        };
    };

    /**
     * @event dragStart
     * DragStart callback fired when the handler is dragged (it's executed only once).
     * It does the following:
     *
     * 1. Gather the connection by calling the handler's grandparent
     * 2. Save the state if the connection (for the undo-redo stack)
     * 3. Clear all the intersections of each segment of the connection
     *
     * @param {SegmentMoveHandler} handler
     */
    SegmentMoveHandler.prototype.onDragStart = function (handler) {
        return function (e, ui) {
            var parentSegment = handler.parent,
                segment,
                connection = parentSegment.getParent(),
                i;

            // TESTING:
            // save values for the undo-redo stack
            connection.savePoints({
                saveToOldPoints: true
            });

            // clear all intersections that exists in
            // parentSegment.parent (connection)
            for (i = 0; i < parentSegment.parent.lineSegments.getSize(); i += 1) {
                segment = parentSegment.parent.lineSegments.get(i);
                segment.clearIntersections();
            }
            // clear all intersections that exists among other connections and
            // parentSegment (the ones that exists in the other connections)
            parentSegment.parent.clearAllIntersections();
            e.stopPropagation();
        };
    };

    /**
     * @event drag
     * Drag callback fired when the handler is being dragged.
     * It only moves the segment vertically or horizontally.
     *
     * @param {SegmentMoveHandler} handler
     */
    SegmentMoveHandler.prototype.onDrag = function (handler) {
        return function (e, ui) {
            var parentSegment = handler.parent;
            parentSegment.moveSegment(ui.position.left, ui.position.top);
        };
    };

    /**
     * @event dragEnd
     * DragEnd callback fired when the handler stops being dragged.
     * It does the following:
     *
     * 1. Gather the connection by calling the handler's grandparent
     * 2. Save the state if the connection (for the undo-redo stack)
     * 3. Create a command for the undo-redo stack
     *
     * @param {SegmentMoveHandler} handler
     */
    SegmentMoveHandler.prototype.onDragEnd = function (handler) {
        return function (e, ui) {
            var parentSegment = handler.parent,
                connection = parentSegment.getParent(),
                canvas = connection.canvas,
                command;

            canvas.draggingASegmentHandler = false;
            handler.onDrag(handler)(e, ui);

            // LOGIC: connection.points is an array of points that is not updated
            // automatically when a connection is painted, it must be
            // explicitly called as connection.savePoints()
            connection.savePoints();
            command = new CommandSegmentMove(connection, {
                oldPoints: connection.getOldPoints(),
                newPoints: connection.getPoints()
            });
            command.execute();
            canvas.commandStack.add(command);

        };
    };

    /**
     * @class Port
     * Class Port represent a special point in a shape where each point is one end point of a connection
     * (a customShape has many ports and each port has a reference to the connection it belongs to).
     *
     * The relation of this class with customShape and connections are described below:
     *
     * - Each port is one end point of a connection (the connection has links to the start port and the end port,
     *      the port has a link to the connection)
     * - A custom shape might have *n* ports (the parent of the port is the custom shape)
     *      so the custom shape has the HTML of the port on it.
     *
     * Some examples of usage:
     *
     *      // let's assume the connection is an instance of the class Connection
     *      // let's assume the customShape is an instance of the class CustomShape
     *      var port = new Port({
 *          width: 8,
 *          height: 8,
 *          visible: true,
 *          parent: customShape
 *      })
     *
     *      // after a port is created, it need to be added to the customShape
     *      // let's add it at position [100, 100]
     *      customShape.addPort(port, 100, 100)
     *
     *      // finally when a connection is created it needs to have links to the ports
     *      // let's assume that another port is an instance of the class Port
     *      // i.e
     *      connection = new Connection({
 *          srcPort: port,
 *          destPort: anotherPort,
 *          segmentColor: new Color(0, 200, 0),
 *          segmentStyle: "regular"
 *      });
     *
     * @extend JCoreObject
     *
     *
     * @param {Object} options Initialization options
     * @cfg {number} [width=4] The width of this port
     * @cfg {number} [height=4] The height of this port
     * @cfg {boolean} [visible=false] The visibility of this port
     * @cfg {CustomShape} [parent=null] The parent of this port
     *
     * @constructor Creates an instance of the class Port
     */
    Port = function (options) {

        JCoreObject.call(this);

        /**
         * Connection to whom this port belongs to
         * @property {Connection}
         */
        this.connection = null;

        /**
         * Representation (Shape) of the port when it is connected (currently it's represented as an {@link Oval})
         * @property {Shape}
         */
        this.representation = null;

        /**
         * Parent of this port.
         * @property {CustomShape}
         */
        this.parent = null;

        /**
         * Old parent of this port.
         * @property {CustomShape}
         */
        this.oldParent = null;

        /**
         * Port direction respect to its parent (its parent is an instance of {@link CustomShape}).
         * @property {number}
         */
        this.direction = null;

        /**
         * The percentage relative to where the port is located regarding one of
         * the shape dimensions (useful to recalculate the ports position while resizing).
         * @property {number}
         */
        this.percentage = null;

        /**
         * Current zIndex of the port.
         * @property {number} [zOrder=1]
         */
        this.zOrder = 1;

        /**
         * Default zIndex of the ports.
         * @property {number} [defaultZOrder=1]
         */
        this.defaultZOrder = 1;

        /**
         * X coordinate sent to the database
         * @property {number} [realX=0]
         */
        this.realX = 0;
        /**
         * Y coordinate sent to the database
         * @property {number} [realY=0]
         */
        this.realY = 0;

        this.dragging = false;

        Port.prototype.initObject.call(this, options);
    };

    Port.prototype = new JCoreObject();

    /**
     * The distance moved when a connection is selected (when a connection is
     * selected the ports move towards the center of the shape so that it's
     * easier to drag the ports)
     * @property {number} [TOWARDS_CENTER=5]
     */
    Port.prototype.TOWARDS_CENTER = 5;

    /**
     * Type of each instance of this class
     * @property {String}
     */
    Port.prototype.type = "Port";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance.
     * @param {Object} options The object that contains the config
     * @private
     */
    Port.prototype.initObject = function (options) {
        /**
         * Default options for the object
         * @property {Object}
         */
        var defaults = {
            width: 8,
            height: 8,
            visible: false,
            parent: null
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaults, options);
        $.extend(true, defaults, {
            // oval is initialized with default values
            representation: new Oval({
                width: defaults.width,
                height: defaults.height,
                center: new Point(0, 0),
                visible: true
            })
        });

        // call setters using the defaults object
        this.setVisible(defaults.visible)
            .setParent(defaults.parent)
            .setDimension(defaults.width, defaults.height)
            .setRepresentation(defaults.representation);
    };

    /**
     * Creates the HTML Representation of the Port
     * @returns {HTMLElement}
     */
    Port.prototype.createHTML = function () {
        JCoreObject.prototype.createHTML.call(this);
        this.html.style.background = 'red';
        this.html.style.borderRadius = '1em'; // w3c
        this.html.style.MozBorderRadius = '1em'; // mozilla

        this.style.addClasses(["port"]);
        return this.html;
    };

    /**
     * Moves this port (considering the borders) after executing the zoom operation.
     * @param {number} positive
     * @chainable
     */
    Port.prototype.applyBorderMargin = function (positive) {
        var factor = (positive) ? 1 : -1;
        this.x += factor * this.parent.border[this.direction].x;
        this.y += factor * this.parent.border[this.direction].y;
        this.zoomX = this.x;
        this.zoomY = this.y;
        this.setAbsoluteX();
        this.setAbsoluteY();

        if (this.html) {
            this.style.addProperties({
                left: this.zoomX,
                top: this.zoomY
            });
        }
        return this;
    };

    /**
     * Sets the x coordinate of this port
     * @param {number} newX
     * @chainable
     */
    Port.prototype.setX = function (newX) {
        this.x = newX;
        this.zoomX = this.x;
        if (this.canvas) {
            this.realX = this.x / this.canvas.zoomFactor;
        } else {
            this.realX = this.x;
        }
        this.setAbsoluteX();
        if (this.html) {
            this.style.addProperties({left: this.zoomX});
//            this.html.style.top = this.zoomY + "px";
        }
        return this;
    };

    /**
     * Sets the y coordinate of this port
     * @param {number} newY
     * @chainable
     */
    Port.prototype.setY = function (newY) {
        this.y = newY;
        this.zoomY = this.y;
        this.setAbsoluteY();
        if (this.canvas) {
            this.realY = this.y / this.canvas.zoomFactor;
        } else {
            this.realY = this.y;
        }
        if (this.html) {
            this.style.addProperties({top: this.zoomY});
//            this.html.style.top = this.zoomY + "px";
        }
        return this;
    };

    /**
     * Sets the width of this port
     * @param {number} newWidth
     * @chainable
     */
    Port.prototype.setWidth = function (newWidth) {
        this.width = newWidth;
        this.zoomWidth = this.width;
        if (this.html) {
            this.style.addProperties({width: this.zoomWidth});
//            this.html.style.width = this.zoomWidth + "px";
        }
        return this;
    };

    /**
     * Sets the width of this port
     * @param {number} newHeight
     * @chainable
     */
    Port.prototype.setHeight = function (newHeight) {
        this.height = newHeight;
        this.zoomHeight = this.height;
        if (this.html) {
            this.style.addProperties({height: this.zoomHeight});
//            this.html.style.width = this.zoomWidth + "px";
        }
        return this;
    };

    /**
     * Paint the port appending its `representation` HTML to `this` HTML.
     * @chainable
     */
    Port.prototype.paint = function () {

        // this line is reworked:
        // original: Oval.prototype.paint.call(this);
        //this.html.appendChild(this.representation.getHTML());
        //this.representation.paint();

        // sets the visibility of this port
        this.setVisible(this.visible);

        // apply predefined style
        this.style.applyStyle();

        return this;
    };

    /**
     * Repaints the port re-applying its left and top position.
     * @param {Port} port
     * @chainable
     */
    Port.prototype.repaint = function (port) {

        port.style.addProperties({
            left: port.x,
            top: port.y
        });
        port.connection.disconnect();
        port.connection.connect();
        port.connection.setSegmentMoveHandlers();
        port.connection.checkAndCreateIntersectionsWithAll();
        return this;
    };

    /**
     * @event dragStart
     * DragStart callback fired when the port is dragged (it's executed only once).
     * It does the following:
     *
     * 1. Moves the port away from the center
     * 2. Moves the otherPort away from the center
     * 3. Disconnects the connection
     *
     * @param {Port} port
     * @param {Port} otherPort
     */
    Port.prototype.onDragStart = function (port, otherPort) {
        return function (e, ui) {

            // move the ports off the center, they'll be correctly repositioned
            // later (in "onDragEnd")
            //otherPort.moveTowardsTheCenter(true);
            //port.moveTowardsTheCenter(true);
            port.dragging = true;
            port.setColor('#40CA09');
            port.connection.disconnect();
            return true;
        };
    };

    /**
     * @event drag
     * Drag callback fired when the port is being dragged.
     * It makes a new segment from the other port to the current position of the mouse.
     *
     * @param {Port} port
     * @param {Point} endPoint
     * @param {Port} otherPort
     * @param {Canvas} canvas
     */
    Port.prototype.onDrag = function (port, endPoint, otherPort, canvas) {
        return function (e, ui) {
            if (canvas.connectionSegment) {
                $(canvas.connectionSegment.getHTML()).remove();
            }

            endPoint.x = e.pageX - canvas.getX() + canvas.getLeftScroll();
            endPoint.y = e.pageY - canvas.getY() + canvas.getTopScroll();
            //make connection segment
            canvas.connectionSegment = new Segment({
                startPoint : otherPort.getPoint(false),
                endPoint : endPoint,
                parent : canvas
            });
            canvas.connectionSegment.pointsTo = port;
            canvas.connectionSegment.createHTML();
            canvas.connectionSegment.paint();
        };
    };

    /**
     * @event dragEnd
     * DragEnd callback fired when the port stops being dragged.
     * It does the following:
     *
     * 1. Repaints the port
     * 2. Moves otherPort towards the center of the shape
     * 3. Moves port towards the center of the shape
     * 4. Shows the handlers of the connection
     *
     * @param {Port} port
     * @param {Port} otherPort
     * @param {Canvas} canvas
     */
    Port.prototype.onDragEnd = function (port, otherPort, canvas) {
        return function (e, ui) {

            if (canvas.connectionSegment) {
                $(canvas.connectionSegment.getHTML()).remove();
            }
            port.repaint(port);

            // move the ports towards the center of its parent
            // (they were moved off the center in "onDragStart")
            //otherPort.moveTowardsTheCenter();
            //port.moveTowardsTheCenter();

            // show the segmentMoveHandlers
            // port.connection.showMoveHandlers();
            port.connection.showPortsAndHandlers();
            canvas.currentConnection = port.connection;
            port.dragging = false;
            port.setColor('red');
        };
    };

    /**
     * Determine the percentage relative to the shape where the port is located.
     * The range of `this.percentage` is from 0 to 1 (inclusive).
     * @return {boolean}
     */
    Port.prototype.determinePercentage = function () {
        //Shape and port dimension to consider, it can be either width or height
        var shapeDimension,
            portDimension;
        if (!this.parent) {
            return false;
        }
        if (this.direction === this.TOP || this.direction === this.BOTTOM) {
            shapeDimension = this.parent.getZoomWidth();
            portDimension = this.x;
        } else {
            shapeDimension = this.parent.getZoomHeight();
            portDimension = this.y;
        }

        this.percentage = Math.round((portDimension / shapeDimension) * 100.0);
        return true;
    };

    /**
     * Shows this port (moving it's HTML representation towards the center for easy dragging).
     * @chainable
     */
    Port.prototype.show = function () {
        this.visible = true;

        this.paint();
        this.html.style.zIndex = 3;
        // move the ports towards the center
        //this.moveTowardsTheCenter();

        return this;
    };

    /**
     * Hides this port (moving it's HTML representation off the center of the shape).
     * @chainable
     */
    Port.prototype.hide = function () {
        this.visible = false;

        this.paint();
        this.html.style.zIndex = 1;

        // move the ports off the center of the shape
        //this.moveTowardsTheCenter(true);  //reverse: true

        return this;
    };

    /**
     * Detaches the HTML of the port from the DOM (saving it in `this.html`), it also removes the port
     * from its parent.
     * @chainable
     */
    Port.prototype.saveAndDestroy = function () {
        this.parent.removePort(this);  //remove from shape

        // save the html but detach it from the DOM
        this.html = $(this.html).detach()[0];
        return this;
    };

    /**
     * Attaches event listeners to this port, currently it has the draggable and mouse over events.
     * @param {Port} currPort
     * @return {Port}
     */
    Port.prototype.attachListeners = function (currPort) {
        var otherPort,
            portDragOptions;
        otherPort = currPort.connection.srcPort.getPoint(false)
            .equals(currPort.getPoint(false)) ? currPort.connection.destPort :
            currPort.connection.srcPort;

        portDragOptions = {
            //containment : "parent"
            start : currPort.onDragStart(currPort, otherPort),
            drag : currPort.onDrag(currPort, currPort.getPoint(false),
                otherPort, currPort.parent.canvas),
            stop : currPort.onDragEnd(currPort, otherPort, currPort.parent.canvas)

            //revert: false,
            //revertDuration: 0

        };
        $(currPort.html).draggable(portDragOptions);

        $(currPort.html).mouseover(
            function () {
                currPort.setColor('#40CA09');
                $(currPort.html).css('cursor', 'Move');

            }
        );

        $(currPort.html).mouseleave(
            function () {
                if(!currPort.dragging) {
                    currPort.setColor('red');
                }

            }
        );

        return currPort;
    };
    /**
     * Sets the Port color.
     * @param {String} newColor
     * @chainable
     */
    Port.prototype.setColor = function (newColor) {
        this.html.style.background = newColor;
        return this;
    };
    /**
     * Moves a port towards or off the center (for easy dragging).
     * @param {boolean} reverse If it's set to true then it will move it off the center
     * @chainable
     */
    Port.prototype.moveTowardsTheCenter = function (reverse) {
        var towardsCenterDistance = Port.prototype.TOWARDS_CENTER,
            dx = [0, -towardsCenterDistance, 0, towardsCenterDistance],
            dy = [towardsCenterDistance, 0, -towardsCenterDistance, 0],
            multiplier = 1;

        if (reverse) {
            multiplier = -1;
        }
        this.setPosition(this.x + dx[this.direction] * multiplier,
            this.y + dy[this.direction] * multiplier);
        return this;
    };

    /**
     * Sets the Direction to the port.
     * @param {number} newDirection
     * @chainable
     */
    Port.prototype.setDirection = function (newDirection) {
        if (newDirection >= 0 && newDirection < 4) {
            this.direction = newDirection;
        } else {
            throw new Error("setDirection(): parameter '" + newDirection +
            "'is not valid");
        }
        return this;
    };

    /**
     * Get the direction to the port. (0 = TOP, 1 = RIGHT, 2 = BOTTOM, 3 = LEFT)
     * @returns {number}
     */
    Port.prototype.getDirection = function () {
        return this.direction;
    };

    /**
     * Sets the parent of the port.
     * @param {Shape} newParent
     * @param {boolean} triggerChange If set to true it'll fire {@link Canvas#event-changeelement}
     * @chainable
     */
    Port.prototype.setParent = function (newParent, triggerChange) {
        //if(newParent.type === "Shape" || newParent.type === "StartEvent" ||
        //newParent.type === "EndEvent")
        if (this.canvas && triggerChange) {
            this.canvas.updatedElement = {
                "id" : this.id,
                "type" : this.type,
                "fields" : [{
                    "field" : "parent",
                    "oldVal" : this.parent,
                    "newVal" : newParent
                }]
            };
            $(this.canvas.html).trigger("changeelement");
        }
        this.parent = newParent;
        return this;
    };

    /**
     * Gets the parent of the port.
     * @return {Port}
     */
    Port.prototype.getParent = function () {
        return this.parent;
    };

    /**
     * Sets the old parent of this port
     * @param {CustomShape} parent
     * @chainable
     */
    Port.prototype.setOldParent = function (parent) {
        this.oldParent = parent;
        return this;
    };
    /**
     * Gets the old parent of this port.
     * @return {Port}
     */
    Port.prototype.getOldParent = function () {
        return this.oldParent;
    };

    /**
     * Sets the connection associated with this port.
     * @param {Connection} newConn
     * @chainable
     */
    Port.prototype.setConnection = function (newConn) {
        if (newConn && newConn.family === "Connection") {
            this.connection = newConn;
        } else {
            throw new Error("setConnection(): parameter is not valid");
        }
        return this;
    };

    /**
     * Gets the connection associated with this port
     * @returns {Connection}
     */
    Port.prototype.getConnection = function () {
        return this.connection;
    };

    /**
     * Returns the representation of the port (currently an instance of the class {@link Oval})
     * @returns {Oval}
     */
    Port.prototype.getRepresentation = function () {
        return this.representation;
    };

    /**
     * Sets the representation of this port (not supported yet)
     * @param {Shape} newRep
     * @chainable
     */
    Port.prototype.setRepresentation = function (newRep) {
        if (newRep instanceof RegularShape) {
            this.representation = newRep;
        } else {
            throw new Error("setRepresentation(): parameter must be an instance" +
            " of any regularShape");
        }
        return this;
    };

    /**
     * Gets the ports position (if `relativeToShape` is set to true it'll return the position
     * respect to the shape, otherwise it'll return its position respect to the canvas)
     * @param {boolean} relativeToShape
     * @returns {Point}
     */
    Port.prototype.getPoint = function (relativeToShape) {
        var border = parseInt(this.parent.style.getProperty('border'), 10) || 0;
        if (relativeToShape) {
            return new Point(this.getX() + Math.round(this.getWidth() / 2),
                this.getY() + Math.round(this.getHeight() / 2));
        }
//    console.log(this.getAbsoluteX());
//    console.log(this.getAbsoluteY());
//    console.log(new Point(this.getAbsoluteX() + Math.round(this.getWidth() / 2),
//        this.getAbsoluteY() + Math.round(this.getHeight() / 2)));
        return new Point(
            this.getAbsoluteX() + Math.round(this.getWidth() / 2),
            this.getAbsoluteY() + Math.round(this.getHeight() / 2)
        );

    };

    /**
     * Gets the percentage of this port relative to its parent.
     * @return {number}
     */
    Port.prototype.getPercentage = function () {
        return this.percentage;
    };

    /**
     * Serializes this port.
     * @return {Object}
     * @return {number} return.x
     * @return {number} return.y
     * @return {number} return.realX
     * @return {number} return.realY
     * @return {string} return.parent The ID of its parent.
     */
    Port.prototype.stringify = function () {
        var inheritedJSON = {},
            thisJSON = {
//            id: this.getID(),
                x: this.getX(),
                y: this.getY(),
                realX : this.realX,
                realY : this.realY,
//            direction: this.getDirection(),
//            percentage: this.getPercentage(),
                parent: this.getParent().getID()
            };
        $.extend(true, inheritedJSON, thisJSON);
        return inheritedJSON;
    };

    /**
     * @abstract
     * @class Router
     * Represents the router used to define the points for a connection.
     * @extend JCoreObject
     *
     * @constructor Creates an instance of the class Router
     */
    Router = function () {
        JCoreObject.call(this);
    };

    Router.prototype = new JCoreObject();

    /**
     * The type of each instance of this class
     * @property {String}
     */
    Router.prototype.type = "Router";

    /**
     * @abstract Abstract method to create a route (defined in other inherited classes)
     * @returns {boolean}
     */
    Router.prototype.createRoute = function () {
        return true;
    };

    /**
     * @class ManhattanConnectionRouter
     * Class ManhattanConnectionRouter uses the 'ManhattanRouter' algorithm to define the points of the connection.
     * @extends Router
     *
     * @constructor Creates an instance of the class ManhattanConnectionRouter
     */
    ManhattanConnectionRouter = function () {
        Router.call(this);
        /**
         * Minimum distance used in the algorithm
         * @property {number} [mindist=20]
         */
        this.mindist = 20;
    };

    ManhattanConnectionRouter.prototype = new Router();

    /**
     * The type of each instance of this class
     * @property {String}
     */
    ManhattanConnectionRouter.prototype.type = "ManhattanConnectionRouter";

    /**
     * Creates the points of `connection` by calling the #route method and using
     * `connection.srcPort` and `connection.destPort` as the start and end points
     * @param {Connection} connection
     * @return {Array} An array of points that define the connection.
     */
    ManhattanConnectionRouter.prototype.createRoute = function (connection) {
        var fromPt, fromDir, toPt, toDir, points = [];

        fromPt = connection.srcPort.getPoint(false);
        fromDir = connection.srcPort.direction;

        toPt = connection.destPort.getPoint(false);
        toDir = connection.destPort.direction;

        // draw a line between the two points.
        this.route(connection, toPt, toDir, fromPt, fromDir, points);
        return points;
    };

    /**
     * Implementation of the 'MahattanRouter' algorithm
     * @param {Connection} conn Instance of the class Connection
     * @param {Point} fromPt initial Point
     * @param {number} fromDir route using to begin line
     *        UP = 0; RIGHT= 1; DOWN = 2; LEFT = 3;
     * @param {Point} toPt final Point
     * @param {number} toDir route using to end line
     *        UP = 0; RIGHT= 1; DOWN = 2; LEFT = 3;
     * @param {Array} points array where points are saved
     */
    ManhattanConnectionRouter.prototype.route = function (conn, fromPt, fromDir,
                                                          toPt, toDir, points) {
        var PORT=(['UP','RIGHT','DOWN','LEFT']);
        var SEGMENT,newPointA,newPointB,findPoint;
        var findAuxPoint,thor=false,thor2=false;

        newPointB=fromPt;
        newPointA=toPt;

        SEGMENT=this.getSegment2(fromDir,toDir,fromPt,toPt,PORT);
        points.push(toPt);
        if(SEGMENT==1)
        {
            points.push(fromPt);
            return;
        }
        do{
            if(SEGMENT===5){
                var dirAux;
                if(toDir===0){
                    findPoint=new Point(newPointA.x,newPointA.y-this.mindist);
                    if(findPoint.x-newPointB.x<0){
                        dirAux=1;
                    }
                    else{
                        dirAux=3;
                    }
                }
                if(toDir===1){
                    findPoint=new Point(newPointA.x+this.mindist,newPointA.y);
                    if(findPoint.y-newPointB.y<0){
                        dirAux=2;
                    }
                    else{
                        dirAux=0;
                    }
                }
                if(toDir===2){
                    findPoint=new Point(newPointA.x,newPointA.y+this.mindist);
                    if(findPoint.x-newPointB.x<0){
                        dirAux=1;
                    }
                    else{
                        dirAux=3;
                    }
                }
                if(toDir===3){
                    findPoint=new Point(newPointA.x-this.mindist,newPointA.y);
                    if(findPoint.y-newPointB.y>0){
                        dirAux=0;
                    }
                    else{
                        dirAux=2;
                    }
                }
                newPointA=findPoint;
                thor2=true;
                toDir=dirAux;
            }
            if(SEGMENT===4){
                var dirAux;
                if(fromDir===0){
                    findPoint=new Point(newPointB.x,newPointB.y-this.mindist);
                    if(findPoint.x-newPointA.x<0){
                        dirAux=1;
                    }
                    else{
                        dirAux=3;
                    }
                }
                if(fromDir===1){
                    findPoint=new Point(newPointB.x+this.mindist,newPointB.y);
                    if(findPoint.y-newPointA.y>0){
                        dirAux=0;
                    }
                    else{
                        dirAux=2;
                    }
                }
                if(fromDir===2){
                    findPoint=new Point(newPointB.x,newPointB.y+this.mindist);
                    if(findPoint.x-newPointA.x<0){
                        dirAux=1;
                    }
                    else{
                        dirAux=3;
                    }
                }
                if(fromDir===3){
                    findPoint=new Point(newPointB.x-this.mindist,newPointB.y);
                    if(findPoint.y-newPointA.y<0){
                        dirAux=2;
                    }
                    else{
                        dirAux=0;
                    }
                }
                newPointB=findPoint;
                findAuxPoint=true;
                fromDir=dirAux;
            }
            if(SEGMENT===3){
                var etx=0;
                var ety=0;
                if(newPointA.y<newPointB.y)
                {
                    etx=this.positive(newPointA.y-newPointB.y);
                }
                if(newPointA.x<newPointB.x)
                {
                    ety=this.positive(newPointA.x-newPointB.x);
                }
                if(toDir===0){
                    if(toDir===0 && fromDir===0){
                        if(newPointA.y<newPointB.y)
                        {
                            findPoint=new Point(newPointA.x,newPointA.y-this.mindist);
                        }
                        else
                        {
                            etx=this.positive(newPointA.y-newPointB.y);
                            findPoint=new Point(newPointA.x,newPointA.y-(this.mindist+etx));
                        }
                    }else{
                        var newAux=0;
                        newAux=(newPointA.y+newPointB.y)/2;
                        findPoint=new Point(newPointA.x,newAux);
                    }
                }
                if(toDir===1){
                    if(toDir===1 && fromDir===1){
                        findPoint=new Point(newPointA.x+(this.mindist+ety),newPointA.y);
                    }else{
                        var newAux=0;
                        newAux=(newPointA.x+newPointB.x)/2;
                        findPoint=new Point(newAux,newPointA.y);
                    }
                }
                if(toDir===2){
                    if(toDir===2 && fromDir===2){
                        findPoint=new Point(newPointA.x,newPointA.y+(this.mindist+etx));
                    }else{
                        var newAux=0;
                        newAux=(newPointA.y+newPointB.y)/2;
                        findPoint=new Point(newPointA.x,newAux);
                    }
                }
                if(toDir===3){
                    if(toDir===3 && fromDir===3){
                        if(newPointA.x<newPointB.x)
                        {
                            findPoint=new Point(newPointA.x-this.mindist,newPointA.y);
                        }
                        else
                        {
                            ety=this.positive(newPointA.x-newPointB.x);
                            findPoint=new Point(newPointA.x-(this.mindist+ety),newPointA.y);
                        }
                    }else{
                        var newAux=0;
                        newAux=(newPointA.x+newPointB.x)/2;
                        findPoint=new Point(newAux,newPointA.y);
                    }
                }
                newPointA=findPoint;
            }else if(SEGMENT==2){
                if(thor==false){
                    if(fromDir===0 || fromDir===2)
                    {
                        findPoint=new Point(newPointB.x,newPointA.y);
                    }else if(fromDir===1 || fromDir===3)
                    {
                        findPoint=new Point(newPointA.x,newPointB.y);
                    }
                }else{
                    if(fromDir===0 || fromDir===2)
                    {
                        if(newPointA.x<newPointB.x&&newPointA.y>newPointB.y){
                            if(fromDir===2){
                                findPoint=new Point(newPointB.x,newPointA.y);
                            }else{
                                findPoint=new Point(newPointA.x,newPointB.y);
                            }
                        }else{
                            findPoint=new Point(newPointB.x,newPointA.y);
                        }

                    }else if(fromDir===1 || fromDir===3)
                    {
                        if(newPointA.x-newPointB.x>newPointA.y-newPointB.y){
                            findPoint=new Point(newPointA.x,newPointB.y);
                        }else{
                            findPoint=new Point(newPointA.x,newPointB.y);
                        }
                    }
                }
            }
            SEGMENT--;
            if(!findAuxPoint){
                points.push(findPoint);
            }else{
                findAuxPoint=false;
                thor=true;
            }
        }while(SEGMENT>1);
        if(thor){
            points.push(newPointB);
        }
        points.push(fromPt);
        return;
    };
//get num segment2
    ManhattanConnectionRouter.prototype.getSegment2 = function (fromDir,toDir,fromPt,toPt,PORT){
        if(fromDir===toDir){
            return 3;
        }else{
            if((0===fromDir && 2===toDir)&&(fromPt.x===toPt.x && fromPt.y>toPt.y)){
                return 1;
            }else if((1===fromDir && 3===toDir)&&(fromPt.x<toPt.x && fromPt.y===toPt.y)){
                return 1;
            }else if((2===fromDir && 0===toDir)&&(fromPt.x===toPt.x && fromPt.y<toPt.y)){
                return 1;
            }else if((3===fromDir && 1===toDir)&&(fromPt.x>toPt.x && fromPt.y===toPt.y)){
                return 1;
            }else if(1===fromDir && 0===toDir){
                if(fromPt.x<toPt.x && fromPt.y<toPt.y){
                    return 2;
                }else{
                    return 4;
                }
            }else if(0===fromDir && 1===toDir){
                if(fromPt.x<toPt.x || fromPt.y<toPt.y){
                    return 4;
                }else{
                    return 2;
                }
            }else if(2===fromDir && 0===toDir){
                if(fromPt.y<toPt.y){
                    return 3;
                }else{
                    return 5;
                }
            }else if(0===fromDir && 2===toDir){
                if(fromPt.y>toPt.y){
                    return 3;
                }else{
                    return 5;
                }
            }else if(3===fromDir && 0===toDir){
                if(fromPt.x>toPt.x && fromPt.y<toPt.y){
                    return 2;
                }else{
                    return 4;
                }
            }else if(0===fromDir && 3===toDir){
                if(fromPt.x<toPt.x && fromPt.y>toPt.y){
                    return 2;
                }else{
                    return 4;
                }
            }else if(2===fromDir && 1===toDir){
                if((fromPt.x<toPt.x && fromPt.y<toPt.y) || fromPt.y>toPt.y){
                    return 4;
                }else{
                    return 2;
                }
            }else if(1===fromDir && 2===toDir){
                if(fromPt.x<toPt.x && fromPt.y>toPt.y){
                    return 2;
                }else{
                    return 4;
                }
            }else if(3===fromDir && 1===toDir){
                if(fromPt.x<toPt.x){
                    return 5;
                }else{
                    return 3;
                }
            }else if(1===fromDir && 3===toDir){
                if(fromPt.x>toPt.x){
                    return 5;
                }else{
                    return 3;
                }
            }else if(3===fromDir && 2===toDir){
                if((fromPt.x<toPt.x && fromPt.y>toPt.y) || fromPt.y<toPt.y){
                    return 4;
                }else{
                    return 2;
                }
            }else if(2===fromDir && 3===toDir){
                if(fromPt.x<toPt.x && fromPt.y<toPt.y){
                    return 2;
                }else{
                    return 4;
                }
            }else
            {
                this.getSegment2(toDir,fromDir,fromPt,toPt,PORT);
            }
        }
    };
    ManhattanConnectionRouter.prototype.positive=function(num)
    {
        if(num<0)
        {
            num*=-1;
        }
        return num;
    };
    /**
     * @class ConnectionDecorator
     * Represents the decorator on each endpoint of a connection (represented as srcDecorator and destDecorator in
     * the class Connection).
     * The connection will be painted as follows:
     *
     * 1. Each connection decorator is painted with a CSS sprite
     * 2. The CSS class is built concatenating (with underscores) the following:
     *
     *      1. The prefix (passed as an argument in the config options)
     *      2. The zoom factor multiplied by 100
     *      3. The decorator type (passed as an argument in the config options)
     *      4. The direction of the decorator (which is the same as the direction of the port it corresponds to)
     *
     * Some examples:
     *
     *      // i.e.
     *      // let's assume that the zoom factor is 1
     *      // let's assume that connection is an instance of the class Connection
     *
     *      // To create a target decorator
     *      var connectionDecorator = new ConnectionDecorator({
 *          decoratorPrefix: 'con',
 *          decoratorType: 'target',
 *          style: {
 *              cssClasses: [],
 *              cssProperties: {}
 *          },
 *          parent: connection
 *      });
     *
     *      // assuming that the direction of the port is (1) TOP
     *      // paint() will build the class like this:
     *      // CSSClass = decoratorPrefix + "_" + zoomFactor * 100 + "_" + decoratorType + "_" + direction
     *      // CSSClass = "con_100_target_TOP"
     *
     *      // To create a source decorator
     *      var connectionDecorator = new ConnectionDecorator({
 *          decoratorPrefix: 'con',
 *          decoratorType: 'source',
 *          style: {
 *              cssClasses: [],
 *              cssProperties: {}
 *          },
 *          parent: connection
 *      });
     *
     *      // assuming that the direction of the port is (3) LEFT
     *      // paint() will build the class like this:
     *      // CSSClass = decoratorPrefix + "_" + zoomFactor * 100 + "_" + decoratorType + "_" + direction
     *      // CSSClass = "con_100_source_LEFT"
     *
     * @extends JCoreObject
     *
     * @constructor Creates an instance of the class ConnectionDecorator
     * @param {Object} options Initialization options
     * @cfg {Point} [decoratorPrefix=''] Decorator prefix used to reconstruct the css class for the sprite
     * @cfg {Connection} [parent=null] The parent of this decorator (must be an instance of the class Connection)
     * @cfg {Object} [style={cssClasses:[], cssProperties:{}}] CSS classes and properties
     */
    ConnectionDecorator = function (options) {

        JCoreObject.call(this, options);

        /**
         * Parent of this decorator (must be a Connection)
         * @property {Connection}
         */
        this.parent = null;

        /**
         * The type of this decorator (either "source" or "target")
         * @property {"source" / "target"}
         */
        this.decoratorType = null;

        /**
         * Decorator prefix of this decorator to build the CSS class for the sprite
         * @property {String}
         */
        this.decoratorPrefix = null;

        /**
         * This parameter is an array to see if the end point
         * is UP, RIGHT, BOTTOM and LEFT
         * @property {Object} spriteDirection
         * @property {string} [spriteDirection.0="top"] Enum for "top"
         * @property {string} [spriteDirection.1="right"] Enum for "right"
         * @property {string} [spriteDirection.2="bottom"] Enum for "bottom"
         * @property {string} [spriteDirection.3="left"] Enum for "left"
         */
        this.spriteDirection = {'0' : 'top', '1' : 'right',
            '2' : 'bottom', '3' : 'left'};

        /**
         * Height of this decorator
         * @property {number} [height=11]
         */
        this.height = 11;

        /**
         * Width of this decorator
         * @property {number} [width=11]
         */
        this.width = 11;

        /**
         * Separator used to build the class
         * @type {String}
         */
        this.separator = null;

        /**
         * Sprite used to build the class
         * @type {String}
         */
        this.sprite = null;

        /**
         * The class that will be constructed using the parameters given in
         * the options object
         * @type {string}
         */
        this.cssClass = null;

        ConnectionDecorator.prototype.initObject.call(this, options);
    };

    ConnectionDecorator.prototype = new JCoreObject();

    /**
     * Type of this connection decorator
     * @property {String}
     */
    ConnectionDecorator.prototype.type = "ConnectionDecorator";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} options The object that contains the config
     * @private
     */
    ConnectionDecorator.prototype.initObject = function (options) {

        var defaults = {
            sprite: 'bpmn_zoom',
            decoratorPrefix: '',
            separator: '_',
            decoratorType: 'target',
            parent: null
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaults, options);

        // init
        this.setDecoratorType(defaults.decoratorType)
            .setDecoratorPrefix(defaults.decoratorPrefix)
            .setSeparator(defaults.separator)
            .setSprite(defaults.sprite)
            .setParent(defaults.parent)
            .setCssClass('');       // cssClass defaults to empty
    };

    /**
     * Paints the connectionDecorator according to the parameters saved in `this.initObject`.
     * The steps to paint the decorator are:
     *
     * 1. Determine if this decorator belongs to the source or destination port
     * 2. Determine the direction of the decorator
     * 3. Build the class using the direction, decorator prefix, decorator type and zoom
     * 4. Determine the position of this decorator
     *
     * @chainable
     */
    ConnectionDecorator.prototype.paint = function () {

        var point,
            canvas,
            direction,
            port,           // the port it "belongs to"
            topStyle,
            leftStyle;

        if (this.decoratorType === "source") {
            port = this.parent.getSrcPort();
        } else {
            port = this.parent.getDestPort();
        }

        point = port.getPoint(false);
        direction = port.getDirection();
        canvas = port.canvas;

        topStyle = [
            point.y - this.zoomHeight,
            point.y - Math.round(this.zoomHeight / 2),
            point.y,
            point.y - Math.round(this.zoomHeight / 2)
        ];
        leftStyle = [
            point.x - Math.round(this.zoomWidth / 2) + 1,
            point.x,
            point.x - Math.round(this.zoomWidth / 2) + 1,
            point.x - this.zoomWidth
        ];

        if (this.getHTML() === null) {
            this.createHTML();
        }

        if (this.decoratorType === null) {
            this.html = null;
            return this;
        }

        // remove the last class if possible
        this.style.removeClasses([this.cssClass]);

        // construct the new class to be applied
        this.setCssClass([this.prefix, parseInt(canvas.zoomFactor * 100, 10),
            this.decoratorType, this.spriteDirection[direction]].join(this.separator));

        this.style.addClasses([
            this.sprite,
            this.getCssClass()
        ]);

        // top and left position
        this.style.addProperties({
            top: topStyle[direction],
            left: leftStyle[direction]
        });

        this.parent.html.appendChild(this.html);
        return this;
    };

    /**
     * Creates the HTML Representation of the SourceSpriteConnectionDecorator
     * @returns {HTMLElement}
     */
    ConnectionDecorator.prototype.createHTML = function () {
        this.html = document.createElement('div');
        this.html.id = this.id;
        this.style.applyStyle();
        this.style.addProperties({
            position: "absolute",
            left: 0,
            top: 0,
            height: this.zoomHeight,
            width: this.zoomWidth,
            zIndex: Style.MAX_ZINDEX    // (segments are 1) so this should be 2
        });

        return this.html;
    };

    /**
     * Attaches listeners to the connectionDecorator (currently it has click and mouseDown events)
     * @chainable
     */
    ConnectionDecorator.prototype.attachListeners = function () {
        var $connectionDecorator;
        $connectionDecorator = $(this.getHTML()).click(this.onClick(this));
//    $connectionDecorator.on("contextmenu", this.onRightClick(this));
        $connectionDecorator.on("mousedown", this.onMouseDown(this));
        return this;
    };

    /**
     * Refresh the dimension and position of the decorator to apply the current
     * zoom scale
     * @chainable
     */
    ConnectionDecorator.prototype.applyZoom = function () {
        this.setDimension(this.width, this.height);
        return this;
    };
    /**
     * @event mousedown
     * ConnectionDecorator mouse down callback fired when the mouse is down on it.
     * @param {ConnectionDecorator} decorator
     */
    ConnectionDecorator.prototype.onMouseDown = function (decorator) {
        return function (e, ui) {
            e.preventDefault();
            if (e.which === 3) {    // right click
                decorator.parent.canvas.updatedElement = decorator.parent;
                $(decorator.parent.canvas.html).trigger("rightclick");
            }
            e.stopPropagation();
        };
    };
// commented by mauricio on 17/12/12
// reason: it was an example on how to change the segment style of the connection
///**
// * XXX
// * @param decorator
// * @return {Function}
// */
//ConnectionDecorator.prototype.onRightClick = function (decorator) {
//    return function (e, ui) {
//        if (decorator.parent.canvas.currentConnection) {
//            var test = ["normal", "message", "association"],
//                style = ["regular", "dotted", "segmented", "segmentdot"],
//                connection = decorator.parent;
//            connection.getDestPort().moveTowardsTheCenter(true);
//            connection.getDestDecorator()
//                .setDecoratorPrefix("con_" + test[parseInt(Math.random() * 3, 10)]);
//            connection.getDestDecorator().paint();
//            connection.getDestPort().moveTowardsTheCenter();
//            connection.setSegmentStyle(style[parseInt(Math.random() *
//                style.length, 10)]);
//            e.preventDefault();
//        }
//    };
//};

    /**
     * @event click
     * Click callback fired when the decorator is clicked on.
     * It hides the currentSelection if any and shows the ports and handlers of `decorator` parent
     * (which is a connection).
     * @param {ConnectionDecorator} decorator
     */
    ConnectionDecorator.prototype.onClick = function (decorator) {
        return function (e, ui) {
            var connection = decorator.parent,
                oldConnection = decorator.parent.canvas.currentConnection,
                canvas = connection.canvas;

            // HIDE
            // if there were some shapes in the current selection then
            // empty the current selection
            canvas.emptyCurrentSelection();

            // if there was a connection previously select hide its ports
            // and its handlers
            if (oldConnection) {
                oldConnection.hidePortsAndHandlers();
            }

            // SHOW
            // show the ports and the handlers of the new connection
            connection.showPortsAndHandlers();

            // set the old connection as this connection
            canvas.currentConnection = connection;

            // TODO: zIndex
            e.stopPropagation();
        };
    };

    /**
     * Serializes this connection decorator.
     * @return {Object}
     * @return {"source" / "target"} return.decoratorType The decorator type to build the CSS class for the sprite
     * @return {string} return.decoratorPrefix The decorator prefix to build the CSS class for the sprite
     */
    ConnectionDecorator.prototype.stringify = function () {
        var inheritedJSON = {},
            thisJSON = {
                decoratorType: this.getDecoratorType(),
                decoratorPrefix: this.getDecoratorPrefix()
            };
        $.extend(true, inheritedJSON, thisJSON);
        return inheritedJSON;
    };

    /**
     * Returns the decorator type
     * @returns {String}
     */
    ConnectionDecorator.prototype.getDecoratorType = function () {
        return this.decoratorType;
    };

    /**
     * Sets the decoration type
     * @param {String} newType
     * @chainable
     */
    ConnectionDecorator.prototype.setDecoratorType = function (newType) {
        this.decoratorType = newType;
        return this;
    };

    /**
     * Returns the decorator type
     * @returns {String}
     */
    ConnectionDecorator.prototype.getDecoratorPrefix = function () {
        return this.prefix;
    };

    /**
     * Sets the decoration prefix
     * @param {String} newType
     * @chainable
     */
    ConnectionDecorator.prototype.setDecoratorPrefix = function (newType) {
        this.prefix = newType;
        return this;
    };

    /**
     * Sets the parent of this connectionDecorator
     * @param {Connection} newParent
     * @chainable
     */
    ConnectionDecorator.prototype.setParent = function (newParent) {
        this.parent = newParent;
        return this;
    };

    /**
     * Gets the parent of this connectionDecorator
     * @return {Connection}
     */
    ConnectionDecorator.prototype.getParent = function () {
        return this.parent;
    };

    /**
     * Sets the separator of this connectionDecorator
     * @param {String} newSeparator
     * @chainable
     */
    ConnectionDecorator.prototype.setSeparator = function (newSeparator) {
        this.separator = newSeparator;
        return this;
    };

    /**
     * Sets the sprite of this connectionDecorator
     * @param {String} newSprite
     * @chainable
     */
    ConnectionDecorator.prototype.setSprite = function (newSprite) {
        this.sprite = newSprite;
        return this;
    };

    /**
     * Gets the separator of this connectionDecorator
     * @return {String}
     */
    ConnectionDecorator.prototype.getSeparator = function () {
        return this.separator;
    };

    /**
     * Sets the cssClass of this connectionDecorator
     * @param {string} newCssClass
     * @chainable
     */
    ConnectionDecorator.prototype.setCssClass = function (newCssClass) {
        this.cssClass = newCssClass;
        return this;
    };

    /**
     * Gets the cssClass of this connectionDecorator
     * @return {string}
     */
    ConnectionDecorator.prototype.getCssClass = function () {
        return this.cssClass;
    };

    /**
     * @class Connection
     * Class that represents a connection between two elements in the diagram.
     *
     * A connection is defined with a set of points, there's a segment between two points i.e. `point[i]`
     * and `point[i + 1]` with `i >= 0` and `i < points.length - 1`, there are two ways to paint a connection:
     *
     * - given *2* {@link Port Ports}, use the algorithm 'ManhattanConnection' to define the points
     * - given *n* points, make the segments with the rule defined above (but first let's use the first and
     *      the last points to make them {@link Port Ports}).
     *
     * Some characteristics of the connection:
     *
     * - The connection has references to its source port and end port.
     * - The `state` of the connection is the set of points that define that connection.
     * - The connections can have a color, the color is an instance of the class Color.
     *
     * The connections can have the following types of segments:
     *
     * - regular (a complete segment)
     * - dotted
     * - segmented
     * - segmented and dotted (mixed)
     *
     * Some examples of the configuration:
     *
     *      // i.e.
     *      // let's assume that there are two shapes (sourceShape and destShape)
     *      // let's assume that srcPort is a port that is stored in sourceShape
     *      // let's assume that destPort is a port that is stored in destShape
     *      // to create an instance of Connection with regular light green segments
     *      var connectionGreen = new Connection({
 *          srcPort: srcPort,
 *          destPort: destPort,
 *          segmentColor: new Color(0, 200, 0),
 *          segmentStyle: "regular"
 *      });
     *      // to create an instance of Connection with dotted red segments
     *      var connectionRed = new Connection({
 *          srcPort: srcPort,
 *          destPort: destPort,
 *          segmentColor: new Color(255, 0, 0),
 *          segmentStyle: "dotted"
 *      });
     *
     * @extend JCoreObject
     *
     * @constructor Creates an instance of the class Connection
     * @param {Object} options Initialization options
     * @cfg {Point} [srcPort=new Port()] Source port of the connection
     * @cfg {Point} [destPort=new Port()] Destination port of the connection
     * @cfg {Color} [segmentColor=new Color(0, 0, 0)] Color of the connection (by default it's black)
     * @cfg {string} [segmentStyle="regular"] Type of segments as defined above
     */
    Connection = function (options) {

        JCoreObject.call(this, options);

        /**
         * The source port of the connection
         * @property {Port}
         */
        this.srcPort = null;

        /**
         * The end port of the connection
         * @property {Port}
         */
        this.destPort = null;

        /**
         * The decorator of the source of the connection
         * @property {ConnectionDecorator}
         */
        this.srcDecorator = null;

        /**
         * The decorator of the target of the connection
         * @property {ConnectionDecorator}
         */
        this.destDecorator = null;

        /**
         * List of the lines that forms the connection
         * @property {ArrayList}
         */
        this.lineSegments = new ArrayList();

        /**
         * Saves a copy of the line segments' points when a flag is passed to the
         * disconnect method (NOTE: this array is used in the
         * userDefinedRoute method)
         * @property {ArrayList}
         */
        this.points = [];

        /**
         * Saves a copy of the line segments' points when a flag is passed to the
         * disconnect method (NOTE: this array is used while creating the object
         * updatedElement in Canvas.triggerConnectionStateChangeEvent)
         * @property {ArrayList}
         */
        this.oldPoints = [];

        /**
         * Current segment style
         * @property {"dotted" / "regular" / "segmented" / "segmentdot"}
         */
        this.segmentStyle = null;

        /**
         * This segment style ej. "dotted", "segmented", "segmentdot" (it's the
         * original style set in `this.initObject()`)
         * @property {"dotted" / "regular" / "segmented" / "segmentdot"}
         */
        this.originalSegmentStyle = null;

        /**
         * Actual color of all the segment in this connection
         * @property {Color}
         */
        this.segmentColor = null;

        /**
         * Original color of all the segments in this connection (set in `this.initObject()`)
         * @property {Color}
         */
        this.originalSegmentColor = null;

        /**
         * default zIndex of the connection
         * @property {number}
         */
        this.defaultZOrder = 2;

        /**
         * Current zIndex of the connection
         * @property {number}
         */
        this.zOrder = 2;

        /**
         * ArrayList which contains the ids of the Connections it has an
         * intersection with:
         *
         *      // i.e.
         *      // let's assume that there's an instance of the class Connection called anotherConnection
         *      intersectionWith = new ArrayList();
         *      intersectionWith.insert(anotherConnection);
         *
         * @property {ArrayList}
         */
        this.intersectionWith = new ArrayList();


        Connection.prototype.initObject.call(this, options);
    };

    Connection.prototype = new JCoreObject();

    /**
     * The type of each instance of this class
     * @property {String}
     */
    Connection.prototype.type = "Connection";

    /**
     * The family of each instance of this class
     * @property {String}
     */
    Connection.prototype.family = "Connection";

    /**
     * Router associated with the connection
     * @property {Router}
     */
    Connection.prototype.router = new ManhattanConnectionRouter();

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance.
     * @param {Object} options The object that contains the config
     * @private
     */
    Connection.prototype.initObject = function (options) {
        var defaultOptions = {
            srcPort: new Port(),
            destPort: new Port(),
            segmentColor: new Color(0, 0, 0),
            segmentStyle: "regular"
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaultOptions, options);

        // init
        this.setSrcPort(defaultOptions.srcPort)
            .setDestPort(defaultOptions.destPort)
            .setSegmentStyle(defaultOptions.segmentStyle, false)
            .setSegmentColor(defaultOptions.segmentColor, false);

        // init originals
        this.originalSegmentStyle = defaultOptions.segmentStyle;
        this.originalSegmentColor = defaultOptions.segmentColor;

        // set the connections for each port as this
        this.getSrcPort().setConnection(this);
        this.getDestPort().setConnection(this);
    };

    /**
     * Creates the HTML Representation of the Connection.
     * @returns {HTMLElement}
     */
    Connection.prototype.createHTML = function () {
        this.html = document.createElement('div');
        this.html.id = this.id;

        this.style.addProperties({
            position: "absolute",
            left: 0,
            top: 0,
            height: 0,
            width: 0,
            zIndex: this.zOrder
        });
        return this.html;
    };

    /**
     * Sets the handlers for each segment.
     * This method sets the handler for each segment, also sets a variable called hasMoveHandler on each segment to
     * either true or false (it'll be false if the current segment is either the first or the last segment of
     * the connection)
     * @chainable
     */
    Connection.prototype.setSegmentMoveHandlers = function () {
        var i,
            currentSegment,
            orientationOptions = [this.HORIZONTAL, this.VERTICAL],
            segmentOrientation = (this.destPort.direction === this.TOP ||
            this.destPort.direction === this.BOTTOM) ? 1 : 0;
        for (i = this.lineSegments.getSize() - 1; i >= 0; i -= 1) {
            currentSegment = this.lineSegments.get(i);
            currentSegment.orientation =
                orientationOptions[segmentOrientation];
            currentSegment.hasMoveHandler = false;

            // set prev and next segments
            if (i < this.lineSegments.getSize() - 1 && i > 0) {
                currentSegment.nextNeighbor = this.lineSegments.get(i + 1);
                currentSegment.previousNeighbor = this.lineSegments.get(i - 1);
                currentSegment.hasMoveHandler = true;
                currentSegment.addSegmentMoveHandler();
            }
            segmentOrientation = 1 - segmentOrientation;
        }
        return this;
    };

    /**
     * Remove all the segmentHandlers of this connection
     * (removing its html)
     * @chainable
     */
    Connection.prototype.removeAllSegmentHandlers = function () {
        // delete previous handlers
        var segment,
            i;
        for (i = 0; i < this.lineSegments.getSize(); i += 1) {
            segment = this.lineSegments.get(i);
            if (segment.hasMoveHandler) {
                $(segment.moveHandler.html).remove();
            }
        }
        return this;
    };

    /**
     * Show the moveHandlers of the connections
     * @chainable
     */
    Connection.prototype.showMoveHandlers = function () {
        var i,
            currentHandler;
        for (i = 0; i < this.lineSegments.getSize(); i += 1) {
            currentHandler = this.lineSegments.get(i).moveHandler;
            if (currentHandler) {
                currentHandler.setVisible(true);
            }
        }
        return this;
    };

    /**
     * Hide the moveHandlers of the connection
     * @chainable
     */
    Connection.prototype.hideMoveHandlers = function () {
        var i,
            currentHandler;
        for (i = 0; i < this.lineSegments.getSize(); i += 1) {
            currentHandler = this.lineSegments.get(i).moveHandler;
            if (currentHandler) {
                currentHandler.setVisible(false);
            }
        }
        return this;
    };

    /**
     * Hides the ports and handlers of this connection.
     * @chainable
     */
    Connection.prototype.hidePortsAndHandlers = function () {
        this.hidePorts();
        this.hideMoveHandlers();
        return this;
    };

    /**
     * Shows the ports and handlers of this connection.
     * @chainable
     */
    Connection.prototype.showPortsAndHandlers = function () {
        this.showPorts();
        this.showMoveHandlers();
        return this;
    };

    /**
     * Paints the connection according to the parameters given as config options, this method `paint()`
     * unlike other similar `paint()` does not append the HTML to the DOM, this is done with a call
     * to `canvas.addConnection(connection)`.
     * @param {Object} options Configuration options
     * @param {string} [options.algorithm="manhattan"] The algorithm used to draw the connection
     * @param {Array} [options.points=[]] Points to be used if the algorithm is "user"
     * @param {number} [options.dx=0] Move the points dx
     * @param {number} [options.dy=0] Move the points dy
     * @chainable
     */
    Connection.prototype.paint = function (options) {
        var defaults = {
            algorithm: 'manhattan',
            points: [],
            dx: 0,
            dy: 0
        };
        $.extend(true, defaults, options);
        try {
            if (this.html === null) {
                this.createHTML();
            }

            $(this.html).empty();
            this.oldPoint = null;

            switch (defaults.algorithm) {
                case 'manhattan':
                    this.createManhattanRoute();
                    break;
                case 'user':
                    this.createUserDefinedRoute(defaults.points,
                        defaults.dx, defaults.dy);
                    break;
                default:
                    throw new Error('Connection.paint(): the algorithm provided ' +
                    'is not correct');
            }

            // apply predefined style
            this.style.applyStyle();

            // the inline style might have changed in this.move()
            // so restore the style to the original setting
            this.style.addProperties({
                top: 0,
                left: 0
            });

            // paint the decorator if any exists
            if (this.destDecorator !== null) {
                this.destDecorator.paint();
                this.destDecorator.attachListeners();
            }

            if (this.srcDecorator !== null) {
                this.srcDecorator.paint();
            }

            this.oldPoint = null;

        } catch (e) {
            console.log(e.message);
        }
        return this;
    };

    /**
     * Hides the connection and its intersections
     * @param {boolean} [savePoints] If set to true, the connection state will be saved in `this.points
     * (see the definition of {@link Connection#property-points} in the definition of the class).
     * @chainable
     */
    Connection.prototype.disconnect = function (savePoints) {
        this.clearAllIntersections();

        // hide the segment handlers
        this.hideMoveHandlers();

        // save the line segments and use them in the createCustomRoute method
        if (savePoints) {
            this.savePoints();
        }
        this.lineSegments.clear();

        // empty the contents
        $(this.html).empty();

        return this;
    };

    /**
     * Connects two elements using options as a parameter (alias for `this.paint`)
     * @param {Object} options Configuration options
     * @param {string} [options.algorithm="manhattan"] The algorithm used to draw the connection
     * @param {Array} [options.points=[]] Points to be used if the algorithm is "user"
     * @param {number} [options.dx=0] Move the points dx
     * @param {number} [options.dy=0] Move the points dy
     * @chainable
     */
    Connection.prototype.connect = function (options) {
        this.paint(options);
        return this;
    };

    /**
     * Hides the ports of the connection
     * @chainable
     */
    Connection.prototype.hidePorts = function () {
        this.srcPort.hide();
        this.destPort.hide();
        return this;
    };

    /**
     * Shows the ports of the connection
     * @chainable
     */
    Connection.prototype.showPorts = function () {
        this.srcPort.show();
        this.destPort.show();
        return this;
    };

    /**
     * Saves the state of the connection.
     * @param {Object} options
     * @param {boolean} [options.saveToOldPoints=false] If set to true then it will save the state
     * to `this.oldPoints` array
     * @chainable
     */
    Connection.prototype.savePoints = function (options) {
        var i,
            segment,
            point,
            arrayChosen = 'points',
            defaults = {
                saveToOldPoints: false
            };

        $.extend(true, defaults, options);

        if (defaults.saveToOldPoints) {
            arrayChosen = "oldPoints";
        }

        this[arrayChosen] = [];
        for (i = 0; i < this.lineSegments.getSize(); i += 1) {
            segment = this.lineSegments.get(i);
            if (i === 0) {
                // insert the startPoint only for the first segment
                this[arrayChosen].push(new Point(
                    segment.startPoint.x,
                    segment.startPoint.y
                ));
            }
            this[arrayChosen].push(new Point(
                segment.endPoint.x,
                segment.endPoint.y
            ));
        }
//    console.log(this[arrayChosen]);
        return this;
    };

    /**
     * Creates the segments of the connection using points and moving the segments dx and dy
     * @param {Array} points
     * @param {number} dx
     * @param {number} dy
     * @chainable
     */
    Connection.prototype.createUserDefinedRoute = function (points, dx, dy) {
        var i,
            segment,
            diffPoint = new Point(dx, dy);
        for (i = 1; i < points.length; i += 1) {
            segment = new Segment({
                startPoint: new Point(
                    parseInt(points[i - 1].x, 10),
                    parseInt(points[i - 1].y, 10)
                ).add(diffPoint),
                endPoint: new Point(
                    parseInt(points[i].x, 10),
                    parseInt(points[i].y, 10)
                ).add(diffPoint),
                parent: this,
                canvas: this.canvas,
                color: this.segmentColor
            });
            this.addSegment(segment);
        }
        return this;
    };

    /**
     * Create the segments of the connection using the points defined by the algorithm "ManhattanConnection"
     * @chainable
     */
    Connection.prototype.createManhattanRoute = function () {
        var points = this.router.createRoute(this),
            i,
            segment;
        // create the segments now that we have the points
        for (i = 1; i < points.length; i += 1) {
            segment = new Segment({
                startPoint: new Point(
                    parseInt(points[i - 1].x, 10),
                    parseInt(points[i - 1].y, 10)
                ),
                endPoint: new Point(
                    parseInt(points[i].x, 10),
                    parseInt(points[i].y, 10)
                ),
                parent: this,
                canvas: this.canvas,
                color: this.segmentColor
            });
            this.addSegment(segment);
        }
        return this;
    };

    /**
     * Add a segment to the line segments arrayList (painting it first)
     * @param {Segment} segment
     * @chainable
     */
    Connection.prototype.addSegment = function (segment) {
        segment.setStyle(this.segmentStyle);
        segment.paint();
        this.lineSegments.insert(segment);
        return this;
    };

    /**
     * Destroys the connection but saving its HTML first
     * @chainable
     */
    Connection.prototype.saveAndDestroy = function () {

        if (this.canvas.currentConnection) {
            this.hidePortsAndHandlers();
            this.canvas.currentConnection = null;
        }

        // remove this from the canvas connections arrayList
        this.canvas.removeConnection(this);

        //this.canvas.removeFromList(this);
        this.srcPort.saveAndDestroy(); //destroy srcPort
        this.destPort.saveAndDestroy(); //destroy destPort

        // save the html but detach it from the DOM
        this.html = $(this.html).detach()[0];

        return this;
    };

    /**
     * Fixes the zIndex of the connection based on the parents of the connection ports (which are
     * shapes), the zIndex is defined as the maximum zIndex the ports parents + 2
     * @chainable
     */
    Connection.prototype.fixZIndex = function () {
        var sourceShape = this.srcPort.parent,
            destShape = this.destPort.parent,
            sourceShapeParent,
            destShapeParent,
            sourceShapeParentZIndex,
            destShapeParentZIndex;

        if (sourceShape.parent) {
            sourceShapeParent = sourceShape.parent;
        } else {
            sourceShapeParent = sourceShape.canvas;
        }
        sourceShapeParentZIndex = Math.min(sourceShapeParent.getZOrder(),
            sourceShape.getZOrder() - 1);

        if (destShape.parent) {
            destShapeParent = destShape.parent;
        } else {
            destShapeParent = destShape.canvas;
        }
        destShapeParentZIndex = Math.min(destShapeParent.getZOrder(),
            destShape.getZOrder() - 1);

        this.setZOrder(Math.max(sourceShapeParentZIndex, destShapeParentZIndex) +
        2);
        return this;
    };
    /**
     * Checks and creates intersections of `this` connection with the `otherConnection`
     * @param {Connection} otherConnection
     * @return {boolean} True if there is at least one intersection
     */
    Connection.prototype.checkAndCreateIntersections = function (otherConnection) {
        // iterate over all the segments of this connection
        var i,
            j,
            segment,
            testingSegment,
            hasAtLeastOneIntersection = false,
            ip; // intersectionPoint

        for (i = 0; i < this.lineSegments.getSize(); i += 1) {
            segment = this.lineSegments.get(i);
            for (j = 0; j < otherConnection.lineSegments.getSize(); j += 1) {
                testingSegment = otherConnection.lineSegments.get(j);

                // create the intersection of the segments if possible
                ip = Geometry.perpendicularSegmentIntersection(segment.startPoint,
                    segment.endPoint, testingSegment.startPoint,
                    testingSegment.endPoint);
                if (ip) {
                    hasAtLeastOneIntersection = true;
                    segment.createIntersectionWith(testingSegment, ip);
                }
            }
        }
        //console.log("There was an intersection? " + hasAtLeastOneIntersection);
        if (hasAtLeastOneIntersection) {
            if (!this.intersectionWith.find('id', otherConnection.getID())) {
                this.intersectionWith.insert(otherConnection);
            }
            if (!otherConnection.intersectionWith.find('id', this.getID())) {
                otherConnection.intersectionWith.insert(this);
            }
        }
        return hasAtLeastOneIntersection;
    };

    /**
     * Checks and creates intersections with all the other connections found in this canvas.
     * This method also repaints the segments that have intersections.
     * @chainable
     */
    Connection.prototype.checkAndCreateIntersectionsWithAll = function () {
        var i,
            otherConnection,
            segment;
        // create the intersections of this connection
        // each segment of this connection saves the intersections it has with
        // other segments as an ArrayList of Intersections
//    console.log(this.canvas.connections.getSize());
        for (i = 0; i < this.canvas.connections.getSize(); i += 1) {
            otherConnection = this.canvas.connections.get(i);
            if (otherConnection.getID() !== this.getID()) {
                this.checkAndCreateIntersections(otherConnection);
            }
        }

        // after we've got all the intersections
        // paint the segments with their intersections
        for (i = 0; i < this.lineSegments.getSize(); i += 1) {
            segment = this.lineSegments.get(i);
            if (segment.intersections.getSize()) {
                segment.paintWithIntersections();
            }
        }
        return this;
    };

    /**
     * Clears all the intersections with the otherConnection that exist in this connection
     * @param {Connection} otherConnection
     * @chainable
     */
    Connection.prototype.clearIntersectionsWith = function (otherConnection) {
        var i,
            segment,
            intersectionObject,
            intersectionWasErased;
        for (i = 0; i < this.lineSegments.getSize(); i += 1) {
            intersectionWasErased = false;
            segment = this.lineSegments.get(i);
            while (true) {
                intersectionObject = segment.
                    intersections.find('idOtherConnection',
                    otherConnection.getID());
                if (intersectionObject) {
                    segment.intersections.remove(intersectionObject);
                    intersectionObject.destroy();
                } else {
                    break;
                }
                intersectionWasErased = true;
            }
            if (intersectionWasErased) {
                segment.paintWithIntersections();
            }
        }
        // remove other connection from this connection intersectionWith ArrayList
        this.intersectionWith.remove(otherConnection);
        otherConnection.intersectionWith.remove(this);
        return this;
    };

    /**
     * Clear all the intersections of this connection calling clearIntersectionsWith
     * many times (one for each connection that exists in the canvas)
     * @chainable
     */
    Connection.prototype.clearAllIntersections = function () {
        var otherIntersection;
//    console.log("Clearing all: " + this.intersectionWith.getSize());
        while (this.intersectionWith.getSize() > 0) {
            otherIntersection = this.intersectionWith.get(0);
//        console.log(otherIntersection);
            otherIntersection.clearIntersectionsWith(this);
        }
        return this;
    };

    /**
     * Moves the connection [dx, dy]
     * @param {number} dx
     * @param {number} dy
     * @chainable
     */
    Connection.prototype.move = function (dx, dy) {
        var top,
            left;

        // moving with inline style
        top = parseFloat(this.html.style.top);
        left = parseFloat(this.html.style.left);
        $(this.html).css({
            'top': top + dy,
            'left': left + dx
        });
        return this;
    };

    /**
     * Serializes this object (as a JavaScript object)
     * @return {Object}
     * @return {string} return.segmentStyle The style of each segment of this connection
     * @return {Object} return.srcPort The serialization of `this.srcPort`
     * @return {Object} return.destPort The serialization of `this.destPort`
     * @return {Array} return.state The array of points that represent this connection a.k.a. state
     * @return {string} return.srcDecoratorPrefix The source decorator prefix
     * @return {string} return.destDecoratorPrefix The destination decorator prefix
     */
    Connection.prototype.stringify = function () {
        return {
//        id: this.getID(),
            segmentStyle: this.getSegmentStyle(),
            srcPort: this.getSrcPort().stringify(),
            destPort: this.getDestPort().stringify(),
            state: this.savePoints() && this.points,
            srcDecoratorPrefix: this.getSrcDecorator().getDecoratorPrefix(),
            destDecoratorPrefix: this.getDestDecorator().getDecoratorPrefix()
        };
    };

    /**
     * Sets the color of the segments of this connection
     * @param {Color} newColor
     * @param {boolean} [repaint] True if the segment are to be painted immediately
     * @chainable
     */
    Connection.prototype.setSegmentColor = function (newColor, repaint) {
        var i,
            segment;
        this.segmentColor = newColor;
        if (this.html && repaint) {
            for (i = 0; i < this.lineSegments.getSize(); i += 1) {
                segment = this.lineSegments.get(i);
                segment.setColor(this.segmentColor);
                segment.paint();
            }
        }
        return this;
    };

    /**
     * Get the segment color of this connection
     * @return {Color}
     */
    Connection.prototype.getSegmentColor = function () {
        return this.segmentColor;
    };

    /**
     * Sets the style of each segment of this connection
     * @param {string} newStyle
     * @param {boolean} [repaint] True if the segment are to be painted immediately
     * @chainable
     */
    Connection.prototype.setSegmentStyle = function (newStyle, repaint) {
        var i,
            segment;
        this.segmentStyle = newStyle;
        if (this.html && repaint) {
            for (i = 0; i < this.lineSegments.getSize(); i += 1) {
                segment = this.lineSegments.get(i);
                segment.setStyle(this.segmentStyle);
                segment.paint();
            }
        }
        return this;
    };

    /**
     * Get the segment style of this connection
     * @return {string}
     */
    Connection.prototype.getSegmentStyle = function () {
        return this.segmentStyle;
    };

    /**
     * Sets the source port
     * @param {Port} newSrcPort
     * @chainable
     */
    Connection.prototype.setSrcPort = function (newSrcPort) {
        this.srcPort = newSrcPort;
        return this;
    };

    /**
     * Gets the source port
     * @return {Port}
     */
    Connection.prototype.getSrcPort = function () {
        return this.srcPort;
    };

    /**
     * Sets the destination port
     * @param {Port} newDestPort
     * @chainable
     */
    Connection.prototype.setDestPort = function (newDestPort) {
        this.destPort = newDestPort;
        return this;
    };

    /**
     * Gets the destination port
     * @return {Port}
     */
    Connection.prototype.getDestPort = function () {
        return this.destPort;
    };

    /**
     * Returns the source decorator of the connection
     * @returns {ConnectionDecorator}
     */
    Connection.prototype.getSrcDecorator = function () {
        return this.srcDecorator;
    };
    /**
     * Returns the target decorator of the connection
     * @returns {ConnectionDecorator}
     */
    Connection.prototype.getDestDecorator = function () {
        return this.destDecorator;
    };
    /**
     * Returns a list of the lines associated with this connection
     * @returns {ArrayList}
     */
    Connection.prototype.getLineSegments = function () {
        return this.lineSegments;
    };

    /**
     * Sets the source decorator of the connection
     * @param {ConnectionDecorator} newDecorator
     * @chainable
     */
    Connection.prototype.setSrcDecorator = function (newDecorator) {
        if (newDecorator.type === 'ConnectionDecorator') {
            this.srcDecorator = newDecorator;
        }
        return this;
    };

    /**
     * Sets the destination decorator of the connection
     * @param {ConnectionDecorator} newDecorator
     * @chainable
     */
    Connection.prototype.setDestDecorator = function (newDecorator) {
        if (newDecorator.type === 'ConnectionDecorator') {
            this.destDecorator = newDecorator;
        }
        return this;
    };

    /**
     * Gets the zOrder of the connection
     * @return {number}
     */
    Connection.prototype.getZOrder = function () {
        return Shape.prototype.getZOrder.call(this);
    };

    /**
     * Gets the oldPoints of the connection
     * @return {Array}
     */
    Connection.prototype.getOldPoints = function () {
        return this.oldPoints;
    };

    /**
     * Gets the points of the connection
     * @return {Array}
     */
    Connection.prototype.getPoints = function () {
        return this.points;
    };

    /**
     * @class BehavioralElement
     * Class that encapsulates the behavior of all elements that have container and
     * drop behaviors attached to them.
     * since this class inherits from {@link JCoreObject}, then the common behaviors
     * and properties for all elements in the designer are also part of this class
     * The purpose of this class is to encapsulate behaviors related to drop and
     * containment of elements, so it shouldn't be instantiated, we should
     * instantiate the elements that inherit from this class instead.
     *          //i.e
     *          //we will set the behaviors that are related only to this class
     *          var shape = new CustomShape({
 *          //we can set different types of containers here and the factory
 *          //will do all the work
 *              container : "regular",
 *              drop : {
 *              //type specifies the drop behavior we want, again we just need
 *              // to pass a string
 *               type : "container",
 *                //selectors are the css selectors that this element will
 *                //accept to be dropped
 *               selectors : [".firstselector",".secondselector"],
 *              //overwrite is an option to override previous and default
 *              //selectors
 *               overwrite : false
 *              }
 *          });
     *
     * @extends JCoreObject
     *
     * @constructor Creates a new instance of this class
     * @param {Object} options
     * @cfg {String} [container="nocontainer"] the type of container behavior
     * we want for an object, it can be regular,or nocontainer, or any other class
     * that extends the {@link ContainerBehavior} class, but also note that we would
     * need to override the factory for container behaviors located in this class.
     * @cfg {Object} [drop={
 *     drop : "nodrop",
 *     selectors : [],
 *     overwrite : false
 * }] Object that contains the options for the drop behavior we want an object
     * to have, we can, assign type which can be container, connection,
     * connectioncontainer, or no drop. As with the container behavior we can extend
     * the behaviors and factory for this functionality.
     * We also have selectors that specify the selectors the drop behavior will
     * accept and the overwrite feature
     */
    BehavioralElement = function (options) {
        JCoreObject.call(this, options);
        /**
         * Determines the container Behavior that this object has
         * @property {ContainerBehavior}
         * TODO Initialize default behavior
         */
        this.containerBehavior = null;
        /**
         * Determines the drop behavior that this object has
         * @property {DropBehavior}
         * TODO Initialize default behavior
         */
        this.dropBehavior = null;
        /**
         * List of the children
         * @property {*}
         */
        this.children = null;

        BehavioralElement.prototype.initObject.call(this, options);
    };

    BehavioralElement.prototype = new JCoreObject();
    /**
     * Type of the all instances of this class
     * @property {String}
     */
    BehavioralElement.prototype.type = "BehavioralElement";
    /**
     * Static variable that will hold the behavior for all objects that implement
     * the nodrop behavior
     * @property {NoDropBehavior}
     */
    BehavioralElement.prototype.noDropBehavior = null;
    /**
     * Static variable that will hold the behavior for all objects that implement
     * the container drop behavior
     * @property {ContainerDropBehavior}
     */
    BehavioralElement.prototype.containerDropBehavior = null;
    /**
     * Static variable that will hold the behavior for all objects that implement
     * the connection drop behavior
     * @property {ConnectionDropBehavior}
     */
    BehavioralElement.prototype.connectionDropBehavior = null;
    /**
     * Static variable that will hold the behavior for all objects that implement
     * the connectioncontainer drop behavior
     * @property {ConnectionContainerDropBehavior}
     */
    BehavioralElement.prototype.connectionContainerDropBehavior = null;
    /**
     * Static variable that will hold the behavior for all objects that implement
     * the nocontainer behavior
     * @property {NoContainerBehavior}
     */
    BehavioralElement.prototype.noContainerBehavior = null;
    /**
     * Static variable that will hold the behavior for all objects that implement
     * the regularcontainer behavior
     * @property {RegularContainerBehavior}
     */
    BehavioralElement.prototype.regularContainerBehavior = null;

    /**
     * Instance initializer which uses options to extend the default config options.
     * The default options are container: nocontainer, and drop: nodrop
     * @param {Object} options
     */
    BehavioralElement.prototype.initObject = function (options) {
        var defaults = {
            drop : {
                type : "nodrop",
                selectors : [],
                overwrite : false
            },
            container : "nocontainer"
        };
        $.extend(true, defaults, options);
        this.setDropBehavior(defaults.drop.type, defaults.drop.selectors,
            defaults.drop.overwrite);
        this.setContainerBehavior(defaults.container);


        this.children = new ArrayList();
    };
    /**
     * Factory of drop behaviors. It uses lazy instantiation to create instances of
     * the different drop behaviors
     * @param {String} type Type of drop behavior we want to assign to an object,
     * it can be nodrop, container, connection or connectioncontainer
     * @param {Array} selectors Array containing the css selectors that the drop
     * behavior will accept
     * @return {DropBehavior}
     */
    BehavioralElement.prototype.dropBehaviorFactory = function (type, selectors) {
        if (type === "nodrop") {
            if (!this.noDropBehavior) {
                this.noDropBehavior = new NoDropBehavior(selectors);
            }
            return this.noDropBehavior;
        }
        if (type === "container") {
            if (!this.containerDropBehavior) {
                this.containerDropBehavior = new ContainerDropBehavior(selectors);
            }
            return this.containerDropBehavior;
        }
        if (type === "connection") {
            if (!this.connectionDropBehavior) {
                this.connectionDropBehavior = new ConnectionDropBehavior(selectors);
            }
            return this.connectionDropBehavior;
        }
        if (type === "connectioncontainer") {
            if (!this.connectionContainerDropBehavior) {
                this.connectionContainerDropBehavior =
                    new ConnectionContainerDropBehavior(selectors);
            }
            return this.connectionContainerDropBehavior;
        }
    };

    /**
     * Factory of container behaviors. It uses lazy instantiation to create
     * instances of the different container behaviors
     * @param {String} type An string that specifies the container behavior we want
     * an instance to have, it can be regular or nocontainer
     * @return {ContainerBehavior}
     */
    BehavioralElement.prototype.containerBehaviorFactory = function (type) {
        if (type === "regular") {
            if (!this.regularContainerBehavior) {
                this.regularContainerBehavior = new RegularContainerBehavior();
            }
            return this.regularContainerBehavior;

        }
        if (!this.noContainerBehavior) {
            this.noContainerBehavior = new NoContainerBehavior();
        }
        return this.noContainerBehavior;
    };
    /**
     * Updates the children positions of a container given the x and y difference
     * @param {Number} diffX x difference
     * @param {Number} diffY y difference
     * @chainable
     * // TODO make this method recursive
     */
    BehavioralElement.prototype.updateChildrenPosition = function (diffX, diffY) {
        var children = this.getChildren(),
            child,
            i,
            updatedChildren = [],
            previousValues = [],
            newValues = [];

        for (i = 0; i < children.getSize(); i += 1) {
            child = children.get(i);
//        child.oldX = child.x;
//        child.oldY = child.y;
//        child.oldAbsoluteX = child.absoluteX;
//        child.oldAbsoluteY = child.absoluteY;
            if ((diffX !== 0 || diffY !== 0) &&
                !this.canvas.currentSelection.contains(child)) {
                updatedChildren.push(child);
                previousValues.push({
                    x : child.x,
                    y : child.y
                });
                newValues.push({
                    x : child.x + diffX,
                    y : child.y + diffY
                });
            }
            child.setPosition(child.x + diffX, child.y + diffY);
        }
        if (updatedChildren.length > 0) {
            this.canvas.triggerPositionChangeEvent(updatedChildren, previousValues,
                newValues);
        }
        return this;
    };

    /**
     * Returns whether the instance is a container or not
     * @return {Boolean}
     */
    BehavioralElement.prototype.isContainer = function () {
        return this.containerBehavior &&
        this.containerBehavior.type !== "NoContainerBehavior";
    };
    /**
     * Sets the container behavior of an object, using the same options as
     * the factory
     * @param {String} behavior the container behavior we want the factory to
     * assign, it can be regular, or nocontainer
     * @chainable
     */
    BehavioralElement.prototype.setContainerBehavior = function (behavior) {
        // update the saved object
        // added by mauricio to save the container behavior of this
        $.extend(true, this.savedOptions, {container: behavior});
        this.containerBehavior = this.containerBehaviorFactory(behavior);
        return this;
    };
    /**
     * Encapsulates the functionality of adding an element this element according
     * to its container behavior
     * @param {Shape} shape Shape we want to add to the element
     * @param {Number} x x coordinate where the shape will be positionated relative
     * to this element
     * @param {Number} y y coordinate where the shape will be positionated relative
     * to this element
     * @param {Boolean} topLeftCorner determines if the drop position should be
     * calculated from the top left corner of the shape or, from its center
     * @chainable
     */
    BehavioralElement.prototype.addElement = function (shape, x, y,
                                                       topLeftCorner) {
        this.containerBehavior.addToContainer(this, shape, x, y, topLeftCorner);
        return this;
    };
    /**
     * Encapsulates the functionality of removing an element this element according
     * to its container behavior
     * @param {Shape} shape shape to be removed from this element
     * @chainable
     */
    BehavioralElement.prototype.removeElement = function (shape) {
        this.containerBehavior.removeFromContainer(shape);
        return this;
    };
    /**
     * Swaps a shape from this container to a different one
     * @param {Shape} shape shape to be swapped
     * @param {BehavioralElement} otherContainer the other container the shape will
     * be swapped to
     * @param {Number} x x coordinate where the shape will be positionated relative
     * to this element
     * @param {Number} y y coordinate where the shape will be positionated relative
     * to this element
     * @param {Boolean} topLeftCorner determines if the drop position should be
     * calculated from the top left corner of the shape or, from its center
     * @chainable
     */
    BehavioralElement.prototype.swapElementContainer = function (shape,
                                                                 otherContainer, x,
                                                                 y, topLeftCorner) {
        var newX = !x ? shape.getX() : x,
            newY = !y ? shape.getY() : y;
        shape.changedContainer = true;
        this.removeElement(shape);
        otherContainer.addElement(shape, newX, newY, topLeftCorner);
        return this;
    };


    /**
     * Returns the list of children belonging to this shape
     * @returns {ArrayList}
     */
    BehavioralElement.prototype.getChildren = function () {
        return this.children;
    };

    /**
     * Updates the dimensions and position of this shape (note: <this> is a shape)
     * @param {Number} margin the margin for this element to consider towards the
     * shapes near its borders
     * @chainable
     */
    BehavioralElement.prototype.updateDimensions = function (margin) {
        // update its size (if an child grew out of the shape)
        // only if it's not the canvas
        if (this.family !== 'Canvas') {
            this.updateSize(margin);
            this.refreshConnections();
            // updates JQueryUI's options (minWidth and minHeight)
            ResizeBehavior.prototype.updateResizeMinimums(this);

            BehavioralElement.prototype.updateDimensions.call(this.parent, margin);

        }
        return this;
    };
    /**
     * Sets the container behavior of an object, using the same options as
     * the factory
     * @param {String} behavior the drop behavior we want the factory to assign,
     * it can be container, nodrop, connection, or connectioncontainer
     * @param {Array} selectors Array containing the css selectors that will be
     * accepted on drop
     * @param {Boolean} overwrite Determines whether the default selectors will
     * be erased
     * @chainable
     */
    BehavioralElement.prototype.setDropBehavior = function (behavior, selectors,
                                                            overwrite) {
        this.dropBehavior = this.dropBehaviorFactory(behavior, selectors);
        this.dropBehavior.setSelectors(selectors, overwrite);
        if (this.html && this.dropBehavior) {
            this.dropBehavior.attachDropBehavior(this);

            // update the saved object
            // added by mauricio to save the drop behavior of this shape
            $.extend(true, this.savedOptions.drop, {
                type: behavior,
                overwrite: overwrite
            });

            if (selectors && selectors.hasOwnProperty('length')) {
                this.dropBehavior.updateSelectors(this, selectors, overwrite);
                // update the saved object
                // added by mauricio to save the drop behavior of this shape
                $.extend(true, this.savedOptions.drop, {
                    selectors: selectors
                });
            }
        }
        return this;
    };
    /**
     * Sets the selectors of the current drop behavior
     * @param {Array} selectors new css selectors for the drop behavior
     * @param {Boolean} overwrite determines whether the default selectors will
     * be erased
     * @chainable
     */
    BehavioralElement.prototype.setDropAcceptedSelectors = function (selectors,
                                                                     overwrite) {
        if (selectors && selectors.hasOwnProperty('length')) {
            this.dropBehavior.updateSelectors(this, selectors, overwrite);
        }
        return this;
    };
    /**
     * Attach the drop behavior to the element, if there is such
     * @chainable
     */
    BehavioralElement.prototype.updateBehaviors = function () {
        if (this.dropBehavior) {
            this.dropBehavior.attachDropBehavior(this);
            this.dropBehavior.updateSelectors(this);
        }
        return this;
    };

    /**
     * Stringifies the container and drop behavior of this object
     * @return {Object}
     */
    BehavioralElement.prototype.stringify = function () {
        var inheritedJSON = JCoreObject.prototype.stringify.call(this),
            thisJSON = {
                container: this.savedOptions.container,
                drop: this.savedOptions.drop
            };
        $.extend(true, inheritedJSON, thisJSON);
        return inheritedJSON;
    };

    /**
     *
     * @class Layer
     * Class that contains the properties of a layer for a shape we need
     * to have a shape already instantiated and added to the canvas in order for
     * this class to be effective
     *
     *          //i.e.
     *           var layer = new Layer({
 *                  //Determine the layer's parent
 *                  parent: customShape,
 *                  layerName: "first layer",
 *                  //the order in which the layers will be added in increasing
 *                  //order
 *                  priority: 0,
 *                  //determines if the layer will be hidden or visible
 *                  visible: true,
 *                  //sprites to be applied on the layers according to a zoom
 *                  //scale
 *                  zoomSprites : ["class50, class75, class100, class125,
 *                      class 150"]
 *           });
     * @extend JCoreObject
     *
     * @constructor
     * Initializes a layer, the constructor must be called with all its parameter
     * for the object to be meaningful, its is important to denote that the css
     * class must follow this structure
     * any word_zoomScale_anythingYouWantHere
     * @param {Object} options
     * @cfg {Object} parent, Parent of a corresponding layer, a layer may not exist
     * without a parent
     * @cfg {String} [layerName="defaultLayerName"] A name we want to label a layer
     * with
     * @cfg {number} [priority=0] The orders in which the layers will be added in
     * increasing order
     * @cfg {boolean} [visible=true] Determines whether a layer wll be visible or
     * hidden
     * @cfg {Array} [zoomSprites=["","","","",""]] Sprites to be applied to the
     * layer according to a zoom scale
     */
    Layer = function (options) {

// TODO: check elementClass and bpmnClass removal impact on the layers
//Layer = function (parent, name, elementClass, priority, bpmnClass, visible) {

        JCoreObject.call(this, options);

        /**
         * The name of the layer
         * @property {String}
         */
        this.layerName = null;

        /**
         * The priority of the layer, determines which layer should be on top
         * @property {number}
         */
        this.priority = null;

        /**
         * The bpmnShape that this layer belongs too.
         * Extremely important since some data will be strictly drawn by its parent
         * @property {Object}
         */
        this.parent = null;

        /**
         * Determines when a layer is visible or not
         * @property boolean
         */
        this.visible = null;
        /**
         * The current Sprite applied in the zoom scale
         * @property {String}
         */
        this.currentZoomClass = "";
        /**
         * Sprites for the layer in each zoom scale
         * @property {Array}
         */
        this.zoomSprites = [];

        Layer.prototype.initObject.call(this, options);
    };

    Layer.prototype = new JCoreObject();

    /**
     * Type of an instance of this class
     * @property {String}
     */
    Layer.prototype.type = "Layer";

    /**
     * Object init method (internal)
     * @param {Object} options
     */
    Layer.prototype.initObject = function (options) {
        /**
         * Default options for the object
         * @property {Object}
         */
        var defaults = {
            x: 0,
            y: 0,
            parent: null,
            layerName: "defaultLayerName",
            priority: 0,
            visible: true,
            zoomSprites : ["", "", "", "", ""]
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaults, options);

        // call setters using the defaults object
        this.setParent(defaults.parent)
            .setPosition(defaults.x, defaults.y)
            .setLayerName(defaults.layerName)
            .setPriority(defaults.priority)
            .setVisible(defaults.visible)
            .setZoomSprites(defaults.zoomSprites)
            .setProperties();

    };
    /**
     * Updates the properties in order to change zoom scales
     */
    Layer.prototype.applyZoom = function () {
        this.setProperties();
    };
    /**
     * Comparison function for ordering layers according to priority
     * @param {Layer} layer1
     * @param {Layer} layer2
     * @returns {boolean}
     */
    Layer.prototype.comparisonFunction = function (layer1, layer2) {
        return layer1.priority > layer2.priority;
    };
    /**
     * Creates the HTML representation of the layer
     * @returns {HTMLElement}
     */
    Layer.prototype.createHTML = function (modifying) {
        this.setProperties();
        JCoreObject.prototype.createHTML.call(this, modifying);
        return this.html;
    };
    /**
     * Paints the corresponding layer, in this case adds the
     * corresponding css classes
     * @chainable
     */
    Layer.prototype.paint = function () {

        var $layer = $(this.html),
            newSprite;
        this.style.removeClasses([this.currentZoomClass]);
        newSprite = this.zoomSprites[this.canvas.zoomPropertiesIndex];
        this.style.addClasses([newSprite]);
        this.currentZoomClass = newSprite;
        this.style.applyStyle();
        /*
         //The current position where the properties for the current zoom factor
         // are located
         var propertiesPosition;

         if (!this.html) {
         return this;
         }
         propertiesPosition = (this.canvas) ? this.canvas.getPropertyPosition() : 2;

         //determine the css classes that will be used
         this.bpmnClass = this.elementProperties[propertiesPosition].bpmnClass;
         this.elementClass = this.elementProperties[propertiesPosition].elementClass;

         //apply classes according to visibility
         if (this.visible) {
         this.html.className = this.bpmnClass + " " + this.elementClass;
         } else {
         this.html.className = "";
         }
         return this;*/
        return this;
    };

    /**
     * This method will set the parent necessary properties for the layer to work
     * @chainable
     */
    Layer.prototype.setProperties = function () {

        if (!this.parent) {
            return this;
        }
        //generates an id for the layer
        this.id = this.parent.getID() + "Layer-" + this.layerName;
        //this.width =  this.parent.getWidth();
        //this.height = this.parent.getHeight();
        this.setDimension(this.parent.getWidth(), this.parent.getHeight());
        // DO NOT ASSUME THAT THE POSITION OF THE LAYER IS 0,0 BECAUSE OF THE
        // BORDERS IT MAY HAVE
//    this.setPosition(0, 0);
        this.canvas = this.parent.canvas;

        return this;
    };
    /**
     * Returns the layer name
     * @returns {String}
     */
    Layer.prototype.getLayerName = function () {
        return this.layerName;
    };

    /**
     * Returns the priority of the layer
     * @returns {number}
     */
    Layer.prototype.getPriority = function () {
        return this.priority;
    };
///**
// * Returns if the layer is visible or not
// * @returns {boolean}
// */
//Layer.prototype.getVisible = function () {
//    return this.visible;
//};

    /**
     * Sets the layer name
     * @param {String} newLayerName
     * @chainable
     */
    Layer.prototype.setLayerName = function (newLayerName) {
        if (typeof newLayerName === "string" && newLayerName !== "") {
            this.layerName = newLayerName;
        }
        return this;
    };

    /**
     * Sets the priority of the layer
     * @param {number} newPriority
     * @chainable
     */
    Layer.prototype.setPriority = function (newPriority) {
        if (typeof newPriority === "number") {
            this.priority = newPriority;
        }
        return this;
    };

    /**
     * Sets the parent of this layer
     * @param {CustomShape} newParent
     * @chainable
     */
    Layer.prototype.setParent = function (newParent) {
        if (newParent) {
            this.parent = newParent;
        }
        return this;
    };

    /**
     * Gets the parent of this layer
     * @return {Shape}
     */
    Layer.prototype.getParent = function () {
        return this.parent;
    };

    /**
     * Sets the css classes for the zoom scales
     * @param {Array} zoomSprites
     * @chainable
     */
    Layer.prototype.setZoomSprites = function (zoomSprites) {
        var i;
        this.zoomSprites = ["", "", "", "", ""];
        for (i = 0; i < zoomSprites.length; i += 1) {
            this.zoomSprites[i] = zoomSprites[i];
        }
        return this;
    };
    /**
     * Serializes this object
     * @return {Object}
     */
    Layer.prototype.stringify = function () {
        /**
         * inheritedJSON = {
     *     id: #
     *     x: #,
     *     y: #,
     *     width: #,
     *     height: #
     * }
         * @property {Object}
         */
        var inheritedJSON = {},
            thisJSON = {
                id: this.getID(),
                x: this.getX(),
                y: this.getY(),
                layerName: this.getLayerName(),
                priority: this.getPriority(),
                style: {
                    cssClasses: this.style.getClasses()
                },
                zoomSprites : this.zoomSprites
            };
        $.extend(true, inheritedJSON, thisJSON);
        return inheritedJSON;
    };

    /**
     * @abstract
     * @class Shape
     * Represents a shape in the jCore framework, shapes can be:
     *
     * - **Regular shapes** (Ovals, rectangles, polygons)
     * - **Custom shapes** (these kind of shapes can have sprites)
     *
     * A shape has the following characteristics:
     *
     * - It has a dragBehavior (inherited from {@link JCoreObject})
     * - It has a dropBehavior (inherited from {@link BehavioralElement})
     * - It has a containerBehavior (inherited from {@link BehavioralElement})
     * - It has a resizeBehavior (instantiated in this class)
     *
     * This class cannot be instantiated.
     *
     * @extend BehavioralElement
     * @constructor Creates an instance of the class ConnectionDecorator
     * @param {Object} options Initialization options
     * @cfg {boolean} [topLeft=false] If set to true then when this shape is dragged from the toolbar it'll be created
     * and placed in its topLeft coordinate otherwise it'll use the center as its topLeft coordinate
     * @cfg {string} [resizeBehavior="no"] Default resize behavior used to create the correct instance in the factory
     * @cfg {Object} [resizeHandlers={
 *      type: "None",
 *      total: 4,
 *          resizableStyle: {
 *              cssProperties: {
 *                  'background-color': "rgb(0, 255, 0)",
 *                  'border': '1px solid black'
 *              }
 *          },
 *          nonResizableStyle: {
 *              cssProperties: {
 *                  'background-color': "white",
 *                  'border': '1px solid black'
 *              }
 *          }
 *      }] Default styles to create the instances of the class Style
     * @cfg {string} [drag="disabled"] Default drag behavior used to create the correct instance in the factory
     */
    Shape = function (options) {
        /**
         * Array built when setting the dimension of the shape to store the
         * x coordinate of the div corners in clockwise order starting at top left
         * @property {Array}
         */
        this.xCorners = [0, 0, 0, 0];
        /**
         * Array built when setting the dimension of the shape to store the
         * y coordinate of the div corners in clockwise order starting at top left
         * @property {Array}
         */
        this.yCorners = [0, 0, 0, 0];
        /**
         * Array built when setting the dimension of the shape to store the
         * x coordinate of the midpoints of each div border in clockwise order
         * starting at the top border
         * @property {Array}
         */
        this.xMidPoints = [0, 0, 0, 0];
        /**
         * Array built when setting the dimension of the shape to store the
         * y coordinate of the midpoints of each div border in clockwise order
         * starting at the top border
         * @property {Array}
         */
        this.yMidPoints = [0, 0, 0, 0];
        /**
         * List containing the resize Points located in the corner of a div
         * @property {ArrayList}
         */
        this.cornerResizeHandlers = new ArrayList();
        /**
         * List containing the resize Points located in the middle of a border
         * @property {ArrayList}
         */
        this.midResizeHandlers = new ArrayList();

        BehavioralElement.call(this, options);

        /**
         * Center point of the shape (in the case of a polygon).
         * @property {Point}
         */
        this.center = null;
        /**
         * The parent of this shape.
         * @property {Shape}
         */
        this.parent = null;
        /**
         * Old parent of this shape (useful to check the previous
         * container of this shape).
         * @property {Shape}
         */
        this.oldParent = null;
        /**
         * Default zOrder of the shape.
         * @property {number} [defaultZOrder=1]
         */
        this.defaultZOrder = 1;
        /**
         * Denotes whether this shape is being dragged.
         * @property {boolean} [dragging=false]
         */
        this.dragging = false;
        /**
         * Denotes whether this shape was dragged.
         * @property {boolean} [wasDragged=false]
         */
        this.wasDragged = false;
        /**
         * Denotes whether this shape was entered by a draggable element.
         * @property {boolean} [entered=false]
         */
        this.entered = false;
        /**
         * Determines the resizeBehavior that this object has.
         * @property {ResizeBehavior}
         */
        this.resizeBehavior = null;
        /**
         * Determines whether the shape is being resized or not.
         * @property {boolean} [resizing=false]
         */
        this.resizing = false;
        /**
         * This shape was repainted.
         * @property {boolean}
         */
        this.repainted = false;
        /**
         * Determines whether a shape has fixed Dimensions or not
         * @property boolean
         */
        this.fixed  = true;
        /**
         * Determines if the shape's been dropped to a different container
         * @property {boolean}
         */
        this.changedContainer = false;
        /**
         * Determines whether this shape will be created considering its top-left
         * coordinates or its center
         * @property {boolean}
         */
        this.topLeftOnCreation = false;

        // set defaults
        Shape.prototype.initObject.call(this, options);
    };

// inherits from JCoreObject
    Shape.prototype = new BehavioralElement();

    /**
     * Type of each instance of this class
     * @property {String}
     */
    Shape.prototype.type = "Shape";

    /**
     * Family of each instance of this class
     * @property {String}
     */
    Shape.prototype.family = "Shape";

    /**
     * Instance of RegularDragBehavior (avoiding the creation of multiple same instances)
     * @property {DragBehavior} [noDragBehavior=null]
     */
    Shape.prototype.noDragBehavior = null;

    /**
     * Instance of RegularDragBehavior (avoiding the creation of multiple same instances)
     * @property {DragBehavior} [regularDragBehavior=null]
     */
    Shape.prototype.regularDragBehavior = null;

    /**
     * Instance of ConnectionDragBehavior (avoiding the creation of multiple same instances)
     * @property {ConnectionDragBehavior} [connectionDragBehavior=null]
     */
    Shape.prototype.connectionDragBehavior = null;

    /**
     * Instance of CustomShapeDragBehavior (avoiding the creation of multiple same instances)
     * @property {CustomShapeDragBehavior} [customShapeDragBehavior=null]
     */
    Shape.prototype.customShapeDragBehavior = null;

    /**
     * Corner resize identifiers (for jQueryUI Resizable handles)
     * @property {Array} [cornersIdentifiers=['nw', 'ne', 'se', 'sw']]
     */
    Shape.prototype.cornersIdentifiers = ['nw', 'ne', 'se', 'sw'];

    /**
     * Mid resize identifiers (for jQueryUI Resizable handles)
     * @property {Array} [midPointIdentifiers=['n', 'e', 's', 'w']]
     */
    Shape.prototype.midPointIdentifiers = ['n', 'e', 's', 'w'];

    /**
     * Constant for the maximum z-index
     * @property {number} [MAX_ZINDEX=100]
     */
    Shape.prototype.MAX_ZINDEX = 100;

    /**
     * Constant for the default radius used in the class Arc
     * @property {number} [DEFAULT_RADIUS=6]
     */
    Shape.prototype.DEFAULT_RADIUS = 6;

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} options The object that contains old points and new points
     * @private
     */
    Shape.prototype.initObject = function (options) {
        var defaults = {
            topLeft : false,
            resizeBehavior: "no",
            resizeHandlers: {
                type: "None",
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
            drag : "disabled"
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaults, options);

        this.resizeBehavior = this.resizeBehaviorFactory(defaults.resizeBehavior);
//    this.midResizeHandlers = new ArrayList();
//    this.cornerResizeHandlers = new ArrayList();
        if (defaults.drag !== "disabled") {
            this.setDragBehavior(defaults.drag);
        }
        this.createHandlers(defaults.resizeHandlers.type,
            defaults.resizeHandlers.total,
            defaults.resizeHandlers.resizableStyle,
            defaults.resizeHandlers.nonResizableStyle);
        this.topLeftOnCreation = defaults.topLeft;
    };

    /**
     * Creates handlers according to the `number` of handlers, the `type` of handlers (currently only Rectangle
     * is supported), the `resizableStyle` (created in `this.initObject`) and the `nonResizableStyle`
     * (created in `this.initObject`).
     * @param {string} type
     * @param {number} number
     * @param {Object} resizableStyle
     * @param {Object} nonResizableStyle
     * @chainable
     */
    Shape.prototype.createHandlers = function (type, number, resizableStyle,
                                               nonResizableStyle) {
        if (type === "Rectangle") {

            var i;

            //First determine how many ResizeHandlers we are to create
            if (!number || (number !== 8 &&
                number !== 4 && number !== 0)) {
                number = 4;
            }
            //Then insert the corners first
            for (i = 0; i < number && i < 4; i += 1) {
                this.cornerResizeHandlers.insert(
                    new ResizeHandler({
                        parent: this,
                        zOrder: Style.MAX_ZINDEX + 3,
                        representation: new Rectangle(),
                        orientation: this.cornersIdentifiers[i],
                        resizableStyle: resizableStyle,
                        nonResizableStyle: nonResizableStyle
                    })
                );
            }
            //subtract 4 just added resize points to the total
            number -= 4;
            //add the rest to the mid list
            for (i = 0; i < number; i += 1) {
                this.midResizeHandlers.insert(
                    new ResizeHandler({
                        parent: this,
                        zOrder: Style.MAX_ZINDEX + 3,
                        representation: new Rectangle(),
                        orientation: this.midPointIdentifiers[i],
                        resizableStyle: resizableStyle,
                        nonResizableStyle: nonResizableStyle
                    })
                );
            }
        }
        return this;
        //console.log(this.cornerResizeHandlers.asArray());
        //console.log(this.midResizeHandlers.asArray());
    };

    /**
     * Updates the position of the handlers using `this.cornerResizeHandlers` and `this.midResizeHandlers`.
     * NOTE: There's a prerequisite to call this method, `this.setDimensions` must be called first
     * because it updated the arrays used by this method.
     * @chainable
     */
    Shape.prototype.updateHandlers = function () {
        var handler,
            i;
        for (i = 0; i < this.cornerResizeHandlers.getSize(); i += 1) {
            handler = this.cornerResizeHandlers.get(i);
            handler.setPosition(this.xCorners[i] -
                Math.round(handler.width / 2) - 1,
                this.yCorners[i] - Math.round(handler.height / 2) - 1);
        }
        for (i = 0; i < this.midResizeHandlers.getSize(); i += 1) {
            handler = this.midResizeHandlers.get(i);
            handler.setPosition(this.xMidPoints[i] -
                Math.round(handler.width / 2) - 1,
                this.yMidPoints[i] - Math.round(handler.height / 2) - 1);
        }
        return this;
    };

    /**
     * Sets the visibility of the resize handlers
     * @param {boolean} visible
     * @chainable
     */
    Shape.prototype.showOrHideResizeHandlers = function (visible) {

        var i;
        if (!visible) {
            visible = false;
        }
        for (i = 0; i < this.cornerResizeHandlers.getSize(); i += 1) {
            this.cornerResizeHandlers.get(i).setVisible(visible);
        }

        for (i = 0; i < this.midResizeHandlers.getSize(); i += 1) {
            this.midResizeHandlers.get(i).setVisible(visible);
        }
        return this;
    };

    /**
     * Applies a predefined style to its handlers (which can be resizable style or non resizable style)
     * @param {string} styleType
     * @chainable
     */
    Shape.prototype.applyStyleToHandlers = function (styleType) {
        var i;
        for (i = 0; i < this.cornerResizeHandlers.getSize(); i += 1) {
            this.cornerResizeHandlers.get(i)[styleType].applyStyle();
        }

        for (i = 0; i < this.midResizeHandlers.getSize(); i += 1) {
            this.midResizeHandlers.get(i)[styleType].applyStyle();
        }
        return this;
    };

    /**
     * Attaches events to this shape (currently mousedown, mouseup and click events).
     *
     * This method also instantiates the behaviors defined in the configuration options of the object,
     * the behaviors instantiated are:
     *
     * - drag behavior
     * - drop behavior
     * - resize behavior
     *
     * @chainable
     */
    Shape.prototype.attachListeners = function () {
        var $shape = $(this.html);
        $shape.on("mousedown", this.onMouseDown(this));
        $shape.on("mouseup", this.onMouseUp(this));
        $shape.on("click", this.onClick(this));
        this.updateBehaviors();
        return this;
    };

    /**
     * @event mousedown
     * Moused down callback fired when the user mouse downs on the `shape`
     * @param shape
     */
    Shape.prototype.onMouseDown = function (shape) {
        return function (e, ui) {
        };
    };

    /**
     * @event mouseup
     * Moused up callback fired when the user mouse ups on the `shape`
     * @param shape
     */
    Shape.prototype.onMouseUp = function (shape) {
        return function (e, ui) {
        };
    };

    /**
     * @event click
     * Click callback fired when the user clicks on the `shape`
     * @param shape
     */
    Shape.prototype.onClick = function (shape) {
        return function (e, ui) {
        };
    };

    /**
     * Creates the HTML representation of the shape, besides calling the method `createHTML` of
     * the method of its parent, it also adds the resize handlers to the DOM.
     * @returns {HTMLElement}
     */
    Shape.prototype.createHTML = function () {
        var i;

        // call the prototype's createHTML
        BehavioralElement.prototype.createHTML.call(this);

        // add the handlers
        for (i = 0; i < this.cornerResizeHandlers.getSize(); i += 1) {
            this.addResizeHandler(this.cornerResizeHandlers.get(i),
                this.xCorners[i], this.yCorners[i]);
        }
        for (i = 0; i < this.midResizeHandlers.getSize(); i += 1) {
            this.addResizeHandler(this.midResizeHandlers.get(i),
                this.xMidPoints[i], this.yMidPoints[i]);
        }
        return this.html;
    };

    /**
     * Creates an instance of one inherited class of dragBehavior according to the `type` (the instantiation
     * occurs only once per type).
     *
     * Here's the relation between `type` and the new instances created:
     *
     * - regular => {@link RegularDragBehavior}
     * - connection => {@link ConnectionDragBehavior}
     * - customshapedrag => {@link CustomShapeDragBehavior}
     * - any other string (including 'nodrag') => {@link NoDragBehavior}
     *
     * @param {string} type
     * @return {DragBehavior}
     */
    Shape.prototype.dragBehaviorFactory = function (type) {
        if (type === "regular") {
            if (!this.regularDragBehavior) {
                this.regularDragBehavior = new RegularDragBehavior();
            }
            return this.regularDragBehavior;
        }
        if (type === "connection") {
            if (!this.connectionDragBehavior) {
                this.connectionDragBehavior = new ConnectionDragBehavior();
            }
            return this.connectionDragBehavior;
        }
        if (type === "customshapedrag") {
            if (!this.customShapeDragBehavior) {
                this.customShapeDragBehavior = new CustomShapeDragBehavior();
            }
            return this.customShapeDragBehavior;
        }
        if (!this.noDragBehavior) {
            this.noDragBehavior = new NoDragBehavior();
        }
        return this.noDragBehavior;
    };

    /**
     * Returns true if this object is draggable
     * @return {boolean}
     */
    Shape.prototype.isDraggable = function () {
        return this.dragBehavior &&
        this.dragBehavior.type !== "NoDragBehavior";
    };

    /**
     * Sets the determined drag behavior to `this` by calling `this.dragBehaviorFactory` (which creates or returns
     * the instance according to `behavior`) and attaches the drag events to `this`.
     * @param {String} behavior
     * @chainable
     */
    Shape.prototype.setDragBehavior = function (behavior) {
        this.dragBehavior = this.dragBehaviorFactory(behavior);
        if (this.html && this.dragBehavior) {
            // can't extend this.savedOptions with this behavior because it changes
            // dynamically to connect or nodrag
            this.dragBehavior.attachDragBehavior(this);
        }
        return this;
    };

    /**
     * Updates the behaviors of this shape (this method is called from `this.attachListeners`).
     * This is the method that actually initializes jQueryUI's plugins (during the creation of the
     * instance of this shapes, the shape's behaviors are initialized but the init that they do
     * initialize jQuery's UI plugins is done through `[behavior].init`).
     * @chainable
     */
    Shape.prototype.updateBehaviors = function () {
        BehavioralElement.prototype.updateBehaviors.call(this);
        if (this.dragBehavior) {
            this.dragBehavior.attachDragBehavior(this);
        }
        if (this.resizeBehavior) {
            this.resizeBehavior.init(this);
        }
        return this;
    };

    /**
     * Adds a `resizeHandler` to the shape at `[x, y]`
     * @param {ResizeHandler} resizeHandler
     * @param {number} x
     * @param {number} y
     * @chainable
     */
    Shape.prototype.addResizeHandler = function (resizeHandler, x, y) {
        if (!this.html) {
            return;
        }
        //console.log(resizeHandler.getHTML());
        this.html.appendChild(resizeHandler.getHTML());

        resizeHandler.setPosition(x - Math.round(resizeHandler.width / 2) - 1,
            y - Math.round(resizeHandler.height / 2) - 1);
        resizeHandler.setCategory("resizable");
        return this;
    };


    /**
     * Paints the shape performing the following actions:
     *
     * - Paints its resize handlers
     * - Applies the predefined style according to the resize behavior it has
     *
     * @chainable
     */
    Shape.prototype.paint = function () {
        var i,
            styleToApply;

//    // apply predefined style
//    this.style.applyStyle();

        for (i = 0; i < this.cornerResizeHandlers.getSize(); i += 1) {
            this.cornerResizeHandlers.get(i).paint();
        }
        for (i = 0; i < this.midResizeHandlers.getSize(); i += 1) {
            this.midResizeHandlers.get(i).paint();
        }

        // apply style to the handlers
        if (this.resizeBehavior) {
            styleToApply = this.resizeBehavior.type === "NoResizeBehavior" ?
                "nonResizableStyle" : "resizableStyle";
            this.applyStyleToHandlers(styleToApply);
        }

        return this;
    };

    Shape.prototype.updateHTML = function () {
        return this;
    };

    /**
     * Detaches `this` HTML from the DOM (also removing it from `canvas.customShapes` or `canvas.regularShapes`)
     * @chainable
     */
    Shape.prototype.saveAndDestroy = function () {
        // save the html but detach it from the DOM
        this.html = $(this.html).detach()[0];
        this.canvas.removeFromList(this);
        return this;
    };

    /**
     * Updates the dimensions of this shape according to the dimensions and
     * positions of its children
     * @param {number} newMargin Padding to be added when a children is near the edge
     * @chainable
     */
    Shape.prototype.updateSize = function (newMargin) {
        var children = this.children,
            limits = children.getDimensionLimit(),
            left = limits[3],
            top = limits[0],
            right = limits[1],
            bottom = limits[2],
            newLeft = this.getX(),
            newTop = this.getY(),
            newWidth = this.getWidth(),
            newHeight = this.getHeight(),
            margin,
            diffX = 0,
            diffY = 0,
            positionShift = false,
            dimensionIncrement = false;

        if (newMargin !== "undefined") {
            margin = newMargin;
        } else {
            margin = 15;
        }

        if (left < 0) {
            diffX = margin - left;
            positionShift = true;
            this.oldX = this.x;
            this.oldAbsoluteX = this.x;
            this.oldY = this.y;
            this.oldAbsoluteY = this.absoluteY;
        }

        if (top < 0) {
            diffY = margin - top;
            positionShift = true;
            this.oldX = this.x;
            this.oldAbsoluteX = this.x;
            this.oldY = this.y;
            this.oldAbsoluteY = this.absoluteY;
        }

        newLeft -= diffX;
        newTop -=  diffY;
        newWidth += diffX;
        newHeight += diffY;

        if (right > this.width) {
            newWidth += right - this.width + margin;
            dimensionIncrement = true;
            this.oldWidth = this.width;
        }
        if (bottom > this.height) {
            newHeight += bottom - this.height + margin;
            dimensionIncrement = true;
            this.oldHeight = this.height;
        }

        // move the shape to the new coordinates
        this.setPosition(newLeft, newTop);

        // TODO: CHECK WHERE THIS FUNCTION MUST GO
        // update the positions of its ports
        //this.updatePortsPosition(newWidth - this.width, newHeight - this.height);

        // update the shape's dimension
        this.setDimension(newWidth, newHeight);

        // custom triggers
        if (positionShift) {
            this.changePosition(this.oldX, this.oldY,
                this.absoluteX, this.absoluteY);
        }
        if (dimensionIncrement) {
            this.changeSize(this.oldWidth, this.oldHeight);
        }

        // move the children
        this.updateChildrenPosition(diffX, diffY);

        return this;
    };

    /**
     * Applies the actual zoom scale to the corresponding shape
     * @chainable
     */
    Shape.prototype.applyZoom = function () {
//    var zoomFactor = this.canvas.getZoomFactor(),
//        zoomIndex = this.canvas.getZoomPropertiesIndex();

        this.refreshShape();
        return this;
    };

    /**
     * Sets the dimension of this shape, it also updates the arrays `this.xCorners, this.yCorners, this.xMidPoints
     * and this.yMidPoints`
     * @param {number} width
     * @param {number} height
     * @chainable
     */
    Shape.prototype.setDimension = function (width, height) {
        BehavioralElement.prototype.setDimension.call(this, width, height);
        if (this.xCorners) {
            this.xCorners = [0, Math.round(this.zoomWidth), Math.round(this.zoomWidth), 0];
            this.yCorners = [0, 0, Math.round(this.zoomHeight), Math.round(this.zoomHeight)];
            this.xMidPoints = [Math.round(this.zoomWidth / 2), Math.round(this.zoomWidth),
                Math.round(this.zoomWidth / 2), 0];
            this.yMidPoints = [0, Math.round(this.zoomHeight / 2), Math.round(this.zoomHeight),
                Math.round(this.zoomHeight / 2)];
            this.updateHandlers();
        }
        return this;
    };

    /**
     * Sets some variables that store what changed during the process of changing the parent and also
     * triggers `changeElement` using those variables.
     *
     * The variables saved in {@link Canvas#updatedElement} are:
     *
     * - x (old x and new x)
     * - y (old y and new y)
     * - absoluteX (old absoluteX and new absoluteX)
     * - absoluteY (old absoluteY and new absoluteY)
     * - parent (old parent and new parent)
     *
     * @param {number} oldX
     * @param {number} oldY
     * @param {number} oldAbsoluteX
     * @param {number} oldAbsoluteY
     * @param {Object} oldParent
     * @param {Canvas} canvas
     * @chainable
     */
    Shape.prototype.changeParent = function (oldX, oldY,
                                             oldAbsoluteX, oldAbsoluteY,
                                             oldParent, canvas) {
        var fields = [
            {
                "field" : "x",
                "oldVal" : oldX,
                "newVal" : this.x
            },
            {
                "field" : "y",
                "oldVal" : oldY,
                "newVal" : this.y
            },
            {
                "field" : "absoluteX",
                "oldVal" : oldAbsoluteX,
                "newVal" : this.absoluteX
            },
            {
                "field" : "absoluteY",
                "oldVal" : oldAbsoluteY,
                "newVal" : this.absoluteY
            },
            {
                "field" : "parent",
                "oldVal" : oldParent,
                "newVal" : this.parent
            }
        ];
        canvas.updatedElement = {
            "id" : this.id,
            "type" : this.type,
            "fields" : fields,
            "relatedObject" : this
        };
        $(canvas.html).trigger("changeelement");
        return this;
    };

    /**
     * Sets some variables that store what changed during the process of resizing and also
     * triggers `changeElement` using those variables.
     *
     * The variables saved in {@link Canvas#updatedElement} are:
     *
     * - width (old width and new width)
     * - height (old height and new height)
     *
     * @param {number} oldWidth
     * @param {number} oldHeight
     * @chainable
     */
    Shape.prototype.changeSize = function (oldWidth, oldHeight) {
        var canvas = this.canvas,
            fields = [
                {
                    "field" : "width",
                    "oldVal" : oldWidth,
                    "newVal" : this.width
                },
                {
                    "field" : "height",
                    "oldVal" : oldHeight,
                    "newVal" : this.height
                }
            ];
        canvas.updatedElement = {
            "id" : this.id,
            "type" : this.type,
            "fields" : fields,
            "relatedObject" : this
        };
        $(canvas.html).trigger("changeelement");
        return this;
    };

    /**
     * Sets some variables that store what changed during the process of changing its position and also
     * triggers `changeElement` using those variables.
     *
     * The variables saved in {@link Canvas#updatedElement} are:
     *
     * - x (old x and new x)
     * - y (old y and new y)
     * - absoluteX (old absoluteX and new absoluteX)
     * - absoluteY (old absoluteY and new absoluteY)
     *
     * @param {number} oldX
     * @param {number} oldY
     * @param {number} oldAbsoluteX
     * @param {number} oldAbsoluteY
     * @chainable
     */
    Shape.prototype.changePosition = function (oldX, oldY, oldAbsoluteX,
                                               oldAbsoluteY) {
        //TODO REVIEW WITH ZOOM OPTIONS
        var canvas = this.canvas,
            fields = [
                {
                    "field" : "x",
                    "oldVal" : oldX,
                    "newVal" : this.x
                },
                {
                    "field" : "y",
                    "oldVal" : oldY,
                    "newVal" : this.y
                }
//            {
//                "field" : "absoluteX",
//                "oldVal" : oldAbsoluteX,
//                "newVal" : this.absoluteX
//            },
//            {
//                "field" : "absoluteY",
//                "oldVal" : oldAbsoluteY,
//                "newVal" : this.absoluteY
//            }

            ];
        canvas.updatedElement = [{
            "id" : this.id,
            "type" : this.type,
            "fields" : fields,
            "relatedObject" : this
        }];
        $(canvas.html).trigger("changeelement");
        return this;
    };

    /**
     * Sets whether the dimensions are fixed or not
     * @param {boolean} fixed
     * @chainable
     */
    Shape.prototype.setFixed = function (fixed) {
        if (typeof fixed === "boolean") {
            this.fixed = fixed;
        }
        return this;
    };

    /**
     * Adds `value` to the z-index of the shape (considering the z-index of its parent), since a shape might have
     * children, this method must increase the z-index of each child recursively.
     * @param {Shape} shape
     * @param {number} value
     * @chainable
     */
    Shape.prototype.fixZIndex = function (shape, value) {

        var i,
            anotherShape,
            port,
            srcShape,
            destShape,
            srcShapeZIndex,
            destShapeZIndex,
            parentZIndex;

        parentZIndex = shape.parent.html.style.zIndex;
        shape.setZOrder(
            parseInt(parentZIndex, 10) + value + parseInt(shape.defaultZOrder, 10)
        );

        // fix children zIndex
        for (i = 0; i < shape.children.getSize(); i += 1) {
            anotherShape = shape.children.get(i);
            anotherShape.fixZIndex(anotherShape, 0);
        }

        // fix connection zIndex
        // only if it has ports
        if (shape.ports) {
            for (i = 0; i < shape.ports.getSize(); i += 1) {
                port = shape.ports.get(i);
                srcShape = port.connection.srcPort.parent;
                destShape = port.connection.destPort.parent;
                srcShapeZIndex = parseInt(srcShape.html.style.zIndex, 10);
                destShapeZIndex = parseInt(destShape.html.style.zIndex, 10);
                port.connection.style.addProperties({
                    zIndex: Math.max(srcShapeZIndex + 1, destShapeZIndex + 1)
                });
            }
        }
        return this;
    };

    /**
     * Increases the zIndex of this shape by Style.MAX_ZINDEX
     * @chainable
     */
    Shape.prototype.increaseZIndex = function () {
        this.fixZIndex(this, Style.MAX_ZINDEX);
        return this;
    };

    /**
     * Decreases the zIndex of this shape back to normal
     * @chainable
     */
    Shape.prototype.decreaseZIndex = function () {
        this.fixZIndex(this, 0);
        return this;
    };

    /**
     * Increases the z-index of `shapes`'s ancestors by one
     * @param shape
     * @chainable
     */
    Shape.prototype.increaseParentZIndex = function (shape) {
        if (shape.family !== "Canvas") {
            shape.style.addProperties({
                zIndex: parseInt(shape.html.style.zIndex, 10) + 1
            });
            shape.increaseParentZIndex(shape.parent);
        }
        return this;
    };

    /**
     * Decreases the zIndex of `shapes`'s ancestors by one by one
     * @param shape
     * @chainable
     */
    Shape.prototype.decreaseParentZIndex = function (shape) {
        if (shape && shape.family !== "Canvas") {
            shape.style.addProperties({
                zIndex: parseInt(shape.html.style.zIndex, 10) - 1
            });
            shape.decreaseParentZIndex(shape.parent);
        }
        return this;
    };

    /**
     * Creates an resizeBehavior instance according to the type
     * @param {string} type
     * @return {ResizeBehavior}
     * @throws {Error} Throws an error if the parameter is not valid
     */
    Shape.prototype.resizeBehaviorFactory = function (type) {
        if (type === "NoResize" || type === "no") {
            return new NoResizeBehavior();
        }
        if (type === "Resize" || type === "yes") {
            return new RegularResizeBehavior();
        }
        throw new Error("resizeBehaviorFactory(): parameter is not valid");
    };

    /**
     * Sets the determined resize behavior to `this` by calling `this.resizeBehaviorFactory` (which creates or returns
     * the instance according to `behavior`) and attaches the drag events to `this`.
     * @param {String} behavior
     * @chainable
     */
    Shape.prototype.setResizeBehavior = function (behavior) {
        if (this.html && behavior) {
            this.resizeBehavior = this.resizeBehaviorFactory(behavior);
            this.resizeBehavior.init(this);
        }
        return this;
    };

    /**
     * Returns whether the shape is resizable or not
     * @return {boolean}
     */
    Shape.prototype.isResizable = function () {
        return this.resizeBehavior &&
        this.resizeBehavior.type !== "NoResizeBehavior";
    };

    /**
     * Updates the position and dimensions of the shape (useful when the parent of this shape
     * has changed positions or dimensions).
     * @chainable
     */
    Shape.prototype.refreshShape = function () {
        this.setPosition(this.x, this.y)
            .setDimension(this.width, this.height);
        return this;
    };

    /**
     * Abstract method intended to refresh the connections of a shapes
     * @abstract
     * @chainable
     */
    Shape.prototype.refreshConnections = function () {
        return this;
    };

    /**
     * Updates the positions of the children of this shape recursively
     * @param {boolean} onCommand
     * @chainable
     */
    Shape.prototype.refreshChildrenPositions = function (onCommand) {
        var i,
            children = this.children,
            child,
            relatedShapes = [],
            coordinates = [];
        for (i = 0; i < children.getSize(); i += 1) {
            child = children.get(i);
            child.setPosition(child.getX(), child.getY());
            if (onCommand) {
                child.refreshConnections(false);
            }
            relatedShapes.push(child);
            coordinates.push({
                x : child.getX(),
                y:  child.getY()
            });
            child.refreshChildrenPositions(onCommand);
        }
        this.canvas.triggerPositionChangeEvent(relatedShapes, coordinates,
            coordinates);
        return this;
    };

    /**
     * Fix connections ports on resize (a container must call this method on resize to reposition its
     * ports on resize and the ports of its children)
     * @param {boolean} resizing
     * @param {boolean} root The currentShape is root?
     * @chainable
     */
    Shape.prototype.fixConnectionsOnResize = function (resizing, root) {

        var i,
            port,
            child,
            connection,
            zoomFactor = this.canvas.zoomFactor;

        if (root) {
            if (this.ports) {
                // connections
                for (i = 0; i < this.ports.getSize(); i += 1) {
                    port = this.ports.get(i);
                    connection = port.connection;
                    this.recalculatePortPosition(port);

                    connection.disconnect().connect();
                    if (!this.resizing) {
                        connection.setSegmentMoveHandlers();
                        connection.checkAndCreateIntersectionsWithAll();
                    }
                }
            }
        } else {
            if (this.ports) {
                // connections
                for (i = 0; i < this.ports.getSize(); i += 1) {
                    // for each port update its absolute position and
                    // repaint its connections
                    port = this.ports.get(i);
                    connection = port.connection;
                    port.setPosition(port.x, port.y);

                    connection.disconnect().connect();
                    if (!this.resizing) {
                        connection.setSegmentMoveHandlers();
                        connection.checkAndCreateIntersectionsWithAll();
                    }
                }
            }
        }

        // children
        for (i = 0; i < this.children.getSize(); i += 1) {
            child = this.children.get(i);
            child.setPosition(child.x, child.y);
            child.fixConnectionsOnResize(child.resizing, false);
        }
        return this;
    };

    /**
     * Serializes this object.
     *
     * This method adds the following to the object retrieved from {@link BehavioralElement#stringify}:
     *
     * - resizeBehavior
     * - resizeHandlers (as defined in the config options)
     *
     * @return {Object}
     */
    Shape.prototype.stringify = function () {
        var inheritedJSON = BehavioralElement.prototype.stringify.call(this),
            type = (this.savedOptions.resizeHandlers &&
                this.savedOptions.resizeHandlers.type) || 'Rectangle',
            total = (this.savedOptions.resizeHandlers &&
                this.savedOptions.resizeHandlers.total) || 4,
            thisJSON = {
                resizeBehavior: this.savedOptions.resizeBehavior,
                resizeHandlers: {
                    type: type,
                    total: total
                }
            };
        $.extend(true, inheritedJSON, thisJSON);
        return inheritedJSON;
    };

    /**
     * Sets the center of the shape
     * @param {number} newCenter
     * @throws {Error} parameter newCenter is not an instance of points
     * @chainable
     */
    Shape.prototype.setCenter = function (newCenter) {
        if (newCenter instanceof Point) {
            this.center = newCenter;
        } else {
            throw new Error("setCenter(): argument is not an instance of Point");
        }
        return this;
    };

    /**
     * Sets the Parent of the shape (might also trigger the custom event change element if the parameter
     * triggerChange is set to true)
     * @chainable
     * @param {Shape} newParent
     * @param {boolean} triggerChange
     */
    Shape.prototype.setParent = function (newParent, triggerChange) {
        //if(newParent.type === "Shape" || newParent.type === "StartEvent" ||
        //newParent.type === "EndEvent")
        if (newParent) {

            if (this.canvas && triggerChange) {
                this.canvas.updatedElement = {
                    "id" : this.id,
                    "type" : this.type,
                    "fields" : [{
                        "field" : "parent",
                        "oldVal" : this.parent,
                        "newVal" : newParent
                    }]
                };
                $(this.canvas.html).trigger("changeelement");
            }
            this.parent = newParent;
        }
// else {
//        throw new Error("setParent() : paramater newParent is null");
//    }
        return this;
    };

    /**
     * Sets the oldParent of the shape.
     * @chainable
     * @param {Shape} oldParent
     */
    Shape.prototype.setOldParent = function (oldParent) {
        this.oldParent = oldParent;
        return this;
    };

    /**
     * Gets the center of the shape.
     * @return {Point}
     */
    Shape.prototype.getCenter = function () {
        return this.center;
    };

    /**
     * Gets the parent of the shape.
     * @return {Shape / Canvas}
     */
    Shape.prototype.getParent = function () {
        return this.parent;
    };

    /**
     * Gets the oldParent of the shape
     * @return {Shape / Canvas}
     */
    Shape.prototype.getOldParent = function () {
        return this.oldParent;
    };

    /**
     * Gets the handles IDs used to initialize jQueryUI's resizable plugin
     * @return {Object}
     */
    Shape.prototype.getHandlesIDs = function () {
        var handlesObject = {},     // the handles of the shape
            i;                      // iterator

        for (i = 0; i < this.midPointIdentifiers.length; i += 1) {
            handlesObject[this.midPointIdentifiers[i]] = '#' +
            this.midPointIdentifiers[i] + this.id +
            'resizehandler';
        }
        for (i = 0; i < this.cornersIdentifiers.length; i += 1) {
            handlesObject[this.cornersIdentifiers[i]] = '#' +
            this.cornersIdentifiers[i] + this.id +
            'resizehandler';
        }
        return handlesObject;
    };

    /**
     * @class Label
     * Creates a an object that can in order to illustrate text in the HTML it can
     * be inside a shape or by its own directly in the canvas
     *
     *              //i.e.
     *              var label = new Label({
 *                  //message that the label will display
 *                  message: "This is a label",
 *                  //orientation of the text, can be vertical or horizontal
 *                  orientation: "horizontal",
 *                  //font-family
 *                  fontFamily: "arial",
 *                  //size of the label object not the text
 *                  size: 80,
 *                  //position where it will be located relative to its
 *                  //container
 *                  position: {
 *                  //location can be center, top, bottom among others,
 *                  //relative to its container
 *                      location: "center",
 *                  //How many pixels in the x coordinate and y coordinate
 *                  //we want to move it from its location
 *                      diffX: 2,
 *                      diffY: -1
 *                  },
 *                  //option that determines if the label should update its
 *                  //parent size when it grows
 *                  updateParent: false,
 *                  //label's parent
 *                  parent: canvas
 *
 *              });
     * @extends Shape
     *
     * @constructor
     * Creates an instance of the class
     * @param {Object} options configuration options for the label object
     * @cfg {String} [message=""] Message to be displayed
     * @cfg {String} [orientation="horizontal"] Orientation of the text, can be
     * vertical or horizontal
     * @cfg {String} [fontFamily="arial"] Font family we want the message to be
     * displayed with
     * @cfg {number} [size=0] Size of the label object
     * @cfg {Object} [position={
 *     location: "none",
 *     diffX: 0,
 *     diffY: 0
 * }] Location where we want the label to be positioned relative to its parent
     * @cfg {boolean} [updateParent=false] Determines whether the parent's size
     * should be updated when the label increases its size
     * @cfg {Object} [parent=null] Label's parent
     */
    Label = function (options) {
        Shape.call(this, options);
        /**
         * The percentage of this label respect to the width of the shape
         * in the range(0, 1)
         * @property {number}
         */
        this.xPercentage = 0;
        /**
         * The percentage of this label respect to the height of the shape
         * in the range(0, 1)
         * @property {number}
         */
        this.yPercentage = 0;
        /**
         * Message that the label will display
         * @property {String}
         */
        this.message = "";
        /**
         * Orientation of the label
         * @property {String}
         */
        this.orientation = "";
        /**
         * HTML span that holds the text display
         * @property {HTMLElement}
         */
        this.text = null;
        /**
         * Determines whether a label's parent should be updated when a label
         * increases its size
         * @property {boolean}
         */
        this.updateParent = false;
        /**
         * Determines the type of overflow this label should have
         * @property {boolean}
         */
        this.overflow = false;
        /**
         * XXX
         * @property {boolean}
         */
        this.onFocus = false;
        /**
         * Determines the location relative to its parent where this label will be
         * positioned
         * @property {String}
         */
        this.location = "";
        /**
         * x direction pixels that the label will be moved from its location
         * @property {number}
         */
        this.diffX = 0;
        /**
         * y direction pixels that the label will be moved from its location
         * @property {number}
         */
        this.diffY = 0;
        /**
         * Determines the font-size to be used in each zoom scale
         * @property {Array}
         */
        this.fontSizeOnZoom = [];
        /**
         * The font-size that this label will use to display the message
         * @property {number}
         */
        this.fontSize = 0;
        /**
         * html text field for text editing
         * @property {HTMLElement}
         */
        this.textField = null;

        Label.prototype.initObject.call(this, options);
    };


    Label.prototype = new Shape();
    /**
     * Type of all label instances
     * @property {String}
     */
    Label.prototype.type = "Label";
    /**
     * Line height to be considered in the label's message
     * @type {number}
     */
    Label.prototype.lineHeight = 20;


    /**
     * Initializer of the object will all the given configuration options
     * @param {Object} options
     */
    Label.prototype.initObject = function (options) {
        var defaults = {
            message : "New Label",
            orientation : "horizontal",
            fontFamily : "arial",
            size : 0,
            position : {
                location : "none",
                diffX : 0,
                diffY : 0
            },
            overflow : false,
            updateParent : false,
            parent : null,
            updateParentOnLoad: true
        };
        this.fontSizeOnZoom = [6, 8, 10, 13, 15];
        $.extend(true, defaults, options);
        this.setMessage(defaults.message)
            .setOverflow(defaults.overflow)
            .setUpdateParent(defaults.updateParent)
            .setOrientation(defaults.orientation)
            .setFontFamily(defaults.fontFamily)
            .setFontSize(defaults.size)
            .setParent(defaults.parent)
            .updateDimension()
            .setLabelPosition(defaults.position.location, defaults.position.diffX,
            defaults.position.diffY)
            .setUpdateParentOnLoad(defaults.updateParentOnLoad);

    };
    /**
     * Attach the corresponding listeners to this label
     * @chainable
     */
    Label.prototype.attachListeners = function () {
        var $label = $(this.html);
        if (!this.html) {
            return this;
        }
        Shape.prototype.attachListeners.call(this);
        $(this.textField).on("keydown", function(e) {
            e.stopPropagation();
        });
        $label.on("dblclick", this.onDblClick(this));
        return this;
    };
    /**
     * Creates the HTML of the label, the input text and the span for displaying the
     * message
     * @return {HTMLElement}
     */
    Label.prototype.createHTML = function () {
        Shape.prototype.createHTML.call(this);
        this.html.style.textAlign = "center";
        this.html.style.align = "center";
        this.html.style.fontFamily = this.fontFamily;
        this.html.style.fontSize = this.fontSize + "pt";
        this.textField = document.createElement("input");
        this.textField.style.width = "200px";
        this.textField.style.position = "absolute";
        this.textField.style.display = "none";
        this.text = document.createElement("span");
        this.text.style.width = "auto";
        this.text.style.height = "auto";
        this.text.style.lineHeight = this.lineHeight * this.canvas.zoomFactor + "px";
        this.text.innerHTML = this.message;
        this.html.appendChild(this.text);
        this.html.appendChild(this.textField);
        this.html.style.zIndex = 2;
        return this.html;
    };
    /**
     * Displays the style of the label and adds the corresponding classes for
     * rotation
     * @chainable
     */
    Label.prototype.paint = function () {
        var $label = $(this.text);

        this.text.style.lineHeight = this.lineHeight * this.canvas.zoomFactor + "px";
        this.textField.value = this.message;
        this.text.innerHTML = this.message;

        this.html.style.verticalAlign = "middle";
        if (this.overflow) {
            this.html.style.overflow = "hidden";
        } else {
            this.html.style.overflow = "none";
        }

        this.displayText(true);
        if (this.orientation === "vertical") {
            $label.addClass('rotateText');
        } else {
            $label.removeClass('rotateText');
        }

        return this;

    };
    /**
     * Displays the label's message in its current orientation or the input text
     * @param {boolean} display true if we want to display the label's message or
     * false for the input text
     * @chainable
     */
    Label.prototype.displayText = function (display) {

        if (display) {
            this.text.style.display = "block";
            this.textField.style.display = "none";
            if (this.orientation === "vertical") {
                this.textField.style.left = "0px";
            }
        } else {
            this.textField.style.display = "block";
            if (this.orientation === "vertical") {
                this.textField.style.left = this.width / 2 - 100 + "px";
            }
            this.text.style.display = "none";
        }
        return this;
    };
    /**
     * Sets the message of this label
     * @param {String} newMessage
     * @chainable
     */
    Label.prototype.setMessage = function (newMessage) {
        this.message = newMessage;
        if (this.text) {
            this.text.innerHTML = this.message;
        }
        return this;
    };
    /**
     * Retrieves the message that this label is displaying
     * @return {String}
     */
    Label.prototype.getMessage = function () {
        return this.message;
    };
    /**
     * Sets the orientation of the text
     * @param {String} newOrientation It can be vertical or horizontal by default
     * @chainable
     */
    Label.prototype.setOrientation = function (newOrientation) {
        var $label;
        this.orientation = newOrientation;
        if (!this.html) {
            return this;
        }
        $label = $(this.text);
        if (newOrientation === "vertical") {
            $label.addClass("rotateText");
            //this.setPosition(this.x - 30, this.y - 30);
        } else {
            $label.removeClass("rotateText");
        }
        return this;
    };
    /**
     * Retrieves the orientation of this label's text
     * @return {String}
     */
    Label.prototype.getOrientation = function () {
        return this.orientation;
    };
    /**
     * Sets the font family of this label's displayed text
     * @param {String} newFontFamily
     * @chainable
     */
    Label.prototype.setFontFamily = function (newFontFamily) {
        this.fontFamily = newFontFamily;
        if (this.html) {
            this.html.style.fontFamily = this.fontFamily;
        }
        return this;
    };

    /**
     * Sets the font-size of this label's displayed text
     * @param {String} newFontSize
     * @chainable
     */
    Label.prototype.setFontSize = function (newFontSize) {
        if (newFontSize === 0) {
            this.fontSize = this.getZoomFontSize();
        } else {
            this.fontSize = newFontSize;
        }
        if (this.html) {
            this.html.style.fontSize = this.fontSize + "pt";
        }
        return this;
    };
    /**
     * Sets the property to determine if a label should update its parent
     * @param {boolean} newUpdateParent
     * @chainable
     */
    Label.prototype.setUpdateParent = function (newUpdateParent) {
        this.updateParent = newUpdateParent;
        return this;
    };
    /**
     * Sets the overflow property of this label
     * @param {boolean} newOverflow
     * @chainable
     */
    Label.prototype.setOverflow = function (newOverflow) {
        this.overflow = newOverflow;
        return this;
    };
    /**
     * Sets the position of the label regarding its parent, considering the location
     * and x and y differentials
     * @param {String} position location where we want to put the label relative to,
     * its parent, it can be top-left, top, top-right, center-left, center,
     * center-right, bottom-left, bottom, bottom-right
     * @param {number} diffX x coordinate pixels to move from its location
     * @param {number} diffY y coordinate pixels to move from its location
     * @chainable
     */
    Label.prototype.setLabelPosition = function (position, diffX, diffY) {
        var x,
            y,
            i,
            width = this.zoomWidth,
            height = this.zoomHeight,
            parent = this.parent,
            parentWidth,
            parentHeight,
            zoomFactor = this.canvas.zoomFactor,
            bottomHeightFactor = 4 * zoomFactor,
            positionString = [
                'top-left',
                'top',
                'top-right',
                'center-left',
                'center',
                'center-right',
                'bottom-left',
                'bottom',
                'bottom-right'
            ],
            orientation,
            orientationIndex = (this.orientation === "vertical") ? 1 : 0,
            positionCoordinates;
        if (!position || position === "") {
            position = "top-left";
        }
        if (diffX === undefined || diffX === null) {
            diffX = 0;
        }
        if (diffY === undefined || diffY === null) {
            diffY = 0;
        }
        if (parent && parent.family !== "Canvas") {
            parentWidth = parent.getZoomWidth();
            parentHeight = parent.getZoomHeight();
            orientation = [
                {x : width / 2, y : 0},
                {x : 0, y : height / 2}
            ];
            positionCoordinates = [
                {
                    x : -width / 2,
                    y : 0
                },
                {
                    x : parentWidth / 2 - width / 2,
                    y:  0
                },
                {
                    x : parentWidth - width / 2,
                    y : 0
                },
                {
                    x : -width / 2,
                    y : parentHeight / 2 - height / 2
                },
                {
                    x : parentWidth / 2 - width / 2,
                    y : parentHeight / 2 - height / 2
                },
                {
                    x : parentWidth - width,
                    y : parentHeight / 2 - height / 2
                },
                {
                    x : -width / 2,
                    y : parentHeight - bottomHeightFactor
                },
                {
                    x : parentWidth / 2 - width / 2,
                    y : parentHeight - bottomHeightFactor
                },
                {
                    x : parentWidth - width / 2,
                    y : parentHeight - bottomHeightFactor
                }
            ];
            for (i  = 0; i < 9; i += 1) {
                if (position === positionString[i]) {
                    this.setPosition(
                        positionCoordinates[i].x / zoomFactor + diffX,
                        positionCoordinates[i].y / zoomFactor + diffY
                    );
                    break;
                }
            }

        }
        this.location = position;
        this.diffX = diffX;
        this.diffY = diffY;
        return this;
    };
    /**
     * Sets boolean value if we need enable aor disable update parent dimension
     * when the label is loaded
     * @param {String} value boolean value
     * @chainable
     */
    Label.prototype.setUpdateParentOnLoad = function (value) {
        this.updateParentOnLoad = value;
        return this;
    };
    /**
     * Hides the span showing the label's message and display the input text ready
     * to be edited
     * @chainable
     */
    Label.prototype.getFocus = function () {
        var $textField = $(this.textField.html);
        this.displayText(false);
        this.canvas.currentLabel = this;
        $($textField).select();
        this.onFocus = true;
        return this;
    };
    /**
     * Hides the input text and display the label's message, and if the message's
     * changed, then it executes the editlabel command
     * @chainable
     */
    Label.prototype.loseFocus = function () {
        var command;
        this.canvas.currentLabel = null;
        if (this.textField.value !== this.message) {
            command = new CommandEditLabel(this, this.textField.value);
            command.execute();
            this.canvas.commandStack.add(command);
            this.setLabelPosition(this.location, this.diffX, this.diffY);
        }
        this.paint();
        this.onFocus = false;
        return this;
    };
    /**
     * On Mouse down hander, used to stop propagation when the label's parent is the
     * canvas
     * @param {Label} label
     * @return {Function}
     */
    Label.prototype.onMouseDown = function (label) {
        return function (e, ui) {
            if (label.parent.family === "Canvas") {
                e.stopPropagation();
            }
        };
    };
    /**
     * On Click handler, used to stop propagation when a label is being edited or
     * its parent is the canvas
     * @param {Label} label
     * @return {Function}
     */
    Label.prototype.onClick = function (label) {
        return function (e, ui) {
            if (label.parent.family === "Canvas") {
                e.stopPropagation();
            }
            if (label.onFocus) {
                e.stopPropagation();
            }
        };
    };
    /**
     * Double Click handler, used in order for this label to get focus and being
     * edited
     * @param {Label} label
     * @return {Function}
     */
    Label.prototype.onDblClick = function (label) {
        return function (e, ui) {
            var canvas = label.getCanvas(),
                $label = $(label.html);
            if (canvas.currentLabel) {
                canvas.currentLabel.loseFocus();
            }
            label.getFocus();

        };
    };
    /**
     * Returns the font-size according to the current zoom scale
     * @return {number}
     */
    Label.prototype.getZoomFontSize = function () {
        var canvas = this.canvas;
        this.fontSize = this.fontSizeOnZoom[canvas.zoomPropertiesIndex];
        return this.fontSize;
    };
    /**
     * Parse the messages in words length.
     * It returns an array with the length of all the words in the message
     * @return {Array}
     */
    Label.prototype.parseMessage = function () {
        var i,
            start = 0,
            result = [],
            word;
        while (this.message.charAt(start) === ' ') {
            start += 1;
        }
        word = 0;
        for (i = start; i < this.message.length; i += 1) {

            if (this.message.charAt(i) === ' ') {
                result.push(word);
                word = 0;
            } else {
                word += 1;
            }
        }
        result.push(word);
        return result;
    };
    /**
     * Updates the dimension of the label, according to its message, and if the
     * updateParent property is true then it will call the corresponding method to
     * update its parent according to the label's size
     * @chainable
     */
    Label.prototype.updateDimension = function (firstTime) {
//    var characterLimit,
//        characterCount,
//        words = [],
//        lines = 0,
//        i = 0,
//        maxWidth = 0,
//        totalCharacters = 0,
//        canvas = this.canvas,
//        characterOnZoom = [3.3, 5, 7, 9.3, 10.5],
//        characterMaxWidth = [4, 6, 8, 10, 12],
//        characterFactor = characterOnZoom[canvas.zoomPropertiesIndex],
//        characterWidth = characterMaxWidth[canvas.zoomPropertiesIndex],
//        zoomFactor = canvas.zoomFactor;
//
//    words = this.parseMessage();
//    for (i = 0; i < words.length; i += 1) {
//        if (maxWidth < words[i]) {
//            maxWidth = words[i];
//        }
//        totalCharacters += words[i] + 1;
//    }
//    totalCharacters -= 1;
//    if (this.orientation === 'vertical') {
//        if (totalCharacters > 0) {
//            this.setDimension((totalCharacters * characterWidth) / zoomFactor,
//                    20);
//        }
//    } else {
//        maxWidth = Math.max(Math.floor((maxWidth * characterWidth)),
//                this.zoomWidth);
//        characterLimit = Math.ceil((maxWidth / characterFactor));
//        i = 0;
//        while (i < words.length) {
//            lines += 1;
//            characterCount = 0;
//            while (characterCount <= characterLimit && i < words.length) {
//                if (words[i] + characterCount > characterLimit) {
//                    if (characterCount !== 0) {
//                        break;
//                    }
//                }
//                characterCount += words[i] + 1;
//                i += 1;
//            }
//        }
//        this.setDimension(maxWidth / zoomFactor, (lines * 20));
//    }
        var divWidth = $(this.text).width(),
            newWidth,
            newHeight;

        newWidth = Math.max(divWidth, this.zoomWidth);
        newHeight = $(this.text).height();

        this.setDimension(newWidth / this.canvas.zoomFactor,
            newHeight / this.canvas.zoomFactor);
        if (this.updateParent) {
            this.updateParentDimension();
        }
        return this;
    };
    /**
     * Apply all properties necessary for this label in a given zoom scale
     * @chainable
     */
    Label.prototype.applyZoom = function () {
        var canvas = this.canvas;
        this.setFontSize(0);
//    this.fontSize = this.fontSizeOnZoom[canvas.zoomPropertiesIndex];
//    this.setDimension(this.width, this.height);
//    this.updateDimension();
        this.paint();
        return this;
    };
    /**
     * Calls the method to update the label's parent dimension according to the
     * label's orientation
     * @chainable
     */
    Label.prototype.updateParentDimension = function () {

        if (this.orientation === "vertical") {
            this.updateVertically();
        } else {
            this.updateHorizontally();
        }
        if (this.parent.html) {
            this.parent.paint();
        }
        return this;
    };
    /**
     * Updates its parent height according to the size of the label
     * @chainable
     */
    Label.prototype.updateVertically = function () {
        var margin = 5,
            parent = this.parent,
            labelWidth = this.zoomWidth,
            newHeight,
            zoomFactor = this.canvas.zoomFactor;
        if (labelWidth > parent.zoomHeight - margin * 2) {
            newHeight = labelWidth + margin * 2;
        } else {
            newHeight = parent.zoomHeight;
        }
        parent.setDimension(parent.width, newHeight / zoomFactor);
        parent.updateChildrenPosition(0, 0);
        parent.refreshConnections();
        this.setLabelPosition(this.location, this.diffX, this.diffY);
        return this;
    };
    /**
     * Updates its parent width and height according to the new label's dimension
     * @chainable
     */
    Label.prototype.updateHorizontally = function () {
        var margin = 5,
            parent = this.parent,
            labelWidth = this.zoomWidth,
            labelHeight = this.zoomHeight,
            newWidth,
            newHeight,
            zoomFactor = this.canvas.zoomFactor;
        if (labelWidth > parent.zoomWidth - margin * 2) {
            newWidth = labelWidth + margin * 2;
        } else {
            newWidth = parent.zoomWidth;
        }
        if (labelHeight > parent.zoomHeight - margin * 2) {
            newHeight = labelHeight + margin * 2;
        } else {
            newHeight = parent.zoomHeight;
        }

        if (!this.updateParentOnLoad) {
            this.updateParentOnLoad = true;
        } else {
            parent.setDimension(newWidth / zoomFactor, newHeight / zoomFactor);
        }
//    parent.setDimension(newWidth / zoomFactor, newHeight / zoomFactor);
//    parent.updateChildrenPosition();
        parent.fixConnectionsOnResize(parent.resizing, true);
        parent.refreshConnections();
        this.setLabelPosition(this.location, this.diffX, this.diffY);
        return this;
    };
    /**
     * Serializes this object
     * @return {Object}
     */
    Label.prototype.stringify = function () {
        // TODO: USE CLASS STYLE IN THE METHODS OF THIS CLASS
        // TODO: COMPLETE THE JSON
        /**
         * inheritedJSON = {
     *     id: #
     *     x: #,
     *     y: #,
     *     width: #,
     *     height: #
     * }
         * @property {Object}
         */
        var inheritedJSON = {},
            thisJSON = {
                id: this.getID(),
                message: this.getMessage(),
                orientation: this.getOrientation(),
                position : {
                    location : this.location,
                    diffX : this.diffX,
                    diffY : this.diffY
                }
            };
        $.extend(true, inheritedJSON, thisJSON);
        return inheritedJSON;

    };

    /**
     * @class CustomShape
     * This is a custom shape, where there can be applied styles, sprites and
     * decoration, it can have connections associated to it by ports, different
     * layers and labels as well
     *              //i.e.
     *              var customShape = new CustomShape({
 *                  //Determines whether the shape will be connected only in its
 *                  //middle points
 *                  connectAtMiddlePoints : true,
 *                  //The layers that will be instantiated with this shape
 *                  layers: [
 *                      {
 *                                                          {
 *                        layerName : "first-layer",
 *                        priority: 2,
 *                        visible: true,
 *                        style: {
 *                        cssClasses: ['bpmn_zoom']
 *                      },
 *                        zoomSprites : ['img_50_start',
 *                        'img_75_start', 'img_100_start',
 *                        'img_125_start', 'img_150_start']
 *                        }, {
 *                        layerName: "second-layer",
 *                        priority: 3,
 *                        visible: true
 *                        }
 *
 *                  ],
 *                  //Labels that belong to this shape
 *                  labels : [
 *                      {
 *                          message: "this is one label",
 *                          position: {
 *                              location : "bottom",
 *                              diffX: 0,
 *                              diffY: 5
 *                          }
 *                      }
 *                  ],
 *                  //The type of connections that are made with this shape,
 *                  //Each type differs of one another for the type of lines
 *                  //used in the connection
 *                  connectionType: "regular"
 *
 *              });
 * @extends Shape
     *
     * @constructor
     * Creates an instance of a CustomShape
     * @param {Object} options configuration options used in a custom shape
     * @cfg {Boolean} [connectAtMiddlePoints=true] Determines whether shape's,
     * connections should be created only in the middle points of its sides
     * @cfg {Array} [layers=[]] Configuration options of all layers that will be,
     * instantiated with this shape
     * @cfg {Array} [labels=[]] Configuration options of all labels that will be
     * instantiated with this shape
     * @cfg {String} [connectionType="regular"] Type of lines that will be used in
     * all connections involving this shape
     */
    CustomShape = function (options) {
        /**
         * List of all the layers associated to this shape
         * @property {ArrayList}
         */
        this.layers = new ArrayList();

        Shape.call(this, options);
        /**
         * List of all the ports associated to this shape
         * @property {ArrayList}
         */
        this.ports = new ArrayList();

        /**
         * List of all the labels associated to this shape
         * @property {ArrayList}
         */
        this.labels = new ArrayList();
        /**
         * List of all the zoom properties in different zoom scales
         * @property {ArrayList}
         */
        this.zoomProperties = new ArrayList();

        /**
         * Inner figure for drawing connection limits
         * @property {Array}
         */
        this.limits = [0, 0, 0, 0];
        /**
         * Border to be added to determine the new position of the port
         * @property {Array}
         */
        this.border = [
            {x: 0, y: 0},
            {x: 0, y: 0},
            {x: 0, y: 0},
            {x: 0, y: 0}
        ];
        /**
         * Determines which type of drag behavior should be assigned
         * @property {number}
         */
        this.dragType = this.CANCEL;
        /**
         * Reference to the point where a connection drag is being started
         * @property {Point}
         */
        this.startConnectionPoint = null;
        /**
         * if set to true, a port will only be added at its middle points
         * @property {Boolean}
         */
        this.connectAtMiddlePoints = null;
        /**
         * Auxiliary property for saving the previous x coordinate in the dragging
         * procedure for multiple drag
         * @property {Number}
         */
        this.previousXDragPosition = 0;
        /**
         * Auxiliary property for saving the previous y coordinate in the dragging
         * procedure for multiple drag
         * @property {Number}
         */
        this.previousYDragPosition = 0;
        /**
         * The type of lines for connections made with this shape
         * @property {String}
         */
        this.connectionType = null;
        // init the custom shape
        CustomShape.prototype.initObject.call(this, options);
    };


    CustomShape.prototype = new Shape();
    /**
     * Type the instances of this class
     * @property {String}
     */
    CustomShape.prototype.type  = "CustomShape";
    /**
     * Family where this class and all its subclasses belong
     * @property {String}
     * @readonly
     */
    CustomShape.prototype.family = "CustomShape";
    /**
     * Reference to a drop behaviors for containers
     * @property {ContainerDropBehavior}
     */
    CustomShape.prototype.containerDropBehavior = null;
    /**
     * Reference to a drop behavior that allows us to make connections
     * @property {ConnectionDropBehavior}
     */
    CustomShape.prototype.connectionDropBehavior = null;
    /**
     * Reference to a drop behavior that has no acceptable droppables
     * @property {NoDropBehavior}
     */
    CustomShape.prototype.noDropBehavior = null;
    /**
     * Constant that represents that a drag behavior for making connections should
     * be used
     * @property {Number}
     */
    CustomShape.prototype.CONNECT = 1;
    /**
     * Constant that represents that a drag behavior for moving the shape should be
     * used
     * @property {Number}
     */
    CustomShape.prototype.DRAG = 2;
    /**
     * Constant that represents that no drag behavior should be used
     * @property {Number}
     */
    CustomShape.prototype.CANCEL = 0;

    /**
     * Initializes the basic attributes for the custom shape, and also the
     * particular objects the shape needs to instantiate
     * //TODO Base limits on zoom
     * @param options
     */
    CustomShape.prototype.initObject = function (options) {

        var defaults = {
                connectAtMiddlePoints: true,
                layers : [],
                labels: [],
                connectionType : "regular"
            },
            i;

        // init the object with NO configurable options
        this.limits = [5, 5, 5, 5, 5];
        this.setStartConnectionPoint(new Point(0, 0));
        //.setDragBehavior(new RegularDragBehavior());

        // init the object with configurable options
        $.extend(true, defaults, options);
        for (i = 0; i < defaults.layers.length; i += 1) {
            this.createLayer(defaults.layers[i]);
        }
        for (i = 0; i < defaults.labels.length; i += 1) {
            this.createLabel(defaults.labels[i]);
        }
        this.setConnectAtMiddlePoints(defaults.connectAtMiddlePoints)
            .setConnectionType(defaults.connectionType);

    };
    /**
     * Creates a layer given its configuration options
     * @param {Object} options configuration options
     * @return {Layer}
     */
    CustomShape.prototype.createLayer = function (options) {

        var layer;
        options.parent = this;
        layer = new Layer(options);
        this.addLayer(layer);
        return layer;
    };
    /**
     * Creates a label given its configuration options
     * @param {Object} options configuration options for instantiating a label
     * @return {Label}
     */
    CustomShape.prototype.createLabel = function (options) {
        var label;
        options.canvas = this.canvas;
        options.parent = this;
        if (options.width === 0) {
            options.width = this.width * 0.9;
        }
        label = new Label(options);
        this.addLabel(label);
        return label;
    };

    /**
     * Adds a label to the array of labels and also appends its html
     * @param {Label}
     */
    CustomShape.prototype.addLabel = function (label) {
        if (this.html) {
            //so we just append it to the parent
            label.parent = this;
            this.html.appendChild(label.getHTML());
        }
        if (!this.labels.contains(label)) {
            this.labels.insert(label);
        }
    };
    /**
     * Creates the html for the shape, its layers and labels
     * @returns {HTMLElement}
     */
    CustomShape.prototype.createHTML = function () {
        var i,
            label;
        Shape.prototype.createHTML.call(this);

        // this line: this.html.className = "custom_shape"
        // replaced with:
        this.style.addClasses(["custom_shape"]);

        this.layers.sort(Layer.prototype.comparisonFunction);

        for (i = 0; i < this.layers.getSize(); i += 1) {
            this.html.appendChild(this.layers.get(i).getHTML());

        }
        for (i = 0; i < this.labels.getSize(); i += 1) {
            label = this.labels.get(i);
            this.addLabel(label);
            label.attachListeners();

        }
        return this.html;
    };

    /**
     * This function will attach all the listeners corresponding to the CustomShape
     * @chainable
     */
    CustomShape.prototype.attachListeners = function () {
        if (this.html === null) {
            return this;
        }

        var $customShape = $(this.html)
            .click(this.onClick(this));
        //drag options for the added shapes
        $customShape.on("mousedown", this.onMouseDown(this));
        $customShape.mousemove(this.onMouseMove(this));
        $customShape.mouseup(this.onMouseUp(this));
        $customShape.on("contextmenu", function (e) {
            e.preventDefault();
        });

        this.updateBehaviors();
        return this;

    };

    /**
     * Apply the styles related to the shape, its layers and labels
     * @chainable
     */
    CustomShape.prototype.paint = function () {
        var i,
            label;

        Shape.prototype.paint.call(this);

        // apply predefined style
//    console.log(this.style.cssProperties);
//    this.style.applyStyle();

        //TODO Apply the style of the given shape
        for (i = 0; i < this.layers.getSize(); i += 1) {
            this.layers.get(i).paint();
        }
        for (i = 0; i < this.ports.getSize(); i += 1) {
            this.ports.get(i).paint();
        }
        for (i = 0; i < this.labels.getSize(); i += 1) {
            label = this.labels.get(i);
            label.paint();
        }
        return this;
    };

    /**
     * Updates properties obtained when the HTML is on the DOM
     * @chainable
     */
    CustomShape.prototype.updateHTML = function () {
        var i,
            label;
        this.setDimension(this.width, this.height);
        for (i = 0; i < this.labels.getSize(); i += 1) {
            label = this.labels.get(i);
            label.paint();
            label.updateDimension();
        }
        return this;
    };
    /**
     * Repaints connections related to this shape
     * @param {Boolean} inContainer Determines if the points of a connection should
     * be saved for its reconstruction
     * @chainable
     */
    CustomShape.prototype.refreshConnections = function (inContainer, isDragged,
                                                         isZoomed) {
        var i,
            connection,
            ports = this.ports,
            port;
        for (i = 0; i < ports.getSize(); i += 1) {
            port = ports.get(i);
            port.setPosition(port.getX(), port.getY());
            connection = port.connection;
            if (!isDragged) {
                connection.disconnect(inContainer)
                    .connect(inContainer);
            }

            connection.setSegmentMoveHandlers()
                .checkAndCreateIntersectionsWithAll();
            if (!isZoomed) {
                this.canvas.triggerConnectionStateChangeEvent(connection);
            }
        }
        return this;
    };
    /**
     * Updates the properties of this shape layers according to the shape itself
     * @chainable
     */
    CustomShape.prototype.updateLayers = function () {
        var i, j,
            layer;
        for (i = 0; i < this.getLayers().getSize(); i += 1) {
            layer = this.getLayers().get(i);
            layer.setProperties();
        }
        return this;
    };
    /**
     * Returns what it should be the next layer if there is such in the DOM tree
     * or null otherwise
     * @param {Layer} layer
     * @returns {Layer}
     */
    CustomShape.prototype.findLayerPosition = function (layer) {
        var nextLayer = null,//holds the next layer regarding the position where
        // the new layer should be inserted
            minVal = 10000000, //holds the minimum value of all the values greater
        // than the newLayer priority
            i,
            currLayer,
            currPriority;
        //iterate through all the layers and find the minimum priority of all
        // the priorities that are greater than the priority of the current layer
        for (i = 0; i < this.layers.getSize(); i += 1) {
            currLayer = this.layers.get(i);
            currPriority = currLayer.getPriority();
            if (currPriority > layer.getPriority()) {
                if (minVal > currPriority) {
                    minVal = currPriority;
                    nextLayer = currLayer;
                }
            }
        }
        return nextLayer;
    };

    /**
     * Adds a new layer to the corresponding shape
     * @param {Layer} newLayer
     * @chainable
     */
    CustomShape.prototype.addLayer = function (newLayer) {
        //gets the layer that would come next the new one
        var nextLayer = this.findLayerPosition(newLayer);
        //if there is none it means that the new layer has the highest priority
        // of all
        if (this.html) {
            if (!nextLayer) {
                //so we just append it to the parent
                this.html.appendChild(newLayer.getHTML());
            } else {
                //otherwise we append it before nextLayer
                this.html.insertBefore(newLayer.getHTML(), nextLayer.getHTML());
            }
            newLayer.paint();
        }
        this.layers.insert(newLayer);

        return this;
    };

    /**
     * Finds a given layer by ID or null of it doesn't exist
     * @param {String} layerID
     * @returns {Layer}
     */
    CustomShape.prototype.findLayer = function (layerID) {

        var currLayer,
            i;
        currLayer = this.layers.find('id', layerID);
        return currLayer;
    };
    /**
     * Set the dimension of the customShape
     * @param {Number} newWidth
     * @param {Number} newHeight
     */
    CustomShape.prototype.setDimension = function (newWidth, newHeight) {
        Shape.prototype.setDimension.call(this, newWidth, newHeight);
        this.updateLabels();
        this.updateLayers();
        return this;
    };
    /**
     * Updates the labels properties if necessary
     * @abstract
     * @template
     * @protected
     */
    CustomShape.prototype.updateLabels = function () {

    };
    /**
     * Makes a layer non-visible
     * @param {String} layerID
     * @returns {CustomShape}
     */
    CustomShape.prototype.hideLayer = function (layerID) {
        var currLayer;
        if (!layerID || typeof layerID !== "string") {
            return this;
        }
        currLayer = this.findLayer(layerID);
        if (!currLayer) {
            return this;
        }

        currLayer.setVisible(false);
        return this;

    };
    /**
     * Makes a layer visible
     * @param {String} layerID
     * @returns {CustomShape}
     */
    CustomShape.prototype.showLayer = function (layerID) {
        var currLayer;
        if (!layerID || typeof layerID !== "string") {
            return this;
        }

        currLayer = this.findLayer(layerID);
        if (!currLayer) {
            return this;
        }
        currLayer.setVisible(true);


        return this;
    };


    /**
     * Adds a port to the Shape
     * @param {Port} port
     * @param {Number} xPortCoord
     * @param {Number} yPortCoord
     * @chainable
     */
    CustomShape.prototype.addPort = function (port, xPortCoord, yPortCoord,
                                              triggerChange, sourcePort) {


        //where the user is attempting to create the port
        //TODO Fix trowing custom events by using properties of the objects
        var position = new Point(xPortCoord, yPortCoord);
//        oldX = port.x,
//        oldY = port.y,
//        oldAbsoluteX = port.absoluteX,
//        oldAbsoluteY = port.absoluteY,
//        oldParent = port.parent;
        //set the corresponding shape where the port would be created

        port.setParent(this);
        port.setCanvas(this.canvas);

        //set the port dimension
//    port.setDimension(8, 8);

        //validate the position of the port in order to positionate it in one of
        // the corners of the shape, this is applied to all but activities

        //port.validatePosition(position);
        this.definePortPosition(port, position, sourcePort);

        //append the html to the DOM and paint the port
        this.html.appendChild(port.getHTML());

        port.paint();
//    port.setColor(new Color(255, 0, 0));
        //insert the port to the ports array of the shape
        this.ports.insert(port);
//    if (triggerChange) {
//        port.changeParent(oldX, oldY, oldAbsoluteX,
//            oldAbsoluteY, oldParent, port.canvas);
//    }
        return this;
    };
    /**
     *
     * Removes a port from the Shape
     * @param {Port} port
     * @chainable
     */
    CustomShape.prototype.removePort = function (port) {
        this.ports.remove(port);
        return this;
    };
    /**
     * Determines the position where the port will be located
     * @param {Port} port
     * @param {Point} point
     * @param {Port} sourcePort
     * @chainable
     */
    CustomShape.prototype.definePortPosition = function (port, point,
                                                         sourcePort) {
        var canvas = this.canvas,
            directionArray = [this.TOP, this.RIGHT, this.BOTTOM, this.LEFT],
        // midPointArray is used when connectAtMiddlePoints is set to TRUE
            midPointArray = [
                new Point(Math.round(this.zoomWidth / 2), 0),               // TOP
                new Point(this.zoomWidth, Math.round(this.zoomHeight / 2)),// RIGHT
                new Point(Math.round(this.zoomWidth / 2), this.zoomHeight),// BOTTOM
                new Point(0, Math.round(this.zoomHeight / 2))               // LEFT
            ],
        // sideArray is used when connectAtMiddlePoints is set to FALSE
            sideArray = [
                new Point(point.x, 0),                          // TOP
                new Point(this.getZoomWidth(), point.y),            // RIGHT
                new Point(point.x, this.getZoomHeight()),           // BOTTOM
                new Point(0, point.y)                           // LEFT
            ],
            usedArray,  // selects either the midPointArray or the side array
            direction,
            i,
            candidateDistance,
            minDistance,
            option,
            border,
            directionBorderMultiplier = [-1, 1, 1, -1],
            rightBorderMultiplier = [0, 0, -2, 0],
            bottomBorderMultiplier = [0, -2, 0, 0];

        // if the shape has the connectAtMiddlePoints flag on then use the midPoints
        usedArray = this.connectAtMiddlePoints ? midPointArray : sideArray;

        // if the shape has a source port available then use manhattan distance
        // instead of squaredDistance
        option = "getSquaredDistance";
        if (sourcePort && this.connectAtMiddlePoints) {
            option = "getManhattanDistance";
//        point = new Point(sourcePort.x + sourcePort.parent.absoluteX,
//            sourcePort.y + sourcePort.parent.absoluteY);
        }
        direction = undefined;  //obtain location of the port
        minDistance = Infinity;
        // get the minimum distance between 2 points;
        for (i = 0; i < usedArray.length; i += 1) {
//        if (sourcePort && this.connectAtMiddlePoints) {
//            // use manhattan distance
//            // logic: manhattan_distance(
//            //      new Point(srcPort.x + srcShape.x, srcPort.y + srcShape.y),
//            //      new Point(destPort.x + destShape.x, destPort.y +
//            //                  destShape.y)
//            // )
////            candidateDistance = point[option](usedArray[i].
////                add(new Point(port.parent.absoluteX,
//                                  port.parent.absoluteY)));
//            candidateDistance = point[option](usedArray[i]);
//        } else {
            // use squared distance
            candidateDistance = point[option](usedArray[i]);
//        }
            if (minDistance > candidateDistance) {
                minDistance = candidateDistance;
                direction = directionArray[i];
            }
        }

        border = this.getBorderConsideringLayers();
        for (i = 0; i < 4; i += 1) {
            this.border[i].x =
                (border * directionBorderMultiplier[i] +
                border * rightBorderMultiplier[i]);
            this.border[i].y =
                (border * directionBorderMultiplier[i] +
                border * bottomBorderMultiplier[i]);
        }
        // because of the zIndex problem move the ports towards the center
        // of the shape (this is done when the destDecorator is selected)
        port.setDirection(direction);
        // setPosition logic:
        // since the port must face the border of the shape (or the shape if it
        // doesn't have a border) first let's move the port according to the
        // direction of the port (up -> -1 * border, right -> 1 * border, bottom ->
        // 1 * border, left -> -1 * border)
        // after the port will be right in the edge of the shape but now the
        // multiplier has also affected the positioning of the port if it's located
        // in the right or in the bottom (the port will move 2 * border in the
        // y-axis or x-axis) so let's reverse that movement using another array
        port.setPosition(
            (
//            this.border[direction].x +
            usedArray[direction].x
            - port.getWidth() / 2
            ),
            (
//            this.border[direction].y +
            usedArray[direction].y
            - port.getHeight() / 2
            )
        );


        port.applyBorderMargin(true);

        // determines the percentage of port in relation with the shape's width or
        // height (useful to determine the new position of the port while resizing)
        port.determinePercentage();

        return this;
    };

    /**
     * Returns the border of this shape or the border of its layers (max)
     * @return {Number}
     */
    CustomShape.prototype.getBorderConsideringLayers = function () {
        var border = parseInt(this.style.getProperty('borderTopWidth') || 0, 10),
            layer,
            i;
        for (i = 0; i < this.getLayers().getSize(); i += 1) {
            layer = this.getLayers().get(i);
            border = Math.max(border, parseInt(
                layer.style.getProperty('borderTopWidth') || 0,
                10
            ));
        }
        return border;
    };

    /**
     * Show  all the ports of the Shape
     * @chainable
     */
    CustomShape.prototype.showPorts = function () {
        var i;
        for (i = 0; i < this.ports.getSize(); i += 1) {
            this.ports.get(i).show();
        }
        return this;
    };

    /**
     * hide  all the ports of the Shape
     * @chainable
     */
    CustomShape.prototype.hidePorts = function () {
        var i;
        for (i = 0; i < this.ports.getSize(); i += 1) {
            this.ports.get(i).hide();
        }
        return this;
    };

    /**
     * Updates the position of the ports regarding the CustomShape and two
     * differentials
     * TODO Improve triggering of events with ports own properties
     * @param {Number} xDiff
     * @param {Number} yDiff
     * @chainable
     */
    CustomShape.prototype.updatePortsPosition = function (xDiff, yDiff) {
        var i,
            port,
            ports = this.ports;
        for (i = 0; i < ports.getSize(); i += 1) {
            port = ports.get(i);
            if (port.direction === this.RIGHT || port.direction === this.BOTTOM) {
                port.oldX = port.x;
                port.oldY = port.y;
                port.oldAbsoluteX = port.absoluteX;
                port.oldAbsoluteY = port.absoluteY;
                port.setPosition(port.x + xDiff, port.y + yDiff, true);
                port.changePosition(port.oldX, port.oldY, port.oldAbsoluteX,
                    port.oldAbsoluteY);
            } else {
                port.setPosition(port.x, port.y, true);
            }
            port.connection.disconnect().connect();
            port.connection.setSegmentMoveHandlers();
        }
        return this;
    };

    /**
     * Recalculates a port position given the port
     * TODO Determine if this method is necessary
     * @param {Port} port
     * @chainable
     */
    CustomShape.prototype.recalculatePortPosition = function (port) {
//    console.log(port.percentage);
        var xPercentage = Math.round((port.percentage *
            port.parent.getZoomWidth()) / 100),
            yPercentage = Math.round((port.percentage *
            port.parent.getZoomHeight()) / 100),
            xCoordinate = [xPercentage, port.parent.getZoomWidth(), xPercentage, 0],
            yCoordinate = [0, yPercentage,
                port.parent.getZoomHeight(), yPercentage];
//    console.log(xPercentage + " " + yPercentage);
        //if a shape has connect to midle poinst enabled
        if (this.connectAtMiddlePoints) {
            xCoordinate = [port.parent.getZoomWidth() / 2, port.parent.getZoomWidth(), port.parent.getZoomWidth() / 2, 0];
            yCoordinate = [0, port.parent.getZoomHeight() / 2,
                port.parent.getZoomHeight(), port.parent.getZoomHeight() / 2];
        }
        port.setPosition(
            this.border[port.direction].x + xCoordinate[port.direction] -
            Math.round(port.width / 2),
            this.border[port.direction].y + yCoordinate[port.direction] -
            Math.round(port.height / 2)
        );
        return this;
    };

    /**
     * Initializes properties to to save the current position of the ports
     * @chainable
     */
    CustomShape.prototype.initPortsChange = function () {
        var i,
            ports = this.ports,
            port;
        for (i = 0; i  < ports.getSize(); i += 1) {
            port = ports.get(i);
            port.oldX = port.x;
            port.oldY = port.y;
            port.oldAbsoluteX = port.absoluteX;
            port.oldAbsoluteY = port.absoluteY;
        }
        return this;
    };

    /**
     * Trigger to save the port changes
     * @chainable
     */
    CustomShape.prototype.firePortsChange = function () {
        var i,
            ports = this.ports,
            port;
        for (i = 0; i < ports.getSize(); i += 1) {
            port = ports.get(i);
            // port is not a shape so use call
            Shape.prototype.changePosition.call(this, port.oldX, port.oldY,
                port.oldAbsoluteX, port.oldAbsoluteY);
        }
        return this;
    };
    /**
     * Updates ports and connections of the current shape
     * @chainable
     */
    CustomShape.prototype.refreshShape = function () {
        Shape.prototype.refreshShape.call(this);
        this.updatePortsOnZoom()
            .refreshConnections(false, false, true);
        this.paint();
        return this;
    };
    /**
     * Updates the position of the ports after applying a zoom scale
     * @chainable
     */
    CustomShape.prototype.updatePortsOnZoom = function () {
        var i,
            ports = this.ports,
            port,
            zoomFactor = this.canvas.zoomFactor,
            prevZoomFactor = (this.canvas.prevZoom * 25 + 50) / 100,
            portFactor = (ports.getSize() > 0) ? ports.get(0).width / 2 : 0,
            srcDecorator,
            destDecorator,
            xCoords = [
                this.zoomWidth / 2 - portFactor,
                this.zoomWidth - portFactor,
                this.zoomWidth / 2 - portFactor,
                -portFactor
            ],
            yCoords = [
                -portFactor,
                this.zoomHeight / 2 - portFactor,
                this.zoomHeight - portFactor,
                this.zoomHeight / 2 - portFactor
            ];

        for (i = 0; i < ports.getSize(); i += 1) {
            port = ports.get(i);
            port.applyBorderMargin(false);
            if (this.connectAtMiddlePoints) {
                port.setPosition(Math.round(xCoords[port.direction]),
                    Math.round(yCoords[port.direction]));
            } else {
                xCoords = [
                    port.x / prevZoomFactor * zoomFactor,
                    this.getZoomWidth() - port.getZoomWidth() / 2,
                    port.x / prevZoomFactor * zoomFactor,
                    port.x
                ];
                yCoords = [
                    port.y,
                    port.y / prevZoomFactor * zoomFactor,
                    this.getZoomHeight() - port.getZoomHeight() / 2,
                    port.y / prevZoomFactor * zoomFactor
                ];
                port.setPosition(Math.round(xCoords[port.direction]),
                    Math.round(yCoords[port.direction]));
            }
            port.applyBorderMargin(true);
            srcDecorator = port.connection.srcDecorator;
            destDecorator = port.connection.destDecorator;
            if (srcDecorator) {
                srcDecorator.applyZoom();
            }
            if (destDecorator) {
                destDecorator.applyZoom();
            }


//        port.connection.disconnect().connect();
//        port.connection.setSegmentMoveHandlers();
//        port.connection.checkAndCreateIntersectionsWithAll();
        }
        return this;
    };
    /**
     * TODO Determine if this method is necessary
     */
    CustomShape.prototype.calculateLabelsPercentage = function () {
        var i, label;
        for (i = 0; i < this.labels.getSize(); i += 1) {
            label = this.labels.get(i);
            label.xPercentage = label.getX() / this.getWidth();
            label.yPercentage = label.getY() / this.getHeight();
        }

    };

    /**
     * Updates the labels position according to its configuration properties
     * @chainable
     */
    CustomShape.prototype.updateLabelsPosition = function () {
        var i,
            label;
        for (i = 0; i < this.labels.getSize(); i += 1) {
            label = this.labels.get(i);
            label.setLabelPosition(label.location, label.diffX, label.diffY);
        }
        return this;
    };

    /**
     * Returns the respective drag behavior according to a given point
     * @return {Number}
     */
    CustomShape.prototype.determineDragBehavior = function (point) {
        // limit to consider inside the shape
        var limit = this.limits[this.canvas.zoomPropertiesIndex],

            border = parseInt(this.style.getProperty('border') || 0, 10);

        // if the point is inside the rectangle determine the behavior
        // (drag or connect)
        if (Geometry.pointInRectangle(point, new Point(0, 0),
                new Point(this.zoomWidth + 2 * border,
                    this.zoomHeight + 2 * border))) {
            // if the shape is inside the inner rectangle then drag
            if (Geometry.pointInRectangle(point,
                    new Point(border + limit, border + limit),
                    new Point(this.zoomWidth + border - limit,
                        this.zoomHeight + border - limit))) {
                return this.DRAG;
            }
            return this.CONNECT;
        }

        // if the mouse pointer is outside then return cancel
        return this.CANCEL;
    };
    /**
     * Creates a drag helper for drag and drop operations for the helper property
     * in jquery ui draggable
     * TODO Create a singleton object for this purpose
     * @returns {String} html
     */
    CustomShape.prototype.createDragHelper = function () {
        var html = document.createElement("div");

        // can't use class style here
        html.style.width = 8 + "px";
        html.style.height = 8 + "px";
        html.style.backgroundColor = "black";
        html.style.zIndex = 2 * Shape.prototype.MAX_ZINDEX;
        html.id = "drag-helper";
        html.className = "drag-helper";
        // html.style.display = "none";
        return html;
    };

    /**
     * Handler for the onmousedown event, changes the draggable properties
     * according to the drag behavior that is being applied
     * @param {CustomShape} CustomShape
     * @returns {Function}
     */
    CustomShape.prototype.onMouseDown = function (customShape) {
        return function (e, ui) {
            var canvas = customShape.canvas;
            if (e.which === 3) {
                $(canvas.html).trigger("rightclick", [e, customShape]);
            } else {

                if (customShape.dragType === customShape.DRAG) {
                    customShape.setDragBehavior("customshapedrag");

                } else if (customShape.dragType === customShape.CONNECT) {
                    customShape.setDragBehavior("connection");
                } else {
                    customShape.setDragBehavior("nodrag");
                }
                customShape.dragging = true;
            }

            e.stopPropagation();
        };
    };


    /**
     * On Mouse Up handler it allows the shape to recalculate drag behavior
     * whenever there was a mouse down event but no drag involved
     * @param {CustomShape} customShape
     * @return {Function}
     */
    CustomShape.prototype.onMouseUp = function (customShape) {
        return function (e, ui) {
            customShape.dragging = false;

        };
    };

    /**
     * Handler for the onmousemove event, determines the drag behavior that is
     * being applied, the coordinates where the mouse is currently located and
     * changes the mouse cursor
     * @param {CustomShape} customShape
     * @returns {Function}
     */

    CustomShape.prototype.onMouseMove = function (customShape) {
        return function (e, ui) {
            var $customShape,
                canvas;

            if (customShape.dragging || customShape.entered) {
                return;
            }
//TODO ADD TO UTILS A FUNCTION TO RETRIEVE A POINT RESPECTING THE SHAPE
            $customShape = $(customShape.html);
            canvas = customShape.getCanvas();

            customShape.startConnectionPoint.x = e.pageX - canvas.getX() -
            customShape.absoluteX + canvas.getLeftScroll();
            customShape.startConnectionPoint.y = e.pageY - canvas.getY() -
            customShape.absoluteY + canvas.getTopScroll();

            customShape.dragType = customShape
                .determineDragBehavior(customShape.startConnectionPoint);

            if (customShape.dragType === customShape.DRAG) {
                $customShape.css('cursor', 'move');
            } else if (customShape.dragType === customShape.CONNECT) {
                $customShape.css('cursor', 'crosshair');

            } else {
                $customShape.css('cursor', 'default');
            }
            //e.stopPropagation();
        };
    };

    /**
     * Handler of the onClick Event hides the selected ports and resize Handlers if
     * any and show its corresponding resize handler
     * @param {CustomShape} customShape
     * @returns {Function}
     */
    CustomShape.prototype.onClick = function (customShape) {
        var i, erros, id, item;
        return function (e, ui) {
            var isCtrl = false,
                canvas = customShape.canvas,
                currentSelection = canvas.currentSelection,
                currentLabel = canvas.currentLabel;
            if (e.ctrlKey) { // Ctrl is also pressed
                isCtrl = true;
            }

            // hide the current connection if there was one
            customShape.canvas.hideCurrentConnection();

            if (e.which === 3) {        // right click
                e.preventDefault();
                // trigger right click
                customShape.canvas.triggerRightClickEvent(customShape);
            } else {
                if (!customShape.wasDragged) {
                    // if the custom shape was not dragged (this var is set to true
                    // in custom_shape_drag_behavior >> onDragEnd)
                    if (isCtrl) {
                        if (currentSelection.contains(customShape)) {
                            // remove from the current selection
                            canvas.removeFromSelection(customShape);
                        } else {
                            // add to the current selection
                            canvas.addToSelection(customShape);
                        }

                    } else {
                        canvas.emptyCurrentSelection();
                        canvas.addToSelection(customShape);
                    }
                }
                if (!currentSelection.isEmpty()) {
                    canvas.triggerSelectEvent(currentSelection.asArray());
                }
            }

            if (this.helper) {
                $(this.helper.html).remove();
            }

            if (currentLabel) {
                currentLabel.loseFocus();
                $(currentLabel.textField).focusout();
            }
            customShape.wasDragged = false;

            e.stopPropagation();
            //select in list item for element panel with errors
            // Add defensive coding to ensure we are not calling methods on
            // undefined properties
            if (listPanelError !== undefined && customShape.BPMNError) {
                erros = customShape.BPMNError.asArray();
                if ( erros.length ) {
                    id = customShape.getID();
                    item = listPanelError.items.filter( function (item) {
                        if (item.getErrorId() === id) {
                            return item
                        }
                    });
                    if (item.length){
                        item = item[0];
                        //console.log(item.html);
                        item.select();
                        $("#div-bpmn-error")[0].scrollTop = item.html.offsetTop;
                    }
                } else {
                    if (listPanelError.selectedItem){
                        listPanelError.selectedItem.deselect();
                    }
                }
            }
        };
    };

    /**
     * Empty function to perform some actions when parsing a diagram (called
     * from Canvas.parse)
     */
    CustomShape.prototype.parseHook = function () {
    };

    /**
     * Returns a list of ports related to the shape
     * @returns {ArrayList}
     */
    CustomShape.prototype.getPorts = function () {
        return this.ports;
    };
    /**
     * Returns a list of Layers related to the shape
     * @returns {ArrayList}
     */
    CustomShape.prototype.getLayers = function () {
        return this.layers;
    };

    /**
     * Returns the labels associated to the current shape
     * @return {ArrayList}
     */
    CustomShape.prototype.getLabels = function () {
        return this.labels;
    };

    /**
     * Applies the current zoom to the corresponding shape its layers and labels
     * @chainable
     */
    CustomShape.prototype.applyZoom = function () {
        var i,
            label;

        Shape.prototype.applyZoom.call(this);

        for (i = 0; i < this.layers.getSize(); i += 1) {
            this.layers.get(i).applyZoom();
        }

        for (i = 0; i < this.labels.getSize(); i += 1) {
            label = this.labels.get(i);
            label.applyZoom();
            label.setLabelPosition(label.location, label.diffX, label.diffY);
            //label.setPosition(label.x, label.y);
        }

        return this;
    };

    /**
     * Sets the start point of a connection corresponding to this shape
     * @param {Point} point
     * @chainable
     */
    CustomShape.prototype.setStartConnectionPoint = function (point) {
        this.startConnectionPoint = point;
        return this;
    };
    /**
     * Sets the connectAtMiddlePoints property
     * @param  {Boolean} connect
     * @chainable
     */
    CustomShape.prototype.setConnectAtMiddlePoints = function (connect) {
        this.connectAtMiddlePoints = connect;
        return this;
    };
    /**
     * Returns whether a shape connections will be done only in the middle points of
     * its sides or not
     * @return {Boolean}
     */
    CustomShape.prototype.getConnectAtMiddlePoints = function () {
        return this.connectAtMiddlePoints;
    };
    /**
     * Sets the connection type of the shape
     * @param {String} newConnType
     * @chainable
     */
    CustomShape.prototype.setConnectionType = function (newConnType) {
        this.connectionType = newConnType;
        return this;
    };
    /**
     * Returns the connection type of the shape
     * @return {String}
     */
    CustomShape.prototype.getConnectionType = function () {
        return this.connectionType;
    };
    /**
     * Serializes this object
     * @return {Object}
     */
    CustomShape.prototype.stringify = function () {
        /**
         * inheritedJSON = {
     *     id: #
     *     x: #,
     *     y: #,
     *     width: #,
     *     height: #
     * }
         * @property {Object}
         */
        var sLayers = [],
            labels = [],
            i,
            inheritedJSON,
            thisJSON;

        // serialize layers
        for (i = 0; i < this.layers.getSize(); i += 1) {
            sLayers.push(this.layers.get(i).stringify());
        }

        // serialize labels
        for (i = 0; i < this.labels.getSize(); i += 1) {
            labels.push(this.labels.get(i).stringify());
        }

        inheritedJSON = Shape.prototype.stringify.call(this);
        thisJSON = {
            canvas: this.canvas.getID(),
            layers: sLayers,
            labels: labels,
            connectAtMiddlePoints: this.getConnectAtMiddlePoints(),
            connectionType: this.getConnectionType(),
            parent: this.parent.getID()
        };
        $.extend(true, inheritedJSON, thisJSON);
        return inheritedJSON;
    };

    /**
     * Builds a custom shape based on the parameter <json>
     * @param {String} json
     * @chainable
     */
    CustomShape.prototype.parseJSON = function (json) {
        this.initObject(json);
        return this;
    };

    /**
     * @class Segment
     * A class that represents a segment, a segment is defined with two points (`startPoint` and `enPoint`).
     * In the jCore library a segment is used as a the key part of connections, it has also the following characteristics:
     *
     * - Since a segment is used as part of a connection, it has neighbors (`previousNeighbor` and `nextNeighbor`).
     * - A segment is parallel to an axis if it forms part of a connection.
     * - A segment has a move handler to move the segment.
     * - A segment has info of other connections it has intersections with
     *
     * Some examples of usage:
     *
     *      // let's assume that we want to connect two shapes, the shapes are connected
     *      // through the creation of a segment (the start point is the mouse position where
     *      // the user fired the mouse down event and the end point is the mouse position where the user
     *      // fired the mouse up event)
     *      // let's assume that canvas is an instance of the class Canvas and it's creating the segment
     *      var redSegment = new Segment({
 *          startPoint: new Point(100, 100),        // a random point
 *          endPoint: new Point(200, 200),          // a random point
 *          parent: canvas,
 *          color: new Color(255, 0, 0)             // red !!
 *      });
     *
     * @extend JCoreObject
     *
     * @constructor Creates an instance of the class Segment
     * @param {Object} options Initialization options
     * @cfg {Point} [startPoint=new Point(0, 0)] The start point of the segment
     * @cfg {Point} [endPoint=new Point(0, 0)] The end point of the segment
     * @cfg {Canvas / Connection} [parent=null] The parent of the segment
     * @cfg {Color} [color=new Color(0, 0, 0)] The color of this segment
     */
    Segment = function (options) {
        JCoreObject.call(this, options);

        /**
         * The parent of the segment.
         * @property {Canvas / Connection} [parent=null]
         */
        this.parent = null;
        /**
         * The start point of the segment.
         * @property {Point} [startPoint=null]
         */
        this.startPoint = null;

        /**
         * The end point of the segment.
         * @property {Point} [endPoint=null]
         */
        this.endPoint = null;

        /**
         * zOrder of the segment.
         * @property {number} [zOrder=Shape.prototype.MAX_ZINDEX]
         */
        this.zOrder = Shape.prototype.MAX_ZINDEX;

        /**
         * The segment to the left of this segment.
         * @property {Segment} [previousNeighbor=null]
         */
        this.previousNeighbor = null;

        /**
         * The segment to the right of this segment.
         * @property {Segment} [nextNeighbor=null]
         */
        this.nextNeighbor = null;

        /**
         * Orientation of the segment, the possible values are:
         *
         * - Vertical
         * - Horizontal
         *
         * @property {String} [orientation=""]
         */
        this.orientation = "";

        /**
         * The width of the segment.
         * @property {number} [width=1]
         */
        this.width = 1;

        /**
         * CustomLine object
         * @property {CustomLine} [customLine=null]
         */
        this.customLine = null;

        /**
         * This segment style, the possible values are:
         *
         * - "dotted"
         * - "segmented"
         * - "segmentdot"
         * @property {string} [segmentStyle=null]
         */
        this.segmentStyle = null;

        /**
         * This segment color.
         * @property {Color} [segmentColor=null]
         */
        this.segmentColor = null;

        /**
         * The move handler is the segment move handler of this segment.
         * @property {SegmentMoveHandler} [moveHandler=null]
         */
        this.moveHandler = null;

        /**
         * Creates an ArrayList of the intersections with other connections.
         *
         *      // the structure is like:
         *      //intersections = [
         *      //    {
     *      //        center: point of intersection,
     *      //        IdOtherConnection: id of the other connection
     *      //    }
         *      //]
         * @property {ArrayList} [intersections=new ArrayList()]
         */
        this.intersections = new ArrayList();

        /**
         * True if this segment has a move handler.
         * @property {boolean} [hasMoveHandler=false]
         */
        this.hasMoveHandler = false;

        // set defaults
        Segment.prototype.initObject.call(this, options);
    };

    Segment.prototype = new JCoreObject();

    /**
     * The type of each instance of this class
     * @property {String}
     */
    Segment.prototype.type = "Segment";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance.
     * @param {Object} options The object that contains the config
     * @private
     */
    Segment.prototype.initObject = function (options) {
        /**
         * Default options for the constructor
         * @property {Object}
         */
        var defaults = {
            startPoint: new Point(0, 0),
            endPoint: new Point(0, 0),
            parent: null,
            color: new Color(0, 0, 0)
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaults, options);

        // init
        this.setStartPoint(defaults.startPoint)
            .setEndPoint(defaults.endPoint)
            .setColor(defaults.color)
            .setParent(defaults.parent);
    };

    /**
     * Creates the HTML Representation of the Segment.
     * @returns {HTMLElement}
     */
    Segment.prototype.createHTML = function () {
        this.html = document.createElement('div');
        this.html.id = this.id;
        this.html.style.position = "absolute";
        this.html.style.left = "0px";
        this.html.style.top = "0px";
        this.html.style.height = "0px";
        this.html.style.width = "0px";
        this.html.style.zIndex = this.zOrder;
        return this.html;
    };

    /**
     * Paints a segment by creating an instance of the class {@link Graphics} and
     * calling {@link Graphics#drawLine}, it also append it's HTML to its parent's HTML.
     * @chainable
     */
    Segment.prototype.paint = function () {
        if (this.getHTML() === null) {
            return this;
        }
        if (this.customLine === null) {
            this.customLine = new CustomLine({canvas : this.html});
        }
        //dibujas linea llamar a drawLine de la clase graphics con los puntos
        this.customLine.drawLine(this.startPoint.x, this.startPoint.y,
            this.endPoint.x, this.endPoint.y); //, this.segmentStyle, this.segmentColor);
        this.parent.html.appendChild(this.html);
        return this;
    };

    /**
     * Removes its HTML from the DOM.
     * @chainable
     */
    Segment.prototype.destroy = function () {
        $(this.html).remove();
        return this;
    };

    /**
     * Paint this segment with the intersections it has stored (this method is called from
     * {@link Connection#checkAndCreateIntersectionsWithAll}), it also append it's HTML to its parent's HTML.
     * @chainable
     */
    Segment.prototype.paintWithIntersections = function () {

        // we have to paint the segment again so destroy the previous one
        this.destroy();

        var startPoint,
            endPoint,
            diff,
            i,
            reverse = false;

        if (this.getHTML() === null) {
            return this;
        }
        if (this.customLine === null) {
            this.customLine = new CustomLine({canvas : this.html});
        }

        //console.log(this.hasMoveHandler);
        if (this.hasMoveHandler) {
            $(this.moveHandler.html).remove();
            this.addSegmentMoveHandler();
        }


        // default differentials to split the segment
        if (this.orientation === this.HORIZONTAL) {
            diff = new Point(Shape.prototype.DEFAULT_RADIUS, 0);
            if (this.startPoint.x > this.endPoint.x) {
                reverse = true;
            }

            // for this to work we need to sort the intersections
            this.intersections.sort(function (i, j) {
                return i.center.x >= j.center.x;
            });

        } else {
            diff = new Point(0, Shape.prototype.DEFAULT_RADIUS);
            if (this.startPoint.y > this.endPoint.y) {
                reverse = true;
            }

            // for this to work we need to sort the intersections
            this.intersections.sort(function (i, j) {
                return i.center.y >= j.center.y;
            });
        }
        this.customLine.clear();

        startPoint = this.startPoint.clone();
        for (i = 0; i < this.intersections.getSize(); i += 1) {
            // if the direction is reverse then we get the
            // inverse position for i in the array
            if (reverse) {
                endPoint = this.intersections
                    .get(this.intersections.getSize() - i - 1).center;
            } else {
                endPoint = this.intersections.get(i).center;
            }

            if (reverse) {
                endPoint = endPoint.add(diff);
            } else {
                endPoint = endPoint.subtract(diff);
            }
            this.customLine.drawLine(startPoint.x, startPoint.y,
                endPoint.x, endPoint.y); //, this.segmentStyle,
            //this.segmentColor, 0, 0, true);
            if (reverse) {
                startPoint = endPoint.subtract(diff.multiply(2));
            } else {
                startPoint = endPoint.add(diff.multiply(2));
            }
        }

        // draw last segment
        endPoint = this.endPoint.clone();
        this.customLine.drawLine(startPoint.x, startPoint.y,
            endPoint.x, endPoint.y); //, this.segmentStyle, this.segmentColor,
        //0, 0, true);
        this.parent.html.appendChild(this.html);
        return this;
    };

    /**
     * Adds a segmentMoveHandler to this segment, it also append the segmentMoveHandler instance HTML to this HTML
     * @chainable
     */
    Segment.prototype.addSegmentMoveHandler = function () {
        var midX = (this.startPoint.x + this.endPoint.x) / 2,
            midY = (this.startPoint.y + this.endPoint.y) / 2;
        this.moveHandler = new SegmentMoveHandler({
            parent: this,
            orientation: this.orientation,
            style: {
                cssProperties: {
                    border: "1px solid black"
                }
            }
        });
        midX -= this.moveHandler.width / 2;
        midY -= this.moveHandler.height / 2;
        this.moveHandler.setPosition(midX, midY);
        this.html.appendChild(this.moveHandler.getHTML());
        this.moveHandler.paint();
        this.moveHandler.attachListeners(this.moveHandler);
        return this;
    };

    /**
     * Returns the parent of the segment
     * @returns {Canvas / Connection}
     */
    Segment.prototype.getParent = function () {
        return this.parent;
    };

    /**
     * Returns the start point of the segment.
     * @returns {Point}
     */
    Segment.prototype.getStartPoint = function () {
        return this.startPoint;
    };

    /**
     * Returns the end point of the segment.
     * @returns {Point}
     */
    Segment.prototype.getEndPoint = function () {
        return this.endPoint;
    };

    /**
     * Sets the parent of the segment.
     * @param {Object} newParent
     * @chainable
     */
    Segment.prototype.setParent = function (newParent) {
        this.parent = newParent;
        return this;
    };

    /**
     * Sets the start point of the segment.
     * @param {Point} newPoint
     * @chainable
     */
    Segment.prototype.setStartPoint = function (newPoint) {
        this.startPoint = newPoint;
        return this;
    };

    /**
     * Sets the end point of the segment.
     * @param {Point} newPoint
     * @chainable
     */
    Segment.prototype.setEndPoint = function (newPoint) {
        this.endPoint = newPoint;
        return this;
    };

    /**
     * Sets the segmentStyle of this segment
     * @param {string} newStyle
     * @chainable
     *
     */
    Segment.prototype.setStyle = function (newStyle) {
        this.segmentStyle = newStyle;
        return this;
    };

    /**
     * Sets the color of this segment
     * @param {Color} newColor
     * @chainable
     */
    Segment.prototype.setColor = function (newColor) {
        this.segmentColor = newColor;
        return this;
    };

    /**
     * Creates an intersection with `otherSegment` and saves it in `this.intersections`.
     * If it doesn't have an intersection point passed as a parameter it will determine the
     * intersection point and add it to `this.intersections` (`this.intersections` considers only unique points)
     * @param {Segment} otherSegment
     * @param {Point} [ip] Intersection Point
     * @chainable
     */
    Segment.prototype.createIntersectionWith = function (otherSegment, ip) {
        var intersectionObject,
            intersectionPoint,
            i,
            goodToInsert = true;
        if (ip) {
            intersectionPoint = ip;
        } else {
            intersectionPoint = Geometry.segmentIntersectionPoint(this.startPoint,
                this.endPoint, otherSegment.startPoint, otherSegment.endPoint);
        }

        // let's consider the case when an intersection point is the same i.e. when a segment crosses two
        // other segments in the same point
        for (i = 0; i < this.intersections.getSize(); i += 1) {
            if (ip.equals(this.intersections.get(i).center)) {
                goodToInsert = false;
            }
        }

        if (goodToInsert) {
            intersectionObject = new Intersection(intersectionPoint,
                otherSegment.parent.getID(), this);
            this.html.appendChild(intersectionObject.getHTML());
            intersectionObject.paint();
            this.intersections.insert(intersectionObject);
        }

        //console.log(intersectionObject);
        //console.log(this.intersections);
        return this;
    };

    /**
     * Clear all the intersections in this segment.
     * @chainable
     */
    Segment.prototype.clearIntersections = function () {
        var i,
            intersection,
            size = this.intersections.getSize();
        while (size > 0) {
            intersection = this.intersections.get(size - 1);
            $(intersection.html).remove();
            this.intersections.popLast();
            size -= 1;
        }
        return this;
    };

    /**
     * Moves the segment either to x or y but not both (this method is called from {@link SegmentMoveHandler#event-drag}).
     * @param {number} x new x coordinate of the segment in the canvas
     * @param {number} y new y coordinate of the segment in the canvas
     */
    Segment.prototype.moveSegment = function (x, y) {
        var handler = this.moveHandler,
            prevNeighbor = this.previousNeighbor,
            nextNeighbor = this.nextNeighbor,
            midX,
            midY;

        if (handler.orientation === handler.VERTICAL) {
            this.startPoint.x = x
            + handler.width / 2;
            this.endPoint.x = x
            + handler.width / 2;
            prevNeighbor.endPoint.x =
                this.startPoint.x;
            nextNeighbor.startPoint.x =
                this.endPoint.x;
        } else {
            this.startPoint.y = y
            + handler.height / 2;
            this.endPoint.y = y
            + handler.height / 2;
            prevNeighbor.endPoint.y =
                this.startPoint.y;
            nextNeighbor.startPoint.y =
                this.endPoint.y;
        }

        // fix handler for the this segment
        if (this.moveHandler) {     // of course yes!
            midX = (this.startPoint.x + this.endPoint.x) / 2
            - this.moveHandler.width / 2;
            midY = (this.startPoint.y + this.endPoint.y) / 2
            - this.moveHandler.height / 2;
            this.moveHandler.setPosition(midX, midY);
        }

        // paint the previous segment
        prevNeighbor.paint();
        // fix handler for the prev segment if possible
        if (prevNeighbor.moveHandler) {
            midX = (prevNeighbor.startPoint.x + prevNeighbor.endPoint.x) / 2
            - prevNeighbor.moveHandler.width / 2;
            midY = (prevNeighbor.startPoint.y + prevNeighbor.endPoint.y) / 2
            - prevNeighbor.moveHandler.height / 2;
            prevNeighbor.moveHandler.setPosition(midX, midY);
        }

        // paint the next segment
        nextNeighbor.paint();
        // fix moveHandler for the next segment if possible
        if (nextNeighbor.moveHandler) {
            midX = (nextNeighbor.startPoint.x + nextNeighbor.endPoint.x) / 2
            - nextNeighbor.moveHandler.width / 2;
            midY = (nextNeighbor.startPoint.y + nextNeighbor.endPoint.y) / 2
            - nextNeighbor.moveHandler.height / 2;
            nextNeighbor.moveHandler.setPosition(midX, midY);
        }

        this.paint();
        return this;
    };

    /**
     * @abstract
     * @class RegularShape
     * @extend Shape
     * The class RegularShape represents all
     * regular shapes created in the canvas such as, rectangles, ovals, ports, and
     * handlers
     *
     * This class will hold all the common behavior of regular shapes
     * like rectangles or ovals
     *
     * @constructor
     * Initializes a regular shape
     */
    RegularShape = function (options) {
        Shape.call(this, options);

        /**
         * color of the shape
         * @property {Color}
         */
        this.color = new Color();

        /**
         * Graphics for this regular shape
         */
        this.graphics = null;
    };

    RegularShape.prototype = new Shape();

    /**
     * Type of the shape
     * @property {String}
     */
    RegularShape.prototype.type = "RegularShape";

//getters
    /**
     * Returns the color of the shape
     * @returns {Color}
     */
    RegularShape.prototype.getColor = function () {
        return this.color;
    };

    /**
     * Sets the color of the shape
     * @returns {RegularShape}
     * @param {Color} newColor
     */
    RegularShape.prototype.setColor = function (newColor) {
        if (newColor.type && newColor.type === "Color") {
            this.color = newColor;
        }
        return this;
    };

    /**
     * @class Polygon
     * Abstract class polygon to draw simple poly-lines
     *
     * An example of usage:
     *
     *      var polygon = new Polygon({
 *          points: []
 *      });
     *
     * @extend RegularShape
     *
     * @constructor Creates an instance of the class Polygon
     * @param {Object} options Initialization options
     * @cfg {Array} [points=[]] The points that make the polygon
     */
    Polygon = function (options) {

        RegularShape.call(this, options);

        /**
         * The points representing this polygon
         * @property {Array}
         */
        this.points = null;

        Polygon.prototype.initObject.call(this, options);
    };

    Polygon.prototype = new RegularShape();

    /**
     * The type of each instance of this class
     * @property {String}
     */
    Polygon.prototype.type = "Polygon";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} options The object that contains the config
     * @private
     */
    Polygon.prototype.initObject = function (options) {
        var defaults = {
            points: []
        };
        $.extend(true, defaults, options);
        this.setPoints(defaults.points);
    };

    /**
     * Sets the points of this polygon
     * @param {Array} newPoints
     * @chainable
     */
    Polygon.prototype.setPoints = function (newPoints) {
        var i, point;
        this.points = [];
        for (i = 0; i < newPoints.length; i += 1) {
            point = newPoints[i];
            this.points.push(new Point(point.getX(), point.getY()));
        }
    };

    /**
     * Gets the points of this polygon
     * @return {Array}
     */
    Polygon.prototype.getPoints = function () {
        return this.points;
    };


    /**
     * @class Rectangle
     * A regular shape that represents a rectangle, in the jCore framework instances of the class Rectangle
     * are used to represent a resize handler and a segment move handler.
     *
     * Some examples of use:
     *
     *      var rectangle = new Rectangle();
     *
     * @extend Polygon
     *
     * @constructor Creates an instance of the class Rectangle
     * @param {Object} options Initialization options (currently there are no initialization options)
     */
    Rectangle = function (options) {
        Polygon.call(this, options);
        Rectangle.prototype.initObject.call(this, options);
    };

    Rectangle.prototype = new Polygon();

    /**
     * The type of each instance of this class
     * @property {String}
     */
    Rectangle.prototype.type = "Rectangle";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} options The object that contains the config
     * @private
     */
    Rectangle.prototype.initObject = function (options) {
    };

    /**
     * Paints the rectangle applying the predefined style and adding a background color if
     * it's possible (it's possible if this rectangle has an instance of the class Color).
     * @chainable
     */
    Rectangle.prototype.paint = function () {
        if (this.html) {
            // apply predefined style
            this.style.applyStyle();

            if (this.color) {
                this.style.addProperties({
                    backgroundColor: this.color.getCSS()
                });
            }
        }
        return this;
    };

    /**
     * Creates the HTML representation of the Rectangle
     * @returns {HTMLElement}
     */
    Rectangle.prototype.createHTML = function () {
        Shape.prototype.createHTML.call(this);
        return this.html;
    };

    /**
     * @class Oval
     * A regular shape that represents an oval, in the jCore framework instances of the class Oval
     * are used to represent a Port.
     *
     * Some examples of use:
     *
     *      var oval = new Oval({
 *          width: 8,
 *          height: 8,
 *          center: new Point(100, 100)
 *      });
     *
     * @extend RegularShape
     *
     * @constructor Creates an instance of the class Oval
     * @param {Object} options Initialization options
     * @cfg {number} [width=4] The width of this oval
     * @cfg {number} [height=4] The height of this oval
     * @cfg {number} [center=new Center(0, 0)] The center of this oval
     */
    Oval = function (options) {
        RegularShape.call(this, options);

        Oval.prototype.initObject.call(this, options);
    };

    Oval.prototype = new RegularShape();

    /**
     * The type of each instance of this class
     * @property {String} [type=Oval]
     */
    Oval.prototype.type = "Oval";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Object} options The object that contains the config
     * @private
     */
    Oval.prototype.initObject = function (options) {
        /**
         * Default options for the object
         * @property {Object}
         */
        var defaults = {
            center: new Point(0, 0),
            width : 4,
            height : 4
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaults, options);

        // call setters using the defaults object
        this.setCenter(defaults.center)
            .setWidth(defaults.width)
            .setHeight(defaults.height);
    };

    /**
     * Paints a **red** oval using the configuration options (the HTML is not appended to
     * the DOM)
     * @chainable
     */
    Oval.prototype.paint = function () {

        // show or hide the oval
        this.setVisible(this.visible);

        if (this.html) {
            // apply predefined style
            this.style.applyStyle();

            //TODO Rewrite this method using HTML/CSS
        }
        return this;
    };

    /**
     * Creates the HTML representation of the Oval
     * @returns {HTMLElement}
     */
    Oval.prototype.createHTML = function () {

        Shape.prototype.createHTML.call(this);
//  this.html.style.backgroundColor = this.color.getCSS();
        return this.html;
        //return this;
    };
    /**
     * Set the oval Color
     * @param {String} newColor
     * @chainable
     */
    Oval.prototype.setColor = function(newColor) {
        this.graphic.setColor(newColor);
        this.graphic.fillOval(0, 0, this.getWidth(), this.getHeight());
        this.graphic.paint();
        return this;
    }

    /**
     * @class Arc
     * @extend RegularShape
     * Class arc that draws arcs using the HTMLElement draw engine.
     * Since the canvas is inverted in the y-axis the angles are:
     *      TOP    270
     *      RIGHT  0
     *      BOTTOM 90
     *      LEFT   180
     * An arc can be defined with the following elements:
     *
     * - *startAngle* start angle `0 <= startAngle < 360`.
     * - *endAngle* end angle `0 <= endAngle < 360`.
     * - *radius* the radius of the circle.
     *
     * Besides, the HTMLElement draw engine needs another parameter called *step*, this field tells the engine to
     * draw only at '*step*' steps
     *      i.e.
     *      startAngle = 90
     *      endAngle = 150
     *      engine.paint() with step = 10:
     *          90, 100, 110, 120, 130, 140, 150
     *      engine.paint() with step = 20:
     *          90, 110, 130, 150
     * As a consequence of the y-axis being inverted,we start drawing from the end angle towards the start angle,
     * therefore to draw an arc from 0 deg to 90 deg we must invert the parameters
     *      ´var a = new Arc({
 *          center: new Point(10, 10),
 *          radius: 200,
 *          startAngle: 270,
 *          endAngle: 0
 *      });´
     *
     * @param {Object} options Initialization options
     * @cfg {Point} [center=new Points(0, 0)] Point representing the center of the arc
     * @cfg {number} [radius=Shape.prototype.DEFAULT_RADIUS] radius of the arc
     * @cfg {number} [startAngle=270] start angle of the arc
     * @cfg {number} [endAngle=90] end angle of the arc
     * @cfg {number} [step=10] steps to jump from end angle (to get to start angle)
     * @constructor Creates a new instance of arc
     */
    Arc = function (options) {
        RegularShape.call(this);

        /**
         * Start angle of this arc
         * @property {number}
         */
        this.startAngle = null;

        /**
         * End angle of this arc
         * @property {number}
         */
        this.endAngle = null;

        /**
         * Radius of the arc
         * @property {number}
         */
        this.radius = null;

        /**
         * Steps to draw the arc
         * @property {number}
         */
        this.step = null;

        // set defaults
        Arc.prototype.initObject.call(this, options);
    };

    Arc.prototype = new RegularShape();

    /**
     * Type of this shape
     * @property {String}
     */
    Arc.prototype.type = "Arc";

    /**
     * Instance initializer which uses options to extend the config options to
     * initialize the instance
     * @private
     * @param {Object} options
     */
    Arc.prototype.initObject = function (options) {

        // Default options for the object
        var defaults = {
            center: new Point(0, 0),
            radius: Shape.prototype.DEFAULT_RADIUS,
            startAngle: 270,
            endAngle: 90,
            step: 10
        };

        // extend recursively defaults with the given options
        $.extend(true, defaults, options);

        // call setters using the defaults object
        this.setCenter(defaults.center)
            .setStartAngle(defaults.startAngle)
            .setEndAngle(defaults.endAngle)
            .setRadius(defaults.radius)
            .setStep(defaults.step);

        // change the id (to debug easier)
        this.id += "-ARC";
    };

    /**
     * In charge of the painting / positioning of the figure on
     * the DOM and setting the styles
     * @chainable
     */
    Arc.prototype.paint = function () {

        this.setVisible(this.visible);

        if (this.html) {
            this.style.applyStyle();
            // this.graphic is inherited from RegularShape
            //TODO Create a new paint module based in HTML/CSS3
        }
        return this;
    };

    /**
     * Creates the HTML representation of the Arc
     * @returns {HTMLElement}
     */
    Arc.prototype.createHTML = function () {
        Shape.prototype.createHTML.call(this);
        return this.html;
    };

    /**
     * Returns the startAngle of the arc
     * @returns {number}
     */
    Arc.prototype.getStartAngle = function () {
        return this.startAngle;
    };

    /**
     * Sets the startAngle of the arc
     * @param {number} newAngle
     * @chainable
     */
    Arc.prototype.setStartAngle = function (newAngle) {
        this.startAngle = newAngle;
        return this;
    };

    /**
     * Returns the endAngle of the arc
     * @returns {number}
     */
    Arc.prototype.getEndAngle = function () {
        return this.endAngle;
    };

    /**
     * Sets the endAngle of the arc
     * @param {number} newAngle
     * @chainable
     */
    Arc.prototype.setEndAngle = function (newAngle) {
        this.endAngle = newAngle;
        return this;
    };

    /**
     * Returns the radius of the arc
     * @returns {number}
     */
    Arc.prototype.getRadius = function () {
        return this.radius;
    };

    /**
     * Sets the radius of the arc
     * @param {number} newRadius
     * @chainable
     */
    Arc.prototype.setRadius = function (newRadius) {
        this.radius = newRadius;
        return this;
    };

    /**
     * Returns the radius of the arc
     * @returns {number}
     */
    Arc.prototype.getStep = function () {
        return this.step;
    };

    /**
     * Sets the step to draw the arc (steps jumped from startAngle to endAngle)
     * @param {number} newStep
     * @chainable
     */
    Arc.prototype.setStep = function (newStep) {
        this.step = newStep;
        return this;
    };

    /**
     * @class MultipleSelectionContainer
     * Represents the rectangle created to do multiple selection after firing {@link Canvas#event-mousedown} and
     * dragging a rectangle over the desired elements in the canvas and firing {@link Canvas#event-mouseup},
     * currently it can only select shapes that are direct children of the canvas.
     *
     * An example of use:
     *
     *      // let's assume that canvas is an instance of the class Canvas
     *      var multipleSelectionContainer = new MultipleSelectionContainer(canvas);
     *
     * @extend Rectangle
     *
     * @constructor Creates an instance of the class MultipleSelectionContainer
     * @param {Canvas} canvas
     */
    MultipleSelectionContainer = function (canvas) {
        RegularShape.call(this);

        /**
         * The background color of this element
         * @property {Color}
         */
        this.backgroundColor = null;

        /**
         * Reference to the canvas
         * @property {Canvas}
         */
        this.canvas = null;

        // init object
        MultipleSelectionContainer.prototype.initObject.call(this, canvas);
    };

    MultipleSelectionContainer.prototype = new Rectangle();

    /**
     * Type of each instance of this class
     * @property {String}
     */
    MultipleSelectionContainer.prototype.type = "MultipleSelectionContainer";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance
     * @param {Canvas} canvas The canvas of this multiple selection container
     * @private
     */
    MultipleSelectionContainer.prototype.initObject = function (canvas) {
        this.backgroundColor = new Color(0, 128, 255, 0.1);     // light blue
        this.canvas = canvas;
        // add this element to the canvas
        this.canvas.addElement(this, 0, 0, true);
    };

    /**
     * Paints the multiple selection container with the color *light blue* (defined in initObject)
     * @chainable
     */
    MultipleSelectionContainer.prototype.paint = function () {
        this.style.addProperties({
            backgroundColor: this.backgroundColor.getCSS()
        });
        return this;
    };

    /**
     * Changes the opacity of this multiple selection container (and repaints it later).
     * @param {number} value
     * @chainable
     */
    MultipleSelectionContainer.prototype.changeOpacity = function (value) {
        this.backgroundColor.setOpacity(value);
        this.paint();
        return this;
    };

    /**
     * Wraps the direct children of the canvas. To call this method it's assumed that this instance has a position
     * and a dimension in the canvas (this method is called from {@link Canvas#event-mouseup}).
     * It executes the following actions:
     *
     * 1. Gathers the currentSelection
     * 2. Checks which direct children of the canvas are inside the selection
     *      (done through {@link MultipleSelectionContainer#intersectElements}).
     * 3. Fires {@link Canvas#triggerSelectEvent} using the new `this.canvas.currentSelection`
     * 4. Resets the state of this instance
     *
     * @chainable
     */
    MultipleSelectionContainer.prototype.wrapElements = function () {
        var currentSelection = this.canvas.currentSelection,
            selection = [];
        this.intersectElements();
        if (!currentSelection.isEmpty()) {
            selection = currentSelection.asArray();
            this.canvas.triggerSelectEvent(selection);
        }
        this.reset();
        this.setVisible(false);
        return this;
    };

    /**
     * Checks which direct children of the canvas are inside `this` (represented as a rectangle in the canvas).
     * The steps are:
     *
     * 1. Empty `this.canvas.currentSelection` by calling {@link Canvas#emptyCurrentSelection}.
     * 2. If a child is inside this rectangle then it's added to `this.canvas.currentSelection`.
     *
     * @chainable
     */
    MultipleSelectionContainer.prototype.intersectElements = function () {
        var i,
            shape,
            children;

        //empty the current selection
        this.canvas.emptyCurrentSelection();

        // get all the customShapes
        children = this.canvas.customShapes;
        for (i = 0; i < children.getSize(); i += 1) {
            shape = children.get(i);
            if (shape.parent.family === "Canvas" && this.checkIntersection(shape)) {
                this.canvas.addToSelection(shape);
            }
        }
        return this;
    };

    /**
     * Resets the position and dimensions of this selection container.
     * @chainable
     */
    MultipleSelectionContainer.prototype.reset = function () {
        this.setPosition(0, 0);
        this.setDimension(0, 0);
        return this;
    };

    /**
     * Alias for {@link JCoreObject#getAbsoluteX}.
     * @property {Function} getLeft
     */
    MultipleSelectionContainer.prototype.getLeft = Shape.prototype.getAbsoluteX;

    /**
     * Alias for {@link JCoreObject#getAbsoluteY}.
     * @property {Function} getTop
     */
    MultipleSelectionContainer.prototype.getTop = Shape.prototype.getAbsoluteY;

    /**
     * Checks if `shape` is inside `this`, `shape` is inside `this` if one of its corners
     * its inside `this`.
     * @param {Shape} shape
     * @return {boolean}
     */
    MultipleSelectionContainer.prototype.checkIntersection = function (shape) {
        var topLeft = new Point(this.zoomX, this.zoomY),
            bottomRight = new Point(this.zoomX + this.zoomWidth,
                this.zoomY + this.zoomHeight);
        return Geometry.pointInRectangle(new Point(shape.getZoomX(), shape.getZoomY()),
            topLeft, bottomRight) ||
        Geometry.pointInRectangle(new Point(shape.zoomX +
        shape.zoomWidth, shape.zoomY), topLeft, bottomRight) ||
        Geometry.pointInRectangle(new Point(shape.zoomX, shape.zoomY +
        shape.zoomHeight), topLeft, bottomRight) ||
        Geometry.pointInRectangle(new Point(shape.zoomX +
        shape.zoomWidth, shape.zoomY + shape.zoomHeight), topLeft, bottomRight);
    };

    /**
     * @class Intersection
     * An intersection in the designer is defined as an arc which has only one additional property: `idOtherConnection`
     * and it's the ID of the other connection this segment has intersection with.
     *
     * All the intersection of a segment are stored in `segment.intersections`.
     *
     * An example of instantiation:
     *
     *      // let's assume that 'segment' is an instance of the class Segment
     *      // let's assume that 'otherSegment' is an instance of the class Segment
     *      // let's assume that 'segment' has an intersection with 'otherSegment' at 'ip' (intersection point)
     *      var intersection = new Intersection(
     *          ip,
     *          otherSegment.parent,getID(),
     *          segment
     *      );
     *
     * @extends Arc
     *
     * @constructor Creates an instance of the class Intersection
     * @param {Point} center
     * @param {string} idOtherConnection
     * @param {Segment} parent
     */
    Intersection = function (center, idOtherConnection, parent) {

        Arc.call(this);
        /**
         * The center of the arc
         * @property {Point} [center=null]
         */
        this.center = (!center) ? null : center;
        /**
         * Visibility of this arc
         * @property {boolean}
         */
        this.visible = true;
        /**
         * Parent of this intersection is a segment
         * @property {Segment}
         */
        this.parent = parent;
        /**
         * Id of the other connection
         * @property {String}
         */
        this.idOtherConnection = idOtherConnection;
    };

    Intersection.prototype = new Arc();

    /**
     * The type of each instance of the class Intersection
     * @property {String}
     */
    Intersection.prototype.type = "Intersection";

    /**
     * Paints this intersection (calling `Arc.paint()`) considering the orientation of its parent.
     *
     * It overwrites the properties `startAngle` and `endAngle` inherited from {@link Arc}
     * according to the orientation of its parent (segment), the calculation is as follows:
     *
     * - Segment is vertical
     *      - startAngle = 270
     *      - endAngle = 90
     * - Segment is horizontal
     *      - startAngle = 180
     *      - endAngle = 0
     *
     * @chainable
     */
    Intersection.prototype.paint = function () {

        // NOTE: it's always visible so do not call setVisible()

        if (this.parent.orientation === this.VERTICAL) {
            this.startAngle = 270;
            this.endAngle = 90;
        } else {
            this.startAngle = 180;
            this.endAngle = 0;
        }

        // call the representation (always an arc)
        Arc.prototype.paint.call(this);

        // apply predefined style
        this.style.applyStyle();

        return this;
    };

    /**
     * Destroys the intersection by removing its HTML from the DOM.
     * @chainable
     */
    Intersection.prototype.destroy = function () {
        $(this.html).remove();
        return this;
    };

    /**
     * Creates the HTML representation of the Intersection.
     * @returns {HTMLElement}
     */
    Intersection.prototype.createHTML = function () {
        Shape.prototype.createHTML.call(this);
        return this.html;
    };

    /**
     * @class Snapper
     * Class snapper represents the helper shown while moving shapes.
     * @extend JCoreObject
     *
     * @constructor Creates an instance of the class Snapper
     * @param {Object} options Initialization options
     * @cfg {Point} [orientation="horizontal"] The default orientation of this snapper
     */
    Snapper = function (options) {

        JCoreObject.call(this, options);

        /**
         * Orientation of this snapper, it can be either "horizontal" or "vertical".
         * @property {string} [orientation=null]
         */
        this.orientation = null;

        /**
         * Data saved to define the positioning of this snapper in the canvas.
         * @property {Array} [data=[]]
         */
        this.data = [];

        /**
         * The visibility of this snapper.
         * @property {boolean} [visible=false]
         */
        this.visible = false;

        Snapper.prototype.initObject.call(this, options);
    };

    Snapper.prototype = new JCoreObject();

    /**
     * The type of each instance of this class
     * @property {String}
     */
    Snapper.prototype.type = "Snapper";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance.
     * @param {Object} options The object that contains the config
     * @private
     */
    Snapper.prototype.initObject = function (options) {

        var defaults = {
            orientation: "horizontal"
        };

        // extend recursively the defaultOptions with the given options
        $.extend(true, defaults, options);

        // call setters using the defaults object
        this.setOrientation(defaults.orientation);
        this.setDimension(defaults.width,defaults.height);

        // create the html (it's hidden initially)
        this.createHTML();
        this.hide();
    };

    /**
     * Creates the HTML representation of the snapper.
     * @returns {HTMLElement}
     */
    Snapper.prototype.createHTML = function () {
        JCoreObject.prototype.createHTML.call(this);
        $(this.canvas.getHTML()).append(this.html);
        this.setZOrder(99);
        this.html.className = 'custom-snapper';

        if(this.getOrientation() == 'horizontal') {
            this.html.id = 'guide-h';
            this.html.style.borderTop = '1px dashed #55f';
            this.html.style.width = '100%';
        } else {
            this.html.id = 'guide-v';
            this.html.style.borderLeft = '1px dashed #55f';
            this.html.style.height = '100%';
        }
        return this.html;
    };

    /**
     * Hides the snapper.
     * @chainable
     */
    Snapper.prototype.hide = function () {
        this.visible = false;
        this.setVisible(this.visible);
        return this;
    };

    /**
     * Shows the snapper.
     * @chainable
     */
    Snapper.prototype.show = function () {
        this.visible = true;
        this.setVisible(this.visible);
        return this;
    };

    /**
     * Fills the data for the snapper (using customShapes and regularShapes).
     * The data considered for each shape is:
     *
     * - Its absoluteX
     * - Its absoluteY
     * - Its absoluteX + width
     * - Its absoluteY + height
     *
     * @chainable
     */
    Snapper.prototype.createSnapData = function () {
        var i,
            index = 0,
            shape,
            border = 0;

        // clear the data before populating it
        this.data = [];

//    console.log("Sizes:");
//    console.log(this.canvas.customShapes.getSize());
//    console.log(this.canvas.regularShapes.getSize());

        // populate the data array using the customShapes
        for (i = 0; i < this.canvas.customShapes.getSize(); i += 1) {
            shape = this.canvas.customShapes.get(i);
            if (!this.canvas.currentSelection.find('id', shape.getID())) {
                border = parseInt($(shape.getHTML()).css('borderTopWidth'), 10);
                if (this.orientation === 'horizontal') {
                    this.data[index * 2] = shape.getAbsoluteY() - border;
                    this.data[index * 2 + 1] = shape.getAbsoluteY() + shape.getZoomHeight();
                } else {
                    this.data[index * 2] = shape.getAbsoluteX() - border;
                    this.data[index * 2 + 1] = shape.getAbsoluteX() + shape.getZoomWidth();
                }
                index += 1;
            }

        }

        // populate the data array using the regularShapes
        for (i = 0; i < this.canvas.regularShapes.getSize(); i += 1) {
            shape = this.canvas.regularShapes.get(i);
            border = parseInt($(shape.getHTML()).css('borderTopWidth'), 10);
            if (this.orientation === 'horizontal') {
                this.data[index * 2] = shape.getAbsoluteY() - border;
                this.data[index * 2 + 1] = shape.getAbsoluteY() +
                shape.getZoomHeight();
            } else {
                this.data[index * 2] = shape.getAbsoluteX() - border;
                this.data[index * 2 + 1] = shape.getAbsoluteX() +
                shape.getZoomWidth();
            }
            index += 1;
        }
        return this;
    };

    /**
     * Sorts the data using the builtin `sort()` function, so that there's an strictly increasing order.
     * @chainable
     */
    Snapper.prototype.sortData = function () {
        this.data.sort(function (a, b) {
            return a > b;
        });
        return this;
    };

    /**
     * Performs a binary search for `value` in `this.data`, return true if `value` was found in the data.
     * @param {number} value
     * @return {boolean}
     */
    Snapper.prototype.binarySearch = function (value) {
        var low = 0,
            up = this.data.length - 1,
            mid;

        while (low <= up) {
            mid = parseInt((low + up) / 2, 10);
            if (this.data[mid] === value) {
                return value;
            }
            if (this.data[mid] > value) {
                up = mid - 1;
            } else {
                low = mid + 1;
            }
        }
        return false;
    };

    /**
     * Attaches listeners to this snapper, currently it only has the
     * mouseMove event which hides the snapper.
     * @param {Snapper} snapper
     * @chainable
     */
    Snapper.prototype.attachListeners = function (snapper) {
        var $snapper = $(snapper.html).mousemove(
            function () {
                snapper.hide();
            }
        );
        return this;
    };

    /**
     * Sets the orientation of this snapper.
     * @param {string} orientation
     * @chainable
     */
    Snapper.prototype.setOrientation = function (orientation) {
        if (orientation === "horizontal" || orientation === "vertical") {
            this.orientation = orientation;
        } else {
            throw new Error("setOrientation(): parameter is not valid");
        }
        return this;
    };

    /**
     * Gets the orientation of this snapper.
     * @return {string}
     */
    Snapper.prototype.getOrientation = function () {
        return this.orientation;
    };

    /**
     * @class ReadOnlyLayer
     * Layer used to give the canvas a readonly state so that the user can just look
     * at the diagram and not be able to perform any modification,
     * the canvas is in charge of instantiating this object when its property
     * readOnly is set to true, there is no need to instance this object
     * independently
     * @extends JCoreObject
     *
     * @constructor
     * Creates an instance of this class
     * @param {Object} options configuration options inherited from JCoreObject
     */

    ReadOnlyLayer = function (options) {
        JCoreObject.call(this, options);
        ReadOnlyLayer.prototype.initObject.call(this, options);
    };

    ReadOnlyLayer.prototype = new JCoreObject();
    /**
     * Creates the HTML and attach the event listeners
     * @param options
     */
    ReadOnlyLayer.prototype.initObject = function (options) {
        this.createHTML();
        this.attachListeners();
    };
    /**
     * Attach the event listeners necessary for blocking interactions
     */
    ReadOnlyLayer.prototype.attachListeners = function () {
        var $layer = $(this.html);
        $layer.on('mousedown', this.onMouseDown(this))
            .on('mouseup', this.onMouseUp(this))
            .on('mousemove', this.onMouseMove(this))
            .on('click', this.onClick(this))
            .droppable({
                accept : "*",
                greedy : true,
                onDrop : function () {
                    return false;
                }
            });
    };
    /**
     * Stops the propagation of the mousedown event
     * @param {ReadOnlyLayer} layer
     * @return {Function}
     */
    ReadOnlyLayer.prototype.onMouseDown = function (layer) {
        return function (e, ui) {
            e.stopPropagation();
        };
    };
    /**
     * Stops the propagation of the mouseup event
     * @param {ReadOnlyLayer} layer
     * @return {Function}
     */
    ReadOnlyLayer.prototype.onMouseUp = function (layer) {
        return function (e, ui) {
            e.stopPropagation();
        };
    };
    /**
     * Stops the propagation of the click event
     * @param {ReadOnlyLayer}layer
     * @return {Function}
     */
    ReadOnlyLayer.prototype.onClick = function (layer) {
        return function (e, ui) {
            e.stopPropagation();
        };
    };
    /**
     * Stops the propagation of the mousemove event
     * @param {ReadOnlyLayer} layer
     * @return {Function}
     */
    ReadOnlyLayer.prototype.onMouseMove = function (layer) {
        return function (e, ui) {
            e.stopPropagation();
        };
    };


    /**
     * @class Canvas
     * Is the object where all the shapes and drawings will be placed on, in addition it handles zoom operations
     * and also the triggering of events.
     *
     * Below are some unique characteristics of the instances of this class:
     *
     * - Each instance of this class has an instance of the following:
     *      - {@link CommandStack Command stack}
     *      - {@link Snapper Two snappers} (one horizontal snapper and one vertical snapper).
     *      - {@link MultipleSelectionContainer} to select multiple shapes.
     *      - {@link Segment}. To create connections between shapes.
     *      - {@link Canvas#property-customShapes CustomShapes arrayList}. To save all the custom shapes
     *          created in this canvas.
     *      - {@link Canvas#property-connections Connections arrayList}. To save all the connections
     *          created in this canvas.
     *      - {@link Canvas#property-currentSelection Current selection arrayList}. To save all the custom shapes
     *          that are select (by clicking, ctrl clicking them or selecting them using the
     *          multipleSelectionContainer instance).
     *      - {@link Canvas#property-currentConnection Current connection}. A pointer to the selected
     *          connection.
     *      - {@link Canvas#property-currentLabel Current label}. A pointer to the active label.
     *
     * Besides this class does the following:
     *
     * - Parses the JSON retrieved from the database (through {@link Canvas#parse})
     * - Creates some custom events (defined in {@link Canvas#attachListeners})
     * - Creates, stores and executes the commands (through its {@link Canvas#property-commandStack}) property
     *
     * Below is an example of instantiation of this class:
     *
     *      // The canvas needs an object containing the reference to existing classes outside the library
     *      // i.e. let's define two classes
     *      var BpmnActivity = function (options) {
 *          ...
 *      };
     *      var BpmnEvent = function (options) {
 *          ...
 *      };
     *
     *      // Next, the canvas needs a factory function to create custom shapes dragged from a toolbar
     *      // this function needs an ID to create the shape
     *      function toolbarFactory (id) {
 *          var customShape = null;
 *          switch(id) {
 *              case: 'BpmnActivity':
 *                  customShape = new BpmnActivity({
 *                      ....
 *                  });
 *                  break;
 *              case: 'BpmnEvent':
 *                  customShape = new BpmnEvent({
 *                      ....
 *                  });
 *                  break;
 *          }
 *          return customShape;
 *      }
     *
     *      // finally an instance of this class can be defined
     *      var canvas = new Canvas({
 *          width: 4000,
 *          height: 4000,
 *          toolbarFactory: toolbarFactory,
 *          copyAndPasteReferences: {
 *              bpmnActivity: BpmnActivity,
 *              bpmnEvent: BpmnEvent
 *          }
 *      });
     *
     * @extends BehavioralElement
     *
     * @constructor
     * Creates an instance of the class
     * @param {Object} options configuration options of the canvas
     * @cfg {number} [width=4000] Width of this canvas.
     * @cfg {number} [height=4000] Height of this canvas.
     * @cfg {Function} [commandStackOnSuccess=function(){}] commandStackOnSuccess callback of the command stack.
     * @cfg {Function} toolbarFactory Function that will handle object creation
     * from a custom toolbar
     * @cfg {Object} [copyAndPasteReferences={}] References to the constructors of the classes
     * (so that a shape is easily created from the canvas)
     * @cfg {boolean} [readOnly=false] Property that determines the permission a
     * user has over the canvas
     */
    Canvas = function (options) {
        BehavioralElement.call(this, options);

        /**
         * Variable that points to the HTML in the DOM of this object.
         * @property {HTMLElement} [html=null]
         */
        this.html = null;
        /**
         * A list of all the custom shapes in the canvas.
         * @property {ArrayList}
         */
        this.customShapes = null;
        /**
         * A list of all the regular shapes in the canvas.
         * @property {ArrayList}
         */
        this.regularShapes = null;
        /**
         * A list of all the connections in the canvas.
         * @property {ArrayList}
         */
        this.connections = null;
        /**
         * A list of all the shapes that are currently selected.
         * @property {ArrayList}
         */
        this.currentSelection = null;
        /**
         * A list of all the connections that will not be repainted (using the ManhattanRouter algorithm),
         * but will be moved only.
         * @property {ArrayList}
         */
        this.sharedConnections = null;
        /**
         * Left Scroll coordinate of the canvas
         * @property {number} [leftScroll=0]
         */
        this.leftScroll = 0;
        /**
         * Top scroll coordinate of the canvas
         * @property {number} [topScroll=0]
         */
        this.topScroll = 0;
        /**
         * Reference to the current selected connection in the canvas
         * @property {Connection}
         */
        this.currentConnection = null;
        /**
         * Pointer to the last connection selected in the canvas
         * (this variable is set from the commandDelete)
         * @property {Connection}
         */
        this.oldCurrentConnection = null;
        /**
         * Instance of the class {@link Segment} used to make connections in the canvas.
         * @property {Segment}
         */
        this.connectionSegment = null;
        /**
         * Instance of the class {@link MultipleSelectionContainer} created to do multiple selection
         * @property {MultipleSelectionContainer}
         */
        this.multipleSelectionHelper = null;
        /**
         * Instance of the class {@link Snapper} which represents the horizontal line used for snapping
         * @property {Snapper}
         */
        this.horizontalSnapper = null;
        /**
         * Instance of the class {@link Snapper} which represents the vertical line used for snapping
         * @property {Snapper}
         */
        this.verticalSnapper = null;
        /**
         * Current zoom Factor of the diagram
         * @property {number} [zoomFactor=1]
         */
        this.zoomFactor = 1;
        /**
         * Index for the zoom properties for shapes corresponding to the current
         * zoom factor
         * @property {number} [zoomPropertiesIndex=2]
         */
        this.zoomPropertiesIndex = 2;
        /**
         * zOrder of the HTML Representation
         * @property {number} [zOrder=0]
         */
        this.zOrder = 0;
        /**
         * Boolean set true if the {@link Canvas#event-mousedown} event of the canvas is fired,
         * it's set to false in the {@link Canvas#event-mouseup} event.
         * @property {boolean} [isMouseDown=false]
         */
        this.isMouseDown = false;
        /**
         * Current selected shape
         * @property {Shape}
         */
        this.currentShape = null;
        /**
         * True if the {@link Canvas#event-mousedown} event of the canvas is triggered and
         * the {@link Canvas#event-mousemove} event is triggered, it's set to false in mouseUp event.
         * @property {boolean} [isMouseDownAndMove=false]
         */
        this.isMouseDownAndMove = false;
        /**
         * Denotes if there's been a multiple drop prior to a drag end.
         * @property {boolean} [multipleDrop=false]
         */
        this.multipleDrop = false;
        /**
         * Denotes if a segment move handler is being dragged. in order not to
         * trigger events in the canvas [draggingASegmentHandler=false]
         * @property {boolean}
         */
        this.draggingASegmentHandler = false;
        /**
         * Elements that was added, changed or deleted in the canvas.
         * @property {Object}
         */
        this.updatedElement = null;
        /**
         * Determines if the canvas has been right clicked at {@link Canvas#event-mousedown}
         * @property {boolean} [rightClick=false]
         */
        this.rightClick = false;
        /**
         * Each time a shape is moved using the cursors, the following code is executed:
         *
         *      // for each 'connection' that is not in this.sharedConnection and that it's being
         *      // recalculated (using ManhattanRouter algorithm)
         *      connection.disconnect().connect()
         *                  .setSegmentMoveHandlers()
         *                  .checkAndCreateIntersectionsWithAll();
         *
         *  So to avoid these operations for each key press of the cursors, let's create a timeout,
         *  so that only after that timeout has expired the code above will run.
         *  This variable is a reference to that timeout.
         * @property {Object}
         */
        this.intersectionTimeout = null;

        /**
         * Point to the current label that is being edited
         * @property {Object}
         */
        this.currentLabel = null;
        /**
         * Instance of the class {@link CommandStack} to be used in this canvas
         * @property {CommandStack}
         */
        this.commandStack = null;
        /**
         * Array which contains a list of all the objects that were duplicated
         * (during copy)
         * @property {Array} [shapesToCopy=[]]
         */
        this.shapesToCopy = [];
        /**
         * Array which contains a list of all the connections that were duplicated
         * (during copy)
         * @property {Array} [connectionsToCopy=[]]
         */
        this.connectionsToCopy = [];
        /**
         * Property that determines the permissions a user has over the canvas
         * @property {boolean} [readOnly=false]
         */
        this.readOnly = false;
        /**
         * Layer that prevents the canvas to be altered
         * @property {ReadOnlyLayer}
         */
        this.readOnlyLayer = null;
        /**
         * Object which holds references to the constructors of the classes
         * (so that a shape is easily created from the canvas)
         * @property {Object} [copyAndPasteReferences={}]
         */
        this.copyAndPasteReferences = {};
        /**
         * Previous zoom properties index
         * @property {number} [prevZoom=1]
         */
        this.prevZoom = 1;
        /**
         * Initializer for labels, so that jQuery can measure the width of a message
         * in the first time its created
         * @type {HTMLElement}
         */
        this.dummyLabelInitializer = null;
        /**
         * Minimum distance to "snap" to a guide
         * @type {number}
         */
        this. MIN_DISTANCE = 4;
        /**
         * Array which contains a list of all coordinates  to snap
         * @type {number}
         */
        this. guides = []; // no guides available ...

        Canvas.prototype.initObject.call(this, options);
    };

    Canvas.prototype = new BehavioralElement();
    /**
     * Type of the instances
     * @property {String}
     */
    Canvas.prototype.type = "Canvas";
    /**
     * Family of the instances, this attribute must not be overridden
     * @property {String}
     * @readonly
     */
    Canvas.prototype.family = "Canvas";

    /**
     * Instance initializer which uses options to extend the config options to initialize the instance.
     * The following properties are instantiated in this method:
     *
     *      this.children = new ArrayList();
     *      this.customShapes = new ArrayList();
     *      this.regularShapes = new ArrayList();
     *      this.connections = new ArrayList();
     *      this.currentSelection = new ArrayList();
     *      this.sharedConnections = new ArrayList();
     *      this.commandStack = new CommandStack(20, commandStackOnSuccess);
     *      this.multipleSelectionHelper = new MultipleSelectionContainer(this);
     *      this.horizontalSnapper = new Snapper({orientation: 'horizontal', canvas: this});
     *      this.verticalSnapper = new Snapper({orientation: 'vertical', canvas: this});
     *
     * @param {Object} options The object that contains the config
     * @private
     */
    Canvas.prototype.initObject = function (options) {

        var canvasPosition,
            defaults;

        defaults = {
            width: 4000,
            commandStackOnSuccess: function () {},
            height: 4000,
//        toolbarFactory : function (id) {return null; }
            toolbarFactory : (options && options.toolbarFactory) ||
            Canvas.prototype.toolBarShapeFactory ||
            function (id) {return null; },
            copyAndPasteReferences: (options && options.copyAndPasteReferences) || {},
            readOnly : false,
            snapToGuide : true
        };

        $.extend(true, defaults, options);

        if (options) {
            this.createHTML();
            canvasPosition = $("#" + this.id).offset();
            this.children = new ArrayList();
            this.customShapes = new ArrayList();
            this.regularShapes = new ArrayList();
            this.connections = new ArrayList();
            this.currentSelection = new ArrayList();
            this.sharedConnections = new ArrayList();
            this.commandStack = new CommandStack(20, defaults.commandStackOnSuccess);
            this.multipleSelectionHelper = new MultipleSelectionContainer(this);
            this.copyAndPaste = false;
            this.copyAndPasteReferences = defaults.copyAndPasteReferences;
            this.setToolBarShapeFactory(defaults.toolbarFactory);

            this.setPosition(canvasPosition.left, canvasPosition.top)
                .setDimension(defaults.width, defaults.height)
                .setCanvas(this)
                .setSnapToGuide(defaults.snapToGuide)
                .setCopyAndPaste(defaults.copyAndPaste);

            if (defaults.readOnly) {
                this.setToReadOnly();
            }
        }
    };

    /**
     * Sets the read and write permissions of the canvas.
     * @param {boolean} readOnly Determines if the canvas will be set to read only
     * or if it will be editable
     * @chainable
     */
    Canvas.prototype.setReadOnly = function (readOnly) {
        if (readOnly) {
            this.setToReadOnly();
        } else {
            this.unsetReadOnly();
        }
        return this;
    };

    /**
     * Sets the canvas to readOnly mode by creating a ReadOnlyLayer instance and appending its html to
     * this html
     * @chainable
     */
    Canvas.prototype.setToReadOnly = function () {
        var readOnlyLayer = this.readOnlyLayer;
        if (readOnlyLayer && readOnlyLayer.html) {
            this.html.appendChild(this.readOnlyLayer.html);
        } else {
            this.readOnlyLayer = new ReadOnlyLayer({
                width : this.width,
                height : this.height
            });
            this.html.appendChild(this.readOnlyLayer.html);
        }
        this.readOnly = true;
        return this;
    };

    /**
     * Sets the canvas to read and write mode.
     * @chainable
     */
    Canvas.prototype.unsetReadOnly = function () {
        var readOnlyLayer = this.readOnlyLayer;
        this.html.removeChild(readOnlyLayer.getHTML());
        this.readOnly = false;
        return this;
    };

    /**
     * Sets the position of the canvas.
     * @param {number} x x coordinate relative to where the canvas is contained
     * @param {number} y y coordinate relative to where the canvas is contained
     * @chainable
     */
    Canvas.prototype.setPosition = function (x, y) {
        this.setX(x);
        this.setY(y);
        return this;
    };

    /**
     * Sets the x coordinate of the canvas, its zoomX and absoluteX to an equal value.
     * @param {number} newX new x coordinate to be applied in the canvas
     * @chainable
     */
    Canvas.prototype.setX = function (newX) {
        this.x = this.zoomX = newX;
        this.absoluteX = 0;
        return this;
    };

    /**
     * Set the y coordinate of the canvas, its zoomY and absoluteY to an equal value
     * @param {number} newY new y coordinate to be applied in the canvas
     * @chainable
     */
    Canvas.prototype.setY = function (newY) {
        this.y = this.zoomY = newY;
        this.absoluteY = 0;
        return this;
    };

    /**
     * Set guide Lines to canvas and create vertican and horizontal snappers
     * @param {Boolean} snap new value to verify if canvas has enabled snappes
     * @chainable
     */
    Canvas.prototype.setSnapToGuide = function (snap) {
        this.snapToGuide = snap;
        // create snappers
        if (!this.horizontalSnapper) {
            this.horizontalSnapper = new Snapper({
                orientation: 'horizontal',
                canvas: this,
                width : 4000,
                height: 1
            });
        }
        if (!this.verticalSnapper) {
            this.verticalSnapper = new Snapper({
                orientation: 'vertical',
                canvas: this,
                width : 1,
                height :4000
            });
        }
        return this;
    };
    /**
     * Sets copy and paste state
     * @param {boolean} value refered to enable and disable copy paste
     * @chainable
     */
    Canvas.prototype.setCopyAndPaste = function (value) {
        this.copyAndPaste = value;
        return this;
    };
    /**
     * Retrieves the div element that has this canvas id
     * @return {HTMLElement}
     */
    Canvas.prototype.createHTMLDiv = function () {
        return document.getElementById(this.id);
    };

    /**
     * Default shape factory for creating shapes.
     * @param {String} id
     * @return {CustomShape}
     * @template
     */
    Canvas.prototype.toolBarShapeFactory = function (id) {
        var customShape = null;

        switch (id) {
            case "tiny_shape":
                customShape = new CustomShape({
                    width: 50,
                    height: 50,
                    "canvas" : this,
                    "connectAtMiddlePoints": false,
                    "drop" : {
                        type : "connection"
                    },
                    "style": {
                        cssClasses: ["grey"],
                        cssProperties: {
                            border: "1px solid blue",
                            "-webkit-box-shadow": "0px 0px 4px 0px black",
                            "box-shadow": "0px 0px 4px 0px black"
                        }
                    },
                    // BEHAVIORS
                    resizeBehavior: "NoResize",
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
                    }
                });
                customShape.attachListeners();
                break;
            case "big_shape":
                customShape = new CustomShape({
                    width: 200,
                    height: 200,
                    canvas : this,
                    container: 'regular',
                    "connectAtMiddlePoints": false,
                    drop : {
                        type : "connectioncontainer",
                        selectors: ['.custom_shape']
                    },
                    "style": {
                        cssClasses: ["grey"],
                        cssProperties: {
                            border: "1px solid green",
                            "-webkit-box-shadow": "0px 0px 4px 0px black",
                            "box-shadow": "0px 0px 4px 0px black"
                        }
                    },
                    // BEHAVIORS
                    resizeBehavior: "yes",
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
                    }
                });
                customShape.attachListeners();
                break;
            default:
        }

        return customShape;
    };

    /**
     * Identifies the family of the shape (which might be *"CustomShape"* or *"RegularShape"*)
     * and adds `shape` to either `this.customShapes` or `this.regularShapes`.
     * @param {Shape} shape
     * @chainable
     */
    Canvas.prototype.addToList = function (shape) {
        switch (shape.family) {
            case "CustomShape":
                if (!this.customShapes.contains(shape)) {
                    this.customShapes.insert(shape);
                }
                break;
            case "RegularShape":
                if (!this.regularShapes.contains(shape)) {
                    this.regularShapes.insert(shape);
                }
                break;
            default:
        }
        return this;
    };

    /**
     * Hides `this.currentConnection` if there is one.
     * @chainable
     */
    Canvas.prototype.hideCurrentConnection = function () {
        // hide the current connection if there was one
        if (this.currentConnection) {
            this.currentConnection.hidePortsAndHandlers();
            this.currentConnection = null;
        }
        return this;
    };

    /**
     * Applies a zoom scale to the canvas and all its elements
     * @param {number} scale numbered from 1 to n
     * @chainable
     */
    Canvas.prototype.applyZoom = function (scale) {
        // TODO Implement Zoom Constants in utils
        var i,
            shape;
        if (scale > 0) {
            scale -= 1;
            this.prevZoom = this.zoomPropertiesIndex;
            this.zoomPropertiesIndex = scale;
            this.zoomFactor = (scale * 25 + 50) / 100;
        }

        for (i = 0; i < this.customShapes.getSize(); i += 1) {
            shape = this.customShapes.get(i);
            shape.applyZoom();
            shape.paint();
        }

        for (i = 0; i < this.regularShapes.getSize(); i += 1) {
            shape = this.regularShapes.get(i);
            shape.applyZoom();
            shape.paint();
        }

        return this;
    };

    /**
     * Adds a connection to the canvas, appending its html to the DOM and inserting
     * it in the list of connections
     * @param {Connection} conn
     * @chainable
     */
    Canvas.prototype.addConnection = function (conn) {
        this.html.appendChild(conn.getHTML());
        this.connections.insert(conn);
        this.updatedElement = conn;
//    $(this.html).trigger("createelement");
        return this;
    };

    /**
     * Remove all selected elements, it destroy the shapes and all references to them.
     * @chainable
     */
    Canvas.prototype.removeElements = function () {
        // destroy the shapes (also destroy all the references to them)
        var shape,
            command;

        command = new CommandDelete(this);
        this.commandStack.add(command);
        command.execute();

//    while (this.getCurrentSelection().getSize() > 0) {
//        shape = this.getCurrentSelection().getFirst();
//        shape.destroy();
//    }
//
//    // destroy the currentConnection (also destroy all the references to it)
//    if (this.currentConnection) {
//        this.currentConnection.destroy();
//        this.currentConnection = null;
//    }

//    DEBUG
//    console.log("CurrentSelection: " + canvas.getCurrentSelection().getSize());
//    console.log("CustomShapes: " + canvas.getCustomShapes().getSize());
//    console.log("RegularShapes: " + canvas.getRegularShapes().getSize());
//    console.log("Connections: " + canvas.getConnections().getSize());

        return this;
    };

    /**
     * Moves all the connections of the children of this shape (shape was moved using the cursors but the children
     * connections don't know that so move those connections), this method is called from #moveElements.
     * @param {Shape} shape
     * @chainable
     */
    Canvas.prototype.moveAllChildConnections = function (shape) {
        var i,
            child,
            j,
            port;
        if (shape.child !== null) {
            for (i = 0; i < shape.children.getSize(); i += 1) {
                child = shape.children.get(i);
                child.setPosition(child.x, child.y);
                for (j = 0; j < child.getPorts().getSize(); j += 1) {
                    port = child.getPorts().get(j);
                    port.setPosition(port.x, port.y);
                    port.connection.disconnect();
                    //alert('disconnected');
                    port.connection.connect();

                }

                this.moveAllChildConnections(child);
            }

        }
        return this;

    };

    /**
     * Move all selected elements in one direction, used mainly for keyboard events
     * @param {Canvas} canvas
     * @param {string} direction The direction to move the shapes to
     * @param {Function} [hook] Hook used to determine which shapes can be moved with the keyboard,
     * the function must receive a shape as its parameter and return true if the shape can be moved, false
     * otherwise (if this function is not defined then it's assumed that all shapes are valid
     * to be moved).
     *      // i.e.
     *      hook = function(shape) {
 *          return shape.isValidToMove();
 *      }
     *
     * @chainable
     */
    Canvas.prototype.moveElements = function (canvas, direction, hook) {
        var i, j,
            shape,
            hfactor = 0,
            vfactor = 0,
            port,
            currentSelection = [],
            canMove;
        switch (direction) {
            case 'LEFT':
                hfactor = -1;
                break;
            case 'RIGHT':
                hfactor = 1;
                break;
            case 'TOP':
                vfactor = -1;
                break;
            case 'BOTTOM':
                vfactor = 1;
                break;
        }

        for (i = 0; i < canvas.getCurrentSelection().getSize(); i += 1) {
            canMove = true;
            shape = canvas.getCurrentSelection().get(i);
            currentSelection.push(shape);

            if (hook && typeof hook === "function" && !hook(shape)) {
                canMove = false;
            }

            if (canMove) {
                shape.oldX = shape.x;
                shape.oldY = shape.y;
                shape.oldAbsoluteX = shape.absoluteX;
                shape.oldAbsoluteY = shape.absoluteY;

                shape.setPosition(shape.getX() + hfactor, shape.getY() + vfactor);
                shape.changePosition(shape.oldX, shape.oldY, shape.oldAbsoluteX,
                    shape.oldAbsoluteY);

                for (j = 0; j < shape.ports.getSize(); j += 1) {
                    //for each port update its absolute position and repaint its
                    // connection
                    port = shape.ports.get(j);

                    port.setPosition(port.x, port.y);
                    port.connection.disconnect().connect();
                    //            this.intersectionTimeout = null;
                }
                this.moveAllChildConnections(shape);
                shape.refreshConnections(false);
            }
        }

        clearTimeout(this.intersectionTimeout);
        this.intersectionTimeout = window.setTimeout(function (currentSelection) {
            var stack = [],
                selection = currentSelection || [];

            for (i = 0; i < selection.length; i += 1) {
                shape = selection[i];
                stack.push(shape);
            }
            while (stack.length > 0) {
                shape = stack.pop();
                // add the children to the stack
                for (i = 0; i < shape.getChildren().getSize(); i += 1) {
                    stack.push(shape.getChildren().get(i));
                }
                for (j = 0; j < shape.ports.getSize(); j += 1) {
                    //for each port update its absolute position and repaint its
                    // connection
                    port = shape.ports.get(j);
                    port.connection.disconnect().connect();
                    port.connection.setSegmentMoveHandlers();
                    port.connection.checkAndCreateIntersectionsWithAll();
                }
            }
        }, 1000, currentSelection);

        return this;
    };

    /**
     * Removes `shape` from the its corresponding list in the canvas (the shape has a reference either in
     * `this.customShapes` or `this.regularShapes`).
     * @param {Shape} shape
     * @chainable
     */
    Canvas.prototype.removeFromList = function (shape) {
        // remove from the current selection
        this.currentSelection.remove(shape);

        if (shape.family === "CustomShape") {
            this.customShapes.remove(shape);
        } else if (shape.family === "RegularShape") {
            this.regularShapes.remove(shape);
        }
        return this;
    };

///**
// * Fixes the data of the snappers recreating the arrays and sorting them,
// * this method is called from {@link RegularDragBehavior#onDragStart} (it might
// * be an overrided method `onDragStart` if the instance of {@link RegularDragBehavior} was changed).
// * @chainable
// */
//Canvas.prototype.fixSnapData = function () {
//    this.horizontalSnapper.createSnapData();
//    this.verticalSnapper.createSnapData();
//    this.horizontalSnapper.sortData();
//    this.verticalSnapper.sortData();
//    return this;
//};

    /**
     * Build the data of the snappers recreating the arrays,
     * this method is called from {@link RegularDragBehavior#onDragStart} (it might
     * be an overrided method `onDragStart` if the instance of {@link RegularDragBehavior} was changed).
     * @chainable
     */
    Canvas.prototype.startSnappers = function (event) {
        var shape, i, parent;
        this.guides = [];
        for (i = 0; i < this.customShapes.getSize(); i += 1) {
            shape = this.customShapes.get(i);
            if (!this.currentSelection.find('id', shape.getID())) {
                this.computeGuidesForElement(shape);
            }
        }
        return this;
    };


    Canvas.prototype.computeGuidesForElement = function (shape) {
        var x = shape.getAbsoluteX();
        var y = shape.getAbsoluteY();

        var w = shape.getZoomWidth() - 1;
        var h = shape.getZoomHeight() - 1;

        this.guides.push(
            { type: "h", x: x, y: y },
            { type: "h", x: x, y: y + h },
            { type: "v", x: x, y: y },
            { type: "v", x: x + w, y: y }
        );
        return this;

    };

    /**
     * Process the snappers according to this criteria and show and hide:
     *
     * - To show the vertical snapper
     *      - `shape.absoluteX` must equal a value in the data of `this.verticalSnapper`
     *      - `shape.absoluteX + shape.width` must equal a value in the data of `this.verticalSnapper`
     *
     * - To show the horizontal snapper
     *      - `shape.absoluteY` must equal a value in the data of `this.horizontalSnapper`
     *      - `shape.absoluteY + shape.height` must equal a value in the data of `this.horizontalSnapper`
     *
     * @param {Object} e
     * @parem {Object} ui
     * @param {Shape} customShape
     * @chainable
     */
    Canvas.prototype.processGuides = function (e, ui, customShape){
        // iterate all guides, remember the closest h and v guides
        var guideV,
            guideH,
            distV = this.MIN_DISTANCE + 1,
            distH = this.MIN_DISTANCE + 1,
            offsetV,
            offsetH,
            mouseRelX,
            mouseRelY,
            pos,
            w = customShape.getZoomWidth() - 1,
            h = customShape.getZoomHeight() - 1,
            d;

        mouseRelY = e.originalEvent.pageY - ui.offset.top;
        mouseRelX = e.originalEvent.pageX - ui.offset.left;
        pos = {
            top: e.originalEvent.pageY - customShape.canvas.getY() - mouseRelY
            + customShape.canvas.getTopScroll(),
            left: e.originalEvent.pageX - customShape.canvas.getX() - mouseRelX
            + customShape.canvas.getLeftScroll()
        };
        $.each(this.guides, function (i, guide) {
            if (guide.type === "h"){
                d = Math.abs(pos.top - guide.y);
                if (d < distH) {
                    distH = d;
                    guideH = guide;
                    offsetH = 0;
                }
                d = Math.abs(pos.top - guide.y + h);
                if (d < distH) {
                    distH = d;
                    guideH = guide;
                    offsetH = h;
                }
            }
            if (guide.type === "v") {
                d = Math.abs(pos.left - guide.x);
                if (d < distV) {
                    distV = d;
                    guideV = guide;
                    offsetV = 0;
                }
                d = Math.abs(pos.left - guide.x + w);
                if (d < distV) {
                    distV = d;
                    guideV = guide;
                    offsetV = w;
                }
            }
        });

        if (distH <= this.MIN_DISTANCE) {
            $("#guide-h").css("top", guideH.y).show();
            if (customShape.parent.family !== 'canvas') {
                ui.position.top = guideH.y - offsetH - customShape.parent.getAbsoluteY();
                customShape.setPosition(ui.helper.position().left / this.zoomFactor,
                    guideH.y - offsetH - customShape.parent.getAbsoluteY())
            } else {
                ui.position.top = guideH.y - offsetH;
                customShape.setPosition(ui.helper.position().left / this.zoomFactor,
                    guideH.y - offsetH)
            }
//        customShape.setPosition(ui.helper.position().left / this.zoomFactor,
//            guideH.y - offsetH);
        } else {
            $("#guide-h").hide();
            //ui.position.top = pos.top;
            //console.log(pos.top);
            //customShape.setPosition(this.getX(), pos.top);
        }

        if (distV <= this.MIN_DISTANCE) {
            $("#guide-v").css("left", guideV.x).show();
            if (customShape.parent.family !== 'canvas') {
                ui.position.left = guideV.x - offsetV - customShape.parent.getAbsoluteX();
                customShape.setPosition(
                    guideV.x - offsetV - customShape.parent.getAbsoluteX(),
                    ui.helper.position().top / this.zoomFactor
                );
            } else {
                ui.position.left = guideV.x - offsetV;
                customShape.setPosition(guideV.x - offsetV,
                    ui.helper.position().top / this.zoomFactor);
            }

        } else{
            $("#guide-v").hide();
            // customShape.setPosition(pos.left, this.getY());
            // ui.position.left = pos.left;
        }
        return this;
    };


///**
// * Shows or hides the snappers according to this criteria:
// *
// * - To show the vertical snapper
// *      - `shape.absoluteX` must equal a value in the data of `this.verticalSnapper`
// *      - `shape.absoluteX + shape.width` must equal a value in the data of `this.verticalSnapper`
// *
// * - To show the horizontal snapper
// *      - `shape.absoluteY` must equal a value in the data of `this.horizontalSnapper`
// *      - `shape.absoluteY + shape.height` must equal a value in the data of `this.horizontalSnapper`
// *
// * @param {Shape} shape
// * @chainable
// */
//Canvas.prototype.showOrHideSnappers = function (shape) {
//    var hSnapper = this.horizontalSnapper,
//        vSnapper = this.verticalSnapper,
//        x = shape.getAbsoluteX(),
//        y = shape.getAbsoluteY(),
//        width = shape.getZoomHeight(),
//        height = shape.getZoomHeight();
//
//    if (hSnapper.binarySearch(y)) {
//        hSnapper.setPosition(
//            this.getLeftScroll() / this.zoomFactor,
//            y / this.zoomFactor
//        );
//        hSnapper.show();
//    } else if (hSnapper.binarySearch(y + height)) {
//        hSnapper.setPosition(
//            this.getLeftScroll() / this.zoomFactor,
//            (y + height) / this.zoomFactor
//        );
//        hSnapper.show();
//    } else {
//        hSnapper.hide();
//    }
//
//    if (vSnapper.binarySearch(x)) {
//        vSnapper.setPosition(
//            x / this.zoomFactor,
//            this.getTopScroll() / this.zoomFactor
//        );
//        vSnapper.show();
//    } else if (vSnapper.binarySearch(x + width)) {
//        vSnapper.setPosition(
//            (x + width) / this.zoomFactor,
//            this.getTopScroll() / this.zoomFactor
//        );
//        vSnapper.show();
//    } else {
//        vSnapper.hide();
//    }
//    return this;
//};


    /**
     * Empties `this.currentSelection` arrayList, thus hiding the resize handlers
     * of each shape that was in it, it also clears `this.sharedConnections` array
     * (there's a direct relationship between them).
     * @chainable
     */
    Canvas.prototype.emptyCurrentSelection = function () {
        var i,
            shape;
        while (this.currentSelection.getSize() > 0) {
            shape = this.currentSelection.get(0);
            this.removeFromSelection(shape);
        }

        // also clear the sharedConnections
        this.sharedConnections.clear();

        return this;
    };

    /**
     * Determines if it's possible to select `newShape` using `referenceShape` as a reference (`newShape` is a valid
     * shape to be added to the selection if it has the same parent as `referenceShape`).
     * @param {Shape} referenceShape shape which parent will be taken as reference
     * @param {Shape} newShape new selected shape
     * @return {boolean}
     */
    Canvas.prototype.isValidSelection = function (referenceShape, newShape) {
        if (referenceShape.parent === null) {
            return newShape.parent === null;
        }
        if (newShape.parent === null) {
            return false;
        }
        return newShape.parent.id === referenceShape.parent.id;
    };

    /**
     * Adds `shape` to `this.currentSelection` if it meets one of the following rules:
     *
     * - If `this.currentSelection` is empty then add it to the arrayList
     * - If `this.currentSelection` is not empty then check if this candidate shape
     *      has the same parent as any element in `this.currentSelection`, if so then add it to
     *      the arrayList.
     *
     * This method also shows the resize handlers of the shape and adds its connections
     * to `this.sharedConnections` if possible.
     * @param {Shape} shape
     * @chainable
     */
    Canvas.prototype.addToSelection = function (shape) {
        var currentSelection = this.currentSelection,
            firstSelected,
            valid,
            isEmpty = currentSelection.isEmpty();
        if (!isEmpty) {
            firstSelected = currentSelection.get(0);
            valid = this.isValidSelection(firstSelected, shape);
        } else {
            valid = true;
        }
        if (!currentSelection.contains(shape) && valid) {
            // increase this shape zIndex
            shape.increaseZIndex();
            currentSelection.insert(shape);

            // add the connections from this shape that are connected
            // to another shape in the currentSelection to the
            // canvas sharedConnections array
            // NOTE: the shape is passed as an argument but its
            // connections are stored
            if (shape.family === "CustomShape") {
                this.addToSharedConnections(shape);
            }

//        console.log("currentSelection: " + this.currentSelection.getSize());
//        console.log("shared connections: " + this.sharedConnections.getSize());
            shape.selected = true;
            shape.showOrHideResizeHandlers(true);
        }
        return this;
    };

    /**
     * Removes `shape` from `this.currentSelection` (also hiding its resize handlers).
     * @param {Shape} shape
     * @chainable
     */
    Canvas.prototype.removeFromSelection = function (shape) {
        shape.decreaseZIndex();
        this.removeFromSharedConnections(shape);
        this.currentSelection.remove(shape);
        shape.selected = false;
        shape.showOrHideResizeHandlers(false);
        return this;
    };

    /**
     * Removes all the shared connections between `customShape` and every shape
     * found in `this.currentSelection`, also the connections inside `customShape` are removed from
     * `this.sharedConnections` array.
     * @param {CustomShape} customShape
     * @chainable
     */
    Canvas.prototype.removeFromSharedConnections = function (customShape) {
        var i,
            child,
            connection,
            sharedConnections = this.sharedConnections;

        for (i = 0; i < customShape.getChildren().getSize(); i += 1) {
            child = customShape.getChildren().get(i);
            this.removeFromSharedConnections(child);
        }

        if (customShape.ports) {
            for (i = 0; i < customShape.ports.getSize(); i += 1) {
                connection = customShape.ports.get(i).connection;
                if (sharedConnections.find('id', connection.getID())) {
                    this.sharedConnections.remove(connection);
                }
            }
        }
        return this;
    };

    /**
     * Checks if an ancestor of `shape` is in `this.currentSelection`.
     * @return {boolean}
     */
    Canvas.prototype.findAncestorInCurrentSelection = function (shape) {
        if (this.currentSelection.find('id', shape.getID())) {
            return true;
        }
        if (!shape.parent) {
            return false;
        }
        return this.findAncestorInCurrentSelection(shape.parent);
    };

    /**
     * Adds all the connections between `customShape` and another shape in the
     * currentSelection to the `sharedConnections` arrayList, also the connections inside
     * `customShape` are added to `this.sharedConnections` array.
     * @param {CustomShape} customShape
     * @chainable
     */
    Canvas.prototype.addToSharedConnections = function (customShape) {
        var i,
            child,
            connection,
            sourceShape,
            destShape,
            sharedConnections = this.sharedConnections;

        for (i = 0; i < customShape.getChildren().getSize(); i += 1) {
            child = customShape.getChildren().get(i);
            this.addToSharedConnections(child);
        }

        if (customShape.ports) {
            for (i = 0; i < customShape.ports.getSize(); i += 1) {
                connection = customShape.ports.get(i).connection;
                sourceShape = connection.srcPort.parent;
                destShape = connection.destPort.parent;
//            console.log(sourceShape);
//            console.log(destShape);

                if (this.findAncestorInCurrentSelection(sourceShape) &&
                    this.findAncestorInCurrentSelection(destShape) &&
                    !sharedConnections.find('id', connection.getID())) {
                    sharedConnections.insert(connection);
                }
            }
        }
        return this;
    };

    /**
     * Removes a connection from `this.connections`.
     * @param {Connection} conn
     * @chainable
     */
    Canvas.prototype.removeConnection = function (conn) {
        //this.currentSelection.remove(conn);
        this.connections.remove(conn);
        return this;
    };

    /**
     * Attaches event listeners to this canvas, it also creates some custom triggers
     * used to save the data (to send it to the database later).
     *
     * The events attached to this canvas are:
     *
     * - {@link Canvas#event-mousedown Mouse down event}
     * - {@link Canvas#event-mousemove Mouse move event}
     * - {@link Canvas#event-mouseup Mouse up event}
     * - {@link Canvas#event-click Click event}
     * - {@link Canvas#event-scroll Scroll event}
     *
     * The custom events are:
     *
     * - {@link Canvas#event-createelement Create element event}
     * - {@link Canvas#event-removeelement Remove element event}
     * - {@link Canvas#event-changeelement Change element event}
     * - {@link Canvas#event-selectelement Select element event}
     * - {@link Canvas#event-rightclick Right click event}
     *
     * This method also initializes jQueryUI's droppable plugin (instantiated as `this.dropBehavior`)
     * @chainable
     */
    Canvas.prototype.attachListeners = function () {
        var $canvas = $(this.html).click(this.onClick(this)),
            $canvasContainer = $canvas.parent();

        $canvas.mousedown(this.onMouseDown(this));
        $canvasContainer.scroll(this.onScroll(this, $canvasContainer));
        $canvas.mousemove(this.onMouseMove(this));
        $canvas.mouseup(this.onMouseUp(this));
        $canvas.on("createelement", this.onCreateElement(this));
        $canvas.on("removeelement", this.onRemoveElement(this));
        $canvas.on("changeelement", this.onChangeElement(this));
        $canvas.on("selectelement", this.onSelectElement(this));
        $canvas.on("rightclick", this.onRightClick(this));
        $canvas.on("contextmenu", function (e) {
            e.preventDefault();
        });
        /* Activate Keyboard Events */
        $(document).on('keydown', onCanvasKeyDown)
            .on('keyup', onCanvasKeyUp);

        this.updateBehaviors();
        return this;
    };

    /**
     * This is a hook that will be executed after an element has been created in
     * the canvas.
     * This hook will be executed every time a shape, a connection, or an
     * independent label is created.
     * @param {Object} updatedElement
     * @param {string} [updatedElement.id] ID of the updated element
     * @param {string} [updatedElement.type] Type of the updated element
     * @param {Shape} [updatedElement.relatedObject] The updated element
     * @param {Array} [updatedElement.relatedElements] An array with all the other elements created
     *  i.e. When executing {@link CommandDelete#undo CommandDelete.undo()}, multiple elements are created
     *  at once, so this property will contain all those shapes.
     * @template
     * @protected
     */
    Canvas.prototype.onCreateElementHandler = function (updatedElement) {
    };

    /**
     * @event createelement
     * Handler for the custom event createelement, this event fires when an element
     * has been created. It executes the hook #onCreateElementHandler
     * @param {Canvas} canvas
     */
    Canvas.prototype.onCreateElement = function (canvas) {
        return function (e, ui) {
            canvas.onCreateElementHandler(canvas.updatedElement);
        };
    };

    /**
     * This is a hook that will be executed after an element has been deleted in
     * the canvas.
     * This hook will be executed every time a shape, a connection, or an
     * independent label is deleted
     * @param {Object} updatedElement
     * @param {string} [updatedElement.id] ID of the removed element
     * @param {string} [updatedElement.type] Type of the removed element
     * @param {Shape} [updatedElement.relatedObject] The removed element
     * @param {Array} [updatedElement.relatedElements] An array with all the other elements removed
     *  i.e. When executing {@link CommandDelete#execute CommandDelete.execute()}, multiple elements are created
     *  at once, so this property will contain all those shapes.
     * @template
     * @protected
     */
    Canvas.prototype.onRemoveElementHandler = function (updatedElement) {
        return true;
    };

    /**
     * @event removeelement
     * Handler for the custom event removeelement, this event fires when an element
     * has been deleted. It executes the hook #onRemoveElementHandler
     * @param {Canvas} canvas
     */
    Canvas.prototype.onRemoveElement = function (canvas) {
        return function (e, ui) {
            canvas.onRemoveElementHandler(canvas.updatedElement.relatedElements);
        };
    };
    /**
     * This is a hook that will be executed after an element has been changed in
     * the canvas.
     * This hook will be executed every time a shape, a connection, or an
     * independent label is changed.
     * `arguments[0]` is an array with all the elements that were updated,
     * the structure of each element of the array is described below:
     *
     *      {
 *          id: #,      // the id of the updated element
 *          type: #     // the type of the updated element
 *          fields: [
 *              {
 *                  field: #        // the field that was updated in this element
 *                  oldVal: #       // the old value of this shape
 *                  newVal: #       // the new value of this shape
 *              },
 *              ...
 *          ]
 *      }
     *
     * @param {Array} updatedElements Array with all the elements that were updated.
     * @template
     * @protected
     */
    Canvas.prototype.onChangeElementHandler = function (updatedElements) {
    };

    /**
     * @event changeelement
     * Handler for the custom event changeeelement, this event fires when an element
     * has been changed. It executes the hook #onChangeElementHandler
     * @param {Canvas} canvas
     */
    Canvas.prototype.onChangeElement = function (canvas) {
        return function (e, ui) {
            canvas.onChangeElementHandler(canvas.updatedElement);
        };
    };

    /**
     * This is a hook that will be executed after an element has been selected in
     * the canvas.
     * This hook will be executed every time a shape, a connection, or an
     * independent label is selected
     * `arguments[0]` is an array with all the elements that were selected,
     * the structure of each element of the array is described below:
     *
     *      {
 *          id: #,              // the id of the selected element
 *          type: #             // the type of the selected element
 *          relatedObject       // the selected element
 *      }
     * @param {Array} updatedElements Array with the selected elements
     * @protected
     * @template
     */
    Canvas.prototype.onSelectElementHandler = function (updatedElements) {
    };

    /**
     * @event selectelement
     * Handler for the custom event selectelement, this event fires when an element
     * has been selected. It executes the hook #onSelectElementHandler
     * @param {Canvas} canvas
     */
    Canvas.prototype.onSelectElement = function (canvas) {
        return function (e, ui) {
            canvas.onSelectElementHandler(canvas.updatedElement);
        };
    };

    /**
     * This is a hook that will be executed after an element has been right clicked
     * in the canvas or the canvas's been right clicked itself.
     * This hook will be executed every time a shape, a connection, an
     * independent label or the canvas is right clicked
     * @param {Object} updatedElement Reference to the last element that was
     * right clicked in the canvas
     * @param {number} x x coordinate where the mouse was pressed
     * @param {number} y y coordinate where the mouse was pressed
     * @template
     * @protected
     */
    Canvas.prototype.onRightClickHandler = function (updatedElement, x, y) {
    };

    /**
     * @event rightclick
     * Handler for the custom event rightclick, this event fires when an element
     * has been right clicked. It executes the hook #onRightClickHandler
     * @param {Canvas} canvas
     */
    Canvas.prototype.onRightClick = function (canvas) {
        return function (event, e, element) {
            var x = e.pageX - canvas.x + canvas.leftScroll,
                y = e.pageY - canvas.y + canvas.topScroll;
            canvas.updatedElement = element;
            canvas.onRightClickHandler(canvas.updatedElement, x, y);
        };
    };

    /**
     * @event click
     * Click event handler, which makes `this.currentLabel` lose its focus.
     * @param {Canvas} canvas
     */
    Canvas.prototype.onClick = function (canvas) {
        return function (e, ui) {
            var currentLabel = canvas.currentLabel;
            //console.log('current:'+ current);

            if (currentLabel) {
                currentLabel.loseFocus();
                $(currentLabel.textField).focusout();
            }
            if (listPanelError){
                if (listPanelError.selectedItem){
                    listPanelError.selectedItem.deselect();
                }
            }
        };
    };

    /**
     * @event mousedown
     * MouseDown Handler of the canvas. It does the following:
     *
     * - Trigger the {@link Canvas#event-rightclick Right Click event} if it detects a right click
     * - Empties `canvas.currentSelection`
     * - Hides `canvas.currentConnection` if there's one
     * - Resets the position of `canvas.multipleSelectionContainer` making it visible and setting its
     *      `[x, y]` to the point where the user did mouse down in the `canvas`.
     *
     * @param {Canvas} canvas
     */
    Canvas.prototype.onMouseDown = function (canvas) {
        return function (e, ui) {

            var x = e.pageX - canvas.getX() + canvas.getLeftScroll(),
                y = e.pageY - canvas.getY() + canvas.getTopScroll();
            e.preventDefault();

            if (e.which === 3) {
                canvas.rightClick = true;
                $(canvas.html).trigger("rightclick", [e, canvas]);
            }

            canvas.isMouseDown = true;
            canvas.isMouseDownAndMove = false;

            // do not create the rectangle selection if a segment handler
            // is being dragged
            if (canvas.draggingASegmentHandler) {
                return;
            }

            // clear old selection
            canvas.emptyCurrentSelection();

            // hide the currentConnection if there's one
            canvas.hideCurrentConnection();

            canvas.multipleSelectionHelper.reset();
            canvas.multipleSelectionHelper.setPosition(x / canvas.zoomFactor,
                y / canvas.zoomFactor);
            canvas.multipleSelectionHelper.oldX = x;
            canvas.multipleSelectionHelper.oldY = y;
            canvas.multipleSelectionHelper.setVisible(true);
            canvas.multipleSelectionHelper.changeOpacity(0.2);
//        console.log("canvas down");

        };
    };

    /**
     * @event mousemove
     * MouseMove handler of the canvas, it does the following:
     *
     * - Updates the position and dimension of `canvas.multipleSelectionContainer`
     *
     * @param {Canvas} canvas
     */
    Canvas.prototype.onMouseMove = function (canvas) {
        return function (e, ui) {
            if (canvas.isMouseDown && !canvas.rightClick) {
                canvas.isMouseDownAndMove = true;
                var x = e.pageX - canvas.getX() + canvas.getLeftScroll(),
                    y = e.pageY - canvas.getY() + canvas.getTopScroll(),
                    topLeftX,
                    topLeftY,
                    bottomRightX,
                    bottomRightY;

                topLeftX = Math.min(x, canvas.multipleSelectionHelper.oldX);
                topLeftY = Math.min(y, canvas.multipleSelectionHelper.oldY);
                bottomRightX = Math.max(x, canvas.multipleSelectionHelper.oldX);
                bottomRightY = Math.max(y, canvas.multipleSelectionHelper.oldY);

                canvas.multipleSelectionHelper.setPosition(
                    topLeftX / canvas.zoomFactor,
                    topLeftY / canvas.zoomFactor
                );
                canvas.multipleSelectionHelper.setDimension(
                    (bottomRightX - topLeftX) / canvas.zoomFactor,
                    (bottomRightY - topLeftY) / canvas.zoomFactor
                );

            }

//        console.log("canvas move");
        };
    };

    /**
     * click handler
     * MouseClick handler of the canvas. It does the following:
     *
     * @param {Canvas} canvas
     * @param {Number} x
     * @param {Number} y
     */
    Canvas.prototype.onClickHandler = function (canvas, x, y) {

    };
    /**
     * @event mouseup
     * MouseUp handler of the canvas. It does the following:
     *
     * - Wraps the elements that are inside `canvas.multipleSelectionContainer`
     * - Resets the state of `canvas.multipleSelectionContainer` (see {@link MultipleSelectionContainer#reset})
     *
     * @param {Canvas} canvas
     */
    Canvas.prototype.onMouseUp = function (canvas) {
        return function (e, ui) {
            if (canvas.isMouseDownAndMove) {
                var x = e.pageX - canvas.getX() + canvas.getLeftScroll(),
                    y = e.pageY - canvas.getY() + canvas.getTopScroll();
                canvas.multipleSelectionHelper.setPosition(
                    Math.min(x, canvas.multipleSelectionHelper.zoomX) / canvas.zoomFactor,
                    Math.min(y, canvas.multipleSelectionHelper.zoomY) / canvas.zoomFactor
                );

                if (canvas.multipleSelectionHelper) {
                    canvas.multipleSelectionHelper.wrapElements();
                }
            } else {
                if (canvas.isMouseDown && !canvas.rightClick) {
                    canvas.onClickHandler(canvas, x, y);
                }
                //canvas.setCurrentShape(null);
                //hideSelectedPorts(canvas);

                if (!canvas.multipleSelectionHelper.wasDragged) {
                    canvas.multipleSelectionHelper.reset().setVisible(false);
                }
            }
            canvas.isMouseDown = false;
            canvas.isMouseDownAndMove = false;
            canvas.rightClick = false;

//        console.log("canvas up");
        };
    };

    /**
     * @event scroll
     * Handler for scrolling, sets the scroll values to the canvas
     * @param {Canvas} canvas
     * @param {Object} $canvasContainer jQuery element that is the container of the `canvas`
     */
    Canvas.prototype.onScroll = function (canvas, $canvasContainer) {
        return function (e, ui) {
            canvas.setLeftScroll($canvasContainer.scrollLeft())
                .setTopScroll($canvasContainer.scrollTop());
        };
    };

    /**
     * Fires the {@link Canvas#event-selectelement} event, and elaborates the structure of the object that will
     * be passed to the handlers.
     * @param {Array} selection The `currentSelection` ArrayList of some canvas
     * @chainable
     */
    Canvas.prototype.triggerSelectEvent = function (selection) {
        var i,
            elements = [],
            current;
        for (i = 0; i < selection.length; i += 1) {
            current = selection[i];
            elements.push({
                id : current.id,
                type : current.type,
                relatedObject : current
            });
        }
        this.updatedElement = elements;
        $(this.html).trigger('selectelement');
        return this;
    };

    /**
     * Fires the {@link Canvas#event-rightclick} event and elaborates the structure
     * of the object that will be passed to the event.
     * @param {CustomShape} element The object that's been right clicked on.
     * @chainable
     */
    Canvas.prototype.triggerRightClickEvent = function (element) {
        this.updatedElement = {
            id : element.id,
            type : element.type,
            relatedObject : element
        };
        $(this.html).trigger('rightclick');
        return this;
    };

    /**
     * Fires the {@link Canvas#event-createelement} event, and elaborates the structure of the object that will
     * be passed to the handlers.
     * @param {Object} shape The shape created
     * @param {Array} relatedElements The array with the other elements created
     * @chainable
     */
    Canvas.prototype.triggerCreateEvent = function (shape, relatedElements) {
        this.updatedElement = {
            id : (shape && shape.id) || null,
            type : (shape && shape.type) || null,
            relatedObject : shape,
            relatedElements: relatedElements

        };
        $(this.html).trigger('createelement');
        return this;
    };

    /**
     * Fires the {@link Canvas#event-removeelement} event, and elaborates the structure of the object that will
     * be passed to the handlers.
     * @param {CustomShape} shape The shape created
     * @param {Array} relatedElements The array with the other elements created
     * @chainable
     */
    Canvas.prototype.triggerRemoveEvent = function (shape, relatedElements) {
        this.updatedElement = {
            id : (shape && shape.id) || null,
            type : (shape && shape.type) || null,
            relatedObject: shape,
            relatedElements : relatedElements
        };
        $(this.html).trigger('removeelement');
        return this;
    };

    /**
     * Fires the {@link Canvas#event-changeelement} event, and elaborates the structure of the object that will
     * be passed to the handlers, the structure contains the following fields (considering old values and new values):
     *
     * - width
     * - height
     *
     * @param {CustomShape} shape The shape that updated its dimension
     * @param {number} oldWidth The old width of `shape`
     * @param {number} oldHeight The old height of `shape`
     * @param {number} newWidth The new width of `shape`
     * @param {number} newHeight The old height of `shape`
     * @chainable
     */
    Canvas.prototype.triggerDimensionChangeEvent = function (shape, oldWidth,
                                                             oldHeight, newWidth, newHeight) {
        this.updatedElement = [{
            id : shape.id,
            type : shape.type,
            fields : [
                {
                    field : "width",
                    oldVal : oldWidth,
                    newVal : newWidth
                },
                {
                    field : "height",
                    oldVal : oldHeight,
                    newVal : newHeight
                }
            ],
            relatedObject: shape
        }];
        $(this.html).trigger('changeelement');
        return this;
    };

    /**
     * Fires the {@link Canvas#event-changeelement} event, and elaborates the structure of the object that will
     * be passed to the handlers, the structure contains the following fields (considering old values and new values):
     *
     * - x
     * - y
     * - parent (the shape that is parent of this shape)
     * - state (of the connection)
     *
     * @param {Port} port The port updated
     * @chainable
     */
    Canvas.prototype.triggerPortChangeEvent = function (port) {
        this.updatedElement = [{
            id: port.getID(),
            type: port.type,
            fields: [
                {
                    field: 'x',
                    oldVal: port.getOldX(),
                    newVal: port.getX()
                },
                {
                    field: 'y',
                    oldVal: port.getOldY(),
                    newVal: port.getY()
                },
                {
                    field: 'parent',
                    oldVal: port.getOldParent().getID(),
                    newVal: port.getParent().getID()
                },
                {
                    field: 'state',
                    oldVal: port.connection.getOldPoints(),
                    newVal: port.connection.savePoints() &&
                    port.connection.getPoints()
                }
            ],
            relatedObject: port
        }];

        $(this.html).trigger('changeelement');
        return this;
    };

    /**
     * Fires the {@link Canvas#event-changeelement} event, and elaborates the structure of the object that will
     * be passed to the handlers, the structure contains the following fields (considering old values and new values):
     *
     * - state (of the connection)
     *
     * @param {Connection} connection The connection updated
     * @chainable
     */
    Canvas.prototype.triggerConnectionStateChangeEvent = function (connection) {
        var points = [],
            point,
            i;
        connection.savePoints();
        for (i = 0; i < connection.points.length; i += 1) {
            point = connection.points[i];
            points.push(new Point(point.x / this.zoomFactor, point.y / this.zoomFactor));
        }
        this.updatedElement = [{
            id: connection.getID(),
            type: connection.type,
            fields: [
                {
                    field: 'state',
                    oldVal: connection.getOldPoints(),
                    newVal: points
                }
            ],
            relatedObject: connection
        }];

        //console.log('connection state change!');
        $(this.html).trigger('changeelement');
        return this;
    };

    /**
     * Fires the {@link Canvas#event-changeelement} event, and elaborates the structure of the object that will
     * be passed to the handlers, the structure contains the following fields (considering old values and new values):
     *
     * - x
     * - y
     *
     * @param {Array} shapes The shapes that were updated
     * @param {Array} before The state of the shapes before they were repositioned
     * @param {Array} after The state of the shapes after they were repositioned
     * @chainable
     */
    Canvas.prototype.triggerPositionChangeEvent = function (shapes, before, after) {
        var i,
            elements = [];
        for (i = 0; i < shapes.length; i += 1) {
            elements.push({
                id : shapes[i].getID(),
                type : shapes[i].type,
                fields : [
                    {
                        field : "x",
                        oldVal : before[i].x,
                        newVal : after[i].x
                    },
                    {
                        field : "y",
                        oldVal : before[i].y,
                        newVal : after[i].y
                    }
                ],
                relatedObject : shapes[i]
            });
        }

        this.updatedElement = elements;
        $(this.html).trigger('changeelement');
        return this;
    };

    /**
     * Fires the {@link Canvas#event-changeelement} event, and elaborates the structure of the object that will
     * be passed to the handlers, the structure contains the following fields (considering old values and new values):
     *
     * - message
     *
     * @param {CustomShape} element The shape that updated one of ots labels
     * @param {string} oldText The old text of the label
     * @param {string} newText The new text of the label
     * @chainable
     */
    Canvas.prototype.triggerTextChangeEvent = function (element, oldText, newText) {
        this.updatedElement = [{
            id : element.id,
            type : element.type,
            parent : element.parent,
            fields : [
                {
                    field : "message",
                    oldVal : oldText,
                    newVal : newText
                }
            ],
            relatedObject: element
        }];
        $(this.html).trigger('changeelement');
        return this;
    };

    /**
     * Fires the {@link Canvas#event-changeelement} event, and elaborates the structure of the object that will
     * be passed to the handlers, the structure contains the following fields (considering old values and new values):
     *
     * - parent
     * - x
     * - y
     *
     * @param {Array} shapes The shapes that were updated
     * @param {Array} before The state of the shapes before they were repositioned
     * @param {Array} after The state of the shapes after they were repositioned
     * @chainable
     */
    Canvas.prototype.triggerParentChangeEvent = function (shapes, before, after) {

        var i,
            elements = [];
        for (i = 0; i < shapes.length; i += 1) {
            elements.push({
                id : shapes[i].getID(),
                type : shapes[i].type,
                fields : [
                    {
                        field : "parent",
                        oldParent: before[i].parent,
                        newVal : after[i].parent

                    },
                    {
                        field : "x",
                        oldVal : before[i].x,
                        newVal : after[i].x
                    },
                    {
                        field : "y",
                        oldVal : before[i].y,
                        newVal : after[i].y
                    }
                ],
                relatedObject : shapes[i]
            });
        }

        this.updatedElement = elements;
        $(this.html).trigger('changeelement');
        return this;
    };

    /**
     * Sets the top scroll of this canvas.
     * @param {number} newScroll
     * @chainable
     */
    Canvas.prototype.setTopScroll = function (newScroll) {
        this.topScroll = newScroll;
        return this;
    };
    /**
     * Sets the left scroll of this canvas.
     * @param {number} newScroll
     * @chainable
     */
    Canvas.prototype.setLeftScroll = function (newScroll) {
        this.leftScroll = newScroll;
        return this;
    };
    /**
     * Sets the zoom Factor applied in the canvas
     * @param {number} newZoom
     * @chainable
     */
    Canvas.prototype.setZoomFactor = function (newZoom) {
        if (typeof newZoom === "number" && newZoom % 25 === 0 && newZoom > 0) {
            this.zoomFactor = newZoom;
        }
        return this;
    };
    /**
     * Sets the currentConnection of this canvas.
     * @param {Connection} newConnection
     * @chainable
     */
    Canvas.prototype.setCurrentConnection = function (newConnection) {
        if (newConnection.type === "Connection") {
            this.currentConnection = newConnection;
        }
        return this;
    };

    /**
     * Assigns `newFunction` as `Canvas.prototype.toolbarShapeFactory` so that
     * the canvas has a reference to the shapes that will be created when they
     * are dragged from the toolbar.
     * @param {Function} newFunction
     * @chainable
     */
    Canvas.prototype.setToolBarShapeFactory = function (newFunction) {
        Canvas.prototype.toolBarShapeFactory = newFunction;
        return this;
    };

    /**
     * Gets the current zoom factor applied in the canvas
     * @return {number}
     */
    Canvas.prototype.getZoomFactor = function () {
        return this.zoomFactor;
    };

    /**
     * Gets the index where the zoom properties are located for the current
     * zoom factor.
     * @return {number}
     */
    Canvas.prototype.getZoomPropertiesIndex = function () {
        return this.zoomPropertiesIndex;
    };

    /**
     * Gets the segment used to make connections in the canvas.
     * @return {Segment}
     */
    Canvas.prototype.getConnectionSegment = function () {
        return this.connectionSegment;
    };

    /**
     * Gets the left scroll position of the canvas.
     * @return {number}
     */
    Canvas.prototype.getLeftScroll = function () {
        return this.leftScroll;
    };

    /**
     * Gets the top scroll position of the canvas.
     * @return {number}
     */
    Canvas.prototype.getTopScroll = function () {
        return this.topScroll;
    };

    /**
     * Gets the current connection stored in this canvas.
     * @return {Connection}
     */
    Canvas.prototype.getCurrentConnection = function () {
        return this.currentConnection;
    };

    /**
     * Gets the current selection of this canvas.
     * @return {ArrayList}
     */
    Canvas.prototype.getCurrentSelection = function () {
        return this.currentSelection;
    };

    /**
     * Gets all the connections of this canvas.
     * @return {ArrayList}
     */
    Canvas.prototype.getConnections = function () {
        return this.connections;
    };

    /**
     * Gets all the shared connections stored in this canvas.
     * @return {ArrayList}
     */
    Canvas.prototype.getSharedConnections = function () {
        return this.sharedConnections;
    };

    /**
     * Gets all the custom shapes of the canvas.
     * @return {ArrayList}
     */
    Canvas.prototype.getCustomShapes = function () {
        return this.customShapes;
    };

    /**
     * Gets all the regular shapes of the canvas.
     * @return {ArrayList}
     */
    Canvas.prototype.getRegularShapes = function () {
        return this.regularShapes;
    };
    /**
     * Gets the multiple selection container instance.
     * @return {MultipleSelectionContainer}
     */
    Canvas.prototype.getMultipleSelectionHelper = function () {
        return this.multipleSelectionHelper;
    };

    /**
     * Gets the horizontal snapper of this canvas.
     * @return {Snapper}
     */
    Canvas.prototype.getHorizontalSnapper = function () {
        return this.horizontalSnapper;
    };

    /**
     * Gets the vertical snapper of this canvas.
     * @return {Snapper}
     */
    Canvas.prototype.getVerticalSnapper = function () {
        return this.verticalSnapper;
    };

    /**
     * Gets the last updated element in the canvas
     * @return {Mixed}
     */
    Canvas.prototype.getUpdatedElement = function () {
        return this.updatedElement;
    };

    /**
     * Any instance of the class Canvas is not resizable so this method
     * will always return false.
     * @return {boolean}
     */
    Canvas.prototype.isResizable = function () {
        return false;
    };

    /**
     * Gets a reference to itself.
     * @return {Canvas}
     */
    Canvas.prototype.getCanvas = function () {
        return this;
    };

    /**
     * Undoes the last action in the canvas by calling `this.commandStack.undo`.
     * @chainable
     */
    Canvas.prototype.undo = function () {
        this.commandStack.undo();
        return this;
    };

    /**
     * Redoes the last action in the canvas by calling `this.commandStack.redo`.
     * @chainable
     */
    Canvas.prototype.redo = function () {
        this.commandStack.redo();
        return this;
    };

    /**
     * Serializes this canvas by serializing its customShapes, regularShapes and connections.
     * @return {Object}
     * @return {Object} return.customShapes See {@link CustomShape#stringify}
     * @return {Object} return.regularShapes See {@link Shape#stringify}
     * @return {Object} return.connections See {@link Connection#stringify}
     */
    Canvas.prototype.stringify = function () {

        var i,
            customShapes = [],
            regularShapes = [],
            connections = [],
            inheritedJSON,
            thisJSON;

        // serialize custom shapes
        for (i = 0; i < this.customShapes.getSize(); i += 1) {
            customShapes.push(this.customShapes.get(i).stringify());
        }

        // serialize regular shapes
        for (i = 0; i < this.regularShapes.getSize(); i += 1) {
            regularShapes.push(this.regularShapes.get(i).stringify());
        }

        // serialize connections shapes
        for (i = 0; i < this.connections.getSize(); i += 1) {
            connections.push(this.connections.get(i).stringify());
        }

        inheritedJSON = Shape.prototype.stringify.call(this);
        thisJSON = {
            customShapes: customShapes,
            regularShapes: regularShapes,
            connections: connections
        };
        $.extend(true, inheritedJSON, thisJSON);
        return inheritedJSON;
    };

    /**
     * Adds shape and its children to `this.shapesToCopy` array so that later
     * they can be pasted in the canvas.
     * @param {Shape} shape
     * @chainable
     */
    Canvas.prototype.addToShapesToCopy = function (shape) {
        var i,
            child;
        this.shapesToCopy.push(shape.stringify());
        for (i = 0; i < shape.getChildren().getSize(); i += 1) {
            child = shape.getChildren().get(i);
            this.addToShapesToCopy(child);
        }
        return this;
    };

    /**
     * Duplicates the `this.sharedConnection` array and the shapes stored in `this.currentSelection`
     * array (saving them in `this.shapesToCopy` and `this.connectionsToCopy` respectively) so
     * that they can be pasted later in the canvas.
     * @chainable
     */
    Canvas.prototype.copy = function () {
        var i,
            shape,
            connection;

        // duplicate shapes
        this.shapesToCopy = [];
        for (i = 0; i < this.getCurrentSelection().getSize(); i += 1) {
            shape = this.getCurrentSelection().get(i);
            this.addToShapesToCopy(shape);
        }

        // duplicate connections
        this.connectionsToCopy = [];
        for (i = 0; i < this.getSharedConnections().getSize(); i += 1) {
            connection = this.getSharedConnections().get(i);
            this.connectionsToCopy.push(connection.stringify());
        }

        /*
         // testing method Canvas.prototype.transformToTree(tree)
         var tree = [];
         for (i = 0; i < this.shapesToCopy.length; i += 1) {
         shape = this.shapesToCopy[i];
         tree.push({id: shape.id, parent: shape.parent});
         }
         console.log(this.transformToTree(tree));
         */
        return this;
    };

    /**
     * Pastes the shapes saved in `this.shapesToCopy` and the connections saved in `this.connectionsToCopy`
     * by calling the #parse method.
     *
     * Currently the parser is called with these arguments:
     *
     *       {
 *          shapes: this.shapesToCopy,
 *          connections: this.connectionsToCopy,
 *          createCommand: true,
 *          uniqueID: true,
 *          selectAfterFinish: true,
 *          prependMessage: "Copy of ",
 *          diffX: 100,
 *          diffY: 100
 *      }
     *
     * @chainable
     */
    Canvas.prototype.paste = function () {
        this.parse({
            shapes: this.shapesToCopy,
            connections: this.connectionsToCopy,
            createCommand: true,
            uniqueID: true,
            selectAfterFinish: true,
            prependMessage: "Copy of ",
            diffX: 100,
            diffY: 100
        });
        return this;
    };

    /**
     * Default copy paste factory which creates new instances of {@link CustomShape}, its main purpose
     * is to create instances using `this.copyAndPasteReferences` (passed through the config options of the canvas)
     * which are reference variables to the constructor of some class (a class declared outside the library).
     *
     *      // let's assume that there's a class declared outside the library called BpmnActivity
     *      var BpmnActivity = function (options) {
 *          ...
 *      };
     *
     *      // in the config options of this canvas, we passed a reference to the constructor like this
     *      var canvas = new Canvas({
 *          ...
 *          copyAndPasteReferences: {
 *              bpmnActivity: BpmnActivity
 *          }
 *          ...
 *      });
     *
     *      // so the shapeFactory will create an instance of the class BpmnActivity
     *      // using that reference
     *      // i.e.
     *      // let's assume that options are the correct configuration options for BpmnActivity
     *      var bpmnActivityInstance = Canvas.prototype.shapeFactory('bpmnActivity', options);
     *
     *
     * @param {string} type The type of the shape to be created
     * @param {Object} options The config options to be passed to the constructor of the shape
     * @return {CustomShape} A custom shape or shape created using the reference created before.
     */
    Canvas.prototype.shapeFactory = function (type, options) {
        if (this.copyAndPasteReferences[type]) {
            return new this.copyAndPasteReferences[type](options);
        }
        return new CustomShape(options);
    };

    /**
     * Factory to create connections
     * @param {string} type
     * @param {Object} options
     * @return {Object}
     */
    Canvas.prototype.connectionFactory = function (type, options) {
        if (type && this.copyAndPasteReferences[type]) {
            return new this.copyAndPasteReferences[type](options);
        }
        return new Connection(options);
    };

    /**
     * Transforms an array of objects, each of the form `{id: #, parent: #}` to a tree like object.
     * The structure of the returned object (which represents a tree) is:
     *
     *      {
 *          id_1: [child_1_of_id1, child_2_of_id1, ...],
 *          id_2: [child_1_of_id2, child_2_of_id2, ...],
 *          ...
 *      }
     *
     * @param {Array} nodes
     * @return {Object}
     */
    Canvas.prototype.transformToTree = function (nodes) {
        var tree = {},
            node,
            i;
        for (i = 0; i < nodes.length; i += 1) {
            // node = {id: #, parent: #, order: #}
            node = nodes[i];

            // create the children of node.id
            if (!tree[node.id]) {
                tree[node.id] = [];
            }

            // insert to the children of its parent
            if (node.parent) {
                // check if the node exists
                if (!tree[node.parent]) {
                    tree[node.parent] = [];
                }

                // add node to the children of node's parent
                tree[node.parent][node.order] = node.id;
            }
        }

        return tree;
    };

    /**
     * Given a tree (with the structure proposed in #transformToTree)
     * and a pointer to the root node, perform a levelOrder traversal (BFS)
     * of the tree and returning an array with the IDs of each node.
     * @param {Object} tree
     * @param {String} [root=canvas.getID()] The ID of the root node (might be canvas)
     * @return {Array} An array with the IDs of the nodes of the tree in level order traversal
     */
    Canvas.prototype.levelOrderTraversal = function (tree, root) {
        var queue = [],
            processed = [],                     // processed shapes
            top,
            realRoot = root || this.getID(),
            i;

        queue.push(realRoot);
        while (queue.length > 0) {
            top = queue.shift();

            // push the json of the node
            processed.push(top);

            // push to the queue
            for (i = 0; i < tree[top].length; i += 1) {
                queue.push(tree[top][i]);
            }
        }

        // return the IDs
        return processed;
    };

    /**
     * Parses `options` creating shapes and connections and placing them in this canvas.
     * It does the following:
     *
     * - Creates each shape (in the same order as it is in the array `options.shapes`)
     * - Creates each connection (in the same order as it is in the array `options.connections`)
     * - Creates the an instance of {@link CommandPaste} (if possible)
     *
     * @param {Object} options
     * @param {Array} [options.shapes=[]] The config options of each shape to be placed in this canvas.
     * @param {Array} [options.connections=[]] The config options of each connection to be placed in this canvas.
     * @param {boolean} [options.uniqueID=false] If set to true, it'll assign a unique ID to each shape created.
     * @param {boolean} [options.selectAfterFinish=false] If set to true, it'll add the shapes that are
     * direct children of this canvas to `this.currentSelection` arrayList.
     * @param {string} [options.prependMessage=""] The message to be prepended to each shape's label.
     * @param {boolean}  [options.createCommand=true] If set to true it'll create a command for each creation
     * of a shape and connection (see {@link CommandCreate}, {@link CommandConnect}) and save them in
     * a {@link CommandPaste} (for undo-redo purposes).
     * @param {number} [options.diffX=0] The number of pixels on the x-coordinate to move the shape on creation
     * @param {number} [options.diffY=0] The number of pixels on the y-coordinate to move the shape on creation
     * @chainable
     */
    Canvas.prototype.parse = function (options) {
        var defaults = {
                shapes: [],
                connections: [],
                uniqueID: false,
                selectAfterFinish: false,
                prependMessage: "",
                createCommand: true,
                diffX: 0,
                diffY: 0
            },
            i,
            j,
            id,
            oldID,
            shape,
            points,
            shapeOptions,
            connection,
            connectionOptions,
            sourcePort,
            sourcePortOptions,
            sourceShape,
            sourceBorder,
            destPort,
            destPortOptions,
            destShape,
            destBorder,
            command,
            diffX,
            diffY,
            connectionConstructor,
            stackCommandCreate = [],
            stackCommandConnect = [],
            canvasID = this.getID(),
            mapOldId = {},              // {oldId: newId}
            map = {},                   // {newId: reference to the shape},
            connectExtendedOptions = {};

        $.extend(true, defaults, options);

        // set the differentials (if the shapes are pasted in the canvas)
        diffX = defaults.diffX;
        diffY = defaults.diffY;

        // map the canvas
        map[canvasID] = this;
        mapOldId[canvasID] = canvasID;

        // empty the current selection and sharedConnections as a consequence
        // (so that the copy is selected after)
        if (defaults.selectAfterFinish) {
            this.emptyCurrentSelection();
        }

        for (i = 0; i < defaults.shapes.length; i += 1) {
            shapeOptions = {};
            $.extend(true, shapeOptions, defaults.shapes[i]);

            // set the canvas of <shape>
            shapeOptions.canvas = this;

            // create a map of the current id with a new id
            oldID = shapeOptions.id;
            // generate a unique id on user request
            if (defaults.uniqueID) {
                shapeOptions.id = Utils.generateUniqueId();
            }
            mapOldId[oldID] = shapeOptions.id;

            // change labels' messages (using prependMessage)
            if (shapeOptions.labels) {
                for (j = 0; j < shapeOptions.labels.length; j += 1) {
                    shapeOptions.labels[j].message = defaults.prependMessage +
                    shapeOptions.labels[j].message;
                }
            }

            // create an instance of the shape based on its type
            shape = this.shapeFactory(shapeOptions.type, shapeOptions);

            // map the instance with its id
            map[shapeOptions.id] = shape;

            // if the shapes don't have a valid parent then set the parent
            // to be equal to the canvas
            // TODO: ADD shapeOptions.topLeftOnCreation TO EACH SHAPE
            if (!mapOldId[shapeOptions.parent]) {
                this.addElement(shape,
                    shapeOptions.x + diffX, shapeOptions.y + diffY, true);
            } else if (shapeOptions.parent !== canvasID) {
                // get the parent of this shape
                map[mapOldId[shapeOptions.parent]].addElement(shape, shapeOptions.x,
                    shapeOptions.y, true);
            } else {
                // move the shapes a little (so it can be seen that
                // they were duplicated)
                map[mapOldId[shapeOptions.parent]].addElement(shape,
                    shapeOptions.x + diffX, shapeOptions.y + diffY, true);
            }
            // perform some extra actions defined for each shape
            shape.parseHook();

            shape.attachListeners();

//        console.log(shape);
            // execute command create but don't add it to the canvas.commandStack
            command = new CommandCreate(shape);
            command.execute();
            stackCommandCreate.push(command);
        }

        for (i = 0; i < defaults.connections.length; i += 1) {
            connectionOptions = {};
            $.extend(true, connectionOptions, defaults.connections[i]);

            // state of the connection
            points = connectionOptions.state || [];

            // determine the shapes
            sourcePortOptions = connectionOptions.srcPort;
            sourceShape = map[mapOldId[sourcePortOptions.parent]];
            sourceBorder = sourceShape.getBorderConsideringLayers();

            destPortOptions = connectionOptions.destPort;
            destShape = map[mapOldId[destPortOptions.parent]];
            destBorder = destShape.getBorderConsideringLayers();

            // populate points if points has no info (backwards compatibility,
            // i.e. the flow state is null)
            if (points.length === 0) {
                points.push({
                    x: sourcePortOptions.x + sourceShape.getAbsoluteX(),
                    y: sourcePortOptions.y + sourceShape.getAbsoluteY()
                });
                points.push({
                    x: destPortOptions.x + destShape.getAbsoluteX(),
                    y: destPortOptions.y + destShape.getAbsoluteY()
                });
            }

            //create the ports
            sourcePort = new Port({
                width: 12,
                height: 12
            });
            destPort = new Port({
                width: 12,
                height: 12
            });

            // add the ports to the shapes
            // LOGIC: points is an array of points relative to the canvas.
            // CustomShape.addPort() requires that the point passed as an argument
            // is respect to the shape, so transform the point's coordinates (also
            // consider the border)
            sourceShape.addPort(
                sourcePort,
                points[0].x + diffX + sourceBorder -
                sourceShape.getAbsoluteX(),
                points[0].y + diffX + sourceBorder -
                sourceShape.getAbsoluteY()
            );
            destShape.addPort(
                destPort,
                points[points.length - 1].x + diffX + destBorder -
                destShape.getAbsoluteX(),
                points[points.length - 1].y + diffY + destBorder -
                destShape.getAbsoluteY(),
                false,
                sourcePort
            );

            connectExtendedOptions = $.extend(true, {}, defaults.connections[i],
                {
                    srcPort : sourcePort,
                    destPort: destPort,
                    canvas : this,
                    segmentStyle: connectionOptions.segmentStyle
                }
            );
            connection = this.connectionFactory(connectionOptions.type,
                connectExtendedOptions);

            connection.id = connectionOptions.id || Utils.generateUniqueId();
            if (defaults.uniqueID) {
                connection.id = Utils.generateUniqueId();
            }

            //set its decorators
            connection.setSrcDecorator(new ConnectionDecorator({
                width: 11,
                height: 11,
                canvas: this,
                decoratorPrefix: connectionOptions.srcDecoratorPrefix,
                decoratorType: "source",
                parent: connection
            }));
            connection.setDestDecorator(new ConnectionDecorator({
                width: 11,
                height: 11,
                canvas: this,
                decoratorPrefix: connectionOptions.destDecoratorPrefix,
                decoratorType: "target",
                parent: connection
            }));

            command = new CommandConnect(connection);
            stackCommandConnect.push(command);

            //connect the two ports
            if (points.length >= 3) {
//            console.log("user");
                connection.connect({
                    algorithm: 'user',
                    points: connectionOptions.state,
                    dx: defaults.diffX,
                    dy: defaults.diffY
                });
            } else {
                // use manhattan
//            console.log("manhattan");
                connection.connect();
            }
            connection.setSegmentMoveHandlers();

            // / fixes the zIndex of the connection
            //connection.fixZIndex();

            //add the connection to the canvas, that means insert its html to
            // the DOM and adding it to the connections array
            this.addConnection(connection);

            // now that the connection was drawn try to create the intersections
            connection.checkAndCreateIntersectionsWithAll();

            //attaching port listeners
            sourcePort.attachListeners(sourcePort);
            destPort.attachListeners(destPort);

            this.triggerCreateEvent(connection, []);
        }

        // finally add to currentSelection each shape if possible (this method is
        // down here because of the zIndex problem with connections)
        if (defaults.selectAfterFinish) {
            for (id in map) {
                if (map.hasOwnProperty(id)) {
                    if (map[id].family !== 'Canvas') {
                        this.addToSelection(map[id]);
                    }
                }
            }
        }

        // create command if possible
        if (defaults.createCommand) {
            this.commandStack.add(new CommandPaste(this, {
                stackCommandCreate: stackCommandCreate,
                stackCommandConnect: stackCommandConnect
            }));
        }
        return this;
    };

///**
// * Testing json (easy viewing of the json file)
// * @param object
// */
//Canvas.prototype.stringifyAndSaveToFile = function (object) {
//    $.ajax({
//        url: '../src/json_test/output.php',
//        type: 'POST',
//        data: {
//            json: object
//        }
//    });
//    window.open('../src/json_test/output.json', '_blank');
//};

    CustomLine = function (options) {
        this.x1 = null;
        this.y1 = null;
        this.x2 = null;
        this.y2 = null;
        this.html = null;
        this.canvas = (options.canvas) ? options.canvas : null;
    };

    CustomLine.prototype.type = "CustomLine";
    CustomLine.prototype.family = "CustomLine";

    CustomLine.prototype.setStarPoint = function(startPoint) {
        this.x1 = startPoint.x;
        this.y1 = startPoint.y;
        return this;
    };

    CustomLine.prototype.setEndPoint = function (endPoint) {
        this.x2 = endPoint.x;
        this.y2 = endPoint.y;
        return this;
    };
    CustomLine.prototype.setHtml = function(html) {
        this.html = html;
        return this;
    };

    CustomLine.prototype.drawLine = function (x1, y1, x2, y2) {
        var dx, dy, length, angle, transform;
        dx = x2-x1;
        dy = y2-y1;
        length = Math.sqrt(Math.pow(Math.abs(dx),2) + Math.pow(Math.abs(dy),2));
        angle  = Math.atan2(dy, dx) * 180 / Math.PI;
        var auxLine=1;
        var screenCssPixelRatio = (window.outerWidth - 8) / window.innerWidth;
        if (screenCssPixelRatio >= .20 && screenCssPixelRatio <= .34) {
            auxLine=4;
        } else if (screenCssPixelRatio <= .54) {
            auxLine=3;
        } else if (screenCssPixelRatio <= .92) {
            auxLine=2;
        } else {
            auxLine=1;
        }
        if (dx === 0) {
            if (dy < 0){
                this.createDiv(x2,y2,auxLine,length);
            } else {
                this.createDiv(x1,y1,auxLine,length);
            }
        } else if(dy === 0) {
            if (dx < 0) {
                this.createDiv(x2,y2,length,auxLine);
            } else {
                this.createDiv(x1,y1,length,auxLine);
            }
        } else {
            transform = 'rotate('+angle+'deg)';
            this.createDiv(x1,y1,length,auxLine, transform);
        }
        this.paint();
    };

    CustomLine.prototype.paint = function () {
        if(this.canvas){
            $(this.html).appendTo(this.canvas);
        }
        this.html = "";
    };

    CustomLine.prototype.createDiv = function(x, y, w, h, transform){
        var orientation = (w === 1 ? 1 : (h === 1 ? 0: -1)), offset;
        // making the lines two pixels wide instead of one
        var lineWidth = w === 1 ? 2 : w;
        var lineHeight = h === 1 ? 2 : h;

        if (transform) {
            offset = {
                left: x,
                top: y
            };
        } else {
            offset = {
                left: x - (orientation === 1 ? 1 : 0),
                top: y - (orientation === 0 ? 1 : 0)
            };

        }
        this.html = $('<div>')
            .addClass('line')
            .css({
                'animation':'none',
                'position': 'absolute'
            })
            .width(lineWidth)
            .height(lineHeight)
            .offset(offset);
        if (transform) {
            $(this.html).css({
                '-webkit-transform': transform,
                '-moz-transform': transform,
                '-ms-transform': transform,
                'transform': transform
            });
        } else {
            $(this.html).addClass((orientation === 1 ? 'line-vertical' : (orientation === 0 ? 'line-horizontal': '')));
        }
    };

    CustomLine.prototype.remove = function () {
        if (this.html) {
            $(this.html).remove();
        }
    };

    CustomLine.prototype.clear = function() {
        $(this.html).empty();
        if(this.canvas) this.canvas.innerHTML = "";
    };

    function onCanvasKeyDown(e) {
        if (activeCanvas) {
            switch (e.which) {
                case 16: // SHIFT KEY
                    isShift = true;
                    break;
                case 17: // CTRL KEY
                    isCtrl = true;
                    break;
                case 116: // F5 KEY
                    e.preventDefault();
                    window.location.reload(true);
                    break;
                case 37:
                    // Left
                    if (!activeCanvas.currentLabel) {
                        e.preventDefault();
                        activeCanvas.moveElements(activeCanvas, 'LEFT');
                    }
                    break;
                case 38:
                    // Top
                    if (!activeCanvas.currentLabel) {
                        e.preventDefault();
                        activeCanvas.moveElements(activeCanvas, 'TOP');
                    }
                    break;
                case 39:
                    // Right
                    if (!activeCanvas.currentLabel) {
                        e.preventDefault();
                        activeCanvas.moveElements(activeCanvas, 'RIGHT');
                    }
                    break;
                case 40:
                    // Bottom
                    if (!activeCanvas.currentLabel) {
                        e.preventDefault();
                        activeCanvas.moveElements(activeCanvas, 'BOTTOM');
                    }
                    break;
                case 67:    // char 'c'
                    if (!activeCanvas.currentLabel && isCtrl) {
                        if (activeCanvas.copyAndPaste) {
                            e.preventDefault();
                            activeCanvas.copy();
                        }

                    }
                    break;
                case 86:    // char 'v'
                    if (!activeCanvas.currentLabel && isCtrl) {

                        if (activeCanvas.copyAndPaste) {
                            e.preventDefault();
                            activeCanvas.paste();
                        }
                    }
                    break;
                case 90:    // char 'z'
                    if (isCtrl) {
                        if (isShift) {
                            // ctrl + shift + z (redo)
                            activeCanvas.redo();
                            e.preventDefault();
                        } else {
                            // ctrl + z (undo)
                            activeCanvas.undo();
                            e.preventDefault();
                        }
                    }
                    break;
                case 8:  //BACKSPACE
                    if (activeCanvas && (activeCanvas.currentSelection.getSize() || activeCanvas.currentConnection)) {
                        e.preventDefault();
                    }
                    break;
            }
        }
    }

    function onCanvasKeyUp(e) {
        var current;
        if (activeCanvas) {
            e.preventDefault();
        }
        switch (e.which) {
            case 8:  //BACKSPACE
                if (isCtrl || (activeCanvas && activeCanvas.currentSelection.getSize())) {
                    if (activeCanvas && !activeCanvas.currentLabel) {
                        activeCanvas.removeElements();
                    }
                } else if (activeCanvas && activeCanvas.currentConnection) {
                    activeCanvas.removeElements();
                }
                break;
            case 13:    // ENTER
                if (activeCanvas && activeCanvas.currentLabel) {
                    activeCanvas.currentLabel.loseFocus();
                }
                break;
            case 46: // DELETE KEY
                if (activeCanvas && !activeCanvas.currentLabel) {
                    activeCanvas.removeElements();
                }
                break;
            case 16: // SHIFT KEY
                isShift = false;
                break;
            case 17: //CTRL KEY
                isCtrl = false;
                break;
            case 113: //F2 KEY
                if (activeCanvas &&
                    activeCanvas.getCurrentSelection().getLast() !== null) {
                    //Run other code here when the element
                    // 'CurElement' is deleted
                    current = activeCanvas.getCurrentSelection().getLast();
                    if (current !== undefined && current.label.html !== null) {
                        $(current.label.html).dblclick();
                        $(current.label.text.html).focus();
                    }
                }
                break;
        }
    }

    return {
        ArrayList: ArrayList,
        Point: Point,
        Geometry: Geometry,
        Graphics: Graphics,
        Utils: Utils,

        Command: Command,
        CommandStack: CommandStack,
        CommandResize: CommandResize,
        CommandConnect: CommandConnect,
        CommandReconnect: CommandReconnect,
        CommandSegmentMove: CommandSegmentMove,
        CommandMove: CommandMove,
        CommandCreate: CommandCreate,
        CommandSwitchContainer: CommandSwitchContainer,
        CommandDelete: CommandDelete,
        CommandPaste: CommandPaste,
        CommandEditLabel: CommandEditLabel,

        ContainerBehavior: ContainerBehavior,
        RegularContainerBehavior: RegularContainerBehavior,
        NoContainerBehavior: NoContainerBehavior,
        DragBehavior: DragBehavior,
        RegularDragBehavior: RegularDragBehavior,
        NoDragBehavior: NoDragBehavior,
        ConnectionDragBehavior: ConnectionDragBehavior,
        CustomShapeDragBehavior: CustomShapeDragBehavior,
        ResizeBehavior: ResizeBehavior,
        RegularResizeBehavior: RegularResizeBehavior,
        NoResizeBehavior: NoResizeBehavior,
        DropBehavior: DropBehavior,
        ConnectionDropBehavior: ConnectionDropBehavior,
        NoDropBehavior: NoDropBehavior,
        ContainerDropBehavior: ContainerDropBehavior,
        ConnectionContainerDropBehavior: ConnectionContainerDropBehavior,

        Color: Color,
        Style: Style,
        JCoreObject: JCoreObject,
        Handler: Handler,
        ReadOnlyLayer: ReadOnlyLayer,
        ResizeHandler: ResizeHandler,
        SegmentMoveHandler: SegmentMoveHandler,
        Port: Port,
        Router: Router,
        ManhattanConnectionRouter: ManhattanConnectionRouter,
        ConnectionDecorator: ConnectionDecorator,
        Connection: Connection,

        BehavioralElement: BehavioralElement,
        Layer: Layer,
        Shape: Shape,
        Label: Label,
        CustomShape: CustomShape,
        Segment: Segment,

        RegularShape: RegularShape,
        Polygon: Polygon,
        Rectangle: Rectangle,
        Oval: Oval,
        Arc: Arc,
        MultipleSelectionContainer: MultipleSelectionContainer,

        Intersection: Intersection,
        Snapper: Snapper,
        Canvas: Canvas,

        /**
         * Sets the active canvas.
         * @param {Canvas} canvas
         * @chainable
         */
        setActiveCanvas: function (canvas) {
            activeCanvas = canvas;
            return this;
        },
        /**
         * Gets the active canvas
         * @return {Canvas}
         */
        getActiveCanvas: function () {
            return activeCanvas;
        },
        /**
         * Get jCore Version.
         * @return {String}
         */
        getVersion: function () {
            return version;
        },
        dispose: function () {
            $(document).off('keydown', onCanvasKeyDown)
                .off('keyup', onCanvasKeyUp);
        }
    };

}(jQuery, window));
