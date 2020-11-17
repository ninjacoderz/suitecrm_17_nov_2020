<div class="panel panel-default panel-email-compose">
    <div class="panel-body">
        {*<input title="Save" accesskey="a" class="button primary" onclick="" type="submit" name="button" value="Save" id="SAVE">*}
        {*<input title="Cancel [Alt+l]" accesskey="l" class="button" onclick="SUGAR.ajaxUI.loadContent('index.php?action=DetailView&amp;module=FP_events&amp;record=cb676657-b74f-c928-9967-58c146121ee9'); return false;" name="button" value="Cancel" type="button" id="CANCEL">*}
         {{if !empty($form) && !empty($form.buttons)}}
            <div class="custom-buttons">
               <div class="sms-schedule">
                  <button id="btnSendLater" type="button">Set schedule</button>
                  <button class="button" id="btnAttachFile_in_CRM" type="button">Attach Files</button>
                  <div class="popup popup-sendMail-email" id="sendMailLater">
                     <ul class=listOption>
                           <div class="form-control">
                                 <li class="optionItem chooseAction">In 1 hour</li>
                                 <li class="optionItem chooseAction">In 2 hours</li>
                                 <li class="optionItem chooseAction">In 3 hours</li>
                           </div>
                           <hr>
                           <div class="form-control">
                                 <li class="optionItem chooseAction">In 2 days</li>
                                 <li class="optionItem chooseAction">In 4 days</li>
                           </div>
                           <hr>
                           <div class="form-control">
                              <p class='pd30'>At a specific time:</p> 
                              <div class='confirm'>
                                 <span class='pd30'>Examples: 'Monday 9am', 'Dec 23'</span>
                                 </div>
                              <div class='pd30 mt10'>
                                 <input class='' id='datetimepicker' name='datetimepicker' value='' title='datetimepicker' /></div>
                                 <div class='pd30 mt10'>
                                       <p class='valueTime'></p>
                                       <a href='javascript:void(0)' class='btnSuccess'>Confirm</a>
                                 </div>
                              </div>
                           </div>
                     </ul>
                  </div>
               </div>
               {{foreach from=$form.buttons key=val item=button}}
                  {{sugar_button module="$module" id="$button" form_id="$form_id" view="$view"}}
               {{/foreach}}
            </div>
         {{/if}}
    </div>
</div>
{literal}
<style>
   #btnSendLater ,#btnAttachFile_in_CRM {
      background-color: #d14836;
      font-size: .875rem;
      letter-spacing: .25px;
      border-radius: 4px;
      box-sizing: border-box;
      color: white;
      cursor: pointer;
      font-weight: 500;
      height: 36px;
      min-width: 80px;
      padding: 0 16px;
      border: none;
      font-size: 15px;
      margin-top: 10px;
   }

   #btnSendLater:focus , #btnAttachFile_in_CRM:focus {
      outline: none;
   }

   #btnSendLater:hover , #btnAttachFile_in_CRM:hover {
      background: #E0513E !important;
      box-shadow: 0 1px 3px 0 rgba(224, 81, 62, 0.74) !important;
   }

   .sms-schedule .popup {
      display: none;
      position: absolute;
      overflow-x: hidden;
      left: 15px;
      padding: 10px 0;
      bottom: 80px;
      z-index: 1000;
      background-color: white;
      user-select: none;
      min-width: 17em;
      max-width: 20em;
      width: 100%;
      overflow-y: auto;
      border-radius: 3px;
      box-shadow: 0 8px 10px 1px rgba(0, 0, 0, 0.14), 0 3px 14px 2px rgba(0, 0, 0, 0.12), 0 5px 5px -3px rgba(0, 0, 0, 0.2);
      font-size: 14px;
      font-family: 'Roboto', sans-serif;
   }

   .sms-schedule .popup .listOption li {
      padding: 5px 0;
      cursor: pointer;
   }

   .sms-schedule .popup .listOption li.chooseAction {
      padding: 5px 20px;
   }

   .sms-schedule .popup .listOption li.chooseAction:hover {
      background: #eeeeee;
   }

   .sms-schedule .popup .listOption span.setting {
      float: right;
      cursor: pointer;
   }

   .sms-schedule .popup .confirm span {
      font-size: 10px;
      font-style: italic;
      padding-top: 5px;
   }

   .sms-schedule .popup .form-control {
      background: none;
      border: none;
      height: auto;
      width: auto;
      padding: 0;
      line-height: 1
   }

   .sms-schedule .popup hr {
      background: gray;
      margin: 5px auto;
   }

   .sms-schedule .pd30 {
      padding: 0 20px;
   }

   .sms-schedule .popup.active-popup-sms {
      display: block;
   }

   .k-picker-wrap {
      width: 230px !important;
      padding: 0 !important;
   }

   input#datetimepicker {
      width: 160px !important;
      background: none;
   }

   .sms-schedule .mt10 {
      margin: 10px 0;
   }

   .sms-schedule .btnSuccess {
      width: 200px;
      text-align: center;
      background: #1a73e8;
      padding: 10px 20px;
      vertical-align: middle;
      line-height: 40px;
      color: white;
      text-decoration: none;
      margin-top: 10px;
      border-radius: 5px;
      font-weight: 500;
   }

   .k-icon {
      background-repeat: no-repeat;
      background-position: center;
      background-size: contain;
   }

   .k-icon.k-i-calendar {
      background-image: url('themes/SuiteP/js/schedule/clock.png');
   }

   .k-icon.k-i-clock {
      background-image: url('themes/SuiteP/js/schedule/timer.png');
   }

   .k-icon.k-i-arrow-60-left {
      background-image: url('themes/SuiteP/js/schedule/back.png');
   }

   .k-icon.k-i-arrow-60-right {
      background-image: url('themes/SuiteP/js/schedule/nexty.png');
   }

   .k-icon.k-i-calendar::before, .k-icon.k-i-clock::before, .k-icon.k-i-arrow-60-left::before, .k-icon.k-i-arrow-60-right::before {
      content: '';
   }

   .result-schedule {
      color: green;
   }

   /* Center the loader */
   #icon_loader {
   position: absolute;
   left: 50%;
   top: 50%;
   z-index: 1;
   width: 150px;
   height: 150px;
   margin: -75px 0 0 -75px;
   border: 16px solid #f3f3f3;
   border-radius: 50%;
   border-top: 16px solid #3498db;
   width: 120px;
   height: 120px;
   -webkit-animation: spin 2s linear infinite;
   animation: spin 2s linear infinite;
   }

   @-webkit-keyframes spin {
   0% { -webkit-transform: rotate(0deg); }
   100% { -webkit-transform: rotate(360deg); }
   }

   @keyframes spin {
   0% { transform: rotate(0deg); }
   100% { transform: rotate(360deg); }
   }
