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
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * Default SugarCRM Logger
 * @api
 */
class SugarLogger implements LoggerTemplate
{
    /**
     * properties for the SugarLogger
     */
	protected $logfile = 'sugarcrm';
	protected $ext = '.log';
	protected $dateFormat = '%c';
	protected $logSize = '10MB';
	protected $maxLogs = 10;
	protected $filesuffix = "";
    protected $date_suffix = "";
	protected $log_dir = '.';


	/**
	 * used for config screen
	 */
	public static $filename_suffix = array(
        //bug#50265: Added none option for previous version users
        "" => "None",
	    "%m_%Y"    => "Month_Year",
        "%d_%m"    => "Day_Month",
	    "%m_%d_%y" => "Month_Day_Year",
	    );

	/**
	 * Let's us know if we've initialized the logger file
	 */
    protected $initialized = false;

    /**
     * Logger file handle
     */
    protected $fp = false;

    public function __get(
        $key
        )
    {
        return $this->$key;
    }

    /**
     * Used by the diagnostic tools to get SugarLogger log file information
     */
    public function getLogFileNameWithPath()
    {
        return $this->full_log_file;
    }

    /**
     * Used by the diagnostic tools to get SugarLogger log file information
     */
    public function getLogFileName()
    {
        return ltrim($this->full_log_file, "./");
    }

    /**
     * Constructor
     *
     * Reads the config file for logger settings
     */
    public function __construct()
    {
        $config = SugarConfig::getInstance();

        $extConfig = trim($config->get('logger.file.ext', $this->ext));
        if (strcmp($extConfig, '') != 0 ) {
            $this->ext = $extConfig;
        }

        $logfileConfig = trim($config->get('logger.file.name', $this->logfile));
        if (strcmp($logfileConfig, '') != 0) {
            $this->logfile = $logfileConfig;
        }

        $this->dateFormat = trim($config->get('logger.file.dateFormat', $this->dateFormat));

        $logSizeConfig = trim($config->get('logger.file.maxSize', $this->logSize));
        if (strcmp($logSizeConfig, '') != 0) {
            $this->logSize = $logSizeConfig;
        }

        $maxLogsConfig = trim($config->get('logger.file.maxLogs', $this->maxLogs));
        if ($maxLogsConfig > 0) {
            $this->maxLogs = $maxLogsConfig;
        }

        $this->filesuffix = trim($config->get('logger.file.suffix', $this->filesuffix));
        $log_dir = trim($config->get('log_dir' , $this->log_dir));
        $this->log_dir = $log_dir . (empty($log_dir)?'':'/');
        unset($config);
        $this->_doInitialization();
        LoggerManager::setLogger('default','SugarLogger');
	}

	/**
	 * Handles the SugarLogger initialization
	 */
    protected function _doInitialization()
    {

        if( $this->filesuffix && array_key_exists($this->filesuffix, self::$filename_suffix) )
        { //if the global config contains date-format suffix, it will create suffix by parsing datetime
            $this->date_suffix = "_" . date(str_replace("%", "", $this->filesuffix));
        }
        $this->full_log_file = $this->log_dir . $this->logfile . $this->date_suffix . $this->ext;
        $this->initialized = $this->_fileCanBeCreatedAndWrittenTo();
        $this->rollLog();
    }

    /**
	 * Checks to see if the SugarLogger file can be created and written to
	 */
    protected function _fileCanBeCreatedAndWrittenTo()
    {
        $this->_attemptToCreateIfNecessary();
        return file_exists($this->full_log_file) && is_writable($this->full_log_file);
    }

    /**
	 * Creates the SugarLogger file if it doesn't exist
	 */
    protected function _attemptToCreateIfNecessary()
    {
        if (file_exists($this->full_log_file)) {
            return;
        }
        @touch($this->full_log_file);
    }

    /**
     * see LoggerTemplate::log()
     */
	public function log(
	    $level,
	    $message
	    )
	{
        if (!$this->initialized) {
            return;
        }
		//lets get the current user id or default to -none- if it is not set yet
		$userID = (!empty($GLOBALS['current_user']->id))?$GLOBALS['current_user']->id:'-none-';

		//if we haven't opened a file pointer yet let's do that
		if (! $this->fp)$this->fp = fopen ($this->full_log_file , 'a' );


		// change to a string if there is just one entry
	    if ( is_array($message) && count($message) == 1 )
	        $message = array_shift($message);
	    // change to a human-readable array output if it's any other array
	    if ( is_array($message) )
		    $message = print_r($message,true);

		//write out to the file including the time in the dateFormat the process id , the user id , and the log level as well as the message
        $this->write(strftime($this->dateFormat) . ' [' . getmypid () . '][' . $userID . '][' . strtoupper($level) . '] ' . $message . "\n");
    }
    
    /**
     * Writing log to file
     * 
     * @param string $string Message to log
     */
    protected function write($string)
    {
        if ($this->fp) {
            fwrite($this->fp, $string);
        }
    }

	/**
	 * rolls the logger file to start using a new file
	 */
	protected function rollLog(
	    $force = false
	    )
	{
        if (!$this->initialized || empty($this->logSize)) {
            return;
        }
		// bug#50265: Parse the its unit string and get the size properly
        $units = array(
            'b' => 1,                   //Bytes
            'k' => 1024,                //KBytes
            'm' => 1024 * 1024,         //MBytes
            'g' => 1024 * 1024 * 1024,  //GBytes
        );
        if( preg_match('/^\s*([0-9]+\.[0-9]+|\.?[0-9]+)\s*(k|m|g|b)(b?ytes)?/i', $this->logSize, $match) ) {
            $rollAt = ( int ) $match[1] * $units[strtolower($match[2])];
        }
		//check if our log file is greater than that or if we are forcing the log to roll if and only if roll size assigned the value correctly
        if ($force || (!empty($rollAt) && filesize($this->full_log_file) >= $rollAt)) {
			//now lets move the logs starting at the oldest and going to the newest
			for($i = $this->maxLogs - 2; $i > 0; $i --) {
                if (file_exists ( $this->log_dir . $this->logfile . $this->date_suffix . '_'. $i . $this->ext )) {
					$to = $i + 1;
                    $old_name = $this->log_dir . $this->logfile . $this->date_suffix . '_'. $i . $this->ext;
                    $new_name = $this->log_dir . $this->logfile . $this->date_suffix . '_'. $to . $this->ext;
					//nsingh- Bug 22548  Win systems fail if new file name already exists. The fix below checks for that.
					//if/else branch is necessary as suggested by someone on php-doc ( see rename function ).
					sugar_rename($old_name, $new_name);

					//rename ( $this->logfile . $i . $this->ext, $this->logfile . $to . $this->ext );
				}
			}
			//now lets move the current .log file
            sugar_rename ($this->full_log_file, $this->log_dir . $this->logfile . $this->date_suffix . '_1' . $this->ext);

		}
	}

    /**
	 * This is needed to prevent unserialize vulnerability
     */
    public function __wakeup()
    {
        // clean all properties
        foreach(get_object_vars($this) as $k => $v) {
            $this->$k = null;
        }
        throw new Exception("Not a serializable object");
    }

	/**
	 * Destructor
	 *
	 * Closes the SugarLogger file handle
     */
	public function __destruct()
	{
		if ($this->fp)
        {
			fclose($this->fp);
            $this->fp = FALSE;
        }
	}
}
