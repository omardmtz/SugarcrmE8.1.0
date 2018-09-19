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


/**
 * PHP wrapper class for Javascript driven TinyMCE WYSIWYG HTML editor
 */
class SugarTinyMCE
{
    var $jsroot = "include/javascript/tiny_mce/";
    var $customConfigFile = 'custom/include/tinyButtonConfig.php';
    public $customPluginConfigFile = 'custom/include/tinyPluginConfig.php';
    var $customDefaultConfigFile = 'custom/include/tinyMCEDefaultConfig.php';
    var $buttonConfigs
        = array(
            'default'             => array(
                'buttonConfig'  => "code,help,separator,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,
	                     					justifyfull,separator,forecolor,backcolor,separator,styleselect,formatselect,fontselect,fontsizeselect,",
                'buttonConfig2' => "cut,copy,paste,pastetext,pasteword,selectall,separator,search,replace,separator,bullist,numlist,separator,outdent,
	                     					indent,separator,ltr,rtl,separator,undo,redo,separator, link,unlink,anchor,image,separator,sub,sup,separator,charmap,
	                     					visualaid",
                'buttonConfig3' => "tablecontrols,separator,advhr,hr,removeformat,separator,insertdate,inserttime,separator,preview,spellchecker"
            ),
            'email_compose'       => array(
                'buttonConfig'  => "code,help,separator,bold,italic,underline,strikethrough,separator,bullist,numlist,separator,justifyleft,justifycenter,justifyright,
	                     					justifyfull,separator,forecolor,backcolor,separator,spellchecker,seperator,styleselect,formatselect,fontselect,fontsizeselect",
                'buttonConfig2' => "",
                'buttonConfig3' => ""
            ),
            'email_compose_light' => array(
                'buttonConfig'  => "code,help,separator,bold,italic,underline,strikethrough,separator,bullist,numlist,separator,justifyleft,justifycenter,justifyright,
	                     					justifyfull,separator,forecolor,backcolor,separator,spellchecker,seperator,styleselect,formatselect,fontselect,fontsizeselect",
                'buttonConfig2' => "",
                'buttonConfig3' => ""
            ),
        );

    var $pluginsConfig
        = array(
            'email_compose_light' => 'insertdatetime,paste,directionality,safari,spellchecker',
            'email_compose'       => 'advhr,insertdatetime,table,preview,paste,searchreplace,directionality,fullpage,spellchecker',
        );


    var $defaultConfig
        = array(
            'convert_urls'                      => false,
            'valid_children'                    => '+body[style]',
            'height'                            => 300,
            'width'                             => '100%',
            'theme'                             => 'advanced',
            'theme_advanced_toolbar_align'      => "left",
            'theme_advanced_toolbar_location'   => "top",
            'theme_advanced_buttons1'           => "",
            'theme_advanced_buttons2'           => "",
            'theme_advanced_buttons3'           => "",
            'theme_advanced_statusbar_location' => "none",
            'strict_loading_mode'               => true,
            'mode'                              => 'exact',
            'language'                          => 'en',
            'plugins'                           => 'advhr,insertdatetime,table,preview,paste,searchreplace,directionality,spellchecker',
            'elements'                          => '',
            'extended_valid_elements'           => 'style[dir|lang|media|title|type],hr[class|width|size|noshade],@[class|style]',
            'content_css'                       => 'include/javascript/tiny_mce/themes/advanced/skins/default/content.css',

        );


    /**
     * Sole constructor
     */
    public function __construct()
    {
        $this->overloadButtonConfigs();
        $this->overloadDefaultConfigs();
        $this->overloadPluginConfigs();
    }

