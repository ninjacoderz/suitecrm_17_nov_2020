$(function () {
    'use strict';
    $("#sanden_option_c").closest('.edit-view-row-item').hide();
    $("#get_stc_veec").closest('tr').hide();
    //INIT
    init_table_sanden('pricingOption');

    /** Extra Add Button Click handle */ 
    $(document).on('click', '#sd_tank_add, #sd_complete_add, #sd_accessory_add, #sd_extra_add', function(e){
        e.preventDefault();
        let attr_id = $(e.target).attr('id');
        if (attr_id.indexOf('sd_complete') != -1) {
            SD_createNewLine('sd_complete');
        } else if (attr_id.indexOf('sd_tank') != -1)  {
            SD_createNewLine('sd_tank');
        } else if (attr_id.indexOf('sd_accessory') != -1) {
            SD_createNewLine('sd_accessory');
        } else {
            SD_createNewLine('sd_extra');
        }
    });

    // Calculate Price Button Click handle 
    $(document).on('click', '#sd_calculate_price', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
                SD_calcOption(i);
        }
        SD_calcHint();
    });

    $(document).on('click', '#sd_show_hint', function(e){
        e.preventDefault();
        $('#sd_hint').toggle();
    });
    // $(document).on('click', '#sd_show_table', function(e){
    //     e.preventDefault();
    //     $('#sanden_pricing_table').toggle();
    // });

    // $(document).on('click', '#generate_table', function(e){
    //     e.preventDefault();
    //     let productType = $("#quote_type_c").val();
    //     switch (productType) {
    //         case "quote_type_sanden":
    //             SD_generateLineItem();
    //         default:
    //             break;
    //     }
    // });
    

    /** Clear sl button */
    $(document).on('click', '*[id*="sd_clear_option"]', function(e){
        e.preventDefault();
        SD_clearOption($(this).data('option'));
        for (var i = 1; i < 7; i++) {
            SD_calcOption(i);
        }

    });

    //PE Admin % handle 
    $(document).on('change', '#sd_pe_admin_percent', function(e){
        e.preventDefault();
        for (var i = 1; i < 7; i++) {
            SD_calcOption(i);
        }
    });

    // Solar Checkbox handle 
    $(document).on('change', 'input[id*="sanden_option"]', function(){
        checkBoxOptionHandle($(this), "sanden_option");
    });

    $(document).on("change", "select[id*='sd_extra_type'], select[id*='sd_complete_type'], input[id*='qty_sd_complete'], input[id*='pmsd_'], select[id*='sd_install_'], input[id*='qty_ext_sd_extra'], input[id*='price_ext_sd_extra'], select[id*='sd_hpump_type'], input[id*='qty_sd_hpump'], select[id*='sd_tank_type'], input[id*='qty_sd_tank'], select[id*='sd_accessory_type'], input[id*='qty_sd_accessory']", function(e){
        var index  = $(this).attr("id").split('_');
        let item_no = $(this).attr('id').charAt($(this).attr('id').length-3);
        let selector = '', type = '', qty_id ='';
        let num_of_line = 1;
        let value_selected = $(this).val();
        index = index[index.length -1];
        
        if ($(this).attr('id').indexOf('sd_complete_type') != -1) {
            selector = 'sd_complete_type';
            num_of_line = SD_getCountLine('sd_complete');
            type = 'Complete';
            qty_id = 'qty_sd_complete';
        }
        if ($(this).attr('id').indexOf('sd_tank_type') != -1) {
            selector = 'sd_tank_type';
            num_of_line = SD_getCountLine('sd_tank');
            type = 'Tank';
            qty_id = 'qty_sd_tank';
        }
        if ($(this).attr('id').indexOf('sd_accessory_type') != -1) {
            selector = 'sd_accessory_type';
            num_of_line = SD_getCountLine('sd_accessory');
            type = 'Accessory';
            qty_id = 'qty_sd_accessory';
        }

        if ($(this).attr('id').indexOf('sd_extra_type') != -1) {
            selector = 'sd_extra_type';
            num_of_line = SD_getCountLine('sd_extra');
            type = 'extra';
            qty_id = 'qty_ext_sd_extra';
            //get cost extra fill to price extra
            $(`#price_ext_sd_extra${item_no}_${index}`).val(getAttributeFromPartNumber($(this).val(), sanden_extra, 'cost') == null ? '' : parseFloat(getAttributeFromPartNumber($(this).val(), sanden_extra, 'cost')) );
        }
        if (selector != '') {
            if (value_selected == '') {
                    $(`#${qty_id}${item_no}_${index}`).val('');
            } else {
                alertExist(selector, num_of_line, index, value_selected, type, item_no);
            }
        }
        SD_calcOption(index);
    });

    $(document).on('change', '.recom_sd_option', function(e){
        SD_saveCurrentState();
    });
});

//*************************FUNCTION DECLARE******************* */
Number.prototype.formatMoney = function (decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator, z
    thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};
