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
 *
 * This is a hack needed because in 6.5 SugarMerge tried to load upgraders from new path
 * but new upgraders are not compatible with old code
 *
 */
class SugarMerge7 extends SugarMerge
{
    protected $upgrader;

    public function setUpgrader($u)
    {
        $this->upgrader = $u;
        if(!empty($u->fp)) {
            $this->setLogFilePointer($u->fp);
        }
    }

    public function getNewPath()
    {
        // HACK, see above
        return '';
    }

    /**
     * Override so that we would have better logging
     * @see SugarMerge::createHistoryLog()
     */
    protected function createHistoryLog($module,$customFile,$file)
    {
        $historyPath = 'custom/' . MB_HISTORYMETADATALOCATION . "/modules/$module/metadata/$file";
        $history = new History($historyPath);
        $timeStamp = $history->append($customFile);
        $this->log("Created history file after merge with new file: " . $historyPath .'_'.$timeStamp);
    }

    /**
     * Log a message
     * @param string $message
     */
    protected function log($message)
    {
        if($this->upgrader) {
            $this->upgrader->log($message);
        }
    }

}


