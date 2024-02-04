<?php

function searchCurrency($query, $xml_file_path)
{
    $xml = simplexml_load_file($xml_file_path);
    foreach ($xml->Currency as $currency) {
        if ($currency->code == $query) {
            // Return the currency node
            return $currency;
        }
    }
    displayError($error_code = "2200", $format = $_GET["format"]);
    return false;
}


function formatDate($date)
{
    $date = new DateTime($date);
    return ($date->format('M d, Y h:i A'));
}

function isRateOutDated($currency, $current_time)
{
    $xml = simplexml_load_file("src/data/rates.xml");
    $current_time = new DateTime($current_time);
    $last_updated_time = new DateTime($xml["ts"]);

    $interval = $current_time->diff($last_updated_time);
    $hours = $interval->h;
    $hours = $hours + ($interval->days * 24);

    if ($hours > 12) {
        return true;
    }

    return false;
}