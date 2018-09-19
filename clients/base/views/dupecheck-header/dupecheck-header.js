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
 * @class View.Views.Base.DupecheckHeaderView
 * @alias SUGAR.App.view.views.BaseDupecheckHeaderView
 * @extends View.View
 */
({

    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        this.context.on('dupecheck:collection:reset', this.updateCount, this);
     },

    updateCount: function() {
        var translatedString = app.lang.get(
            'LBL_DUPLICATES_FOUND',
            this.module,
            {'duplicateCount': this.collection.length}
        );
        this.$('span.duplicate_count').text(translatedString);
    }
})
