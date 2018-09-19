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


class SidecarSubpanelLayoutMetaDataParser extends SidecarListLayoutMetaDataParser
{

    public $invisibleFields = array();

    /*
     * Constructor, builds the parent ListLayoutMetaDataParser then adds the
     * panel data to it
     *
     * @param string $view           The view type, that is, editview, searchview etc
     * @param string $moduleName     The name of the module to which this listview belongs
     * @param string $packageName    If not empty, the name of the package to which this listview belongs
     * @param string $client         The client making the request for this parser
     */
    public function __construct($subpanelName, $moduleName, $packageName = '', $client = 'base')
    {
        $GLOBALS['log']->debug(get_class($this) . ": __construct()");

        if (empty($client)) {
            throw new \InvalidArgumentException("Client cannot be blank in SidecarSubpanelLayoutMetaDataParser");
        }

        if (empty($packageName)) {
            $this->implementation = new DeployedSidecarSubpanelImplementation($subpanelName, $moduleName, $client);
        } else {
            $this->implementation = new UndeployedSidecarSubpanelImplementation($subpanelName, $moduleName, $packageName, $client);
        }

        $this->_viewdefs = $this->implementation->getViewdefs();
        $this->_paneldefs = $this->implementation->getPanelDefs();
        $this->_fielddefs = $this->implementation->getFieldDefs();
        $this->columns = array('LBL_DEFAULT' => 'getDefaultFields', 'LBL_HIDDEN' => 'getAvailableFields');
    }

    /**
     * Clears mobile and portal metadata caches that have been created by the API
     * to allow immediate rendering of changes at the client
     */
    protected function _clearCaches()
    {
        // Leave this empty. The metadata cache clear that needs to be done for
        // this has to be done late in the process or at least well after the 
        // extensions for a subpanel are built.
    }

    /**
     * Return a specific panel if it exists, if it does not exist, `false` will be returned instead
     *
     * @param int $panel What panel are we looking for?
     * @return array|bool
     */
    protected function getPanel($panel = null)
    {
        if (isset($this->_paneldefs[$panel])) {
            return $this->_paneldefs[$panel];
        }

        return false;
    }

    /*
     * Removes a field from the SubPanel Layout
     *
     * @param  string  $fieldName Name of the field to remove
     * @return boolean True if the field was removed; false otherwise
     */
    public function removeField($fieldName)
    {
        $return = false;
        // Start with out current viewdefs
        if (isset($this->_viewdefs['panels'])) {
            // The current panels, should be the same as $this->_paneldefs
            $panels = $this->_viewdefs['panels'];

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
                        $this->_viewdefs['panels'][$panelIndex]['fields'] = $newFields;
                    }
                }
            }
        }

        return $return;
    }

    /** {@inheritDoc} */
    public function isValidField($key, array $def)
    {
        // allow only relationship fields based on the same relationship as the subpanel
        if (!empty($def['rname_link']) &&
            $this->getFieldRelationship($def) !== $this->getSubpanelRelationship()
        ) {
            return false;
        }

        return parent::isValidField($key, $def);
    }

    /**
     * Subpanel doesn't support this.
     * {@inheritDoc}
     */
    protected function isAdditionalField(array $field)
    {
        return false;
    }

    /**
     * Returns the name of the relationship corresponding to the given field
     *
     * @param array $def
     * @return string|null
     */
    protected function getFieldRelationship(array $def)
    {
        if (isset($def['link']) && isset($this->_fielddefs[$def['link']]['relationship'])) {
            return $this->_fielddefs[$def['link']]['relationship'];
        }

        return null;
    }

    /**
     * Returns the name of the relationship which the subpanel is built on
     *
     * @return string|null
     */
    protected function getSubpanelRelationship()
    {
        $linkName = $this->implementation->getLinkName();
        if ($linkName) {
            $moduleName = $this->implementation->getPrimaryModuleName();
            $bean = BeanFactory::newBean($moduleName);
            $def = $bean->getFieldDefinition($linkName);
            if (isset($def['relationship'])) {
                return $def['relationship'];
            }
        }

        return null;
    }
}
