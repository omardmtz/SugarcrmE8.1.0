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
 * This class is used to allow a module to inherit its ACL's from a different related module
 */
class SugarACLParentModule extends SugarACLStrategy
{
    protected $parentModule = '';
    protected $parentLink = '';
    //Can only validate owner against a known subset of the normal check acccess actions
    protected static $requiresOwnerCheck = array('delete', 'edit', 'detail', 'view', 'read');

    public function __construct($aclOptions)
    {
        if (is_array($aclOptions) && !empty($aclOptions['parentModule'])) {
            $this->parentModule = $aclOptions['parentModule'];
        }
        if (!empty($aclOptions['parentLink'])) {
            $this->parentLink = $aclOptions['parentLink'];
        }
    }

    /**
     * Only allow access to users with the user admin setting
     *
     * @param string $module
     * @param string $view
     * @param array  $context
     *
     * @return bool|void
     */
    public function checkAccess($module, $action, $context)
    {
        $action = $this->fixUpActionName($action);

        if ($action == "field") {
            return true;
        }

        if (!empty($this->parentLink)) {
            $linkName = $this->parentLink;
            $bean = SugarACL::loadBean($module, $context);
            $bean->load_relationship($linkName);
            if (empty($bean->$linkName)) {
                throw new SugarException("Invalid link $linkName for parent ACL");
            }
            if ($bean->$linkName->getType() == "many") {
                throw new SugarException("Cannot serch for owners through multi-link $linkName");
            }
            $parentModule = $bean->$linkName->getRelatedModuleName();
            if (!empty($this->parentModule) && $parentModule != $this->parentModule) {
                throw new SugarException("Cannot search for owners through link with incorrect module $parentModule");
            }
            if (in_array($action, self::$requiresOwnerCheck)) {
                //Check ACL's that require a parent such as edit/detail
                $parentIds = $bean->$linkName->get();
                if (is_array($parentIds) && !empty($parentIds)) {
                    $parentId = $parentIds[0];
                    $parentBean = BeanFactory::getBean($parentModule, $parentId);
                    //The parent failed to retrieve, you probably don't have access
                    if (empty($parentBean->id)) {
                        return false;
                    }
                    $context['bean'] = $parentBean;
                    return $parentBean->ACLAccess($action, $context);
                }

            } else {
                //Fall here for ACL's like list that don't require a parent to check
                //Don't pass the context since the bean won't match the module.
                //We also can't check owner at this level since we don't have the bean so owner_override must be true
                unset($context['bean']);
                $context['owner_override'] = true;

                return SugarACL::checkAccess($parentModule, $action, $context);
            }
        }

        return true;
    }

    /**
     * Get user access for the list of actions
     *
     * @param string $module
     * @param array  $access_list List of actions
     *
     * @returns array - List of access levels. Access levels not returned are assumed to be "all allowed".
     */
    public function getUserAccess($module, $access_list, $context)
    {
        if (!empty($this->parentModule)) {
            //Don't pass the context bean since it won't match the module.
            $parentContext = array('owner_override' => true);
            if (!empty($context['user'])) {
                $parentContext['user'] = $context['user'];
            }
            return SugarACL::getUserAccess($this->parentModule, $access_list, $parentContext);
        }

        return array();

    }

}
