$(document).ready(function(){
     function Link_View_Email() {
         var email_id_c = $("#email_id_c").val();
         $(".Link_View_Email").remove();
         if(email_id_c != ''){

            $("#email_id_c").closest('.col-xs-12').append('<br><a class="Link_View_Email" target="_blank" href="/index.php?module=Emails&action=DetailView&record='+email_id_c +'">Edit Link</a>');
         }
     }
     Link_View_Email();
});

//VUT-S-Show email, sms popup quick link
$(document).ready(function(){
    // //FUNCTION
    var phone_number_customer = "";
	var name_customer_mess = '';
	var user_name_mess = '';
	var module_for_mess = $('#MassUpdate input[name="module"]').val();
	var record_id_mess = '';
    function click_send_sms(){
        var content_messager_string = $('#content_messager').val();
        var user_phone_number	= $('#from_phone_number').find('option:selected').val();
        phone_number_customer = phone_number_customer.trim()
        var data_json = {
            'phone_number_customer':phone_number_customer,
            'from_phone_number':user_phone_number,
            'content_messager' :content_messager_string,
            'module': module_for_mess,
            'record_id' : record_id_mess,
        }
        $.ajax({
            url: 'index.php?entryPoint=sendSMS',
            type: 'POST',
            data: data_json,
            success: function(data){
                console.log(data);
            }
        });
    }
    // //FUNCTION


    $(document).on('click','.sms_icon_invoice',function(e){
        $("body").find("#dialog_send_sms").remove();
        $("body").append(' <div id = "dialog_send_sms" title="Send SMS">'+
                                '<div id="sms">'+
                                    '<div>'+
                                        '<div class="label" >Phone Number :'+
                                        '<span class="input" id="phone_number_customer"></span>'+
                                    '</div>'+
                                    '<div>'+
                                        '<div class="label" >From Phone Number :</div>'+
                                        '<select style="width:170px;margin-bottom:2px;" id="from_phone_number" >'+
                                           '<option label="+61490942067" value="+61490942067">+61490942067</option>'+
                                           '<option label="+61421616733" value="+61421616733">+61421616733</option>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div>'+
                                        '<div class="label">Message :'+
                                            '<select style="width:500px;margin-bottom:2px;" id="messager_template">'+
                                                '<option  value=""></option>'+
                                                '<option  value="Hi [FirstName], [User] from Solargain. We’d love to prepare a solar quote for you. So we can view aerial photos of your house, can you advise the address? We also need your email address to send a quote to you. If you wish you can call me on [UserMobile]">Hi [FirstName], [User] from Solargain. We’d love to prepare a solar quote for you. So we can view aerial photos of your house, can you advise the address? We also need your email address to send a quote to you. If you wish you can call me on [UserMobile]</option>'+
                                                '<option  value="Hi [FirstName], this is [User] from Solargain. Just checking in to see how your solar decision-making was progressing and how I may assist? If you wish you can call me on [UserMobile].">Hi [FirstName], this is [User] from Solargain. Just checking in to see how your solar decision-making was progressing and how I may assist? If you wish you can call me on [UserMobile].</option>'+
                                                '<option  value="Hi [FirstName], your solar energy system is due for a service. Please reply YES to book your solar health check or STOP to opt out of these notifications. For more information, call 1300 73 93 55 or visit https://www.solargain.com.au/service/book-your-solar-power-maintenance-service.">Hi [FirstName], your solar energy system is due for a service. Please reply YES to book your solar health check or STOP to opt out of these notifications. For more information, call 1300 73 93 55 or visit https://www.solargain.com.au/service/book-your-solar-power-maintenance-service.</option>'+
                                            '</select>'+
                                        '</div>'+
                                        '<div class="input">'+
                                            '<textarea id="content_messager" style="width:100%;height:200px;">'+
                                            '</textarea>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>');
        var phone_number_id = $(this).attr("data-source")+"_phone_number";
        var name_customer_mess = $(this).attr("data-source")+"_name";
        user_name_mess = $('#assigned_user_name').val();
        phone_number_customer = $(this).parent().children("."+phone_number_id).text();
        var numberPattern = /\d+/g;
        phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
        $('#phone_number_customer').text(phone_number_customer);
        $("#dialog_send_sms").dialog({
            width: 712,
            height:478,
            modal:true,
            buttons: {
                Cancel: function(){
                    $(this).dialog('close');
                    $( "#content_messager" ).val("");
                    $('#messager_template').prop('selectedIndex',0);
                    $('#from_phone_number').prop('selectedIndex',0);
                },
                Send: function(){
                    click_send_sms();
                    $(this).dialog('close');
                    $( "#content_messager" ).val("");
                    $('#messager_template').prop('selectedIndex',0);
                    $('#from_phone_number').prop('selectedIndex',0);
                }
            }
        });
    })

    if (module_sugar_grp1 == "pe_internal_note" && action_sugar_grp1 == 'EditView') {
        var record_id= $('body').find('input[name="record"]').val();
        if(record_id == ''){
            alert('Please Save before !');
            return;
        }
        $.ajax({
            url :"?entryPoint=APIgetContactFromInternalNote&record="+record_id+"&module=" + module_sugar_grp1,
            success:function(data){
                // debugger;
                if (data == "[]") {return;}
                var jsonObject = JSON.parse(data);
                var contact_id = jsonObject.Contacts.id;
                var name = jsonObject.Contacts.name;
                var phone = jsonObject.Contacts.phone_mobile;
                var email = jsonObject.Contacts.email;
                if (contact_id != undefined) {
                    if (action_sugar_grp1 == 'EditView') {
                        position_append = "#EditView_tabs";
                        $html_link = "<div><table><tr>";
                        if (name != "") {
                            $html_link += "<span style='color:black;font-weight:700;' class='contact_name' value='"+name+"'>Contact : </span><a target='_blank' href='/index.php?module=Contacts&action=DetailView&record=" + contact_id + "'>" + name + "</a> <a target='_blank' href='/index.php?module=Contacts&action=EditView&record=" + contact_id + "'> [E] </a>";
                        }
                        if (email != "") {
                            $html_link += "<span style='color:black;font-weight:700;'> Email :</span><a onclick=$(document).openComposeViewModal(this); data-module=Contacts data-record-id='"+contact_id+"'data-module-name='"+name+"' data-email-address='"+email+"'>"+email+"</a>";
                        }
                        if (phone != "") {
                            $html_link += "<span style='color:black;font-weight:700;'> Phone number: </span><span class='contact_phone_number'>"+phone+'</span> <img class="sms_icon_invoice" data-source="contact" src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14">';
                            html +='&nbsp;<a target="_blank" href="http://message.pure-electric.com.au/'+phone.replace(/^0/g, "#61").replace(/^61/g,"#61").replace(/\s+/g,'')+'" title="Message Portal"><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="14" width="14"></a>'
                        }
                        $html_link += '</tr></table></div>';
                        $(position_append).before($html_link);
                    }
                }
            }
        });
    }
});
//VUT-E-Show email, sms popup quick link


