<?php

//**แก้ไขเฉพาะส่วนนี้เท่านั้น**/
date_default_timezone_set('Asia/Bangkok');
define('DB_HOST', 'localhost');//localhost  // 127.0.0.1
define('DB_NAME', 'thaweesup-mansion'); //
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('ROOTPATH', curRootPath('thaweesup-mansion'));
define('PATH_UPLOAD', '../uploads/');
define('PATH_DOWNLOAD', 'uploads/');
define('PATH_MAIL_TEMPLATE', '/service/template/');
define('ADDRESS', '');
define('TITLE_THAI', 'ระบบจัดการหอพัก');
define('TITLE_ENG', 'ระบบจัดการหอพัก');
define('DEPOSIT_PRICE', 0); // ราคามัดจำ
define('INSURANCE_PRICE', 2); // ราคาประกัน = ค่าห้อง
//define('ELECTRICITY_UNIT_PRICE', 8); // ค่าไฟ
//define('WATER_UNIT_PRICE', 10); // ค่าน้ำ
define('PAYMENT_DATE', "05"); // วันที่กำหนดจ่าย
define('RATE_PAYMENT_PRICE', 100); // ค่าปรับ

//define('MAIL_SERVER_USERNAME', "@gmail.com");
//define('MAIL_SERVER_PASSWORD', "");
//define('MAIL_FORM', "@gmail.com");
//define('MAIL_FORM_NAME', "ระบบจัดการหอพัก");

//** Status Group **//
define('GROUP_CONTRACT', 1);
define('GROUP_STAY', 2);
define('GROUP_PAYMENT', 3);
//** Status Group End **//


//** Status **//
define('ACTIVE_CONTRACT', 1);
define('CANCEL_CONTRACT', 2);

define('NOTSTAY_STATUS', 10);
define('STAY_STATUS', 11);
define('REQUEST_CHECK_OUT_STATUS', 12);
define('MOVE_CHECK_OUT_STATUS', 13);

define('SAVE_PAYMENT', 20);
define('WAITING_PAYMENT', 21);
define('PAYMENT_SUCCESS', 22);




require_once('classes/MySQLDBConn.class.php');
require_once('classes/NumberThai.php');
require_once('service/Notification.php');
$conn = new MySQLDBConn(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$uprofile = isset($_SESSION['uprofile']) ? $_SESSION['uprofile'] : null;

if (getIsset('page') == "") {
    $page_con = 1;
} else {
    $page_con = getIsset('page');
}
$strMonthCut = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
$start = $page_con - 1;
$limit = 12; // จำนวน record ที่แสดง


function curRootPath($localhost_path, $server_name = 'localhost')
{
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"])) if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    //$server_name = $_SERVER["SERVER_NAME"];
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $server_name . ":" . $_SERVER["SERVER_PORT"] . '/' . $localhost_path;
    } else if ($_SERVER["SERVER_PORT"] == "80" && ($server_name == 'localhost' || $server_name == '127.0.0.1')) {
        $pageURL .= $server_name . '/' . $localhost_path;
    } else {
        $pageURL .= $server_name;
    }
    return $pageURL;
}

function getJsonObjectInput()
{
    $json = file_get_contents('php://input');
    $obj = json_decode($json, TRUE);
    return $obj;
}

function redirectTo($url)
{
    header('location: ' . $url);
    //echo 'redirect to '.$url;
    exit(0);
}

//Convert to thai date
function DateThai($strDate = 'now')
{
    if ($strDate == 'now') $strDate = date('Y-m-d');
    $strYear = date("Y", strtotime($strDate));
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strMonthCut = Array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}

function DateMonthThai($year, $month)
{
    $strMonthCut = Array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$month];
    return " $strMonthThai $year";
}
function DateMonthThaiByDate($strDate = 'now')
{
    $year = date("Y", strtotime($strDate));
    $month = date("n", strtotime($strDate));
    $strMonthCut = Array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strMonthThai = $strMonthCut[$month];
    return " $strMonthThai $year";
}
function DateDayMonthThaiByDate($strDate = 'now')
{
    $year = date("Y", strtotime($strDate));
    $month = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strMonthCut = Array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strMonthThai = $strMonthCut[$month];
    return " $strDay $strMonthThai $year";
}

function toYYYYMMDD_thai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("m", strtotime($strDate));
    $strDay = date("d", strtotime($strDate));
    return $strYear . '-' . $strMonth . '-' . $strDay;
}

function toDDMMYYYY_thai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("m", strtotime($strDate));
    $strDay = date("d", strtotime($strDate));
    return $strDay . '-' . $strMonth . '-' . $strYear;
}

function toDDMMYYYY($strDate)
{
    $strYear = date("Y", strtotime($strDate));
    $strMonth = date("m", strtotime($strDate));
    $strDay = date("d", strtotime($strDate));
    return $strDay . '-' . $strMonth . '-' . $strYear;
}

function getIsset($post_value)
{
    $value = "";
    if (isset($_GET[$post_value])) {
        $value = $_GET[$post_value];
    }
    if (isset($_POST[$post_value])) {
        $value = $_POST[$post_value];
    }
    return $value;
}

function alertMassage($str)
{
    echo "<script>alert('" . $str . "');</script>";
}

function confirmMassage($str)
{
    echo "<script>confirm('" . $str . "');</script>";
}

function toThaiBath($number)
{
    $thai_bath = new Pongpop\Number\NumberThai();
    return $thai_bath->convertBaht($number);
}

function generateFileName($file)
{
    $FileName = strtolower($file['name']); //uploaded file name
    $ImageExt = substr($FileName, strrpos($FileName, '.')); //file extension
    $RandNumber = date('YmdHis') . rand(0, 999999); //Random number to make each filename unique.
    $NewFileName = $RandNumber . $ImageExt;
    return $NewFileName;
}

function uploadFile($file, $old_file_name, $path)
{
    $file_name = $old_file_name;
    if (!empty($file['name'])) {
        if ($file['name'] == '' && $old_file_name != '') return $old_file_name;
        if ($file['name'] == '') return '';

        $file_name = generateFileName($file);
        if (move_uploaded_file($file["tmp_name"], $path . $file_name)) {
        }
    }
    return $file_name;
}
