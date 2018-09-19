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
 * @class PMSE.Style
 * Class that represent the style of a an object, {@link JCoreObject} creates an instance of this class so every
 * class that inherits from {@link JCoreObject} has an instance of this class.
 *
 *      // i.e
 *      // Let's assume that 'shape' is a CustomShape
 *      var style = new PMSE.Style({
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
 * @constructor
 * Creates a new instance of this class
 * @param {Object} options
 * @cfg {Array} [cssClasses=[]] the classes that `this.belongsTo` has
 * @cfg {Object} [cssProperties={}] the css properties that `this.belongsTo` has
 * @cfg {Object} [belongsTo=null] a pointer to the owner of this instance
 */
PMSE.Style = function(options) {

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


    PMSE.Style.prototype.initObject.call(this, options);
};


/**
 * The type of this class
 * @property {String}
 */
PMSE.Style.prototype.type = 'PMSE.Style';

/**
 * Constant for the max z-index
 * @property {number} [MAX_ZINDEX=100]
 */
PMSE.Style.MAX_ZINDEX = 100;

/**
 * Instance initializer which uses options to extend the config options to
 * initialize the instance
 * @private
 * @param {Object} options
 */
PMSE.Style.prototype.initObject = function(options) {
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
PMSE.Style.prototype.applyStyle = function() {

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
PMSE.Style.prototype.addProperties = function(properties) {
    $.extend(true, this.cssProperties, properties);
    $(this.belongsTo.html).css(properties);
    return this;
};

/**
 * Gets a property from `this.cssProperties` using jQuery or `window.getComputedStyle()`
 * @param {String} property
 * @return {String}
 */
PMSE.Style.prototype.getProperty = function(property) {
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
PMSE.Style.prototype.removeProperties = function(properties) {
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
PMSE.Style.prototype.addClasses = function(cssClasses) {
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
PMSE.Style.prototype.removeClasses = function(cssClasses) {

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
PMSE.Style.prototype.removeAllClasses = function() {
    this.cssClasses = [];
    $(this.belongsTo.html).removeClass();
    return this;
};

/**
 * Checks if the class is a class stored in ´this.cssClasses´
 * @param cssClass
 * @return {boolean}
 */
PMSE.Style.prototype.containsClass = function(cssClass) {
    return this.cssClasses.indexOf(cssClass) !== -1;
};

/**
 * Returns an array with all the classes of ´this.belongsTo´
 * @return {Array}
 */
PMSE.Style.prototype.getClasses = function() {
    return this.cssClasses;
};

/**
 * Serializes this instance
 * @return {Object}
 * @return {Array} return.cssClasses
 */
PMSE.Style.prototype.stringify = function() {
    return {
        cssClasses: this.cssClasses
//        cssProperties: this.cssProperties
    };
};
