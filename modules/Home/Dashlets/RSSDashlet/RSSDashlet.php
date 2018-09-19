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


class RSSDashlet extends Dashlet
{
    protected $url = 'http://www.sugarcrm.com/crm/aggregator/rss/1';
    protected $height = '200'; // height of the pad
    protected $images_dir = 'modules/Home/Dashlets/RSSDashlet/images';

    /**
     * Constructor
     *
     * @global string current language
     * @param guid $id id for the current dashlet (assigned from Home module)
     * @param array $def options saved for this dashlet
     */
    public function __construct($id, $def)
    {
        $this->loadLanguage('RSSDashlet', 'modules/Home/Dashlets/'); // load the language strings here

        if(!empty($def['height'])) // set a default height if none is set
            $this->height = $def['height'];

        if(!empty($def['url']))
            $this->url = $def['url'];

        if(!empty($def['title']))
            $this->title = $def['title'];
        else
            $this->title = $this->dashletStrings['LBL_TITLE'];

        if(isset($def['autoRefresh'])) $this->autoRefresh = $def['autoRefresh'];

        parent::__construct($id); // call parent constructor

        $this->isConfigurable = true; // dashlet is configurable
        $this->hasScript = false;  // dashlet has javascript attached to it
    }

    /**
     * {@inheritDoc}
     * @param string $text Ignored
     */
    public function display($text = '')
    {
        $ss = new Sugar_Smarty();
        $ss->assign('saving', $this->dashletStrings['LBL_SAVING']);
        $ss->assign('saved', $this->dashletStrings['LBL_SAVED']);
        $ss->assign('id', $this->id);
        $ss->assign('height', $this->height);
        $ss->assign('rss_output', $this->getRSSOutput($this->url));
        $str = $ss->fetch('modules/Home/Dashlets/RSSDashlet/RSSDashlet.tpl');
        return parent::display($this->dashletStrings['LBL_DBLCLICK_HELP']) . $str; // return parent::display for title and such
    }

    /**
     * Displays the configuration form for the dashlet
     *
     * @return string html to display form
     */
    public function displayOptions() {
        global $app_strings, $sugar_version, $sugar_config;

        $ss = new Sugar_Smarty();
        $ss->assign('titleLbl', $this->dashletStrings['LBL_CONFIGURE_TITLE']);
        $ss->assign('heightLbl', $this->dashletStrings['LBL_CONFIGURE_HEIGHT']);
        $ss->assign('rssUrlLbl', $this->dashletStrings['LBL_CONFIGURE_RSSURL']);
        $ss->assign('saveLbl', $app_strings['LBL_SAVE_BUTTON_LABEL']);
        $ss->assign('clearLbl', $app_strings['LBL_CLEAR_BUTTON_LABEL']);
        $ss->assign('title', $this->title);
        $ss->assign('height', $this->height);
        $ss->assign('url', $this->url);
        $ss->assign('id', $this->id);
        if($this->isAutoRefreshable()) {
       		$ss->assign('isRefreshable', true);
			$ss->assign('autoRefresh', $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_AUTOREFRESH']);
			$ss->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
			$ss->assign('autoRefreshSelect', $this->autoRefresh);
		}

        return parent::displayOptions() . $ss->fetch('modules/Home/Dashlets/RSSDashlet/RSSDashletOptions.tpl');
    }

    /**
     * called to filter out $_REQUEST object when the user submits the configure dropdown
     *
     * @param array $req $_REQUEST
     * @return array filtered options to save
     */
    public function saveOptions($req)
    {
        $options = array();
        $options['title'] = $req['title'];
        $options['url'] = $req['url'];
        $options['height'] = $req['height'];
        $options['autoRefresh'] = empty($req['autoRefresh']) ? '0' : $req['autoRefresh'];

        return $options;
    }

    protected function getRSSOutput(
        $url
        )
    {
        // suppress XML errors
        libxml_use_internal_errors(true);
        $urlparse = parse_url($url);
        if (empty($urlparse['scheme']) || empty($urlparse['host'])) {
            return $this->dashletStrings['ERR_LOADING_FEED'];
        }
        if ($urlparse['scheme'] != 'http' && $urlparse['scheme'] != 'https') {
            return $this->dashletStrings['ERR_LOADING_FEED'];
        }
        $data = file_get_contents($url);
        if (!$data) {
            return $this->dashletStrings['ERR_LOADING_FEED'];
        }
        libxml_disable_entity_loader(true);
        $rssdoc = simplexml_load_string($data);
        // return back the error message if the loading wasn't successful
        if (!$rssdoc)
            return $this->dashletStrings['ERR_LOADING_FEED'];

        $output = "<table class='edit view'>";
        if ( isset($rssdoc->channel) ) {
            foreach ( $rssdoc->channel as $channel ) {
                if ( isset($channel->item ) ) {
                    foreach ( $channel->item as $item ) {
                        $link = htmlspecialchars($item->link, ENT_QUOTES, 'UTF-8');
                        $title = htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');
                        $description = htmlspecialchars($item->description, ENT_QUOTES, 'UTF-8');
                        $output .= <<<EOHTML
<tr>
<td>
    <h3><a href="{$link}" target="_child">{$title}</a></h3>
    {$description}
</td>
</tr>
EOHTML;
                    }
                }
            }
        }
        else {
            foreach ( $rssdoc->entry as $entry ) {
                $link = trim($entry->link);
                if ( empty($link) ) {
                    $link = $entry->link[0]['href'];
                }
                $link = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
                $title = htmlspecialchars($entry->title, ENT_QUOTES, 'UTF-8');
                $summary = htmlspecialchars($entry->summary, ENT_QUOTES, 'UTF-8');
                $output .= <<<EOHTML
<tr>
<td>
    <h3><a href="{$link}" target="_child">{$title}</a></h3>
    {$summary}
</td>
</tr>
EOHTML;
            }
        }
        $output .= "</table>";

        return $output;
    }
}
