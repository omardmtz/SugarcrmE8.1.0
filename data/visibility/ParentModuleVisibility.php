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
 * ACL Visiblity driven by a related module.
 * Only supports SugarQuery
 */
class ParentModuleVisibility extends ACLVisibility
{

    protected $parentLink = "";
    /**
     * @param SugarBean $bean
     */
    public function __construct($bean, $options)
    {
        if (!empty($options['parentLink'])) {
            $this->parentLink = $options['parentLink'];
        }

        return parent::__construct($bean);
    }

    /**
     * Add visibility clauses to the FROM part of the query
     *
     * @param string $query
     *
     * @return string
     */
    public function addVisibilityFromQuery(SugarQuery $query)
    {
        if (!empty($this->parentLink))
        {
            $linkName = $this->parentLink;
            $query->from->load_relationship($linkName);
            if(empty($query->from->$linkName)) {
                throw new SugarApiExceptionInvalidParameter("Invalid link $linkName for owner clause");
            }
            if($query->from->$linkName->getType() == "many") {
                throw new SugarApiExceptionInvalidParameter("Cannot serch for owners through multi-link $linkName");
            }
            $this->join = $query->join($linkName, array('joinType' => 'LEFT'));
        }

        return $query;
    }
}
