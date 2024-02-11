<?php
include("src/core/request_data.php");
include("src/core/generate_rates_XML.php");
include("src/core/convert.php");
include("src/utils/validate.php");
include("src/utils/validation.php");
include("src/utils/xml_tweaks.php");
include("src/utils/responses.php");
include_once("src/data/config.php");

@date_default_timezone_set("GMT");

checkConvertParameters();

if (!file_exists("src/data/rates.xml")) {
    try {
        writeXmlRates("src/data/rates.xml");
    } catch (Exception $e) {
        displayError($error_code = 1500, $format = $_GET["format"]);
        exit();
    }
}

if (isRateOutDated("GBP", date("Y-m-d H:i:s"), "src/data/rates.xml", 12)) {
    writeXmlRates("src/data/rates.xml");
}

$curreny_exchange_details = calculate();
displayConvertResult($curreny_exchange_details, $format = $_GET["format"]);