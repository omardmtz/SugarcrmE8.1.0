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
 * This class encapsulates properties and behavior of an embedded image, which is a type of attachment, so that a common
 * interface can be expected no matter what package is being used to deliver email.
 *
 * @extends Attachment
 */
class EmbeddedImage extends Attachment
{
    // private members
    private $cid;   // The Content-ID used to reference the image in the message.

    /**
     * @access public
     * @param string      $path     required
     * @param string      $cid      required
     * @param null|string $name              Should be a string, but null is acceptable if the path will be used for
     *                                       the name.
     * @param string      $encoding
     * @param string      $mimeType
     */
    public function __construct($cid, $path, $name = null, $encoding = Encoding::Base64, $mimeType = "") {
        $this->setCid($cid);
        parent::__construct($path, $name, $encoding, $mimeType);
    }

    /**
     * @access public
     * @param string $cid required
     */
    public function setCid($cid) {
        $this->cid = $cid;
    }

    /**
     * @return string
     */
    public function getCid() {
        return $this->cid;
    }

    /**
     * Returns an array representation of the embedded image by adding the Content-ID to the array resulting from
     * calling the parent method of the same name.
     *
     * @access public
     * @return array Array of key value pairs representing the properties of the attachment.
     */
    public function toArray() {
        $image = parent::toArray();
        $image["cid"] = $this->getCid();

        return $image;
    }
}
