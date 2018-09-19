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



class InvadersDashlet extends Dashlet {
    var $savedText; // users's saved text
    var $height = '100'; // height of the pad

    /**
     * Constructor
     *
     * @global string current language
     * @param guid $id id for the current dashlet (assigned from Home module)
     * @param array $def options saved for this dashlet
     */
    public function __construct($id, $def)
    {
        $this->loadLanguage('InvadersDashlet'); // load the language strings here

        if(!empty($def['height'])) // set a default height if none is set
            $this->height = $def['height'];

        parent::__construct($id); // call parent constructor

        $this->isConfigurable = false; // dashlet is configurable
        $this->hasScript = true;  // dashlet has javascript attached to it

        // if no custom title, use default
        if(empty($def['title'])) $this->title = $this->dashletStrings['LBL_TITLE'];
        else $this->title = $def['title'];
    }

    /**
     * {@inheritDoc}
     * @param string $text Ignored
     */
    public function display($text = '')
    {
        $ss = new Sugar_Smarty();
        $ss->assign('id', $this->id);
        $ss->assign('height', $this->height);
        $ss->assign('dashletStrings', $this->dashletStrings);

        $str = $ss->fetch('modules/Home/Dashlets/InvadersDashlet/InvadersDashlet.tpl');
        return parent::display($this->dashletStrings['LBL_DBLCLICK_HELP']) . $str . '<br />'; // return parent::display for title and such
    }

    /**
     * Displays the javascript for the dashlet
     *
     * @return string javascript to use with this dashlet
     */
    function displayScript() {
        $ss = new Sugar_Smarty();
        $ss->assign('id', $this->id);
        $ss->assign('dashletStrings', $this->dashletStrings);

        $str = $ss->fetch('modules/Home/Dashlets/InvadersDashlet/InvadersDashletScript.tpl');
        return $str; // return parent::display for title and such
    }

    /**
     * Displays the configuration form for the dashlet
     *
     * @return string html to display form
     */
    function displayOptions() {
        global $app_strings;

        $ss = new Sugar_Smarty();
        $ss->assign('titleLbl', $this->dashletStrings['LBL_CONFIGURE_TITLE']);
        $ss->assign('heightLbl', $this->dashletStrings['LBL_CONFIGURE_HEIGHT']);
        $ss->assign('saveLbl', $app_strings['LBL_SAVE_BUTTON_LABEL']);
        $ss->assign('clearLbl', $app_strings['LBL_CLEAR_BUTTON_LABEL']);
        $ss->assign('title', $this->title);
        $ss->assign('height', $this->height);
        $ss->assign('id', $this->id);

        return parent::displayOptions() . $ss->fetch('modules/Home/Dashlets/InvadersDashlet/InvadersOptions.tpl');
    }

    /**
     * called to filter out $_REQUEST object when the user submits the configure dropdown
     *
     * @param array $req $_REQUEST
     * @return array filtered options to save
     */
    function saveOptions($req) {
        global $sugar_config, $timedate, $current_user, $theme;
        $options = array();
        $options['title'] = $_REQUEST['title'];
        if(is_numeric($_REQUEST['height'])) {
            if($_REQUEST['height'] > 0 && $_REQUEST['height'] <= 300) $options['height'] = $_REQUEST['height'];
            elseif($_REQUEST['height'] > 300) $options['height'] = '300';
            else $options['height'] = '100';
        }

//        $options['savedText'] = br2nl($this->savedText);
        $options['savedText'] = $this->savedText;

        return $options;
    }

    /**
     * Used to save text on textarea blur. Accessed via Home/CallMethodDashlet.php
     * This is an example of how to to call a custom method via ajax
     */
    function saveText() {
        if(isset($_REQUEST['savedText'])) {
            $optionsArray = $this->loadOptions();
//            _pp($_REQUEST['savedText']);
            $optionsArray['savedText'] = nl2br($_REQUEST['savedText']);
            $this->storeOptions($optionsArray);

        }
        else {
            $optionsArray['savedText'] = '';
        }
        $json = getJSONobj();
        header("Content-Type: application/json");
        $guid = InputValidation::getService()->getValidInputRequest('id', 'Assert\Guid', '');
        echo $json->encode(array('id' => $guid, 'savedText' => $optionsArray['savedText']));
    }
}

