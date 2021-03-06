/**
 * Global variables
 */
 var sanden_complete, sanden_hpump, sanden_tank, sanden_accessory, sanden_extra, sanden_install, electric_installation;
 var sanden_rebate = [], sanden_installation_extra = [];
 var sd_lineOne = ['SSI','SSPI', 'SANDEN_SUPPLY_ONLY'];
 var sd_installation_plumber = ['Sanden_Plb_Install_Std', 'Sanden_Plb_Std_New'];
 var sd_installation_electrician = ['Sanden_Elec_Install_Std'];
 var sd_delivery = ['San_Delivery', 'SANDEN_DELIVERY']; 
 var sd_Rebate = ['STCs', 'VEECs'];
 var sd_Rebate_partNumber = ['STC Rebate Certificate', 'VEEC Rebate Certificate'];
 var sd_installation_extra = ['PB', 'Photo_Upload_Bonus']; //shortname same partnumber
 var sd_delivery_state = {
     'WA' : 360,
     'default': 150,
 };

/**
 * Init table Sanden for Quote
 */
 async function init_table_sanden(module = '') {
    // Call API get Sanden Product
    try{
        //get STC - VEEC
        SD_getRebateProduct(sd_Rebate, sanden_rebate);
        //get Installer extra
        SD_getRebateProduct(sd_installation_extra, sanden_installation_extra);
        await $.ajax({
            url: '/index.php?entryPoint=APIGetSandenProduct'
        }).then(function(result) {
            let dataJSON = JSON.parse(result);
            // Set global var
            sanden_complete = dataJSON.sanden_complete;
            sanden_hpump = dataJSON.sanden_hpump;
            sanden_accessory = dataJSON.sanden_accessory;
            sanden_tank = dataJSON.sanden_tank;
            sanden_extra = dataJSON.sanden_extra;
            sanden_install = dataJSON.sanden_install; // include delivery
            electric_installation = dataJSON.electric_installation; 
        });
    } catch (ex) {
        console.log(ex);
    }

    let sanden_pricing_table   = $('<div id="sanden_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row" style="margin-bottom: 20px;"></div>');
    let data = [
        ["Selected Option"
            ,"<input data-attr='1' type='checkbox' class='sanden_option sanden_pricing' name='sanden_option' id='sanden_option_1' style='margin-bottom:5px'> Option 1"
            ,"<input data-attr='2' type='checkbox' class='sanden_option sanden_pricing' name='sanden_option' id='sanden_option_2' style='margin-bottom:5px'> Option 2"
            ,"<input data-attr='3' type='checkbox' class='sanden_option sanden_pricing' name='sanden_option' id='sanden_option_3' style='margin-bottom:5px'> Option 3"
            ,"<input data-attr='4' type='checkbox' class='sanden_option sanden_pricing' name='sanden_option' id='sanden_option_4' style='margin-bottom:5px'> Option 4"
            ,"<input data-attr='5' type='checkbox' class='sanden_option sanden_pricing' name='sanden_option' id='sanden_option_5' style='margin-bottom:5px'> Option 5"
            ,"<input data-attr='6' type='checkbox' class='sanden_option sanden_pricing' name='sanden_option' id='sanden_option_6' style='margin-bottom:5px'> Option 6"],
        ["Recommended Option"
            ,"<input data-attr='1' type='checkbox' class='recom_sd_option sanden_pricing' name='recom_sd_option' id='recom_sd_option_1' style='margin-bottom:5px'>"
            ,"<input data-attr='2' type='checkbox' class='recom_sd_option sanden_pricing' name='recom_sd_option' id='recom_sd_option_2' style='margin-bottom:5px'>"
            ,"<input data-attr='3' type='checkbox' class='recom_sd_option sanden_pricing' name='recom_sd_option' id='recom_sd_option_3' style='margin-bottom:5px'>"
            ,"<input data-attr='4' type='checkbox' class='recom_sd_option sanden_pricing' name='recom_sd_option' id='recom_sd_option_4' style='margin-bottom:5px'>"
            ,"<input data-attr='5' type='checkbox' class='recom_sd_option sanden_pricing' name='recom_sd_option' id='recom_sd_option_5' style='margin-bottom:5px'>"
            ,"<input data-attr='6' type='checkbox' class='recom_sd_option sanden_pricing' name='recom_sd_option' id='recom_sd_option_6' style='margin-bottom:5px'>"],
        [""
            , "<button data-option ='1' id='sd_clear_option_1' class='button default'>Clear Option 1</button>"
            , "<button data-option ='2' id='sd_clear_option_2' class='button default'>Clear Option 2</button>"
            , "<button data-option ='3' id='sd_clear_option_3' class='button default'>Clear Option 3</button>"
            , "<button data-option ='4' id='sd_clear_option_4' class='button default'>Clear Option 4</button>"
            , "<button data-option ='5' id='sd_clear_option_5' class='button default'>Clear Option 5</button>"
            , "<button data-option ='6' id='sd_clear_option_6' class='button default'>Clear Option 6</button>"],
        ["PM:"
            , makeInputBox("pmsd_1 sanden_pricing", "pmsd_1", false)
            , makeInputBox("pmsd_2 sanden_pricing", "pmsd_2", false)
            , makeInputBox("pmsd_3 sanden_pricing", "pmsd_3", false)
            , makeInputBox("pmsd_4 sanden_pricing", "pmsd_4", false)
            , makeInputBox("pmsd_5 sanden_pricing", "pmsd_5", false)
            , makeInputBox("pmsd_6 sanden_pricing", "pmsd_6", false)],
        ["<strong>Complete System</strong>"
            ,""
            ,""
            ,""
            ,""
            ,""
            ,""],
        ["Sanden Type 1"
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_complete), "sd_complete_type1_1 sanden_pricing", "sd_complete_type1_1") 
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_complete), "sd_complete_type1_2 sanden_pricing", "sd_complete_type1_2")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_complete), "sd_complete_type1_3 sanden_pricing", "sd_complete_type1_3")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_complete), "sd_complete_type1_4 sanden_pricing", "sd_complete_type1_4")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_complete), "sd_complete_type1_5 sanden_pricing", "sd_complete_type1_5")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_complete), "sd_complete_type1_6 sanden_pricing", "sd_complete_type1_6")],
        ["Number Sanden 1"
            , makeInputBox("qty_sd_complete1_1 sanden_pricing", "qty_sd_complete1_1", false)
            , makeInputBox("qty_sd_complete1_2 sanden_pricing", "qty_sd_complete1_2", false)
            , makeInputBox("qty_sd_complete1_3 sanden_pricing", "qty_sd_complete1_3", false)
            , makeInputBox("qty_sd_complete1_4 sanden_pricing", "qty_sd_complete1_4", false)
            , makeInputBox("qty_sd_complete1_5 sanden_pricing", "qty_sd_complete1_5", false)
            , makeInputBox("qty_sd_complete1_6 sanden_pricing", "qty_sd_complete1_6", false)],
        ["<button type='button' id='sd_complete_add' class='button default'>+</button>"
            , "<input type='hidden' class='sanden_pricing' name='sd_complete_line' id='sd_complete_line' value='1' />"],
        ["", "&nbsp;"],
        ["STC:"
            , makeInputBox("sd_stc sanden_pricing", "sd_stc_1", false)
            , makeInputBox("sd_stc sanden_pricing", "sd_stc_2", false)
            , makeInputBox("sd_stc sanden_pricing", "sd_stc_3", false)
            , makeInputBox("sd_stc sanden_pricing", "sd_stc_4", false)
            , makeInputBox("sd_stc sanden_pricing", "sd_stc_5", false)
            , makeInputBox("sd_stc sanden_pricing", "sd_stc_6", false)],
        ["VEEC:"
            , makeInputBox("sd_veec sanden_pricing", "sd_veec_1", false)
            , makeInputBox("sd_veec sanden_pricing", "sd_veec_2", false)
            , makeInputBox("sd_veec sanden_pricing", "sd_veec_3", false)
            , makeInputBox("sd_veec sanden_pricing", "sd_veec_4", false)
            , makeInputBox("sd_veec sanden_pricing", "sd_veec_5", false)
            , makeInputBox("sd_veec sanden_pricing", "sd_veec_6", false)],
        ["<button type='button' id='get_stc_veec' class='button default'>Get STC/VEEC</button>"],
        // ["<strong>Separated System</strong>"
        //     ,""
        //     ,""
        //     ,""
        //     ,""
        //     ,""
        //     ,""],
        // ["Heat Pump"
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_hpump), "sd_hpump_type1_1 sanden_pricing", "sd_hpump_type1_1")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_hpump), "sd_hpump_type1_2 sanden_pricing", "sd_hpump_type1_2")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_hpump), "sd_hpump_type1_3 sanden_pricing", "sd_hpump_type1_3")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_hpump), "sd_hpump_type1_4 sanden_pricing", "sd_hpump_type1_4")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_hpump), "sd_hpump_type1_5 sanden_pricing", "sd_hpump_type1_5")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_hpump), "sd_hpump_type1_6 sanden_pricing", "sd_hpump_type1_6")],
        // ["Number Heat Pump"
        //     , makeInputBox("qty_sd_hpump1_1 sanden_pricing", "qty_sd_hpump1_1", false)
        //     , makeInputBox("qty_sd_hpump1_2 sanden_pricing", "qty_sd_hpump1_2", false)
        //     , makeInputBox("qty_sd_hpump1_3 sanden_pricing", "qty_sd_hpump1_3", false)
        //     , makeInputBox("qty_sd_hpump1_4 sanden_pricing", "qty_sd_hpump1_4", false)
        //     , makeInputBox("qty_sd_hpump1_5 sanden_pricing", "qty_sd_hpump1_5", false)
        //     , makeInputBox("qty_sd_hpump1_6 sanden_pricing", "qty_sd_hpump1_6", false)],
        // // ["<button type='button' id='sd_hpump_add' class='button default'>+</button>"
        // //     , "<input type='hidden' class='sanden_pricing' name='sd_hpump_line' id='sd_hpump_line' value='1' />"],
        // ["Tank 1"
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_tank), "sd_tank_type1_1 sanden_pricing", "sd_tank_type1_1")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_tank), "sd_tank_type1_2 sanden_pricing", "sd_tank_type1_2")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_tank), "sd_tank_type1_3 sanden_pricing", "sd_tank_type1_3")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_tank), "sd_tank_type1_4 sanden_pricing", "sd_tank_type1_4")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_tank), "sd_tank_type1_5 sanden_pricing", "sd_tank_type1_5")
        //     , makeSelectBox(SD_convertJSONToArrayInit(sanden_tank), "sd_tank_type1_6 sanden_pricing", "sd_tank_type1_6")],
        // ["Number Tank 1"
        //     , makeInputBox("qty_sd_tank1_1 sanden_pricing", "qty_sd_tank1_1", false)
        //     , makeInputBox("qty_sd_tank1_2 sanden_pricing", "qty_sd_tank1_2", false)
        //     , makeInputBox("qty_sd_tank1_3 sanden_pricing", "qty_sd_tank1_3", false)
        //     , makeInputBox("qty_sd_tank1_4 sanden_pricing", "qty_sd_tank1_4", false)
        //     , makeInputBox("qty_sd_tank1_5 sanden_pricing", "qty_sd_tank1_5", false)
        //     , makeInputBox("qty_sd_tank1_6 sanden_pricing", "qty_sd_tank1_6", false)],
        // ["<button type='button' id='sd_tank_add' class='button default'>+</button>"
        //     , "<input type='hidden' class='sanden_pricing' name='sd_tank_line' id='sd_tank_line' value='1' />"],
        // ["", "&nbsp;"],
        ["Plumber Install"
            , makeSelectBox(['Yes', 'No'], "sd_install_plum sanden_pricing", "sd_install_plum_1")
            , makeSelectBox(['Yes', 'No'], "sd_install_plum sanden_pricing", "sd_install_plum_2")
            , makeSelectBox(['Yes', 'No'], "sd_install_plum sanden_pricing", "sd_install_plum_3")
            , makeSelectBox(['Yes', 'No'], "sd_install_plum sanden_pricing", "sd_install_plum_4")
            , makeSelectBox(['Yes', 'No'], "sd_install_plum sanden_pricing", "sd_install_plum_5")
            , makeSelectBox(['Yes', 'No'], "sd_install_plum sanden_pricing", "sd_install_plum_6")],
        ["Electrician Install"
            , makeSelectBox(['Yes', 'No'], "sd_install_elec sanden_pricing", "sd_install_elec_1")
            , makeSelectBox(['Yes', 'No'], "sd_install_elec sanden_pricing", "sd_install_elec_2")
            , makeSelectBox(['Yes', 'No'], "sd_install_elec sanden_pricing", "sd_install_elec_3")
            , makeSelectBox(['Yes', 'No'], "sd_install_elec sanden_pricing", "sd_install_elec_4")
            , makeSelectBox(['Yes', 'No'], "sd_install_elec sanden_pricing", "sd_install_elec_5")
            , makeSelectBox(['Yes', 'No'], "sd_install_elec sanden_pricing", "sd_install_elec_6")],
        ["Accessory 1"
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_accessory), "sd_accessory_type1_1 sanden_pricing", "sd_accessory_type1_1")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_accessory), "sd_accessory_type1_2 sanden_pricing", "sd_accessory_type1_2")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_accessory), "sd_accessory_type1_3 sanden_pricing", "sd_accessory_type1_3")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_accessory), "sd_accessory_type1_4 sanden_pricing", "sd_accessory_type1_4")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_accessory), "sd_accessory_type1_5 sanden_pricing", "sd_accessory_type1_5")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_accessory), "sd_accessory_type1_6 sanden_pricing", "sd_accessory_type1_6")],
        ["Number Accessory 1"
            , makeInputBox("qty_sd_accessory1_1 sanden_pricing", "qty_sd_accessory1_1", false)
            , makeInputBox("qty_sd_accessory1_2 sanden_pricing", "qty_sd_accessory1_2", false)
            , makeInputBox("qty_sd_accessory1_3 sanden_pricing", "qty_sd_accessory1_3", false)
            , makeInputBox("qty_sd_accessory1_4 sanden_pricing", "qty_sd_accessory1_4", false)
            , makeInputBox("qty_sd_accessory1_5 sanden_pricing", "qty_sd_accessory1_5", false)
            , makeInputBox("qty_sd_accessory1_6 sanden_pricing", "qty_sd_accessory1_6", false)],
        ["<button type='button' id='sd_accessory_add' class='button default'>+</button>"
            , "<input type='hidden' class='sanden_pricing' name='sd_accessory_line' id='sd_accessory_line' value='1' />"],

        ["Extra 1"
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_extra), "sd_extra_type1_1 sanden_pricing", "sd_extra_type1_1")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_extra), "sd_extra_type1_2 sanden_pricing", "sd_extra_type1_2")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_extra), "sd_extra_type1_3 sanden_pricing", "sd_extra_type1_3")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_extra), "sd_extra_type1_4 sanden_pricing", "sd_extra_type1_4")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_extra), "sd_extra_type1_5 sanden_pricing", "sd_extra_type1_5")
            , makeSelectBox(SD_convertJSONToArrayInit(sanden_extra), "sd_extra_type1_6 sanden_pricing", "sd_extra_type1_6")],
        ["Extra (number/price) 1"
            , makeTwoInputBox("sd_expand_ext sd_extra_type1_1 sanden_pricing", "qty_ext_sd_extra1_1", "price_ext_sd_extra1_1" ,false)
            , makeTwoInputBox("sd_expand_ext sd_extra_type1_2 sanden_pricing", "qty_ext_sd_extra1_2", "price_ext_sd_extra1_2", false)
            , makeTwoInputBox("sd_expand_ext sd_extra_type1_3 sanden_pricing", "qty_ext_sd_extra1_3", "price_ext_sd_extra1_3", false)
            , makeTwoInputBox("sd_expand_ext sd_extra_type1_4 sanden_pricing", "qty_ext_sd_extra1_4", "price_ext_sd_extra1_4", false)
            , makeTwoInputBox("sd_expand_ext sd_extra_type1_5 sanden_pricing", "qty_ext_sd_extra1_5", "price_ext_sd_extra1_5", false)
            , makeTwoInputBox("sd_expand_ext sd_extra_type1_6 sanden_pricing", "qty_ext_sd_extra1_6", "price_ext_sd_extra1_6", false)],
        ["<button type='button' id='sd_extra_add' class='button default'>+</button>"
            , "<input type='hidden' class='sanden_pricing' name='sd_extra_line' id='sd_extra_line' value='1' />"],
        ["SubTotal:"
            , makeInputBox("sd_subtotal sanden_pricing", "sd_subtotal_1", true)
            , makeInputBox("sd_subtotal sanden_pricing", "sd_subtotal_2", true)
            , makeInputBox("sd_subtotal sanden_pricing", "sd_subtotal_3", true)
            , makeInputBox("sd_subtotal sanden_pricing", "sd_subtotal_4", true)
            , makeInputBox("sd_subtotal sanden_pricing", "sd_subtotal_5", true)
            , makeInputBox("sd_subtotal sanden_pricing", "sd_subtotal_6", true)],
        ["GST:"
            , makeInputBox("sd_gst sanden_pricing", "sd_gst_1", true)
            , makeInputBox("sd_gst sanden_pricing", "sd_gst_2", true)
            , makeInputBox("sd_gst sanden_pricing", "sd_gst_3", true)
            , makeInputBox("sd_gst sanden_pricing", "sd_gst_4", true)
            , makeInputBox("sd_gst sanden_pricing", "sd_gst_5", true)
            , makeInputBox("sd_gst sanden_pricing", "sd_gst_6", true)],
        ["Rebate (stc/veec):"
            , makeInputBox("sd_rebate sanden_pricing", "sd_rebate_1", true)
            , makeInputBox("sd_rebate sanden_pricing", "sd_rebate_2", true)
            , makeInputBox("sd_rebate sanden_pricing", "sd_rebate_3", true)
            , makeInputBox("sd_rebate sanden_pricing", "sd_rebate_4", true)
            , makeInputBox("sd_rebate sanden_pricing", "sd_rebate_5", true)
            , makeInputBox("sd_rebate sanden_pricing", "sd_rebate_6", true)],
        ["Grand total:"
            , makeInputBox("sanden_pricing", "sd_grandtotal_1", true)
            , makeInputBox("sanden_pricing", "sd_grandtotal_2", true)
            , makeInputBox("sanden_pricing", "sd_grandtotal_3", true)
            , makeInputBox("sanden_pricing", "sd_grandtotal_4", true)
            , makeInputBox("sanden_pricing", "sd_grandtotal_5", true)
            , makeInputBox("sanden_pricing", "sd_grandtotal_6", true)],
        ["PE Admin (%)", "<input type='number' class='sanden_pricing' name='sd_pe_admin_percent' id='sd_pe_admin_percent' value='19' />"],
    ];
    
    //  Update to set order before "Save and Generate Quote" field    
    $("#sanden_option_c").closest('.tab-content').append(sanden_pricing_table);
    makeTable(sanden_pricing_table, data, "sanden_pricing", "sanden_pricing");
    $('body').find("#sanden_pricing_table").append("<button type='button' id='sd_calculate_price' class='button default' style='display: block'>Calculate Price </button>");
    // $('body').find("#sanden_pricing_table").before("</br><button type='button' id='sd_show_table' class='button default' style='display: block'>Show/Hide Table Sanden </button>");

    //css Table
    $(".sanden_pricing td").css({"padding":"0px 5px"});
    $(".sanden_pricing th").css({"padding":"0px 5px"});
    $(".sanden_pricing th:first-child").css({"width":"160px"});
    $(".sanden_pricing select, .sanden_pricing input[class*='sanden_pricing']:not([type='checkbox'])").css({"width":"100%"});
    $(".sd_expand_ext").css({"width":"48%"});
    // //hide line Subtotal + GST 
    // $('#sanden_pricing').find('.sd_subtotal').closest('tr').hide();
    // $('#sanden_pricing').find('.sd_gst').closest('tr').hide();

    // Load Sanden Option
    SD_loadOption();
    // // Add Hint
    SD_initHint();
    if(module == '') {
        // Show button
        $('body').find("#sd_hint").after("<button type='button' id='generate_table' class='button default' style='display: block'>Generate Pricing Options</button>");
        $('body').find('#generate_table').after($(document).find('#send_sanden_pricing').clone()); 

    } else {
        $('body').find('#get_stc_veec').closest('tr').remove();
        $('body').find('.sd_stc').closest('tr').remove();
        $('body').find('.sd_veec').closest('tr').remove();
    }
}



