$(function () {
    'use strict';
    //INIT
    $(document).find('#own_solar_pv_pricing_c').attr('readonly', 'readonly');

    // //test
    // $('#detailpanel_11').append('<button id="testVUT">CLICK</button>');
    // $(document).on('click','#testVUT',function(){
    //     // debugger;
    //     getOwnSolarPricing($("#solar_pv_pricing_input_c").val(), 'solar_input');
    // });
    // //test

    //Variables
    var quote_type = $("#quote_type_c").val();
    var solar_input = $("#solar_pv_pricing_input_c").val();
    var own_solar = $("#own_solar_pv_pricing_c").val();
    var sol_panel, sol_inverter, og_inverter, og_battery, og_accessory; 

    switch (quote_type) {
        case 'quote_type_solar':
            init_table_own_solar();
            getDataProductSolar();
            if (own_solar != '') {
                getOwnSolarPricing(own_solar);
            } else if (solar_input != '') {
                getOwnSolarPricing(solar_input, 'solar_input');
            }
            break;
        case 'quote_type_off_grid_system':
            init_table_offgrid();
            break;
        default: break;
    }

    // .:nhantv:. Add a checkbox to Itemise in LINE ITEMS
    $('input[name="sl_quote_option"]').on('change', function() {
        checkBoxOptionHandle($(this), "sl_quote_option");
    });

    // .:nhantv:. Clear Off Grid Option
    $(document).on('click', '*[id*="clear_og_option"]', function(e){
        e.preventDefault();
        clearOgOption($(this).data('option'));
    });

    // .:nhantv:. Offgrid Checkbox handle 
    $(document).on('change', 'input[id*="offgrid_option"]', function(){
        checkBoxOptionHandle($(this), "offgrid_option");
    });

    // .:nhantv:. Total panels change handle 
    $(document).on('change', 'input[id*="total_og_panels"], select[id*="panel_og_type"], select[id*="inverter_og_type"]', function(e){
        e.preventDefault();
        optionChangeHandle($(this));
    });

    // .:nhantv:. Calculate Button Click handle 
    $(document).on('click', '#calculate_og', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
            var panel_type = $("#panel_og_type_"+i).val();
            var inverter_type = $("#inverter_og_type_"+i).val();
            // Get suggested
            if(panel_type != '' && inverter_type != ""){
                // Calculate option
                calcOption(i);
            }
        }
    });

});


//DECLARE FUNCTIONS
/**
 * VUT - get All Data Product Solar (panel + inverter + std solar install)
 */
function getDataProductSolar() {
    let panel_type = [
        'Jinko 330W Mono PERC HC',
        // 'Jinko 370W Cheetah Plus JKM370M-66H',
        'Q CELLS Q.MAXX-G2 350W',
        /*'Longi Hi-MO X 350W''Q CELLS Q.MAXX 330W''Q CELLS Q.PEAK DUO G6+ 350W',*/
        'Sunpower P3 370 BLACK',
        /*'Sunpower X22 360W',*/
        'Sunpower Maxeon 3 400',
        'Solar PV Standard Install',
        /*'Sunpower Maxeon 2 350','Sunpower Maxeon 3 395'*/
    ];
    let inverter_type = [
        'Primo 3',
        'Primo 4',
        'Primo 5',
        'Primo 6',
        'Primo 8.2',
        'Symo 5',
        'Symo 6',
        'Symo 8.2',
        'Symo 10',
        'Symo 15',
        'SYMO 20',
        'S Edge 3',
        'S Edge 5',
        'S Edge 6',
        'S Edge 8',
        'S Edge 8 3P',
        'S Edge 10',
        'IQ7 plus',
        /*'IQ7',*/
        'IQ7X',
        /*'Growatt 3',
        'Growatt 5',
        'Growatt 6',
        'Growatt8',
        'Growatt 8.2',*/
        'Sungrow 3',
        'Sungrow 5',
        'Sungrow 8',
        'Sungrow 10 3P',
        'Sungrow 15 3P',
    ];

    $.ajax({
        url: "index.php?entryPoint=getOwnSolarPricing",
        type : 'POST',
        async: false,
        data: 
        {
            panel_type: panel_type,
            inverter_type: inverter_type,
        },
        success: function (data) {
            if(data === undefined){
                localStorage.setItem('productSolar','');
                return;
            }else{
                localStorage.setItem('productSolar',JSON.stringify($.parseJSON(data)));
            }
        }
    }); 
}

