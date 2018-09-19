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
var EmailPickerField = function (settings, parent) {
	MultipleItemField.call(this, settings, parent);
	this._teams = null;
	this._teamsDropdown = null;
	this._teamTextField = null;
	this._teamValueField = null;
	this._lastQuery = null;
	this._roles = null;
	this._rolesDropdown = null;
	this._roleTextField = null;
	this._roleValueField = null;
	this._modules = null;
	this._moduleTextField = null;
	this._moduleValueField = null;
	this._userModules = null;
	this._recipientModules = null;
	this._suggestPanel = null;
	this._suggestTimer = null;
	this._delaySuggestTime = null;
	this._suggestionDataURL = null;
	this._suggestionDataRoot = null;
	this._suggestionItemName = null;
	this._suggestionItemAddress = null;
	this._suggestionVisible = false;
	this._timer = null;
	EmailPickerField.prototype.init.call(this, settings);
};

EmailPickerField.prototype = new MultipleItemField();
EmailPickerField.prototype.constructor = EmailPickerField;
EmailPickerField.prototype.type = 'EmailPickerField';

EmailPickerField.prototype.init = function (settings) {
	var defaults = {
		teams: [],
		roles: [],
		modules: [],
		teamTextField: "text",
		teamValueField: "value",
		roleTextField: "text",
		roleValueField: "value",
		moduleTextField: "text",
		moduleValueField: "value",
		delaySuggestTime: 500,
		suggestionDataURL: null,
		suggestionDataRoot: null,
		suggestionItemName: null,
		suggestionItemAddress: "email"
	};

	jQuery.extend(true, defaults, settings);

	this._lastQuery = {};

	this.setTeamTextField(defaults.teamTextField)
		.setTeamValueField(defaults.teamValueField)
		.setTeams(defaults.teams)
		.setRoleTextField(defaults.roleTextField)
		.setRoleValueField(defaults.roleValueField)
		.setRoles(defaults.roles)
		.setModuleTextField(defaults.moduleTextField)
		.setModuleValueField(defaults.moduleValueField)
		.setModules(defaults.modules)
		.setSuggestionDataURL(defaults.suggestionDataURL)
		.setSuggestionDataRoot(defaults.suggestionDataRoot)
		.setDelaySuggestTime(defaults.delaySuggestTime)
		.setSuggestionItemName(defaults.suggestionItemName)
		.setSuggestionItemAddress(defaults.suggestionItemAddress);
};

EmailPickerField.prototype.setSuggestionItemName = function(text) {
	if(!(text === null || typeof text === 'string')) {
		throw new Error("setSuggestionItemName(): The parameter must be a string or null.");
	}
	this._suggestionItemName = text;
	return this;
};

EmailPickerField.prototype.setSuggestionItemAddress = function(text) {
	if(!(text === null || (typeof text === 'string' && text !== ""))) {
		throw new Error("setSuggestionItemAddress(): The parameter must be a string different than an empty string.");
	}
	this._suggestionItemAddress = text;
	return this;
};

EmailPickerField.prototype.setSuggestionDataURL = function (url) {
	if (!(url === null || typeof url === "string")) {
		throw new Error("setSuggestionDataURL(): The parameter must be a string or null.");
	}
	this._suggestionDataURL = url;
	return this;
};

EmailPickerField.prototype.setSuggestionDataRoot = function(root) {
	if (!(root === null || typeof root === "string")) {
		throw new Error("setSuggestionDataRoot(): The parameter must be a string or root.");
	}
	this._suggestionDataRoot = root;
	return this;
};

EmailPickerField.prototype.setDelaySuggestTime = function (milliseconds) {
	if (typeof milliseconds !== "number") {
		throw new Error("setDelaySuggestTime(): The parameter must be a number.");
	}
	this._delaySuggestTime = milliseconds;
	return this;
};

EmailPickerField.prototype.setModuleTextField = function (field) {
	if (typeof field !== 'string') {
		throw new Error("setModuleTextField(): The parameter must be a string.");
	}
	this._moduleTextField = field;
	return this;
};

EmailPickerField.prototype.setModuleValueField = function (field) {
	if (typeof field !== 'string') {
		throw new Error("setModuleValueField(): The parameter must be a string.");
	}
	this._moduleValueField = field;
	return this;
};

