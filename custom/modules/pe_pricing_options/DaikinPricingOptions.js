var dk_main,dk_extra, dk_install, dk_wifi;
const daikin_delivery = {
    'VIC' : '90',
    'default' : '40',
}; //["Daikin Factory Delivery"]; //default = 40
const daikin_install = ["Daikin Standard Install"];

$(function () {
    'use strict';
    //INIT
    // $(document).find('#own_solar_pv_pricing_c').attr('readonly', 'readonly');
    // var quote_type = $("#quote_type_c").val();
    // if(quote_type == 'quote_type_daikin'){
    //     init_table_daikin();
    //     // $("#quote_note_inputs_c").closest('.edit-view-row-item').show();
    //     $(document).find('#generate_quote').show();
    // }

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

    /** Clear sl button */
    $(document).on('click', '*[id*="clear_dk_option"]', function(e){
        e.preventDefault();
        DK_clearOption($(this).data('option'));
    });

    // $(document).on("change", "select[id*='extra_dk_type'], select[id*='main_dk_type'], input[id*='total_dk_type'], input[id*='pmdk_'], input[id*='total_dk_wifi_'], select[id*='install_dk_'], input[id*='ext_dk_no'], input[id*='ext_dk_val'], select[id*='wifi_dk_type'], input[id*='number_wifi_dk_type']", function(e){
    //     var index  = $(this).attr("id").split('_');
    //     index = index[index.length -1];
    //         DK_calcOption(index);
    // });

    //************************************************ END THIENPB ************************************************ */

    // // .:nhantv:. Add a checkbox to Itemise in LINE ITEMS
    // $('input[name="dk_quote_option"]').on('change', function() {
    //     // Set change
    //     let dataAttr = $(this).attr('data-attr');
    //     let propVal = $(this).prop('checked');
    //     if(propVal){
    //         $('input[name="dk_quote_option"]').each(function(){
    //             $(this).attr('data-attr') && $(this).attr('data-attr') === dataAttr
    //             ? $(this).prop('checked', propVal) 
    //             : $(this).prop('checked', !propVal);
    //         })
    //     } else {
    //         // Mark line deleted
    //         for (var i = 0; i < prodln; i++){
    //             markLineDeleted(i,"product_");
    //         };
    //         // Delete group
    //         $("#lineItems").find(".group_body").each((index) => {
    //             markGroupDeleted(index);
    //         });
    //     }
    //     generateJSONForInput();
    // });
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
        });
    } catch (ex) {
        console.log(ex);
    }

    let daikin_pricing_table   = $('<div id="daikin_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row" style="margin-bottom: 20px;"></div>');
    let data = [
        ["Option"
            ,"1"
            ,"2"
            ,"3"
            ,"4"
            ,"5"
            ,"6"],
        [""
            , "<button data-option ='1' id='clear_dk_option_1' class='button default'>Clear Option 1</button>"
            , "<button data-option ='2' id='clear_dk_option_2' class='button default'>Clear Option 2</button>"
            , "<button data-option ='3' id='clear_dk_option_3' class='button default'>Clear Option 3</button>"
            , "<button data-option ='4' id='clear_dk_option_4' class='button default'>Clear Option 4</button>"
            , "<button data-option ='5' id='clear_dk_option_5' class='button default'>Clear Option 5</button>"
            , "<button data-option ='6' id='clear_dk_option_6' class='button default'>Clear Option 6</button>"],
        ["PM:"
            , makeInputBox("pmdk_1 daikin_pricing_input", "pmdk_1", false)
            , makeInputBox("pmdk_2 daikin_pricing_input", "pmdk_2", false)
            , makeInputBox("pmdk_3 daikin_pricing_input", "pmdk_3", false)
            , makeInputBox("pmdk_4 daikin_pricing_input", "pmdk_4", false)
            , makeInputBox("pmdk_5 daikin_pricing_input", "pmdk_5", false)
            , makeInputBox("pmdk_6 daikin_pricing_input", "pmdk_6", false)],
        ["Daikin Type 1"
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_1 daikin_pricing_input", "main_dk_type1_1") 
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_2 daikin_pricing_input", "main_dk_type1_2")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_3 daikin_pricing_input", "main_dk_type1_3")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_4 daikin_pricing_input", "main_dk_type1_4")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_5 daikin_pricing_input", "main_dk_type1_5")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_main), "main_dk_type_6 daikin_pricing_input", "main_dk_type1_6")],
        ["Number Daikin 1"
            , makeInputBox("total_dk_type_1 daikin_pricing_input", "total_dk_type1_1", false)
            , makeInputBox("total_dk_type_2 daikin_pricing_input", "total_dk_type1_2", false)
            , makeInputBox("total_dk_type_3 daikin_pricing_input", "total_dk_type1_3", false)
            , makeInputBox("total_dk_type_4 daikin_pricing_input", "total_dk_type1_4", false)
            , makeInputBox("total_dk_type_5 daikin_pricing_input", "total_dk_type1_5", false)
            , makeInputBox("total_dk_type_6 daikin_pricing_input", "total_dk_type1_6", false)],
        ["<button id='main_add' class='button default'>+</button>"
            , "<input type='hidden' class='daikin_pricing_input' name='main_line' id='main_line' value='1' />"],
        // ["Daikin Wifi"
        //     , makeInputBox("total_dk_wifi_1 daikin_pricing", "total_dk_wifi_1", false)
        //     , makeInputBox("total_dk_wifi_2 daikin_pricing", "total_dk_wifi_2", false)
        //     , makeInputBox("total_dk_wifi_3 daikin_pricing", "total_dk_wifi_3", false)
        //     , makeInputBox("total_dk_wifi_4 daikin_pricing", "total_dk_wifi_4", false)
        //     , makeInputBox("total_dk_wifi_5 daikin_pricing", "total_dk_wifi_5", false)
        //     , makeInputBox("total_dk_wifi_6 daikin_pricing", "total_dk_wifi_6", false)],
        ["Wifi Type 1"
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_1 daikin_pricing_input", "wifi_dk_type1_1")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_2 daikin_pricing_input", "wifi_dk_type1_2")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_3 daikin_pricing_input", "wifi_dk_type1_3")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_4 daikin_pricing_input", "wifi_dk_type1_4")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_5 daikin_pricing_input", "wifi_dk_type1_5")
            , makeSelectBox(DK_convertJSONToArrayInit(dk_wifi), "wifi_dk_type_6 daikin_pricing_input", "wifi_dk_type1_6")],
        ["Number Wifi 1"
            , makeInputBox("number_wifi_dk_type_1 daikin_pricing_input", "number_wifi_dk_type1_1", false)
            , makeInputBox("number_wifi_dk_type_2 daikin_pricing_input", "number_wifi_dk_type1_2", false)
            , makeInputBox("number_wifi_dk_type_3 daikin_pricing_input", "number_wifi_dk_type1_3", false)
            , makeInputBox("number_wifi_dk_type_4 daikin_pricing_input", "number_wifi_dk_type1_4", false)
            , makeInputBox("number_wifi_dk_type_5 daikin_pricing_input", "number_wifi_dk_type1_5", false)
            , makeInputBox("number_wifi_dk_type_6 daikin_pricing_input", "number_wifi_dk_type1_6", false)],
        ["<button id='wifi_add' class='button default'>+</button>"
            , "<input type='hidden' class='daikin_pricing_input' name='wifi_line' id='wifi_line' value='1' />"],
        ["Daikin Install"
            , makeSelectBox(['Yes', 'No'], "install_dk_1 daikin_pricing_input", "install_dk_1")
            , makeSelectBox(['Yes', 'No'], "install_dk_2 daikin_pricing_input", "install_dk_2")
            , makeSelectBox(['Yes', 'No'], "install_dk_3 daikin_pricing_input", "install_dk_3")
            , makeSelectBox(['Yes', 'No'], "install_dk_4 daikin_pricing_input", "install_dk_4")
            , makeSelectBox(['Yes', 'No'], "install_dk_5 daikin_pricing_input", "install_dk_5")
            , makeSelectBox(['Yes', 'No'], "install_dk_6 daikin_pricing_input", "install_dk_6")],

        // ["", "&nbsp;"],
        // ["<button id='calculate_dk' class='button default'>Max</button>", "&nbsp;"],
        // ["", "&nbsp;"],
        // ["Extra 1"
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_1 daikin_pricing_input", "extra_dk_type1_1")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_2 daikin_pricing_input", "extra_dk_type1_2")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_3 daikin_pricing_input", "extra_dk_type1_3")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_4 daikin_pricing_input", "extra_dk_type1_4")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_5 daikin_pricing_input", "extra_dk_type1_5")
        //     , makeSelectBox(DK_convertJSONToArrayInit(dk_extra), "extra_dk_type_6 daikin_pricing_input", "extra_dk_type1_6")],
        // ["Extra (number/value) 1"
        //     , makeTwoInputBox("expand_ext extra_dk_type_1 daikin_pricing_input", "ext_dk_no1_1", "ext_dk_val1_1" ,false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_2 daikin_pricing_input", "ext_dk_no1_2", "ext_dk_val1_2", false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_3 daikin_pricing_input", "ext_dk_no1_3", "ext_dk_val1_3", false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_4 daikin_pricing_input", "ext_dk_no1_4", "ext_dk_val1_4", false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_5 daikin_pricing_input", "ext_dk_no1_5", "ext_dk_val1_5", false)
        //     , makeTwoInputBox("expand_ext extra_dk_type_6 daikin_pricing_input", "ext_dk_no1_6", "ext_dk_val1_6", false)],
        // ["<button id='extra_add' class='button default'>+</button>"
        //     , "<input type='hidden' class='daikin_pricing' name='extra_line' id='extra_line' value='1' />"],
        // ["Grand total:"
        //     , makeInputBox("daikin_pricing", "total_dk_1", true)
        //     , makeInputBox("daikin_pricing", "total_dk_2", true)
        //     , makeInputBox("daikin_pricing", "total_dk_3", true)
        //     , makeInputBox("daikin_pricing", "total_dk_4", true)
        //     , makeInputBox("daikin_pricing", "total_dk_5", true)
        //     , makeInputBox("daikin_pricing", "total_dk_6", true)],
        // ["PE Admin (%)", "<input type='number' class='daikin_pricing' name='dk_pe_admin_percent' id='dk_pe_admin_percent' value='19' />"],
    ];
    
    //  Update to set order before "Save and Generate Quote" field
    var seletor_panel_pricing_pv = '';
    $('.panel-content .panel-default').each(function(){
        var title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
        if(title_panel_default == 'pricing options'){
            seletor_panel_pricing_pv = '#' + $(this).find('.panel-body').attr('id');
        }
    })
    $(seletor_panel_pricing_pv).find(".tab-content").html(daikin_pricing_table);

    makeTable(daikin_pricing_table, data, "daikin_pricing", "daikin_pricing");
    //css Table
    $(".daikin_pricing td").css({"padding":"0px 5px"});
    $(".daikin_pricing th").css({"padding":"0px 5px"});
    $(".daikin_pricing th:first-child").css({"width":"160px"});
    $(".daikin_pricing select, .daikin_pricing input[class*='daikin_pricing_input']:not([type='checkbox'])").css({"width":"100%"});
    $(".expand_ext").css({"width":"48%"});

    $('table[id="daikin_pricing"]').after('<br><button type="button" class="button primary" id="save_options"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Save Options </button><div class="clearfix"></div>');
    $("#save_options").on("click",function(){
        save_values('daikin_pricing_table','daikin_pricing_input');
    })
    // Load Daikin Option
    DK_loadOption();
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
        id1 = "total_dk_type";
    } else if (target == 'wifi') {
        label = "Wifi Type ";
        id = "wifi_dk_type";
        list = dk_wifi;
        label1 = "Number Wifi ";
        id1 = "number_wifi_dk_type";
    } else {
        label = "Extra ";
        id = "extra_dk_type";
        list = dk_extra;
        label1 = "Extra (number/value) ";
        id1 = "ext_dk_no";
        id2 = "ext_dk_val";
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
            let select = makeSelectBox(DK_convertJSONToArrayInit(list), `${id}_${next_index} daikin_pricing_input`, id + next_index + "_" + i);
            select.css({"width":"100%"});
            if (target == 'main' || target == 'wifi') {
                input = makeInputBox(`${id1}_${next_index} daikin_pricing_input`, `${id1}${next_index}_${i}`, false);
                input.css({"width":"100%"});
            } else {
                input = makeTwoInputBox(`${id}_${next_index} daikin_pricing_input`, `${id1}${next_index}_${i}`, `${id2}${next_index}_${i}`, false);
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
    $("#daikin_pricing_table .daikin_pricing").each(function (){
        let opt = {};
        let id_product = '', partNumber_product = '', name_product = '';
        var id_name = $(this).attr("id");
        let item_no = id_name.charAt(id_name.length-3);
        let option = id_name.split('_').pop();
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
        if (id_name.indexOf('main_dk_type') != -1 || id_name.indexOf('total_dk_type') != -1) {
            if (!result[option].hasOwnProperty('products')) {
                result[option].products = {};
            }
            if(id_name.indexOf('main_dk_type') != -1) {
                id_product = getAttributeFromName(opt[id_name], dk_main, 'id') != '' ?  getAttributeFromName(opt[id_name], dk_main, 'id') : '';
                partNumber_product = getAttributeFromName(opt[id_name], dk_main, 'part_number') != '' ? getAttributeFromName(opt[id_name], dk_main, 'part_number') : '';
                name_product = getAttributeFromName(opt[id_name], dk_main, 'name') != '' ? getAttributeFromName(opt[id_name], dk_main, 'name') : '';
                result[option].products[item_no] = {...result[option].products[item_no], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};
            }

            result[option].products[item_no] = {...result[option].products[item_no], ...opt};
            return true;
        }
        //Wifi
        if (id_name.indexOf('wifi_dk_type') != -1 || id_name.indexOf('number_wifi_dk_type') != -1) {
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
        if (id_name.indexOf('extra_dk_type') != -1 || id_name.indexOf('ext_dk_no') != -1 || id_name.indexOf('ext_dk_val') != -1) {
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
    //add state
    result = {...result, ...{'state': state}};
    $("#quote_note_inputs_c").val(JSON.stringify(result));
}

//Load option
function DK_loadOption(){
    if($("#pricing_option_input_c").val() != ""){
        try{
            var json_val = JSON.parse($("#pricing_option_input_c").val());
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
// function DK_getCurrentOptionState(index){
//     let result = {};
//     let state = $('#install_address_state_c').val();
//     result['state'] = state;
//     result['pm'] = ($("#pmdk_"+index).val() != '') ? parseFloat($("#pmdk_"+index).val()) : 0;
//     // Main line
//     let num_of_line = DK_getCountLine('main');
//     for (var i = 0; i < num_of_line; i++) {
//         result['main_type' + (i + 1)] = $('#main_dk_type' + (i + 1) + '_' + index).val();
//         result['total_dk_type' + (i + 1)] = $('#total_dk_type' + (i + 1) + '_' + index).val();
//     }

//     // Wifi line
//     num_of_line = DK_getCountLine('wifi');
//     for (var i = 0; i < num_of_line; i++) {
//         result['wifi_type' + (i + 1)] = $('#wifi_dk_type' + (i + 1) + '_' + index).val();
//         result['number_wifi_type' + (i + 1)] = $('#number_wifi_dk_type' + (i + 1) + '_' + index).val();
//     }

//     // result['total_wifi'] = $('#total_dk_wifi_' + index).val();
//     result['install_dk'] = $('#install_dk_' + index).val();
//     // Extra line
//     num_of_line = DK_getCountLine('extra');
//     for (var i = 0; i < num_of_line; i++) {
//         result['extra_type' + (i + 1)] = $('#extra_dk_type' + (i + 1) + '_' + index).val();
//         result['ext_dk_no' + (i + 1)] = $('#ext_dk_no' + (i + 1) + '_' + index).val();
//         result['ext_dk_val' + (i + 1)] = $('#ext_dk_val' + (i + 1) + '_' + index).val();
//     }
//     return result;
// }


// function DK_calcOption(index, isTotalPanel = false) {
//     if(index != '' && index != undefined){
//         let currState = DK_getCurrentOptionState(index);
//         if(currState.main_type != ''){
//             // Grand Total
//             let grandTotal = DK_calcGrandTotal(currState);
//             $("#total_dk_"+index).val(parseFloat(roundTo90(grandTotal)).formatMoney(2, ',', '.'));
//             // Save current option
//             DK_saveCurrentState();

//         }
//     }
// }

//
function DK_clearOption(option){
    $("#daikin_option_"+(option)).prop('checked', false);
    $('#daikin_pricing_table td:nth-child('+ (option + 1) +')').find('input').val('');
    $('#daikin_pricing_table td:nth-child('+ (option + 1) +')').find('select').prop("selectedIndex", 0);
}

function DK_getCountLine(target){
    return parseInt($('#'+ target +'_line').val());
}


// Solar Checkbox handle 
$(document).on('change', 'input[id*="daikin_option"]', function(){
    checkBoxOptionHandle($(this), "daikin_option");
});

//***************************************** ??? *********************************************************** */
