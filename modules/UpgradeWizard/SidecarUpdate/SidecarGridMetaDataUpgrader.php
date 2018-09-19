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

// This will need to be pathed properly when packaged
require_once 'modules/UpgradeWizard/SidecarUpdate/SidecarAbstractMetaDataUpgrader.php';

class SidecarGridMetaDataUpgrader extends SidecarAbstractMetaDataUpgrader
{
    /**
     * The metadata array key for the panels/data section. Wireless calls them
     * panels, portal call them data.
     *
     * @var array
     */
    protected $panelKeys = array(
        'portaledit'     => 'data',
        'portaldetail'   => 'data',
        'wirelessedit'   => 'panels',
        'wirelessdetail' => 'panels',
    );

    /**
     * Panel names in the new style, for the first two panels
     * 
     * @var array
     */
    protected $panelNames = array(
        array(
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
        ),
        array(
            'name' => 'panel_hidden',
            'label' => 'LBL_RECORD_SHOWMORE'
        ),
    );

    /**
     * Converts the legacy Grid metadata to Sidecar style
     *
     * Because there were additions to the grid metadata files, the upgrader will
     * actually not use the full legacy metadata but only the panel defs. The rest
     * of the metadata will come from the 6.6+ style metadata.
     */
    public function convertLegacyViewDefsToSidecar()
    {
        $client = $this->client == 'wireless' ? 'mobile' : $this->client;
        $this->logUpgradeStatus('Converting ' . $this->client . ' ' . $this->viewtype . ' view defs for ' . $this->module);

        // Leave the original legacy viewdefs in tact
        $defs = $this->legacyViewdefs;

        // Find out which panel key to use based on viewtype and client
        $panelKey = $this->panelKeys[$this->client.$this->viewtype];
        if (isset($defs[$panelKey])) {
            // Get the converted defs
            $fields = $this->handleConversion($defs, $panelKey);

            // If we still don't have new viewdefs then fall back onto the old
            // ones. This shouldn't happen, but we need to make sure we have defs
            $newdefs = $this->loadDefaultMetadata();
            if (empty($newdefs)) {
                $newdefs = $defs;
            }

            // Set the new panel defs from the fields that were just converted
            $paneldefs = array(array('label' => 'LBL_PANEL_DEFAULT', 'fields' => $fields));

            // Kill the data (old defs) and panels (new defs) elements from the defs
            unset($newdefs['data'], $newdefs['panels']);

            // Create, or recreate, the panel defs
            $newdefs['panels'] = $paneldefs;

            // Clean up the module name for saving
            $module = $this->getNormalizedModuleName();
            $this->logUpgradeStatus("Setting new $client:{$this->type} view defs internally for $module");
            // Setup the new defs
            $this->sidecarViewdefs[$module][$client]['view'][$this->viewtype] = $newdefs;
        }
    }

