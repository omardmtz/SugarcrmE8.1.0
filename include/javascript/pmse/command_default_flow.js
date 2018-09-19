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
/*global jCore,

 */
var CommandDefaultFlow = function (receiver, destID) {
    jCore.Command.call(this, receiver);
    this.before = null;
    this.after = null;
    this.prefix = null;
    CommandDefaultFlow.prototype.initObject.call(this, destID);
};

CommandDefaultFlow.prototype = new jCore.Command();

CommandDefaultFlow.prototype.type = "CommandDefaultFlow";

CommandDefaultFlow.prototype.initObject = function (destID) {
    var i, s, p;

    this.prefix = {
        "AdamActivity": "act",
        "AdamGateway": "gat"
    };

    this.before = {
        defaultDestID: this.receiver[this.prefix[this.receiver.type] + "_default_flow"] || "",
        connections: []
    };
    this.after = {
        defaultDestID: destID,
        connections: null
    };
    p = this.receiver.getPorts();
    s = p.getSize();
    for (i = 0; i < s; i += 1) {
        this.before.connections.push({
            id: p.get(i).connection.getID(),
            condition: p.get(i).connection.getFlowCondition(),
            type: p.get(i).connection.getFlowType()
        });
    }
};

CommandDefaultFlow.prototype.fireTrigger = function (undo) {
    var i, p, s, c, tmp, v, updatedElement = [{
        id: this.receiver.getID(),
        type: this.receiver.type,
        relatedObject: this.receiver,
        fields: [{
            field: "default_flow",
            //newVal: this.receiver[this.prefix[this.receiver.type] + "_default_flow"] || null,
            newVal: this.receiver[this.prefix[this.receiver.type] + "_default_flow"] || 0,
            oldVal: undo ? this.after.defaultDestID : this.before.defaultDestID
        }]
    }];

    p = this.receiver.getPorts();
    s = p.getSize();
    for (i = 0; i < s; i += 1) {
        c = p.get(i).connection;
        tmp = {
            id: c.getID(),
            relatedObject: c,
            type: c.type,
            fields: []
        };

        v = undo ? this.after.connections[i].type : this.before.connections[i].type;
        if (c.getFlowType() !== v) {
            tmp.fields.push({
                field: "type",
                newVal: c.getFlowType(),
                oldVal: v
            });
        }

        v = undo ? this.after.connections[i].condition : this.before.connections[i].condition;
        if (c.getFlowCondition() !== v) {
            tmp.fields.push({
                field: "condition",
                newVal: c.getFlowCondition(),
                oldVal: v
            });
        }

        if (tmp.fields.length > 0) {
            updatedElement.push(tmp);
        }
    }

    this.receiver.getCanvas().triggerDefaultFlowChangeEvent(updatedElement);
};

CommandDefaultFlow.prototype.execute = function () {
    var i, p, s, c;
    this.receiver.setDefaultFlow(this.after.defaultDestID === "" ? 0 : this.after.defaultDestID);
    if (!this.after.connections) {
        this.after.connections = [];
        p = this.receiver.getPorts();
        s = p.getSize();
        for (i = 0; i < s; i += 1) {
            c = p.get(i).connection;
            this.after.connections.push({
                id: c.getID(),
                condition: c.getFlowCondition(),
                type: c.getFlowType()
            });
        }
    }
    this.fireTrigger();
};

CommandDefaultFlow.prototype.undo = function () {
    var i, c, t;

    for (i = 0; i < this.before.connections.length; i += 1) {
        c = this.receiver.canvas.getConnections().find("id", this.before.connections[i].id);
        c.setFlowCondition(this.before.connections[i].condition);
        t = this.before.connections[i].type;
        if (c.getFlowType() !== t) {
            if (t !== "DEFAULT") {
                c.setFlowType(t)
                    .changeFlowType(t.toLowerCase());
            }
        }
    }
    this.receiver.setDefaultFlow(this.before.defaultDestID);
    this.fireTrigger(true);
};

CommandDefaultFlow.prototype.redo = function () {
    this.execute();
};