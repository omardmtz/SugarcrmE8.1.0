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
 * Highlighter
 *
 *                      !!! DEPRECATION WARNING !!!
 *
 * All code in include/SugarSearchEngine is going to be deprecated in a future
 * release. Do not use any of its APIs for code customizations as there will be
 * no guarantee of support and/or functionality for it. Use the new framework
 * located in the directories src/SearchEngine and src/Elasticsearch.
 *
 * @deprecated
 */
class SugarSearchEngineHighlighter
{
    /**
     * @var module name
     */
    protected $module;

    public static $preTag = '<strong>';
    public static $postTag = '</strong>';
    public static $fragmentSize = 20;
    public static $fragmentNumber = 2;
    public static $fragmentSep = ' ... ';

    /**
     * Setter for module name.
     * @param $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * Process highlighter results
     * @param array $results
     * @return array
     */
    public function processHighlightText(array $results)
    {
        $ret = array();
        foreach ($results as $field => $fragments) {
            $ret[$field] = array(
                'text' => '',
                'module' => $this->module,
                'label' => $this->getLabel($field)

            );
            $first = true;

            foreach ($fragments as $fragment) {

                // check if $fragment is an array
                // E.g. if $field = 'email', $fragment could be an array.
                // make sure to use its value only
                if (is_array($fragment) && count($fragment) == 1) {
                    $fragment = $fragment[0];
                }
                if (!is_string($fragment)) {
                    continue;
                }

                if (!$first) {
                    $ret[$field]['text'] .= self::$fragmentSep . $fragment;
                } else {
                    $ret[$field]['text'] = $fragment;
                }
                $first = false;
            }
        }

        return $ret;
    }

    /**
     * Get field label
     * @param string $field
     * @return string
     */
    public function getLabel($field)
    {
        if (empty($this->module)) {
            return $field;
        } else {
            $field_defs = $this->getFieldDefs($this->module);
            $field_def = isset($field_defs[$field]) ? $field_defs[$field] : false;
            if ($field_def === false || (!isset($field_def['vname']) && !isset($field_def['label']))) {
                return $field;
            }

            return (isset($field_def['label'])) ? $field_def['label'] : $field_def['vname'];
        }
    }


    /**
     * Return field defs for given module name
     * @param string $module
     * @return array
     */
    protected function getFieldDefs($module)
    {
        return BeanFactory::newBean($module)->field_defs;
    }
}
