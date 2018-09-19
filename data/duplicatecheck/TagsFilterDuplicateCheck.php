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
 * This method of duplicate check passes a configurable set of filters off to the Filter API to find duplicates.
 */
class TagsFilterDuplicateCheck extends FilterDuplicateCheck
{
    /**
     * @inheritDoc
     */
    public function getValueFromField($inField)
    {
        // For tags we want lower case name duplicate comparison
        return strtolower($this->bean->$inField);
    }
}
