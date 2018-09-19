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

use Sugarcrm\Sugarcrm\Security\Csrf\CsrfAuthenticator;
use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;

/**
 * Base Sugar view
 * @api
 */
class SugarView
{
    /**
     * This array is meant to hold an objects/data that we would like to pass between
     * the controller and the view.  The bean will automatically be set for us, but this
     * is meant to hold anything else.
     */
    var $view_object_map = array();
    /**
     * The name of the current module.
     */
    var $module = '';
    /**
     * The name of the current action.
     */
    var $action = '';
    /**
     * @var SugarBean
     */
    var $bean = null;
    /**
     * Sugar_Smarty. This is useful if you have a view and a subview you can
     * share the same smarty object.
     *
     * @var Sugar_Smarty
     */
    var $ss = null;
    /**
     * Any errors that occured this can either be set by the view or the controller or the model
     */
    var $errors = array();
    /**
     * Set to true if you do not want to display errors from SugarView::displayErrors(); instead they will be returned
     */
    var $suppressDisplayErrors = false;

    /**
     * Options for what UI elements to hide/show/
     */
    var $options = array(
        'show_header' => true,
        'show_title' => true,
        'show_subpanels' => false,
        'show_search' => true,
        'show_footer' => false,
        'show_javascript' => true,
        'view_print' => false,
        'use_table_container' => true
    );
    var $type = null;
    var $responseTime;
    var $fileResources;
    protected $base_menu;

    /**
     * The string which will be shown in the browser's title
     *
     * @var string
     */
    protected $browserTitle;

    /**
     * @var Request 
     */
    protected $request;

    /**
     * Ctor
     * @param SugarBean $bean
     * @param array $view_object_map
     * @param Request $request
     */
    public function __construct($bean = null, $view_object_map = array(), Request $request = null)
    {
        $this->base_menu = SugarAutoLoader::loadExtension("menus", "application");
        $this->request = $request ?: InputValidation::getService();
    }

    public function init(
        $bean = null,
        $view_object_map = array()
        )
    {
        $this->bean = $bean;
        $this->view_object_map = $view_object_map;
        $this->action = $GLOBALS['action'];
        $this->module = $GLOBALS['module'];
        $this->_initSmarty();
    }

    protected function _initSmarty()
    {
        $this->ss = new Sugar_Smarty();
        $this->ss->assign('MOD', $GLOBALS['mod_strings']);
        $this->ss->assign('APP', $GLOBALS['app_strings']);
    }

    /**
     * This method will be called from the controller and is not meant to be overridden.
     */
    public function process($params = array())
    {
        LogicHook::initialize();
        $this->_checkModule();

        //trackView has to be here in order to track for breadcrumbs
        $this->_trackView();

        //For the ajaxUI, we need to use output buffering to return the page in an ajax friendly format
        // FIXME review this code (no more ajax load)
        if ($this->_getOption('json_output')){
			ob_start();
			if(!empty($_REQUEST['ajax_load']) && !empty($_REQUEST['loadLanguageJS'])) {
				echo $this->_getModLanguageJS();
			}
		}

        if ($this->_getOption('show_header')) {
            $this->displayHeader();
        } else {
            $this->renderJavascript();
        }

        $records = $this->request->getValidInputRequest('updated_records');
        if (!empty($records)) {
            $this->errors[] = $records;
        }

        $this->_buildModuleList();
        $this->preDisplay($params);
        $this->displayErrors($params);
        $this->display($params);
        if ( !empty($this->module) ) {
            $GLOBALS['logic_hook']->call_custom_logic($this->module, 'after_ui_frame');
        } else {
            $GLOBALS['logic_hook']->call_custom_logic('', 'after_ui_frame');
        }

        if ($this->_getOption('show_subpanels') && !empty($_REQUEST['record'])) $this->_displaySubPanels();

        if ($this->action === 'Login') {
            //this is needed for a faster loading login page ie won't render unless the tables are closed
            ob_flush();
        }
        if ($this->_getOption('show_footer')) $this->displayFooter();
        $GLOBALS['logic_hook']->call_custom_logic('', 'after_ui_footer');

        //Do not track if there is no module or if module is not a String
        $this->_track();
    }

    /**
     * This method will display the errors on the page.
     *
     * @param array $params additional view paramters passed through from the controller
     */
    public function displayErrors($params = array())
    {
        $errors = '';

        foreach($this->errors as $error) {
            $errors .= '<span class="error">' . $error . '</span><br>';
        }

        if ( !$this->suppressDisplayErrors ) {
            echo $errors;
        }
        else {
            return $errors;
        }
    }

    /**
     * [OVERRIDE] - This method is meant to overidden in a subclass. The purpose of this method is
     * to allow a view to do some preprocessing before the display method is called. This becomes
     * useful when you have a view defined at the application level and then within a module
     * have a sub-view that extends from this application level view.  The application level
     * view can do the setup in preDisplay() that is common to itself and any subviews
     * and then the subview can just override display(). If it so desires, can also override
     * preDisplay().
     */
    public function preDisplay()
    {
    }

    /**
     * [OVERRIDE] - This method is meant to overidden in a subclass. This method
     * will handle the actual display logic of the view.
     */
    public function display()
    {
    }


    /**
     * trackView
     */
    protected function _trackView()
    {
        $action = strtolower($this->action);
        //Skip save, tracked in SugarBean instead
        if($action == 'save') {
        return;
        }


        $trackerManager = TrackerManager::getInstance();
        $timeStamp = TimeDate::getInstance()->nowDb();
        if($monitor = $trackerManager->getMonitor('tracker')){
            $monitor->setValue('team_id', $GLOBALS['current_user']->getPrivateTeamID());
            $monitor->setValue('action', $action);
            $monitor->setValue('user_id', $GLOBALS['current_user']->id);
            $monitor->setValue('module_name', $this->module);
            $monitor->setValue('date_modified', $timeStamp);
            $monitor->setValue('visible', (($monitor->action == 'detailview') || ($monitor->action == 'editview')
                                            || ($monitor->action == 'wirelessdetail') || ($monitor->action == 'wirelessedit')
                                            ) ? 1 : 0);

            if (!empty($this->bean->id)) {
                $monitor->setValue('item_id', $this->bean->id);
                $monitor->setValue('item_summary', $this->bean->get_summary_text());
            }

            //If visible is true, but there is no bean, do not track (invalid/unauthorized reference)
            //Also, do not track save actions where there is no bean id
            if($monitor->visible && empty($this->bean->id)) {
            $trackerManager->unsetMonitor($monitor);
            return;
            }
            $trackerManager->saveMonitor($monitor, true, true);
        }
    }


