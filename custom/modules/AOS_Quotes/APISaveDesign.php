<?php
    // .:nhantv:. Save Design data from Solar design tool
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-type');

    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    $resData = [];

    if ($contentType === "application/json") {
        //Receive the RAW post data.
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);

        // If json_decode success, the JSON is valid.
        if(is_array($decoded)) {
            $id = $decoded['id'];
            // Case id invalid
            if(!isset($id) && $id == '') return;

            $dataDesign = $decoded['dataDesign'];
            // Case dataDesign invalid
            if(!isset($dataDesign) && $dataDesign == '') return;
            
            // Get quote
            $quote = new AOS_Quotes();
            $quote->retrieve($id);
            // Case quote not exist
            if(!$quote->id) return;

            try{
                $quote->design_tool_json_c = json_encode($dataDesign); 
                $quote->save();
                $resData['code'] = 0;
                $resData['message'] = 'Save Success!';
                echo json_encode($resData);
            } catch(Exception $e){
                $resData['code'] = -1;
                $resData['message'] = $e;
                echo json_encode($resData);
            }
        } else {
            // Send error back to user.
            $resData['code'] = -1;
            $resData['message'] = "Error: Can not parse data into JSON";
            echo json_encode($resData);
        }
    }

    
    