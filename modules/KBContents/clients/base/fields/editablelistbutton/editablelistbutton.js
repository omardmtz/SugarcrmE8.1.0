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
 * @class View.Fields.KBContents.EditablelistbuttonField
 * @alias SUGAR.App.view.fields.KBContentsEditablelistbuttonField
 * @extends View.Fields.Base.EditablelistbuttonField
 */
({
    extendsFrom: 'EditablelistbuttonField',

    /**
     * @inheritdoc
     *
     * Add KBNotify plugin for field.
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], [
            'KBNotify'
        ]);
        this._super('initialize', [options]);
    },

    /**
     * Overriding custom save options to trigger kb:collection:updated event when KB model saved.
     *
     * @override
     * @param {Object} options
     */
    getCustomSaveOptions: function(options) {
        var success = _.compose(options.success, _.bind(function(model) {
            this.notifyAll('kb:collection:updated', model);
            return model;
        }, this));
        return {'success': success};
    }
})
