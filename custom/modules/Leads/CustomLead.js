(function ($) {
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id = $("input[name='record']").val();

        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1&seek_install_date=1&' + ((record_id!="")? ("&lead_id="+record_id):""),
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();

            // Populate fields
            if ($(source).attr('data-record-id') !== '') {
            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');

            // var solar_install_contact = {'VIC': 'Sofia Younan <sofia.younan@solargain.com.au>',
            // 'SA': 'Malinda King <malinda.king@solargain.com.au>',
            // 'ACT': 'Malinda King <malinda.king@solargain.com.au>',
            // 'NSW': 'Malinda King <malinda.king@solargain.com.au>',
            // 'WA': ' Malinda King <malinda.king@solargain.com.au>',
            // 'QLD': 'Rebecca Rodgers <Rebecca.Rodgers@solargain.com.au>',
            //  }

            //dung code - change address email new
            var solar_install_contact = {'VIC': 'vic.installs@solargain.com.au', // update 13/12/18 -change address email for state VIC
            'SA': "sg.wa.installadmin@solargain.com.au",
            'ACT': "sg.wa.installadmin@solargain.com.au",
            'NSW': "sg.wa.installadmin@solargain.com.au",
            'WA': "sg.wa.installadmin@solargain.com.au",
            'QLD': 'Rebecca Rodgers <Rebecca.Rodgers@solargain.com.au>',
             }

            var populateModuleName = $(source).attr('data-module-name');
            var populateEmailAddress = $(source).attr('data-email-address');

            /*if (populateModuleName !== '') {
                populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            }*/
            var primary_address_state = $("#primary_address_state").val().toUpperCase();
            if(primary_address_state !== '') {
                switch (primary_address_state) {
                    case 'VICTORIA':
                        primary_address_state = 'VIC';
                        break;
                    case 'QUEENSLAND':
                        primary_address_state = 'QLD';
                        break;
                    case 'NEW SOUTH WALES':
                        primary_address_state = 'NSW' ;
                        break;
                    case 'AUSTRALIAN CAPITAL TERRITORY':
                        primary_address_state = 'ACT';
                        break;
                    case 'SOUTH AUSTRALIA':
                        primary_address_state = 'SA';
                        break;
                }
              
            }

            var populateEmailAddress = solar_install_contact[primary_address_state];
            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
            //dung code --- input hidden type seek-install-date-from-leads
           
            var record_id = $("input[name='record']").val();
            var html_checkbox_Convert_Solar_Opportunity = 
            '<div class="col-xs-12 col-sm-12 edit-view-row-item hidden ">'
            + '<div class="col-xs-12 col-sm-2 label" data-label="">'
            + 'Seek_Install_Date_From_Leads_Check:</div>'
            + '<div class="col-xs-12 col-sm-8 edit-view-field " type="bool" field="send_sms" colspan="3">'
            + '<input type="text" checked id="Seek_Install_Date_From_Leads_Check" name="Seek_Install_Date_From_Leads_Check" value="'+record_id +'" title="" tabindex="0">'                              
            +'</div>'
            +'</div>';
            $(self.emailComposeView).find('#EditView_tabs .tab-content .edit-view-row').append(html_checkbox_Convert_Solar_Opportunity);
            
            var phone_number_customer = $('#phone_mobile').val();
            phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
            $('#number_client').val(phone_number_customer);

            $('#sms_message').after("<br><button class='button' type='button' id='get_mess' >Get SMS</button>");
            $("#get_mess").click(function(){
                //thienpb code update sms message from custom changes to the email.
                $('#sms_message').val('');
                var sms_body = '';
                var sms_body_split = '';
                    var sms_body = $('#ComposeView').find('textarea#description').val();
                    sms_body_split = sms_body.split('Regards');
                    if(sms_body_split.length > 1 ){
                        sms_body =  sms_body_split[0].trim();
                    }else{
                        sms_body = sms_body.split('Thanks in advance');
                        sms_body =  sms_body[0].trim();
                    }
                    $("#sms_message").val(sms_body.replace(', ',', \n').replace('? ','? \n').replace('Customer Name','\nCustomer  Name').replace('Customer Address','\nCustomer Address').replace('Network','\nNetwork').replace('Notes','\nNotes'));
            //end
            })
            if(phone_number_customer != ''){
                //$('#send_sms').attr('checked',true);
                $('#number_receive_sms').val('matthew_paul_client');
            }

            }
            $(self.emailComposeView).on('sentEmail', function (event, composeView) {
            composeBox.hide();
            composeBox.remove();
            });
            $(self.emailComposeView).on('disregardDraft', function (event, composeView) {
            if (typeof messageBox !== "undefined") {
                var mb = messageBox({size: 'lg'});
                mb.setTitle(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
                mb.setBody(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
                mb.on('ok', function () {
                mb.remove();
                composeBox.hide();
                composeBox.remove();
                });
                mb.on('cancel', function () {
                mb.remove();
                });
                mb.show();
            } else {
                if (confirm(self.translatedErrorMessage)) {
                composeBox.hide();
                composeBox.remove();
                }
            }
            });


            composeBox.on('cancel', function () {
            composeBox.remove();
            });
            composeBox.on('hide.bs.modal', function () {
            composeBox.remove();
            });
        }).fail(function (data) {
            composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
        });
        return $(self);
    };
}(jQuery));


//dung code - button Seek Details
(function ($) {
    $.fn.openComposeViewModal_seekDetails = function (source) {
        "use strict";
       
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id = $("input[name='record']").val();

        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1&seek_details=1'
                + '&nmi_c=' + $('#nmi_c').val()
                + '&account_number_c=' + $('#account_number_c').val()
                + '&energy_retailer_c=' + $('#energy_retailer_c').val()
                + '&name_on_billing_account_c='+ $('#name_on_billing_account_c').val()
                + '&first_name=' +$('#first_name').val()
                + ((record_id!="")? ("&lead_id="+record_id):""),
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();

            // Populate fields
            if ($(source).attr('data-record-id') !== '') {
            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');

            var populateModuleName = $(source).attr('data-module-name');
            var populateEmailAddress = $(source).attr('data-email-address');

            var populateEmailAddress = $("#Leads0emailAddress0").val();
            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);

            }
            $(self.emailComposeView).on('sentEmail', function (event, composeView) {
            composeBox.hide();
            composeBox.remove();
            });
            $(self.emailComposeView).on('disregardDraft', function (event, composeView) {
            if (typeof messageBox !== "undefined") {
                var mb = messageBox({size: 'lg'});
                mb.setTitle(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
                mb.setBody(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
                mb.on('ok', function () {
                mb.remove();
                composeBox.hide();
                composeBox.remove();
                });
                mb.on('cancel', function () {
                mb.remove();
                });
                mb.show();
            } else {
                if (confirm(self.translatedErrorMessage)) {
                composeBox.hide();
                composeBox.remove();
                }
            }
            });
            composeBox.on('cancel', function () {
            composeBox.remove();
            });
            composeBox.on('hide.bs.modal', function () {
            composeBox.remove();
            });
        }).fail(function (data) {
            composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
        });
        return $(self);
    };
}(jQuery));

//dung code - button popup email request address
(function ($) {
    $.fn.openComposeViewModal_sendRequestAddressSMS = function (source) {
        "use strict";
       

        //Dung code
        if($('#Leads0emailAddress0').val() == '' || $('#email_send_status_c').val() == 'sent'){
            $( "#dialog_send_sms" ).dialog("open");
            var module_send = 'Leads';
            var record_id = $('body').find('input[name="record"]').val().trim();
            var user_name = $('#assigned_user_name').val().trim();
            user_name = user_name.split(" ");
            user_name = user_name[0];
            var name_customer = $('#first_name').val();
            var phone_number_customer = $('#phone_mobile').val();
            var numberPattern = /\d+/g;
            phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
            $('#phone_number_customer').text(phone_number_customer);
            $('#phone_number_customer').append('<input type="text" hidden name="button_request_address_sms" id="button_request_address_sms" size="30" maxlength="100" value="button_request_address_sms" title="">');
            var address_content_messager = '';
            $('#primary_address_city').val() !== ''? address_content_messager +=  ' ' +$('#primary_address_city').val().trim():address_content_messager;
            $('#primary_address_state').val() !== ''? address_content_messager += ' ' +$('#primary_address_state').val().trim():address_content_messager;
            $('#primary_address_postalcode').val() !== ''? address_content_messager += ' '+$('#primary_address_postalcode').val().trim():address_content_messager;
            //var content_messager_send_request_address_SMS = 'Hi '+ name_customer +', my name is Paul from PureElectric a strategic Solargain partner. I received your request for a Solargain solar quote for your place, I have that you are in'+ address_content_messager +'? If you could please reply back with your street address I would be more than happy to assist. You can reply back via SMS to this number, or email me paul.szuster@pure-electric.com.au or give me a call 0423 494 949. Look forward to your response. Regards, Paul.' ;
            var content_messager_send_request_address_SMS = 'Hi '+ name_customer +', my name is '+user_name+' from PureElectric a strategic Solargain partner. I received your request for a Solargain solar quote for your place, I have that you are in'+ address_content_messager +'? If you could please reply back with your street address I would be more than happy to assist. You can reply back via SMS to this number, or email me '+((user_name=="Matthew")?"matthew.wright@pure-electric.com.au":"paul.szuster@pure-electric.com.au")+' or give me a call '+((user_name=="Matthew")?"0421 616 733":"0423 494 949")+'. Look forward to your response. Regards, '+user_name+'.' ;
            $( "#content_messager" ).val(content_messager_send_request_address_SMS);   
            
            // set sedning number 
            var sending_number = (user_name=="Matthew")?"+61421616733":"+61490942067";
            $("#number_send_sms").val(sending_number);
            return false;  
        }
        //End dung code
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id = $("input[name='record']").val();
        // sms_received
        var sms_received = "";
        if($('#phone_mobile').val() !== '') sms_received = $('#phone_mobile').val();
        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1&email_type=street_address_request_email' + ((record_id!="")? ("&lead_id="+record_id):"") + ((sms_received!="")? ("&sms_received="+sms_received):""),
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();
            //dung code - display template request address sms
            var user_name = $('#assigned_user_name').val().trim();
            user_name = user_name.split(" ");
            user_name = user_name[0];

            var name_customer = $('#first_name').val();

            var phone_number_customer = $('#phone_mobile').val();

            phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
            $('#number_client').val(phone_number_customer);  
              
            var address_content_messager = '';
            $('#primary_address_city').val() !== ''? address_content_messager +=  ' ' +$('#primary_address_city').val().trim():address_content_messager;
            $('#primary_address_state').val() !== ''? address_content_messager += ' ' +$('#primary_address_state').val().trim():address_content_messager;
            $('#primary_address_postalcode').val() !== ''? address_content_messager += ' '+$('#primary_address_postalcode').val().trim():address_content_messager;
            //var content_messager_send_request_address_SMS = 'Hi '+ name_customer +', my name is Paul from PureElectric a strategic Solargain partner. I received your request for a Solargain solar quote for your place, I have that you are in'+ address_content_messager +'? If you could please reply back with your street address I would be more than happy to assist. You can reply back via SMS to this number, or email me paul.szuster@pure-electric.com.au or give me a call 0423 494 949. Look forward to your response. Regards, Paul.' ;
            var content_messager_send_request_address_SMS = 'Hi '+ name_customer +', my name is '+user_name+' from PureElectric a strategic Solargain partner. I received your request for a Solargain solar quote for your place, I have that you are in'+ address_content_messager +'? If you could please reply back with your street address I would be more than happy to assist. You can reply back via SMS to this number, or email me '+((user_name=="Matthew")?"matthew.wright@pure-electric.com.au":"paul.szuster@pure-electric.com.au")+' or give me a call '+((user_name=="Matthew")?"0421 616 733":"0423 494 949")+'. Look forward to your response. Regards, '+user_name+'.' ;
            
            $('#sms_message').val(content_messager_send_request_address_SMS);
            
            var sending_number = (user_name=="Matthew")?"+61421616733":"+61490942067";
             $("#number_send_sms").val(sending_number);

            // Populate fields
            if ($(source).attr('data-record-id') !== '') {
            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');

            var populateModuleName = $(source).attr('data-module-name');
            var populateEmailAddress = $(source).attr('data-email-address');

            var populateEmailAddress = $("#Leads0emailAddress0").val();
            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);

            }
            $(self.emailComposeView).on('sentEmail', function (event, composeView) {
            composeBox.hide();
            composeBox.remove();
            });
            $(self.emailComposeView).on('disregardDraft', function (event, composeView) {
            if (typeof messageBox !== "undefined") {
                var mb = messageBox({size: 'lg'});
                mb.setTitle(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
                mb.setBody(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
                mb.on('ok', function () {
                mb.remove();
                composeBox.hide();
                composeBox.remove();
                });
                mb.on('cancel', function () {
                mb.remove();
                });
                mb.show();
            } else {
                if (confirm(self.translatedErrorMessage)) {
                composeBox.hide();
                composeBox.remove();
                }
            }
            });
            composeBox.on('cancel', function () {
            composeBox.remove();
            });
            composeBox.on('hide.bs.modal', function () {
            composeBox.remove();
            });
        }).fail(function (data) {
            composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
        });
        return $(self);
    };
}(jQuery));

function sendEmailToAdmin() {

    if ($("#primary_address_street").val() == '' ) {
        alert("Cannot send email No Street address entered");
        return;
    }
    //dung code - check button send Request Design can't send when email Request Design sent
    if($('#time_request_design_c').val() !== ''){
        alert("You can't send email because it has sent.");
        return;
    }

    // Thienpb code  - save and send Request Design
    var lead_id = '';
    if($('input[name="record"]').val() == ''){
        var ok = confirm('Lead is not saved! Do you want Save and send Request Designs?');
        if(ok ==  true){
            $('#sendMailToAdmin span.glyphicon-refresh').removeClass('hidden');
            $("#EditView input[name='action']").val('Save');
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                async:false,
                success: function (data) {
                    var lead_id_patt = /"record" value="(.*)"/g;
                    lead_id = lead_id_patt.exec(data);
                    if(lead_id !== null && typeof lead_id === 'object'){
                        if(lead_id[1] !='')  {
                            lead_id = lead_id[1]
                        }
                    }
                }
            });
        }else{
            return;
        }
    }else{
        $('#sendMailToAdmin span.glyphicon-refresh').removeClass('hidden');
        lead_id = $("#EditView input[name='record']").val();
    }
    var _url = "?entryPoint=customLeadSendEmailToAdmin&record_id="
                + lead_id
                + "&primary_address_street=" + $("#primary_address_street").val()
                + "&primary_address_city=" + $("#primary_address_city").val()
                + "&primary_address_state=" + $("#primary_address_state").val()
                + "&primary_address_postalcode=" + $("#primary_address_postalcode").val();

    $.ajax({
        url: _url,
        type: 'GET',

    }).done(function (data) {
        $('#sendMailToAdmin span.glyphicon-refresh').addClass('hidden');
      
        //dung code - display time click "Request Design"
        var value = data.split(' ');

        var date = value[0];
        $('#time_request_design_c_date').val(date);

        var times = value[1].split(':');
        $('#time_request_design_c_hours').val(times[0]);

        var minutes = times[1] - (times[1] % 15);
        if (minutes == 0)
        {
            minutes = '00';
        }

        $('#time_request_design_c_minutes').val(minutes);

        $('#time_request_design_c').val(date + ' ' + times[0] + ':' + minutes);
        //dung code- update status lead after click request design]
        if($('#status').val() == 'Assigned' || $('#status').val() == 'New'){
            $('#status').val("In Process");
        }

    });
}
 //dung code
function sendEmailDesignsComplete() {
    //dung code - alert confirm
    var dialog_message = "<ul style='list-style-type: circle; padding: 0 40px;'>";
    if($('#time_completed_job_c').val() !== '') 
        dialog_message += "<li>We sent Designs Complete email to assigned user before. Do you want to resend a new Designs Complete email to assigned user again?</li>";
    if($("#do_not_email_c").is(':checked') == false)
        dialog_message += '<li>"Do Not Send Design Complete Email To Client" checkbox is not checked. Do you want to send design to client?</li>';
    if(($("#time_sent_to_client_c").val() != "") && ($("#do_not_email_c").is(':checked') == false))
        dialog_message += '<li>"We also sent design to client before. Do you want to send design to client again?</li>';
    //dung code - check suggest_price field
    var select_option = $("#solargain_options_c").val();
    var html_option = '<li>Pricing Options ';
    var check_empty_suggest_price = false;
    select_option.forEach(function(element){
        element++;
        if($("#suggest_price_"+(element+1)).val() == ''){
            html_option = html_option + element +',';
            check_empty_suggest_price = true;
        } 
    });
    html_option +=' is NOT FILLED IN! Are you sure you want to continue?</li>';
    if(check_empty_suggest_price){
        dialog_message += html_option; 
    }
    dialog_message += "</ul>";
    if(dialog_message !== "<ul style='list-style-type: circle; padding: 0 40px;'></ul>") {
        var dialog = $(dialog_message).dialog({
            buttons: {
                "Yes": function() { 
                    send_email_design_complete(); dialog.dialog('close');
                },
                "Cancel":  function() {
                    dialog.dialog('close');
                    return;
                }
            }
            });
    } else {
        send_email_design_complete();
    }

    /*
    if($("#do_not_email_c").is(':checked') == false){
            var dialog3 = $('<p>"Do Not Send Design Complete Email To Client" checkbox is not checked. Do you want to send design to client?</p>').dialog({
                buttons: {
                    "Yes": function() { 
                        if($("#time_sent_to_client_c").val() != ""){
                            var dialog2 = $('<p>"We also sent design to client before. Do you want to send design to client again?</p>').dialog({
                                buttons: {
                                    "Yes": function() { 
                                        dialog2.dialog('close');
                                    },
                                    "Cancel":  function() {
                                        dialog2.dialog('close');
                                        return;
                                    }
                                }
                            });
                        }
                        dialog3.dialog('close');
                    },
                    "Cancel":  function() {
                        dialog3.dialog('close');
                        return;
                    }
                }
            });
        }
        */
    function send_email_design_complete() {        

        $('#SAVE').prop('disabled', true);
        $('#save_and_edit').prop('disabled', true);
    
        $('#sendDesignsComplete span.glyphicon-refresh').removeClass('hidden');
    
        var _url = "?entryPoint=customLeadSendDesignsComplete&record_id="
        + $('input[name="record"]').val()
        + "&assigned_user_id=" + $('input[name="assigned_user_id"]').val()
        + "&assigned_user_name=" + $('input[name="assigned_user_name"]').val()
        + "&primary_address_street=" + $("#primary_address_street").val()
        + "&primary_address_city=" + $("#primary_address_city").val()
        + "&primary_address_state=" + $("#primary_address_state").val()
        + "&primary_address_postalcode=" + $("#primary_address_postalcode").val();
    
        $.ajax({
            url: _url,
            type: 'GET'
        }).done(function (data) {

            var jsonObject = $.parseJSON(data);

            var vals = jsonObject.time_complete.split(' ');
    
            var date = vals[0];
            $('#time_completed_job_c_date').val(date);
    
            var times = vals[1].split(':');
    
            var hours = times[0];
            if (hours < 10)
            {
                hours = '0' + hours;
            }
            $('#time_completed_job_c_hours').val(hours);
    
            var minutes = times[1] - (times[1] % 15);
            if (minutes == 0)
            {
                minutes = '00';
            }
            $('#time_completed_job_c_minutes').val(minutes);
    
            $('#time_completed_job_c').val(date + ' ' + hours + ':' + minutes);
            //
            // Time send client
            if(jsonObject.time_sent_client !== "undefined" && jsonObject.time_sent_client != ""){
                var vals = jsonObject.time_sent_client.split(' ');
        
                var date = vals[0];
                $('#time_sent_to_client_c_date').val(date);
        
                var times = vals[1].split(':');
        
                var hours = times[0];
                if (hours < 10)
                {
                    hours = '0' + hours;
                }
                $('#time_sent_to_client_c_hours').val(hours);
        
                var minutes = times[1] - (times[1] % 15);
                if (minutes == 0)
                {
                    minutes = '00';
                }
                $('#time_sent_to_client_c_minutes').val(minutes);
        
                $('#time_sent_to_client_c').val(date + ' ' + hours + ':' + minutes);
            }
            $('#sendDesignsComplete span.glyphicon-refresh').addClass('hidden');
    
            $('#SAVE').prop('disabled', false);
            $('#save_and_edit').prop('disabled', false);
        });
    
        // dung code - ajax download file pdf from solargain
        if($('#solargain_quote_number_c').val() !== ''){
            var url_download = "?entryPoint=CustomDownloadPDF&record_id="
            + $('input[name="record"]').val()
            + '&quote_solorgain='+$('#solargain_quote_number_c').val();
            $.ajax({
                url: url_download,
                async: false,
                type: 'GET',
                success: function(data){
                    console.log(data);
                }
            })
        }else{
            alert('Not have number quote solargain!');
        }
    }
}

