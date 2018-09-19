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
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='include/javascript/yui-old/assets/container.css'}" />
<script type="text/javascript" src='{sugar_getjspath file="include/SugarFields/Fields/Collection/SugarFieldCollection.js"}'></script>
<div id='{{sugarvar key='name'}}_div' name='{{sugarvar key='name'}}_div'><img src="{sugar_getimagepath file='sqsWait.gif'}" alt="loading..." id="{{sugarvar key="name"}}_loading_img" style="display:none"></div>
<script type="text/javascript">
//{literal}
    var callback = {
        success:function(o){
            //{/literal}
            //collection['{{sugarvar key="name"}}'] = new SUGAR.collection('{{sugarvar key="name"}}', "{{sugarvar key='module'}}", '{{$displayParams.popupData}}');
            document.getElementById('{{sugarvar key="name"}}_loading_img').style.display="none";
            document.getElementById('{{sugarvar key="name"}}_div').innerHTML = o.responseText;
            SUGAR.util.evalScript(o.responseText);
            {* //TODO: Expression Engine removed from Tokyo so SUGAR.forms no longer exists.
			{{if !empty($required)}}
            SUGAR.forms.FormValidator.add('EditView', '{{sugarvar key="name"}}_field', 'isRequiredCollection(\${{sugarvar key="name"}}_field)', SUGAR.language.get('app_strings', 'ERROR_MISSING_COLLECTION_SELECTION'));
            {{/if}} *}
            //{literal}
        },
        failure:function(o){
            alert(SUGAR.language.get('app_strings','LBL_AJAX_FAILURE'));
        }
    }
    //{/literal}
    document.getElementById('{{sugarvar key="name"}}_loading_img').style.display="inline";
    postData = '&displayParams=' + '{{$displayParamsJSON}}' + '&vardef=' + '{{$vardefJSON}}' + '&module_dir=' + document.forms.EditView.module.value + '&bean_id=' + document.forms.EditView.record.value + '&action_type=editview';
    //{literal}
    YAHOO.util.Connect.asyncRequest('POST', 'index.php?action=viewsugarfieldcollection', callback, postData);
//{/literal}
</script>