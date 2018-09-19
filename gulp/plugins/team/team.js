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

var _ = require('lodash');
var fs = require('fs');
var path = require('path');

var teamData = {
    ty: {
        fullName: 'sugarcrm/eng-typhoon'
        // to have a custom filter, put "filter" as a function here
    }
};

// currently, we use the mention-bot file to store the mapping of teams to files
var defaultTeamFiles = JSON.parse(fs.readFileSync('../.mention-bot', 'utf-8'));

/**
 * getDefaultTeamFiles is the default filter.
 *
 * @param {string} fullName The team's full name.
 * @return {string[]} A list of file paths belonging to the given teams.
 */
function getDefaultTeamFiles(fullName) {
    var globs = _.find(defaultTeamFiles.alwaysNotifyForPaths, function(obj) {
        return obj.name === fullName;
    }).files;

    // the paths are relative to the project root, which is wrong
    // strip out the "sugarcrm" part
    _.each(globs, function(glob, index, list) {
        list[index] = glob.replace('sugarcrm/', '');
    });
    return globs;
}

/**
 * filterTeams returns a glob of files belonging to the given teams.
 *
 * @param {string|string[]} teams Teams to include.
 * @return {string[]} A list of file paths belonging to the given teams.
 * @throws {Error} If an invalid team is passed.
 */
function filterTeams(teams) {
    if (_.isString(teams)) {
        teams = [teams];
    }

    var teamFilesArray = [];
    _.each(teams, function(team) {
        var thisTeamsData = teamData[team];
        if (!thisTeamsData) {
            var msg = 'Invalid team ' + team + ' given. Supported teams are {' + _.keys(teamData).join(', ') + '}.';
            throw new Error(msg);
        }
        var thisTeamsFiles;
        if (_.isFunction(thisTeamsData.filter)) {
            thisTeamsFiles = thisTeamsData.filter();
        } else {
            thisTeamsFiles = getDefaultTeamFiles(thisTeamsData.fullName);
        }
        teamFilesArray = _.union(teamFilesArray, thisTeamsFiles);
    });
    return teamFilesArray;
}

module.exports = filterTeams;
