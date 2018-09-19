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
 * @class View.Views.Base.Tags.PreviewView
 * @alias SUGAR.App.view.views.BaseTagsPreviewView
 * @extends View.Views.Tags.PreviewView
 */
({
    extendsFrom: 'PreviewView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        // Initialize collection
        this.collection_preview = app.data.createBeanCollection('Tags');
    },

    /**
     * @inheritdoc
     */
    saveClicked: function() {
        var options = {
            showAlerts: true,
            success: _.bind(this.handleTagSuccess, this),
            error: _.bind(this.handleTagError, this)
        };
        this.checkForTagDuplicate(options);
    },

    /**
     * Handle fetch error
     */
    handleTagError: function() {
        app.alert.show('collections_error', {
            level: 'error',
            messages: 'LBL_TAG_FETCH_ERROR'
        });
    },

    /**
     * Handle fetch success
     * @param {array} collection_preview
     */
    handleTagSuccess: function(collection_preview) {
        if (collection_preview.length > 0) {
            // duplicate found, warn user and quit
            app.alert.show('tag_duplicate', {
                level: 'warning',
                messages: app.lang.get('LBL_EDIT_DUPLICATE_FOUND', 'Tags')
            });
        } else {
            // no duplicate found, continue with save
            this._super('saveClicked');
        }
    },

    /**
     * Check to see if new name is a duplicate
     * @param options
     */
    checkForTagDuplicate: function(options) {
        this.collection_preview.filterDef = [{
            'name_lower': {'$equals': this.model.get('name').toLowerCase()}
        }, {
            'id': {'$not_equals': this.model.get('id')}
        }];

        this.collection_preview.fetch(options);
    }
})
