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
 * @class View.Views.Base.MergeDuplicatesProgressView
 * @alias SUGAR.App.view.views.BaseMergeDuplicatesProgressView
 * @extends View.Views.Base.MassupdateProgressView
 */
({
    extendsFrom: 'MassupdateProgressView',

    plugins: ['editable'],

    /**
     * @inheritdoc
     */
    _labelSet: {
        TITLE: 'LBL_MERGE_DUPLICATES_TITLE',
        PROGRESS_STATUS: 'TPL_MERGE_DUPLICATES_PROGRESS_STATUS',
        FAIL_TO_ATTEMPT: 'TPL_MERGE_DUPLICATES_FAIL_TO_ATTEMPT',
        FAIL: 'TPL_MERGE_DUPLICATES_FAIL'
    },

    /**
     * @property {Number} processedCount Number of processed elements.
     */
    processedCount: 0,

    /**
     * @property {Number} failsCount Number of fails.
     */
    failsCount: 0,

    /**
     * @inheritdoc
     */
    initLabels: function() {
        this.LABELSET = this._labelSet;
    },

    /**
     * Reset view parameters.
     */
    reset: function() {
        this.processedCount = 0;
        this.failsCount = 0;
        this.totalRecord = 0;
    },

    /**
     * @inheritdoc
     *
     * There are no conditions to check.
     */
    checkAvailable: function() {
        return true;
    },

    /**
     * @inheritdoc
     *
     * No estimate used.
     */
    getEstimate: function() {
        return 0;
    },

    /**
     * Set number of total elements for progress.
     *
     * @param {Number} total Number of total records.
     */
    setTotalRecords: function(total) {
        this.totalRecord = total;
    },

    /**
     * @inheritdoc
     */
    getTotalRecords: function() {
        return this.totalRecord;
    },

    /**
     * @inheritdoc
     */
    getRemainder: function() {
        return '';
    },

    /**
     * Setup count of processed elements.
     *
     * @param {Number} count Count of processed elements.
     */
    setProgressSize: function(count) {
        this.processedCount;
    },

    /**
     * Increments count of processed elements.
     */
    incrementProgressSize: function() {
        this.processedCount = this.processedCount + 1;
    },

    /**
     * @inheritdoc
     */
    getProgressSize: function() {
        return this.processedCount;
    },

    /**
     * @inheritdoc
     *
     * @param {Object} context Object to check errors.
     */
    checkError: function(context) {
        if (_.isUndefined(context) || _.isUndefined(context.attempt)) {
            return;
        }

        if (context.attempt === 0 ||
            context.attempt > (context.maxAllowAttempt || 3)
        ) {
            return;
        }

        app.alert.dismiss('check_error_message');
        app.alert.show('check_error_message', {
            level: 'warning',
            messages: app.lang.get(this.LABELSET['FAIL_TO_ATTEMPT'], this.module, {
                objectName: context.objectName || '',
                num: context.attempt,
                total: (context.maxAllowAttempt || 3)
            }),
            autoClose: true,
            autoCloseDelay: 8000
        });
    },

    /**
     * Handler for drawer `reset` event.
     * @return {boolean}
     */
    _onDrawerReset: function() {
        this.showProgress();
        return false;
    },

    /**
     * @inheritdoc
     *
     * Setup handler for drawer to prevent closing it.
     * We need it b/ the operation an be too long and in this time
     * token can be expired.
     */
    showProgress: function() {
        app.drawer.before('reset', this._onDrawerReset, this);
        this._super('showProgress');
    },

    /**
     * Update the progress view when the job is paused.
     * Triggers `massupdate:pause:completed` event on model.
     */
    pauseProgress: function() {
        var stopButton = this.getField('btn-stop');
        if (stopButton) {
            stopButton.setDisabled(true);
        }
        this.$holders.bar.removeClass('active');
        this.model.trigger('massupdate:pause:completed');
    },

    /**
     * Update the progress view when the job is resumed.
     * Triggers `massupdate:resume:completed` event on model.
     */
    resumeProgress: function() {
        var stopButton = this.getField('btn-stop');
        if (stopButton) {
            stopButton.setDisabled(false);
        }
        this.model.trigger('massupdate:resume:completed');
    },

    /**
     * Update the progress view when the job is stopped.
     * Triggers `massupdate:stop:completed` event on model.
     */
    stopProgress: function() {
        this.model.trigger('massupdate:stop:completed');
    },

    /**
     * @inheritdoc
     *
     * Dismiss alerts:
     * 1. `stop_confirmation` - confirmation on pause
     * 2. `check_error_message` - check errors status alert
     * Triggers `massupdate:end:completed` event on model.
     * Removes handler for drawer.
     */
    hideProgress: function() {
        app.drawer.offBefore('reset', this._onDrawerReset, this);
        this.hide();
        app.alert.dismiss('stop_confirmation');
        app.alert.dismiss('check_error_message');
        this.model.trigger('massupdate:end:completed');
    },

    /**
     * Called with new item is processed.
     *
     * Increments number of processed elements and
     * calls {@link View.MergeDuplicatesProgressView#updateProgress}.
     * Triggers `massupdate:item:processed:completed` event on model.
     */
    onItemProcessed: function() {
        this.incrementProgressSize();
        this.updateProgress();
        this.model.trigger('massupdate:item:processed:completed');
    },

    /**
     * Called when item go to next attemp.
     * Triggers `massupdate:item:attempt:completed` event on model.
     *
     * @param {Object} context Object that triggered event.
     */
    onNextAttept: function(context) {
        this.checkError(context);
        this.model.trigger('massupdate:item:attempt:completed');
    },

    /**
     * Called when item cannot be processed after a few attemps.
     *
     * Shows error message.
     * Triggers `massupdate:item:fail:completed` event on model.
     *
     * @param {Object} context Object that triggered event.
     */
    onItemFail: function(context) {
        this.failsCount = this.failsCount + 1;
        this.$holders.bar
            .removeClass('progress-info')
            .addClass('progress-danger');

        app.alert.dismiss('fail_message');
        app.alert.show('fail_message', {
            level: 'error',
            messages: app.lang.get(this.LABELSET['FAIL'], this.module, {
                objectName: context.objectName || ''
            })
        });
        this.model.trigger('massupdate:item:fail:completed');
    },

    /**
     * @inheritdoc
     *
     * Use model to listen events insted of collection.
     */
    bindDataChange: function() {
        if (!this.model) {
            return;
        }
        this.on('render', this.initHolders, this);
        this.before('start', this.checkAvailable, this);
        this.model.on('massupdate:always', this.updateProgress, this);
        this.model.on('massupdate:start', this.showProgress, this);
        this.model.on('massupdate:end', this.hideProgress, this);
        this.model.on('massupdate:fail', this.checkError, this);
        this.model.on('massupdate:resume', this.resumeProgress, this);
        this.model.on('massupdate:pause', this.pauseProgress, this);
        this.model.on('massupdate:stop', this.stopProgress, this);
        this.model.on('massupdate:item:processed', this.onItemProcessed, this);
        this.model.on('massupdate:item:attempt', this.onNextAttept, this);
        this.model.on('massupdate:item:fail', this.onItemFail, this);
    }
})
