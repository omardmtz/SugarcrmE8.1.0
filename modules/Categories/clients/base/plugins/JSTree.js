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
        app.plugins.register('JSTree', ['view', 'field'], {

            /**
             * The JS Tree Object.
             * @property {Object} jsTree
             */
            jsTree: null,

            /**
             * JSTree settings.
             * @property {Object} jsTreeSettings
             */
            jsTreeSettings: null,

            /**
             * JSTree callbacks.
             * @property {Object} jsTreeCallbacks
             */
            jsTreeCallbacks: null,

            /**
             * JSTree options
             * @property {Object}
             */
            jsTreeOptions: null,

            /**
             * JQuery container with empty label
             * @property {Object} $noData
             */
            $noData: null,

            /**
             * JQuery container with empty label
             * @property {Object} $treeContainer
             */
            $treeContainer: null,

            /**
             * @inheritdoc
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    app.events.on(
                        'app:nestedset:sync:complete',
                        this.onNestedSetSyncComplete,
                        this
                    );
                    app.error.errorName2Keys['empty_node_name'] = 'ERR_EMPTY_NODE_NAME';
                });
            },

            /**
             * @param {View.Component} component The component this plugin is attached to.
             */
            onDetach: function(component) {
                // Disabling all existing tooltips for input
                if (!_.isEmpty(this.$treeContainer)) {
                    _.each(this.$treeContainer.find('input'), _.bind(function(input) {
                        this._disableTooltip($(input));
                    }, this));
                }

                if (!_.isEmpty(this.jsTree)) {
                    this.jsTree.off();
                }
                app.events.off(
                    'app:nestedset:sync:complete',
                    this.onNestedSetSyncComplete,
                    this
                );

            },

            /**
             * Handler on sync NestedSetCollection.
             *
             * Re-render all nested set views that display same category root.
             * As additional action - refresh context menu.
             *
             * @param {Data.NestedSetCollection} collection Synced collection.
             */
            onNestedSetSyncComplete: function(collection) {
                if (_.isFunction(Object.getPrototypeOf(this).onNestedSetSyncComplete)) {
                    Object.getPrototypeOf(this).onNestedSetSyncComplete.call(this, collection);
                    return;
                }
                if (this.disposed || _.isUndefined(this.collection) ||
                    this.collection.root !== collection.root
                ) {
                    return;
                }

                if (this.collection !== collection) {
                    this.render();
                }
            },

            /**
             * Render JSTree.
             * @param {Object} $container
             * @param {Object} settings
             * @param {String} settings.settings.module_root Module parameter to build a collection (required).
             * @param {String} settings.settings.category_root Root parameter to build a collection (required).
             * @param {Object} callbacks
             * @param {Object} callbacks.onToggle Callback on expand/collapse a tree branch.
             * @param {Object} callbacks.onLoad Callback on tree loaded.
             * @param {Object} callbacks.onLeaf Callback on leaf click.
             * @param {Object} callbacks.onShowContextmenu Callback on show a context menu.
             * @param {Object} callbacks.onAdd Callback on add a new node.
             * @param {Object} callbacks.onLoadState Callback on load state.
             * @param {Object} callbacks.onSelect Callback on select a node.
             * @private
             */
            _renderTree: function($container, settings, callbacks) {

                this.jsTreeSettings = settings.settings || {};
                this.jsTreeOptions = settings.options || {};
                this.jsTreeCallbacks = callbacks || {};

                this.$noData = $('<div />', {'data-type': 'jstree-no-data', class: 'block-footer'})
                    .html(app.lang.get('LBL_NO_DATA_AVAILABLE', this.module));
                this.$treeContainer = $('<div />', '');

                this.dataProvider = this.jsTreeSettings.module_root || null;
                this.root = this.jsTreeSettings.category_root || null;

                if (!this.dataProvider || !this.root) {
                    return;
                }
                $container.empty();
                $container.append(this.$noData).append(this.$treeContainer);
                this._toggleVisibility(true);
                this.collection.module = this.dataProvider;
                this.collection.root = this.root;
                this.collection.tree({
                    success: _.bind(function(data) {
                        this.createTree(data.jsonTree, this.$treeContainer, this.loadPluginsList());
                    }, this),
                    error: _.bind(function(collection, error) {
                        this._alertError(error, _.bind(function() {
                            this.createTree([], this.$treeContainer, this.loadPluginsList());
                        }, this));
                    }, this)
                });
            },

            /**
             * Hide tree if there is no data, show otherwise.
             * @param {Boolean} hide Hide tree if true, show otherwise.
             * @private
             */
            _toggleVisibility: function(hide) {
                if (hide === true) {
                    this.$treeContainer.hide();
                    this.$noData.show();
                } else {
                    this.$treeContainer.show();
                    this.$noData.hide();
                }
            },

            /**
             * Load JSTree plugins list.
             */
            loadPluginsList: function() {
                return _.union(
                    ['json_data', 'ui', 'crrm', 'types', 'themes', 'search'], // default plugins
                    !_.isUndefined(this.jsTreeSettings.plugins) ? this.jsTreeSettings.plugins : []
                );
            },

            /**
             * Create JSTree.
             * @param {Object} data
             * @param {Object} $container
             * @param {Array} plugins
             * @example List of available plugins, based on common jstree list.
             * ```
             * ['json_data', 'dnd', 'ui', 'crrm', 'types', 'themes', 'contextmenu', 'search']
             * ```
             */
            createTree: function(data, $container, plugins) {
                this._toggleVisibility(data.length === 0);
                // make sure we're using an array
                // if the data coming from the endpoint is an array with one element
                // it gets converted to a JS object in the process of getting here
                if (!_.isArray(data)) {
                    data = [data];
                }
                var treeData = data,
                    fn = function(el) {
                        if (!_.isEmpty(el.children)) {
                            _.each(el.children.records, fn);
                            el.children = el.children.records;
                        }
                        el.data = el.name;
                        el.metadata = {id: el.id};
                        el.attr = {'data-id': el.id, 'data-level': el.lvl, 'id': el.id};
                    },
                    jsTreeOptions = {
                        settings: this.jsTreeSettings,
                        plugins: _.isEmpty(plugins) ? this.loadPluginsList() : plugins,
                        json_data: {
                            'data': treeData
                        },
                        contextmenu: {
                            items: this._loadContextMenu(this.jsTreeSettings),
                            show_at_node: false
                        },
                        search: {
                            case_insensitive: true
                        },
                        core: {
                            strings: {
                                new_node: app.lang.get('LBL_DEFAULT_TITLE', 'Categories')
                            }
                        }
                    };
                jsTreeOptions = _.extend({}, jsTreeOptions, this.jsTreeOptions);
                treeData.ctx = this.context;
                _.each(treeData, fn);

                this.jsTree = $container.jstree(jsTreeOptions)
                .on('loaded.jstree', _.bind(function() {
                    this._loadedHandler($container);
                }, this))
                .on('select_node.jstree', _.bind(this._selectNodeHandler, this))
                .on('create.jstree', _.bind(this._createHandler, this))
                .on('show_input.jstree', _.bind(this._showInputHandler, this))
                .on('move_node.jstree', _.bind(this._moveHandler, this))
                .on('remove.jstree', _.bind(this._removeHandler, this))
                .on('rename_node.jstree', _.bind(this._renameNodeHandler, this))
                .on('rename.jstree', _.bind(this._renameHandler, this))
                .on('load_state.jstree', _.bind(this._loadedStateHandler, this))
                .on('search.jstree', _.bind(this._searchHandler, this));
            },

            /**
             * Drag-and-Drop handler when node move is finished.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _moveHandler: function(event, data) {
                /**
                 * Catch Drag-And-Drop move_node
                 */
                if ($.vakata.dnd.is_drag && $.vakata.dnd.user_data.jstree) {
                    if (!_.isUndefined(data.rslt.o) && !_.isUndefined(data.rslt.r)) {
                        this.moveNode(data.rslt.o.data('id'), data.rslt.r.data('id'), data.rslt.p, function(obj, response) {
                            var levelDelta = parseInt(obj.lvl) - parseInt($(data.rslt.o).data('level'));
                            //set new level for dragged node
                            $(data.rslt.o).attr('data-level', obj.lvl);
                            $(data.rslt.o).data('level', obj.lvl);
                            //recalculate the level for all nodes within selected
                            _.each($(data.rslt.o).find('li'), function(item){
                                var currentLevel = parseInt($(item).attr('data-level'));
                                $(item).attr('data-level', currentLevel + levelDelta);
                                $(item).data('level', currentLevel + levelDelta);
                            });
                        });
                    }
                }
            },

            /**
             * Hadle load state of tree.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _loadedStateHandler: function (event, data) {
                if (this.jsTreeCallbacks.onLoadState) {
                    _.each(data.rslt, function(val, ind) {
                        _.each(val, function(v, i) {
                            var id = v,
                                selectedNode,
                                node = this.jsTree.find('[data-id=' + id +']');
                            if (node.length === 0) {
                                return;
                            }
                            selectedNode = {
                                id: id,
                                name: node.find('a:first').text().trim(),
                                type: node.data('type') || 'folder',
                                toString: function() {
                                    // jstree jquery plugin uses toString to get ID. We need to return right one.
                                    return id;
                                }
                            };
                            val[i] = selectedNode;
                        }, this);
                        data.rslt[ind] = val;
                    }, this);
                    this.jsTreeCallbacks.onLoadState(data.rslt);
                }
            },

            /**
             * Remove node handler.
             * @param {Event} event
             * @param {Object} data
             * @return {boolean}
             * @private
             */
            _removeHandler: function(event, data) {
                if (!data) {
                    return false;
                }
                if (this.jsTreeCallbacks.onRemove &&
                    !this.jsTreeCallbacks.onRemove.apply(this, [data.rslt.obj])) {
                    return false;
                }
                return this._jstreeRemoveNode(data.rslt.obj);
            },

            /**
             * Rename node handler.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _renameNodeHandler: function(event, data) {
                if (!_.isUndefined(data.rslt.obj.data('id'))) {
                    var bean = this.collection.getChild(data.rslt.obj.data('id'));
                    if (!_.isUndefined(bean)) {
                        if (bean.get('name') !== data.rslt.name) {
                            bean.set('name', data.rslt.name);
                            bean.save();
                        }
                    }
                }
            },

            /**
             * Rename handler.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _renameHandler: function(event, data) {
                this._toggleAddNodeButton(data.rslt.obj, false);
            },

            /**
             * Search node handler.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _searchHandler: function(event, data) {
                /* ToDo: handler for search node - wbi */
            },

            /**
             * Load context menu.
             * @param {Object} settings
             * @param {Boolean} settings.showMenu Show menu ot not.
             * @return {Object}
             * @private
             */
            _loadContextMenu: function(settings) {
                var self = this;
                if (settings.showMenu === true) {
                    return {
                        edit: {
                            separator_before: false,
                            separator_after: true,
                            _disabled: false,
                            label: app.lang.get('LBL_CONTEXTMENU_EDIT', self.module),
                            action: function(obj) {
                                this.rename(obj);
                            }
                        },
                        moveup: {
                            separator_before: false,
                            separator_after: true,
                            _disabled: false,
                            label: app.lang.get('LBL_CONTEXTMENU_MOVEUP', self.module),
                            action: function(obj) {
                                var currentNode = this._get_node(obj),
                                    prevNode = this._get_prev(obj, true);
                                if (currentNode && prevNode) {
                                    self.moveNode(
                                        currentNode.data('id'),
                                        prevNode.data('id'),
                                        'before',
                                        function() {
                                            $(currentNode).after($(prevNode));
                                        });
                                }
                            }
                        },
                        movedown: {
                            separator_before: false,
                            separator_after: true,
                            _disabled: false,
                            label: app.lang.get('LBL_CONTEXTMENU_MOVEDOWN', self.module),
                            action: function(obj) {
                                var currentNode = this._get_node(obj),
                                    nextNode = this._get_next(obj, true);
                                if (currentNode && nextNode) {
                                    self.moveNode(
                                        currentNode.data('id'),
                                        nextNode.data('id'),
                                        'after',
                                        function() {
                                            $(nextNode).after($(currentNode));
                                        });
                                }
                            }
                        },
                        moveto: {
                            separator_before: false,
                            separator_after: true,
                            _disabled: false,
                            label: app.lang.get('LBL_CONTEXTMENU_MOVETO', self.module),
                            action: false,
                            submenu: this._buildRootsSubmenu(settings)
                        },
                        delete: {
                            separator_before: false,
                            separator_after: true,
                            _disabled: false,
                            label: app.lang.get('LBL_CONTEXTMENU_DELETE', self.module),
                            action: function(obj) {
                                var bean = self.collection.getChild(obj.data('id'));
                                if (!_.isUndefined(bean)) {
                                    self.warnDelete({
                                        model: bean,
                                        success: _.bind(function() {
                                            this.remove(obj);
                                            self._toggleVisibility(self.collection.length === 0);
                                        }, this)
                                    });
                                }
                            }
                        }
                    };
                } else {
                    return {};
                }
            },

            /**
             * Popup dialog message to confirm delete action.
             * @param {Object} options
             * @param {Data.Bean} options.model Model to delete.
             * @param {Function} options.success Calback on success.
             */
            warnDelete: function(options) {
                options = options || {};
                if (_.isEmpty(options.model)) {
                    return;
                }

                app.alert.show('delete_confirmation', {
                    level: 'confirmation',
                    messages: this.getDeleteMessages(options.model).confirmation,
                    onConfirm: _.bind(function() {
                        this.deleteModel(options);
                    }, this),
                    onCancel: function() {

                    }
                });
            },

            /**
             * Formats the messages to display in the alerts when deleting a record.
             *
             * @param {Data.Bean} model The model concerned.
             * @return {Object} The list of messages.
             * @return {string} return.confirmation Confirmation message.
             * @return {string} return.success Success message.
             */
            getDeleteMessages: function(model) {
                var messages = {};
                var name = Handlebars.Utils.escapeExpression(app.utils.getRecordName(model)).trim();
                var context = app.lang.getModuleName(model.module).toLowerCase() + ' ' + name;
                messages.confirmation = app.utils.formatString(
                    model.children.length === 0 ?
                        app.lang.get('NTC_DELETE_CONFIRMATION_FORMATTED', this.module) :
                        app.lang.get('NTC_DELETE_CONFIRMATION_FORMATTED_PLURAL', this.module),
                    [context]
                );
                messages.success = app.utils.formatString(app.lang.get('NTC_DELETE_SUCCESS'), [context]);
                return messages;
            },

            /**
             * Delete the model once the user confirms the action.
             * @param {Object} options
             * @param {Data.Bean} options.model Model to delete.
             * @param {Function} options.success Calback on success.
             */
            deleteModel: function(options) {
                options = options || {};
                options.success = options.success || null;
                options.error = options.error || null;
                if (_.isEmpty(options.model)) {
                    return;
                }

                options.model.destroy({
                    //Show alerts for this request
                    showAlerts: {
                        'process': true,
                        'success': {
                            messages: this.getDeleteMessages(options.model).success
                        }
                    },
                    success: options.success,
                    error: options.error
                });
            },

            /**
             * Build submenu from root items.
             * @param {Object} settings
             * @return {Object}
             * @private
             */
            _buildRootsSubmenu: function(settings) {
                var self = this,
                    subMenu = {},
                    selectedId = this.jsTree && this.jsTree.jstree('get_selected').data('id');

                _.each(this.collection.models, function(entry, index) {
                    if (selectedId !== entry.id) {
                        subMenu['movetosubmenu' + index] = {
                            id: entry.id,
                            separator_before: false,
                            icon: 'jstree-icon',
                            separator_after: false,
                            label: entry.escape('name'),
                            action: function(obj) {
                                self.moveNode(obj.data('id'), entry.id, 'last', function(data, response) {
                                    self.jsTree.jstree(
                                        'move_node',
                                        self.jsTree.jstree('get_instance')
                                            .get_container_ul()
                                            .find('li[data-id=' + obj.data('id') + ']'),
                                        self.jsTree.jstree('get_instance')
                                            .get_container_ul()
                                            .find('li[data-id=' + entry.id + ']')
                                    );
                                });
                            }
                        };
                    }
                });

                return subMenu;
            },

            /**
             * Handle actions when tree is loaded.
             * @param {Object} $container
             * @private
             */
            _loadedHandler: function($container) {
                $container
                    .addClass('jstree-sugar')
                    .addClass('tree-component');
                if (this.jsTreeCallbacks.onLoad) {
                    this.jsTreeCallbacks.onLoad.apply();
                }
            },

            /**
             * Handle actions when node is selected.
             * @param {Event} event
             * @param {Object} data
             * @return {boolean}
             * @private
             */
            _selectNodeHandler: function(event, data) {
                if (!_.isUndefined(data.args[0])) {
                    var selectedNode = {
                        id: data.rslt.obj.data('id'),
                        name: data.rslt.obj.find('a:first').text().trim(),
                        type: data.rslt.obj.data('type') || 'folder'
                        },
                        action = $(data.args[0]).data('action');
                    if (action === 'jstree-toggle' && data.rslt.obj.hasClass('jstree-leaf')) {
                        action = 'jstree-leaf-click';
                    }
                    switch (action) {
                        case 'jstree-toggle':
                            selectedNode.open = data.rslt.obj.hasClass('jstree-closed') ? true : false;
                            if (this.jsTreeCallbacks.onToggle &&
                                !this.jsTreeCallbacks.onToggle.apply(this, [selectedNode])) {
                                return false;
                            }
                            this._jstreeToggle(event, data);
                            break;
                        case 'jstree-leaf-click':
                            if (this.jsTreeCallbacks.onLeaf &&
                                !this.jsTreeCallbacks.onLeaf.apply(this, [selectedNode])) {
                                return false;
                            }
                            break;
                        case 'jstree-contextmenu':
                            if ($(data.args[0]).hasClass('disabled') || (this.jsTreeCallbacks.onShowContextmenu &&
                                !this.jsTreeCallbacks.onShowContextmenu.apply(this, [event, data]))) {
                                return false;
                            }
                            this._jstreeShowContextmenu(event, data);
                            break;
                        case 'jstree-addnode':
                            if ($(data.args[0]).hasClass('disabled') || (this.jsTreeCallbacks.onAdd &&
                                !this.jsTreeCallbacks.onAdd.apply(this, [event, data]))) {
                                return false;
                            }
                            this._onAdd(event, data);
                            break;
                        case 'jstree-select':
                            if (this.jsTreeCallbacks.onSelect &&
                                !this.jsTreeCallbacks.onSelect.apply(this, [selectedNode])) {
                                return false;
                            }
                            this._jstreeSelectNode(selectedNode);
                            break;
                    }
                }
            },

            /**
             * Handle actions when node is created.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _createHandler: function(event, data) {
                var parentId = data.rslt.parent === -1 ? this.root : data.rslt.parent.data('id'),
                    newNode = data.rslt.obj,
                    node = {
                        title: data.rslt.name,
                        position: data.rslt.position
                    },
                    self = this;

                if (data.args[2] === undefined || data.args[2].id === undefined) {
                    this.collection.append({
                        target: parentId,
                        data: {name: node.title},
                        success: function(item) {
                            newNode.attr('data-id', item.id);
                            newNode.attr('data-level', item.lvl);
                            if (newNode.is('[disabled=disabled]')) {
                                newNode.attr('disabled', false);
                            }
                            self._toggleAddNodeButton(newNode, false);
                            self._toggleVisibility(false);
                        },
                        error: function(error) {
                            self._alertError(error, _.bind(function(){
                                this.jsTree.jstree('remove', newNode);
                            }, self));
                        }
                    });
                }
            },

            /**
             * Handle actions when edit input is displayed.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _showInputHandler: function(event, data) {
                var self = this,
                    el = data.obj.children('input'),
                    clonedElement = el.clone(),
                    obj = data.obj,
                    t = data.t,
                    h1 = data.h1,
                    h2 = data.h2,
                    w = data.w;

                h1.css({
                    fontFamily: h2.css('fontFamily') || '',
                    fontSize: h2.css('fontSize') || '',
                    fontWeight: h2.css('fontWeight') || '',
                    fontStyle: h2.css('fontStyle') || '',
                    fontStretch: h2.css('fontStretch') || '',
                    fontVariant: h2.css('fontVariant') || '',
                    letterSpacing: h2.css('letterSpacing') || '',
                    wordSpacing: h2.css('wordSpacing') || ''
                });

                el.hide();
                obj.append(clonedElement);

                this._toggleAddNodeButton(obj, true);
                this._enableTooltip(clonedElement);

                clonedElement.width(Math.min(h1.text("pW" + clonedElement[0].value).width(), w))[0].select();

                clonedElement
                    .on('keydown', function(event) {
                        var key = event.which;
                        if (key === 27) {
                            $(this).attr('data-mode') === 'add' ? $(this).attr('data-mode', 'delete') : this.value = t;
                            el.attr('data-mode') === 'add' ? el.attr('data-mode', 'delete') : el.value = t;
                        }
                        if (key === 27 || key === 13 || key === 37 || key === 38 || key === 39 || key === 40 ||
                            key === 32) {
                            event.stopImmediatePropagation();
                        }
                        if (key === 13) {
                            if (this.value.trim().length === 0) {
                                app.alert.show('wrong_node_name', {
                                    level: 'error',
                                    messages: app.error.getErrorString('empty_node_name', self),
                                    autoClose: false
                                });
                                return false;
                            } else {
                                app.alert.dismiss('wrong_node_name');
                            }
                        }
                        if (key === 27 || key === 13) {
                            self._disableTooltip(clonedElement);
                            event.preventDefault();
                            self._blur($(this), el);
                        }
                    })
                    .on('keyup', function(event) {
                        clonedElement.width(Math.min(h1.text("pW" + this.value).width(), w));
                    })
                    .on('keypress', function(event) {
                        if (event.which === 13) {
                            return false;
                        }
                    });
            },

            /**
             * Process custom blur for cloned object and call blur event in original object.
             * @param {Object} clonedObj
             * @param {Object} originalObj
             * @private
             */
            _blur: function(clonedObj, originalObj) {
                originalObj.val(clonedObj.val());
                originalObj.blur();
            },

            /**
             * Toggle tree node.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _jstreeToggle: function(event, data) {
                data.inst.toggle_node(data.rslt.obj);
            },

            /**
             * Show Context menu.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _jstreeShowContextmenu: function(event, data) {
                var container = data.inst._get_node().parent(),
                    level = data.inst._get_node().attr('data-level'),
                    firstNodeId = $(container).find('li[data-level=' + level + ']').first().data('id'),
                    lastNodeId = $(container).find('li[data-level=' + level + ']').last().data('id');

                if (!_.isUndefined(data.inst.get_settings().contextmenu.items)) {
                    // Clear contextmenu.items.moveto.submenu property.
                    data.inst._set_settings({
                        contextmenu: {items: {moveto: {submenu: null}}}
                    });
                    // Refresh contextmenu.items.moveto.submenu property.
                    data.inst._set_settings({
                        contextmenu: {items: {moveto: {submenu: this._buildRootsSubmenu(this.jsTreeSettings)}}}
                    });
                    data.inst._set_settings({
                        contextmenu: {
                            items: {
                                moveup: {_disabled: data.inst._get_node().data('id') === firstNodeId}
                            }
                        }
                    });
                    data.inst._set_settings({
                        contextmenu: {
                            items: {
                                movedown: {_disabled: data.inst._get_node().data('id') === lastNodeId}
                            }
                        }
                    });
                }

                if (!$(event.currentTarget).hasClass('jstree-loading')) {
                    data.inst.show_contextmenu($(data.args[0]), data.args[2].pageX, data.args[2].pageY);
                }
            },

            /**
             * Add action by default.
             * @param {Event} event
             * @param {Object} data
             * @private
             */
            _onAdd: function(event, data) {
                var createdNode = this.jsTree.jstree('create', data.inst._get_node());
                this._toggleAddNodeButton(createdNode, true);
            },

            /**
             * Select action by default.
             * @param {Object} selectedNode
             * @return {Object}
             * @private
             */
            _jstreeSelectNode: function(selectedNode) {
                if (this.jsTreeSettings.isDrawer) {
                    app.drawer.close(selectedNode);
                } else {
                    return selectedNode;
                }
            },

            /**
             * Remove action by default.
             * @param removedNode
             * @return {boolean}
             * @private
             */
            _jstreeRemoveNode: function(removedNode) {
                return false;
            },

            /**
             * Add action.
             * @param {String} title
             * @param {String|Number} position
             * @param {Boolean} editable
             * @param {Boolean} addToRoot
             * @param {Boolean} disabled
             */
            addNode: function(title, position, editable, addToRoot, disabled) {
                var self = this,
                    selectedNode = (addToRoot === true) ? [] : this.jsTree.jstree('get_selected'),
                    pos = position || 'last',
                    isEdit = editable || false,
                    isDisabled = disabled !== false,
                    customAttr = (isDisabled === true) ? {disabled: 'disabled'} : {};

                if (title) {
                    var createdNode = this.jsTree.jstree(
                        'create',
                        selectedNode,
                        pos,
                        {data: title, attr: customAttr},
                        function(obj) {
                            if (self.collection.length === 0) {
                                self._toggleVisibility(false);
                            }
                        },
                        isEdit
                    );
                    this._toggleAddNodeButton(createdNode, true);
                }
            },

            /**
             * Select node in tree.
             * @param {String} id
             */
            selectNode: function(id) {
                var node = this.jsTree.find('[data-id=' + id + ']');
                this.jsTree.jstree('select_node', node);
                node.addClass('jstree-clicked');
            },

            /**
             * Insert node into tree.
             * @param {Object} data
             * @param {String} parent_id
             * @param {String} type
             */
            insertNode: function(data, parent_id, type) {
                var selectedNode = this.jsTree.find('[data-id=' + parent_id + ']'),
                    isViewable = data.isViewable || false;
                this.jsTree.jstree('create', selectedNode, 'last', {data: data.name, id: data.id}, function(obj) {
                    obj.data('id', data.id)
                        .data('type', type || 'folder')
                        .attr('data-id', data.id)
                        .attr('data-disabled', !isViewable)
                        .find('ins:first').addClass('leaf');
                    if (!isViewable) {
                        obj.addClass('disabled');
                    }
                }, true, true);
            },

            /**
             * Save state of tree.
             */
            saveJSTreeState: function () {
                _.defer(function(jstree) {
                    jstree.jstree('save_state');
                }, this.jsTree);
            },

            /**
             * Load state of tree.
             */
            loadJSTreeState: function () {
                this.jsTree.jstree('load_state');
            },

            /**
             * Open required node.
             * @param {String} id
             */
            openNode: function(id) {
                var selectedNode = this.jsTree.find('[data-id=' + id +']');
                if (selectedNode.hasClass('jstree-closed')) {
                    this.jsTree.jstree('open_node', selectedNode);
                }
            },

            /**
             * Close required node.
             * @param {String} id
             */
            closeNode: function(id) {
                var selectedNode = this.jsTree.find('[data-id=' + id +']');
                if (selectedNode.hasClass('jstree-open')) {
                    this.jsTree.jstree('close_node', selectedNode);
                }
            },

            /**
             * Show child nodes which were added by insertNode.
             * @param {String} id
             */
            showChildNodes: function(id) {
                var selectedNode = this.jsTree.find('[data-id=' + id +']');
                selectedNode.children("ul:eq(0)").children("li").show();
            },
            /**
             * Hide child nodes to prevent open/close folder.
             * @param {String} id
             */
            hideChildNodes: function(id) {
                var selectedNode = this.jsTree.find('[data-id=' + id +']');
                selectedNode.children("ul:eq(0)").children("li").hide();
            },

            /**
             * Removes children with provided type for the node.
             * @param {String} id
             * @param {String} type
             */
            removeChildrens: function (id, type) {
                var currentNode = this.jsTree.find('[data-id=' + id +']'),
                    childrens = currentNode.children("ul:eq(0)").children("li");
                type = type || 'folder';
                _.each(childrens, function(child) {
                    if ($(child).data('type') === type) {
                        this.jsTree.jstree('delete_node', child);
                    }
                }, this);

            },

            /**
             * Clear selected nodes.
             */
            clearSelection: function() {
                this.jsTree.jstree('deselect_all');
            },

            /**
             * Search action.
             * @param {String} searchString
             */
            searchNode: function(searchString) {
                this.jsTree.jstree('clear_search');
                this.jsTree.jstree('close_all');

                if (!_.isUndefined(searchString)) {
                    this.jsTree.jstree('search', searchString);
                }
            },

            /**
             * Move action.
             * @param {String} idRecord
             * @param {String} idTarget
             * @param {String} position
             * @param {Function} callback
             */
            moveNode: function(idRecord, idTarget, position, callback) {
                var self = this,
                    pos = position || 'last',
                    method = 'move' + pos.charAt(0).toUpperCase() + pos.substring(1).toLowerCase();

                if (idRecord === idTarget) {
                    app.alert.show('wrong_path_confirmation', {
                        level: 'error',
                        messages: app.lang.get('LBL_WRONG_MOVE_PATH', 'Categories')
                    });
                }

                if (_.isFunction(this.collection[method]) && (idRecord !== idTarget)) {
                    this.collection[method]({
                        record: idRecord,
                        target: idTarget,
                        success: function(data, response) {
                            if (!_.isUndefined(callback)) {
                                callback(data, response);
                            }
                        },
                        error: function(error) {
                            self._alertError(error);
                        }
                    });
                }
            },

            /**
             * Alert server error.
             * @param {Object} error
             * @param {Function} callback
             * @private
             */
            _alertError: function(error, callback) {
                if (!_.isUndefined(error.message)) {
                    var level = 'error',
                        messages = error.message,
                        title;
                    switch(error.code) {
                        case 'not_authorized':
                            title = 'ERR_NO_VIEW_ACCESS_TITLE';
                            level = 'warning';
                            messages = error.message;
                            break;
                    }
                    app.alert.show('server-error', {
                        title: title,
                        level: level,
                        messages: messages
                    });
                }
                if (_.isFunction(callback)) {
                    callback();
                }
            },

            /**
             * Disable/enable 'Add Node' button for a given node.
             * @param {Object} node JSTree node object.
             * @param {Boolean} disable
             * @private
             */
            _toggleAddNodeButton: function(node, disable) {
                var addButton = $(node).find('[data-action="jstree-addnode"]'),
                    contextButton = $(node).find('[data-action="jstree-contextmenu"]');
                if (disable) {
                    addButton.addClass('disabled');
                    contextButton.addClass('disabled');
                } else {
                    addButton.removeClass('disabled');
                    contextButton.removeClass('disabled');
                }
            },

            /**
             * Enables tooltip on input element
             *
             * Note: We are enabling tooltip for focus event only.
             * That's why we need to call stopPropagation() on hover event for tooltip
             *
             * @param {jQuery} input Input element for JSTree node
             * @private
             */
            _enableTooltip: function(input) {
                input.on('hover', function(e) {
                    e.stopPropagation();
                });

                input.attr('rel', 'tooltip');
                input.attr('title', app.lang.get('LBL_CREATE_CATEGORY_PLACEHOLDER', 'KBContents'));
                input.tooltip({
                    container: 'body',
                    trigger: 'focus',
                    delay: {show: 200, hide: 100}
                }).tooltip('show');
            },

            /**
             * Disable tooltip on input element
             *
             * @param {jQuery} input Input element for JSTree node
             * @private
             */
            _disableTooltip: function(input) {
                if (!input.data('bs.tooltip')) {
                    return;
                }

                /*
                 [RS-1063]
                 This is the known bug of an old version of the Bootstrap Tooltip.
                 (see https://github.com/twbs/bootstrap/issues/10740)
                 Next line (in combination with .find('..:visible') above) is a fix for current version
                 */
                input.data('bs.tooltip').$tip.remove();
                input.tooltip('destroy');
            }
        });
    });

})(SUGAR.App);
