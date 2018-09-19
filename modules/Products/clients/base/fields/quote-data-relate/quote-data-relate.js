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
 * @class View.Fields.Base.Products.QuoteDataRelateField
 * @alias SUGAR.App.view.fields.BaseProductsQuoteDataRelateField
 * @extends View.Fields.Base.BaseRelateField
 */
({
    extendsFrom: 'BaseRelateField',

    /**
     * The temporary "(New QLI}" string to add if users type in their own product name
     * @type {string}
     */
    createNewLabel: undefined,

    /**
     * The temporary ID to user for newly created QLI names
     * @type {string}
     */
    newQLIId: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.createNewLabel = app.lang.get('LBL_CREATE_NEW_QLI_IN_DROPDOWN', 'Products');
        this.newQLIId = 'newQLIId';

        this._super('initialize', [options]);
    },

    /**
     * Overriding because getSearchModule needs to return Products for this metadata
     *
     * @inheritdoc
     */
    _getPopulateMetadata: function() {
        return app.metadata.getModule('Products');
    },

    /**
     * Overridden select2 change handler for the custom case of being able to add new unlinked Products
     * @param evt
     * @private
     */
    _onSelect2Change: function(evt) {
        var $select2 = $(evt.target).data('select2');
        var id = evt.val;
        var value = id ? $select2.selection.find('span').text() : $(evt.target).data('rname');
        var collection = $select2.context;
        var model;
        var attributes = {
            id: '',
            value: ''
        };

        if (value && value.indexOf(this.createNewLabel)) {
            // if value had new QLI label, remove it
            value = value.replace(this.createNewLabel, '');
        }

        value = value ? value.trim() : value;

        // default to given id/value or empty strings, cleans up logic significantly
        attributes.id = id || '';
        attributes.value = value || '';

        if (collection && id) {
            // if we have search results use that to set new values
            model = collection.get(id);
            if (model) {
                attributes.id = model.id;
                attributes.value = model.get('name');
                _.each(model.attributes, function(value, field) {
                    if (app.acl.hasAccessToModel('view', model, field)) {
                        attributes[field] = attributes[field] || model.get(field);
                    }
                });
            }
        } else if (evt.currentTarget.value && value) {
            // if we have previous values keep them
            attributes.id = value;
            attributes.value = evt.currentTarget.value;
        }

        // set the attribute values
        this.setValue(attributes);

        if (id === this.newQLIId) {
            // if this is a new QLI
            this.model.set({
                product_template_id: '',
                product_template_name: value,
                name: value
            });
            // update the select2 label
            this.$(this.fieldTag).select2('val', value);
        }

        return;
    },

    /**
     * Extending to add the custom createSearchChoice option
     *
     * @inheritdoc
     */
    _getSelect2Options: function() {
        return _.extend(this._super('_getSelect2Options'), {
            createSearchChoice: _.bind(this._createSearchChoice, this)
        });
    },

    /**
     * Extending to also check models' product_template_name/name and product_template_id/id
     *
     * @inheritdoc
     */
    format: function(value) {
        var idList;
        value = value || this.model.get(this.name) || this.model.get('name');

        this._super('format', [value]);

        // If value is not set (new row item) then the select2 will show the ID and we dont want that
        if (value) {
            idList = this.model.get(this.def.id_name) || this.model.get('id');
            if (_.isArray(value)) {
                this.formattedIds = idList.join(this._separator);
            } else {
                this.formattedIds = idList;
            }

            if (_.isEmpty(this.formattedIds)) {
                this.formattedIds = value;
            }
        }

        return value;
    },

    /**
     * Overriding if there's no product_template_id or name, use the Products module and record ID
     *
     * @inheritdoc
     */
    _buildRoute: function() {
        this.buildRoute(this.model.module, this.model.get('id'));
    },

    /**
     * Overriding as should default to the model's ID then if empty go to the link id
     *
     * @inheritdoc
     */
    _getRelateId: function() {
        return this.model.get(this.def.id_name) || this.model.get('id') ;
    },

    /**
     * Add a new search choice for the user's text
     *
     * @param {string} term The text the user is searching for
     * @return {{id: (*|string), text: *}}
     * @private
     */
    _createSearchChoice: function(term) {
        return {
            id: this.newQLIId,
            text: term + this.createNewLabel
        };
    }
});
