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
 * @class View.Fields.Base.Styleguide.DateField
 * @alias SUGAR.App.view.fields.BaseStyleguideDateField
 * @extends View.Fields.Base.DateField
 */

({
    extendsFrom: 'DateField',

    /**
     * @inheritdoc
     */
    _dispose: function() {
        // FIXME: new date picker versions have support for plugin removal/destroy
        // we should do the upgrade in order to prevent memory leaks
        // FIXME: the base date field has a bug in disposing a datepicker field
        // that has been instantiated but not rendered.

        if (this._hasDatePicker && !_.isUndefined(this.$(this.fieldTag).data('datepicker'))) {
            $(window).off('resize', this.$(this.fieldTag).data('datepicker').place);
        }
    }
})
