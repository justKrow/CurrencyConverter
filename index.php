<?php
include("src/core/request_data.php");
include("src/core/generate_XML.php");
include("src/utils/validation.php");
@date_default_timezone_set("GMT");

define('PARAMS', array('to', 'from', 'amnt', 'format'));

if (!isset($_GET['format']) || empty($_GET['format'])) {
    $_GET['format'] = 'xml';
}

# ensure PARAM values match the keys in $GET
if (count(array_intersect(PARAMS, array_keys($_GET))) < 4) {
    if ($_GET['format'] == "xml") {
        displayXmlError($error_code = 1000, $error_message = "Required parameter is missing");
    } else if ($_GET['format'] == "json") {
        displayJsonError($error_code = 1000, $error_message = "Required parameter is missing");
    }
    exit();
}

# ensure no extra params
if (count($_GET) > 4) {
    if ($_GET['format'] == "xml") {
        displayXmlError($error_code = 1100, $error_message = "Parameter not recognized");
    } else if ($_GET['format'] == "json") {
        displayJsonError($error_code = 1100, $error_message = "Parameter not recognized");
    }
    exit();
}

if () {

}

$from_currency = $_GET['from'];
$to_currency = $_GET['to'];
$amount = $_GET['amnt'];
$format = $_GET['format'];
$rates_file_path = "src/data/rates.xml";

if (!file_exists($file_path)) {
    $rates_json = callAPI("v3/latest?apikey=cur_live_96KTMb7o5rCL1KM8A2EwznmdXIHWnM9Spovv0QdT&base_currency=GBP");
    $rates_data = json_decode($rates_json, true);

    $currencies_json = callAPI("v3/currencies?apikey=cur_live_96KTMb7o5rCL1KM8A2EwznmdXIHWnM9Spovv0QdT");
    $currencies_data = json_decode($currencies_json, true);

    $transformed_data = transformData($rates_data, $currencies_data);
    $xmlContent = createXML($transformed_data, $rates_data["meta"]);
    file_put_contents("src/data/rates.xml", $xmlContent);
}