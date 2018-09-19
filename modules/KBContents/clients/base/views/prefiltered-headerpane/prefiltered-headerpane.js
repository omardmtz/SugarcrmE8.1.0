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
 * @class View.Views.Base.PrefilteredHeaderpaneView
 * @alias SUGAR.App.view.views.BasePrefilteredHeaderpaneView
 * @extends View.Views.Base.SelectionHeaderpaneView
 */

({
    extendsFrom: 'SelectionHeaderpaneView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.meta.fields = _.map(this.meta.fields, function(field) {
            if (field.name === 'title') {
                field['formatted_value'] = this.context.get('headerPaneTitle')
                    || this._formatTitle(field['default_value'])
                    || app.lang.get(field['value'], this.module);
                this.title = field['formatted_value'];
            }
            return field;
        }, this);
    }
})
