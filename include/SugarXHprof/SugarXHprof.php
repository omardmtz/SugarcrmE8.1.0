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
 * Class allows us to use XHprof for profiling
 *
 * To run profiler you should call SugarXHprof::getInstance()->start();
 * To stop profiler you should call SugarXHprof::getInstance()->end();
 * 'start' method registers 'end' method as shutdown function because of it call of 'end' method is unnecessary if you want profile all calls
 * Also 'start' method is called automatically in entryPoint.php file
 *
 * To see reports you can use https://github.com/sugarcrm/xhprof-viewer or https://github.com/lox/xhprof
 *
 * If you want to customize SugarXHprof you should create file in custom/include/SugarXHprof/ folder and name file as name of your custom class
 * Change $sugar_config['xhprof_config']['manager'] to be name of your custom class
 * Custom class has to extend from SugarXHprof
 * If custom class doesn't exist or doesn't extend from SugarXHprof then SugarXHprof be used
 *
 * To enable profiling you should add next properties to config_override.php
 *
 * @see SugarXHprof::$enable            for $sugar_config['xhprof_config']['enable']
 * @see SugarXHprof::$manager           for $sugar_config['xhprof_config']['manager']
 * @see SugarXHprof::$log_to            for $sugar_config['xhprof_config']['log_to']
 * @see SugarXHprof::$sample_rate       for $sugar_config['xhprof_config']['sample_rate']
 * @see SugarXHprof::$ignored_functions for $sugar_config['xhprof_config']['ignored_functions']
 * @see SugarXHprof::$flags             for $sugar_config['xhprof_config']['flags']
 * @see SugarXHprof::$track_sql             for $sugar_config['xhprof_config']['track_sql']
 * @see SugarXHprof::$track_sql_backtrace   for $sugar_config['xhprof_config']['track_sql_backtrace']
 * @see SugarXHprof::$track_elastic         for $sugar_config['xhprof_config']['track_elastic']
 * @see SugarXHprof::$track_elastic_backtrace   for $sugar_config['xhprof_config']['track_elastic_backtrace']
 * @see SugarXHprof::$save_to               for $sugar_config['xhprof_config']['save_to']
 * @see SugarXHprof::$filter_wt             for $sugar_config['xhprof_config']['filter_wt']
 * @see SugarXHprof::$memory_limit             for $sugar_config['xhprof_config']['memory_limit']
 * @see SugarXHprof::$mongodb_uri           for $sugar_config['xhprof_config']['mongodb_uri']
 * @see SugarXHprof::$mongodb_db            for $sugar_config['xhprof_config']['mongodb_db']
 * @see SugarXHprof::$mongodb_collection    for $sugar_config['xhprof_config']['mongodb_collection']
 * @see SugarXHprof::$mongodb_options       for $sugar_config['xhprof_config']['mongodb_options']
 */
use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Performance\Dbal\XhprofLogger;

class SugarXHprof
{
    /**
     * @var SugarXHprof instance of profiler
     */
    protected static $instance = null;

    /**
     * @var bool enable profiler or not, it will be disabled by some reasons
     * @see SugarXHprof::loadConfig()
     */
    protected static $enable = false;

    /**
     * @var string class of manager for customization, has to extend from SugarXHprof class
     */
    protected static $manager = __CLASS__;

    /**
     * @var string path to directory for logs, if log_to is empty then xhprof.output_dir be used
     */
    protected static $log_to = '';

    /**
     * @var int where value is a number and 1/value requests are profiled. So to sample all requests set it to 1
     */
    protected static $sample_rate = 10;

    /**
     * @var array array of function names to ignore from the profile (pass into xhprof_enable)
     */
    protected static $ignored_functions = array();

    /**
     * @var int minimum value for 'wall time' in milliseconds
     */
    protected static $filter_wt = 0;

    /**
     * @var string memory limit to set while saving profile data to storage
     */
    protected static $memory_limit = '2048M';

    /**
     * @var string place to save data, if $save_to is 'mongodb' then data are stored in the mongo DB
     */
    protected static $save_to = 'file';

