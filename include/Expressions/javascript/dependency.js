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


(function() {

/**
 * This JavaScript file provides an entire framework for the new
 * SUGAR Calculated Fields/Dependent Dropdowns implementation.
 * This is integrated heavily with the SUGAR Expressions engine
 * which does the actual input validation and expression
 * calculations behind the scenes.
 *
 * @import sugar_expressions.php
 * @import formvalidation.js  (RequireDependency function)
 * @import yui-dom-event.js	    (although, we could do without this in the future)
 */

// namespace
if ( typeof(SUGAR.forms) == 'undefined' )	SUGAR.forms = {};
if ( typeof(SUGAR.forms.animation) == 'undefined') SUGAR.forms.animation = {};

var Dom = YAHOO.util.Dom;

/**
 * @STATIC
 * The main assignment handler which maintains a registry of the
 * current variables in use and the appropriate fields they map to.
 * It can assign values to variables and retrieve the values of
 * variables. Also, it animates the updated fields if necessary
 * to indicate a change in value to the user.
 */
var AH = SUGAR.forms.AssignmentHandler = function() {
	// pass ...
}

/**
 * @STATIC
 * This flag determines whether animations are turned on/off.
 */
AH.ANIMATE = true;

    /**
  * @STATIC
 * This array maps form elements to their respective element in DOM.
 */
AH.COLLECTIONS_MAP = {};

    /**
 * @STATIC
 *  * Event which is fired after loadComplete method is done.
 * Meaning it that all the onload dependencies were fired.
 */
AH.AFTER_LOAD_COMPLETE = new YAHOO.util.CustomEvent("AFTER_LOAD_COMPLETE");

/**
 * @STATIC
 * This array maps variables to their respective element id's.
 */
AH.VARIABLE_MAP = {};

/**
 * @STATIC
 * This array maps variables to a set of listeners
 */
AH.LISTENERS = {};

/**
 * @STATIC
 * This array contains a list of valid relationship links for this module
 */
AH.LINKS = {};

/**
 * @STATIC
 * This array contains the list of locked variables. (For Detection of Circular References)
 */
AH.LOCKS = {};

AH.QUEUEDDEPS = [];



/**
 * @STATIC
 * Register a variable with the handler.
 */
AH.register = function(variable, view) {
	if (!view) view = AH.lastView;

	if (typeof(AH.VARIABLE_MAP[view]) == "undefined")
		AH.VARIABLE_MAP[view] = {};

	if ( variable instanceof Array ) {
		for ( var i = 0; i < variable.length; i++ ) {
			AH.VARIABLE_MAP[view][variable[i]] = document.getElementById(variable[i]);
		}
	} else {
		AH.VARIABLE_MAP[view][variable] = document.getElementById(variable);
	}
}


/**
 * @STATIC
 * Register form fields with the handler.
 */
AH.registerFields = function(flds) {
	if ( typeof(flds) != 'object' ) return;
	var form = document.forms[flds.form];
	var names = flds.fields;
	if (typeof(AH.VARIABLE_MAP[flds.form]) == "undefined")
		AH.VARIABLE_MAP[flds.form] = {};
	if ( typeof(form) == 'undefined' ) return;
	for ( var i = 0; i < names.length; i++ ) {
		var el = form[names[i]];
		if ( el != null ){
            AH.VARIABLE_MAP[flds.form][el.id] = el;
            AH.updateListeners(el.id, flds.form, el);
        }
	}
}

/**
 * @STATIC
 * Register all the fields in a form
 */
AH.registerForm = function(f, formEl) {
	var form = formEl || document.forms[f];
	if (typeof(AH.VARIABLE_MAP[f]) == "undefined")
		AH.VARIABLE_MAP[f] = {};
    AH.COLLECTIONS_MAP[f] = {};
	if ( typeof(form) == 'undefined' ) return;
	for ( var i = 0; i < form.length; i++ ) {
		var el = form[i];
		if ( el != null && el.value != null && el.id != null && el.id != "")
        {
            //Check for collections
            if (el.type && el.type == "text" && Dom.getAncestorByClassName(el, "emailaddresses"))
            {
                //Find the parent span to get the field name
                var span = Dom.getAncestorByTagName(el, "span");
                    sId = span.id; //Will be in the format fieldName_span
                    fieldName = sId.substring(0, sId.length - 5);
                if (!AH.VARIABLE_MAP[f][fieldName] || !Dom.isAncestor(span, AH.VARIABLE_MAP[f][fieldName])) {
                    AH.VARIABLE_MAP[f][fieldName] = el;
                    AH.updateListeners(fieldName, f, el);
                }
                } else if(el.type && el.type == "radio") {
                    if (!AH.COLLECTIONS_MAP[f][el.name]) {
                        AH.COLLECTIONS_MAP[f][el.name] = [];
                    }
                AH.COLLECTIONS_MAP[f][el.name].push(el);
            }
			AH.VARIABLE_MAP[f][el.id] = el;
            AH.updateListeners(el.id, f, el);
        }
		else if ( el != null && el.value && el.type=="hidden"){
			AH.VARIABLE_MAP[f][el.name] = el;
			AH.updateListeners(el.name, f, el);
		}
	}
}

AH.registerView = function(view, startEl) {
	var Dom = YAHOO.util.Dom;
	AH.lastView = view;
	if (typeof(AH.VARIABLE_MAP[view]) == "undefined")
		AH.VARIABLE_MAP[view] = {};
	if (Dom.get(view) != null && Dom.get(view).tagName == "FORM") {
		return AH.registerForm(view);
	}
	var form = Dom.get("form");
    if (form && form.name == view)
    {
        AH.registerForm(view, form);
    }
    var nodes = YAHOO.util.Selector.query("span.sugar_field", startEl);
	for (var i in nodes) {
		if (nodes[i].id != "")
			AH.VARIABLE_MAP[view][nodes[i].id] = nodes[i];
            AH.updateListeners(nodes[i].id, view, nodes[i]);
	}
}


/**
 * @STATIC
 * Register a form field with the handler.
 */
AH.registerField = function(formname, field) {
	AH.registerFields({form:formname,fields:new Array(field)});
}

/**
 * @STATIC
 * Retrieve the value of a variable.
 */
AH.getValue = function(variable, view, ignoreLinks) {
	if (!view) view = AH.lastView;

	//Relate fields are only string on the client side, so return the variable name back.
	if(AH.LINKS[view][variable] && !ignoreLinks)
		return variable;

	var field = AH.getElement(variable, view);
	if ( field == null || field.tagName == null) 	return null;

	if (field.children.length == 1 && field.children[0].tagName.toLowerCase() == "input")
		field = field.children[0];

	// special select case for IE6 and dropdowns
	if ( field.tagName.toLowerCase() == "select" ) {
		if(field.selectedIndex == -1) {
			return null;
		} else {
			return field.options[field.selectedIndex].value;
		}
	}

	//checkboxes need to return a boolean value
	if(field.tagName.toLowerCase() == "input" && field.type.toLowerCase() == "checkbox") {
		return field.checked ? SUGAR.expressions.Expression.TRUE : SUGAR.expressions.Expression.FALSE;
	}

    if(field.tagName.toLowerCase() == 'input' && field.type.toLowerCase() == 'radio') {
        var form = field.form;
        var radioButtons = form[field.name];

        for(var rbi=0; rbi < radioButtons.length; rbi++) {
            var button = radioButtons[rbi];

            if(button.checked) {
                return button.value;
            }
        }
     }

	//Special case for dates
	if (field.className && (field.className == "DateTimeCombo" || field.className == "Date")){
		return SUGAR.util.DateUtils.parse(field.value, "user");
	}

	//For DetailViews where value is enclosed in a span tag
    if (field.tagName.toLowerCase() == "span")
    {
        if (field.hasAttribute("data-id-value"))
        {
            return field.getAttribute("data-id-value");
        }

        return document.all ? trim(field.innerText) : trim(field.textContent);
    }

	if (field.value !== null && typeof(field.value) != "undefined")
	{
		var asNum = SUGAR.expressions.unFormatNumber(field.value);
		if ( (/^(\-)?[0-9]+(\.[0-9]+)?$/).exec(asNum) != null ) {
			return parseFloat(asNum);
		}
		return field.value;
	}

	return YAHOO.lang.trim(field.innerText);
}

/**
 * @STATIC
 * Retrieve the element behind a variable.
 */
AH.getLink = function(variable, view) {
	if (!view) view = AH.lastView;

	if(AH.LINKS[view][variable])
		return AH.LINKS[view][variable];
}

/**
 *
 * @param link
 * @param type
 * @param field
 * @param value
 * @param view
 */
AH.cacheRelatedField = function(link, ftype, value, view)
{
    if (!view) view = AH.lastView;

    if(!AH.LINKS[view][link])
        return false;

    //If there is already a value cached for this link, we need to merge in the new field values
    if (typeof(AH.LINKS[view][link][ftype]) == "object" && typeof(value == "object"))
    {
        for(var i in value)
        {
            AH.LINKS[view][link][ftype][i] = value[i];
        }
    }
    else
        AH.LINKS[view][link][ftype] = value;

    return true;
}

/**
 *
 * @param link
 * @param ftype
 * @param view
 */
AH.getCachedRelatedField = function(link, ftype, view)
{
    if (!view) view = AH.lastView;

    if(!AH.LINKS[view][link] || AH.LINKS[view][link][ftype])
        return null;

    return AH.LINKS[view][link][ftype];
}

 /**
  * @STATIC
 * Retrieve the collection behind a variable.
 */
AH.getCollection = function (variable, view) {
    if (!view)view = AH.lastView;
        var field = (AH.COLLECTIONS_MAP[view] && AH.COLLECTIONS_MAP[view][variable]) ? AH.COLLECTIONS_MAP[view][variable] : null;
        return field;
}

/**
 * @STATIC
 * Retrieve the element behind a variable.
 */
AH.getElement = function(variable, view) {
	if (!view) view = AH.lastView;

	// retrieve the variable
	var field = AH.VARIABLE_MAP[view][variable];

	if ( field == null )
		field = YAHOO.util.Dom.get(variable);

	return field;
}

/**
 * @STATIC
 * Assign a value to a variable.
 */
AH.assign = function(variable, value, flash)
{
	var Dom = YAHOO.util.Dom;
	if (typeof flash == "undefined")
		flash = true;
	// retrieve the variable
	var field = AH.getElement(variable);

	if ( field == null )
		return null;

	// now check if this field is locked
	if ( AH.LOCKS[variable] != null ) {
		throw ("Circular Reference Detected");
	}

	//Detect field types and add error handling.
    if (document.DetailView)
    {
        field.innerHTML = '';
        field.appendChild(document.createTextNode(value));
    }
    else if (Dom.hasClass(field, "imageUploader"))
	{
		var img = Dom.get("img_" + field.id);
		img.src = value;
		img.style.visibility = "";
	}
	else if (field.type == "checkbox") {
		field.checked = value == SUGAR.expressions.Expression.TRUE || value === true;
	}
    else if(field.type == "radio") {//52997 - select radio button
        var radioButtons = field.form[field.id];

        for(var rbi=0; rbi < radiobuttons.length; rbi++) {
            var button = radioButtons[rbi];

            if(button.value == value) {
                button.checked = true;
            } else {
                button.checked = false;
            }
        }
    }
    else if(value instanceof Date)
    {
        if (Dom.hasClass(field, "date_input"))
			field.value = SUGAR.util.DateUtils.formatDate(value);
		else {
            if (Dom.hasClass(field, "DateTimeCombo"))
                AH.setDateTimeField(field, value);

            field.value = SUGAR.util.DateUtils.formatDate(value, true);
        }
    }
	else {
        // See if this is a numeric field that needs formatting
        var fieldForm = field.form.attributes.name.value;
        var fieldType = 'text';
        
        if ( typeof(validate[fieldForm]) == "object" ) {
            for ( var idx in validate[fieldForm] ) {
                if (validate[fieldForm][idx][0] == field.name) {
                    // We found our field
                    fieldType = validate[fieldForm][idx][1];
                    break;
                }
            }
        }
        if ( fieldType == 'decimal' || fieldType == 'currency' || fieldType == 'int' ) {
            // It's numeric, let's format it
            var localPrecision = 2;
            if ( fieldType == 'int' ) {
                localPrecision = 0;
            } else {
                // Some pages actually populate the precision, most do not however.
                if ( typeof(precision) != 'undefined' ) {
                    localPrecision = precision;
                }
            }

            if ( value != '' ) {
                value = formatNumber(value,num_grp_sep,dec_sep,localPrecision,localPrecision);
            }
        } else if ( typeof(value) == 'object' 
                    && value.length > 0 
                    && fieldType != 'multienum' ) {
            value = value.join(', ');
        }
		field.value = value;
	}

	// animate
	if ( AH.ANIMATE && flash){
        try{
            //This can throw an error if the page is in the middle of rendering
            SUGAR.forms.FlashField(field);
        }catch(e){}
    }


	// lock this variable
	AH.LOCKS[variable] = true;

	// fire onchange
	SUGAR.util.callOnChangeListers(field);

	// unlock this variable
	AH.LOCKS[variable] = null;
}

/**
 * @private
 *  Private function used to attach a listener to an element
 *
 * @param varname
 * @param callback
 * @param scope
 */
var attachListener = function(el, callback, scope, view)
{
    scope = scope || this;
    if (el.type && el.type.toUpperCase() == "CHECKBOX")
    {
        return YAHOO.util.Event.addListener(el, "click", callback, scope, true);
    }
    else if (el.type && el.type.toUpperCase() == "RADIO"){//52997 - add event listners on Radio button elements
        var radioButtons = el.form[el.id];

        for(var radioButtonIndex = 0; radioButtonIndex < radioButtons.length; radioButtonIndex++) {
            var button = radioButtons[radioButtonIndex];
            YAHOO.util.Event.addListener(button, "click", callback, scope, true);
        }
    }
    else {
        return YAHOO.util.Event.addListener(el, "change", callback, scope, true);
    }
}

/**
 *  Registers a callback to fire when a variable/field changes in the current view
 * @param varname
 * @param callback
 * @param scope
 * @param view
 */
AH.addListener = function(varname, callback, scope, view)
{
    if (!view) view = AH.lastView;
    if (!AH.LISTENERS[view]) AH.LISTENERS[view] = {};
    if (!AH.LISTENERS[view][varname]) AH.LISTENERS[view][varname] = [];
    var el = AH.getElement(varname, view);
    AH.LISTENERS[view][varname].push({el:el, callback:callback, scope:scope});
    if (!el) return false;
    attachListener(el, callback, scope, view);
}

/**
 *  re-attach any listeners orhpaned for a given variable on a given view (such as a field being removed and then re-added
 * @param varname
 * @param view
 */
AH.updateListeners = function(varname, view, el)
{
    if (!view) view = AH.lastView;
    if (!AH.LISTENERS[view] || !AH.LISTENERS[view][varname] ) {
        return;
    }
    var l = AH.LISTENERS[view][varname],
        el = el || AH.getElement(varname, view);

    for(var i =0; i < l.length; i++)
    {
        var oldEl = l[i].el;
        //If the element attached to this event is no longer the current
        // element for this variable on this view, update it
        if (oldEl != el)
        {
            if (oldEl.type && oldEl.type.toUpperCase() == "CHECKBOX")
            {
                YAHOO.util.Event.removeListener(oldEl, "click", l[i].callback);
            }
            else {
                YAHOO.util.Event.removeListener(oldEl, "change", l[i].callback);
            }
            attachListener(el, l[i].callback, l[i].scope, view);
            AH.LISTENERS[view][varname][i] = {el:el, callback:l[i].callback, scope:l[i].scope};
        }
    }

}

AH.setDateTimeField = function(field, value)
{
	var Dom = YAHOO.util.Dom,
		SDU = SUGAR.util.DateUtils,
		id = field.id,
	    date = Dom.get(id + "_date"),
		hours = Dom.get(id + "_hours"),
		min = Dom.get(id + "_minutes"),
		mer = Dom.get(id + "_meridiem");

	value = SDU.roundTime(value);
	date.value = SDU.formatDate(value);
	if (mer){
		//set AM/PM field
		var h = SDU.formatDate(value, true, "h");
		var m = SDU.formatDate(value, true, "i");
		var a = SUGAR.expressions.userPrefs.timef.indexOf("A") != -1 ?
				SDU.formatDate(value, true, "A") : SDU.formatDate(value, true, "a");
		AH.setSelectedOption(hours, h);
		AH.setSelectedOption(min, m);
		AH.setSelectedOption(mer, a);
	} else {
		//24 Hour time
		var h = SDU.formatDate(value, true, "H");
		var m = SDU.formatDate(value, true, "i");
		AH.setSelectedOption(hours, h);
		AH.setSelectedOption(min, m);
	}
}

AH.setSelectedOption = function(field, value)
{
	for (var i = 0; i < field.options.length; i++)
	{
		if (field.options[i].value == value)
		{
			field.options[i].selected = true;
			break;
		}
	}
	return;
}

AH.showError = function(variable, error)
{
	// retrieve the variable
	var field = AH.getElement(variable);

	if ( field == null )
		return null;

	add_error_style(field.form.name, field, error, false);
}

AH.clearError = function(variable)
{
	// retrieve the variable
	var field = AH.getElement(variable);
	if ( field == null )
		return;

	for(var i in inputsWithErrors)
	{
		if (inputsWithErrors[i] == field)
		{
			if ( field.parentNode.className.indexOf('x-form-field-wrap') != -1 )
            {
				field.parentNode.parentNode.removeChild(field.parentNode.parentNode.lastChild);
            }
            else
            {
            	field.parentNode.removeChild(field.parentNode.lastChild);
            }
			delete inputsWithErrors[i];
			return;
		}
	}
}

AH.fireOnLoad = function(dep)
{
    AH.QUEUEDDEPS.push(dep);
}

AH.loadComplete = function()
{
    // First get a list of all the related values we are going to need,
    // request those from the server, and then fire all the queued dependencies
    var fields = [];
    for (var i = 0; i < AH.QUEUEDDEPS.length; i++)
    {
        fields = $.merge(fields, AH.QUEUEDDEPS[i].getRelatedFields());
    }

    AH.getRelatedFieldValues(fields);

    //Now fire all the queued dependencies
    for (var i = 0; i < AH.QUEUEDDEPS.length; i++)
    {
        SUGAR.forms.Trigger.fire.call(AH.QUEUEDDEPS[i].trigger);
    }
    AH.AFTER_LOAD_COMPLETE.fire();
}

/**
 * This function is used to cache a large set of related fields values at once.
 * @param fields
 */
AH.setRelatedFields = function(fields){
    for (var link in fields)
    {
        for (var type in fields[link])
        {
            AH.cacheRelatedField(link, type, fields[link][type]);
        }
    }
}

/**
 * Send a request to the server to retrieve the value of a related field.
 * @param array fields set of related field values to retrieve. The array should be objects in the format
 *  {
 *      link: "name_of_link_field,"
 *      type: (related, count, rollup*),
 *      relate: "name_of_related_field" (only required for related fields, not rollup or count)
 *  }
 */
AH.getRelatedFieldValues = function(fields, module, record)
{
    if (fields.length > 0){
        module = module || SUGAR.forms.AssignmentHandler.getValue("module");
        record = record || SUGAR.forms.AssignmentHandler.getValue("record");

        // Go from the back, because of the possible deletion of related type fields
        for (var i = fields.length - 1; i >= 0; i--)
        {
            //Related fields require a current related id
            if (fields[i].type == "related")
            {
                var linkDef = SUGAR.forms.AssignmentHandler.getLink(fields[i].link);
                if (linkDef && linkDef.id_name && linkDef.module) {
                    var idField = document.getElementById(linkDef.id_name);
                    if (idField && (idField.tagName == "INPUT" || idField.hasAttribute("data-id-value")))
                    {
                        fields[i].relModule = linkDef.module;
                        fields[i].relId = SUGAR.forms.AssignmentHandler.getValue(linkDef.id_name, false, true);

                        // If there is no relId, there is no point in querying for this field
                        if (fields[i].relId.length == 0)
                        {
                            // So we remove it
                            fields.splice(i, 1);
                        }
                    }
                }
            }
        }

        // If we removed all fields (related) no point in sending a request
        if (fields.length > 0)
        {
            var r = http_fetch_sync("index.php", SUGAR.util.paramsToUrl({
                module:"ExpressionEngine",
                action:"getRelatedValues",
                record_id: record,
                tmodule: module,
                fields: YAHOO.lang.JSON.stringify(fields),
                to_pdf: 1
            }));
            try {
                var ret = YAHOO.lang.JSON.parse(r.responseText);
                AH.setRelatedFields(ret);
                return ret;
            } catch(e){}
        }
    }

    return null;
}

/**
 * Used to retrieve a single related field value, either from the cache or from the server.
 * @param link
 * @param type
 * @param field
 * @param view
 */
AH.getRelatedField = function(link, ftype, field, view){
    if (!view)
        view = AH.lastView;
    else
        AH.lastView = view;


    if(!AH.LINKS[view][link])
        return null;

    var linkDef = SUGAR.forms.AssignmentHandler.getLink(link);
    var currId;
    if (linkDef.id_name)
    {
        currId = SUGAR.forms.AssignmentHandler.getValue(linkDef.id_name, false, true);
    }

    // Clear the Link cache when the old and new relIds are different
    if ((linkDef.relId || currId) && linkDef.relId != currId) {
        AH.clearRelatedFieldCache(link, view);
    }

    if (typeof(linkDef[ftype]) == "undefined"
        || (field && typeof(linkDef[ftype][field]) == "undefined")

        // make sure that at least one of old and new value of the relate field is not empty.
        // otherwise the cache considered invalid in case when both values are empty but have
        // different types (null, false, undefined or empty string)
        || (ftype == "related" && (linkDef.relId || !_.isUndefined(currId)) && linkDef.relId != currId)
    ){
        var params = {link: link, type: ftype};
        if (field)
            params.relate = field;
        AH.getRelatedFieldValues([params]);
        //Reload the link now that getRelatedFieldValues has been called.
        linkDef = SUGAR.forms.AssignmentHandler.getLink(link);
    }

    if (typeof(linkDef[ftype]) == "undefined")
        return null;
    //Everything but count requires specifying a related field to use, so make sure to check that field retrieved correctly
    if (field) {
        //If we didn't load the field we wanted, return null
        if (typeof(linkDef[ftype][field]) == "undefined")
            return null;
        else
            return linkDef[ftype][field];
    }

    return linkDef[ftype];

}

/**
 * When a relate field changes id, we need to clear the cache for that link.
 * @param link
 * @param view
 */
AH.clearRelatedFieldCache = function(link, view){
    if (!view) view = AH.lastView;

    if(!AH.LINKS[view][link])
        return false;

    delete (AH.LINKS[view][link]["relId"]);
    delete (AH.LINKS[view][link]["related"]);

    return true;
}

/**
 * @STATIC
 * Change the style attributes of the given variable.
 */
AH.setStyle = function(variable, styles)
{
	// retrieve the variable
	var field = AH.getElement(variable);
	if ( field == null )	return null;

	// set the styles
	for ( var property in styles ) {
		YAHOO.util.Dom.setStyle(field, property + "", styles[property]);
	}
}

/**
 * Resets all of the SugarLogic Form Handler variables and registers to clean any cruft from previously loaded views.
 */
AH.reset = function() {
    AH.VARIABLE_MAP = {};
    AH.LISTENERS = {};
    AH.LINKS = {};
    AH.LOCKS = {};
}

SUGAR.forms.FormExpressionContext = function(formName)
{
	this.formName = formName;
	if (typeof(AH.VARIABLE_MAP[formName]) == "undefined")
		AH.registerView(formName);
}

SUGAR.util.extend(SUGAR.forms.FormExpressionContext, SUGAR.expressions.ExpressionContext, {
	getValue: function(varname)
	{
		var SE = SUGAR.expressions, toConst = SE.ExpressionParser.prototype.toConstant;

		var value = "";

		//Relate fields are only string on the client side, so return the variable name back.
		if(AH.LINKS[this.formName][varname])
			value = varname;
		else
			value = AH.getValue(varname, this.formName);

        if(typeof(value) == 'number')
        {
            return toConst(value);
        }
		else if (typeof(value) == "string")
		{
			if ((/^(\s*)$/).exec(value) != null || value === "")
            {
				return toConst('""')
			}
            // test if value is a number or boolean
            else if ( SE.isNumeric(value) ) {
				return toConst(SE.unFormatNumber(value));
		    }
			// assume string
			else {
				return toConst('"' + value + '"');
			}
		} else if (typeof(value) == "object" && value != null && value.getTime) {
			//This is probably a date object that we must convert to an expression
			var d = new SE.DateExpression("");
			d.evaluate = function(){return this.value};
			d.value = value;
			return d;
		}


		return toConst('""');
	},
	setValue: function(varname, value)
	{
		AH.assign(varname, value, true);
	},
    getLink : function(varname)
    {
        if(AH.LINKS[this.formName][varname])
            return AH.LINKS[this.formName][varname];
        return false;
    },
    addListener : function(varname, callback, scope)
    {
    	AH.addListener(varname, callback, scope, this.formName);
    },
    getRelatedField : function(link, ftype, field){
        //For 'related' fields, the ID of the related record can be changed on the form so we need to look for it
        //before we call down to the server
        if (ftype == 'related')
        {
            //We just have a field name, assume its the name of a link field
            //and the parent module is the current module.
            //Try and get the current module and record ID
            var module = AH.getValue("module");
            var record = AH.getValue("record");
            var linkDef = AH.getLink(link);
            var linkId = false, url = "index.php?";

            if (linkDef && linkDef.id_name && linkDef.module) {
                var idField = document.getElementById(linkDef.id_name);
                if (idField && idField.tagName == "INPUT")
                {
                    linkId = AH.getValue(linkDef.id_name, false, true);
                    module = linkDef.module;
                }
                //Clear the cache for this link if the id has changed
                if (linkDef.relId && linkDef.relId != linkId)
                    AH.clearRelatedFieldCache(link);
            }
        }

        return AH.getRelatedField(link, ftype, field);
    },
    getAppListStrings : function(list) {
        return SUGAR.language.get('app_list_strings', list);
    },
    parseDate: function(date, type) {
        return SUGAR.util.DateUtils.parse(date, type);
    },
    getElement : function(variable) {
        return AH.getElement(variable, this.formName);
    },

    /**
     * Do math calculations in javascript,
     * sans floating point errors.
     *
     * ex. $10.52 is really 1052 cents. Think of currency as
     * cents and apply math that way (as integers)  and this should
     * help keep floating point issues out of the picture.
     *
     * @param {String} operator
     * @param {Number} n1
     * @param {Number|undefined} n2
     * @param {Number|undefined} (decimals)
     * @param {boolean|undefined} (fixed) return value as fixed string
     * @return {Number|String} rounded amount
     */
    _math: function(operator, n1, n2, decimals, fixed) {
        decimals = (_.isFinite(decimals) && decimals >= 0) ? parseInt(decimals) : 6;
        fixed = fixed || false;
        var result;
        var divisor = Math.pow(10, decimals);
        var r1 = parseFloat(n1) * divisor;
        var r2 = !_.isUndefined(n2) ? (parseFloat(n2) * divisor) : undefined;
        switch (operator) {
            case 'round':
                result = Math.round(r1) / divisor;
                break;
            case 'add':
                result = (r1 + r2) / divisor;
                break;
            case 'sub':
                result = (r1 - r2) / divisor;
                break;
            case 'mul':
                result = this.round(r1 * r2 / divisor / divisor, decimals, fixed);
                break;
            case 'div':
                result = this.round(r1 / r2, decimals, fixed);
                break;
            default:
                // no valid operator, just return number
                return n1;
                break;
        }
        return (fixed && !_.isString(result)) ? result.toFixed(decimals) : result;
    },
    /**
     * Used to Add Values
     *
     * @param {String|Number} start        What we are starting with
     * @param {String|Number} add          What we want to add to the value
     * @return {String}
     */
    add: function(start, add) {
        return this._math('add', start, add, 6, true);
    },
    /**
     * Used to Subtract Values
     *
     * @param {String|Number} start        What we are starting with
     * @param {String|Number} subtract          What we want to subtract from the value
     * @return {String}
     */
    subtract: function(start, subtract) {
        return this._math('sub', start, subtract, 6, true);
    },
    /**
     * Used to Multiply Values
     *
     * @param {String|Number} start        What we are starting with
     * @param {String|Number} multiply     What we want to multipy by
     * @return {String}
     */
    multiply: function(start, multiply) {
        return this._math('mul', start, multiply, 6, true);
    },
    /**
     * Used to Divide Values
     *
     * @param {String|Number} start        What we are starting with
     * @param {String|Number} divide       What we want to divide the currency value by
     * @return {String}
     */
    divide: function(start, divide) {
        return this._math('div', start, divide, 6, true);
    },
    /**
     * Used to Round Values
     *
     * @param {String|Number} start        What we are starting with
     * @param {String|Number} divide       What we want to divide the currency value by
     * @return {String}
     */
    round: function(start, precision) {
        return this._math('round', start, precision, true);
    }
});


/**
 * @STATIC
 * The Default expression parser.
 */
SUGAR.forms.DefaultExpressionParser = new SUGAR.expressions.ExpressionParser();

/**
 * @STATIC
 * Parses expressions given a variable map.<br>
 */
SUGAR.forms.evalVariableExpression = function(expression, varmap, view)
{
	return SUGAR.forms.DefaultExpressionParser.evaluate(expression, new SUGAR.forms.FormExpressionContext(view));
}

/**
 * Replaces range expressions with their values.
 * eg. '%a[1,10]' => '$a1,$a2,$a3,...,$a10'
 */
SUGAR.forms._performRangeReplace = function(expression)
{
	this.generateRange = function(prefix, start, end) {
		var str = "";
		var i = parseInt(start);
		if ( typeof(end) == 'undefined' )
			while ( AH.getElement(prefix + '' + i) != null )
				str += '$' + prefix + '' + (i++) + ',';
		else
			for ( ; i <= end ; i ++ ) {
				var t = prefix + '' + i;
				if ( AH.getElement(t) != null )
					str += '$' + t + ',';
			}
		return str.substring(0, str.length-1);
	}

	this.valueReplace = function(val) {
		if ( !(/^\$.*$/).test(val) )	return val;
		return AH.getValue(val.substring(1));
	}

	// flags
	var isInQuotes = false;
	var prev;
	var inRange;

	// go character by character
	for ( var i = 0 ;  ; i ++ ) {
		// due to fluctuating expression length
		if ( i == expression.length ) break;

		var ch = expression.charAt(i);

		if ( ch == '"' && prev != '\\' )	isInQuotes = !isInQuotes;

		if ( !isInQuotes && ch == '%' ) {
			inRange = true;

			// perform the replace
			var loc_start = expression.indexOf( '[' , i+1 );
			var loc_comma = expression.indexOf(',', loc_start );
			var loc_end   = expression.indexOf(']', loc_start );

			// invalid expression syntax?
			if ( loc_start < 0 || loc_end < 0 )	throw ("Invalid range syntax");

			// construct the pieces
			var prefix = expression.substring( i+1 , loc_start );
			var start, end;

			// optional param is there
			if ( loc_comma > -1 && loc_comma < loc_end ) {
				start = expression.substring( loc_start+1, loc_comma );
				end = expression.substring( loc_comma + 1, loc_end );
			} else {
				start = expression.substring( loc_start+1, loc_end );
			}

			// optional param is there
			if ( loc_comma > -1 && loc_comma < loc_end )	end = expression.substring( loc_comma + 1, loc_end );

			// construct the range
			var result = this.generateRange(prefix, this.valueReplace(start), this.valueReplace(end));

			// now perform the replace
			if ( typeof(end) == 'undefined' )
				expression = expression.replace('%'+prefix+'['+start+']', result);
			else
				expression = expression.replace('%'+prefix+'['+start+','+end+']', result);

			// skip on
			i = i + result.length - 1;
		}

		prev = ch;
	}

	return expression;
}

SUGAR.forms.getFieldsFromExpression = function(expression)
{
	var re = /[^$]*?\$(\w+)[^$]*?/g,
		matches = [],
		result;
	while (result = re.exec(expression))
	{
		matches.push(result[result.length-1]);
	}
	return matches;
}

/**
 * A dependency is an object representation of a variable being dependent
 * on other variables. For example A being the sum of B and C where A is
 * 'dependent' on B and C.
 */
SUGAR.forms.Dependency = function(trigger, actions, falseActions, testOnLoad, form)
{
	if (typeof(form) != "string")
		if (AH.lastView)
			form = AH.lastView;
		else
			form = "EditView";
	this.actions = actions;
	this.falseActions = falseActions;
	this.context = new SUGAR.forms.FormExpressionContext(form);
    trigger.setContext(this.context);
    trigger.setDependency(this);
	SUGAR.lastDep = this;
    this.trigger = trigger;
	if (testOnLoad) {
			AH.fireOnLoad(this);
	}

}


/**
 * Triggers this dependency to be re-evaluated again.
 */
SUGAR.forms.Dependency.prototype.fire = function(undo)
{
	try {
		var actions = this.actions;
		if (undo && this.falseActions != null)
			actions = this.falseActions;

		if (actions instanceof SUGAR.forms.AbstractAction) {
			actions.setContext(this.context);
			actions.exec();
		} else {
			for (var i in actions) {
				var action = actions[i];
				if (typeof action.exec == "function") {
					action.setContext(this.context);
					action.exec();
				}
			}
		}
	} catch (e) {
		if (!SUGAR.isIE && console && console.log){
			console.log('ERROR: ' + e);
		}
		return;
	}
};

SUGAR.forms.Dependency.prototype.getRelatedFields = function () {
    var parser = SUGAR.forms.DefaultExpressionParser,
        fields = parser.getRelatedFieldsFromFormula(this.trigger.condition);
    //parse will search a list of actions for formulas with relate fields
    var parse = function (actions) {
        if (actions instanceof SUGAR.forms.AbstractAction) {
            actions = [actions];
        }
        for (var i in actions) {
            var action = actions[i];
            //Iterate over all the properties of the action to see if they are formulas with relate fields
            if (typeof action.exec == "function") {
                for (var p in action) {
                    if (typeof action[p] == "string")
                        fields = $.merge(fields, parser.getRelatedFieldsFromFormula(action[p]));
                }
            }
        }
    }
    parse(this.actions);
    parse(this.falseActions);
    return fields;
}


    SUGAR.forms.AbstractAction = function (target) {
        this.target = target;
    };

    SUGAR.forms.AbstractAction.prototype.exec = function () {

    }

    SUGAR.forms.AbstractAction.prototype.setContext = function (context) {
        this.context = context;
    }

    SUGAR.forms.AbstractAction.prototype.evalExpression = function (exp, context) {
        return SUGAR.forms.DefaultExpressionParser.evaluate(exp, context).evaluate();
    }

    /**
     * Determines if actions is allowed to set new value on the record in the given context
     * @param {ExpressionContext} context
     * @returns {boolean}
     */
    SUGAR.forms.AbstractAction.prototype.canSetValue = function (context) {
        return true;
    };

    /**
     * This object resembles a trigger where a change in any of the specified
     * variables triggers the dependencies to be re-evaluated again.
     */
    SUGAR.forms.Trigger = function (variables, condition) {
        this.variables = variables;
        this.condition = condition;
        this.dependency = { };
        this._attachListeners();
    }

    /**
     * Attaches a 'change' listener to all the fields that cause
     * the condition to be re-evaluated again.
     */
    SUGAR.forms.Trigger.prototype._attachListeners = function () {
        var handler = AH;
        if (!(this.variables instanceof Array)) {
            this.variables = [this.variables];
        }

        for (var i = 0; i < this.variables.length; i++) {
            var el = handler.getCollection(this.variables[i]);
            if(!el) {
                var el = handler.getElement(this.variables[i]);
            }
            if (!el) continue;
            if (el.type && el.type.toUpperCase() == "CHECKBOX") {
                YAHOO.util.Event.addListener(el, "click", SUGAR.forms.Trigger.fire, this, true);
            } else {
                YAHOO.util.Event.addListener(el, "change", SUGAR.forms.Trigger.fire, this, true);
            }
        }
    }

    /**
     * Attaches a 'change' listener to all the fields that cause
     * the condition to be re-evaluated again.
     */
    SUGAR.forms.Trigger.prototype.setDependency = function (dep) {
        this.dependency = dep;
    }

    SUGAR.forms.Trigger.prototype.setContext = function (context) {
        this.context = context;
    }

    /**
     * @STATIC
     * This is the function that is called when a 'change' event
     * is triggered. If the condition is true, then it triggers
     * all the dependencies.
     */
    SUGAR.forms.Trigger.fire = function () {
        // eval the condition
        var eval;
        var val;
        try {
            eval = SUGAR.forms.DefaultExpressionParser.evaluate(this.condition, this.context);
        } catch (e) {
            if (!SUGAR.isIE && console && console.log) {
                console.log('ERROR:' + e + "; in Condition: " + this.condition);
            }
        }

        // evaluate the result
        if (typeof(eval) != 'undefined')
            val = eval.evaluate();

        // if the condition is met
        if (val == SUGAR.expressions.Expression.TRUE) {
            // single dependency
            if (this.dependency instanceof SUGAR.forms.Dependency) {
                this.dependency.fire(false);
                return;
            }
        } else if (val == SUGAR.expressions.Expression.FALSE) {
            // single dependency
            if (this.dependency instanceof SUGAR.forms.Dependency) {
                this.dependency.fire(true);
                return;
            }
        }
    }

    SUGAR.forms.flashInProgress = {};
    /**
     * @STATIC
     * Animates a field when by changing it's background color to
     * a shade of light red and back.
     */
    SUGAR.forms.FlashField = function (field, to_color) {
        if (typeof(field) == 'undefined')     return;

        if (SUGAR.forms.flashInProgress[field.id])
            return;
        SUGAR.forms.flashInProgress[field.id] = true;
        // store the original background color
        var original = field.style.backgroundColor;

        // default bg-color to white
        if (typeof(original) == 'undefined' || original == '') {
            original = '#FFFFFF';
        }

        // default to_color
        if (typeof(to_color) == 'undefined')
            var to_color = '#FF8F8F';

        // Create a new ColorAnim instance
        var oButtonAnim = new YAHOO.util.ColorAnim(field, { backgroundColor:{ to:to_color } }, 0.2);

        oButtonAnim.onComplete.subscribe(function () {
            if (this.attributes.backgroundColor.to == to_color) {
                this.attributes.backgroundColor.to = original;
                this.animate();
            } else {
                field.style.backgroundColor = original;
                SUGAR.forms.flashInProgress[field.id] = false;
            }
        });

        //Flash tabs for fields that are not visible.
        var tabsId = field.form.getAttribute("name") + "_tabs";
        if (typeof (window[tabsId]) != "undefined") {
            var tabView = window[tabsId];
            var parentDiv = YAHOO.util.Dom.getAncestorByTagName(field, "div");
            if (tabView.get) {
                var tabs = tabView.get("tabs");
                for (var i in tabs) {
                    if (i != tabView.get("activeIndex") && (tabs[i].get("contentEl") == parentDiv
                        || YAHOO.util.Dom.isAncestor(tabs[i].get("contentEl"), field))) {
                        var label = tabs[i].get("labelEl");

                        if (SUGAR.forms.flashInProgress[label.parentNode.id])
                            return;

                        var tabAnim = new YAHOO.util.ColorAnim(label, { color:{ to:'#F00' } }, 0.2);
                        tabAnim.origColor = Dom.getStyle(label, "color");
                        tabAnim.onComplete.subscribe(function () {
                            if (this.attributes.color.to == '#F00') {
                                this.attributes.color.to = this.origColor;
                                this.animate();
                            } else {
                                SUGAR.forms.flashInProgress[label.parentNode.id] = false;
                            }
                        });
                        SUGAR.forms.flashInProgress[label.parentNode.id] = true;
                        tabAnim.animate();
                    }
                }
            }
        }

        oButtonAnim.animate();
    }
})();
