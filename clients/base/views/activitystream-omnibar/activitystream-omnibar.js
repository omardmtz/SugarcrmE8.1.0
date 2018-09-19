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
 * @class View.Views.Base.ActivitystreamOmnibarView
 * @alias SUGAR.App.view.views.BaseActivitystreamOmnibarView
 * @extends View.View
 */
({
    events: {
        'click .addPost': 'addPost',
        'keyup .sayit': '_handleContentChange', //type text
        'change .sayit': '_handleContentChange', //drag text in
        'paste .sayit': '_handleContentPaste' //for IE - right click, paste
    },

    className: 'omnibar',

    plugins: ['DragdropAttachments', 'Taggable', 'Pagination'],

    initialize: function(options) {
        // regular expression to find all non-breaking spaces
        this.nbspRegExp = new RegExp(String.fromCharCode(160), 'g');

        app.view.View.prototype.initialize.call(this, options);

        // Assets for the activity stream post avatar
        this.user_id = app.user.get('id');
        this.full_name = app.user.get('full_name');
        this.picture_url = app.user.get('picture') ? app.api.buildFileURL({
            module: 'Users',
            id: this.user_id,
            field: 'picture'
        }) : '';

        this.toggleSubmitButton = _.debounce(this.toggleSubmitButton, 200);
        this.on('attachments:add attachments:remove attachments:end', this.toggleSubmitButton, this);
        this.on('attachments:start', this.disableSubmitButton, this);
    },

    /**
     * Initialize Taggable plugin so that it knows which record the tags are
     * associated with.
     */
    bindDataChange: function() {
        if (this.context.parent) {
            this.context.parent.on('change', function(context) {
                var moduleName = context.get('module'),
                    modelId = context.get('model').get('id');

                this.setTaggableRecord(moduleName, modelId);
            }, this);
        }
        app.view.View.prototype.bindDataChange.call(this);
    },

    /**
     * Remove events added in bindDataChange().
     */
    unbindData: function() {
        if (this.context.parent) {
            this.context.parent.off(null, null, this);
        }
        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');

        if (!app.config.activityStreamsEnabled) {
            this.$el.addClass('hide');
        }

        return this;
    },

    /**
     * Creates a new post.
     */
    addPost: function() {
        var self = this,
            parentId = this.context.parent.get('model').id,
            parentType = this.context.parent.get('model').module,
            attachments = this.$('.activitystream-pending-attachment'),
            bean;

        // Process "Home" and "Activity" layouts as global activity stream types
        if (parentType == 'Home' || parentType == 'Activities') {
            parentType = null;
            parentId = null;
        }

        var payload = {
            activity_type: 'post',
            parent_id: parentId || null,
            parent_type: parentType,
            data: {}
        };

        if (!this.isSubmitDisabled()) {
            payload.data = this.getPost();

            if (payload.data.value && (payload.data.value.length > 0)) {
                this.disableSubmitButton();
                bean = app.data.createBean('Activities');
                bean.save(payload, {
                    success: function(model) {
                        self.$('div.sayit')
                            .empty()
                            .trigger('change')
                            .focus();

                        model.set('picture', app.user.get('picture'));
                        self.collection.add(model);
                        self.context.trigger('activitystream:post:prepend', model);
                    },
                    complete: function() {
                        self.enableSubmitButton();
                    },
                    showAlerts: true
                });
            }

            this.trigger("attachments:process");
        }
    },

    /**
     * Retrieve the post entered inside content editable and translate any tags into text format
     * so that it can be saved in the database as JSON string.
     *
     * @return {string}
     */
    getPost: function() {
        var post = this.unformatTags(this.$('div.sayit'));

        // Need to replace all non-breaking spaces with a regular space because the EmbedLinkService.php
        // treats spaces and non-breaking spaces differently. Having non-breaking spaces causes to parse
        // URLs incorrectly.
        post.value = post.value.replace(this.nbspRegExp, ' ');

        return post;
    },

    /**
     * Check to see if the Submit button should be disabled/enabled.
     */
    isSubmitDisabled: function() {
        var post = this.getPost(),
            attachments = this.getAttachments();

        return post.value.length === 0 && _.size(attachments) === 0;
    },

    /**
     * Toggle the Submit button disabled/enabled state.
     */
    toggleSubmitButton: function() {
        if (this.isSubmitDisabled()) {
            this.disableSubmitButton();
        } else {
            this.enableSubmitButton();
        }
    },

    /**
     * Enable Submit button
     */
    enableSubmitButton: function() {
        this.$('.addPost')
            .removeClass('disabled')
            .attr('aria-disabled', false)
            .attr('tabindex', 0);
    },

    /**
     * Disable Submit button
     */
    disableSubmitButton: function() {
        this.$('.addPost')
            .addClass('disabled')
            .attr('aria-disabled', true)
            .attr('tabindex', -1);
    },

    /**
     * Show or hide the placeholder and toggle the submit button in response to
     * a content change in the input field.
     *
     * @param e
     * @private
     */
    _handleContentChange: function(e) {
        // We can't use any of the jQuery methods or use the dataset property to
        // set this attribute because they don't seem to work in IE 10. Dataset
        // isn't supported in IE 10 at all.
        var el = e.currentTarget;
        if (el.textContent) {
            el.setAttribute('data-hide-placeholder', 'true');
        } else {
            el.removeAttribute('data-hide-placeholder');
        }
        this.toggleSubmitButton();
    },

    /**
     * Wrapper around _handleContentChange to defer until paste event completes
     * Paste event needed for IE (right click, paste)
     *
     * @param e
     * @private
     */
    _handleContentPaste: function(e) {
        _.defer(_.bind(this._handleContentChange, this), e);
    }
})
