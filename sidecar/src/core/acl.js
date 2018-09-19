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

const User = require('core/user');

/**
 * Dictionary that maps actions to permissions.
 * FIXME: make this constant again
 * @private
 */
let Action2Permission = {
    view: 'read',
    readonly: 'read',
    edit: 'write',
    detail: 'read',
    list: 'read',
    disabled: 'read',
};

/**
 * ACL helper to convert ACLs data into booleans.
 *
 * @param {string} action Action name.
 * @param {Object} acls ACL hash.
 * @return {boolean} Flag indicating if the current user has access.
 * @private
 */
function hasAccess(action, acls) {
    let access;

    if (acls.access === 'no') {
        access = 'no';
    } else {
        access = acls[action];
    }

    return access !== 'no';
}

/**
 * ACL helper to convert ACLs data on fields into booleans.
 *
 * @param {string} action Action name.
 * @param {Object} acls ACL hash.
 * @param {string} field Name of the Module's field.
 * @return {boolean} Flag indicating if the current user has access.
 * @private
 */
function hasAccessToField(action, acls, field) {
    let access;

    action = Action2Permission[action] || action;
    if (acls.fields[field] && acls.fields[field][action]) {
        access = acls.fields[field][action];
    }

    return access !== 'no';
}

/**
 * The ACL module provides methods to check ACLs for modules and fields.
 *
 * @module Core/Acl
 */

/**
 * @alias module:Core/Acl
 */
let Acl = {
    /**
     * Checks ACLs to see if the current user can perform the given action on a
     * given module or record.
     *
     * @param {string} action Action name.
     * @param {string} module Module name.
     * @param {Object} [options] Options.
     * @param {string} [options.field] Name of the field to check access to.
     * @param {string} [options.acls] Record's ACLs that take precedence over
     *   the module's ACLs. These are normally supplied by the server as part
     *   of the data response in `_acl`.
     * @return {boolean} `true` if the current user has access to the given
     *   `action`; `false` otherwise.
     */
    hasAccess: function(action, module, options) {
        let field;
        let recordAcls;

        if (!_.isObject(options)) {
            // TODO: Throw deprecation warning and remove this code.
            field = arguments[3];
            recordAcls = arguments[4];
        } else {
            field = options.field;
            recordAcls = options.acls;
        }

        let acls = User.getAcls()[module];
        if (!acls && !recordAcls) {
            return true;
        }

        acls = acls || {};
        if (recordAcls) {
            let fieldAcls = _.extend({}, acls.fields, recordAcls.fields);
            acls = _.extend({}, acls, recordAcls);
            acls.fields = fieldAcls;
        }

        let access = hasAccess(action, acls);
        if (access && field && acls.fields) {
            access = hasAccessToField(action, acls, field);

            // if the field is in a group, see if we have access to the group
            let moduleMeta = SUGAR.App.metadata.getModule(module);
            let fieldMeta = (moduleMeta && moduleMeta.fields) ? moduleMeta.fields[field] : null;
            if (access && fieldMeta && fieldMeta.group) {
                access = hasAccessToField(action, acls, fieldMeta.group);
            }
        }

        return access;
    },

    /**
     * Checks ACLs to see if the current user can perform the given action on a
     * given model's field.
     *
     * @param {string} action Action name.
     * @param {Object} model Model instance.
     * @param {string} [field] Name of the model field.
     * @return {boolean} `true` if the current user has access to the given
     *   `action`; `false` otherwise.
     */
    hasAccessToModel: function(action, model, field) {
        let id;
        let module;
        let acls;

        if (model) {
            id = model.id;
            module = model.module;
            acls = model.get('_acl') || { fields: {} };
        }

        if (action === 'edit' && !id) {
            action = 'create';
        }

        return this.hasAccess(action, module, {field, acls});
    },

    /**
     * Checks ACLs to see if the current user can perform the given
     * action on any model.
     *
     * ```
     * const Acl = require('core/acl');
     *
     * // Check whether user has `admin` access for any module.
     * Acl.hasAccessToAny('admin');
     *
     * // Check whether user has `developer` access for any module.
     * Acl.hasAccessToAny('developer');
     * ```
     *
     * @param {string} action Action name.
     * @return {boolean} `true` if the current user has access to the given
     *   `action`; `false` otherwise.
     */
    hasAccessToAny: function(action) {
        return _.some(User.getAcls(), function(obj, module) {
                return this.hasAccess(action, module);
        }, this);
    },

    _hasAccess: function(action, acls) {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Core.Acl#_hasAccess is a private method that you are not allowed to access. ' +
                'Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Core.Acl#_hasAccess is a private method that you should not access. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the ' +
            'public API only.');

        return hasAccess(action, acls);
    },

    _hasAccessToField: function(action, acls, field) {
        if (!SUGAR.App.config.sidecarCompatMode) {
            SUGAR.App.logger.error('Core.Acl#_hasAccessToField is a private method that you are not allowed ' +
                'to access. Please use only the public API.');
            return;
        }

        SUGAR.App.logger.warn('Core.Acl#_hasAccessToField is a private method that you should not access. ' +
            'You will NOT be allowed to access it in the next release. Please update your code to rely on the public ' +
            'API only.');

        return hasAccessToField(action, acls, field);
    },

    /**
     * This method has no effect. Do not use it.
     *
     * @deprecated since 7.10
     */
    clearCache: function() {
        SUGAR.App.logger.warn('`Core.Acl#clearCache` is deprecated since 7.10 and will be removed in a future ' +
            'release. This method has no effect. Please do not use it anymore');
        this._accessToAny = {};
    }
};

Object.defineProperty(Acl, 'action2permission', {
    configurable: true,

    get: function() {
        SUGAR.App.logger.warn('`Core.Acl.action2permission` has been made private since 7.10 and you will not be able ' +
            'to access it in the next release. Please do not use it anymore');
        return Action2Permission;
    },

    set: function(val) {
        SUGAR.App.logger.warn('`Core.Acl.action2permission` has been made private since 7.10 and you will not be able ' +
            'to access it in the next release. Please do not use it anymore');
        Action2Permission = val;
    },
});

/**
 * Dummy _accessToAny variable.
 *
 * @deprecated
 * @private
 */
let dummyAccessToAny = {};

Object.defineProperty(Acl, '_accessToAny', {
    configurable: true,

    get: function() {
        SUGAR.App.logger.warn('`Core.Acl#_accessToAny` is deprecated since 7.10 and will be removed in a future ' +
            'release. Please do not use it anymore');
        return dummyAccessToAny;
    },

    set: function(val) {
        SUGAR.App.logger.warn('`Core.Acl#_accessToAny` is deprecated since 7.10 and will be removed in a future ' +
            'release. Please do not use it anymore');
        dummyAccessToAny = val;
    },
});

module.exports = Acl;
