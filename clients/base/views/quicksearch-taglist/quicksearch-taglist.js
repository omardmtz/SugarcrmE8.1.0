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
 * @class View.Views.Base.QuicksearchTagListView
 * @alias SUGAR.App.view.views.BaseQuicksearchTagListView
 * @extends View.View
 */
({
    className: 'table-cell',

    events: {
        'click .tag-remove': 'removeTagClicked',
        'click .tag-name': 'highlightTagClicked'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.selectedTags = this.layout.selectedTags || [];

        this.activeIndex = null;

        // Listener for quicksearch tag additions
        this.layout.on('quicksearch:tag:add', this.addTag, this);

        this.layout.on('quicksearch:tags:remove', this.removeAllTags, this);

        //Listener for receiving focus for up/down arrow navigation:
        this.on('navigate:focus:receive', function(next) {
            if (next) {
                this.activeIndex = 0;
            } else {
                this.activeIndex = this.selectedTags.length - 1;
            }
            this._highlightActive();
            this.attachKeydownEvent();
        }, this);

        // Listener for losing focus for up/down arrow navigation:
        this.on('navigate:focus:lost', function() {
            this.activeIndex = null;
            this.$('.tag-wrapper').removeClass('highlight');
            this.disposeKeydownEvent();
        }, this);

        app.events.on('app:sync:complete', function() {
            this.layout.off('route:search', this.populateTagsFromContext);
            this.layout.on('route:search', this.populateTagsFromContext, this);
        }, this);

        this.context.on('tagsearch:fire:new', this.populateTagsFromContext, this);
    },

    /**
     * Populate the taglist with the tags specified in the context. Call a search if param is true
     */
    populateTagsFromContext: function() {
        var tagNames = this.context.get('tagParams');
        // If no tagNames, just move onto the regular search
        if (!tagNames || !tagNames.length) {
            this.selectedTags.splice(0, this.selectedTags.length);
            this.render();
            this.context.set('tags', []);
            this.context.trigger('search:fire:new');
            return;
        }
        var tags = app.data.createBeanCollection('Tags');
        var self = this;
        var tagNamesLowerCase = _.map(tagNames, function(tagName) {
            return tagName.toLowerCase();
        });

        tags.filterDef = {
            'filter': [{
                'name_lower': { '$in': tagNamesLowerCase }
            }]
        };

        tags.fetch({
            // Arbitrary large number, in case user wants to search by more than 20 tags.
            limit: 100,
            success: function(collection) {
                //Remove internal tag list and then re add the ones that should be there
                self.selectedTags.splice(0, self.selectedTags.length);
                _.each(collection.models, function(tag) {
                    self.selectedTags.push({id: tag.get('id'), name: tag.get('name')});
                });
                self.render();

                //Push completed tag objects to context
                self.context.set('tags', self.selectedTags);
                self.layout.trigger('quicksearch:button:toggle', false);

                self.context.trigger('search:fire:new');
            },
            error: function() {
                app.alert.show('collections_error', {
                    level: 'error',
                    messages: 'LBL_TAG_FETCH_ERROR'
                });
            }
        });
    },

    /**
     * Returns true if there are tags to focus. Otherwise, false.
     */
    isFocusable: function() {
        return this.selectedTags && this.selectedTags.length;
    },

    /**
     * Attach the keydown events for the view.
     */
    attachKeydownEvent: function() {
        $(document).on('keydown.' + this.cid, _.bind(this.keydownHandler, this));
    },

    /**
     * Dispose the keydown events for the view.
     */
    disposeKeydownEvent: function() {
        $(document).off('keydown.' + this.cid);
    },

    /**
     * Handle the keydown events.
     * @param {Event} e
     */
    keydownHandler: function(e) {
        switch (e.keyCode) {
            case 37: // left arrow
                this.moveLeft();
                break;
            case 39: // right arrow
                this.moveRight();
                break;
            case 8:  // backspace
            case 46: // del
                this.handleBackspace();
                e.stopPropagation();
                e.preventDefault();
                break;
            default:
                this.layout.trigger('navigate:to:component', 'quicksearch-bar');
                break;
        }
    },

    /**
     * Handler for the backspace/delete keys. Removes tag if one is highlighted, then highlights a new tag
     * or re-focuses the search bar
     */
    handleBackspace: function() {
        this.removeTag(false);

        if (this.selectedTags.length) {
            // If there is a tag to the left of the removed tag, highlight it
            // If tagIndex is 0, highlight whatever is left at index 0.
            if (this.activeIndex > 0) {
                this.activeIndex--;
            }
            this._highlightActive();
        } else {
            // If no tags are left, automatically give focus back to whatever is to the right
            this.moveRight();
        }
    },

    /**
     * Adds a tag to the page
     * @param {Object} tag
     */
    addTag: function(tag) {
        if (tag && tag.name) {
            // If tag already exists do nothing
            if (!_.find(this.selectedTags, function(tagToCheck) {
                return tagToCheck.name === tag.name;
            })) {
                this.selectedTags.push(tag);
                this.render();
                this.layout.trigger('quicksearch:fire:search', true);
            }
        }
    },

    /**
     * Remove a specific tag
     * @param {jQuery || boolean} $tagParam - jQuery representation of tag pill. Optional
     * (if it doesn't exist, default to activeIndex)
     */
    removeTag: function($tagParam) {
        // Only continue if we have either a $tag param or an activeIndex
        if (!$tagParam && _.isNull(this.activeIndex)) {
            return;
        }

        var $tag = $tagParam || this.$('.tag-wrapper:eq(' + this.activeIndex + ')');

        // Remove the selected tag from the internal tag list
        var index = _.indexOf(_.pluck(this.selectedTags, 'name'), $tag.attr('tag-name'));
        this.selectedTags.splice(index, 1);

        // Remove the selected tag from the DOM
        $tag.remove();

        this.layout.trigger('quicksearch:fire:search', true);
    },

    /**
     * Click handler for tag removal element
     * @param {Event} e
     */
    removeTagClicked: function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $tag = this.$(e.target).parent();
        this.removeTag($tag);
        this.$('.tag-wrapper').removeClass('highlight');

        // Go back to the quicksearch bar to prevent any shenanigans (only on click)
        this.layout.trigger('navigate:to:component', 'quicksearch-bar');
    },

    /**
     * Removes all tags from search bar (When searchbar's "X" is clicked)
     */
    removeAllTags: function() {
        // Remove all tags from the tags array (and the layout tag array since other views share that)
        this.selectedTags.splice(0, this.selectedTags.length);
        this.activeIndex = null;
        this.$('.tag-wrapper').remove();
    },


    /**
     * Click handler for tag highlighting
     * @param {Event} e
     */
    highlightTagClicked: function(e) {
        this.requestFocus();

        // Set highlight class
        this.$('.tag-wrapper').removeClass('highlight');
        var $tag = this.$(e.target).parent();
        $tag.addClass('highlight');

        // Set activeIndex
        this.activeIndex = _.indexOf(_.pluck(this.selectedTags, 'name'), $tag.attr('tag-name'));
    },

    /**
     * Highlights a specific tag element.
     */
    _highlightActive: function() {
        this.$('.tag-wrapper').removeClass('highlight');
        this.$('.tag-wrapper:eq(' + this.activeIndex + ')').addClass('highlight');
    },

    /**
     * Request focus from the layout. This is used primarily for mouse clicks.
     */
    requestFocus: function() {
        this.layout.trigger('navigate:to:component', this.name);
    },

    /**
     * Move to the next the active element.
     */
    moveRight: function() {
        // check to make sure we will be in bounds.
        if (this.activeIndex < this.selectedTags.length - 1) {
            // We're in bounds, just go to the next element in this view.
            this.activeIndex++;
            this._highlightActive();
        } else {
            // We're trying to move beyond the elements in this view. We need to try to move to the next view
            this._handleBoundary(true);

        }
    },

    /**
     * Move to the previous the active element.
     */
    moveLeft: function() {
        // check to make sure we will be in bounds.
        if (this.activeIndex > 0) {
            // We're in bounds, just go to the previous element in this view
            this.activeIndex--;
            this._highlightActive();
        } else {
            // We're trying to move beyond the elements in this view. We need to try to move to the previous view
            this._handleBoundary(false);
        }
    },

    /**
     * Handle when the user uses their keyboard to try to navigate outside of the view. This handles both the top and
     * bottom boundaries.
     * @param {boolean} next - If true, we are checking the next element. If false, we are checking the previous.
     * @private
     */
    _handleBoundary: function(next) {
        var event = 'navigate:next:component';
        if (!next) {
            event = 'navigate:previous:component';
        }
        if (this.layout.triggerBefore(event)) {
            this.clearActive();
            this.layout.trigger(event);
        }
    },

    /**
     * Clear the active element and dispose key events
     */
    clearActive: function() {
        this.activeIndex = null;
        this.$('.tag-wrapper').removeClass('highlight');
        this.disposeKeydownEvent();
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        this.disposeKeydownEvent();
        this._super('unbind');
    }
})
