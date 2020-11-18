(function ($) {
    
    $.fn.openComposeViewModal_SendSolarQuote = function (source) {
        "use strict";
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var lead_id= $(source).attr('data-record-id') ;
        var record_id = $("input[name='record']").val();
        var email_type = $(source).attr('data-email-type');
        //thienpb code
        var sg_inverter_model = $(source).attr('data-sg-inverter-model');
        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1' + ((record_id!="")? ("&quote_id="+record_id):"") + ((lead_id!="")? ("&lead_id="+lead_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((sg_inverter_model!='')?("&inverter_model="+sg_inverter_model):""),
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
            var populateModuleQuoteID = $(source).attr('data-module-quote-id');


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
            $(self.emailComposeView).find('input[name="return_module"]').after("<input type='hidden' name = 'quote_parent_id' id = 'quote_parent_id' value='"+populateModuleQuoteID+"' >");


            // dung code - add checkbox Convert Solar Opportunity for popup "Send Solar Design"
            if(email_type == 'send_solar_quote'){
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
            if(email_type == 'send_solar_quote'){
                var number_quote_number = $('#solargain_quote_number_c').text().trim();
            }else{
                var number_quote_number = $('#solargain_tesla_quote_number_c').text().trim();
            }
            if(typeof(number_quote_number) == 'undefined' || number_quote_number == ''
            || typeof(module_sugar_grp1) == 'undefined' || module_sugar_grp1 == ''
            || typeof(action_sugar_grp1) == 'undefined' || action_sugar_grp1 == ''){
            
            }else {
                if(module_sugar_grp1 == 'AOS_Quotes' && action_sugar_grp1 == 'DetailView'){
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

    $.fn.openComposeViewModal_SendSolarPricing = function (source) {
        "use strict";
        var module_name = $(source).attr('data-module');
        var record_id= $(source).attr('data-record-id') ;
        var emailType = $(source).data('email-type');
        if(record_id == ''){
            alert('Please Save before !');
            return;
        }

        var self = this;

        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var  email_module  =  $(source).attr('data-module');
        var address = $('#address').html();
        if( $('#vic_rebate_c').prop('checked') == true ){
            var vic_rebate = "Yes";
        }else {
            var vic_rebate = "No";
        }
        if( $('#vic_loan_c').prop('checked') == true ){
            var vic_loan = "Yes";
        }else {
            var vic_loan = "No";
        }
        if( $('#double_storey_c').prop('checked') == true ){
            var storey = "Double Storey";
        }else {
            var storey = "Single Storey";
        }
        var url_email = 'index.php?module=Emails&action=ComposeView&address='+ address +'&storey='+storey+'&vic_rebate='+vic_rebate+'&vic_loan='+vic_loan+'&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"") ;
                
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
           
            //$(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_id"]').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_module"]').val(populateModule);         
            
            
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
    $( document ).ready(function() {
        // TriTruong Code
        var button_copy = '<button type="button" class="clip_aboard_url">Copy Path</button>';
        var typeProduct =  $('div[field="quote_type_c"]').text();
        
        var quote_id = $("input[name=uid]").val();
        console.log(quote_id);
        if(typeProduct.indexOf('Sanden') != -1) {
            $('div[field="quote_upload_url_c"]').append(button_copy);
            $('#quote_upload_url_c').text('https://pure-electric.com.au/pe-sanden-quote-form/confirm?quote-id='+quote_id);
        } else if (typeProduct.indexOf('Daikin') != -1) {
            $('div[field="quote_upload_url_c"]').append(button_copy);
            $('#quote_upload_url_c').text('https://pure-electric.com.au/pedaikinform-new/confirm?quote-id='+quote_id);
        }
        
        $('.clip_aboard_url').click(function() {
            var textarea = document.createElement('textarea');
            textarea.id = 'temp_element';
            textarea.style.height = 0;
            document.body.appendChild(textarea);
            textarea.value = document.getElementById('quote_upload_url_c').innerText;
            var selector = document.querySelector('#temp_element');
            selector.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            /* Alert the copied text */
            alert("Copied the text: " + textarea.value);
        });
        // thienpb copy
        var button_copy = '<button type="button" class="clip_aboard_url">Copy Path</button>';
        var typeProduct =  $('div[field="quote_type_c"]').text();
        
        var quote_id = $("input[name=uid]").val();
        var acceptance_url = '<div class="row detail-view-row">'
        +'<div class="col-xs-12 col-sm-12 detail-view-row-item">'
        +'<div class="col-xs-12 col-sm-2 label col-1-label"><br/>'
        +'Quote Acceptance URL:'
        +'</div>'
        +'<div class="col-xs-12 col-sm-10 detail-view-field " colspan="3">'
        +'<span class="sugar_field" id="quote_acceptance_url"></span>'
        +'<button type="button" id="clip_aboard_acceptance_url">Copy Path</button></div>'
        +'</div>'
        +'</div>';
        $(".row.detail-view-row").last().after(acceptance_url);
        if(typeProduct.indexOf('Sanden') != -1) {
            $('div[field="quote_acceptance_url"]').append(button_copy);
            $('#quote_acceptance_url').text('https://pure-electric.com.au/pe-sanden-quote-form/acceptance?quote-id='+quote_id);
        } else if (typeProduct.indexOf('Daikin') != -1) {
            $('div[field="quote_acceptance_url"]').append(button_copy);
            $('#quote_acceptance_url').text('https://pure-electric.com.au/pedaikinform-new/acceptance?quote-id='+quote_id);
        }else if (typeProduct.indexOf('Solar') >= 0){
            $('div[field="quote_acceptance_url"]').append(button_copy);
            $('#quote_acceptance_url').text('https://pure-electric.com.au/confirm_option_acceptance?quote-id='+quote_id);
        }
        
        $('#clip_aboard_acceptance_url').on("click",function() {
            var textarea = document.createElement('textarea');
            textarea.id = 'temp_element';
            textarea.style.height = 0;
            document.body.appendChild(textarea);
            textarea.value = document.getElementById('quote_acceptance_url').innerText;
            var selector = document.querySelector('#temp_element');
            selector.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            /* Alert the copied text */
            alert("Copied the text: " + textarea.value);
        });
        //dung code -- build section site detail image 
        function render_field_data(value,fulldata){
            //value[0] id, value[1] value, value[2]  label ,fulldata json data 
            
            var html = '<div class="col-xs-12 col-sm-12 detail-view-row-item">'
            + '<div class="col-xs-12 col-sm-4 label col-1-label">'
            + value[2]
            + '</div>'
            +'<div class="col-xs-12 col-sm-8 detail-view-field inlineEdit" type="'+value[0]+'" field="'+value[0]+'">';
            
            switch (value[0]) {
                case "billing_account_email":
                    html +='<a class="email-link"  onclick="$(document).openComposeViewModal(this);" data-module="AOS_Quotes" data-record-id="'+ $("input[name='record']").val() +'" data-module-name="'+ $("#name").text() +'" data-email-address="'+value[1]+'">'+value[1]+'</a>';
                    html +='<br><a style="color:blue;" class="email-link-gsearch"  target="_blank" href="https://mail.google.com/#search/'+value[1]+'">GSearch </a>';
                    html += '<a class="copy-email-link" data-email-address="'+value[1]+'" \
                    title="Copy '+value[1]+'" onclick="$(document).copy_email_address(this);"\
                    style="cursor: pointer; position: relative;display: inline-block;border-bottom: 1px dotted black;" data-toggle="tooltip">&nbsp;<span class="glyphicon glyphicon-copy"></span>\
                    <span class="tooltiptext" style="display:none;width:200px;background:#94a6b5;color:#fff;text-align: center;border-radius: 6px;padding: 5px 0; position: absolute;z-index: 1;">Copied '+value[1]+'</span></a>';
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
                case "mobile_phone_c":
                        html +='<span class="sugar_field" id="'+value[0]+'">'+value[1]+'</span>  ' + ' <img class="sms_icon" src="themes/SuiteR/images/sms.png" alt="icon-sms" height="14" width="14">';
                        html +='&nbsp;<a target="_blank" href="http://message.pure-electric.com.au/'+value[1].replace(/^0/g, "#61").replace(/^61/g,"#61").replace(/\s+/g,'')+'" title="Message Portal"><img class="mess_portal" data-source="account"  src="themes/SuiteR/images/mess_portal.png" alt="mess_portal" height="14" width="14"></a>'
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
            url: "/index.php?entryPoint=APIGetDataQuotes&record_id=" + $('input[name="record"]').val(),
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
                $("body").find('#group_custom_template_col_2_2').append('<canvas hidden="" id="clipboard"></canvas>');
                //Function auto loadding image detail 
                
                    var generateUUID = json_data.installation_pictures_c[1];
                    if(generateUUID == '') {
                        generateUUID = create_generateUUID();
                    }
                    var check_image = $('img#Map_Template_Image').attr('src');
                    if( json_data.billing_address_street[1] != "" && check_image == undefined) {

                        SUGAR.ajaxUI.showLoadingPanel();
                        $("#ajaxloading_mask").css("position",'fixed');
                        $.ajax({
                            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' 
                            + encodeURIComponent(json_data.billing_address_street[1]) +", " 
                            + encodeURIComponent(json_data.billing_address_city[1])  + ", " 
                            + encodeURIComponent(json_data.billing_address_state[1])  
                            + ", " +  encodeURIComponent(json_data.billing_address_postalcode[1]) 
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
                                        async: false,
                                        success: function(result) {
                                            $("#Map_Template_Image").hide();
                                            $("#map").hide();
                                            $("#Map_Template_Image").after(result);                
                                        }
                                    }).done(function(data){
                                        CopyToClipboard(generateUUID);                          
                                    });
                                }
                                else{
                                    SUGAR.ajaxUI.hideLoadingPanel();
                                }
                            }    
                        })
                    }  
            }
        });
        //Tri Truong - Only allow assigned user to send email
        var userUrl = $('.user-dropdown li:first-child a').attr('href'),
            userId  = userUrl.substr(userUrl.length - 36),
            assignedId = $('#assigned_user_id').attr('data-id-value');
        $('body').on("click","#send_quote_pdf, #send_tesla_quote_pdf",function() {
            if (!compareToday()) return false;
            // alert require lead 
            
            if( $(this).attr("data-email-type") == "solar_design_complete"){
                if($('div[field="assigned_user_c"] input').prop("checked" ) == false) {
                    $(document).openComposeViewModal_SendSolarQuote(this);
                } else {
                    if(userId == assignedId) {
                        $(document).openComposeViewModal_SendSolarQuote(this);
                    } else {
                        alert('Only allow assigned user to send email');
                        return false;
                    }
                }
            }else {
                if($('div[field="assigned_user_c"] input').prop("checked" ) == false) {
                    $(document).send_solar_pdf(this);
                } else {
                    if(userId == assignedId) {
                        $(document).send_solar_pdf(this);
                    } else {
                        alert('Only allow assigned user to send email');
                        return false;
                    }
                }
            }
        })
        $("#convert_to_invoice_button").click(function (e) {
            e.stopPropagation()
            console.log("ss");
            // e.stopImmediatePropagation()
            /* your code continues ... */
        });

        $('#convert_to_invoice_button').attr('onclick',
            ' var record = encodeURIComponent($("input[name=\'record\']").val()); \
            $.ajax({\
                url: \'/index.php?entryPoint=checkSwitchBoardAttached&record=\'+ record,\
                success: function (data) {\
                    console.log(data);\
                    if(data){\
                        var _form = document.getElementById(\'formDetailView\');_form.action.value=\'converToInvoice\';_form.submit();\
                    }\
                    else {\
                        alert(\'Please add Switchboard photo to Attachment!\');\
                    }\
                }\
            });\
            ')
        $('#tab-actions').after($('<li></li>').append($("li#tab-actions li:nth-child(6)").attr('id','hiddeninput').clone() ));
        $('#hiddeninput input').hide();
        // $('#tab-actions').after($('#tab-actions li:first').clone());

        //Thienpb code add button send solar pdf
        var solargain_inverter_model = $("#solargain_inverter_model_c").val();
        $.ajax({
            type: "GET",
            cache: false,
            url: "?entryPoint=getLeadFromAccount&account_id="+$("#billing_account_id").attr('data-id-value')+"&lead_id="+$("#leads_aos_quotes_1leads_ida").attr('data-id-value'),
        }).done(function (data) {
            var json = $.parseJSON(data);
            window.lead_source = json.lead_source;
            if(json.id != ''){
                if($('#quote_type_c').val() == 'quote_type_solar' || $('#quote_type_c').val() == 'quote_type_tesla') {
                    if($('#quote_type_c').val() == 'quote_type_solar'){
                        $('#tab-actions').after('<li><a id="send_quote_pdf" style="background: #f08377" class="button primary" data-email-type="solar_design_complete" data-module="AOS_Quotes" data-record-id="'+ json.id +'" data-module-name="'+ json.first_name + ' ' + json.last_name +'" data-email-address="'+  json.email +'" data-sg-inverter-model="'+solargain_inverter_model+'"  data-module-quote-id="'+ $("input[name='record']").val()+'">Send Email Quote <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></a></li>');
                    } else {
                        if($('#quote_type_c').val() == 'quote_type_tesla'){
                            $('#tab-actions').after('<li><a id="send_tesla_quote_pdf" style="background: #f08377" class="button primary" data-email-type="send_tesla_quote" data-module="AOS_Quotes" data-record-id="'+ json.id +'" data-module-name="'+ json.first_name + ' ' + json.last_name +'" data-email-address="'+  json.email +'" data-sg-inverter-model="'+solargain_inverter_model+'"  data-module-quote-id="'+ $("input[name='record']").val()+'">Send Email Quote <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></a></li>');
                        }
                    }
                }else if($('#quote_type_c').val() == 'quote_type_daikin'){
                    $('#tab-actions').after('<li><input type="button" name="Email QuotePdf" value="Send Email Quote" id="Email QuotePdf" class="button primary" onclick="if (!compareToday()) return false;document.getElementById(\'popupDivBack_ara\').style.display=\'none\';document.getElementById(\'popupDiv_ara\').style.display=\'none\';var form=document.getElementById(\'popupForm\');if(form!=null){$(form).attr(\'target\', \'_blank\');form.task.value=\'emailpdf\';form.templateID.value=\'4fbfbfa6-0bc9-3dbb-0d5e-57ce330802c5\';form.productType.value=\'quote_type_daikin\';form.submit();}else{alert(\'Error!\');}" /></li>');
                }else {
                    $('#tab-actions').after('<li><input type="button" name="Email QuotePdf" value="Send Email Quote" id="Email QuotePdf" class="button primary" onclick="if (!compareToday()) return false;document.getElementById(\'popupDivBack_ara\').style.display=\'none\';document.getElementById(\'popupDiv_ara\').style.display=\'none\';var form=document.getElementById(\'popupForm\');if(form!=null){$(form).attr(\'target\', \'_blank\');form.task.value=\'emailpdf\';form.templateID.value=\'4fbfbfa6-0bc9-3dbb-0d5e-57ce330802c5\';form.submit();}else{alert(\'Error!\');}" /></li>');
                } 
            }else{
                if($('#quote_type_c').val() == 'quote_type_solar' || $('#quote_type_c').val() == 'quote_type_tesla') {
                    if($('#quote_type_c').val() == 'quote_type_solar'){
                        $('#tab-actions').after('<li><a id="send_quote_pdf" style="background: #f08377" class="button primary" data-email-type="solar_design_complete" data-error="Not have lead source" >Send Email Quote <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></a></li>');
                    } else {
                        if($('#quote_type_c').val() == 'quote_type_tesla'){
                            $('#tab-actions').after('<li><a id="send_tesla_quote_pdf" style="background: #f08377" class="button primary" data-email-type="send_tesla_quote" data-error="Not have lead source">Send Email Quote <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></a></li>');
                        }
                    }
                }else if($('#quote_type_c').val() == 'quote_type_daikin'){
                    $('#tab-actions').after('<li><input type="button" name="Email QuotePdf" value="Send Email Quote" id="Email QuotePdf" class="button primary" onclick="if (!compareToday()) return false;document.getElementById(\'popupDivBack_ara\').style.display=\'none\';document.getElementById(\'popupDiv_ara\').style.display=\'none\';var form=document.getElementById(\'popupForm\');if(form!=null){$(form).attr(\'target\', \'_blank\');form.task.value=\'emailpdf\'; form.templateID.value=\'4fbfbfa6-0bc9-3dbb-0d5e-57ce330802c5\';form.productType.value=\'quote_type_daikin\';form.submit();}else{alert(\'Error!\');}" /></li>');
                }else{
                    $('#tab-actions').after('<li><input type="button" name="Email QuotePdf" value="Send Email Quote" id="Email QuotePdf" class="button primary" onclick="if (!compareToday()) return false;document.getElementById(\'popupDivBack_ara\').style.display=\'none\';document.getElementById(\'popupDiv_ara\').style.display=\'none\';var form=document.getElementById(\'popupForm\');if(form!=null){$(form).attr(\'target\', \'_blank\');form.task.value=\'emailpdf\';form.templateID.value=\'4fbfbfa6-0bc9-3dbb-0d5e-57ce330802c5\';form.submit();}else{alert(\'Error!\');}" /></li>');
                }
            }
        });
        $('#tab-actions').after('<li><input type="button" data-module-quote-id="'+ $("input[name='record']").val()+'" name="Create_Call" value="Create Call" id="Create_Call" class="button primary" onclick="$(document).create_call_back(this);" /></li>');
        $.fn.create_call_back = function (ele){
            var parent_id = $(ele).attr('data-module-quote-id');
            var parent_module = 'AOS_Quotes';
            $.ajax({
                url: "index.php?entryPoint=create_call_back_pe_internal_note&parent_id=" +parent_id +"&parent_module="+parent_module,
                success:function (data) {
                    if(data != 'error'){
                        window.open('/index.php?module=Calls&action=EditView&record='+data.trim(),'_blank');
                    }else{
                        window.open('/index.php?module=Calls&action=EditView&record=','_blank');
                    }
                }
            })
        }
        $.fn.send_solar_pdf = function (ele){
            var source = ele;
            var record_id= $(source).attr('data-module-quote-id') ;
            var type = $('#quote_type_c').val();
            var check_pricing = false;
            if(type == 'quote_type_solar'){
                $.ajax({
                    url: 'index.php?entryPoint=customComparePricing&record_id=' +record_id+ '&module=AOS_Quotes&type='+type,
                    async:false,
                    success: function(data){
                        if(data == 'not equal'){
                            var html_comfirm_price =  "<p style='text-align:center;'>Customer price and Suggested price are not equal, do you still wish to email this quote</p>";
                        
                            var dialog = $(html_comfirm_price).dialog({
                                buttons: {
                                    "Yes": function() {
                                        beforeSendPDF(source);
                                        dialog.dialog('close');
                                    },
                                    "No":  function() { 
                                        dialog.dialog('close');
                                        return;
                                    }
                                },
                                width: 600
                            });
                        }else{
                            beforeSendPDF(source);
                        }
                    }
                });
            }else{
                beforeSendPDF(source);
            }
        }
    });
    function Alert_Assigned_User_Lock_Sent_Email(){
        var userName = $('input[name="current_user_name"]').val(),
            userId  = $('input[name="current_user_id"]').val(),
            assignedId = $('#assigned_user_id').attr('data-id-value'),
            assignedName = $('#assigned_user_id').text().trim(),
            confirm_content = '',
            checkbox_Assigned_User_Lock = $('#assigned_user_lockout_c').is(':checked'); 
         
        if(checkbox_Assigned_User_Lock && userId != assignedId){
            confirm_content = "<h4>Hi "+userName + ", This Quote is assigned to user " + assignedName + ".Do you want send email from this quotes?</h4>";
        }
        return confirm_content;
    };
     // Dung code -- Alert Assigned User Lock
     if(module_sugar_grp1 == 'AOS_Quotes'){
        var confirm_Alert_Assigned_User_Lock = Alert_Assigned_User_Lock_Sent_Email();
        if(confirm_Alert_Assigned_User_Lock != ''){   
            var dialog = $(confirm_Alert_Assigned_User_Lock).dialog({
                buttons: {
                    "Yes": function() { 
                        dialog.dialog('close');
                    },
                    "Cancel":  function() {
                        dialog.dialog('close');
                        $(document).find("#send_tesla_quote_pdf,#send_solar_pricing,#send_quote_pdf,#solar_design_complete").hide();
                        return;
                    }
                }
            });
        } 
    }
})