    /**
     * Gets fields from a panel and converts them from legacy format to sidecar
     * format
     * 
     * @param array $panel The legacy panel
     * @param integer $maxcols The maximum number of columns for the layout
     * @param integer $maxspan The maximum number of cells to span for a field
     * @return array
     */
    public function getConvertedPanelDefs($panel, $maxcols, $maxspan = 12)
    {
        $fields = array();
        if (is_array($panel)) {
            foreach ($panel as $rowIndex => $row) {
                // This is a single panel, most likely mobile or portal
                if (is_string($row)) {
                    // This needs to span the maxcols amount
                    $fields[] = array(
                        'name' => $row,
                        'span' => $maxspan,
                    );
                    continue;
                }

                // If this is an array, handle it
                $cols = count($row);
                // Assumption here is that Portal and Wireless will never have
                // more than 2 columns in the old setup
                if ($cols == 1) {
                    // Either a string field name or an instruction
                    if (is_string($row[0])) {
                        if (!$this->isValidField($row[0])) {
                            continue;
                        }
                        if ($maxcols == 1) {
                            $fields[] = $row[0];
                        } else {
                            $fields[] = array(
                                'name' => $row[0],
                                'span' => $maxspan,
                            );
                        }
                    } elseif (is_array($row[0])) {
                        // Some sort of instruction set
                        if (isset($row[0]['name'])) {
                            // Old style field now maps to name
                            $field = $row[0]['name'];
                            if (!$this->isValidField($field)) {
                                continue;
                            }
                            unset($row[0]['name']);
                            $fields[] = array_merge(
                                array('name' => $field),
                                $row[0],
                                $maxcols == 1 ? array() : array('span' => $maxspan)
                            );
                        } else {
                            // Fallback... take it as is
                            $fields[] = $row[0];
                        }
                    }
                } else {
                    // We actually have the necessary col count
                    foreach ($row as $rowFields) {
                        if (is_array($rowFields)) {
                            if (isset($rowFields['name'])) {
                                if (!$this->isValidField($rowFields['name'])) {
                                    continue;
                                }
                                $fields[] = $rowFields['name'];
                            }
                        } else {
                            if (!$this->isValidField($rowFields)) {
                                continue;
                            }
                            $fields[] = $rowFields;
                        }
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Handles the actual conversion of viewdefs. By default this method will 
     * only convert field defs without panel data to support original upgrading
     * from 6.5 -> 6.6 and to support portal and mobile conversion. 
     * 
     * @param array $defs The complete legacy viewdef
     * @param string $panelKey The viewdef key that contains panel data
     * @param boolean $full Flag that tells this method whether to return a 
     *                      single array of fields or a full conversion of defs
     * @return array
     */
    public function handleConversion($defs, $panelKey, $full = false)
    {
        $fields = $panels = array();
        if (isset($defs[$panelKey])) {
            // Necessary for setting the proper field array types
            $maxcols = isset($defs['templateMeta']['maxColumns']) ? intval($defs['templateMeta']['maxColumns']) : 2;

            // Mobile and portal implement one panel only, so this needs to be
            // handled up front. Also, neither portal nor mobile utilize the
            // additional metadata per panel so sending back the field defs here
            // is adequate.
            if (in_array($this->client, array('mobile', 'wireless', 'portal'))) {
                return $this->getConvertedPanelDefs($defs[$panelKey], $maxcols);
            }

            // Loop counter, used to keep track of labels for a panel
            $c = 0;

            foreach ($defs[$panelKey] as $label => $panel) {
                // Get fields from this panel and convert them
                $fields = array_merge($fields, $this->getConvertedPanelDefs($panel, $maxcols));

                // For full conversion of metadata we need to group fields into 
                // their respective panels. This handles that here.
                if ($full) {
                    // Set the hidden flag for handling 'hide' property
                    $hidden = false;

                    // Handle panel naming and labeling
                    $panelNames = $this->getConvertedPanelName($label, $c);
                    $panelName = $panelNames['name'];
                    $panelLabel = $panelNames['label'];

                    // Set the hide property?
                    if ($panelName == 'panel_hidden') {
                        $hidden = true;
                    }

                    // Basic defs that should never change
                    $defs = array(
                        'name' => $panelName,
                        'label' => $panelLabel,
                        'columns' => $maxcols,
                        'labels' => true,
                        'labelsOnTop' => true,
                        'placeholders' => true,
                    );

                    // Handle the 'hide' property
                    if ($hidden) {
                        $defs['hide'] = true;
                    }

                    // Add in the fields array
                    $defs['fields'] = $fields;

                    // Build this panel's metadata
                    $panels[] = $defs;

                    // Reset fields array so they don't stack up inside of panels
                    $fields = array();
                }

                // Increment the counter that handles panel labels
                $c++;
            }
        }

        // This is a full metadata conversion, so send back a complete metadata
        // collection
        if ($full) {
            return array(
                'templateMeta' => isset($defs['templateMeta']) ? $defs['templateMeta'] : array(),
                'panels' => $panels
            );
        }

        // Return the default converted fields array
        return $fields;
    }

    /**
     * Gets a panel name. Used in conversion of old style to new style. Checks
     * whether a panel label ($name) is a custom panel or not and if not, will
     * convert it to Business Card or Show More where appropriate.
     * 
     * @param string $name The name or label of the panel
     * @param integer $index The numeric position of the panel
     * @return array
     */
    protected function getConvertedPanelName($name, $index)
    {
        // Custom panels on Edit/Detail views were named lbl_{type}view_panel#
        // For meetings module name is lbl_meeting_information
        $custom = preg_match('/lbl_(edit|detail)view_panel|lbl_meeting_information/i', $name);

        // If this isn't a custom panel and the index is a known special panel
        // handle it
        if (!$custom && isset($this->panelNames[$index]['name'])) {
            $panelName = $this->panelNames[$index]['name'];
            $panelLabel = $this->panelNames[$index]['label'];
        } else {
            // Else just transform the name and label as appropriate
            $panelName = strtolower($name);
            $panelLabel = strtoupper($name);
        }

        return array('name' => $panelName, 'label' => $panelLabel);
    }
}
