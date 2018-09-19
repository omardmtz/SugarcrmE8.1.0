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
 * @class View.Views.Base.pmse_Inbox.PreviewView
 * @alias SUGAR.App.view.views.Basepmse_InboxPreviewView
 * @extends View.Views.Base.PreviewView
 */
({
    extendsFrom: 'PreviewView',

    events: {
        'click .minify': 'toggleMinify'
    },

    toggleMinify: function(evt) {
        var $el = this.$('.dashlet-toggle > i'),
            collapsed = $el.is('.icon-chevron-up');
            if(collapsed){
                $('.dashlet-toggle > i').removeClass('icon-chevron-up');
            $('.dashlet-toggle > i').addClass('icon-chevron-down');
        }else{
                $('.dashlet-toggle > i').removeClass('icon-chevron-down');
                $('.dashlet-toggle > i').addClass('icon-chevron-up');
            }
        $('.dashlet').toggleClass('collapsed');
        $('.dashlet-content').toggleClass('hide');
    },

    /**
     * Renders the preview dialog with the data from the current model and collection.
     */
    _render: function() {
        var self = this;

        //only use id2 if it's available
        if (this.model.get('id2')) {
            this.model.set('id', this.model.get('id2'));
        }

        var pmseInboxUrl = app.api.buildFileURL({
            module: 'pmse_Inbox',
            id: self.model.get('cas_id') || (self.model.collection.get(self.model)).get('cas_id'),
            field: 'id'
        }, {cleanCache: true});
        this.image_preview_url = pmseInboxUrl;

        this._super('_render');
    }
});