/**
 * VUT - get data own solar
 * @param {'json'} data 
 * @param {String} string >> solar_input / own_solar
 */
function getOwnSolarPricing(data, string='') {
    let json_data;
    productSolar = JSON.parse(localStorage.productSolar);

    if (string == '') {
        json_data = $.parseJSON(data, true);
        loadOwnOptionsPricing();
    } else {
        json_data = getInputTypes(data);
        for (let i=1; i<7; i++) {
            json_data[`own_basePrice_${i}`] = calc_own_basePrice(productSolar, json_data, i);
        }
        $('#own_solar_pv_pricing_c').val(JSON.stringify(json_data));
        loadOwnOptionsPricing();
    }
}

/**
 * VUT - calc base Price for Own solar
 * @param {'localStorage.productSolar'} products 
 * @param {'json_own'} own_data 
 * @param {'number'} index 
 * @return {'float'} total cost
 */
function calc_own_basePrice(products, own_data, index) {
    let panel_cost = 0, inverter_cost = 0, std_solar_install_cost = 0;
    // let inv_regex = '/'+own_data[`own_inverterType_${index}`]+'/';
    panel_cost = parseFloat(own_data[`own_totalPanels_${index}`]) * parseFloat(!isNaN(products.panels[own_data[`own_panelType_${index}`]])?products.panels[own_data[`own_panelType_${index}`]]:0);
    std_solar_install_cost = parseFloat(own_data[`own_totalkW_${index}`]) * parseFloat(products.panels['Solar PV Standard Install']);
    $.each(products.inverters, function (name, cost) {
        if (name.indexOf(own_data[`own_inverterType_${index}`]) !== -1) {
            inverter_cost = parseFloat(cost);
            return false;
        }
    });
    return (panel_cost + inverter_cost + std_solar_install_cost).toFixed(2);
}

/**
 * VUT - get input type from Solar PV Pricing Input 
 * @param {'json'} solar_input 
 * @returns {'json'} json_res
 */
function getInputTypes(solar_input) {
    let json_val = JSON.parse(solar_input);
    let json_res = {};
    for (let key in json_val) {
        if (key.indexOf('total_kW_') != -1) {
            json_res['own_totalkW_'+key[key.length - 1]] = json_val[key]; 
        } else if (key.indexOf('panel_type_') != -1 ) {
            json_res['own_panelType_'+key[key.length - 1]] = json_val[key]; 
        } else if (key.indexOf('inverter_type_') != -1) {
            json_res['own_inverterType_'+key[key.length - 1]] = json_val[key]; 
        } else if (key.indexOf('total_panels_') != -1) {
            json_res['own_totalPanels_'+key[key.length - 1]] = json_val[key]; 
        } 
    }
    return json_res;
} //end function getInputTypes

/**
 * VUT - init table own solar pv pricing  
 */
