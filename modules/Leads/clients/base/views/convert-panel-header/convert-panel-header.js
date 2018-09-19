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
({
    events: {
        'click .toggle-link': 'handleToggleClick'
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        options.meta.buttons = this.getButtons(options);
        app.view.View.prototype.initialize.call(this, options);
        this.layout.on('toggle:change', this.handleToggleChange, this);
        this.layout.on('lead:convert-dupecheck:pending', this.setDupeCheckPending, this);
        this.layout.on('lead:convert-dupecheck:complete', this.setDupeCheckResults, this);
        this.layout.on('lead:convert-panel:complete', this.handlePanelComplete, this);
        this.layout.on('lead:convert-panel:reset', this.handlePanelReset, this);
        this.layout.on('lead:convert:duplicate-selection:change', this.setAssociateButtonState, this);
        this.context.on('lead:convert:' + this.meta.module + ':shown', this.handlePanelShown, this);
        this.context.on('lead:convert:' + this.meta.module + ':hidden', this.handlePanelHidden, this);
        this.initializeSubTemplates();
    },

    /**
     * Return the metadata for the Associate/Reset buttons to be added to the
     * convert panel header
     *
     * @param {Object} options
     * @return {Array}
     */
    getButtons: function(options) {
        return [
            {
                name: 'associate_button',
                type: 'button',
                label: this.getLabel(
                    'LBL_CONVERT_CREATE_MODULE',
                    {'moduleName': options.meta.moduleSingular}
                ),
                css_class: 'btn-primary disabled'
            },
            {
                name: 'reset_button',
                type: 'button',
                label: 'LBL_CONVERT_RESET_PANEL',
                css_class: 'btn-invisible btn-link'
            }
        ];
    },

    /**
     * Initialize the Reset button to be hidden on render
     * @inheritdoc
     */
    _render: function() {
        app.view.View.prototype._render.call(this);
        this.getField('reset_button').hide();
    },

    /**
     * Compile data from the convert panel layout with some of the metadata to
     * be used when rendering sub-templates
     *
     * @return {Object}
     */
    getCurrentState: function() {
        var currentState = _.extend({}, this.layout.currentState, {
            create: (this.layout.currentToggle === this.layout.TOGGLE_CREATE),
            labelModule: this.module,
            moduleInfo: {'moduleName': this.meta.moduleSingular},
            required: this.meta.required
        });

        if (_.isNumber(currentState.dupeCount)) {
            currentState.duplicateCheckResult = {'duplicateCount': currentState.dupeCount};
        }

        return currentState;
    },

    /**
     * Pull in the sub-templates to be used to render & re-render pieces of the convert header
     * Pieces of the convert header change based on various states the panel is in
     */
    initializeSubTemplates: function() {
        this.tpls = {};
        this.initial = {};

        this.tpls.title = app.template.getView(this.name + '.title', this.module);
        this.initial.title = this.tpls.title(this.getCurrentState());

        this.tpls.dupecheckPending = app.template.getView(this.name + '.dupecheck-pending', this.module);
        this.tpls.dupecheckResults = app.template.getView(this.name + '.dupecheck-results', this.module);
    },

    /**
     * Toggle the subviews based on which link was clicked
     *
     * @param {Event} event The click event on the toggle link
     */
    handleToggleClick: function(event) {
        var nextToggle = this.$(event.target).data('next-toggle');
        this.layout.trigger('toggle:showcomponent', nextToggle);
        event.preventDefault();
        event.stopPropagation();
    },

    /**
     * When switching between sub-views, change the appropriate header components:
     * - Title changes to reflect New vs. Select (showing New ModuleName or just ModuleName)
     * - Dupe check results are shown/hidden based on whether dupe view is shown
     * - Change the toggle link to allow the user to toggle back to the other one
     * - Enable Associate button when on create view - Enable/Disable button based
     *   on whether dupe selected on dupe view
     *
     * @param {string} toggle Which view is now being displayed
     */
    handleToggleChange: function(toggle) {
        this.renderTitle();
        this.toggleDupeCheckResults(toggle === this.layout.TOGGLE_DUPECHECK);
        this.setSubViewToggle(toggle);
        this.setAssociateButtonState();
    },

    /**
     * When opening a panel, change the appropriate header components:
     * - Activate the header
     * - Display the subview toggle link
     * - Enable Associate button when on create view - Enable/Disable button
     *   based on whether dupe selected on dupe view
     * - Mark active indicator pointing up
     */
    handlePanelShown: function() {
        this.$('.accordion-heading').addClass('active');
        this.toggleSubViewToggle(true);
        this.setAssociateButtonState();
        this.toggleActiveIndicator(true);
    },

    /**
     * When hiding a panel, change the appropriate header components:
     * - Deactivate the header
     * - Hide the subview toggle link
     * - Disable the Associate button
     * - Mark active indicator pointing down
     */
    handlePanelHidden: function() {
        this.$('.accordion-heading').removeClass('active');
        this.toggleSubViewToggle(false);
        this.setAssociateButtonState(false);
        this.toggleActiveIndicator(false);
    },

    /**
     * When a panel has been marked complete, change the appropriate header components:
     * - Mark the step circle as check box
     * - Title changes to show the record associated
     * - Hide duplicate check results
     * - Hide the subview toggle link
     * - Switch to Reset button
     */
    handlePanelComplete: function() {
        this.setStepCircle(true);
        this.renderTitle();
        this.toggleDupeCheckResults(false);
        this.toggleSubViewToggle(false);
        this.toggleButtons(true);
    },

    /**
     * When a panel has been reset, change the appropriate header components:
     * - Mark the step circle back to step number
     * - Title changes back to incomplete (showing New ModuleName or just ModuleName)
     * - Show duplicate check count (if any found)
     * - Switch to back to Associate button
     * - Enable Associate button when on create view - Enable/Disable button
     *   based on whether dupe selected on dupe view
     */
    handlePanelReset: function() {
        this.setStepCircle(false);
        this.renderTitle();
        this.toggleDupeCheckResults(true);
        this.toggleButtons(false);
        this.setAssociateButtonState();
    },

    /**
     * Switch between check mark and step number
     *
     * @param {boolean} complete Whether to mark panel completed
     */
    setStepCircle: function(complete) {
        var $stepCircle = this.$('.step-circle');
        if (complete) {
            $stepCircle.addClass('complete');
        } else {
            $stepCircle.removeClass('complete');
        }
    },

    /**
     * Render the title based on current state Create vs DupeCheck and
     * Complete vs. Incomplete
     */
    renderTitle: function() {
        this.$('.title').html(this.tpls.title(this.getCurrentState()));
    },

    /**
     * Put up "Searching for duplicates" message
     */
    setDupeCheckPending: function() {
        this.renderDupeCheckResults('pending');
    },

    /**
     * Display duplicate results (if any found) or hide subview links if none found
     *
     * @param {number} duplicateCount Number of duplicates found
     */
    setDupeCheckResults: function(duplicateCount) {
        if (duplicateCount > 0) {
            this.renderDupeCheckResults('results');
        } else {
            this.renderDupeCheckResults('clear');
        }
        this.setSubViewToggleLabels(duplicateCount);
    },

    /**
     * Render either dupe check results or pending (or empty if no dupes found)
     *
     * @param {string} type Which message to show - `results` or `pending`
     */
    renderDupeCheckResults: function(type) {
        var results = '';
        if (type === 'results') {
            results = this.tpls.dupecheckResults(this.getCurrentState());
        } else if (type === 'pending') {
            results = this.tpls.dupecheckPending(this.getCurrentState());
        }
        this.$('.dupecheck-results').text(results);
    },

    /**
     * Show/hide dupe check results
     * If duplicate already selected, results will not be shown
     *
     * @param {boolean} show Whether to show the duplicate check results
     */
    toggleDupeCheckResults: function(show) {
        // if we are trying to show this, but we already have a dupeSelected, change the show to false
        if (show && this.layout.currentState.dupeSelected) {
            show = false;
        }
        this.$('.dupecheck-results').toggle(show);
    },

    /**
     * Show/hide the subview toggle links altogether
     * If panel is complete, the subview toggle will not be shown
     *
     * @param {boolean} show Whether to show the subview toggle
     */
    toggleSubViewToggle: function(show) {
        if (this.layout.currentState.complete) {
            show = false;
        }
        this.$('.subview-toggle').toggleClass('hide', !show);
    },

    /**
     * Show/hide appropriate toggle link for the subview being displayed
     *
     * @param {string} nextToggle Css class labeling the next toggle
     */
    setSubViewToggle: function(nextToggle) {
        _.each(['dupecheck', 'create'], function(currentToggle) {
            this.toggleSubViewLink(currentToggle, (nextToggle === currentToggle));
        }, this);
    },

    /**
     * Show/hide a single subview toggle link
     *
     * @param {string} currentToggle Css class labeling the current toggle
     * @param {boolean} show Whether to show the toggle link
     */
    toggleSubViewLink: function(currentToggle, show) {
        this.$('.subview-toggle .' + currentToggle).toggle(show);
    },

    /**
     * Switch subview toggle labels based on whether duplicates were found or not
     *
     * @param {number} duplicateCount
     */
    setSubViewToggleLabels: function(duplicateCount) {
        if (duplicateCount > 0) {
            this.setSubViewToggleLabel('dupecheck', 'LBL_CONVERT_IGNORE_DUPLICATES');
            this.setSubViewToggleLabel('create', 'LBL_CONVERT_BACK_TO_DUPLICATES');
        } else {
            this.setSubViewToggleLabel('dupecheck', 'LBL_CONVERT_SWITCH_TO_CREATE');
            this.setSubViewToggleLabel('create', 'LBL_CONVERT_SWITCH_TO_SEARCH');
        }
    },

    /**
     * Set label for given subview toggle
     *
     * @param {string} currentToggle Css class labeling the current toggle
     * @param {string} label Label to replace the toggle text with
     */
    setSubViewToggleLabel: function(currentToggle, label) {
        this.$('.subview-toggle .' + currentToggle).text(this.getLabel(label));
    },

    /**
     * Toggle between Associate and Reset buttons
     *
     * @param {boolean} complete
     */
    toggleButtons: function(complete) {
        var associateButton = 'associate_button',
            resetButton = 'reset_button';

        if (complete) {
            this.getField(associateButton).hide();
            this.getField(resetButton).show();
        } else {
            this.getField(associateButton).show();
            this.getField(resetButton).hide();
        }
    },

    /**
     * Activate/Deactivate the Associate button based on which subview is active
     * and whether the panel itself is active (keep disabled when panel not active)
     *
     * @param {boolean} [activate]
     */
    setAssociateButtonState: function(activate) {
        var $associateButton = this.$('[name="associate_button"]'),
            panelActive = this.$('.accordion-heading').hasClass('active');

        //use current state to determine activate if not explicit in call
        if (_.isUndefined(activate)) {
            if (this.layout.currentToggle === this.layout.TOGGLE_CREATE) {
                activate = true;
            } else {
                activate = this.layout.currentState.dupeSelected;
            }
        }

        this.setAssociateButtonLabel(this.layout.currentToggle === this.layout.TOGGLE_CREATE);

        //only activate if current panel is active
        if (activate && panelActive) {
            $associateButton.removeClass('disabled');
        } else {
            $associateButton.addClass('disabled');
        }
    },

    /**
     * Set the label for the Associate Button
     *
     * @param {boolean} isCreate
     */
    setAssociateButtonLabel: function(isCreate) {
        var label = 'LBL_CONVERT_SELECT_MODULE';
        if (isCreate) {
            label = 'LBL_CONVERT_CREATE_MODULE';
        }
        this.$('[name="associate_button"]').html(this.getLabel(label, {'moduleName': this.meta.moduleSingular}));
    },

    /**
     * Toggle the active indicator up/down
     *
     * @param {boolean} active
     */
    toggleActiveIndicator: function(active) {
        var $activeIndicator = this.$('.active-indicator i');
        $activeIndicator.toggleClass('fa-chevron-up', active);
        $activeIndicator.toggleClass('fa-chevron-down', !active);
    },

    /**
     * Get translated strings from the Leads module language file
     *
     * @param {string} key The app/mod string
     * @param {Object} [context] Any placeholder data to populate in the string
     * @return {string} The translated string
     */
    getLabel: function(key, context) {
        context = context || {};
        return app.lang.get(key, 'Leads', context);
    }
})
