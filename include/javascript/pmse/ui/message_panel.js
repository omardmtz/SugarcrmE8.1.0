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
// jscs:disable
/*globals PMSE.Container, $, PMSE.Modal, TabPanelElement, PMSE.Panel, PMSE.Base, document, PMSE.Button,
 setTimeout
 */
var PMSE = PMSE || {};
/**
 * @class MessagePanel
 * Handle window objects
 * @extends PMSE.Container
 *
 * @constructor
 * Creates a new instance of the window's class
 * @param {Object} options
 */
var MessagePanel = function (options) {
    PMSE.Container.call(this, options);
    /**
     * Defines the window's modal property
     * @type {Boolean}
     */
    this.modal = null;
    /**
     * Defines the PMSE.Modal Object to handle modal windows
     * @type {PMSE.Modal}
     */
    this.modalObject = null;
    /**
     * Defines the HTML Element to apply the modal mask
     * @type {HTMLElement}
     * @private
     */
    this.modalContainer = null;
    /**
     * Defines the Close PMSE.Button HTML Element
     * @type {HTMLElement}
     */
    this.closeButtonObject = null;
    /**
     * Defines the window header HTML Element where are placed the title label HTML Element and the Close PMSE.Button HTML Element
     * @type {HTMLElement}
     */
    this.windowHeader = null;
    /**
     * Records the loading state of the window
     * @type {Boolean}
     * @private
     */
    this.loaded = false;

    /**
     * Defines the DestroyOnHide property
     * @type {Boolean}
     */
    this.destroyOnHide = null;
    this.message = null;
    this.footer = null;
    this.buttons = [];
    this.footerHeight = null;
    this.headerHeight = null;
    this.positionFixed = false;
    MessagePanel.prototype.initObject.call(this, options);
};

MessagePanel.prototype = new PMSE.Container();

/**
 * Defines the object's type
 * @type {String}
 */
MessagePanel.prototype.type = "MessagePanel";
MessagePanel.prototype.classPictureMap = {
    'Information': 'adam-message-panel-picture-information',
    'Error': 'adam-message-panel-picture-error',
    'Warning': 'adam-message-panel-picture-warning',
    'Confirm': 'adam-message-panel-picture-question'
};
/**
 * Initialize the object with the default values
 */
MessagePanel.prototype.initObject = function (options) {
    var defaults = {
        title: '',
        modal: true,
        closeButton: true,
        modalHandler: null,
        destroyOnHide: false,
        wtype: 'Warning',
        message: '',
        footerHeight: 40,
        headerHeight: 0,
        buttons: [],
        height: 100,
        width: 400
    };
    $.extend(true, defaults, options);
    this.setTitle(defaults.title)
        .setModal(defaults.modal)
        .setVisible(false)
        .setCloseButton(defaults.closeButton)
        .setDestroyOnHide(defaults.destroyOnHide)
        .setMessageType(defaults.wtype)
        .setMessage(defaults.message)
        .setFooterHeight(defaults.footerHeight)
        .setHeaderHeight(defaults.headerHeight)
        .setButtons(defaults.buttons)
        .setHeight(defaults.height)
        .setWidth(defaults.width);

    this.modalContainer = $('body');
};

/**
 * Sets the window's title
 * @param {String} text
 */
MessagePanel.prototype.setTitle = function (text) {
    this.title = text;
    if (this.titleLabelObject) {
        this.titleLabelObject.innerHTML = text;
    }
    return this;
};
/**
 * Creates the HTML Element fot the object
 * @return {*}
 */
