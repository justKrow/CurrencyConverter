<?php

function handlePostRequest($currency_code, $xml_file_path)
{
    $dom = new DOMDocument();
    $dom->load($xml_file_path);
    $xpath = new DOMXPath($dom);

    // Check if the currency already exists
    $query = sprintf("//Currency[code='%s']", $currency_code);
    $entries = $xpath->query($query);

    if ($entries->length > 0) {
        // Currency exists, update 'live' attribute
        foreach ($entries as $entry) {
            $entry->setAttribute('live', 1);
        }
    } else {
        // Currency doesn't exist, create a new element
        $currency = $dom->createElement("Currency");
        $currency->setAttribute('rate', '1');
        $currency->setAttribute('live', 1);
        $code = $dom->createElement("code", $currency_code);
        $currency->appendChild($code);

        // Add other necessary elements like "curr", "loc" etc. here

        $dom->documentElement->appendChild($currency);
    }

    $dom->save($xml_file_path);
    $GLOBALS['live_countries'][] = $currency_code;
    print_r($GLOBALS['live_countries']);
}


function handlePutRequest()
{

}

function handleDeleteRequest()
{

}