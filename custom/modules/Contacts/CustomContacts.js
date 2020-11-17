$(function () {
    'use strict';
    // Generate uinique id
    $( document ).ready(function() {
        function Display_information_account (){
            var record_id = $("#account_id").val();
            var module_name = 'Accounts';
            $.ajax({
                url: "?entryPoint=getContactPhoneNumber&action=getInfoFromCall&module_name=" + module_name + "&record_id=" + record_id,
            }).done(function (data) {
                $('body').find('#information_account_contact').remove();
                $("#account_id").closest('.edit-view-row-item').append(data);
            });
        }
        Display_information_account();
        YAHOO.util.Event.addListener("account_id", "change", Display_information_account);
        //dung code -button US7 TIPS 
        $("#CANCEL").after(
        '&nbsp;<button data-record-id="'+$('input[name="record"]').val()+'" data-module-name="'+ $("#first_name").val() + ' ' + $("#last_name").val() +'" type="button" email-type="us7_tips" id="email_us7_tips" class="button email_us7_tips" title="US7 Tips" data-module="Contacts" onClick="$(document).openComposeViewModal(this);" >US7 Tips<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        );
        //dung code -button Sanden TIPS 
        $("#CANCEL").after(
            '&nbsp;<button email-type="sanden_tips" data-record-id="'+$('input[name="record"]').val()+'" data-module-name="'+ $("#first_name").val() + ' ' + $("#last_name").val() +'" type="button" id="email_sanden_tips" class="button email_sanden_tips" title="Sanden Tips" data-module="Contacts" onClick="popupSandenProduct(this);" >Sanden Tips<span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
            )

        $("#btn_clr_assigned_user_name").after('<button type="button" style="width: 218px;" id="update_relates" class="button update_relates">Update Related <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
        var record = $("input[name='record']").val();

        if(typeof record !== "undefined" && record != ""){
            $("#update_relates").click(function(){
                var assigned_id = $("#assigned_user_id").val();
                $('#update_relates span.glyphicon-refresh').removeClass('hidden');
                $.ajax({
                    url: "/index.php?entryPoint=customUpdateRelated&bean_type=Contacts&record="+record +"&assigned_id="+assigned_id,
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
                console.log(ui.item.value);
                var value =  ui.item.value.split(",");
                var address1 = value[0];
                var address2 = value[1].trim();
                $("#primary_address_street").val(address1);

                var address3 = address2.split("  ");

                $("#primary_address_city").val(address3[0]);
                $("#primary_address_state").val(address3[1]);
                $("#primary_address_postalcode").val(address3[2]);
                return false;
            }
        });

        $("#alt_address_street").autocomplete({
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
                console.log(ui.item.value);
                var value =  ui.item.value.split(",");
                var address1 = value[0];
                var address2 = value[1].trim();
                $("#alt_address_street").val(address1);

                var address3 = address2.split("  ");

                $("#alt_address_city").val(address3[0]);
                $("#alt_address_state").val(address3[1]);
                $("#alt_address_postalcode").val(address3[2]);
                return false;
            }
        });

         //dung code - get address by field postcode or city
         $("#primary_address_postalcode ,#primary_address_city").autocomplete({
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
                $("#primary_address_city").val(array_value[0]);
                $("#primary_address_state").val(array_value[1]);
                $("#primary_address_postalcode").val(array_value[2]);
                return false;
            },
        });

        //dung code-- button Send Email TrustPilot 
        $("#CANCEL").after(
            '&nbsp;<button class="button" type="button" id="Send_Email_TrustPilot" >TrustPilot</button>'
        );
        
        $('body').on('click','#Send_Email_TrustPilot',function(){
            var answer = confirm("Are you want to send email TrustPilot to customer?")
            if (answer) {
                $.ajax({
                    url : "?entryPoint=SendEmailTrustPilop&module=Contact&action=EditView&record_id="+$('input[name="record"]').val(),
                    success: function (data) {
                        if(data == 'error'){
                            alert('Error!');
                        }else{
                            alert('Success!');
                        }
                    }
                });
            }
            else {
                return false;
            }
        })

        var parent_selected =  $('#ContactsemailAddressesTable0').parent();
        $('#ContactsemailAddressesTable0').detach().appendTo(parent_selected);

        function addOpenMapView_Billing_Address() {
            //tu-code add Near Map
            var address = $("#primary_address_street").val()+','+$("#primary_address_city").val()+','+$("#primary_address_state").val()+','+$("#primary_address_postalcode").val();     
            // $("#primary_address_country").after("<br><div>"+open_map+"</div>");
            var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&q=' + $("#primary_address_street").val() +", " + $("#primary_address_city").val() + ", " + $("#primary_address_state").val() + ", " + $("#primary_address_postalcode").val(); //26 Walsh Ave, St Marys SA 5042, Úc;
            // $("#primary_address_country").after('<br><a id="open_map" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a>');
            // tuan code
            $("#primary_address_street_label label").after(
                '<a style="float: right;cursor:pointer;" id="open_map_billing_leads" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
            $("#primary_address_street").before( 
                '<div style="background-color: white;display:none;border:1px solid;position:absolute;padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_billing_leads" class="show-open-map hide_map">'+
                    '<ul>'+
                    '<li><a id="open_map" target="_blank" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a></li>'+
                    '<li><a style="cursor:pointer;" target="_blank" href="http://maps.nearmap.com?addr='+ address+'&z=22&t=roadmap">Near Map</a></li>'+
                    '<li><a style="cursor:pointer;" id="link_realestate_billing">Realestate</a></li>'+
                    '</ul>'+
                '</div>'
                );
            $("#link_realestate_billing").click(function(){
                SUGAR.ajaxUI.showLoadingPanel();
                $.ajax({
                    url: "?entryPoint=getLinkRealestate",
                    type: 'POST',
                    data: {
                        street    : $("#primary_address_street").val(),
                        city      : $("#primary_address_city").val(),
                        state     : $("#primary_address_state").val(),
                        postcode  : $("#primary_address_postalcode").val(),
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
                            
            $('#open_map_billing_leads').click(function(){
                    $('#open_map_popup_billing_leads').fadeToggle()
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
                url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + $("#primary_address_street").val() +", " + $("#primary_address_city").val() + ", " + $("#primary_address_state").val() + ", " + $("#primary_address_postalcode").val() + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
                type: 'GET',
                success: function(result) {
                    if (result.status != "OK")
                        return;
        
                    var location = result.results[0].geometry.location;
        
                    $.ajax({
                        url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&size=600x300&location=' + location.lat + ',' + location.lng,
                        type: 'GET',
                        success: function(result) {
                            //console.log(result.status);
                            if (result.status == "OK") {
                                var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
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
                                                var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&pano=' + panoData.location.pano;
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

        addOpenMapView_Billing_Address();

        function addOpenMapView_Install_Address() {
            var address = $("#alt_address_street").val()+','+$("#alt_address_city").val()+','+$("#alt_address_state").val()+','+$("#alt_address_postalcode").val();     
            var urlMap = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&q=' + $("#alt_address_street").val() +", " + $("#alt_address_city").val() + ", " + $("#alt_address_state").val() + ", " + $("#alt_address_postalcode").val(); //26 Walsh Ave, St Marys SA 5042, Úc;
            $("#alt_address_street_label label").after(
                '<a style="float: right;cursor:pointer;" id="open_map_install_add_contacts" title="open map"><img src="themes/SuiteP/images/map.png" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></a>');
            $("#alt_address_street").before( 
                '<div style="background-color: white;display:none;border:1px solid;position:absolute;padding:3px;margin-top:12px;box-shadow: 0px 0px 4px rgb(15, 15, 15,0.5);" id="open_map_popup_install_add_contacts" class="show-open-map hide_map">'+
                    '<ul>'+
                    '<li><a id="open_map_install_add" target="_blank" class="various fancybox.iframe" href="' + urlMap + '">Open Map</a></li>'+
                    '<li><a style="cursor:pointer;" target="_blank" href="http://maps.nearmap.com?addr='+ address+'&z=22&t=roadmap">Near Map</a></li>'+
                    '<li><a style="cursor:pointer;" id="link_realestate_install">Realestate</a></li>'+
                    '</ul>'+
                '</div>'
                );
            $("#link_realestate_install").click(function(){
                SUGAR.ajaxUI.showLoadingPanel();
                $.ajax({
                    url: "?entryPoint=getLinkRealestate",
                    type: 'POST',
                    data: {
                        street    : $("#alt_address_street").val(),
                        city      : $("#alt_address_city").val(),
                        state     : $("#alt_address_state").val(),
                        postcode  : $("#alt_address_postalcode").val(),
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
            $('#open_map_install_add_contacts').click(function(){
                    $('#open_map_popup_install_add_contacts').fadeToggle()
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
                url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + $("#alt_address_street").val() +", " + $("#alt_address_city").val() + ", " + $("#alt_address_state").val() + ", " + $("#alt_address_postalcode").val() + '&key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo',
                type: 'GET',
                success: function(result) {
                    if (result.status != "OK")
                        return;
        
                    var location = result.results[0].geometry.location;
        
                    $.ajax({
                        url: 'https://maps.googleapis.com/maps/api/streetview/metadata?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&size=600x300&location=' + location.lat + ',' + location.lng,
                        type: 'GET',
                        success: function(result) {
                            //console.log(result.status);
                            if (result.status == "OK") {
                                var urlStreetView = 'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCuMMCDEYH86TlV0BLA8VF3xU1wmdSaxEo&location=' + location.lat + ',' + location.lng;
                                $("#open_map_install_add").after('<br><a id="open_street_view_install_add" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
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
                                                $("#open_map_install_add").after('<br><a id="open_street_view_install_add" class="various fancybox.iframe" href="' + urlStreetView + '">Open Street View</a>');
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

        addOpenMapView_Install_Address();
    });
});

(function ($) {
    $.fn.openComposeViewModal = function (source) {
        "use strict";
        var record_id= $(source).attr('data-record-id') ;
        var sanden_product = $(source).attr('data-sanden-product');
        if(record_id == ''){
            alert('Please Save before !');
            return;
        }
        var self = this;
        self.emailComposeView = null;
        var opts = $.extend({}, $.fn.EmailsComposeViewModal.defaults);
        var composeBox = $('<div></div>').appendTo(opts.contentSelector);
        composeBox.messageBox({"showHeader": false, "showFooter": false, "size": 'lg'});
        composeBox.setBody('<div class="email-in-progress"><img src="themes/' + SUGAR.themes.theme_name + '/images/loading.gif"></div>');
        composeBox.show();
        var record_id = $("input[name='record']").val();
        var emailType = $(source).attr('email-type');
        console.log(emailType);
        $.ajax({
            type: "GET",
            cache: false,
            url: 'index.php?module=Emails&action=ComposeView&in_popup=1'
                + ((emailType!="")? ("&email_type="+emailType):"")
                + ((record_id!="")? ("&record_id="+record_id):"")
                + ((sanden_product!="")? ("&sanden_product="+sanden_product):""),
        }).done(function (data) {
            if (data.length === 0) {
            console.error("Unable to display ComposeView");
            composeBox.setBody(SUGAR.language.translate('', 'ERR_AJAX_LOAD'));
            return;
            }
            composeBox.setBody(data);
            self.emailComposeView = composeBox.controls.modal.body.find('.compose-view').EmailsComposeView();

            // Populate fields
            if ($(source).attr('data-record-id') !== '') {
            var populateModule = $(source).attr('data-module');
            var populateModuleRecord = $(source).attr('data-record-id');

            var populateModuleName = $(source).attr('data-module-name');
            var populateEmailAddress = $(source).attr('data-email-address');

            var populateEmailAddress = $("#Contacts0emailAddress0").val();
            $(self.emailComposeView).find('#to_addrs_names').val(populateEmailAddress);
            $(self.emailComposeView).find('#parent_type').val(populateModule);
            $(self.emailComposeView).find('#parent_name').val(populateModuleName);
            $(self.emailComposeView).find('#cc_addrs_names').val("Pure Info <info@pure-electric.com.au>");
            $(self.emailComposeView).find('#parent_id').val(populateModuleRecord);

            }
            $(self.emailComposeView).on('sentEmail', function (event, composeView) {
            composeBox.hide();
            composeBox.remove();
            });
            $(self.emailComposeView).on('disregardDraft', function (event, composeView) {
            if (typeof messageBox !== "undefined") {
                var mb = messageBox({size: 'lg'});
                mb.setTitle(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_TITLE'));
                mb.setBody(SUGAR.language.translate('', 'LBL_CONFIRM_DISREGARD_DRAFT_BODY'));
                mb.on('ok', function () {
                mb.remove();
                composeBox.hide();
                composeBox.remove();
                });
                mb.on('cancel', function () {
                mb.remove();
                });
                mb.show();
            } else {
                if (confirm(self.translatedErrorMessage)) {
                composeBox.hide();
                composeBox.remove();
                }
            }
            });
            composeBox.on('cancel', function () {
            composeBox.remove();
            });
            composeBox.on('hide.bs.modal', function () {
            composeBox.remove();
            });
        }).fail(function (data) {
            composeBox.controls.modal.content.html(SUGAR.language.translate('', 'LBL_EMAIL_ERROR_GENERAL_TITLE'));
        });
        return $(self);
    };
}(jQuery));

//VUT-S-Create popup when click Sandan Tip
function popupSandenProduct(e) {
    var popupList = $('<div id="popupSanden" title="Sanden Product">'
                    + '<input name="sandenProduct" type="radio" value="G2">G2<br>'
                    + '<input name="sandenProduct" type="radio" value="G3">G3<br>'
                    + '<input name="sandenProduct" type="radio" value="G4">G4<br>'
                    + '</div>');
    popupList.dialog({
        modal:true,
        buttons: {
            Cancel : function(){
                $(this).dialog("close");
            },
            OK : function() {
                $(e).attr('data-sanden-product', $('input[name="sandenProduct"]:checked').val());
                $(document).openComposeViewModal(e);
                $(this).dialog("close");

            }
        }
    });
}
//VUT-S-Create popup when click Sandan Tip