EmailPickerField.prototype.setModules = function (items) {
	var i;
	if(!jQuery.isArray(items)) {
		throw new Error("setModules(): The parameter must be an array.");
	}
    items = _.filter(items, function(item){ if (item.module!=="Users") return item; });
	this._modules = items;
	if(this._userModules) {
		this._userModules.setOptions(items);
		this._recipientModules.setOptions(items);
	}
	return this;
};

EmailPickerField.prototype.setRoleTextField = function(field) {
	if (typeof field !== 'string') {
		throw new Error("setRoleTextField(): The parameter must be a string.");
	}
	this._roleTextField = field;
	return this;
};

EmailPickerField.prototype.setRoleValueField = function(field) {
	if (typeof field !== 'string') {
		throw new Error("setRoleValueField(): The parameter must be a string.");
	}
	this._roleValueField = field;
	return this;
};

EmailPickerField.prototype.setRoles = function (items) {
	var i;
	if(!jQuery.isArray(items)) {
		throw new Error("setRoles(): The parameter must be an array.");
	}
	this._roles = items;
	if(this._rolesDropdown) {
		this._rolesDropdown.setOptions(items);
	}
	return this;
};

EmailPickerField.prototype.setTeamTextField = function(teamTextField) {
	if (typeof teamTextField !== 'string') {
		throw new Error("setTeamTextField(): The parameter must be a string.");
	}
	this._teamTextField = teamTextField;
	return this;
};

EmailPickerField.prototype.setTeamValueField = function (teamValueField) {
	if (typeof teamValueField !== 'string') {
		throw new Error("setTeamValueField(): The parameter must be a string.");
	}
	this._teamValueField = teamValueField;
	return this;
};

EmailPickerField.prototype.setTeams = function (teams) {
	var i;
	if(!jQuery.isArray(teams)) {
		throw new Error("setItems(): The parameter must be an array.");
	}
	this._teams = teams;
	if(this._teamsDropdown) {
		this._teamsDropdown.setOptions(teams);
	}
	return this;
};

EmailPickerField.prototype._onItemSetText = function () {
	return function(itemObject, data) {
		return data.label;
	};
};

/*EmailPickerField.prototype._createItemData = function(data) {
	return {
		name: data.name || data.emailAddress || "",
		emailAddress: data.emailAddress || "",
		module: null
	};
};

EmailPickerField.prototype._onBeforeAddItemByInput = function () {
	var that = this;
	return function (itemContainer, singleItem, text, index) {
		return that._createItem({
			emailAddress: text
		}, singleItem);
	}
};*/

EmailPickerField.prototype._onPanelValueGeneration = function () {
	var that = this;
	return function (fieldPanel, fieldPanelItem, data) {
		var newEmailItem = {}, parentPanelID = that.id, i18nID, aux = 'i18n', replacementText;

		switch (fieldPanelItem.id) {
			case parentPanelID + '-user-form':
				newEmailItem.type = 'user';
				newEmailItem.module = data['module'];
				newEmailItem.value = data['user_who'];
				newEmailItem.user = data['user'];
				i18nID = 'LBL_PMSE_EMAILPICKER_'
					+ fieldPanelItem.getItem('user').getSelectedData(aux) + "_"
					+ fieldPanelItem.getItem('user_who').getSelectedData(aux);

				newEmailItem.label = translate(i18nID).replace(/%\w+%/g,
					fieldPanelItem.getItem("module").getSelectedText());
				break;
			case parentPanelID + '-recipient-form':
				newEmailItem.type = 'recipient';
				newEmailItem.module = data['module'];
				newEmailItem.value = data['emailAddressField'];
				newEmailItem.label = fieldPanelItem.getItem('module').getSelectedText() + ":"
					+ fieldPanelItem.getItem('emailAddressField').getSelectedText();
				break;
			case parentPanelID + '-team-form':
				newEmailItem.type = 'team';
				newEmailItem.value = data['team'];
				newEmailItem.label = translate('LBL_PMSE_EMAILPICKER_TEAM_ITEM').replace(/%\w+%/g,
					fieldPanelItem.getItem("team").getSelectedText());
				break;
			case parentPanelID + '-role-form':
				newEmailItem.type = 'role';
				newEmailItem.value = data['role'];
				newEmailItem.label = translate('LBL_PMSE_EMAILPICKER_ROLE_ITEM').replace(/%\w+%/g,
					fieldPanelItem.getItem("role").getSelectedText());
				break;
			case parentPanelID + 'list-suggest':
				newEmailItem.type = 'email';
				newEmailItem.value = data['emailAddress'];
				newEmailItem.label = data['fullName'];
                newEmailItem.id = data.id;
				break;
			default:
				throw new Error('_onPanelValueGeneration(): invalid fieldPanelItem\'s id.');
		}
		newEmailItem = that._createItem(newEmailItem);
		that.controlObject.addItem(newEmailItem);
	};
};

