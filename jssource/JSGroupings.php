<?php
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
/*
 * This is the array that is used to determine how to group/concatenate js files together
 * The format is to define the location of the file to be concatenated as the array element key
 * and the location of the file to be created that holds the child files as the array element value.
 * So: $original_file_location => $Concatenated_file_location
 *
 * If you wish to add a grouping that contains a file that is part of another group already,
 * add a '.' after the .js in order to make the element key unique.  Make sure you pare the extension out
 *
 */
        if(!function_exists('getSubgroupForTarget'))
        {
            /**
             * Helper to allow for getting sub groups of combinations of includes that are likely to be required by
             * many clients (so that we don't end up with duplication from client to client).
             * @param  string $subGroup The sub-group
             * @param  string $target The target file to point to e.g. '<app>/<app>.min.js',
             * @return array array of key vals where the keys are source files and values are the $target passed in.
             */
            function getSubgroupForTarget ($subGroup, $target) {
                // Add more sub-groups as needed here if client include duplication in $js_groupings
                switch ($subGroup) {
                    case 'bootstrap':
                        return array(
                            'include/javascript/twitterbootstrap/transition.js'  => $target,
                            'include/javascript/twitterbootstrap/bootstrap-button.js'  => $target,
                            'include/javascript/twitterbootstrap/bootstrap-tooltip.js' => $target,
                            'include/javascript/twitterbootstrap/bootstrap-dropdown.js'=>  $target,
                            'include/javascript/twitterbootstrap/bootstrap-popover.js' => $target,
                            'include/javascript/twitterbootstrap/bootstrap-modal.js'   => $target,
                            'include/javascript/twitterbootstrap/bootstrap-alert.js'   => $target,
                            'include/javascript/twitterbootstrap/bootstrap-datepicker.js' => $target,
                            'include/javascript/twitterbootstrap/bootstrap-tab.js'     => $target,
                            'include/javascript/twitterbootstrap/bootstrap-collapse.js'   => $target,
                            'include/javascript/twitterbootstrap/bootstrap-colorpicker.js' => $target,
                        );
                        break;
                    case 'bootstrap_core':
                        return array(
                            'include/javascript/jquery/bootstrap/bootstrap.min.js'       =>   $target,
                            'include/javascript/jquery/jquery.popoverext.js'             =>   $target,
                        );
                        break;
                    case 'jquery_core':
                        return array (
                            'include/javascript/jquery/jquery-min.js'               =>  $target,
                            'include/javascript/jquery/jquery-ui-min.js'            =>  $target,
                            'include/javascript/jquery/jquery.json-2.3.js'          =>  $target,
                            'include/javascript/jquery/jquery-migrate-1.2.1.min.js' =>  $target,
                        );
                        break;
                    case 'jquery_menus':
                        return array(
                            'include/javascript/jquery/jquery.hoverIntent.js'            =>   $target,
                            'include/javascript/jquery/jquery.hoverscroll.js'            =>   $target,
                            'include/javascript/jquery/jquery.hotkeys.js'                =>   $target,
                            'include/javascript/jquery/jquery.tipTip.js'              	 =>   $target,
                            'include/javascript/jquery/jquery.sugarMenu.js'              =>   $target,
                            'include/javascript/jquery/jquery.highLight.js'              =>   $target,
                            'include/javascript/jquery/jquery.showLoading.js'            =>   $target,
                            'include/javascript/jquery/jquery.jstree.js'              	 =>   $target,
                            'include/javascript/jquery/jquery.dataTables.min.js'         =>   $target,
                            'include/javascript/jquery/jquery.dataTables.customSort.js'  =>   $target,
                            'include/javascript/jquery/jquery.jeditable.js'              =>   $target,
                        );
                        break;
                    default:
                        break;
                }
            }
        }
        $calendarJSFileName = file_exists('custom/include/javascript/calendar.js') ?
            'custom/include/javascript/calendar.js' : 'include/javascript/calendar.js';
        $js_groupings = array(
           $sugar_grp1 = array(
                //scripts loaded on first page
                'sidecar/node_modules/underscore/underscore-min.js' => 'include/javascript/sugar_grp1.js',
                'include/javascript/sugar_3.js'         => 'include/javascript/sugar_grp1.js',
                'include/javascript/ajaxUI.js'          => 'include/javascript/sugar_grp1.js',
                'include/javascript/cookie.js'          => 'include/javascript/sugar_grp1.js',
                'include/javascript/menu.js'            => 'include/javascript/sugar_grp1.js',
                $calendarJSFileName                     => 'include/javascript/sugar_grp1.js',
                'include/javascript/quickCompose.js'    => 'include/javascript/sugar_grp1.js',
                'include/javascript/yui/build/yuiloader/yuiloader-min.js' => 'include/javascript/sugar_grp1.js',
                //HTML decode
                'include/javascript/phpjs/license.js' => 'include/javascript/sugar_grp1.js',
                'include/javascript/phpjs/get_html_translation_table.js' => 'include/javascript/sugar_grp1.js',
                'include/javascript/phpjs/html_entity_decode.js' => 'include/javascript/sugar_grp1.js',
                'include/javascript/phpjs/htmlentities.js' => 'include/javascript/sugar_grp1.js',
	            //Expression Engine
                'sidecar/lib/sugarlogic/expressions.js'        => 'include/javascript/sugar_grp1.js',
	            'include/Expressions/javascript/dependency.js' => 'include/javascript/sugar_grp1.js',
                'include/EditView/Panels.js'   => 'include/javascript/sugar_grp1.js',
            ),
			// solo jquery libraries
			$sugar_grp_jquery_core = getSubgroupForTarget('jquery_core', 'include/javascript/sugar_grp1_jquery_core.js'),

            //bootstrap
            $sugar_grp_bootstrap = getSubgroupForTarget('bootstrap_core', 'include/javascript/sugar_grp1_bootstrap.js'),

            //jquery for moddule menus
            $sugar_grp_jquery_menus = getSubgroupForTarget('jquery_menus', 'include/javascript/sugar_grp1_jquery_menus.js'),

            //core app jquery libraries
			$sugar_grp_jquery = array_merge(getSubgroupForTarget('jquery_core', 'include/javascript/sugar_grp1_jquery.js'),
                getSubgroupForTarget('bootstrap_core', 'include/javascript/sugar_grp1_jquery.js'),
                getSubgroupForTarget('jquery_menus', 'include/javascript/sugar_grp1_jquery.js')
            ),

           $sugar_field_grp = array(
               'include/SugarFields/Fields/Collection/SugarFieldCollection.js' => 'include/javascript/sugar_field_grp.js',
               'include/SugarFields/Fields/Teamset/Teamset.js' => 'include/javascript/sugar_field_grp.js',
               'include/SugarFields/Fields/Datetimecombo/Datetimecombo.js' => 'include/javascript/sugar_field_grp.js',
           ),
            $sugar_grp1_yui = array(
			//YUI scripts loaded on first page
            'include/javascript/yui3/build/yui/yui-min.js'              => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui3/build/loader/loader-min.js'        => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/yahoo/yahoo-min.js'           => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/dom/dom-min.js'               => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/yahoo-dom-event/yahoo-dom-event.js'
			    => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/event/event-min.js'           => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/logger/logger-min.js'         => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/animation/animation-min.js'   => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/connection/connection-min.js' => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/dragdrop/dragdrop-min.js'     => 'include/javascript/sugar_grp1_yui.js',
            //Ensure we grad the SLIDETOP custom container animation
            'include/javascript/yui/build/container/container-min.js'   => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/element/element-min.js'       => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/tabview/tabview-min.js'       => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/selector/selector.js'     => 'include/javascript/sugar_grp1_yui.js',
            //This should probably be removed as it is not often used with the rest of YUI
            'include/javascript/yui/ygDDList.js'                        => 'include/javascript/sugar_grp1_yui.js',
            //YUI based quicksearch
            'include/javascript/yui/build/datasource/datasource-min.js' => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/json/json-min.js'             => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/autocomplete/autocomplete-min.js'=> 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/quicksearch.js'                         => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/menu/menu-min.js'             => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/sugar_connection_event_listener.js'     => 'include/javascript/sugar_grp1_yui.js',
			'include/javascript/yui/build/calendar/calendar.js'     => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/history/history.js'     => 'include/javascript/sugar_grp1_yui.js',
            'include/javascript/yui/build/resize/resize-min.js'     => 'include/javascript/sugar_grp1_yui.js',
            ),

            $sugar_grp_yui_widgets = array(
			//sugar_grp1_yui must be laoded before sugar_grp_yui_widgets
            'include/javascript/yui/build/datatable/datatable-min.js'   => 'include/javascript/sugar_grp_yui_widgets.js',
            'include/javascript/yui/build/treeview/treeview-min.js'     => 'include/javascript/sugar_grp_yui_widgets.js',
			'include/javascript/yui/build/button/button-min.js'         => 'include/javascript/sugar_grp_yui_widgets.js',
            'include/javascript/yui/build/calendar/calendar-min.js'     => 'include/javascript/sugar_grp_yui_widgets.js',
			'include/javascript/sugarwidgets/SugarYUIWidgets.js'        => 'include/javascript/sugar_grp_yui_widgets.js',
            // Include any Sugar overrides done to YUI libs for bugfixes
            'include/javascript/sugar_yui_overrides.js'   => 'include/javascript/sugar_grp_yui_widgets.js',
            ),

			$sugar_grp_yui_widgets_css = array(
				"include/javascript/yui/build/fonts/fonts-min.css" => 'include/javascript/sugar_grp_yui_widgets.css',
				"include/javascript/yui/build/treeview/assets/skins/sam/treeview.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
				"include/javascript/yui/build/datatable/assets/skins/sam/datatable.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
				"include/javascript/yui/build/container/assets/skins/sam/container.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
                "include/javascript/yui/build/button/assets/skins/sam/button.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
				"include/javascript/yui/build/calendar/assets/skins/sam/calendar.css"
					=> 'include/javascript/sugar_grp_yui_widgets.css',
			),

            $sugar_grp_yui2 = array(
            //YUI combination 2
            'include/javascript/yui/build/dragdrop/dragdrop-min.js'    => 'include/javascript/sugar_grp_yui2.js',
            'include/javascript/yui/build/container/container-min.js'  => 'include/javascript/sugar_grp_yui2.js',
            ),

            //Grouping for emails module.
            $sugar_grp_emails = array(
            'include/javascript/yui/ygDDList.js' => 'include/javascript/sugar_grp_emails.js',
            'include/SugarEmailAddress/SugarEmailAddress.js' => 'include/javascript/sugar_grp_emails.js',
            'include/SugarFields/Fields/Collection/SugarFieldCollection.js' => 'include/javascript/sugar_grp_emails.js',
            'include/SugarRouting/javascript/SugarRouting.js' => 'include/javascript/sugar_grp_emails.js',
            'include/SugarDependentDropdown/javascript/SugarDependentDropdown.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/InboundEmail/InboundEmail.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/EmailUIShared.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/EmailUI.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/EmailUICompose.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/ajax.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/grid.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/init.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/complexLayout.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/composeEmailTemplate.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/displayOneEmailTemplate.js' => 'include/javascript/sugar_grp_emails.js',
            'modules/Emails/javascript/viewPrintable.js' => 'include/javascript/sugar_grp_emails.js',
            'include/javascript/quicksearch.js' => 'include/javascript/sugar_grp_emails.js',

            ),

            //Grouping for the quick compose functionality.
            $sugar_grp_quick_compose = array(
            'include/javascript/jsclass_base.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'include/javascript/jsclass_async.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/vars.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'include/SugarFields/Fields/Collection/SugarFieldCollection.js' => 'include/javascript/sugar_grp_quickcomp.js', //For team selection
            'modules/Emails/javascript/EmailUIShared.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/ajax.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/grid.js' => 'include/javascript/sugar_grp_quickcomp.js', //For address book
            'modules/Emails/javascript/EmailUICompose.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/composeEmailTemplate.js' => 'include/javascript/sugar_grp_quickcomp.js',
            'modules/Emails/javascript/complexLayout.js' => 'include/javascript/sugar_grp_quickcomp.js',
            ),
           $sugar_grp_sidecar = array_merge(
               array(
                   'include/javascript/phpjs/base64_encode.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/jquery/jquery-ui-min.js' => 'include/javascript/sugar_sidecar.min.js',
               ),
               getSubgroupForTarget('bootstrap', 'include/javascript/sugar_sidecar.min.js'),
               array(
                   // D3 (version 4.x) library custom bundle
                   // with only modules for main sugar chart types
                   'include/javascript/d3-sugar/d3-sugar.min.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/sucrose/sucrose.min.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/SugarCharts/sucrose/js/sugarCharts.js' => 'include/javascript/sugar_sidecar.min.js',
                   // D3 (version 3.x) entire library
                   'include/javascript/nvd3/lib/d3.min.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/nvd3/nv.d3.min.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/sugar7/error.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/sugar7/touch.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/select2/select2.js' => 'include/javascript/sugar_sidecar.min.js',
                   //To fix some issues on select2 plugin.
                   'include/javascript/sugar7/plugins/Select2.js'  => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/jquery/jquery.timepicker.js'=> 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/jquery/jquery.jstree.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/jquery/jstree.state.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/jquery/jquery.popoverext.js'           => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/jquery/jquery.nouislider.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/nprogress/nprogress.js' => 'include/javascript/sugar_sidecar.min.js',

                   'include/javascript/select2/language.js' => 'include/javascript/sugar_sidecar.min.js',
                   'sidecar/node_modules/moment/min/locales.min.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/favicon.js' => 'include/javascript/sugar_sidecar.min.js',

                   //Expression Engine
                   'sidecar/lib/sugarlogic/expressions.js'              => 'include/javascript/sugar_sidecar.min.js',
                   'sidecar/lib/sugarlogic/sidecarExpressionContext.js' => 'include/javascript/sugar_sidecar.min.js',

                    // Plugins for Sugar 7.
                    'include/javascript/sugar7/plugins/FieldErrorCollection.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Dashlet.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Connector.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Audit.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/CommittedDeleteWarning.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/FindDuplicates.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/MergeDuplicates.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/DragdropAttachments.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/FileDragoff.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Dropdown.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ListColumnEllipsis.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/MassCollection.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Pii.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ReorderableColumns.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/jquery/jquery.rtl-scroll.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/jquery/sugar.resizableColumns.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ResizableColumns.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ToggleMoreLess.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'modules/Contacts/clients/base/plugins/ContactsPortalMetadataFilter.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'modules/pmse_Inbox/clients/base/plugins/ProcessActions.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/HistoricalSummary.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/MetadataEventDriven.js' =>
                        'include/javascript/sugar_sidecar.min.js',
                    //load SFA specific plugins. Remove this in favor of a custom plugin loader.
                    'modules/Forecasts/clients/base/plugins/DisableDelete.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'modules/Forecasts/clients/base/plugins/DisableMassDelete.js'  => 'include/javascript/sugar_sidecar.min.js',
                   'modules/Quotes/clients/base/plugins/QuotesLineNumHelper.js'  => 'include/javascript/sugar_sidecar.min.js',
                   'modules/Quotes/clients/base/plugins/QuotesViewSaveHelper.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/MassQuote.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Taggable.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/RelativeTime.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ErrorDecoration.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ClickToEdit.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/GridBuilder.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ListDisableSort.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Editable.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ListEditable.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ListRemoveLinks.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/File.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/FieldDuplicate.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/LinkedModel.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ToggleVisibility.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Pagination.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ShortcutSession.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/sugar7/plugins/CanvasDataRenderer.js' => 'include/javascript/sugar_sidecar.min.js',
                    'modules/Categories/clients/base/plugins/JSTree.js'  => 'include/javascript/sugar_sidecar.min.js',
                    // Support Portal features for Sugar7
                    'modules/Contacts/clients/base/lib/bean.js' => 'include/javascript/sugar_sidecar.min.js',
                    'modules/Categories/clients/base/plugins/NestedSetCollection.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/DirtyCollection.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Prettify.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Chart.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/EmailClientLaunch.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'modules/KBContents/clients/base/plugins/KBContent.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'modules/Teams/clients/base/plugins/TbACLs.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'modules/KBContents/clients/base/plugins/KBNotify.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/Tinymce.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/VirtualCollection.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/SearchForMore.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/EditAllRecurrences.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/AddAsInvitee.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/DragdropSelect2.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/sugar7/plugins/ReminderTimeDefaults.js' => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/sugar7/plugins/CollectionFieldLoadAll.js'  => 'include/javascript/sugar_sidecar.min.js',
                   'include/javascript/sugar7/plugins/EmailParticipants.js'  => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/fuse/fuse.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/tinymce4/jquery.tinymce.min.js' => 'include/javascript/sugar_sidecar.min.js',
                    'include/javascript/mousetrap/mousetrap.min.js' => 'include/javascript/sugar_sidecar.min.js',
                )
           ),

            $sugar_grp_sugar7 = array(
                'include/javascript/sugar7.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/tutorial.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/bwc.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/utils.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/utils-filters.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/utils-search.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/field.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/hacks.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/alert.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/language.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/help.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/hbs-helpers.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/underscore-mixins.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/filter-analytics.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/metadata-manager.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/sweetspot.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/tooltip.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/import-export-warnings.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/shortcuts.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/accessibility/accessibility.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/accessibility/click.js' => 'include/javascript/sugar_grp7.min.js',
                'include/javascript/sugar7/accessibility/label.js' => 'include/javascript/sugar_grp7.min.js',
            ),

            $sugar_grp_portal2 = array(
                'portal2/lib/sugar.searchahead.js' => 'portal2/portal.min.js',
                'portal2/error.js' => 'portal2/portal.min.js',
                'portal2/user.js' => 'portal2/portal.min.js',
                'portal2/portal.js' => 'portal2/portal.min.js',
            ),

            $sugar_grp_sugar7_portal2 = array(
                'include/javascript/sugar7/tutorial.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/utils.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/utils-filters.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/field.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/hacks.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/alert.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/hbs-helpers.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/language.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/accessibility/accessibility.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/accessibility/click.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/accessibility/label.js' => 'portal2/sugar_portal.min.js',
                'custom/include/javascript/voodoo.js' => 'portal2/sugar_portal.min.js',
                'include/javascript/sugar7/shortcuts.js' => 'portal2/sugar_portal.min.js',
            ),

            $pmse_designer = array(
                'include/javascript/pmse/tree.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/drag_behavior.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/drop_behavior.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/shapes.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/flow.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/command.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/command_annotation_resize.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/command_single_property.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/container_behavior.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/resize_behavior.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/project.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/canvas.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/marker.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/event.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/gateway.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/activity.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/artifact.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/properties_grid.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/artifact_resize_behavior.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/command_default_flow.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/command_connection_condition.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/command_reconnect.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/pmtree.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/progrid.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/ErrorMessageItem.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/ListContainer.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/ErrorListItem.js' => 'include/javascript/pmse.designer.min.js',
                'include/javascript/pmse/ErrorListPanel.js' => 'include/javascript/pmse.designer.min.js',
                //including next files to original file to have one request only
                'include/javascript/pmse/designer.js' => 'include/javascript/pmse.designer.min.js',
            ),

            $pmse_libraries = array(
                'include/javascript/pmse/lib/jquery.layout-latest.js' => 'include/javascript/pmse.libraries.min.js',
            ),

            $pmse_jcore = array(
                'include/javascript/pmse/lib/jcore.js' => 'include/javascript/pmse.jcore.min.js',
            ),

            $pmse_utils = array(
                'include/javascript/pmse/utils.js' => 'include/javascript/pmse.utils.min.js',
            ),

            $pmse_ui = array(
                'include/javascript/pmse/ui/utils.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/style.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/arraylist.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/base.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/modal.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/proxy.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/element.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/container.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/window.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/action.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/menu.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/checkbox_item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/separator_item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/menu_item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/layout.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/tooltip.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/form.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/field.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/validator.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/validator_types.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/field_types.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/button.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/rest_proxy.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/sugar_proxy.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/item_matrix.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/item_updater.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/html_panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/store.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/grid.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/history_panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/log_field.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/message_panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/updater_field.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/note_panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/reassign_field.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/reassign_form.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/data_item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/single_item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/list_item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/item_container_control.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/field_panel_item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/field_panel_button.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/field_panel_button_group.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/collapsible_panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/form_panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/list_panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/multiple_collapsible_panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/field_panel_item_factory.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/field_panel.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/multiple_item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/email_picker.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/expression_builder2.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/criteria_field.js' => 'include/javascript/pmse.ui.min.js',
                // Since there won't be a js for BR anymore, its files
                'include/javascript/pmse/ui/expression_container.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/decision_table.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/close_list_item.js' => 'include/javascript/pmse.ui.min.js',
                'include/javascript/pmse/ui/dropdown_selector.js' => 'include/javascript/pmse.ui.min.js',
                // files under the modules/pmse_Inbox/js/ folder
                'modules/pmse_Inbox/js/formAction.js' => 'include/javascript/pmse.ui.min.js',
            ),

            //Grouping for TBA configuration.
            $sugar_grp_tba = array(
                'modules/Teams/javascript/TBAConfiguration.js' => 'include/javascript/sugar_grp_tba.js',
            ),
        );

    /**
     * Check for custom additions to this code
     */

    if(!class_exists('SugarAutoLoader')) {
        // This block is required because this file could be called from a non-entrypoint (such as jssource/minify.php).
        require_once('include/utils/autoloader.php');
        SugarAutoLoader::init();
    }

    foreach(SugarAutoLoader::existing("custom/jssource/JSGroupings.php", SugarAutoLoader::loadExtension("jsgroupings")) as $file) {
        require $file;
    }
