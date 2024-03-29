<?php

// Include necessary files
include("src/core/request_data.php");
include("src/core/generate_rates_XML.php");
include("src/core/convert.php");
include("src/utils/validate.php");
include("src/utils/validation.php");
include("src/utils/xml_tweaks.php");
include("src/utils/responses.php");
include_once("src/data/config.php");

// Set default timezone to GMT
@date_default_timezone_set("GMT");

// Check and process conversion parameters
checkConvertParameters();

// If rates XML file doesn't exist, generate it
if (!file_exists("src/data/rates.xml")) {
    try {
        writeXmlRates("src/data/rates.xml", "src/data/live_countries.json");
    } catch (Exception $e) {
        // Display error if XML file generation fails
        displayError($error_code = 1500, $format = $_GET["format"]);
        exit();
    }
}

// Check if GBP rate is outdated and regenerate rates XML if necessary
if (isRateOutDated(date("Y-m-d H:i:s"), "src/data/rates.xml")) {
    writeXmlRates("src/data/rates.xml", "src/data/live_countries.json");
}

// Calculate currency exchange details
$curreny_exchange_details = calculate();

// Display the conversion result based on the requested format
displayConvertResult($curreny_exchange_details, $format = $_GET["format"]);
