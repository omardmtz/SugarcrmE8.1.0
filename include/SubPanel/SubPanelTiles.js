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

// $Id: SubPanelTiles.js 56952 2010-06-14 21:44:26Z sadek $

var request_id = 0;
var current_child_field = '';
var current_subpanel_url = '';
var child_field_loaded = new Object();
var request_map = new Object();

function get_module_name()
{
	if(typeof(window.document.forms['DetailView']) == 'undefined') {
		return '';
	} else {

		//check to see if subpanel_parent_module input exists.  If so override module name
		//this is used in the case when the subpanel contents are of the same module as the current module
		//and the record in $_REQUEST is of the parent object.  By specifying the subpanel_parent_module,
		//you allow normal processing to continue.  For an example, see trackdetailview.html/php in campaigns module
		if(typeof(window.document.forms['DetailView'].elements['subpanel_parent_module']) != 'undefined' &&
		window.document.forms['DetailView'].elements['subpanel_parent_module'].value != ''){
			return window.document.forms['DetailView'].elements['subpanel_parent_module'].value;
		}
		return window.document.forms['DetailView'].elements['module'].value;
	}
}
/*this function will take in three parameters, m,i,a and recreate navigation
* m = module
* i = record id
* a = action (detail/edit)
* t = element to be modified
* r = relationship to update after edit.
* This is done to minimize page size
* */
function subp_nav(m,i,a,t,r){
    //TODO: This function should be removed when bwc is phased out
    var url, app = window.parent.SUGAR.App;

    //BWC: Preseve legacy way if the "go to" module is a bwc; ignores sidecar
    //modules which get handled later by subp_nav_sidecar function  below
    if (app.metadata.getModule(m).isBwcEnabled) {
        if (t.href.search(/#/) < 0) {
            //no need to process if url has already been converted
            return;
        }
        if (a=='d') {
            a='DetailView';
        } else {
            a='EditView';
        }
        url = "index.php?module="+m+"&action="+a+"&record="+i+"&parent_module="+get_module_name()+"&parent_id="+get_record_id()+"&return_module="+get_module_name()+"&return_id="+get_record_id()+"&return_action=DetailView";
        if (r) {
            url += "&return_relationship=" + r;
        }
        t.href = url;
    }
}

/*
* m = module
* i = record id; when action is 'c' it's the related's parent id
* a = action (create/detail/edit abbreviated by single character e.g. 'c')
* link = relationship link name.
* */
function subp_nav_sidecar(m, i, a, link) {
    var app = parent.SUGAR.App,
        view = app.controller.layout.getComponent('bwc'),
        url;
    if (!app.metadata.getModule(m).isBwcEnabled) {

        //action is create
        if (a === 'c') {
            view.createRelatedRecord(m, link);
            return false;
        }
        //action is not create
        url = view.convertToSidecarUrl('index.php?module=' + m + '&action=' + '&record=' + i);
        app.router.navigate(url, {trigger: true});
        return false;
    }
}

/**
 * Launches the Archive Email drawer
 */
function subp_archive_email() {
    var app = parent.SUGAR.App,
        view = app.controller.layout.getComponent('bwc');

    view.openArchiveEmailDrawer();
    return false;
}

/**
 * Builds a link for relationship deleting and navigates to this link
 * @param  {String} module Module name
 * @param  {String} action Action name
 * @param  {Object} params Params to be included in a url
 */
function relationship_remove(module, action, params) {
    var id = get_record_id();
    var params = _.extend(params, {
        record: id,
        return_id: id
    });

    var route = app.bwc.buildRoute(module, id || null, action, params);

    if (!_.isUndefined(params.return_url)) {
        route += params.return_url;
    }

    app.router.navigate("#" + route, {trigger: true});
}

/*this function will take in three parameters, m,i,a and recreate navigation
* m = module
* i = record id
* a = action (detail/edit)
* This is done to minimize page size
* */
function sub_p_rem(sp,lf,li,rp){

	return_url = "index.php?module="+get_module_name()+"&action=SubPanelViewer&subpanel="+sp+"&record="+get_record_id()+"&sugar_body_only=1&inline=1";

	remove_url = "index.php?module="+ get_module_name()
			+ "&action=DeleteRelationship"
			+ "&record="+ get_record_id()
			+ "&linked_field="+ lf  //$linked_field"
			+ "&linked_id="+ li //$record"
			+ "&return_url=" + escape(escape(return_url))
			+ "&refresh_page=" + rp;//$refresh_page"
	showSubPanel(sp,remove_url,true);
}

function sp_rem_conf(){
	return confirm(SUGAR.language.get('app_strings', 'NTC_REMOVE_CONFIRMATION'))
}

function sub_p_del(sp,submod,subrec, rp){
	return_url = "index.php?module="+get_module_name()+"&action=SubPanelViewer&subpanel="+sp+"&record="+get_record_id()+"&sugar_body_only=1&inline=1";

	remove_url = "index.php?module="+ submod
			+ "&action=delete"
			+ "&record="+ subrec
			+ "&return_url=" + escape(escape(return_url))
			+ "&refresh_page=" + rp;//$refresh_page"
	showSubPanel(sp,remove_url,true);
}

function sp_del_conf(){
	return confirm(SUGAR.language.get('app_strings', 'NTC_DELETE_CONFIRMATION'))
}

function get_record_id()
{
	return window.document.forms['DetailView'].elements['record'].value;
}

function get_layout_def_key()
{
	if(typeof(window.document.forms['DetailView'].elements['layout_def_key']) == 'undefined')return '';
	return window.document.forms['DetailView'].elements['layout_def_key'].value;
}

function save_finished(args)
{
	var child_field = request_map[args.request_id];
	delete (child_field_loaded[child_field] );
	showSubPanel(child_field);
}

function set_return_and_save_background(popup_reply_data)
{
	var form_name = popup_reply_data.form_name;
	var name_to_value_array = popup_reply_data.name_to_value_array;
	var passthru_data = popup_reply_data.passthru_data;
	var select_entire_list = typeof( popup_reply_data.select_entire_list ) == 'undefined' ? 0 : popup_reply_data.select_entire_list;
	var current_query_by_page = popup_reply_data.current_query_by_page;
	// construct the POST request
	var query_array =  new Array();
	if (name_to_value_array != 'undefined') {
		for (var the_key in name_to_value_array)
		{
			if(the_key == 'toJSON')
			{
				/* just ignore */
			}
			else
			{
				query_array.push(the_key+"="+name_to_value_array[the_key]);
			}
		}
	}
  	//construct the muulti select list
	var selection_list = popup_reply_data.selection_list;
	if (selection_list != 'undefined') {
		for (var the_key in selection_list)
		{
			query_array.push('subpanel_id[]='+selection_list[the_key])
		}
	}
	var module = get_module_name();
	var id = get_record_id();

    query_array.push('csrf_token=' + SUGAR.csrf.form_token);
	query_array.push('value=DetailView');
	query_array.push('module='+module);
	query_array.push('http_method=get');
	query_array.push('return_module='+module);
	query_array.push('return_id='+id);
	query_array.push('record='+id);
	query_array.push('isDuplicate=false');
	query_array.push('action=Save2');
	query_array.push('inline=1');
	query_array.push('select_entire_list='+select_entire_list);
	if(select_entire_list == 1){
		query_array.push('current_query_by_page='+current_query_by_page);
	}
	var refresh_page = escape(passthru_data['refresh_page']);
	for (prop in passthru_data) {
		if (prop=='link_field_name') {
			query_array.push('subpanel_field_name='+escape(passthru_data[prop]));
		} else {
			if (prop=='module_name') {
				query_array.push('subpanel_module_name='+escape(passthru_data[prop]));
			} else if(prop == 'prospect_ids'){
				for(var i=0;i<passthru_data[prop].length;i++){
					query_array.push(prop + '[]=' + escape(passthru_data[prop][i]));
				}
			} else {
				query_array.push(prop+'='+escape(passthru_data[prop]));
			}
		}
	}

	var query_string = query_array.join('&');
	request_map[request_id] = passthru_data['child_field'];

	var returnstuff = http_fetch_sync('index.php',query_string);
	request_id++;

	// Bug 52843
	// If returnstuff.responseText is empty, don't process, because it will blank the innerHTML
	if (typeof returnstuff != 'undefined' && typeof returnstuff.responseText != 'undefined' && returnstuff.responseText.length != 0) {
		got_data(returnstuff, true);
	}
 	if(refresh_page == 1){
 		document.location.reload(true);
 	}
}

function got_data(args, inline)
{

	var list_subpanel = document.getElementById('list_subpanel_'+request_map[args.request_id].toLowerCase());
	//this function assumes that we are always working with a subpanel..
	//add a null check to prevent failures when we are not.
	if (list_subpanel != null) {
		var subpanel = document.getElementById('subpanel_'+request_map[args.request_id].toLowerCase());
		var child_field = request_map[args.request_id].toLowerCase();
        var bwcComponent = window.parent.SUGAR.App.controller.layout.getComponent('bwc');

        if(inline){
            if (bwcComponent) {
                bwcComponent.confirmMemLeak(list_subpanel);
                $('a', list_subpanel).off('.bwc.sugarcrm');
            }

			child_field_loaded[child_field] = 2;
			list_subpanel.innerHTML='';
			list_subpanel.innerHTML=args.responseText;

		} else {
            if (bwcComponent) {
                bwcComponent.confirmMemLeak(subpanel);
                $('a', subpanel).off('.bwc.sugarcrm');
            }

			child_field_loaded[child_field] = 1;
			subpanel.innerHTML='';
			subpanel.innerHTML=args.responseText;

			/* walk into the DOM and insert the list_subpanel_* div */
			var inlineTable = subpanel.getElementsByTagName('table');
			inlineTable = inlineTable[1];
			inlineTable = subpanel.removeChild(inlineTable);
			var listDiv = document.createElement('div');
			listDiv.id = 'list_subpanel_'+request_map[args.request_id].toLowerCase();
			subpanel.appendChild(listDiv);
			listDiv.appendChild(inlineTable);
		}
        SUGAR.util.evalScript(args.responseText);
		subpanel.style.display = '';
		set_div_cookie(subpanel.cookie_name, '');

		current_child_field = child_field;
		//reinit action menus
		$("ul.clickMenu").each(function(index, node){
	  		$(node).sugarActionMenu();
	  	});

       if (bwcComponent) {
           bwcComponent.rewriteLinks();
       }
	}
}

function showSubPanel(child_field,url,force_load,layout_def_key)
{
	var inline = 1;
	if ( typeof(force_load) == 'undefined' || force_load == null)
	{
		force_load = false;
	}

	if (force_load || typeof( child_field_loaded[child_field] ) == 'undefined')
	{
		request_map[request_id] = child_field;
		if ( typeof (url) == 'undefined' || url == null)
		{
			var module = get_module_name();
			var id = get_record_id();
            if ( typeof(layout_def_key) == 'undefined' || layout_def_key == null ) {
                layout_def_key = get_layout_def_key();
            }

			url = 'index.php?sugar_body_only=1&module='+module+'&subpanel='+child_field+'&action=SubPanelViewer&inline=' + inline + '&record='+id + '&layout_def_key='+ layout_def_key;
		}

		if ( url.indexOf('http://') != 0  && url.indexOf('https://') != 0)
		{
			url = ''+url ;
		}

		current_subpanel_url = url;
		var returnstuff = http_fetch_sync(url+ '&inline=' + inline + '&ajaxSubpanel=true');
		request_id++;
		got_data(returnstuff, inline);
	}
	else
	{
		var subpanel = document.getElementById('subpanel_'+child_field);
		subpanel.style.display = '';

		set_div_cookie(subpanel.cookie_name, '');

		if (current_child_field != '' && child_field != current_child_field)
		{
			hideSubPanel(current_child_field);
		}

		current_child_field = child_field;
	}
	if(typeof(url) != 'undefined' && url != null && url.indexOf('refresh_page=1') > 0){
		document.location.reload();
	}

}

function markSubPanelLoaded(child_field){
	child_field_loaded[child_field] = 2;
}
function hideSubPanel(child_field)
{
	var subpanel = document.getElementById('subpanel_'+child_field);
	subpanel.style.display = 'none';
	set_div_cookie(subpanel.cookie_name, 'none');
}
var sub_cookie_name = get_module_name() + '_divs';
var temp = Get_Cookie(sub_cookie_name);
var div_cookies = new Array();

if(temp && typeof(temp) != 'undefined'){
	div_cookies = get_sub_cookies(temp);
}
function set_div_cookie(name, display){
	div_cookies[name] = display;
	Set_Cookie(sub_cookie_name, subs_to_cookie(div_cookies), 3000, false, false,false);
}


function local_open_popup(name, width, height,arg1, arg2, arg3, params)
{
	return open_popup(name, width, height,arg1,arg2,arg3, params);
}

SUGAR.subpanelUtils = function() {
	var originalLayout = null,
        subpanelContents = {},
        subpanelLocked = {},

        // Keeps track of the current subpanel id
        currentPanelDiv;

	return {
		// get the current subpanel layout
		getLayout: function(asString, ignoreHidden) {
		    subpanels = document.getElementById('subpanel_list');
		    subpanelIds = new Array();
		    for(wp = 0; wp < subpanels.childNodes.length; wp++) {
		      if(typeof subpanels.childNodes[wp].id != 'undefined' && subpanels.childNodes[wp].id.match(/whole_subpanel_[\w-]*/) && (typeof ignoreHidden == 'undefined' || subpanels.childNodes[wp].style.display != 'none')) {
				subpanelIds.push(subpanels.childNodes[wp].id.replace(/whole_subpanel_/,''));
		      }
		    }
			if(asString) return subpanelIds.join(',');
			else return subpanelIds;
		},

		// called when subpanel is picked up
		onDrag: function(e, id) {
			originalLayout = SUGAR.subpanelUtils.getLayout(true, true);
		},

		// called when subpanel is dropped
		onDrop: function(e, id) {
			newLayout = SUGAR.subpanelUtils.getLayout(true, true);
		  	if(originalLayout != newLayout) { // only save if the layout has changed
				SUGAR.subpanelUtils.saveLayout(newLayout);
		  	}
		},

		// save the layout of the subpanels
		saveLayout: function(order) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING_LAYOUT'));

			if(typeof SUGAR.subpanelUtils.currentSubpanelGroup != 'undefined') {
				var orderList = SUGAR.subpanelUtils.getLayout(false, true);
				var currentGroup = SUGAR.subpanelUtils.currentSubpanelGroup;
			}
			var success = function(data) {
				ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVED_LAYOUT'));
				window.setTimeout('ajaxStatus.hideStatus()', 2000);
				if(typeof SUGAR.subpanelUtils.currentSubpanelGroup != 'undefined') {
					SUGAR.subpanelUtils.reorderSubpanelSubtabs(currentGroup, orderList);
				}
			}

			url = 'index.php?module=Home&action=SaveSubpanelLayout&layout=' + order + '&layoutModule=' + currentModule;
			if(typeof SUGAR.subpanelUtils.currentSubpanelGroup != 'undefined') {
				url = url + '&layoutGroup=' + encodeURI(SUGAR.subpanelUtils.currentSubpanelGroup);
			}
			var cObj = YAHOO.util.Connect.asyncRequest('GET', url, {success: success, failure: success});
		},

        /**
         * Call when an inline create is saved.
         * Note: We require the subpanel name to refresh the subpanel contents and
         * to close the subpanel after the save. However, the code the generates the
         * button doesn't have access to the subpanel name, only the module name.
         * Hence this rather long-winded mechanism.
         * @param theForm
         * @param buttonName id of the originating 'save' button - we determine the
         *          associated subpanel name by climbing the DOM from this point
         */
		inlineSave: function(theForm, buttonName) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING'));
			var success = function(data) {
                var module = get_module_name();
                var id = get_record_id();
                var layout_def_key = get_layout_def_key();
                var result = data.responseText;
                if (typeof(result) != 'undefined' && result != null && result['status'] == 'dupe') {
                    document.location.href = "index.php?" + result['get'].replace(/&amp;/gi,'&').replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&#039;/gi,'\'').replace(/&quot;/gi,'"').replace(/\r\n/gi,'\n');
                    return;
                } else {
                    SUGAR.subpanelUtils.cancelCreate(buttonName);
                    // parse edit form name in order to get the name of
                    // module which saved item belongs to
                    var savedModule = theForm.replace(/^([^_]+_){2}/, "");
                    if (window.ModuleSubPanels && window.ModuleSubPanels[savedModule]) {
                        var subPanels = window.ModuleSubPanels[savedModule];
                        // reload all sub-panels that may contain the bean
                        // has been edited
                        for (var i = 0; i < subPanels.length; i++) {
                            showSubPanel(subPanels[i], null, true);
                        }
                    }
                    ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVED'));
                    window.setTimeout('ajaxStatus.hideStatus()', 1000);
                }
			}

            YAHOO.util.Connect.setForm(theForm, true, true);
			var cObj = YAHOO.util.Connect.asyncRequest('POST', 'index.php', {success: success, failure: success, upload:success});
			return false;
		},

        /**
         * Retrieves the subpanel form.
         * Note: We only allow one subpanel form to be open at any given time
         * because some of the form widgets interfere with each other.
         * @param theForm
         * @param theDiv
         * @param loadingStr
         */
		sendAndRetrieve: function(theForm, theDiv, loadingStr) {
            // look whether a quick create form is currently opened
            var quickCreateDiv = YAHOO.util.Selector.query("div.quickcreate", null, true);
            if (quickCreateDiv)
            {
                var form = YAHOO.util.Selector.query("form", quickCreateDiv, true);
                if (form)
                {
                    // discover cancelCreate function parameters needed
                    var moduleName = form.id.replace(/.*?_([^_]+)$/, "$1");
                    var buttonName = moduleName + "_subpanel_cancel_button";
                    var cancelled  = false;

                    // try to cancel form submission
                    SUGAR.subpanelUtils.cancelCreate(buttonName, function()
                    {
                        cancelled = true;
                    });

                    // if submission cancellation was cancelled, do nothing
                    if (cancelled)
                    {
                        return false;
                    }
                }
            }

			function success(data) {
				var theDivObj = document.getElementById(theDiv),
                    divName = theDiv + '_newDiv',
                    form_el;
                SUGAR.subpanelUtils.dataToDOMAvail = false;

                // Show buttons before we remove subpanel
                if (typeof currentPanelDiv != 'undefined' && currentPanelDiv != null) {
                    var button_elements = YAHOO.util.Selector.query('td.buttons', currentPanelDiv, false);
                    YAHOO.util.Dom.setStyle(button_elements, 'display', '');
                }
                // Check if preview subpanel form exists, remove if it does.
                SUGAR.subpanelUtils.removeSubPanel();

				subpanelContents[theDiv] = {};
				subpanelContents[theDiv]['list'] = theDivObj;
				subpanelContents[theDiv]['newDiv'] = document.createElement('div');
				subpanelContents[theDiv]['newDiv'].innerHTML = '<script type="text/javascript">SUGAR.subpanelUtils.dataToDOMAvail=true;</script>' + data.responseText;
				subpanelContents[theDiv]['newDiv'].id = divName;
				subpanelContents[theDiv]['newDiv'].className = 'quickcreate';

				// Grab the buttons from the subpanel and hide them
				var button_elements = YAHOO.util.Selector.query('td.buttons', theDiv, false);
				YAHOO.util.Dom.setStyle(button_elements, 'display', 'none');
				button_elements = YAHOO.util.Selector.query('ul.SugarActionMenu', theDiv, false);
				YAHOO.util.Dom.setStyle(button_elements, 'display', 'none');

                // Add the form object to the DOM
				theDivObj.parentNode.insertBefore(subpanelContents[theDiv]['newDiv'], theDivObj);
                currentPanelDiv = divName;

                if (!SUGAR.subpanelUtils.dataToDOMAvail) {
					SUGAR.util.evalScript(data.responseText);
				}

				form_el = YAHOO.util.Selector.query('form', divName, true);
                YAHOO.util.Dom.setStyle(form_el, 'padding-bottom', '10px');

                subpanelLocked[theDiv] = false;
                setTimeout("enableQS(false)",500);
				ajaxStatus.hideStatus();
			}

			if (subpanelLocked[theDiv] === true) {
                return false;
            }

			subpanelLocked[theDiv] = true;

			loadingStr = loadingStr || SUGAR.language.get('app_strings', 'LBL_LOADING');
			ajaxStatus.showStatus(loadingStr);
			YAHOO.util.Connect.setForm(theForm);
			YAHOO.util.Connect.asyncRequest('POST', 'index.php', {success: success, failure: success});

			return false;
		},

        // as long as formerly the function used to be always returning false,
        // there was no possibility to determine, was the creation cancelled or not.
        // we couldn't modify function return value in case of user cancels the
        // cancellation as long as it (false) is used in multiple places to
        // prevent DOM event propagation. thus, cancelCallback optional
        // parameter is added to be able to track this case
		cancelCreate: function(buttonName, cancelCallback) {
			var element = document.getElementById(buttonName);

			do {
				element = element.parentNode;
			} while ( element.className != 'quickcreate' && element.parentNode ) ;

			var theDiv = element.id.substr(0,element.id.length-7);

			if (typeof(subpanelContents[theDiv]) == 'undefined')
                return false;

            if ("function" === typeof cancelCallback)
            {
                cancelCallback();
            }

            SUGAR.subpanelUtils.removeSubPanel();
            var button_elements = YAHOO.util.Selector.query('td.buttons', theDiv, false);
            YAHOO.util.Dom.setStyle(button_elements, 'display', '');
            button_elements = YAHOO.util.Selector.query('ul.SugarActionMenu', theDiv, false);
            YAHOO.util.Dom.setStyle(button_elements, 'display', '');

			return false;
		},

		loadSubpanelGroupFromMore: function(group){
			SUGAR.subpanelUtils.updateSubpanelMoreTab(group);
			SUGAR.subpanelUtils.loadSubpanelGroup(group);
		},

		updateSubpanelMoreTab: function(group){
			// Update Tab
			var moreTab = document.getElementById(SUGAR.subpanelUtils.subpanelMoreTab + '_sp_tab');
			moreTab.id = group + '_sp_tab';
			moreTab.getElementsByTagName('a')[0].innerHTML = group;
			moreTab.getElementsByTagName('a')[0].href = "javascript:SUGAR.subpanelUtils.loadSubpanelGroup('"+group+"');";

			// Update Menu
			var menuLink = document.getElementById(group+'_sp_mm');
			menuLink.id = SUGAR.subpanelUtils.subpanelMoreTab+'_sp_mm';
			menuLink.href = "javascript:SUGAR.subpanelUtils.loadSubpanelGroupFromMore('"+SUGAR.subpanelUtils.subpanelMoreTab+"');";
			menuLink.innerHTML = SUGAR.subpanelUtils.subpanelMoreTab;

			SUGAR.subpanelUtils.subpanelMoreTab = group;
		},

        /**
         * Removes the current subpanel if it exists.
         */
        removeSubPanel: function() {
            var currentPanelEl = null;
            if (typeof currentPanelDiv != 'undefined' && currentPanelDiv != null) {
                currentPanelEl = document.getElementById(currentPanelDiv);
            }

            if (currentPanelEl != null) {
                currentPanelEl.parentNode.removeChild(currentPanelEl);
                // TODO review these SUGAR.ajaxUI.* methods
                SUGAR.ajaxUI.cleanGlobals();
                currentPanelDiv = null;
            }
        },

		/* loadSubpanels:
		/* construct set of needed subpanels */
		/* if we have not yet loaded this subpanel group, */
		/*     set loadedGroups[group] */
		/*     for each subpanel in subpanelGroups[group] */
		/*         if document.getElementById('whole_subpanel_'+subpanel) doesn't exist */
		/*         then add subpanel to set of needed subpanels */
		/*     if we need to load any subpanels, send a request for them */
		/*	      with updateSubpanels as the callback. */
		/* otherwise call updateSubpanels */
		/* call setGroupCookie */

		loadSubpanelGroup: function(group){
			if(group == SUGAR.subpanelUtils.currentSubpanelGroup) return;

			if(SUGAR.subpanelUtils.loadedGroups[group]){
				SUGAR.subpanelUtils.updateSubpanel(group);
			}else{

				SUGAR.subpanelUtils.loadedGroups.push(group);
				var needed = [];
				for(group_sp in SUGAR.subpanelUtils.subpanelGroups[group]){
					if(typeof(SUGAR.subpanelUtils.subpanelGroups[group][group_sp]) == 'string' && !document.getElementById('whole_subpanel_'+SUGAR.subpanelUtils.subpanelGroups[group][group_sp])){
						needed.push(SUGAR.subpanelUtils.subpanelGroups[group][group_sp]);
					}
				}
				var success = function(){
					SUGAR.subpanelUtils.updateSubpanelEventHandlers(needed);
					SUGAR.subpanelUtils.updateSubpanels(group);
				};
				/* needed to retrieve each of the specified subpanels and install them ...*/
				/* load them in bulk, insert via innerHTML, then sort nodes later. */
				if(needed.length){

					ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
					SUGAR.util.retrieveAndFill(SUGAR.subpanelUtils.requestUrl + needed.join(','),'subpanel_list', null, success, null, true);
				}else{
					SUGAR.subpanelUtils.updateSubpanels(group);
				}
			}
			SUGAR.subpanelUtils.setGroupCookie(group);
		},

		/* updateSubpanels:
		/* for each child node of subpanel_list */
		/*     let subpanel name be id.match(/whole_subpanel_(\w*)/) */
		/*     if the subpanel name is in the list of subpanels for the current group, show it */
		/*     otherwise hide it */
		/* swap nodes to suit user's order */
		/* call updateSubpanelTabs */

		updateSubpanels: function(group){
			var sp_list = document.getElementById('subpanel_list');
			for(sp in sp_list.childNodes){
				if(sp_list.childNodes[sp].id){
					sp_list.childNodes[sp].style.display = 'none';
				}
			}

			for(group_sp in SUGAR.subpanelUtils.subpanelGroups[group]){
                if ( typeof(SUGAR.subpanelUtils.subpanelGroups[group][group_sp]) != 'string' )
                {
                    continue;
                }
				var cur = document.getElementById('whole_subpanel_'+SUGAR.subpanelUtils.subpanelGroups[group][group_sp]);

                if(cur == null)
                {
                    continue;
                }

                cur.style.display = 'block';

			}

			SUGAR.subpanelUtils.updateSubpanelTabs(group);
		},

		updateSubpanelTabs: function(group){
			if(SUGAR.subpanelUtils.showLinks){
				SUGAR.subpanelUtils.updateSubpanelSubtabs(group);
				document.getElementById('subpanelSubTabs').innerHTML = SUGAR.subpanelUtils.subpanelSubTabs[group];
			}

			oldTab = document.getElementById(SUGAR.subpanelUtils.currentSubpanelGroup+'_sp_tab');
			if(oldTab){
				oldTab.className = '';
				oldTab.getElementsByTagName('a')[0].className = '';
			}

			mainTab = document.getElementById(group+'_sp_tab');
			mainTab.className = 'active';
			mainTab.getElementsByTagName('a')[0].className = 'current';

			SUGAR.subpanelUtils.currentSubpanelGroup = group;
			ajaxStatus.hideStatus();
		},

		updateSubpanelEventHandlers: function(){
			if(SubpanelInitTabNames){
				SubpanelInitTabNames(SUGAR.subpanelUtils.getLayout(false));
			}
		},

		reorderSubpanelSubtabs: function(group, order){
			SUGAR.subpanelUtils.subpanelGroups[group] = order;
			if(SUGAR.subpanelUtils.showLinks==1){
				SUGAR.subpanelUtils.updateSubpanelSubtabs(group);
				if(SUGAR.subpanelUtils.currentSubpanelGroup == group){
					document.getElementById('subpanelSubTabs').innerHTML = SUGAR.subpanelUtils.subpanelSubTabs[group];
				}
			}
		},

		// Re-renders the contents of subpanelSubTabs[group].
		// Does not immediately affect what's on the screen.
		updateSubpanelSubtabs: function(group){
			var notFirst = 0;
			var preMore = SUGAR.subpanelUtils.subpanelGroups[group].slice(0, SUGAR.subpanelUtils.subpanelMaxSubtabs);

			SUGAR.subpanelUtils.subpanelSubTabs[group] = '<table border="0" cellpadding="0" cellspacing="0" height="20" width="100%" class="subTabs"><tr>';

			for(var sp_key = 0; sp_key < preMore.length; sp_key++){
				if(notFirst != 0){
					SUGAR.subpanelUtils.subpanelSubTabs[group] += '<td width="1"> | </td>';
				}else{
					notFirst = 1;
				}
				SUGAR.subpanelUtils.subpanelSubTabs[group] += '<td nowrap="nowrap"><a href="#'+preMore[sp_key]+'" class="subTabLink">'+SUGAR.subpanelUtils.subpanelTitles[preMore[sp_key]]+'</a></td>';
			}
			if(document.getElementById('MoreSub'+group+'PanelMenu')){
				SUGAR.subpanelUtils.subpanelSubTabs[group] += '<td nowrap="nowrap"> | &nbsp;<span class="subTabMore" id="MoreSub'+group+'PanelHandle" style="margin-left:2px; cursor: pointer; cursor: hand;" align="absmiddle" onmouseover="SUGAR.subpanelUtils.menu.tbspButtonMouseOver(this.id,\'\',\'\',0);">&gt;&gt;</span></td>';
			}
			SUGAR.subpanelUtils.subpanelSubTabs[group] += '<td width="100%">&nbsp;</td></tr></table>';

			// Update the more menu for the current group
			var postMore = SUGAR.subpanelUtils.subpanelGroups[group].slice(SUGAR.subpanelUtils.subpanelMaxSubtabs);
			var subpanelMenu = document.getElementById('MoreSub'+group+'PanelMenu');

			if(postMore && subpanelMenu){
				subpanelMenu.innerHTML = '';
				for(var sp_key = 0; sp_key < postMore.length; sp_key++){
					subpanelMenu.innerHTML += '<a href="#'+postMore[sp_key]+'" class="menuItem" parentid="MoreSub'+group+'PanelMenu" onmouseover="hiliteItem(this,\'yes\'); closeSubMenus(this);" onmouseout="unhiliteItem(this);">'+SUGAR.subpanelUtils.subpanelTitles[postMore[sp_key]]+'</a>';
				}
				subpanelMenu += '</div>';
			}
		},

		setGroupCookie: function(group){
			Set_Cookie(SUGAR.subpanelUtils.tabCookieName, group, 3000, false, false,false);
		}
	};
}();

