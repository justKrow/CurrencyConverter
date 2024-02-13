<?php

// Function to handle POST request
function handlePostRequest($xml_file_path)
{
    try {
        // Load XML file and create XPath object
        $dom = new DOMDocument();
        $dom->load($xml_file_path);
        $xpath = new DOMXPath($dom);

        // Check if the currency already exists in the XML data
        $query = sprintf("//Currency[code='%s']", $_GET["cur"]);
        $entries = $xpath->query($query);

        // If currency exists, update 'live' attribute
        if ($entries->length > 0) {
            foreach ($entries as $entry) {
                $entry->setAttribute('live', 1);
            }
        } else {
            // If currency doesn't exist, fetch data from API and create a new element
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

        // Update live countries list
        $live_countries = json_decode(file_get_contents('../src/data/live_countries.json'), true);
        if (!in_array($_GET["cur"], $live_countries)) {
            $live_countries[] = $_GET["cur"];
        }
        file_put_contents('../src/data/live_countries.json', json_encode($live_countries, JSON_PRETTY_PRINT));

        // Save changes to XML file
        $dom->save($xml_file_path);

        // Return true to indicate successful handling of request
        return true;
    } catch (Exception $e) {
        // If an exception occurs, display error
        displayError($error_code = 2500, $format = $_GET["format"]);
    }
}
