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
    // forms datetime
    _renderHtml: function () {
        var self = this;

        this._super('_renderHtml');

        // sugar7 date field
        //TODO: figure out how to set the date value when calling createField
        this.model.start_date = '2000-01-01T22:47:00+00:00';
        var fieldSettingsDate = {
            view: this,
            def: {
                name: 'start_date',
                type: 'date',
                view: 'edit',
                enabled: true
            },
            viewName: 'edit',
            context: this.context,
            module: this.module,
            model: this.model,
        },
        dateField = app.view.createField(fieldSettingsDate);
        this.$('#sugar7_date').append(dateField.el);
        dateField.render();

        // sugar7 datetimecombo field
        this.model.start_datetime = '2000-01-01T22:47:00+00:00';
        var fieldSettingsCombo = {
            view: this,
            def: {
                name: 'start_datetime',
                type: 'datetimecombo',
                view: 'edit',
                enabled: true
            },
            viewName: 'edit',
            context: this.context,
            module: this.module,
            model: this.model,
        },
        datetimecomboField = app.view.createField(fieldSettingsCombo);
        this.$('#sugar7_datetimecombo').append(datetimecomboField.el);
        datetimecomboField.render();

        // static examples
        this.$('#dp1').datepicker();
        this.$('#tp1').timepicker();

        this.$('#dp2').datepicker({format:'mm-dd-yyyy'});
        this.$('#tp2').timepicker({timeformat:'H.i.s'});

        this.$('#dp3').datepicker();

        var startDate = new Date(2012,1,20);
        var endDate = new Date(2012,1,25);

        this.$('#dp4').datepicker()
          .on('changeDate', function(ev){
            if (ev.date.valueOf() > endDate.valueOf()){
              self.$('#alert').show().find('strong').text('The start date can not be greater then the end date');
            } else {
              self.$('#alert').hide();
              startDate = new Date(ev.date);
              self.$('#startDate').text(self.$('#dp4').data('date'));
            }
            self.$('#dp4').datepicker('hide');
          });

        this.$('#dp5').datepicker()
          .on('changeDate', function(ev){
            if (ev.date.valueOf() < startDate.valueOf()){
              self.$('#alert').show().find('strong').text('The end date can not be less then the start date');
            } else {
              self.$('#alert').hide();
              endDate = new Date(ev.date);
              self.$('#endDate').text(self.$('#dp5').data('date'));
            }
            self.$('#dp5').datepicker('hide');
          });


        this.$('#tp3').timepicker({'scrollDefaultNow': true});

        this.$('#tp4').timepicker();
        this.$('#tp4_button').on('click', function (){
          self.$('#tp4').timepicker('setTime', new Date());
        });

        this.$('#tp5').timepicker({
          'minTime': '2:00pm',
          'maxTime': '6:00pm',
          'showDuration': true
        });

        this.$('#tp6').timepicker();
        this.$('#tp6').on('changeTime', function() {
          self.$('#tp6_legend').text('You selected: ' + $(this).val());
        });

        this.$('#tp7').timepicker({ 'step': 5 });
    },

    _dispose: function() {
        this.$('#dp4').off('changeDate');
        this.$('#dp5').off('changeDate');
        this.$('#tp4_button').off('click');
        this.$('#tp6').off('changeTime');

        this._super('_dispose');
    }
})
