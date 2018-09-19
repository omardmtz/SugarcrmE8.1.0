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

class SugarWidgetFieldImage extends SugarWidgetFieldVarchar
{

	function displayListPlain($layout_def) {
		$value = $this->_get_list_value($layout_def);
		return "<a href=\"javascript:SUGAR.image.lightbox('index.php?entryPoint=download&id=$value&type=SugarFieldImage&isTempFile=1')\">"
		       . translate("LBL_VIEW_IMAGE") . '</a>';
	}
}