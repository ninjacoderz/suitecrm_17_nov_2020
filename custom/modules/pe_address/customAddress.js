$(document).ready(function() {
//for test
    // $('input[id="SAVE"]').next().after('&nbsp;<button type="button" id="test" class="button" >TEST<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
    // $(document).on('click', '#test', function(){
    //     var fulladdress = `${encodeURIComponent($('#billing_address_street').val())}, ${encodeURIComponent($('#billing_address_city').val())}, ${encodeURIComponent($('#billing_address_state').val())}, ${encodeURIComponent($('#billing_address_postalcode').val())}`;
    //     var geoCode = getGEOGoogle(fulladdress);
    //     if (geoCode != '') {
    //         $(document).find('#map_data').val(JSON.stringify(geoCode));
    //     }
    // });
//for test
    //Hide field data JSON
    // $(document).find('#map_data').closest('.edit-view-row-item').hide();
    if($('#map_data').val() == '') {
       var geoData = getGeoData();
       if (geoData != '') {
        $(document).find('#map_data').val(JSON.stringify(geoData));
       }
    }
});

/**
 * DECLARE FUNCTIONS
 */

function getGeoData() {
    let fulladdress = `${encodeURIComponent($('#billing_address_street').val())}, ${encodeURIComponent($('#billing_address_city').val())}, ${encodeURIComponent($('#billing_address_state').val())}, ${encodeURIComponent($('#billing_address_postalcode').val())}`;
    let geoCode = getGEOGoogle(fulladdress);
    if (geoCode != '') {
        return geoCode;
    } else {
        return '';
    }
}
/**
 * Get Geo from Google
 * @param {string} fulladdress = "street, city, state, postcode"
 */
function getGEOGoogle(fulladdress) {
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
        return dataGEO;
    }

}