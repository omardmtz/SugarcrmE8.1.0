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

require_once 'modules/ModuleBuilder/parsers/constants.php' ;

class History implements HistoryInterface
{

    private $_dirname ; // base directory for the history files
    private $_basename ; // base name for a history file, for example, listviewdef.php
    private $_list ; // the history - a list of history files

    private $_previewFilename ; // the location of a file for preview

    /*
     * Constructor
     * @param string $previewFilename The filename which the caller expects for a preview file
     */
    public function __construct ( $previewFilename )
    {
        $GLOBALS [ 'log' ]->debug ( get_class ( $this ) . "->__construct( {$previewFilename} )" ) ;
        $this->_previewFilename = $previewFilename ;
        $this->_list = array ( ) ;

        $this->_basename = basename ( $this->_previewFilename ) ;
        $this->_dirname = dirname ( $this->_previewFilename );
 	    $this->_historyLimit = isset ( $GLOBALS [ 'sugar_config' ] [ 'studio_max_history' ] ) ? $GLOBALS [ 'sugar_config' ] [ 'studio_max_history' ] : 50 ;

        // create the history directory if it does not already exist
        if (!is_dir($this->_dirname)) {
           mkdir_recursive($this->_dirname);
         }
        else
        {
            // Reconstruct the history from the saved files
            $filenameList = glob($this->getFileByTimestamp('*'));
            if (!empty($filenameList))
            {
                foreach ($filenameList as $filename)
                {
                    if(preg_match('/(\d+)$/', $filename, $match))
                    {
                        $this->_list [] = $match[1];
                    }
                }
            }
        }
        // now sort the files, oldest first
        if (count ( $this->_list ) > 0)
        {
            sort ( $this->_list ) ;
        }

        // Make sure to clear the old _previewFilename on instantiation
        $this->undoRestore();
    }


    /*
     * Get the most recent item in the history
     * @return timestamp of the first item
     */
    public function getCount ()
    {
        return count ( $this->_list ) ;
    }

    /*
     * Get all the items in the history
     * @return list array
     */
    public function getList ()
    {
        return $this->_list;
    }

    /*
     * Get the most recent item in the history
     * @return timestamp of the first item
     */
    public function getFirst ()
    {
        return end ( $this->_list ) ;
    }

    /*
     * Get the oldest item in the history (the default layout)
     * @return timestamp of the last item
     */
    public function getLast ()
    {
        return reset ( $this->_list ) ;
    }

    /*
     * Get the next oldest item in the history
     * @return timestamp of the next item
     */
    public function getNext ()
    {
        return prev ( $this->_list ) ;
    }

    /*
     * Get the nth item in the history (where the zeroeth record is the most recent)
     * @return timestamp of the nth item
     */
     public function getNth ( $index )
    {
        $value = end ( $this->_list ) ;
        $i = 0 ;
        while ( $i < $index )
        {
            $value = prev ( $this->_list ) ;
            $i ++ ;
        }
        return $value ;
    }

    /*
     * Add an item to the history
     * @return String   A GMT Unix timestamp for this newly added item
     */
    public function append ($path)
    {
        // make sure we don't have a duplicate filename - highly unusual as two people should not be using Studio/MB concurrently, but when testing quite possible to do two appends within one second...
        // because so unlikely in normal use we handle this the naive way by waiting a second so our naming scheme doesn't get overelaborated
        $retries = 0 ;

        $now = TimeDate::getInstance()->getNow();
        $new_file = null;
        for($retries = 0; !file_exists($new_file) && $retries < 5; $retries ++)
        {
            $now->modify("+1 second");
            $time = $now->__get('ts');
            $new_file = $this->getFileByTimestamp( $time );
        }
        // now we have a unique filename, copy the file into the history
        copy ( $path, $new_file ) ;
 	    $this->_list [ ] = $time ;

        // finally, trim the number of files we're holding in the history to that specified in the configuration
       // truncate the oldest files, keeping only the most recent $GLOBALS['sugar_config']['studio_max_history'] files (zero=keep them all)
        $to_delete = $this->getCount() - $this->_historyLimit;
        if ($this->_historyLimit != 0 && $to_delete)
        {
            // most recent files are at the end of the list, so we strip out the first count-max_history records
            // can't just use array_shift because it renumbers numeric keys (our timestamp keys) to start from zero...
            for ( $i = 0 ; $i < $to_delete ; $i ++ )
            {
               $timestamp = array_shift( $this->_list ) ;
               if (! unlink ( $this->getFileByTimestamp( $timestamp ) ))
                {
                    $GLOBALS [ 'log' ]->warn ( "History.php: unable to remove history file {$timestamp} from directory {$this->_dirname} - permissions problem?" ) ;                }
            }
        }

        // finally, remove any history preview file that might be lurking around - as soon as we append a new record it supercedes any old preview, so that must be removed (bug 20130)
        if (file_exists($this->_previewFilename))
        {
            $GLOBALS [ 'log' ]->debug( get_class($this)."->append(): removing old history file at {$this->_previewFilename}");
            unlink($this->_previewFilename);
        }

        return $time ;
    }

    /*
     * Restore the historical layout identified by timestamp
     * @param Unix timestamp $timestamp GMT Timestamp of the layout to recover
     * @return GMT Timestamp if successful, null if failure (if the file could not be copied for some reason)
     */
   public function restoreByTimestamp ($timestamp)
    {
         $filename = $this->getFileByTimestamp( $timestamp );
        $GLOBALS [ 'log' ]->debug ( get_class ( $this ) . ": restoring from $filename to {$this->_previewFilename}" ) ;

        if (file_exists ( $filename ))
        {
            $this->savePreview($filename);
            return $timestamp ;
        }
        return null ;
    }

    /**
     * Saves given file for preview
     *
     * @param $filename File name
     */
    public function savePreview($filename)
    {
        copy($filename, $this->_previewFilename);
    }

    /*
     * Undo the restore - revert back to the layout before the restore
     */
    public function undoRestore ()
    {
        if (file_exists ( $this->_previewFilename ))
        {
            unlink($this->_previewFilename);
        }
    }

    /**
     * Returns full path to history file by timestamp. This function returns file path even if file doesn't exist
     * @param  $timestamp
     * @return string
     */
    public function getFileByTimestamp($timestamp)
    {
        return $this->_dirname . DIRECTORY_SEPARATOR . $this->_basename . '_' . $timestamp ;
    }


}
