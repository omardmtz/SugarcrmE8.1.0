<!-- -->
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
.view tr td table.action tr td  {
	border-bottom: thin solid #CCC;
	padding:3px 8px 5px 5px;
}

.view tr td table.action  {
	border-style: none none none solid;
	border-width: thin;
	border-color: #CCC;
	color:#000;
	width:100%;
	padding:0px;
	background-color:#FAFAFA;
}

table.action th {
	background:#AAA none repeat scroll 0 0;
	color:black;
}

.toggleDown div, .toggleUp div {
	background-image:url(themes/default/images/ArrowButtons.png);
	width:18px;
	height:14px
}

table.list tr td.toggleDown div {
	background-position:0px 14px;
}

table.list tr td.toggleDown, table.list:hover tr td.toggleDown:hover {
	background-image:url(themes/default/images/line.gif);
	background-position:7px 10px;
	background-repeat:no-repeat;
}
.actionLeadLine {
	background-image:url(themes/default/images/line.gif);
	background-position:7px 50%;
	background-repeat:no-repeat;
}

.actionLeadLine div{
	background-color:black;
	height:1px;
	position:relative;
	right:-8px;
	top:-6px;
	width:7px;
}
</style>
{/literal}
<div id='depGrid'></div>
{if $empty}{sugar_translate label='LBL_NO_RELS' module='ModuleBuilder'}</h3>{/if}
{if $studio}{sugar_translate label='LBL_CUSTOM_RELATIONSHIPS' module='ModuleBuilder'}</h3>{/if}
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="list view">
	<tr class="list header">
		<th width="13px">&nbsp;</th><th width="5px">&nbsp;</th>
		<th width="25%">{sugar_translate label="LBL_REL_NAME"}</th>
		<th width="55%">{sugar_translate label="LBL_TRIGGER"}</th>
		<th width="25%">{sugar_translate label="LBL_ACTIONS"}</th><th>&nbsp;</th><th>&nbsp;</th>
	</tr>
	{foreach from=$dependencies item='dep' key='depName'}
		{counter name="offset" print=false}
		{if $smarty.foreach.dependencies.iteration is odd}
			{assign var='_rowColor' value='odd'}
		{else}
			{assign var='_rowColor' value='even'}
		{/if}
		<tr class="{$_rowColor}ListRowS1">
			<td id="{$dep.name}actionsTri" id=width="18px" colspan="2" class="actionToggle toggleUp">
				<div onclick="ModuleBuilder.Dependencies.toggleAction('{$dep.name}actions')"/></div></td>
			<td>{$dep.name}</td>
			<td>{$dep.condition}</td>
			<td><button id="{$dep.name}actionsBtn" class="button" 
					onclick="ModuleBuilder.Dependencies.toggleAction('{$dep.name}actions')">
				Show Actions</button></td>
			<td width="10px"><img border="0" alt=$mod_strings.LBL_EDIT src="themes/default/images/edit_inline.gif"/></td>
			<td width="10px"><img border="0" alt=$mod_strings.LBL_DELETE src="themes/Sugar/images/delete_inline.gif"/></td>
		</tr>
		<tr style="display:none" id="{$dep.name}actions">
			<td class="actionLeadLine"><div/></td>
			<td colspan="6" style="padding-left:0px;padding-right:0px">
    		<table cellspacing="0" class="action view list" border="0" cellpadding="0"/>
    			<tr height="10"><th width="25%" class="headerList">Action</th><th width="25%" class="headerList">Target</th>
    			<th width="50%" class="headerList">Details</th><th class="headerList">&nbsp;</th><th class="headerList">&nbsp;</th>
    			{foreach from=$dep.actions item='action'}
    			<tr><td>{$action.action}</td><td>{$action.target}</td><td>{$action.value}</td>
    			<td width="10px"><img border="0" alt=$mod_strings.LBL_EDIT src="themes/default/images/edit_inline.gif"/></td>
    			<td width="10px"><img border="0" alt=$mod_strings.LBL_DELETE src="themes/Sugar/images/delete_inline.gif"/></td></tr>
    			{/foreach}
    			<tr><td colspan="5"><div class="action new" style="text-align:center">New Action 
    			<img border="0" alt=$mod_strings.LBL_NEW src="themes/default/images/new_inline.gif" style="position: relative; top: 0.15em;"/></div></td></tr>
    		</table>
    	</td></tr>  
	{/foreach}
</table>
{sugar_translate label="LBL_REL_NAME"}:<input id="newDepName" name="newDepName"/>
{sugar_translate label="LBL_TRIGGER"}:
<input id="newDepTrigger" name="newDepTrigger" style='background-color:#EEE;color:#222' readonly/>
<input class="button" type=button name="editFormula" value="Edit Formula"  style="margin-bottom:3px;"
onclick="ModuleBuilder.moduleLoadFormula(Ext.getDom('newDepTrigger').value, 'newDepTrigger')"/>
<button onclick='ModuleBuilder.Dependencies.addNewDep(Ext.getDom("newDepName").value, Ext.getDom("newDepTrigger").value)' 
	style="margin-bottom:3px;">{$mod_strings.LBL_BTN_ADD_DEPENDENCY}</button>
	
