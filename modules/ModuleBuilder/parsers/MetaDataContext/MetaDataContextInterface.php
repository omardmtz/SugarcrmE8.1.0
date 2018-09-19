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
 * Metadata context interface
 *
 * Describes the context which the metadata is effective in
 */
interface MetaDataContextInterface
{
    /**
     * Returns the context hash (used for caching context specific metadata).
     *
     * @return array
     */
    public function getHash();

    /**
     * Checks if the given metadata file is valid in the context.
     *
     * @param array $file Metadata file info
     *
     * @return boolean
     */
    public function isValid(array $file);

    /**
     * Compares the priority of two metadata files
     *
     * @param array $a Metadata file info
     * @param array $b Metadata file info
     *
     * @return int
     */
    public function compare(array $a, array $b);
}
