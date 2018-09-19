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
var PMSE = PMSE || {};
/**
 * @class LabelField
 * Handles the Label fields
 * @extends PMSE.Field
 *
 * @constructor
 * Creates a new instance of the class
 * @param {Object} options
 * @param {PMSE.Form} parent
 */
var LogField = function (options, parent) {
    PMSE.Field.call(this, options, parent);
    this.submit = false;
    this.items = [];
    this.deleteBtn = false;
    this.deleteControl = null;
    LogField.prototype.initObject.call(this, options);
    //$.extend(true, this.defaults, options);
};
LogField.prototype = new PMSE.Field();

/**
 * Defines the object's type
 * @type {String}
 */
LogField.prototype.type = 'LogField';

LogField.prototype.initObject = function (options) {
    var defaults = {
        marginLeft : 10,
        timeTextSize: 11,
        picture : '/img/default_user.png',
        user: '',
        showUser: true,
        message: 'default message',
        items : [],
        startDate: '3 July 2013',
        duration: null,
        completed: false,
        deleteBtn: false,
        script: false
    };
    $.extend(true, defaults, options);
    this.setMarginLeft(defaults.marginLeft)
        .setPicture(defaults.picture)
        .setUser(defaults.user)
        .setShowUser(defaults.showUser)
        .setTimeTextSize(defaults.timeTextSize)
        .setStartDate(defaults.startDate)
        .setMessage(defaults.message)
        .setDuration(defaults.duration)
        .setItems(defaults.items)
        .setCompleted(defaults.completed)
        .setDeleteBtn(defaults.deleteBtn)
        .setScript(defaults.script);
};

LogField.prototype.setMarginLeft = function (marginLeft) {
    this.marginLeft = marginLeft;
    return this;
};
LogField.prototype.setScript = function (scr) {
    this.script = scr;
    return this;
};
LogField.prototype.setPicture = function (picture) {
    this.picture = picture;
    return this;
};
LogField.prototype.setUser = function (user) {
    this.user = user;
    return this;
};
LogField.prototype.setShowUser = function(showUser) {
    this.showUser = showUser;
    return this;
};
LogField.prototype.setTimeTextSize = function (size) {
    this.timeTextSize = size;
    return this;
};
LogField.prototype.setStartDate = function (date) {
    this.startDate = date;
    return this;
};
LogField.prototype.setMessage = function (msg) {
    this.message = msg;
    return this;
};
LogField.prototype.setDuration = function (time) {
    this.duration = time;
    return this;
};
LogField.prototype.setItems = function (items) {
    this.items = items;
    return this;
};
LogField.prototype.setCompleted = function (val) {
    this.completed = val;
    return this;
};
LogField.prototype.setDeleteBtn = function (val) {
    this.deleteBtn = val;
    return this;
};
/**
 * Creates the HTML Element of the field
 */
