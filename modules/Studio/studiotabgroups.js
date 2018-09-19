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

// $Id: studiotabgroups.js 18703 2006-12-15 09:42:43Z majed $
var subtabCount = [];
var subtabModules = [];
var tabLabelToValue = [];
StudioTabGroup = function(){
	this.lastEditTabGroupLabel = -1;
};


StudioTabGroup.prototype.editTabGroupLabel = function (id, done){
	if(!done){
		if(this.lastEditTabGroupLabel != -1)StudioTabGroup.prototype.editTabGroupLabel(this.lastEditTabGroupLabel, true);
		document.getElementById('tabname_'+id).style.display = 'none';
		document.getElementById('tablabel_'+id).style.display = '';
		document.getElementById('tabother_'+id).style.display = 'none';
		//#28274, I think this is a simple way when a element can't accept focus()
		try{
			document.getElementById('tablabel_'+id).focus();
		}
		catch(er){
			//TODO
		}
		this.lastEditTabGroupLabel = id;
		YAHOO.util.DragDropMgr.lock();
	}else{
		this.lastEditTabGroupLabel = -1;
		document.getElementById('tabname_'+id).innerHTML = escape(document.getElementById('tablabel_'+id).value);
		document.getElementById('tabname_'+id).style.display = '';
		document.getElementById('tablabel_'+id).style.display = 'none';
		document.getElementById('tabother_'+id).style.display = '';
		YAHOO.util.DragDropMgr.unlock();
	}
}

 StudioTabGroup.prototype.generateForm = function(formname){
  	var form = document.getElementById(formname);
  	for(var j = 0; j < slotCount; j++){
		var ul = document.getElementById('ul' + j);
		var items = ul.getElementsByTagName('li');
		for(var i = 0; i < items.length; i++) {
		    if(typeof(subtabModules[items[i].id]) != 'undefined'){
			
				var input = document.createElement('input');
				input.type='hidden';
				input.name= j + '_'+ i;
				input.value = tabLabelToValue[subtabModules[items[i].id]];
				form.appendChild(input);
			}
		}
    }
	//set the slotcount in the form.
	form.slot_count.value = slotCount;
};

 StudioTabGroup.prototype.generateGroupForm = function(formname){
		  	var form = document.getElementById(formname);
		  	for(j = 0; j < slotCount; j++){
				var ul = document.getElementById('ul' + j);
				items = ul.getElementsByTagName('li');
				for(i = 0; i < items.length; i++) {
				if(typeof(subtabModules[items[i].id]) != 'undefined'){
					var input = document.createElement('input');
					input.type='hidden'
					input.name= 'group_'+ j + '[]';
					input.value = tabLabelToValue[subtabModules[items[i].id]];
					form.appendChild(input);
				}
				}
		  }
		  };

StudioTabGroup.prototype.deleteTabGroup = function(id){
		if(document.getElementById('delete_' + id).value == 0){
			document.getElementById('ul' + id).style.display = 'none';
			document.getElementById('tabname_'+id).style.textDecoration = 'line-through'
			document.getElementById('delete_' + id).value = 1;
		}else{
			document.getElementById('ul' + id).style.display = '';
			document.getElementById('tabname_'+id).style.textDecoration = 'none'
			document.getElementById('delete_' + id).value = 0;
		}
	}	


var lastField = '';
			var lastRowCount = -1;
			var undoDeleteDropDown = function(transaction){
			    deleteDropDownValue(transaction['row'], document.getElementById(transaction['id']), false);
			}
			jstransaction.register('deleteDropDown', undoDeleteDropDown, undoDeleteDropDown);
			function deleteDropDownValue(rowCount, field, record){
			    if(record){
			        jstransaction.record('deleteDropDown',{'row':rowCount, 'id': field.id });
			    }
			    //We are deleting if the value is 0
			    if(field.value == '0'){
			        field.value = '1';
			        document.getElementById('slot' + rowCount + '_value').style.textDecoration = 'line-through';
			    }else{
			        field.value = '0';
			        document.getElementById('slot' + rowCount + '_value').style.textDecoration = 'none';
			    }
			    
			   
			}
var studiotabs = new StudioTabGroup();