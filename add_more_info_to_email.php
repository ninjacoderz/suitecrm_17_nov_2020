<?php 

require_once(dirname(__FILE__).'/custom/include/SugarFields/Fields/Multiupload/'.'simple_html_dom.php');
$file=$_GET["file_name"];
$data=file_get_contents("email/".$file);

// Get from Email from data 
preg_match('#Return-Path: <(.+?)>#i', $data, $matches);

$lookup_result = array();

if (isset($matches[1]) && $matches[1] != "") {

	//thien fix if sent from system@netsuite.com
	//if($matches[1] == "system@netsuite.com"){
	if (strpos($matches[1], "netsuite.com") !== false){
		
		// Copy it to special folder for backup
		copy("email/".$file,"email/specials/".$file);
		// 1: Get pdf from email
		preg_match('/Content-Disposition: attachment; filename=(.*?)[\r\n](.*?)=/s',$data,$match_pdf);
		if(isset($match_pdf[2]) && $match_pdf[2] != ""){
			$pdf_content =  base64_decode($match_pdf[2]);
			$folder = dirname(__FILE__).'/custom/include/SugarFields/Fields/Multiupload/server/php/html';
			$fp = fopen($folder.'/'.'convert.pdf', "w+");
			fwrite($fp, $pdf_content);
			fclose($fp);

			// 2. parse to HTML

			exec("which pdftohtml", $output, $returnStatus);
			exec("/bin/pdftohtml -nodrm ".$folder."/convert.pdf ".$folder."/convert.html"." 2>&1", $output);

			$ahtml = file_get_contents(realpath($folder."/converts.html"));
			$geo_id_arr = '';	
			preg_match('/<b>Memo<\/b><br\/>(.*?)<b>Amount<\/b><br\/>/s', $ahtml, $match_pdf_1);
			if(isset($match_pdf_1[1])){
				preg_match_all('/[WH|SH]+(.-).[0-9]+/s',$match_pdf_1[1],$match_pdf_2);
				if(count($match_pdf_2[0]) > 0){
					foreach($match_pdf_2[0] as $res){
						$geo_id_arr.="'".$res."',";
					}
					$geo_id_arr ="(".trim($geo_id_arr,',').")";

					// 3. Parse HTML -> Invoice LInk

					$db = DBManagerFactory::getInstance();
					$query = "SELECT id_c FROM aos_invoices_cstm  WHERE (stc_aggregator_serial_c IN ".$geo_id_arr."  OR stc_aggregator_serial_2_c IN ".$geo_id_arr." OR stc_aggregator_c IN ".$geo_id_arr." ) ORDER BY stc_aggregator_serial_c ASC" ;

					// 4. Alter email content CRM LINK

					preg_match('/boundary=(.*?)\n/i', $data, $matches_new);
					if(count($matches_new) && $matches_new[1] != ""){
	
						$matches_new[1] = trim($matches_new[1],'"');
						$email_segments_new = explode('--' . $matches_new[1], $data);
						for ($i = 0; $i < count($email_segments_new); $i++){
							if (strpos($email_segments_new[$i], "Content-Type: text/plain") !== false){
								$crm_links = "";
								$crm_links .= "CRM Links: ".PHP_EOL;
                                $ret = $db->query($query);
								while($row = $db->fetchByAssoc($ret)){
                                    $crm_links .= 'CRM Invoice : https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$row['id_c'].PHP_EOL;
								}

                                $crm_links .= "End Links";
                                if( strpos($email_segments_new[$i],'Content-Type: text/plain; charset="UTF-8"') !== false ){
                                    $email_segments_new[$i] = str_replace('Content-Type: text/plain; charset="UTF-8"', 'Content-Type: text/plain; charset="UTF-8"'.PHP_EOL.PHP_EOL.$crm_links, $email_segments_new[$i]);
                                }
                                elseif(strpos($email_segments_new[$i],'Content-Type: text/plain;') !== false && strpos($email_segments_new[$i], 'charset=us-ascii') !== false && strpos($email_segments_new[$i], "Content-Transfer-Encoding: 7bit") !== false){
                                    $email_segments_new[$i] = str_replace('Content-Transfer-Encoding: 7bit', 'Content-Transfer-Encoding: 7bit'.PHP_EOL.PHP_EOL.PHP_EOL.$crm_links, $email_segments_new[$i]);
                                }
							}
							if (strpos($email_segments_new[$i], "Content-Type: text/html;") !== false){
								$crm_links = "";
								$crm_links .= "CRM Links: ".PHP_EOL;
                                $ret = $db->query($query);
								while($row1 = $db->fetchByAssoc($ret)){
                                    if(strpos($email_segments_new[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
                                        $crm_links .= '<a href=3D"https://suitecrm.pure-electric.com.au/index.php?module=3DAOS_Invoices&action=3DEditView&record=3D'.$row1['id_c'].'">[CRM Invoice]</a>'.PHP_EOL;
                                    } else {
                                        $crm_links .= '<a href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$row1['id_c'].'">[CRM Invoice]</a>'.PHP_EOL;
                                    }
								}
                                $crm_links .= "End Links";
                                preg_match('/<BODY[^>]*>.*?/', $email_segments_new[$i], $out);
                                $first_div = "";
                                if(isset($out[0])){
                                    $first_div  = $out[0];
                                }
                                $email_segments_new[$i]  = preg_replace("/<BODY[^>]*>.*?/", $first_div.$crm_links.'<br/>'.'<br/>', $email_segments_new[$i], 1);
                                $email_segments_new[$i] .= PHP_EOL.PHP_EOL;
							}
						}
						file_put_contents("email/".$file, implode('--' . $matches_new[1], $email_segments_new));
						copy("email/".$file,"email/specials/after/".$file);
						return;
					}
				}
			}
		}
		
	}
	//end
	// If sent from do-not-reply@geocreation.com.au
	$reference_no == "";
	if( $matches[1] == "do-not-reply@geocreation.com.au" ){
		preg_match('#Reference no: (.+?)\n#i', $data, $reference_no);
		$reference_no = trim(strip_tags($reference_no[1]));

	}

	// If is sent from our system 
	
	if( $matches[1] == "binh.nguyen@pure-electric.com.au" ||
		$matches[1] == "operations@pure-electric.com.au" ||
		$matches[1] == "ross@pure-electric.com.au" ||
		$matches[1] == "ross.munro@pure-electric.com.au" ||
		$matches[1] ==  "paul.szuster@pure-electric.com.au" ||
		$matches[1] ==  "paul@pure-electric.com.au" ||
		$matches[1] ==  "matthew.wright@pure-electric.com.au" ||
		$matches[1] ==  "matthew@pure-electric.com.au" ||
		$matches[1] ==  "lee.andrewartha@pure-electric.com.au" ||
		$matches[1] == "pure.electric.com.au@gmail.com" || 
		$matches[1] == "john.hooper@pure-electric.com.au"){
			$data = preg_replace('/CRM Links\:[\s\S]+?End Links/', '', $data);
			file_put_contents("email/".$file, $data);
			return;
	}

	$email = str_replace(".","",$matches[1]);
	$db = DBManagerFactory::getInstance();

    $sql = "SELECT ear.bean_id AS id , bean_module AS module FROM email_addresses ea
    		RIGHT JOIN email_addr_bean_rel ear ON ear.email_address_id = ea.id
    		WHERE 1=1 
    		AND ea.deleted  != 1 AND ear.deleted != 1
    		AND LOWER(replace(ea.email_address, '.', '')) LIKE LOWER(replace('%".$email."%', '.', ''))";
	
    $ret = $db->query($sql);

    while ($row = $db->fetchByAssoc($ret)) {
    	$lookup_result[] = $row;
	}

	if(count($lookup_result)== 0  && $reference_no == "") return;
}

$matches = array();
preg_match('/boundary=(.*?)\n/i', $data, $matches);
if(count($matches) == 0){
	preg_match("/CRM Links:(.+?)End Links/s", $email_segments[$i], $link_matches);
	if (isset($link_matches[0])&& $link_matches[0]!= ""){
		$email_segments[$i] = str_replace($link_matches[0], "", $email_segments[$i]);
	}
	
	if(count($lookup_result)){
		$crm_links = "";
		$crm_links .= "CRM Links: ".PHP_EOL;
		$db = DBManagerFactory::getInstance();
		$quotes = array();
		$invoices = array();
		foreach($lookup_result as $res){
			$crm_links .= preg_replace('/(?:s)$/', '',$res['module']).": https://suitecrm.pure-electric.com.au/index.php?module=".$res['module']."&action=EditView&record=".$res['id'].PHP_EOL;
			if($res['module'] == "Leads"){
				$lead = new Lead();
				$lead->retrieve($res['id']);

				if(isset($lead->email1) && $lead->email1!= ""){
					//<a target="_blank" href="https://mail.google.com/#search/fraserwikner%40hotmail.com">GM Search</a>
					$crm_links .= "GSearch:".": https://mail.google.com/#search/".$lead->email1.PHP_EOL;
					$crm_links .= "SMS link: http://message.pure-electric.com.au/search_message.php?crm_ref=".$lead->id.PHP_EOL;
				}

				// if(isset($lead->solargain_lead_number_c) && $lead->solargain_lead_number_c!= ""){
				// 		$crm_links .= "Solargain Lead:".": https://crm.solargain.com.au/lead/edit/".$lead->solargain_lead_number_c.PHP_EOL;
				// }
				// if(isset($lead->solargain_quote_number_c) && $lead->solargain_quote_number_c!= ""){
				// 		$crm_links .= "Solargain Quote:".": https://crm.solargain.com.au/quote/edit/".$lead->solargain_quote_number_c.PHP_EOL;
				// }

				//Change logic get number quote solar from lead to quote
					//quote Suitecrm solar normal
					$bean_quote = new AOS_Quotes();
					$bean_quote->retrieve( $lead->create_solar_quote_num_c);
					if($bean_quote->id != '') {
						$number_lead = $bean_quote->solargain_lead_number_c;
						if(isset($bean_quote->solargain_lead_number_c) && $bean_quote->solargain_lead_number_c!= ""){
							$crm_links .= "Solargain Lead:".": https://crm.solargain.com.au/lead/edit/".$bean_quote->solargain_lead_number_c.PHP_EOL;
						}
						if(isset($bean_quote->solargain_quote_number_c) && $bean_quote->solargain_quote_number_c!= ""){
							$crm_links .= "Solargain Quote:".": https://crm.solargain.com.au/quote/edit/".$bean_quote->solargain_quote_number_c.PHP_EOL;
						}
						if(isset($bean_quote->solargain_tesla_quote_number_c) && $bean_quote->solargain_tesla_quote_number_c!= ""){
							$crm_links .= "Solargain Tesla Quote:".": https://crm.solargain.com.au/quote/edit/".$bean_quote->solargain_tesla_quote_number_c.PHP_EOL;
						}
					} 


					//quote Suitecrm solar tesla 
					$bean_quote = new AOS_Quotes();
					$bean_quote->retrieve( $lead->create_tesla_quote_num_c);
					if($bean_quote->id != '') {
						$number_lead = $bean_quote->solargain_lead_number_c;
						if(isset($bean_quote->solargain_lead_number_c) && $bean_quote->solargain_lead_number_c!= ""){
							$crm_links .= "Solargain Lead:".": https://crm.solargain.com.au/lead/edit/".$bean_quote->solargain_lead_number_c.PHP_EOL;
						}
						if(isset($bean_quote->solargain_quote_number_c) && $bean_quote->solargain_quote_number_c!= ""){
							$crm_links .= "Solargain Quote:".": https://crm.solargain.com.au/quote/edit/".$bean_quote->solargain_quote_number_c.PHP_EOL;
						}
						if(isset($bean_quote->solargain_tesla_quote_number_c) && $bean_quote->solargain_tesla_quote_number_c!= ""){
							$crm_links .= "Solargain Tesla Quote:".": https://crm.solargain.com.au/quote/edit/".$bean_quote->solargain_tesla_quote_number_c.PHP_EOL;
						}
					} 
				//End Change logic get number quote solar from lead to quote
				if(isset($lead->phone_mobile) && $lead->phone_mobile != ""){
						$crm_links .= "Mobile:".$lead->phone_mobile.PHP_EOL;
				}
				if(isset($lead->phone_work) && $lead->phone_work != ""){
						$crm_links .= "Work:".$lead->phone_work.PHP_EOL;
				}
				if(isset($lead->phone_work) && $lead->phone_work != ""){
					$crm_links .= "W: ".$lead->phone_work.": http://message.pure-electric.com.au/#".  preg_replace("/^0/", "61", preg_replace('/\D/', '', $lead->phone_work)).PHP_EOL;
				} else if(isset($lead->phone_mobile) && $lead->phone_mobile != ""){
					$crm_links .= "M: ".preg_replace('/(\s)/','',$lead->phone_mobile).": http://message.pure-electric.com.au/#".preg_replace("/^0/", "61", preg_replace('/\D/', '', $lead->phone_mobile)).PHP_EOL;
				}			
				if(isset($lead->primary_address_postalcode) && $lead->primary_address_postalcode != ""){
						$crm_links .= " ".$lead->primary_address_street. " ".
												$lead->primary_address_city. " ".
												$lead->primary_address_state. " ".
												$lead->primary_address_postalcode .PHP_EOL;
				}
				// BinhNT Remove this links
				if(false) if(isset($lead->id) && $lead->id != ""){
						$crm_links .= "Acceptance Email: https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateAcceptanceLink&lead_id=".$lead->id.PHP_EOL;
						//thienpb code - add link forward acceptance email to sg sam
						$crm_links .= "Forward Acceptance Email: https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateForwardAcceptanceLink&lead_id=".$lead->id.PHP_EOL;
				}
				//primary_address_city
			}
			if($res['module'] == 'Accounts'){            
				$sql = "SELECT id FROM aos_quotes WHERE billing_account_id='".$res['id']."'";
				$ret = $db->query($sql);
				if($ret->num_rows >0){
					while($row = $db->fetchByAssoc($ret)){
						if(!in_array($row,$quotes)){
							$quotes[] = $row;
						}
					}
				}
				$sql = "SELECT id FROM aos_invoices WHERE billing_account_id='".$res['id']."'";
				$ret = $db->query($sql);
				if($ret->num_rows >0){
					while($row = $db->fetchByAssoc($ret)){
						if(!in_array($row,$invoices)){
							$invoices[] = $row;
						}
					}
				}
			}
			if($res['module'] == "Contacts"){
				$sql = "SELECT id FROM aos_quotes WHERE billing_contact_id = '".$res['id']."'";
				$ret = $db->query($sql);
				if($ret->num_rows >0){
					while($row = $db->fetchByAssoc($ret)){
						if(!in_array($row,$quotes)){
							$quotes[] = $row;
						}
					}
				}
				$sql = "SELECT id FROM aos_invoices WHERE billing_contact_id = '".$res['id']."'";
				$ret = $db->query($sql);
				if($ret->num_rows >0){
					while($row = $db->fetchByAssoc($ret)){
						if(!in_array($row,$invoices)){
							$invoices[] = $row;
						}
					}
				}
			}
		}
		if(count($quotes) >0){
			$group_name ='';
			foreach ($quotes as $res_qt){
				$quote = new AOS_Quotes();
				$quote->retrieve($res_qt['id']);
				$sql = "SELECT name FROM aos_line_item_groups WHERE parent_id = '".$quote->id."' AND parent_type = 'AOS_Quotes'" ;
				$ret = $db->query($sql);
				$row = $db->fetchByAssoc($ret);
				if(strpos(strtolower($row['name']),'daikin') !== false){
					$group_name = ' DAIKIN';
				}else if(strpos(strtolower($row['name']),'sanden') !== false){
					$group_name = ' SANDEN';
				}else if(strpos(strtolower($row['name']),'methven') !== false){
					$group_name = ' METHVEN';
					$crm_links .= "Auspost Link: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/ready".PHP_EOL;
				}else{
					$group_name ='';
				}

				$crm_links .= 'PEQ '.$quote->number.$group_name.": https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$quote->id.PHP_EOL;
			}
		}
		if(count($invoices) >0){
			$group_name ='';
			foreach ($invoices as $res_inv){
				$invoice_new = new AOS_Invoices();
				$invoice_new->retrieve($res_inv['id']);
				$crm_links .= 'PEINV '.$invoice_new->number.": https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$invoice_new->id.PHP_EOL;
			}
		}

		$crm_links .= "End Links";
	}

	if(isset($reference_no) && $reference_no != ""){
		// Query for get invoice link
		$db = DBManagerFactory::getInstance();

		$sql = "SELECT * FROM aos_invoices_cstm
				WHERE 1=1 
				AND (stc_aggregator_serial_c = '$reference_no' OR stc_aggregator_serial_2_c = '$reference_no')";

		$ret = $db->query($sql);

		while ($row = $db->fetchByAssoc($ret)) {
			$lookup_result[] = $row;
		}
		if(count($lookup_result)== 0) return;

		if(count($lookup_result)){
			$crm_links = "";
			$crm_links .= "CRM Links: ".PHP_EOL;
			foreach($lookup_result as $res){
				$crm_links .= "AOS_Invoices: https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$res['id'].PHP_EOL;
			}
			$crm_links .= "End Links";
		}
		file_put_contents("email/".$file, file_get_contents("email/".$file).PHP_EOL.$crm_links);
		return;
	}

	file_put_contents("email/".$file, file_get_contents("email/".$file).PHP_EOL.$crm_links);
}
if(count($matches) && $matches[1] != ""){
	
	$matches[1] = trim($matches[1],'"');
	$email_segments = explode('--' . $matches[1], $data);

	for ($i = 0; $i < count($email_segments); $i++)
	{
		if (strpos($email_segments[$i], "Content-Type: text/plain") !== false)
		{
			preg_match("/CRM Links:(.+?)End Links/s", $email_segments[$i], $link_matches);
			if (isset($link_matches[0])&& $link_matches[0]!= ""){
				$email_segments[$i] = str_replace($link_matches[0], "", $email_segments[$i]);
			}

			if(count($lookup_result)){
				$crm_links = "";
				$crm_links .= "CRM Links: ".PHP_EOL;
				$db = DBManagerFactory::getInstance();
				$quotes = array();
				$invoices = array();
				foreach($lookup_result as $res){
					$crm_links .= preg_replace('/(?:s)$/', '',$res['module']).": https://suitecrm.pure-electric.com.au/index.php?module=".$res['module']."&action=EditView&record=".$res['id'].PHP_EOL;
					if($res['module'] == "Leads"){
						$lead = new Lead();
						$lead->retrieve($res['id']);

						if(isset($lead->email1) && $lead->email1!= ""){
							//<a target="_blank" href="https://mail.google.com/#search/fraserwikner%40hotmail.com">GMSearch</a>
							$crm_links .= "GSearch:".": https://mail.google.com/#search/".$lead->email1.PHP_EOL;
							$crm_links .= "SMS link: http://message.pure-electric.com.au/search_message.php?crm_ref=".$lead->id.PHP_EOL;

						}
						// if(isset($lead->solargain_lead_number_c) && $lead->solargain_lead_number_c!= ""){
						// 		$crm_links .= "Solargain Lead:".": https://crm.solargain.com.au/lead/edit/".$lead->solargain_lead_number_c.PHP_EOL;
						// }
						// if(isset($lead->solargain_quote_number_c) && $lead->solargain_quote_number_c!= ""){
						// 		$crm_links .= "Solargain Quote:".": https://crm.solargain.com.au/quote/edit/".$lead->solargain_quote_number_c.PHP_EOL;
						// }
						//Change logic get number quote solar from lead to quote
							//quote Suitecrm solar normal
							$bean_quote = new AOS_Quotes();
							$bean_quote->retrieve( $lead->create_solar_quote_num_c);
							if($bean_quote->id != '') {
								$number_lead = $bean_quote->solargain_lead_number_c;
								if(isset($bean_quote->solargain_lead_number_c) && $bean_quote->solargain_lead_number_c!= ""){
									$crm_links .= "Solargain Lead:".": https://crm.solargain.com.au/lead/edit/".$bean_quote->solargain_lead_number_c.PHP_EOL;
								}
								if(isset($bean_quote->solargain_quote_number_c) && $bean_quote->solargain_quote_number_c!= ""){
									$crm_links .= "Solargain Quote:".": https://crm.solargain.com.au/quote/edit/".$bean_quote->solargain_quote_number_c.PHP_EOL;
								}
								if(isset($bean_quote->solargain_tesla_quote_number_c) && $bean_quote->solargain_tesla_quote_number_c!= ""){
									$crm_links .= "Solargain Tesla Quote:".": https://crm.solargain.com.au/quote/edit/".$bean_quote->solargain_tesla_quote_number_c.PHP_EOL;
								}
							} 


							//quote Suitecrm solar tesla 
							$bean_quote = new AOS_Quotes();
							$bean_quote->retrieve( $lead->create_tesla_quote_num_c);
							if($bean_quote->id != '') {
								$number_lead = $bean_quote->solargain_lead_number_c;
								if(isset($bean_quote->solargain_lead_number_c) && $bean_quote->solargain_lead_number_c!= ""){
									$crm_links .= "Solargain Lead:".": https://crm.solargain.com.au/lead/edit/".$bean_quote->solargain_lead_number_c.PHP_EOL;
								}
								if(isset($bean_quote->solargain_quote_number_c) && $bean_quote->solargain_quote_number_c!= ""){
									$crm_links .= "Solargain Quote:".": https://crm.solargain.com.au/quote/edit/".$bean_quote->solargain_quote_number_c.PHP_EOL;
								}
								if(isset($bean_quote->solargain_tesla_quote_number_c) && $bean_quote->solargain_tesla_quote_number_c!= ""){
									$crm_links .= "Solargain Tesla Quote:".": https://crm.solargain.com.au/quote/edit/".$bean_quote->solargain_tesla_quote_number_c.PHP_EOL;
								}
							} 
						//End Change logic get number quote solar from lead to quote
						
						if(isset($lead->primary_address_postalcode) && $lead->primary_address_postalcode != ""){
								$crm_links .= " ".$lead->primary_address_street. " ".
														$lead->primary_address_city. " ".
														$lead->primary_address_state. " ".
														$lead->primary_address_postalcode .PHP_EOL;
						}

						if(false) if(isset($lead->id) && $lead->id != ""){
								$crm_links .= "Acceptance Email: https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateAcceptanceLink&lead_id=".$lead->id.PHP_EOL;
								//thienpb code - add link forward acceptance email to sg sam
								$crm_links .= "Forward Acceptance Email: https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateForwardAcceptanceLink&lead_id=".$lead->id.PHP_EOL;
						}
					}
					if($res['module'] == 'Accounts'){            
						$sql = "SELECT id FROM aos_quotes WHERE billing_account_id='".$res['id']."'";
						$ret = $db->query($sql);
						if($ret->num_rows >0){
							while($row = $db->fetchByAssoc($ret)){
								if(!in_array($row,$quotes)){
									$quotes[] = $row;
								}
							}
						}
						$sql = "SELECT id FROM aos_invoices WHERE billing_account_id='".$res['id']."'";
						$ret = $db->query($sql);
						if($ret->num_rows >0){
							while($row = $db->fetchByAssoc($ret)){
								if(!in_array($row,$invoices)){
									$invoices[] = $row;
								}
							}
						}
					}
					if($res['module'] == "Contacts"){
						$sql = "SELECT id FROM aos_quotes WHERE billing_contact_id = '".$res['id']."'";
						$ret = $db->query($sql);
						if($ret->num_rows >0){
							while($row = $db->fetchByAssoc($ret)){
								if(!in_array($row,$quotes)){
									$quotes[] = $row;
								}
							}
						}
						$sql = "SELECT id FROM aos_invoices WHERE billing_contact_id = '".$res['id']."'";
						$ret = $db->query($sql);
						if($ret->num_rows >0){
							while($row = $db->fetchByAssoc($ret)){
								if(!in_array($row,$invoices)){
									$invoices[] = $row;
								}
							}
						}
					}
					
				}
				if(count($quotes) >0){
					$group_name ='';
					foreach ($quotes as $res_qt){
						$quote = new AOS_Quotes();
						$quote->retrieve($res_qt['id']);
						$sql = "SELECT name FROM aos_line_item_groups WHERE parent_id = '".$quote->id."' AND parent_type = 'AOS_Quotes'" ;
						$ret = $db->query($sql);
						$row = $db->fetchByAssoc($ret);
						if(strpos(strtolower($row['name']),'daikin') !== false){
							$group_name = ' DAIKIN';
						}else if(strpos(strtolower($row['name']),'sanden') !== false){
							$group_name = ' SANDEN';
						}else if(strpos(strtolower($row['name']),'methven') !== false){
							$group_name = ' METHVEN';
							$crm_links .= "Auspost Link: https://auspost.com.au/mypost-business/shipping-and-tracking/orders/ready".PHP_EOL;
						}else{
							$group_name ='';
						}
						$crm_links .= 'PEQ '.$quote->number.$group_name.": https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record=".$quote->id.PHP_EOL;
	
					}
				}

				if(count($invoices) >0){
					$group_name ='';
					foreach ($invoices as $res_inv){
						$invoice_new = new AOS_Invoices();
						$invoice_new->retrieve($res_inv['id']);
						$crm_links .= 'PEINV '.$invoice_new->number.": https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$invoice_new->id.PHP_EOL;
					}
				}
				$crm_links .= "End Links";
			}
			if( strpos($email_segments[$i],'Content-Type: text/plain; charset="UTF-8"') !== false ){
				$email_segments[$i] = str_replace('Content-Type: text/plain; charset="UTF-8"', 'Content-Type: text/plain; charset="UTF-8"'.PHP_EOL.PHP_EOL.$crm_links, $email_segments[$i]);
			}elseif(strpos($email_segments[$i],'Content-Type: text/plain; charset=utf-8') !== false && strpos($email_segments[$i],'Content-Transfer-Encoding: base64') !== false){
				preg_match('/Content-Transfer-Encoding: base64[\r\n](.*)/s',$email_segments[$i],$match_content);
                if(isset($match_content) && $match_content[1] != ""){
                    $email_content = base64_encode($crm_links.base64_decode($match_content[1]));
                }
                $email_segments[$i] = str_replace(trim($match_content[1]), "\r\n".$email_content."\r\n"."\r\n", $email_segments[$i]);

				//$email_segments[$i] = preg_replace('/Content-Transfer-Encoding: base64[\r\n](.*)/s','Content-Transfer-Encoding: base64'.PHP_EOL.PHP_EOL.$email_content,$email_segments[$i]);	
			}elseif(strpos($email_segments[$i],'Content-Type: text/plain;') !== false && strpos($email_segments[$i], 'charset=us-ascii') !== false && strpos($email_segments[$i], "Content-Transfer-Encoding: 7bit") !== false){
				$email_segments[$i] = str_replace('Content-Transfer-Encoding: 7bit', 'Content-Transfer-Encoding: 7bit'.PHP_EOL.PHP_EOL.PHP_EOL.$crm_links, $email_segments[$i]);
			}
			

			// Do nothing ?
		}

		if (strpos($email_segments[$i], "Content-Type: text/html;") !== false)
		{
			preg_match("/CRM Links:(.+?)End Links/s", $email_segments[$i], $link_matches);
			if (isset($link_matches[0])&& $link_matches[0]!= ""){
				$email_segments[$i] = str_replace($link_matches[0], "", $email_segments[$i]);
			}

			if(count($lookup_result)){
				//$email_segments[$i]
				$crm_links = "";
				$crm_links .= 'CRM Links: '.PHP_EOL;
				$db = DBManagerFactory::getInstance();
				$quotes = array();
				$invoices = array();
				foreach($lookup_result as $res){
					if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
						$crm_links .= '<a href=3D"https://suitecrm.pure-electric.com.au/index.php?module=3D'.$res['module'].'&action=3DEditView&record=3D'.$res['id'].'">['.preg_replace('/(?:s)$/', '',$res['module']).']</a>'.PHP_EOL;
						
					} else {
						$crm_links .= '<a href="https://suitecrm.pure-electric.com.au/index.php?module='.$res['module'].'&action=EditView&record='.$res['id'].'">['.preg_replace('/(?:s)$/', '',$res['module']).']</a>'.PHP_EOL;
					}

					if($res['module'] == "Leads"){
						$lead = new Lead();
						$lead->retrieve($res['id']);

						if(isset($lead->email1) && $lead->email1!= ""){
							if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
									$crm_links .= '<a href=3D"https://mail.google.com/#search/'.$lead->email1.'">GSearch</a>'.PHP_EOL;
									$crm_links .= '<a href=3D"http://message.pure-electric.com.au/search_message.php?crm_ref=3D'.$lead->id.'">SMS link</a>'.PHP_EOL;

							} else {
									$crm_links .= '<a href="https://mail.google.com/#search/'.$lead->email1.'">GSearch</a>'.PHP_EOL;
									$crm_links .= '<a href="http://message.pure-electric.com.au/search_message.php?crm_ref='.$lead->id.'">SMS link</a>'.PHP_EOL;

							}
						}

						
						// if(isset($lead->solargain_quote_number_c) && $lead->solargain_quote_number_c != ""){

						// 		if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
						// 				$crm_links .= '<a href=3D"https://crm.solargain.com.au/quote/edit/'.$lead->solargain_quote_number_c.'">[SQ'.$lead->solargain_quote_number_c.']</a>'.PHP_EOL;
										
						// 		} else {
						// 				$crm_links .= '<a href="https://crm.solargain.com.au/quote/edit/'.$lead->solargain_quote_number_c.'">[SQ'.$lead->solargain_quote_number_c.']</a>'.PHP_EOL;
						// 		}
						// } else if(isset($lead->solargain_lead_number_c) && $lead->solargain_lead_number_c != ""){
						// 	if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
						// 			$crm_links .= '<a href=3D"https://crm.solargain.com.au/lead/edit/'.
						// 			$lead->solargain_lead_number_c.'">[SL'.$lead->solargain_lead_number_c.']</a>'.PHP_EOL;
									
						// 	} else {
						// 			$crm_links .= '<a href="https://crm.solargain.com.au/lead/edit/'.$lead->solargain_lead_number_c.'">[SL'.$lead->solargain_lead_number_c.']</a>'.PHP_EOL;
						// 	}
							
						// }

						//Change logic get number quote solar from lead to quote
							//quote Suitecrm solar normal
							$bean_quote = new AOS_Quotes();
							$bean_quote->retrieve( $lead->create_solar_quote_num_c);
							if($bean_quote->id != '') {
								$number_lead = $bean_quote->solargain_lead_number_c;
								if(isset($bean_quote->solargain_quote_number_c) && $bean_quote->solargain_quote_number_c != ""){

									if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
										$crm_links .= '<a href=3D"https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_quote_number_c.'">[SQ'.$bean_quote->solargain_quote_number_c.']</a>'.PHP_EOL;
												
									} else {
										$crm_links .= '<a href="https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_quote_number_c.'">[SQ'.$bean_quote->solargain_quote_number_c.']</a>'.PHP_EOL;
									}
								} else if(isset($bean_quote->solargain_lead_number_c) && $bean_quote->solargain_lead_number_c != ""){
									if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
										$crm_links .= '<a href=3D"https://crm.solargain.com.au/lead/edit/'.
										$bean_quote->solargain_lead_number_c.'">[SL'.$bean_quote->solargain_lead_number_c.']</a>'.PHP_EOL;
											
									} else {
										$crm_links .= '<a href="https://crm.solargain.com.au/lead/edit/'.$bean_quote->solargain_lead_number_c.'">[SL'.$bean_quote->solargain_lead_number_c.']</a>'.PHP_EOL;
									}
								
								} else if(isset($bean_quote->solargain_tesla_quote_number_c) && $bean_quote->solargain_tesla_quote_number_c != ""){
									if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
										$crm_links .= '<a href=3D"https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_tesla_quote_number_c.'">[SQ'.$bean_quote->solargain_tesla_quote_number_c.']</a>'.PHP_EOL;
										
									} else {
										$crm_links .= '<a href="https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_tesla_quote_number_c.'">[SQ'.$bean_quote->solargain_tesla_quote_number_c.']</a>'.PHP_EOL;
									}
								
								}
							} 


							//quote Suitecrm solar tesla 
							$bean_quote = new AOS_Quotes();
							$bean_quote->retrieve( $lead->create_tesla_quote_num_c);
							if($bean_quote->id != '') {
								$number_lead = $bean_quote->solargain_lead_number_c;
								if(isset($bean_quote->solargain_quote_number_c) && $bean_quote->solargain_quote_number_c != ""){

									if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
										$crm_links .= '<a href=3D"https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_quote_number_c.'">[SQ'.$bean_quote->solargain_quote_number_c.']</a>'.PHP_EOL;
												
									} else {
										$crm_links .= '<a href="https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_quote_number_c.'">[SQ'.$bean_quote->solargain_quote_number_c.']</a>'.PHP_EOL;
									}
								} else if(isset($bean_quote->solargain_lead_number_c) && $bean_quote->solargain_lead_number_c != "" && $number_lead != $bean_quote->solargain_lead_number_c){
									if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
										$crm_links .= '<a href=3D"https://crm.solargain.com.au/lead/edit/'.
										$bean_quote->solargain_lead_number_c.'">[SL'.$bean_quote->solargain_lead_number_c.']</a>'.PHP_EOL;
											
									} else {
										$crm_links .= '<a href="https://crm.solargain.com.au/lead/edit/'.$bean_quote->solargain_lead_number_c.'">[SL'.$bean_quote->solargain_lead_number_c.']</a>'.PHP_EOL;
									}
								
								} else if(isset($bean_quote->solargain_tesla_quote_number_c) && $bean_quote->solargain_tesla_quote_number_c != ""){
									if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
										$crm_links .= '<a href=3D"https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_tesla_quote_number_c.'">[SQ'.$bean_quote->solargain_tesla_quote_number_c.']</a>'.PHP_EOL;
										
									} else {
										$crm_links .= '<a href="https://crm.solargain.com.au/quote/edit/'.$bean_quote->solargain_tesla_quote_number_c.'">[SQ'.$bean_quote->solargain_tesla_quote_number_c.']</a>'.PHP_EOL;
									}
								
								}
							} 
						//End Change logic get number quote solar from lead to quote
						
						if(isset($lead->phone_work) && $lead->phone_work != ""){
							if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
								$crm_links .= '<span>M :</span><a href=3D"http://message.pure-electric.com.au/#'.preg_replace("/^0/","61", preg_replace("/\D/", "", $lead->phone_work)).'">'.$lead->phone_work.'</a>'.PHP_EOL;
							}else {
								$crm_links .= '<span>M :</span><a href="http://message.pure-electric.com.au/#'.preg_replace("/^0/","61", preg_replace("/\D/", "", $lead->phone_work)).'">'.$lead->phone_work.'</a>'.PHP_EOL;
							}
						} else if(isset($lead->phone_mobile) && $lead->phone_mobile != ""){
							if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
								$crm_links .= '<span>M :</span><a href=3D"http://message.pure-electric.com.au/#'.preg_replace("/^0/","61", preg_replace("/\D/", "", $lead->phone_mobile)).'">'.$lead->phone_mobile.'</a>'.PHP_EOL;
							}else {
								$crm_links .= '<span>M :</span><a href="http://message.pure-electric.com.au/#'.preg_replace("/^0/","61", preg_replace("/\D/", "", $lead->phone_mobile)).'">'.$lead->phone_mobile.'</a>'.PHP_EOL;
							}
						}
						
						if(isset($lead->primary_address_postalcode) && $lead->primary_address_postalcode != ""){
								$crm_links .= " ".$lead->primary_address_street. " ".
														$lead->primary_address_city. " ".
														$lead->primary_address_state. " ".
														$lead->primary_address_postalcode .PHP_EOL;
						}

						if(false) if(isset($lead->id) && $lead->id != ""){
							if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
								$crm_links .= 
								'<a href=3D"https://suitecrm.pure-electric.com.au/index.php?entryPoint=3DcustomCreateAcceptanceLink&lead_id=3D'.$lead->id.'">Acceptance Email</a>'.PHP_EOL;
								//thienpb code - add link forward acceptance email to sg sam
								$crm_links .= '<a href=3D"https://suitecrm.pure-electric.com.au/index.php?entryPoint=3DcustomCreateForwardAcceptanceLink&lead_id=3D'.$lead->id.'">Forward Acceptance Email</a>'.PHP_EOL;
							} else {
								$crm_links .= 
								'<a href="https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateAcceptanceLink&lead_id='.$lead->id.'">Acceptance Email</a>'.PHP_EOL;
								//thienpb code - add link forward acceptance email to sg sam
								$crm_links .= 
								'<a href="https://suitecrm.pure-electric.com.au/index.php?entryPoint=customCreateForwardAcceptanceLink&lead_id='.$lead->id.'">Forward Acceptance Email</a>'.PHP_EOL;
							}
						}
					}
					
					if($res['module'] == 'Accounts'){            
						$sql = "SELECT id FROM aos_quotes WHERE billing_account_id='".$res['id']."'";
						$ret = $db->query($sql);
						if($ret->num_rows >0){
							while($row = $db->fetchByAssoc($ret)){
								if(!in_array($row,$quotes)){
									$quotes[] = $row;
								}
							}
						}
						$sql = "SELECT id FROM aos_invoices WHERE billing_account_id='".$res['id']."'";
						$ret = $db->query($sql);
						if($ret->num_rows >0){
							while($row = $db->fetchByAssoc($ret)){
								if(!in_array($row,$invoices)){
									$invoices[] = $row;
								}
							}
						}
					}
					if($res['module'] == "Contacts"){
						$sql = "SELECT id FROM aos_quotes WHERE billing_contact_id = '".$res['id']."'";
						$ret = $db->query($sql);
						if($ret->num_rows >0){
							while($row = $db->fetchByAssoc($ret)){
								if(!in_array($row,$quotes)){
									$quotes[] = $row;
								}
							}
						}
						$sql = "SELECT id FROM aos_invoices WHERE billing_contact_id = '".$res['id']."'";
						$ret = $db->query($sql);
						if($ret->num_rows >0){
							while($row = $db->fetchByAssoc($ret)){
								if(!in_array($row,$invoices)){
									$invoices[] = $row;
								}
							}
						}
					}
					
					//$crm_links .= '<a href="https://suitecrm.pure-electric.com.au/index.php?module='.$res['module'].'&action=EditView&record='.$res['id'].'">['.$res['module'].']</a>'.PHP_EOL;
				}
				if(count($quotes) >0){
					$group_name ='';
					foreach ($quotes as $res_qt){
						$quote = new AOS_Quotes();
						$quote->retrieve($res_qt['id']);
						$sql = "SELECT name FROM aos_line_item_groups WHERE parent_id = '".$quote->id."' AND parent_type = 'AOS_Quotes'" ;
						$ret = $db->query($sql);
						$row = $db->fetchByAssoc($ret);
						if(strpos(strtolower($row['name']),'daikin') !== false){
							$group_name = ' DAIKIN';
						}else if(strpos(strtolower($row['name']),'sanden') !== false){
							$group_name = ' SANDEN';
						}else if(strpos(strtolower($row['name']),'methven') !== false){
							$group_name = ' METHVEN';
							if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
								$crm_links .= '<a href=3D"https://auspost.com.au/mypost-business/shipping-and-tracking/orders/ready">[Auspost Link]</a>'.PHP_EOL;
							}else{
								$crm_links .= '<a href="https://auspost.com.au/mypost-business/shipping-and-tracking/orders/ready">[Auspost Link]</a>'.PHP_EOL;
							}
						}else{
							$group_name ='';
						}
						//fix task matt -  add the word SOL after PEQ XXXX for solar ie PEQ XXXX SOL
						if($group_name ==''){
							if (isset($quote->solargain_quote_number_c) && $quote->solargain_quote_number_c !="") {
								$group_name =' SOL';
							}elseif (isset($quote->solargain_lead_number_c) && $quote->solargain_lead_number_c !="") {
								$group_name =' SOL';
							}elseif (isset($quote->solargain_tesla_quote_number_c) && $quote->solargain_tesla_quote_number_c !="") {
								$group_name =' SOL';
							}else{
								$group_name ='';
							}
						}

						if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
							$crm_links .= '<a href=3D"https://suitecrm.pure-electric.com.au/index.php?module=3DAOS_Quotes&action=3DEditView&record=3D'.$quote->id.'">[PEQ '.$quote->number.$group_name.']</a>'.PHP_EOL;
							//VUT-shortcut link SG
							if (isset($quote->solargain_quote_number_c) && $quote->solargain_quote_number_c !="") {
								$crm_links .= '<a href=3D"https://crm.solargain.com.au/quote/edit/'.$quote->solargain_quote_number_c.'">(SQ '.$quote->solargain_quote_number_c.')</a>'.PHP_EOL;
							}
							//fix task - matt remove link (SLxxxx)
							// if (isset($quote->solargain_lead_number_c) && $quote->solargain_lead_number_c !="") {
							// 	$crm_links .= '<a href=3D"https://crm.solargain.com.au/lead/edit/'.$quote->solargain_lead_number_c.'">(SL '.$quote->solargain_lead_number_c.')</a>'.PHP_EOL;
							// }
							if (isset($quote->solargain_tesla_quote_number_c) && $quote->solargain_tesla_quote_number_c !="") {
								$crm_links .= '<a href=3D"https://crm.solargain.com.au/lead/edit/'.$quote->solargain_tesla_quote_number_c.'">(SQ '.$quote->solargain_tesla_quote_number_c.')</a>'.PHP_EOL;
							}
							//VUT-shortcut link SG

						}else{
							$crm_links .= '<a href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&action=EditView&record='.$quote->id.'">[PEQ '.$quote->number.$group_name.']</a>'.PHP_EOL;
							//VUT-shortcut link SG
							if (isset($quote->solargain_quote_number_c) && $quote->solargain_quote_number_c !="") {
								$crm_links .= '<a href="https://crm.solargain.com.au/quote/edit/'.$quote->solargain_quote_number_c.'">(SQ '.$quote->solargain_quote_number_c.')</a>'.PHP_EOL;
							}
							//fix task - matt remove link (SLxxxx)
							// if (isset($quote->solargain_lead_number_c) && $quote->solargain_lead_number_c !="") {
							// 	$crm_links .= '<a href="https://crm.solargain.com.au/lead/edit/'.$quote->solargain_lead_number_c.'">(SL '.$quote->solargain_lead_number_c.')</a>'.PHP_EOL;
							// }
							if (isset($quote->solargain_tesla_quote_number_c) && $quote->solargain_tesla_quote_number_c !="") {
								$crm_links .= '<a href="https://crm.solargain.com.au/lead/edit/'.$quote->solargain_tesla_quote_number_c.'">(SQ '.$quote->solargain_tesla_quote_number_c.')</a>'.PHP_EOL;
							}
							//VUT-shortcut link SG
						}
	
					}
				}
				if(count($invoices) >0){
					$group_name ='';
					foreach ($invoices as $res_inv){
						$invoice_new = new AOS_Invoices();
						$invoice_new->retrieve($res_inv['id']);
						if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
							$crm_links .= '<a href=3D"https://suitecrm.pure-electric.com.au/index.php?module=3DAOS_Invoices&action=3DEditView&record=3D'.$invoice_new->id.'">[PEINV '.$invoice_new->number.']</a>'.PHP_EOL;
						}else{
							$crm_links .= '<a href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$invoice_new->id.'">[PEINV '.$invoice_new->number.']</a>'.PHP_EOL;
						}
					}
				}
				$crm_links .= "End Links";
				//if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false){
					//$email_segments[$i] = str_replace("Content-Transfer-Encoding: quoted-printable".PHP_EOL.PHP_EOL, "Content-Transfer-Encoding: quoted-printable".PHP_EOL.PHP_EOL.$crm_links, $email_segments[$i]);
				//}
				//else {
				//$email_segments[$i] .= $crm_links;
				//}
				/*$html = str_get_html($email_segments[$i]);
				$first_div = $html->find("div", 0);
				$first_div->innertext = $crm_links.$first_div->innertext;
				$html->save();
				$email_segments[$i] = $html->outertext;
				$email_segments[$i] = str_replace('Content-Type: text/html; charset="UTF-8"', PHP_EOL.'Content-Type: text/html; charset="UTF-8"'.PHP_EOL.PHP_EOL, $email_segments[$i]);
				*/

				if(strpos($email_segments[$i], "Content-Type: text/html; charset=utf-8") !== false && strpos($email_segments[$i],'Content-Transfer-Encoding: base64') !== false){
                    preg_match('/Content-Transfer-Encoding: base64[\r\n](.*)/s',$email_segments[$i],$match_content);
	                if(isset($match_content) && $match_content[1] != ""){
	                    $email_content = base64_encode($crm_links.base64_decode($match_content[1]));
	                }
               	 	$email_segments[$i] = str_replace(trim($match_content[1]), "\r\n".$email_content."\r\n"."\r\n", $email_segments[$i]);
               	 	//('/Content-Transfer-Encoding: base64[\r\n](.*)/s','Content-Transfer-Encoding: base64'.PHP_EOL.PHP_EOL.$email_content, 
               	 	//	$email_segments[$i]);
                }
				
				preg_match('/<div[^>]*>.*?/', $email_segments[$i], $out);
				$first_div = "";
				if(isset($out[0])){
					$first_div  = $out[0];
				}
				$email_segments[$i]  = preg_replace("/<div[^>]*>.*?/", $first_div.$crm_links.'<br/>'.'<br/>', $email_segments[$i], 1);

				/*if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
					$email_segments[$i] = str_replace('Content-Transfer-Encoding: quoted-printable', 'Content-Transfer-Encoding: quoted-printable'.PHP_EOL.PHP_EOL.$crm_links, $email_segments[$i]);
				} else {
					$email_segments[$i] = str_replace('Content-Type: text/html; charset="UTF-8"', 'Content-Type: text/html; charset="UTF-8"'.PHP_EOL.PHP_EOL.$crm_links, $email_segments[$i]);
				}*/
			}
			

			if(isset($reference_no) && $reference_no != ""){

				// Query for get invoice link
				$db = DBManagerFactory::getInstance();
		
				$sql = "SELECT * FROM aos_invoices_cstm
						WHERE 1=1 
						AND (stc_aggregator_serial_c = '$reference_no' OR stc_aggregator_serial_2_c = '$reference_no')";
		
				$ret = $db->query($sql);
				
				$lookup_result = array();

				while ($row = $db->fetchByAssoc($ret)) {
					$lookup_result[] = $row;
				}

				if(count($lookup_result)== 0) return;
				$crm_links = "";
				$crm_links .= "CRM Links: ".PHP_EOL;
				if(count($lookup_result)){
					
					
					foreach($lookup_result as $res){
						if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
							// https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$res['id'].PHP_EOL;
							$crm_links .= 
							'<a href=3D"https://suitecrm.pure-electric.com.au/index.php?module=3DAOS_Invoices&action=3DEditView&record=3D'.$res['id_c'].'">[CRM Invoice]</a>'.PHP_EOL;
						} else {
							$crm_links .= 
							'<a href="https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record='.$res['id_c'].'">[CRM Invoice]</a>'.PHP_EOL;
						}
					}
					
				}
				if(strpos($email_segments[$i], "Content-Transfer-Encoding: quoted-printable") !== false) {
							// https://suitecrm.pure-electric.com.au/index.php?module=AOS_Invoices&action=EditView&record=".$res['id'].PHP_EOL;
							$crm_links .= 
							'<a href==3D"https://geocreation.com.au/assignments/'.$reference_no.'/edit">[GEO Creation]</a>'.PHP_EOL;
						} else {
							$crm_links .= 
							'<a href="https://geocreation.com.au/assignments/'.$reference_no.'/edit">[GEO Creation]</a>'.PHP_EOL;
						}
				$crm_links .= "End Links";
				//file_put_contents("email/".$file, file_get_contents("email/".$file).PHP_EOL.$crm_links);
				preg_match('/<div[^>]*>.*?/', $email_segments[$i], $out);
				$first_div = "";
				if(isset($out[0])){
					$first_div  = $out[0];
				}
				$email_segments[$i]  = preg_replace("/<div[^>]*>.*?/", $first_div.$crm_links.'<br/>'.'<br/>', $email_segments[$i], 1);
			}
			$email_segments[$i] .= PHP_EOL.PHP_EOL;
			
		}
	}
	file_put_contents("email/".$file, implode('--' . $matches[1], $email_segments));
}
