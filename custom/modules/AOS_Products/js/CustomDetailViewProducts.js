$(function () {
    'use strict';
    $( document ).ready(function() {
        $('#accounts_aos_products_1accounts_ida').closest('.detail-view-row-item').find('.col-sm-4.label').html('<br>Product Brand:');
        // .:nhantv:. Hide Solar category and Capacity field
        dynamicSolarCategory();
        dynamicCapacityLabel();
        //VUT - S - Button create Product Price
        $('#tab-actions').parent().append('<li><input type="button" name="Add New Product Price" value="Add New Product Price" id="add_new_product_price" class="button primary"/></li>'); 
        $(document).on('click', '#add_new_product_price', function() {
            var parent_id = $("input[name='record']").val();
            $.ajax({
                url: "index.php?entryPoint=createProductPrice&parent_id=" +parent_id,
                success:function (data) {
                    if(data != 'error'){
                        window.open('/index.php?module=pe_product_prices&action=EditView&record='+data.trim(),'_blank');
                    }else{
                        alert('Create fail!');
                    }
                }
            })
        });
        //VUT - E - Button create Product Price
    });

});
function dynamicSolarCategory(){
    let productCategoryValue = $("#aos_product_category_id").text();
    // if (productCategoryValue != "Solar Panels" && productCategoryValue != "Solar") {
    if ($.inArray(productCategoryValue, ["Solar Panels", "Solar", "Microgrid"]) == -1) {
        $("#solar_category_c").parent().parent().css("display", "none");
    } else {
        $("#solar_category_c").parent().parent().attr('style','');
    }
    if ($.inArray(productCategoryValue, ["Solar"]) != -1 && $('#solar_category_c').val() == 'solar_panels') {
        $("#module_efficiency").parent().parent().attr('style','');
        $("#warranty").parent().parent().attr('style','');
    } else {
        $("#module_efficiency").parent().parent().css("display", "none");
        $("#warranty").parent().parent().css("display", "none");
    }
}

// .:nhantv:. Dynamic label Capacity
function dynamicCapacityLabel(){
    let productCategoryValue = $("#aos_product_category_id").text();
    let solarCategoryValue = $("#solar_category_c").val();
    let labelCapacity = $("#capacity_c").parent().parent().find('div.label');

    if ($.inArray(productCategoryValue, ["Solar Panels", "Solar", "Microgrid"]) != -1) {
        switch (solarCategoryValue) {
            case "solar_panels":
                labelCapacity.text("\nSolar PV Module Capacity (W)");
                break;
            case "inverters":
            case "og_inverters":
                labelCapacity.text("\nSolar Inverter Capacity (kW)");
                break;
            case "battery_storage":
                labelCapacity.text("\nBattery Storage Capacity (kWh)");
                break;
            default: 
                labelCapacity.text("\nCapacity");
                break;
        }
    } else {
        labelCapacity.text("\nCapacity");
    }
    
    return;
}