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

SUGAR.forms = SUGAR.forms || {};
SUGAR.forms.animation = SUGAR.forms.animation || {};

/**
 * An expression context is used to retrieve variables when evaluating expressions.
 * the default class only returns the empty string.
 */
var SE = SUGAR.expressions;
var App = SUGAR.App || App;
/**
 * SugarLogic ExpressionContext for Sidecar.
 * @type {SUGAR.expressions.SidecarExpressionContext}
 *
 * @param view {App.view.View} Sidecar view to attach to
 * @param model {App.data.Bean} optional Primary Bean object to operate on. If not provided it will be
 * pulled from the view.
 *
 * @param collection {App.data.beanCollection} optional Collection object to use instead of a single primary
 * model.
 */
var SEC = SE.SidecarExpressionContext = function (view, model, collection) {
    this.view = view;
    if (collection instanceof App.data.beanCollection) {
        this.collection = collection;
    } else {
        this.collection = view.collection || view.context.get('collection');
    }

    this.model = model || view.model || view.context.get('model');
};

App.utils.extendFrom(SEC, SE.ExpressionContext, {

    /**
     * Ability to clean up error handlers
     */
    dispose: function () {
        if (!this.view.disposed) {
            var scContext = this.view.context;
            _.each(this.dependencies, function (dep) {
                scContext.off('list:editrow:fire', null, dep);
            });
        }
    },

    /**
     * Initialize SugarLogic
     *
     * @param {Array} depsMeta
     * @param {bool} isCreate
     */
    initialize: function (depsMeta, isCreate) {
        var relatedFields = [];
        var isCreate = isCreate || false;
        var scContext = this.view.context;

        this.dependencies = [];

        /**
         * @param {Array} models
         * @param {Object} trigger
         */
        var updateCollection = _.bind(function(models, trigger) {
            _.each(models, function(model) {
                if (trigger.dependency.testOnLoad || model.isNew()) {
                    trigger.context.isOnLoad = true;
                    trigger.context.inCollection = true;
                    SUGAR.forms.Trigger.fire.apply(trigger, [model]);
                    trigger.context.isOnLoad = null;
                    trigger.context.inCollection = null;
                }
            }, this);
        }, this);


        _.each(depsMeta, function(dep) {
            var newDep = SUGAR.forms.Dependency.fromMeta(dep, this);
            if (newDep) {
                relatedFields = _.union(relatedFields, newDep.getRelatedFields());

                // We need to re-run this code on render. See below.
                if (scContext.isCreate() || newDep.fireLinkOnlyDependency() || isCreate) {
                    SUGAR.forms.Trigger.fire.apply(newDep.trigger);
                    this.view.trigger('sugarlogic:initialize');
                }

                //We need to fire onLoad dependencies when a row toggles
                if (newDep.testOnLoad) {
                    scContext.on('list:editrow:fire', function() {
                        scContext.isOnLoad = true;
                        SUGAR.forms.Trigger.fire.apply(this.trigger, arguments);
                        scContext.isOnLoad = null;
                    }, newDep);
                }

                if (this.collection instanceof SUGAR.App.data.beanCollection) {
                    //For views with collections, we need to trigger onLoad dependencies during sync
                    //For dependent fields and other actions which work outside of edit.
                    this.collection.on('sync', function(synced, syncData) {
                        var models;
                        var ids;
                        if (synced instanceof SUGAR.App.data.beanCollection) {
                            //only update the changed set when we have a list
                            ids = _.pluck(syncData, 'id');
                            models = synced.filter(function(model) {
                                return _.contains(ids, model.id);
                            });

                            //Use defer to prevent script timeouts on large lists
                            _.defer(updateCollection, models, newDep.trigger);
                        } else {
                            updateCollection([synced], newDep.trigger);
                        }
                    }, this);
                    this.collection.on('add', function(model){updateCollection([model], newDep.trigger)}, this);
                    updateCollection(this.collection.models, newDep.trigger);
                }

                this.dependencies.push(newDep);
            }
        }, this);

        this._setupLinks(relatedFields);
        this._requestRollups(relatedFields);

        // Fields are initialized upon view `render` and they can be
        // setting a default value at this time. We need to run this
        // code again.
        this.view.on('render', function () {
            _.each(this.dependencies, function (dep) {
                if (scContext.isCreate() || dep.fireLinkOnlyDependency()) {
                    SUGAR.forms.Trigger.fire.apply(dep.trigger);
                    this.view.trigger('sugarlogic:initialize');
                }
            }, this);
        }, this);
    },

    getValue : function(varname)
    {
        var value = this.model.get(varname),
            def =   this.model.fields[varname],
            result;

        if (!def) {
            SUGAR.App.logger.warn('Attempted to get a value for field ' + varname
                + ' which is not a field in ' + this.model.module);
        }

        //Relate fields are only string on the client side, so return the variable name back.
        // make sure def is defined first
        if (def && def.type == 'link') {
            value = varname;
        }

        if (typeof(value) == "string")
        {
            value = value.replace(/\n/g, "");
            if ((/^(\s*)$/).exec(value) != null || value === "")
            {
                result = SEC.parser.toConstant('""');
            }
            // test if value is a number or boolean but not a currency value, currency always needs to be a string
            // enum values should be treated as strings, so let enum values fall into the final else
            else if ((def.type !== 'currency' && def.type !== 'enum') && SE.isNumeric(value)) {
                result = SEC.parser.toConstant(SE.unFormatNumber(value));
            } else if (def.type == "date" || def.type == "datetime") {
                value = App.date.stripIsoTimeDelimterAndTZ(value);
                value = App.date.parse(value);
                value.type = def.type;
                result = this.getDateExpression(value);
            }
            // assume string
            else {
                result =  SEC.parser.toConstant('"' + value + '"');
            }
        } else if (typeof(value) == "object" && value != null && value.getTime) {
            //This is probably a date object that we must convert to an expression
            result = this.getDateExpression(value);
        } else if (typeof(value) == "number") {
            //Cast to a string before send to toConstant.
            result =  SEC.parser.toConstant("" + value);
        } else if (_.isBoolean(value)) {
            result =  value ? new SUGAR.expressions.TrueExpression() : new SUGAR.expressions.FalseExpression();
        } else {
            result = SEC.parser.toConstant('""');
        }

        return result;

    },
    setValue : function(varname, value)
    {
        if (typeof(this.model.fields[varname]) != 'object') {
            return;
        }
        var targetDef = this.model.fields[varname];

        this.lockedFields = this.lockedFields || [];

        if (typeof(value) == 'object'
            && value.length > 0
            && targetDef.type != 'multienum') {
            value = value.join(', ');

        } else if (value && targetDef.type === 'date') {
            value = App.date(value).formatServer(true);

        } else if (value && targetDef.type === 'datetimecombo') {
            value = App.date(value).format();
        }

        //Boolean handling
        if (targetDef.type == 'bool' && _.isString(value)) {
            value = SUGAR.expressions.Expression.isTruthy(value);
        }

        // Format decimal/float fields
        if (value && (targetDef.type === 'decimal' || targetDef.type === 'float')) {
            value = App.utils.formatNumber(
                value,
                targetDef.round || 6,
                targetDef.precision || 6,
                null,
                null
            );
        }

        // Do not overflow maxlength for calculated fields
        if (_.isString(value) && targetDef.len && value.length > targetDef.len) {
            var self = this;
            value = value.substring(0, targetDef.len);
            this.model.once('change:' + varname, function() {
                var msg = SUGAR.App.lang.getAppString('LBL_FIELD_TRIMMED');
                SUGAR.forms.markField(varname, self.getElement(varname), msg);
            });
        } else if (SUGAR.forms.markedField[varname]) {
            SUGAR.forms.unmarkField(varname, this.getElement(varname));
        }

        if (!this.lockedFields[varname])
        {
            this.lockedFields[varname] = true;
            var el = this.getElement(varname);
            if (el) {
                SUGAR.forms.FlashField($(el).parents('[data-fieldname="' + varname + '"]'), null, varname);
            }
            var ret = this.model.set(varname, value);
            this.lockedFields = [];
            return  ret;
        }
    },
    addListener : function(varname, callback, scope)
    {
        if (this.collection) {
            this.collection.off("change:" + varname, callback, scope);
            this.collection.on("change:" + varname, callback, scope);
        }
        if (this.model) {
            this.model.off("change:" + varname, callback, scope);
            this.model.on("change:" + varname, callback, scope);
        }
    },
    getElement : function(varname) {
        var field = this.getField(varname);
        if (field && field.el)
            return field.el;
    },
    getField : function(varname) {
        return this.view.getField(varname, this.model);
    },
    addClass : function(varname, css_class, includeLabel){
        var def = this.view.getFieldMeta(varname, true),
            props = includeLabel ? ["css_class", "cell_css"] : ["css_class"],
            el = this.getElement(varname),
            parent = $(el).closest('div.record-cell');

        _.each(props, function(prop) {
            if (!def[prop]) {
                def[prop] = css_class;
            } else if (def[prop].indexOf(css_class) == -1){
                def[prop] += " " + css_class;
            }
        });
        this.view.setFieldMeta(varname, def);

        $(el).addClass(css_class);
        if (!def._isChild && includeLabel && parent) {
            parent.addClass(css_class);
        }

    },
    removeClass : function(varname, css_class, includeLabel) {
        var def = this.view.getFieldMeta(varname, true),
            field = this.view.getField(varname),
            props = includeLabel ? ["css_class", "cell_css"] : ["css_class"],
            el = this.getElement(varname),
            parent = $(el).closest('div.record-cell');

        //Remove it from both the field objects def and the view metadata
        _.each([field.def, def], function(d) {
            _.each(props, function(prop) {
                if (d[prop] && d[prop].indexOf(css_class) != -1) {
                    d[prop] = $.trim((" " + d[prop] + " ").replace(new RegExp(' ' + css_class + ' '), ""));
                }
            });
        });
        this.view.setFieldMeta(varname, def);

        $(el).removeClass(css_class);
        if (!def._isChild && includeLabel && parent) {
            parent.removeClass(css_class);
            parent.find("." + css_class).removeClass(css_class);

        }
    },
    getLink : function(variable) {
        var model = this.model;
        if (model && model.fields && model.fields[variable])
            return model.fields[variable];
    },

    showError : function(variable, error)
    {
    	//TODO
    },
    clearError : function(variable)
    {
    	//TODO
    },
    setStyle : function(variable, styles)
    {
    	//TODO
    },
    setRelatedFields : function(fields, silent, model){
        silent = !!silent;
        model = model || this.model;
        for (var link in fields)
        {
            var linkVar = this._linkValueVarname(link),
                currValue = model.get(linkVar),
                forceChangeEvent = !!currValue, //Force the change event if the model already had an object for the link
                value = currValue || {};
            _.each(fields[link], function(values, type) {
                if (_.isObject(values)) {
                    //Convert any array values to objects.
                    values = _.mapObject(values, function(val) {
                        return _.isArray(val) ? _.object(val) : val;
                    });
                    value[type] = _.extend(value[type] || {}, values);
                } else {
                    value[type] = values;
                }
            });
            var toSet = {};
            toSet[linkVar] = value;
            model.set(toSet, {silent:silent});
            if (!silent && forceChangeEvent) {
                model.trigger("change:" + link, model);
            }
        }
    },
    getRelatedFieldValues : function(fields, module, record, silent)
    {
        var self = this,
            api = App.api,
            model = record instanceof App.data.beanModel ? record : this.model;
        if (fields.length > 0) {
            module = module || model.module || this.view.context.get('module');
            record = _.isString(record) ? record : model.get("id");
            for (var i = 0; i < fields.length; i++) {
                var link = fields[i].link,
                    toSet = {};
                //Related fields require a current related id
                if (fields[i].type == "related") {
                    var linkDef = model.fields[link];
                    if (linkDef && linkDef.id_name && linkDef.module) {
                        var relId = model.get(linkDef.id_name);
                        if (relId) {
                            fields[i].relId = relId
                            fields[i].relModule = linkDef.module;
                        }
                    }
                }
                if (fields[i].relate) {
                    //Mark the links as populating soon to prevent repeat requests for the same data.
                    toSet[link] = {};
                    toSet[link][fields[i].type] = {};
                    toSet[link][fields[i].type][fields[i].relate] = "";
                    self.setRelatedFields(toSet, true, model);
                }
            }
            var data = {id: record, action: "related"},
                params = {module: module, fields: JSON.stringify(fields)};

            api.call("read", api.buildURL("ExpressionEngine", "related", data, params), data, params, {
                success: function(resp) {
                    self.setRelatedFields(resp, silent, model);
                    return resp;
                }});
        }
        return null;
    },
    /**
     * Handle Updating or Create a Relationship Context
     *
     * @param {String} link The Relationship Name
     * @param {String} ftype The SugarLogic Function
     * @param {String} field The field, if no field is needed, pass in undefined
     * @param {Mixed} value The Value
     * @param {Boolean} isNew Are we created a new record?
     */
    updateRelatedFieldValue: function(link, ftype, field, value, isNew) {
        var linkValues = this.model.get(this._linkValueVarname(link)) || {},
            isNew = isNew || false,
            newValues = {};
        if (!_.isUndefined(linkValues[ftype]) || isNew) {
            if (!field) {
                linkValues[ftype] = value;
            } else if (!isNew && !_.isUndefined(linkValues[ftype][field])) {
                linkValues[ftype][field] = value;
            } else if (isNew) {
                if (_.isUndefined(linkValues[ftype])) {
                    linkValues[ftype] = {};
                }
                linkValues[ftype][field] = value;
            }
        }

        newValues[link] = linkValues;

        this.setRelatedFields(newValues, true);
    },

    /**
     * FixMe: Right now we need to do this so the Rollup SugarLogic doesn't break the saving on creates
     * @param {string} link
     * @return {string}
     * @private
     */
    _linkValueVarname: function(link) {
        return '_' + link + '-rel_exp_values';
    },

    getRelatedCollectionValues: function(model, link, ftype, field) {
        model = model || this.model;
        var linkValues = model.get(this._linkValueVarname(link)) || {};
        var fieldVal = field + '_values';
        if (ftype) {
            if (linkValues[ftype] && linkValues[ftype][fieldVal]) {
                return linkValues[ftype][fieldVal];
            }
            return [];
        }
        return linkValues;
    },

    updateRelatedCollectionValues: function(model, link, ftype, field, relModel, operation) {
        model = model || this.model;
        var link = this._linkValueVarname(link);
        var linkValues = model.get(link) || {};
        var fTypes = [ftype];
        var fields = [field];
        if (!ftype) {
            fTypes = _.keys(linkValues);
        }
        _.each(fTypes, function(fType) {
            //related link values don't apply to collections
            if (fType == 'related') {
                return;
            }
            if (!field) {
                fields = _.filter(_.keys(linkValues[fType]), function(field) {
                    return field.substr(-7) != '_values';
                });
            }
            _.each(fields, function(field) {
                var fieldVal = field + '_values';
                var isCurrency = relModel.fields[field] && relModel.fields[field].type === 'currency';
                var current = linkValues[fType] && linkValues[fType][fieldVal] || {};

                if (!_.isEmpty(current) && operation == 'remove') {
                    if (relModel.id in current) {
                        delete current[relModel.id];
                    }

                    if (relModel.cid in current) {
                        delete current[relModel.cid];
                    }
                }

                if (!operation || operation == 'add') {
                    //Cleanup references to a model's CID if it now has an ID
                    if (current[relModel.cid] && relModel.id) {
                        delete current[relModel.cid];
                    }
                    var id = relModel.id ? relModel.id : relModel.cid;
                    var value = relModel.get(field);
                    if (isCurrency) {
                        value = App.currency.convertToBase(
                            value,
                            relModel.get('currency_id')
                        );
                    }
                    current[id] = value;
                }
                linkValues[fType] = linkValues[fType] || {};
                linkValues[fType][fieldVal] = current;
            }, this);
        }, this);

        model.set(link, linkValues);
    },

    getRelatedField : function(link, ftype, field){
        var linkVar = this._linkValueVarname(link);
        var linkValues = this.model.get(linkVar) || {};

        if (ftype == "related" && !this.inCollection) {// except subpanels. see explanation below RE:updating a full collection of models
            return this._handleRelateExpression(link, field);
        }

        //Check if we already have this value
        if (typeof(linkValues[ftype]) != "undefined") {
            if (!field) {
                return linkValues[ftype];
            } else if (typeof(linkValues[ftype][field]) != "undefined") {
                return linkValues[ftype][field];
            }
        }
        //We fell through so its time to try and load through ajax

        //When updating a full collection of models, do not get related values as this will
        //send one request per item in the list (could be as many as 100)
        if (this.inCollection) {
            linkValues[ftype] = linkValues[ftype] || {};
            linkValues[ftype][field] = linkValues[ftype][field] || "";
            // Setting with the silent flag to prevent change events from firing
            // downstream and forcing recalculations based on those changes
            this.model.set(linkVar, linkValues, {silent: true});
            return "";
        }

        //Do not attempt to load related values of a new record
        if (this.model.isNew()) {
            return "";
        }

        var params = {link: link, type: ftype};
        if (field) {
            params.relate = field;
        }

        this.getRelatedFieldValues([params]);

        // since the call above potentially created the values,
        // get them form the model again and try to find them again.
        linkValues = this.model.get(linkVar) || {};
        //Check if we already have this value
        if (!_.isUndefined(linkValues[ftype])) {
            if (!field) {
                return linkValues[ftype];
            } else if (!_.isUndefined(linkValues[ftype][field])) {
                return linkValues[ftype][field];
            }
        }
        return '';

    },
    _handleRelateExpression : function(link, field){
        //Ensure the current model is selected in the collection.
        this.view.context.set("model", this.model);
        var isSubpanel = this.view.context.get('isSubpanel') === true;

        if (isSubpanel) {
            var relContext = this.view.context.parent;
        } else {
            var relContext = this.view.context.getChildContext({link: link});
        }

        //Prepares instances of related model and collection.
        relContext.prepare();
        var col = relContext.get("collection"),
            fields = relContext.get('fields') || [],
            self = this,
            //First check if there is a relate field availible before loading a rel context.
            rField = _.find(this.model.fields, function(def){
                return (def.type && def.type == "relate" && def.id_name && def.link && def.link == link)
            }) || {},
            relModel = relContext.get("model");

        //Now check if a relate field for this link changed since we last loaded this value
        //Check if the context is a collection and use the first model instead if that is the case
        if (relContext.isDataFetched() && !relContext.get("modelId")) {
            relModel = col.length > 0 ? col.models[0] : null;
        }

        var idName = this.model.get(rField.id_name);
        var differentModel = relModel && relModel.get('id') !== idName;
        var relationID;

        //Now check if a relate field for this link changed since we last loaded this value
        if (!_.isEmpty(rField) && (!idName || differentModel) && !isSubpanel) {
            //Nuke the context info now since its no longer valid
            relContext.set({model:null});
            relContext.resetLoadFlag();
            //We are using a relate field but its empty for now, so abort.
            if (!idName) {
                return "";
            }
        }

        //make sure the property to cache the related fields is defined
        if (_.isUndefined(this.view._loadedRelatedFields)) {
            this.view._loadedRelatedFields = {};
        }

        // initiate loading data in case if it's not initiated yet or loaded model doesn't contain the needed field
        if (idName) {
            relationID = idName;
        } else {
            var relationship = this.model.fields[link].relationship;
            relationID = relationship ? relationship : '';
        }
        var cachedKey = link + '_' + field + '_' + relationID;

        if (field &&
            (!relContext.isDataFetched() ||
            (relModel && _.isUndefined(relModel.get(field)) && !relModel.getFetchRequest())) &&
            !this.view._loadedRelatedFields.hasOwnProperty(cachedKey)) {

            // fields may contain more fields than just the original one
            // so cache those as well to avoid doing multiple requests
            _.each(fields, _.bind(function(iterField) {
                var fieldCacheKey = link + '_' + iterField + '_' + relationID;
                this.view._loadedRelatedFields[fieldCacheKey] = fieldCacheKey;
            }, this));

            if (!_.contains(fields, field)) {
                fields.push(field);
            }

            this._loadRelatedData(link, fields, relContext, rField);

            //add listener to remove cached info if the field value's change
            if (rField.id_name) {
                this.addListener(rField.id_name, function() {
                    this.view._loadedRelatedFields = {};
                }, this);
            }
        }
        else if (relModel) {
            var value = relModel.get(field);
            if (value) {
                var def = relModel.fields[field];
                if (def.type == "date" || def.type == "datetime") {
                    value = App.date.stripIsoTimeDelimterAndTZ(value);
                    value = App.date.parse(value);
                    value.type = def.type;
                }
            }
            return value;
        } else if (!col.page) {
            // This link is currently being loaded (with the field we need). Collection's don't fire a sync/fetch event,
            // so we need to use doWhen to known when the load is complete.
            // We will fire the link change event once the load is complete to re-fire the dependency with the correct data.
            // Keep a reference to what the model was when we started the doWhen in case it changes when the sync completes.
            var model = this.model;
            SUGAR.App.utils.doWhen(function(){return col.page > 0}, function(){
                model.trigger("change:" + link, model);
            });
        }
        return "";
    },
    getDateExpression: function(date) {
        //This is probably a date object that we must convert to an expression
        var d = new SE.DateExpression("");
        d.evaluate = function(){return this.value};
        d.value = date;
        return d;
    },
    //Helper function to trigger the actual load call of related data
    _loadRelatedData : function(link, fields, relContext, rField) {
        var self = this;
        if (rField && this.model.get(rField.id_name)){
            //If we are using a relate field rather than a full link
            var modelId = this.model.get(rField.id_name),
                model =  relContext.get("model")
                     || SUGAR.App.data.createRelatedBean(this.model, this.model.get(rField.id_name), link);
            relContext.set({
                modelId:modelId,
                model : model
            });
        } else {
            //If we don't have a record id, we can't make a server call for anything so abort at this point
            if (_.isEmpty(this.model.get("id")))
                return "" ;
        }

        relContext.prepare();
        //Call set in case fields was not already on the context
        relContext.set({
            'fields' : _.union(relContext.get("fields") || [], fields),
            //Force skipFetch false if this context had the data we wanted, we wouldn't be here.
            skipFetch : false
        });
        if (relContext.isDataFetched()){
            relContext.resetLoadFlag();
        }

        var model = this.model;
        //need up update the related context's bean to the current list bean before we can load it.
        //relContext.set("parentModel", model);
        //relContext.attributes.collection.link.bean = model;
        relContext.loadData({
            relate: !rField, //don't use the link api if we are forcing an id pulled from a field on the current model.
            success: function() {
                // We will fire the link change event once the load is complete to re-fire the dependency with the correct data.
                model.trigger("change:" + link, model);
            }
        });

    },
    //PreSetup but don't load any related contexts we might need
    _setupLinks : function(relatedFields){
        _.each(relatedFields, function(field) {
            if (field.link && field.relate && field.type == "related") {
                var relContext = this.view.context.getChildContext({link:field.link});
                if (relContext) {
                    relContext.set("fields", _.union(relContext.get("fields") || [], [field.relate]));
                }
            }
        }, this);
    },
    //Preload (but don't trigger changes) related field data
    _requestRollups :  function(relatedFields, model){
        model = model || this.model;
        if (!model.isNew()) {
            this.getRelatedFieldValues(relatedFields, model.module, model, true);
        }
    },
    fireOnLoad : function(dep) {
        //Disable fire on load for now as we no longer have edit vs detail views and
        //this is just costing us performance.
        //this.view.model.once("change", SUGAR.forms.Trigger.fire, dep.trigger);
    },
    getAppListStrings : function(list) {
        return SUGAR.App.lang.getAppListStrings(list);
    },
    parseDate: function(date, type) {
        return SUGAR.App.date.parse(date);
    },
    /**
     * Used to set a field read-only / disabled
     * @param {String} target Name of field to modify
     * @param {Boolean} disabled True to mark a field read-only. False to mark it editable.
     */
    setFieldDisabled: function(target, disabled) {
        disabled = (_.isUndefined(disabled)) ? true : disabled;
        var field = this.getField(target);
        if (field) {
            field.setDisabled(disabled, {trigger: true});

            // if disabled is false
            // and the currentState of the field is not edit
            // and the currentState of the view is edit but not inlineEditMode
            // then we switch to field into edit mode and add the field to the editableFields list
            if (_.isEqual(disabled, false)
                && !_.isEqual(field.currentState, 'edit')
                && _.isEqual(this.view.currentState, 'edit')
                && !_.isEqual(this.view.inlineEditMode, true)
            ) {
                field.setMode('edit');
                this.view.editableFields.push(field);
            }
            if (_.isFunction(this.view.setEditableFields)) {
                this.view.setEditableFields();
            }
        }
    },
    /**
     * Used to mark a field required or not in the current view.
     * @param {String} target Name of field to modify.
     * @param {Boolean} required True to mark a field required. False to mark it nullable.
     */
    setFieldRequired: function(target, required) {
        //Force required to be boolean true or false
        required = SUGAR.expressions.Expression.isTruthy(required);
        var field = this.view.getField(target, this.model);
        if (field) {
            field.def.required = required;
            field.render();
        }
    },
    /**
     * Used to set assigned user.
     * @param {string} username Username to set the record assigned to.
     */
    setAssignedUserName: function(target, username) {
        if (this.model.has('assigned_user_name') && this.model.has('assigned_user_id')) {
            var self = this, options = {}, usersCollection = SUGAR.App.data.createBeanCollection('Users');

            options.filter = {
                filter: [
                    { user_name: username }
                ]
            }

            options.success = function(collection) {
                var userModel = collection.first();
                if (userModel) {
                    self.model.set({
                        'assigned_user_name': userModel.get('full_name'),
                        'assigned_user_id': userModel.get('id')
                    });
                    var field = self.view.getField(target, self.model);
                    if (field && field.el) {
                        SUGAR.forms.FlashField(field.el, null, target);
                    }
                }
            }

            options.error = function() {
                SUGAR.App.alert.show('server-error', {
                    level: 'error',
                    title: SUGAR.App.lang.get('ERR_GENERIC_TITLE'),
                    messages: SUGAR.App.lang.get('ERR_ASSIGNTO_ACTION')
                });
            }

            usersCollection.fetch({
                fields: ['full_name'],
                limit: 1,
                params: options.filter,
                success: options.success,
                error: options.error
            });
        }
    },

    /**
     * Set the model on the Context
     * @param {Backbone.Model} model
     */
    setModel: function(model) {
        this.model = model;
    },

    /**
     * If the trigger is coming from an event for a related record
     * this allows the RelatedModel to be set on the context
     *
     * @param {Backbone.Model} model
     */
    setRelatedModel: function(model) {
        this.relatedModel = model;
    },
    /**
     * Used to Add Currency Values
     *
     * @param {String} start        What we are starting with
     * @param {String} add          What we want to add to the value
     * @return {String}
     */
    add: function(start, add) {
        return SUGAR.App.math.add(start, add, 6, true);
    },
    /**
     * Used to Subtract Currency Values
     *
     * @param {String} start        What we are starting with
     * @param {String} subtract          What we want to subtract from the value
     * @return {String}
     */
    subtract: function(start, subtract) {
        return SUGAR.App.math.sub(start, subtract, 6, true);
    },
    /**
     * Used to Multiply Currency Values
     *
     * @param {String} start        What we are starting with
     * @param {String} multiply     What we want to multipy by
     * @return {String}
     */
    multiply: function(start, multiply) {
        return SUGAR.App.math.mul(start, multiply, 6, true);
    },
    /**
     * Used to Divide Currency Values
     *
     * @param {String} start        What we are starting with
     * @param {String} divide       What we want to divide the currency value by
     * @return {String}
     */
    divide: function(start, divide) {
        return SUGAR.App.math.div(start, divide, 6, true);
    },

    /**
     * Used to Round the value to a given precision
     *
     * @param {String} start
     * @param {String} precision
     * @return {String}
     */
    round: function(start, precision) {
        return SUGAR.App.math.round(start, precision, true);
    },

    /**
     * Check the model for collection fields,  If any fields are are found for the given link name, then return it.
     *
     * This will save sidecar from having to create a new context if the model will contain the collection as part of
     * it's data set.
     *
     * @param {String} linkName
     * @return {undefined|SUGAR.App.data.beanCollection|string}
     */
    getNestedCollectionFromModel: function(linkName, model) {
        // this will check to see if a collection exists
        // if one does, it will be returned
        var nestedCollection;
        model = model || this.model;

        // first check for collection fields
        var collectionFields = _.pluck(model.fieldsOfType('collection'), 'name');
        if (!_.isEmpty(collectionFields)) {
            var collectionField = _.chain(collectionFields)
                .filter(function(f) { return _.contains(model.fields[f].links, linkName); }, this)
                .value();
            if (_.size(collectionField) === 1) {
                var cf = _.first(collectionField);
                if (model.has(cf)) {
                    nestedCollection = model.get(cf);

                    if (!(nestedCollection instanceof SUGAR.App.data.beanCollection)) {
                        nestedCollection = undefined;
                    }
                } else {
                    nestedCollection = cf;
                }
            }
        }
        return nestedCollection;
    },

    /**
     * Get the Context for the passed in Link
     *
     * @param {View.View} view
     * @param {String} linkName
     * @return {Core.Context|*}
     */
    getLinkContext: function(view, linkName) {
        var LinkContext = view.context.getChildContext({link: linkName});
        // lets make sure that the context contains a collection
        LinkContext.prepare();
        return LinkContext;
    }

});

