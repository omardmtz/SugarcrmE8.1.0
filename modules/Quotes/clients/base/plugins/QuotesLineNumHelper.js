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
         * This plugin adds Line Number functionality to views
         */
        app.plugins.register('QuotesLineNumHelper', ['view'], {
            /**
             * The line_num field definition
             */
            lineNumFieldDef: undefined,

            /**
             * If the line_num field has been added to the columns or not
             */
            hasLineNumField: undefined,

            /**
             * Hash map by group ID of current line count
             */
            lineNumGroupIdMap: undefined,

            /**
             * If the user has toggled for us to show line numbers or not
             */
            showLineNums: undefined,

            /**
             * @inheritdoc
             */
            onAttach: function(component, plugin) {
                this.once('init', function() {
                    var ctx;

                    if (this.model.module === 'Quotes') {
                        ctx = this.context;
                    } else {
                        ctx = this.context.parent;
                        this.layout.on('quotes:line_nums:reset', this.resetGroupLineNumbers, this);
                    }

                    ctx.on('quotes:show_line_nums:changed', this.onShowLineNumsChanged, this);

                    this.lineNumFieldDef = _.find(this._fields, function(field) {
                        return field.name === 'line_num';
                    });

                    this._fields = _.reject(this._fields, function(field) {
                        return field.name === 'line_num';
                    });

                    this.hasLineNumField = false;
                    this.showLineNums = false;
                    this.lineNumGroupIdMap = {};

                    if (ctx.has('model') && ctx.get('model').has('show_line_nums')) {
                        this.onShowLineNumsChanged(ctx.get('model').get('show_line_nums'));
                    }
                }, this);
            },

            /**
             * @inheritdoc
             *
             * Clean up associated event handlers.
             */
            onDetach: function(component, plugin) {
                var ctx;

                if (this.model.module === 'Quotes') {
                    ctx = this.context;
                } else {
                    ctx = this.context.parent;
                    this.layout.off('quotes:line_nums:reset', this.resetGroupLineNumbers, this);
                }

                if (ctx) {
                    ctx.off('quotes:show_line_nums:changed', this.onShowLineNumsChanged, this);
                }
                this.lineNumGroupIdMap = undefined;
            },

            /**
             * Handles when the show_line_nums var changes on the model
             *
             * @param {boolean} showLineNums If we should show line numbers or not
             */
            onShowLineNumsChanged: function(showLineNums) {
                var changed = false;
                var isBundle = this.model.module === 'ProductBundles' && this.collection.length;
                this.showLineNums = showLineNums;

                if (this.showLineNums && !this.hasLineNumField) {
                    this._addLineNumFieldDef();
                    if (isBundle) {
                        this._addLineNumToModel(this.model.cid, this.collection);
                    }
                    changed = true;
                } else if (!this.showLineNums && this.hasLineNumField) {
                    this._removeLineNumFieldDef();
                    if (isBundle) {
                        this._removeLineNumFromModel(this.model.cid, this.collection);
                    }
                    changed = true;
                }

                if (changed) {
                    this.render();
                }
            },

            /**
             * Returns the line count Object for the group ID
             *
             * @param {string} groupId The ID of the group to get the line_num Object
             * @return {Object}
             */
            getGroupLineNumCount: function(groupId) {
                this._checkAddGroupToMap(groupId);

                return this.lineNumGroupIdMap[groupId];
            },

            /**
             * Allows other views to reset and update all models' line_num count in a group's collection
             *
             * @param {string} groupId The ID of the group to update the line_num Object
             * @param {Data.MixedBeanCollection} collection The ProductBundle group collection
             */
            resetGroupLineNumbers: function(groupId, collection) {
                var groupLineNumObj;

                if (!this.showLineNums) {
                    // if we're not showing line numbers, don't do anything
                    return;
                }

                groupId = groupId || this.model.cid;
                collection = collection || this.collection;

                this._checkAddGroupToMap(groupId);

                // get the group line_num object
                groupLineNumObj = this.lineNumGroupIdMap[groupId];
                // reset the line number count so we can re-number all the models
                groupLineNumObj.ct = 1;

                _.each(collection.models, _.bind(function(groupLineNumObj, model) {
                    if (model.module === 'Products') {
                        model.set('line_num', groupLineNumObj.ct++);
                    }
                }, this, groupLineNumObj), this);
            },

            /**
             * Checks to see if the groupId already exists in lineNumGroupIdMap and if it does not
             * it adds the groupId as a key and an Object as the value
             *
             * @param {string} groupId The ID of the group to check
             * @private
             */
            _checkAddGroupToMap: function(groupId) {
                if (!this.lineNumGroupIdMap[groupId]) {
                    this.lineNumGroupIdMap[groupId] = {
                        ct: 1
                    };
                }
            },

            /**
             * Checks to see if the count for the given groupId is less than 1, if so, it deletes the group key
             * from the object
             *
             * @param {string} groupId The ID of the group to check
             * @private
             */
            _checkRemoveGroupFromMap: function(groupId) {
                if (this.lineNumGroupIdMap[groupId].ct < 1) {
                    delete this.lineNumGroupIdMap[groupId];
                }
            },

            /**
             * Adds the line_num field def to the view
             *
             * @private
             */
            _addLineNumFieldDef: function() {
                this._fields.unshift(this.lineNumFieldDef);
                this.hasLineNumField = true;
            },

            /**
             * Removes the line_num field def from the view
             *
             * @private
             */
            _removeLineNumFieldDef: function() {
                this._fields = _.reject(this._fields, function(field) {
                    return field.name === 'line_num';
                });
                this.hasLineNumField = false;
            },
            /**
             * Adds the line_num var to the model
             *
             * @private
             */
            _addLineNumToModel: function(groupId, collection) {
                this._checkAddGroupToMap(groupId);

                _.each(collection.models, _.bind(function(groupId, model) {
                    if (model.module === 'Products') {
                        model.set('line_num', this.lineNumGroupIdMap[groupId].ct++);
                    }
                }, this, groupId), this);
            },

            /**
             * Removes the line_num var from the model
             *
             * @private
             */
            _removeLineNumFromModel: function(groupId, collection) {
                _.each(collection.models, _.bind(function(groupId, model) {
                    if (model.module === 'Products') {
                        model.unset('line_num');
                        this.lineNumGroupIdMap[groupId].ct--;
                    }
                }, this, groupId), this);

                this._checkRemoveGroupFromMap(groupId);
            }
        });
    });
})(SUGAR.App);
