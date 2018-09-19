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

function CompanyDetailsDialog(div_id, text, x, y)
{
    this.div_id = div_id;
    this.text = text;
    this.width = 300;
    this.header = '';
    this.footer = '';
    this.x = x;
    this.y = y;
}

function header(header)
{
    this.header = header;
}

function footer(footer)
{
    this.footer = footer;
}

function display()
{
    if(typeof(dialog) != 'undefined')
        dialog.destroy();

    dialog = new YAHOO.widget.SimpleDialog(this.div_id,
        {
            width: this.width,
            visible: true,
            draggable: true,
            close: true,
            text: this.text,
            constraintoviewport: true,
            x: this.x,
            y: this.y
    });

    dialog.setHeader(this.header);
    dialog.setBody(this.text);
    dialog.setFooter(this.footer);
    dialog.render(document.body);
    dialog.show();
}

CompanyDetailsDialog.prototype.setHeader = header;
CompanyDetailsDialog.prototype.setFooter = footer;
CompanyDetailsDialog.prototype.display = display;
