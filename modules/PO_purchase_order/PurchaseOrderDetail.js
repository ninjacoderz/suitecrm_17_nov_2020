$(function () {
    'use strict';
    // Generate uinique id
    $( document ).ready(function() {
        $('#tab-actions').after($('#tab-actions li:nth-child(6)').clone());
        $('#tab-actions').after($('#convert_to_bill_button').parent().clone());
        $('#tab-actions').after('<button type="button" class="button primary" id="convertToWareHouseLog"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Convert to WareHouseLog </button>');
        $('#tab-actions').after("<li><button type='button' class='button' id='detail_preview_pdf_purchase'>Preview Purchase PDF</button></li>");
        $('#tab-actions').parent().append('<li><input type="button" id="delivery_coming" value="Delivery coming" class="button primary" data-email-type="delivery_coming" onclick="$(document).openComposeViewModal(this);" data-module="PO_purchase_order" data-module-name="'+ $("#name").text() +'" data-contact-name="'+$('#contact_id_c').text()+'"  data-record-id="'+ $("input[name='record']").val() +'" /></li>');
        $('#tab-actions').parent().append('<li><input type="button" id="delivery_schedule" value="Delivery Schedule" class="button primary" data-email-type="delivery_schedule" onclick="$(document).openComposeViewModal(this);" data-module="PO_purchase_order" data-module-name="'+ $("#name").text() +'" data-contact-name="'+$('#contact_id_c').text()+'"  data-record-id="'+ $("input[name='record']").val()  +'" /></li>');
        //VUT - Add button Email Freight Company
        $('#tab-actions').parent().append('<li><button type="button" class="button primary" id="email_freight_company" value="FREIGHT COMPANY" class="button primary" data-email-type="freight_company" onclick="$(document).openComposeViewModal_Freight_Company(this);" data-module="PO_purchase_order" data-module-name="'+ $("#name").text() +'" data-contact-name="COPE '+$('#shipping_address_state').val()+'"  data-record-id="'+ $("input[name='record']").val()  +'"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>EMAIL FREIGHT COMPANY</button></li>');

        $(document).on('click','#detail_preview_pdf_purchase',function(){
            // var quote_type = '' ;
            // if( $("#quote_type_c").val() == 'quote_type_solar'){
            //     quote_type = 'quote';
            // }else if($("#quote_type_c").val() == 'quote_type_tesla'){
            //     quote_type = 'tesla';
            // }
            // if(quote_type != ''){
                // SUGAR.ajaxUI.showLoadingPanel();
                // $.ajax({
                //     url: 'index.php?entryPoint=CustomDownloadPDF&type='+quote_type+'&module=PO_purchase_order&record_id='+$("input[name='record']").val()+'&preview=true',
                //     async: true,
                //     success: function(result) {
                //         var data = $.parseJSON(result);                      
                //         $(".modal_preview_pdf").remove();
                //         var html = '<div class="modal fade modal_preview_pdf" tabindex="-1" role="dialog">'+
                //                         '<div class="modal-dialog" style="width:60%">'+
                //                             '<div class="modal-content">'+
                //                                 '<div class="modal-header" style="padding:5px;">'+
                //                                     '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>'+
                //                                     '<h4 class="modal-title" id="title-generic"><center>'+data['file_name']+'</center></h4>'+
                //                                 '</div>'+
                //                                 '<div class="modal-body" style="padding:1px;">'+
                //                                     '<embed style="height:calc('+$('body').height()+'px - 100px);width:100%;" src="data:application/pdf;base64,'+data['pdf_content']+'" type="application/pdf"  />'+
                //                                 '</div>'+
                //                             '</div>'+
                //                         '</div>'+
                //                     '</div>';
                //         $("body").append(html);
                //         $(".modal_preview_pdf").modal('show');
                //         SUGAR.ajaxUI.hideLoadingPanel();
                //     }
                // });
            // }else{
                SUGAR.ajaxUI.showLoadingPanel();
                var filecontent;
                $.ajax({
                    url: "/index.php?entryPoint=generatePdf&templateID=3bd2f6d5-46f9-d804-9d5b-5a407d37d4c5&task=pdf&module=PO_purchase_order&uid="+$("input[name='record']").val()+'&preview=yes',
                    type: "GET",
                    async: false,
                    success: function(result, text, xhr){
                        filecontent = result;
                        var today = new Date();
                        var date = today.getDate()+(today.toLocaleString('default', { month: 'short' }))+today.getFullYear();
                        var file_name  = "Purchase Order_"+$("#number").text().trim() + $("#name").text().replace(" ","_").trim() + date+".pdf";
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
            // }
        });
        $('#convertToWareHouseLog').click(function(){
            var record = $("input[name='record']").val();
            var loc ='';
            $.ajax({
                url : "?entryPoint=customConvertPOToWareHouseLog&po_id="+record,
                type : 'GET',
                success: function (data) {
                    if(data != ''){
                        loc = 'index.php?module=pe_warehouse_log&action=EditView&record='+data;
                        window.open(loc);
                    }
                }
            });
        });
    });
})
(function ($) {
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var email_module  =  $(source).attr('data-module');
        var receiver_id  =  $('#contact_id_c').attr('data-id-value');

        if( receiver_id == ''){
            alert('Receiver cannot be found!');
            return;
        }
        if( $('#delivery_date_c').text() == "" ){
            alert('No delivery date!');
            return;
        }
        var email_address = "";
        $.ajax({
            url: "/index.php?entryPoint=getInvoiceInfo&receiver_id="+receiver_id+"&type_module_delivery=PO_purchase_order",
            type: "GET",
            async: false,
            success: function(data){
                var invoice_info = $.parseJSON(data);
                email_address = invoice_info.supplier_email;
            }
        });
        var self = this;

        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();

        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"")+'&email_plumber=plumber';
                
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
            var populateEmailAddress = $(source).attr('data-email-address');
            // get email address
            if(typeof(pulateEmailAddress) == 'undefined'){
                populateEmailAddress = email_address;
            }
            
            if(typeof($(source).attr('data-contact-name')) != 'undefined'){
                populateEmailAddress = $(source).attr('data-contact-name') + ' <' + populateEmailAddress + '>';
            }else if (populateModuleName !== '' && typeof($(source).attr('data-contact-name')) == 'undefined') {
                populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            }
        
            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
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

    //VUT - email Freight Company
    $.fn.openComposeViewModal_Freight_Company = function (source) {
        "use strict";
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var email_module  =  $(source).attr('data-module');
        
        var email_address = "";
        var cope = $('#local_freight_company_c').val();
        // var postcode = $('#shipping_address_postalcode').val();
        if( cope == "" ){
            alert("Please select local freight company");
            return;
        }
         switch (cope) {
            case "cope_act":
                email_address = "act@cope.com.au";
            break;
            case "cope_nsw":
                email_address = "nsw@cope.com.au";
            break;
            case "cope_qld":
                email_address = "qldcust@cope.com.au";
            break;
            case "cope_sa":
                email_address = "sa@cope.com.au";
            break;
            case "cope_vic":
                email_address = "vic@cope.com.au";
            break;
            case "cope_wa":
                email_address = "wa@cope.com.au";
            break;
         }
        
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();

        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"");
                
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
            var populateEmailAddress = $(source).attr('data-email-address');
            // get email address
            if(typeof(pulateEmailAddress) == 'undefined'){
                populateEmailAddress = email_address;
            }
            
            if(typeof($(source).attr('data-contact-name')) != 'undefined'){
                populateEmailAddress = $(source).attr('data-contact-name') + ' <' + populateEmailAddress + '>';
            }else if (populateModuleName !== '' && typeof($(source).attr('data-contact-name')) == 'undefined') {
                populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
            }
        
            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
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