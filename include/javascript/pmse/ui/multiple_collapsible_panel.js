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
var MultipleCollapsiblePanel = function (settings) {
    CollapsiblePanel.call(this, jQuery.extend(true, {bodyHeight: 100}, settings));
    this._selectedPanel = null;
    this._panelList = null;
    this._htmlContent = null;
    this._htmlContentHeader = null;
    this._htmlContentTitle = null;
    this._lastSelectedPanel = null;
    this._selectedPanel = null;
    this._fastAccessObject = {};
    this._originalBodyHeight = null;
    MultipleCollapsiblePanel.prototype.init.call(this, settings);
};

MultipleCollapsiblePanel.prototype = new CollapsiblePanel();
MultipleCollapsiblePanel.prototype.constructor = MultipleCollapsiblePanel;
MultipleCollapsiblePanel.prototype.type = "MultipleCollapsiblePanel";

MultipleCollapsiblePanel.prototype.init = function () {
    this._panelList = new ListPanel({
        itemsContent: this._panelListItemContent(),
        onItemClick: this._onPanelListItemClick(),
        collapsed: false
    });
};

MultipleCollapsiblePanel.prototype.isParentOf = function (item) {
    return !!this.getItem(item.id);
};

MultipleCollapsiblePanel.prototype.getItem = function (item) {
    var searchedItem = null;

    if (typeof item === 'string') {
        searchedItem = this._items.find('id', item);
    } else if (typeof item === 'number') {
        searchedItem = this._items.get(item);
    } else if (item instanceof CollapsiblePanel && this.isParentOf(item)) {
        searchedItem = item;
    }

    return searchedItem;
};

MultipleCollapsiblePanel.prototype.disableItem = function(item) {
    var itemToChange = this.getItem(item);

    if (itemToChange) {
        itemToChange.disable();
    }
    return this;
};

MultipleCollapsiblePanel.prototype.enableItem = function(item) {
    var itemToChange = this.getItem(item);

    if (itemToChange) {
        itemToChange.enable();
    }
    return this;
};

MultipleCollapsiblePanel.prototype._onItemEnablementStatusChange = function () {
    var that = this;
    return function (item, active) {
        var accessObject = that._fastAccessObject[item.id],
            listItem = accessObject.listItem;
        listItem.setVisible(active);
        if (active) {
            accessObject.panel.expand();
        } else {
            if (!that.isCollapsed() && that._selectedPanel === item) {
                that.displayMenu(true);
            }
        }
    };
};

MultipleCollapsiblePanel.prototype._panelListItemContent = function () {
    var that = this;
    return function (listItem, data) {
        var a = this.createHTMLElement("a"),
            span = this.createHTMLElement("span"),
            i = this.createHTMLElement("i");
        a.className = "adam list-item-content";
        i.className = "adam list-item-arrow fa fa-arrow-circle-right";
        span.textContent = data["text"];
        a.appendChild(span);
        a.appendChild(i);
        return a;
    };
};

MultipleCollapsiblePanel.prototype._onPanelListItemClick = function () {
    var that = this;
    return function (listPanel, item) {
        that.displayPanel(item.getData().id);
    };
};

MultipleCollapsiblePanel.prototype._clearContent = function () {
    var nodes;
    if (this._htmlContent) {
        nodes = this._htmlContent.childNodes;
        while (nodes.length > 1) {
            if (nodes[0].remove) {
                this._htmlContent.lastChild.remove();
            } else {
                this._htmlContent.lastChild.removeNode(true);
            }
        }
    }

    return true;
};

MultipleCollapsiblePanel.prototype.expand = function (noAnimation) {
    this.displayMenu(true);
    CollapsiblePanel.prototype.expand.call(this, noAnimation);
    return this;
};

/*MultipleCollapsiblePanel.prototype.isParentOf = function (panel) {
    return !!this._items.indexOf(panel);
};*/

MultipleCollapsiblePanel.prototype.displayPanel = function (panel) {
    var panelToDisplay = this._items.find("id", panel), bodyHeight, contentHeaderHeight, w;
    if(this._selectedPanel !== panelToDisplay) {
        this._selectedPanel = panelToDisplay;
        if(this.html) {
            if (this._lastSelectedPanel !== panelToDisplay) {
                this._selectedPanel.getHTML();
                this._clearContent();
                this._htmlContentTitle.textContent = this._selectedPanel.getTitle();
                this._htmlContent.appendChild(this._selectedPanel._htmlBody);
            }

            bodyHeight = jQuery(this._selectedPanel._htmlBody).outerHeight();
            contentHeaderHeight = jQuery(this._htmlContentHeader).outerHeight();
            this._originalBodyHeight = this._bodyHeight;
            this.setBodyHeight(bodyHeight + contentHeaderHeight);

            w = $(this._htmlBody).innerWidth();
            this._htmlContent.style.left = w + "px";
            jQuery(this._panelList._htmlBody).animate({
                left: "-=" + w + "px"
            });
            jQuery(this._htmlContent).animate({
                left: 0
            });
        }
    }
};

MultipleCollapsiblePanel.prototype.displayMenu = function (noAnimation) {
    var w, selectedPanel;
    if (this._selectedPanel) {
        this.setBodyHeight(this._originalBodyHeight);
        selectedPanel = this._selectedPanel;
        this._lastSelectedPanel = this._selectedPanel;
        this._selectedPanel = null;
        this._panelList._htmlBody.scrollTop = 0;
        w = parseInt(this._panelList._htmlBody.style.left, 10) * -1;//jQuery(this._htmlBody).innerWidth(); //jQuery(this._panelList._htmlBody).outerWidth();
        if (noAnimation) {
            this._panelList._htmlBody.style.left = "0px";
            this._htmlContent.style.left = w + "px";
        } else {
            jQuery(this._panelList._htmlBody).add(this._htmlContent).animate({
                left: "+=" + w + "px"
            });
        }
        if (typeof selectedPanel.onCollapse === 'function') {
            selectedPanel.onCollapse(selectedPanel);
        }
    }
    return this;
};

