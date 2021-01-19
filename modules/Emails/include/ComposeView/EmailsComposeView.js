/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

(function ($) {
  /**
   *
   * @param options
   * @returns {jQuery|HTMLElement}
   * @constructor
   */
  $.fn.EmailsComposeView = function (options) {
    "use strict";
    var self = $(this);
    var opts = $.extend({}, $.fn.EmailsComposeView.defaults, options);
    var jQueryFormComposeView = $('form[name="ComposeView"]')[0];

    self.attachFile = undefined;
    self.attachNote = undefined;
    self.attachDocument = undefined;
    /**
     * Determines if the signature comes before the reply to message
     * @type {boolean}
     */
    self.prependSignature = false;

    /**
     * Defines the buttons that are displayed when the user focuses in on a to, cc and bcc field.
     *
     * @param data-open-popup-module - The module to popup
     * @param data-open-popup-email-address-field - the field name that holds a single email address (assumes email1)
     *
     * To add a button (using popup behavior)
     * $('.compose-view#id).EmailsComposeView().qtipBar +=
     * '<button class="btn btn-default btn-sm btn-qtip-bar" '+
     * 'data-open-popup-module="Contacts" data-open-popup-email-address-field="email1">'+'</button>';
     *
     * To add a button (using your own behavior)
     * $('.compose-view#id).EmailsComposeView().qtipBar += '<button class="btn btn-default btn-sm"></button>';
     *
     * @type {string}
     */
    self.qtipBar =
      '<button class="btn btn-default btn-sm btn-qtip-bar" data-open-popup-module="Contacts" data-open-popup-email-address-field="email1" title="' + SUGAR.language.translate('Emails', 'LBL_INSERT_CONTACT_EMAIL') + '"><span class="glyphicon"><img src="themes/' + SUGAR.themes.theme_name + '/images/sidebar/modules/Contacts.svg"></span></button>' +
      '<button class="btn btn-default btn-sm btn-qtip-bar" data-open-popup-module="Accounts" title="' + SUGAR.language.translate('Emails', 'LBL_INSERT_ACCOUNT_EMAIL') + '"><span class="glyphicon"><img src="themes/' + SUGAR.themes.theme_name + '/images/sidebar/modules/Accounts.svg"></span></button>' +
      '<button class="btn btn-default btn-sm btn-qtip-bar" data-open-popup-module="Prospects" title="' + SUGAR.language.translate('Emails', 'LBL_INSERT_TARGET_EMAIL') + '"><span class="glyphicon"><img src="themes/' + SUGAR.themes.theme_name + '/images/sidebar/modules/Prospects.svg"></span></button>' +
      '<button class="btn btn-default btn-sm btn-qtip-bar" data-open-popup-module="Users" title="' + SUGAR.language.translate('Emails', 'LBL_INSERT_USER_EMAIL') + '"><span class="glyphicon"><img src="themes/' + SUGAR.themes.theme_name + '/images/sidebar/modules/Users.svg"></span></button>' +
      '<button class="btn btn-default btn-sm btn-qtip-bar" data-open-popup-module="Leads" title="' + SUGAR.language.translate('Emails', 'LBL_INSERT_LEAD_EMAIL') + '"><span class="glyphicon"><img src="themes/' + SUGAR.themes.theme_name + '/images/sidebar/modules/Leads.svg"></span></button>';

    /**
     * opens a popup when a btn-qtip-bar is clicked
     */
    self.handleQTipBarClick = function () {
      var module = $('#qtip_bar_module');
      module.val($(this).attr('data-open-popup-module'));

      var fields = {
        'id': 'qtip_bar_id',
        'name': 'qtip_bar_name'
      }

      if (typeof $(this).attr('data-open-popup-email-address-field') === "undefined") {
        fields['email1'] = 'qtip_bar_email_address';
      } else {
        fields[$(this).attr('data-open-popup-email-address-field')] = 'qtip_bar_email_address';
      }

        $.fn.EmailsComposeView.setEmailAddressFieldFromPopup = function(resultData) {
            var contact_name = resultData.name_to_value_array.qtip_bar_name;
            var contact_email_address = resultData.name_to_value_array.qtip_bar_email_address;

            if (trim(contact_email_address) !== '') {
                var formatted_email_address = '';
                if (trim(contact_name) !== '') {
                    // use name <email address> format
                    formatted_email_address = contact_name + ' <' + contact_email_address + '>';
                } else {
                    // use email address
                    formatted_email_address = contact_email_address;
                }

                if (trim($(self.active_elementQTipBar).val()) === '') {
                    $(self.active_elementQTipBar).val(formatted_email_address);
                } else {
                    $(self.active_elementQTipBar).val(
                        $(self.active_elementQTipBar).val() + ', ' +
                        formatted_email_address
                    );
                }
            }
        };

      var popupWindow = open_popup(
        $(this).attr('data-open-popup-module'),
        600,
        400,
        "",
        true,
        false,
        {
          "call_back_function": '$.fn.EmailsComposeView.setEmailAddressFieldFromPopup',
          "form_name": "ComposeView",
          "field_to_name_array": fields
        },
        "single",
        false
      );
    };

    /**
     * Shows the qtip bar when the user focuses in on a to, cc and bcc field.
     *
     * To reuse this behaviour for an other field simply bind the focus event to this method call
     */
    self.showQTipBar = function () {
      self.active_elementQTipBar = this;
      $(this).qtip({
        content: {
          text: self.qtipBar
        },
        position: {
          my: 'bottom left',
          at: 'top left'
        },
        show: {solo: true, ready: true, event: false},
        hide: {event: false},
        style: {classes: 'emails-qtip'}
      });
      $(this).qtip("show");
      $(this).unbind('unfocus').blur(function (e) {
        var isButton = $(e.relatedTarget).hasClass('btn-qtip-bar');
        var isQtipContent = $(e.relatedTarget).hasClass('qtip-content');
        var isQtip = $(e.relatedTarget).hasClass('qtip-tip');

        if (isButton || isQtipContent || isQtip) {
          return false;
        }

        $(this).qtip("hide");
      });
      $('.btn-qtip-bar').unbind('click').click(self.handleQTipBarClick);
    };

    /**
     * @return string UUID
     */
    self.generateID = function () {
      "use strict";
      var characters = ['a', 'b', 'c', 'd', 'e', 'f', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
      var format = '0000000-0000-0000-0000-00000000000';
      return Array.prototype.map.call(format, function ($obj) {
        var min = 0;
        var max = characters.length - 1;

        if ($obj === '0') {
          var index = Math.round(Math.random() * (max - min) + min);
          $obj = characters[index];
        }

        return $obj;
      }).toString().replace(/(,)/g, '');
    };

    /**
     * confirms if form is valid
     * @returns {boolean}
     */
    self.isValid = function () {
      "use strict";
      return self.isToValid() &&
        self.isCcValid() &&
        self.isBccValid() &&
        self.isSubjectValid() &&
        self.isBodyValid();
    };

    /**
     * validates form and displays error
     * @returns {boolean}
     */
    self.validate = function () {
      var valid = self.isValid();
      if (valid === false) {
        if (typeof messageBox !== "undefined") {
          var mb = messageBox({size: 'lg'});
          mb.setTitle(SUGAR.language.translate('', 'ERR_INVALID_REQUIRED_FIELDS'));
          mb.setBody(self.translatedErrorMessage);

          mb.on('ok', function () {
            mb.remove();
          });

          mb.on('cancel', function () {
            mb.remove();
          });

          mb.show();
        } else {
          alert(self.translatedErrorMessage);
        }
      }
      return valid;
    };

    /**
     * Is the To field valid
     * @returns {boolean}
     */
    self.isToValid = function () {
      "use strict";
      var emailAddresses = $(self).find('[name=to_addrs_names]').val().split('/[,;]/');

      if (self.isValidEmailAddresses(emailAddresses)) {
        return true;
      }

      self.setValidationMessage('to_addrs_names', 'LBL_HAS_INVALID_EMAIL_TO');
      return false;
    };

    /**
     * Is the CC field valid
     * @returns {boolean}
     */
    self.isCcValid = function () {
      "use strict";

      var cc = $(self).find('[name=cc_addrs_names]').val();
      var emailAddresses = cc.split('/[,;]/');

      if (self.isValidEmailAddresses(emailAddresses) || cc === '') {
        return true;
      }

      self.setValidationMessage('cc_addrs_names', 'LBL_HAS_INVALID_EMAIL_CC');
      return false;
    };

    /**
     * Is the BCC field valid
     * @returns {boolean}
     */
    self.isBccValid = function () {
      "use strict";
      var bcc = $(self).find('[name=bcc_addrs_names]').val();
      var emailAddresses = bcc.split('/[,;]/');

      if (self.isValidEmailAddresses(emailAddresses) || bcc === '') {
        return true;
      }

      self.setValidationMessage('bcc_addrs_names', 'LBL_HAS_INVALID_EMAIL_BCC');
      return false;
    };

    /**
     * Is the Subject field valid
     * @returns {boolean}
     */
    self.isSubjectValid = function () {
      "use strict";

      if ($(self).find('[name=name]').val() !== '') {
        return true;
      }

      self.setValidationMessage('name', 'LBL_HAS_EMPTY_EMAIL_SUBJECT');
      return false;
    };

    /**
     * Is the Body field valid
     * @returns {boolean}
     */
    self.isBodyValid = function () {
      "use strict";

      if ($(self).find('#description').val() !== '') {
        return true;
      }

      self.setValidationMessage('description_html', 'LBL_HAS_EMPTY_EMAIL_BODY');
      return false;
    };

    /**
     *
     * @param field string name
     * @param label string eg LBL_OK
     */
    self.setValidationMessage = function (field, label) {
      "use strict";
      self.translatedErrorMessage = SUGAR.language.translate('Emails', label);
    };

    /**
     * Determines if a set of email addresses are valid
     * @param emailAddresses array|object eg ['a@example.com', 'b@example.com']
     * @returns {boolean}
     */
    self.isValidEmailAddresses = function (emailAddresses) {
      "use strict";
      if (typeof emailAddresses === 'object') {
        for (var i = 0; i < emailAddresses.length; i++) {
          emailAddresses[i] = (emailAddresses[i] !== '') && isValidEmail(emailAddresses[i]);
        }
        if (emailAddresses.indexOf(false) === -1) {
          return true;
        }
      }

      return false;
    };


    self.updateSignature = function () {
      //thienpb fix
      var inboundId = $('#ComposeView input[name=inbound_email_id]').val();
      if(typeof inboundId == undefined) {
        var inboundId = $('#from_addr_name').find('option:selected').attr('inboundId');
      }
      if (inboundId === undefined) {
        console.warn('Unable to retrieve selected inbound id in the "From" field.');
        return false;
      }

      var signatureElement = $('<div></div>')
        .addClass('email-signature');
      var signatures = $(self).find('.email-signature');
      var htmlSignature = null;
      var plainTextSignature = null;

      // Find signature
      $.each(signatures, function (index, value) {
        if ($(value).attr('data-inbound-email-id') === inboundId) {

          if ($(value).hasClass('html')) {
            htmlSignature = $(value).val();
          } else if ($(value).hasClass('plain')) {
            plainTextSignature = $(value).val();
          }
        }
      });

      if (
        htmlSignature === null &&
        plainTextSignature === null
      ) {
        console.warn('Unable to retrieve signature from document.');
        return false;
      }

      if (htmlSignature === null) {
        // use plain signature instead
        $(plainTextSignature).appendTo(signatureElement);
      } else if (plainTextSignature === null) {
        // use html signature
        $(htmlSignature).appendTo(signatureElement);
      } else {
        $(htmlSignature).appendTo(signatureElement);
      }

      if (tinymce.editors.length < 1) {
        console.warn('unable to find tinymce editor');
        return false;
      }

      var body = tinymce.activeEditor.getContent();
      if (body === '') {
        // BinhNT Make change
        tinymce.activeEditor.setContent('' + signatureElement[0].outerHTML, {format: 'html'});
      } else if ($(body).hasClass('email-signature')) {
        var newBody = $('<div></div>');
        $(body).appendTo(newBody);
        $(newBody).find('.email-signature').replaceWith(signatureElement[0].outerHTML);
        tinymce.activeEditor.setContent(newBody.html(), {format: 'html'});
      } else {
        // reply to / forward
        if (self.prependSignature === true) {
           // BinhNT change
          tinymce.activeEditor.setContent('' + signatureElement[0].outerHTML + body, {format: 'html'});
        } else {
          tinymce.activeEditor.setContent(body + signatureElement[0].outerHTML, {format: 'html'});
        }
      }
    };
    
    self.updateFromInfos = function () {
      var infos = $('#from_addr_name').find('option:selected').attr('infos');
      if(infos === undefined) {
        console.warn('Unable to retrieve selected infos in the "From" field.');
        return false;
      } 
      
      if(!$('#from_addr_name_infos').length) {
          $('#from_addr_name').parent().append('<span id="from_addr_name_infos"></span>');
      }
      
      $('#from_addr_name_infos').html(infos);
      
    };

    /**
     *
     * @param editor
     */
    self.tinyMceSetup = function (editor) {
      var html = $(self).find('#description_html').val();

      //dung code -- auto setup content default when click link email from detailview Account, Contact, Leads
      if(html == ''){
        if( typeof(module_sugar_grp1) !== 'undefined' || module_sugar_grp1 !== ''
        || typeof(action_sugar_grp1) !== 'undefined' || action_sugar_grp1 !== ''){
          var array_module_active = ['Leads','Accounts','Contacts'];
          if(array_module_active.includes(module_sugar_grp1) && action_sugar_grp1 == 'DetailView'){
              switch (module_sugar_grp1) {
                case 'Contacts':
                    var first_name = $("#first_name").text();
                  break;
                case 'Accounts':
                    var full_name = $("#name").text();
                    var array_name = full_name.split(' ');
                    var first_name = array_name[0];
                  break;              
                default:
                    var full_name = $("#full_name").text();
                    var array_name = full_name.split(' ');
                    var first_name = array_name[0];
                  break;
              }

            html = '<div dir="ltr"><div>Hi '+first_name+',?</div><div style="font-family:Arial, Helvetica, sans-serif;font-size:14px;line-height:22.4px;color:#444444;padding:0px;margin:0px;">?</div>';
        }
      }
     
      }
      editor.on('init', function () {
        this.getDoc().body.style.fontName = 'tahoma';
        this.getDoc().body.style.fontSize = '13px';
      if (html !== null) {
        editor.setContent('' + html);
      }
    });
  
    editor.on('change', function () {
      // copy html to plain
      $(self).find('.html_preview').html(editor.getContent());
      $(self).find('input#description_html').val(editor.getContent());

      // if($('input[name="inbound_email_id"]').val() !== "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" && $('input[name="inbound_email_id"]').val()!=='8dab4c79-32d8-0a26-f471-59f1c4e037cf') {
      //   $(self).find('#bcc_addrs_names').val("Lee Andrewartha <lee.andrewartha@pure-electric.com.au>");
      // }

      $(self).find('textarea#description').val($(self).find('.html_preview').text());
      });

      editor.on('SetContent', function () {
        // debugger
        // copy html to plain
        $(self).find('.html_preview').html(editor.getContent());
        $(self).find('input#description_html').val(editor.getContent());
        if ($(self).find('#cc_addrs_names').val().indexOf('sa@cope.com.au') == -1) { //if has cc_addr has <sa@cope.com.au> then break
          $(self).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
        } 

        // if($('input[name="inbound_email_id"]').val() !== "58cceed9-3dd3-d0b5-43b2-59f1c80e3869" && $('input[name="inbound_email_id"]').val()!=='8dab4c79-32d8-0a26-f471-59f1c4e037cf') {
        //   $(self).find('#bcc_addrs_names').val("Lee Andrewartha <lee.andrewartha@pure-electric.com.au>");
        // }

        $(self).find('textarea#description').val($(self).find('.html_preview').text());
      });
    };

    /**
     *
     * @event sendEmail
     * @event sentEmailError
     * @event sentEmailAlways
     * @event sentEmail
     * @returns {boolean}
     */
    self.onSendEmail = function () {
      $(self).trigger("sendEmail", [self]);

      // Tell the user we are sending an email
      var mb = messageBox();
      mb.hideHeader();
      mb.hideFooter();
      document.activeElement.blur();
      mb.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
      mb.show();
      mb.on('ok', function () {
        "use strict";
        mb.remove();
        return false;
      });

      mb.on('cancel', function () {
        "use strict";
        mb.remove();
      });

      var fileCount = 0;
      // Use FormData v2 to send form data via ajax
      var formData = new FormData(jQueryFormComposeView);

      $(this).find('input').each(function (inputIndex, inputValue) {
        if ($(inputValue).attr('type').toLowerCase() !== 'file') {
          if ($(inputValue).attr('name') === 'action') {
            formData.append('refer_' + $(inputValue).attr('name'), $(inputValue).val());
            formData.append($(inputValue).attr('name'), 'send');
          } else if ($(inputValue).attr('name') === 'send') {
            formData.append($(inputValue).attr('name'), 1);
          } else {
            formData.append($(inputValue).attr('name'), $(inputValue).val());
          }
        }
      });

      $(this).find('select').each(function (i, v) {
        if (typeof $(v).attr('is_file') === 'undefined') {
          formData.append($(v).attr('name'), $(v).val());
        }
      });

      $(this).find('textarea').each(function (i, v) {
        formData.append($(v).attr('name'), $(v).val());
      });

      $(this).find('button').each(function (i, v) {
        formData.append($(v).attr('name'), $(v).val());
      });


      $(this).find('input[type=checkbox]').each(function (i, v) {
        formData.append($(v).attr('name'), $(v).prop('checked'));
      })

      // BinhNT

      if( $('#number_client') !== 'undefined'){
        var client_number =  $('#number_client').val().trim();
        if(client_number != ""){
          formData.append('number_client', client_number);
        }
      }

      $.ajax({
        type: "POST",
        data: formData,
        cache: false,
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        url: $(this).attr('action')
      }).done(function (response) {
        "use strict";
        console.log('Data return action send : ',response);
        if(response == '' && typeof response == undefined){
          mb.showHeader();
          mb.setBody("Error sending this email. Please reload browser and try to send it again.");
          mb.showFooter();
          $(self).trigger("sentEmailError", [self, response]);
        }else{
          response = JSON.parse(response);
          if (typeof response.errors !== "undefined") {
            mb.showHeader();
            mb.setBody(response.errors.title);
            mb.showFooter();
            $(self).trigger("sentEmailError", [self, response]);
          } else {
            mb.showHeader();
            mb.setBody(response.data.title);
            mb.showFooter();

            // If the user is viewing the form in the standard view
            if ($(self).find('input[type="hidden"][name="return_module"]').val() !== '' 
            && $(self).find('input[type="hidden"][name="return_id"]').val() !== ''
            && $(self).find('input[type="hidden"][name="return_action"]').val() !== '') {
              mb.on('ok', function () {
              //debugger
                var url = 'index.php?';
                if(($('#' + self.attr('id') + ' input[type="hidden"][name="quote_parent_id"]').val() != '') && typeof($('#' + self.attr('id') + ' input[type="hidden"][name="quote_parent_id"]').val()) !== 'undefined' ){
                    url = url + 'module=AOS_Quotes&action=EditView&record=' + $('#' + self.attr('id') + ' input[type="hidden"][name="quote_parent_id"]').val();
                }else{
                  var module = $('#' + self.attr('id') + ' input[type="hidden"][name="return_module"]').val();
                  if (module !== undefined) {
                    url = url + 'module=' + module;
                  }

                var action = $('#' + self.attr('id') + ' input[type="hidden"][name="return_action"]').val();
                if (action !== undefined) {
                  url = url + '&action=' + action;
                }

                  var record = $('#' + self.attr('id') + ' input[type="hidden"][name="return_id"]').val();
                  if (record !== undefined) {
                    url = url + '&record=' + record;
                  }
                }

                location.href = url;
              });
            } else {
              mb.on('ok', function () {
                // The user is viewing in the modal view
                $(self).trigger("sentEmail", [self, response]);
              });

            }
            
            //dung code - add template sms request adress in popupcomposerview - in module Leads
              if( typeof(module_sugar_grp1) !== 'undefined' || module_sugar_grp1 !== ''
                || typeof(action_sugar_grp1) !== 'undefined' || action_sugar_grp1 !== ''){
                if(module_sugar_grp1 == 'Leads' && action_sugar_grp1 == 'EditView'){
                    var emails_email_templates_idb = $('#emails_email_templates_idb').val();
                    if(emails_email_templates_idb == '383cde5c-de72-3902-2a9a-5b5008c452d0' && response.data.title == 'Email sent'){
                      $('#email_send_status_c').val('sent');
                      if($('#status').val() == 'Assigned') {
                          $('#status').val('In Process');
                      }   
                    }
                    //dung code -- seek install date
                    
                    var Seek_Install_Date_From_Leads_Check = $('#Seek_Install_Date_From_Leads_Check').val();
                    if(Seek_Install_Date_From_Leads_Check !== '' && response.data.title == 'Email sent'){
                      
                      var currentdate =  new Date().toLocaleString("en-US", {timeZone: "Australia/Melbourne"})
                      
                      var datetime = currentdate.getDate() + "/" + ("0" + (currentdate.getMonth() + 1)).slice(-2) + "/" + currentdate.getFullYear() ;
                    
                      $('#seek_install_date_c_date').val(datetime);
              
                      var hours = currentdate.getHours();
                      hours = ("0" + hours).slice(-2);
                      $('#seek_install_date_c_hours').val(hours);
              
                      var minutes = currentdate.getMinutes();
                      minutes = minutes - (minutes % 15);
                      if (minutes == 0)
                      {
                          minutes = '00';
                      }
                      $('#seek_install_date_c_minutes').val(minutes);
              
                      $('#seek_install_date_c').val(datetime + ' ' + hours + ':' + minutes);          
                    }
                }
              }

            //dung code - add template sms request adress in popupcomposerview - in module Leads
          
            if( typeof(module_sugar_grp1) !== 'undefined' || module_sugar_grp1 !== ''
            || typeof(action_sugar_grp1) !== 'undefined' || action_sugar_grp1 !== ''){
              if(module_sugar_grp1 == 'PO_purchase_order' && action_sugar_grp1 == 'EditView'){
                var seek_install_date_from_po_check = $('#po_id_email_Seek_Install_Date_From_PO_Check').val();
                if(seek_install_date_from_po_check !== '' && response.data.title == 'Email sent'){
                  
                  var currentdate = new Date();
                  
                  var datetime = currentdate.getDate() + "/" + ("0" + (currentdate.getMonth() + 1)).slice(-2) + "/" + currentdate.getFullYear() ;
                
                  $('#seek_install_time_c_date').val(datetime);
          
                  var hours = currentdate.getHours();
                  hours = ("0" + hours).slice(-2);
                  $('#seek_install_time_c_hours').val(hours);
          
                  var minutes = currentdate.getMinutes();
                  minutes = minutes - (minutes % 15);
                  if (minutes == 0)
                  {
                      minutes = '00';
                  }
                  $('#seek_install_time_c_minutes').val(minutes);
          
                  $('#seek_install_time_c').val(datetime + ' ' + hours + ':' + minutes);          
                }
            }
          }
            //thienpb code popular time sent to
            if( typeof(module_sugar_grp1) !== 'undefined' || module_sugar_grp1 !== ''
                || typeof(action_sugar_grp1) !== 'undefined' || action_sugar_grp1 !== ''){
                if(module_sugar_grp1 == 'AOS_Quotes' && action_sugar_grp1 == 'EditView'){
                    var emails_email_templates_idb = $('#emails_email_templates_idb').val();
                    if(emails_email_templates_idb == 'a8dbc136-588b-7213-9cbf-5bd0063f4de9' && response.data.title == 'Email sent'){
                      
                      $("#stage").val('Delivered')
                      var currentdate = new Date();
                      var datetime = currentdate.getDate() + "/" + ("0" + (currentdate.getMonth() + 1)).slice(-2) + "/" + currentdate.getFullYear() ;
                      $('#time_sent_to_client_c_date').val(datetime);
                      var hours = currentdate.getHours();
                      hours = ("0" + hours).slice(-2);
                      $('#time_sent_to_client_c_hours').val(hours);
              
                      var minutes = currentdate.getMinutes();
                      minutes = minutes - (minutes % 15);
                      if (minutes == 0){
                          minutes = '00';
                      }
                      $('#time_sent_to_client_c_minutes').val(minutes);
                      $('#time_sent_to_client_c').val(datetime + ' ' + hours + ':' + minutes); 
                    }
                }
              }
          }
        }
      }).fail(function (response) {
        "use strict";
        mb.showHeader();
        mb.setBody(response.errors.title);
        $(self).trigger("sentEmailError", [self, response]);
      }).always(function (data) {
        $(self).trigger("sentEmailAlways", [self, data]);
      });


      return false;
    };


    /**
     * @event sendEmail
     * @returns {boolean}
     */
    self.sendEmail = function (e) {
      "use strict";
      e.preventDefault();
      $(this).find('[name=action]').val('send');
      if (self.validate()) {
        $(this).submit();
      }
      return false;
    };


    /**
     * @event attachFile
     * @returns {boolean}
     */
    self.attachFile = function (event) {
      "use strict";
      event.preventDefault();
      $(self).trigger("attachFile", [self]);

      // Add the file input onto the page
      var id = self.generateID();

      var fileGroupContainer = $('<div></div>')
        .addClass('attachment-group-container')
        .appendTo(self.find('.file-attachments'));

      var fileInput = $('<input>')
        .attr('type', 'file')
        .attr('id', 'file_' + id)
        .attr('name', 'email_attachment[]')
        .attr('multiple', 'true')
        .appendTo(fileGroupContainer);


      var fileLabel = $('<label></label>')
        .attr('for', 'file_' + id)
        .addClass('attachment-blank')
        .html('<span class="glyphicon glyphicon-paperclip"></span>')
        .appendTo(fileGroupContainer);

      // use the label to open file dialog
      fileLabel.click();

      // handle when the a file is selected
      fileInput.change(function (event) {

        if (event.target.files.length === 0) {
          fileGroupContainer.remove();
          return false;
        }
        if (event.target.files.length > 1) {
          $(fileLabel.addClass('label-with-multiple-files'));
        } else {
          $(fileLabel.removeClass('label-with-multiple-files'));
        }

        fileLabel.html('');
        fileLabel.empty();

        if (fileGroupContainer.find('.attachment-remove').length === 0) {
          var removeAttachment = $('<a class="attachment-remove"><span class="glyphicon glyphicon-remove"></span></a>');
          fileGroupContainer.append(removeAttachment);
          // handle when user removes attachment
          removeAttachment.click(function () {
            fileGroupContainer.remove();
          });
        }

        for (var i = 0; i < event.target.files.length; i++) {
          var file = event.target.files[i];
          var name = file.name;
          var size = file.size;
          var type = file.type;

          var fileContainer = $('<div class="attachment-file-container"></div>');
          fileContainer.appendTo(fileLabel);
          // Create icons based on file type
          if (type.indexOf('image') !== -1) {
            fileContainer.addClass('file-image');
            fileContainer.append('<span class="attachment-type glyphicon glyphicon-picture"></span>');
          } else if (type.indexOf('audio') !== -1) {
            fileContainer.addClass('file-audio');
            fileContainer.append('<span class="attachment-type glyphicon glyphicon-music"></span>');
          } else if (type.indexOf('video') !== -1) {
            fileContainer.addClass('file-video');
            fileContainer.append('<span class="attachment-type glyphicon glyphicon-film"></span>');
          } else if (type.indexOf('zip') !== -1) {
            fileContainer.addClass('file-video');
            fileContainer.append('<span class="attachment-type glyphicon glyphicon-compressed"></span>');
          } else {
            fileContainer.addClass('file-other');
            fileContainer.append('<span class="attachment-type glyphicon glyphicon-file"></span>');
          }
          fileContainer.append('<span class="attachment-name"> ' + name + ' </span>');
          fileContainer.append('<span class="attachment-size"> ' + self.humanReadableFileSize(size, true) + ' </span>');

          fileLabel.removeClass('attachment-blank');

        }

      });

      return false;
    };

    /**
     * @event attachDocument
     * @returns {boolean}
     */
    self.attachDocument = function (event) {
      "use strict";
      event.preventDefault();
      $(self).trigger("attachDocument", [self]);

      // Add the file input onto the page
      var id = self.generateID();

      var fileGroupContainer = $('<div></div>')
        .addClass('attachment-group-container')
        .appendTo(self.find('.document-attachments'));

      var fileInput = $('<input>')
        .attr('type', 'hidden')
        .attr('id', 'file_' + id)
        .attr('name', 'documentId')
        .attr('data-file-input', 'documentId')
        .appendTo(fileGroupContainer);


      //language=JQuery-CSS
      var document_attachment_id = $('[name=document_attachment_id]');
      var fileInputID = undefined;
      if (document_attachment_id.length === 0) {
        fileInputID = $('<input>')
          .attr('type', 'hidden')
          .attr('name', 'document_attachment_id')
          .appendTo(fileGroupContainer.closest('.attachments'));
      } else {
        fileInputID = document_attachment_id;
      }

      $.fn.EmailsComposeView.selectDocumentFromPopup = function(resultData) {
        set_return(resultData);
        if (fileInputID.val().length === 0) {
          // id is empty
          fileGroupContainer.remove();
          self.updateDocumentIDs();
        } else {
          // id is full
          if (fileGroupContainer.find('.attachment-remove').length === 0) {
            var removeAttachment = $('<a class="attachment-remove"><span class="glyphicon glyphicon-remove"></span></a>');
            fileGroupContainer.append(removeAttachment);
            // handle when user removes attachment
            removeAttachment.click(function () {
              fileGroupContainer.remove();
              self.updateDocumentIDs();
            });
          }

          fileInput.val(fileInputID.val());
          fileLabel.empty();

          var fileContainer = $('<div class="attachment-file-container"></div>');
          fileContainer.appendTo(fileLabel);
          fileContainer.append('<span class="attachment-name"> ' + fileInputName.val() + ' </span>');

          fileLabel.removeClass('attachment-blank');

          self.updateDocumentIDs();
        }
      };

      //language=JQuery-CSS
      var document_attachment_name = $('[name=document_attachment_name]');
      var fileInputName = undefined;
      if (document_attachment_name.length === 0) {
        fileInputName = $('<input>')
          .attr('type', 'hidden')
          .attr('name', 'document_attachment_name')
          .appendTo(fileGroupContainer.closest('.attachments'));
      } else {
        fileInputName = document_attachment_name;
      }
      fileInputName.val('');

      var fileLabel = $('<label></label>')
        .attr('for', 'file_' + id)
        .addClass('attachment-blank')
        .html('<img src="themes/' + SUGAR.themes.theme_name + '/images/sidebar/modules/Documents.svg">')
        .appendTo(fileGroupContainer);

      var showSelectDocumentDialog = function () {
        fileInputID.val('');
        fileInputName.val('');
        var popupWindow = open_popup(
          "Documents",
          600,
          400,
          "",
          true,
          false,
          {
            "call_back_function": '$.fn.EmailsComposeView.selectDocumentFromPopup',
            "form_name": "ComposeView",
            "field_to_name_array": {
              "id": "document_attachment_id",
              "name": "document_attachment_name"
            }
          },
          "single",
          false
        );

      };

      // Mimic the file attachment behaviour
      fileLabel.click(showSelectDocumentDialog);
      // Call the select document dialog
      fileLabel.click();

      return false;
    };

    self.updateDocumentIDs = function () {
      self.find('.document-attachments')
        .find('.attachment-group-container')
        .each(function (index, value) {
          $(value).find('[data-file-input]').attr('name', 'documentId' + index);
        });
    };

    /**
     * @event saveDraft
     * @returns {boolean}
     */
    self.saveDraft = function (e) {
      "use strict";
      e.preventDefault();
      $(this).closest('[name=action]').val('SaveDraft');

      if (self.validate()) {
        self.onSavingDraft();
      }
      return false;
    };

    //thienpb code
    self.saveDraftSchedule = function (e) {
      "use strict";
      e.preventDefault();
      $(this).closest('[name=action]').val('SaveDraft');

      if (self.validate()) {
        self.onSavingDraft();
      }
      return false;
    };

    self.onSavingDraft = function () {
      "use strict";
      $(self).trigger("saveDraft", [self]);
      // Tell the user we are sending an email
      var mb = messageBox();
      mb.hideHeader();
      mb.hideFooter();
      mb.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
      mb.show();

      mb.on('ok', function () {
        "use strict";
        mb.remove();
        //thien code
        setTimeout(function(){
          $('.popup-sendMail').toggleClass('active-popup-sms');
            $(".message-box.in").remove();
            $(".modal-backdrop").remove();
        },100);
        return false;
      });

      mb.on('cancel', function () {
        "use strict";
        mb.remove();
        setTimeout(function(){
          $('.popup-sendMail').toggleClass('active-popup-sms');
            $(".message-box.in").remove();
            $(".modal-backdrop").remove();
        },100);
      });

      var fileCount = 0;
      // Use FormData v2 to send form data via ajax
      var formData = new FormData(jQueryFormComposeView);

      $(this).find('input').each(function (i, v) {
        if ($(v).attr('type').toLowerCase() !== 'file') {
          var name = $(v).attr('name');
          if (name === 'action') {
            formData.append(name, 'SaveDraft');
          } else if (name === 'send') {
            formData.append(name, 0);
          } else {
            formData.append(name, $(v).val());
          }
        }
      });

      $(this).find('select').each(function (i, v) {
        if (typeof $(v).attr('is_file') === 'undefined') {
          formData.append($(v).attr('name'), $(v).val());
        }
      });

      $(this).find('textarea').each(function (i, v) {
        formData.append($(v).attr('name'), $(v).val());
      });

      $(this).find('button').each(function (i, v) {
        formData.append($(v).attr('name'), $(v).val());
      });

      //thienpb custom set schedule time
      if($(this).find("#datetimepicker").val() != ''){
        formData.append('schedule_time',(new Date($(this).find("#datetimepicker").val()).getTime())/1000);
        formData.append('from_phone_number',$("#from_phone_number").val());
      }
      $.ajax({
        type: "POST",
        data: formData,
        cache: false,
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        url: 'index.php?module=Emails'
      }).done(function (response) {
        "use strict";
        response = JSON.parse(response);
        if (typeof response.errors !== "undefined") {
          mb.showHeader();
          mb.setBody(response.errors.title);
          mb.showFooter();
          $(self).trigger("saveEmailError", [self, response]);
        } else {
          mb.showHeader();
          mb.setBody(response.data.title);
          mb.showFooter();
          $(self).trigger("saveEmailSuccess", [self, response]);

          var id = undefined;
          if ($(self).find('[name=id]').length === 0) {
            id = $('<input>').attr('type', 'hidden').attr('name', 'id').val(response.data.id);
            id.appendTo($(self).closest('[name=ComposeView]'));
          } else {
            id = $(self).find('[name=id]');
            $(id).val(response.data.id);
          }
          $(self).find('input[name=record]').val(response.data.id);
          $.fn.EmailsComposeView.checkForDraftAttachments(response.data.id);
        }
      }).fail(function (response) {
        "use strict";
        response = JSON.parse(response);
        mb.setBody(response.errors.title);
        $(self).trigger("saveEmailError", [self, response]);
      }).always(function (response) {
        response = JSON.parse(response);
        $(self).trigger("saveEmailAlways", [self, response]);
      });

      return false;
    };
    /**
     *
     * @event disregardDraft
     * @returns {boolean}
     */
    self.disregardDraft = function () {
      "use strict";

      var mb = messageBox();
      mb.setTitle(SUGAR.language.translate('Emails', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
      mb.setBody(SUGAR.language.translate('Emails', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
      mb.show();

      mb.on('ok', function () {
        "use strict";

        mb.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');

        $(jQueryFormComposeView).find('input[name=action]').val('DeleteDraft');
        // Use FormData v2 to send form data via ajax
        var formData = new FormData(jQueryFormComposeView);

        $(this).find('input').each(function (i, v) {
          if ($(v).attr('type').toLowerCase() !== 'file') {
            var name = $(v).attr('name');
            if (name === 'action') {
              formData.append(name, 'Delete');
            } else if (name === 'send') {
              formData.append(name, 0);
            } else {
              formData.append(name, $(v).val());
            }
          }
        });

        $(this).find('select').each(function (i, v) {
          if (typeof $(v).attr('is_file') === 'undefined') {
            formData.append($(v).attr('name'), $(v).val());
          }
        });

        $(this).find('textarea').each(function (i, v) {
          formData.append($(v).attr('name'), $(v).val());
        });

        $(this).find('button').each(function (i, v) {
          formData.append($(v).attr('name'), $(v).val());
        });

        $.ajax({
          type: "POST",
          data: formData,
          cache: false,
          processData: false,  // tell jQuery not to process the data
          contentType: false,   // tell jQuery not to set contentType
          url: 'index.php?module=Emails'
        }).done(function (response) {
          $(self).trigger("discardDraftDone", [self, response]);
        }).error(function (response) {
          mb.setBody(SUGAR.language.translate('', 'LBL_ERROR_SAVING_DRAFT'));
          $(self).trigger("discardDraftBody", [self, response]);
        }).always(function (response) {
          $(self).trigger("discardDraftAlways", [self, response]);
          mb.remove();
          if ($(self).find('input[type="hidden"][name="return_module"]').val() !== '' 
          && $(self).find('input[type="hidden"][name="return_id"]').val() !== ''
          && $(self).find('input[type="hidden"][name="return_action"]').val() !== '') {
            // mb.on('ok', function () {
              var url = 'index.php?';
              if(($('#' + self.attr('id') + ' input[type="hidden"][name="quote_parent_id"]').val() != '') && typeof($('#' + self.attr('id') + ' input[type="hidden"][name="quote_parent_id"]').val()) !== 'undefined' ){
                  url = url + 'module=AOS_Quotes&action=EditView&record=' + $('#' + self.attr('id') + ' input[type="hidden"][name="quote_parent_id"]').val();
              }else{
                var module = $('#' + self.attr('id') + ' input[type="hidden"][name="return_module"]').val();
                if (module !== undefined) {
                  url = url + 'module=' + module;
                }

              var action = $('#' + self.attr('id') + ' input[type="hidden"][name="return_action"]').val();
              if (action !== undefined) {
                url = url + '&action=' + action;
              }

                var record = $('#' + self.attr('id') + ' input[type="hidden"][name="return_id"]').val();
                if (record !== undefined) {
                  url = url + '&record=' + record;
                }
              }

              location.href = url;
            // });
          } else {
            mb.on('ok', function () {
              // The user is viewing in the modal view
              location.reload();
            });
            $(self).trigger("sentEmail", [self, response]);
          }
          return false;
        });
      });
      mb.on('cancel', function () {
        "use strict";
        // do something
        mb.remove();
        return false;
      });

      return false;
    };

    self.humanReadableFileSize = function (bytes, si) {
      var thresh = si ? 1000 : 1024;
      if (Math.abs(bytes) < thresh) {
        return bytes + ' B';
      }
      var units = si
        ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
        : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
      var u = -1;
      do {
        bytes /= thresh;
        ++u;
      } while (Math.abs(bytes) >= thresh && u < units.length - 1);
      return bytes.toFixed(1) + ' ' + units[u];
    };

    /**
     * Constructor
     */
    self.construct = function () {
      "use strict";

      if (self.length === 0) {
        console.error('EmailsComposeView - Invalid Selector');
        return;
      }

      if (self.attr('id').length === 0) {
        console.warn('EmailsComposeView - expects element to have an id. EmailsComposeView has generated one.');
        self.attr('id', self.generateID());
      }

      if (self.find("[name=record]").val().length > 0) {
        $.fn.EmailsComposeView.checkForDraftAttachments(self.find("[name=record]").val());
      }

      if (typeof opts.tinyMceOptions.setup === "undefined") {
        opts.tinyMceOptions.setup = self.tinyMceSetup;
      }

      if (typeof opts.tinyMceOptions.selector === "undefined") {
        opts.tinyMceOptions.selector = 'form[name="ComposeView"] textarea#description';
      }

      if ($(self).find('#from_addr_name').length !== 0) {
        var selectFrom = $('<select></select>')
          .attr('name', 'from_addr')
          .attr('id', 'from_addr_name');
        var from_addr = $(self).find('#from_addr_name');
        from_addr.replaceWith(selectFrom);

        $.ajax({
          "url": 'index.php?module=Emails&action=getFromFields'
        }).done(function (response) {
          // debugger;
          var json = JSON.parse(response);
          var selected_from = false;
          if (typeof json.data !== "undefined") {
            $(json.data).each(function (i, v) {
              var selectOption = $('<option></option>');
              // BinhNT

               var optionValue = "";
              if(typeof v.attributes.from_name !== "undefined"){
                optionValue = v.attributes.from_name + " <" + v.attributes.from + ">";
                //selectOption.attr('value', v.attributes.from_name + " <" + v.attributes.from + ">");
              } else {
                optionValue = v.attributes.from;
              }
              selectOption.attr('value', optionValue);

              //selectOption.attr('value', v.attributes.from);
              selectOption.attr('inboundId', v.id);
              selectOption.attr('infos', '(<b>Reply-to:</b> ' + v.attributes.reply_to + ', <b>From:</b> ' + v.attributes.from + ')');
              // BinhNT
              //selectOption.html(v.attributes.name);
              selectOption.text(optionValue);
              
            selectOption.appendTo(selectFrom);

            if ($(self).find("[name=return_module]").val() == "AOS_Invoices" || module_sugar_grp1 == 'AOS_Invoices') {
              if($(self).find('[id=emails_email_templates_idb]').val() == '872b8b71-0374-c4ee-50aa-5f0e99e1728a'){
                if(v.attributes.from == 'accounts@pure-electric.com.au'){
                  selectOption.attr('selected', 'true');
                  $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                  $(self).find('[id=bcc_addrs_names]').val('');
                  selected_from = true;
                }
              } 
            }
            //VUT-S-Select email operations@pure-electric.com.au when click Button Delivery Coming and Schedule / Sanden Tip
            if (module_sugar_grp1 == 'AOS_Invoices' || $(self).find("[name=return_module]").val() == "AOS_Invoices") {
              if($(self).find('[id=name]').val().indexOf('Delivery') >= 0 || $(self).find('[id=name]').val().indexOf('Sanden Service Tips') >= 0 || $(self).find('[id=emails_email_templates_idb]').val() == 'c537f9f6-99d8-231d-3e80-5d50acd8af6a') {
                if(v.attributes.from == 'operations@pure-electric.com.au'){
                  selectOption.attr('selected', 'true');
                  $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                  selected_from = true;
                }
              }
            }
            //VUT-E-Select email operations@pure-electric.com.au when click Button Delivery Coming 
            //thienpb code change form email by assigned
            if(module_sugar_grp1 == 'Leads'  || module_sugar_grp1 == 'AOS_Quotes' || module_sugar_grp1 == 'AOS_Invoices'){
              if(!selected_from) {
                if($('#assigned_user_id').val() == '8d159972-b7ea-8cf9-c9d2-56958d05485e' || $('#assigned_user_id').attr('data-id-value') == '8d159972-b7ea-8cf9-c9d2-56958d05485e' || (typeof( window.assigned_user_id) != 'undefined' && window.assigned_user_id == '8d159972-b7ea-8cf9-c9d2-56958d05485e')){
                  if(typeof(window.lead_source) != 'undefined' && window.lead_source == 'Solargain'){
                    if(v.attributes.from.toLowerCase() == 'matthew.wright@solargain.com.au'){
                      selectOption.attr('selected', 'true');
                      $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                    }
                  }
                  else{
                    if(v.attributes.reply_to == 'matthew.wright@pure-electric.com.au' && module_sugar_grp1 != 'AOS_Quotes'){
                      selectOption.attr('selected', 'true');
                      $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                    }
                  }
                // }else if($('#assigned_user_id').val() == '61e04d4b-86ef-00f2-c669-579eb1bb58fa'|| $('#assigned_user_id').attr('data-id-value') == '61e04d4b-86ef-00f2-c669-579eb1bb58fa' ){
                }else{
                  if(typeof(window.lead_source) != 'undefined' && window.lead_source == 'Solargain'){
                    if(v.attributes.from.toLowerCase() == 'paul.szuster@solargain.com.au'){
                      selectOption.attr('selected', 'true');
                      $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                    }
                  }
                  else{
                    if(v.attributes.reply_to == 'paul.szuster@pure-electric.com.au' && module_sugar_grp1 != 'AOS_Quotes'){
                      selectOption.attr('selected', 'true');
                      $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                    }
                  }
  
                }
              }
            }
          //VUT-S-Sanden STC survey send from "accounts@pure-electric.com.au"
            if (module_sugar_grp1 == 'AOS_Invoices') {
              if($(self).find('[id=name]').val().indexOf('Sanden STC Form for') >= 0) {
                if (v.attributes.from.toLowerCase() == 'accounts@pure-electric.com.au') {
                  selectOption.attr('selected', 'true');
                  $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                } else {
                  if(v.attributes.reply_to == 'accounts@pure-electric.com.au'){
                    selectOption.attr('selected', 'true');
                    $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                  }
                }
              }
            }
          //VUT-E-Sanden STC survey send from "accounts@pure-electric.com.au"
            // //thienpb code 
            // if($(self).find('[id=name]').val().indexOf('Pure Electric Seek Install Date') >= 0 ){
            //   if(v.attributes.from == 'operations@pure-electric.com.au'){
            //     selectOption.attr('selected', 'true');
            //     $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
            //   }
            // }

            if($(self).find("[name=return_module]").val() == "PO_purchase_order" || $(self).find("[name=return_module]").val() == "AOS_Invoices" || $(self).find("[name=return_module]").val() == "AOS_Quotes")
            {
              //thienpb code
               // all email from po send by email operations
               if($(self).find("[name=return_module]").val() == "PO_purchase_order"){
                if(v.attributes.from == 'operations@pure-electric.com.au'){
                  selectOption.attr('selected', 'true');
                  $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                }
              }else if($(self).find("[name=return_module]").val() == "AOS_Invoices"){
                if($(self).find('[id=name]').val().indexOf('Seek Install Date') >= 0  ||  $(self).find('[id=name]').val().indexOf('Choose Your Installation Date Availability') >= 0){
                  if(v.attributes.from == 'operations@pure-electric.com.au'){
                    selectOption.attr('selected', 'true');
                    $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                  }
                }else if(($(self).find('[id=name]').val().indexOf('Pure Electric Plumbing  PO#') >= 0 ||$(self).find('[id=name]').val().indexOf('Pure Electric Electrical  PO#') >= 0) && $(self).find('[id=name]').val().indexOf('| Upcoming') >= 0){
                  if(v.attributes.from == 'operations@pure-electric.com.au'){
                    selectOption.attr('selected', 'true');
                    $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                  }
                } else{
                    if(optionValue.toLowerCase().indexOf("accounts") >= 0){
                      selectOption.attr('selected', 'true');
                      $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                      self.updateSignature();
                      self.updateFromInfos();
                  }
                }
              } else if($(self).find("[name=return_module]").val() == "AOS_Quotes"){
                // dung code --- auto change from email address @solargain.com.au when lead source company from solar
                 if($('#assigned_user_id').val() == '8d159972-b7ea-8cf9-c9d2-56958d05485e' || $('#assigned_user_id').attr('data-id-value') == '8d159972-b7ea-8cf9-c9d2-56958d05485e' || (typeof( window.assigned_user_id) != 'undefined' && window.assigned_user_id == '8d159972-b7ea-8cf9-c9d2-56958d05485e')){
                    if(typeof(window.lead_source) != 'undefined' && window.lead_source == 'Solargain'){
                      
                      if(v.attributes.from.toLowerCase() == 'matthew.wright@solargain.com.au'){
                        selectOption.attr('selected', 'true');
                        $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                      }
                    }
                    // else{
                    //   if(v.attributes.reply_to == 'matthew.wright@pure-electric.com.au'){
                    //     selectOption.attr('selected', 'true');
                    //     $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                    //   }
                    // }
                  }else{
                    if(typeof(window.lead_source) != 'undefined' && window.lead_source == 'Solargain'){
                      
                      if(v.attributes.from.toLowerCase() == 'paul.szuster@solargain.com.au'){
                        selectOption.attr('selected', 'true');
                        $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                      }
                    }
                    // else{
                    //   if(v.attributes.reply_to == 'paul.szuster@pure-electric.com.au'){
                    //     selectOption.attr('selected', 'true');
                    //     $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                    //   }
                    // }
                  }
                // end dung code --- auto change from email address @solargain.com.au when lead source company from solar
              }
            }
            //VUT-S- From follow assign current id if(typeof(window.lead_source) != 'undefined' && window.lead_source == 'Solargain')
            // debugger;
            if ($(self).find("[name=return_module]").val() == "AOS_Quotes" || module_sugar_grp1 == 'AOS_Quotes') {
              if(typeof(window.lead_source) == 'undefined' || window.lead_source != 'Solargain') {
                if (v.user_id !='' && typeof(v.user_id) !== "undefined" && !v.attributes.from.toLowerCase().includes('solargain.com.au')) {
                  selectOption.attr('selected', 'true');
                  $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                }
              }
            }
            // custom logic popup email in pe_warehouse_log && VUT button Installation Calendar Plumber/Electrician at Invoices' EditView
            // debugger;
            if ($(self).find("[name=return_module]").val() == "pe_warehouse_log" || module_sugar_grp1 == 'pe_warehouse_log' || $(self).find("[name=return_module]").val() == "AOS_Invoices") {
              if($(self).find('[id=emails_email_templates_idb]').val() == '86230685-a99f-e7ba-b6ef-5fa0ad6a2bc3' || $(self).find('[id=emails_email_templates_idb]').val() == '3722ae7c-d8b7-e03f-559c-5df843678e41' /**Ins Calendar Plumber */ || $(self).find('[id=emails_email_templates_idb]').val() == 'dc0416cd-6867-5508-3d20-5df843ba69dc' /**Ins Calendar Electrician */){
                if(v.attributes.from == 'operations@pure-electric.com.au'){
                  selectOption.attr('selected', 'true');
                  $(self).find('[name=inbound_email_id]').val($(self).find('[name=from_addr] option:selected').attr('inboundid'));
                  $(self).find('[id=bcc_addrs_names]').val('');
                }
              } 
            }
            //VUT-E- From follow assign current id 

              // include signature for account
              $('<textarea></textarea>')
                .val(v.emailSignatures.html)
                .addClass('email-signature')
                .addClass('html')
                .addClass('hidden')
                .attr('data-inbound-email-id', v.id)
                .appendTo(self);

              $('<textarea></textarea>')
                .val(v.emailSignatures.plain)
                .addClass('email-signature')
                .addClass('plain')
                .addClass('hidden')
                .attr('data-inbound-email-id', v.id)
                .appendTo(self);

              if (typeof v.prepend !== "undefined" && v.prepend === true) {
                self.prependSignature = true;
              }
              self.updateSignature();
            });

            var selectedInboundEmail = $(self).find('[name=inbound_email_id]').val();
            var selectInboundEmailOption = $(selectFrom).find('[inboundid="' + selectedInboundEmail + '"]');
            if (selectInboundEmailOption.val()) {
              $(selectFrom).val(selectInboundEmailOption.val());
            }

            $(selectFrom).change(function (e) {
              $(self).find('[name=inbound_email_id]').val($(this).find('option:selected').attr('inboundId'));
              self.updateSignature();
              self.updateFromInfos();
            });

            $(self).trigger('emailComposeViewGetFromFields');
            
            self.updateFromInfos();

          }

          if ($(self).find('#is_only_plain_text').length === 1) {
            $(self).find('#is_only_plain_text').click(function () {
              var tinemceToolbar = $(tinymce.EditorManager.activeEditor.getContainer()).find('.mce-toolbar');
              if ($('#is_only_plain_text').prop('checked')) {
                tinemceToolbar.hide();
              } else {
                tinemceToolbar.show();
              }
            });
          }

          if (typeof json.errors !== "undefined") {
            $.fn.EmailsComposeView.showAjaxErrorMessage(json);
          }
        }).error(function (response) {
          console.error(response);
        });
      }

      /**
       * Used to preview email. It also doubles as a means to get the plain text version
       * using $('#'+self.attr('id') + ' .html_preview').text();s
       */
      $('<div></div>').addClass('hidden').addClass('html_preview').appendTo($(self));

      $('<input>')
        .attr('name', 'description_html')
        .attr('type', 'hidden')
        .attr('id', 'description_html')
        .appendTo($(self));

      if (typeof tinymce === "undefined") {
        console.error('EmailsComposeView - Missing Dependency: Cannot find tinyMCE.');

        // copy plain to html
        $(self).find('#description_html').closest('.edit-view-row-item').addClass('hidden');
        $(self).find('textarea#description_html').on("keyup", function () {
          $(self).find('input#description_html').val($(self).find('textarea#description').val().replace('\n', '<br>'));
        });
      } else {
        $(self).find('[data-label="description_html"]').closest('.edit-view-row-item').addClass('hidden');

        var intervalCheckTinymce = window.setInterval(function () {
          var isFromPopulated = $('#from_addr_name').prop("tagName").toLowerCase() === 'select';
          if (tinymce.editors.length > 0 && isFromPopulated === true) {
            self.updateSignature();
            clearInterval(intervalCheckTinymce);
          }
        }, 300);

        tinymce.init(opts.tinyMceOptions);

      }

      // Handle sent email submission
      self.submit(self.onSendEmail);

      // Handle toolbar (default) button events
      //thienpb add button set schedule
      $(self).find('.btnSuccess').click(self.saveDraftSchedule);
      $(self).find('.btn-send-email').click(self.sendEmail);
      $(self).find('.btn-attach-file').click(self.attachFile);
      $(self).find('.btn-attach-notes').click(self.attachNote);
      $(self).find('.btn-attach-document').click(self.attachDocument);
      $(self).find('.btn-save-draft').click(self.saveDraft);
      $(self).find('.btn-disregard-draft').click(self.disregardDraft);

      var file = $('<input />')
        .attr('name', file);

      $(self).on('remove', self.destruct);

      // detect empty rows
      $(self).find('.edit-view-row-item').each(function () {
        if (trim($(this).html()).length === 0) {
          $(this).addClass('empty');
        }
      });

      // qtipBar
      var hidden = $('<input type="hidden" id="qtip_bar_module">' +
        '<input type="hidden" id="qtip_bar_id">' +
        '<input type="hidden" id="qtip_bar_name">' +
        '<input type="hidden" id="qtip_bar_email_address">').appendTo(self);
      $(self).find('#to_addrs_names').focus(self.showQTipBar);
      $(self).find('#cc_addrs_names').focus(self.showQTipBar);
      $(self).find('#bcc_addrs_names').focus(self.showQTipBar);
      $(self).on('sendEmail', function () {
        $('.emails-qtip').remove();
      });


      $(self).trigger("constructEmailsComposeView", [self]);
    };

    /**
     * @destructor
     */
    self.destruct = function () {
      // TODO: Find a better way only display one tiny mce
      // Remove the hanging tinyMCE div
      $('.mce-panel').remove();
      var length = tinyMCE.editors.length;
      for (var i = length; i > 0; i--) {
        tinyMCE.editors[i - 1].remove();
      }
      $('.emails-qtip').remove();
      return true;
    };

    self.construct();

    return $(self);
  };

  $.fn.EmailsComposeView.checkForDraftAttachments = function (id) {
    // Check if this is a draft email with attachments
    $.ajax({
      "url": 'index.php?module=Emails&action=GetDraftAttachmentData&id=' + id
    }).done(function (jsonResponse) {
      var response = JSON.parse(jsonResponse);
      if (typeof response.data !== "undefined") {
        $('.file-attachments').empty();
        $.fn.EmailsComposeView.loadAttachmentDataFromAjaxResponse(response);
      }
      if (typeof response.errors !== "undefined") {
        $.fn.EmailsComposeView.showAjaxErrorMessage(response);
      }
    }).error(function (response) {
      console.error(response);
    });
  };

  $.fn.EmailsComposeView.showAjaxErrorMessage = function (response) {
    var message = '';
    $.each(response.errors, function (i, v) {
      message = message + v.title;
    });
    var mb = messageBox();
    mb.setBody(message);
    mb.show();

    mb.on('ok', function () {
      "use strict";
      mb.remove();
    });

    mb.on('cancel', function () {
      "use strict";
      mb.remove();
    });
  };

  $.fn.EmailsComposeView.loadAttachmentDataFromAjaxResponse = function (response) {
    var isDraft = (typeof response.data.draft !== undefined && response.data.draft ? true : false);
    $('.file-attachments').empty();
    var inputName = 'template_attachment[]';
    var removeName = 'temp_remove_attachment[]';
    if (isDraft) {
      var inputName = 'dummy_attachment[]';
      var removeName = 'remove_attachment[]';
    }
    if (typeof response.data.attachments !== 'undefined' && response.data.attachments.length > 0) {
      var removeDraftAttachmentInput = $('<input>')
        .attr('type', 'hidden')
        .attr('name', 'removeAttachment')
        .appendTo($('.file-attachments'));
      if (!isDraft) {
        $('<input>')
          .attr('type', 'hidden')
          .attr('name', 'ignoreParentAttachments')
          .attr('value', '1')
          .appendTo($('.file-attachments'));
      }
      for (i = 0; i < response.data.attachments.length; i++) {
        var id = response.data.attachments[i]['id'];
        var fileGroupContainer = $('<div></div>')
          .addClass('attachment-group-container')
          .appendTo($('.file-attachments'));

        var fileInput = $('<select></select>')
          .attr('style', 'display:none')
          .attr('id', id)
          .attr('is_file', true)
          .attr('name', inputName)
          .attr('multiple', 'multiple');

        var fileOptions = $('<option></option>')
          .attr('selected', 'selected')
          .attr('value', id)
          .appendTo(fileInput);

        fileInput.appendTo(fileGroupContainer);
        var fileLabel = $('<label></label>')
          .attr('for', 'file_' + id)
          .html('<span class="glyphicon glyphicon-paperclip"></span>')
          .appendTo(fileGroupContainer);

        var fileContainer = $('<div class="attachment-file-container"></div>');
        fileContainer.appendTo(fileLabel);
        fileContainer.append('<span class="attachment-name"> ' + response.data.attachments[i]['name'] + ' </span>');

        var removeAttachment = $('<a class="attachment-remove"><span class="glyphicon glyphicon-remove"></span></a>');
        removeAttachment.click(function () {
          //dung code - confirm before delete attrck remove
           var ok_confirm = confirm('Do you want to delete ?');
           if(ok_confirm == true) {
              $(this).parent().hide();
              // BinhNT Fix it
              var l_id = $(this).siblings( "select" ).attr("id");
              $(this).parent().find('[name="' + inputName + '"]').attr('name', removeName);
              if (isDraft) {
                removeDraftAttachmentInput.val(removeDraftAttachmentInput.val() + '::' + l_id);
              }
           }else {
              return;
           }


        });
        fileGroupContainer.append(removeAttachment);
      }
    }
  };

  $.fn.EmailsComposeView.onTemplateSelect = function (args) {

    var confirmed = function (args) {
      // var args = JSON.parse(args);
      var form = $('[name="' + args.form_name + '"]');
      $.post('index.php?entryPoint=emailTemplateData', {
        emailTemplateId: args.name_to_value_array.emails_email_templates_idb,
        //VUT-S-Email Template-Change variable at Accounts
        parent_type: $('#ComposeView').find('#parent_type').val(),
        parent_id:  $('#ComposeView').find('#parent_id').val(),
        //VUT-E-Email Template-Change variable at Accounts
      }, function (jsonResponse) {
        var response = JSON.parse(jsonResponse);
        $.fn.EmailsComposeView.loadAttachmentDataFromAjaxResponse(response);
        {
        // BinhNT
          var inboundId = $('input[name=inbound_email_id]').val();
          if (inboundId === undefined) {
              console.warn('Unable to retrieve selected inbound id in the "From" field.');
              return false;
          }
      
          var signatureElement = $('<div></div>')
              .addClass('email-signature');
          var signatures = $(form).find('.email-signature');
          var htmlSignature = null;
          var plainTextSignature = null;
      
          // Find signature
          $.each(signatures, function(index, value) {
              if ($(value).attr('data-inbound-email-id') === inboundId) {
      
                  if ($(value).hasClass('html')) {
                      htmlSignature = $(value).val();
                  } else if ($(value).hasClass('plain')) {
                      plainTextSignature = $(value).val();
                  }
              }
          });
      
          if (
              htmlSignature === null &&
              plainTextSignature === null
          ) {
              console.warn('Unable to retrieve signature from document.');
              return false;
          }
      
          if (htmlSignature === null) {
              // use plain signature instead
              $(plainTextSignature).appendTo(signatureElement);
          } else if (plainTextSignature === null) {
              // use html signature
              $(htmlSignature).appendTo(signatureElement);
          } else {
              $(htmlSignature).appendTo(signatureElement);
          }
        }
        $(form).find('[name="name"]').val(response.data.subject);
        tinymce.activeEditor.setContent(response.data.body_from_html, {format: 'html'});
        // BinhNT

        {
      
          if (tinymce.editors.length < 1) {
              console.warn('unable to find tinymce editor');
              return false;
          }
      
          var body = tinymce.activeEditor.getContent();
          if (body === '') {
              tinymce.activeEditor.setContent('' + signatureElement[0].outerHTML, {
                  format: 'html'
              });
          } else if ($(body).hasClass('email-signature')) {
              var newBody = $('<div></div>');
              $(body).appendTo(newBody);
              $(newBody).find('.email-signature').replaceWith(signatureElement[0].outerHTML);
              tinymce.activeEditor.setContent(newBody.html(), {
                  format: 'html'
              });
          } else {
              // reply to / forward
              if (self.prependSignature === true) {
                  tinymce.activeEditor.setContent('' + signatureElement[0].outerHTML + body, {
                      format: 'html'
                  });
              } else {
                  tinymce.activeEditor.setContent(body + signatureElement[0].outerHTML, {
                      format: 'html'
                  });
              }
          }
          if($('#emails_email_templates_idb').val() != ''){
            $('.link_open_template').remove();
            $('#emails_email_templates_idb').parent().append('<a class="link_open_template" target="_blank" href="/index.php?module=EmailTemplates&return_module=EmailTemplates&action=EditView&record='+ $('#emails_email_templates_idb').val()+'">Open Link Template</a>');
          }
        }
      });
      set_return(args);
    };

    var mb = messageBox();
    mb.setTitle(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_TITLE'));
    mb.setBody(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_BODY'));
    mb.show();

    mb.on('ok', function () {
      "use strict";
      confirmed(args);
      mb.remove();
    });

    mb.on('cancel', function () {
      "use strict";
      mb.remove();
    });
  };

  $.fn.EmailsComposeView.onSMSTemplateSelect = function (args) {
    if(typeof(action_sugar_grp1) != 'undefined' && (action_sugar_grp1 == 'EditView' || action_sugar_grp1 == 'DetailView')){
      var record_id = record;
      var module_set = module_sugar_grp1;
    }
    else if(typeof(action_sugar_grp1) != 'undefined' && action_sugar_grp1 == 'ComposeViewWithPdfTemplate'){
      var record_id = $("#parent_id").val();
      var module_set = $("#parent_type").val();
    }
    else if(typeof(action_sugar_grp1) != 'undefined' && action_sugar_grp1 == 'index' && typeof(moduleName) != 'undefined' && moduleName == 'Home'){
      //listview in HOME
      var module_set = $(document).find('input[class="email-compose-view-to-list"]').attr('data-record-module');
      var record_id = $(document).find('input[class="email-compose-view-to-list"]').attr('data-record-id');
    }
    else if(typeof(action_sugar_grp1) != 'undefined' && action_sugar_grp1 == 'index' && typeof(moduleName) == 'undefined'){
      //listview in module Leads, Contact, Account
      var record_id = $("#parent_id").val();
      var module_set = $("#parent_type").val();
    }
    else{
      var record_id = $('input[name="record"]').val();
      var module_set = $('#DetailView').find('input[name="module"]').val();
    }

    //custom name customer 
    var fullname_customer = $("#to_addrs_names").val();
    var first_acc_name = (fullname_customer.split('<')[0]).split(" ")[0];
    var confirmed = function (args) {
      var form = $('[name="' + args.form_name + '"]');
      $.post('index.php?entryPoint=SMSTemplateData', {
        smsTemplateId: args.name_to_value_array.emails_pe_smstemplate_idb,
        record_id: record_id,
        module_set: module_set,
        first_acc_name: first_acc_name,
      }, function (jsonResponse) {
        var response = JSON.parse(jsonResponse);
        $.fn.EmailsComposeView.loadAttachmentDataFromAjaxResponse(response);
        $(form).find('[name="sms_message"]').val(response.data.body_from_html);      
        if($('#emails_pe_smstemplate_idb').val() != ''){
          $('.link_open_sms_template').remove();
          $('#emails_pe_smstemplate_idb').parent().append('<a class="link_open_sms_template" target="_blank" href="/index.php?module=pe_smstemplate&return_module=pe_smstemplate&action=EditView&record='+ $('#emails_pe_smstemplate_idb').val()+'">Link SMS Template</a>');
        }
      });
      set_return(args);
    };
    var mb = messageBox();
    mb.setTitle(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_SMS_TEMPLATE_TITLE'));
    mb.setBody(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_SMS_TEMPLATE_BODY'));
    mb.show();

    mb.on('ok', function () {
      "use strict";
      confirmed(args);
      mb.remove();
    });

    mb.on('cancel', function () {
      "use strict";
      mb.remove();
    });
  };


  $.fn.EmailsComposeView.RefeshTemplate = function (args) {
      var url =  window.request_actual + '&RefeshTemplate=true';
      var confirmed = function (args) {
        var form = $('[name="' + args.form_name + '"]');
        $.post(url, {
          emailTemplateId: args.name_to_value_array.emails_email_templates_idb
        }, function (jsonResponse) {
          var patt_data_json = /<div hidden id="data_json_email_refesh">(.*?)<\/div>/;
          var json_data_email_refesh = patt_data_json.exec(jsonResponse);
       
          var response = $.parseJSON(json_data_email_refesh[1]);
          console.log(response);
          $.fn.EmailsComposeView.loadAttachmentDataFromAjaxResponse(response);
          
          {
          // BinhNT
            var inboundId = $('input[name=inbound_email_id]').val();
            if (inboundId === undefined) {
                console.warn('Unable to retrieve selected inbound id in the "From" field.');
                return false;
            }
        
            var signatureElement = $('<div></div>')
                .addClass('email-signature');
            var signatures = $(form).find('.email-signature');
            var htmlSignature = null;
            var plainTextSignature = null;
        
            // Find signature
            $.each(signatures, function(index, value) {
                if ($(value).attr('data-inbound-email-id') === inboundId) {
        
                    if ($(value).hasClass('html')) {
                        htmlSignature = $(value).val();
                    } else if ($(value).hasClass('plain')) {
                        plainTextSignature = $(value).val();
                    }
                }
            });
        
            if (
                htmlSignature === null &&
                plainTextSignature === null
            ) {
                console.warn('Unable to retrieve signature from document.');
                return false;
            }
        
            if (htmlSignature === null) {
                // use plain signature instead
                $(plainTextSignature).appendTo(signatureElement);
            } else if (plainTextSignature === null) {
                // use html signature
                $(htmlSignature).appendTo(signatureElement);
            } else {
                $(htmlSignature).appendTo(signatureElement);
            }
          }
          $(form).find('[name="name"]').val(response.data.subject);
          tinymce.activeEditor.setContent(response.data.body_from_html, {format: 'html'});
          // BinhNT

          {
        
            if (tinymce.editors.length < 1) {
                console.warn('unable to find tinymce editor');
                return false;
            }
        
            var body = tinymce.activeEditor.getContent();
            if (body === '') {
                tinymce.activeEditor.setContent('' + signatureElement[0].outerHTML, {
                    format: 'html'
                });
            } else if ($(body).hasClass('email-signature')) {
                var newBody = $('<div></div>');
                $(body).appendTo(newBody);
                $(newBody).find('.email-signature').replaceWith(signatureElement[0].outerHTML);
                tinymce.activeEditor.setContent(newBody.html(), {
                    format: 'html'
                });
            } else {
                // reply to / forward
                if (self.prependSignature === true) {
                    tinymce.activeEditor.setContent('' + signatureElement[0].outerHTML + body, {
                        format: 'html'
                    });
                } else {
                    tinymce.activeEditor.setContent(body + signatureElement[0].outerHTML, {
                        format: 'html'
                    });
                }
            }
          }
        });
        set_return(args);
      };

    var mb = messageBox();
    mb.setTitle(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_TITLE'));
    mb.setBody(SUGAR.language.translate('Emails', 'LBL_CONFIRM_APPLY_EMAIL_TEMPLATE_BODY'));
    mb.show();

    mb.on('ok', function () {
      "use strict";
      confirmed(args);
      mb.remove();
    });

    mb.on('cancel', function () {
      "use strict";
      mb.remove();
    });
  };

  $.fn.EmailsComposeView.onParentSelect = function (args) {
    set_return(args);
    if (isValidEmail(args.name_to_value_array.email1)) {
      var emailAddress = args.name_to_value_array.email1;
      var self = $('[name="' + args.form_name + '"]');
      var toField = $(self).find('[name=to_addrs_names]');
      if (toField.val().indexOf(emailAddress) === -1) {
        var toFieldVal = toField.val();
        if (toFieldVal === '') {
          toField.val(emailAddress);
        } else {
          toField.val(toFieldVal + ', ' + emailAddress);
        }

      }
    }
  };

  $.fn.EmailsComposeView.defaults = {
    "tinyMceOptions": {
      skin_url: "themes/default/css",
      skin: "",
      plugins: "fullscreen autoresize link preview autolink link ",
      menubar: false,
      toolbar: ['fontselect | fontsizeselect | bold italic underline | styleselect | link'],
      browser_spellcheck: true,
      formats: {
        bold: {inline: 'b'},
        italic: {inline: 'i'},
        underline: {inline: 'u'}
      },
      convert_urls:true,
      relative_urls:false,
      remove_script_host:false,
    }
  };
}(jQuery));

// BinhNT 
$(document).ready(function(){
  var inboundId = $('input[name=inbound_email_id]').val();
  var return_module = $('input[name="return_module"]').val();
  var return_action = $('input[name="return_action"]').val();
  if(return_module == "AOS_Quotes"){
    $("#send_sms").click();
    $("#number_receive_sms").val("matthew_paul_client");
  }
  // if(typeof current_user_id !== "undefined")
  // if (current_user_id == 'd028d21f-504c-c8ff-3ba3-57ab05ae7a4d' && typeof $('#bcc_addrs_names') !== "undefined") {
  //   $('#bcc_addrs_names').val('Lee Andrewartha <lee.andrewartha@pure-electric.com.au>');
  // }
});

function get_lead_source_company(module_get,record){
  if(record != '' ){
      $.ajax({
          url:"?entryPoint=get_lead_source_company&record="+record+"&module=" + module_get,
          async:false,
          success:function(data){
            var json_data = $.parseJSON(data);
            window.lead_source = json_data.lead_source_co_c;
            window.assigned_user_id = json_data.assigned_user_id;
          }
      });
  }
}

  //Dung code 
  $(document).ready(function(){
    if($('#emails_email_templates_idb').val() != ''){
      $('.link_open_template').remove();
      $('#emails_email_templates_idb').parent().append('<a class="link_open_template" target="_blank" href="/index.php?module=EmailTemplates&return_module=EmailTemplates&action=EditView&record='+ $('#emails_email_templates_idb').val()+'">Open Link Template</a>');
    }
    //email template
    if($('#emails_email_templates_idb').val() == '') {
      var urlParams = new URLSearchParams(window.location.search);
      var Params_email_template_id = urlParams.get('email_template_id');
      $('#emails_email_templates_idb').val(Params_email_template_id);
      window.refesh_template_parent_id =  urlParams.get('return_id');
      window.refesh_template_installation_id =  urlParams.get('installation_id');
      window.refesh_template_role =  urlParams.get('role');
      if($('#emails_email_templates_idb').val() != ''){
        $('.link_open_template').remove();
        $('#emails_email_templates_idb').parent().append('<a class="link_open_template" target="_blank" href="/index.php?module=EmailTemplates&return_module=EmailTemplates&action=EditView&record='+ $('#emails_email_templates_idb').val()+'">Open Link Template</a>');
      } 
    }
    
    // sms template
    if($('#emails_pe_smstemplate_idb').val() == '') {
      var urlParams = new URLSearchParams(window.location.search);
      var Params_sms_template_id = urlParams.get('sms_template_id');
      $('#emails_pe_smstemplate_idb').val(Params_sms_template_id);
      if($('#emails_pe_smstemplate_idb').val() != ''){
        $('.link_open_sms_template').remove();
        $('#emails_pe_smstemplate_idb').parent().append('<a class="link_open_sms_template" target="_blank" href="/index.php?module=pe_smstemplate&return_module=pe_smstemplate&action=EditView&record='+ $('#emails_pe_smstemplate_idb').val()+'">Link SMS Template</a>');
      } 
    }
    SUGAR.ajaxUI.showLoadingPanel();
    $.ajax({
      url: "/index.php?entryPoint=customGetEmailTemplates&action=get_name_template&emails_email_templates_idb="+ $('#emails_email_templates_idb').val() +"&emails_pe_smstemplate_idb="+$('#emails_pe_smstemplate_idb').val(),
      type : 'GET',
      success: function(result){
        if(result != 'null'){
            var jsonObject = $.parseJSON(result);
            $("#emails_email_templates_name").val(jsonObject.emails_email_templates_name);
            $("#emails_pe_smstemplate_name").val(jsonObject.emails_pe_smstemplate_name);
            
        }
        SUGAR.ajaxUI.hideLoadingPanel();
      },
      error: function(result){
        console.log('error');
      }
    });
    // custom get sms_signture
    var html_select_sms_signture = "<select name='select_sms_signture' id='select_sms_signture' style='width:50%;'><option value=''></option></select>";
    $("#sms_signture").parent().append(html_select_sms_signture);
    $("#sms_signture").hide();
    $("#sms_content").closest('.edit-view-row-item').hide();
    $.ajax({
        url: 'index.php?entryPoint=CRUD_SMS_Signture' ,
        type: 'POST',
        data: 
        {
            action: 'read',
            custom_action : 'get_sms_signture'
        },
        async: true,
        success: function(result) {                   
            render_select_sms_signture(result);
            //Select PE Account sms'signture  for Send Geo Reminder in Invoice
            setTimeout(function(){
                if ($('#emails_pe_smstemplate_idb').val() == '5fcde64f-63ac-dc94-21fb-5e5ef5cf4c70') {
                    $('#select_sms_signture').val('_1588918966').change();
                }    
              },100);
            }
      });
      function render_select_sms_signture(result){
          var data_result = $.parseJSON(result);
          var id_current_sms_signture = '';
          window.data_sms_signture = data_result;
          $('#select_sms_signture').empty();
          $('#select_sms_signture').append($('<option>', {
              value: '',
              text: ''
          }));
          $.each(data_result,function(k,v){
              $('#select_sms_signture').append($('<option>', {
                  value: k,
                  text: v.title
              }));
              if(trim($("#sms_signture").val()) == trim(v.content)){
                id_current_sms_signture = k;
              }
          });
          if(id_current_sms_signture != ''){
            $('#select_sms_signture').val(id_current_sms_signture);
          }
      };
    $('body').on('change','#select_sms_signture',function(){
        var sms_message = $("#sms_message").val();
        var before_sms_signture = $("#sms_signture").val();
        var before_sms_content = sms_message.replace(before_sms_signture,'');
        $("#sms_content").val(before_sms_content.replace(/\n{2,}/g, "\r\n"));
        var sms_signture =  window.data_sms_signture[$(this).val()].content;
        $("#sms_signture").val(sms_signture);
        $("#sms_message").val($("#sms_content").val()+'\n'+$("#sms_signture").val());
    });

    if($('#emails_pe_smstemplate_idb').val() != ''){
      $('.link_open_sms_template').remove();
      $('#emails_pe_smstemplate_idb').parent().append('<a class="link_open_sms_template" target="_blank" href="/index.php?module=pe_smstemplate&return_module=pe_smstemplate&action=EditView&record='+ $('#emails_pe_smstemplate_idb').val()+'">Link SMS Template</a>');
    }
   
    $('#emails_pe_smstemplate_idb').parent().append('<button id="btn_create_template" class="button lastChild" type="button">Create Template</button>');
    $('body').on('click','#btn_create_template',function(){
      var win = window.open('/index.php?module=pe_smstemplate&action=EditView&record=', 'pe_smstemplate_popup_window1' , 'width=800,height=800,resizable=1,scrollbars=1');
      if (window.focus) {
        win.focus();
      }
    })

      //dung code -- button update phone number 
      if($("#number_client").val().trim() != ''){
        $("#number_client").prop('disabled', true);
      }else{
        $("#number_client").parent().append('<button id="btn_update_number_relative" class="button" type="button">Update Number</button>');
      }
      $('body').on('click','#btn_update_number_relative',function(e){
        var ok = confirm('Do you want update mobile phone number in Lead, Account and Contact?');
        if(ok ==  true){
            var record_id = $("input[name='parent_id']").val();
            var module_parent = $("input[name='parent_type']").val();
            if(typeof(moduel_parent) == 'undefined' ) {
                module_parent = $("#parent_id").val();
            }
            var phone_number = $("input[name='number_client']").val();
            $.ajax({
                url: "?entryPoint=custom_update_relative_phone_number",
                type:'post',
                data: {
                  'record_id' : record_id,
                  'module':module_parent,
                  'phone_number' : phone_number
                },
                success: function (data) {
                    console.log(data);
                }
            });
            e.stopImmediatePropagation();
        }else{
            e.stopImmediatePropagation();
        }
        
      })
      $('body').on('change','#number_client',function(){
        var phone_number = $("input[name='number_client']").val();
        phone_number = phone_number.replace(/\D/g, '').replace(/^04/g, '614');
        phone_number = '+' + phone_number;
        $("input[name='number_client']").val(phone_number);
      });

    $('#emails_email_templates_idb').parent().append('<br><button id="refesh_tempalte" type="button" class="button lastChild" >Refesh Template</button>');
    
    $('#refesh_tempalte').click(function(){
      if( typeof(window.refesh_template_installation_id) != 'undefined' && window.refesh_template_installation_id !=  '') {
           //case custom code for button refresh template new tab
          $.ajax({
              type: "POST",
              url: "?entryPoint=generateInstallationCalendarEmail&invoiceID="+window.refesh_template_parent_id+"&installation_id=" + window.refesh_template_installation_id,
              success : function(data_){
                  if(data_ != ''){
                      var urls = JSON.parse(data_);
                      if(urls.client_url != '' && window.refesh_template_role == 'client'){
                          window.open(urls.client_url,"_blank");
                          window.top.close();
                      }
                      if(urls.electrician_url != ''&& window.refesh_template_role == 'electrician' ){
                          window.open(urls.electrician_url,"_blank");
                          window.top.close();
                      }
                      if(urls.plumber_url != ''&& window.refesh_template_role == 'plumber'){
                          window.open(urls.plumber_url,"_blank");
                          window.top.close();
                      }
                  }
              }
          });
      }else{
      //case default
        var args =  {
          form_name: "ComposeView",
          name_to_value_array:{
            emails_email_templates_idb:"",
            emails_email_templates_name:"",
          }
        };
        $.fn.EmailsComposeView.RefeshTemplate(args); 
      }
     
    });
    var array_result;
    $('#emails_email_templates_name').autocomplete({
      open: function(){
        $('.ui-autocomplete').css('z-index', 99999999999999);
      },
      source: function(request, response){
        if(request['term'] !== '') {
          $.ajax({
            url: "/index.php?entryPoint=customGetEmailTemplates&term="+ request['term'] ,
            type : 'GET',
            success: function(result){
              if(result !== 'null'){
                var suggest =[];
                var jsonObject = $.parseJSON(result);
                for (i = 0; i < jsonObject.length; i++) {
                    suggest.push(jsonObject[i].name);
                }
                response(suggest);
                array_result = jsonObject;
              }
            },
            error: function(result){
              console.log('error');
            }
          })
        }
      },
      select: function(event, ui) {
        var args =  {
          form_name: "ComposeView",
          name_to_value_array:{
            emails_email_templates_idb:"",
            emails_email_templates_name:"",
          }
        };
        args.name_to_value_array.emails_email_templates_name = ui.item.value;
        var name_email_templates_select = ui.item.value;
        for (let index = 0; index < array_result.length; index++) {
          if(name_email_templates_select == array_result[index].name){
            $('#emails_email_templates_idb').val(array_result[index].id); 
            args.name_to_value_array.emails_email_templates_idb = array_result[index].id;        
          }
        };
        $.fn.EmailsComposeView.onTemplateSelect(args); 
        if($('#emails_email_templates_idb').val() != ''){
          $('.link_open_template').remove();
          $('#emails_email_templates_idb').parent().append('<a class="link_open_template" target="_blank" href="/index.php?module=EmailTemplates&return_module=EmailTemplates&action=EditView&record='+ $('#emails_email_templates_idb').val()+'">Open Link Template</a>');
        }
      }
    })

    $('#emails_pe_smstemplate_name').autocomplete({
      open: function(){
        $('.ui-autocomplete').css('z-index', 99999999999999);
      },
      source: function(request, response){
        if(request['term'] !== '') {
          $.ajax({
            url: "/index.php?entryPoint=customGetSMSTemplate&term="+ request['term'] ,
            type : 'GET',
            success: function(result){
              if(result !== 'null'){
                var suggest =[];
                var jsonObject = $.parseJSON(result);
                for (i = 0; i < jsonObject.length; i++) {
                    suggest.push(jsonObject[i].name);
                }
                response(suggest);
                array_result = jsonObject;
              }
            },
            error: function(result){
              console.log('error');
            }
          })
        }
      },
      select: function(event, ui) {
        var args =  {
          form_name: "ComposeView",
          name_to_value_array:{
            pe_smstemplate_id_c:"",
            sms_template_c:"",
          }
        };
        args.name_to_value_array.sms_template_c = ui.item.value;
        var name_sms_template_select = ui.item.value;
        for (let index = 0; index < array_result.length; index++) {
          if(name_sms_template_select == array_result[index].name){
            $('#emails_pe_smstemplate_idb').val(array_result[index].id); 
            args.name_to_value_array.pe_smstemplate_id_c = array_result[index].id; 
            //custom name customer 
            var fullname_customer = $("#to_addrs_names").val();
            var first_acc_name = (fullname_customer.split('<')[0]).split(" ")[0];
            var content_sms = array_result[index].body_c.replace("$first_name",first_acc_name);
            //VUT-S-quote number in sms template when used 'term'
              if (module_sugar_grp1 == 'AOS_Quotes') {
                var quote_number = $('#number').text().trim();
                  content_sms = content_sms.replace("$quote_number",quote_number);
              } else if (module_sugar_grp1 == 'AOS_Invoices') {
                var quote_number = $('#quote_number').text().trim();
                content_sms = content_sms.replace("$quote_number",quote_number);
                var product_type = $("div[field='quote_type_c']").text().trim();
                content_sms = content_sms.replace("$product_type",product_type);
              }
            //VUT-E-quote number in sms template when used 'term'
            $("#sms_signture").val(array_result[index].sms_signture);
            $("#sms_content").val(content_sms);
            $("#sms_message").val($("#sms_content").val()+'\n'+$("#sms_signture").val());
            
          }
        };
        if($('#emails_pe_smstemplate_idb').val() != ''){
          $('.link_open_sms_template').remove();
          $('#emails_pe_smstemplate_idb').parent().append('<a class="link_open_sms_template" target="_blank" href="/index.php?module=pe_smstemplate&return_module=pe_smstemplate&action=EditView&record='+ $('#emails_pe_smstemplate_idb').val()+'">Link SMS Template</a>');
        }
       
      }
    });
    var array_result_data ;
    $('#number_client').autocomplete({
      open: function(){
        $('.ui-autocomplete').css('z-index', 99999999999999);
      },
      source: function(request, response){
        if(request["term"].length > 3) {
          $.ajax({
            url: "/index.php?entryPoint=customGetPhoneNumber&term="+ request['term'] ,
            type : 'GET',
            success: function(result){
              if(result !== 'null'){
                var suggest =[];
                var jsonObject = $.parseJSON(result);
                for (i = 0; i < jsonObject.length; i++) {
                    suggest.push(jsonObject[i].name);
                }
               
                array_result_data = jsonObject;
                response(suggest);
          
              }
            },
            error: function(result){
              console.log('error');
            }
          })
        }
      },
      select: function(event, ui) {
        var value_data =  ui.item.value.split("|");
        var value_data_name = value_data[0];
        var value_data_phone = value_data[1];
        console.log(value_data_phone);
        $("#number_client").val(value_data_phone);
        return false;
      }
    });
    

  if(action_sugar_grp1 == 'DetailView'){ // detail view
      record = $('body').find('input[name="record"]').val();     
      get_lead_source_company(module_sugar_grp1,record);    
  }else if(action_sugar_grp1 == 'EditView'){ // edit view
      record = $('body').find('input[name="record"]').val();
      get_lead_source_company(module_sugar_grp1,record);
  }else if(action_sugar_grp1 == 'ComposeViewWithPdfTemplate'){ // ComposeViewWithPdfTemplate
      var return_module = $("input[name='return_module']").val();
      var return_record = $("input[name='return_id']").val();
      get_lead_source_company(return_module,return_record);
  }

  })

  $(document).ready(function(){
    var click = 1;
    var origin_string_subject;
    var origin_content ;
    $('#btn_parent_name').parent().append('<button id="button_getdata" type="button" class="button lastChild" ><span class="glyphicon glyphicon-ok"></span></button>');
    $('#button_getdata').click(function(){
      if(click == 1 ){
        var parent_id = $('#parent_id').val();
        var parent_type = $('#parent_type').val();
        var emails_email_templates_idb = $('#emails_email_templates_idb').val();
        origin_string_subject = $('#name').val();
        origin_content = tinyMCE.activeEditor.getContent();
        click = 0;
        $.ajax({
          url: "/index.php?entryPoint=customGetDataForEmailTemplate&relate_to=" +parent_type +"&parent_id="+parent_id+"&emails_email_templates_idb="+emails_email_templates_idb,
          type : 'GET',
          success: function(result){
            if(result == 'It don\'t has data !!!' || result == "Template is wrong !!!"){
              alert (result);
            }else{
              var result_array = $.parseJSON(result);
              var string_subject = $('#name').val();
              string_subject = string_subject.replace('$lead_first_name',result_array['lead_first_name']);
              string_subject = string_subject.replace('$lead_primary_address_city',result_array['lead_primary_address_city']);
              $('#name').val(string_subject);
              var content = tinyMCE.activeEditor.getContent();
              content = content.replace('$lead_first_name',result_array['lead_first_name']);
              tinyMCE.activeEditor.setContent(content);
            }
          },
        });
      }else{
        tinyMCE.activeEditor.setContent(origin_content);
        $('#name').val(origin_string_subject);
        click = 1;
      }

    });
  });
  //Code Dung code

  //dung code - add link solargain quote in opoup in details module Leads
  $(document).ready(function(){
    // var number_quote_number = $('#solargain_quote_number_c').text().trim();
    // if(typeof(number_quote_number) == 'undefined' || number_quote_number == ''
    // || typeof(module_sugar_grp1) == 'undefined' || module_sugar_grp1 == ''
    // || typeof(action_sugar_grp1) == 'undefined' || action_sugar_grp1 == ''){
      
    // }else {
    //   if(module_sugar_grp1 == 'Leads' && action_sugar_grp1 == 'DetailView'){
    //     var href = "<a target='_blank' href='https://crm.solargain.com.au/quote/edit/" + number_quote_number +"'>Open Quote Solargain</a>";
    //     $('#parent_type').parent().parent().append(href);
    //   }
    // }
    
    //dung code - add template sms request adress in popupcomposerview - in module Leads
    if( typeof(module_sugar_grp1) == 'undefined') module_sugar_grp1 = '';
    if( typeof(action_sugar_grp1) == 'undefined') action_sugar_grp1 = '';
    if( typeof(module_sugar_grp1) !== 'undefined' || module_sugar_grp1 !== ''
    || typeof(action_sugar_grp1) !== 'undefined' || action_sugar_grp1 !== ''){
      if(module_sugar_grp1 == 'Leads' && action_sugar_grp1 == 'DetailView'){
          var emails_email_templates_idb = $('#emails_email_templates_idb').val();
          //if(emails_email_templates_idb == '383cde5c-de72-3902-2a9a-5b5008c452d0'){
             var user_name = $('#assigned_user_id').text().trim();
             user_name = user_name.split(" ");
             user_name = user_name[0];

             var name_customer = $('#full_name').text().trim();
             name_customer = name_customer.split(" ");
             name_customer = name_customer[0];

             var phone_number_customer = $('div[field="phone_mobile"]').text().trim();

             phone_number_customer = phone_number_customer.replace(/\D/g, '').replace(/^04/g, '+614');
            // $('#number_client').val(phone_number_customer);  

             var address_content_messager = '';
             $('#primary_address_city').val() !== ''? address_content_messager +=  ' ' +$('#primary_address_city').val().trim():address_content_messager;
             $('#primary_address_state').val() !== ''? address_content_messager += ' ' +$('#primary_address_state').val().trim():address_content_messager;
             $('#primary_address_postalcode').val() !== ''? address_content_messager += ' '+$('#primary_address_postalcode').val().trim():address_content_messager;
             var content_messager_send_request_address_SMS = 'Hi '+ name_customer +', my name is '+user_name+' from PureElectric a strategic Solargain partner. I received your request for a Solargain solar quote for your place, I have that you are in'+ address_content_messager +'? If you could please reply back with your street address I would be more than happy to assist. You can reply back via SMS to this number, or email me '+((user_name=="Matthew")?"matthew.wright@pure-electric.com.au":"paul.szuster@pure-electric.com.au")+' or give me a call '+((user_name=="Matthew")?"0421 616 733":"0423 494 949")+'. Look forward to your response. Regards, '+user_name+'.' ;
             
            //  $('#sms_message').val(content_messager_send_request_address_SMS);
            //  var sending_number = (user_name=="Matthew")?"+61421616733":"+61490942067";
            //  $("#number_send_sms").val(sending_number);
         //}
      }
    }
  })

  //dung code - popup show image and file pdf file attackment
 $('body').append ('<div style="background: black;z-index:9999999;position: fixed;width: 100%;height:100%;display: none;border: 1px solid black;margin: 0 auto;text-align: center;"  id="popup_content">'
 + '<button id="popup_bottom_preview" type="button" class="btn btn-info"><<</button>'
 +'<button  id="popup_bottom_close" type="button" class="btn btn-info">X</button>'
 + '<button  id="popup_bottom_next" type="button" class="btn btn-info">>></button>'
 +'<div style=" height:100%;" id="group_img"></div>'
 +'</div>');
 var current_img_id = '';
 var count_img=1;
  $(document).ready(function(){
    $(document).on('click','div[class="attachment-group-container"]>label',function(event){  
      var Check_id_file_current = $(this).parent().find('select').attr('id');
      $('#group_img').empty();
      // if($('input[name="module"]').val() !== 'Leads' && $('input[name="return_action"]').val() == 'DetailView'){
      //   return;
      // }
      $('.attachments .file-attachments .attachment-group-container').each(function(){
        $('#popup_content').show();
        var name_folder = $(this).find('select').attr('id');
        var name_file =$(this).find('.attachment-file-container .attachment-name').text();
        name_file = name_file.replace('Email Attachment:','').trim();
        var type_file = name_file.slice(-3).toLowerCase();
        var url_img = '/upload/'+name_folder;
        $('#group_img').append ('<div style="padding:0 5% 5% 5%; height:100%; display:none;" id="popup_image_'+count_img+'"></div>');
        if(type_file == 'png'||type_file == 'jpg' ||type_file == 'gif' || type_file == 'peg'){
          $('#popup_image_'+count_img).append ('<img width="100%" src="' +url_img +'" />');
        }else if(type_file == 'pdf'){
          $('#popup_image_'+count_img).append ("<object style='pointer-events:auto;' style='width:100%;height:100%;' data='"+url_img+"' type='application/pdf' width='100%' height='100%'></object>");   
        }
        if(Check_id_file_current == name_folder) current_img_id = count_img;
        count_img++;
      });
      $('#popup_image_'+current_img_id).show();
    })   
  });

    $('#popup_bottom_close').click(function(e){
      $('#popup_image_'+current_img_id).hide();
      $('#popup_content').hide();
      count_img = 1;
      current_img_id='';
      e.preventDefault();
    });

    $('#popup_bottom_preview').click(function(e){
      $('#popup_image_'+current_img_id).hide();
      if(current_img_id == 1){
        current_img_id = count_img;
      }else {
        current_img_id --;
      }
      $('#popup_image_'+current_img_id).show();
      e.preventDefault();
    });

    $('#popup_bottom_next').click(function(e){
      $('#popup_image_'+current_img_id).hide();
      if(current_img_id == count_img){
        current_img_id = 1;
      }else {
        current_img_id ++;
      }
      $('#popup_image_'+current_img_id).show();
      e.preventDefault();
    });

//thienpb code -- comment
$(function(){
  var html_cmt = "<div style='position: absolute;width: 165px;left: -35px;top: 50px;'>";
  html_cmt += "<label>Quick Comment</label>";
  html_cmt += "<select name='slb_comment' id='slb_comment' style='width:100%;'><option value=''></option></select>";
  html_cmt += "</br><button type='button' class='button' id='btn_insert_cmt' style='width: 80px;margin-top: 5px;'>Insert</button>";
  html_cmt += "</br><button class='button' type='button' id='btn_edit_template_comment' style='width: 80px;margin-top: 5px;'>Edit</button>&nbsp;";
  html_cmt += "<label style='margin-top: 20px;'>Comment Label</label>";
  html_cmt += "<button  type='button' hidden class='button' id='btn_update_cmt'>Update</button>";
  html_cmt += "<textarea id='textarea_cmt' name='textarea_cmt' style='width:100%;font-weight:normal;line-height:20px;' rows='3'></textarea>";
  html_cmt += "</div>";
  $("div[data-label='description']").append(html_cmt);
  $(document).find("#slb_comment").find('option:not(:first)').remove();

  $('#textarea_cmt').attr('data-click-state', 1);
    $("#textarea_cmt").hover(function(){
        if($(this).attr('data-click-state') == 1) {
            $(this).height( $(this)[0].scrollHeight).change();
            $(this).attr('data-click-state', 0)
        }else{
            $(this).height(30).change();
            $(this).attr('data-click-state', 1)
        }
    });

    $.ajax({
      url: 'index.php?entryPoint=CRUD_Quick_Comment' ,
      type: 'POST',
      data: 
      {
          action: 'read',
      },
      async: true,
      success: function(result) {                         
          render_select_template(result);
      }
  }); 


  $(document).find("#slb_comment").on("change",function(){
    $(document).find("#textarea_cmt").val($(this).val());
  });

  $(document).find("#textarea_cmt").on("input",function(e){
    $(document).find("#btn_update_cmt").removeAttr("hidden");
  });

  $(document).on("click",'#btn_update_cmt',function(e){
    var textarea_cmt = $(document).find("#textarea_cmt").val();
    var titleTemplate  = $(document).find("#slb_comment option:selected").text();
    var IdTemplate = $(document).find("#slb_comment option:selected").attr('IdTemplate');
    $.ajax({
        url: 'index.php?entryPoint=CRUD_Quick_Comment' ,
        type: 'POST',
        data: 
        {
            id: IdTemplate,
            action: 'update',
            content: encodeURIComponent(textarea_cmt),
            title: encodeURIComponent(titleTemplate)
        },
        success: function(result) {                         
            render_select_template(result);
            alert('Updated Success!');
        }
    }); 
    e.stopImmediatePropagation();
  });

  $(document).on("click","#btn_insert_cmt",function(e){    
    if($(document).find("#textarea_cmt").val().trim() != ''){
      tinyMCE.activeEditor.execCommand('mceInsertRawHTML', false, $(document).find("#textarea_cmt").val().trim());
    }else{
      alert("Please select the quick comment or write to input comment label.");
    }
    e.stopImmediatePropagation();
  });

  //VUT-S-Add button copy email content to sms content
  var btn_copy_content_email_to_sms = "</br><button class='button lastChild' type='button' id='btn_copy_email_to_sms' style='margin-top: 5px;'>Copy Email to SMS</button>";
  var action_email = $('#ComposeView input[name="action"]').val();
  $('#is_only_plain_text').closest('.edit-view-row-item').append(btn_copy_content_email_to_sms);
  $('#btn_copy_email_to_sms').click(function(e){
  /**s */
  var content_email = '';
    if (action_email == 'ComposeView') {
        content_emails = $('.html_preview').children();
        $.each(content_emails,function(k,v){
            if (v.className != 'email-signature') {
              content_email += v.textContent.replace(/[\n\r]+|[\s]{2,}/,'') + '\n';
            }
        });
    } 
    else { //don't get table infomation Quote in email content 
      content_email = $('.html_preview table:first-child td').html();
      if (content_email != undefined) {
        content_email= content_email.replace(/<[^>]*>?/gm, '');
      } else {
        content_email = $('.html_preview').html();
        if (content_email != undefined) {
          content_email= content_email.replace(/<[^>]*>?/gm, '').replace(/\&nbsp;/g, ' ');
        }
      }
    }
    var content_sms;
    // $('textarea#sms_content').change();.replace(/<[^>]*>?/gm, '')
    $('textarea#sms_content').val(content_email);
    content_sms = $('textarea#sms_content').val() + "\n" +$('textarea#sms_signture').val();
    $('textarea#sms_message').val(content_sms.replace('\n\n', '\n'));
    /**s */
  });
  //VUT-E-Add button copy email content to sms content

    //dung code-- popup template quick comment
    $( "#dialog_quick_comment" ).dialog({
        autoOpen: false,
        width: 712,
        height:478,
        buttons: {
            Save: function(){
                SUGAR.ajaxUI.showLoadingPanel();
                $("#ajaxloading_mask").css("position",'fixed');
                //create new
                if($("#id_template_quick_comment").val() == '') {
                    if($("#title_quick_comment").val() == ''){
                        alert('Could you insert title please?');
                        SUGAR.ajaxUI.hideLoadingPanel();
                        return false;
                    };
                    $.ajax({
                        url: 'index.php?entryPoint=CRUD_Quick_Comment' ,
                        type: 'POST',
                        data: 
                        {
                            id: $("#id_template_quick_comment").val(),
                            action: 'create',
                            content: encodeURIComponent($("#content_quick_comment").val().trim()),
                            title: encodeURIComponent($("#title_quick_comment").val())
                        },
                        success: function(result) {              
                            render_select_template(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                        }
                    }); 
                }   
                //update
                else{
                    $.ajax({
                        url: 'index.php?entryPoint=CRUD_Quick_Comment' ,
                        type: 'POST',
                        data: 
                        {
                            id: $("#id_template_quick_comment").val(),
                            action: 'update',
                            content: encodeURIComponent($("#content_quick_comment").val().trim()),
                            title: encodeURIComponent($("#title_quick_comment").val())
                        },
                        success: function(result) {                         
                            render_select_template(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                        }
                    }); 
                }
                $("#textarea_cmt").val( $("#content_quick_comment").val().trim());  
                $(this).dialog('close');
            },
            Create: function(){
                $("#id_template_quick_comment").val('');
                $("#content_quick_comment").val('');
                $("#title_quick_comment").val('');
            },
            Insert: function(){
                $("#textarea_cmt").val( $("#content_quick_comment").val().trim());      
                $(this).dialog('close');
            },
            Delete: function(){
                var ok = confirm('Do you want delete Template !');
                if (ok){
                    SUGAR.ajaxUI.showLoadingPanel();
                    $("#ajaxloading_mask").css("position",'fixed');
                    $.ajax({
                        url: 'index.php?entryPoint=CRUD_Quick_Comment' ,
                        type: 'POST',
                        data: 
                        {
                            id: $("#id_template_quick_comment").val(),
                            action: 'delete',
                            content: encodeURIComponent($("#content_quick_comment").val().trim()),
                            title: encodeURIComponent($("#title_quick_comment").val())
                        },
                        success: function(result) {                         
                            render_select_template(result);
                            SUGAR.ajaxUI.hideLoadingPanel();
                            $("#content_quick_comment").val('');
                            $("#title_quick_comment").val('');
                            $("#id_template_quick_comment").val('');
                        }
                    }); 
                }
            },
            Cancel: function(){
                $(this).dialog('close');
            },
        }
    });
    $("#btn_edit_template_comment").click(function(e){
        SUGAR.ajaxUI.showLoadingPanel();
        $("#ajaxloading_mask").css("position",'fixed');
        $.ajax({
            url: 'index.php?entryPoint=CRUD_Quick_Comment' ,
            type: 'POST',
            data: 
            {
                action: 'read',
            },
            async: true,
            success: function(result) {                         
                render_select_template(result);
                SUGAR.ajaxUI.hideLoadingPanel();
                $( "#dialog_quick_comment" ).dialog("open");
            }
        }); 
        return false;
    })

    $('#select_title_template_quick_comment').change(function(){
        var id = $('#select_title_template_quick_comment').val();
        if(id == '') return false;
        var title = $('#select_title_template_quick_comment option:selected').text();
        $("#title_quick_comment").val(title);
        $("#id_template_quick_comment").val(id);
        $("#content_quick_comment").val(window.data_quick_comment[id].content.trim());
    });

    function render_select_template(result){
      var data_result = $.parseJSON(result);
      window.data_quick_comment = data_result;
      $('#select_title_template_quick_comment').empty();
      $('#select_title_template_quick_comment').append($('<option>', {
          value: '',
          text: ''
      }));
      $(document).find("#slb_comment").empty();
      $('#slb_comment').append($('<option>', {
          value: '',
          text: ''
      }));
      $.each(data_result,function(k,v){
          if((!v.title.includes('(Plumbing Notes)')) && (!v.title.includes('(Electrical Notes)')) ) {
              $('#select_title_template_quick_comment').append($('<option>', {
                value: k,
                text: v.title
            }));
          }

          $(document).find("#slb_comment").append($('<option>', {
            value: v.content,
            text: v.title,
            IdTemplate: k
          }));
      });
  }
    //end dung code-- popup template quick comment
});
