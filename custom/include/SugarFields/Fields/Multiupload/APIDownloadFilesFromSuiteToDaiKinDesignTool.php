<?php

$path                   = $_SERVER["DOCUMENT_ROOT"] . '/custom/include/SugarFields/Fields/Multiupload/server/php/files/';

// $pre_install_photos_c   = 'cedf86b0-f013-478b-bcdd-23517df82b02';
$pre_install_photos_c   = $_POST['pre_install_photos_c'];
$quote_id               = $_POST['quote_id'];

$folderPath = $path . $pre_install_photos_c.'/';

$scannedFiles = scandir($folderPath);

unset($scannedFiles[array_search('.', $scannedFiles, true)]);
unset($scannedFiles[array_search('..', $scannedFiles, true)]);

$files = [];

foreach ($scannedFiles as $file) {
    if (is_file($folderPath.$file)) {
        $files[] = $file;
    }
}

echo json_encode($files);




