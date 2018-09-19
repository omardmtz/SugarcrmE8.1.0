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
 * @class View.Views.Base.pmse_Project.PreviewView
 * @alias SUGAR.App.view.views.Basepmse_ProjectPreviewView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: 'PreviewView',

    events: {
        'click .minify': 'toggleMinify'
    },

    toggleMinify: function (evt) {
        var $el = this.$('.dashlet-toggle > i'),
            collapsed = $el.is('.icon-chevron-up');
        if (collapsed) {
            $('.dashlet-toggle > i').removeClass('icon-chevron-up');
            $('.dashlet-toggle > i').addClass('icon-chevron-down');
        } else {
            $('.dashlet-toggle > i').removeClass('icon-chevron-down');
            $('.dashlet-toggle > i').addClass('icon-chevron-up');
        }
        $('.dashlet').toggleClass('collapsed');
        $('.dashlet-content').toggleClass('hide');
    },

    /**
     * @override Overriding so we can set this.image_preview_url for the
     * Process Definition image
     */
    _render: function() {
        if (this.model) {
            var pmseInboxUrl = app.api.buildFileURL({
                module: 'pmse_Project',
                id: this.model.get('id'),
                field: 'id'
            }, {cleanCache: true});
            this.image_preview_url = pmseInboxUrl;
        }

        this._super('_render');
    }
});
