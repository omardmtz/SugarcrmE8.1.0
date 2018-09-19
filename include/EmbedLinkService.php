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
class EmbedLinkService
{
    // regular expression from http://daringfireball.net/2010/07/improved_regex_for_matching_urls
    protected static $urlRegex = '#(?i)\b((?:https?://|www\d{0,3}\.|[a-z0-9.\-]+\.[a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))#i';

    protected static $imageRegex = '#(https?://[^\s]+(?=\.(jpe?g|png|gif)))(\.(jpe?g|png|gif))#i';

    protected static $cache = array(); //cache curl results to prevent retrieving web sites twice

    protected static $supportedOEmbedTypes = array('video','rich',);

    /**
     * Get all embed data of links in a given string
     *
     * @param string $text
     * @return array
     */
    public function get($text)
    {
        $embeds = array();
        $urls   = $this->findAllUrls($text);

        foreach ($urls as $url) {
            if ($this->isImage($url)) {
                $embed = array(
                    'type' => 'image',
                    'src'  => $url,
                );
                array_push($embeds, $embed);
            } else {
                $embed = $this->getOEmbedData($url);
                if (!empty($embed)) {
                    array_push($embeds, $embed);
                }
            }
        }

        return array('embeds' => $embeds);
    }

    /**
     * Find all URLs in a given string
     *
     * @param string $text
     * @return array
     */
    protected function findAllUrls($text)
    {
        preg_match_all(static::$urlRegex, $text, $matches);
        return $matches[0];
    }

    /**
     * Is it a link to an image?
     *
     * @param string $url
     * @return bool
     */
    protected function isImage($url)
    {
        return (preg_match(static::$imageRegex, $url) === 1);
    }

    /**
     * Get oEmbed data for a given URL
     *
     * @param string $url
     * @return array
     */
    protected function getOEmbedData($url)
    {
        libxml_use_internal_errors(true);

        $oEmbedUrls = $this->getOEmbedUrls($url);

        if (isset($oEmbedUrls['json'])) {
            $oEmbedFromJson = $this->getJsonOEmbedData($oEmbedUrls['json']);
            if (count($oEmbedFromJson) !== 0) {
                return $oEmbedFromJson;
            }
        }

        if (isset($oEmbedUrls['xml'])) {
            $oEmbedFromXml = $this->getXmlOEmbedData($oEmbedUrls['xml']);
            if (count($oEmbedFromXml) !== 0) {
                return $oEmbedFromXml;
            }
        }

        return array();
    }

    /**
     * Get the URLs where the oEmbed data can be found.
     *
     * @param string $url
     * @return array
     */
    protected function getOEmbedUrls($url)
    {
        $results = array();
        $domString = $this->fetch($url);

        if ($domString && (strlen($domString) > 0)) {
            $dom = new DOMDocument();
            $dom->loadHTML($domString);

            $linkTags = $dom->getElementsByTagName('link');

            foreach ($linkTags as $tag) {
                if ($tag instanceof DOMElement) {
                    $type = $tag->getAttribute('type');
                    $type = trim($type);
                    if ($type === 'application/json+oembed') {
                        $results['json'] = $tag->getAttribute('href');
                    } elseif ($type === 'text/xml+oembed') {
                        $results['xml'] = $tag->getAttribute('href');
                    }
                }
            }
        }

        return $results;
    }

    /**
     * Get the JSON oEmbed data of a given URL
     *
     * @param string $url
     * @return array
     */
    protected function getJsonOEmbedData($url)
    {
        $embedData = array();
        $jsonString = $this->fetch($url);

        if ($jsonString && (strlen($jsonString) > 0)) {
            $jsonOEmbed = json_decode($jsonString);

            if (in_array($jsonOEmbed->type, static::$supportedOEmbedTypes)) {
                $embedData['type'] = $jsonOEmbed->type;
                $embedData['html'] = $this->convertToProtocolRelativeUrl($this->cleanHtml($jsonOEmbed->html));
                $embedData['width'] = $jsonOEmbed->width;
                $embedData['height'] = $jsonOEmbed->height;
            }
        }

        return $embedData;
    }

    /**
     * Get the XML oEmbed data of a given URL
     *
     * @param string $url
     * @return array
     */
    protected function getXmlOEmbedData($url)
    {
        $embedData = array();
        $xmlOEmbed = new DOMDocument();
        $xmlString = $this->fetch($url);

        if ($xmlString && (strlen($xmlString) > 0)) {
            $xmlOEmbed->loadXML($xmlString);

            $typeElements = $xmlOEmbed->getElementsByTagName('type');
            if ((count($typeElements) > 0) && in_array($typeElements->item(0)->nodeValue, static::$supportedOEmbedTypes)) {
                $embedData['type'] = $typeElements->item(0)->nodeValue;

                $htmlElements = $xmlOEmbed->getElementsByTagName('html');
                if (count($htmlElements) > 0) {
                    $embedData['html'] = $this->convertToProtocolRelativeUrl($this->cleanHtml($htmlElements->item(0)->nodeValue));
                }

                $widthElements = $xmlOEmbed->getElementsByTagName('width');
                if (count($widthElements) > 0) {
                    $embedData['width'] = $widthElements->item(0)->nodeValue;
                }

                $heightElements = $xmlOEmbed->getElementsByTagName('height');
                if (count($heightElements) > 0) {
                    $embedData['height'] = $heightElements->item(0)->nodeValue;
                }
            }
        }

        return $embedData;
    }

    /**
     * Fetch the content of a given URL
     *
     * @param string $url
     * @return string
     */
    protected function fetch($url)
    {
        if (!isset(static::$cache[$url])) {
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 15);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

            static::$cache[$url] = curl_exec($curl);
            curl_close($curl);
        }

        return static::$cache[$url];
    }

    /**
     * Find http:// and https:// protocol and replace it with protocol agnostic url
     *
     * @param string $urlString
     * @return string
     */
    protected function convertToProtocolRelativeUrl($urlString)
    {
        $embedHtmlData = str_replace('http://', '//', $urlString);
        $embedHtmlData = str_replace('https://', '//', $embedHtmlData);
        return $embedHtmlData;
    }

    /**
     * Processes the html with HtmlPurifier
     *
     * @param string $html
     * @return string
     */
    protected function cleanHtml($html)
    {
        return SugarCleaner::cleanHtml($html);
    }
}