    /**
     * Displays the header on section of the page; basically everything before the content
     * @deprecated since 7.0, will be removed from 7.2.
     */
    public function displayHeader()
    {
        global $theme;
        global $max_tabs;
        global $app_strings;
        global $current_user;
        global $app_list_strings;
        global $mod_strings;
        global $current_language;

        $GLOBALS['app']->headerDisplayed = true;

        $themeObject = SugarThemeRegistry::current();
        $theme = $themeObject->__toString();

        $ss = new Sugar_Smarty();
        $ss->assign("APP", $app_strings);
        $ss->assign("THEME", $theme);
        $ss->assign("THEME_IE6COMPAT", $themeObject->ie6compat ? 'true':'false');
        $ss->assign("MODULE_NAME", $this->module);
        $ss->assign("langHeader", get_language_header());
        $ss->assign('use_table_container', (isset($this->options['use_table_container']) ? $this->options['use_table_container'] : false));

        // set ab testing if exists
        $testing = $this->request->getValidInputRequest('testing', null, 'a');
        $ss->assign("ABTESTING", $testing);

        // get browser title
        $ss->assign("SYSTEM_NAME", $this->getBrowserTitle());

        // get css
        $ss->assign("SUGAR_CSS", $this->getThemeCss());

        // get javascript
        ob_start();
        $this->renderJavascript();

        $ss->assign("SUGAR_JS",ob_get_contents().$themeObject->getJS());
        ob_end_clean();

        $favicon = $this->getFavicon();
        $ss->assign('FAVICON_URL', $favicon['url']);

        // build the shortcut menu
        $shortcut_menu = array();
        foreach ( $this->getMenu() as $key => $menu_item )
            $shortcut_menu[$key] = array(
                "URL"         => $menu_item[0],
                "LABEL"       => $menu_item[1],
                "MODULE_NAME" => $menu_item[2],
                "IMAGE"       => $themeObject
                    ->getImage($menu_item[2],"border='0' align='absmiddle'",null,null,'.gif',$menu_item[1]),
                );
        $ss->assign("SHORTCUT_MENU",$shortcut_menu);

        // handle rtl text direction
        if(isset($_REQUEST['RTL']) && $_REQUEST['RTL'] == 'RTL'){
            $_SESSION['RTL'] = true;
        }
        if(isset($_REQUEST['LTR']) && $_REQUEST['LTR'] == 'LTR'){
            unset($_SESSION['RTL']);
        }
        if(isset($_SESSION['RTL']) && $_SESSION['RTL']){
            $ss->assign("DIR", 'dir="RTL"');
        }

        // handle resizing of the company logo correctly on the fly
        $companyLogoURL = $themeObject->getImageURL('company_logo.png', true, true);
        $companyLogoURL_arr = explode('?', $companyLogoURL);
        $companyLogoURL = $companyLogoURL_arr[0];

        $company_logo_attributes = sugar_cache_retrieve('company_logo_attributes');
        if(!empty($company_logo_attributes)) {
            $ss->assign("COMPANY_LOGO_MD5", $company_logo_attributes[0]);
            $ss->assign("COMPANY_LOGO_WIDTH", $company_logo_attributes[1]);
            $ss->assign("COMPANY_LOGO_HEIGHT", $company_logo_attributes[2]);
        }
        else {
            // Always need to md5 the file
            $ss->assign("COMPANY_LOGO_MD5", md5_file($companyLogoURL));

            list($width,$height) = getimagesize($companyLogoURL);
            if ( $width > 212 || $height > 40 ) {
                $resizePctWidth  = ($width - 212)/212;
                $resizePctHeight = ($height - 40)/40;
                if ( $resizePctWidth > $resizePctHeight )
                    $resizeAmount = $width / 212;
                else
                    $resizeAmount = $height / 40;
                $ss->assign("COMPANY_LOGO_WIDTH", round($width * (1/$resizeAmount)));
                $ss->assign("COMPANY_LOGO_HEIGHT", round($height * (1/$resizeAmount)));
            }
            else {
                $ss->assign("COMPANY_LOGO_WIDTH", $width);
                $ss->assign("COMPANY_LOGO_HEIGHT", $height);
            }

            // Let's cache the results
            sugar_cache_put('company_logo_attributes',
                            array(
                                $ss->get_template_vars("COMPANY_LOGO_MD5"),
                                $ss->get_template_vars("COMPANY_LOGO_WIDTH"),
                                $ss->get_template_vars("COMPANY_LOGO_HEIGHT")
                                )
            );
        }
        $ss->assign("COMPANY_LOGO_URL",getJSPath($companyLogoURL)."&logo_md5=".$ss->get_template_vars("COMPANY_LOGO_MD5"));

        // get the global links (Currently not used, since we hardcoded the metadata for profileactions
        // and upon upgrade, we convert global link in metadataconverter to create custom metadata
        // for profileactions, Thus, code below can be removed in the future)
        $gcls = array();
        $global_control_links = array();
        require("include/globalControlLinks.php");

        foreach($global_control_links as $key => $value) {
            if ($key == 'users')  {   //represents logout link.
                $ss->assign("LOGOUT_LINK", $value['linkinfo'][key($value['linkinfo'])]);
                $ss->assign("LOGOUT_LABEL", key($value['linkinfo']));//key value for first element.
                continue;
            }

            foreach ($value as $linkattribute => $attributevalue) {
                // get the main link info
                if ( $linkattribute == 'linkinfo' ) {
                    $gcls[$key] = array(
                        "LABEL" => key($attributevalue),
                        "URL"   => current($attributevalue),
                        "SUBMENU" => array(),
                    );
                    if(substr($gcls[$key]["URL"], 0, 11) == "javascript:") {
                        $gcls[$key]["ONCLICK"] = substr($gcls[$key]["URL"],11);
                        $gcls[$key]["URL"] = "javascript:void(0)";
                    }
                }
                // and now the sublinks
                if ( $linkattribute == 'submenu' && is_array($attributevalue) ) {
                    foreach ($attributevalue as $submenulinkkey => $submenulinkinfo)
                        $gcls[$key]['SUBMENU'][$submenulinkkey] = array(
                            "LABEL" => key($submenulinkinfo),
                            "URL"   => current($submenulinkinfo),
                        );
                    if(substr($gcls[$key]['SUBMENU'][$submenulinkkey]["URL"], 0, 11) == "javascript:") {
                        $gcls[$key]['SUBMENU'][$submenulinkkey]["ONCLICK"] = substr($gcls[$key]['SUBMENU'][$submenulinkkey]["URL"],11);
                        $gcls[$key]['SUBMENU'][$submenulinkkey]["URL"] = "javascript:void(0)";
                    }
                }
            }
        }
        $ss->assign("GCLS",$gcls);

        $ss->assign("SEARCH", $this->request->getValidInputRequest('query_string', null, ''));

        if ($this->action == "EditView" || $this->action == "Login")
            $ss->assign("ONLOAD", 'onload="set_focus()"');

        $ss->assign("AUTHENTICATED",isset($_SESSION["authenticated_user_id"]));
        $ss->assign("ISPRINT",isset($_REQUEST['print'])); //this will be used by header.tpl to hide the megamenu bar when its 'print' view

        // get other things needed for page style popup
        if (isset($_SESSION["authenticated_user_id"])) {
            // get the current user name and id
            $ss->assign("CURRENT_USER", $current_user->full_name == '' || !showFullName()
                ? $current_user->user_name : $current_user->full_name );
            $ss->assign("CURRENT_USER_ID", $current_user->id);

            // get the last viewed records
            $tracker = BeanFactory::newBean('Trackers');
            $history = $tracker->get_recently_viewed($current_user->id);
            $ss->assign("recentRecords",$this->processRecentRecords($history));
        }

        $bakModStrings = $mod_strings;

        $imageURL = SugarThemeRegistry::current()->getImageURL("dashboard.png");
        $homeImage = "<img src='$imageURL'>";
		$ss->assign("homeImage",$homeImage);
        global $mod_strings;
        $mod_strings = $bakModStrings;
        $headerTpl = $themeObject->getTemplate('header.tpl');
        if (inDeveloperMode() )
            $ss->clear_compiled_tpl($headerTpl);
            $ss->display($headerTpl);
            $this->includeClassicFile('modules/Administration/DisplayWarnings.php');

            $errorMessages = SugarApplication::getErrorMessages();
            if ( !empty($errorMessages)) {
                foreach ( $errorMessages as $error_message ) {
                    echo('<p class="error" data-type="bwc-error">' . $error_message.'</p>');
                }
            }
    }

