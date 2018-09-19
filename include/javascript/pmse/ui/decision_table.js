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
// jscs:disable
var PMSE = PMSE || {};
    var DecisionTable = function(options) {
        PMSE.Element.call(this, {id: options.id});
        this.base_module = null;
        this.hitType = null;
        this.dom = null;
        this.proxy = null;
        this.conditions = null;
        this.conclusions = null;
        this.decisionRows = null;
        this.rows = null;
        this.width = null;
        this.onAddColumn = null;
        this.onRemoveColumn = null;
        this.onAddRow = null;
        this.onRemoveRow = null;
        this.onChange = null;
        this.onDirty = null;
        this.showDirtyIndicator = null;
        this.isDirty = null;
        this.conditionFields = [];
        this.conditionCombos = {};
        this.conditionFieldsReady = false;
        this.conclusionFields = [];
        this.conclusionCombos = {};
        this.conclusionFieldsReady = false;
        this.correctlyBuilt = false;
        this.globalCBControl = null;
        this.globalDDSelector = null;
        this.moduleFieldSeparator = "|||";
        this._currencies = [];
        this._dateFormat = null;
        this._timeFormat = null;
        this._isApplyingColumnScrolling = null;
        this.invalidFieldAlertKey = 'DecisionTableInvalidField';
        this.conditionColumnClassName = 'decision-table-condition-column';
        this.conclusionColumnClassName = 'decision-table-conclusion-column';
        this.closeElementClassName = 'decision-table-close-element';
        this.addColumnClassName = 'decision-table-add-button';
        this.decisionTableRowClassName = 'decision-table-row';
        this.decisionTableBodyClassId = '#decision-table-body';
        this.decisionTableTemplate = null;
        this.decisionRowTemplate = null;
        this.emptyCellTemplate = null;
        this.addConclusionButtonOffset = 30;
        DecisionTable.prototype.initObject.call(this, options || {});
    };

    DecisionTable.prototype = new PMSE.Element();

    DecisionTable.prototype.type = 'DecisionTable';

    DecisionTable.LANG_MODULE = 'pmse_Business_Rules';

    DecisionTable.prototype.initObject = function(options) {
        var defaults = {
            proxy: new SugarProxy(),
            restClient: null,
            base_module: "",
            type: 'multiple',
            width: 'auto',
            rows: 0,
            container: null,
            columns: {
                conditions: [],
                conclusions: []
            },
            ruleset: [],
            onAddColumn: null,
            onRemoveColumn: null,
            onChange: null,
            showDirtyIndicator: true,
            currencies: [],
            dateFormat: "YYYY-MM-DD",
            timeFormat: "H:i"

        }, that = this;

        $.extend(true, defaults, options);

        this.dom = {};
        this.conclusions = [];
        this.conditions = [];
        this.decisionRows = 0;
        this.onAddColumn = defaults.onAddColumn;
        this.onRemoveColumn = defaults.onRemoveColumn;
        this.onChange = defaults.onChange;
        this.rows = parseInt(defaults.rows, 10);

        // first 2 cols are for index and checkbox
        this.preConditionsCols = 2;
        // after Conditions end and before Conclusions begin
        // there is a column '+' for adding a condition
        this.preConclusionsCols = 1;
        this.setCurrencies(defaults.currencies)
            .setDateFormat(defaults.dateFormat)
            .setTimeFormat(defaults.timeFormat)
            .setProxy(defaults.proxy/*, defaults.restClient*/)
            .setBaseModule(defaults.base_module)
            .setHitType(defaults.type);

        this.auxConclusions = defaults.columns.conclusions;
        this.auxConditions = defaults.columns.conditions;
        this.rules = defaults.ruleset;

        this.globalCBControl = new ExpressionControl({
            matchOwnerWidth: false,
            width: 250,
            allowInput: true,
            itemContainerHeight: 70,
            dateFormat: this._dateFormat,
            timeFormat: this._timeFormat,
            appendTo: jQuery("#businessrulecontainer").get(0),
            currencies: this._currencies,
            useOffsetLeft: true
        });

        this.globalDDSelector = new DropdownSelector({
            matchOwnerWidth: true,
            adjustWidth: false
        });

        this.getFields();
    };

    DecisionTable.prototype.setCurrencies = function (currencies) {
        this._currencies = currencies;
        if (this.globalCBControl) {
            this.globalCBControl.setCurrencies(this._currencies);
        }
        return this;
    };

    DecisionTable.prototype.setDateFormat = function (dateFormat) {
        if (this.globalCBControl) {
            this.globalCBControl.setDateFormat(dateFormat);
        }
        this._dateFormat = dateFormat;
        return this;
    };

    DecisionTable.prototype.setTimeFormat = function (timeFormat) {
        if (this.globalCBControl) {
            this.globalCBControl.setDateFormat(timeFormat);
        }
        this._timeFormat = timeFormat;
        return this;
    };

    DecisionTable.prototype.setShowDirtyIndicator = function (show) {
        this.showDirtyIndicator = !!show;
        return this;
    };

    DecisionTable.prototype.getIsDirty = function () {
        return this.isDirty;
    };

    DecisionTable.prototype.setIsDirty = function(dirty, silence) {
        this.isDirty = dirty;
        if (!silence) {
            if(typeof this.onDirty === 'function') {
                this.onDirty.call(this, dirty);
            }
        }
        return this;
    };

    DecisionTable.prototype.onChangeVariableHandler = function() {
        var that = this;
        return function(newVal, oldVal) {
            var valid, cell = this.getHTML(),
                index = $(cell.parentElement).find(cell.tagName.toLowerCase() + '.' + cell.className).index(cell);
            if(this.variableMode === 'condition') {
                valid = that.validateColumn(index, 0);
            } else {
                valid = that.validateColumn(index, 1);
            }

            that.setIsDirty(true);

            if(typeof that.onChange === 'function') {
                that.onChange.call(that, {
                    object: this,
                    newVal: newVal,
                    oldVal: oldVal
                }, valid);
            }
        };
    };

    DecisionTable.prototype.onChangeValueHandler = function() {
        var self = this;
        return function(valueObject, newVal, oldVal) {
            var row, cell, index, indexColumn, isEvaluationVariable, valid;
            var rCell = 0;
            var conLength = 0;
            var preCondCols = self.preConditionsCols;
            var preConcCols = 0;

            isEvaluationVariable = valueObject instanceof DecisionTableValueEvaluation;
            cell = valueObject.getHTML();
            row = cell.parentElement;
            rCell = $(row).children('td').index(cell);
            if (!isEvaluationVariable) {
                conLength = self.conditions.length;
                preConcCols = self.preConclusionsCols;
            }
            indexColumn = rCell - conLength - preCondCols - preConcCols;
            index = $(row.parentElement).children('tr.' + self.decisionTableRowClassName).index(row);

            valid = self.validateColumn(indexColumn, isEvaluationVariable ? 0 : 1);
            if (valid.valid) {
                valid = self.validateRow(index);
            }

            self.setIsDirty(true);
            if (typeof self.onChange === 'function') {
                self.onChange.call(self, {
                    object: valueObject,
                    newVal: newVal,
                    oldVal: oldVal
                }, valid);
            }
        };
    };

    DecisionTable.prototype.removeAllConclusions = function() {
        while(this.conclusions.length) {
            this.conclusions[0].remove(true);
        }

        return this;
    };

    DecisionTable.prototype.removeAllConditions = function() {
        while(this.conditions.length) {
            this.conditions[0].remove(true);
        }
        return this;
    };

    DecisionTable.prototype.setConditions = function(conditions) {
        var i;
        this.removeAllConditions();
        for(i = 0; i < conditions.length; i+=1) {
            this.addCondition(conditions[i]);
        }
        return this;
    };
    DecisionTable.prototype.setConclusions = function(conclusions) {
        var i;
        this.removeAllConclusions();
        for(i = 0; i < conclusions.length; i+=1) {
            this.addConclusion(!conclusions[i], this.base_module + this.moduleFieldSeparator + conclusions[i]);
        }
        return this;
    };

    /**
     * Scan through the fields list in the current rule set for any invalid fields
     * Toggle save button states and error alert
     * @param {boolean} whether to show error alert
     */
    DecisionTable.prototype.validateFields = function(showAlert) {
        var scanArray = function(input) {
            var i;
            for(i = 0; i < input.length; i++) {
                if(!input[i].fieldValid && (input[i].field != '' || input[i].module != '')) {
                    return false;
                }
            }
            return true;
        };
        var valid = scanArray(this.conditions);
        if (valid) {
            valid = scanArray(this.conclusions);
        }
        if (valid) {
            $(".btn-primary[name='project_save_button']").removeClass("disabled");
            $(".btn-primary[name='project_finish_button']").removeClass("disabled");
            App.alert.dismiss(this.invalidFieldAlertKey);
        } else {
            $(".btn-primary[name='project_save_button']").addClass("disabled");
            $(".btn-primary[name='project_finish_button']").addClass("disabled");
            if (showAlert) {
                App.alert.show(this.invalidFieldAlertKey, {
                    level: "error",
                    messages: translate('LBL_PMSE_MESSAGE_REQUIRED_FIELDS_BUSINESSRULES', DecisionTable.LANG_MODULE)
                });
            }
        }
    };

    /**
     * Utility function to construct a module field concatenation to be used
     * mostly as an identifier
     * @param {string} module name
     * @param {string} field name
     * @return {string} concatenation
     */
    DecisionTable.prototype.getModuleFieldConcat = function(module, field) {
        return module + this.moduleFieldSeparator + field;
    };

    DecisionTable.prototype.setRuleset = function(ruleset) {
        var i, j,
            condition_column_helper = {},
            conclusion_column_helper = {},
            aux,
            conditions, conclusions, auxKey;

        //fill the column helper for conditions
        for(i = 0; i < this.conditions.length; i+=1) {
            if(!condition_column_helper[this.conditions[i].select.value]) {
                condition_column_helper[this.conditions[i].select.value] = [i];
            } else {
                condition_column_helper[this.conditions[i].select.value].push(i);
            }
        }

        conclusion_column_helper.result = 0;
        for(i = 1; i < this.conclusions.length; i+=1) {
            conclusion_column_helper[this.conclusions[i].select.value] = i
        }

        for(i = 0; i < ruleset.length; i+=1) {
            conditions = ruleset[i].conditions;
            aux = {};
            for(j = 0; j < conditions.length; j+=1) {
                auxKey = this.getModuleFieldConcat(conditions[j].variable_module, conditions[j].variable_name);
                if(typeof aux[auxKey] === 'undefined') {
                    aux[auxKey] = -1;
                }
                aux[auxKey] +=1;
                if(typeof condition_column_helper[auxKey] !== 'undefined') {
                    this.conditions[condition_column_helper[auxKey][aux[auxKey]]].addValue(conditions[j].value, conditions[j].condition);
                }
            }

            conclusions = ruleset[i].conclusions;
            for(j = 0; j < conclusions.length; j+=1) {
                auxKey = (conclusions[j].conclusion_type === "return" ? "result" : this.getModuleFieldConcat(this.base_module, conclusions[j].conclusion_value));
                if(typeof conclusion_column_helper[auxKey] !== 'undefined') {
                    this.conclusions[conclusion_column_helper[auxKey]].addValue(conclusions[j].value);
                }
            }

            this.addDecisionRow();
        }

        this.validateFields(true);
        this.correctlyBuilt = true;
        return this;
    };

    /**
     * Updates the prev-index data attribute on the decision rows for use in mapping
     */
    DecisionTable.prototype.updatePrevIndex = function() {
        var self = this;
        $(self.decisionTableBodyClassId)
            .children('tr.' + self.decisionTableRowClassName + ':not([data-previndex])')
            .each(function() {
                var curIndex = $(self.decisionTableBodyClassId)
                    .children('tr.' + self.decisionTableRowClassName).index($(this));
                $(this).closest('tr').attr('data-previndex', curIndex);
            });
    };

    DecisionTable.prototype.addDecisionRow = function() {
        var self = this;
        var i;

        if (!(this.conditions.length && this.conclusions.length)) {
            return this;
        }

        if (!this.decisionRowTemplate) {
            this.decisionRowTemplate = this.compileTemplate('decision-row');
        }

        var context = {index : this.decisionRows + 1};

        var row = this.getHTMLFromTemplate(this.decisionRowTemplate, context);

        // Add condition columns
        var conditions = document.createElement("div");
        for (i = 0; i < this.conditions.length; i++) {
            if (!this.conditions[i].values[this.decisionRows]) {
                this.conditions[i].addValue();
            }
            conditions.appendChild(this.conditions[i].getValueHTML(this.conditions[i].values.length - 1));
        }
        $(row).find('#conditions').replaceWith(conditions.children);

        // Add conclusion columns
        var conclusions = document.createElement("div");
        for (i = 0; i < this.conclusions.length; i++) {
            if (!this.conclusions[i].values[this.decisionRows]) {
                this.conclusions[i].addValue();
            }
            conclusions.appendChild(this.conclusions[i].getValueHTML(this.conclusions[i].values.length - 1));
        }
        $(row).find('#conclusions').replaceWith(conclusions.children);

        this.decisionRows++;
        if (this.decisionRows === 1) {
            $(row).find('.checkbox-index .checkbox-input').addClass('hide');
        } else {
            $(document).find('.checkbox-index .checkbox-input.hide').removeClass('hide');
        }
        this.dom.decisionTableBody.append(row);

        // attach click event on checkbox to save the current index in data-previndex
        $(row).find('.checkbox-input').click(function() {
            $(this).closest('tr').toggleClass('selected').toggleClass('highlight')
                .find('span, div.expression-container-cell, td.' + self.conclusionColumnClassName)
                .toggleClass('highlight');

            // Handle prev-index data attribute
            self.updatePrevIndex();

            // Visual cue to remove decision table rows
            $('#trash-button').removeClass('decision-table-btn-hidden');

            // Remove visual cue when nothing is selected
            if ($('.checkbox-input:checked').length == 0) {
                $('#trash-button').addClass('decision-table-btn-hidden');
            }
            if ($('.checkbox-input:checked').length === self.decisionRows-1) {
                $(document).find('.checkbox-index:not(.highlight) .checkbox-input').addClass('hide');
            } else {
                $(document).find('.checkbox-index .checkbox-input.hide').removeClass('hide');
            }
        });

        this.attachListeners();

        return this;
    };

    DecisionTable.prototype.removeDecisionRowWithoutConfirmation = function(index) {
        var i;
        var valid;

        if (index < 0) {
            return this;
        }

        for (i = 0; i < this.conclusions.length; i++) {
            this.conclusions[i].removeValue(index);
        }

        for (i = 0; i < this.conditions.length; i++) {
            this.conditions[i].removeValue(index);
        }

        $(this.dom.decisionTableBody).children('tr.' + this.decisionTableRowClassName).eq(index).remove();

        this.decisionRows--;
        if (this.decisionRows === 1) {
            $(document).find('.checkbox-index .checkbox-input').addClass('hide');
        }
        this.setIsDirty(true);

        // reset the index numbers on decision rows
        var index = 1;
        $(this.decisionTableBodyClassId).children('tr.' + this.decisionTableRowClassName).each(function() {
            $(this).find('span.index').eq(0).text(index);
            index++;
        });

        valid = this.validateColumn();

        if (typeof this.onChange === 'function') {
            this.onChange.call(this, {}, valid);
        }

        return this;
    };

    /**
     * Remove the data attribute previndex after deleting row/s
     */
    DecisionTable.prototype.removePrevIndexOnDeleteRow = function() {
        var self = this;
        $(self.decisionTableBodyClassId)
            .children('tr.' + self.decisionTableRowClassName)
            .each(function() {
                $(this).closest('tr').removeAttr('data-previndex');
            });
    };

    DecisionTable.prototype.removeDecisionRow = function(index) {
        var i,
            ask = false,
            self = this;

        if(this.decisionRows === 1) {
            App.alert.show('mininal-error', {
                level: 'warning',
                messages: translate('LBL_PMSE_MESSAGE_LABEL_MIN_ROWS', DecisionTable.LANG_MODULE),
                autoClose: true
            });
            return this;
        }

        //Check if there are conditions or conditions filled
        for(i = 0; i < this.conditions.length; i+=1) {
            if ((this.conditions[i].values[index]) && (this.conditions[i].values[index].filledValue())) {
                ask = true;
                break;
            }
        }
        if (!ask) {
            for(i = 0; i < this.conclusions.length; i+=1) {
                if ((this.conclusions[i].values[index]) && (this.conclusions[i].values[index].filledValue())) {
                    ask = true;
                    break;
                }
            }
        }
        if (ask) {
            App.alert.show('message-config-delete-row', {
                level: 'confirmation',
                messages: translate('LBL_PMSE_MESSAGE_LABEL_DELETE_ROW', DecisionTable.LANG_MODULE),
                onConfirm: function() {
                    return self.removeDecisionRowWithoutConfirmation(index);
                },
                onCancel: function() {
                    return this;
                }
            });
        } else {
            return this.removeDecisionRowWithoutConfirmation(index);
        }
    };

    DecisionTable.prototype.removeMultipleDecisionRowsWithoutConfirmation = function() {
        var self = this;
        $(self.dom.decisionTableBody).children('tr.selected').each(function() {
            var removeIndex = $(self.dom.decisionTableBody)
                .children('tr.' + self.decisionTableRowClassName)
                .index(this);
            self.removeDecisionRowWithoutConfirmation(removeIndex);
        });

        this.removePrevIndexOnDeleteRow();
        $('#trash-button').addClass('decision-table-btn-hidden');
    };

    DecisionTable.prototype.removeMultipleDecisionRows = function() {
        var self = this;
        var ask = false;
        var removeIndexes = [];
        var i;
        var j;

        // collect all indexes to be removed
        $(self.dom.decisionTableBody).children('tr.selected').each(function() {
            var removeIndex = $(self.dom.decisionTableBody)
                .children('tr.' + self.decisionTableRowClassName)
                .index(this);
            removeIndexes.push(removeIndex);
        });

        if (removeIndexes.length == 0) {
            return;
        }
        if (this.decisionRows === removeIndexes.length) {
            App.alert.show('mininal-error', {
                level: 'warning',
                messages: translate('LBL_PMSE_MESSAGE_LABEL_MIN_ROWS', DecisionTable.LANG_MODULE),
                autoClose: true
            });
            return this;
        }

        // Check if there are conditions or conclusions filled
        for (j = 0; !ask && j < removeIndexes.length; j++) {
            for (i = 0; !ask && i < this.conditions.length; i++) {
                if (this.conditions[i].values[removeIndexes[j]].filledValue()) {
                    ask = true;
                }
            }
        }
        for (j = 0; !ask && j < removeIndexes.length; j++) {
            for (i = 0; !ask && i < this.conclusions.length; i++) {
                if (this.conclusions[i].values[removeIndexes[j]].filledValue()) {
                    ask = true;
                }
            }
        }

        if (ask) {
            App.alert.show('message-config-delete-row', {
                level: 'confirmation',
                messages: translate('LBL_PMSE_MESSAGE_LABEL_DELETE_ROW', DecisionTable.LANG_MODULE),
                onConfirm: function() {
                    self.removeMultipleDecisionRowsWithoutConfirmation();
                },
                onCancel: function() {
                    return this;
                }
            });
        } else {
            self.removeMultipleDecisionRowsWithoutConfirmation();
        }
    };

    DecisionTable.prototype.parseFieldsData = function(data, self) {
        var i, j, fields, combos, module, result = {success : false};
        if (data && data.success) {
            fields = [];
            combos = {};
            for (i = 0; i < data.result.length; i += 1) {
                module = data.result[i];
                for (j = 0; j < module.fields.length; j += 1) {
                    fields.push({
                        label: module.fields[j].text,
                        value: module.fields[j].value,
                        type: module.fields[j].type,
                        moduleText: module.text,
                        moduleValue: module.value
                    });
                    //Maybe backend shouldn't send the optionItem field if doesn't apply to the field.
                    if (module.fields[j].optionItem !== "none") {
                        combos[module.value + self.moduleFieldSeparator + module.fields[j].value] = module.fields[j].optionItem;
                    } else if (module.fields[j].type === 'Checkbox') {
                        combos[module.value + self.moduleFieldSeparator + module.fields[j].value] = {
                            checked: translate('LBL_PMSE_DROP_DOWN_CHECKED', 'pmse_Business_Rules'),
                            unchecked: translate('LBL_PMSE_DROP_DOWN_UNCHECKED', 'pmse_Business_Rules')
                        };
                    }
                }
            }
            result.fields = fields;
            result.combos = combos;
            result.success = true;
        }
        return result;
    };

    DecisionTable.prototype.finishGetFields = function(defaultValue, self) {
        self.setConditions(self.auxConditions);
        if (!self.conditions.length) {
            self.addCondition(defaultValue);
        }
        self.setConclusions(self.auxConclusions);
        if (!self.conclusions.length) {
            self.addConclusion(true);
        }
        self.setRuleset(self.rules);
        if(!self.decisionRows) {
            self.addDecisionRow();
        }

        self.makeDecisionRowsSortable();

        App.alert.dismiss('upload');
        self.setIsDirty(false);
    };

    /*
     * Re-order multi-select elements with drag and drop functionality for Decision Rows
     */
    DecisionTable.prototype.makeDecisionRowsSortable = function() {
        var self = this;
        // Variables used to stop the helper from being dragged over the end of the table.
        // Defined here so it can be set at drag start and used while sorting.
        var tableBottomPosition;
        var tableTopPosition;
        $(self.decisionTableBodyClassId).sortable({
            cancel: 'tr:not(.selected)',
            items: 'tr.' + self.decisionTableRowClassName,
            connectWith: 'table',
            cursor: 'move',
            delay: 150,
            revert: 0,
            axis: 'y',
            scroll: true,
            cursorAt: {bottom: 0},

            // Manually force it to not go over.
            sort: function(event, ui) {

                //Check if we've gone over the ends of the table.
                if (ui.position.top > tableBottomPosition) {
                    ui.helper.css({top: tableBottomPosition});
                    // Move the placeholder to the bottom.
                    ui.placeholder.appendTo(self.dom.decisionTableBody);
                }
                else if (ui.position.top < tableTopPosition) {
                    ui.helper.css({top: tableTopPosition});
                    // Move the placeholder to the top.
                    ui.placeholder.insertAfter(self.dom.decisionRowHeader);
                }
            },

            // In order to do multi-select drag and drop:
            // 1. create a custom helper with the selected items
            // 2. hide the items while dragging
            // 3. manually append the selected items upon receive
            helper: function(e, item) {
                if (!item.hasClass('selected')) {
                    item.addClass('selected');
                }
                // Clone selected items before hiding
                var elements = $('#businessruledesigner .selected').not('.ui-sortable-placeholder').clone();
                // Hide selected items
                item.siblings('.selected').addClass('hidden').css('display', 'none');

                // Use the widths of the header row to set widths of the cloned td
                var widths = [];
                $('#decision-row-header').children('td').each(function(index) {
                    widths[index] = $(this).width();
                });
                $(elements).each(function(elementIndex) {
                    $(elements).eq(elementIndex).children('td').each(function(index) {
                        $(this).width(widths[index]);
                    });
                });

                // Combine the cloned rows for multi-select drag and drop
                var helper = $('<tr/>');
                // Force its width to match the rows.
                helper.width(elements.eq(0).width());
                return helper.append(elements);
            },

            start: function (e, ui) {
                var elements = ui.item.siblings('.selected.hidden').not('.ui-sortable-placeholder');
                // Store the selected items to item being dragged
                ui.item.data('items', elements);

                // Calculate how far the helper can be dragged down.
                tableBottomPosition = self.dom.decisionTable.height() -
                    // Account for larger helpers by finding out how many rows are in it.
                    (ui.helper.height() * ui.helper.children().size()) +
                    // Allow for dragging over the footer to give more leeway.
                    (self.dom.businessRulesFooter.height());

                // Calculate how far the helper can be dragged up.
                tableTopPosition = self.dom.businessRulesHeader.height() -
                    // Account for larger helpers by finding out how many rows are in it.
                    (ui.helper.height() * ui.helper.children().size()) +
                    // Account for the two rows of header stuff at the top.
                    (self.dom.decisionRowHeader.height() * 2);

            },

            receive: function (e, ui) {
                // Manually add the selected items before the one actually being dragged
                ui.item.before(ui.item.data('items'));
            },

            stop: function(e, ui) {
                var elements = ui.item.siblings('.selected');
                $(elements).each(function() {
                    var prevIndex = $(this).attr('data-previndex');
                    var itemIndex = $(ui.item).attr('data-previndex');
                    if (prevIndex < itemIndex) {
                        ui.item.before(this);
                    } else {
                        ui.item.after(this);
                    }
                });

                // Show the selected items after the operation
                ui.item.siblings('.selected').removeClass('hidden').css('display', '');

                // Save the mappings between old and new index of the moved elements
                var decisionRowMappings = [];

                $(self.decisionTableBodyClassId + ' > tr.' + self.decisionTableRowClassName).each(function() {
                    var curIndex = $(self.decisionTableBodyClassId + ' > tr.'
                        + self.decisionTableRowClassName).index(this);
                    var oldIndex = $(this).attr('data-previndex');
                    $(this).attr('data-newindex', curIndex);
                    if ($.isNumeric(curIndex) && $.isNumeric(oldIndex) && (curIndex >= 0) && (oldIndex >= 0)) {
                        decisionRowMappings[curIndex] = oldIndex;
                    }
                    $(this).removeAttr('data-previndex').removeAttr('data-newindex');
                });
                self.sortDecisionRows(decisionRowMappings);

                // In order to allow multiple moves per save, we needs to reset
                // the prev-index data attribute
                self.updatePrevIndex();

                $(document).find('.checkbox-index .checkbox-input.hide').removeClass('hide');
            }
        });
    };

    /*
     * After the decision rows have been moved around in the UI, we need to change
     * the actual conditions and conclusions variables to reflect the UI changes.
     * @param decisionRowMappings (Mapping of new index values to old ones)
     */
    DecisionTable.prototype.sortDecisionRows = function(decisionRowMappings) {
        var drLength = decisionRowMappings.length;
        var conditionsLength = this.conditions.length;
        var conclusionsLength = this.conclusions.length;
        var i;
        var j;

        for (j = 0; j < conditionsLength; j++) {
            var newConditionValues = [];
            for (i = 0; i < drLength; i++) {
                newConditionValues[i] = this.conditions[j].values[decisionRowMappings[i]];
            }
            this.conditions[j].values = newConditionValues;
        }

        for (j = 0; j < conclusionsLength; j++) {
            var newConclusionValues = [];
            for (i = 0; i < drLength; i++) {
                newConclusionValues[i] = this.conclusions[j].values[decisionRowMappings[i]];
            }
            this.conclusions[j].values = newConclusionValues;
        }

        var index = 1;
        $(this.decisionTableBodyClassId).children('tr.' + this.decisionTableRowClassName).each(function() {
            $(this).find('span.index').eq(0).text(index);
            index++;
        });
    };

    DecisionTable.prototype.getConditionFields = function(defaultValue) {
        var self = this;
        this.proxy.setUrl('ProcessBusinessRules/fields/conditions');
        this.proxy.getData({base_module: this.base_module, call_type: 'BRR'}, {
            success: function(data) {
                var result = self.parseFieldsData(data, self);
                if (result.success) {
                    self.conditionFields = result.fields;
                    self.conditionCombos = result.combos;
                    self.conditionFieldsReady = true;
                    if (self.conclusionFieldsReady) {
                        self.finishGetFields(defaultValue, self);
                    }
                }
            }
        });
    };

    DecisionTable.prototype.getConclusionFields = function(defaultValue) {
        var self = this;
        this.proxy.setUrl('ProcessBusinessRules/fields/conclusions');
        this.proxy.getData({base_module: this.base_module, call_type: 'BR'}, {
            success: function(data) {
                var result = self.parseFieldsData(data, self);
                if (result.success) {
                    self.conclusionFields = result.fields;
                    self.conclusionCombos = result.combos;
                    self.conclusionFieldsReady = true;
                    if (self.conditionFieldsReady) {
                        self.finishGetFields(defaultValue, self);
                    }
                }
            }
        });
    };

    DecisionTable.prototype.getFields = function(defaultValue) {
        if (!this.conditionFieldsReady || !this.conclusionFieldsReady) {
            App.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
            if (!this.conditionFieldsReady) {
                this.getConditionFields(defaultValue);
            }
            if (!this.conclusionFieldsReady) {
                this.getConclusionFields(defaultValue);
            }
        }
    };

    DecisionTable.prototype.setProxy = function(proxy/*, restClient*/) {
        this.proxy = proxy;
        return this;
    };

    DecisionTable.prototype.setBaseModule = function(base_module) {
        this.base_module = base_module;
        return this;
    };

    DecisionTable.prototype.setHitType = function(hitType) {
        this.hitType = hitType;
        return this;
    };

    DecisionTable.prototype.onBeforeVariableOpenPanelHandler = function () {
        var that = this;
        return function (column, decisionTableValue) {
            var conditionHeader = that.dom.firstCondition;
            var conclusionHeader = that.dom.firstConclusion;
            var isDTVEval = decisionTableValue instanceof DecisionTableValueEvaluation;
            var headerContainer = isDTVEval ? conditionHeader : conclusionHeader;
            var tableContainer = isDTVEval ? conditionHeader : conclusionHeader;
            var headerPosition = getRelativePosition(column.html, headerContainer);
            var headerWidth = $(column.html).innerWidth();
            var leftWidth = getRelativePosition(this.html, that.html).left + that.globalCBControl.width;
            var boundingBoxWidth = $(that.html).outerWidth();

            that.globalCBControl.setAlignWithOwner("left");
            if (headerPosition.left < 0) {
                that._isApplyingColumnScrolling = true;
                tableContainer.scrollLeft += headerPosition.left;
            } else if (headerPosition.left > $(headerContainer).innerWidth()) {
                that._isApplyingColumnScrolling = true;
                tableContainer.scrollLeft = headerPosition.left + headerWidth +
                    headerContainer.scrollLeft - $(headerContainer).innerWidth();
            }
            if (leftWidth > boundingBoxWidth) {
                var offset = leftWidth - boundingBoxWidth + that.addConclusionButtonOffset;
                that.globalCBControl.setAlignWithOwner("right");
                that.globalCBControl.setOffsetLeft(offset);
            }
        };
    };

    DecisionTable.prototype.onRemoveVariableHandler = function(array) {
        var that = this, variablesArray = array, valid;
        return function() {
            var x;
            for(var i = 0; i < variablesArray.length; i+=1) {
                if(variablesArray[i] === this) {
                    x = variablesArray[i];
                    variablesArray.splice(i, 1);
                }
            }
            valid = that.validateRow();
            that.setIsDirty(true);
            if(typeof that.onChange === 'function') {
                that.onChange.call(that, {}, valid);
            }
        };
    };


    DecisionTable.prototype.addCondition = function(defaultValue) {
        var condition = new DecisionTableVariable({
            parent: this,
            field: defaultValue || null,
            fields: this.conditionFields,
            combos: this.conditionCombos,
            inputFields: this.conditionFields,
            variableMode: 'condition'
        }), i, html;
        var conditionsColSpan;

        condition.onBeforeValueOpenPanel = this.onBeforeVariableOpenPanelHandler();
        condition.onRemove = this.onRemoveVariableHandler(this.conditions);
        condition.onChangeValue = this.onChangeValueHandler();
        condition.onChange = this.onChangeVariableHandler();
        if (this.html) {
            // First add the condition col to the decision row header
            if (this.conditions.length == 0) {
                this.dom.firstCondition = this.dom.decisionRowHeader
                    .children('td')
                    .eq(this.preConditionsCols);
                this.dom.firstCondition
                    .replaceWith(condition.getHTML());

                // Display a different tool tip if there is only 1 condition remaining
                this.dom.decisionRowHeader
                    .children('td.' + this.conditionColumnClassName)
                    .find('.' + this.closeElementClassName)
                    .eq(0)
                    .attr('title', translate('LBL_PMSE_TOOLTIP_REMOVE_COL_DATA', DecisionTable.LANG_MODULE));
            } else {
                this.dom.decisionRowHeader
                    .children('td.' + this.conditionColumnClassName)
                    .eq(this.conditions.length - 1)
                    .after(condition.getHTML());

                // For more than 1 condition set the tool tip to be "Remove Condition"
                this.dom.decisionRowHeader
                    .children('td.' + this.conditionColumnClassName)
                    .find('.' + this.closeElementClassName)
                    .attr('title', translate('LBL_PMSE_TOOLTIP_REMOVE_CONDITION', DecisionTable.LANG_MODULE));
            }

            conditionsColSpan = this.conditions.length + 2;
            this.dom.conditionsHeader.attr('colspan', conditionsColSpan);
            this.dom.conditionsFooter.attr('colspan', conditionsColSpan);
        }

        this.conditions.push(condition);
        this.proxy.uid = this.base_module || "";

        // Now add the condition col to all decision rows
        for (i = 0; i < this.decisionRows; i++) {
            condition.addValue();
            html = condition.getValueHTML(i);

            this.dom.decisionTableBody
                .children('tr')
                .eq(i + 1)
                .children('td.' + this.conditionColumnClassName)
                .eq(this.conditions.length - 2)
                .after(html);
        }

        this.setIsDirty(true);

        return this;
    };

    DecisionTable.prototype.addConclusion = function(returnType, defaultValue) {
        var conclusion = new DecisionTableVariable({
            isReturnType: returnType,
            variableMode: "conclusion",
            fields: this.conclusionFields,
            combos: this.conclusionCombos,
            inputFields: this.conditionFields,
            field: defaultValue,
            parent: this
        });
        var i;
        var conclusionsColSpan;
        var lastConclusionChild;

        conclusion.onBeforeValueOpenPanel = this.onBeforeVariableOpenPanelHandler();
        conclusion.onRemove = this.onRemoveVariableHandler(this.conclusions);
        conclusion.onChangeValue = this.onChangeValueHandler();
        conclusion.onChange = this.onChangeVariableHandler();

        if (this.html) {
            // First add the conclusion col to the decision header row
            lastConclusionChild = this.conditions.length +
                this.conclusions.length +
                this.preConditionsCols +
                this.preConclusionsCols;

            if (returnType) {
                this.dom.decisionRowHeader
                    .children('td')
                    .eq(lastConclusionChild)
                    .replaceWith(conclusion.getHTML());
            } else {
                this.dom.decisionRowHeader
                    .children('td')
                    .eq(lastConclusionChild - 1)
                    .after(conclusion.getHTML());
            }

            if (this.conclusions.length == 0) {
                this.dom.firstConclusion = this.dom.decisionRowHeader
                    .children('td')
                    .eq(this.conditions.length + this.preConditionsCols + this.preConclusionsCols);
            } else if (this.conclusions.length == 1) {
                // Display a different tool tip if there is only 1 Change Field remaining
                this.dom.decisionRowHeader
                    .children('td.' + this.conclusionColumnClassName)
                    .find('.' + this.closeElementClassName)
                    .eq(0)
                    .attr('title', translate('LBL_PMSE_TOOLTIP_REMOVE_COL_DATA', DecisionTable.LANG_MODULE));
            } else {
                // Display the normal tool tip if there are more Change Fields
                this.dom.decisionRowHeader
                    .children('td.' + this.conclusionColumnClassName)
                    .find('.' + this.closeElementClassName)
                    .eq(0)
                    .attr('title', translate('LBL_PMSE_TOOLTIP_REMOVE_CONCLUSION', DecisionTable.LANG_MODULE));
            }

            conclusionsColSpan = this.conclusions.length + 2;
            this.dom.conclusionsHeader.attr('colspan', conclusionsColSpan);
            this.dom.conclusionsFooter.attr('colspan', conclusionsColSpan);
        }


        this.conclusions.push(conclusion);

        for (i = 0; i < this.decisionRows; i += 1) {
            conclusion.addValue();

            this.dom.decisionTableBody
                .children('tr')
                .eq(i + 1)
                .children('td')
                .eq(lastConclusionChild - 1)
                .after(conclusion.getValueHTML(i));
        }

        this.setIsDirty(true);

        return this;
    };

    DecisionTable.prototype.canBeRemoved = function(obj) {
        var res = false;
        if(obj.parent === this) {
            if(obj.variableMode === 'condition') {
                res = this.conditions.length > 1;
                if(!res) {
                    App.alert.show('mininal-column-error', {
                        level: 'warning',
                        messages: translate('LBL_PMSE_MESSAGE_LABEL_MIN_CONDITIONS_COLS', DecisionTable.LANG_MODULE),
                        autoClose: true
                    });
                }
            } else if (obj.variableMode === 'conclusion') {
                res = this.conclusions.length > 1;
                if(!res) {
                    App.alert.show('mininal-column-error', {
                        level: 'warning',
                        messages: translate('LBL_PMSE_MESSAGE_LABEL_MIN_CONCLUSIONS_COLS', DecisionTable.LANG_MODULE),
                        autoClose: true
                    });
                }
            }
        }
        return res;
    };

    DecisionTable.prototype.createHTML = function() {
        if(this.html) {
            return this.html;
        }

        if (!this.decisionTableTemplate) {
            this.decisionTableTemplate = this.compileTemplate('decision-table');
        }

        var context = {
            conditionsSpan : 2,
            conclusionsSpan : 3,
            conditionsTitle : translate('LBL_PMSE_LABEL_CONDITIONS', DecisionTable.LANG_MODULE),
            returnTitle : translate('LBL_PMSE_LABEL_RETURN_VALUE', DecisionTable.LANG_MODULE),
            conclusionsTitle : translate('LBL_PMSE_LABEL_CONCLUSIONS', DecisionTable.LANG_MODULE),
            removeConditionTitle : '',
            addConditionTitle : translate('LBL_PMSE_TOOLTIP_ADD_CONDITION', DecisionTable.LANG_MODULE),
            removeConclusionTitle : '',
            addConclusionTitle : translate('LBL_PMSE_TOOLTIP_ADD_CONCLUSION', DecisionTable.LANG_MODULE)
        };

        var html = this.getHTMLFromTemplate(this.decisionTableTemplate, context);
        this.dom.businessRulesHeader = $(html).find('tr.business-rules-header').first();
        this.dom.conditionsHeader = $(html).find('#decision-table-conditions-header-column').first();
        this.dom.conclusionsHeader = $(html).find('#decision-table-conclusions-header-column').first();
        this.dom.businessRulesFooter = $(html).find('#business-rules-footer').first();
        this.dom.conditionsFooter = $(html).find('#decision-table-conditions-footer').first();
        this.dom.conclusionsFooter = $(html).find('#decision-table-conclusions-footer').first();
        this.dom.decisionRowHeader = $(html).find('#decision-row-header').first();
        this.dom.trashNode = $(html).find('#trash-button').first();
        this.dom.decisionTableBody = $(html).find('#decision-table-body').first();
        this.dom.addConditionButton = $(html).find('#add-condition-button').first();
        this.dom.addConclusionButton = $(html).find('#add-conclusion-button').first();
        this.dom.addDecisionRowButton = $(html).find('#add-decision-row-button').first();
        this.dom.decisionTable = $(html);

        this.html = html;
        this.setShowDirtyIndicator(this.showDirtyIndicator);

        this.attachTableListeners();
        this.attachListeners();

        return this.html;
    };

    DecisionTable.prototype.attachTableListeners = function() {
        var self = this;
        // Click event for removing multiple decision rows
        this.dom.trashNode.on('click', function() {
            self.removeMultipleDecisionRows();
        });
        this.dom.addConditionButton.on('click', function() {
            self.addCondition();
        });
        this.dom.addConclusionButton.on('click', function() {
            self.addConclusion();
        });
        this.dom.addDecisionRowButton.on('click', function() {
            self.addDecisionRow();
        });
    };

    DecisionTable.prototype.attachListeners = function() {
        var self = this;

        this.dom.decisionTable.on('keydown', 'td', function(e) {
            var index, row = this.parentElement;
            if(e.keyCode === 9) {
                index = $(row.parentElement).find("tr").index(row);
                if(!e.shiftKey) {
                    e.preventDefault();
                    $(self.conditions[0]
                        .getValueHTML(index)[0])
                        .find("span").focus();
                } else if(index > 0){
                    e.preventDefault();
                    $(self.conclusions[that.conclusions.length - 1].getValueHTML(index - 1))
                        .find("span").focus();
                }
            }
        });

        this.dom.decisionTable.on('keydown', 'td', function(e) {
            var index, row = self.parentElement;
            if (e.keyCode === 9) {
                index = $(row.parentElement).find("tr").index(row);
                if ($(row).find("td:last").get(0) === self && !e.shiftKey) {
                    e.preventDefault();
                    $(self.conditions[0].getValueHTML(index)[0]).find("span").focus();
                } else if ($(row).find("td:first").get(0) === self && e.shiftKey && index > 0){
                    e.preventDefault();
                    $(self.conclusions[self.conclusions.length - 1].getValueHTML(index - 1))
                        .find('span').focus();
                }
            }
        });

        // Note: #businessruledesigner is the current element that scrolls.
        // Once the fixed header feature is implemented this might need to be changed.
        $('#businessruledesigner').on('scroll', function() {
            if (self.globalCBControl && self.globalCBControl.isOpen()) {
                self.globalCBControl.close();
            }
            if (self.globalDDSelector && self.globalDDSelector.isOpen()) {
                self.globalDDSelector.close();
            }
        });

        return this;
    };

    DecisionTable.prototype.validateConclusions = function() {
        var i, obj = {};

        for(i = 0; i < this.conclusions.length; i+=1) {
            if(!this.conclusions[i].isReturnType && this.conclusions[i].field && this.conclusions[i].getFilledValuesNum()) {
                if(!obj[this.conclusions[i].field]) {
                    obj[this.conclusions[i].field] = true;
                } else {
                    $(this.conclusions[i].getHTML()).addClass('error');
                    return {
                        valid: false,
                        location: "Conclusion # " + (i + 1),
                        message: translate('LBL_PMSE_BUSINESSRULES_ERROR_CONCLUSIONVARDUPLICATED', DecisionTable.LANG_MODULE)
                    }
                }
            }
            $(this.conclusions[i].getHTML()).removeClass('error');
        }

        return {valid: true};
    };

    DecisionTable.prototype.validateRow = function(index) {
        var start = 0, limit = this.decisionRows,
            rowHasConclusions, rowHasConditions, i, j, defaultRulesets = 0;

        if(typeof index === 'number') {
            start = index;
            limit = index + 1;
        }

        for(i = start; i < limit; i+=1) {
            rowHasConditions = false;
            rowHasConclusions = false;
            //validate if the row has return value conclusion if there are any condition
            for(j = 0; j < this.conditions.length; j+=1) {
                if(this.conditions[j].values[i].filledValue()) {
                    rowHasConditions = true;
                    break;
                }
            }

            if(rowHasConditions) {
                if(!this.conclusions[0].values[i].filledValue()) {
                    $(this.conclusions[0].values[i].getHTML()).addClass("error");
                    return {
                        valid: false,
                        message: translate('LBL_PMSE_MESSAGE_LABEL_EMPTY_RETURN_VALUE', DecisionTable.LANG_MODULE),
                        location: "row # " + (i + 1)
                    };
                } else {
                    rowHasConclusions = true;
                }
            }
            $(this.conclusions[0].values[i].getHTML()).removeClass("error");

            if(!rowHasConclusions) {
                for(j = 0; j < this.conclusions.length; j+=1) {
                    if(this.conclusions[j].values[i].filledValue()) {
                        rowHasConclusions = true;
                        break;
                    }
                }
            }
            if(rowHasConclusions && !rowHasConditions) {
                defaultRulesets += 1;
                if(defaultRulesets > 1) {
                    $(this.dom.conditionsTable).find('tr').eq(i).addClass('error');
                    return {
                        valid: false,
                        message: translate('LBL_PMSE_BUSINESSRULES_ERROR_EMPTYROW', DecisionTable.LANG_MODULE),
                        location: 'row # ' + (i + 1)
                    };
                }
            }
            $(this.dom.conditionsTable).find('tr').eq(i).removeClass('error');
        }

        return {valid: true};
    };

    DecisionTable.prototype.validateColumn = function(index, type) {
        var valid, i, j, variables = [
            {
                type: "condition",
                collection: this.conditions
            }, {
                type: "conclusion",
                collection: this.conclusions
            }
        ];

        $(this.dom.conditionsTable).find('tr').removeClass('error');

        if(typeof index === 'number' && typeof type === 'number') {
            valid = variables[type].collection[index].isValid();
            if(!valid.valid) {
                return {
                    valid: false,
                    message: valid.message,
                    location: variables[type].type + " # " + (index + 1) + (!isNaN(valid.index) ? " - row " + (valid.index + 1) : "")
                };
            }
        } else {
            for(j = 0; j < variables.length; j+=1) {
                for(i = 0; i < variables[j].collection.length; i+=1) {
                    valid = variables[j].collection[i].isValid();
                    if(!valid.valid) {
                        return {
                            valid: false,
                            message: valid.message,
                            location: variables[j].type + " # " + (i + 1) + (!isNaN(valid.index) ? " - row " + (valid.index + 1) : "")
                        };
                    }
                }
            }
        }

        return {valid: true};
    };

    DecisionTable.prototype.isValid = function() {
        var valid;

        if(!this.correctlyBuilt) {
            return {
                valid: false,
                message: translate('LBL_PMSE_BUSINESSRULES_ERROR_INCORRECT_BUILD', DecisionTable.LANG_MODULE)
            };
        }

        valid = this.validateColumn();

        if(!valid.valid) {
            return valid;
        }
        valid = this.validateRow();
        if(!valid.valid) {
            return valid;
        }

        return this.validateConclusions();
    };

    DecisionTable.prototype.getJSON = function() {
        var json = {
            base_module: this.base_module,
            type: this.hitType,
            columns: {
                conditions: [],
                conclusions: []
            },
            ruleset: []
        }, ruleset, conditions, conclusions, i, j, obj, defaultRuleSets = 0, auxKey;

        if(!this.isValid().valid) {
            return null;
        }

        //Add the conditions columns evaluating duplications
        obj = {};
        for(j = 0; j < this.decisionRows; j+=1) {
            for(i = 0; i < this.conditions.length; i+=1) {
                if(this.conditions[i].field && this.conditions[i].values[j].getValue().length) {
                    auxKey = this.conditions[i].module + this.moduleFieldSeparator + this.conditions[i].field;
                    if(!obj[auxKey]) {
                        obj[auxKey] = {
                            max: 0,
                            current: 0
                        };
                    }
                    obj[auxKey].current += 1;
                    if(obj[auxKey].current > obj[auxKey].max) {
                        obj[auxKey].max = obj[auxKey].current;
                    }
                }
            }
            for(i in obj) {
                obj[i].current = 0;
            }
        }
        for(i = 0; i < this.conditions.length; i+=1) {
            auxKey = this.conditions[i].module + this.moduleFieldSeparator + this.conditions[i].field;
            if(obj[auxKey]) {
                for(j = 0; j < obj[auxKey].max; j+=1) {
                    json.columns.conditions.push({
                        module: this.conditions[i].module,
                        field: this.conditions[i].field
                    });
                }
                delete obj[auxKey];
            }
        }


        for(i = 0; i < this.conclusions.length; i+=1) {
            if(this.conclusions[i].isReturnType || (this.conclusions[i].field && this.conclusions[i].getFilledValuesNum())) {
                json.columns.conclusions.push(this.conclusions[i].select ? this.conclusions[i].field : "");
            }
        }

        for(i = 0; i < this.decisionRows; i+=1) {
            ruleset = {
                id: i + 1
            };
            conditions = [];
            conclusions = [];
            for(j = 0; j < this.conditions.length; j+=1) {
                obj = this.conditions[j].getJSON(i);
                if(obj) {
                    conditions.push(obj);
                }
            }
            for(j = 0; j < this.conclusions.length; j+=1) {
                obj = this.conclusions[j].getJSON(i);
                if(obj.value.length) {
                    conclusions.push(obj);
                }
            }
            ruleset.conditions = conditions;
            ruleset.conclusions = conclusions;
            if(!conditions.length) {
                defaultRuleSets += 1;
            }
            if(conditions.length || defaultRuleSets <= 1) {
                json.ruleset.push(ruleset);
            }
        }

        return json;
    };