/**
 * @static
 * The Default expression parser.
 */
SEC.parser = new SUGAR.expressions.ExpressionParser();

/**
 * @static
 * Parses expressions given a variable map.<br>
 */
SEC.evalVariableExpression = function(expression, view)
{
	return SEC.parser.evaluate(expression, new SEC(view));
}

/**
 * A dependency is an object representation of a variable being dependent
 * on other variables. For example A being the sum of B and C where A is
 * 'dependent' on B and C.
 */
SUGAR.forms.Dependency = function(trigger, actions, falseActions, testOnLoad, context)
{
    this.actions = actions;
	this.falseActions = falseActions;
	this.context = context;
    this.testOnLoad = testOnLoad;
    this.trigger = trigger;
    trigger.setContext(this.context);
    trigger.setDependency(this);
	if (testOnLoad) {
	    context.fireOnLoad(this);
	}
}

    /**
     *  Creates a Dependency from the given metadata.
     *
     * @static
     * @param {Object} meta The metadata that defines this dependency
     * @param {ExpressionContext} context The context to attach to this dependency
     * @return {SUGAR.forms.Dependency} Dependency object created from metadata
     */
SUGAR.forms.Dependency.fromMeta = function(meta, context) {
    var condition = meta.trigger || 'true',
        triggerFields = meta.triggerFields || SE.ExpressionParser.prototype.getFieldsFromExpression(condition),
        relatedFields = meta.relatedFields || [],
        actions = meta.actions || [],
        falseActions = meta.notActions || [],
        onLoad = meta.onload || false,
        //Aways trigger a dependecy onload during a create since no data exists server side yet.
        isNew = context.model && _.isEmpty(context.model.get('id')),
        actionObjects = [],
        falseActionObjects = [];

    //Without any trigger fields (or a condition with variables), we can't create a trigger
    if (_.isEmpty(triggerFields) && !onLoad && !isNew)
        return null;
    //No actions means no reason to create a dependency
    if (_.isEmpty(actions) && _.isEmpty(falseActions))
        return null;

    _.each(actions, function(actionDef) {
        if (!actionDef.action || !SUGAR.forms[actionDef.action + 'Action'])
            return;

        var addAction = true;
        // if the action is SetValue, lets make sure the the user has edit access to this field
        if (actionDef.action === 'SetValue' && actionDef.params.target) {
            addAction = SUGAR.App.acl.hasAccess('edit', this.model.module, undefined, actionDef.params.target);
        }

        if (addAction) {
            actionObjects.push(new SUGAR.forms[actionDef.action + 'Action'](actionDef.params));
        }
    }, context);
    _.each(falseActions, function(actionDef) {
        if (!actionDef.action || !SUGAR.forms[actionDef.action + 'Action'])
            return;

        var addAction = true;
        // if the action is SetValue, lets make sure the the user has edit access to this field
        if (actionDef.action === 'SetValue' && actionDef.params.target) {
            addAction = SUGAR.App.acl.hasAccess('edit', this.model.module, undefined, actionDef.params.target);
        }

        if (addAction) {
            falseActionObjects.push(new SUGAR.forms[actionDef.action + 'Action'](actionDef.params));
        }

    }, context);

    return new SUGAR.forms.Dependency(
        new SUGAR.forms.Trigger(triggerFields, condition, context, relatedFields),
        actionObjects, falseActionObjects, onLoad, context
    );
};


    /**
     * If the type of all the triggerFiels are link, then it should return false
     * @return {boolean}
     */
    SUGAR.forms.Dependency.prototype.fireLinkOnlyDependency = function()
    {
        if (!this.testOnLoad) {
            return false;
        }
        /**
         * cycle all the triggerFields
         *   - When all the triggerFields are type of link, we can still trigger this
         *   - if one is not type of link, then don't trigger this
         */
        var fire = true;
        if (this.context.model.fields) {
            _.each(this.trigger.variables, function(field) {
                fire = (fire && this.context.model.fields[field] && this.context.model.fields[field].type == "link");
            }, this);
        }

        return fire;
    }


