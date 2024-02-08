<?php

function handleDeleteRequest($currency_code)
{
    $dom = new DOMDocument();
    $dom->load("../src/data/rates.xml");
    $xpath = new DOMXPath($dom);

    // Check if the currency already exists
    $query = sprintf("//Currency[code='%s']", $currency_code);
    $entries = $xpath->query($query);

    if ($entries->length > 0) {
        // Currency exists, update 'live' attribute
        foreach ($entries as $entry) {
            $entry->setAttribute('live', 0);
        }
        $GLOBALS['live_countries'] = array_filter($GLOBALS['live_countries'], function ($item) use ($currency_code) {
            return $item !== $currency_code; // Removes all items with the value 'c'
        });
        print_r($GLOBALS['live_countries']);
        $dom->save("../src/data/rates.xml");
    } else {
        displayError($error_code = 1200, $format = $_GET['format']);
        exit();
    }
}

