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
var PMSE = PMSE || {};
PMSE.RegExpValidator = function(options, parent) {
    PMSE.Validator.call(this, options, parent);
    PMSE.RegExpValidator.prototype.initObject.call(this, options);
};

PMSE.RegExpValidator.prototype = new PMSE.Validator();

PMSE.RegExpValidator.prototype.type = 'PMSE.RegExpValidator';

PMSE.RegExpValidator.prototype.initObject = function(options) {
    var defaults = {
        errorMessage: "The text pattern doesn't match"
    };

    $.extend(true, defaults, options);

    this.setErrorMessage(defaults.errorMessage);
};

PMSE.RegExpValidator.prototype.validate = function() {
    var res = false;
    if (this.criteria instanceof RegExp && this.parent && this.parent.value) {
        this.valid = this.criteria.test(this.parent.value);
    } else {
        this.valid = false;
    }
};

PMSE.TextLengthValidator = function(options, parent) {
    PMSE.Validator.call(this, options, parent);
    PMSE.TextLengthValidator.prototype.initObject(this, options);
};

PMSE.TextLengthValidator.prototype = new PMSE.Validator();

PMSE.TextLengthValidator.prototype.type = 'PMSE.TextLengthValidator';

PMSE.TextLengthValidator.prototype.initObject = function(options) {
    var defaults = {
        errorMessage: "The text length doesn't match with the specified one"
    };

    $.extend(true, defaults, options);

    this.setErrorMessage(defaults.errorMessage);
};

PMSE.TextLengthValidator.prototype.validate = function() {
    var res = false,
        value = this.criteria.trim ? $.trim(this.parent.value) : this.parent.value;

    this.valid = true;

    if (this.criteria.maxLength) {
        this.valid = value.length <= parseInt(this.criteria.maxLength, 10);
    }
    if (this.criteria.minLength) {
        this.valid = (this.valid !== null ? this.valid : true) && value.length >= parseInt(this.criteria.minLength, 10);
    }
};

PMSE.CustomValidator = function(options, parent) {
    PMSE.Validator.call(this, options, parent);
};

PMSE.CustomValidator.prototype = new PMSE.Validator();

PMSE.CustomValidator.prototype.type = 'PMSE.CustomValidator';

PMSE.CustomValidator.prototype.validate = function() {
    if (typeof this.criteria.validationFunction === 'function') {
        this.valid = this.criteria.validationFunction.call(this.parent, this.parent.parent);
    }
    if (typeof this.valid === 'undefined' || this.valid === null) {
        this.valid = false;
    }
};

PMSE.NumberValidator = function(options, parent) {
    PMSE.Validator.call(this, options, parent);

    PMSE.NumberValidator.prototype.initObject.call(this, options);
};

PMSE.NumberValidator.prototype = new PMSE.Validator();

PMSE.NumberValidator.prototype.initObject = function(options) {
    var defaults = {
        criteria: {
            decimalSeparator: ".",
            errorMessage: 'The value must be a number'
        }
    };
    $.extend(true, defaults, options);

    this.setDecimalSeparator(defaults.criteria.decimalSeparator)
        .setErrorMessage(defaults.errorMessage);
};

PMSE.NumberValidator.prototype.setDecimalSeparator = function(separator) {
    this.criteria.decimalSeparator = separator;
};

