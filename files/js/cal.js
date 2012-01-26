/* jQuery UI Date Picker v3.3 - previously jQuery Calendar
   Written by Marc Grabanski (m@marcgrabanski.com) and Keith Wood (kbwood@virginbroadband.com.au).

   Copyright (c) 2007 Marc Grabanski (http://marcgrabanski.com/code/ui-datepicker)
   Dual licensed under the MIT (MIT-LICENSE.txt)
   and GPL (GPL-LICENSE.txt) licenses.
   Date: 09-03-2007  */

/* Date picker manager.
   Use the singleton instance of this class, $.datepicker, to interact with the date picker.
   Settings for (groups of) date pickers are maintained in an instance object
   (DatepickerInstance), allowing multiple different settings on the same page. */
   
(function($) { // hide the namespace

function Datepicker() {
	this.debug = false; // Change this to true to start debugging
	this._nextId = 0; // Next ID for a date picker instance
	this._inst = []; // List of instances indexed by ID
	this._curInst = null; // The current instance in use
	this._disabledInputs = []; // List of date picker inputs that have been disabled
	this._datepickerShowing = false; // True if the popup picker is showing , false if not
	this._inDialog = false; // True if showing within a "dialog", false if not
	this.regional = []; // Available regional settings, indexed by language code
	this.regional[''] = { // Default regional settings
		clearText: 'Очистить', // Display text for clear link
		clearStatus: 'Стереть текущую дату', // Status text for clear link
		closeText: 'Закрыть', // Display text for close link
		closeStatus: 'Закрыть без сохранения', // Status text for close link
		prevText: '&#x3c;Пред', // Display text for previous month link
		prevStatus: 'Предыдущий месяц', // Status text for previous month link
		nextText: 'След&#x3e;', // Display text for next month link
		nextStatus: 'Следующий месяц', // Status text for next month link
		currentText: 'Сегодня', // Display text for current month link
		currentStatus: 'Текущий месяц', // Status text for current month link
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
			'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'], // Names of months for drop-down and formatting
		monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'], // For formatting
		monthStatus: 'Показать другой месяц', // Status text for selecting a month
		yearStatus: 'Показать другой год', // Status text for selecting a year
		weekHeader: 'Нед', // Header for the week of the year column
		weekStatus: 'Неделя года', // Status text for the week of the year column
		dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'], // For formatting
		dayNamesShort: ['Вск', 'Пнд', 'Втр', 'Срд', 'Чтв', 'Птн', 'Сбт'], // For formatting
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'], // Column headings for days starting at Sunday
		dayStatus: 'Установить первым днем недели', // Status text for the day of the week selection
		dateStatus: 'Выбрать день, месяц, год', // Status text for the date selection
		dateFormat: 'dd.mm.yy', // See format options on parseDate
		firstDay: 1, // The first day of the week, Sun = 0, Mon = 1, ...
		initStatus: 'Выбрать дату', // Initial Status text on opening
		isRTL: false // True if right-to-left language, false if left-to-right
	};
	this._defaults = { // Global defaults for all the date picker instances
		showOn: 'focus', // 'focus' for popup on focus,
			// 'button' for trigger button, or 'both' for either
		showAnim: 'show', // Name of jQuery animation for popup
		defaultDate: null, // Used when field is blank: actual date,
			// +/-number for offset from today, null for today
		appendText: '', // Display text following the input box, e.g. showing the format
		buttonText: '...', // Text for trigger button
		buttonImage: '', // URL for trigger button image
		buttonImageOnly: false, // True if the image appears alone, false if it appears on a button
		closeAtTop: true, // True to have the clear/close at the top,
			// false to have them at the bottom
		mandatory: false, // True to hide the Clear link, false to include it
		hideIfNoPrevNext: false, // True to hide next/previous month links
			// if not applicable, false to just disable them
		changeMonth: true, // True if month can be selected directly, false if only prev/next
		changeYear: true, // True if year can be selected directly, false if only prev/next
		yearRange: '-10:+10', // Range of years to display in drop-down,
			// either relative to current year (-nn:+nn) or absolute (nnnn:nnnn)
		changeFirstDay: true, // True to click on day name to change, false to remain as set
		showOtherMonths: false, // True to show dates in other months, false to leave blank
		showWeeks: false, // True to show week of the year, false to omit
		calculateWeek: this.iso8601Week, // How to calculate the week of the year,
			// takes a Date and returns the number of the week for it
		shortYearCutoff: '+10', // Short year values < this are in the current century,
			// > this are in the previous century, 
			// string value starting with '+' for current year + value
		showStatus: false, // True to show status bar at bottom, false to not show it
		statusForDate: this.dateStatus, // Function to provide status text for a date -
			// takes date and instance as parameters, returns display text
		minDate: null, // The earliest selectable date, or null for no limit
		maxDate: null, // The latest selectable date, or null for no limit
		speed: 'medium', // Speed of display/closure
		beforeShowDay: null, // Function that takes a date and returns an array with
			// [0] = true if selectable, false if not,
			// [1] = custom CSS class name(s) or '', e.g. $.datepicker.noWeekends
		beforeShow: null, // Function that takes an input field and
			// returns a set of custom settings for the date picker
		onSelect: null, // Define a callback function when a date is selected
		numberOfMonths: 1, // Number of months to show at a time
		stepMonths: 1, // Number of months to step back/forward
		rangeSelect: false, // Allows for selecting a date range on one date picker
		rangeSeparator: ' - ' // Text between two dates in a range
	};
	$.extend(this._defaults, this.regional['']);
	this._datepickerDiv = $('<div id="datepicker_div"></div>');
}

$.extend(Datepicker.prototype, {
	/* Class name added to elements to indicate already configured with a date picker. */
	markerClassName: 'hasDatepicker',

	/* Debug logging (if enabled). */
	log: function () {
		if (this.debug) {
			console.log.apply('', arguments);
		}
	},
	
	/* Register a new date picker instance - with custom settings. */
	_register: function(inst) {
		var id = this._nextId++;
		this._inst[id] = inst;
		return id;
	},

	/* Retrieve a particular date picker instance based on its ID. */
	_getInst: function(id) {
		return this._inst[id] || id;
	},

	/* Override the default settings for all instances of the date picker. 
	   @param  settings  object - the new settings to use as defaults (anonymous object)
	   @return the manager object */
	setDefaults: function(settings) {
		extendRemove(this._defaults, settings || {});
		return this;
	},

	/* Handle keystrokes. */
	_doKeyDown: function(e) {
		var inst = $.datepicker._getInst(this._calId);
		if ($.datepicker._datepickerShowing) {
			switch (e.keyCode) {
				case 9:  $.datepicker.hideDatepicker('');
						break; // hide on tab out
				case 13: $.datepicker._selectDay(inst, inst._selectedMonth, inst._selectedYear,
							$('td.datepicker_daysCellOver', inst._datepickerDiv)[0]);
						return false; // don't submit the form
						break; // select the value on enter
				case 27: $.datepicker.hideDatepicker(inst._get('speed'));
						break; // hide on escape
				case 33: $.datepicker._adjustDate(inst,
							(e.ctrlKey ? -1 : -inst._get('stepMonths')), (e.ctrlKey ? 'Y' : 'M'));
						break; // previous month/year on page up/+ ctrl
				case 34: $.datepicker._adjustDate(inst,
							(e.ctrlKey ? +1 : +inst._get('stepMonths')), (e.ctrlKey ? 'Y' : 'M'));
						break; // next month/year on page down/+ ctrl
				case 35: if (e.ctrlKey) $.datepicker._clearDate(inst);
						break; // clear on ctrl+end
				case 36: if (e.ctrlKey) $.datepicker._gotoToday(inst);
						break; // current on ctrl+home
				case 37: if (e.ctrlKey) $.datepicker._adjustDate(inst, -1, 'D');
						break; // -1 day on ctrl+left
				case 38: if (e.ctrlKey) $.datepicker._adjustDate(inst, -7, 'D');
						break; // -1 week on ctrl+up
				case 39: if (e.ctrlKey) $.datepicker._adjustDate(inst, +1, 'D');
						break; // +1 day on ctrl+right
				case 40: if (e.ctrlKey) $.datepicker._adjustDate(inst, +7, 'D');
						break; // +1 week on ctrl+down
			}
		}
		else if (e.keyCode == 36 && e.ctrlKey) { // display the date picker on ctrl+home
			$.datepicker.showFor(this);
		}
	},

	/* Filter entered characters - based on date format. */
	_doKeyPress: function(e) {
		var inst = $.datepicker._getInst(this._calId);
		var chars = $.datepicker._possibleChars(inst._get('dateFormat'));
		var chr = String.fromCharCode(e.charCode == undefined ? e.keyCode : e.charCode);
		return (chr < ' ' || !chars || chars.indexOf(chr) > -1);
	},

	/* Attach the date picker to an input field. */
	_connectDatepicker: function(target, inst) {
		var input = $(target);
		if (this._hasClass(input, this.markerClassName)) {
			return;
		}
		var appendText = inst._get('appendText');
		var isRTL = inst._get('isRTL');
		if (appendText) {
			if (isRTL) {
				input.before('<span class="datepicker_append">' + appendText + '</span>');
			}
			else {
				input.after('<span class="datepicker_append">' + appendText + '</span>');
			}
		}
		var showOn = inst._get('showOn');
		if (showOn == 'focus' || showOn == 'both') { // pop-up date picker when in the marked field
			input.focus(this.showFor);
		}
		if (showOn == 'button' || showOn == 'both') { // pop-up date picker when button clicked
			var buttonText = inst._get('buttonText');
			var buttonImage = inst._get('buttonImage');
			var buttonImageOnly = inst._get('buttonImageOnly');
			var trigger = $(buttonImageOnly ? '<img class="datepicker_trigger" src="' +
				buttonImage + '" alt="' + buttonText + '" title="' + buttonText + '"/>' :
				'<button type="button" class="datepicker_trigger">' + (buttonImage != '' ?
				'<img src="' + buttonImage + '" alt="' + buttonText + '" title="' + buttonText + '"/>' :
				buttonText) + '</button>');
			input.wrap('<span class="datepicker_wrap"></span>');
			if (isRTL) {
				input.before(trigger);
			}
			else {
				input.after(trigger);
			}
			trigger.click(this.showFor);
		}
		input.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress);
		input[0]._calId = inst._id;
	},

	/* Attach an inline date picker to a div. */
	_inlineDatepicker: function(target, inst) {
		var input = $(target);
		if (this._hasClass(input, this.markerClassName)) {
			return;
		}
		input.addClass(this.markerClassName).append(inst._datepickerDiv);
		input[0]._calId = inst._id;
		this._updateDatepicker(inst);
		/* @todo: fix _inlineShow automatic resizing
			- Endless loop bug in IE6.  
			- inst._datepickerDiv.resize doesn't ever fire in firefox.  */
		// inst._datepickerDiv.resize(function() { $.datepicker._inlineShow(inst); });
	},

	/* Tidy up after displaying the date picker. */
	_inlineShow: function(inst) {
		var numMonths = inst._getNumberOfMonths(); // fix width for dynamic number of date pickers
		inst._datepickerDiv.width(numMonths[1] * $('.datepicker', inst._datepickerDiv[0]).width());
	}, 

	/* Does this element have a particular class? */
	_hasClass: function(element, className) {
		var classes = element.attr('class');
		return (classes && classes.indexOf(className) > -1);
	},

	/* Pop-up the date picker in a "dialog" box.
	   @param  dateText  string - the initial date to display (in the current format)
	   @param  onSelect  function - the function(dateText) to call when a date is selected
	   @param  settings  object - update the dialog date picker instance's settings (anonymous object)
	   @param  pos       int[2] - coordinates for the dialog's position within the screen or
	                     event - with x/y coordinates or
	                     leave empty for default (screen centre)
	   @return the manager object */
	dialogDatepicker: function(dateText, onSelect, settings, pos) {
		var inst = this._dialogInst; // internal instance
		if (!inst) {
			inst = this._dialogInst = new DatepickerInstance({}, false);
			this._dialogInput = $('<input type="text" size="1" style="position: absolute; top: -100px;"/>');
			this._dialogInput.keydown(this._doKeyDown);
			$('body').append(this._dialogInput);
			this._dialogInput[0]._calId = inst._id;
		}
		extendRemove(inst._settings, settings || {});
		this._dialogInput.val(dateText);

		this._pos = (pos ? (pos.length ? pos : [pos.pageX, pos.pageY]) : null);
		if (!this._pos) {
			var browserWidth = window.innerWidth || document.documentElement.clientWidth ||
				document.body.clientWidth;
			var browserHeight = window.innerHeight || document.documentElement.clientHeight ||
				document.body.clientHeight;
			var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
			var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
			this._pos = // should use actual width/height below
				[(browserWidth / 2) - 100 + scrollX, (browserHeight / 2) - 150 + scrollY];
		}

		// move input on screen for focus, but hidden behind dialog
		this._dialogInput.css('left', this._pos[0] + 'px').css('top', this._pos[1] + 'px');
		inst._settings.onSelect = onSelect;
		this._inDialog = true;
		this._datepickerDiv.addClass('datepicker_dialog');
		this.showFor(this._dialogInput[0]);
		if ($.blockUI) {
			$.blockUI(this._datepickerDiv);
		}
		return this;
	},

	/* Pop-up the date picker for a given input field.
	   @param  control  element - the input field attached to the date picker or
	                    string - the ID or other jQuery selector of the input field or
	                    object - jQuery object for input field
	   @return the manager object */
	showFor: function(control) {
		control = (control.jquery ? control[0] :
			(typeof control == 'string' ? $(control)[0] : control));
		var input = (control.nodeName && control.nodeName.toLowerCase() == 'input' ? control : this);
		if (input.nodeName.toLowerCase() != 'input') { // find from button/image trigger
			input = $('input', input.parentNode)[0];
		}
		if ($.datepicker._lastInput == input) { // already here
			return;
		}
		if ($(input).isDisabledDatepicker()) {
			return;
		}
		var inst = $.datepicker._getInst(input._calId);
		var beforeShow = inst._get('beforeShow');
		extendRemove(inst._settings, (beforeShow ? beforeShow.apply(input, [input, inst]) : {}));
		$.datepicker.hideDatepicker('');
		$.datepicker._lastInput = input;
		inst._setDateFromField(input);
		if ($.datepicker._inDialog) { // hide cursor
			input.value = '';
		}
		if (!$.datepicker._pos) { // position below input
			$.datepicker._pos = $.datepicker._findPos(input);
			$.datepicker._pos[1] += input.offsetHeight; // add the height
		}
		var isFixed = false;
		$(input).parents().each(function() {
			isFixed |= $(this).css('position') == 'fixed';
		});
		if (isFixed && $.browser.opera) { // correction for Opera when fixed and scrolled
			$.datepicker._pos[0] -= document.documentElement.scrollLeft;
			$.datepicker._pos[1] -= document.documentElement.scrollTop;
		}
		inst._datepickerDiv.css('position', ($.datepicker._inDialog && $.blockUI ?
			'static' : (isFixed ? 'fixed' : 'absolute'))).
			css('left', $.datepicker._pos[0] + 'px').css('top', $.datepicker._pos[1] + 'px');
		$.datepicker._pos = null;
		$.datepicker._showDatepicker(inst);
		return this;
	},

	/* Construct and display the date picker. */
	_showDatepicker: function(id) {
		var inst = this._getInst(id);
		inst._rangeStart = null;
		this._updateDatepicker(inst);
		if (!inst._inline) {
			var speed = inst._get('speed');
			var postProcess = function() {
				$.datepicker._datepickerShowing = true;
				$.datepicker._afterShow(inst);
			};
			var showAnim = inst._get('showAnim') || 'show';
			inst._datepickerDiv[showAnim](speed, postProcess);
			if (speed == '') {
				postProcess();
			}
			if (inst._input[0].type != 'hidden') {
				inst._input[0].focus();
			}
			this._curInst = inst;
		}
	},

	/* Generate the date picker content. */
	_updateDatepicker: function(inst) {
		inst._datepickerDiv.empty().append(inst._generateDatepicker());
		var numMonths = inst._getNumberOfMonths();
		if (numMonths[0] != 1 || numMonths[1] != 1) {
			inst._datepickerDiv.addClass('datepicker_multi');
		} 
		else {
			inst._datepickerDiv.removeClass('datepicker_multi');
		}
		if (inst._get('isRTL')) {
			inst._datepickerDiv.addClass('datepicker_rtl');
		}
		else {
			inst._datepickerDiv.removeClass('datepicker_rtl');
		}
		if (inst._input && inst._input[0].type != 'hidden') {
			inst._input[0].focus();
		}
	},

	/* Tidy up after displaying the date picker. */
	_afterShow: function(inst) {
		var numMonths = inst._getNumberOfMonths(); // fix width for dynamic number of date pickers
		inst._datepickerDiv.width(numMonths[1] * $('.datepicker', inst._datepickerDiv[0]).width());
		if ($.browser.msie && parseInt($.browser.version) < 7) { // fix IE < 7 select problems
			$('#datepicker_cover').css({width: inst._datepickerDiv.width() + 4,
				height: inst._datepickerDiv.height() + 4});
		}
		// re-position on screen if necessary
		var isFixed = inst._datepickerDiv.css('position') == 'fixed';
		var pos = inst._input ? $.datepicker._findPos(inst._input[0]) : null;
		var browserWidth = window.innerWidth || document.documentElement.clientWidth ||
			document.body.clientWidth;
		var browserHeight = window.innerHeight || document.documentElement.clientHeight ||
			document.body.clientHeight;
		var scrollX = (isFixed ? 0 : document.documentElement.scrollLeft || document.body.scrollLeft);
		var scrollY = (isFixed ? 0 : document.documentElement.scrollTop || document.body.scrollTop);
		// reposition date picker horizontally if outside the browser window
		if ((inst._datepickerDiv.offset().left + inst._datepickerDiv.width() -
				(isFixed && $.browser.msie ? document.documentElement.scrollLeft : 0)) >
				(browserWidth + scrollX)) {
			inst._datepickerDiv.css('left', Math.max(scrollX,
				pos[0] + (inst._input ? $(inst._input[0]).width() : null) - inst._datepickerDiv.width() -
				(isFixed && $.browser.opera ? document.documentElement.scrollLeft : 0)) + 'px');
		}
		// reposition date picker vertically if outside the browser window
		if ((inst._datepickerDiv.offset().top + inst._datepickerDiv.height() -
				(isFixed && $.browser.msie ? document.documentElement.scrollTop : 0)) >
				(browserHeight + scrollY) ) {
			inst._datepickerDiv.css('top', Math.max(scrollY,
				pos[1] - (this._inDialog ? 0 : inst._datepickerDiv.height()) -
				(isFixed && $.browser.opera ? document.documentElement.scrollTop : 0)) + 'px');
		}
	},
	
	/* Find an object's position on the screen. */
	_findPos: function(obj) {
		while (obj && (obj.type == 'hidden' || obj.nodeType != 1)) {
			obj = obj.nextSibling;
		}
		var curleft = curtop = 0;
		if (obj && obj.offsetParent) {
			curleft = obj.offsetLeft;
			curtop = obj.offsetTop;
			while (obj = obj.offsetParent) {
				var origcurleft = curleft;
				curleft += obj.offsetLeft;
				if (curleft < 0) {
					curleft = origcurleft;
				}
				curtop += obj.offsetTop;
			}
		}
		return [curleft,curtop];
	},

	/* Hide the date picker from view.
	   @param  speed  string - the speed at which to close the date picker
	   @return void */
	hideDatepicker: function(speed) {
		var inst = this._curInst;
		if (!inst) {
			return;
		}
		var rangeSelect = inst._get('rangeSelect');
		if (rangeSelect && this._stayOpen) {
			this._selectDate(inst, inst._formatDate(
				inst._currentDay, inst._currentMonth, inst._currentYear));
		}
		this._stayOpen = false;
		if (this._datepickerShowing) {
			speed = (speed != null ? speed : inst._get('speed'));
			inst._datepickerDiv.hide(speed, function() {
				$.datepicker._tidyDialog(inst);
			});
			if (speed == '') {
				this._tidyDialog(inst);
			}
			this._datepickerShowing = false;
			this._lastInput = null;
			inst._settings.prompt = null;
			if (this._inDialog) {
				this._dialogInput.css('position', 'absolute').
					css('left', '0px').css('top', '-100px');
				if ($.blockUI) {
					$.unblockUI();
					$('body').append(this._datepickerDiv);
				}
			}
			this._inDialog = false;
		}
		this._curInst = null;
	},

	/* Tidy up after a dialog display. */
	_tidyDialog: function(inst) {
		inst._datepickerDiv.removeClass('datepicker_dialog');
		$('.datepicker_prompt', inst._datepickerDiv).remove();
	},

	/* Close date picker if clicked elsewhere. */
	_checkExternalClick: function(event) {
		if (!$.datepicker._curInst) {
			return;
		}
		var target = $(event.target);
		if ((target.parents("#datepicker_div").length == 0) &&
				(target.attr('class') != 'datepicker_trigger') &&
				$.datepicker._datepickerShowing && !($.datepicker._inDialog && $.blockUI)) {
			$.datepicker.hideDatepicker('');
		}
	},

	/* Adjust one of the date sub-fields. */
	_adjustDate: function(id, offset, period) {
		var inst = this._getInst(id);
		inst._adjustDate(offset, period);
		this._updateDatepicker(inst);
	},

	/* Action for current link. */
	_gotoToday: function(id) {
		var date = new Date();
		var inst = this._getInst(id);
		inst._selectedDay = date.getDate();
		inst._selectedMonth = date.getMonth();
		inst._selectedYear = date.getFullYear();
		this._adjustDate(inst);
	},

	/* Action for selecting a new month/year. */
	_selectMonthYear: function(id, select, period) {
		var inst = this._getInst(id);
		inst._selectingMonthYear = false;
		inst[period == 'M' ? '_selectedMonth' : '_selectedYear'] =
			select.options[select.selectedIndex].value - 0;
		this._adjustDate(inst);
	},

	/* Restore input focus after not changing month/year. */
	_clickMonthYear: function(id) {
		var inst = this._getInst(id);
		if (inst._input && inst._selectingMonthYear && !$.browser.msie) {
			inst._input[0].focus();
		}
		inst._selectingMonthYear = !inst._selectingMonthYear;
	},

	/* Action for changing the first week day. */
	_changeFirstDay: function(id, day) {
		var inst = this._getInst(id);
		inst._settings.firstDay = day;
		this._updateDatepicker(inst);
	},

	/* Action for selecting a day. */
	_selectDay: function(id, month, year, td) {
		if (this._hasClass($(td), 'datepicker_unselectable')) {
			return;
		}
		var inst = this._getInst(id);
		var rangeSelect = inst._get('rangeSelect');
		if (rangeSelect) {
			if (!this._stayOpen) {
				$('.datepicker td').removeClass('datepicker_currentDay');
				$(td).addClass('datepicker_currentDay');
			} 
			this._stayOpen = !this._stayOpen;
		}
		inst._currentDay = $('a', td).html();
		inst._currentMonth = month;
		inst._currentYear = year;
		this._selectDate(id, inst._formatDate(
			inst._currentDay, inst._currentMonth, inst._currentYear));
		if (this._stayOpen) {
			inst._endDay = inst._endMonth = inst._endYear = null;
			inst._rangeStart = new Date(inst._currentYear, inst._currentMonth, inst._currentDay);
			this._updateDatepicker(inst);
		}
		else if (rangeSelect) {
			inst._endDay = inst._currentDay;
			inst._endMonth = inst._currentMonth;
			inst._endYear = inst._currentYear;
			inst._selectedDay = inst._currentDay = inst._rangeStart.getDate();
			inst._selectedMonth = inst._currentMonth = inst._rangeStart.getMonth();
			inst._selectedYear = inst._currentYear = inst._rangeStart.getFullYear();
			inst._rangeStart = null;
			if (inst._inline) {
				this._updateDatepicker(inst);
			}
		}
	},

	/* Erase the input field and hide the date picker. */
	_clearDate: function(id) {
		var inst = this._getInst(id);
		this._stayOpen = false;
		inst._endDay = inst._endMonth = inst._endYear = inst._rangeStart = null;
		this._selectDate(inst, '');
	},

	/* Update the input field with the selected date. */
	_selectDate: function(id, dateStr) {
		var inst = this._getInst(id);
		dateStr = (dateStr != null ? dateStr : inst._formatDate());
		if (inst._rangeStart) {
			dateStr = inst._formatDate(inst._rangeStart) + inst._get('rangeSeparator') + dateStr;
		}
		if (inst._input) {
			inst._input.val(dateStr);
		}
		var onSelect = inst._get('onSelect');
		if (onSelect) {
			onSelect.apply((inst._input ? inst._input[0] : null), [dateStr, inst]);  // trigger custom callback
		}
		else {
			if (inst._input) {
				inst._input.trigger('change'); // fire the change event
			}
		}
		if (inst._inline) {
			this._updateDatepicker(inst);
		}
		else {
			if (!this._stayOpen) {
				this.hideDatepicker(inst._get('speed'));
				this._lastInput = inst._input[0];
				if (typeof(inst._input[0]) != 'object') {
					inst._input[0].focus(); // restore focus
				}
				this._lastInput = null;
			}
		}
	},

	/* Set as beforeShowDay function to prevent selection of weekends.
	   @param  date  Date - the date to customise
	   @return [boolean, string] - is this date selectable?, what is its CSS class? */
	noWeekends: function(date) {
		var day = date.getDay();
		return [(day > 0 && day < 6), ''];
	},
	
	/* Set as calculateWeek to determine the week of the year based on the ISO 8601 definition.
	   @param  date  Date - the date to get the week for
	   @return  number - the number of the week within the year that contains this date */
	iso8601Week: function(date) {
		var checkDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
		var firstMon = new Date(checkDate.getFullYear(), 1 - 1, 4); // First week always contains 4 Jan
		var firstDay = firstMon.getDay() || 7; // Day of week: Mon = 1, ..., Sun = 7
		firstMon.setDate(firstMon.getDate() + 1 - firstDay); // Preceding Monday
		if (firstDay < 4 && checkDate < firstMon) { // Adjust first three days in year if necessary
			checkDate.setDate(checkDate.getDate() - 3); // Generate for previous year
			return $.datepicker.iso8601Week(checkDate);
		}
		else if (checkDate > new Date(checkDate.getFullYear(), 12 - 1, 28)) { // Check last three days in year
			firstDay = new Date(checkDate.getFullYear() + 1, 1 - 1, 4).getDay() || 7;
			if (firstDay > 4 && (checkDate.getDay() || 7) < firstDay - 3) { // Adjust if necessary
				checkDate.setDate(checkDate.getDate() + 3); // Generate for next year
				return $.datepicker.iso8601Week(checkDate);
			}
		}
		return Math.floor(((checkDate - firstMon) / 86400000) / 7) + 1; // Weeks to given date
	},
	
	/* Provide status text for a particular date.
	   @param  date  the date to get the status for
	   @param  inst  the current datepicker instance
	   @return  the status display text for this date */
	dateStatus: function(date, inst) {
		return $.datepicker.formatDate(inst._get('dateStatus'), date, inst._get('dayNamesShort'),
			inst._get('dayNames'), inst._get('monthNamesShort'), inst._get('monthNames'));
	},

	/* Parse a string value into a date object.
	   The format can be combinations of the following:
	   d  - day of month (no leading zero)
	   dd - day of month (two digit)
	   D  - day name short
	   DD - day name long
	   m  - month of year (no leading zero)
	   mm - month of year (two digit)
	   M  - month name short
	   MM - month name long
	   y  - year (two digit)
	   yy - year (four digit)
	   '...' - literal text
	   '' - single quote

	   @param  format           String - the expected format of the date
	   @param  value            String - the date in the above format
	   @param  shortYearCutoff  Number - the cutoff year for determining the century (optional)
	   @param  dayNamesShort    String[7] - abbreviated names of the days from Sunday (optional)
	   @param  dayNames         String[7] - names of the days from Sunday (optional)
	   @param  monthNamesShort  String[12] - abbreviated names of the months (optional)
	   @param  monthNames       String[12] - names of the months (optional)
	   @return  Date - the extracted date value or null if value is blank */
	parseDate: function (format, value, shortYearCutoff, dayNamesShort, dayNames, monthNamesShort, monthNames) {
		if (format == null || value == null) {
			throw 'Invalid arguments';
		}
//		format = dateFormats[format] || format;
		value = (typeof value == 'object' ? value.toString() : value + '');
		if (value == '') {
			return null;
		}
		dayNamesShort = dayNamesShort || this._defaults.dayNamesShort;
		dayNames = dayNames || this._defaults.dayNames;
		monthNamesShort = monthNamesShort || this._defaults.monthNamesShort;
		monthNames = monthNames || this._defaults.monthNames;
		var year = -1;
		var month = -1;
		var day = -1;
		var literal = false;
		// Check whether a format character is doubled
		var lookAhead = function(match) {
			var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) == match);
			if (matches) {
				iFormat++;
			}
			return matches;	
		};
		// Extract a number from the string value
		var getNumber = function(match) {
			lookAhead(match);
			var size = (match == 'y' ? 4 : 2);
			var num = 0;
			while (size > 0 && iValue < value.length &&
					value.charAt(iValue) >= '0' && value.charAt(iValue) <= '9') {
				num = num * 10 + (value.charAt(iValue++) - 0);
				size--;
			}
			if (size == (match == 'y' ? 4 : 2)) {
				throw 'Missing number at position ' + iValue;
			}
			return num;
		};
		// Extract a name from the string value and convert to an index
		var getName = function(match, shortNames, longNames) {
			var names = (lookAhead(match) ? longNames : shortNames);
			var size = 0;
			for (var j = 0; j < names.length; j++) {
				size = Math.max(size, names[j].length);
			}
			var name = '';
			var iInit = iValue;
			while (size > 0 && iValue < value.length) {
				name += value.charAt(iValue++);
				for (var i = 0; i < names.length; i++) {
					if (name == names[i]) {
						return i + 1;
					}
				}
				size--;
			}
			throw 'Unknown name at position ' + iInit;
		};
		// Confirm that a literal character matches the string value
		var checkLiteral = function() {
			if (value.charAt(iValue) != format.charAt(iFormat)) {
				throw 'Unexpected literal at position ' + iValue;
			}
			iValue++;
		};
		var iValue = 0;
		for (var iFormat = 0; iFormat < format.length; iFormat++) {
			if (literal) {
				if (format.charAt(iFormat) == '\'' && !lookAhead('\'')) {
					literal = false;
				}
				else {
					checkLiteral();
				}
			}
			else {
				switch (format.charAt(iFormat)) {
					case 'd':
						day = getNumber('d');
						break;
					case 'D': 
						getName('D', dayNamesShort, dayNames);
						break;
					case 'm': 
						month = getNumber('m');
						break;
					case 'M':
						month = getName('M', monthNamesShort, monthNames); 
						break;
					case 'y':
						year = getNumber('y');
						break;
					case '\'':
						if (lookAhead('\'')) {
							checkLiteral();
						}
						else {
							literal = true;
						}
						break;
					default:
						checkLiteral();
				}
			}
		}
		if (year < 100) {
			year += new Date().getFullYear() - new Date().getFullYear() % 100 +
				(year <= shortYearCutoff ? 0 : -100);
		}
		var date = new Date(year, month - 1, day);
		if (date.getFullYear() != year || date.getMonth() + 1 != month || date.getDate() != day) {
			throw 'Invalid date'; // E.g. 31/02/*
		}
		return date;
	},

	/* Format a date object into a string value.
	   The format can be combinations of the following:
	   d  - day of month (no leading zero)
	   dd - day of month (two digit)
	   D  - day name short
	   DD - day name long
	   m  - month of year (no leading zero)
	   mm - month of year (two digit)
	   M  - month name short
	   MM - month name long
	   y  - year (two digit)
	   yy - year (four digit)
	   '...' - literal text
	   '' - single quote

	   @param  format           String - the desired format of the date
	   @param  date             Date - the date value to format
	   @param  dayNamesShort    String[7] - abbreviated names of the days from Sunday (optional)
	   @param  dayNames         String[7] - names of the days from Sunday (optional)
	   @param  monthNamesShort  String[12] - abbreviated names of the months (optional)
	   @param  monthNames       String[12] - names of the months (optional)
	   @return  String - the date in the above format */
	formatDate: function (format, date, dayNamesShort, dayNames, monthNamesShort, monthNames) {
		if (!date) {
			return '';
		}
//		format = dateFormats[format] || format;
		dayNamesShort = dayNamesShort || this._defaults.dayNamesShort;
		dayNames = dayNames || this._defaults.dayNames;
		monthNamesShort = monthNamesShort || this._defaults.monthNamesShort;
		monthNames = monthNames || this._defaults.monthNames;
		// Check whether a format character is doubled
		var lookAhead = function(match) {
			var matches = (iFormat + 1 < format.length && format.charAt(iFormat + 1) == match);
			if (matches) {
				iFormat++;
			}
			return matches;	
		};
		// Format a number, with leading zero if necessary
		var formatNumber = function(match, value) {
			return (lookAhead(match) && value < 10 ? '0' : '') + value;
		};
		// Format a name, short or long as requested
		var formatName = function(match, value, shortNames, longNames) {
			return (lookAhead(match) ? longNames[value] : shortNames[value]);
		};
		var output = '';
		var literal = false;
		if (date) {
			for (var iFormat = 0; iFormat < format.length; iFormat++) {
				if (literal) {
					if (format.charAt(iFormat) == '\'' && !lookAhead('\'')) {
						literal = false;
					}
					else {
						output += format.charAt(iFormat);
					}
				}
				else {
					switch (format.charAt(iFormat)) {
						case 'd':
							output += formatNumber('d', date.getDate()); 
							break;
						case 'D': 
							output += formatName('D', date.getDay(), dayNamesShort, dayNames);
							break;
						case 'm': 
							output += formatNumber('m', date.getMonth() + 1); 
							break;
						case 'M':
							output += formatName('M', date.getMonth(), monthNamesShort, monthNames); 
							break;
						case 'y':
							output += (lookAhead('y') ? date.getFullYear() : 
								(date.getYear() % 100 < 10 ? '0' : '') + date.getYear() % 100);
							break;
						case '\'':
							if (lookAhead('\'')) {
								output += '\'';
							}
							else {
								literal = true;
							}
							break;
						default:
							output += format.charAt(iFormat);
					}
				}
			}
		}
		return output;
	},

	/* Extract all possible characters from the date format. */
	_possibleChars: function (format) {
//		format = dateFormats[format] || format;
		var chars = '';
		var literal = false;
		for (var iFormat = 0; iFormat < format.length; iFormat++) {
			if (literal) {
				if (format.charAt(iFormat) == '\'' && !lookAhead('\'')) {
					literal = false;
				}
				else {
					chars += format.charAt(iFormat);
				}
			}
			else {
				switch (format.charAt(iFormat)) {
					case 'd':
					case 'm': 
					case 'y':
						chars += '0123456789'; 
						break;
					case 'D': 
					case 'M':
						return null; // Accept anything
					case '\'':
						if (lookAhead('\'')) {
							chars += '\'';
						}
						else {
							literal = true;
						}
						break;
					default:
						chars += format.charAt(iFormat);
				}
			}
		}
		return chars;
	}
});

