<?php
function validateParam()
{
    # ensure PARAM values match the keys in $GET
    if (count(array_intersect(PARAMS, array_keys($_GET))) < 4) {
        displayError($error_code = 1000, $error_message = "Required parameter is missing", $format = $_GET['format']);
        exit();
    }

    # ensure no extra params
    else if (count($_GET) > 4) {
        displayError($error_code = 1100, $error_message = "Parameter not recognized", $format = $_GET['format']);
        exit();
    }

    # ensure not recognized currencies
    $xml = simplexml_load_file("src/data/rates.xml");
    $to_currency_exist = false;
    $from_currency_exist = false;
    foreach ($xml->Currency as $currency) {
        if (strval($_GET['to']) == $currency->code) {
            $to_currency_exist = true;
        }
        if (strval($_GET['from']) == $currency->code) {
            $from_currency_exist = true;
        }
    }
    if (($to_currency_exist == false) || ($from_currency_exist == false)) {
        displayError($error_code = 1200, $error_message = "Currency type not recognized", $format = $_GET['format']);
        exit();
    }

    # ensure that amount is decimal number
    if (is_numeric($_GET['amount']) && strpos($_GET['amount'], '.') !== true) {
        displayError($error_code = 1300, $error_message = "Currency amount must be a decimal number", $format = $_GET['format']);
        exit();
    }

    # ensure correct format
    if (!isset($_GET['format']) || empty($_GET['format'])) {
        $_GET['format'] = 'xml';
    } else if ($_GET['format'] != "xml" || $_GET['format'] != "json") {
        displayError($error_code = 1400, $error_message = "Format must be xml or json", $format = "xml");
        exit();
    }
}