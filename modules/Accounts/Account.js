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
function open_contact_popup(module_name,width,height,initial_filter,close_popup,hide_clear_button,popup_request_data,popup_mode,create,metadata)
{window.document.popup_request_data=popup_request_data;window.document.close_popup=close_popup;URL='index.php?mode=MultiSelect&'
+'module='+module_name
+'&action=ContactAddressPopup';if(initial_filter!='')
{URL+='&query=true'+initial_filter;}
if(hide_clear_button)
{URL+='&hide_clear_button=true';}
windowName='popup_window';windowFeatures='width='+width
+',height='+height
+',resizable=1,scrollbars=1';if(popup_mode==''&&popup_mode=='undefined'){popup_mode='single';}
URL+='&mode='+popup_mode;if(create==''&&create=='undefined'){create='false';}
URL+='&create='+create;if(metadata!=''&&metadata!='undefined'){URL+='&metadata='+metadata;}
win=window.open(URL,windowName,windowFeatures);if(window.focus)
{win.focus();}
return win;}
function set_focus(){document.getElementById('name').focus();}
// BinhNT add it back
$(document).on('click','.btn_button_today',function(){
    showSubPanel('account_po_purchase_order','/index.php?module=Accounts&return_module=Accounts&action=DetailView&record=61db330d-0aee-6661-8ac3-585c79c765a2&ajax_load=1&loadLanguageJS=1&Accounts_account_po_purchase_order_CELL_offset=&inline=true&to_pdf=true&action=SubPanelViewer&subpanel=account_po_purchase_order&Accounts_account_po_purchase_order_CELL_ORDER_BY=&layout_def_key=Accounts&inline=1&ajaxSubpanel=true&_=1534489638044&today_button=yes',true)
});

$(document).on('click','.btn_button_reset',function(){
    showSubPanel('account_po_purchase_order','/index.php?module=Accounts&return_module=Accounts&action=DetailView&record=61db330d-0aee-6661-8ac3-585c79c765a2&ajax_load=1&loadLanguageJS=1&Accounts_account_po_purchase_order_CELL_offset=&inline=true&to_pdf=true&action=SubPanelViewer&subpanel=account_po_purchase_order&Accounts_account_po_purchase_order_CELL_ORDER_BY=&layout_def_key=Accounts&inline=1&ajaxSubpanel=true&_=1534489638044',true)
});

$(document).ready(function(){
    // button create contact xero from account
    $('#tab-actions').after('<li><a id="create_contact_xero" >Create Contact Xero<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></a></li>');
    $('body').on('click','#create_contact_xero',function(){
        $.ajax({
            url: "/index.php?entryPoint=customCreatePOXeroContact&contact=1&method=put" + '&record='+ encodeURIComponent($('input[name="record"]').val()),
            success: function (data) {
                if(data !="success"){
                    alert("Can't create XERO contact");
                }else{
                    alert('Create Xero Contact Is Done.');
                }
            }
        });
    })
    // find to closest Plumber
    // if($('#name').html() == "I DO GROUP PTY LTD" || $('#name').html() == "Plumbing Bros" || $('#name').html() == "Chilli Hot Water & Plumbing" ||$('#name').html() == "Rite Choice Plumbing and Gas" || $('#name').html() == "Christopher Fawaz" || $('#name').html() == "ASAP Local Plumbing2" || $('#name').html() == "L.A ELECTRICAL CONECTIONS" ){
    //     $("div[field='sanden_plumber_c']").parent().parent().hide();
    // }
    // $('body').on("click",'#inlineEditSaveButton',function(){
        
    //     if($(this).parent().find("div[field='sanden_plumber_c'] input").attr('id') == 'sanden_plumber_c' ) {
    //         if( $(this).parent().find("div[field='sanden_plumber_c'] input").is(':checked')){
    //             var record_id = $('#formDetailView input[name="record"]').val();
    //             var sanden_plumber = $('div[field="billing_address_street"] #billing_address_street').val() +','+ $('div[field="billing_address_street"] #billing_address_city').val() +' '+ $('div[field="billing_address_street"] #billing_address_state').val() +' '+ $('div[field="billing_address_street"] #billing_address_postalcode').val() ;
    //             $.ajax({
                    
    //                 url: "?entryPoint=customFilterPlumber&address="+ sanden_plumber +"&record_id=" + record_id+"&type=Plumer" ,
    //                 success: function (data) {
    //                     var jsonObject = $.parseJSON(data);
    //                     if( jsonObject.error !=""){
    //                         alert(json.error);
    //                     }
    //                 }
    //             });
    //         }    
    //     }
})