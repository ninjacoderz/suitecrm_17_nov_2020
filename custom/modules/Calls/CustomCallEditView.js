$(function () {
    'use strict'; 
    $( document ).ready(function() {
        function Display_information_account (){
            var record_id = $("#parent_id").val();
            var module_name = $("#parent_type").val();
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&action=getInfoFromCall&module_name=" + module_name + "&record_id=" + record_id,
            }).done(function (data) {
                $('body').find('#information_account_contact').remove();
                $("#date_start_date").closest('.edit-view-row-item').append(data);
            });
        }
        Display_information_account();
        YAHOO.util.Event.addListener("parent_id", "change", Display_information_account);
        
        $("#CANCEL").after(
            ' <button type="button" id="save_and_create" class="button SaveAndCreate" title="Save and Create" onClick="SUGAR.SaveAndCreate(this);">Save and Create</button>'
        )
        SUGAR.SaveAndCreate = function (elem) {
            SUGAR.ajaxUI.showLoadingPanel();
            $("#EditView input[name='action']").val('Save');
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                success: function (data) {
                    SUGAR.ajaxUI.hideLoadingPanel();
                    $("#EditView").remove();
                    window.location.href  ='?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DCalls%26action%3DEditView%26return_module%3DCalls%26return_action%3DDetailView';
                }
            });
            return false;
        }
        
        // override custom display field  Start Date&Time
        $("#date_start_minutes").empty();
        var array_custom_minutes = ['00','05','10','15','20','25','30','35','40','45','50','55'];
        var date_start_date =  GLOBAL_REGISTRY['focus'].fields.date_start;
        var date_start_hour_minutes = '';
        var date_start_minutes = '';
        if(date_start_date.indexOf(' ') !== -1){
             date_start_hour_minutes = date_start_date.split(' ');
            if(date_start_date.indexOf(':') !== -1){
                 date_start_minutes = parseInt((date_start_hour_minutes[1].split(':'))[1]);
            }    
        }
        $.each(array_custom_minutes, function(k,v){
            var checked = '';
            if(date_start_minutes == v){
                checked = 'SELECTED' ;
            }
            var html_option = '<option '+checked+' value="'+v+'">'+v+'</option>';
            $("#date_start_minutes").append(html_option);
        });
        
    });

    $(document).ready(function(){
        //VUT-S-Add button Quote Follow Up
        $("#CANCEL").parent().append('<button style="margin: 0px 2px;" type="button" id="quote_follow_up" title="Quote Follow Up" class="button quote_follow_up">Quote Follow Up</button>');
        // tuan code
        $("#CANCEL").parent().append(
            ' <button type="button" data-contact-name="'+$('#parent_name').val()+'" data-email-type="calls_voice_email" data-module="AOS_Quotes" data-module-name="'+$('#name').val()+'" data-record-id="'+ $('input[name="record"]').val() +'" id="voice_email" onclick="$(document).openComposeViewModal_VoiceEmail_Thanks(this);" title="VOICE EMAIL" class="button button primary voice_email">VOICEMAIL EMAIL</button>'
        )
        $("#CANCEL").parent().append(
            ' <button type="button" data-contact-name="'+$('#parent_name').val()+'" data-email-type="tks_for_voice_email" data-module="AOS_Quotes" data-module-name="'+$('#name').val()+'" data-record-id="'+ $('input[name="record"]').val() +'" id="voice_email" onclick="$(document).openComposeViewModal_VoiceEmail_Thanks(this);" title="THANKS FOR CALL EMAIL" class="button button primary voice_email">THANKS FOR CALL EMAIL</button>'
        )
        $(document).on('click','#quote_follow_up',function(){
            SUGAR.ajaxUI.showLoadingPanel();
            var record_id = $('input[name="record"]').val();
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
                        width: 600,
                        buttons: {
                            Cancel : function(){
                                $(this).dialog("close");
                            },
                            OK : function() {
                                var selectedQuote = $('input[name="selectQuote"]:checked');
                                $(document).openComposeViewModal_quoteFollowUp(selectedQuote);
                                $(this).dialog("close");
                            }
                        }
                    });
                    SUGAR.ajaxUI.hideLoadingPanel();

                } else {
                    debugger;
                    var selectedQuote = $(json.html);
                    SUGAR.ajaxUI.hideLoadingPanel();
                    $(document).openComposeViewModal_quoteFollowUp(selectedQuote);
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
            var email = $("#detail_email").attr('data-email-address');
            var product_type = $(source).attr('data-product-type');
            var lead_source_company = $(source).attr('data-lead-source');
            var name_quote = $(source).attr('data-quote-name');

            // debugger;
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
        //VUT-E-Add button Quote Follow Up
        $.fn.openComposeViewModal_VoiceEmail_Thanks = function (source) {
        
            var record_id= $(source).attr('data-record-id') ;
            var email_type = $(source).attr('data-email-type');
            var email_module  =  $(source).attr('data-module');
            
            var email_address = "";
    
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
                var populateEmailAddress = $("#detail_email").attr('data-email-address');
                // // get email address
                // if(typeof(populateEmailAddress) == 'undefined'){
                //     populateEmailAddress = email_address;
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
    });

})