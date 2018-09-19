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
 * Default view class for handling DetailViews
 *
 * @package MVC
 * @category Views
 */
class ViewDetail extends SugarView
{
    /**
     * @see SugarView::$type
     */
    public $type = 'detail';

    /**
     * @var DetailView2 object
     */
    public $dv;

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
 	    $metadataFile = $this->getMetaDataFile();
 	    $this->dv = new DetailView2();
 	    $this->dv->ss = $this->ss;
 	    $this->dv->setup($this->module, $this->bean, $metadataFile, SugarAutoLoader::existingCustomOne('include/DetailView/DetailView.tpl'));
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        if(empty($this->bean->id)){
            sugar_die($GLOBALS['app_strings']['ERROR_NO_RECORD']);
        }
        $this->dv->process();
        echo $this->dv->display();
    }
}
