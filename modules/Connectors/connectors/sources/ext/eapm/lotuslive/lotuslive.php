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


class ext_eapm_lotuslive extends source {
	protected $_enable_in_wizard = false;
	protected $_enable_in_hover = false;
	protected $_has_testing_enabled = false;

    // DEPRECATED in favor of IBM SmartCloud
    protected $_enable_in_admin_display = false;
    protected $_enable_in_admin_properties = false;

	public function getItem($args=array(), $module=null){}
	public function getList($args=array(), $module=null) {}
}
