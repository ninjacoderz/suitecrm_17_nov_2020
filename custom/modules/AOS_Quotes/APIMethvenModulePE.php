<?php
require_once('include/SugarPHPMailer.php');
$path           = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';
$dirName        = $_POST['pre_install_photos_c'];
$folderName     = $path . $dirName . '/';
$thumbnail      = $path . $dirName . '/thumbnail' . '/';
$action         = $_POST['action'];
$quote_id       = $_POST['quote_id'];
$quote = new AOS_Quotes();
$quote->retrieve($quote_id);

if($quote->id == ""){
    echo json_encode(array('msg'=>'error'));die();
}

if($quote->pre_install_photos_c == '' && $dirName != ''){
    $quote->pre_install_photos_c = $dirName;
    $quote->save();
}


switch ($action) {
    case 'sendFileToQuoteByForm':
        $shortcuts ="";      
        $list_photos = "<br><h4>List Photos:</h4>";
        $results = render_json_quote($quote_id);
        foreach($results as $key => $value){
            if( !empty($value) ){
                $shortcuts .= "<a href='https://suitecrm.pure-electric.com.au/index.php?module=".$key."&action=EditView&record=".$value."'>".$key."</a> | ";
            }
        }
        if(!file_exists($folderName)){
            mkdir($path . $dirName, 0777, true);
        }
        for ($k=1; $k < 4; $k++) { 
            if(count($_POST['files']['data-pe-files-exiting-shower-'.$k]['tmp_name']) > 0) {
                for($i = 0; $i < count($_POST['files']['data-pe-files-exiting-shower-'.$k]['tmp_name']); $i++) {
                    if($_POST['files']['data-pe-files-exiting-shower-'.$k]['name'][$i] != ""){
                        $file_type = $quote->number.'_Old_Shower_'.$k.'_'.$i.'.'.pathinfo( basename($_POST['files']['data-pe-files-exiting-shower-'.$k]['name'][$i]), PATHINFO_EXTENSION);
                        $count = checkCountExistPhoto($file_type,$folderName,'_Old_Shower_'.$k);
                        $file_type = $quote->number.'_Old_Shower_'.$k.'_'.$count.'.'.pathinfo( basename($_POST['files']['data-pe-files-exiting-shower-'.$k]['name'][$i]), PATHINFO_EXTENSION );
                        copy($_POST['files']['data-pe-files-exiting-shower-'.$k]['tmp_name'][$i], $folderName.$file_type);
                        $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Existing Shower '.$k.'_'.$i.'</a>';
                        addToNotes($file_type,$folderName,$parent_id,$parent_type);
                    };
                }
            }; 
            
        }

        $account = new Contact();
        $account->retrieve($quote->billing_contact_id);
        $subject = $quote->account_firstname_c." ".$quote->account_lastname_c." uploaded file Existing Shower to Quote#".$quote->number." ".$quote->name;
        $body = $shortcuts;
        $body .= "<p>Link Quote: <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&offset=14&stamp=1587091474041920500&return_module=AOS_Quotes&action=EditView&record=".$quote->id."' target='_blank'>".$quote->name."</a></p>";
        $body .= "<p>Email: <a href='https://mail.google.com/#search/".$account->email1."'>".$account->email1." GSearch</a></p>";
        $body .= "<p>Phone number: <a href='#'>".$account->phone_mobile."</a></p></p>";
        $body .= "<p><a href='https://suitecrm.pure-electric.com.au/index.php?entryPoint=converToInvoice&record=".$quote->id."' target='_blank'>Convert Invoice</a></p>";
        $body .= $list_photos;
        CallAPICloneFile($quote);
        $data_return = send_email_APIMethven($body, $subject);
        create_internal_notes($quote,$subject);
        echo json_encode($data_return);die();
        break;
    case 'getGroupProduct':
        $products_return = get_product_by_quote($quote);
        $data_return = get_data_quote_product($quote,$products_return);
        echo json_encode($data_return);die();
        break;
    case 'sendAcceptanceNotification':
        $products_return = get_product_by_quote($quote);
        $quote_return = get_data_quote_product($quote,$products_return);
        $body = generate_email_acceptance_notification($quote_return);
        $subject = 'Quote option approved notification '.preg_replace('/\(|\)/', '', $quote->name);
        $data_return = send_email_APIMethven($body, $subject);
        create_internal_notes($quote,$subject);
        echo json_encode($data_return);die();
        break;
    case 'uploadRemittanceAdvice':
        $shortcuts ="";      
        $list_photos = "<br><h4>List Photos:</h4>";
        $results = render_json_quote($quote_id);
        foreach($results as $key => $value){
            if( !empty($value) ){
                $shortcuts .= "<a href='https://suitecrm.pure-electric.com.au/index.php?module=".$key."&action=EditView&record=".$value."'>".$key."</a> | ";
            }
        }
        if(!file_exists($folderName)){
            mkdir($path . $dirName, 0777, true);
        }

        // REMITTANCE ADVICE
        if(count($_POST['files']['data-client-files-remittance-advice']['tmp_name']) > 0) {
            for($i = 0; $i < count($_POST['files']['data-client-files-remittance-advice']['tmp_name']); $i++) {
                if($_POST['files']['data-client-files-remittance-advice']['name'][$i] != ""){
                    $file_type = $quote->number.'_Remittance_Advice_'.$i.'.'.pathinfo( basename($_POST['files']['data-client-files-remittance-advice']['name'][$i]), PATHINFO_EXTENSION);
                    $count = checkCountExistPhoto($file_type,$folderName,'_Remittance_Advice_');
                    $file_type = $quote->number.'_Remittance_Advice_'.$count.'.'.pathinfo( basename($_POST['files']['data-client-files-remittance-advice']['name'][$i]), PATHINFO_EXTENSION );
                    copy($_POST['files']['data-client-files-remittance-advice']['tmp_name'][$i], $folderName.$file_type);
                    $list_photos .= '<br><a data-gallery="image" href="https://suitecrm.pure-electric.com.au/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$dirName.'/'.$file_type.'">Remittance Advice '.$i.'</a>';
                    addToNotes($file_type,$folderName,$parent_id,$parent_type);
                };
            }
        }; 

        $account = new Contact();
        $account->retrieve($quote->billing_contact_id);
        $subject = $quote->account_firstname_c." ".$quote->account_lastname_c." uploaded file Remittance Advice to Quote#".$quote->number." ".$quote->name;
        $body = $shortcuts;
        $body .= "<p>Link Quote: <a href='https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&offset=14&stamp=1587091474041920500&return_module=AOS_Quotes&action=EditView&record=".$quote->id."' target='_blank'>".$quote->name."</a></p>";
        $body .= "<p>Email: <a href='https://mail.google.com/#search/".$account->email1."'>".$account->email1." GSearch</a></p>";
        $body .= "<p>Phone number: <a href='#'>".$account->phone_mobile."</a></p></p>";
        $body .= "<p><a href='https://suitecrm.pure-electric.com.au/index.php?entryPoint=converToInvoice&record=".$quote->id."' target='_blank'>Convert Invoice</a></p>";
        $body .= $list_photos;
        
        $data_return = send_email_APIMethven($body, $subject);
        create_internal_notes($quote,$subject);
        echo json_encode($data_return);die();
        
        break;
    default:
        echo json_encode(array('msg'=>'error'));die();
        break;
}