function init_table_own_solar() {
    // let own_solar_pv_pricing_table   = $("<div id='own_solar_pv_pricing_table'></div>"); 
    let own_solar_pv_pricing_table   = $('<div id="own_solar_pv_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row"></div>'); 
    
    let data = [
        ["", 'Option 1', 'Option 2', 'Option 3', 'Option 4', 'Option 5', 'Option 6'],
        ["Selected Quote Option"
            , "<input data-attr='1' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_1' style='margin-bottom:5px'>"
            ,"<input data-attr='2' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_2' style='margin-bottom:5px'>"
            ,"<input data-attr='3' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_3' style='margin-bottom:5px'>"
            ,"<input data-attr='4' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_4' style='margin-bottom:5px'>"
            ,"<input data-attr='5' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_5' style='margin-bottom:5px'>"
            ,"<input data-attr='6' type='checkbox' class='sl_quote_option solar_pv_pricing_input' name='sl_quote_option' id='own_sl_option_6' style='margin-bottom:5px'>"],
        ["Total kW:", makeInputBox("own_totalkW_1 own_solar_pv_pricing", "own_totalkW_1", true), makeInputBox("own_totalkW_2 own_solar_pv_pricing", "own_totalkW_2", true), makeInputBox("own_totalkW_3 own_solar_pv_pricing", "own_totalkW_3",true), makeInputBox("own_totalkW_4 own_solar_pv_pricing", "own_totalkW_4",true), makeInputBox("own_totalkW_5 own_solar_pv_pricing", "own_totalkW_5",true), makeInputBox("own_totalkW_6 own_solar_pv_pricing", "own_totalkW_6",true)],
        ["Panel Type:", makeInputBox("own_panelType_1 own_solar_pv_pricing", "own_panelType_1", true), makeInputBox("own_panelType_2 own_solar_pv_pricing", "own_panelType_2", true), makeInputBox("own_panelType_3 own_solar_pv_pricing", "own_panelType_3", true), makeInputBox("own_panelType_4 own_solar_pv_pricing", "own_panelType_4", true), makeInputBox("own_panelType_5 own_solar_pv_pricing", "own_panelType_5", true), makeInputBox("own_panelType_6 own_solar_pv_pricing", "own_panelType_6", true)],
        ["Inverter Type:", makeInputBox("own_inverterType_1 own_solar_pv_pricing", "own_inverterType_1", true), makeInputBox("own_inverterType_2 own_solar_pv_pricing", "own_inverterType_2", true), makeInputBox("own_inverterType_3 own_solar_pv_pricing", "own_inverterType_3", true), makeInputBox("own_inverterType_4 own_solar_pv_pricing", "own_inverterType_4", true), makeInputBox("own_inverterType_5 own_solar_pv_pricing", "own_inverterType_5", true), makeInputBox("own_inverterType_6 own_solar_pv_pricing", "own_inverterType_6", true)],
        // ["Panel Type:", makeSelectBox(panel_type,"own_panelType_1 own_solar_pv_pricing", "own_panelType_1"), makeSelectBox(panel_type,"own_panelType_2 own_solar_pv_pricing", "own_panelType_2"), makeSelectBox(panel_type,"own_panelType_3 own_solar_pv_pricing", "own_panelType_3"), makeSelectBox(panel_type,"own_panelType_4 own_solar_pv_pricing", "own_panelType_4"), makeSelectBox(panel_type,"own_panelType_5 own_solar_pv_pricing", "own_panelType_5"), makeSelectBox(panel_type,"own_panelType_6 own_solar_pv_pricing", "own_panelType_6")],
        // ["Inverter Type:", makeSelectBox(inverter_type,"own_inverterType_1 own_solar_pv_pricing", "own_inverterType_1"), makeSelectBox(inverter_type,"own_inverterType_2 own_solar_pv_pricing", "own_inverterType_2"), makeSelectBox(inverter_type,"own_inverterType_3 own_solar_pv_pricing", "own_inverterType_3"), makeSelectBox(inverter_type,"own_inverterType_4 own_solar_pv_pricing", "own_inverterType_4"), makeSelectBox(inverter_type,"own_inverterType_5 own_solar_pv_pricing", "own_inverterType_5"), makeSelectBox(inverter_type,"own_inverterType_6 own_solar_pv_pricing", "own_inverterType_6")],
        ["Total Panels:", makeInputBox("own_totalPanels_1 own_solar_pv_pricing","own_totalPanels_1", true), makeInputBox("own_totalPanels_2 own_solar_pv_pricing", "own_totalPanels_2", true), makeInputBox("own_totalPanels_3 own_solar_pv_pricing", "own_totalPanels_3", true), makeInputBox("own_totalPanels_4 own_solar_pv_pricing", "own_totalPanels_4", true), makeInputBox("own_totalPanels_5 own_solar_pv_pricing", "own_totalPanels_5", true), makeInputBox("own_totalPanels_6 own_solar_pv_pricing", "own_totalPanels_6", true)],
        ["Base Price:", makeInputBox("own_basePrice_1 own_solar_pv_pricing", "own_basePrice_1", true), makeInputBox("own_basePrice_2 own_solar_pv_pricing", "own_basePrice_2", true), makeInputBox("own_basePrice_3 own_solar_pv_pricing", "own_basePrice_3", true), makeInputBox("own_basePrice_4 own_solar_pv_pricing", "own_basePrice_4", true), makeInputBox("own_basePrice_5 own_solar_pv_pricing", "own_basePrice_5", true), makeInputBox("own_basePrice_6 own_solar_pv_pricing", "own_basePrice_6", true)],
    ];
    //select Panel OWN SOLAR
    // let selector_panel_own_solar = '';
    // $('.panel-content .panel-default').each(function(){
    //     let title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
    //     if(title_panel_default.indexOf('own solar') >= 0 ){
    //         selector_panel_own_solar = '#' + $(this).find('.panel-body').attr('id');
    //     } 

    // $(selector_panel_own_solar).find(".tab-content").html(own_solar_pv_pricing_table);
    
    // .:nhantv:. Update to set order before "Save and Generate Quote" field
    $('body').find("#generate_quote").before(own_solar_pv_pricing_table);

    makeTable(own_solar_pv_pricing_table, data, "Own-Solar-PV-Pricing", "Own-Solar-PV-Pricing");
    //css Table
    $(".Own-Solar-PV-Pricing td").css({"padding":"0px 5px"});
    $(".Own-Solar-PV-Pricing th").css({"text-align":"center"});
    // .:nhantv:. Init Options and gen Line Items
    initOptionAndGenLineItem();
} //end function init_table_own_solar

