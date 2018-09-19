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

require_once 'include/workflow/alert_utils.php';

use Sugarcrm\Sugarcrm\ProcessManager;

class PMSEEmailHandler
{
    /**
     * The Bean Handler object
     * @var PMSEBeanHandler
     */
    private $beanUtils;

    /**
     * The Administration bean
     * @var Administration
     */
    private $admin;

    /**
     * The Localization Bean
     * @deprecated Will be removed in a future release
     * @var PMSELogger
     */
    private $locale;

    /**
     * The Logger object
     * @var PMSELogger
     */
    private $logger;

    /**
     * The Related Module object
     * @var PMSERelatedModule
     */
    private $pmseRelatedModule;

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $msg = 'The %s method will be removed in a future release and should no longer be used';
        LoggerManager::getLogger()->deprecated(sprintf($msg, __METHOD__));
    }

    /**
     * Get the PMSE Related Module object
     * @return PMSERelatedModule
     */
    protected function getRelatedModuleObject()
    {
        if (empty($this->pmseRelatedModule)) {
            $this->pmseRelatedModule = ProcessManager\Factory::getPMSEObject('PMSERelatedModule');
        }

        return $this->pmseRelatedModule;
    }

    /**
     * Gets the proper bean for processing
     * @param SugarBean $bean The target bean
     * @param string $module The related module name
     * @return SugarBean
     */
    protected function getProperBean(SugarBean $bean, $module)
    {
        global $beanList;

        // Module in this case could be a relationship name, link name or
        // some other value
        if (!isset($beanList[$module])) {
            return $this->getRelatedModuleObject()->getRelatedModule($bean, $module);
        }

        // If the module is an actual module, send the original bean back
        return $bean;
    }

    /**
     * Gets the Bean Handler object
     * @return PMSEBeanHandler
     * @codeCoverageIgnore
     */
    public function getBeanUtils()
    {
        if (empty($this->beanUtils)) {
            $this->beanUtils = ProcessManager\Factory::getPMSEObject('PMSEBeanHandler');
        }

        return $this->beanUtils;
    }

    /**
     * Gets the localization object
     * @deprecated Will be removed in a future release
     * @return type
     * @codeCoverageIgnore
     */
    public function getLocale()
    {
        $msg = 'The %s method will be removed in a future release and should no longer be used';
        LoggerManager::getLogger()->deprecated(sprintf($msg, __METHOD__));

        global $locale;
        return $locale;
    }

    /**
     * Gets the PMSE Logger object
     * @return PMSELogger
     * @codeCoverageIgnore
     */
    public function getLogger()
    {
        if (empty($this->logger)) {
            $this->logger = PMSELogger::getInstance();
        }

        return $this->logger;
    }

    /**
     * Gets the administration object
     * @return Administration
     * @codeCoverageIgnore
     */
    public function getAdmin()
    {
        if (empty($this->admin)) {
            $this->admin = new Administration();
        }

        return $this->admin;
    }

    /**
     * Sets the administration object
     * @param Administration $admin
     */
    public function setAdmin(Administration $admin)
    {
        $this->admin = $admin;
    }

    /**
     * Sets the bean handler object
     * @param PMSEBeanHandler $beanUtils
     * @codeCoverageIgnore
     */
    public function setBeanUtils(PMSEBeanHandler $beanUtils)
    {
        $this->beanUtils = $beanUtils;
    }

    /**
     * Sets the localization object
     * @deprecated Will be removed in a future release
     * @param type $locale
     * @codeCoverageIgnore
     */
    public function setLocale($locale)
    {
        $msg = 'The %s method will be removed in a future release and should no longer be used';
        LoggerManager::getLogger()->deprecated(sprintf($msg, __METHOD__));

        $this->locale = $locale;
    }

    /**
     * Sets the logger oject
     * @param PMSELogger $logger
     * @codeCoverageIgnore
     */
    public function setLogger(PMSELogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param type $module
     * @param type $beanId
     * @return type
     * @codeCoverageIgnore
     */
    public function retrieveBean($module, $beanId = null)
    {
        return BeanFactory::getBean($module, $beanId);
    }

    /**
     * Get the email data stored in a json string and also processes and parses the variable data.
     * @param type $bean
     * @param type $json
     * @param type $flowData
     * @return \StdClass
     */
    public function processEmailsFromJson($bean, $json, $flowData)
    {
        $addresses = json_decode($json);
        $result = new stdClass();
        if (isset($addresses->to) && is_array($addresses->to)) {
            $result->to = $this->processEmailsAndExpand($bean, $addresses->to, $flowData);
        }
        if (isset($addresses->cc) && is_array($addresses->cc)) {
            $result->cc = $this->processEmailsAndExpand($bean, $addresses->cc, $flowData);
        }
        if (isset($addresses->bcc) && is_array($addresses->bcc)) {
            $result->bcc = $this->processEmailsAndExpand($bean, $addresses->bcc, $flowData);
        }

        return $result;
    }

    /**
     * Process the email and also obtains the bean data that needs to be inserted in the email object,
     * replacing the variables instances with the actual value.
     * @param type $bean
     * @param type $to
     * @param type $flowData
     * @return \StdClass
     * @codeCoverageIgnore
     */
    public function processEmailsAndExpand($bean, $to, $flowData)
    {
        $res = array();

        $moduleName = $flowData['cas_sugar_module'];
        $object_id = $flowData['cas_sugar_object_id'];

        foreach ($to as $entry) {
            switch (strtoupper($entry->type)) {
                case 'USER':
                    $res = array_merge(
                        $res, $this->processUserEmails($bean, $entry, $flowData)
                    );
                    break;
                case 'TEAM':
                    $res = array_merge(
                        $res, $this->processTeamEmails($bean, $entry, $flowData)
                    );
                    break;
                case 'ROLE':
                    $res = array_merge(
                        $res, $this->processRoleEmails($bean, $entry, $flowData)
                    );
                    break;
                case 'RECIPIENT':
                    $res = array_merge(
                        $res, $this->processRecipientEmails($bean, $entry, $flowData)
                    );
                    break;
                case 'EMAIL':
                    $res = array_merge(
                        $res, $this->processDirectEmails($bean, $entry, $flowData)
                    );
                    break;
            }
        }

        return $res;
    }

    public function processUserEmails($bean, $entry, $flowData)
    {
        $res = $users = array();

        // Get the correct bean for this request
        $bean = $this->getProperBean($bean, $entry->module);
        switch ($entry->value) {
            case 'last_modifier':
                $users[] = $this->getLastModifier($bean);
                break;
            case 'record_creator':
                $users[] = $this->getRecordCreator($bean);
                break;
            case 'is_assignee':
                $users[] = $this->getCurrentAssignee($bean);
                break;
        }
        foreach ($users as $user) {
            $res = array_merge($res, $this->getUserEmails($user, $entry));
        }
        return $res;
    }

    public function getCurrentAssignee($bean)
    {
        $userBean = $this->retrieveBean("Users", $bean->assigned_user_id);
        return $userBean;
    }

    public function getRecordCreator($bean)
    {
        $userBean = $this->retrieveBean("Users", $bean->created_by);
        return $userBean;
    }

    public function getLastModifier($bean)
    {
        $userBean = $this->retrieveBean("Users", $bean->modified_user_id);
        return $userBean;
    }

    /**
     * Checks if a User bean is for an active user
     * @param $userBean
     * @return bool
     */
    public function isUserActiveForEmail(User $userBean)
    {
        // Emails should only be sent when Employee Status is Active AND User Status is Active
        return PMSEEngineUtils::isUserActive($userBean) && !empty($userBean->full_name) && !empty($userBean->email1);
    }

    public function getUserEmails($userBean, $entry)
    {
        $res = array();
        $user = $userBean;
        if ($entry->user === 'manager_of') {
            $user = $this->getSupervisor($userBean);
        }

        if ($this->isUserActiveForEmail($user)) {
            $item = new stdClass();
            $item->name = $user->full_name;
            $item->address = $user->email1;
            $res[] = $item;
        }
        return $res;
    }

    public function getSupervisor($user)
    {
        if (isset($user->reports_to_id) && $user->reports_to_id != '') {
            $supervisor = $this->retrieveBean("Users", $user->reports_to_id);
            if (
                isset($supervisor->full_name) &&
                !empty($supervisor->full_name) &&
                isset($supervisor->email1) &&
                !empty($supervisor->email1)
            ) {
                return $supervisor;
            } else {
                return '';
            }
        }
    }

    public function processTeamEmails($bean, $entry, $flowData)
    {
        $res = array();
        $team = $this->retrieveBean('Teams',$entry->value); //$beanFactory->getBean('Teams');
        //$response = $team->getById();
        $members = $team->get_team_members();
        foreach ($members as $user) {
            $userBean = $this->retrieveBean("Users", $user->id);
            if ($this->isUserActiveForEmail($userBean)) {
                $item = new stdClass();
                $item->name = $userBean->full_name;
                $item->address = $userBean->email1;
                $res[] = $item;
            }
        }
        return $res;
    }

    public function processRoleEmails($bean, $entry, $flowData)
    {
        $res = array();
        $role = $this->retrieveBean('ACLRoles', $entry->value);
        $userList = $role->get_linked_beans('users','User');
        foreach ($userList as $user) {
            if ($this->isUserActiveForEmail($user)) {
                $item = new stdClass();
                $item->name = $user->full_name;
                $item->address = $user->email1;
                $res[] = $item;
            }
        }
        return $res;
    }

    public function processRecipientEmails($bean, $entry, $flowData)
    {
        $res = array();
        $field = $entry->value;

        // Get the correct bean for this request
        $bean = $this->getProperBean($bean, $entry->module);
        if (!empty($bean) && is_object($bean)) {
            $value = $bean->$field;
        } else {
            $value = '';
        }

        $item = new stdClass();
        $item->name = $value;
        $item->address = $value;
        $res[] = $item;
        return $res;
    }

    public function processDirectEmails($bean, $entry, $flowData)
    {
        $res = array();
        $item = new stdClass();
        if (isset($entry->id)) {
            $userBean = $this->retrieveBean('Users', $entry->id);
            if (!empty($userBean)) {
                $item->name = $userBean->full_name;
                $item->address = $userBean->email1;
                $res[] = $item;
            }
        } else {
            // for typed-in emails
            $item->name = $entry->value;
            $item->address = $entry->value;
            $res[] = $item;
        }

        return $res;
    }

    /**
     * Returns a Mailer object
     * @return mixed
     */
    protected function retrieveMailer()
    {
        return MailerFactory::getSystemDefaultMailer();
    }
    /**
     * Send the email based in an email template and with the email data parsed.
     * @param type $moduleName
     * @param type $beanId
     * @param type $addresses
     * @param type $templateId
     * @return type
     */
    public function sendTemplateEmail($moduleName, $beanId, $addresses, $templateId)
    {
        $mailTransmissionProtocol = "unknown";
        if (PMSEEngineUtils::isEmailRecipientEmpty($addresses)) {
            $this->getLogger()->alert('All email recipients are filtered out of the email recipient list.');
            return;
        }
        try {
            $bean = $this->retrieveBean($moduleName, $beanId);
            $templateObject = $this->retrieveBean('pmse_Emails_Templates');
            $templateObject->disable_row_level_security = true;

            $mailObject = $this->retrieveMailer();
            $mailTransmissionProtocol   = $mailObject->getMailTransmissionProtocol();

            $this->addRecipients($mailObject, $addresses);

            if (isset($templateId) && $templateId != "") {
                $templateObject->retrieve($templateId);
            } else {
                $this->getLogger()->warning('template_id is not defined');
            }

            if (!empty($templateObject->from_name) && !empty($templateObject->from_address)) {
                $mailObject->setHeader(EmailHeaders::From, new EmailIdentity($templateObject->from_address, $templateObject->from_name));
            }

            if (isset($templateObject->body) && empty($templateObject->body)) {
                $templateObject->body = strip_tags(from_html($templateObject->body_html));
            } else {
                $this->getLogger()->warning('template body is not defined');
            }

            if (!empty($templateObject->body) && !empty($templateObject->body_html)) {
                $textOnly = EmailFormatter::isTextOnly($templateObject->body_html);
                if (!$textOnly) {
                    if (!empty($templateObject->body_html)) {
                        //set HTMLBody
                        $htmlBody = from_html($this->getBeanUtils()->mergeBeanInTemplate($bean, $templateObject->body_html));
                        $mailObject->setHtmlBody($htmlBody);
                    }
                }
                // set TextBody too
                $textBody = strip_tags(br2nl($templateObject->body));
                $mailObject->setTextBody($textBody);
            } else {
                $this->getLogger()->warning('template body_html is not defined');
            }

            if (isset($templateObject->subject)) {
                $mailObject->setSubject(from_html($this->getBeanUtils()->mergeBeanInTemplate($bean, $templateObject->subject)));
            } else {
                $this->getLogger()->warning('template subject is not defined');
            }

            $mailObject->send();
        } catch (MailerException $mailerException) {
            $message = $mailerException->getMessage();
            $this->getLogger()->warning("Error sending email (method: {$mailTransmissionProtocol}), (error: {$message})");
        }
    }

    /**
     * Add receipients to Mailer object in preparation to sending email
     * @param $mailObject Mailer object
     * @param $addresses To, CC & BCC Email addresses
     */
    protected function addRecipients($mailObject, $addresses)
    {
        foreach (['to', 'cc', 'bcc'] as $type) {
            if (isset($addresses->{$type})) {
                $method = 'addRecipients' . ucfirst($type);
                foreach ($addresses->{$type} as $key => $email) {
                    $mailObject->{$method}(new EmailIdentity($email->address, $email->name));
                }
            }
        }
    }

    /**
     * Checks if the primary email address exists
     * @param type $field
     * @param type $bean
     * @param type $historyData
     * @return boolean
     */
    public function doesPrimaryEmailExists($field, $bean, $historyData)
    {
        if ($field->field == 'email_addresses_primary') {
            $preEmail = $bean->emailAddress->getPrimaryAddress('', $bean->id, $bean->module_dir);
            if (empty($preEmail)) {
                //is a new record, it hasn't any email in DB yet
                $emailKey = $this->getPrimaryEmailKeyFromREQUEST($bean);
                $historyData->savePredata($field->field, $_REQUEST[$emailKey]);
                $_REQUEST[$emailKey] = $field->value;
            } else {
                //the record exist in db
                $historyData->savePredata($field->field, $preEmail);
                $this->updateEmails($bean, $field->value);
            }
            return true;
        }
        return false;
    }

    /**
     * Get the primary Key from a request in order to obtain the email id
     * @param type $bean
     * @return type
     */
    public function getPrimaryEmailKeyFromREQUEST($bean)
    {
        $module = $bean->module_dir;
        $widgetCount = 0;
        $moduleItem = '0';

        $widget_id = '';
        foreach ($_REQUEST as $key => $value) {
            if (strpos($key, 'emailAddress') !== false) {
                break;
            }
            $widget_id = $_REQUEST[$module . '_email_widget_id'];
        }

        while (isset($_REQUEST[$module . $widget_id . "emailAddress" . $widgetCount])) {
            if (empty($_REQUEST[$module . $widget_id . "emailAddress" . $widgetCount])) {
                $widgetCount++;
                continue;
            }

            $primaryValue = false;

            $eId = $module . $widget_id;
            if (isset($_REQUEST[$eId . 'emailAddressPrimaryFlag'])) {
                $primaryValue = $_REQUEST[$eId . 'emailAddressPrimaryFlag'];
            } elseif (isset($_REQUEST[$module . 'emailAddressPrimaryFlag'])) {
                $primaryValue = $_REQUEST[$module . 'emailAddressPrimaryFlag'];
            }

            if ($primaryValue) {
                return $eId . 'emailAddress' . $widgetCount;
            }
            $widgetCount++;
        }
        $_REQUEST[$bean->module_dir . '_email_widget_id'] = 0;
        $_REQUEST['emailAddressWidget'] = 1;
        $_REQUEST['useEmailWidget'] = true;
        $emailId = $bean->module_dir . $moduleItem . 'emailAddress';
        $_REQUEST[$emailId . 'PrimaryFlag'] = $emailId . $moduleItem;
        $_REQUEST[$emailId . 'VerifiedFlag' . $moduleItem] = true;
        //$_REQUEST[$emailId . 'VerifiedValue' . $moduleItem] = $myemail;

        return $emailId . $moduleItem;
    }

    /**
     * Update the email data in the REQUEST global object
     * @param type $bean
     * @param type $newEmailAddress
     */
    public function updateEmails($bean, $newEmailAddress)
    {
        //Note.- in the future will be an 'array' of change fields emails
        $moduleItem = '0';
        $addresses = $bean->emailAddress->getAddressesByGUID($bean->id, $bean->module_dir);
        if (sizeof($addresses) > 0) {
            $_REQUEST[$bean->module_dir . '_email_widget_id'] = 0;
            $_REQUEST['emailAddressWidget'] = 1;
            $_REQUEST['useEmailWidget'] = true;
        }
        foreach ($addresses as $item => $data) {
            if (!isset($data['email_address_id']) || !isset($data['primary_address'])) {
                $this->getLogger()->error(' The Email address Id or the primary address flag does not exist in DB');
                continue;
            }
            $emailAddressId = $data['email_address_id'];
            $emailId = $bean->module_dir . $moduleItem . 'emailAddress';
            if (!empty($emailAddressId) && $data['primary_address'] == 1) {
                $_REQUEST[$emailId . 'PrimaryFlag'] = $emailId . $item;
                $_REQUEST[$emailId . $item] = $newEmailAddress;
            } else {
                $_REQUEST[$emailId . $item] = $data['email_address'];
            }
            $_REQUEST[$emailId . 'Id' . $item] = $emailAddressId;
            $_REQUEST[$emailId . 'VerifiedFlag' . $item] = true;
            $_REQUEST[$emailId . 'VerifiedValue' . $item] = $data['email_address'];
        }
    }

}
