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
 * @class View.Fields.Base.PhoneField
 * @alias SUGAR.App.view.fields.BasePhoneField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     *
     * The direction for this field should always be `ltr`.
     */
    direction: 'ltr',

    /**
     * @override
     * @param options
     */
    initialize: function (options) {
        var serverInfo = app.metadata.getServerInfo();

        this.skypeEnabled = serverInfo.system_skypeout_on ? true : false;

        this._super('initialize', [options]);
    },
    /**
     * @override
     * @param value
     * @return {Mixed}
     */
    format: function (value) {
        if ((this.action === 'list' || this.action === 'detail' || this.action === 'record')
            && this.isSkypeFormatted(value)
            && this.skypeEnabled) {
            this.skypeValue = this.skypeFormat(value);
        }
        return value;
    },
    /**
     * checks if value should be skype formatted + 00 or 011 leading is necessary
     * @param value {String}
     * @return {boolean}
     */
    isSkypeFormatted: function (value) {
        if (_.isString(value)) {
            return value.substr(0, 1) === '+' || value.substr(0, 2) === '00' || value.substr(0, 3) === '011';
        } else {
            return false;
        }
    },
    /**
     * strips extra characters from phone number for skype
     *
     * Document: https://support.skype.com/en/faq/FA12006/how-do-i-script-webpages-to-find-phone-numbers-using-click-to-call
     *
     * @param value {String}
     * @return {string}
     */
    skypeFormat: function (value) {
        if (_.isString(value)) {
            var number = value.replace(/[^\d\(\)\.\-\/ ]/g, '');

            if(null !== number.match(/[\-]/g) && number.match(/[\-]/g).length >= 2) {
                // ensure format is "+CC-NDC-SN"
                number = number.replace(/[^\d\-]/g, '')
                    .replace(/(\d+)\-(\d+)\-([\d\-]+)/g, function($0, $1, $2, $3) {
                        return [$1, $2, $3.replace(/\D/g, '')].join('-');
                    });
            } else if(null !== number.match(/[\.]/g) && number.match(/[\.]/g).length >= 2) {
                // ensure format is "+CC.NDC.SN"
                number = number.replace(/[^\d\.]/g, '')
                    .replace(/(\d+)\.(\d+)\.([\d\.]+)/g, function($0, $1, $2, $3) {
                        return [$1, $2, $3.replace(/\D/g, '')].join('.');
                    });
            } else if(null !== number.match(/\(\D*\d+\D*\)/g)) {
                // ensure format is "+CC(NDC)SN"
                number = number.replace(/[^\d\(\)]+/g, '')
                    .replace(/(\d+)\((\d+)\)([0-9\(\)]+)/g, function($0, $1, $2, $3) {
                        return $1 + '(' + $2 + ')' + $3.replace(/\D/g, '');
                    })
            } else if(null !== number.match(/[\/]/g) && number.match(/[\/]/g).length >= 2) {
                // ensure format is "+CC/NDC/SN"
                number = number.replace(/[^\d\/]/g, '')
                    .replace(/(\d+)\/(\d+)\/([\d\/]+)/g, function($0, $1, $2, $3) {
                        return [$1, $2, $3.replace(/\D/g, '')].join('/');
                    });
            } else if(null !== number.match(/\S+\s+\S+\s+[\S\s]+/g)) {
                // ensure format is "+CC NDC SN"
                number = number.replace(/(\S+)\s+(\S+)\s+([\S\s]+)/g, function($0, $1, $2, $3) {
                    return _.map([$1, $2, $3], function(s) {
                        return s.replace(/\D/g, '');
                    }).join(' ');
                })
            } else {
                number = number.replace(/\D/g, '');
            }
            if(value.substr(0, 1) === '+' || (number.substr(0, 2) !== '00' && number.substr(0, 3) !== '011')) {
                number = '+' + number;
            }
            return number;

        } else if (_.isNumber(value)) {
            if(value.substr(0, 2) !== '00' && value.substr(0, 3) !== '011') {
                value = '+' + value;
            }
        }
        return value;
    }
})
