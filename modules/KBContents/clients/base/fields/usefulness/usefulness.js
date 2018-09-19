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
    events: {
        'click [data-action=useful]': 'usefulClicked',
        'click [data-action=notuseful]': 'notusefulClicked'
    },

    /**
     * @inheritdoc
     *
     * This field doesn't support `showNoData`.
     */
    showNoData: false,

    plugins: [],

    KEY_USEFUL: '1',
    KEY_NOT_USEFUL: '-1',

    voted: false,
    votedUseful: false,
    votedNotUseful: false,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        if (!this.model.has('useful')) {
            this.model.set('useful', 0);
        }
        if (!this.model.has('notuseful')) {
            this.model.set('notuseful', 0);
        }
        this.checkVotes();
    },

    /**
     * Check votes state,
     * Set values for votedUseful, if user voted `useful` and
     * votedNotUseful if user voted `not useful`.
     */
    checkVotes: function() {
        var vote = this.model.get('usefulness_user_vote');
        this.votedUseful = (vote == this.KEY_USEFUL);
        this.votedNotUseful = (vote == this.KEY_NOT_USEFUL);
    },

    /**
     * The vote for useful or not useful.
     *
     * @param {boolean} isUseful Flag of useful or not useful.
     */
    vote: function(isUseful) {
        if (
            (isUseful && this.model.get('usefulness_user_vote') == this.KEY_USEFUL)
            || (!isUseful && this.model.get('usefulness_user_vote') == this.KEY_NOT_USEFUL)
        ) {
            return;
        }
        var action = isUseful ? 'useful' : 'notuseful';
        var url = app.api.buildURL(this.model.module, action, {
            id: this.model.id
        });
        var callbacks = {
            success: _.bind(function(data) {
                this.model.set({
                    'usefulness_user_vote': data.usefulness_user_vote,
                    'useful': data.useful,
                    'notuseful': data.notuseful,
                    'date_modified': data.date_modified
                });
                if (!this.disposed) {
                    this.render();
                }
            }, this),
            error: function() {}
        };

        app.api.call('update', url, null, callbacks);
    },

    /**
     * Handler to vote useful when icon clicked.
     */
    usefulClicked: function() {
        this.vote(true);
    },

    /**
     * Handler to vote not useful when icon clicked.
     */
    notusefulClicked: function() {
        this.vote(false);
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this.checkVotes();
        this._super('_render');
        return this;
    }
})
