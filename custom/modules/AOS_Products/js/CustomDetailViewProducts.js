$(function () {
    'use strict';
    $( document ).ready(function() {
        $('#accounts_aos_products_1accounts_ida').closest('.detail-view-row-item').find('.col-sm-4.label').html('<br>Product Brand:');
        // .:nhantv:. Hide Solar category and Capacity field
        dynamicSolarCategory();
        dynamicCapacityLabel();
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