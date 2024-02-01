<?php
function callAPI($end_point)
{
    $base_url = "https://api.currencyapi.com/";
    $url = $base_url . $end_point;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    json_decode($response, true);

    if ($response === false) {
        displayError($error_code = 1500, $error_message = "Error in Service", $format = $_GET['format']);
    }

    return $response;
}


