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
/**
 * @class View.Views.Base.MassaddtolistView
 * @alias SUGAR.App.view.views.BaseMassaddtolistView
 * @extends View.Views.Base.MassupdateView
 */
({
    extendsFrom: 'MassupdateView',
    addToListFieldName: 'prospect_lists',
    listModule: 'ProspectLists',
    massUpdateViewName: 'massaddtolist-progress',
    className: 'extend',

    initialize: function(options) {
        var additionalEvents = {};
        additionalEvents['click .btn[name=create_button]'] = 'createAndSelectNewList';
        this.events = _.extend({}, this.events, additionalEvents);
        this._super("initialize", [options]);
    },

    /**
     * Listen for just the massaddtolist event from the list view
     */
    delegateListFireEvents: function() {
        this.layout.on("list:massaddtolist:fire", this.show, this);
        this.layout.on("list:massaction:hide", this.hide, this);
    },

    /**
     * Pull out the target list link field from the field list and treat it like a relate field for later rendering
     * @param options
     */
    setMetadata: function(options) {
        var moduleMetadata = app.metadata.getModule(options.module);

        if (!moduleMetadata) {
            return;
        }

        var addToListField = _.find(moduleMetadata.fields, function(field) {
            return field.name === this.addToListFieldName;
        }, this);

        if (addToListField) {
            addToListField = app.utils.deepCopy(addToListField);
            addToListField.id_name = this.addToListFieldName + '_id';
            addToListField.name = this.addToListFieldName + '_name';
            addToListField.label = addToListField.label || addToListField.vname;
            addToListField.type = 'relate';
            addToListField.required = true;
            this.addToListField = addToListField;
        }
    },

    /**
     * Hide the view if we were not able to find the appropriate list field and somehow render is triggered
     */
    _render: function() {
        var result = this._super("_render");

        if(_.isUndefined(this.addToListField)) {
            this.hide();
        }
        return result;
    },

    /**
     * There is only one field for this view, so it is the default as well
     */
    setDefault: function() {
        this.defaultOption = this.addToListField;
    },

    /**
     * When adding to a target list, the API is expecting an array of IDs
     */
    getAttributes: function() {
        var attributes = {};
        attributes[this.addToListFieldName] = [
            this.model.get(this.addToListField.id_name)
        ];
        return attributes;
    },

    /**
     * Build dynamic success messages to be displayed if the API call is successful
     * Overridden to build different success messages from massupdate
     *
     * @param massUpdateModel - contains the attributes of what records are being updated
     */
    buildSaveSuccessMessages: function(massUpdateModel) {
        var doneLabel = 'TPL_MASS_ADD_TO_LIST_SUCCESS',
            queuedLabel = 'TPL_MASS_ADD_TO_LIST_QUEUED',
            listName = this.model.get(this.addToListField.name),
            listId = this.model.get(this.addToListField.id_name),
            listUrl = '#' + app.router.buildRoute(this.listModule, listId);

        return {
            done: app.lang.get(doneLabel, null, {
                listName: listName,
                listUrl: listUrl
            }),
            queued: app.lang.get(queuedLabel, null, {
                listName: listName,
                listUrl: listUrl
            })
        };
    },

    /**
     * Create a new target list and select it in the list
     */
    createAndSelectNewList: function() {
        app.drawer.open({
            layout: 'create-nodupecheck',
            context: {
                create: true,
                module: this.listModule
            }
        }, _.bind(this.selectNewlyCreatedList, this));
    },

    /**
     * Callback for create new target list - sets relate field with newly created list
     * @param context
     * @param model newly created target list model
     */
    selectNewlyCreatedList: function(context, model) {
        var relateField = this.getField('prospect_lists_name');
        if (relateField) {
            model.value = model.get('name');
            relateField.setValue(model);
        }
    }
})