/**VUT-Check quote date vs today*/
function compareToday() {
    var today = new Date().toLocaleString('default',{day: '2-digit', month: '2-digit',year: 'numeric'}); //mm/dd/yyyy
    today = new Date(today);
    var date = $('#quote_date_c').text().split(' ')[0].split('/'); //dd/mm/yyyy
    var new_date = date[1]+'/'+date[0]+'/'+date[2];
    new_date = new Date(new_date); 
    if (today.getTime()!==new_date.getTime()) {
        if (!confirm('Quote Date is not today, still want to send out?')) {
            return false;
        } else return true;
    } else return true;
}

function beforeSendPDF(source){
    var record_id= $(source).attr('data-module-quote-id') ;
    $.ajax({
        url: 'index.php?entryPoint=customgetlastdatefile&record_id=' +record_id+ '&module=AOS_Quotes',
        async: true,
        success: function(data){
            if(data.trim() == 'Not Data'){
                var html_comfirm = "<p style='text-align:center;'>This Quote haven\'t SG files (Quote PDF or Design options).</br>Do you want to download all PDF and pictures ?</p>";
            }else {
                var html_comfirm = "<p style='text-align:center;'>The latest SG files  have been downloaded at "+data+".</br>Do you want to download newest SG Quote PDF and Design options.</p>";
            }
            var dialog = $(html_comfirm).dialog({
                buttons: {
                    "Yes": function() { 
                        var pre_install_photos_c = $('#pre_install_photos_c').text().trim();
                        var quote_sg = $('#solargain_quote_number_c').text().trim();
                        var lead_sg = $("#solargain_lead_number_c").val();
                        if($(source).attr('data-email-type') == 'solar_design_complete'){
                            var url_download = "?entryPoint=CustomDownloadPDF&module=AOS_Quotes&type=quote&record_id="
                            + record_id
                            $.ajax({
                                url: url_download,
                                async: false,
                                success: function(data){
                                }
                            })
                        }

                        if($(source).attr('data-email-type') == 'send_tesla_quote'){
                            var url_download = "?entryPoint=CustomDownloadPDF&module=AOS_Quotes&type=tesla&record_id="
                            + record_id
                            $.ajax({
                                url: url_download,
                                type: 'GET',
                                async: false,
                                success: function(data){
                                }
                            });
                        }

                        if($(source).attr('data-email-type') == 'solar_design_complete') {
                            $.ajax({
                                url: "?entryPoint=downloadSGImageOptions&module=AOS_Quotes&quote_solorgain="+quote_sg+"&pre_install_photos_c="+pre_install_photos_c+'&SGleadID='+lead_sg,
                                async: false,
                                success: function(data){
                                }
                            });
                        }
                        $(document).openComposeViewModal_SendSolarQuote(source); 
                        dialog.dialog('close');
                        
                    },
                    "No":  function() { 
                        $(document).openComposeViewModal_SendSolarQuote(source); 
                        dialog.dialog('close');
                        return;
                    }
                },
                width: 600
            });
        }
    })
}
function getParameterByName(name, url) {
    if (!url) return null;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$(function () {
    'use strict';

    $( document ).ready(function() {
        $("#subpanel_aos_quotes_pe_internal_note_1 tr").each(function() {
            var recordId = getParameterByName('record', $(this).find("td:nth-child(4) a").attr('href'));
            var module_name = getParameterByName('module', $(this).find("td:nth-child(4) a").attr('href'));
            var url = "/index.php?entryPoint=customLeadHoverEmail&record=" + recordId + "&module=" + module_name ;
            if(recordId != null ) {
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

        //thienpb code  -- show button preview pdf
        $('#tab-actions').after("<li><button style='margin: 0px 3px;' type='button' class='button' id='detail_preview_pdf'>Preview Quote PDF</button></li>");
        //tuan code - add button Sanden Quoter
        // $("#tab-actions").after('<li><a class="button moveSandenForm" href="https://pure-electric.com.au/pesandenform" target="_blank" style="color:white;padding:8.5px;background: #f08377">SANDEN QUOTE FORM</a></li>')
        $(document).on('click','#detail_preview_pdf',function(){
            var quote_type = '';
            if($("#quote_type_c").val() == 'quote_type_solar'){
                quote_type = 'quote';
            }else if($("#quote_type_c").val() == 'quote_type_tesla'){
                quote_type = 'tesla';
            }
            if(quote_type != ''){
                SUGAR.ajaxUI.showLoadingPanel();
                $.ajax({
                    url: 'index.php?entryPoint=CustomDownloadPDF&module=AOS_Quotes&type='+quote_type+'&record_id='+$("input[name='record']").val()+'&preview=true',
                    async: true,
                    success: function(result) {
                        // debugger;
                        if(result == '' && typeof result == undefined)return;
                        var data = $.parseJSON(result);                      
                        $(".modal_preview_pdf").remove();
                        var html = '<div class="modal fade modal_preview_pdf" tabindex="-1" role="dialog">'+
                                        '<div class="modal-dialog" style="width:60%">'+
                                            '<div class="modal-content">'+
                                                '<div class="modal-header" style="padding:5px;">'+
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                    '<h4 class="modal-title" id="title-generic"><center>'+data['pdf_file']+'</center></h4>'+
                                                '</div>'+
                                                '<div class="modal-body" style="padding:1px;">'+
                                                    '<embed style="height:calc('+$('body').height()+'px - 100px);width:100%;" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+data['generate_ID']+'/'+encodeURIComponent(data['pdf_file'])+'" type="application/pdf"  />'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                        $("body").append(html);
                        $(".modal_preview_pdf").modal('show');
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }
                });
            }else{
                SUGAR.ajaxUI.showLoadingPanel();
                var filecontent;
                $.ajax({
                    url: "/index.php?entryPoint=generatePdf&templateID=4fbfbfa6-0bc9-3dbb-0d5e-57ce330802c5&task=pdf&module=AOS_Quotes&uid="+$("input[name='record']").val()+'&preview=yes&productType='+$('#quote_type_c').val(),
                    type: "GET",
                    async: false,
                    success: function(result, text, xhr){
                        filecontent = result;
                        var today = new Date();
                        var date = today.getDate()+(today.toLocaleString('default', { month: 'short' }))+today.getFullYear();
                        var file_name  = "Quote_"+$("#number").text().trim() +"_"+ $("#name").text().replace(" ","_").trim() + date+".pdf";
                        $(".modal_preview_pdf").remove();
                        var html = '<div class="modal fade modal_preview_pdf" tabindex="-1" role="dialog">'+
                                        '<div class="modal-dialog" style="width:60%">'+
                                            '<div class="modal-content">'+
                                                '<div class="modal-header" style="padding:5px;">'+
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                                                    '<h4 class="modal-title" id="title-generic"><center>'+file_name+'</center></h4>'+
                                                '</div>'+
                                                '<div class="modal-body" style="padding:1px;">'+
                                                    '<embed style="height:calc('+$('body').height()+'px - 100px);width:100%;" src="data:application/pdf;base64,'+filecontent+'" type="application/pdf"  />'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                        $("body").append(html);
                        $(".modal_preview_pdf").modal('show');
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }
                });
            }
        });

        //thienpb code -- button send solar option 
        if($("#quote_type_c").val() == 'quote_type_solar'){
            $('#tab-actions').after('<li><button type="button" class="button" id="send_solar_pricing" onclick="$(document).openComposeViewModal_SendSolarPricing(this);" data-email-type="send_solar_pricing"  data-module="AOS_Quotes" data-module-name="'+$("#name").text()+'" data-record-id="'+$("input[name='record']").val()+'">Send Solar Pricing Options</button></li>');
        }
        $("#tab-actions").after('<button data-email-type="follow_up" data-quote-name="'+$("#name").text()+'" data-email-address="'+$(".email-link").attr('data-email-address')+'" data-lead-id="'+$('#leads_aos_quotes_1leads_ida').attr('data-id-value')+'"   data-record-id="'+$('input[name="record"]').val()+'" type="button" id="quote_follow_up" class="button quote_follow_up" title="Quote Follow Up" data-module="Lead" onClick="SUGAR.quoteFollowUp(this);" >Quote Follow Up<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>');
        $('#quote_follow_up').after('<button type="button" class="button primary" id="send_site_inspection_request" onclick="$(document).openComposeViewModal(this);" data-email-type="send_site_inspection_request" data-module="AOS_Quotes" data-module-name="'+$("#name").text()+'" data-contact-name="'+$('#billing_contact_id').text()+'" data-record-id="'+$("input[name='record']").val()+'">Send Inspection Request</button>');
        // $("#quote_follow_up").after('<button style="margin: 0px 3px;background:#009acf;" type="button" id="btn_pe_daikin_form" class="button btn_pe_daikin_form" title="PE Daikin Form">Daikin Quote Form</button>');
        $("#quote_follow_up").after('<button style="margin: 0px 3px;background:#945596;" type="button" id="btn_pe_sanden_form_new" class="button btn_pe_sanden_form_new" title="PE Sanden Form">Sanden Quote Form</button>');
        $("#quote_follow_up").after('<button style="margin: 0px 3px;background:#f48c21;" type="button" id="btn_pe_solar_form" class="button btn_pe_solar_form" title="PE Solar Form">Solar Quote Form</button>');
        $("#quote_follow_up").after('<button style="margin: 0px 3px;background:#009acf;" type="button" id="btn_pe_daikin_new_form" class="button btn_pe_daikin_new_form" title="PE Daikin Form">Daikin Quote Form</button>');
        var lead_id = $('#leads_aos_quotes_1leads_ida').attr('data-id-value');
        $("#btn_pe_daikin_form").click(function(e) {
            if(lead_id != '') {
                window.open(
                    'https://pure-electric.com.au/pedaikinform?lead-id='+lead_id,
                    '_blank' // <- This is what makes it open in a new window.
                );
            } else {
                alert('No leads in quote, please add lead information !')
                // window.open(
                //     'https://pure-electric.com.au/pedaikinform',
                //     '_blank' // <- This is what makes it open in a new window.
                // );
            }
           
        });
        $("#btn_pe_daikin_new_form").click(function(e) {
            if(lead_id != '') {
                window.open(
                    'https://pure-electric.com.au/pedaikinform-new?lead-id='+lead_id,
                    '_blank' // <- This is what makes it open in a new window.
                );
            } else {
                alert('No leads in quote, please add lead information !')
                // window.open(
                //     'https://pure-electric.com.au/pedaikinform',
                //     '_blank' // <- This is what makes it open in a new window.
                // );
            }
        });
        $("#btn_pe_sanden_form_new").click(function(e) {
            if(lead_id != '') {
                window.open(
                    'https://pure-electric.com.au/pe-sanden-quote-form?lead-id='+lead_id,
                    '_blank' // <- This is what makes it open in a new window.
                );
            } else {
                alert('No leads in quote, please add lead information !')
                // window.open(
                //     'https://pure-electric.com.au/pedaikinform',
                //     '_blank' // <- This is what makes it open in a new window.
                // );
            }
            
        });
        $("#btn_pe_solar_form").click(function(e) {
            if(lead_id != '') {
                window.open(
                    'https://pure-electric.com.au/pesolarform?lead-id='+lead_id,
                    '_blank' // <- This is what makes it open in a new window.
                );
            } else {
                alert('No leads in quote, please add lead information !')
                // window.open(
                //     'https://pure-electric.com.au/pedaikinform',
                //     '_blank' // <- This is what makes it open in a new window.
                // );
            }
        });
        SUGAR.quoteFollowUp =  function(elem){
            $(document).openComposeViewModal_quoteFollowUp(elem);
        };
        $.fn.openComposeViewModal_quoteFollowUp = function (source) {
            var self = this;
            self.emailComposeView = null;
            var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
            var composeBox = $('<div></div>').appendTo(opts.contentSelector);
            composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
            composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
            composeBox.show();
            var record_id= $(source).attr('data-record-id');
            var email_type = $(source).attr('data-email-type');
            var lead_id = $(source).attr('data-lead-id');
            var email = $(".email-link").attr('data-email-address');
            var product_type = $("#quote_type_c").val();
            var lead_source_company = $("#lead_source_co_c").val();
            var name_quote = $(source).attr('data-quote-name');
            $.ajax({
                type: "GET",
                cache: false,
                url: 'index.php?module=Emails&action=ComposeView&in_popup=1&quote_id='+record_id+((email_type!="")? ("&email_type="+email_type):"")+((lead_id!="")? ("&lead_id="+lead_id):"")+((email!='') ? "&email_address="+email : "")+((product_type!='') ? "&product_type="+product_type : "")+((lead_source_company!='') ? "&lead_source_company="+lead_source_company : ""),
            }).done(function (data) {
                if (data.length === 0) {
                console.error("Unable to display ComposeView");
                composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
                return;
                }
                composeBox.setBody(data);
                self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();
                var quote_id =  $(self.emailComposeView).find('input[name="email_return_id"]').val();
                $(self.emailComposeView).find('#parent_type').val('AOS_Quotes');
                $(self.emailComposeView).find('#parent_name').val(name_quote);
                $(self.emailComposeView).find('#parent_id').val(quote_id);
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
        }
        
    });
});

function create_generateUUID() {
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

function CopyToClipboard(generateUUID){
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
                var recordId = $("input[name='record']").val();
                $.ajax({
                    type: "POST", 
                    url: "index.php?entryPoint=Image_Site_Details_Clipboard_Popup", 
                    data: { img: image_data,id:generateUUID, record_id: recordId ,nameModule : 'AOS_Quotes' ,action: 'DetailView'}      
                    }).done(function(data_return){                
                        $('body').find("#Map_Template_Image").remove();
                        $('body').find("#google_map").remove();
                        var html_image_site_detail = '<img id="Map_Template_Image" style="border-radius:5px;background-color:#ffffff;border:1px solid #808080;height:auto;width:100%;max-width:220px;" alt="Map Template Image" src="/custom/include/SugarFields/Fields/Multiupload/server/php/files/'+ generateUUID +'/Image_Site_Detail.jpg?'+Date.now()+'">';
                        $('body').find("#group_custom_template_col_2_2").append(html_image_site_detail);
                        SUGAR.ajaxUI.hideLoadingPanel();                 
                    });
                document.body.removeChild(canvas);
            }
        });
    },10000);
}

//VUT-S-Preview email in Quote
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
$(function () {
    'use strict';
    $( document ).ready(function() {
        $('body').on('click','.preview_function',function(){
            var recordId = getParameterByName('record', $(this).parent().parent().find("td:nth-child(3) a").attr('href'));
            var url = "/index.php?entryPoint=customAccountHoverEmail&record=" + recordId;
            $.fancybox({
                maxWidth	: 800,
                maxHeight	: 600,
                fitToView	: false,
                width		: '70%',
                height		: '70%',
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none',
                href: url,
                type: 'ajax',
            })
        });
        // $('#tab-actions').after($('#tab-actions li:first').clone());
    });
});

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

        var self = this;

        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var  email_module  =  $(source).attr('data-module');
        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"") ;
                
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
            debugger;
            
            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');
            var populateModuleName = $(source).attr('data-module-name');
            // var populateEmailAddress = $(source).attr('data-email-address');
            // // get email address
            // if(typeof(pulateEmailAddress) == 'undefined'){
            //     populateEmailAddress = $("#group_custom_template_col_1").find(".email-link").attr('data-email-address');
            // }
            
            // if(typeof($(source).attr('data-contact-name')) != 'undefined'){
            //     populateEmailAddress = $(source).attr('data-contact-name') + ' <' + populateEmailAddress + '>';
            // }else if (populateModuleName !== '' && typeof($(source).attr('data-contact-name')) == 'undefined') {
            //     populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            // }
        
            // $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_id"]').val(populateModuleRecord);
            $(self.emailComposeView).find('input[name="return_module"]').val(populateModule);         
            
            
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
