/**
 * Advanced OpenReports, SugarCRM Reporting.
 * @package Advanced OpenReports for SugarCRM
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility <info@salesagility.com>
 */

$(document).ready(function () {

  $('#download_pdf_button_old').click(function () {

    var _form = addParametersToForm('DownloadPDF');

    var rGraphs = document.getElementsByClassName('resizableCanvas');
    for (var i = 0; i < rGraphs.length; i++) {
      _form.append('<input type="hidden" id="graphsForPDF" name="graphsForPDF[]" value=' + rGraphs[i].toDataURL() + '>');
    }

    _form.submit();

    $("#formDetailView #graphsForPDF").remove();
  });

  $('#download_csv_button_old').click(function () {

    var _form = addParametersToForm('Export');

    _form.submit();
  });

  $('#updateParametersButton').click(function(){

    var _form = addParametersToForm('DetailView');

    _form.submit();
  });

  //dung code - button SEND Email report name "SOLAR DESIGN COMPLETE TO BE SENT"
  if($('input[name="record"]').val() == '9a6c9a44-b626-787a-ed29-5c1317e08434') {
    $('#tab-actions').after('<li><a id="send_email_SG_design_complete">EMAIL OLD QUOTES</a></li>');
    $("#send_email_SG_design_complete").click(function(){
        $.ajax({
          url : 'index.php?entryPoint=CustomSGDesignCompleteToSent',
          success:  function(data){                
              alert('Success');
          }

        })
    })
  }
  //dung code - button update GEO STATUS report name "SUBSIDY UNPAID"
  else if($('input[name="record"]').val() == 'ca98eb8d-5d0f-dbe7-a321-5b6906d2d313'){
    $('#tab-actions').after('<li><a id="update_GEO_Status_From_Report_SUBSIDY_UNPAID">Update GEO Status</a></li>');
    $("#update_GEO_Status_From_Report_SUBSIDY_UNPAID").click(function(){
        $.ajax({
          url : 'index.php?entryPoint=update_GEO_Status_From_Report_SUBSIDY_UNPAID',
          success:  function(data){  
            if(data.trim() == 'success'){
              location.reload();
            }            
          }

        })
    })
  }

    //dung code - button "Mark As Lost" lost report name "SENT FOLLOW UP ON OLD QUOTE"
    if($('input[name="record"]').val() == '325d4ac7-f4b0-f7c5-31ea-5c3c052a4afd') {
      $('#tab-actions').after('<li><a id="Mark_As_Lost">Mark As Lost</a></li>');
      $("#Mark_As_Lost").click(function(){
          $.ajax({
            url : 'index.php?entryPoint=Mark_As_Lost',
            success:  function(data){                
                alert('Success');
            }
  
          })
      })
    }

     //dung code - button SEND Email report 

  $('#tab-actions').after('<li><a id="send_email_report">Email Report</a></li>');
  $("#send_email_report").click(function(){
    var record_id_report =  $("input[name='record']").val();
      $.ajax({
        url : 'index.php?entryPoint=CustomSendEmailReport&report_id='+record_id_report,
        success:  function(data){                
            alert('Success');
        }
      })
  })
  $('#send_email_report').parent().after('<li><a id="reload_report">Reload Report</a></li>');
  $("#reload_report").click(function(){
    $.ajax({
      url : 'index.php?entryPoint=customChmod777',
      success:  function(data){                
          alert('Success');
      }
    })
  })
});


function updateTimeDateFields(fieldInput, ln) {
  // datetime combo fields
  if (typeof fieldInput === 'undefined'
    && $("[name='aor_conditions_value\\[" + ln + "\\]").val()
    && $("[name='aor_conditions_value\\[" + ln + "\\]").hasClass('DateTimeCombo')) {
    var datetime = $("[name='aor_conditions_value\\[" + ln + "\\]']").val();
    var date = datetime.substr(0, 10);
    var formatDate = $.datepicker.formatDate('yy-mm-dd', new Date(date));
    fieldInput = datetime.replace(date, formatDate) + ':00';
  }
  return fieldInput;
}

function updateHiddenReportFields(ln, _form) {
// Fix for issue #1272 - AOR_Report module cannot update Date type parameter.
  if ($("#aor_conditions_value\\["+ln+"\\]\\[0\\]").length) {
      var fieldValue = $("#aor_conditions_value\\["+ln+"\\]\\[0\\]").val();
      var fieldSign = $("#aor_conditions_value\\["+ln+"\\]\\[1\\]").val();
      var fieldNumber = $("#aor_conditions_value\\["+ln+"\\]\\[2\\]").val();
      var fieldTime = $("#aor_conditions_value\\["+ln+"\\]\\[3\\]").val();

      _form.append('<input type="hidden" name="parameter_date_value['+ ln + ']" value="' + fieldValue + '">');
      _form.append('<input type="hidden" name="parameter_date_sign['+ ln + ']" value="' + fieldSign + '">');
      _form.append('<input type="hidden" name="parameter_date_number['+ ln + ']" value="' + fieldNumber + '">');
      _form.append('<input type="hidden" name="parameter_date_time['+ ln + ']" value="' + fieldTime + '">');
  }
}

