<?php


if (isset($_GET['record']) && $_GET['module'] == 'Emails')
{
    $recordId = $_GET['record'];
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT description_html FROM emails_text WHERE email_id = '$recordId'";

    $ret = $db->query($sql);
    if ($row = $db->fetchByAssoc($ret))
    {
        $desc = from_html($row['description_html']);
        $result = $desc;
        $pos = strpos($desc, '<div class="email-signature">');
        if ($pos !== false)
        {
            $result =  substr($desc, 0, $pos);
        }
        else
        {
            $pos = strpos($desc, '<div class="gmail_signature">');
            if ($pos !== false)
            {
                $result =  substr($desc, 0, $pos);
            }
        }

        $attachments = '';
        $sql = "SELECT id,filename FROM notes WHERE parent_id = '$recordId'";
        $ret = $db->query($sql);
        while ($row = $db->fetchByAssoc($ret)) {
            $url = "https://suitecrm.pure-electric.com.au/index.php?entryPoint=download&id=" . $row['id'] . "&type=Notes";
            $attachments = $attachments . '<br><a href="' . $url . '">' . $row['filename']. '</a>';
        }

        if ($attachments != '') {
            $result = $result . '<br><br><div>Attachments:' . $attachments . '</div>';
        }
        
        echo $result;
    }
}

if (isset($_GET['record']) && $_GET['module'] == 'pe_smsmanager') {
    $recordId = $_GET['record'];
    $db = DBManagerFactory::getInstance();
    $sql = "SELECT name,description FROM pe_smsmanager WHERE id = '$recordId'";
    $ret = $db->query($sql);
    if ($row = $db->fetchByAssoc($ret)){
        echo '<h3>Subject : ' .$row['name'] .'</h3>';
        echo $row['description'];
    }
}