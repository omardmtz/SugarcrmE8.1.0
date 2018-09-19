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
 * Class to run a job which should remove temporary files
 */
class SugarJobRemoveTmpFiles extends SugarJobRemoveFiles
{
    /**
     * {@inheritDoc}
     */
    protected function getDirectory()
    {
        return 'upload://tmp';
    }

    /**
     * {@inheritDoc}
     */
    protected function getMaxLifetime()
    {
        global $sugar_config;

        if (isset($sugar_config['tmp_file_max_lifetime'])) {
            return $sugar_config['tmp_file_max_lifetime'];
        }

        return null;
    }
}
