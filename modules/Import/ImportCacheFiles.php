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
/*********************************************************************************

 * Description: Static class to that is used to get the filenames for the various
 * cache files used
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/

class ImportCacheFiles
{
    /**#@+
     * Cache file names
     */
    const FILE_MISCELLANEOUS      = 'misc';
    const FILE_DUPLICATES         = 'dupes';
    const FILE_DUPLICATES_DISPLAY = 'dupesdisplay';
    const FILE_ERRORS             = 'error';
    const FILE_ERROR_RECORDS      = 'errorrecords';
    const FILE_ERROR_RECORDS_ONLY = 'errorrecordsonly';
    const FILE_STATUS             = 'status';
    /**#@-*/

    /**
     * List of all cache file names
     * 
     * @var array
     */
    protected static $all_files = array(
        self::FILE_MISCELLANEOUS,
        self::FILE_DUPLICATES,
        self::FILE_DUPLICATES_DISPLAY,
        self::FILE_ERRORS,
        self::FILE_ERROR_RECORDS,
        self::FILE_ERROR_RECORDS_ONLY,
        self::FILE_STATUS,
    );

    /**
     * Get import directory name
     * @return string
     */
    public static function getImportDir()
    {
        return "upload://import";
    }


    /**
     * Function generates a download link for the given import file
     *
     * @param string $fileName String value of the upload file name
     * @return string The converted URL of the file name
     */
    public static function convertFileNameToUrl($fileName)
    {
        $fileName = str_replace(self::getImportDir() . "/", "", $fileName);
        $fileName = "index.php?entryPoint=download&id=ImportErrors&type=import&tempName=" . $fileName . "&isTempFile=1";
        return $fileName;
    }


    /**
     * Returns the filename for a temporary file
     *
     * @param  string $type string to prepend to the filename, typically to indicate the file's use
     * @return string filename
     */
    private static function _createFileName($type = self::FILE_MISCELLANEOUS)
    {
        global $current_user;
        $importdir = self::getImportDir();
        // ensure dir exists and writable
        UploadStream::ensureDir($importdir, true);

        return "$importdir/{$type}_{$current_user->id}.csv";
    }

    /**
     * Ensure that all cache files are writable or can be created
     * 
     * @return bool
     */
    public static function ensureWritable()
    {
        foreach (self::$all_files as $type)
        {
            $filename = self::_createFileName($type);
            if (file_exists($filename) && !is_writable($filename))
            {
                return false;
            }
            elseif (!is_writable(dirname($filename)))
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns the duplicates filename (the ones used to download to csv file
     *
     * @return string filename
     */
    public static function getDuplicateFileName()
    {
        return self::_createFileName(self::FILE_DUPLICATES);
    }

    /**
     * Returns the duplicates display filename (the one used for display in html)
     *
     * @return string filename
     */
    public static function getDuplicateFileDisplayName()
    {
        return self::_createFileName(self::FILE_DUPLICATES_DISPLAY);
    }

    /**
     * Returns the error filename
     *
     * @return string filename
     */
    public static function getErrorFileName()
    {
        return self::_createFileName(self::FILE_ERRORS);
    }

    /**
     * Returns the error records filename
     *
     * @return string filename
     */
    public static function getErrorRecordsFileName()
    {
        return self::_createFileName(self::FILE_ERROR_RECORDS);
    }

    /**
     * Returns the error records filename
     *
     * @return string filename
     */
    public static function getErrorRecordsWithoutErrorFileName()
    {
        return self::_createFileName(self::FILE_ERROR_RECORDS_ONLY);
    }

    /**
     * Returns the status filename
     *
     * @return string filename
     */
    public static function getStatusFileName()
    {
        return self::_createFileName(self::FILE_STATUS);
    }

    /**
     * Clears out all cache files in the import directory
     */
    public static function clearCacheFiles()
    {
        global $sugar_config;
        $importdir = self::getImportDir();
        if ( is_dir($importdir) ) {
            $files = dir($importdir);
            while (false !== ($file = $files->read())) {
                if ( !is_dir($file) && stristr($file,'.csv') )
                    unlink("$importdir/$file");
            }
        }
    }
}
