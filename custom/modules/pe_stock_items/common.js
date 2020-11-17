$(document).ready(function(){
    $('#parent_type').hide();
    // $('#warehouse_c').val($('#parent_name').val());
    YAHOO.util.Event.addListener("parent_name", "change", function(){
        var id = $("#parent_id").val();
        if( $("#parent_name").val() != ''){
            $.ajax({
                url: 'index.php?entryPoint=customPe&parent_id='+id,
                success: function(data){
                    if(data == ''){
                        $('#warehouse_c').val('');
                        $('#pe_warehouse_id_c').val('');
                        alert('Warehouse Log have not Warehouse!');
                        return;       
                    }
                    try {
                        var result = JSON.parse(data);
                        $('#warehouse_c').val(result['name']);
                        $('#pe_warehouse_id_c').val(result['id']);
                    }catch(err) {
                        console.log(err);
                    }
                }
            });
        }
    });

});  