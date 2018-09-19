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
 * @class PMSE.Element
 * Base class to handle HTML Divs
 * @extends PMSE.Base
 *
 *
 * @constructor
 * Create a new instace of the class 'PMSE.Element'
 * @param {Object} options
 */
PMSE.Element = function(options) {
    PMSE.Base.call(this, options);
    /**
     * Absolute X position of the HTML Element
     * @type {Number}
     */
    this.x = null;
    /**
     * Absolute Y position of the HTML Element
     * @type {Number}
     */
    this.y = null;
    /**
     * Width dimension of the HTML Element
     * @type {Number}
     */
    this.width = null;
    /**
     * Height dimension of the HTML Element
     * @type {Number}
     */
    this.height = null;
    /**
     * Pointer to the HTMLElement object
     * @type {HTMLElement}
     */
    this.html = null;
    /**
     * Intance of the jCore.Style object to handle style tags
     * @type {Object}
     */
    this.style = null;
    /**
     * Defines if the HTML element is visible
     * @type {Boolean}
     */
    this.visible = null;
    /**
     * Defines the value of the zIndex for the HTML Element
     * @type {Number}
     */
    this.zOrder = null;
    /**
     * Holds the compiled handlebars template
     * @type {Object}
     */
    this.template = null;
    /**
     * Holds the compiled handlebars template2
     * @type {Object}
     */
    this.template2 = null;

    PMSE.Element.prototype.initObject.call(this, options);
};
PMSE.Element.prototype = new PMSE.Base();

/**
 * Defines the object type
 * @type {String}
 * @private
 */
PMSE.Element.prototype.type = 'Base';
/**
 * Defines the object family
 * @type {String}
 * @private
 */
PMSE.Element.prototype.family = 'Base';

/**
 * Initialize the object with the default values
 * @param {Object} options
 * @private
 */
PMSE.Element.prototype.initObject = function(options) {
    var defaults = {
        //id : (options && options.id) || jCore.Utils.generateUniqueId(),
        style : {
            cssProperties: {},
            cssClasses: []
        },
        width : 0,
        height : 0,
        x : 0,
        y : 0,
        zOrder : 1,
        visible : true
    };

    // Do not deep copy here
    $.extend(defaults, options);

    this//.setId(defaults.id)
        .setStyle(new PMSE.Style({
            belongsTo: this,
            cssProperties: defaults.style.cssProperties,
            cssClasses: defaults.style.cssClasses
        }))
        .setDimension(defaults.width, defaults.height)
        .setPosition(defaults.x, defaults.y)
        .setZOrder(defaults.zOrder)
        .setVisible(defaults.visible);
};

/**
* Sets the id property
* @param {String} newID
* @return {*}
*/
PMSE.Element.prototype.setId = function(newID) {
    this.id = newID;
    if (this.html) {
        this.html.id = this.id;
    }
    return this;
};
/**
 * Sets the X property
 * @param {Number} x
 * @return {*}
 */
PMSE.Element.prototype.setX = function(x) {
    if (typeof x === 'number') {
        this.x = x;
        if (this.html) {
            this.style.addProperties({left: this.x});
        }
    } else {
        throw new Error('setX: x param is not a number');
    }
    return this;
};

/**
 * Sets the Y property
 * @param {Number} y
 * @return {*}
 */
PMSE.Element.prototype.setY = function(y) {
    if (typeof y === 'number') {
        this.y = y;
        if (this.html) {
            this.style.addProperties({top: this.y});
        }
    } else {
        throw new Error('setY: y param is not a number');
    }
    return this;
};

/**
 * Sets the width property
 * @param {Number} w
 * @return {*}
 */
PMSE.Element.prototype.setWidth = function(w) {
    if (typeof w === 'number') {
        this.width = w;
        if (this.html) {
            this.style.addProperties({width: this.width});
        }
    } else {
        throw new Error('setWidth: w is not a number');
    }
    return this;
};

/**
 * Sets the height property
 * @param {Number} h
 * @return {*}
 */
