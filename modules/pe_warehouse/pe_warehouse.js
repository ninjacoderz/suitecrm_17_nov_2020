$(document).ready(function(){
    function addOpenMapView_Billing_Address() {
        var address = $("#billing_address_street").val()+','+$("#billing_address_city").val()+','+$("#billing_address_state").val()+','+$("#billing_address_postalcode").val();     
        var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&q=' + $("#billing_address_street").val() +", " + $("#billing_address_city").val() + ", " + $("#billing_address_state").val() + ", " + $("#billing_address_postalcode").val();
        $("#billing_address_street_label label").after(
            '<a style="float: right;cursor:pointer;" id="open_map_billing_warehouselog" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        $("#billing_address_street").before( 
            '<div style="background-color: white;display:none;border:1px solid;position:absolute;padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_billing_warehouselog" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a id="open_map_billing_address" target="_blank" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a></li>'+
                '<li><a style="cursor:pointer;" target="_blank" href="http://maps.nearmap.com?addr='+ address+'&z=22&t=roadmap">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_billing">Realestate</a></li>'+
                '</ul>'+
            '</div>'
            );
        $('#open_map_billing_warehouselog').click(function(){
                $('#open_map_popup_billing_warehouselog').fadeToggle()
        })
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
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + $("#billing_address_street").val() +", " + $("#billing_address_city").val() + ", " + $("#billing_address_state").val() + ", " + $("#billing_address_postalcode").val() + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
            type: 'GET',
            success: function(result) {
                if (result.status != "OK")
                    return;
    
                var location = result.results[0].geometry.location;
    
                $.ajax({
                    url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&size=600x300&location=' + location.lat + ',' + location.lng,
                    type: 'GET',
                    success: function(result) {
                        if (result.status == "OK") {
                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                            $("#open_map_billing_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
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
                                            $("#open_map_billing_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
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
    addOpenMapView_Billing_Address();
    $("#link_realestate_billing").click(function(){
        // address = address.toLowerCase().replace(/ /g, '-');
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

    function addOpenMapView_Shipping_Address() {
        var address = $("#shipping_address_street").val()+','+$("#shipping_address_city").val()+','+$("#shipping_address_state").val()+','+$("#shipping_address_postalcode").val();     
        var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&q=' + $("#shipping_address_street").val() +", " + $("#shipping_address_city").val() + ", " + $("#shipping_address_state").val() + ", " + $("#shipping_address_postalcode").val();
        $("#shipping_address_street_label label").after(
            '<a style="float: right;cursor:pointer;" id="open_map_shipping_address_warehouselog" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
        $("#shipping_address_street").before( 
            '<div style="background-color: white;display:none;border:1px solid;position:absolute;padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_shipping_address_warehouselog" class="show-open-map hide_map">'+
                '<ul>'+
                '<li><a id="open_map_shipping_address" target="_blank" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a></li>'+
                '<li><a style="cursor:pointer;" target="_blank" href="http://maps.nearmap.com?addr='+ address+'&z=22&t=roadmap">Near Map</a></li>'+
                '<li><a style="cursor:pointer;" id="link_realestate_shipping" >Realestate</a></li>'+
                '</ul>'+
            '</div>'
            );
        $('#open_map_shipping_address_warehouselog').click(function(){
                $('#open_map_popup_shipping_address_warehouselog').fadeToggle()
        })
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
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + $("#shipping_address_street").val() +", " + $("#shipping_address_city").val() + ", " + $("#shipping_address_state").val() + ", " + $("#shipping_address_postalcode").val() + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
            type: 'GET',
            success: function(result) {
                if (result.status != "OK")
                    return;
    
                var location = result.results[0].geometry.location;
    
                $.ajax({
                    url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&size=600x300&location=' + location.lat + ',' + location.lng,
                    type: 'GET',
                    success: function(result) {
                        if (result.status == "OK") {
                            var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                            $("#open_map_shipping_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
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
                                            $("#open_map_shipping_address").after('<br><a id="open_street_view" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
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
    addOpenMapView_Shipping_Address();
    $("#link_realestate_shipping").click(function(){
        // address = address.toLowerCase().replace(/ /g, '-');
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

})