// .:nhantv:. Clear Offgrid Option
function clearOgOption(option){
    $("#offgrid_option_"+(option)).prop('checked', false);
    $('#offgrid_pricing_table td:nth-child('+ (option + 1) +')').find('input').val('');
    $('#offgrid_pricing_table td:nth-child('+ (option + 1) +')').find('select').prop("selectedIndex", 0);
}

// .:nhantv:. Option Changed handle
function optionChangeHandle(el){
    let attrId = $(el).attr('id');
    let isTotalPanel = attrId.indexOf('total_og_panels_') != -1;
    let index = attrId.substr(attrId.length - 1);
    if(index != "") {
        let currState = getCurrentOptionState(index);
        // Check condition to validate panel - inverter
        if(currState.panel_type != '' && currState.inverter_type != ''){
            calcOption(index, isTotalPanel);
        }
    }
    return;
}

// .:nhantv:. Check box Option handle
function checkBoxOptionHandle(el, inputClass){
    // Set change
    let dataAttr = $(el).attr('data-attr');
    let propVal = $(el).prop('checked');
    if(propVal){
        $('input[name="'+ inputClass +'"]').each(function(){
            $(this).attr('data-attr') && $(this).attr('data-attr') === dataAttr
            ? $(this).prop('checked', propVal) 
            : $(this).prop('checked', !propVal);
        })
    } else {
        // Mark line deleted
        for (var i = 0; i < prodln; i++){
            markLineDeleted(i,"product_");
        };
        // Delete group
        $("#lineItems").find(".group_body").each((index) => {
            markGroupDeleted(index);
        });
    }
    if(inputClass == "sl_quote_option"){
        generateJSONForInput();
    }
}
// .:nhantv:. Convert JSON to Array data
function convertJSONToArrayInit(jsonData){
    let result = [''];
    if (typeof(jsonData) != 'undefined') {
        jsonData.forEach(element => {
            result.push(element.short_name);
        });
    }
    return result;
}

// .:nhantv:. Get current option state
function getCurrentOptionState(index){
    let result = {};
    result['total_kw'] = $('#total_og_kW_' + index).val();
    result['panel_type'] = $('#panel_og_type_' + index).val();
    result['inverter_type'] = $('#inverter_og_type_' + index).val();
    result['total_panels'] = $('#total_og_panels_' + index).val();
    result['number_stcs'] = $('#number_og_stcs_' + index).val();
    result['offgrid_inverter'] = $('#offgrid_inverter_' + index).val();
    result['offgrid_batery'] = $('#offgrid_batery_' + index).val();
    result['offgrid_howmany'] = $('#offgrid_howmany_' + index).val();
    result['offgrid_accessory1'] = $('#offgrid_accessory1_' + index).val();
    result['offgrid_accessory2_'] = $('#offgrid_accessory2_' + index).val();
    return result;
}

// .:nhantv:. Get kw from name
function getKwFromName(name, type){
    let target = {};
    switch (type) {
        case "panel":
            target = sol_panel;
            break;
        case "inverter":
            target = sol_inverter;
            break;
    }
    for (var item in target) {
        if (target[item].short_name == name) {
            return parseFloat(target[item].capacity);
        }
    }
    return 0;
}

// .:nhantv:. Get max panels
function getMaxPanelAndTotalKw(currState, isTotalPanel){
    const ratio = 1.5;
    const panel_kw = getKwFromName(currState.panel_type, "panel");
    const inverter_kw = getKwFromName(currState.inverter_type, "inverter");
    const maxPanel = Math.ceil(inverter_kw * ratio / panel_kw);
    const maxKw = (inverter_kw * ratio).toFixed(1);
    let result = [];
    result['max'] = maxPanel;
    result['kw'] = maxKw;
    // check total panel manually input
    if (isTotalPanel) {
        // Check inut panel is greater than max value
        if(maxPanel < currState.total_panels){
            alert("You have exceeded the maximum number of panels for that panel type. Max = " + maxPanel);
            return result;
        }
        result['max'] = currState.total_panels;
        result['kw'] = (panel_kw * currState.total_panels).toFixed(1);
    }
    return result;
}

