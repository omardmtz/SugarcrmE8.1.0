{*
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
*}

{literal}
<style>
.yui-dt-col-required .yui-dt-liner, .yui-dt-col-copyData .yui-dt-liner, .yui-dt-col-delete .yui-dt-liner
{
    text-align:center;
}
</style>
{/literal}
{if !empty($warningMessage)}
<p class="error">{$warningMessage}</p>
{/if}
<input type='button' name='saveLayout' value='{sugar_translate label="LBL_BTN_SAVE" module="ModuleBuilder"}'
    class='button' onclick='ModuleBuilder.saveConvertLeadLayout();' style="margin-bottom:5px;">
<img class="spacer" src="{sugar_getjspath file='include/images/blank.gif'}" style="width:50px;height:5px"/>
{html_options name="convertSelectNewModule" id="convertSelectNewModule" options=$availableModules}
<input type='button' name='addModule' value='{sugar_translate label="LBL_CONVERT_ADD_MODULE"}'
    class='button' onclick='ModuleBuilder.addConvertLeadLayout();' style="margin-bottom:5px;">

<div id='relGrid'></div>
{if $studio}{sugar_translate label='LBL_CUSTOM_RELATIONSHIPS' module='ModuleBuilder'}</h3>{/if}
<script>
{literal}

function getModuleNameFromLabel(label) {
    var moduleList = SUGAR.language.get('app_list_strings', "moduleList");
    for (var i in moduleList) {
        if (moduleList[i] == label) {
            return i;
        }
    }
    return label;
}

var removeLayout = function(row) {
    if (confirm("Are you sure you wish to remove this layout?")) {
        var module = row.getData("module");
        var moduleName = row.getData("moduleName");

        ModuleBuilder.convertLayoutGrid.deleteRow(row);
        ModuleBuilder.state.markAsDirty();

        addOption(module, moduleName);
    }
};

var formatRemoveButton = function(el, rec, col, data) {
    var out;
    if (rec.getData().module == "Contacts") {
        return;
    }
    out = {/literal}"<img alt='{$mod_strings.LBL_EDIT_INLINE}' name='delete_inline' src='{sugar_getimagepath file='delete_inline.gif'}' />";{literal}
    el.innerHTML = out;
    YAHOO.util.Event.addListener(el, "click", function() {
        removeLayout(grid.getRecord(el));
    });
};

var formatCheckbox = function(el, rec, col, data){
    var out = "<input type='checkbox' name='" + rec.getData().module + "-" + col.field + "'"
           + "onclick='ModuleBuilder.convertLayoutGrid.getRecord(this).setData(\"" + col.field + "\", this.checked)';";
    if (data) {
        out += " checked='checked'";
    }
    if (isCheckboxDisabled(rec, col)) {
        out += " disabled ";
    }
    out += " />";
    el.innerHTML = out;

    addDragDropStatus(el, rec);
};

var isCheckboxDisabled = function(rec, col) {
    var oppRow,
        module = rec.getData().module,
        isDisabled = false;

    if (module == 'Contacts') {
        isDisabled = true;
    } else if (module == 'Accounts' && col.key == 'required') {
        //if Opportunity is on the convert lead layout, disable required for Account
        oppRow = _.find(modules.modules, function(row) {
            return (row.module && row.module === 'Opportunities');
        });
        if (oppRow) {
            isDisabled = true;
        }
    }
    return isDisabled;
};

//some modules should not allow drag & drop to maintain their order
var addDragDropStatus = function(el, rec) {
    var $row = $(el).closest('tr'),
        disableDragDrop = [
            "Contacts",
            "Accounts",
            "Opportunities"
        ];
    if (disableDragDrop.indexOf(rec.getData().module) != -1) {
        $row.addClass('sw-no-drag-drop');
    }
};

{/literal}
var modules = {ldelim}modules:{$modules}{rdelim};
var moduleDefaults = {$moduleDefaults};
YAHOO.SUGAR.DragDropTable.groups = [];
var grid = ModuleBuilder.convertLayoutGrid = new YAHOO.SUGAR.DragDropTable('relGrid',
    [
        {ldelim}key:'module',       label: '{sugar_translate label="LBL_CONVERT_MODULE_NAME"}', hidden: true {rdelim},
        {ldelim}key:'duplicateCheckOnStart', label: '{sugar_translate label="LBL_CONVERT_MODULE_NAME"}', hidden: true {rdelim},
        {ldelim}key:'moduleName',   label: '{sugar_translate label="LBL_CONVERT_MODULE_NAME"}', width: 200,sortable: false {rdelim},
        {ldelim}key:'required',     label: '{sugar_translate label="LBL_CONVERT_REQUIRED"}',    width: 80, sortable: false, formatter:formatCheckbox{rdelim},
        {ldelim}key:'copyData',     label: '{sugar_translate label="LBL_CONVERT_COPY"}',        width: 80, sortable: false, formatter:formatCheckbox{rdelim},
        {ldelim}key:'delete',       label: '{sugar_translate label="LBL_CONVERT_DELETE"}',      width: 60, sortable: false, formatter:formatRemoveButton{rdelim}
    ],{literal}
    new YAHOO.util.LocalDataSource(modules, {
        responseSchema: {
           resultsList : "modules",
           fields : [{key : "module"}, {key : "moduleName"},{key: "required"}, {key: "copyData"}, {key: "delete"}]
        }
    }),
    {MSG_EMPTY: SUGAR.language.get('ModuleBuilder','LBL_NO_RELS')}
);

