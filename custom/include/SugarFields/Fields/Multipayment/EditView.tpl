{php}
{/php}

{assign var="value" value={{sugarvar key='value' string=true}}}
<style type="text/css">
    #multipayment td{ldelim}
        padding-bottom: 20px;
    {ldelim}
</style>
<script src="custom/include/SugarFields/Fields/Multipayment/js/multipayment.js"></script>

<script type="text/javascript" language="javascript">

    function delTextRow(e)
    {ldelim}
        jQuery(e).parent().parent().remove();
        updatePaymentInfo();
        populatePaymentAmout();
    {rdelim}

    function addTextRow(payment_amount , payment_description , payment_date , payment_brankref )
    {ldelim}
        // fixed check undefined and null
        if(!payment_amount) payment_amount ="";
        if(!payment_description) payment_description ="";
        if(!payment_date) payment_date ="";
        if(!payment_brankref) payment_brankref ="";
        var table = document.getElementById("multipayment");
        var rowCount = table.rows.length;

        var newRow = table.insertRow(rowCount);

        newRow.id = "textRow" + (rowCount + 1 );

        // it's quite ugly code but cross-browser
        var amountTD = document.createElement('td');
        amountTD.align='center';
        amountTD.innerHTML = '<input placeholder="Payment Amount" type="text" name="payment_amount" size="30" value="'+ payment_amount +'">';
        newRow.appendChild (amountTD);

        var descriptionTD = document.createElement('td');
        descriptionTD.align='center';
        {literal}
            //VUT - S - create dropdown Payment Description  https://trello.com/c/pn3AfT9q/3070-invoice-payments-please-get-a-pull-down-for-payment-descriptions
            var payment_options = SUGAR.language.languages.app_list_strings['payment_options_list'];
            //var payment_options = {""  : "","deposit" : "Deposit","part_payment" : "Part Payment","balance_of_payment" : "Balance of Payment","partial_payment" : "Partial Payment","full_payment" : "Full Payment"};
            var option_render = '';
            //debugger
            for (var key in payment_options) {
                var opt_value = payment_options[key];
                if (opt_value.toLowerCase() == payment_description.toLowerCase()) {
                    option_render += '<option selected="selected" name="'+key+'" label="'+opt_value+'" value="'+opt_value+'">'+opt_value+'</option>';
                } else {
                    option_render += '<option label="'+opt_value+'" name="'+key+'" value="'+opt_value+'">'+opt_value+'</option>';
                }
            }
            //VUT - E - create dropdown Payment Description  https://trello.com/c/pn3AfT9q/3070-invoice-payments-please-get-a-pull-down-for-payment-descriptions
        {/literal}
        descriptionTD.innerHTML = '<select class="payment_description" name="payment_description">'+option_render+'</select>';
        //descriptionTD.innerHTML = '<input placeholder="Payment Description" type="text" name="payment_description" size="45" value="'+ payment_description +'">';
        newRow.appendChild (descriptionTD);

        var dateTD = document.createElement('td');
        dateTD.align='center';
        dateTD.innerHTML = '<span><input type="text" placeholder="Payment Date"  name="payment_date" id="payment_date_'+ (rowCount + 1 ) +'" size="30" value="'+ payment_date +'">' +
            '<img src="{sugar_getimagepath file="jscalendar.gif"}" alt="Enter Date" style="position:relative; top:6px" id="payment_date_'+ (rowCount + 1 ) + 'trigger" border="0"></span>';
        newRow.appendChild (dateTD);

        Calendar.setup (
            {ldelim}
                inputField : "payment_date_" + (rowCount + 1 ),
                form : "EditView",
                ifFormat : "%d/%m/%Y %H:%M",
                daFormat : "%d/%m/%Y %H:%M",
                button : "payment_date_"+ (rowCount + 1 ) + "trigger",
                singleClick : true,
                dateStr : "",
                startWeekday: 0,
                step : 1,
                weekNumbers:false
            {rdelim}
        );

        var bankRefTD = document.createElement('td');
        bankRefTD.align='center';
        bankRefTD.innerHTML = '<input type="text" placeholder="Bank Reference" name="payment_brankref" size="30" value="'+ payment_brankref +'">' ;
        newRow.appendChild (bankRefTD);
  
        var removeTD = document.createElement('td');
        removeTD.innerHTML = '<button value="Add More" onclick="javascript:delTextRow(this)" type="button" class="button">' +
            '<i class="glyphicon glyphicon-minus"></i>' +
            '</button> ';
        newRow.appendChild(removeTD);
    {rdelim}

</script>

<input type="hidden" name='{{sugarvar key='name'}}' value='{$value}' />

<table class="multipayment" id="multipayment">
    <tr>
        <td nowrap="" scope="row">
                <span class="id-ff multiple ownline">
                <button value="Add More" onclick="javascript:addTextRow()" type="button" class="button">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
                Payment Amount
                </span>
        </td>

        <td nowrap="" scope="row">
            Payment Description
        </td>

        <td scope="row" NOWRAP>
            Payment Date
        </td>

        <td nowrap="" scope="row">
            Bank Reference 
        </td>

        <td nowrap="" scope="row">

        </td>

    </tr>
    <br/>
</table>

<script type="text/javascript" language="javascript">

    var payment = '{$value}';
    var paymentJSONObject =(payment!="") ? JSON.parse(decodeURIComponent(payment)) : (new Array());




    if(paymentJSONObject.length > 0)
    {ldelim}
        for(var i in paymentJSONObject)
            {ldelim}
                var e  = paymentJSONObject[i];
                addTextRow(e.payment_amount , e.payment_description , e.payment_date , e.payment_brankref);
            {rdelim}
    {rdelim}

    addTextRow();


</script>