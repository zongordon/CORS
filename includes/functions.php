<?php
//Moved header code to separate header.php file
//Replaced code for finding ip address
//Turned off function GetSQLValueString()
//Moved restrict access code to restrict_access.php

//Validate email
function valid_email($email) {
    return !!filter_var($email, FILTER_VALIDATE_EMAIL);
}


//Transform server time to GMT+1 and set timestamp for Now()
date_default_timezone_set('Europe/Stockholm');
$now = date('Y-m-d H:i');

if (!isset($_SESSION)) {
  session_start();
}

function get_ip_address() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if (validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}
/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}
$user_ip = get_ip_address();
//echo 'IP: '.$user_ip;
    
//Convert strings to UTF-8
function encodeToUtf8($string) {
     return mb_convert_encoding($string, "UTF-8", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
}

//Convert strings to ISO-8859-1
function encodeToISO($string) {
     return mb_convert_encoding($string, "ISO-8859-1", mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true));
}
?>