/**
 * Check line item exists
 */
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

/**
 * Get Attribute From partNumber of the item
 */
 function getAttributeFromPartNumber(partnumber, target, attribute){
    for (var item in target) {
        if (target[item].part_number == partnumber) {
            return target[item][attribute];
        }
    }
    return null;
}

/**
 * Get the item from partnumber
 */
 function getItemFromPartNumber(partnumber, target){
    for (var item in target) {
        if (target[item].part_number == partnumber) {
            return target[item];
        }
    }
    return null;
}

/**
 * Init Hint Calc 
 */
 function SD_initHint(){
    // Show button
    $('body').find("#sd_calculate_price").after("<button type='button' id='sd_show_hint' class='button default' style='display: block'>Hide Calc Hint</button>");
    // Append texarea
    $('body').find("#sd_show_hint").after("<div id='sd_hint'>"
        +"<div id='sd_hint1' style='display: inline-block;width: 500px;'></div>"
        +"<div id='sd_hint2' style='display: inline-block;width: 500px;'></div>"
        +"</div>");
    // // Show button
    // $('body').find("#sd_hint").after("<button type='button' id='generate_table' class='button default' style='display: block'>Generate From Table</button>");
}

/**
 * write Hint
 */
 function SD_writeHint(key, value, number = '', isBreakLine = false, isHeader = false){
    return (isBreakLine ? '<p style="width: 400px; text-align: center;display: block;">-----------------------------------------------------------------------</p>' : '') 
        + '<'+ (isHeader ? 'h3' : 'p') +' style="width: 250px; display: inline-block;margin:0;">'
        + ' ' + (number != '' ?  `${number} x ` : '') + key 
        + '</'+ (isHeader ? 'h3' : 'p') +'><p style="width: 150px; text-align: right;display: inline-block;">' + (value != '' ? parseFloat(value).toFixed(2) : '') + '</p></br>' 
        + (isBreakLine ? '</br>' : '');
}

