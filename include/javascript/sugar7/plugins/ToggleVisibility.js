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

        /**
         * Widget for toggling visibility panel.
         *
         * Should include at least two states: "user" and "group".
         * Default values can be defined in the sections:
         * 'config' => array(
         *     'visibility' => 'user',
         * ),
         * 'preview' => array(
         *     'visibility' => 'group',
         * ),
         *
         * To activate it the view metadata should contain last_state
         * or be stored in settings.
         */
        app.plugins.register('ToggleVisibility', 'view', {

            /**
             * Custom Events for making the ToggleVisibility work on a view.
             */
            events: {
                'click [data-action=visibility-switcher]': 'visibilitySwitcher'
            },

            /**
             * Last state and meta key for visibility.
             */
            visibilityKey: 'visibility',

            defaultVisibility: 'user',

            onAttach: function() {
                this.on('init', function() {
                    if (!this.meta.last_state) {
                        this.meta.last_state = {
                            id: this.dashModel.get('id') + ':' + this.name,
                            defaults: {}
                        };
                    }

                    this._initVisibility();
                });
            },

            /**
             * Initializes visibility.
             * Can be defined in settings, last state is populated
             * automatically with it or with default value.
             */
            _initVisibility: function() {
                var config = app.metadata.getView(this.module, this.name),
                    visibilityField = _.chain(config.panels)
                        .map(function(obj) {
                            return obj.fields;
                        })
                        .flatten()
                        .findWhere({name: this.visibilityKey})
                        .value();

                if (!visibilityField) {
                    return;
                }

                if (_.contains(this.plugins, 'Dashlet')) {
                    this.settings.on('change:' + this.visibilityKey, function(model, value) {
                        this._setVisibilityLastState(value);
                    }, this);
                    this.settings.set(this.visibilityKey, this.getVisibility());
                } else {
                    if (!this.hasVisibility()) {
                        this.setVisibility(this.getVisibility());
                    }
                }
            },

            /**
             * Saves passed visibility value into local storage.
             *
             * @param {String} visibility Visibility value.
             */
            _setVisibilityLastState: function(visibility) {
                if (!this.meta.last_state.defaults[this.visibilityKey]) {
                    this.meta.last_state.defaults[this.visibilityKey] = visibility;
                }
                // Register default value.
                app.user.lastState.register(this);
                // Saved or meta default key.
                var specificVisibilityKey = app.user.lastState.key(this.visibilityKey, this);
                app.user.lastState.set(specificVisibilityKey, visibility);
            },

            /**
             * Event handler for visibility switcher.
             * Custom functionality can be added in view into the
             * #visibilitySwitcher() function.
             *
             * @param {Event} event Click event.
             */
            visibilitySwitcher: function(event) {
                var proto = Object.getPrototypeOf(this),
                    visibility = this.$(event.currentTarget).val();
                if (visibility === this.getVisibility()) {
                    return;
                }
                this.setVisibility(visibility);
                if (_.isFunction(proto.visibilitySwitcher)) {
                    proto.visibilitySwitcher.call(this, event);
                }
                this.loadData();
            },

            /**
             * Get current visibility state.
             * Returns default value if can't find in last state or settings.
             *
             * @return {String} Visibility.
             */
            getVisibility: function() {
                var visibility = app.user.lastState.get(
                    app.user.lastState.key(this.visibilityKey, this),
                    this
                );
                if (!visibility && this.settings) {
                    visibility = this.settings.get(this.visibilityKey);
                }
                return visibility || this.defaultVisibility;
            },

            /**
             * Set current visibility state.
             * Saves value into last state and settings if possible.
             *
             * @param {String} visibility Visibility value.
             */
            setVisibility: function(visibility) {
                if (_.contains(this.plugins, 'Dashlet')) {
                    // Invokes _setVisibilityLastState(), see "change" event.
                    this.settings.set(this.visibilityKey, visibility);
                } else {
                    this._setVisibilityLastState(visibility);
                }
            },

            /**
             * Checks if visibility is stored in settings or last state.
             *
             * @return {Boolean}
             */
            hasVisibility: function() {
                var visibility = app.user.lastState.get(
                    app.user.lastState.key(this.visibilityKey, this),
                    this
                );
                if (!visibility && this.settings) {
                    visibility = this.settings.has(this.visibilityKey);
                }
                return !!visibility;
            }
        });
    });
})(SUGAR.App);