(function ($) {
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        var module_name = $(source).attr('data-module');
        var record_id= $(source).attr('data-record-id') ;
        var emailType = $(source).data('email-type');
        if(record_id == ''){
            alert('Please Save before !');
            return;
        }

        if(module_name == 'Invoices'){
            var order_number = $("#solargain_invoices_number_c").val();
            if(emailType != 'invoice_payment_reminder' && emailType != 'Send_Customer_Install_date') {
                if(order_number == "" ){
                    alert('Please fill Order Number !');
                    $("#solargain_invoices_number_c").focus();
                    return;
                }
            }
        }
        if(module_name == 'AOS_Quotes'){
            var seek_install_date_c = $('#proposed_install_date_c_date').val();
            if(seek_install_date_c == ''){
                alert('Please fill Proposed Install Date !');
                $("#proposed_install_date_c_date").focus();
                return;
            }
        }
        var quote_number= $('#solargain_quote_number_c').val() ;
   
        $('#Advise_Install_Date span.glyphicon-refresh').removeClass('hidden');
        // alert("ok");
        var build_url_quote = "?entryPoint=customProposedInstallDate";
        build_url_quote += "&quoteSG_ID="+quote_number;
        build_url_quote += "&proposed_date="+ $("#proposed_install_date_c").val();
        $.ajax({
            url: build_url_quote,
            type : 'POST',
            success: function (data) {
                $('#Advise_Install_Date span.glyphicon-refresh').addClass('hidden');
            },
        });
    
        var self = this;
        console.log(source);
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        if(module_name == 'Invoices') {
            var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((order_number!="")? ("&order_number="+order_number):"")+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") ;
        }else if(module_name == 'AOS_Quotes'){
            var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((seek_install_date_c!="")? ("&seek_install_date_c="+encodeURIComponent(seek_install_date_c)):"")+  ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") ;
        }else{
            var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1' ;
        }
        
        $.ajax({
            type: "GET",
            cache: false,
            url: url_email,
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();

            
            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');
            var populateModuleName = $(source).attr('data-module-name');
           
            // $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_id"]').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_module"]').val(populateModule);         
            $(self.emailComposeView).find('input[name="to_addrs_names"]').val(populateModuleName+'<'+ $(source).attr('data-email-address') + '>');    
            
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
