<?php

array_push($job_strings, 'custom_checkdesigns');

function custom_checkdesigns()
{
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT id_c, user_id_c, time_accepted_job_c FROM leads_cstm WHERE user_id_c != '' AND time_accepted_job_c != '' AND time_completed_job_c = ''";

    $ret = $db->query($sql);

    while ($row = $db->fetchByAssoc($ret))
    {
        date_default_timezone_set('Australia/Melbourne');
        $date = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $row['time_accepted_job_c'])));
        $timeAgo = time() - strtotime($date);
        $timeAgo = $timeAgo / 3600;
        if ($date < strtotime("2017-12-22 00:00") || $timeAgo < 1.5)
        {
            continue;
        }

        $record_id = $row['id_c'];
        $lead = new Lead();
        $lead = $lead->retrieve($record_id);

        $user = new User();
        $user->retrieve($lead->user_id_c);
    
        require_once('include/SugarPHPMailer.php');
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        $mail->FromName = $defaults['name'];
        $mail->IsHTML(true);

        if ($timeAgo > 4)
        {
            $lead->user_id_c = '';
            $lead->time_accepted_job_c = '';
            $lead->save();

            $mail->Subject = 'DESIGNS EMAIL - DESIGNS OUT' . $lead->first_name. ' ' . $lead->last_name . ' '
            . $lead->primary_address_street . ' ' . $lead->primary_address_city . ' '
            . $lead->primary_address_state . ' ' . $lead->primary_address_postalcode;

            $mail->Body = '<div>' . $user->name . ' already agreed to do this design at ' . $lead->time_accepted_job_c . ' (Time Melbourne) (Above 4 hours ago).</div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Edit</a></div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=DetailView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Normal</a></div>';
        }
        else if ($timeAgo > 2)
        {
            $lead->user_id_c = '';
            $lead->time_accepted_job_c = '';
            $lead->save();

            $mail->Subject = 'DESIGNS EMAIL - REPEAT' . $lead->first_name. ' ' . $lead->last_name . ' '
            . $lead->primary_address_street . ' ' . $lead->primary_address_city . ' '
            . $lead->primary_address_state . ' ' . $lead->primary_address_postalcode;

            $mail->Body = '<div>' . $user->name . ' already agreed to do this design at ' . $lead->time_accepted_job_c . ' (Time Melbourne) (Above 2 hours ago).</div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Edit</a></div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=DetailView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Normal</a></div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?entryPoint=customLeadAcceptJob&record_id=' . $record_id . '">Accept Job</a></div>';
        }
        else
        {
            $mail->Subject = 'DESIGNS EMAIL - OVERDUE' . $lead->first_name. ' ' . $lead->last_name . ' '
            . $lead->primary_address_street . ' ' . $lead->primary_address_city . ' '
            . $lead->primary_address_state . ' ' . $lead->primary_address_postalcode;

            $mail->Body = '<div>' . $user->name . ' already agreed to do this design at ' . $lead->time_accepted_job_c . ' (Time Melbourne) (Above 1.5 hours ago).</div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=EditView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Edit</a></div>
            <br><div><a target="_blank" href="https://suitecrm.pure-electric.com.au/index.php?action=DetailView&module=Leads&record=' . $record_id . '&offset=14&stamp=1511568593091292500&return_module=Home&return_action=index">CRM Link Normal</a></div>';
        }

        $mail->prepForOutbound();
        $mail->AddAddress('binhdigipro@gmail.com');
        $mail->AddAddress('admin@pure-electric.com.au');
        $mail->AddCC('info@pure-electric.com.au');

        $sent = $mail->Send();
        echo $sent;        
    }
}
