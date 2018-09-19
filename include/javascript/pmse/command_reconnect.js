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
/*global jCore

 */
var AdamCommandReconnect = function (rec, opt) {
    var NewObj = function (receiver) {
        jCore.CommandReconnect.call(this, receiver);
        NewObj.prototype.initObject.call(this, receiver, opt);
    };

    NewObj.prototype = new jCore.CommandReconnect(rec);

    NewObj.prototype.initObject = function (receiver, opt) {

        this.prefix = {
            "AdamActivity": "act",
            "AdamGateway": "gat"
        };

        this.srcShape = this.receiver.connection.getSrcPort().parent;
        this.before.type = this.receiver.connection.getFlowType();
        this.before.condition = this.receiver.connection.getFlowCondition();
        this.before.defaultFlow = this.srcShape.type === 'AdamGateway' || this.srcShape.type === 'AdamActivity' ? this.srcShape[this.prefix[this.srcShape.type] + "_default_flow"] : "";
        this.after.type = null;
        this.condition = null;
        this.after.defaultFlow = "";
    };

    NewObj.prototype.fireTrigger = function (undo) {
        var updatedElement = [], connection = this.receiver.connection, v, flowChanges, n;
        if (this.after.type === 'DEFAULT' || this.before.type === 'DEFAULT') {
            updatedElement.push({
                id: this.srcShape.getID(),
                relatedObject: this.srcShape,
                type: this.srcShape.type,
                fields: [{
                    field: "default_flow",
                    newVal: this.srcShape[this.prefix[this.srcShape.type] + "_default_flow"],
                    oldVal: undo ? this.after.defaultFlow : this.before.defaultFlow
                }]
            });
        }

        flowChanges = {
            id: connection.getID(),
            relatedObject: connection,
            type: connection.type,
            fields: []
        };

        v = undo ? this.after.type : this.before.type;
        n = connection.getFlowType();
        if (v !== n) {
            flowChanges.fields.push({
                field: "type",
                newVal: n,
                oldVal: v
            });
        }

        v = undo ? this.after.condition : this.before.condition;
        n = connection.getFlowCondition();
        if (v !== n) {
            flowChanges.fields.push({
                field: "condition",
                newVal: n,
                oldVal: v
            });
        }

        if (flowChanges.fields.length > 0) {
            updatedElement.push(flowChanges);
        }

        this.receiver.getCanvas().triggerDefaultFlowChangeEvent(updatedElement);
    };

    NewObj.prototype.execute = function () {
        var connection = this.receiver.connection;
        connection.setFlowType(this.after.type);

        jCore.CommandReconnect.prototype.execute.call(this);

        if (connection.getSrcPort().getParent().type === 'AdamGateway' || (!connection.getFlowCondition() && this.srcShape.type !== "AdamArtifact" && connection.getDestPort().parent.type !== "AdamArtifact")) {
            connection.setFlowType("SEQUENCE").changeFlowType('sequence');
        } else if ((connection.getSrcPort().getParent().type === 'AdamActivity' && connection.getFlowCondition()) && !(connection.getSrcPort().getParent().type === 'AdamArtifact' || connection.getDestPort().getParent().type === 'AdamArtifact')) {
            connection.setFlowType("CONDITIONAL").changeFlowType('conditional');
        } else {
            connection.setFlowType("ASSOCIATION").setFlowCondition("").changeFlowType('association');
            if (this.srcShape.type === "AdamActivity" || this.srcShape.type === "AdamGateway") {
                this.srcShape[this.prefix[this.srcShape.type] + "_default_flow"] = "";
                this.after.defaultFlow = "";
            }
        }


        if (!this.after.type || !this.after.condition) {
            this.after.type = connection.getFlowType();
            this.after.condition = connection.getFlowCondition();
        }
        this.fireTrigger();
    };

    NewObj.prototype.undo = function () {
        var connection = this.receiver.connection,
            prev = {
                type: connection.getFlowType(),
                condition: connection.getFlowCondition()
            };
        jCore.CommandReconnect.prototype.undo.call(this);
        connection.setFlowCondition(this.before.condition)
            .setFlowType(this.before.type)
            .changeFlowType(this.before.type.toLowerCase());
        if (this.srcShape.updateDefaultFlow) {
            this.srcShape.updateDefaultFlow(this.before.defaultFlow);
        }
        this.fireTrigger(true);
    };
    return new NewObj(rec);
};
