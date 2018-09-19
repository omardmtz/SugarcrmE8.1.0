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

if(typeof(SimpleList) == 'undefined'){
	var Dom = YAHOO.util.Dom;
    SimpleList = function(){
        var editImage;
        var deleteImage;
        var ul_list;
        var jstransaction;
        var lastEdit;
        var isIE = isSupportedIE();
        var requiredOptions;
        var listName;
        return {
    init: function(editImage, deleteImage) {
        var ul = document.getElementById('ul1', 'drpdwn');
        SimpleList.lastEdit = null; // Bug 14662
        SimpleList.editImage = editImage;
        SimpleList.deleteImage = deleteImage;
        new YAHOO.util.DDTarget("ul1");

        Studio2.scrollZones = {}
        for (var i = 0; Dom.get("ul" + i); i++){
            Studio2.scrollZones["ul" + i] = Studio2.getScrollZones("ul" + i);
        }
           
        for (i=0;i<SimpleList.ul_list.length;i++){
            if ( typeof SimpleList.ul_list[i] != "number" && SimpleList.ul_list[i] == "" ) {
                SimpleList.ul_list[i] = SUGAR.language.get('ModuleBuilder', 'LBL_BLANK');
            }
            new Studio2.ListDD(SimpleList.ul_list[i], 'drpdwn', false);
        }
        YAHOO.util.Event.on("dropdownaddbtn", "click", this.addToList, 'dropdown_form');
        SimpleList.jstransaction = new JSTransaction();
        SimpleList.jstransaction.register('deleteDropDown', SimpleList.undoDeleteDropDown, SimpleList.undoDeleteDropDown);
        SimpleList.jstransaction.register('changeDropDownValue', SimpleList.undoDropDownChange, SimpleList.redoDropDownChange);

    },
    isValidDropDownKey : function(value){
    	if(value.match(/^[\w\d \.]+$/i) || value == "")
    		return true;
    	
    	return false;
    },
    isBlank : function(value){
    	return value == SUGAR.language.get('ModuleBuilder', 'LBL_BLANK') 
    			|| (typeof value != "number" && value == "");
    },
    addToList : function(event, form){
        var dropName = document.getElementById('drop_name');
        var dropValue = document.getElementById('drop_value');
        dropName.value = trim(dropName.value);
        dropValue.value = trim(dropValue.value);

    	//Validate the dropdown key manually
    	removeFromValidate('dropdown_form', 'drop_name');
        if (!SimpleList.isValidDropDownKey(YAHOO.lang.escapeHTML(dropName.value))) {
			addToValidate('dropdown_form', 'drop_name', 'error', false, SUGAR.language.get('ModuleBuilder', 'LBL_JS_VALIDATE_KEY_WITH_SPACE'));
    	}
    	
    	if (!check_form("dropdown_form")) return;

        if ((!SimpleList.isBlank(YAHOO.lang.escapeHTML(dropName.value)) &&
            SimpleList.isBlank(YAHOO.lang.escapeHTML(dropValue.value))) ||
            (SimpleList.isBlank(YAHOO.lang.escapeHTML(dropName.value)) &&
            !SimpleList.isBlank(YAHOO.lang.escapeHTML(dropValue.value)))) {
            alert(SUGAR.language.get('ModuleBuilder', 'LBL_DROPDOWN_BLANK_WARNING'));
            return;
        }

        var ul1=YAHOO.util.Dom.get("ul1");

        var items = ul1.getElementsByTagName("li");
        for (i=0;i<items.length;i=i+1) {
            if ((SimpleList.isBlank(items[i].id) &&
                SimpleList.isBlank(YAHOO.lang.escapeHTML(dropName.value))) ||
                items[i].id == YAHOO.lang.escapeHTML(dropName.value)) {
                alert(SUGAR.language.get('ModuleBuilder', 'LBL_DROPDOWN_KEY_EXISTS'));
                return;
            }
        }

        liObj = document.createElement('li');
        liObj.className = "draggable";
        if (YAHOO.lang.escapeHTML(dropName.value) == '' || !YAHOO.lang.escapeHTML(dropName.value)) {
            liObj.id = SUGAR.language.get('ModuleBuilder', 'LBL_BLANK');
        } else {
            liObj.id = YAHOO.lang.escapeHTML(dropName.value);
        }

        var text1 = document.createElement('input');
        text1.type = 'hidden';
        text1.id = 'value_' + liObj.id;
        text1.name = 'value_' + liObj.id;
        text1.value = YAHOO.lang.escapeHTML(dropValue.value);

        var html = '<table width=\'100%\'><tr><td class=\'first\'><b>' + liObj.id +
            '</b><input id=\'value_' + liObj.id + '\' value="' +
            YAHOO.lang.escapeHTML(dropValue.value) +
            '" type = \'hidden\'><span class=\'fieldValue\' id=\'span_' + liObj.id + '\'>';
        if (dropValue.value == '') {
            html += '[' + SUGAR.language.get('ModuleBuilder', 'LBL_BLANK') + ']';
        } else {
            html += '[' + YAHOO.lang.escapeHTML(dropValue.value) + ']';
        }
        html += '</span>';
        html += '<span class=\'fieldValue\' id=\'span_edit_' + liObj.id + '\' style=\'display:none\'>';
        html += '<input type=\'text\' id=\'input_' + liObj.id + '\' value="' +
            YAHOO.lang.escapeHTML(dropValue.value) + '" onchange=\'SimpleList.setDropDownValue("' +
            liObj.id + '\', unescape(this.value), true)\' >';
        html += '</span>';
        html += '</td><td align=\'right\'><a href=\'javascript:void(0)\' onclick=\'SimpleList.editDropDownValue("' +
            liObj.id + '", true)\'>' + SimpleList.editImage + '</a>';
        html += '&nbsp;<a href=\'javascript:void(0)\' onclick=\'SimpleList.deleteDropDownValue("' +
            liObj.id + '", true)\'>' + SimpleList.deleteImage + '</a>';
        html += '</td></tr></table>';

        liObj.innerHTML = html;
        ul1.appendChild(liObj);
        new Studio2.ListDD(liObj, 'drpdwn', false);
        dropValue.value = '';
        dropName.value = '';
        dropName.focus();

        SimpleList.jstransaction.record('deleteDropDown',{'id': liObj.id });

    },
 
    sortAscending: function ()
    {
        // now sort using a Shellsort - do this rather than by using the inbuilt sort function as we need to sort a complex DOM inplace
        var parent = YAHOO.util.Dom.get("ul1");
        var items = parent.getElementsByTagName("li") ;
        var increment = Math.floor ( items.length / 2 ) ;
        
        function swapItems(itemA, itemB) {
        	var placeholder = document.createElement ( "li" ) ;
            Dom.insertAfter(placeholder, itemA);
            Dom.insertBefore(itemA, itemB);
            Dom.insertBefore(itemB, placeholder);
            
            //Cleanup the placeholder element
            parent.removeChild(placeholder);
        }

        while (increment > 0) {
            for (var i = increment; i < items.length; i++) {
                var j = i,
                    getItemValue = function(id) {
                        var input = document.getElementById('input_' + id) || document.getElementById('value_' + id);
                        return input && input.value ? input.value.toLowerCase() : "";
                    },
                    id = items[i].id,
                    iValue = getItemValue(id);

                while ((j >= increment) && (getItemValue(items[j - increment].id) > iValue)) {
                    // logically, this is what we need to do: items [j] = items [j - increment];
                    // but we're working with the DOM through a NodeList (items) which is readonly, so things aren't that simple
                    // A placeholder will be used to keep track of where in the DOM the swap needs to take place
                    // especially with IE which enforces the prohibition on duplicate Ids, so copying nodes is problematic
                    swapItems(items [j], items [j - increment]);
                    j = j - increment;
                }
            }

            if (increment == 2)
                increment = 1;
            else
                increment = Math.floor(increment / 2.2);
        }
    },
    sortDescending: function ()
    {
        this.sortAscending();
        var reverse = function ( children )
        {
            var parent = children [ 0 ] . parentNode ;
            var start = 0;
            if ( children [ 0 ].id == '-blank-' ) // don't include -blank- element in the sort
                start = 1 ;
            for ( var i = children.length - 1 ; i >= start ; i-- )
            {
                parent.appendChild ( children [ i ] ) ;
            }
        };
        reverse ( YAHOO.util.Dom.get("ul1").getElementsByTagName("li") ) ;
    },
    handleSave:function(){
         var parseList = function(ul, title) {
            var items = ul.getElementsByTagName("li");
            var out = [];
            for (i=0;i<items.length;i=i+1) {
                var name = items[i].id;
                var value = document.getElementById('value_'+name).value;
                out[i] = [ name , unescape(value) ];
            }
            return YAHOO.lang.JSON.stringify(out);
        };
        var ul1=YAHOO.util.Dom.get("ul1");
        var hasDeletedItem = false;
        for(j = 0; j < SimpleList.jstransaction.JSTransactions.length; j++){
            if(SimpleList.jstransaction.JSTransactions[j]['transaction'] == 'deleteDropDown') {
                var liEl = new YAHOO.util.Element(SimpleList.jstransaction.JSTransactions[j]['data']['id']);
                if (liEl && liEl.hasClass('deleted'))
                    hasDeletedItem = true;
                break;
            }
        }
        if(hasDeletedItem) {
        	if(!confirm(SUGAR.language.get('ModuleBuilder', 'LBL_CONFIRM_SAVE_DROPDOWN')))
        		return false;        	
    	}
        
        for(j = 0; j < SimpleList.jstransaction.JSTransactions.length; j++){
            if(SimpleList.jstransaction.JSTransactions[j]['transaction'] == 'deleteDropDown'){
                var liEl = new YAHOO.util.Element(SimpleList.jstransaction.JSTransactions[j]['data']['id']);
                if(liEl && liEl.hasClass('deleted'))
                	ul1.removeChild(liEl.get("element"));
            }
        }
        var list = document.getElementById('list_value');

        var out = parseList(ul1, "List 1");
        list.value = out;
        ModuleBuilder.refreshDD_name = document.getElementById('dropdown_name').value;
        if (document.forms.popup_form)
        {
            ModuleBuilder.handleSave("dropdown_form", ModuleBuilder.refreshDropDown);
        }
        else
        {
            ModuleBuilder.handleSave("dropdown_form", ModuleBuilder.refreshGlobalDropDown);
        }
    },
    isRequiredItem: function(name) {
        var required = false;
        for (var i in SimpleList.requiredOptions) {
            if (SimpleList.requiredOptions[i] == name) {
                required = true;
                break;
            }
        }
        
        return required;
    },
    getDeleteConfirmationMessage: function(id) {
        // Base key is the always available confirmation lang string index.
        // Name key is a string that could exist within ModuleBuilder for a
        // given dropdown name. This allows for customizations of messaging per
        // dropdown.
        // Item key is a string that could exist for a given list item. This allows
        // for very fine control over confirmation messages to the list item level.
        var baseKey = 'LBL_JS_DELETE_REQUIRED_DDL_ITEM',
            nameKey = (SimpleList.name) ? baseKey + '_' + SimpleList.name.toUpperCase() : baseKey,
            itemKey = baseKey + '_' + id.replace(/\s/g, '_').replace(/[^\w]/gi, '').toUpperCase(),
            message = SUGAR.language.get('ModuleBuilder', itemKey);

        // See if the item key check passed muster. Checking 'undefined' here is
        // safe, as that is what is returned from get()
        if (message == 'undefined') {
            message = SUGAR.language.get('ModuleBuilder', nameKey);
        }

        // If name key is undefined then we fall back to the base key which is
        // always there
        if (message == 'undefined') {
            message = SUGAR.language.get('ModuleBuilder', baseKey);
        }

        return message;
    },
    deleteDropDownValue : function(id, record){
        var required = SimpleList.isRequiredItem(id),
            message  = SimpleList.getDeleteConfirmationMessage(id);

        if (!required || (required && confirm(message))) {
            var field = new YAHOO.util.Element(id);
            if(record){
                SimpleList.jstransaction.record('deleteDropDown',{'id': id });
            }
            if (field.hasClass('deleted')) {
                field.removeClass('deleted');
            } else {
                field.addClass('deleted');
            }
        }
    },
    editDropDownValue : function(id, record){
        //Close any other dropdown edits
        if (SimpleList)
            SimpleList.endCurrentDropDownEdit();
        var dispSpan = document.getElementById('span_'+id);
        var editSpan = document.getElementById('span_edit_'+id);
        dispSpan.style.display = 'none';

        if(SimpleList.isIE){
            editSpan.style.display = 'inline-block';
        }else{
            editSpan.style.display = 'inline';
        }
        var textbox = document.getElementById('input_'+id);
        textbox.focus();
        SimpleList.lastEdit = id;
    },
    endCurrentDropDownEdit : function() {
        if (SimpleList.lastEdit != null)
        {
            var valueLastEdit = unescape(document.getElementById('input_'+SimpleList.lastEdit).value);
            SimpleList.setDropDownValue(SimpleList.lastEdit,valueLastEdit,true);
        }
    },
    setDropDownValue : function(id, val, record){

        if(record){
            SimpleList.jstransaction.record('changeDropDownValue', {'id':id, 'new':val, 'old':document.getElementById('value_'+ id).value});
        }
        var dispSpan = document.getElementById('span_'+id);
        var editSpan = document.getElementById('span_edit_'+id);
        var textbox = document.getElementById('input_'+id);

        dispSpan.style.display = 'inline';
        editSpan.style.display = 'none';
        dispSpan.innerHTML = "["+val+"]";
        document.getElementById('value_'+ id).value = val;
        SimpleList.lastEdit = null; // Bug 14662 - clear the last edit point behind us
    },
    undoDeleteDropDown : function(transaction){

        SimpleList.deleteDropDownValue(transaction['id'], false);
    },
    undoDropDownChange : function(transaction){
        SimpleList.setDropDownValue(transaction['id'], transaction['old'], false);
    },
    redoDropDownChange : function(transaction){
        SimpleList.setDropDownValue(transaction['id'], transaction['new'], false);
    },
    undo : function(){
        SimpleList.jstransaction.undo();
    },
    redo : function(){
        SimpleList.jstransaction.redo();
    }
}//return
}();
}