    /**
     * Get the Themes CSS from the ThemeObject
     *
     * @return string           The html for the CSS that needs to be loaded
     */
    public function getThemeCss()
    {
        $themeObject = SugarThemeRegistry::current();
        $css = $themeObject->getCSS();
        if ($this->_getOption('view_print')) {
            $css .= '<link rel="stylesheet" type="text/css" href="'.$themeObject->getCSSURL('print.css').'" media="all" />';
        }

        return $css;
    }

    function getModuleMenuHTML()
    {

    }

    /**
     * If the view is classic then this method will include the file and
     * setup any global variables.
     *
     * @param string $file
     */
    public function includeClassicFile(
        $file
        )
    {
        global $sugar_config, $theme, $current_user, $sugar_version, $sugar_flavor, $mod_strings, $app_strings, $app_list_strings, $action;
        global $gridline, $request_string, $modListHeader, $dashletData, $authController, $locale, $currentModule, $import_bean_map, $image_path, $license;
        global $user_unique_key, $server_unique_key, $barChartColors, $modules_exempt_from_availability_check, $dictionary, $current_language, $beanList, $beanFiles, $sugar_build, $sugar_codename;
        global $timedate, $login_error; // cn: bug 13855 - timedate not available to classic views.
        if (!empty($this->module))
            $currentModule = $this->module;
        require_once ($file);
    }

    protected function _displayLoginJS()
    {
        global $sugar_config, $timedate;

        $action = $this->request->getValidInputRequest('action');
        if(isset($this->bean->module_dir)){
            echo "<script>var module_sugar_grp1 = '{$this->bean->module_dir}';</script>";
        }
        if(isset($_REQUEST['action'])){
            echo "<script>var action_sugar_grp1 = '{$action}';</script>";
        }
        echo '<script>jscal_today = 1000*' . $timedate->asUserTs($timedate->getNow()) . '; if(typeof app_strings == "undefined") app_strings = new Array();</script>';
        if (!is_file(sugar_cached("include/javascript/sugar_grp1.js"))) {
            $_REQUEST['root_directory'] = ".";
            $minifyUtils = new SugarMinifyUtils();
            $minifyUtils->ConcatenateFiles(".");
        }
        echo getVersionedScript('cache/include/javascript/sugar_grp1_jquery.js');
        echo getVersionedScript('cache/include/javascript/sugar_grp1_yui.js');
        echo getVersionedScript('cache/include/javascript/sugar_grp1.js');
        if (!is_file(sugar_cached("Expressions/functions_cache.js"))) {
            $GLOBALS['updateSilent'] = true;
            include("include/Expressions/updatecache.php");
        }

        $path = shouldResourcesBeMinified()
            ? 'cache/Expressions/functions_cache.js'
            : 'cache/Expressions/functions_cache_debug.js';
        echo getVersionedScript($path);

        echo <<<EOQ
        <script>
            if ( typeof(SUGAR) == 'undefined' ) {SUGAR = {}};
            if ( typeof(SUGAR.themes) == 'undefined' ) SUGAR.themes = {};
        </script>
EOQ;
    }

    /**
     * Get JS validation code for views
     */
    public static function getJavascriptValidation()
    {
        global $timedate;
        $cal_date_format = $timedate->get_cal_date_format();
        $timereg = $timedate->get_regular_expression($timedate->get_time_format());
        $datereg = $timedate->get_regular_expression($timedate->get_date_format());
        $date_pos = '';
        foreach ($datereg['positions'] as $type => $pos) {
            if (empty($date_pos)) {
                $date_pos .= "'$type': $pos";
            } else {
                $date_pos .= ",'$type': $pos";
            }
        }

        $time_separator = $timedate->timeSeparator();
        $hour_offset = $timedate->getUserUTCOffset() * 60;

        // Escape the time separator if it is a period.
        $timeFormat = $time_separator === '.' ? preg_replace('/\./', '\\.', $timereg['format']) : $timereg['format'];

        // Add in the number formatting styles here as well, we have been handling this with individual modules.
        require_once ('modules/Currencies/Currency.php');
        list ($num_grp_sep, $dec_sep) = get_number_seperators();

        $the_script = "<script type=\"text/javascript\">\n" . "\tvar time_reg_format = '" .
             addslashes($timeFormat) . "';\n" . "\tvar date_reg_format = '" .
             addslashes($datereg['format']) . "';\n" . "\tvar date_reg_positions = { $date_pos };\n" .
             "\tvar time_separator = '$time_separator';\n" .
             "\tvar cal_date_format = '$cal_date_format';\n" .
             "\tvar time_offset = $hour_offset;\n" . "\tvar num_grp_sep = '$num_grp_sep';\n" .
             "\tvar dec_sep = '$dec_sep';\n" . "</script>";

        return $the_script;
    }

