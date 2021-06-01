$(document).ready(function() {
  'use strict';
/**S-For autocomplete product */
// S - set product relate
    sqs_objects["name"] = {
        "form": "EditView",
        "method": "query",
        "modules": ["AOS_Products"],
        "group": "or",
        "field_list": ["name", "part_number", "description", "id"],
        "populate_list": ["name", "part_number", "description", "product_id"],
        "required_list": "",
        "conditions": [{
          "name": "name",
          "op": "like_custom",
          "end": "%",
          "value": ""
        }],
        "order": "name",
        "limit": "30",
        "no_match_text": "No Match"
    };
    sqs_objects["part_number"] = {
    "form": "EditView",
    "method": "query",
    "modules": ["AOS_Products"],
    "group": "or",
    "field_list": ["part_number", "name", "description", "id"],
    "populate_list": ["part_number", "name", "description", "product_id"],
    "required_list": "",
    "conditions": [{
        "name": "part_number",
        "op": "like_custom",
        "end": "%",
        "value": ""
    }],
    "order": "name",
    "limit": "30",
    "no_match_text": "No Match"
    };
// E - set product relate
// // S - Add sqlEnable
//     $(document).find('#name').addClass('sqsEnabled').attr('autocomplete', 'off');
//     $(document).find('#part_number').addClass('sqsEnabled').attr('autocomplete', 'off');
// // E - Add sqlEnable
/**E-For autocomplete product */

  // S - Show link product
  showLinkProduct();
  YAHOO.util.Event.addListener(["product_id"], "change", showLinkProduct);
  // E - Show link product
  // S - Show link Supplier
  showLinkSupplier();
  YAHOO.util.Event.addListener(["account_id"], "change", showLinkSupplier);
  // E - Show link Supplier

  // S - HIDE FIELDS/PANEL */
  $('body').find('div[class="panel-content"] div[class="panel panel-default"]').each(function(){
    var name_panel = $(this).find('.panel-heading').text().trim();
    if (name_panel.toLowerCase() == 'data hidden') {
        $(this).hide();
        return;
    }
  });
  // E - HIDE FIELDS/PANEL */

  // S - Show/Hide field Website
  showFieldWebsite();
  $(document).on('change', '#pricing_source', function() {
    showFieldWebsite();
  });
  // E - Show/Hide field Website

  // S - GET Price from website
  $(document).on('click', '#get_price_from_web', function() {
    let supplier_id = $('#account_id').val();
    let web = $('#website').val();
    let price_source = $('#pricing_source').val();
    if (supplier_id == '' || web == '') {
      alert('Please select Supplier/ enter website !');
      return;
    } else {
      $.ajax({
        url: "index.php?entryPoint=getPriceFromWeb",
        type: 'POST',
        data: {
          supplier_id : encodeURIComponent(supplier_id),
          web: encodeURIComponent(web),
          record_id : encodeURIComponent($("input[name='record']").val()),
        },
        async: false,
        success:function (price) {
            console.log(price);
            alert(price);
        }
      });
    }


  });
  // E - GET Price from website

  // S - Add button Today
  var bt_today  ='<button style="padding: 0px 10px;margin: 0px 1px;" type="button" class="button get_date" title="Get Today" data-type="today" >T</button>';
  $('div[field="date_release"]').find('tr[valign="middle"]').append('<td>'+bt_today+'</td>');
  $('.get_date').click(function(){
    var field_date = $(this).closest('.edit-view-field').attr('field');
    var type_field = $(this).closest('.edit-view-field').attr('type');
    var type_button = $(this).attr('data-type');
    var date_click = getDate_Inv(type_button);
    if (type_field == 'date') {
        $('#'+field_date).val(date_click);
    } else {
        var today_date = new Date();
        var hour_date = ((today_date.getHours() < 10) ? '0' : '') + today_date.getHours();
        var minutes_date = today_date.getMinutes();
        if(minutes_date<15){
            minutes_date = $("#"+field_date+"_minutes option:eq(1)").val();
        }else if(minutes_date>=15 && minutes_date < 30){
            minutes_date = $("#"+field_date+"_minutes option:eq(2)").val();
        }else if(minutes_date>=30 && minutes_date < 45){
            minutes_date = $("#"+field_date+"_minutes option:eq(3)").val();
        }else{
            minutes_date = $("#"+field_date+"_minutes option:eq(4)").val();      
        }
        $('#'+field_date+'_date').val(date_click);
        $('#'+field_date+'_hours').val(hour_date);
        $('#'+field_date+'_minutes').val(minutes_date);
        $('#'+field_date).val(date_click+' '+hour_date+':'+minutes_date);
    }
  });     
  // E - Add button Today
});




