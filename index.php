<?php
include("src/core/request_data.php");
include("src/core/generate_rates_XML.php");
include("src/core/convert.php");
include("src/utils/validate.php");
include("src/utils/validation.php");
include("src/utils/xml_tweaks.php");
include("src/utils/constants.php");

@date_default_timezone_set("GMT");

define('PARAMS', array('to', 'from', 'amnt', 'format'));

validateParam();

$rates_file_path = "src/data/rates.xml";

if (!file_exists($rates_file_path)) {
    $rates_data = callAPI($end_point = "v3/latest?apikey=", $attribute = "&base_currency=GBP");
    $currencies_data = callAPI($end_point = "v3/currencies?apikey=", $base_currency = null);

    $transformed_data = transformData($rates_data, $currencies_data);
    createXML($transformed_data, $rates_data["meta"], $rates_file_path);
}

$curreny_exchange_details = calculate();
displayConvertXmlResult($curreny_exchange_details);
// print_r($curreny_exchange_details);