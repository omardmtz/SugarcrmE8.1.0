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
        app.plugins.register('Pii', ['view'], {
            /**
             * Add "View PII" button to the module metadata if there are any PII fields
             * @param {Object[]} fields List of fielddefs for the attached view.
             * @private
             */
            _insertViewPiiButton: function(fields) {
                var piiFields = _.where(fields, {pii: true});

                if (piiFields.length > 0) {
                    var actionDropDown = _.findWhere(this.meta.buttons, {type: 'actiondropdown'});

                    if (actionDropDown) {
                        var buttons = actionDropDown.buttons;
                        var auditButtonMeta = _.findWhere(buttons, {name: 'audit_button'});
                        var auditButtonIndex = _.indexOf(buttons, auditButtonMeta);
                        var viewPiiButtonMeta = {
                            type: 'rowaction',
                            event: 'button:view_pii_button:click',
                            name: 'view_pii_button',
                            label: 'LBL_DATAPRIVACY_VIEW_PII',
                            acl_action: 'view'
                        };

                        if (auditButtonIndex > -1) {
                            buttons.splice(auditButtonIndex + 1, 0, viewPiiButtonMeta);
                        } else {
                            buttons.push(viewPiiButtonMeta);
                        }
                    }
                }
            },

            /**
             * @inheritdoc
             *
             * Bind the View PII button handler.
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    var model = this.context.get('model') || this.model;
                    this._insertViewPiiButton(model.fields);

                    this.context.on('button:view_pii_button:click', this.viewPiiClicked, this);
                });
            },

            /**
             * Handles the click event and opens the pii view in the drawer.
             */
            viewPiiClicked: function() {
                var context = this.context.getChildContext({
                    name: 'Pii',
                    model: app.data.createBean('Pii')
                });

                context.set('pModule', this.module);
                context.set('pId', this.model.id);

                app.drawer.open({
                    layout: 'pii',
                    context: context
                });
            },

            /**
             * @inheritdoc
             *
             * Clean up associated event handlers.
             */
            onDetach: function(component, plugin) {
                this.context.off('button:view_pii_button:click', this.viewPiiClicked, this);
            }
        });
    });
})(SUGAR.App);
