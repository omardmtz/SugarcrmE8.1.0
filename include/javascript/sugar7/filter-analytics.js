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
(function(app) {

    /**
     * Track filter dropdown selection.
     */
    var trackFilterDropdown = function() {
        if (!app.view.views.BaseFilterFilterDropdownView.prototype) {
            return;
        }
        var _filterDropdownProto = _.clone(app.view.views.BaseFilterFilterDropdownView.prototype);
        _.extend(app.view.views.BaseFilterFilterDropdownView.prototype, {
            handleChange: function(id) {
                _filterDropdownProto.handleChange.apply(this,[id]);
                this.trackGA(id);
            },

            trackGA: function(id) {
                app.analytics.trackEvent('click', id + 'Filter-selected', id); //...more params
            }
        });
    };

    /**
     * Track filter field and operator selection.
     */
    var trackFilterFieldAndOperator = function() {
        var currentFieldName;

        if (!app.view.views.BaseFilterRowsView.prototype) {
            return;
        }
        var _filterFieldOperatorProto = _.clone(app.view.views.BaseFilterRowsView.prototype);
        _.extend(app.view.views.BaseFilterRowsView.prototype, {

            handleOperatorSelected: function(e) {

                _filterFieldOperatorProto.handleOperatorSelected.apply(this,[e]);
                var $el = this.$(e.currentTarget),
                operator = $el.val();
                app.analytics.trackEvent(e.type, currentFieldName + "With"+ operator, e);
            },
            handleFieldSelected: function(e) {
                _filterFieldOperatorProto.handleFieldSelected.apply(this,[e]);
                var $el = this.$(e.currentTarget),
                fieldName = $el.val();
                currentFieldName = fieldName;
            }


        });
    };


    app.events.on('app:sync:complete', function() {

        trackFilterDropdown();
        trackFilterFieldAndOperator();

    });
})(SUGAR.App);
