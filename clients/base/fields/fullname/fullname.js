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
 * @class View.Fields.Base.FullnameField
 * @alias SUGAR.App.view.fields.BaseFullnameField
 * @extends View.Fields.Base.FieldsetField
 */
({
    extendsFrom: 'FieldsetField',

    /**
     * Mapping name field name to format initial
     *
     * @property {Object}
     */
    formatMap: {
        'f': 'first_name',
        'l': 'last_name',
        's': 'salutation'
    },

    /**
     * @inheritdoc
     * Sort the dependant fields by the user locale format order.
     */
    initialize: function(options) {
        var formatPlaceholder = app.user.getPreference('default_locale_name_format') || '';
        this._super('initialize', [options]);
        if (!this.module) {
            app.logger.error('Fullname field requires a module');
            this.dispose();
            return;
        }

        var meta = app.metadata.getModule(this.module);
        this.formatMap = meta.formatMap || this.formatMap;

        this.def.fields = _.reduce(formatPlaceholder.split(''), function(fields, letter) {
            // only letters a-z may be significant in the format,
            // everything else is translated verbatim
            if (letter >= 'a' && letter <= 'z' && this.formatMap[letter]) {
                var fieldMeta = meta.fields[this.formatMap[letter]];
                if (fieldMeta) {
                    // clone because we'd rewrite it later and we don't want to mess with actual metadata
                    fields.push(_.clone(fieldMeta));
                }
            }
            return fields;
        }, [], this);
        this.def.fields = app.metadata._patchFields(this.module, meta, this.def.fields);

        if (this.def && this.def.link && app.acl.hasAccessToModel('view', this.model)) {
            var action = this.def.route && this.def.route.action ? this.def.route.action : '';
            //If `this.template` resolves to `base/list.hbs`, that template expects an
            //initialized `this.href`. That's normally handled by the `base.js` controller,
            //but, in this case, since `fullname.js` is controller, we must handle here.
            this.href = '#' + app.router.buildRoute(this.module || this.context,
                this.model.id, action, this.def.bwcLink);
        }
    },

    /**
     * @inheritdoc
     */
    _loadTemplate: function() {
        this._super('_loadTemplate');

        var template = app.template.getField(
            this.type,
            this.view.name + '-' + this.tplName,
            this.model.module);
        //SP-1719: The view-combined template should also follow the view's custom template.
        if (!template && this.view.meta && this.view.meta.template) {
            template = app.template.getField(
                this.type,
                this.view.meta.template + '-' + this.tplName,
                this.model.module);
        }
        this.template = template || this.template;
    },

    /**
     * @inheritdoc
     * Format name parts to current user locale.
     */
    format: function() {
        return app.utils.getRecordName(this.model);
    },

    /**
     * @override
     */
    _isErasedField: function() {
        if (!this.model) {
            return false;
        }

        return app.utils.isNameErased(this.model);
    },

    /**
     * @override
     * Note that the parent bindDataChange (from FieldsetField) is an empty function
     */
    bindDataChange: function() {
        if (this.model) {
            // As detail templates don't contain Sidecar Fields,
            // we need to rerender this field in order to visualize the changes
            this.model.on("change:" + this.name, function() {
                if (this.action !== 'edit') {
                    this.render();
                }
            }, this);
            // When a child field changes, we need to update the full_name value
            _.each(this.def.fields, function(field) {
                this.model.on("change:" + field.name, this.updateValue, this);
            }, this);
        }
    },

    /**
     * Update the value of this parent field when a child changes
     */
    updateValue: function() {
        this.model.set(this.name, this.format());
    },

    /**
     * Called by record view to set max width of inner record-cell div
     * to prevent long names from overflowing the outer record-cell container
     */
    setMaxWidth: function(width) {
        this.$('.record-cell').children().css({'max-width': width});
    },

    /**
     * Return the width of padding on inner record-cell
     */
    getCellPadding: function() {
        var padding = 0,
            $cell = this.$('.record-cell');

        if (!_.isEmpty($cell)) {
            padding = parseInt($cell.css('padding-left'), 10) + parseInt($cell.css('padding-right'), 10);
        }

        return padding;
    },

    _render: function() {
        // FIXME: This will be cleaned up by SC-3478.
        if (this.view.name === 'preview') {
            this.def.link = _.isUndefined(this.def.link) ? true : this.def.link;
        }
        this._super('_render');
    }
})
