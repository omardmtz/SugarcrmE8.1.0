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
 * @class View.Views.Base.ConfigDrawerHowtoView
 * @alias SUGAR.App.view.views.BaseConfigDrawerHowtoView
 * @extends View.View
 */
({
    howtoData: {},

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.context.on('config:howtoData:change', function(howtoData) {
            this.howtoData = howtoData;
            this._render();
        }, this);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        // manually render this template with just the howtoData
        this.$el.html(this.template(this.howtoData))
    }
})
