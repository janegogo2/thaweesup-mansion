<?php
require_once '../connection.inc.php';
$error = array();
$error['message'] = '';

$result = array();
$result['uploaded'] = 0;
$result['fileName'] = '';
$result['url'] = '';
$result['error'] = $error;

$file_name = generateFileName($_FILES['upload']);
$path = PATH_DOWNLOAD;
$url = "../" . $path . '/' . $file_name;

//extensive suitability check before doing anything with the file...
if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name']))) {
    $message = "No file uploaded.";
} else if ($_FILES['upload']["size"] == 0) {
    $message = "The file is of zero length.";
} else if (($_FILES['upload']["type"] != "image/pjpeg") AND ($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/png") AND ($_FILES['upload']["type"] != "image/gif")) {
    $message = "The image must be in either GIF , JPG or PNG format. Please upload a JPG or PNG instead.";
} else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {
    $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
} else {
    $message = "";

    $move = move_uploaded_file($_FILES['upload']['tmp_name'], $url);
    if (!$move) {
        $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
    }
    $url = ROOTPATH . '/' . $path . '/' . $file_name;
}


if ($message != "") {
    $url = "";
    $result['uploaded'] = 0;
    $error['message'] = $message;
} else {
    $result['uploaded'] = 1;
    $result['url'] = $url;
    $result['fileName'] = $file_name;
}
echo json_encode($result);
