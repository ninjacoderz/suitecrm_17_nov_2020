$(function () {
    'use strict';
    //INIT
    $(document).find('#own_solar_pv_pricing_c').attr('readonly', 'readonly');
    init_table_own_solar();
    getDataProductSolar();

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

    if (quote_type == 'quote_type_solar') {
        if (own_solar != '') {
            getOwnSolarPricing(own_solar);
        } else if (solar_input != '') {
            getOwnSolarPricing(solar_input, 'solar_input');
        }
    }

});


//DECLARE FUNCTIONS
/**
 * VUT - get All Data Product Solar (panel + inverter + std solar install)
 */
function getDataProductSolar() {
    let panel_type = [
        'Jinko 370W Cheetah Plus JKM370M-66H',
        'Q CELLS Q.MAXX-G2 350W',
        /*'Longi Hi-MO X 350W''Q CELLS Q.MAXX 330W''Q CELLS Q.PEAK DUO G6+ 350W',*/
        'Sunpower P3 325 BLACK',
        /*'Sunpower X22 360W',*/
        'Sunpower Maxeon 3 400W',
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
    let own_solar_pv_pricing_table   = $("<div id='own_solar_pv_pricing_table'></div>");
    
    let data = [
        ["", 'Option 1', 'Option 2', 'Option 3', 'Option 4', 'Option 5', 'Option 6'],
        ["Total kW:", makeInputBox("own_totalkW_1 own_solar_pv_pricing", "own_totalkW_1", true), makeInputBox("own_totalkW_2 own_solar_pv_pricing", "own_totalkW_2", true), makeInputBox("own_totalkW_3 own_solar_pv_pricing", "own_totalkW_3",true), makeInputBox("own_totalkW_4 own_solar_pv_pricing", "own_totalkW_4",true), makeInputBox("own_totalkW_5 own_solar_pv_pricing", "own_totalkW_5",true), makeInputBox("own_totalkW_6 own_solar_pv_pricing", "own_totalkW_6",true)],
        ["Panel Type:", makeInputBox("own_panelType_1 own_solar_pv_pricing", "own_panelType_1", true), makeInputBox("own_panelType_2 own_solar_pv_pricing", "own_panelType_2", true), makeInputBox("own_panelType_3 own_solar_pv_pricing", "own_panelType_3", true), makeInputBox("own_panelType_4 own_solar_pv_pricing", "own_panelType_4", true), makeInputBox("own_panelType_5 own_solar_pv_pricing", "own_panelType_5", true), makeInputBox("own_panelType_6 own_solar_pv_pricing", "own_panelType_6", true)],
        ["Inverter Type:", makeInputBox("own_inverterType_1 own_solar_pv_pricing", "own_inverterType_1", true), makeInputBox("own_inverterType_2 own_solar_pv_pricing", "own_inverterType_2", true), makeInputBox("own_inverterType_3 own_solar_pv_pricing", "own_inverterType_3", true), makeInputBox("own_inverterType_4 own_solar_pv_pricing", "own_inverterType_4", true), makeInputBox("own_inverterType_5 own_solar_pv_pricing", "own_inverterType_5", true), makeInputBox("own_inverterType_6 own_solar_pv_pricing", "own_inverterType_6", true)],
        // ["Panel Type:", makeSelectBox(panel_type,"own_panelType_1 own_solar_pv_pricing", "own_panelType_1"), makeSelectBox(panel_type,"own_panelType_2 own_solar_pv_pricing", "own_panelType_2"), makeSelectBox(panel_type,"own_panelType_3 own_solar_pv_pricing", "own_panelType_3"), makeSelectBox(panel_type,"own_panelType_4 own_solar_pv_pricing", "own_panelType_4"), makeSelectBox(panel_type,"own_panelType_5 own_solar_pv_pricing", "own_panelType_5"), makeSelectBox(panel_type,"own_panelType_6 own_solar_pv_pricing", "own_panelType_6")],
        // ["Inverter Type:", makeSelectBox(inverter_type,"own_inverterType_1 own_solar_pv_pricing", "own_inverterType_1"), makeSelectBox(inverter_type,"own_inverterType_2 own_solar_pv_pricing", "own_inverterType_2"), makeSelectBox(inverter_type,"own_inverterType_3 own_solar_pv_pricing", "own_inverterType_3"), makeSelectBox(inverter_type,"own_inverterType_4 own_solar_pv_pricing", "own_inverterType_4"), makeSelectBox(inverter_type,"own_inverterType_5 own_solar_pv_pricing", "own_inverterType_5"), makeSelectBox(inverter_type,"own_inverterType_6 own_solar_pv_pricing", "own_inverterType_6")],
        ["Total Panels:", makeInputBox("own_totalPanels_1 own_solar_pv_pricing","own_totalPanels_1", true), makeInputBox("own_totalPanels_2 own_solar_pv_pricing", "own_totalPanels_2", true), makeInputBox("own_totalPanels_3 own_solar_pv_pricing", "own_totalPanels_3", true), makeInputBox("own_totalPanels_4 own_solar_pv_pricing", "own_totalPanels_4", true), makeInputBox("own_totalPanels_5 own_solar_pv_pricing", "own_totalPanels_5", true), makeInputBox("own_totalPanels_6 own_solar_pv_pricing", "own_totalPanels_6", true)],
        ["Base Price:", makeInputBox("own_basePrice_1 own_solar_pv_pricing", "own_basePrice_1", true), makeInputBox("own_basePrice_2 own_solar_pv_pricing", "own_basePrice_2", true), makeInputBox("own_basePrice_3 own_solar_pv_pricing", "own_basePrice_3", true), makeInputBox("own_basePrice_4 own_solar_pv_pricing", "own_basePrice_4", true), makeInputBox("own_basePrice_5 own_solar_pv_pricing", "own_basePrice_5", true), makeInputBox("own_basePrice_6 own_solar_pv_pricing", "own_basePrice_6", true)],
    ];
    //select Panel OWN SOLAR
    let selector_panel_own_solar='';
    $('.panel-content .panel-default').each(function(){
        let title_panel_default = $(this).find('.panel-heading a div').text().toLowerCase().trim();
        if(title_panel_default == 'own solar'){
            selector_panel_own_solar = '#' + $(this).find('.panel-body').attr('id');
        }
    });

    $(selector_panel_own_solar).find(".tab-content").html(own_solar_pv_pricing_table);
    makeTable(own_solar_pv_pricing_table, data, "Own-Solar-PV-Pricing", "Own-Solar-PV-Pricing");
    //css Table
    $(".Own-Solar-PV-Pricing td").css({"padding":"0px 5px"});
    $(".Own-Solar-PV-Pricing th").css({"text-align":"center"});
} //end function init_table_own_solar

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
