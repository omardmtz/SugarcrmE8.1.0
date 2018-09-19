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

(function(app) {
    app.events.on('app:init', function() {
        app.plugins.register('TbACLs', ['field'], {

            events: {
                'click .btn[data-rname=select-team]': 'selectTeam'
            },

            isTBAEnabled: false,

            /**
             * Bind handlers for field duplication.
             *
             * @param {View.Component} component Component to attach plugin.
             * @param {Object} plugin Object of plugin to attach.
             * @return {void}
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    this.isTBAEnabled = this.isEnabledForModule(this.module);
                });
            },

            /**
             * Select the team.
             * @param {Event} evt The 'click' event.
             */
            selectTeam: function(evt) {
                var index = $(evt.currentTarget).data('index');
                if (!this.value[index] || !this.value[index].id) {
                    return;
                }
                this.toggleSelectedTeam(index);
            },

            /**
             * Toggle selected status on the team.
             * @param {number} index Row index.
             */
            toggleSelectedTeam: function(index) {
                if (!this.value[index] || !this.value[index].id) {
                    return;
                }
                this.value[index].selected = this.value[index].selected ? false : true;
                this._updateAndTriggerChange(this.value);
                this._toggleSelectedButton(index, this.value[index].selected);
            },

            /**
             * Toggle selected button.
             * @param {number} index
             * @param {boolean} state
             * @private
             */
            _toggleSelectedButton: function(index, state) {
                var state = state || false,
                    btnName = 'select-team',
                    btnSelector = '.btn[data-rname='+ btnName +'][data-index=' + index + ']',
                    btnIconSelector = btnSelector + ' i.fa';

                if (state) {
                    this.$(btnSelector).addClass('active');
                    this.$(btnIconSelector).removeClass('fa-lock').addClass('fa-unlock-alt');
                } else {
                    this.$(btnSelector).removeClass('active');
                    this.$(btnIconSelector).removeClass('fa-unlock-alt').addClass('fa-lock');
                }
            },

            /**
             * Is Team Based ACL enabled for module.
             * @param {String} module
             * @return {boolean}
             */
            isEnabledForModule: function(module) {
                if (!_.isUndefined(app.config.teamBasedAcl.enabled) &&
                        !_.isUndefined(app.config.teamBasedAcl.enabledModules)) {
                    return app.config.teamBasedAcl.enabled &&
                        (_.indexOf(app.config.teamBasedAcl.enabledModules, module) !== -1);
                } else {
                    return false;
                }
            },

            /**
             * {@inheritDoc}
             *
             * Clean up associated event handlers.
             */
            onDetach: function(component, plugin) {

            }
        });
    });
})(SUGAR.App);
