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
 * @class View.Layouts.Base.DashletMainLayout
 * @alias SUGAR.App.view.layouts.BaseDashletMainLayout
 * @extends View.Layout
 */
({
    tagName: "ul",
    className: "dashlets row-fluid",

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        if(this.model) {
            this.model.on("change:metadata", this.setMetadata, this);
            this.model.on("change:layout", this.setWidth, this);
            this.model.on("applyDragAndDrop", this.applyDragAndDrop, this);
            this.model.on("setMode", function(mode) {
                this.model._previousMode = this.model.mode;
                this.model.mode = mode;
            }, this);
            this.model.trigger('setMode', this.context.get("create") ? 'edit' : 'view');
        }
    },

    /**
     * Replace all components based on the dashboard metadata value
     */
    setMetadata: function() {
        if(!this.model.get("metadata")) return;
        //Clean all components
        _.each(this._components, function(component) {
            component.dispose();
        }, this);
        this._components = [];
        this.$el.children().remove();

        var components = app.utils.deepCopy(this.model.get("metadata")).components;
        _.each(components, function(component, index) {
            this.initComponents([{
                layout: {
                    type: 'dashlet-row',
                    width: component.width,
                    components: component.rows,
                    index: index + ''
                }
            }]);
        } , this);

        this.loadData();
        this.render();
    },

    /**
     * Set current main layout's width proportion
     */
    setWidth: function() {
        var metadata = this.model.get("metadata"),
            $el = this.$el.children();

        _.each(metadata.components, function(component, index){
            $el.get(index).className = $el.get(index).className.replace(/span\d+\s*/, '');
            $($el.get(index)).addClass("span" + component.width);
        }, this);
    },

    /**
     * @inheritdoc
     *
     * Resets the original css classes, and adds the dashboard classes if
     * defined.
     */
    _render: function() {
        this.$el.removeClass();
        this.$el.addClass(this.className);
        this._super('_render');
        if (this.model.has('css_class')) {
            this.$el.addClass(this.model.get('css_class'));
        }
    },

    /**
     * Set all appended dashlets drag-and-droppable
     */
    applyDragAndDrop: function() {
        if (this.model.get('drag_and_drop') === false) {
            return;
        }
        var self = this;
        this.$('.dashlet:not(.empty)').draggable({
            revert: 'invalid',
            handle: 'h4',
            scroll: true,
            scrollSensitivity: 100, //pixel
            appendTo: this.$el,
            start: function(event, ui) {
                $(this).css({visibility: 'hidden'});
                self.model.trigger("setMode", "drag");
                self.context.trigger('dashlet:draggable:start');
            },
            stop: function() {
                self.model.trigger("setMode", self.model._previousMode);
                self.$(".dashlet.ui-draggable").attr("style", "");
                self.context.trigger('dashlet:draggable:stop');
            },
            helper: function() {
                var $clone = $(this).clone();
                $clone
                    .addClass('helper')
                    .css({opacity: 0.8})
                    .width($(this).width());
                $clone.find('.btn-toolbar').remove();
                return $clone;
            }
        });

        this.$('.dashlet-container').droppable({
            activeClass: 'ui-droppable-active',
            hoverClass: 'active',
            tolerance: 'pointer',
            accept: function($el) {
                return $el.data('type') === 'dashlet' && self.$(this).find('[data-action=droppable]').length === 1;
            },
            drop: function(event, ui) {
                var sourceIndex = ui.draggable.parents(".dashlet-container:first").data('index')(),
                    targetIndex = self.$(this).data('index')();
                self.switchComponent(targetIndex, sourceIndex);
            }
        });
    },

    /**
     * Retrives the seperate component metadata from the whole dashboard components
     *
     * @param {Object} metadata for all dashboard componenets
     * @param {String} tree based trace key (each digit represents the index number of the each level)
     * @return {Object} component metadata and its dashlet frame layout
     */
    getCurrentComponent: function(metadata, tracekey) {
        var position = tracekey.split(''),
            component = metadata.components;
        _.each(position, function(index) {
            component = component.rows ? component.rows[index] : component[index];
        }, this);

        var layout = this;
        _.each(position, function(index) {
            layout = layout._components[index];
        }, this);
        return {
            metadata: component,
            layout: layout
        };
    },

    /**
     * Switch the places of two components
     *
     * @param {String} target key
     * @param {String} source key
     */
    switchComponent: function(target, source) {
        if (target === source) {
            return;
        }
        var metadata = this.model.get('metadata'),
            targetComponent = this.getCurrentComponent(metadata, target),
            sourceComponent = this.getCurrentComponent(metadata, source);

        //Swap the metadata except 'width' property since it's previous size
        var cloneMeta = app.utils.deepCopy(targetComponent.metadata);
        _.each(targetComponent.metadata, function(value, key) {
            if (key !== 'width') {
                delete targetComponent.metadata[key];
            }
        }, this);
        _.each(sourceComponent.metadata, function(value, key) {
            if (key !== 'width') {
                targetComponent.metadata[key] = value;
                delete sourceComponent.metadata[key];
            }
        }, this);
        _.each(cloneMeta, function(value, key) {
            if (key !== 'width') {
                sourceComponent.metadata[key] = value;
            }
        }, this);

        this.model.set('metadata', app.utils.deepCopy(metadata), {silent: true});
        this.model.trigger('change:layout');
        if (this.model._previousMode === 'view') {
            //Autosave for view mode
            this.model.save(null, {
                //Show alerts for this request
                showAlerts: true
            });
        }
        //Swap the view components
        var targetDashlet = targetComponent.layout._components.splice(0);
        var sourceDashlet = sourceComponent.layout._components.splice(0);

        //switch the metadata
        var targetMeta = app.utils.deepCopy(targetComponent.layout.meta);
        var sourceMeta = app.utils.deepCopy(sourceComponent.layout.meta);
        targetComponent.layout.meta = sourceMeta;
        sourceComponent.layout.meta = targetMeta;

        _.each(targetDashlet, function(comp) {
            sourceComponent.layout._components.push(comp);
            comp.layout = sourceComponent.layout;
        }, this);
        _.each(sourceDashlet, function(comp) {
            targetComponent.layout._components.push(comp);
            comp.layout = targetComponent.layout;
        }, this);
        //switch invisibility
        var targetInvisible = targetComponent.layout._invisible;
        var sourceInvisible = sourceComponent.layout._invisible;
        if (targetInvisible) {
            sourceComponent.layout.setInvisible();
        } else {
            sourceComponent.layout.unsetInvisible();
        }
        if (sourceInvisible) {
            targetComponent.layout.setInvisible();
        } else {
            targetComponent.layout.unsetInvisible();
        }

        //Swap the DOM
        var cloneEl = targetComponent.layout.$el.children(':first').get(0);
        targetComponent.layout.$el.append(sourceComponent.layout.$el.children(':not(.helper)').get(0));
        sourceComponent.layout.$el.append(cloneEl);
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        var $dashlets = this.$('.dashlet');
        var $dashletContainers = this.$('.dashlet-container');

        // Make sure the element is initialized to be draggable before destroying.
        _.each($dashlets, function(dashlet) {
            var $dashlet = $(dashlet);
            if (!_.isUndefined($dashlet.draggable('instance'))) {
                $dashlet.draggable('destroy');
            }
        });

        // Make sure the element is initialized to be droppable before destroying.
        _.each($dashletContainers, function(dashletContainer) {
            var $dashletContainer = $(dashletContainer);
            if (!_.isUndefined($dashletContainer.droppable('instance'))) {
                $dashletContainer.droppable('destroy');
            }
        });

        this._super('_dispose');
    }
})
