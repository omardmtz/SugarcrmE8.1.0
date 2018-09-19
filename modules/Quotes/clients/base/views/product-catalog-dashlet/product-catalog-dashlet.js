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
 * @class View.Views.Base.Quotes.ProductCatalogDashletView
 * @alias SUGAR.App.view.views.QuotesProductCatalogDashletView
 * @extends View.View
 */
({

    extendsFrom: 'QuotesProductCatalogView',

    plugins: [
        'CanvasDataRenderer',
        'Dashlet'
    ],

    /**
     * Boolean if this is the dashlet config view or not
     */
    isConfig: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.isConfig = !!this.meta.config;
    },

    /**
     * @inheritdoc
     */
    loadData: function(options) {
        if (this.isConfig) {
            return;
        }

        this._super('loadData', [options]);
    },

    /**
     * @inheritdoc
     */
    toggleLoading: function(startLoading, showPhaserLoading) {
        var $el = this.layout.$('i[data-action=loading]');
        if (startLoading) {
            $el.removeClass('fa-cog');
            $el.addClass('fa-refresh fa-spin');
        } else {
            $el.removeClass('fa-refresh fa-spin');
            $el.addClass('fa-cog');
        }
    }
})