LogField.prototype.createHTML = function () {
    var fieldLabel, logPicture, newsItem, datetime, detailDiv, durationDiv,buttonAnchor, labelSpan, that = this;
    PMSE.Field.prototype.createHTML.call(this);
    this.html.style.fontSize = "12px";
    this.html.style.display = 'table';
    this.html.style.width = '98%';
    detailDiv = this.createHTMLElement('div');
    detailDiv.style.width = '70 %';
    //detailDiv.style.cssFloat= 'left';
    detailDiv.style.display = 'table-cell';

    if (this.script){
        logPicture = this.createHTMLElement('div');
        logPicture.className = this.picture;
        logPicture.style.marginRight = "15px";
        logPicture.innerHTML = 'AW';
        detailDiv.appendChild(logPicture);
    } else {
        logPicture = this.createHTMLElement('img');
        logPicture.style.width = '32px';
        logPicture.style.height = '32px';
        logPicture.style.cssFloat = "left";
        logPicture.style.marginRight = "10px";
        logPicture.src = this.picture;
        detailDiv.appendChild(logPicture);
    }


    newsItem = this.createHTMLElement('p');
//    fieldLabel.className = 'adam-form-label';

    if (this.showUser) {
        newsItem.innerHTML = '<strong>' + this.user + '</strong> ';
    }
    newsItem.innerHTML += this.label;
    //fieldLabel.style.verticalAlign = 'top';
    newsItem.style.marginLeft = this.marginLeft + 'px';
    newsItem.style.display = "block";
    detailDiv.appendChild(newsItem);

    datetime  = this.createHTMLElement('time');
  //  datetime.dateTime = '2013-07-03T11:58:45-04:00';
    datetime.style.color = '#707070';
    datetime.style.fontSize = this.timeTextSize + "px";
    datetime.textContent = this.startDate;
    detailDiv.appendChild(datetime);

    this.html.appendChild(detailDiv);
    if (this.duration) {
        durationDiv = this.createHTMLElement('div');
        durationDiv.style.width = '15%';
        durationDiv.style.paddingLeft = '15px';
        durationDiv.style.display = 'table-cell';
        //durationDiv.style.height = '100%';
        durationDiv.style.color = '#707070';
        durationDiv.style.fontSize = this.timeTextSize + "px";
        durationDiv.innerHTML =  '<p> ' + this.duration + '</p>';
        //for tuning duration section
        this.durationSection = durationDiv;
        this.html.appendChild(durationDiv);
    }


   // if (this.completed) {
    durationDiv = this.createHTMLElement('div');
    durationDiv.style.width = '2%';
    durationDiv.style.paddingLeft = '5px';
    durationDiv.style.display = 'table-cell';
    //durationDiv.style.height = '100%';
    //durationDiv.style.color = '#707070';
    durationDiv.style.fontSize = this.timeTextSize + "px";
    //durationDiv.innerHTML =  '<p> true </p>';
    if (this.completed) {
        durationDiv.className = 'adam-completed-log';
    }

    this.html.appendChild(durationDiv);
//  }


    return this.html;
};
LogField.prototype.attachListeners = function () {
    var id, logPanel, logBefore, logMidle, that;
    that = this;
    $(this.html).click(function (e) {
        id = $(e.currentTarget).attr('id');

        if (that.parent.getLogField(id).parent.itemShowed
            && that.parent.getLogField(id).parent.itemShowed === id) {
            $("#logPanel").slideToggle();
            that.parent.getLogField(id).parent.itemShowed = null;
        } else {
            $('#logPanel').remove();
            if (that.parent.getLogField(id).items.length > 0) {
                logPanel = that.createHTMLElement('div');
                logPanel.id = "logPanel";
                logPanel.style.display = 'none';
                logPanel.style.overflow = 'auto';
                logPanel.style.padding = '10px';
                logPanel.style.border = "1px solid silver";
                logPanel.style.backgroundColor = '#FAFAFA';
                $('#' + id).after(logPanel);

                if (that.parent.logType === 'difList') {
                    //console.log('difList');
                    logBefore = that.parent.getLogField(id).createDifList('before');
                    $(logPanel).append(logBefore);

                    logMidle = that.createHTMLElement('div');
                    logMidle.style.width = '5%';
                    logMidle.style.cssFloat = 'left';
                    logMidle.innerHTML = '&nbsp;';
                    $(logPanel).append(logMidle);

                    logBefore = that.parent.getLogField(id).createDifList('after');
                    $(logPanel).append(logBefore);
                    that.parent.getLogField(id).parent.itemShowed = id;
                } else {
                    logPanel.innerHTML = '<h2 style="text-align: center; font-family: Verdana;">"' + that.message + '"<h2>';
                }
                $("#logPanel").slideToggle();
            } else {
                that.parent.getLogField(id).parent.itemShowed = null;
            }


        }

    });
//    $(this.deleteControl).click(function (e) {
//        e.preventDefault();
//        e.stopPropagation();
//        console.log('remove button');
//        console.log(that);
//    });
};
LogField.prototype.createDifList = function (type) {
    var logDiv, log, c = '', i, related;
    logDiv = this.createHTMLElement('div');
    logDiv.style.width = '45%';
    logDiv.style.position = 'relative';
    //logBefore.style.height = '100%';
    // logBefore.style.verticalAlign= 'middle';
    logDiv.style.cssFloat = 'left';
    //logBefore.style.padding = '5px';
    logDiv.style.backgroundColor = (type === 'before') ? '#fdd' : '#cfc';
    for (i = 0; i < this.items.length; i += 1) {
        related = this.items[i];
        log = this.createHTMLElement('p');
        c = (type === 'before') ? '-' : '+';
        c += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        c += related.field + ': ';
        c += (type === 'before') ? related.before : related.after;
        log.innerHTML = c;
        $(logDiv).append(log);

    }


    return logDiv;
};
LogField.prototype.setParent = function (parent) {
    this.parent = parent;
    return this;
};