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
    _renderHtml: function() {
        this._super('_renderHtml');

        // Chart data
        var dataDefault = {
            'properties': {
                'title': 'Sales by Section'
            },
            'data': [
                {key: 'Section 1', value: 3},
                {key: 'Section 2', value: 5},
                {key: 'Section 3', value: 7},
                {key: 'Section 4', value: 9}
            ]
        };

        var dataColors = {
            'properties': {
                'title': 'Sales by Section'
            },
            'data': [
                {key: 'Section 1', value: 3, color: '#d62728'},
                {key: 'Section 2', value: 5, color: '#ff7f0e'},
                {key: 'Section 3', value: 7, color: '#bcbd22'},
                {key: 'Section 4', value: 9, color: '#2ca02c'}
            ]
        };

        var dataClasses = {
            'properties': {
                'title': 'Sales by Section'
            },
            'data': [
                {key: 'Section 1', value: 3, classes: 'sc-fill09'},
                {key: 'Section 2', value: 5, classes: 'sc-fill03'},
                {key: 'Section 3', value: 7, classes: 'sc-fill12'},
                {key: 'Section 4', value: 9, classes: 'sc-fill05'}
            ]
        };

        // Color options
        var defaultOptions = {};
        var gradientOptions = {gradient: true};
        var graduatedOptions = {c1: '#e8e2ca', c2: '#3e6c0a', l: dataDefault.data.length};
        var graduatedGradientOptions = {c1: '#e8e2ca', c2: '#3e6c0a', l: dataDefault.data.length, gradient: true};

        // Chart models
        var chartDefault = sucrose.charts.pieChart().colorData('default', defaultOptions);
        var chartDefaultGradient = sucrose.charts.pieChart().colorData('default', gradientOptions);
        var chartData = sucrose.charts.pieChart().colorData('data', defaultOptions);
        var chartDataGradient = sucrose.charts.pieChart().colorData('data', gradientOptions);
        var chartGraduated = sucrose.charts.pieChart().colorData('graduated', graduatedOptions);
        var chartGraduatedGradient = sucrose.charts.pieChart().colorData('graduated', graduatedGradientOptions);
        var chartClasses = sucrose.charts.pieChart().colorData('class', defaultOptions);

        // Render
        d3.select('#pie1 svg')
            .datum(dataDefault)
            .call(chartDefault);

        d3.select('#pie2 svg')
            .datum(dataDefault)
            .call(chartDefaultGradient);

        d3.select('#pie3 svg')
            .datum(dataColors)
            .call(chartData);

        d3.select('#pie4 svg')
            .datum(dataColors)
            .call(chartDataGradient);

        d3.select('#pie5 svg')
            .datum(dataDefault)
            .call(chartGraduated);

        d3.select('#pie6 svg')
            .datum(dataDefault)
            .call(chartGraduatedGradient);

        d3.select('#pie7 svg')
            .datum(dataDefault)
            .call(chartClasses);

        d3.select('#pie8 svg')
            .datum(dataClasses)
            .call(chartData);
    }
})
