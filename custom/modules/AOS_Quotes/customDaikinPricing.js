var dk_main,dk_extra, dk_install, dk_wifi, dk_air_install;
const daikin_delivery = {
    'VIC' : '90',
    'default' : '40',
}; //["Daikin Factory Delivery"]; //default = 40
const daikin_ds = ['Daikin Supply', 'Daikin Supply and Installation'];
const daikin_install = ["Daikin Standard Install"];

$(function () {
    'use strict';
    //INIT
    // $(document).find('#own_solar_pv_pricing_c').attr('readonly', 'readonly');
    var quote_type = $("#quote_type_c").val();
    if(quote_type == 'quote_type_daikin'){
        init_table_daikin();
        // $("#quote_note_inputs_c").closest('.edit-view-row-item').show();
        $(document).find('#generate_quote').show();
    }

    /** Extra Add Button Click handle */ 
    $(document).on('click', '#extra_add, #main_add, #wifi_add', function(e){
        e.preventDefault();
        let attr_id = $(e.target).attr('id');
        if (attr_id.indexOf('extra') != -1) {
            DK_createNewLine('extra');
        } else if (attr_id.indexOf('main') != -1)  {
            DK_createNewLine('main');
        } else {
            DK_createNewLine('wifi');
        }
    });

    // Calculate Price Button Click handle 
    $(document).on('click', '#calculate_dk_price', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
                DK_calcOption(i);
        }
        DK_calcHint();
    });

    $(document).on('click', '#show_dk_hint', function(e){
        e.preventDefault();
        $('#dk_hint').toggle();
    });
    

    /** Clear sl button */
    $(document).on('click', '*[id*="clear_dk_option"]', function(e){
        e.preventDefault();
        DK_clearOption($(this).data('option'));
    });

    //PE Admin % handle 
    $(document).on('change', '#dk_pe_admin_percent', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
            DK_calcOption(i);
        }
    });
    
   
    // /** Clear sl button */
    // $(document).on('click', '#calculate_dk', function(e){
    //     e.preventDefault();
    //     for (var i = 1; i < 7; i++) {
    //         var main_type = $("#main_dk_type_"+i).val();
    //         var extra_type = $("#extra_dk_type_"+i).val();
    //         // Get suggested
    //         if(main_type != '' && extra_type != ""){
    //             // Calculate option
    //             DK_calcOption(i);
    //         }
    //     }
    // });

    // Solar Checkbox handle 
    $(document).on('change', 'input[id*="daikin_option"]', function(){
        checkBoxOptionHandle($(this), "daikin_option");
    });

    $(document).on("change", "select[id*='extra_dk_type'], select[id*='main_dk_type'], input[id*='qty_main_dk'], input[id*='pmdk_'], select[id*='install_dk_'], input[id*='qty_ext_dk'], input[id*='price_ext_dk'], select[id*='wifi_dk_type'], input[id*='qty_wifi_dk']", function(e){
        var index  = $(this).attr("id").split('_');
        let item_no = $(this).attr('id').charAt($(this).attr('id').length-3);
        let selector = '', type = '', qty_id ='';
        let num_of_line = 1;
        let value_selected = $(this).val();
        index = index[index.length -1];
        
        if ($(this).attr('id').indexOf('main_dk_type') != -1) {
            selector = 'main_dk_type';
            num_of_line = DK_getCountLine('main');
            type = 'main';
            qty_id = 'qty_main_dk';
        }
        if ($(this).attr('id').indexOf('wifi_dk_type') != -1) {
            selector = 'wifi_dk_type';
            num_of_line = DK_getCountLine('wifi');
            type = 'wifi';
            qty_id = 'qty_wifi_dk';
        }
        if ($(this).attr('id').indexOf('extra_dk_type') != -1) {
            selector = 'extra_dk_type';
            num_of_line = DK_getCountLine('extra');
            type = 'extra';
            qty_id = 'qty_ext_dk';
            //get cost extra fill to price extra
            $(`#price_ext_dk${item_no}_${index}`).val(getAttributeFromName($(this).val(), dk_extra, 'cost') == null ? '' : parseFloat(getAttributeFromName($(this).val(), dk_extra, 'cost')) );
        }
        if (selector != '') {
            if (value_selected == '') {
                    $(`#${qty_id}${item_no}_${index}`).val('');
            } else {
                alertExist(selector, num_of_line, index, value_selected, type, item_no);
            }
        }
        DK_calcOption(index);
    });

});
//***************************************** FUNCTION *********************************************************** */
async function init_table_daikin() {
    // Call API get Offgrid Product
    try{

        await $.ajax({
            url: '/index.php?entryPoint=APIGetDaikinProduct'
        }).then(function(result) {
            let dataJSON = JSON.parse(result);
            // Set global var
            dk_main = dataJSON.dk_main;
            dk_extra = dataJSON.dk_extra;
            dk_install = dataJSON.dk_install;
            dk_wifi = dataJSON.dk_wifi;
            dk_air_install = dataJSON.dk_air_install;
        });
    } catch (ex) {
        console.log(ex);
    }

    let daikin_pricing_table   = $('<div id="daikin_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row" style="margin-bottom: 20px;"></div>');
    let data = [
        ["Selected Option"
            ,"<input data-attr='1' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_1' style='margin-bottom:5px'> Option 1"
            ,"<input data-attr='2' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_2' style='margin-bottom:5px'> Option 2"
            ,"<input data-attr='3' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_3' style='margin-bottom:5px'> Option 3"
            ,"<input data-attr='4' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_4' style='margin-bottom:5px'> Option 4"
            ,"<input data-attr='5' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_5' style='margin-bottom:5px'> Option 5"
            ,"<input data-attr='6' type='checkbox' class='daikin_option daikin_pricing' name='daikin_option' id='daikin_option_6' style='margin-bottom:5px'> Option 6"],
        [""
            , "<button data-option ='1' id='clear_dk_option_1' class='button default'>Clear Option 1</button>"
            , "<button data-option ='2' id='clear_dk_option_2' class='button default'>Clear Option 2</button>"
            , "<button data-option ='3' id='clear_dk_option_3' class='button default'>Clear Option 3</button>"
            , "<button data-option ='4' id='clear_dk_option_4' class='button default'>Clear Option 4</button>"
            , "<button data-option ='5' id='clear_dk_option_5' class='button default'>Clear Option 5</button>"
            , "<button data-option ='6' id='clear_dk_option_6' class='button default'>Clear Option 6</button>"],
        ["PM:"
            , makeInputBox("pmdk_1 daikin_pricing", "pmdk_1", false)
            , makeInputBox("pmdk_2 daikin_pricing", "pmdk_2", false)
            , makeInputBox("pmdk_3 daikin_pricing", "pmdk_3", false)
            , makeInputBox("pmdk_4 daikin_pricing", "pmdk_4", false)
            , makeInputBox("pmdk_5 daikin_pricing", "pmdk_5", false)
            , makeInputBox("pmdk_6 daikin_pricing", "pmdk_6", false)],
        ["Total Cooling Capacity (kW):"
            , makeInputBox("daikin_pricing total_cooling_capacity_1", "total_cooling_capacity_1", true)
            , makeInputBox("daikin_pricing total_cooling_capacity_2", "total_cooling_capacity_2", true)
            , makeInputBox("daikin_pricing total_cooling_capacity_3", "total_cooling_capacity_3", true)
            , makeInputBox("daikin_pricing total_cooling_capacity_4", "total_cooling_capacity_4", true)
            , makeInputBox("daikin_pricing total_cooling_capacity_5", "total_cooling_capacity_5", true)
            , makeInputBox("daikin_pricing total_cooling_capacity_6", "total_cooling_capacity_6", true)],
        ["Total Heating Capacity (kW):"
            , makeInputBox("daikin_pricing total_heating_capacity_1", "total_heating_capacity_1", true)
            , makeInputBox("daikin_pricing total_heating_capacity_2", "total_heating_capacity_2", true)
            , makeInputBox("daikin_pricing total_heating_capacity_3", "total_heating_capacity_3", true)
            , makeInputBox("daikin_pricing total_heating_capacity_4", "total_heating_capacity_4", true)
            , makeInputBox("daikin_pricing total_heating_capacity_5", "total_heating_capacity_5", true)
            , makeInputBox("daikin_pricing total_heating_capacity_6", "total_heating_capacity_6", true)],
        ["Daikin Type 1"
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_1 daikin_pricing", "main_dk_type1_1") 
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_2 daikin_pricing", "main_dk_type1_2")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_3 daikin_pricing", "main_dk_type1_3")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_4 daikin_pricing", "main_dk_type1_4")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_5 daikin_pricing", "main_dk_type1_5")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_6 daikin_pricing", "main_dk_type1_6")],
        ["Number Daikin 1"
            , makeInputBox("qty_main_dk_1 daikin_pricing", "qty_main_dk1_1", false)
            , makeInputBox("qty_main_dk_2 daikin_pricing", "qty_main_dk1_2", false)
            , makeInputBox("qty_main_dk_3 daikin_pricing", "qty_main_dk1_3", false)
            , makeInputBox("qty_main_dk_4 daikin_pricing", "qty_main_dk1_4", false)
            , makeInputBox("qty_main_dk_5 daikin_pricing", "qty_main_dk1_5", false)
            , makeInputBox("qty_main_dk_6 daikin_pricing", "qty_main_dk1_6", false)],
        ["<button id='main_add' class='button default'>+</button>"
            , "<input type='hidden' class='daikin_pricing' name='main_line' id='main_line' value='1' />"],
        ["Wifi Type 1"
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_1 daikin_pricing", "wifi_dk_type1_1")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_2 daikin_pricing", "wifi_dk_type1_2")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_3 daikin_pricing", "wifi_dk_type1_3")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_4 daikin_pricing", "wifi_dk_type1_4")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_5 daikin_pricing", "wifi_dk_type1_5")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_6 daikin_pricing", "wifi_dk_type1_6")],
        ["Number Wifi 1"
            , makeInputBox("qty_wifi_dk_1 daikin_pricing", "qty_wifi_dk1_1", false)
            , makeInputBox("qty_wifi_dk_2 daikin_pricing", "qty_wifi_dk1_2", false)
            , makeInputBox("qty_wifi_dk_3 daikin_pricing", "qty_wifi_dk1_3", false)
            , makeInputBox("qty_wifi_dk_4 daikin_pricing", "qty_wifi_dk1_4", false)
            , makeInputBox("qty_wifi_dk_5 daikin_pricing", "qty_wifi_dk1_5", false)
            , makeInputBox("qty_wifi_dk_6 daikin_pricing", "qty_wifi_dk1_6", false)],
        ["<button id='wifi_add' class='button default'>+</button>"
            , "<input type='hidden' class='daikin_pricing' name='wifi_line' id='wifi_line' value='1' />"],
        ["Daikin Install"
            , makeSelectBox(['Yes', 'No'], "install_dk_1 daikin_pricing", "install_dk_1")
            , makeSelectBox(['Yes', 'No'], "install_dk_2 daikin_pricing", "install_dk_2")
            , makeSelectBox(['Yes', 'No'], "install_dk_3 daikin_pricing", "install_dk_3")
            , makeSelectBox(['Yes', 'No'], "install_dk_4 daikin_pricing", "install_dk_4")
            , makeSelectBox(['Yes', 'No'], "install_dk_5 daikin_pricing", "install_dk_5")
            , makeSelectBox(['Yes', 'No'], "install_dk_6 daikin_pricing", "install_dk_6")],

        ["", "&nbsp;"],
        // ["<button id='calculate_dk' class='button default'>Max</button>", "&nbsp;"],
        ["", "&nbsp;"],
        ["Extra 1"
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_1 daikin_pricing", "extra_dk_type1_1")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_2 daikin_pricing", "extra_dk_type1_2")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_3 daikin_pricing", "extra_dk_type1_3")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_4 daikin_pricing", "extra_dk_type1_4")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_5 daikin_pricing", "extra_dk_type1_5")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_6 daikin_pricing", "extra_dk_type1_6")],
        ["Extra (number/price) 1"
            , makeTwoInputBox("expand_ext extra_dk_type_1 daikin_pricing", "qty_ext_dk1_1", "price_ext_dk1_1" ,false)
            , makeTwoInputBox("expand_ext extra_dk_type_2 daikin_pricing", "qty_ext_dk1_2", "price_ext_dk1_2", false)
            , makeTwoInputBox("expand_ext extra_dk_type_3 daikin_pricing", "qty_ext_dk1_3", "price_ext_dk1_3", false)
            , makeTwoInputBox("expand_ext extra_dk_type_4 daikin_pricing", "qty_ext_dk1_4", "price_ext_dk1_4", false)
            , makeTwoInputBox("expand_ext extra_dk_type_5 daikin_pricing", "qty_ext_dk1_5", "price_ext_dk1_5", false)
            , makeTwoInputBox("expand_ext extra_dk_type_6 daikin_pricing", "qty_ext_dk1_6", "price_ext_dk1_6", false)],
        ["<button id='extra_add' class='button default'>+</button>"
            , "<input type='hidden' class='daikin_pricing' name='extra_line' id='extra_line' value='1' />"],
        ["SubTotal:"
            , makeInputBox("subtotal_dk daikin_pricing", "subtotal_dk_1", true)
            , makeInputBox("subtotal_dk daikin_pricing", "subtotal_dk_2", true)
            , makeInputBox("subtotal_dk daikin_pricing", "subtotal_dk_3", true)
            , makeInputBox("subtotal_dk daikin_pricing", "subtotal_dk_4", true)
            , makeInputBox("subtotal_dk daikin_pricing", "subtotal_dk_5", true)
            , makeInputBox("subtotal_dk daikin_pricing", "subtotal_dk_6", true)],
        ["GST:"
            , makeInputBox("gst_dk daikin_pricing", "gst_dk_1", true)
            , makeInputBox("gst_dk daikin_pricing", "gst_dk_2", true)
            , makeInputBox("gst_dk daikin_pricing", "gst_dk_3", true)
            , makeInputBox("gst_dk daikin_pricing", "gst_dk_4", true)
            , makeInputBox("gst_dk daikin_pricing", "gst_dk_5", true)
            , makeInputBox("gst_dk daikin_pricing", "gst_dk_6", true)],
        ["Grand total:"
            , makeInputBox("daikin_pricing", "grandtotal_dk_1", true)
            , makeInputBox("daikin_pricing", "grandtotal_dk_2", true)
            , makeInputBox("daikin_pricing", "grandtotal_dk_3", true)
            , makeInputBox("daikin_pricing", "grandtotal_dk_4", true)
            , makeInputBox("daikin_pricing", "grandtotal_dk_5", true)
            , makeInputBox("daikin_pricing", "grandtotal_dk_6", true)],
        ["PE Admin (%)", "<input type='number' class='daikin_pricing' name='dk_pe_admin_percent' id='dk_pe_admin_percent' value='19' />"],
    ];
    
    //  Update to set order before "Save and Generate Quote" field
    $('body').find("#generate_quote").before(daikin_pricing_table);
    $('body').find("#generate_quote").before("<button type='button' id='calculate_dk_price' class='button default' style='display: block'>Calculate Price </button>");

    makeTable(daikin_pricing_table, data, "daikin_pricing", "daikin_pricing");
    //css Table
    $(".daikin_pricing td").css({"padding":"0px 5px"});
    $(".daikin_pricing th").css({"padding":"0px 5px"});
    $(".daikin_pricing th:first-child").css({"width":"160px"});
    $(".daikin_pricing select, .daikin_pricing input[class*='daikin_pricing']:not([type='checkbox'])").css({"width":"100%"});
    $(".expand_ext").css({"width":"48%"});
    //hide line Subtotal + GST 
    $('#daikin_pricing').find('.subtotal_dk').closest('tr').hide();
    $('#daikin_pricing').find('.gst_dk').closest('tr').hide();

    // Load Daikin Option
    DK_loadOption();
    // Add Hint
    DK_initHint();
}

