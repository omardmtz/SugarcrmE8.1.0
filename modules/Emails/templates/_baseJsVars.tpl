{*
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
*}

<script type="text/javascript" language="Javascript">
var req;
var target;
var flexContentOld = "";
var forcePreview = false;
var inCompose = false;

/* globals for Callback functions */
var email; // AjaxObject.showEmailPreview
var ieId;
var ieName;
var focusFolder;
var meta; // AjaxObject.showEmailPreview
var sendType;
var targetDiv;
var urlBase = 'index.php';
var urlStandard = 'sugar_body_only=true&to_pdf=true&module=Emails&action=EmailUIAjax';

var lazyLoadFolder = null;
</script>
