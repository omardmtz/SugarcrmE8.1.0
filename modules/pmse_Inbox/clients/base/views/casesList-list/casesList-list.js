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
    extendsFrom: 'RecordlistView',

    /**
     * Removes the event listeners that were added to the mass collection.
     */
    unbindData: function() {
        var massCollection = this.context.get('mass_collection');
        if (massCollection) {
            massCollection.off(null, null, this);
        }
        this._super("unbindData");
    },

    /**
     * @inheritdoc
     */
    _setOrderBy: function(options) {
        this.context.set('sortOptions', options);
        options.query = this.context.get('query');
        options.module_list = this.context.get('module_list');
        options.offset = 0;
        options.update = false;
        this._super('_setOrderBy', options);
    },

    /**
     * Override to hook in additional triggers as the mass collection is updated (rows are checked on/off in
     * the actionmenu field). Also attempts to pre-check any rows when the list is refreshed and selected recipients
     * are found within the new result set (this behavior occurs when the user searches the address book).
     *
     * @private
     */
    _render: function() {
        if (app.acl.hasAccessToAny('developer')) {
            this._super('_render');
        }
        else {
            app.controller.loadView({
                layout: 'access-denied'
            });
        }
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        jQuery('.adam-modal').remove();
        this._super('_dispose');
    }
})
