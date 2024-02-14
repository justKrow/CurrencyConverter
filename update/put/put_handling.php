<?php

function handlePutRequest()
{
    try {
        // Check if the currency is GBP (British Pound)
        if ($_GET["cur"] === "GBP") {
            // Display error and exit if the currency is GBP
            displayError($error_code = 2400, $format = $_GET["format"]);
            exit();
        }

        // Search for the currency in the XML file
        $currency = searchCurrency($_GET["cur"], "../src/data/rates.xml");

        // Check if the currency is not marked as live
        if ($currency["live"] == 0) {
            // Display error and exit if the currency is not live
            displayError($error_code = 2200, $format = $_GET["format"]);
            exit();
        }

        // Prepare currency history data
        $currency_history = [
            "at" => formatDate(date("Y-m-d H:i:s")),
            "old_rate" => strval($currency["rate"]),
            "code" => strval($currency->code),
            "name" => strval($currency->curr),
            "loc" => strval($currency->loc)
        ];

        // Check if the currency rate is outdated
        if (isRateOutDated($_GET["cur"], date("Y-m-d H:i:s"), "../src/data/rates.xml")) {
            // Write updated rates to XML file if outdated
            writeXmlRates("../src/data/rates.xml");
            // Search for the currency again after updating rates
            $currency = searchCurrency($_GET["cur"], "../src/data/rates.xml");
            // Add status and updated rate to currency history
            $currency_history[] = ["status" => "outdated", "rate" => strval($currency["rate"])];
        } else {
            // Add status indicating that rate was updated
            $currency_history[] = ["status" => "updated"];
        }

        // Return currency history data
        return $currency_history;
    } catch (Exception $e) {
        // Display error and exit if an exception occurs
        displayError($error_code = 2500, $format = $_GET["format"]);
        exit();
    }
}
