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
 * Adds the tag field to those record views that need it
 */
class SugarUpgradeAddTagFieldToViews extends UpgradeScript
{
    public $order = 9700;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * List of views to apply the tag field to. Starting out as record view only
     * for 7.7, this can be expanded in future releases if need be.
     * @var array
     */
    protected $views = array(
        'record',
    );

    /**
     * Lookup of problem modules that we should skip.
     * @var array
     */
    protected $skipModules = array(
        // FIXME INT-1312: Figure out why, in certain configurations,
        // isModuleBWC("Documents") returns false.
        'Documents' => true,
    );

    public function run()
    {
        // For upgrades from pre-7.7 to 7.7 and above, add tags to the record
        // views
        if (version_compare($this->from_version, '7.7', '<') && version_compare($this->to_version, '7.7', '>=')) {
            $this->addTagFieldToViews();
        }
    }

    /**
     * Adds the tag field to the record views of taggable modules that do not
     * already have the tag field on the view
     */
    protected function addTagFieldToViews()
    {
        foreach ($this->views as $view) {
            // Get the files that need to be worked on
            $files = $this->getViewFiles($view);

            // Handle each view file
            foreach ($files as $file) {
                // Get the data from the file
                $data = $this->getViewFileData($file);

                // If there was file data and the tag field was added...
                if (!empty($data) && $this->addTagToMetadata($data)) {
                    // ... save the changes
                    $this->saveViewFileData($data);
                }
            }
        }
    }

    /**
     * Gets a listing of all $view files for a given client
     * @param string $view The view to get files for
     * @param string $client The client to get the view file for
     * @return array
     */
    protected function getViewFiles($view, $client = 'base')
    {
        // Build a general path to search for
        $path = "modules/*/clients/$client/views/$view/$view.php";

        // This is the base path within deployed modules
        $base = glob($path);

        // These are custom paths for deployed modules
        $cust = glob("custom/$path");

        // Send back the merged result
        return array_merge($base, $cust);
    }

    /**
     * Gets an array of data for a given file path.
     * @param string $file The file path to get information from
     * @return array
     */
    protected function getViewFileData($file)
    {
        // Get some of the particulars for this file
        $parts = explode('/', $file);

        if ($parts[0] === 'custom') {
            $module = $parts[2];
            $client = $parts[4];
            $view = $parts[6];
        } else {
            $module = $parts[1];
            $client = $parts[3];
            $view = $parts[5];
        }

        // Get the view defs now
        $viewdefs = $this->getViewDefsFromFile($file);

        // If the viewdefs are properly formatted, save them onto the return array
        // and be done
        if (isset($viewdefs[$module][$client]['view'][$view])) {
            return array(
                'module' => $module,
                'client' => $client,
                'view' => $view,
                'file' => $file,
                'defs' => $viewdefs[$module][$client]['view'][$view],
            );
        }

        return array();
    }

    /**
     * Gets the viewdefs from a file
     * @param string $file The file path to get metadata from
     * @return array
     */
    protected function getViewDefsFromFile($file)
    {
        $viewdefs = array();
        require $file;
        return $viewdefs;
    }

    /**
     * Adds the tag field to the module metadata
     * @param array &$data The data for a given metadata file
     * @return boolean
     */
    public function addTagToMetadata(&$data) {
        // Only work on the data if there are panels to work with
        if (isset($data['defs']['panels'])) {
            $panels = $data['defs']['panels'];

            // Only handle adding the field if the module is taggabe and doesn't
            // already have the tag field on the view
            if ($this->isTaggable($data['module']) && !$this->hasTagField($panels)) {
                // I know this seems excessive, but good logging saves lives down
                // the road
                $this->log("Tag field is not on the $data[client] $data[view] view for the $data[module] module... adding now.");

                // Get the panels array with the tag field added to it, in the 
                // correct panel and everything
                $panels = $this->addTagToViewMeta($panels);

                $this->log("Tag field added to the $data[client] $data[view] view for the $data[module] module.");

                // Set this back on the $data array for saving later
                $data['defs']['panels'] = $panels;
                return true;
            }
        }

        return false;
    }

    /**
     * Saves the updated view file metadata if there were changes to it
     * @param array $data Updated view metadata
     */
    protected function saveViewFileData(array $data)
    {
        // This saves the changes to the file
        $this->saveChanges($data['defs'], $data['file'], $data['module'], $data['view'], $data['client']);
        $this->log("**** SAVED: $data[client] $data[view] view for the $data[module] module after adding the tag field.");
    }

    /**
     * Adds the tag field to business card section of the record view metadata
     * @param array $panels The existing panels array
     * @return array
     */
    public function addTagToViewMeta(array $panels)
    {
        $panels[1]['fields'][] = array(
            'name' => 'tag',
            'span' => 12,
        );

        return $panels;
    }

    /**
     * Checks if a module is taggable or not
     * @param string $module The module to check
     * @return boolean
     */
    protected function isTaggable($module)
    {
        // FIXME INT-1312: Figure out why, in certain configurations,
        // isModuleBWC("Documents") returns false.
        if (isModuleBWC($module) || isset($this->skipModules[$module])) {
            return false;
        }
        $bean = BeanFactory::newBean($module);
        if (isset($bean->field_defs['tag'])) {
            return true;
        }

        return false;
    }

    /**
     * Determines if the tag field is found in the $panels array
     * @param array $panels Panels metadata
     * @return boolean
     */
    public function hasTagField(array $panels) {
        foreach ($panels as $panel) {
            foreach ($panel['fields'] as $field) {
                if (is_array($field) && !empty($field['name']) && $field['name'] === 'tag') {
                    return true;
                } elseif (is_string($field) && $field === 'tag') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Saves viewdefs to file
     *
     * @param array $defs
     * @param string $file
     * @param string $module
     * @param string $view
     * @param string $client
     */
    protected function saveChanges($defs, $file, $module, $view, $client)
    {
        write_array_to_file("viewdefs['$module']['$client']['view']['$view']", $defs, $file);
    }
}
