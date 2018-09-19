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

class SugarForecasting_ReportingUsers extends SugarForecasting_AbstractForecast
{
    /**
     * Process to get an array of Users for the user that was passed in
     *
     * @return array|string
     */
    public function process()
    {
        $userId = $this->getArg('user_id');
        $isManager = User::isManager($userId);
        $loggedInUserId = $GLOBALS['current_user']->id;
        $loggedInUserIsManager = User::isManager($loggedInUserId);
        $children = array();
        $userBean = BeanFactory::getBean('Users', $userId);
        $tree = null;

        //if manager, get the children nodes. else, load the user's manager and children (if the logged in user is a
        //manager)
        if ($isManager) {
            $children = $this->getChildren($userBean);
        } else if (!$isManager && $loggedInUserIsManager) {
            $userBean = BeanFactory::getBean('Users', $userBean->reports_to_id);
            $children = $this->getChildren($userBean);
        }

        //generate base tree
        $tree = $this->formatForTree($userBean, $children);

        //if the passed in user isn't who is logged in, we need to generate a top level parent link.  This is only
        //generated if the top level link isn't the same as the folder view that we loaded above if the person
        //is a manager.
        if ($loggedInUserId != $userId && $loggedInUserIsManager && $userBean->id != $loggedInUserId) {
            // we need to create a parent record
                $parent = $this->getParentLink($loggedInUserId);
                // the open user should be marked as a manager now
                $tree['attr']['rel'] = 'manager';

                // put the parent link and the tree in the same level
                $tree = array($parent, $tree);
        }

        return $tree;
    }

    /**
     * Load up all the reporting users for a given user
     * @param User $user
     * @return array
     */
    protected function getChildren(User $user)
    {
        $query = $user->create_new_list_query('',
            'users.reports_to_id = ' . $user->db->quoted($user->id) . ' AND users.status = \'Active\'');
        $response = $user->process_list_query($query, 0);
        return $response['list'];
    }

    /**
     * Format the main part of the tree
     * @param User $user
     * @param array $children
     * @return array
     */
    protected function formatForTree(User $user, array $children)
    {
        $tree = $this->getTreeArray($user, 'root');

        if (!empty($children)) {
            // we have children
            // add the manager again as the my opportunities bunch
            $tree['children'][] = $this->getTreeArray($user, 'my_opportunities');
            foreach ($children as $child) {
                $tree['children'][] = $this->getTreeArray($child, 'rep');
            }

            $tree['state'] = 'open';
        }

        return $tree;
    }

    /**
     * Utility method to get the Parent Link
     *
     * @param string $manager_reports_to
     * @return array
     */
    protected function getParentLink($manager_reports_to)
    {
        /* @var $parentBean User */
        $parentBean = BeanFactory::getBean('Users', $manager_reports_to);
        $parent = $this->getTreeArray($parentBean, 'parent_link');

        // overwrite the whole attr array for the parent
        $parent['attr'] = array(
            'rel' => 'parent_link',
            'class' => 'parent',
            // adding id tag for QA's voodoo tests
            'id' => 'jstree_node_parent'
        );

        return $parent;
    }

    /**
     * Utility method to build out a tree node array
     * @param User $user
     * @param string $rel
     * @return array
     */
    protected function getTreeArray(User $user, $rel = 'rep')
    {
        global $locale;
        $fullName = $locale->formatName($user);

        $qa_id = 'jstree_node_';
        if ($rel == "my_opportunities") {
            $qa_id .= 'myopps_';
        }

        $state = '';

        if ($rel == 'rep' && User::isManager($user->id)) {
            // check if the user is a manager and if they are change the rel to be 'manager'
            $rel = 'manager';
            $state = 'closed';
        }

        return array(
            'data' => $fullName,
            'children' => array(),
            'metadata' => array(
                'id' => $user->id,
                'user_name' => $user->user_name,
                'full_name' => $fullName,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'picture' => $user->picture,
                'reports_to_id' => $user->reports_to_id,
                'reports_to_name' => $user->reports_to_name,
                'title' => $user->title,
                'is_manager' => User::isManager($user->id),
                'is_top_level_manager' => User::isTopLevelManager($user->id),
            ),
            'state' => $state,
            'attr' => array(
                // set all users to rep by default
                'rel' => $rel,
                // adding id tag for QA's voodoo tests
                'id' => $qa_id . $user->user_name
            )
        );
    }

}
