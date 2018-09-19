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
{literal}
<script>
if(typeof TrackerDashlet == 'undefined') { // since the dashlet can be included multiple times a page, don't redefine these functions

function TrackerDashlet() {

   function queryReturned(data) {
        eval ('result = '+data.responseText);
       	ajaxStatus.showStatus('{/literal}{$saved}{literal}');
       	if(typeof result != 'undefined') {
			var colModel = new Array();
			var data = new Array();
			var storeFields = new Array();
			
			for (var x = 0; x < result['col_headers'].length; x++) {
			    col_header = result['col_headers'][x];
			    sort_type = (typeof result['sort_types'][col_header] != 'undefined') ? result['sort_types'][col_header] : 'asString';
				storeFields[x] = col_header;
				colModel[x] = {key: col_header, 
				               label: result['col_labels'][x], 
				               width: result['col_widths'][x], 
				               sortable: true};
			}
			
			for (var x = 0; x < result['data'].length; x++) {
				 var row = new Array();
				 for (var r = 0; r < result['col_headers'].length; r++){
				 	row[r] = result['data'][x][result['col_headers'][r]];				 	
				 }
				 data[x] = row;
			}
		} //if
		
        _store = new YAHOO.util.LocalDataSource(data);
        _store.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
        _store.responseSchema = { 
            fields: storeFields
        }; 
		
    	_grid.destroy();
    	
	    _grid = new YAHOO.widget.ScrollingDataTable('tracker_grid_' + _id, colModel, _store, {
    	    stripeRows: true,
	        height: _height,
	        width:600,
	        MSG_EMPTY: SUGAR.language.get('app_strings', 'LBL_EMAIL_SEARCH_NO_RESULTS')
	    });
		_grid.render();
	      
       	window.setTimeout('ajaxStatus.hideStatus()', 2000);   
   } //queryReturned


   function comboChanged() {
        func = _comboQuery.options[_comboQuery.selectedIndex].value;
        var dateDependentQueries = {/literal}{$dateDependentQueries}{literal};
        setDisabled = true;
        var setDisplayed = 'none';
        for(j = 0; j < dateDependentQueries.length; j++){
        	if(func == dateDependentQueries[j]){
        		setDisabled = false;
                setDisplayed= 'inline';
        		break;
        	}
        }
        _dateButton.disabled = setDisabled;
        _dateTextField.disabled = setDisabled;
        _clearButton.disabled = setDisabled;
        _runButton.disabled = setDisabled;

        _sinceLabel.style.display = setDisplayed;
        _dateButton.style.display = setDisplayed;
        _dateTextField.style.display = setDisplayed;
        _clearButton.style.display = setDisplayed;
        _runButton.style.display = setDisplayed;
        runClicked();
   } //comboChanged


   function runClicked() {
    	var queryMethod = _comboQuery.options[_comboQuery.selectedIndex].value;
    	var datePicked = _dateTextField.value;
    	if(queryMethod){
    	   runQuery(queryMethod, '{/literal}{$id}{literal}', datePicked);
    	}
   } //runClicked


   function runQuery(method, id, datePicked) {
    	ajaxStatus.showStatus('{/literal}{$saving}{literal}'); // show that AJAX call is happening
    	// what data to post to the dashlet
    	postData = 'to_pdf=1&module=Home&action=CallMethodDashlet&method='+method+'&id=' + id+'&date_selected='+datePicked;
		var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php', {success: queryReturned, failure: queryReturned}, postData);
   }


   function clearDateField() {
	    _dateTextField.value = '';
	    _dateTextField.focus();
   }
   
   function init(id, height) {

	        _id = id;

	   	    _store = new YAHOO.util.LocalDataSource({fields: []});

		    // create the Grid
		    if(!height){
		    	height = 300;
		    }

		    var calenderImagepath = "{/literal}{sugar_getimagepath file='Calendar.gif'}{literal}";
		    var clearButtonLabel = "{/literal}{$clearLbl}{literal}";
		    var runButtonLabel = "{/literal}{$runLbl}{literal}";
		    var sincelabel = "{/literal}{$sinceLbl}{literal}";
		    _height = height;
		    comboData = {/literal}{$comboData}{literal};
		    var options = "";
		    for (i = 0 ; i < comboData.length ; i++) {
		    	var entry = comboData[i];
		    	options = options + "<option value='" + entry[1] + "'>" + entry[0] + "</option>";
		    }
		    var trackerContentHeader = "<select onchange='tracker_dashlet.comboChanged();' id=\"" + "combo_" + _id + "\">" + options + "</select>";
		    trackerContentHeader = trackerContentHeader + '<span id="sinceLabel_'+_id+'">'+sincelabel+'</span>';
		    trackerContentHeader = trackerContentHeader + " <input type='text' name='text_" + _id + "' id='text_" + _id + "'>";
		    trackerContentHeader = trackerContentHeader + "&nbsp;<input class='button' id='calbutton_" +  _id + "' type='button' style='width:20px;height:18px;background-image:url(" + calenderImagepath + ")'>";
		    trackerContentHeader = trackerContentHeader + "&nbsp;<input id='runbutton_" +  _id + "' class='button' type='button' onclick='tracker_dashlet.runClicked();' name='" + runButtonLabel + "'" +  " value='" + runButtonLabel + "'>";
		    trackerContentHeader = trackerContentHeader + "&nbsp;<input id='clearbutton_" +  _id + "' class='button' type='button' onclick='tracker_dashlet.clearDateField();' name='" + clearButtonLabel + "'" + "value='" + clearButtonLabel + "'>";
		    
		    var moduleId = "trackercontent_div_" + _id;
			var trackerContent = new YAHOO.widget.Module(moduleId, { visible: false });
			var trackerContentPanelHTML = "<div class='ytheme-gray' id=\"tracker_grid_" + _id + "\"></div>";
			trackerContent.setHeader(trackerContentHeader);
			trackerContent.setBody(trackerContentPanelHTML);
			trackerContent.render();
			trackerContent.show();
		    
		    Calendar.setup ({
				inputField : "text_" + _id,
				daFormat : "%Y-%m-%d %H:%M",
				button : "calbutton_" + _id,
				singleClick : true,
				dateStr : "",
				step : 1,
				weekNumbers:false
				}
			);
			
		    _grid = new YAHOO.widget.ScrollingDataTable('tracker_grid_' + _id, [ ], _store, {
	    	    stripeRows: true,
		        height: height,
		        width:600,
		        MSG_EMPTY: SUGAR.language.get('app_strings', 'LBL_EMAIL_SEARCH_NO_RESULTS')
		    });
		    
		    _comboQuery = document.getElementById("combo_" + _id);
            _sinceLabel = document.getElementById("sinceLabel_" + _id);
            _sinceLabel.style.display = 'none';
		    _dateButton = document.getElementById("calbutton_" + _id);
		    _dateButton.disabled = true;
            _dateButton.style.display = 'none';
		    _dateTextField = document.getElementById("text_" + _id);
		    _dateTextField.disabled = true;
            _dateTextField.style.display = 'none';
		    _runButton = document.getElementById("runbutton_" + _id);
		    _clearButton = document.getElementById("clearbutton_" + _id);
		    _clearButton.disabled = true;
            _clearButton.style.display = 'none';
			_grid.render();
   } //init

   var _id;
   var _grid;
   var _store;
   var _comboQuery;
   var _dateTextField;
   var _dateButton;
   var _dateTextField;
   var _clearButton;
   var _runButton;
   var _height;
   
   this.init = init;
   this.comboChanged = comboChanged;
   this.clearDateField = clearDateField;
   this.runClicked = runClicked;
   this.runQuery = runQuery;
} //TrackerDashlet

} //if
</script>
{/literal}