///////***** FUNCTION DECLARE *****//////////////////////

function showLinkProduct() {
  let name = $(document).find('#name');
  let partnumber = $(document).find('#part_number');
  let product_id = $(document).find('#product_id').val();
  $(document).find('.link_product').remove();
  if (name.val() != '' && product_id != '') {
    let link = "<p class='link_product'><a  href='/index.php?module=AOS_Products&action=EditView&record=" + product_id + "' target='_blank'>Open Product</a></p>";
    name.parent().append(link);
    partnumber.parent().append(link);
}
}

function showLinkSupplier() {
  let acc = $(document).find('#account_id');
  $(document).find('.link_supplier').remove();
  if (acc.val() != '') {
    let link = "<p class='link_supplier'><a  href='/index.php?module=Accounts&action=EditView&record=" + acc.val() + "' target='_blank'>Open Supplier</a></p>";
    acc.parent().append(link);
  }
}

function showFieldWebsite() {
  let price_src = $(document).find('#pricing_source').val();
  if (price_src == 'website') {
    $(document).find('#website').closest('.edit-view-row-item').show();
    $(document).find('#get_price_from_web').remove();
    $(document).find('#website').parent().after('<button type="button" id="get_price_from_web" class="button primary">Get Price</button>'); 
  } else {
    $(document).find('#website').closest('.edit-view-row-item').hide();
  }
}

function openProductRelate(){
    var popupRequestData = {
      "call_back_function" : "setProductRelate",
      "form_name" : "EditView",
      "field_to_name_array" : {
        "name" : "name",
        "part_number" : "part_number",
        "description" : "description",
        "product_id" : "id",
      }
    };
  
    open_popup('AOS_Products', 800, 850, '', true, true, popupRequestData);
  
}
 
function setProductRelate(popupReplyData){
    set_return(popupReplyData);
}
 
/**
 * 3 button TODAY +7 , TODAY +1, TODAY
 * @param {STRING} type  'data-type' of element
 */
 function getDate_Inv(type) {
  var date_return = '';
  var date = new Date();
  let day = date.getDay();
  switch(type){
      case 'today':
          var data = defaultDateTime_Inv(new Date());
          if(data['day'] < 10) {
              data['day'] = '0'+data['day'];
          }
          if(data['month'] < 10) {
              data['month'] = '0' + data['month'];
          }
          date_return = data['day']+'/'+data['month']+'/'+data['year']; 
          break;
      case '1':
          if (day === 5) {
              var data = defaultDateTime_Inv(new Date(date.getTime() + 3*(24*60*60*1000)));
          } else {
              var data = defaultDateTime_Inv(new Date(date.getTime() + (24*60*60*1000)));
          }
          if(data['day'] < 10) {
              data['day'] = '0'+data['day'];
          }
          if(data['month'] < 10) {
              data['month'] = '0' + data['month'];
          }
          date_return = data['day']+'/'+data['month']+'/'+data['year']; 
          break;
      case '7':
          var data = defaultDateTime_Inv(new Date(date.getTime() + 7*(24*60*60*1000)));
          if(data['day'] < 10) {
              data['day'] = '0'+data['day'];
          }
          if(data['month'] < 10) {
              data['month'] = '0' + data['month'];
          }
          date_return = data['day']+'/'+data['month']+'/'+data['year']; 
          break;
  }
  return date_return;
}

function defaultDateTime_Inv(date){
  var now     = date;
  var year    = now.getFullYear();
  var month   = now.getMonth()+1; 
  var day     = now.getDate();
  return {'day':day,'month':month,'year':year,}
}
