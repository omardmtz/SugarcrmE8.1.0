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
 *
 * Service Dictionary for REST API
 *
 */
class ServiceDictionaryRest extends ServiceDictionary
{
    // definition of score weight
    const SCORE_WILDCARD = 0.75;
    const SCORE_MODULE = 1;
    const SCORE_EXACTMATCH = 1.75;
    const SCORE_CUSTOMEXTRA = 0.5;

    const WEIGHT_MINVERSION = 0.02;
    const WEIGHT_MAXVERSION = 0.02;
    const WEIGHT_MINMAXVERSION_MATCH = 0.02;
    const WEIGHT_BASE = 100000;

    /**
     * Loads the dictionary so it can be searche
     */
    public function loadDictionary()
    {
        $this->dict = $this->loadDictionaryFromStorage('rest');
    }

    /**
     * Looks up a route based on the passed in information
     * @param array $path Split up array of path elements
     * @param string $version The requested API verison
     * @param srting $requestType The HTTP request type (GET/POST/DELETE)
     * @param srting $platform The platform for the API request
     * @return array The best-match path element
     */
    public function lookupRoute($path, $version, $requestType, $platform)
    {
        $pathLength = count($path);

        // Put the request type on the front of the path, it is how it is indexed
        array_unshift($path, $requestType);

        // The first element (path length) we can find on our own, but the request type will need to be hunted normally
        if (!isset($this->dict[$pathLength])) {
            // There is no route with the same number of /'s as the requested route, send them on their way
            throw new SugarApiExceptionNoMethod('Could not find a route with '.$pathLength.' elements');
        }

        if (isset($this->dict[$pathLength][$platform])) {
            $route = $this->lookupChildRoute($this->dict[$pathLength][$platform], $path, $version);
        }

        // If we didn't find a route and we are on a non-base platform, see if we find one
        // in base. Using empty here because if platform route wasn't found then $route
        // will not yet be defined.
        if (empty($route) && $platform != 'base' && isset($this->dict[$pathLength]['base'])) {
            $route = $this->lookupChildRoute($this->dict[$pathLength]['base'], $path, $version);
        }

        if ($route == false) {
            throw new SugarApiExceptionNoMethod('Could not find a route with '.$pathLength.' elements');
        }

        return $route;
    }

    /**
     * Recursively digs through routes to find all options and picks the best one
     * @param array $routeBase The current list of routes you are picking between
     * @param array $path The list of path elements to search through
     * @param float $version The API version you are looking for
     * @return array The best-match path element
     */
    protected function lookupChildRoute($routeBase, $path, $version)
    {
        if (count($path) == 0) {
            // We are at the end of the lookup, the elements here are actual paths, we need to return the best one
            return $this->getBestEnding($routeBase, $version);
        }

        // Grab the element of the path we are actually looking at
        $pathElement = array_shift($path);

        $bestScore = 0.0;
        $bestRoute = false;
        // Try to match it against all of the options at this level
        foreach ($routeBase as $routeKey => $subRoute) {
            $match = false;

            if (substr($routeKey, 0, 1) == '<') {
                // It's a data-specific function match
                switch ($routeKey) {
                    case '<module>':
                        $match = $this->matchModule($pathElement);
                        break;
                }
            } elseif ($routeKey == '?') {
                // Wildcard, matches everything
                $match = true;
            } elseif ($routeKey == $pathElement) {
                // Direct string match
                $match = true;
            }

            if ($match) {
                $route = $this->lookupChildRoute($subRoute, $path, $version);
                if ($route != false && $route['score'] > $bestScore) {
                    $bestRoute = $route;
                    $bestScore = $route['score'];
                }
            }
        }

        return $bestRoute;
    }

    /**
     * Picks the best route from the leaf-nodes discovered through lookupChildRoute
     * @param array $routes The current list of routes you are picking between
     * @param array $path The list of path elements to search through
     * @param float $version The API version you are looking for
     * @return array The best-match path element
     */
    protected function getBestEnding($routes, $version)
    {
        $bestScore = 0.0;
        $bestRoute = false;

        foreach ($routes as $route) {
            if (isset($route['minVersion']) && $route['minVersion'] > $version) {
                // Min version is too low, look for another route
                continue;
            }
            if (isset($route['maxVersion']) && $route['maxVersion'] < $version) {
                // Max version is too high, look for another route
                continue;
            }

            // calculate extra weight for version
            $routeScore = $route['score'] + $this->getScoreWeightForVersion($route, $version);
            if ($routeScore > $bestScore) {
                $bestRoute = $route;
                $bestRoute['score'] = $routeScore;
                $bestScore = $bestRoute['score'];
            }
        }

        return $bestRoute;
    }

