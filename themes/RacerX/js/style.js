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


 //window resize event handers
//$(window).resize(function() {
//	SUGAR.themes.resizeSearch();
//	SUGAR.themes.resizeMenu();
//});



$(document).ready(function(){
//	SUGAR.themes.resizeSearch();
//    if(SUGAR.App == undefined) {
//	    SUGAR.themes.resizeMenu();
//    }
	//setup for action menus
	SUGAR.themes.actionMenu();
	//setup event handler for search results
//	SUGAR.themes.searchResults();
    //setup footer for toggling
//    SUGAR.themes.toggleFooter();
    //initialize global tooltips
//	SUGAR.themes.globalToolTips();
//
//    SUGAR.themes.currentMenuIndex =  $("#themeTabGroupMenu_All" ).children("li").index($("#themeTabGroupMenu_All").children("li.current"));
//
//    $('body').click(function(e) {
//    	if($(e.target).closest('#dcmenuSearchDiv').length == 0 && !$("#SpotResults").parent().hasClass("dccontent") && $("#dcgscontent").css("visibility") != "visible"){
//            SUGAR.themes.clearSearch();
//        }
//    });
});

SUGAR.themes = SUGAR.namespace("themes");
//if(Get_Cookie("sugar_theme_menu_load") == null) {
//	Set_Cookie('sugar_theme_menu_load','false',30,'/','','');
//}
SUGAR.append(SUGAR.themes, {
//    setRightMenuTab: function(el, params) {
//
//        var extraMenu = "#moduleTabExtraMenu"+sugar_theme_gm_current;
//        var moreItemsContainer = "#moduleTabMore"+sugar_theme_gm_current;
//
//		//Check if the menu we want to show is in the more menu
//		if($(el).parents().is(extraMenu)) {
//            var menuNode = $(el).parent();
//            menuNode.remove();
//            $("#moduleTabExtraMenu" + sugar_theme_gm_current).before(menuNode);
//            menuNode.find('ul.megamenu').css('left','0px');
//            menuNode.find('ul.megamenu').css('top','auto');
//            //kill menu and reload it so all events are assigned properly
//
//			$("#moduleList").find("a").unbind();
//			SUGAR.themes.loadModuleList();
//
//		}
//    },
//    setCurrentTab: function(params) {
//        var el = '#moduleTab_'+ sugar_theme_gm_current + params.module;
//        if ($(el) && $(el).parent()) {
//            SUGAR.themes.setRightMenuTab(el, params);
//            var currActiveTab = "#themeTabGroupMenu_"+sugar_theme_gm_current+" li.current";
//            if ($(currActiveTab)) {
//                if ($(currActiveTab) == $(el).parent()) return;
//                $(currActiveTab).removeClass("current");
//            }
//            $(el).parent().addClass("current");
//        }
//        SUGAR.themes.resizeMenu();
//        makeCall = true;
//    },
//    setModuleTabs: function(html) {
//        var el = $("#moduleList");
//        if (el) {
//            try {
//                el.remove("ul.sf-menu");
//            } catch (e) {
//                //If the menu fails to load, we can get leave the user stranded, reload the page instead.
//                window.location.reload();
//            }
//            el.html(html);
//            this.loadModuleList();
//        }
//    },
//
//    toggleQuickCreateOverFlow: function(menuName,maction) {
//    	var menuName = "#quickCreateULSubnav";
//    		if(maction == "more") {
//				$(menuName).addClass("showMore");
//				$(menuName).removeClass("showLess");
//    		} else {
//    			$(menuName).addClass("showLess");
//				$(menuName).removeClass("showMore");
//    		}
//    },
//    toggleMenuOverFlow: function(menuName,maction) {
//
//    	var menuName = "#"+menuName;
//        //Bug#52141: Prevent hiding the menu after expanding with more modules
//        var retainItem = $("#themeTabGroupMenu_" + sugar_theme_gm_current),
//            retainClass = $.fn.superfish.defaults['retainClass'];
//        retainItem.addClass(retainClass);
//        $(menuName).off("mouseout.retain").on("mouseout.retain", function(){
//            retainItem.removeClass(retainClass);
//        });
//
//	    if(maction == "more") {
//			$(menuName).addClass("showMore");
//			$(menuName).removeClass("showLess");
//
//		var viewPortHeight = $(window).height(),
//		    menuOffset = $(menuName).offset(),
//		    menuHeight = $(menuName).outerHeight(true),
//		    menuWidth = $(menuName).width(),
//		    footerHeight = $('#footer').height(),
//		    moduleListHeight = $('#moduleList').height(),
//		    filterByHeight = $(menuName).parent().find('ul.filterBy').outerHeight(true);
//			if(menuHeight + moduleListHeight > viewPortHeight-(moduleListHeight+filterByHeight+footerHeight+2+20)) {
//
//	    		$.fn.hoverscroll.params = $.extend($.fn.hoverscroll.params, {
//	    			vertical : true,
//	    			width: menuWidth,
//	    			hoverZone: 'instant',
//	    			height: viewPortHeight-(moduleListHeight+filterByHeight+footerHeight+2+20)
//	    		});
//	    		$(menuName).hoverscroll();
//	    		$(menuName).width(menuWidth);
//                $(menuName).addClass("hs-active");
//
//		    }
//		} else {
//			$(menuName).addClass("showLess");
//			$(menuName).removeClass("showMore");
//            if( $(menuName).hasClass("hs-active") ) {
//                $(menuName).removeClass("hs-active");
//			    $.fn.hoverscroll.destroy($(menuName));
//            }
//		}
//    },
//    switchMenuMode: function() {
//    	if(Get_Cookie("sugar_theme_menu_mode") == 'click') {
//    		Set_Cookie('sugar_theme_menu_mode','hover',30,'/','','');
//    	} else {
//    		Set_Cookie('sugar_theme_menu_mode','click',30,'/','','');
//    	}
//    	location.reload();
//    },
//    getMenuMode: function() {
//
//    	if(Get_Cookie("sugar_theme_menu_mode") == null) {
//    		Set_Cookie('sugar_theme_menu_mode','hover',30,'/','','');
//    	}
//
//    	if(Get_Cookie("sugar_theme_menu_mode") == 'click') {
//	    	return true;
//    	} else {
//    		return false;
//    	}
//    },
//    updateFavoritesList: function(v)
//    {
//        makeCall = v;
//    },
//    loadModuleList: function() {
//        var isRTL = (typeof rtl != "undefined") && rtl;
//        $('#moduleList ul.sf-menu').superfish({
//			delay: 50,
//			speed: 0,
//            animation: {
//                display: 'show'
//            },
//			firstOnClick: SUGAR.themes.getMenuMode(),
//			autoArrows: false,
//			dropShadows: false,
//            ignoreClass: 'megawrapper',
//            rtl: isRTL,
//			onBeforeShow: function() {
//				if($(this).hasClass("megamenu") && $(this).prev().hasClass('more') != true) {
//                    var extraMenu = "#moduleTabExtraMenu"+sugar_theme_gm_current;
//					var moduleName = $(this).prev().attr("module");
//                    //Check if the menu we want to show is in the more menu
//					if($(this).parents().is(extraMenu)) {
//						var moduleName = moduleName.replace("Overflow","");
//						var moduleName = moduleName.replace("Hidden","");
//					}
//
//					var that = $(this),
//                        shortCutWidth = that.find("ul.MMShortcuts").parent().outerWidth();
//					//ajax call for favorites
//
//                    if(that.is(".rtl, .ltr")) {
//                        that.find("ul.MMFavorites, ul.MMLastViewed").parent().hide();
//                        var _width = that.find("ul.MMShortcuts").parent().outerWidth();
//                        that.width(_width).find(".megawrapper").width(_width);
//                    } else {
//                        var _width = that.attr("width");
//
//                        if(_width) {
//                            that.find("div.show").show();
//                            that.width(_width).find(".megawrapper").width(_width);
//                        }
//                        else {
//                            that.find("ul.MMFavorites, ul.MMLastViewed").parent().removeClass("divider").hide();
//                            var _width = that.find("ul.MMShortcuts").parent().removeClass("divider").outerWidth();
//                            that.width(_width).find(".megawrapper").width(_width);
//                        }
//                    }
//                    var updateHeight = function() {
//                        var wrapperHeight = $(that).find("li div.megawrapper").height();
//                        if(wrapperHeight == 0) {
//                            setTimeout(updateHeight, 500);
//                        } else {
//                            if(wrapperHeight > $(that).find("div.megacolumn-content").height())
//                                $(that).find("div.megacolumn-content").height(wrapperHeight);
//                        }
//                    };
//					if($(this).find("ul.MMFavorites li:last a").html() == "&nbsp;" || makeCall == true) {
//
//						$.ajax({
//						  url: "index.php?module="+moduleName+"&action=favorites",
//						  success: function(json){
//						    var lastViewed = $.parseJSON(json),
//                                container = that.find("ul.MMFavorites");
//                            container.children().not(':eq(0)').remove();
//						    $.each(lastViewed, function(k,v) {
//						    	if(v.text == "none") {
//						    		v.url = "javascript: void(0);";
//						    	}
//                                container.append("<li><a href=\""+ v.url +"\">"+v.text+"</a></li>");
//						    });
//
//                            if( lastViewed.length > 0 && lastViewed[0].text != 'none') {
//                                var _width = shortCutWidth;
//
//                                if(container.parent().hasClass("show")) {
//                                    _width = 0;
//                                } else {
//                                    container.parent().show().addClass("show");
//                                }
//
//                                if(that.attr("width")) {
//                                    _width += parseInt(that.attr("width"));
//                                    that.find("ul.MMLastViewed").parent().addClass("divider");
//                                } else {
//                                    that.find("ul.MMShortcuts").parent().addClass("divider");
//                                    _width += shortCutWidth;
//                                }
//                                that.width(_width).attr("width", _width).find(".megawrapper").width(_width);
//                            }
//                              //update column heights so dividers are even
//                              that.find("ul.MMFavorites li:nth("+lastViewed.length+")").children().ready(updateHeight);
//						  }
//						});
//					}
//					//ajax call for last viewed
//					if($(this).find("ul.MMLastViewed li:last a").html() == "&nbsp;" || makeCall == true) {
//						$.ajax({
//						  url: "index.php?module="+moduleName+"&action=modulelistmenu",
//						  success: function(json){
//						    var lastViewed = $.parseJSON(json),
//                                container = that.find("ul.MMLastViewed");
//                            container.children().not(':eq(0)').remove();
//						    $.each(lastViewed, function(k,v) {
//						    	if(v.text == "none") {
//						    		v.url = "javascript: void(0);";
//						    	}
//                                container.append("<li><a href=\""+ v.url +"\">"+v.text+"</a></li>");
//						    });
//
//                              if(lastViewed.length > 0 && lastViewed[0].text != 'none') {
//                                  var _width = shortCutWidth;
//                                  if(container.parent().hasClass("show")) {
//                                      _width = 0;
//                                  } else {
//                                      container.parent().show().addClass("show");
//                                  }
//                                  if(that.attr("width")) {
//                                      _width += parseInt(that.attr("width"));
//                                      container.parent().addClass("divider");
//                                  } else {
//                                      that.find("ul.MMShortcuts").parent().addClass("divider");
//                                      _width += shortCutWidth;
//                                  }
//                                  that.width(_width).attr("width", _width).find(".megawrapper").width(_width);
//                              }
//                              //update column heights so dividers are even
//                              that.find("ul.MMLastViewed li:nth("+lastViewed.length+")").children().ready(updateHeight);
//						  }
//						});
//					}
//				makeCall = false;
//				}
//			},
//			onShow: function() {
//			}
//		});
//    },
//    editMenuMode: function() {
//
//
//
//    },
//    resizeSearch: function() {
//        if(SUGAR.themes.enableResizeSearch) {
//            $('#moduleList').attr("width", $('#moduleList').attr("width") || $('#moduleList').css("width").replace("px",""));
//
//            var searchWidth = $("#dcmenu").width() - 200
//                - $("#quickCreate").width() - $("#globalLinksModule").width() - $("#dcmenuSugarCube").width() - $("#moduleList").attr("width");
//            //maximize the proportion of the searchbox size as three quarter of the empty space among the module list and right-hand side menus
//            searchWidth *= .75;
//
//            $('#sugar_spot_search').attr("width", $('#sugar_spot_search').attr("width") || $('#sugar_spot_search').css("width").replace("px",""));
//            var originalSearchWidth = parseInt($('#sugar_spot_search').attr("width"));
//            searchWidth = (searchWidth >= originalSearchWidth) ? searchWidth : originalSearchWidth;
//            $('#sugar_spot_search_div').width(searchWidth + 25);
//            $('#sugar_spot_search').width(searchWidth);
//            $('#sugar_spot_search_div').find("section ul").width(searchWidth - 120); //resize the search result text length
//        }
//
//    },
//    resizeMenu: function () {
//        //Bug#52433: Prevent calling resizeMenu before basic theme is initialized
//        if(typeof sugar_theme_gm_current == "undefined") return;
//
//        //Bug#52650: For RTL, Keep module menus rather than search, quick menu, and etc.
//        var isRTL = (typeof rtl != "undefined") && rtl;
//        if(isRTL) {
//            $("#dcmenu").css({
//                'left' : $(window).width() < $("#dcmenu").width() ? $(window).width() - $("#dcmenu").width() : ''
//            });
//        }
//
//	    var maxMenuWidth = $("#dcmenu").width() - 100 //100px: spacing for submegamenu, padding and border lines
//            - $("#quickCreate").width() - $("#globalLinksModule").width() - $("#dcmenuSugarCube").width() - $("#dcmenuSearchDiv").width();
//		var currentModuleList = $("#themeTabGroupMenu_" + sugar_theme_gm_current),
//            menuItemsWidth = SUGAR.themes.menuItemsWidth || $("#moduleList").width(),
//            _ie_adjustment = 10,
//            menuItems = currentModuleList.children("li"),
//            menuLength = menuItems.length,
//            currentMenu = currentModuleList.children("li.current");
//        if($.browser.msie) {
//            menuItemsWidth = 0;
//            menuItems.each(function(){
//                menuItemsWidth += $(this).width();
//            });
//            menuItemsWidth += _ie_adjustment;
//            currentModuleList.width(menuItemsWidth);
//            menuItemsWidth += _ie_adjustment;
//            $("#moduleList").width(menuItemsWidth);
//
//        }
//        SUGAR.themes.menuItemsWidth = menuItemsWidth;
//
//
//        if(menuItemsWidth > maxMenuWidth){
//            var moreNode = $("#moduleTabMore" + sugar_theme_gm_current);
//            while(menuItemsWidth >= maxMenuWidth && menuLength-- > 0){
//                var menuNode = $("#moduleTabExtraMenu" + sugar_theme_gm_current).prev();
//
//                if(menuNode.hasClass("current")){
//                    menuNode = menuNode.prev();
//                }
//                if(menuNode.hasClass("home")){
//                    //menuNode = menuNode.prev();
//                }
//                menuNode.find(".megamenu").css({
//                    'left' : '',
//                    'right' : '',
//                    'top' : '',
//                    'bottom' : ''
//                });
//                if($.browser.msie) {
//                    menuNode.attr("width", menuNode.outerWidth());
//                    menuItemsWidth -= menuNode.outerWidth();
//                    moreNode.prepend(menuNode);
//                    currentModuleList.width(menuItemsWidth + _ie_adjustment);
//                    if(isRTL) {
//                        $("#moduleList").width(menuItemsWidth + _ie_adjustment);
//                    }
//                    SUGAR.themes.menuItemsWidth = menuItemsWidth;
//                } else {
//                    moreNode.prepend(menuNode);
//                    menuItemsWidth = currentModuleList.width();
//                    SUGAR.themes.menuItemsWidth = false;
//                }
//            }
//        }
//        else {
//
//            var insertNode = $("#moduleTabExtraMenu" + sugar_theme_gm_current),
//                dynamicMenuLength = insertNode.parent().children("li").length;
//            if(dynamicMenuLength <= this.currentMenuIndex+1){
//                insertNode = currentMenu;
//            }
//            while(menuItemsWidth <= maxMenuWidth && (menuLength <= max_tabs)){
//                var menuNode = $("#moduleTabMore" + sugar_theme_gm_current).children("li:first"),
//                    menuNodeWidth = ($.browser.msie) ? (menuNode.attr("width") || menuNode.width()) : menuNode.width(),
//                    menuNodeIndex = menuNode.parent().prevAll().length;
//                if((menuNode.attr("id") != undefined &&
//                    menuNode.attr("id").match(/moduleMenuOverFlow[a-zA-Z]*/)) ||
//                    (menuItemsWidth + parseInt(menuNodeWidth)) > maxMenuWidth){
//                    break;
//                }
//                menuLength++;
//
//                menuNode.find(".megamenu").css({
//                    'left' : '',
//                    'right' : '',
//                    'top' : '',
//                    'bottom' : ''
//                });
//                if($.browser.msie) {
//                    menuItemsWidth += parseInt(menuNodeWidth);
//                    insertNode.before(menuNode);
//                    SUGAR.themes.menuItemsWidth = menuItemsWidth;
//                    currentModuleList.width(menuItemsWidth + _ie_adjustment);
//                    if(isRTL) {
//                        $("#moduleList").width(menuItemsWidth + _ie_adjustment);
//                    }
//                } else {
//                    insertNode.before(menuNode);
//                    menuItemsWidth = currentModuleList.width();
//                    SUGAR.themes.menuItemsWidth = false;
//                }
//            }
//        }
//
//
//    },
//    globalToolTips: function () {
//    	// $("#moduleList .home a").tipTip({maxWidth: "auto", edgeOffset: 10});
//		$("#arrow").tipTip({maxWidth: "auto", edgeOffset: 10});
//		$("#logo").tipTip({maxWidth: "auto", edgeOffset: 10});
//		$("#quickCreateUL span").tipTip({maxWidth: "auto", edgeOffset: 10, content: SUGAR.language.translate('', 'LBL_QUICK_CREATE_TITLE')});
//        if( typeof($("#dcmenuSugarCube").attr("title")) != 'undefined' )
//        {
//		    $("#dcmenuSugarCube").tipTip({maxWidth: "auto", edgeOffset: 10});
//        }
//
//		$("#sugar_spot_search").tipTip({maxWidth: "auto", edgeOffset: 10});
//        $("#glblSearchBtn").tipTip({maxWidth: "auto", edgeOffset: 10});
//		//setup tool tips for partner integrations
//		$("#partner").children("a").each(
//            function (index) {
//                    $(this).tipTip({maxWidth: "auto", edgeOffset: 10});
//                }
//		);
//    },
//    toggleFooter: function () {
//        var isVisible = Get_Cookie('sugar_theme_footer_visible');
//        if(isVisible != null && isVisible == 'false')
//        {
//            var el = $("#arrow");
//            el.toggleClass("up");
//            SUGAR.themes.hideFooter(el);
//            el.find("i").removeClass("icon-chevron-down").addClass("icon-chevron-up");
//            $("#footer").slideToggle("fast");
//        }
//        $("#arrow").click(function(){
//            $(this).toggleClass("up");
//            if ($(this).hasClass('up')) {
//                Set_Cookie('sugar_theme_footer_visible','true',3000,false, false, false);
//                SUGAR.themes.showFooter(this);
//                $(this).find("i").removeClass("icon-chevron-up").addClass("icon-chevron-down");
//
//            } else {
//                Set_Cookie('sugar_theme_footer_visible','false',3000,false, false, false);
//                $(this).find("i").removeClass("icon-chevron-down").addClass("icon-chevron-up");
//                SUGAR.themes.hideFooter(this);
//            }
//            $("#footer").slideToggle("fast");
//        });
//    },
//    hideFooter: function(el){
//        $(el).attr("title","Show");
//        $(el).animate({bottom:'0'},200);
//        $("#arrow").tipTip({maxWidth: "auto", edgeOffset: 10});
//    },
//    showFooter: function(el){
//        $(el).attr("title","Hide");
//        $(el).animate({bottom:'11px'},200);
//        $("#arrow").tipTip({maxWidth: "auto", edgeOffset: 10});
//    },
//    searchResults: function () {
//    	firstHit = false;
//    	$("#sugar_spot_search").keypress(function(event) {
//			DCMenu.startSearch(event);
//            SUGAR.util.doWhen(function(){
//                return document.getElementById('SpotResults') != null;
//            }, SUGAR.themes.resizeSearch);
//			$('#close_spot_search').css("display","inline-block");
//
//			 if(event.charCode == 0 && !firstHit) {
//			firstHit = true;
//			 	}
//			$('#close_spot_search').click(function() {
//				SUGAR.themes.clearSearch();
//			});
//
//		});
//    },
//    clearSearch: function() {
//   		$("div#sugar_spot_search_results").hide();
//        $("#SpotResults").remove();
//		$('#close_spot_search').css("display","none");
//        $("#sugar_spot_search").val(SUGAR.language.get('app_strings', 'LBL_SEARCH'));
//        $("#sugar_spot_search").css('color', 'grey');
//		$("#sugar_spot_search").removeClass("searching");
//		$('#sugar_spot_search_div').css("left",0);
//	  	firstHit = false;
//   	},
   	actionMenu: function() {
	   	//set up any action style menus
		$("ul.clickMenu").each(function(index, node){
	  		$(node).sugarActionMenu();
	  	});

		//Fix show more/show less buttons in top action menus
		$("[class^='moduleMenuOverFlow']").each(function(index,node){
		    var jNode = $(node);
		    jNode.unbind("click");
			jNode.click(function(event){
				event.stopPropagation();
			});

		});
   	},
   	sugar_theme_gm_switch: function(groupName, groupId) {

        SUGAR.themes.current_theme = (SUGAR.themes.current_theme) ? SUGAR.themes.current_theme : sugar_theme_gm_current;
        $('ul.sf-menu:visible li').hideSuperfishUl();
        $('#moduleTabMore'+SUGAR.themes.current_theme +' li').hideSuperfishUl();
        var dcheight = $("#dcmenu").outerHeight();
        var current_menu = $('ul.sf-menu:visible');
        var target_menu = $('#themeTabGroupMenu_'+ (groupId ? groupId : groupName));
        SUGAR.themes.current_theme = sugar_theme_gm_current = groupName;
        //fliping over-and-out is added to change the global menu theme
//        $("#dcmenu").animate({
//            top: '-=' + dcheight
//        }, 200, function() {
//            current_menu.hide();
//            target_menu.show();
//            SUGAR.themes.menuItemsWidth = null;
//            SUGAR.themes.resizeSearch();
//            SUGAR.themes.resizeMenu();
//            $(this).animate({
//                top: '+=' + dcheight
//            }, 200);
//        });
        $.ajax({
	    	type: "POST",
	    	url: "index.php?module=Users&action=ChangeGroupTab&to_pdf=true",
	    	data: 'newGroup='+groupName
	    });

   	}

});

