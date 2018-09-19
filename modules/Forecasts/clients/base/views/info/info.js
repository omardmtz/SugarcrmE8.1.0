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
 * @class View.Views.Base.Forecasts.InfoView
 * @alias SUGAR.App.view.views.BaseForecastsInfoView
 * @extends View.View
 */
({
    /**
     * Timeperiod model 
     */
    tpModel: undefined,
    
    /**
     * @inheritdoc
     *
     */
    initialize: function(options) {
        if (app.lang.direction === 'rtl') {
            options.template = app.template.getView('info.info-rtl', 'Forecasts');

            // reverse the datapoints
            options.meta.datapoints.reverse();
        }

        this.tpModel = new Backbone.Model();
        this._super("initialize", [options]);
        this.resetSelection(this.context.get("selectedTimePeriod"));
    },
    
    /**
     * @inheritdoc
     *
     */
    bindDataChange: function(){
        this.tpModel.on("change", function(model){
            this.context.trigger(
                'forecasts:timeperiod:changed',
                model,
                this.getField('selectedTimePeriod').tpTooltipMap[model.get('selectedTimePeriod')]);
        }, this);
        
        this.context.on("forecasts:timeperiod:canceled", function(){
            this.resetSelection(this.tpModel.previous("selectedTimePeriod"));
        }, this);
        
    },
    
    /**
     * Sets the timeperiod to the selected timeperiod, used primarily for resetting
     * the dropdown on nav cancel
     */
    resetSelection: function(timeperiod_id){
        this.tpModel.set({selectedTimePeriod:timeperiod_id}, {silent:true});
        _.find(this.fields, function(field){
            if(_.isEqual(field.name, "selectedTimePeriod")){
                field.render();
                return true;
            }
        });
    }
    
})
