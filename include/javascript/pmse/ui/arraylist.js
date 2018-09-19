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
// jscs:disable
var PMSE = PMSE || {};
/**
 * @class PMSE.ArrayList
 * Construct a List similar to Java's PMSE.ArrayList that encapsulates methods for
 * making a list that supports operations like get, insert and others.
 *
 *      some examples:
 *      var item,
 *          arrayList = new PMSE.ArrayList();
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
 * @constructor Returns an instance of the class PMSE.ArrayList
 */
PMSE.ArrayList = function() {
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
         * The ID of this PMSE.ArrayList is generated using the function Math.random
         * @property {number} id
         */
        id: Math.random(),
        /**
         * Gets an element in the specified index or undefined if the index
         * is not present in the array
         * @param {number} index
         * @returns {Object / undefined}
         */
        get : function(index) {
            return elements[index];
        },
        /**
         * Inserts an element at the end of the list
         * @param {Object}
         * @chainable
         */
        insert : function(item) {
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
        remove : function(item) {
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
        getSize : function() {
            return size;
        },
        /**
         * Returns true if the list is empty
         * @returns {boolean}
         */
        isEmpty : function() {
            return size === 0;
        },
        /**
         * Returns the first occurrence of an element, if the element is not
         * contained in the list then returns -1
         * @param {Object} item
         * @return {number}
         */
        indexOf : function(item) {
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
        find : function(attribute, value) {
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
        contains : function(item) {
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
        sort : function(compFunction) {
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
        asArray : function() {
            return elements;
        },
        /**
         * Returns the first element of the list
         * @return {Object}
         */
        getFirst : function() {
            return elements[0];
        },
        /**
         * Returns the last element of the list
         * @return {Object}
         */
        getLast : function() {
            return elements[size - 1];
        },

        /**
         * Returns the last element of the list and deletes it from the list
         * @return {Object}
         */
        popLast : function() {
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
        getDimensionLimit : function() {
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
        clear : function() {
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
        getCanvas : function() {
            return (this.getSize() > 0) ? this.get(0).getCanvas() : undefined;
        }
    };
};

// Declarations created to instantiate in NodeJS environment
if (typeof exports !== 'undefined') {
    module.exports = PMSE.ArrayList;
//    var _ = require('../../lib/underscore/underscore.js');
}