/**
 * Convert JSON to Array data
 */
 function SD_convertJSONToArrayInit(jsonData){
    let result = [''];
    if (typeof(jsonData) != 'undefined') {
        jsonData.forEach(element => {
            result.push(element.part_number);
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
    return input;
}

//Create new line
function SD_createNewLine(target = 'sd_complete'){
    var label, id, list, label1, id1, id2;
    switch (target) {
        case 'sd_complete':
            label = "Sanden Type ";
            id = "sd_complete_type";
            list = sanden_complete;
            label1 = "Number Sanden ";
            id1 = "qty_sd_complete";
            break;
        case 'sd_tank':
            label = "Tank ";
            id = "sd_tank_type";
            list = sanden_tank;
            label1 = "Number Tank ";
            id1 = "qty_sd_tank";
            break;
        case 'sd_accessory':
            label = "Accessory ";
            id = "sd_accessory_type";
            list = sanden_accessory;
            label1 = "Number Accessory ";
            id1 = "qty_sd_accessory";
            break;
        case 'sd_extra':
            label = "Extra ";
            id = "sd_extra_type";
            list = sanden_extra;
            label1 = "Extra (number/price) ";
            id1 = "qty_ext_sd_extra";
            id2 = "price_ext_sd_extra";
            break;
        default:
            break;    
    }
    
    let next_index = SD_getCountLine(target) + 1;
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
            let select = makeSelectBox(SD_convertJSONToArrayInit(list), `${id}${next_index}_${i} sanden_pricing`, id + next_index + "_" + i);
            select.css({"width":"100%"});
            if (target != 'sd_extra') {
                input = makeInputBox(`${id1}${next_index}_${i} sanden_pricing`, `${id1}${next_index}_${i}`, false);
                input.css({"width":"100%"});
            } else {
                input = makeTwoInputBox(`${id}${next_index}_${i} sanden_pricing`, `${id1}${next_index}_${i}`, `${id2}${next_index}_${i}`, false);
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

function SD_saveCurrentState(){
    let result = {};
    let state = $("#install_address_state_c").val();
    let postcode = $("#install_address_postalcode_c").val();
    let check_main = {};
    $("#sanden_pricing_table .sanden_pricing").each(function (){
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
        if (id_name.indexOf('sd_complete_type') != -1 || id_name.indexOf('qty_sd_complete') != -1) {
            if (!result[option].hasOwnProperty('completes')) {
                result[option].completes = {};
            }
            if(id_name.indexOf('sd_complete_type') != -1) {
                id_product = getAttributeFromPartNumber(opt[id_name], sanden_complete, 'id') != '' ?  getAttributeFromPartNumber(opt[id_name], sanden_complete, 'id') : '';
                partNumber_product = getAttributeFromPartNumber(opt[id_name], sanden_complete, 'part_number') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_complete, 'part_number') : '';
                name_product = getAttributeFromPartNumber(opt[id_name], sanden_complete, 'name') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_complete, 'name') : '';
                result[option].completes[item_no] = {...result[option].completes[item_no], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};
            }
            if (id_name.indexOf('qty_sd_complete') != -1) {
                if (!check_main.hasOwnProperty(option)) {
                    check_main[option] = 0;
                }
                check_main[option] += parseInt(opt[id_name] != '' ? opt[id_name] : 0);
            }

            result[option].completes[item_no] = {...result[option].completes[item_no], ...opt};
            return true;
        }
        //Hpump
        if (id_name.indexOf('sd_hpump_type') != -1 || id_name.indexOf('qty_sd_hpump') != -1) {
            if (!result[option].hasOwnProperty('hpump')) {
                result[option].hpump = {};
            }
            if(id_name.indexOf('sd_hpump_type') != -1) {
                id_product = getAttributeFromPartNumber(opt[id_name], sanden_hpump, 'id') != '' ?  getAttributeFromPartNumber(opt[id_name], sanden_hpump, 'id') : '';
                partNumber_product = getAttributeFromPartNumber(opt[id_name], sanden_hpump, 'part_number') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_hpump, 'part_number') : '';
                name_product = getAttributeFromPartNumber(opt[id_name], sanden_hpump, 'name') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_hpump, 'name') : '';
                result[option].hpump[item_no] = {...result[option].hpump[item_no], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};
            }

            result[option].hpump[item_no] = {...result[option].hpump[item_no], ...opt};
            return true;
        }

        //Tank
        if (id_name.indexOf('sd_tank_type') != -1 || id_name.indexOf('qty_sd_tank') != -1) {
            if (!result[option].hasOwnProperty('tanks')) {
                result[option].tanks = {};
            }
            if(id_name.indexOf('sd_tank_type') != -1) {

                id_product = getAttributeFromPartNumber(opt[id_name], sanden_tank, 'id') != '' ?  getAttributeFromPartNumber(opt[id_name], sanden_tank, 'id') : '';
                partNumber_product = getAttributeFromPartNumber(opt[id_name], sanden_tank, 'part_number') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_tank, 'part_number') : '';
                name_product = getAttributeFromPartNumber(opt[id_name], sanden_tank, 'name') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_tank, 'name') : '';
                result[option].tanks[item_no] = {...result[option].tanks[item_no], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};
            }
            result[option].tanks[item_no] = {...result[option].tanks[item_no], ...opt};
            return true;
        }

        //Accessory 
        if (id_name.indexOf('sd_accessory_type') != -1 || id_name.indexOf('qty_sd_accessory') != -1) {
            if (!result[option].hasOwnProperty('accessories')) {
                result[option].accessories = {};
            }
            if(id_name.indexOf('sd_accessory_type') != -1) {
                id_product = getAttributeFromPartNumber(opt[id_name], sanden_accessory, 'id') != '' ?  getAttributeFromPartNumber(opt[id_name], sanden_accessory, 'id') : '';
                partNumber_product = getAttributeFromPartNumber(opt[id_name], sanden_accessory, 'part_number') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_accessory, 'part_number') : '';
                name_product = getAttributeFromPartNumber(opt[id_name], sanden_accessory, 'name') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_accessory, 'name') : '';
                result[option].accessories[item_no] = {...result[option].accessories[item_no], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};
            }
            result[option].accessories[item_no] = {...result[option].accessories[item_no], ...opt};
            return true;
        }
        
        //Extra 
        if (id_name.indexOf('sd_extra_type') != -1 || id_name.indexOf('qty_ext_sd_extra') != -1 || id_name.indexOf('price_ext_sd_extra') != -1) {
            // debugger
            if (!result[option].hasOwnProperty('extras')) {
                result[option].extras = {};
            }
            if(id_name.indexOf('sd_extra_type') != -1) {
                id_product = getAttributeFromPartNumber(opt[id_name], sanden_extra, 'id') != '' ?  getAttributeFromPartNumber(opt[id_name], sanden_extra, 'id') : '';
                partNumber_product = getAttributeFromPartNumber(opt[id_name], sanden_extra, 'part_number') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_extra, 'part_number') : '';
                name_product = getAttributeFromPartNumber(opt[id_name], sanden_extra, 'name') != '' ? getAttributeFromPartNumber(opt[id_name], sanden_extra, 'name') : '';
                result[option].extras[item_no] = {...result[option].extras[item_no], ...{'id' : id_product, 'partNumber' : partNumber_product, 'productName' : name_product}};
            }
            result[option].extras[item_no] = {...result[option].extras[item_no], ...opt};
            return true;
        }

        //Rebate 
        if (id_name.indexOf('sd_stc') != -1 || id_name.indexOf('sd_veec') != -1) {
            if(id_name.indexOf('sd_stc') != -1) {
                result[option] = {
                    ...result[option]
                    , ...{
                        'sd_stc_cost': parseFloat(getAttributeFromPartNumber(sd_Rebate_partNumber[0], sanden_rebate, 'cost'))
                    }
                };
            } 
            if(id_name.indexOf('sd_veec') != -1) {
                result[option] = {
                    ...result[option]
                    , ...{
                        'sd_veec_cost': parseFloat(getAttributeFromPartNumber(sd_Rebate_partNumber[1], sanden_rebate, 'cost'))
                    }
                };
            } 

            result[option] = {...result[option], ...opt};
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
    result = {...result, ...{'state': state, 'postcode': postcode}};
    $("#sanden_option_c").val(JSON.stringify(result));
}

