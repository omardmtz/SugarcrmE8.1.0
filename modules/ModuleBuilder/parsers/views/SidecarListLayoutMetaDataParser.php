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

class SidecarListLayoutMetaDataParser extends ListLayoutMetaDataParser
{
    /**
     * Invalid field types for various sidecar clients. Format should be
     * $client => array('type', 'type').
     *
     * @var array
     * @protected
     */
    protected $invalidTypes = array(
        'base' => array('iframe', 'encrypt', 'html', 'currency_id'),
    );

    /**
     * List of allowed views for this parser.
     *
     * This is checked in the constructor and will throw an exception if the
     * requested view is not allowed.
     *
     * @var array
     */
    protected $allowedViews = array(
        MB_SIDECARLISTVIEW,
        MB_SIDECARPOPUPVIEW,
        MB_SIDECARDUPECHECKVIEW,
        MB_WIRELESSLISTVIEW,
        MB_SIDECARQUOTEDATAGROUPLIST,
    );

    /*
     * Constructor, builds the parent ListLayoutMetaDataParser then adds the
     * panel data to it
     *
     * @param string $view        The view type, that is, editview, searchview etc
     * @param string $moduleName  The name of the module to which this listview belongs
     * @param string $packageName If not empty, the name of the package to which this listview belongs
     * @param string $client      The client making the request for this parser
     */
    public function __construct ($view , $moduleName , $packageName = '', $client = '')
    {
        parent::__construct($view, $moduleName, $packageName, $client);
        $this->_paneldefs = $this->implementation->getPanelDefs();
    }

    /**
     * Return a list of the default fields for a sidecar listview
     * @return array List of default fields as an array
     */
    public function getDefaultFields()
    {
        $defaultFields = array();
        foreach ($this->_paneldefs as $def) {
            if (isset($def['fields']) && is_array($def['fields'])) {
                foreach ($def['fields'] as $field) {
                    if (!is_array($field) && !empty($this->_fielddefs[$field])) {
                        $fieldName = $field;
                        $field = self::_trimFieldDefs($this->_fielddefs[$field]);
                        $field['name'] = $fieldName;
                        $field['default'] = true;
                        $field['enabled'] = true;
                    }
                    if (!empty($field['name'])) {
                        if ($this->isDefaultField($field)) {
                            if (isset($this->_fielddefs[$field['name']])) {
                                $defaultFields[$field['name']] = self::_trimFieldDefs(array_merge($this->_fielddefs[$field['name']], $field));
                                if (!empty($field['label'])) {
                                    $defaultFields[$field['name']]['label'] = $field['label'];
                                }
                            } else {
                                $defaultFields[$field['name']] = $field;
                            }
                        }
                    }
                }
            }
        }

        return $defaultFields ;
    }

    /**
     * Gets the list of predefined widths available for the list view columns.
     *
     * @return array The list of widths.
     * @static
     */
    static public function getDefaultWidths() {
        $widths = array(
            'xxsmall',
            'xsmall',
            'small',
            'medium',
            'large',
            'xlarge',
            'xxlarge',
        );
        return $widths;
    }

    /**
     * Detects if the field should be displayed in "Default" list
     *
     * @param array $field Field view definition
     * @return bool
     */
    protected function isDefaultField(array $field)
    {
        return (!isset($field['default']) || $field['default'])
            && (!isset($field['enabled']) || $field['enabled'])
            // for studio we don't know all cases, value can be array or 'visible' etc.
            // because of that we use expected studio not to be false or 'false'.
            // when we'll know all values we should replace that condition with suitable one.
            && (!isset($field['studio']) || ($field['studio'] !== false && $field['studio'] !== 'false')
        );
    }

    /**
     * Gets a list of fields that are currently hidden but can still be added to
     * the available fields list or the default fields list
     *
     * @access public
     * @return array List of additional fields as an array
     */
    public function getAdditionalFields()
    {
        $additionalFields = array();
        foreach ($this->_paneldefs as $def) {
            if (isset($def['fields'])) {
                foreach ($def['fields'] as $field) {
                    if (!is_array($field) && !empty($this->_fielddefs[$field])) {
                        $field = self::_trimFieldDefs($this->_fielddefs[$field]);
                        $field['default'] = true;
                    }
                    if (!empty($field['name'])) {
                        // Bug #25322
                        if (strtolower($field['name']) == 'email_opt_out') {
                            continue;
                        }

                        if ($this->isAdditionalField($field)) {
                            if (isset($this->_fielddefs[$field['name']])) {
                                $additionalFields[$field['name']] = self::_trimFieldDefs($this->_fielddefs[$field['name']]);
                            } else {
                                $additionalFields[$field['name']] = $field;
                            }
                        }
                    }
                }
            }
        }

        return $additionalFields ;
    }