/**
 * For the module list menu
 */

//$("#moduleList").ready(function(){
//	SUGAR.themes.loadModuleList();
//});

//$(document).bind('keydown', 'Ctrl+b',function() {
//	SUGAR.themes.switchMenuMode()
//});
/**
 * For the module list menu scrolling functionality
 */
YAHOO.util.Event.onContentReady("tabListContainerTable", function()
{
    YUI({combine: true, timeout: 10000, base:"include/javascript/yui3/build/", comboBase:"index.php?entryPoint=getYUIComboFile&"}).use("anim", function(Y)
    {
        var content = Y.one('#content');
        var addPage = Y.one('#add_page');
        var tabListContainer = Y.one('#tabListContainer');
        var tabList = Y.one('#tabList');
        var dashletCtrlsElem = Y.one('#dashletCtrls');
        var contentWidth = content.get('offsetWidth');
        var dashletCtrlsWidth = (dashletCtrlsElem) ? dashletCtrlsElem.get('offsetWidth') + 10 : 10;
        var addPageWidth = (addPage) ? addPage.get('offsetWidth') + 2 : 2;
        var tabListContainerWidth = tabListContainer.get('offsetWidth');
        var tabListWidthElem = tabList.get('offsetWidth');
        var maxWidth = (contentWidth-3)-(dashletCtrlsWidth+addPageWidth+2);

        var tabListChildren = tabList.get('children');

        var tabListWidth = 0;
        for(i=0;i<tabListChildren.size();i++) {
            if(Y.UA.ie == 7) {
				tabListWidth += tabListChildren.item(i).get('offsetWidth')+2;
			} else {
				tabListWidth += tabListChildren.item(i).get('offsetWidth');
			}
        }

        if(tabListWidth > maxWidth) {
            tabListContainer.setStyle('width',maxWidth+"px");
            tabList.setStyle('width',tabListWidth+"px");
            tabListContainer.addClass('active');
        }


        var node = Y.one('#tabListContainer .yui-bd');
        var anim = new Y.Anim({
            node: node,
            to: {
                scroll: function(node) {
                    return [node.get('scrollLeft') + node.get('offsetWidth'),0]
                }
            },
            easing: Y.Easing.easeOut
        });

        var onClick = function(e) {

            var y = node.get('offsetWidth');
            if (e.currentTarget.hasClass('yui-scrollup')) {
                y = 0 - y;
            }

            anim.set('to', { scroll: [y + node.get('scrollLeft'),0] });
            anim.run();
        };

        Y.all('#tabListContainer .yui-hd a').on('click', onClick);
    });


});


