<?php

// Function to calculate currency conversion
function calculate()
{
    // Load XML file containing currency rates
    $xml = simplexml_load_file("src/data/rates.xml");

    // Retrieve details of the 'from' and 'to' currencies
    $from_currency = searchCurrency(strtoupper($_GET["from"]), "src/data/rates.xml");
    $to_currency = searchCurrency(strtoupper($_GET["to"]), "src/data/rates.xml");

    // Check if the currency rates are outdated and update them if necessary
    if (isRateOutDated(date("Y-m-d H:i:s"), "src/data/rates.xml")) {
        writeXmlRates("src/data/rates.xml", "src/data/live_countries.json");
    }

    // Prepare details of the 'from' currency
    $from_currency_details = [
        "code" => strval($from_currency->code),
        "rate" => (float) ($from_currency["rate"]),
        "curr" => strval($from_currency->curr),
        "loc" => strval($from_currency->loc),
        "amnt" => (float) $_GET["amnt"],
    ];

    // Prepare details of the 'to' currency
    $to_currency_details = [
        "code" => strval($to_currency->code),
        "rate" => (float) ($to_currency["rate"]),
        "curr" => strval($to_currency->curr),
        "loc" => strval($to_currency->loc),
    ];

    // Calculate the exchange rate between the currencies
    $exchange_rate = $to_currency_details["rate"] / $from_currency_details["rate"];
    $to_currency_details["amnt"] = $exchange_rate * $from_currency_details["amnt"];

    // Prepare conversion details
    $convert_details = [
        "at" => formatDate($xml["ts"]), // Format timestamp of currency rates
        "rate" => $exchange_rate, // Exchange rate
        "from" => $from_currency_details, // Details of 'from' currency
        "to" => $to_currency_details, // Details of 'to' currency
    ];

    return $convert_details;
}