MessagePanel.prototype.createHTML = function () {

    var marginProps, closeBtn, titleLabel, windowHeader, tabsContainer, i, footerDiv, pictureDiv, textDiv;
    PMSE.Container.prototype.createHTML.call(this);
    marginProps = '-' + parseInt(this.height / 2, 10) + 'px 0 0 -' + parseInt(this.width / 2, 10) + 'px';
    //this.style.addClasses(['adam-message-panel']);
    this.style.addClasses(['adam-message-panel']);
    this.style.addProperties({
        'z-index': 1034,
        'left': '50%',
        'top': '50%'

        //'margin': marginProps

//        'height': 'auto',
//        'width': 'auto'
//        'height': '50px',
//        'width': '200px'
    });

    this.height -= 16;
    this.html.style.height = this.height + "px";
    this.html.tabIndex = "-1";

    windowHeader = this.createHTMLElement('div');
    windowHeader.className = 'adam-message-panel-header';
    titleLabel = this.createHTMLElement('label');
    titleLabel.className = 'adam-message-panel-title';
    titleLabel.innerHTML = this.title || "&nbsp;";
    titleLabel.title = titleLabel.innerHTML;
    if (this.closeButton) {
        closeBtn = this.createHTMLElement('span');
        closeBtn.className = 'adam-message-panel-close';
        windowHeader.appendChild(closeBtn);
        this.html.insertBefore(windowHeader, this.body);
        this.closeButtonObject = closeBtn;
    } else {
        this.html.insertBefore(windowHeader, this.body);
    }
    windowHeader.appendChild(titleLabel);

//    tabsContainer = this.createHTMLElement("ul");
//    tabsContainer.className = 'adam-tabs';
//    this.html.insertBefore(tabsContainer, this.body);
//    this.tabsContainer = tabsContainer;
//
////    for(i = 0; i < this.panels.length; i += 1) {
////        tabsContainer.appendChild(this.panels[i].getTab());
////    }
//
//    if(i <= 1) {
//        tabsContainer.style.display = 'none';
//    }
//
    this.windowHeader = windowHeader;
//    this.titleLabelObject = titleLabel;

    //this.html.appendChild(windowHeader);
    if (this.body) {
        this.body.className = 'adam-message-panel-body';
        this.body.style.textAlign = 'center';
        this.body.style.paddingTop = '10px';
        this.body.style.paddingBottom = '10px';
        pictureDiv = this.createHTMLElement('div');
        //pictureDiv.className = 'adam-message-panel-picture-information';
        pictureDiv.className = this.classPictureMap[this.wtype];
        this.body.appendChild(pictureDiv);
        textDiv = this.createHTMLElement('div');
        textDiv.className = 'adam-message-panel-text';
        textDiv.innerHTML = this.getMessage() || "&nbsp;";
        //textDiv.style.display = 'inline-block';
        //textDiv.style.width = '82%';
        this.body.appendChild(textDiv);

        //this.body.style.height = (this.height - 22 - (i > 1 ? 22 : 0)) + 'px';
        //this.body.innerHTML = this.getMessage() || "&nbsp;";
    }
    this.generateButtons(this.wtype);

    if (this.footer) {
        this.html.appendChild(this.footer);
    } else {
        footerDiv = this.createHTMLElement('div');
        footerDiv.className = 'adam-message-panel-footer';
        this.html.appendChild(footerDiv);
        this.footer = footerDiv;
    }


    for (i = 0; i < this.buttons.length; i += 1) {
        this.footer.appendChild(this.buttons[i].getHTML());
    }
    this.body.style.bottom = (this.footerHeight + 8) + 'px';
    this.footer.style.height = this.footerHeight + 'px';
    this.footer.style.textAlign = 'right';
    //this.footer.style.position = 'absolute';
    this.footer.style.bottom = '0px';
    //this.addButtons();
    return this.html;
};

/**
 * Shows the Message panel
 */
MessagePanel.prototype.show = function (params) {
    if (!this.loaded) {
        this.load(params);
    }

    this.setHeight($(this.body).innerHeight());

    if (this.modal) {
        this.modalObject.show(this);
        if (this.modalObject.html) {
            this.modalObject.html.style.zIndex = '1034';
        }
    } else {
        document.body.appendChild(this.html);
    }

    this.setVisible(true);
    this.fixPositions();
};
/**
 * Sets the window's modal property
 * @param {Boolean} value
 */
MessagePanel.prototype.setModal = function (value) {
    if (value) {
        this.modalObject = new PMSE.Modal({
            clickHandler: this.modalHandler
        });
    } else {
        this.modalObject = null;
    }
    this.modal = value;
    return this;
};
/**
 * Opens/Creates the windows object
 * @private
 */
MessagePanel.prototype.load = function (params) {
    var titleLabel;
    if (!this.html) {
        this.createHTML();
        this.attachListeners();
        this.loaded = true;
    }
};
/**
 * Sets the destroy on hide property
 * @param {Boolean} value
 * @return {*}
 */