MultipleCollapsiblePanel.prototype.setBodyHeight = function (height) {
    if (isNaN(height)) {
        throw new Error("setBodyHeight(): The parameter must be a number.");
    }
    this._bodyHeight = height;
    if(this._htmlBody) {
        this._htmlBody.style.maxHeight = this._htmlBody.style.height = height + "px";
    }
    return this;
};

MultipleCollapsiblePanel.prototype.clearItems = function () {
    this.displayMenu();
    if (this._panelList) {
        this._panelList.clearItems();
    }
    this._items.clear();
    return this;
};

MultipleCollapsiblePanel.prototype._paintItems = function () {
    var i, items;
    if (this._panelList) {
        items = this._items.asArray();
        this._panelList.clearItems();
        for (i = 0; i < items.length; i += 1) {
            this._paintItem(items[i]);
        }
    }
    return this;
};

MultipleCollapsiblePanel.prototype._paintItem = function (item) {
    var items;
    if (this._panelList) {
        this._panelList.addItem({
            data: {
                id: item.id,
                text: item.getTitle()
            },
            visible: !item.isDisabled()
        });
        items = this._panelList.getItems();
        this._fastAccessObject[item.id] = {
            listItem: items[items.length - 1],
            panel: item
        };
    }
    return this;
};

MultipleCollapsiblePanel.prototype._createItem = function (item) {
    var newItem;
    //item.onValueAction = this._onSubpanelItemAction();
    switch (item.type) {
        case "form":
            newItem = new FormPanel(item);
            break;
        case "list":
            newItem = new ListPanel(item);
            break;
        default:
            throw new Error("_createItem(): The parameter has an invalid \"type\" property.");
    }
    return newItem;
};

MultipleCollapsiblePanel.prototype.getValueObject = function (args) {
    return args.value;
};

MultipleCollapsiblePanel.prototype._onValueAction = function (anyArgument) {
    if(typeof this.onValueAction === 'function') {
        this.onValueAction(anyArgument.panel, this.getValueObject(anyArgument));
    }
    return this;
};

MultipleCollapsiblePanel.prototype._onSubpanelItemAction = function () {
    var that = this;
    return function (panel, panelValue) {
        that._onValueAction({panel: panel, value: panelValue});
    };
};

MultipleCollapsiblePanel.prototype.addItem = function(item) {
    var itemToAdd;
    if (item instanceof CollapsiblePanel) {
        itemToAdd = item;
    } else if (typeof item === 'object') {
        itemToAdd = this._createItem(item);
    } else {
        throw new Error("addItem(): The parameter must be an instance of CollapsiblePanel or an object.");
    }
    itemToAdd.setParent(this)
        .setOnValueActionHandler(this._onSubpanelItemAction())
        .setOnEnablementStatusChangeHandler(this._onItemEnablementStatusChange())
        .disableAnimations();
    this._items.insert(itemToAdd.expand());

    if (!this._massiveAction) {
        this._paintItem(item);
    }
    return this;
};

MultipleCollapsiblePanel.prototype.removeItem = function (item) {
    var itemToRemove = this.getItem(item);

    if (itemToRemove) {
        this._items.remove(itemToRemove);
        delete this._fastAccessObject[itemToRemove.id];
        if (this.html) {
            if (itemToRemove.html.remove) {
                itemToRemove.html.remove()
            } else {
                itemToRemove.html.removeNode(true);
            }
        }
    }

    return this;
};

MultipleCollapsiblePanel.prototype._attachListeners = function () {
    var that;
    if(this.html && !this._attachedListeners) {
        that = this;
        CollapsiblePanel.prototype._attachListeners.call(this);
        jQuery(this._htmlContentBackButton).on('click', function() {
            that.displayMenu();
        });
    }
    return this;
};

MultipleCollapsiblePanel.prototype._createBody = function () {
    var body, content, contentHeader, contentTitle, backButton;
    if (!this._htmlBody) {
        body = this.createHTMLElement("div");
        //body.className = "adam multiple-panel-body";
        content = this.createHTMLElement("div");
        content.className = "adam multiple-panel-content";
        contentHeader = this.createHTMLElement("header");
        contentHeader.className = "adam multiple-panel-contentheader";
        contentTitle = this.createHTMLElement("span");
        contentTitle.className = "adam multiple-panel-title";
        backButton = this.createHTMLElement("i");
        backButton.className = "adam multiple-panel-back fa fa-arrow-circle-left";

        this._panelList.getHTML();
        this._panelList._htmlBody.className += " adam-main-list";

        contentHeader.appendChild(contentTitle);
        contentHeader.appendChild(backButton);
        content.appendChild(contentHeader);
        body.appendChild(this._panelList._htmlBody);
        body.appendChild(content);

        this._htmlContent = content;
        this._htmlContentHeader = contentHeader;
        this._htmlContentTitle = contentTitle;
        this._htmlContentBackButton = backButton;
        this._htmlBody = body;
    }
    return this._htmlBody;
};

MultipleCollapsiblePanel.prototype.createHTML = function () {
    if (!this.html) {
        CollapsiblePanel.prototype.createHTML.call(this);
        this.html.className += " multiple-panel";
    }
    return this.html;
};
