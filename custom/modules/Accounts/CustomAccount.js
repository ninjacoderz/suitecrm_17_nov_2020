
$(function () {
    'use strict';
// Generate uinique id

// Hide Subpanel Daikin Information 
    $('#detailpanel_1').parent().hide();
    
    $("#btn_clr_assigned_user_name").after('<button type="button" style="width: 218px;" id="update_relates" class="button update_relates">Update Related <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
    var record = $("input[name='record']").val();

    if(typeof record !== "undefined" && record != ""){
        $("#update_relates").click(function(){
            var assigned_id = $("#assigned_user_id").val();
            $('#update_relates span.glyphicon-refresh').removeClass('hidden');
            $.ajax({
                url: "/index.php?entryPoint=customUpdateRelated&bean_type=Accounts&record="+record +"&assigned_id="+assigned_id,
                //data: {q:request.term},
                //crossOrigin: true,
                type: 'GET',
                //async: false,
                //crossDomain: true,
                //dataType: 'jsonp',

                success: function(data)
                {
                    $('#update_relates span.glyphicon-refresh').addClass('hidden');
                },
                error: function(response){console.log("Fail");},
            });
            return false;
        });
    }

    $("#billing_address_street").autocomplete({
        source: function( request, response ) {

            console.log(request["term"]);
            Math.floor((Math.random() * 3) + 1);

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
            console.log(ui.item.value);
            var value =  ui.item.value.split(",");
            var address1 = value[0];
            var address2 = value[1].trim();

            $("#billing_address_street").val(address1);

            var address3 = address2.split("  ");

            $("#billing_address_city").val(address3[0]);
            $("#billing_address_state").val(address3[1]);
            $("#billing_address_postalcode").val(address3[2]);
            return false;
        }
    });
    //thienpb code here
    $("#shipping_address_street").autocomplete({
        source: function( request, response ) {

            console.log(request["term"]);
            Math.floor((Math.random() * 3) + 1);

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
            console.log(ui.item.value);
            var value =  ui.item.value.split(",");
            var address1 = value[0];
            var address2 = value[1].trim();

            $("#shipping_address_street").val(address1);

            var address3 = address2.split("  ");

            $("#shipping_address_city").val(address3[0]);
            $("#shipping_address_state").val(address3[1]);
            $("#shipping_address_postalcode").val(address3[2]);
            return false;
        }
    });
     //dung code - get address by field postcode or city
     $("#billing_address_city ,#billing_address_postalcode").autocomplete({
        source: function( request, response ) {
            console.log(request["term"]);
            Math.floor((Math.random() * 3) + 1);
            if(request["term"].length > 3){
                $.ajax({
                    url: "/index.php?entryPoint=customGetAddress&postcode_city="+request["term"],
                    type: 'GET',
                    success: function(data)
                    {
                        var suggest =[];
                        var jsonObject = data.split('\n');
                        for (i = 0; i < jsonObject.length; i++) {
                            var array_child = jsonObject[i].split('|');
                            if(array_child[0] !== ''){
                                suggest[i] = array_child[2] +',' +array_child[3] +',' +array_child[1];
                            }
                        }
                        response(suggest);
                    },
                    error: function(response){console.log("Fail");},
                });
            }
        },
        select: function( event, ui ) {
            var address = ui.item.value;
            var array_value =  address.split(",");
            $("#billing_address_city").val(array_value[0]);
            $("#billing_address_state").val(array_value[1]);
            $("#billing_address_postalcode").val(array_value[2]);
            return false;
        },
    });

    var parent_selected =  $('#AccountsemailAddressesTable0').parent();
    $('#AccountsemailAddressesTable0').detach().appendTo(parent_selected);

    function addOpenMapView(div_id,street,city,state,postalcode) {
        var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&q=' +street +", " + city + ", " + state + ", " + postalcode; //26 Walsh Ave, St Marys SA 5042, Úc;
        $("#"+div_id).after('<br><a id="open_map_'+div_id+'" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a>');
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
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + street +", " + city + ", " + state + ", " + postalcode + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
            type: 'GET',
            success: function(result) {
                if (result.status != "OK")
                    return;
    
                var location = result.results[0].geometry.location;
    
                $.ajax({
                    url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&size=600x300&location=' + location.lat + ',' + location.lng,
                    type: 'GET',
                    success: function(result) {
                        console.log(result.status);
                        if (result.status == "OK") {
                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                            $("#open_map_"+div_id).after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
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
                                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&pano=' + panoData.location.pano;
                                            $("#open_map_"+div_id).after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
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
    if($("#billing_address_street").val() != '' || $("#billing_address_city").val() != '' || $("#billing_address_state").val() != '' || $("#billing_address_postalcode").val() != ''){
        addOpenMapView('billing_address_country',$("#billing_address_street").val(),$("#billing_address_city").val(),$("#billing_address_state").val(),$("#billing_address_postalcode").val());
    }
    if($("#shipping_address_street").val() != '' || $("#shipping_address_city").val() != '' || $("#shipping_address_state").val() != '' || $("#shipping_address_postalcode").val() != ''){
        addOpenMapView('shipping_address_country',$("#shipping_address_street").val(),$("#shipping_address_city").val(),$("#shipping_address_state").val(),$("#shipping_address_postalcode").val());
    }
    $(document).ready(function(){
        //tu-code add Near Map
        // var open_map = "<a target='_blank' href='http://maps.nearmap.com?addr='"+  $("#billing_address_street").val()+','+$("#billing_address_city").val()+','+$("#billing_address_state").val()+','+$("#billing_address_postalcode").val() + "'&z=22&t=roadmap'>Near Map</a>";
        // $("#billing_address_country").after("<br/>"+open_map);
        // $("#shipping_address_country").after("<br/>"+open_map);
        var address = $("#billing_address_street").val()+','+$("#billing_address_city").val()+','+$("#billing_address_state").val()+','+$("#billing_address_postalcode").val();     
        var address_realestate = address.toLowerCase().replace(/ |,/g,'-');
        $("#billing_address_street_label label").after('<a style="float: right;cursor:pointer;" id="open_map_billing" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        
        $("#billing_address_street").before(
            '<div style="background-color: white;display:none;border:1px solid;position:absolute; padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_billing" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a style="cursor:pointer;" onclick="openBillingMap(); return false;">Open Map</a></li>'+
                '<li><a style="cursor:pointer;" href="http://maps.nearmap.com?addr='+ address +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_billing">Realestate</a></li>'+
                '</ul>'+
            '</div>'
            );
        $("#shipping_address_street_label label").after(
            '<a style="float: right;cursor:pointer;" id="open_map_shipping" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        $("#shipping_address_street").before(
            '<div style="background-color: white;display:none;border:1px solid;position:absolute; padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_shipping" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a style="cursor:pointer;" onclick="openShippingMap(); return false;">Open Map</a></li>'+
                '<li><a style="cursor:pointer;"  href="http://maps.nearmap.com?addr='+ address +'&z=22&t=roadmap" target="_blank">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_shipping" >Realestate</a></li>'+
                '</ul>'+
            '</div>'
        );
        $("#link_realestate_shipping").click(function(){
            SUGAR.ajaxUI.showLoadingPanel();
            $.ajax({
                url: "?entryPoint=getLinkRealestate",
                type: 'POST',
                data: {
                    street    : $("#shipping_address_street").val(),
                    city      : $("#shipping_address_city").val(),
                    state     : $("#shipping_address_state").val(),
                    postcode  : $("#shipping_address_postalcode").val(),
                },
                success: function(data){
                    if(data !== 'Not Find Address On Realestate'){
                        window.open(data,'_blank');
                    }else {
                        alert(data);
                    }
                    SUGAR.ajaxUI.hideLoadingPanel();
                }
            })
        })
        $("#link_realestate_billing").click(function(){
            SUGAR.ajaxUI.showLoadingPanel();
            $.ajax({
                url: "?entryPoint=getLinkRealestate",
                type: 'POST',
                data: {
                    street    : $("#billing_address_street").val(),
                    city      : $("#billing_address_city").val(),
                    state     : $("#billing_address_state").val(),
                    postcode  : $("#billing_address_postalcode").val(),
                },
                success: function(data){
                    if(data !== 'Not Find Address On Realestate'){
                        window.open(data,'_blank');
                    }else {
                        alert(data);
                    }
                    SUGAR.ajaxUI.hideLoadingPanel();
                }
            })
        })
            
        $('#open_map_billing').click(function(){
            $('#open_map_popup_billing').fadeToggle()
        })
        $('#open_map_shipping').click(function(){
            $('#open_map_popup_shipping').fadeToggle()
        })

        //Primary Contact 
        $("#primary_contact_c").hide();
 
        function getdata_create_select_primary_contact(record = ''){
            if(record == ''){
                var record = $("input[name='record']").val();
            }
            
            $.ajax({
                url: "/index.php?entryPoint=get_list_contact_from_account&account_id="+record,
                type: 'GET',
                success: function(data)
                {
                    var json_data = $.parseJSON(data);
                    $('body').find('#custom_select').remove();
                    var select = $("<select/>").addClass('custom_select').attr("id",'custom_select');
                    $.each(json_data, function(index, key) {
                        if($("#primary_contact_c").val() == ''){
                            $('body').find('#primary_contact_c').val(key.id);
                        }
                        select.append( $('<option></option>').val(key.id).html(key.name) );
                    });
                    $("#primary_contact_c").parent().append(select);
                    if($("#primary_contact_c").val() != ''){
                        $('body').find('#custom_select').val($("#primary_contact_c").val());
                    }  
                    $(".open_primary_contact").remove();
                    $("#primary_contact_c").parent().append('<a class="open_primary_contact" target="_blank" href="/index.php?module=Contacts&action=EditView&record='+$("#primary_contact_c").val() +'"> Edit Primary Contact</a>');
                },
                error: function(response){console.log("Fail");},
            });
        }
        getdata_create_select_primary_contact();
        $('body').on('change','#custom_select',function(){
            $("#primary_contact_c").val($("#custom_select").val());
            $(".open_primary_contact").remove();
            $("#primary_contact_c").parent().append('<a class="open_primary_contact" target="_blank" href="/index.php?module=Contacts&action=EditView&record='+$("#primary_contact_c").val() +'"> Edit Primary Contact</a>');
        })

        // Create New Contact
        $("#primary_contact_c").parents('.edit-view-row-item').find('.label').append('<br> <button type="button" class="button primary" id="create_new_contact"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Create New Contact</button>');
        $('#create_new_contact').click(function(){
            var account_id = $("input[name='record']").val();
            createAccount(account_id)
        });

        function createAccount(account_id) {     
            $("#EditView input[name='action']").val('Save');
            SUGAR.ajaxUI.showLoadingPanel();
            $.ajax({
                type: $("#EditView").attr('method'),
                url: $("#EditView").attr('action'),
                data: $("#EditView").serialize(),
                async:false,
                success: function (data) { 
                    var account_id_patt = /"record" value="(.*)"/g;
                    account_id = account_id_patt.exec(data);
                    if(account_id !== null && typeof account_id === 'object'){
                        if(account_id[1] !='')  {
                            createContact(account_id[1]);
                        }
                    }
                }
             });                  
        }

        function createContact(account_id) {  
            $.ajax({
                url: "/index.php?entryPoint=create_new_contact&account_id="+account_id,
                context: document.body,
                async: true
            }).done(function (data) {
                //step2: load new account 
                getdata_create_select_primary_contact();
                window.open("/index.php?module=Contacts&action=EditView&record="+data,"_blank");
                SUGAR.ajaxUI.hideLoadingPanel();
            });  
        }

    })

    //VUT-S-Create button "update infomation"
    $("#home_phone_c").parents('.edit-view-row-item').append('<br> <button type="button" class="button primary" id="update_phone_number"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Update infomation</button>');
    $('#abn_c').parent().siblings('.label').append('<br><button type="button" class="button primary" id="getData_ABN"> <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>Get ABN</button>');
    $(document).on('change', '#abn_c',function(){
        showlinkABN();
    });
    showlinkABN();
    function showlinkABN() {
        // debugger
        if ($("#abn_c").val() == "") {
            $('.link_abn').remove();
            return;
        }
        var abn_num = $("#abn_c").val().replace(/\s+/g,'');
        var href = "<a class='link_abn' target='_blank' href='https://abr.business.gov.au/ABN/View?id=" + abn_num +"'> https://abr.business.gov.au/ABN/View?id=" + abn_num + "</a>";
        $('.link_abn').remove();
        $('#abn_c').parent().after(href);
    }

    $('#update_phone_number').click(function(){
        var build_url = "/index.php?entryPoint=UpdateInfomationAccount";
        build_url += "&account_id="+encodeURIComponent($("input[name='record']").val());
        build_url += "&name="+encodeURIComponent($("#name").val());
        build_url += "&account_type="+encodeURIComponent($("#check_account_type_c").val());
        build_url += "&phone_fax="+encodeURIComponent($("#phone_fax").val());
        build_url += "&daikin_account_number_c="+encodeURIComponent($("#daikin_account_number_c").val());
        build_url += "&phone_office="+encodeURIComponent($("#phone_office").val());
        build_url += "&mobile_phone_c="+encodeURIComponent($("#mobile_phone_c").val());
        build_url += "&home_phone_c="+encodeURIComponent($("#home_phone_c").val());
        $.ajax({
            url: build_url,
            type: 'GET',
            success: function()
            {
                alert('INFOMATION HAVE UPDATED !');
            },
            error: function(response){console.log("Fail");},
        });
    });
    // tuan copy ABN from Invoice
    $("#getData_ABN").click(function(){   
        $.ajax({
            url: 'index.php?entryPoint=getdata_ABN&number_ABN='+$('#abn_c').val(),
            success: function(data){

                if(data !== '[]') {
                    var data_result =  $.parseJSON(data);
                    $('#entity_name_c').val(data_result['Entiny_name']);
                    $('#abn_status_c').val(data_result['ABN_status']);
                    $('#entity_type_c').val(data_result['Entity_type']);
                    $('#good_services_tax_c').val(data_result['Goods_Services_Tax']);
                    $('#main_business_location_c').val(data_result['Main_business_location']);
                    if (typeof(data_result['Business_name']) !== 'undefined'){
                        $('#business_name_c').val(JSON.stringify(data_result['Business_name']));
                    }
                    else {
                        $('#business_name_c').val(JSON.stringify(data_result['trading_name']));
                    }

                    $('#trading_name_c').val(JSON.stringify(data_result['trading_name']));
                    $('#asic_registation_acn_or_arbn_c').val(data_result['ASIC_registration_ACN_or_ARBN']);
                    var html_business_name = '';
                    if(typeof(data_result['Business_name']) !== 'undefined' ){
                        $.each(data_result['Business_name'],function(key,value){
                            if(key !== '' || value[0] !== '' ){
                                var string_plus = '<input type="radio" name="Business_name" class="Business_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                                html_business_name += string_plus;
                            }
                        });
                    } else if(typeof(data_result['trading_name']) !== 'undefined' ){
                        $.each(data_result['trading_name'],function(key,value){
                            if(key !== '' || value[0] !== '' ){
                                var string_plus = '<input type="radio" name="Business_name" class="Business_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                                html_business_name += string_plus;
                            }
                        });
                    }
                    $('#text_business_name').html('');
                    $('#text_business_name').append(html_business_name);

                    /// Trading name
                    var html_trading_name = '';
                    if(typeof(data_result['trading_name']) !== 'undefined' ){
                        $.each(data_result['trading_name'],function(key,value){
                            if(key !== '' || value[0] !== '' ){
                                var string_plus = '<input type="radio" name="trading_name" class="trading_name" value="'+key+'">' + key + ' ---From: ' + value[0]+'<br>'; 
                                html_trading_name += string_plus;
                            }
                        });
                    }
                    $('#text_trading_name').html('');
                    $('#text_trading_name').append(html_trading_name);
                }else {
                    alert('ABN is wrong !');
                }
            }
        });
    });
});


function openBillingMap(){
    var url = 'https://www.google.com/maps/place/'+ $("#billing_address_street").val() +", " + $("#billing_address_city").val() + ", " + $("#billing_address_state").val() + ", " + $("#billing_address_postalcode").val(); //26 Walsh Ave, St Marys SA 5042, Úc;
    window.open(url, '_blank');
    return false;
}

function openShippingMap(){
    var url = 'https://www.google.com/maps/place/'+ $("#shipping_address_street").val() +", " + $("#shipping_address_city").val() + ", " + $("#shipping_address_state").val() + ", " + $("#shipping_address_postalcode").val(); //26 Walsh Ave, St Marys SA 5042, Úc;
    window.open(url, '_blank');
    return false;
}
