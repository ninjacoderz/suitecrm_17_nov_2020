function sendEmailToAdmin() {
    $('#sendMailToAdmin span.glyphicon-refresh').removeClass('hidden');
    var _url = "?entryPoint=customLeadSendEmailToAdmin&record_id=" 
                + $('input[name="record"]').val()
                + "&primary_address_street=" + $("#primary_address_street").val() 
                + "&primary_address_city=" + $("#primary_address_city").val() 
                + "&primary_address_state=" + $("#primary_address_state").val() 
                + "&primary_address_postalcode=" + $("#primary_address_postalcode").val();

    $.ajax({
        url: _url,
        type: 'GET',

    }).done(function (data) {
        $('#sendMailToAdmin span.glyphicon-refresh').addClass('hidden');
    });
}

function sendEmailDesignsComplete() {
    $('#sendDesignsComplete span.glyphicon-refresh').removeClass('hidden');

    var _url = "?entryPoint=customLeadSendDesignsComplete&record_id=" 
    + $('input[name="record"]').val()
    + "&assigned_user_id=" + $('input[name="assigned_user_id"]').val()
    + "&assigned_user_name=" + $('input[name="assigned_user_name"]').val()
    + "&primary_address_street=" + $("#primary_address_street").val() 
    + "&primary_address_city=" + $("#primary_address_city").val() 
    + "&primary_address_state=" + $("#primary_address_state").val() 
    + "&primary_address_postalcode=" + $("#primary_address_postalcode").val();
    
    
    $.ajax({
        url: _url,
        type: 'GET'
        }).done(function (data) {
        $('#sendDesignsComplete span.glyphicon-refresh').addClass('hidden');
        });
    }

