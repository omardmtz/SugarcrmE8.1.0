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
 * Class OpportunityViews
 */
class OpportunityViews
{

    /**
     * @var Opportunity
     */
    protected $bean;

    public function __construct()
    {
        SugarAutoLoader::load('modules/ModuleBuilder/parsers/ParserFactory.php');
        $this->bean = BeanFactory::newBean('Opportunities');
    }

    /**
     * Process the Base Record View for Opportunities
     *
     * @param array $fieldMap
     */
    public function processBaseRecordLayout(array $fieldMap)
    {
        /* @var $gridDefParser SidecarGridLayoutMetaDataParser */
        $gridDefParser = ParserFactory::getParser(MB_RECORDVIEW, 'Opportunities', null, null, 'base');

        $this->_processRecordParser($gridDefParser, $fieldMap);
    }

    /**
     * Process the Mobile Edit and Detail Views for Opportunities
     *
     * @param array $fieldMap
     */
    public function processMobileRecordLayout(array $fieldMap)
    {
        /* @var $gridDefParser SidecarGridLayoutMetaDataParser */
        $gridDefParser = ParserFactory::getParser(MB_WIRELESSEDITVIEW, 'Opportunities', null, null, 'mobile');
        $this->_processRecordParser($gridDefParser, $fieldMap);

        /* @var $gridDefParser SidecarGridLayoutMetaDataParser */
        $gridDefParser = ParserFactory::getParser(MB_WIRELESSDETAILVIEW, 'Opportunities', null, null, 'mobile');
        $this->_processRecordParser($gridDefParser, $fieldMap);
    }

    /**
     * Reusable code that allows for the Record View Parsers
     *
     * @param SidecarGridLayoutMetaDataParser $parser
     * @param array $fieldMap
     */
    protected function _processRecordParser(SidecarGridLayoutMetaDataParser $parser, array $fieldMap) {
        // no matter what we are going to add everything to the first panel at the end, SidecarGridLayoutMetaDataParser
        // doesn't have position capabilities...grrrr

        $fields = $parser->getAvailableFields();
        $handleSave = false;

        // sort the map first, so the removes get done first
        asort($fieldMap);

        foreach ($fieldMap as $fieldName => $fieldAction) {
            if ($fieldAction === true) {
                // lets make sure the field is Available
                foreach ($fields as $k => $val) {
                    if ($val['name'] == $fieldName) {
                        $handleSave = $handleSave | $parser->addField($val);
                        break;
                    }
                }
            } else {
                if ($fieldAction === false) {
                    $handleSave = $handleSave | $parser->removeField($fieldName);
                }
            }
        }
        if ($handleSave) {
            $parser->handleSave(false, false);
        }
    }

    /**
     * Add and Remove fields from all the list views
     *
     * @param array $fieldMap How are we going to change the fields
     * @return array The Modules which had their subpanels affected
     */
    public function processListViews(array $fieldMap)
    {
        // fix the selected-list view
        $this->processSelectedListView($fieldMap);

        // fix the dupecheck-list view
        $this->processDupeCheckListView($fieldMap);

        // get the generic list view
        $this->processListView($fieldMap);

        // get the mobile list view now
        $this->processMobileListView($fieldMap);

        $subpanel_modules = array('Opportunities');

        $links = $this->bean->get_linked_fields();

        foreach($links as $link => $def) {
            if ($this->bean->load_relationship($link) && $this->bean->$link instanceof Link2) {
                $linkname = $this->bean->$link->getRelatedModuleLinkName();
                $relatedmodule = $this->bean->$link->getRelatedModuleName();

                if (!empty($linkname) && $relatedmodule != 'Contracts') {
                    $this->processListView($fieldMap, $relatedmodule, $linkname);
                    $subpanel_modules[] = $relatedmodule;
                }
            }
        }

        return $subpanel_modules;
    }

    /**
     * Since the module name is lowercase, we need to find it in the keys of lowercase
     *
     * @param String $moduleToLookFor What module are we trying to find.
     * @return bool|String
     */
    protected function findModuleName($moduleToLookFor)
    {
        global $beanList;

        // do this here so we don't have to do it over and over again
        $moduleToLookFor = strtolower($moduleToLookFor);

        // find the correct bean module name
        foreach ($beanList as $beanModule => $beanName) {
            if (strtolower($beanModule) === $moduleToLookFor) {
                return $beanModule;
                break;
            }
        }

        // module not found;
        return false;
    }

    /**
     * Fix the `selected-list` view
     *
     * @param array $fieldMap
     */
    protected function processSelectedListView(array $fieldMap)
    {
        /* @var $listDefsParser SidecarListLayoutMetaDataParser */
        $listDefsParser = ParserFactory::getParser(MB_SIDECARPOPUPVIEW, 'Opportunities', null, null, 'base');
        $this->processList($fieldMap, $listDefsParser->_paneldefs, $listDefsParser);
    }

    /**
     * Fix the `dupecheck-list` view
     *
     * @param array $fieldMap
     */
    protected function processDupeCheckListView(array $fieldMap)
    {
        /* @var $listDefsParser SidecarListLayoutMetaDataParser */
        $listDefsParser = ParserFactory::getParser(MB_SIDECARDUPECHECKVIEW, 'Opportunities', null, null, 'base');
        $this->processList($fieldMap, $listDefsParser->_paneldefs, $listDefsParser);
    }

