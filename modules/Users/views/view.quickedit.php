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

class UsersViewQuickedit extends ViewQuickEdit
{
    /**
     * @var footerTpl String variable of the Smarty template file used to render the footer portion.  Override here to allow for record reassignment.
     */
    protected $footerTpl = 'modules/Users/tpls/QuickEditFooter.tpl';


    /**
     * @var defaultButtons Array of default buttons assigned to the form (see function.sugar_button.php)
     * We will still take the DCMENUCANCEL and DCMENUFULLFORM buttons, but we inject our own Save button via the QuickEditFooter.tpl file
     */
    protected $defaultButtons = array('DCMENUCANCEL', 'DCMENUFULLFORM');

}