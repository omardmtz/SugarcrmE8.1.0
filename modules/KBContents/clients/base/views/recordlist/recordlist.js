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
     * @inheritdoc
     *
     * Add KBContent plugin for view.
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], [
            'KBContent',
            'KBNotify'
        ]);

        this._super('initialize', [options]);

        this.layout.on('list:record:deleted', function() {
            this.refreshCollection();
            this.notifyAll('kb:collection:updated');
        }, this);

        this.context.on('kbcontents:category:deleted', function(node) {
            this.refreshCollection();
            this.notifyAll('kb:collection:updated');
        }, this);

        if (!app.acl.hasAccessToModel('edit', this.model)) {
            this.context.set('requiredFilter', 'records-noedit');
        }
    }
})
