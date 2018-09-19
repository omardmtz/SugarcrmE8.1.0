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

// FIXME remove this view

class ViewAjaxUI extends SugarView
{
    /**
     * Constructor
     *
     * @see SugarView::__construct()
     */
 	public function __construct()
 	{
 		$this->options['show_title'] = true;
		$this->options['show_header'] = true;
		$this->options['show_footer'] = true;
		$this->options['show_javascript'] = true;
		$this->options['show_subpanels'] = false; 
		$this->options['show_search'] = false;
		
        parent::__construct();
 	}

    public function display()
 	{
 		$user = $GLOBALS["current_user"];
 		$etag = $user->id . $user->getETagSeed("mainMenuETag");
        $etag .= $GLOBALS['current_language'];
         //Include fts engine name in etag so we don't cache searchbar.
        $etag .= SugarSearchEngineFactory::getFTSEngineNameFromConfig();
        $etag = md5($etag);
 		generateEtagHeader($etag);
        //Prevent double footers
        $GLOBALS['app']->headerDisplayed = false;
 	}
}
