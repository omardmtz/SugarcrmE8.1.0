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
 * Class to run a job which should remove diagnostic files
 */
abstract class SugarJobRemoveFiles implements RunnableSchedulerJob
{
    /**
     * @var SchedulersJob
     */
    protected $job;

    /**
     * @param SchedulersJob $job
     */
    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }

    /**
     * @param $data
     * @return bool
     */
    public function run($data)
    {
        $this->job->runnable_ran = true;

        $maxLifetime = $this->getMaxLifetime();
        if (!$maxLifetime) {
            $this->job->failJob('File max lifetime is not configured');
            return false;
        }

        $dir = $this->getDirectory();
        if (!file_exists($dir)) {
            $this->job->succeedJob();
            return true;
        }

        $files = $this->getFilesToRemove($dir, $maxLifetime);
        $result = $this->removeFiles($files);

        if ($result) {
            $this->job->succeedJob();
        } else {
            $this->job->failJob();
        }

        return $result;
    }

    /**
     * Finds temporary files to remove
     *
     * @param string $dir
     * @param int $max_lifetime
     * @return Iterator Files to remove
     */
    protected function getFilesToRemove($dir, $max_lifetime)
    {
        $files = array();
        $it = new FilesystemIterator($dir);

        /** @var SplFileInfo $file */
        foreach ($it as $file) {
            if ($file->isFile() && time() - $file->getMTime() >= $max_lifetime) {
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * Removes temporary files
     *
     * @param Traversable|array $files Files to remove
     * @return boolean Whether all files were successfully removed (if any)
     */
    protected function removeFiles($files)
    {
        $result = true;
        foreach ($files as $file) {
            if (!unlink($file)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Returns the directory to remove files from
     *
     * @return string
     */
    abstract protected function getDirectory();

    /**
     * Returns the maximum age of a file before it's removed in seconds
     *
     * @return integer
     */
    abstract protected function getMaxLifetime();
}
