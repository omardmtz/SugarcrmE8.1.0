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
 * Declare the namespace for this class
 */
namespace Sugarcrm\Sugarcrm\ProcessManager;

/**
 * Factory class for handling creation of objects for Process Management
 * @package ProcessManager
 */
class Factory
{
    /**
     * Local stack of cached objects
     * @var array
     */
    protected static $cache = [];

    /**
     * Mapping of field types to evaluator objects types
     * @var array
     */
    protected static $fieldEvaluatorTypeMap = [
        'date' => 'Datetime',
        'time' => 'Datetime',
        'datetimecombo' => 'Datetime',
        'datetime' => 'Datetime',
        // int is a reserved word in PHP7
        'int' => 'Integer',
    ];

    /**
     * Root of all files that shipped with PMSE
     * @var string
     */
    protected static $pmseBasePath = 'modules/';

    /**
     * PMSE Paths off of the pmseBasePath where files live
     * @var array
     */
    protected static $pmsePaths = [
        'pmse_Business_Rules/',
        'pmse_Business_Rules/clients/base/api/',
        'pmse_Emails_Templates/',
        'pmse_Emails_Templates/clients/base/api/',
        'pmse_Inbox/clients/base/api/',
        'pmse_Inbox/engine/',
        'pmse_Inbox/engine/parser/',
        'pmse_Inbox/engine/PMSEElements/',
        'pmse_Inbox/engine/PMSEHandlers/',
        'pmse_Inbox/engine/PMSEPreProcessor/',
        'pmse_Inbox/engine/wrappers/',
        'pmse_Project/clients/base/api/',
        'pmse_Project/clients/base/api/wrappers/',
        'pmse_Project/clients/base/api/wrappers/PMSEObservers/'
    ];

    /*
     * default exception type
     */
    protected static $defaultExceptionType = 'Base';

    /**
     * Gets an array of assembled paths for include.
     * @return array
     */
    protected static function getPMSEPaths()
    {
        // Set a default return
        $paths = [];

        // Loop and set now
        foreach (self::$pmsePaths as $path) {
            // Assumption here: basePaths are properly suffixed with /
            $paths[] = static::$pmseBasePath . $path;
        }

        return $paths;
    }


    /**
     * Gets an Advanced Workflow class. This expects a mapping of file basename to
     * class name. This method allows for extending an Advanced Workflow class using
     * the 'Custom' prefix on a classname OR overriding an Advanced Workflow class
     * completely by reusing the name of the class/file. Priority is given to
     * Custom classes before overrides.
     *
     * @param string $name Name of the element to get the class for
     * @param string $prefix Prefix to inject into the class name
     * @return string
     */
    public static function getPMSEClass($name, $prefix = '')
    {
        // Default variable for our classname
        $class = '';

        // Handle verification of the name being requested
        if (empty($name)) {
            $msg = 'Cannot load an unnamed PMSE Object';
            $exception = static::getException('Runtime', $msg);
            throw $exception;
        }

        // Get the proper class name for this
        $name = static::getPMSEClassName($name, $prefix);

        // Get the paths to traverse
        $paths = self::getPMSEPaths();

        // First check for Custom classes of the type Custom$name
        foreach ($paths as $path) {
            $custom = 'Custom' . $name;
            if (\SugarAutoLoader::requireWithCustom("custom/$path{$custom}.php") !== false) {
                // Set our class name and move on
                $class = $custom;

                // Stop looking when we find something
                break;
            }
        }

        // Next check for PMSE standard / overridden classes
        if (empty($class)) {
            foreach ($paths as $path) {
                if (\SugarAutoLoader::requireWithCustom("$path{$name}.php") !== false) {
                    // Set it and forget it
                    $class = $name;

                    // Again, stop searching if we find something
                    break;
                }
            }
        }

        // Validate our return before sending anything back
        if (empty($class)) {
            $msg = "Unable to find/load a PMSE class named $name";
            $exception = static::getException('Runtime', $msg);
            throw $exception;
        }

        // Send back the class name
        return $class;
    }

    /**
     * Gets an Advanced Workflow object. This expects a mapping of file basename to
     * class name. This method allows for extending an Advanced Workflow class using
     * the 'Custom' prefix on a classname OR overriding an Advanced Workflow class
     * completely by reusing the name of the class/file. Priority is given to
     * Custom classes before overrides.
     *
     * @param string $name Name of the element to get the object for
     * @param string $prefix Prefix to inject into the class name
     * @param boolean $noCache Flag to tell this method not to cache
     * @return PMSE* Object
     */
    public static function getPMSEObject($name, $prefix = '', $noCache = false)
    {
        // If there is no current cached class name, get it and cache it
        $key = $name . $prefix;
        if (empty(static::$cache['classes'][$key]) || $noCache) {
            // Get the class name for this object and cache it so we don't have
            // to continually find the class name
            static::$cache['classes'][$key] = static::getPMSEClass($name, $prefix);
        }

        // Get new object. Argument passing will take place in other methods.
        return new static::$cache['classes'][$key];
    }