function generate_email_acceptance_notification($quote_return){
    $url = 'https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record='.$quote_return['quote_id'];
    $color = ['#627afe','#f8f7fc'];

    $product_text = '';
    if(count($quote_return['products']) > 0){
        foreach($quote_return['products'] as $key => $val){
            $product_text .= '<div style="margin: 5px 0;">'.$val['Quantity'].' x '.$val['Product'].'</div>';
        }               
    }

    $content_email ='<div style="display: block;align-items: stretch;justify-content: left;">
                        <div style="width: 450px;float:left;min-height: 100%;background: '.$color[1].';">
                            <div style="padding: 10px;height: 100%;">
                                <div style="background:#cad2ff;color:white;background-image: linear-gradient(90deg,'.$color[0].','.$color[1].');;padding: 20px 20px 20px 20px;font-size:18px;font-weight:700;">System Price</div>
                                <div style="font-size:15px;padding: 20px;color: #333;">
                                '.$product_text.'                                                
                                </div>
                                <div style="font-weight:700;padding: 15px 20px 15px 20px;font-size:16px;border-top: 1px solid #e4dfdf;">
                                    <div><span>Subtotal</span><span style="float:right">'.$quote_return['groupProducts']['Subtotal'].'</span></div>
                                    <div><span>GST <i title="In accordance with the Australian Tax Office (ATO), the 10% GST must be added to the total price of the system, before any allowance is made for the environmental certificates."></i></span><span style="float:right">'.$quote_return['groupProducts']['GST'].'</span></div>
                                </div>
                                <div style="text-align:center;border: 1px solid '.$color[0].';">
                                    <span style="font-size:24px">$</span>
                                    <span style="font-size: 40px;color:'.$color[0].'">'.$quote_return['groupProducts']['Group_Total'].'</span>
                                    <span style="color:#3b3b3b;font-weight:600"></span>
                                </div>
                            </div>
                        </div>
                        <div class="customer-information" style="float:left;width:450px;min-height: 100%;background: '.$color[1].';">
                            <div style="padding: 10px;height: 100%;">
                                <div style="background:#cad2ff;color:white;background-image: linear-gradient(90deg,'.$color[0].','.$color[1].');;padding: 20px 20px 20px 20px;font-size:18px;font-weight:700;">Information</div>
                                <div style="font-size:15px;padding: 20px;color: #333;">
                                    <div style="margin: 5px 0;display: flex;">
                                        <span style="width:30%;">Your Install ID#</span>
                                        <span style="width:70%;"><a href="'.$url.'">Quote #'.$quote_return['quote_number'].'</a></span>
                                    </div>
                                    <div style="margin: 15px 0;display: flex;">
                                        <span style="width:30%;">Name</span>
                                        <span style="width:70%;">'.$quote_return['first_name'].' '.$quote_return['last_name'].'</span>
                                    </div>
                                    <div style="margin: 15px 0;display: flex;">
                                        <span style="width:30%;">Address</span>
                                        <span style="width:70%;">'.$quote_return['street_address'].', '.$quote_return['postcode_address'].' '.$quote_return['suburb_address'].'</span>
                                    </div>
                                    <div style="margin: 15px 0;display: flex;">
                                        <span style="width:30%;">Phone Number</span>
                                        <span style="width:70%;">'.$quote_return['phone_number'].'</span>
                                    </div>
                                    <div style="margin: 15px 0;display: flex;">
                                        <span style="width:30%;">Email</span>
                                        <span style="width:70%;">'.$quote_return['email'].'</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both"></div>
                    </div>';
        
    $content_email .= '<div style="clear:left"></div>';

    $bodytext = '<div><p>Hi team, The customer has just accepted the Quote option.Cheers!</p></div>'.$content_email.'<div><p>Please check the  quote: <a href="'.$url.'">Quote #'.$quote_return['quote_number'].'</a></p></div>';
    return $bodytext;
}
function get_product_by_quote($quote){
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT * FROM aos_products_quotes pg
            WHERE pg.parent_type = 'AOS_Quotes' AND pg.parent_id = '" . $quote->id . "' AND pg.deleted = 0 GROUP BY pg.part_number";
    $res = $db->query($sql);
    $products_return = array();
    while ($row = $db->fetchByAssoc($res)) {
        $products_return[$row['part_number']] = array (
            'Quantity' => number_format($row['product_qty'], 0),
            'Product' =>  $row['name'],
            'Description' =>  str_replace(["\r\n","\n\r","\n","\r"],"<br>",$row['item_description']),
            'List' =>  number_format($row['product_cost_price'], 2),
            'Sale_Price' => number_format($row['product_list_price'], 2),
            'Tax_Amount' => $row['vat_amt'],
            'Discount' => 0,
            'Total' => number_format($row['product_total_price'], 2)
        );
    }
    return $products_return;
}

