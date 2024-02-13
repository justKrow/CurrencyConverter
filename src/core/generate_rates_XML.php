<?php

// Function to write currency rates to an XML file
function writeXmlRates($output_file)
{
    // Call API to get rates and currencies data
    $rates_data = callAPI($end_point = "v3/latest?apikey=", $attribute = "&base_currency=GBP");
    $currencies_data = callAPI($end_point = "v3/currencies?apikey=", $attribute = null);

    // Transform rates and currencies data
    $transformed_data = transformData($rates_data, $currencies_data);

    // Create XML file with transformed data
    createXML($transformed_data, $output_file);
}

// Function to transform rates and currencies data
function transformData($rates_data, $currencies_data)
{
    $transformed_data = [];
    foreach ($rates_data["data"] as $code => $rate_info) {
        $currency_details = [
            "code" => $code,
            "rate" => $rate_info["value"],
            "curr" => $currencies_data["data"][$code]["name"] ?? "",
            "loc" => isset($currencies_data["data"][$code]["countries"]) ? implode(", ", $currencies_data["data"][$code]["countries"]) : ""
        ];
        $transformed_data[] = $currency_details;
    }
    return $transformed_data;
}

// Function to create XML file
function createXML($transformed_data, $output_file)
{
    // Load live countries data if available
    if (file_exists('src/data/live_countries.json')) {
        $live_countries = json_decode(file_get_contents('src/data/live_countries.json'), true);
    } else {
        $live_countries = [];
    }

    // Create DOMDocument
    $dom = new DOMDocument("1.0", "UTF-8");
    $root = $dom->createElement("rates");
    $dom->appendChild($root);
    $root->setAttribute("ts", date("Y-m-d H:i:s"));
    $root->setAttribute("base", "GBP");

    // Iterate through transformed data and create XML elements
    foreach ($transformed_data as $currency) {
        $currency_element = $dom->createElement("Currency");
        $currency_element->appendChild($dom->createElement("code", $currency["code"]));
        $currency_element->setAttribute("rate", $currency["rate"]);
        // Set 'live' attribute based on live countries
        if (in_array($currency["code"], $live_countries)) {
            $currency_element->setAttribute('live', 1);
        } else {
            $currency_element->setAttribute('live', 0);
        }
        $currency_element->appendChild($dom->createElement("curr", $currency["curr"]));
        $currency_element->appendChild($dom->createElement("loc", $currency["loc"]));

        $root->appendChild($currency_element);
    }

    // Save the XML file
    $dom->save($output_file);
}
