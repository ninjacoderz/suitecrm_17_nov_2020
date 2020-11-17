(function ($) {
    $.fn.openComposeViewModal = function (source) {
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
}(jQuery));

$(function () {
    'use strict';
    // Generate uinique id
    debugger;
    $( document ).ready(function() {
        $('#tab-actions').after($('<li></li>').append($("a[data-module='Accounts']:last").clone() ));
        // $('#tab-actions').after($('#tab-actions li:first').clone());
        //<a href="javascript:void(0);" onclick=" $(document).openComposeViewModal(this);" data-module-name="Riley -" data-email-address="riley.cumming@hotmail.com">riley.cumming@hotmail.com</a>
        
        var record_id = $("#account_id").attr('data-id-value');
        var full_name = $("#account_id").text();
        $.ajax({
            url: "?entryPoint=getContactPhoneNumber&module_name=Accounts&action=GetInfoForSendEmail&record_id=" + record_id,
            context: document.body,
            async: false
        }).done(function (data) {
            var json = $.parseJSON(data);
            $('#tab-actions').after('<li><a class="link-email"  onclick="$(document).openComposeViewModal(this);" data-module="Accounts" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ json.email +'">'+json.email+'</a></li>');
            $('#tab-actions').after('<li><a class="link-email"  data-email-type="email-acceptance" onclick="$(document).openComposeViewModal(this);" data-module="Accounts" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ json.email +'">Send Acceptance</a></li>');

            var oppo_record_id = $("input[name='parent_id']").val();
            $('#tab-actions').after('<li><a id="reminder-email" data-email-type="reminder-email" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ oppo_record_id  +'" data-module-name="'+ full_name +'" data-email-address="'+json.email+'">Reminder Email</a></li>');
            //End Dung code
        });
        
        //$('#tab-actions').after('<li><a id="sanden-email" data-email-type="first-sanden" onclick="$(document).openComposeViewModal(this);" data-module="Leads" data-record-id="'+ record_id +'" data-module-name="'+ full_name +'" data-email-address="'+ to_email +'">Sanden Email</a></li>');

    });
   

})