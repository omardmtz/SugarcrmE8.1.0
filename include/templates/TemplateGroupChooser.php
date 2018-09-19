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

$js_loaded = false;

class TemplateGroupChooser extends Template {
    var $args;
    var $js_loaded = false;
    var $display_hide_tabs = true;
    var $display_third_tabs = false;

    function display() {
        global $app_strings, $mod_strings, $js_loaded;
        
        $left_size = (empty($this->args['left_size']) ? '10' : $this->args['left_size']);
        $right_size = (empty($this->args['right_size']) ? '10' : $this->args['right_size']);
        $third_size = (empty($this->args['third_size']) ? '10' : $this->args['third_size']);
        $max_left = (empty($this->args['max_left']) ? '' : $this->args['max_left']);
        $alt_tip_up = $app_strings['LBL_ALT_MOVE_COLUMN_UP'];
        $alt_tip_down = $app_strings['LBL_ALT_MOVE_COLUMN_DOWN'];
        $alt_tip_left = $app_strings['LBL_ALT_MOVE_COLUMN_LEFT'];
        $alt_tip_right = $app_strings['LBL_ALT_MOVE_COLUMN_RIGHT'];
        
        $str = '';
        if($js_loaded == false) {
//            $this->template_groups_chooser_js();
            $js_loaded = true;
        }
        if(!isset($this->args['display'])) {
            $table_style = "";
        }
        else {
            $table_style = "display: ".$this->args['display'];
        }

        $str .= "<div id=\"{$this->args['id']}\" style=\"{$table_style}\">";
        if(!empty($this->args['title'])) $str .= "<h4>{$this->args['title']}</h4>";
        $str .= <<<EOQ
        <table cellpadding="0" cellspacing="0" border="0">
        
        <tr>
            <td>&nbsp;</td>
            <td scope="row" id="chooser_{$this->args['left_name']}_text" align="center"><nobr>{$this->args['left_label']}</nobr></td>
EOQ;

        if($this->display_hide_tabs == true) {
           $str .= <<<EOQ
            <td>&nbsp;</td>
            <td scope="row" id="chooser_{$this->args['right_name']}" align="center"><nobr>{$this->args['right_label']}</nobr></td>
EOQ;
        }
        
        if($this->display_third_tabs == true) {
           $str .= <<<EOQ
            <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td scope="row" id="chooser_{$this->args['third_name']}" align="center"><nobr>{$this->args['third_label']}</nobr></td>
EOQ;
        }
        
        $str .= '<td>&nbsp;</td></tr><tr><td valign="top" style="padding-right: 2px; padding-left: 2px;" align="center">';
        if(!isset($this->args['disable'])) {
            $str .= "<a id='chooser_{$this->args['left_name']}_up_arrow' onclick=\"return SUGAR.tabChooser.up('{$this->args['left_name']}','{$this->args['left_name']}','{$this->args['right_name']}');\">" .  SugarThemeRegistry::current()->getImage('uparrow_big','border="0" style="margin-bottom: 1px;"',null,null,'.gif',$alt_tip_up) . "</a><br>
                     <a id='chooser_{$this->args['right_name']}_down_arrow' onclick=\"return SUGAR.tabChooser.down('{$this->args['left_name']}','{$this->args['left_name']}','{$this->args['right_name']}');\">" . SugarThemeRegistry::current()->getImage('downarrow_big','border="0" style="margin-top: 1px;"',null,null,'.gif',$alt_tip_down) . "</a>";
        }
        
        $str .= <<<EOQ
                </td>    
                <td align="center">
                    <table border="0" cellspacing=0 cellpadding="0" align="center">
                        <tr>
                            <td id="{$this->args['left_name']}_td" align="center">
                            <select id="{$this->args['left_name']}" name="{$this->args['left_name']}[]" size=
EOQ;
        $str .=  '"' . (empty($this->args['left_size']) ? '10' : $this->args['left_size']) . '" multiple="multiple" ' . (isset($this->args['disable']) ?  "DISABLED" : '') . 'style="width: 150px;">';

        foreach($this->args['values_array'][0] as $key=>$value) {
            $str .= "<option value='{$key}'>{$value}</option>";
        }
        $str .= "</select></td>
            </tr>
            </table>
            </td>";
        if ($this->display_hide_tabs == true) {
            $str .= '<td valign="top" style="padding-right: 2px; padding-left: 2px;" align="center">';
            if(!isset($this->args['disable'])) { 
                $str .= "<a id='chooser_{$this->args['left_name']}_left_arrow' onclick=\"return SUGAR.tabChooser.right_to_left('{$this->args['left_name']}','{$this->args['right_name']}', '{$left_size}', '{$right_size}', '{$max_left}');\">" . SugarThemeRegistry::current()->getImage('leftarrow_big','border="0" style="margin-right: 1px;"',null,null,'.gif',$alt_tip_left) . "</a><a id='chooser_{$this->args['left_name']}_left_to_right' onclick=\"return SUGAR.tabChooser.left_to_right('{$this->args['left_name']}','{$this->args['right_name']}', '{$left_size}', '{$right_size}');\">" . SugarThemeRegistry::current()->getImage('rightarrow_big','border="0" style="margin-left: 1px;"',null,null,'.gif',$alt_tip_right) . "</a>";
            }
            $str .= "</td>
                     <td>
                     <table border=\"0\" cellspacing=0 cellpadding=\"0\" align=\"center\">
                        <tr>
                <td id=\"{$this->args['right_name']}_td\" align=\"center\">
                <select id=\"{$this->args['right_name']}\" name=\"{$this->args['right_name']}[]\" size=\"" . (empty($this->args['right_size']) ? '10' : $this->args['right_size']) . "\" multiple=\"multiple\" " . (isset($this->args['disable']) ? "DISABLED" : '') . 'style="width: 150px;">';
            foreach($this->args['values_array'][1] as $key=>$value) {
                $str .= "<option value=\"{$key}\">{$value}</option>";
            }
            $str .= "</select></td>
            </tr>
            </table>
            </td>";
            $str .= "<td valign=\"top\" style=\"padding-right: 2px; padding-left: 2px;\" align=\"center\">"
                    . "<script>var object_refs = new Object();object_refs['{$this->args['right_name']}'] = document.getElementById('{$this->args['right_name']}');</script>";
         }
         
         if ($this->display_third_tabs == true) {
            $str .= '<td valign="top" style="padding-right: 2px; padding-left: 2px;" align="center">';
            if(!isset($this->args['disable'])) { 
                $str .= "<a id='chooser_{$this->args['right_name']}_right_arrow' onclick=\"return SUGAR.tabChooser.right_to_left('{$this->args['right_name']}','{$this->args['third_name']}', '{$right_size}', '{$third_size}');\">" . SugarThemeRegistry::current()->getImage('leftarrow_big','border="0" style="margin-right: 1px;"',null,null,'.gif',$alt_tip_left) . "</a><a id='chooser_{$this->args['right_name']}_left_to_right' onclick=\"return SUGAR.tabChooser.left_to_right('{$this->args['right_name']}','{$this->args['third_name']}', '{$right_size}', '{$third_size}');\">" . SugarThemeRegistry::current()->getImage('rightarrow_big','border="0" style="margin-left: 1px;"',null,null,'.gif',$alt_tip_right) . "</a>";
            }
            $str .= "</td>
                <td id=\"{$this->args['third_name']}_td\" align=\"center\">
                <select id=\"{$this->args['third_name']}\" name=\"{$this->args['third_name']}[]\" size=\"" . (empty($this->args['third_size']) ? '10' : $this->args['third_size']) . "\" multiple=\"multiple\" " . (isset($this->args['disable']) ? "DISABLED" : '') . 'style="width: 150px;">';
            foreach($this->args['values_array'][2] as $key=>$value) {
                $str .= "<option value=\"{$key}\">{$value}</option>";
            }
            $str .= "</select>
                <script>
                    object_refs['{$this->args['third_name']}'] = document.getElementById('{$this->args['third_name']}');
                </script>
                <td valign=\"top\" style=\"padding-right: 2px; padding-left: 2px;\" align=\"center\">
                </td>";
         }
         $str .= "<script>
                object_refs['{$this->args['left_name']}'] = document.getElementById('{$this->args['left_name']}');
                </script></tr>
            </table></div>";

                
        return $str;
}



    /*
     * All Moved to sugar_3.js in class tabChooser;
     * Please follow style that Dashlet configuration is done.
     */ 
    function template_groups_chooser_js() {
        //return '<script>var object_refs = new Object();</script>';
    }

}