    /**
     * Returns the Javascript necessary to initialize a TinyMCE instance for a given <textarea> or <div>
     *
     * @param string target Comma delimited list of DOM ID's, <textarea id='someTarget'>
     *
     * @return string
     */
    function getInstance($targets = "")
    {
        global $json;

        if (empty($json)) {
            $json = getJSONobj();
        }

        $config = $this->defaultConfig;
        //include tinymce lang file
        $lang = substr($GLOBALS['current_language'], 0, 2);
        if (SugarAutoLoader::existing('include/javascript/tiny_mce/langs/' . $lang . '.js')) {
            $config['language'] = $lang;
        }
        $config['directionality']          = SugarThemeRegistry::current()->directionality;
        $config['elements']                = $targets;
        $config['theme_advanced_buttons1'] = $this->buttonConfigs['default']['buttonConfig'];
        $config['theme_advanced_buttons2'] = $this->buttonConfigs['default']['buttonConfig2'];
        $config['theme_advanced_buttons3'] = $this->buttonConfigs['default']['buttonConfig3'];

        $jsConfig = $json->encode($config);

        $instantiateCall = '';
        $path = getJSPath('include/javascript/tiny_mce/tiny_mce.js');
        $ret
                   = <<<eoq
<script type="text/javascript" language="Javascript" src="$path"></script>

<script type="text/javascript" language="Javascript">
<!--
if (!SUGAR.util.isTouchScreen()) {
    tinyMCE.init({$jsConfig});
	{$instantiateCall}
}
else {
eoq;
        $exTargets = explode(",", $targets);
        foreach ($exTargets as $instance) {
            $ret
                .= <<<eoq
    document.getElementById('$instance').style.width = '100%';
    document.getElementById('$instance').style.height = '100px';
eoq;
        }
        $ret
            .= <<<eoq
}
-->
</script>

eoq;
        return $ret;
    }

    function getConfig($type = 'default')
    {
        global $json;

        if (empty($json)) {
            $json = getJSONobj();
        }

        $config = $this->defaultConfig;
        //include tinymce lang file
        $lang = substr($GLOBALS['current_language'], 0, 2);
        if (SugarAutoLoader::existing('include/javascript/tiny_mce/langs/' . $lang . '.js')) {
            $config['language'] = $lang;
        }
        $config['theme_advanced_buttons1'] = $this->buttonConfigs[$type]['buttonConfig'];
        $config['theme_advanced_buttons2'] = $this->buttonConfigs[$type]['buttonConfig2'];
        $config['theme_advanced_buttons3'] = $this->buttonConfigs[$type]['buttonConfig3'];

        if (isset($this->pluginsConfig[$type])) {
            $config['plugins'] = $this->pluginsConfig[$type];
        }

        $jsConfig = $json->encode($config);
        return "var tinyConfig = " . $jsConfig . ";";

    }

    /**
     * This function takes in html code that has been produced (and somewhat mauled) by TinyMCE
     * and returns a cleaned copy of it.
     *
     * @param $html
     *
     * @return $html with all the tinyMCE specific html removed
     */
    function cleanEncodedMCEHtml($html)
    {
        $html = str_replace("mce:script", "script", $html);
        $html = str_replace("mce_src=", "src=", $html);
        $html = str_replace("mce_href=", "href=", $html);
        return $html;
    }

    /**
     * Reload the default button configs by allowing admins to specify
     * which tinyMCE buttons will be displayed in a separate config file.
     *
     */
    private function overloadButtonConfigs()
    {
        if (SugarAutoLoader::existing($this->customConfigFile)) {
            require_once($this->customConfigFile);
        }

        $defs = SugarAutoLoader::loadExtension('tinymce');
        if ($defs) {
            require($defs);
        }

        if (!isset($buttonConfigs)) {
            return;
        }

        foreach ($buttonConfigs as $k => $v) {
            $this->buttonConfigs[$k] = $v;
        }
    }

    /**
     * Reload the default tinyMCE plugin config
     *
     */
    private function overloadPluginConfigs()
    {
        if (SugarAutoLoader::existing($this->customPluginConfigFile)) {
            require_once($this->customPluginConfigFile);
        }

        $defs = SugarAutoLoader::loadExtension('tinymce');
        if ($defs) {
            require($defs);
        }

        if (!isset($pluginsConfig)) {
            return;
        }

        foreach ($pluginsConfig as $k => $v) {
            $this->pluginsConfig[$k] = $v;
        }
    }

    /**
     * Reload the default tinyMCE config
     *
     */
    private function overloadDefaultConfigs()
    {
        if (SugarAutoLoader::existing($this->customDefaultConfigFile)) {
            require_once($this->customDefaultConfigFile);
        }

        $defs = SugarAutoLoader::loadExtension('tinymce');
        if ($defs) {
            require($defs);
        }

        if (!isset($defaultConfig)) {
            return;
        }

        foreach ($defaultConfig as $k => $v) {
            if ($k == "extended_valid_elements") {
                $this->defaultConfig[$k] .= "," . $v;
            } else {
                $this->defaultConfig[$k] = $v;
            }
        }
    }

} // end class def
