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
 * @class View.Fields.Base.SelectionField
 * @alias SUGAR.App.view.fields.BaseSelectionField
 * @extends View.Fields.Base.BaseField
 */
({
    events: {
        'click input.selection': 'toggleSelect'
    },
    toggleSelect: function(evt) {
        var $el = $(evt.currentTarget).is(":checked");
        if($el) {
            this.check();
        } else {
            this.uncheck();
        }
    },
    check: function() {
        if(this.model) {
            this.context.set('selection_model', this.model);
        }
    },
    uncheck: function() {
        if(this.model) {
            this.context.unset('selection_model');
        }
    },
    bindDomChange: function() {
        //don't update the row's model & re-render, this is just a mechanism for selecting a row
    }
})