//DecisionTableVariable
    var DecisionTableVariable = function(options) {
        PMSE.Element.call(this);

        this.parent = null;

        this.fieldName = null;
        this.field = null;
        this.fieldType = null;
        this.module = null;
        this.fieldValid = true;

        this.values = [];
        this.fields = null;
        this.combos = {};
        this.inputFields = null;

        this.variableMode = null;
        this.isReturnType = null;
        this.closeButton = null;

        this.select = null;

        this.onBeforeValueOpenPanel = null;
        this.onRemove = null;
        this.onChange = null;
        this.onChangeValue = null;

        DecisionTableVariable.prototype.initObject.call(this, options);
    };

    DecisionTableVariable.prototype = new PMSE.Element();

    DecisionTableVariable.prototype.initObject = function(options) {
        var defaults = {
            parent: null,

            field: null,

            fields: [],
            combos: {},
            inputFields: [],

            variableMode: "condition",
            isReturnType: false,

            onBeforeValueOpenPanel: null,
            onRemove: null,
            onChange: null,
            onChangeValue: null
        };

        // Do not deep copy here
        $.extend(defaults, options);

        this.parent = defaults.parent;
        this.variableMode = defaults.variableMode;
        this.isReturnType = defaults.isReturnType;

        this.setFields(defaults.fields)
            .setCombos(defaults.combos)
            .setInputFields(defaults.inputFields)
            .setField(defaults.field);

        if (defaults.values) {
            this.setValues(defaults.values);
        }

        this.onBeforeValueOpenPanel = defaults.onBeforeValueOpenPanel;
        this.onRemove = defaults.onRemove;
        this.onChange = defaults.onChange;
        this.onChangeValue = defaults.onChangeValue;
    };

    /**
     * Utility function to retrieve the option tag with a certain value
     * @param {string} the value for the option
     * @return {jQuery elements} matched elements
     */
    DecisionTableVariable.prototype.getOption = function (value) {
        return $(this.select).children("option[value='" + value + "']");
    }

    DecisionTableVariable.prototype.setField = function (newField) {
        var i,
            label,
            option,
            currentField,
            field,
            module,
            moduleFieldConcat;
        if (!this.isReturnType) {
            if (!this.fieldValid) {
                moduleFieldConcat = this.parent.getModuleFieldConcat(this.module, this.field);
                option = this.getOption(moduleFieldConcat);
                if (option.length) {
                    this.select.removeChild(option[0]);
                }
            }
            if (newField) {
                if (typeof newField === 'string') {
                    moduleFieldConcat = newField;
                    field = newField.split(this.parent.moduleFieldSeparator);
                    module = field[0];
                    field = field[1];
                } else {
                    module = newField.module;
                    field = newField.field;
                    moduleFieldConcat = this.parent.getModuleFieldConcat(module, field);
                }
                label = module + ':' + field;
            } else {
                module = '';
                field = '';
                moduleFieldConcat = this.parent.getModuleFieldConcat(module, field);
                label = '';
            }
            this.field = field;
            this.fieldName = null;
            this.fieldType = null;
            this.module = module;
            this.fieldValid = false;
            for (i = 0; i < this.fields.length; i += 1) {
                currentField = this.fields[i];
                if (currentField.value === field && currentField.moduleValue === module) {
                    this.fieldName = currentField.label;
                    this.fieldType = currentField.type;
                    this.fieldValid = true;
                    break;
                }
            }
            if (this.fieldValid) {
                $(this.select).removeClass('field-invalid');
            } else {
                if (this.field == '' && this.module == '') {
                    $(this.select).removeClass('field-invalid');
                } else {
                    $(this.select).addClass('field-invalid');
                }
                option = this.getOption(moduleFieldConcat);
                if (!option.length) {
                    option = this.createHTMLElement('option');
                    option.label = label;
                    option.value = moduleFieldConcat;
                    this.select.insertBefore(option, this.select.firstChild);
                }
            }
            this.select.value = moduleFieldConcat;
            this.parent.validateFields(false);
        }
        return this;
    };

    DecisionTableVariable.prototype.setFields = function(fields) {
        if(fields.push && fields.pop) {
            this.fields = fields;
            if (!this.isReturnType) {
                this.populateSelectElement();
            }
        }
        return this;
    };

    DecisionTableVariable.prototype.setCombos = function (combos) {
        this.combos = combos;
        return this;
    };

    DecisionTableVariable.prototype.setInputFields = function(fields) {
        if(fields.push && fields.pop) {
            this.inputFields = fields;
        }
        return this;
    };

    DecisionTableVariable.prototype.populateSelectElement = function() {
        var i,
            currentGroup,
            optgroup,
            option,
            select,
            label;

        if (this.select) {
            $(this.select).empty();
        }

        select = this.createHTMLElement('select');
        select.className = 'condition-select';

        if (this.fields.length) {
            currentGroup = {};
            for(i = 0; i < this.fields.length; i += 1) {
                if (this.variableMode === 'conclusion' && !this.isReturnType && this.fields[i].value === 'email1') {
                    continue;
                }
                if (this.fields[i].moduleText !== currentGroup.label) {
                    if (this.variableMode === 'conclusion' && this.fields[i].moduleValue !== this.parent.base_module) {
                        break;
                    }
                    currentGroup = document.createElement("optgroup");
                    currentGroup.label = this.fields[i].moduleText;
                    select.appendChild(currentGroup);
                }
                option = this.createHTMLElement('option');
                label = SUGAR.App.lang.get(this.fields[i].label, this.base_module);
                if (typeof label === 'object'){
                    label = this.fields[i].label;
                }
                option.label = label;
                option.value = this.fields[i].moduleValue + this.parent.moduleFieldSeparator + this.fields[i].value;
                option.appendChild(document.createTextNode(label));
                if(this.field === option.value) {
                    option.selected = true;
                }
                currentGroup.appendChild(option);
            }
        }
        this.select = select;

        return this;
    };

    DecisionTableVariable.prototype.setValues = function(values) {
        var i;

        if (typeof values !== "object" || !values.push) {
            return this;
        }

        i = 0;
        if(this.variableMode === 'conclusion') {
            for(i = 0; i < values.length; i += 1) {
                if (typeof values[i] === "string" || typeof values[i] === 'number') {
                    this.values.push(new DecisionTableSingleValue({
                        value: values[i],
                        parent: this,
                        fields: this.inputFields
                    }));
                }
            }
        } else {
            for(i = 0; i < values.length; i += 1) {
                this.values.push(new DecisionTableValueEvaluation({
                    value: values[i].value,
                    operator: values[i].operator,
                    parent: this,
                    fields: this.inputFields
                }));
            }
        }
        return this;
    };

    //DecisionTableVariable.prototype.setName = function(name) {
    //    this.name = name;
    //    return this;
    //};



    DecisionTableVariable.prototype.getValueHTML = function(index) {
        if(this.values[index]) {
            return this.values[index].getHTML();
        }

        return null;
    };

    DecisionTableVariable.prototype.createHTML = function() {
        if (this.html) {
            return this.html;
        }

        if (!this.template) {
            this.template = this.compileTemplate('variable');
        }

        var context = {isCondition : this.variableMode == 'condition', isReturn : this.isReturnType};
        context.title = context.isCondition ?
            translate('LBL_PMSE_TOOLTIP_REMOVE_CONDITION', DecisionTable.LANG_MODULE) :
            translate('LBL_PMSE_TOOLTIP_REMOVE_CONCLUSION', DecisionTable.LANG_MODULE);

        // Since the return label is in the header row of form elements, we need to add it here
        context.returnTitle = translate('LBL_PMSE_LABEL_RETURN_VALUE', DecisionTable.LANG_MODULE);
        var html = this.getHTMLFromTemplate(this.template, context);

        if (!context.isReturn) {
            $(html).find('#select').replaceWith(this.select);
            this.closeButton = $(html).find(':button').first();
        }

        this.html = html;

        this.attachListeners();
        return this.html;
    };

    DecisionTableVariable.prototype.removeWithoutConfirmation = function(index) {
        var i;
        var numDecisionRows;
        var newColspan;

        while (this.values.length) {
            this.values[0].remove();
        }
        this.values = null;
        $(this.html).remove();

        numDecisionRows = $(this.parent.decisionTableBodyClassId).children('tr').length - 1;
        if (this.variableMode == 'condition') {
            // Adjust the Conditions Header Column colspan
            newColspan = $('#decision-table-conditions-header-column').attr('colspan') - 1;
            $(this.parent.dom.conditionsHeader).attr('colspan', newColspan);
            $(this.parent.dom.conditionsFooter).attr('colspan', newColspan);
        } else {
            // Adjust the Conclusions Header Column colspan
            newColspan = $('#decision-table-conclusions-header-column').attr('colspan') - 1;
            $(this.parent.dom.conclusionsHeader).attr('colspan', newColspan);
            $(this.parent.dom.conclusionsFooter).attr('colspan', newColspan);
        }

        if (typeof this.onRemove === 'function') {
            this.onRemove.call(this);
        }
    };


    DecisionTableVariable.prototype.remove = function(force, index) {
        var self = this;
        if (force) {
            this.removeWithoutConfirmation(index);
            return this;
        }
        if(!this.parent.canBeRemoved(this)) {
            return this;
        }
        if(this.getFilledValuesNum()) {
            App.alert.show('variable-check', {
                level: 'confirmation',
                messages: translate('LBL_PMSE_MESSAGE_LABEL_REMOVE_VARIABLE','pmse_Business_Rules'),
                onCancel: function() {
                    return;
                },
                onConfirm: function() {
                    self.removeWithoutConfirmation(index);
                }
            });
        } else {
            this.removeWithoutConfirmation(index);
        }
        return this;
    };

    DecisionTableVariable.prototype.attachListeners = function() {
        var self = this,
            oldField,
            newField;

        if(!this.html) {
            return this;
        }

        $(this.select).on('change', function(){
            oldField = self.module + self.parent.moduleFieldSeparator  + self.field;
            newField = this.value;

            if (self.hasValues(true)) {
                App.alert.show('select-change-confirm', {
                    level: 'confirmation',
                    messages: translate('LBL_PMSE_MESSAGE_LABEL_CHANGE_COLUMN_TYPE','pmse_Business_Rules'),
                    autoClose: false,
                    onConfirm: function () {
                        self.setField(newField || null);
                        self.clearAllValues();

                        if (typeof self.onChange === 'function') {
                            self.onChange.call(self, self.field, oldField);
                        }
                    },
                    onCancel: function () {
                        self.select.value  = oldField;
                    }
                });
            } else {
                self.setField(this.value || null);
                self.clearAllValues();

                if (typeof self.onChange === 'function') {
                    self.onChange.call(self, self.field, oldField);
                }
            }
        });

        $(this.closeButton).on("click", function() {
            if (self.variableMode == 'condition') {
                self.remove(false, $('#decision-row-header')
                    .find('td.' + self.parent.conditionColumnClassName)
                    .index(this.closest('td'))
                );
                // If there is just 1 condition then show a different Tool Tip
                if (self.parent.conditions.length == 1) {
                    $('#decision-row-header')
                        .children('td.' + self.parent.conditionColumnClassName)
                        .find('.' + self.parent.closeElementClassName)
                        .eq(0)
                        .attr('title', translate('LBL_PMSE_TOOLTIP_REMOVE_COL_DATA',
                            DecisionTable.LANG_MODULE));
                }
            } else {
                self.remove(false, $('#decision-row-header')
                    .find('td.' + self.parent.conclusionColumnClassName)
                    .index(this.closest('td'))
                );
                // If there is just 1 Change Field then show a different Tool Tip.
                // Conclusion#0 is the return value. Conclusion#1 onwards are the change fields.
                if (self.parent.conclusions.length == 2) {
                    $('#decision-row-header')
                        .children('td.' + self.parent.conclusionColumnClassName)
                        .find('.' + self.parent.closeElementClassName)
                        .eq(0)
                        .attr('title', translate('LBL_PMSE_TOOLTIP_REMOVE_COL_DATA',
                            DecisionTable.LANG_MODULE));
                }
            }
        });

        return this;
    };

    DecisionTableVariable.prototype.clearAllValues = function () {
        var i;
        for (i = 0; i < this.values.length; i += 1) {
            this.values[i].clear();
        }
        return this;
    };

    DecisionTableVariable.prototype.hasValues = function (partiallyFilled) {
        return (this.getFilledValuesNum(partiallyFilled) !== 0);
    };

    DecisionTableVariable.prototype.getFilledValuesNum = function(partiallyFilled) {
        var i,
            n = 0,
            current;
        if (partiallyFilled) {
            for(i = 0; i < this.values.length; i+=1) {
                current = this.values[i];
                if (current.isPartiallyFilled) {
                    if(current.isPartiallyFilled()) {
                        n +=1;
                    }
                } else if (current.filledValue()) {
                    n +=1;
                }
            }
        } else {
            for(i = 0; i < this.values.length; i+=1) {
                if(this.values[i].filledValue()) {
                    n +=1;
                }
            }
        }
        return n;
    };

    DecisionTableVariable.prototype.onBeforeValueOpenPanelHandler = function () {
        var that = this;
        return function (decisionTableValue) {
            if (typeof that.onBeforeValueOpenPanel === 'function') {
                that.onBeforeValueOpenPanel(that, decisionTableValue);
            }
        };
    };

    DecisionTableVariable.prototype.onRemoveValueHandler = function() {
        var that = this;
        return function () {
            var i;
            for(i = 0; i < that.values.length; i+=1) {
                if(that.values[i] === this) {
                    that.values.splice(i, 1);
                    return;
                }
            }
        };
    };

    DecisionTableVariable.prototype.onChangeValueHandler = function() {
        var that = this;
        return function(newVal, oldVal) {
            if(typeof that.onChangeValue === 'function') {
                that.onChangeValue.call(that, this, newVal, oldVal);
            }
        };
    };

    DecisionTableVariable.prototype.addValue = function(value, operator) {
        var value;
        if(this.variableMode === 'conclusion') {
            value = new DecisionTableSingleValue({value: value, parent: this, fields: this.inputFields});
        } else {
            value = new DecisionTableValueEvaluation({value: value, operator: operator, parent: this, fields: this.inputFields});
        }
        value.onBeforeOpenPanel = this.onBeforeValueOpenPanelHandler();
        value.onRemove = this.onRemoveValueHandler();
        value.onChange = this.onChangeValueHandler();
        this.values.push(value);

        return this;
    };

    DecisionTableVariable.prototype.getJSON = function(index) {
        var json = {};
        if(typeof index === 'number') {
            if(this.values[index]) {

                json.value = this.values[index].getValue();

                if(this.variableMode === 'conclusion') {
                    json.conclusion_value = (this.isReturnType ? 'result' : this.field);
                    json.conclusion_type = this.isReturnType ? 'return' : 'variable'; //"expression" type also must be set
                } else {
                    json.variable_name = this.field;
                    json.condition = this.values[index].operator;
                    if(!(!json.value || json.condition) || (!json.value && !json.condition) /*|| (json.value.push && !json.value.length)*/)  {
                        return false;
                    }
                }

                if (!this.isReturnType) {
                    json.variable_module = this.module;
                }

                return json;
            }
        } else {
            return false;
        }
    };

    DecisionTableVariable.prototype.removeValue = function(index) {
        if(this.values[index]) {
            $(this.values[index].getHTML()).remove();
            this.values.splice(index, 1);
        }

        return this;
    };

    DecisionTableVariable.prototype.isValid = function() {
        var valid = {
            valid: true
        }, i, values = 0, validation;
        $(this.select).parent().removeClass("error");
        if(this.variableMode === 'conclusion') {
            for(i = 0; i < this.values.length; i+=1) {
                if (_.isUndefined(this.values[i])) {
                    continue;
                }
                validation = this.values[i].isValid();
                if(!validation.valid) {
                    return validation;
                }
                if(this.values[i].value.length) {
                    values +=1;
                }
            }
        } else {
            for(i = 0; i < this.values.length; i+=1) {
                validation = this.values[i].isValid();
                if(this.values[i].operator) {
                    values +=1;
                }
                if(!validation.valid) {
                    valid.valid = false;
                    valid.message = validation.message;
                    valid.index = i;
                    return valid;
                }
            }
        }

        if(values && (this.select && !this.select.value)) {
            $(this.select.parentElement).addClass("error");
            valid = {
                valid: false,
                message: translate('LBL_PMSE_MESSAGE_LABEL_DEFINE_COLUMN_TYPE', DecisionTable.LANG_MODULE)
            };
        }

        return valid;
    };

