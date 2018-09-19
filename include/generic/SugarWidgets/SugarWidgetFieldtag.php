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
 * Report widget field that handles specifics of Tag field types
 */
class SugarWidgetFieldTag extends SugarWidgetFieldVarchar {
    /**
     * Handles formatting of a Tag field for rendering on report list views
     * 
     * @param array $layout_def The defs of the field from the report
     * @return string
     */
    public function displayList($layout_def)
    {
        // Get the value of the field
        $value = $this->displayListPlain($layout_def);
        
        // $value should be something like ^..^,^..^
        // No, that isn't an emotibatman parade
        // 
        // This logic removes outer ^ characters, explodes on ^,^ and then glues
        // back together on ', '
        $data = implode(', ', explode('^,^', trim($value, '^')));
        return $data;
    }

    /**
     * Handles WHERE query building for CONTAINS requests
     * 
     * @param array $layout_def The defs of the field from the report
     * @return string
     */
    function queryFilterContains(&$layout_def)
    {
        $matches = explode(',', $layout_def['input_name0']);
        $q = "";
        foreach ($matches as $match) {
            // Make the match field the lowercase version of the field. This feels
            // a little dirty but I bet Mike Rowe would approve
            $match = strtolower(trim($match));
            $q .= " " . $this->getLowercaseColumnSelect($layout_def) . " LIKE '%" .$GLOBALS['db']->quote($match)."%' OR";
        }

        return rtrim($q, " OR");
    }

    /**
     * Handles WHERE query building for NOT CONTAINS requests
     * 
     * @param array $layout_def The defs of the field from the report
     * @return string
     */
    function queryFilterNot_Contains(&$layout_def)
    {
        $matches = explode(',', $layout_def['input_name0']);
        $q = "";
        foreach ($matches as $match) {
            // Make the match field the lowercase version of the field. This feels
            // a little dirty but I bet Mike Rowe would approve
            $match = strtolower(trim($match));
            $q .= " " . $this->getLowercaseColumnSelect($layout_def) . " NOT LIKE '%" .$GLOBALS['db']->quote($match)."%' AND";
        }

        return rtrim($q, " AND");
    }

    /**
     * Gets the lowercase version of the name of the field for use in queries
     * 
     * @param array $def
     * @return string
     */
    protected function getLowercaseColumnSelect($def)
    {
        // Copied to ensure that references don't get clobbered after this point
        $hold = $def;
        $hold['name'] .= '_lower';
        return parent::_get_column_select($hold);
    }
}
