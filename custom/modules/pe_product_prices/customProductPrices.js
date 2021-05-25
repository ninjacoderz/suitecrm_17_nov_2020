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
$(document).on('change', '#name, #part_number, #product_id', function() {
  showLinkProduct();
});
// E - Show link product
// S - Show link Supplier
showLinkAccount();
$(document).on('change', '#account_id', function() {
  showLinkAccount();
});
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

function showLinkAccount() {
  let acc = $(document).find('#account_id');
  if (acc.val() != '') {
    let link = "<p class='link_supplier'><a  href='/index.php?module=Accounts&action=EditView&record=" + acc.val() + "' target='_blank'>Open Supplier</a></p>";
    acc.parent().append(link);
  }
}

function showFieldWebsite() {
  let price_src = $(document).find('#pricing_source').val();
  if (price_src == 'website') {
    $(document).find('#website').closest('.edit-view-row-item').show();
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
 