<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use SuiteCRM\Utility\SuiteValidator;

include_once 'include/Exceptions/SugarControllerException.php';

include_once __DIR__ . '/EmailsDataAddressCollector.php';
include_once __DIR__ . '/EmailsControllerActionGetFromFields.php';

class EmailsController extends SugarController
{
    const ERR_INVALID_INBOUND_EMAIL_TYPE = 100;
    const ERR_STORED_OUTBOUND_EMAIL_NOT_SET = 101;
    const ERR_STORED_OUTBOUND_EMAIL_ID_IS_INVALID = 102;
    const ERR_STORED_OUTBOUND_EMAIL_NOT_FOUND = 103;
    const ERR_REPLY_TO_ADDR_NOT_FOUND = 110;
    const ERR_REPLY_TO_FROMAT_INVALID_SPLITS = 111;
    const ERR_REPLY_TO_FROMAT_INVALID_NO_NAME = 112;
    const ERR_REPLY_TO_FROMAT_INVALID_NO_ADDR = 113;
    const ERR_REPLY_TO_FROMAT_INVALID_AS_FROM = 114;

    /**
     * @var Email $bean ;
     */
    public $bean;

    /**
     * @see EmailsController::composeBean()
     */
    const COMPOSE_BEAN_MODE_UNDEFINED = 0;

    /**
     * @see EmailsController::composeBean()
     */
    const COMPOSE_BEAN_MODE_REPLY_TO = 1;

    /**
     * @see EmailsController::composeBean()
     */
    const COMPOSE_BEAN_MODE_REPLY_TO_ALL = 2;

    /**
     * @see EmailsController::composeBean()
     */
    const COMPOSE_BEAN_MODE_FORWARD = 3;

    /**
     * @see EmailsController::composeBean()
     */
    const COMPOSE_BEAN_WITH_PDF_TEMPLATE = 4;

    protected static $doNotImportFields = array(
        'action',
        'type',
        'send',
        'record',
        'from_addr_name',
        'reply_to_addr',
        'to_addrs_names',
        'cc_addrs_names',
        'bcc_addrs_names',
        'imap_keywords',
        'raw_source',
        'description',
        'description_html',
        'date_sent_received',
        'message_id',
        'name',
        'status',
        'reply_to_status',
        'mailbox_id',
        'created_by_link',
        'modified_user_link',
        'assigned_user_link',
        'assigned_user_link',
        'uid',
        'msgno',
        'folder',
        'folder_type',
        'inbound_email_record',
        'is_imported',
        'has_attachment',
        'id',
    );

    /**
     * @see EmailsViewList
     */
    public function action_index()
    {
        $this->view = 'list';
    }

    /**
     * @see EmailsViewDetaildraft
     */
    public function action_DetailDraftView()
    {
        $this->view = 'detaildraft';
    }

    /**
     * @see EmailsViewCompose
     */
    public function action_ComposeView()
    {
        // BinhNT Code here
        $this->bean->save();
        if (isset($_REQUEST['email_type'])) {
            if($_REQUEST['email_type'] == "Send_Customer_Install_date"){
                $record_id = trim($_REQUEST['record_id']);
                $sg_order_number = trim($_GET['order_number']);
                if($sg_order_number == '') return;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://crm.solargain.com.au/apiv2/orders/$sg_order_number");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
                $headers = array();
                $headers[] = "Pragma: no-cache";
                $headers[] = "Accept-Encoding: gzip, deflate, br";
                $headers[] = "Accept-Language: en-US,en;q=0.9";
                $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36";
                $headers[] = "Accept: application/json, text/plain, */*";
                $headers[] = "Referer: https://crm.solargain.com.au/order/edit/30962";
                $headers[] = "Authorization: Basic bWF0dGhldy53cmlnaHQ6TVdAcHVyZTczMw==";
                $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
                $headers[] = "Connection: keep-alive";
                $headers[] = "Cache-Control: no-cache";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                curl_close ($ch);
                $json_result = json_decode($result);

                //logic for install date
                if(isset($json_result->InstallDate)){
                    $date = date_create($json_result->InstallDate);
                    $installdate = date_format($date,"d/m/Y");
                }else {
                    $installdate =$json_result->Quote->ProposedInstallDate->Date;
                }

                $quote_number = $json_result->Quote->ID;
                
                $db = DBManagerFactory::getInstance();
                $sql = "SELECT id_c FROM `leads_cstm` WHERE solargain_tesla_quote_number_c='$quote_number' OR 	solargain_quote_number_c='$quote_number' ";
                $ret = $db->query($sql);

                while ($row = $db->fetchByAssoc($ret)) {
                   $lead_id = $row['id_c'];
                }
                $macro_nv = array();
                $focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $lead_id);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '88c54ff3-a7a3-02a6-4b87-5c873c49eba2'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                // parse value
                $bean_invoice = BeanFactory::getBean('AOS_Invoices', $record_id);
                if(!$bean_invoice->id) return;
                $description = str_replace("\$aos_invoices_installation_date_c",$bean_invoice->installation_date_c , $description);
                $description_html = str_replace("\$aos_invoices_installation_date_c",$bean_invoice->installation_date_c , $description_html);
                $description = str_replace("\$contact_name",$bean_invoice->billing_contact , $description);
                $description_html = str_replace("\$contact_name",$bean_invoice->billing_contact , $description_html);
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = '88c54ff3-a7a3-02a6-4b87-5c873c49eba2';
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                 //get email from contact
                 $contact_bean = new Contact;
                 $contact_bean->retrieve($bean_invoice->billing_contact_id);

                $this->bean->to_addrs_names = $contact_bean->email1;
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
            }

            //Dung code --- Email Survey Form 
            if($_REQUEST['email_type'] == 'survey_form_email'){
                $record_id = trim($_REQUEST['record_id']);
                $macro_nv = array();
                $focusName = "AOS_Invoices";
                $focus = BeanFactory::getBean($focusName, $record_id);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */
                //$emailTemplateID =  '548719ca-7bd8-be58-dd9f-5de5d7dad315'; 
                $strtotime_picked_up_check = strtotime(str_replace('/','-',explode(" ",$focus->picked_up_date_c)[0]))+14*60*60*24;
                if($strtotime_picked_up_check <= time()) {
                    $emailTemplateID = 'f0779388-37db-cbce-d674-5e8e858c3149';
                }else{
                    $emailTemplateID = '787fbdd0-2872-8ee7-4232-5de613f50ced';
                }
                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    $emailTemplateID
                );
                //get total price stc and veec
                $db = DBManagerFactory::getInstance();
                $array_part_numbers_stc_veec = ['STC Rebate Certificate','VEEC Rebate Certificate'];
                $string_array_part_number = "'" .implode("','",$array_part_numbers_stc_veec) . "'";

                
                $sql = "SELECT * FROM aos_products_quotes
                WHERE parent_type = 'AOS_Invoices' AND parent_id = '" . $record_id . "'
                AND part_number IN ($string_array_part_number) AND deleted = 0 
                ";
                $total_stc_veec = 0;
                $check_exist_part_number = [];
                $res = $db->query($sql);
                while ($row = $db->fetchByAssoc($res)) {
                    $check_exist_part_number[] = $row['part_number'];
                    $total_stc_veec +=$row['product_total_price'];
                }
                $params = array(
                    'currency_symbol' => false
                );
                $total_price = '$'.currency_format_number((0-$total_stc_veec),$params);
                //get email from contact
                $contact_bean = new Contact;
                $contact_bean->retrieve($focus->billing_contact_id);

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $invoice_link = '<a target="_blank" href="http://pure-electric.com.au/sanden-stc-form?invoice_id='.$record_id .'&invoice_number='.$focus->number.'" >Invoice# '.$focus->number.'</a>';
                $time_picked_up = date('D d/m/Y',(strtotime(str_replace('/','-',explode(" ",$focus->picked_up_date_c)[0]))+14*60*60*24));
                //parse value
                $description = str_replace("Invoice-Link",$invoice_link , $description);
                $description_html = str_replace("Invoice-Link",$invoice_link , $description_html);
                $description = str_replace("\$contact_first_name",$contact_bean->first_name , $description);
                $description_html = str_replace("\$contact_first_name",$contact_bean->first_name , $description_html);
                $description = str_replace("\$Total-STC-VEEC",$total_price , $description);
                $description_html = str_replace("\$Total-STC-VEEC",$total_price , $description_html);

                $name = str_replace("\$contact_first_name",$contact_bean->first_name , $name);
                $name = str_replace("\$aos_invoices_number ",$focus->number , $name);
                $name = str_replace("\$aos_invoices_name",$focus->name , $name);
                if( $emailTemplateID == '787fbdd0-2872-8ee7-4232-5de613f50ced') {
                    $name .= " only- PAPERWORK DUE ".$time_picked_up;
                    $description = str_replace("14 calendar days","14 calendar days - ". $time_picked_up.' -' , $description);
                    $description_html = str_replace("14 calendar days","14 calendar days - ". $time_picked_up.' -' , $description_html);
                }
                // parse Note-Special
                if(in_array('STC Rebate Certificate', $check_exist_part_number) 
                && in_array('VEEC Rebate Certificate', $check_exist_part_number) && $focus->install_address_state_c == 'VIC') {
                    $Note_Special_String = '<p style="font-family: Times New Roman; font-size: medium;" data-mce-style="font-family: Times New Roman; font-size: medium;">
                    For the Email template sent out the customer asking for filling out the Sanden STC form, could you please add this one to the email "For VEEC acceptance,
                     the Plumbing Compliance Certificate and the Electrical Certificate should have the words "disconnected and decommissioned existing electric storage hot water system"</p>';  
                }else{
                    $Note_Special_String = '';
                }
                $description = str_replace("\$Note_Special",$Note_Special_String  , $description);
                $description_html = str_replace("\$Note_Special",$Note_Special_String  , $description_html);
                
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                //get email from contact

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    'b51414de-3298-ed66-bbba-5e49c7fd52de' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact_bean->first_name, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact_bean->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client =  $phone_number; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }

            //VUT-S-Invoice - Button Sanden Health Check
            if($_REQUEST['email_type'] == 'sanden_health_check'){
                $record_id = trim($_REQUEST['record_id']);
                $macro_nv = array();
                $focusName = "AOS_Invoices";
                $focus = BeanFactory::getBean($focusName, $record_id);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplateID =  '76c97f88-ae32-36ad-6186-5ffbc3420c44'; //suitecrm server
                // $emailTemplateID = '39acf291-0c24-1cac-41d3-5ffbf43749d3'; //test devel

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    $emailTemplateID
                );
                //get email from contact
                $contact_bean = new Contact;
                $contact_bean->retrieve($focus->billing_contact_id);

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;

                $description = str_replace("\$contact_first_name",$contact_bean->first_name , $description);
                $description_html = str_replace("\$contact_first_name",$contact_bean->first_name , $description_html);
                $name = str_replace("\$contact_name", $contact_bean->first_name.' '.$contact_bean->last_name , $name);
                $name = str_replace("\$contact_primary_address_city", $contact_bean->primary_address_city , $name);
                $name = str_replace("\$contact_primary_address_state", $contact_bean->primary_address_state , $name);

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                //get email from contact

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //start - code render sms_template  
                // global $current_user;
                // $smsTemplateID = '4efab103-2d92-a39d-bcdd-5eb2030047bd'; //suitecrm server
                // // $smsTemplateID = '92b32931-44c6-7dc4-3358-5eb2235ba028'; //test local VUT
                // $smsTemplate = BeanFactory::getBean(
                //     'pe_smstemplate',
                //     $smsTemplateID 
                // );
                // $body =  $smsTemplate->body_c;
                // $body = str_replace("\$first_name", $contact_bean->first_name, $body);
                // if( isset($_REQUEST['email_plumber']) && $_REQUEST['email_plumber'] == "plumber"){
                //     $body = str_replace("\$product_type", $product, $body);
                // }
                // $smsTemplate->body_c = $body;
                // $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                // $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                // $this->bean->number_receive_sms = "matthew_paul_client";
                // $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact_bean->phone_mobile));
                // $phone_number = preg_replace("/^61/", "+61", $phone_number);
                // $this->bean->number_client =  $phone_number; 
                // $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //VUT-E-Invoice - BUtton Sanden Health Check

