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

    //Button NMI 
    $("#nmi").after('<br><button class="button primary" id="getnmi"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get NMI </button>');
    $("#getnmi").after('<button class="button primary" id="checkNMI"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Check NMI </button>');
    $('#checkNMI').after('<div id="text_check_nmi"></div>');
    $('#getnmi').on('click', function (event) {
        get_number_NMI();
        return false;
    });
    $('#checkNMI').on('click', function () {
        var nmi_number = $('#nmi').val();
        if (nmi_number == '') {
            alert('We have not NMI Number.');
            return false;
        } else {
            $('#checkNMI span.glyphicon-refresh').removeClass('hidden');
            $.ajax({
                url: "/index.php?entryPoint=CustomCheckNumberNMI&nmi_c=" + nmi_number,
                type: 'GET',
                success: function (data) {
                    if (data == '' || typeof data === 'undefined') return;
                    var data_json = $.parseJSON(data);
                    if (data_json['Quote For'] == null && data_json['Network Distributor'] == null && data_json['NMI'] == null) {
                        var html_append = '<p>* Number Meter Wrong *</p>';
                        $('#text_check_nmi').empty();
                        $('#text_check_nmi').append(html_append);
                    } else {
                        var html_append = '';
                        html_append += '<p>*Address : ' + data_json['Quote For'] + '</p>';
                        html_append += '<p>*Network Distributor : ' + data_json['Network Distributor'] + '</p>';
                        html_append += '<p>*NMI : ' + data_json['NMI'] + '</p>';
                        $('#address_nmi').val(data_json['Quote For']);
                        $('#text_check_nmi').empty();
                        $('#text_check_nmi').append(html_append);
                    }
                    $('#checkNMI span.glyphicon-refresh').addClass('hidden');
                },
            })
        }
        return false;
    })
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
    let fulladdress = `${$('#billing_address_street').val()} ${$('#billing_address_city').val()} ${$('#billing_address_state').val()} ${$('#billing_address_postalcode').val()}`;
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
        dataGEO.data_img = '';
        if (hasImg == 0) {
            SUGAR.ajaxUI.showLoadingPanel();
            setTimeout(function () {
                let promises = [];
                promises.push(convertasbinaryimage());
                Promise.all(promises).then(responseList => {
                    setTimeout(function() {
                        // debugger;
                        let record_id = $('input[name="record"]').val();
                        let data_img = $(document).find('#image_satellite').attr('src');
                        if (data_img != '' && typeof data_img != 'undefined' && record_id == '') {
                            dataGEO.data_img = data_img;
                            $(document).find('#map_data').val(JSON.stringify(dataGEO));
                            SUGAR.ajaxUI.hideLoadingPanel();
                            return;
                        }
                        SUGAR.ajaxUI.hideLoadingPanel();
                    }, 1000);
                    
                });
            }, 3000);
        }
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
        // setTimeout(function () {
        //     convertasbinaryimage();
        // }, 3000);
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
            if (record != '') {
                $.ajax({
                    type: "POST", 
                    async: false,
                    url: "index.php?entryPoint=googleForAddress", 
                    data: { data: img, id: record_id, module: module_sugar_grp1}      
                    }).done(function(data){
                        // console.log();
                });
            }
         }
    });
}
/**
 * Copy from Quote custom\modules\AOS_Quotes\CustomQuotes.js
 */
