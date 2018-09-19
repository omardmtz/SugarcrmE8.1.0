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
    app.events.on("app:init", function () {
        var hashKey = null;
        var pinged = false;
        app.plugins.register('Connector', ['view'], {

            /**
             * Check if a specific connector is valid. If it is, call the success call
             * @param {string} name
             * @param {function} successCall
             * @param {function} errorCall
             * @param {array} connectorCriteria
             */
            checkConnector: function (name, successCall, errorCall, connectorCriteria) {
                var connectors,
                    connector = null;
                var self = this;
                var successCallWrapper = _.bind(function () {
                    this.checkConnector(name, successCall, errorCall, connectorCriteria);
                }, this);

                if (hashKey === null) {
                    pinged = true;
                    this.getConnectors(name, successCallWrapper);
                }
                else {
                    connectors = app.cache.get(hashKey);
                    if (connectors && connectors[name]) {
                        connector = connectors[name];
                    }
                    // if connector exists and all connectorCriteria is true, call the success call
                    if ((connector) &&
                        (this.checkCriteria(connector, connectorCriteria))) {
                        connector.connectorHash = hashKey;
                        successCall(connector);
                    }
                    else {
                        if (pinged === false) {
                            pinged = true;
                            _.defer(function() {
                                self.getConnectors(name, successCallWrapper);
                            });
                        }
                        else {
                            pinged = false;
                            errorCall(connector);
                        }
                    }
                }
            },

            /**
             * Check to see if specified criteria is met
             * @param {object} connector
             * @param {array} criteria
             *
             * @return {boolean} true if criteria is met
             */
            checkCriteria: function (connector, criteria) {
                var check = true;

                _.each(criteria, function (criterion) {
                    if (criterion === 'test_passed') {
                        if (connector.testing_enabled) {
                            check = check && connector.test_passed;
                        }
                    }
                    else {
                        check = check && connector[criterion];
                    }

                    if (!check) {
                        return check;
                    }
                });

                return check;
            },
            /**
             * gets connector field mappings
             * @param {String} connector
             * @param {Module} module
             * @returns {{}}
             */
            getConnectorModuleFieldMapping: function (connector, module) {
                var connectors = app.cache.get(hashKey);
                var mappings = {};
                if (connectors[connector] &&
                    connectors[connector].field_mapping &&
                    connectors[connector].field_mapping.beans &&
                    connectors[connector].field_mapping.beans[module]
                    ) {
                    mappings = connectors[connector].field_mapping.beans[module];
                }
                return mappings;
            },

            /**
             * API call to connectors endpoint to populate cache
             * @param {string} name
             * @param {function} successCall
             */
            getConnectors: function (name, successCall) {
                var connectorURL = app.api.buildURL('connectors');

                app.api.call('GET', connectorURL, {}, {
                    success: function (data) {
                        hashKey = data['_hash'];
                        app.cache.set(hashKey, data['connectors']);
                        successCall();
                    }
                });
            }
        });
    });
})(SUGAR.App);