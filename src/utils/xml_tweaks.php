<?php

// Function to search for a currency in the XML data
function searchCurrency($query, $xml_file_path)
{
    // Load XML file
    $xml = simplexml_load_file($xml_file_path);

    // Iterate through currencies in XML
    foreach ($xml->Currency as $currency) {
        // If currency code matches the query, return the currency details
        if ($currency->code == $query) {
            return $currency;
        }
    }

    // If currency is not found, display error and return false
    displayError($error_code = "2200", $format = $_GET["format"]);
    return false;
}

// Function to format date
function formatDate($date)
{
    // Create DateTime object from provided date
    $date = new DateTime($date);

    // Return formatted date string
    return ($date->format('M d, Y h:i A'));
}

// Function to check if currency rate is outdated
function isRateOutDated($currency, $current_time, $xml_file_path)
{
    // Load XML file
    $xml = simplexml_load_file($xml_file_path);

    // Create DateTime objects for current time and last updated time
    $current_time = new DateTime($current_time);
    $last_updated_time = new DateTime($xml["ts"]);

    // Calculate difference in hours between current time and last updated time
    $interval = $current_time->diff($last_updated_time);
    $hours = $interval->h;
    $hours = $hours + ($interval->days * 24);

    // If the difference in hours exceeds the interval, return true (rate is outdated)
    if ($hours > 2) {
        return true;
    }

    // Otherwise, return false (rate is up to date)
    return false;
}
