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
function treeinit() {}

if(typeof('console') == 'undefined'){
console = {
	log: function(message){

	}}
}
(function() {
	var sw = YAHOO.SUGAR,
		Event = YAHOO.util.Event,
		Connect = YAHOO.util.Connect,
	    Dom = YAHOO.util.Dom;
	
function createTreePanel(treeData, params) {
	var tree = new YAHOO.widget.TreeView(params.id);
	var root = tree.getRoot();
	addChildNodes(root, treeData);
	
	return tree;
}

function addChildNodes(parentNode, parentData) {
	var nodes = parentData.nodes || parentData.children;
	for (i in nodes) {
		if (typeof(nodes[i]) == 'object') {
			nodes[i].data.href = 'javascript:void(0);';
			var node = new YAHOO.widget.TextNode(nodes[i].data, parentNode)
			node.action = nodes[i].data.action;
			if (typeof(nodes[i].nodes) == 'object') {
				addChildNodes(node, nodes[i]);
			}
		}
	}
}

if (typeof(ModuleBuilder) == 'undefined') {
	ModuleBuilder = {
		/**
		 * Elements of the request being sent either through {@see submitForm} or
		 * {@see asyncRequest}. This holds certain elements of the request for use
		 * in {@see resendRequest} when studio goes stale and the parent window
		 * session cookie is refreshed but the system does not expose the session
		 * cookie name to the client.
		 * 
		 * @type {Object}
		 */
		requestElements: {},
		/**
		 * Flag that indicates to the process whether this is a request that is
		 * in the process of being resent because of a stale session id.
		 * 
		 * @type {Boolean}
		 */
		isResend: false,
	    init: function(){
			//Setup the basic ajax request settings
			Connect.extraParams = {
				to_pdf: true
			};
			Connect.url = 'index.php?to_pdf=1&sugar_body_only=1';
			Connect.method = 'POST';
			Connect.timeout = 300000; 
			
			if (SUGAR.themes.tempHideLeftCol)
				SUGAR.themes.tempHideLeftCol();
			
			var Ck = YAHOO.util.Cookie;
			
			//Setup the main layout
			var tp = ModuleBuilder.tabPanel = new YAHOO.widget.TabView("mbtabs");
			tp.addTab(new YAHOO.widget.Tab({ 
				label: SUGAR.language.get('ModuleBuilder', 'LBL_SECTION_MAIN'),
				scroll : true,
				content : "<div> </div>",
				id : "center",
				active : true
			}));

			var viewHeight = document.documentElement ? document.documentElement.clientHeight : self.innerHeight;
            var heightOffset = $('#dcmenu').length > 0 ? $('#dcmenu').height() : $('#header').height();
			var mp = ModuleBuilder.mainPanel = new YAHOO.widget.Layout('mblayout', {
				border: false,
				height: viewHeight - heightOffset - 40,
				//autoHeight: true
				//frame: true,
				units: [//ModuleBuilder.tree, ModuleBuilder.tabPanel,
				{
					position: 'center',
					body : 'mbcenter',
					scroll : true
				},{
					position: "left",
					header: "Tree",
					collapse: true,
					width: 230,
					minWidth: 100,
					resize: true,
					scroll : true,
					body : "<div id='mbTree'/>"
				},{
					id: 'help',
					header: SUGAR.language.get('ModuleBuilder', 'LBL_SECTION_HELP'),
					position:'right',
					body: 'mbhelp',
					scroll: true,
					width: 250,
					minWidth: 200,
					resize: true,
					collapse: true
				}]
			});
			mp.render();
			
			ModuleBuilder.nextYear = new Date();
			ModuleBuilder.nextYear.setDate(ModuleBuilder.nextYear.getDate() + 360);
			
			var nextyear = ModuleBuilder.nextYear;
			
			if (Ck.getSub("ModuleBuilder", "helpHidden") == "true") {
				mp.getUnitByPosition('right').collapse();
			}
			if (Ck.getSub("ModuleBuilder", "treeHidden") == "true") {
				mp.getUnitByPosition('left').collapse();
			}
			
			var centerEl = mp.getUnitByPosition('center').get('wrap');
			tp.appendTo(centerEl);
			
			//YUI does not take the resizers into account when calculating panel size.
			var correctW = function(){
				var w = (this.body.offsetWidth - 7) + "px";
				this.body.style.width = w;
				this.header.style.width = w;
                if (typeof Studio2 != "undefined")
                    Studio2.resizeDivs();
                if (typeof resizeDDLists == "function")
                    resizeDDLists();
			};
			mp.getUnitByPosition('right').on("resize", correctW); 
			mp.getUnitByPosition('right').on("collapse", function(){
				Ck.setSub("ModuleBuilder", "helpHidden", "true");
                mp.get("element").querySelector(".yui-layout-clip-right .collapse").id = "expand_help";
			});
			mp.getUnitByPosition('right').on("expand", function(){
				Ck.setSub("ModuleBuilder", "helpHidden", "false");
			});
			mp.getUnitByPosition('left').on("resize", correctW);
			mp.getUnitByPosition('left').on("collapse", function(){
				Ck.setSub("ModuleBuilder", "treeHidden", "true");
                mp.get("element").querySelector(".yui-layout-clip-left .collapse").id = "expand_tree";
			});
			mp.getUnitByPosition('left').on("expand", function(){
				Ck.setSub("ModuleBuilder", "treeHidden", "false");
			});
			mp.resize(true);
			Event.on(window, 'resize', ModuleBuilder.autoSetLayout, this, true);

			var tree = ModuleBuilder.tree = createTreePanel(TREE_DATA, {
				id: 'mbTree'
			});
			tree.setCollapseAnim("TVSlideOut");
			tree.setExpandAnim("TVSlideIn");
			//tree.subscribe("labelClick", ModuleBuilder.handleTreeClick);
			tree.subscribe("clickEvent", ModuleBuilder.handleTreeClick);
			tree.render();
			
			//Setup Browser History
			var mbContent = YAHOO.util.History.getBookmarkedState('mbContent');
			
			if (ModuleBuilder.mode == 'mb') {
				mp.getUnitByPosition('left').header.firstChild.innerHTML = SUGAR.language.get('ModuleBuilder', 'LBL_SECTION_PACKAGES');
				mbContent = mbContent ? mbContent : 'module=ModuleBuilder&action=package&package=';
			}
			else if (ModuleBuilder.mode == 'studio') {
				ModuleBuilder.MBpackage = ''; // set to empty so other views can recognize that dealing with an deployed, rather than undeployed, module
				mp.getUnitByPosition('left').header.firstChild.innerHTML = SUGAR.language.get('ModuleBuilder', 'LBL_SECTION_MODULES');
				mbContent = mbContent ? mbContent :'module=ModuleBuilder&action=wizard';
			}
			else if (ModuleBuilder.mode == 'sugarportal') {
				mp.getUnitByPosition('left').header.firstChild.innerHTML =SUGAR.language.get('ModuleBuilder', 'LBL_SECTION_PORTAL');
				mbContent = mbContent ? mbContent : 'module=ModuleBuilder&action=wizard&portal=1';
			}
			else if (ModuleBuilder.mode == 'dropdowns') {
				mp.getUnitByPosition('left').header.firstChild.innerHTML = SUGAR.language.get('ModuleBuilder', 'LBL_SECTION_DROPDOWNS');
				mbContent = mbContent ? mbContent : 'module=ModuleBuilder&action=dropdowns';
			}
			else {
				mp.getUnitByPosition('left').collapse(false);
				mbContent = mbContent ? mbContent : 'module=ModuleBuilder&action=home';
			}

			YAHOO.util.History.register('mbContent', mbContent, ModuleBuilder.navigate);
            YAHOO.util.History.initialize("yui-history-field", "yui-history-iframe");
			ModuleBuilder.getContent(mbContent);
			
			if (SUGAR.themes.tempHideLeftCol) SUGAR.themes.tempHideLeftCol();
			ModuleBuilder.autoSetLayout();
			
			ModuleBuilder.tabPanel.on('activeTabChange', function(e) {
				ModuleBuilder.helpLoad( e.newValue.get("id") ) ;
			});
			
			if (Dom.get("HideHandle")){
				if (SUGAR.themes.tempHideLeftCol){
					SUGAR.themes.tempHideLeftCol();
					}
			}
            //We need to add ID's to the collapse buttons for automated testing
            Dom.getElementsByClassName("collapse", "div", mp.getUnitByPosition('left').header)[0].id = "collapse_tree";
            Dom.getElementsByClassName("collapse", "div", mp.getUnitByPosition('right').header)[0].id = "collapse_help";
		},
		//Empty layout manager
		layoutValidation: {
			popup_window: null,
			popup: function(){
				ModuleBuilder.layoutValidation.popup_window = new YAHOO.widget.SimpleDialog("emptyLayout", {
					width: "400px",
					draggable: true,
					constraintoviewport: true,
					modal: true,
					fixedcenter: true,
					text: SUGAR.language.get('ModuleBuilder', 'ERROR_MINIMUM_FIELDS'),
					bodyStyle: "padding:5px",
					buttons: [{
						text: SUGAR.language.get('ModuleBuilder', 'LBL_BTN_CLOSE'),
						isDefault:true,
						handler: function(){
							ModuleBuilder.layoutValidation.popup_window.hide()
						}
					}]
				});
				ModuleBuilder.layoutValidation.popup_window.render(document.body);
				ModuleBuilder.layoutValidation.popup_window.show();
			}
		},
		//Layout history manager
		history: {
			popup_window: false,
			reverted: false,
			params: { },
			browse: function(module, layout, subpanel){
				subpanel = subpanel ? subpanel : "";
				if (!module && ModuleBuilder.module != "undefined") {
					module = ModuleBuilder.module;
				}   
				if (!ModuleBuilder.history.popup_window) {
					ModuleBuilder.history.popup_window = new YAHOO.SUGAR.AsyncPanel('histWindow', {
						width: "400px",
						draggable: true,
						close: true,
						constraintoviewport: true,
						fixedcenter: false
					});
				}
				var module_str = module;
				if(typeof SUGAR.language.languages['app_list_strings']['moduleList'][module] != 'undefined'){
					module_str = SUGAR.language.languages['app_list_strings']['moduleList'][module];
				} 
				ModuleBuilder.history.popup_window.setHeader( module_str + ' : ' + SUGAR.language.get('ModuleBuilder', 'LBL_' + layout.toUpperCase()) + SUGAR.language.get('ModuleBuilder', 'LBL_HISTORY_TITLE'));
				ModuleBuilder.history.popup_window.setBody("test");
				ModuleBuilder.history.popup_window.render(document.body);
				ModuleBuilder.history.params = {
					module: 'ModuleBuilder',
					histAction: 'browse',
					action: 'history',
					view_package: ModuleBuilder.MBpackage,
					view_module: module,
					view: layout,
                    subpanel: subpanel
                    ,role: $("input[name=role]").val()
				};
				ModuleBuilder.history.popup_window.load(ModuleBuilder.paramsToUrl(ModuleBuilder.history.params));
				ModuleBuilder.history.popup_window.show();
				ModuleBuilder.history.popup_window.center();
			},
			preview: function(module, layout, id, subpanel) {
				var prevPanel =  ModuleBuilder.findTabById('preview:' + id);
				if (!prevPanel) {
					ModuleBuilder.history.params = {
						module: 'ModuleBuilder',
						histAction: 'preview',
						action: 'history',
						view_package: ModuleBuilder.MBpackage,
						view_module: module,
						view: layout,
						sid: id,
                        subpanel: subpanel
                        ,role: $("input[name=role]").val()
					};
					prevPanel = new YAHOO.SUGAR.ClosableTab({
						dataSrc: Connect.url + "&" + ModuleBuilder.paramsToUrl(ModuleBuilder.history.params),
						label: SUGAR.language.get("ModuleBuilder", "LBL_MB_PREVIEW"),
						id: 'preview:' + id,
						scroll: true,
						cacheData: true,
						active :true
					}, ModuleBuilder.tabPanel);
					prevPanel.closable = true;
					ModuleBuilder.tabPanel.addTab(prevPanel);
				} else {
					ModuleBuilder.tabPanel.set("activeTab", prevPanel);
				}
				
			},
            revert: function(module, layout, id, subpanel, isDefault) {
				var prevTab = ModuleBuilder.tabPanel.getTabIndex("preview:" + id);
				if(prevTab) ModuleBuilder.tabPanel.removeTab(prevTab);

                var role = $("input[name=role]").val();
				ModuleBuilder.history.params = {
					module: 'ModuleBuilder',
					histAction: 'restore',
					action: 'history',
					view_package: ModuleBuilder.MBpackage,
					view_module: module,
					view: layout,
					sid: id,
                    subpanel: subpanel
                    ,role: role
				};
				ModuleBuilder.asyncRequest(ModuleBuilder.history.params, function(){
					ModuleBuilder.history.reverted = true;
                    ModuleBuilder.getContent(ModuleBuilder.paramsToUrl({
                        module: "ModuleBuilder",
                        action: "editLayout",
                        view: layout,
                        view_module: module,
                        subpanel: subpanel,
                        view_package: ModuleBuilder.MBpackage
                    }), function(content) {
                        ModuleBuilder.updateContent(content);
                        if (role && isDefault) {
                            ModuleBuilder.state.markAsReset();
                        } else {
                            ModuleBuilder.state.markAsDirty();
                        }
                    });
				}, false);
			},
            resetToDefault: function(module, layout) {
                ModuleBuilder.history.params = {
                    module: 'ModuleBuilder',
                    histAction: 'resetToDefault',
                    action: 'history',
                    view_package: ModuleBuilder.MBpackage,
                    view_module: module,
                    view: layout,
                    role: $("input[name=role]").val()
                };
                ModuleBuilder.asyncRequest(ModuleBuilder.history.params, function(){
                    ModuleBuilder.history.reverted = true;
                    ModuleBuilder.getContent(ModuleBuilder.contentURL, function(content) {
                        ModuleBuilder.updateContent(content);
                        ModuleBuilder.state.markAsDirty();
                        ModuleBuilder.state.markAsReset();
                    });
                });
            },
			cleanup: function() {
				if (ModuleBuilder.history.reverted && ModuleBuilder.history.params.histAction) {
					ModuleBuilder.history.params.histAction = 'unrestore';
					ModuleBuilder.asyncRequest({params: ModuleBuilder.history.params});
				}
				ModuleBuilder.history.params = { };
				ModuleBuilder.history.reverted = false;
			},
			update: function() {
				if (ModuleBuilder.history.popup_window && ModuleBuilder.history.popup_window.cfg.getProperty("visible")) {
					var historyButton = YAHOO.util.Dom.get('historyBtn');
					if (historyButton) {
						historyButton.onclick();
					} else {
						ModuleBuilder.history.popup_window.hide();
					}
				}
			}
		},
		state: {
			isDirty: false,
            isReset: false,
            markAsDirty: function() {
                ModuleBuilder.state.isDirty = true;
                ModuleBuilder.state.markAsNotReset();
            },
            markAsClean: function() {
                ModuleBuilder.state.isDirty = false;
            },
            markAsReset: function() {
                ModuleBuilder.state.isReset = true;
                $("#saveBtn").prop("disabled", true);
            },
            markAsNotReset: function() {
                ModuleBuilder.state.isReset = false;
                $("#saveBtn").prop("disabled", false);
            },
			saving: false,
            hideFailedMesage: false,
			intended_view: {
				url: null,
				successCall: null
			},
			current_view: {
				url: null,
				successCall: null
			},
			save_url_for_current_view: null,
			popup_window: null,
			setupState: function(){
				document.body.setAttribute("onclose", "ModuleBuilder.state.popup(); ModuleBuilder.state.popup_window.show()");
				return;
			},
			onSaveClick: function(){
				//set dirty = false
				//call the save method of the current view.
				//call the intended action.
                ModuleBuilder.state.markAsClean();
				var saveBtn = document.getElementById("saveBtn");
				if (!saveBtn) {
					var mbForm = document.forms[1];
					if (mbForm)
						var mbButtons = mbForm.getElementsByTagName("input");
					if (mbButtons) {
						for (var button = 0; button < mbButtons.length; button++) {
							var name = mbButtons[button].getAttribute("name");
							if (name && (name.toUpperCase() == "SAVEBTN" || name.toUpperCase() == "LSAVEBTN")) {
								saveBtn = mbButtons[button];
								break;
							}
						}
					}
					else {
						alert(SUGAR.language.get('ModuleBuilder', 'LBL_NO_SAVE_ACTION'));
					}
				}
				if (saveBtn) {
					//After the save call completes, load the next page
					ModuleBuilder.state.saving = true;
                    saveBtn.click();
				}
				ModuleBuilder.state.popup_window.hide();

				ModuleBuilder.getContent(ModuleBuilder.state.intended_view.url, ModuleBuilder.state.intended_view.successCall);
			},
			onDontSaveClick: function(){
				//set dirty to false
				//call the intended action.
                ModuleBuilder.state.markAsClean();
				ModuleBuilder.history.cleanup();
				ModuleBuilder.getContent(ModuleBuilder.state.intended_view.url, ModuleBuilder.state.intended_view.successCall);
				ModuleBuilder.state.popup_window.hide();
			},
			loadOnSaveComplete: function() {
				ModuleBuilder.state.saving = false;
				ModuleBuilder.getContent(ModuleBuilder.state.intended_view.url, ModuleBuilder.state.intended_view.successCall);
			},
			popup: function(){
                if(false == YAHOO.lang.isObject(ModuleBuilder.state.popup_window) || ModuleBuilder.state.popup_window.id != 'confirmUnsaved'){
                    ModuleBuilder.state.popup_window = new YAHOO.widget.SimpleDialog("confirmUnsaved", {
                     width: "400px",
                     draggable: true,
                     constraintoviewport: true,
                     modal: true,
                     fixedcenter: true,
                     text: SUGAR.language.get('ModuleBuilder', 'LBL_CONFIRM_DONT_SAVE'),
                     bodyStyle: "padding:5px",
                     buttons: [{
                        text: SUGAR.language.get('ModuleBuilder', 'LBL_BTN_DONT_SAVE'),
                        handler: ModuleBuilder.state.onDontSaveClick
                     }, {
                        text: SUGAR.language.get('ModuleBuilder', 'LBL_BTN_CANCEL'),
                        isDefault:true,
                        handler: function(){
                            ModuleBuilder.state.popup_window.hide()
                            if (ModuleBuilder.state.intended_view.cancelCall) {
                                ModuleBuilder.state.intended_view.cancelCall();
                            }
                        }
                     },{
                        text: SUGAR.language.get('ModuleBuilder', 'LBL_BTN_SAVE_CHANGES'),
                        handler: ModuleBuilder.state.onSaveClick
                        }]
                    });
                    ModuleBuilder.state.popup_window.setHeader(SUGAR.language.get('ModuleBuilder', 'LBL_CONFIRM_DONT_SAVE_TITLE'));
                }
                if(ModuleBuilder.disablePopupPrompt != 1){
                    ModuleBuilder.state.popup_window.render(document.body);
                }else{
                    ModuleBuilder.state.onDontSaveClick();
                }
			}
		},
        handleFieldEditToggling: function() {
            // Handle calculated field check box controlling
            var calculatedCheckbox = Dom.get('calculated');
            if (calculatedCheckbox && calculatedCheckbox.checked) {
                ModuleBuilder.disableCalculatedControlledElems(true);
            }
        },
        copyFromView: function(module, layout){
            var url = ModuleBuilder.contentURL;
            ModuleBuilder.getContent(url+"&copyFromEditView=true");
             ModuleBuilder.contentURL = url;
            ModuleBuilder.state.intended_view.url = url;
            ModuleBuilder.state.markAsDirty();
        },
		//AJAX Navigation Functions
		navigate : function(url) {
			//Check if we are just registering the url
			if (url != ModuleBuilder.contentURL) {
				ModuleBuilder.getContent(url);
			}
		},
        getContent: function(url, successCall, cancelCall) {
			if (!url) return;
			
			if (url.substring(0, 11) == "javascript:")
			{
				eval(url.substring(11));
				return;
			}

			//save a pointer to intended action
			ModuleBuilder.state.intended_view.url = url;
			ModuleBuilder.state.intended_view.successCall = successCall;
            ModuleBuilder.state.intended_view.cancelCall = cancelCall;
			if(ModuleBuilder.state.isDirty){ //prompt to save current data.
				//check if we are editing a property of the current view (such views open up in new tabs)
				//if so we leave the state dirty and return

				ModuleBuilder.state.popup();
				ModuleBuilder.state.popup_window.show();
				return;
			}else{
				ModuleBuilder.state.current_view.url = url;
				ModuleBuilder.state.current_view.successCall = successCall;
			}
			ModuleBuilder.centerContentURL = ModuleBuilder.contentURL || url;
			ModuleBuilder.contentURL =  url;
			if (typeof(successCall) != 'function') {
				if (ModuleBuilder.callInProgress)
					return;
				ModuleBuilder.callInProgress = true;
				successCall = ModuleBuilder.updateContent;
			}

            var requestUrl = url;
            var role = $("input[name=role]").val();
            if (role && url.indexOf("&role=") < 0 && ModuleBuilder.isLayoutTheSame(url)) {
                requestUrl += "&role=" + encodeURIComponent(role);
            }

            ModuleBuilder.asyncRequest(requestUrl, successCall);
		},

        /**
         * Checks if the layout corresponding to the given URL is the same as current one
         *
         * @param {String} url The URL to be requested
         * @returns {Boolean}
         */
        isLayoutTheSame: function(url) {
            var params = ["view_module", "view"];
            var values = ModuleBuilder.urlToParams(url);
            var currentValues = ModuleBuilder.urlToParams(ModuleBuilder.centerContentURL);
            for (var i = 0, param; i < params.length; i++) {
                param = params[i];
                if (values[param] && currentValues[param] && values[param] != currentValues[param]) {
                    return false;
                }
            }

            return true;
        },

		updateContent: function(o){
            if (ModuleBuilder.copyLayoutDialog) {
                ModuleBuilder.copyLayoutDialog.destroy();
                delete ModuleBuilder.copyLayoutDialog;
            }

			ModuleBuilder.callInProgress = false;
			//Check if a save action was called and now we need to move-on
			if (ModuleBuilder.state.saving) {
				ModuleBuilder.state.loadOnSaveComplete();
				return;
			}
			ajaxStatus.flashStatus(SUGAR.language.get('app_strings', 'LBL_REQUEST_PROCESSED'), 2000);
			if(ModuleBuilder.checkForErrors(o))
                return false; 
			
			try {
			var ajaxResponse = YAHOO.lang.JSON.parse((o.responseText));
			} catch (err) {
				// If this is a login redirect and the request is in the process
				// of resending then we do not want to render the alert box since
				// the request will eventually finish and render what was asked for.
				if (!(ModuleBuilder.isResend && ModuleBuilder.isLogoutRedirect(o.responseText))) {
					YAHOO.SUGAR.MessageBox.show({
	                    title: SUGAR.language.get('ModuleBuilder', 'ERROR_GENERIC_TITLE'),
	                    msg: o.responseText,
	                    width: 500
	                });
				}
				return false;
			}
			
			
			if (ajaxResponse.tpl){
				var t = new YAHOO.SUGAR.Template(ajaxResponse.tpl);
				ModuleBuilder.ajaxData = ajaxResponse.data;
				ModuleBuilder.tabPanel.getTab(0).set(t.exec(ajaxResponse.data));
				SUGAR.util.evalScript(t.exec(ajaxResponse.data));
				return true;
			}
			// If the center panel isn't being updated, revert the content URL since we only care about the center panel
			// for reload purposes
			if (!ajaxResponse.center) {
				ModuleBuilder.contentURL = ModuleBuilder.centerContentURL;
			}
			
			for (var maj in ajaxResponse) {
				var name = 'mb' + maj;
				var comp = ModuleBuilder.mainPanel.getUnitById(maj);
				if (!comp) {
					var tabs = ModuleBuilder.tabPanel.get("tabs");
					for (i in tabs) {
						if (tabs[i].get && tabs[i].get("id") == maj)
						comp = tabs[i];
					}
				}
				
				if (name == 'mbwest') { //refresh package_tree!
					var tree = ModuleBuilder.tree;
					var root = tree.root;
					tree.maxAnim = 0;
					tree.collapseAll();
					while (root.hasChildren()) {
						tree.removeNode(root.children[0], true);
					}
					addChildNodes(root, ajaxResponse.west.content.tree_data);
					tree.maxAnim = 2;
					tree.render();
				}
				else {
					if (!comp) {
						if(ajaxResponse[maj].action == 'deactivate') continue;
						comp = new YAHOO.SUGAR.ClosableTab({
							content: "<div class='bodywrapper'><script>ModuleBuilder.scriptTest=true;</script>" + ((maj == 'center') ? "<div>" + ajaxResponse[maj].crumb + "</div>" :"")
								 + ajaxResponse[maj].content + "</div>",
							label: ajaxResponse[maj].title,
							id: maj,
							scroll: true,
							closable: true,
							active :true
						}, ModuleBuilder.tabPanel);
						comp.closable = true;
						ModuleBuilder.scriptTest = false;
						ModuleBuilder.tabPanel.set("activeTab", comp);
						ModuleBuilder.tabPanel.addTab(comp);
						//Text if the browser automatically evaluated the content's script tags or not. If not, manually evaluate them.
						if (!ModuleBuilder.scriptTest) {
							SUGAR.util.evalScript(ajaxResponse[maj].content);
						}
					} else {
						//Store Center pane changes in browser history
						if (name == 'mbcenter') {
							YAHOO.util.History.navigate('mbContent', ModuleBuilder.contentURL);
							ModuleBuilder.closeAllTabs();
							comp = ModuleBuilder.tabPanel.getTab(0);
						}
						ModuleBuilder.tabPanel.set("activeTab", comp);
						comp.set('content', "<div class='bodywrapper'><div>" + ajaxResponse[maj].crumb + "</div>" + ajaxResponse[maj].content + "</div>");
						if (ajaxResponse[maj].title != "no_change")
							comp.set('label', ajaxResponse[maj].title);
						SUGAR.util.evalScript(ajaxResponse[maj].content);	
					}
				}
				ModuleBuilder.history.update();
				ModuleBuilder.handleFieldEditToggling();
			}
		},
		checkForErrors: function(o){
			if (SUGAR.util.isLoginPage(o.responseText))
				return true;
			if (o.responseText.substr(0, 1) == "<") {
				// If the response indicates a login redirect then the session id
				// has gone stale and needs to be updated. 
				if (ModuleBuilder.isLogoutRedirect(o.responseText)) {
					// This means a bwc redirect, so attempt to login and carry on
					ModuleBuilder.isResend = true;
					parent.SUGAR.App.bwc.login(null, ModuleBuilder.resendRequest);
					return false;
				}
				
				// Only show the error alert box if this is not a resend request
				if (!ModuleBuilder.isResend) {
	                YAHOO.SUGAR.MessageBox.show({
						title: SUGAR.language.get('ModuleBuilder', 'ERROR_GENERIC_TITLE'),
						msg: o.responseText,
						width: 500
					});
					return true;
				}
            }
			
			
			return false;
		},
		refreshMetadata: function() {
			// Get the parent api object
			var api = parent.SUGAR.App.api;

			// Call the ping api
			api.call('read', api.buildURL('ping'));
		},
        /**
         * toggles save and cancel buttons
         */
        toggleButtons: function() {
            if ($.fn.toggle) {
                $('#popup_form_id [name="cancelbtn"]').toggle();
                $('#popup_form_id [name="fsavebtn"]').toggle();
            }
        },
		submitForm: function(formname, successCall){
            ModuleBuilder.toggleButtons();
			ajaxStatus.showStatus(SUGAR.language.get('ModuleBuilder', 'LBL_AJAX_LOADING'));
			if (typeof(successCall) == 'undefined') {
				successCall = ModuleBuilder.updateContent;
			}
			else {
				ModuleBuilder.callLock = true;
			}
			
			// Capture aspects of the request in case the need to resend arises
			ModuleBuilder.requestElements.fields = Connect.setForm(document.getElementById(formname) || document.forms[formname]);
            ModuleBuilder.requestElements.callbacks = {
                success: function() {
                    ModuleBuilder.toggleButtons();
                    successCall.apply(this, arguments);
                },
                failure: function() {
                    ModuleBuilder.toggleButtons();
                    ModuleBuilder.failed.apply(this, arguments);
                }
            };
			Connect.asyncRequest(
			    Connect.method, 
			    Connect.url, 
			    ModuleBuilder.requestElements.callbacks
			);
		},
		setMode: function(reqMode){
			ModuleBuilder.mode = reqMode;
		},
		main: function(type){
			document.location.href = 'index.php?module=ModuleBuilder&action=index&type=' + type;
		},
		failed: function(o){
            if(!ModuleBuilder.state.hideFailedMesage){
                ajaxStatus.flashStatus(SUGAR.language.get('ModuleBuilder', 'LBL_AJAX_FAILED_DATA'), 2000);
            }
		},
		//Wizard Functions
		buttonDown: function(button, name, list){
			if (typeof(name) != 'undefined') {
				for (i in ModuleBuilder.buttons[list]) {
					ModuleBuilder.buttons[list][i].className = 'wizardButton';
				}
				ModuleBuilder.buttonSelect(name, list);
			}
			button.className = 'wizardButtonDown';

		},
		buttonOver: function(button){
			button.className = 'button';
		},
		buttonOut: function(button, name, list){
			if (typeof(name) != 'undefined') {
				if (ModuleBuilder.buttonGetSelected(list) != name) {
					button.className = 'wizardButton'
				}
			}
			else {
				button.className = 'wizardButton'
			}

		},
		buttonAdd: function(id, name, list){
			if (typeof(ModuleBuilder.buttons[list]) == 'undefined') {
				ModuleBuilder.buttons[list] = {};

			}
			ModuleBuilder.buttons[list][name] = document.getElementById(id);

		},
		buttonGetSelected: function(list){
			if (typeof(ModuleBuilder.selected[list]) == 'undefined') {
				return false;
			}
			return ModuleBuilder.selected[list];
		},
		buttonSelect: function(name, list){
			ModuleBuilder.selected[list] = name;
		},
		buttonToForm: function(form, field, list){
			var theField = eval('document.' + form + '.' + field);
			theField.value = ModuleBuilder.buttonGetSelected(list);
		},

		getTitle: function(title, breadCrumb){
			return "<h2>" + title + "</h2><br>" + breadCrumb;
		},
		closeAllTabs: function() {
			var tabs = ModuleBuilder.tabPanel.get('tabs');
			for (var i = tabs.length - 1; i > -1; i--) {
				var tab = tabs[i];
				if (tab.close) {
					tab.close();
				}
			}
		},
		//Help Functions
		helpRegister: function(name){
			var formname = 'document.' + name;
			var form = eval(formname);
			var i = 0;
			for (i = 0; i < form.elements.length; i++) {
				if (typeof(form.elements[i].type) != 'undefined' && typeof(form.elements[i].name) != 'undefined' && form.elements[i].type != 'hidden') {
					form.elements[i].onmouseover = function(){
						ModuleBuilder.helpToggle(this.name)
					};
					form.elements[i].onmouseout = function(){
						ModuleBuilder.helpToggle('default')
					};

				}
			}
		},
		helpUnregisterByID: function (id){
			var elm = document.getElementById(id);
			if (elm) {
			elm.onmouseover = function() {};
			elm.onmouseout = function() {};
			}
			return;
		},
		helpRegisterByID: function(name, tag){
			var parent = document.getElementById(name);
			var children = parent.getElementsByTagName(tag);
			for (var i = 0; i < children.length; i++) {
				if (children[i].id != 'undefined') {
					children[i].onmouseover = function(){
						ModuleBuilder.helpToggle(this.id)
					};
					children[i].onmouseout = function(){
						ModuleBuilder.helpToggle('default')
					};
				}
			}
		},
		/**
		 * Sets up the popup events for fieldset fields when double clicked
		 */
		helpSetupFieldsets: function() {
			var fieldsetElems = document.getElementsByClassName('field_fieldset_fields'),
				fieldElem;
			
			for (var i = 0; i < fieldsetElems.length; i++) {
				if (fieldsetElems[i].id != 'undefined') {
					// Get the div that contains the box that contains the field
					fieldElem = document.getElementById(fieldsetElems[i].id.replace('fieldset_', ''));
					if (fieldElem) {
						// Add a double click event that shows what field a combo
						// field contains
						fieldElem.ondblclick = function() {
							// Use jQuery DOM element selection since different
							// browsers handle content differently. This fixes an
							// issue where FF was not rendering the popup.
							YAHOO.SUGAR.MessageBox.show({
			                    title: $('#le_label_' + this.id).text().replace(" **", "") + " " + SUGAR.language.get("ModuleBuilder", "LBL_COMBO_FIELD_CONTAINS"),
			                    msg: $('#fieldset_' + this.id).html(),
			                    width: 500
			                });
						};
					}
				}
			}
		},
		helpSetup: function(group, def, panel){
			if (!ModuleBuilder.panelHelp) ModuleBuilder.panelHelp = [];
			
			// setup the linkage between this tab/panel and the relevant help
			var id = ModuleBuilder.tabPanel.get("activeTab").get("id")  ;
			ModuleBuilder.panelHelp [ id ] = { lang: group , def: def } ;
			 
			// get the help text if required
			if ( ! ModuleBuilder.AllHelpLang ) ModuleBuilder.AllHelpLang = SUGAR.language.get('ModuleBuilder', 'help');

			if (group && def) {
				ModuleBuilder.helpLang = ModuleBuilder.AllHelpLang[group];
				ModuleBuilder.helpDefault = def;
			} 
			
			ModuleBuilder.helpToggle('default');
			
			// Add fieldset help handling
			ModuleBuilder.helpSetupFieldsets();
		},
		helpLoad: function(panelId){
			if (!ModuleBuilder.panelHelp) return;
			
			if ( ! ModuleBuilder.AllHelpLang ) ModuleBuilder.AllHelpLang = SUGAR.language.get('ModuleBuilder', 'help');
			
			if ( ModuleBuilder.panelHelp [ panelId ] )
			{
				ModuleBuilder.helpLang = ModuleBuilder.AllHelpLang[ ModuleBuilder.panelHelp [ panelId ].lang ];
				ModuleBuilder.helpDefault = ModuleBuilder.panelHelp [ panelId ].def ;
				ModuleBuilder.helpToggle('default');
			}
		},
		helpToggle: function(name){
			// Handling for combo field help text being appended to record view
			// help text. The areas this applies to are the toolbox, the panels
			// area and the default area when the layout editor loads for record
			// view.
			var newHelpText;
			var appendComboFieldIndicator = name == 'default' || name == 'panels' || name == 'toolbox';
			var isRecordView = ModuleBuilder.helpDefault == 'defaultrecordview';
			if (name == 'default') {
				name = ModuleBuilder.helpDefault;
			}
			
			if (ModuleBuilder.helpLang != null && typeof(ModuleBuilder.helpLang[name]) != 'undefined') {
				newHelpText = ModuleBuilder.helpLang[name];
				if (isRecordView && appendComboFieldIndicator) {
					// Add the notification of combo fields
					newHelpText += "<br><br>" + SUGAR.language.get('ModuleBuilder', 'LBL_INDICATES_COMBO_FIELD');
				}
				document.getElementById('mbhelp').innerHTML = newHelpText;
			}
		},
		handleSave: function(form, callBack){
			if (check_form(form)) {
                ModuleBuilder.state.markAsClean();
				ModuleBuilder.submitForm(form, callBack);
			}
		},
		//Tree Functions
		handleTreeClick: function(o) {
			var node = o.node;
			ModuleBuilder.getContent(node.data.action);
			return false;
		},
		treeSubscribe:function(tree){
			tree.subscribe("labelClick", ModuleBuilder.treeLabelClick);
		},
		treeRefresh:function(type){
			ModuleBuilder.getContent('module=ModuleBuilder&action=ViewTree&tree=' + type);
		},
		//MB Specific
		addModule: function(MBpackage){
			ModuleBuilder.getContent('module=ModuleBuilder&action=module&view_package=' + MBpackage);
		},
		viewModule: function(MBpackage, module){
			ModuleBuilder.getContent('module=ModuleBuilder&action=module&view_package=' + MBpackage + '&view_module=' + module);
		},
		packageDelete: function(MBpackage){
			ajaxStatus.showStatus(SUGAR.language.get('ModuleBuilder', 'LBL_AJAX_DELETING'));
			if (confirm(SUGAR.language.get('ModuleBuilder', 'LBL_JS_REMOVE_PACKAGE'))) {
				ModuleBuilder.getContent('module=ModuleBuilder&action=DeletePackage&package=' + MBpackage);
				var node = ModuleBuilder.tree.getNodeByProperty('id', 'package_tree/' + MBpackage);
				if (node) ModuleBuilder.tree.removeNode(node, true);
			}
		},
		packagePublish: function(form){
			if (check_form(form)) {
				ajaxStatus.showStatus(SUGAR.language.get('ModuleBuilder', 'LBL_AJAX_BUILDPROGRESS'));
				ModuleBuilder.submitForm(form, ModuleBuilder.packageBuild);
			}
		},
		packageBuild: function(o){
			//make sure no user changes were made
			document.CreatePackage.action.value = 'BuildPackage';
			document.CreatePackage.submit();
			ajaxStatus.flashStatus(SUGAR.language.get('app_strings', 'LBL_REQUEST_PROCESSED'), 2000);
			ModuleBuilder.callLock = false;
		},
		packageDeploy: function(form, deployed){
            var confirmed = true;
            if (deployed){
    			confirmed = confirm(SUGAR.language.get('ModuleBuilder', 'LBL_JS_DEPLOY_PACKAGE'));
            }
	        if (confirmed && check_form(form)) {
				ajaxStatus.showStatus(SUGAR.language.get('ModuleBuilder', 'LBL_AJAX_DEPLOYPROGRESS'));
				ModuleBuilder.submitForm(form, ModuleBuilder.packageInstall);
			}
		},
		packageInstall: function(o){
			//make sure no user changes were made
			document.CreatePackage.action.value = 'displaydeploy';
			ModuleBuilder.callLock = false;
			ModuleBuilder.submitForm('CreatePackage', ModuleBuilder.packageInstallCleanup);
		},
		packageInstallCleanup: function(o){
			//make sure no user changes were made
			document.CreatePackage.action.value = 'displaydeploy';
			ModuleBuilder.callLock = false;
			ModuleBuilder.submitForm('CreatePackage');
		},
		beginDeploy: function(p){
			ModuleBuilder.asyncRequest('module=ModuleBuilder&action=DeployPackage&package=' + p, ModuleBuilder.deployComplete);
		},
		deployComplete: function(o){
			var resp = o.responseText;
			
			//check if the deploy completed
			if (!resp.match(/^\s*(\s*(Table already exists : [\w_]*)(<br>)*\s*)*complete$/m))
			{
					//Unknown error occured, warn the user
					alert(SUGAR.language.get("ModuleBuilder", "LBL_DEPLOY_FAILED"));
			}
			//Cleanup in the background
			ModuleBuilder.asyncRequest(
			    'module=Administration&action=RebuildRelationship&silent=true',
				function(){}
			);
			ModuleBuilder.asyncRequest(
				'module=Administration&action=RebuildDashlets&silent=true',
				function(){}			
			);
			
			ModuleBuilder.failed = function(){};
            ModuleBuilder.state.hideFailedMesage = true;

			// Reset the metadata on the client so that new modules are shown immediately
			ModuleBuilder.refreshMetadata();
			
			//Reload the page
			window.setTimeout("window.location.assign(window.location.href.split('#')[0])", 2000);
			
			
		},
		packageExport: function(form){
			if (check_form(form)) {
				ajaxStatus.showStatus(SUGAR.language.get('ModuleBuilder', 'LBL_AJAX_BUILDPROGRESS'));
				ModuleBuilder.submitForm(form, ModuleBuilder.packageExportProject);
			}
		},
		packageExportProject: function(o){
			//make sure no user changes were made
			document.CreatePackage.action.value = 'ExportPackage';
			document.CreatePackage.submit();
			ajaxStatus.flashStatus(SUGAR.language.get('app_strings', 'LBL_REQUEST_PROCESSED'), 2000);
			ModuleBuilder.callLock = false;
		},
		moduleDelete: function(MBpackage, module){
			ajaxStatus.showStatus(SUGAR.language.get('ModuleBuilder', 'LBL_AJAX_DELETING'));
			if (confirm(SUGAR.language.get('ModuleBuilder', 'LBL_JS_REMOVE_MODULE'))) {
				ModuleBuilder.getContent('module=ModuleBuilder&action=DeleteModule&package=' + MBpackage + '&view_module=' + module);
				var node = ModuleBuilder.tree.getNodeByProperty('id', 'package_tree/' + MBpackage + '/' + module);
				if (node) ModuleBuilder.tree.removeNode(node, true);
			}
		},
		moduleViewFields: function(o){

			ModuleBuilder.callLock = false;

			ModuleBuilder.getContent('module=ModuleBuilder&action=modulefields&view_package=' + ModuleBuilder.MBpackage + 
				'&view_module=' + ModuleBuilder.module);
		},
		moduleLoadField: function(name, type){
			if (typeof(type) == 'undefined')
				type = 0;
			if (typeof(formsWithFieldLogic) != 'undefined')
				formsWithFieldLogic = 'undefined';
			ModuleBuilder.getContent('module=ModuleBuilder&action=modulefield&view_package=' + ModuleBuilder.MBpackage + 
				'&view_module=' + ModuleBuilder.module + '&field=' + name + '&type=' + type);
		},
		moduleLoadLabels: function(type){
			if (typeof(type) == 'undefined')
				type = 0;
			else
				if (type == "studio") {
					ModuleBuilder.getContent('module=ModuleBuilder&action=editLabels&view_module=' + ModuleBuilder.module);
				}
				else
					if (type == "mb") {
						ModuleBuilder.getContent('module=ModuleBuilder&action=modulelabels&view_package=' + ModuleBuilder.MBpackage + '&view_module=' + ModuleBuilder.module + '&type=' + type);
					}
		},
		moduleViewRelationships: function(o){
			ModuleBuilder.callLock = false;
			ModuleBuilder.getContent('module=ModuleBuilder&action=relationships&view_package=' + ModuleBuilder.MBpackage + '&view_module=' + ModuleBuilder.module);
		},
		moduleLoadRelationship2: function(name, resetLabel, checkLanguage) {
			if (resetLabel && Dom.get('rhs_label')) {
				Dom.get('rhs_label').value = "";
			}
			var panel = ModuleBuilder.findTabById('relEditor');
			if (!panel) {
				panel = new YAHOO.SUGAR.ClosableTab({
					label: SUGAR.language.get("ModuleBuilder", "LBL_RELATIONSHIP_EDIT"),
					id: 'relEditor',
					scroll: true,
					cacheData: true,
					active :true
				}, ModuleBuilder.tabPanel);
				ModuleBuilder.tabPanel.addTab(panel);
			} else {
				ModuleBuilder.tabPanel.set("activeTab", panel);
			}
			var rtField = Dom.get('relationship_type_field');
			var relType = rtField ? rtField.options[rtField.selectedIndex].value: "";
			if (name == "") {
				name = Dom.get('rel_name_id') ? Dom.get('rel_name_id').value : "";
			}
			var params = {
				module: 'ModuleBuilder',
				action: 'relationship',
				view_package: ModuleBuilder.MBpackage,
				view_module: ModuleBuilder.module,
				relationship_name: name,
				relationship_type: relType,
				lhs_module: Dom.get('lhs_mod_field') ? Dom.get('lhs_mod_field').value : document.forms.relform ? document.forms.relform.lhs_module.value : "",
				rhs_module: Dom.get('rhs_mod_field') ? Dom.get('rhs_mod_field').value : "",
				lhs_label:  Dom.get('lhs_label')     ? Dom.get('lhs_label').value     : "",
				rhs_label:  Dom.get('rhs_label')     ? Dom.get('rhs_label').value     : "",
				json: false,
				id:'relEditor'
			};
			if(checkLanguage){
				params['relationship_lang'] = Dom.get('relationship_lang').value;
				params['ajaxLoad'] = '1';
			}
			ModuleBuilder.asyncRequest(params, function(o) {
				ajaxStatus.hideStatus();
				var tab = ModuleBuilder.findTabById('relEditor');
				tab.set("content", o.responseText);
				SUGAR.util.evalScript(o.responseText);
			});
		},
		inFieldCreate: function() {
			// See if this is a new field. Used for dropdown fields only when
			// creating a dropdown while creating a field. This keeps the field
			// name editable to prevent overwriting OOTB fields.
			var theform = document.forms[0];
			return theform.is_new !== undefined && theform.is_new.value == "1";
		},
		moduleDropDown: function(name, field) {
			// If this request is made in the middle of creating a new field then
			// we need to let the dropdown field know that so when the request
			// comes back it handles the field naming properly
			var isNew = ModuleBuilder.inFieldCreate() ? '&is_new_field=1' : '';
			ModuleBuilder.getContent('module=ModuleBuilder&action=dropdown&view_package=' + ModuleBuilder.MBpackage + '&view_module=' + ModuleBuilder.module + '&dropdown_name=' + name + '&field=' + field + isNew);
		},
		moduleViewLayouts: function(o){
			ModuleBuilder.callLock = false;
			ModuleBuilder.getContent('module=ModuleBuilder&MB=1&action=wizard&view_package=' + ModuleBuilder.MBpackage + '&view_module=' + ModuleBuilder.module);
		},
        moduleViewMobileLayouts: function(o){
            ModuleBuilder.callLock = false;
            ModuleBuilder.getContent('module=ModuleBuilder&MB=1&action=wizard&view=wirelesslayouts&view_package=' + ModuleBuilder.MBpackage + '&view_module=' + ModuleBuilder.module);
        },
		findTabById : function(id) {
			var tabs = ModuleBuilder.tabPanel.get("tabs");
			for (var i = 0; i < tabs.length; i++) {
				if (tabs[i].get("id") == id)
					return tabs[i];
			}
			return null;
		}, 
		autoSetLayout: function(){
			var mp = ModuleBuilder.mainPanel,
                c = Dom.get('mblayout'),
                width = Dom.getViewportWidth() - 40,
                height = Dom.getViewportHeight() - Dom.getY(c) - 30;
            if (SUGAR.util.isTouchScreen()) {
                width = parent.SUGAR.App.controller.layout.$el.width() - 40;
                height = parent.SUGAR.App.controller.layout.$el.height() - 60;
            }
            mp.set('height', height);
            mp.set('width', width);
            mp.resize(true);
			var tabEl = ModuleBuilder.tabPanel.get("element");
			Dom.setStyle(tabEl.firstChild.nextSibling, "overflow-y", "auto");
			Dom.setStyle(tabEl.firstChild.nextSibling, "height", tabEl.offsetHeight - ModuleBuilder.tabPanel.get("element").firstChild.offsetHeight - 5 + "px");
			//Resize editor layouts
			if (document.getElementById('toolbox')) Studio2.resizeDivs();
			if (document.getElementById('edittabs')) resizeDDLists();
		},
		paramsToUrl : function (params) {
			url = "";
			for (i in params) {
                url += i + "=" + params[i] + "&";
			}
			return url;
		},
        urlToParams: function(url) {
            var params = {};
            for (var pairs = url.split("&"), parts, i = 0, length = pairs.length; i < length; i++) {
                parts = pairs[i].split("=", 2);
                params[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);
            }
            return params;
        },
		/**
		 * Indicates whether the text presented shows that the request failed and
		 * is requiring a login via redirect.
		 * 
		 * @param {String} text The response text from a previous asyncRequest
		 * @return {Boolean} True if the provided text string contains a redirect indication
		 */
		isLogoutRedirect: function(text) {
			return text.substr(0,8) == "<script>" && text.indexOf("App.bwc.login") != -1;
		},
		/**
		 * Resends the last request. This will be used in cases where the session 
		 * id has gone stale and a request through BWC login has fetched a new
		 * one. 
		 * 
		 * @return {Void}
		 */
        resendRequest: function() {
            var method = ModuleBuilder.requestElements.method || Connect.method,
                url = ModuleBuilder.requestElements.url || Connect.url,
                postFields = {};
			
			// Reset the isResend flag so that if this request fails it can fail
			// legitimately
			ModuleBuilder.isResend = false;
			
			// Since this is only set on submitForm, check for it before just
			// throwing it into the Connect object
			if (ModuleBuilder.requestElements.fields) {
				Connect.setForm(ModuleBuilder.requestElements.fields);
			}

            // Attach CSRF token for POST
            if (method == "POST") {
                postFields['csrf_token'] = SUGAR.csrf.form_token;
            }

            Connect.asyncRequest(
                method,
                url,
                ModuleBuilder.requestElements.callbacks,
                SUGAR.util.paramsToUrl(postFields)
            );
		},
		asyncRequest : function (params, callback, showLoading) {
			// Used to normalize request arguments needed for the async request
			// as well as for setting into the requestElements object
            var url,
                cUrl = Connect.url,
                cMethod = Connect.method,
                postFields = {};
			
			if (typeof params == "object") {
				url = ModuleBuilder.paramsToUrl(params);
			} else {
				url = params;
			}
			
			cUrl += '&' + url;

            // attach CSRF token for POST, we don't want this in the url
            if (cMethod == "POST") {
                postFields['csrf_token'] = SUGAR.csrf.form_token;
            }
			
			ModuleBuilder.requestElements.method = cMethod;
			ModuleBuilder.requestElements.url = cUrl;
			ModuleBuilder.requestElements.callbacks = {success: callback, failure: ModuleBuilder.failed};


            if (typeof(showLoading) == 'undefined' || showLoading == true) {
                ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING_PAGE'));
            }

            Connect.asyncRequest(
                ModuleBuilder.requestElements.method,
                ModuleBuilder.requestElements.url,
                ModuleBuilder.requestElements.callbacks,
                SUGAR.util.paramsToUrl(postFields)
            );
		},
		refreshGlobalDropDown: function(o){
			// just clear the callLock; the convention is that this is done in a handler rather than in updateContent
			ModuleBuilder.callLock = false;
			ModuleBuilder.updateContent(o);
		},
		refreshDropDown: function(){
            var selected = ModuleBuilder.refreshDD_name;
            ModuleBuilder.asyncRequest(
                'module=ModuleBuilder&action=refreshDropDown&view_package=' + ModuleBuilder.MBpackage
                    + '&view_module=' + ModuleBuilder.module,
                function (xhr) {
                    var options = JSON.parse(xhr.responseText);
                    var $dropdown = $("#options").empty();
                    $.each(options, function(_, option) {
                        var $option = $("<option/>").val(option).text(option);
                        if (option == selected) {
                            $option.prop("selected", true);
                        }
                        $dropdown.append($option);
                    });
                    $dropdown.change();
                    ModuleBuilder.tabPanel.get("activeTab").close();
                    ajaxStatus.hideStatus();
                }
            );
		},
		dropdownChanged: function(value){
			var select = document.getElementById('default[]').options;
			while(select.length > 0) {
				select[0] = null;
			}
			ModuleBuilder.asyncRequest(
				'module=ModuleBuilder&action=get_app_list_string&key=' + value +
				'&view_package=' + ModuleBuilder.MBpackage + '&view_module=' + ModuleBuilder.module,
				ModuleBuilder.dropdownChangedCallback
			);
		},
		dropdownChangedCallback : function(o) {
			var ajaxResponse = YAHOO.lang.JSON.parse(o.responseText);
			var select = document.getElementById('default[]').options;
			var count = 0;
			for (var key in ajaxResponse) {
				select[count] = new Option(ajaxResponse[key], key);
				count++;
			}
			ajaxStatus.flashStatus(SUGAR.language.get('app_strings', 'LBL_REQUEST_PROCESSED'), 2000);
		},
		setSelectedOption : function (sel, option)
		{
			var sel = Dom.get(sel);
			for (var i = 0; i < sel.options.length; i++)
			{
				if(sel.options[i].value == option) {
					sel.selectedIndex = i;
					return true;
				}
			}
			return false;
		}
		,moduleLoadFormula: function(formula, targetId, returnType){
            if (!targetId)
                targetId = "formula";
            if(!returnType)
                returnType = "";

            if (!ModuleBuilder.formulaEditorWindow)
                ModuleBuilder.formulaEditorWindow = new YAHOO.SUGAR.AsyncPanel('formulaBuilderWindow', {
                    width: 512,
                    draggable: true,
                    close: true,
                    constraintoviewport: true,
                    fixedcenter: false,
                    script: true,
                    modal: true
                });
            var win = ModuleBuilder.formulaEditorWindow;
            win.setHeader(SUGAR.language.get("ModuleBuilder", "LBL_FORMULA_BUILDER"));
            win.setBody("loading...");
            win.render(document.body);
            win.params = {
                module:"ExpressionEngine",
                action:"editFormula",
                targetField: targetId,
                returnType: returnType,
                loadExt:false,
                embed: true,
                targetModule:ModuleBuilder.module,
                package:ModuleBuilder.MBpackage,
                formula:encodeURIComponent(YAHOO.lang.JSON.stringify(formula))
            };
            win.load('', "POST", function ()
            {
                ModuleBuilder.formulaEditorWindow.center();
                SUGAR.util.evalScript(ModuleBuilder.formulaEditorWindow.body.innerHTML);
            }, ModuleBuilder.paramsToUrl(win.params));
            win.show();
            win.center();
        },
        editVisibilityGrid: function(targetId, parent_options, child_options){
            if (!targetId)
                targetId = "visibility_grid";

            if (!ModuleBuilder.visGridWindow)
                ModuleBuilder.visGridWindow = new YAHOO.SUGAR.AsyncPanel('visGridWindow', {
                    draggable: true,
                    close: true,
                    constraintoviewport: true,
                    fixedcenter: false,
                    script: true,
                    modal: true,
                    buttons: [
                        {text:"Save", handler:function(){

                        }, isDefault:true },
                        { text:"Cancel",  handler:function(){
                            ModuleBuilder.visGridWindow.hide()}
                        }
                    ]
                });
            var win = ModuleBuilder.visGridWindow;
            win.setHeader(SUGAR.language.get("ModuleBuilder", "LBL_VISIBILITY_EDITOR"));
            win.setBody("loading...");
            win.render(document.body);
            win.params = {
                module:"ModuleBuilder",
                action:"depdropdown",
                targetModule: ModuleBuilder.module,
                package: ModuleBuilder.MBpackage,
                parentList: parent_options,
                childList: child_options,
                targetId:targetId,
                mode:2,
                mapping: Dom.get(targetId).value,
                csrf_token: SUGAR.csrf.form_token
            };
            win.load('', 'POST', function() {
                SUGAR.util.evalScript(win.body.innerHTML);
                //firefox will ignore the left panel size, so we need to manually force the windows height and width
                win.body.style.height = "570px";
                win.body.style.minWidth = "880px";
                win.center();
            },
                //POST parameters
                ModuleBuilder.paramsToUrl(win.params)
            );
            win.show();
            win.center();
        },
        disableCalculatedControlledElems: function(disable) {
        	// Get the massupdate chechbox if its available
        	var massupdate  = Dom.get('massupdate');
			//Getting the default value field is tricky as it can have multiple different ID's
			var defaultVal = false;
			for(var i in {'default':"", 'int_default':"", 'default[]':""}) {
				if (Dom.get(i)) {
					defaultVal = Dom.get(i); 
					break;
				}
			}
			
			// Turn massupdate off now
			if (massupdate) {
				// Uncheck the massupdate checkbox
				massupdate.checked = false;
				massupdate.disabled = disable;

				// Unset the default value
				if (defaultVal) {
					// Handle "unsetting" of default values
					if (defaultVal.tagName == 'SELECT') {
						defaultVal.selectedIndex = 0;
					} else if (defaultVal.tagName == 'INPUT') {
						if (defaultVal.type == 'checkbox') {
							defaultVal.checked = false;
						} else {
							defaultVal.value = '';
						}
					}
					defaultVal.disabled = disable;
				}
			}
        },
        toggleParent: function(enable){
            if (typeof(enable) == 'undefined') {
                enable = Dom.get('has_parent').checked;
            }
            var display = enable ? "" : "none";
            Dom.setStyle("visGridRow", "display", display);
            if(Dom.get('has_parent')){
                Dom.get('has_parent').value = enable;
            }
        },
        toggleBoost: function() {
            var display = "";
            if (Dom.get("fts_field_config").value < 2) {
                display = "none";
            }
            Dom.setStyle("ftsFieldBoostRow", "display", display);
        },
		toggleCF: function(enable) {
            if (typeof(enable) == 'undefined') {
                enable = Dom.get('calculated').checked;
            }
            var display = enable ? "" : "none";
			Dom.setStyle("formulaRow", "display", display);
			Dom.setStyle("enforcedRow", "display", display);
            if(Dom.get('calculated')){
			    Dom.get('calculated').value = enable;
            }
            this.toggleEnforced(enable);
        },
        toggleEnforced: function(enable) {
            if (typeof enable == "undefined")
                enable = Dom.get("enforced").checked && Dom.get('calculated').checked;
            var reportable = Dom.get('reportable');
            var importable = Dom.get('importable');
            var duplicate  = Dom.get('duplicate_merge');
            var disable = enable ? true : "";
            if (reportable) reportable.disabled = disable;
            if(enable)
            {
            	if (duplicate)ModuleBuilder.setSelectedOption(duplicate, '0')

            	if (importable)ModuleBuilder.setSelectedOption(importable, 'false');
            }
            if (importable)importable.disabled = disable;
            if (duplicate)duplicate.disabled = disable;
			this.toggleDateTimeDefalutEnabled(disable);
			// This handles both massupdate and default values for calculated 
			// checkbox checks
			ModuleBuilder.disableCalculatedControlledElems(disable);
            if(Dom.get("enforced")){
                Dom.get("enforced").value = enable;
            }
        },
		toggleDateTimeDefalutEnabled : function(disable)
		{
			if (Dom.get("defaultDate_date"))
			{
				Dom.get("defaultDate_date").disabled = disable;
				Dom.get("defaultTime_hours").disabled = disable;
				Dom.get("defaultTime_minutes").disabled = disable;
                if (Dom.get("defaultTime_meridiem"))
			    {
				    Dom.get("defaultTime_meridiem").disabled = disable;
                }
			}
		},
        toggleDF: function(enable, query) {
            if (typeof(enable) == 'undefined' || enable === null) {
                enable = Dom.get('dependent').checked;
            }
            var display = enable ? "" : "none";
            Dom.setStyle('visFormulaRow', 'display', display);
            //If a query was passed in, we need to enble/disable elements that match the query as well
            if (query)
                $(query).css("display", display);
            Dom.get('dependency').disabled = !enable;
			Dom.get('dependent').value = enable;
        },
        //We can only have a formula or a vis_grid. Before we save we need to clear the one we aren't using
        validateDD: function() {
            if ($('#depTypeSelect').val() != "parent")
                $("#visibility_grid").val("");
            if ($('#depTypeSelect').val() != "formula")
                $("#dependency").val("");
            return true;
        },
        addToHead: function(src, type){
            var tag, srcKey, typeTag, rel;
            type = type ? type : "js";
            if (type == "js") {
                tag = "script";
                srcKey = "src";
                typeTag = "text/javascript";
            } else if (type == "css")
            {
                tag = "link";
                srcKey = "href";
                typeTag = "text/css";
                rel = "stylesheet";
            } else {
                //Invalid or unknown type
                return;
            }
            var headElem = document.getElementsByTagName('head')[0];
            var tmpElem = document.createElement(tag);
            tmpElem.type = typeTag;
            tmpElem[srcKey] = src;
            if (rel)
                tmpElem.rel = rel;
            headElem.appendChild(tmpElem);
        },
        enforceAuditPii: function() {
            var piiCheckBox = document.getElementById("piiCheckbox");
            var auditCheckBox = document.getElementById("auditedCheckbox");
            if (piiCheckBox && auditCheckBox) {
                if (piiCheckBox.checked) {
                    auditCheckBox.checked = true;
                    auditCheckBox.disabled = true;
                    auditCheckBox.value = 1;
                } else {
                    auditCheckBox.disabled = false;
                }
            }
        }
        ,switchLayoutRole: function(element) {
            var $select = $(element);
            var $input = $('input[name="role"]');
            var previousRole = $input.val();
            var role = $select.val();
            var params = ModuleBuilder.urlToParams(ModuleBuilder.contentURL);
            params.role = role;
            var url = ModuleBuilder.paramsToUrl(params);
            ModuleBuilder.getContent(
                url,
                function(r) {
                    $input.val(role);
                    ModuleBuilder.updateContent(r);
                }, function() {
                    $select.val(previousRole);
                }
            );
        },
        copyLayoutFromRole: function() {
            var dialog = ModuleBuilder.getCopyLayoutDialog();
            dialog.show();
        },
        getCopyLayoutDialog: function() {
            if (ModuleBuilder.copyLayoutDialog) {
                return ModuleBuilder.copyLayoutDialog;
            }

            var dialog = new YAHOO.widget.SimpleDialog("copy-from-dialog", {
                fixedcenter: true,
                modal: true,
                draggable: false,
                buttons: [{
                    text: SUGAR.language.get("ModuleBuilder", "LBL_BTN_COPY"),
                    handler: function() {
                        var role = $("input[name=role]").val();
                        var source = $("#copy-from-options").val();

                        var originalUrl = ModuleBuilder.contentURL;

                        var params = ModuleBuilder.urlToParams(ModuleBuilder.contentURL);
                        params.action = "copyLayout";
                        params.source = source;
                        var url = ModuleBuilder.paramsToUrl(params);

                        var dialog = this;
                        ModuleBuilder.getContent(url, function() {
                            ModuleBuilder.updateContent.apply(this, arguments);
                            ModuleBuilder.state.markAsDirty();
                            dialog.cancel();
                        });
                        ModuleBuilder.contentURL = originalUrl;
                    }
                }, {
                    text: SUGAR.language.get("ModuleBuilder", "LBL_BTN_CANCEL"),
                    isDefault:true,
                    handler: function(){
                        this.cancel();
                    }
                }]
            });

            var contents = document.getElementById("copy-from-contents");
            contents.style.display = "";
            dialog.setHeader(SUGAR.language.get("ModuleBuilder", "LBL_HEADER_COPY_FROM_LAYOUT"));
            dialog.setBody(contents);
            dialog.render(document.body);

            ModuleBuilder.copyLayoutDialog = dialog;
            return dialog;
        }
	};
	ModuleBuilder.buttons = {};
	ModuleBuilder.selected = {};
	ModuleBuilder.callLock = false;
}
})();
