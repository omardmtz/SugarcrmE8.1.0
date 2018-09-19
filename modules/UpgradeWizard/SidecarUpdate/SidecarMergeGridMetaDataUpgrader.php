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

require_once 'modules/UpgradeWizard/SidecarUpdate/SidecarGridMetaDataUpgrader.php';
require_once 'modules/ModuleBuilder/parsers/ParserFactory.php';

class SidecarMergeGridMetaDataUpgrader extends SidecarGridMetaDataUpgrader
{

    /**
     * Composite views
     * sugar7view => [ 'sugar6view1' => ['metadatadefs1', 'view1'], 'sugar6view2' => ['metadatadefs2', 'view2'] ]
     * @var array
     */
    protected $mergeViews = array(
        MB_RECORDVIEW => array(
            'detail' => array('detailviewdefs', MB_DETAILVIEW),
            'edit' => array('editviewdefs', MB_EDITVIEW),
        ),
        MB_PORTALRECORDVIEW => array(
            'detail' => array('detailviewdefs', MB_DETAILVIEW),
            'edit' => array('editviewdefs', MB_EDITVIEW),
        ),
    );

    protected $mergeViewsSidecar = array(
        MB_PORTALRECORDVIEW => array(
            'detail' => array('detail', MB_PORTALDETAILVIEW),
            'edit' => array('edit', MB_PORTALEDITVIEW),
        ),
    );

    /**
     * Panels where fields from each view are placed
     * @var array
     */
    protected $viewPanels = array(
        MB_RECORDVIEW => array(
            MB_DETAILVIEW => 'LBL_RECORD_BODY',
            MB_EDITVIEW => 'LBL_RECORD_SHOWMORE',
        ),
        MB_PORTALRECORDVIEW => array(
            MB_PORTALDETAILVIEW => 'LBL_RECORD_BODY',
            MB_PORTALEDITVIEW => 'LBL_RECORD_BODY',
            MB_DETAILVIEW => 'LBL_RECORD_BODY',
            MB_EDITVIEW => 'LBL_RECORD_BODY',
        ),
    );

    /**
     * List of upgraded dir to prevent double upgrades
     */
    protected static $upgraded = array();

    /**
     * Fields that need to be removed entirely as they no longer have context in
     * Sugar7+. This is a hash of $fieldname => 1 values.
     *
     * @var array
     */
    protected $removeFields = array(
        // currency_id is now set with the currency field
        'currency_id' => 1,
        // This is now part of a combination field
        'portal_password1' => 1,
    );

    /**
     * Field defs for address type fieldset fields that were previously handled
     * through templating.
     *
     * @var array
     */
    protected $addressFields = array(
        'shipping_address_street' => array(
            'name' => 'shipping_address',
            'type' => 'fieldset',
            'css_class' => 'address',
            'label' => 'LBL_SHIPPING_ADDRESS',
            'fields' => array(
                array(
                    'name' => 'shipping_address_street',
                    'css_class' => 'address_street',
                    'placeholder' => 'LBL_SHIPPING_ADDRESS_STREET',
                ),
                array(
                    'name' => 'shipping_address_city',
                    'css_class' => 'address_city',
                    'placeholder' => 'LBL_SHIPPING_ADDRESS_CITY',
                ),
                array(
                    'name' => 'shipping_address_state',
                    'css_class' => 'address_state',
                    'placeholder' => 'LBL_SHIPPING_ADDRESS_STATE',
                ),
                array(
                    'name' => 'shipping_address_postalcode',
                    'css_class' => 'address_zip',
                    'placeholder' => 'LBL_SHIPPING_ADDRESS_POSTALCODE',
                ),
                array(
                    'name' => 'shipping_address_country',
                    'css_class' => 'address_country',
                    'placeholder' => 'LBL_SHIPPING_ADDRESS_COUNTRY',
                ),
                array(
                    'name' => 'copy',
                    'label' => 'NTC_COPY_BILLING_ADDRESS',
                    'type' => 'copy',
                    'mapping' => array(
                        'billing_address_street' => 'shipping_address_street',
                        'billing_address_city' => 'shipping_address_city',
                        'billing_address_state' => 'shipping_address_state',
                        'billing_address_postalcode' => 'shipping_address_postalcode',
                        'billing_address_country' => 'shipping_address_country',
                    ),
                ),
            ),
        ),
        'billing_address_street' => array(
            'name' => 'billing_address',
            'type' => 'fieldset',
            'css_class' => 'address',
            'label' => 'LBL_BILLING_ADDRESS',
            'fields' => array(
                array(
                    'name' => 'billing_address_street',
                    'css_class' => 'address_street',
                    'placeholder' => 'LBL_BILLING_ADDRESS_STREET',
                ),
                array(
                    'name' => 'billing_address_city',
                    'css_class' => 'address_city',
                    'placeholder' => 'LBL_BILLING_ADDRESS_CITY',
                ),
                array(
                    'name' => 'billing_address_state',
                    'css_class' => 'address_state',
                    'placeholder' => 'LBL_BILLING_ADDRESS_STATE',
                ),
                array(
                    'name' => 'billing_address_postalcode',
                    'css_class' => 'address_zip',
                    'placeholder' => 'LBL_BILLING_ADDRESS_POSTALCODE',
                ),
                array(
                    'name' => 'billing_address_country',
                    'css_class' => 'address_country',
                    'placeholder' => 'LBL_BILLING_ADDRESS_COUNTRY',
                ),
            ),
        ),
        'primary_address_street' => array(
            'name' => 'primary_address',
            'type' => 'fieldset',
            'css_class' => 'address',
            'label' => 'LBL_PRIMARY_ADDRESS',
            'fields' => array(
                array(
                    'name' => 'primary_address_street',
                    'css_class' => 'address_street',
                    'placeholder' => 'LBL_PRIMARY_ADDRESS_STREET',
                ),
                array(
                    'name' => 'primary_address_city',
                    'css_class' => 'address_city',
                    'placeholder' => 'LBL_PRIMARY_ADDRESS_CITY',
                ),
                array(
                    'name' => 'primary_address_state',
                    'css_class' => 'address_state',
                    'placeholder' => 'LBL_PRIMARY_ADDRESS_STATE',
                ),
                array(
                    'name' => 'primary_address_postalcode',
                    'css_class' => 'address_zip',
                    'placeholder' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
                ),
                array(
                    'name' => 'primary_address_country',
                    'css_class' => 'address_country',
                    'placeholder' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
                ),
            ),
        ),
        'alt_address_street' => array(
            'name' => 'alt_address',
            'type' => 'fieldset',
            'css_class' => 'address',
            'label' => 'LBL_ALT_ADDRESS',
            'fields' => array(
                array(
                    'name' => 'alt_address_street',
                    'css_class' => 'address_street',
                    'placeholder' => 'LBL_ALT_ADDRESS_STREET',
                ),
                array(
                    'name' => 'alt_address_city',
                    'css_class' => 'address_city',
                    'placeholder' => 'LBL_ALT_ADDRESS_CITY',
                ),
                array(
                    'name' => 'alt_address_state',
                    'css_class' => 'address_state',
                    'placeholder' => 'LBL_ALT_ADDRESS_STATE',
                ),
                array(
                    'name' => 'alt_address_postalcode',
                    'css_class' => 'address_zip',
                    'placeholder' => 'LBL_ALT_ADDRESS_POSTALCODE',
                ),
                array(
                    'name' => 'alt_address_country',
                    'css_class' => 'address_country',
                    'placeholder' => 'LBL_ALT_ADDRESS_COUNTRY',
                ),
                array(
                    'name' => 'copy',
                    'label' => 'NTC_COPY_PRIMARY_ADDRESS',
                    'type' => 'copy',
                    'mapping' => array(
                        'primary_address_street' => 'alt_address_street',
                        'primary_address_city' => 'alt_address_city',
                        'primary_address_state' => 'alt_address_state',
                        'primary_address_postalcode' => 'alt_address_postalcode',
                        'primary_address_country' => 'alt_address_country',
                    ),
                ),
            ),
        ),
    );