    /**
     * Detects if the field should be displayed in "Available" list
     *
     * @param array $field Field view definition
     * @return bool
     */
    protected function isAdditionalField(array $field)
    {
        return isset($field['default']) && !$field['default']
            && (!isset($field['enabled']) || $field['enabled']);
    }

    /**
     * Gets a list of fields that are available to be added to the default fields
     * list
     *
     * @access public
     * @return array
     */
    public function getAvailableFields()
    {
        $availableFields = array();

        // Select available fields from the field definitions - don't need to worry about checking if ok to include as the Implementation has done that already in its constructor
        foreach ($this->_fielddefs as $key => $def) {
            if ($this->isValidField($key, $def)) {
                if (!$this->panelHasField($key)) {
                    $availableFields[$key] = self::_trimFieldDefs($this->_fielddefs[$key]);
                } elseif (isset($def['default']) && !$def['default'] && !$this->isAdditionalField($def)) {
                    // if the panel has the field, but it's not displayed by default,
                    // add it to the list of available field and keep the effective definition
                    $availableFields[$key] = $def;
                }
            }
        }

        $origPanels = $this->getOriginalPanelDefs();
        foreach ($origPanels as $panel) {
            if (is_array($panel) && isset($panel['fields']) && is_array($panel['fields'])) {
                foreach ($panel['fields'] as $field) {
                    if (!is_array($field) && !empty($this->_fielddefs[$field])) {
                        $field = self::_trimFieldDefs($this->_fielddefs[$field]);
                    }
                    if (isset($field['name']) && !$this->panelHasField($field['name']) || (isset($field['enabled']) && $field['enabled'] == false)) {
                        if (!isset($field['label']) && !isset($field['vname'])) {
                            if (isset($this->_fielddefs[$field['name']]['label'])) {
                                $field['label'] = $this->_fielddefs[$field['name']]['label'];
                            } else {
                                $field['label'] = $this->_fielddefs[$field['name']]['vname'];
                            }
                        }
                        // lets make sure that the field can still be in studio
                        if ($this->isValidField($field['name'], $this->_fielddefs[$field['name']])) {
                            $availableFields[$field['name']] = $field;
                        }
                    }
                }
            }
        }

        return $availableFields;
    }

    /**
     * Gets the original panel defs for this listview
     *
     * @return array
     */
    public function getOriginalPanelDefs()
    {
        $defs = $this->getOriginalViewDefs();
        $viewClient = $this->implementation->getViewClient();
        $viewType = empty($viewClient) ? 'base' : $viewClient;
        if (isset($defs[$viewType]) && is_array($defs[$viewType]) && isset($defs[$viewType]['view']) && is_array($defs[$viewType]['view'])) {
            $index = key($defs[$viewType]['view']);
            if (isset($defs[$viewType]['view'][$index]['panels'])) {
                $defs = $defs[$viewType]['view'][$index]['panels'];
            }
        }

        return $defs;
    }

    /**
     * Checks to see if a field name is in any of the panels
     *
     * @access public
     * @param  string $name The name of the field to check
     * @param  array  $src  The source array to scan
     * @return bool
     */
    public function panelHasField($name, $src = null)
    {
        $field = $this->panelGetField($name, $src);

        return !empty($field);
    }

    /**
     * Scans the panels/fields to see if the panel list already has a field and,
     * if it does, returns that field with its position in the panels list
     *
     * @access public
     * @param  string $name The name of the field to check
     * @param  array  $src  The array to scan for the field
     * @return array
     */
    public function panelGetField($name, $src = null)
    {
        // If there was a passed source, use that for the panel search
        $panels = $src !== null && is_array($src) ? $src : $this->_paneldefs;
        foreach ($panels as $panelix => $def) {
            if (isset($def['fields']) && is_array($def['fields'])) {
                foreach ($def['fields'] as $fieldix => $field) {
                    if (!is_array($field) && !empty($this->_fielddefs[$field])) {
                        $field = self::_trimFieldDefs($this->_fielddefs[$field]);
                    }
                    if (isset($field['name']) && $field['name'] == $name) {
                        return array('field' => $field, 'panelix' => $panelix, 'fieldix' => $fieldix);
                    }
                }
            }
        }

        return array();
    }

