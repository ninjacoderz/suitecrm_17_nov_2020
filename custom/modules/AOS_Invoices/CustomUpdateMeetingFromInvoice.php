<?php
$meeting_id = trim($_REQUEST['meeting_id']);
$install_date = trim($_REQUEST['installation_date_c']);
$meeting_plumber_id = $_REQUEST['meeting_plumber'];
$meeting_electrician_id = $_REQUEST['meeting_electrician'];
$messages = [
    'meeting_id_error' => '',
    'meeting_plumber_id_error' => '',
    'meeting_electrician_id_error' => '',
];
if ($meeting_id != '') {
    $meetings = new Meeting();
    $meetings->retrieve($meeting_id);
    if(!$meetings->id) {
        $messages['meeting_id_error'] = 'No meeting_id!';
    } else {
        if($install_date != '') {
            $meetings->date_start = $install_date;
        }
        $meetings->save();
    }
}
if ($install_date != '') {
    $date = explode(" ",$install_date)[0];
    if ($meeting_plumber_id != '') {
        $meeting_plumber = new Meeting();
        $meeting_plumber->retrieve($meeting_plumber_id);
        if (!$meeting_plumber->id) {
            $messages['meeting_plumber_id_error'] = 'No meeting_plumber_id!';
        } else {
            $date_plumber = DateTime::createFromFormat('d/m/Y H:i:s',$date.' 08:00:00', new DateTimeZone("Australia/Melbourne"));
            $meeting_plumber->date_start = $date_plumber->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
            $meeting_plumber->save();
        }
    }

    if ($meeting_electrician_id != '') {
        $meeting_electrician = new Meeting();
        $meeting_electrician->retrieve($meeting_electrician_id);
        if (!$meeting_electrician->id) {
            $messages['meeting_electrician_id_error'] = 'No meeting_electrician_id!';
        } else {
            $date_electrician = DateTime::createFromFormat('d/m/Y H:i:s',$date.' 12:00:00', new DateTimeZone("Australia/Melbourne"));
            $meeting_electrician->date_start = $date_electrician->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
            $meeting_electrician->save();
        }
    }
}

echo json_encode($messages);
