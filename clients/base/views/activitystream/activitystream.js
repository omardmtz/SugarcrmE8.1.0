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
 * @class View.Views.Base.ActivitystreamView
 * @alias SUGAR.App.view.views.BaseActivitystreamView
 * @extends View.View
 */
({
    events: {
        'change div[data-placeholder]': 'checkPlaceholder',
        'keydown div[data-placeholder]': 'checkPlaceholder',
        'keypress div[data-placeholder]': 'checkPlaceholder',
        'input div[data-placeholder]': 'checkPlaceholder',
        'click .reply': 'showAddComment',
        'click .reply-btn': 'addComment',
        'click .preview-btn:not(.disabled)': 'previewRecord',
        'click .comment-btn': 'toggleReplyBar',
        'click .more': 'fetchComments'
    },

    tagName: "li",
    className: "activitystream-posts-comments-container",
    plugins: ['RelativeTime', 'FileDragoff', 'Taggable'],
    cacheNamePrefix: "user:avatars:",
    cacheNameExpire: ":expiry",
    expiryTime: 36000000,   //1 hour in milliseconds
    thresholdRelativeTime: 2, //Show relative time for 2 days and then date time after

    _unformattedPost: null,
    _unformattedComments: {},

    // Based on regular expression from http://daringfireball.net/2010/07/improved_regex_for_matching_urls
    // It is the JavaScript regular expression version of the one in LinkEmbed.php
    urlRegExp: /\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/ig,

    _attachImageSelector: 'img[data-note-id]',

    /**
     * Modules placed in this array will have links to the module removed from the display
     * e.g. Quotas isn't a real module, has no record view, shouldn't have a link
     */
    blacklistModules: [
        'Quotas'
    ],

    initialize: function(options) {
        this.readonly = !!options.readonly;
        this.nopreview = !!options.nopreview;

        app.view.View.prototype.initialize.call(this, options);

        var lastComment = this.model.get("last_comment");
        this.commentsCollection = app.data.createRelatedCollection(this.model, "comments");

        if (lastComment && !_.isUndefined(lastComment.id)) {
            this.commentsCollection.reset([lastComment]);
        }

        this.model.set("comments", this.commentsCollection);

        // If comment_count is 0, we don't want to decrement the count by 1 since -1 is truthy.
        var count = parseInt(this.model.get('comment_count'), 10);
        this.remaining_comments = 0;
        this.more_tpl = "TPL_MORE_COMMENT";
        if (count) {
            this.remaining_comments = count - 1;

            // Pluralize the comment count label
            if (count > 2) {
                this.more_tpl += "S";
            }
        }

        this.preview = this.getPreviewData();
        var data = this.model.get('data');
        var activity_type = this.model.get('activity_type');

        this.tpl = "TPL_ACTIVITY_" + activity_type.toUpperCase();

        switch(activity_type) {
            case 'post':
                if (!data.value) {
                    this.tpl = null;
                }
                break;
            case 'update':
                data.updateStr = this.processUpdateActivityTypeMessage(data.changes);
                this.model.set('data', data);
                break;
            case 'attach':
                var url,
                    urlAttributes = {
                    module: 'Notes',
                    id: data.noteId,
                    field: 'filename'
                };

                if (data.mimetype && data.mimetype.indexOf("image/") === 0) {
                    url = app.api.buildFileURL(urlAttributes, {
                        htmlJsonFormat: false,
                        passOAuthToken: false,
                        cleanCache: true,
                        forceDownload: false
                    });

                    data.embeds = [{
                        type: "image",
                        src: url,
                        noteId: data.noteId
                    }];
                } else {
                    url = app.api.buildFileURL(urlAttributes);
                }

                data.url = url;
                this.$el.data(data);
                this.model.set('data', data);
                this.model.set('display_parent_type', 'Files');
                break;
        }

        this.processEmbed();
        this.toggleSubmitButton = _.debounce(this.toggleSubmitButton, 200);

        // Resize video when the browser window is resized
        this.resizeVideo = _.bind(_.throttle(this.resizeVideo, 500), this);
        $(window).on('resize.' + this.cid, this.resizeVideo);

        // specify the record that the tags are associated with
        this.setTaggableRecord(this.model.get('parent_type'), this.model.get('parent_id'));
    },

     /**
     * Creates the update message for the activity stream based on the fields changed.
     * @param {object} changes Object containing the changes for the fields of an update activity message
     * @return {string} The formatted message for the update
     */
     processUpdateActivityTypeMessage: function (changes) {
         var updateTpl = Handlebars.compile(app.lang.get('TPL_ACTIVITY_UPDATE_FIELD', 'Activities')),
             parentType = this.model.get("parent_type"),
             fields = app.metadata.getModule(parentType).fields,
             self = this,
             updateStr;

         updateStr = _.reduce(changes, function (memo, changeObj) {
             var fieldMeta = fields[changeObj.field_name],
                 field = app.view.createField({
                     def: fieldMeta,
                     view: self,
                     model: self.model,
                     viewName: 'detail'
                 });

             changeObj.before = field.format(changeObj.before);
             changeObj.after = field.format(changeObj.after);
             changeObj.field_label = app.lang.get(fields[changeObj.field_name].vname, parentType);

             if (memo) {
                 return updateTpl(changeObj) + ', ' + memo;
             }
             return updateTpl(changeObj);
         }, '');

         return updateStr;
     },

    /**
     * Get embed templates to be processed on render
     */
    processEmbed: function() {
        var data = this.model.get('data');

        if (!_.isEmpty(data.embeds)) {
            this.embeds = [];
            _.each(data.embeds, function(embed) {
                var typeParts = embed.type.split('.'),
                    type = typeParts.shift(),
                    embedTpl;

                _.each(typeParts, function(part) {
                    type = type + part.charAt(0).toUpperCase() + part.substr(1);
                });

                embedTpl = app.template.get(this.name + '.' + type + 'Embed');
                if (embedTpl) {
                    this.embeds.push(embedTpl(embed));
                }
            }, this);
        }
    },

    fetchComments: function() {
        var self = this;
        this.commentsCollection.fetch({
            //Don't show alerts for this request
            showAlerts: false,
            relate: true,
            success: function(collection) {
                self.remaining_comments = 0;
                self.render();
            }
        });
    },

    /**
     * Event handler for clicking comment button -- shows a post's comment box.
     * @param  {Event} e
     */
    showAddComment: function(e) {
        var currentTarget = this.$(e.currentTarget);

        currentTarget.closest('li').find('.activitystream-comment').toggle();
        currentTarget.closest('li').find('.activitystream-comment').find('.sayit').focus();

        e.preventDefault();
    },

    /**
     * Creates a new comment on a post.
     * @param {Event} event
     */
    addComment: function (event) {
        var self = this,
            parentId = this.model.id,
            payload = {
                parent_id: parentId,
                data: {}
            },
            bean;

        payload.data = this.getComment();

        if (payload.data.value && (payload.data.value.length > 0)) {
            bean = app.data.createRelatedBean(this.model, null, 'comments');
            bean.save(payload, {
                relate: true,
                success: _.bind(self.addCommentCallback, self)
            });
        }
    },

    /**
     * Callback for rendering a newly added comment into the activity stream view
     * @param  {Object} model
     */
    addCommentCallback: function (model) {
        var template, data;

        this.$('div.reply').empty().trigger('change');
        this.commentsCollection.add(model);
        this.toggleReplyBar();

        template = app.template.getView('activitystream.comment');

        data = model.get('data');
        data.value = this.formatTags(data.value);
        data.value = this.formatLinks(data.value);

        this.processAvatars();
        this.$('.comments').prepend(template(model.attributes));
        this.context.trigger('activitystream:post:prepend', this.model);
    },

    /**
     * Handler for previewing a record listed on the activity stream.
     * @param  {Event} event
     */
    previewRecord: function(event) {
        var el = this.$(event.currentTarget),
            data = el.data(),
            module = data.module,
            id = data.id;

        // Remove highlighted styling from all activities
        this.layout.clearRowDecorations();

        // If module/id data attributes don't exist, this user
        // doesn't have access to that record due to team security.
        if (module && id) {
            var model = app.data.createBean(module),
                collection = this.context.get("collection");

            model.set("id", id);
            this.decorateRow();
            app.events.trigger("preview:module:update", this.context.get("module"));
            app.events.trigger("preview:render", model, collection, true, this.cid);
        }

        event.preventDefault();
    },

    /**
     * Handles highlighting of current activity item and active state of preview button.
     */
    decorateRow: function() {
        this.$el.addClass('highlighted');
        this.$('.preview-btn')
            .addClass('active')
            .attr('aria-pressed', true);
    },

    _renderHtml: function(model) {
        // Save state of the reply bar before rendering
        var isReplyBarOpen = this.$(".comment-btn").hasClass("active") && this.$(".comment-btn").is(":visible"),
            replyVal = this.$(".reply").html();

        this.processAvatars();
        this.formatAllTagsAndLinks();

        this._setRelativeTimeAvailable();

        app.view.View.prototype._renderHtml.call(this);

        this.resizeVideo();

        // If the reply bar was previously open, keep it open (render hides it by default)
        if (isReplyBarOpen) {
            this.toggleReplyBar();
            this.$(".reply").html(replyVal);
        }

        this._addBrokenImageHandler();
    },

    /**
     * Add a listener for when activity has a broken image for attach type posts
     * Remove the broken image and remove link to broken image
     *
     * @private
     */
    _addBrokenImageHandler: function() {
        this.$(this._attachImageSelector).on('error', _.bind(function(event) {
            var $brokenImg = $(event.currentTarget),
                linkSelector = 'a[data-note-id="' + $brokenImg.data('note-id') + '"]';

            //first remove the link to the image which will also be broken
            //FIXME: this is hacky, but temporary until we fix how attachment posts are displayed in MAR-780
            this.$(linkSelector).contents().unwrap();
            //then remove the broken image
            $brokenImg.closest('div[class="embed"]').remove();
        }, this));
    },

    /**
     * Sets property on activity to show date created as a relative time or as date time.
     *
     * @private
     */
    _setRelativeTimeAvailable: function() {
        var diffInDays = app.date().diff(this.model.get('date_entered'), 'days', true);
        this.useRelativeTime = (diffInDays <= this.thresholdRelativeTime);
    },

    /**
     * Format all tags and link in post and comments.
     */
    formatAllTagsAndLinks: function() {
        var post = this.model.get('data');

        // Check to see if the post's module is in the blacklist, if so, delete
        // the module property from the object so it will not create a link to the record
        if (post.object && post.object.module && _.contains(this.blacklistModules, post.object.module)) {
            delete post.object.module;
        }

        this.unformatAllTagsAndLinks();

        if (post) {
            this._unformattedPost = post.value;
            post.value = this.formatLinks(post.value);
            post.value = this.formatTags(post.value);
        }

        this.commentsCollection.each(function(model) {
            var data = model.get('data');
            this._unformattedComments[model.get('id')] = data.value;
            data.value = this.formatLinks(data.value);
            data.value = this.formatTags(data.value);
        }, this);
    },

    /**
     * Revert back to the unformatted version of tags and links
     */
    unformatAllTagsAndLinks: function() {
        var post = this.model.get('data');
        if (post) {
            post.value = this._unformattedPost || post.value;
        }

        this.commentsCollection.each(function(model) {
            var data = model.get('data');
            data.value = this._unformattedComments[model.get('id')] || data.value;
        }, this);
    },

    /**
     * Searches the post to identify links and make them as actual links
     *
     * @param {String} post
     * @return {string}
     */
    formatLinks: function(post) {
        var formattedPost = '';

        if (post && (post.length > 0)) {
            formattedPost = post.replace(this.urlRegExp, function(url) {
                var href = url;
                if ((url.indexOf('http://') !== 0) && (url.indexOf('https://') !== 0)) {
                    href = 'http://' + url;
                }
                return '<a href="' + href + '" target="_blank">' + url + '</a>';
            });
        }

        return formattedPost;
    },

    /**
     * Resize the iframe that embeds video
     */
    resizeVideo: function() {
        // if this is disposed, then just bail as the code below with throw errors
        if (this.disposed === true) {
            return;
        }
        var data = this.model.get('data'),
            $embed = this.$('.embed'),
            $iframes = $embed.find('iframe'),
            videoCount = 0,
            embedWidth;

        if (_.isArray(data.embeds)) {
            embedWidth = $embed.width();
            _.each(data.embeds, function(embed) {
                var $iframe, iframeWidth, iframeHeight;

                if (((embed.type === 'video') || (embed.type === 'rich')) && ($iframes.length > 0)) {
                    $iframe = $iframes.eq(videoCount);

                    iframeWidth = Math.min(embedWidth, 480);
                    iframeHeight = parseInt(embed.height, 10) * (iframeWidth / parseInt(embed.width, 10));

                    $iframe.prop({
                        width: iframeWidth,
                        height: iframeHeight
                    });

                    videoCount++;
                }
            });
        }
    },

    /**
     * Sets the profile picture for activities based on the created by user.
     */
    processAvatars: function () {
        var comments = this.model.get('comments'),
            postPictureUrl;

        if (this.model.get('activity_type') === 'post' && !this.model.get('picture_url')) {
            postPictureUrl = this.getAvatarUrlForUser(this.model, 'post');
            this.model.set('picture_url', postPictureUrl);
        }

        if (comments) {
            comments.each(function (comment) {
                var commentPictureUrl = this.getAvatarUrlForUser(comment, 'comment');
                comment.set('picture_url', commentPictureUrl);
            }, this);
        }
    },

    /**
     * Builds and returns the url for the user's profile picture based on fetching from cache
     * @param model
     * @param activityType
     * @return {string}
     */
    getAvatarUrlForUser: function (model, activityType){
        var createdBy = model.get('created_by'),
            hasPicture = this.checkUserHasPicture(model, activityType);

        return hasPicture ? this.buildAvatarUrl(createdBy) : '';
    },

    /**
     * Checks cache to see if user has a picture, calls API if needed
     *
     * @param model The User
     * @param activityType
     * @return {boolean} whether user has a picture
     */
    checkUserHasPicture: function (model, activityType) {
        var createdBy = model.get('created_by'),
            hasPicture;

        // If processing the current user's avatar, no need to fetch
        if (createdBy === app.user.get('id')) {
            hasPicture = !_.isEmpty(app.user.get('picture'));
        } else {
            // Check cache
            hasPicture = this.getUserPictureStatus(createdBy);
        }

        // If not current user or cached, call api to check if user has a picture
        if (_.isUndefined(hasPicture)) {
            this.fetchUserPicture(model, activityType);
            hasPicture = false; // Use placeholder until api call finishes
        }

        return hasPicture;
    },

    /**
     * Retrieves a user and caches the results of whether the user has a profile picture.
     * Replaces the default icon with an image tag of the profile picture.
     *
     * @param model
     * @param activityType
     */
    fetchUserPicture: function(model, activityType) {
        var self = this,
            createdBy = model.get('created_by'),
            user = app.data.createBean('Users', {id: createdBy});

        user.fetch({
            fields: ["picture"],
            success: function () {
                var pictureUrl = self.buildAvatarUrl(createdBy),
                    hasPicture = !_.isEmpty(user.get('picture'));

                self.setUserPictureStatus(createdBy, hasPicture);

                // If picture exists, replace the activity image with the user's profile picture
                if (hasPicture) {
                    self.$('#avatar-' + activityType + '-' + model.get('id')).html("<img src='" + pictureUrl + "' alt='" + model.get('created_by_name') + "'>");
                }
            },
            error: function () {
                // Problem retrieving picture, use placeholder
                self.setUserPictureStatus(createdBy, false);
            }
        });
    },

    /**
     * Retrieve from the app cache whether user has a picture
     * Respects cache TTL, returns undefined if expired
     *
     * @param userId
     * @return {boolean|undefined} whether user has picture or `undefined` if cache not set or expired
     * @private
     */
    getUserPictureStatus: function(userId) {
        var hasPicture = app.cache.get(this.cacheNamePrefix + userId),
            cachedTTL = app.cache.get(this.cacheNamePrefix + userId + this.cacheNameExpire);

        return (cachedTTL < $.now()) ? undefined : hasPicture;
    },

    /**
     * Cache whether the user has a picture or not
     *
     * @param userId
     * @param hasPicture
     * @private
     */
    setUserPictureStatus: function(userId, hasPicture) {
        app.cache.set(this.cacheNamePrefix + userId, hasPicture);
        app.cache.set(this.cacheNamePrefix + userId + this.cacheNameExpire, $.now() + this.expiryTime);
    },

    /**
     * Build the file url for the given user's avatar
     *
     * @param userId
     * @return {string} The avatar url
     * @private
     */
    buildAvatarUrl: function(userId) {
        return app.api.buildFileURL({
            module: 'Users',
            id: userId,
            field: 'picture'
        });
    },

    toggleReplyBar: function() {
        var isHidden = this.$('.reply-area').hasClass('hide');
        this.$('.reply-area').toggleClass('hide', !isHidden);
        this.$('.comment-btn')
            .toggleClass('active', isHidden)
            .attr('aria-pressed', isHidden);
    },

    /**
     * Retrieve comment entered inside content editable and translate any tags into text format
     * so that it can be saved in the database as JSON string.
     *
     * @return {string}
     */
    getComment: function() {
        return this.unformatTags(this.$('div.reply'));
    },

    /**
     * Determines the status and label for the preview button.
     *
     * @return {Object}
     * @return {Boolean} return.enabled Whether the preview is enabled.
     * @return {String} return.label The label to display in the preview button
     *   tooltip.
     */
    getPreviewData: function () {
        var parentModel,
            preview = {
                enabled: true,
                label: 'LBL_PREVIEW'
            },
            isBwcEnabled,
            module = this.model.get('display_parent_type');

        if (module) {
            // assume modules without metadata are BWC by default.
            isBwcEnabled = true;
            var moduleMetadata = app.metadata.getModule(module);
            if (moduleMetadata && _.has(moduleMetadata, 'isBwcEnabled')) {
                isBwcEnabled = moduleMetadata.isBwcEnabled;
            }
        } else {
            isBwcEnabled = false;
        }

        if (isBwcEnabled) {
            preview.enabled = false;
            preview.label = 'LBL_PREVIEW_BWC_TOOLTIP';
        } else if (this.model.get("activity_type") === 'attach') { //no preview for attachments
            preview.enabled = false;
            preview.label = 'LBL_PREVIEW_DISABLED_ATTACHMENT';
        } else if (_.isEmpty(this.model.get('display_parent_id')) || _.isEmpty(this.model.get('display_parent_type'))) {  //no related record
            preview.enabled = false;
            preview.label = 'LBL_PREVIEW_DISABLED_NO_RECORD';
        } else if (!app.acl.hasAccess("view", this.model.get('display_parent_type'))) { //no access to related record
            preview.enabled = false;
            preview.label = 'LBL_PREVIEW_DISABLED_NO_ACCESS';
        } else if (this.model.get('preview_enabled') === false) { //deleted or no team access to related record
            preview.enabled = false;
            preview.label = this.model.get('preview_disabled_reason');
        } else {
            parentModel = this._getParentModel('record', this.context);
            // Check if the bean to be previewed is the same as the context.
            if (parentModel && parentModel.module == this.model.get('display_parent_type') && parentModel.id === this.model.get('display_parent_id')) {
                preview.enabled = false;
                preview.label = 'LBL_PREVIEW_DISABLED_SAME_RECORD';
            }
        }

        return preview;
    },

    /**
     * Traverse up the context hierarchy and look for given layout, retrieve the model from the layout's context
     *
     * @param layoutName to look for up the context hierarchy
     * @param context start of context hierarchy
     * @return {Mixed}
     * @private
     */
    _getParentModel: function(layoutName, context) {
        if (context) {
            if (context.get('layout') === layoutName) {
                return context.get('model');
            } else {
                return this._getParentModel(layoutName, context.parent);
            }
        } else {
            return null;
        }
    },

    /**
     * Toggle the Submit button disabled/enabled state.
     */
    toggleSubmitButton: function(enable) {
        if (!enable) {
            this.disableSubmitButton();
        } else {
            this.enableSubmitButton();
        }
    },

    /**
     * Enable Submit button
     */
    enableSubmitButton: function() {
        this.$('.reply-btn')
            .removeClass('disabled')
            .attr('aria-disabled', false)
            .attr('tabindex', 0);
    },

    /**
     * Disable Submit button
     */
    disableSubmitButton: function() {
        this.$('.reply-btn')
            .addClass('disabled')
            .attr('aria-disabled', true)
            .attr('tabindex', -1);
    },

    /**
     * If the reply area has content, remove placeholder and
     * enable the reply button
     */
    checkPlaceholder: function(e) {
        // We can't use any of the jQuery methods or use the dataset property to
        // set this attribute because they don't seem to work in IE 10. Dataset
        // isn't supported in IE 10 at all.
        var el = e.currentTarget;
        if (el.textContent) {
            el.setAttribute('data-hide-placeholder', 'true');
            this.toggleSubmitButton(true);
        } else {
            el.removeAttribute('data-hide-placeholder');
            this.toggleSubmitButton(false);
        }
    },

    /**
     * Data change event.
     */
    bindDataChange: function () {
        if (this.commentsCollection) {
            this.commentsCollection.on("add", function () {
                this.model.set('comment_count', this.model.get('comment_count') + 1);
            }, this);
        }
        app.view.View.prototype.bindDataChange.call(this);
    },

    unbindData: function() {
        if (this.commentsCollection) {
            this.commentsCollection.off();
        }
        app.view.View.prototype.unbindData.call(this);
    },

    _dispose: function() {
        $(window).off('resize.' + this.cid);
        this.$(this._attachImageSelector).off('error');
        app.view.View.prototype._dispose.call(this);
        this.commentsCollection = null;
    }
})