PMSE.NumberValidator.prototype.validate = function() {
    var evaluate, n, aux,
        intValid = false,
        decValid = false,
        i, r, c,
        milesSeparator;
    this.valid = false;
    if (this.parent && this.parent.value) {
        evaluate = this.parent.value.replace(/\./g, "");
        evaluate = evaluate.replace(/,/g, "");
        if (! /^\s*\d+\s*$/.test(evaluate)) {
            return;
        }

        if (this.criteria.decimalSeparator !== '.' && this.criteria.decimalSeparator !== ',') {
            return;
        }

        milesSeparator = this.criteria.decimalSeparator === ',' ? '.' : ',';

        r = new RegExp("\\" + milesSeparator, "g"); //generates a regular expression equivalent to /\./g
        //split the string into integer part and decimal part
        n = this.parent.value.split(this.criteria.decimalSeparator);
        //checks if there's at most one decimal separator
        aux = this.parent.value.match(new RegExp("\\" + this.criteria.decimalSeparator, 'g'));
        if (aux && aux.length > 1) {
            return;
        }
        //checks if the integer part (witouth miles separator) is composed only by digits
        if (!/^\s*\d+\s*$/.test(n[0].replace(new RegExp('\\' + milesSeparator, 'g'), ""))) {
            return;
        }
        //checks if the integer part has at least one miles separator, if it is 
        //check the number of them is the correct
        if (n[0].match(r) && n[0].match(r).length !== 0) {
            if (n[0].charAt(0) === '0') {
                return;
            }
            aux = Math.floor(n[0].length / 4);
            aux -= (n[0].length % 4) ? 0 : 1; //the number of separators
            if (n[0].match(r).length !== aux) {
                return;
            }
            i = n[0].length - 4;
            c = 0;
            while (i > 0) {
                if (n[0].charAt(i) === milesSeparator) {
                    c += 1;
                }
                i -= 4;
            }
            if (c != aux) {
                return;
            }
            intValid = true;
        }

        if (n[1]) {
            if (!/^\s*\d+\s*$/.test(n[1])) {
                return;
            }
        }
        this.valid = true;
    }
};

PMSE.ComparisonValidator = function(options, parent) {
    PMSE.Validator.call(this, options, parent);
    PMSE.ComparisonValidator.prototype.initObject(this, options);
};

PMSE.ComparisonValidator.prototype = new PMSE.Validator();

PMSE.ComparisonValidator.prototype.type = 'PMSE.ComparisonValidator';

PMSE.ComparisonValidator.prototype.initObject = function(options) {
    var defaults = {
        errorMessage: "The comparison failed"
    };

    $.extend(true, defaults, options);

    this.setErrorMessage(defaults.errorMessage);
};

PMSE.ComparisonValidator.prototype.validate = function() {
    var evaluate, i, operators = {
        '==': function(a, b) {
            return a === b;
        },
        '>': function(a, b) {
            return a > b;
        },
        '>=': function(a, b) {
            return a >= b;
        },
        '<': function(a, b) {
            return a < b;
        },
        '<=': function(a, b) {
            return a <= b;
        }
    }, fields = this.parent.parent.items.slice(0), currentField, j;
    this.valid = false;
    if (!operators[this.criteria.operator]) {
        return;
    }
    switch (this.criteria.compare) {
    case 'textLength':
        evaluate = this.parent.value.length;
        for (i = 0; i < this.criteria.compareWith.length; i += 1) {
            for (j = 0; j < fields.length; j += 1) {
                currentField = fields.shift();
                if (currentField.name === this.criteria.compareWith[j]) {
                    break;
                }
            }
            if (!operators[this.criteria.operator](evaluate, currentField.value.length)) {
                return;
            }
        }
        break;
    case 'numeric':
        if (isNaN(this.parent.value.replace(/,/g, ""))) {
            return;
        }
        evaluate = parseFloat(this.parent.value.replace(/,/g, ""));
        for (i = 0; i < this.criteria.compareWith.length; i += 1) {
            for (j = 0; j < fields.length; j += 1) {
                currentField = fields.shift();
                if (currentField.name === this.criteria.compareWith[j]) {
                    break;
                }
            }
            if (isNaN(currentField.value.replace(/,/g, ""))) {
                return;
            }
            if (!operators[this.criteria.operator](evaluate, parseFloat(currentField.value.replace(/,/g, "")))) {
                return;
            }
        }
        break;
    default: //string
        evaluate = this.parent.value;
        for (i = 0; i < this.criteria.compareWith.length; i += 1) {
            for (j = 0; j < fields.length; j += 1) {
                currentField = fields.shift();
                if (currentField.name === this.criteria.compareWith[j]) {
                    break;
                }
            }
            if (!operators[this.criteria.operator](evaluate, currentField.value)) {
                return;
            }
        }
    }
    this.valid = true;
};

PMSE.RangeValidator = function(options, parent) {
    PMSE.Validator.call(this, options, parent);
    PMSE.RangeValidator.prototype.initObject.call(this, options);
};

PMSE.RangeValidator.prototype = new PMSE.Validator();