    /**
     * Called from process(). This method will display the correct javascript.
     */
    protected function _displayJavascript()
    {
        global $locale, $sugar_config, $timedate;

        $action = $this->request->getValidInputRequest('action');

        if ($this->_getOption('show_javascript')) {
            if (!$this->_getOption('show_header')) {
                $langHeader = get_language_header();

                echo <<<EOHTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html {$langHeader}>
<head>
EOHTML;
            }

            $js_vars = array(
                "sugar_cache_dir" => "cache/",
                );

            if(isset($this->bean->module_dir)){
                $js_vars['module_sugar_grp1'] = $this->bean->module_dir;
            }
            if(isset($_REQUEST['action'])){
                $js_vars['action_sugar_grp1'] = $action;
            }
            echo '<script>jscal_today = 1000*' . $timedate->asUserTs($timedate->getNow()) . '; if(typeof app_strings == "undefined") app_strings = new Array();</script>';
            // Make sure the necessary cache files are in place
            $jsFiles = array("sugar_grp1.js", "sugar_grp1_yui.js", "sugar_grp1_jquery.js");
            ensureJSCacheFilesExist($jsFiles);
            echo getVersionedScript('cache/include/javascript/sugar_grp1_jquery.js');
            echo getVersionedScript('cache/include/javascript/sugar_grp1_yui.js');
            echo getVersionedScript('cache/include/javascript/sugar_grp1.js');

            $version_mark = getVersionedPath(null);
            echo '<script>SUGAR = SUGAR || {}; SUGAR.VERSION_MARK = ' . json_encode($version_mark) . ';</script>';

            // output necessary config js in the top of the page
            $config_js = $this->getSugarConfigJS();
            if(!empty($config_js)){
                echo "<script>\n".implode("\n", $config_js)."</script>\n";
            }

            // CSRF form token
            echo $this->getCsrfFormTokenJscript();

            if ( isset($sugar_config['email_sugarclient_listviewmaxselect']) ) {
                echo "<script>SUGAR.config.email_sugarclient_listviewmaxselect = {$GLOBALS['sugar_config']['email_sugarclient_listviewmaxselect']};</script>";
            }

            $image_server = (defined('TEMPLATE_URL'))?TEMPLATE_URL . '/':'';
            echo '<script type="text/javascript">SUGAR.themes.image_server="' . $image_server . '";</script>'; // cn: bug 12274 - create session-stored key to defend against CSRF
            echo '<script type="text/javascript">var name_format = "' . $locale->getLocaleFormatMacro() . '";</script>';
            echo self::getJavascriptValidation();
            if (!is_file(sugar_cached('jsLanguage/') . $GLOBALS['current_language'] . '.js')) {
                jsLanguage::createAppStringsCache($GLOBALS['current_language']);
            }
            echo getVersionedScript('cache/jsLanguage/'. $GLOBALS['current_language'] . '.js', $GLOBALS['sugar_config']['js_lang_version']);

			echo $this->_getModLanguageJS();
            echo getVersionedScript('include/javascript/productTour.js');

            if (!is_file(sugar_cached("Expressions/functions_cache.js"))) {
                $GLOBALS['updateSilent'] = true;
                include("include/Expressions/updatecache.php");
            }

            $path = shouldResourcesBeMinified()
                ? 'cache/Expressions/functions_cache.js'
                : 'cache/Expressions/functions_cache_debug.js';
            echo getVersionedScript($path);

            echo "\n" . '<script type="text/javascript">' . DependencyManager::getJSUserVariables($GLOBALS['current_user']) . "</script>\n";

            //echo out the $js_vars variables as javascript variables
            echo "<script type='text/javascript'>\n";
            foreach($js_vars as $var=>$value)
            {
                echo "var {$var} = '{$value}';\n";
            }
            echo "</script>\n";
        }
    }

	protected function _getModLanguageJS(){
		if (!is_file(sugar_cached('jsLanguage/') . $this->module . '/' . $GLOBALS['current_language'] . '.js')) {
			jsLanguage::createModuleStringsCache($this->module, $GLOBALS['current_language']);
		}
		return getVersionedScript("cache/jsLanguage/{$this->module}/". $GLOBALS['current_language'] . '.js', $GLOBALS['sugar_config']['js_lang_version']);
	}

