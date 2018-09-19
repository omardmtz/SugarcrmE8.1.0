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
 * @class View.Layouts.Base.ConfigDrawerContentLayout
 * @alias SUGAR.App.view.layouts.BaseConfigDrawerContentLayout
 * @extends View.Layout
 *
 * Triggered Events:
 *  config:howtoData:change - When a different accordion panel is clicked, a howtoData:change event will be triggered
 *      with the current how-to data for View.Views.Base.ConfigHowToView to listen for and update
 */
({
    events: {
        'click .accordion-toggle': 'onAccordionToggleClicked'
    },

    /**
     * The HTML ID of the Accordion divs
     */
    collapseDivId: 'config-accordion',

    /**
     * The currently-selected config panel
     */
    selectedPanel: '',

    /**
     * The current HowTo data Object
     */
    currentHowToData: undefined,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.currentHowToData = {};
        this._initHowTo();
    },

    /**
     * @inheritdoc
     */
    _render: function () {
        this._super('_render');

        //This is because backbone injects a wrapper element.
        this.$el.addClass('accordion');
        this.$el.attr('id', this.collapseDivId);

        //apply the accordion to this layout
        this.$('.collapse').collapse({
            toggle:false,
            parent:'#' + this.collapseDivId
        });

        // select the first panel in metadata
        this.selectPanel(_.first(this.meta.components).view);
    },

    /**
     * Used to select a specific panel by name
     * Correct names can be found in the specific view's hbs
     * Specifically found in the id attribute of '.accordion-heading a'
     *
     * @param {String} panelName The ID name of the panel to select
     */
    selectPanel: function(panelName) {
        this.selectedPanel = panelName;
        this.$('#' + panelName + 'Collapse').collapse('show');
        // manually trigger the accordion to toggle
        // but don't pass event so it uses the selectedPanel name
        this.onAccordionToggleClicked();
    },

    /**
     * Event handler for 'click .accordion-toggle' event
     *
     * @param {jQuery.Event|undefined} evt
     */
    onAccordionToggleClicked: function(evt) {
        var helpId = (evt) ? $(evt.currentTarget).data('help-id') : this.selectedPanel;
        this._switchHowToData(helpId);

        this.context.trigger('config:howtoData:change', this.currentHowToData);
    },

    /**
     * Function for child modules to initialize their own HowTo data
     *
     * @private
     */
    _initHowTo: function() {
    },

    /**
     * Handles switching the HowTo text and info by a specific accordion view being toggled
     *
     * @param {String} helpId The
     * @private
     */
    _switchHowToData: function(helpId) {
    },

    /**
     * Allows child config views with specific needs to be able to 'manually' update the HowTo text
     *
     * @param title
     * @param text
     */
    changeHowToData: function(title, text) {
        this.currentHowToData.title = title;
        this.currentHowToData.text = text;
        this.context.trigger('config:howtoData:change', this.currentHowToData);
    }
})
