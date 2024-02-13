<?php

// Function to call an API
function callAPI($end_point, ?string $attribute)
{
    // Construct API URL
    $url = BASE_URL . $end_point . API_KEY . $attribute;

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    // Execute cURL request
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response
    $response = json_decode($response, true);

    // Check if decoding failed
    if ($response === false) {
        // Display error and exit
        displayError($error_code = 1500, $format = $_GET['format']);
        exit();
    }

    return $response;
}
