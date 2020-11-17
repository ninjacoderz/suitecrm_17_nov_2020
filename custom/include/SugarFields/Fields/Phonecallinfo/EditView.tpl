{php}

{/php}

{assign var="value" value={{sugarvar key='value' string=true}}}
<style type="text/css">
    #daikininfo td{ldelim}
        padding-bottom: 20px;
    {ldelim}
</style>
<script src="custom/include/SugarFields/Fields/Phonecallinfo/js/phonecallinfo.js"></script>

<script type="text/javascript" language="javascript">

</script>

<input type="hidden" name='{{sugarvar key='name'}}' value='{$value}' />

<div class="row edit-view-row" id="phonecallinfo">
    <div class="col-xs-12 col-sm-12 edit-view-row-item">

        <div class="col-sm-1">
            <input type="checkbox" name="4-day-installer-checkbox" id="4-day-installer-checkbox">
        </div>
        <div class="col-sm-6">
            <p>-4 days installer</p>
            <p>Schedule: <span id="4-day-installer-schedule" class="phone-call-schedule"></span></p>
            <p>Complete: <span id="4-day-installer-complete" class="complete"></span></p>
        </div>
        <div class="col-sm-5 edit-view-field " type="varchar">
            <textarea style="width:100%" id="4-day-installer-notes" name="4_day_installer_notes" rows="1" cols="50" title="" tabindex=""></textarea>
        </div>

    </div>

    <div class="col-xs-12 col-sm-12 edit-view-row-item">

        <div class="col-sm-1">
            <input type="checkbox" name="4-day-client-checkbox" id="4-day-client-checkbox">
        </div>
        <div class="col-sm-6">
            <p>-4 days client</p>
            <p>Schedule: <span id="4-day-client-schedule" class="phone-call-schedule"></span></p>
            <p>Complete: <span id="4-day-client-complete" class="complete"></span></p>
        </div>
        <div class="col-sm-5 edit-view-field " type="varchar">
            <textarea style="width:100%" id="4-day-client-notes" name="4-day-client_notes" rows="1" cols="50" title="" tabindex=""></textarea>
        </div>

        <!-- [/hide] -->
    </div>

    <div class="col-xs-12 col-sm-12 edit-view-row-item">

        <div class="col-sm-1">
            <input type="checkbox" name="1-day-installer-checkbox" id="1-day-installer-checkbox">
        </div>
        <div class="col-sm-6">
            <p>-1 day installer</p>
            <p>Schedule: <span id="1-day-installer-schedule" class="phone-call-schedule"></span></p>
            <p>Complete: <span id="1-day-installer-complete" class="complete"></span></p>
        </div>
        <div class="col-sm-5 edit-view-field " type="varchar">
            <textarea style="width:100%" id="1-day-installer-notes" name="1-day-installer_notes" rows="1" cols="50" title="" tabindex=""></textarea>
        </div>

        <!-- [/hide] -->
    </div>

    <div class="col-xs-12 col-sm-12 edit-view-row-item">

        <div class="col-sm-1">
            <input type="checkbox" name="1-before-client-checkbox" id="1-before-client-checkbox">
        </div>
        <div class="col-sm-6">
            <p>-1 before client</p>
            <p>Schedule: <span id="1-before-client-schedule" class="phone-call-schedule"></span></p>
            <p>Complete: <span id="1-before-client-complete" class="complete"></span></p>
        </div>
        <div class="col-sm-5 edit-view-field " type="varchar">
            <textarea style="width:100%" id="1-before-client-notes" name="1-before-client_notes" rows="1" cols="50" title="" tabindex=""></textarea>
        </div>

        <!-- [/hide] -->
    </div>

    <div class="col-xs-12 col-sm-12 edit-view-row-item">

        <div class="col-sm-1">
            <input type="checkbox" name="morning-installer-checkbox" id="morning-installer-checkbox">
        </div>
        <div class="col-sm-6">
            <p>morning installer</p>
            <p>Schedule: <span id="morning-installer-schedule" class="phone-call-schedule"></span></p>
            <p>Complete: <span id="morning-installer-complete" class="complete"></span></p>
        </div>
        <div class="col-sm-5 edit-view-field " type="varchar">
            <textarea style="width:100%" id="morning-installer-notes" name="morning_installer_notes" rows="1" cols="50" title="" tabindex=""></textarea>
        </div>

        <!-- [/hide] -->
    </div>

    <div class="col-xs-12 col-sm-12 edit-view-row-item">

        <div class="col-sm-1">
            <input type="checkbox" name="morning-customer-checkbox" id="morning-customer-checkbox">
        </div>
        <div class="col-sm-6">
            <p>morning customer</p>
            <p>Schedule: <span id="morning-customer-schedule" class="phone-call-schedule"></span></p>
            <p>Complete: <span id="morning-customer-complete" class="complete"></span></p>
        </div>
        <div class="col-sm-5 edit-view-field " type="varchar">
            <textarea style="width:100%" id="morning-customer-notes" name="morning-customer_notes" rows="1" cols="50" title="" tabindex=""></textarea>
        </div>

        <!-- [/hide] -->
    </div>

    <div class="col-xs-12 col-sm-12 edit-view-row-item">

        <div class="col-sm-1">
            <input type="checkbox" name="evening-customer-checkbox" id="evening-customer-checkbox">
        </div>
        <div class="col-sm-6">
            <p>evening customer</p>
            <p>Schedule: <span id="evening-customer-schedule" class="phone-call-schedule"></span></p>
            <p>Complete: <span id="evening-customer-complete" class="complete"></span></p>
        </div>
        <div class="col-sm-5 edit-view-field " type="varchar">
            <textarea style="width:100%" id="evening-customer-notes" name="evening-customer_notes" rows="1" cols="50" title="" tabindex=""></textarea>
        </div>

        <!-- [/hide] -->
    </div>

    <div class="col-xs-12 col-sm-12 edit-view-row-item">

        <div class="col-sm-1">
            <input type="checkbox" name="3-days-client-checkbox" id="3-days-client-checkbox">
        </div>
        <div class="col-sm-6">
            <p>+3 days client</p>
            <p>Schedule: <span id="3-days-client-schedule" class="phone-call-schedule"></span></p>
            <p>Complete: <span id="3-days-client-complete" class="complete"></span></p>
        </div>
        <div class="col-sm-5 edit-view-field " type="varchar">
            <textarea style="width:100%" id="3-days-client-notes" name="3-days-client_notes" rows="1" cols="50" title="" tabindex=""></textarea>
        </div>

        <!-- [/hide] -->
    </div>
