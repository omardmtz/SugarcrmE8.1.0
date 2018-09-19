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
var CommandAnnotationResize = function (receiver) {
    jCore.CommandResize.call(this, receiver);
};

CommandAnnotationResize.prototype.type = 'commandAnnotationResize';

CommandAnnotationResize.prototype.execute = function () {
    jCore.CommandResize.prototype.execute.call(this);
    //this.receiver.graphics.clear();
    this.receiver.paint();
};

CommandAnnotationResize.prototype.undo = function () {
    jCore.CommandResize.prototype.undo.call(this);
    //this.receiver.graphics.clear();
    this.receiver.paint();
};

CommandAnnotationResize.prototype.redo = function () {
    this.execute();
};