// .:nhantv:. Calculate Total Kwh and STCs of Option
async function calcOption(index, isTotalPanel = false) {
    var postcode = $("#install_address_postalcode_c").val();
    if(index != '' && index != undefined){
        let currState = getCurrentOptionState(index);
        if(currState.panel_type != '' && currState.inverter_type != ''){
            // Get max panels and total kw
            let maxPnAndTotalKw = getMaxPanelAndTotalKw(currState, isTotalPanel);
            // Set value
            $("#total_og_panels_"+index).val(maxPnAndTotalKw['max']);
            $('#total_og_kW_'+index).val(maxPnAndTotalKw['kw']);

            // Get STCs value
            try {
                // Show loading
                SUGAR.ajaxUI.showLoadingPanel();
                await $.ajax({
                    url: 'index.php?entryPoint=getSTCsNumberForQuotePricing&total_kw='+maxPnAndTotalKw['kw']+'&postcode='+postcode,
                    type : 'GET',
                    dataType: 'text',
                }).then(function (data) {
                    var result = JSON.parse(data);
                    if(result['NumberOfSTCs'] != ''){
                        $("#number_og_stcs_"+index).val(result['NumberOfSTCs']);
                    }
                    saveCurrentState();
                    // Hide loading
                    setTimeout(function (){
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }, 300);
                });
            } catch (err) {
                console.log(err);
            }
        }
    }
}
// .:nhantv:. Save curent state
function saveCurrentState(){
    var values = {};
    $("#offgrid_pricing_table .offgrid_pricing").each(function (){
        var id_name = $(this).attr("id");
        if($("#"+id_name).attr('type')== 'checkbox'){
            values[id_name] = ($(this).is(":checked") == true) ? 1 : 0;
        } else {
            values[id_name] = $(this).val();
        }
    });

    $("#offgrid_option_c").val(JSON.stringify(values));
}

// .:nhantv:. Load Offgrid option
function loadOffgridOption(){
    if($("#offgrid_option_c").val() != ""){
        try{
            var json_val = JSON.parse($("#offgrid_option_c").val());
            for (let key in json_val) {
                if($("#"+key).attr('type') == 'checkbox'){
                    $("#"+key).prop( "checked", json_val[key] );
                } else {
                    $("#"+key).val(json_val[key]);
                }
            }
        } catch (err) {
            console.log(err);
        }
    }
}

// .:nhantv:. Generate Off-grid Item
async function generateOffgridItem(){
    // Save current state
    saveCurrentState();

    // Get Option Quote
    let index = $('input[name="offgrid_option"]:checked').attr('data-attr');
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
    $("#group0name").val("Off-Grid Option " + index);

    // Get value
    let currState = getCurrentOptionState(index);
    // Validate current State
    if (currState.offgrid_howmany == '') {
        alert("You must enter the number of Battery!");
        return;
    }
    // Create line item
    try{
        // Show loading
        SUGAR.ajaxUI.showLoadingPanel();
        await autoCreateLineItem("Microgrid Solar PV Supply and Install", 1);
        await autoCreateLineItem("Microgrid Standard Install", 1);
        currState.panel_type && await autoCreateLineItem(currState.panel_type, currState.total_panels);
        currState.inverter_type && await autoCreateLineItem(currState.inverter_type, 1);
        currState.offgrid_inverter && await autoCreateLineItem(currState.offgrid_inverter, 1);
        currState.offgrid_batery && await autoCreateLineItem(currState.offgrid_batery, currState.offgrid_howmany);
        currState.offgrid_accessory1 && await autoCreateLineItem(currState.offgrid_accessory1, 1);
        currState.offgrid_accessory2 && await autoCreateLineItem(currState.offgrid_accessory2, 1);
        // Alway add this product: STCs
        await autoCreateLineItem("STCs", currState.number_stcs);
        // Calculate
        await calculatePrice(currState.total_panels, currState.total_kw, "off-grid");
    } catch(err) {
        console.log(err);
    } finally {
        // Hide loading
        setTimeout(function (){
            SUGAR.ajaxUI.hideLoadingPanel();
        }, 300);
    }
}

