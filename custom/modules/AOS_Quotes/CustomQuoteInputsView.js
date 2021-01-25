/** JS LOAD CUSTOM QUOTE INPUT  */
    $(function () {
        'use strict';
        
        $("#quote_note_inputs_c").closest('.edit-view-field').parent().parent().hide();
        var html_group_custom_quote_inputs = 
        '<div id="group_custom_quote_inputs_checklist" class="row detail-view-row"></div>';
        $("#quote_note_inputs_c").closest('.tab-content').append(html_group_custom_quote_inputs);
        var btn_generate_quote = '<button type="button" id="generate_quote" class="button primary">Save and Generate Quote</button>';
        $("#quote_note_inputs_c").closest('.tab-content').append(btn_generate_quote);
        
        if($("#quote_type_c").val() == "quote_type_solar"){
            renderQuoteInputHTML('quote_type_solar');
        }else{
            renderQuoteInputHTML('quote_type_sanden');
        }

        // $("#quote_type_c").on("change", function(){
        //     if($(this).val() == "quote_type_solar"){
        //         renderQuoteInputHTML('quote_type_solar');
        //     }else{
        //         renderQuoteInputHTML('quote_type_sanden');
        //     }
        // });

        $("#generate_quote").on('click',function(){
            if($("#quote_type_c").val() == "quote_type_solar"){
                // generate_quote_by_input('quote_type_solar');
                generateJSONForInput();
            }else{
                generate_quote_by_input('quote_type_sanden');
            }
        });
    });

    function renderQuoteInputHTML(type){
        $.ajax({
            url: '/index.php?entryPoint=APIRenderListQuoteInputs&type='+type,
            success: function (result) {
                try {
                    var json_data = JSON.parse(result);
                    $("#group_custom_quote_inputs_checklist").empty().append(json_data['template_html']);
                } catch (error) {
                    console.log(error)
                }
            }
        }).done(function (data) {
            parseJSONValueToFields();
        });
    }

    function generate_quote_by_input(dataType){
        SUGAR.ajaxUI.showLoadingPanel();
        if(dataType == "quote_type_sanden") {
            var data = new Object();
            var checkList =  $("#group_custom_quote_inputs_checklist .custom_fields");
            data['quote_generate_type'] = 'bySuite';
            data['quote_id'] = $('input[name=record]').val();
            checkList.each(function() {
                data[$(this).attr('id')] = $(this).val();
            });
            if(data != '') {
                $.ajax({
                    type: "POST",
                    url: '/index.php?entryPoint=APICreateQuoteNewFromSandenPE',
                    data: data,
                    success: function (result) {
                        try {
                            SUGAR.ajaxUI.hideLoadingPanel();
                            location.reload();
                        } catch (error) {
                            console.log(error)
                        }
                    }
                });
            }
        }else{

        }        
    }

    function parseJSONValueToFields(){
        var dataJSON = JSON.parse($("#quote_note_inputs_c").val());
        for (let key in dataJSON) {  
            $("#"+key).val(dataJSON[key]);
        }
    }

    function generateJSONForInput(){
        var values = {};
        $("#group_custom_quote_inputs_checklist .custom_fields").each(function (){
            var id_name = $(this).attr("id");
            values[id_name] = $(this).val();
        });
        $("#quote_note_inputs_c").val(JSON.stringify(values));
    }

