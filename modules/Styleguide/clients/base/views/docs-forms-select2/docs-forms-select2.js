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
    // forms range
    _renderHtml: function () {
        this._super('_renderHtml');

        var $this,
            ctor;

        function select2ChartSelection(chart) {
            if (!chart.id) return chart.text; // optgroup
            return svgChartIcon(chart.id);
        }

        function select2ChartResult(chart) {
            if (!chart.id) return chart.text; // optgroup
            return svgChartIcon(chart.id) + chart.text;
        }

        //
        $('select[name="priority"]').select2({minimumResultsForSearch: 7});

        //
        $('#priority').select2({minimumResultsForSearch: 7, width: '200px'});

        //
        $("#e6").select2({
            placeholder: "Search for a movie",
            minimumInputLength: 1,
            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                url: "styleguide/content/js/select2.json",
                dataType: 'json',
                data: function (term, page) {
                    return {q:term};
                },
                results: function (data, page) { // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to alter remote JSON data
                    return {results: data.movies};
                }
            },
            formatResult: function(m) { return m.title; },
            formatSelection: function(m) { return m.title; }
        });

        //
        $this = $('#priority2');
        ctor = this.getSelect2Constructor($this);
        $this.select2(ctor);

        //
        $this = $('#state');
        ctor = this.getSelect2Constructor($this);
        ctor.formatSelection = function(state) { return state.id;};
        $this.select2(ctor);
        $('#states3').select2({width: '200px', minimumResultsForSearch: 7, allowClear: true});

        //
        $this = $('#s2_hidden');
        ctor = this.getSelect2Constructor($this);
        ctor.data = [
            {id: 0, text: 'enhancement'},
            {id: 1, text: 'bug'},
            {id: 2, text: 'duplicate'},
            {id: 3, text: 'invalid'},
            {id: 4, text: 'wontfix'}
        ];
        ctor.placeholder = "Select a issue type...";
        $this.select2(ctor);
        $('#states4').select2({width: '200px', minimumResultsForSearch: 1000, dropdownCssClass: 'select2-drop-bootstrap'});

        //
        $this = $('select[name="chart_type"]');
        ctor = this.getSelect2Constructor($this);
        ctor.dropdownCssClass = 'chart-results select2-narrow';
        ctor.width = 'off';
        ctor.minimumResultsForSearch = 9;
        ctor.formatResult = select2ChartResult;
        ctor.formatSelection = select2ChartSelection;
        ctor.escapeMarkup = function(m) { return m; };
        $this.select2(ctor);

        //
        $this = $('select[name="label_module"]');
        ctor = this.getSelect2Constructor($this);
        ctor.width = 'off';
        ctor.minimumResultsForSearch = 9;
        ctor.formatSelection = function(item) {
            return '<span class="label label-module label-module-mini label-' + item.text + '">' + item.id + '</span>';
        };
        ctor.escapeMarkup = function(m) { return m; };
        ctor.width = '55px';
        $this.select2(ctor);

        //
        $('#priority3').select2({width: '200px', minimumResultsForSearch: 7, dropdownCssClass: 'select2-drop-error'});

        //
        $('#multi1').select2({width: '100%'});
        $('#multi2').select2({width: '300px'});

        //
        $('#states5').select2({
            width: '100%',
            minimumResultsForSearch: 7,
            containerCssClass: 'select2-choices-pills-close'
        });

        //
        $('#states4').select2({
            width: '100%',
            minimumResultsForSearch: 7,
            containerCssClass: 'select2-choices-pills-close',
            formatSelection: function(item) {
                return '<span class="select2-choice-type">Link:</span><a href="javascript:void(0)" rel="' + item.id + '">' + item.text + '</a>';
            },
            escapeMarkup: function(m) { return m; }
        });

        $('.error .select .error-tooltip').tooltip({
            trigger: 'click',
            container: 'body'
        });
    },

    getSelect2Constructor: function($select) {
        var _ctor = {};
        _ctor.minimumResultsForSearch = 7;
        _ctor.dropdownCss = {};
        _ctor.dropdownCssClass = '';
        _ctor.containerCss = {};
        _ctor.containerCssClass = '';

        if ( $select.hasClass('narrow') ) {
            _ctor.dropdownCss.width = 'auto';
            _ctor.dropdownCssClass = 'select2-narrow ';
            _ctor.containerCss.width = '75px';
            _ctor.containerCssClass = 'select2-narrow';
            _ctor.width = 'off';
        }

        if ( $select.hasClass('inherit-width') ) {
            _ctor.dropdownCssClass = 'select2-inherit-width ';
            _ctor.containerCss.width = '100%';
            _ctor.containerCssClass = 'select2-inherit-width';
            _ctor.width = 'off';
        }

        return _ctor;
    }
})
