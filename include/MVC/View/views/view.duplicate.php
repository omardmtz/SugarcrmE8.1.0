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
 * Class ViewDuplicate
 */
class ViewDuplicate extends ViewEdit
{
    /**
     * Re-declare view type to pass ACL.
     * @var string
     */
    public $type = 'duplicate';

    /**
     * @see SugarView::process()
     */
    public function process($params = array())
    {
        //Return view type to edit to render EditView page.
        $this->type = 'edit';
        parent::process($params);
    }

    /**
     * Get DuplicateView object
     * @return DuplicateView
     */
    protected function getEditView()
    {
        return new DuplicateView();
    }
}
