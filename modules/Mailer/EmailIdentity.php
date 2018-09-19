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
 * This class encapsulates properties and behavior of email identities, which are the email address and a name, if one
 * is associated with the email address. An email identity can be considered to look like "Bob Smith" <bsmith@yahoo.com>
 * in practice.
 */
class EmailIdentity
{
    // private members
    private $email; // The email address used in this identity.
    private $name;  // The name that accompanies the email address.

    /**
     * @access public
     * @param string      $email required
     * @param string|null $name  Should be a string unless no name is associated with the email address.
     */
    public function __construct($email, $name = null) {
        $this->setEmail($email);
        $this->setName($name);
    }

    /**
     * @access public
     * @param string $email required
     * @throws MailerException
     * @todo still need to do more to validate the email address
     */
    public function setEmail($email) {
        // validate the email address
        if (!is_string($email) || trim($email) == "") {
            //@todo stringify $email and add it to the message
            throw new MailerException("Invalid email address", MailerException::InvalidEmailAddress);
        }

        $this->email = trim($email);
    }

    /**
     * @access public
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @access public
     * @param string|null $name required Should be a string unless no name is associated with the email address.
     */
    public function setName($name) {
        // if $name is null, then trim will return an empty string, which is perfectly acceptable
        $this->name = trim($name);
    }

    /**
     * Returns a string if a name exists, or an empty string or null if a name does not exist.
     *
     * @access public
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Convert special HTML entities back to characters in cases where the email address contains characters that are
     * considered invalid for email. Call this method on the object before transferring the object or its email property
     * to a package that is being used to deliver email.
     *
     * @access public
     * @bug 31778
     */
    public function decode() {
        // add back in html characters (apostrophe ' and ampersand &) to email addresses
        // this was causing email bounces in names like "O'Reilly@example.com" being sent over as "O&#039;Reilly@example.com"
        // transferred from the commit found at https://github.com/sugarcrm/Mango/commit/67b9144cd7bfa5425a98e28a1f7d9e994c7826bc
        $this->email = htmlspecialchars_decode($this->email, ENT_QUOTES);
    }
}
