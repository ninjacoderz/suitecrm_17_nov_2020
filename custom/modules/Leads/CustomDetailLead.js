(function ($) {
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        console.log("s");
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
        console.log(record_id);
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
$(function () {
    'use strict';    
    // Generate uinique id
    var number_click_request_design = false;
    $( document ).ready(function() {
        //dung codeo -- build section image 
        var button_copy = '<button type="button" class="clip_aboard_url">Copy Path</button>';

        var lead_id = $("input[name='record']").val();

        $('div[field="sanden_upload_url_c"]').append(button_copy);
        $('#sanden_upload_url_c').text('https://pure-electric.com.au/pe-sanden-quote-form/confirm-to-lead?lead-id='+lead_id);
        $('div[field="daikin_upload_url_c"]').append(button_copy);
        $('#daikin_upload_url_c').text('https://pure-electric.com.au/pedaikinform-new/confirm-to-lead?lead-id='+lead_id);
        $('div[field="solar_upload_url_c"]').append(button_copy);
        $('#solar_upload_url_c').text('https://pure-electric.com.au/pesolarform/confirm-to-lead?lead-id='+lead_id);

        $('.clip_aboard_url').click(function() {
            var type_id = $(this).parent().find('span').attr('id')
            var textarea = document.createElement('textarea');
            textarea.id = 'temp_element';
            textarea.style.height = 0;
            document.body.appendChild(textarea);
            textarea.value = document.getElementById(type_id).innerText;
            var selector = document.querySelector('#temp_element');
            selector.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            /* Alert the copied text */
            alert("Copied the text: " + textarea.value);
        });
        function render_field_data(value,fulldata){
            //value[0] id, value[1] value, value[2]  label ,fulldata json data 
            
            var html = '<div class="col-xs-12 col-sm-12 detail-view-row-item">'
           + '<div class="col-xs-12 col-sm-4 label col-1-label">'
           + value[2]
            + '</div>'
            +'<div class="col-xs-12 col-sm-8 detail-view-field inlineEdit" type="'+value[0]+'" field="'+value[0]+'">';
            
            switch (value[0]) {
                case "billing_account_email":
                    html +='<a style="margin: 10px 3px;" class="email-link"  onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ $("input[name='record']").val() +'" data-module-name="Leads" data-email-address="'+value[1]+'">'+value[1]+'</a>';
                    html +='<br><a style="color:blue;" class="email-link"  target="_blank" href="https://mail.google.com/#search/'+value[1]+'">GSearch </a>';
                    html += '<a class="copy-email-link" data-email-address="'+value[1]+'" \
                    title="Copy '+value[1]+'" onclick="$(document).copy_email_address(this);"\
                    style="cursor: pointer; position: relative;display: inline-block;border-bottom: 1px dotted black;" data-toggle="tooltip">&nbsp;<span class="glyphicon glyphicon-copy"></span>\
                    <span class="tooltiptext" style="display:none;width:200px;background:#94a6b5;color:#fff;text-align: center;border-radius: 6px;padding: 5px 0; position: absolute;z-index: 1;">Copied '+value[1]+'</span></a>';
                    break;
                case "mobile_phone_c":
                        html +='<span class="sugar_field" id="'+value[0]+'">'+value[1]+'</span>  ' + ' <img class="sms_icon" src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14">';
                        html +='&nbsp;<a target="_blank" href="http://message.pure-electric.com.au/'+value[1].replace(/^0/g, "#61").replace(/^61/g,"#61").replace(/\s+/g,'')+'" title="Message Portal"><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="14" width="14"></a>'
                        break;
                case "solargain_quote_number_c":
                    html +='<a target="_blank" href="https://crm.solargain.com.au/quote/edit/'+ value[1] +'">'+value[1]+'</a>';
                    break;
                case "solargain_tesla_quote_number_c":
                    html +='<a target="_blank" href="https://crm.solargain.com.au/quote/edit/'+ value[1] +'">'+value[1]+'</a>';
                    break;
                case "solargain_lead_number_c":
                    html +='<a target="_blank" href="https://crm.solargain.com.au/lead/edit/'+ value[1] +'">'+value[1]+'</a>';
                    break;
                default:
                    html +='<span class="sugar_field" id="'+value[0]+'">'+value[1]+'</span>';
                    break;
            }
          
            html +='<div class="inlineEditIcon col-xs-hidden">'
            +'</div>'
            +'</div>'
            +'</div>';
            return html;
        };
        var html_group_custom_template = 
        '<div id="group_custom_template" class="row detail-view-row">'
           + '<div id="group_custom_template_col_1" class="col-xs-12 col-sm-6 detail-view-row-item">'
           + '</div>'
           + '<div id="group_custom_template_col_2" class="col-xs-12 col-sm-6 detail-view-row-item">'
                + '<div id="group_custom_template_col_2_1" class="col-xs-12 col-sm-6 detail-view-row-item">'
                + '</div>'
                + '<div id="group_custom_template_col_2_2" class="col-xs-12 col-sm-6 detail-view-row-item">'
                + '</div>'
           + '</div>'
        +'</div>';
        $("#custom_detail_in_detail_view").parent().hide();
        $("#custom_detail_in_detail_view").parent().parent().find('.label').hide();
        $('#tab-content-0 #custom_detail_in_detail_view').closest('.detail-view-row-item').after(html_group_custom_template);
  
        $.ajax({
            url: "/index.php?entryPoint=APIGetDataLeads&record_id=" + $('input[name="record"]').val(),
            success: function(data){
                var json_data = JSON.parse(data);
                console.log(json_data);
                //Account 
                $("body").find('#group_custom_template_col_1').append(render_field_data(json_data.account_name),json_data);
                $("body").find('#group_custom_template_col_1').append(render_field_data(json_data.mobile_phone_c),json_data);
                $("body").find('#group_custom_template_col_1').append(render_field_data(json_data.billing_account_email),json_data);
                $("body").find('#group_custom_template_col_1').append(render_field_data(json_data.address),json_data);
                $("body").find('#group_custom_template_col_1').append(render_field_data(json_data.solargain_quote_number_c),json_data);
                $("body").find('#group_custom_template_col_1').append(render_field_data(json_data.solargain_lead_number_c),json_data);
                $("body").find('#group_custom_template_col_1').append(render_field_data(json_data.solargain_tesla_quote_number_c),json_data);
            
                //site details
                $("body").find('#group_custom_template_col_2_1').append(render_field_data(json_data.address_site_details),json_data);
                $("body").find('#group_custom_template_col_2_1').append(render_field_data(json_data.roof_type_c),json_data);
                $("body").find('#group_custom_template_col_2_1').append(render_field_data(json_data.nmi_c),json_data);
                $("body").find('#group_custom_template_col_2_1').append(render_field_data(json_data.distributor_c),json_data);
             
               if(json_data.installation_pictures_c[2]) {
                var html_image_site_detail = '<img id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;height:auto;width:100%;max-width:220px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+ json_data.installation_pictures_c[1]+'/Image_Site_Detail.jpg?'+Date.now()+'">';
               }else{
                var html_image_site_detail = '<div id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;padding:3px;width:100%;max-width:198px;height:auto;margin-bottom:5px;text-align:center;">Map Template Image</div>';
               }
               $("body").find('#group_custom_template_col_2_2').append(html_image_site_detail);
            }
        })
        //dung code- function button send email Request Design
        $(document).on('click', '#sendMailToAdmin_detail',function(){
            if ($("#primary_address_street").val() == '' ) {
                alert("Cannot send email No Street address entered");
                return;
            }
            $('#sendMailToAdmin_detail span.glyphicon-refresh').removeClass('hidden');
            //dung code - check button send Request Design can't send when email Request Design sent
            if($('#time_request_design_c').text() !== '' || number_click_request_design){
                var dialog = $('<p>Email has already been sent are you sure you want to request again?</p>').dialog({
                    buttons: {
                        "Yes": function() {
                                send_email_request_design();
                                dialog.dialog('close');
                                $('#sendMailToAdmin_detail span.glyphicon-refresh').addClass('hidden');
                            },
                        "Cancel":  function() {
                                dialog.dialog('close');
                                $('#sendMailToAdmin_detail span.glyphicon-refresh').addClass('hidden');
                                return;
                            }
                        }
                });
            }else {
                send_email_request_design();
                $('#sendMailToAdmin_detail span.glyphicon-refresh').addClass('hidden');
            }
        });

        //dung code - function send_email_request_design
        function send_email_request_design(){
            number_click_request_design = true;
            var _url = "?entryPoint=customLeadSendEmailToAdmin&record_id="
            + $('input[name="record"]').val()
            + "&primary_address_street=" + $("#primary_address_street").val()
            + "&primary_address_city=" + $("#primary_address_city").val()
            + "&primary_address_state=" + $("#primary_address_state").val()
            + "&primary_address_postalcode=" + $("#primary_address_postalcode").val();

            $.ajax({
                url: _url,
                type: 'GET',
                async: false, 
                success: function(data){
                    if(data !== ''){
                        alert('Request Designs email has just sent.');
                    }
                }
            })
        }

        $('#tab-actions').after($('<li></li>').append($("a[data-module='Leads']:last").clone() ));
        // $('#tab-actions').after($('#tab-actions li:first').clone());
        //<a href="javascript:void(0);" onclick=" $(document).openComposeViewModal(this);" data-module-name="Riley -" data-email-address="riley.cumming@hotmail.com">riley.cumming@hotmail.com</a>
        var record_id = $("input[name='lead_id']").val();
        var full_name = $("input[name='parent_name']").val();
        var to_email = $("input[name='to_email_addrs']").val();

        //thienpb fix get solargain_inverter_model_c
        var solargain_inverter_model = $("#solargain_inverter_model_c").val();
        $('#tab-actions').after('<li><a id="off-grid-email" data-email-type="off-grid" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Off Grid</a></li>');
        $('#tab-actions').after('<li><a id="methven-email" data-email-type="methven" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Methven</a></li>');
        $('#tab-actions').after('<li><a id="nexura-design" data-email-type="nexura-design" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Nexura</a></li>');
        $('#tab-actions').after('<li><a id="daikin-email" data-email-type="first-daikin" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">US7</a></li>');
        //Thienpb code 3 sanden button
        $('#tab-actions').after('<li><a id="sanden-email-fqs" data-email-type="Sanden_FQS" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Sanden FQS</a></li>');
        $('#tab-actions').after('<li><a id="sanden-email-fqv" data-email-type="Sanden_FQV" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Sanden FQV</a></li>');
        // $('#tab-actions').after('<li><a id="sanden-email" data-email-type="Sanden_EQTAQ" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Sanden EQTAQ</a></li>');
        //end
        $('#tab-actions').after('<li><a id="solar-tesla" data-email-type="solar-tesla" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Tesla</a></li>');
        $('#tab-actions').after('<li><a id="solar-design" data-email-type="solar-design" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Solar</a></li>');
        //$('#tab-actions').after('<li><a id="solar_design_complete" data-email-type="solar_design_complete" onclick="$(document).solar_design_complete(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'" data-sg-inverter-model="'+solargain_inverter_model+'">Send Solar Designs</a></li>');
        $('#tab-actions').after('<li><a id="street_address_request_email" data-email-type="street_address_request_email" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Request Address</a></li>');
        //$('#tab-actions').after('<button type="button" id="sendMailToAdmin_detail" class="button sendMailToAdmin_detail" title="Request Designs"> Request Designs <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        //$('#tab-actions').after('<li><a id="send_tesla_quote" data-email-type="send_tesla_quote" onclick="$(document).solar_design_complete(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'" data-sg-inverter-model="'+solargain_inverter_model+'">Send Tesla Quote</a></li>');
        // $("#tab-actions").after('<li><a class="button moveSandenForm" href="https://pure-electric.com.au/pesandenform" target="_blank" style="color:white;padding:8.5px;">SANDEN QUOTE FORM</a></li>');
        //thienpb code new logic open popup
        setTimeout(function(){
            var hashurl = window.location.hash;
            if(hashurl == '#product_type_sanden_fqs'){
                $("#sanden-email-fqs").trigger('click');
            }else if(hashurl == '#product_type_sanden_fqv'){
                $("#sanden-email-fqv").trigger('click');
            }else if(hashurl == '#product_type_daikinUS7'){
                $("#daikin-email").trigger('click');
            }else if(hashurl == '#product_type_methven'){
                $("#methven-email").trigger('click');
            }else if(hashurl == '#product_type_solar'){
                $("#solar-design").trigger('click');
            }else if(hashurl == '#product_type_tesla'){
                $("#solar-tesla").trigger('click');
            }else if(hashurl == '#product_type_nexura'){
                $("#nexura-design").trigger('click');
            } else if (hashurl == '#product_type_off_grid') {
                $('#off-grid-email').trigger('click');
            }
        },2000);

        //dung code - function solar design complete
        $.fn.solar_design_complete = function (ele){
            var source = ele;
            var record_id= $(source).attr('data-record-id') ;
            $.ajax({
                url: 'index.php?entryPoint=customgetlastdatefile&record_id=' +record_id,
                async: true,
                success: function(data){
                    console.log(data)
                    if(data.trim() == 'Not Data'){
                        var html_comfirm = "<p>Not file in this Lead!Do you want to download newest pdf and pictures.</p>";
                    }else {
                        var html_comfirm = "<p>The latest file was download at"+data +".Do you want to download newest pdf quote and solar design pictures</p>";
                    }
                    var dialog = $(html_comfirm).dialog({
                        buttons: {
                            "Yes": function() { 
                               
                                $.ajax({
                                    url: "?entryPoint=getAllFilesAttachments&lead_id="+record_id+"&module=Leads&action=detailLeadSolarDesignComplete",
                                    async: true,
                                    type: 'GET',
                                    success: function(data){
                                        console.log(data);
                                    }
                                })
                                $(document).openComposeViewModal(source); 
                                dialog.dialog('close');},
                            "No":  function() { 
                                $(document).openComposeViewModal(source); 
                                dialog.dialog('close');
                                return;
                            }
                        }
                    });
                }
            })
        }

        //dung code - logic button street_address_request_email
        $('#street_address_request_email').on('click',function(){
            if($("#status").val() == 'Address_Requested') {
                var dialog_message = confirm('We sent Request Address before.Do you want send again ?');
                if(dialog_message == true){
                    if($('input[name="to_email_addrs"]').val() !== ''){
                        $(document).openComposeViewModal(this);
                    }else {
                        //Dung code
                        $( "#dialog_send_sms" ).dialog("open");
                        var user_name = $('#assigned_user_name').text().trim();
                        user_name = user_name.split(" ");
                        user_name = user_name[0];
                        var name_customer = $('#full_name').text().trim();
                        name_customer = name_customer.split(" ");
                        name_customer = name_customer[0];
                        var phone_number_customer = $('div[field="phone_mobile"]').text().trim();
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
                        //End dung code
                        var sending_number = (user_name=="Matthew")?"+61421616733":"+61490942067";
                        $("#number_send_sms").val(sending_number);
                    }
                }else{
                    return false;
                }
            }else {
                if($('input[name="to_email_addrs"]').val() !== ''){
                    $(document).openComposeViewModal(this);
                }else {
                    //Dung code
                    $( "#dialog_send_sms" ).dialog("open");
                    var user_name = $('#assigned_user_name').text().trim();
                    user_name = user_name.split(" ");
                    user_name = user_name[0];
                    var name_customer = $('#full_name').text().trim();
                    name_customer = name_customer.split(" ");
                    name_customer = name_customer[0];
                    var phone_number_customer = $('div[field="phone_mobile"]').text().trim();
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
                    //End dung code
                    var sending_number = (user_name=="Matthew")?"+61421616733":"+61490942067";
                    $("#number_send_sms").val(sending_number);
                }
            }

        })
    });


    //dung code event click field number quote and opportunity
    $(document).ready(function(){
        $('#create_opportunity_number_c ,#create_sanden_number_c ,#create_solar_number_c ,#create_daikin_number_c ').on('click',function(){
            if($(this).text() !== '') {
                window.open('/index.php?module=Opportunities&action=EditView&record='+$(this).text(), '_blank');
            }
        });
        
        $('#create_daikin_quote_num_c ,#create_sanden_quote_num_c ,#create_methven_quote_num_c, #create').on('click',function(){
            if($(this).text() !== '') {
                window.open('/index.php?module=AOS_Quotes&action=EditView&record='+$(this).text(), '_blank');
            }
        });

        $('#open_new_tag_c').parent().parent().hide();
        function auto_open_new_tag(){
            var open_new_tag = $('#open_new_tag_c').text();
            var record_id = $("input[name='lead_id']").val();
            try {
                open_new_tag = $.parseJSON(open_new_tag);
                $.each(open_new_tag, function(key ,value){
                    if(value == '1' ) {
                        console.log(key);
                        var selector = $('#'+key).text();
                        if(key == 'create_opportunity_number_c' || key == 'create_sanden_number_c' || key == 'create_solar_number_c' || key == 'create_daikin_number_c' || key == 'create_methven_number_c' || key == 'create_grid_button_number_c') {
                            //window.open('/index.php?module=Opportunities&action=EditView&record='+selector, '_blank');
                        } if (key == 'service_case_number_c') {
                            window.open('/index.php?module=pe_service_case&action=EditView&record='+ selector, '_blank');
                        } else {
                            window.open('/index.php?module=AOS_Quotes&action=EditView&record='+ selector, '_blank');
                        }
                        
                        open_new_tag[key] = '2';
                    }
                });
                open_new_tag['id'] = record_id;
                $.ajax({
                    url: 'index.php?entryPoint=customPostFieldOpenTag',
                    data: open_new_tag,
                    success: function(data){
                        console.log(data);
                    }
                })
            } catch (e) {
                return false;
            } 
        }
        auto_open_new_tag();

        //VUT-
        setTimeout(function() {
            var record_id = $('body').find('input[name="record"]').val().trim();
            $.ajax({
                url: "?entryPoint=customGetAgeDays&record_id="+record_id,
                type: 'GET',
                success: function(data){
                // debugger;
                if(data == '') {return;}
                else {$('#age_days_c').text(data);}
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


        //tritruong -- add button Convert Off Grid
        $("#convert_lead_button").parent().after('<li><input class="button_convert" title="Convert Off Grid" accesskey="V" class="button" name="convert" id="convert_off_grid_button" type="button" value="Convert Off Grid"></li>');
        //dung code -- add button convert 
        $("#convert_lead_button").parent().after('<li><input class="button_convert" title="Convert Daikin" accesskey="V" class="button" name="convert" id="convert_daikin_button" type="button" value="Convert Daikin"></li>');
        $("#convert_lead_button").parent().after('<li><input class="button_convert" title="Convert Sanden" accesskey="V" class="button" name="convert" id="convert_sanden_button" type="button" value="Convert Sanden"></li>');
        $("#convert_lead_button").parent().after('<li><input class="button_convert" title="Convert Solar" accesskey="V" class="button" name="convert" id="convert_solar_button" type="button" value="Convert Solar"></li>');
        $("#convert_lead_button").parent().after('<li><input class="button_convert" title="Convert Mathven" accesskey="V" class="button" name="convert" id="convert_mathven_button" type="button" value="Convert Methven"></li>');

        
        //$(".email-link").parent("li").after('<li><a style="background:#009acf;" type="button" id="btn_pe_daikin_form" class="button btn_pe_daikin_form" title="PE Daikin Form">Daikin Quote Form</a></li>');
        $(".email-link").parent("li").after('<li><a style="background:#945596;" type="button" id="btn_pe_sanden_form_new" class="button btn_pe_sanden_form_new" title="PE Sanden Form">Sanden Quote Form</a></li>');
        $(".email-link").parent("li").after('<li><a style="background:#f48c21;" type="button" id="btn_pe_solar_form" class="button btn_pe_solar_form" title="PE Solar Form">Solar Quote Form</a></li>');
        $(".email-link").parent("li").after('<li><a style="background:#009acf;" type="button" id="btn_pe_daikin_new_form" class="button btn_pe_daikin_new_form" title="PE Daikin Form">Daikin Quote Form</a></li>');
        var lead_id = $("input[name=lead_id]").val();
        //thienpb code
        $('#tab-actions').after('<li><button style="background:#945596;" type="button" id="btn_solar_design" class="button btn_solar_design" title="Solar Design">Solar Design</button></li>');
        $("#btn_pe_daikin_form").click(function(e) {
            window.open(
                'https://pure-electric.com.au/pedaikinform?lead-id='+lead_id,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
        $("#btn_pe_daikin_new_form").click(function(e) {
            window.open(
                'https://pure-electric.com.au/pedaikinform-new?lead-id='+lead_id,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
        $("#btn_pe_sanden_form_new").click(function(e) {
            window.open(
                'https://pure-electric.com.au/pe-sanden-quote-form?lead-id='+lead_id,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
        $("#btn_pe_solar_form").click(function(e) {
            window.open(
                'https://pure-electric.com.au/pesolarform?lead-id='+lead_id,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
        $("#btn_solar_design").on('click',function(e) {
            var address = [$("#primary_address_street").val(),$("#primary_address_city").val()+' '+$("#primary_address_state").val(),$("#primary_address_postalcode").val(),'Australia'];
            address = address.join(', ');
            var first_name = $("#account_name").text().split(" ")[0];
            var family_name = $("#account_name").text().split(" ").slice(1).join(" ");
            var email = $("div[field='billing_account_email'] a").data("email-address");
            var phone = $("#mobile_phone_c").text().replace(/ /g,'');
            window.open(
                'https://solardesign.pure-electric.com.au/#/projects/create?addressSearch='+address+'&first_name='+first_name+'&family_name='+family_name+'&email='+email+'&phone='+phone,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
        $('.button_convert').on('click',function(){
            var record_lead_id = $("input[name='lead_id']").val();
            //VUT-S
            var product_type = $("#product_type_c").val();
            //VUT-E
            var type_button = $(this).attr('id');
            $.ajax({
                url: "?entryPoint=CustomButtonConvertLead&record_id="+record_lead_id+"&type_convert="+type_button+"&product_type="+product_type,
                success: function(data){
                    var object_result =  $.parseJSON(data);
                    $('#open_new_tag_c').text(object_result.json_open_new_tag);
                    $.each(object_result.array_id_opportunity_and_quote, function(key,value){
                        $('#'+key).text(value);
                    })
                    // debugger
                    auto_open_new_tag();
                }
            })
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
    });
    $(document).ready(function(){
        //tu-code show Links related to the customer
        var record  = $('input[name="record"]').val();
        if(record != ''){
            $.ajax({
                url:"?entryPoint=CustomConverted&record="+record+"&module=AOS_Quotes",
                success:function(data){
                   var jsonObject = JSON.parse(data);
                   for(var i = 0; i < jsonObject.length; i++){
                        var edit_link = "<a target='_blank' href='/index.php?module=AOS_Quotes&action=EditView&record="+jsonObject[i]['id']+"'>[E] </a>";
                        var href = "<td>Converted Quote:"+edit_link+"&nbsp;"+"<a target='_blank' href='/index.php?module=AOS_Quotes&action=DetailView&record="+jsonObject[i]['id']+"'>"+jsonObject[i]['name']+"</a></td>";
                        $(".converted_account").children().children().children().children().children().children().append(href);
                    }
                }
            });
            $.ajax({
                url:"?entryPoint=CustomConverted&record="+record+"&module=AOS_Invoices",
                success:function(data){
                    var jsonObject = JSON.parse(data);
                   for(var i = 0; i < jsonObject.length; i++){
                        var edit_link = "<a target='_blank' href='/index.php?module=AOS_Invoices&action=EditView&record="+jsonObject[i]['id']+"'>[E] </a>";
                         var href = "<td>Converted Invoice:"+edit_link+"&nbsp;"+"<a target='_blank' href='/index.php?module=AOS_Invoices&action=DetailView&record="+jsonObject[i]['id']+"'>"+jsonObject[i]['name']+"</a></td>";
                         $(".converted_account").children().children().children().children().children().children().append(href);
                    }
                }
            });
        }
        //tu-code auto  send request designs after save
        if($("#create_solar_quote_num_c").text() !== ''){
            if($("#time_request_design_c").text() == ''){
                if($("#lead_source").val() == 'Solargain'){
                    var _url = "?entryPoint=customQuoteSendEmailToAdmin&record_id="
                    + $("#create_solar_quote_num_c").text()
                    + "&billing_address_street=" + $("#primary_address_street").val()
                    + "&billing_address_city=" + $("#primary_address_city").val()
                    + "&billing_address_state=" + $("#primary_address_state").val()
                    + "&billing_address_postalcode=" + $("#primary_address_postalcode").val();
                    $.ajax({
                        url: _url,
                        type: 'GET',
                        success: function(data){
                            if(data !== ''){
                                console.log("Success Request Design");
                            }
                        }
                    })
                }
                    
            }else{
                return;
            }
        }
    })
})