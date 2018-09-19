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

namespace Sugarcrm\Sugarcrm\Util\Files;

use Sugarcrm\Sugarcrm\Security\Validator\Validator;
use Sugarcrm\Sugarcrm\Security\Validator\Constraints\File;

/**
 *
 * Utility function covering file loading operations like validating file
 * paths, performing file includes to retrieve variables, ...
 *
 * Note that the functionality of these utility functions should not be mixed
 * with auto loading concerns which belong the \SugarAutoLoader.
 *
 */
class FileLoader
{
    protected static $sugarBaseDirs = array();

    /**
     * Validate given file name. Whenever insecure input variables are used
     * to construct file paths it is recommended to use this methods to
     * validate the requested file path when explicitly calling include or
     * require in our code base.
     *
     * This method validates if the given file fits within the allowed base
     * directories and whether any invalid characters are present.
     * characters are described in FileValidator.
     *
     * @see \Sugarcrm\Sugarcrm\Security\Validator\Constraints\FileValidator
     *
     * @param string $file File name
     * @param boolean $upload Allow validation in upload directory
     * @return string File name
     * @throws \RuntimeException
     */
    public static function validateFilePath($file, $upload = false)
    {
        $baseDirs = self::getBaseDirs();

        // add upload directory to the allowed list
        if ($upload) {
            $uploadDir = ini_get('upload_tmp_dir');
            $baseDirs[] = self::getRealPath($uploadDir ? $uploadDir : sys_get_temp_dir());
        }

        $constraint = new File(array('baseDirs' => $baseDirs));
        $violations = Validator::getService()->validate($file, $constraint);
        if (count($violations) > 0) {
            $msg = array_reduce(iterator_to_array($violations), function ($msg, $violation) {
                return empty($msg) ? $violation->getMessage() : $msg . ' - ' . $violation->getMessage();
            });
            throw new \RuntimeException($msg);
        }
        return $constraint->getFormattedReturnValue();
    }

    /**
     * Get variable content from include file. This is the recommended method
     * to dynamically include variable from a file when the file name is based
     * on untrusted user input as it implicitly calls self::validateFilePath.
     *
     * @param string $file File name
     * @param string $returnVar Name of the variable
     * @return mixed
     */
    public static function varFromInclude($file, $returnVar)
    {
        $result = self::varsFromInclude($file, array($returnVar));
        return isset($result[$returnVar]) ? $result[$returnVar] : null;
    }

    /**
     * Get variables content from include file. This is the recommended method
     * to dynamically include variables from a file when the file name is based
     * on untrusted user input as it implicitly calls self::validateFilePath.
     *
     * @param string $file File name
     * @param array $returnVars List of variable names
     * @return array
     */
    public static function varsFromInclude($file, array $returnVars)
    {
        include self::validateFilePath($file);
        $returnVarsResult = array();
        foreach ($returnVars as $returnVar) {
            $returnVarsResult[$returnVar] = isset(${$returnVar}) ? ${$returnVar} : null;
        }
        return $returnVarsResult;
    }

    /**
     * Get base directories. This returns the sugar base directory and
     * optionally the shadow instance directory when shadow is in use.
     * @return array
     */
    public static function getBaseDirs()
    {
        if (empty(self::$sugarBaseDirs)) {
            self::$sugarBaseDirs = array(SUGAR_BASE_DIR);
            if (defined('SHADOW_INSTANCE_DIR')) {
                self::$sugarBaseDirs[] = SHADOW_INSTANCE_DIR;
            }

            foreach (self::$sugarBaseDirs as $key => $dir) {
                self::$sugarBaseDirs[$key] = self::getRealPath($dir);
            }
        }
        return self::$sugarBaseDirs;
    }

    /**
     * to validate and get real path
     * @param $path
     * @return string, normalized path
     */
    protected static function getRealPath($path)
    {
        $realpath = realpath($path);
        if (!$realpath) {
            throw new \RuntimeException("path: $path doesn't exist!");
        }
        return $realpath;
    }

}