function alertExist(selector, num_of_line, index, value_selected, type, item_no) {
    for (let i = 1 ; i <= num_of_line; i++) {
        if (i == item_no) {
            continue;
        } else {
            if (value_selected == $(`#${selector}${i}_${index}`).val()) {
                alert(`Exist in ${type} ${i}`);
                $(`#${selector}${item_no}_${index}`).val('');
                break;
            }
        }
    }
}

function DK_initHint(){
    // Show button
    $('body').find("#generate_quote").before("<button type='button' id='show_dk_hint' class='button default' style='display: block'>Hide Calc Hint</button>");
    // Append texarea
    $('body').find("#generate_quote").before("<div id='dk_hint'>"
        +"<div id='dk_hint1' style='display: inline-block;width: 500px;'></div>"
        +"<div id='dk_hint2' style='display: inline-block;width: 500px;'></div>"
        +"</div>");
}

function DK_writeHint(key, value, number = '', isBreakLine = false, isHeader = false){
    return (isBreakLine ? '<p style="width: 400px; text-align: center;display: block;">-----------------------------------------------------------------------</p>' : '') 
        + '<'+ (isHeader ? 'h3' : 'p') +' style="width: 250px; display: inline-block;margin:0;">'
        + ' ' + (number != '' ?  `${number} x ` : '') + key 
        + '</'+ (isHeader ? 'h3' : 'p') +'><p style="width: 150px; text-align: right;display: inline-block;">' + (value != '' ? parseFloat(value).toFixed(2) : '') + '</p></br>' 
        + (isBreakLine ? '</br>' : '');
}

