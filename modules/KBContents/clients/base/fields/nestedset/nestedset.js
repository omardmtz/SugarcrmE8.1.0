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
 * @class View.Fields.Base.KBContents.NestedsetField
 * @alias SUGAR.App.view.fields.BaseNestedsetField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     */
    fieldTag: 'div',

    /**
     * Root ID of a shown Nestedset.
     * @property {String}
     */
    categoryRoot: null,

    /**
     * Module which implements Nestedset.
     * @property {String}
     */
    moduleRoot: null,

    /**
     * @inheritdoc
     */
    extendsFrom: 'BaseField',

    /**
     * @inheritdoc
     */
    plugins: ['JSTree', 'NestedSetCollection'],

    /**
     * Selector for tree's placeholder.
     * @property {String}
     */
    ddEl: '[data-menu=dropdown]',

    /**
     * Flag indicates if input for new node shown.
     * @property {Boolean}
     */
    inCreation: false,

    /**
     * Callback to handle global dropdown click event.
     * @property {Callback}
     */
    dropdownCallback: null,

    /**
     * @inheritdoc
     */
    events: {
        'click [data-role=treeinput]': 'openDropDown',
        'click': 'handleClick',
        'keydown [data-role=secondinput]': 'handleKeyDown',
        'click [data-action=full-screen]': 'fullScreen',
        'click [data-action=create-new]': 'switchCreate',
        'keydown [data-role=add-item]': 'handleKeyDown',
        'click [data-action=show-list]': 'showList',
        'click [data-action=clear-field]': 'clearField'
    },

    /**
     * @inheritdoc
     */
    initialize: function(opts) {
        this._super('initialize', [opts]);
        var module = this.def.config_provider || this.context.get('module'),
            config = app.metadata.getModule(module, 'config');
        this.categoryRoot = this.def.category_root || config.category_root || '';
        this.moduleRoot = this.def.category_provider || this.def.data_provider || module;
        this.dropdownCallback = _.bind(this.handleGlobalClick, this);
        this.emptyLabel = app.lang.get(
            'LBL_SEARCH_SELECT_MODULE',
            this.module,
            {module: app.lang.get(this.def.label, this.module)}
        );
        this.before('render', function() {
            if (this.$(this.ddEl).length !== 0 && this._dropdownExists()) {
                this.closeDropDown();
            }
            return true;
        });
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        var treeOptions = {},
            $ddEl,
            self = this;
        this._super('_render');
        $ddEl = this.$(this.ddEl);
        if ($ddEl.length !== 0 && this._dropdownExists()) {
            $ddEl.dropdown();
            $ddEl.data('dropdown').opened = false;
            $ddEl.off('click.bs.dropdown');
            treeOptions = {
                settings: {
                    category_root: this.categoryRoot,
                    module_root: this.moduleRoot
                },
                options: {}
            };
            this._renderTree(
                this.$('[data-place=tree]'),
                treeOptions,
                {
                    'onSelect': _.bind(this.selectedNode, this),
                    'onLoad': function () {
                        if (!self.disposed) {
                            self.toggleSearchIcon(false);
                        }
                    }
                }
            );
            this.toggleSearchIcon(true);
            this.toggleClearIcon();
        }
    },

    /**
     * Gets HTML placeholder for a field.
     * @return {String} HTML placeholder for the field as Handlebars safe string.
     */
    getPlaceholder: function() {
        // if this in the filter row, the placeholder must have some css rules
        if (this.view && this.view.action === 'filter-rows') {
            return new Handlebars.SafeString('<span sfuuid="' + this.sfId + '" class="nestedset-filter-container"></span>');
        }
        return this._super('getPlaceholder');
    },

    /**
     * Show dropdown.
     * @param {Event} evt Triggered mouse event.
     */
    openDropDown: function(evt) {
        if (!this._dropdownExists()) {
            return;
        }
        var dropdown = this.$(this.ddEl).data('dropdown');
        if (dropdown.opened === true) {
            return;
        }
        this.view.trigger('list:scrollLock', true);
        $('body').on('click.bs.dropdown.data-api', this.dropdownCallback);
        evt.stopPropagation();
        evt.preventDefault();
        _.defer(function(dropdown, self) {
            var treePosition, $input;
            if (self.disposed) {
                return;
            }
            treePosition = self.$el.find('[data-role=treeinput]').position();
            $input = self.$('[data-role=secondinput]');
            self.$(self.ddEl).css({'left': treePosition.left - 1 + 'px', 'top': treePosition.top + 27 + 'px'});
            self.$(self.ddEl).dropdown('toggle');
            $input.val('');
            dropdown.opened = true;
            $input.focus();
        }, dropdown, this);
    },

    /**
     * Close dropdown.
     * @return {Boolean} Return `true` if dropdown has been closed, `false` otherwise.
     */
    closeDropDown: function() {
        var dropdown = this.$(this.ddEl).data('dropdown');
        if (!dropdown) {
            return false;
        }
        if (!dropdown.opened === true) {
            return false;
        }
        this.view.trigger('list:scrollLock', false);
        this.$(this.ddEl).dropdown('toggle');
        if (this.inCreation) {
            this.switchCreate();
        }
        dropdown.opened = false;
        $('body').off('click.bs.dropdown.data-api', this.dropdownCallback);
        this.clearSelection();
        return true;
    },

    /**
     * Toggle icon in search field while loading tree.
     * @param {Boolean} hide Flag indicates would we show the icon.
     */
    toggleSearchIcon: function(hide) {
        this.$('[data-role=secondinputaddon]')
            .toggleClass('fa-search', !hide)
            .toggleClass('fa-spinner', hide)
            .toggleClass('fa-spin', hide);
    },

    /**
     * Toggle clear icon in field.
     */
    toggleClearIcon: function() {
        if (_.isEmpty(this.model.get(this.def.name))) {
            this.$el.find('[data-action=clear-field]').hide();
        } else {
            this.$el.find('[data-action=clear-field]').show();
        }
    },

    /**
     * Handle global dropdown clicks.
     * @param evt {Event} Triggered mouse event.
     */
    handleGlobalClick: function(evt) {
        if (this._dropdownExists()) {
            this.closeDropDown();
            evt.preventDefault();
            evt.stopPropagation();
        }
    },

    /**
     * Handle all clicks for the field.
     * Need to catch for preventing external events.
     * @param evt {Event} Triggered mouse event.
     */
    handleClick: function(evt) {
        evt.preventDefault();
        evt.stopPropagation();
    },

    /**
     *  Search in the tree.
     */
    searchTreeValue: function() {
        var val = this.$('[data-role=secondinput]').val();
        this.searchNode(val);
    },

    /**
     * @override `Editable` plugin event to prevent default behavior.
     */
    bindKeyDown: function() {},

    /**
     * @override `Editable` plugin event to prevent default behavior.
     */
    bindDocumentMouseDown: function() {},

    /**
     * @override `Editable` plugin event to prevent default behavior.
     */
    focus: function() {
        if (this._dropdownExists()) {
            this.$('[data-role=treeinput]').click();
        }
    },

    /**
     * Handle key events in input fields.
     * @param evt {Event} Triggered key event.
     */
    handleKeyDown: function(evt) {
        var role = $(evt.currentTarget).data('role');
        if (evt.keyCode !== 13 && evt.keyCode !== 27) {
            return;
        }
        evt.preventDefault();
        evt.stopPropagation();
        switch (evt.keyCode) {
            case 13:
                switch (role) {
                    case 'secondinput':
                        this.searchTreeValue(evt);
                        break;
                    case 'add-item':
                        this.addNew(evt);
                        break;
                }
                break;
            case 27:
                switch (role) {
                    case 'secondinput':
                        this.closeDropDown();
                        break;
                    case 'add-item':
                        this.switchCreate();
                        break;
                }
                break;
        }
    },

    /**
     * Set value of a model.
     * @param {String} id Related ID value.
     * @param {String} val Related value.
     */
    setValue: function(id, val) {
        this.model.set(this.def.id_name, id);
        this.model.set(this.def.name, val);
    },

    /**
     * @inheritdoc
     *
     * No data changes to bind.
     */
    bindDomChange: function () {
    },

    /**
     * @inheritdoc
     *
     * Set right value in DOM for the field.
     */
    bindDataChange: function() {
        this.model.on("change:" + this.name, this.dataChangeUpdate, this);
    },

    /**
     * Update field data.
     */
    dataChangeUpdate: function() {
        if (this._dropdownExists()) {
            var id = this.model.get(this.def.id_name),
                name = this.model.get(this.def.name),
                child = this.collection.getChild(id);
            if (!name && child) {
                name = child.get(this.def.rname);
            }
            if (!name) {
                bean = app.data.createBean(this.moduleRoot, {id: id});
                bean.fetch({
                    success: _.bind(function(data) {
                        if (this.model) {
                            this.model.set(this.def.name, data.get(this.def.rname));
                        }
                    }, this)
                });
            }
            this.$('[data-role="treevalue"]','[name=' + this.def.name + ']').text(name);
            this.$('[name=' + this.def.id_name + ']').val(id);
        }
        if (!this.disposed) {
            this.render();
        }
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        if (this._dropdownExists()) {
            $('body').off('click.bs.dropdown.data-api', this.dropdownCallback);
        }
        this._super('_dispose');
    },

    /**
     * Open drawer with tree list.
     */
    fullScreen: function() {
        var treeOptions = {
            category_root: this.categoryRoot,
            module_root: this.moduleRoot,
            plugins: ['dnd', 'contextmenu'],
            isDrawer: true
            },
            treeCallbacks = {
                'onRemove': function(node) {
                    if (this.context.parent) {
                        this.context.parent.trigger('kbcontents:category:deleted', node);
                    }
                },
                'onSelect': function(node) {
                    if (!_.isEmpty(node) && !_.isEmpty(node.id) && !_.isEmpty(node.name)) {
                        return true;
                    }
                }
            },
        // @TODO: Find out why params from context for drawer don't pass to our view tree::_initSettings
            context = _.extend({}, this.context, {treeoptions: treeOptions, treecallbacks: treeCallbacks});
        app.drawer.open({
            layout: 'nested-set-list',
            context: {
                module: 'Categories',
                parent: context,
                treeoptions: treeOptions,
                treecallbacks: treeCallbacks
            }
        }, _.bind(this.selectedNode, this));
    },

    /**
     * Open drawer with module records.
     */
    showList: function() {
        var popDef = {},
            filterOptions;
        popDef[this.def.id_name] = this.model.get(this.def.id_name);
        filterOptions = new app.utils.FilterOptions()
            .config(this.def)
            .setFilterPopulate(popDef)
            .format();

        app.drawer.open({
            layout: 'prefiltered',
            module: this.module,
            context: {
                module: this.module,
                filterOptions: filterOptions,
            }
        });
    },

    /**
     * Add new element to the tree.
     * @param {Event} evt Triggered key event.
     */
    addNew: function(evt) {
        var name = $(evt.target).val().trim();
        if (!name) {
            app.alert.show('wrong_node_name', {
                level: 'error',
                messages: app.error.getErrorString('empty_node_name', this),
                autoClose: true
            });
        } else {
            this.addNode(name, 'last', true, false, true);
            this.switchCreate();
        }
    },

    /**
     * Create and hide input for new element.
     */
    switchCreate: function() {
        var $options = this.$('[data-place=bottom-options]'),
            $create = this.$('[data-place=bottom-create]'),
            $input = this.$('[data-role=add-item]'),
            placeholder = app.lang.get('LBL_CREATE_CATEGORY_PLACEHOLDER', this.module);
        if (this.inCreation === false) {
            $options.hide();
            $create.show();
            $input
                .tooltip({
                    title: placeholder,
                    container: 'body',
                    trigger: 'manual',
                    delay: {show: 200, hide: 100}
                })
                .tooltip('show');
            $input.focus().select();
        } else {
            $input.tooltip('destroy');
            $input.val('');
            $create.hide();
            $options.show();
        }
        this.inCreation = !this.inCreation;
    },

    /**
     * Clear input element.
     */
    clearField: function(event) {
        event.preventDefault();
        event.stopPropagation();
        this.setValue('', '');
        this.$('[data-role="treevalue"]','[name=' + this.def.name + ']').text(this.emptyLabel);
        this.$('[name=' + this.def.id_name + ']').val();
        this.toggleClearIcon();
    },

    /**
     * Callback to handle selection of the tree.
     * @param data {Object} Data from selected node.
     */
    selectedNode: function(data) {
        if (_.isEmpty(data) || _.isEmpty(data.id) || _.isEmpty(data.name)) {
            return;
        }
        var id = data.id,
            val = data.name;
        this.setValue(id, val);
        this.closeDropDown();
        this.toggleClearIcon();
    },

    /**
     * Checks whether we need to work with dropdown on the view.
     * @private
     */
    _dropdownExists: function() {
        return this.action === 'edit' || (this.meta && this.meta.view === 'edit');
    },

    /**
     * We don't need tooltip, because it breaks dropdown.
     * @inheritdoc
     */
    decorateError: function(errors) {
        var $tooltip = $(this.exclamationMarkTemplate()),
            $ftag = this.$('span.select-arrow');
        this.$el.closest('.record-cell').addClass('error');
        this.$el.addClass('error');
        $ftag.after($tooltip);
        this.$('[data-role=parent]').addClass('error');
    },

    /**
     * Need to remove own error decoration.
     * @inheritdoc
     */
    clearErrorDecoration: function() {
        this.$el.closest('.record-cell').removeClass('error');
        this.$el.removeClass('error');
        this.$('[data-role=parent]').removeClass('error');
        this.$('.add-on.error-tooltip').remove();
        if (this.view && this.view.trigger) {
            this.view.trigger('field:error', this, false);
        }
    },

    /**
     * @inheritdoc
     */
    exclamationMarkTemplate: function() {
        var extraClass = this.view.tplName === 'record' ? 'top0' : 'top4';
        return '<span class="error-tooltip ' + extraClass + ' add-on" data-contexclamationMarkTemplateainer="body">' +
        '<i class="fa fa-exclamation-circle">&nbsp;</i>' +
        '</span>';
    }
})