    /**
     * Called from process(). This method will display the footer on the page.
     * @deprecated since 7.0, will be removed from 7.2.
     */
    public function displayFooter()
    {
        // TODO make me a function
        $message = sprintf(
            "Deprecated as of '%s'. Will be removed with '%s'; in '%s'.",
            '7.0',
            '7.2',
            __FILE__
        );
        $GLOBALS['log']->deprecated($message);

        // TODO check if we need this on flav < PRO

        /*
        if (empty($this->responseTime)) {
            $this->_calculateFooterMetrics();
        }
        global $sugar_config;
        global $app_strings;
        global $mod_strings;
		$themeObject = SugarThemeRegistry::current();
        //decide whether or not to show themepicker, default is to show
        $showThemePicker = true;
        if (isset($sugar_config['showThemePicker'])) {
            $showThemePicker = $sugar_config['showThemePicker'];
        }

        $ss = new Sugar_Smarty();
        $ss->assign("AUTHENTICATED",isset($_SESSION["authenticated_user_id"]));
        $ss->assign('MOD',return_module_language($GLOBALS['current_language'], 'Users'));
        $ss->assign('use_table_container', (isset($this->options['use_table_container']) ? $this->options['use_table_container'] : false));

		$bottomLinkList = array();
		 if (isset($this->action) && $this->action != "EditView") {
			 $bottomLinkList['print'] = array($app_strings['LNK_PRINT'] => getPrintLink());
		}
		$bottomLinkList['backtotop'] = array($app_strings['LNK_BACKTOTOP'] => 'javascript:SUGAR.util.top();');

		$bottomLinksStr = "";
		foreach($bottomLinkList as $key => $value) {
			foreach($value as $text => $link) {
				   $href = $link;
				   if(substr($link, 0, 11) == "javascript:") {
                       $onclick = " onclick=\"".substr($link,11)."\"";
                       $href = "javascript:void(0)";
                   } else {
                   		$onclick = "";
                   	}
                $imageURL = SugarThemeRegistry::current()->getImageURL($key.'.gif');
				$bottomLinksStr .= "<a href=\"{$href}\"";
				$bottomLinksStr .= (isset($onclick)) ? $onclick : "";
				$bottomLinksStr .= "><img src='{$imageURL}' alt=''>"; //keeping alt blank on purpose for 508 (text will be read instead)
				$bottomLinksStr .= " ".$text."</a>";
			}
		}
		$ss->assign("BOTTOMLINKS",$bottomLinksStr);
        if (SugarConfig::getInstance()->get('calculate_response_time', false))
            $ss->assign('STATISTICS',$this->_getStatistics());

        // Under the License referenced above, you are required to leave in all copyright statements in both
        // the code and end-user application.





        $copyright = '&copy; 2004-2013 <a href="http://www.sugarcrm.com" target="_blank" class="copyRightLink">SugarCRM Inc.</a> All Rights Reserved.<br>';


        // You are required to leave in all copyright statements in both the
        // code and end-user application as well as the the powered by image.
        // You can not change the url or the image below.
        $attribLinkImg = "<A href='http://www.sugarcrm.com' target='_blank'><img style='margin-top: 2px' border='0' width='120' height='34' src='".getJSPath('include/images/poweredby_sugarcrm_65.png')."' alt='Powered By SugarCRM'></A>\n";

		// handle resizing of the company logo correctly on the fly
        $companyLogoURL = $themeObject->getImageURL('company_logo.png', true, true);
        $companyLogoURL_arr = explode('?', $companyLogoURL);
        $companyLogoURL = $companyLogoURL_arr[0];

        $company_logo_attributes = sugar_cache_retrieve('company_logo_attributes');
        if(!empty($company_logo_attributes)) {
            $ss->assign("COMPANY_LOGO_MD5", $company_logo_attributes[0]);
            $ss->assign("COMPANY_LOGO_WIDTH", $company_logo_attributes[1]);
            $ss->assign("COMPANY_LOGO_HEIGHT", $company_logo_attributes[2]);
        }
        else {
            // Always need to md5 the file
            $ss->assign("COMPANY_LOGO_MD5", md5_file($companyLogoURL));

            list($width,$height) = getimagesize($companyLogoURL);
            if ( $width > 212 || $height > 40 ) {
                $resizePctWidth  = ($width - 212)/212;
                $resizePctHeight = ($height - 40)/40;
                if ( $resizePctWidth > $resizePctHeight )
                    $resizeAmount = $width / 212;
                else
                    $resizeAmount = $height / 40;
                $ss->assign("COMPANY_LOGO_WIDTH", round($width * (1/$resizeAmount)));
                $ss->assign("COMPANY_LOGO_HEIGHT", round($height * (1/$resizeAmount)));
            }
            else {
                $ss->assign("COMPANY_LOGO_WIDTH", $width);
                $ss->assign("COMPANY_LOGO_HEIGHT", $height);
            }

            // Let's cache the results
            sugar_cache_put('company_logo_attributes',
                            array(
                                $ss->get_template_vars("COMPANY_LOGO_MD5"),
                                $ss->get_template_vars("COMPANY_LOGO_WIDTH"),
                                $ss->get_template_vars("COMPANY_LOGO_HEIGHT")
                                )
            );
        }
        $ss->assign("COMPANY_LOGO_URL",getJSPath($companyLogoURL)."&logo_md5=".$ss->get_template_vars("COMPANY_LOGO_MD5"));

        // Bug 38594 - Add in Trademark wording
        $copyright .= 'SugarCRM is a trademark of SugarCRM, Inc. All other company and product names may be trademarks of the respective companies with which they are associated.<br />';

        //rrs bug: 20923 - if this image does not exist as per the license, then the proper image will be displayed regardless, so no need
        //to display an empty image here.
        if(file_exists('include/images/poweredby_sugarcrm_65.png')){
            $copyright .= $attribLinkImg;
        }
        // End Required Image
        $ss->assign('COPYRIGHT',$copyright);
        if(isset($GLOBALS['current_user']) && !empty($GLOBALS['current_user']->id))
        {
            $dcm = DCFactory::getContainer(null, 'DCMenu');
            $ss->assign('DYNAMICDCACTIONS',$dcm->getPartnerIconMenus());
        }

        // here we allocate the help link data
        $help_actions_blacklist = array('Login'); // we don't want to show a context help link here
        if (!in_array($this->action,$help_actions_blacklist)) {
            $url = 'javascript:void(window.open(\'index.php?module=Administration&action=SupportPortal&view=documentation&version='.$GLOBALS['sugar_version'].'&edition='.$GLOBALS['sugar_flavor'].'&lang='.$GLOBALS['current_language'].
                        '&help_module='.$this->module.'&help_action='.$this->action.'&key='.$GLOBALS['server_unique_key'].'\'))';
            $label = (isset($GLOBALS['app_list_strings']['moduleList'][$this->module]) ?
                        $GLOBALS['app_list_strings']['moduleList'][$this->module] : $this->module). ' '.$app_strings['LNK_HELP'];
            $ss->assign('HELP_LINK',SugarThemeRegistry::current()->getLink($url, $label, "id='help_link_two'",
                '', '',null,null,'','',"<i class='icon-question-circle icon'></i>"));
        }
        // end

        $ss->assign('TOUR_LINK',SugarThemeRegistry::current()->getLink("javascript: void(0);", $app_strings['LNK_TOUR'], "id='tour_link'",
            '', '',null,null,'','',"<i class='icon-road icon'></i>"));

        if (!empty($GLOBALS['sugar_config']['disabled_feedback_widget']))
            $ss->assign('DISABLE_FEEDBACK_WIDGET', TRUE);

        $ss->display(SugarThemeRegistry::current()->getTemplate('footer.tpl'));
        */
    }

    /**
     * Called from process(). This method will display subpanels.
     */
    protected function _displaySubPanels()
    {
        if (!empty($this->bean->id) &&
            (SugarAutoLoader::existingCustom('modules/' . $this->module . '/metadata/subpaneldefs.php') ||
             SugarAutoLoader::loadExtension("layoutdefs", $this->module))) {
            $GLOBALS['focus'] = $this->bean;
            $subpanel = new SubPanelTiles($this->bean, $this->module);
            echo $subpanel->display();
        }
    }

    protected function _buildModuleList()
    {
        if (!empty($GLOBALS['current_user']) && empty($GLOBALS['modListHeader']))
            $GLOBALS['modListHeader'] = query_module_access_list($GLOBALS['current_user']);
    }

    /**
     * private method used in process() to determine the value of a passed in option
     *
     * @param string option - the option that we want to know the valye of
     * @param bool default - what the default value should be if we do not find the option
     *
     * @return bool - the value of the option
     */
    protected function _getOption(
        $option,
        $default = false
        )
    {
        if (!empty($this->options) && isset($this->options['show_all'])) {
            return $this->options['show_all'];
        } elseif (!empty($this->options) && isset($this->options[$option])) {
            return $this->options[$option];
        } else return $default;
    }

    /**
     * track
     * Private function to track information about the view request
     */
    private function _track()
    {
        if (empty($this->responseTime)) {
            $this->_calculateFooterMetrics();
        }
        if (empty($GLOBALS['current_user']->id)) {
            return;
        }


        $trackerManager = TrackerManager::getInstance();

        if(!$trackerManager->isPaused())
        {
            // Track performance
            if ($performanceMonitor = $trackerManager->getMonitor('tracker_perf')) {
                $performanceMonitor->setValue('server_response_time', $this->responseTime);
                $dbManager = DBManagerFactory::getInstance();
                $performanceMonitor->db_round_trips = $dbManager->getQueryCount();
                $performanceMonitor->setValue('date_modified', TimeDate::getInstance()->nowDb());
                $performanceMonitor->setValue('db_round_trips', $dbManager->getQueryCount());
                $performanceMonitor->setValue('files_opened', $this->fileResources);
                if (function_exists('memory_get_usage')) {
                    $performanceMonitor->setValue('memory_usage', memory_get_usage());
                }

                $trackerManager->saveMonitor($performanceMonitor);
            }

            SugarApplication::trackSession();
        }
    }

