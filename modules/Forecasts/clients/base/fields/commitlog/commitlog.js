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
 * @class View.Fields.Base.Forecasts.CommitlogField
 * @alias SUGAR.App.view.fields.BaseForecastsCommitlogField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * Stores the historical log of the Forecast entries
     */
    commitLog: [],

    /**
     * Previous committed date value to display in the view
     */
    previousDateEntered: '',

    initialize: function(options) {
        app.view.Field.prototype.initialize.call(this, options);

        this.on('show', function() {
            if (!this.disposed) {
                this.render();
            }
        }, this);
    },

    bindDataChange: function() {
        this.collection.on('reset', function() {
            this.hide();
            this.buildCommitLog();
        }, this);

        this.context.on('forecast:commit_log:trigger', function() {
            if(!this.isVisible()) {
                this.show();
            } else {
                this.hide();
            }
        }, this);
    },

    /**
     * Does the heavy lifting of looping through models to build the commit history
     */
    buildCommitLog: function() {
        //Reset the history log
        this.commitLog = [];

        if(_.isEmpty(this.collection.models)) {
            return;
        }

        // get the first model so we can get the previous date entered
        var previousModel = _.first(this.collection.models);

        // parse out the previous date entered
        var dateEntered = new Date(Date.parse(previousModel.get('date_modified')));
        if (dateEntered == 'Invalid Date') {
            dateEntered = previousModel.get('date_modified');
        }
        // set the previous date entered in the users format
        this.previousDateEntered = app.date.format(dateEntered, app.user.getPreference('datepref') + ' ' + app.user.getPreference('timepref'));

        //loop through from oldest to newest to build the log correctly
        var loopPreviousModel = '',
            models = _.clone(this.collection.models).reverse(),
            selectedUser = this.view.context.get('selectedUser'),
            forecastType = app.utils.getForecastType(selectedUser.is_manager, selectedUser.showOpps);
        _.each(models, function(model) {
            this.commitLog.push(app.utils.createHistoryLog(loopPreviousModel, model, forecastType === 'Direct'));
            loopPreviousModel = model;
        }, this);

        //reset the order of the history log for display
        this.commitLog.reverse();
    }
})
