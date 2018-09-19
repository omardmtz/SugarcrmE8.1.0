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
 * @class View.Fields.Base.Emails.FromField
 * @alias SUGAR.App.view.fields.BaseEmailsFromField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     *
     * This field doesn't support `showNoData`.
     */
    showNoData: false,

    /**
     * The selector for accessing the Select2 field when in edit mode. The
     * Select2 field is where the sender is displayed.
     *
     * @property {string}
     */
    fieldTag: 'input.select2',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['EmailParticipants', 'ListEditable']);
        this._super('initialize', [options]);

        // Specify the error label for when the sender's email address is
        // invalid.
        app.error.errorName2Keys[this.type] = app.lang.get('ERR_INVALID_SENDER', this.module);
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        if (this.model) {
            // Avoids a full re-rendering when editing. The current value of
            // the field is formatted and passed directly to Select2 when in
            // edit mode.
            this.listenTo(this.model, 'change:' + this.name, _.bind(function() {
                var $el = this.$(this.fieldTag);

                if (_.isEmpty($el.data('select2'))) {
                    this.render();
                } else {
                    $el.select2('data', this.getFormattedValue());
                }
            }, this));
        }
    },

    /**
     * @inheritdoc
     */
    bindDomChange: function() {
        var $el = this.$(this.fieldTag);

        $el.on('select2-selecting', _.bind(function(event) {
            if (this.disposed) {
                event.preventDefault();
            }
        }, this));

        $el.on('change', _.bind(function(event) {
            var collection;

            if (this.model && !this.disposed) {
                collection = this.model.get(this.name);

                if (!_.isEmpty(event.added)) {
                    // Replace the current model in the collection, as there
                    // can only be one.
                    collection.remove(collection.models);
                    collection.add(event.added);
                }

                if (!_.isEmpty(event.removed)) {
                    collection.remove(event.removed);
                }
            }
        }, this));
    },

    /**
     * @inheritdoc
     *
     * Destroys the Select2 element.
     */
    unbindDom: function() {
        this.$(this.fieldTag).select2('destroy');
        this._super('unbindDom');
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        var $el;
        var options;

        this._super('_render');

        $el = this.$(this.fieldTag);

        if ($el.length > 0) {
            options = this.getSelect2Options();
            options = _.extend(options, {
                allowClear: !this.def.required,
                multiple: false,

                /**
                 * Constructs a representation for a selected sender to be
                 * displayed in the field.
                 *
                 * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
                 *
                 * @param {Data.Bean} sender
                 * @return {string}
                 * @private
                 */
                formatSelection: _.bind(function(sender) {
                    var template = app.template.getField(this.type, 'select2-selection', this.module);

                    return sender ? template({value: sender.toHeaderString({quote_name: true})}) : '';
                }, this),

                /**
                 * Constructs a representation for the sender to be displayed
                 * in the dropdown options after a query.
                 *
                 * See [Select2 Documentation](http://ivaynberg.github.io/select2/#documentation).
                 *
                 * @param {Data.Bean} sender
                 * @return {string}
                 */
                formatResult: _.bind(function(sender) {
                    var template = app.template.getField(this.type, 'select2-result', this.module);

                    return template({
                        value: sender.toHeaderString({quote_name: true}),
                        module: sender.get('parent_type')
                    });
                }, this),

                /**
                 * Don't escape a choice's markup since we built the HTML.
                 *
                 * See [Select2 Documentation](https://select2.github.io/select2/#documentation).
                 *
                 * @param {string} markup
                 * @return {string}
                 */
                escapeMarkup: function(markup) {
                    return markup;
                }
            });
            $el.select2(options).select2('val', []);

            if (this.isDisabled()) {
                $el.select2('disable');
            }
        }
    },

    /**
     * @inheritdoc
     * @return {Data.Bean}
     */
    format: function(value) {
        // Reset the tooltip.
        this.tooltip = '';

        if (value instanceof app.BeanCollection) {
            value = value.first();

            if (value) {
                value = this.prepareModel(value);
            }

            if (value) {
                this.tooltip = value.toHeaderString();
            }
        }

        return value;
    }
})