    /**
     * @var string mongo server info
     */
    protected static $mongodb_uri = 'mongodb://localhost:27017';

    /**
     * @var string name of mongo database
     */
    protected static $mongodb_db = 'xhprof';

    /**
     * @var string name of mongo collections
     */
    protected static $mongodb_collection = 'results';

    /**
     * @var array mongo options
     * @see http://php.net/manual/en/mongoclient.construct.php#mongo.mongoclient.construct.parameters
     */
    protected static $mongodb_options = array(
        "username" => '',
        "password" => ''
    );

    /**
     * @var int flags for xhprof
     * @see http://www.php.net/manual/xhprof.constants.php
     */
    protected static $flags = 0;

    /**
     * @var bool track sql data, if $track_sql is 'true' then sql data are stored
     */
    protected static $track_sql = true;

    /**
     * @var bool track sql backtrace data, if $track_sql_backtrace is 'true' then sql backtrace data are stored
     */
    protected static $track_sql_backtrace = true;

    /**
     * @var bool track elastic data, if it is 'true' then elastic data are stored
     */
    protected static $track_elastic = true;

    /**
     * @var bool track elastic backtrace data, if it is 'true' then elastic backtrace data are stored
     */
    protected static $track_elastic_backtrace = true;

    /**
     * Sql queries and related information
     *
     * @var array
     */
    protected $sqlTracker;

    /**
     * @var array Elastic queries and related information
     */
    protected $elasticTracker;

    /**
     * @var float Profiling start time
     */
    protected $startTime;

    /**
     * Populates configuration from $sugar_config to self properties
     */
    protected static function loadConfig()
    {
        if (!empty($GLOBALS['sugar_config']['xhprof_config'])) {
            foreach ($GLOBALS['sugar_config']['xhprof_config'] as $k => $v) {
                if (isset($v) && property_exists(static::$manager, $k)) {
                    static::${$k} = $v;
                }
            }
        }

        if (!static::$enable) {
            return;
        }

        // disabling profiler if XHprof extension is not loaded
        if (extension_loaded('xhprof') == false) {
            static::$enable = false;

            return;
        }

        if (static::$save_to == 'file') {
            // using default directory for profiler if it is not set
            if (empty(static::$log_to)) {
                static::$log_to = ini_get('xhprof.output_dir');
                if (empty(static::$log_to)) {
                    static::$log_to = sys_get_temp_dir();

                    error_log("Warning: Must specify directory location for XHProf runs. " .
                        "Trying " . static::$log_to . " as default. You can either set "
                        . "\$sugar_config['xhprof_config']['log_to'] sugar config " .
                        "or set xhprof.output_dir ini param.");
                }
            }

            // disabling profiler if directory is not exist or is not writable
            if (is_dir(static::$log_to) == false || is_writable(static::$log_to) == false) {
                static::$enable = false;
            }
        }

        // enable SugarXhprofLogger class for Doctrine
        if (static::$enable && empty($GLOBALS['installing'])) {
            $logger = DBManagerFactory::getDbalLogger();
            $decorator = new XhprofLogger(static::$instance, $logger);
            DBManagerFactory::setDbalLogger($decorator);
        }
    }

    /**
     * Tries to load custom profiler. If it doesn't exist then use itself
     *
     * @return SugarXHprof
     */
    public static function getInstance()
    {
        if (static::$instance != null) {
            return static::$instance;
        }

        static::$manager = !empty($GLOBALS['sugar_config']['xhprof_config']['manager']) ?
            $GLOBALS['sugar_config']['xhprof_config']['manager'] :
            static::$manager;

        if (is_file('custom/include/SugarXHprof/' . static::$manager . '.php')) {
            require_once 'custom/include/SugarXHprof/' . static::$manager . '.php';
        } elseif (is_file('include/SugarXHprof/' . static::$manager . '.php')) {
            require_once 'include/SugarXHprof/' . static::$manager . '.php';
        }

        if (class_exists(static::$manager) && is_subclass_of(static::$manager, __CLASS__)) {
            static::$instance = new static::$manager();
        } else {
            static::$instance = new self();
        }

        static::$instance->loadConfig();

        return static::$instance;
    }

