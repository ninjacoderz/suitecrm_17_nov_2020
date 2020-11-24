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

require_once('modules/Emails/Email.php');
require_once('modules/Contacts/Contact.php');

/**
 * Class sendEmail
 * TODO: Move to emails module. This class violates single responsibility principle. In that the emails
 * module should handle the email
 */
class sendEmail
{
    /**
     * @param SugarBean $module
     * @param string $module_type
     * @param string $printable
     * @param string $file_name
     * @param bool $attach
     * @see generatePDF (Entrypoint)
     * @deprecated use EmailController::composeViewFrom
     */
    
    public function send_email($module, $module_type, $printable, $file_name, $attach, $sms_content="", $sms_received="",$smsTemplateID="")
    {
        global $current_user, $mod_strings, $sugar_config;
        // First Create e-mail draft
        $email = BeanFactory::newBean('Emails');
        // set the id for relationships
        $email->id = create_guid();
        $email->new_with_id = true;

        // subject thien fix
        //$email->name = $mod_strings['LBL_EMAIL_NAME'] . ' ' . $module->name;

        if($_REQUEST['module'] == "PO_purchase_order"){
            $email->name = $mod_strings['LBL_EMAIL_NAME']." PO #".$module->number. ' for ' . $module->name;
        }else if($_REQUEST['module'] == 'AOS_Invoices' || $_REQUEST['module'] == 'AOS_Quotes'){
            $email->name = preg_replace('/(?: for)/', ' #'.$module->number.' for', $mod_strings['LBL_EMAIL_NAME']).' '. $module->name;
            $printable =  preg_replace('/<div id="sugar_text_div_term_and_conditions">(.*)<\/div>/m','', $printable);
        }else{
            $email->name = $mod_strings['LBL_EMAIL_NAME'] .' '. $module->name;
        }

        // set sms content that relate with email
        $email->sms_message = str_replace("\n\n","\n",str_replace("<br />","\n",$sms_content));
        $email->number_client = $sms_received;
        // custom sms signture for email Invoice
        if($_REQUEST['module'] == "AOS_Invoices") {
            $path_file_json_sms_signture = dirname(__FILE__) .'/../../custom/modules/Users/json_sms_signture.json';
            $json_data = json_decode(file_get_contents($path_file_json_sms_signture),true);
            if(isset($json_data)) {
                if(isset($json_data['1588651225'])) {
                    $email->sms_message .= $json_data['1588651225']['content'];
                }else{
                    $email->sms_message .= $current_user->sms_signature_c;
                }
            }
        }

        // body
        $email_edit_link = "";
        if($_REQUEST['auto_send']){
            $email_edit_link = "<a href='https://suitecrm.pure-electric.com.au/index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=".$module->module_dir."&return_action=DetailView&return_id=".$module->id."&return_action=DetailView&record=".$email->id."&sms_template_id=".$smsTemplateID."'>Edit Email Link</a><br/>";
            $email->description_html = $email_edit_link.$printable;
        } else
            $email->description_html = $printable;
        // type is draft
        $email->type = "draft";
        $email->status = "draft";

        if (!empty($module->billing_contact_id)) {
            $contact_id = $module->billing_contact_id;
        } else {
            if (!empty($module->contact_id)) {
                $contact_id = $module->contact_id;
            }
        }

        // BinhNT Code here
        if($module->module_dir == "PO_purchase_order"){
            $account_id = $module->billing_account_id;
            $account = new Account;
            if ($account->retrieve($account_id)) {
                $email->parent_type = 'Accounts';
                $email->parent_id = $account->id;
                //$email->from_addr = 'PureElectric Accounts <accounts@pure-electric.com.au>';
                if (!empty($account->email1)) {
                    //$email->to_addrs_emails = $account->email1 . ";";
                    //$email->to_addrs = $account->name . " <" . $account->email1 . ">";
                    $email->to_addrs_names = $account->name . " <" . $account->email1 . ">";
                    $email->parent_name = $account->name;
                    //$email->module_name = $account->name;
                }
            }
        }

        // TODO: FIX UID / Inbound Email Account
        $inboundEmailID = $current_user->getPreference('defaultIEAccount', 'Emails');
        $email->mailbox_id = $inboundEmailID;

        $contact = new Contact;
        if ($contact->retrieve($contact_id)) {
            $email->parent_type = 'Contacts';
            $email->parent_id = $contact->id;

            if (!empty($contact->email1)) {
                $email->to_addrs_emails = $contact->email1 . ";";
                //$email->to_addrs = $contact->name . " <" . $contact->email1 . ">";
                $email->to_addrs_names = $contact->name . " <" . $contact->email1 . ">";
                $email->parent_name = $contact->name;
            }
        }


        // team id
        $email->team_id = $current_user->default_team;
        // assigned_user_id
        $email->assigned_user_id = $current_user->id;
        // Save the email object
        global $timedate;
        $email->date_start = $timedate->to_display_date_time(gmdate($GLOBALS['timedate']->get_db_date_time_format()));
        $email->save(false);
        $email_id = $email->id;

        if ($attach) {
            $note = BeanFactory::newBean('Notes');
            $note->modified_user_id = $current_user->id;
            $note->created_by = $current_user->id;
            $note->name = $file_name;
            $note->parent_type = 'Emails';
            $note->parent_id = $email_id;
            $note->file_mime_type = 'application/pdf';
            $note->filename = $file_name;
            $noteId = $note->save();

            if ($noteID !== false && !empty($noteId)) {
                rename($sugar_config['upload_dir'] . 'attachfile.pdf', $sugar_config['upload_dir'] . $note->id);
                $email->attachNote($note);
            } else {
                $GLOBALS['log']->error('AOS_PDF_Templates: Unable to save note');
            }
        }

        //VUT-Attachment file for PO Plumbing Sanden Job
        if ($module->module_dir == "PO_purchase_order" && strpos(strtolower($module->name),'sanden') !== false && strpos(strtolower($module->name),'plumbing') !== false) {
            $this->attachmentFileForPOSandenPlumping($email);
        }
        //VUT-Attachment file for PO Plumbing Sanden Job

        if($module_type == "AOS_Invoices"){
            $invoice_id = $_REQUEST['uid'];
            $invoice = new AOS_Invoices();
            $invoice = $invoice->retrieve($invoice_id);
            if($invoice->id !== ""){
                $invoice_file_attachmens = scandir(realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $invoice->installation_pictures_c ."/");
                $noteArray = array();

                if (count($invoice_file_attachmens)>0) foreach ($invoice_file_attachmens as $att){
                    // Create Note
                    //if(strpos($att, "Bill") !== false) continue;
                    if((strpos(strtolower($att), "ces") !== false && strpos(strtolower($att), "pdf") !== false) || (strpos(strtolower($att), "pcoc") !== false && strpos(strtolower($att), "pdf") !== false)){
                        $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $invoice->installation_pictures_c ."/" . $att ;
                        if(!is_file($source)) continue;
                        
                        $noteTemplate = new Note();
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $email_id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteTemplate->date_entered = '';
                        $noteTemplate->file_mime_type = 'application/pdf';
                        $noteTemplate->filename = $att;
                        $noteTemplate->name = $att;
                        
                        $noteTemplate->save();

                        $destination = realpath(dirname(__FILE__) . '/../../').'/upload/'.$noteTemplate->id;
                        //$source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $lead_bean->installation_pictures_c ."/" . $att ;
                        copy( $source, $destination);
                        //$noteArray[] = $noteTemplate;
                        $email->attachNote($noteTemplate);
                        if($_REQUEST['auto_send']){
                            if($noteTemplate) $email->saved_attachments[] = $noteTemplate;
                        }
                    } elseif((strpos(strtolower($att), "ces") !== false  
                                && (strpos(strtolower($att), "png") !== false 
                                    || strpos(strtolower($att), "jpg") !== false
                                    || strpos(strtolower($att), "jpeg") !== false
                                    ) 
                                )
                            
                                || (strpos(strtolower($att), "pcoc") !== false
                                    && (strpos(strtolower($att), "png") !== false 
                                    || strpos(strtolower($att), "jpg") !== false
                                    || strpos(strtolower($att), "jpeg") !== false
                                    ) 
                                )
                                
                        ){
                        $source =  realpath(dirname(__FILE__) . '/../../').'/custom/include/SugarFields/Fields/Multiupload/server/php/files/'. $invoice->installation_pictures_c ."/" . $att ;
                        if(!is_file($source)) continue;
                        
                        $noteTemplate = new Note();
                        $noteTemplate->id = create_guid();
                        $noteTemplate->new_with_id = true; // duplicating the note with files
                        $noteTemplate->parent_id = $email_id;
                        $noteTemplate->parent_type = 'Emails';
                        $noteTemplate->date_entered = '';

                        if(strpos(strtolower($att), "png") !== false) {
                            $noteTemplate->file_mime_type = 'image/png';
                        } else {
                            $noteTemplate->file_mime_type = 'image/jpg';
                        }

                        $noteTemplate->filename = $att;
                        $noteTemplate->name = $att;
                        
                        $noteTemplate->save();

                        $destination = realpath(dirname(__FILE__) . '/../../').'/upload/'.$noteTemplate->id;
                        copy( $source, $destination);
                        $email->attachNote($noteTemplate);
                        if($_REQUEST['auto_send']){
                            if($noteTemplate) $email->saved_attachments[] = $noteTemplate;
                        }
                    }
                }
                //$email->saved_attachments = array_merge($email->saved_attachments, $noteArray);
            }
        }

        
        if($_REQUEST['auto_send']){
            $email->to_addrs_arr = array( array("email" => "info@pure-electric.com.au"), array("email" => "binhdigipro@gmail.com"));
            if($note) $email->saved_attachments[] = $note;
            $email->from_addr = "accounts@pure-electric.com.au";
            $email->from_name = "PureElectric Accounts";
            $email->send();
            
            $email->to_addrs_arr = NULL;
            if(isset ($contact->email1 ) && $contact->email1 != ""){
                $email->to_addrs_names = $contact->name . " <" . $contact->email1 . ">";
            }
            $email->from_addr_name = "PureElectric Accounts &lt;accounts@pure-electric.com.au&gt;";
            // personal accounts
            // Hard code for mail inbox 
            //if(($module->module_dir == "PO_purchase_order" || $module->module_dir == "AOS_Invoices") && $current_user->id == "61e04d4b-86ef-00f2-c669-579eb1bb58fa"){
                //$email->mailbox_id = "b4fc56e6-6985-f126-af5f-5aa8c594e7fd";// Account email;
            //}
            
            $email->description_html = preg_replace("/<a(.*)>Edit Email Link<\/a>/", "", $email->description_html);
            $email->save();
            return;
        }

        // redirect
        if (empty($email_id)) {
            echo "Unable to initiate Email Client";
            exit;
        } else {
            //VUT - Add pdf_template_ID
            $templateID = $_REQUEST['templateID'];
            header('Location: index.php?action=ComposeViewWithPdfTemplate&module=Emails&return_module=' . $module_type . '&return_action=DetailView&return_id=' . $module->id . '&record=' . $email_id."&template_id=".$templateID."&sms_template_id=".$smsTemplateID);
        }
    }