<div id=yuiTable/>
<script type="text/javascript">
var dependencies = {ldelim}"dependencies":{$dependencies}{rdelim};
{literal}
ModuleBuilder.Dependencies = {
	visibleActions:[],
	toggleAction : function(id) {
		if (this.visibleActions[id]) {
			this.visibleActions[id] = false;
			document.getElementById(id).style.display = 'none';
			document.getElementById(id + "Tri").className = "toggleUp";
			document.getElementById(id + "Btn").innerHTML = "Show Actions";
		} else {
			document.getElementById(id).style.display = '';
			this.visibleActions[id] = true;
			document.getElementById(id + "Tri").className = "toggleDown";
			document.getElementById(id + "Btn").innerHTML = "Hide Actions";
		}
	},
	addNewDep : function(name, trigger) {
		//Ajax save the new dep then append it to the list. (refresh the whole view?)
		Ext.DomHelper.append(ModuleBuilder.mainPanel.getEl(), {
			id:'loadingDiv', tag:'div',cls:'loading ext-el-mask-msg',html:'Saving...',
			})
		Ext.get('loadingDiv').center();
		ModuleBuilder.tabPanel.disable();
		Ext.Ajax.request({
			params:{
				module:"ExpressionEngine",
				action:"saveDependency",
				isNew: true,
				module: ModuleBuilder.module,
				package:ModuleBuilder.MBpackage,
				name:name,
				trigger:trigger
			},
			success:function(){
				ModuleBuilder.getContent(
					'module=ModuleBuilder&action=moduledependencies&view_module=' + ModuleBuilder.module + 
					'&view_package=' + ModuleBuilder.MBpackage, function(o){
						ModuleBuilder.updateContent(o);
						Ext.get("loadingDiv").remove();
						ModuleBuilder.tabPanel.enable();
					}
				);
			}
		});
	},
	setupDataTable: function() {
		this.dataSource = new YAHOO.util.DataSource([]) ;
		this.dataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY; 
		this.dataSource.responseSchema = { 
	            fields: ["name","condition","actions", "type"] 
	        }; 
		this.dataTable = new YAHOO.widget.DataTable("yuiTable", 
			[
				{key:"name",sortable:true},
				{key:"condition"},
				{key:"actions"},
				{key:"type"}
			], 
			this.dataSource, 
			{}
		); 
	}
	
}

{/literal}
ModuleBuilder.module = '{$view_module}';
ModuleBuilder.MBpackage = '{$view_package}';
//ModuleBuilder.helpRegisterByID('depGrid');
/*{*
var dependencies = {ldelim}"dependencies":{$dependencies}{rdelim};
var listTemplate = new Ext.XTemplate(
'<table cellspacing="0" cellpadding="0" border="0" width="100%" class="list view">',
	'<tr class="list header">',
		'<th>{sugar_translate label="LBL_REL_NAME"}</th>',
		'<th>{sugar_translate label="LBL_CONDITION"}</th>',
		'<th>{sugar_translate label="LBL_ACTIONS"}</th><th/><th/>',
	'<tpl for="dependencies">',
		'</tr><tr class="{[xindex % 2 === 0 ? "even" : "odd"]}ListRowS1">',
			'<td></td>',
	'</tpl>'
);


var depstore = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
        root:"dependencies",
        id: "name"
        }, Ext.data.Record.create([
            {name:'name', mapping:'name'},
            {name:'condition', mapping:'condition'},
            {name:'actions'}, {name:'actions'},
            {name:'type'}])
	),
	data:dependencies,
	sortInfo:{field: 'name', direction: "ASC"},
	groupField:'type',
	listeners: {
		datachanged: function(s) {

		}
	}
});

var grid = new Ext.grid.GridPanel({
    store: depstore,
     columns: [
        {id:'name', header: SUGAR.language.get('ModuleBuilder','LBL_REL_NAME'), width: 120, sortable: true, dataIndex: 'name'},
        {header: SUGAR.language.get('ModuleBuilder','LBL_CONDITION'), width: 200, sortable: false, dataIndex: 'condition'},
        {header: SUGAR.language.get('ModuleBuilder','LBL_ACTIONS'), width: 200, sortable: false, dataIndex: 'actions', renderer:function(a){
			return '<input type=button id="' + a[0][0] + 'act_btn" class="button" onclick="ModuleBuilder.toggleActions(this)"  value="Show Actions"/>';
		}},
        {header: '', width: 20, sortable: false, dataIndex: '', renderer:function(){
			return '<img border="0" alt=$mod_strings.LBL_EDIT src="themes/default/images/edit_inline.gif"/>';
		}},
		{header: '', width: 20, sortable: false, dataIndex: '',renderer:function(){
			return '<img border="0" alt=$mod_strings.LBL_EDIT src="themes/default/images/edit_inline.gif"/>';
		}}
    ],
    renderTo:'depGrid',
    sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
	width:Ext.isIE ? Ext.get(Ext.getDom('depGrid').parentNode).getWidth() - 25 : "100%",
    autoHeight: true
});
grid.on('rowclick', function(grid, i){
	var rel = grid.getStore().getAt(i);
	//ModuleBuilder.tabPanel.remove("relEditor");
	//ModuleBuilder.moduleLoadRelationship2(rel.data.relationship_name);
});*}*/
ModuleBuilder.module = '{$view_module}';
ModuleBuilder.MBpackage = '{$view_package}';
ModuleBuilder.helpRegisterByID('depGrid');
//{if $fromModuleBuilder}
ModuleBuilder.helpSetup('relationshipsHelp','default');
//{else}
ModuleBuilder.helpSetup('studioWizard','relationshipsHelp');
//{/if}
</script>