    /**
     * Method tries to detect entryPoint, service, module & action and returns it as string
     *
     * @return string action
     */
    static public function detectAction()
    {
        $action = '';

        $request = InputValidation::getService();
        // index.php
        if (!empty($GLOBALS['app']) && $GLOBALS['app'] instanceof SugarApplication && $GLOBALS['app']->controller instanceof SugarController) {
            // we validate entry point name against controller registry
            $entryPoint = $request->getValidInputRequest('entryPoint');
            if ($entryPoint) {
                if (!empty($GLOBALS['app']->controller->entry_point_registry) && !empty($GLOBALS['app']->controller->entry_point_registry[$entryPoint])) {
                    $action .= 'entryPoint.' . $entryPoint;
                } else {
                    $action .= 'entryPoint.unknown';

                }
            } else {
                $action .= $GLOBALS['app']->controller->module . '.' . $GLOBALS['app']->controller->action;
            }
        } // soap.php
        elseif (!empty($GLOBALS['server']) && $GLOBALS['server'] instanceof soap_server) {
            if ($GLOBALS['server']->methodname) {
                $action .= 'soap.' . $GLOBALS['server']->methodname;
            } else {
                $action .= 'soap.wsdl';
            }
        } // service soap
        elseif (!empty($GLOBALS['service_object']) && $GLOBALS['service_object'] instanceof SugarSoapService) {
            $action .= 'soap.' . $GLOBALS['service_object']->getRegisteredClass();
            if ($GLOBALS['service_object']->getServer() instanceof soap_server) {
                if ($GLOBALS['service_object']->getServer()->methodname) {
                    $action .= '.' . $GLOBALS['service_object']->getServer()->methodname;
                } else {
                    $action .= '.wsdl';
                }
            } else {
                $action .= '.unknown';
            }
        } // service rest
        elseif (!empty($GLOBALS['service_object']) && $GLOBALS['service_object'] instanceof SugarRestService) {
            $action .= 'rest.' . $GLOBALS['service_object']->getRegisteredImplClass();
            $method = $request->getValidInputRequest('method');
            if (!empty($method) && method_exists($GLOBALS['service_object']->implementation, $method)) {
                $action .= '.' . $method;
            } elseif (empty($method)) {
                $action .= '.index';
            } else {
                $action .= '.unknown';
            }
        } // unknown
        else {
            $action .= basename($_SERVER['SCRIPT_FILENAME']);
        }

        if ($action == 'rest.php' && isset($GLOBALS['service'])) {
            $rawPath = $GLOBALS['service']->getRequest()->getRawPath();
            $method = $GLOBALS['service']->getRequest()->getMethod();
            $action = $method . '.rest.' . trim($rawPath, ' /');
        }

        return $action;
    }

    /**
     * Replaces all characters that are not alphanum, dash or underscore with underscores
     *
     * @param $runName
     * @return mixed
     */
    protected static function cleanActionString($runName)
    {
        return preg_replace('/[^\w\d_-]/', '_', $runName);
    }

    /**
     * Tries to enabled xhprof if all settings were passed
     */
    public function start()
    {
        if (static::$enable == false) {
            return;
        }

        if (static::$sample_rate == 0) {
            static::$enable = false;
            return;
        }

        $rate = 1 / static::$sample_rate * 100;
        if (rand(0, 100) > $rate) {
            static::$enable = false;
            return;
        }

        $this->resetSqlTracker();
        $this->resetElasticTracker();

        register_shutdown_function(array(
            $this,
            'end'
        ));

        $this->startTime = isset($_SERVER['REQUEST_TIME_FLOAT']) ? $_SERVER['REQUEST_TIME_FLOAT'] : microtime(true);

        xhprof_enable(static::$flags, array(
            'ignored_functions' => static::$ignored_functions
        ));
    }

    /**
     * Is profiler enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return static::$enable;
    }

    /**
     * Reset sql tracker
     */
    public function resetSqlTracker()
    {
        $this->sqlTracker = array(
            'summary_time' => 0,
            'sql' => array(),
            'backtrace_calls' => array(),
        );
    }