EmailPickerField.prototype._onLoadSuggestions = function () {
	var that = this;
	return function (listPanel, data) {
		var replacementText = {
			"%NUMBER%": listPanel.getItems().length,
			"%TEXT%": that._lastQuery.query
		};

		listPanel.setTitle(translate("LBL_PMSE_EMAILPICKER_RESULTS_TITLE").replace(/%\w+%/g, function(wildcard) {
		   return replacementText[wildcard] !== undefined ? replacementText[wildcard] : wildcard;
		}));
	};
};

EmailPickerField.prototype._createPanel = function () {
	var that = this;

	if (!this._panel) {
		this._suggestPanel = new ListPanel({
			id: this.id + "list-suggest",
			title: translate('LBL_PMSE_EMAILPICKER_SUGGESTIONS'),
			itemsContent: this._suggestionItemContent(),
			visible: false,
			bodyHeight: 150,
			onLoad: this._onLoadSuggestions()
		});

		this._teamsDropdown = new FormPanelDropdown({
			name: 'team',
			label: 'Team',
			type: 'dropdown',
			required: true,
			width: '100%',
			labelField: this._teamTextField,
			valueField: this._teamValueField
		});
		this.setTeams(this._teams);

		this._rolesDropdown = new FormPanelDropdown({
			name: 'role',
			label: 'Role',
			type: 'dropdown',
			width: '100%',
			required: true,
			labelField: this._roleTextField,
			valueField: this._roleValueField
		});
		this.setRoles(this._roles);

		this._userModules = new FormPanelDropdown({
			name: 'module',
			label: 'Module',
			type: 'dropdown',
			required: true,
			width: '100%',
			labelField: this._moduleTextField,
			valueField: this._moduleValueField
		});
		this._recipientModules = new FormPanelDropdown({
			name: 'module',
			label: 'Module',
			type: 'dropdown',
			required: true,
			width: '100%',
			labelField: this._moduleTextField,
			valueField: this._moduleValueField,
			dependantFields: ['emailAddressField']
		});
		this.setModules(this._modules);

		this._panel = new FieldPanel({
			items: [
				{
					type: "multiple",
					headerVisible: false,
					collapsed: false,
					bodyHeight: 117,
					items: [
						{
							id: this.id + "-user-form",
							type: 'form',
							title: "User",
							items: [
								this._userModules,
								{
									name: 'user',
									label: '',
									type: 'dropdown',
									required: true,
									width: '40%',
									options: [
										{
											label: 'User who',
											value: 'who',
											i18n: "USER"
										},
										{
											label: 'User is manager of who',
											value: 'manager_of',
											i18n: "MANAGER"
										}
									]
								},
								{
									name: 'user_who',
									label: '',
									type: 'dropdown',
									required: true,
									width: '60%',
									options: [
										{
											label: translate('LBL_PMSE_EMAILPICKER_USER_RECORD_CREATOR'),
											value: 'record_creator',
											i18n: 'CREATED'
										},
										{
											label: translate('LBL_PMSE_EMAILPICKER_USER_LAST_MODIFIER'),
											value: 'last_modifier',
											i18n: 'LAST_MODIFIED'
										},
										{
											label: translate('LBL_PMSE_EMAILPICKER_USER_IS_ASIGNEE'),
											value: 'is_assignee',
											i18n: 'IS_ASSIGNED'
										}
									]
								}
							]
						},
						{
							id: this.id + "-recipient-form",
							type: 'form',
							title: "Recipient",
							items: [
								this._recipientModules,
								{
									name: 'emailAddressField',
									label: 'Email Address Field',
									type: 'dropdown',
									required: true,
									width: '100%',
									dependencyHandler: function (dependantField, field, value) {
										var relatedModule;
										if (!value) {
											return;
										}
										relatedModule = App.metadata.getRelationship(value);
										dependantField.setDataURL('pmse_Project/CrmData/fields/' + value)
											.setDataRoot('result')
											.setLabelField('text')
											.setValueField('value')
                                            .setAttributes({base_module: PROJECT_MODULE, call_type: 'ET'})
											.load();
									},
									optionsFilter: function (item) {
										return item.type === "email" || item.type === "TextField";
									}
								}
							]
						},
						{
							id: this.id + '-team-form',
							type: 'form',
							title: translate('LBL_PMSE_EMAILPICKER_TEAMS'),
							required: true,
							items: [
								this._teamsDropdown
							]
						},
						{
							id: this.id + '-role-form',
							type: 'form',
							title: "Role",
							items: [
								this._rolesDropdown
							]
						}
					]
				},
				this._suggestPanel
			]
		});
		MultipleItemField.prototype._createPanel.call(this);
	}

	return this;
};