/** END LOAD */
// $(document).ready(function() {
//     var dataType = "Sanden";
//     $("#quote_note_inputs_c").closest('.edit-view-field').parent().parent().hide();
//     var html_group_custom_quote_inputs = 
//     '<div id="group_custom_quote_inputs_checklist" class="row detail-view-row"></div>';
//     $("#quote_note_inputs_c").closest('.tab-content').append(html_group_custom_quote_inputs);
//     var btn_generate_quote = '<button type="button" id="generate_quote" class="button primary">Generate Quote</button>';
//     $("#quote_note_inputs_c").closest('.tab-content').append(btn_generate_quote);
//     $('#generate_quote').on('click', function() {
//         generate_quote_by_input();
//     });
//     function render_json_data_quoteInputs(){
//         var data = new Object();
//         data.quote_main_tank_water = $('#quote_main_tank_water').val();
//         data.quote_number_sanden = $('#quote_number_sanden').val();
//         data.quote_tank_size = $('#quote_tank_size').val();
//         data.quote_plumbing_installation_by_pure = $('#quote_plumbing_installation_by_pure').val();
//         data.quote_electrical_installation_by_pure = $('#quote_electrical_installation_by_pure').val();
//         data.quote_quick_connection_kit = $('#quote_quick_connection_kit').val();
//         data.quote_provide_stcs = $('#quote_provide_stcs').val();
//         data.quote_pickup_site_delivery = $('#quote_pickup_site_delivery').val();
//         data.quote_choice_type_install = $("#quote_choice_type_install").val();
//         data.quote_replacement_urgent = $('#quote_replacement_urgent').val();
//         data.quote_choice_type_product = $('#quote_choice_type_product').val();
//         data.quote_product_choice_type_gas = $('#quote_product_choice_type_gas').val();
//         data.quote_gas_connection = $('#quote_gas_connection').val();
//         data.quote_product_choice_type_electric = $('#quote_product_choice_type_electric').val();
//         data.quote_electric_storage_located = $('#quote_electric_storage_located').val();
//         data.quote_about_outside = $('#quote_about_outside').val();
//         data.quote_about_inside = $('#quote_about_inside').val();
//         data.quote_product_choice_type_solar = $('#quote_product_choice_type_solar').val();
//         data.quote_solar_boosted = $('#quote_solar_boosted').val();
//         data.quote_product_choice_type_wood = $('#quote_product_choice_type_wood').val();
//         data.quote_product_choice_type_lpg = $('#quote_product_choice_type_lpg').val();
//         data.quote_new_place_choice = $('#quote_new_place_choice').val();
//         data.quote_existing_install_location = $('#quote_existing_install_location').val();
//         data.quote_install_location_access = $('#quote_install_location_access').val();
//         data.quote_stair_access = $('#quote_stair_access').val();
//         data.quote_alectrical_already = $('#quote_alectrical_already').val();
//         data.quote_hot_cold_connections = $('#quote_hot_cold_connections').val();
//         data.quote_located_within = $('#quote_located_within').val();
//         data.quote_additional_untempered = $('#quote_additional_untempered').val();
//         data.quote_concrete_slab = $('#quote_concrete_slab').val();
//         data.quote_concrete_pavers = $('#quote_concrete_pavers').val();
//         data.quote_hot_water_rebate = $('#quote_hot_water_rebate').val();
//         var jsonString= JSON.stringify(data);
//         return jsonString;
//     }
//     function render_checkbox_quote_checklist(){
//         $.ajax({
//             url: '/index.php?entryPoint=APIRenderListQuoteInputs&action=render',
//             success: function (result) {
//                 try {
//                    var json_data = $.parseJSON(result);
//                    $("#group_custom_quote_inputs_checklist").append(json_data['template_html']);
                   
//                    render_select_option_quote_input();
//                 } catch (error) {
//                     console.log(error)
//                 }
//             }
//         });
//     }
//     render_checkbox_quote_checklist();
//     function render_data_quoteInputs(data) {
//         if(data == '') {
//             data = '{"quote_main_tank_water":"","quote_number_sanden":"","quote_tank_size":"","quote_plumbing_installation_by_pure":"","quote_electrical_installation_by_pure":"","quote_quick_connection_kit":"","quote_provide_stcs":"","quote_pickup_site_delivery":"","quote_choice_type_install":"","quote_replacement_urgent":"","quote_choice_type_product":"","quote_gas_connection":"","quote_product_choice_type_gas":"","quote_electric_storage_located":"","quote_about_outside":"","quote_about_inside":"","quote_product_choice_type_solar":"","quote_solar_boosted":"","quote_product_choice_type_wood":"","quote_product_choice_type_lpg":"","quote_new_place_choice":"","quote_existing_install_location":"","quote_install_location_access":"","quote_stair_access":"","quote_alectrical_already":"","quote_hot_cold_connections":"","quote_product_choice_type_electric":"","quote_located_within":"Underground","quote_additional_untempered":"","quote_concrete_slab":"","quote_concrete_pavers":"","quote_hot_water_rebate":""}';
//             data =  $.parseJSON(data);
//         }else{
//             data =  $.parseJSON(data);
//         }  
//         return data;
//     }
    
    
//     function render_select_option_quote_input(){
//         if(dataType == "Sanden") {
//             var data = $("#quote_note_inputs_c").val();
//             const parseData = render_data_quoteInputs(data);
//             if(parseData != '') {
//                 for (const [key, value] of Object.entries(parseData)) {
//                     selectElement(key, value);
//                 }
//             }
//         }
//     }

//     function generate_quote_by_input(){
//         SUGAR.ajaxUI.showLoadingPanel();
//         if(dataType == "Sanden") {
//             var data = new Object();
//             var checkList = jQuery('#group_custom_quote_inputs_checklist .edit-view-field select');
//             data['quote_generate_type'] = 'bySuite';
//             data['quote_id'] = $('input[name=record]').val();
//             checkList.each(function() {
//                 data[$(this).attr('id')] = $(this).val();
//             });
//             if(data != '') {
//                 $.ajax({
//                     type: "POST",
//                     url: '/index.php?entryPoint=APICreateQuoteNewFromSandenPE',
//                     data: data,
//                     success: function (result) {
//                         try {
//                             SUGAR.ajaxUI.hideLoadingPanel();
//                             location.reload();
//                             // location.href = window.location.href + "#detailpanel_3";
//                         } catch (error) {
//                             console.log(error)
//                         }
//                     }
//                 });
//             }
//         }
//     }
// });
// function selectElement(id, valueToSelect) {
//     if(id != 'module' && id != 'action' && id != 'entryPoint' && id != 'quote_generate_type' && id != 'quote_id') {
//         var element = document.getElementById(id);
//         element.value = valueToSelect;
//     }
// }