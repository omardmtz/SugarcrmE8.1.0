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
(function (app) {
    app.events.on('app:init', function () {
        app.plugins.register('ListColumnEllipsis', ['view'], {

            events: {
                'click [data-field-toggle]': 'toggleColumn'
            },
            /**
             * Toggle the 'visible' state of an available field
             * @param {Object} event jquery event object
             */
            toggleColumn: function (event) {
                var column = $(event.currentTarget).data('fieldToggle');

                // SP-845 (must have atleast one column selected)
                // User should not be able to deselect if only one column available
                if (this.isLastColumnVisible(column)) {
                    event.stopPropagation();
                    return;
                }
                this._toggleColumn(column);
                this.render();
                this._reopenFieldsDropdown(event);
            },
            /**
             * Toggle selected field.
             *
             * @param {String} column The column name.
             * @protected
             */
            _toggleColumn: function(column) {
                var changedColumn = {};
                // Search _fields.options for match on column and toggle it's selected property
                var f = this._fields._byId[column];
                if (f) {
                    f.selected = !f.selected;
                    changedColumn = f;
                }
                this._fields.visible = _.where(this._fields.all, { selected: true });

                // trigger an event to let the view that this is mixed-into that a column has been toggled
                this.trigger('list:toggle:column', column, changedColumn.selected, changedColumn);
            },

            /**
             * Determines if only one column is visible, and if the `column` being toggled is the last visible one.
             * @param {String} column The column's `name`
             * @return {Boolean} True if one column left and trying to toggle same column; false otherwise
             * @protected
             */
            isLastColumnVisible: function(column) {
                if (this._fields.visible.length === 1) {
                    // See if we're trying to toggle the last checked column
                    var f = this._fields._byId[column];
                    return f && f.selected;
                }
                return false;
            },
            /**
             * Reopens fields dropdown and stopPropagation to keep fields dropdown opened
             * @param {Object} event The original event
             * @protected
             */
            _reopenFieldsDropdown: function(event) {
                this.$('[data-action="fields-toggle"]').dropdown('toggle');
                event.stopPropagation();
            },
            onAttach: function (component, plugin) {
                this.before('render', function () {
                    var lastActionColumn = _.last(this.rightColumns);
                    if (lastActionColumn) {
                        lastActionColumn.isColumnDropdown = true;
                    }
                }, this);
            }
        });
    });
})(SUGAR.App);
