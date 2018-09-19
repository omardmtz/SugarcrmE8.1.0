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
        'click [data-action=toggle]': 'toggleIsRead',
        'mouseover [data-action=toggle]': 'toggleMouse',
        'mouseout [data-action=toggle]': 'toggleMouse'
    },

    /**
     * @inheritdoc
     *
     * The read field is always a readonly field.
     *
     * If `mark_as_read` option is enabled on metadata it means we should
     * automatically mark the notification as read.
     *
     */
    initialize: function(options) {
        options.def.readonly = true;

        this._super('initialize', [options]);

        if (options.def && options.def.mark_as_read) {
            this.markAs(true);
        }
    },

    /**
     * Event handler for mouse events.
     *
     * @param {Event} event Mouse over / mouse out.
     */
    toggleMouse: function(event) {
        var $target= this.$(event.currentTarget),
            isRead = this.model.get('is_read');

        if (!isRead) {
            return;
        }

        var label = event.type === 'mouseover' ? 'LBL_UNREAD' : 'LBL_READ';
        $target.html(app.lang.get(label, this.module));
        $target.toggleClass('label-inverse', event.type === 'mouseover');
    },

    /**
     * Toggle notification `is_read` flag.
     */
    toggleIsRead: function() {
        this.markAs(!this.model.get('is_read'));
    },

    /**
     * Mark notification as read/unread.
     *
     * @param {Boolean} read `True` marks notification as read, `false` as
     *   unread.
     */
    markAs: function(read) {
        if (read === this.model.get('is_read')) {
            return;
        }

        this.model.save({is_read: !!read}, {
            success: _.bind(function() {
                if (!this.disposed) {
                    this.render();
                }
            }, this)
        });
    }
})