//Load option
function SD_loadOption(){
    if($("#sanden_option_c").val() != ""){
        try{
            var json_val = JSON.parse($("#sanden_option_c").val());
            
            // Create Complete line
            let current_line = SD_getCountLine('sd_complete');
            let item_line = (json_val.sd_complete_line != undefined && json_val.sd_complete_line != '') ? json_val.sd_complete_line : 1;
            if (item_line > current_line) {
                for (let i = 0; i < (item_line - current_line); i++) {
                    SD_createNewLine('sd_complete');
                }
            }
            // Create Tank line
            current_line = SD_getCountLine('sd_tank');
            item_line = (json_val.sd_tank_line != undefined && json_val.sd_tank_line != '') ? json_val.sd_tank_line : 1;
            if (item_line > current_line) {
                for (let i = 0; i < (item_line - current_line); i++) {
                    SD_createNewLine('sd_tank');
                }
            }
            // Create Accessory line
            current_line = SD_getCountLine('sd_accessory');
            item_line = (json_val.sd_accessory_line != undefined && json_val.sd_accessory_line != '') ? json_val.sd_accessory_line : 1;
            if (item_line > current_line) {
                for (let i = 0; i < (item_line - current_line); i++) {
                    SD_createNewLine('sd_accessory');
                }
            }

            // Create Extra line
            current_line = SD_getCountLine('sd_extra');
            item_line = (json_val.sd_extra_line != undefined && json_val.sd_extra_line != '') ? json_val.sd_extra_line : 1;
            if (item_line > current_line) {
                for (let i = 0; i < (item_line - current_line); i++) {
                    SD_createNewLine('sd_extra');
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
function SD_getCurrentOptionState(index){
    let result = {};
    let total_qty_sd_complete = 0;
    let state = $('#install_address_state_c').val();
    let postcode = $('#install_address_postalcode_c').val();

    result['state'] = state;
    result['postcode'] = postcode;
    result['index'] = index;
    result['stc_number'] = ($("#sd_stc_"+index).val() != '') ? parseFloat($("#sd_stc_"+index).val()) : '0';
    result['veec_number'] = ($("#sd_veec_"+index).val() != '') ? parseFloat($("#sd_veec_"+index).val()) : '0';
    result['pm'] = ($("#pmsd_"+index).val() != '') ? parseFloat($("#pmsd_"+index).val()) : '0';
    // Complete line
    let num_of_line = SD_getCountLine('sd_complete');
    for (var i = 0; i < num_of_line; i++) {
        result['sd_complete_type' + (i + 1)] = $('#sd_complete_type' + (i + 1) + '_' + index).val() == null ? '' : $('#sd_complete_type' + (i + 1) + '_' + index).val();
        result['qty_sd_complete' + (i + 1)] = $('#qty_sd_complete' + (i + 1) + '_' + index).val() != '' ? $('#qty_sd_complete' + (i + 1) + '_' + index).val() : '0' ;
        total_qty_sd_complete += $('#qty_sd_complete' + (i + 1) + '_' + index).val() != '' ? parseFloat($('#qty_sd_complete' + (i + 1) + '_' + index).val()) : 0;
    }
    // Total qty complete 
    result['total_qty_sd_complete'] = total_qty_sd_complete;
    // Heat pump
    result['sd_hpump_type'] =  $(`#sd_hpump_type1_${index}`).val() == null ? '' : $(`#sd_hpump_type1_${index}`).val();
    result['qty_sd_hpump'] =  $(`#qty_sd_hpump1_${index}`).val() != '' ?  $(`#qty_sd_hpump1_${index}`).val() : '0';
    // Tank line
    num_of_line = SD_getCountLine('sd_tank');
    for (var i = 0; i < num_of_line; i++) {
        result['sd_tank_type' + (i + 1)] = $('#sd_tank_type' + (i + 1) + '_' + index).val() == null ? '' : $('#sd_tank_type' + (i + 1) + '_' + index).val();
        result['qty_sd_tank' + (i + 1)] = $('#qty_sd_tank' + (i + 1) + '_' + index).val() != '' ? $('#qty_sd_tank' + (i + 1) + '_' + index).val() : '0' ;
    }
    // Installer
    result['sd_install_plumber'] = $('#sd_install_plum_' + index).val();
    result['sd_install_electrician'] = $('#sd_install_elec_' + index).val();
    // Accessory line
    num_of_line = SD_getCountLine('sd_accessory');
    for (var i = 0; i < num_of_line; i++) {
        result['sd_accessory_type' + (i + 1)] = $('#sd_accessory_type' + (i + 1) + '_' + index).val() == null ? '' : $('#sd_accessory_type' + (i + 1) + '_' + index).val();
        result['qty_sd_accessory' + (i + 1)] = $('#qty_sd_accessory' + (i + 1) + '_' + index).val() != '' ? $('#qty_sd_accessory' + (i + 1) + '_' + index).val() : '0' ;
    }
    
    // Extra line
    num_of_line = SD_getCountLine('sd_extra');
    for (var i = 0; i < num_of_line; i++) {
        result['sd_extra_type' + (i + 1)] = $('#sd_extra_type' + (i + 1) + '_' + index).val() == null ? '' : $('#sd_extra_type' + (i + 1) + '_' + index).val();
        result['qty_ext_sd_extra' + (i + 1)] = $('#qty_ext_sd_extra' + (i + 1) + '_' + index).val() != '' ? $('#qty_ext_sd_extra' + (i + 1) + '_' + index).val() : '0';
        result['price_ext_sd_extra' + (i + 1)] = $('#price_ext_sd_extra' + (i + 1) + '_' + index).val() != '' ? $('#price_ext_sd_extra' + (i + 1) + '_' + index).val() : '0';
    }
    return result;
}


function SD_calcOption(index) {
    // debugger
    if(index != '' && index != undefined){
        let currState = SD_getCurrentOptionState(index);
        let grandTotalR90;
        // Grand Total
        let rebate = SD_calcSTCVEEC(currState); // so am
        let grandTotal = SD_calcGrandTotal(currState); //include rebate
        if (grandTotal != 0) {
            grandTotalR90 = Number(roundTo90(grandTotal));
        } else {
            grandTotalR90 = grandTotal;
        }
        let grandTotalNoRebate = grandTotalR90 - rebate;
        let subtotal = Number(parseFloat(grandTotalNoRebate/1.1).toFixed(2));
        let gst = Number(parseFloat(grandTotalNoRebate - subtotal).toFixed(2));
        //fill 
        $('#sd_rebate_'+index).val(parseFloat(rebate).formatMoney(2, ',', '.'));
        $("#sd_subtotal_"+index).val(parseFloat(subtotal).formatMoney(2, ',', '.'));
        $("#sd_gst_"+index).val(parseFloat(gst).formatMoney(2, ',', '.'));
        $("#sd_grandtotal_"+index).val(parseFloat(grandTotalR90).formatMoney(2, ',', '.'));
        // $("#sd_grandtotal_"+index).val(parseFloat(roundTo90(grandTotal)).formatMoney(2, ',', '.'));
        // Save current option
        SD_saveCurrentState();
    }
}

//
function SD_clearOption(option){
    $("#sanden_option_"+(option)).prop('checked', false);
    $("#recom_sd_option_"+(option)).prop('checked', false);
    $('#sanden_pricing_table td:nth-child('+ (option + 1) +') input:not(input[id="sd_pe_admin_percent"], input[id="sd_complete_line"],input[id="sd_hpump_line"] ,input[id="sd_tank_line"] ,input[id="sd_accessory_line"], input[id="sd_extra_line"])').val('');
    $('#sanden_pricing_table td:nth-child('+ (option + 1) +')').find('select').prop("selectedIndex", 0);
}

function SD_getCountLine(target){
    return parseInt($('#'+ target +'_line').val());
}

function SD_calcInstallCost(currState) {
    let num_of_line = SD_getCountLine('sd_complete');
    let total_qty_sd_complete = 0, install_cost = 0, quantityPB = 0;
    for (var i = 0; i < num_of_line; i++) {
        if(currState['sd_complete_type' + (i + 1)] != '' && currState['qty_sd_complete'+(i+1)] != ''){
            total_qty_sd_complete += parseFloat(currState['qty_sd_complete'+(i+1)]);
        }
    }
    // Sanden install
    if (currState['sd_install_plumber'] == 'Yes' && total_qty_sd_complete > 0 ) {
        install_cost += parseFloat(getAttributeFromPartNumber(sd_installation_plumber[0], sanden_install, 'cost')) * parseFloat(total_qty_sd_complete);
        quantityPB+=1;
    }
    if (currState['sd_install_electrician'] == 'Yes' && total_qty_sd_complete > 0 ) {
        install_cost += parseFloat(getAttributeFromPartNumber(sd_installation_electrician[0], electric_installation, 'cost')) * parseFloat(total_qty_sd_complete);
        quantityPB+=1;
    }
    if (quantityPB > 0) {
        install_cost += parseFloat(getAttributeFromPartNumber(sd_installation_extra[0], sanden_installation_extra, 'cost')) * quantityPB + parseFloat(getAttributeFromPartNumber(sd_installation_extra[1], sanden_installation_extra, 'cost')) * quantityPB;
    }
    return install_cost;
}

function SD_calcDeliveryCost(equipmentCost, currState) {
    let delivery_cost = 0;
    //Sanden delivery 
    if (equipmentCost > 0) {
        // delivery_cost += parseFloat(getAttributeFromPartNumber(sd_delivery[0], sanden_install, 'cost')); //get database
        delivery_cost += parseFloat(sd_delivery_state[currState.state == 'WA' ? 'WA' : 'default']);
    }
    return delivery_cost;
}

function SD_calcEquipmentCost(currState){
    let complete_cost = 0, extra_cost = 0, tank_cost = 0, hpump_cost = 0, accessory_cost = 0;
    // Sanden complete cost
    let num_of_line = SD_getCountLine('sd_complete');
    for (var i = 0; i < num_of_line; i++) {
        if(currState['sd_complete_type' + (i + 1)] != '' && currState['qty_sd_complete'+(i+1)] != 0){
            complete_cost += parseFloat(getAttributeFromPartNumber(currState['sd_complete_type' + (i + 1)], sanden_complete, "cost")) * parseFloat(currState['qty_sd_complete'+(i+1)]);
        }
    }

    // Sanden Heat Pump cost
    if (currState['sd_hpump_type'] != '' && currState['qty_sd_hpump'] != 0) {
        hpump_cost += parseFloat(getAttributeFromPartNumber(currState['sd_hpump_type'], sanden_hpump, "cost")) * parseFloat(currState['qty_sd_hpump']);
    }

    // Sanden Tank cost
    num_of_line = SD_getCountLine('sd_tank');
    for (var i = 0; i < num_of_line; i++) {
        if(currState['sd_tank_type' + (i + 1)] != '' && currState['qty_sd_tank'+(i+1)] != 0){
            tank_cost += parseFloat(getAttributeFromPartNumber(currState['sd_tank_type' + (i + 1)], sanden_tank, "cost")) * parseFloat(currState['qty_sd_tank'+(i+1)]);
        }
    }

    // Sanden Accessory cost
    num_of_line = SD_getCountLine('sd_accessory');
    for (var i = 0; i < num_of_line; i++) {
        if(currState['sd_accessory_type' + (i + 1)] != '' && currState['qty_sd_accessory'+(i+1)] != 0){
            accessory_cost += parseFloat(getAttributeFromPartNumber(currState['sd_accessory_type' + (i + 1)], sanden_accessory, "cost")) * parseFloat(currState['qty_sd_accessory'+(i+1)]);
        }
    }
    
    // Sanden extra cost
    num_of_line = SD_getCountLine('sd_extra');
    for (var i = 0; i < num_of_line; i++) {
        if(currState['sd_extra_type' + (i + 1)] != '' && currState['qty_ext_sd_extra' + (i + 1)] != 0 && currState['price_ext_sd_extra' + (i + 1)] != 0){
            extra_cost += parseFloat(currState['qty_ext_sd_extra' + (i + 1)]) * parseFloat(currState['price_ext_sd_extra' + (i + 1)]);
        }
    }

    return complete_cost + hpump_cost + accessory_cost +tank_cost + extra_cost;
}

function SD_calcGrandTotal(currState){
    // debugger
    let grandTotal = 0, stc_veec_cost = 0;
    // Equipment cost
    grandTotal += SD_calcEquipmentCost(currState);
    // Install + Delivery cost
    grandTotal += SD_calcInstallCost(currState) + SD_calcDeliveryCost(SD_calcEquipmentCost(currState), currState);
    //stc + veec
    stc_veec_cost += SD_calcSTCVEEC(currState);
    // PE Admin %
    grandTotal += grandTotal * (parseFloat($('#sd_pe_admin_percent').val()) / 100);
    // GST 10%
    let gst = grandTotal * 0.1;
    // Include GST, sub rebate
    grandTotal += gst + stc_veec_cost;
    // PM
    if (currState.pm != undefined && currState.pm != '') {
        grandTotal += parseFloat(currState.pm);
    }
    return grandTotal;
}

function SD_calcHint(){
    $('#sd_hint1').html('');
    $('#sd_hint2').html('');
    // Check index
    let index = $('input[name="sanden_option"]:checked').attr('data-attr');
    if (!index){
        $('#sd_hint1').html("You must choose the Option to see calc hint");
        alert("You must choose the Option to see calc hint");
        return;
    }
    let currState = SD_getCurrentOptionState(index);
    let str = "";
    /** ==S== HINT 1 ==== */
         /** S - Equipment Cost */ 
        let complete_cost = 0, delivery_cost = 0, install_cost = 0, extra_cost = 0, tank_cost = 0, hpump_cost = 0, accessory_cost = 0, rebate_cost = 0;
        // Sanden complete cost
        let num_of_line = SD_getCountLine('sd_complete');
        for (var i = 0; i < num_of_line; i++) {
            if(currState['sd_complete_type' + (i + 1)] != '' && currState['qty_sd_complete'+(i+1)] != 0){
                complete_cost += parseFloat(getAttributeFromPartNumber(currState['sd_complete_type' + (i + 1)], sanden_complete, "cost")) * parseFloat(currState['qty_sd_complete'+(i+1)]);
                str += SD_writeHint(currState['sd_complete_type' + (i + 1)], parseFloat(getAttributeFromPartNumber(currState['sd_complete_type' + (i + 1)], sanden_complete, "cost")) * parseFloat(currState['qty_sd_complete'+(i+1)]), parseFloat(currState['qty_sd_complete'+(i+1)]));
            }
        }
        // Sanden Heat Pump cost
        if (currState['sd_hpump_type'] != '' && currState['qty_sd_hpump'] != 0) {
            hpump_cost += parseFloat(getAttributeFromPartNumber(currState['sd_hpump_type'], sanden_hpump, "cost")) * parseFloat(currState['qty_sd_hpump']);
            str+= SD_writeHint(currState['sd_hpump_type'],hpump_cost, parseFloat(currState['qty_sd_hpump']));
        }
        // Sanden Tank cost
        num_of_line = SD_getCountLine('sd_tank');
        for (var i = 0; i < num_of_line; i++) {
            if(currState['sd_tank_type' + (i + 1)] != '' && currState['qty_sd_tank'+(i+1)] != 0){
                tank_cost += parseFloat(getAttributeFromPartNumber(currState['sd_tank_type' + (i + 1)], sanden_tank, "cost")) * parseFloat(currState['qty_sd_tank'+(i+1)]);
                str += SD_writeHint(currState['sd_tank_type' + (i + 1)], parseFloat(getAttributeFromPartNumber(currState['sd_tank_type' + (i + 1)], sanden_tank, "cost")) * parseFloat(currState['qty_sd_tank'+(i+1)]), parseFloat(currState['qty_sd_tank'+(i+1)]));
            }
        }

        // Sanden Accessory cost
        num_of_line = SD_getCountLine('sd_accessory');
        for (var i = 0; i < num_of_line; i++) {
            if(currState['sd_accessory_type' + (i + 1)] != '' && currState['qty_sd_accessory'+(i+1)] != 0){
                accessory_cost += parseFloat(getAttributeFromPartNumber(currState['sd_accessory_type' + (i + 1)], sanden_accessory, "cost")) * parseFloat(currState['qty_sd_accessory'+(i+1)]);
                str += SD_writeHint(currState['sd_accessory_type' + (i + 1)], parseFloat(getAttributeFromPartNumber(currState['sd_accessory_type' + (i + 1)], sanden_accessory, "cost")) * parseFloat(currState['qty_sd_accessory'+(i+1)]), parseFloat(currState['qty_sd_accessory'+(i+1)]));
            }
        }
        
        // Sanden extra cost
        num_of_line = SD_getCountLine('sd_extra');
        for (var i = 0; i < num_of_line; i++) {
            if(currState['sd_extra_type' + (i + 1)] != '' && currState['qty_ext_sd_extra' + (i + 1)] != 0 && currState['price_ext_sd_extra' + (i + 1)] != 0){
                extra_cost += parseFloat(currState['qty_ext_sd_extra' + (i + 1)]) * parseFloat(currState['price_ext_sd_extra' + (i + 1)]);
                str+= SD_writeHint(currState['sd_extra_type' + (i + 1)], parseFloat(currState['qty_ext_sd_extra' + (i + 1)]) * parseFloat(currState['price_ext_sd_extra' + (i + 1)]), parseFloat(currState['qty_ext_sd_extra' + (i + 1)]));
            }
        }
    // total equipment cost
    let equipment = complete_cost + hpump_cost + accessory_cost +tank_cost + extra_cost;
    str += SD_writeHint(
        "TOTAL EQUIPMENT COST"
        , equipment
        , ''
        , true
        , true
    );
    /** E - Equipment Cost */ 

    /** S - Install and Delivery */
        //Sanden delivery 
        if (equipment != 0) {
            // delivery_cost += parseFloat(getAttributeFromPartNumber(sd_delivery[0], sanden_install, 'cost'));
            delivery_cost += parseFloat(sd_delivery_state[currState.state == 'WA' ? 'WA' : 'default']);
            str+= SD_writeHint(`Delivery ${currState.state}`,delivery_cost); 
        }
        // Sanden install 
        let plumber_cost = 0, electrician_cost = 0, quantityPB = 0, install_extra_cost = 0;
        if (currState['sd_install_plumber'] == 'Yes' && currState['total_qty_sd_complete'] > 0) {
            plumber_cost += parseFloat(getAttributeFromPartNumber(sd_installation_plumber[0], sanden_install, 'cost')) * parseFloat(currState['total_qty_sd_complete']);
            str+= SD_writeHint('Plumber Install',plumber_cost, currState['total_qty_sd_complete']);
            quantityPB+=1;
        }
        if (currState['sd_install_electrician'] == 'Yes' && currState['total_qty_sd_complete'] > 0) {
            electrician_cost += parseFloat(getAttributeFromPartNumber(sd_installation_electrician[0], electric_installation, 'cost')) * parseFloat(currState['total_qty_sd_complete']);
            str+= SD_writeHint('Electrician Install',electrician_cost, currState['total_qty_sd_complete']);
            quantityPB+=1;
        }
        if (quantityPB > 0) {
            str+= SD_writeHint('Paperwork Bonus',parseFloat(getAttributeFromPartNumber(sd_installation_extra[0], sanden_installation_extra, 'cost')) * quantityPB, quantityPB);
            str+= SD_writeHint('Upload Photo',parseFloat(getAttributeFromPartNumber(sd_installation_extra[1], sanden_installation_extra, 'cost')) * quantityPB, quantityPB);
            install_extra_cost += parseFloat(getAttributeFromPartNumber(sd_installation_extra[0], sanden_installation_extra, 'cost')) * quantityPB + parseFloat(getAttributeFromPartNumber(sd_installation_extra[1], sanden_installation_extra, 'cost')) * quantityPB
        }
        install_cost = plumber_cost + electrician_cost + install_extra_cost;
        let ins_delivery = delivery_cost + install_cost;
        str += SD_writeHint(
            "TOTAL INSTALL AND DELIVERY COST"
            , ins_delivery
            , ''
            , true
            , true
        );
    /** E - Install and Delivery */

    /** S - Subtotal = Equipment + install + delivery */
        str += SD_writeHint(
            "SUBTOTAL (Equipment + Install + Delivery)"
            , (equipment + ins_delivery)
            , ''
            , true
            , true
        );
    /** E - Subtotal = Equipment + install + delivery */
    // PE Admin %
    str += SD_writeHint(
        'PE Admin %'
        , parseFloat($('#sd_pe_admin_percent').val()) / 100
    );
    // Subtotal + PE Admin
    let grandTotal = equipment + ins_delivery ;
    grandTotal += grandTotal*(parseFloat($('#sd_pe_admin_percent').val()) / 100);
    str += SD_writeHint(
        'Sub total + PE Admin % '
        , grandTotal
    );

    // str += SD_writeHint(
    //     'Sub total (sub stc/veec)'
    //     , grandTotal + (stc_cost + veec_cost)
    // );


    // GST 10%
    let gst = grandTotal * 0.1;
    str += SD_writeHint(
        'GST 10%'
        , gst
    );
    // Include GST
    grandTotal += gst;
    str += SD_writeHint(
        'Grand Total inclue GST'
        , grandTotal
        ,''
        , true
        , true
    );

    /**S -  STC - VEEC */
    let stc_cost = 0, veec_cost = 0;
    if (parseInt(currState['stc_number']) > 0 || parseInt(currState['veec_number']) > 0) {
        if (parseInt(currState['stc_number']) > 0) {
            stc_cost += parseFloat(getAttributeFromPartNumber(sd_Rebate_partNumber[0], sanden_rebate, 'cost')) * parseInt(currState['stc_number']);
            str+= SD_writeHint('STCs', stc_cost, parseInt(currState['stc_number']));
        }
        if (parseInt(currState['veec_number']) > 0) {
            veec_cost += parseFloat(getAttributeFromPartNumber(sd_Rebate_partNumber[1], sanden_rebate, 'cost')) * parseInt(currState['veec_number']);
            str+= SD_writeHint('VEECs', veec_cost, parseInt(currState['veec_number']));
        }
        rebate_cost = stc_cost + veec_cost;
        str+= SD_writeHint("TOTAL REBATES (STCs/VEECs)"
            , rebate_cost
            , ''
            , true
            , true
        );
    }
    /**E - STC - VEEC */

    // Sub rebate 
    grandTotal += rebate_cost;
    str += SD_writeHint(
        'Grand Total (sub rebate)'
        , grandTotal
    );

    // PM price
    if(currState.pm != undefined && currState.pm != ''){
        grandTotal += parseFloat(currState.pm);
        str += SD_writeHint(
            'GRAND TOTAL + PM'
            , grandTotal
            , ''
            , true
            , true
        );
    }
    /** ==E== HINT 1 ==== */

    // /** ==================== Hint 2 =======================*/ 
    let str2 = '';
    let grandTotal2 = Number(roundTo90(grandTotal));
    // Recalculate Sub Price Total
    str2 += SD_writeHint(
        'Grand Total (Round 90)'
        , grandTotal2
        , ''
        , true
        , true
    );

    // Total before Rebate (STCs/VEECs)
    grandTotal2 = grandTotal2 - rebate_cost;
    str2 += SD_writeHint(
        'Grand Total (before Rebate)'
        , grandTotal2
    );

    let subtotal2 = Number(parseFloat(grandTotal2/1.1).toFixed(2));
    let gst2 = Number(parseFloat(grandTotal2 - subtotal2).toFixed(2));
    str2 += SD_writeHint(
        'New Subtotal (before rebate, show at calc Table / line 01 in line item)'
        , subtotal2
    );
    str2 += SD_writeHint(
        'New GST'
        , gst2
    );

    str2 += SD_writeHint(
        'New Subtotal (sub rebate, show at line item summary)'
        , subtotal2 + rebate_cost
        , ''
        , true
        , true
    );


    // Return
    $('#sd_hint1').append(str);
    $('#sd_hint2').append(str2);
}

var SD_wait = ms => {
    return new Promise(res => setTimeout(res, ms));
};

function SD_getCompleteSys(){
    let result = {};
    let num_of_line = SD_getCountLine('sd_complete');
    for (var j = 1; j < 7 ; j++) {
        for (var i = 0; i < num_of_line; i++) {
            if ( $('#sd_complete_type' + (i + 1) + '_' + j).val() == null || $('#sd_complete_type' + (i + 1) + '_' + j).val() == '') continue;
            result['sd_complete_type' + (i + 1) + '_' + j] = $('#sd_complete_type' + (i + 1) + '_' + j).val();
        }
    }
    return result;
}

function SD_getRebateProduct(data_in, data_out){
    data_in.forEach((element, index) => {
        $.ajax({
            url: "/index.php?entryPoint=APIGetProductInfoByShortName&short_name=" + element,
            type: 'GET'})
        .then(function(data) {
            if(data !== undefined || data !== ""){
                data_out.push(JSON.parse(data));
            }
        });
    });
}

function SD_calcSTCVEEC(currState) {
    let stc_cost = 0, veec_cost = 0;
    /** STC - VEEC */
    if (parseInt(currState['stc_number']) > 0 && currState['total_qty_sd_complete'] > 0) {
        stc_cost += parseFloat(getAttributeFromPartNumber(sd_Rebate_partNumber[0], sanden_rebate, 'cost')) * parseInt(currState['stc_number']);
    }
    if (parseInt(currState['veec_number']) > 0 && currState['total_qty_sd_complete'] > 0) {
        veec_cost += parseFloat(getAttributeFromPartNumber(sd_Rebate_partNumber[1], sanden_rebate, 'cost')) * parseInt(currState['veec_number']);
    }
    return stc_cost + veec_cost;
}