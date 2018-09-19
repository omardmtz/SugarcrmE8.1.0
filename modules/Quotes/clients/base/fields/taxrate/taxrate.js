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
 * @class View.Fields.Base.Quotes.TaxrateField
 * @alias SUGAR.App.view.fields.BaseQuotesTaxrateField
 * @extends View.Fields.Base.EnumField
 */
({
    extendsFrom: 'RelateField',

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this._super('bindDataChange');

        this.model.on('change:taxrate_value', this._onTaxRateChange, this);
    },

    /**
     * Sets a new "tax" value when the taxrate changes
     *
     * @param {Data.Bean} model The changed model
     * @param {string} taxrateValue The new taxrate value "8.25", "!0", etc
     * @private
     */
    _onTaxRateChange: function(model, taxrateValue) {
        taxrateValue = taxrateValue || '0';

        var taxratePercent = app.math.div(taxrateValue, '100');
        var newTax = app.math.mul(this.model.get('taxable_subtotal'), taxratePercent);

        this.model.set('tax', newTax);
    },

    /**
     * Extending to add taxrate_value to the id/name values
     *
     * @inheritdoc
     */
    _onSelect2Change: function(e) {
        var plugin = $(e.target).data('select2');
        var id = e.val;
        var value;
        var collection;
        var attributes = {};

        if (_.isUndefined(id)) {
            return;
        }

        value = (id) ? plugin.selection.find('span').text() : $(this).data('rname');
        collection = plugin.context;

        if (collection && !_.isEmpty(id)) {
            // if we have search results use that to set new values
            var model = collection.get(id);
            attributes.id = model.id;
            attributes.value = model.get('value');
            attributes.name = model.get('name');
            _.each(model.attributes, function(value, field) {
                if (app.acl.hasAccessToModel('view', model, field)) {
                    attributes[field] = attributes[field] || model.get(field);
                }
            });
        } else if (e.currentTarget.value && value) {
            // if we have previous values keep them
            attributes.id = value;
            attributes.name = e.currentTarget.value;
            attributes.value = value;
        } else {
            // default to empty
            attributes.id = '';
            attributes.name = '';
            attributes.value = '';
        }

        this.setValue(attributes);
    },

    /**
     * Extending to add taxrate_value to the id/name values
     *
     * @inheritdoc
     */
    setValue: function(models) {
        if (!models) {
            return;
        }
        var updateRelatedFields = true;
        var values = {
            taxrate_id: models.id,
            taxrate_name: models.name,
            taxrate_value: models.value
        };

        if (_.isArray(models)) {
            // Does not make sense to update related fields if we selected
            // multiple models
            updateRelatedFields = false;
        }

        this.model.set(values);

        if (updateRelatedFields) {
            // TODO: move this to SidecarExpressionContext
            // check if link field is currently populated
            if (this.model.get(this.fieldDefs.link)) {
                // unset values of related bean fields in order to make the model load
                // the values corresponding to the currently selected bean
                this.model.unset(this.fieldDefs.link);
            } else {
                // unsetting what is not set won't trigger "change" event,
                // we need to trigger it manually in order to notify subscribers
                // that another related bean has been chosen.
                // the actual data will then come asynchronously
                this.model.trigger('change:' + this.fieldDefs.link);
            }
        }
    }
})