function DK_calcHint(){
    $('#dk_hint1').html('');
    $('#dk_hint2').html('');
    // Check index
    let index = $('input[name="daikin_option"]:checked').attr('data-attr');
    if (!index){
        $('#dk_hint1').html("You must choose the Option to see calc hint");
        alert("You must choose the Option to see calc hint");
        return;
    }
    let currState = DK_getCurrentOptionState(index);
    let str = "";
    /** ==S== HINT 1 ==== */
         /** S - Equipment Cost */ 
        let numbers_daikin =0 ,main_cost = 0, delivery_cost = 0,install_cost = 0, extra_cost = 0, wifi_cost = 0;
        let num_of_line = DK_getCountLine('main');
        for (var i = 0; i < num_of_line; i++) {
            if(currState['main_type' + (i + 1)] != '' && currState['qty_main_dk'+(i+1)] != ''){
                main_cost += parseFloat(getAttributeFromName(currState['main_type' + (i + 1)], dk_main, "cost")) * parseFloat(currState['qty_main_dk'+(i+1)]);
                str += DK_writeHint(currState['main_type' + (i + 1)], parseFloat(getAttributeFromName(currState['main_type' + (i + 1)], dk_main, "cost")) * parseFloat(currState['qty_main_dk'+(i+1)]), parseFloat(currState['qty_main_dk'+(i+1)]));
                numbers_daikin += parseFloat(currState['qty_main_dk'+(i+1)]);
            }
        }

        // Daikin Wifi
        num_of_line = DK_getCountLine('wifi');
        for (var i = 0; i < num_of_line; i++) {
            if(currState['wifi_type' + (i + 1)] != '' && currState['number_wifi_type'+(i+1)] != ''){
                wifi_cost += parseFloat(getAttributeFromName(currState['wifi_type' + (i + 1)], dk_wifi, "cost")) * parseFloat(currState['number_wifi_type'+(i+1)]);
                str+= DK_writeHint(currState['wifi_type' + (i + 1)], parseFloat(getAttributeFromName(currState['wifi_type' + (i + 1)], dk_wifi, "cost")) * parseFloat(currState['number_wifi_type'+(i+1)]), parseFloat(currState['number_wifi_type'+(i+1)]) );
            }
        }
        // Daikin extra cost
        num_of_line = DK_getCountLine('extra');
        for (var i = 0; i < num_of_line; i++) {
            if(currState['extra_type' + (i + 1)] != '' && currState['qty_ext_dk' + (i + 1)] != '' && currState['price_ext_dk' + (i + 1)] != ''){
                extra_cost += parseFloat(currState['qty_ext_dk' + (i + 1)]) * parseFloat(currState['price_ext_dk' + (i + 1)]);
                str+= DK_writeHint(currState['extra_type' + (i + 1)], parseFloat(currState['qty_ext_dk' + (i + 1)]) * parseFloat(currState['price_ext_dk' + (i + 1)]), parseFloat(currState['qty_ext_dk' + (i + 1)]));
            }
        }
    // total equipment cost
    let equipment = main_cost + wifi_cost + extra_cost;
    str += DK_writeHint(
        "TOTAL EQUIPMENT COST"
        , equipment
        , ''
        , true
        , true
    );
    /** E - Equipment Cost */ 

    /** S - Install and Delivery */
        //Daikin delivery 
        if (currState['state'] == 'VIC') {
            delivery_cost = parseFloat(daikin_delivery.default) + parseFloat(daikin_delivery.VIC);//parseFloat(getAttributeFromName(daikin_delivery[0], '', 'cost'));
        } else {
            delivery_cost = parseFloat(daikin_delivery.default);//parseFloat(getAttributeFromName(daikin_delivery[0], '', 'cost'));
        }
        str+= DK_writeHint('Delivery',delivery_cost);
        // Daikin install
        if (currState['install_dk'] == 'Yes') {
            install_cost = parseFloat(getAttributeFromName(daikin_install[0], dk_air_install, 'cost')) * parseFloat(numbers_daikin);
            str+= DK_writeHint('Daikin Install',install_cost, numbers_daikin);
        }
        let ins_delivery = delivery_cost + install_cost;
        str += DK_writeHint(
            "TOTAL INSTALL AND DELIVERY COST"
            , ins_delivery
            , ''
            , true
            , true
        );
    /** E - Install and Delivery */

    /** S - Subtotal = Equipment + install + delivery */
        str += DK_writeHint(
            "SUBTOTAL (Equipment + Install + Delivery)"
            , (equipment + ins_delivery)
            , ''
            , true
            , true
        );
    /** E - Subtotal = Equipment + install + delivery */
    // PE Admin %
    str += DK_writeHint(
        'PE Admin %'
        , parseFloat($('#dk_pe_admin_percent').val()) / 100
    );
    // Subtotal + PE Admin
    let grandTotal = equipment + ins_delivery;
    grandTotal += grandTotal*(parseFloat($('#dk_pe_admin_percent').val()) / 100);
    str += DK_writeHint(
        'Sub total + PE Admin %'
        , grandTotal
    );
    // GST 10%
    let gst = grandTotal * 0.1;
    str += DK_writeHint(
        'GST 10%'
        , gst
    );
    // Include GST
    grandTotal += gst;
    str += DK_writeHint(
        'Grand Total inclue GST'
        , grandTotal
    );
    
    // PM price
    if(currState.pm != undefined && currState.pm != ''){
        grandTotal += parseFloat(currState.pm);
        str += DK_writeHint(
            'GrandTotal + PM'
            , grandTotal
        );
    }
    /** ==E== HINT 1 ==== */

    // /** ==================== GP calc =======================*/ 
    // let str2 = '';
    // // Sub Price Total
    // str2 += DK_writeHint(
    //     'SUB PRICE TOTAL = Sub total + PE Admin %'
    //     , sub_price_toal
    //     , true
    //     , true
    // );
    // // Customer Revenue
    // let customer_revenue = parseFloat(sub_price_toal) + parseFloat(stc_client);
    // str2 += DK_writeHint(
    //     'Customer Revenue = Sub Price Total + STCs (Client show)'
    //     , customer_revenue
    // );
    // // STCs Revenue
    // let stc_revenue = 37.25 * parseFloat(currState.number_stcs);
    // str2 += DK_writeHint(
    //     'STCs Revenue'
    //     , stc_revenue
    // );
    // // Sub Total for GP
    // let sub_total_gp = customer_revenue + stc_revenue;
    // str2 += DK_writeHint(
    //     'SUB TOTAL (Revenue) = STCs + Customer'
    //     , sub_total_gp
    //     , true
    //     , true
    // );
    // // Gross Profit
    // let gross_profit = parseFloat(sub_total_gp) - parseFloat(sub_total);
    // str2 += DK_writeHint(
    //     'Gross Profit = Sub Total (Revenue) - Sub total'
    //     , gross_profit
    // );
    // // % Gross Profit
    // let gross_profit_percent = (parseFloat(gross_profit) / parseFloat(sub_total));
    // str2 += DK_writeHint(
    //     '% Gross Profit = Gross Profit / Sub total'
    //     , gross_profit_percent
    // );
    // if (parseFloat(gross_profit_percent) < parseFloat($('#pe_admin_percent').val()) / 100) {
    //     str2 += DK_writeHint(
    //         '-> Need to Go Seek: SUB PRICE TOTAL'
    //         , ''
    //         , true
    //         , true
    //     );
    // }

    // Return
    $('#dk_hint1').append(str);
    // $('#dk_hint2').append(str2);
}