    /**
     * Sidecar specific method that delegates validity checking to client specific
     * methods if they exists, otherwise passes through to the parent checker
     *
     * @param  string $key The field name
     * @param  array  $def The field defs for key
     * @return bool
     */
    public function isValidField($key, array $def)
    {
        if (!empty($this->client)) {
            $method = 'isValidField' . ucfirst(strtolower($this->client));
            if (method_exists($this, $method)) {
                return $this->$method($key, $def);
            }
        }

        return parent::isValidField($key, $def);
    }

    /**
     * Validates portal only fields. Runs the field through a prelimiary check
     * of type before passing the field on to the parent validator.
     *
     * @param  string $key The field name to check for
     * @param  array  $def The field defs for the key
     * @return bool
     */
    public function isValidFieldPortal($key, array $def)
    {
        if (isset($this->invalidTypes['portal'])) {
            if (!isset($def['type']) || in_array($def['type'], $this->invalidTypes['portal'])) {
                return false;
            }
        }

        return parent::isValidField($key, $def);
    }

    /**
     * Populates the panel defs, and the view defs, from the request
     *
     * @return void
     */
    protected function _populateFromRequest()
    {
        $GLOBALS['log']->debug(get_class($this) . "->populateFromRequest() - fielddefs = ".print_r($this->_fielddefs, true));
        // Transfer across any reserved fields, that is, any where studio !== true,
        // which are not editable but must be preserved
        $newPaneldefs = array();
        $newPaneldefIndex = 0;
        $newPanelFieldMonitor = array();

        foreach ($this->_paneldefs as $index => $panel) {
            if (isset($panel['fields']) && is_array($panel['fields'])) {
                foreach ($panel['fields'] as $field) {
                    // Build out the massive conditional structure
                    $studio  = isset($field['studio']);
                    $studioa = $studio && is_array($field['studio']);
                    $studioa = $studioa && isset($field['studio']['listview']) &&
                               ($field['studio']['listview'] === false || ($slv = strtolower($field['studio']['listview'])) == 'false' || $slv == 'required');
                    $studion = $studio && !is_array($field['studio']);
                    $studion = $studion && ($field['studio'] === false || ($slv = strtolower($field['studio'])) == 'false' || $slv == 'required');

                    $studio  = $studio && ($studioa || $studion);
                    if (isset($field['name']) && $studio) {
                        $newPaneldefs[$newPaneldefIndex++] = $field;
                        $newPanelFieldMonitor[$field['name']] = true;
                    }
                }
            }
        }
        // only take items from group_0 for searchviews (basic_search or
        // advanced_search) and subpanels (which both are missing the Available
        // column) - take group_0, _1 and _2 for all other list views
        $lastGroup = isset($this->columns['LBL_AVAILABLE']) ? 2 : 1;

        for ($i = 0; isset($_POST['group_' . $i]) && $i < $lastGroup; $i ++) {
            foreach ($_POST['group_' . $i] as $fieldname) {
                $fieldname = strtolower($fieldname);
                //Check if the field was previously on the layout
                if ($f = $this->panelGetField($fieldname)) {
                    $newPaneldefs[$newPaneldefIndex] = $f['field'];
                } elseif ($f = $this->panelGetField($fieldname, $this->getOriginalPanelDefs())) { // Check if the original view def contained it
                    $newPaneldefs[$newPaneldefIndex] = $f['field'];
                } else {
                    // Create a definition from the fielddefs
                    // if we don't have a valid fieldname then just ignore it and move on...
                    if (!isset($this->_fielddefs[$fieldname])) {
                        continue;
                    }

                    // Get the initial def structure
                    $def = $this->_trimFieldDefs($this->_fielddefs[$fieldname]);

                    // Set the basic default properties of the field def
                    $panelfield = $this->setDefDefaults($fieldname, $def);

                    // set the related fields
                    $panelfield = $this->setDefRelatedFields($def, $panelfield);

                    // Handle readonly flags
                    $panelfield = $this->setDefReadonly($def, $panelfield);

                    // Handle link setup
                    $panelfield = $this->setDefLink($fieldname, $panelfield);

                    // Handle the sortable flag
                    $panelfield = $this->setDefSortable($fieldname, $panelfield);

                    // Handle currency formatting
                    $panelfield = $this->setDefCurrencyFormat($fieldname, $panelfield);

                    $newPaneldefs[$newPaneldefIndex] = $panelfield;
                }

                if (isset($_REQUEST[strtolower($fieldname) . 'width'])) {
                    $width = substr($_REQUEST[$fieldname . 'width'], 6);
                    $isPercentage = strrpos($width, '%') !== false;
                    $intWidth = intval($width);
                    $isDefaultWidth = in_array($width, self::getDefaultWidths());

                    if (!$isPercentage && ($intWidth > 0 || $isDefaultWidth)) {
                        $newPaneldefs[$newPaneldefIndex]['width'] = $width;
                    } else {
                        unset($newPaneldefs[$newPaneldefIndex]['width']);
                    }
                } elseif (($def = $this->panelGetField($fieldname)) && isset($def['field']['width'])) {
                    $newPaneldefs[$newPaneldefIndex]['width'] = $def['field']['width'];
                } else {
                    unset($newPaneldefs[$newPaneldefIndex]['width']);
                }

                // Set the default flag to make it a default field
                $newPaneldefs[$newPaneldefIndex]['default'] = ($i == 0);

                // Handle enabling the field (either first column or second column
                if ($i < 2 && empty($newPaneldefs[$newPaneldefIndex]['enabled'])) {
                    $newPaneldefs[$newPaneldefIndex]['enabled'] = true;
                }

                $newPaneldefIndex++;
            }
        }

        // Add in the non named field meta
        foreach ($this->_paneldefs as $panel) {
            if (isset($panel['fields']) && is_array($panel['fields'])) {
                foreach ($panel['fields'] as $field) {
                    if (!isset($field['name'])) {
                        $newPaneldefs[$newPaneldefIndex++] = $field;
                    }
                }
            }
        }

        // We need to add panels back into the viewdefs at the point where we got them
        $panelDefsPath = $this->implementation->getPanelDefsPath();
        $stack = &$this->_viewdefs;
        foreach ($panelDefsPath as $path) {
            if (isset($stack[$path])) {
                $stack = &$stack[$path];
            }
        }
        if (isset($stack['panels'])) {
            $stack['panels'][0]['fields'] = $newPaneldefs;
        }

        $this->_paneldefs[0]['fields'] = $newPaneldefs;
    }