function get_number_NMI() {
    $('#getnmi span.glyphicon-refresh').removeClass('hidden');
    if ($("#billing_address_street").val() == '' && $("#billing_address_city").val() == ''
        && $("#billing_address_state").val() == '' && $("#billing_address_postalcode").val() == '') {
        alert('Could you enter "Address" please ?');
        $("#billing_address_street").focus();
        return false;
    }
    //thien fix for get nmi
    var address = $("#billing_address_street").val() + ',' +
        $("#billing_address_city").val() + ' ' +
        $("#billing_address_state").val() + ' ' +
        $("#billing_address_postalcode").val();

    var value = address.split(",");
    var valueLen = value.length;
    var address1 = value[0];
    for (var i = 1; i < valueLen - 1; i++) {
        address1 = address1 + value[i];
    }
    var address2 = value[valueLen - 1].trim();

    var address3 = address2.split(" ");

    var address1Items = address1.split(",");
    var address1Len = address1Items.length;
    var addarr = address1Items[address1Len - 1].trim().split(" ");
    var a_first_addres = "";
    var unit = "";
    var unit_num = "";
    var address_number = "";
    var address_name = "";
    if (addarr.length == 2) {
        //a_first_addres = addarr[0].replace("Unit","U")+ "/";
        //a_first_addres += addarr[1].replace(/ /,"/");

        // Unit param
        var unit_numbers = addarr[0].split(" ");
        unit = unit_numbers[0];
        unit_num = unit_numbers[1];
        var address_numbers = addarr[1].split(" ");
        address_number = address_numbers[0];
        address_name = address_numbers[1].replace(" ", "+");

    }
    else {
        //a_first_addres  = "NA/"
        //a_first_addres +=  address1.replace(/ /,"/");

        var address_numbers = addarr;
        address_number = address_numbers[0];
        address_name = address_numbers[1].replace(" ", "+");
    }
    var requestString; //= a_first_addres + "/" + address3[0].trim() +"/"+ address3[1].trim() +"/"+ address3[2].trim();
    //var street = explode()
    requestString = encodeURIComponent("unit=" + unit + "/unit_num=" + unit_num + "/streetNumber=" + address_number + "/streetName=" + address_name + "/city=" + address3[0] + "/state=" + address3[1] + "/customerType=residential/searchByPostcode=false/postcode=" + address3[2] + "/fuelType=dual&hasSolarPanels=false/connectionScenario=PROS_SWT");

    $.ajax({
        url: "/index.php?entryPoint=customGetRetailer&address=" + address + "&momentumenergy=1&requestString=" + requestString,
        type: 'GET',
        async: false,
        success: function (data) {
            if (data.indexOf("ChooseExactMeter_Nmis_0__MeterNumber") >= 0) {
                $(".modal_nmi").remove();
                var html = '<div class="modal fade modal_nmi" tabindex="-1" role="dialog">' +
                    '<div class="modal-dialog">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>' +
                    '<h4 class="modal-title" id="title-generic">Select NMI</h4>' +
                    '</div>' +
                    '<div class="modal-body">' +
                    '<div class="container-fluid" style="margin-left:30px;">' + data +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                $("body").append(html);
                $(".modal_nmi").modal('show');
                $(".modal_nmi").find("input").click(function () {
                    $("#nmi").val($(this).val());
                    $("#address_nmi").val($("#ChooseExactMeter_Nmis_" + $(this).index() + "__Address").val());
                    $(".modal_nmi").modal('hide');
                })
            } else {
                $("#nmi").val(data);
                if (data !== "") {
                    getNMI();
                }
                if (data == '') {
                    var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
                    if (ok)
                        window.open('https://www.momentumenergy.com.au/', '_blank');
                    $("#nmi").val('');
                    $("#address_nmi").val('');
                }
            }
            $('#getnmi span.glyphicon-refresh').addClass('hidden');
        },

        error: function (response) {
            $('#getnmi span.glyphicon-refresh').addClass('hidden');

            var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
            if (ok)
                window.open('https://www.momentumenergy.com.au/', '_blank');
            $("#nmi").val('');
            $("#address_nmi").val('');
        },
    });

}

/**
 * Copy from Quote custom\modules\AOS_Quotes\CustomQuotes.js
 */ 
 function getNMI() {
    var nmi = $("#nmi").val();
    if (parseInt(nmi) != nmi) {
        $("#nmi").val('');
        $("#address_nmi").val('');
        alert("Invalid NMI!");
        return false;
    }

    // $('#getDistributor span.glyphicon-refresh').removeClass('hidden');

    nmi = parseInt(nmi);

    var NSP = [
        {
            name: "Citipower",
            value: 4,
            range: [{ min: 6102000000, max: 6103999999 }]
        },
        {
            name: "Jemena",
            value: 5,
            range: [{ min: 6001000000, max: 6001999999 }]
        },
        {
            name: "Powercor",
            value: 6,
            range: [{ min: 6203000000, max: 6204999999 }]
        },
        {
            name: "Ausnet",
            value: 7,
            range: [{ min: 6305000000, max: 6306999999 },
            { min: 6509000000, max: 6509009999 }]
        },
        {
            value: "United",
            value: 8,
            range: [{ min: 6407000000, max: 6408999999 }]
        },
        {
            name: "Western Power",
            value: 1,
            range: [{ min: 8001000000, max: 8020999999 }]
        },
        {
            name: "SA Power Networks - NSP",
            value: 13,
            range: [{ min: 2001000000, max: 2002999999 }]
        },
        {
            name: "Energex",
            value: 2,
            range: [{ min: 3100000000, max: 3199999999 }]
        },
        {
            name: "Ergon",
            value: 3,
            range: [{ min: 3000000000, max: 3099999999 }]
        },
        {
            name: "Essential Energy",
            value: 9,
            range: [{ min: 4001000000, max: 4001999999 }, { min: 4508000000, max: 4508099999 },
            { min: 4204000000, max: 4204999999 }, { min: 4407000000, max: 4407999999 }]
        },
        {
            name: "Ausgrid",
            value: 10,
            range: [{ min: 4102000000, max: 4104999999 }]
        },
        {
            name: "Endeavour Energy",
            value: 12,
            range: [{ min: 4310000000, max: 4319999999 }]
        },
        {
            name: "ActewAGL",
            value: 11,
            range: [{ min: 7001000000, max: 7001999999 }]
        },
    ];

    var NSPLen = NSP.length;
    for (var i = 0; i < NSPLen; i++) {
        var range = NSP[i].range
        var rangeLen = range.length;
        for (var j = 0; j < rangeLen; j++) {
            if ((nmi >= range[j].min && nmi <= range[j].max) ||
                (nmi >= range[j].min * 10 && nmi <= range[j].max * 10 + 9)) {
                $("#electricity_distributor").val(NSP[i].value);
                // //thien fix show Ausnet_Approval button
                // if (NSP[i].value == 7) {
                //     $('#Ausnet_Approval').show();
                // } else {
                //     $('#Ausnet_Approval').hide();
                // }

                // //thien fix show register jemena button
                // if (NSP[i].value == 5) {
                //     $('#register_jemena_account').show();
                // } else {
                //     $('#register_jemena_account').hide();
                // }
                // $('#getDistributor span.glyphicon-refresh').addClass('hidden');

                return false;
            }
        }
    }

    // $('#getDistributor span.glyphicon-refresh').addClass('hidden');

    return false;
}
