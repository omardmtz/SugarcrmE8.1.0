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
 * This class encapsulates properties and behavior of an attachment so that a common interface can be expected
 * no matter what package is being used to deliver email.
 */
class AttachmentPeer
{
    /**
     * Constructs an attachment from the SugarBean that is passed in.
     *
     * @static
     * @access public
     * @param SugarBean $bean required
     * @return Attachment
     * @throws MailerException
     */
    public static function attachmentFromSugarBean(SugarBean $bean) {
        $filePath = null;
        $fileName = null;
        $mimeType = "";

        if ($bean instanceof Document) {
            if (empty($bean->id)) {
                throw new MailerException(
                    "Invalid Attachment: document not found",
                    MailerException::InvalidAttachment
                );
            }
            $document_revision_id = $bean->document_revision_id;
            $documentRevision = new DocumentRevision();
            if (!empty($document_revision_id)) {
                $documentRevision->retrieve($bean->document_revision_id);
            }
            if (empty($document_revision_id) || $documentRevision->id != $document_revision_id) {
                throw new MailerException(
                    "Invalid Attachment: Document with Id (" . $bean->id . ")  contains an invalid or empty revision id: (" . $document_revision_id . ")",
                    MailerException::InvalidAttachment
                );
            }
            $bean = $documentRevision;
        }

        $beanName = get_class($bean);
        switch ($beanName) {
            case "Note":
            case "DocumentRevision":
                $filePath = rtrim(SugarConfig::getInstance()->get('upload_dir', 'upload'), '/\\') . '/' . (method_exists($bean, 'getUploadId') ? $bean->getUploadId() : $bean->id);
                $fileName = empty($bean->filename) ? $bean->name : $bean->filename;
                $mimeType = empty($bean->file_mime_type) ? $mimeType : $bean->file_mime_type;
                break;
            default:
                throw new MailerException(
                    "Invalid Attachment: SugarBean '{$beanName}' not supported as an Email Attachment",
                    MailerException::InvalidAttachment
                );
                break;
        }

        // Path must Exist and Must be a Regular File
        if (!is_file($filePath)) {
            throw new MailerException(
                "Invalid Attachment: file not found: {$filePath}",
                MailerException::InvalidAttachment
            );
        }

        $attachment = new Attachment($filePath, $fileName, Encoding::Base64, $mimeType);

        return $attachment;
    }


    /**
     * Constructs an embedded image from the SugarBean that is passed in.
     *
     * @static
     * @access public
     * @param SugarBean $bean required
     * @param $cid required
     * @return EmbeddedImage
     * @throws MailerException
     */
    public static function embeddedImageFromSugarBean(SugarBean $bean, $cid) {
        $beanName = get_class($bean);
        $filePath = null;
        $fileName = null;
        $mimeType = "";

        switch ($beanName) {
            case "Note":
                $filePath = rtrim(SugarConfig::getInstance()->get('upload_dir', 'upload'), '/\\') . '/' . $bean->getUploadId();
                $fileName = empty($bean->filename) ? $bean->name : $bean->filename;
                $mimeType = empty($bean->file_mime_type) ? $mimeType : $bean->file_mime_type;
                break;
            default:
                throw new MailerException(
                    "Invalid Attachment: SugarBean '{$beanName}' not supported as an Email EmbeddedImage",
                    MailerException::InvalidAttachment
                );
                break;
        }

        // Path must Exist and Must be a Regular File
        if (!is_file($filePath)) {
            throw new MailerException(
                "Invalid Attachment: file not found: {$filePath}",
                MailerException::InvalidAttachment
            );
        }

        $embeddedImage = new EmbeddedImage($cid, $filePath, $fileName, Encoding::Base64, $mimeType);

        return $embeddedImage;
    }
}