    /**
     * Checks to see if the module name passed is valid; dies if it is not
     */
    protected function _checkModule()
    {
        if (!empty($this->module) && !file_exists('modules/'.$this->module)) {
            $error = str_replace("[module]", "$this->module", $GLOBALS['app_strings']['ERR_CANNOT_FIND_MODULE']);
            $GLOBALS['log']->fatal($error);
            echo $error;
            die();
        }
    }

    public function renderJavascript()
    {
        if ($this->action !== 'Login')
            $this->_displayJavascript();
        else
            $this->_displayLoginJS();
    }

    private function _calculateFooterMetrics()
    {
        $endTime = microtime(true);
        $deltaTime = $endTime - $GLOBALS['startTime'];
        $this->responseTime = number_format(round($deltaTime, 2), 2);
        // Print out the resources used in constructing the page.
        $this->fileResources = count(get_included_files());
    }

    protected function _getStatistics()
    {
        $endTime = microtime(true);
        $deltaTime = $endTime - $GLOBALS['startTime'];
        $response_time_string = $GLOBALS['app_strings']['LBL_SERVER_RESPONSE_TIME'] . ' ' . number_format(round($deltaTime, 2), 2) . ' ' . $GLOBALS['app_strings']['LBL_SERVER_RESPONSE_TIME_SECONDS'];
        $return = $response_time_string;
        if (!empty($GLOBALS['sugar_config']['show_page_resources'])) {
            // Print out the resources used in constructing the page.
            $included_files = get_included_files();

            // take all of the included files and make a list that does not allow for duplicates based on case
            // I believe the full get_include_files result set appears to have one entry for each file in real
            // case, and one entry in all lower case.
            $list_of_files_case_insensitive = array();
            foreach($included_files as $key => $name) {
                // preserve the first capitalization encountered.
                $list_of_files_case_insensitive[mb_strtolower($name) ] = $name;
            }
            $return .= $GLOBALS['app_strings']['LBL_SERVER_RESPONSE_RESOURCES'] . '(' . DBManager::getQueryCount() . ',' . sizeof($list_of_files_case_insensitive) . ')<br>';
            // Display performance of the internal and external caches....
            $cacheStats = SugarCache::instance()->getCacheStats();
            $return .= "External cache (hits/total=ratio) local ({$cacheStats['localHits']}/{$cacheStats['requests']}=" . round($cacheStats['localHits']*100/$cacheStats['requests'], 0) . "%)";
            $return .= " external ({$cacheStats['externalHits']}/{$cacheStats['requests']}=" . round($cacheStats['externalHits']*100/$cacheStats['requests'], 0) . "%)<br />";
            $return .= " misses ({$cacheStats['misses']}/{$cacheStats['requests']}=" . round($cacheStats['misses']*100/$cacheStats['requests'], 0) . "%)<br />";
        }

        $return .= $this->logMemoryStatistics();

        return $return;
    }

    /**
     * logMemoryStatistics
     *
     * This function returns a string message containing the memory statistics as well as writes to the memory_usage.log
     * file the memory statistics for the SugarView invocation.
     *
     * @param $newline String of newline character to use (defaults to </ br>)
     * @return $message String formatted message about memory statistics
     */
    protected function logMemoryStatistics($newline='<br>')
    {
        $log_message = '';

        if(!empty($GLOBALS['sugar_config']['log_memory_usage']))
        {
            if(function_exists('memory_get_usage'))
            {
                $memory_usage = memory_get_usage();
                $bytes = $GLOBALS['app_strings']['LBL_SERVER_MEMORY_BYTES'];
                $data = array($memory_usage, $bytes);
                $log_message = string_format($GLOBALS['app_strings']['LBL_SERVER_MEMORY_USAGE'], $data) . $newline;
            }

            if(function_exists('memory_get_peak_usage'))
            {
                $memory_peak_usage = memory_get_peak_usage();
                $bytes = $GLOBALS['app_strings']['LBL_SERVER_MEMORY_BYTES'];
                $data = array($memory_peak_usage, $bytes);
                $log_message .= string_format($GLOBALS['app_strings']['LBL_SERVER_PEAK_MEMORY_USAGE'], $data) . $newline;
            }

            if(!empty($log_message))
            {
                $data = array
                (
                   !empty($this->module) ? $this->module : $GLOBALS['app_strings']['LBL_LINK_NONE'],
                   !empty($this->action) ? $this->action : $GLOBALS['app_strings']['LBL_LINK_NONE'],
                );

                $output = string_format($GLOBALS['app_strings']['LBL_SERVER_MEMORY_LOG_MESSAGE'], $data) . $newline;
                $output .= $log_message;
                $fp = fopen("memory_usage.log", "ab");
                fwrite($fp, $output);
                fclose($fp);
            }
        }

        return $log_message;
    }


    /**
     * Loads the module shortcuts menu
     *
     * @param  $module string optional, can specify module to retrieve menu for if not the current one
     * @return array module menu
     */
    public function getMenu($module = null, $mod_strings_override = false)
    {
        global $current_language, $current_user, $app_strings, $module_menu, $mod_strings;

        if ( empty($module) )
            $module = $this->module;

        //Need to make sure the mod_strings match the requested module or Menus may fail
        $curr_mod_strings = $mod_strings;
        if($mod_strings_override !== false) {
            $mod_strings = $mod_strings_override;
        } else {
            $mod_strings = return_module_language($current_language, $module);
        }

        $module_menu = array();

        $menus = SugarAutoLoader::existing('modules/' . $module . '/Menu.php', SugarAutoLoader::loadExtension("menus", $module));
        foreach($menus as $file) {
            require $file;
        }
        if (empty($menus) && !empty($GLOBALS['mod_strings']['LNK_NEW_RECORD'])) {
            $module_menu[] = array("index.php?module=$module&action=EditView&return_module=$module&return_action=DetailView",
                $GLOBALS['mod_strings']['LNK_NEW_RECORD'],"{$GLOBALS['app_strings']['LBL_CREATE_BUTTON_LABEL']}$module" ,$module );
            $module_menu[] = array("index.php?module=$module&action=index", $GLOBALS['mod_strings']['LNK_LIST'],
                $module, $module);
            if ( ($this->bean instanceOf SugarBean) && !empty($this->bean->importable) )
                if ( !empty($mod_strings['LNK_IMPORT_'.strtoupper($module)]) )
                    $module_menu[] = array("index.php?module=Import&action=Step1&import_module=$module&return_module=$module&return_action=index",
                        $mod_strings['LNK_IMPORT_'.strtoupper($module)], "Import", $module);
                else
                    $module_menu[] = array("index.php?module=Import&action=Step1&import_module=$module&return_module=$module&return_action=index",
                        $app_strings['LBL_IMPORT'], "Import", $module);
        }
        if($this->base_menu) {
            require $this->base_menu;
        }

        $mod_strings = $curr_mod_strings;
        $builtModuleMenu = $module_menu;
        unset($module_menu);

        return $builtModuleMenu;
    }