// .:nhantv:. Initable Offgrid
async function init_table_offgrid() {
    // Call API get Offgrid Product
    try{
        await $.ajax({
            url: '/index.php?entryPoint=APIGetOffGridProduct'
        }).then(function(result) {
            let dataJson = JSON.parse(result);
            // Set global var
            sol_panel = dataJson.panel_data;
            sol_inverter = dataJson.inverter_data;
            og_inverter = dataJson.og_inverter_data;
            og_battery = dataJson.battery_data;
            og_accessory = dataJson.accessory_data;
        });
    } catch (ex) {
        console.log(ex);
    }

    let offgrid_pricing_table   = $('<div id="offgrid_pricing_table" class="col-md-12 col-xs-12 col-sm-12 edit-view-row" style="margin-bottom: 20px;"></div>');
    let data = [
        ["Selected Option"
            ,"<input data-attr='1' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_1' style='margin-bottom:5px'> Option 1"
            ,"<input data-attr='2' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_2' style='margin-bottom:5px'> Option 2"
            ,"<input data-attr='3' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_3' style='margin-bottom:5px'> Option 3"
            ,"<input data-attr='4' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_4' style='margin-bottom:5px'> Option 4"
            ,"<input data-attr='5' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_5' style='margin-bottom:5px'> Option 5"
            ,"<input data-attr='6' type='checkbox' class='offgrid_option offgrid_pricing' name='offgrid_option' id='offgrid_option_6' style='margin-bottom:5px'> Option 6"],
        [""
            , "<button data-option ='1' id='clear_og_option_1' class='button default'>Clear Option 1</button>"
            , "<button data-option ='2' id='clear_og_option_2' class='button default'>Clear Option 2</button>"
            , "<button data-option ='3' id='clear_og_option_3' class='button default'>Clear Option 3</button>"
            , "<button data-option ='4' id='clear_og_option_4' class='button default'>Clear Option 4</button>"
            , "<button data-option ='5' id='clear_og_option_5' class='button default'>Clear Option 5</button>"
            , "<button data-option ='6' id='clear_og_option_6' class='button default'>Clear Option 6</button>"],
        ["Total kW:"
            , makeInputBox("total_og_kW_1 offgrid_pricing", "total_og_kW_1", true)
            , makeInputBox("total_og_kW_2 offgrid_pricing", "total_og_kW_2", true)
            , makeInputBox("total_og_kW_3 offgrid_pricing", "total_og_kW_3", true)
            , makeInputBox("total_og_kW_4 offgrid_pricing", "total_og_kW_4", true)
            , makeInputBox("total_og_kW_5 offgrid_pricing", "total_og_kW_5", true)
            , makeInputBox("total_og_kW_6 offgrid_pricing", "total_og_kW_6", true)],
        ["Panel Type"
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_1 offgrid_pricing", "panel_og_type_1")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_2 offgrid_pricing", "panel_og_type_2")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_3 offgrid_pricing", "panel_og_type_3")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_4 offgrid_pricing", "panel_og_type_4")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_5 offgrid_pricing", "panel_og_type_5")
            , makeSelectBox(convertJSONToArrayInit(sol_panel), "panel_og_type_6 offgrid_pricing", "panel_og_type_6")],
        ["Inverter Type"
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_og_type_1 offgrid_pricing", "inverter_og_type_1")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_og_type_2 offgrid_pricing", "inverter_og_type_2")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_og_type_3 offgrid_pricing", "inverter_og_type_3")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_og_type_4 offgrid_pricing", "inverter_og_type_4")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_og_type_5 offgrid_pricing", "inverter_og_type_5")
            , makeSelectBox(convertJSONToArrayInit(sol_inverter), "inverter_og_type_6 offgrid_pricing", "inverter_og_type_6")],
        ["Total Panels"
            , makeInputBox("total_og_panels_1 offgrid_pricing", "total_og_panels_1", false)
            , makeInputBox("total_og_panels_2 offgrid_pricing", "total_og_panels_2", false)
            , makeInputBox("total_og_panels_3 offgrid_pricing", "total_og_panels_3", false)
            , makeInputBox("total_og_panels_4 offgrid_pricing", "total_og_panels_4", false)
            , makeInputBox("total_og_panels_5 offgrid_pricing", "total_og_panels_5", false)
            , makeInputBox("total_og_panels_6 offgrid_pricing", "total_og_panels_6", false)],
        ["Number of STCs"
            , makeInputBox("number_og_stcs_1 offgrid_pricing", "number_og_stcs_1", true)
            , makeInputBox("number_og_stcs_2 offgrid_pricing", "number_og_stcs_2", true)
            , makeInputBox("number_og_stcs_3 offgrid_pricing", "number_og_stcs_3", true)
            , makeInputBox("number_og_stcs_4 offgrid_pricing", "number_og_stcs_4", true)
            , makeInputBox("number_og_stcs_5 offgrid_pricing", "number_og_stcs_5", true)
            , makeInputBox("number_og_stcs_6 offgrid_pricing", "number_og_stcs_6", true)],
        ["", "&nbsp;"],
        ["<button id='calculate_og' class='button default'>Max</button>", "&nbsp;"],
        ["", "&nbsp;"],
        ["Off-Grid Inverter:"
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_1")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_2")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_3")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_4")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_5")
            , makeSelectBox(convertJSONToArrayInit(og_inverter), "offgrid_inverter_1 offgrid_pricing", "offgrid_inverter_6")],
        ["Battery Storage:"
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_1 offgrid_pricing", "offgrid_batery_1",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_2 offgrid_pricing", "offgrid_batery_2",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_3 offgrid_pricing", "offgrid_batery_3",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_4 offgrid_pricing", "offgrid_batery_4",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_5 offgrid_pricing", "offgrid_batery_5",)
            , makeSelectBox(convertJSONToArrayInit(og_battery), "offgrid_batery_6 offgrid_pricing", "offgrid_batery_6",)],
        ["How Many Batteries:"
            , makeInputBox("offgrid_howmany_1 offgrid_pricing", "offgrid_howmany_1", false)
            , makeInputBox("offgrid_howmany_2 offgrid_pricing", "offgrid_howmany_2", false)
            , makeInputBox("offgrid_howmany_3 offgrid_pricing", "offgrid_howmany_3", false)
            , makeInputBox("offgrid_howmany_4 offgrid_pricing", "offgrid_howmany_4", false)
            , makeInputBox("offgrid_howmany_5 offgrid_pricing", "offgrid_howmany_5", false)
            , makeInputBox("offgrid_howmany_6 offgrid_pricing", "offgrid_howmany_6", false)],
        ["OG Accessory 1:"
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_1 offgrid_pricing", "offgrid_accessory1_1")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_2 offgrid_pricing", "offgrid_accessory1_2")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_3 offgrid_pricing", "offgrid_accessory1_3")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_4 offgrid_pricing", "offgrid_accessory1_4")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_5 offgrid_pricing", "offgrid_accessory1_5")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory1_6 offgrid_pricing", "offgrid_accessory1_6")],
        ["OG Accessory 2:"
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_1 offgrid_pricing", "offgrid_accessory2_1")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_2 offgrid_pricing", "offgrid_accessory2_2")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_3 offgrid_pricing", "offgrid_accessory2_3")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_4 offgrid_pricing", "offgrid_accessory2_4")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_5 offgrid_pricing", "offgrid_accessory2_5")
            , makeSelectBox(convertJSONToArrayInit(og_accessory), "offgrid_accessory2_6 offgrid_pricing", "offgrid_accessory2_6")],
    ];
    
    // .:nhantv:. Update to set order before "Save and Generate Quote" field
    $('body').find("#generate_quote").before(offgrid_pricing_table);

    makeTable(offgrid_pricing_table, data, "offgrid_pricing", "offgrid_pricing");
    //css Table
    $(".offgrid_pricing td").css({"padding":"0px 5px"});
    $(".offgrid_pricing th").css({"padding":"0px 5px"});
    $(".offgrid_pricing select, .offgrid_pricing input[class*='offgrid_pricing']:not([type='checkbox'])").css({"width":"100%"});

    // Load Off-Grid Option
    loadOffgridOption();
}

/**
 * VUT - load Options Own Solar PV Pricing
 */
function loadOwnOptionsPricing(){
    if($("#own_solar_pv_pricing_c").val()!=""){
        var json_val = JSON.parse(($("#own_solar_pv_pricing_c").val() != "")?$("#own_solar_pv_pricing_c").val():"{}");
        for (let key in json_val) {  
            $("#"+key).val(json_val[key]);
        }
    }
}
