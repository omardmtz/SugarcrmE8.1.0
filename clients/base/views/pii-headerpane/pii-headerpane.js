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
 * @class View.Views.Base.PiiHeaderpaneView
 * @alias SUGAR.App.view.views.BasePiiHeaderpaneView
 * @extends View.Views.Base.HeaderpaneView
 */
({
    extendsFrom: 'HeaderpaneView',

    events: {
        'click a[name=close_button]': 'close'
    },

    /**
     * @inheritdoc
     *
     * Initialize and bind close drawer shortcut keys.
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        app.shortcuts.register({
            id: 'PiiHeaderPanel:Close',
            keys: ['esc','mod+alt+l'],
            component: this,
            description: 'LBL_SHORTCUT_CLOSE_DRAWER',
            callOnFocus: true,
            handler: function() {
                var $closeButton = this.$('a[name=close_button]');
                if ($closeButton.is(':visible') && !$closeButton.hasClass('disabled')) {
                    $closeButton.click();
                }
            }
        });
    },

    /**
     * Closes the drawer.
     */
    close: function() {
        app.drawer.close();
    },

    /**
     * @override
     *
     * Overriding to show record name on title header if it is available;
     * if not, use the standard title.
     */
    _formatTitle: function(title) {
        var model = this.context.parent.get('model');
        var recordName = app.utils.getRecordName(model);
        if (recordName) {
            return app.lang.get('TPL_DATAPRIVACY_PII_TITLE', model.module, {name: recordName});
        } else if (title) {
            return app.lang.get(title, this.module);
        } else {
            return '';
        }
    }
})