    /**
    * Returns the module name which should be highlighted in the module menu
     */
    protected function _getModuleTab()
    {
        global $app_list_strings, $moduleTabMap, $current_user;

		$userTabs = query_module_access_list($current_user);
		//If the home tab is in the user array use it as the default tab, otherwise use the first element in the tab array
		$defaultTab = (in_array("Home",$userTabs)) ? "Home" : key($userTabs);

        // Need to figure out what tab this module belongs to, most modules have their own tabs, but there are exceptions.
        if ( !empty($_REQUEST['module_tab']) )
            return $_REQUEST['module_tab'];
        elseif ( isset($moduleTabMap[$this->module]) )
            return $moduleTabMap[$this->module];
        // Special cases
        elseif ( $this->module == 'MergeRecords' )
            return !empty($_REQUEST['merge_module']) ? $_REQUEST['merge_module'] : $_REQUEST['return_module'];
        elseif ( $this->module == 'Users' && $this->action == 'SetTimezone' )
            return $defaultTab;
        // Default anonymous pages to be under Home
        elseif ( !isset($app_list_strings['moduleList'][$this->module]) )
            return $defaultTab;
        else
            return $this->module;
    }

   /**
    * Return the "breadcrumbs" to display at the top of the page
    *
    * @param  bool $show_help optional, true if we show the help links
    * @return string HTML string containing breadcrumb title
    */
    public function getModuleTitle(
        $show_help = true
        )
    {
        global $sugar_version, $sugar_flavor, $server_unique_key, $current_language, $action;

        $theTitle = "<div class='moduleTitle'>\n";

        $module = preg_replace("/ /","",$this->module);

        $params = $this->_getModuleTitleParams();
        $index = 0;

		if(SugarThemeRegistry::current()->directionality == "rtl") {
			$params = array_reverse($params);
		}
		if(count($params) > 1) {
			array_shift($params);
		}
		$count = count($params);
        $paramString = '';
        foreach($params as $parm){
            $index++;
            $paramString .= $parm;
            if($index < $count){
                $paramString .= $this->getBreadCrumbSymbol();
            }
        }

        if(!empty($paramString)){
               $theTitle .= "<h2> $paramString </h2>\n";
           }


        // bug 56131 - restore conditional so that link doesn't appear where it shouldn't
        if($show_help) {
            $theTitle .= "<span class='utils'>";
            $createImageURL = SugarThemeRegistry::current()->getImageURL('create-record.gif');
            $url = 'index.php?' . http_build_query(
                array(
                    'module' => $module,
                    'action' => 'EditView',
                    'return_module' => $module,
                    'return_action' => 'DetailView',
                )
            );
            $theTitle .= <<<EOHTML
&nbsp;
<a id="create_image" href="{$url}" class="utilsLink">
<img src='{$createImageURL}' alt='{$GLOBALS['app_strings']['LNK_CREATE']}'></a>
<a id="create_link" href="{$url}" class="utilsLink">
{$GLOBALS['app_strings']['LNK_CREATE']}
</a>
EOHTML;
            $theTitle .= "</span>";
        }

        $theTitle .= "<div class='clear'></div></div>\n";
        return $theTitle;
    }

    /**
     * Return the metadata file that will be used by this view.
     *
     * @return string File location of the metadata file.
     */
    public function getMetaDataFile($type = null)
    {
        if ($type === null) {
            $type = $this->type;
        }
        $viewDef = strtolower($type) . 'viewdefs';
        return SugarAutoLoader::loadWithMetafiles($this->module, $viewDef);
    }


    /**
     * Returns an array composing of the breadcrumbs to use for the module title
     *
     * @param bool $browserTitle true if the returned string is being used for the browser title, meaning
     *                           there should be no HTML in the string
     * @return array
     */
    protected function _getModuleTitleParams($browserTitle = false)
    {
        $params = array($this->_getModuleTitleListParam($browserTitle));
        if (isset($this->action)){
            switch ($this->action) {
            case 'EditView':
                if(!empty($this->bean->id) && (empty($_REQUEST['isDuplicate']) || $_REQUEST['isDuplicate'] === 'false')) {
                    $params[] = "<a href='index.php?module={$this->module}&action=DetailView&record={$this->bean->id}'>".$this->bean->get_summary_text()."</a>";
                    $params[] = $GLOBALS['app_strings']['LBL_EDIT_BUTTON_LABEL'];
                }
                else
                    $params[] = $GLOBALS['app_strings']['LBL_CREATE_BUTTON_LABEL'];
                break;
            case 'DetailView':
                // We cannot assume we will always have a bean, especially in
                // cases like hitting Administration and not having permission
                if ($this->bean) {
                    $beanName = $this->bean->get_summary_text();
                    if($this->bean->isFavoritesEnabled()) {
                        $beanName .= '&nbsp;' . SugarFavorites::generateStar(SugarFavorites::isUserFavorite($this->module, $this->bean->id), $this->module, $this->bean->id);
                    }
                    $params[] = $beanName;
                }
                break;
            }
        }

        return $params;
    }

    /**
     * Returns the portion of the array that will represent the listview in the breadcrumb
     *
     * @param bool $browserTitle true if the returned string is being used for the browser title, meaning
     *                           there should be no HTML in the string
     * @return string
     */
    protected function _getModuleTitleListParam( $browserTitle = false )
    {
        global $current_user;
        global $app_strings;

        if (!empty($GLOBALS['app_list_strings']['moduleList'][$this->module])) {
            $firstParam = $GLOBALS['app_list_strings']['moduleList'][$this->module];
        } else {
            $firstParam = $this->module;
        }

    	$iconPath = $this->getModuleTitleIconPath($this->module);

        if ($this->action == "ListView" || $this->action == "index") {
            if (empty($iconPath) || $browserTitle) {
                return $firstParam;
            } elseif (SugarThemeRegistry::current()->directionality == "ltr") {
                return $app_strings['LBL_SEARCH']. "&nbsp;" . $firstParam;
            } else {
                return $firstParam . "&nbsp;" . $app_strings['LBL_SEARCH'];
            }
        } elseif (empty($iconPath) || $browserTitle) {
            return $firstParam;
        }
    }

    protected function getModuleTitleIconPath($module)
    {
    	$iconPath = "";
    	if(is_file(SugarThemeRegistry::current()->getImageURL('icon_'.$module.'_32.png',false))) {
    		$iconPath = SugarThemeRegistry::current()->getImageURL('icon_'.$module.'_32.png');
    	}
    	else if (is_file(SugarThemeRegistry::current()->getImageURL('icon_'.ucfirst($module).'_32.png',false))) {
    		$iconPath = SugarThemeRegistry::current()->getImageURL('icon_'.ucfirst($module).'_32.png');
    	}
    	return $iconPath;
    }

