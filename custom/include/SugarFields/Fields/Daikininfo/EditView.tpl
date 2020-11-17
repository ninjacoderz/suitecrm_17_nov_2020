{php}

{/php}

{assign var="value" value={{sugarvar key='value' string=true}}}
<style type="text/css">
    #daikininfo td{ldelim}
        padding-bottom: 20px;
    {ldelim}
</style>
<script src="custom/include/SugarFields/Fields/Daikininfo/js/daikininfo.js"></script>

<script type="text/javascript" language="javascript">

    function delDaikinTextRow(e)
    {ldelim}
        jQuery(e).parent().parent().remove(); 
        updateInputDaikinValue();
    {rdelim}

    function addDaikinTextRow(product_name , veet_code, indoor_model , indoor_serial , outdoor_model, outdoor_serial, date_delivered, date_ordered, order_confirmed, wifi )
    {ldelim}

        if(product_name === undefined) product_name ="";
        if(veet_code === undefined) veet_code ="";
        if(indoor_model === undefined) indoor_model ="";
        if(indoor_serial === undefined) indoor_serial ="";
        if(outdoor_model === undefined) outdoor_model ="";
        if(outdoor_serial === undefined) outdoor_serial ="";
        if(date_delivered === undefined) date_delivered ="";
        if(order_confirmed === undefined) order_confirmed ="";
        if(date_ordered === undefined) date_ordered ="";
        if(wifi === undefined) wifi ="true";

        var table = document.getElementById("daikininfo");
        var rowCount = table.rows.length;

        var newRow = table.insertRow(rowCount);

        newRow.id = "daikininfo_" + (rowCount + 1 );

        // it's quite ugly code but cross-browser
        var productNameTD = document.createElement('td');
        productNameTD.align='center';
        productNameTD.innerHTML = '<select name="daikin_info_product_name"> ' +
            '<option value="">- Product Name -</option>' +
            '<option value="Nexura 2.5kW" '+ ((product_name=="Nexura 2.5kW")?"selected":"") +'>Nexura 2.5kW</option>' +
            '<option value="Nexura 3.5kW" '+ ((product_name=="Nexura 3.5kW")?"selected":"") +'>Nexura 3.5kW</option>' +
            '<option value="Nexura 4.8kW" '+ ((product_name=="Nexura 4.8kW")?"selected":"") +'>Nexura 4.8kW</option>' +
            '<option value="US7 2.5kW small" '+ ((product_name=="US7 2.5kW small")?"selected":"") +'>US7 2.5kW small</option>' +
            '<option value="US7 2.5kW small" '+ ((product_name=="US7 2.5kW small")?"selected":"") +'>US7 2.5kW small</option>' +
            '<option value="US7 3.5kW medium" '+ ((product_name=="US7 3.5kW medium")?"selected":"") +'>US7 3.5kW medium</option>' +
            '<option value="US7 5.0kW large"  '+ ((product_name=="US7 5.0kW large")?"selected":"") +'>US7 5.0kW large</option>' +
            '<option value="Cora 2kW" '+ ((product_name=="Cora 2kW")?"selected":"") +'>Cora 2kW</option>' +
            '<option value="Cora 2.5kW" '+ ((product_name=="Cora 2.5kW")?"selected":"") +'>Cora 2.5kW</option>' +
            '<option value="Cora 3.5kW" '+ ((product_name=="Cora 3.5kW")?"selected":"") +'>Cora 3.5kW</option>' +
            '<option value="Cora 4.6kW" '+ ((product_name=="Cora 4.6kW")?"selected":"") +'>Cora 4.6kW</option>' +
            '<option value="Cora 5kW" '+ ((product_name=="Cora 5kW")?"selected":"") +'>Cora 5kW</option>' +
            '<option value="Cora 6kW" '+ ((product_name=="Cora 6kW")?"selected":"") +'>Cora 6kW</option>' + 
            '</select>'; //value="'+ product_name +'"
        newRow.appendChild (productNameTD);

        var veetCodeTD = document.createElement('td');
        veetCodeTD.align='center';
        veetCodeTD.innerHTML = '<input placeholder="VEET Code" type="text" name="veet_code" size="30" value="'+ veet_code +'">';
        newRow.appendChild (veetCodeTD);

        var indoorModelTD = document.createElement('td');
        indoorModelTD.align='center';
        indoorModelTD.innerHTML = '<input placeholder="Indoor Model" type="text" name="indoor_model" size="30" value="'+ indoor_model +'">';
        newRow.appendChild (indoorModelTD);

        var indoorSerialTD = document.createElement('td');
        indoorSerialTD.align='center';
        indoorSerialTD.innerHTML = '<input placeholder="Indoor Serial" type="text" name="indoor_serial" size="30" value="'+ indoor_serial +'">';
        newRow.appendChild (indoorSerialTD);

        var outdoorModelTD = document.createElement('td');
        outdoorModelTD.align='center';
        outdoorModelTD.innerHTML = '<input placeholder="Outdoor Model" type="text" name="outdoor_model" size="30" value="'+ outdoor_model +'">';
        newRow.appendChild (outdoorModelTD);

        var outdoorSerialTD = document.createElement('td');
        outdoorSerialTD.align='center';
        outdoorSerialTD.innerHTML = '<input placeholder="Outdoor Serial" type="text" name="outdoor_serial" size="30" value="'+ outdoor_serial +'">';
        newRow.appendChild (outdoorSerialTD);

        var dateTD = document.createElement('td');
        dateTD.align='center';
        dateTD.innerHTML = '<span><input type="text" placeholder="Date delivered"  name="date_delivered" id="date_delivered_'+ (rowCount + 1 ) +'" size="30" value="'+ date_delivered +'">' +
            '<img src="{sugar_getimagepath file="jscalendar.gif"}" alt="Enter Date" style="position:relative; top:6px" id="date_delivered_'+ (rowCount + 1 ) + 'trigger" border="0"></span>';
        newRow.appendChild (dateTD);

        Calendar.setup (
            {ldelim}
                inputField : "date_delivered_" + (rowCount + 1 ),
                form : "EditView",
                ifFormat : "%d/%m/%Y %H:%M",
                daFormat : "%d/%m/%Y %H:%M",
                button : "date_delivered_"+ (rowCount + 1 ) + "trigger",
                singleClick : true,
                dateStr : "",
                startWeekday: 0,
                step : 1,
                weekNumbers:false
                {rdelim}
        );

        var orderedDateTD = document.createElement('td');
        orderedDateTD.align='center';
        orderedDateTD.innerHTML = '<span><input type="text" placeholder="Date ordered"  name="date_ordered" id="date_ordered_'+ (rowCount + 1 ) +'" size="30" value="'+ date_ordered +'">' +
            '<img src="{sugar_getimagepath file="jscalendar.gif"}" alt="Enter Date" style="position:relative; top:6px" id="date_ordered_'+ (rowCount + 1 ) + 'trigger" border="0"></span>';
        newRow.appendChild (orderedDateTD);
        YAHOO.util.Event.addListener("date_delivered_"+ (rowCount + 1 ), "change", updateInputDaikinValue);

        Calendar.setup (
            {ldelim}
                inputField : "date_ordered_" + (rowCount + 1 ),
                form : "EditView",
                ifFormat : "%d/%m/%Y %H:%M",
                daFormat : "%d/%m/%Y %H:%M",
                button : "date_ordered_"+ (rowCount + 1 ) + "trigger",
                singleClick : true,
                dateStr : "",
                startWeekday: 0,
                step : 1,
                weekNumbers:false
                {rdelim}
        );

        var orderConfirmed = document.createElement('td');
        orderConfirmed.align='center';
        orderConfirmed.innerHTML = '<input  type="checkbox" name="order_confirmed" size="30" value="order_confirmed" ' +
            (order_confirmed?'checked':'') +
            '>';

        newRow.appendChild (orderConfirmed);
        YAHOO.util.Event.addListener("date_ordered_"+ (rowCount + 1 ), "change", updateInputDaikinValue);

        var wifiTD = document.createElement('td');
        wifiTD.align='center';
        wifiTD.innerHTML = '<input placeholder="Outdoor Serial" type="checkbox" name="wifi" size="30" value="wifi" ' +
            (wifi?'checked':'') +
            '>';

        newRow.appendChild (wifiTD);

        var removeTD = document.createElement('td');
        removeTD.innerHTML = '<button value="Add More" onclick="javascript:delDaikinTextRow(this)" type="button" class="button">' +
            '<i class="glyphicon glyphicon-minus"></i>' +
            '</button> ';
        newRow.appendChild(removeTD);
    {rdelim}