function addOpenMapView() {
    //tu-code add Near Map
    var address = $("#primary_address_street").val()+','+$("#primary_address_city").val()+','+$("#primary_address_state").val()+','+$("#primary_address_postalcode").val();     
    // $("#primary_address_country").after("<br><div>"+open_map+"</div>");
    var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&q=' + $("#primary_address_street").val() +", " + $("#primary_address_city").val() + ", " + $("#primary_address_state").val() + ", " + $("#primary_address_postalcode").val(); //26 Walsh Ave, St Marys SA 5042, Úc;
    // $("#primary_address_country").after('<br><a id="open_map" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a>');
    // tuan code
    $("#primary_address_street_label label").after(
        '<a style="float: right;cursor:pointer;" id="open_map_billing_leads" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
    $("#primary_address_street").before( 
        '<div style="background-color: white;display:none;border:1px solid;position:absolute;padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_billing_leads" class="show-open-map hide_map">'+
            '<ul>'+
            '<li><a id="open_map" target="_blank" class="various fancybox.iframe" href="' + urlMap + '">Google Maps</a></li>'+
            '<li><a style="cursor:pointer;" target="_blank" href="http://maps.nearmap.com?addr='+ address+'&z=22&t=roadmap">Near Map</a></li>'+
            '<li><a style="cursor:pointer;" id="link_realestate_billing">Realestate</a></li>'+
            '</ul>'+
        '</div>'
        );
    $("#link_realestate_billing").click(function(){
        SUGAR.ajaxUI.showLoadingPanel();
        $.ajax({
            url: "?entryPoint=getLinkRealestate",
            type: 'POST',
            data: {
                street    : $("#primary_address_street").val(),
                city      : $("#primary_address_city").val(),
                state     : $("#primary_address_state").val(),
                postcode  : $("#primary_address_postalcode").val(),
            },
            success: function(data){
                if(data !== 'Not Find Address On Realestate'){
                    window.open(data,'_blank');
                }else {
                    alert(data);
                }
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        })
    })
    $('#open_map_billing_leads').click(function(){
            $('#open_map_popup_billing_leads').fadeToggle()
    })
     // tuan css checkbox
    // $('#detailpanel_-1 input[type="checkbox"]').css({"border-style":"solid",'border-width': '2px','height': '20px','width': '20px'})
    // $('#detailpanel_1 input[type="checkbox"]').css({"border-style":"solid",'border-width': '2px','height': '20px','width': '20px'})
    // $('#detailpanel_2 input[type="checkbox"]').css({"border-style":"solid",'border-width': '2px','height': '20px','width': '20px'})
    $(".various").fancybox({
        maxWidth	: 800,
        maxHeight	: 600,
        fitToView	: false,
        width		: '70%',
        height		: '70%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });

    $.ajax({
        url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + $("#primary_address_street").val() +", " + $("#primary_address_city").val() + ", " + $("#primary_address_state").val() + ", " + $("#primary_address_postalcode").val() + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
        type: 'GET',
        success: function(result) {
            if (result.status != "OK")
                return;

            var location = result.results[0].geometry.location;

            $.ajax({
                url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&size=600x300&location=' + location.lat + ',' + location.lng,
                type: 'GET',
                success: function(result) {
                    //console.log(result.status);
                    if (result.status == "OK") {
                        var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                        $("#open_map").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                    }
                    else {
                        var astorPlace = new google.maps.LatLng(location.lat, location.lng);
                        var webService = new google.maps.StreetViewService();
                        var checkaround = 500;

                        webService.getPanoramaByLocation(astorPlace, checkaround, checkNearestStreetView);

                        function checkNearestStreetView(panoData){
                            if(panoData) {
                                 if(panoData.location) {
                                    if(panoData.location.latLng) {
                                        var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&pano=' + panoData.location.pano;
                                        $("#open_map").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                                    }
                                }
                            }
                        }
                    }
                }
            });
        }
    });
}
    

$(function () {
    'use strict';
    //hidden salutation
    $("#salutation").hide();
    // Generate uinique id
    //VUT - S - Get distance for Installer
    $('#distance_plumber').closest('.edit-view-row-item').prepend('<div><button class="button primary" type="button" id="getDistance_all"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get All Distance </button><button class="button primary" id="getDistance_selected"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Distance Selected </button></div>');
    $(document).on('focus', '#proposed_plumber_acccount, #proposed_electrician_acccount, #proposed_daikin_installer_acccount, #proposed_solar_installer_acccount', function () {
        window.accountFocus = $(this).siblings('input[type=hidden]');
    });
    //VUT - get distance
    $(document).on('click', '#getDistance_selected', function (e) {
        if (typeof window.accountFocus === "undefined") return false;
        $('#getDistance_selected span.glyphicon-refresh').removeClass('hidden');
        let accFocus = window.accountFocus;
        let accFocus_idElement = accFocus.attr('id');
        let account_id = $(`#${accFocus_idElement}`).val();
        let field_distance_idElement;
        let distance_selected = get_distance_by_account_id(account_id);
        switch (accFocus_idElement) {
            case 'proposed_plumber_acccount_id': 
                field_distance_idElement = 'distance_plumber';
                break;
            case 'proposed_electrician_acccount_id': 
                field_distance_idElement = 'distance_electrician';
                break;
            case 'proposed_daikin_installer_acccount_id':
                field_distance_idElement = 'distance_daikin_installer';
                break;
            case 'proposed_solar_installer_acccount_id':
                field_distance_idElement = 'distance_to_sg_c';
                break;
            default:
                break;
        }
        if (typeof (distance_selected) == 'string') {
            $("#" + field_distance_idElement).val(`${distance_selected}`);
        } else {
            $("#" + field_distance_idElement).val(`${distance_selected} km`);
        }
        $('#getDistance_selected span.glyphicon-refresh').addClass('hidden');
        return false;
    });

    //VUT - get all distance
    $(document).on("click", "#getDistance_all", function () {
        var from_address = $("#site_detail_addr__c").val() + ", " +
            $("#site_detail_addr__city_c").val() + ", " +
            $("#site_detail_addr__state_c").val() + ", " +
            $("#site_detail_addr__postalcode_c").val();
        SUGAR.ajaxUI.showLoadingPanel();
        getDistances(from_address);
    });

    
    //VUT - E - Get distance for Installer

    //tuan code css 
    // $('div[field="product_type_c"]').css('height','85px');
    $('div[field="product_type_c"]').parent().css('margin-bottom','50px');
    //dung code - button push quote date to solargain
    //$('#quote_date_c').parent().parent().append('<br> <button class="button primary" id="pushQuoteDateToSG"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Push to SG</button>');
    $("#pushQuoteDateToSG").click(function(){
        if($("#quote_date_c").val() == '') {
            alert('Not value Quote Date!');
            return false;
        }
        $('#pushQuoteDateToSG span.glyphicon-refresh').removeClass('hidden');
        var build_url_quote = "?entryPoint=customUpdateQuoteToSolarGain";
        build_url_quote += '&record='+ encodeURIComponent($('input[name="record"]').val());
        build_url_quote += "&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
        build_url_quote += "&quoteDate="+encodeURIComponent($("#quote_date_c").val());
        $.ajax({
            url: build_url_quote,
            type : 'POST',
            success: function (data) {
                $('#pushQuoteDateToSG span.glyphicon-refresh').addClass('hidden');
            },
        });
        return false;
    });

    $('#quote_date_c').parent().append('<br> <button class="button primary" id="generateTodayDate"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Generate Today</button>');
    $("#generateTodayDate").click(function(){
        var currentdate = new Date();
        var datetime = currentdate.getDate() + "/" + (currentdate.getMonth()+1) + "/" + currentdate.getFullYear() ;//+ " " + currentdate.getHours() + ":" + currentdate.getMinutes();
        $('#quote_date_c_date').val(datetime);
        $('#quote_date_c_date').trigger('change');
        return false;
    });

    $('#distance_to_sg_c').parent().siblings('.label').append('<br> <button class="button primary" id="getDistance"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Distance </button>');
    $('#getDistance').click(function (){
        var from_address =  $("#primary_address_street").val() +", " +
                            $("#primary_address_city").val() + ", " +
                            $("#primary_address_state").val() + ", " +
                            $("#primary_address_postalcode").val();

        /*var solargain_address = ["10 Milly Court, Malaga WA 6090",
        "963/1002 Grand Junction Road, Holden Hill SA 5088",
        "Unit 2, 7 Beale Way, Rockingham WA 6168",
        "Unit 7, 88 Dynon Road, West Melbourne VIC 3003",
        "Unit 1, 5-7 Imboon Street, Deception Bay QLD 4508",
        "21C Richmond Road, Homebush NSW 2140",
        "244 Fitzgerald Street, Northam WA 6401",
        "117 Lockyer Avenue, Albany WA 6330",
        "Unit 2, 18 Bourke Street, Bunbury WA 6230",
        "25 Wright Street, Busselton WA 6280",
        "Lot 10 Reg Clarke Road, Geraldton WA 6530",
        "23-49 Parfitt Road, Wangaratta VIC 3676",
        "Shed 16B, 22 Walsh Road, Warrnambool VIC 3280",
        "Unit 7, 8-10 Boat Harbour Drive, Pialba QLD 4655",
        "14 Ipswich St, Fyshwick ACT 2609"];*/
        var solargain_address = {
            "2":"Unit 7, 88 Dynon Road, West Melbourne VIC 3003",
            "14":"963/1002 Grand Junction Road, Holden Hill SA 5088",
            "0":"10 Milly Court, Malaga WA 6090",
            "1":"Unit 2, 7 Beale Way, Rockingham WA 6168",
            "3":"Unit 1, 5-7 Imboon Street, Deception Bay QLD 4508",
            "4":"21C Richmond Road, Homebush NSW 2140",
            "5":"244 Fitzgerald Street, Northam WA 6401",
            "6":"117 Lockyer Avenue, Albany WA 6330",
            "7":"Unit 2, 18 Bourke Street, Bunbury WA 6230",
            "8":"25 Wright Street, Busselton WA 6280",
            "9":"Lot 10 Reg Clarke Road, Geraldton WA 6530",
            "10":"23-49 Parfitt Road, Wangaratta VIC 3676",
            "11":"Shed 16B, 22 Walsh Road, Warrnambool VIC 3280",
            "12":"Unit 7, 8-10 Boat Harbour Drive, Pialba QLD 4655",
            "13":"14 Ipswich St, Fyshwick ACT 2609"
        }


        var selected_offices = $("#solargain_offices_c").val();

        var to_address = solargain_address[selected_offices];//"7/88 Dynon Road, West Melbourne VIC 3003";
        var suggest_address = [];
        for (var key in solargain_address) {
            if(/*solargain_address[key].indexOf($("#primary_address_state").val()) != -1*/ true){
                suggest_address.push(solargain_address[key]);
            }
        };


        $("#distance_to_sg_c").after("<b class='suggest'> Suggest: </b><br/>");
        $("#distance_to_sg_c").after("<b class='shortest'> Shortest: </b><br/><b class='shortest-suggest'></b>");
        // $.ajax({
        //     url: "/index.php?entryPoint=customDistance&address_from=" + from_address + "&address_to=" + to_address,
        //     type: 'GET',

        //     success: function(data)
        //     {
        //         console.log(data);
        //         var jsonObject = $.parseJSON(data);
        //         var distance_value = parseInt(jsonObject.routes[0].legs[0].distance.text.replace('km','').replace(',','').trim());
        //         if(distance_value > 100){
        //             alert('Distance to installer >100 km seek advice as to whether to proceed with quote');
        //         }
        //         $("#distance_to_sg_c").val(jsonObject.routes[0].legs[0].distance.text);
        //     },

        //     error: function(response)
        //     {
        //         alert("Cannot get distance");
        //     },
        // });

        // Solve suggest
        var shortest = 0;
        var shortest_string = "";
        var option__office_shortest = '';
        var promises = [];
        
        //suggest_address.forEach(function(addr){
        for ( var i = 0; i < suggest_address.length; i ++){
            var addr = suggest_address[i];
            promises.push($.ajax({
                url: "/index.php?entryPoint=customDistance&address_from=" + from_address + "&address_to=" + addr,
                type: 'GET',
                //async: false,
                success: function(data)
                {
                    var jsonObject = $.parseJSON(data);
                    var l_distance = parseFloat(jsonObject.routes[0].legs[0].distance.text.replace(/[^\d.-]/g, ''));
                    var addr = jsonObject.toAddress;
                    if (shortest == 0 || shortest > l_distance){
                        shortest = l_distance
                        $("#distance_to_sg_c").nextAll('.shortest-suggest').html('<a class="selected-suggest" dist="'+jsonObject.routes[0].legs[0].distance.text+'" rel="'+addr+'" href="#">'+addr+':<span style="color:green">'+jsonObject.routes[0].legs[0].distance.text+'</span></a> <br>');
                        //shortest_string = '<a class="selected-suggest" dist="'+jsonObject.routes[0].legs[0].distance.text+'" rel="'+addr+'" href="#">'+addr+':<span style="color:green">'+jsonObject.routes[0].legs[0].distance.text+'</span></a> <br>';
                        //dung code - new logic -auto fill short disance 
                        $("#distance_to_sg_c").val(shortest+' km');
                        option__office_shortest = addr;
                    }
                    $("#distance_to_sg_c").nextAll('.suggest').next().after('<a class="selected-suggest" dist="'+jsonObject.routes[0].legs[0].distance.text+'" rel="'+addr+'" href="#">'+addr+':<span style="color:green">'+jsonObject.routes[0].legs[0].distance.text+'</span></a> <br>');
                },

                error: function(response)
                {
                    alert("Cannot get distance");
                },
            })
            );
        }
        //})
        /// Inpormiss
        Promise.all(promises)
        .then(responseList => {
            $("#solargain_offices_c").val($('option[label="'+option__office_shortest+'"]').val());
            if(shortest > 100){
                alert('Distance to installer >100 km seek advice as to whether to proceed with quote');
            }
        })
            

        return false;
    });
    $('div[field="distance_to_sg_c"]').on('click','.selected-suggest', function ( event){
        console.log("=============="+$(this).attr("dist"));
        event.preventDefault();
        var add = $(this).attr("rel");
        var link = this
        $("#solargain_offices_c option").each(function(){
            if ($(this).attr("label") == add ){
                var val = $(this).attr("value");
                $("#solargain_offices_c").val(val);
                $(link).attr("dist")
                var distance_value = parseInt( $(link).attr("dist").replace('km','').replace(',','').trim());
                if(distance_value > 100){
                    alert('Distance to installer >100 km seek advice as to whether to proceed with quote');
                }
                $("#distance_to_sg_c").val($(link).attr("dist"));
                return;
            }
        });
    });
    SolarGainQuoteNumberLink();

    $('#solargain_quote_number_c').change(function () {
        SolarGainQuoteNumberLink();
    });

    function SolarGainQuoteNumberLink() {
        if ($('#solargain_quote_number_c').val() == "") return;
        var href = "<div class='open-solargain-quote-number'>SG Quote <a target='_blank' href='https://crm.solargain.com.au/quote/edit/" + $('#solargain_quote_number_c').val()+"'>https://crm.solargain.com.au/quote/edit/" + $('#solargain_quote_number_c').val() + "</a></div>";
        $('#solargain_quote_number_c').siblings().empty();
        $('#solargain_quote_number_c').parent().append(href);
    }

    SolarGainLeadNumberLink();

    $('#solargain_lead_number_c').change(function () {
        SolarGainLeadNumberLink();
    });

    function SolarGainLeadNumberLink() {
        if ($('#solargain_lead_number_c').val() == "") return;
        var href = "<div class='open-solargain--number'>SG Lead <a target='_blank' href='https://crm.solargain.com.au/lead/edit/" + $('#solargain_lead_number_c').val()+"'>https://crm.solargain.com.au/lead/edit/" + $('#solargain_lead_number_c').val() + "</a></div>";
        $('#solargain_lead_number_c').siblings().empty();
        $('#solargain_lead_number_c').parent().append(href);
    }
    // First name last name move out

    $("#last_name").change(function(){
        var name = $("#first_name").val()+ " " + $("#last_name").val();
        $("#EditView_account_name").val(name);
        return true;
    });

    $("#first_name").change(function(){
        var name = $("#first_name").val()+ " " + $("#last_name").val();
        $("#EditView_account_name").val(name);
        return true;
    });
    var leadID = 0;

    function generateUUID() {
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

    $( document ).ready(function() {
        // Autocomplete search

        if ($('input[name="installation_pictures_c"]').val() == "") {
            $('input[name="installation_pictures_c"]').val(generateUUID());
        }

        $('input[id="SAVE"]').next().after(
            '&nbsp;<button type="button" id="save_and_edit" class="button saveAndEdit" title="Save and Edit" onClick="SUGAR.saveAndEdit(this);">Save and Edit</button>'
        )

        SUGAR.saveAndEdit = function (elem) {
            SUGAR.ajaxUI.showLoadingPanel();
            $("#EditView input[name='action']").val('Save');
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                success: function (data) {
                    if($("input[name='record']").val() == ''){
                        var record_id_patt = /"record" value="(.*)"/g;
                        var records = record_id_patt.exec(data);
                        if(records !== null && typeof records === 'object'){
                            if(records[1] !='')  {
                                window.onbeforeunload = null;
                                window.onunload = null;
                                window.addEventListener('beforeunload', function(e) {
                                    window.onbeforeunload = null;
                                    window.onunload = null;
                                });
                                var url = 'https://suitecrm.pure-electric.com.au';
                                // var url = 'http://locsuitecrm.com/';
                                window.location.href = url+"index.php?module="+module_sugar_grp1+"&action=EditView&record="+records[1];
                            }
                        }
                        return false;
                    }
                    $(".reload_after_rename").trigger("click");
                    SUGAR.ajaxUI.hideLoadingPanel();
                }
            });
            return false;
        }

        // dung code -- create logic button cancel
        SUGAR.ButtonCancel = function (elem)
        {
            var url = '/index.php?module=Leads&action=DetailView&record=' + $('input[name="record"]').val();
            $(location).attr('href', url);
        }
        if($('input[name="record"]').val() != ''){
            $(document).find("input[id='CANCEL']").hide();
            $('input[id="SAVE"]').next().after(
                '&nbsp;<button type="button" id="new_cancel" class="button" title="Cancel" onClick="SUGAR.ButtonCancel(this);">Cancel</button>'
            );
        }

        //dung code - logic button "Create Tesla SG Quote"
        // create button Create Tesla SG Quote -- thienpb edit
        // if($("#solargain_tesla_quote_number_c").val() == ''){
        //     $("#CANCEL").after(
        //         '&nbsp;<button type="button" id="Create_Tesla_SG_Quote" class="button" title="Create Tesla SG Quote" onClick="SUGAR.CreateTeslaSGQuote(this);">Create Tesla SG Quote</button>'
        //     )
        // }else{
        //     $("#CANCEL").after(
        //         '&nbsp;<button type="button" id="updateTeslaQuoteSG" class="button" title="Update Tesla SG Quote" onClick="SUGAR.updateTeslaQuoteToSolargain(this);">Update Tesla SG Quote</button>'
        //     )
        // }

        SUGAR.CreateTeslaSGQuote = function (element){
    
            //auto save before push SolarGain
            $("#EditView input[name='action']").val('Save');
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                async: false, 
                success: function () {
                }
            });

            if($("#solargain_lead_number_c").val() !== ""){
                SUGAR.convertToSolargainQuoteTesla(element);
                return;
            }

            var build_url=  "?entryPoint=customCreateSolarGain";
            build_url += '&notes='+ encodeURIComponent($("#description").val()) ;
            build_url += '&record='+ encodeURIComponent($('input[name="record"]').val());
            //system_size_c
            build_url += '&system_size='+ encodeURIComponent($("#system_size_c").val()) ;
            build_url += '&unit_per_day='+ encodeURIComponent($("#units_per_day_c").val()) ;
            build_url += '&dolar_month='+ encodeURIComponent($("#dolar_month_c").val()) ;
            build_url += '&number_of_people='+ encodeURIComponent($("#number_of_people_c").val()) ;
            build_url += '&primary_address_state='+ encodeURIComponent($("#primary_address_state").val()) ;
            var customer_type = $('input[name=customer_type_c]:checked').val();
            build_url += '&customer_type='+ encodeURIComponent(customer_type) ;
            build_url += '&last_name='+ encodeURIComponent($("#last_name").val()) ;
            build_url += '&first_name='+ encodeURIComponent($("#first_name").val()) ;
            build_url += '&phone_work='+ encodeURIComponent($("#phone_work").val()) 
            build_url += '&phone_mobile='+ encodeURIComponent($("#phone_mobile").val()); 
            build_url += '&email='+ encodeURIComponent($("#Leads0emailAddress0").val());
            build_url += '&primary_address_street='+ encodeURIComponent($("#primary_address_street").val());
            build_url += '&primary_address_city='+ encodeURIComponent($("#primary_address_city").val());
            build_url += '&state='+ encodeURIComponent($("#primary_address_state").val());
            build_url += '&postalcode='+ encodeURIComponent($("#primary_address_postalcode").val());
            //build_height
            build_url += '&build_height='+ encodeURIComponent($("#gutter_height_c").val());
            var connection_type = $("#connection_type_c").val();
            if (connection_type == "Semi_Rural_Remote_Meter")  connection_type =  "Semi Rural/Remote Meter";
            build_url += '&connection_type='+ encodeURIComponent($("#connection_type_c").val());
            build_url += '&main_type='+ encodeURIComponent($("#main_type_c").val());

            build_url += '&meter_number='+ encodeURIComponent($("#meter_number_c").val());
            build_url += '&nmi_number='+ encodeURIComponent($("#nmi_c").val());
            build_url += '&account_number='+ encodeURIComponent($("#account_number_c").val());
            build_url += '&billing_name='+ encodeURIComponent($("#name_on_billing_account_c").val());
            build_url += '&energy_retailer='+ encodeURIComponent($("#energy_retailer_c").val());
            build_url += '&distributor='+ encodeURIComponent($("#distributor_c").val());
            // Roof type
            var roof_type = $("#roof_type_c").val();
            if (roof_type == "klip_loc"){
                roof_type = "Klip Loc";
            }
            if (roof_type == "Trim_Deck"){
                roof_type = "Trim Deck";
            }

            if (roof_type == "Ground_Mount"){
                roof_type = "Ground Mount";
            }

            build_url += '&roof_type='+ encodeURIComponent(roof_type);

            $.ajax({
                url: build_url,
                type : 'POST',
                success: function (data) {
                    $("#solargain_lead_number_c").val(data);
                    $("#solargain_lead_number_c").trigger("change");
                    SUGAR.convertToSolargainQuoteTesla(element);
                },
            });
        }
        // end - logic button "Create Tesla SG Quote"

        //function convertToSolargainQuoteTesla
        SUGAR.convertToSolargainQuoteTesla = function (element){      
            leadID = $("#solargain_lead_number_c").val();
            if (leadID == 0 ) return;
            var build_url=  "?entryPoint=customCreateQuoteTalest";
            build_url += '&leadID='+ encodeURIComponent(leadID);

            build_url += '&record='+ encodeURIComponent($('input[name="record"]').val())
                        + "&primary_address_street=" + $("#primary_address_street").val()
                        + "&primary_address_city=" + $("#primary_address_city").val()
                        + "&primary_address_state=" + $("#primary_address_state").val()
                        + "&primary_address_postalcode=" + $("#primary_address_postalcode").val();

            $.ajax({
                url: build_url,
                type : 'POST',
                success: function (data) {
                    //console.log(data);
                    leadID = data;
                    $("#solargain_tesla_quote_number_c").val(data);
                    SolarGainQuoteNumberLinkTalest();
                },
            });
        }

        //thienpb code -- function Update tesla sg quote
         SUGAR.updateTeslaQuoteToSolargain = function(elem) {
            $('#updateTeslaQuoteSG span.glyphicon-refresh').removeClass('hidden');

            var tesla_quote_id = $("#solargain_tesla_quote_number_c").val();
            if(tesla_quote_id == ''){
                alert('Please enter tesla quote number before clicking this button.');
                $("#solargain_tesla_quote_number_c").focus();
                $('#updateTeslaQuoteSG span.glyphicon-refresh').addClass('hidden');
                return;
            }

            var build_url_quote_tesla = "?entryPoint=customUpdateTeslaQuoteToSolarGain";
            build_url_quote_tesla += '&record='+ encodeURIComponent($('input[name="record"]').val());
            build_url_quote_tesla += "&tesla_quote_id="+encodeURIComponent($("#solargain_tesla_quote_number_c").val());
            build_url_quote_tesla += "&meter_number="+encodeURIComponent($("#meter_number_c").val());
            build_url_quote_tesla += "&meter_phase="+encodeURIComponent($("#meter_phase_c").val());
            build_url_quote_tesla += '&account_number='+ encodeURIComponent($("#account_number_c").val());
            build_url_quote_tesla += "&nmi_number="+encodeURIComponent($("#nmi_c").val());
            build_url_quote_tesla += "&name_on_billing_account="+encodeURIComponent($("#name_on_billing_account_c").val());
            build_url_quote_tesla += '&billing_name='+ encodeURIComponent($("#name_on_billing_account_c").val());
            build_url_quote_tesla += "&energy_retailer="+encodeURIComponent($("#energy_retailer_c").val());
            build_url_quote_tesla += "&distributor="+encodeURIComponent($("#distributor_c").val());
            build_url_quote_tesla += '&primary_address_street='+ encodeURIComponent($("#primary_address_street").val());
            build_url_quote_tesla += '&primary_address_city='+ encodeURIComponent($("#primary_address_city").val());
            build_url_quote_tesla += '&state='+ encodeURIComponent($("#primary_address_state").val());
            build_url_quote_tesla += '&postalcode='+ encodeURIComponent($("#primary_address_postalcode").val());
            build_url_quote_tesla += '&address_nmi='+ encodeURIComponent($("#address_nmi_c").val());
            build_url_quote_tesla += '&last_name='+ encodeURIComponent($("#last_name").val()) ;//
            build_url_quote_tesla += '&first_name='+ encodeURIComponent($("#first_name").val()) ;//
            build_url_quote_tesla += '&phone_work='+ encodeURIComponent($("#phone_work").val()) //
            var phone_number_customer = $('#phone_mobile').val();
            phone_number_customer = phone_number_customer.replace(/\s/g, '');
            build_url_quote_tesla += '&phone_mobile='+ encodeURIComponent(phone_number_customer); //
            build_url_quote_tesla += '&email='+ encodeURIComponent($("#Leads0emailAddress0").val());
            var customer_type = $('input[name=customer_type_c]:checked').val();
            build_url_quote_tesla += '&customer_type='+ encodeURIComponent(customer_type) ;

            var roof_type = $("#roof_type_c").val();
            if (roof_type == "klip_loc"){
                roof_type = "Klip Loc";
            }
            if (roof_type == "Trim_Deck"){
                roof_type = "Trim Deck";
            }

            if (roof_type == "Ground_Mount"){
                roof_type = "Ground Mount";
            }

            build_url_quote_tesla += '&roof_type='+ encodeURIComponent(roof_type);
            build_url_quote_tesla += '&build_height='+ encodeURIComponent($("#gutter_height_c").val());
            build_url_quote_tesla += '&connection_type='+ encodeURIComponent($("#connection_type_c").val());
            build_url_quote_tesla += '&main_type='+ encodeURIComponent($("#main_type_c").val());
            for(var i = 1; i<=6 ;i++){
                var travel_km    = $("#travel_km_"+i).val().trim();
                //var price_option = $("#suggest_price_"+i).val().trim();
                var number_double_storey_panel = $("#number_double_storey_panel_"+i).val().trim();
                var groups_of_panels  = $("#groups_of_panels_"+i).val().trim();

                if(travel_km == ''){
                    travel_km = 0;
                }else if(parseInt(travel_km) > 50) {
                    travel_km = parseInt(travel_km) - 50;
                } else{
                    travel_km = 0;
                }
                if(number_double_storey_panel == ''){
                    number_double_storey_panel = 0;
                }
                if(parseInt(groups_of_panels) >= 2){
                    groups_of_panels = groups_of_panels - 2;
                }else{
                    groups_of_panels = 0;
                }

                //build_url_quote += '&price_option_'+ i +'='+ encodeURIComponent(price_option);
                build_url_quote_tesla += '&travel_km_'+ i +'='+ encodeURIComponent(parseInt(travel_km));
                build_url_quote_tesla += '&number_double_storey_panel_'+ i +'='+ encodeURIComponent(parseInt(number_double_storey_panel));
                build_url_quote_tesla += '&groups_of_panels_'+ i +'='+ encodeURIComponent(parseInt(groups_of_panels));

            }

            $.ajax({
                url: build_url_quote_tesla,
                type : 'POST',
                success: function (data) {
                    $('#updateTeslaQuoteSG span.glyphicon-refresh').addClass('hidden');
                },
            });
             
         }

        //function display link crm solargain talest
        function SolarGainQuoteNumberLinkTalest() {
            if ($('#solargain_tesla_quote_number_c').val() == "") return;
            var href = "<div class='open-solargain-quote-talest-number'>SG Quote Tesla <a target='_blank' href='https://crm.solargain.com.au/quote/edit/" + $('#solargain_tesla_quote_number_c').val()+"'>https://crm.solargain.com.au/quote/edit/" + $('#solargain_tesla_quote_number_c').val() + "</a></div>";
            $('#solargain_tesla_quote_number_c').siblings().empty();
            $('#solargain_tesla_quote_number_c').parent().append(href);
        }
        // call when load page
        SolarGainQuoteNumberLinkTalest();
        
        //thien code for get meter number
        $("#meter_number_c").after('<br><button type="button" class="button primary" id="getMeter"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Meter </button>');
        var nmi_number_meter = '';
        var distributor_meter ='';
        $("#getMeter").on("click",function(){
            nmi_number_meter =  $("#nmi_c").val();
            if(nmi_number_meter !== ''){
                ajax_get_number_meter();
            }else{
                get_number_NMI();
                ajax_get_number_meter();
                $('#getMeter span.glyphicon-refresh').addClass('hidden');
            }
        });
        //end
        
        //dung code - copy code thien get meter
        function ajax_get_number_meter(){
            nmi_number_meter =  $("#nmi_c").val();
            distributor_meter =  $("#distributor_c").val();
            var record_id = $("input[name='record']").val();
            var customer_name = $("#first_name").val() +' ' + $('#last_name').val();
            var meter_phase_c = $("#meter_phase_c").val();
            if(distributor_meter == 4 ||  distributor_meter == 6){
                SUGAR.ajaxUI.showLoadingPanel();
                $.ajax({
                    url: "/index.php?entryPoint=customGetMeter&nmi_number=" + nmi_number_meter +"&lead_id="+record_id+"&meter_phase_c="+meter_phase_c+"&type=GET_METER",
                    type: 'GET',
                    success: function(data)
                    {
                        if(data != '' && typeof data !== "undefined"){
                            $("#meter_number_c").val(data);
                            SUGAR.ajaxUI.hideLoadingPanel();
                        }else{
                            SUGAR.ajaxUI.hideLoadingPanel();
                            //tuan cope thien code ===================
                            $(".modal_meter_number").remove();
                            var html = '<div class="modal fade modal_meter_number" tabindex="-1" role="dialog">'+
                                            '<div class="modal-dialog">'+
                                                '<div class="modal-content">'+
                                                    '<div class="modal-header">'+
                                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                        '<h5 class="modal-title" id="title-generic"><center>. Please check your address and Search again.</center></h5>'+
                                                    '</div>'+
                                                    '<div class="modal-body">'+
                                                        '<div class="container-fluid" style="margin-left:30px;font-size:13px;text-align:center;"><a target="_blank" href="https://econnect.portal.powercor.com.au/customer/solarpreapprovalrequestpage"> Citipower Powercor\'s Link</a>'
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>';
                            $("body").append(html);
                            $(".modal_meter_number").modal('show');
                        // tuan and ==============================
                            //alert ("The address you have nominated cannot be found in our system. Please check your address and Search again.");
                            $("#meter_number_c").val('');
                        }
                    },
                    error: function(response){
                        alert('Get Meter Number Fail! Please check NMI Number and try again.');
                        SUGAR.ajaxUI.hideLoadingPanel();
                        $("#meter_number_c").val('');
                    }
                })
            }else{
                alert("Please sure Distributor option is 'Citipower' OR 'Powercor'");
                SUGAR.ajaxUI.hideLoadingPanel();
                $("#meter_number_c").val('');
            }
            
        }
        //dung code - buttom check Meter
        $("#getMeter").after('<button type="button" class="button primary" id="checkMeter"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Check Meter </button>');
        $('#checkMeter').after('<div id="text_check_meter"></div>');
        $('#checkMeter').on('click',function(){
            var meter_number = $('#meter_number_c').val();
            var meterQuoteState =  $('#primary_address_state').val().toUpperCase();
            if(meter_number == '') {
                alert('We have not Meter Number.');
                return false;
            }else {
                $('#checkMeter span.glyphicon-refresh').removeClass('hidden');
                $.ajax({
                    url: "/index.php?entryPoint=CustomCheckNumberMeter&meter_number_c=" + meter_number +'&meterQuoteState='+meterQuoteState,
                    type: 'GET',
                    success: function(data)
                    {
                        var data_json =  $.parseJSON(data);
                        if(data_json['Quote For'] == null && data_json['Network Distributor'] == null && data_json['NMI'] == null ) {
                            var html_append = '<p>* Number Meter Wrong *</p>';
                            $('#text_check_meter').empty();
                            $('#text_check_meter').append(html_append);
                        }else {
                            var html_append = '';
                            html_append += '<p>*Quote For : ' + data_json['Quote For'] +'</p>';
                            html_append += '<p>*Network Distributor : ' + data_json['Network Distributor'] +'</p>';
                            html_append += '<p>*NMI : ' + data_json['NMI'] +'</p>';
                            $('#text_check_meter').empty();
                            $('#text_check_meter').append(html_append);
                        }
                        $('#checkMeter span.glyphicon-refresh').addClass('hidden');
                    },
                })
            }
            return false;
        })
        //$("#primary_address_country").after("<br><button id='open_map' onclick='openMap(); return false;' >Open Map </button> ");//&nbsp; &nbsp; <button id='open_map' onclick='open_street_view();' >Open Street View </button>
        $("#distributor_c").after('<br><button class="button primary" id="getDistributor"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Distributor </button>');
        //thien add button Ausnet approval
        $("#getDistributor").after("<br><button class='button primary' style='display:none' type='button'  id='Ausnet_Approval' name = 'Ausnet_Approval' ><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span> Ausnet Approval </button>")
        if($("#distributor_c").val() == 7){
            $('#Ausnet_Approval').show();
        }else{
            $('#Ausnet_Approval').hide();
        }
        $("#Ausnet_Approval").click(function(){
            $('#Ausnet_Approval span.glyphicon-refresh').removeClass('hidden');
            var nmi_number_meter = $("#nmi_c").val();
            var meter_number = $("#meter_number_c").val();
            var installation_pictures_c_id = $('input[name="installation_pictures_c"]').val();
            if(meter_number == '' || nmi_number_meter == ''){
                alert("Don\'t have number NMI or Meter Number");
                $('#Ausnet_Approval span.glyphicon-refresh').addClass('hidden');
                return false;
            }
            $.ajax({
                url: "/index.php?entryPoint=getAusnetApproval&nmi_number=" + nmi_number_meter +"&meter_number="+meter_number+"&installation_pictures="+installation_pictures_c_id,
                type: 'GET',
                success: function(data){

                    if(data=='done'){
                        alert('Get PDF file successfuly!');
                    }else{
                        alert('Get PDF file Fail! Please check NMI Number and Meter Number.');
                    }

                    $('#Ausnet_Approval span.glyphicon-refresh').addClass('hidden');
                    $(".files").empty();                
                    $.ajax({
                        url: $('#fileupload').fileupload('option', 'url'),
                        dataType: 'json',
                        context: $('#fileupload')[0]
                    }).always(function () {
                        $(this).removeClass('fileupload-processing');
            
                    }).done(function (result) {
                        $(this).fileupload('option', 'done')
                            .call(this, $.Event('done'), {result: result});
                    });
                },
                error: function(response){
                    alert('Get PDF file Fail! Please check NMI Number and Meter Number.');
                    $('#Ausnet_Approval span.glyphicon-refresh').addClass('hidden');
                },
            });
        })

        //thien add button  register jemena account
        $("#getDistributor").after("<br><button class='button primary' style='display:none' type='button'  id='register_jemena_account' name = 'register_jemena_account' ><span class='glyphicon hidden glyphicon-refresh glyphicon-refresh-animate'></span> Register Jemena Account </button>")
        if($("#distributor_c").val() == 5){
            $('#register_jemena_account').show();
        }else{
            $('#register_jemena_account').hide();
        }

        $("#register_jemena_account").click(function(){
            $('#register_jemena_account span.glyphicon-refresh').removeClass('hidden');
            var nmi_number_jemena = $("#nmi_c").val();
            var meter_number_jemena = $("#meter_number_c").val();
            var AddressLineOne = $("#primary_address_street").val().trim()+' '+$("#primary_address_city").val();
            var subrb = $("#primary_address_state").val();
            var postcode = $("#primary_address_postalcode").val();
            var email_cus = $("#Leads0emailAddress0").val();
            var firstName = $("#first_name").val();
            var lastName = $("#last_name").val();
            var contactNumber = $("#phone_mobile").val();
            if(nmi_number_jemena == '' || meter_number_jemena == ''){
                alert("Don\'t have number NMI or Meter Number");
                $('#Ausnet_Approval span.glyphicon-refresh').addClass('hidden');
                return false;
            }
            $.ajax({
                url: "/index.php?entryPoint=registrationJemena&nmiNumber=" +nmi_number_jemena+"&meterNumber="+meter_number_jemena+"&AddressLineOne="+AddressLineOne+"&postcode="+postcode+"&subrb="+subrb+"&email_cus="+email_cus+"&firstName="+firstName+"&lastName="+lastName+"&contactNumber="+contactNumber,
                type: 'GET',
                success: function(data){
                    if(data.indexOf("@sharklasers.com") > 0){
                        $("#jemena_account_c").val(data);
                        $('#register_jemena_account span.glyphicon-refresh').addClass('hidden');
                    }else{
                        $(".modal_jemena").remove();
                        var html = '<div class="modal fade modal_jemena" tabindex="-1" role="dialog">'+
                                        '<div class="modal-dialog">'+
                                            '<div class="modal-content">'+
                                                '<div class="modal-header">'+
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                    '<h4 class="modal-title" id="title-generic"><center>Jemena Account Error</center></h4>'+
                                                '</div>'+
                                                '<div class="modal-body">'+
                                                    '<div class="container-fluid" style="margin-left:30px;font-size:13px;text-align:center;">'+data+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                        $("body").append(html);
                        $(".modal_jemena").modal('show');
                        $('#register_jemena_account span.glyphicon-refresh').addClass('hidden');
                    }
                }
            });

        });

        function getNMI(){
            var nmi = $("#nmi_c").val();
            if (parseInt(nmi) != nmi)
            {
                $("#nmi_c").val('');
                $("#address_nmi_c").val('');
                alert("Invalid NMI!");
                return false;
            }

            $('#getDistributor span.glyphicon-refresh').removeClass('hidden');

            nmi = parseInt(nmi);

            var NSP = [
                {
                    name: "Citipower",
                    value: 4,
                    range: [{min: 6102000000, max: 6103999999}]
                },
                {
                    name: "Jemena",
                    value: 5,
                    range: [{min: 6001000000, max: 6001999999}]
                },
                {
                    name: "Powercor",
                    value: 6,
                    range: [{min: 6203000000, max: 6204999999}]
                },
                {
                    name: "Ausnet",
                    value: 7,
                    range: [{min: 6305000000, max: 6306999999},
                            {min: 6509000000, max: 6509009999}]
                },
                {
                    value: "United",
                    value: 8,
                    range: [{min: 6407000000, max: 6408999999}]
                },
                {
                    name: "Western Power",
                    value: 1,
                    range: [{min: 8001000000, max: 8020999999}]
                },
                {
                    name: "SA Power Networks - NSP",
                    value: 13,
                    range: [{min: 2001000000, max: 2002999999}]
                },
                {
                    name: "Energex",
                    value: 2,
                    range: [{min: 3100000000, max: 3199999999}]
                },
                {
                    name: "Ergon",
                    value: 3,
                    range: [{min: 3000000000, max: 3099999999}]
                },
                {
                    name: "Essential Energy",
                    value: 9,
                    range: [{min: 4001000000, max: 4001999999}, {min: 4508000000, max: 4508099999},
                            {min: 4204000000, max: 4204999999}, {min: 4407000000, max: 4407999999}]
                },
                {
                    name: "Ausgrid",
                    value: 10,
                    range: [{min: 4102000000, max: 4104999999}]
                },
                {
                    name: "Endeavour Energy",
                    value: 12,
                    range: [{min: 4310000000, max: 4319999999}]
                },
                {
                    name: "ActewAGL",
                    value: 11,
                    range: [{min: 7001000000, max: 7001999999}]
                },
            ];

            var NSPLen = NSP.length;
            for (var i = 0; i < NSPLen; i++)
            {
                var range = NSP[i].range
                var rangeLen = range.length;
                for (var j = 0; j < rangeLen; j++)
                {
                    if ((nmi >= range[j].min && nmi <= range[j].max) ||
                        (nmi >= range[j].min * 10 && nmi <= range[j].max * 10 + 9))
                    {
                        $("#distributor_c").val(NSP[i].value);
                        //thien fix show Ausnet_Approval button
                        if(NSP[i].value == 7){
                           $('#Ausnet_Approval').show(); 
                        }else{
                            $('#Ausnet_Approval').hide();
                        }

                        //thien fix show register jemena button
                        if(NSP[i].value == 5){
                            $('#register_jemena_account').show();
                         }else{
                             $('#register_jemena_account').hide();
                         }
                        $('#getDistributor span.glyphicon-refresh').addClass('hidden');

                        return false;
                    }
                }
            }

            $('#getDistributor span.glyphicon-refresh').addClass('hidden');

            return false;
        }
        $('#getDistributor').on('click', function (event){
            return getNMI();
        });

        //$("#primary_address_country").after("<br><button id='open_map' onclick='openMap(); return false;' >Open Map </button> ");//&nbsp; &nbsp; <button id='open_map' onclick='open_street_view();' >Open Street View </button>

        addOpenMapView();

        $("#nmi_c").after('<br><button class="button primary" id="getnmi"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get NMI </button>');
        $('#getnmi').on('click', function(event) {
            get_number_NMI();
            return false;
        });

        //dung code - buttom check NMI
        $("#getnmi").after('<button type="button" class="button primary" id="checkNMI"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Check NMI </button>');
        $('#checkNMI').after('<div id="text_check_nmi"></div>');
        $('#checkNMI').on('click',function(){
            var nmi_number = $('#nmi_c').val();
            if(nmi_number == '') {
                alert('We have not NMI Number.');
                return false;
            }else {
                $('#checkNMI span.glyphicon-refresh').removeClass('hidden');
                $.ajax({
                    url: "/index.php?entryPoint=CustomCheckNumberNMI&nmi_c=" + nmi_number,
                    type: 'GET',
                    success: function(data)
                    {
                        var data_json =  $.parseJSON(data);
                        if(data_json['Quote For'] == null && data_json['Network Distributor'] == null && data_json['NMI'] == null ) {
                            var html_append = '<p>* Number Meter Wrong *</p>';
                            $('#text_check_nmi').empty();
                            $('#text_check_nmi').append(html_append);
                        }else {
                            var html_append = '';
                            html_append += '<p>*Quote For : ' + data_json['Quote For'] +'</p>';
                            html_append += '<p>*Network Distributor : ' + data_json['Network Distributor'] +'</p>';
                            html_append += '<p>*NMI : ' + data_json['NMI'] +'</p>';
                            $('#text_check_nmi').empty();
                            $('#text_check_nmi').append(html_append);
                        }
                        $('#checkNMI span.glyphicon-refresh').addClass('hidden');
                    },
                })
            }
            return false;
        })

        //dung code - copy function get NMI  from thien code
        function get_number_NMI(){
            
            $('#getnmi span.glyphicon-refresh').removeClass('hidden');

            //thien fix for get nmi
            var address = $("#primary_address_street").val() + ',' +
                          $("#primary_address_city").val() + ' ' +
                          $("#primary_address_state").val() + ' ' +
                          $("#primary_address_postalcode").val();

            var value =  address.split(",");
            var valueLen = value.length;
            var address1 = value[0];
            for (var i = 1; i < valueLen - 1; i++) {
                address1 = address1 + value[i];
            }
            var address2 = value[valueLen - 1].trim();

            var address3 = address2.split(" ");

            var address1Items = address1.split(",");
            var address1Len = address1Items.length;
            var addarr = address1Items[address1Len - 1].trim().split(" ");
            var a_first_addres = "";
            var unit = "";
            var unit_num = "";
            var address_number = "";
            var address_name = "";
            if(addarr.length == 2){
                //a_first_addres = addarr[0].replace("Unit","U")+ "/";
                //a_first_addres += addarr[1].replace(/ /,"/");

                // Unit param
                var unit_numbers = addarr[0].split(" ");
                unit = unit_numbers[0];
                unit_num = unit_numbers[1];
                var address_numbers =addarr[1].split(" ");
                address_number = address_numbers[0];
                address_name = address_numbers[1].replace(" ", "+");

            }
            else{
                //a_first_addres  = "NA/"
                //a_first_addres +=  address1.replace(/ /,"/");

                var address_numbers = addarr;
                address_number = address_numbers[0];
                address_name = address_numbers[1].replace(" ", "+");
            }
            var requestString ; //= a_first_addres + "/" + address3[0].trim() +"/"+ address3[1].trim() +"/"+ address3[2].trim();
            //var street = explode()
            requestString = encodeURIComponent("unit=" + unit + "/unit_num=" + unit_num + "/streetNumber="+address_number+"/streetName="+address_name+"/city="+ address3[0] + "/state="+address3[1]+"/customerType=residential/searchByPostcode=false/postcode="+address3[2]+"/fuelType=dual&hasSolarPanels=false/connectionScenario=PROS_SWT");

            $.ajax({
                url: "/index.php?entryPoint=customGetRetailer&address=" + address + "&momentumenergy=1&requestString="+requestString,
                type: 'GET',
                async: false, 
                success: function(data)
                {
                    if(data.indexOf("ChooseExactMeter_Nmis_0__MeterNumber") >= 0){
                        $(".modal_nmi").remove();
                        var html = '<div class="modal fade modal_nmi" tabindex="-1" role="dialog">'+
                                        '<div class="modal-dialog">'+
                                            '<div class="modal-content">'+
                                                '<div class="modal-header">'+
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                    '<h4 class="modal-title" id="title-generic">Select NMI</h4>'+
                                                '</div>'+
                                                '<div class="modal-body">'+
                                                    '<div class="container-fluid" style="margin-left:30px;">'+data+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                        $("body").append(html);
                        $(".modal_nmi").modal('show');
                        $(".modal_nmi").find("input").click(function(){
                            $("#nmi_c").val($(this).val());
                            $("#address_nmi_c").val($("#ChooseExactMeter_Nmis_"+$(this).index()+"__Address").val());
                            $(".modal_nmi").modal('hide');
                        })
                    }else{
                        $("#nmi_c").val(data);
                        if(data !== ""){
                            getNMI();
                        }
                        if (data == '')
                        {
                            var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
                            if (ok)
                                window.open('https://www.momentumenergy.com.au/', '_blank');
                            $("#nmi_c").val('');
                            $("#address_nmi_c").val('');
                        }
                    }
                    $('#getnmi span.glyphicon-refresh').addClass('hidden');
                },

                error: function(response)
                {
                    $('#getnmi span.glyphicon-refresh').addClass('hidden');

                    var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
                    if (ok)
                        window.open('https://www.momentumenergy.com.au/', '_blank');
                    $("#nmi_c").val('');
                    $("#address_nmi_c").val('');
                },
            });

        }
        $("#primary_address_street").autocomplete({
            source: function( request, response ) {
                /*var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
                response( $.grep( tags, function( item ){
                    return matcher.test( item );
                }) );*/
                console.log(request["term"]);
                Math.floor((Math.random() * 3) + 1);


                // https://www.agl.com.au/svc/QAS/GetSearchResult?searchKey=26%20Walsh%20Avenue&maxResults=10&searchType=SiteAddressSearch&_=1499225218711
                if(request["term"].length > 3){
                    $.ajax({
                        url: "/index.php?entryPoint=customGetAddress&address="+request["term"],
                        //data: {q:request.term},
                        //crossOrigin: true,
                        type: 'GET',
                        //async: false,
                        //crossDomain: true,
                        //dataType: 'jsonp',

                        success: function(data)
                        {
                            var suggest =[];
                            var jsonObject = $.parseJSON(data);
                            for (i = 1; i < jsonObject.length; i++) {
                                suggest.push(jsonObject[i].name);
                            }
                            console.log(jsonObject);
                            response(suggest);
                        },
                        error: function(response){console.log("Fail");},
                    });
                }
                //console.log(response);
            },
            select: function( event, ui ) {
                var address = ui.item.value;
                var value =  ui.item.value.split(",");
                var valueLen = value.length;
                var address1 = value[0];
                for (var i = 1; i < valueLen - 1; i++) {
                    address1 = address1 + value[i];
                }
                var address2 = value[valueLen - 1].trim();

                $("#primary_address_street").val(address1);

                var address3 = address2.split("  ");

                $("#primary_address_city").val(address3[0]);
                $("#primary_address_state").val(address3[1]);
                $("#primary_address_postalcode").val(address3[2]);

                var address1Items = address1.split(",");
                var address1Len = address1Items.length;
                var addarr = address1Items[address1Len - 1].trim().split("  ");
                var a_first_addres = "";
                var unit = "";
                var unit_num = "";
                var address_number = "";
                var address_name = "";
                if(addarr.length == 2){
                    //a_first_addres = addarr[0].replace("Unit","U")+ "/";
                    //a_first_addres += addarr[1].replace(/ /,"/");

                    // Unit param
                    var unit_numbers = addarr[0].split(" ");
                    unit = unit_numbers[0];
                    unit_num = unit_numbers[1];
                    var address_numbers =addarr[1].split(" ");
                    address_number = address_numbers[0];
                    address_name = address_numbers[1].replace(" ", "+");

                }
                else{
                    //a_first_addres  = "NA/"
                    //a_first_addres +=  address1.replace(/ /,"/");

                    var address_numbers =addarr[0].split(" ");
                    address_number = address_numbers[0];
                    address_name = address_numbers[1].replace(" ", "+");
                }
                
                var requestString ; //= a_first_addres + "/" + address3[0].trim() +"/"+ address3[1].trim() +"/"+ address3[2].trim();
                //var street = explode()
                requestString = encodeURIComponent("unit=" + unit + "/unit_num=" + unit_num + "/streetNumber="+address_number+"/streetName="+address_name+"/city="+ address3[0] + "/state="+address3[1]+"/customerType=residential/searchByPostcode=false/postcode="+address3[2]+"/fuelType=dual&hasSolarPanels=false/connectionScenario=PROS_SWT");

                $.ajax({
                    url: "/index.php?entryPoint=customGetRetailer&address=" + address + "&momentumenergy=1&requestString="+requestString,
                    type: 'GET',

                    success: function(data)
                    {
                        if(data.indexOf("ChooseExactMeter_Nmis_0__MeterNumber") >= 0){
                            $(".modal_nmi").remove();
                            var html = '<div class="modal fade modal_nmi" tabindex="-1" role="dialog">'+
                                            '<div class="modal-dialog">'+
                                                '<div class="modal-content">'+
                                                    '<div class="modal-header">'+
                                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                        '<h4 class="modal-title" id="title-generic">Select NMI</h4>'+
                                                    '</div>'+
                                                    '<div class="modal-body">'+
                                                        '<div class="container-fluid" style="margin-left:30px;">'+data+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>';
                            $("body").append(html);
                            $(".modal_nmi").modal('show');
                            $(".modal_nmi").find("input").click(function(){
                                $("#nmi_c").val($(this).val());
                                $("#address_nmi_c").val($("#ChooseExactMeter_Nmis_"+$(this).index()+"__Address").val());
                                $(".modal_nmi").modal('hide');
                            })
                        }else{
                            $("#nmi_c").val(data);
                            if(data !== ""){
                                getNMI();
                            }
                            if (data == '')
                            {
                                var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
                                if (ok)
                                    window.open('https://www.momentumenergy.com.au/', '_blank');
                            }
                        }
                    },

                    error: function(response){
                        var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
                        if (ok)
                            window.open('https://www.momentumenergy.com.au/', '_blank');
                        $("#nmi_c").val('');
                        $("#address_nmi_c").val('');
                    },
                });

                //dung code -trigger event  getDistance
                $("#getDistance").trigger('click');
/*
                // address to request
                var address1Items = address1.split(",");
                var address1Len = address1Items.length;
                var addarr = address1Items[address1Len - 1].trim().split("  ");
                var a_first_addres = "";
                var unit = "";
                var unit_num = "";
                var address_number = "";
                var address_name = "";
                if(addarr.length == 2){
                    //a_first_addres = addarr[0].replace("Unit","U")+ "/";
                    //a_first_addres += addarr[1].replace(/ /,"/");

                    // Unit param
                    var unit_numbers = addarr[0].split(" ");
                    unit = unit_numbers[0];
                    unit_num = unit_numbers[1];
                    var address_numbers =addarr[1].split(" ");
                    address_number = address_numbers[0];
                    address_name = address_numbers[1].replace(" ", "+");

                }
                else{
                    //a_first_addres  = "NA/"
                    //a_first_addres +=  address1.replace(/ /,"/");

                    var address_numbers =addarr[0].split(" ");
                    address_number = address_numbers[0];
                    address_name = address_numbers[1].replace(" ", "+");
                }

                var requestString ; //= a_first_addres + "/" + address3[0].trim() +"/"+ address3[1].trim() +"/"+ address3[2].trim();
                //var street = explode()
                requestString = encodeURIComponent("unit=" + unit + "/unit_num=" + unit_num + "/streetNumber="+address_number+"/streetName="+address_name+"/city="+ address3[0] + "/state="+address3[1]+"/customerType=residential/searchByPostcode=false/postcode="+address3[2]+"/fuelType=dual&hasSolarPanels=false/connectionScenario=PROS_SWT");

                $.ajax({
                    url: "/index.php?entryPoint=customGetRetailer&address="+requestString,//"https://www.originenergy.com.au/for-home/electricity-and-gas/plans/energy-plans.planslanding.json/3000/" + requestString + "/NA/No/No/Elec/Email_Green/2/residential/iwt0a/40232495/No/suffix.json",
                    //data: {q:request.term},
                    //crossOrigin: true,
                    type: 'GET',
                    //async: false,

                    success: function(data)
                    {
                        var suggest =[];
                        //var jsonObject = $.parseJSON(data);
                        if (typeof data[0].nmi !== "undefined"){
                            $("#nmi_c").val(data[0].nmi);
                        }
                        //var provide_name = data.data.plans[0].fuel.electricity.tariff[0].serviceProviderName;
                        //console.log(jsonObject);
                        /*if(typeof jsonObject.originPlanData.plan[0].energyType[0].rates !== "undefined"){
                            var rates = jsonObject.originPlanData.plan[0].energyType[0].rates;
                            for (var rate in rates) {
                                // object[prop]
                                var first_rate = rates[rate];
                                provide_name = first_rate.serviceProviderName;
                                break;
                            }
                            //var plan = jsonObject.originPlanData.plan[0].energyType[0].ERV-GH.serviceProviderName;
                        }*/
                        //console.log(provide_name);
                        /*var distributors = {
                            "Citipower": "4",
                            "Jemena Electricity Networks (Vic) Ltd": "5",
                            "Powercor": "6",
                            "SP Ausnet": "7",
                            "Jemena (United Energy)": "8", // Edited
                            "Western Power": "1",
                            "SA Power Networks - NSP": "13",
                            "Energex": "2",
                            "Ergon": "3",
                            "Essential Energy": "9",
                            "Ausgrid": "10",
                            "Endeavour Energy": "12",
                            "ActewAGL": "11",
                            "AusNet Electricity Services Pty Ltd":"14",
                        }
                        $("#distributor_c").val(distributors[provide_name])
                        console.log("dss"+ provide_name);*/
/*
                    },
                    error: function(response){
                        var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
                        if (ok)
                            window.open('https://signup.globirdenergy.com.au/yourproperty', '_blank');
                    },
                });
*/
                return false;
            }
        });
        //dung code - get address by field postcode or city
        $("#primary_address_postalcode ,#primary_address_city").autocomplete({
            source: function( request, response ) {
                console.log(request["term"]);
                Math.floor((Math.random() * 3) + 1);
                if(request["term"].length > 3){
                    $.ajax({
                        url: "/index.php?entryPoint=customGetAddress&postcode_city="+request["term"],
                        type: 'GET',
                        success: function(data)
                        {
                            var suggest =[];
                            var jsonObject = data.split('\n');
                            for (i = 0; i < jsonObject.length; i++) {
                                var array_child = jsonObject[i].split('|');
                                if(array_child[0] !== ''){
                                    suggest[i] = array_child[2] +',' +array_child[3] +',' +array_child[1];
                                }
                            }
                            response(suggest);
                        },
                        error: function(response){console.log("Fail");},
                    });
                }
            },
            select: function( event, ui ) {
                var address = ui.item.value;
                var array_value =  address.split(",");
                $("#primary_address_city").val(array_value[0]);
                $("#primary_address_state").val(array_value[1]);
                $("#primary_address_postalcode").val(array_value[2]);
                return false;
            },
        });
        //VUT - S - autocomplete Site Address
        $("#site_detail_addr__c").autocomplete({
            source: function( request, response ) {
                console.log(request["term"]);
                Math.floor((Math.random() * 3) + 1);
                if(request["term"].length > 3){
                    $.ajax({
                        url: "/index.php?entryPoint=customGetAddress&address="+request["term"],
                        type: 'GET',
                        success: function(data)
                        {
                            var suggest =[];
                            var jsonObject = $.parseJSON(data);
                            for (i = 1; i < jsonObject.length; i++) {
                                suggest.push(jsonObject[i].name);
                            }
                            console.log(jsonObject);
                            response(suggest);
                        },
                        error: function(response){console.log("Fail");},
                    });
                }
            },
            select: function( event, ui ) {
                var value =  ui.item.value.split(",");
                var valueLen = value.length;
                var address1 = value[0];
                for (var i = 1; i < valueLen - 1; i++) {
                    address1 = address1 + value[i];
                }
                var address2 = value[valueLen - 1].trim();

                $("#site_detail_addr__c").val(address1);

                var address3 = address2.split("  ");

                $("#site_detail_addr__city_c").val(address3[0]);
                $("#site_detail_addr__state_c").val(address3[1]);
                $("#site_detail_addr__postalcode_c").val(address3[2]);
                return false;
            }, 
        });       
        //VUT - E - autocomplete Site Address
        //Tuan code ------------------------------------
        //$("#btn_view_change_log").after('&nbsp;<button type="button" id="mark_as_SG_sent" class="button mark_as_SG_sent" title="Mark as SG sent" onClick="SUGAR.markAsSG_sent(this);" > Mark as SG Sent <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        SUGAR.markAsSG_sent = function(elem) {
            $('#mark_as_SG_sent span.glyphicon-refresh').removeClass('hidden');
            // alert("ok");
            var build_url_quote = "?entryPoint=customMarkAsSG_sent";
            build_url_quote += "&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
            build_url_quote += "&email="+ encodeURIComponent($("#Leads0emailAddress0").val());
            $.ajax({
                url: build_url_quote,
                type : 'POST',
                success: function (data) {
                    $('#mark_as_SG_sent span.glyphicon-refresh').addClass('hidden');
                },
            });
        }
        // tuan and -----------------------------------
        /*if($("#solargain_lead_number_c").val() == "") $("#save_and_edit").after(
            '&nbsp;<button type="button" id="push_to_solargain" class="button pushToSolargain" title="Push To Solargain" onClick="SUGAR.pushToSolargain(this);" > Push To Solargain <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )

        if($("#solargain_quote_number_c").val() == "") $("#push_to_solargain").after(
            '&nbsp;<button type="button" id="convertToQuotesSolarGain" class="button convertToQuotesSolarGain" title="Convert To Solargain Quote" onClick="SUGAR.convertToSolargainQuote(this);" > Convert SG Quotes <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )*/

        //thien add button
        // if($("#solargain_quote_number_c").val() == ""){
        //     $("#save_and_edit").after(
        //     '&nbsp;<button type="button" id="convertToQuotesSolarGain" class="button convertToQuotesSolarGain" title="Convert To Solargain Quote" onClick="SUGAR.pushToSolargain(this);" > Push To SG <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        //     )
        // }else{
        //     $("#save_and_edit").after(
        //     '&nbsp;<button type="button" id="updateToQuotesSolarGain" class="button updateToQuotesSolarGain" title="Update Info To Solargain Quote" onClick="SUGAR.updateQuoteToSolargain(this);" > Update Info To To SG Quote <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        //     )
        // }

        
        // Request address via sms
        if(true){
            $("#save_and_edit").after(
            '&nbsp;<button type="button" data-record-id="'+$('input[name="record"]').val()+'" data-module-name="'+ $("#first_name").val() + ' ' + $("#last_name").val() +'" id="sendRequestAddressSMS" data-module="Leads" class="button sendRequestAddressSMS" title="Request address SMS" onClick="SUGAR.sendRequestAddressSMS(this);" > Request Address <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            )
        }
        // tuan code disable button Request Designs and  Designs Complete 
        // $("#save_and_edit").after(
        //     '&nbsp;<button type="button" id="sendMailToAdmin" class="button sendMailToAdmin" title="Request Designs" onClick="sendEmailToAdmin();" > Request Designs <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        // )
        //tuan add button Sanden Form
        // $("#sendRequestAddressSMS").after(
        //     '&nbsp;<a class="button moveSandenForm" href="https://pure-electric.com.au/pe-sanden-quote-form" target="_blank" style="color:white;padding:8.5px">SANDEN QUOTE FORM</a>'
        // )
        // $("#sendMailToAdmin").after(
        //     '&nbsp;<button type="button" id="sendDesignsComplete" class="button sendDesignsComplete" title="Designs Complete" onClick="sendEmailDesignsComplete();" > Designs Complete <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        // )

        $("#sendMailToAdmin").after(
            '&nbsp;<button data-record-id="'+$('input[name="record"]').val()+'" data-module-name="'+ $("#first_name").val() + ' ' + $("#last_name").val() +'" type="button" id="seekInstallDate" class="button seekInstallDate" title="Seek Install Date" data-module="Leads" onClick="SUGAR.seekInstallDate(this);" >Seek Install Date<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )
            //dung code - button seekInstallDate
        $("#seekInstallDate").after(
            '&nbsp;<button data-record-id="'+$('input[name="record"]').val()+'" data-module-name="'+ $("#first_name").val() + ' ' + $("#last_name").val() +'" type="button" id="seekDetails" class="button seekDetails" title="Seek Details" data-module="Leads" onClick="SUGAR.seekDetails(this);" >Seek Details<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )
        SUGAR.seekDetails = function(element) {
            $(document).openComposeViewModal_seekDetails(element);
        }
        SUGAR.sendRequestAddressSMS = function(element) {
            if($("#status").val() == 'Address_Requested') {
                var dialog_message = confirm('We sent Request Address before.Do you want send again ?');
                if(dialog_message == true){
                    $(document).openComposeViewModal_sendRequestAddressSMS(element);
                }else{
                    return false;
                }
            }else{
                $(document).openComposeViewModal_sendRequestAddressSMS(element);
            }
        }

        SUGAR.seekInstallDate = function(elem) {
            $(document).openComposeViewModal(elem);
            /*
            if ($("#primary_address_street").val() == '' ) {
                alert("Cannot send email No Street address entered");
                return;
            }

            $('#sendMailToAdmin span.glyphicon-refresh').removeClass('hidden');
            var _url = "?entryPoint=customLeadSendEmailToAdmin&record_id="
                        + $('input[name="record"]').val()
                        + "&primary_address_street=" + $("#primary_address_street").val()
                        + "&primary_address_city=" + $("#primary_address_city").val()
                        + "&primary_address_state=" + $("#primary_address_state").val()
                        + "&primary_address_postalcode=" + $("#primary_address_postalcode").val();

            $.ajax({
                url: _url,
                type: 'GET',

            }).done(function (data) {
                $('#sendMailToAdmin span.glyphicon-refresh').addClass('hidden');
            });
            $("#solargain_lead_number_c").val(data);
            $("#solargain_lead_number_c").trigger("change");  */
        }
        //thien fix
        SUGAR.updateQuoteToSolargain = function(elem) {
            $('#updateToQuotesSolarGain span.glyphicon-refresh').removeClass('hidden');
           // alert("ok");
            var build_url_quote = "?entryPoint=customUpdateQuoteToSolarGain";
            build_url_quote += '&record='+ encodeURIComponent($('input[name="record"]').val());
            build_url_quote += "&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
            build_url_quote += "&meter_number="+encodeURIComponent($("#meter_number_c").val());
            build_url_quote += "&meter_phase="+encodeURIComponent($("#meter_phase_c").val());
            build_url_quote += '&account_number='+ encodeURIComponent($("#account_number_c").val());
            build_url_quote += "&nmi_number="+encodeURIComponent($("#nmi_c").val());
            build_url_quote += "&name_on_billing_account="+encodeURIComponent($("#name_on_billing_account_c").val());
            build_url_quote += '&billing_name='+ encodeURIComponent($("#name_on_billing_account_c").val());
            build_url_quote += "&energy_retailer="+encodeURIComponent($("#energy_retailer_c").val());
            build_url_quote += "&distributor="+encodeURIComponent($("#distributor_c").val());
            build_url_quote += '&primary_address_street='+ encodeURIComponent($("#primary_address_street").val());
            build_url_quote += '&primary_address_city='+ encodeURIComponent($("#primary_address_city").val());
            build_url_quote += '&state='+ encodeURIComponent($("#primary_address_state").val());
            build_url_quote += '&postalcode='+ encodeURIComponent($("#primary_address_postalcode").val());
            build_url_quote += '&address_nmi='+ encodeURIComponent($("#address_nmi_c").val());
            build_url_quote += '&last_name='+ encodeURIComponent($("#last_name").val()) ;//
            build_url_quote += '&first_name='+ encodeURIComponent($("#first_name").val()) ;//
            build_url_quote += '&phone_work='+ encodeURIComponent($("#phone_work").val()) //
            var phone_number_customer = $('#phone_mobile').val();
            phone_number_customer = phone_number_customer.replace(/\s/g, '');
            build_url_quote += '&phone_mobile='+ encodeURIComponent(phone_number_customer); //
            build_url_quote += '&email='+ encodeURIComponent($("#Leads0emailAddress0").val());
            var customer_type = $('input[name=customer_type_c]:checked').val();
            build_url_quote += '&customer_type='+ encodeURIComponent(customer_type) ;

            var roof_type = $("#roof_type_c").val();
            if (roof_type == "klip_loc"){
                roof_type = "Klip Loc";
            }
            if (roof_type == "Trim_Deck"){
                roof_type = "Trim Deck";
            }

            if (roof_type == "Ground_Mount"){
                roof_type = "Ground Mount";
            }

            build_url_quote += '&roof_type='+ encodeURIComponent(roof_type);
            build_url_quote += '&build_height='+ encodeURIComponent($("#gutter_height_c").val());
            build_url_quote += '&connection_type='+ encodeURIComponent($("#connection_type_c").val());
            build_url_quote += '&main_type='+ encodeURIComponent($("#main_type_c").val());
            for(var i = 1; i<=6 ;i++){
                var travel_km    = $("#travel_km_"+i).val().trim();
                var price_option = $("#suggest_price_"+i).val().trim();
                var number_double_storey_panel = $("#number_double_storey_panel_"+i).val().trim();
                var groups_of_panels  = $("#groups_of_panels_"+i).val().trim();

                if(travel_km == ''){
                    travel_km = 0;
                }else if(parseInt(travel_km) > 50) {
                    travel_km = parseInt(travel_km) - 50;
                } else{
                    travel_km = 0;
                }
                if(number_double_storey_panel == ''){
                    number_double_storey_panel = 0;
                }
                if(parseInt(groups_of_panels) >= 2){
                    groups_of_panels = groups_of_panels - 2;
                }else{
                    groups_of_panels = 0;
                }

                build_url_quote += '&price_option_'+ i +'='+ encodeURIComponent(price_option);
                build_url_quote += '&travel_km_'+ i +'='+ encodeURIComponent(parseInt(travel_km));
                build_url_quote += '&number_double_storey_panel_'+ i +'='+ encodeURIComponent(parseInt(number_double_storey_panel));
                build_url_quote += '&groups_of_panels_'+ i +'='+ encodeURIComponent(parseInt(groups_of_panels));

            }

            $.ajax({
                url: build_url_quote,
                type : 'POST',
                success: function (data) {
                    $('#updateToQuotesSolarGain span.glyphicon-refresh').addClass('hidden');
                },
            });
        }

        SUGAR.pushToSolargain = function (elem) {

            if($("input[name='record']").val() == ''){
                alert('Please save this Lead before push to SG!');
                return;
            }

            if($("#solargain_lead_number_c").val() !== ""){
                SUGAR.convertToSolargainQuote(elem);
                return;
            }

            //dung code - new logic - auto save before push SolarGain
            $("#EditView input[name='action']").val('Save');
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                async: false, 
                success: function () {
                    console.log('Good');
                }
            });

            $('#convertToQuotesSolarGain span.glyphicon-refresh').removeClass('hidden');

            var build_url=  "?entryPoint=customCreateSolarGain";
            build_url += '&notes='+ encodeURIComponent($("#description").val()) ;
            build_url += '&record='+ encodeURIComponent($('input[name="record"]').val());
            //system_size_c
            build_url += '&system_size='+ encodeURIComponent($("#system_size_c").val()) ;
            build_url += '&unit_per_day='+ encodeURIComponent($("#units_per_day_c").val()) ;
            build_url += '&dolar_month='+ encodeURIComponent($("#dolar_month_c").val()) ;
            build_url += '&number_of_people='+ encodeURIComponent($("#number_of_people_c").val()) ;
            build_url += '&primary_address_state='+ encodeURIComponent($("#primary_address_state").val()) ;
            var customer_type = $('input[name=customer_type_c]:checked').val();
            build_url += '&customer_type='+ encodeURIComponent(customer_type) ;
            build_url += '&last_name='+ encodeURIComponent($("#last_name").val()) ;//
            build_url += '&first_name='+ encodeURIComponent($("#first_name").val()) ;//
            build_url += '&phone_work='+ encodeURIComponent($("#phone_work").val()) //
            //tu-code
            var phone_number_customer = $('#phone_mobile').val();
            phone_number_customer = phone_number_customer.replace(/\s/g, '');
            build_url += '&phone_mobile='+ encodeURIComponent(phone_number_customer); //
            build_url += '&email='+ encodeURIComponent($("#Leads0emailAddress0").val());//Leads0emailAddress0
            build_url += '&primary_address_street='+ encodeURIComponent($("#primary_address_street").val());
            build_url += '&primary_address_city='+ encodeURIComponent($("#primary_address_city").val());
            build_url += '&state='+ encodeURIComponent($("#primary_address_state").val());
            build_url += '&postalcode='+ encodeURIComponent($("#primary_address_postalcode").val());
            //build_height
            build_url += '&build_height='+ encodeURIComponent($("#gutter_height_c").val());
            var connection_type = $("#connection_type_c").val();
            if (connection_type == "Semi_Rural_Remote_Meter")  connection_type =  "Semi Rural/Remote Meter";
            build_url += '&connection_type='+ encodeURIComponent($("#connection_type_c").val());
            build_url += '&main_type='+ encodeURIComponent($("#main_type_c").val());
            //Semi Rural/Remote Meter
            /*
             "ConnectionType" =>	urldecode($_GET['connection_type']),
             "MeterNumber"	=> urldecode($_GET['meter_number']),
             "NMINumber"	=> urldecode($_GET['nmi_number']),
             "AccountNumber" =>	urldecode($_GET['account_number']),
             "BillingName"	=> urldecode($_GET['billing_name'],

             */

            build_url += '&meter_number='+ encodeURIComponent($("#meter_number_c").val());
            build_url += '&nmi_number='+ encodeURIComponent($("#nmi_c").val());
            build_url += '&account_number='+ encodeURIComponent($("#account_number_c").val());
            build_url += '&billing_name='+ encodeURIComponent($("#name_on_billing_account_c").val());
            build_url += '&energy_retailer='+ encodeURIComponent($("#energy_retailer_c").val());
            build_url += '&distributor='+ encodeURIComponent($("#distributor_c").val());
            //energy_retailer_c
            //distributor_c
            // Roof type
            var roof_type = $("#roof_type_c").val();
            if (roof_type == "klip_loc"){
                roof_type = "Klip Loc";
            }
            if (roof_type == "Trim_Deck"){
                roof_type = "Trim Deck";
            }

            if (roof_type == "Ground_Mount"){
                roof_type = "Ground Mount";
            }

            build_url += '&roof_type='+ encodeURIComponent(roof_type);

            //primary_address_postalcode
            //primary_address_street //primary_address_city
            $.ajax({
                url: build_url,
                //data : data,
                type : 'POST',
                //contentType: false,
                //processData: false,

                success: function (data) {
                    console.log(data);
                    leadID = data;

                    $("#solargain_lead_number_c").val(data);
                    $("#solargain_lead_number_c").trigger("change");
                    SUGAR.convertToSolargainQuote(elem);
                },
            });
        }

        //thienpb code - Update price to sg
        SUGAR.updateQuotePriceToSolargain = function(elem) {
            $('#updateToQuotesSolarGain_position_price span.glyphicon-refresh').removeClass('hidden');

            if($("#solargain_quote_number_c").val() == ''){
                alert('Please create a Quote on SolarGain before clicking this button.');
                ("#solargain_quote_number_c").focus();
                $('#updateToQuotesSolarGain_position_price span.glyphicon-refresh').addClass('hidden');
                return;
            }
            if($("#primary_address_state").val() == ''){
                alert('Please Enter State Field before clicking this button.');
                $("#primary_address_state").focus();
                $('#updateToQuotesSolarGain_position_price span.glyphicon-refresh').addClass('hidden');
                return;
            }
            var build_url_quote_price = "?entryPoint=customUpdateQuotePriceToSolarGain";
            build_url_quote_price += '&record='+ encodeURIComponent($('input[name="record"]').val());
            build_url_quote_price += "&quoteSG_ID="+encodeURIComponent($("#solargain_quote_number_c").val());
            build_url_quote_price += '&state='+ encodeURIComponent($("#primary_address_state").val());
            for(var i = 1; i<=6 ;i++){
                var price_option = $("#suggest_price_"+i).val().trim();
                build_url_quote_price += '&price_option_'+ i +'='+ encodeURIComponent(price_option);
            }
            
            $.ajax({
                url: build_url_quote_price,
                type : 'POST',
                success: function (data) {
                    $('#updateToQuotesSolarGain_position_price span.glyphicon-refresh').addClass('hidden');
                },
            });
        }
        //END Update price to sg

        if($("#solargain_lead_number_c").val() != "" && $("#convertToQuotesSolarGain").length == 0 ){
            leadID = $("#solargain_lead_number_c").val();
            $("#push_to_solargain").after(
                '&nbsp;<button type="button" id="convertToQuotesSolarGain" class="button convertToQuotesSolarGain" title="Convert To Solargain Quote" onClick="SUGAR.convertToSolargainQuote(this);" > Convert SG Quotes <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            )
        }

        SUGAR.convertToSolargainQuote = function (elem) {
            $('#convertToQuotesSolarGain span.glyphicon-refresh').removeClass('hidden');
            leadID = $("#solargain_lead_number_c").val();
            if (leadID == 0 ) return;
            var build_url=  "?entryPoint=customCreateSolarGainQuote";
            build_url += '&leadID='+ encodeURIComponent(leadID);
            var solargain_options = $('#solargain_options_c').val();
            if (solargain_options_c!=null && solargain_options.length > 0){
                build_url += '&sgoption='+ solargain_options.join();
            }

            build_url += '&record='+ encodeURIComponent($('input[name="record"]').val())
                        + "&primary_address_street=" + $("#primary_address_street").val()
                        + "&primary_address_city=" + $("#primary_address_city").val()
                        + "&primary_address_state=" + $("#primary_address_state").val()
                        + "&primary_address_postalcode=" + $("#primary_address_postalcode").val();

            //Thienpb code - Travel option and Price option
            for(var i = 1; i<=6 ;i++){
                var travel_km    = $("#travel_km_"+i).val().trim();
                var price_option = $("#suggest_price_"+i).val().trim();
                var number_double_storey_panel = $("#number_double_storey_panel_"+i).val().trim();
                var groups_of_panels  = $("#groups_of_panels_"+i).val().trim();

                if(travel_km == ''){
                    travel_km = 0;
                }else if(parseInt(travel_km) > 50) {
                    travel_km = travel_km - 50;
                }
                if(number_double_storey_panel == ''){
                    number_double_storey_panel = 0;
                }
                if(parseInt(groups_of_panels) >= 2){
                    groups_of_panels = groups_of_panels - 2;
                }else{
                    groups_of_panels = 0;
                }
                
                build_url += '&price_option_'+ i +'='+ encodeURIComponent(price_option);
                build_url += '&travel_km_'+ i +'='+ encodeURIComponent(parseInt(travel_km));
                build_url += '&number_double_storey_panel_'+ i +'='+ encodeURIComponent(parseInt(number_double_storey_panel));
                build_url += '&groups_of_panels_'+ i +'='+ encodeURIComponent(parseInt(groups_of_panels));

            }

            //thienpb code here
            // var suite_field = $('#distance_to_sg_c').val().trim().split(" ");
            // var travel_sg = 0;
            // if(suite_field[0] <= 50) {
            //     travel_sg = 0;
            // }else{
            //     travel_sg = suite_field[0] - 50;
            // }
            // build_url += '&suite_field='+travel_sg.toFixed(0);
            //end thienpb
            $.ajax({
                url: build_url,
                //data : data,
                type : 'POST',
                //contentType: false,
                //processData: false,

                success: function (data) {
                    console.log(data);
                    leadID = data;
                    $('#convertToQuotesSolarGain span.glyphicon-refresh').addClass('hidden');
                    $("#solargain_quote_number_c").val(data);
                    $("#solargain_quote_number_c").trigger("change");
                },
            });
        }


        // Send pdf to client - dung update new logic
            // $("#SAVE").after(
            //     '&nbsp;<button type="button" id="sendSolarGainPDF" class="button sendSolarGainPDF" title="Send Solargain PDF" onClick="SUGAR.sendSolarGainPDF(this);" >Send Solargain PDF<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            // )


        SUGAR.sendSolarGainPDF = function (elem) {
            var dialog_message = "<ul style='list-style-type: circle; padding: 0 40px;'>";
            if($('#solargain_quote_number_c').val() == '') 
            {
                dialog_message += "<li>Please create a Quote on SolarGain before clicking this button.</li>";
            }else {
                dialog_message += "<li>Email will be sent from SolarGain. Do you want to continue sending?</li>";
            }
                
            dialog_message += "</ul>";
           
            var dialog = $(dialog_message).dialog({
                buttons: {
                    "Yes": function() { 
                        if($('#solargain_quote_number_c').val() == '') {
                            dialog.dialog('close');
                            return;
                        }
                        
                        var number_quote = $('#solargain_quote_number_c').val();
                        $('#sendSolarGainPDF span.glyphicon-refresh').removeClass('hidden');
                        var record_id = $("input[name='record']").val();
                        var build_url=  "?entryPoint=sendSolargainQuotePDF";
                        build_url += '&solarQuoteID='+ encodeURIComponent(number_quote);
                        build_url += '&record_id='+ encodeURIComponent(record_id);
                        $.ajax({
                            url: build_url,
                            type : 'POST',
                            success: function (data) {
                                console.log(data);
                                var vals = data.time_sent_client.split(' ');
                        
                                var date = vals[0];
                                $('#time_sent_to_client_c_date').val(date);
                        
                                var times = vals[1].split(':');
                        
                                var hours = times[0];
                                
                                $('#time_sent_to_client_c_hours').val(hours);
                        
                                var minutes = times[1] - (times[1] % 15);
                                if (minutes == 0)
                                {
                                    minutes = '00';
                                }
                                $('#time_sent_to_client_c_minutes').val(minutes);
                        
                                $('#time_sent_to_client_c').val(date + ' ' + hours + ':' + minutes);
                                $('#status').val('Converted');
                                alert("Email PDF sent!");
                                $('#sendSolarGainPDF span.glyphicon-refresh').addClass('hidden');
                            },
                        });
                        dialog.dialog('close');
                    },
                    "Cancel":  function() {
                        dialog.dialog('close');
                        return;
                    }
                }
            });
            
        }
    })
});
$(document).ready(function(){
    var parent_selected =  $('#LeadsemailAddressesTable0').parent();
    $('#LeadsemailAddressesTable0').detach().appendTo(parent_selected);
    //tu-code auto minimise sub panel
    // function minimise_sub(){
    //     let sub_panel = $('body').find('div[class="panel-content"] div[class="panel panel-default"]');
    //     $.each(sub_panel,function(){
    //         let check = false;
    //         $(this).find("input[type='text']").each(function(){
    //             if ($(this).val() != '') {
    //                 check = true;
    //                 return false;
    //             }
    //         });
    //         if (check == false) {
    //             $(this).find('a[data-toggle="collapse-edit"]').addClass('collapsed')
    //             $(this).find('.panel-body.panel-collapse').removeClass('in')
    //         }
    //     });
    //     // for (i=0;i< sub_panel.length; i++) {
    //     //     if (sub_panel[i].getAttribute('style') != "display: none;") {

    //     //     }
    //     // }
    //     // $("#detailpanel_"+option).find("input[type='text']").each(function(){
    //     //     if($(this).val() != ''){ 
    //     //         check = true;
    //     //         return false;  
    //     //     }else{
    //     //         check = false;   
    //     //     }
    //     // });
    //     // if(check == false){
    //     //     $('#detailpanel_'+option).removeClass('in');
    //     //     $('#detailpanel_'+option).siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
    //     // }
    // }
    // for(var i = -1; i < 5;i++){
        // minimise_sub();
    // }
    //tu-code auto minimis solar PV pricing
    if($("#panel_type_1" ).val() == '' && $("#panel_type_1" ).val() == '' && $("#panel_type_1" ).val() == ''){
        if($("#panel_type_4" ).val() == '' && $("#panel_type_5" ).val() == '' && $("#panel_type_6" ).val() == ''){
            $('#detailpanel_5').removeClass('in');
            $('#detailpanel_5').siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
        }
    }
    if ($('#solar_pv_pricing_input_c').val() == '') {
            $('#detailpanel_6').removeClass('in');
            $('#detailpanel_6').siblings().find('a[data-toggle="collapse-edit"]').addClass('collapsed');
    }
    //tu-code auto click create Suite LEAD
    $('body').on('change','#primary_address_street, #primary_address_city,#primary_address_state,#primary_address_postalcode,#lead_source',function(){
        if($("#primary_address_street").val() != ''&& $("#primary_address_city").val() != ''&& $("#primary_address_state").val() != ''&& $("#primary_address_postalcode").val() != '' && $("#lead_source").val() == 'Solargain'){
            $("#create_solar_quote_c").prop('checked', true);
        }else{
            $("#create_solar_quote_c").prop('checked', false);
        }
    })
})
// Dung fix
$(function(){

    $( document ).ready(function() {
        var sgPrices = {
            "VIC":{
                "option1":7490,
                "option2":8590,
                "option3":10690,
                "option4":9790,
                "option5":11390,
                "option6":14590},
            "SA":{
                "option1":6890,
                "option2":7790,
                "option3":9490,
                "option4":8990,
                "option5":10590,
                "option6":13290},
            "NSW":{
                "option1":7290,
                "option2":8290,
                "option3":9990,
                "option4":9690,
                "option5":10990,
                "option6":14490},
            "ACT":{
                "option1":7890,
                "option2":8790,
                "option3":10590,
                "option4":9990,
                "option5":11590,
                "option6":14490},
            "QLD":{
                "option1":6390,
                "option2":7290,
                "option3":8990,
                "option4":8690,
                "option5":9990,
                "option6":12990}
            };
        var state_ =  $('#primary_address_state').val();
        if(typeof sgPrices[state_] !== 'undefined'){
            // do nothings

        }
        else {
            switch (state_.toLowerCase()) {
                case 'victoria':
                    state_ = 'VIC';
                    break;
                case 'queensland':
                    state_ = 'QLD';
                    break;
                case 'new south wales':
                    state_ = 'NSW' ;
                    break;
                case 'australian capital territory':
                    state_ = 'ACT';
                    break;
                case 'south australia':
                    state_ = 'SA';
                    break;
                default:
                    state_ = 'not value support';
                    break;
            }
        }
        var div_html = '';
        if(state_ !== 'not value support' ){
            for(i=1;i<=6;i++){
                div_html += 'Price option '+i+": "+sgPrices[state_]['option'+i]+'</br>';
            }
        }else {
            div_html = 'Not data for this state ';
        }

        $('#solargain_options_c').after("<div id='state_option_price' style='position: absolute;left: 150px;top:-5px;font-size:13px'>"+div_html+"</div>");

        // Thienpb code for get state option price 
        $(document).find('#state_option_price').after('</br><br> <button type="button" class="button primary" id="getSGPrice"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get SG Price </button>');
        var sg_quote = $("#solargain_quote_number_c").val();
        $("#getSGPrice").click(function(){
            $('#getSGPrice span.glyphicon-refresh').removeClass('hidden');
            $(".div_option").remove();
            if(sg_quote != ''){
                $.ajax({
                    url: '?entryPoint=customGetSolarGainStateOptionPrice&sg_quote_id='+sg_quote,
                    type : 'GET',
                    dataType : 'json',
                    success: function (data) {
                        $('#getSGPrice').after("<div class='div_option' style='position: absolute;left: 285px;top:-5px;font-size:13px'><div style='margin-right: 20px;float: left; font-weight: bold;font-size: 14px;'>SG Price:</div><div style='float:left;'>"+data.html+"</div><div class='clear'></div></div>");
                        $('#getSGPrice').after("<div class='div_option' style='position: absolute;left: 465px;top:-5px;font-size:13px'><div style='margin-right: 20px;float: left; font-weight: bold;font-size: 14px;'>Inverter Model:</div><div style='float:left;'>"+data.html_inverter+"</div><div class='clear'></div></div>");
                        if(data.error == 'error'){
                            alert("Different Inverter Model in Options");
                            $("#solargain_inverter_model_c").val('');
                        }else{
                            $("#solargain_inverter_model_c").val(data.model);
                        }
                        $('#getSGPrice span.glyphicon-refresh').addClass('hidden');
                    },
                })
            }else{
                alert("Solargain Quote Number is required!");
                $('#getSGPrice span.glyphicon-refresh').addClass('hidden');
            }
        })
        //dung code button 2 get price SG
        
        var sg_quote = $("#solargain_quote_number_c").val();
        $("#getSGPrice_table_price").click(function(){
            $('#getSGPrice_table_price span.glyphicon-refresh').removeClass('hidden');
            $(".sg_price_get").remove();
            if(sg_quote != ''){
                $.ajax({
                    url: '?entryPoint=customgetpricefromSG&sg_quote_id='+sg_quote,
                    type : 'GET',
                    dataType : 'json',
                    success: function (data) {
                        $('#Solar-PV-Pricing tbody').after(data.html);
                        $('#Solar-PV-Pricing tbody').after(data.html_inverter);
                        if(data.error == 'error'){
                            alert("Different Inverter Model in Options");
                            $("#solargain_inverter_model_c").val('');
                        }else{
                            $("#solargain_inverter_model_c").val(data.model);
                        }
                        $('#getSGPrice_table_price span.glyphicon-refresh').addClass('hidden');
                    },
                })
            }else{
                alert("Solargain Quote Number is required!");
                $('#getSGPrice_table_price span.glyphicon-refresh').addClass('hidden');
            }
        })

        //end

        var record_id_new = $("input[name='record']").val();
        var email_send_id = $("#email_send_id_c").val();
        if(email_send_id != ""){
            $('#email_send_id_c').after("<br><a target=blank href='/index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=Leads&return_action=DetailView&return_action=DetailView&record="+email_send_id+"'>Open email link</a>");
        }

    });
})
//Dung end fix

//Dung code - checkbox convert Lead
$(document).ready(function(){
    $('#create_sanden_quote_c ').on('click',function(){
        if($(this).prop('checked') == true){
            $('#create_sanden_c ,#create_sanden_quote_c,').prop('checked',true);
        }
        if($(this).is(":checked")){
            $("#sanden_315").val(1);
        } else {
            $("#sanden_315").val("");
        }
    });

    $('#create_methven_quote_c ').on('click',function(){
        if($(this).prop('checked') == true){
            $('#create_methven_c').prop('checked',true);
        }
    });

    $('#create_solar_quote_c ').on('click',function(){
        if($(this).prop('checked') == true){
            $('#create_solar_c').prop('checked',true);
        }
    });

    $('#create_daikin_quote_c ').on('click',function(){
        if($(this).prop('checked') == true){
            $('#create_daikin_c ,#create_daikin_quote_c, #sent_email_daikin_quote_c').prop('checked',true);
        }
        if($(this).is(":checked")){
            $("#daikin_25_pro").val(1);
        } else {
            $("#daikin_25_pro").val("");
        }
    });

    $('#open_new_tag_c').parent().parent().hide();
    //create link open quote
    var array_quote = ['create_daikin_quote_num_c','daikin_nexura_quote_num_c','create_sanden_quote_num_c','create_solar_quote_fqs_num_c','create_solar_quote_fqv_num_c','create_methven_quote_num_c','create_tesla_quote_num_c','create_solar_quote_num_c', 'create_off_grid_button_num_c'];
    function open_link_quote_from_field(array_quote){
        $('.link_open_quote').remove();
        $.each(array_quote,function(i,val){          
            if($('#'+val).val() != ''){
                $('#'+val).after('<br><a class="link_open_quote" target="_blank" href="/index.php?module=AOS_Quotes&action=EditView&record='+$('#'+val).val()+'">Open Link</a>');
            }
        }) 
    } 
    open_link_quote_from_field(array_quote);

    //VUT-S-Show link Service Case
    if ($('#service_case_number_c').val() != '') {
        $('#service_case_number_c + br').remove();
        $('.link_servicecase').remove();
        $('#service_case_number_c').after('<br><a class="link_servicecase" target="_blank" href="/index.php?module=pe_service_case&action=EditView&record='+$('#service_case_number_c').val()+'">Open Link</a>');
    }
    YAHOO.util.Event.addListener('service_case_number_c',"change", function(){
        $('#service_case_number_c + br').remove();
        $('.link_servicecase').remove();
        if ($('#service_case_number_c').val() != '') {
            $('#service_case_number_c').after('<br><a class="link_servicecase" target="_blank" href="/index.php?module=pe_service_case&action=EditView&record='+$('#service_case_number_c').val()+'">Open Link</a>');
        }
    });

    //VUT-E-Show link Service Case

    YAHOO.util.Event.addListener(array_quote,"change", function(e){
        open_link_quote_from_field(array_quote);
    });
    // // dung code -- add new column near Building Address
    // $("#phone_work").parent().parent().after('<div id="new_column_near_address_building" class="col-xs-12 col-sm-6 edit-view-row-item"><fieldset id="new_column_near_address_building"></fieldset></div>');
    // var array_id_div_move_to_new_columns = ['age_days_c','lead_source_co_c','lead_source','product_type_c'];
    // $.each(array_id_div_move_to_new_columns, function(k,v){
    //     var parent_id = $('#' + v ).parent().parent();
    //     parent_id.removeClass('col-sm-6');
    //     if(v == 'product_type_c'){
    //         parent_id.removeAttr('style');
    //     }
    //     $(parent_id).detach().appendTo('#new_column_near_address_building');
    // });
   
    //change multiple select to multiple checkbox
    var Object_value_option_product_type = [];
    var Html_multiple_select = '<div id="group_product_type_checkbox" style="position:relative;z-index:999;">';
    $('#product_type_c option').each(function(k,v){
        var label_option = $(this).attr('label');
        var value_option = $(this).attr('value');
        var value_select = $(this).is(":checked");
        Object_value_option_product_type[k]= [label_option,value_option,value_select];
        if(value_option != ''){
            Html_multiple_select += '<br><label><input'+((value_select)?' checked':'') +' type="checkbox" value="'+value_option+ '" class="group_checkbox_product_type_item"> '+label_option+'</label>';
        }
        
    });
  
    Html_multiple_select += '</div>';
    $('#product_type_c').after(Html_multiple_select);
   
    $('body').on('change','#group_product_type_checkbox',function(){
        debugger
        var array_group_checkbox_product_type =[];
        $('#group_product_type_checkbox .group_checkbox_product_type_item').each(function(k,v){
            var value_option = $(this).attr('value');
            var value_select = $(this).is(":checked");
            if(value_select){
                array_group_checkbox_product_type.push(value_option);
            }
        });
        $('#product_type_c').val(array_group_checkbox_product_type);
        //console.log(array_group_checkbox_product_type);
    });
   $("#product_type_c").hide();
    //hidden -- not use all field
    $('#create_daikin_c ,#create_daikin_number_c, #create_sanden_c, #create_sanden_number_c,#create_solar_c,#create_solar_number_c,#create_methven_c,#create_methven_number_c,#time_request_design_c,#seek_install_date_c,#time_accepted_job_c,#time_completed_job_c,#time_sent_to_client_c,#address_provided_c').closest('.edit-view-row-item').hide();
    $('#department,#phone_fax,#website,#requested_products_c,#title,#special_notes_c,#price_notes_c,#quote_date_c,#system_size_c,#dolar_month_c,#units_per_day_c,#number_of_people_c,#phone_num_registered_account_c,#jemena_account_c,#live_chat_c,#create_opportunity_c,#create_opportunity_number_c,#solargain_inverter_model_c').closest('.edit-view-row-item').hide();
})

//Dung code - create button send email in convert Lead

$(document).ready(function(){
    $.fn.openComposeViewModal_button = function (source) {
        "use strict";
        console.log("s");
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        console.log(record_id);
        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1' + ((record_id!="")? ("&lead_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):""),
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();

            // Populate fields
            if ($(source).attr('data-record-id') !== '') {
            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');
            var populateModuleName = $(source).attr('data-module-name');
            var populateEmailAddress = $(source).attr('data-email-address');

            if (populateModuleName !== '') {
                populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            }

            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);

            }
            $(self.emailComposeView).on('sentEmail', function (event, composeView) {
            composeBox.hide();
            composeBox.remove();
            });
            $(self.emailComposeView).on('disregardDraft', function (event, composeView) {
            if (typeof messageBox !== "undefined") {
                var mb = messageBox({size: 'lg'});
                mb.setTitle(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
                mb.setBody(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
                mb.on('ok', function () {
                mb.remove();
                composeBox.hide();
                composeBox.remove();
                });
                mb.on('cancel', function () {
                mb.remove();
                });
                mb.show();
            } else {
                if (confirm(self.translatedErrorMessage)) {
                composeBox.hide();
                composeBox.remove();
                }
            }
            });


            composeBox.on('cancel', function () {
            composeBox.remove();
            });
            composeBox.on('hide.bs.modal', function () {
            composeBox.remove();
            });
        }).fail(function (data) {
            composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
        });
        return $(self);
    };
    //tuan code ================
    $.fn.openComposeViewModal_sendinstalldate = function (source) {
        "use strict";
        console.log("s");
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        console.log(record_id);
        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1' + ((record_id!="")? ("&lead_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):""),
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();

            // Populate fields
            if ($(source).attr('data-record-id') !== '') {
            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');
            var populateModuleName = $(source).attr('data-module-name');
            var populateEmailAddress = $(source).attr('data-email-address');

            if (populateModuleName !== '') {
                populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            }

            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);

            }
            $(self.emailComposeView).on('sentEmail', function (event, composeView) {
            composeBox.hide();
            composeBox.remove();
            });
            $(self.emailComposeView).on('disregardDraft', function (event, composeView) {
            if (typeof messageBox !== "undefined") {
                var mb = messageBox({size: 'lg'});
                mb.setTitle(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
                mb.setBody(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
                mb.on('ok', function () {
                mb.remove();
                composeBox.hide();
                composeBox.remove();
                });
                mb.on('cancel', function () {
                mb.remove();
                });
                mb.show();
            } else {
                if (confirm(self.translatedErrorMessage)) {
                composeBox.hide();
                composeBox.remove();
                }
            }
            });


            composeBox.on('cancel', function () {
            composeBox.remove();
            });
            composeBox.on('hide.bs.modal', function () {
            composeBox.remove();
            });
        }).fail(function (data) {
            composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
            });
            return $(self);
        };
        // tuan code send email info pack alira
        $.fn.openComposeViewModal_sendInfoPackAlira = function (source) {
            "use strict";
            console.log("s");
            var self = this;
            self.emailComposeView = null;
            var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
            var composeBox = $('<div></div>').appendTo(opts.contentSelector);
            composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
            composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
            composeBox.show();
            var record_id= $(source).attr('data-record-id') ;
            var email_type = $(source).attr('data-email-type');
            console.log(record_id);
            $.ajax({
                type: "GET",
                cache: false,
                url: 'index.php?module=Emails&action=ComposeView&in_popup=1' + ((record_id!="")? ("&lead_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):""),
            }).done(function (data) {
                if (data.length === 0) {
                console.error("Unable to display ComposeView");
                composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
                return;
                }
                composeBox.setBody(data);
                self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();
    
                // Populate fields
                if ($(source).attr('data-record-id') !== '') {
                var populateModule = $(source).attr('data-module');
                var populateModuleRecord = $(source).attr('data-record-id');
                var populateModuleName = $(source).attr('data-module-name');
                var populateEmailAddress = $(source).attr('data-email-address');
    
                if (populateModuleName !== '') {
                    populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
                }
    
                $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
                $(self.emailComposeView).find('#parent_type').val(populateModule);
                $(self.emailComposeView).find('#parent_name').val(populateModuleName);
                $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
                $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
    
                }
                $(self.emailComposeView).on('sentEmail', function (event, composeView) {
                composeBox.hide();
                composeBox.remove();
                });
                $(self.emailComposeView).on('disregardDraft', function (event, composeView) {
                if (typeof messageBox !== "undefined") {
                    var mb = messageBox({size: 'lg'});
                    mb.setTitle(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
                    mb.setBody(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
                    mb.on('ok', function () {
                    mb.remove();
                    composeBox.hide();
                    composeBox.remove();
                    });
                    mb.on('cancel', function () {
                    mb.remove();
                    });
                    mb.show();
                } else {
                    if (confirm(self.translatedErrorMessage)) {
                    composeBox.hide();
                    composeBox.remove();
                    }
                }
                });
    
    
                composeBox.on('cancel', function () {
                composeBox.remove();
                });
                composeBox.on('hide.bs.modal', function () {
                composeBox.remove();
                });
            }).fail(function (data) {
                composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
                });
                return $(self);
            };    
        // TUAN GET LEAD SOURSE SG CRM
        $("select[name='how_did_you_hear_about_pe_c']").on("change", function(){
            GET_lead_sourse_SG(this)
        });
        function GET_lead_sourse_SG(e){
            // $("select[name='sg_lead_source_c']").hide();
            var select_source = $("select[name='sg_lead_source_c']");
            var Units = $(e).val();
            if(Units == "blank"){
                $("select[name='sg_lead_source_c']").val("blank");
                return
            }
            $.ajax({
                url: "?entryPoint=SG_lead_sources&units_id="+Units+"&record_id="+$("input[name='record']").val(),
                type : 'POST',
            }).done(function (data) {
                var lead_source = jQuery.parseJSON(data);
                select_source.empty();
                for( var i = 0; i < lead_source.length; i++ ){
                    if( lead_source[i]['lead_source'] != undefined){
                        $("select[name='sg_lead_source_c']").val(lead_source[i]['lead_source']);
                    }else {
                        select_source.append("<option label='"+lead_source[i]["Description"]+"' value='" + lead_source[i]["Description"] + "'>" + lead_source[i]["Description"] + "</option>");
                    }
                }
                // $("select[name='sg_lead_source_c']").show();
            });
        }
        if($("select[name='how_did_you_hear_about_pe_c']").val() != "_blank"){
            GET_lead_sourse_SG( $("select[name='how_did_you_hear_about_pe_c']") );
        }
    var record_id = $("input[name='record']").val();
    var full_name = $("#first_name").val() +' ' + $('#last_name').val();
    var to_email = $("#Leads0emailAddress0").val();
        $('#create_daikin_quote_c').after('<span>5.0</span> <select name="daikin_50_pro" id="daikin_50_pro" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_daikin_quote_c').after('<span>3.5</span> <select name="daikin_35_pro" id="daikin_35_pro" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_daikin_quote_c').after('<span>2.5</span> <select name="daikin_25_pro" id="daikin_25_pro" ><option value=""></option><option selected="selected" value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');

        $('#create_daikin_nexura_quote_c').after('<span>4.8</span> <select name="daikin_nexura_48" id="daikin_nexura_48" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_daikin_nexura_quote_c').after('<span>3.5</span> <select name="daikin_nexura_35" id="daikin_nexura_35" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_daikin_nexura_quote_c').after('<span>2.5</span> <select name="daikin_nexura_25" id="daikin_nexura_25" ><option value=""></option><option selected="selected" value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');

        $('#create_sanden_quote_c').after('<span>160</span> <select name="sanden_160" id="sanden_160" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_sanden_quote_c').after('<span>250</span> <select name="sanden_250" id="sanden_250" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_sanden_quote_c').after('<span>315</span> <select name="sanden_315" id="sanden_315" ><option value=""></option><option selected="selected" value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');

        $('#create_sanden_quote_c').after( '&nbsp;<button data-email-type="first-sanden" type="button" id="send_sanden" class="button" title="Popup Send Email Sanden" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'" onClick="$(document).openComposeViewModal_button(this);">Email<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        $('#create_daikin_quote_c').after( '&nbsp;<button data-email-type="first-daikin" type="button" id="send_daikin" class="button" title="Popup Send Email Daikin" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'" onClick="$(document).openComposeViewModal_button(this);">Email<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        $('#create_daikin_nexura_quote_c').after( '&nbsp;<button data-email-type="nexura-design" type="button" id="send_daikin_nexura" class="button" title="Popup Send Email Daikin Nexura" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'" onClick="$(document).openComposeViewModal_button(this);">Email<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');

        $('#create_solar_quote_fqs_c').after('<span>250</span> <select name="sanden_fqs_250" id="sanden_fqs_250" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_solar_quote_fqs_c').after('<span>300</span> <select name="sanden_fqs_300" id="sanden_fqs_300" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_solar_quote_fqs_c').after('<span>315</span> <select name="sanden_fqs_315" id="sanden_fqs_315" ><option value=""></option><option selected="selected" value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_solar_quote_fqs_c').after( '&nbsp;<button data-email-type="first-sanden" type="button" id="send_sanden_fqs" class="button" title="Popup Send Email Sanden" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'" onClick="$(document).openComposeViewModal_button(this);">Email<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        $('#create_solar_quote_fqs_c').parent().append( '<br><span>Supply Only:</span><input type="checkbox" id="check_box_supply_only_fqs" name="check_box_supply_only_fqs" value="1" title="" tabindex="0">');
        $('#create_solar_quote_fqs_c').parent().append( '&nbsp<span>VEECs:</span><input type="checkbox" id="check_box_veec" name="check_box_veec" value="1" title="" tabindex="0">');
        // tuan remove  Sanden numbers for FQV
        // $('#create_solar_quote_fqv_c').after('<span>160</span> <select name="sanden_fqv_160" id="sanden_fqv_160" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        // $('#create_solar_quote_fqv_c').after('<span>250</span> <select name="sanden_fqv_250" id="sanden_fqv_250" ><option value=""></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_solar_quote_fqv_c').after('<span>315</span> <select name="sanden_fqv_315" id="sanden_fqv_315" ><option value=""></option><option selected="selected" value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select>');
        $('#create_solar_quote_fqv_c').after( '&nbsp;<button data-email-type="first-sanden" type="button" id="send_sanden_fqv" class="button" title="Popup Send Email Sanden" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'" onClick="$(document).openComposeViewModal_button(this);">Email<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        $('#create_solar_quote_fqv_c').parent().append( '<br><span>Supply Only:</span><input type="checkbox" id="check_box_supply_only_fqv" name="check_box_supply_only_fqv" value="1" title="" tabindex="0">');
        //tuan code
        $('#seek_install_date_c_minutes').after( '&nbsp;<button data-email-type="send-install-date" type="button" id="send_install_date" class="button" title="Popup Send Email Install Date" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'" onClick="$(document).openComposeViewModal_sendinstalldate(this);">Send<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        // check postcode water
        $('#primary_address_postalcode').after( '&nbsp;<button  type="button" id="check_water_quality" class="button primary" title="Check Your Water Quality">CHECK WATER QUALITY<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        $('#primary_address_postalcode').css('width','35%');
        $('#check_water_quality').click(function () {
            $('#check_water_quality span.glyphicon-refresh').removeClass('hidden');
            var postcode_num = $('#primary_address_postalcode').val();
            $('#link-sanden-hot-water').remove();
            $.ajax({
                url: "?entryPoint=customCheckPostalCodeSandenWater&postcode_num="+postcode_num,
                type : 'POST',
                success: function (data) {
                    $('#check_water_quality span.glyphicon-refresh').addClass('hidden');
                    if( data == "There are no known water quality issues with this postcode."){
                        $('#check_water_quality').after('<br><a target="_blank" id="link-sanden-hot-water" title="Link Sanden Hot Water" href="https://www.sanden-hot-water.com.au/check-your-water-quality?r=549&postcode=' + postcode_num +'">Yes good !</a>')
                    }else {
                        $('#check_water_quality').after('<br><a target="_blank" id="link-sanden-hot-water" title="Link Sanden Hot Water" href="https://www.sanden-hot-water.com.au/check-your-water-quality?r=549&postcode=' + postcode_num +'">FQV !</a>');
                    }
                },
            });
        })
})

//dung code - button Get ALl Files
$(document).ready(function(){
    // $("#btn_view_change_log").after(
    //     ' <button type="button" data-email-type="InfoPackAlira" data-email-address="'+$('#Leads0emailAddress0').val()+'" data-record-id="'+$('input[name="record"]').val()+'" data-module-name="'+ $("#first_name").val() + ' ' + $("#last_name").val() +'" type="button" class="button infopackalira" data-module="Leads"  title="Email Info Pack Daikin Alira" onClick="$(document).openComposeViewModal_sendInfoPackAlira(this);">Info Pack Daikin Alira</button>'
    // );
    $("#subpanel_leads_pe_internal_note_1 tr").each(function() {
        var recordId = getParameterByName('record', $(this).find("td:nth-child(4) a").attr('href'));
        var module_name = getParameterByName('module', $(this).find("td:nth-child(4) a").attr('href'));
        var url = "/index.php?entryPoint=customLeadHoverEmail&record=" + recordId + "&module=" + module_name ;
        if(recordId != null) {
            $(this).find("td:nth-child(3)").append('<a class="various fancybox.ajax" data-fancybox-type="ajax" href="' + url + '"> Preview</a>');
        }   
    });

    $('.various').fancybox({
        maxWidth	: 800,
        maxHeight	: 600,
        fitToView	: false,
        width		: '70%',
        height		: '70%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });

    if(module_sugar_grp1 == 'Leads'){
        $("#fileupload").prepend('<button type="button" id="get_all_files_leads" class="button primary" title="Get All File"> Get All Files From Email And SMS<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        $("#get_all_files_leads").click(function(){
            $('#get_all_files_leads span.glyphicon-refresh').removeClass('hidden');
            var lead_id =  $('input[name="record"]').val();
            var pre_install_photos_c = $("input[name='installation_pictures_c']").val();
            if(lead_id !== '' ) {
                $.ajax({
                    url: "?entryPoint=getAllFilesAttachments&lead_id="+lead_id+"&module=Leads"+"&pre_install_photos_c="+pre_install_photos_c,
                    success: function(data)
                    {
                        $(".files").empty();                
                        $.ajax({
                            url: $('#fileupload').fileupload('option', 'url'),
                            dataType: 'json',
                            context: $('#fileupload')[0]
                        }).always(function () {
                            $(this).removeClass('fileupload-processing');
                
                        }).done(function (result) {
                            $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                            $('#get_all_files_leads span.glyphicon-refresh').addClass('hidden');
                        });
                    }
                });
            }else {
                alert('Not have number ID Leads. Please save and edit before.');
                $('#get_all_files_leads span.glyphicon-refresh').addClass('hidden');
            }

            //trigger event get all file from SMS
            $("#get_all_files_message_app").trigger('click');
        });

        //thienpb code
        $("#get_all_files_leads").after('<button type="button" id="get_files_from_s3_lead" class="button primary" title="Get Files From S3">Get Files From S3<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        $("#get_files_from_s3_lead").click(function(){
            $('#get_files_from_s3 span.glyphicon-refresh').removeClass('hidden');
            var quote_id =  $('input[name="record"]').val();
            if(quote_id == ''){
                alert('Not have number ID Quotes. Please save and edit before.');
                $('#get_files_from_s3_lead span.glyphicon-refresh').addClass('hidden');
                $("input[name='button']").first().focus();
                return;
            }
            var pre_install_photos_c = $("input[name='installation_pictures_c']").val();

            var url_download = "https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/s3.php?myDirectoryName="+pre_install_photos_c;
            $.ajax({
                url: url_download,
                async:false,
                success: function(data){
                    $(".files").empty();
                    $.ajax({
                        url: $('#fileupload').fileupload('option', 'url'),
                        dataType: 'json',
                        context: $('#fileupload')[0],
                    }).always(function () {
                        $(this).removeClass('fileupload-processing');
                    }).done(function (result) {
                        $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                        $('#get_files_from_s3_lead span.glyphicon-refresh').addClass('hidden');
                    });           
                }
            })

        });

        //VUT-Disable input 
        $(document).find('#age_days_c').attr('disabled', 'disabled');
        setTimeout(function() {
            var record_id = $('body').find('input[name="record"]').val().trim();
            $.ajax({
                url: "?entryPoint=customGetAgeDays&record_id="+record_id,
                type: 'GET',
                success: function(data){
                // debugger;
                if(data == '') {return;}
                else {$('#age_days_c').val(data);}
                // var date = $.parseJSON(data);   
                // $('#lead_age_c').val(date.lead_age_c);
                //     var start = new Date(data),
                //     end   = new Date(),
                //     diff  = new Date(end - start),
                //     days  = Math.ceil(diff/1000/60/60/24);
                //    $('#age_days_c').val(days);
                }
            })
        }, 3000)
        //dung code - button get link realestate 
        $('#EditView_account_name').parent().append('<button type="button" id="get_link_realestate_and_domain" class="button primary" >Realestate + Domain<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        $('#link_realestate_address_c').parent().parent().hide();
        if( $('#link_realestate_address_c').val() !== ''){
            $('#get_link_realestate_and_domain').parent().append('<br><a id="open_link_realestate" target="_blank" href="'+$('#link_realestate_address_c').val() +'">Open link Realestate</a>');
        };     
        $("#get_link_realestate_and_domain").click(function(){
            $("#get_link_realestate_and_domain  span.glyphicon-refresh").removeClass('hidden');
            var address = $("#primary_address_street").val() + " " + $("#primary_address_city").val() + " " + $("#primary_address_state").val() + " " + $("#primary_address_postalcode").val() ;     
            address = address.toLowerCase().replace(/ /g, '-');
            var record_id = $("input[name='record']").val();
            $.ajax({
                url: "?entryPoint=Button_Get_Link_Realestate&record_id="+record_id+"&address="+address,
                success: function(data){
                    if(data !== 'Not Find Address On Realestate'){
                        $('#link_realestate_address_c').val(data);
                        if( $('#link_realestate_address_c').val() !== ''){
                            $("#open_link_realestate").remove();
                            $('#get_link_realestate_and_domain').parent().append('<br><a id="open_link_realestate" target="_blank" href="'+$('#link_realestate_address_c').val() +'">Open link Realestate</a>');
                        };
                        $("#get_link_realestate_and_domain  span.glyphicon-refresh").addClass('hidden');
                    }else {
                        $('#link_realestate_address_c').val('');
                        $("#open_link_realestate").remove();
                        alert(data);
                        $("#get_link_realestate_and_domain  span.glyphicon-refresh").addClass('hidden');
                    }     
                }
            })
        })

        
     // button Update Related
     
        $("#btn_clr_assigned_user_name").after('<button type="button" style="width: 150px;" id="update_relates" class="button update_relates">Update Related <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        $("#assigned_user_name").parent().before('<a target="_blank" href="https://pure-electric.com.au/change_assigned_user">LINK CHANGE DEFAULT ASSIGN USER PE</a>');
        $("#update_relates").click(function(event){
            event.preventDefault();
            var assigned_id = $("#assigned_user_id").val();
            var record = $("input[name='record']").val();
            if(record == '') return false;
            $('#update_relates span.glyphicon-refresh').removeClass('hidden');
            $.ajax({
                url: "/index.php?entryPoint=customUpdateRelated&bean_type=Leads&record="+record +"&assigned_id="+assigned_id,
                type: 'GET',
                success: function(data)
                {
                    $('#update_relates span.glyphicon-refresh').addClass('hidden');
                },
                error: function(response){console.log("Fail");},
            });
            return false;
        });
        
    
        //dung code - button get link domain 
       // $('#EditView_account_name').parent().append('<br><button type="button" id="get_link_domain" class="button primary" > Get Link Domain<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
       //thienpb comment because it is not working 
       $('#link_domain_address_c').parent().parent().hide();
        if( $('#link_domain_address_c').val() !== ''){
            $('#get_link_realestate_and_domain').parent().append('<br><a id="open_link_domain" target="_blank" href="'+$('#link_domain_address_c').val() +'">Open link Domain</a>');
        };     
        $("#get_link_realestate_and_domain").click(function(){
            var address = $("#primary_address_street").val() + " " + $("#primary_address_city").val() + " " + $("#primary_address_state").val() + " " + $("#primary_address_postalcode").val() ;     
            address = address.toLowerCase().replace(/ /g, '_');
            var record_id = $("input[name='record']").val();
            $.ajax({
                url: "?entryPoint=Button_Get_Link_Domain&record_id="+record_id+"&address="+address,
                success: function(data){
                    if(data !== 'Not Find Address On Domain'){
                        $('#link_domain_address_c').val(data);
                        if( $('#link_domain_address_c').val() !== ''){
                            $("#open_link_domain").remove();
                            $('#get_link_realestate_and_domain').parent().append('<br><a id="open_link_domain" target="_blank" href="'+$('#link_domain_address_c').val() +'">Open link Domain</a>');
                        }; 
                    }else {
                        $('#link_domain_address_c').val('');
                        $("#open_link_domain").remove();
                        alert(data);
                    }     
                }
            })
        })
    }
    
    //Thiencode - button get all image of Options Solargain
    $("#get_all_files_leads").after('<button type="button" id="get_sg_image_options" class="button primary" title="Get ALL File From SG">Get ALL File From SG<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
    $("#get_sg_image_options").click(function(){
        $('#get_sg_image_options span.glyphicon-refresh').removeClass('hidden');
        
        var lead_id =  $('input[name="record"]').val();
        if(lead_id == ''){
            alert('Not have number ID Leads. Please save and edit before.');
            $('#get_sg_image_options span.glyphicon-refresh').addClass('hidden');
            $("input[name='button']").first().focus();
            return;
        }

        var pre_install_photos_c = $("input[name='installation_pictures_c']").val();
        var quote_sg = $("#solargain_quote_number_c").val();
        setTimeout(function(){
            if($('#solargain_quote_number_c').val() !== ''){
                var url_download = "?entryPoint=CustomDownloadPDF&type=quote&record_id="
                + $('input[name="record"]').val()
                + '&quote_solorgain='+$('#solargain_quote_number_c').val();
                $.ajax({
                    url: url_download,
                    async:false,
                    success: function(data){
                        $(".files").empty();               
                    }
                })
            }

            if($('#solargain_tesla_quote_number_c').val() !== ''){
                $('#get_sg_image_options span.glyphicon-refresh').removeClass('hidden');
                var url_download = "?entryPoint=CustomDownloadPDF&type=tesla&record_id="
                + $('input[name="record"]').val()
                + '&quote_solorgain='+$('#solargain_tesla_quote_number_c').val();
                $.ajax({
                    url: url_download,
                    type: 'GET',
                    async:false,
                    success: function(data){
                        $(".files").empty();
                    }
                });
            }

            if(quote_sg !== '' ) {
                $.ajax({
                    url: "?entryPoint=downloadSGImageOptions&quote_solorgain="+quote_sg+"&pre_install_photos_c="+pre_install_photos_c,
                    async:false,
                    success: function(data)
                    {
                        $(".files").empty();
                        $.ajax({
                            url: $('#fileupload').fileupload('option', 'url'),
                            dataType: 'json',
                            context: $('#fileupload')[0],
                        }).always(function () {
                            $(this).removeClass('fileupload-processing');
                        }).done(function (result) {
                            $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                            $('#get_sg_image_options span.glyphicon-refresh').addClass('hidden');
                        });
                    }
                });
            }
        },100);

    });
   
    //dung code - check field distance before save
    $('#SAVE').attr('onclick','return false;');
    $('#SAVE').click(function(e){
       var pt=/^([A-Za-z ])+$/;
       var txt = $('#first_name').val();
       var check_email = check_email_before_convert(); 
        if( pt.test(txt) == false && txt != ""){
            alert ('Please - Change First_name: "&" to "And"');
            return false;
        }
        else if(check_email == 'error'){
            alert ('Cloud you check email before convert quote!');
            return false;
        }
        else {
            //dung code  --- check exist lead before save 
            $.ajax({
                url: "?entryPoint=CustomCheckExistLeadBeforeSave",
                method: "POST",
                async :false,
                data :{first_name :$('#first_name').val(),
                        last_name : $("#last_name").val(),
                    address_email : $('#Leads0emailAddress0').val(),
                    record_id : $('input[name="record"]').val(),
                        },
                success: function(data)
                {
                    if(data !== 'Not Exist') {
                        var dialog_message = 
                        '<p>List Leads Same frist name , last name and email address :<br>'
                        + data   
                        + "Do you want continue Save ?</p>" ;  
                                
                        var dialog = $(dialog_message).dialog({
                            buttons: {
                                "Yes": function() { 
                                    dialog.dialog('close');
                                    check_exist_distance_field();       
                                },
                                "Cancel":  function() {
                                    dialog.dialog('close');
                                    return;
                                }
                            }
                        });
                    }else {
                        check_exist_distance_field();
                    }
                }
            });
        }
    })

    // check exist distance field
    function check_exist_distance_field() {
        var distance_value = $("#distance_to_sg_c").val().replace('km','').replace(',','').trim();
        distance_value = distance_value.substring(0, distance_value.indexOf('.'));
        distance_value = parseInt(distance_value);
        // if($("#distance_to_sg_c").val() == ''){
        //        //tu-code
        //        var dialog_message = confirm('Get null distance field, Do you want to continue');
        //        if(dialog_message == true){
        //            var _form = document.getElementById('EditView');
        //            _form.action.value='Save';
        //         //    $("#updateToQuotesSolarGain").trigger('click');
        //            SUGAR.ajaxUI.submitForm(_form);
        //            return check_form('EditView');
        //        }else{
        //            $("#distance_to_sg_c").focus();
        //            return false;
        //        }
        // }else 
        if(distance_value > 100){
            var dialog_message = '<span>Distance greater than 100 kms please speak to the sales consultant</span>';
            var dialog = $(dialog_message).dialog({
                buttons: {
                    "Yes": function() {
                        dialog.dialog('close');
                        var _form = document.getElementById('EditView');
                        _form.action.value='Save';
                        // $("#updateToQuotesSolarGain").trigger('click');
                        SUGAR.ajaxUI.submitForm(_form);
                        return check_form('EditView');
                    },
                    "Cancel":  function() {
                        dialog.dialog('close');
                    }
                }
            });
        }     
        else{
            var _form = document.getElementById('EditView');
            _form.action.value='Save';
            // $("#updateToQuotesSolarGain").trigger('click');
            SUGAR.ajaxUI.submitForm(_form);
            return check_form('EditView');
        }
    }

    //function auto select Lead Source CO is PE when not have value
    function auto_select_lead_source(){
        if($("#lead_source_co_c").val() == '') {
            $("#lead_source_co_c").val('PureElectric');
        }
    }
    auto_select_lead_source();
    //thienpb code - custom rename file
    $(".fileupload-buttonbar").find(".delete").after('<button type="button" id="rename_files" class="button primary" title="Rename files">Rename Files<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
    $("#rename_files").click(function(){

        $('#rename_files span.glyphicon-refresh').removeClass('hidden');
        var installation_pictures_c = $("input[name='installation_pictures_c']").val();
        var primary_address_street = $("#primary_address_street").val();
        var primary_address_city = $("#primary_address_city").val();
        var distributor_c = $("#distributor_c").val();
        var record = $('input[name="record"]').val();
        var files = $("#file_rename_c").val();

        $.ajax({
            url: "?entryPoint=customRenameFileUpload",
            method: "POST",
            data :{installation_pictures_c : installation_pictures_c,
                   primary_address_street : primary_address_street,
                   primary_address_city : primary_address_city,
                   distributor_c : distributor_c,
                   record : record,
                   files :  files},
            success: function(data)
            {
                $(".files").empty();
                    $.ajax({
                        url: $('#fileupload').fileupload('option', 'url'),
                        dataType: 'json',
                        context: $('#fileupload')[0]
                    }).always(function () {
                        $(this).removeClass('fileupload-processing');
                    }).done(function (result) {
                        $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                        $('#rename_files span.glyphicon-refresh').addClass('hidden');
                    });
            }
        });
    });

    //dung code - button get all file in message app
    if(module_sugar_grp1 == 'Leads'){
        $("#fileupload").prepend('<button type="button" hidden id="get_all_files_message_app" class="button primary" title="Get All File"> Get Files From SMS<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        $("#get_all_files_message_app").click(function(){
            $('#get_all_files_message_app span.glyphicon-refresh').removeClass('hidden');
            var lead_id =  $('input[name="record"]').val();
            var pre_install_photos_c = $("input[name='installation_pictures_c']").val();
            if(lead_id !== '' ) {
                $.ajax({
                    url: "?entryPoint=getAllFilesMessageApp&lead_id="+lead_id+"&pre_install_photos_c="+pre_install_photos_c,
                    success: function(data)
                    {
                        $(".files").empty();                
                        $.ajax({
                            url: $('#fileupload').fileupload('option', 'url'),
                            dataType: 'json',
                            context: $('#fileupload')[0]
                        }).always(function () {
                            $(this).removeClass('fileupload-processing');
                
                        }).done(function (result) {
                            $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                            $('#get_all_files_message_app span.glyphicon-refresh').addClass('hidden');
                        });
                    }
                });
            }else {
                alert('Not have number ID Leads.');
                $('#get_all_files_message_app span.glyphicon-refresh').addClass('hidden');
            }
        });

    }

    //dung code -- button send solar design
    // tuan REMOVE BUTTON send_solar_design
    // $("#CANCEL").after(
    //     '&nbsp;<button type="button" id="send_solar_design" class="button send_solar_design" title="send_solar_design" data-email-type="solar_design_complete" onclick="$(document).openComposeViewModal_sendSolarDesign(this);" data-module="Leads"' 
    //     +' data-record-id="'+ $("input[name='record']").val()
    //     +'" data-module-name="' + $("#first_name").val() + ' ' +$("#last_name").val()
    //     +'" data-email-address="' + $("#Leads0emailAddress0").val()
    //     +'" data-sg-inverter-model="'+ $("#solargain_inverter_model_c").val()
    //     +'" >Send Solar Design <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
    // ) 
    
    //check box sync address in Lead from billing address to site detail address
    
    
    function syncFieldsBillingQuotes(check){
        if(check){
            $("#site_detail_addr__c").val($("#primary_address_street").val());    
            $("#site_detail_addr__city_c").val($("#primary_address_city").val());         
            $("#site_detail_addr__state_c").val($("#primary_address_state").val());
            $("#site_detail_addr__postalcode_c").val($("#primary_address_postalcode").val());
            $("#site_detail_addr__country_c").val($("#primary_address_country").val());
        }
    }
    $("body").on("click", "#check_addr_site_detail_c", function(){
        
        syncFieldsBillingQuotes($(this).is(":checked"));
    });

    $("#primary_address_country").parent().parent().after('<tr><td scope="row" nowrap="">Copy to Site Details:</td><td><input id="checkbox_copy_address_to_site_details" name="alt_checkbox" type="checkbox"></td></tr>');
    $("body").on("click", "#checkbox_copy_address_to_site_details", function(){    
        syncFieldsBillingQuotes($(this).is(":checked"));
    });

    //hiden section  MORE INFORMATION , SOLARGAIN INFOMATION
    $('body').find('div[class="panel-content"] div[class="panel panel-default"]').each(function(){
        var name_panel = $(this).find('.panel-heading').text().trim().toUpperCase();
        var id_panel = $(this).find('.panel-body').attr('id').trim();
        
        switch (name_panel) {
            case 'MORE INFORMATION':
                $('#'+id_panel).parent().hide();
                break;
            case 'SOLARGAIN INFOMATION':
                $('#'+id_panel).parent().hide();
                break;
            default:
                break;
        }
    });

/**
 * Function Upload Image In Detail (Start)
 */

                    if(module_sugar_grp1 == 'Leads') {
                        var folder_id  = $('body').find('input[name="installation_pictures_c"]').val();
                        $('body').find('#block_image_site_detail').remove();
                        $(document).find('#popup_image_site_detail').remove();
                        var url_img = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime();

                        var image_html_site_details = '<div id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:198px;height:auto;margin-bottom:5px;text-align:center;" >Map Template Image</div>';

                        var img_html ='<div id="block_image_site_detail"><div class="clear"></div><div class="clear"></div><div class="col-xs-12 edit-view-row-item image_site_detail"><div class="col-md-6 col-sm-6 col-xs-6"> \
                                        <div class="col-md-12 col-sm-12 col-xs-12" id="maptemplate-img">' 
                                        + image_html_site_details + 
                                        '</div> \
                                        <div class="col-md-12 col-sm-12 col-xs-12"> \
                                            <button type="button" id="Upload_Image_Site_Detail" class="button" title="Upload Image Site Detail" >Upload</button> \
                                        </div> \
                                        <input style="display:none;" type="file" name="image_site_detail" id="image_site_detail" "/> \
                                    </div></div></div>';
                        
                        var popup_image_html = '<div style="display:none;position:fixed;z-index:100000;background:burlywood;width:100%;height:100%;text-align:center;top:0;" id="popup_image_site_detail">'
                        +'<button onclick="hidePopup()" id="popup_bottom_close" type="button" class="btn btn-info">X</button>'                   
                        +'<div style="height:100%;width:100%;padding:5%;" id="popup_image_site_detail_image">'
                        + '<div id="Map_Template_Image_popup"></div>'
                        +'</div>'
                        +'</div>';
                        $('body').after(popup_image_html);
                        $('body').find('#solargain_tesla_quote_number_c').parent().parent().after(img_html);
                    }
                    YAHOO.util.Event.addListener("Upload_Image_Site_Detail", "click", function(){
                        $('body').find('#image_site_detail').trigger('click');
                    });

                    YAHOO.util.Event.addListener("image_site_detail",'change',function(){ 
                        var fd = new FormData(); 
                        var files = $('#image_site_detail')[0].files[0]; 
                        fd.append('file', files); 

                        $.ajax({ 
                            url: '?entryPoint=Image_Site_Details&id='+ $('body').find('input[name="installation_pictures_c"]').val(), 
                            type: 'post', 
                            data: fd, 
                            contentType: false, 
                            processData: false, 
                            success: function(response){ 
                                if(response != 0){ 
                                    var folder_id  = $('body').find('input[name="installation_pictures_c"]').val();
                                    $('body').find("#Map_Template_Image").remove();
                                    var image_html = '<img id="Map_Template_Image" onclick="showPopup()" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:220px;height:auto;margin-top: 5em;margin-bottom:5px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime()+'">';
                                    $('body').find("#maptemplate-img").append(image_html);

                                    document.getElementById("Map_Template_Image_popup").src = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime();
                                    //reload list files
                                    $(".files").empty();
                                    $.ajax({
                                        url: $('#fileupload').fileupload('option', 'url'),
                                        dataType: 'json',
                                        context: $('#fileupload')[0]
                                    }).always(function () {
                                        $(this).removeClass('fileupload-processing');
                            
                                    }).done(function (result) {
                                        $('button[type="resize_all"]').trigger('click');
                                    });
                                } 
                            }, 
                        }); 
                    })
                if(module_sugar_grp1 == 'Leads') {
                    setTimeout(function() {
                        var address_site = $("#site_detail_addr__c").val()+','+$("#site_detail_addr__city_c").val()+','+$("#site_detail_addr__state_c").val()+','+$("#site_detail_addr__postalcode_c").val();  
                        var group_address_install = '<div id="group_address_site_detail" class="col-xs-12 edit-view-row-item col-sm-6">';
                        group_address_install += '<fieldset> <legend> Site Address </legend></fieldset>';
                        group_address_install += '</div>';
                        $(".image_site_detail").children().before(group_address_install);
                        $("#site_detail_addr__c").before(
                            '<div style="background-color: white;border:1px solid;display:none;position:absolute; padding:3px;margin-top:-16px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_site" class="show-open-map hide_map">'+
                                '<ul>'+
                                '<li><a style="cursor:pointer;" onclick="openSiteMap(); return false;">Google Maps</a></li>'+
                                '<li><a style="cursor:pointer;"  href="http://maps.nearmap.com?addr='+ address_site +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                                '</ul>'+
                            '</div>'
                        );
                        $("#site_detail_addr__c").parent().parent().parent().children().each(function(index,item){
                        
                            if(index >=12 && index <= 28){
                                    if($(this).has('div').length){
                                        $(this).removeClass('col-sm-6').detach().appendTo("#group_address_site_detail");
                                    }else{
                                        $(this).hide();
                                    }
                            }
                        });
                        $(document).find('#check_addr_site_detail_c').parent().detach().appendTo("#group_address_site_detail");
                        $('body').find('#solargain_tesla_quote_number_c').closest('.edit-view-row-item').after('<div id="div_for_distance_to_sg_c"><div class="clear"></div></div>');
                        // $("#distance_to_sg_c").closest(".edit-view-row-item").detach().appendTo("#div_for_distance_to_sg_c");
                        var parent_solargain_tesla_quote_number_c =  $('#solargain_tesla_quote_number_c').parent().parent();
                        $("#solargain_quote_number_c").parent().parent().detach().insertAfter(parent_solargain_tesla_quote_number_c);
                        $("#site_detail_addr__c").parent().parent().before('<div class="col-xs-12 edit-view-field"> <label>Copy address from billing address:</label><input id="check_addr_site_detail_c" name="check_addr_site_detail_c"  type="checkbox"></div>');
                        $("#group_address_site_detail fieldset").after($("#open_map_popup_site").parent().parent().removeClass("col-sm-6")); //Nhat code https://trello.com/c/ISA6X0yY/
                    }, 3000)
                }
            //THIEN FIX
                $("body").find("#Upload_Image_Site_Detail").after('<button type="button" id="import_map" class="button" >Import</button>');
                $("#Upload_Image_Site_Detail").before(
                    '<div style="background-color: white;border: 1px solid #9E9E9E;position: absolute;padding: 3px 3px 0px 3px;margin-top: 0px;left: 205px;z-index: 999;width: 150px;display:none" id="import_button">'+
                        '<ul>'+
                        '<li><button type="button" id="open_map_google" style= "width:100%" class="button" >Map Google</button></li>'+
                        '<li><button type="button" id="open_nearmap" style= "width:100%" class="button" >Near Map</button></li>'+
                        '</ul>'+
                    '</div>'
                );
                $("#import_map").click(function(){
                    $('#import_button').fadeToggle()
                });
                //get image from google map 
                $("#open_map_google").click(function(e){
                    $(this).closest("#import_button").hide();
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    $.ajax({
                        url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' 
                        + encodeURIComponent($('#site_detail_addr__c').val()) +", " 
                        + encodeURIComponent($('#site_detail_addr__city_c').val())  + ", " 
                        + encodeURIComponent($('#site_detail_addr__state_c').val())  
                        + ", " +  encodeURIComponent($('#site_detail_addr__postalcode_c').val()) 
                        + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
                        type: 'GET',
                        success: function(result) {
                            if (result.status == "OK"){
                                var location = result.results[0].geometry.location;
                                $.ajax({
                                    url: "index.php?entryPoint=Image_Site_Details_Get_From_Google&lat="
                                    +  location.lat
                                    + "&lng="  + location.lng,
                                    type: 'GET',
                                    success: function(result) {
                                        $("#Map_Template_Image").hide();
                                        $("#map").hide();
                                        $("#Map_Template_Image").after(result);
                                        $("#download").remove();
                                        $('#import_map').after("<button type='button' class='button'  id='download' onclick='CopyToClipboard()'>Save</button>");                            
                                        SUGAR.ajaxUI.hideLoadingPanel();
                                    }
                                });
                            }
                            else{
                                SUGAR.ajaxUI.hideLoadingPanel();
                            }

                        }
                    }); 
                });

                //get image from nearmap
                $("#open_nearmap").click(function(e){
                    $(this).closest("#import_button").hide();
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    $.ajax({
                        url: "index.php?entryPoint=Image_Site_Details_Get_From_Nearmap&quoteSG_ID="
                        + $("#solargain_quote_number_c").val()
                        + "&siteDetail_ID="+$("#sg_site_details_no_c").val(),
                        type: 'GET',
                        success: function(result) {
                            $("#Map_Template_Image").hide();
                            $("#map").hide();
                            $("#Map_Template_Image").after(result);
                            $("#download").remove();
                            $('#import_map').after("<button type='button' class='button'  id='download' onclick='CopyToClipboard()'>Save</button>");                            
                            var _DRAGGGING_STARTED = 0;
                            var _LAST_MOUSE_POSITION = { x: null, y: null };
                            var _DIV_OFFSET = $(document).find("#map").offset();
                            var _CONTAINER_WIDTH = $(document).find("#map").outerWidth();
                            var _CONTAINER_HEIGHT =$(document).find("#map").outerHeight();
                            var _IMAGE_WIDTH;
                            var _IMAGE_HEIGHT;
                            var _IMAGE_LOADED = 0;

                            // Check whether image is cached or wait for the image to load 
                            // This is necessary before calculating width and height of the image
                            if($(document).find("#drag-image").get(0).complete) {
                            ImageLoaded();
                            }
                            else {
                            $(document).find("#drag-image").on('load', function() {
                                ImageLoaded();
                            });
                            }

                            // Image is loaded
                            function ImageLoaded() {
                            _IMAGE_WIDTH = $(document).find("#drag-image").width();
                            _IMAGE_HEIGHT = $(document).find("#drag-image").height();
                            _IMAGE_LOADED = 1;
                            $(document).find("#drag-image").css({ top: 'calc(-50% - 100px)', left: 'calc(-50% - 100px)'});
                            }

                            $(document).find("#map").on('mousedown', function(event) {
                            /* Image should be loaded before it can be dragged */
                            if(_IMAGE_LOADED == 1) { 
                                _DRAGGGING_STARTED = 1;

                                /* Save mouse position */
                                _LAST_MOUSE_POSITION = { x: event.pageX - _DIV_OFFSET.left, y: event.pageY - _DIV_OFFSET.top };
                            }
                            });

                            $(document).find("#map").on('mouseup', function() {
                            _DRAGGGING_STARTED = 0;
                            });

                            $(document).find("#map").on('mousemove', function(event) {
                            if(_DRAGGGING_STARTED == 1) {
                                var current_mouse_position = { x: event.pageX - _DIV_OFFSET.left, y: event.pageY - _DIV_OFFSET.top };
                                var change_x = current_mouse_position.x - _LAST_MOUSE_POSITION.x;
                                var change_y = current_mouse_position.y - _LAST_MOUSE_POSITION.y;

                                /* Save mouse position */
                                _LAST_MOUSE_POSITION = current_mouse_position;

                                var img_top = parseInt($(document).find("#drag-image").css('top'), 10);
                                var img_left = parseInt($(document).find("#drag-image").css('left'), 10);

                                var img_top_new = img_top + change_y;
                                var img_left_new = img_left + change_x;

                                /* Validate top and left do not fall outside the image, otherwise white space will be seen */
                                if(img_top_new > 0)
                                img_top_new = 0;
                                if(img_top_new < (_CONTAINER_HEIGHT - _IMAGE_HEIGHT))
                                img_top_new = _CONTAINER_HEIGHT - _IMAGE_HEIGHT;

                                if(img_left_new > 0)
                                img_left_new = 0;
                                if(img_left_new < (_CONTAINER_WIDTH - _IMAGE_WIDTH))
                                img_left_new = _CONTAINER_WIDTH - _IMAGE_WIDTH;

                                $(document).find("#drag-image").css({ top: img_top_new + 'px', left: img_left_new + 'px' });
                            }
                            });
                            SUGAR.ajaxUI.hideLoadingPanel();
                        }
                    });
                });
                // auto import image map
                if( $('#primary_address_street').val() != "" && $('#site_detail_addr__c').val() == "" ){
                    $('#site_detail_addr__c').val($('#primary_address_street').val());
                    $('#site_detail_addr__city_c').val($('#primary_address_city').val());
                    $('#site_detail_addr__state_c').val($('#primary_address_state').val());
                    $('#site_detail_addr__postalcode_c').val($('#primary_address_postalcode').val());
                    $('#site_detail_addr__country_c').val($('#primary_address_country').val());
                    $('#import_map').trigger('click'); 
                    $('#open_map_google').trigger('click');
                    setTimeout(function(){ 
                        $('body').find('#download').trigger('click');  
                    },4000)
                }
            //END

/**
 * Function Upload Image In Detail (END)
 */

});
function getParameterByName(name, url) {
    if (!url) return null;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
(function ($) {
    $.fn.openComposeViewModal_sendSolarDesign = function (source) {
        "use strict";
       
        var self = this;
        
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        
        //thienpb code
        var sg_inverter_model = $(source).attr('data-sg-inverter-model');
        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1' + ((record_id!="")? ("&lead_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((sg_inverter_model!='')?("&inverter_model="+sg_inverter_model):""),
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();

            // Populate fields
            if ($(source).attr('data-record-id') !== '') {
            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');
            var populateModuleName = $(source).attr('data-module-name');
            var populateEmailAddress = $(source).attr('data-email-address');

            if (populateModuleName !== '') {
                populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            }

            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_id"]').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_module"]').val(populateModule);
            // dung code - add checkbox Convert Solar Opportunity for popup "Send Solar Design"
            if(email_type == 'solar_design_complete'){
                var html_checkbox_Convert_Solar_Opportunity = 
                '<div class="col-xs-12 col-sm-12 edit-view-row-item">'
                + '<div class="col-xs-12 col-sm-2 label" data-label="">'
                + 'Convert Solar Opportunity:</div>'
                + '<div class="col-xs-12 col-sm-8 edit-view-field " type="bool" field="send_sms" colspan="3">'
                + ' <input type="hidden" name="send_sms" value="0"> '
                + '<input type="checkbox" checked id="Convert_Solar_Opportunity" name="Convert_Solar_Opportunity" value="1" title="" tabindex="0">'                  
                +'</div>'
                +'</div>';
                $(self.emailComposeView).find('#EditView_tabs .tab-content .edit-view-row').append(html_checkbox_Convert_Solar_Opportunity);
            }
            if(email_type == 'solar_design_complete'){
                var number_quote_number = $('#solargain_quote_number_c').text().trim();
            }else{
                var number_quote_number = $('#solargain_tesla_quote_number_c').text().trim();
            }
            if(typeof(number_quote_number) == 'undefined' || number_quote_number == ''
            || typeof(module_sugar_grp1) == 'undefined' || module_sugar_grp1 == ''
            || typeof(action_sugar_grp1) == 'undefined' || action_sugar_grp1 == ''){
            
            }else {
                if(module_sugar_grp1 == 'Leads' && action_sugar_grp1 == 'DetailView'){
                    var href = "<a target='_blank' href='https://crm.solargain.com.au/quote/edit/" + number_quote_number +"'>Open Quote Solargain</a>";
                    $(self.emailComposeView).find('#parent_type').parent().parent().append(href);
                }
            }
            
            }
            $(self.emailComposeView).on('sentEmail', function (event, composeView) {
            composeBox.hide();
            composeBox.remove();
            });
            $(self.emailComposeView).on('disregardDraft', function (event, composeView) {
            if (typeof messageBox !== "undefined") {
                var mb = messageBox({size: 'lg'});
                mb.setTitle(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
                mb.setBody(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
                mb.on('ok', function () {
                mb.remove();
                composeBox.hide();
                composeBox.remove();
                });
                mb.on('cancel', function () {
                mb.remove();
                });
                mb.show();
            } else {
                if (confirm(self.translatedErrorMessage)) {
                composeBox.hide();
                composeBox.remove();
                }
            }
            });


            composeBox.on('cancel', function () {
            composeBox.remove();
            });
            composeBox.on('hide.bs.modal', function () {
            composeBox.remove();
            });
        }).fail(function (data) {
            composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
        });
        return $(self);
    };
}(jQuery));

function showPopup(){
    $('#popup_image_site_detail').show();
}
function hidePopup(){
    $('#popup_image_site_detail').hide();
}
function CopyToClipboard(){
    if($(document).find('#map').length == 0)return;
    window.onbeforeunload = null;
    setTimeout(function(){
        html2canvas($(document).find('#map'), {
            useCORS: true,
            allowTaint:false,
            onrendered: function(canvas) {
                document.body.appendChild(canvas);
                if(canvas.width == 0 && canvas.height == 0){
                    console.log("can't create canvas.");
                    return false;
                }
                var image = Canvas2Image.convertToPNG(canvas);
                var image_data = $(image).attr('src');
                var generateUUID = $('input[name="installation_pictures_c"]').val();
                $.ajax({
                    type: "POST", 
                    url: "index.php?entryPoint=Image_Site_Details_Clipboard_Popup", 
                    data: { img: image_data, id: generateUUID}      
                    }).done(function(data){
                        var folder_id  = $('body').find('input[name="installation_pictures_c"]').val();
                        $('body').find("#Map_Template_Image").remove();
                        $('body').find("#nearmap").remove();
                        var image_html = '<img id="Map_Template_Image" onclick="showPopup()" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:220px;height:auto;margin-top: 5em;margin-bottom:5px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime()+'">';
                        $('body').find("#maptemplate-img").append(image_html);
                        document.getElementById("Map_Template_Image_popup").src = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+folder_id+'/Image_Site_Detail.jpg?'+ new Date().getTime();
                        //reload list files
                        $(document).find("#map").remove();
                        $(document).find("#download").remove();
                        $('#install_address_c').focus();
                       
                    });
                document.body.removeChild(canvas);
            }
        });
    },100);
}

function check_email_before_convert(){
    var msg_error = '';
    var email = $("#Leads0emailAddress0").val();
    var re = /\S+@\S+\.\S+/;
    var array_checkbox =  ['create_daikin_quote_c','create_daikin_nexura_quote_c','create_solar_quote_fqs_c','create_solar_quote_fqv_c','create_methven_quote_c','create_solar_quote_c','create_tesla_quote_c'];
    var action_convert_lead = false;
    $.each(array_checkbox,function(i,v){
        if($('#'+v).is(':checked')){
            action_convert_lead = true;
        }
    });
    if(email == ''){
        if(action_convert_lead) {
            msg_error = 'error';
        }else{
            msg_error = 'success';
        }
    }else{
        if(re.test(email)){
            msg_error = 'success';    
        }else{
            msg_error = 'error';        
        }
    }  
   
    return msg_error;
}

$(document).ready(function(){
    // .:nhantv:. Add customer form link
    generateCustomerLink();

    var lead_id = $("input[name=lead_id]").val();;
    //thienpb code -- API Generate to quote design
    $("#btn_view_change_log").before('<button style="margin: 10px 0;background:#945596;" type="button" id="btn_lead_design_tool" class="button btn_lead_design_tool" title="Lead Design Tool">Lead Design Tool</button>');
    //$("#btn_view_change_log").before('<button style="margin: 10px 3px;background:#009acf;" type="button" id="btn_pe_daikin_form" class="button btn_pe_daikin_form" title="PE Daikin Form">Daikin Quote Form</button>');
    // $("#btn_view_change_log").before('<button style="margin: 10px 3px;background:#945596;" type="button" id="btn_pe_sanden_form" class="button btn_pe_sanden_form" title="PE Sanden Form">Sanden Quote Form</button>');
    $("#btn_view_change_log").before('<button style="margin: 10px 3px;background:#945596;" type="button" id="btn_pe_sanden_form_new" class="button btn_pe_sanden_form_new" title="PE Sanden Form">Sanden Quote Form</button>');
    $("#btn_view_change_log").before('<button style="margin: 10px 3px;background:#f48c21;" type="button" id="btn_pe_solar_form" class="button btn_pe_solar_form" title="PE Solar Form">Solar Quote Form</button>');
    $("#btn_view_change_log").before('<button style="margin: 10px 3px;background:#009acf;" type="button" id="btn_pe_daikin_new_form" class="button btn_pe_daikin_new_form" title="PE Daikin Form">Daikin Quote Form </button>');
    //thienpb code
    $("#btn_view_change_log").before('<button style="margin: 10px 3px;background:#945596;" type="button" id="btn_solar_design" class="button btn_solar_design" title="Solar Design">Solar Design</button>');

    $("#btn_pe_daikin_new_form").click(function(e) {
        window.open(
            'https://pure-electric.com.au/pedaikinform-new/master?lead-id='+lead_id,
            '_blank' // <- This is what makes it open in a new window.
        );
    });
    $("#btn_pe_daikin_form").click(function(e) {
        window.open(
            'https://pure-electric.com.au/pedaikinform?lead-id='+lead_id,
            '_blank' // <- This is what makes it open in a new window.
        );
    });
    $("#btn_pe_sanden_form").click(function(e) {
        window.open(
            'https://pure-electric.com.au/pesandenform?lead-id='+lead_id,
            '_blank' // <- This is what makes it open in a new window.
        );
    });
    $("#btn_pe_sanden_form_new").click(function(e) {
        window.open(
            'https://pure-electric.com.au/pe-sanden-quote-form/master?lead-id='+lead_id,
            '_blank' // <- This is what makes it open in a new window.
        );
    });
    $("#btn_pe_solar_form").click(function(e) {
        window.open(
            'https://pure-electric.com.au/pesolarform/master?lead-id='+lead_id,
            '_blank' // <- This is what makes it open in a new window.
        );
    });
    $("#btn_lead_design_tool").click(function(e) {
        var dsg_lat,dsg_lng;
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' 
            + encodeURIComponent($('#site_detail_addr__c').val()) +", " 
            + encodeURIComponent($('#site_detail_addr__city_c').val())  + ", " 
            + encodeURIComponent($('#site_detail_addr__state_c').val())  
            + ", " +  encodeURIComponent($('#site_detail_addr__postalcode_c').val()) 
            + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
            type: 'GET',
            success: function(result) {
                var location = result.results[0].geometry.location;
                if (result.status == "OK"){
                    dsg_lat = location.lat;
                    dsg_lng = location.lng;
                }
            }
        });
        // $.ajax({
        //     type: "GET",
        //     cache: false,
        //     url: "?entryPoint=getLeadFromAccount&account_id="+$("#billing_account_id").val()+'&lead_id='+$("#leads_aos_quotes_1leads_ida").val(),
        // }).done(function (data) {
        // var json = $.parseJSON(data);
        var roof_type_arr ={"Tin"       :2,
                        "Tile"          :3,
                        "klip_loc"      :4,
                        "Concrete"      :5,    
                        "Trim_Deck"     :6,
                        "Insulated"     :7,
                        "Asbestos"      :8,
                        "Ground_Mount"  :9,
                        "Terracotta"    :10,
                        "Other"         :1};
        var data_lead = {
            "ID"            : parseInt($("div[field='number']").text().trim('')),
            "Name"          : html_entity_decode($("#first_name").val() +' '+ $("#last_name").val()),
            "FirstName"     : html_entity_decode($("#first_name").val()),
            "LastName"      : html_entity_decode($("#last_name").val()),
            "Mobile"        : html_entity_decode($("#phone_mobile").val()),
            "Email"         : html_entity_decode($("#Leads0emailAddress0").val()),
            "Address"       :{
                "ID"                    :1,
                "Street1"               :html_entity_decode($("#site_detail_addr__c").val()),
                "Locality"              :html_entity_decode($("#site_detail_addr__city_c").val()),
                "State"                 :html_entity_decode($("#site_detail_addr__state_c").val()),
                "PostCode"              :parseInt(html_entity_decode($("#site_detail_addr__postalcode_c").val())),
                "Street1NotProvided"    :false,
                "LocalityNotProvided"   :false,
                "Latitude"              :dsg_lat,
                "Longitude"             :dsg_lng,
                "Value"                 :$("#site_detail_addr__c").val()+', '+$("#site_detail_addr__city_c").val()+' '+$("#site_detail_addr__state_c").val()+' '+$("#site_detail_addr__postalcode_c").val(),
            },
            "RoofType"      : encodeURIComponent(roof_type_arr[$("#roof_type_c").val()]),
            "CustomerTypeID": parseInt($("#customer_type_c").val()),
            "BuildHeight"   : $("#gutter_height_c").val(),
            "EnergyRetailer": $("#energy_retailer_c").val(),
            "NetworkOperator"   : $("#distributor_c").val(),
            "ConnectionType": $("#connection_type_c").val(),
            "Photo"         : {
                "Name"      : "Image_Site_Detail.jpg",
                "Data"      : $("#Map_Template_Image").attr("src"),
            }
        }
            $.ajax({
                type: "GET",
                cache: false,
                //url: "http://loc.designtools.com/APIv2/SAM_API.php?query=leads/"+$("div[field='number']").text().trim('')+'&data_lead='+JSON.stringify(data_lead),
                url: "https://designtool.pure-electric.com.au/APIv2/SAM_API.php?query=leads/"+$("div[field='number']").text().trim('')+'&data_lead='+JSON.stringify(data_lead),
                success: function(result) {
                    if(result == 'done'){
                        //window.open("http://loc.designtools.com/lead.php?leadID="+$("div[field='number']").text().trim(''));
                        window.open("https://designtool.pure-electric.com.au/lead.php?leadID="+$("div[field='number']").text().trim(''));
                    }
                }
            })

    });
    $("#btn_solar_design").on('click',function(e) {
        // .:nhantv:. Update link to Jarod's Solar Design Tool
        window.open(
            'https://main.d3djjyr2awjnsi.amplifyapp.com/',
            '_blank' // <- This is what makes it open in a new window.
        );

        // var address = [$("#primary_address_street").val(),$("#primary_address_city").val()+' '+$("#primary_address_state").val(),$("#primary_address_postalcode").val(),'Australia'];
        // address = address.join(', ');
        // var first_name = $("#first_name").val();
        // var family_name = $("#last_name").val();
        // var email = $("#Leads0emailAddress0").val();
        // var phone = $("#phone_mobile").val().replace(/ /g,'');
        // window.open(
        //     'https://solardesign.pure-electric.com.au/#/projects/create?addressSearch='+address+'&first_name='+first_name+'&family_name='+family_name+'&email='+email+'&phone='+phone,
        //     '_blank' // <- This is what makes it open in a new window.
        // );
    });
    //});
    //end
        
    // dung code --- function remove break line in description
    function remove_break_line_textarea(){
        var textarea = $("#description").val();
        $("#description").val(textarea.replace(/\n{3,}/g, "\r\n"));
        autosize.update($("#description"));
    }
    autosize($('#description'));
    remove_break_line_textarea();
    $("#description").on('change',function(){
        remove_break_line_textarea();
    });

    //show link Account sub panel PROPOSED INSTALLER
    showLinkAccount();
    YAHOO.util.Event.addListener(["proposed_plumber_acccount_id","proposed_electrician_acccount_id","proposed_daikin_installer_acccount_id", "proposed_solar_installer_acccount_id"], "change", showLinkAccount);
});

// .:nhantv:. Generate customer's link
function generateCustomerLink(){
    const lead_id = $("input[name=lead_id]").val();
    var strAppend = '';
    // case 'quote_type_sanden':
    strAppend = '<div><span><strong>Customer Sanden Form Link: </strong></span><span id="customer_path">' +
        'https://pure-electric.com.au/pe-sanden-quote-form?lead-id=' + lead_id +
        '</span><button type="button" onclick="clip_aboard(\'customer_path\')">Copy Path</button></div>';
    $('#absolute_path').next().after(strAppend);
    // case 'quote_type_solar':
    strAppend = '<div><span><strong>Customer Solar Form Link: </strong></span><span id="customer_path">' +
        'https://pure-electric.com.au/pesolarform?lead-id=' + lead_id +
        '</span><button type="button" onclick="clip_aboard(\'customer_path\')">Copy Path</button></div>';
    $('#absolute_path').next().after(strAppend);
    // case 'quote_type_daikin':
    strAppend = '<div><span><strong>Customer Daikin Form Link: </strong></span><span id="customer_path">' +
        'https://pure-electric.com.au/pedaikinform-new?lead-id=' + lead_id +
        '</span><button type="button" onclick="clip_aboard(\'customer_path\')">Copy Path</button></div>';
    $('#absolute_path').next().after(strAppend);
}

function showLinkAccount() {
    let plumber_account_id = $('#proposed_plumber_acccount_id');
    let electrician_account_id = $('#proposed_electrician_acccount_id');
    let daikin_installer_account_id = $('#proposed_daikin_installer_acccount_id');
    let solar_installer_account_id = $('#proposed_solar_installer_acccount_id');

    $("#link_proposed_plumber_acccount").remove();
    if (plumber_account_id.val() != '') {
        plumber_account_id.parent().append("<p id='link_proposed_plumber_acccount'><a  href='/index.php?module=Accounts&action=EditView&record=" + plumber_account_id.val() + "' target='_blank'>Open Account</a></p>");
    }

    $("#link_proposed_electrician_acccount").remove();
    if (electrician_account_id.val() != '') {
        electrician_account_id.parent().append("<p id='link_proposed_electrician_acccount'><a  href='/index.php?module=Accounts&action=EditView&record=" + electrician_account_id.val()+ "' target='_blank'>Open Account</a></p>");
    }

    $("#link_proposed_daikin_installer_acccount").remove();
    if (daikin_installer_account_id.val() != '') {
        daikin_installer_account_id.parent().append("<p id='link_proposed_daikin_installer_acccount'><a  href='/index.php?module=Accounts&action=EditView&record=" + daikin_installer_account_id.val()+ "' target='_blank'>Open Account</a></p>");
    }

    $("#link_proposed_solar_installer_acccount").remove();
    if (solar_installer_account_id.val() != '') {
        solar_installer_account_id.parent().append("<p id='link_proposed_solar_installer_acccount'><a  href='/index.php?module=Accounts&action=EditView&record=" + solar_installer_account_id.val()+ "' target='_blank'>Open Account</a></p>");
    }

    $('.display_link_contact_proposed_account').remove();
    $.ajax({
        url: "?entryPoint=getContactFromAccount&request=custom_display_link_contact_plum_elec_quote&sanden_electrician_id=" + electrician_account_id.val()+"&sanden_installer_id="+plumber_account_id.val()+"&daikin_installer_id="+daikin_installer_account_id.val()+"&solar_installer_id="+solar_installer_account_id.val(),
   }).done(function (data) {
        if(data == '' || typeof data == 'undefined')return;
       var json = $.parseJSON(data);
       
       $("#link_proposed_plumber_acccount").remove();
       if (plumber_account_id.val() != '') {
            plumber_account_id.parent().append("<p id='link_proposed_plumber_acccount'><a  href='/index.php?module=Accounts&action=EditView&record=" + plumber_account_id.val() + "' target='_blank'>Open Account</a></p>");
           if(json.sanden_installer_contact != '')  plumber_account_id.parent().append("<p class='display_link_contact_proposed_account' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.sanden_installer_contact+ "' target='_blank'>Open Primary Contact</a><input type='hidden' id='sanden_installer_contact_id' value='"+json.sanden_installer_contact+"'></p>");
       }

       $("#link_proposed_electrician_acccount").remove();
       if (electrician_account_id.val() != '') {
            electrician_account_id.parent().append("<p id='link_proposed_electrician_acccount'><a  href='/index.php?module=Accounts&action=EditView&record=" + electrician_account_id.val()+ "' target='_blank'>Open Account</a></p>");
           if(json.sanden_electrician_contact != '') electrician_account_id.parent().append("<p class='display_link_contact_proposed_account' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.sanden_electrician_contact+ "' target='_blank'>Open Primary Contact</a><input type='hidden' id='sanden_electrician_contact_id' value='"+json.sanden_electrician_contact+"'></p>");
       }

       $("#link_proposed_daikin_installer_acccount").remove();
       if (daikin_installer_account_id.val() != '') {
            daikin_installer_account_id.parent().append("<p id='link_proposed_daikin_installer_acccount'><a  href='/index.php?module=Accounts&action=EditView&record=" + daikin_installer_account_id.val()+ "' target='_blank'>Open Account</a></p>");
            if(json.daikin_installer_contact != '') daikin_installer_account_id.parent().append("<p class='display_link_contact_proposed_account' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.daikin_installer_contact+ "' target='_blank'>Open Primary Contact</a><input type='hidden' id='daikin_installer_contact_id' value='"+json.daikin_installer_contact+"'></p>");
       }

       $("#link_proposed_solar_installer_acccount").remove();
       if (solar_installer_account_id.val() != '') {
        solar_installer_account_id.parent().append("<p id='link_proposed_solar_installer_acccount'><a  href='/index.php?module=Accounts&action=EditView&record=" + solar_installer_account_id.val()+ "' target='_blank'>Open Account</a></p>");
            if(json.solar_installer_contact != '') solar_installer_account_id.parent().append("<p class='display_link_contact_proposed_account' ><a  href='/index.php?module=Contacts&action=EditView&record=" + json.solar_installer_contact+ "' target='_blank'>Open Primary Contact</a><input type='hidden' id='daikin_installer_contact_id' value='"+json.daikin_installer_contact+"'></p>");
       }

    });


}

function get_distance_by_account_id(id_account){
    if(id_account == '') return '';
    if( $('#site_detail_addr__c').val() == "" ){  
        var from_address =  $("#primary_address_street").val() +", " +
                            $("#primary_address_city").val() + ", " +
                            $("#primary_address_state").val() + ", " +
                            $("#primary_address_postalcode").val();
     
    }else {
        var from_address =  $("#site_detail_addr__c").val() +", " +
                            $("#site_detail_addr__city_c").val() + ", " +
                            $("#site_detail_addr__state_c").val() + ", " +
                            $("#site_detail_addr__postalcode_c").val();
    }
    var result_distance = '';
    $.ajax({
        url: "/index.php?entryPoint=getdistance_Flum_or_Elec_to_Suite&ac_id="+id_account,
        type: 'GET',
        async:false,
        success: function(data)
            {
                if(data == ', , , '){
                    alert('Sorry! - Not see address');
                }else {       
                    $.ajax({
                            url: "/index.php?entryPoint=customDistance&address_from=" + from_address + "&address_to=" + data,
                            type: 'GET',
                            async:false,
                            success: function(result)
                            {
                                try {
                                    var jsonObject = $.parseJSON(result);
                                    var l_distance = parseFloat(jsonObject.routes[0].legs[0].distance.text.replace(/[^\d.-]/g, ''));
                                    result_distance = l_distance;
                                } catch (error) {
                                    result_distance = 'not found';
                                }
                                
                            } 
                        
                    });
                }
            },
    })
    return result_distance;
}

function getDistances(from_address) {
    setTimeout(() => {
        $.ajax({
            url: "?entryPoint=customFilterPlumber&address_from=" + from_address,
            success: function (data) {
                try {
                    var infor = $.parseJSON(data);
                    renderShortest(infor['daikin_instaler'], 'distance_daikin_installer', 'proposed_daikin_installer_acccount', 'proposed_daikin_installer_acccount_id');
                    renderShortest(infor['electrician'], 'distance_electrician', 'proposed_electrician_acccount', 'proposed_electrician_acccount_id')
                    renderShortest(infor['plumber'], 'distance_plumber', 'proposed_plumber_acccount', 'proposed_plumber_acccount_id');
                    SUGAR.ajaxUI.hideLoadingPanel();
                } catch (error) {
                    console.log(error);
                    SUGAR.ajaxUI.hideLoadingPanel();

                }
            }
        });
    }, 1000);
}

function renderShortest(info, field_distance_id, field_account_name, field_account_id) {
    $("#" + field_distance_id).after("<b class='suggest'> Suggest: </b><br/>");
    $("#" + field_distance_id).after("</br><b class='shortest'> Shortest: </b><br/><b class='shortest-suggest'></b>");
    info.sort(function (a, b) {
        return a.distance - b.distance;
    });
    for (var i = 0; i < 5; i++) {
        var addr = info[0][0];
        var name_lum = info[0][1];
        var id_nearest = info[0][2];
        var str_dis = info[0][3];

        $("#" + field_distance_id).nextAll('.shortest-suggest').html('<a class="selected-suggest" dist="' + str_dis + '" rel="' + addr + '" href="#">' + name_lum + ': ' + addr + ':<span style="color:green">' + str_dis + '</span></a> <br>');
        $("#" + field_distance_id).val(str_dis);
        $('#' + field_account_name).val(name_lum);
        $('#' + field_account_id).val(id_nearest);
        $("#" + field_distance_id).nextAll('.suggest').append('<a class="selected-suggest" dist="' + info[i][3] + '" rel="' + info[i][0] + '" href="#">' + info[i][1] + ': ' + info[i][0] + ':<span style="color:green">' + info[i][3] + '</span></a> <br>');
    }
}