    /**
     * get extra score weight based on version
     * @param array $route The current candidate route
     * @param float $version The API version you are looking for
     * @return float
     */
    protected function getScoreWeightForVersion($route, $version)
    {
        $extraScore = 0.0;
        if (isset($route['minVersion']) && version_compare($route['minVersion'], $version, '<=')) {
            // normalize MAJOR.MINOR version
            $v = explode('.', $route['minVersion']);
            $majorVer = (int) $v[0];
            $minorVer = empty($v[1]) ? 0 : (int) $v[1];
            $wt = round(($majorVer*100 + $minorVer)/self::WEIGHT_BASE, 5);
            $extraScore += self::WEIGHT_MINVERSION + $wt;
        }

        if (isset($route['maxVersion']) && version_compare($route['maxVersion'], $version, '>=')) {
            $extraScore += self::WEIGHT_MAXVERSION;
        }

        if (isset($route['minVersion']) && isset($route['maxVersion']) && version_compare($route['maxVersion'], $version, '==')) {
            $extraScore += self::WEIGHT_MINMAXVERSION_MATCH;
        }

        return $extraScore;
    }

    protected function matchModule($pathElement)
    {
        return isset($GLOBALS['beanList'][$pathElement]);
    }

    /**
     * Pre register endpoints, currently initialized empty array
     */
    public function preRegisterEndpoints()
    {
        $this->endpointBuffer = array();
    }

    /**
     * Register end points
     * @param array $newEndpoints
     * @param string $file
     * @param string $fileClass
     * @param string $platform
     * @param boolean $isCustom
     */
    public function registerEndpoints($newEndpoints, $file, $fileClass, $platform, $isCustom)
    {
        if (!is_array($newEndpoints)) {
            return;
        }

        foreach ($newEndpoints as $endpoint) {
            /*
             * Every endpoint can have one or more request types. This will mostly not be
             * the case, however in certain situations we wish to perform a GET using a
             * request body to avoid too long urls. Because not every REST client has the
             * ability to perform a GET with a request body, in those situation we want to
             * be able to register the same endpoint for both GET and POST.
             */
            $reqTypes = is_array($endpoint['reqType']) ? $endpoint['reqType'] : array($endpoint['reqType']);

            foreach ($reqTypes as $reqType) {
                // We use the path length, platform, and request type as the first three keys to search by
                $path = $endpoint['path'];
                array_unshift($path, count($endpoint['path']), $platform, $reqType);

                $endpointScore = 0.0;
                if (isset($endpoint['extraScore'])) {
                    $endpointScore += $endpoint['extraScore'];
                }

                if ($isCustom) {
                    // Give some extra weight to custom endpoints so they can override built in endpoints
                    $endpointScore = $endpointScore + self::SCORE_CUSTOMEXTRA;
                }

                $endpoint['file'] = $file;
                $endpoint['className'] = $fileClass;
                $endpoint['reqType'] = $reqType;

                $this->addToPathArray($this->endpointBuffer, $path, $endpoint, $endpointScore);
            }
        }
    }

    /**
     * Recursively construct the full endpoint collection.
     *
     * @param array $parent
     * @param array $path
     * @param array $endpoint
     * @param float $score
     */
    protected function addToPathArray(&$parent, $path, $endpoint, $score)
    {
        if (!isset($path[0])) {
            // We are out of elements, no need to go any further
            $endpoint['score'] = $score;
            $parent[] = $endpoint;
            return;
        }

        $currPath = array_shift($path);

        if ($currPath === '?') {
            // This matches anything
            $myScore = self::SCORE_WILDCARD;
        } elseif ($currPath[0] === '<') {
            // This is looking for a specfic data type
            $myScore = self::SCORE_MODULE;
        } else {
            // This is looking for a specific string
            $myScore = self::SCORE_EXACTMATCH;
        }


        if (!isset($parent[$currPath])) {
            $parent[$currPath] = array();
        }

        $this->addToPathArray($parent[$currPath], $path, $endpoint, ($score + $myScore));
    }

    /**
     * Get registered end points
     * @return array
     */
    public function getRegisteredEndpoints()
    {
        return $this->endpointBuffer;
    }
}
