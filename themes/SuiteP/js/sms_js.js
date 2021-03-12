
$(document).ready(function(){
	var phone_number_customer = "";
	var name_customer = '';
	var user_name = '';
    var module_send = '' ;
    var record_id = '';
    window.Object_Customer_SMS = {
        'phone_number' : '',
        'first_name' : ''
    };
    function render_attachment(){
        "use strict";
        event.preventDefault();
     
        // Add the file input onto the page
        var id = SMS_generateUUID();

        var fileGroupContainer = $('<div></div>')
            .addClass('attachment-group-container')
            .appendTo($('body').find('#group_sms_files'));

        var fileInput = $('<input>')
            .attr('type', 'file')
            .attr('id', 'file_' + id)
            .attr('name', 'sms_attachment[]')
            .attr('multiple', 'true')
            .appendTo(fileGroupContainer);


        var fileLabel = $('<label></label>')
            .attr('for', 'file_' + id)
            .addClass('attachment-blank')
            .html('<span class="glyphicon glyphicon-paperclip"></span>')
            .appendTo(fileGroupContainer);

        // use the label to open file dialog
        fileLabel.click();

        // handle when the a file is selected
        fileInput.change(function (event) {

            if (event.target.files.length === 0) {
            fileGroupContainer.remove();
            return false;
            }
            if (event.target.files.length > 1) {
            $(fileLabel.addClass('label-with-multiple-files'));
            } else {
            $(fileLabel.removeClass('label-with-multiple-files'));
            }

            fileLabel.html('');
            fileLabel.empty();

            if (fileGroupContainer.find('.attachment-remove').length === 0) {
            var removeAttachment = $('<a class="attachment-remove"><span class="glyphicon glyphicon-remove"></span></a>');
            fileGroupContainer.append(removeAttachment);
            // handle when user removes attachment
            removeAttachment.click(function () {
                fileGroupContainer.remove();
            });
            }

            for (var i = 0; i < event.target.files.length; i++) {
            var file = event.target.files[i];
            var name = file.name;
            var size = file.size;
            var type = file.type;

            var fileContainer = $('<div class="attachment-file-container"></div>');
            fileContainer.appendTo(fileLabel);
            // Create icons based on file type
            if (type.indexOf('image') !== -1) {
                fileContainer.addClass('file-image');
                fileContainer.append('<span class="attachment-type glyphicon glyphicon-picture"></span>');
            } else if (type.indexOf('audio') !== -1) {
                fileContainer.addClass('file-audio');
                fileContainer.append('<span class="attachment-type glyphicon glyphicon-music"></span>');
            } else if (type.indexOf('video') !== -1) {
                fileContainer.addClass('file-video');
                fileContainer.append('<span class="attachment-type glyphicon glyphicon-film"></span>');
            } else if (type.indexOf('zip') !== -1) {
                fileContainer.addClass('file-video');
                fileContainer.append('<span class="attachment-type glyphicon glyphicon-compressed"></span>');
            } else {
                fileContainer.addClass('file-other');
                fileContainer.append('<span class="attachment-type glyphicon glyphicon-file"></span>');
            }
            fileContainer.append('<span class="attachment-name"> ' + name + ' </span>');
            fileContainer.append('<span class="attachment-size"> 12331 </span>');

            fileLabel.removeClass('attachment-blank');

            }

        });

        return false;
    }

    function SMS_generateUUID() {
        var d = new Date().getTime();
        if (window.performance && typeof window.performance.now === "function") {
            d += performance.now();
            ; //use high-precision timer if available
        }
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
        return uuid;
    };
    
	$( "#dialog_send_sms" ).dialog({
       	autoOpen: false,
	    width: 1000,
		height: 500,
		buttons: {
            Attach: function(){
                render_attachment();
            },
			Cancel: function(){
				$(this).dialog('close');
                $( "#content_messager" ).val("");
                $('#phone_number_customer').text('');
				$('#messager_template').prop('selectedIndex',0);
                $('#from_phone_number').prop('selectedIndex',0);
			},
			Send: function(){
				click_send_sms();
				$(this).dialog('close');
                $( "#content_messager" ).val("");
                $('#phone_number_customer').text('');
				$('#messager_template').prop('selectedIndex',0);
                $('#from_phone_number').prop('selectedIndex',0);
                $("#group_sms_files").empty();
            },
		}
    });
	$('button[class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close"]').click(function(){
        $( "#content_messager" ).val("");
        $('#phone_number_customer').text('');
		$('#messager_template').prop('selectedIndex',0);
		$('#from_phone_number').prop('selectedIndex',0);
	});
//autocomplete for get sms template
$('#sms_template_name').autocomplete({
    open: function(){
      $('.ui-autocomplete').css('z-index', 99999999999999);
    },
    source: function(request, response){
      if(request['term'] !== '') {
        $.ajax({
          url: "/index.php?entryPoint=customGetSMSTemplate&term="+ request['term'] ,
          type : 'GET',
          success: function(result){
            if(result !== 'null'){
              var suggest =[];
              var jsonObject = $.parseJSON(result);
              for (i = 0; i < jsonObject.length; i++) {
                  suggest.push(jsonObject[i].name);
              }
              response(suggest);
              array_result = jsonObject;
            }
          },
          error: function(result){
            console.log('error');
          }
        })
      }
    },
    select: function(event, ui) {
      var args =  {
        form_name: "ComposeView",
        name_to_value_array:{
          pe_smstemplate_id_c:"",
          sms_template_c:"",
        }
      };
      args.name_to_value_array.sms_template_c = ui.item.value;
      var name_sms_template_select = ui.item.value;
      for (let index = 0; index < array_result.length; index++) {
        if(name_sms_template_select == array_result[index].name){
          $('#sms_template_id').val(array_result[index].id); 
          args.name_to_value_array.pe_smstemplate_id_c = array_result[index].id;    
          var content_sms = array_result[index].body_c.replace("$first_name",window.Object_Customer_SMS.first_name);
          $("#content_messager").val(content_sms);
        }
      };
      if($('#sms_template_id').val() != ''){
        $('.link_open_sms_template').remove();
        $('#sms_template_id').parent().append('<a class="link_open_sms_template" target="_blank" href="/index.php?module=pe_smstemplate&return_module=pe_smstemplate&action=EditView&record='+ $('#sms_template_id').val()+'">Link SMS Template</a>');
      }
     
    }
  });

//icon select sms template
$('body').on('click','#button_select_sms_template',function(){
    var popupRequestData = {
      "call_back_function" : "setSMSTemplateReturn",
      "form_name" : "EditView",
      "field_to_name_array" : {
        "id" : 'sms_template_id',
        "name" :  'sms_template_name', 
        "body_c" :  'content_messager',
      }
    };
    var query = '';
    open_smstemplate_popup('pe_smstemplate', 800, 850, query , true, true, popupRequestData,'single',true);
})

//icon clear select sms template
$('body').on('click','#button_reset_sms_template',function(){
    $(document).find("#sms_template_id").val('');
    $(document).find("#sms_template_name").val('');
    $(document).find("#content_messager").val('');
})

  function open_smstemplate_popup(module_name, width, height, initial_filter, close_popup, hide_clear_button, popup_request_data, popup_mode, create, metadata) {
    if (typeof(popupCount) == "undefined" || popupCount == 0)
        popupCount = 1;
    window.document.popup_request_data = popup_request_data;
    window.document.close_popup = close_popup;
    width = (width == 600) ? 800 : width;
    height = (height == 400) ? 800 : height;
    URL = 'index.php?' +
        'module=' + module_name +
        '&action=Popup';
    if (initial_filter != '') {
        URL += '&query=true' + initial_filter;
        popupName = initial_filter.replace(/[^a-z_0-9]+/ig, '_');
        windowName = module_name + '_popup_window' + popupName;
    } else {
        windowName = module_name + '_popup_window' + popupCount;
    }
    popupCount++;
    if (hide_clear_button) {
        URL += '&hide_clear_button=true';
    }
    windowFeatures = 'width=' + width +
        ',height=' + height +
        ',resizable=1,scrollbars=1';
    if (popup_mode == '' || popup_mode == undefined) {
        popup_mode = 'single';
    }
    URL += '&mode=' + popup_mode;
    if (create == '' || create == undefined) {
        create = 'false';
    }
    URL += '&create=' + create;
    if (metadata != '' && metadata != undefined) {
        URL += '&metadata=' + metadata;
    }
    if (popup_request_data.jsonObject) {
        var request_data = popup_request_data.jsonObject;
    } else {
        var request_data = popup_request_data;
    }
    var field_to_name_array_url = '';
    if (request_data && request_data.field_to_name_array != undefined) {
        for (var key in request_data.field_to_name_array) {
            if (key.toLowerCase() != 'id') {
                field_to_name_array_url += '&field_to_name[]=' + encodeURIComponent(key.toLowerCase());
            }
        }
    }
    if (field_to_name_array_url) {
        URL += field_to_name_array_url;
    }
    win = SUGAR.util.openWindow(URL, windowName, windowFeatures);
    if (window.focus) {
        win.focus();
    }
    win.popupCount = popupCount;
    return win;
  }

// Event click icon sms get data
    $('body').on('click', '.sms_icon', function() {
    // VUT-S
        $.ajax({
            url: 'index.php?entryPoint=getSMSTemplatePopup',
            type: 'POST',
            // data: {
            //     action: 'read',
            // },
            success: function(data){
                render_select_sms(data);
            }
        });
    // VUT-E

        $.ajax({
            url: 'index.php?entryPoint=CRUD_SMS_Signture' ,
            type: 'POST',
            data: 
            {
                action: 'read',
                custom_action : 'get_sms_signture'
            },
            async: true,
            success: function(result) {                   
                render_select_sms_signture_dialog(result);
            }
        });
        $( "#dialog_send_sms" ).dialog("open");
        if(action_sugar_grp1 == 'index' && typeof(module_sugar_grp1) == 'undefined'){ // send from home
            module_send = $(this).parent().attr('current-module');
            record_id = $(this).parent().attr('record-id');
            user_name = $('#with-label').attr('title');
            name_customer = $(this).parent().parent().find('td[field="name"] a' ).text();
            phone_number_customer = $(this).parent().find('a').text();
            if(phone_number_customer ==''){
                // phone_number_customer = $(this).parent().find('td[field="phone"]').text();
                phone_number_customer = $(this).parent().text();
            };
        }
        else if(action_sugar_grp1 == 'index' && typeof(module_sugar_grp1) !== 'undefined'){ // send from list view
            module_send = module_sugar_grp1;
            record_id = $(this).parent().parent().find('input[name="mass[]"]').val();
            user_name = $(this).parent().parent().find('td[field="assigned_user_name"] a').text();
            name_customer = $(this).parent().parent().find('td[field="name"] a' ).text();
            phone_number_customer = $(this).parent().find('a').text();
            if(phone_number_customer ==''){
                // phone_number_customer = $(this).parent().find('td[type="phone"]').text();
                phone_number_customer = $(this).parent().text();
            };

        }else if(action_sugar_grp1 == 'DetailView'){ // send from detail view
            module_send = module_sugar_grp1;
            record_id = $('body').find('input[name="record"]').val();
            user_name = $('#assigned_user_id').text();
            if(module_send == 'Contacts' || module_send == 'Leads'){
                name_customer =  $('#full_name').text();
            }else {
                if(module_send == 'AOS_Quotes') {
                    name_customer =$('#account_name').text();
                }else if(module_send == 'AOS_Invoices'){
                    name_customer =$('#billing_account').text();
                }
                else{
                    name_customer =$('#name').text();
                }
            }
            if(name_customer == ''){
                name_customer = $('#first_name').text();
            }
            phone_number_customer = $(this).parent().find('div[type="phone"] a').text();
            //custom for module Calls
            if(phone_number_customer == '' && module_sugar_grp1 == 'Calls'){
                phone_number_customer = $(this).parent().find('input.phone').val();
            }

            if(phone_number_customer == ''){
                // phone_number_customer = $(this).parent().find('div[type="phone"]').text();
                phone_number_customer = $(this).parent().text();
            }
        }else if(action_sugar_grp1 == 'EditView'){ // send from edit view
            module_send = module_sugar_grp1;
            record_id = $('body').find('input[name="record"]').val();
            user_name = $('#assigned_user_name').val();
            if(module_send == 'Contacts' || module_send == 'Leads'){
                name_customer = $('#first_name').val();
            }else {
                name_customer =$('#name').val();
            }

            phone_number_customer = $(this).parent().find('input.phone').val();
        }else if(action_sugar_grp1 == 'UnifiedSearch'){ // send from search
            
            var href = $(this).parents().eq(2).find('td :first-child').attr('href');
            var array_href = href.split('&');
            $.each(array_href, function(key,value){
                var array_value = value.split('=');
                switch (array_value[0]) {
                    case 'module':
                        module_send = array_value[1];
                        break;
                    case 'record':
                        record_id = array_value[1];
                        break;
                    default:
                        break;
                }
                
            });

            user_name = '';
            name_customer = '';
            phone_number_customer = $(this).parent().find('a').text();
            if(phone_number_customer ==''){
                phone_number_customer = $(this).parent().text();
            };
        }
        name_customer = name_customer.trim();
        user_name = user_name.trim();
        var numberPattern = /\d+/g;
        phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '614');
        phone_number_customer = '+' + phone_number_customer;
        $('#phone_number_customer').text(phone_number_customer);
        $( "#content_messager" ).val('Hi '+ name_customer.split(" ")[0]);
        window.Object_Customer_SMS.phone_number = phone_number_customer;
        window.Object_Customer_SMS.first_name = name_customer.split(" ")[0];
    });

//VUT-S
    //Event change field + hidden in template SMS message 
    $('#message_template').change(function(){
            var content_messager_string = "";
            // var user_phone_number	= $('#from_phone_number').find('option:selected').val();
        $("#message_template option:selected").each(function() {
            var obj = window.data_message[$(this).val()];
            content_messager_string += obj.sms_content + " ";
            content_messager_string = content_messager_string.replace("$first_name",name_customer);
            content_messager_string = content_messager_string.replace("$assigned_user_first_name",user_name);
            // content_messager_string = content_messager_string.replace("[UserMobile]",user_phone_number);
        });
        $("#sms_message_dialog").val(content_messager_string);
        $("#sms_signture_dialog").val(window.data_sms_signture[$('#select_sms_signture_dialog').val()].content);
        $( "#content_messager" ).val($("#sms_message_dialog").val()+'\n'+$("#sms_signture_dialog").val());
        if( $('#message_template').val() != ''){
            $('.link_open_sms_template').remove();
            $('#message_template').parent().append('<a class="link_open_sms_template" target="_blank" href="/index.php?module=pe_smstemplate&return_module=pe_smstemplate&action=EditView&record='+  $('#message_template').val() +'">Link SMS Template</a>');
        }
    });
    $('#sms_template_name').parent().hide();
//VUT-E

//Event change field in template SMS message
	$('#messager_template').change(function(){
		 var content_messager_string = "";
		 var user_phone_number	= $('#from_phone_number').find('option:selected').val();
		$( "#messager_template option:selected" ).each(function() {
			content_messager_string += $( this ).text() + " ";
			content_messager_string = content_messager_string.replace("[FirstName]",name_customer);
			content_messager_string = content_messager_string.replace("[User]",user_name);
			content_messager_string = content_messager_string.replace("[UserMobile]",user_phone_number);
        });
        $("#sms_message_dialog").val(content_messager_string);
        $("#sms_signture_dialog").val(window.data_sms_signture[$('#select_sms_signture_dialog').val()].content);
		$( "#content_messager" ).val($("#sms_message_dialog").val()+'\n'+$("#sms_signture_dialog").val());
	})

    $('body').on('change','#select_sms_signture_dialog',function(){
        var sms_signture =  window.data_sms_signture[$(this).val()].content;
        var old_sms_signture = $("#sms_signture_dialog").val().trim();
        var full_current_content_messager = $("#content_messager").val().trim();
        var current_content_messager = full_current_content_messager.replace(old_sms_signture,'');
        $("#sms_signture_dialog").val(sms_signture);
        $("#sms_message_dialog").val(current_content_messager.trim());
        $("#content_messager").val($("#sms_message_dialog").val()+'\n'+$("#sms_signture_dialog").val());
    });

	//function send sms when click
	function click_send_sms(){
		var content_messager_string = $('#content_messager').val();
        var user_phone_number	= $('#from_phone_number').find('option:selected').val();
        var phone_number_customer = $("#phone_number_customer").text();
        var timestamp = $('#dataTimestamp').val();
        if(timestamp != '') {
           var status = 'schedule';
        } else {
            var status = 'sent';
        }
        phone_number_customer = phone_number_customer.trim();
        //  action send files
        
        // Read selected files
        var totalgroupfiles = $('input[name="sms_attachment[]"]').length;
        if(totalgroupfiles > 0) {
            var form_data = new FormData();
            for (var i = 0; i < totalgroupfiles; i++) {
                var totalfiles = $('input[name="sms_attachment[]"]')[i].files.length;
                for (var index = 0; index < totalfiles; index++) {
                   form_data.append("sms_files[]", $('input[name="sms_attachment[]"]')[i].files[index]);
                }
            } 
            var url_post_file = 'index.php?entryPoint=sendSMS&action=send_files'
            +'&timestamp='+encodeURIComponent(timestamp)
            +'&status='+encodeURIComponent(status)
            +'&record_id='+encodeURIComponent(record_id)
            +'&module='+encodeURIComponent(module_send)
            +'&from_phone_number='+encodeURIComponent(user_phone_number)
            +'&phone_number_customer='+encodeURIComponent(phone_number_customer);
            console.log(url_post_file);
            $.ajax({
                url: url_post_file,
                type: 'POST',
                data: form_data,
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success: function(data){
                    console.log('success!!!');
                }
            });
        }
   
        // dung code  - send by button Request Address SMS from Leads
        if((action_sugar_grp1 == 'EditView' || action_sugar_grp1 == 'DetailView') && typeof(action_sugar_grp1) !== 'undefined' 
        && typeof(module_sugar_grp1) !== 'undefined'&& module_sugar_grp1 == 'Leads' 
        && typeof($('#button_request_address_sms').val()) !== 'undefined'&& $('#button_request_address_sms').val() == 'button_request_address_sms') {
            module_send = 'Leads';
            record_id = $('body').find('input[name="record"]').val();
            var data_json = {
                'phone_number_customer':phone_number_customer,
                'from_phone_number':user_phone_number,
                'content_messager' :content_messager_string,
                'module': module_send,
                'record_id' : record_id,
                'button_send' : 'Request_address_sms',
                'timestamp': '',
                'status' : status
            };
            //dung code- update status lead after click Request Address SMS
            //if($('#status').val() == 'Assigned')$('#status').val("In Process");
            $('#status').val("Address_Requested")
        }else {
            var data_json = {
                'phone_number_customer':phone_number_customer,
                'from_phone_number':user_phone_number,
                'content_messager' :content_messager_string,
                'module': module_send,
                'record_id' : record_id,
                'timestamp' : timestamp,
                'status' : status
            };
        }

        if(phone_number_customer == ''){
            alert('Don\'t have phone number !');
        }else {
            $.ajax({
                url: 'index.php?entryPoint=sendSMS',
                type: 'POST',
                data: data_json,
                success: function(data){
                    console.log('success!!!');
                }
            });
        }		
    }
    
    $('body').on('click','.sms_getwaylink', function(){
        if(action_sugar_grp1 == 'index' && typeof(module_sugar_grp1) !== 'undefined'){
            var recordId = $(this).parent().parent().find('input[name="mass[]"]').val();
            var url = "http://message.pure-electric.com.au/search_message.php?crm_ref="+recordId ;
            window.open(url,'_blank');
        }
    });
    $(function(){
        $('#btnSendLater').click(function(){
        $('.popup-sendMail').toggleClass('active-popup-sms');
        })
        $("#datetimepicker").kendoDateTimePicker({
            format: "dd/MM/yyyy hh:mm tt"
        });
        var datepicker = $("#datetimepicker").data("kendoDatePicker");
        var setScheduleTime = function (date_schedule) {
           $("#datetimepicker").kendoDateTimePicker({
              format: "dd/MM/yyyy hh:mm tt",
              value : new Date(date_schedule)
              
           })
        };

        var defaultDateTime = function(date){
           var now     = date;
           var year    = now.getFullYear();
           var month   = now.getMonth()+1; 
           var day     = now.getDate();
           var hour    = now.getHours();
           var minute  = now.getMinutes();
           return {'year':year,'month':month,'day':day,'hour':hour,'minute':minute}
        }
        var getDateTime = function(option){
                var date_return = '';
                var date = new Date();
                switch(option){
                    case 'In 1 hour':
                        var data = defaultDateTime(new Date());
                        date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+(data['hour']+1)+':'+data['minute'];
                        break;
                    case 'In 2 hours':
                        var data = defaultDateTime(new Date());
                        date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+(data['hour']+2)+':'+data['minute'];; 
                        break;
                    case 'In 3 hours':
                        var data = defaultDateTime(new Date());
                        date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+(data['hour']+3)+':'+data['minute'];; 
                        break;
                    case 'In 2 days':
                        
                        var data = defaultDateTime(new Date(date.getTime() + 2*(24*60*60*1000)));
                        date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute']; 
                        break;
                    case 'In 4 days':
                        var data = defaultDateTime(new Date(date.getTime() + 4*(24*60*60*1000)));
                        date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute']; 
                        break;
                    case 'In 1 Week':
                        var data = defaultDateTime(new Date(date.getTime() + 7*(24*60*60*1000)));
                        date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute']; 
                        break;
                    case 'In 2 Weeks':
                        var data = defaultDateTime(new Date(date.getTime() + 14*(24*60*60*1000)));
                        date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute']; 
                        break;
                    case 'In 1 Month':
                        var data = defaultDateTime(new Date(date.getFullYear(), date.getMonth()+1, date.getDate()));
                        date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute'];
                        break;
                }
            return date_return;
        }

        $(".listOption").find('li').click(function(){
           var option = $(this).html();
           setScheduleTime(getDateTime(option));
        });
        $('.btnSuccess').click(function() {

        var datetimepicker = $("#datetimepicker").data("kendoDateTimePicker");
        var datepicker = datetimepicker.value();
        var datestring = kendo.toString(datepicker, "dd/MM/yyyy h:mm tt");
        var today = new Date();
            if (toTimestamp(datepicker) < toTimestamp(today)) {
                $('#datetimepicker').css('color','red');
            } else {
                $('#datetimepicker').css('color','green');
                $('#dataTimestamp').val(toTimestamp(datepicker));
                $('.popup-sendMail').removeClass('active-popup-sms');
                $('.result-schedule').text(datestring);
            }
        })
        function toTimestamp(strDate){
            var datum = strDate.getTime()/1000;
            return datum;
        }
    });

})
function setSMSTemplateReturn(popupReplyData){
  if(typeof popupReplyData.serials !== 'undefined'){
    for (var key in popupReplyData.serials){
      $("#"+key).val(popupReplyData.serials[key]);
    }
  } else {
    $(document).find("#sms_template_id").val(popupReplyData.name_to_value_array.sms_template_id);
    $(document).find("#sms_template_name").val(popupReplyData.name_to_value_array.sms_template_name);
    $.post('index.php?entryPoint=SMSTemplateData', {
        smsTemplateId: popupReplyData.name_to_value_array.sms_template_id
      }, function (jsonResponse) {
        
        var response = JSON.parse(jsonResponse);
        console.log(response);
        var content_sms = response.data.body_from_html.replace("$first_name",window.Object_Customer_SMS.first_name);
        $('body').find('#content_messager').val(content_sms);      
        if($('#sms_template_id').val() != ''){
          $('.link_open_sms_template').remove();
          $('#sms_template_id').parent().append('<a class="link_open_sms_template" target="_blank" href="/index.php?module=pe_smstemplate&return_module=pe_smstemplate&action=EditView&record='+ $('#sms_template_id').val()+'">Link SMS Template</a>');
        }
    });
  }
  
}