function get_data_quote_product($quote,$products_return){
    $Contact = new Contact();
    $Contact->retrieve($quote->billing_contact_id);

    $db = DBManagerFactory::getInstance();
    $sql_group = "SELECT * FROM  aos_line_item_groups lig
    WHERE lig.parent_type = 'AOS_Quotes' AND lig.parent_id = '" . $quote->id . "' AND lig.deleted = 0";
    $ret = $db->query($sql_group);
    $data_return = array();
    while ($row = $db->fetchByAssoc($ret)) {
        $data_return = array (
            'quote_id' => $quote->id,
            'quote_number' => $quote->number,
            'node_id' => $quote->drupal_node_c,
            'lead_id' => $quote->leads_aos_quotes_1leads_ida,
            'pre_install_photos_c' => $quote->pre_install_photos_c,
            'products' => $products_return,
            'first_name' => $quote->account_firstname_c,
            'last_name' => $quote->account_lastname_c,
            'street_address' => $quote->billing_address_street,
            'postcode_address' => $quote->billing_address_postalcode,
            'phone_number' => $Contact->phone_mobile,
            'email' => $Contact->email1,
            'suburb_address' => $quote->billing_address_city,
            'quote_number' => $quote->number,
            'groupProducts' => array(
                'Group_Name' => $row['name'],
                'Total' => number_format($row['total_amount'],2),
                'Discount' => 0,
                'Subtotal' => number_format($row['subtotal_amount'],2),
                'GST' => number_format($row['tax_amount'],2),
                'Tax' =>  number_format($row['tax_amount'],2),
                'Group_Total' => number_format($row['total_amount'],2)
            )
        );
    }
    return $data_return;
}