    /**
     * Returns the string which will be shown in the browser's title; defaults to using the same breadcrumb
     * as in the module title
     *
     * @return string
     */
    public function getBrowserTitle()
    {
        global $app_strings;

        if ($this->browserTitle !== null) {
            return $this->browserTitle;
        }

        $browserTitle = $app_strings['LBL_BROWSER_TITLE'];
        if ( $this->module == 'Users' && ($this->action == 'SetTimezone' || $this->action == 'Login') )
            return $browserTitle;
        $params = $this->_getModuleTitleParams(true);
        foreach ($params as $value )
            $browserTitle = strip_tags($value) . ' &raquo; ' . $browserTitle;

        return $browserTitle;
    }

    /**
     * Sets the string which will be shown in the browser's title
     *
     * @param string $browserTitle Browser title
     *
     * @return string
     */
    public function setBrowserTitle($browserTitle)
    {
        $this->browserTitle = $browserTitle;
    }

    /**
     * Returns the correct breadcrumb symbol according to theme's directionality setting
     *
     * @return string
     */
    public function getBreadCrumbSymbol()
    {
        return self::staticGetBreadCrumbSymbol();
    }

    /**
     * Returns the correct breadcrumb symbol according to theme's directionality setting
     *
     * @return string
     */
    public static function staticGetBreadCrumbSymbol()
    {
        if (SugarThemeRegistry::current()->directionality == "ltr") {
            return "<span class='breadCrumbSymbol'>&raquo;</span>";
        } else {
            return "<span class='breadCrumbSymbol'>&laquo;</span>";
        }
    }

    /**
     * Fetch config values to be put into an array for JavaScript
     *
     * @return array
     */
    protected function getSugarConfigJS(){
        global $sugar_config;

        // Set all the config parameters in the JS config as necessary
        $config_js = array();
        if (!empty($sugar_config['js_available']) && is_array ($sugar_config['js_available']))
        {
            foreach ($sugar_config['js_available'] as $configKey)
            {
                if (isset($sugar_config[$configKey]))
                {
                    $jsVariableStatement = $this->prepareConfigVarForJs($configKey, $sugar_config[$configKey]);
                    if (!array_search($jsVariableStatement, $config_js))
                    {
                        $config_js[] = $jsVariableStatement;
                    }
                }
            }
        }

        return $config_js;
    }

    /**
     * Utility method to convert sugar_config values into a JS acceptable format.
     *
     * @param string $key       Config Variable Name
     * @param string $value     Config Variable Value
     * @return string
     */
    protected function prepareConfigVarForJs($key, $value)
    {
        $value = json_encode($value);
        return "SUGAR.config.{$key} = {$value};";
    }

    /**
     * getHelpText
     *
     * This is a protected function that returns the help text portion.  It is called from getModuleTitle.
     *
     * @param $module String the formatted module name
     * @return $theTitle String the HTML for the help text
     */
    protected function getHelpText($module)
    {
        $createImageURL = SugarThemeRegistry::current()->getImageURL('create-record.gif');
        $url = 'index.php?' . http_build_query(
            array(
                'module' => $module,
                'action' => 'EditView',
                'return_module' => $module,
                'return_action' => 'DetailView',
            )
        );
        $theTitle = <<<EOHTML
&nbsp;
<img src='{$createImageURL}' alt='{$GLOBALS['app_strings']['LNK_CREATE']}'>
<a href="{$url}" class="utilsLink">
{$GLOBALS['app_strings']['LNK_CREATE']}
</a>
EOHTML;
        return $theTitle;
    }

    /**
     * Retrieves favicon corresponding to currently requested module
     *
     * @return array
     */
    protected function getFavicon()
    {
        // get favicon
        if(isset($GLOBALS['sugar_config']['default_module_favicon']))
            $module_favicon = $GLOBALS['sugar_config']['default_module_favicon'];
        else
            $module_favicon = false;

        $themeObject = SugarThemeRegistry::current();

        $favicon = '';
        if ( $module_favicon )
            $favicon = $themeObject->getImageURL($this->module.'_favico.png',false);
        if ( !sugar_is_file($favicon) || !$module_favicon )
            $favicon = $themeObject->getImageURL('sugar_icon.ico',false);

        $extension = pathinfo($favicon, PATHINFO_EXTENSION);
        switch ($extension)
        {
            case 'png':
                $type = 'image/png';
                break;
            case 'ico':
                // fall through
            default:
                $type = 'image/x-icon';
                break;
        }

        return array(
            'url'  => getJSPath($favicon),
            'type' => $type,
        );
    }


    /**
     * getCustomFilePathIfExists
     * Substitute custom file if it exists
     * @param $file String of filename to check
     * @return $file String of filename including custom directory if found
     */
    protected function getCustomFilePathIfExists($file)
    {
        return SugarAutoLoader::existingCustomOne($file);
    }


    /**
     * fetchTemplate
     *
     * This function wraps the call to the fetch function of the Smarty variable for the view
     *
     * @param $file String path of the file to fetch
     * @return $content String content from resulting Smarty fetch operation on template
     */
    protected function fetchTemplate($file)
    {
        return $this->ss->fetch($file);
    }

    /**
     * handles the tracker output, and adds a link and a shortened name.
     * given html safe input, it will preserve html safety
     *
     * @param array $history - returned from the tracker
     * @return array augmented history with image link and shortened name
     */
    protected function processRecentRecords($history) {
        foreach ( $history as $key => $row ) {
            $history[$key]['item_summary_short'] = to_html(getTrackerSubstring($row['item_summary'])); //bug 56373 - need to re-HTML-encode
            $history[$key]['image'] = SugarThemeRegistry::current()
                ->getImage($row['module_name'],'border="0" align="absmiddle"',null,null,'.gif',$row['item_summary']);
        }
        return $history;
    }

    /**
	 * Determines whether the state of the post global array indicates there was an error uploading a
     * file that exceeds the post_max_size setting.  Such an error can be detected if:
     *  1. The Server['REQUEST_METHOD'] will still point to POST
     *  2. POST and FILES global arrays will be returned empty despite the request method
     * This also results in a redirect to the home page (due to lack of module and action in POST)
     *
	 * @return boolean indicating true or false
	 */
    public function checkPostMaxSizeError(){
         //if the referrer is post, and the post array is empty, then an error has occurred, most likely
         //while uploading a file that exceeds the post_max_size.
         if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
             $GLOBALS['log']->fatal($GLOBALS['app_strings']['UPLOAD_ERROR_HOME_TEXT']);
             return true;
        }
        return false;
    }

    /**
     * Return CSRF form token jscript
     * @return string
     */
    protected function getCsrfFormTokenJscript()
    {
        return sprintf(
            '<script>SUGAR.csrf = {}; SUGAR.csrf.form_token = "%s";</script>',
            CsrfAuthenticator::getInstance()->getFormToken()
        );
    }
}
