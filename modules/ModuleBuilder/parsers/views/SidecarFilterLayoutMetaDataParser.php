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


class SidecarFilterLayoutMetaDataParser extends SidecarListLayoutMetaDataParser
{
    /**
     * @var array The list of field types and their filter operators.
     */
    public $operators = array();

    /*
     * Constructor, builds the parent ListLayoutMetaDataParser then adds the
     * panel data to it
     *
     * @param string $moduleName     The name of the module to which this listview belongs
     * @param string $packageName    If not empty, the name of the package to which this listview belongs
     * @param string $client         The client making the request for this parser
     */
    public function __construct($moduleName, $packageName = '', $client = 'base')
    {
        $GLOBALS['log']->debug(get_class($this) . ": __construct()");

        if (empty($client)) {
            throw new \InvalidArgumentException("Client cannot be blank in SidecarFilterLayoutMetaDataParser");
        }

        // Set the client
        $this->client = $client;

        if (empty($packageName)) {
            $this->implementation = new DeployedSidecarFilterImplementation($moduleName, $client);
        } else {
            $this->implementation = new UndeployedSidecarFilterImplementation($moduleName, $packageName, $client);
        }

        $this->_moduleName = $moduleName;
        $this->_viewdefs = $this->implementation->getViewdefs();
        $this->_paneldefs = $this->_viewdefs;
        $this->_fielddefs = $this->implementation->getFieldDefs();

        $this->columns = array('LBL_DEFAULT' => 'getDefaultFields', 'LBL_HIDDEN' => 'getAvailableFields');

        $filterBeanClass = BeanFactory::getBeanClass('Filters');
        $this->operators = $filterBeanClass::getOperators($client);
    }

    /**
     * Return a list of the default fields for a sidecar listview
     * @return array List of default fields as an array
     */
    public function getDefaultFields()
    {
        $defaultFields = array();
        foreach ($this->_viewdefs['fields'] as $name => $details) {
            $def = isset($this->_fielddefs[$name]) ? $this->_fielddefs[$name] : $details;
            if ($this->isValidField($name, $def)) {
                $defaultFields[$name] = $def;
            }
        }
        return $defaultFields;
    }

    /**
     * Checks to see if a field name is in any of the panels
     *
     * @access public
     * @param  string $name The name of the field to check
     * @param  array $src  The source array to scan
     * @return bool
     */
    public function panelHasField($name, $src = null)
    {
        return (!empty($this->_viewdefs['fields'][$name]));
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
        // Make a copy of the field defs array
        $fieldDefs = $this->_fielddefs;

        // to include combo fields which are not in  _fielddefs
        $comboFieldDefs = $this->implementation->getComboFieldDefs();
        $fieldDefs = array_merge($fieldDefs, $comboFieldDefs);

        // Grab our original viewdefs since there are fields on here we'll need
        $viewDefs = $this->implementation->getOriginalViewdefs();

        // The array we will be working on to determine our fields
        $availableFields = array();

        // Inspect the original viewdefs to make sure combo fields are handled correctly
        foreach ($viewDefs['fields'] as $field => $def) {
            if (empty($def)) {
                // Nested if here to prevent logic form always falling into the
                // else that belongs to this ifs parent if
                if (!isset($fieldDefs[$field])) {
                    $fieldDefs[$field] = array();
                }
            } else {
                if (isset($def['dbFields']) && is_array($def['dbFields'])) {
                    // Loop over the dbFields in this filter field and remove them 
                    // from the field defs
                    foreach ($def['dbFields'] as $fieldName) {
                        unset($fieldDefs[$fieldName]);
                    }

                    // Now create a mock field def from this combo field and add
                    // it to the available field list. Adding it here adds it to
                    // the beginning of the list. To add it to the end, change
                    // this from $availableFields to $fieldDefs.
                    $availableFields[$field] = $def;
                }
            }
        }

        // Loop the field defs, checking validity and that the field is not currently
        // on the list of fields
        foreach ($fieldDefs as $key => $def) {
            if ($this->isValidField($key, $def) && !$this->panelHasField($key)) {
                $availableFields[$key] = self::_trimFieldDefs($fieldDefs[$key]);
            }
        }

        // Now loop over the current viewdefs and remove whatever is left from the
        // available field list.
        foreach ($this->_viewdefs['fields'] AS $name => $details) {
            unset($availableFields[$name]);
        }

        return $availableFields;
    }