//don't highlight row if it is not a drag/drop row
var onEventHighlightRow = function(args) {
    if (args.target.className.indexOf('sw-no-drag-drop') == -1) {
        grid.onEventHighlightRow(args);
    }
};

grid.subscribe("rowMouseoverEvent", onEventHighlightRow);
grid.subscribe("rowMouseoutEvent", grid.onEventUnhighlightRow);
grid.render();
{/literal}
//tooltips
new YAHOO.widget.Tooltip("module_tooltip", {ldelim}
    context: grid.getColumn(2).getThEl(),
    text: '{sugar_translate label="LBL_MODULE_TIP"}',
    showDelay: 500
{rdelim});
new YAHOO.widget.Tooltip("required_tooltip", {ldelim}
    context: grid.getColumn(3).getThEl(),
    text: '{sugar_translate label="LBL_REQUIRED_TIP"}',
    showDelay: 500
{rdelim});
new YAHOO.widget.Tooltip("copy_tooltip", {ldelim}
    context: grid.getColumn(4).getThEl(),
    text: '{sugar_translate label="LBL_COPY_TIP"}',
    showDelay: 500
{rdelim});
new YAHOO.widget.Tooltip("delete_tooltip", {ldelim}
    context: grid.getColumn(5).getThEl(),
    text: '{sugar_translate label="LBL_DELETE_TIP"}',
    showDelay: 500
{rdelim});
{literal}
ModuleBuilder.saveConvertLeadLayout = function() {
	var rows = ModuleBuilder.convertLayoutGrid.getRecordSet().getRecords(),
        params,
        out = {};

    for (var i in rows) {
        out[i] = rows[i].getData();
        out[i].module = getModuleNameFromLabel(out[i].module);
    }
    params = {
        module: 'Leads',
        action: 'editconvert',
        updateConvertDef: true,
        data:YAHOO.lang.JSON.stringify(out)
    };

    ModuleBuilder.state.markAsClean();
	ModuleBuilder.asyncRequest(params, function(o) {
	    ajaxStatus.hideStatus();
	    ModuleBuilder.updateContent(o);
	});
};

ModuleBuilder.addConvertLeadLayout = function() {
    var select = YAHOO.util.Dom.get("convertSelectNewModule");
    if (select.selectedIndex < 0) {
        return;
    }

    var option = select.options[select.selectedIndex],
        module = option.value,
        insertIndex = determineNewRowIndex(module),
        data = YAHOO.lang.merge({}, moduleDefaults[module], {
            module: module,
            moduleName: option.text
        });

    ModuleBuilder.convertLayoutGrid.addRow(data, insertIndex);
    select.removeChild(option);
    ModuleBuilder.state.markAsDirty();
};

var determineNewRowIndex = function(newModule) {
    //force Accounts, Contacts, and Opportunities to remain in the same order
    var orderRestrictions = {
            'Accounts': {after: ['Contacts']},
            'Opportunities': {after: ['Contacts', 'Accounts']}
        },
        rows = ModuleBuilder.convertLayoutGrid.getRecordSet().getRecords(),
        insertIndex = rows.length,
        tempIndex = 0;

    if (orderRestrictions[newModule]) {
        _.each(orderRestrictions[newModule].after, function(precedingModule) {
            var precedingRowIndex,
                precedingRow = _.find(rows, function(row) {
                    return (row.getData().module === precedingModule);
                });
            if (precedingRow) {
                precedingRowIndex = ModuleBuilder.convertLayoutGrid.getRecordIndex(precedingRow);
                if (precedingRowIndex >= tempIndex) {
                    tempIndex = precedingRowIndex + 1;
                }
            }
        });
        insertIndex = tempIndex;
    }
    return insertIndex;
};

var addOption = function(module, moduleName) {
    var select = YAHOO.util.Dom.get("convertSelectNewModule"),
        options = select.options;
    for (var i = 0, length = select.length; i < length; i++) {
        if (options[i].text.localeCompare(moduleName) >= 0) {
            break;
        }
    }

    var option = document.createElement("option");
    option.value = module;
    option.text = moduleName;
    select.add(option, i);
};

{/literal}
ModuleBuilder.module = '{$view_module}';
ModuleBuilder.MBpackage = '{$view_package}';
ModuleBuilder.helpSetup('studioWizard','convertLeadHelp');
</script>