/**
 * Triggers this dependency to be re-evaluated again.
 */
SUGAR.forms.Dependency.prototype.fire = function(undo)
{
    if(this.context.view.disposed || !this.context.view.context) {
        return;
    }
	try {
        var model = this.context.model;
        this.lastTriggeredActions = this.lastTriggeredActions || [];

		//Do not trigger dependencies on models that haven't changed and aren't set to fire on load.
        if (model.inSync && !this.testOnLoad) {
            return;
        }

        var actions = this.actions;
		if (undo && this.falseActions != null)
			actions = this.falseActions;

        //Clean up any render listeners for out of date actions when a dependency is triggered multiple times
        _.each(this.lastTriggeredActions, function(action) {
            this.context.view.off(null, null, action);
        }, this);

        if (actions instanceof SUGAR.forms.AbstractAction)
            actions = [actions];

        for (var i in actions) {
            var action = actions[i];
            if (typeof action.exec == "function") {
                action.setContext(this.context);
                action.exec();
                if (this.testOnLoad && action.afterRender) {
                    this.context.view.on('render', action.exec, action);
                }
            }
        }

        this.lastTriggeredActions = actions;

	} catch (e) {
		if (!SUGAR.isIE && console && console.log){
			console.log('ERROR: ' + e);
		}
		return;
	}
};

