Date.prototype.addDays = function(days) {
    var date = new Date(this.getTime())
    date.setDate(this.getDate() + parseInt(days));
    return date;
};
$(function () {
    'use strict';
    $("#phonecallinfo").on("change", ["input", "textarea"],  function(){
        updateInputPhoneCallValue();
    });

    //$(".phone-call-schedule").text($("#installation_date_c_date").val() + " " + $("#installation_date_c_hours").val() + ":" + $("#installation_date_c_minutes").val());

    var parts = $("#installation_date_c_date").val().split('/');
    var mydate = new Date(parts[2],parts[1]-1,parts[0]);

    var fourdayBefore = mydate.addDays(-4);
    var threedayAfter = mydate.addDays(3);
    var onedayBefore = mydate.addDays(-1);

    $("#4-day-installer-schedule").text(fourdayBefore.getDate()+ "/" + (fourdayBefore.getMonth() + 1 )+ "/" + fourdayBefore.getFullYear() +" "
        + "08" + ":" + "00" );
    $("#4-day-client-schedule").text(fourdayBefore.getDate()+ "/" + (fourdayBefore.getMonth() + 1 )+ "/" + fourdayBefore.getFullYear() +" "
        + "08" + ":" + "00" );
    $("#morning-customer-schedule").text($("#installation_date_c_date").val() + " " + "8" + ":" + "00");
    $("#morning-installer-schedule").text($("#installation_date_c_date").val() + " " + "8" + ":" + "00");

    $("#1-day-installer-schedule").text(onedayBefore.getDate()+ "/" + (onedayBefore.getMonth() + 1 )+ "/" + onedayBefore.getFullYear() +" "
        + "08" + ":" + "00" );

    $("#1-before-client-schedule").text(onedayBefore.getDate()+ "/" + (onedayBefore.getMonth() + 1 )+ "/" + onedayBefore.getFullYear() +" "
        + "08" + ":" + "00" );

    $("#midday-installer-schedule").text($("#installation_date_c_date").val() + " " + "11" + ":" + "00");

    $("#evening-customer-schedule").text($("#installation_date_c_date").val() + " " + "15" + ":" + "00");

    $("#3-days-client-schedule").text(threedayAfter.getDate()+ "/" + (threedayAfter.getMonth() + 1 )+ "/" + threedayAfter.getFullYear() +" "
        + "08" + ":" + "00" );


    YAHOO.util.Event.addListener("installation_date_c_date", "change",
        function(){
            var parts = $("#installation_date_c_date").val().split('/');
            var mydate = new Date(parts[2],parts[1]-1,parts[0]);

            var fourdayBefore = mydate.addDays(-4);
            var threedayAfter = mydate.addDays(+3);
            var onedayBefore = mydate.addDays(-1);
        $("#4-day-installer-schedule").text(fourdayBefore.getDate()+ "/" + (fourdayBefore.getMonth() + 1 )+ "/" + fourdayBefore.getFullYear() +" "
            + "08" + ":" + "00" );
        $("#4-day-client-schedule").text(fourdayBefore.getDate()+ "/" + (fourdayBefore.getMonth() + 1 )+ "/" + fourdayBefore.getFullYear() +" "
                + "08" + ":" + "00" );
        $("#morning-customer-schedule").text($("#installation_date_c_date").val() + " " + "8" + ":" + "00");
        $("#morning-installer-schedule").text($("#installation_date_c_date").val() + " " + "8" + ":" + "00");

        $("#1-day-installer-schedule").text(onedayBefore.getDate()+ "/" + (onedayBefore.getMonth() + 1 )+ "/" + onedayBefore.getFullYear() +" "
                + "08" + ":" + "00" );

        $("#1-before-client-schedule").text(onedayBefore.getDate()+ "/" + (onedayBefore.getMonth() + 1 )+ "/" + onedayBefore.getFullYear() +" "
                + "08" + ":" + "00" );

        $("#midday-installer-schedule").text($("#installation_date_c_date").val() + " " + "11" + ":" + "00");

        $("#evening-customer-schedule").text($("#installation_date_c_date").val() + " " + "15" + ":" + "00");

        $("#3-days-client-schedule").text(threedayAfter.getDate()+ "/" + (threedayAfter.getMonth() + 1 )+ "/" + threedayAfter.getFullYear() +" "
                + "08" + ":" + "00" );
    });

    $("#phonecallinfo input[type='checkbox']").change(function(){
        if($(this).is(":checked")){
            var dt = new Date();
            var time = dt.getDate()+ "/" + (dt.getMonth() + 1 )+ "/" + dt.getFullYear() +" "+ dt.getHours() + ":" + (dt.getMinutes()<10?'0':'') + dt.getMinutes() ;
            $(this).parent().parent().find(".complete").text(time);
        }
    });

    $( document ).ready(function() {

    });
});

function updateInputPhoneCallValue(){

    var phonecallInfo = new Object();
    phonecallInfo.four_day_installer_checkbox = $("#4-day-installer-checkbox").is(":checked");
    phonecallInfo.four_day_installer_complete = $("#4-day-installer-complete").text();
    phonecallInfo.four_day_installer_notes = $("#4-day-installer-notes").val();

    phonecallInfo.morning_installer_checkbox = $("#morning-installer-checkbox").is(":checked");
    phonecallInfo.morning_installer_complete = $("#morning-installer-complete").text();
    phonecallInfo.morning_installer_notes = $("#morning-installer-notes").val();

    phonecallInfo.four_day_client_checkbox = $("#4-day-client-checkbox").is(":checked");
    phonecallInfo.four_day_client_complete = $("#4-day-client-complete").text();
    phonecallInfo.four_day_client_notes = $("#4-day-client_notes").val();

    phonecallInfo.morning_customer_checkbox = $("#morning-customer-checkbox").is(":checked");
    phonecallInfo.morning_customer_complete = $("#morning-customer-complete").text();
    phonecallInfo.morning_customer_notes = $("#morning-customer-notes").val();

    phonecallInfo.one_day_installer_checkbox = $("#1-day-installer-checkbox").is(":checked");
    phonecallInfo.one_day_installer_complete = $("#1-day-installer-complete").text();
    phonecallInfo.one_day_installer_notes = $("#1-day-installer_notes").val();

    phonecallInfo.midday_installer_checkbox = $("#midday-installer-checkbox").is(":checked");
    phonecallInfo.midday_installer_complete = $("#midday-installer-complete").text();
    phonecallInfo.midday_installer_notes = $("#midday-installer_notes").val();

    phonecallInfo.one_day_client_checkbox = $("#1-before-client-checkbox").is(":checked");
    phonecallInfo.one_day_client_complete = $("#1-before-client-complete").text();
    phonecallInfo.one_day_client_notes = $("#1-before-client-notes").val();


    phonecallInfo.evening_customer_checkbox = $("#evening-customer-checkbox").is(":checked");
    phonecallInfo.evening_customer_complete = $("#evening-customer-complete").text();
    phonecallInfo.evening_customer_notes = $("#evening-customer-notes").val();

    phonecallInfo.three_day_client_checkbox = $("#3-days-client-checkbox").is(":checked");
    phonecallInfo.three_day_client_complete = $("#3-days-client-complete").text();
    phonecallInfo.three_day_client_notes = $("#3-days-client_notes").val();

    $('input[name="phone_call_c"]').val(encodeURIComponent(JSON.stringify(phonecallInfo)));
}