//VUT-Render data SMS template 
function render_select_sms(result) {
    var data_result = $.parseJSON(result);
    if(typeof(window.data_message) == 'undefined') {
        window.data_message = data_result;
    }
    /**Popup */
    $('#message_template').empty();
    $('#message_template').append($('<option>', {
        value: '',
        text: ''
    }));

    $.each(data_result, function(key,value){
        $('#message_template').append($('<option>', {
            value: key,
            text: value.name
        }));
    });
}

function render_select_sms_signture_dialog(result){
    var data_result = $.parseJSON(result);
    var id_current_sms_signture = '';
    window.data_sms_signture = data_result;
    $('#select_sms_signture_dialog').empty();
 
    $.each(data_result,function(k,v){
        $('#select_sms_signture_dialog').append($('<option>', {
            value: k,
            text: v.title
        }));
        if(trim($("#sms_signture_dialog").val()) == trim(v.content)){
          id_current_sms_signture = k;
        }
    });
    if(id_current_sms_signture != ''){
      $('#select_sms_signture_dialog').val(id_current_sms_signture);
    }
};


$.fn.copy_email_address = function (element){
    var emailAddress = $(element).attr('data-email-address');
    var dummy = document.createElement("input");
    document.body.appendChild(dummy);
    dummy.setAttribute('value', emailAddress);
    dummy.select();
    document.execCommand('copy');
    document.body.removeChild(dummy);
    $(element).find('span.tooltiptext').show();
    var element = $(element);
    setTimeout(function() {
        element.find('span.tooltiptext').hide();
    },600);
}