    /**
     * VUT- Attachment file from Document to PO Sanden Plumping
     * @param Email $email
     */
    protected function attachmentFileForPOSandenPlumping($email) {
        global $sugar_config;
        global $current_user;
        // $idDocument='3601fe65-1e3d-724a-189c-5f7a87624d4f'; 
        // $idDocument='ee488072-583f-719b-5e27-5f7aeb91662d'; //devel
        $idDocument='e77b2aeb-0d07-13e1-283a-5f7a8084d755'; //server
        $db_document = DBManagerFactory::getInstance();
        $sql_docs = "SELECT document_revisions.filename as filename, document_revisions.id as id_file 
                    FROM document_revisions INNER JOIN documents ON documents.id = document_revisions.document_id 
                    WHERE documents.id = '$idDocument'";
        $ret =$db_document->query($sql_docs);
        while ($row = $ret->fetch_assoc()) {
            $filename = $row['filename'];
            $file_id = $row['id_file'];
            $local_location = "upload://{$file_id}";
            $mime_type = mime_content_type($local_location);
            $note = new Note();
            $note->modified_user_id = $current_user->id;
            $note->created_by = $current_user->id;
            $note->name = $filename;
            $note->parent_type = 'Emails';
            $note->parent_id = $email->id;
            $note->file_mime_type = $mime_type;
            $note->filename = $filename; 
            $noteId = $note->save();
            if($noteId !== false && !empty($noteId)) {
                copy($sugar_config['upload_dir'] . $file_id, $sugar_config['upload_dir'] . $note->id);
                $email->attachNote($note);
            } else {
                $GLOBALS['log']->error('AOS_PDF_Templates: Unable to save note');
            }
        }
    }


}

if(!function_exists("retrieveByGroupId")){
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
}
