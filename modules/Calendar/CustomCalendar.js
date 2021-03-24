(function ($) {
   // dung code - auto check value in Popup meeting at Home Page
    function changeFormatDate(date_time){
        var value = date_time.split('/');
        return value[1] +'/' +value[0] + '/' +value[2];
    }

    YAHOO.util.Event.addListener("date_end_date", "change", function(){
        var date_time_start = $("#date_start").val();
        var date_time_end = $("#date_end").val();
        date_time_end = changeFormatDate(date_time_end);
        date_time_start = changeFormatDate(date_time_start);
        var time_stamp_date_time_start = new Date(date_time_start);
        var time_stamp_date_time_end = new Date(date_time_end);
        if(time_stamp_date_time_start >=  time_stamp_date_time_end) {
            alert("Time Invalid");
        }
    });

    //dung code - resize Font size Repeat
    $("#CalendarRepeatForm table tbody tr td").first().remove();
    $("#CalendarRepeatForm table tbody tr td").first().before('<td width="12.5%" valign="top" scope="row"><h3>Repeat:</h3></td>');

    //dung code- reload page update info calendar when click save and remove-all-recurrences
    $("#btn-save, #btn-remove-all-recurrences").on("click",function(){
          // tuan code update date to WH log
          var  meeting_delivery = $('#name').val().substr(0,7);
          var link_wh = $('#link_to_warehouse_log_c').val();
          if( meeting_delivery == "Dispatc" ){
              var dispatch_date = $('#date_start').val();
              $.ajax({
                  url: "index.php?entryPoint=createMettingWHLog&record_id=" + link_wh +"&dispatch_date="+dispatch_date+"&update_met=update_to_WH_log",
                  type: 'GET',
                  async: false,
                  success: function(data){
                      console.log('update seccess!')
                  }
              });
          }else if( meeting_delivery == "Arrival" ){
              var arrival_date = $('#date_start').val();
              $.ajax({
                  url: "index.php?entryPoint=createMettingWHLog&record_id=" + link_wh +"&arrival_date="+arrival_date+"&update_met=update_to_WH_log",
                  type: 'GET',
                  async: false,
                  success: function(data){
                      console.log('update seccess!')
                  }
              });
          }
        location.reload();
    });
    // tuan code add background-color calender
    $('#calendarContainer .fc-content-skeleton .fc-event-container .fc-content .fc-title').each(function(){
     var  meeting_delivery = $(this).html().substr(0,7);
        if( meeting_delivery == "Dispatc" ){
            $(this).parent().parent().css({'background-color':'#8cfd67','color':'black'});
        }else if( meeting_delivery == "Arrival" ){
            $(this).parent().parent().css({'background-color':'#f95858','color':'black'})
        } else if (meeting_delivery == "Meeting") { 
            $(this).parent().parent().css({'background-color':'#15c9e6','color':'black'});
        }
    }) 
}(jQuery));