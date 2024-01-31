<?php
include("src/core/request_data.php");
include("src/core/generate_XML.php");
@date_default_timezone_set("GMT");

define('PARAMS', array('to', 'from', 'amnt', 'format'));

if (!isset($_GET['format']) || empty($_GET['format'])) {
    $_GET['format'] = 'xml';
}

# ensure PARAM values match the keys in $GET
if (count(array_intersect(PARAMS, array_keys($_GET))) < 4) {
    echo "a 1000: error";
    exit();
}

# ensure no extra params
if (count($_GET) > 4) {
    echo "a 1100: error";
    exit();
}

$file_path = "src/data/rates.xml";

if (!file_exists($file_path)) {
    $rates_json = callAPI("v3/latest?apikey=cur_live_96KTMb7o5rCL1KM8A2EwznmdXIHWnM9Spovv0QdT&base_currency=GBP");
    $rates_data = json_decode($rates_json, true);

    $currencies_json = callAPI("v3/currencies?apikey=cur_live_96KTMb7o5rCL1KM8A2EwznmdXIHWnM9Spovv0QdT");
    $currencies_data = json_decode($currencies_json, true);

    $transformed_data = transformData($rates_data, $currencies_data);
    $xmlContent = createXML($transformed_data, $rates_data["meta"]);
    file_put_contents("src/data/rates.xml", $xmlContent);
}

$fromCurrency = $_GET['from'];
$toCurrency = $_GET['to'];
$amount = $_GET['amnt'];
$format = $_GET['format'];

print($fromCurrency);
print($toCurrency);
print($amount);
print($format);