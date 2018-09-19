/* =========================================================
 * bootstrap-datepicker.js 
 * http://www.eyecon.ro/bootstrap-datepicker
 * =========================================================
 * Copyright 2012 Stefan Petre
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */
 
!function( $ ) {

	// Picker object
	var Datepicker = function(element, options){
		this.element = $(element);
		this.rawFormat = options.format||this.element.data('date-format')||'mm/dd/yyyy';
        this.appendTo = options.appendTo;
        this.format = DPGlobal.parseFormat(this.rawFormat);
		this.possibleChars = DPGlobal.getPossibleDateChars(this.rawFormat);
		this.picker = $(DPGlobal.template)
                            .appendTo(this.appendTo?this.appendTo:"body")
							.on({
								click: $.proxy(this.click, this),
								mousedown: $.proxy(this.mousedown, this)
							});
		this.isInput = this.element.is('input');
		this.component = this.element.is('.date') ? this.element.find('.add-on') : false;


		if (this.isInput) {
			this.element.on({
				focus: $.proxy(this.show, this),
				blur: $.proxy(this.hide, this),
				keyup: $.proxy(this.update, this),
				keydown: $.proxy(this.keydown, this),
				keypress: $.proxy(this.keypress, this),
				click: $.proxy(this.click, this)
			});
			// If we have a button with .add-on stand class bind to our show:
			if (this.element.parent().find('.add-on').length) {
			    // Only proxy on the first one (e.g. calendar icon; if a SugarCRM datetimecombo, we'll have a
			    // "clock" for time and we don't want to open the calendar when that's clicked (UIUX-1110 / SP-1362)
			    // Clicking on clock time icon is handled by the datetimecombo.js controller in SugarCRM.
				this.element.parent().find('.add-on[data-icon=calendar]').on({
					click: $.proxy(this.focusShow, this)
				});
			}
		} else {
			if (this.component){
				this.component.on('click', $.proxy(this.show, this));
			} else {
				this.element.on('click', $.proxy(this.show, this));
			}
		}
		this.minViewMode = options.minViewMode||this.element.data('date-minviewmode')||0;
		if (typeof this.minViewMode === 'string') {
			switch (this.minViewMode) {
				case 'months':
					this.minViewMode = 1;
					break;
				case 'years':
					this.minViewMode = 2;
					break;
				default:
					this.minViewMode = 0;
					break;
			}
		}
		this.viewMode = options.viewMode||this.element.data('date-viewmode')||0;
		if (typeof this.viewMode === 'string') {
			switch (this.viewMode) {
				case 'months':
					this.viewMode = 1;
					break;
				case 'years':
					this.viewMode = 2;
					break;
				default:
					this.viewMode = 0;
					break;
			}
		}
		this.startViewMode = this.viewMode;
		this.weekStart = options.weekStart||this.element.data('date-weekstart')||0;
		this.enableKeyboard = options.enableKeyboard||true;
		if (options.languageDictionary) {
			this.languageDictionary(options.languageDictionary);	
		}
		this.weekEnd = this.weekStart === 0 ? 6 : this.weekStart - 1;

		this.dates = options.languageDictionary || {  // defaults to English
			days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
			daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
			daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
			months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
		};
		this.fillDow();
		this.fillMonths();
		this.update();
		this.showMode();
	};
	
	Datepicker.prototype = {
		constructor: Datepicker,

		// Precondition: We presume that datesLangDict exists and is an object literal
		languageDictionary: function(datesLangDict) {
			var reqPropsIndx,
				requiredProperties = ['days', 'daysShort', 'daysMin', 'months', 'monthsShort'];

			// If all required properties are not included in datesLangDict we return false
			for (reqPropsIndx = 0; reqPropsIndx < requiredProperties.length; reqPropsIndx++) {
				if (typeof datesLangDict[requiredProperties[reqPropsIndx]] === 'undefined') {
					return false;
				}
			}
			this.dates = datesLangDict;
			return true;
		},
		getLanguageDictionary: function() {
			return this.dates;
		},
		focusShow: function(e) {
			this.element[0].focus();
		},	
		show: function(e) {
			this.picker.show();
			this.height = this.component ? this.component.outerHeight() : this.element.outerHeight();
			this.place();
			$(window).on('resize', $.proxy(this.place, this));
			if (e ) {
				e.stopPropagation();
				e.preventDefault();
			}
			if (!this.isInput) {
				$(document).on('mousedown', $.proxy(this.hide, this));
			}
			this.element.trigger({
				type: 'show',
				date: this.date
			});
			this.hidden = false;
		},
		hide: function(){
			this.picker.hide();
            // TODO: this was moved here 'cause it's what makes more sense plus
            // it avoids getting into a loop under certain circumstances, we
            // should check if the upgrade to the latest version solves this
            // issue.
            this.hidden = true;
			$(window).off('resize', this.place);
			this.viewMode = this.startViewMode;
			this.showMode();
			if (!this.isInput) {
				$(document).off('mousedown', this.hide);
			} else {
				// Only set the date, etc., if we have a valid date value 
				// in our input. Otherwise, we'll let "save validation" handle
				if (!this.verifyDate(this.element.val())) {
					this.element.trigger({
						type: 'hide'
					});
					return;
				}
			}
			// Replace set() by setValue() call in order to use
			// this.element.val() instead of this.date - this problem came up
			// on SC-2380.
			//
			// We should do an upgrade to the latest version, which seems to fix
			// this issue (at the time being), though, we seem to have a lot of
			// internal customizations which should be handled with care.
			this.setValue(this.element.val());
			this.element.trigger({
				type: 'hide',
				date: this.date
			});
		},

		set: function() {
			var formated = DPGlobal.formatDate(this.date, this.format);
			if (!this.isInput) {
				if (this.component){
					this.element.find('input').prop('value', formated);
				}
				this.element.data('date', formated);
			} else {
				this.element.prop('value', formated);
			}
		},
		setValue: function(newDate) {
			if (typeof newDate === 'string') {
				this.date = DPGlobal.parseDate(newDate, this.format).dateObject;
			} else {
				this.date = new Date(newDate);
			}
			this.set();
			this.viewDate = new Date(this.date.getFullYear(), this.date.getMonth(), 1, 0, 0, 0, 0);
			this.fill();
		},
		place: function(){
            var offset = this.component ? this.component.offset() : this.element.offset();
            if (this.appendTo && this.appendTo != "body") {
                offset.left = (this.element.offset().left - $(this.appendTo).offset().left) + $(this.appendTo).scrollLeft();
                offset.top = (this.element.offset().top - $(this.appendTo).offset().top) + $(this.appendTo).scrollTop();
            }
			this.picker.css({
				top: offset.top + this.height,
				left: offset.left
			});
		},
		verifyDate: function(dateString) {
			return DPGlobal.parseDate(dateString, this.format).inputDateStringWasValid;
		},
		verifyInput: function(dateInput) {
			return this.possibleChars.toLowerCase().indexOf(dateInput) !== -1;
		},
		update: function(newDate){
			var dateInput =
				typeof newDate === 'string' ? newDate : (this.isInput ? this.element.prop('value') : this.element.data('date'));
			this.date = DPGlobal.parseDate(
				dateInput,
				this.format
			).dateObject;
			try {
				//might be an invalid date (since we now leave those so validation framework can uniformly handle)
				this.viewDate = new Date(this.date.getFullYear(), this.date.getMonth(), 1, 0, 0, 0, 0);
				this.fill();
			} catch(e) {}
			return true;
		},
		fillDow: function(){
			var dowCnt = this.weekStart;
			var html = '<tr>';
			while (dowCnt < this.weekStart + 7) {
				//FIXME: SC-4404 Should use `this.dates.daysMin` after
				// translations for `dom_cal_day_min` are integrated
				// use daysMin by default, fall back to daysShort when daysMin is not available
				var daysMinShort = "";
				if (!_.isEmpty(this.dates.daysMin)) {
					daysMinShort = this.dates.daysMin[(dowCnt++)%7];
				} else {
					daysMinShort = this.dates.daysShort[(dowCnt++)%7];
				}
				html += '<th class="dow">'+ daysMinShort +'</th>';
			}
			html += '</tr>';
			this.picker.find('.datepicker-days thead').append(html);
		},
		fillMonths: function(){
			var html = '';
			var i = 0;
			while (i < 12) {
				html += '<span class="month">'+this.dates.monthsShort[i++]+'</span>';
			}
			this.picker.find('.datepicker-months td').append(html);
		},
		fill: function() {
			var d = new Date(this.viewDate),
				year = d.getFullYear(),
				month = d.getMonth(),
				currentDate = this.date.valueOf();
			this.picker.find('.datepicker-days th:eq(1)')
						.text(this.dates.months[month]+' '+year);
			var prevMonth = new Date(year, month-1, 28,0,0,0,0),
				day = DPGlobal.getDaysInMonth(prevMonth.getFullYear(), prevMonth.getMonth());
			prevMonth.setDate(day);
			prevMonth.setDate(day - (prevMonth.getDay() - this.weekStart + 7)%7);
			var nextMonth = new Date(prevMonth);
			nextMonth.setDate(nextMonth.getDate() + 42);
			nextMonth = nextMonth.valueOf();
			var html = [];
			var clsName,
				prevY,
				prevM;
			while(prevMonth.valueOf() < nextMonth) {
				if (prevMonth.getDay() === this.weekStart) {
					html.push('<tr>');
				}
				clsName = '';
				prevY = prevMonth.getFullYear();
				prevM = prevMonth.getMonth();
				if ((prevM < month &&  prevY === year) ||  prevY < year) {
					clsName += ' old';
				} else if ((prevM > month && prevY === year) || prevY > year) {
					clsName += ' new';
				}
				if (prevMonth.valueOf() === currentDate) {
					clsName += ' active';
				}
				html.push('<td class="day'+clsName+'">'+prevMonth.getDate() + '</td>');
				if (prevMonth.getDay() === this.weekEnd) {
					html.push('</tr>');
				}
				prevMonth.setDate(prevMonth.getDate()+1);
			}
			this.picker.find('.datepicker-days tbody').empty().append(html.join(''));
			var currentYear = this.date.getFullYear();
			
			var months = this.picker.find('.datepicker-months')
						.find('th:eq(1)')
							.text(year)
							.end()
						.find('span').removeClass('active');
			if (currentYear === year) {
				months.eq(this.date.getMonth()).addClass('active');
			}
			
			html = '';
			year = parseInt(year/10, 10) * 10;
			var yearCont = this.picker.find('.datepicker-years')
								.find('th:eq(1)')
									.text(year + '-' + (year + 9))
									.end()
								.find('td');
			year -= 1;
			for (var i = -1; i < 11; i++) {
				html += '<span class="year'+(i === -1 || i === 10 ? ' old' : '')+(currentYear === year ? ' active' : '')+'">'+year+'</span>';
				year += 1;
			}
			yearCont.html(html);
		},
		keypress: function(e) {
			function isFirefoxDeleteOrTabKey() {
				if (e.keyCode===46 && e.which===0 || e.keyCode===9 && e.which===0) {
					return true;
				}
				return false;
			}

			switch(e.keyCode){
				// Ignore all these
				case 27: // escape
				case 33: // Page up 
				case 34: // Page down 
				case 36: // Home 
				case 35: // End
				case 37: // left
				case 39: // right
				case 38: // up
				case 40: // down
				case 32: // space
				case 13: // enter
				case 8:  // backspace 
					break;
				default: 
					// '/', '.', '-' separators not reliable in keydown,
					// so, we handle here using keypress event instead ;-)
					// http://unixpapa.com/js/key.html
					// if not firefox delete key
					if (!isFirefoxDeleteOrTabKey()) {
						var chr = String.fromCharCode(e.which);
						if (!this.verifyInput(chr)) {
							e.preventDefault();
						}
					}
					break;
			}
		},
		keydown: function(e) {
			var self = this, sign, day, month, direction, newDate, newViewDate, dateChanged = false, 
				element, isYearBasedMove, yearMultiplier;

			function _updateDate(type, sign, multiplier) {
				multiplier = multiplier || 1;

				if (type === 'months') {
					// Move month or year (dependent on whether they have the Shift key down as well
					newDate     = DPGlobal.multiplyMonths(self.date, sign, (1 * multiplier));
					newViewDate = DPGlobal.multiplyMonths(self.viewDate, sign, (1 * multiplier));
				} else {
					// Days
					newDate     = new Date(self.date);
					newDate.setDate(self.date.getDate() + (sign * multiplier));
					newViewDate = new Date(self.viewDate);
					newViewDate.setDate(self.viewDate.getDate() + (sign * multiplier));
				}
				dateChanged = true;
			}

			// Reader beware - fall throughs used heavily!
			switch(e.keyCode){
				case 27: // escape
					this.hide();
					e.preventDefault();
					break;
				case 33: // Page up 
				case 34: // Page down 
					if (!this.enableKeyboard) break;
					direction = e.keyCode == 33 ? -1 : 1;
					// Move month or year (dependent on whether they have the Shift key down as well
					yearMultiplier = (e.shiftKey) ? 12 : 1;
					_updateDate('months', direction, yearMultiplier);
					break;
				case 36: // Home 
				case 35: // End
					if (!this.enableKeyboard) break;
					direction = e.keyCode == 36 ? -1 : 1;

					// shiftKey means go to first or last day of year
					isYearBasedMove = (e.shiftKey) ? true : false;
					if (isYearBasedMove) {
						// Move to first/laste day of this year
						if (direction < 0) {
							// go to first day of year
							day = 1;
							month = 0;
						} else {
							// go to last day of year
							day = 31;
							month = 11;
						}
						newDate     = new Date(self.date.getFullYear(), month, day, 0, 0, 0);
						newViewDate = new Date(self.viewDate.getFullYear(), month, day, 0, 0, 0);
					} else {
						// Month based move .. move to first/last day of month
						// 
						// If HOME (-1) we want first of month - set day at 1 and addmonth to 0
						// If END  (+1) we want last of month  - set day at 0 and addmonth to 1
						// Tricky part is next month with day at 0 gives last of previous month ;-)
						day      = (direction < 0) ? 1 : 0; 
						addmonth = (direction < 0) ? 0 : 1; 
						newDate     = new Date(self.date.getFullYear(), (self.date.getMonth()+addmonth), day, 0, 0, 0);
						newViewDate = new Date(self.viewDate.getFullYear(), (self.viewDate.getMonth()+addmonth), day, 0, 0, 0);
					}
					dateChanged = true;
					break;
				case 37: // left
				case 39: // right
					if (!this.enableKeyboard) break;
					direction = e.keyCode == 37 ? -1 : 1;
					_updateDate('days', direction);
					break;
				case 38: // up
				case 40: // down
					if (!this.enableKeyboard) break;
					direction = e.keyCode == 38 ? -1 : 1;
					_updateDate('days', direction, 7);
					break;
				case 32: // space
				case 13: // enter
					this.hide();
					e.preventDefault();
					break;
				// case 9: Tabs ... letting browser handle this ;=)
				default: 
					break;
			}
			// If date changed, reset internal date representations, update date displayed, and fire changeDate event
			if(dateChanged) {
				this.date = newDate;
				this.viewDate = newViewDate;
				this.setValue(this.date);
				this.update(this.date);
				e.preventDefault();

				this.element.trigger({
					type: 'changeDate',
					date: this.date
				});
				if (this.isInput) {
					element = this.element;
				} else if (this.component){
					element = this.element.find('input');
				}
				if (element) {
					element.change();
				}
			}
		},	
		click: function(e) {
			e.stopPropagation();
			e.preventDefault();
			var target = $(e.target).closest('span, td, th, input');
			if (target.length === 1) {
				switch(target[0].nodeName.toLowerCase()) {
					case 'input':
						if (this.hidden) {
							this.show();
						}
						break;
					case 'th':
						switch(target[0].className) {
							case 'switch':
								this.showMode(1);
								break;
							case 'prev':
							case 'next':
								this.viewDate['set'+DPGlobal.modes[this.viewMode].navFnc].call(
									this.viewDate,
									this.viewDate['get'+DPGlobal.modes[this.viewMode].navFnc].call(this.viewDate) + 
									DPGlobal.modes[this.viewMode].navStep * (target[0].className === 'prev' ? -1 : 1)
								);
								this.fill();
								this.set();
								break;
						}
						break;
					case 'span':
						if (target.is('.month')) {
							var month = target.parent().find('span').index(target);
							this.viewDate.setMonth(month);
						} else {
							var year = parseInt(target.text(), 10)||0;
							this.viewDate.setFullYear(year);
						}
						if (this.viewMode !== 0) {
							this.date = new Date(this.viewDate);
							this.element.trigger({
								type: 'changeDate',
								date: this.date,
								viewMode: DPGlobal.modes[this.viewMode].clsName
							});
						}
						this.showMode(-1);
						this.fill();
						this.set();
						break;
					case 'td':
						if (target.is('.day')){
							var day = parseInt(target.text(), 10)||1;
							var month = this.viewDate.getMonth();
							if (target.is('.old')) {
								month -= 1;
							} else if (target.is('.new')) {
								month += 1;
							}
							var year = this.viewDate.getFullYear();
							this.date = new Date(year, month, day,0,0,0,0);
							this.viewDate = new Date(year, month, Math.min(28, day),0,0,0,0);
							this.fill();
							this.set();
							this.element.trigger({
								type: 'changeDate',
								date: this.date,
								viewMode: DPGlobal.modes[this.viewMode].clsName
							});
							// SI 59041 - we now want to close datepicker on a day being clicked
							this.hide();
						}
						break;
				}
			}
		},
		
		mousedown: function(e){
			e.stopPropagation();
			e.preventDefault();
		},
		
		showMode: function(dir) {
			if (dir) {
				this.viewMode = Math.max(this.minViewMode, Math.min(2, this.viewMode + dir));
			}
			this.picker.find('>div').hide().filter('.datepicker-'+DPGlobal.modes[this.viewMode].clsName).show();
		}
	};
	
	$.fn.datepicker = function ( option, val ) {
		return this.each(function () {
			var $this = $(this),
				data = $this.data('datepicker'),
				options = typeof option === 'object' && option;

			// Since we're using .datepicker class to represent both our plugin $this and also the dropdown
			// menu, we only want to create a Datepicker instance for the former and ignore the popup's element.
			if (($this).hasClass('dropdown-menu') !== true) {
				if (!data) {
					$this.data('datepicker', (data = new Datepicker(this, $.extend({}, $.fn.datepicker.defaults,options))));
				}
				if (typeof option === 'string') data[option](val);
			}
		});
	};

	$.fn.DateVerifier = function (dateString, format) {
		var formatObject = DPGlobal.parseFormat(format);
		return DPGlobal.parseDate(dateString, formatObject).inputDateStringWasValid;
	};

	$.fn.datepicker.defaults = {
	};
	$.fn.datepicker.Constructor = Datepicker;
	
	var DPGlobal = {
		modes: [
			{
				clsName: 'days',
				navFnc: 'Month',
				navStep: 1
			},
			{
				clsName: 'months',
				navFnc: 'FullYear',
				navStep: 1
			},
			{
				clsName: 'years',
				navFnc: 'FullYear',
				navStep: 10
		}],

		isLeapYear: function (year) {
			return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0));
		},
		// This adds months from 'd' date passed in. 'n' is the number of months to add.
        multiplyMonths: function(d, sign, term) {
            var originalDate = d.getDate(), newDate;
            term = term || 1;
			d.setMonth(d.getMonth() + (sign * term));

			// When adding months, we may overshoot (eg October 31 +1's to December 1):
			// var d = new Date("October 31, 2012"); d.setMonth(d.getMonth()+1); log(d);
			// Outputs: Sat Dec 01 2012 00:00:00 GMT-0800 (PST)
			// In that case we simply rewind a day to get back to Nov 30. 
			newDate = d.getDate();

			// It doesn't matter whether we overshoot day of month when adding OR subtracting...if original day of month
			// was too big, it always advances to first of next month. So we can share following logic (regardless of sign):
			if(newDate < originalDate) {
                d.setDate(1);
                d.setDate(d.getDate()-1);
			}
            return d;
        },
		getDaysInMonth: function (year, month) {
			return [31, (DPGlobal.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
		},
		getPossibleDateChars: function(format) {
			var i, len, chars;

			// This plugin supports the following for formats:
			// formats: dd, d, mm, m, yyyy, yy; & separators: '-', '/', '.'
			chars = '0123456789';
			done:
			for(i = 0, len = format.length; i < len; i++) {
				switch (format.charAt(i)) {
					case '/':
					case '.':
					case '-':
						chars += format.charAt(i);
						break done;
					default:
						break;
				}
			}
			return chars;
		},
		parseFormat: function(format){
			var separator = format.match(/[.\/\-\s].*?/),
				parts = format.split(/\W+/);
			if (!separator || !parts || parts.length === 0){
				throw new Error("Invalid date format.");
			}
			return {separator: separator, parts: parts};
		},
		/**
		 * parseDate tries to build a date from the 'dateIn' date string input using the
		 * 'format' input provided. It will ignore invalid ranges and always try to build
		 * the date object. However, if it does encounter an invalid range, that will be 
		 * reflected in inputDateStringWasValid property. Thus the object returned looks like:
		 * {'dateObject': date, 'inputDateStringWasValid': isValid};
		 */
		parseDate: function(dateIn, format) {
			var parts = dateIn.split(format.separator),
				date = new Date(), isLegalDate = true,
				val, i, cnt, originalDate, len, 
				day, month, year,
				monthsSet = false, dayValue = null, isValid = true;

			// First check separator is correct
			isValid = dateIn.lastIndexOf(format.separator) !== -1;

			date.setHours(0);
			date.setMinutes(0);
			date.setSeconds(0);
			date.setMilliseconds(0);

			function _setDate(theDay) {
				// Check if date in legal range, if so, attempt to set date directly
				// but take in to account possible "next month wrapping"
				if (_isInRange(theDay, 1, 31)) {
					date.setDate(theDay);
					if (date.getDate() < theDay) {
						date.setDate(0);
					}
				} else {
					isValid = false;
				}
			}
			
			function _isInRange(val, min, max) {
				var value = parseInt(val, 10);
				return (!isNaN(value) && value >= min && value <= max);
			}

			function century() {return new Date().getFullYear().toString().substr(0, 2);}

			function twoDigitString (n) {
				return n.toString().length === 1 ? '0' + n : n.toString();
			}

			if (parts.length === format.parts.length) {

				for (i=0, cnt = format.parts.length; i < cnt; i++) {
					
					// ensures date string has "surrounding values" e.g. not: 11--1999
					if (!parts[i]) {
						return false;
					}

					val = parseInt(parts[i], 10);
					switch(format.parts[i]) {
						case 'dd':
						case 'd':
							day = val;
							// We must set months first and if format is dd-mm we defer setting day until month
							if (!monthsSet) {
								dayValue = val;	
							} else {
								_setDate(val);
							}
							break;
						case 'mm':
						case 'm':
							month = val - 1;
							if (_isInRange(val, 1, 12)) {
								// If October 31 and we do setMonth(10) we get December since not 31 days in Nov!
								originalDate = date.getDate();
								date.setMonth(val - 1);
								if (date.getDate() < originalDate) {
									date.setDate(0);  
								}
								// If we had dd-mm format, we save 'day' in dayValue and set here (we must set months 
								// first since we may be in wrong month that doesn't have enough days to fullfill e.g. 
								// if the current date value for month was Nov, and we tried setting day to 31!)
								if (dayValue) {
									_setDate(dayValue);
								}
								monthsSet = true;
							} else {
								isValid = false;
							}
							break;
						case 'yy':
							year = 2000 + val;
							len = val.toString().length;
							// Auto correct when exactly four digits and current century
							if (len === 4 && century() === val.toString().substr(0, 2)) {
								date.setFullYear(century() + val.toString().substr(-2));
							}
							else if (_isInRange(val, 0, 99)) {
								date.setFullYear(century() + twoDigitString(val));
							} else {
								isValid = false;
							}
							break;
						case 'yyyy':
							year = val;
							len = val.toString().length;
							if (_isInRange(val, 0, 9999)) {

								// year length is 1 or 2 (already disallowed empty "surrounding" parts)
								if (len < 3) {
									date.setFullYear(century() + twoDigitString(val));
                                    year = date.getFullYear();
								}
								// e.g. no reasonable way to handle 2000 + 113 
								else if (len === 3) {
									isValid = false;
								} else {
									date.setFullYear(val);
								}
							} else {
								isValid = false;
							}
							break;
					}
				}
				if (isValid) {
					date = new Date(year, month, day, 0, 0, 0);
				}
			} else {
				isValid = false;
			}
			return {'dateObject': date, 'inputDateStringWasValid': isValid};
		},
		formatDate: function(date, format){
			var val = {
				d: date.getDate(),
				m: date.getMonth() + 1,
				yy: date.getFullYear().toString().substring(2),
				yyyy: date.getFullYear()
			};
			val.dd = (val.d < 10 ? '0' : '') + val.d;
			val.mm = (val.m < 10 ? '0' : '') + val.m;
			var date = [];
			for (var i=0, cnt = format.parts.length; i < cnt; i++) {
				date.push(val[format.parts[i]]);
			}
			return date.join(format.separator);
		},
		headTemplate: '<thead>'+
							'<tr>'+
								'<th class="prev">&lsaquo;</th>'+
								'<th colspan="5" class="switch"></th>'+
								'<th class="next">&rsaquo;</th>'+
							'</tr>'+
						'</thead>',
		contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>'
	};
	DPGlobal.template = '<div class="datepicker dropdown-menu">'+
							'<div class="datepicker-days">'+
								'<table class=" table-condensed">'+
									DPGlobal.headTemplate+
									'<tbody></tbody>'+
								'</table>'+
							'</div>'+
							'<div class="datepicker-months">'+
								'<table class="table-condensed">'+
									DPGlobal.headTemplate+
									DPGlobal.contTemplate+
								'</table>'+
							'</div>'+
							'<div class="datepicker-years">'+
								'<table class="table-condensed">'+
									DPGlobal.headTemplate+
									DPGlobal.contTemplate+
								'</table>'+
							'</div>'+
						'</div>';

}( window.jQuery );