MessagePanel.prototype.setDestroyOnHide = function (value) {
    this.destroyOnHide = value;
    return this;
};

/**
 * Sets the close Button property
 * @param {Boolean} value
 * @return {*}
 */
MessagePanel.prototype.setCloseButton = function (value) {
    this.closeButton = value;
    return this;
};
/**
 * Sets the window listeners
 */
MessagePanel.prototype.attachListeners = function () {
    var self = this,
        i,
        btn,
        focushandler,
        that = this;
    $(this.html).draggable({
        cursor: "move",
        scroll: false,
        containment: "document"
    }).on('keydown keyup keypress', function (e) {
        e.stopPropagation();
    });

    if (this.closeButton && this.closeButtonObject) {
        $(this.closeButtonObject).click(function (e) {
            e.stopPropagation();
            self.hide();
        });
    }
    for (i = 0; i < this.buttons.length; i += 1) {
        this.buttons[i].attachListeners();
    }
    $('input').blur();
    $('a').blur();

    $(this.html).attr('tabindex', -1).focus();

    setTimeout(function () {
        $(document).on('focusin', focushandler);
    }, 0);

    focushandler = function (e) {
        if (!$(e.target).parents().addBack().is('#' + that.id)) {
            $(that.html).focus();
        }
    };

};
MessagePanel.prototype.setMessage = function (msg) {
    this.message = msg;
    return this;
};

MessagePanel.prototype.getMessage = function (msg) {
    return this.message;
};
MessagePanel.prototype.setMessageType = function (type) {
    this.wtype = type;
    return this;
};

MessagePanel.prototype.getMessageType = function (type) {
    return this.wtype;
};

/**
 * Hides the window
 * @param {Boolean} [destroy]
 */
MessagePanel.prototype.hide = function (destroy) {
    if (this.modal) {
        this.modalObject.hide();
    } else {
        document.body.removeChild(this.html);
    }
    this.setVisible(false);
    if (destroy || this.destroyOnHide) {
        this.close();
    }
};
MessagePanel.prototype.generateButtons = function (type) {

    var btns = [],
        that = this;
    if (this.buttons.length === 0) {
        switch (type) {
        case 'Information':
        case 'Error':
        case 'Warning':
        case 'Confirm':
            this.addButton({
                jtype: 'normal',
                caption: 'OK',
                handler: function () {
                    //console.log(this);
                    //alert('handler');
                    that.close();
                    //wAlert.close();
                    //fAlert.submit();
                }
            });
            break;
        }
        //this.setButtons(btns);
    }


    return this;

};

/**
 * Sets the buttons
 * @param {Array} buttons
 * @return {*}
 */
MessagePanel.prototype.setButtons = function (buttons) {
    var i;
    for (i = 0; i < buttons.length; i += 1) {
        this.addButton(buttons[i], this);
    }
    return this;
};

MessagePanel.prototype.addButton = function (button) {
    var newButton;
    if (button && button.family && button.family === 'PMSE.Button') {
        newButton = button;
        newButton.setParent(this);
    } else {
        newButton = new PMSE.Button(button, this);
    }
    if (newButton) {
        this.buttons.push(newButton);
    }
};
MessagePanel.prototype.setHeight = function (height) {
    var bodyHeight;
    //PMSE.Container.prototype.setHeight.call(this, height);
    bodyHeight = this.height - this.footerHeight - this.headerHeight;
    //console.log(bodyHeight);
    this.setBodyHeight(bodyHeight);
    return this;
};

MessagePanel.prototype.setFooterHeight = function (width) {
    this.footerHeight = width;
    return this;
};

MessagePanel.prototype.setHeaderHeight = function (width) {
    this.headerHeight = width;
    return this;
};
/**
 * Close the window and destroy the object
 */
MessagePanel.prototype.close = function () {
    if (this.visible) {
        this.hide();
    }
    if (this.dispose) {
        this.dispose();
    }
};
MessagePanel.prototype.fixPositions = function () {
    if (!this.positionFixed) {
        var width = $(this.html).width(),
            height = $(this.html).height(),
            position = $(this.html).offset(),
            x,
            y;
        x = position.top - height / 2;
        y = position.left - width / 2;
        this.html.style.top = x + 'px';
        this.html.style.left = y + 'px';
        this.positionFixed = true;
    }

    return this;
};