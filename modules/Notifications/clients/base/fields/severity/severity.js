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
({
    /**
     * Severity Widget.
     *
     * Extends from EnumField widget adding style property according to specific
     * severity.
     */
    extendsFrom: 'EnumField',

    /**
     * An object where its keys map to specific severity and values to matching
     * CSS classes.
     *
     * @property {Object}
     * @protected
     */
    _styleMapping: {
        'default': 'label-unknown',
        alert: 'label-important',
        information: 'label-info',
        other: 'label-inverse',
        success: 'label-success',
        warning: 'label-warning'
    },

    /**
     * @inheritdoc
     *
     * Listen to changes on `is_read` field only if view name matches
     * notifications.
     */
    bindDataChange: function() {
        this._super('bindDataChange');

        if (this.model && this.view.name === 'notifications') {
            this.model.on('change:is_read', this.render, this);
        }
    },

    /**
     * @inheritdoc
     *
     * Inject additional logic to load templates based on different view names
     * according to the following:
     *
     * - `fields/severity/<view-name>-<tpl-name>.hbs`
     * - `fields/severity/<view-template-name>-<tpl-name>.hbs`
     */
    _loadTemplate: function() {
        this._super('_loadTemplate');

        var template = app.template.getField(
            this.type,
            this.view.name + '-' + this.tplName,
            this.model.module
        );

        if (!template && this.view.meta && this.view.meta.template) {
            template = app.template.getField(
                this.type,
                this.view.meta.template + '-' + this.tplName,
                this.model.module
            );
        }

        this.template = template || this.template;
    },

    /**
     * @inheritdoc
     *
     * Defines `severityCss` property based on field value. If current severity
     * does not match a known value its value is used as label and default
     * style is used as well.
     */
    _render: function () {
        var severity = this.model.get(this.name),
            options = app.lang.getAppListStrings(this.def.options);

        this.severityCss = this._styleMapping[severity] || this._styleMapping['default'];
        this.severityLabel = options[severity] || severity;

        this._super('_render');
    }
})
