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
SUGAR.expressions.initFormulaBuilder = function() {
	var Dom = YAHOO.util.Dom,
		Connect = YAHOO.util.Connect,
		Msg = YAHOO.SUGAR.MessageBox;
		
/**
 * @author dwheeler
 */
/**
 * Run through the javascript function cache to find all the loaded functions.
 */
SUGAR.expressions.getFunctionList = function()
{
	var typeMap = SUGAR.expressions.Expression.TYPE_MAP;
	var funcMap = SUGAR.FunctionMap;
	var funcList = [];
	for (var i in funcMap) {
		if (typeof funcMap[i] == "function" && funcMap[i].prototype){
			for (var j in typeMap){
				if (funcMap[i].prototype instanceof typeMap[j]) {
					funcList[funcList.length] = [i, j];
					break;
				}
			}
		}
	}
	return funcList;
};

    /**
     * Returns a list of functions that are recommended for users (instead of the complete list)
     */
SUGAR.expressions.getDisplayFunctionList = function() {
    var functionsArray = SUGAR.expressions.getFunctionList();
    var usedClasses = { };
    var ret = [];
    for (var i in functionsArray)
    {
        var fName = functionsArray[i][0];
        //Internal Sugar functions that most users will not find useful
        switch (fName) {
        case "isValidTime":
        case "isAlpha":
        case "doBothExist":
        case "isValidPhone":
        case "isRequiredCollection":
        case "isNumeric":
        case "isValidDBName":
        case "isAlphaNumeric":
        case "stddev":
        case "charAt":
        case "formatName":
        case "sugarField":
		case "forecastCommitStage":
        case "currencyRate":
            continue;
            break;
        }
        //For now, hide date functions in the formula builder as they are unstable.
        if (functionsArray[i][1] == "time")
            continue;
        if (usedClasses[SUGAR.FunctionMap[fName].prototype.className])
            continue;
        if (functionsArray[i][1] == "number")
            ret.push([functionsArray[i][0], "_number"]);
        else
            ret.push(functionsArray[i]);

        usedClasses[SUGAR.FunctionMap[fName].prototype.className] = true;
    }

    return ret;
}

/**
 * Pulls the current expression from the input field, replaces variables and validates through the parser.
 */
SUGAR.expressions.setReturnTypes = function(t, vMap)
{
	var see = SUGAR.expressions.Expression;
	if (t.type == "variable")
	{
		if(typeof(vMap[t.name]) == "undefined")
			throw ("Unknown field: " + t.name);
		else if(vMap[t.name] == "relate")
			t.returnType = SUGAR.expressions.Expression.GENERIC_TYPE;
		else
			t.returnType = vMap[t.name];
	}
	if (t.type == "function")
	{
		for(var i in t.args)
		{
			SUGAR.expressions.setReturnTypes(t.args[i], vMap);
		}
		var fMap = SUGAR.FunctionMap;
		if(typeof(fMap[t.name]) == "undefined")
			throw (t.name + ": No such function defined");
		for (var j in see.TYPE_MAP){
			if (fMap[t.name].prototype instanceof see.TYPE_MAP[j]) {
				t.returnType = j;
				break;
			}
		}

		// For the conditional function, if both argument return types are same, set the conditional type
		if (t.name == "ifElse") {
			var args = t.args;
			if (args[1].returnType == args[2].returnType) {
				t.returnType = args[1].returnType;
			}
		}

		if(!t.returnType)
			throw (t.name + ": No known return type!");
	}
}

    /**
     * Recursively checks the paramaers passed to all the functions in the formula.
     * @param t expression function
     */
SUGAR.expressions.validateReturnTypes = function(t)
{
	if (t.type == "function")
	{
		//Depth first recursion
		for(var i in t.args)
		{
			SUGAR.expressions.validateReturnTypes(t.args[i]);
		}
		
		var fMap = SUGAR.FunctionMap;
		var see = SUGAR.expressions.Expression;
		if(typeof(fMap[t.name]) == "undefined")
			throw (t.name + ": No such function defined");
		
		var types = fMap[t.name].prototype.getParameterTypes();
		var count = fMap[t.name].prototype.getParamCount();
		
		// check parameter count
		if ( count == see.INFINITY && t.args.length == 0 ) {
			throw (t.name + ": Requires at least one parameter");
		}
		if ( count != see.INFINITY && t.args instanceof Array && t.args.length != count ) {
			throw (t.name + ": Requires exactly " + count + " parameter(s)");
		}
		
		if ( typeof(types) == 'string' ) {
			for (var i = 0 ; i < t.args.length ; i ++ ) {
				if(!t.args[i].returnType)
					throw (t.name + ": No known return type!");
				if ( !fMap[t.name].prototype.isProperType(new see.TYPE_MAP[t.args[i].returnType],types)) {
					throw (t.name + ": All parameters must be of type '" + types + "'");
				}
			}
		}
		else {
			for ( var i = 0 ; i < types.length ; i++ ) {
				if ( !fMap[t.name].prototype.isProperType(new see.TYPE_MAP[t.args[i].returnType],types[i]) ) {
					throw (t.name + ": The parameter at index " + i + " must be of type " + types[i] );
				}
			}
		}
	}
};

    /**
     * validate functions with related fields which require a server call to validate
     * @param t expression function
     */
SUGAR.expressions.validateRelateFunctions = function(t)
{
	var SU = SUGAR.util, SE = SUGAR.expressions;
    if (t.type == "function")
	{
		//Depth first recursion
		for(var i in t.args)
		{
			SE.validateRelateFunctions(t.args[i]);
		}

		//these functions all take a link and a string for a related field
        var relFuncs = ["related", "rollupAve", "rollupMax", "rollupMin", "rollupSum"];

		if (SU.arrayIndexOf(relFuncs, t.name) == -1)
            return true;

        var url = "index.php?" + SU.paramsToUrl({
            module : "ExpressionEngine",
            action : "validateRelatedField",
            tmodule : ModuleBuilder.module,
            package : ModuleBuilder.MBpackage,
            link : t.args[0].name,
            related : t.args[1].value
        });
        var resp = http_fetch_sync(url);
        var def = YAHOO.lang.JSON.parse(resp.responseText);

        //Check if a field was found
        if (typeof(def) == "string"){
            throw(t.name + ": " + def);
        }
        if (t.name != "related" && def.type && SU.arrayIndexOf(["decimal", "int", "float", "currency"], def.type) == -1)
        {
            throw (t.name + ": related field  " + t.args[1].value + " must be a number ");
        }

        return def;
	}
};

    /**
     *  Grab and validate the current formula and validate it.
     *  Exceptions will be thrown and caught if any errors are found.
     * @param silent
     * @param matchType
     */
SUGAR.expressions.validateCurrExpression = function(silent, matchType)
{
	try {
		var varTypeMap = {};
		for (var i = 0; i < fieldsArray.length; i++){
			varTypeMap[fieldsArray[i][0]] = fieldsArray[i][1];
		}
		var expression = YAHOO.lang.trim(Dom.get('formulaInput').value);
		var tokens = new SUGAR.expressions.ExpressionParser().tokenize(expression);
		SUGAR.expressions.setReturnTypes(tokens, varTypeMap);
		SUGAR.expressions.validateReturnTypes(tokens);
		var def = SUGAR.expressions.validateRelateFunctions(tokens),
		    returnType = tokens.returnType;
		if (tokens.name == 'related' && def.type) {
		    switch(def.type) {
		        case "datetime":
                case 'datetimecombo':
		            returnType = "date"; break;
		        case "bool":
		            returnType = "boolean"; break;
		        default:
		            returnType = def.type;
		    }
		}
		if (matchType && matchType != returnType) {
			Msg.show({
                type: "alert",
                title: SUGAR.language.get("ModuleBuilder", "LBL_FORMULA_INVALID"),
                msg: SUGAR.language.get("ModuleBuilder", "LBL_FORMULA_TYPE") + matchType
            });
			return false;
		}
		
		if (typeof (silent) == 'undefined' || !silent) 
			Msg.show({msg: "Validation Sucessfull"});
		
		return true;
	} catch (e) {
			Msg.show({
                type: "alert",
                title: SUGAR.language.get("ModuleBuilder", "LBL_FORMULA_INVALID"),
                msg: YAHOO.lang.escapeHTML(e.message ? e.message : e)
            });
		return false;
	}
}

    /**
     * If the current formula is valid, save it into the target id.
     * @param target id of input element to save the formula into
     * @param returnType - optional - the required return type of the formula.
     */
SUGAR.expressions.saveCurrentExpression = function(target, returnType)
{
    var expression = YAHOO.lang.trim(Dom.get('formulaInput').value);

       /* Remove all blanks in expression that are not part of a string */
    var res="";
    var quote=0;
    for (var i=0; i<expression.length; i++) {
        var ch = expression.substr(i,1);
        if (ch=='"') {
            quote++;
        }
        if ((quote % 2) || ch != " ") {
            res += ch;
        }
    }
    Dom.get('formulaInput').value=res;

	if (!SUGAR.expressions.validateCurrExpression(true, returnType))
		return false;
	if (YAHOO.lang.isString(target))
		target = Dom.get(target);
	target.value = Dom.get("formulaInput").value;
	if (typeof target.onchange == "function")
	{
		target.onchange();
	}
	return true;
}

    /**
     * YUI Tooltip displayed when hovering over the function list.
     */
SUGAR.expressions.GridToolTip = {
	tipCache : { },
	currentHelpFunc : "",
	showFunctionDescription: function(tip, func) {
		var ggt = SUGAR.expressions.GridToolTip;
		if (ggt.currentHelpFunc == func)
			return;
		ggt.currentHelpFunc = func;
		var cache = ggt.tipCache;
		
		if (typeof cache[func] == 'string') {
			tip.cfg.setProperty("text", cache[func]);
		} else {
			cache[func] = "loading...";
			tip.cfg.setProperty("text", cache[func]);
			ggt.tip = tip;
			Connect.asyncRequest(
			    Connect.method, 
			    Connect.url + '&' + SUGAR.util.paramsToUrl({
			    	"function": func, 
			    	action: "functionDetail", 
			    	module: "ExpressionEngine"
			    }),
			    {success: ggt.showAjaxResponse, failure: function(){}}
			);
		}
	},
	showAjaxResponse: function (o) {
		var ggt = SUGAR.expressions.GridToolTip;
		var r = YAHOO.lang.JSON.parse(o.responseText);
		ggt.tipCache[r.func] = r.desc;
		if (r.func == ggt.currentHelpFunc) {
			ggt.tip.cfg.setProperty("text", r.desc);
		}
	}
};

	/*
	 * Set up the function and field list s
	 */
    var typeFormatter = function(el, rec, col, data)
	{
		var out = "";
		switch(data)
		{
			case "string":
				out = "string"; break;
			case "_number":
			case "number":
				out = "num"; break;
			case "time":
				out = "date"; break;
			case "enum":
				out = "enum"; break;
			case "boolean":
				out = "bool"; break;
			case "date":
				out = "date"; break;
			default:
				out = "generic";
		}
		el.innerHTML = '<img src="themes/default/images/SugarLogic/icon_' + out + '_16.png"></img>';
	};
	var fieldFormatter = function(el, rec, col, data)
	{
		el.innerHTML = "$" + data;
	};
	var visibleFields = [];
	var fieldsJSON =  [];
	var j = 0;
	for(var i in fieldsArray)
	{
		//Hide relate(link) fields from the user, but allow them to be used.
		if(fieldsArray[i][1] != "relate") {
			visibleFields[j] = fieldsArray[i];
			fieldsJSON[j] = {name: fieldsArray[i][0], type: fieldsArray[i][1]};
			j++;
		}
	}

	var fieldDS = new YAHOO.util.LocalDataSource(visibleFields, {
		responseType: YAHOO.util.LocalDataSource.TYPE_JSARRAY,
		responseSchema: {
		   resultsList : "relationships",
		   fields : ['name', 'type']
	    }
	});
	var fieldsGrid = new YAHOO.widget.ScrollingDataTable('fieldsGrid',
		[
		    {key:'name', label: "Fields", width: 200, sortable: true, formatter: fieldFormatter},
		    {key:'type', label: "&nbsp;", width: 20, sortable: true, formatter:typeFormatter}
		],
		fieldDS,
	    {height: "200px", MSG_EMPTY: SUGAR.language.get('ModuleBuilder','LBL_NO_FIELDS')}
	);
	fieldsGrid.on("rowClickEvent", function(e){
		var record = this.getRecord(e.target);
		Dom.get("formulaInput").value += "$" + record.getData().name;
	});
	fieldsGrid.on("sortedByChange", function(e){
		if(e.newValue)
			fieldsGrid.sortedColumn = e.newValue;
	});
	
	fieldDS.queryMatchContains = true;
	var fieldAC = new YAHOO.widget.AutoComplete("formulaFieldsSearch","fieldSearchResults", fieldDS);
	fieldAC.doBeforeLoadData = function( sQuery , oResponse , oPayload ) {
		fieldsGrid.initializeTable();
		fieldsGrid.addRows(oResponse.results);
		fieldsGrid.sortColumn(fieldsGrid.sortedColumn.column, fieldsGrid.sortedColumn.dir);
		fieldsGrid.render();
    }


	Dom.get("formulaFieldsSearch").onkeyup = function() {
		if (this.value == '') {
			fieldsGrid.initializeTable();
			fieldsGrid.addRows(fieldsJSON);
			fieldsGrid.sortColumn(fieldsGrid.sortedColumn.column, fieldsGrid.sortedColumn.dir);
			fieldsGrid.render();
		} // if
	}
	Dom.get("formulaFieldsSearch").onfocus = function() {
		if (Dom.hasClass(this, "empty"))
		{
			this.value = '';
			Dom.removeClass(this, "empty");
		}
	}
	Dom.get("formulaFieldsSearch").onblur = function() {
		if (this.value == '')
		{
			this.value = SUGAR.language.get("ModuleBuilder", "LBL_SEARCH_FIELDS");
			Dom.addClass(this, "empty");
		}
	}
	fieldsGrid.sortColumn(fieldsGrid.getColumn(0))
	fieldsGrid.render();
	SUGAR.expressions.fieldGrid = fieldsGrid;

    //Setup the function list
    if (!SUGAR.expressions.funcGridData) {
        SUGAR.expressions.funcGridData = SUGAR.expressions.getDisplayFunctionList();
    }

	var funcDS = new YAHOO.util.LocalDataSource(SUGAR.expressions.funcGridData, 
	{
		responseType: YAHOO.util.LocalDataSource.TYPE_JSARRAY,
		responseSchema: 
		{
		   resultsList : "relationships",
		   fields : ['name', 'type']
	    }
	});
	var fg = SUGAR.expressions.functionsGrid = new YAHOO.widget.ScrollingDataTable('functionsGrid',
		[
		    {key:'name', label: "Functions", width: 200, sortable: true},
		    {key:'type', label: "&nbsp;", width: 20, sortable: true, formatter:typeFormatter}
		],
		funcDS,
	    {height: "200px", MSG_EMPTY: SUGAR.language.get('ModuleBuilder','LBL_NO_FUNCS')}
	);
	
	fg.on("rowClickEvent", function(e){
		var record = this.getRecord(e.target);
		Dom.get("formulaInput").value +=  record.getData().name + '(';
	});
	fg.on("sortedByChange", function(e){
		if(e.newValue)
		    SUGAR.expressions.functionsGrid.sortedColumn = e.newValue;
	});

    //If the expression tooltip exists, destroy it to re-initialize
    if(SUGAR.expressions.tooltip){
	    SUGAR.expressions.tooltip.destroy();
    }
    var funcTip = SUGAR.expressions.tooltip = new YAHOO.widget.Tooltip("functionsTooltip", {
        context: "functionsGrid",
        text: "",
        showDelay: 300,
        zindex: ModuleBuilder.formulaEditorWindow ? ModuleBuilder.formulaEditorWindow.cfg.getProperty("zindex") + 2 : 27
    });
	
	funcTip.table = fg;
	
	funcTip.contextMouseOverEvent.subscribe(function(context, e){
		var target =  e[1].srcElement  ? e[1].srcElement : e[1].target;
		if ((Dom.hasClass(target, "yui-dt-bd"))) {return false;}
		
		var row = this.table.getRecord(target);
		if (!row) {return false;}
		
		if (this.timer)
			this.timer.cancel();
		
		this.timer = YAHOO.lang.later(250, this, function(funcName){
			SUGAR.expressions.GridToolTip.showFunctionDescription(this, funcName);
		}, row.getData()['name']);
		
		return true;
	});
	funcDS.queryMatchContains = true;
	var funcAC = new YAHOO.widget.AutoComplete("formulaFuncSearch","funcSearchResults", funcDS);
	funcAC.doBeforeLoadData = function( sQuery , oResponse , oPayload ) {
		var fg = SUGAR.expressions.functionsGrid;
		fg.initializeTable();
		fg.addRows(oResponse.results);
		fg.sortColumn(fg.sortedColumn.column, fg.sortedColumn.dir);
		fg.render();
    }
	if (!SUGAR.expressions.funcionListJSON)
	{
		SUGAR.expressions.funcionListJSON =  [];
		for(var i in SUGAR.expressions.funcGridData)
		{
			SUGAR.expressions.funcionListJSON[i] = {name: SUGAR.expressions.funcGridData[i][0], type: SUGAR.expressions.funcGridData[i][1]};
		}
	}
	//Setup the listeners for the search function input
    Dom.get("formulaFuncSearch").onkeyup = function() {
		if (this.value == '') {
			Dom.addClass(this, "empty");
			var fg = SUGAR.expressions.functionsGrid;
			fg.initializeTable();
			fg.addRows(SUGAR.expressions.funcionListJSON);
			fg.sortColumn(fg.sortedColumn.column, fg.sortedColumn.dir);
			fg.render();
		}
	}
	Dom.get("formulaFuncSearch").onfocus = function() {
		if (Dom.hasClass(this, "empty"))
		{
			this.value = '';
			Dom.removeClass(this, "empty");
		}
	}
	Dom.get("formulaFuncSearch").onblur = function() {
		if (this.value == '')
		{
			this.value = SUGAR.language.get("ModuleBuilder", "LBL_SEARCH_FUNCS");
			Dom.addClass(this, "empty");
		}
	}
	fg.render();
	fg.sortColumn(fg.getColumn(1));

	Dom.setStyle(Dom.get("formulaBuilder").parentNode, "padding", "0");
	
	if(ModuleBuilder && ModuleBuilder.formulaEditorWindow)
		ModuleBuilder.formulaEditorWindow.center();


    //Update functions for the rel field and rollup wizards.
    //These must be global as the windows themselves will call them on change
    SUGAR.expressions.updateSelRFLink = function(link)
    {
        var win = SUGAR.formulaRelFieldWin;
        win.params = {
            module:"ExpressionEngine",
            action:"selectRelatedField",
            tmodule:ModuleBuilder.module,
            selLink:link,
            package:ModuleBuilder.MBpackage
        };
        win.load(ModuleBuilder.paramsToUrl(win.params), null, function(){win.center();});
    };

    SUGAR.expressions.updateRollupWizard = function(link, type)
    {
        var win = SUGAR.rollupWindow;
        win.params = {
            module:"ExpressionEngine",
            action:"rollupWizard",
            tmodule:ModuleBuilder.module,
            selLink:link,
            type:type,
            package:ModuleBuilder.MBpackage
        };
        win.load(ModuleBuilder.paramsToUrl(win.params), null, function(){win.center();});
    };

    SUGAR.expressions.insertRollup = function(){
        if ($('#rollwiz_rfield').val()){
            $.markItUp({
                target : "#formulaInput",
                closeWith: 'rollup' + $("#rollwiz_type").val() + '($' + $('#rollwiz_rmodule').val() + ', "' + $('#rollwiz_rfield').val() + '")'
            });
        }
        SUGAR.rollupWindow.hide();
    }

    SUGAR.expressions.insertRelated = function(){
        $.markItUp({
            target : "#formulaInput",
            closeWith:"related($" + $("#selrf_rmodule").val() + ",\"" + $("#selrf_rfield").val() + "\")"
        });
        SUGAR.formulaRelFieldWin.hide()
    }

    /**
     * Set up the markitup on the formula input element.
     * We want to allow tabs but don't override any other normal textbox actions
     * The wizard buttons are set up here as well
     */
    $("#formulaInput").markItUp({
    	onShiftEnter:  	{keepDefault:true},
    	onCtrlEnter:  	{keepDefault:true},
    	onTab:    		{keepDefault:false, replaceWith:'    '},
    	markupSet:  [{
            name:SUGAR.language.get("ModuleBuilder", "LBL_RELATED_FIELD"),
            className:'rel_field button',
            beforeInsert:function(){
                if (!SUGAR.formulaRelFieldWin)
                    SUGAR.formulaRelFieldWin = new YAHOO.SUGAR.AsyncPanel('relatedFieldWindow', {
                        width: 400,
                        draggable: true,
                        close: true,
                        constraintoviewport: true,
                        fixedcenter: false,
                        script: false,
                        modal: true
                    });
                var win = SUGAR.formulaRelFieldWin;
                win.setHeader(SUGAR.language.get("ModuleBuilder", "LBL_FORMULA_BUILDER"));
                win.setBody("loading...");
                win.render(document.body);
                SUGAR.expressions.updateSelRFLink("");
                win.show();
                win.center();
            }
        },{
            name:SUGAR.language.get("ModuleBuilder", "LBL_ROLLUP"),
            className:'rollup button',
            beforeInsert:function(){
                if (!SUGAR.rollupWindow)
                    SUGAR.rollupWindow = new YAHOO.SUGAR.AsyncPanel('rollupWindow', {
                        width: 400,
                        draggable: true,
                        close: true,
                        constraintoviewport: true,
                        fixedcenter: false,
                        script: false,
                        modal: true
                    });
                var win = SUGAR.rollupWindow;
                win.setHeader(SUGAR.language.get("ModuleBuilder", "LBL_FORMULA_BUILDER"));
                win.setBody("loading...");
                win.render(document.body);
                SUGAR.expressions.updateRollupWizard("", "");
                win.show();
                win.center();
            }
        }]
    });

    /****************
     * This section sets up the formula builder autocomplete
     */

    // Find the max z-index and use it for the autocomplete popup/tooltip
    var maxZ = Math.max.apply(
            null,
            // Apply a function to all elements of body to return z-index
            $.map(
                $('body > *'),
                function(element, index)
                {
                    return parseInt($(element).css('z-index')) || 1;	
                }
            )
        );

    // Create the auto-complete wrapper
    if ($("#fb_ac_wrapper").length == 0)
    {
        $("body").append(
            "<input id='fb_ac_input' style='display: none; z-index: " + maxZ + "; position: relative'>" +
            "<div id='fb_ac_wrapper' style='position: absolute;'>" +
                "<div id='fb_ac_spacer'></div>" +
            "</div>"
        )
        $("#fb_ac_wrapper").position({ my: "left top", at: "left top", of: "#formulaInput" });
    }
    
    var fb_ac_open = false;
    /**
     * Gets the index of the first character in the current formula component (function or variable)
     * @param val string formula
     * @param offset current cursor position
     */
    var getCompStart = function(val, offset)
    {
        var start = 0,
            chars = {",":"", ".":"", "(":"", ")":""};
        for( var c in chars)
        {
            var pos = val.lastIndexOf(c, offset - 1);
            if (pos !== false && pos > start)
                start = pos + 1;
        }
        return start;
    };
    /**
     * Gets the index of the last character in the current formula component (function or variable)
     * @param val string formula
     * @param integer offset current cursor position
     */
    var getCompEnd = function(val, offset)
    {
        var end = val.length;
        for( var c in {",":0, ".":0, "(":0, ")":0})
        {
            var pos = val.indexOf(c, offset);
            if (pos > -1 && pos < end)
                end = pos;
        }
        return end;
    };

    /**
     * Returns the text of the current formula component (function or variable)
     * @param string val formula
     * @param integer offset current cursor position
     */
    var getComponentText = function(val, offset)
    {
        var target = $("#formulaInput")[0];
        val = typeof(val) == "undefined" ? $("#formulaInput").val() : val;
        offset = typeof(offset) == "undefined" ? target.selectionEnd : offset;
        //Start by getting everything back to the next comma, period, or (
        var start = getCompStart(val, offset);
        if (start > offset)
            start = offset;
        //Now find where the end point of the current string is
        var end = getCompEnd(val, offset);

        return $.trim(val.substring(start, end));
    };

    /**
     * Used to find the parent function of the current component
     * Walks back through the string with a counter finding the first open paren without a close.
     * @param string val formula
     * @param integer offset current cursor position
     */
    var getOpenParenIndex = function(val, offset){
        var commas = 0, count = 0, inQuotes = false;
        for (var i = offset; i > -1; i--)
        {
            if (inQuotes && val[i] != '"')
                continue;
            else if (val[i] == '"')
                inQuotes = !inQuotes;
            else if (val[i] == "(")
            {
                if (count > 0)
                    count--;
                else
                    return [i, commas];
            }
            else if (val[i] == ")")
                count++;
            else if(val[i] == "," && count == 0)
                commas++;
        }
        return -1;
    };

    /**
     * Returns the expected return type based on the current cursor position in the formula
     */
    var getExpectedComponentType = function()
    {
        var target = $("#formulaInput")[0],
            val = $("#formulaInput").val(),
            offset = target.selectionEnd - 1,
            start = getCompStart(val, offset);
        if (start > offset)
            start = offset;

        //Find out the name of the calling function
        var lastParen = getOpenParenIndex(val, start);
        if (lastParen != -1)
        {
            var parent = getComponentText(val, lastParen[0] - 1);
            var fMap = SUGAR.FunctionMap;
            var see = SUGAR.expressions.Expression;

            if(typeof(fMap[parent]) == "undefined")
            {
                console.log("unknown parent function: " + parent);
                return false;
            };

            var types = fMap[parent].prototype.getParameterTypes();
            var count = fMap[parent].prototype.getParamCount();
            if (count != -1 && lastParen[1] >= count)
            {
                console.log("too many arguments!");
                return false;
            }
            if($.isArray(types))
            {
                return types[lastParen[1]];
            }
            return types;
        }

        //If we have dropped down here, we have no idea what the return should be.
        return "generic";
    };

    /**
     * Return an array of all the field names from the current module of a given type
     * Optionally it can take a search string to filter the fields by name
     * @param type optional type of fields to return
     * @param search string optional returns only fields containing the given string
     * @param limit integer optional maximum number of items to return.
     */
    var getFieldsByType = function(type, search, limit ){
        if (!type)
            type = "generic";
        if (search)
            search  = search.toLowerCase();

        var ret = [];
        for(var i = 0; i < fieldsArray.length; i++)
        {
            var f = fieldsArray[i];
            if ((type == "generic" || f[1] == type)
                && (!search || f[0].toLowerCase().indexOf(search) > -1)
            ){
                ret.push(f[0]);
            }

            if (limit && ret.length >= limit)
                break;
        }
        return ret;
    };

    var displayFunctions = SUGAR.expressions.getDisplayFunctionList();
    /**
     * Return an array of all the function names from the current module of a given type
     * Optionally it can take a search string to filter the fields by name
     * @param type optional type of fields to return
     * @param search string optional returns only fields containing the given string
     * @param limit integer optional maximum number of items to return.
     */
    var getFunctionsByType = function(type, search, limit)
    {
        if (!type)
            type = "generic";
        if (search)
            search  = search.toLowerCase();

        var ret = [],
            fMap = SUGAR.FunctionMap,
        	see = SUGAR.expressions.Expression;
        for(var i = 0; i < displayFunctions.length; i++)
        {
            try{
                if ((!search || displayFunctions[i][0].toLowerCase().indexOf(search) > -1)
                    && fMap[displayFunctions[i][0]]
                    && see.prototype.isProperType(fMap[displayFunctions[i][0]].prototype, type)
                ){
                    ret.push(displayFunctions[i][0]);
                }
            }catch(e){
                console.log(i);
            }

            if (limit && ret.length >= limit)
                break;
        }
        return ret;
    }

    /**
     * replace the contents on the hiden spacers that float left and push the ac widget in line with the cursor
     */
    var updateACSpacer = function()
    {
        var val = $("#formulaInput").val(),
            rows = val.substring(0, $("#formulaInput")[0].selectionEnd, true).split("\n"),
            html = "";

        for(var i=0; i < rows.length; i++)
        {
            //The last row should be trimmed to just the content before the start of the current component
            if (i == rows.length - 1)
            {
                //If this is the first component in this row, just leave any leading whitespace
                var start = getCompStart(rows[i], rows[i].length - 1);
                if (start == 0){
                    rows[i] = new RegExp("^\\s*").exec(rows[i])[0];
                } else {
                    rows[i] = rows[i].substring(0, start);
                }
            }
            var line = htmlentities(rows[i], "ENT_NOQUOTES").replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;").replace(/ /g, "&nbsp;");
            html += "<div class='fb_ac_spacer"+ (i != rows.length - 1 ? " fb_ac_spacer_line'>" : "'>") + line + "</div>";
        }

        //Clear the old spacer
        $("#fb_ac_wrapper .fb_ac_spacer").remove();
        //Insert the new spacer before the autocomplete widget
        $("#fb_ac_wrapper ul.ui-autocomplete").before(html);
    }

    // Use (maxZInput + 2) so that we guarantee that the help tooltip will be on top
    // because one more element is generated using the #fb_ac_input z-index + 1
    var maxZTooltip = maxZ + 2;
    
    // Create the auto-complete tooltip
    if ($("#fb_ac_help").length == 0)
    {
        $('body').append("<div id='fb_ac_help' style='z-index: " + maxZTooltip + ";' class='fb_ac_help'></div>'");
    }

    var hideACHelp = function()
    {
        $("#fb_ac_help").css("visibility", "hidden");
    };

    var showACHelp = function(func)
    {
        var ggt = SUGAR.expressions.GridToolTip,
            cache = ggt.tipCache,
            div = $("#fb_ac_help");

        if (ggt.currentHelpFunc == func && div.css("visibility") != "hidden")
            return;
        ggt.currentHelpFunc = func;

        var do_show = function(){
            //Don't show the help if the AC widget was closed during the 300ms wait
            if (!fb_ac_open) return;
            if (typeof cache[func] == 'string') {
                div.html(cache[func]);
            } else {
                div.html("loading...");
                $.ajax({
                    url:Connect.url,
                    data : {
                        "function": func,
                        action: "functionDetail",
                        module: "ExpressionEngine"
                    },
                    success: function(data){
                        var desc = $.parseJSON(data).desc;
                        cache[func] = desc;
                        div.html(desc);
                    }
                });
            }
            div.position({
                my : "left top",
                at : "right top",
                of : "#fb_ac_wrapper ul.ui-autocomplete"
            });
            div.css("visibility", "visible");
        }
        //Force a 300ms timer on actually showing the help window
        if (SUGAR.expressions.fb_ac_help_timer)
           window.clearTimeout(SUGAR.expressions.fb_ac_help_timer);

       //Use a 300ms timer before showing/updating the autocomplete
       SUGAR.expressions.fb_ac_help_timer = window.setTimeout(do_show, 300);
    };


    var acMode = "functions";
    //Initialize the Autocomplete. It will used a hidden input that is updated by a listener on the formula input
    $( "#fb_ac_input" ).autocomplete({
        source: function(e, fn){
            var expectedType = getExpectedComponentType();
            //If expected type is false, we shouldn't show the autocomplete
            if (expectedType === false)
                return false;

            //Fields
            if(e.term[0] == "$")
            {
                fn(getFieldsByType(expectedType, e.term.substr(1), 10));
                acMode = "fields";
            }
            else {
                fn(getFunctionsByType(expectedType, e.term, 10));
                acMode = "functions";
            }
        },
        appendTo: "#fb_ac_wrapper",
        position: { my : "left top", at: "left top"},
        open: function(event, ui) {
            fb_ac_open = true;
            //Set the content of the spacer to the same as the formula input to offset the autocomplete location by that amount
            updateACSpacer();

            $('#fb_ac_wrapper ul.ui-autocomplete').addClass('fb_ac_menu');
            $('.fb_ac_menu').css('z-index', maxZ + 3);
            var liDiff = $('.fb_ac_menu li').outerWidth() - $('.fb_ac_menu li').width();
            var ulDiff = parseInt($('.fb_ac_menu').css("border-left-width")) + parseInt($('.fb_ac_menu').css("border-right-width"));
            $('.fb_ac_menu li').width($('.fb_ac_menu li').width() - ulDiff - liDiff);

            hideACHelp();
        },
        close: function(){
            fb_ac_open = false;
            hideACHelp();
        },
        select: function(event, ui) {
            //On selection, replace the current element in the formula with the selection
            var target = $("#formulaInput"),
                el = target[0],
                val = target.val(),
                offset = el.selectionEnd,
                start = getCompStart(val, offset),
                end = getCompEnd(val, offset),
                comp = getComponentText(),
                selected = ui.item.value,
                cursorOffset = 0;
                if (start > offset)
                    start = offset;

            //Fields need the dollar sign replaced
            if (comp[0] == "$")
                selected = "$" + selected;
            else if (val[getCompEnd(val, offset)] != "("){
                selected += "(";
                cursorOffset = 1;
            }

            var begin = val.substring(0, start);
            var ending = val.substring(end);
            //Refill any whitespace in front and back of the component
            var ws = new RegExp("^(\\s+)[!\s]*").exec(val.substring(start, end));
            if (ws) selected = ws[0] + selected;

            ws = new RegExp("[!\s]*(\\s+)$").exec(val.substring(start, end));
            if (ws) selected += ws[0];


            //slice the selected item into the formula
            target.val(begin + selected + ending);
            end = getCompEnd(target.val(), offset) + cursorOffset,
            el.setSelectionRange(end, end);
            fb_ac_open = false;
            hideACHelp();
        },
        focus: function(event, ui) {
            hideACHelp();
            if (ui.item && acMode == "functions")
                showACHelp(ui.item.value);
        }
    });

    //On keyup on the formula input, update the autocomplete widget after a 300ms delay
    $("#formulaInput").keyup(function(e){
        $("#fb_ac_input").val(getComponentText());
        //$("#fb_ac_input").trigger(e);
        if(!(e.keyCode == 38 || e.keyCode == 40) && e.keyCode != 13 && e.keyCode != 27)
        {
            if (SUGAR.expressions.fb_ac_timer)
                window.clearTimeout(SUGAR.expressions.fb_ac_timer);

            //Use a 300ms timer before showing/updating the autocomplete
            SUGAR.expressions.fb_ac_timer = window.setTimeout(function(){
                //Reposition the autocomplete wrapper if it closed as the window may have moved
                if (!fb_ac_open){
                    $("#fb_ac_wrapper").position({ my : "left top", at: "left top", of: "#formulaInput", collision:"none"});
                }
                //DO not open the auto complete for moving the curser. Only modify it if its already open
                if ((e.keyCode != 37 && e.keyCode != 39) || fb_ac_open){
                    $( "#fb_ac_input" ).autocomplete("search", getComponentText());
                }
            }, 300);
        }
    })

    //Pass keydown events from the formula input to the autocomplete
    $("#formulaInput").keydown(function(e){
        //Prevent arrow key default when the autocomplete is visible and pass it the event
        if ((e.keyCode == 38 || e.keyCode == 40) && fb_ac_open){
            e.preventDefault();
        }

        if(fb_ac_open)
            $('#fb_ac_input').trigger(e);
    })

    //Allow the user to click out of the autocomplete
    $("body").mousedown(function() {
        var $input = $("#fb_ac_input");
        if ($input.autocomplete("instance")) {
            $input.autocomplete("close");
        }
    });
    //Need to prevent the body from seeing mousedown events to the AC widget
    $("#fb_ac_wrapper").mousedown(function(){return false});

    //On close, destroy the autocomplete
    SUGAR.expressions.closeFormulaBuilder = function()
    {
        $('#fb_ac_input').autocomplete( "destroy" );
        $("#fb_ac_wrapper").remove();
        ModuleBuilder.formulaEditorWindow.hide();
    }



};