EmailPickerField.prototype._showSuggestionPanel = function () {
	var panelItems = this._panel.getItems(), i;

	if (!this._suggestionVisible) {
		for (i = 0; i < panelItems.length; i += 1) {
			if (panelItems[i] !== this._suggestPanel) {
				panelItems[i].setVisible(false);
			} else {
				panelItems[i].setVisible(true);
			}
		}
	}
	this._suggestionVisible = true;
	return this;
};

EmailPickerField.prototype._hideSuggestionPanel = function () {
	var panelItems = this._panel.getItems(), i;

	if (this._suggestionVisible) {
		for (i = 0; i < panelItems.length; i += 1) {
			if (panelItems[i] !== this._suggestPanel) {
				panelItems[i].setVisible(true);
			} else {
				panelItems[i].setVisible(false);
			}
		}
	}
	this._suggestionVisible = false;
	return this;
};

EmailPickerField.prototype._suggestionItemContent = function() {
	var that = this;
	return function (item, data) {
		var name = that.createHTMLElement('strong'),
			address = that.createHTMLElement('small'),
			container = that.createHTMLElement('a');

		container.href = "#";
		container.className = "adam email-picker-suggest";
		if(that._suggestionItemName) {
			name.className = "adam email-picker-suggest-name";
			name.textContent = data[that._suggestionItemName];
			container.appendChild(name);
		}
		address.className = "adam email-picker-suggest-address";
		address.textContent = data[that._suggestionItemAddress];
		container.appendChild(address);

		return container;
	};
};

EmailPickerField.prototype._onBeforeAddItemByInput = function () {
	var that = this;
	return function (itemContainer, singleItem, text, index) {
		return that._createItem({
			value: text,
			type: "email",
			label: text
		}, singleItem);
	}
};

EmailPickerField.prototype._isValidInput = function () {
	var that = this;
	return function (itemContainer, text) {
		return /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/.test(text);
	};
};

EmailPickerField.prototype._loadSuggestions = function (c) {
	var that = this;
	return function	() {
		var url;
		clearInterval(that._timer);
		if(that._suggestionDataURL) {
			that._lastQuery = {
				query: c,
				dataRoot: that._suggestionDataRoot,
				dataURL: that._suggestionDataURL
			};
			url = that._suggestionDataURL.replace(/\{\$\d+\}/g, encodeURIComponent(c));
			that._suggestPanel.setDataURL(url)
				.setDataRoot(that._suggestionDataRoot);
			that._suggestPanel.load();
		}
	};
};

EmailPickerField.prototype.openPanel = function () {
	if (!this.isPanelOpen()) {
		this._hideSuggestionPanel();
		MultipleItemField.prototype.openPanel.call(this);
	}
	return this;
};

EmailPickerField.prototype._onInputChar = function () {
	var that = this;
	return function (itemContainer, theChar, completeText, keyCode) {
		var trimmedText = jQuery.trim(completeText);
		clearInterval(that._timer);
		if (trimmedText) {
			if (that._suggestionDataURL) {
				//Vefify if the current query is identical than the last one
				if (!(that._lastQuery.query === trimmedText && that._lastQuery.dataURL === that._suggestionDataURL
					&& that._lastQuery.dataRoot === that._suggestionDataRoot)) {
					that._timer = setInterval(that._loadSuggestions(trimmedText), that._delaySuggestTime);
					that._suggestPanel.clearItems()
						._showLoadingMessage()
						.setTitle(translate("LBL_PMSE_EMAILPICKER_SUGGESTIONS"));
				}
				that._showSuggestionPanel();
				that.openPanel(true);
				that._suggestPanel.expand();
			}
		} else {
			that._hideSuggestionPanel();
		}
	};
};

EmailPickerField.prototype.createHTML = function () {
	if(!this.html) {
		MultipleItemField.prototype.createHTML.call(this);
		this.controlObject.setOnInputCharHandler(this._onInputChar());
	}
	return this;
};
