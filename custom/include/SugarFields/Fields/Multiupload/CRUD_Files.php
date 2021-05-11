<?php
$id =  $_REQUEST['id'];
$action = $_REQUEST['action'];
$module = $_REQUEST['module'];
$id_email = $_REQUEST['id_email'];
switch ($module) {
    case 'AOS_Invoices':
        $invoice = new AOS_Invoices();
        $invoice->retrieve($id);
        if($invoice->id == '') {echo 'Not Have Files'; die();}
        $folder_install = $invoice->installation_pictures_c;
        break;
    case 'AOS_Quotes':
        $quote = new AOS_Quotes();
        $quote->retrieve($id);
        if($quote->id == '') {echo 'Not Have Files'; die();}
        $folder_install = $quote->pre_install_photos_c;
        break;
    case 'PO_purchase_order':
        $po = new PO_purchase_order();
        $po->retrieve($id);
        // if ($po->id == '' || $po->installation_pdf_c == '') {
        //     echo 'Not Have Files'; die();
        // } 
        $invoice_id = $po->aos_invoices_po_purchase_order_1aos_invoices_ida;
        if( $invoice_id != "" ){
            $invoice = new AOS_Invoices();
            $invoice->retrieve($invoice_id);
            $folder_install = $invoice->installation_pictures_c;
        }else {
            $folder_install = $po->installation_pdf_c;
        }    
        break;
    default:
        echo 'Not Have Files';
        die();
        break;
}

$forder  = dirname(__FILE__)."/server/php/files/". $folder_install."/";
$result = array();
$link_folder_file = '/custom/include/SugarFields/Fields/Multiupload/server/php/files/' . $folder_install .'/';
switch ($action) {
    case 'read':
        $nameFileNote = [];
        $EmailBean = new Email();
        $EmailBean->retrieve($id_email);
        if($EmailBean->id == '')  die('Not Have Files');
        $q = "SELECT id FROM notes WHERE deleted = 0 AND parent_id = '" . $id_email . "'";
        $r = $EmailBean->db->query($q);
        while ($a = $EmailBean->db->fetchByAssoc($r)) {
                $note = new Note();
                $note->retrieve($a['id']);
                $result[] = array(
                    'link_image' => '/upload/'. $note->id,
                    'link_thub' => '/upload/'. $note->id,
                    'file_name' => $note->filename,
                    'id_folder' => '',
                    'note_id' => $note->id,
                    'attach' => 1
                );
                array_push($nameFileNote, $note->filename);
        }
        
        $data = scan_dir_folder($forder);
        foreach ($data as  $value) {
            if (!in_array($value,$nameFileNote)) {
                $result[] = array(
                    'link_image' => $link_folder_file .'/'.$value,
                    'link_thub' => $link_folder_file .'thumbnail/'.$value,
                    'file_name' => $value,
                    'id_folder' => $folder_install,
                    'note_id' => '',
                    'attach' => 0
                );
            }
        }

        break;
    case 'addNotes':
        $id_email = $_REQUEST['id_email'];
        $jsonString = $_REQUEST['jsonString'];
        $jsonDecode = json_decode(urldecode($jsonString), true);
        $EmailBean = new Email();
        $EmailBean->retrieve($id_email);
        if($EmailBean->id == '')  die('Not Have Files');
        foreach ($jsonDecode as $key => $value) {
            if( $value[1] != '')
            {
                $file_name = $value[1];
                $id_folder = $value[0];
                $noteTemplate = new Note();
                $noteTemplate->id = create_guid();
                $noteTemplate->new_with_id = true; // duplicating the note with files
                $noteTemplate->parent_id = $EmailBean->id;
                $noteTemplate->parent_type = 'Emails';
                $noteTemplate->date_entered = '';
                $noteTemplate->file_mime_type = mime_content_type($file_name);
                $noteTemplate->filename = $file_name;
                $noteTemplate->name = $file_name;
                $noteTemplate->save();
                $destination =  realpath(dirname(__FILE__) . '/../../../../../').'/upload/'.$noteTemplate->id;
                $source =  $forder ."/" . $file_name ;
                if (!symlink( $source , $destination)) {
                    $GLOBALS['log']->error("upload_file could not copy [ { $source } ] to [ {$destination} ]");
                }
                $EmailBean->attachNote($noteTemplate);
                $result[] = array(
                    'link_image' => $link_folder_file .'/'.$file_name,
                    'link_thub' => $link_folder_file .'thumbnail/'.$file_name,
                    'file_name' => $file_name,
                    'id_folder' => $folder_install,
                    'note_id' => $noteTemplate->id,
                    'attach' => 1
                );
            }
        }
        $EmailBean->save();
        break;
    default:

        break;
}
if(count($result) == 0){echo 'Not Have Files'; die();}
echo (json_encode($result));

function scan_dir_folder ($dir){
    $files = array();    
    foreach ( scandir($dir) as $file) {
        $array_extension = explode('.', $file);
        $extension = end($array_extension);
        if ($extension != "json" && $extension != '' && $extension != '.DS_Store' && $extension != 'thumbnail') {
            $files[$file] = $file;
        }
    }
    asort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}

if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}