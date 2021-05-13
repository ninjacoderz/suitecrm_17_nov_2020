function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
$(document).ready(function(){
    // $('#tab-actions').after($('#tab-actions li:first').clone()); 
    function Display_information_account (){
        var module_name = getParameterByName('module', $("#parent_id").parent().find("a").attr('href'));
        var record_id = getParameterByName('record',$("#parent_id").parent().find("a").attr('href'));
        if (module_name !='' && record_id!='') {
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&action=getInfoFromCall&module_name=" + module_name + "&record_id=" + record_id,
            }).done(function (data) {
                $('body').find('#information_account_contact').remove();
                $("#CONTACT_NAME").closest('.detail-view-row').find('.detail-view-row-item:nth-child(1)').append(data);
            });
        }
    }
    Display_information_account();

    //VUT-S-Add button Quote Follow Up sqsEnabled
    $('#tab-actions').parent().append('<button type="button" id="quote_follow_up" title="Quote Follow Up" class="button button primary quote_follow_up">Quote Follow Up</button>');

    $(document).on('click','#quote_follow_up',function(){
        SUGAR.ajaxUI.showLoadingPanel();
        var record_id = $('input[name="record"]').val();
        $('#parent_name').attr('id','old_parent_name');
        $.ajax({
            url: '?entryPoint=APIGetQuoteFollowUp&module=Calls&record_id='+record_id,
            type: 'GET',
            cache: false,
        }).done(function (data){
            var json = $.parseJSON(data);
            if (json.count >1 ) {
                var popupList = $(json.html);
                popupList.dialog({
                    modal:true,
                    width: 700,
                    buttons: {
                        Cancel : function(){
                            $(this).dialog("close");
                        },
                        OK : function() {
                            var selectedQuote = $('input[name="selectQuote"]:checked');
                            $(document).openComposeViewModal_quoteFollowUp(selectedQuote);
                            $(this).dialog("close");
                            $('#old_parent_name').attr('id','parent_name');
                        }
                    }
                });
                SUGAR.ajaxUI.hideLoadingPanel();

            } else {
                var selectedQuote = $(json.html);
                SUGAR.ajaxUI.hideLoadingPanel();
                $(document).openComposeViewModal_quoteFollowUp(selectedQuote);
                $('#old_parent_name').attr('id','parent_name');
            }
        });
    });
    /**Function open Quote Follow Up */
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
        var product_type = $(source).attr('data-product-type');
        var lead_source_company = $(source).attr('data-lead-source');
        var name_quote = $(source).attr('data-quote-name');
        var call_id= $('input[name="record"]').val();

        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1&quote_id='+record_id+((email_type!="")? ("&email_type="+email_type):"")+((lead_id!="")? ("&lead_id="+lead_id):"")+((email!='') ? "&email_address="+email : "")+((product_type!='') ? "&product_type="+product_type : "")+((lead_source_company!='') ? "&lead_source_company="+lead_source_company : "")+"&call_id="+call_id+"&module_type=call",
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
    //VUT-E-Add button Quote Follow Up

    //VUT - S - Clone button Delete
    $('#tab-actions').parent().append($('#tab-actions li:nth-child(3)').clone().css('margin','0px 1px')); 
    //VUT - E - Clone button Delete
    
});