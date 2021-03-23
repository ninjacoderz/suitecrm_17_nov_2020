
$(function () {
    'use strict';
// Generate uinique id
    $("#btn_clr_assigned_user_name").after('<button style="width: 150px;" id="update_relates" class="button update_relates">Update Related <span class="glyphicon hidden glyphicon-refresh glyphicon-refresh-animate"></span></button>');
    var record = $("input[name='record']").val();

    if(typeof record !== "undefined" && record != ""){
        $("#update_relates").click(function(){
            var assigned_id = $("#assigned_user_id").val();
            $('#update_relates span.glyphicon-refresh').removeClass('hidden');
            $.ajax({
                url: "/index.php?entryPoint=customUpdateRelated&bean_type=opportunity&record="+record +"&assigned_id="+assigned_id,
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

    // Get solargain link

    $.ajax({
        url: "/index.php?entryPoint=customGetSolarGainValues&record="+record,
        //data: {q:request.term},
        //crossOrigin: true,
        type: 'GET',
        //async: false,
        //crossDomain: true,
        //dataType: 'jsonp',

        success: function(data)
        {
            //$('#update_relates span.glyphicon-refresh').addClass('hidden');
            // BinhNT
            var json_data = $.parseJSON(data);
            $("#opportunities_opportunities_1_name").parent().after("<div>Solar Gain Lead:<a target='_blank' href='https://crm.solargain.com.au/lead/edit/"+json_data['lead']+"'>Lead</a></div>" + "<div>Solar Gain Quote:<a target='_blank' href='https://crm.solargain.com.au/quote/edit/"+json_data['quote']+"'>Quote</a></div>");

        },
        error: function(response){console.log("Fail");},
    });
});

//dung code - display link <a>
$(document).ready(function(){
    function display_solar_monitoring(){
        $('#link_solar_monitoring').remove();
        if($('#solar_monitoring_c').val()!== ''){
            $('#solar_monitoring_c').parent().append('<a id="link_solar_monitoring" target="_blank" href="https://www.solarweb.com/PvSystems/PvSystem?pvSystemId='+$('#solar_monitoring_c').val() +'">Open Solar Monitoring</a>');
        }
    };
    $(document).on("load","#solar_monitoring_c",display_solar_monitoring());
    $('#solar_monitoring_c').blur(function(){display_solar_monitoring()});
})