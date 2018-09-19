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

class SugarWidgetFieldId extends SugarWidgetReportField
{
    public function queryFilterIs($layout_def)
 {
		return $this->_get_column_select($layout_def)."='".$GLOBALS['db']->quote($layout_def['input_name0'])."'\n";
 }


}
