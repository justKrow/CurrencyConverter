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
        $rates_data = callAPI($end_point = "v3/latest?apikey=", $attribute = "&base_currency=GBP&currencies[]=" . $currency_code);
        $currencies_data = callAPI($end_point = "v3/currencies?apikey=", $attribute = "&currencies[]=" . $currency_code);
        $transformed_data = transformData($rates_data, $currencies_data);

        $currency = $dom->createElement("Currency");
        $currency->setAttribute('rate', strval($transformed_data[0]["rate"]));
        $currency->setAttribute('live', 1);

        $currency->appendChild($dom->createElement("code", strval($transformed_data[0]["code"])));
        $currency->appendChild($dom->createElement("curr", strval($transformed_data[0]["curr"])));
        $currency->appendChild($dom->createElement("loc", strval($transformed_data[0]["loc"])));

        $dom->documentElement->appendChild($currency);
        $GLOBALS['live_countries'][] = $currency_code;
    }
    $dom->save($xml_file_path);
}