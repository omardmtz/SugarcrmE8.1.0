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

// Check to see if we have already registered our upload stream wrapper
if (!in_array('upload', stream_get_wrappers())) {
    UploadStream::register();
}

require_once 'include/utils/file_utils.php';

/**
 * Class to handle downloading of files. Should eventually replace download.php
 */
class DownloadFile {
    /**
     * Gets a file and returns an HTTP response with the contents of the request file for download
     *
     * @param SugarBean $bean The SugarBean to get the file for
     * @param string $field The field name to get the file for
     * @param boolean $forceDownload force to download the file if true.
     */
    public function getFile(SugarBean $bean, $field, $forceDownload = false) {
        if ($this->validateBeanAndField($bean, $field, 'file') || $this->validateBeanAndField($bean, $field, 'image')) {
            $def = $bean->field_defs[$field];

            if ($def['type'] == 'image') {
                $info = $this->getImageInfo($bean, $field);
            } elseif ($def['type'] == 'file') {
                $info = $this->getFileInfo($bean, $field);

                $sfh = new SugarFieldHandler();
                /* @var $sf SugarFieldFile */
                $sf = $sfh->getSugarField($def['type']);

                //If the requested file is not a supported image type, we should force a download.
                if (!$forceDownload && !in_array($info['content-type'], $sf::$imageFileMimeTypes)) {
                    $forceDownload = true;
                }
            }

            if ($info) {
                $this->outputFile($forceDownload, $info);
            } else {
                // @TODO Localize this exception message
                throw new Exception('File information could not be retrieved for this record');
            }
        }
    }

    /**
     * Sends an HTTP response with the contents of the request file for download
     *
     * @param boolean $forceDownload force to download the file if true.
     * @param array $info Array containing the file details.
     */
    public function outputFile($forceDownload, array $info) {
        header("Pragma: public");
        header("Cache-Control: max-age=1, post-check=0, pre-check=0");

        if (!$forceDownload) {
            header("Content-Type: {$info['content-type']}");
        } else {
            header("Content-Type: application/force-download");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"".$info['name']."\"");
        }
        header("X-Content-Type-Options: nosniff");
        header("Content-Length: " . filesize($info['path']));
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
        set_time_limit(0);
        ob_start();

