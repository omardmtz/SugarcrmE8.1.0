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

class SugarQuery_Builder_Groupby
{
    /**
     * @var null|SugarQuery_Builder_Field_Groupby
     */
    public $column;

    /**
     * @var SugarQuery
     */
    public $query;

    public function __construct(SugarQuery $query)
    {
        $this->query = $query;
    }

    public function addField($column)
    {
        $this->column = new SugarQuery_Builder_Field_Groupby($column, $this->query);
        return $this;
    }
    
    public function addRaw($expression) {
        $this->column = new SugarQuery_Builder_Field_Raw($expression, $this->query);
        return $this;
    }
}
