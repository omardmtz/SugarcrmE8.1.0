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
 * UsersViewQuickEdit.php
 * @author Collin Lee
 *
 * This class is a view extension of the include/MVC/View/views/view.edit.php file.  We are overriding the ViewQuickEdit class because the
 * Users module quick edit treatment has some specialized behavior during the save operation.  In particular, if the user's status is set to
 * Inactive, this needs to trigger a dialog to reassign records.  The quick edit functionality was introduced into the Users module in the 6.4 release.
 *
 */
require_once('include/MVC/View/views/view.quickedit.php');
require_once('include/EditView/EditView2.php');

class ProjectViewQuickedit extends ViewQuickEdit
{
    /**
     * @var headerTpl String variable of the Smarty template file used to render the header portion
     */
    protected $headerTpl = 'include/EditView/header.tpl';

    /**
     * @var footerTpl String variable of the Smarty template file used to render the footer portion
     */
    protected $footerTpl = 'include/EditView/footer.tpl';

    /**
     * @var defaultButtons Array of default buttons assigned to the form (see function.sugar_button.php)
     */
    protected $defaultButtons = array('DCMENUSAVE', 'DCMENUCANCEL', 'DCMENUFULLFORM');


    public function preDisplay()
    {

        if(!empty($_REQUEST['record'])) {
            $this->bean->retrieve($_REQUEST['record']);
            if($this->bean->is_template == 1){
                $this->footerTpl = 'modules/Project/tpls/QuickEditFooter.tpl';
                $this->headerTpl = 'modules/Project/tpls/QuickEditHeader.tpl';
                $this->defaultButtons = array('DCMENUCANCEL');
            }
        }
        return parent::preDisplay();
    }

}