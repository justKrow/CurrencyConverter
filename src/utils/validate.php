<?php

// Function to check if convert parameters are valid
function checkConvertParameters()
{
    // Check format parameter
    checkFormat();

    // Check if parameters match up
    checkParameterMatchUp();

    // Check if currency is live
    checkCurrencyLive();

    // Check amount format
    checkAmntFormat();
}

// Function to check format parameter
function checkFormat()
{
    // If format parameter is not set or empty, set it to 'xml'
    if (!isset($_GET['format']) || empty($_GET['format'])) {
        $_GET['format'] = 'xml';
    }
    // If format is not 'xml' or 'json', display error and exit
    else if ($_GET['format'] !== "xml" && $_GET['format'] !== "json") {
        displayError($error_code = 1400, $format = "xml");
        exit();
    }
}

// Function to check if parameters match up
function checkParameterMatchUp()
{
    // If the intersection of PARAMS and parameters in $_GET is less than 4, display error and exit
    if (count(array_intersect(PARAMS, array_keys($_GET))) < 4) {
        displayError($error_code = 1000, $format = $_GET['format']);
        exit();
    }
    // If there are more than 4 parameters in $_GET, display error and exit
    else if (count($_GET) > 4) {
        displayError($error_code = 1100, $format = $_GET['format']);
        exit();
    }
}

// Function to check if currency is live
function checkCurrencyLive()
{
    // Load live countries data
    $live_countries = json_decode(file_get_contents('src/data/live_countries.json'), true);

    // Check if 'from' and 'to' currencies are live, if not, display error and exit
    if ((in_array(strtoupper($_GET["from"]), $live_countries) == false) || (in_array(strtoupper($_GET["to"]), $live_countries) == false)) {
        displayError($error_code = 1200, $format = $_GET['format']);
        exit();
    }
}

// Function to check amount format
function checkAmntFormat()
{
    // If 'amnt' is not numeric or doesn't contain a decimal point, display error and exit
    if (!is_numeric($_GET['amnt']) || strpos($_GET['amnt'], '.') === false) {
        displayError($error_code = 1300, $format = $_GET['format']);
        exit();
    }
}