    /**
     * Add a field to a panel
     *
     * @param string $field       The Field we want to add
     * @param null|int $placementIndex       Where we want to add the field to the panel, if null add to end
     * @param int $panel                    The panel we want to use
     * @throws Exception
     */
    public function addField($field, $additionalDefs = array(), $placementIndex = null, $panelIndex = 0)
    {
        $panel = $this->getPanel($panelIndex);

        if ($panel === false) {
            throw new Exception('Invalid Panel');
        }
        $fieldCount = count($panel['fields']);
        $fields = $panel['fields'];

        // we just have a field name, make it into an array
        $field = $this->generateFieldDef($field);

        if (!empty($additionalDefs)) {
            $field = array_merge($field, $additionalDefs);
        }

        if (is_null($placementIndex)) {
            array_push($fields, $field);
        } elseif ($placementIndex === 0) {
            array_unshift($fields, $field);
        } else {
            $fields_new = array_slice($fields, 0, $placementIndex);
            array_push($fields_new, $field);
            array_push($fields_new, array_slice($fields, $placementIndex, $fieldCount - 1));

            $fields = $fields_new;
        }

        $this->setPanelFields($fields);
    }

    /**
     * @param null|integer $panel           Get a specific panel
     * @return array|bool                   Return all the panels or a specific panel if $panel is set and exist,
     *                                      If no panels are found or the panel is empty, return boolean false
     */
    protected function getPanel($panel = null)
    {
        // Start with out current viewdefs
        if (isset($this->_viewdefs[$this->client]['view'])) {
            // list, edit or detail
            $type = key($this->_viewdefs[$this->client]['view']);

            // The current panels, should be the same as $this->_paneldefs
            $panels = $this->_viewdefs[$this->client]['view'][$type]['panels'];

            if (empty($panels) || !is_array($panels)) {
                return false;
            }

            if (!is_null($panel) && is_integer($panel) && isset($panels[$panel])) {
                return $panels[$panel];
            }

            return $panels;
        }

        return false;
    }