    /**
     * Reset elastic tracker
     */
    public function resetElasticTracker()
    {
        $this->elasticTracker = array(
            'summary_time' => 0,
            'queries' => array(),
            'backtrace_calls' => array(),
        );
    }

    /**
     * Tries to collect data from XHprof after call of 'start' method
     */
    public function end()
    {
        if (!static::$enable) {
            return;
        }

        static::$enable = false;
        $origMemLimit = ini_get('memory_limit');
        ini_set('memory_limit', static::$memory_limit);

        $xhprofData = xhprof_disable();
        $wallTime = $xhprofData['main()']['wt'];

        if ($wallTime > static::$filter_wt * 1000) {
            $sqlQueries = count($this->sqlTracker['sql']);
            $elasticQueries = count($this->elasticTracker['queries']);
            $action = static::cleanActionString(static::detectAction());

            $runName = implode('d', array(
                    str_replace('.', 'd', $this->startTime),
                    $wallTime,
                    $sqlQueries,
                    $elasticQueries
                )) . '.' . $action;

            $this->saveRun($runName, $xhprofData);
        }

        ini_set('memory_limit', $origMemLimit);
    }

    /**
     * Saves the data collected to the specified storage depending on the configuration
     *
     * @param $runName
     * @param $xhprofData
     */
    protected function saveRun($runName, $xhprofData)
    {
        switch (static::$save_to) {
            case 'mongodb':
                $this->saveDataToMongoDB($xhprofData);
                break;

            case 'file':
                $this->saveDataToFile($xhprofData, $runName . '.xhprof');

                if ($this->sqlTracker['sql']) {
                    $this->saveDataToFile($this->sqlTracker, $runName . '.xhsql');
                }

                if ($this->elasticTracker['queries']) {
                    $this->saveDataToFile($this->elasticTracker, $runName . '.xhelastic');
                }
                break;

            default:
                error_log('Invalid `save_to` configuration value');
                break;
        }
    }

    /**
     * Adds info about an sql query
     *
     * @param $sql
     * @param $time
     */
    public function trackSQL($sql, $time)
    {
        if ($this->isEnabled() && static::$track_sql) {
            $this->sqlTracker['summary_time'] += $time;

            $this->sqlTracker['sql'][] = array(
                $sql,
                $time,
                static::$track_sql_backtrace
                    ? $this->xhpTrace(debug_backtrace(), $this->sqlTracker['backtrace_calls'])
                    : null,
            );
        }
    }

    /**
     * Adds info about an elastic query
     *
     * @param $query
     * @param $queryData
     * @param $queryTime
     */
    public function trackElasticQuery($query, $queryData, $queryTime)
    {
        if ($this->isEnabled() && static::$track_elastic) {
            $this->elasticTracker['summary_time'] += $queryTime;
            $this->elasticTracker['queries'][] = array(
                vsprintf("%s %s", $query),
                $queryData,
                $queryTime,
                static::$track_elastic_backtrace ? $this->xhpTrace(debug_backtrace(),
                    $this->elasticTracker['backtrace_calls']) : null
            );
        }
    }

