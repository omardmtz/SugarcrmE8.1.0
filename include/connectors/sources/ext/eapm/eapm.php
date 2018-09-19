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
 * Generic source connected using EAPM access details
 * @api
 */
abstract class ext_eapm extends source{

    /**
     * The ExternalAPI Base that instantiated this connector.
     * @var _eapm
     */
    protected $_eapm;

    public function setEAPM(ExternalAPIBase $eapm)
    {
        $GLOBALS['log']->debug("Connector is setting eapm");
        $this->_eapm = $eapm;
    }

    public function getEAPM()
    {
        $GLOBALS['log']->debug("Connector is getting eapm");
        return $this->_eapm;
    }

}
?>
