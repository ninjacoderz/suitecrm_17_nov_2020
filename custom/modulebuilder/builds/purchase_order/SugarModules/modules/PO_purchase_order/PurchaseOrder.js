
function  pushToXero(el){
    
    $(el).find('span.glyphicon-refresh').removeClass('hidden');
    $.ajax({
        url: "/index.php?entryPoint=customCreatePOXeroInvoice&invoice=1&method=put" + '&record='+ encodeURIComponent($('input[name="record"]').val()),
        success: function (data) {

            $(el).find('span.glyphicon-refresh').addClass('hidden');
            var json = $.parseJSON(data);
            if(json.error !=""){
                alert(json.error);
            }
        }
    });
    return false;
}

$(function () {
    'use strict';
    // Generate uinique id
    $( document ).ready(function() {
        /*$("#detailpanel_1").on('change', ".group_name", function(){
            if($(this).
            $(this).after('&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="button pushToXero" title="" onclick="pushToXero(this);" style="\
            width:  auto;\
            padding:  10px;\
            height: 40px;\
        ">\
        Push to Xero\
        </button>'
        )});
        */
        /*$("#line_items_span .group_name").each( function(){
            if($(this).next('.pushToXero').length == 0)
            $(this).after('&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="button pushToXero" title="" onclick="pushToXero(this);" style="\
            width:  auto;\
            padding:  10px;\
            height: 40px;\
        ">\
        Push to Xero\
        <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span>\
        </button>'
        )});
        */
        $("#save_and_continue").after(
            '&nbsp;<button type="button" id="create_xero_invoice" class="button createXeroInvoice" title="Create Xero Invoice" onClick="pushToXero(this);" > Create Xero Invoice <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span> </button>'
        )
    });

    
});