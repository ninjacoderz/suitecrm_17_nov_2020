<?php
/**
 * Advanced OpenSales, Advanced, robust set of sales modules.
 * @package Advanced OpenSales for SugarCRM
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility <info@salesagility.com>
 */

    if (!(ACLController::checkAccess('AOS_Invoices', 'edit', true))) {
        ACLController::displayNoAccess();
        die;
    }

    require_once('modules/AOS_Quotes/AOS_Quotes.php');
    require_once('modules/AOS_Invoices/AOS_Invoices.php');
    require_once('modules/AOS_Products_Quotes/AOS_Products_Quotes.php');

    global $timedate;
    //Setting values in Quotes
    $quote = BeanFactory::newBean('AOS_Quotes');
    $quote->retrieve($_REQUEST['record']);
    $quote->invoice_status = 'Invoiced';
    $quote->total_amt = format_number($quote->total_amt);
    $quote->discount_amount = format_number($quote->discount_amount);
    $quote->subtotal_amount = format_number($quote->subtotal_amount);
    $quote->tax_amount = format_number($quote->tax_amount);
    if ($quote->shipping_amount != null) {
        $quote->shipping_amount = format_number($quote->shipping_amount);
    }
    $quote->total_amount = format_number($quote->total_amount);
    $quote->stage = "converted_to_invoice";
    $quote->save();
    $get_all_photo = dirToArray($_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$quote->pre_install_photos_c.'/') ;
    
    //Setting Invoice Values
    date_default_timezone_set('Australia/Melbourne');
    $invoice = BeanFactory::newBean('AOS_Invoices');
    $rawRow = $quote->fetched_row;
    $rawRow['id'] = '';
    $rawRow['template_ddown_c'] = ' ';
    $rawRow['quote_number'] = $rawRow['number'];
    $rawRow['number'] = '';
    $dt = explode(' ', $rawRow['date_entered']);
    $rawRow['quote_date'] = $dt[0];
    $rawRow['invoice_date'] = date('Y-m-d');
    $rawRow['total_amt'] = format_number($rawRow['total_amt']);
    $rawRow['discount_amount'] = format_number($rawRow['discount_amount']);
    $rawRow['subtotal_amount'] = format_number($rawRow['subtotal_amount']);
    $rawRow['tax_amount'] = format_number($rawRow['tax_amount']);
    $rawRow['date_entered'] = '';
    $rawRow['date_modified'] = '';
    if ($rawRow['shipping_amount'] != null) {
        $rawRow['shipping_amount'] = format_number($rawRow['shipping_amount']);
    }
    $rawRow['total_amount'] = format_number($rawRow['total_amount']);
    $invoice->populateFromRow($rawRow);
    $invoice->process_save_dates =false;
    $invoice->save();
    // BinhNT
    // Do special things to make it convert exactly
    // // VUT - comment because change logic
    // $invoice->account_id1_c = $quote->account_id_c;
    // $invoice->account_id_c = $quote->account_id1_c;
    $array_product_type_daikin = ['quote_type_daikin','quote_type_nexura'];
    $plumber_account =  new Account();
    if (in_array($quote->quote_type_c,$array_product_type_daikin) || strpos(strtolower($quote->name),'daikin') !== false ) {
            $invoice->account_id2_c = 'def803db-f1ea-5f11-305e-5db106d4cf1e'; //old logic (daikin supplier)
            $invoice->account_id1_c = $quote->account_id4_c; //Plumber
            $invoice->plumber_install_date_c = explode(" ",$quote->proposed_install_date_c)[0];
            $invoice->distance_to_suite_c = $quote->distance_to_daikin_installer_c;
            $invoice->delivery_date_time_c = $quote->proposed_delivery_date_c;
        $plumber_account->retrieve($quote->account_id4_c);
    } else if ($quote->quote_type_c == 'quote_type_sanden' || strpos(strtolower($quote->name),'sanden') !== false) {
        $invoice->account_id1_c = $quote->account_id3_c; //Plumber
        $invoice->distance_to_suite_c = $quote->distance_to_travel_c;
        $invoice->plumber_install_date_c = explode(" ",$quote->proposed_install_date_c)[0];
        $invoice->account_id_c = $quote->account_id2_c; //Electrician
        $invoice->distance_to_suitecrm_c = $quote->distance_to_electrician_c;
        $invoice->electrician_install_date_c = explode(" ",$quote->proposed_install_date_c)[0];
        $plumber_account->retrieve($invoice->account_id1_c);
        $invoice->dispatch_date_c = $quote->proposed_dispatch_date_c;
        //tuan code plumping template default
        $template = file_get_contents('custom/modules/AOS_Invoices/json_plumbing_template.json');
        $template = json_decode($template);
        foreach( $template as $key => $val){
            if($key == "1585189464"){
                $content = $val->content;
                $invoice->plumbing_notes_c = $content;
            }
        }    
    }
    $invoice->invoice_note_c = $quote->quote_note_c;
	//VUT - convert Proposed install Date > Installation Date
    $invoice->installation_date_c = $quote->proposed_install_date_c;
    if ($invoice->installation_date_c != '') {
        createMettingForInstaller($invoice);
    }

    if( isset($_REQUEST['orderID']) ){
        $invoice->order_number_c = $_REQUEST['orderID'];
    }
    // change status lead
    if( $quote->leads_aos_quotes_1leads_ida != ""){
        $lead =  new Lead();
        $lead->retrieve($quote->leads_aos_quotes_1leads_ida);
        $lead->status = "Converted";
        $lead->save();
    }
    // //VUT - comment because change logic
    // $plumber_account =  new Account();
    // $plumber_account->retrieve($quote->account_id_c);
    if ($plumber_account->load_relationship('contacts')) {  
    	$relatedContacts = $plumber_account->contacts->getBeans();  
    	if (!empty($relatedContacts)) {  
            // reset($relatedContacts);  
            // $contact = current($relatedContacts);  
            $contact = $relatedContacts[$plumber_account->primary_contact_c];
            $invoice->contact_id4_c = $contact->id;
	    }  
    }

	$electric_account =  new Account();
    // $electric_account->retrieve($quote->account_id1_c);
    $electric_account->retrieve($invoice->account_id_c);
	if ($electric_account->load_relationship('contacts')) {  
        $relatedContacts = $electric_account->contacts->getBeans();  
        if (!empty($relatedContacts)) {  
            // reset($relatedContacts);  
            // $contact = current($relatedContacts);
            $contact = $relatedContacts[$electric_account->primary_contact_c];
            $invoice->contact_id_c = $contact->id;
        }  
	}

    $invoice->installation_pictures_c = gererate_UUID_for_invoice();
	// $invoice->installation_notes_c = $quote->pre_install_notes_c;
	if($quote->installation_date_c){
		$date_explode = explode(" ", $quote->installation_date_c);
		if(count($date_explode) >= 2){
			$inst_date = $date_explode[0];
			$invoice->due_date = $inst_date;
		}
    }
    $invoice->quote_type_c = $quote->quote_type_c;
    //VUT - Add next action date when creating Methven's Invoice
    if ($invoice->quote_type_c == 'quote_type_methven' ) {
        date_default_timezone_set('UTC');
        $dateAUS = date('Y-m-d H:i:s', time());
        $invoice->next_action_date_c = $dateAUS;
    }
    $invoice->description = $quote->description;
    $invoice->invoice_note_c = $quote->quote_note_c;

    //$invoice->save();

    //dung code -- Convert site details quote to invoices
        $array_potential_issues_c = explode(',',str_replace('^','',$quote->potential_issues_c));
        $data_site_details =  array (
            'pe_site_details_no_c' => $quote->pe_site_details_no_c ,
            'sg_site_details_no_c' => $quote->sg_site_details_no_c,
            'solargain_quote_number_c' => $quote->solargain_quote_number_c,
            'detail_site_install_address_c' => $quote->install_address_c,
            'detail_site_install_address_city_c' =>$quote->install_address_city_c,
            'detail_site_install_address_state_c' => $quote->install_address_state_c,
            'detail_site_install_address_postalcode_c' => $quote->install_address_postalcode_c,
            'detail_site_install_address_country_c' => $quote->install_address_country_c,
            'customer_type_c' =>  $quote->customer_type_c,
            'gutter_height_c' => $quote->gutter_height_c,
            'roof_type_c' =>  $quote->roof_type_c,
            'export_meter_c' =>  $quote->export_meter_c,
            'potential_issues_c' => $array_potential_issues_c,
            'cable_size_c' => $quote->cable_size_c,
            'connection_type_c' => $quote->connection_type_c,
            'main_type_c' =>  $quote->main_type_c,
            'meter_number_c' => $quote->meter_number_c,
            'meter_phase_c' => $quote->meter_phase_c,
            'nmi_c' => $quote->nmi_c,
            'account_number_c' => $quote->account_number_c,
            'address_nmi_c' =>$quote->address_nmi_c,
            'name_on_billing_account_c' => $quote->name_on_billing_account_c,
            'distributor_c' =>  $quote->distributor_c,
            'energy_retailer_c' =>  $quote->energy_retailer_c,
            'account_holder_dob_c' => $quote->account_holder_dob_c,
          );
        $invoice->data_json_site_details_c =  json_encode($data_site_details);
        //$invoice->save();
    //end dung code -- Convert site details quote to invoices
    //Setting invoice quote relationship
    require_once('modules/Relationships/Relationship.php');
    $key = Relationship::retrieve_by_modules('AOS_Quotes', 'AOS_Invoices', $GLOBALS['db']);
    if (!empty($key)) {
        $quote->load_relationship($key);
        $quote->$key->add($invoice->id);
    }
        //Setting Group Line Items
        $db = DBManagerFactory::getInstance();
        $sql = "SELECT * FROM aos_line_item_groups WHERE parent_type = 'AOS_Quotes' AND parent_id = '".$quote->id."' AND deleted = 0";
        $result = $db->query($sql); //$this->bean->db
        $quoteToInvoiceGroupIds = array();
        while ($row = $db->fetchByAssoc($result) ) {
            $quoteGroupId = $row['id'];
            $row['id'] = '';
            $row['parent_id'] = $invoice->id;
            $row['parent_type'] = 'AOS_Invoices';
            if ($row['total_amt'] != null) {
                $row['total_amt'] = format_number($row['total_amt']);
            }
            if ($row['discount_amount'] != null) {
                $row['discount_amount'] = format_number($row['discount_amount']);
            }
            if ($row['subtotal_amount'] != null) {
                $row['subtotal_amount'] = format_number($row['subtotal_amount']);
            }
            if ($row['tax_amount'] != null) {
                $row['tax_amount'] = format_number($row['tax_amount']);
            }
            if ($row['subtotal_tax_amount'] != null) {
                $row['subtotal_tax_amount'] = format_number($row['subtotal_tax_amount']);
            }
            if ($row['total_amount'] != null) {
                $row['total_amount'] = format_number($row['total_amount']);
            }
            $group_invoice = BeanFactory::newBean('AOS_Line_Item_Groups');
            $group_invoice->populateFromRow($row);
            $group_invoice->save();
            $quoteToInvoiceGroupIds[$quoteGroupId] = $group_invoice->id;
        }

        $sanden_model_c = '';
        //Setting Line Items
        // $isSandenSupply = false;
        $sql = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Quotes' AND parent_id = '".$quote->id."' AND deleted = 0";
        $result = $db->query($sql);
        while ($row = $db->fetchByAssoc($result)) {
            $row['id'] = '';
            $row['parent_id'] = $invoice->id;
            $row['parent_type'] = 'AOS_Invoices';
            $row['group_id'] = $quoteToInvoiceGroupIds[$row['group_id']];
            if ($row['product_cost_price'] != null) {
                $row['product_cost_price'] = format_number($row['product_cost_price']);
            }
            $row['product_list_price'] = format_number($row['product_list_price']);
            if ($row['product_discount'] != null) {
                $row['product_discount'] = format_number($row['product_discount']);
                $row['product_discount_amount'] = format_number($row['product_discount_amount']);
            }
            $row['product_unit_price'] = format_number($row['product_unit_price']);
            $row['vat_amt'] = format_number($row['vat_amt']);
            $row['product_total_price'] = format_number($row['product_total_price']);
            $row['product_qty'] = format_number($row['product_qty']);
            $prod_invoice = BeanFactory::newBean('AOS_Products_Quotes');
            $prod_invoice->populateFromRow($row);
            $prod_invoice->save();
            if (strpos($row['part_number'],'GAUS-') !== false && $sanden_model_c == '' ) { 
                $sanden_model_c  = $row['part_number'];
            }
    }
    //Dung code
	$invoice_title  = strtolower($rawRow['name']);
	if (strpos($invoice_title,'daikin') !== false) {
		$accout_daikin = new Account();
		$accout_daikin->retrieve($quote->billing_account_id);
		$invoice->delivery_contact_name_c = $accout_daikin->name;		
		if ($accout_daikin->shipping_address_street !== "") {
			$invoice->delivery_contact_address_c = $accout_daikin->shipping_address_street;
			$invoice->delivery_contact_suburb_c = $accout_daikin->shipping_address_city;
			$invoice->delivery_contact_postcode_c  = $accout_daikin->shipping_address_postalcode;
			$invoice->delivery_contact_phone_numbe_c =  $accout_daikin->phone_office ;
		} else {
			$invoice->delivery_contact_address_c = $quote->shipping_address_street;
			$invoice->delivery_contact_suburb_c = $quote->shipping_address_city;
			$invoice->delivery_contact_postcode_c  = $quote->shipping_address_postalcode;
			$invoice->delivery_contact_phone_numbe_c =  $accout_daikin->phone_office ;
		}
	}
    //save value default
    $invoice->existing_sh_flow_rate_c = '9';
    $invoice->replacement_showerhead_c = '5.0';
    $invoice->sanden_model_c = $sanden_model_c;
    //dung code- convert payments json string
    if(isset($_REQUEST['payment_amount'])) {
        $array_payments_link = array(
            '0' => array(
                'payment_amount' => $_REQUEST['payment_amount'],
                'payment_brankref' =>  $_REQUEST['payment_brankref'],
                'payment_date' =>  $_REQUEST['payment_date'],
                'payment_description' =>  $_REQUEST['payment_description']
            )
        );
        $array_payments_link = rawurlencode(json_encode($array_payments_link));
        $invoice->payments_c =$array_payments_link;
    }
    $invoice->save();

    // create WareHouse Log From Shop Online
    if( isset($_REQUEST['orderID']) ){
        $aupost_shipping_id = $_REQUEST['aupost_shipping_id'];
        $connote_id = $_REQUEST['connote_id'];
        create_warehouse_log($invoice->id,$quote,$aupost_shipping_id,$connote_id);
    }
    $array_convert_file_name = array(
        'proposed_install_location' => '_Proposed_Install_Location',
        'switchboard' => '_Switchboard',
        'shipping_confirmation' => '_ShippingConfirmation',
        'street_view' => '_Street_View',
        'remittance_advice' => 'Remittance_Advice',
        'Existing_HWS' => '_Old_Existing_HWS',
        'Meter_UpClose' => '_Meter_UpClose',
        'Roof_Pitch' => '_Roof_Pitch',
        'Acceptance' => '_Acceptance',
        'House_Plans' => '_House_Plans',
        'Meter_Box' => '_Meter_Box',
        'Install_Photo' => '_New_Install_Photo'
    );
    foreach($get_all_photo as $photo){
            $file_name = $photo;
            foreach ($array_convert_file_name as $key => $label_new_file) {
                $condition_change_file = false;
                $array_explode_name =  explode('_',$key);
                // check file in quote include name in array convert file
                foreach ($array_explode_name as $value_name) {
                    if(strpos(strtolower($file_name), strtolower($value_name)) !== false ){
                        $condition_change_file = true;
                    }else{
                        $condition_change_file = false;
                    }
                }  
                if($condition_change_file){    
                    $extension=end(explode(".", $file_name));
                    $new_file_name = 'Inv-'.$invoice->number.$label_new_file;
                    $inv_file_path = 
                    $i = 1;
                    $will_rename = $new_file_name;
                    $current_file_path_inv = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$invoice->installation_pictures_c;
                    while( !empty(glob($current_file_path_inv.'/'.$will_rename."*"))){
                      $will_rename = $new_file_name.$i;
                      $i++;
                    }
                   
                    $will_rename .= ('.'.$extension);
                    $new_file_name = $will_rename; 
                    break;
                }else{
                    $new_file_name = $file_name;
                }
            }



            $folderName_old  = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$quote->pre_install_photos_c.'/'.$file_name;
            $folderName_new  = $_SERVER["DOCUMENT_ROOT"] .'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'.$invoice->installation_pictures_c.'/';
          
            //check exists folder
            if(!file_exists ($folderName_new)) {
                mkdir($folderName_new);
            }
            copy($folderName_old, $folderName_new.$new_file_name);
            addToNotes($new_file_name,$folderName_new,$invoice->id,"AOS_Invoice");
            
    }
	//End Dung code
    if(strtolower(trim($quote->install_address_state_c)) == 'sa' && ($quote->quote_type_c == 'quote_type_sanden' || strpos(strtolower($quote->name),'sanden') !== false) ) {
        Generate_REPS_WH1_PDF($invoice);
    }
   
    ob_clean();
	$create_three_po = true;
	// BinhNT;   
    require_once('modules/PO_purchase_order/CreatePurchaseOrder.php');
    //VUT - S - Auto create PO
    // $invoiceBean = BeanFactory::getBean("AOS_Invoices", $invoice->id);
    $invoiceBean = new AOS_Invoices();
    $invoiceBean->retrieve($invoice->id);
    $isSandenSupply = isSandenSupply($invoiceBean->id, $quote);
    if (($isSandenSupply['SSI'] || $isSandenSupply['SSO']) && $quote->proposed_dispatch_date_c != '' && ($quote->quote_type_c == 'quote_type_sanden' || strpos(strtolower($quote->name),'sanden') !== false)) {
       createPO('sanden_supply', $invoiceBean , $invoiceBean->installation_pictures_c, gererate_UUID_for_invoice());
    }
    if ($invoiceBean->delivery_date_time_c != '' && (in_array($quote->quote_type_c,$array_product_type_daikin) || strpos(strtolower($quote->name),'daikin') !== false )) {
        createPO('daikin', $invoiceBean, $invoiceBean->installation_pictures_c, gererate_UUID_for_invoice());
    }
    if ($invoiceBean->quote_type_c == 'quote_type_sanden' && $quote->proposed_install_date_c != '') {
        if ( $invoiceBean->account_id1_c != '' ) {
            createPO('plumber', $invoiceBean , $invoiceBean->installation_pictures_c, gererate_UUID_for_invoice());
        }
        if ($invoiceBean->account_id_c != '' ) {
            createPO('electrical', $invoiceBean , $invoiceBean->installation_pictures_c, gererate_UUID_for_invoice());
        }
    }
    if ($invoiceBean->quote_type_c == 'quote_type_daikin'  && $quote->proposed_install_date_c != '') {
        if ( $invoiceBean->account_id1_c != '' ) {
            createPO('plumber', $invoiceBean , $invoiceBean->installation_pictures_c, gererate_UUID_for_invoice());
        }
    }
    //VUT - E - Auto create PO
    header('Location: index.php?module=AOS_Invoices&action=EditView&record='.$invoice->id);

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
    function addToNotes($file,$folderName,$parent_id,$parent_type){
        // $listFile = dirToArray($folderName);
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
    
        $url_img = $folderName.$file;
        $percent = 0.85;
        //Get path image
    
    
        // Get new sizes
        list($width, $height) = getimagesize($url_img);
        $newwidth = round($width * $percent);
    
        if($newwidth >= 800){
            resize_upload($newwidth,$url_img,$url_img);
            $size_image_new =filesize ($url_img);
            echo FileSizeConvert_Upload($size_image_new);
        } else {
            echo 'ERROR';
        }
            // rotateImage($url_img);
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
    
    function delete_directory($dirname) {
        if (is_dir($dirname))
            $dir_handle = opendir($dirname);
        if (!$dir_handle)
            return false;
        while($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                    unlink($dirname."/".$file);
                else
                    delete_directory($dirname.'/'.$file);
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
    
    function resize_upload($newWidth, $targetFile, $originalFile) {
    
        $info = getimagesize($originalFile);
        $mime = $info['mime'];
        switch ($mime) {
                case 'image/jpeg':
                        $image_create_func = 'imagecreatefromjpeg';
                        $image_save_func = 'imagejpeg';
                        $new_image_ext = 'jpg';
                        break;
    
                case 'image/png':
                        $image_create_func = 'imagecreatefrompng';
                        $image_save_func = 'imagepng';
                        $new_image_ext = 'png';
                        break;
    
                case 'image/gif':
                        $image_create_func = 'imagecreatefromgif';
                        $image_save_func = 'imagegif';
                        $new_image_ext = 'gif';
                        break;
    
                default: 
                        throw new Exception('Unknown image type.');
        }
    
        $img = $image_create_func($originalFile);
        list($width, $height) = getimagesize($originalFile);
    
        $newHeight = ($height / $width) * $newWidth;
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
        if (file_exists($targetFile)) {
                unlink($targetFile);
        }
        $image_save_func($tmp, "$targetFile");
    }
    // convert byte to MB,KB,B,TB,GB
    function FileSizeConvert_Upload($bytes)
    {
        $bytes = floatval($bytes);
            $arBytes = array(
                0 => array(
                    "UNIT" => "TB",
                    "VALUE" => pow(1024, 4)
                ),
                1 => array(
                    "UNIT" => "GB",
                    "VALUE" => pow(1024, 3)
                ),
                2 => array(
                    "UNIT" => "MB",
                    "VALUE" => pow(1024, 2)
                ),
                3 => array(
                    "UNIT" => "KB",
                    "VALUE" => 1024
                ),
                4 => array(
                    "UNIT" => "B",
                    "VALUE" => 1
                ),
            );
    
        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "." , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    function gererate_UUID_for_invoice(){
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
    }

    function create_warehouse_log($InvoiceID,$quote,$aupost_shipping_id,$connote_id){
        $BeanInvoice = BeanFactory::getBean('AOS_Invoices', $InvoiceID);
        if($BeanInvoice->id != ''&& $quote->id != ''){
            $pe_warehouse_log = BeanFactory::newBean('pe_warehouse_log');
            $rawRow = $quote->fetched_row;
            $rawRow['id'] = '';
            $rawRow['number'] = '';
            $rawRow['date_entered'] = '';
            $rawRow['date_modified'] = ''; 
            $rawRow['total_amt'] = '';
            $rawRow['discount_amount'] = '';
            $rawRow['subtotal_amount'] = '';
            $rawRow['tax_amount'] = '';
            $rawRow['total_amount'] = '';
            $rowRow['billing_account_id'] = '';
            $pe_warehouse_log->populateFromRow($rawRow);
            //shipping address
            $pe_warehouse_log->shipping_address_street =  $BeanInvoice->shipping_address_street;   
            $pe_warehouse_log->shipping_address_city =  $BeanInvoice->shipping_address_city;
            $pe_warehouse_log->shipping_address_state = $BeanInvoice->shipping_address_state;
            $pe_warehouse_log->shipping_address_postalcode = $BeanInvoice->shipping_address_postalcode;
            $pe_warehouse_log->shipping_address_country  =  $BeanInvoice->shipping_address_country;

            //destination address and billing address
            $pe_warehouse_log->destination_address_street = $pe_warehouse_log->billing_address_street = '';
            $pe_warehouse_log->destination_address_city = $pe_warehouse_log->billing_address_city = '';
            $pe_warehouse_log->destination_address_state = $pe_warehouse_log->billing_address_state = '';
            $pe_warehouse_log->destination_address_postalcode = $pe_warehouse_log->billing_address_postalcode = '';
            $pe_warehouse_log->destination_address_country = $pe_warehouse_log->billing_address_country = '';

            $pe_warehouse_log->process_save_dates =false;
            $pe_warehouse_log->sold_to_invoice_id = $InvoiceID;
            $pe_warehouse_log->shipping_account_id = $BeanInvoice->billing_account_id;
            $pe_warehouse_log->shipping_product_type_c = 'quote_type_methven';
            $pe_warehouse_log->carrier = 'Australia Post';
            $pe_warehouse_log->whlog_status = 'Unallocated';
            $pe_warehouse_log->aupost_shipping_id = $aupost_shipping_id;
            $pe_warehouse_log->connote = $connote_id;
            $pe_warehouse_log->save(); 
        }
    }

    /**
     * VUT - Check is Sanden Supply for PO
     * @param string $invoiceID
     * @param Module $quote
     * 
     */
    function isSandenSupply($invoiceID, $quote) {
        $sanden_supply = array (
            'SSI' => false,
            'SSO' => false,
        );
        $SSI = false;
        $states = ['NSW', 'QLD', 'SA', 'WA'];
      
        $db = DBManagerFactory::getInstance();
        $query = "SELECT * FROM aos_products_quotes WHERE parent_type = 'AOS_Invoices' AND parent_id = '".$invoiceID."' AND deleted = 0";
        $ret = $db->query($query);
        while($row = $db->fetchByAssoc($ret)){
            if ($row['part_number'] == 'SANDEN_SUPPLY_ONLY') {
              $sanden_supply['SSO'] = true;
            }
            if ($row['part_number'] == 'SSI') {
                $SSI = true;
            }
        }
        if ($SSI && in_array(trim($quote->install_address_state_c),$states)) {
          $sanden_supply['SSI'] = true;
        }
        return $sanden_supply;
    }

    /**
     * VUT - create Meetting For Installer
     * @param  module $invoice
     * @return string  $meeting->id 
     */
    function createMettingForInstaller($invoice) {
        $meetings = new Meeting;
        $meetings->name = $invoice->name;
        $meetings->date_start = $invoice->installation_date_c;
        $meetings->parent_type = "Accounts";
        $meetings->parent_id = $invoice->billing_account_id;
        $meetings->assigned_user_id = $invoice->assigned_user_id;
        $meetings->aos_invoices_id_c = $invoice->id;
        if(empty($meetings->duration_hours)){
            $meetings->duration_hours  = 3;
            $meetings->duration_minutes  = 0;
        }
        $meetings->save();
        $invoice->meeting_c = $meetings->id;
        $invoice->save();
        global $current_user;
        
        
        $reminder_json = '[{"idx":0,"id":"","popup":false,"email":true,"timer_popup":"60","timer_email":"86400","invitees":[{"id":"","module":"Users","module_id":"'.$current_user->id.'"}]}]';
        $meetings->saving_reminders_data = true;
        $reminderData = json_encode(
            $meetings->removeUnInvitedFromReminders(json_decode(html_entity_decode($reminder_json), true))
        );
        Reminder::saveRemindersDataJson('Meetings', $meetings->id, $reminderData);
        $meetings->saving_reminders_data = false;
        
        
        $relate_values = array('user_id'=>$current_user->id,'meeting_id'=>$meetings->id);
        $data_values = array('accept_status'=>true);
        $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
        
        //thien fix
        if($current_user->id == '8d159972-b7ea-8cf9-c9d2-56958d05485e'){
            $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meetings->id);
            $data_values = array('accept_status'=>true);
            $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
        }else if($current_user->id == '61e04d4b-86ef-00f2-c669-579eb1bb58fa'){
            $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
            $data_values = array('accept_status'=>true);
            $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
        }else{
            $relate_values = array('user_id'=>'61e04d4b-86ef-00f2-c669-579eb1bb58fa','meeting_id'=>$meetings->id);
            $data_values = array('accept_status'=>true);
            $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
        
            $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
            $data_values = array('accept_status'=>true);
            $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
        }
        
        // $relate_values = array('user_id'=>'8d159972-b7ea-8cf9-c9d2-56958d05485e','meeting_id'=>$meetings->id);
        // $data_values = array('accept_status'=>true);
        // $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
        
        // $relate_values = array('user_id'=>'d028d21f-504c-c8ff-3ba3-57ab05ae7a4d','meeting_id'=>$meetings->id);
        // $data_values = array('accept_status'=>true);
        // $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
        
        $relate_values = array('user_id'=>'ad0d4940-e0ea-1dc1-7748-592b7b07d80f','meeting_id'=>$meetings->id);
        $data_values = array('accept_status'=>true);
        $meetings->set_relationship($meetings->rel_users_table, $relate_values, false, false,$data_values);
        
        //$meetings->users->add("61e04d4b-86ef-00f2-c669-579eb1bb58fa");
        //$meetings->save();
        //????
        if($meetings->update_vcal)
        {
            vCal::cache_sugar_vcal($user);
        }
        // echo $meetings->id;
    }

    // Function Generate REPS_WH1_PDF 
    function Generate_REPS_WH1_PDF($invoice){
        $tmpfsuitename = dirname(__FILE__).'/cookiesuitecrm.txt';
        $fields = array();
        $url = 'http://locsuitecrm.com/index.php?entryPoint=Generate_REPS_WH1_PDF&InvoiceID='.$invoice->id;
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
        $result = curl_exec($curl); 
    }