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
 * API class for fetching the contents of an RSS feed and returning relevant, 
 * expected information from it. This class allows the application to proxy RSS
 * Feed requests on the server to prevent cross site or domain origin issues on 
 * the client.
 * 
 * Much of this code is borrowed from the getRSSFeed() method in 
 * modules/Home/Dashlets/RSSDashlet/RSSDashlet.php
 */
class RSSFeedApi extends SugarApi
{
    /**
     * Feed metadata should contain these at least
     * 
     * @var array
     */
    protected $feedMeta = array('title', 'link', 'description', 'pubDate');

    /**
     * An entry should contain at least these elements
     * 
     * @var array
     */
    protected $feedEntry = array(
        'title', 'description', 'link', 'pubDate', 'source', 'author',
    );

    public function registerApiRest()
    {
        return array(
            'getFeed' => array(
                'reqType' => 'GET',
                'path' => array('rssfeed'),
                'pathVars' => array(),
                'method' => 'getFeed',
                'shortHelp' => 'Consumes an RSS Feed and returns the content of the feed to the client',
                'longHelp' => 'include/api/help/rssfeed_help.html',
                'exceptions' => array(
                    // Thrown in validateFeedUrl
                    'SugarApiExceptionInvalidParameter',
                    // Thrown in getFeedContent and getFeedXMLContent
                    'SugarApiExceptionConnectorResponse',
                ),
            ),
        );
    }

    /**
     * Gets an RSS feed
     * 
     * @param ServiceBase $api The service object
     * @param array $args The request arguments
     * @return array Feed data
     */
    public function getFeed(ServiceBase $api, array $args)
    {
        // Simple sanity checking
        $this->requireArgs($args, array('feed_url'));
        $url = $args['feed_url'];

        // Simple feed URL validation
        $this->validateFeedUrl($url);

        // Get the limit of feed entries
        $limit = $this->getFeedLimit($args);

        // Grab the data now if possible
        $data = $this->getFeedContent($url);

        // Gets a SimpleXMLElement object
        $rss = $this->getFeedXMLObject($data);

        // Get the parsed response as a simple array
        $result = $this->getParsedXMLData($rss, $limit);

        return array('feed' => $result);
    }

    /**
     * Simple URL validation for an RSS feed
     * 
     * @param string $url The RSS Feed URL
     */
    public function validateFeedUrl($url)
    {
        $parts = parse_url($url);
        if (empty($parts['scheme']) || empty($parts['host'])) {
            throw new SugarApiExceptionInvalidParameter('LBL_ERR_LOADING_RSS_FEED');
        }

        if ($parts['scheme'] != 'http' && $parts['scheme'] != 'https') {
            throw new SugarApiExceptionInvalidParameter('LBL_ERR_LOADING_RSS_FEED');
        }
    }

    /**
     * Gets the feed entries limit, either from the request or from the config
     * 
     * @param array $args Request arguments
     * @return int
     */
    public function getFeedLimit(array $args)
    {
        global $sugar_config;

        // Set a default limit in case it wasn't requested. 5 is used on the
        // client so keep them in sync. Also prevent large data collection here
        // by limiting the limit to 20, which is also a client side attribute.
        $max = 20;
        if (isset($sugar_config['rss_feed_max_entries'])) {
            $max = intval($sugar_config['rss_feed_max_entries']);
        }

        $limit = isset($args['limit']) ? intval($args['limit']) : 5;
        if ($limit > $max) {
            $limit = $max;
        }

        return $limit;
    }

    /**
     * Gets the content of a feed URL if possible.
     * 
     * @param string $url The URL of the RSS Feed
     * @return string The XML of an RSS Feed URL response
     */
    public function getFeedContent($url)
    {
        // If a valid URL is used, but is not consumable, handle it. Using error
        // suppression here to prevent warnings from making it to output.
        $data = @file_get_contents($url);
        if (!$data) {
            throw new SugarApiExceptionConnectorResponse('LBL_ERR_LOADING_RSS_FEED');
        }

        return $data;
    }

    /**
     * Gets a SimpleXMLElement object created from a valid XML string
     * 
     * @param string $data An XML file content
     * @return SimpleXMLElement
     */
    public function getFeedXMLObject($data)
    {
        // Suppress XML errors
        libxml_use_internal_errors(true);
        libxml_disable_entity_loader(true);

        // Try to load the objectified data if possible
        $rss = simplexml_load_string($data);
        if (!$rss) {
            throw new SugarApiExceptionConnectorResponse('LBL_ERR_LOADING_RSS_FEED');
        }

        return $rss;
    }

    /**
     * Gets the relevant data for an API response from a SimpleXMLElement object
     * 
     * @param SimpleXMLElement $rss A SimpleXMLElement object
     * @param int $limit A limit to the number of entries returned
     * @return array
     */
    public function getParsedXMLData(SimpleXMLElement $rss, $limit)
    {
        // The counter to make sure we are under our limit
        $counter = 0;

        $result = array();
        if (isset($rss->channel)) {
            foreach ($rss->channel as $channel) {
                // Set meta properties from the feed
                foreach ($this->feedMeta as $prop) {
                    if ($channel->$prop) {
                        // Wrapping in double quotes here to force casting to string
                        $value = "{$channel->$prop}";

                        // Fix pubDate
                        if ($prop === 'pubDate') {
                            $prop = 'publication_date';
                        }
                        $result[$prop] = $value;
                    }
                }

                // Now handle entries
                if (isset($channel->item)) {
                    foreach ($channel->item as $item) {
                        if ($counter === $limit) {
                            break 2;
                        }

                        foreach ($this->feedEntry as $prop) {
                            $value = "{$item->$prop}";
                            // Fix pubDate
                            if ($prop === 'pubDate') {
                                $prop = 'publication_date';
                            }
                            $result['entries'][$counter][$prop] = $value;
                        }

                        $counter++;
                    }
                }
            }
        } else {
            // Set the basic meta properties to empties for this feed
            foreach ($this->feedMeta as $prop) {
                // Fix pubDate
                if ($prop === 'pubDate') {
                    $prop = 'publication_date';
                }

                $result[$prop] = '';
            }

            foreach ($rss->entry as $entry) {
                if ($counter === $limit) {
                    break;
                }

                foreach ($this->feedEntry as $prop) {
                    $value = "{$entry->$prop}";
                    if ($prop === 'link') {
                        $value = trim($value);
                        if (empty($value)) {
                            $value = "{$entry->link[0]['href']}";
                        }
                    }

                    // Fix pubDate
                    if ($prop === 'pubDate') {
                        $prop = 'publication_date';
                    }
                    $result['entries'][$counter][$prop] = $value;
                }

                $counter++;
            }
        }

        return $result;
    }
}