            readfile($info['path']);
        @ob_end_flush();
    }

    /**
     * Gets the server path to a file named $fileid
     *
     * @param string $fileid The name of the file to get - Can be a path as well
     * @return string
     */
    public function getFilePathFromId($fileid) {
        return 'upload://' . $fileid;
    }

    /**
     * Gets file info for a bean and field type image
     *
     * @param SugarBean $bean The bean to get the info for
     * @param string $field The field name to get the file information for
     * @return array|bool
     */
    public function getImageInfo($bean, $field) {
        if ($this->validateBeanAndField($bean, $field, 'image')) {
            $filename = $bean->{$field};
            $filepath = $this->getFilePathFromId($filename);

            // Quick existence check to make sure we are actually working
            // on a real file
            if (!file_exists($filepath)) {
                return false;
            }

            $filedata = getimagesize($filepath);

            $info = array(
                'content-type' => $filedata['mime'],
                'content-length' => filesize($filepath),
                'name' => $filename,
                'path' => $filepath,
            );
            return $info;
        }
    }

    /**
     * This function makes sure the bean exists, the field exists in the bean and is the expected type
     *
     * @param SugarBean $bean The SugarBean to get the file for
     * @param string $field The field name to get the file for
     * @param string $type the type of the field
     * @return bool
     * @throws Exception
     */
    protected function validateBeanAndField($bean, $field, $type)
    {
        if (!$bean instanceof SugarBean || empty($bean->id) || empty($bean->{$field})) {
            // @TODO Localize this exception message
            throw new Exception('Invalid SugarBean');
            return false;
        }
        if (!isset($bean->field_defs[$field])) {
            // @TODO Localize this exception message
            throw new Exception('Missing field definitions for ' . $field);
            return false;
        }
        if (!isset($bean->field_defs[$field]['type']) || $bean->field_defs[$field]['type'] != $type) {
            return false;
        }
        return true;
    }

    /**
     * Gets file info for a bean and field type file
     *
     * @param SugarBean $bean The bean to get the info for
     * @param string $field The field name to get the file information for
     * @return array|bool
     */
    public function getFileInfo($bean, $field) {
        if ($this->validateBeanAndField($bean, $field, 'file')) {
                    // Default the file id and url
                    $fileid  = $bean->id;
                    $fileurl = '';

                    // Handle special cases, like Documents
                    if (isset($bean->object_name)) {
                        if ($bean->object_name == 'Document') {
                            // Documents store their file information in DocumentRevisions
                            $revision = BeanFactory::retrieveBean('DocumentRevisions', $bean->id);

                            if (!empty($revision)) {
                                $fileid  = $revision->id;
                                $name    = $revision->filename;
                                $fileurl = empty($revision->doc_url) ? '' : $revision->doc_url;
                            } else {
                                // The id is not a revision id, try the actual document revision id
                                $revision = BeanFactory::retrieveBean('DocumentRevisions', $bean->document_revision_id);

                                if (!empty($revision)) {
                                    // Revision will hold the file id AND the file name
                                    $fileid = $revision->id;
                                    $name   = $revision->filename;
                                    $fileurl = empty($revision->doc_url) ? '' : $revision->doc_url;
                                } else {
                                    // Nothing to find
                                    return false;
                                }
                            }
                        }
                        else if ($bean->object_name == 'Note') {
                            $fileid = $bean->getUploadId();
                        }
                    } else {
                        $fileid = $bean->id;
                        $fileurl  = '';
                    }

                    $filepath = $this->getFilePathFromId($fileid);

                    // Quick existence check to make sure we are actually working
                    // on a real file
            if (!file_exists($filepath) && ($bean->doc_type == "Sugar" || empty($bean->doc_type))) {
                return false;
            }

                    if (empty($fileurl) && !empty($bean->doc_url)) {
                        $fileurl = $bean->doc_url;
                    }

                    // Get our filename if we don't have it already
                    if (empty($name)) {
                        $name = $bean->getFileName();
                    }

                    return array(
                        'content-type' => $this->getMimeType($filepath),
                        'content-length' => filesize($filepath),
                        'name' => $name,
                        'uri' => $fileurl,
                        'path' => $filepath,
                        'doc_type' => $bean->doc_type
                    );
        } else {
            return null;
        }
    }

    /**
     * Gets an archive of files and returns an HTTP response with the contents
     * of the request file for download archive.
     *
     * @param array $beans The list of SugarBean(s) to get the archive for
     * @param string $field The field name to get the file for
     * @param string $outputName Output archive name.
     *
     * @throws Exception
     */
    public function getArchive(array $beans, $field, $outputName = '')
    {
        $archive = tempnam(sys_get_temp_dir(), 'sug');

        $files = $this->getFileNamesForArchive($beans, $field);

        if (count($files) == 0) {
            throw new Exception('Files could not be retrieved for this record');
        }

        $zip = new ZipArchive();
        $zip->open($archive, ZipArchive::OVERWRITE);
        foreach ($files as $file => $path) {
            $zip->addFromString($file, file_get_contents($path));
        }
        $zip->close();

        $outputName = trim($outputName);
        if (empty($outputName)) {
            $outputName = 'archive.zip';
        } else if (get_file_extension($outputName) != 'zip') {
            $outputName .= '.zip';
        }

        $this->outputFile(
            true,
            array(
                'content-type' => $this->getMimeType($archive),
                'content-length' => filesize($archive),
                'name' => $outputName,
                'path' => $archive,
            )
        );
    }

    /**
     * Return files name with postfix, if need, and path to it.
     * @param array $beans
     * @param string $field
     * @return array File name and path.
     */
    public function getFileNamesForArchive($beans, $field)
    {
        $aliases = array();
        $result = array();

        foreach ($beans as $bean) {
            if ($this->validateBeanAndField($bean, $field, 'file')
                || $this->validateBeanAndField($bean, $field, 'image')) {

                $info = $this->getFileInfo($bean, $field);
                if ($info) {
                    if (empty($aliases[$info['name']])) {
                        $aliases[$info['name']] = array();
                    }
                    array_push($aliases[$info['name']], $info['path']);
                }
            }
        }

        foreach ($aliases as $fname => $paths) {
            if (count($paths) == 1) {
                $result[$fname] = reset($paths);
            } else {
                $count = 0;
                $fparts = explode('.', $fname);
                $ind = count($fparts) > 1 ? count($fparts) - 2 : 0;
                $tmp = $fparts[$ind];
                foreach ($paths as $path) {
                    $fparts[$ind] .= "_{$count}";
                    $count ++;
                    $result[implode('.', $fparts)] = $path;
                    $fparts[$ind] = $tmp;
                }
            }
        }
        return $result;
    }

    /**
     * Gets the mime type of a file
     *
     * @param string $filename Path to the file
     * @return string|false The string mime type or false if the file does not exist
     */
    public function getMimeType($filename) {
        return get_file_mime_type($filename);
    }

    /**
     * Gets the contents of a file
     *
     * @param string $filename Path to the file
     * @return string
     */
    public function getFileByFilename($file)
    {
        if(!file_exists($file))
        {
            // handle exception elsewhere
            throw new Exception('File could not be retrieved', 'FILE_DOWNLOAD_INCORRECT_DEF_TYPE');
        }

        return file_get_contents($file);

    }
}

/**
 * File downloading for API
 */
class DownloadFileApi extends DownloadFile
{
    /**
     * API object
     * @var ServiceBase
     */
    protected $api;

    public function __construct(ServiceBase $api)
    {
        $this->api = $api;
    }

    /**
     * Sends an HTTP response with the contents of the request file for download
     *
     * @param boolean $forceDownload true if force to download the file
     * @param array $info Array containing the file details.
     * Currently supported:
     * - content-type - content type for the file
     *
     */
    public function outputFile($forceDownload, array $info)
    {
        if(empty($info['path'])) {
            throw new SugarApiException('No file name supplied');
        }

        if (!empty($info['doc_type']) && $info['doc_type'] != "Sugar") {
            $this->api->setHeader("Location", $info['uri']);
            return;
        }

        $this->api->setHeader("Expires", TimeDate::httpTime(time() + 2592000));

        if (!$forceDownload) {
            if(!empty($info['content-type'])) {
                $this->api->setHeader("Content-Type", $info['content-type']);
            } else {
                $this->api->setHeader("Content-Type", "application/octet-stream");
            }
        } else {
            $this->api->setHeader("Content-Type", "application/force-download");
            if (!empty($info['content-type'])) {
                $this->api->setHeader('Content-Type', $info['content-type']);
            } else {
                $this->api->setHeader('Content-Type', 'application/octet-stream');
            }
            if(empty($info['name'])) {
                $info['name'] = pathinfo($info['path'], PATHINFO_BASENAME);
            }
            $this->api->setHeader("Content-Disposition", "attachment; filename=\"".$info['name']."\"");
        }
        $this->api->fileResponse($info['path']);
    }
}