    /**
     * Settings for fields we know how to handle
     */
    protected $knownFields = array(
        "date_entered" => array(
            'name' => 'date_entered_by',
            'readonly' => true,
            'type' => 'fieldset',
            'label' => 'LBL_DATE_ENTERED',
            'fields' => array(
                array(
                    'name' => 'date_entered',
                ),
                array(
                    'type' => 'label',
                    'default_value' => 'LBL_BY',
                ),
                array(
                    'name' => 'created_by_name',
                ),
            ),
        ),
        "date_modified" => array(
            'name' => 'date_modified_by',
            'readonly' => true,
            'type' => 'fieldset',
            'label' => 'LBL_DATE_MODIFIED',
            'fields' => array(
                array(
                    'name' => 'date_modified',
                ),
                array(
                    'type' => 'label',
                    'default_value' => 'LBL_BY',
                ),
                array(
                    'name' => 'modified_by_name',
                ),
            ),
        ),
    );

    /**
     * List of acceptable templateMeta properties for new metadata
     *
     * @var array
     */
    protected $templateMetaProps = array(
        'useTabs' => 1,
        'maxColumns' => 1,
    );

    /**
     * List of invalid label regular expressions. Needed for cases in which some
     * labels should NOT be allowed to be carried over from past incarnations of
     * sugar. Like Products.
     *
     * @var array
     */
    protected $invalidLabelRegex = array(
        // Products module, because they liked Smarty code as labels. WUT?
        '/\{\$MOD.(.*)|strip_semicolon/',
    );

    protected function getOriginalFile($filepath)
    {
        $files = explode("/", $filepath);
        // drop prefixes like custom/
        while(!empty($files) && $files[0] != 'modules') {
            array_shift($files);
        }
        if(empty($files)) {
            return $filepath;
        }

        if($this->client == 'portal' && !$this->sidecar) {
            // old portal view paths originate from porta/ and will look like
            // portal/modules/MODULE/metadata/detailviewdefs.php
            array_unshift($files, 'portal');
        }
        return join("/", $files);
    }

    /**
     * Get data's main directory
     * @return string
     */
    protected function getDir()
    {
        if($this->sidecar) {
            // For sidecar it's path/views/edit/edit.php
            return dirname(dirname($this->fullpath));
        } else {
            // For sugar6 it's path/metadata/editviewdefs.php
            return dirname($this->fullpath);
        }
    }

    /**
     * Do not upgrade same directory twice
     * @see SidecarAbstractMetaDataUpgrader::upgradeCheck()
     */
    public function upgradeCheck()
    {
        $dirname = $this->getDir();
        if(!empty(self::$upgraded[$this->viewtype][$dirname])) {
            // we already did this path for this viewtype
            $this->logUpgradeStatus("Already upgraded $dirname {$this->viewtype}");
            return false;
        } else {
            self::$upgraded[$this->viewtype][$dirname] = true;
        }
        return true;
    }

