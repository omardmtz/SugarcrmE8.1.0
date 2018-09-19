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
(function( $ ) {

    var defaultPrefix = 'progrid', 
    defaultSettings = {
        id: null, 
        rows: [],
        width: '100%',
        fontSize: '11px',
        prefix: defaultPrefix,
        onValueChanged: null,
        onChangeDiscarded: null,
        onViewMode: null,
        onEditMode: null,
        onRowsInitialized: null,
        onRowSelected: null,
        onRowDeselected: null,
        onGridReady: null
    },
    instances = 0,
    createRow = function( settings, prefix ){
        if(!settings.name) {
            $.error('name setting is required!');
        }
        var defaultSettings = $.extend({
                label: '',
                type: '',
                readOnly: false,
                trueLabel: 'true',
                falseLabel: 'false',
                yesNoValueMode: 'boolean',
                validate: 'none'
            }, settings),
            row = {
                name: defaultSettings.name,
                label: defaultSettings.label,
                type: defaultSettings.type,
                readOnly: defaultSettings.readOnly
            },
            html = null,
            value = null,
            valueText = null,
            addSelectRowListener = function(flag) {
                if(typeof flag === 'undefined' || flag) {
                    $(html).find('td').on('selectRow', onSelectRow);
                    $(html).on("click", function(){
                        $(this).find("td:last").trigger("selectRow");
                    });
                } else {
                    $(html).find('td').off('selectRow', onSelectRow);
                    $(html).off("click");
                }
            },
            addDeselectRowListener = function(flag) {
                if(typeof flag === 'undefined' || flag) {
                    $(html).find('td').on('focusout', onDeselectRow);
                } else {
                    $(html).find('td').off('focusout', onDeselectRow);
                }
            },
            onSelectRow = function(){
                var row = $(this).parent(),
                    obj = row.data("rowObject");

                addSelectRowListener(false);

                if(typeof obj.onSelected === 'function'){
                    obj.onSelected.call(obj);
                }

                if(obj.readOnly) {
                    $(html).find('td:last div').focus();
                    addDeselectRowListener();
                    return;
                }
                obj.editMode();
                addDeselectRowListener();
            },
            onDeselectRow = function() {
                var row = $(this).parent(),
                    obj = row.data("rowObject");
                addDeselectRowListener(false);
                obj.editMode(false, false);
                addSelectRowListener();
                if(typeof obj.onRowDeselected === 'function') {
                    obj.onRowDeselected.call(obj);
                }
            },
            setViewModeEvents = function(){
                $(this.getHTML()).find("td:last div").on("focus", function(){
                    $(this.parentNode).trigger("selectRow");
                }).on('keydown', function(e){
                    e.stopPropagation();
                    if(e.keyCode === 13)
                        $(this.parentNode).trigger("selectRow");
                });
            },
            getCurrentSelectedValue = function(){
                var selectedValue = $(html).find('td:last > *').val();

                //we get the current selected value
                switch(this.type){
                    case 'text':
                        selectedValue = defaultSettings.validate === 'integer'?parseInt(selectedValue, 10):selectedValue;
                        break;
                    case 'yesNo':
                        switch(defaultSettings.yesNoValueMode){
                            case 'int':
                                selectedValue = parseInt(selectedValue, 10);
                                break;
                            case 'boolean':
                                selectedValue = selectedValue === "true"?true:false;
                        }
                        break;
                }

                return selectedValue;
            },
            discardChanges = function(){
                if(!this.readOnly) {
                    var valueDiscarded = getCurrentSelectedValue.call(this), 
                        currentValue = this.getValue();
                    this.editMode(false);
                    if(typeof this.onChangeDiscarded === 'function' && valueDiscarded !== currentValue) {
                        this.onChangeDiscarded.call(this, {
                            valueDiscarded: valueDiscarded,
                            currentValue: currentValue
                        });
                    }
                }
            };

        row.onValueChanged = null;

        row.onSelected = null;

        row.onEditMode = null;

        row.onViewMode = null;

        row.onChangeDiscarded = null;

        row.onRowDeselected = null;

        row.getValue = function() {
            return value;
        };

        row.updateValueText = function() {
            valueText = this.getValue();
            switch (this.type) {
                case 'text':
                    valueText = value.toString();
                    break;
                case 'yesNo':
                        valueText = value?defaultSettings.trueLabel:defaultSettings.falseLabel;
                    break;
                case 'select':
                    if(!defaultSettings.options) {
                        valueText = "";
                        break;
                    }
                    for( i = 0; i < defaultSettings.options.length; i++) {
                        if (defaultSettings.options[i].value === valueText) {
                            valueText = defaultSettings.options[i].label;
                            break;
                        }
                    }
                    break;
            }
        };

        row.getValueText = function() {
            return valueText;
        };

        row.getHTML = function() {
            if(!html) {
                var value = this.getValueText(), i, that = this;
                html = document.createElement('tr');
                
                $(html).append('<td class="'+prefix+'-first-col"><div style="width:0px">'+this.label+'</div></td>'+
                    '<td class="'+prefix+'-second-col"><div tabindex="0" style="width:0px">'+(value?value:'')+'</div></td>');
                $(html).data("rowObject", this);
                addSelectRowListener(true);//$(html).find('td').on('selectRow', onSelectRow);
                //$(html).on("focusout", onDeselectRow);
                setViewModeEvents.call(this);
            }

            return html;
        };

        row.save = function(){
            if(defaultSettings.readOnly) {
                return;
            }
            var previousValue = this.getValue(),
                data,
                newValue = getCurrentSelectedValue.call(this);
            if(defaultSettings.validate === 'integer' && this.type === 'text') {
                previousValue = parseInt(previousValue, 10);
            }

            if(newValue !== previousValue){
                value = newValue;
                this.updateValueText();
                if(typeof this.onValueChanged === 'function') {
                    this.onValueChanged({
                        row: this,
                        newValue: newValue,
                        previousValue: previousValue
                    });   
                }
            }

            this.editMode(false);
        };

        row.editMode = function(edit, focus){
            var element, val, that = this,
                width = $(html.parentNode).width(),
                fontSize = parseInt($(html).css('font-size'),10),
                availableWidth = (width/2)-(fontSize*1.82);

            if(typeof edit === 'undefined' || edit){
                switch(this.type) {
                    case 'text':
                        element = $('<input>').addClass('input-small').attr({type:"text"}).val(this.getValueText()).css({
                            "width": availableWidth
                        });
                        if(defaultSettings.validate === 'integer'){
                            element.on('keydown', function(e){
                                e.stopPropagation();
                                if ( e.keyCode == 46 || e.keyCode == 8 || e.keyCode == 9 || e.keyCode == 27 || e.keyCode == 13 || 
                                    (e.keyCode == 65 && e.ctrlKey === true) || 
                                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                                         return;
                                }
                                else {
                                    if (e.shiftKey || (e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105 )) {
                                        e.preventDefault(); 
                                    }   
                                }
                            });
                        }
                        break;
                    case 'select':
                        element = $('<select>').css({
                            width: "100%"
                        });
                        for(i=0; i<this.options.length; i++)
                        {
                            name = this.options[i].label;
                            element.append($('<option label="'+name+'" value="'+this.options[i].value+'" '+((this.options[i].value === this.getValue())?'selected="selected"':'')+' '+(this.options[i].disabled?'disabled':'')+' >'+name+'</option>'));
                        }
                        break;
                    case 'yesNo':
                        if(typeof value === 'string'){
                            val = value;
                            val = val === 'true'?true:false;
                        } else {
                            val = !!value;
                        }
                        element = $('<select>').css({
                            width: "100%"
                        });
                        element.append($('<option label="'+defaultSettings.trueLabel+'" value="'+(defaultSettings.yesNoValueMode === 'int'?1:'true')+'" '+(val?'selected="selected"':'')+'>'+defaultSettings.trueLabel+'</option>'));
                        element.append($('<option label="'+defaultSettings.falseLabel+'" value="'+(defaultSettings.yesNoValueMode === 'int'?0:'false')+'" '+(!val?'selected="selected"':'')+'>'+defaultSettings.falseLabel+'</option>'));
                }
                element.on('focusout', function(){
                    that.save();
                }).on("click", function(e){
                    e.stopPropagation();
                }).on("keydown", function(e){
                    e.stopPropagation();
                    if(e.keyCode === 13)
                    {
                        that.save();
                    }else if(e.keyCode === 27){
                        discardChanges.call(that);
                    }
                });
                try{
                    $(html).find('td:last').empty();
                }catch(e){};
                $(html).find('td:last').append(element);
                element.focus();
                if(typeof this.onEditMode === 'function') {
                    this.onEditMode.call(this);
                }
                element.select();
            } else {
                try{
                    $(html).find('td:last').empty();
                }catch(e){};
                $(html).find('td:last').append($('<div tabindex="0" style="width: '+availableWidth+'px">'+this.getValueText()+'</div>'));
                if(typeof focus === 'undefined' || focus) {
                    $(html).find('td:last div').focus();
                }
                setViewModeEvents.call(this);
                if(typeof this.onViewMode === 'function') {
                    this.onViewMode.call(this);
                }
            }

        };

        prefix = prefix || defaultPrefix;

        if(row.type === 'select') {
            row.options = settings.options || [];
        }
        switch (row.type) {
            case 'text':
                value = (typeof defaultSettings.value !== 'undefined' && defaultSettings.value !== null && (defaultSettings.validate !== 'int'?defaultSettings.value.toString():parseInt(defaultSettings.value,10))) || '';
                break;
            case 'select':
                if(!defaultSettings.options) {
                    value = null;
                    break;
                }
                for( i = 0; i < defaultSettings.options.length; i++) {
                    if ((typeof defaultSettings.options[i].selected === 'boolean' && defaultSettings.options[i].selected) ||
                        (typeof defaultSettings.options[i].selected !== 'boolean' && defaultSettings.options[i].selected === defaultSettings.options[i].value)
                    ) {
                        value = defaultSettings.options[i].value;
                        break;
                    }
                }
                if(value === null && defaultSettings.options[0]) {
                    value = (defaultSettings.options && defaultSettings.options[0].value) || null;
                }
                break;
            case 'yesNo':
                if(typeof defaultSettings.value === 'string'){
                    value = defaultSettings.value.toLowerCase();
                    value = value === 'true'?true:false;
                } else {
                    value = !!defaultSettings.value;
                }
                switch(defaultSettings.yesNoValueMode){
                    case 'int':
                        value = value?1:0;
                        break;
                    case 'literal':
                        value = value?'true':'false';
                }
        }

        row.updateValueText();
        return row;
    },
    setWidth = function(width){
        if(typeof width !== 'undefined'){
            var theWidth;
            var fontSize = parseInt($(this).find('table.progrid-table').css("font-size"));
            if(isNaN(fontSize)) {
                fontSize = 0;
            }
            theWidth = $(this).find('table').find('td div').css({
                "width": "0px"
            }).end().css({
                "width": width
            }).width();
            theWidth = (theWidth/2)-(1.82*fontSize);
            $(this).find('td div').css({"width": theWidth});
        }
    },
    appendRow = function (row) {
        var i, j, aux, flag = false,
            rows = this.find('tr'), name;
        for(i = 0; i < rows.length; i++){
            name = $(rows[i]).data('rowObject').label;
            for(j=0; j < name.length; j++) {
                if(row.label === "" || row.label.charAt(j) < name.charAt(j)) {
                    flag = true;
                }
                break;
            }
            if(flag) {
                break;
            }
        }
        if(rows[i]) {
            $(rows[i]).before(row.getHTML());
        } else {
            this.append(row.getHTML());
        }
    },
    buildTable = function(settings){
        var $this = $(this),
            table = $(document.createElement('table')),
            i, row, body;
        table
            .css({"width": settings.width, "font-size": settings.fontSize})
            .addClass('progrid-table')
            .attr({'id': settings.prefix+'-propTable-'+instances, cellpadding:0, cellspacing:0, border:0});
        table.append($('<thead>').append($('<tr>').append('<th>Name</th>').append('<th>Value</th>')));
        body = $('<tbody>');

        for(i = 0; i < settings.rows.length; i++){
            row = createRow(settings.rows[i]);
            row.onSelected = function(){
                $(this.getHTML()).addClass('row_selected');
                if(typeof settings.onRowSelected === 'function') {
                    settings.onRowSelected.call($this.get(0), {
                        id: settings.id,
                        fieldName: this.name, 
                        fieldLabel: this.label,
                        fieldType: this.type,
                        value: this.getValue()
                    });
                }
            };
            row.onViewMode = function(){
                if(typeof settings.onViewMode === 'function') {
                    settings.onViewMode.call($this.get(0), {
                        id: settings.id,
                        fieldName: this.name, 
                        fieldLabel: this.label,
                        fieldType: this.type,
                        value: this.getValue()
                    });
                }
            };
            row.onEditMode = function(){
                if(typeof settings.onEditMode === 'function') {
                    settings.onEditMode.call($this.get(0), {
                        id: settings.id,
                        fieldName: this.name, 
                        fieldLabel: this.label,
                        fieldType: this.type,
                        value: this.getValue()
                    });
                }
            };
            row.onRowDeselected = function(){
                $(this.getHTML()).removeClass('row_selected');
                if(typeof settings.onRowDeselected === 'function') {
                    settings.onRowDeselected.call($this.get(0), {
                        id: settings.id,
                        fieldName: this.name,
                        fieldLabel: this.label,
                        fieldType: this.type,
                        value: this.getValue()
                    });
                }
            };
            row.onChangeDiscarded = function(data){
                if(typeof settings.onChangeDiscarded === 'function') {
                    settings.onChangeDiscarded.call($this.get(0), {
                        id: settings.id,
                        fieldName: this.name,
                        fieldLabel: this.label,
                        fieldType: this.type,
                        currentValue: data.currentValue,
                        discardedValue: data.valueDiscarded
                    });
                }
            };
            row.onValueChanged = function(data) {
                if(typeof settings.onValueChanged === 'function') {
                    settings.onValueChanged.call($this.get(0), {
                        id: settings.id,
                        fieldName: this.name,
                        fieldLabel: this.label,
                        fieldType: this.type,
                        value: data.newValue,
                        previousValue: data.previousValue
                    });
                }
            };
            $(row.getHTML()).addClass(i%2===0?'odd':'even');
            appendRow.call(body, row);
        }

        table.append(body);
        if(typeof settings.onRowsInitialized === 'function') {
            settings.onRowsInitialized.call($this.get(0), {
                rows: settings.rows
            });
        }

        $this.append(table);
    },
    methods = {
        /**
         * method to initialize the grid.
         * @param  {object} settings        Object that contains the settings for the grid creation,
         *                                  this settings are:
         *                                  {
         *                                      id: []
         *                                  }
         *                                  id: an identifier for the element related to the properties on the grid
         *                                  width: the width for the grid, defaults to 'auto'
         *                                  rows: an objects array, each object have the settings for every field in the grid, 
         *                                         this settings may vary depend on the type of field, 
         *                                         however, there are 3 settings that are used in all types, they are:
         *                                         
         *                                         name: the name for the field, always required,
         *                                         label: the text show as label for the field
         *                                         type: the field type, it can be 'text', 'selection', 'yesNo',
         *                                         readOnly: a boolean that indicates if the field will be a read only item
         *
         *                                         the other settings are:
         *
         *                                          value: inicates the initial value for the field.
         *                                              it can be applied only in text and yesNo type fields.
         *                                          validate: it can contain the value 'integer' for admit just integer values, 
         *                                              it can applied only in text type field. default to 'none'
         *                                          options: an objects array, only for select type field, 
         *                                              each object specify the settings for every option in the select type field, 
         *                                              the structure for each object is:
         *                                                  label: the label to show in the option,
         *                                                  value: the value for the option,
         *                                                  selected: an boolean or string that indicates id the option is selected, 
         *                                                      if the value is a string then it is compared with the value field, 
         *                                                      if they are identical then the option is selected.
         *                                           trueLabel: only for yesNo type field, a string specifies the label for the true option
         *                                           falseLabel:  only for yesNo type field, a string that specifies the label for the false option
         *                                           yeaNoValueMode: only for yesNo type field, it can be 'boolean', 'int', 'literal'
         *                                                   'boolean' returns javascript boolean values (true, false)
         *                                                   'int' returns javascript integer values (0, 1)
         *                                                   'literal' returns javascript strings ('true', 'false')
         *                                                the value field for the select yesNo typoe can be boolean, int or string type, 
         *                                                it will converted internally to the right type 
         *                                   prefix: a string to be used in the class name for the elements on grid,
         *                                   onViewMode: callback to be executed when some field of the grid enters to view mode,
         *                                   onEditMode: callback to be executed when some field of the grid enters to edit mode,        
         *                                   onRowsInitialized:  callback to be executed when all fields of the grid are built,        
         *                                   onRowSelected:  callback to be executed when some field of the grid is selected,        
         *                                   onRowDeselected:  callback to be executed when some field of the grid is deselected,        
         *                                   onGridReady: callback to be executed when the grid is built and ready to use
         *                                          
         * @return {jQuery object}          jQuery object the plugin was invoked on
         */
        init : function( settings ) {
            settings = $.extend({}, defaultSettings, settings);
            return this.each(function(){
                instances++;
                $(this).empty();
                buildTable.call(this, settings);
                setWidth.call(this, settings.width);
                $(this).trigger('gridready');
                if(typeof settings.onGridReady === 'function') {
                    settings.onGridReady.call(this);
                }
            });
        },
        /**
         * jQuery function to set the grid width
         * @param {number|string} width the width value for the grid, it can be in css format (i.e. '300px', 'auto')
         */
        setWidth: function(width){
            setWidth.call(this, width);
        }
    };

    $.fn.progrid = function( method ) {
        if( methods[method] ) {
            return methods[method].apply(this, Array.prototype.slice.call( arguments, 1 ));
        } else if( typeof method === 'object' || !method ) {
            return methods.init.apply(this, arguments);
        } else {

        }
    };


})( jQuery );