</script>

<input type="hidden" name='{{sugarvar key='name'}}' value='{$value}' />

<table class="daikininfo" id="daikininfo">
    <tr>
        <td nowrap="" scope="row">
                <span class="id-ff multiple ownline">
                <button value="Add More" onclick="javascript:addDaikinTextRow()" type="button" class="button">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
                Product name
                </span>
        </td>

        <td nowrap="" scope="row">
            VEET Code
        </td>

        <td nowrap="" scope="row">
            Indoor Model #
        </td>


        <td scope="row" NOWRAP>
            Indoor Serial #
        </td>

        <td nowrap="" scope="row">
            Outdoor Model #
        </td>

        <td nowrap="" scope="row">
            Outdoor Serial #
        </td>

        <td nowrap="" scope="row">
            Delivered
        </td>

        <td nowrap="" scope="row">
            Ordered
        </td>

        <td nowrap="" scope="row">
            Confirmed
        </td>
        <td nowrap="" scope="row">
            Wifi
        </td>

        <td nowrap="" scope="row">

        </td>

    </tr>
    <br/>
</table>

<script type="text/javascript" language="javascript">

    var daikininfo = '{$value}';
    var daikininfoJSONObject =(daikininfo!="") ? JSON.parse(decodeURIComponent(daikininfo)) : (new Array());

    if(daikininfoJSONObject.length > 0)
    {ldelim}
        for(var i in daikininfoJSONObject)
            {ldelim}
                var e  = daikininfoJSONObject[i];
                addDaikinTextRow(e.product_name ,e.veet_code, e.indoor_model , e.indoor_serial , e.outdoor_model, e.outdoor_serial, e.date_delivered,e.date_ordered, e.order_confirmed, e.wifi);
            {rdelim}
    {rdelim}

    // Always add a blank textrow
    addDaikinTextRow();


</script>