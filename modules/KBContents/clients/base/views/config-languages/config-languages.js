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
 * @class View.Views.Base.KBContentsConfigLanguagesView
 * @alias SUGAR.App.view.layouts.BaseKBContentsConfigLanguages
 * @extends View.Views.Base.ConfigPanelView
 */
({
    extendsFrom: 'ConfigPanelView',

    /**
     * @inheritdoc
     */
    initialize: function (options) {
        this._super('initialize', [options]);
        var model = this.context.get('model');
        model.fields = this.getFieldNames();
        model.addValidationTask('validate_config_languages', _.bind(this._validateLanguages, this));
        model.on('validation:success', _.bind(this._validationSuccess, this));

        app.error.errorName2Keys['lang_empty_key'] = 'ERR_CONFIG_LANGUAGES_EMPTY_KEY';
        app.error.errorName2Keys['lang_empty_value'] = 'ERR_CONFIG_LANGUAGES_EMPTY_VALUE';
        app.error.errorName2Keys['lang_duplicate'] = 'ERR_CONFIG_LANGUAGES_DUPLICATE';
    },

    /**
     * Validate languages duplicates.
     * @param {Object} fields
     * @param {Object} errors
     * @param {Function} callback
     */
    _validateLanguages: function (fields, errors, callback) {
        var model = this.context.get('model'),
            languages = this.model.get('languages'),
            languagesToSave = [],
            index = 0,
            languageErrors = [];

        _.each(languages, function(lang) {
            var lng = _.omit(lang, 'primary'),
                key = _.first(_.keys(lng)),
                val = lang[key].trim();
            if (val.length === 0) {
                languageErrors.push({
                    'message': app.error.getErrorString('lang_empty_value', this),
                    'key': key,
                    'ind': index,
                    'type': 'value'
                });
            }
            index = index + 1;
            languagesToSave.push(key.trim().toLowerCase());
        }, this);

        if ((index = _.indexOf(languagesToSave, '')) !== -1) {
            languageErrors.push({
                'message': app.error.getErrorString('lang_empty_key', this),
                'key': '',
                'ind': index,
                'type': 'key'
            });
        }

        if (languagesToSave.length !== _.uniq(languagesToSave).length) {
            var tmp = languagesToSave.slice(0);
            tmp.sort();
            for (var i = 0; i < tmp.length - 1; i++) {
                if (tmp[i + 1] == tmp[i]) {
                    languageErrors.push({
                        'message': app.error.getErrorString('lang_duplicate', this),
                        'key': tmp[i],
                        'ind': _.indexOf(languagesToSave, tmp[i]),
                        'type': 'key'
                    });
                }
            }
        }

        if (languageErrors.length > 0) {
            errors.languages = errors.languages || {};
            errors.languages.errors = languageErrors;
            app.alert.show('languages', {
                level: 'error',
                autoClose: true,
                messages: app.lang.get('ERR_RESOLVE_ERRORS')
            });
        }
        callback(null, fields, errors);
    },

    /**
     * On success validation, trim language keys and labels
     */
    _validationSuccess: function () {
        var model = this.context.get('model'),
            languages = this.model.get('languages');

        // trim keys
        var buf = _.map(languages, function(lang) {
            var prim = lang['primary'],
                lng = _.omit(lang, 'primary'),
                key = _.first(_.keys(lng)),
                val = lang[key].trim();

            key = key.trim();
            var res = {primary: prim};
            res[key] = val;

            return res;
        }, this);

        model.set('languages', buf);
    }
})
