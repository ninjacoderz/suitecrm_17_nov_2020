$(document).ready(function() {
    'use strict';
        //init Geo Data
        // debugger
        // if( $('#satellite_view_span').length == 0) {
        //     getGEOGoogle();
        // } else {
        //     getGEOGoogle(1);
        // }
    
    
}); //end $(document).ready
    
    
//////////////////////////////////***DECLARE FUNCTIONS***//////////////////////////////////////
/**
 * Get Geo from Google
 */
function getGEOGoogle(hasImg = 0) {
    let fulladdress = `${encodeURIComponent($('#billing_address_street').val())}, ${encodeURIComponent($('#billing_address_city').val())}, ${encodeURIComponent($('#billing_address_state').val())}, ${encodeURIComponent($('#billing_address_postalcode').val())}`;
    let dataGEO = new Object();
    $.ajax({
        url: `https://maps.googleapis.com/maps/api/geocode/json?address=${fulladdress}&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo`,
        type: 'GET',
        async: false,
        success: function (result) {
            if (result.status == "OK") {
                dataGEO.location = result.results[0].geometry.location;
                dataGEO.place_id = result.results[0].place_id;
            } 
        }
    });
    if($.isEmptyObject(dataGEO)) {
        return '';
    } else {
        showStreetView(dataGEO, hasImg);
    }

}
/**
 * show Street View Google
 * @param {Object} data 
 */
function showStreetView(data,hasImg) {
    let street_view_url = `https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=${data.location.lat},${data.location.lng}`;
    let street_view = `<iframe id="street-view-google" src="${street_view_url}" height="300" title="Street View"></iframe>`;
    $(document).find('#street_view_span').empty().append(street_view);
    if (hasImg == 0) {
        $.ajax({
            url: "index.php?entryPoint=Image_Site_Details_Get_From_Google&lat="
            +  data.location.lat
            + "&lng="  + data.location.lng,
            type: 'GET',
            async: false,
            success: function(result) {
                $("#satellite_view_span").empty().append(result);
            }
        });
        setTimeout(function () {
            convertasbinaryimage();
        }, 3000);
    }
}

function convertasbinaryimage() {
    if($(document).find('#map').length == 0)return;
    html2canvas(document.getElementById("map"), {
        useCORS: true,
        onrendered: function (canvas) {
            let img = canvas.toDataURL("image/png");
            $('#satellite_view_span').empty().append('<img id="image_satellite" src="'+img+'"/>');
            // let generateUUID = $('input[name="installation_pictures_c"]').val();
            let record_id = $('input[name="record"]').val();
            $.ajax({
                type: "POST", 
                async: false,
                url: "index.php?entryPoint=readGoogleSheet", 
                data: { data: img, id: record_id, module: module_sugar_grp1}      
                }).done(function(data){
                    // console.log();
                });

            }
    });
}