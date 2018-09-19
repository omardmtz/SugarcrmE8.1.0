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


class PMSERecordValidator extends PMSEBaseValidator implements PMSEValidate
{
    /**
     *
     * @param PMSERequest $request
     * @return \PMSERequest
     */
    public function validateRequest(PMSERequest $request)
    {
        $this->logger->info("Validate Request " . get_class($this));
        $this->logger->debug(array("Request data:", $request));

        $request->validate();
        return $request;
    }
}