function send_email_APIMethven($body, $subject){
    $mail = new SugarPHPMailer();  
    $mail->setMailerForSystem();  
    $mail->From = 'info@pure-electric.com.au';  
    $mail->FromName = 'Pure Electric';  
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->IsHTML(true);
    $mail->IsHTML(true);
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    //$mail->AddAddress('nguyenphudung93.dn@gmail.com');
    $mail->AddAddress('info@pure-electric.com.au');
    $mail->AddCC('paul.szuster@pure-electric.com.au');
    $mail->AddCC('matthew.wright@pure-electric.com.au');
    $mail->prepForOutbound();
    $mail->setMailerForSystem();  
    $sent = $mail->Send();
    if($sent){
        return array('msg'=>'sent');
    }else{
        return array('msg'=>'fail');        
    }
}

function addToNotes($file,$folderName,$parent_id,$parent_type){
    resize_image($file, $folderName);
    $noteTemplate = new Note();
    $noteTemplate->id = create_guid();
    $noteTemplate->new_with_id = true; // duplicating the note with files
    $noteTemplate->parent_id = $parent_id;
    $noteTemplate->parent_type = $parent_type;
    $noteTemplate->date_entered = '';
    $noteTemplate->file_mime_type = mime_content_type($folderName.$file);
    $noteTemplate->filename = $file;
    $noteTemplate->name = $file;
    $noteTemplate->save();
}

function checkCountExistPhoto($file_type,$folderName,$new_name){
    $data_exist= [];
    $get_all_photo = dirToArray($folderName);
    foreach ($get_all_photo as $photo_exist) {
        if(  strpos($photo_exist, $new_name)){
            $data_exist[] = $photo_exist;
        }
    }
    $count =  count($data_exist);
    return $count;   
}
function dirToArray($dir) { 
   
    $result = array();
    $cdir = scandir($dir); 
    foreach ($cdir as $key => $value) 
    { 
       if (!in_array($value,array(".",".."))) 
       { 
          if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
          { 
             $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
          } 
          else 
          { 
             $result[] = $value; 
          } 
       } 
    }
    return $result; 
}

function resize_image($file, $current_file_path) {
    $type = strtolower(substr(strrchr($file, '.'), 1));
    $typeok = TRUE;
    if($type == 'gif' || $type == 'jpg' || $type == 'jpeg' || $type == 'png') {
        if(!file_exists ($current_file_path."/thumbnail/")) {
            mkdir($current_file_path."/thumbnail/");
        }
        $thumb =  $current_file_path."/thumbnail/".$file;
        switch ($type) {
            case 'jpg': // Both regular and progressive jpegs
            case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
                break;
            case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
                break;
            default: $typeok = FALSE; break;
        }
        if ($typeok){
            list($w, $h) = getimagesize($current_file_path.'/'. $file);

            $src = $src_func($current_file_path.'/'. $file);
            $new_img = imagecreatetruecolor(80,80);
            imagecopyresampled($new_img,$src,0,0,0,0,80,80,$w,$h);
            $write_func($new_img,$thumb, $image_quality);
            
            imagedestroy($new_img);
            imagedestroy($src);
        }
    } 
}

function render_json_quote($quote_id){
        $result = array(
            'AOS_Quotes' => [],
            'Leads' => "",
            'Accounts' => "",
            'Contacts' => "",
            'PO_purchase_order' => [],
        );   
        $bean_quotes =  new AOS_Quotes();
        $bean_quotes->retrieve($quote_id);
        //account + contact +oppurtunity
        if(empty($result['Leads'])) {
            $result['Leads']= $bean_quotes->leads_aos_quotes_1leads_ida;
        }
        if(empty($result['Accounts'])) {
            $result['Accounts'] = $bean_quotes->billing_account_id;
        }
        if(empty($result['Contacts'])) {
            $result['Contacts'] = $bean_quotes->billing_contact_id;
        }
    return $result;
}

function create_internal_notes($quote,$content){
    $internal_notes = new pe_internal_note();
    $internal_notes->type_inter_note_c = 'general';
    $internal_notes->description = $content;
    $internal_notes->save();
    $internal_notes->load_relationship('aos_quotes_pe_internal_note_1');
    $internal_notes->aos_quotes_pe_internal_note_1->add($quote->id);
}

function CallAPICloneFile($quote){
    // clone file from Quote To Leads
    $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
    $fields = array();
    $url = 'https://suitecrm.pure-electric.com.au/index.php?entryPoint=APICloneFile&method=clone_file_Quote_to_Lead&leadID='
    .$quote->leads_aos_quotes_1leads_ida .'&quoteID='.$quote->id ;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($curl, CURLOPT_COOKIEFILE, $tmpfsuitename);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $resultCURL = curl_exec($curl); 
    curl_close($curl);
}