    /**
     * Gets a modified class name based on $prefix prefix
     * @param string $name Name of the element to get the object for
     * @param string $prefix Prefix to inject into the class name
     * @return string
     */
    public static function getPMSEClassName($name, $prefix = '')
    {
        // If the PMSE prefix was left off, add it
        if (substr($name, 0, 4) !== 'PMSE') {
            // And add the prefix, if there is one
            $name = "PMSE$prefix$name";
        } else {
            // Add in the prefix between the PMSE and class name. A simple str_replace
            // here could be problematic, if classes have another PMSE in the name.
            $name = "PMSE$prefix" . substr($name, 4);
        }

        return $name;
    }

    /**
     * Gets the correct field evaluator type for building an field evaluator
     * object.
     * @param array $def Field def for this field
     * @return string
     */
    protected static function getFieldEvaluatorType($def)
    {
        // Get the proper type for this field from the vardef
        $type = isset($def['custom_type']) ? $def['custom_type'] : $def['type'];
        if (isset(static::$fieldEvaluatorTypeMap[$type])) {
            return static::$fieldEvaluatorTypeMap[$type];
        }

        return ucfirst(strtolower($type));
    }

    /**
     * Gets a ProcessManager\EvaluatorInterface object
     * @param array $def Field def for this field
     * @param boolean $new Flag that tells this method whether to get a new object
     * @return ProcessManager\EvaluatorInterface
     */
    public static function getFieldEvaluator(array $def, $new = false)
    {
        // Get the proper type for this field from the vardef
        $type = static::getFieldEvaluatorType($def);

        if (!isset(static::$cache['evaluators'][$type]) || $new === true) {
            // Get the field evaluator namespace root
            $nsRoot = 'Sugarcrm\\Sugarcrm\\ProcessManager\\Field\\Evaluator\\';

            // Get the class name for this field type, getting the custom class if
            // found
            $class = \SugarAutoLoader::customClass($nsRoot . $type);

            // Set the base class name, getting the custom class if found
            $base = \SugarAutoLoader::customClass($nsRoot . 'Base');

            // Set the class name to load based on availability of the class. If
            // the type class exists, use it, otherwise fallback to the failsafe
            // base class name.
            $load = class_exists($class) ? $class : $base;

            // Load what we have now
            static::$cache['evaluators'][$type] = new $load;
        }

        return static::$cache['evaluators'][$type];
    }

    /**
     * Gets a Process Element object. This expects a mapping of file basename to
     * class name. This method allows for extending a PMSEElement class using
     * the 'Custom' prefix on a classname OR overriding a PMSEElement class
     * completely by reusing the name of the class/file. Priority is given to
     * Custom classes before overrides.
     *
     * Eventually this will return a ProcessManager\Element object, but until
     * all PMSE classes are moved out to ProcessManager classes, this will have
     * to do.
     *
     * @todo  Create a PMSERunnable and have all PMSEElement object
     * implement it.
     * @param string $name Name of the element to get the object for
     * @return PMSERunnable
     */
    public static function getElement($name = '')
    {
        // Start with the path
        $path = 'modules/pmse_Inbox/engine/PMSEElements/';

        // Default element class name
        if (empty($name)) {
            $name = 'PMSEElement';
        } elseif (substr($name, 0, 4) !== 'PMSE') {
            $name = 'PMSE' . $name;
        }

        // Default return value
        $return = null;

        // Create a Custom class name
        $custom = 'Custom' . $name;

        // This checks for Custom classes that will likely extends a base class
        if (\SugarAutoLoader::requireWithCustom("custom/$path{$custom}.php") !== false) {
            $return = new $custom;
        } elseif (\SugarAutoLoader::requireWithCustom("$path{$name}.php") !== false) {
            // This checks for custom classes that override a base class
            $return = new $name;
        }

        // Before returning, we should validate that the object is an instance
        // of PMSEElementInterface
        if ($return instanceof \PMSERunnable) {
            return $return;
        }

        // Handle the exception for this case
        $msg = "Could not instantiate a Process Manager $name Element object.";
        $exception = static::getException('Runtime', $msg);
        throw $exception;
    }

    /**
     * Gets a Process Manager exception object
     * @param string $type The type of Exception to get
     * @param string $message The exception message to throw and log
     * @param int $code The exception code
     * @param Exception $previous The previous exception (used for chaining)
     * @return Exception
     */
    public static function getException($type = '', $message = '', $code = 0, $previousException = null)
    {
        if (empty($type)) {
            $type = static::$defaultExceptionType;
        }

        $exceptionClassName = ucfirst(strtolower($type)) . 'Exception';
        $defaultClassName = static::$defaultExceptionType . 'Exception';

        // Handle the message now
        if (empty($message)) {
            $message = 'An unknown Process Manager exception had occurred';
        }

        // Get the exception namespace root
        $nsRoot = 'Sugarcrm\\Sugarcrm\\ProcessManager\\Exception\\';

        // Get the full class name for this exception, getting the custom class if found
        $exceptionClass = \SugarAutoLoader::customClass($nsRoot . $exceptionClassName);

        // Get the full default class name, getting the custom class if found
        $defaultClass = \SugarAutoLoader::customClass($nsRoot . $defaultClassName);

        // Set the class name to load based on availability of the class. If the type class exists,
        // use it, otherwise fallback to the default class
        $class = class_exists($exceptionClass) ? $exceptionClass : $defaultClass;

        // Create the exception object
        $obj = new $class($message, $code, $previousException);

        // Return it
        return $obj;
    }
}
