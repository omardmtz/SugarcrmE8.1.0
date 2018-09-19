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
    extendsFrom: 'RecordView',

    /**
     * @inheritdoc
     *
     * Add KBContent plugin for view.
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], [
            'KBContent'
        ]);

        this._super('initialize', [options]);
        this.context.on('kbcontents:category:deleted', this._categoryDeleted, this);
    },

    /**
     * Process record on category delete.
     * @param {Object} node
     * @private
     */
    _categoryDeleted: function(node) {
        if (this.model.get('category_id') === node.data('id')) {
            this.model.unset('category_id');
            this.model.unset('category_name');
        }
        if (this.disposed) {
            return;
        }
        this.render();
    },

    /**
     * @inheritdoc
     *
     * Need to switch field to `edit` if it has errors.
     */
    handleFieldError: function(field, hasError) {
        this._super('handleFieldError', [field, hasError]);
        if (hasError && field.tplName === 'detail') {
            field.setMode('edit');
        }
    }

})
