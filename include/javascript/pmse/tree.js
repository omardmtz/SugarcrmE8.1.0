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
/*global $, jCore */
var Tree = function () {
};

Tree.treeReload = function (id, items) {
    var shape,
        $elem = $('#adam_tree');
    $elem.empty();

    $elem.pmtree({
        id: id,
        collapsed: true,
        items: items,
        select: function (param) {
            shape = jCore.getActiveCanvas().customShapes.find('id', param.uid);
            if (shape) {
                shape.canvas.emptyCurrentSelection();
                shape.canvas.addToSelection(shape);
                //to disable textbox of label
                if (shape.canvas.currentLabel) {
                    shape.canvas.currentLabel.loseFocus();
                }
                //for property grids
                shape.canvas.project.updatePropertiesGrid(shape);
            }
        },
        unselect: function (id) {
//            alert ('unselect item');
        }

    });
};

var setSelectedNode = function (shape) {
    var id = "#" + $('a[uid ="' + shape.getID() + '"]').attr("desc");

    $(".treechild").attr("status", "unmarked");
    $(".treechild").css("background", "#fff");

    $(id).css("background", "#CEE3F6");
    $(id).attr("status", "marked");
//    var oShape = {};
};
