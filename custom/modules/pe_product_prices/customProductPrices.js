$(document).ready(function() {
// S - set product relate
    sqs_objects["name"] = {
        "form": "EditView",
        "method": "query",
        "modules": ["AOS_Products"],
        "group": "or",
        "field_list": ["name", "part_number"],
        "populate_list": ["name", "part_number"],
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
    "field_list": ["part_number", "name"],
    "populate_list": ["part_number", "name"],
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
// S - Add sqlEnable
    $(document).find('#name').addClass('sqsEnabled').attr('autocomplete', 'off');
    $(document).find('#part_number').addClass('sqsEnabled').attr('autocomplete', 'off');
// E - Add sqlEnable
});




///////***** FUNCTION DECLARE *****//////////////////////
function openProductRelate(){
    var popupRequestData = {
      "call_back_function" : "setProductRelate",
      "form_name" : "EditView",
      "field_to_name_array" : {
        "name" : "name",
        "part_number" : "part_number",
      }
    };
  
    open_popup('AOS_Products', 800, 850, '', true, true, popupRequestData);
  
}
 
function setProductRelate(popupReplyData){
    set_return(popupReplyData);
}
 