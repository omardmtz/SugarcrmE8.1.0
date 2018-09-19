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
 * @deprecated Since 7.8
 * Class BypassRelationshipUpdateException
 */
class BypassRelationshipUpdateException extends Exception
{
    /*
       Thrown when a condition exists in a before_relationship_update Hook such that
       the Update should Not Actually Occur. Throwing this exception will abort the
       relationship update.
    */
}
