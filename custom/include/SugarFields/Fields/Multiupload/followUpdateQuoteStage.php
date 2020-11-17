<?php
    $db = DBManagerFactory::getInstance();
    $token = $_REQUEST['token'];
    $message ='' ;
    if ($token != '' && preg_match('/^[0-9A-F]{40}$/i', $token)) {

    }else{
        $message = '<span style="color:red;">The token was expired!</span>';
    }

    $query = "SELECT quote_id FROM pending_quote_token WHERE token ='$token'";
    $ret = $db->query($query);
    $row = $db->fetchByAssoc($ret);

    if($_REQUEST['follow'] == 'yes'){
        if($row['quote_id'] == '' ){
            $message = '<span style="color:red;">The link is broken!</span>';
        }else{
            $db->query("DELETE FROM pending_quote_token WHERE token ='$token'");
            $message = '<span style="color:green;">We will continue to keep your quote in our system.</div>';
        }

    }else if($_REQUEST['follow'] == 'no'){
            
            if($row['quote_id'] !=''){
            $quote = new AOS_Quotes();
            $quote = $quote->retrieve($row['quote_id']);
            $quote->stage = 'Lost_No_Longer_Interested';
            $quote->save();

            //delete
            $db->query("DELETE FROM pending_quote_token WHERE token ='$token'");
            $message = '<span style="color:green;">See you again!</div>';
        }else{
            $message = '<span style="color:red;">The link is broken!</span>';
        }
    }else{
        $message = '<span style="color:red;">The link is broken!</span>';
    }

    echo '<div style="text-align:center;text-align: center;position: relative;border: 1px gray;border-style: dashed;border-radius: 10px;padding-bottom: 20px;width: 500px;margin: 100 auto;background-color: #efefef;">
        <h1 style="font-size: 3.5rem;font-weight: 300;">Thank You!</h1>
        <p style="font-size: 1.25rem;font-weight: 300;"><strong>'.$message.'</strong></p>
      </div>';