PMSE.RangeValidator.prototype.initObject = function(options) {
    var defaults = {
        criteria: {
            type: "string",
            dateFormat: "yyyy-mm-dd"
        },
        errorMessage: "the value is out of ranges"
    };

    $.extend(true, defaults, options);

    this.setCriteria(defaults.criteria)
        .setErrorMessage(defaults.errorMessage);
};

PMSE.RangeValidator.prototype.validate = function() {
    var that = this,
        options = [
            "minValue",
            "maxValue"
        ],
        parser = {
            string: function(val) {
                return val.toString();
            },
            numeric: function(val) {
                if (isNaN(val)) {
                    return NaN;
                }
                return parseFloat(val);
            },
            date: function(val) {
                var i, date, aux = {}, dateParts = {}, length,
                    indexes = ["yyyy", "mm", "dd", "hh", "ii", "ss"];
                if (typeof val === 'object') {
                    date = new Date(
                        val.year,
                        val.month - 1,
                        val.day,
                        val.hours || 0,
                        val.minutes || 0,
                        val.seconds || 0,
                        val.milliseconds || 0
                    );
                } else if (typeof val === 'string') {
                    that.criteria.dateFormat = $.trim(that.criteria.dateFormat);
                    /*if(that.criteria.dateFormat.length !== val.length) {
                        return null;
                    }*/
                    for (i = 0; i < indexes.length; i += 1) {
                        aux[indexes[i]] = that.criteria.dateFormat.toLowerCase().indexOf(indexes[i]);
                        switch (indexes[i]) {
                        case 'yyyy':
                        case 'mm':
                        case 'dd':
                            dateParts[indexes[i]] = aux[indexes[i]] >= 0 ? val.substr(aux[indexes[i]], indexes[i].length) : "x";
                            break;
                        default:
                            dateParts[indexes[i]] = (aux[indexes[i]] >= 0 ? val.substr(aux[indexes[i]], 2) : 0) || 0;
                        }

                        if (isNaN(dateParts[indexes[i]]) || !/^\s*\d+\s*$/.test(dateParts[indexes[i]])) {
                            return null;
                        } else {
                            dateParts[indexes[i]] = parseInt(dateParts[indexes[i]], 10);
                        }
                    }

                    if (dateParts.mm <= 0 && dateParts.dd <= 0) {
                        return null;
                    }
                    switch (dateParts.mm) {
                    case 4:
                    case 6:
                    case 9:
                    case 11:
                        if (dateParts.dd > 30) {
                            return null;
                        }
                        break;
                    case 2:
                        if (((dateParts.yyyy % 4 === 0 && dateParts.yyyy % 100 !== 0) || (dateParts.yyyy % 400 === 0))
                                && dateParts.dd > 29) {
                            return null;
                        } else {
                            if (dateParts.dd > 28) {
                                return null;
                            }
                        }
                        break;
                    default:
                        if (dateParts.dd > 31) {
                            return null;
                        }
                        break;
                    }

                    date = new Date(
                        dateParts.yyyy,
                        dateParts.mm > 0 && dateParts.mm < 13 ? dateParts.mm - 1 : "x",
                        dateParts.dd,
                        dateParts.hh >= 0 && dateParts.hh < 24 ? dateParts.hh : "x",
                        dateParts.ii >= 0 && dateParts.ii < 60 ? dateParts.ii : "x",
                        dateParts.ss >= 0 && dateParts.ss < 60 ? dateParts.ss : "x"
                    );

                } else {
                    return null;
                }
                if (Object.prototype.toString.call(date) !== "[object Date]") {
                    return null;
                }
                return !isNaN(date.getTime()) ? date : null;
            }
        },
        i,
        parsedValues = {};

    for (i = 0; i < options.length; i += 1) {
        if (this.criteria[options[i]]) {
            parsedValues[options[i]] = parser[this.criteria.type.toLowerCase()](this.criteria[options[i]]);
        }
    }

    if (!(this.criteria.minValue || this.criteria.maxValue)) {
        this.valid = false;
    } else {
        this.valid = true;
        if (parsedValues.maxValue) {
            this.valid = parser[this.criteria.type.toLowerCase()](this.parent.value) <= parsedValues.maxValue;
        }

        if (parsedValues.minValue) {
            this.valid = this.valid && parser[this.criteria.type.toLowerCase()](this.parent.value) >= parsedValues.minValue;
        }
    }
};
