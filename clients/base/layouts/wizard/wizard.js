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
 * Layout used for Wizards (like the first time login wizard).
 * Extend this layout and provide metadata for your wizard page components.
 *
 * Default implementation allows you to register a callback on the context
 * to get notified when Wizard is finished.
 *
 * For example,
 *
 *     context.set("callbacks", {
 *         complete: function(){...}
 *     }
 *
 * @class View.Layouts.Base.WizardLayout
 * @alias SUGAR.App.view.layouts.BaseWizardLayout
 * @extends View.Layout
 */
({

    /**
     * Current page index shown in Wizard
     * @private
     */
    _currentIndex: 0,

    /**
     * @param {Object} options
     * @override
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        $(window).on('keypress.' + this.cid, _.bind(this.handleKeypress, this));
    },

    /**
     * Place only initial wizard page at first
     * @param component Wizard page component
     * @override
     * @private
     */
    _placeComponent: function(component){
        if (component == this._components[this._currentIndex]) {
            this.$el.append(component.el);
        }
    },

    /**
     * Add only wizard pages that the current user needs to see.
     *
     * @param {View.Layout/View.View} component Component (view or layout) to add
     * @param {Object} def Metadata definition
     * @override
     */
    addComponent: function(component, def) {
        component = this._addButtonsForComponent(component);
        if (_.result(component, 'showPage')) {
            this._super('addComponent', [component, def]);
        }
    },
    /**
     * Helper to add appropriate buttons based on which page of wizard we're on.
     * Assumes that button 0 is previous, 1 is next, 2 is finish (Start Sugar).
     * Should only be called internal by `addComponent`.
     * @param {Object} component component from `addComponent`
     * @private
     */
    _addButtonsForComponent: function(component) {
        var buttons = [];
        component.meta = component.meta || {};
        //Adds appropriate button for component based on position in wizard
        _.each(this.meta.components, function(comp, i) {
            //found a match, add appropriate buttons based on wizard position
            if (comp.view === component.name) {
                if (i===0) {
                    //next button only
                    buttons.push(this.meta.buttons[1]);
                } else if (i === this.meta.components.length-1) {
                    // previous/start sugar buttons
                    buttons.push(this.meta.buttons[0]);
                    buttons.push(this.meta.buttons[2]);
                } else {
                    // previous/next buttons
                    buttons.push(this.meta.buttons[0]);
                    buttons.push(this.meta.buttons[1]);
                }
            }
        }, this);
        component.meta.buttons = buttons;
        return component;
    },

    /**
     * Renders a different page from the wizard
     * @param {number} newIndex New page index to select
     * @return {Object} How far the user has progressed through the wizard
     * @return {number} return.page The current page number
     * @return {number} return.lastPage The last page number
     */
    setPage: function(newIndex){
        if (newIndex !== this._currentIndex &&
                (newIndex >= 0 && newIndex < this._components.length)) {
            //detach preserves jQuery event listeners, etc.
            this._components[this._currentIndex].$el.detach();
            this._currentIndex = newIndex;
            this.$el.append(this._components[this._currentIndex].el);

            // Wait for the wizard-page to tell us it's ready for interactions from keypresses.
            this.on('wizard-page:render:complete', function() {
                $(window).on('keypress.' + this.cid, _.bind(this.handleKeypress, this));
            });

            this._components[this._currentIndex].render();
        }
        return this.getProgress();
    },

    /**
     * Only render the current component (WizardPageView) instead of each component in layout
     * @override
     * @private
     */
    _renderHtml: function() {
        if (Modernizr.touch) {
            app.$contentEl.addClass('content-overflow-visible');
        }
        if (this._components) {
            this._components[this._currentIndex].render();
        }
    },

    /**
     * Returns current progress through wizard
     * @return {Object} How far the user has progressed through the wizard
     * @return {number} return.page The current page number
     * @return {number} return.lastPage The last page number
     */
    getProgress: function(){
        return {
            page: this._currentIndex + 1,
            lastPage: this._components.length
        };
    },

    /**
     * Moves to previous page, if possible.
     * @return {Object} How far the user has progressed through the wizard
     * @return {number} return.page The current page number
     * @return {number} return.lastPage The last page number
     */
    previousPage: function(){
        // We're navigating, don't get any more keypresses.
        $(window).off('keypress.' + this.cid);
        return this.setPage(this._currentIndex - 1);
    },

    /**
     * Moves to next page, if possible.
     * @return {Object} How far the user has progressed through the wizard
     * @return {number} return.page The current page number
     * @return {number} return.lastPage The last page number
     */
    nextPage: function(){
        // We're navigating, don't get any more keypresses.
        $(window).off('keypress.' + this.cid);
        return this.setPage(this._currentIndex + 1);
    },

    /**
     * Disposes of layout then calls finished callback if registered
     */
    finished: function(){
        if (Modernizr.touch) {
            app.$contentEl.removeClass('content-overflow-visible');
        }
        var callbacks = this.context.get("callbacks"); //save callbacks first
        this.dispose();
        if (callbacks && callbacks.complete) {
            callbacks.complete();
        }
    },

    /**
     * Process next on key 'Enter'
     * @param e
     */
    handleKeypress: function(e) {
        var wizardPage = this._components[this._currentIndex];
        // Check wizardPage no matter which key we're trapping (for future expansion).
        if (wizardPage) {
            // Check if we're catching enter.
            if (e.keyCode === 13) {
                document.activeElement.blur();
                if (wizardPage.isPageComplete()) {
                    // Once we're navigating, don't get any more keypresses.
                    $(window).off('keypress.' + this.cid);
                    wizardPage.next();
                }
            }
        }
    },

    /**
     * @private
     * @override
     */
    _dispose: function() {
        // We're done with this view, remove the keypress bind.
        $(window).off('keypress.' + this.cid);
        this._super('_dispose');
    }
})
