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
/*global SUGAR, canvas, Document*/

var translate = function (label, module, replace) {
    //var string = (SUGAR.language.languages.ProcessMaker[label]) ? SUGAR.language.languages.ProcessMaker[label] : label;
    var string, language, arr;
    if (!module){
        if (!window.CURRENT_MODULE) {
            module = 'pmse_Project';
        } else {
            module = window.CURRENT_MODULE;
        }
    }
    if (App) {
        string = App.lang.get(label, module);
    } else {
        language = SUGAR.language.languages;
        arr = language[module];
        string = (arr && arr[label]) ? arr[label] : label;
    }
    if (!replace) {
        return string;
    } else {
        return string.toString().replace(/\%s/, replace);
    }
};
