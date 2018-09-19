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
 * DuplicateView
 * @api
 */
class DuplicateView extends EditView
{
    /**
     * @see EditView::setup()
     */
    public function setup(
        $module,
        $focus = null,
        $metadataFile = null,
        $tpl = 'include/EditView/EditView.tpl',
        $createFocus = true
    ) {
        parent::setup($module, $focus, $metadataFile, $tpl, $createFocus);
        $this->isDuplicate = isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true';
    }
}
