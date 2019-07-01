<?php
define('ACCESS_DENIED_URL', get_root_path('apartment-management') . '/access_denied.php');
// User Level
define('ADMIN_LEVEL', 99);
define('USER_LEVEL', 1);

function is_has_session($user_level)
{
    $session = isset($_SESSION['uprofile']) ? $_SESSION['uprofile'] : null;
    if ($session == null) return false;
    if ($session['level'] != $user_level) return false;

    return true;
}

function is_list_session($list)
{
    $chk = 0;
    for ($i = 0; $i < sizeof($list); $i++) {
        if (is_has_session($list[$i])) {
            $chk++;
        }
    }
    if ($chk > 0) {
        return true;
    } else {
        return false;
    }

}

function redirectToAccessDenied()
{
    header('location: ' . ACCESS_DENIED_URL);
    //echo 'redirect to '.$url;
    exit(0);
}

function redirect_expire_session($list)
{
    if (!is_has_session($list))
        redirectToAccessDenied();
}

function redirect_to($url)
{
    header('location: ' . $url);
    //echo 'redirect to '.$url;
    exit(0);
}

function get_user_level($level)
{
    $level = intval($level);
    if (1 == $level)
        return ADMIN_LEVEL;
    if (2 == $level)
        return USER_LEVEL;
}

function get_root_path($localhost_path, $server_name = 'localhost')
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

function file_upload_max_size()
{
    static $max_size = -1;

    if ($max_size < 0) {
        // Start with post_max_size.
        $max_size = parse_size(ini_get('post_max_size'));

        // If upload_max_size is less, then reduce. Except if upload_max_size is
        // zero, which indicates no limit.
        $upload_max = parse_size(ini_get('upload_max_filesize'));
        if ($upload_max > 0 && $upload_max < $max_size) {
            $max_size = $upload_max;
        }
    }
    return $max_size;
}

function parse_size($size)
{
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
    $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
    if ($unit) {
        // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    } else {
        return round($size);
    }
}

