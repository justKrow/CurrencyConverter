<?php
include("src/data/config.php");

function callAPI($end_point, ?string $attribute)
{
    $url = BASE_URL . $end_point . API_KEY . $attribute;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);

    if ($response === false) {
        displayError($error_code = 1500, $format = $_GET['format']);
    }
    return $response;
}


