<?php

function checkConvertParameters()
{
    checkFormat();
    checkParameterMatchUp();
    checkCurrencyLive();
    checkAmntFormat();
}

function checkFormat()
{
    if (!isset($_GET['format']) || empty($_GET['format'])) {
        $_GET['format'] = 'xml';
    } else if ($_GET['format'] !== "xml" && $_GET['format'] !== "json") {
        displayError($error_code = 1400, $format = "xml");
        exit();
    }
}

function checkParameterMatchUp()
{
    if (count(array_intersect(PARAMS, array_keys($_GET))) < 4) {
        displayError($error_code = 1000, $format = $_GET['format']);
        exit();
    } else if (count($_GET) > 4) {
        displayError($error_code = 1100, $format = $_GET['format']);
        exit();
    }
}

function checkCurrencyLive()
{
    if ((in_array($_GET["from"], $GLOBALS['live_countries']) == false) || (in_array($_GET["from"], $GLOBALS['live_countries']) == false)) {
        displayError($error_code = 1200, $format = $_GET['format']);
        exit();
    }
}

function checkAmntFormat()
{
    if (!is_numeric($_GET['amnt']) || strpos($_GET['amnt'], '.') === false) {
        displayError($error_code = 1300, $format = $_GET['format']);
        exit();
    }
}