SUGAR.subpanelUtils.menu = function(){
	return {
		tbspButtonMouseOver : function(id,top,left,leftOffset){ //*//
			closeMenusDelay = eraseTimeout(closeMenusDelay);
			if (openMenusDelay == null){
				openMenusDelay = window.setTimeout("SUGAR.subpanelUtils.menu.spShowMenu('"+id+"','"+top+"','"+left+"','"+leftOffset+"')", delayTime);
			}
		},
		spShowMenu : function(id,top,left,leftOffset){ //*//
			openMenusDelay = eraseTimeout(openMenusDelay);
			var menuName = id.replace(/Handle/i,'Menu');
			var menu = getLayer(menuName);
			if (currentMenu){
				closeAllMenus();
			}
			SUGAR.subpanelUtils.menu.spPopupMenu(id, menu, top,left,leftOffset);
		},
		spPopupMenu : function(handleID, menu, top, left, leftOffset){ //*//
			var bw = checkBrowserWidth();
			var menuName = handleID.replace(/Handle/i,'Menu');
			var menuWidth = 120;
			var imgWidth = document.getElementById(handleID).width;
			if (menu){
				var menuHandle = getLayer(handleID);
				var p=menuHandle;
				if (left == "") {
					var left = 0;
					while(p&&p.tagName.toUpperCase()!='BODY'){
						left+=p.offsetLeft;
						p=p.offsetParent;
					}
					left+=parseInt(leftOffset);
				}
				if (top == "") {
					var top = 0;
					p=menuHandle;
					top+=p.offsetHeight;
					while(p&&p.tagName.toUpperCase()!='BODY'){
						top+=p.offsetTop;
						p=p.offsetParent;
					}
				}
				if (left+menuWidth>bw) {
					left = left-menuWidth+imgWidth;
				}
				setMenuVisible(menu, left, top, false);
			}
		}
	};
}();
