
$(document).ready(function(){
    // $('#tab-actions').after($('#tab-actions li:first').clone());
    $('#tab-actions').after('<li><input hidden type="button" id="send_trustpilop" value="TrustPilot" class="button primary"/></li>');
    $('#tab-actions').after('<li><input hidden type="button" id="product_review" value="Product Review" class="button primary" data-email-type="product_review" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-module-name="'+ $("#first_name").text()+' '+$("#last_name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('#tab-actions').after('<li><input hidden type="button" id="google_review" value="Google Review" class="button primary" data-email-type="google_review" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-module-name="'+ $("#first_name").text()+' '+$("#last_name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('#tab-actions').after('<li><input hidden type="button" id="word_of_mouth" value="Word of Mouth" class="button primary" data-email-type="word_of_mouth" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-module-name="'+ $("#first_name").text()+' '+$("#last_name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');    
    $('#tab-actions').after('<li><input hidden type="button" id="facebook" value="Facebook Review" class="button primary" data-email-type="facebook" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-module-name="'+ $("#first_name").text()+' '+$("#last_name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('#tab-actions').after('<li><input hidden type="button" id="methven_review" value="Methven Review" class="button primary" data-email-type="methven_review" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-module-name="'+ $("#first_name").text()+' '+$("#last_name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('#tab-actions').after('<li><input hidden type="button" id="pe_methven_review" value="PE + Methven Review" class="button primary" data-email-type="pe_methven_review" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-module-name="'+ $("#first_name").text()+' '+$("#last_name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    //VUT - Add button create Google Contact
    $('#tab-actions').after('<li><input type="button" id="create_GG_contact" onclick="createContactGoogle(this);" value="Create Google Contact" class="button primary" data-module="Contacts" data-record-id="'+ $("input[name='record']").val() +'"/></li>');
    //dung code -button US7 TIPS 
    $('#tab-actions').after('<li><input type="button" id="email_us7_tips" value="US7 Tips" class="button primary" data-email-type="us7_tips" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-module-name="'+ $("#first_name").text()+' '+$("#last_name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    //dung code -button Sanden TIPS 
   $("#tab-actions").after(
        '&nbsp;<button data-email-type="sanden_tips" data-record-id="'+$('input[name="record"]').val()+'" data-module-name="'+ $("#first_name").text() + ' ' + $("#last_name").text() +'" type="button" id="email_sanden_tips" class="button email_sanden_tips" title="Sanden Tips" data-module="Contacts" onClick="popupSandenProduct(this);" >Sanden Tips<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )
    //dung code-- button Send Email TrustPilot 
    $('body').on('click','#send_trustpilop',function(){
        var answer = confirm("Are you want to send email TrustPilot to customer?")
        if (answer) {
            $.ajax({
                url : "?entryPoint=SendEmailTrustPilop&module=Contact&action=EditView&record_id="+$('input[name="record"]').val(),
                success: function (data) {
                   if(data.trim() != 'success'){
                        alert('UnSuccess!');
                   }else{
                        alert('Success!');
                   }
                }
            });
        }
        else {
            return false;
        }
    
    })

    //hide Contact GG 
    $(document).find('#create_GG_contact').hide();
    
    //add SEND REVIEW button 
    $('#tab-actions').after('<li><button style="background:#46a049;" type="button" name="send_review" value="Send Review" id="send_review" class="button primary"><i class="glyphicon glyphicon-star"></i>Send Review</button></li>');
    $('#tab-actions').after('<li><input hidden type="button" id="product_review_sanden" value="Product Review PE + Sanden" class="button primary" data-email-type="product_review_sanden" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-module-name="'+ $("#first_name").text()+' '+$("#last_name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('#tab-actions').after('<li><input hidden type="button" id="product_review_daikin_us7" value="Product Review PE + Daikin US7" class="button primary" data-email-type="product_review_daikin_us7" onclick="$(document).openComposeViewModal(this);" data-module="Contacts" data-module-name="'+ $("#first_name").text()+' '+$("#last_name").text() +'" data-record-id="'+ $("input[name='record']").val() +'" /></li>');
    $('body').on('click','#send_review',function(){
    
        if($('#primary_address_state').val().toLowerCase() == 'vic' && $("#email1_span a.email-link").text().trim().toLowerCase().indexOf("@gmail.com")!=-1 ){
            //case 1: IF GMAIL (xxxx@gmail.com) and IF Contact State = VIC
            var array_suggested = {'google_review':'Google Review',
            'word_of_mouth':'Word Of Mouth',
            'send_trustpilop': 'TrustPilot',
            'product_review': 'Product Review PE',
            'facebook': 'Facebook',
            'product_review_sanden': 'Product Review PE + Sanden',
            'product_review_daikin_us7': 'Product Review PE + Daikin US7',
            'methven_review': 'Methven',
            'pe_methven_review': 'PE + Methven',
        };
        }else if(($('#primary_address_state').val().toLowerCase() == 'vic' && !($("#email1_span a.email-link").text().trim().toLowerCase().indexOf("@gmail.com")!=-1 ))){
            //case 2: IF NON GMAIL (xxxx@gmail.com) and IF Contact State = VIC
            var array_suggested = {
            'word_of_mouth':'Word Of Mouth',
            'send_trustpilop': 'TrustPilot',
            'product_review': 'Product Review PE',
            'facebook': 'Facebook',
            'google_review':'Google Review',
            'product_review_sanden': 'Product Review PE + Sanden',
            'product_review_daikin_us7': 'Product Review PE + Daikin US7',
            'methven_review': 'Methven',
            'pe_methven_review': 'PE + Methven',};
        }else{
            //case 3: IF NON GMAIL (xxxx@gmail.com) and IF Contact NON State = VIC
            var array_suggested = {
                'send_trustpilop': 'TrustPilot',
                'product_review': 'Product Review PE',
                'facebook': 'Facebook',
                'word_of_mouth':'Word Of Mouth',
                'google_review':'Google Review',
                'product_review_sanden': 'Product Review PE + Sanden',
                'product_review_daikin_us7': 'Product Review PE + Daikin US7',
                'methven_review': 'Methven',
                'pe_methven_review': 'PE + Methven',};
        }
        console.log(array_suggested);
        popupSendReview(array_suggested);
    });

    function popupSendReview(array_suggested) {

        var popupListHtml = '<div id="popupSendReview" title="Select Option Send Review" class="row">';
        var index = 0;
        $.each( array_suggested, function( key, value ) {
            popupListHtml += '<div class="form-check form-check-inline checkbox-primary"> \
                                    <input class="form-check-input" type="radio" name="option_SendReview" '+((index == 0)?'checked' : '')+' id="id_option_'+key+'" value="'+key+'"> \
                                    <label class="form-check-label" for="id_option_'+key+'">'+value+'</label>\
                                    </div>';
            index ++;
        });
        popupListHtml += '</div>';
        $(popupListHtml).dialog({
            modal:true,
            minWidth: 500,
            resizable: false,
            buttons: {
                Cancel : function(){
                    $(this).dialog("close");
                    $(this).dialog('destroy').remove()
                },
                OK : function() {
                    var selected_SendReview = $('input[name="option_SendReview"]:checked').val();
                    $("#"+selected_SendReview).trigger('click');
                    $(this).dialog("close");
                    $(this).dialog('destroy').remove()
    
                }
            }
        });
    }
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
        var sanden_product = $(source).attr('data-sanden-product');
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
        var url_email = 'index.php?module=Emails&action=ComposeView&in_popup=1'+ ((record_id!="")? ("&record_id="+record_id):"") + ((email_type!="")? ("&email_type="+email_type):"") + ((email_module!="")? ("&email_module="+email_module):"")   + ((sanden_product!="")? ("&sanden_product="+sanden_product):"") ;
        
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
            var populateEmailAddress = $(".email-link").attr('data-email-address');
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

//VUT-S-Create popup when click Sandan Tip
function popupSandenProduct(e) {
    var popupList = $('<div id="popupSanden" title="Sanden Product">'
                    + '<input name="sandenProduct" type="radio" value="G2">G2<br>'
                    + '<input name="sandenProduct" type="radio" value="G3">G3<br>'
                    + '<input name="sandenProduct" type="radio" value="G4">G4<br>'
                    + '</div>');
    popupList.dialog({
        modal:true,
        buttons: {
            Cancel : function(){
                $(this).dialog("close");
            },
            OK : function() {
                $(e).attr('data-sanden-product', $('input[name="sandenProduct"]:checked').val());
                $(document).openComposeViewModal(e);
                $(this).dialog("close");

            }
        }
    });
}
//VUT-S-Create popup when click Sandan Tip

/**
 * VUT - create Contact Google
 */
function createContactGoogle(e) {
    let module = $(e).attr('data-module');
    let record_id = $(e).attr('data-record-id');
    $.ajax({
        url : `?entryPoint=createGoogleContact&record_id=${record_id}`,
        success: function (data) {
            var newWindow = window.open(data, 'name', 'height=600,width=450');
            if (window.focus) {
              newWindow.focus();
            }        
        }
    });
}