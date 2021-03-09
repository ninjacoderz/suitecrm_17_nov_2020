<?php
global $timedate, $current_user;
$record_id =$_REQUEST['record_id'];
$bean =  new Lead();
$bean->retrieve($record_id); 
$user_timezone = TimeDate::userTimezone($current_user);
if($bean->id == '') {
    echo date("d-m-Y");
}else{
    // $date_last =$bean->date_entered ;
    // $timestamp = substr($date_last, 0, 10);
    // $date = DateTime::createFromFormat('d/m/Y',$timestamp);
    // echo $date->format('Y-m-d');

    //VUT-S-Calculate date_create to change status converted date
    /**Lead - date_entered */
    $date_start = $bean->date_entered;
    $date = DateTime::createFromFormat('d/m/Y H:i',$date_start, new DateTimeZone($user_timezone));
    $gr_status = ['Converted','Dead','Spam','Test'];
    $calculator = false;
    if (in_array($bean->status,$gr_status)) { 
        $calculator = true;
    } elseif (strpos($bean->status,'Lost') !== false) {
        $calculator = true;
    }
    if (!$calculator) {
        $check = strtolower($bean->age_days_c);
        if (!(strpos($check,'days') !== false && strpos($check,'hours') !== false && strpos($check, 'minutes') !== false)) {
            // $date_converted = DateTime::createFromFormat('U', time(), new DateTimeZone($user_timezone));
            $date_converted = DateTime::createFromFormat('d/m/Y H:i', $timedate->now(), new DateTimeZone($user_timezone));
            $date_diff = date_diff($date, $date_converted, false)->format('%a Days %h Hours %i Minutes');
            echo $date_diff;
        }
    } else {
        $check = strtolower($bean->age_days_c);
        if ($check == '' || !(strpos($check,'days') !== false && strpos($check,'hours') !== false && strpos($check, 'minutes') !== false)) {
            if (strpos($bean->status,'Lost') !== false) {
                $note_id = getInternalNoteForLead($record_id, 'Lost');
            } else {
                $note_id = getInternalNoteForLead($record_id, $bean->status);
            }
            if ($note_id != '') {
                $note = new pe_internal_note();
                $note->retrieve($note_id);
                $date_last = $note->date_entered;
                $date_converted = DateTime::createFromFormat('d/m/Y H:i', $date_last, new DateTimeZone($user_timezone));
                $date_diff = date_diff($date, $date_converted, false)->format('%a Days %h Hours %i Minutes');
                $bean->age_days_c = $date_diff;
                $bean->save();
                echo $date_diff;
            } else {
                $note_id_new = getInternalNoteForLead($record_id, 'new');
                if ($note_id_new !='') {
                    $note = new pe_internal_note();
                    $note->retrieve($note_id_new);
                    $date_last = $note->date_entered;
                    $date_converted = DateTime::createFromFormat('d/m/Y H:i', $date_last, new DateTimeZone($user_timezone));
                    $date_diff = date_diff($date, $date_converted, false)->format('%a Days %h Hours %i Minutes');
                    // $bean->age_days_c = $date_diff;
                    // $bean->save();
                    echo $date_diff;
                } else { //Converted but save note is new
                    echo 'Internal note haven\'t status NEW'; 
                }
            } 
        } else {
            echo '';
        }

    }
    //VUT-S-Calculate date_create to change status converted date
}

/**
 * VUT-Get Internal note relate Lead have status nearly 
 * @param string $record_id  Lead's id
 * @param string $status  Lead's status
 * @return string pe_internal_note's id 
*/
function getInternalNoteForLead ($record_id, $status) {
    $db = DBManagerFactory::getInstance();
    $query = "SELECT pe_internal_note.id as note_id, pe_internal_note.date_entered as note_date, pe_internal_note.description as note_description
              FROM pe_internal_note 
              LEFT JOIN leads_pe_internal_note_1_c ON leads_pe_internal_note_1_c.leads_pe_internal_note_1pe_internal_note_idb = pe_internal_note.id
              LEFT JOIN leads ON leads.id = leads_pe_internal_note_1_c.leads_pe_internal_note_1leads_ida
              WHERE pe_internal_note.deleted = 0 AND leads.id = '$record_id' 
              ORDER BY pe_internal_note.date_entered DESC
              ";
    $ret = $db->query($query);
    
    while($row = $ret->fetch_assoc()){
      $check = strtolower($row['note_description']);
      if (strpos($check, 'lead status') !== false && strpos($check, strtolower($status)) !== false ) {
        return $row['note_id'];
        die();
      }
    }
    return '';
}

