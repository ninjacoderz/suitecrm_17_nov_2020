<?php
if(isset($_POST['submit']) && isset($_FILES)) {
    require __DIR__ . '/vendor/autoload.php';
    $target_dir = "";
    $uploadOk = 1;
    $FileType = strtolower(pathinfo($_FILES["attachment"]["name"],PATHINFO_EXTENSION));
    $target_file = $target_dir . generateRandomString() .'.'.$FileType;
    // Check file size
    if ($_FILES["attachment"]["size"] > 5000000) {
        header('HTTP/1.0 403 Forbidden');
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    if($FileType != "pdf" && $FileType != "png" && $FileType != "jpg") {
        header('HTTP/1.0 403 Forbidden');
        echo "Sorry, please upload a pdf file";
        $uploadOk = 0;
    }
    if ($uploadOk == 1) {
   
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
            uploadToApi($target_file);
        } else {
            header('HTTP/1.0 403 Forbidden');
            echo "Sorry, there was an error uploading your file.";
        }
    } 
} else {
    header('HTTP/1.0 403 Forbidden');
    echo "Sorry, please upload a pdf file";
}

function uploadToApi($target_file){
    require __DIR__ . '/vendor/autoload.php';
    $fileData = fopen($target_file, 'r');
    $client = new \GuzzleHttp\Client();
    try {
    $r = $client->request('POST', 'https://api.ocr.space/parse/image',[
        'headers' => ['apiKey' => 'helloworld'],
        'multipart' => [
            [
                'name' => 'file',
                'contents' => $fileData
            ]
        ]
    ], ['file' => $fileData]);
    $response =  json_decode($r->getBody(),true);
    if($response['ErrorMessage'] == "") {
?>
<html>
    <head>
    <title>Result</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/css/bootstrap.min.css">
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/js/bootstrap.min.js'></script>
    </head>
    <body>
        <div class="form-group container">
            <label for="exampleTextarea">Result</label>
            <textarea class="form-control" id="exampleTextarea" rows="30">
            <?php
                foreach($response['ParsedResults'] as $pareValue) {
                    echo $pareValue['ParsedText'];
                }
            ?></textarea>
        </div>
    </body>
</html>
<?php
    } else {
        header('HTTP/1.0 400 Forbidden');
        echo $response['ErrorMessage'];
    }
    } catch(Exception $err) {
        header('HTTP/1.0 403 Forbidden');
        echo $err->getMessage();
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>