$(document).ready(function(){
       

    function render_link_info(module_sugar_grp1,record,position_append){
        // Create open link PO,Quote,Invoice,Account,Contact,Opportunity
        var array_module_use_fucntion = ['AOS_Quotes','AOS_Invoices','Leads','Accounts','Contacts', 'pe_service_case', 'Calls'];
        var check_module_use = array_module_use_fucntion.includes(module_sugar_grp1);
        if(record != '' && check_module_use){
            $.ajax({
                url:"?entryPoint=get_info_create_link&record="+record+"&module=" + module_sugar_grp1,
                success:function(data){
                    if(data == '' && typeof data == undefined)return;
                   var jsonObject = JSON.parse(data);
                   $html_link = '<div><table><tr>';
                   $.each(jsonObject, function(key, value) {
                        $.each(value,function(id,name){
                            if(id != '') {
                                switch (key) {
                                    case 'AOS_Quotes':
                                        // if(key == module_sugar_grp1) break;
                                        if (id == record) break;
                                        $html_link += "<td><span style='color:black;font-weight:700;'>Converted Quotes : </span><a target='_blank' href='/index.php?module=AOS_Quotes&action=DetailView&record=" + id + "'>" + name + "</a> <a target='_blank' href='/index.php?module=AOS_Quotes&action=EditView&record=" + id + "'>[E]</a></td>";
                                        break;
                                    case 'AOS_Invoices':
                                        if(key == module_sugar_grp1) break;
                                        $html_link += "<td><span style='color:black;font-weight:700;'>Converted Invoices :</span><a target='_blank' href='/index.php?module=AOS_Invoices&action=DetailView&record=" + id + "'>" + name + "</a> <a target='_blank' href='/index.php?module=AOS_Invoices&action=EditView&record=" + id + "'>[E]</a></td>";
                                        break;
                                    case 'Opportunities':
                                        if(key == module_sugar_grp1) break;
                                        $html_link += "<td><span style='color:black;font-weight:700;'>Converted Opportunity :</span><a target='_blank' href='/index.php?module=Opportunities&action=DetailView&record=" + id + "'>" + name + "</a> <a target='_blank' href='/index.php?module=Opportunities&action=EditView&record=" + id + "'>[E]</a></td>";
                                        break;                           
                                    case 'PO_purchase_order':
                                        if(key == module_sugar_grp1) break;
                                        $html_link += "<td><span style='color:black;font-weight:700;'>Converted PO :</span><a target='_blank' href='/index.php?module=PO_purchase_order&action=DetailView&record=" + id + "'>" + name + "</a> <a target='_blank' href='/index.php?module=PO_purchase_order&action=EditView&record=" + id + "'>[E]</a></td>";
                                        break;
                                    case 'Accounts':
                                        if(key == module_sugar_grp1) break;
                                        $html_link += "<td><span style='color:black;font-weight:700;'>Converted Account :</span><a target='_blank' href='/index.php?module=Accounts&action=DetailView&record=" + id + "'>" + name + "</a> <a target='_blank' href='/index.php?module=Accounts&action=EditView&record=" + id + "'>[E]</a></td>";
                                        break;
                                    case 'Leads':
                                        if(key == module_sugar_grp1) break;
                                        $html_link += "<td><span style='color:black;font-weight:700;'>Converted Leads :</span><a target='_blank' href='/index.php?module=Leads&action=DetailView&record=" + id + "'>" + name + "</a> <a target='_blank' href='/index.php?module=Leads&action=EditView&record=" + id + "'>[E]</a></td>";
                                        break;
                                    case 'Contacts':
                                        if(key == module_sugar_grp1) break;
                                        $html_link += "<td><span style='color:black;font-weight:700;'>Converted Contact :</span><a target='_blank' href='/index.php?module=Contacts&action=DetailView&record=" + id + "'>" + name + "</a> <a target='_blank' href='/index.php?module=Contacts&action=EditView&record=" + id + "'>[E]</a></td>";
                                        break;
                                    case 'Calls':
                                        if(id == record) break;
                                        $html_link += "<td><span style='color:black;font-weight:700;'>Converted Calls :</span><a target='_blank' href='/index.php?module=Calls&action=DetailView&record=" + id + "'>" + name + "</a> <a target='_blank' href='/index.php?module=Calls&action=EditView&record=" + id + "'>[E]</a></td>";
                                        break;
                                    default:
                                        break;
                                }
                            }                         
                        })
                    });
                    $html_link += '</tr></table></div>';
                    $(position_append).before($html_link);
                }
            });
        }
    }
    
    function render_nav_panel_right(module_sugar_grp1){
        var array_module_use_fucntion = ['AOS_Quotes','AOS_Invoices','Leads','PO_purchase_order','pe_warehouse_log','pe_service_case'];
        var check_module_use = array_module_use_fucntion.includes(module_sugar_grp1);
        if(!check_module_use) return;
        var json_menu_nav = [];
        var html_menu_nav = "<nav id='custom_nav'><button type='button' class='button' id='button_open_nav'><i class='glyphicon glyphicon-plus'></i></button><br><button type='button' style='padding-left: 21px;font-size:large;padding-right: 22px;' class='button' id='button_save_nav'>S</button><br><button type='button' class='button'  id='button_back_to_top' onclick='SUGAR.util.top();' href='javascript:void(0)'><i class='glyphicon glyphicon-arrow-up'></i></button><ul hidden><br><nav id='custom_nav_list'>";
        if (module_sugar_grp1 == 'pe_service_case') {
            $('body').find('div[class="panel-content"] div[class="panel panel-default tab-panel-0"]').each(function() {
                $(this).removeClass('tab-panel-0');
            });
        }
        $('body').find('div[class="panel-content"] div[class="panel panel-default"]').each(function(){
            var name_panel = $(this).find('.panel-heading').text().trim();
            if (module_sugar_grp1 == 'AOS_Invoices' && $('#quote_type_c').val() != 'quote_type_sanden' && name_panel == 'OLD HWS INFORMATION') {
                $(this).hide();
                return;
            } else {
                var id_panel = $(this).find('.panel-body').attr('id').trim();
                var elem_object = { 'id_panel' : id_panel , 'name_panel' : name_panel};
                json_menu_nav.push(elem_object);
                html_menu_nav += "<li><a class='link_into_panel' data-id='#"+id_panel +"' >" + name_panel +"</a></li>";
            }
        })
        //VUT-S-Internal Note (Shortcut)
        if (module_sugar_grp1 != 'pe_service_case') {
            html_menu_nav += "<li><a class='link_into_panel_c' data-id='#hack_code'>Internal Notes</a></li>";
        }
        //VUT-E-Internal Note (Shortcut)
        html_menu_nav += "</ul></nav>";
        $("#EditView_tabs").append(html_menu_nav);
        /**Add button SAVE&EDIT at nav_right */
        $('#button_save_nav').after("<br><button type='button' style='padding-left: 10px;font-size:medium;padding-right: 12px;' class='button' id='button_save_edit_nav_right' onClick='SUGAR.saveAndEdit(this);'>S&E</button>");
        $('#button_back_to_top').fadeOut(200);
    }
    function render_nav_panel_left(module_sugar_grp1){
        var array_module_use_fucntion = ['AOS_Quotes','AOS_Invoices','Leads','PO_purchase_order','pe_warehouse_log','pe_service_case'];
        var check_module_use = array_module_use_fucntion.includes(module_sugar_grp1);
        if(!check_module_use) return;
        var json_menu_nav = [];
        var html_menu_nav = "<nav id='custom_nav_left'><button type='button' class='button' id='button_open_nav_left'><i class='glyphicon glyphicon-plus'></i></button><br><button type='button' style='padding-left: 21px;font-size:large;padding-right: 22px;' class='button' id='button_save_nav_left'>S</button><br><button type='button' class='button'  id='button_back_to_top_left' onclick='SUGAR.util.top();' href='javascript:void(0)'><i class='glyphicon glyphicon-arrow-up'></i></button><ul hidden><br><nav id='custom_nav_list_left'>";
        if (module_sugar_grp1 == 'pe_service_case') {
            $('body').find('div[class="panel-content"] div[class="panel panel-default tab-panel-0"]').each(function() {
                $(this).removeClass('tab-panel-0');
            });
        }
        $('body').find('div[class="panel-content"] div[class="panel panel-default"]').each(function(){
            var name_panel = $(this).find('.panel-heading').text().trim();
            if (module_sugar_grp1 == 'AOS_Invoices' && $('#quote_type_c').val() != 'quote_type_sanden' && name_panel == 'OLD HWS INFORMATION') {
                $(this).hide();
                return;
            } else {
                var id_panel = $(this).find('.panel-body').attr('id').trim();
                var elem_object = { 'id_panel' : id_panel , 'name_panel' : name_panel};
                json_menu_nav.push(elem_object);
                html_menu_nav += "<li><a class='link_into_panel_left' data-id='#"+id_panel +"' >" + name_panel +"</a></li>";
            }
        })
        //VUT-S-Internal Note (Shortcut)
        if (module_sugar_grp1 != 'pe_service_case') {
            html_menu_nav += "<li><a class='link_into_panel_c' data-id='#hack_code'>Internal Notes</a></li>";
        }
        //VUT-E-Internal Note (Shortcut)
        html_menu_nav += "</ul></nav>";
        $("#EditView_tabs").append(html_menu_nav);
        /**Add button SAVE&EDIT at nav_left */
        $('#button_save_nav_left').after("<br><button type='button' style='padding-left: 10px;font-size:medium;padding-right: 12px;' class='button' id='button_save_edit_nav_left' onClick='SUGAR.saveAndEdit(this);'>S&E</button>");
        $('#button_back_to_top_left').fadeOut(200);
    }

    // ===== Scroll to Top ==== 
    $(window).scroll(function() {
        if ($(this).scrollTop() >= 100) {      
            $('#button_back_to_top').fadeIn(200);    
        } else {
            $('#button_back_to_top').fadeOut(200);   
        }
    });
    $(window).scroll(function() {
        if ($(this).scrollTop() >= 100) {      
            $('#button_back_to_top_left').fadeIn(200);    
        } else {
            $('#button_back_to_top_left').fadeOut(200);   
        }
    });

    //VUT-S-Internal Note (Shortcut)
        $("body").on('click','.link_into_panel_c',function(){
            var element_id = $(this).attr('data-id');
            $('html, body').animate({
                scrollTop: (($(element_id).offset().top) - 100)
            },100);
        })
    //VUT-E-Internal Note (Shortcut)
    $("body").on('click','.link_into_panel',function(){
        
        var element_id = $(this).attr('data-id');
        $('html, body').animate({
            scrollTop: (($(element_id).closest(".panel-default").offset().top) - 100)
        },100);
    })
    $("body").on('click','.link_into_panel_left',function(){
        
        var element_id = $(this).attr('data-id');
        $('html, body').animate({
            scrollTop: (($(element_id).closest(".panel-default").offset().top) - 100)
        },100);
    })
    $('body').on('click','#button_open_nav',function(){
        $('#custom_nav ul').toggle();
    });
    $('body').on('click','#button_open_nav_left',function(){
        $('#custom_nav_left ul').toggle();
    });
    $('body').on('click','#button_save_nav',function(){
        var _form = document.getElementById('EditView');
        _form.action.value='Save'; 
        if(check_form('EditView'))SUGAR.ajaxUI.submitForm(_form);
        return false;
    });
    $('body').on('click','#button_save_nav_left',function(){
        var _form = document.getElementById('EditView');
        _form.action.value='Save'; 
        if(check_form('EditView'))SUGAR.ajaxUI.submitForm(_form);
        return false;
    });
    
    if(action_sugar_grp1 == 'DetailView'){ // detail view
        //VUT-Clone Edit button
        $('.nav.nav-tabs .active').after($('#tab-actions li:first').clone()); 

        record = $('body').find('input[name="record"]').val();
        position_append = "div[class='detail-view']";
        display_status_title(module_sugar_grp1,action_sugar_grp1);
        // if(module_sugar_grp1 == 'Leads') return;
        render_link_info(module_sugar_grp1,record,position_append);
        
    }else if(action_sugar_grp1 == 'EditView'){ // edit view
    
        record = $('body').find('input[name="record"]').val();
        position_append = "#EditView_tabs";
        render_link_info(module_sugar_grp1,record,position_append);
        render_nav_panel_right(module_sugar_grp1);
        render_nav_panel_left(module_sugar_grp1);
        display_status_title(module_sugar_grp1,action_sugar_grp1);
        //VUT-S-Add button assign user anywhere
        button_assign_user = '<button style="margin: 0px 1px" type="button" name="Paul Szuster" class="button select_assign_user" value="61e04d4b-86ef-00f2-c669-579eb1bb58fa">PS</button>'
            +'<button style="margin: 0px 1px" type="button" name="Matthew Wright" class="button select_assign_user" value="8d159972-b7ea-8cf9-c9d2-56958d05485e">MW</button>';
            // +'<button style="margin: 0px 1px" type="button" name="John Hooper" class="button select_assign_user" value="b33d5d2f-89fc-ce57-1df9-5e38d4d8e98d">JH</button>';
        $('#assigned_user_name').parent().append(button_assign_user);
        $('#assigned_user_name').css("width", "150px")
        //VUT-E-Add button assign user anywhere
        //VUT - S - js change title
        $(document).on('change', '#name', function(){
            display_status_title(module_sugar_grp1,action_sugar_grp1);
        });
        //VUT - E - js change title
    }

    //VUT-S-Click button assign user 
    $("body").on('click','.select_assign_user',function(){
        // debugger;
        var assigned_user_id = $(this).val();
        var assigned_user_name = $(this).attr('name');
        $('#assigned_user_id').val(assigned_user_id);
        $('#assigned_user_name').val(assigned_user_name);
    })
    //VUT-E-Click assign user button
    function display_status_title(module_sugar_grp1,action_sugar_grp1){
        var array_module_use_fucntion = ['AOS_Quotes','AOS_Invoices','Leads','Accounts','Contacts','pe_warehouse_log', 'pe_service_case', 'Calls', 'PO_purchase_order'];
        var check_module_use = array_module_use_fucntion.includes(module_sugar_grp1);
        if(!check_module_use) return;
        if(action_sugar_grp1 == 'EditView'){
            switch (module_sugar_grp1) {
                case 'Leads':
                    $('.module-title-text a').text('Lead #'+$('div[field="number"]').text().trim()+' '+$('.module-title-text a').text().trim());
                    $('.module-title-text').append('<span>('+$("#status option:selected").text()+')</span>');
                    break;
                case 'AOS_Quotes':
                    $('.module-title-text a').text('');
                    $('.module-title-text a').text('Quote #'+$('div[field="number"]').text().trim()+' '+$(document).find('#name').val());
                    $('.module-title-text').append('<span>('+$("#stage option:selected").text()+')</span>');
                    break;            
                case 'AOS_Invoices':
                    $('.module-title-text a').text('');
                    $('.module-title-text a').text('Invoice #'+$('div[field="number"]').text().trim()+' '+$(document).find('#name').val());
                    $('.module-title-text').append('<span>('+$("#status option:selected").text()+')</span>');
                    break; 
                case 'Accounts':
                    $('.module-title-text a').text('Account #'+$('div[field="number"]').text().trim()+' '+$('.module-title-text a').text().trim());
                    break;  
                case 'Contacts':
                    $('.module-title-text a').text('Contact #'+$('div[field="number"]').text().trim()+' '+$('.module-title-text a').text().trim());
                    break; 
                case 'pe_warehouse_log':
                    if($("#shipping_product_type_c option:selected").text() != ''){
                        $('.module-title-text a').append(' - '+$("#shipping_product_type_c option:selected").text());
                    }
                    break;         
                case 'pe_service_case':
                    $('.module-title-text a').text('');
                    $('.module-title-text a').text('Service Case #'+$('div[field="number"]').text().trim()+' '+$(document).find('#name').val());
                    break;  
                case 'Calls':
                    $('.module-title-text a').text('Calls #'+$('div[field="number"]').text().trim()+' '+$('.module-title-text a').text().trim());
                    break;  
                case 'PO_purchase_order':
                    $('.module-title-text a').text('');
                    $('.module-title-text a').text($(document).find('#name').val());
                    break;  
                default:
                    break;
            }
        }else if(action_sugar_grp1 == 'DetailView'){
            switch (module_sugar_grp1) {
                case 'Leads':
                        $('.module-title-text').text('Lead #'+$('#number').text().trim()+' '+$('.module-title-text').text().trim());
                    $('.module-title-text').append('<span>('+$("#status").parent().text().trim()+')</span>');
                    break;
                case 'AOS_Quotes':
                    $('.module-title-text').text('Quote #'+$('#number').text().trim()+' '+$('.module-title-text').text().trim());
                    $('.module-title-text').append('<span>('+$("#stage").parent().text().trim()+')</span>');
                    break;            
                case 'AOS_Invoices':
                        $('.module-title-text').text('Invoice #'+$('#number').text().trim()+' '+$('.module-title-text').text().trim());
                    $('.module-title-text').append('<span>('+$("#status").parent().text().trim()+')</span>');
                    break;
                case 'Accounts':
                        $('.module-title-text').text('Account #'+$('#number').text().trim()+' '+$('.module-title-text').text().trim());
                    break;
                case 'Contacts':
                        $('.module-title-text').text('Contact #'+$('#number').text().trim()+' '+$('.module-title-text').text().trim());
                    break;
                case 'pe_warehouse_log':
                    if($("#shipping_product_type_c").val() != ''){
                        $('.module-title-text').text($('.module-title-text').text().trim()+' - '+$("div[field='shipping_product_type_c']").text().trim());
                    }
                    break;       
                case 'pe_service_case':
                    $('.module-title-text').text('Service Case #'+$('#number').text().trim()+' '+$('.module-title-text').text().trim());
                    break;  
                case 'Calls':
                    $('.module-title-text').text('Calls #'+$('#number').text().trim()+' '+$('.module-title-text').text().trim());
                    break;  
                default:
                    break;
            }
        }

    }

    //thienpb code -- show hide description
    $('.product_item_description, #quote_note_c').attr('data-click-state', 1);
    $(".product_item_description, #quote_note_c").hover(function(){
        if($(this).attr('data-click-state') == 1) {
            $(this).height( $(this)[0].scrollHeight).change();
            $(this).attr('data-click-state', 0)
        }else{
            $(this).height(30).change();
            $(this).attr('data-click-state', 1)
        }
    });
    //VUT-S-Wrap textarea for LEAD/INVOICE/QUOTE in EditView
    function remove_break_line_textarea(){
        var array_field_not_use_function = ['short_description_c'];
        var allTextarea = $(document).find('textarea');
        allTextarea.each(function(){
            var check_field_not_use = array_field_not_use_function.includes($(this).attr('id'));
            // debugger
            if(!check_field_not_use) {
                if (module_sugar_grp1 == 'Calls' && $(this).attr('id') == 'description') {
                    $(this).attr('rows', 5);
                } else { 
                    $(this).attr('rows', 2);
                }
                var textarea = $(this).val();
                $(this).val(textarea.replace(/\n{2,}/g, "\r\n"));
                autosize.update($(this));
            }
        })
    }
    if (action_sugar_grp1 == 'EditView') {
        autosize($('textarea'));
        autosize.destroy($('.product_item_description'));
        autosize.destroy($('#solar_pv_pricing_input_c'));
        autosize.destroy($('#own_solar_pv_pricing_c'));
        autosize.destroy($('#quote_note_c'));

        if (module_sugar_grp1 == 'Calls') {
            autosize.destroy($('#description'));
        } else if (module_sugar_grp1 == 'AOS_Invoices') {
            autosize.destroy($('#content_ces_template'));
        }
        remove_break_line_textarea();
        $('textarea').on('change', function(){
            remove_break_line_textarea();
        }); 
    }
    
    //VUT-E-Wrap textarea for LEAD/INVOICE/QUOTE in EditView

    //change format for input field phonenumber
    $('#phone_mobile,#phone_work,#mobile_phone_c,#phone_office').keyup(function() {
        let text=$(this).val()                             //Get the value
        let id = $(this).attr('id');
        debugger;
        text=text.replace(/\D/g,'')                        //Remove illegal characters
        switch (id) {
            //mobile phone
            case 'phone_mobile': case 'mobile_phone_c':
                if(text.length>4) text=text.replace(/.{4}/,'$& ')  //Add hyphen at pos.4
                if(text.length>8) text=text.replace(/.{8}/,'$& ')  //Add hyphen at pos.8
                break;
            //office phone
            case 'phone_work': case 'phone_office':
                if(text.length>2) text=text.replace(/.{2}/,'$& ')  //Add hyphen at pos.2
                if(text.length>7) text=text.replace(/.{7}/,'$& ')  //Add hyphen at pos.7
                break;
        }
        $(this).val(text); 
    }); 
    //VUT-S-Format phone number
        setTimeout(function () {
            if (action_sugar_grp1 == 'DetailView') {
                //mobile phone Lead/Acc/Contact
                $('#mobile_phone_c, [field="mobile_phone_c"] a:first, [field="phone_mobile"] a:first').text(function (i, phonenumber) {
                    phonenumber = phonenumber.trim();
                    phonenumber = phonenumber.replace(/(\d{4})(\d{3})(\d{3})/, '$1 $2 $3');
                    return phonenumber;
                });
                //office phone Acc/Contact
                $('[field="phone_office"] a:first, [field="phone_work"] a:first').text(function (i, phonenumber) {
                    phonenumber = phonenumber.trim();
                    phonenumber = phonenumber.replace(/(\d{2})(\d{4})(\d{4})/, '$1 $2 $3');
                    return phonenumber;
                });
            }
            if (action_sugar_grp1 == 'EditView') { 
                //mobile phone Lead/Acc/Contact
                $('#phone_mobile,#mobile_phone_c').val(function(i, phonenumber) {
                    phonenumber = phonenumber.trim();
                    phonenumber = phonenumber.replace(/(\d{4})(\d{3})(\d{3})/, '$1 $2 $3');
                    return phonenumber;
                });
                //office phone Acc/Contact
                $('#phone_office, #phone_work').val(function(i, phonenumber) {
                    phonenumber = phonenumber.trim();
                    phonenumber = phonenumber.replace(/(\d{2})(\d{4})(\d{4})/, '$1 $2 $3');
                    return phonenumber;
                });
            }
        }, 500);
    //VUT-E-Format phone number

    //thienpb code change STCs vat
    $("#lineItems").on("blur",".product_name",function(){
        if($(this).val() == 'STCs'){
            $(this).closest("tr").find(".product_vat_amt_select").val('0.0').trigger('change');
        }
    });

    //Return detail view when open and save record from Dashlet
    if (action_sugar_grp1 == 'EditView' && $("input[name='return_module']").val() == 'Home' && $("input[name='return_action']").val() == 'index') {
        // debugger;
        $("input[name='return_module']").val('');
        $("input[name='return_action']").val('');
        $("input[name='relate_to']").val('');
    }
    //collapsed subpanel >> https://trello.com/c/pwBJMLYB/3126-suite-panels-invoice-quote-lead-certain-sub-panels-are-expanded-with-no-data-information-but-other-panels-are-not-auto-expanded
    if (action_sugar_grp1 == "EditView" && (module_sugar_grp1 == "AOS_Invoices" || module_sugar_grp1 == "Leads" || module_sugar_grp1 == "AOS_Quotes")) {
        setTimeout(function () {
            minimise_sub();
        },500);
    }
    
})

function minimise_sub(){
    // debugger
    let sub_panel = $('body').find('div[class="panel-content"] div[class="panel panel-default"]');
    $.each(sub_panel,function(){
        let check = false;
        $(this).find("input[type='text']").each(function(){
            if ($(this).val() != '') {
                check = true;
                return false;
            }
        });
        if (check == false) {
            $(this).find('a[data-toggle="collapse-edit"]').addClass('collapsed')
            $(this).find('.panel-body.panel-collapse').removeClass('in')
        }
    });
}
