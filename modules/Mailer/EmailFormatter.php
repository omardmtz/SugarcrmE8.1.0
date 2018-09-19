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
 * This class implements the additional SugarCRM-specific email formatting that SmtpMailer lacks.
 */
class EmailFormatter
{
    // private members
    private $includeDisclosure = false; // true=append the disclosure to the message
    private $disclosureContent;         // the content to disclose

    /**
     * @access public
     */
    public function __construct() {
        $this->retrieveDisclosureSettings();
    }

    /**
     * Static helper - Does Mail Message Content Contain HTML?
     *
     * @access public
     * @param string $content
     * @return boolean
     */
    public static function isHtml($content) {
        return (strcmp($content, preg_replace('/(?:<|&lt;)\/?([a-zA-Z]+) *[^\/(?:<|&lt;)]*?\/?(?:>|&gt;)/', '', $content)) != 0);
    }

    /**
     * Static helper - Is Mail Message Content Text Only?
     *
     * @access public
     * @param string $content
     * @return boolean
     */
    public static function isTextOnly($content) {
        return !self::isHtml($content);
    }

    /**
     * Performs character set and HTML character translations on the string.
     *
     * @access public
     * @param string       $string      required The string that is to be translated.
     * @param Localization $locale      required The locale object for doing the character set translation.
     * @param string       $toCharset   required Translate to this character set.
     * @param string       $fromCharset          Translate from this character set.
     * @return string The translated string.
     */
    public function translateCharacters($string, Localization $locale, $toCharset, $fromCharset = "UTF-8") {
        // perform character set translations on the string
        $string = $locale->translateCharset($string, $fromCharset, $toCharset);

        // perform HTML character translations on the string
        $string = from_html($string);

        return $string;
    }

    /**
     * Adds the optional disclosure content to the message.
     *
     * @access public
     * @param string $body required
     * @return string
     */
    public function formatTextBody($body) {
        if ($this->includeDisclosure) {
            $body .= "\r\r{$this->disclosureContent}"; //@todo why are we using /r?
        }

        return $body;
    }

    /**
     * Adds the optional disclosure content to the message. Additionally, converts to embedded images any inline images
     * that are found locally on the server that hosts the application instance. This extra step is done to guarantee
     * that locally referenced images can be seen by the recipient, whether the server is public or private.
     *
     * @access public
     * @param string $body required
     * @return array body=String with the applicable modifications. images=Array of EmbeddedImage objects.
     */
    public function formatHtmlBody($body) {
        global $sugar_config;
        $siteUrl = $sugar_config["site_url"];

        if ($this->includeDisclosure) {
            $body .= "<br /><br />{$this->disclosureContent}";
        }

        // replace references to cache/images with cid tag
        $pattern     = ";=\s*\"" . preg_quote(sugar_cached("images/"), ";") . ";";
        $replacement = "=\"cid:";
        $body        = preg_replace($pattern, $replacement, $body);

        // replace any embeded images using cache/images for src url
        $converted = $this->convertInlineImageToEmbeddedImage(
            $body,
            "(?:{$siteUrl})?/?cache/images/",
            sugar_cached("images/")
        );
        $body   = $converted["body"];
        $images = $converted["images"];

        // replace any embeded images using the secure entryPoint for src url
        $converted = $this->convertInlineImageToEmbeddedImage(
            $body,
            "(?:{$siteUrl})?/?index.php[?]entryPoint=download&(?:amp;)?[^\"]*?id=",
            "upload://",
            true
        );
        $body   = $converted["body"];
        $images = array_merge($images, $converted["images"]);

        return array(
            "body"   => $body,
            "images" => $images,
        );
    }

    /**
     * Replace images with locations specified by regex with cid: images and attach needed files.
     *
     * @access protected
     * @param string $body
     * @param string $regex       Regular expression
     * @param string $localPrefix Prefix where local files are stored
     * @param bool   $object      Use attachment object
     * @return array body=String with the applicable modifications. images=Array of EmbeddedImage objects.
     */
    protected function convertInlineImageToEmbeddedImage($body, $regex, $localPrefix, $object = false) {
        $embeddedImages = array();
        $i              = 0;
        $foundImages    = array();
        $src = "[\"']({$regex})([^&\"']+).*?[\"']";

        preg_match_all("#<img[^>]*[\s]+src[^=]*=[\s]*{$src}#si", $body, $foundImages);

        foreach ($foundImages[2] as $image) {
            $filename     = urldecode($image);
            $cid          = $filename;
            $fileLocation = $localPrefix . $filename;

            if (file_exists($fileLocation)) {
                $mimeType = "";

                if ($object) {
                    $mimeType  = "application/octet-stream";
                    $objectType = array();

                    if (preg_match("#&(?:amp;)?type=([\w]+)#i", $foundImages[0][$i], $objectType)) {
                        $beanName = null;

                        switch (strtolower($objectType[1])) {
                            case "documents":
                                $beanName = "DocumentRevisions";
                                break;
                            case "notes":
                                $beanName = "Notes";
                                break;
                        }
                    }

                    if (!is_null($beanName)) {
                        $bean = SugarModule::get($beanName)->loadBean();
                        $bean->retrieve($filename);

                        if (!empty($bean->id)) {
                            $mimeType  = $bean->file_mime_type;
                            $filename  = $bean->filename;
                        }
                    }
                } else {
                    $mimeType = "image/" . strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                }

                $embeddedImages[] = new EmbeddedImage($cid, $fileLocation, $filename, Encoding::Base64, $mimeType);
                $i++;
            }
        }

        // replace references to cache with cid tag
        $body = preg_replace("|{$src}|i", '"cid:$2"', $body);

        // remove bad img line from outbound email
        $body = preg_replace('#<img[^>]+src[^=]*=\"\/([^>]*?[^>]*)>#sim', "", $body);

        return array(
            "body"   => $body,
            "images" => $embeddedImages,
        );
    }

    /**
     * Retrieves settings from the administrator configuration indicating whether or not to include a disclosure
     * at the bottom of an email, and if so, the content to disclose.
     *
     * @access protected
     * @todo consider how this could become a merge field that is added prior to the Mailer getting created
     */
    protected function retrieveDisclosureSettings() {
        $admin = new Administration();
        $admin->retrieveSettings();

        if (isset($admin->settings["disclosure_enable"]) && !empty($admin->settings["disclosure_enable"])) {
            $this->includeDisclosure = true;
            $this->disclosureContent = $admin->settings["disclosure_text"];
        }
    }
}
