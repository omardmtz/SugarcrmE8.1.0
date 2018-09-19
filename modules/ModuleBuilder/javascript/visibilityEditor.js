
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

if (typeof ModuleBuilder == "undefined" || !ModuleBuilder)
	var ModuleBuilder = {} ;

/*
 * Some code for checkboxes thanks to Ext Grid sample (extjs.com)
 */
 
ModuleBuilder.VisibilityEditor = function ( myData , targetId , returnFunction, onCloseFunction )
{	
	var myColumnDefs = new Array ( { 
		dataIndex:'sugar_key' ,
		header:SUGAR.language.get('ModuleBuilder','LBL_DEPENDENT_DROPDOWN' ) 
	} ) ;
	var myJsonDefs = new Array () ;
	var fields = new Array () ;
	
	for ( var i in myData.visibility_grid[0] )
	{
			fields [ fields.length ] = i;
			myJsonDefs[myJsonDefs.length] = { name: i } ;

			var colDef = {
				hidden: ( i == 'sugar_key' ),
				header: ( i == '' ) ? SUGAR.language.get('ModuleBuilder','LBL_BLANK' ) : i , 
				dataIndex:i,
				sortable: false,
				renderer: function(v, p, record){
        			p.css += ' x-grid3-check-col-td'; 
        			return '<div class="x-grid3-check-col'+(v?'-on':'')+' x-grid3-cc-'+this.id+'">&#160;</div>';
    				}
			};
			
			myColumnDefs[myColumnDefs.length] = colDef ;

	}
	
	var myStoreDef = {
    	fields: fields,
        data: myData,
        reader: new Ext.data.JsonReader({
        	root: 'visibility_grid'
        	},
        	new Ext.data.Record.create ( myJsonDefs )
        )
    };
     
	var myEditorGrid = new Ext.grid.GridPanel({
		id: 'visibilityEditor',
		layout: 'fit',
		applyTo: targetId,
		clicksToEdit:1,
		stripeRows: true,
		store: new Ext.data.Store(myStoreDef),
		cm: new Ext.grid.ColumnModel(myColumnDefs)
	});
	myEditorGrid.fields = fields;
	
	/*
	 * On commit, generate a JSON encoded representation of the visibility grid
	 * The final format will be a JSON encoded version of the following :
	 * [
 	 * { key: 'field_key_1' , trigger_key_1: true/false , trigger_key_2 : true/false, ... , trigger_key_n : true/false },
	 * { key: 'field_key_2' , trigger_key_1: true/false , trigger_key_2 : true/false, ... , trigger_key_n : true/false },
	 * { ... }
	 * { key: 'field_key_m' , trigger_key_1: true/false , trigger_key_2 : true/false, ... , trigger_key_n : true/false },
	 * ]
	 * The visibility grid is held within the store in a number of records, where each record holds a boolean value indicating if
	 * the dependent dropdown value in that column is shown when the trigger is set to the value provided in the record key
	 */
	myEditorGrid.commit = function () {
		var grid = {} ;
		for ( var column = 0 ; column < this.fields.length ; column++ ) {
			var fieldKey = this.fields [ column ] ;
			for ( var row = 0 ; row < this.store.getCount() ; row++ ) {
				var record = this.store.getAt ( row ) ;
				var triggerKey = record.get ( 'sugar_key' ) ;
				if ( ! grid [ triggerKey ] )
					grid [ triggerKey ] = {} ;
				grid [ triggerKey ] [ fieldKey ] = record.get ( fieldKey ) ;
			}
		}		
		return Ext.util.JSON.encode ( grid );
	}
		
	myEditorGrid.on( 'cellmousedown' , function(grid, rowIndex , columnIndex){
    	var record = this.store.getAt(rowIndex);
        var column = this.fields[columnIndex-1];
        record.set(column, !record.data[column]);
    });
	
	this.myEditorPanel = new Ext.Panel({
		items: [myEditorGrid],
		applyTo: targetId,
		layout: 'fit',
		buttons: [
			{text: SUGAR.language.get('app_strings','LBL_SAVE_BUTTON_LABEL' ), handler: returnFunction},
			{text: SUGAR.language.get('app_strings','LBL_CANCEL_BUTTON_LABEL' ), handler: onCloseFunction }
		]
	});
};