    /**
     * Fix the normal list view + any subpanels
     *
     * @param array $fieldMap
     * @param string $module
     * @param null $subpanel_name
     */
    protected function processListView(array $fieldMap, $module = 'Opportunities', $subpanel_name = null)
    {
        /* @var $listDefsParser SidecarListLayoutMetaDataParser */
        $listDefsParser = ParserFactory::getParser(MB_LISTVIEW, $module, null, $subpanel_name, 'base');
        $this->processList($fieldMap, $listDefsParser->_paneldefs, $listDefsParser);
    }

    /**
     * Fix the mobile list view
     *
     * @param array $fieldMap
     */
    protected function processMobileListView(array $fieldMap)
    {
        /* @var $listDefsParser SidecarListLayoutMetaDataParser */
        $listDefsParser = ParserFactory::getParser(MB_WIRELESSLISTVIEW, 'Opportunities', null, null, 'mobile');
        $this->processList($fieldMap, $listDefsParser->_paneldefs, $listDefsParser);
    }

    /**
     * Process the ListView to set the fields correctly
     *
     * @param array $fieldMap
     * @param $current_fields
     * @param ListLayoutMetaDataParser $listParser
     * @return bool did the ListLayout get updated?
     */
    protected function processList(array $fieldMap, $current_fields, ListLayoutMetaDataParser $listParser)
    {
        if (!($listParser instanceof SidecarListLayoutMetaDataParser)) {
            // we have a BWC module
            return $this->processBWCList($fieldMap, $listParser);
        }

        $handleSave = false;
        $saveFields = array();
        // process the fields
        foreach ($current_fields as $panel_id => $panel) {
            if (is_array($panel['fields'])) {
                foreach ($panel['fields'] as $field) {
                    $name = $field['name'];
                    $addField = true;
                    $additionalDefs = $field;
                    if (isset($fieldMap[$name])) {
                        if ($fieldMap[$name] === true) {
                            // nothing to do, field is present
                        } elseif ($fieldMap[$name] !== false) {
                            // we have the field, so get it's defs
                            $defs = $this->bean->getFieldDefinition($fieldMap[$name]);
                            if ($defs) {
                                // set the name variable to the new field name.
                                $name = $fieldMap[$name];
                                // reset the additionDefs since we have a new field
                                $additionalDefs = array();
                            } else {
                                // we didn't find any defs for the new field, so error on caution and remove the old one
                                $addField = false;
                            }
                            $handleSave = true;
                        } else {
                            // instead of a name being passed in, false was, so we should remove that field.
                            $addField = false;
                            $handleSave = true;
                        }

                        unset($fieldMap[$name]);
                    }

                    if ($addField) {
                        $saveFields[] = array($name, $additionalDefs);
                    }
                }
            }
        }

        // make sure that the field map is empty, if it's not process any remaining fields
        if (!empty($fieldMap)) {
            foreach($fieldMap as $field => $trigger) {
                if($trigger === true) {
                    $defs = $this->bean->getFieldDefinition($field);
                    if ($defs) {
                        $saveFields[] = array($field, array());
                        $handleSave = true;
                    }
                }
            }
        }
        if ($handleSave) {
            // make sure the list is reset
            $listParser->resetPanelFields();

            foreach ($saveFields as $params) {
                $listParser->addField($params[0], $params[1]);
            }
            $listParser->handleSave(false, false);
        }

        return $handleSave;
    }

    /**
     * Process the BWC Subpanels
     *
     * @param array $fieldMap The changes being made to the fields
     * @param ListLayoutMetaDataParser $listParser the List Parser to use
     * @return bool Did this update the List Layout defs?
     */
    protected function processBWCList(array $fieldMap, ListLayoutMetaDataParser $listParser)
    {
        // there is a relationship but it doesn't have a opp subpanel so it's empty
        // it should just bailout from this method
        if (empty($listParser->_viewdefs)) {
            return false;
        }

        // backup what is currently in $_POST
        $backupPost = $_POST;

        $_POST = array(
            'view_module' => $listParser->getModuleName(),
            'group_0' => array()
        );

        $handleSave = false;
        // process the fields
        foreach ($listParser->_viewdefs as $name => $field_def) {
            $addField = true;
            if (isset($fieldMap[$name])) {
                if ($fieldMap[$name] === true) {
                    // nothing to do, field is present
                } elseif ($fieldMap[$name] !== false) {
                    // we have the field, so get it's defs
                    $defs = $this->bean->getFieldDefinition($fieldMap[$name]);
                    if ($defs) {
                        // set the name variable to the new field name.
                        $name = $fieldMap[$name];
                    } else {
                        // we didn't find any defs for the new field, so error on caution and remove the old one
                        $addField = false;
                    }
                    $handleSave = true;
                } else {
                    // instead of a name being passed in, false was, so we should remove that field.
                    $addField = false;
                    $handleSave = true;
                }

                unset($fieldMap[$name]);
            }

            if ($addField) {
                $_POST['group_0'][] = $name;
            }
        }

        // make sure that the field map is empty, if it's not process any remaining fields
        if (!empty($fieldMap)) {
            foreach($fieldMap as $field => $trigger) {
                if($trigger === true) {
                    $defs = $this->bean->getFieldDefinition($field);
                    if ($defs) {
                        $_POST['group_0'][] = $field;
                        $handleSave = true;
                    }
                }
            }
        }

        $return = $handleSave;
        if ($handleSave) {
            // when we save it, return what we saved
            $return = $_POST;
            $listParser->handleSave(true, false);
        }
        // restore the post variables
        $_POST = $backupPost;

        return $return;
    }
}
