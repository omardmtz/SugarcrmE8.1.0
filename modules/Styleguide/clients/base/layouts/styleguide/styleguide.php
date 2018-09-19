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
$viewdefs['Styleguide']['base']['layout']['styleguide'] = array(
    'css_class' => 'styleguide',
    'components' => array(
        array(
            'layout' => array(
                'type' => 'base',
                'css_class' => 'row-fluid',
                'components' => array(
                    array(
                        'layout' => array(
                            'type' => 'base',
                            'css_class' => 'main-pane span12',
                            'components' => array(
                                array(
                                    'view' => 'sg-headerpane',
                                ),
                                array(
                                    'view' => array(
                                        'type' => 'styleguide',
                                        'css_class' => 'container-fluid',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    "metadata" => array(
        "chapters" => array (
            "home" => array (
                "title" => "Styleguide",
                "description" => "A guide to styling the Sugar7 User Interface",
                "index" => false,
            ),
            "docs" => array (
                "title" => "Core UI Elements",
                "description" => "Simple and flexible HTML, CSS, and Javascript for popular user interface components and interactions.",
                "index" => true,
                "sections" => array (
                    "base" => array (
                        "title" => "Base CSS",
                        "description" => "Basic HTML elements styled and enhanced with extensible classes for a fresh, consistent look and feel.",
                        "index" => true,
                        "pages" => array (
                            "typography" => array("title"=>"Typography", "description"=>"Headings, paragraphs, lists, and other inline type elements."),
                            "grid" => array("title"=>"Grid system", "description"=>"A responsive 12-column grid fluid-width layout."),
                            "icons" => array("title"=>"Icons", "description"=>"Font Awesome icon library for scalable font based icons and glyphs."),
                            "mixins" => array("title"=>"Mixins", "description"=>"Include or generate snippets of CSS with parameters."),
                            "responsive" => array("title"=>"Responsive design", "description"=>"Media queries for various devices and resolutions."),
                            "variables" => array("title"=>"Variables", "description"=>"LESS variables, HTML values, and usage guidelines."),
                            "labels" => array("title"=>"Labels", "description"=>"Label and text annotations."),
                            "edit" => array("title"=>"Edit Documentation", "description"=>"Instructions for updating Styleguide documentation."),
                            "theme" => array("title"=>"Custom Theme Variables", "description"=>"Instructions for modifying theme colors."),
                        )
                    ),
                    "forms" => array (
                        "title" => "Form Elements",
                        "description" => "Basic form elements and layouts for a consistent editing experience.",
                        "index" => true,
                        "pages" => array (
                            "fields" => array("title"=>"Sugar7 fields", "url"=>"#Styleguide/fields/index", "description"=>"Basic fields that support detail, record, and edit modes with error addons."),
                            "buttons" => array("title"=>"Buttons", "description"=>"Standard css only button styles."),
                            //TODO: remove this page or update
                            //"editable" => array("title"=>"Editable", "description"=>"Inline form edit inputs."),
                            "layouts" => array("title"=>"Form layouts", "description"=>"Customized layouts of field components."),
                            "file" => array("title"=>"File uploader", "description"=>"Stylized file upload widget."),
                            "datetime" => array("title"=>"Date-time picker", "description"=>"Lightweight date/time picker."),
                            "select2" => array("title"=>"Select2", "description"=>"jQuery plugin replacement for select boxes. It supports searching, remote data sets, and infinite scrolling of results."),
                            "jstree" => array("title"=>"jsTree", "description"=>"jQuery plugin cross browser tree component."),
                            "range" => array("title"=>"Range Slider", "description"=>"jQuery plugin range picker."),
                            "switch" => array("title"=>"Switch", "description"=>"jQuerty plugin turns check boxes into toggle switch."),
                        )
                    ),
                    "components" => array (
                        "title" => "Components",
                        "description" => "Dozens of reusable components are built in to provide navigation, alerts, popovers, and much more.",
                        "index" => true,
                        "pages" => array (
                            "alerts" => array("title"=>"Alerts", "description"=>"Styles for success, warning, and error messages."),
                            "collapse" => array("title"=>"Collapse", "description"=>"Get base styles and flexible support for collapsible components like accordions and navigation."),
                            "dropdowns" => array("title"=>"Dropdowns", "description"=>"Add dropdown menus to nearly anything with this simple plugin. Features full dropdown menu support on in the navbar, tabs, and pills."),
                            "popovers" => array("title"=>"Popovers", "description"=>"Add small overlays of content, like those on the iPad, to any element for housing secondary information."),
                            "progress" => array("title"=>"Progress bars", "description"=>"For loading, redirecting, or action status."),
                            "tooltips" => array("title"=>"Tooltips", "description"=>"A new take on the jQuery Tipsy plugin, Tooltips don't rely on images, uses CSS3 for animations, and data-attributes for local title storage."),
                            "keybindings" => array("title"=>"Key bindings", "description"=>"Interacting with UI components using the keyboard."),
                        )
                    ),
                    "layouts" => array (
                        "title" => "Layouts & Views",
                        "description" => "Modals, navbars, and other layout widgets.",
                        "index" => true,
                        "pages" => array (
                            "list" => array("title"=>"List Tables", "description"=>"For, you guessed it, tabular data."),
                            "record" => array("title"=>"Record Views", "url"=>"#Styleguide/create", "description"=>"Detail, edit and create views for records."),
                            "drawer" => array("title"=>"Drawers", "description"=>"Drawer is a form of a modal that pushes main content down and expands from the top taking 100% of the screen width."),
                            "navbar" => array("title"=>"Navbar", "description"=>"Top level navigation layout."),
                            "tabs" => array("title"=>"Tab Navigation", "description"=>"Highly customizable list-based navigation."),
                            //TODO: remove these pages or update
                            //"modals" => array("title"=>"Modals", "description"=>"A streamlined, but flexible, take on the traditional javascript modal plugin with only the minimum required functionality and smart defaults."),
                            //"wizard" => array("title"=>"Wizard", "description"=>"Wizard takes advantage of bootstrap modals and sets up a framework for taking a user through multiple steps to complete a task."),
                            //"thumbnails" => array("title"=>"Thumbnails", "description"=>"Grids of images, videos, text, and more."),
                            //"scrollspy" => array("title"=>"Scrollspy", "description"=>"Use scrollspy to automatically update the links in your navbar to show the current active link based on scroll position."),
                            //"carousel" => array("title"=>"Carousel", "description"=>"Create a merry-go-round of any content you wish to provide an interactive slideshow of content."),
                            //"typeahead" => array("title"=>"Typeahead", "description"=>"A basic, easily extended plugin for quickly creating elegant typeaheads with any form text input."),
                            //"transitions" => array("title"=>"Transitions", "description"=>"For simple transition effects, include bootstrap-transition.js once to slide in modals or fade out alerts."),
                      )
                    ),
                    "dashboards" => array (
                        "title" => "Dashboards",
                        "description" => "Documentation and guidelines for dashboards within the app.",
                        "index" => true,
                        "pages" => array (
                            "home" => array("title"=>"Home Module Dashboard", "description"=>"A grid layout for arranging dashlets."),
                            "intel" => array("title"=>"Intelligence Pane Dashboard", "description"=>"Special features of the content related right hand side dashboard."),
                            "dashlets" => array("title"=>"Dashlets", "description"=> "Patterns, styles, and elements for creating dashlets."),
                        )
                    ),
                    "charts" => array (
                        "title" => "Sucrose Charts",
                        "description" => "Standard and custom charts in Sugar7 are developed using D3 and the Sucrose Charts library. For configuration details see the <a href='http://sucrose.io'>sucrose.io</a> website.",
                        "index" => true,
                        "pages" => array (
                            "types" => array("title"=>"Chart Types", "description"=>"Currently supported Sucrose chart types."),
                            "colors" => array("title"=>"Chart Colors", "description"=>"Flexible methods for assigning color maps and fill methods to Sucrose charts."),
                        )
                    ),
                ),
            ),
            "fields" => array (
                "title" => "Example Sugar7 Fields",
                "description" => "Basic fields that support detail, record, and edit modes with error addons.",
                "index" => false,
            ),
            "views" => array (
                "title" => "Example Sugar7 Views",
                "description" => "Basic views are the building blocks of a layout.",
                "index" => true,
                "sections" => array (
                    "list" => array (
                        "title" => "List Views",
                        "description" => "List views for simple and complex data tables.",
                        "index" => true,
                        "pages" => array (
                            "basic" => array("title"=>"Basic List", "description"=>"Simple table layouts with striping."),
                        )
                    ),
                    "dashlet" => array (
                        "title" => "Dashlet Views",
                        "description" => "Component views combined to form a dashlet.",
                        "index" => true,
                        "pages" => array (
                            "toolbar" => array("title"=>"Toolbar", "description"=>"Dashlet header bar for interacting with dashlet."),
                        )
                    ),
                ),
            ),
        ),
        "template_values" => array(
            "last_updated" => "2015-12-01T22:47:00+00:00",
            "version" => "7.8.0",
        ),
    ),
);
