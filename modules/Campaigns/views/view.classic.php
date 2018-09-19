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

class CampaignsViewClassic extends SugarView
{
    public function __construct()
 	{
        parent::__construct();
 		$this->type = $this->action;
 	}

 	/**
	 * @see SugarView::display()
	 */
	public function display()
	{
 		// Call SugarController::getActionFilename to handle case sensitive file names
 		$file = SugarController::getActionFilename($this->action);
 		$classic = SugarAutoLoader::existingCustomOne('modules/' . $this->module . '/'. $file . '.php');
 		if($classic) {
 		    $this->includeClassicFile($classic);
 		    return true;
 		}
		return false;
 	}

    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
    	$params = array();
    	$params[] = $this->_getModuleTitleListParam($browserTitle);
    	if (isset($this->action)){
    		switch($_REQUEST['action']){
    				case 'WizardHome':
				    	if(!empty($this->bean->id))
				    	{
				    		$params[] = "<a href='index.php?module={$this->module}&action=DetailView&record={$this->bean->id}'>".$this->bean->name."</a>";
				    	}
				    	$params[] = $GLOBALS['mod_strings']['LBL_CAMPAIGN_WIZARD'];
				    	break;
				    case 'WebToLeadCreation':
    					$params[] = $GLOBALS['mod_strings']['LBL_LEAD_FORM_WIZARD'];
    					break;
    				case 'WizardNewsletter':

                        if (isset($_REQUEST['wizardtype']) && $_REQUEST['wizardtype'] == '2') {
                            if (!empty($this->bean->id)) {
                                $params[] = "<a href='index.php?module={$this->module}&action=DetailView&record={$this->bean->id}'>".$GLOBALS['mod_strings']['LBL_EMAIL_TITLE']."</a>";
                            }
                            $params[] = $GLOBALS['mod_strings']['LBL_CREATE_EMAIL'];
                        } else {
                            if (!empty($this->bean->id)) {
				    		    $params[] = "<a href='index.php?module={$this->module}&action=DetailView&record={$this->bean->id}'>".$GLOBALS['mod_strings']['LBL_NEWSLETTER_TITLE']."</a>";
				    	    }
                            $params[] = $GLOBALS['mod_strings']['LBL_CREATE_NEWSLETTER'];

                        }
				    	break;
    				case 'CampaignDiagnostic':
    					$params[] = $GLOBALS['mod_strings']['LBL_CAMPAIGN_DIAGNOSTICS'];
    					break;
    				case 'WizardEmailSetup':
    					$params[] = $GLOBALS['mod_strings']['LBL_EMAIL_SETUP_WIZARD_TITLE'];
    					break;
    				case 'TrackDetailView':
    					if(!empty($this->bean->id))
    					{
	    					$params[] = "<a href='index.php?module={$this->module}&action=DetailView&record={$this->bean->id}'>".$this->bean->name."</a>";
    					}
	    				$params[] = $GLOBALS['mod_strings']['LBL_LIST_TO_ACTIVITY'];
    					break;
    		}//switch
    	}//fi

    	return $params;
    }
}
