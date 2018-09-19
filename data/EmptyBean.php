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
 * Empty bean - to perform non-static functions on bean without loading specific beans
 */
class EmptyBean extends SugarBean
{
    // this bean has no vardefs
    public $disable_vardefs = true;
    // no custom fields
    public $disable_custom_fields = true;
}
