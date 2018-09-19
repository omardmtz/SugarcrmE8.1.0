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
 * @class View.Views.Base.MassupdateProgressView
 * @alias SUGAR.App.view.views.BaseMassupdateProgressView
 * @extends View.View
 */
({
    plugins: ['Editable'],

    events: {
        'click [name=btn-stop]': 'pauseUpdate'
    },

    /**
     * Total number of records.
     *
     * @property
     */
    totalRecord: 0,

    /**
     * Executed datetime in milliseconds.
     *
     * @property
     */
    _startTime: 0,

    /**
     * Speed of last executed job.
     *
     * @property
     */
    _velocity: 0,

    /**
     * HTML Elements that needs to update dynamically.
     *
     * @property
     */
    $holders: {},

    /**
     * Set of labels.
     *
     * @property
     */
    _labelSet: {
        'update': {
            PROGRESS_STATUS: 'TPL_MASSUPDATE_PROGRESS_STATUS',
            DURATION_FORMAT: 'TPL_MASSUPDATE_DURATION_FORMAT',
            FAIL_TO_ATTEMPT: 'TPL_MASSUPDATE_FAIL_TO_ATTEMPT',
            WARNING_CLOSE: 'TPL_MASSUPDATE_WARNING_CLOSE',
            WARNING_INCOMPLETE: 'TPL_MASSUPDATE_WARNING_INCOMPLETE',
            SUCCESS: 'TPL_MASSUPDATE_SUCCESS',
            TITLE: 'TPL_MASSUPDATE_TITLE'
        },
        'delete': {
            PROGRESS_STATUS: 'TPL_MASSDELETE_PROGRESS_STATUS',
            DURATION_FORMAT: 'TPL_MASSDELETE_DURATION_FORMAT',
            FAIL_TO_ATTEMPT: 'TPL_MASSDELETE_FAIL_TO_ATTEMPT',
            WARNING_CLOSE: 'TPL_MASSDELETE_WARNING_CLOSE',
            WARNING_INCOMPLETE: 'TPL_MASSDELETE_WARNING_INCOMPLETE',
            SUCCESS: 'TPL_MASSDELETE_SUCCESS',
            TITLE: 'TPL_MASSDELETE_TITLE'
        }
    },

    /**
     * @inheritdoc
     * Check if current process job is completed.
     *
     * @return {boolean} If this process job is not completed, it returns true.
     */
    hasUnsavedChanges: function() {
        return (this.totalRecord > 0);
    },

    /**
     * Initialize the job collection set.
     *
     * @param {Backbone.Collection} collection Selected set of models.
     */
    initCollection: function(collection) {
        this.unbindData();
        this.collection = collection;
        this.hide();
        this.bindDataChange();
    },

    /**
     * Returns action name.
     *
     * @return {string}
     */
    getCurrentMethod: function() {
        return this.collection.method || this.collection.defaultMethod;
    },

    /**
     * Initialize the labels.
     */
    initLabels: function() {
        this.LABELSET = this._labelSet[this.getCurrentMethod()];
    },

    /**
     * Initialize the dynamic DOM elements into the variable,
     * in order to avoid the multiple jQuery selector.
     */
    initHolders: function() {
        var self = this;
        self.$holders = {};
        this.$('[data-holder]').each(function(holder) {
            var $el = $(this);
            self.$holders[$el.data('holder')] = $el;
        });
    },

    /**
     * @inheritdoc
     */
    unbindData: function() {
        this.offBefore('start');
        this.off('render', null, this);
        app.view.View.prototype.unbindData.call(this);
    },

    /**
     * @inheritdoc
     * Bind the listeners for each massupdate status.
     */
    bindDataChange: function() {
        if (!this.collection) {
            return;
        }
        this.on('render', this.initHolders, this);
        this.collection.on('massupdate:always', this.updateProgress, this);
        this.collection.on('massupdate:start', this.showProgress, this);
        this.collection.on('massupdate:end', this.hideProgress, this);
        this.collection.on('massupdate:fail', this.checkError, this);
        this.collection.on('massupdate:resume', this.resumeProcess, this);
        this.collection.on('massupdate:pause', this.pauseProcess, this);
    },

    /**
     * Check current job occurs error or not.
     *
     * If api retry the attempt,
     * it displays api failure error message in the alert bar.
     */
    checkError: function() {
        if (this.collection.attempt === 0) {
            this.$holders.bar
                .addClass('progress-info')
                .removeClass('progress-danger');
            return;
        } else if (this.collection.attempt > this.collection.maxAllowAttempt) {
            return;
        }
        this.$holders.bar
            .removeClass('progress-info')
            .addClass('progress-danger');
        app.alert.dismiss('stop_confirmation');
        app.alert.show('stop_confirmation', {
            level: 'error',
            messages: app.lang.get(this.LABELSET['FAIL_TO_ATTEMPT'], this.module, {
                num: this.collection.attempt,
                total: this.collection.maxAllowAttempt
            })
        });
    },

    /**
     * Estimate remaining time.
     *
     * @return {Number} Remaining seconds.
     */
    getEstimate: function() {
        if (!this.collection.chunks) {
            return 0;
        }
        var chunkSize = this.collection.chunks.length,
            remainSize = this.collection.length,
            duration = (new Date().getTime() - this._startTime) / 1000;
        this._startTime = new Date().getTime();
        this._velocity = chunkSize / duration; //amount per sec

        return parseInt(remainSize / this._velocity, 10);
    },

    /**
     * Convert numeric time into the relative time.
     * @param {Number} elapsed Numeric time.
     * @return {String} Converted string time.
     *   Returns empty unless condition is satisfied.
     */
    getRelativeTime: function(elapsed) {
        var msPerMinute = 60,
            msPerHour = msPerMinute * 60,
            msPerDay = msPerHour * 24,
            unitString = '',
            relateTime = 0;

        if (elapsed <= 0) {
            return '';
        }

        if (elapsed < msPerMinute) {
            relateTime = elapsed;
            unitString = app.lang.get('LBL_DURATION_SECONDS');
        } else if (elapsed < msPerHour) {
            relateTime = Math.round(elapsed / msPerMinute);
            unitString = app.lang.get('LBL_DURATION_MINUTES');
        } else if (elapsed < msPerDay) {
            relateTime = Math.round(elapsed / msPerHour);
            unitString = app.lang.get('LBL_DURATION_HOUR');
        } else {
            //too huge
            return '';
        }

        return app.lang.get(this.LABELSET['DURATION_FORMAT'], this.collection.baseModule, {
            time: relateTime,
            unit: unitString
        });
    },

    /**
     * Returns number of total elements for progress.
     *
     * @return {Number} Number of total elements.
     */
    getTotalRecords: function() {
        return this.collection.length;
    },

    /**
     * Calculate remaining records.
     *
     * @return {Number} Remaining size.
     */
    getRemainder: function() {
        var chunkSize = _.isEmpty(this.collection.chunks) ? 0 : this.collection.chunks.length,
            size = _.min([this.collection.models.length, this.collection.length + chunkSize]);

        return size;
    },

    /**
     * Calculate current progress size.
     * Include the completed queue size and current executing chunk size.
     *
     * @return {Number} Progress size.
     */
    getProgressSize: function() {
        var chunkSize = _.isEmpty(this.collection.chunks) ? 0 : this.collection.chunks.length,
            size = _.min([this.totalRecord, this.totalRecord - this.collection.length + chunkSize]);

        return size;
    },

    /**
     * Calculate completed size.
     *
     * @return {Number} Completed size.
     */
    getCompleteRecords: function() {
        return this.totalRecord - this.collection.length;
    },

    /**
     * Calculate number of failed updates.
     *
     * @return {Number} Number of failed updates.
     */
    getFailedRecords: function() {
        return this.collection.numFailures;
    },

    /**
     * Resume the mass job once user were requested to resume.
     * Update screen in proper way.
     */
    resumeUpdate: function() {
        this.collection.resumeFetch();
    },

    /**
     * Request pausing the mass job once user were requested to pause.
     * Update screen in proper way.
     */
    pauseUpdate: function() {
        var stopButton = this.getField('btn-stop');
        stopButton.setDisabled(true);
        this.collection.pauseFetch();
    },

    /**
     * Update the progress view when the job is paused.
     */
    pauseProcess: function() {
        this.$holders.bar.removeClass('active');
        app.alert.dismiss('stop_confirmation');
        app.alert.show('stop_confirmation', {
            level: 'confirmation',
            messages: app.lang.get(this.LABELSET['WARNING_CLOSE'], this.module, {
                num: this.getRemainder()
            }),
            onConfirm: _.bind(this.hideProgress, this),
            onCancel: _.bind(this.resumeUpdate, this),
            autoClose: false
        });
    },

    /**
     * Update the progress view when the job is resumed.
     */
    resumeProcess: function() {
        this.$holders.bar.addClass('active');
        var stopButton = this.getField('btn-stop');
        stopButton.setDisabled(false);
    },

    /**
     * Start displaying the progress view.
     */
    showProgress: function() {
        this.initLabels();
        this.totalRecord = this.getTotalRecords();
        this._startTime = new Date().getTime();

        //restore back previous button status.
        var stopButton = this.getField('btn-stop');
        if (stopButton) {
            stopButton.setDisabled(false);
        }

        var title = app.lang.get(this.LABELSET.TITLE, this.module, {
            module: app.lang.getModuleName(this.module, {plural: true})
        });
        this.$holders.title.text(title);

        this.updateProgress();
        this.show();
    },

    /**
     * Finish displaying the pregress view.
     * Reset current mass job.
     */
    hideProgress: function() {
        var size = this.getCompleteRecords(),
            discardSize = this.collection.discards.length,
            failed = this.getFailedRecords();
        // "failed" records may mean field acl restrictions on "update" action
        if (discardSize > 0 || (this.getCurrentMethod() == 'update' && failed > 0)) {
            //permission warning
            var message = app.lang.get(this.LABELSET['SUCCESS'], this.module, {
                num: this.totalRecord - discardSize - failed
            });
            message += ' ' + app.lang.get('TPL_MASSUPDATE_WARNING_PERMISSION', this.module, {
                remain: discardSize + failed
            });
            app.alert.show('massupdate_final_notice', {
                level: 'warning',
                messages: message,
                autoClose: true,
                autoCloseDelay: 8000
            });
        } else if (this.totalRecord !== size || failed > 0) {
            //incomplete
            app.alert.show('massupdate_final_notice', {
                level: 'warning',
                messages: app.lang.get(this.LABELSET['WARNING_INCOMPLETE'], this.module, {
                    num: this.getRemainder() + failed
                }),
                autoClose: true,
                autoCloseDelay: 8000
            });
        } else {
            //successfully complete
            app.alert.show('massupdate_final_notice', {
                level: 'success',
                messages: app.lang.get(this.LABELSET['SUCCESS'], this.module, {
                    num: size
                }),
                autoClose: true,
                autoCloseDelay: 8000
            });
        }
        this.totalRecord = 0;
        this.collection.resetProgress();
        this.hide();
    },

    /**
     * Update current progress status.
     */
    updateProgress: function() {
        if (!this.collection || this.collection.length === 0) {
            return;
        }

        var estimate = this.getEstimate(),
            estimateMessage = this.getRelativeTime(estimate),
            size = this.getProgressSize(),
            percent = (size * 100 / this.totalRecord),
            message = app.lang.get(this.LABELSET['PROGRESS_STATUS'], this.module, {
                num: size,
                percent: Math.round(percent),
                total: this.totalRecord
            });
        if (!_.isEmpty(estimateMessage)) {
            this.$holders.estimate.text(estimateMessage);
        }
        this.checkError();
        this.$holders.message.text(message);
        this.$holders.progressbar.css({'width': percent + '%'});
    }
})
