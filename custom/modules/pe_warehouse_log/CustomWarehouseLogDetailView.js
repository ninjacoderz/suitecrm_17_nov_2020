$(function () {
    'use strict';
    // Generate uinique id
    $( document ).ready(function() {
        $('#tab-actions').parent().append('<li><input type="button" id="delivery_coming" value="Delivery coming" class="button primary" data-email-type="delivery_coming" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+ $("#sold_to_invoice_id").text() +'"  data-record-id="'+ $("#sold_to_invoice_id").attr('data-id-value') +'" /></li>');
        $('#tab-actions').parent().append('<li><input type="button" id="delivery_schedule" value="Delivery Schedule" class="button primary" data-email-type="delivery_schedule" onclick="$(document).openComposeViewModal(this);" data-module="AOS_Invoices" data-module-name="'+ $("#sold_to_invoice_id").text() +'"  data-record-id="'+ $("#sold_to_invoice_id").attr('data-id-value') +'" /></li>');
        //VUT - Create button Email Freight Company
        $('#tab-actions').parent().append('<button type="button" class="button primary" id="email_freight_company" value="FREIGHT COMPANY" class="button primary" data-email-type="freight_company" onclick="$(document).openComposeViewModal_Freight_Company(this);" data-module="pe_warehouse_log" data-module-name="'+ $("#name").text() +'"  data-record-id="'+ $("input[name='record']").val()  +'"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>EMAIL FREIGHT COMPANY</button>');
        // $('#tab-actions').after($('#tab-actions li:first').clone());
        if($('#connote').html() != ""){
            if($("#carrier").val() == "Australia Post"){
                $('#connote').parent().append('<a target="_blank" href="https://auspost.com.au/mypost/track/#/details/' +$('#connote').html() +'">'+$('#connote').html()+'</a>');
            } else if($("#carrier").val() == "COPE"){
                $('#connote').parent().append('<a target="_blank" href="http://tracking.cope.com.au/track.php?consignment=' +$('#connote').html() +'">'+$('#connote').html()+'</a>');
            }else if($("#carrier").val() == "TNT"){
                $('#connote').html('<a target="_blank" href="https://www.tnt.com/express/en_gc/site/shipping-tools/track.html?searchType=con&cons='+$('#connote').html()+'">'+$('#connote').html()+'</a>')
            }
        }
    });
})
(function ($) {
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var email_module  =  $(source).attr('data-module');
        if(record_id == ''){
            alert('Please Save before !');
            return;
        }
        var email_address = "";
        var client_name = "";

        $.ajax({
            url: "/index.php?entryPoint=getInvoiceInfo&record_id="+$("#sold_to_invoice_id").attr('data-id-value')+"&type_module=pe_warehouse_log",
            type: "GET",
            async: false,
            success: function(data){
                var invoice_info = $.parseJSON(data);
                email_address = invoice_info.client_address;
                client_name = invoice_info.billing_account;
            }
        });
        var self = this;

        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();

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

            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');
            var populateModuleName = $(source).attr('data-module-name');
            var populateEmailAddress = $(source).attr('data-email-address');
            // get email address
            if(typeof(pulateEmailAddress) == 'undefined'){
                populateEmailAddress = email_address;
            }
            
            if(typeof(client_name) != 'undefined'){
                populateEmailAddress = client_name + ' <' + populateEmailAddress + '>';
            }else if (populateModuleName !== '' && typeof(client_name) == 'undefined') {
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

    //VUT - Email Freight Company >> copyright from PO
    $.fn.openComposeViewModal_Freight_Company = function (source) {
        "use strict";
        var record_id= $(source).attr('data-record-id') ;
        var email_type = $(source).attr('data-email-type');
        var email_module  =  $(source).attr('data-module');

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