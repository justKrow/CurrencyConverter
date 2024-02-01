<?php
include("src/core/request_data.php");
include("src/core/generate_XML.php");
include("src/utils/validate.php");
include("src/utils/validation.php");
@date_default_timezone_set("GMT");

define('PARAMS', array('to', 'from', 'amount', 'format'));

validateParam();

$rates_file_path = "src/data/rates.xml";

if (!file_exists($rates_file_path)) {
    $rates_data = callAPI("v3/latest?apikey=cur_live_HFBzAC5K8BiW2Zfcfp6NbwU1auQCayMQDvQlqIos&base_currency=GBP");
    $currencies_data = callAPI("v3/currencies?apikey=cur_live_HFBzAC5K8BiW2Zfcfp6NbwU1auQCayMQDvQlqIos");

    $transformed_data = transformData($rates_data, $currencies_data);
    createXML($transformed_data, $rates_data["meta"], $rates_file_path);
}