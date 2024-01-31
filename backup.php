<?php
include("src/core/request_data.php");
include("src/core/generate_XML.php");

$file_path = "src/data/rates.xml";

if (file_exists($file_path)) {
    echo "The file exists.";
} else {
    $rates_json = callAPI("v3/latest?apikey=cur_live_96KTMb7o5rCL1KM8A2EwznmdXIHWnM9Spovv0QdT&base_currency=GBP");
    $rates_data = json_decode($rates_json, true);

    $currencies_json = callAPI("v3/currencies?apikey=cur_live_96KTMb7o5rCL1KM8A2EwznmdXIHWnM9Spovv0QdT");
    $currencies_data = json_decode($currencies_json, true);

    $transformed_data = transformData($rates_data, $currencies_data);
    $xmlContent = createXML($transformed_data, $rates_data["meta"]);
    file_put_contents("src/data/rates.xml", $xmlContent);
}