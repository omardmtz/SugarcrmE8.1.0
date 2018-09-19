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
 * @class View.Fields.Base.TeamsetField
 * @alias SUGAR.App.view.fields.BaseTeamsetField
 * @extends View.Fields.Base.RelateField
 */
({
    extendsFrom: 'RelateField',

    events: {
        'click .btn[name=add]': 'addItem',
        'click .btn[name=remove]': 'removeItem',
        'click .btn[name=primary]': 'setPrimaryItem',
        'change input.select2': 'inputChanged'
    },
    plugins: ['FieldDuplicate', 'TbACLs'],

    /**
     * HTML tag of the append team checkbox.
     *
     * @property {String}
     */
    appendTeamTag: 'input[name=append_team]',

    /**
     * Group fit class.
     *
     * @property {String}
     */
    fitGroupClass: 'three',

    /**
     * Button fit class.
     *
     * @property {String}
     */
    fitButtonClass: 'third',

    initialize: function (options) {
        this._super('initialize', [options]);
        /**
         * @inheritdoc
         */
        this._allow_single_deselect = false;

        /**
         * @inheritdoc
         */
        this._minChars = 1;

        this._currentIndex = 0;
        this.model.on("change:team_name_type", this.appendTeam, this);
    },

    /**
     * @inheritdoc
     *
     * Binds append team checkbox change for massupdate.
     */
    bindDomChange: function() {
        var $el = this.$(this.appendTeamTag);
        if ($el.length) {
            $el.on('change', _.bind(function() {
                this.appendTeamValue = $el.prop('checked');
                this.model.set('team_name_type', this.appendTeamValue ? '1' : '0');
            }, this));
        }
        this._super('bindDomChange');
    },

    /**
     * @inheritdoc
     */
    unbindDom: function() {
        this.$(this.appendTeamTag).off();
        this._super('unbindDom');
    },

    /**
     * Handler on copy teamset values from one model to another.
     *
     * Prevent unchecking last team in team set in order to force the existence
     * of at least one checked team. If primary team is unchecked the next
     * checked team is defined as primary. Set up `checked` property for
     * current team and render field. Returns `false` to prevent processing
     * of teamset field copy cause all logic is implemented in this method.
     *
     * Called from {@link app.plugins._beforeFieldDuplicate}.
     */
    beforeFieldDuplicate: function(params) {

        if (!params.model || !params.data) {
            return false;
        }

        if (this.name !== params.data.fieldName ||
            params.model.get('id') !== this.model.get('id')
        ) {
            return true;
        }

        var checked = params.data.checked || false,
            teamId = params.data.recordItemId || null,
            teams = this.model.get(this.name),
            team = _.findWhere(teams, {'id': teamId}),
            primaryTeam = _.findWhere(teams, {'primary': true}),
            checkedTeams = _.where(teams, {'checked': true}),
            newPrimaryTeam = null;

        if (checked === false && checkedTeams.length === 1) {
            if (!this.disposed) {
                this.render();
            }
            return false;
        }

        if (checked === false && primaryTeam === team) {
            newPrimaryTeam = _.find(checkedTeams, function(item) {
                return item.id !== team.id;
            });
            if (newPrimaryTeam) {
                team.primary = false;
                newPrimaryTeam.primary = true;
            }
        }

        if (team) {
            team.checked = checked;
        }

        if (!this.disposed) {
            this.render();
        }
        return false;
    },

    /**
     * Handler to format field for `merge-duplicate` view.
     *
     * For teamset field we override value to have set of teams from all models
     * for primary record in merge-duplicate view.
     *
     * Called from {@link app.plugins._formatFieldForDuplicate}.
     */
    formatFieldForDuplicate: function() {
        if (_.isUndefined(this.view.generatedValues) ||
            _.isUndefined(this.view.generatedValues.teamsets)
        ) {
            return;
        }

        var allTeams = this.view.generatedValues.teamsets[this.name];

        if (!(this.view.collection instanceof Backbone.Collection)) {
            return;
        }

        _.each(this.view.collection.models, function(model) {
            var teamIds = _.compact(_.pluck(model.get(this.name), 'id')),
                primaryTeam = _.findWhere(model.get(this.name), {primary: true}),
                teams = [];

            _.each(allTeams, function(team) {
                if (model === this.view.primaryRecord || _.contains(teamIds, team.id)) {
                    var extendedTeam = _.extend(
                        app.utils.deepCopy(team),
                        {
                            checked: (model === this.view.primaryRecord && _.contains(teamIds, team.id) === true),
                            primary: (primaryTeam && primaryTeam.id === team.id)
                        }
                    );
                    if (this.isTBAEnabled) {
                        extendedTeam.selected = (
                            _.contains(teamIds, team.id) === true &&
                            _.where(model.get(this.name), {id: team.id})[0].selected
                        );
                    }
                    teams.push(extendedTeam);
                } else {
                    teams.push({
                        checked: false,
                        primary: false
                    });
                }
            }, this);
            model.set(this.name, teams, {silent: true});
        }, this);
    },

    /**
     * Handler to unformat field for `merge-duplicate` view.
     *
     * For teamset field we should exclude teams that are not selected before
     * save primary record.
     *
     * Called from {@link app.plugins._unformatFieldForDuplicate}.
     */
    unformatFieldForDuplicate: function() {
        if (!this.view.primaryRecord) {
            return;
        }

        this.view.primaryRecord.set(
            this.name,
            _.where(this.view.primaryRecord.get(this.name), {'checked': true}),
            {silent: true}
        );
    },

    /**
     * Changes default behavior when doing inline editing on a List view.  We want to
     * load 'list' template instead of 'edit' template because this keeps the teamset widget
     * read-only during inline editing. See SP-1197.
     * Overrides templates for `merge-duplicate` view.
     * @override
     * @private
     */
    _loadTemplate: function() {
        this._super("_loadTemplate");

        if (!_.isUndefined(this.isTBAEnabled) && this.isTBAEnabled) {
            this.fitGroupClass = 'four';
            this.fitButtonClass = 'fourth';
        }

        var template = app.template.getField(
            this.type,
            this.view.name + '-' + this.tplName,
            this.model.module);

        if (!template && this.view.meta && this.view.meta.template) {
            template = app.template.getField(
                this.type,
                this.view.meta.template + '-' + this.tplName,
                this.model.module);
        }

        // If we're loading edit template on List view switch to detail template instead
        if (!template && this.view.action === 'list' && _.contains(['edit','detail'], this.tplName)) {
            this.template = app.template.getField(
                this.type,
                'list',
                this.module, this.tplName
            ) || app.template.empty;
            this.tplName = 'list';
        }

        this.template = template || this.template;
    },

    /**
     * @inheritdoc
     * Add ability to edit and save an invalid team set.
     */
    _render: function () {
        var self = this;

        if (_.isEmpty(this.value) && this.action == 'edit' && this._checkAccessToAction(this.action)) {
            // Leave an empty team set on list view in case of cancel.
            this.value = app.utils.deepCopy(app.user.getPreference('default_teams'));
            this._updateAndTriggerChange(this.value);
        }
        this._super('_render');

        if (this.tplName === 'edit') {
            this.$(this.fieldTag).each(function (index, el) {
                var plugin = $(el).data("select2");
                // If there is a plugin but no team index, set it
                if (!_.isUndefined(plugin) && _.isUndefined(plugin.setTeamIndex)) {
                    plugin.setTeamIndex = function () {
                        self._currentIndex = $(this).data("index");
                    };
                    plugin.opts.element.on("select2-open", plugin.setTeamIndex);
                }
            });
        }
    },

    /**
     * Called to update value when a selection is made from options view dialog
     * @param model New value for teamset
     */
    setValue: function (model) {
        if (!model) {
            return;
        }
        var index = this._currentIndex,
            team = this.value;
        team[index || 0].id = model.id;
        team[index || 0].name = model.value;
        this._updateAndTriggerChange(team);
    },

    /**
     * @override
     */
    _isErasedField: function() {
        if (!this.model) {
            return false;
        }

        return !this.model.get(this.name) && _.contains(this.model.get('_erased_fields'), this.name);
    },

    format: function (value) {
        if (this.model.isNew() && (_.isEmpty(value) || this.model.get(this.name) != value)) {
            //load the default team setting that is specified in the user profile settings
            if (_.isEmpty(value)) {
                //Here default_teams with "selected teams" are copied from user to module.
                //This "teams" will be send to server with "selected" options
                value = app.utils.deepCopy(app.user.getPreference("default_teams"));
                //If TBA not enabled for module "selected" can be set to false.
                _.each(value, function(team, index, list) {
                    if (!_.isUndefined(team.selected) && !this.isTBAEnabled) {
                        team.selected = false;
                    }
                });
                this.model.setDefault(this.name, value);
            }
        }
        value = app.utils.deepCopy(value);
        if (!_.isArray(value)) {
            value = [
                {
                    name: value
                }
            ];
        }
        // FIXME: SC-3836 will replace special-casing view names/actions via
        // action based templates.
        if (this.view.action === 'list' && this.view.name !== 'merge-duplicates' && this.view.name !== 'audit') {
            //Display primary team in list view
            var primaryTeam = _.find(value, function (team) {
                return team.primary;
            });
            // If there is no primary team discovered return an empty string
            return !_.isUndefined(primaryTeam) && !_.isUndefined(primaryTeam.name) ? primaryTeam.name : "";
        }
        // Place the add button as needed
        if (_.isArray(value) && !_.isEmpty(value)) {
            _.each(value, function(team, index, list) {
                delete team.remove_button;
                if (!team.add_button && index === list.length - 1) {
                    team.add_button = true;
                } else {
                    delete team.add_button;
                }
            });

            // number of valid teams
            var numTeams = _.filter(value, function (team) {
                return !_.isUndefined(team.id);
            }).length;
            // Show remove button for all unset combos and only set combos if there are more than one
            _.each(value, function (team) {
                if (_.isUndefined(team.id) || numTeams > 1) {
                    team.remove_button = true;
                }
            });
        }
        return value;
    },

    /**
     * Validates for equality on id and primary properties
     * since other attributes are used for rendering.
     *
     * @override
     */
    equals: function(other) {
        var validateMap = function(item) {
            return {
                id: item.id,
                primary: item.primary
            };
        };
        return _.isEqual(
            _.map(this.getFormattedValue(), validateMap),
            _.map(other.getFormattedValue(), validateMap)
        );
    },

    addTeam: function () {
        this.value.push({});
        this._currentIndex++;
        this._updateAndTriggerChange(this.value);
    },
    removeTeam: function (index) {
        // Do not remove last team.
        if (index === 0 && this.value.length === 1) {
            return;
        }
        if (this._currentIndex === this.value.length - 1) {
            this._currentIndex--;
        }
        //Pick first team to be Primary if we're removing Primary team
        var removed = this.value.splice(index, 1);
        if (removed && removed.length > 0 && removed[0].primary) {
            this.setPrimary(0);
        }
        this._updateAndTriggerChange(this.value);
    },
    appendTeam: function () {
        var appendTeam = this.model.get("team_name_type");
        if (appendTeam !== "1") {
            var primaryTeam = _.find(this.value, function (team) {
                return team.primary;
            }, this);
            if (_.isEmpty(primaryTeam)) {
                this.setPrimary(0);
            }
        }
    },
    setPrimary: function (index) {
        var previousPrimary = null,
            appendTeam = this.model.get("team_name_type");
        _.each(this.value, function (team, i) {
            if (team.primary && appendTeam === "1") {
                previousPrimary = i;
            }
            team.primary = false;
        });
        //If this team is set, then allow it to turn primary
        if (previousPrimary !== index && this.value[index].name) {
            this.value[index].primary = true;
        }
        this._updateAndTriggerChange(this.value);
        return (this.value[index]) ? this.value[index].primary : false;
    },
    //Forcing change event since backbone isn't picking up on changes within an object within the array.
    inputChanged: function (evt) {
        this._updateAndTriggerChange(this.value);
    },
    /**
     * @inheritdoc
     * Restore the select2 focus location after refresh the dom.
     */
    bindDataChange: function() {
        if (this.model) {
            this.model.on('change:' + this.name, function() {
                var teamList = this.model.get(this.name);

                if (!_.isUndefined(teamList) && (this._currentIndex !== teamList.length - 1)) {
                    this._currentIndex = teamList.length - 1;
                }

                this.render();

                var $fieldTag = this.$(this.fieldTag);
                if (!_.isEmpty($fieldTag.data('select2'))) {
                    this.$($fieldTag.get(this._currentIndex)).focus();
                }
            }, this);
        }
    },
    /**
     * Forcing change event on value update since backbone isn't picking up on changes within an object within the array.
     * @param value New value for teamset field
     * @private
     */
    _updateAndTriggerChange: function (value) {
        // SP-1437: No Warning message when update with Team field only

        // The following is provided for your convenience should you wish to learn more about
        // Backbone Model .changedAttributes() not showing all changes.
        // For a list of the actual third party software used in this Sugar product,
        // please visit http://support.sugarcrm.com/06_Customer_Center/11_Third_Party_Software/.
        //
        // http://stackoverflow.com/questions/17221680/backbone-model-changedattributes-not-showing-all-changes
        _.each(value, function(team) {
            // "add_button" and "remove_button" are JS elements, don't track them.
            delete team.add_button;
            delete team.remove_button;
        });
        this.model.unset(this.name, {silent: true}).set(this.name, value);//force changedAttributes
        this.model.trigger('change');
    },
    addItem: _.debounce(function (evt) {
        var index = $(evt.currentTarget).data('index');
        //Only allow adding a Team when ones been selected (SP-534)
        if (!index || this.value[index].id) {
            this.addTeam();
        }
    }, 0),
    removeItem: _.debounce(function (evt) {
        var index = $(evt.currentTarget).data('index');
        if (_.isNumber(index)) {
            this.removeTeam(index);
        }
    }, 0),
    setPrimaryItem: _.debounce(function (evt) {
        var index = $(evt.currentTarget).data('index');

        //Don't allow setting to primary until user's selected an actual team (SP-530)
        if (!this.value[index] || !this.value[index].id) {
            return;
        }
        this.$(".btn[name=primary]").removeClass("active");
        if (this.setPrimary(index)) {
            this.$(".btn[name=primary][data-index=" + index + "]").addClass("active");
        }
    }, 0)
})