function localToDbFormat(index, ln, fieldInput) {
// Fix for issue #1082 - change local date format to db date format
  if ($('#aor_conditions_value' + index + '').hasClass('date_input')) { // only change to DB format if its a date
    if ($('#aor_conditions_value' + ln + '').hasClass('date_input')) {
      fieldInput = $.datepicker.formatDate('yy-mm-dd', new Date(fieldInput));
    }
  }
  return fieldInput;
}

function appendHiddenFields(_form, ln, id) {
    _form.append('<input type="hidden" name="parameter_id\[' + ln + '\]" value="' + id + '">');
    var operator = $("#aor_conditions_operator\\[" + ln + "\\]").val();
    _form.append('<input type="hidden" name="parameter_operator\[' + ln + '\]" value="' + operator + '">');
    var fieldType = $("#aor_conditions_value_type\\[" + ln + "\\]").val();
    _form.append('<input type="hidden" name="parameter_type[' + ln + ']" value="' + fieldType + '">');

    // values can be #aor_conditions_value3 or #aor_conditions_value[3]
    var fieldInput = '';
    if ($("#aor_conditions_value\\["+ln+"\\]\\[0\\]").length > 0) {
        fieldInput = $("#aor_conditions_value\\["+ln+"\\]\\[0\\]").val();
    } else if ($("#aor_conditions_value\\["+ln+"\\]").length > 0) {
        fieldInput = $("#aor_conditions_value\\["+ln+"\\]").val();
    } else if ($("[name='aor_conditions_value\\[" + ln + "\\]']").length > 0) {
    	fieldInput = $("[name='aor_conditions_value\\[" + ln + "\\]']").val();
    }

    fieldInput = updateTimeDateFields(fieldInput, ln);
    _form.append('<input type="hidden" name="parameter_value[' + ln + ']" value="' + fieldInput + '">');
	
    updateHiddenReportFields(ln, _form);
}

function addParametersToForm(action) {
  var _form = $('#formDetailView');
  _form.find('input[name=action]').val(action);

  $('.aor_conditions_id').each(function(index, elem) {
    $elem = $(elem);
    var ln = $elem.attr('id').substr(17);
    var id = $elem.val();
    appendHiddenFields(_form, ln, id);
  });
  return _form;
}

function openProspectPopup() {

  var popupRequestData = {
    "call_back_function": "setProspectReturn",
    "form_name": "EditView",
    "field_to_name_array": {
      "id": "prospect_id"
    }
  };

  open_popup('ProspectLists', '600', '400', '', true, false, popupRequestData);

}

function setProspectReturn(popup_reply_data) {

  var callback = {
    success: function (result) {
      //report_rel_modules = result.responseText;
      //alert('pass '+result.responseText);
    },
    failure: function (result) {
      //alert('fail '+result.responseText);
    }
  }

  var prospect_id = popup_reply_data.name_to_value_array.prospect_id;
  var record = document.getElementsByName('record')[0].value;

  var form = addParametersToForm("addToProspectList");
  var query = form.serialize();
  YAHOO.util.Connect.asyncRequest("GET", "index.php?" + query + "&prospect_id=" + prospect_id, callback);
}

function changeReportPage(record, offset, group_value, table_id) {
  var paginationButtonCaller = $(this);
  var query = "?module=AOR_Reports&action=changeReportPage&record=" + record + "&offset=" + offset + "&group=" + group_value;
  $('.aor_conditions_id').each(function (index, elem) {
    $elem = $(elem);
    var ln = $elem.attr('id').substr(17);
    var id = $elem.val();
    query += "&parameter_id[]=" + id;
    var operator = $("#aor_conditions_operator\\[" + ln + "\\]").val();
    query += "&parameter_operator[]=" + operator;
    var fieldType = $('#aor_conditions_value_type\\[' + ln + '\\]').val();
    query += "&parameter_type[]=" + fieldType;
    var fieldInput = '';
    if ($("#aor_conditions_value\\["+ln+"\\]\\[0\\]").length > 0) {
		var fieldValue = $("#aor_conditions_value\\["+ln+"\\]\\[0\\]").val();
        query += "&parameter_date_value[]=" + fieldValue;
        var fieldSign = $("#aor_conditions_value\\["+ln+"\\]\\[1\\]").val();
        query += "&parameter_date_sign[]=" + fieldSign;
        var fieldNumber = $("#aor_conditions_value\\["+ln+"\\]\\[2\\]").val();
        query += "&parameter_date_number[]=" + fieldNumber;
        var fieldTime = $("#aor_conditions_value\\["+ln+"\\]\\[3\\]").val();
        query += "&parameter_date_time[]=" + fieldTime;
        fieldInput = $("#aor_conditions_value\\["+ln+"\\]\\[0\\]").val();
        fieldInput = updateTimeDateFields(fieldInput, ln);
    } else {
      fieldInput = $('#aor_conditions_value\\[' + ln + '\\]').val();
    }
    query += "&parameter_value[]=" + fieldInput;
  });

  $.get(query).done(
    function (data) {
      $('#report_table_' + table_id + group_value).replaceWith(data);
    }
  );
}
