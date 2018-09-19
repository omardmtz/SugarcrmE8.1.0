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

 * Description:  Class to handle splitting a file into separate parts
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/

class ImportFileSplitter
{
    /**
     * Filename of file we are splitting
     */
    private $_sourceFile;

    /**
     * Count of files that we split the $_sourceFile into
     */
    private $_fileCount;

    /**
     * Count of records in $_sourceFile
     */
    private $_recordCount;

     /**
     * Maximum number of records per file
     */
    private $_recordThreshold;

    /**
     * Constructor
     *
     * @param string $source filename we are splitting
     */
    public function __construct(
        $source = null,
        $recordThreshold = 1000
        )
    {
            // sanitize crazy values to the default value
        if ( !is_int($recordThreshold) || $recordThreshold < 1 ){
        	//if this is not an int but is still a
        	//string representation of a number, then cast to an int
        	if(!is_int($recordThreshold) && is_numeric($recordThreshold)){
        		//cast the string to an int
        		$recordThreshold = (int)$recordThreshold;
        	}else{
        		//if not a numeric string, or less than 1, then default to 100
            	$recordThreshold = 100;
        	}
        }
        $this->_recordThreshold = $recordThreshold;
        $this->_sourceFile      = $source;
    }

    /**
     * Returns true if the filename given exists and is readable
     *
     * @return bool
     */
    public function fileExists()
    {
        if ( !is_file($this->_sourceFile) || !is_readable($this->_sourceFile)) {
           return false;
        }

        return true;
    }

    /**
     * Actually split the file into parts
     *
     * @param string $delimiter
     * @param string $enclosure
     * @param bool $has_header true if file has a header row
     */
    public function splitSourceFile(
        $delimiter = ',',
        $enclosure = '"',
        $has_header = false
        )
    {
        if (!$this->fileExists())
            return false;
        $importFile = new ImportFile($this->_sourceFile,$delimiter,$enclosure,false);
        $filecount = 0;
        $fw = sugar_fopen("{$this->_sourceFile}-{$filecount}", "w");
        $count = 0;
        $rows = '';
        // skip first row if we have a header row
        if ( $has_header && $importFile->getNextRow() ) {
            // mark as duplicate to stick header row in the dupes file
            $importFile->markRowAsDuplicate();
            // same for error records file
            $importFile->writeErrorRecord();
        }
        while ($row = $importFile->getNextRow(false)) {
            // after $this->_recordThreshold rows, close this import file and goto the next one
            if ( $count >= $this->_recordThreshold ) {
                fwrite($fw, $rows);
                $rows = '';
                fclose($fw);
                $filecount++;
                $fw = sugar_fopen("{$this->_sourceFile}-{$filecount}", "w");
                $count = 0;
            }
            $rows .= $row;
            $count++;
        }

        fwrite($fw, $rows);
        fclose($fw);
        $this->_fileCount   = $filecount;
        $this->_recordCount = ($filecount * $this->_recordThreshold) + $count;
        // increment by one to get true count of files created
        ++$this->_fileCount;
    }

    /**
     * Return the count of records in the file, if it's been processed with splitSourceFile()
     *
     * @return int count of records in the file
     */
    public function getRecordCount()
    {
        if ( !isset($this->_recordCount) )
            return false;

        return $this->_recordCount;
    }

    /**
     * Return the count of files created by the split, if it's been processed with splitSourceFile()
     *
     * @return int count of files created by the split
     */
    public function getFileCount()
    {
        if ( !isset($this->_fileCount) )
            return false;

        return $this->_fileCount;
    }

    /**
     * Return file name of one of the split files
     *
     * @param int $filenumber which split file we want
     *
     * @return string filename
     */
    public function getSplitFileName(
        $filenumber = 0
        )
    {
        if ( $filenumber >= $this->getFileCount())
            return false;

        return "{$this->_sourceFile}-{$filenumber}";
    }

}