            //VUT-S-Invoice-Detailview- Button 'Delivery Coming'
                if($_REQUEST['email_type'] == 'delivery_coming'){
                    if( isset($_REQUEST['email_plumber']) && $_REQUEST['email_plumber'] == "plumber"){
                        $emailTemplateID =  '174c7160-5938-1bb4-fd41-5eb205b50ce7';  //suitecrm server
    
                        $emailTemplate = BeanFactory::getBean(
                            'EmailTemplates',
                            $emailTemplateID
                        );
                        $purchase_bean = new PO_purchase_order();
                        $purchase_bean->retrieve(trim($_REQUEST['record_id']));

                        $contact_bean = new Contact();
                        $contact_bean->retrieve($purchase_bean->contact_id_c);
    
                        $name = $emailTemplate->subject;
                        $description_html = $emailTemplate->body_html;
                        $description = $emailTemplate->body;
                        //parse value
                        $product = ucwords(str_replace("_", " " , $purchase_bean->po_type_c));
                        $link_upload = '<a href="https://pure-electric.com.au/upload_file_delivery_purchase?purchase_id='.$_REQUEST['record_id'].'" target="_blank">Please click this link to upload your photo.</a>';

                        $description = str_replace("\$contact_first_name",$contact_bean->first_name , $description);
                        $description = str_replace("\$aos_invoices_delivery_date_time_c",$purchase_bean->delivery_date_c, $description);
                        $description = str_replace("\$aos_invoices_quote_type_c",  $product , $description);
                        $description = str_replace("\$aos_link_upload_delivery",  $link_upload , $description);

                        $description_html = str_replace("\$contact_first_name",$contact_bean->first_name , $description_html);
                        $description_html = str_replace("\$aos_invoices_delivery_date_time_c", $purchase_bean->delivery_date_c , $description_html);
                        $description_html = str_replace("\$aos_invoices_quote_type_c",  $product , $description_html);
                        $description_html = str_replace("\$aos_link_upload_delivery",  $link_upload , $description_html);
                
                        //Change subject Email Delivery
                        $deleted_name = preg_replace('/([\d]{1,2} [\w]{3} [\d]{0,4} [\d].*)/', '', $purchase_bean->name);
                        $name = str_replace("\$aos_invoices_name", $deleted_name, $name);
                        // $name = str_replace("\$aos_invoices_name",  $purchase_bean->name , $name);
                        $name = str_replace("\$aos_invoices_delivery_date_time_c",  $purchase_bean->delivery_date_c , $name);

                    }else {
                        $record_id = trim($_REQUEST['record_id']);
                        $macro_nv = array();
                        $focusName = "AOS_Invoices";
                        $focus = BeanFactory::getBean($focusName, $record_id);

                        if(!$focus->id) return;
                        /**
                         * @var EmailTemplate $emailTemplate
                         */

                        $emailTemplateID =  '174c7160-5938-1bb4-fd41-5eb205b50ce7'; //suitecrm server
                        // $emailTemplateID = '3c000080-df93-adf8-d948-5eb222aa2a88'; //test local VUT

                        $emailTemplate = BeanFactory::getBean(
                            'EmailTemplates',
                            $emailTemplateID
                        );
                        //get email from contact
                        $contact_bean = new Contact;
                        $contact_bean->retrieve($focus->billing_contact_id);

                        $name = $emailTemplate->subject;
                        $description_html = $emailTemplate->body_html;
                        $description = $emailTemplate->body;
                        //parse value
                        $link_upload = '<a href="https://pure-electric.com.au/upload_file_delivery?invoice_id='.$focus->id.'" target="_blank">Please click this link to upload your photo.</a>';

                        $description = str_replace("\$contact_first_name",$contact_bean->first_name , $description);
                        $description_html = str_replace("\$contact_first_name",$contact_bean->first_name , $description_html);
                        $description_html = str_replace("\$aos_invoices_delivery_date_c", $focus->delivery_date_time_c , $description_html);
                        $description = str_replace("\$aos_invoices_delivery_date_c", $focus->delivery_date_time_c , $description);
                        $name = str_replace("\$aos_invoices_delivery_date_c", $focus->delivery_date_time_c , $name);
                        $description =  str_replace("\$aos_link_upload_delivery",  $link_upload , $description);
                        $description_html =  str_replace("\$aos_link_upload_delivery",  $link_upload , $description_html);

                    }            
                    $templateData = $emailTemplate->parse_email_template(
                        array(
                            'subject' => $name,
                            'body_html' => $description_html,
                            'body' => $description,
                        ),
                        $focusName,
                        $focus,
                        $macro_nv
                    );
                    $this->bean->emails_email_templates_idb = $emailTemplateID ;
                    $attachmentBeans = $emailTemplate->getAttachments();

                    if($attachmentBeans) {
                        $this->bean->status = "draft";
                        $this->bean->save();
                        foreach($attachmentBeans as $attachmentBean) {

                            $noteTemplate = clone $attachmentBean;
                            $noteTemplate->id = create_guid();
                            $noteTemplate->new_with_id = true; 
                            $noteTemplate->parent_id = $this->bean->id;
                            $noteTemplate->parent_type = 'Emails';
                            $noteFile = new UploadFile();
                            $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                            $noteTemplate->save();
                            $this->bean->attachNote($noteTemplate);
                        }
                    }
                    //get email from contact
                    $this->bean->name = trim(preg_replace('/\s+/',' ', $templateData['subject']));
                    // $this->bean->name = $templateData['subject'];
                    $this->bean->description_html = $templateData['body_html'];
                    $this->bean->description = $templateData['body_html'];
                    //start - code render sms_template  
                    global $current_user;
                    $smsTemplateID = '4efab103-2d92-a39d-bcdd-5eb2030047bd'; //suitecrm server
                    // $smsTemplateID = '92b32931-44c6-7dc4-3358-5eb2235ba028'; //test local VUT
                    $smsTemplate = BeanFactory::getBean(
                        'pe_smstemplate',
                        $smsTemplateID 
                    );
                    $body =  $smsTemplate->body_c;
                    $body = str_replace("\$first_name", $contact_bean->first_name, $body);
                    if( isset($_REQUEST['email_plumber']) && $_REQUEST['email_plumber'] == "plumber"){
                        $body = str_replace("\$product_type", $product, $body);
                    }
                    $smsTemplate->body_c = $body;
                    $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                    $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                    $this->bean->number_receive_sms = "matthew_paul_client";
                    $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact_bean->phone_mobile));
                    $phone_number = preg_replace("/^61/", "+61", $phone_number);
                    $this->bean->number_client =  $phone_number; 
                    $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                    //end - code render sms_template
                }
            //TUAN FREIGHT COMPANY
            if($_REQUEST['email_type'] == 'freight_company'){
                $emailTemplateID =  '1c9c2ce9-7d86-7345-73b8-5f86b95a2b7b';
                // $emailTemplateID = '66ca469a-9eca-37f3-bd62-5f8674c1ddf8'; //local

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    $emailTemplateID
                );
                if ($_REQUEST['email_module'] == 'pe_warehouse_log') {
                    $pe_whl_bean = new pe_warehouse_log();
                    $pe_whl_bean->retrieve(trim($_REQUEST['record_id']));
                    $connote = $pe_whl_bean->connote;
                    $db = DBManagerFactory::getInstance();
                    if ($pe_whl_bean->warehouse_order_number !='') { 
                        $sql = "SELECT po_purchase_order.id
                                FROM po_purchase_order
                                LEFT JOIN po_purchase_order_cstm ON po_purchase_order.id = po_purchase_order_cstm.id_c
                                WHERE  po_purchase_order_cstm.supplier_order_number_c = '$pe_whl_bean->warehouse_order_number' AND po_purchase_order.deleted = 0";
                        $ret = $db->query($sql);
                        while ($row = $db->fetchByAssoc($ret)) {
                            $purchase_bean = new PO_purchase_order();
                            $purchase_bean->retrieve(trim($row['id']));
                        }
                    } else {
                        $pe_whl_bean->load_relationships('PO_purchase_order');                    
                        $purchase_bean = $pe_whl_bean->get_linked_beans('po_purchase_order_pe_warehouse_log_1','PO_purchase_order')[0];
                    }
                } else {
                    $purchase_bean = new PO_purchase_order();
                    $purchase_bean->retrieve(trim($_REQUEST['record_id']));
                    $db = DBManagerFactory::getInstance();
                    $sql = "SELECT `connote` FROM `pe_warehouse_log` WHERE `name` LIKE '%$purchase_bean->supplier_order_number_c%'";
                    $ret = $db->query($sql);
                    while ($row = $db->fetchByAssoc($ret)) {
                        $connote = $row['connote'];
                    }
                }

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                //parse value
                $name = str_replace("\$aos_connote", $connote .' - '.$purchase_bean->shipping_address_city , $name);
                $description = str_replace("\$aos_cope", "Cope ".$purchase_bean->shipping_address_state , $description);
                $description = str_replace("\$aos_connote", $connote .' - '.$purchase_bean->shipping_address_city , $description);

                $description_html = str_replace("\$aos_cope", "Cope ".$purchase_bean->shipping_address_state , $description_html);
                $description_html = str_replace("\$aos_connote", $connote .' - '.$purchase_bean->shipping_address_city , $description_html);
            
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
            $this->bean->emails_email_templates_idb = $emailTemplateID ;
            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {

                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; 
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';
                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }
                    //get phone number
            $postcode = $purchase_bean->shipping_address_postalcode;
            switch ($purchase_bean->local_freight_company_c) {
                case "cope_act":
                    $phone = "02 6295 1816";
                    $email_address = "actops@cope.com.au";
                break;
                case "cope_nsw":
                    $phone = "02 8787 8888";
                    $email_address = "nsw@cope.com.au";
                break;
                case "cope_qld":
                    $phone = "07 3441 4100";
                    $email_address = "qldcust@cope.com.au";
                break;
                case "cope_sa":
                    $phone = "08 8249 2222";
                    $email_address = "sa@cope.com.au";
                break;
                case "cope_vic":
                    $phone = "03 9235 0400";
                    $email_address = "vic@cope.com.au";
                break;
                case "cope_wa":
                    $phone = "08 9251 5333";
                    $email_address = "wa@cope.com.au";
                break;
            }
                if ($_REQUEST['email_module'] == 'pe_warehouse_log') {
                    $this->bean->to_addrs_names = "Cope ".$purchase_bean->shipping_address_state." <".$email_address.">";
                }
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //start - code render sms_template  
                global $current_user;
                $smsTemplateID = '606bbec6-7684-6bcc-fabd-5f86c2ea0ebd'; 
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    $smsTemplateID 
                );
                $body =  $smsTemplate->body_c;

                $body = str_replace("\$aos_cope","Cope ".$purchase_bean->shipping_address_state , $body);
                $body = str_replace("\$aos_connote",$connote, $body);

                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->number_receive_sms = "matthew_paul_client";
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client =  $phone_number; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
             //TUAN CALENDAR EMAIL
            if($_REQUEST['email_type'] == 'clients_calendar' || $_REQUEST['email_type'] == 'plumber_calendar' || $_REQUEST['email_type'] == 'electrician_calendar'){
                $invoice = new AOS_Invoices();
                $invoice->retrieve($_REQUEST['record_id']);
                if( $_REQUEST['email_type'] == 'clients_calendar' ){
                    $emailTemplateID =  '3d130783-62df-4eaa-c1c5-5dee208d3e02';
                    $contact = new Contact;
                    $contact->retrieve($invoice->contact_id3_c);
                    $link_calendar = "https://calendar.pure-electric.com.au/#/installation-booking/".$invoice->installation_calendar_id_c."/client";
                    if ($invoice->quote_type_c ==  "quote_type_sanden" ) {
                        $product = "Sanden";
                    } else if ($invoice->quote_type_c == "quote_type_daikin" || $invoice->quote_type_c == "quote_type_nexura") {
                        $product = "Daikin";
                    }

                }elseif( $_REQUEST['email_type'] == 'plumber_calendar' ){
                    $emailTemplateID =  '3722ae7c-d8b7-e03f-559c-5df843678e41';
                    $contact = new Contact;
                    $contact->retrieve($invoice->contact_id4_c);
                    $link_calendar = "https://calendar.pure-electric.com.au/#/installation-booking/".$invoice->installation_calendar_id_c."/plumber/".$invoice->account_id1_c;
                }else { //electrician
                    $emailTemplateID =  'dc0416cd-6867-5508-3d20-5df843ba69dc';
                    $contact = new Contact;
                    $contact->retrieve($invoice->contact_id_c);
                    $link_calendar = "https://calendar.pure-electric.com.au/#/installation-booking/".$invoice->installation_calendar_id_c."/electrician/".$invoice->account_id_c;
                }

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    $emailTemplateID
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                //parse value
                $name = str_replace("\$aos_invoices_name", $invoice->name , $name);
                if( $_REQUEST['email_type'] == 'clients_calendar' ){
                    $description = str_replace("\$contact_first_name",$contact->first_name , $description);
                    $description_html = str_replace("\$contact_first_name", $contact->first_name , $description_html);
                    $description = str_replace("\$aos_invoices_quote_type_c",$product , $description);
                    $description_html = str_replace("\$aos_invoices_quote_type_c", $product , $description_html);
                    //SMS 
                    $smsTemplate_Client = BeanFactory::getBean(
                        'pe_smstemplate',
                        'ab4b8f77-4bb5-a00d-9c55-5f9b4ad921b6' 
                    );
                    $AccountClient = new Account();
                    $AccountClient = $AccountClient->retrieve($invoice->billing_account_id);
                    $body_sms =  $smsTemplate_Client->body_c;
                    $body_sms = str_replace('$installation_calendar_url',$installation_calendar_url,str_replace("\$first_name", explode(" ", $AccountClient->name,2)[0], $body_sms));
                    $body_sms = str_replace('$aos_invoices_quote_type_c', $quote_type,$body_sms);
                    $smsTemplate_Client->body_c = $body_sms;
                    $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $AccountClient->phone_mobile));
                    $phone_number = preg_replace("/^61/", "+61", $phone_number);
                    $this->bean->emails_pe_smstemplate_idb = $smsTemplate_Client->id;
                    $this->bean->emails_pe_smstemplate_name =  $smsTemplate_Client->name; 
                    $this->bean->number_client =  $phone_number; 
                    $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate_Client,$focus),ENT_QUOTES)));
                }else{
                    $description = str_replace("\$name",$contact->first_name , $description);
                    $description_html = str_replace("\$name", $contact->first_name , $description_html);
                    //VUT - S - customer infomation
                    $contact_customer = new Contact();
                    $contact_customer->retrieve($invoice->contact_id3_c);
                    $customer_address = $invoice->install_address_c . " ".  $invoice->install_address_city_c . " ".  $invoice->install_address_state_c. " ".  $invoice->install_address_postalcode_c;

                    $customer_phone = '';
                    if ($contact_customer->phone_mobile != '') {
                        $customer_phone .= 'M: '.$contact_customer->phone_mobile;
                    } 
                    if ($contact_customer->phone_work != '') {
                        $customer_phone .= ' W: '.$contact_customer->phone_work;
                    }
                    $description = str_replace("\$aos_invoices_billing_contact",$contact_customer->name , $description);
                    $description_html = str_replace("\$aos_invoices_billing_contact", $contact_customer->name , $description_html);
                    $description = str_replace("\$aos_invoices_install_address_c",$customer_address , $description);
                    $description_html = str_replace("\$aos_invoices_install_address_c", $customer_address , $description_html);
                    $description = str_replace("\$aos_invoices_install_address_c",$customer_address , $description);
                    $description_html = str_replace("\$aos_invoices_install_address_c", $customer_address , $description_html);
                    $description = str_replace("\$aos_invoices_contact_id3_c",$customer_phone , $description);
                    $description_html = str_replace("\$aos_invoices_contact_id3_c", $customer_phone , $description_html);
                    //VUT - E - customer infomation
                    if( $_REQUEST['email_type'] == 'plumber_calendar' ){
                        $description = str_replace("\$aos_invoices_plumbing_notes_c",$invoice->plumbing_notes_c , $description);
                        $description_html = str_replace("\$aos_invoices_plumbing_notes_c", $invoice->plumbing_notes_c , $description_html);
                        $description = str_replace("\$distance_to_suite_c",$invoice->distance_to_suite_c , $description);
                        $description_html = str_replace("\$distance_to_suite_c", $invoice->distance_to_suite_c , $description_html);
                    }else { //electrician
                        $description = str_replace("\$aos_invoices_electrical_notes_c",$invoice->electrical_notes_c , $description);
                        $description_html = str_replace("\$aos_invoices_electrical_notes_c", $invoice->electrical_notes_c , $description_html);
                        $description = str_replace("\$distance_to_suite_c",$invoice->distance_to_suitecrm_c , $description);
                        $description_html = str_replace("\$distance_to_suite_c", $invoice->distance_to_suitecrm_c , $description_html);
                    }
                    //VUT - S - Add file "Proposed Install Location" to email Plumber/Electrician
                    $invoice_file_attachments = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $invoice->installation_pictures_c ."/");
                    $name_file_include = 'Proposed_Install_Location';
                    if (count($invoice_file_attachments)>0 ) {
                        $this->bean->status = "draft";
                        $this->bean->save();
                        foreach ($invoice_file_attachments as $att){
                            $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $invoice->installation_pictures_c ."/" . $att ;
                            if(!is_file($source)) continue;
                            if (strpos(strtolower($att),strtolower($name_file_include)) !== false 
                                || strpos(strtolower($att),strtolower('_Existing_Hws')) !== false /**https://trello.com/c/3Fe84CCL/3026-invoice-email-po-and-send-out-the-calendar-link-to-the-installers-please-ensure-automatically-show-the-old-hws-photos-and-switch?menu=filter&filter=member:paulszuster1,mode:and */
                                || strpos(strtolower($att),strtolower('Switchboard')) !== false) {
                                $noteTemplate = new Note();
                                $noteTemplate->id = create_guid();
                                $noteTemplate->new_with_id = true; // duplicating the note with files
                                $noteTemplate->parent_id = $this->bean->id;
                                $noteTemplate->parent_type = 'Emails';
                                $noteTemplate->date_entered = '';
                                // $noteTemplate->file_mime_type = 'application/pdf';
                                $noteTemplate->filename = $att;
                                $noteTemplate->name = $att;
            
                                $noteTemplate->save();
            
                                $destination = realpath(dirname(__FILE__) . '/../../').'/upload/'.$noteTemplate->id;
                                if (!symlink($source, $destination)) {
                                    $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                                }
                                $this->bean->attachNote($noteTemplate);
                            }
                        }
                    }
                    //VUT - E - Add file "Proposed Install Location" to email Plumber/Electrician
                    // //start - code render sms_template  
                    // global $current_user;
                        $smsTemplate = BeanFactory::getBean(
                            'pe_smstemplate',
                            'ca646f5f-399a-d408-7536-601102429ed6' 
                        );
                        $body =  $smsTemplate->body_c;
                        $body = str_replace("\$first_name", $contact->first_name, $body);
                        $body = str_replace("\$aos_invoices_billing_contact",  $contact_customer->name, $body);
                        $smsTemplate->body_c = $body;
                        $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                        $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                    $this->bean->number_receive_sms = "matthew_paul_client";
                    $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                    $phone_number = preg_replace("/^61/", "+61", $phone_number);
                    $this->bean->number_client =  $phone_number; 
                    $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus),ENT_QUOTES)));   
                    // //end - code render sms_template
                }
                $description = str_replace("\$installation_calendar_url", $link_calendar, $description);

                $description_html = str_replace("\$installation_calendar_url", $link_calendar , $description_html);

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //Add email sale person
                $user_assign = new User();
                $user_assign->retrieve($invoice->assigned_user_id);
                $email_assigned = $user_assign->email1;
                $this->bean->cc_addrs_names = $email_assigned;
            }

            /**VUT-E-Quote-Button 'Send Inspection Request' (send for installer Sanden/Daikin) */
            if($_REQUEST['email_type'] == 'send_site_inspection_request'){
                $record_id = trim($_REQUEST['record_id']);
                $macro_nv = array();
                $focusName = "AOS_Quotes";
                $focus = BeanFactory::getBean($focusName, $record_id);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplateID =  '342c960a-732e-c0ab-18a7-5ef40150d2dc'; //suitecrm server
                //$emailTemplateID = '21f1b63d-d4c6-5da2-9fd4-5ef44eab2172'; //test devel VUT

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    $emailTemplateID
                );
                //get email from contact
                $contact_bean = new Contact;
                $contact_bean->retrieve($focus->billing_contact_id);

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                //parse value
                $description = str_replace("\$contact_phone_mobile",$contact_bean->phone_mobile , $description);
                $description_html = str_replace("\$contact_phone_mobile",$contact_bean->phone_mobile , $description_html);

                
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                /**Custom Attachments */
                // $noteArray = array();
                $file_attachments = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c ."/");
                $name_file_include = 'Proposed_Install_Location';
                if (count($file_attachments)>0 ) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach ($file_attachments as $att) {
                        $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c ."/" . $att ;
                        if(!is_file($source)) continue;
                        if (strpos(strtolower($att),strtolower($name_file_include))) {
                            $noteTemplate = new Note();
                            $noteTemplate->id = create_guid();
                            $noteTemplate->new_with_id = true; // duplicating the note with files
                            $noteTemplate->parent_id = $this->bean->id;
                            $noteTemplate->parent_type = 'Emails';
                            $noteTemplate->date_entered = '';
                            $noteTemplate->filename = $att;
                            $noteTemplate->name = $att;
                            if(strpos(strtolower($att), "png") !== false) {
                                $noteTemplate->file_mime_type = 'image/png';
                            } elseif (strpos(strtolower($att), "pdf") !== false) {
                                $noteTemplate->file_mime_type = 'application/pdf';
                            } else {
                                $noteTemplate->file_mime_type = 'image/jpg';
                            }
                                
                            $noteTemplate->save();
        
                            $destination = realpath(dirname(__FILE__) . '/../../').'/upload/'.$noteTemplate->id;
                            if (!symlink($source, $destination)) {
                                $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                            }
                            $this->bean->attachNote($noteTemplate);
                        }
                    }
                }
                //get infomation from quote
                /**Check product type => select installer */
                $installer = new Account();
                $contact_installer = new Contact();
                if ($focus->quote_type_c ==  "quote_type_sanden" ) {
                    $installer->retrieve($focus->account_id3_c);
                    $contact_installer->retrieve($installer->primary_contact_c);
                } else if ($focus->quote_type_c == "quote_type_daikin" || $focus->quote_type_c == "quote_type_nexura") {
                    $installer->retrieve($focus->account_id4_c);
                    $contact_installer->retrieve($installer->primary_contact_c);
                }
                
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                $this->bean->to_addrs_names = $installer->name." <".$installer->email1.">";


                // //start - code render sms_template  
                // global $current_user;
                $smsTemplateID = '328a27e7-51e8-1640-4183-5d75f7757982'; //suitecrm server
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    $smsTemplateID 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact_installer->first_name, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->number_receive_sms = "matthew_paul_client";
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact_installer->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client =  $phone_number; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus)/**.' '.$current_user->sms_signature_c */,ENT_QUOTES)));   
                // //end - code render sms_template
            }
            /**VUT-E-Quote-Button 'Send Inspection Request' */

           //Nhat code https://trello.com/c/XTSzMI2F/
            if($_REQUEST['email_type'] == 'sanden_freight_estimate'){
                $record_id = trim($_REQUEST['record_id']);
                $macro_nv = array();
                $focusName = "AOS_Quotes";
                $focus = BeanFactory::getBean($focusName, $record_id);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplateID = '4f9d33bc-347c-d77d-04c5-609ca866758e'; //suitecrm server
                // $emailTemplateID = '5b618685-ec7e-14d2-56d4-609dd973ed74'; //test devel

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    $emailTemplateID
                );
                //get email from contact
                $contact_bean = new Contact;
                $contact_bean->retrieve($focus->billing_contact_id);

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                //parse value
                // $name = str_replace("\$aos_quotes_site_detail_addr__city_c",$focus->billing_address_city, $name);
                // $description_html = str_replace("\$aos_quotes_site_detail_addr__city_c",$focus->billing_address_city . ", " . $focus->billing_address_state .", " . $focus->billing_address_postalcode , $description_html);

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                /**Custom Attachments */
                // $noteArray = array();

                // $file_attachments = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c ."/");
                // $name_file_include = 'Proposed_Install_Location';
                // if (count($file_attachments)>0 ) {
                //     $this->bean->status = "draft";
                //     $this->bean->save();
                //     foreach ($file_attachments as $att) {
                //         $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c ."/" . $att ;
                //         if(!is_file($source)) continue;
                //         if (strpos(strtolower($att),strtolower($name_file_include))) {
                //             $noteTemplate = new Note();
                //             $noteTemplate->id = create_guid();
                //             $noteTemplate->new_with_id = true; // duplicating the note with files
                //             $noteTemplate->parent_id = $this->bean->id;
                //             $noteTemplate->parent_type = 'Emails';
                //             $noteTemplate->date_entered = '';
                //             $noteTemplate->filename = $att;
                //             $noteTemplate->name = $att;
                //             if(strpos(strtolower($att), "png") !== false) {
                //                 $noteTemplate->file_mime_type = 'image/png';
                //             } elseif (strpos(strtolower($att), "pdf") !== false) {
                //                 $noteTemplate->file_mime_type = 'application/pdf';
                //             } else {
                //                 $noteTemplate->file_mime_type = 'image/jpg';
                //             }
                                
                //             $noteTemplate->save();
        
                //             $destination = realpath(dirname(__FILE__) . '/../../').'/upload/'.$noteTemplate->id;
                //             if (!symlink($source, $destination)) {
                //                 $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                //             }
                //             $this->bean->attachNote($noteTemplate);
                //         }
                //     }
                // }

                //get infomation from quote
                /**Check product type => select installer */

                // $installer = new Account();
                // $contact_installer = new Contact();
                // if ($focus->quote_type_c ==  "quote_type_sanden" ) {
                //     $installer->retrieve($focus->account_id3_c);
                //     $contact_installer->retrieve($installer->primary_contact_c);
                // } else if ($focus->quote_type_c == "quote_type_daikin" || $focus->quote_type_c == "quote_type_nexura") {
                //     $installer->retrieve($focus->account_id4_c);
                //     $contact_installer->retrieve($installer->primary_contact_c);
                // }
                
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                $this->bean->to_addrs_names = $installer->name." <".$installer->email1.">";


                // //start - code render sms_template  
                global $current_user;
                $smsTemplateID = '328a27e7-51e8-1640-4183-5d75f7757982'; //suitecrm server
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    $smsTemplateID 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact_installer->first_name, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->number_receive_sms = "matthew_paul_client";
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact_installer->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client =  $phone_number; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus)/**.' '.$current_user->sms_signature_c */,ENT_QUOTES)));   
                // //end - code render sms_template
            }

            //Button 'Delivery Schedule'
            if($_REQUEST['email_type'] == 'delivery_schedule'){ 
                // tuan code for plumber 
                if( isset($_REQUEST['email_plumber']) && $_REQUEST['email_plumber'] == "plumber"){
                    $emailTemplateID =  'bebb052d-9864-2d21-5ee9-5ed0892cb560'; //suitecrm server

                    $emailTemplate = BeanFactory::getBean(
                        'EmailTemplates',
                        $emailTemplateID
                    );
                    //get email from contact
                    $purchase_bean = new PO_purchase_order();
                    $purchase_bean->retrieve(trim($_REQUEST['record_id']));

                    $contact_bean = new Contact();
                    $contact_bean->retrieve($purchase_bean->contact_id_c);

                    $name = $emailTemplate->subject;
                    $description_html = $emailTemplate->body_html;
                    $description = $emailTemplate->body;
                    //parse value
                    $product = ucwords(str_replace("_", " " , $purchase_bean->po_type_c));
                        //Change subject Email Delivery
                        $deleted_name = preg_replace('/([\d]{1,2} [\w]{3} [\d]{0,4} [\d].*)/', '', $purchase_bean->name);
                        $name = str_replace("\$po_purchase_order_name", $deleted_name, $name);
                        $name = str_replace("\$aos_invoices_delivery_date_c", $purchase_bean->delivery_date_c, $name);

                        $description = str_replace("\$contact_first_name",$contact_bean->first_name , $description);
                        $description = str_replace("\$aos_invoices_quote_type_c",$product , $description);
                        $description = str_replace("\$aos_invoices_delivery_date_c",$purchase_bean->delivery_date_c , $description);

                        $description_html = str_replace("\$contact_first_name",$contact_bean->first_name , $description_html);
                        $description_html = str_replace("\$aos_invoices_quote_type_c",$product, $description_html);
                        $description_html = str_replace("\$aos_invoices_delivery_date_c",$purchase_bean->delivery_date_c, $description_html);

                }else {
                    $record_id = trim($_REQUEST['record_id']);
                    $macro_nv = array();
                    $focusName = "AOS_Invoices";
                    $focus = BeanFactory::getBean($focusName, $record_id);

                    if(!$focus->id) return;
                    /**
                     * @var EmailTemplate $emailTemplate
                     */

                    $emailTemplateID =  'bebb052d-9864-2d21-5ee9-5ed0892cb560'; //suitecrm server

                    $emailTemplate = BeanFactory::getBean(
                        'EmailTemplates',
                        $emailTemplateID
                    );
                //get email from contact
                    $contact_bean = new Contact;
                    $contact_bean->retrieve($focus->billing_contact_id);
                    $name = $emailTemplate->subject;
                    $description_html = $emailTemplate->body_html;
                    $description = $emailTemplate->body;
                    //parse value
                        $name = str_replace("\$po_purchase_order_name", $focus->name, $name);
                        $name = str_replace("\$aos_invoices_delivery_date_c", $focus->delivery_date_time_c, $name);

                        $description = str_replace("\$contact_first_name",$contact_bean->first_name , $description);
                        $description = str_replace("\$aos_invoices_delivery_date_c",$focus->delivery_date_time_c , $description);

                        $description_html = str_replace("\$contact_first_name",$contact_bean->first_name , $description_html);
                        $description_html = str_replace("\$aos_invoices_delivery_date_c",$focus->delivery_date_time_c , $description_html);
                }

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                //get email from contact
                $this->bean->name = trim(preg_replace('/\s+/',' ', $templateData['subject']));
                // $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //start - code render sms_template  
                global $current_user;
                $smsTemplateID = 'b66e78dc-12ca-b703-b70a-5ed089ff1212';
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    $smsTemplateID 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact_bean->first_name, $body);
                if( isset($_REQUEST['email_plumber']) && $_REQUEST['email_plumber'] == "plumber"){
                    $body = str_replace("\$product_type", $product, $body);
                }
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->number_receive_sms = "matthew_paul_client";
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact_bean->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client =  $phone_number; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            // tuan reupload customer 
            if($_REQUEST['email_type'] == 'client_reuploads_photo'){ 
                $record_id = trim($_REQUEST['record_id']);
                $macro_nv = array();
                $focusName = "AOS_Invoices";
                $focus = BeanFactory::getBean($focusName, $record_id);
                if(!$focus->id) return;

                $emailTemplateID = '95cb7a0c-5386-b18d-5fd9-601a676c3e7b';// 'e69a39b1-f128-16c0-3693-601a5c0f52ea'; 

                $emailTemplate = BeanFactory::getBean(
                        'EmailTemplates',
                        $emailTemplateID
                    );

                $invoice = new AOS_Invoices();
                $invoice->retrieve($_REQUEST['record_id']);
                $contact =  new Contact();
                $contact->retrieve($invoice->billing_contact_id);

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                
                $link_upload_files = 'https://pure-electric.com.au/upload_file_sanden/for-customer?invoice_id=' . $invoice->id;
                $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">File Upload Link Here</a>';
                $description = str_replace("\$aos_invoices_customer",$contact->first_name , $description);
                $description = str_replace("\$aos_invoices_link_upload",$string_link_upload_files , $description);

                $description_html = str_replace("\$aos_invoices_customer",$contact->first_name , $description_html);
                $description_html = str_replace("\$aos_invoices_link_upload",$string_link_upload_files, $description_html);


                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                //get email from contact

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //start - code render sms_template  
                global $current_user;
                $smsTemplateID = '1d415167-da0b-628f-9e36-601a68d8a93c';
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    $smsTemplateID 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$aos_invoices_customer", $contact->first_name, $body);
                $body = str_replace("\$aos_invoices_link_upload", $string_link_upload_files, $body);

                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->number_receive_sms = "matthew_paul_client";
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client =  $phone_number; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            // tuan info pack daikin alira
            if($_REQUEST['email_type'] == 'InfoPackAlira'){ 

                $macro_nv = array();
                $focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);
                if(!$focus->id) return;

                $emailTemplateID = '56ff8695-7163-315a-e1e8-60b9ae967c1a'; //local c36e9173-54eb-79b5-cc58-60bd72c6a78a';
                /**
                 * @var EmailTemplate $emailTemplate
                 */
                $emailTemplate = BeanFactory::getBean(
                        'EmailTemplates',
                        $emailTemplateID
                    );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                // //start - code render sms_template  
                // global $current_user;
                // $smsTemplateID = '1d415167-da0b-628f-9e36-601a68d8a93c';
                // $smsTemplate = BeanFactory::getBean(
                //     'pe_smstemplate',
                //     $smsTemplateID 
                // );
                // $body =  $smsTemplate->body_c;
                // $body = str_replace("\$aos_invoices_customer", $focus->first_name, $body);
                // $body = str_replace("\$aos_invoices_link_upload", $string_link_upload_files, $body);

                // $smsTemplate->body_c = $body;
                // $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                // $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                // $this->bean->number_receive_sms = "matthew_paul_client";
                // $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $focus->phone_mobile));
                // $phone_number = preg_replace("/^61/", "+61", $phone_number);
                // $this->bean->number_client =  $phone_number; 
                // $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            if($_REQUEST['email_type'] == 'calls_voice_email' || $_REQUEST['email_type'] == 'tks_for_voice_email'){ 

                $macro_nv = array();
                $focusName = "Calls";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['record_id']);
                if(!$focus->id) return;

                $quote_id = $focus->aos_quotes_id_c;

                $quote = new AOS_Quotes();
                $quote->retrieve($quote_id); 
                
                if ($quote->quote_type_c ==  "quote_type_sanden" ) {
                    $product = "Sanden";
                } else if ($quote->quote_type_c == "quote_type_daikin" || $invoice->quote_type_c == "quote_type_nexura") {
                    $product = "Daikin";
                }else if($quote->quote_type_c == "quote_type_solar"){
                    $product = "Solar";
                }
                if($_REQUEST['email_type'] == 'calls_voice_email'){
                    $emailTemplateID = '1a45b869-1279-5f67-840f-60c2c8df9567'; //46988bdc-d3b5-6f6f-6b5b-60c96666cfaa'; 
                }elseif($_REQUEST['email_type'] == 'tks_for_voice_email') {
                    $emailTemplateID = '2aec2ea2-ecfe-1964-1ba0-60c94ecf7775' ; //9425e4ae-4527-cd24-cdbc-60c96f01e322'; 
                }
                /**
                 * @var EmailTemplate $emailTemplate
                 */
                $emailTemplate = BeanFactory::getBean(
                        'EmailTemplates',
                        $emailTemplateID
                    );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;

                $name = str_replace("\$aos_quotes_name", $quote->name , $name);
                $name = str_replace("\$aos_quotes_number", $quote->number , $name);

                $description = str_replace("\$contact_first_name",$quote->account_firstname_c , $description);
                $description = str_replace("\$aos_quotes_quote_type_c",$product, $description);

                $description_html = str_replace("\$contact_first_name",$quote->account_firstname_c , $description_html);
                $description_html = str_replace("\$aos_quotes_quote_type_c",$product , $description_html);

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //start - code render sms_template  
                $account = new Account();
                $account->retrieve($quote->billing_account_id); 
                $this->bean->to_addrs_names = $account->name .' <'.$account->email1.'>';


                global $current_user;
                $smsTemplateID = 'c7560fbf-417b-e397-360f-5e9e5066d209';
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    $smsTemplateID 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name",$quote->account_firstname_c, $body);

                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->number_receive_sms = "matthew_paul_client";
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $account->mobile_phone_c));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client =  $phone_number; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                // end - code render sms_template
            }
            if($_REQUEST['email_type'] == 'client_warranty_registration'){ 
                $emailTemplateID = 'a60e5ca5-6919-87ac-916c-6034cbff7477';//test 'c51e810f-f6b5-bf50-5ab6-6034cbce9ce3';


                $emailTemplate = BeanFactory::getBean(
                        'EmailTemplates',
                        $emailTemplateID
                    );

                $invoice = new AOS_Invoices();
                $invoice->retrieve($_REQUEST['record_id']);
                $contact =  new Contact();
                $contact->retrieve($invoice->billing_contact_id);

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                
                $link_upload_files = 'https://pure-electric.com.au/upload_file_sanden/client-warranty?invoice_id=' . $invoice->id;
                $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">File Upload Link Here</a>';
                $description = str_replace("\$contact_first_name",$contact->first_name , $description);
                $description = str_replace("\$aos_invoices_link_upload",$string_link_upload_files , $description);

                $description_html = str_replace("\$contact_first_name",$contact->first_name , $description_html);
                $description_html = str_replace("\$aos_invoices_link_upload",$string_link_upload_files, $description_html);
                    //signature
                $emailSignatureId = '3ad8f82a-d3e7-5897-7c98-5ba1c4ac785e'; 
                $user = new User();
                $user->retrieve('8d159972-b7ea-8cf9-c9d2-56958d05485e');
                $defaultEmailSignature = $user->getSignature($emailSignatureId);
            
                if (empty($defaultEmailSignature)) {
                    $defaultEmailSignature = array(
                        'html' => '<br>',
                        'plain' => '\r\n',
                    );
                    $defaultEmailSignature['no_default_available'] = true;
                } else {
                    $defaultEmailSignature['no_default_available'] = false;
                }
                $defaultEmailSignature['signature_html'] =  str_replace('Accounts', '', $defaultEmailSignature['signature_html']);
                // $description .= "<br><br><br>";
                $description .=  $defaultEmailSignature['signature_html'];
                // $description_html .= "<br><br><br>";
                $description_html .=  $defaultEmailSignature['signature_html'];
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                //get email from contact

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //start - code render sms_template  
                // global $current_user;
                // $smsTemplateID = '1d415167-da0b-628f-9e36-601a68d8a93c';
                // $smsTemplate = BeanFactory::getBean(
                //     'pe_smstemplate',
                //     $smsTemplateID 
                // );
                // $body =  $smsTemplate->body_c;
                // $body = str_replace("\$aos_invoices_customer", $contact->first_name, $body);
                // $body = str_replace("\$aos_invoices_link_upload", $string_link_upload_files, $body);

                // $smsTemplate->body_c = $body;
                // $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                // $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                // $this->bean->number_receive_sms = "matthew_paul_client";
                // $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                // $phone_number = preg_replace("/^61/", "+61", $phone_number);
                // $this->bean->number_client =  $phone_number; 
                // $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            // tuan Seek Better SG Solar PV Install Date
            if($_REQUEST['email_type'] == 'better_sg_solar_date'){ 
                $emailTemplateID = '1ebb24a7-11ab-cc01-b31d-60220a3e0adb';// 'test 7f0890f4-536c-20d6-6cda-602cc64ad9dd'; 

                $emailTemplate = BeanFactory::getBean(
                        'EmailTemplates',
                        $emailTemplateID
                    );

                $invoice = new AOS_Invoices();
                $invoice->retrieve($_REQUEST['record_id']);
                $contact =  new Contact();
                $contact->retrieve($invoice->billing_contact_id);

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;

                $name = str_replace("\$contact_name", $contact->first_name .' '.$contact->last_name , $name);
                $name = str_replace("\$aos_invoices_install_address_city_c", $invoice->install_address_city_c  , $name);
                $name = str_replace("\$aos_invoices_order_number_c", $invoice->order_number_c , $name);


                $description = str_replace("\$aos_invoices_order_number_c",$invoice->order_number_c , $description);
                $description = str_replace("\$contact_name",$contact->first_name .' '.$contact->last_name, $description);
                $description = str_replace("\$contact_email1",$contact->email1 , $description);
                $description = str_replace("\$aos_quotes_site_detail_addr__c",$invoice->install_address_c .' '.$invoice->install_address_city_c .' '.$invoice->install_address_state_c .' '.$invoice->install_address_postalcode_c, $description);
                $description = str_replace("\$contact_phone_mobile",$contact->phone_mobile , $description);
                $description = str_replace("\$aos_quotes_distributor_c",$invoice->distributor_c , $description);
                $description = str_replace("\$aos_invoices_quick_notes_c",$invoice->description , $description);

                $description_html = str_replace("\$aos_invoices_order_number_c",$invoice->order_number_c , $description_html);
                $description_html = str_replace("\$contact_name",$contact->first_name .' '.$contact->last_name, $description_html);
                $description_html = str_replace("\$contact_email1",$contact->email1 , $description_html);
                $description_html = str_replace("\$aos_quotes_site_detail_addr__c",$invoice->install_address_c .' '.$invoice->install_address_city_c .' '.$invoice->install_address_state_c .' '.$invoice->install_address_postalcode_c, $description_html);
                $description_html = str_replace("\$contact_phone_mobile",$contact->phone_mobile , $description_html);
                $description_html = str_replace("\$aos_quotes_distributor_c",$invoice->distributor_c , $description_html);
                $description_html = str_replace("\$aos_invoices_quick_notes_c",$invoice->description , $description_html);

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                //get email from contact

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
            }
            //dung code --- button Advise_Install_Date
            if($_REQUEST['email_type'] == 'Advise_Install_Date'){
                $seek_install_date_c = trim(Urldecode($_GET['seek_install_date_c']));
                $record_id = trim($_REQUEST['record_id']);
                $macro_nv = array();
                $focusName = "AOS_Quotes";
                $focus = BeanFactory::getBean($focusName, $record_id);

                if(!$focus->id) return;
                $this->bean->return_module = 'AOS_Quotes';
                $this->bean->return_id = $focus->id;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '7ad49555-86b7-1d86-93e3-5c876238eefb'
                );
                $name = $emailTemplate->subject . ' ' . $seek_install_date_c;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;

                //parse value
                $description = str_replace("\$aos_quotes_seek_install_date_c",$seek_install_date_c , $description);
                $description_html = str_replace("\$aos_quotes_seek_install_date_c",$seek_install_date_c , $description_html);
                $description = str_replace("\$contact_name",$focus->billing_contact , $description);
                $description_html = str_replace("\$contact_name",substr($focus->billing_contact, 0, strpos($focus->billing_contact, " ")) , $description_html);
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = '7ad49555-86b7-1d86-93e3-5c876238eefb';
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                //get email from contact
                $contact_bean = new Contact;
                $contact_bean->retrieve($focus->billing_contact_id);

                $this->bean->to_addrs_names = $contact_bean->email1;
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
            }
              //Tuan code ============================
            if($_REQUEST['email_type'] == "send-install-date"){
                $macro_nv = array();
                $focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

             // if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'e97f0f0f-7760-ef3e-b0f5-5c7dd58eea0f'
                   //tuan test 'ebdad234-2ddf-4f44-4499-5c7e4834c8b1'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = 'e97f0f0f-7760-ef3e-b0f5-5c7dd58eea0f';

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
            }

            //THIENPB CODE -- button Seek installer install date
            if ($_REQUEST['email_type'] == 'seek-install-date-from-po') {
                $focusName = "PO_purchase_order";
                $macro_nv = array();
                $focus = BeanFactory::getBean($focusName, $_REQUEST['po_id']);

                global $current_user;
                if($current_user->id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa")
                    $this->bean->mailbox_id = "b4fc56e6-6985-f126-af5f-5aa8c594e7fd";
                /**
                * @var EmailTemplate $emailTemplate
                */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'da9a3a97-6c94-44ef-84e7-5cd3d5cba616'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = 'da9a3a97-6c94-44ef-84e7-5cd3d5cba616';
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                //replcae variable
                $bean_invoice = BeanFactory::getBean('AOS_Invoices',$_REQUEST['invoice_id']);
                $bean_po = $focus;
                
                if(strpos($bean_po->name,'Plumbing') !== false){
                    $po_type = 'Plumbing';
                }else if(strpos($bean_po->name,'Electrical') !== false){
                    $po_type = 'Electrical';
                }else{
                    $po_type = '';
                }
                // replace subject variable
                $this->bean->name = str_replace("\$aos_invoices_name", $bean_invoice->name , $this->bean->name);
                
                // replace content variable
                $phone = '';
                $account_name = '';
                $address_customer = '';
                $distance = '';
                $description = '';

                if($po_type == 'Plumbing'){
                    $account_name= $bean_invoice->plumber_contact_c;
                }else if('Electrical'){
                    $account_name= $bean_invoice->electrician_contact_c;
                }else{
                    $account_name ='';
                }

                if($bean_po->billing_account != ''){
                    $address_customer = $bean_po->shipping_address_street .' '.$bean_po->shipping_address_city .' ' .$bean_po->shipping_address_state .' '. $bean_po->shipping_address_postalcode;
                    $distance = $bean_po->distance_to_travel;
                }else{
                    if($po_type == 'Plumbing'){
                        $distance = $bean_invoice->distance_to_suite_c;

                    }else if('Electrical'){
                        $distance = $bean_invoice->distance_to_suite_c;
                    }else{
                        $distance = '';
                    }
                    $address_customer = $bean_invoice->shipping_address_street .' '.$bean_invoice->shipping_address_city .' ' .$bean_invoice->shipping_address_state .' '. $bean_invoice->shipping_address_postalcode;
                }

                $bean_account = BeanFactory::getBean('Accounts',$bean_po->shipping_account_id);
                if($bean_account->home_phone_c){
                    $phone .= " H: ".$bean_account->phone_home;
                }
                if($bean_account->mobile_phone_c){
                    $phone .= " M: ".$bean_account->mobile_phone_c;
                }

                if($bean_account->phone_office){
                    $phone .= " W: ".$bean_account->phone_office;
                }

                if(trim($phone) == ''){
                    $db = DBManagerFactory::getInstance();
                    $sql = "SELECT * FROM `leads` WHERE `account_id` ='".$bean_po->shipping_account_id."'";
                    $ret = $db->query($sql);
                    while ($row = $db->fetchByAssoc($ret)) {
                        if($row['phone_home']){
                            $phone .= "H: ".$row['phone_home'];
                        }
                        if($row['phone_mobile']){
                            $phone .= "M: ".$row['phone_mobile'];
                        }
                        if($row['phone_work']){
                            $phone .= "W: ".$row['phone_work'];
                        }
                    }
                }

                if($bean_po->description !=''){
                    $description = $bean_po->description;
                }else{
                    if($po_type == 'Plumbing'){
                        $description = $bean_invoice->plumbing_notes_c;

                    }else if('Electrical'){
                        $description = $bean_invoice->electrical_notes_c;
                    }else{
                        $description = '';
                    }
                }

                $this->bean->description_html = str_replace("XX (Plumber)",$account_name, $this->bean->description_html);
                $this->bean->description_html = str_replace("\$$ XX (Contact)",$bean_po->shipping_account, $this->bean->description_html);
                $this->bean->description_html = str_replace("\$\$ XX (Install Address)",$address_customer, $this->bean->description_html);
                $this->bean->description_html = str_replace("M: XX W: XX (Contact Mobile Number / Contact Work Number)",$phone, $this->bean->description_html);
                $this->bean->description_html = str_replace("XX km",$distance, $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_invoices_plumbing_notes_c", $description, $this->bean->description_html);
            }

            //THIENPB CODE -- button Proposed installer install date
            if ($_REQUEST['email_type'] == 'proposed-install-date-from-po') {
                $focusName = "PO_purchase_order";
                $macro_nv = array();
                $focus = BeanFactory::getBean($focusName, $_REQUEST['po_id']);

                global $current_user;
                if($current_user->id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa")
                    $this->bean->mailbox_id = "b4fc56e6-6985-f126-af5f-5aa8c594e7fd";
                /**
                * @var EmailTemplate $emailTemplate
                */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'f02e67ea-99cc-112d-c8a2-5cd3d490c7ae'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = 'f02e67ea-99cc-112d-c8a2-5cd3d490c7ae';
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                //replcae variable
                $bean_invoice = BeanFactory::getBean('AOS_Invoices',$_REQUEST['invoice_id']);
                $bean_po = $focus;
                
                if(strpos($bean_po->name,'Plumbing') !== false){
                    $po_type = 'Plumbing';
                }else if(strpos($bean_po->name,'Electrical') !== false){
                    $po_type = 'Electrical';
                }else{
                    $po_type = '';
                }
                // replace subject variable
                $this->bean->name = str_replace("\$aos_invoices_installation_date_c", $bean_po->install_date , $this->bean->name);
                $this->bean->name = str_replace("\$aos_invoices_name", $bean_po->name , $this->bean->name);
                
                // replace content variable
                $phone = '';
                $account_name = '';
                $address_customer = '';
                $distance = '';
                $description = '';
                if($po_type == 'Plumbing'){
                    $account_name= $bean_invoice->plumber_contact_c;
                    $contact = new Contact();
                    $contact->retrieve($bean_invoice->contact_id4_c);
                } else if($po_type =='Electrical'){
                    $account_name= $bean_invoice->electrician_contact_c;
                    $contact = new Contact();
                    $contact->retrieve($bean_invoice->contact_id_c);
                }else{
                    $account_name ='';
                }

                if($bean_po->billing_account != ''){
                    $address_customer = $bean_po->shipping_address_street .' '.$bean_po->shipping_address_city .' ' .$bean_po->shipping_address_state .' '. $bean_po->shipping_address_postalcode;
                    $distance = $bean_po->distance_to_travel;
                }else{
                    if($po_type == 'Plumbing'){
                        $distance = $bean_invoice->distance_to_suite_c;

                    }else if($po_type =='Electrical'){
                        $distance = $bean_invoice->distance_to_suitecrm_c;
                    }else{
                        $distance = '';
                    }
                    $address_customer = $bean_invoice->shipping_address_street .' '.$bean_invoice->shipping_address_city .' ' .$bean_invoice->shipping_address_state .' '. $bean_invoice->shipping_address_postalcode;
                }

                $bean_account = BeanFactory::getBean('Accounts',$bean_po->shipping_account_id);
                if($bean_account->home_phone_c){
                    $phone .= " H: ".$bean_account->phone_home;
                }
                if($bean_account->mobile_phone_c){
                    $phone .= " M: ".$bean_account->mobile_phone_c;
                }

                if($bean_account->phone_office){
                    $phone .= " W: ".$bean_account->phone_office;
                }

                if(trim($phone) == ''){
                    $db = DBManagerFactory::getInstance();
                    $sql = "SELECT * FROM `leads` WHERE `account_id` ='".$bean_po->shipping_account_id."'";
                    $ret = $db->query($sql);
                    while ($row = $db->fetchByAssoc($ret)) {
                        if($row['phone_home']){
                            $phone .= "H: ".$row['phone_home'];
                        }
                        if($row['phone_mobile']){
                            $phone .= "M: ".$row['phone_mobile'];
                        }
                        if($row['phone_work']){
                            $phone .= "W: ".$row['phone_work'];
                        }
                    }
                }

                if($bean_po->description !=''){
                    $description = $bean_po->description;
                }else{
                    if($po_type == 'Plumbing'){
                        $description = $bean_invoice->plumbing_notes_c;

                    }else if($po_type =='Electrical'){
                        $description = $bean_invoice->electrical_notes_c;
                    }else{
                        $description = '';
                    }
                }

                $this->bean->description_html = str_replace("\$account_name",$account_name, $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_invoices_installation_date_c",   $bean_po->install_date , $this->bean->description_html);
                $this->bean->description_html = str_replace("Customer Name: \$\$ XX",'Customer Name: '.$bean_po->shipping_account , $this->bean->description_html);
                $this->bean->description_html = str_replace("Customer Address: \$\$ XX", 'Customer Address: '.$address_customer, $this->bean->description_html);
                $this->bean->description_html = str_replace("Customer Phone: M: XX W: XX", 'Customer Phone: '.$phone, $this->bean->description_html);
                $this->bean->description_html = str_replace("XX km",$distance, $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_invoices_plumbing_notes_c", $description, $this->bean->description_html);
                //VUT - Add phone number SMS
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";
            }

            if($_REQUEST['email_type'] == "first-daikin"){
                $macro_nv = array();
                $focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

                // if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '8d9e9b2c-e05f-deda-c83a-59f97f10d06a'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb =  '8d9e9b2c-e05f-deda-c83a-59f97f10d06a';
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $link_upload_files = 'https://pure-electric.com.au/pedaikinform-new/confirm-to-lead?lead-id=' . $focus->id;
                $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">File Upload Link Here</a>';
                $this->bean->description_html = str_replace("\$link_upload_files",$string_link_upload_files, $this->bean->description_html);
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '2745cca2-2fc8-6ec6-de78-5ecb17f27320' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $focus->first_name, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //thienpb code -logic for 3 sanden type
            if($_REQUEST['email_type'] == "Sanden_EQTAQ"){
                $macro_nv = array();
                $focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

               // if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '7c189f2f-19a9-c2c1-23fa-59f922602067'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = '7c189f2f-19a9-c2c1-23fa-59f922602067';
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
            }
            if($_REQUEST['email_type'] == "Sanden_FQS"){
                $macro_nv = array();
                $focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

               // if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'dbf622ae-bb45-cb79-eb97-5cd287c48ac3'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = 'dbf622ae-bb45-cb79-eb97-5cd287c48ac3';
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                $link_upload_files = 'https://pure-electric.com.au/pe-sanden-quote-form/confirm-to-lead?lead-id=' . $focus->id;
                $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">File Upload Link Here</a>';
                $this->bean->description_html = str_replace("\$link_upload_files",$string_link_upload_files, $this->bean->description_html);
            
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '4faf7d06-e182-e671-b5db-5ed065b777af' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $focus->first_name, $body);
                $link_upload_files = 'https://pure-electric.com.au/pe-sanden-quote-form/confirm-to-lead?lead-id=' . $focus->id;
                $body = str_replace("\$link_upload_files",$link_upload_files, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            if($_REQUEST['email_type'] == "Sanden_FQV"){
                $macro_nv = array();
                $focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

               // if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = 'ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5';
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                
                $link_upload_files = 'https://pure-electric.com.au/pe-sanden-quote-form/confirm-to-lead?lead-id=' . $focus->id;
                $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">File Upload Link Here</a>';
                $this->bean->description_html = str_replace("\$link_upload_files",$string_link_upload_files, $this->bean->description_html);
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '4faf7d06-e182-e671-b5db-5ed065b777af' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $focus->first_name, $body);
                $link_upload_files = 'https://pure-electric.com.au/pe-sanden-quote-form/confirm-to-lead?lead-id=' . $focus->id;
                $body = str_replace("\$link_upload_files",$link_upload_files, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            
            //authority_to_leave
            if($_REQUEST['email_type'] == 'authority_to_leave'){
                $emailTemplateID =  '86230685-a99f-e7ba-b6ef-5fa0ad6a2bc3';
                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    $emailTemplateID
                );
                $record_id = trim($_REQUEST['record_id']);
                $macro_nv = array();
                $focusName = "pe_warehouse_log";
                $focus = BeanFactory::getBean($focusName, $record_id);
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                 //parse value - custom value
                 $pe_warehouse_log_arrival_date_c = explode(" ",$focus->arrival_date_c)[0];
                 $description = str_replace("\$pe_warehouse_log_arrival_date_c",$pe_warehouse_log_arrival_date_c , $description);
                 $description_html = str_replace("\$pe_warehouse_log_arrival_date_c",$pe_warehouse_log_arrival_date_c , $description_html);
                
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; 
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = trim(preg_replace('/\s+/',' ', $templateData['subject']));
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body'];
            }

            //thienpb code -- methven button
            if($_REQUEST['email_type'] == "methven"){
                $macro_nv = array();
                $focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

               // if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'ec302586-cd96-e843-bd9b-5b25c5b0b321'
                    //'2aa99273-e383-766f-6810-5bf602b5f4b5'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = 'ec302586-cd96-e843-bd9b-5b25c5b0b321';
                //$this->bean->emails_email_templates_idb = '2aa99273-e383-766f-6810-5bf602b5f4b5';

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
            }

            //VUT-S-Off Grid button in Leads detailview
            if($_REQUEST['email_type'] == "off-grid"){
                $macro_nv = array();
                $focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

               // if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    // '71bdedf6-bd1b-bb19-1d82-5ebb777fb2e2' //local
                    'aadabe47-f800-266e-78dc-5e49e7eb2629'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                // $this->bean->emails_email_templates_idb = '71bdedf6-bd1b-bb19-1d82-5ebb777fb2e2'; // local
                $this->bean->emails_email_templates_idb = 'aadabe47-f800-266e-78dc-5e49e7eb2629';

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {

                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
            }
            //VUT-E-Off Grid button in Leads detailview
            //VUT-S- Invoice - Edit - Free promo code
            if($_REQUEST['email_type'] == 'email_promo_code_methven') {
                $macro_nv = array();
                $focusName = "AOS_Invoices";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['record_id']);
                $promo_codes = str_replace("_",", ", $_REQUEST['promo_codes']);
    
                if(!$focus->id) return;
                /**
                 * get Contact
                 */
                $contact = new Contact();
                $contact->retrieve($focus->billing_contact_id);
                /**
                 * @var EmailTemplate $emailTemplate
                 */
                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'ce8e68b1-1188-e395-ac77-5f8e51353711'
                );
    
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                //Change variables
                $description_html = str_replace("\$lead_first_name",$contact->first_name,$description_html);
                $description = str_replace("\$lead_first_name",$contact->first_name,$description);
                if ($promo_codes == '') {
                    $promo_codes = "no code";
                }
                $description_html = str_replace("\$promo_codes",$promo_codes,$description_html);
                $description = str_replace("\$promo_codes",$promo_codes,$description);

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();
    
                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
    
                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;
    
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);
    
                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
    
                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '4c447d23-ebde-fc9a-4de7-5f96792042b1' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact->first_name, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //VUT-E- Invoice - Edit - Free promo code


            //EMAIL GET ROT Agreement
            if($_REQUEST['email_type'] == 'EMAIL_GET_ROT_Agreement') {
                $macro_nv = array();
                $focusName = "AOS_Invoices";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['record_id']);
    
                if(!$focus->id) return;
                /**
                 * get Contact
                 */
                $contact = new Contact();
                $contact->retrieve($focus->billing_contact_id);
                /**
                 * @var EmailTemplate $emailTemplate
                 */
                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '872b8b71-0374-c4ee-50aa-5f0e99e1728a'
                );
    
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                //Change variables
                $description_html = str_replace("\$aos_invoices_name",$focus->name,$description_html);
                $description = str_replace("\$aos_invoices_name",$focus->name,$description);

                $description_html = str_replace("\$aos_customer_name",$contact->first_name,$description_html);
                $description = str_replace("\$aos_customer_name",$contact->first_name,$description);
    
                $customer_address = $focus->install_address_c . " ".  $focus->install_address_city_c . " ".  $focus->install_address_state_c. " ".  $focus->install_address_postalcode_c;
                $description_html = str_replace("\$aos_invoices_install_address_c",$customer_address,$description_html);
                $description = str_replace("\$aos_invoices_install_address_c",$customer_address,$description);

                $custom_link_ROT_Agreement = '<a href="https://pure-electric.com.au/pesignaturepad?invoiceID=' .$focus->id .'&method=getCustomerInfo" target="_blank">Link Customer Agreement</a>' ;
                
                $description_html = str_replace("\$custom_link_ROT_Agreement",$custom_link_ROT_Agreement,$description_html);
                $description = str_replace("\$custom_link_ROT_Agreement",$custom_link_ROT_Agreement,$description);
               
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );
                $this->bean->emails_email_templates_idb = $emailTemplateID ;
                $attachmentBeans = $emailTemplate->getAttachments();
    
                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
    
                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;
    
                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);
    
                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
    
                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '45161090-6c10-3134-3a77-5f0ff10ec644' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name",  $contact->first_name , $body);
                $custom_link_ROT_Agreement = ' https://pure-electric.com.au/pesignaturepad?invoiceID=' .$focus->id .'&method=getCustomerInfo' ;
                $smsTemplate->body_c = $body . $custom_link_ROT_Agreement;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                $path_file_json_sms_signture = dirname(__FILE__) .'/../../custom/modules/Users/json_sms_signture.json';
                $json_data = json_decode(file_get_contents($path_file_json_sms_signture),true);
                if(isset($json_data)) {
                    if(isset($json_data['1588918966'])) {
                        $this->bean->sms_message .='.'. PHP_EOL.PHP_EOL .$json_data['1588918966']['content'];
                        $this->bean->sms_signture = $json_data['1588918966']['content'];
                    }
                }

                //end - code render sms_template
            }

            //TriTruong Button Payment Reminder
            if($_REQUEST['email_type'] == "invoice_payment_reminder"){
                $macro_nv = array();
                $focusName = "AOS_Invoices";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['record_id']);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '6fa66d7d-8834-330a-a5dd-5cd38a4f6b2b'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                $focus_user = BeanFactory::getBean('Users', $focus->assigned_user_id);
                $this->bean->description_html = str_replace("\$Firstname", explode(" ", $focus->billing_account)[0] , $this->bean->description_html);
                $this->bean->name = str_replace("\$Lastname ", explode(" ", $focus->billing_account,2)[1] , $this->bean->name);
                $this->bean->name = str_replace("#", "#".$focus->number ,$this->bean->name);
                $this->bean->description_html = str_replace("\$AssignedUser Number", $focus_user->phone_mobile, $this->bean->description_html);
                if(strpos(strtolower($focus->name),'sanden')){
                    $this->bean->name = str_replace("\$Product_Type ", "Sanden", $this->bean->name);
                    $this->bean->description_html = str_replace("\$product_type", 'Sanden', $this->bean->description_html);
                }else {
                    $this->bean->name = str_replace("\$Product_Type ", "Daikin", $this->bean->name);
                    $this->bean->description_html = str_replace("\$product_type", 'Daikin', $this->bean->description_html);
                }
            }
            
            if($_REQUEST['email_type'] == "requestClientInfo"){
                $macro_nv = array();
                $focusName = "AOS_Quotes";
                $quote_id = $_REQUEST['record_id'];
                $focus = BeanFactory::getBean($focusName, $_REQUEST['record_id']);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '62056415-781f-ae0b-837c-5ce75658c29f'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        //$noteTemplate->file_mime_type = 'application/pdf';
                        //$noteTemplate->filename = $att;
                        //$noteTemplate->name = $att;

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }
                $token = sha1(uniqid($quote_id, true));
                $db = DBManagerFactory::getInstance();
                $db->query("INSERT INTO pending_quote_token (quote_id, token, tstamp) VALUES ('$quote_id','$token' ,".time().")");
                $url = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=formInformationClient&token=" . $token;
                // $url = "http://suitecrm-pure.local/index.php?entryPoint=formInformationClient&token=" . $token;

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];
                $this->bean->description_html = str_replace("\$aos_quotes_account_firstname_c", explode(" ", $focus->billing_account)[0] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$url", $url , $this->bean->description_html);
            }
            //End TriTruong

            //product review Daikin US7
            if($_REQUEST['email_type'] == "product_review_daikin_us7"){
                $macro_nv = array();

                if($_REQUEST['email_module'] == "AOS_Invoices"){
                    $invoice = new AOS_Invoices();
                    $focus = $invoice->retrieve($_REQUEST['record_id']);

                    $contact =  new Contact();
                    $contact->retrieve($focus->billing_contact_id);
                }else{
                    $contact =  new Contact();
                    $contact->retrieve($_REQUEST['record_id']);
                    $focus = $contact;
                }

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '9b47772c-36d8-299f-7731-5f69647817f0'
                    //'879f1d44-ddec-6bfb-2d52-5d4b999cc551'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $this->bean->description_html = str_replace("\$contact_first_name", $contact->first_name , $this->bean->description_html);
                $this->bean->description_html = str_replace("__DISPLAY_NAME__", urlencode($contact->first_name.' '.$contact->last_name),$this->bean->description_html);
                $this->bean->description_html = str_replace("__EMAIL_ADDRESS__", urlencode($contact->email1) ,$this->bean->description_html);
                
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";

                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    'db055ffd-fa7d-0040-47d7-5f6970089b50' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact->first_name, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //end

            //product review PE Sanden 
            if($_REQUEST['email_type'] == "product_review_sanden"){
                $macro_nv = array();

                if($_REQUEST['email_module'] == "AOS_Invoices"){
                    $invoice = new AOS_Invoices();
                    $focus = $invoice->retrieve($_REQUEST['record_id']);

                    $contact =  new Contact();
                    $contact->retrieve($focus->billing_contact_id);
                }else{
                    $contact =  new Contact();
                    $contact->retrieve($_REQUEST['record_id']);
                    $focus = $contact;
                }

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'e47752ee-3f12-ff1e-6ae3-5f694a6f865f'
                    //'879f1d44-ddec-6bfb-2d52-5d4b999cc551'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $this->bean->description_html = str_replace("\$contact_first_name", $contact->first_name , $this->bean->description_html);
                $this->bean->description_html = str_replace("__DISPLAY_NAME__", urlencode($contact->first_name.' '.$contact->last_name),$this->bean->description_html);
                $this->bean->description_html = str_replace("__EMAIL_ADDRESS__", urlencode($contact->email1) ,$this->bean->description_html);
                
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";

                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    'aef500c5-7ac2-0ab6-03e2-5f696f128bc1' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact->first_name, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //end
            //Thienpb code -- product review
            if($_REQUEST['email_type'] == "product_review"){
                $macro_nv = array();

                if($_REQUEST['email_module'] == "AOS_Invoices"){
                    $invoice = new AOS_Invoices();
                    $focus = $invoice->retrieve($_REQUEST['record_id']);

                    $contact =  new Contact();
                    $contact->retrieve($focus->billing_contact_id);
                }else{
                    $contact =  new Contact();
                    $contact->retrieve($_REQUEST['record_id']);
                    $focus = $contact;
                }

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'b230b9b0-9bf6-5c41-fa63-5d4ba9aaeaf6'
                    //'879f1d44-ddec-6bfb-2d52-5d4b999cc551'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $this->bean->description_html = str_replace("\$contact_first_name", $contact->first_name , $this->bean->description_html);
                $this->bean->description_html = str_replace("__DISPLAY_NAME__", urlencode($contact->first_name.' '.$contact->last_name),$this->bean->description_html);
                $this->bean->description_html = str_replace("__EMAIL_ADDRESS__", urlencode($contact->email1) ,$this->bean->description_html);
                
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";

                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '70600baf-0edb-5fca-424e-5d6f4aee3adc' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact->first_name, $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //end
            
            //Thienpb code -- google review
            if($_REQUEST['email_type'] == "google_review"){
                $macro_nv = array();

                if($_REQUEST['email_module'] == "AOS_Invoices"){
                    $invoice = new AOS_Invoices();
                    $focus = $invoice->retrieve($_REQUEST['record_id']);

                    $contact =  new Contact();
                    $contact->retrieve($focus->billing_contact_id);
                }else{
                    $contact =  new Contact();
                    $contact->retrieve($_REQUEST['record_id']);
                    $focus = $contact;
                }

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '841f3d2b-ffed-0901-c4be-5d4cd5f2f8f6'
                    //'879f1d44-ddec-6bfb-2d52-5d4b999cc551'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $this->bean->description_html = str_replace("\$contact_first_name",  $contact->first_name , $this->bean->description_html);
    
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '21e95a26-c066-860f-95bf-5d6863e424af' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name",  $contact->first_name , $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //end

            if($_REQUEST['email_type'] == "methven_review"){
                $macro_nv = array();

                $contact =  new Contact();
                $contact->retrieve($_REQUEST['record_id']);
                $focus = $contact;
                

                if(!$focus->id) return;

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'ad9a7d93-5e1f-9f5f-e97a-5fadbfd90839'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $this->bean->description_html = str_replace("\$contact_first_name",  $contact->first_name , $this->bean->description_html);
    
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;
                $this->bean->number_receive_sms = "matthew_paul_client";
                //start - code render sms_template  - comment because not yet use it
                // global $current_user;
                // $smsTemplate = BeanFactory::getBean(
                //     'pe_smstemplate',
                //     '21e95a26-c066-860f-95bf-5d6863e424af' 
                // );
                // $body =  $smsTemplate->body_c;
                // $body = str_replace("\$first_name",  $contact->first_name , $body);
                // $smsTemplate->body_c = $body;
                // $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                // $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                // $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
    
            if($_REQUEST['email_type'] == "pe_methven_review"){
                $macro_nv = array();
                $contact =  new Contact();
                $contact->retrieve($_REQUEST['record_id']);
                $focus = $contact;

                if(!$focus->id) return;
                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'cb8c50b4-cfc6-bd04-ff22-5fadbe781e47'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $this->bean->description_html = str_replace("\$contact_first_name",  $contact->first_name , $this->bean->description_html);
    
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";
                //start - code render sms_template  -comment because not yet use it
                // global $current_user;
                // $smsTemplate = BeanFactory::getBean(
                //     'pe_smstemplate',
                //     '21e95a26-c066-860f-95bf-5d6863e424af' 
                // );
                // $body =  $smsTemplate->body_c;
                // $body = str_replace("\$first_name",  $contact->first_name , $body);
                // $smsTemplate->body_c = $body;
                // $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                // $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                // $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
       

            //Thienpb code -- Word of Mouth
            if($_REQUEST['email_type'] == "word_of_mouth"){
                $macro_nv = array();

                // if($_REQUEST['email_module'] == "AOS_Invoices"){
                //     $invoice = new AOS_Invoices();
                //     $focus = $invoice->retrieve($_REQUEST['record_id']);

                //     $contact =  new Contact();
                //     $contact->retrieve($focus->billing_contact_id);
                // }else{
                    $contact =  new Contact();
                    $contact->retrieve($_REQUEST['record_id']);
                    $focus = $contact;
                // }

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'ac5bb370-7b90-f0df-b9d7-5d771a7485e8'
                    //'bd0249da-2da8-63ea-13de-5d772903bb4c'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $this->bean->description_html = str_replace("\$contact_first_name",  $contact->first_name , $this->bean->description_html);
                $this->bean->name = str_replace("\$contact_name ",  $contact->first_name .' ' .$contact->last_name , $this->bean->name);
                $this->bean->name = str_replace("\$contact_first_name ",  $contact->first_name  , $this->bean->name);

                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '724168b1-0270-3d97-74b6-5e6018256edb' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name",  $contact->first_name , $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //end
            
            //Thienpb code --facebook
            if($_REQUEST['email_type'] == "facebook"){
                $macro_nv = array();

                // if($_REQUEST['email_module'] == "AOS_Invoices"){
                //     $invoice = new AOS_Invoices();
                //     $focus = $invoice->retrieve($_REQUEST['record_id']);

                //     $contact =  new Contact();
                //     $contact->retrieve($focus->billing_contact_id);
                // }else{
                    $contact =  new Contact();
                    $contact->retrieve($_REQUEST['record_id']);
                    $focus = $contact;
                // }

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '75cc2aab-ce5f-6201-1443-5d772a4565ef'
                    //'bd0249da-2da8-63ea-13de-5d772903bb4c'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;
                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $name,
                        'body_html' => $description_html,
                        'body' => $description,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $this->bean->to_addrs_names = $contact->first_name.' '.$contact->last_name." <$contact->email1>";
                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $this->bean->description_html = str_replace("\$contact_first_name",  $contact->first_name , $this->bean->description_html);
    
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";
            }
            //end

            // .:nhantv:. off-grid pricing
            if($_REQUEST['email_type'] == "off_grid_pricing"){
                $macro_nv = array();
                $focusName = "AOS_Quotes";
                $quote = new AOS_Quotes();
                $focus = $quote->retrieve($_REQUEST['record_id']);
                $this->bean->return_module = 'AOS_Quotes';
                $this->bean->return_id = $focus->id;
                $lead =  new Lead();
                $lead->retrieve($focus->leads_aos_quotes_1leads_ida);

                $contact =  new Contact();
                $contact->retrieve($focus->billing_contact_id);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */
                // Live: c11048a6-4055-49f1-0b3f-60b7500751a2
                // Local: 3677ce10-b644-b632-0ce5-60b7531891e0
                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    'c11048a6-4055-49f1-0b3f-60b7500751a2'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $source = realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c;
                $filesDesigns = $this->check_exist_file($source, 'Design');
                $filesSolar = $this->check_exist_file($source, 'Quote_');
                $filesSolar_Pdf = [];
                foreach ($filesSolar as $value) {
                    if (strpos(strtolower($value), "pdf") !== false) {
                        $filesSolar_Pdf[] =   $value;
                    }
                }
                $all_files_send  = array_merge($filesDesigns,$filesSolar_Pdf);
                foreach ($all_files_send as $file) {
                    $this->bean->save();
                    $noteTemplate = new Note();
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true;
                    $noteTemplate->parent_id = $this->bean->id; 
                    $noteTemplate->parent_type = 'Emails';
                    $noteTemplate->date_entered = '';
                    $noteTemplate->filename = $file;
                    $noteTemplate->name = $file;   
                    $noteTemplate->save();
                    global $sugar_config;
                    $destination = $sugar_config['upload_dir'].$noteTemplate->id;
                    $sourcefile = $source.'/'.$file;

                    if(strpos(strtolower($file), "png") !== false) {
                        $noteTemplate->file_mime_type = 'image/png';
                    } elseif (strpos(strtolower($file), "pdf") !== false) {
                        $noteTemplate->file_mime_type = 'image/jpg';
                    } else {
                        $noteTemplate->file_mime_type = 'image/jpg';
                    }
                    if (!symlink($sourcefile, $destination)) {
                    $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                    }
                    $this->bean->attachNote($noteTemplate);
                }
                
                $this->bean->to_addrs_names = $lead->first_name.' '.$lead->last_name." <$lead->email1>";
                $this->bean->name = $name;
                $this->bean->description_html = $description_html;
                $this->bean->description = $description;

                //VUT - S - replace Quote Inputs  
                if ($quote->quote_note_inputs_c !='') {
                    $solar_quote_input = json_decode(html_entity_decode($quote->quote_note_inputs_c), true);
                    if ($_REQUEST['view'] == 'detailview') {
                        $_REQUEST['storey'] = $solar_quote_input['storeys'];
                    }
                    if (count(array_filter($solar_quote_input)) == 0) {
                        $this->bean->description_html = htmlspecialchars(preg_replace('/(?si)<p id="sugar_text_change_p_table"(.*)<=?\/p>/U', '',html_entity_decode($this->bean->description_html)));
                        // $this->bean->description_html = str_replace("\$table_solar_quote_inputs", '' , $this->bean->description_html);
                    } else {
                        $this->bean->description_html = $this->renderTableQuoteInputSolarPricing($solar_quote_input, $this->bean->description_html);
                    }
                } else {
                    $this->bean->description_html = htmlspecialchars(preg_replace('/(?si)<p id="sugar_text_change_p_table"(.*)<=?\/p>/U', '',html_entity_decode($this->bean->description_html)));
                    // $this->bean->description_html = str_replace("\$table_solar_quote_inputs", '' , $this->bean->description_html);
                } 
                //VUT - E - replace Quote Inputs 
                //replace data for subject - VUT - 2020/03/04
                $this->bean->name = str_replace("\$aos_quotes_billing_account",  $focus->billing_account, $this->bean->name);
                $this->bean->name = str_replace("\$aos_quotes_site_detail_addr__city_c",  $focus->install_address_city_c , $this->bean->name);
                $this->bean->name = str_replace("\$aos_quotes_site_detail_addr__state_c ",  $focus->install_address_state_c.' ' , $this->bean->name);
                
                //replace data for body
                $this->bean->description_html = str_replace("\$contact_first_name",  $contact->first_name , $this->bean->description_html);
                
                $this->bean->description_html = str_replace("\$aos_quotes_installation address_c", $_REQUEST['address'] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_stroreys_c", $_REQUEST['storey'] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_preferred_c", "No" , $this->bean->description_html);

                // if( strpos($_REQUEST['address'],"VIC") == TRUE){
                //     $html_vic = '<table style="text-align:left;border-collapse:collapse;width:735px;">
                //                 <tbody>
                //                 <tr>
                //                     <td style="padding: 5px; border: .5px solid #8a8a8a;">Want to apply Solar VIC Rebate to your solar pricing?</td>
                //                     <td style="padding: 5px; border: .5px solid #8a8a8a;width: 47%">'. $_REQUEST['vic_rebate'] .'</td>
                //                 </tr>
                //                 <tr>
                //                     <td style="padding: 5px; border: .5px solid #8a8a8a;">Want to apply Solar VIC Loan to your solar pricing?</td>
                //                     <td style="padding: 5px; border: .5px solid #8a8a8a;" >'.  $_REQUEST['vic_loan'] .'</td>
                //                 </tr>
                //                 </tbody></table>';
                //     $this->bean->description_html = str_replace("\$aos_solar_vic_loan_c", $html_vic ,$this->bean->description_html);
                //     // $body_html = str_replace("\$aos_quotes_loan_c",    ($vic_loan == "yes_loan") ? "Yes": 'No' , $body_html);
                // } else {
                    $this->bean->description_html = str_replace("\$aos_solar_vic_loan_c"," " ,$this->bean->description_html);
                // }

                // .:nhantv:. 
                $pricing_options = $focus->offgrid_option_c;
                $solar_pricing_options = '';

                if($pricing_options != ''){
                    $pricings = json_decode(html_entity_decode($pricing_options));
                    // Inverter line
                    $inverter_line = (int)$pricings->inverter_line + 1;
                    // Offgrid inverter line
                    $offgrid_inverter_line = (int) $pricings->offgrid_inv_line + 1;
                    // Accesory line
                    $accessory_line = (int)$pricings->og_accessory_line + 1;
                    // Init Option price area
                    $solar_pricing_options .= '<div style="margin:0;padding:0;box-sizing:border-box;width:100%;max-width:735px;line-height:1.8;font-family:sans-serif;font-size:16px">';
                    // Get Product bean
                    $productBean = BeanFactory::newBean('AOS_Products');

                    // Loop through options
                    for ($i=1; $i < 7 ; $i++) {
                        // Inverter optimize
                        $inverter_og = array();
                        for ($line = 1; $line < $inverter_line; $line++){
                            $tmpName = 'inverter_og_type'.$line.'_'.$i;
                            if($pricings->$tmpName != ''){
                                // Get Product name from DB
                                $inverter_name = $this->getProductNameByShortName($productBean, $pricings->$tmpName);
                                // Check exist on array
                                if($inverter_og[$inverter_name] != null){
                                    $newQty = $inverter_og[$inverter_name] + 1;
                                    $inverter_og[$inverter_name] = $newQty;
                                } else {
                                    $inverter_og[$inverter_name] = 1;
                                }
                            }
                        }
                        // Offgrid Inverter 
                        $offgrid_inverters = [];
                        for ($line = 1; $line < $offgrid_inverter_line; $line++) {
                            $tmpName = 'offgrid_inverter'.($line == 1 ? '' : $line).'_'.$i;
                            if($pricings->$tmpName != ''){
                                // Get Product name from DB
                                $offgrid_inverter_name = $this->getProductNameByShortName($productBean, $pricings->$tmpName);
                                // Check exist on array
                                if($offgrid_inverters[$offgrid_inverter_name] != null){
                                    $newQty = $offgrid_inverters[$offgrid_inverter_name] + 1;
                                    $offgrid_inverters[$offgrid_inverter_name] = $newQty;
                                } else {
                                    $offgrid_inverters[$offgrid_inverter_name] = 1;
                                }
                            }
                        }
                        // Accessory optimize
                        $accessory_og = array();
                        for ($line = 1; $line < $accessory_line; $line++){
                            $tmpName = 'offgrid_accessory'.$line.'_'.$i;
                            if($pricings->$tmpName != ''){
                                // Get Product name from DB
                                $accessory_name = $this->getProductNameByShortName($productBean, $pricings->$tmpName);
                                // Check exist on array
                                if($accessory_og[$accessory_name] != null){
                                    $newQty = $accessory_og[$accessory_name] + 1;
                                    $accessory_og[$accessory_name] = $newQty;
                                } else {
                                    $accessory_og[$accessory_name] = 1;
                                }
                            }
                        }

                        // Curent Products name from DB
                        $curr_product = $this->getCurrentProductName($productBean, $pricings, $i);

                        // STCs price
                        $stc_price = $this->calcStcs($productBean, $pricings->{'number_og_stcs_'.$i});
                        
                        // Check if option has value to render
                        if($pricings->{'og_total_'.$i} != "" || $pricings->{'og_total_'.$i} != 0){
                            // Format og_total
                            $grandTotal = substr($pricings->{'og_total_'.$i}, 0, -3);
                            // Check 2n + 1 item
                            if($i > 1 && fmod($i, 2) == 1){
                                $solar_pricing_options .= '<div style="clear: both;"></div>';
                            }
                            // Check total battery
                            $total_battery = ($pricings->{'total_battery_'.$i} != null && $pricings->{'total_battery_'.$i} != '') ? (float)$pricings->{'total_battery_'.$i} : 0;
                            // Render Option
                            $solar_pricing_options .= '<div style="float:left;padding:0;width:30%;min-width:365px;background:#fff;color:#444;text-align:center;overflow:hidden;margin:0">
                            <div style="margin:0.5rem;border-radius:2rem;border:3px solid rgb(235,235,235)">
                              <div style="border-top-left-radius:2rem;border-top-right-radius:2rem;clear:both;margin:0;font-weight:bold;padding:0 0 0.25rem 0;color:#fff;background:#C32177;">
                                <div style="width:100%;clear: both;">
                                    <div style="width:60px;text-align:center;float: left;">
                                        <h1 style="margin: 0;padding:0;color:#fb2a5c;font-size:2rem;background:white;border-radius:2rem;border:2px solid rgba(196, 33, 120, 0.7)">'.$i.'</h1>
                                    </div>
                                    <div style="text-align:center;">
                                        <h1 style="margin:0;padding:7px 0;font-size:1.5rem;color: white;">'.(float)$pricings->{'total_og_kW_'.$i}.' kW / '.$total_battery.' kWh</h1>
                                    </div>
                                </div>
                              </div>
                              <div style="margin:0;padding:0.5rem;">
                                <div style="padding:0;color:#444;list-style:none;text-align:left;margin:0.5rem 0 0 0;">
                                  <table style="width: 100%;">
                                    <tbody>
                                      <tr>
                                        <td style="text-align:left">
                                          <h1 style="margin:0 0 0.25rem 0;padding:0;font-size:1rem;font-weight:bold">'.$pricings->{'total_og_panels_'.$i}.'x '.$curr_product['panel'].'</h1>
                                        </td>
                                      </tr>';
                                    // Render inverter
                                    foreach ($inverter_og as $key => $value) {
                                        $solar_pricing_options .= '<tr>
                                            <td>
                                                <div style="margin:0;padding:0;font-size:0.8rem">'.$value.'x '.$key.'</div>
                                            </td>
                                        </tr>';
                                    }
                                    // Render Offgrid inverter
                                    foreach ($offgrid_inverters as $key => $value) {
                                        $solar_pricing_options .= '<tr>
                                            <td>
                                                <div style="margin:0;padding:0;font-size:0.8rem">'.$value.'x '.$key.'</div>
                                            </td>
                                        </tr>';
                                    }
                            $solar_pricing_options .= '<tr>
                                        <td>
                                          <div style="margin:0;padding:0;font-size:0.8rem">'.$pricings->{'offgrid_howmany_'.$i}.'x '.$curr_product['offgrid_batery'].'</div>
                                        </td>
                                      </tr>';
                                    // Render Accessories
                                    foreach ($accessory_og as $key => $value) {
                                        $solar_pricing_options .= '<tr>
                                            <td>
                                                <div style="margin:0;padding:0;font-size:0.8rem">'.$value.'x '.$key.'</div>
                                            </td>
                                        </tr>';
                                    }
                                    // Render Generator
                                    if ($pricings->{'re_generator_'.$i} != '') {
                                        $solar_pricing_options .= '<tr>
                                            <td>
                                                <div style="margin:0;padding:0;font-size:0.8rem">1x '.$curr_product['re_generator'].'</div>
                                            </td>
                                        </tr>';
                                    }
                            $solar_pricing_options .= '</tbody>
                                  </table>
                                </div>
                                <div style="color:#444;text-align:left;margin:0.5rem 0 0 0;padding:0.5rem 0 0 0;border-top:1px solid rgb(235,235,235)">
                                    <table style="width:100%;font-weight:bold">
                                        <tbody>
                                            <tr>
                                                <td style="width:70%;text-align:left">
                                                    <p style="margin:0;padding:0;font-size:0.8rem;color:gray">Full Purchase Price (inc GST)</p>
                                                </td>
                                                <td style="width:30%;text-align:right">
                                                    <p style="margin:0;padding:0;font-size:0.8rem;color:gray;font-weight:bold">$ '.((float)str_replace(',', '', $grandTotal) - $stc_price).'</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:70%;text-align:left">
                                                    <p style="margin:0;padding:0;font-size:0.8rem;color:gray">Less STCs (GST N/A)</p>
                                                </td>
                                                <td style="width:30%;text-align:right">
                                                    <p style="margin:0;padding:0;font-size:0.8rem;color:#F2283C;font-weight:bold">$ '.$stc_price.'</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:70%;text-align:left">
                                                    <p style="margin:0;padding:0;font-size:0.8rem;color:gray">Discounted Purchase Price</p>
                                                </td>
                                                <td style="width:30%;text-align:right">
                                                    <p style="margin:0;padding:0;font-size:0.8rem;color:gray;font-weight:bold"></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                              <h1 style="border-bottom-left-radius: 2rem;border-bottom-right-radius: 2rem;margin:0;font-size:2rem;font-weight:bold;color:#f77422;padding:0.5rem 2rem;border-top:1px solid rgb(235, 235, 235);">
                                <span style="margin: 0;padding:0;font-size: 1.5rem;">$</span> '.$grandTotal.'</h1>
                            </div>
                          </div>';
                        }
                    }
                    $solar_pricing_options .= '<div style="clear: both;"></div></div>';
                }
                $this->bean->description_html = str_replace("\$solar_pricing_options",  $solar_pricing_options , $this->bean->description_html);

                $meter_phase_c  = array('','Single Phase','Two Phase (Rural Only)','Three Phase');
                $distributor_c = array("0"=>"",
                        "4" => "Citipower",
                        "5" => "Jemena",
                        "6"=>"Powercor",
                        "7"=>"SP Ausnet",
                        "8"=>"United Energy Distribution",
                        "1"=>"Western Power",
                        "13"=>"South Australia Power Network",
                        "2"=>"Energex",
                        "3" => "Ergon",
                        "9" => "Essential Energy",
                        "10"=>"Ausgrid",
                        "12"=>"Endeavour Energy",
                        "11"=>"ActewAGL",
                        "14"=>"AusNet Electricity Services Pty Ltd",
                );
                
                if(  $_REQUEST['storey'] == 'Double Storey'){
                    $gutter_height_c = "3-5m";
                }else {
                    $gutter_height_c = "0-3m";
                }
                $roof_type_c    = array('Tin'=>'Tin',
                                        'Tile'=>'Tile',
                                        'klip_loc'=>'Klip Loc',
                                        'Concrete'=>'Concrete',
                                        'Trim_Deck'=>'Trim Deck',
                                        'Insulated'=>'Insulated',
                                        'Asbestos'=>'Asbestos',
                                        'Ground_Mount'=>'Ground Mount',
                                        'Terracotta'=>'Terracotta',
                                        'Other'=>'Other');
                $roof_pitch_c    = array('0-25 Degrees'=>'0-25 Degrees',
                                        '25-30 Degrees'=>'25-30 Degrees',
                                        '30+ Degrees'=>'30+ Degrees');
                
                $this->bean->description_html = str_replace("\$aos_quotes_meter_phase_c",  $meter_phase_c[$focus->meter_phase_c] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_distributor_c",  $distributor_c[$focus->distributor_c] , $this->bean->description_html);
                // $this->bean->description_html = str_replace("\$aos_quotes_first_solar_c",   'No' , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_gutter_height_c",   $gutter_height_c , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_roof_pitch_c",   $roof_pitch_c[$focus->roof_pitch_c] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_roof_type_c",  $roof_type_c[$focus->roof_type_c] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_main_switch_c",$focus->main_switch_c , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_external_internal_c", $focus->external_or_internal_c, $this->bean->description_html);

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $this->bean->name,
                        'body_html' => $this->bean->description_html,
                        'body' => $this->bean->description_html,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";
                //start - code render sms_template  
                global $current_user;
                // live: 25817940-0e0d-3d72-5e1b-60b853000a9a
                // local: 1d4462d6-1a54-45af-3a7c-5d647d113e0e
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '25817940-0e0d-3d72-5e1b-60b853000a9a' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact->first_name, $body);
                $body = str_replace("\$aos_quote_id", $_REQUEST['record_id'], $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //end

            // TriTruong Daikin Pricing
            if($_REQUEST['email_type'] == "daikin_pricing"){
                $macro_nv = array();
                $focusName = "AOS_Quotes";
                $quote = new AOS_Quotes();
                $focus = $quote->retrieve($_REQUEST['record_id']);
                $this->bean->return_module = 'AOS_Quotes';
                $this->bean->return_id = $focus->id;
                $lead =  new Lead();
                $lead->retrieve($focus->leads_aos_quotes_1leads_ida);

                $contact =  new Contact();
                $contact->retrieve($focus->billing_contact_id);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */
                // Live: c11048a6-4055-49f1-0b3f-60b7500751a2
                // Local: 3677ce10-b644-b632-0ce5-60b7531891e0
                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    '663bcda3-938a-319a-43e7-60d543a5da8b'
                    // 'c193892e-03a6-dcf2-cf23-60d44b214156'
                );

                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;

                $this->bean->to_addrs_names = $lead->first_name.' '.$lead->last_name." <$lead->email1>";
                $this->bean->name = $name;
                $this->bean->description_html = $description_html;
                $this->bean->description = $description;

                if ($quote->quote_note_inputs_c !='') {
                    $daikin_quote_input = json_decode(html_entity_decode($quote->quote_note_inputs_c), true);
                    if (count(array_filter($daikin_quote_input)) == 0) {
                        $this->bean->description_html = htmlspecialchars(preg_replace('/(?si)<p id="sugar_text_change_p_table"(.*)<=?\/p>/U', '',html_entity_decode($this->bean->description_html)));
                        
                    } else {
                        $this->bean->description_html = $this->renderTableQuoteInputSolarPricing($daikin_quote_input, $this->bean->description_html);
                    }
                } else {
                    $this->bean->description_html = htmlspecialchars(preg_replace('/(?si)<p id="sugar_text_change_p_table"(.*)<=?\/p>/U', '',html_entity_decode($this->bean->description_html)));
                    
                } 

                $this->bean->name = str_replace("\$aos_quotes_billing_account",  $focus->billing_account, $this->bean->name);
                $this->bean->name = str_replace("\$aos_quotes_site_detail_addr__city_c",  $focus->install_address_city_c , $this->bean->name);
                $this->bean->name = str_replace("\$aos_quotes_site_detail_addr__state_c ",  $focus->install_address_state_c.' ' , $this->bean->name);
                
                //replace data for body
                $this->bean->description_html = str_replace("\$contact_first_name",  $contact->first_name , $this->bean->description_html);
                
                $this->bean->description_html = str_replace("\$aos_quotes_installation address_c", $_REQUEST['address'] , $this->bean->description_html);
                
                $remove = ['daikin_pricing', 'main_line', 'wifi_line', 'extra_line', 'dk_pe_admin_percent', 'state'];
                $daikin_pricing_options = '';
                $style = '"margin: 0;text-transform: uppercase;color: white;"';
                $daikin_quote_input = array_diff_key($daikin_quote_input, array_flip($remove));
                foreach($daikin_quote_input as $key=>$value) {
                    // if(intval($value['grandtotal_dk_'.$key]) > 1000) {
                    if($value['isSend'] > 0 && intval($value["total_cooling_capacity_".$key]) > 0 && intval($value["total_heating_capacity_".$key]) > 0 ) {
                        $daikin_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float: left;width: 290px;background-color: white;margin-top: 25px;margin-right: 10px;box-shadow: 0 0 7px 0px #e6f9ff; margin-bottom: 30px" data-mce-style="float: left;width: 290px;background-color: white;margin-top: 25px;margin-right: 10px;box-shadow: 0 0 7px 0px #e6f9ff; margin-bottom: 30px">
                        <div class="op_header" style="width: 100%; position: inherit;" data-mce-style="width: 100%; position: inherit;">
                           <div class="number-options" style="float: left;width: 45px;height: 45px;text-align: center;line-height: 45px;font-weight: bold; '.($value["recom_dk_option_".$key] == 1 ? "background: #4f5ea5" : "background: #177eb3").';color: white;font-size: 18px;" data-mce-style="float: left;width: 45px;height: 45px;text-align: center;line-height: 45px;font-weight: bold;'.($value["recom_dk_option_".$key] == 1 ? "background: #4f5ea5" : "background: #177eb3").';color: white;font-size: 18px;">'.$key.'</div>
                           <div class="p" style="'.($value["recom_dk_option_".$key] == 1 ? "background: #4f5ea5b0" : "background-color: #009acf").';float: right;width: 245px;/* border-radius: 0 20px 20px 0; */height: 45px;osition: relative;/* border: 1px solid #009acf; */text-align: left;line-height: 45px;padding-left: 10p;font-size: 15px;" data-mce-style="'.($value["recom_dk_option_".$key] == 1 ? "background: #7484d0" : "background-color: #009acf").';float: right;width: 245px;/* border-radius: 0 20px 20px 0; */height: 45px;osition: relative;/* border: 1px solid #009acf; */text-align: left;line-height: 45px;padding-left: 10p;font-size: 15px;">
                              <p style="color: white;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 0px;padding-left: 12px;font-weight: bold;font-size: 16px;letter-spacing: 1px;margin-bottom: 0;" data-mce-style="color: white;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 0px;padding-left: 12px;font-weight: bold;font-size: 16px;letter-spacing: 1px;margin-bottom: 0;">'.((intval($value["total_cooling_capacity_".$key]) > 0 ) ? round($value["total_cooling_capacity_".$key], 1).'kW (C)' : '3.5 kW').' '.((intval($value["total_heating_capacity_".$key]) > 0 ) ? round($value["total_heating_capacity_".$key], 1).'kW (H)' : '').'</p>
                           </div>
                        </div>
                        <div class="select-inverter" style="clear: both;padding: 15px 10px;z-index: 7;position: relative;'.($value["recom_dk_option_".$key] == 1 ? "background: #f1f3ff" : "background: #e6f9ff").';" data-mce-style="clear: both;padding: 15px 10px;z-index: 7;position: relative;'.($value["recom_dk_option_".$key] == 1 ? "background: #f1f3ff" : "background: #e6f9ff").'">
                           '.$this->parseProduct($value['products'], $key, 'products').'
                           '.$this->parseProduct($value['wifi'], $key, 'wifi').'
                        </div>
                        <div class="total-price-item" style="'.($value["recom_dk_option_".$key] == 1 ? "background: #d0d5f3" : "background-color: #daf3ff").';padding: 10px;color: #0a557b;font-size: 14px;font-weight: 600;" data-mce-style="'.($value["recom_dk_option_".$key] == 1 ? "background: #d0d5f3" : "background-color: #daf3ff").';padding: 10px;color: #0a557b;font-size: 14px;font-weight: 600;">
                           <div style="margin-bottom: 10px;" data-mce-style="margin-bottom: 10px;">Sub Total<span style="float: right;" data-mce-style="float: right;">$'.$value['subtotal_dk_'.$key].'</span></div>
                           <div style="margin-bottom: 10px;" data-mce-style="margin-bottom: 10px;">GST<span style="float: right;" data-mce-style="float: right;">$'.$value['gst_dk_'.$key].'</span></div>
                           <div style="height: 1px;border-bottom: 1px solid #a5c9fc;margin: 5px 0px 5px 0px;" data-mce-style="height: 1px;border-bottom: 1px solid #a5c9fc;margin: 5px 0px 5px 0px;">&nbsp;</div>
                           <div class="total-price" style="text-align: center;/* border: 1px solid #ea9e23; */margin-top: 15px;" data-mce-style="text-align: center;/* border: 1px solid #ea9e23; */margin-top: 15px;">
                           <span class="symbol" style="font-size: 20px;'.($value["recom_dk_option_".$key] == 1 ? "color: #4f5ea5" : "color: #339acf").';font-weight: 600;" data-mce-style="font-size: 20px;'.($value["recom_dk_option_".$key] == 1 ? "color: #4f5ea5" : "color: #339acf").';font-weight: 600;">$ </span>
                           <span class="amount" style="letter-spacing: 1px;font-size: 35px;'.($value["recom_dk_option_".$key] == 1 ? "color: #4f5ea5" : "color: #339acf").';" data-mce-style="letter-spacing: 1px;font-size: 35px;'.($value["recom_dk_option_".$key] == 1 ? "color: #4f5ea5" : "color: #339acf").';">'.$value['grandtotal_dk_'.$key].'</span>
                           </div>
                        </div>
                        <div class="op_footer" style="text-align: center;padding: 5px;'.($value["recom_dk_option_".$key] == 1 ? "background: #4f5ea5" : "background-color: #009acf").';height: 25px;" data-mce-style="text-align: center;padding: 5px;'.($value["recom_dk_option_".$key] == 1 ? "background: #4f5ea5" : "background-color: #009acf").';height: 25px;">
                            '.($value["recom_dk_option_".$key] == 1 ? '<h3 style="margin: 0;text-transform: uppercase;color: white;padding: 2px 0;">Recommended</h3>' : "&nbsp;").'
                        </div>
                     </div>';     
                    }
                    
                }
                $this->bean->description_html = str_replace("\$daikin_pricing_options",  $daikin_pricing_options , $this->bean->description_html);
                //end - code render sms_template
            }
            //Thienpb code -- solar pricing options
            if($_REQUEST['email_type'] == "send_solar_pricing"){
                $macro_nv = array();
                $focusName = "AOS_Quotes";
                $quote = new AOS_Quotes();
                $focus = $quote->retrieve($_REQUEST['record_id']);
                $this->bean->return_module = 'AOS_Quotes';
                $this->bean->return_id = $focus->id;
                $lead =  new Lead();
                $lead->retrieve($focus->leads_aos_quotes_1leads_ida);

                $contact =  new Contact();
                $contact->retrieve($focus->billing_contact_id);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    //'ba4a72df-d9e3-7a20-d7b2-5d5bb366c7a4'
                    '9d9f03ae-fe75-68d0-72ad-5d5b95cda15b'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $source = realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c;
                $filesDesigns = $this->check_exist_file($source, 'Design');
                $filesSolar = $this->check_exist_file($source, 'Quote_');
                $filesSolar_Pdf = [];
                foreach ($filesSolar as $value) {
                    if (strpos(strtolower($value), "pdf") !== false) {
                        $filesSolar_Pdf[] =   $value;
                    }
                }
                $all_files_send  = array_merge($filesDesigns,$filesSolar_Pdf);
                foreach ($all_files_send as $file) {
                    $this->bean->save();
                    $noteTemplate = new Note();
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true;
                    $noteTemplate->parent_id = $this->bean->id; 
                    $noteTemplate->parent_type = 'Emails';
                    $noteTemplate->date_entered = '';
                    $noteTemplate->filename = $file;
                    $noteTemplate->name = $file;   
                    $noteTemplate->save();
                    global $sugar_config;
                    $destination = $sugar_config['upload_dir'].$noteTemplate->id;
                    $sourcefile = $source.'/'.$file;

                    if(strpos(strtolower($file), "png") !== false) {
                        $noteTemplate->file_mime_type = 'image/png';
                    } elseif (strpos(strtolower($file), "pdf") !== false) {
                        $noteTemplate->file_mime_type = 'image/jpg';
                    } else {
                        $noteTemplate->file_mime_type = 'image/jpg';
                    }
                    if (!symlink($sourcefile, $destination)) {
                    $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                    }
                    $this->bean->attachNote($noteTemplate);
                }
                
                $this->bean->to_addrs_names = $lead->first_name.' '.$lead->last_name." <$lead->email1>";
                $this->bean->name = $name;
                $this->bean->description_html = $description_html;
                $this->bean->description = $description;

                //VUT - S - replace Quote Inputs  
                if ($quote->quote_note_inputs_c !='') {
                    $solar_quote_input = json_decode(html_entity_decode($quote->quote_note_inputs_c), true);
                    if (count(array_filter($solar_quote_input)) == 0) {
                        $this->bean->description_html = htmlspecialchars(preg_replace('/(?si)<p id="sugar_text_change_p_table"(.*)<=?\/p>/U', '',html_entity_decode($this->bean->description_html)));
                        // $this->bean->description_html = str_replace("\$table_solar_quote_inputs", '' , $this->bean->description_html);
                    } else {
                        $this->bean->description_html = $this->renderTableQuoteInputSolarPricing($solar_quote_input, $this->bean->description_html);
                    }
                } else {
                    $this->bean->description_html = htmlspecialchars(preg_replace('/(?si)<p id="sugar_text_change_p_table"(.*)<=?\/p>/U', '',html_entity_decode($this->bean->description_html)));
                    // $this->bean->description_html = str_replace("\$table_solar_quote_inputs", '' , $this->bean->description_html);
                } 
                //VUT - E - replace Quote Inputs 
                //replace data for subject - VUT - 2020/03/04
                $this->bean->name = str_replace("\$aos_quotes_billing_account",  $focus->billing_account, $this->bean->name);
                $this->bean->name = str_replace("\$aos_quotes_site_detail_addr__city_c",  $focus->install_address_city_c , $this->bean->name);
                $this->bean->name = str_replace("\$aos_quotes_site_detail_addr__state_c ",  $focus->install_address_state_c.' ' , $this->bean->name);
                
                //replace data for body
                $this->bean->description_html = str_replace("\$contact_first_name",  $contact->first_name , $this->bean->description_html);
                
                $this->bean->description_html = str_replace("\$aos_quotes_installation address_c", $_REQUEST['address'] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_stroreys_c", $_REQUEST['storey'] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_preferred_c", "No" , $this->bean->description_html);

                if(   strpos($_REQUEST['address'],"VIC") == TRUE){
                    $html_vic = '<table style="text-align:left;border-collapse:collapse;width:735px;">
                                <tbody>
                                <tr>
                                    <td style="padding: 5px; border: .5px solid #8a8a8a;">Want to apply Solar VIC Rebate to your solar pricing?</td>
                                    <td style="padding: 5px; border: .5px solid #8a8a8a;width: 47%">'. $_REQUEST['vic_rebate'] .'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px; border: .5px solid #8a8a8a;">Want to apply Solar VIC Loan to your solar pricing?</td>
                                    <td style="padding: 5px; border: .5px solid #8a8a8a;" >'.  $_REQUEST['vic_loan'] .'</td>
                                </tr>
                                </tbody></table>';
                    $this->bean->description_html = str_replace("\$aos_solar_vic_loan_c", $html_vic ,$this->bean->description_html);
                    // $body_html = str_replace("\$aos_quotes_loan_c",    ($vic_loan == "yes_loan") ? "Yes": 'No' , $body_html);
                }else {
                    $this->bean->description_html = str_replace("\$aos_solar_vic_loan_c"," " ,$this->bean->description_html);
                }
                $pricing_options = $focus->solar_pv_pricing_input_c;

                $solar_pricing_options = '';

                if($pricing_options != ''){
                    $pricings = json_decode(html_entity_decode($pricing_options));
                    // for ($i=1; $i < 7 ; $i++) { 
                    //     if($pricings->{'base_price_'.$i} != "" || $pricings->{'base_price_'.$i} != 0){
                    //         $solar_pricing_options .= '<p style="font-family: Times New Roman; font-size: medium;" data-mce-style="font-family: Times New Roman; font-size: medium;"></br><strong>Option #'.$i.':</strong> '.$pricings->{'total_kW_'.$i}.'kW = '.$pricings->{'total_panels_'.$i}.'x '.$pricings->{'panel_type_'.$i}.' + '.$pricings->{'inverter_type_'.$i}.(($pricings->{'extra_1_'.$i})? ' + '.$pricings->{'extra_1_'.$i} : '').(($pricings->{'extra_2_'.$i})? ' + '.$pricings->{'extra_2_'.$i} : '').' = $'.$pricings->{'customer_price_'.$i}.' ($'. $pricings->{'price_kw_'.$i}.'/W)</p>';
                    //     }
                    // }
                    for ($i=1; $i < 7 ; $i++) { 
                        if($pricings->{'base_price_'.$i} != "" || $pricings->{'base_price_'.$i} != 0){
                            $extra_3 = "";
                            switch ( $pricings->{'inverter_type_'.$i} ){
                                case "Primo 3":
                                    $inverter = "Fronius Primo 3.0-1 3kW";
                                    break;
                                case "Primo 4":
                                    $inverter = "Fronius Primo 4.0-1 4kW";
                                    break;
                                case "Primo 5":
                                    $inverter = "Fronius Primo 5.0-1-I 5kW";
                                    break;
                                case "Primo 6":
                                    $inverter = "Fronius Primo 6.0-1 6kW"; 
                                    break;
                                case "Primo 8.2":
                                    $inverter = "Fronius Primo 8.2-1 8.2kW"; 
                                    break;
                                case "Symo 5":
                                    $inverter = "Fronius Symo 5 Dual Tracker"; 
                                    break;
                                case "Symo 6":
                                    $inverter = "Fronius Symo 6 Dual Tracker"; 
                                    break;
                                case "Symo 8.2":
                                    $inverter = "Fronius Symo 8.2 Dual Tracker"; 
                                    break;
                                case "Symo 10":
                                    $inverter = "Fronius Symo 10 Dual Tracker"; 
                                    break;
                                case "Symo 15":
                                    $inverter = "Fronius Symo 15.0kW Dual Tracker 10yr warranty"; 
                                    break;
                                case "SYMO 20":
                                    $inverter = "Fronius Symo 20.0kW Dual Tracker 10yr warranty"; 
                                    break;
                                case "IQ7X":
                                    $inverter = "Enphase IQ7X 315W Micro Inverter" ;
                                    break; 
                                case "IQ7+":
                                    $inverter = "Enphase IQ7+ 290W Micro Inverter";
                                    break;
                                case "S Edge 3G": 
                                    $inverter = "SolarEdge 3kW Genesis HD-Wave 1Ph Inverter";
                                    break; 
                                case "S Edge 5G": 
                                        $inverter = "SolarEdge 5kW Genesis HD-Wave 1Ph Inverter";
                                    break; 
                                case "S Edge 6G":
                                    $inverter = "SolarEdge 6kW Genesis HD-Wave 1Ph Inverter" ;
                                    break; 
                                case "S Edge 8G":
                                    $inverter = "SolarEdge 8.25kW Genesis HD-Wave 1Ph Inverter"; 
                                    break;
                                case "S Edge 8 3P":
                                    $inverter = "SolarEdge 8kW Three Phases Inverter"; 
                                    break;
                                case "S Edge 10G":
                                    $inverter = "SolarEdge 10kW Genesis HD Wave 1Ph Inverter";
                                    break;
                                // case "Growatt 5":
                                //  $inverter = "Growatt 5000TL-X Dual MPPT 5kW"; 
                                // break;
                                // case "Growatt 6":
                                //  $inverter = "Growatt 6000TL-X Dual MPPT 6kW"; 
                                // break;
                                case "Sungrow 3":
                                    $inverter = "Sungrow SG3K-D 3kW Dual MPPT WiFi"; 
                                    break;
                                case "Sungrow 5":
                                    $inverter = "Sungrow SG5K-D 5kW Dual MPPT WiFi"; 
                                    break;
                                case "Sungrow 8":
                                    $inverter = "Sungrow SG8K-D PREMIUM 8kW Dual MPPT WiFi";
                                    break;
                                case "Sungrow 10 3P":
                                    $inverter = "Sungrow SG-10KTL-MT 10kW Three Phase";
                                    break;
                                case "Sungrow 15 3P":
                                    $inverter = "Sungrow SG-15KTL-M 15kW Three Phase";
                                    break;
                                // default:
                                //     $inverter = "SolarEdge 10";
                                //     $extra_3 = "SolarEdge 10kW HD-Wave 1Ph Inverter";
                                // break;
                            }
                            if($i == 4 ){
                                $solar_pricing_options .= '<div style="clear:left"></div>';
                            }
                            // $pm = 100;
                            // $price_kw = round($pricings->{'customer_price_'.$i}/($pricings->{'total_kW_'.$i}*1000), 2);
                            if( $_REQUEST['vic_rebate'] == 'Yes'){
                                $str_vicreabte = 1850;
                                if( $_REQUEST['vic_loan'] == 'Yes'){
                                    $reabte_price = (Int)$pricings->{'customer_price_'.$i} - $str_vicreabte;
                                    $loan_price = (Int)$pricings->{'customer_price_'.$i} - $str_vicreabte - $str_vicreabte;
                                    // $solar_pricing_options .= '<p style="font-size: medium;" data-mce-style="font-size: medium;"></br><strong>Option #'.$i.':</strong> '.$pricings->{'total_kW_'.$i}.'kW = '.$pricings->{'total_panels_'.$i}.'x '.$pricings->{'panel_type_'.$i}.' + '.$pricings->{'inverter_type_'.$i}.(($pricings->{'extra_1_'.$i})? ' + '.$pricings->{'extra_1_'.$i} : '').(($pricings->{'extra_2_'.$i})? ' + '.$pricings->{'extra_2_'.$i} : '').' = $'.$pricings->{'customer_price_'.$i}.' - $1888 (Solar VIC Rebate)'.' - $1888 (Solar VIC Loan) = $'. $loan_price.' ($'.$price_kw.'/W)</p>';
                                    $solar_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float:left;width: 290px;background-color: white;border: 1px solid #f6ebd9;;margin-top: 25px;margin-right: 10px;padding: 5px;">
                                                                    <div class="op_header" style="width: 100%;position: inherit;">
                                                                        <div class="number-options" style="float: left;border: 2px solid #efb352;width: 9%;height: 25px;text-align: center;line-height: 27px;">' . $i . '</div>
                                                                        <div class="p" style="background-color: #efb352;float: right;width: 88.7%;border-radius: 0 20px 20px 0;height: 27px;osition: relative;border: 1px solid #efb352;text-align: center;line-height: 27px;">
                                                                            <p style="color: white;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 0px;">' . $pricings->{'total_kW_'.$i} . ' kW</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="select-inverter" style="clear: both;padding: 10px;;z-index: 7;position: relative;">
                                                                        <div style="font-size: 15px;">' .$pricings->{'total_panels_'.$i}. ' x ' . $pricings->{'panel_type_'.$i} . '</div>
                                                                        <div style="font-size: 13px;">' . $inverter . '</div>
                                                                        <div style="font-size: 13px;">' . (($pricings->{'extra_1_'.$i})? $pricings->{'extra_1_'.$i} : '<br>') . '</div>
                                                                        <div style="font-size: 13px;">' . (($pricings->{'extra_2_'.$i})? $pricings->{'extra_2_'.$i} : '<br>') . '</div>
                                                                        <div style="font-size: 13px;">' . $extra_3 . '</div>
                                                                    </div>
                                                                    <div class="total-price-item" style="background-color: #fef9f2;padding: 10px;color: #333;font-size: 12px;font-weight: 600;">
                                                                                <div><span >Full Purchase Price (inc GST)</span><span style="float: right;">$' . $pricings->{'sgp_system_price_'.$i} . '</span></div>
                                                                                <div><span >Less STCs (GST N/A)</span><span  style="float: right;color:red;">$-' . $pricings->{'stc_value_'.$i} . '</span></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span >Discounted Purchase Price</span><span  style="float:right;">$' . $pricings->{'customer_price_'.$i} . '</span></div>
                                                                                <div><span >Solar VIC Rebate</span><span  style="float: right;color:red">$-' .$str_vicreabte.'</span></div>
                                                                                <div><small>* Where eligible for the Solar VIC Rebate</small></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span style="margin-top:8px;">Out of Pocket Price <small>(inc. GST)</small></span><span  style="float: right;">$'.$reabte_price.'</span></div>
                                                                                <div><span >Interest Free Loan <small>(inc. GST)</small></span><span  style="float:right;color:red;">$-'.$str_vicreabte.'</span></div>
                                                                                <div><small>Payable to Solar VIC</small></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span >Up-front Price <small>(inc. GST)</small></span></div>
                                                                                <div class="total-price" style="text-align: center;border: 1px solid #ea9e23;margin-top: 15px;">
                                                                                    <span class="symbol" style="font-size: 20px;color: #3b3b3b;font-weight: 600;">$ </span>
                                                                                    <span class="amount" style="letter-spacing: -2px;font-size: 35px;color: #ea9e23;">'.$loan_price.'</span>
                                                                                </div>
                                                                    </div>
                                                                    <div class="op_footer" style="text-align: center;padding: 5px;background-color: #efb352;height: 3px;">
                                                                    </div>
                                                            </div>';
                                }else {
                                    $reabte_price = (Int)$pricings->{'customer_price_'.$i} - $str_vicreabte;
                                    // $solar_pricing_options .= '<p style="font-size: medium;" data-mce-style="font-size: medium;"></br><strong>Option #'.$i.':</strong> '.$pricings->{'total_kW_'.$i}.'kW = '.$pricings->{'total_panels_'.$i}.'x '.$pricings->{'panel_type_'.$i}.' + '.$pricings->{'inverter_type_'.$i}.(($pricings->{'extra_1_'.$i})? ' + '.$pricings->{'extra_1_'.$i} : '').(($pricings->{'extra_2_'.$i})? ' + '.$pricings->{'extra_2_'.$i} : '').' = $'.$pricings->{'customer_price_'.$i}.' - $1888 (Solar VIC Rebate) = $'. $reabte_price.' ($'.$price_kw.'/W)</p>';
                                    $solar_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float:left;width: 290px;background-color: white;border: 1px solid #f6ebd9;;margin-top: 25px;margin-right: 10px;padding: 5px;">
                                                                    <div class="op_header" style="width: 100%;position: inherit;">
                                                                        <div class="number-options" style="float: left;border: 2px solid #efb352;width: 9%;height: 25px;text-align: center;line-height: 27px;">' . $i . '</div>
                                                                        <div class="p" style="background-color: #efb352;float: right;width: 88.7%;border-radius: 0 20px 20px 0;height: 27px;osition: relative;border: 1px solid #efb352;text-align: center;line-height: 27px;">
                                                                            <p style="color: white;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 0px;">' . $pricings->{'total_kW_'.$i} . ' kW</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="select-inverter" style="clear: both;padding: 10px;;z-index: 7;position: relative;">
                                                                        <div style="font-size: 15px;">' .$pricings->{'total_panels_'.$i}. ' x ' . $pricings->{'panel_type_'.$i} . '</div>
                                                                        <div style="font-size: 13px;">' . $inverter . '</div>
                                                                        <div style="font-size: 13px;">' . (($pricings->{'extra_1_'.$i})? $pricings->{'extra_1_'.$i} : '<br>') . '</div>
                                                                        <div style="font-size: 13px;">' . (($pricings->{'extra_2_'.$i})? $pricings->{'extra_2_'.$i} : '<br>') . '</div>
                                                                        <div style="font-size: 13px;">' . $extra_3 . '</div>
                                                                    </div>
                                                                    <div class="total-price-item" style="background-color: #fef9f2;padding: 10px;color: #333;font-size: 12px;font-weight: 600;">
                                                                                <div><span >Full Purchase Price (inc GST)</span><span style="float: right;">$' . $pricings->{'sgp_system_price_'.$i} . '</span></div>
                                                                                <div><span >Less STCs (GST N/A)</span><span  style="float: right;color: red;">$-' . $pricings->{'stc_value_'.$i} . '</span></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span >Discounted Purchase Price</span><span  style="float:right;">$' . $pricings->{'customer_price_'.$i} . '</span></div>
                                                                                <div><span >Solar VIC Rebate</span><span  style="float: right;color:red">$-' .$str_vicreabte.'</span></div>
                                                                                <div><small>* Where eligible for the Solar VIC Rebate</small></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span style="margin-top:8px;">Out of Pocket Price <small>(inc. GST)</small></span></div>
                                                                                <div class="total-price" style="text-align: center;border: 1px solid #ea9e23;margin-top: 15px;">
                                                                                    <span class="symbol" style="font-size: 20px;color: #3b3b3b;font-weight: 600;">$ </span>
                                                                                    <span class="amount" style="letter-spacing: -2px;font-size: 35px;color: #ea9e23;">'.$reabte_price.'</span>
                                                                                </div>
                                                                    </div>
                                                                    <div class="op_footer" style="text-align: center;padding: 5px;background-color: #efb352;height: 3px;">
                                                                    </div>
                                                            </div>';
                                }
                            }else{
                                // $solar_pricing_options .= '<p style="font-size: medium;" data-mce-style="font-size: medium;"></br><strong>Option #'.$i.':</strong> '.$pricings->{'total_kW_'.$i}.'kW = '.$pricings->{'total_panels_'.$i}.'x '.$pricings->{'panel_type_'.$i}.' + '.$pricings->{'inverter_type_'.$i}.(($pricings->{'extra_1_'.$i})? ' + '.$pricings->{'extra_1_'.$i} : '').(($pricings->{'extra_2_'.$i})? ' + '.$pricings->{'extra_2_'.$i} : '').' = $'.$pricings->{'customer_price_'.$i}.' ($'.$price_kw.'/W)</p>';
                                $solar_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float:left;width: 290px;background-color: white;border: 1px solid #f6ebd9;;margin-top: 25px;margin-right: 10px;padding: 5px;">
                                                                <div class="op_header" style="width: 100%;position: inherit;">
                                                                    <div class="number-options" style="float: left;border: 2px solid #efb352;width: 9%;height: 25px;text-align: center;line-height: 27px;">' . $i . '</div>
                                                                    <div class="p" style="background-color: #efb352;float: right;width: 88.7%;border-radius: 0 20px 20px 0;height: 27px;osition: relative;border: 1px solid #efb352;text-align: center;line-height: 27px;">
                                                                        <p style="color: white;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 0px;">' . $pricings->{'total_kW_'.$i} . ' kW</p>
                                                                    </div>
                                                                </div>
                                                                <div class="select-inverter" style="clear: both;padding: 10px;;z-index: 7;position: relative;">
                                                                    <div style="font-size: 15px;">' .$pricings->{'total_panels_'.$i}. ' x ' . $pricings->{'panel_type_'.$i} . '</div>
                                                                    <div style="font-size: 13px;">' . $inverter . '</div>
                                                                    <div style="font-size: 13px;">' . (($pricings->{'extra_1_'.$i})? $pricings->{'extra_1_'.$i} : '<br>') . '</div>
                                                                    <div style="font-size: 13px;">' . (($pricings->{'extra_2_'.$i})? $pricings->{'extra_2_'.$i} : '<br>') . '</div>
                                                                    <div style="font-size: 13px;">' . $extra_3 . '</div>
                                                                    </div>
                                                                <div class="total-price-item" style="background-color: #fef9f2;padding: 10px;color: #333;font-size: 12px;font-weight: 600;">
                                                                            <div><span >Full Purchase Price (inc GST)</span><span style="float: right;">$' . $pricings->{'sgp_system_price_'.$i} . '</span></div>
                                                                            <div><span >Less STCs (GST N/A)</span><span  style="float: right;color: red;">$-' . $pricings->{'stc_value_'.$i} . '</span></div>
                                                                            <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                            <div><span >Discounted Purchase Price</span></div>
                                                                            <div class="total-price" style="text-align: center;border: 1px solid #ea9e23;margin-top: 15px;">
                                                                                <span class="symbol" style="font-size: 20px;color: #3b3b3b;font-weight: 600;">$ </span>
                                                                                <span class="amount" style="letter-spacing: -2px;font-size: 35px;color: #ea9e23;">'.$pricings->{'customer_price_'.$i}.'</span>
                                                                            </div>
                                                                </div>
                                                                <div class="op_footer" style="text-align: center;padding: 5px;background-color: #efb352;height: 3px;">
                                                                </div>
                                                        </div>';
                            }
                        }
                    }
                    $solar_pricing_options .= '<div style="clear:left"></div>';
                    // $body_html = str_replace("\$solar_pricing_options",  $solar_pricing_options , $body_html);
                }
                $this->bean->description_html = str_replace("\$solar_pricing_options",  $solar_pricing_options , $this->bean->description_html);

                $meter_phase_c  = array('','Single Phase','Two Phase (Rural Only)','Three Phase');
                $distributor_c = array("0"=>"",
                        "4" => "Citipower",
                        "5" => "Jemena",
                        "6"=>"Powercor",
                        "7"=>"SP Ausnet",
                        "8"=>"United Energy Distribution",
                        "1"=>"Western Power",
                        "13"=>"South Australia Power Network",
                        "2"=>"Energex",
                        "3" => "Ergon",
                        "9" => "Essential Energy",
                        "10"=>"Ausgrid",
                        "12"=>"Endeavour Energy",
                        "11"=>"ActewAGL",
                        "14"=>"AusNet Electricity Services Pty Ltd",
                );
                
                if(  $_REQUEST['storey'] == 'Double Storey'){
                    $gutter_height_c = "3-5m";
                }else {
                    $gutter_height_c = "0-3m";
                    // $gutter_height_c = array( "",
                    //                     '0-3m',
                    //                     '3-5m',
                    //                     '5m - 10m',
                    //                     '10m - 15m',
                    //                     '15m+',
                    //                     'Other');
                }
                $roof_type_c    = array('Tin'=>'Tin',
                                        'Tile'=>'Tile',
                                        'klip_loc'=>'Klip Loc',
                                        'Concrete'=>'Concrete',
                                        'Trim_Deck'=>'Trim Deck',
                                        'Insulated'=>'Insulated',
                                        'Asbestos'=>'Asbestos',
                                        'Ground_Mount'=>'Ground Mount',
                                        'Terracotta'=>'Terracotta',
                                        'Other'=>'Other');
                $roof_pitch_c    = array('0-25 Degrees'=>'0-25 Degrees',
                                        '25-30 Degrees'=>'25-30 Degrees',
                                        '30+ Degrees'=>'30+ Degrees');
                
                $this->bean->description_html = str_replace("\$aos_quotes_meter_phase_c",  $meter_phase_c[$focus->meter_phase_c] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_distributor_c",  $distributor_c[$focus->distributor_c] , $this->bean->description_html);
                // $this->bean->description_html = str_replace("\$aos_quotes_first_solar_c",   'No' , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_gutter_height_c",   $gutter_height_c , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_roof_pitch_c",   $roof_pitch_c[$focus->roof_pitch_c] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_roof_type_c",  $roof_type_c[$focus->roof_type_c] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_main_switch_c",$focus->main_switch_c , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_external_internal_c", $focus->external_or_internal_c, $this->bean->description_html);

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $this->bean->name,
                        'body_html' => $this->bean->description_html,
                        'body' => $this->bean->description_html,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '1d4462d6-1a54-45af-3a7c-5d647d113e0e' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact->first_name, $body);
                $body = str_replace("\$aos_quote_id", $_REQUEST['record_id'], $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //end
            //Thienpb code -- solar pricing options
            if($_REQUEST['email_type'] == "send_pe_solar_pricing"){
                $macro_nv = array();
                $focusName = "AOS_Quotes";
                $quote = new AOS_Quotes();
                $focus = $quote->retrieve($_REQUEST['record_id']);
                $this->bean->return_module = 'AOS_Quotes';
                $this->bean->return_id = $focus->id;
                $lead =  new Lead();
                $lead->retrieve($focus->leads_aos_quotes_1leads_ida);

                $contact =  new Contact();
                $contact->retrieve($focus->billing_contact_id);

                if(!$focus->id) return;
                /**
                 * @var EmailTemplate $emailTemplate
                 */

                $emailTemplate = BeanFactory::getBean(
                    'EmailTemplates',
                    //'ba4a72df-d9e3-7a20-d7b2-5d5bb366c7a4'
                    '9d9f03ae-fe75-68d0-72ad-5d5b95cda15b'
                );
                $name = $emailTemplate->subject;
                $description_html = $emailTemplate->body_html;
                $description = $emailTemplate->body;

                $attachmentBeans = $emailTemplate->getAttachments();

                if($attachmentBeans) {
                    $this->bean->status = "draft";
                    $this->bean->save();
                    foreach($attachmentBeans as $attachmentBean) {
                        $noteTemplate = clone $attachmentBean;
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true;
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';

                        $noteFile = new UploadFile();
                        $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                        $noteTemplate->save();
                        $this->bean->attachNote($noteTemplate);
                    }
                }

                $source = realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c;
                $filesDesigns = $this->check_exist_file($source, 'Design');
                $filesSolar = $this->check_exist_file($source, 'Quote_');
                $filesSolar_Pdf = [];
                foreach ($filesSolar as $value) {
                    if (strpos(strtolower($value), "pdf") !== false) {
                        $filesSolar_Pdf[] =   $value;
                    }
                }
                $all_files_send  = array_merge($filesDesigns,$filesSolar_Pdf);
                foreach ($all_files_send as $file) {
                    $this->bean->save();
                    $noteTemplate = new Note();
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true;
                    $noteTemplate->parent_id = $this->bean->id; 
                    $noteTemplate->parent_type = 'Emails';
                    $noteTemplate->date_entered = '';
                    $noteTemplate->filename = $file;
                    $noteTemplate->name = $file;   
                    $noteTemplate->save();
                    global $sugar_config;
                    $destination = $sugar_config['upload_dir'].$noteTemplate->id;
                    $sourcefile = $source.'/'.$file;

                    if(strpos(strtolower($file), "png") !== false) {
                        $noteTemplate->file_mime_type = 'image/png';
                    } elseif (strpos(strtolower($file), "pdf") !== false) {
                        $noteTemplate->file_mime_type = 'image/jpg';
                    } else {
                        $noteTemplate->file_mime_type = 'image/jpg';
                    }
                    if (!symlink($sourcefile, $destination)) {
                    $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                    }
                    $this->bean->attachNote($noteTemplate);
                }
                
                $this->bean->to_addrs_names = $lead->first_name.' '.$lead->last_name." <$lead->email1>";
                $this->bean->name = $name;
                $this->bean->description_html = $description_html;
                $this->bean->description = $description;

                //VUT - S - replace Quote Inputs  
                if ($quote->quote_note_inputs_c !='') {
                    $solar_quote_input = json_decode(html_entity_decode($quote->quote_note_inputs_c), true);
                    if (count(array_filter($solar_quote_input)) == 0) {
                        $this->bean->description_html = htmlspecialchars(preg_replace('/(?si)<p id="sugar_text_change_p_table"(.*)<=?\/p>/U', '',html_entity_decode($this->bean->description_html)));
                        // $this->bean->description_html = str_replace("\$table_solar_quote_inputs", '' , $this->bean->description_html);
                    } else {
                        $this->bean->description_html = $this->renderTableQuoteInputSolarPricing($solar_quote_input, $this->bean->description_html);
                    }
                } else {
                    $this->bean->description_html = htmlspecialchars(preg_replace('/(?si)<p id="sugar_text_change_p_table"(.*)<=?\/p>/U', '',html_entity_decode($this->bean->description_html)));
                    // $this->bean->description_html = str_replace("\$table_solar_quote_inputs", '' , $this->bean->description_html);
                } 

                //VUT - E - replace Quote Inputs 
                //replace data for subject - VUT - 2020/03/04
                $this->bean->name = str_replace("\$aos_quotes_billing_account",  $focus->billing_account, $this->bean->name);
                $this->bean->name = str_replace("\$aos_quotes_site_detail_addr__city_c",  $focus->install_address_city_c , $this->bean->name);
                $this->bean->name = str_replace("\$aos_quotes_site_detail_addr__state_c ",  $focus->install_address_state_c.' ' , $this->bean->name);
                
                //replace data for body
                $this->bean->description_html = str_replace("\$contact_first_name",  $contact->first_name , $this->bean->description_html);
                
                $this->bean->description_html = str_replace("\$aos_quotes_installation address_c", $_REQUEST['address'] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_stroreys_c", $_REQUEST['storey'] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_preferred_c", "No" , $this->bean->description_html);

                if(   strpos($_REQUEST['address'],"VIC") == TRUE){
                    $html_vic = '<table style="text-align:left;border-collapse:collapse;width:735px;">
                                <tbody>
                                <tr>
                                    <td style="padding: 5px; border: .5px solid #8a8a8a;">Want to apply Solar VIC Rebate to your solar pricing?</td>
                                    <td style="padding: 5px; border: .5px solid #8a8a8a;width: 47%">'. $_REQUEST['vic_rebate'] .'</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px; border: .5px solid #8a8a8a;">Want to apply Solar VIC Loan to your solar pricing?</td>
                                    <td style="padding: 5px; border: .5px solid #8a8a8a;" >'.  $_REQUEST['vic_loan'] .'</td>
                                </tr>
                                </tbody></table>';
                    $this->bean->description_html = str_replace("\$aos_solar_vic_loan_c", $html_vic ,$this->bean->description_html);
                    // $body_html = str_replace("\$aos_quotes_loan_c",    ($vic_loan == "yes_loan") ? "Yes": 'No' , $body_html);
                }else {
                    $this->bean->description_html = str_replace("\$aos_solar_vic_loan_c"," " ,$this->bean->description_html);
                }

                $pricing_options = $focus->own_solar_pv_pricing_c;

                $solar_pricing_options = '';

                if($pricing_options != ''){
                    $pricings = json_decode(html_entity_decode($pricing_options));
                    // Inverter line
                    $inverter_line = (int)$pricings->sl_inverter_line + 1;
                    // Accesory line
                    $accessory_line = (int)$pricings->sl_accessory_line + 1;

                    $productBean = BeanFactory::newBean('AOS_Products');

                    for ($i=1; $i < 7 ; $i++) {
                        
                        // Inverter optimize
                        $inverter_sol = array();
                        for ($line = 1; $line <  $inverter_line; $line++){
                            $tmpName = 'inverter_sl_type'.$line.'_'.$i;
                            if($pricings->$tmpName != ''){
                                // Get Product name from DB
                                $inverter_name = $this->getProductNameByShortName($productBean, $pricings->$tmpName);
                                // Check exist on array
                                if($inverter_sol[$inverter_name] != null){
                                    $newQty = $inverter_sol[$inverter_name] + 1;
                                    $inverter_sol[$inverter_name] = $newQty;
                                } else {
                                    $inverter_sol[$inverter_name] = 1;
                                }
                            }
                        }
                       
                        // Accessory optimize
                        $accessory_sol = array();
                        for ($line = 1; $line < $accessory_line; $line++){
                            $tmpName = 'sl_accessory'.$line.'_'.$i;
                            if($pricings->$tmpName != ''){
                                // Get Product name from DB
                                $accessory_name = $this->getProductNameByShortName($productBean, $pricings->$tmpName);
                                // Check exist on array
                                if($accessory_sol[$accessory_name] != null){
                                    $newQty = $accessory_sol[$accessory_name] + 1;
                                    $accessory_sol[$accessory_name] = $newQty;
                                } else {
                                    $accessory_sol[$accessory_name] = 1;
                                }
                            }
                        }
                        if($i == 4 ){
                            $solar_pricing_options .= '<div style="clear:left"></div>';
                        }
                         // STCs price
                         $stc_price = $this->calcStcs($productBean, $pricings->{'number_sl_stcs_'.$i});
                        // Check if option has value to render
                        if($pricings->{'total_sl_'.$i} != "" || $pricings->{'total_sl_'.$i} != 0){
                            $grandTotal = substr($pricings->{'total_sl_'.$i}, 0, -3);
                            if( $_REQUEST['vic_rebate'] == 'Yes'){
                                $str_vicreabte = 1850;
                                if( $_REQUEST['vic_loan'] == 'Yes'){
                                    $reabte_price = (Int)$pricings->{'total_sl_'.$i} - $str_vicreabte;
                                    $loan_price = (Int)$pricings->{'total_sl_'.$i} - $str_vicreabte - $str_vicreabte;
                                    $solar_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float:left;width: 290px;background-color: white;border: 1px solid #f6ebd9;;margin-top: 25px;margin-right: 10px;padding: 5px;">
                                                                    <div class="op_header" style="width: 100%;position: inherit;">
                                                                        <div class="number-options" style="float: left;border: 2px solid #efb352;width: 9%;height: 25px;text-align: center;line-height: 27px;">' . $i . '</div>
                                                                        <div class="p" style="background-color: #efb352;float: right;width: 88.7%;border-radius: 0 20px 20px 0;height: 27px;osition: relative;border: 1px solid #efb352;text-align: center;line-height: 27px;">
                                                                            <p style="color: white;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 0px;">' . $pricings->{'total_sl_kW_'.$i} . ' kW</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="select-inverter" style="clear: both;padding: 10px;;z-index: 7;position: relative;">
                                                                        <div style="font-size: 15px;">' .$pricings->{'total_sl_panels_'.$i}. ' x ' . $pricings->{'panel_sl_type_'.$i} . '</div>';
                                                                        // Render Accessories
                                                                        foreach ($inverter_sol as $key => $value) {
                                                                            $solar_pricing_options .= '<div style="font-size: 13px;">'.$value.' x '.$key.'</div>';
                                                                        }                                                                
                                                                        // Render Accessories
                                                                        foreach ($accessory_sol as $key => $value) {
                                                                            $solar_pricing_options .= '<div style="font-size: 13px;">'. $value.'x '.$key .'</div>';
                                                                        }                                   
                                                                        
                                    $solar_pricing_options .=       '</div>
                                                                    <div class="total-price-item" style="background-color: #fef9f2;padding: 10px;color: #333;font-size: 12px;font-weight: 600;">
                                                                                <div><span >Full Purchase Price (inc GST)</span><span style="float: right;">$' . ((float)str_replace(',', '', $grandTotal) - $stc_price)  . '</span></div>
                                                                                <div><span >Less STCs (GST N/A)</span><span  style="float: right;color:red;">$' . $stc_price . '</span></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span >Discounted Purchase Price</span><span  style="float:right;">$' . (float)str_replace(',', '', $grandTotal) . '</span></div>
                                                                                <div><span >Solar VIC Rebate</span><span  style="float: right;color:red">$-' .$str_vicreabte.'</span></div>
                                                                                <div><small>* Where eligible for the Solar VIC Rebate</small></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span style="margin-top:8px;">Out of Pocket Price <small>(inc. GST)</small></span><span  style="float: right;">$'.$reabte_price.'</span></div>
                                                                                <div><span >Interest Free Loan <small>(inc. GST)</small></span><span  style="float:right;color:red;">$-'.$str_vicreabte.'</span></div>
                                                                                <div><small>Payable to Solar VIC</small></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span >Up-front Price <small>(inc. GST)</small></span></div>
                                                                                <div class="total-price" style="text-align: center;border: 1px solid #ea9e23;margin-top: 15px;">
                                                                                    <span class="symbol" style="font-size: 20px;color: #3b3b3b;font-weight: 600;">$ </span>
                                                                                    <span class="amount" style="letter-spacing: -2px;font-size: 35px;color: #ea9e23;">'.$loan_price.'</span>
                                                                                </div>
                                                                    </div>
                                                                    <div class="op_footer" style="text-align: center;padding: 5px;background-color: #efb352;height: 3px;">
                                                                    </div>
                                                            </div>';
                                }else {
                                    $reabte_price = (Int)$pricings->{'customer_price_'.$i} - $str_vicreabte;
                                    // $solar_pricing_options .= '<p style="font-size: medium;" data-mce-style="font-size: medium;"></br><strong>Option #'.$i.':</strong> '.$pricings->{'total_kW_'.$i}.'kW = '.$pricings->{'total_panels_'.$i}.'x '.$pricings->{'panel_type_'.$i}.' + '.$pricings->{'inverter_type_'.$i}.(($pricings->{'extra_1_'.$i})? ' + '.$pricings->{'extra_1_'.$i} : '').(($pricings->{'extra_2_'.$i})? ' + '.$pricings->{'extra_2_'.$i} : '').' = $'.$pricings->{'customer_price_'.$i}.' - $1888 (Solar VIC Rebate) = $'. $reabte_price.' ($'.$price_kw.'/W)</p>';
                                    $solar_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float:left;width: 290px;background-color: white;border: 1px solid #f6ebd9;;margin-top: 25px;margin-right: 10px;padding: 5px;">
                                                                    <div class="op_header" style="width: 100%;position: inherit;">
                                                                        <div class="number-options" style="float: left;border: 2px solid #efb352;width: 9%;height: 25px;text-align: center;line-height: 27px;">' . $i . '</div>
                                                                        <div class="p" style="background-color: #efb352;float: right;width: 88.7%;border-radius: 0 20px 20px 0;height: 27px;osition: relative;border: 1px solid #efb352;text-align: center;line-height: 27px;">
                                                                            <p style="color: white;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 0px;">' . $pricings->{'total_kW_'.$i} . ' kW</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="select-inverter" style="clear: both;padding: 10px;;z-index: 7;position: relative;">
                                                                        <div style="font-size: 15px;">' .$pricings->{'total_sl_panels_'.$i}. ' x ' . $pricings->{'panel_sl_type_'.$i} . '</div>';
                                                                        // Render Accessories
                                                                        foreach ($inverter_sol as $key => $value) {
                                                                            $solar_pricing_options .= '<div style="font-size: 13px;">'.$value.' x '.$key.'</div>';
                                                                        }                                                                
                                                                        // Render Accessories
                                                                        foreach ($accessory_sol as $key => $value) {
                                                                            $solar_pricing_options .= '<div style="font-size: 13px;">'.$value.'x '.$key.'</div>';
                                                                        }                                   
                                                                        
                                    $solar_pricing_options .=       '</div>
                                                                    <div class="total-price-item" style="background-color: #fef9f2;padding: 10px;color: #333;font-size: 12px;font-weight: 600;">
                                                                                <div><span >Full Purchase Price (inc GST)</span><span style="float: right;">$' . ((float)str_replace(',', '', $grandTotal) - $stc_price)  . '</span></div>
                                                                                <div><span >Less STCs (GST N/A)</span><span  style="float: right;color: red;">$-' . $stc_price . '</span></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span >Discounted Purchase Price</span><span  style="float:right;">$' . (float)str_replace(',', '', $grandTotal) . '</span></div>
                                                                                <div><span >Solar VIC Rebate</span><span  style="float: right;color:red">$-' .$str_vicreabte.'</span></div>
                                                                                <div><small>* Where eligible for the Solar VIC Rebate</small></div>
                                                                                <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                                <div><span style="margin-top:8px;">Out of Pocket Price <small>(inc. GST)</small></span></div>
                                                                                <div class="total-price" style="text-align: center;border: 1px solid #ea9e23;margin-top: 15px;">
                                                                                    <span class="symbol" style="font-size: 20px;color: #3b3b3b;font-weight: 600;">$ </span>
                                                                                    <span class="amount" style="letter-spacing: -2px;font-size: 35px;color: #ea9e23;">'.$reabte_price.'</span>
                                                                                </div>
                                                                    </div>
                                                                    <div class="op_footer" style="text-align: center;padding: 5px;background-color: #efb352;height: 3px;">
                                                                    </div>
                                                            </div>';
                                }
                            }else{
                                // $solar_pricing_options .= '<p style="font-size: medium;" data-mce-style="font-size: medium;"></br><strong>Option #'.$i.':</strong> '.$pricings->{'total_kW_'.$i}.'kW = '.$pricings->{'total_panels_'.$i}.'x '.$pricings->{'panel_type_'.$i}.' + '.$pricings->{'inverter_type_'.$i}.(($pricings->{'extra_1_'.$i})? ' + '.$pricings->{'extra_1_'.$i} : '').(($pricings->{'extra_2_'.$i})? ' + '.$pricings->{'extra_2_'.$i} : '').' = $'.$pricings->{'customer_price_'.$i}.' ($'.$price_kw.'/W)</p>';
                                $solar_pricing_options .= '<div class="col-md-4 col-sm-12 col-xs-12 select_options" style="float:left;width: 290px;background-color: white;border: 1px solid #f6ebd9;;margin-top: 25px;margin-right: 10px;padding: 5px;">
                                                                <div class="op_header" style="width: 100%;position: inherit;">
                                                                    <div class="number-options" style="float: left;border: 2px solid #efb352;width: 9%;height: 25px;text-align: center;line-height: 27px;">' . $i . '</div>
                                                                    <div class="p" style="background-color: #efb352;float: right;width: 88.7%;border-radius: 0 20px 20px 0;height: 27px;osition: relative;border: 1px solid #efb352;text-align: center;line-height: 27px;">
                                                                        <p style="color: white;font-family: oswaldregular;font-size: 20px;font-weight: 500;margin-left: 5px;margin-top: 0px;">' . $pricings->{'total_sl_kW_'.$i} . ' kW</p>
                                                                    </div>
                                                                </div>
                                                                <div class="select-inverter" style="clear: both;padding: 10px;;z-index: 7;position: relative;">
                                                                    <div style="font-size: 15px;">' .$pricings->{'total_sl_panels_'.$i}. ' x ' . $pricings->{'panel_sl_type_'.$i} . '</div>';
                                                                    // Render Accessories
                                                                    foreach ($inverter_sol as $key => $value) {
                                                                        $solar_pricing_options .= '<div style="font-size: 13px;">'.$value.' x '.$key.'</div>';
                                                                    }                                                                
                                                                    // Render Accessories
                                                                    foreach ($accessory_sol as $key => $value) {
                                                                        $solar_pricing_options .= '<div style="font-size: 13px;">'.$value.'x '.$key .'</div>';
                                                                    }                                    
                                                                    
                                $solar_pricing_options .=       '</div>
                                                                <div class="total-price-item" style="background-color: #fef9f2;padding: 10px;color: #333;font-size: 12px;font-weight: 600;">
                                                                            <div><span >Full Purchase Price (inc GST)</span><span style="float: right;">$' . ((float)str_replace(',', '', $grandTotal) - $stc_price) . '</span></div>
                                                                            <div><span >Less STCs (GST N/A)</span><span  style="float: right;color: red;">$' . $stc_price . '</span></div>
                                                                            <div style="height: 1px;border-bottom: 1px solid #c7c1c1;margin: 5px 0px 5px 0px;"></div>
                                                                            <div><span >Discounted Purchase Price</span></div>
                                                                            <div class="total-price" style="text-align: center;border: 1px solid #ea9e23;margin-top: 15px;">
                                                                                <span class="symbol" style="font-size: 20px;color: #3b3b3b;font-weight: 600;">$ </span>
                                                                                <span class="amount" style="letter-spacing: -2px;font-size: 35px;color: #ea9e23;">'. (float)str_replace(',', '', $grandTotal) .'</span>
                                                                            </div>
                                                                </div>
                                                                <div class="op_footer" style="text-align: center;padding: 5px;background-color: #efb352;height: 3px;">
                                                                </div>
                                                        </div>';
                            }
                        }
                    }
                    $solar_pricing_options .= '<div style="clear:left"></div>';
                    // $body_html = str_replace("\$solar_pricing_options",  $solar_pricing_options , $body_html);
                }
                $this->bean->description_html = str_replace("\$solar_pricing_options",  $solar_pricing_options , $this->bean->description_html);

                $meter_phase_c  = array('','Single Phase','Two Phase (Rural Only)','Three Phase');
                $distributor_c = array("0"=>"",
                        "4" => "Citipower",
                        "5" => "Jemena",
                        "6"=>"Powercor",
                        "7"=>"SP Ausnet",
                        "8"=>"United Energy Distribution",
                        "1"=>"Western Power",
                        "13"=>"South Australia Power Network",
                        "2"=>"Energex",
                        "3" => "Ergon",
                        "9" => "Essential Energy",
                        "10"=>"Ausgrid",
                        "12"=>"Endeavour Energy",
                        "11"=>"ActewAGL",
                        "14"=>"AusNet Electricity Services Pty Ltd",
                );
                
                if(  $_REQUEST['storey'] == 'Double Storey'){
                    $gutter_height_c = "3-5m";
                }else {
                    $gutter_height_c = "0-3m";
                }
                $roof_type_c    = array('Tin'=>'Tin',
                                        'Tile'=>'Tile',
                                        'klip_loc'=>'Klip Loc',
                                        'Concrete'=>'Concrete',
                                        'Trim_Deck'=>'Trim Deck',
                                        'Insulated'=>'Insulated',
                                        'Asbestos'=>'Asbestos',
                                        'Ground_Mount'=>'Ground Mount',
                                        'Terracotta'=>'Terracotta',
                                        'Other'=>'Other');
                $roof_pitch_c    = array('0-25 Degrees'=>'0-25 Degrees',
                                        '25-30 Degrees'=>'25-30 Degrees',
                                        '30+ Degrees'=>'30+ Degrees');
                
                $this->bean->description_html = str_replace("\$aos_quotes_meter_phase_c",  $meter_phase_c[$focus->meter_phase_c] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_distributor_c",  $distributor_c[$focus->distributor_c] , $this->bean->description_html);
                // $this->bean->description_html = str_replace("\$aos_quotes_first_solar_c",   'No' , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_gutter_height_c",   $gutter_height_c , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_roof_pitch_c",   $roof_pitch_c[$focus->roof_pitch_c] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_roof_type_c",  $roof_type_c[$focus->roof_type_c] , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_main_switch_c",$focus->main_switch_c , $this->bean->description_html);
                $this->bean->description_html = str_replace("\$aos_quotes_external_internal_c", $focus->external_or_internal_c, $this->bean->description_html);

                $templateData = $emailTemplate->parse_email_template(
                    array(
                        'subject' => $this->bean->name,
                        'body_html' => $this->bean->description_html,
                        'body' => $this->bean->description_html,
                    ),
                    $focusName,
                    $focus,
                    $macro_nv
                );

                $this->bean->name = $templateData['subject'];
                $this->bean->description_html = $templateData['body_html'];
                $this->bean->description = $templateData['body_html'];

                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";
                //start - code render sms_template  
                global $current_user;
                $smsTemplate = BeanFactory::getBean(
                    'pe_smstemplate',
                    '1d4462d6-1a54-45af-3a7c-5d647d113e0e' 
                );
                $body =  $smsTemplate->body_c;
                $body = str_replace("\$first_name", $contact->first_name, $body);
                $body = str_replace("\$aos_quote_id", $_REQUEST['record_id'], $body);
                $smsTemplate->body_c = $body;
                $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
                //end - code render sms_template
            }
            //end
            
        }

        //Dung code
        if($_REQUEST['email_type'] == "reminder-email"){
            $macro_nv = array();
            $focusName = "Opportunities";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                '82675a55-a8ea-0439-7ad8-5af3b5b901e5'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );

            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';

                    //$noteTemplate->file_mime_type = 'application/pdf';
                    //$noteTemplate->filename = $att;
                    //$noteTemplate->name = $att;

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }

            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body_html'];
            $focus_user = BeanFactory::getBean('Users', $focus->assigned_user_id);
            $this->bean->description_html = str_replace("\$contact_first_name", explode(" ", $focus->account_name)[0] , $this->bean->description_html);
            $this->bean->description_html = str_replace("\$AssignedUser Number", $focus_user->phone_mobile, $this->bean->description_html);
            if(strpos(strtolower($focus->name),'sanden')){
                $this->bean->description_html = str_replace("\$ProductType", 'Sanden', $this->bean->description_html);
            }else {
                $this->bean->description_html = str_replace("\$ProductType", 'Daikin', $this->bean->description_html);
            }
        }
        //End Dung Code

        //DUng code - popup email solar design complete

        if($_REQUEST['email_type'] == "solar_design_complete" || $_REQUEST['email_type'] == "send_tesla_quote"){
            $macro_nv = array();
            $focusName = "AOS_Quotes";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['quote_id']);
            

            if(!$focus->id) return;
            //VUT-create mirror lead 
            // $mirror_lead = $focus; 
            
            /**
             * @var EmailTemplate $emailTemplate
             */
            if($_REQUEST['email_type'] == "send_tesla_quote"){
                $emailTemplate_id = '2316382f-a235-beb5-e12e-5c1862686a24';
            }else{
                // change logic - use only this template
                $emailTemplate_id = '64084c36-9ba4-68fd-20c8-5ecc3b51c593';
            }

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                $emailTemplate_id
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;

            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );

            $this->bean->emails_email_templates_idb = $emailTemplate_id;
            $attachmentBeans = $emailTemplate->getAttachments();

            // Thienpb code set email template by sg inverter model
            if($_REQUEST['email_type'] != "send_tesla_quote"){
                if($_REQUEST['inverter_model'] == 'Fronius_Primo'){
                    $emailTemplate1 = BeanFactory::getBean(
                        'EmailTemplates',
                        '3742953d-1318-43cb-00e3-5bbaab707bcd'
                    );
                    $attachmentBeans = array_merge($attachmentBeans,$emailTemplate1->getAttachments()) ;
                }elseif($_REQUEST['inverter_model'] == 'Fronius_Symo'){
                    $emailTemplate2 = BeanFactory::getBean(
                        'EmailTemplates',
                        '180953f6-3dda-b10e-8f39-5bbbfe2bec38'
                    );
                    $attachmentBeans = array_merge($attachmentBeans,$emailTemplate2->getAttachments()) ;

                }elseif($_REQUEST['inverter_model'] == 'SolarEdge'){
                    $emailTemplate3 = BeanFactory::getBean(
                        'EmailTemplates',
                        '12fb3725-0581-cf2c-18ed-5bbbfe6b0089'
                    );
                    $attachmentBeans = array_merge($attachmentBeans,$emailTemplate3->getAttachments()) ;
                }
            }
            //end

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }

            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body_html'];

            // Solve logic for Attachment 
            // if($_REQUEST['quote_id']!="") {
            //     $quote = new AOS_Quotes();
            //     $quote = $quote->retrieve($_REQUEST['quote_id']);

            // }
            // if($quote->id != ""){
            //     $focus = $quote;
                $this->bean->description_html = str_replace("\$aos_quotes_id",$_REQUEST['quote_id'], $this->bean->description_html);
                $this->bean->return_module = 'AOS_Quotes';
                $this->bean->return_id = $focus->id;
            // }

            if($_REQUEST['quote_id'] != "") {
                $file_attachmens = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c ."/");
            } else {
                $file_attachmens = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->installation_pictures_c ."/");
            }

            $noteArray = array();
            //thienpb code - block file for email
            if($focus->block_files_for_email_c != ""){
                $file_attachmens = array_diff($file_attachmens,json_decode(htmlspecialchars_decode($focus->block_files_for_email_c)));
            }
            
            $quote_file_exist = false;
            $num_quote_SG = $focus->solargain_quote_number_c;
            $num_quote_SG_Tesla = $focus->solargain_tesla_quote_number_c ;

            if (count($file_attachmens)>0) foreach ($file_attachmens as $att){
                // Create Note
                $source = "";
                //if(strpos($att, "Bill") !== false) continue;
                //check button send tesla and quote nomal
                if($_REQUEST['email_type'] == "solar_design_complete" && $num_quote_SG != '' && ( strpos($att, 'Quote_#'.$num_quote_SG) !== false  || strpos($att, 'Design_') !== false ) ) {
                        if($_REQUEST['quote_id'] != "") {
                            $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c ."/" . $att ;
                        } else {
                            $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->installation_pictures_c ."/" . $att ;
                        }
                }elseif ( $_REQUEST['email_type'] == "send_tesla_quote" && $num_quote_SG_Tesla != '' && (strpos($att, 'Quote_#'.$num_quote_SG_Tesla)  !== false ) ) {
                    if($_REQUEST['quote_id'] != "") {
                        $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c ."/" . $att ;
                    } else {
                        $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->installation_pictures_c ."/" . $att ;
                    }
                }
                
                if(!is_file($source)) continue;
                // if($_REQUEST['email_type'] == "send_tesla_quote"  && strpos($att, 'Quote_') === false){
                //     continue;
                // }
                $noteTemplate = new Note();
                $noteTemplate->id = create_guid();
                $noteTemplate->new_with_id = true; // duplicating the note with files
                $noteTemplate->parent_id = $this->bean->id;
                $noteTemplate->parent_type = 'Emails';
                $noteTemplate->date_entered = '';       
                $noteTemplate->file_mime_type = 'application/pdf';
                $noteTemplate->filename = $att;
                $noteTemplate->name = $att;

                $noteTemplate->save();

                $destination = realpath(dirname(__FILE__) . '/../../').'/upload/'.$noteTemplate->id;
                //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                //copy( $source, $destination);
                if (!symlink($source, $destination)) {
                    $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                }
                $noteArray[] = $noteTemplate;
                $this->bean->attachNote($noteTemplate);
                $noteArray[] = $noteTemplate; 

                // Special check if source has Quote file
                if(strpos($att, 'Quote_') !== false){
                    $quote_file_exist = true;
                }
                if($quote_file_exist){
                    $moduleName = "Leads";
                    $lead = BeanFactory::getBean($moduleName, $_REQUEST['lead_id']);
                    $first_name = $lead->first_name;
                    $this->bean->send_sms = 1;
    
                    $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $lead->phone_mobile));
                    $phone_number = preg_replace("/^61/", "+61", $phone_number);
                    $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                    $this->bean->number_receive_sms = "matthew_paul_client";
                    //$this->bean->sms_message = "Hi $first_name your pure-electric quote has been sent to your inbox, if you can't find it please check your spam folder";
                    $assigned_name = $focus->assigned_user_name;
                    $this->bean->sms_message = "Hi $first_name, Your Solar PV quote has been prepared and sent to your email inbox. If you can't find it, check your spam folder and if no success still, please don't hesitate to contact us. Regards, $assigned_name";
                }
                
            }

            //start - code render sms_template  
            global $current_user;
            $smsTemplate = BeanFactory::getBean(
                'pe_smstemplate',
                '5999d6d4-d1b7-161d-c1eb-5ecc6b2df036' 
            );
            $body =  $smsTemplate->body_c;
            $body = str_replace("\$first_name", $focus->account_firstname_c, $body);
            $smsTemplate->body_c = $body;
            $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
            $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
            $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
            //end - code render sms_template
        }

        //End Dung code - popup email solar design complete

        //Dung code - popup email street_address_request_email
        if($_REQUEST['email_type'] == "street_address_request_email"){
            $macro_nv = array();
            $focusName = "Leads";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                '383cde5c-de72-3902-2a9a-5b5008c452d0'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );
            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body_html'];
            $this->bean->emails_email_templates_idb = '383cde5c-de72-3902-2a9a-5b5008c452d0';
            ///
            if(isset($_REQUEST['sms_received']) && $_REQUEST['sms_received'] != ""){
                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $_REQUEST['sms_received']));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
            }
        }
        //End dung code - poptup email street_address_request_email

        if($_REQUEST['email_type'] == "nexura-design"){
            $macro_nv = array();
            $focusName = "Leads";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                '5ad80115-b756-ea3e-ca83-5abb005602bf'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );

            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';

                    //$noteTemplate->file_mime_type = 'application/pdf';
                    //$noteTemplate->filename = $att;
                    //$noteTemplate->name = $att;

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }

            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body_html'];

            $link_upload_files = 'https://pure-electric.com.au/pedaikinform-new/confirm-to-lead?lead-id=' . $focus->id;
            $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">File Upload Link Here</a>';
            $this->bean->description_html = str_replace("\$link_upload_files",$string_link_upload_files, $this->bean->description_html);
            //start - code render sms_template  
            global $current_user;
            $smsTemplate = BeanFactory::getBean(
                'pe_smstemplate',
                '2745cca2-2fc8-6ec6-de78-5ecb17f27320' 
            );
            $body =  $smsTemplate->body_c;
            $body = str_replace("\$first_name", $focus->first_name, $body);
            $smsTemplate->body_c = $body;
            $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
            $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
            $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
            //end - code render sms_template
        }

        if($_REQUEST['email_type'] == "solar-design"){
            $macro_nv = array();
            $focusName = "Leads";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */
            
            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                '3c143527-67a2-6190-1565-5d5b3809767e'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );

            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';

                    //$noteTemplate->file_mime_type = 'application/pdf';
                    //$noteTemplate->filename = $att;
                    //$noteTemplate->name = $att;

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }

            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body_html'];

            $link_upload_files = 'https://pure-electric.com.au/pesolarform/confirm-to-lead?lead-id=' . $focus->id;
            $string_link_upload_files = '<a target="_blank" href="'.$link_upload_files.'">File Upload Link Here</a>';
            $this->bean->description_html = str_replace("\$link_upload_files",$string_link_upload_files, $this->bean->description_html);
            //start - code render sms_template  
            global $current_user;
            $smsTemplate = BeanFactory::getBean(
                'pe_smstemplate',
                '89eeb336-b0f4-2d4c-c9e5-5ecb33c852be' 
            );
            $body =  $smsTemplate->body_c;
            $body = str_replace("\$first_name", $focus->first_name, $body);
            $smsTemplate->body_c = $body;
            $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
            $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
            $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
            //end - code render sms_template
        }

        if($_REQUEST['email_type'] == "solar-tesla"){
            $macro_nv = array();
            $focusName = "Leads";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */
            
            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                'fc0302d3-0953-5fa0-66c4-5d6478659062'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );

            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';

                    //$noteTemplate->file_mime_type = 'application/pdf';
                    //$noteTemplate->filename = $att;
                    //$noteTemplate->name = $att;

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }

            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body_html'];
            //start - code render sms_template  
            global $current_user;
            $smsTemplate = BeanFactory::getBean(
                'pe_smstemplate',
                '89eeb336-b0f4-2d4c-c9e5-5ecb33c852be' 
            );
            $body =  $smsTemplate->body_c;
            $body = str_replace("\$first_name", $focus->first_name, $body);
            $smsTemplate->body_c = $body;
            $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
            $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
            $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
            //end - code render sms_template
        }

        if($_REQUEST['email_type'] == "email-acceptance"){
            $macro_nv = array();
            $focusName = "Accounts";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                '825462b3-13de-70bf-913a-5aa077d13344'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );

            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';

                    //$noteTemplate->file_mime_type = 'application/pdf';
                    //$noteTemplate->filename = $att;
                    //$noteTemplate->name = $att;

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }

            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body_html'];

            $this->bean->description_html = str_replace("FIRSTNAME", $focus->name, $this->bean->description_html);
            $this->bean->description = str_replace("FIRSTNAME", $focus->name, $this->bean->description);
            // Query back to lead
            //$this->bean->description_html = str_replace("QUOTATION #XXXXX", "QUOTATION #$lead->solargain_quote_number_c", $email->description_html);
            //$this->bean->description = str_replace("FIRSTNAME", "QUOTATION #$lead->solargain_quote_number_c", $email->description);
        }
        
        if (isset($_REQUEST['seek_install_date'])) { // BinhNT coded
            $lead = new Lead();
            $quote = new AOS_Quotes();
            $record_id = $_REQUEST['lead_id'];
            $quote_id = $_REQUEST['quote_id'];
            $lead = $lead->retrieve($record_id);
            $quote = $quote->retrieve($quote_id);
            if(!$quote->id) return;
            $this->bean->return_module = 'AOS_Quotes';
            $this->bean->return_id = $quote->id;

            // Use query to get description
            $lead_solargain_quote_number_c = $quote->solargain_quote_number_c;
            if($quote->solargain_quote_number_c == '' && $quote->quote_type_c == 'quote_type_tesla') {
                $lead_solargain_quote_number_c = $quote->solargain_tesla_quote_number_c;
            }
            $lead_name = $lead->first_name . " " . $lead->last_name;
            $lead_email_addresss = $lead->email1;
            $lead_primary_address_street = $quote->install_address_c;
            $lead_primary_address_city = $quote->install_address_city_c;
            $lead_primary_address_state = $quote->install_address_state_c;
            $lead_primary_address_postalcode = $quote->install_address_postalcode_c;
            if($lead->phone_mobile != '') {
                $lead_mobile_number = 'M '.$lead->phone_mobile;
            }
            if($lead->phone_work != '') {
                $lead_phone_work = 'W '.$lead->phone_work;
            }
            $pe_quote_number = $quote->number;
            $distributor_array = array(
                    "4" => "Citipower",
                    "5" => "Jemena",
                    "6"=>"Powercor",
                    "7"=>"SP Ausnet",
                    "8"=>"United Energy Distribution",
                    "1"=>"Western Power",
                    "13"=>"South Australia Power Network",
                    "2"=>"Energex",
                    "3" => "Ergon",
                    "9" => "Essential Energy",
                    "10"=>"Ausgrid",
                    "12"=>"Endeavour Energy",
                    "11"=>"ActewAGL",
                    "14"=>"AusNet Electricity Services Pty Ltd",
            );
            $lead_distributor_c = "";
            $notes = "";
            if($quote_id != ''){
                $lead_distributor_c = $distributor_array[$quote->distributor_c];
            }else{
                $lead_distributor_c = $distributor_array[$lead->distributor_c];
            }
            
            $solar_install_contact = array(
                'VIC'=> 'SG Ops Team',
                'SA'=> 'SG Ops Team',
                'ACT'=> 'SG Ops Team',
                'NSW'=> 'SG Ops Team',
                'WA'=> ' SG Ops Team',
                'QLD'=> 'SG Ops Team',
            );

            if($quote == '' || $quote === null){
                $lead_solargain_quote_number_c = $lead->solargain_quote_number_c;
            }
            $contact_name = $solar_install_contact[$lead->primary_address_state];
            $this->bean->name = "Seek Solar PV Install Date - $lead_name $lead_primary_address_city Quote #$lead_solargain_quote_number_c";
            $this->bean->description = "Hi ".$contact_name.", <br>";
            $this->bean->description .= "
            Could you please provide an install date for the following customer? <br>

            Quote #: <a target=\"_blank\" href=\"https://crm.solargain.com.au/quote/edit/".$lead_solargain_quote_number_c."\"> ".$lead_solargain_quote_number_c."</a> <br>

            PE Quote #:<a target=\"_blank\" href=\"http://suitecrm.pure-electric.com.au/index.php?module=AOS_Quotes&offset=2&stamp=1552373603092667900&return_module=AOS_Quotes&action=EditView&record=".$quote_id."\">".$pe_quote_number."</a><br>

            Customer Name: $lead_name <br>

            Customer Email Address: $lead_email_addresss<br>

            Customer Address: $lead_primary_address_street $lead_primary_address_city $lead_primary_address_state $lead_primary_address_postalcode <br>

            Customer Phone: $lead_mobile_number  $lead_phone_work <br>

            Network: $lead_distributor_c <br>

            Notes: $notes";
            $this->bean->description_html = $this->bean->description;
        }

        //dung code - Button Seek Details 
        if (isset($_REQUEST['seek_details'])) { 
            $lead = new Lead();
            $record_id = $_REQUEST['lead_id'];
            $lead = $lead->retrieve($record_id);
            //logic check condition
                $array_check_info_true = array();
                $array_check_info_false = array();
                //Condition 1 : check exist nmi
                if($_REQUEST['nmi_c'] !== ''){
                    array_push($array_check_info_true , 'NMI');
                }else{
                    array_push($array_check_info_false , 'NMI');
                };
                //Conditon 2 ,3 : check exist photo meter box  and Switchboard
                $leads_file_attachmens = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead->installation_pictures_c ."/");
                foreach ($leads_file_attachmens as $key => $value) {

                    if (strpos($value, 'Meter_Box') !== false) {
                        array_push($array_check_info_true , 'photo of your meter box');
                    }          
                    if (strpos($value, 'Switchboard') !== false) {
                        array_push($array_check_info_true , 'photo of your switchboard');
                    }
                }
                if(!in_array('photo of your meter box',$array_check_info_true)){
                    array_push($array_check_info_false , 'photo of your meter box');
                }
                if(!in_array('photo of your switchboard',$array_check_info_true)){
                    array_push($array_check_info_false , 'photo of your switchboard');
                }
                //Condition 4: check information other from email customer
                if($_REQUEST['account_number_c'] !== '' && $_REQUEST['energy_retailer_c']  !== ''  && $_REQUEST['name_on_billing_account_c'] !== '') {
                    array_push($array_check_info_true , 'a copy of your bill');
                }else {
                    array_push($array_check_info_false , 'a copy of your bill showing Retailer Name, Name on the Bill, Account Number and the Phone Number that is registered with the electricity company');
                }

            // Use query to get description
            //tu-code change description
            $macro_nv = array();
            $focusName = "Leads";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                '52269b3c-0394-08e6-6328-5c64b8201381'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );
            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body'];
        }

        //Email SA REPS To Yes 
        if (isset($_REQUEST['email_type']) && $_REQUEST['email_type']== 'email_sa_reps_to_yess') { 

            $macro_nv = array();
            $focusName = "AOS_Invoices";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['record_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                '78866e51-461b-7b16-075e-60335d35ccba'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );
            
            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';


                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }

            /**Custom Attachments From Folder Files and Photo*/
            $file_attachments = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->installation_pictures_c ."/");
            $name_file_include = ['SA_REPS_Information_Statement.pdf','SA_REPS_Activity_Record_WH1_Hot_Replacement.pdf'];
            if (count($file_attachments)>0 ) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach ($file_attachments as $att) {
                    $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->installation_pictures_c ."/" . $att ;
                    if(!is_file($source)) continue;
                    if (strpos(strtolower($att),  strtolower($name_file_include[0])) !== false ||
                    strpos(strtolower($att),  strtolower($name_file_include[1])) !== false ) {
                        $noteTemplate = new Note();
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $this->bean->id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteTemplate->date_entered = '';
                        $noteTemplate->filename = $att;
                        $noteTemplate->name = $att;
                        if(strpos(strtolower($att), "png") !== false) {
                            $noteTemplate->file_mime_type = 'image/png';
                        } elseif (strpos(strtolower($att), "pdf") !== false) {
                            $noteTemplate->file_mime_type = 'application/pdf';
                        } else {
                            $noteTemplate->file_mime_type = 'image/jpg';
                        }
                            
                        $noteTemplate->save();

                        $destination = realpath(dirname(__FILE__) . '/../../').'/upload/'.$noteTemplate->id;
                        if (!symlink($source, $destination)) {
                            $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                        }
                        $this->bean->attachNote($noteTemplate);
                    }
                }
            }

            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body'];
            $this->bean->to_addrs_names = ($focus->email1) ? $focus->email1 : '';      
        }

        //VUT - S - SA REPS CUSTOMER EMAIL
        if (isset($_REQUEST['email_type']) && $_REQUEST['email_type']== 'email_sa_reps_customer') { 

            $macro_nv = array();
            $focusName = "AOS_Invoices";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['record_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                '8737d65f-2da4-7e1a-6ad1-605966b7119c'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $contact =  new Contact();
            $contact->retrieve($focus->billing_contact_id);

            //Change variable
            $description = str_replace("\$contact_first_name",$contact->first_name , $description);

            $description_html = str_replace("\$contact_first_name",$contact->first_name , $description_html);
            //Change variable

            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );
            
            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';


                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }

            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body'];
            $this->bean->to_addrs_names = ($focus->email1) ? $focus->email1 : '';      
        }
        //VUT - E - SA REPS CUSTOMER EMAIL

        //dung code - button US7 TIPS
        if (isset($_REQUEST['email_type']) && $_REQUEST['email_type']== 'us7_tips') { 
            $contact = new Contact();
            $record_id = $_REQUEST['record_id'];
            $contact = $contact->retrieve($record_id);

            $macro_nv = array();
            $focusName = "Contacts";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['record_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */

            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                'c537f9f6-99d8-231d-3e80-5d50acd8af6a'
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );
            
            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';

                    //$noteTemplate->file_mime_type = 'application/pdf';
                    //$noteTemplate->filename = $att;
                    //$noteTemplate->name = $att;

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }


            $this->bean->description_html = str_replace("\$contact_first_name",$contact->first_name,$this->bean->description_html);
            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body'];
            $this->bean->to_addrs_names = ($focus->email1) ? $focus->email1 : '';

            $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $contact->phone_mobile));
            $phone_number = preg_replace("/^61/", "+61", $phone_number);
            $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
            $this->bean->number_receive_sms = "matthew_paul_client";
            //start - code render sms_template  
            global $current_user;
            $smsTemplate = BeanFactory::getBean(
                'pe_smstemplate',
                '76569b04-1096-849f-244f-5e60155625a3' 
            );
            $body =  $smsTemplate->body_c;
            $body = str_replace("\$first_name", $contact->first_name, $body);
            $smsTemplate->body_c = $body;
            $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
            $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
            $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
            //end - code render sms_template
        }

        //dung code - button Sanden TIPS
        if (isset($_REQUEST['email_type']) && $_REQUEST['email_type']== 'sanden_tips') { 
            $contact = new Contact();
            $record_id = $_REQUEST['record_id'];
            $contact = $contact->retrieve($record_id);
            $sanden_product = $_REQUEST["sanden_product"];

            $macro_nv = array();
            $focusName = "Contacts";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['record_id']);

            if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */
            $emailTemplateID = '';
            switch ($sanden_product) {
                case 'G2':
                    $emailTemplateID = 'b2390382-d938-c0fc-2259-6046c1cbb7ee';
                    break;
                case 'G3':
                    $emailTemplateID = 'a33d4e37-15f5-5f1c-bd20-6046cb724766';
                    break;    
                case 'G4':
                    $emailTemplateID = 'bd902f3b-e281-6764-ac50-5d50bea88378';
                    break;        
                default:
                    $emailTemplateID = 'bd902f3b-e281-6764-ac50-5d50bea88378';
                    break;
            }
            
            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                 $emailTemplateID
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );
            
            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                    /**Select sanden product */
                    // if (strpos($attachmentBean->filename, $sanden_product) === false && ($attachmentBean->file_mime_type == 'image/jpeg' || $attachmentBean->file_mime_type == 'application/pdf')) {
                    //     if (strpos($attachmentBean->filename, "Sanden Maintenance") === false) {
                    //         continue;
                    //     }
                    // }
                    /**Select sanden product */
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';

                    //$noteTemplate->file_mime_type = 'application/pdf';
                    //$noteTemplate->filename = $att;
                    //$noteTemplate->name = $att;

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }


            $this->bean->description_html = str_replace("\$contact_first_name",$contact->first_name,$this->bean->description_html);
            $this->bean->name = $templateData['subject'];
            $this->bean->description_html = $templateData['body_html'];
            $this->bean->description = $templateData['body'];
            $this->bean->to_addrs_names = ($focus->email1) ? $focus->email1 : '';
            //start - code render sms_template  
            global $current_user;
            $smsTemplate = BeanFactory::getBean(
                'pe_smstemplate',
                'd8ff08a6-02e3-3f12-998a-5d6864a8b7f3' 
            );
            $body =  $smsTemplate->body_c;
            $body = str_replace("\$first_name", $contact->first_name, $body);
            $smsTemplate->body_c = $body;
            $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
            $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
            $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
            //end - code render sms_template
        }

        //thienpb code  - Button quote Follow Up
        if($_REQUEST['email_type'] == 'follow_up'){
            $macro_nv = array();
            $focusName = "Leads";
            $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);

            //if(!$focus->id) return;
            /**
             * @var EmailTemplate $emailTemplate
             */
            //dung code - logic template for "Follow Up"
            if($_REQUEST['product_type'] != 'quote_type_solar' && $_REQUEST['lead_source_company'] != 'Solargain'){
                $EmailTemplateID = '22d9ed2d-c403-d59a-af09-5c1b288e6985';
            }
            elseif($_REQUEST['product_type'] == 'quote_type_solar' && $_REQUEST['lead_source_company'] == 'Solargain'){
                $EmailTemplateID = '2105719a-e409-79d5-ae7b-5d14371c661f';
            }
            elseif($_REQUEST['product_type'] == 'quote_type_solar' && $_REQUEST['lead_source_company'] != 'Solargain'){
                $EmailTemplateID = '3d250ac9-738f-dba1-5d15-5d1438c39456';
            }
            else{
                $EmailTemplateID =  '22d9ed2d-c403-d59a-af09-5c1b288e6985';
            }
            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                $EmailTemplateID 
            );

            $name = $emailTemplate->subject;
            $description_html = $emailTemplate->body_html;
            $description = $emailTemplate->body;

            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $name,
                    'body_html' => $description_html,
                    'body' => $description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );

            $attachmentBeans = $emailTemplate->getAttachments();

            if($attachmentBeans) {
                $this->bean->status = "draft";
                $this->bean->save();
                foreach($attachmentBeans as $attachmentBean) {
                    $noteTemplate = clone $attachmentBean;
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';

                    //$noteTemplate->file_mime_type = 'application/pdf';
                    //$noteTemplate->filename = $att;
                    //$noteTemplate->name = $att;

                    $noteFile = new UploadFile();
                    $noteFile->duplicate_file($attachmentBean->id, $noteTemplate->id, $noteTemplate->filename);

                    $noteTemplate->save();
                    $this->bean->attachNote($noteTemplate);
                }
            }

            $quote_data = BeanFactory::getBean('AOS_Quotes', $_REQUEST['quote_id']);
            if(!$quote_data->id) return;
            $this->bean->return_module = 'AOS_Quotes';
            $this->bean->return_id = $quote_data->id;
            $this->bean->name = str_replace("\$aos_quotes_number",$quote_data->number,$name);
            $this->bean->name = str_replace("\$aos_quotes_name",$quote_data->name,$this->bean->name );
            $this->bean->name = str_replace("\$aos_quotes_solargain_quote_number_c",$quote_data->solargain_quote_number_c,$this->bean->name );
            
            $description_html = str_replace("\$lead_first_name",$quote_data->account_firstname_c,$description_html);
            $description_html = str_replace("\$aos_quotes_number",$quote_data->number,$description_html);
            $description_html = str_replace("\$aos_quotes_name",$quote_data->name,$description_html);
            $description_html = str_replace("\$aos_quotes_solargain_quote_number_c",$quote_data->solargain_quote_number_c,$description_html);
            $description_html = str_replace("\$aos_quotes_quote_date_c", date("F Y",strtotime(str_replace("/","-",$quote_data->quote_date_c))),$description_html);

            $select_call_status = '<div><div style="float:left;padding:0;width:30%;min-width:215px;color:#444;text-align:center;overflow:hidden;margin:0">
                                        <div style="margin:0.5rem;border-radius:2rem;border:3px solid rgb(235,235,235)">
                                            <div style="height: auto;border-radius: 20px;clear:both;margin:0;font-weight:bold;padding:0.25rem 0;color:#fff;background: silver;">
                                                <table style="width:100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:center;"><a target="_blank" style="text-decoration: none;margin:0;padding:0;font-size:13px;color: black;" href="http://devel.pure-electric.com.au/quote_follow_up?quote_id='.$_REQUEST['quote_id'].'&feedback=I_Need_More_Time">I Need More Time - Email Me In a Week</a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="float:left;padding:0;width:30%;min-width:215px;color:#444;text-align:center;overflow:hidden;margin:0">
                                        <div style="margin:0.5rem;border-radius:2rem;border:3px solid rgb(235,235,235)">
                                            <div style="height: auto;border-radius: 20px;clear:both;margin:0;font-weight:bold;padding:0.25rem 0;color:#fff;background: silver;">
                                                <table style="width:100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:center;"><a target="_blank" style="text-decoration: none;margin:0;padding:0;font-size:13px;color: black;" href="http://devel.pure-electric.com.au/quote_follow_up?quote_id='.$_REQUEST['quote_id'].'&feedback=I_Have_More_Questions">I Have More Questions - Call Me When Possible</a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="float:left;padding:0;width:30%;min-width:215px;color:#444;text-align:center;overflow:hidden;margin:0">
                                        <div style="margin:0.5rem;border-radius:2rem;border:3px solid rgb(235,235,235)">
                                            <div style="height: auto;border-radius: 20px;clear:both;margin:0;font-weight:bold;padding:0.25rem 0;color:#fff;background: silver;">
                                                <table style="width:100%">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:center;"><a target="_blank" style="text-decoration: none;margin:0;padding:0;font-size:13px;color: black;" href="http://devel.pure-electric.com.au/quote_follow_up?quote_id='.$_REQUEST['quote_id'].'&feedback=Not_Proceeding_With_Quote">Not Proceeding With Quote - Thank You For The Quote</a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear: both;"></div>';

            $description_html = str_replace("\$button_select_status",$select_call_status , $description_html);

            $token = sha1(uniqid($quote_data->id, true));
            $db = DBManagerFactory::getInstance();
            $db->query("INSERT INTO pending_quote_token (quote_id, token, tstamp) VALUES ('$quote_data->id','$token' ,".time().")");

            $token_url = '/index.php?entryPoint=followUpdateQuoteStage&token='.$token.'&follow=';
            
            $yesno = '<a href="'.$token_url.'yes"> YES still interested </a> | <a href="'.$token_url.'no">No longer interested</a>';
            $description_html = str_replace("\$aos_quotes_yesno",$yesno,$description_html);
            
            $this->bean->description_html = $description_html;
            $this->bean->to_addrs_names = ($focus->email1) ? $focus->email1 : $_REQUEST['email_address'];
            $this->bean->description = $templateData['body_html'];

            //start - code render sms_template  
            global $current_user;
            $smsTemplate = BeanFactory::getBean(
                'pe_smstemplate',
                '76532523-fc21-b5ce-3671-5e3254c29a60' 
            );
            $body =  $smsTemplate->body_c;
            $body = str_replace("\$first_name", $quote_data->account_firstname_c, $body);
            $smsTemplate->body_c = $body;
            $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
            $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
            $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$quote_data).' '.$current_user->sms_signature_c,ENT_QUOTES)));   
            //end - code render sms_template
        }

        if($emailTemplate->id != '') {
            $this->bean->emails_email_templates_name = $emailTemplate->name;
            $this->bean->emails_email_templates_idb = $emailTemplate->id;
        }
        //overide to address email for email installation calendar >> 12/03/2021 VUT - comment to render
        if($_GET['return_module'] == "AOS_Invoices" && isset($_REQUEST['changedSubject']) && isset($_REQUEST['installation_id'])){
            $role_user = $_REQUEST['role'];
            $return_id = $_REQUEST["return_id"];
            $invoice = new AOS_Invoices();
            $invoice->retrieve($return_id);
            if($invoice->id != '') {
                switch ($role_user) {
                    case 'client':
                        $account_id = $invoice->billing_account_id;
                        break;
                    case 'electrician':
                        $account_id = $invoice->account_id_c;
                        break;   
                    case 'plumber':
                        $account_id = $invoice->account_id1_c;
                        break;                 
                    default:
                        $account_id = '';
                        break;
                }
                $account = new Account();
                $account = $account->retrieve($account_id);
                if(!empty($account->id)){
                    $sea = new SugarEmailAddress; 
                    $primary = $sea->getPrimaryAddress($account);
                    $this->bean->to_addrs_names =  $account->name . "  <".$primary.">";
                    // $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $account->mobile_phone_c));
                    // $phone_number = preg_replace("/^61/", "+61", $phone_number);
                    // $this->bean->number_client = $phone_number  ;
                }
            }
        }

        //start - code render sms_template
            //auto render template default
            if($this->bean->sms_message == ''){
                    global $current_user;
                    $smsTemplate = BeanFactory::getBean(
                        'pe_smstemplate',
                        '328a27e7-51e8-1640-4183-5d75f7757982' 
                    );
                    $this->bean->emails_pe_smstemplate_idb  =   $smsTemplate->id;
                    $this->bean->emails_pe_smstemplate_name =  $smsTemplate->name; 
                    $this->bean->sms_message =trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus).' '.$current_user->sms_signature_c,ENT_QUOTES)));
                
            }
        //end - code render sms_template
        //devise content sms and sms signture
        if( $this->bean->emails_pe_smstemplate_idb != ''){
            if( $this->bean->sms_signture == '') {
                $this->bean->sms_signture = $current_user->sms_signature_c;
            };
            if(  $this->bean->sms_content == '') {
                $this->bean->sms_content = trim(strip_tags(html_entity_decode($this->parse_sms_template($smsTemplate,$focus),ENT_QUOTES)));
            };       
        }
        //dung code-- display number client 
        if($focus->id != ''){
            //popup by button
            $Parent_Module = $focusName;
            $Parent_Id = $focus->id;
        }
        elseif(isset($_REQUEST['targetModule'])){
            //popup by search
            $Parent_Module = $_REQUEST['targetModule'];
            $Parent_Id = $_REQUEST['ids'];
        }
        elseif(isset($_REQUEST['email_module'])){
            //popup by link email
            $Parent_Module = $_REQUEST['email_module'];
            $Parent_Id = $_REQUEST['record_id'];
        }
        elseif(isset($_REQUEST['lead_id'])){
            //popup by link email only Lead
            $Parent_Module = 'Leads';
            $Parent_Id = $_REQUEST['lead_id'];
        }
        $Bean_Parent = BeanFactory::getBean($Parent_Module,$Parent_Id);
        if($Bean_Parent->id != '' && $this->bean->number_client == ''){
            switch ($Parent_Module) {
                case 'Leads':
                    $phone_number = $Bean_Parent->phone_mobile;
                    break;
                case 'Accounts':
                    if($Bean_Parent->id != ''){
                        $phone_number = $Bean_Parent->mobile_phone_c;      
                    }
                    break;
                case 'Contacts':
                    $phone_number = $Bean_Parent->phone_mobile;  
                    break;
                case 'AOS_Quotes':
                    $account = new Account();
                    $account->retrieve($Bean_Parent->billing_account_id);
                    if($account->id != ''){
                        $phone_number = $account->mobile_phone_c;      
                    }
                    break;
                case "AOS_Invoices":
                    $account = new Account();
                    $account->retrieve($Bean_Parent->billing_account_id);
                    if($account->id != ''){
                        $phone_number = $account->mobile_phone_c;      
                    }
                    break;
                default:
                    # code...
                    break;
            }
            $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $phone_number));
            $phone_number = preg_replace("/^61/", "+61", $phone_number);
            $this->bean->number_client = $phone_number;
        }

        // add variable return module and return id 
        if($this->bean->return_module == '') {
            $this->bean->return_module = $focus->module_dir;
        }
        if($this->bean->return_id == '') {
            $this->bean->return_id = $focus->id;
        }
        if($this->bean->email_id == '') {
            $this->bean->email_id =  $this->bean->id;
        }

        //end dung code-- display number client 
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $this->bean->request_actual= $actual_link ;
        if($_REQUEST['RefeshTemplate'] == 'true'){

            if(count($noteArray) > 0 ){
                $attachmentBeans = array_merge((array)$attachmentBeans,(array)$noteArray);
            }else{
                $attachmentBeans =  $attachmentBeans;
            }

            // $this->bean->getNotes($this->bean->id);
            // $attachmentBeans =   $this->bean->attachments;
            if ($attachmentBeans) {
                $attachments_email_refesh = array();
                foreach ($attachmentBeans as $attachmentBean) {
                    $attachments_email_refesh[] = array(
                        'id' => $attachmentBean->id,
                        'name' => $attachmentBean->name,
                        'file_mime_type' => $attachmentBean->file_mime_type,
                        'filename' => $attachmentBean->filename,
                        'parent_type' => $attachmentBean->parent_type,
                        'parent_id' => $attachmentBean->parent_id,
                        'description' => $attachmentBean->description,
                    );
                }
            }

            $json_data_email_refesh = array(
                'error' => '',
                'msgs' => [],
                'data' => array(
                    'body' => html_entity_decode($this->bean->description),
                    'body_from_html' => html_entity_decode($this->bean->description_html),
                    'body_html' => html_entity_decode($this->bean->description),
                    'id' => $emailTemplate->id,
                    'name' => $emailTemplate->name,
                    'subject' => $this->bean->name,
                    'attachments' =>(count($attachments_email_refesh)>0) ? $attachments_email_refesh : array(),
                ),
            );

            echo '<div hidden id="data_json_email_refesh">'. json_encode($json_data_email_refesh) .'</div>';
        }
        echo '<script> window.request_actual ="'.$actual_link.'";</script>';
        //VUT- Add BCC email current user / If Jane Au > no add BCC
        if ($current_user->id != "93883222-d915-6c7b-54f3-5dfae2a09ad9") {
            $this->bean->bcc_addrs_names = $current_user->name.' <'.$current_user->email1.'>'; //VUT-Add BCC email current user
        }
        //VUT - resize Image Note()
        $this->resizeImageNote($this->bean->id);
        $this->view = 'compose';
        // For viewing the Compose as modal from other modules we need to load the Emails language strings
        if (isset($_REQUEST['in_popup']) && $_REQUEST['in_popup']) {
            if (!is_file('cache/jsLanguage/Emails/' . $GLOBALS['current_language'] . '.js')) {
                require_once('include/language/jsLanguage.php');
                jsLanguage::createModuleStringsCache('Emails', $GLOBALS['current_language']);
            }
            echo '<script src="cache/jsLanguage/Emails/'. $GLOBALS['current_language'] . '.js"></script>';
        }
        if (isset($_REQUEST['ids']) && isset($_REQUEST['targetModule'])) {
            $toAddressIds = explode(',', rtrim($_REQUEST['ids'], ','));
            foreach ($toAddressIds as $id) {
                $destinataryBean = BeanFactory::getBean($_REQUEST['targetModule'], $id);
                if ($destinataryBean) {
                    $idLine = '<input type="hidden" class="email-compose-view-to-list" ';
                    $idLine .= 'data-record-module="' . $_REQUEST['targetModule'] . '" ';
                    $idLine .= 'data-record-id="' . $id . '" ';
                    $idLine .= 'data-record-name="' . $destinataryBean->name . '" ';
                    $idLine .= 'data-record-email="' . $destinataryBean->email1 . '">';
                    echo $idLine;
                }
            }
        }
        if (isset($_REQUEST['relatedModule']) && isset($_REQUEST['relatedId'])) {
            $relateBean = BeanFactory::getBean($_REQUEST['relatedModule'], $_REQUEST['relatedId']);
            $relateLine = '<input type="hidden" class="email-relate-target" ';
            $relateLine .= 'data-relate-module="' . $_REQUEST['relatedModule'] . '" ';
            $relateLine .= 'data-relate-id="' . $_REQUEST['relatedId'] . '" ';
            $relateLine .= 'data-relate-name="' . $relateBean->name . '">';
            echo $relateLine;
        }
    }

    // .:nhantv:. Get Product Name by Short Name
    protected function getProductNameByShortName($productBean, $shortName){
        $productObj = $productBean->retrieve_by_string_fields(
            array(
            'short_name_c' => $shortName
            )
        );
        return ($productObj != null) ? $productObj->name : $shortName;
    }

    // .:nhantv:. Calculate STCs price
    protected function calcStcs($productBean, $num_stc){
        $productObj = $productBean->retrieve_by_string_fields(
            array(
            'short_name_c' => 'STCs'
            )
        );
        return ($productObj != null) ? (float)$productObj->cost * $num_stc : -34 * $num_stc;
    }

    // .:nhantv:. Get Curent product name
    protected function getCurrentProductName($productBean, $pricings, $i){
        $curr_product = array();
        $name = "";

        // Panel
        if($pricings->{'panel_og_type_'.$i} != ''){
            $name = $this->getProductNameByShortName($productBean, $pricings->{'panel_og_type_'.$i});
            $curr_product['panel'] = $name;
        }
        // Og Inverter
        if($pricings->{'offgrid_inverter_'.$i} != ''){
            $name = $this->getProductNameByShortName($productBean, $pricings->{'offgrid_inverter_'.$i});
            $curr_product['offgrid_inverter'] = $name;
        }
        // Battery
        if($pricings->{'offgrid_batery_'.$i} != ''){
            $name = $this->getProductNameByShortName($productBean, $pricings->{'offgrid_batery_'.$i});
            $curr_product['offgrid_batery'] = $name;
        }
        // Accessory 1
        if($pricings->{'offgrid_accessory1_'.$i} != ''){
            $name = $this->getProductNameByShortName($productBean, $pricings->{'offgrid_accessory1_'.$i});
            $curr_product['offgrid_accessory1'] = $name;
        }
        // Accessory 2
        if($pricings->{'offgrid_accessory2_'.$i} != ''){
            $name = $this->getProductNameByShortName($productBean, $pricings->{'offgrid_accessory2_'.$i});
            $curr_product['offgrid_accessory2'] = $name;
        }
        // Generator
        if($pricings->{'re_generator_'.$i} != ''){
            $name = $this->getProductNameByShortName($productBean, $pricings->{'re_generator_'.$i});
            $curr_product['re_generator'] = $name;
        }

        return $curr_product;
    }

    /**
     * Creates a record from the Quick Create Modal
     */
    public function action_QuickCreate()
    {
        $this->view = 'ajax';
        $originModule = $_REQUEST['module'];
        $targetModule = $_REQUEST['quickCreateModule'];

        $_REQUEST['module'] = $targetModule;

        $controller = ControllerFactory::getController($targetModule);
        $controller->loadBean();
        $controller->pre_save();
        $controller->action_save();
        $bean = $controller->bean;

        $_REQUEST['module'] = $originModule;

        if (!$bean) {
            $result = ['id' => false];
            echo json_encode($result);
            return;
        }

        $result = [
            'id' => $bean->id,
            'module' => $bean->module_name,
        ];
        echo json_encode($result);

        if (empty($_REQUEST['parentEmailRecordId'])) {
            return;
        }
        $emailBean = BeanFactory::getBean('Emails', $_REQUEST['parentEmailRecordId']);
        if (!$emailBean) {
            return;
        }

        $relationship = strtolower($controller->module);
        $emailBean->load_relationship($relationship);
        $emailBean->$relationship->add($bean->id);

        if (!$bean->load_relationship('emails')) {
            return;
        }

        $bean->emails->add($emailBean->id);
    }

    /**
     * @see EmailsViewSendemail
     */
    public function action_send()
    {
        global $current_user;
        global $app_strings;
        global $sugar_config;
        $request = $_REQUEST;

        $this->bean = $this->bean->populateBeanFromRequest($this->bean, $request);
        $inboundEmailAccount = new InboundEmail();
        $inboundEmailAccount->retrieve($_REQUEST['inbound_email_id']);

        // BinhNT Code
        if (isset($request['send_sms']) && ($request['send_sms']!== "") && $request['send_sms'] != "false"){

            // Get the phone number
            $phone_number_array = array(
                "matthew_paul" => array(
                    //"+61421616733",
                    //"+61423494949",
                ),
                "matthew_paul_client" => array(
                    //"+61421616733",
                    //"+61423494949",
                    $this->bean->number_client?$this->bean->number_client:$request['number_client']
                ),
            );

            if(isset($request['number_receive_sms']) && isset($request['sms_message'])){
                $number_receive_sms = $request['number_receive_sms'];
                $client_numbers = $phone_number_array[$number_receive_sms];
                
                //thienpb fix
                $sms_body = preg_replace("/&#?[a-z0-9]{2,8};/i"," ", strip_tags($request['sms_message']));
                $sms_body = str_replace("$", "\\$", html_entity_decode($sms_body, ENT_QUOTES));
                $sms_body = str_replace("+", "\\+", $sms_body);
                //$sms_body = str_replace("'", "\'", $sms_body);
                if( $_POST['number_send_sms'] == "+61421616733"){
                    $message_dir = '/var/www/message2';
                }
                elseif( $_POST['number_send_sms'] == "+61490942067"){
                    $message_dir = '/var/www/message';
                }
                foreach($client_numbers as $phone_number){
                    exec("cd ".$message_dir."; php send-message.php sms ".$phone_number." ".escapeshellarg($sms_body) ,$outputs);
                    if(count($outputs) > 0){
                        foreach($outputs as $error){
                            if($error == "invalid syntax."){
                                $GLOBALS['log']->security(
                                    'Send SMS fai ('.$error.')'
                                );
                                $response['errors'] = [
                                    'type' => get_class($this->bean),
                                    'id' => $this->bean->id,
                                    'title' =>  'Send SMS fail ('.$error.')'
                                ];
                                echo json_encode($response);
                                die;
                            }
                        }
                    }
                    //global $current_user;
                    // we also send the images

                    if (count($request['dummy_attachment'])){
                        foreach($request['dummy_attachment'] as $attachment){

                            // We dont do this with CES VBA .pdf document
                            $noteTemplate = new Note();
                            $noteTemplate->retrieve($attachment);
                            if(isset($noteTemplate->id) && ($noteTemplate->id != "" ) && 
                                        (strpos($noteTemplate->filename, ".pdf") !== false) &&
                                        (strpos(strtolower($noteTemplate->filename), "ces") !== false || strpos(strtolower($noteTemplate->filename), "pcoc") !== false)
                                        ){
                                continue; // Dont do anything
                            }
                            // Just do it with invoice, PO and quotes pdf 
                            if(isset($noteTemplate->id) && ($noteTemplate->id != "" ) && 
                                        (strpos($noteTemplate->filename, ".pdf") !== false) &&

                                        (strpos(strtolower($noteTemplate->filename), "quote_") !== false || 
                                            //strpos(strtolower($noteTemplate->filename), "quote_#") !== false || 
                                            strpos(strtolower($noteTemplate->filename), "invoice") !== false ||
                                            strpos(strtolower($noteTemplate->filename), "purchaseorder_") !== false || 
                                            strpos(strtolower($noteTemplate->filename), "po-#") !== false)
                                        ){
                                // Dont do anything
                            } else {
                                continue;
                            }
                            //if it s quote from SG, continue
                            $is_SGQuote = false;
                            if(isset($noteTemplate->id) && ($noteTemplate->id != "" ) && 
                                        (strpos($noteTemplate->filename, ".pdf") !== false) &&
                                        strpos(strtolower($noteTemplate->filename), "quote_#") !== false)
                                        {
                                //continue; 
                                        $is_SGQuote = true;

                            } else {
                                // dont do anything
                            }

                            $file_path = "/var/www/suitecrm/upload/".$attachment;

                            $imagick = new Imagick();
                            if(is_file($file_path)){
                                $imagick->readImage($file_path);


                                $noOfPagesInPDF = $imagick->getNumberImages();
                                $files = array();
                                if ($noOfPagesInPDF) {
                                    if($is_SGQuote){
                                        for ($i = 2; $i < $noOfPagesInPDF - 4; $i++) {

                                            $l_Image = new Imagick();
                                            $l_Image->setResolution(150, 150);
                                            $l_Image->readImage($file_path."[".$i."]");
                                            $l_Image = $l_Image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

                                            $l_Image->setCompression(Imagick::COMPRESSION_JPEG);
                                            $l_Image->setImageBackgroundColor('white');
                                            $l_Image->setCompressionQuality (100);
                                            $l_Image->stripImage();
                                            $l_Image->setImageFormat("jpg");
                                            $path_to_write = "/var/www/suitecrm/public_files/".$attachment.$i.'.jpg';
                                            $l_Image->writeImage($path_to_write);

                                            $l_Image->clear();
                                            $l_Image->destroy();
                                            $image_url = "https://".$_SERVER['HTTP_HOST'].'/public_files/'.$attachment.$i.".jpg";
                                            exec("cd " . $message_dir . "; php send-message.php mms " . $phone_number ." ".escapeshellarg($image_url));
                                        }
                                    }else {
                                        for ($i = 0; $i < $noOfPagesInPDF; $i++) {

                                            $l_Image = new Imagick();
                                            $l_Image->setResolution(150, 150);
                                            $l_Image->readImage($file_path."[".$i."]");
                                            $l_Image = $l_Image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

                                            $l_Image->setCompression(Imagick::COMPRESSION_JPEG);
                                            $l_Image->setImageBackgroundColor('white');
                                            $l_Image->setCompressionQuality (100);
                                            $l_Image->stripImage();
                                            $l_Image->setImageFormat("jpg");
                                            $path_to_write = "/var/www/suitecrm/public_files/".$attachment.$i.'.jpg';
                                            $l_Image->writeImage($path_to_write);

                                            $l_Image->clear();
                                            $l_Image->destroy();
                                            $image_url = "https://".$_SERVER['HTTP_HOST'].'/public_files/'.$attachment.$i.".jpg";
                                            exec("cd " . $message_dir . "; php send-message.php mms " . $phone_number ." ".escapeshellarg($image_url));
                                        }
                                    }

                                }
                            }
                            
                            $is_SGQuote = false;
                        }
                    }
                }

                $sms = new pe_smsmanager();
                $sms->description = $sms_body;
                $sms->save();
                if($request['return_module'] == "AOS_Invoices") {
                    $invoice = new AOS_Invoices();
                    $invoice = $invoice->retrieve($request['return_id']);
                    if(isset($invoice->id) && $invoice->id != ""){
                        $sms->name = "Invoice ".$invoice->number." SMS";
                        $sms->load_relationship('pe_smsmanager_aos_invoices');
                        $sms->pe_smsmanager_aos_invoices->add($invoice);
                        $sms->save();
                    }

                }elseif($request['return_module'] == "AOS_Quotes") {
                    $quote = new AOS_Quotes();
                    $quote = $quote->retrieve($request['return_id']);
                    if(isset($quote->id) && $quote->id != ""){
                        $sms->name = "Quote ".$quote->number." SMS";
                        $sms->load_relationship('pe_smsmanager_aos_quotes');
                        $sms->pe_smsmanager_aos_quotes->add($quote);
                        $sms->save();
                        //VUT-Internal note-Sms out
                            $internal_notes = new pe_internal_note();
                            $internal_notes->type_inter_note_c = 'sms_out';
                            // $internal_notes->pe_smsmanager_id_c = $sms->id;
                            $internal_notes->description = $sms->name;
                            $internal_notes->save();
                            $internal_notes->load_relationship('aos_quotes_pe_internal_note_1');
                            $internal_notes->aos_quotes_pe_internal_note_1->add($quote->id);
                        //VUT-Internal note-Sms out
                    }

                }elseif($request['return_module'] == "Accounts") {
                    $account = new Account();
                    $account = $account->retrieve($request['return_id']);
                    if(isset($account->id) && $account->id != ""){
                        $sms->name = "Account ".$account->name." SMS";
                        $sms->load_relationship('pe_smsmanager_accounts');
                        $sms->pe_smsmanager_accounts->add($account);
                        $sms->save();
                    }

                }elseif($request['return_module'] == "Leads") {
                    $lead = new Lead();
                    $lead = $lead->retrieve($request['return_id']);
                    if(isset($lead->id) && $lead->id != ""){
                        $sms->name = "Lead ".$lead->number." SMS";
                        $sms->load_relationship('pe_smsmanager_leads');
                        $sms->pe_smsmanager_leads->add($lead);
                        $sms->save();
                    }

                }elseif($request['return_module'] == "Contacts") {
                    $contact = new Contact();
                    $contact = $contact->retrieve($request['return_id']);
                    if(isset($contact->id) && $contact->id != ""){
                        $sms->name = "Contact ".$contact->name." SMS";
                        $sms->load_relationship('pe_smsmanager_contacts');
                        $sms->pe_smsmanager_contacts->add($contact);
                        $sms->save();
                    }

                }
                /// end
                //$sms->pe_smsmanager_aos_invoices->add()
            }

        }

        
        if ($this->userIsAllowedToSendEmail($current_user, $inboundEmailAccount, $this->bean)) {
            $this->bean->save();

            $this->bean->handleMultipleFileAttachments();

            // parse and replace bean variables
            $this->bean = $this->replaceEmailVariables($this->bean, $request);

            // Thienpb Code
            if(isset($request['send_gmail']) && ($request['send_gmail'] !== "") && $request['send_gmail'] != "false"){
                require_once('vendor/phpmailer/phpmailer/src/PHPMailer.php');
                $fromGmail  = '';
                $fromGmailName = '';
                if($this->bean->from_addr == "matthew@pure-electric.com.au" || $this->bean->from_addr == "matthew.wright@pure-electric.com.au"){
                    $fromGmail = 'matt.wright.pure@gmail.com';
                    $fromGmailName = "Matthew Wright";
                }else{
                    $fromGmail = 'paul.szuster.pure@gmail.com';
                    $fromGmailName = "Paul Szuster";

                }

                $smtpGmail = new SugarPHPMailer();
                $smtpGmail->isSMTP();
                $smtpGmail->Host = 'ssl://smtp.gmail.com';
                $smtpGmail->Port = 465;
                $smtpGmail->SMTPAuth = true;
                $smtpGmail->Username = $fromGmail;
                $smtpGmail->Password = 'Puretrue2020';

                $smtpGmail->setFrom($fromGmail,$fromGmailName);
                $toEmail = explode("<", $this->bean->to_addrs);
                if(count($toEmail) > 1){
                    $smtpGmail->addAddress(trim(str_replace('>','',$toEmail[1])),trim($toEmail[0]));
                }else{
                    $smtpGmail->addAddress(trim($toEmail[0]));

                }
               
                $smtpGmail->Subject = $this->bean->name;
                $smtpGmail->isHTML(true);
                $smtpGmail->Body =  htmlspecialchars_decode($this->bean->description_html);
                
                $attachments = $this->bean->attachments;
                foreach ($attachments as $noteAttr) {
                  global $sugar_config;
                  $location = $sugar_config['upload_dir'].$noteAttr->id;
                  $mime_type = $attachment->file_mime_type;
                  $smtpGmail->AddAttachment($location, $noteAttr->filename, 'base64', $mime_type);
                }
               // $smtpGmail->addAttachment($attachments);
                $smtpGmail->send();
            }
            if ($this->bean->send()) {
                //VUT - S - Change status PO 
                if ($_REQUEST["return_module"] == "PO_purchase_order" && isset($_REQUEST["pdf_id"]) && $_REQUEST["pdf_id"] != '') {
                    $po = new PO_purchase_order();
                    $po->retrieve($_REQUEST["return_id"]);
                    if (($po->po_type_c == "sanden_supply" || $po->po_type_c == "daikin_supply") && ($po->status_c == 'PE_Mgmt_Approved'|| $po->status_c == 'Draft' )) {
                        $po->status_c = 'Sent_To_Supplier';
                        $po->save();
                    }
                }
                //VUT - E - Change status PO 

                //VUT - S - Invoice Methven - change status to Paid and delete Next Action Date
                if ($_REQUEST["return_module"] == "AOS_Invoices" && isset($_REQUEST["pdf_id"]) && $_REQUEST["pdf_id"] != '') {
                    $invoice = new AOS_Invoices();
                    $invoice->retrieve($_REQUEST["return_id"]);
                    if ($invoice->quote_type_c == 'quote_type_methven') {
                        $invoice->status = 'Paid';
                        $invoice->next_action_date_c = '';
                        $invoice->save();
                    }
                }
                //VUT - E - Invoice Methven - change status to Paid and delete Next Action Date
                
                //thienpb code - Update status sent_pricing_option
                if($_REQUEST['emails_email_templates_idb'] == '9d9f03ae-fe75-68d0-72ad-5d5b95cda15b'){
                    $quote = new AOS_Quotes();
                    $quote->retrieve($_REQUEST["return_id"]);    
                    if( $quote->stage != 'Designs_Complete' && $quote->stage != 'Reviewed'  ) {
                        $quote->stage = 'pricing_options_sent';
                        $quote->save();
                        $call = new Call();
                        $call->parent_type = "Accounts";
                        $call->parent_id = $quote->billing_account_id;
                        $call->parent_name =$quote->billing_account;
                        $call->name = $quote->name;
                        $call->assigned_user_id = $quote->assigned_user_id;
                        $call->assigned_user_name = $quote->assigned_user_name;
                        $call->direction='Outbound';
                        date_default_timezone_set('UTC');
                        $dateAUS = date('Y-m-d H:i:s', (time()+24*60*60));
                        $call->date_start = $dateAUS;
                        $call->date_end = $dateAUS;
                        $call->duration_hours='0';
                        $call->duration_minutes='30';
                        // $call->account_id =$account->id;
                        $call->status='Planned';
                        $call->save();  
                    }             
                }
                
                //thienpb code - Update status save and pdf solar
                if($_REQUEST['emails_email_templates_idb'] == "64084c36-9ba4-68fd-20c8-5ecc3b51c593" && $_REQUEST["parent_type"] == "AOS_Quotes"){
                    if(!empty($_REQUEST["parent_id"])){
                        $quoteSolarBean = new AOS_Quotes();
                        $quoteSolarBean->retrieve($_REQUEST["parent_id"]);
                        if($quoteSolarBean->id){
                            $quoteSolarBean->stage = "Delivered";
                            $quoteSolarBean->save();
                        }
                        $_REQUEST['quote_parent_id'] = $_REQUEST["parent_id"];
                    }else if(!empty($_REQUEST["return_id"])){
                        $quoteSolarBean = new AOS_Quotes();
                        $quoteSolarBean->retrieve($_REQUEST["return_id"]);
                        if($quoteSolarBean->id){
                            $quoteSolarBean->stage = "Delivered";
                            $quoteSolarBean->save();
                        }
                        $_REQUEST['quote_parent_id'] = $_REQUEST["return_id"];
                    }
                }

                //dung code - Update time  in Leads Seek install date 
                if(isset($_REQUEST['Seek_Install_Date_From_Leads_Check']) &&  $_REQUEST['Seek_Install_Date_From_Leads_Check'] !== '') {
                    $Leads = new Lead();
                    $Leads->retrieve($_REQUEST['Seek_Install_Date_From_Leads_Check']);
                    date_default_timezone_set('UTC');
                    $dateAUS = date('Y-m-d H:i:s', time());
                    $Leads->seek_install_date_c = $dateAUS;
                    $Leads->save();
                }

                //thienpb code - Update time  in Quotes Seek install date 
                if(isset($_REQUEST['Seek_Install_Date_From_Quotes_Check']) &&  $_REQUEST['Seek_Install_Date_From_Quotes_Check'] !== '') {
                    $quote = new AOS_Quotes();
                    $quote->retrieve($_REQUEST['Seek_Install_Date_From_Quotes_Check']);                    
                    date_default_timezone_set('UTC');
                    $dateAUS = date('Y-m-d H:i:s', time());
                    $quote->seek_install_date_c = $dateAUS;
                    $quote->stage = 'Install_Date_Requested';
                    $quote->save();  
                }
            
                //dung code - Update time  in PO Seek install date 
                if(isset($_REQUEST['po_id_email_Seek_Install_Date_From_PO_Check']) && $_REQUEST['po_id_email_Seek_Install_Date_From_PO_Check'] !== '') {
                    $PO_purchase_order = new PO_purchase_order();
                    $PO_purchase_order->retrieve($_REQUEST['po_id_email_Seek_Install_Date_From_PO_Check']);                    
                    date_default_timezone_set('UTC');
                    $dateAUS = date('Y-m-d H:i:s', time());
                    $PO_purchase_order->seek_install_time_c = $dateAUS;
                    $PO_purchase_order->save();
                    
                }

                //dung code - Logic : Check Email Send To Customer by manualy at first time From New Lead. Assigned to current user 
                if($_REQUEST['parent_type'] == 'Leads' 
                && isset($_REQUEST['number_send_sms']) && $_REQUEST['number_send_sms'] !== ''
                && isset($_REQUEST['number_receive_sms']) && $_REQUEST['number_receive_sms'] !== ''
                ) {
                    $lead_id = $this->bean->parent_id;
                    $query = "
                    SELECT parent_id ,mailbox_id FROM emails
                    WHERE
                        parent_id = '$lead_id' AND parent_type = 'Leads' AND
                        (number_send_sms IS NOT NULL OR number_send_sms = '') AND
                        deleted = 0 ";
                    $db = DBManagerFactory::getInstance();
                    $row = $db->query($query, true);
                    if($row->num_rows == 1 ) {
                        $lead = new Lead();
                        $lead = $lead->retrieve($lead_id);
                        /*$matthew_inbound_id = "58cceed9-3dd3-d0b5-43b2-59f1c80e3869";
                        $paul_inbound_id    = "ae0192a6-b70b-23a1-8dc0-59f1c819a22c";
                        */
                        $assigned_user_id_old = $lead->assigned_user_id;
                        
                        if( strpos($_REQUEST["from_addr"],"Paul Szuster") !== false  ){ // $_REQUEST['inbound_email_id'] == '"46dfeb97-52c5-bcfe-ffbd-59f1c5061272"' ) {
                            $lead->assigned_user_name = 'Paul Szuster';
                            $lead->assigned_user_id = '61e04d4b-86ef-00f2-c669-579eb1bb58fa';
                        } else if (strpos($_REQUEST["from_addr"],"Matthew Wright") !== false ){
                            $lead->assigned_user_name = 'Matthew Wright';
                            $lead->assigned_user_id = '8d159972-b7ea-8cf9-c9d2-56958d05485e';
                        } else {
                            // Do nothing
                        }
                        $lead->save();

                        //auto send notification for old assigned user
                        if($assigned_user_id_old !== $lead->assigned_user_id) {
                            $emailObj = new Email();
                            $defaults = $emailObj->getSystemDefaultEmail();
                            $mail = new SugarPHPMailer();
                            $mail->setMailerForSystem();
                            $mail->From = $defaults['email'];
                            $mail->FromName = $defaults['name'];
                            $mail->IsHTML(true);
                            $mail->Subject = 'The lead has been reallocated to - ' .$lead->assigned_user_name;

                            date_default_timezone_set('Australia/Melbourne');
                            $dateAUS = date('m/d/Y h:i:s a', time());
                            
                            $mail->Body = '<div>The lead has been reallocated to ' .$lead->assigned_user_name .  ' on date ' . $dateAUS . ' (Time Melbourne).</div>
                            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $lead_id. '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Lead Link Edit</a></div>';    
                            
                            $mail->prepForOutbound();
                            //$mail->AddAddress('nguyenphudung93.dn@gmail.com');
                            $mail->AddAddress('admin@pure-electric.com.au');
                            $mail->AddCC('info@pure-electric.com.au');
                           if($assigned_user_id_old == '61e04d4b-86ef-00f2-c669-579eb1bb58fa') {
                                $mail->AddAddress('paul.szuster@pure-electric.com.au');   
                           }else{
                                $mail->AddAddress('matthew.wright@pure-electric.com.au');
                           }
                           
                            $sent = $mail->Send();

                        }
                    }         
                }
                //Thienpb code for check attachment
                
                $attachments = $this->bean->attachments;
                $check_file = false;
                foreach($attachments as $row){
                    if(strpos($row->filename,'Quote_#') === false){
                        $check_file = true;
                        break;
                    }
                }
                if( $check_file && ( $_REQUEST['emails_email_templates_idb'] == "64084c36-9ba4-68fd-20c8-5ecc3b51c593" || $_REQUEST['emails_email_templates_idb'] == "4f86b77f-94a4-1523-5194-59ed8f28e5c0" || $_REQUEST['emails_email_templates_idb'] == "a8dbc136-588b-7213-9cbf-5bd0063f4de9" || $_REQUEST['emails_email_templates_idb'] == '2316382f-a235-beb5-e12e-5c1862686a24' || $_REQUEST['emails_email_templates_idb'] == "9e6b03dd-52d2-a034-c9cb-5cb6aa76ab0d" )){
                    $lead_id = $this->bean->parent_id;
                    // if($lead_id !=''){
                        $lead = new Lead();
                        $lead = $lead->retrieve($lead_id);
                        
                        //thienpb code
                        if(isset($_REQUEST['quote_parent_id']) && $_REQUEST['quote_parent_id'] != ''){
                            $quote_module =  new AOS_Quotes();
                            $quote_module = $quote_module->retrieve($_REQUEST['quote_parent_id']);
                            if($quote_module->id){
                                $quote_module->stage = "Delivered";
                                date_default_timezone_set('UTC');
                                $dateAUS = date('Y-m-d H:i:s', time());
                                $quote_module->time_sent_to_client_c = $dateAUS;

                                global $current_user;
                                
                                $quote_module->sender_c = $current_user->name;
                                $quote_module->user_id1_c = $current_user->id;
                                $quote_module->save();
                            }
                        }

                        // if($lead->id != ''){
                            if($_REQUEST['emails_email_templates_idb'] == '2316382f-a235-beb5-e12e-5c1862686a24'){
                                if(isset($_REQUEST['quote_parent_id']) && $_REQUEST['quote_parent_id'] != ''){
                                    $quote_id = $quote_module->solargain_tesla_quote_number_c;
                                }else{
                                    $quote_id = $lead->solargain_tesla_quote_number_c;
                                }
                            }else{
                                if(isset($_REQUEST['quote_parent_id']) && $_REQUEST['quote_parent_id'] != ''){
                                    $quote_id = $quote_module->solargain_quote_number_c;
                                }else{
                                    $quote_id = $lead->solargain_quote_number_c;
                                }
                            }
                            
                            if($quote_id != ''){
                                date_default_timezone_set('Africa/Lagos');
                                set_time_limit ( 0 );
                                ini_set('memory_limit', '-1');

                                $username = "matthew.wright";
                                $password =  "MW@pure733";

                                //1. login and get json quotes

                                $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_id;
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $url);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                                curl_setopt($curl,CURLOPT_ENCODING , "gzip");
                                curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                        "Host: crm.solargain.com.au",
                                        "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                                        "Content-Type: application/json",
                                        "Accept: application/json, text/plain, */*",
                                        "Accept-Language: en-US,en;q=0.5",
                                        "Accept-Encoding: 	gzip, deflate, br",
                                        "Connection: keep-alive",
                                        "Authorization: Basic ".base64_encode($username . ":" . $password),
                                        "Referer: https://crm.solargain.com.au/quote/edit/".$result,
                                        "Cache-Control: max-age=0"
                                    )
                                );

                                $quote = curl_exec($curl);
                                $quote_decode = json_decode($quote);
                                 //Thienpb code for change account if download false
                                    if(!isset($quote_decode->ID)){
                                        if($username == 'paul.szuster@solargain.com.au'){
                                            $username = "matthew.wright";
                                            $password =  "MW@pure733";
                                        }else{
                                            $username = 'paul.szuster@solargain.com.au';
                                            $password = 'WalkingElephant#256';
                                        }
                                        
                                        //get data from SG quote
                                            $url = "https://crm.solargain.com.au/APIv2/quotes/". $quote_id;
                                            //set the url, number of POST vars, POST data
                                        
                                            $curl = curl_init();
                                            
                                            curl_setopt($curl, CURLOPT_URL, $url);
                                            
                                            
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                                            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                                            
                                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                            //
                                            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                            curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                                            curl_setopt($curl, CURLOPT_ENCODING , "gzip");
                                            curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                                            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                                    "Host: crm.solargain.com.au",
                                                    "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                                                    "Content-Type: application/json",
                                                    "Accept: application/json, text/plain, */*",
                                                    "Accept-Language: en-US,en;q=0.5",
                                                    "Accept-Encoding: 	gzip, deflate, br",
                                                    "Connection: keep-alive",
                                                    "Authorization: Basic ".base64_encode($username . ":" . $password),
                                                    "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id,
                                                    "Cache-Control: max-age=0"
                                                )
                                            );
                                            
                                            $quote = curl_exec($curl);
                                            $quote_decode = json_decode($quote);
                                            curl_close($curl);
                                    }

                                    if(!isset($quote_decode)) die();
                                //END

                                //2. get email customer and change fake email 
                                    $customer_id = $quote_decode->Customer->ID;
                                    $custommer_type = $quote_decode->Customer->CustomerTypeID;
                                    $email_customer = $quote_decode->Customer->Email;

                                    $data = array(
                                        "ID"=>$customer_id,
                                        "CustomerTypeID" => $custommer_type,
                                        "LastName" => $quote_decode->Customer->LastName,
                                        "FirstName" => $quote_decode->Customer->FirstName,
                                        "Phone"	=> $quote_decode->Customer->Phone,
                                        "Mobile" => $quote_decode->Customer->Mobile,
                                        "Email" => 'customfakemail@sharklasers.com',
                                        "Address" => $quote_decode->Customer->Address,
                                        "OptIn" => true,
                                        "Notes" => array(array(
                                            "ID" => 0,
                                        )),
                                    );
                                    $data_string = json_encode($data);
                                    $url = "https://crm.solargain.com.au/APIv2/customers/";

                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, $url);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                                    curl_setopt($curl, CURLOPT_POST, 1);
                                    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

                                    $headers = array();
                                    $headers[] = "Pragma: no-cache";
                                    $headers[] = "Origin: https://crm.solargain.com.au";
                                    $headers[] = "Accept-Encoding: gzip, deflate, br";
                                    $headers[] = "Accept-Language: en";
                                    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
                                    $headers[] = "Content-Type: application/json";
                                    $headers[] = "Accept: application/json, text/plain, */*";
                                    $headers[] = "Cache-Control: no-cache";
                                    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
                                    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
                                    $headers[] = "Connection: keep-alive";
                                    $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                                    $result = curl_exec($curl);
                                    curl_close($curl);

                                //3. send pdf email

                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, "https://crm.solargain.com.au/APIv2/quotes/".$quote_id."/sendPDF");
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                                    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

                                    $headers = array();
                                    $headers[] = "Pragma: no-cache";
                                    $headers[] = "Origin: https://crm.solargain.com.au";
                                    $headers[] = "Accept-Encoding: gzip, deflate, br";
                                    $headers[] = "Accept-Language: en";
                                    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
                                    $headers[] = "Content-Type: application/json";
                                    $headers[] = "Accept: application/json, text/plain, */*";
                                    $headers[] = "Cache-Control: no-cache";
                                    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
                                    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
                                    $headers[] = "Connection: keep-alive";
                                    $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                                    $result = curl_exec($curl);
                                    curl_close ($curl);

                                    $url = 'https://crm.solargain.com.au/APIv2/quotes/'.$quote_id;
                                    //set the url, number of POST vars, POST data
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, $url);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                                    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
                                    curl_setopt($curl,CURLOPT_ENCODING , "gzip");
                                    curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                            "Host: crm.solargain.com.au",
                                            "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                                            "Content-Type: application/json",
                                            "Accept: application/json, text/plain, */*",
                                            "Accept-Language: en-US,en;q=0.5",
                                            "Accept-Encoding: 	gzip, deflate, br",
                                            "Connection: keep-alive",
                                            "Authorization: Basic ".base64_encode($username . ":" . $password),
                                            "Referer: https://crm.solargain.com.au/quote/edit/".$result,
                                            "Cache-Control: max-age=0"
                                        )
                                    );

                                    $quote_new = curl_exec($curl);
                                    $quote_decode_new = json_decode($quote_new);
                                    if(!isset($quote_decode_new)) die();

                                    //if($quote_decode_new->Status->Description != "Sent to Customer"){
                                      //  return;
                                    //}

                                //4. change back email customer

                                    $data['Email'] = $email_customer;
                                    $data_string = json_encode($data);
                                    $url = "https://crm.solargain.com.au/APIv2/customers/";
                                    
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, $url);
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                                    curl_setopt($curl, CURLOPT_POST, 1);
                                    curl_setopt($curl, CURLOPT_ENCODING, 'gzip, deflate');

                                    $headers = array();
                                    $headers[] = "Pragma: no-cache";
                                    $headers[] = "Origin: https://crm.solargain.com.au";
                                    $headers[] = "Accept-Encoding: gzip, deflate, br";
                                    $headers[] = "Accept-Language: en";
                                    $headers[] = "Authorization: Basic ".base64_encode($username . ":" . $password);
                                    $headers[] = "Content-Type: application/json";
                                    $headers[] = "Accept: application/json, text/plain, */*";
                                    $headers[] = "Cache-Control: no-cache";
                                    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36";
                                    $headers[] = "Cookie: SL_GWPT_Show_Hide_tmp=1; SL_wptGlobTipTmp=1";
                                    $headers[] = "Connection: keep-alive";
                                    $headers[] = "Referer: https://crm.solargain.com.au/quote/edit/".$quote_id;
                                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                                    $result = curl_exec($curl);
                                    curl_close($curl);

                            }
                        // }
                    // }
                }
                
                //END

                $this->bean->status = 'sent';
                if($_REQUEST["parent_type"] == "Leads"){
                    $leadID = $_REQUEST["parent_id"];
                    //$this->updateSolargainLead($leadID, $request);

                    //dung code - change status lead from button Street Address Request Email
                    if($_REQUEST['emails_email_templates_idb'] == '383cde5c-de72-3902-2a9a-5b5008c452d0') {
                        $leads_bean = new Lead();
                        $leads_bean->retrieve($_REQUEST['parent_id']);
                        // if($leads_bean->status == "Assigned"){
                        //     $leads_bean->status = 'In Process';
                        // }
                        $leads_bean->status = 'Address_Requested';
                        $leads_bean->email_send_status_c = 'sent';
                        $leads_bean->save();
                    }           
                    //dung code - change status lead from button Solar Design Complete
                    //if($_REQUEST['emails_email_templates_idb'] == '4f86b77f-94a4-1523-5194-59ed8f28e5c0' || $_REQUEST['emails_email_templates_idb'] ==  'a8dbc136-588b-7213-9cbf-5bd0063f4de9' || $_REQUEST['emails_email_templates_idb'] ==  '2316382f-a235-beb5-e12e-5c1862686a24') {
                    if($_REQUEST['emails_email_templates_idb'] == '4f86b77f-94a4-1523-5194-59ed8f28e5c0' 
                    || $_REQUEST['emails_email_templates_idb'] ==  'a8dbc136-588b-7213-9cbf-5bd0063f4de9' 
                    || $_REQUEST['emails_email_templates_idb'] ==  '3742953d-1318-43cb-00e3-5bbaab707bcd' 
                    || $_REQUEST['emails_email_templates_idb'] ==  '180953f6-3dda-b10e-8f39-5bbbfe2bec38'  
                    || $_REQUEST['emails_email_templates_idb'] ==  '12fb3725-0581-cf2c-18ed-5bbbfe6b0089'
                    || $_REQUEST['emails_email_templates_idb'] ==  '2316382f-a235-beb5-e12e-5c1862686a24'
                    ) { 
                        
                        $leads_bean = new Lead();
                        $leads_bean->retrieve($_REQUEST['parent_id']);
                        date_default_timezone_set('Australia/Melbourne');
                        $dateAUS = date('Y-m-d H:i:s', time());
                        $leads_bean->solar_send_email_complete_c = $dateAUS;
                        $leads_bean->time_sent_to_client_c = $dateAUS;

                        // We need also send sms here too
                        // First need to check if attachment have quotes
                        $attachments = $this->bean->attachments;
                        $is_have_sg_quote_file = false;
                        foreach($attachments as $row){
                            if(strpos($row->filename,'Quote_') === false){
                                $is_have_sg_quote_file = true;
                                break;
                            }
                        }
                        // Update info for Lead in Suitecrm
                        if($is_have_sg_quote_file)$leads_bean->status = 'Converted';
                        if(false && $is_have_sg_quote_file){ // temporary disabled it
                            $client_number = $leads_bean->phone_mobile? $leads_bean->phone_mobile: $leads_bean->phone_work;
                            $client_number = str_replace(" ", "",preg_replace("/^04/", '+614', $client_number));
                            $message_dir = "";
                            if( $leads_bean->assigned_user_id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa"){ // Paul 
                                $message_dir = '/var/www/message';
                                $admin_name = "Paul";
                            }
                            elseif( $leads_bean->assigned_user_id == "8d159972-b7ea-8cf9-c9d2-56958d05485e"){ // Matthew
                                $message_dir = '/var/www/message2';
                                $admin_name = "Matthew";
                            }
                            $client_firstname = $leads_bean->first_name;
                            $sms_body = "Hi $client_firstname,  $admin_name here from Pure Electric I've just sent you your solargain solar quote which you should have on your email - please also check your spam folder.  can you please confirm that you've got it ok? Kind Regards, $admin_name";
                            if($client_number != "" && $message_dir!=""){
                                exec("cd ".$message_dir."; php send-message.php sms ".$client_number." ".escapeshellarg($sms_body));
                            }
                            $sms = new pe_smsmanager();
                            $sms->description = $sms_body;
                            $sms->save();
                            $sms->name = $client_number. " ".substr($sms_body, 0, 30)."...";
                            $sms->load_relationship('pe_smsmanager_leads');
                            $sms->pe_smsmanager_leads->add($leads_bean);
                            $sms->save();
                        }
                        // End Send SMS

                        //create opportunity Solar
                        if($_REQUEST['Convert_Solar_Opportunity'] == 'true' && $leads_bean->create_solar_number_c == ''){
                            
                            //if not have account and contact, create new account and contact
                                //create contact
                                if($leads_bean->contact_id == '') {
                                    $contact = new Contact();
                                    $contact->salutation = $leads_bean->salutation;
                                    $contact->first_name = $leads_bean->first_name;
                                    $contact->last_name = $leads_bean->last_name;
                                    $contact->phone_work = $leads_bean->phone_work;
                                    $contact->phone_mobile = $leads_bean->phone_mobile;
                                    $contact->department = $leads_bean->department;
                                    $contact->phone_fax = $leads_bean->phone_fax;
                                    $contact->primary_address_street = $leads_bean->primary_address_street;
                                    $contact->primary_address_city = $leads_bean->primary_address_city;
                                    $contact->primary_address_state = $leads_bean->primary_address_state;
                                    $contact->primary_address_postalcode = $leads_bean->primary_address_postalcode;
                                    $contact->primary_address_country = $leads_bean->primary_address_country;
                                    $contact->assigned_user_name = $leads_bean->assigned_user_name;
                                    $contact->assigned_user_id = $leads_bean->assigned_user_id;
                                    $contact->save();
                                    $leads_bean->contact_id = $contact->id;
                                }

                                //create account
                                if($leads_bean->account_id == '') {
                                    $account = new Account();
                                    $account->name = $leads_bean->first_name ." " . $leads_bean->last_name;
                                    $account->phone_office = $leads_bean->phone_office;
                                    $account->phone_fax = $leads_bean->phone_fax;
                                    $account->website = $leads_bean->website;
                                    $account->billing_address_street = $leads_bean->primary_address_street;
                                    $account->billing_address_city = $leads_bean->primary_address_city;
                                    $account->billing_address_state = $leads_bean->primary_address_state;
                                    $account->billing_address_postalcode = $leads_bean->primary_address_postalcode;
                                    $account->billing_address_country = $leads_bean->primary_address_country;
                                    $account->assigned_user_name = $leads_bean->assigned_user_name;
                                    $account->assigned_user_id = $leads_bean->assigned_user_id;
                                    $account->save();
                                    $leads_bean->account_id = $account->id;
                                }
                            
                            $opportunity = new Opportunity();
                            $opportunity->name = $leads_bean->last_name .' ' .$leads_bean->primary_address_city .' Solar';
                            $opportunity->account_name = $leads_bean->EditView_account_name;
                            $opportunity->assigned_user_name = $leads_bean->assigned_user_name;
                            $opportunity->assigned_user_id = $leads_bean->assigned_user_id;
                            $opportunity->account_name =  $leads_bean->first_name ." " . $leads_bean->last_name;
                            $opportunity->account_id = $leads_bean->account_id;
                            $opportunity->amount = 600;//$bean->opportunity_amount;
                            $opportunity->sales_stage = 'Negotiation/Review';
                            $opportunity->lead_source =$leads_bean->lead_source;
                            $date = new DateTime('+1 day');
                            $opportunity->date_closed = $date->format('Y-m-d');
                            $opportunity->save();
                            if($leads_bean->opportunity_id == ''){
                                $leads_bean->opportunity_id = $opportunity->id;
                            }
                            $leads_bean->create_solar_number_c =  $opportunity->id;
                            $leads_bean->opportunity_id =  $opportunity->id;
                            $leads_bean->opportunity_name =  $opportunity->name;
                            $leads_bean->create_solar_c = '1'; 
                            $leads_bean->status = 'Converted';
                        }
                        $leads_bean->save();
                    }

                    //dung code - change status email quote sanden
                    if($_REQUEST['emails_email_templates_idb'] ==  '7c189f2f-19a9-c2c1-23fa-59f922602067') {
                        $leads_bean = new Lead();
                        $leads_bean->retrieve($_REQUEST['parent_id']);
                        $leads_bean->check_email_sanden_quote_c = 'sent';
                        $leads_bean->save();
                    }

                    //thienpb code
                    if($_REQUEST['emails_email_templates_idb'] ==  'dbf622ae-bb45-cb79-eb97-5cd287c48ac3') {
                        $leads_bean = new Lead();
                        $leads_bean->retrieve($_REQUEST['parent_id']);
                        $leads_bean->sent_email_sanden_fqs_c = 1;
                        $leads_bean->save();
                    }

                    if($_REQUEST['emails_email_templates_idb'] ==  'ad1f03d0-dc47-7f39-fbb9-5cd289eafcf5') {
                        $leads_bean = new Lead();
                        $leads_bean->retrieve($_REQUEST['parent_id']);
                        $leads_bean->sent_email_sanden_fqv_c = 1;
                        $leads_bean->save();
                    }
                    //dung code - change status email quote daikin
                    if($_REQUEST['emails_email_templates_idb'] == '8d9e9b2c-e05f-deda-c83a-59f97f10d06a') {
                        $leads_bean = new Lead();
                        $leads_bean->retrieve($_REQUEST['parent_id']);
                        $leads_bean->check_email_daikin_quote_c = 'sent';
                        $leads_bean->save();
                    }

                    if ($_REQUEST["emails_email_templates_name"] == 'Designs Complete')
                    {
                        $lead = new Lead();
                        $lead->retrieve($leadID);
                        if ($lead->time_completed_job_c != '')
                        {
                            date_default_timezone_set('UTC');
                            $dateAUS = date('Y-m-d H:i:s', time());
                            $lead->time_sent_to_client_c = $dateAUS;
                            $lead->save();
                        }
                    }

                    //change status all email send from module lead 
                    if(isset($_REQUEST['parent_type']) && isset($_REQUEST['parent_id']) && $_REQUEST['parent_type'] == 'Leads' &&  $_REQUEST['parent_id'] != ''){
                        $leads_bean = new Lead();
                        $leads_bean->retrieve($_REQUEST['parent_id']);
                        if(isset($leads_bean->id) && $leads_bean->status != 'Converted') {
                            $leads_bean->status = 'Info_Pack_Sent';
                            $leads_bean->save();
                        }
                    }
                    
                    //change status solar design
                    if($_REQUEST['emails_email_templates_idb'] == '3c143527-67a2-6190-1565-5d5b3809767e') {
                        $leads_bean = new Lead();
                        $leads_bean->retrieve($_REQUEST['parent_id']);
                        if(isset($leads_bean->id) && $leads_bean->status != 'Converted') {
                            $leads_bean->status = 'Info_Pack_Sent';
                            $leads_bean->save();
                        }
                    }
                }
                $this->bean->save();
                if ($_REQUEST["return_module"] == "AOS_Quotes")
                {
                    $quoteID = $_REQUEST["return_id"];
                    if ($quoteID != '')
                    {
                        $quote = new AOS_Quotes();
                        $quote->retrieve($quoteID);
                        if ($quote->number != null)
                        {
                            $body_html = $this->bean->description_html;
                            preg_match('/Reference: (.*?)<br/', $body_html, $matches, PREG_OFFSET_CAPTURE);
                            $quote->bank_ref_c = $matches[1][0];
                            $quote->save();
                        }
                    }
                }

                //thien fix

                if($_REQUEST['sendGeo_invoice_id'] != ''){
                    $invoice = new AOS_Invoices();
                    $invoice->retrieve($_REQUEST['sendGeo_invoice_id']);
                    if($invoice->id != ''){
                        $invoice->send_geo_email_status_c = 'sent';
                        $invoice->save();
                    }

                }

                // create internal note when sent email
                $address_email_customer = $this->bean->to_addrs_arr[0]['email'];
                $this->auto_create_internal_note($address_email_customer,$this->bean);
                $this->auto_change_status_lead($address_email_customer,$this->bean);

            } else {
                // Don't save status if the email is a draft.
                // We need to ensure that drafts will still show
                // in the list view
                if ($this->bean->status !== 'draft') {
                    $this->bean->save();
                }
                $this->bean->status = 'send_error';
            }

            $this->view = 'sendemail';
        } else {
            $GLOBALS['log']->security(
                'User ' . $current_user->name .
                ' attempted to send an email using incorrect email account settings in' .
                ' which they do not have access to.'
            );

            $this->view = 'ajax';
            $response['errors'] = [
                'type' => get_class($this->bean),
                'id' => $this->bean->id,
                'title' => $app_strings['LBL_EMAIL_ERROR_SENDING']
            ];
            echo json_encode($response);
        }
    }

    protected function updateSolargainLead($leadID, $request){

        $lead = new Lead();
        $lead->retrieve($leadID);
        if(!$lead->solargain_lead_number_c) {
            return;
        }
        $solargainLead = $lead->solargain_lead_number_c;
        date_default_timezone_set('Africa/Lagos');
        set_time_limit ( 0 );
        ini_set('memory_limit', '-1');

        $username = "matthew.wright";
        $password = "MW@pure733";

        // Get full json response for Leads

        $url = "https://crm.solargain.com.au/APIv2/leads/". $solargainLead;
        //set the url, number of POST vars, POST data

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);


        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:      gzip, deflate, br",
                "Connection: keep-alive",
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/lead/edit/".$solargainLead,
                "Cache-Control: max-age=0"
            )
        );

        $leadJSON = curl_exec($curl);
        curl_close ( $curl );

        $leadSolarGain = json_decode($leadJSON);
        global $current_user;
        // building Note
        // Logged in user name: Email From name: and email template title
        $note = "";
        if(isset($this->bean->from_name) && $this->bean->from_name != ""){
            $note = $current_user->full_name. " : ". $this->bean->from_name. " : ".$request["emails_email_templates_name"];
        }
        /*else {
            $note = $current_user->full_name. " : ".$request["emails_email_templates_name"];
        }*/
        $leadSolarGain->Notes[] = array(
            "ID" => 0,
            "Type"=> array(
                "ID"=>5,
                "Name"=>"E-Mail Out",
                "RequiresComment"=> true
            ),
            "Text"=> $note
        );

        $leadSolarGainJSONDecode = json_encode($leadSolarGain, JSON_UNESCAPED_SLASHES);
        //echo $leadSolarGainJSONDecode;die();
        // Save back lead
        $url = "https://crm.solargain.com.au/APIv2/leads/";
        //set the url, number of POST vars, POST data
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $leadSolarGainJSONDecode);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT,  $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Host: crm.solargain.com.au",
                "User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
                "Content-Type: application/json",
                "Accept: application/json, text/plain, */*",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Encoding:      gzip, deflate, br",
                "Connection: keep-alive",
                "Content-Length: " .strlen($leadSolarGainJSONDecode),
                "Authorization: Basic ".base64_encode($username . ":" . $password),
                "Referer: https://crm.solargain.com.au/lead/edit/".$solargainLead,
            )
        );

        $lead = json_decode(curl_exec($curl));
        curl_close ( $curl );
    }

    protected function auto_create_internal_note($address_email_customer,$beanEmail){
        if($beanEmail->id != '' && $address_email_customer != '') {
            $db = DBManagerFactory::getInstance();
    
            //get id contact , id account by email address 
            $sql = "SELECT contacts.id as contacts_id , accounts.id as accounts_id FROM email_addr_bean_rel 
            LEFT JOIN contacts ON email_addr_bean_rel.bean_id = contacts.id
            LEFT JOIN accounts ON email_addr_bean_rel.bean_id = accounts.id
            LEFT JOIN email_addresses ON email_addresses.id = email_addr_bean_rel.email_address_id
            WHERE email_addresses.email_address  LIKE '%$address_email_customer%' AND (email_addr_bean_rel.bean_module ='Contacts' OR email_addr_bean_rel.bean_module ='Accounts'  )";
            $contacts_id = ''; $accounts_id = '';
            $ret = $db->query($sql);
            while ($row = $db->fetchByAssoc($ret)) {
                if($row["contacts_id"] != ""){
                    $contacts_id = $row["contacts_id"];
                }
                if($row["accounts_id"] != ""){
                    $accounts_id = $row["accounts_id"];
                }
            }
            
            //VUT-delete ", leads.id as leads_id" in SELECT sql
            $sql = "SELECT aos_invoices.id as aos_invoices_id ,  aos_quotes.id as aos_quotes_id FROM accounts_contacts
            LEFT JOIN aos_invoices ON aos_invoices.billing_account_id = accounts_contacts.account_id
            LEFT JOIN aos_quotes ON aos_quotes.billing_account_id = accounts_contacts.account_id
            -- LEFT JOIN leads ON leads.account_id = accounts_contacts.account_id
            WHERE  ( accounts_contacts.contact_id  = '$contacts_id' OR accounts_contacts.account_id = '$accounts_id')";
            //get id invoice and id quote by id account or contact
            $ret = $db->query($sql);
            while ($row = $db->fetchByAssoc($ret)) {
                if($row["aos_invoices_id"] != ""){
                    $invoice_id[] = $row["aos_invoices_id"];
                }
                if($row["aos_quotes_id"] != ""){
                    $quote_id[] = $row["aos_quotes_id"];
                }
                // if($row["leads_id"] != ""){
                //     $lead_id[] = $row["leads_id"];
                // }
            }
        
            $bean_intenal_notes = new  pe_internal_note();
            $bean_intenal_notes->type_inter_note_c = 'email_out';
            $bean_intenal_notes->description =  $beanEmail->name;
            $bean_intenal_notes->email_id_c =  $beanEmail->id;
            $bean_intenal_notes->save();
            
            //VUT-S-Create internal note when send PO email (name email include PO and #number)
            preg_match('/(\w{0,14}).(#\d)\w+/', $beanEmail->name, $check); 
            $info_PO = explode('#', $check[0]);
            if (trim($info_PO[0]) == 'PO') {
                $sql_PO = "SELECT id
                            FROM po_purchase_order
                            WHERE number = '$info_PO[1]' AND deleted != 1
                            ";
                $ret_PO = $db->query($sql_PO);
                while ($row = $db->fetchByAssoc($ret_PO)) {
                    if ($row['id'] != '') {
                        // $PO = new PO_purchase_order();
                        // $PO->retrieve($row['id']);
                        $bean_intenal_notes->load_relationship('po_purchase_order_pe_internal_note_1');
                        $bean_intenal_notes->po_purchase_order_pe_internal_note_1->add($row['id']);
                    }
                }
            }
            //VUT-E-Create internal note when send PO email (name email include PO and #number)
            
        
            if(count($invoice_id) > 0 || count($quote_id) > 0 || count($lead_id) > 0 ) {  
                //delete value same like
                $quote_id = array_unique($quote_id, SORT_REGULAR);
                $invoice_id = array_unique($invoice_id, SORT_REGULAR);
                // $lead_id = array_unique($lead_id, SORT_REGULAR);
        
                foreach ($invoice_id as $key => $value) {
                    if($value != ''){
                        $bean_intenal_notes->load_relationship('aos_invoices_pe_internal_note_1');
                        $bean_intenal_notes->aos_invoices_pe_internal_note_1->add($value);
                    }
                }

                foreach ($lead_id as $key => $value) {
                    if($value != ''){
                        $bean_intenal_notes->load_relationship('leads_pe_internal_note_1');
                        $bean_intenal_notes->leads_pe_internal_note_1->add($value);
                    }
                }
        
                foreach ($quote_id as $key => $value) {
                    if($value != ''){
                        $bean_intenal_notes->load_relationship('aos_quotes_pe_internal_note_1');
                        $bean_intenal_notes->aos_quotes_pe_internal_note_1->add($value);
                    }
                }
                
            }     
        }
    }

    protected function auto_change_status_lead($address_email_customer,$beanEmail) {
        if($beanEmail->id != '' && $address_email_customer != '') {
            $db = DBManagerFactory::getInstance();
    
            //get id contact , id account by email address 
            $sql = "SELECT leads.id as leads_id FROM email_addr_bean_rel 
            LEFT JOIN contacts ON email_addr_bean_rel.bean_id = contacts.id
            LEFT JOIN accounts ON email_addr_bean_rel.bean_id = accounts.id
            LEFT JOIN leads ON email_addr_bean_rel.bean_id = leads.id
            LEFT JOIN email_addresses ON email_addresses.id = email_addr_bean_rel.email_address_id
            WHERE email_addresses.email_address  LIKE '%$address_email_customer%' ";
            $leads_id = []; 
            $ret = $db->query($sql);
            while ($row = $db->fetchByAssoc($ret)) {
                if($row["leads_id"] != ""){
                    $leads_id[] = $row["leads_id"];
                }
            }
            $leads_id = array_unique($leads_id);
            foreach ($leads_id as $key => $value) {
                $lead = new Lead();
                $lead->retrieve($value);
                if($lead->id != '' && $lead->status == 'New'){
                    $lead->status = 'In Process';
                    $lead->save();
                }
            }
            

        }
    }
    /**
     * Parse and replace bean variables
     * but first validate request,
     * see log to check validation problems
     *
     * return Email bean
     *
     * @param Email $email
     * @param array $request
     * @return Email
     */
    protected function replaceEmailVariables(Email $email, $request)
    {
        // request validation before replace bean variables

        if ($this->isValidRequestForReplaceEmailVariables($request)) {
            $macro_nv = array();

            $focusName = $request['parent_type'];
            $focus = BeanFactory::getBean($focusName, $request['parent_id']);
            if ($email->module_dir == 'Accounts') {
                $focusName = 'Accounts';
            }

            /**
             * @var EmailTemplate $emailTemplate
             */
            $emailTemplate = BeanFactory::getBean(
                'EmailTemplates',
                isset($request['emails_email_templates_idb']) ?
                    $request['emails_email_templates_idb'] :
                    null
            );
            $templateData = $emailTemplate->parse_email_template(
                array(
                    'subject' => $email->name,
                    'body_html' => htmlspecialchars($email->description_html),// BinhNT edited $email->description_html,//'body_html' => $email->description_html,
                    'body' => $email->description,
                ),
                $focusName,
                $focus,
                $macro_nv
            );

            $email->name = $templateData['subject'];
            $email->description_html = $templateData['body_html'];
            $email->description = $templateData['body'];
        } else {
            $this->log('Email variables is not replaced because an invalid request.');
        }


        return $email;
    }

    /**
     * Request validation before replace bean variables,
     * see log to check validation problems
     *
     * @param array $request
     * @return bool
     */
    protected function isValidRequestForReplaceEmailVariables($request)
    {
        $isValidRequestForReplaceEmailVariables = true;

        if (!is_array($request)) {

            // request should be an array like standard $_REQUEST

            $isValidRequestForReplaceEmailVariables = false;
            $this->log('Incorrect request format');
        }


        if (!isset($request['parent_type']) || !$request['parent_type']) {

            // there is no any selected option in 'Related To' field
            // so impossible to replace variables to selected bean data

            $isValidRequestForReplaceEmailVariables = false;
            $this->log('There isn\'t any selected BEAN-TYPE option in \'Related To\' dropdown');
        }


        if (!isset($request['parent_id']) || !$request['parent_id']) {

            // there is no any selected bean in 'Related To' field
            // so impossible to replace variables to selected bean data

            $isValidRequestForReplaceEmailVariables = false;
            $this->log('There isn\'t any selected BEAN-ELEMENT in \'Related To\' field');
        }


        return $isValidRequestForReplaceEmailVariables;
    }

    /**
     * Add a message to log
     *
     * @param string $msg
     * @param string $level
     */
    private function log($msg, $level = 'info')
    {
        $GLOBALS['log']->$level($msg);
    }

    /**
     * @see EmailsViewCompose
     */
    public function action_SaveDraft()
    {
        $this->bean = $this->bean->populateBeanFromRequest($this->bean, $_REQUEST);
        
        //thienpb custom save email schedule
        if(!empty($_REQUEST['schedule_time']) && !is_nan($_REQUEST['schedule_time'])){
            $this->bean->status = 'email_schedule';
            $this->bean->schedule_timestamp_c = $_REQUEST['schedule_time'];
            $this->send_sms_schedule();
        }else{
            $this->bean->status = 'draft';
        }
        $this->bean->save();
        $this->bean->handleMultipleFileAttachments();
        $this->view = 'savedraftemail';
    }

    /**
     * @see EmailsViewCompose
     */
    public function action_DeleteDraft()
    {
        $this->bean->deleted = '1';
        $this->bean->status = 'draft';
        $this->bean->save();
        $this->view = 'deletedraftemail';
    }


    /**
     * @see EmailsViewPopup
     */
    public function action_Popup()
    {
        $this->view = 'popup';
    }

    /**
     * Gets the values of the "from" field
     * includes the signatures for each account
     */
    public function action_getFromFields()
    {
        global $current_user;
        global $sugar_config;
        $email = new Email();
        $email->email2init();
        $ie = new InboundEmail();
        $ie->email = $email;
        $accounts = $ieAccountsFull = $ie->retrieveAllByGroupIdWithGroupAccounts($current_user->id);
        $accountSignatures = $current_user->getPreference('account_signatures', 'Emails');
        $showFolders = unserialize(base64_decode($current_user->getPreference('showFolders', 'Emails')));
        if ($accountSignatures != null) {
            $emailSignatures = unserialize(base64_decode($accountSignatures));
        } else {
            $GLOBALS['log']->warn('User ' . $current_user->name . ' does not have a signature');
        }

        $defaultEmailSignature = $current_user->getDefaultSignature();
        if (empty($defaultEmailSignature)) {
            $defaultEmailSignature = array(
                'html' => '<br>',
                'plain' => '\r\n',
            );
            $defaultEmailSignature['no_default_available'] = true;
        } else {
            $defaultEmailSignature['no_default_available'] = false;
        }

        $prependSignature = $current_user->getPreference('signature_prepend');

        $data = array();
        foreach ($accounts as $inboundEmailId => $inboundEmail) {
            if (in_array($inboundEmail->id, $showFolders)) {
                $storedOptions = unserialize(base64_decode($inboundEmail->stored_options));
                $isGroupEmailAccount = $inboundEmail->isGroupEmailAccount();
                $isPersonalEmailAccount = $inboundEmail->isPersonalEmailAccount();

                $oe = new OutboundEmail();
                $oe->retrieve($storedOptions['outbound_email']);
                //VUT-S-Add current user-id
                $check = false;
                if (strpos(strtolower($storedOptions['from_name']) , strtolower($current_user->name) ) !== false) {
                    $check = true;
                }
                //VUT-E-Add current user-id
                
                $dataAddress = array(
                    'type' => $inboundEmail->module_name,
                    'id' => $inboundEmail->id,
                    'user_id' => $check ? $current_user->id : '',
                    'attributes' => array(
                        'reply_to' => $storedOptions['reply_to_addr'],
                        'name' => $storedOptions['from_name'],
                        'from' => $storedOptions['from_addr'],
                        'from_name' => $storedOptions['from_name'],
                    ),
                    'prepend' => $prependSignature,
                    'isPersonalEmailAccount' => $isPersonalEmailAccount,
                    'isGroupEmailAccount' => $isGroupEmailAccount,
                    'outboundEmail' => array(
                        'id' => $oe->id,
                        'name' => $oe->name,
                    ),
                );

                // Include signature
                if (isset($emailSignatures[$inboundEmail->id]) && !empty($emailSignatures[$inboundEmail->id])) {
                    $emailSignatureId = $emailSignatures[$inboundEmail->id];
                } else {
                    $emailSignatureId = '';
                }

                $signature = $current_user->getSignature($emailSignatureId);
                if (!$signature) {
                    if ($defaultEmailSignature['no_default_available'] === true) {
                        $dataAddress['emailSignatures'] = $defaultEmailSignature;
                    } else {
                        $dataAddress['emailSignatures'] = array(
                            'html' => utf8_encode(html_entity_decode($defaultEmailSignature['signature_html'])),
                            'plain' => $defaultEmailSignature['signature'],
                        );
                    }
                } else {
                    $dataAddress['emailSignatures'] = array(
                        'html' => utf8_encode(html_entity_decode($signature['signature_html'])),
                        'plain' => $signature['signature'],
                    );
                }

                $data[] = $dataAddress;
            }
        }

        if (isset($sugar_config['email_allow_send_as_user']) && ($sugar_config['email_allow_send_as_user'])) {
            require_once('include/SugarEmailAddress/SugarEmailAddress.php');
            $sugarEmailAddress = new SugarEmailAddress();
            $userAddressesArr = $sugarEmailAddress->getAddressesByGUID($current_user->id, 'Users');
            foreach ($userAddressesArr as $userAddress) {
                if ($userAddress['reply_to_addr'] === '1') {
                    $fromString =  $current_user->full_name . ' &lt;' . $userAddress['email_address'] . '&gt;';
                } else {
                    $fromString =  $current_user->full_name . ' &lt;' . $current_user->email1 . '&gt;';
                }
                // ($userAddress['reply_to_addr'] === '1') ? $current_user->email1 : $userAddress['email_address']
                $data[] = array(
                    'type' => 'personal',
                    'id' => $userAddress['email_address_id'],
                    'attributes' => array(
                        'from' => $fromString,
                        'reply_to' =>  $current_user->full_name . ' &lt;' . $userAddress['email_address']  . '&gt;',
                        'name' => $current_user->full_name,
                    ),
                    'prepend' => $prependSignature,
                    'isPersonalEmailAccount' => true,
                    'isGroupEmailAccount' => false,
                    'emailSignatures' => array(
                        'html' => utf8_encode(html_entity_decode($defaultEmailSignature['signature_html'])),
                        'plain' => $defaultEmailSignature['signature'],
                    ),
                );
            }
            unset($userAddress);
        }

        $oe = new OutboundEmail();
        if ($oe->isAllowUserAccessToSystemDefaultOutbound()) {
            $system = $oe->getSystemMailerSettings();
            $data[] = array(
                'type' => 'system',
                'id' => $system->id,
                'attributes' => array(
                    'reply_to' => $system->smtp_from_addr,
                    'from' => $system->smtp_from_addr,
                    'name' => $system->smtp_from_name,
                    'oe' => $system->mail_smtpuser,
                ),
                'prepend' => false,
                'isPersonalEmailAccount' => false,
                'isGroupEmailAccount' => true,
                'outboundEmail' => array(
                    'id' => $system->id,
                    'name' => $system->name,
                ),
                'emailSignatures' => $defaultEmailSignature,
            );
        }

        $dataEncoded = json_encode(array('data' => $data), JSON_UNESCAPED_UNICODE);
        echo utf8_decode($dataEncoded);
        $this->view = 'ajax';
    }

    /**
     * Returns attachment data to ajax call
     */
    public function action_GetDraftAttachmentData()
    {
        $data['attachments'] = array();

        if (!empty($_REQUEST['id'])) {
            $bean = BeanFactory::getBean('Emails', $_REQUEST['id']);
            $data['draft'] = $bean->status == 'draft' ? 1 : 0;
            if (!$attachmentBeans = BeanFactory::getBean('Notes')
                ->get_full_list('', "parent_id = '" . $_REQUEST['id'] . "'")) {
                LoggerManager::getLogger()->warn('No attachment Note for selected Email.');
            } else {
                foreach ($attachmentBeans as $attachmentBean) {
                    $data['attachments'][] = array(
                        'id' => $attachmentBean->id,
                        'name' => $attachmentBean->name,
                        'file_mime_type' => $attachmentBean->file_mime_type,
                        'filename' => $attachmentBean->filename,
                        'parent_type' => $attachmentBean->parent_type,
                        'parent_id' => $attachmentBean->parent_id,
                        'description' => $attachmentBean->description,
                    );
                }
            }
        }

        $dataEncoded = json_encode(array('data' => $data), JSON_UNESCAPED_UNICODE);
        echo utf8_decode($dataEncoded);
        $this->view = 'ajax';
    }
    public function parseProduct($arr, $key, $type) {
        $list = '';
        if($type == 'products') {
            foreach($arr as $item) {
                if($item['productName'] !== null) {
                    $list .= '<div style="font-size: 15px;margin-top: 10px;" data-mce-style="font-size: 15px;margin-top: 10px;">'.$item["qty_main_dk1_".$key].'X '.$item["productName"].'</div>';
                } else {
                    $list .= '<div style="font-size: 15px;margin-top: 10px;" data-mce-style="font-size: 15px;margin-top: 10px;">&nbsp;</div>';
                }
            }
        } else if($type == 'wifi') {
            foreach($arr as $item) {
                if($item['productName'] !== null) {
                    $list .= '<div style="font-size: 15px;margin-top: 10px;" data-mce-style="font-size: 15px;margin-top: 10px;">'.$item["qty_wifi_dk1_".$key].'X '.$item["wifi_dk_type1_".$key].'</div>';
                } else {
                    $list .= '<div style="font-size: 15px;margin-top: 10px;" data-mce-style="font-size: 15px;margin-top: 10px;">&nbsp;</div>';
                }
            }
        }
        
        return $list;
    }

    public function action_CheckEmail()
    {
        $inboundEmail = new InboundEmail();
        $inboundEmail->syncEmail();

        echo json_encode(array('response' => array()));
        $this->view = 'ajax';
    }

    /**
     * Used to list folders in the list view
     */
    public function action_GetFolders()
    {
        require_once 'include/SugarFolders/SugarFolders.php';
        global $current_user, $mod_strings;
        $email = new Email();
        $email->email2init();
        $ie = new InboundEmail();
        $ie->email = $email;
        $GLOBALS['log']->debug('********** EMAIL 2.0 - Asynchronous - at: refreshSugarFolders');
        $rootNode = new ExtNode('', '');
        $folderOpenState = $current_user->getPreference('folderOpenState', 'Emails');
        $folderOpenState = empty($folderOpenState) ? '' : $folderOpenState;

        try {
            $ret = $email->et->folder->getUserFolders(
                $rootNode,
                sugar_unserialize($folderOpenState),
                $current_user,
                true
            );
            $out = json_encode(array('response' => $ret));
        } catch (SugarFolderEmptyException $e) {
            $GLOBALS['log']->warn($e->getMessage());
            $out = json_encode(array('errors' => array($mod_strings['LBL_ERROR_NO_FOLDERS'])));
        }

        echo $out;
        $this->view = 'ajax';
    }


    /**
     * @see EmailsViewDetailnonimported
     */
    public function action_DisplayDetailView()
    {
        $result = null;

        $db = DBManagerFactory::getInstance();
        $emails = BeanFactory::getBean("Emails");

        $uid = $_REQUEST['uid'];
        $inboundEmailRecordId = $_REQUEST['inbound_email_record'];

        $validator = new SuiteValidator();

        if ($validator->isValidId($uid)) {
            $subQuery = "`mailbox_id` = " . $db->quoted($inboundEmailRecordId) . " AND `uid` = " . $db->quoted($uid);
            $result = $emails->get_full_list('', $subQuery);
        }

        if (empty($result)) {
            $this->view = 'detailnonimported';
        } else {
            header('location:index.php?module=Emails&action=DetailView&record=' . $result[0]->id);
        }
    }

    /**
     * @see EmailsViewDetailnonimported
     */
    public function action_ImportAndShowDetailView()
    {
        $db = DBManagerFactory::getInstance();
        if (isset($_REQUEST['inbound_email_record']) && !empty($_REQUEST['inbound_email_record'])) {
            $inboundEmail = new InboundEmail();
            $inboundEmail->retrieve($db->quote($_REQUEST['inbound_email_record']), true, true);
            $inboundEmail->connectMailserver();
            $importedEmailId = $inboundEmail->returnImportedEmail($_REQUEST['msgno'], $_REQUEST['uid']);

            // Set the fields which have been posted in the request
            $this->bean = $this->setAfterImport($importedEmailId, $_REQUEST);

            if ($importedEmailId !== false) {
                header('location:index.php?module=Emails&action=DetailView&record=' . $importedEmailId);
            }
        } else {
            // When something fail redirect user to index
            header('location:index.php?module=Emails&action=index');
        }
    }

    /**
     * @see EmailsViewImport
     */
    public function action_ImportView()
    {
        $this->view = 'import';
    }

    public function action_GetCurrentUserID()
    {
        global $current_user;
        echo json_encode(array("response" => $current_user->id));
        $this->view = 'ajax';
    }

    public function action_ImportFromListView()
    {
        $db = DBManagerFactory::getInstance();

        if (isset($_REQUEST['inbound_email_record']) && !empty($_REQUEST['inbound_email_record'])) {
            $inboundEmail = BeanFactory::getBean('InboundEmail', $db->quote($_REQUEST['inbound_email_record']));
            if (isset($_REQUEST['folder']) && !empty($_REQUEST['folder'])) {
                $inboundEmail->mailbox = $_REQUEST['folder'];
            }
            $inboundEmail->connectMailserver();

            if (isset($_REQUEST['all']) && $_REQUEST['all'] === 'true') {
                // import all in folder
                $importedEmailsId = $inboundEmail->importAllFromFolder();
                foreach ($importedEmailsId as $importedEmailId) {
                    $this->bean = $this->setAfterImport($importedEmailId, $_REQUEST);
                }
            } else {
                foreach ($_REQUEST['uid'] as $uid) {
                    $importedEmailId = $inboundEmail->returnImportedEmail($_REQUEST['msgno'], $uid);
                    $this->bean = $this->setAfterImport($importedEmailId, $_REQUEST);
                }
            }
        } else {
            $GLOBALS['log']->fatal('EmailsController::action_ImportFromListView() missing inbound_email_record');
        }

        header('location:index.php?module=Emails&action=index');
    }

    public function action_ReplyTo()
    {
        $this->composeBean($_REQUEST, self::COMPOSE_BEAN_MODE_REPLY_TO);
        $this->view = 'compose';
    }

    public function action_ReplyToAll()
    {
        $this->composeBean($_REQUEST, self::COMPOSE_BEAN_MODE_REPLY_TO_ALL);
        $this->view = 'compose';
    }

    public function action_Forward()
    {
        $this->composeBean($_REQUEST, self::COMPOSE_BEAN_MODE_FORWARD);
        $this->view = 'compose';
    }

    /**
     * Fills compose view body with the output from PDF Template
     * @see sendEmail::send_email()
     */
    public function action_ComposeViewWithPdfTemplate()
    {
        $this->composeBean($_REQUEST, self::COMPOSE_BEAN_WITH_PDF_TEMPLATE);
        global $current_user;
        //If Jane Au > no add BCC
        if ($current_user->id != "93883222-d915-6c7b-54f3-5dfae2a09ad9") {
            $this->bean->bcc_addrs_names = $current_user->name.' <'.$current_user->email1.'>'; //VUT-Add BCC email current user
        }
        //
        if($_GET['return_module'] == "AOS_Quotes" && $_REQUEST['return_id'] != ""){
            
            $focus = BeanFactory::getBean($_GET['return_module'], $_REQUEST['return_id']);

            $invoice_file_attachmens = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c ."/");
            //print_r($invoice_file_attachmens);die();
            $noteArray = array();
            //thienpb code - block file for email
            //$invoice_file_attachmens = array_diff($invoice_file_attachmens,json_decode(htmlspecialchars_decode($focus->block_files_for_email_c)));
            $quote_file_exist = false;
            /**VUT-Attachment file "Proposed_Install_Location" */
            $name_file_include = 'Proposed_Install_Location';
            $num_quote_SG = $focus->solargain_quote_number_c;
            $num_quote_SG_Tesla = $focus->solargain_tesla_quote_number_c ;
            if (count($invoice_file_attachmens)>0) foreach ($invoice_file_attachmens as $att){
            // Create Note
            //if(strpos($att, "Bill") !== false) continue;
                //check button send tesla and quote nomal
                if($num_quote_SG_Tesla !== ''&& (strpos($att, 'Quote_#'.$num_quote_SG_Tesla) === false )) continue;
                if($num_quote_SG !== '' && (strpos($att, 'Quote_#'.$num_quote_SG) === false )) continue;
                if (strpos(strtolower($att),strtolower($name_file_include)) === false) continue;
                $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->pre_install_photos_c ."/" . $att ;
                if (!is_file($source)) continue;

                $noteTemplate = new Note();
                $noteTemplate->id = create_guid();
                $noteTemplate->new_with_id = true; // duplicating the note with files
                $noteTemplate->parent_id = $this->bean->id;
                $noteTemplate->parent_type = 'Emails';
                $noteTemplate->date_entered = '';
                $noteTemplate->file_mime_type = 'application/pdf';
                $noteTemplate->filename = $att;
                $noteTemplate->name = $att;

                $noteTemplate->save();

                $destination = realpath(dirname(__FILE__) . '/../../').'/upload/'.$noteTemplate->id;
                //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                //copy( $source, $destination);
                if (!symlink($source, $destination)) {
                    $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                }
                //$noteArray[] = $noteTemplate;
                $this->bean->attachNote($noteTemplate);
                // Special check if source has Quote file
                if(strpos($att, 'Quote_') !== false){
                    $quote_file_exist = true;
                }
            }
            //die();
            if($quote_file_exist){
                // Do some extra thing here
                /*$focusName = "Leads";
                $focus = BeanFactory::getBean($focusName, $_REQUEST['lead_id']);
                $first_name = $focus->first_name;
                $this->bean->send_sms = 1;

                $phone_number = preg_replace("/^0/", "+61", preg_replace('/\D/', '', $focus->phone_mobile));
                $phone_number = preg_replace("/^61/", "+61", $phone_number);
                $this->bean->number_client = $phone_number;//$_REQUEST['sms_received'];
                $this->bean->number_receive_sms = "matthew_paul_client";
                //$this->bean->sms_message = "Hi $first_name your pure-electric quote has been sent to your inbox, if you can't find it please check your spam folder";
                $assigned_name = $focus->assigned_user_name;
                $this->bean->sms_message = "Hi $first_name, Your Solar PV quote has been prepared and sent to your email inbox. If you can't find it, check your spam folder and if no success still, please don't hesitate to contact us. Regards, $assigned_name";
                */
            }
        }

        // Add BCC email address Saler for PO Email
        if($_GET['return_module'] == "PO_purchase_order" && $_REQUEST['return_id'] != ""){  
            $focus = BeanFactory::getBean($_GET['return_module'], $_REQUEST['return_id']);
            $assign_user = new User();
            $assign_user->retrieve($focus->assigned_user_id);
            $this->bean->bcc_addrs_names = ' ';
            if(!empty($this->bean->bcc_addrs_names) && trim($this->bean->bcc_addrs_names) != '') {
                $this->bean->bcc_addrs_names .= ',';
            }
            $this->bean->bcc_addrs_names .= $assign_user->name.' <'.$assign_user->email1.'>'; 
        }
        
        //VUT-S-Attach file "proposed install location" 2020/06/30
        if ($_GET['return_module'] == "AOS_Invoices" && $_REQUEST['return_id'] != "") {
            $focus = BeanFactory::getBean($_GET['return_module'], $_REQUEST['return_id']);

            $invoice_file_attachments = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->installation_pictures_c ."/");
            $name_file_include = 'Proposed_Install_Location';
            //VUT - S - check install date <> today ==> dont include Proposed Install Location (https://trello.com/c/RClziQkW/2984-invoice-when-we-click-email-invocie-if-the-customer-balance-owing-is-0-and-the-install-date-is-over-current-date-please-dont-inc?menu=filter&filter=*)
            // $today = new DateTime();
            global $timedate;
            $today_datetime = explode(" ",$timedate->now());
            $today = new DateTime(str_replace("/","-",$today_datetime[0]));
            $install_datetime = explode(" ", $focus->installation_date_c);
            $install_date = new DateTime(str_replace("/","-",$install_datetime[0]));
            if (($install_date->format('Y-m-d') < $today->format('Y-m-d')) && floatval($focus->total_balance_owing_c) <= 0) {
                $check_date = 1;
            } else {
                $check_date = 0;
            }
            //VUT - E - check install date <> today ==> dont include Proposed Install Location
            if (count($invoice_file_attachments)>0 ) foreach ($invoice_file_attachments as $att){
                $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $focus->installation_pictures_c ."/" . $att ;
                if(!is_file($source)) continue;
                if (strpos(strtolower($att),strtolower($name_file_include)) && $check_date == 0) {
                    $noteTemplate = new Note();
                    $noteTemplate->id = create_guid();
                    $noteTemplate->new_with_id = true; // duplicating the note with files
                    $noteTemplate->parent_id = $this->bean->id;
                    $noteTemplate->parent_type = 'Emails';
                    $noteTemplate->date_entered = '';
                    // $noteTemplate->file_mime_type = 'application/pdf';
                    $noteTemplate->filename = $att;
                    $noteTemplate->name = $att;

                    $noteTemplate->save();

                    $destination = realpath(dirname(__FILE__) . '/../../').'/upload/'.$noteTemplate->id;
                    if (!symlink($source, $destination)) {
                        $GLOBALS['log']->error("upload_file could not copy [ {$source} ] to [ {$destination} ]");
                    }
                    $this->bean->attachNote($noteTemplate);
                }
            }
        }
        //VUT-S-Attach file "proposed install location"
        $this->resizeImageNote($this->bean->id);
        $this->view = 'compose';
    }

    public function action_SendDraft()
    {
        $this->view = 'ajax';
        echo json_encode(array());
    }


    /**
     * @throws SugarControllerException
     */
    public function action_MarkEmails()
    {
        $this->markEmails($_REQUEST);
        echo json_encode(array('response' => true));
        $this->view = 'ajax';
    }

    /**
     * @throws SugarControllerException
     */
    public function action_DeleteFromImap()
    {
        $uid = $_REQUEST['uid'];
        $db = DBManagerFactory::getInstance();

        if (!empty($_REQUEST['inbound_email_record'])) {
            $emailID = $_REQUEST['inbound_email_record'];
        } elseif (!empty($_REQUEST['record'])) {
            $emailID = (new Email())->retrieve($_REQUEST['record']);
        } else {
            throw new SugarControllerException('No Inbound Email record in request');
        }

        $inboundEmail = BeanFactory::getBean('InboundEmail', $db->quote($emailID));

        if (is_array($uid)) {
            $uid = implode(',', $uid);
            $this->view = 'ajax';
        }

        if (isset($uid)) {
            $inboundEmail->deleteMessageOnMailServer($uid);
        } else {
            LoggerManager::getLogger()->fatal('EmailsController::action_DeleteFromImap() missing uid');
        }

        if ($this->view === 'ajax') {
            echo json_encode(['response' => true]);
        } else {
            header('location:index.php?module=Emails&action=index');
        }
    }

    /**
     * @param array $request
     * @throws SugarControllerException
     */
    public function markEmails($request)
    {
        // validate the request

        if (!isset($request['inbound_email_record']) || !$request['inbound_email_record']) {
            throw new SugarControllerException('No Inbound Email record in request');
        }

        if (!isset($request['folder']) || !$request['folder']) {
            throw new SugarControllerException('No Inbound Email folder in request');
        }

        // connect to requested inbound email server
        // and select the folder

        $ie = $this->getInboundEmail($request['inbound_email_record']);
        $ie->mailbox = $request['folder'];
        $ie->connectMailserver();

        // get requested UIDs and flag type

        $UIDs = $this->getRequestedUIDs($request);
        $type = $this->getRequestedFlagType($request);

        // mark emails
        $ie->markEmails($UIDs, $type);
    }

    /**
     * @param array $request
     * @param int $mode
     * @throws InvalidArgumentException
     * @see EmailsController::COMPOSE_BEAN_MODE_UNDEFINED
     * @see EmailsController::COMPOSE_BEAN_MODE_REPLY_TO
     * @see EmailsController::COMPOSE_BEAN_MODE_REPLY_TO_ALL
     * @see EmailsController::COMPOSE_BEAN_MODE_FORWARD
     */
    public function composeBean($request, $mode = self::COMPOSE_BEAN_MODE_UNDEFINED)
    {
        if ($mode === self::COMPOSE_BEAN_MODE_UNDEFINED) {
            throw new InvalidArgumentException('EmailController::composeBean $mode argument is COMPOSE_BEAN_MODE_UNDEFINED');
        }

        $db = DBManagerFactory::getInstance();
        global $mod_strings;


        global $current_user;
        $email = new Email();
        $email->email2init();
        $ie = new InboundEmail();
        $ie->email = $email;
        $accounts = $ieAccountsFull = $ie->retrieveAllByGroupIdWithGroupAccounts($current_user->id);
        if (!$accounts) {
            $url = 'index.php?module=Users&action=EditView&record=' . $current_user->id . "&showEmailSettingsPopup=1";
            SugarApplication::appendErrorMessage(
                "You don't have any valid email account settings yet. <a href=\"$url\">Click here to set your email accounts.</a>"
            );
        }


        if (isset($request['record']) && !empty($request['record'])) {
            $parent_name = $this->bean->parent_name;
            $this->bean->retrieve($request['record']);
        } else {
            $inboundEmail = BeanFactory::getBean('InboundEmail', $db->quote($request['inbound_email_record']));
            $inboundEmail->connectMailserver();
            $importedEmailId = $inboundEmail->returnImportedEmail($request['msgno'], $request['uid']);
            $this->bean->retrieve($importedEmailId);
        }

        $_REQUEST['return_module'] = 'Emails';
        $_REQUEST['return_Action'] = 'index';

        if (isset($parent_name)) {
            $this->bean->parent_name = $parent_name;
        }

        if ($mode === self::COMPOSE_BEAN_MODE_REPLY_TO || $mode === self::COMPOSE_BEAN_MODE_REPLY_TO_ALL) {
            // Move email addresses from the "from" field to the "to" field
            $this->bean->to_addrs = $this->bean->from_addr;
            isValidEmailAddress($this->bean->to_addrs);
            $this->bean->to_addrs_names = $this->bean->from_addr_name;
        } elseif ($mode === self::COMPOSE_BEAN_MODE_FORWARD) {
            $this->bean->to_addrs = '';
            $this->bean->to_addrs_names = '';
        } elseif ($mode === self::COMPOSE_BEAN_WITH_PDF_TEMPLATE) {
            // Get Related To Field
            // Populate to
            // BinhNT hard code for send from Account
            if($_GET['return_module'] == "AOS_Invoices"){
                global $current_user;
                $showFolders = sugar_unserialize(base64_decode($current_user->getPreference('showFolders', 'Emails')));
                if (empty($showFolders)) {
                    $showFolders = array();
                }
                if ($current_user->hasPersonalEmail()) {
                    $personals = $this->retrieveByGroupId($current_user->id);
                }
                $inboundEmailID = $current_user->getPreference('defaultIEAccount', 'Emails');
                if(count($personals)) foreach($personals as $personal) {
                    if (in_array($personal->id, $showFolders)) {
                        if(strpos(strtolower($personal->name), "account") !== false) {
                            $inboundEmailID = $personal->id;
                        }
                    }
                }
                $this->bean->mailbox_id = $inboundEmailID;
            }
        }

        if ($mode !== self::COMPOSE_BEAN_MODE_REPLY_TO_ALL) {
            $this->bean->cc_addrs_arr = array();
            $this->bean->cc_addrs_names = '';
            $this->bean->cc_addrs = '';
            $this->bean->cc_addrs_names = 'Pure Info <info@pure-electric.com.au>'; // BinhNT
            $this->bean->cc_addrs_ids = '';
            $this->bean->cc_addrs_emails = '';
            //dung code bbc email lee for account lee
        }

        if ($mode === self::COMPOSE_BEAN_MODE_REPLY_TO || $mode === self::COMPOSE_BEAN_MODE_REPLY_TO_ALL) {
            // Add Re to subject
            $this->bean->name = $mod_strings['LBL_RE'] . $this->bean->name;
        } else {
            if ($mode === self::COMPOSE_BEAN_MODE_FORWARD) {
                // Add FW to subject
                $this->bean->name = $mod_strings['LBL_FW'] . $this->bean->name;
            }
        }

        if (empty($this->bean->name)) {
            $this->bean->name = $mod_strings['LBL_NO_SUBJECT'] . $this->bean->name;
        }
        // BinhNT code here
        //thienpb code add invoice number
        if($_GET['return_module'] == "AOS_Invoices" && !isset($_REQUEST['changedSubject'])){
            $invoice_bean = new AOS_Invoices();
            $invoice_bean->retrieve($_GET['return_id']);
            if($invoice_bean->id){
                $name_check = strtolower($this->bean->name);
                if(strpos($name_check ,'invoice #') !== false){
                    $this->bean->name = "Pure Electric ". $this->bean->name;
                }else{
                    $this->bean->name = "Pure Electric Invoice #".$invoice_bean->number. $this->bean->name;
                }
            }else{
                $this->bean->name = "Pure Electric ". $this->bean->name;
            }
        }else{
            if(strpos($this->bean->name,"Pure Electric") !== false){
                $this->bean->name = $this->bean->name;
            }else{
                $this->bean->name = "Pure Electric ". $this->bean->name;
            }
            
        }

        // Move body into original message
        if (!empty($this->bean->description_html)) {
            $this->bean->description = '<br>' . $mod_strings['LBL_ORIGINAL_MESSAGE_SEPERATOR'] . '<br>' .
                $this->bean->description_html;
        } else {
            if (!empty($this->bean->description)) {
                $this->bean->description = PHP_EOL . $mod_strings['LBL_ORIGINAL_MESSAGE_SEPERATOR'] . PHP_EOL .
                    $this->bean->description;
            }
        }
        // BinhNT

        $this->bean->description = str_replace("'", "&#039;", $this->bean->description);
        $this->bean->description_html = str_replace("'", "&#039;",$this->bean->description_html);
        
    }


    function retrieveByGroupId($groupId)
    {
        $q = '
        SELECT id FROM inbound_email
        WHERE
            group_id = \'' . $groupId . '\' AND
            deleted = 0 AND
            status = \'Active\'';
        $db = DBManagerFactory::getInstance();
        $r = $db->query($q, true);

        $beans = array();
        while ($a = $db->fetchByAssoc($r)) {
            $ie = new InboundEmail();
            $ie->retrieve($a['id']);
            $beans[$a['id']] = $ie;
        }

        return $beans;
    }

    public function parse_sms_template($smsTemplate, $focus)
    {
        global $beanList, $app_list_strings;
        $body =  $smsTemplate->body_c;
        $address_customer =  $focus->primary_address_street . ' ' .$focus->primary_address_city . ' ' .$focus->primary_address_state . ' ' .$focus->primary_address_postalcode;
        $body = str_replace("\$first_name", $focus->first_name, $body);
        $body = str_replace("\$last_name", $focus->last_name,$body);
        $body = str_replace("\$address",$address_customer, $body);
        //VUT-S- $quote_number in sms template
            if ($focus->module_dir == 'AOS_Quotes') {
                $body = str_replace("\$quote_number",$focus->number, $body);
            } else if ($focus->module_dir == 'AOS_Invoices') {
                $body = str_replace("\$quote_number",$focus->quote_number, $body);
                $product_type =  $app_list_strings['quote_type_list'][$focus->quote_type_c];
                $body = str_replace("\$product_type",$product_type, $body);
            } else {
                $body = str_replace("\$quote_number","", $body);
            }
        //VUT-E- $quote_number in sms template
        if($focus->assigned_user_id == '61e04d4b-86ef-00f2-c669-579eb1bb58fa') {
            //paul
            $body = str_replace("\$assigned_user_first_name", 'Paul', $body);
            $body = str_replace("\$assigned_user_email", 'paul.szuster@pure-electric.com.au', $body);
            $body = str_replace("\$assigned_user_phone_number", '0423 494 949', $body);
        }elseif ($focus->assigned_user_id == '71adfe6a-5e9e-1fc2-3b6c-6054c8e33dcb') {
            //Michael Golden
            $body = str_replace("\$assigned_user_first_name", 'Michael', $body);
            $body = str_replace("\$assigned_user_email", 'michael.golden@pure-electric.com.au', $body);
            $body = str_replace("\$assigned_user_phone_number", '0416 185 005', $body);
        } else {
            //matt
            $body = str_replace("\$assigned_user_first_name", 'Matthew', $body);
            $body = str_replace("\$assigned_user_email", 'matthew.wright@pure-electric.com.au', $body);
            $body = str_replace("\$assigned_user_phone_number", '0421 616 733', $body);
        }
        return $body;
    }

    public function check_exist_file($source, $string) {
        $file_array = scandir($source);
        $file_array = array_diff($file_array, array('.', '..'));
        $result = array();
        foreach($file_array as $file){
            if (strpos(strtolower($file), strtolower($string)) !== false && strpos($file, $string) == 0) {
                $result[] = $file;
            }
        }
        return $result;
    } 
    public function send_sms_schedule() {
        if (isset($_REQUEST['send_sms']) && ($_REQUEST['send_sms']!== "") && $_REQUEST['send_sms'] != "false") {
            $phone_number_customer = $_REQUEST['number_client'];
            if($phone_number_customer == '') return;
            $from_phone_number = $_REQUEST['number_send_sms'];
            $content_messager = $_REQUEST['sms_message'];
            $content_messager = str_replace("$", "\\$", html_entity_decode($content_messager, ENT_QUOTES));
            $module =$_REQUEST['return_module'];
            $record_id = $_REQUEST['return_id'];
            $status = 'schedule';
            $timestamp = $_REQUEST['schedule_time'];
            $short_content_messager = explode(" ",$content_messager);
            $short_content_messager = array_slice($short_content_messager,0,10);
            $short_content_messager = $phone_number_customer .' - '  .implode(" ",$short_content_messager) ." ...";
            $uniqid = uniqid();
    
            $sms = new pe_smsmanager();
            $sms->description = $content_messager;
            $sms->name = $short_content_messager;
            $sms->message_uniqid_c = $uniqid;
            date_default_timezone_set('UTC');
            $dateAUS =  date( "Y-m-d H:i:s",$timestamp);
            $sms->time_schedule_c = $dateAUS ;
            $sms->status_c = 'schedule' ;
            
            $sms->save();
            if($module == 'AOS_Invoices'){
                $bean_module = new AOS_Invoices();
                $bean_module = $bean_module->retrieve($record_id);
                $sms->load_relationship('pe_smsmanager_aos_invoices');
                $sms->pe_smsmanager_aos_invoices->add($bean_module);
                $internal_notes = new pe_internal_note();
                $internal_notes->type_inter_note_c = 'sms_out';
                $internal_notes->description = $sms->name;
                $internal_notes->save();
                $internal_notes->load_relationship('aos_invoices_pe_internal_note_1');
                $internal_notes->aos_invoices_pe_internal_note_1->add($bean_module->id);
            }elseif($module == 'Accounts'){
                $bean_module = new Account();
                $bean_module = $bean_module->retrieve($record_id);
                $sms->load_relationship('pe_smsmanager_accounts');
                $sms->pe_smsmanager_accounts->add($bean_module);
            }elseif($module == 'Leads'){
                $bean_module = new Lead();
                $bean_module = $bean_module->retrieve($record_id);
                $sms->load_relationship('pe_smsmanager_leads');
                $sms->pe_smsmanager_leads->add($bean_module);
                $internal_notes = new pe_internal_note();
                $internal_notes->type_inter_note_c = 'sms_out';
                $internal_notes->description = $sms->name;
                $internal_notes->save();
                $internal_notes->load_relationship('leads_pe_internal_note_1');
                $internal_notes->leads_pe_internal_note_1->add($bean_module->id);
            }elseif($module == 'Contacts'){
                $bean_module = new Contact();
                $bean_module = $bean_module->retrieve($record_id);
                $sms->load_relationship('pe_smsmanager_contacts');
                $sms->pe_smsmanager_contacts->add($bean_module);
            }elseif($module == 'AOS_Quotes'){
                $bean_module = new AOS_Quotes();
                $bean_module = $bean_module->retrieve($record_id);
                $sms->load_relationship('pe_smsmanager_aos_quotes');
                $sms->pe_smsmanager_aos_quotes->add($bean_module);
                $internal_notes = new pe_internal_note();
                $internal_notes->type_inter_note_c = 'sms_out';
                $internal_notes->description = $sms->name;
                $internal_notes->save();
                $internal_notes->load_relationship('aos_quotes_pe_internal_note_1');
                $internal_notes->aos_quotes_pe_internal_note_1->add($bean_module->id);
            }else{}
    
            $sms->save();
    
            global $sugar_config;
    
            $message_dir = "";
            if( $_POST['from_phone_number'] == "+61421616733"){
                $message_dir = '/var/www/message2';
            }
            elseif( $_POST['from_phone_number'] == "+61490942067"){
                $message_dir = '/var/www/message';
            }
            
            exec("cd ".$message_dir."; php send-message-scheduled.php sms ".$phone_number_customer." ".escapeshellarg($content_messager)." ".$timestamp .' ' .$uniqid);
            $phone_number = "+61421616733";
            $content_messager = "Sent to: ".$phone_number_customer.". ".$content_messager;
            exec("cd ".$message_dir."; php send-message-scheduled.php sms ".$phone_number." ".escapeshellarg($content_messager)." ".$timestamp .' ' .$uniqid);
        } 
    }
    /**
     * @param $request
     * @return null|string
     */
    private function getRequestedUIDs($request)
    {
        $ret = $this->getRequestedArgument($request, 'uid');
        if (is_array($ret)) {
            $ret = implode(',', $ret);
        }

        return $ret;
    }

    /**
     * @param array $request
     * @return null|mixed
     */
    private function getRequestedFlagType($request)
    {
        $ret = $this->getRequestedArgument($request, 'type');

        return $ret;
    }

    /**
     * @param array $request
     * @param string $key
     * @return null|mixed
     */
    private function getRequestedArgument($request, $key)
    {
        if (!isset($request[$key])) {
            $GLOBALS['log']->error("Requested key is not set: ");

            return null;
        }

        return $request[$key];
    }

    /**
     * return an Inbound Email by requested record
     *
     * @param string $record
     * @return InboundEmail
     * @throws SugarControllerException
     */
    private function getInboundEmail($record)
    {
        $db = DBManagerFactory::getInstance();
        $ie = BeanFactory::getBean('InboundEmail', $db->quote($record));
        if (!$ie) {
            throw new SugarControllerException("BeanFactory can't resolve an InboundEmail record: $record");
        }

        return $ie;
    }

    /**
     * @param array $request
     * @return bool|Email
     * @see Email::id
     * @see EmailsController::action_ImportAndShowDetailView()
     * @see EmailsController::action_ImportView()
     */
    protected function setAfterImport($importedEmailId, $request)
    {
        $emails = BeanFactory::getBean("Emails", $importedEmailId);

        foreach ($request as $requestKey => $requestValue) {
            if (strpos($requestKey, 'SET_AFTER_IMPORT_') !== false) {
                $field = str_replace('SET_AFTER_IMPORT_', '', $requestKey);
                if (in_array($field, self::$doNotImportFields)) {
                    continue;
                }

                $emails->{$field} = $requestValue;
            }
        }

        $emails->save();

        return $emails;
    }

    /**
     * @param User $requestedUser
     * @param InboundEmail $requestedInboundEmail
     * @param Email $requestedEmail
     * @return bool false if user doesn't have access
     */
    protected function userIsAllowedToSendEmail($requestedUser, $requestedInboundEmail, $requestedEmail)
    {
        global $sugar_config;

        // Check that user is allowed to use inbound email account
        $hasAccessToInboundEmailAccount = false;
        $usersInboundEmailAccounts = $requestedInboundEmail->retrieveAllByGroupIdWithGroupAccounts($requestedUser->id);
        foreach ($usersInboundEmailAccounts as $inboundEmailId => $userInboundEmail) {
            if ($userInboundEmail->id === $requestedInboundEmail->id) {
                $hasAccessToInboundEmailAccount = true;
                break;
            }
        }

        $inboundEmailStoredOptions = $requestedInboundEmail->getStoredOptions();

        // if group email account, check that user is allowed to use group email account
        if ($requestedInboundEmail->isGroupEmailAccount()) {
            if ($inboundEmailStoredOptions['allow_outbound_group_usage'] === true) {
                $hasAccessToInboundEmailAccount = true;
            } else {
                $hasAccessToInboundEmailAccount = false;
            }
        }

        // Check that the from address is the same as the inbound email account
        $isFromAddressTheSame = false;
        if ($inboundEmailStoredOptions['from_addr'] === $requestedEmail->from_addr) {
            $isFromAddressTheSame = true;
        }

        // Check if user is using the system account, as the email address for the system account, will have different
        // settings. If there is not an outbound email id in the stored options then we should try
        // and use the system account, provided that the user is allowed to use to the system account.
        $outboundEmailAccount = new OutboundEmail();
        if (empty($inboundEmailStoredOptions['outbound_email'])) {
            $outboundEmailAccount->getSystemMailerSettings();
        } else {
            $outboundEmailAccount->retrieve($inboundEmailStoredOptions['outbound_email']);
        }

        $isAllowedToUseOutboundEmail = false;
        if ($outboundEmailAccount->type === 'system') {
            if ($outboundEmailAccount->isAllowUserAccessToSystemDefaultOutbound()) {
                $isAllowedToUseOutboundEmail = true;
            }

            // When there are not any authentication details for the system account, allow the user to use the system
            // email account.
            if ($outboundEmailAccount->mail_smtpauth_req == 0) {
                $isAllowedToUseOutboundEmail = true;
            }

            // When the user is allowed to send email as themselves using the system account, allow them to use the system account
            if (isset($sugar_config['email_allow_send_as_user']) && ($sugar_config['email_allow_send_as_user'])) {
                $isAllowedToUseOutboundEmail = true;
            }

            $admin = new Administration();
            $admin->retrieveSettings();
            $adminNotifyFromAddress = $admin->settings['notify_fromaddress'];
            if ($adminNotifyFromAddress === $requestedEmail->from_addr) {
                $isFromAddressTheSame = true;
            }
        } else {
            if ($outboundEmailAccount->type === 'user') {
                $isAllowedToUseOutboundEmail = true;
            }
        }

        // The inbound email account is an empty object, we assume the user has access
        if (empty($requestedInboundEmail->id)) {
            $hasAccessToInboundEmailAccount = true;
            $isFromAddressTheSame = true;
        }

        $error = false;
        if ($hasAccessToInboundEmailAccount !== true) {
            $error = 'Email Error: Not authorized to use Inbound Account "' . $requestedInboundEmail->name . '"';
        }
        if ($isFromAddressTheSame !== true) {
            $error = 'Email Error: Requested From address mismatch "'
                . $requestedInboundEmail->name . '" / "' . $requestedEmail->from_addr . '"';
        }
        if ($isAllowedToUseOutboundEmail !== true) {
            $error = 'Email Error: Not authorized to use Outbound Account "' . $outboundEmailAccount->name . '"';
        }
        if ($error !== false) {
            $GLOBALS['log']->security($error);
            return false;
        }
        return true;
    }

    /**
     * VUT - render Quote Input for Email Solar Pricing
     * @param JSON $quotes_input realpath(dirname(__FILE__) . '/../../').'/custom/include
     * @param STRING $text email_description_html
     * @return STRING $text
     */ 
    protected function renderTableQuoteInputSolarPricing($quotes_input, $text) {
        include realpath(dirname(__FILE__) . '/../../').'/custom/modules/AOS_Quotes/vardef_list_quote_solar.php';
        $text = preg_replace('/(?si)<div id="sugar_text_change_table_solar_input"+?>(.*)<=?\/div>/U', '', html_entity_decode($text));
        // $data = json_decode(html_entity_decode($quotes_input));
        $html = '<div id="change_table_solar_input"><table  style="text-align:left;border-collapse:collapse;width:735px;"><tbody>';
        $html .='<tr><th style="text-align: left; background: #f48c21; color: #ffffff; padding: 10px 5px; border-right: 1px solid #f48c21;" colspan="2">Pricing assumptions:</th></tr>';
        //field missing when preg_replace
        $begin_pos = '  <tr>
                            <td style="padding: 5px; border: 0.5px solid #8a8a8a; width: 379px; height: 13px;">Installation Address:</td> 
                            <td style="padding: 5px; border: 0.5px solid #8a8a8a; width: 334px; height: 13px;">$aos_quotes_installation address_c</td>
                        </tr>';
        $miss_fields = array(
            'Gutter Height:' => '$aos_quotes_gutter_height_c',
            'Select your preferred/inverter combination!' => '$aos_quotes_preferred_c',
            'Notes' => '$aos_quotes_special_notes_c',
        );    
        $end_pos = '';
        foreach ($miss_fields as $k => $v) {
            $end_pos .= '<tr>';
            $end_pos .= '<td style="padding: 5px; border: 0.5px solid #8a8a8a; width: 379px; height: 13px;">'.$k.'</td>';
            $end_pos .= '<td style="padding: 5px; border: 0.5px solid #8a8a8a; width: 334px; height: 13px;">'.$v.'</td>';                
            $end_pos .= '</tr>';            
        }                   
        //field missing when preg_replace
        $solar_input ='';
        foreach ($quotes_input as $key => $value) {
            if ($vardefs_array[$key] === null) continue;
            $solar_input .= '<tr>';
            $solar_input .= '<td style="padding: 5px; border: 0.5px solid #8a8a8a; width: 379px; height: 13px;">'.$vardefs_array[$key]['display_label'].'</td>';
            $solar_input .= '<td style="padding: 5px; border: 0.5px solid #8a8a8a; width: 334px; height: 13px;">'.$value.'</td>';                
            $solar_input .= '</tr>';            
        }
        //concat string
        $html .= $begin_pos;
        $html .= $solar_input;
        $html .= $end_pos;
        $html .='</tbody></table></div>';
        $text = str_replace("\$table_solar_quote_inputs", $html , $text);

        return htmlspecialchars($text);
    }

    protected function resizeImageNote($email_id) {
        $db = DBManagerFactory::getInstance();
        $q = "SELECT id FROM notes WHERE deleted = 0 AND parent_id = '" . $email_id . "'";
        $r = $db->query($q);
        $totalSize = 0;
        $limitSize = 25165824; //24MB
        while ($a = $db->fetchByAssoc($r)) {
            $note = new Note();
            $note->retrieve($a['id']);
            if ($note->id) {
                $array_extension = explode('.', $note->filename);
                $extension = end($array_extension);
                $image = $_SERVER["DOCUMENT_ROOT"].'/upload/'. $note->id;
                if (in_array(strtolower($extension), [ 'jpg', 'jpeg', 'gif', 'png'])) {
                    try {
                        $sizeFile = 0;
                        $oneMB = 1048576;
                        $im = new Imagick($image);
                        if ($im->getImageLength() > $oneMB) {
                            $new_image = "{$image}.{$extension}";
                            $im->writeImage($new_image);
                            $im->readImage($new_image);
                            // $origin_properties = $im->getImageProperties();
                            $sizeFile = $im->getImageLength();
                            $maxWidth = 2048; //2k 2048*1080
                            $maxHeight = 1080;
                            $i = 0;
                            while ($sizeFile > $oneMB && $i < 5) {
                                $size = $im->getImageGeometry();
                                $im->setImageCompression(\Imagick::COMPRESSION_JPEG);
                                $im->setImageCompressionQuality(90);
                                if($size['width'] >= $size['height']){
                                    if($size['width'] > $maxWidth){
                                        $im->resizeImage($maxWidth, 0, \Imagick::FILTER_LANCZOS, 1);
                                        $maxWidth *= 0.9; 
                                        $maxHeight *= 0.9;
                                    } 
                                } else{
                                    if($size['height'] > $maxHeight){
                                        $im->resizeImage(0, $maxHeight, \Imagick::FILTER_LANCZOS, 1);
                                        $maxWidth *= 0.9; 
                                        $maxHeight *= 0.9;
                                    }
                                }
                                $im->writeImage($new_image);
                                $im->readImage($new_image);
                                $sizeFile = $im->getImageLength();
                                $i++;
                            }
                            if (is_link($image)) {
                                unlink($image);
                            }
                            $im->writeImage($image);
                            unlink($new_image);
                        }
                        $totalSize += filesize($image);
                    } catch (Exception $e) {
                        //throw new Exception('Exception:' . $e->getMessage());
                    }
                } else { //other files
                    $totalSize += filesize($image);
                }
            } 
        }
    
        if ($totalSize > $limitSize) {
            //throw new Exception('Error: totalSize > 24MB');
        }
    }

}


