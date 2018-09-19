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
// Get the global PMSE classes variable.
var PMSE = PMSE || {};

function showImage(code) {
    var _App, pmseInboxUrl;
    if (App) {
        _App = App;
    } else {
        _App = parent.SUGAR.App;
    }
    pmseInboxUrl = _App.api.buildFileURL({
        module: 'pmse_Inbox',
        id: code,
        field: 'id'
    }, {cleanCache: true});
    _App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
    viewImage(pmseInboxUrl, code, _App);
}

function viewImage(url, code, _App){
    var f, w, hp, img, ih, iw, a;
    img = new Image();
    img.src = url;
    img.onload = function () {
        if (img.width < 760) {
            ih = img.height;
            iw = img.width;
        } else {
            ih = parseInt(img.height * (760 / img.width), 10);
            iw = 760;
        }
        a = '<img width="' + iw + '" src="' + img.src + '" />';
        hp = new HtmlPanel({
            source: a,
            scroll: ((ih + 45) > 400) ? true : false
        });

        w = new PMSE.Window({
            width: iw + 40,
            height: ((ih + 45) < 400) ?  ih + 45 : 400,
            modal: true,
            //title: translate('LBL_PMSE_TITLE_CASE') + ' # ' + code + ': ' + translate('LBL_PMSE_TITLE_CURRENT_STATUS')
            title: translate('LBL_PMSE_TITLE_IMAGE_GENERATOR', 'pmse_Inbox', code)
        });
        w.addPanel(hp);
        w.show();
        _App.alert.dismiss('upload');
    };
}