//Convert JSON to Array data
function DK_convertJSONToArrayInit(jsonData){
    let result = [''];
    if (typeof(jsonData) != 'undefined') {
        jsonData.forEach(element => {
            result.push(element.short_name);
        });
    }
    return result;
}

//Make 2 input 1 line
function makeTwoInputBox(iclass,iid, iid1, disabled = false){
    var read = disabled == false ? '' : 'disabled'
    var input = `   <input class="${iclass}" id="${iid}" ${read} style="width: 48%;" />
                    <input class="${iclass}" id="${iid1}" ${read} style="width: 48%;" />
                `;
    // var input = $("<input/>").addClass(iclass).attr("id",iid).prop('disabled', disabled).css('width', '50%');
    // var input1 = $("<input/>").addClass(iclass).attr("id",iid1).prop('disabled', disabled).css('width', '50%');
    return input;
}
//make dropdown number
function makeDropdownNumber(number, sclass, sid) {
    let i;
    var select = $("<select/>").addClass(sclass).attr("id",sid);
    select.append($('<option></option>'));
    for (i=1 ; i <= number; i++) {
        select.append( $('<option></option>').val(i).html(i) );
    }
    return select;
}

//Create new line
function DK_createNewLine(target = 'extra'){
    var label, id, list, label1, id1, id2;
    if (target == 'main') {
        label = "Daikin Type ";
        id = "main_dk_type";
        list = dk_main;
        label1 = "Number Daikin ";
        id1 = "qty_main_dk";
    } else if (target == 'wifi') {
        label = "Wifi Type ";
        id = "wifi_dk_type";
        list = dk_wifi;
        label1 = "Number Wifi ";
        id1 = "qty_wifi_dk";
    } else {
        label = "Extra ";
        id = "extra_dk_type";
        list = dk_extra;
        label1 = "Extra (number/price) ";
        id1 = "qty_ext_dk";
        id2 = "price_ext_dk";
    }
    
    let next_index = DK_getCountLine(target) + 1;
    let new_tr = document.createElement('tr');
    let new_tr1 = document.createElement('tr');
    for (var i = 0; i < 7; i++) {
        let td = document.createElement('td');
        td.style.padding = "0px 5px";
        let td1 = document.createElement('td');
        td1.style.padding = "0px 5px";
        if(i == 0){
            // First td
            td.style.width = "160px";
            td.innerHTML = label + next_index;
            td1.style.width = "160px";
            td1.innerHTML = label1 + next_index;

        } else {
            // Other td
            let input;
            let select = makeSelectBox(DK_convertJSONToArrayInit(list), `${id}_${next_index} daikin_pricing`, id + next_index + "_" + i);
            select.css({"width":"100%"});
            if (target == 'main' || target == 'wifi') {
                input = makeInputBox(`${id1}_${next_index} daikin_pricing`, `${id1}${next_index}_${i}`, false);
                input.css({"width":"100%"});
            } else {
                input = makeTwoInputBox(`${id}_${next_index} daikin_pricing`, `${id1}${next_index}_${i}`, `${id2}${next_index}_${i}`, false);
                // input.css({"width":"100%"});
            }
            $(td).html(select);
            $(td1).html(input);
        }
        new_tr.appendChild(td);
        new_tr1.appendChild(td1);
    }
    $('#'+ target +'_add').closest('tr').before(new_tr, new_tr1);
    $('#'+ target +'_line').val(next_index);
}

