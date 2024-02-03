<?php
# use your own API key from https://currencyapi.com/
define("BASE_URL", "https://api.currencyapi.com/");
define("API_KEY", "cur_live_HFBzAC5K8BiW2Zfcfp6NbwU1auQCayMQDvQlqIos");

# live countries
$GLOBALS['live_countries'] = array("AUD", "BRL", "CAD", "CHF", "CNY", "DKK", "EUR", "GBP", "HKD", "HUF", "INR", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "RUB", "SEK", "SGD", "THB", "TRY", "USD", "ZAR");

# error codes
define(
    "ERROR_CODES",
    array(
        "1000" => "Required parameter is missing",
        "1100" => "Parameter not recognized",
        "1200" => "Currency type not recognized",
        "1300" => "Currency amount must be a decimal number",
        "1400" => "Format must be xml or json",
        "1500" => "Error in service",
        "2000" => "Action not recognized or is missing",
        "2100" => "Currency code in wrong format or is missing",
        "2200" => "Currency code not found for update",
        "2300" => "No rate listed for this currency",
        "2400" => "Cannot update base currency",
        "2500" => "Error in service"
    )
);

# path for rates.xml
define("RATES_XML_FILE", "src/data/rates.xml");