//Value Cells for DecisionTable
//DecisionTableValue
    var DecisionTableValue = function(settings) {
        PMSE.Element.call(this, settings);
        this.value = null;
        this.expression = null;
        this.onBeforeOpenPanel = null;
        this.onRemove = null;
        this.onChange = null;
        this.parent = null;
        DecisionTableValue.prototype.initObject.call(this, settings);
    };

    DecisionTableValue.prototype = new PMSE.Element();

    DecisionTableValue.prototype.initObject = function(settings) {
        var defaults = {
            value: [],
            onBeforeOpenPanel: null,
            onRemove: null,
            onChange: null,
            parent: null,
            fields: []
        };

        // Do not deep copy here
        $.extend(defaults, settings || {});

        this.parent = defaults.parent;
        this.expression = new ExpressionContainer({
            variables: defaults.fields,
            onBeforeOpenPanel: this.onBeforeOpenPanelHandler(),
            onChange: this.onChangeExpressionHandler()
        }, this);
        this.setValue(defaults.value);
        this.onBeforeOpenPanel = defaults.onBeforeOpenPanel;
        this.onRemove = defaults.onRemove;
        this.onChange = defaults.onChange;
    };

    DecisionTableValue.prototype.onBeforeOpenPanelHandler = function () {
        var that = this;
        return function (expressionContainer) {
            if (typeof that.onBeforeOpenPanel === 'function') {
                that.onBeforeOpenPanel(that);
            }
        };
    };

    DecisionTableValue.prototype.onChangeExpressionHandler = function() {
        var that = this;
        return function(newVal, oldVal) {
            that.value = this.getObject();
            if(typeof that.onChange === 'function') {
                that.onChange.call(that, newVal, oldVal);
            }
        };
    };

    DecisionTableValue.prototype.updateHTML = function() {};

    DecisionTableValue.prototype.clear = function () {
        this.setValue([]);
        return this;
    };

    DecisionTableValue.prototype.setValue = function(value) {
        var i;

        this.expression.setExpressionValue(value);
        this.value = value;

        return this;
    };

    DecisionTableValue.prototype.createHTML = function() {};

    DecisionTableValue.prototype.onEnterCellHandler = function(controlCreationFunction) {
        var that = this;
        return function() {
            if(typeof controlCreationFunction !== 'function') {
                return;
            }
            var control = controlCreationFunction();
            if (control) {
                $(this.parentElement).empty().append(control);
                $(control).select().focus();
            }
        };
    };

    DecisionTableValue.prototype.onLeaveCellHandler = function(member) {
        var that = this;
        return function() {
            var span = document.createElement('span'),
                cell = this.parentElement, oldValue = that[member], changed = false,
                text;
            span.tabIndex = 0;
            changed = oldValue !== this.value;
            that[member] = this.value;
            if(that[member]) {
                text = $(this).find('option:selected').attr('label');
                span.appendChild(document.createTextNode(text));
            } else {
                span.innerHTML = '&nbsp;';
            }
            try {
                $(cell).empty().append(span);
                if (text && $(span).innerWidth() < span.scrollWidth) {
                    span.setAttribute("title", text);
                } else {
                    span.removeAttribute("title");
                }
            } catch(e){}
            that.isValid();
            if(changed && typeof that.onChange === 'function') {
                that.onChange.call(that, that[member], oldValue);
            }
        };
    };

    DecisionTableValue.prototype.isValid = function() {
        if(this.expression.isValid()) {
            $(this.html).removeClass('error');
            return {
                valid: true
            };
        } else {
            $(this.html).addClass('error');
            return {
                valid: false,
                message: translate('LBL_PMSE_BUSINESSRULES_ERROR_INVALIDEXPRESSION', DecisionTable.LANG_MODULE)
            }
        }
    };

    DecisionTableValue.prototype.attachListeners = function() {};

    DecisionTableValue.prototype.remove = function() {
        $(this.html).remove();
        this.expression.remove();
        if(typeof this.onRemove === 'function') {
            this.onRemove.call(this);
        }
    };

    DecisionTableValue.prototype.getValue = function() {
        return this.expression.getObject();
    };

    DecisionTableValue.prototype.filledValue = function() {
        return !!this.value.length;
    };

