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
({
    // forms jstree
    _renderHtml: function () {
        var self = this;

        this._super('_renderHtml');

        this.$('#people').jstree({
            "json_data" : {
                "data" : [
                    {
                        "data" : "Sabra Khan",
                        "state" : "open",
                        "metadata" : { id : 1 },
                        "children" : [
                            {"data" : "Mark Gibson","metadata" : { id : 2 }},
                            {"data" : "James Joplin","metadata" : { id : 3 }},
                            {"data" : "Terrence Li","metadata" : { id : 4 }},
                            {"data" : "Amy McCray",
                                "metadata" : { id : 5 },
                                "children" : [
                                    {"data" : "Troy McClure","metadata" : {id : 6}},
                                    {"data" : "James Kirk","metadata" : {id : 7}}
                                ]
                            }
                        ]
                    }
                ]
            },
            "plugins" : [ "json_data", "ui", "types" ]
        })
        .bind('loaded.jstree', function () {
            // do stuff when tree is loaded
            self.$('#people').addClass('jstree-sugar');
            self.$('#people > ul').addClass('list');
            self.$('#people > ul > li > a').addClass('jstree-clicked');
        })
        .bind('select_node.jstree', function (e, data) {
            data.inst.toggle_node(data.rslt.obj);
        });
    }
})
