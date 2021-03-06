/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */
 // DungNP Alter the original calendar code
Calendar = function() {};
Calendar.getHighestZIndex = function(containerEl) {
    var highestIndex = 0;
    var currentIndex = 0;
    var els = Array();
    els = containerEl ? containerEl.getElementsByTagName('*') : document.getElementsByTagName('*');
    for (var i = 0; i < els.length; i++) {
        currentIndex = YAHOO.util.Dom.getStyle(els[i], "zIndex");
        if (!isNaN(currentIndex) && currentIndex > highestIndex) {
            highestIndex = parseInt(currentIndex);
        }
    }
    return (highestIndex == Number.MAX_VALUE) ? Number.MAX_VALUE : highestIndex + 1;
};
Calendar.getDateField = function(id, form) {
    var input;
    if (form) {
        var formElement = document.getElementById(form);
        if (formElement) {
            for (var i = 0; i < formElement.elements.length; i++) {
                if (formElement.elements[i].id == id) {
                    input = formElement.elements[i];
                    break;
                }
            }
        }
    } else {
        input = document.getElementById(id);
    }
    return input;
};
Calendar.setup = function(params) {
    YAHOO.util.Event.onDOMReady(function() {
        var Event = YAHOO.util.Event;
        var Dom = YAHOO.util.Dom;
        var dialog;
        var calendar;
        var showButton = params.button ? params.button : params.buttonObj;
        var userDateFormat = params.ifFormat ? params.ifFormat : (params.daFormat ? params.daFormat : "m/d/Y");
        var inputField = params.inputField ? params.inputField : params.inputFieldObj.id;
        var form = params.form ? params.form : '';
        var startWeekday = params.startWeekday ? params.startWeekday : 0;
        var dateFormat = userDateFormat.substr(0, 10);
        var date_field_delimiter = /([-.\\/])/.exec(dateFormat)[0];
        dateFormat = dateFormat.replace(/[^a-zA-Z]/g, '');
        var monthPos = dateFormat.search(/m/);
        var dayPos = dateFormat.search(/d/);
        var yearPos = dateFormat.search(/Y/);
        var dateParams = new Object();
        dateParams.delim = date_field_delimiter;
        dateParams.monthPos = monthPos;
        dateParams.dayPos = dayPos;
        dateParams.yearPos = yearPos;
        var showButtonElement = Dom.get(showButton);
        var dateInputField = Dom.get(inputField);
        function calendarCreate() {
            if (!dialog) {
                dialog = new YAHOO.widget.SimpleDialog("container_" + showButtonElement.id, {
                    visible: false,
                    context: [showButton, "tl", "bl", ["beforeShow"],
                        [-175, 5]
                    ],
                    buttons: [],
                    draggable: false,
                    close: true,
                    zIndex: Calendar.getHighestZIndex(document.body),
                    constraintoviewport: true
                });
                dialog.setHeader(SUGAR.language.get('app_strings', 'LBL_MASSUPDATE_DATE'));
                var dialogBody = '<p class="callnav_today" style="text-align:center;"><a href="javascript:void(0)"  id="callnav_today" style="display:  inline-block;padding-right:10px;">' + SUGAR.language.get('app_strings', 'LBL_EMAIL_DATE_TODAY') +'</a><a href="javascript:void(0)"  id="callnav_1day"  style="display: inline-block;padding-right: 10px;">+1</a>'+'<a href="javascript:void(0)"  style="display: inline-block;padding-right: 10px;"  id="callnav_7days">+7</a>'+'<a href="javascript:void(0)" style="display: inline-block;padding-right: 10px;" id="callnav_30days">+30</a>'+ '<a href="javascript:void(0)" style="display: inline-block;padding-right: 10px;" id="callnav_60days">+60</a>' +'<a href="javascript:void(0)" style="display: inline-block;padding-right: 10px;" id="callnav_90days">+90</a>'+'<a href="javascript:void(0)" id="callnav_120days">+120</a>'+'</p><div id="' + showButtonElement.id + '_div"></div>';
                dialog.setBody(dialogBody);
                dialog.render(document.body);
                Dom.addClass("container_" + showButtonElement.id, "cal_panel");
                Event.addListener("callnav_today", "click", function() {
                    calendar.clear();
                    var now = new Date();
                    // custom code Set hour, minute current
                        var pre_idButton = "#"+ showButtonElement.id.replace("trigger","");
                        var hour = now.getHours();
                        if (hour < 10) {
                            hour = '0' + hour;
                        }
                        $(pre_idButton+'hours').val(hour) ;
                        var minute = now.getMinutes();
                        if (minute < 15) {
                            $(pre_idButton+'minutes').val('00');
                        } 
                        if (minute >= 15 && minute <30  ) {
                            $(pre_idButton+'minutes').val('15');
                        }
                        if (minute >= 30 && minute < 45  ) {
                            $(pre_idButton+'minutes').val('30');
                        }
                        if (minute >= 45 && minute < 60  ) {
                            $(pre_idButton+'minutes').val('30');
                        }
    
                    var input = Calendar.getDateField(inputField, form);
                    input.value = formatSelectedDate(now);
                    var cellIndex = calendar.getCellIndex(now);
                    if (cellIndex > -1) {
                        var cell = calendar.cells[cellIndex];
                        Dom.addClass(cell, calendar.Style.CSS_CELL_SELECTED);
                    }
                    if (input.onchange)
                        input.onchange();
                    SUGAR.util.callOnChangeListers(input);
                    return false;
                });
                Event.addListener("callnav_7days", "click", function() {
                    calendar.clear();
                    var now = new Date();
                    var day_7 = new Date(now.getFullYear(), now.getMonth(), now.getDate()+7);
                    var getMonth_now = now.getMonth();
                    var getMonth_day_7 = day_7.getMonth();
                    var add_month = getMonth_day_7 - getMonth_now;
                    calendar.addMonths(add_month);
                    var getYear_now = now.getYear();
                    var getYear_day_7 = day_7.getYear();
                    var add_year = getYear_day_7 - getYear_now;
                    calendar.addYears(add_year);
                    var input = Calendar.getDateField(inputField, form);
                    input.value = formatSelectedDate(day_7);
                    var cellIndex = calendar.getCellIndex(day_7);
                    if (cellIndex > -1) {
                        var cell = calendar.cells[cellIndex];
                        Dom.addClass(cell, calendar.Style.CSS_CELL_SELECTED);
                    }
                    if (input.onchange)
                        input.onchange();
                    SUGAR.util.callOnChangeListers(input);
                    return false;
                });
                Event.addListener("callnav_1day", "click", function() {                    
                    var now = new Date();
                    var day_1 = new Date(now.getFullYear(), now.getMonth(), now.getDate()+1); 
                    var getMonth_now = now.getMonth();
                    var getMonth_day_1= day_1.getMonth();
                    var add_month = getMonth_day_1 - getMonth_now;
                    calendar.addMonths(add_month);
                    var getYear_now = now.getYear();
                    var getYear_day_1 = day_1.getYear();
                    var add_year = getYear_day_1 - getYear_now;
                    calendar.addYears(add_year);
                    var input = Calendar.getDateField(inputField, form);
                    input.value = formatSelectedDate(day_1);
                    var cellIndex = calendar.getCellIndex(day_1);
                    if (cellIndex > -1) {
                        var cell = calendar.cells[cellIndex];
                        Dom.addClass(cell, calendar.Style.CSS_CELL_SELECTED);
                    }
                    if (input.onchange)
                        input.onchange();
                    SUGAR.util.callOnChangeListers(input);
                    return false;
                });
                Event.addListener("callnav_30days", "click", function() {
                    calendar.clear();
                    var now = new Date();
                    var day_30 = new Date(now);
                    day_30.setDate(day_30.getDate()+30);
                    var getMonth_now = now.getMonth();
                    var getMonth_day_30 = day_30.getMonth();
                    var add_month = getMonth_day_30 - getMonth_now;
                    calendar.addMonths(add_month);
                    var getYear_now = now.getYear();
                    var getYear_day_30 = day_30.getYear();
                    var add_year = getYear_day_30 - getYear_now;
                    calendar.addYears(add_year);
                    var input = Calendar.getDateField(inputField, form);
                    input.value = formatSelectedDate(day_30);
                    var cellIndex = calendar.getCellIndex(day_30);
                    if (cellIndex > -1) {
                        var cell = calendar.cells[cellIndex];
                        Dom.addClass(cell, calendar.Style.CSS_CELL_SELECTED);
                    }
                    if (input.onchange)
                        input.onchange();
                    SUGAR.util.callOnChangeListers(input);
                    return false;
                });
                Event.addListener("callnav_60days", "click", function() {
                    calendar.clear();
                    var now = new Date();
                    var day_60 = new Date(now);
                    day_60.setDate(day_60.getDate()+ 60);
                    var getMonth_now = now.getMonth();
                    var getMonth_day_60 = day_60.getMonth();
                    var add_month = getMonth_day_60 - getMonth_now;
                    calendar.addMonths(add_month);
                    var getYear_now = now.getYear();
                    var getYear_day_60 = day_60.getYear();
                    var add_year = getYear_day_60 - getYear_now;
                    calendar.addYears(add_year);
                    var input = Calendar.getDateField(inputField, form);
                    input.value = formatSelectedDate(day_60);
                    var cellIndex = calendar.getCellIndex(day_60);
                    if (cellIndex > -1) {
                        var cell = calendar.cells[cellIndex];
                        Dom.addClass(cell, calendar.Style.CSS_CELL_SELECTED);
                    }
                    if (input.onchange)
                        input.onchange();
                    SUGAR.util.callOnChangeListers(input);
                    return false;
                });
                //VUT-S-Add 90days
                Event.addListener("callnav_90days", "click", function() {
                    calendar.clear();
                    // debugger;
                    var now = new Date();
                    var day_90 = new Date(now);
                    day_90.setDate(day_90.getDate()+ 90);
                    var getMonth_now = now.getMonth();
                    var getMonth_day_90 = day_90.getMonth();
                    var add_month = getMonth_day_90 - getMonth_now;
                    calendar.addMonths(add_month);
                    var getYear_now = now.getYear();
                    var getYear_day_90 = day_90.getYear();
                    var add_year = getYear_day_90 - getYear_now;
                    calendar.addYears(add_year);
                    var input = Calendar.getDateField(inputField, form);
                    input.value = formatSelectedDate(day_90);
                    var cellIndex = calendar.getCellIndex(day_90);
                    if (cellIndex > -1) {
                        var cell = calendar.cells[cellIndex];
                        Dom.addClass(cell, calendar.Style.CSS_CELL_SELECTED);
                    }
                    if (input.onchange)
                        input.onchange();
                    SUGAR.util.callOnChangeListers(input);
                    return false;
                });
                //VUT-E-Add 90days
                Event.addListener("callnav_120days", "click", function() {
                    calendar.clear();
                    var now = new Date();
                    var day_120 = new Date(now);
                    day_120.setDate(day_120.getDate() + 120);
                    var getMonth_now = now.getMonth();
                    var getMonth_day_120 = day_120.getMonth();
                    var add_month = getMonth_day_120 - getMonth_now;
                    calendar.addMonths(add_month);
                    var getYear_now = now.getYear();
                    var getYear_day_120 = day_120.getYear();
                    var add_year = getYear_day_120 - getYear_now;
                    calendar.addYears(add_year);
                    var input = Calendar.getDateField(inputField, form);
                    input.value = formatSelectedDate(day_120);
                    var cellIndex = calendar.getCellIndex(day_120);
                    if (cellIndex > -1) {
                        var cell = calendar.cells[cellIndex];
                        Dom.addClass(cell, calendar.Style.CSS_CELL_SELECTED);
                    }
                    if (input.onchange)
                        input.onchange();
                    SUGAR.util.callOnChangeListers(input);
                    return false;
                });
                
                dialog.showEvent.subscribe(function() {
                    if (YAHOO.env.ua.ie) {
                        dialog.fireEvent("changeContent");
                    }
                });
                Event.on(document, "click", function(e) {
                    if (!dialog) {
                        return;
                    }
                    var el = Event.getTarget(e);
                    var dialogEl = dialog.element;
                    //Nhat code - check class date time.
                    let inputElClass = $(el).attr("class");
                    
                    let isDateField = () => {
                      let firstStr = "";
                      let secondStr = "";
                      // Check Input field had changed.
                      firstStr = dateInputField.id;
                      if ($(el).attr("id") == undefined) {
                        secondStr = "";
                      } else {
                        secondStr = $(el).attr("id");
                      }
                      if (firstStr !== secondStr) {
                        dialog.hide();
                      }
                      return inputElClass === "date_input" || inputElClass === "datetimecombo_date" ? true : false;
                    };
                    
                    if (el != dialogEl && !Dom.isAncestor(dialogEl, el) && el != showButtonElement && !Dom.isAncestor(showButtonElement, el) && !isDateField()) {
                      dialog.hide();
                    } 
                   
                    //dung code - auto sort calendar dashlet 
                    var date_sort = $('#inset_date_dashlet').val();
                    if(date_sort !== '' && typeof(date_sort) !== 'undefined') {
                        var dashlets_id =  $('#inset_date_dashlet').parent().parent().parent().parent().parent().parent().attr('id');
                        dashlets_id = dashlets_id.slice(8);
                        date_sort = date_sort.split('/');
                        var url = "index.php?module=Home&action=DynamicAction&DynamicAction=displayDashlet&sugar_body_only=1&&year="+date_sort[2]+"&month="+ date_sort[1]+"&day="+date_sort[0]+"&hour=0&id=" +dashlets_id;
                        SUGAR.mySugar.retrieveDashlet(dashlets_id, url);
                    }
                });
            }
            if (!calendar) {
                var navConfig = {
                    strings: {
                        month: SUGAR.language.get('app_strings', 'LBL_CHOOSE_MONTH'),
                        year: SUGAR.language.get('app_strings', 'LBL_ENTER_YEAR'),
                        submit: SUGAR.language.get('app_strings', 'LBL_EMAIL_OK'),
                        cancel: SUGAR.language.get('app_strings', 'LBL_CANCEL_BUTTON_LABEL'),
                        invalidYear: SUGAR.language.get('app_strings', 'LBL_ENTER_VALID_YEAR')
                    },
                    monthFormat: YAHOO.widget.Calendar.SHORT,
                    initialFocus: "year"
                };
                calendar = new YAHOO.widget.Calendar(showButtonElement.id + '_div', {
                    iframe: false,
                    hide_blank_weeks: true,
                    navigator: navConfig
                });
                calendar.cfg.setProperty('DATE_FIELD_DELIMITER', date_field_delimiter);
                calendar.cfg.setProperty('MDY_DAY_POSITION', dayPos + 1);
                calendar.cfg.setProperty('MDY_MONTH_POSITION', monthPos + 1);
                calendar.cfg.setProperty('MDY_YEAR_POSITION', yearPos + 1);
                calendar.cfg.setProperty('START_WEEKDAY', startWeekday);
                if (typeof SUGAR.language.languages['app_list_strings'] != 'undefined' && SUGAR.language.languages['app_list_strings']['dom_cal_month_long'] != 'undefined') {
                    if (SUGAR.language.languages['app_list_strings']['dom_cal_month_long'].length == 13) {
                        SUGAR.language.languages['app_list_strings']['dom_cal_month_long'].shift();
                    }
                    calendar.cfg.setProperty('MONTHS_LONG', SUGAR.language.languages['app_list_strings']['dom_cal_month_long']);
                }
                if (typeof SUGAR.language.languages['app_list_strings'] != 'undefined' && typeof SUGAR.language.languages['app_list_strings']['dom_cal_day_short'] != 'undefined') {
                    if (SUGAR.language.languages['app_list_strings']['dom_cal_day_short'].length == 8) {
                        SUGAR.language.languages['app_list_strings']['dom_cal_day_short'].shift();
                    }
                    calendar.cfg.setProperty('WEEKDAYS_SHORT', SUGAR.language.languages['app_list_strings']['dom_cal_day_short']);
                }
                var formatSelectedDate = function(selDate) {
                    var monthVal = selDate.getMonth() + 1;
                    if (monthVal < 10) {
                        monthVal = '0' + monthVal;
                    }
                    var dateVal = selDate.getDate();
                    if (dateVal < 10) {
                        dateVal = '0' + dateVal;
                    }
                    var yearVal = selDate.getFullYear();
                    selDate = '';
                    if (monthPos == 0) {
                        selDate = monthVal;
                    } else if (dayPos == 0) {
                        selDate = dateVal;
                    } else {
                        selDate = yearVal;
                    }
                    if (monthPos == 1) {
                        selDate += date_field_delimiter + monthVal;
                    } else if (dayPos == 1) {
                        selDate += date_field_delimiter + dateVal;
                    } else {
                        selDate += date_field_delimiter + yearVal;
                    }
                    if (monthPos == 2) {
                        selDate += date_field_delimiter + monthVal;
                    } else if (dayPos == 2) {
                        selDate += date_field_delimiter + dateVal;
                    } else {
                        selDate += date_field_delimiter + yearVal;
                    }
                    return selDate;
                };
                calendar.selectEvent.subscribe(function(type, args, obj) {
                    var input = Calendar.getDateField(inputField, form);
                    if (calendar.getSelectedDates().length > 0) {
                        input.value = formatSelectedDate(calendar.getSelectedDates()[0]);
                        if (params.comboObject) {
                            params.comboObject.update();
                        }
                    } else if (typeof args[0][0] == 'object') {
                        selDate = args[0][0];
                        input.value = formatSelectedDate(new Date(selDate[0], selDate[1], selDate[2]));
                    } else {
                        input.value = '';
                    }
                    if (input.onchange)
                        input.onchange();
                    dialog.hide();
                    SUGAR.util.callOnChangeListers(input);
                });
                calendar.renderEvent.subscribe(function() {
                    dialog.fireEvent("changeContent");
                });
            }
            var sanitizeDate = function(date, dateParams) {
                var dateArray = Array();
                var returnArray = Array('', '', '');
                var delimArray = Array(".", "/", "-");
                var dateCheck = 0;
                for (var delimCounter = 0; delimCounter < delimArray.length; delimCounter++) {
                    dateArray = date.split(delimArray[delimCounter]);
                    if (dateArray.length == 3) {
                        break;
                    }
                }
                if (dateArray.length != 3) {
                    var oDate = new Date();
                    var dateArray = [0, 0, 0];
                    dateArray[dateParams.dayPos] = oDate.getDate();
                    dateArray[dateParams.monthPos] = oDate.getMonth() + 1;
                    dateArray[dateParams.yearPos] = oDate.getFullYear();
                }
                for (var i = 0; i < dateArray.length; i++) {
                    if (dateArray[i] > 32) {
                        returnArray[dateParams.yearPos] = dateArray[i];
                        dateCheck += 1;
                    } else if (dateArray[i] <= 12) {
                        if ((dateParams.monthPos < dateParams.dayPos) && (returnArray[dateParams.monthPos] == '')) {
                            returnArray[dateParams.monthPos] = dateArray[i];
                            dateCheck += 100;
                        } else if ((dateParams.monthPos > dateParams.dayPos) && (returnArray[dateParams.dayPos] != '')) {
                            returnArray[dateParams.monthPos] = dateArray[i];
                            dateCheck += 100;
                        } else if ((dateParams.dayPos < dateParams.monthPos) && (returnArray[dateParams.dayPos] == '')) {
                            returnArray[dateParams.dayPos] = dateArray[i];
                            dateCheck += 10;
                        } else if ((dateParams.dayPos > dateParams.monthPos) && (returnArray[dateParams.monthPos] != '')) {
                            returnArray[dateParams.dayPos] = dateArray[i];
                            dateCheck += 10;
                        }
                    } else if (dateArray[i] > 12 && dateArray[i] < 32) {
                        if (returnArray[dateParams.dayPos] != '') {
                            returnArray[dateParams.monthPos] = returnArray[dateParams.dayPos];
                            dateCheck -= 10;
                            dateCheck += 100;
                        }
                        returnArray[dateParams.dayPos] = dateArray[i];
                        dateCheck += 10;
                    }
                }
                if (dateCheck != 111) {
                    return sanitizeDate("", dateParams);
                }
                return returnArray.join(dateParams.delim);
            };
            var sanitizedDate = sanitizeDate(Calendar.getDateField(inputField, form).value, dateParams);
            var sanitizedDateArray = sanitizedDate.split(dateParams.delim);
            calendar.cfg.setProperty("selected", sanitizedDate);
            calendar.cfg.setProperty("pageDate", sanitizedDateArray[monthPos] + dateParams.delim + sanitizedDateArray[yearPos]);
            calendar.render();
            dialog.show();
        };
        Event.on(showButtonElement, "click", calendarCreate);
        //Nhat code - add event date field is clicked;
        Event.on(dateInputField, "click", calendarCreate);
        //Add placeholder
        $(dateInputField).attr("placeholder", "DD/MM/YYYY").css("width", "auto");
    });
};
