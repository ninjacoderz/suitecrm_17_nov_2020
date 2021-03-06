
function generateUUID() {
    var d = new Date().getTime();
    if (window.performance && typeof window.performance.now === "function") {
        d += performance.now();
        ; 
    }
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    return uuid;
};

function autoLookupAddress(field){
    $("#"+field+"_address_street").autocomplete({
        source: function( request, response ) {
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
            console.log(ui.item.value);
            var value =  ui.item.value.split(",");
            var address1 = value[0];
            var address2 = value[1];

            $("#"+field+"_address_street").val(address1);

            var address3 = address2.split("  ");

            $("#"+field+"_address_city").val(address3[0].trim());
            $("#"+field+"_address_state").val(address3[1].trim());
            $("#"+field+"_address_postalcode").val(address3[2].trim());

            return false;
        }
    });
}

function showLinkPO(){
    $("#POLink").remove();
    if($('#po_purchase_order_id_c').val() != ''){
        $('#po_purchase_order_id_c').parent().append("<div id='POLink'><a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record="+$('#po_purchase_order_id_c').val()+"'>Open PO</a></div>");
    }
}

(function ($) {
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        //  Use ajax to get more information
        
        var record_id= $(source).attr('data-record-id') ;
        var owner_email = "";
        $.ajax({
            url: "?entryPoint=getContactPhoneNumber&module_name=Accounts&action=GetInfoForSendEmail&record_id=" + record_id,
            context: document.body,
            async: false
        }).done(function (data) {
            var json = $.parseJSON(data);
            owner_email = json.email;
        });

        
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var po_id= $(source).attr('data-po-id') ;
        var email_type = $(source).attr('data-email-type');
        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1' + ((po_id!="")? ("&po_id="+po_id):"") + ((email_type!="")? ("&email_type="+email_type):"")+ ((date_seek_install_date!="")? ("&date_seek_install_date="+encodeURIComponent(date_seek_install_date)):"")+ ((invoice_id!="")? ("&invoice_id="+invoice_id):"") ,
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
                var populateEmailAddress = owner_email;
                
                if (populateModuleName !== '') {
                    populateEmailAddress = populateModuleName + ' <' + populateEmailAddress + '>';
                }

                $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
                $(self.emailComposeView).find('#parent_type').val(populateModule);
                $(self.emailComposeView).find('#parent_name').val(populateModuleName);
                $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
                $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);
                $(self.emailComposeView).find('input[name=return_id]').val($(source).attr('data-po-id'));
                $(self.emailComposeView).find('input[name=return_module]').val('pe_bills');
                //dung code --- input hidden type seek-install-date-from-po
                if (email_type == 'seek-install-date-from-po' ){
                    var html_checkbox_Convert_Solar_Opportunity = 
                '<div class="col-xs-12 col-sm-12 edit-view-row-item hidden">'
                + '<div class="col-xs-12 col-sm-2 label" data-label="">'
                + 'Seek_Install_Date_From_PO_Check:</div>'
                + '<div class="col-xs-12 col-sm-8 edit-view-field " type="bool" field="send_sms" colspan="3">'
                + '<input type="text" checked id="po_id_email_Seek_Install_Date_From_PO_Check" name="po_id_email_Seek_Install_Date_From_PO_Check" value="'+po_id +'" title="" tabindex="0">'                              
                +'</div>'
                +'</div>';
                    $(self.emailComposeView).find('#EditView_tabs .tab-content .edit-view-row').append(html_checkbox_Convert_Solar_Opportunity);
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


$(document).ready(function(){

    if ($('input[name="installation_pdf_c"]').val() == "") {
        $('input[name="installation_pdf_c"]').val(generateUUID());
    }
    autoLookupAddress('billing');
    autoLookupAddress('shipping');
    showLinkXero();
    showLinkPO();
    createLinkProduct();

    YAHOO.util.Event.addListener("po_purchase_order_id_c", "change", function(){
        showLinkPO();
        return false;
    });

    $('#line_items_span').on('change', '.product_name', function (e) {
        setTimeout(function() {
            createLinkProduct();
        }, 500)
    })

    $("#SAVE").after(
        ' <button style="background:#009acf;" type="button" id="CRUD_Xero_Bill" class="button CRUD_Xero_Bill" title="Create And Update Xero Bill" onClick="SUGAR.CRUD_Xero_Bill(this);" >Create & Update Xero <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
    );
  
    SUGAR.CRUD_Xero_Bill= function(elemt){
        
        var html_alert = '';
        if($("input[name='record']").val() == ''){
            html_alert += '<h4 class="text-danger">Bill is not saved! Please Save and Reload Page?</h4>';
        }
        
        if( html_alert != ''){
            $('#alert_modal').find('.modal-body').empty();
            $('#alert_modal').find('.modal-body').append(html_alert); 
            $('#alert_modal').modal('show'); 
            return false;
        }
        // save invoice
        SUGAR.ajaxUI.showLoadingPanel(); 
        $("#EditView input[name='action']").val('Save');
        $.ajax({
            type: $("#EditView").attr('method'),
            url: $("#EditView").attr('action'),
            data: $("#EditView").serialize(),
            async:false,
            success: function (data) { 
                
                var bill_id = $("input[name='record']").val();
              
                if(bill_id !='')  {
                    // create and update invoice xero
                    var url_xero_bill = "/index.php?entryPoint=CRUD_Bill_Xero&method=create&from_action=button" + '&record='+ encodeURIComponent($('input[name="record"]').val());
                    $.ajax({
                        url:url_xero_bill,
                        success:function(data){   
                            SUGAR.ajaxUI.hideLoadingPanel();                             
                                try {
                                    var json = $.parseJSON(data);
                                    console.log(json);
                                    if( $('#xero_bill_c').val() == ''){
                                        $('#xero_bill_c').val(json.bill_xero_id);
                                    }
                                    showLinkXero();
                                    if(json.msg != ''){
                                        $('#alert_modal').find('.modal-body').empty();
                                        $('#alert_modal').find('.modal-body').append(json.msg); 
                                        $('#alert_modal').modal('show'); 
                                        return false;
                                    }else{
                                        $('#alert_modal').find('.modal-body').empty();
                                        $('#alert_modal').find('.modal-body').append('Push and update XERO Bill done!'); 
                                        $('#alert_modal').modal('show'); 
                                        return false;
                                    }

                                } catch (e) {
                                    return false;
                                }                   
                        }
                    });
                }
                
            }
        });

       
    }

    function showLinkXero(){
        xeroID = $('#xero_bill_c').val();
        $("#xeroLink").remove();
        if(xeroID != ''){
            $('#xero_bill_c').after("<div id='xeroLink'><a target='_blank' href='https://go.xero.com/AccountsPayable/Edit.aspx?InvoiceID="+xeroID+"'>Xero Bill Link</a></div>");
        }
    }
    function createLinkProduct() {
        setTimeout(function() {
            if($('.product_link').length) {
                $('.product_link').remove();
            }
            $('input[id*=product_product_id]').each(function(index) {
                var product_id = $(this).val();
                if($(this).val() != '') {
                    $(this).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ product_id +'">Link</a></div>');
                }
            })
        },500);
    }
});
