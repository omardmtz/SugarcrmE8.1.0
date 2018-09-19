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
    className: 'headerpane',

    initialize: function(options) {
        this._super('initialize', [options]);
        var request = this.context.get('request');
        this.page = request.page_details;
        this.sections = request.page_data.docs.sections;
        this.$find = [];
    },

    _render: function() {
        var self = this,
            $optgroup = {};

        // render view
        this._super('_render');

        // styleguide guide doc search
        this.$find = $('#find_patterns');

        if (this.$find.length) {
            // build search select2 options
            $.each(this.sections, function(k, v) {
                if (!v.index) {
                    return;
                }
                $optgroup = $('<optgroup>').appendTo(self.$find).attr('label', v.title);
                $.each(v.pages, function(i, d) {
                    renderSearchOption(k, i, d, $optgroup);
                });
            });

            // search for patterns
            this.$find.on('change', function(e) {
                window.location.href = $(this).val();
            });

            // init select2 control
            this.$find.select2();
        }

        function renderSearchOption(section, page, d, optgroup) {
            $('<option>')
                .appendTo(optgroup)
                .attr('value', (d.url ? d.url : fmtLink(section, page)))
                .text(d.title);
        }

        function fmtLink(section, page) {
            return '#Styleguide/docs/' + section + (page ? '-' + page : '-index');
        }
    },

    _dispose: function() {
        this.$find.off('change');
        this._super('_dispose');
    }
})