</div>

<script type="text/javascript" language="javascript">
    var phonecallinfo = '{$value}';
    var phonecallinfoJSONObject =(phonecallinfo!="") ? JSON.parse(decodeURIComponent(phonecallinfo)) : (new Array());
    console.log(phonecallinfoJSONObject);


    $("#4-day-installer-checkbox").prop('checked', phonecallinfoJSONObject.four_day_installer_checkbox);
    $("#4-day-installer-complete").text(phonecallinfoJSONObject.four_day_installer_complete );
    $("#4-day-installer-notes").val(phonecallinfoJSONObject.four_day_installer_notes);

    $("#morning-installer-checkbox").prop('checked', phonecallinfoJSONObject.morning_installer_checkbox);
    $("#morning-installer-complete").text(phonecallinfoJSONObject.morning_installer_complete );
    $("#morning-installer-notes").val(phonecallinfoJSONObject.morning_installer_notes);

    $("#4-day-client-checkbox").prop('checked', phonecallinfoJSONObject.four_day_client_checkbox);
    $("#4-day-client-complete").text(phonecallinfoJSONObject.four_day_client_complete);
    $("#4-day-client_notes").val(phonecallinfoJSONObject.four_day_client_notes);

    $("#morning-customer-checkbox").prop('checked', phonecallinfoJSONObject.morning_customer_checkbox);
    $("#morning-customer-complete").text(phonecallinfoJSONObject.morning_customer_complete);
    $("#morning-customer-notes").val(phonecallinfoJSONObject.morning_customer_notes);

    $("#1-day-installer-checkbox").prop('checked',phonecallinfoJSONObject.one_day_installer_checkbox);
    $("#1-day-installer-complete").text(phonecallinfoJSONObject.one_day_installer_complete);
    $("#1-day-installer_notes").val(phonecallinfoJSONObject.one_day_installer_notes);

    $("#midday-installer-checkbox").prop('checked', phonecallinfoJSONObject.midday_installer_checkbox);
    $("#midday-installer-complete").text(phonecallinfoJSONObject.midday_installer_complete);
    $("#midday-installer_notes").val(phonecallinfoJSONObject.midday_installer_notes);

    $("#1-before-client-checkbox").prop('checked', phonecallinfoJSONObject.one_day_client_checkbox);
    $("#1-before-client-complete").text(phonecallinfoJSONObject.one_day_client_complete);
    $("#1-before-client-notes").val(phonecallinfoJSONObject.one_day_client_notes);


    $("#evening-customer-checkbox").prop('checked', phonecallinfoJSONObject.evening_customer_checkbox);
    $("#evening-customer-complete").text(phonecallinfoJSONObject.evening_customer_complete);
    $("#evening-customer-notes").val(phonecallinfoJSONObject.evening_customer_notes);

    $("#3-days-client-checkbox").prop('checked', phonecallinfoJSONObject.three_day_client_checkbox);
    $("#3-days-client-complete").text(phonecallinfoJSONObject.three_day_client_complete);
    $("#3-days-client_notes").val(phonecallinfoJSONObject.three_day_client_notes);



</script>