SUGAR.forms.Dependency.prototype.getRelatedFields = function() {
    var parser = SEC.parser,
        fields = parser.getRelatedFieldsFromFormula(this.trigger.condition);
    //parse will search a list of actions for formulas with relate fields
    var parse = function (actions) {
        if (actions instanceof SUGAR.forms.AbstractAction) {
            actions = [actions];
        }
        for (var i in actions) {
            var action = actions[i],
                actionTarget = action.target || undefined;
            //Iterate over all the properties of the action to see if they are formulas with relate fields
            if (typeof action.exec == "function") {
                for (var p in action) {
                    if (typeof action[p] == "string" && action[p].indexOf('$') > -1) {
                        fields = $.merge(fields, parser.getRelatedFieldsFromFormula(action[p], actionTarget));
                    }
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
        context = context || this.context;
        return SEC.parser.evaluate(exp, context).evaluate();
    }

    /**
     * Determines if actions is allowed to set new value on the record in the given context
     *
     * @param {ExpressionContext} context Expression context
     * @return {Boolean}
     */
    SUGAR.forms.AbstractAction.prototype.canSetValue = function (context) {
        if (context.options && context.options.revert) {
            return false;
        }

        if (context.isOnLoad && !context.model.isNew()) {
            return false;
        }

        return true;
    };

    /**
     * This object resembles a trigger where a change in any of the specified
     * variables triggers the dependencies to be re-evaluated again.
     *
     * @param {Array} variables
     * @param {Boolean} condition
     * @param {Object} context
     * @param {Array} variableFields
     * @constructor
     */
    SUGAR.forms.Trigger = function (variables, condition, context, relatedFields) {
        this.variables = variables;
        this.condition = condition;
        this.context = context;
        this.relatedFields = relatedFields || [];
        // track if the collection was found on the model
        this.dependency = { };
    };

    /**
     * Utility Method to find a related collection.  If the collection is found on the model, but it's not set
     * on the model yet, it will add a listener to fire the {@_attachListeners} event again once that field
     * has been set on the model.
     *
     * If no collection field exists, it will fall back and create linked context and return that collection
     *
     * @param {string} link_name
     * @return {undefined|Backbone.Collection|string}
     * @private
     */
    SUGAR.forms.Trigger.prototype._findRelatedCollection = function(link_name, model)
    {
        model = model || this.context.model;
        var varContextCollection = this.context.getNestedCollectionFromModel(link_name, model);
        if (_.isUndefined(varContextCollection)) {
            var varContext = this.context.getLinkContext(this.context.view, link_name);
            if (varContext && varContext.has('collection')) {
                varContextCollection = varContext.get('collection');
            }
        }

        return varContextCollection;
    };

    /**
     * Attaches a 'change' listener to all the fields that cause
     * the condition to be re-evaluated again.
     * FIXME: Listeners attached here should be disposed by sugarlogic itself.
     * Currently the disposing of listeners relies on context.clear()
     * from sidecar.
     */
    SUGAR.forms.Trigger.prototype._attachListeners = function() {
        if (!(this.variables instanceof Array)) {
            this.variables = [this.variables];
        }

        for (var i = 0; i < this.variables.length; i++) {
            this.context.addListener(this.variables[i], SUGAR.forms.Trigger.fire, this, true);
        }

        var models = [this.context.model];
        //Collections require extra listeners to handle models being added or removed
        if (this.context.useCollection) {
            models = this.context.collection.models;
            //When the collection gets new models, we need to listen to thier child collections
            this.context.collection.off('add', null, this);
            this.context.collection.off('remove', null, this);
            this.context.collection.on('add', function(model, collection, options) {
                this._attachCollectionListeners(model);
            }, this);
            //When a model is removed, we need to remove all the listeners to that model
            this.context.collection.on('remove', function(model, collection, options) {
                _.each(model._relatedCollections, function(collection) {
                    collection.off(null, null, this);
                })
                model.off(null, null, this);
            }, this);
        }
        _.each(models, function(model) {
            this._attachCollectionListeners(model);
        }, this);
    };

    SUGAR.forms.Trigger.prototype._attachCollectionListeners = function(model, triggerLink, relModel) {
        var relFields = this.relatedFields,
            linkFields = [],
            changingField,
            isRemoveEvent = false;
        triggerLink = triggerLink || false;

        // get all the related fields for the links in this formula
        _.each(this.dependency.getRelatedFields(), function(field) {
            linkFields[field.link] = _.union(linkFields[field.link] || [], [field.relate]);
        });

        var colChangeCallback = _.bind(function(relModel, value, event) {
            // what field on the model actually changed,
            // this is useful for the rollupCondition checks.
            if (value !== null && !_.isUndefined(value)) {
                _.find(relModel.changed, function(v, k) {
                    if (_.contains(relFields, k) && v === value) {
                        changingField = k;
                        return true;
                    }
                });
            } else if (event && event.type == 'remove') {
                this.context.isRemoveEvent = true;
            }
            if (event && event.link) {
                this.context.updateRelatedCollectionValues(model, event.link, null, null, relModel, event.type);
            }
            var prevModel = this.context.model;
            this.context.model = model;
            this.context.changingField = changingField;
            SUGAR.forms.Trigger.fire.call(this, relModel, null, {
                fromRelated: true,
                changingField: changingField,
                isRemoveEvent: isRemoveEvent
            });
            // remove the field from the context after the trigger has fired
            this.context.model = prevModel;
            delete this.context.isRemoveEvent
            delete this.context.changingField;
        }, this);

        for (var i = 0; i < this.variables.length; i++) {
            // this is an action with a target and we are one a new model,
            // find the LinkContext and add the listener to the collection since there are no relatedFields
            var link = this.variables[i];
            var linkRelatedFields = linkFields[link] || [];

            var varContextCollection = this._findRelatedCollection(link, model);
            if (varContextCollection instanceof Backbone.Collection) {
                //First bind updates of the collection (models added or removed)
                varContextCollection.off('add', null, this);
                varContextCollection.on('add', function(relModel) {
                    colChangeCallback(relModel, null, {
                        type: 'add',
                        link: link
                    });
                }, this);

                varContextCollection.off('remove', null, this);
                varContextCollection.on('remove', function(relModel) {
                    colChangeCallback(relModel, null, {
                        type: 'remove',
                        link: link
                    });
                }, this);

                //Now bind changes of any related field of models in the collection
                if (!_.isEmpty(linkRelatedFields)) {
                    var events = 'change:' + _.unique(linkRelatedFields).join(' change:');
                    // remove all listeners from this context regardless of the function
                    varContextCollection.off(events, null, this);
                    varContextCollection.on(events, colChangeCallback, this);
                }
            } else if (_.isString(varContextCollection)) {
                //Adding Listener to re-run the _attachListeners once the field is set and changes
                model.once('change:' + varContextCollection, function(parentModel, changedRelatedModel) {
                    this._attachListeners();
                    //If this event was caused by a model in the collection changing, trigger the dependency
                    if (_.isObject(changedRelatedModel) && _.intersection(_.keys(changedRelatedModel.changed), relFields).length > 0) {
                        SUGAR.forms.Trigger.fire.call(this, model);
                    }
                }, this);
                return varContextCollection;
            }
        }
        if(triggerLink && _.contains(this.variables, triggerLink)) {
            colChangeCallback(relModel, null, 'add');
        }
    };


    /**
     * Attaches a 'change' listener to all the fields that cause
     * the condition to be re-evaluated again.
     */
    SUGAR.forms.Trigger.prototype.setDependency = function (dep) {
        this.dependency = dep;
        this._attachListeners();
    }

    SUGAR.forms.Trigger.prototype.setContext = function (context) {
        this.context = context;
    }

    /**
     * @static
     * This is the function that is called when a 'change' event
     * is triggered. If the condition is true, then it triggers
     * all the dependencies.
     */
    SUGAR.forms.Trigger.fire = function (model, value, options) {
        options = options || {};
        // eval the condition
        var evaluation, val, prevModel, prevRelatedModel;
        if (model) {
            // if the context doesn't have a model or the current model.module is equal to the new model.module
            // do we have a context or not?
            var fromRelated = options.fromRelated || false;

            if (!fromRelated && model) {
                prevModel = this.context.model;
                this.context.setModel(model);
            } else if (fromRelated) {
                prevRelatedModel = this.context.relatedModel;
                // set the related module as the trigger is coming from a rollup formula
                // since the model doesn't match was it on the context
                this.context.setRelatedModel(model);
            }
        }
        this.context.options = options;
        try {
            evaluation = SEC.parser.evaluate(this.condition, this.context);
        } catch (e) {
            if (!SUGAR.isIE && console && console.log) {
                console.log('ERROR:' + e + '; in Condition: ' + this.condition);
            }
        }

        // evaluate the result
        if (!_.isUndefined(evaluation)) {
            val = evaluation.evaluate();
        }

        // if the condition is met
        if (val == SUGAR.expressions.Expression.TRUE) {
            // single dependency
            if (this.dependency instanceof SUGAR.forms.Dependency) {
                this.dependency.fire(false);
            }
        } else if (val == SUGAR.expressions.Expression.FALSE) {
            // single dependency
            if (this.dependency instanceof SUGAR.forms.Dependency) {
                this.dependency.fire(true);
            }
        }

        //Cleanup and changes we made to the expression context
        if (!fromRelated && model) {
            this.context.setModel(prevModel);
        }

        if (fromRelated) {
            this.context.setRelatedModel(prevModel);
        }
    };

    SUGAR.forms.flashInProgress = {};
    SUGAR.forms.markedField = {};
    SUGAR.forms.exclamationMarkTemplate = Handlebars.compile(
        '<span class="warning-tooltip add-on" data-container="body" rel="tooltip" title="{{this}}"><i class="fa fa-warning"></i></span>'
    );

    /**
     * @static
     * Animates a field when by changing it's background color to
     * a shade of light red and back.
     */
    SUGAR.forms.FlashField = function (field, to_color, key) {
        if (typeof(field) == 'undefined' || (!key && !field.id))
            return;
        key = key || field.id;
        if (SUGAR.forms.flashInProgress[key])
            return;

        SUGAR.forms.flashInProgress[key] = true;

        to_color = to_color || '#FF8F8F';
        // store the original background color but default to white
        var original = field.style && field.style.backgroundColor ? field.style.backgroundColor : '#FFFFFF' ;

        $(field).css("backgroundColor", original);

        $(field).animate({
            backgroundColor : to_color
        }, 200, function(){
            $(field).animate({
                backgroundColor : original
            }, 200, function(){
                delete SUGAR.forms.flashInProgress[key];
            });
        });
    };

    /**
     * @static
     * Marks a field by changing its background color and setting a text under it
     */
    SUGAR.forms.markField = function(key, el, text) {
        if (SUGAR.forms.markedField[key])
            return;

        if (!el)
            return;

        var field = $(el).parents('[data-fieldname="' + key + '"]');
        var $ftag = $(el).children();

        // add warning class
        field.addClass('warning');

        // insert tooltip
        var $tooltip = $(SUGAR.forms.exclamationMarkTemplate(text));
        var isWrapped = $ftag.parent().hasClass('input-append');

        if (!isWrapped) {
            $ftag.wrap('<div class="input-append warning">');
        }

        $ftag.parent().addClass('warning');
        $ftag.after($tooltip);

        SUGAR.forms.markedField[key] = $tooltip;
    };

    /**
     * @static
     * Unmarks a marked field
     */
    SUGAR.forms.unmarkField = function(key, el) {
        if (!SUGAR.forms.markedField[key])
            return;

        if (!el)
            return;

        var field = $(el).parents('[data-fieldname="' + key + '"]');

        // remove warning class
        field.removeClass('warning');

        // remove tooltip
        SUGAR.forms.markedField[key].remove();

        SUGAR.forms.markedField[key] = null;
    };

    SE.plugin = {
        onAttach: function() {
            this.on('init', function() {
                this._slCtx = this.initSugarLogic();
                /**
                 * Reference to the `SugarLogic` plugin context on the view.
                 *
                 * @deprecated Deprecated since 7.9.
                 * @type {SUGAR.expressions.ExpressionContext}
                 */
                Object.defineProperty(this, 'slContext', {
                    get: function () {
                        SUGAR.App.logger.warn('`View.slContext` has been deprecated since 7.9.');
                        return this._slCtx;
                    },
                    configurable: true,
                    enumerable: true,
                });

                this.context.addFields(this._getDepFields());
            }, this);
        },

        /**
         * Init SugarLogic on a passed in model or the default one to the view that this plugin is attached to
         *
         * @param {Data.Bean} [model] The Model we want to enable SugarLogic on
         * @param {Array} [dependencies] Any dependencies that should be used.
         * @param {Bool} [isCreate] Trigger the dependencies on model that is in create mode.
         * @return {SUGAR.expressions.SidecarExpressionContext}
         */
        initSugarLogic: function(model, dependencies, isCreate) {
            // set the defaults if they are not already set
            model = model || this.model;
            this._dependencies = dependencies || this.getApplicableDeps();
            isCreate = isCreate || false;
            var slContext = new SUGAR.expressions.SidecarExpressionContext(this, model, this.collection);
            if (_.isEmpty(this._dependencies)) {
                return slContext;
            }

            slContext.initialize(this._dependencies, isCreate);
            return slContext;
        },

        /**
         * Gets the list of dependent/calculated fields with SugarLogic
         * expressions, to be included in the list of fields fetched by the
         * view's context.
         *
         * @protected
         * @return {Array} The list of SugarLogic fields.
         */
        _getDepFields: function() {
            var fields;
            // Parses the expression for fields (beginning with `$`).
            var getFields = SE.ExpressionParser.prototype.getFieldsFromExpression;
            var deps = this.getApplicableDeps();

            _.each(deps, function(dep) {
                if (dep.trigger) {
                    fields = _.union(fields, getFields(dep.trigger));
                }
                _.each(dep.actions, function(action) {
                    _.each(action.params, function(param) {
                        if (_.isString(param)) {
                            fields = _.union(fields, getFields(param));
                        }
                    });
                });
            });

            if (this.model) {
                fields = _.filter(fields, function(field) {
                    return this.model.fields[field] && this.model.fields[field].type !== 'link';
                }, this);
            }

            return fields;
        },

        getApplicableDeps: function() {
            var meta = _.extend({}, this.meta, this.options.meta),
                // module level dependencies
                modDeps = SUGAR.App.metadata.getModule(this.context.get("module"), "dependencies"),
                // FIXME sugarplugin shouldn't depend on info from other plugins...
                action = (_.contains(this.plugins, 'Editable')
                    || this.name == 'edit'
                    || this.name == 'create')
                        ? "edit" : "view",
                deps = meta.dependencies;

            if (!_.isEmpty(modDeps)) {
                // to merge with view level dependencies
                var filteredModDeps = _.filter(modDeps, function(dep) {
                    if (_.contains(dep.hooks, "all") || _.contains(dep.hooks, action)) {
                        return true;
                    }
                    return false;
                });
                deps = (!_.isEmpty(deps)) ? _.union(deps, filteredModDeps) : filteredModDeps;
            }

            return deps;
        },

        /**
         * Unbinds event listeners bound by SidecarExpressionContext class.
         */
        onDetach: function () {
            this.off(null, null, this._slCtx);
            this.model && this.model.off(null, null, this._slCtx);
            this.collection && this.collection.off(null, null, this._slCtx);

            _.each(this._slCtx.dependencies, function (dep) {
                this.context.off('list:editrow:fire', null, dep);
                _.each(dep.actions, function (action) {
                    this.off(null, null, action);
                }, this);
            }, this);
        },
    };

    //Register SugarLogic as a plugin to sidecar.
    if (SUGAR.App && SUGAR.App.plugins) {
        SUGAR.App.plugins.register('SugarLogic', 'view', SE.plugin);
    } else if (console.error) {
        console.error("unable to find the plugin manager");
    }
})();