function DK_saveCurrentState(){
    let result = {};
    let state = $("#install_address_state_c").val();
    let check_main = {};
    $("#daikin_pricing_table .daikin_pricing").each(function (){
        let opt = {};
        let id_product = '', partNumber_product = '', name_product = '';
        var id_name = $(this).attr("id");
        let item_no = id_name.charAt(id_name.length-3);
        let option = id_name.split('_').pop();
        // if (!isNaN(option) && option > 1) {
        //     return true;
        // }
        if (isNaN(option)) {
            result[id_name] = $(this).val();
            return true;
        }
        if($("#"+id_name).attr('type')== 'checkbox'){
            opt[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
        } else {
            opt[id_name] = $(this).val();
        }

        //Main
        if (id_name.indexOf('main_dk_type') != -1 || id_name.indexOf('qty_main_dk') != -1) {
            if (!result[option].hasOwnProperty('products')) {
                result[option].products = {};
            }
            if(id_name.indexOf('main_dk_type') != -1) {
                id_product = getAttributeFromName(opt[id_name], dk_main, 'id') != '' ?  getAttributeFromName(opt[id_name], dk_main, 'id') : '';
                partNumber_product = getAttributeFromName(opt[id_name], dk_main, 'part_number') != '' ? getAttributeFromName(opt[id_name], dk_main, 'part_number') : '';
                name_product = getAttributeFromName(opt[id_name], dk_main, 'name') != '' ? getAttributeFromName(opt[id_name], dk_main, 'name') : '';
                result[option].products[item_no] = {...result[option].products[item_no], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};
            }
            if (id_name.indexOf('qty_main_dk') != -1) {
                if (!check_main.hasOwnProperty(option)) {
                    check_main[option] = 0;
                }
                check_main[option] += parseInt(opt[id_name] != '' ? opt[id_name] : 0);
            }

            result[option].products[item_no] = {...result[option].products[item_no], ...opt};
            return true;
        }
        //Wifi
        if (id_name.indexOf('wifi_dk_type') != -1 || id_name.indexOf('qty_wifi_dk') != -1) {
            if (!result[option].hasOwnProperty('wifi')) {
                result[option].wifi = {};
            }
            if(id_name.indexOf('wifi_dk_type') != -1) {
                id_product = getAttributeFromName(opt[id_name], dk_wifi, 'id') != '' ?  getAttributeFromName(opt[id_name], dk_wifi, 'id') : '';
                partNumber_product = getAttributeFromName(opt[id_name], dk_wifi, 'part_number') != '' ? getAttributeFromName(opt[id_name], dk_wifi, 'part_number') : '';
                name_product = getAttributeFromName(opt[id_name], dk_wifi, 'name') != '' ? getAttributeFromName(opt[id_name], dk_wifi, 'name') : '';
                result[option].wifi[item_no] = {...result[option].wifi[item_no], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};
            }

            result[option].wifi[item_no] = {...result[option].wifi[item_no], ...opt};
            return true;
        }
        //Extra 
        if (id_name.indexOf('extra_dk_type') != -1 || id_name.indexOf('qty_ext_dk') != -1 || id_name.indexOf('price_ext_dk') != -1) {
            if (!result[option].hasOwnProperty('extras')) {
                result[option].extras = {};
            }
            if(id_name.indexOf('extra_dk_type') != -1) {
                id_product = getAttributeFromName(opt[id_name], dk_extra, 'id') != '' ?  getAttributeFromName(opt[id_name], dk_extra, 'id') : '';
                partNumber_product = getAttributeFromName(opt[id_name], dk_extra, 'part_number') != '' ? getAttributeFromName(opt[id_name], dk_extra, 'part_number') : '';
                name_product = getAttributeFromName(opt[id_name], dk_extra, 'name') != '' ? getAttributeFromName(opt[id_name], dk_extra, 'name') : '';
                result[option].extras[item_no] = {...result[option].extras[parseInt(item_no) - 1], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};
            }
            result[option].extras[item_no] = {...result[option].extras[item_no], ...opt};
            return true;
        }

        result[option] = {...result[option], ...opt};
    });
    //check send email daikin pricing option
    for (const [k, qty] of Object.entries(check_main)) {
        if (qty > 0) {
            result[k] = {...result[k],...{'isSend': 1}};
        } else {
            result[k] = {...result[k],...{'isSend': 0}};
        }
    }
    
    //add state
    result = {...result, ...{'state': state}};
    $("#quote_note_inputs_c").val(JSON.stringify(result));
}

//Load option
function DK_loadOption(){
    if($("#quote_note_inputs_c").val() != ""){
        try{
            var json_val = JSON.parse($("#quote_note_inputs_c").val());
            // Create main line
            let current_line = DK_getCountLine('main');
            let item_line = (json_val.main_line != undefined && json_val.main_line != '') ? json_val.main_line : 1;
            if (item_line > current_line) {
                for (let i = 0; i < (item_line - current_line); i++) {
                    DK_createNewLine('main');
                }
            }

            current_line = DK_getCountLine('wifi');
            item_line = (json_val.wifi_line != undefined && json_val.wifi_line != '') ? json_val.wifi_line : 1;
            if (item_line > current_line) {
                for (let i = 0; i < (item_line - current_line); i++) {
                    DK_createNewLine('wifi');
                }
            }

            current_line = DK_getCountLine('extra');
            item_line = (json_val.extra_line != undefined && json_val.extra_line != '') ? json_val.extra_line : 1;
            if (item_line > current_line) {
                for (let i = 0; i < (item_line - current_line); i++) {
                    DK_createNewLine('extra');
                }
            }
            
            for (const [key, v] of Object.entries(json_val)) {
                if (isNaN(key)) {
                        if($("#"+key).attr('type') == 'checkbox'){
                            $("#"+key).prop( "checked", json_val[key] );
                        } else {
                            $("#"+key).val(json_val[key]);
                        }
                } else {
                    for (const [vkey, vvalue] of Object.entries(v)) {
                        if (typeof vvalue == "object") {
                            for (const [vvkey, vvvalue] of Object.entries(vvalue)) {
                                for (const [vvvkey, vvvvalue] of Object.entries(vvvalue)) {
                                    if($("#"+vvvkey).attr('type') == 'checkbox'){
                                        $("#"+vvvkey).prop( "checked", vvvvalue);
                                    } else {
                                        $("#"+vvvkey).val(vvvvalue);
                                    }
                                }
                            }
                        } else {
                            if($("#"+vkey).attr('type') == 'checkbox'){
                                $("#"+vkey).prop( "checked", vvalue);
                            } else {
                                $("#"+vkey).val(vvalue);
                            }
                        }
                    }
                }
            }
        } catch (err) {
            console.log(err);
        }
    }
}

//Get current option state
function DK_getCurrentOptionState(index){
    let result = {};
    let state = $('#install_address_state_c').val();
    result['state'] = state;
    result['index'] = index;
    result['pm'] = ($("#pmdk_"+index).val() != '') ? parseFloat($("#pmdk_"+index).val()) : '0';
    // Main line
    let num_of_line = DK_getCountLine('main');
    for (var i = 0; i < num_of_line; i++) {
        result['main_type' + (i + 1)] = $('#main_dk_type' + (i + 1) + '_' + index).val();
        result['qty_main_dk' + (i + 1)] = $('#qty_main_dk' + (i + 1) + '_' + index).val() != '' ? $('#qty_main_dk' + (i + 1) + '_' + index).val() : '0' ;
    }

    // Wifi line
    num_of_line = DK_getCountLine('wifi');
    for (var i = 0; i < num_of_line; i++) {
        result['wifi_type' + (i + 1)] = $('#wifi_dk_type' + (i + 1) + '_' + index).val();
        result['number_wifi_type' + (i + 1)] = $('#qty_wifi_dk' + (i + 1) + '_' + index).val() != '' ? $('#qty_wifi_dk' + (i + 1) + '_' + index).val() : '0' ;
    }

    result['install_dk'] = $('#install_dk_' + index).val();
    // Extra line
    num_of_line = DK_getCountLine('extra');
    for (var i = 0; i < num_of_line; i++) {
        result['extra_type' + (i + 1)] = $('#extra_dk_type' + (i + 1) + '_' + index).val();
        result['qty_ext_dk' + (i + 1)] = $('#qty_ext_dk' + (i + 1) + '_' + index).val() != '' ? $('#qty_ext_dk' + (i + 1) + '_' + index).val() : '0';
        result['price_ext_dk' + (i + 1)] = $('#price_ext_dk' + (i + 1) + '_' + index).val() != '' ? $('#price_ext_dk' + (i + 1) + '_' + index).val() : '0';
    }
    return result;
}


function DK_calcOption(index) {
    if(index != '' && index != undefined){
        let currState = DK_getCurrentOptionState(index);
        // Grand Total
        let grandTotal = DK_calcGrandTotal(currState);
        let grandTotalR90 = Number(roundTo90(grandTotal));
        let subtotal = Number(parseFloat(grandTotalR90/1.1).toFixed(2));
        let gst = Number(parseFloat(grandTotalR90 - subtotal).toFixed(2));
        //fill 
        $("#subtotal_dk_"+index).val(parseFloat(subtotal).formatMoney(2, ',', '.'));
        $("#gst_dk_"+index).val(parseFloat(gst).formatMoney(2, ',', '.'));
        $("#grandtotal_dk_"+index).val(parseFloat(grandTotalR90).formatMoney(2, ',', '.'));
        // $("#grandtotal_dk_"+index).val(parseFloat(roundTo90(grandTotal)).formatMoney(2, ',', '.'));
        // Save current option
        DK_saveCurrentState();
    }
}

//
function DK_clearOption(option){
    $("#daikin_option_"+(option)).prop('checked', false);
    $('#daikin_pricing_table td:nth-child('+ (option + 1) +') input:not(input[id="dk_pe_admin_percent"], input[id="main_line"],input[id="wifi_line"], input[id="extra_line"])').val('');
    $('#daikin_pricing_table td:nth-child('+ (option + 1) +')').find('select').prop("selectedIndex", 0);
}

function DK_getCountLine(target){
    return parseInt($('#'+ target +'_line').val());
}

function DK_calcInstallCost(currState) {
    let num_of_line = DK_getCountLine('main');
    let numbers_daikin = 0, install_cost = 0;
    for (var i = 0; i < num_of_line; i++) {
        if(currState['main_type' + (i + 1)] != '' && currState['qty_main_dk'+(i+1)] != ''){
            numbers_daikin += parseFloat(currState['qty_main_dk'+(i+1)]);
        }
    }
    // Daikin install
    if (currState['install_dk'] == 'Yes') {
        install_cost = parseFloat(getAttributeFromName(daikin_install[0], dk_air_install, 'cost')) * parseFloat(numbers_daikin);
    }
    return install_cost;
}

function DK_calcDeliveryCost(currState) {
    let delivery_cost = 0;
    //Daikin delivery 
    if (currState['state'] == 'VIC') {
        delivery_cost = parseFloat(daikin_delivery.default) + parseFloat(daikin_delivery.VIC);//parseFloat(getAttributeFromName(daikin_delivery[0], '', 'cost'));
    } else {
        delivery_cost = parseFloat(daikin_delivery.default);//parseFloat(getAttributeFromName(daikin_delivery[0], '', 'cost'));
    }
    return delivery_cost;
}

function DK_calcEquipmentCost(currState){
    let main_cost = 0, extra_cost = 0, wifi_cost = 0, total_cool_capacity = 0, total_heat_capacity = 0;
    // Daikin main cost
    let num_of_line = DK_getCountLine('main');
    for (var i = 0; i < num_of_line; i++) {
        if(currState['main_type' + (i + 1)] != '' && currState['qty_main_dk'+(i+1)] != ''){
            main_cost += parseFloat(getAttributeFromName(currState['main_type' + (i + 1)], dk_main, "cost")) * parseFloat(currState['qty_main_dk'+(i+1)]);
            // numbers_daikin += parseFloat(currState['qty_main_dk'+(i+1)]);
            total_cool_capacity += parseFloat(getAttributeFromName(currState['main_type' + (i + 1)], dk_main, "cool_capacity")) * parseFloat(currState['qty_main_dk'+(i+1)]);
            total_heat_capacity += parseFloat(getAttributeFromName(currState['main_type' + (i + 1)], dk_main, "heat_capacity")) * parseFloat(currState['qty_main_dk'+(i+1)]);
        }
    }
    // //Daikin delivery 
    // if (currState['state'] == 'VIC') {
    //     delivery_cost = parseFloat(daikin_delivery.default) + parseFloat(daikin_delivery.VIC);//parseFloat(getAttributeFromName(daikin_delivery[0], '', 'cost'));
    // } else {
    //     delivery_cost = parseFloat(daikin_delivery.default);//parseFloat(getAttributeFromName(daikin_delivery[0], '', 'cost'));
    // }
    // // Daikin install
    // if (currState['install_dk'] == 'Yes') {
    //     install_cost = parseFloat(getAttributeFromName(daikin_install[0], dk_air_install, 'cost')) * parseFloat(numbers_daikin);
    // }

    // Daikin Wifi
    num_of_line = DK_getCountLine('wifi');
    for (var i = 0; i < num_of_line; i++) {
        if(currState['wifi_type' + (i + 1)] != '' && currState['number_wifi_type'+(i+1)] != ''){
            wifi_cost += parseFloat(getAttributeFromName(currState['wifi_type' + (i + 1)], dk_wifi, "cost")) * parseFloat(currState['number_wifi_type'+(i+1)]);
        }
    }
    // Daikin extra cost
    num_of_line = DK_getCountLine('extra');
    for (var i = 0; i < num_of_line; i++) {
        if(currState['extra_type' + (i + 1)] != '' && currState['qty_ext_dk' + (i + 1)] != '' && currState['price_ext_dk' + (i + 1)] != ''){
            extra_cost += parseFloat(currState['qty_ext_dk' + (i + 1)]) * parseFloat(currState['price_ext_dk' + (i + 1)]);
        }
    }
    //fill cool/heat capacity
    $(`#total_cooling_capacity_${currState['index']}`).val(total_cool_capacity.formatMoney(2, ',', '.'));
    $(`#total_heating_capacity_${currState['index']}`).val(total_heat_capacity.formatMoney(2, ',', '.'));

    return main_cost + wifi_cost + extra_cost;
}

function DK_calcGrandTotal(currState){
    let grandTotal = 0;
    // Equipment cost
    grandTotal += DK_calcEquipmentCost(currState);
    // Install + Delivery cost
    grandTotal += DK_calcInstallCost(currState) + DK_calcDeliveryCost(currState);
    // PE Admin %
    grandTotal += grandTotal * (parseFloat($('#dk_pe_admin_percent').val()) / 100);
    // GST 10%
    let gst = grandTotal * 0.1;
    // Include GST above
    grandTotal += gst;
     // PM
     if (currState.pm != undefined && currState.pm != '') {
        grandTotal += parseFloat(currState.pm);
     }

    return grandTotal;
}

async function DK_autoCreateLineItem(shortName, target, total_item, price_item = ''){
    var info_pro = getItemFromName(shortName, target);
    if(info_pro['id'] !== undefined){
        insertProductLine('product_group0', '0');
        lineno  = prodln-1;
        var popupReplyData = {};
        popupReplyData.form_name = "EditView";
        var name_to_value_array = {};
        name_to_value_array["product_currency"+lineno] = info_pro['currency'];
        name_to_value_array["product_item_description"+lineno] = (info_pro['description'] == null) ? '' : info_pro['description'];
        name_to_value_array["product_name"+lineno] = info_pro['name'];
        name_to_value_array["product_part_number"+lineno] =  info_pro['part_number'];
        name_to_value_array["product_product_id"+lineno] = info_pro['id'];
        if (price_item != '') { //User enter price extra
            name_to_value_array["product_product_cost_price"+lineno] = price_item;
            name_to_value_array["product_product_list_price"+lineno] = "" + price_item;
        } else {
            name_to_value_array["product_product_cost_price"+lineno] = info_pro['cost'];
            name_to_value_array["product_product_list_price"+lineno] = "" + info_pro['cost'];
        }
        name_to_value_array["product_product_qty"+lineno] = "" + parseFloat(total_item);
        popupReplyData["name_to_value_array"] = name_to_value_array;            
        $('#product_product_list_price'+lineno).focus();
        $('#product_product_id'+lineno).after('<div style="position: absolute;"><a class="product_link" target="_blank" href="/index.php?module=AOS_Products&action=EditView&record='+ info_pro['id'] +'">Link</a></div>');
        setProductReturn(popupReplyData);
    }
}

async function DK_generateLineItem(){
    // Save current state
    DK_saveCurrentState();

    // Get Option Quote
    let index = $('input[name="daikin_option"]:checked').attr('data-attr');
    if (!index){
        alert("You must choose the Option to generate line item");
        return;
    }

    // Mark line deleted
    for (var i = 0; i < prodln; i++){
        markLineDeleted(i,"product_");
    }

    // Check exist any line item
    if ($("#lineItems").find(".group_body").length == 0){
        insertGroup(0);
    } else {
        $("#group_body"+($("#lineItems").find(".group_body").length -1)).show();
    }
    // $("#group0name").val("Off-Grid Option " + index);

    // Get option

    await wait(300);
    // let json_val = JSON.parse($("#quote_note_inputs_c").val());
    // let currState = json_val[index];
    let currState = DK_getCurrentOptionState(index);

    // // Validate current State
    // if (currState.offgrid_howmany == '') {
    //     alert("You must enter the number of Battery!");
    //     return;
    // }
    // Create line item
    try{
        // Show loading
        SUGAR.ajaxUI.showLoadingPanel();
        //debugger
        if (currState['install_dk'] == 'Yes') {
            await DK_autoCreateLineItem(daikin_ds[1], dk_install, 1);
            await DK_autoCreateLineItem(daikin_install[0], dk_air_install, 1);
        } else {
            await DK_autoCreateLineItem(daikin_ds[0], dk_install, 1);
        }
        // Main line
        let num_of_line = getCountLine('main');
        for (let i = 0; i < num_of_line; i++) {
            if (currState['main_type' + (i + 1)] != "" && parseInt(currState['qty_main_dk' + (i + 1)]) != 0) {
                await DK_autoCreateLineItem(currState['main_type' + (i + 1)], dk_main, currState['qty_main_dk' + (i + 1)]);
            }
        }
        // wifi line
        num_of_line = getCountLine('wifi');
        for (let i = 0; i < num_of_line; i++) {
            if (currState['wifi_type' + (i + 1)] != "" &&  parseInt(currState['number_wifi_type' + (i + 1)]) != 0) {
                await DK_autoCreateLineItem(currState['wifi_type' + (i + 1)], dk_wifi, currState['number_wifi_type' + (i + 1)]);
            }
        }
        // wifi line
        num_of_line = getCountLine('extra');
        for (let i = 0; i < num_of_line; i++) {
            if (currState['extra_type' + (i + 1)] != "" &&  parseInt(currState['qty_ext_dk' + (i + 1)]) != 0) {
                if (parseInt(currState['price_ext_dk' + (i + 1)]) != 0) {
                    await DK_autoCreateLineItem(currState['extra_type' + (i + 1)], dk_extra, currState['qty_ext_dk' + (i + 1)], currState['price_ext_dk' + (i + 1)]);
                } else {
                    await DK_autoCreateLineItem(currState['extra_type' + (i + 1)], dk_extra, currState['qty_ext_dk' + (i + 1)]);
                }
            }
        }
        // Calculate
        await DK_calculatePrice(currState);

        // Calc Equipment Cost
        let equipmentCost = DK_calcEquipmentCost(currState);
        $('#sanden_supply_bill').val(parseFloat(equipmentCost).formatMoney(2, ',', '.'));
        $('#sanden_supply_bill').trigger('change');
        // Calc Installation Cost
        let installationCost = DK_calcInstallCost(currState);
        $('#electrician_bill').val(parseFloat(installationCost).formatMoney(2, ',', '.'));
        $('#electrician_bill').trigger('change');
        // Calc Delivery Cost
        let deliveryCost = DK_calcDeliveryCost(currState);
        $('#sanden_shipping_bill').val(parseFloat(deliveryCost).formatMoney(2, ',', '.'));
        $('#sanden_shipping_bill').trigger('change');
        
    } catch(err) {
        console.log(err);
    } finally {
        // Hide loading
        setTimeout(function (){
            autoSaveData();
        }, 300);
    }
}

async function DK_calculatePrice(currState = {}){
    let productVisible = $('.product_group').find('tbody[id*=product_body]:visible');
    var totalList = 0, totalDiscount = 0, totalAmount = 0;
    var list, dis, amount, tax;
    let grandTotal = parseFloat(roundTo90(DK_calcGrandTotal(currState))).formatMoney(2, ',', '.')
    // For each
    productVisible.each((index, el) => {
        // // get target
        list = $(el).find('input[id*=product_product_list_price]');
        // dis = $(el).find('input[id*=product_product_discount]');
        // amount = $(el).find('input[id*=product_product_total_price]');
        tax = $(el).find('select[id*=product_vat]');

        if(index !== 0 && index < productVisible.length){
            // // calculate line item exclude first line and last line
            // totalList += get_value(list.attr('id'));
            // totalDiscount += get_value(dis.attr('id'));
            // totalAmount += get_value(amount.attr('id'));
            set_value(list.attr('id'), "");
            $(tax).val('0.0');
        }
        // blur
        list.trigger("blur");
    });
    await wait(200);
    $("#total_amount").trigger("focusin");
    
    // // PE Admin
    // totalAmount += totalAmount * (parseFloat($('#dk_pe_admin_percent').val()) / 100);

    // // PM price
    // if(currState.pm != undefined && currState.pm != ''){
    //     totalAmount += parseFloat(currState.pm);
    // }

    // Set value to first line
    list = $(productVisible[0]).find('input[id*=product_product_list_price]');
    set_value(list.attr('id'), parseFloat(grandTotal));
    list.trigger("blur");

    // Set value to grand total
    $("#total_amount").trigger("focusin");
    $("#total_amount").val(grandTotal);
    $("#total_amount").trigger("change");

    // Scroll top offset
    jQuery('html,body').animate({scrollTop: jQuery('.panel-heading a div:contains(Line Items)').offset().top - 200}, 500);
}


//***************************************** ??? *********************************************************** */
