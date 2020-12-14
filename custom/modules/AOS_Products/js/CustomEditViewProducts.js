$(function () {
    'use strict';
    $( document ).ready(function() {


        //button CRUD Product SuiteCRM with PE Site
        var html_CRUD_Product_SuiteCRM_PESite = '<div class="clear"></div><br>\
        <button type="button" class="button primary" id="button_CRUD_Product_SuiteCRM_PESite"> \
        <span class="glyphicon glyphicon-modal-window"></span> Async Product PE Site</button>';

        $("#id_product_drupal").parent().parent().append(html_CRUD_Product_SuiteCRM_PESite);
        

        SUGAR.CRUDProductPESite = function(source){
            
            var type_method = $(source).attr('type');

            var datapost = {
                method : type_method,
                productID : $("#id_product_drupal").val(),
                name : $("#name").val(),
                price : $("#price").val(),
                description : parse_textarea_to_fill_html($("#description").val()),
            };
            
            CRUD_Product_PESite(datapost);
        };
    

        function display_link_product_pesite(){
            var id_product_drupal = $('#id_product_drupal').val().trim();
            $("#link_edit_product_pesite").remove();
            if(id_product_drupal != ''){
                let url_link_edit = "https://pure-electric.com.au/product/"+id_product_drupal+"/edit?destination=/admin/commerce/products";
                $("#id_product_drupal").parent().append("<p id='link_edit_product_pesite'><a  href='" +url_link_edit+ "' target='_blank'>Edit Product PE Site</a></p>");
            }
        }

        display_link_product_pesite();
        YAHOO.util.Event.addListener(["id_product_drupal"], "change", display_link_product_pesite);
        
        
        $("#button_CRUD_Product_SuiteCRM_PESite").on('click',function(){
            var body_modal_html =    
                    '<div id="popup_CRUD_Product_SuiteCRM_PESite">\
                        <div class="form-group text-center"> \
                            <button style="background-color: #1E96FD;" id="get_info_product" disable type="GET" onClick="SUGAR.CRUDProductPESite(this);" class="btn btn-default"><span class="glyphicon glyphicon-collapse-down"></span> Get Product</button> \
                            <button style="background-color: #1E96FD;" id="update_info_product" disable type="UPDATE" onClick="SUGAR.CRUDProductPESite(this);" class="btn btn-default"><span class="glyphicon glyphicon-collapse-up"></span> Update Product </button> \
                            <button  style="background-color: #1E96FD; display: none;" id="create_info_product" disable type="CREATE" onClick="SUGAR.CRUDProductPESite(this);" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Create Product </button> \
                        </div>\
                        <div class="form-group text-center"> \
                            <strong>Notes:</strong> <p>When we get product, it will use to "ID Product Drupal" that is default.</p>\
                                                    <p> If it\'s missing, we get by name product.</p>\
                        </div>\
                    </div>';
                    $('#alert_modal').find('.modal-body').empty();
                    $('#alert_modal').find('.modal-body').append(body_modal_html); 
                    $('#alert_modal').find('.modal-header').empty();
                    $('#alert_modal').find('.modal-header').append('<h3 style="text-align:center;">Select Option GET - UPDATE Products</h3>');
                    $('#alert_modal').modal('show'); 
        })


        function CRUD_Product_PESite(datapost){
            SUGAR.ajaxUI.showLoadingPanel();
            $.ajax({
                url: "?entryPoint=CRUD_Product_PESite",
                type: 'POST',
                data: datapost,
                success: function(data){
                   
                    if(data == '' && typeof data == undefined) return;
                    var jsonObject = $.parseJSON(data);
                    parse_data_product_PESite(jsonObject)
                    SUGAR.ajaxUI.hideLoadingPanel();
                }
            })
        }
        
        function parse_data_product_PESite(jsonObject){
            switch (jsonObject['method']) {
                case 'GET':
                    if(jsonObject['message'] == "not found"){
                        $('#alert_modal').find('.modal-body').empty();
                        $('#alert_modal').find('.modal-body').append('<h3  style="text-align:center;color:red;"> This product is not found in PE Site</h3>'); 
                        $('#alert_modal').find('.modal-header').empty();
                        $('#alert_modal').find('.modal-header').append('<h2 style="text-align:center;">Warning</h2>');
                        $('#alert_modal').modal('show'); 
                        return;
                    }
                    $("#description").val(jsonObject['description']);
                    $("#price").val(jsonObject['price']);
                    $("#name").val(jsonObject['name']);
                    if($("#id_product_drupal").val() == '') $("#id_product_drupal").val(jsonObject['productID']);
                    break;
                case 'UPDATE':
                case 'CREATE':
                    break;
                default:
                    break;
            }
            $('#alert_modal').modal('hide'); 
            display_link_product_pesite();
        }

        function parse_textarea_to_fill_html(value){
            var html_return = '';
            html_return += value.replaceAll("\n", "<br />");
            html_return += '';
            return html_return;
        }
    });

});