    /**
     * Processes the stack trace of a query
     *
     * @param $trace
     * @param array $callsHash
     * @return string
     */
    protected function xhpTrace($trace, array &$callsHash = array())
    {
        array_splice($trace, 0, 2);
        $format = '#%s %s [Line: %s] %s(%s)';
        $strLimit = 64;
        $traceShort = array();
        $repeated = array();

        foreach ($trace as $index => $t) {
            $args = '';
            if (isset($t['args']) && !empty($t['args'])) {
                foreach ($t['args'] as $a) {
                    if (!empty($args)) {
                        $args .= ', ';
                    }
                    switch (gettype($a)) {
                        case 'integer':
                        case 'double':
                            $args .= $a;
                            break;
                        case 'string':
                            if (strlen($a) > $strLimit) {
                                $a = substr($a, 0, $strLimit) . '...';
                            }
                            $args .= "\"$a\"";
                            break;
                        case 'array':
                            $args .= 'Array(' . count($a) . ')';
                            break;
                        case 'object':
                            $args .= 'Object(' . get_class($a) . ')';
                            break;
                        case 'resource':
                            $args .= 'Resource(' . strstr($a, '#') . ')';
                            break;
                        case 'boolean':
                            $args .= $a ? 'True' : 'False';
                            break;
                        case 'NULL':
                            $args .= 'Null';
                            break;
                        default:
                            $args .= 'Unknown';
                    }
                }
            }

            $callName = (isset($t['class']) ? $t['class'] : '')
                . (isset($t['type']) ? $t['type'] : '')
                . (isset($t['function']) ? $t['function'] : '');

            $callsHash[$callName] = isset($callsHash[$callName]) ? $callsHash[$callName] + 1 : 1;
            $repeated[$callName] = isset($repeated[$callName]) ? $repeated[$callName] + 1 : 0;

            $traceShort[] = sprintf($format,
                $index + 1,
                isset($t['file']) && $t['file'] ? $t['file'] : 'n/a',
                isset($t['line']) && $t['line'] ? $t['line'] : 'n/a',
                $callName,
                $args
            );
        }

        foreach ($repeated as $k => $v) {
            if ($v > 0) {
                $callsHash[$k] -= $v;
                for ($i = 1; $i <= $v; $i++) {
                    $name = $k . '@' . $i;
                    $callsHash[$name] = isset($callsHash[$name]) ? $callsHash[$name] + 1 : 1;
                }
            }
        }

        return implode("\n", $traceShort);
    }

    /**
     * Serializes and stores data to the specified file in `log_to` directory
     *
     * @param $data
     * @param $filename
     */
    protected function saveDataToFile($data, $filename)
    {
        $filename = static::$log_to . '/' . $filename;

        $file = fopen($filename, 'w');
        if ($file) {
            $data = serialize($data);
            fwrite($file, $data);
            fclose($file);
        } else {
            error_log("Could not open $filename\n");
        }
    }

    /**
     * Add meta data to the results and stores it in the mongo database
     *
     * @param $data
     */
    protected function saveDataToMongoDB($data)
    {
        if (!extension_loaded('mongo')) {
            error_log('xhprof - extension mongo not loaded');

            return;
        }

        $uri = array_key_exists('REQUEST_URI', $_SERVER)
            ? $_SERVER['REQUEST_URI']
            : null;
        if (empty($uri) && isset($_SERVER['argv'])) {
            $cmd = basename($_SERVER['argv'][0]);
            $uri = $cmd . ' ' . implode(' ', array_slice($_SERVER['argv'], 1));
        }

        $time = array_key_exists('REQUEST_TIME', $_SERVER)
            ? $_SERVER['REQUEST_TIME']
            : time();
        $requestTimeFloat = $this->startTime;

        $requestTs = new MongoDate($time);
        $requestTsMicro = new MongoDate($requestTimeFloat[0], $requestTimeFloat[1]);

        $mongoData['meta'] = array(
            'url' => $uri,
            'SERVER' => $_SERVER,
            'get' => $_GET,
            'env' => $_ENV,
            'simple_url' => preg_replace('/\=\d+/', '', $uri),
            'request_ts' => $requestTs,
            'request_ts_micro' => $requestTsMicro,
            'request_date' => date('Y-m-d', $time),
        );

        $mongoData['profile'] = $data;
        if (static::$track_sql) {
            $mongoData['sqlTracker'] = $this->sqlTracker;
        }
        if (static::$track_elastic) {
            $mongoData['elasticTracker'] = $this->elasticTracker;
        }
        unset($data);

        try {
            $mongo = new MongoClient(static::$mongodb_uri, static::$mongodb_options);

            $db = static::$mongodb_db;
            $collection = static::$mongodb_collection;
            $mongo->$db->$collection->insert($mongoData);
        } catch (\MongoException $e) {
            error_log($e->getMessage() . $e->getTraceAsString());
        }
    }
}