//DecisionTableSingleValue
    var DecisionTableSingleValue = function(settings) {
        DecisionTableValue.call(this, settings);
    };

    DecisionTableSingleValue.prototype = new DecisionTableValue();

    DecisionTableSingleValue.prototype.createValueControl = function() {
        var that = this;
        return function() {
            var input = document.createElement('input');
            input.type = 'text';
            input.value = that.value || "";
            return input;
        };
    };

    DecisionTableSingleValue.prototype.updateHTML = function() {
        if(this.html) {
            if(this.value) {
                $(this.html).find('span').text(this.value);
            } else {
                $(this.html).find('span').html('&nbsp;');
            }
            $(this.html).find('input').val(this.value);
        }
        return this;
    };

    DecisionTableSingleValue.prototype.createHTML = function() {
        if(this.html) {
            return this.html;
        }

        if (!this.template2) {
            this.template2 = this.compileTemplate('single-value');
        }

        var context = {};

        var cell = this.getHTMLFromTemplate(this.template2, context);

        cell.appendChild(this.expression.getHTML());

        this.html = cell;

        return cell;
    };

//DecisionTableValueEvaluation
    var DecisionTableValueEvaluation = function(settings) {
        DecisionTableValue.call(this, settings);
        this.operator = null;
        DecisionTableValueEvaluation.prototype.initObject.call(this, settings);
    };

    DecisionTableValueEvaluation.prototype = new DecisionTableValue();

    DecisionTableValueEvaluation.prototype.initOperators = function (module) {
        DecisionTableValueEvaluation.prototype.OPERATORS = [
            {
                label: '==',
                value: '==',
            },
            {
                label: '!=',
                value: '!=',
            },
            {
                label: '>=',
                value: '>=',
            },
            {
                label: '<=',
                value: '<=',
            },
            {
                label: '>',
                value: '>',
            },
            {
                label: '<',
                value: '<',
            },
            {
                label: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_EQUAL_TEXT', module),
                value: 'equals',
            },
            {
                label: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_NOT_EQUAL_TEXT', module),
                value: 'not_equals',
            },
            {
                label: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_STARTS_TEXT', module),
                value: 'starts_with',
            },
            {
                label: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_ENDS_TEXT', module),
                value: 'ends_with',
            },
            {
                label: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_CONTAINS_TEXT', module),
                value: 'contains',
            },
            {
                label: App.lang.get('LBL_PMSE_EXPCONTROL_OPERATOR_NOT_CONTAINS_TEXT', module),
                value: 'does_not_contain',
            }
        ];
    };

    DecisionTableValueEvaluation.prototype.initObject = function(settings) {
        DecisionTableValueEvaluation.prototype.initOperators('pmse_Project');
        this.setOperator(settings.operator || "");
    };

    DecisionTableValueEvaluation.prototype.clear = function () {
        DecisionTableValue.prototype.clear.call(this);
        this.setOperator("");
        return this;
    };

    DecisionTableValueEvaluation.prototype.findOperatorLabel = function(operator) {
        var i;
        for (i = 0; i < this.OPERATORS.length; i++) {
            if (operator == this.OPERATORS[i].value) {
                operator = this.OPERATORS[i].label;
                break;
            }
        }
        return operator;
    };

    DecisionTableValueEvaluation.prototype.setOperator = function(operator) {
        var $span;
        this.operator = operator;
        if (this.html && this.html[0]) {
            $span = jQuery(this.html[0]).find('span').empty();
            if (operator) {
                $span.append(this.findOperatorLabel(operator));
            } else {
                $span.html("&nbsp;");
            }
        }
        return this;
    };

    DecisionTableValueEvaluation.prototype.createHTML = function () {
        if(this.html) {
            return this.html;
        }

        if (!this.template) {
            this.template = this.compileTemplate('value-evaluation');
        }

        var context = {};
        if(this.operator) {
            context.operator = this.findOperatorLabel(this.operator);
        } else {
            context.operator = '&nbsp;';
        }

        var html = this.getHTMLFromTemplate(this.template, context);

        var valueCell = DecisionTableSingleValue.prototype.createHTML.call(this);

        $(html).find('#value').replaceWith(valueCell);

        $(html).find('#operator')
            .on('focus', 'span', this.onEnterCellHandler(this.createOperatorControl()))
            .on('blur', 'select', this.onLeaveCellHandler('operator'));

        this.html = html;

        return this.html;
    };

    DecisionTableValueEvaluation.prototype.fillOperators = function(select, type) {
        var i, option, enabledOperators;

        switch (type.toLowerCase()) {
            case 'date':
            case 'datetime':
            case 'decimal':
            case 'currency':
            case 'float':
            case 'integer':
                enabledOperators = this.OPERATORS.slice(0, 6);
                break;
            case 'textarea':
            case 'textfield':
            case 'email':
            case 'phone':
            case 'url':
            case 'name':
                enabledOperators = this.OPERATORS.slice(6);
                break;
            default:
                enabledOperators = this.OPERATORS.slice(0, 2);
        }

        for(i = 0; i < enabledOperators.length; i+=1) {
            option = this.createHTMLElement("option");

            option.label = enabledOperators[i].label;
            option.value = enabledOperators[i].value;
            option.selected = enabledOperators[i].value === this.operator;
            option.innerHTML = option.label;
            select.appendChild(option);
        }

        return select;
    };

    DecisionTableValueEvaluation.prototype.createValueControl = function() {
        var that = this;
        return function() {
            var input = document.createElement('input');
            input.type = 'text';
            input.value = that.value || "";
            return input;
        };
    };

    DecisionTableValueEvaluation.prototype.createOperatorControl = function() {
        var that = this;
        return function() {
            var select = document.createElement('select'), parent = that.parent, type = parent.fieldType;
            if (typeof type !== 'string') {
                App.alert.show(null, {
                    level: 'warning',
                    messages: translate('LBL_PMSE_MESSAGE_LABEL_DEFINE_COLUMN_TYPE', DecisionTable.LANG_MODULE),
                    autoClose: true
                });
                return null;
            } else {
                that.fillOperators(select, type);
                select.value = that.operator;
                return select;
            }
        };
    };

    DecisionTableValueEvaluation.prototype.filledValue = function() {
        return !!this.operator && DecisionTableValue.prototype.filledValue.call(this);
    };

    DecisionTableValueEvaluation.prototype.isPartiallyFilled = function() {
        return !!this.operator || DecisionTableValue.prototype.filledValue.call(this);
    };

    DecisionTableValueEvaluation.prototype.isValid = function() {
        var res = DecisionTableValue.prototype.isValid.call(this);

        if(!res.valid) {
            $(this.html[0]).removeClass('error');
        } else {
            res = {
                valid: (!!this.value.length === !!this.operator)
            };
            if(!res.valid) {
                $(this.html).addClass('error');
                res.message = translate('LBL_PMSE_MESSAGE_LABEL_MISSING_EXPRESSION_OR_OPERATOR', DecisionTable.LANG_MODULE);
            } else {
                $(this.html).removeClass('error');
            }
        }

        return res;
    };

    DecisionTableValueEvaluation.prototype.getOperator = function() {
        return this.operator;
    };
