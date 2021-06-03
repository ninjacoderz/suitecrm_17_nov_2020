$(document).ready(function() {
    'use strict';
// //for test
//     $('input[id="SAVE"]').next().after('&nbsp;<button type="button" id="test" class="button" >TEST<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
//     $(document).on('click', '#test', function(){
//         convertasbinaryimage(true);
//     });
// //for test
    
    //Hide field data JSON
    $(document).find('#map_data').closest('.edit-view-row-item').hide();
    if ($("input[name='record']").val() == '') {
        $(document).find('[field="installation_pictures_c"]').closest('.panel.panel-default').hide();
    }
    //init Geo Data
    // debugger
    if( $('#image_satellite').length == 0) {
        getGEOGoogle();
    } else {
        getGEOGoogle(1);
    }

    $(document).on('change', '#billing_address_street',  function(){
        getGEOGoogle();
    });

    $(document).on('change', '#billing_address_street, #billing_address_city, #billing_address_state, #billing_address_postalcode',  function(){
        $(document).find('#name').val(generateName());
    });


    $("#billing_address_street").autocomplete({
        source: function (request, response) {
            Math.floor((Math.random() * 3) + 1);

            if (request["term"].length > 3) {
                $.ajax({
                    url: "/index.php?entryPoint=customGetAddress&address=" + request["term"],
                    type: 'GET',
                    //async: false,
                    //crossDomain: true,
                    //dataType: 'jsonp',

                    success: function (data) {
                        if (data == '' || typeof data === 'undefined') return;
                        var suggest = [];
                        var jsonObject = $.parseJSON(data);
                        for (i = 1; i < jsonObject.length; i++) {
                            suggest.push(jsonObject[i].name);
                        }
                        console.log(jsonObject);
                        response(suggest);
                    },
                    error: function (response) { console.log("Fail"); },
                });
            }
            //console.log(response);
        },
        select: function (event, ui) {
            console.log(ui.item.value);
            var value = ui.item.value.split(",");
            var address1 = value[0];
            var address2 = value[1];

            $("#billing_address_street").val(address1);

            var address3 = address2.split("  ");

            $("#billing_address_city").val(address3[0].trim());
            $("#billing_address_state").val(address3[1].trim());
            $("#billing_address_postalcode").val(address3[2].trim());
            return false;
        }
    });

    //SAVE AND EDIT
    SUGAR.saveAndEdit = function (elem) {
        SUGAR.ajaxUI.showLoadingPanel();
        $("#EditView input[name='action']").val('Save');
        $.ajax({
            type: $("#EditView").attr('method'),
            url: $("#EditView").attr('action'),
            data: $("#EditView").serialize(),
            success: function (data) {
                if($("input[name='record']").val() == ''){
                    var record_id_patt = /"record" value="(.*)"/g;
                    var records = record_id_patt.exec(data);
                    if(records !== null && typeof records === 'object'){
                        if(records[1] !='')  {
                            window.onbeforeunload = null;
                            window.onunload = null;
                            window.addEventListener('beforeunload', function(e) {
                                window.onbeforeunload = null;
                                window.onunload = null;
                            });
                            var url = 'https://suitecrm.pure-electric.com.au';
                            // var url = 'http://locsuitecrm.com/';
                            window.location.href = url+"index.php?module="+module_sugar_grp1+"&action=EditView&record="+records[1];
                        }
                    }
                    return false;
                }
                $(".reload_after_rename").trigger("click");
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        });
        return false;
    }

}); //end $(document).ready


//////////////////////////////////***DECLARE FUNCTIONS***//////////////////////////////////////


function generateName() {
    let fulladdress = `${$('#billing_address_street').val()}, ${$('#billing_address_city').val()}, ${$('#billing_address_state').val()}, ${$('#billing_address_postalcode').val()}`;
    return fulladdress;
}

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
    $(document).find('#map_data').val(JSON.stringify(dataGEO));
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
                url: "index.php?entryPoint=googleForAddress", 
                data: { data: img, id: record_id, module: module_sugar_grp1}      
                }).done(function(data){
                    // console.log();
                });

         }
    });
}