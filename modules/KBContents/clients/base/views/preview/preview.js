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
 * @class View.Views.Base.KBContentsPreviewView
 * @alias SUGAR.App.view.views.BaseKBContentsPreviewView
 * @extends View.Views.Base.PreviewView
 */
({

    extendsFrom: 'PreviewView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['KBContent']);
        this._super('initialize', [options]);
    },

    /**
     * @inheritdoc
     * @TODO: Need to be removed after BR-2704 fixed.
     */
    _previewifyMetadata: function(meta) {
        _.each(meta.panels, function(panel) {
            panel.fields = _.filter(panel.fields, function(def) {
                if (def.type == 'fieldset' && !_.isEmpty(def.fields)) {
                    return _.find(def.fields, function(def) {
                        return def.type !== 'htmleditable_tinymce';
                    }) === undefined;
                }
                return def.type !== 'htmleditable_tinymce';
            });
        }, this);
        return this._super('_previewifyMetadata', [meta]);
    },

    /**
     * We don't need to initialize KB listeners.
     * @override.
     * @private
     */
    _initKBListeners: function() {}
})