/* Individualised settings for date picker functionality applied to one or more related inputs.
   Instances are managed and manipulated through the Datepicker manager. */
function DatepickerInstance(settings, inline) {
	this._id = $.datepicker._register(this);
	this._selectedDay = 0;
	this._selectedMonth = 0; // 0-11
	this._selectedYear = 0; // 4-digit year
	this._input = null; // The attached input field
	this._inline = inline; // True if showing inline, false if used in a popup
	this._datepickerDiv = (!inline ? $.datepicker._datepickerDiv :
		$('<div id="datepicker_div_' + this._id + '" class="datepicker_inline"></div>'));
	// customise the date picker object - uses manager defaults if not overridden
	this._settings = extendRemove({}, settings || {}); // clone
	if (inline) {
		this._setDate(this._getDefaultDate());
	}
}

$.extend(DatepickerInstance.prototype, {
	/* Get a setting value, defaulting if necessary. */
	_get: function(name) {
		return (this._settings[name] != null ? this._settings[name] : $.datepicker._defaults[name]);
	},

	/* Parse existing date and initialise date picker. */
	_setDateFromField: function(input) {
		this._input = $(input);
		var dateFormat = this._get('dateFormat');
		var dates = this._input ? this._input.val().split(this._get('rangeSeparator')) : null; 
		this._endDay = this._endMonth = this._endYear = null;
		var shortYearCutoff = this._get('shortYearCutoff');
		shortYearCutoff = (typeof shortYearCutoff != 'string' ? shortYearCutoff :
			new Date().getFullYear() % 100 + parseInt(shortYearCutoff, 10));
		var date = defaultDate = this._getDefaultDate();
		if (dates.length > 0) {
			var dayNamesShort = this._get('dayNamesShort');
			var dayNames = this._get('dayNames');
			var monthNamesShort = this._get('monthNamesShort');
			var monthNames = this._get('monthNames');
			if (dates.length > 1) {
				date = $.datepicker.parseDate(dateFormat, dates[1], shortYearCutoff,
					dayNamesShort, dayNames, monthNamesShort, monthNames) || defaultDate;
				this._endDay = date.getDate();
				this._endMonth = date.getMonth();
				this._endYear = date.getFullYear();
			}
			try {
				date = $.datepicker.parseDate(dateFormat, dates[0], shortYearCutoff,
					dayNamesShort, dayNames, monthNamesShort, monthNames) ||defaultDate;
			}
			catch (e) {
				$.datepicker.log(e);
				date = defaultDate;
			}
		}
		this._selectedDay = this._currentDay = date.getDate();
		this._selectedMonth = this._currentMonth = date.getMonth();
		this._selectedYear = this._currentYear = date.getFullYear();
		this._adjustDate();
	},
	
	/* Retrieve the default date shown on opening. */
	_getDefaultDate: function() {
		return this._determineDate('defaultDate', new Date());
	},

	/* A date may be specified as an exact value or a relative one. */
	_determineDate: function(name, defaultDate) {
		var offsetNumeric = function(offset) {
			var date = new Date();
			date.setDate(date.getDate() + offset);
			return date;
		};
		var offsetString = function(offset, getDaysInMonth) {
			var date = new Date();
			var matches = /^([+-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?$/.exec(offset);
			if (matches) {
				var year = date.getFullYear();
				var month = date.getMonth();
				var day = date.getDate();
				switch (matches[2] || 'd') {
					case 'd' : case 'D' :
						day += (matches[1] - 0); break;
					case 'w' : case 'W' :
						day += (matches[1] * 7); break;
					case 'm' : case 'M' :
						month += (matches[1] - 0); 
						day = Math.min(day, getDaysInMonth(year, month));
						break;
					case 'y': case 'Y' :
						year += (matches[1] - 0);
						day = Math.min(day, getDaysInMonth(year, month));
						break;
				}
				date = new Date(year, month, day);
			}
			return date;
		};
		var date = this._get(name);
		return (date == null ? defaultDate :
			(typeof date == 'string' ? offsetString(date, this._getDaysInMonth) :
			(typeof date == 'number' ? offsetNumeric(date) : date)));
	},

	/* Set the date(s) directly. */
	_setDate: function(date, endDate) {
		this._selectedDay = this._currentDay = date.getDate();
		this._selectedMonth = this._currentMonth = date.getMonth();
		this._selectedYear = this._currentYear = date.getFullYear();
		if (this._get('rangeSelect')) {
			if (endDate) {
				this._endDay = endDate.getDate();
				this._endMonth = endDate.getMonth();
				this._endYear = endDate.getFullYear();
			}
			else {
				this._endDay = this._currentDay;
				this._endMonth = this._currentMonth;
				this._endYear = this._currentYear;
			}
		}
		this._adjustDate();
	},

	/* Retrieve the date(s) directly. */
	_getDate: function() {
		var startDate = (!this._currentYear || (this._input && this._input.val() == '') ? null :
			new Date(this._currentYear, this._currentMonth, this._currentDay));
		if (this._get('rangeSelect')) {
			return [startDate, (!this._endYear ? null :
				new Date(this._endYear, this._endMonth, this._endDay))];
		}
		else {
			return startDate;
		}
	},

	/* Generate the HTML for the current state of the date picker. */
	_generateDatepicker: function() {
		var today = new Date();
		today = new Date(today.getFullYear(), today.getMonth(), today.getDate()); // clear time
		var showStatus = this._get('showStatus');
		var isRTL = this._get('isRTL');
		// build the date picker HTML
		var clear = (this._get('mandatory') ? '' :
			'<div class="datepicker_clear"><a onclick="jQuery.datepicker._clearDate(' + this._id + ');"' + 
			(showStatus ? this._addStatus(this._get('clearStatus') || '&#xa0;') : '') + '>' +
			this._get('clearText') + '</a></div>');
		var controls = '<div class="datepicker_control">' + (isRTL ? '' : clear) +
			'<div class="datepicker_close"><a onclick="jQuery.datepicker.hideDatepicker();"' +
			(showStatus ? this._addStatus(this._get('closeStatus') || '&#xa0;') : '') + '>' +
			this._get('closeText') + '</a></div>' + (isRTL ? clear : '')  + '</div>';
		var prompt = this._get('prompt');
		var closeAtTop = this._get('closeAtTop');
		var hideIfNoPrevNext = this._get('hideIfNoPrevNext');
		var numMonths = this._getNumberOfMonths();
		var stepMonths = this._get('stepMonths');
		var isMultiMonth = (numMonths[0] != 1 || numMonths[1] != 1);
		var minDate = this._getMinMaxDate('min', true);
		var maxDate = this._getMinMaxDate('max');
		var drawMonth = this._selectedMonth;
		var drawYear = this._selectedYear;
		if (maxDate) {
			var maxDraw = new Date(maxDate.getFullYear(),
				maxDate.getMonth() - numMonths[1] + 1, maxDate.getDate());
			maxDraw = (minDate && maxDraw < minDate ? minDate : maxDraw);
			while (new Date(drawYear, drawMonth, 1) > maxDraw) {
				drawMonth--;
				if (drawMonth < 0) {
					drawMonth = 11;
					drawYear--;
				}
			}
		}
		// controls and links
		var prev = '<div class="datepicker_prev">' + (this._canAdjustMonth(-1, drawYear, drawMonth) ? 
			'<a onclick="jQuery.datepicker._adjustDate(' + this._id + ', -' + stepMonths + ', \'M\');"' +
			(showStatus ? this._addStatus(this._get('prevStatus') || '&#xa0;') : '') + '>' +
			this._get('prevText') + '</a>' :
			(hideIfNoPrevNext ? '' : '<label>' + this._get('prevText') + '</label>')) + '</div>';
		var next = '<div class="datepicker_next">' + (this._canAdjustMonth(+1, drawYear, drawMonth) ?
			'<a onclick="jQuery.datepicker._adjustDate(' + this._id + ', +' + stepMonths + ', \'M\');"' +
			(showStatus ? this._addStatus(this._get('nextStatus') || '&#xa0;') : '') + '>' +
			this._get('nextText') + '</a>' :
			(hideIfNoPrevNext ? '>' : '<label>' + this._get('nextText') + '</label>')) + '</div>';
		var html = (prompt ? '<div class="datepicker_prompt">' + prompt + '</div>' : '') +
			(closeAtTop && !this._inline ? controls : '') +
			'<div class="datepicker_links">' + (isRTL ? next : prev) +
			(this._isInRange(today) ? '<div class="datepicker_current">' +
			'<a onclick="jQuery.datepicker._gotoToday(' + this._id + ');"' +
			(showStatus ? this._addStatus(this._get('currentStatus') || '&#xa0;') : '') + '>' +
			this._get('currentText') + '</a></div>' : '') + (isRTL ? prev : next) + '</div>';
		var showWeeks = this._get('showWeeks');
		for (var row = 0; row < numMonths[0]; row++) {
		for (var col = 0; col < numMonths[1]; col++) {
			var selectedDate = new Date(drawYear, drawMonth, this._selectedDay);
			html += '<div class="datepicker_oneMonth' + (col == 0 ? ' datepicker_newRow' : '') + '">' +
				this._generateMonthYearHeader(drawMonth, drawYear, minDate, maxDate,
				selectedDate, row > 0 || col > 0) + // draw month headers
				'<table class="datepicker" cellpadding="0" cellspacing="0"><thead>' + 
				'<tr class="datepicker_titleRow">' +
				(showWeeks ? '<td>' + this._get('weekHeader') + '</td>' : '');
			var firstDay = this._get('firstDay');
			var changeFirstDay = this._get('changeFirstDay');
			var dayNames = this._get('dayNames');
			var dayNamesShort = this._get('dayNamesShort');
			var dayNamesMin = this._get('dayNamesMin');
			for (var dow = 0; dow < 7; dow++) { // days of the week
				var day = (dow + firstDay) % 7;
				var status = this._get('dayStatus') || '&#xa0;';
				status = (status.indexOf('DD') > -1 ? status.replace(/DD/, dayNames[day]) :
					status.replace(/D/, dayNamesShort[day]));
				html += '<td' + ((dow + firstDay + 6) % 7 >= 5 ? ' class="datepicker_weekEndCell"' : '') + '>' +
					(!changeFirstDay ? '<span' :
					'<a onclick="jQuery.datepicker._changeFirstDay(' + this._id + ', ' + day + ');"') + 
					(showStatus ? this._addStatus(status) : '') + ' title="' + dayNames[day] + '">' +
					dayNamesMin[day] + (changeFirstDay ? '</a>' : '</span>') + '</td>';
			}
			html += '</tr></thead><tbody>';
			var daysInMonth = this._getDaysInMonth(drawYear, drawMonth);
			if (drawYear == this._selectedYear && drawMonth == this._selectedMonth) {
				this._selectedDay = Math.min(this._selectedDay, daysInMonth);
			}
			var leadDays = (this._getFirstDayOfMonth(drawYear, drawMonth) - firstDay + 7) % 7;
			var currentDate = new Date(this._currentYear, this._currentMonth, this._currentDay);
			var endDate = this._endDay ? new Date(this._endYear, this._endMonth, this._endDay) : currentDate;
			var printDate = new Date(drawYear, drawMonth, 1 - leadDays);
			var numRows = (isMultiMonth ? 6 : Math.ceil((leadDays + daysInMonth) / 7)); // calculate the number of rows to generate
			var beforeShowDay = this._get('beforeShowDay');
			var showOtherMonths = this._get('showOtherMonths');
			var calculateWeek = this._get('calculateWeek') || $.datepicker.iso8601Week;
			var dateStatus = this._get('statusForDate') || $.datepicker.dateStatus;
			for (var dRow = 0; dRow < numRows; dRow++) { // create date picker rows
				html += '<tr class="datepicker_daysRow">' +
					(showWeeks ? '<td class="datepicker_weekCol">' + calculateWeek(printDate) + '</td>' : '');
				for (var dow = 0; dow < 7; dow++) { // create date picker days
					var daySettings = (beforeShowDay ?
						beforeShowDay.apply((this._input ? this._input[0] : null), [printDate]) : [true, '']);
					var otherMonth = (printDate.getMonth() != drawMonth);
					var unselectable = otherMonth || !daySettings[0] ||
						(minDate && printDate < minDate) || (maxDate && printDate > maxDate);
					html += '<td class="datepicker_daysCell' +
						((dow + firstDay + 6) % 7 >= 5 ? ' datepicker_weekEndCell' : '') + // highlight weekends
						(otherMonth ? ' datepicker_otherMonth' : '') + // highlight days from other months
						(printDate.getTime() == selectedDate.getTime() && drawMonth == this._selectedMonth ?
						' datepicker_daysCellOver' : '') + // highlight selected day
						(unselectable ? ' datepicker_unselectable' : '') +  // highlight unselectable days
						(otherMonth && !showOtherMonths ? '' : ' ' + daySettings[1] + // highlight custom dates
						(printDate.getTime() >= currentDate.getTime() && printDate.getTime() <= endDate.getTime() ?  // in current range
						' datepicker_currentDay' : // highlight selected day
						(printDate.getTime() == today.getTime() ? ' datepicker_today' : ''))) + '"' + // highlight today (if different)
						(unselectable ? '' : ' onmouseover="jQuery(this).addClass(\'datepicker_daysCellOver\');' +
						(!showStatus || (otherMonth && !showOtherMonths) ? '' : 'jQuery(\'#datepicker_status_' +
						this._id + '\').html(\'' + (dateStatus.apply((this._input ? this._input[0] : null),
						[printDate, this]) || '&#xa0;') +'\');') + '"' +
						' onmouseout="jQuery(this).removeClass(\'datepicker_daysCellOver\');' +
						(!showStatus || (otherMonth && !showOtherMonths) ? '' : 'jQuery(\'#datepicker_status_' +
						this._id + '\').html(\'&#xa0;\');') + '" onclick="jQuery.datepicker._selectDay(' +
						this._id + ',' + drawMonth + ',' + drawYear + ', this);"') + '>' + // actions
						(otherMonth ? (showOtherMonths ? printDate.getDate() : '&#xa0;') : // display for other months
						(unselectable ? printDate.getDate() : '<a>' + printDate.getDate() + '</a>')) + '</td>'; // display for this month
					printDate.setDate(printDate.getDate() + 1);
				}
				html += '</tr>';
			}
			drawMonth++;
			if (drawMonth > 11) {
				drawMonth = 0;
				drawYear++;
			}
			html += '</tbody></table></div>';
		}
		}
		html += (showStatus ? '<div id="datepicker_status_' + this._id + 
			'" class="datepicker_status">' + (this._get('initStatus') || '&#xa0;') + '</div>' : '') +
			(!closeAtTop && !this._inline ? controls : '') +
			'<div style="clear: both;"></div>' + 
			($.browser.msie && parseInt($.browser.version) < 7 && !this._inline ? 
			'<iframe src="javascript:false;" class="datepicker_cover"></iframe>' : '');
		return html;
	},
	
	/* Generate the month and year header. */
	_generateMonthYearHeader: function(drawMonth, drawYear, minDate, maxDate, selectedDate, secondary) {
		minDate = (this._rangeStart && minDate && selectedDate < minDate ? selectedDate : minDate);
		var showStatus = this._get('showStatus');
		var html = '<div class="datepicker_header">';
		// month selection
		var monthNames = this._get('monthNames');
		if (secondary || !this._get('changeMonth')) {
			html += monthNames[drawMonth] + '&#xa0;';
		}
		else {
			var inMinYear = (minDate && minDate.getFullYear() == drawYear);
			var inMaxYear = (maxDate && maxDate.getFullYear() == drawYear);
			html += '<select class="datepicker_newMonth" ' +
				'onchange="jQuery.datepicker._selectMonthYear(' + this._id + ', this, \'M\');" ' +
				'onclick="jQuery.datepicker._clickMonthYear(' + this._id + ');"' +
				(showStatus ? this._addStatus(this._get('monthStatus') || '&#xa0;') : '') + '>';
			for (var month = 0; month < 12; month++) {
				if ((!inMinYear || month >= minDate.getMonth()) &&
						(!inMaxYear || month <= maxDate.getMonth())) {
					html += '<option value="' + month + '"' +
						(month == drawMonth ? ' selected="selected"' : '') +
						'>' + monthNames[month] + '</option>';
				}
			}
			html += '</select>';
		}
		// year selection
		if (secondary || !this._get('changeYear')) {
			html += drawYear;
		}
		else {
			// determine range of years to display
			var years = this._get('yearRange').split(':');
			var year = 0;
			var endYear = 0;
			if (years.length != 2) {
				year = drawYear - 10;
				endYear = drawYear + 10;
			}
			else if (years[0].charAt(0) == '+' || years[0].charAt(0) == '-') {
				year = drawYear + parseInt(years[0], 10);
				endYear = drawYear + parseInt(years[1], 10);
			}
			else {
				year = parseInt(years[0], 10);
				endYear = parseInt(years[1], 10);
			}
			year = (minDate ? Math.max(year, minDate.getFullYear()) : year);
			endYear = (maxDate ? Math.min(endYear, maxDate.getFullYear()) : endYear);
			html += '<select class="datepicker_newYear" ' +
				'onchange="jQuery.datepicker._selectMonthYear(' + this._id + ', this, \'Y\');" ' +
				'onclick="jQuery.datepicker._clickMonthYear(' + this._id + ');"' +
				(showStatus ? this._addStatus(this._get('yearStatus') || '&#xa0;') : '') + '>';
			for (; year <= endYear; year++) {
				html += '<option value="' + year + '"' +
					(year == drawYear ? ' selected="selected"' : '') +
					'>' + year + '</option>';
			}
			html += '</select>';
		}
		html += '</div>'; // Close datepicker_header
		return html;
	},

	/* Provide code to set and clear the status panel. */
	_addStatus: function(text) {
		return ' onmouseover="jQuery(\'#datepicker_status_' + this._id + '\').html(\'' + text + '\');" ' +
			'onmouseout="jQuery(\'#datepicker_status_' + this._id + '\').html(\'&#xa0;\');"';
	},

	/* Adjust one of the date sub-fields. */
	_adjustDate: function(offset, period) {
		var year = this._selectedYear + (period == 'Y' ? offset : 0);
		var month = this._selectedMonth + (period == 'M' ? offset : 0);
		var day = Math.min(this._selectedDay, this._getDaysInMonth(year, month)) +
			(period == 'D' ? offset : 0);
		var date = new Date(year, month, day);
		// ensure it is within the bounds set
		var minDate = this._getMinMaxDate('min', true);
		var maxDate = this._getMinMaxDate('max');
		date = (minDate && date < minDate ? minDate : date);
		date = (maxDate && date > maxDate ? maxDate : date);
		this._selectedDay = date.getDate();
		this._selectedMonth = date.getMonth();
		this._selectedYear = date.getFullYear();
	},
	
	/* Determine the number of months to show. */
	_getNumberOfMonths: function() {
		var numMonths = this._get('numberOfMonths');
		return (numMonths == null ? [1, 1] : (typeof numMonths == 'number' ? [1, numMonths] : numMonths));
	},

	/* Determine the current maximum date - ensure no time components are set - may be overridden for a range. */
	_getMinMaxDate: function(minMax, checkRange) {
		var date = this._determineDate(minMax + 'Date', null);
		if (date) {
			date.setHours(0);
			date.setMinutes(0);
			date.setSeconds(0);
			date.setMilliseconds(0);
		}
		return date || (checkRange ? this._rangeStart : null);
	},

	/* Find the number of days in a given month. */
	_getDaysInMonth: function(year, month) {
		return 32 - new Date(year, month, 32).getDate();
	},

	/* Find the day of the week of the first of a month. */
	_getFirstDayOfMonth: function(year, month) {
		return new Date(year, month, 1).getDay();
	},

	/* Determines if we should allow a "next/prev" month display change. */
	_canAdjustMonth: function(offset, curYear, curMonth) {
		var numMonths = this._getNumberOfMonths();
		var date = new Date(curYear, curMonth + (offset < 0 ? offset : numMonths[1]), 1);
		if (offset < 0) {
			date.setDate(this._getDaysInMonth(date.getFullYear(), date.getMonth()));
		}
		return this._isInRange(date);
	},

	/* Is the given date in the accepted range? */
	_isInRange: function(date) {
		// during range selection, use minimum of selected date and range start
		var newMinDate = (!this._rangeStart ? null :
			new Date(this._selectedYear, this._selectedMonth, this._selectedDay));
		newMinDate = (newMinDate && this._rangeStart < newMinDate ? this._rangeStart : newMinDate);
		var minDate = newMinDate || this._getMinMaxDate('min');
		var maxDate = this._getMinMaxDate('max');
		return ((!minDate || date >= minDate) && (!maxDate || date <= maxDate));
	},

	/* Format the given date for display. */
	_formatDate: function(day, month, year) {
		if (!day) {
			this._currentDay = this._selectedDay;
			this._currentMonth = this._selectedMonth;
			this._currentYear = this._selectedYear;
		}
		var date = (day ? (typeof day == 'object' ? day : new Date(year, month, day)) :
			new Date(this._currentYear, this._currentMonth, this._currentDay));
		return $.datepicker.formatDate(this._get('dateFormat'), date,
			this._get('dayNamesShort'), this._get('dayNames'),
			this._get('monthNamesShort'), this._get('monthNames'));
	}
});

/* jQuery extend now ignores nulls! */
function extendRemove(target, props) {
	$.extend(target, props);
	for (var name in props) {
		if (props[name] == null) {
			target[name] = null;
		}
	}
	return target;
};

/* Attach the date picker to a jQuery selection.
   @param  settings  object - the new settings to use for this date picker instance (anonymous)
   @return jQuery object - for chaining further calls */
$.fn.attachDatepicker = function(settings) {
	return this.each(function() {
		// check for settings on the control itself - in namespace 'date:'
		var inlineSettings = null;
		for (attrName in $.datepicker._defaults) {
			var attrValue = this.getAttribute('date:' + attrName);
			if (attrValue) {
				inlineSettings = inlineSettings || {};
				try {
					inlineSettings[attrName] = eval(attrValue);
				}
				catch (err) {
					inlineSettings[attrName] = attrValue;
				}
			}
		}
		var nodeName = this.nodeName.toLowerCase();
		if (nodeName == 'input') {
			var instSettings = (inlineSettings ? $.extend($.extend({}, settings || {}),
				inlineSettings || {}) : settings); // clone and customise
			var inst = (inst && !inlineSettings ? inst :
				new DatepickerInstance(instSettings, false));
			$.datepicker._connectDatepicker(this, inst);
		} 
		else if (nodeName == 'div' || nodeName == 'span') {
			var instSettings = $.extend($.extend({}, settings || {}),
				inlineSettings || {}); // clone and customise
			var inst = new DatepickerInstance(instSettings, true);
			$.datepicker._inlineDatepicker(this, inst);
		}
	});
};

/* Detach a datepicker from its control.
   @return jQuery object - for chaining further calls */
$.fn.removeDatepicker = function() {
	var jq = this.each(function() {
		var $this = $(this);
		var nodeName = this.nodeName.toLowerCase();
		var calId = this._calId;
		this._calId = null;
		if (nodeName == 'input') {
			$this.siblings('.datepicker_append').replaceWith('');
			$this.siblings('.datepicker_trigger').replaceWith('');
			$this.removeClass($.datepicker.markerClassName).
				unbind('focus', $.datepicker.showFor).
				unbind('keydown', $.datepicker._doKeyDown).
				unbind('keypress', $.datepicker._doKeyPress);
			var wrapper = $this.parents('.datepicker_wrap');
			if (wrapper) {
				wrapper.replaceWith(wrapper.html());
			}
		} 
		else if (nodeName == 'div' || nodeName == 'span') {
			$this.removeClass($.datepicker.markerClassName).empty();
		}
		if ($('input[_calId=' + calId + ']').length == 0) {
			// clean up if last for this ID
			$.datepicker._inst[calId] = null;
		}
	});
	if ($('input.hasDatepicker').length == 0) {
		// clean up if last input 
		$.datepicker._datepickerDiv.replaceWith('');
	}
	return jq;
};

/* Enable the date picker to a jQuery selection.

   @return jQuery object - for chaining further calls */
$.fn.enableDatepicker = function() {
	return this.each(function() {
		this.disabled = false;
		$(this).siblings('button.datepicker_trigger').each(function() { this.disabled = false; });
		$(this).siblings('img.datepicker_trigger').css({opacity: '1.0', cursor: ''});
		var $this = this;
		$.datepicker._disabledInputs = $.map($.datepicker._disabledInputs,
			function(value) { return (value == $this ? null : value); }); // delete entry
	});
};

/* Disable the date picker to a jQuery selection.
   @return jQuery object - for chaining further calls */
$.fn.disableDatepicker = function() {
	return this.each(function() {
		this.disabled = true;
		$(this).siblings('button.datepicker_trigger').each(function() { this.disabled = true; });
		$(this).siblings('img.datepicker_trigger').css({opacity: '0.5', cursor: 'default'});
		var $this = this;
		$.datepicker._disabledInputs = $.map($.datepicker._disabledInputs,
			function(value) { return (value == $this ? null : value); }); // delete entry
		$.datepicker._disabledInputs[$.datepicker._disabledInputs.length] = this;
	});
};

/* Is the first field in a jQuery collection disabled as a datepicker?
   @return boolean - true if disabled, false if enabled */
$.fn.isDisabledDatepicker = function() {
	if (this.length == 0) {
		return false;
	}
	for (var i = 0; i < $.datepicker._disabledInputs.length; i++) {
		if ($.datepicker._disabledInputs[i] == this[0]) {
			return true;
		}
	}
	return false;
};

/* Update the settings for a date picker attached to an input field or division.
   @param  name   string - the name of the setting to change
                  object - the new settings to update
   @param  value  any - the new value for the setting (omit if above is a map)
   @return jQuery object - for chaining further calls */
$.fn.changeDatepicker = function(name, value) {
	var settings = name || {};
	if (typeof name == 'string') {
		settings = {};
		settings[name] = value;
	}
	return this.each(function() {
		var inst = $.datepicker._getInst(this._calId);
		if (inst) {
			extendRemove(inst._settings, settings);
			$.datepicker._updateDatepicker(inst);
		}
	});
};

/* Show the date picker attached to the first entry in a jQuery selection.
   @return jQuery object - for chaining further calls */
$.fn.showDatepicker = function() {
	$.datepicker.showFor(this);
	return this;
};

/* Set the dates for a jQuery selection.
   @param  date     Date - the new date
   @param  endDate  Date - the new end date for a range (optional)
   @return jQuery object - for chaining further calls */
$.fn.setDatepickerDate = function(date, endDate) {
	return this.each(function() {
		var inst = $.datepicker._getInst(this._calId);
		if (inst) {
			inst._setDate(date, endDate);
			$.datepicker._updateDatepicker(inst);
		}
	});
};

/* Get the date(s) for the first entry in a jQuery selection.
   @return Date - the current date or
           Date[2] - the current dates for a range*/
$.fn.getDatepickerDate = function() {
	var inst = (this.length > 0 ? $.datepicker._getInst(this[0]._calId) : null);
	return (inst ? inst._getDate() : null);
};
	
/* Initialise the date picker. */
$(document).ready(function() {
	$.datepicker = new Datepicker(); // singleton instance
	$(document.body).append($.datepicker._datepickerDiv).
		mousedown($.datepicker._checkExternalClick);
});

})(jQuery);