    /**
     * @param string|array $fieldName
     * @return array
     * @throws Exception
     */
    protected function generateFieldDef($fieldName)
    {
        // Get the initial def structure
        $def = $this->_trimFieldDefs($this->_fielddefs[$fieldName]);

        // Set the basic default properties of the field def
        $fieldDef = $this->setDefDefaults($fieldName, $def, true);

        // set the related fields
        $fieldDef = $this->setDefRelatedFields($def, $fieldDef);

        // set the alignment of column, if one is setup
        $fieldDef = $this->setDefAlign($fieldName, $fieldDef);

        // Set the link property
        $fieldDef = $this->setDefLink($fieldName, $fieldDef);

        // Handle sortable flag setting
        $fieldDef = $this->setDefSortable($fieldName, $fieldDef);

        // Handle currency format
        $fieldDef = $this->setDefCurrencyFormat($fieldName, $fieldDef);

        return $fieldDef;
    }

    /**
     * Empty out the current Panel Fields so we start with a clean slate
     */
    public function resetPanelFields()
    {
        $this->setPanelFields();
    }

    /**
     * Set the Fields array on the PanelDefs and ViewDefs
     *
     * @param array $fieldDefs
     */
    public function setPanelFields($fieldDefs = array())
    {
        $panelDefsPath = $this->implementation->getPanelDefsPath();
        $stack = & $this->_viewdefs;
        foreach ($panelDefsPath as $path) {
            if (isset($stack[$path])) {
                $stack = & $stack[$path];
            }
        }
        if (isset($stack['panels'])) {
            $stack['panels'][0]['fields'] = $fieldDefs;
        }

        $this->_paneldefs[0]['fields'] = $fieldDefs;
    }


    /*
     * Removes a field from the layout
     *
     * @param  string  $fieldName Name of the field to remove
     * @return boolean True if the field was removed; false otherwise
     */
    public function removeField($fieldName)
    {
        $return = false;
        // Start with out current viewdefs
        if (isset($this->_viewdefs[$this->client]['view'])) {
            // list, edit or detail
            $type = key($this->_viewdefs[$this->client]['view']);

            // The current panels, should be the same as $this->_paneldefs
            $panels = $this->_viewdefs[$this->client]['view'][$type]['panels'];

            if (!empty($panels) && is_array($panels)) {
                foreach ($panels as $panelIndex => $def) {
                    if (isset($def['fields']) && is_array($def['fields'])) {
                        $newFields = array();
                        foreach ($def['fields'] as $fieldIndex => $field) {
                            if (!empty($field['name']) && $field['name'] == $fieldName) {
                                $return = true;
                                continue;
                            }

                            $newFields[] = $field;
                        }

                        // Reset the panel defs for now
                        $this->_paneldefs[$panelIndex]['fields'] = $newFields;

                        // Now handle the change in the viewdefs for saving
                        $this->_viewdefs[$this->client]['view'][$type]['panels'][$panelIndex]['fields'] = $newFields;
                    }
                }
            }
        }

        return $return;
    }

    /**
     * Clears mobile and portal metadata caches that have been created by the API
     * to allow immediate rendering of changes at the client
     */
    protected function _clearCaches()
    {
        if ($this->implementation->isDeployed()) {
            // Installing and trying to refresh the modules cache is bad juju
            if (!empty($GLOBALS['installing'])) {
                // Use the clear NOW method so that if there is a cache it is wiped
                MetaDataManager::clearAPICache(true, true);
            } else {
                MetaDataManager::refreshModulesCache(array($this->_moduleName));
            }
            parent::_clearCaches();
        }
    }

    /**
     * Sets the currency_format property of the fielddef
     *
     * @param string $fieldName  The name of the field being worked on
     * @param array $fieldDef The current fielddef collection for a field
     * @param bool $addDefault Flag that determines whether the default property is added
     * @return array The modified fielddef collection
     */
    public function setDefDefaults($fieldName, $fieldDef, $addDefault = false)
    {
        // Set the label
        $label = isset($fieldDef['label']) ? $fieldDef['label'] : '';
        if (empty($label) && isset($fieldDef['name'])) {
            $label = isset($fieldDef['vname']) ? $fieldDef['vname'] : $fieldDef['name'];
        }

        // Generate the basics of all initial def defaults
        $return = array(
            'name' => $fieldName,
            'label' => $label,
            'enabled' => true,
        );

        if ($addDefault) {
            $return['default'] = true;
        }

        return $return;
    }