function addOpenMapView() {
    var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyDivGJgXZLmPCBasgRYU-FlZBxsKIUMFts&q=' + $("#primary_address_street").val() +", " + $("#primary_address_city").val() + ", " + $("#primary_address_state").val() + ", " + $("#primary_address_postalcode").val(); //26 Walsh Ave, St Marys SA 5042, Úc;

    $("#primary_address_country").after('<br><a id="open_map" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a>');
    $(".various").fancybox({
        maxWidth	: 800,
        maxHeight	: 600,
        fitToView	: false,
        width		: '70%',
        height		: '70%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });

    $.ajax({
        url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + $("#primary_address_street").val() +", " + $("#primary_address_city").val() + ", " + $("#primary_address_state").val() + ", " + $("#primary_address_postalcode").val() + '&key=AIzaSyDivGJgXZLmPCBasgRYU-FlZBxsKIUMFts',
        type: 'GET',
        success: function(result) {
            if (result.status != "OK")
                return;

            var location = result.results[0].geometry.location;

            $.ajax({
                url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyDivGJgXZLmPCBasgRYU-FlZBxsKIUMFts&size=600x300&location=' + location.lat + ',' + location.lng,
                type: 'GET',
                success: function(result) {
                    console.log(result.status);
                    if (result.status == "OK") {
                        var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyDivGJgXZLmPCBasgRYU-FlZBxsKIUMFts&location=' + location.lat + ',' + location.lng;
                        $("#open_map").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                    }
                    else {
                        var astorPlace = new google.maps.LatLng(location.lat, location.lng);
                        var webService = new google.maps.StreetViewService();  
                        var checkaround = 500;
                        
                        webService.getPanoramaByLocation(astorPlace, checkaround, checkNearestStreetView);
                        
                        function checkNearestStreetView(panoData){
                            if(panoData) {
                                 if(panoData.location) {
                                    if(panoData.location.latLng) {
                                        var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyDivGJgXZLmPCBasgRYU-FlZBxsKIUMFts&pano=' + panoData.location.pano;
                                        $("#open_map").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
                                    }
                                }
                            }
                        }            
                    }
                }
            });
        }
    });
}

$(function () {
    'use strict';
    // Generate uinique id

    SolarGainQuoteNumberLink();

    $('#solargain_quote_number_c').change(function () {
        SolarGainQuoteNumberLink();
    });

    function SolarGainQuoteNumberLink() {
        if ($('#solargain_quote_number_c').val() == "") return;
        var href = "<div class='open-solargain-quote-number'>Open SolarGain Quote <a target='_blank' href='https://crm.solargain.com.au/quote/edit/" + $('#solargain_quote_number_c').val()+"'>https://crm.solargain.com.au/quote/edit/" + $('#solargain_quote_number_c').val() + "</a></div>";
        $('#solargain_quote_number_c').siblings().empty();
        $('#solargain_quote_number_c').parent().append(href);
    }

    SolarGainLeadNumberLink();

    $('#solargain_lead_number_c').change(function () {
        SolarGainLeadNumberLink();
    });

    function SolarGainLeadNumberLink() {
        if ($('#solargain_lead_number_c').val() == "") return;
        var href = "<div class='open-solargain--number'>Open SolarGain Lead <a target='_blank' href='https://crm.solargain.com.au/lead/edit/" + $('#solargain_lead_number_c').val()+"'>https://crm.solargain.com.au/lead/edit/" + $('#solargain_lead_number_c').val() + "</a></div>";
        $('#solargain_lead_number_c').siblings().empty();
        $('#solargain_lead_number_c').parent().append(href);
    }
    // First name last name move out

    $("#last_name").change(function(){
        var name = $("#first_name").val()+ " " + $("#last_name").val();
        $("#EditView_account_name").val(name);
        return true;
    });

    $("#first_name").change(function(){
        var name = $("#first_name").val()+ " " + $("#last_name").val();
        $("#EditView_account_name").val(name);
        return true;
    });
    var leadID = 0;

    function generateUUID() {
        var d = new Date().getTime();
        if (window.performance && typeof window.performance.now === "function") {
            d += performance.now();
            ; //use high-precision timer if available
        }
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
        return uuid;
    };
    
    $( document ).ready(function() {
        // Autocomplete search
        

        if ($('input[name="installation_pictures_c"]').val() == "") {
            $('input[name="installation_pictures_c"]').val(generateUUID());
        }

        $("#CANCEL").after(
            '&nbsp;<button type="button" id="save_and_edit" class="button saveAndEdit" title="Save and Edit" onClick="SUGAR.saveAndEdit(this);">Save and Edit</button>'
        )

        SUGAR.saveAndEdit = function (elem) {
            $("#EditView input[name='action']").val('Save');
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                success: function (data) {
                    location.reload(true);
                }
            });
            return false;
        }

        //$("#primary_address_country").after("<br><button id='open_map' onclick='openMap(); return false;' >Open Map </button> ");//&nbsp; &nbsp; <button id='open_map' onclick='open_street_view();' >Open Street View </button>
        $("#distributor_c").after('<br><button class="button primary" id="getDistributor"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> Get Distributor </button>');
        
        $('#getDistributor').on('click', function (event){
            $('#getDistributor span.glyphicon-refresh').removeClass('hidden');
            var nmi = $("#nmi_c").val();
            $.ajax({
                url: "/index.php?entryPoint=customGetRetailer&nmi="+nmi,

                type: 'GET',

                success: function(data)
                {
                    var distributors = {
                        "Citipower": "4",
                        "Jemena": "5",
                        "Powercor": "6",
                        "Ausnet": "7",
                        "United": "8", // Edited
                        "Western Power": "1",
                        "SA Power Networks - NSP": "13",
                        "Energex": "2",
                        "Ergon": "3",
                        "Essential Energy": "9",
                        "Ausgrid": "10",
                        "Endeavour Energy": "12",
                        "ActewAGL": "11",
                        "AusNet Electricity Services Pty Ltd":"14",
                    }
                    $("#distributor_c").val(distributors[data.quote.network]);

                    $('#getDistributor span.glyphicon-refresh').addClass('hidden');
                },
                
                error: function(response){console.log("Fail");},
            });

            return false;
        });

        //$("#primary_address_country").after("<br><button id='open_map' onclick='openMap(); return false;' >Open Map </button> ");//&nbsp; &nbsp; <button id='open_map' onclick='open_street_view();' >Open Street View </button>

        addOpenMapView();

        $("#primary_address_street").autocomplete({
            source: function( request, response ) {
                /*var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
                response( $.grep( tags, function( item ){
                    return matcher.test( item );
                }) );*/
                console.log(request["term"]);
                Math.floor((Math.random() * 3) + 1);


                // https://www.agl.com.au/svc/QAS/GetSearchResult?searchKey=26%20Walsh%20Avenue&maxResults=10&searchType=SiteAddressSearch&_=1499225218711
                if(request["term"].length > 3){
                    $.ajax({
                        url: "/index.php?entryPoint=customGetAddress&address="+request["term"],
                        //data: {q:request.term},
                        //crossOrigin: true,
                        type: 'GET',
                        //async: false,
                        //crossDomain: true,
                        //dataType: 'jsonp',

                        success: function(data)
                        {
                            var suggest =[];
                            var jsonObject = $.parseJSON(data);
                            for (i = 1; i < jsonObject.length; i++) {
                                suggest.push(jsonObject[i].name);
                            }
                            console.log(jsonObject);
                            response(suggest);
                        },
                        error: function(response){console.log("Fail");},
                    });
                }
                //console.log(response);
            },
            select: function( event, ui ) {
                var value =  ui.item.value.split(",");
                var valueLen = value.length;
                var address1 = value[0];
                for (var i = 1; i < valueLen - 1; i++) {
                    address1 = address1 + value[i];
                }
                var address2 = value[valueLen - 1].trim();

                $("#primary_address_street").val(address1);

                var address3 = address2.split("  ");

                $("#primary_address_city").val(address3[0]);
                $("#primary_address_state").val(address3[1]);
                $("#primary_address_postalcode").val(address3[2]);

                // address to request
                var address1Items = address1.split(",");
                var address1Len = address1Items.length;
                var addarr = address1Items[address1Len - 1].trim().split("  ");
                var a_first_addres = "";
                var unit = "";
                var unit_num = "";
                var address_number = "";
                var address_name = "";
                if(addarr.length == 2){
                    //a_first_addres = addarr[0].replace("Unit","U")+ "/";
                    //a_first_addres += addarr[1].replace(/ /,"/");

                    // Unit param
                    var unit_numbers = addarr[0].split(" ");
                    unit = unit_numbers[0];
                    unit_num = unit_numbers[1];
                    var address_numbers =addarr[1].split(" ");
                    address_number = address_numbers[0];
                    address_name = address_numbers[1].replace(" ", "+");

                }
                else{
                    //a_first_addres  = "NA/"
                    //a_first_addres +=  address1.replace(/ /,"/");

                    var address_numbers =addarr[0].split(" ");
                    address_number = address_numbers[0];
                    address_name = address_numbers[1].replace(" ", "+");
                }

                var requestString ; //= a_first_addres + "/" + address3[0].trim() +"/"+ address3[1].trim() +"/"+ address3[2].trim();
                //var street = explode()
                requestString = encodeURIComponent("unit=" + unit + "/unit_num=" + unit_num + "/streetNumber="+address_number+"/streetName="+address_name+"/city="+ address3[0] + "/state="+address3[1]+"/customerType=residential/searchByPostcode=false/postcode="+address3[2]+"/fuelType=dual&hasSolarPanels=false/connectionScenario=PROS_SWT");

                $.ajax({
                    url: "/index.php?entryPoint=customGetRetailer&address="+requestString,//"https://www.originenergy.com.au/for-home/electricity-and-gas/plans/energy-plans.planslanding.json/3000/" + requestString + "/NA/No/No/Elec/Email_Green/2/residential/iwt0a/40232495/No/suffix.json",
                    //data: {q:request.term},
                    //crossOrigin: true,
                    type: 'GET',
                    //async: false,

                    success: function(data)
                    {
                        var suggest =[];
                        //var jsonObject = $.parseJSON(data);
                        if (typeof data[0].nmi !== "undefined"){
                            $("#nmi_c").val(data[0].nmi);
                        }
                        //var provide_name = data.data.plans[0].fuel.electricity.tariff[0].serviceProviderName;
                        //console.log(jsonObject);
                        /*if(typeof jsonObject.originPlanData.plan[0].energyType[0].rates !== "undefined"){
                            var rates = jsonObject.originPlanData.plan[0].energyType[0].rates;
                            for (var rate in rates) {
                                // object[prop]
                                var first_rate = rates[rate];
                                provide_name = first_rate.serviceProviderName;
                                break;
                            }
                            //var plan = jsonObject.originPlanData.plan[0].energyType[0].ERV-GH.serviceProviderName;
                        }*/
                        //console.log(provide_name);
                        /*var distributors = {
                            "Citipower": "4",
                            "Jemena Electricity Networks (Vic) Ltd": "5",
                            "Powercor": "6",
                            "SP Ausnet": "7",
                            "Jemena (United Energy)": "8", // Edited
                            "Western Power": "1",
                            "SA Power Networks - NSP": "13",
                            "Energex": "2",
                            "Ergon": "3",
                            "Essential Energy": "9",
                            "Ausgrid": "10",
                            "Endeavour Energy": "12",
                            "ActewAGL": "11",
                            "AusNet Electricity Services Pty Ltd":"14",
                        }
                        $("#distributor_c").val(distributors[provide_name])
                        console.log("dss"+ provide_name);*/
                        
                    },
                    error: function(response){
                        console.log("Fail");

                        var ok = confirm('GB NMI lookup failed!\nPerform manual lookup instead?');
                        if (ok)
                            window.open('https://signup.globirdenergy.com.au/yourproperty', '_blank');
                    },
                });

                return false;
            }
        });

        $("#save_and_edit").after(
            '&nbsp;<button type="button" id="push_to_solargain" class="button pushToSolargain" title="Push To Solargain" onClick="SUGAR.pushToSolargain(this);" > Push To Solargain <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )

        $("#push_to_solargain").after(
            '&nbsp;<button type="button" id="convertToQuotesSolarGain" class="button convertToQuotesSolarGain" title="Convert To Solargain Quote" onClick="SUGAR.convertToSolargainQuote(this);" > Convert SG Quotes <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )

        $("#save_and_edit").after(
            '&nbsp;<button type="button" id="sendMailToAdmin" class="button sendMailToAdmin" title="Request Designs" onClick="sendEmailToAdmin();" > Request Designs <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )

        $("#sendMailToAdmin").after(
            '&nbsp;<button type="button" id="sendDesignsComplete" class="button sendDesignsComplete" title="Designs Complete" onClick="sendEmailDesignsComplete();" > Designs Complete <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )
        SUGAR.pushToSolargain = function (elem) {
            $('#push_to_solargain span.glyphicon-refresh').removeClass('hidden');

            var build_url=  "?entryPoint=customCreateSolarGain";
            build_url += '&notes='+ encodeURIComponent($("#description").val()) ;
            build_url += '&record='+ encodeURIComponent($('input[name="record"]').val());
            //system_size_c
            build_url += '&system_size='+ encodeURIComponent($("#system_size_c").val()) ;
            build_url += '&unit_per_day='+ encodeURIComponent($("#units_per_day_c").val()) ;
            build_url += '&dolar_month='+ encodeURIComponent($("#dolar_month_c").val()) ;
            build_url += '&number_of_people='+ encodeURIComponent($("#number_of_people_c").val()) ;
            build_url += '&primary_address_state='+ encodeURIComponent($("#primary_address_state").val()) ;
            var customer_type = $('input[name=customer_type_c]:checked').val();
            build_url += '&customer_type='+ encodeURIComponent(customer_type) ;
            build_url += '&last_name='+ encodeURIComponent($("#last_name").val()) ;//
            build_url += '&first_name='+ encodeURIComponent($("#first_name").val()) ;//
            build_url += '&phone_work='+ encodeURIComponent($("#phone_work").val()) //
            build_url += '&phone_mobile='+ encodeURIComponent($("#phone_mobile").val()); //
            build_url += '&email='+ encodeURIComponent($("#Leads0emailAddress0").val());//Leads0emailAddress0
            build_url += '&primary_address_street='+ encodeURIComponent($("#primary_address_street").val());
            build_url += '&primary_address_city='+ encodeURIComponent($("#primary_address_city").val());
            build_url += '&state='+ encodeURIComponent($("#primary_address_state").val());
            build_url += '&postalcode='+ encodeURIComponent($("#primary_address_postalcode").val());
            //build_height
            build_url += '&build_height='+ encodeURIComponent($("#gutter_height_c").val());
            var connection_type = $("#connection_type_c").val();
            if (connection_type == "Semi_Rural_Remote_Meter")  connection_type =  "Semi Rural/Remote Meter";
            build_url += '&connection_type='+ encodeURIComponent($("#connection_type_c").val());
            build_url += '&main_type='+ encodeURIComponent($("#main_type_c").val());
            //Semi Rural/Remote Meter
            /*
             "ConnectionType" =>	urldecode($_GET['connection_type']),
             "MeterNumber"	=> urldecode($_GET['meter_number']),
             "NMINumber"	=> urldecode($_GET['nmi_number']),
             "AccountNumber" =>	urldecode($_GET['account_number']),
             "BillingName"	=> urldecode($_GET['billing_name'],

             */

            build_url += '&meter_number='+ encodeURIComponent($("#meter_number_c").val());
            build_url += '&nmi_number='+ encodeURIComponent($("#nmi_c").val());
            build_url += '&account_number='+ encodeURIComponent($("#account_number_c").val());
            build_url += '&billing_name='+ encodeURIComponent($("#name_on_billing_account_c").val());
            build_url += '&energy_retailer='+ encodeURIComponent($("#energy_retailer_c").val());
            build_url += '&distributor='+ encodeURIComponent($("#distributor_c").val());
            //energy_retailer_c
            //distributor_c
            // Roof type
            var roof_type = $("#roof_type_c").val();
            if (roof_type == "klip_loc"){
                roof_type = "Klip Loc";
            }
            if (roof_type == "Trim_Deck"){
                roof_type = "Trim Deck";
            }

            if (roof_type == "Ground_Mount"){
                roof_type = "Ground Mount";
            }

            build_url += '&roof_type='+ encodeURIComponent(roof_type);

            //primary_address_postalcode
            //primary_address_street //primary_address_city
            $.ajax({
                url: build_url,
                //data : data,
                type : 'POST',
                //contentType: false,
                //processData: false,

                success: function (data) {
                    console.log(data);
                    leadID = data;
                    $('#push_to_solargain span.glyphicon-refresh').addClass('hidden');
                    $("#solargain_lead_number_c").val(data);
                    if($("#convertToQuotesSolarGain").length == 0){
                        $("#push_to_solargain").after(
                            '&nbsp;<button type="button" id="convertToQuotesSolarGain" class="button convertToQuotesSolarGain" title="Convert To Solargain Quote" onClick="SUGAR.convertToSolargainQuote(this);" > Convert SG Quotes <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
                        )
                    }
                },
            });
        }

        if($("#solargain_lead_number_c").val() != "" && $("#convertToQuotesSolarGain").length == 0 ){
            leadID = $("#solargain_lead_number_c").val();
            $("#push_to_solargain").after(
                '&nbsp;<button type="button" id="convertToQuotesSolarGain" class="button convertToQuotesSolarGain" title="Convert To Solargain Quote" onClick="SUGAR.convertToSolargainQuote(this);" > Convert SG Quotes <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            )
        }

        SUGAR.convertToSolargainQuote = function (elem) {
            $('#convertToQuotesSolarGain span.glyphicon-refresh').removeClass('hidden');
            leadID = $("#solargain_lead_number_c").val();
            if (leadID == 0 ) return;
            var build_url=  "?entryPoint=customCreateSolarGainQuote";
            build_url += '&leadID='+ encodeURIComponent(leadID);
            var solargain_options = $('#solargain_options_c').val();
            if (solargain_options_c!=null && solargain_options.length > 0){
                build_url += '&sgoption='+ solargain_options.join();
            }

            build_url += '&record='+ encodeURIComponent($('input[name="record"]').val());
            $.ajax({
                url: build_url,
                //data : data,
                type : 'POST',
                //contentType: false,
                //processData: false,

                success: function (data) {
                    console.log(data);
                    leadID = data;
                    $('#convertToQuotesSolarGain span.glyphicon-refresh').addClass('hidden');
                    $("#solargain_quote_number_c").val(data);
                },
            });
        }
    })
});
