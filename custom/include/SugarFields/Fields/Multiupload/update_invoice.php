<?php 
//GET ID_invoice
$user = "root";
$pass = "binhmatt2018";
$conn = mysqli_connect("localhost",$user,$pass,"suitecrm");
$query = "SELECT id FROM aos_invoices";
$result= mysqli_query($conn,$query);
    while($rows = mysqli_fetch_assoc($result)){
            $id_c =  $rows['id'];
            //echo  "<br>".$id_c;
            $focusName = "AOS_Invoices";
            $focus = BeanFactory::getBean($focusName,$id_c);
            $number_plumber_po = $focus->plumber_po_c;
            $number_electrical_po = $focus->electrical_po_c;
            $number_daikin_po = $focus->daikin_po_c;
            $number_invoice = $focus->id;
            $subtotal_plumber_po=0;
            $subtotal_electrical_po=0;
            $subtotal_daikin_po=0;
            $subtotal_invoice =0;

            $invoice = new AOS_Invoices();
            $purchaseOrder = new PO_purchase_order();
            if ($number_invoice !== '') {
                $invoice->retrieve($number_invoice);
                $subtotal_invoice = $invoice->subtotal_amount;
                if($subtotal_invoice == '') {
                    $subtotal_invoice = 0;
                } else{
                    $subtotal_invoice = substr($subtotal_invoice,0,-4);
                }    
            }

            if ($number_plumber_po !== '') {
                $purchaseOrder->retrieve($number_plumber_po);
                $subtotal_plumber_po = $purchaseOrder->subtotal_amount;
                if($subtotal_plumber_po == '') {
                    $subtotal_plumber_po = 0;
                } else{
                    $subtotal_plumber_po = substr($subtotal_plumber_po,0,-4);
                }    
            }

            if($number_electrical_po !== ''){
                $purchaseOrder->retrieve($number_electrical_po);
                $subtotal_electrical_po = $purchaseOrder->subtotal_amount;
                if($subtotal_electrical_po == '') {
                    $subtotal_electrical_po = 0;
                } else{
                    $subtotal_electrical_po = substr($subtotal_electrical_po,0,-4);
                }
            }

            if($number_daikin_po !== ''){
                $purchaseOrder->retrieve($number_daikin_po);
                $subtotal_daikin_po = $purchaseOrder->subtotal_amount;
                if($subtotal_daikin_po == '') {
                    $subtotal_daikin_po = 0;
                } else{
                    $subtotal_daikin_po = substr($subtotal_daikin_po,0,-4);
                }    
            }

            $subtotal = $subtotal_plumber_po + $subtotal_daikin_po +$subtotal_electrical_po;
            $profit = $subtotal_invoice - $subtotal;
            //tuan code ----------
                if($subtotal != 0 ){
                    $gp = number_format($profit/$subtotal * 100, 2) ;
                }else {
                $gp= 0;
                }
                $data_out_put = 

                '<div class="col-xs-12 col-sm-6 edit-view-row-item"></div>'
                .'<div class="col-xs-12 col-sm-6 edit-view-row-item"></div>'
                .'<div class="col-xs-12 col-sm-6 edit-view-row-item">'
                . '<div class="col-xs-12 col-sm-8 label">'
                . 'Subtotal : </div>'
                . '<div class="col-xs-12 col-sm-4">'
                . $subtotal
                .'</div></div>'
                .'<div class="col-xs-12 col-sm-6 edit-view-row-item"></div>'
                .'<div class="col-xs-12 col-sm-6 edit-view-row-item">'
                . '<div class="col-xs-12 col-sm-8 label">'
                . 'Profit : </div>'
                . '<div class="col-xs-12 col-sm-4">'
                . $profit
                .'</div></div>'
                .'</div></div>'
                .'<div class="col-xs-12 col-sm-6 edit-view-row-item"></div>'
                .'<div class="col-xs-12 col-sm-6 edit-view-row-item">'
                . '<div class="col-xs-12 col-sm-8 label">'
                . 'GP % : </div>'
                . '<div class="col-xs-12 col-sm-4">'
                . (($subtotal != 0 )? number_format($profit/$subtotal * 100, 2) : 0 )."%" 
                .'</div></div>'
                .'<div class="clear"></div><div class="clear"></div>'
                ;
            //echo $data_out_put."<hr>";
            // die;

                $_user = "root";
                $_pass = "binhmatt2018";
                $_conn = mysqli_connect("localhost",$_user,$_pass,"suitecrm");
                $_query = "UPDATE aos_invoices_cstm SET detail_gp_c='$gp%',detail_subtotal_c='$subtotal',detail_profit_c='$profit'  WHERE id_c ='$id_c '";
                if($_conn->query($_query) === TRUE){
                echo "Update thanh cong";
                }
                mysqli_close($_conn);
     }
 mysqli_close($conn);
?>
<!-- // echo "<script>  $('#detail_subtotal_c').append($subtotal);"
//                 ."$('#detail_profit_c').append($profit);"
//                 ."$('#detail_gp_c').append($gp +'%');"
//     ."</script>"; -->