</style>
{/literal}
<script type="text/javascript" src="themes/SuiteP/js/schedule/kendo.all.min.js"></script>
<link rel='stylesheet' href='themes/SuiteP/js/schedule/kendo.common.min.css'>
<link rel='stylesheet' href='themes/SuiteP/js/schedule/kendo.common-material.min.css'>
<link rel='stylesheet' href='themes/SuiteP/js/schedule/kendo.default.min.css'>
{literal}
   <script>
      $(function(){
         $("#schedule_timestamp_c").closest('.edit-view-row-item').hide();
         $('#btnSendLater').click(function(e){
            $('.popup-sendMail-email').toggleClass('active-popup-sms');
            var datetime_stamp = $("#schedule_timestamp_c").val();
            if(datetime_stamp != '') {
               $("#datetimepicker").kendoDateTimePicker({
                  value : new Date($("#schedule_timestamp_c").val() * 1000)
               });
            }
         })
         $("#datetimepicker").kendoDateTimePicker();
         var datepicker = $("#datetimepicker").data("kendoDatePicker");
         var setScheduleTime = function (date_schedule) {
            $("#datetimepicker").kendoDateTimePicker({
               value : new Date(date_schedule)
            })
         };

         var defaultDateTime = function(date){
            var now     = date;
            var year    = now.getFullYear();
            var month   = now.getMonth()+1; 
            var day     = now.getDate();
            var hour    = now.getHours();
            var minute  = now.getMinutes();
            return {'year':year,'month':month,'day':day,'hour':hour,'minute':minute}
         }
         var getDateTime = function(option){
         var date_return = '';
         var date = new Date();
         switch(option){
               case 'In 1 hour':
                  var data = defaultDateTime(new Date());
                  date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+(data['hour']+1)+':'+data['minute'];
                  break;
               case 'In 2 hours':
                  var data = defaultDateTime(new Date());
                  date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+(data['hour']+2)+':'+data['minute'];; 
                  break;
               case 'In 3 hours':
                  var data = defaultDateTime(new Date());
                  date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+(data['hour']+3)+':'+data['minute'];; 
                  break;
               case 'In 2 days':
                  
                  var data = defaultDateTime(new Date(date.getTime() + 2*(24 * 60 * 60 * 1000)));
                  date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute']; 
                  break;
               case 'In 4 days':
                  var data = defaultDateTime(new Date(date.getTime() + 4*(24 * 60 * 60 * 1000)));
                  date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute']; 
                  break;
               case 'In 1 Week':
                  var data = defaultDateTime(new Date(date.getTime() + 7*(24 * 60 * 60 * 1000)));
                  date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute']; 
                  break;
               case 'In 2 Weeks':
                  var data = defaultDateTime(new Date(date.getTime() + 14*(24 * 60 * 60 * 1000)));
                  date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute']; 
                  break;
               case 'In 1 Month':
                  var data = defaultDateTime(new Date(date.getFullYear(), date.getMonth()+1, date.getDate()));
                  date_return = data['year']+'-'+data['month']+'-'+data['day']+' '+data['hour']+':'+data['minute'];
                  break;
            }

            return date_return;

         }

         $(".listOption").find('li').click(function(){
            var option = $(this).html();
            setScheduleTime(getDateTime(option));
         });
      });
   </script>
{/literal}