    /**
     * Removes a field from the layout
     *
     * @param string $fieldName Name of the field to remove
     * @return boolean True if the field was removed; false otherwise
     */
    public function removeField($fieldName)
    {
        if (isset($this->_viewdefs['fields'][$fieldName])) {
            unset($this->_viewdefs['fields'][$fieldName]);
            return true;
        }

        return false;
    }

    /**
     * Add a field to the Filters
     *
     * @param string $fieldName
     * @param array $defs
     * @return bool True if the field was added, false otherwise
     */
    public function addField($fieldName, $defs = array(), $placementIndex = null, $panelIndex = 0)
    {
        if (!$this->panelHasField($fieldName)) {
            $this->_viewdefs['fields'][$fieldName] = $defs;
            return true;
        }

        return false;
    }


    /**
     * Populates the panel defs, and the view defs, from the request
     *
     * @return void
     */
    protected function _populateFromRequest()
    {
        $GLOBALS['log']->debug(
            get_class($this) . "->populateFromRequest() - fielddefs = " . print_r($this->_fielddefs, true)
        );
        // Transfer across any reserved fields, that is, any where studio !== true,
        // which are not editable but must be preserved
        $newPaneldefs = array();
        $newPaneldefIndex = 0;
        $newPanelFieldMonitor = array();

        if (!empty($this->_viewdefs['fields'])) {
            foreach ($this->_viewdefs['fields'] as $fieldName => $field) {
                // Build out the massive conditional structure
                $studio = isset($field['studio']);
                $studioa = $studio && is_array($field['studio']);
                $studioa = $studioa && isset($field['studio']['listview']) &&
                    ($field['studio']['listview'] === false || ($slv = strtolower(
                            $field['studio']['listview']
                        )) == 'false' || $slv == 'required');
                $studion = $studio && !is_array($field['studio']);
                $studion = $studion && ($field['studio'] === false || ($slv = strtolower(
                            $field['studio']
                        )) == 'false' || $slv == 'required');

                $studio = $studio && ($studioa || $studion);
                if (isset($fieldName) && $studio) {
                    $newPaneldefs[$fieldName] = $field;
                }
            }
        }

        $lastGroup = isset($this->columns['LBL_AVAILABLE']) ? 2 : 1;

        $comboFieldDefs = $this->implementation->getComboFieldDefs();

        for ($i = 0; isset($_POST['group_' . $i]) && $i < $lastGroup; $i++) {
            foreach ($_POST['group_' . $i] as $fieldname) {
                $fieldname = strtolower($fieldname);
                //Check if the field was previously on the layout
                if (!empty($this->_viewdefs['fields'][$fieldname])) {
                    $newPaneldefs[$fieldname] = $this->_viewdefs['fields'][$fieldname];
                } elseif ((!empty($comboFieldDefs[$fieldname]) &&
                        isset($comboFieldDefs[$fieldname]['dbFields'])) ||
                    $fieldname === '$favorite'
                ) {
                    // combo fields such as address_street
                    // Or condition is for special field found that should be added too
                    $newPaneldefs[$fieldname] = $comboFieldDefs[$fieldname];
                } else {
                    $newPaneldefs[$fieldname] = array();
                }
            }
        }

        $this->_viewdefs['fields'] = $newPaneldefs;
    }

    /**
     * Sidecar specific method that delegates validity checking to client
     * specific methods if they exists, otherwise passes through to the parent
     * checker.
     *
     * @param string $key The field name.
     * @param array $def The field defs for key.
     * @return bool `true` if valid, `false` otherwise.
     */
    public function isValidField($key, array $def)
    {
        if (parent::isValidField($key, $def)) {
            // Predefined filters are valid 'fields'
            if (!empty($def['predefined_filter'])) {
                return true;
            }
            if (empty($def['type'])) {
                return false;
            }

            if (!empty($this->operators[$def['type']])) {
                return true;
            }
        }

        return false;
    }
}