PMSE.Element.prototype.setHeight = function(h) {
    if (typeof h === 'number') {
        this.height = h;
        if (this.html) {
            this.style.addProperties({height: this.height});
        }
    } else {
        throw new Error('setHeight: h is not a number');
    }
    return this;
};

/**
 * Sets the position of the HTML Element
 * @param {Number} x
 * @param {Number} y
 * @return {*}
 */
PMSE.Element.prototype.setPosition = function(x, y) {
    this.setX(x);
    this.setY(y);
    return this;
};

/**
 * Sets the dimension of the HTML Element
 * @param {Number} w
 * @param {Number} h
 * @return {*}
 */
PMSE.Element.prototype.setDimension = function(w, h) {
    this.setWidth(w);
    this.setHeight(h);
    return this;
};

/**
 * Sets the xOrder property
 * @param {Number} z
 * @return {*}
 */
PMSE.Element.prototype.setZOrder = function(z) {
    if (typeof z === 'number' && z > 0) {
        this.zOrder = z;
        if (this.html) {
            this.style.addProperties({zIndex: this.zOrder});
        }
    }
    return this;
};

/**
 * Sets the visible property
 * @param {Boolean} value
 * @return {*}
 */
PMSE.Element.prototype.setVisible = function(value) {
    if (_.isBoolean(value)) {
        this.visible = value;
        if (this.html) {
            if (value) {
                this.style.addProperties({display: "inline"});
            } else {
                this.style.addProperties({display: "none"});
            }
        }
    }
    return this;
};

/**
 * Sets the style object
 * @param {Object} style Instance of jCore.Style
 * @return {*}
 */
PMSE.Element.prototype.setStyle = function(style) {
    if (style instanceof PMSE.Style) {
        this.style = style;
    }
    return this;
};

/**
 * Creates a new HTML Element
 * @param {String} type
 * @return {HTMLElement}
 */
PMSE.Element.prototype.createHTMLElement = function(type) {
    return document.createElement(type);
};

/**
 * Creates the hmtl object
 * @return {HTMLElement}
 */
PMSE.Element.prototype.createHTML = function() {
    if (!this.html) {
        this.html = this.createHTMLElement('div');
        this.html.id = this.id;

        this.style.applyStyle();

        this.style.addProperties({
            position: "absolute",
            left: this.x,
            top: this.y,
            width: this.width,
            height: this.height,
            zIndex: this.zOrder
        });
    }
    return this.html;
};

/**
 * Defines the functionality to paint the HTML element
 * @abstract
 */
PMSE.Element.prototype.paint = function() {
};

/**
 * Returns the html pointer
 * @return {HTMLElement}
 */
PMSE.Element.prototype.getHTML = function() {
    if (!this.html) {
        this.createHTML();
    }
    return this.html;
};

/**
 * Returns the compiled handlebars template based on the input
 * @param {string} template
 * @return {Object}
 */
PMSE.Element.prototype.compileTemplate = function(template) {
    var source = App
        .metadata
        .getView('pmse_Business_Rules')
        .businessrules
        .templates[template]
        .replace(/\r?\n|\r/g,'');
    return Handlebars.compile(source);
};

/**
 * Returns the html pointer by using the provided handlebars template and context
 * @param {Object} template
 * @param {Object} context
 * @return {HTMLElement}
 */
PMSE.Element.prototype.getHTMLFromTemplate = function(template, context) {
    if (template) {
        var html = template(context);
        var parsed = $.parseHTML(html);
        return parsed[0];
    }
    return null;
};

/**
 * Calculates the text width
 * @param {String} text
 * @param {String} [font]
 * @return {*}
 */
PMSE.Element.prototype.calculateWidth = function(text, font) {
    //TODO Improve the div creation (maybe we can use a singleton for this)
    var f = font || '12px arial',
        $o = $(this.createHTMLElement('div')), w;
        $o.text(text)
            .css({'position': 'absolute', 'float': 'left', 'white-space': 'nowrap', 'visibility': 'hidden', 'font': f})
            .appendTo($('body')),
        w = $o.width();

    $o.remove();

    return w;
};
