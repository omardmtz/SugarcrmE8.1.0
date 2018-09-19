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


class UsefulnessLink extends Link2
{
    /**
     * Vote for an article.
     *
     * @param $vote
     * @return array|bool
     */
    public function vote($vote)
    {
        $params = array();
        $user = $GLOBALS['current_user'];
        $contact_id = null;

        if (!$this->isValidSugarUser($user) && $contact = $this->getPortalContact()) {
            $contact_id = $contact->id;
            $params['where'] = 'contact_id = ' . DBManagerFactory::getInstance()->quoted($contact_id);
        }
        /**
         * Load only required votes
         */
        $this->load($params);

        /**
         * Delete previous votes for a portal contact
         */
        if ($contact_id !== null) {
            if (!empty($this->rows)) {
                $q = $this->relationship->getQuery($this, array('return_as_array' => true));
                if (!empty($params['where'])) {
                    $q['where'] .= ' AND ' . $params['where'];
                }
                $q = 'UPDATE ' . $this->relationship->getRelationshipTable() . ' SET deleted = 1 ' . $q['where'];
                DBManagerFactory::getInstance()->query($q);
            }
            $this->relationship->primaryOnly = true;
        }
        $result = $this->add(
            $user,
            array(
                'vote' => $vote ? 1 : -1,
                'ssid' => session_id(),
                'contact_id' => $contact_id,
                'zeroflag' => 0
            )
        );
        $this->relationship->primaryOnly = false;
        return $result;
    }

    /**
     * Check if user is not an portal one.
     * @param SugarBean $user
     * @return bool
     */
    public function isValidSugarUser($user)
    {
        $portalUserId = BeanFactory::newBean('Users')->retrieve_user_id('SugarCustomerSupportPortalUser');
        return $user->id !== $portalUserId;
    }

    /**
     * Return contact associated with portal user.
     * @see CurrentUserPortalApi::getPortalContact
     * @return null|SugarBean
     */
    public function getPortalContact()
    {
        return BeanFactory::getBean('Contacts', $_SESSION['contact_id']);
    }
}