    /**
     * Sets the necessary legacy field defs for use in converting
     */
    public function setLegacyViewdefs()
    {
        $views = $this->sidecar ? $this->mergeViewsSidecar : $this->mergeViews;
        if(empty($views[$this->viewtype])) {
            $this->logUpgradeStatus("Did not find merge views for {$this->viewtype}");
            return;
        }

        $dirname = $this->getDir();

        $foundCustom = false;
        // Load all views for this combined view
        foreach($views[$this->viewtype] as $view => $data) {
            list($file, $lViewtype) = $data;

            if ($this->sidecar) {
                $customFilePath = "$dirname/$file/$file.php";
            } else {
                $customFilePath = "$dirname/$file.php";
            }

            // If this is a history file, add the timestamp back since we need 
            // that. This should only come into play on history upgrades
            if ($this->timestamp) {
                $customFilePath .= '_' . $this->timestamp;
            }

            $originalFilePath = $this->getOriginalFile($customFilePath);
            $origExists = file_exists($originalFilePath);
            $custExists = file_exists($customFilePath);
            // try without custom/, as this is a merge
            if(!$origExists && !$custExists) {
                $this->logUpgradeStatus("Could not find $originalFilePath or $customFilePath for $lViewtype");
                continue;
            }

            if (!$origExists && $custExists) {
                $this->originalLegacyViewdefs[$lViewtype] = $this->loadLayout($view, $customFilePath, $lViewtype);
            } else {
                $this->originalLegacyViewdefs[$lViewtype] = $this->loadLayout($view, $originalFilePath, $lViewtype);
            }
            if($custExists) {
                $foundCustom = true;
                $this->legacyViewdefs[$lViewtype] = $this->loadLayout($view, $customFilePath, $lViewtype);
            } else {
                $this->legacyViewdefs[$lViewtype] = $this->originalLegacyViewdefs[$lViewtype];
            }

        }
        // If we didn't find any custom files - we don't need to do anything
        if(!$foundCustom) {
            $this->legacyViewdefs = array();
            $this->logUpgradeStatus("Did not find customizations for {$this->viewtype}");
        }
    }

