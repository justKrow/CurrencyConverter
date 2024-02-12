<?php

function handlePostRequest($xml_file_path)
{
    try {
        $dom = new DOMDocument();
        $dom->load($xml_file_path);
        $xpath = new DOMXPath($dom);

        // Check if the currency already exists
        $query = sprintf("//Currency[code='%s']", $_GET["cur"]);
        $entries = $xpath->query($query);

        if ($entries->length > 0) {
            // Currency exists, update 'live' attribute
            foreach ($entries as $entry) {
                $entry->setAttribute('live', 1);
            }
        } else {
            // Currency doesn't exist, create a new element
            $rates_data = callAPI($end_point = "v3/latest?apikey=", $attribute = "&base_currency=GBP&currencies[]=" . $_GET["cur"]);
            $currencies_data = callAPI($end_point = "v3/currencies?apikey=", $attribute = "&currencies[]=" . $_GET["cur"]);
            $transformed_data = transformData($rates_data, $currencies_data);

            $currency = $dom->createElement("Currency");
            $currency->setAttribute('rate', strval($transformed_data[0]["rate"]));
            $currency->setAttribute('live', 1);

            $currency->appendChild($dom->createElement("code", strval($transformed_data[0]["code"])));
            $currency->appendChild($dom->createElement("curr", strval($transformed_data[0]["curr"])));
            $currency->appendChild($dom->createElement("loc", strval($transformed_data[0]["loc"])));

            $dom->documentElement->appendChild($currency);
        }
        #add to live_countries.json
        $live_countries = json_decode(file_get_contents('../src/data/live_countries.json'), true);

        // Check if the currency code is not already in the array
        if (!in_array($_GET["cur"], $live_countries)) {
            // Add the new currency code
            $live_countries[] = $_GET["cur"];
        }

        file_put_contents('../src/data/live_countries.json', json_encode($live_countries, JSON_PRETTY_PRINT));
        $dom->save($xml_file_path);
        return true;
    } catch (Exception $e) {
        displayError($error_code = 2500, $format = $_GET["format"]);
    }
}