    /**
     * Sets the id and link properties of the fielddef
     *
     * @param string $fieldName  The name of the field being worked on
     * @param array $fieldDef The current fielddef collection for a field
     * @return array The modified fielddef collection
     */
    public function setDefLink($fieldName, $fieldDef)
    {
        if (isset($this->_fielddefs[$fieldName]['type'])) {
            if (($this->_fielddefs[$fieldName]['type'] == 'relate' ||
                // fixing bug #25640: Value of "Relate" custom field is not displayed as a link in list view
                // we should set additional params such as 'link' and 'id' to be stored in custom listviewdefs.php
                $this->_fielddefs[$fieldName]['type'] == 'parent')
            ) {
                if (isset($this->_fielddefs[$fieldName]['id_name'])) {
                    $fieldDef['id'] = strtoupper($this->_fielddefs[$fieldName]['id_name']);
                }
                $fieldDef['link'] = true;
            } else if ($this->_fielddefs[$fieldName]['type'] == 'name') {
                $fieldDef['link'] = true;
            }
        }

        return $fieldDef;
    }

    /**
     * Sets the sortable property of the fielddef
     * Also see SidecarPortalListLayoutMetaDataParser::setDefSortable() for special handling on portal list view.
     *
     * @param string $fieldName  The name of the field being worked on
     * @param array $fieldDef The current fielddef collection for a field
     * @return array The modified fielddef collection
     */
    public function setDefSortable($fieldName, $fieldDef)
    {
        // sorting fields of certain types will cause database engine problems
        $noSortByType = isset($this->_fielddefs[$fieldName]['type']) && isset($this->nonSortableTypes[$this->_fielddefs[$fieldName]['type']]);
        $noSortDBType = isset($this->_fielddefs[$fieldName]['dbType']) && $this->_fielddefs[$fieldName]['dbType'] == 'id';
        $sortable = (isset($this->_fielddefs[$fieldName]['sortable'])) ? isTruthy($this->_fielddefs[$fieldName]['sortable']) : false;
        $relateSortable = false;
        if (isset($this->_fielddefs[$fieldName]['type']) && $this->_fielddefs[$fieldName]['type'] === 'relate') {
            $hasSortOn = isset($this->_fielddefs[$fieldName]['sort_on']) && is_array($this->_fielddefs[$fieldName]['sort_on']);
            $relateSortable = !($hasSortOn);
        }
        if ($noSortByType || $noSortDBType || $relateSortable) {
            $fieldDef['sortable'] = $sortable;
        }

        return $fieldDef;
    }

    /**
     * Sets the currency_format property of the fielddef
     *
     * @param string $fieldName  The name of the field being worked on
     * @param array $fieldDef The current fielddef collection for a field
     * @return array The modified fielddef collection
     */
    public function setDefCurrencyFormat($fieldName, $fieldDef)
    {
        // Bug 23728 - Make adding a currency type field default to setting the 'currency_format' to true
        if (isset($this->_fielddefs[$fieldName]['type']) && $this->_fielddefs[$fieldName]['type'] == 'currency') {
            $fieldDef['currency_format'] = true;
        }

        return $fieldDef;
    }

    /**
     * Sets the align property of the fielddef
     *
     * @param string $fieldName  The name of the field being worked on
     * @param array $fieldDef The current fielddef collection for a field
     * @return array The modified fielddef collection
     */
    public function setDefAlign($fieldName, $fieldDef)
    {
        if (isset($this->_fielddefs[$fieldName]['align']) && !empty($this->_fielddefs[$fieldName]['align'])) {
            $fieldDef['align'] = $this->_fielddefs[$fieldName]['align'];
        }

        return $fieldDef;
    }

    /**
     * Sets the readonly property of a fielddef
     *
     * @param array $rawDef   The raw field def from an initial def fetch
     * @param array $fieldDef The current fielddef collection for a field
     * @return array The modified fielddef collection
     */
    public function setDefReadonly($rawDef, $fieldDef)
    {
        if (isset($rawDef['readonly'])) {
            $fieldDef['readonly'] = $rawDef['readonly'];
        }

        return $fieldDef;
    }

    /**
     * Sets the relate_fields property of a fielddef
     *
     * @param array $rawDef   The raw field def from an initial def fetch
     * @param array $fieldDef The current fielddef collection for a field
     * @return array The modified fielddef collection
     */
    public function setDefRelatedFields($rawDef, $fieldDef)
    {
        if (isset($rawDef['related_fields']) && !empty($rawDef['related_fields'])) {
            $fieldDef['related_fields'] = $rawDef['related_fields'];
        }

        return $fieldDef;
    }
}
