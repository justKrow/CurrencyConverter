<?php
include("src/core/request_data.php");
include("src/core/generate_XML.php");
include("src/utils/validate.php");
include("src/utils/validation.php");
@date_default_timezone_set("GMT");

define('PARAMS', array('to', 'from', 'amnt', 'format'));

validateParam();

$from_currency = $_GET['from'];
$to_currency = $_GET['to'];
$amount = $_GET['amnt'];
$format = $_GET['format'];
$rates_file_path = "src/data/rates.xml";

if (!file_exists($file_path)) {
    $rates_json = callAPI("v3/latest?apikey=cur_live_96KTMb7o5rCL1KM8A2EwznmdXIHWnM9Spovv0QdT&base_currency=GBP");

    $currencies_json = callAPI("v3/currencies?apikey=cur_live_96KTMb7o5rCL1KM8A2EwznmdXIHWnM9Spovv0QdT");

    $transformed_data = transformData($rates_data, $currencies_data);
    $xmlContent = createXML($transformed_data, $rates_data["meta"]);
    file_put_contents("src/data/rates.xml", $xmlContent);
}