    protected function loadLayout($view, $filepath, $lViewtype){
        $this->logUpgradeStatus("Loading $filepath for $lViewtype");
        include $filepath;
        // There is an odd case where custom modules are pathed without the
        // package name prefix but still use it in the module name for the
        // viewdefs. This handles that case. Also sets a prop that lets the
        // rest of the process know that the module is named differently
        if (isset($module_name)) {
            $this->modulename = $module = $module_name;
        } else {
            $module = $this->module;
        }


        $ret = array();

        $var = $this->variableMap[$this->client][$view];
        if (isset($$var)) {
            $defs = $$var;
            if($this->sidecar) {
                if(!empty($defs[$module][$this->client]['view'][$view])) {
                    $ret = $defs[$module][$this->client]['view'][$view];
                }
            } else {
                if (isset($this->vardefIndexes[$this->client.$view])) {
                    $index = $this->vardefIndexes[$this->client.$view];
                    // This is a rarity, but it does happen in the wild... if the
                    // known module name is the key of the defs use it, otherwise
                    // get the key from the defs and use that
                    reset($defs);
                    $key = isset($defs[$module]) ? $module : key($defs);
                    $ret = empty($index) ? $defs[$key] : $defs[$key][$index];
                    if($this->client == 'portal' && !empty($ret['data'])) {
                        // Portal views are in 'data', not 'panels'
                        // Because it'd be boring if all data formats were the same, right?
                        $ret['panels'] = array($ret['data']);
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * (non-PHPdoc)
     * @see SidecarAbstractMetaDataUpgrader::handleSave()
     */
    public function handleSave()
    {
        if(empty($this->sidecarViewdefs)) {
            // if we didn't create any new defs, nothing to save
            return true;
        }
        return parent::handleSave();
    }

    /**
     * Handles conversion of old style defs to new style defs, using the basics
     * of a layout def
     *
     * @param string $fieldname The fieldname to work with
     * @param array $data The layout def of the legacy field
     * @return array
     */
    protected function convertFieldData($fieldname, $data)
    {
        if(!empty($this->knownFields[$fieldname])) {
            return $this->knownFields[$fieldname];
        } elseif (isset($data['type']) && $data['type'] == 'address' && isset($data['displayParams']['key'])) {
            // If this is an address combo handle it
            $address = $this->getAddressFieldsetDef($fieldname);
            if (!empty($address)) {
                return $address;
            }
        }

        $newdata = array('name' => $fieldname);
        if (is_array($data)) {
            if (!empty($data['readonly']) || !empty($data['readOnly'])) {
                $newdata['readonly'] = true;
            }

            if (!empty($data['label']) && $this->isValidLabel($data['label'])) {
                $newdata['label'] = $data['label'];
            }
        }
        return $newdata;
    }

    /**
     * Checks to see if a label is valid for inclusion on a Record view. The
     * {@see $invalidLabelRegex} array contains a list of regular expressions to
     * check the label against to make sure that it is clean for use.
     *
     * @param string $label The label to check for validation
     * @return boolean
     */
    protected function isValidLabel($label)
    {
        foreach ($this->invalidLabelRegex as $pattern) {
            if (preg_match($pattern, $label)) {
                return false;
            }
        }

        return true;
    }

    protected function loadDefaultMetadata()
    {
        $defaultDefs = parent::loadDefaultMetadata();
        if(!empty($defaultDefs) && !empty($this->base_defsfile) && !empty($this->defsfile)) {
            // If we loaded template one, copy it to base file so we could load the parser
            // We only do this if the module is deployed though
            if ((file_exists($this->base_defsfile) || !file_exists($this->defsfile)) && $this->deployed) {
                $this->logUpgradeStatus("Copying template defs {$this->base_defsfile} to {$this->defsfile}");
                mkdir_recursive(dirname($this->defsfile));
                $client = $this->client == 'wireless' ? 'mobile' : $this->client;
                $viewname = pathinfo($this->defsfile, PATHINFO_FILENAME);
                $export = var_export($defaultDefs, true);
                $data  = <<<END
<?php
/* Generated by SugarCRM Upgrader */
\$viewdefs['{$this->getNormalizedModuleName()}']['{$client}']['view']['{$viewname}'] = {$export};
END;
                sugar_file_put_contents($this->defsfile, $data);

            }
        }
        return $defaultDefs;
    }

    /**
     * Checks whether there are customizations to tab defs in the detailview layout
     * of the module. At this time all other views are not checked for tab customizations.
     *
     * @param string $view The viewtype to check tab customizations on
     * @return boolean
     */
    protected function hasTabDefCustomizations($view)
    {
        if ($view !== 'detailview') {
            return false;
        }

        // Check bool true instead of truthy since studio would set this to boolean
        $useTabs = isset($this->legacyViewdefs[$view]['templateMeta']['useTabs'])
                   && $this->legacyViewdefs[$view]['templateMeta']['useTabs'] === true;

        // Make sure there are tab defs and they are an array
        $hasDefs = !empty($this->legacyViewdefs[$view]['templateMeta']['tabDefs'])
                   && is_array($this->legacyViewdefs[$view]['templateMeta']['tabDefs']);

        return $useTabs && $hasDefs;
    }

    /**
     * Converts the legacy Grid metadata to Sidecar style
     */
    public function convertLegacyViewDefsToSidecar()
    {
        if(empty($this->legacyViewdefs)) {
            return;
        }

        // If this module is undeployed and doesn't have a base set of metadata,
        // we should just end things right now. It's not you, it's me.
        if (!$this->deployed && $this->package) {
            // This is the marker that decides our fate
            $found = false;


            // The undeployed implementation uses history then working, where 
            // working translates to the base file and history translates to 
            // working. 
            $types = array(
                MB_HISTORYMETADATALOCATION,
                MB_WORKINGMETADATALOCATION,
            );

            // Check each type for file existence. This is done similarly to the
            // implementation, without the overhead of constructing that object 
            // or the parser just yet.
            foreach ($types as $type) {
                $file = MetaDataFiles::getUndeployedFileName($this->viewtype, $this->module, $this->package, $type, $this->client);
                if (file_exists($file)) {
                    $found = true;
                    break;
                }
            }

            // If neither of the files we needed are found, bail now since without
            // these the upgrade process breaks
            if (!$found) {
                $this->logUpgradeStatus("Required {$this->client} {$this->viewtype} metadata for {$this->module} is missing... aborting merge upgrade");
                return;
            }
        }

        $this->logUpgradeStatus('Converting ' . $this->client . ' ' . $this->viewtype . ' view defs for ' . $this->module);

        // Holds merged defs and final defs
        $newdefs = $tempdefs = $finaldefs = array();

        // This needs to be called before the parser is instantiated to prevent
        // custom metadata from being created from basic module template
        $defaultDefs = $this->loadDefaultMetadata();

        // Get the parser now that default metadata has been fetched
        $parser = ParserFactory::getParser($this->viewtype, $this->module, $this->package, null, $this->client);

        // Get the fields that are on the default defs panels since we may need 
        // those as well
        $defaultDefsFields = $parser->getFieldsFromPanels($defaultDefs['panels']);

        // Go through merge views, add fields added to detail view to base panel
        // and fields added to edit view not in detail view or hidden panel
        $customFields = array();
        foreach($this->legacyViewdefs as $lViewtype => $data) {
            // We will need a parser no matter what
            $legacyParser = $this->getLegacyParser($lViewtype);
            // Step 1, handle tabDef changes
            $hasTabDefCustomizations = $this->hasTabDefCustomizations($lViewtype);

            // Tabdefs holds tab def customizations. If there are tab customizations
            // then the defaultDefs need to be derived from the customize layout
            // instead of the default viewdef
            $tabdefs = array();
            if ($hasTabDefCustomizations) {
                // Used for converting tab names. Tabs and panels need to match
                // and that matching gets handled here and in handleConversion
                $c = 0;

                // pull out the tab definitions from the originals, put them into the Canonical Form
                foreach($data['templateMeta']['tabDefs'] as $tabName => $tabContent) {
                    // Handle panel labels for matching later
                    $panelNames = $this->getConvertedPanelName($tabName, $c);
                    $tabName = $panelNames['label'];

                    // Save these for later to prevent conflict with new panels later
                    $tabdefs[$tabName] = array(
                        'newTab' => $tabContent['newTab'],
                        'panelDefault' => $tabContent['panelDefault']
                    );

                    // Increment the counter
                    $c++;
                }

                // Intermediate step here needed for tabdef customizations... what is
                // needed is to convert the old panels to new style, then inject the
                // defaultDef header panel into the newly converted panel set before
                // continuing on
                $headerPanel = $defaultDefs['panels'][0];
                $defaultDefs = $this->handleConversion($data, 'panels', true);
                array_unshift($defaultDefs['panels'], $headerPanel);
            }

            // TemplateMeta needs to be added if it isn't, to both the default defs
            // and the parser viewdefs
            if (isset($data['templateMeta'])) {
                if (!isset($defaultDefs['templateMeta'])) {
                    $defaultDefs['templateMeta'] = $data['templateMeta'];
                } else {
                    $defaultDefs['templateMeta'] = array_merge($defaultDefs['templateMeta'], $data['templateMeta']);
                }

                if (!isset($parser->_viewdefs['templateMeta'])) {
                    $parser->_viewdefs['templateMeta'] = $defaultDefs['templateMeta'];
                } else {
                    $parser->_viewdefs['templateMeta'] = array_merge($parser->_viewdefs['templateMeta'], $defaultDefs['templateMeta']);
                }
            }

            // Make a header fields array so that fields that may be in legacy
            // defs can be plucked out
            $headerFields = array();
            foreach ($defaultDefs['panels'][0]['fields'] as $hField) {
                // Handle array type fields first
                if (is_array($hField)) {
                    // But only if there is a name element of the array
                    if (isset($hField['name'])) {
                        // First set from the name of the field we are on
                        $headerFields[$hField['name']] = $hField['name'];

                        // Now if there are fields for this field (fieldset), grab those too
                        if (isset($hField['fields']) && is_array($hField['fields'])) {
                            foreach ($hField['fields'] as $hFieldName) {
                                // Some modules have header field fieldset defs that are arrays
                                if (is_array($hFieldName) && isset($hFieldName['name'])) {
                                    $headerFields[$hFieldName['name']] = $hFieldName['name'];
                                } else {
                                    $headerFields[$hFieldName] = $hFieldName;
                                }
                            }
                        }
                    }
                } else {
                    // This will be a string, take it as is
                    $headerFields[$hField] = $hField;
                }
            }

            // Step 2, convert panels if there are any to handle
            if(!empty($data['panels'])) {
                // Add spans fields as needed
                $maxSpan = $parser->getMaxSpan();
                // Need to send the entire data array as we need templateData.maxColumns
                $data = $this->addSpansToFields($data, $maxSpan);
                $legacyPanelFields = $legacyParser->getFieldsFromPanels($data['panels']);
                foreach($legacyPanelFields as $fieldname => $fielddef) {
                    // Handle removal of fields from customFields (legacy defs) as needed
                    $skip = false;
                    if (empty($fieldname) || !$this->isValidField($fieldname)) {
                        // Definitely skip keeping empty and invalid field names.
                        $skip = true;
                    } else {
                        // Is this field already in the customFields collection?
                        $cf = isset($customFields[$fieldname]);

                        // Or perhaps it is explicitly in the removeFields array?
                        $rf = isset($this->removeFields[$fieldname]);

                        // Or maybe it is in the headerFields array?
                        $hf = isset($headerFields[$fieldname]);

                        // If this field is any of the arrays above, skip it
                        if ($cf || $rf || $hf) {
                            $skip = true;
                        }
                    }

                    if ($skip) {
                        continue;
                    }
                    $customFields[$fieldname] = array('data' => $fielddef, 'source' => $lViewtype);
                }
            }

            // Handle field conversion and renames from previous versions
            $customFields = $this->applyFieldConversionPatches($customFields);

            // Grab our twitter conversion flag early since twitter_id could be
            // an array as a field
            $convertTwitter = $this->needsTwitterConversion();

            // Handle unsetting of header fields from non header panels and handle
            // email1 <=> email conversion
            foreach ($defaultDefs['panels'] as $panelIndex => $panel) {
                foreach ($panel['fields'] as $fieldIndex => $fieldName) {

                    // Turn a field name into a string for searching convenience
                    if (is_array($fieldName)) {
                        $lookupFieldName = (isset($fieldName['name'])) ? $fieldName['name'] : null;
                    } else {
                        $lookupFieldName = $fieldName;
                    }

                    // Handle fields that are not meant to be here, like header
                    // fields, but only if we are not in the header panel
                    if ($panelIndex > 0) {
                        if (isset($lookupFieldName) && isset($headerFields[$lookupFieldName])) {
                            // Remove this field from the defs and move on
                            unset($defaultDefs['panels'][$panelIndex]['fields'][$fieldIndex]);
                            continue;
                        }
                    }

                    // Delete 'team_name' from the layout if module doesn't implement team-security, otherwise leave it;
                    // the field is a part of 'basic' template by default, but module may not implement team-security
                    if (isset($lookupFieldName) && $lookupFieldName == 'team_name' && !$this->isValidField($fieldName)) {
                        unset($defaultDefs['panels'][$panelIndex]['fields'][$fieldIndex]);
                    }

                    // Hack email field into submission
                    if (isset($lookupFieldName) && $lookupFieldName == 'email1') {
                        if (is_array($fieldName)) {
                            $fieldName['name'] = 'email';
                            $defaultDefs['panels'][$panelIndex]['fields'][$fieldIndex] = $fieldName;
                        } else {
                            $defaultDefs['panels'][$panelIndex]['fields'][$fieldIndex] = 'email';
                        }
                    }

                    // Handle twitter_id to twitter renaming
                    if (isset($lookupFieldName) && $lookupFieldName == 'twitter_id' && $convertTwitter) {
                        // If twitter is already on the defaults, remove twitter_id entirely,
                        // otherwise rename twitter_id
                        if (isset($defaultDefsFields['twitter'])) {
                            unset($defaultDefs['panels'][$panelIndex]['fields'][$fieldIndex]);
                        } elseif (is_array($fieldName)) {
                            $fieldName['name'] = 'twitter';
                            $defaultDefs['panels'][$panelIndex]['fields'][$fieldIndex] = $fieldName;
                        } else {
                            $defaultDefs['panels'][$panelIndex]['fields'][$fieldIndex] = 'twitter';
                        }
                    }
                }

                // Reset the array indexes
                $defaultDefs['panels'][$panelIndex]['fields'] = array_values($defaultDefs['panels'][$panelIndex]['fields']);
            }
            // End email1 => email hack

            // Make sure the array pointer for the panels is back at the start.
            // This is needed to allow canonical conversion to pick up the right
            // header panel
            reset($defaultDefs['panels']);

            $origFields = array();
            // replace viewdefs with defaults, since parser's viewdefs can be already customized by other parts
            // of the upgrade
            $parser->_viewdefs['panels'] = $parser->convertFromCanonicalForm($defaultDefs['panels']);
            // get field list
            $origData = $parser->getFieldsFromPanels($defaultDefs['panels']);
            // Go through existing fields and remove those not in the new data
            foreach($origData as $fname => $fielddef) {
                if (!$this->isValidField($fname)) {
                    continue;
                }
                if(is_array($fielddef) && !empty($fielddef['fields'])) {
                    // fieldsets - iterate over each field
                    $setExists = false;
                    if(!empty($customFields[$fielddef['name']])) {
                        $setExists = true;
                    } else {
                        foreach($fielddef['fields'] as $setfielddef) {
                            if(!is_array($setfielddef)) {
                                $setfname = $setfielddef;
                            } else {
                                // skip weird nameless ones
                                if(empty($setfielddef['name'])) continue;
                                $setfname = $setfielddef['name'];
                            }
                            // if we have one field - we take all set
                            if(isset($customFields[$setfname])) {
                                $setExists = true;
                                break;
                            }
                        }
                    }
                    if($setExists) {
                        $origFields[$fielddef['name']] = $fielddef;
                        // if fields exist, we take all the set as existing fields
                        foreach($fielddef['fields'] as $setfielddef) {
                            if(!is_array($setfielddef)) {
                                $setfname = $setfielddef;
                            } else {
                                // skip werid nameless ones
                                if(empty($setfielddef['name'])) continue;
                                $setfname = $setfielddef['name'];
                            }
                            $origFields[$setfname] = $fielddef;
                        }
                    } else {
                        // else we delete the set but only if it isn't a header field
                        if (!isset($headerFields[$fname])) {
                            $parser->removeField($fname);
                        }
                    }
                } else {
                    // if it's a regular field, check against existing field in new data
                    if(!isset($customFields[$fname]) && !isset($headerFields[$fname])) {
                        // not there - remove it
                        $parser->removeField($fname);
                    } else {
                        // otherwise - keep as existing
                        $origFields[$fname] = $fielddef;
                    }
                }
            }

            // now go through new fields and add those not in original data
            // $customFields is legacy defs, $origFields are Sugar7 OOTB defs
            foreach($customFields as $fieldname => $data) {
                if(isset($origFields[$fieldname])) {
                    // If the field is special, massage it into latest format being
                    // sure to maintain its current position in the layout
                    if ($this->isSpecialField($fieldname)) {
                        $fielddata = $this->convertFieldData($fieldname, $data['data']);
                        $this->updateField($parser, $fieldname, $fielddata);
                    }
                } else {
                    $fielddata = $this->convertFieldData($fieldname, $data['data']);
                    // FIXME: hack since addField cuts field defs
                    if($this->isSpecialField($fieldname) && empty($parser->_originalViewDef[$fielddata['name']])) {
                        $parser->_originalViewDef[$fielddata['name']] = $fielddata;
                    }
                    $parser->addField($fielddata, $this->getPanelName($parser->_viewdefs['panels'], $data['source']));
                }
            }

            // Convert the panels array to something useful
            $panels = $parser->convertToCanonicalForm($parser->_viewdefs['panels'] ,$parser->_fielddefs);

            // Add back in tabDefs on the panels if there are any
            if (!empty($tabdefs)) {
                foreach($panels as $key => $panel) {
                    if (!empty($panel['label']) && isset($tabdefs[$panel['label']])) {
                        $panels[$key]['newTab'] = $tabdefs[$panel['label']]['newTab'];
                        $panels[$key]['panelDefault'] = $tabdefs[$panel['label']]['panelDefault'];
                    }
                }
            }

            // There needs to be two different arrays here to allow for non-recursive
            // but nested array merges. This allows for detail views to be upgraded
            // first followed by edit views while still maintaining tab definitions
            // for detail views
            if (empty($newdefs['panels'])) {
                $newdefs = $parser->_viewdefs;
                $newdefs['panels'] = $panels;
            } else {
                $tempdefs = $parser->_viewdefs;
                $tempdefs['panels'] = $panels;
            }

            // After all iterations, this will be the final, merged layout def
            $finaldefs = $this->mergeLayoutDefs($newdefs, $tempdefs);
        }

        //Finally re-add any fields that are on the OOB sidecar view but not on the OOB Legacy views
        $finaldefs = $this->addNewFieldsToLayout($finaldefs);

        $this->sidecarViewdefs[$this->getNormalizedModuleName()][$this->client]['view'][MetaDataFiles::getName($this->viewtype)] = $finaldefs;
    }

   /**
    * Get panel name where new field should be placed
    * @param array $panels Panel data for viewdef
    * @param string $source Source view for field
    * @return string|null
    */
   protected function getPanelName($panels, $source)
   {
       if(isset($this->viewPanels[$this->viewtype][$source])) {
           $panelName = $this->viewPanels[$this->viewtype][$source];
       }
       if(empty($panelName) || !isset($panels[$panelName])) {
           // will use last available panel
           $panelNames = array_keys($panels);
           $panelName = array_pop($panelNames);
       }
       return $panelName;
   }

    /**
     * Gets address field recordview defs to replace previous address fields from
     * edit/detail view
     *
     * @param string $name The name of the field from the old viewdef
     * @return array
     */
    protected function getAddressFieldsetDef($name)
    {
        if (isset($this->addressFields[$name])) {
            return $this->addressFields[$name];
        }

        return array();
    }

    /**
     * Determines if a field is valid based on name.
     *
     * @param string $field The name of the field
     * @return boolean True if the field name is valid
     */
    public function isValidField($field)
    {
        // Empty strings are not valid fields
        if (empty($field)) {
            return false;
        }

        // Because some fields on a layout are not actual fields (fieldsets) we
        // need to make sure that this field is not one of those special fields
        // we know about. This loops over those special fields before delegating
        // to the bean.
        $props = array('addressFields', 'knownFields');

        foreach ($props as $prop) {
            $array = $this->$prop;
            foreach ($array as $fieldName => $fieldDef) {
                // If the field is the same name as the special array index, it's
                // good to go
                if ($field == $fieldName) {
                    return true;
                }

                // If the name of the field in the def is the same as the field,
                // it's good to go
                if (isset($fieldDef['name']) && $fieldDef['name'] == $field) {
                    return true;
                }

                // If the special field's def is a fieldset, and any of the fields
                // in that set match the field, it is good to go
                if (isset($fieldDef['fields']) && is_array($fieldDef['fields'])) {
                    foreach ($fieldDef['fields'] as $fields) {
                        if (isset($fields['name']) && $fields['name'] == $field) {
                            return true;
                        }
                    }
                }
            }
        }

        // Now delegate to the parent
        return parent::isValidField($field);
    }

    /**
     * Determines if a field is special by name
     *
     * @param string $fieldname The name of the field to check
     * @return boolean
     */
    protected function isSpecialField($fieldname)
    {
        return !empty($this->knownFields[$fieldname]) || !empty($this->addressFields[$fieldname]);
    }

    /**
     * Determines if a field is special by the name that is used in Sidecar field name convention.
     * Is a reverse variant of SidecarMergeGridMetaDataUpgrader::isSpecialField() method.
     *
     * @param string $fieldName The name of the field to check
     * @return boolean
     */
    protected function isSpecialFieldBySidecarName($fieldName)
    {
        $allFields = array_merge($this->knownFields, $this->addressFields);
        foreach ($allFields as $def) {
            if (!empty($def['name']) && $def['name'] == $fieldName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Updates relevant information for a field on the parser prior to saving new
     * defs
     *
     * @param AbstractMetaDataParser $parser A metadata parser object, probably a Grid Parser
     * @param string $fieldname The name of the field to update
     * @param array $fielddata The data that should be used to do the update on the field
     * @return boolean
     */
    protected function updateField($parser, $fieldname, $fielddata)
    {
        // Find $fieldname in the viewdefs and change it to fielddata[name]
        foreach ($parser->_viewdefs['panels'] as $panelName => $panel) {
            foreach ($panel as $rowIndex => $row) {
                foreach ($row as $index => $field) {
                    if ($field == $fieldname) {
                        // Change the field name to what it should be
                        $parser->_viewdefs['panels'][$panelName][$rowIndex][$index] = $fielddata['name'];

                        // Update the original view def with the newest def
                        $parser->_originalViewDef[$fielddata['name']] = $fielddata;

                        // Remove the old def since it is no longer needed
                        unset($parser->_originalViewDef[$fieldname]);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Merges current defs into previous, also cleaning up defs along the way
     *
     * @param array $current The current layoutdefs array
     * @param array $previous The last layout defs array
     * @return array
     */
    protected function mergeLayoutDefs(array $current, array $previous)
    {
        if (empty($previous)) {
            return $current;
        }

        // Handle panel merging
        foreach ($previous['panels'] as $index => $panel) {
            $current['panels'][$index] = array_merge($current['panels'][$index], $panel);
        }

        if (isset($current['templateMeta'])) {
            foreach ($current['templateMeta'] as $key => $value) {
                if (!isset($this->templateMetaProps[$key])) {
                    unset($current['templateMeta'][$key]);
                }
            }
        }

        return $current;
    }

    /**
     * Walk through default record view lookng for fields that did not exist on the original pre-sidecar layout defs
     * Any such fields should be appended to the final defs
     *
     * @param array $newDefs
     * @param array $defaultDefs
     */
    protected function addNewFieldsToLayout(array $newDefs) {
        $defaultDefs = $this->loadDefaultMetadata();
        $parser = ParserFactory::getParser($this->viewtype, $this->module, $this->package, null, $this->client);
        $defaultFields = $parser->getFieldsFromPanels($defaultDefs['panels']);
        $currentFields = $parser->getFieldsFromPanels($newDefs['panels']);
        $origFields = array();
        foreach($this->originalLegacyViewdefs as $lViewtype => $data) {
            // We will need a parser no matter what
            $legacyParser = $this->getLegacyParser($lViewtype);
            // replace viewdefs with defaults, since parser's viewdefs can be already customized by other parts
            // of the upgrade
            $legacyParser->_viewdefs['panels'] = $legacyParser->convertFromCanonicalForm($data['panels']);
            // get field list
            $origData = $legacyParser->getFieldsFromPanels($data['panels']);
            $origFields = array_merge($origFields, $origData);
        }
        //Add fields always defaults to the bottom of the first panel.
        //In this case the 'first panel' is the first non-header panel, AKA panel[1]
        if (empty($newDefs['panels'][1]['fields'])) {
            $this->logUpgradeStatus("Unable to find panels to add to");
        }

        foreach($defaultFields as $field => $def) {
            // If the special field was not present on legacy layout we shouldn't add it.
            if ($this->isSpecialFieldBySidecarName($field) && empty($currentFields[$field])) {
                continue;
            }
            //If a field wasn't on the lagacy layout, add it to the custom one.
            if (empty($origFields[$field]) && empty($currentFields[$field]) && !empty($newDefs['panels'][1]['fields'])) {
                $newDefs['panels'][1]['fields'][] = $def;
            }
        }

        return $newDefs;
    }

    /**
     * Applies hacks and patches to fielddefs that have changes since the previous
     * version
     * 
     * @param array $data The current custom defs to check
     * @return array The hacked together defs
     */
    public function applyFieldConversionPatches($data)
    {
        // We need to keep the order of fields in tact when changing field names.
        // The basics are, when changing a field name like email1 to email, 
        // "renaming" it basically removed it from the current array and added a
        // new field to the end of the array:
        // $data = array('a' => '', 'b1' => '', 'c' => '') gets turned into
        // $data = array('a' => '', 'c' => '', 'b' => '')
        // Keeping order fixes that issue
        $order = array();
        foreach ($data as $key => $val) {
            $order[$key] = $key;
        }

        // Hack: we've moved email1 to email
        if(isset($data['email1'])) {
            // Get the defs for the email1 field into the email field
            $data['email'] = $data['email1'];

            // Remove the email1 field since it is not longer used
            unset($data['email1']);

            // Keep the order of the fields in tact
            $order['email1'] = 'email';
        }

        // We've also moved twitter_id to twitter for some module types
        if (isset($data['twitter_id']) && $this->needsTwitterConversion()) {
            // Only reset twitter_id to twitter if twitter isn't set already
            if (!isset($data['twitter'])) {
                $data['twitter'] = $data['twitter_id'];
            }

            // We no longer need this
            unset($data['twitter_id']);

            // Keep the order of this field
            $order['twitter_id'] = 'twitter';
        }

        // Reset the return data to use the proper order
        $return = array();
        foreach ($order as $new) {
            $return[$new] = $data[$new];
        }

        return $return;
    }

    /**
     * Checks a bean to see if the module needs to have the twitter_id field
     * converted
     * 
     * @return boolean
     */
    public function needsTwitterConversion()
    {
        // Get the bean for this module since we might need it
        $bean = BeanFactory::newBean($this->module);

        if (!empty($bean)) {
            // Check our type
            $checkType  = $bean instanceof Company || $bean instanceof Person;

            // Check the tables... there are exceptions, eg, Employees, Styleguide
            $checkTable = !empty($bean->table_name) && $bean->table_name != 'users' && $bean->table_name != 'styleguide';

            // Send back what we know
            return $checkType && $checkTable;
        }

        return false;
    }

    /**
     * Modifies the panels originally picked up in the scraping of layouts for 
     * upgrading. This will add necessary spans to defs if they aren't there yet.
     * 
     * @param array $panels The metadata array
     * @param int $maxSpan The span size to cover for a cell across a row
     * @return array
     */
    public function addSpansToFields(array $data, $maxSpan)
    {
        // Yes, this could be done in a ternary, but long lines anger the standards
        // monsters so we use more lines to keep them skinny.
        $maxCols = 2;
        if (isset($data['templateMeta']['maxColumns'])) {
            $maxCols = intval($data['templateMeta']['maxColumns']);
        }

        // Get a single cell span
        $span = floor($maxSpan / $maxCols);

        // Grab the panels for mutations now
        $panels = $data['panels'];
        foreach ($panels as $panelName => $panel) {
            foreach ($panel as $rowIndex => $row) {
                if (is_array($row)) {
                    // Get the count of the row cells. If they are less than max cols
                    // then span the last cell
                    $count = count($row);
                    if ($count < $maxCols) {
                        // Index for the last cell in the row
                        $i = $count - 1;

                        // This is the total span for the last row, including the 
                        // last cell in the row
                        $totalSpan = $span * ($maxCols - $i);
                        if (!is_array($row[$i])) {
                            $newRow = array('name' => $row[$i]);
                        } else {
                            $newRow = $row[$i];
                        }

                        $newRow['span'] = $totalSpan;
                        $row[$i] = $newRow;
                    }
                } else {
                    // This is a string field so we need to look at the $panel
                    $count = count($panel);
                    if ($count < $maxCols) {
                        $totalSpan = $span * ($maxCols - ($count - 1));
                        $newRow = array(
                            'name' => $row,
                            'span' => $totalSpan,
                        );

                        $row = $newRow;
                    }
                }

                $panels[$panelName][$rowIndex] = $row;
            }
        }

        $data['panels'] = $panels;
        return $data;
    }

    /**
     * Returns parser for parsing legacy layout
     *
     * @param string $lViewtype
     * @return AbstractMetaDataParser
     */
    protected function getLegacyParser($lViewtype)
    {
        if ($this->sidecar) {
            $parser = ParserFactory::getParser($lViewtype, $this->module, $this->package, null, $this->client);
        } elseif ($this->client == 'portal') {
            require_once 'modules/ModuleBuilder/parsers/parser.portallupgrade.php';
            $parser = new ParserPortalUpgrade();
        } else {
            $parser = ParserFactory::getParser($lViewtype, $this->module, $this->package);
        }

        return $parser;
    }
}
