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
 * This class encapsulates behavior related to translating recipient lists to the package that is being used to deliver
 * email. Specifically it acts as a container for all recipients and performs validation on and duplicate checking for
 * the recipients being added.
 */
class RecipientsCollection
{
    // constants used for documenting which Add methods are valid
    const FunctionAddTo  = "addTo";
    const FunctionAddCc  = "addCc";
    const FunctionAddBcc = "addBcc";

    // private members
    private $to;
    private $cc;
    private $bcc;

    /**
     * @access public
     */
    public function __construct() {
        $this->clearAll(); // set default values
    }

    /**
     * Makes absolutely sure that the recipient lists are no longer in memory at time of destruction. Although, this
     * may be overkill.
     *
     * @access public
     */
    public function __destruct() {
        $this->clearAll();
    }

    /**
     * Clears each of the recipient lists.
     *
     * @access public
     */
    public function clearAll() {
        $this->clearTo();
        $this->clearCc();
        $this->clearBcc();
    }

    /**
     * Clears the To recipient list.
     *
     * @access public
     */
    public function clearTo() {
        $this->to = array();
    }

    /**
     * Clears the CC recipient list.
     *
     * @access public
     */
    public function clearCc() {
        $this->cc = array();
    }

    /**
     * Clears the BCC recipient list.
     *
     * @access public
     */
    public function clearBcc() {
        $this->bcc = array();
    }

    /**
     * Add recipients to the specified list in the $function parameter.
     *
     * @access public
     * @param array  $recipients Array of EmailIdentity objects.
     * @param string $function   The name of the RecipientsCollection method to use for adding recipients.
     * @throws MailerException
     * @todo probably should report success/overwrite for each recipient added
     */
    public function addRecipients($recipients = array(), $function = RecipientsCollection::FunctionAddTo) {
        // validate the function to use for adding the recipients
        if (!method_exists($this, $function)) {
            throw new MailerException("Cannot add recipients using {$function}", MailerException::ResourceNotFound);
        }

        $recipients = $this->castRecipientsAsArray($recipients);

        foreach ($recipients as $recipient) {
            // add the recipient to the specified list
            $this->$function($recipient);
        }
    }

    /**
     * Add the recipient to the To recipient list. Use the recipient's email address as the key to avoid adding
     * duplicate recipients. This will overwrite a recipient if a duplicate does exist. However, duplicates can still
     * exist within the different recipient classifications (to/cc/bcc).
     *
     * @access public
     * @param EmailIdentity $recipient required
     * @todo consider reporting success/overwrite; there's really no failure
     */
    public function addTo(EmailIdentity $recipient) {
        $this->to[$recipient->getEmail()] = $recipient;
    }

    /**
     * Add the recipient to the CC recipient list. Use the recipient's email address as the key to avoid adding
     * duplicate recipients. This will overwrite a recipient if a duplicate does exist. However, duplicates can still
     * exist within the different recipient classifications (to/cc/bcc).
     *
     * @access public
     * @param EmailIdentity $recipient required
     * @todo consider reporting success/overwrite; there's really no failure
     */
    public function addCc(EmailIdentity $recipient) {
        $this->cc[$recipient->getEmail()] = $recipient;
    }

    /**
     * Add the recipient to the BCC recipient list. Use the recipient's email address as the key to avoid adding
     * duplicate recipients. This will overwrite a recipient if a duplicate does exist. However, duplicates can still
     * exist within the different recipient classifications (to/cc/bcc).
     *
     * @access public
     * @param EmailIdentity $recipient required
     * @todo consider reporting success/overwrite; there's really no failure
     */
    public function addBcc(EmailIdentity $recipient) {
        $this->bcc[$recipient->getEmail()] = $recipient;
    }

    /**
     * Returns all of the recipients classified by the list to which they belong.
     *
     * @access public
     * @return array An array of EmailIdentity objects grouped by list.
     */
    public function getAll() {
        return array(
            'to'  => $this->getTo(),
            'cc'  => $this->getCc(),
            'bcc' => $this->getBcc(),
        );
    }

    /**
     * Returns all of the recipients from the To recipient list.
     *
     * @access public
     * @return array An array of EmailIdentity objects.
     */
    public function getTo() {
        return $this->to;
    }

    /**
     * Returns all of the recipients from the CC recipient list.
     *
     * @access public
     * @return array An array of EmailIdentity objects.
     */
    public function getCc() {
        return $this->cc;
    }

    /**
     * Returns all of the recipients from the BCC recipient list.
     *
     * @access public
     * @return array An array of EmailIdentity objects.
     */
    public function getBcc() {
        return $this->bcc;
    }

    /**
     * Forces the recipients list, represented by $recipients, to be represented as an array so that the list can be
     * looped over the same whether there is one recipient or one thousand recipients.
     *
     * @access private
     * @param EmailIdentity|array $recipients Should be a single EmailIdentity or an array of EmailIdentity objects.
     * @return array An array of EmailIdentity objects.
     */
    private function castRecipientsAsArray($recipients) {
        if (!is_array($recipients)) {
            $recipients = array($recipients);
        }

        return $recipients;
    }
}
