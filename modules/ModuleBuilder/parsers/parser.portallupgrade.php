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
 * Special metadata parser implementation for upgrading old portal edit/detail views
 */
class ParserPortalUpgrade extends GridLayoutMetaDataParser
{
    /**
     * {@inheritDoc}
     *
     * Disable original constructor, since during upgrade we don't need to intantiate metadata implementation,
     * which in some cases (like KBDocuments) will fail
     */
    public function __construct()
    {
    }
}
