<?php

function checkCrudParameters()
{
    // checkFormat();
    // checkAction();
    // checkCrudParameterMatchUp();
    // checkCurrencyCode();
}

function checkCrudParameterMatchUp()
{
    if (count(array_intersect(PARAMS_CRUD, array_keys($_GET))) < 3) {
        displayError($error_code = 1000, $format = $_GET['format']);
        exit();
    } else if (count($_GET) < 3) {
        displayError($error_code = 1100, $format = $_GET['format']);
        exit();
    }
}

function checkAction()
{
    if ((!isset($_GET["action"])) || (empty($_GET["action"]))) {
        $_GET["action"] = null;
        displayError($error_code = 2000, $format = $_GET["format"]);
        exit();
    }
    if (in_array($_GET["action"], CRUD_OPTIONS) == false) {
        displayError($error_code = 2000, $format = $_GET["format"]);
        exit();
    }
}

function checkCurrencyCode()
{
    if ((!ctype_alpha($_GET["cur"])) || (strlen($_GET["cur"]) != 3)) {
        displayError($error_code = 2100, $format = $_GET["format"]);
        exit();
    }
    if (in_array($_GET["cur"], $GLOBALS['live_countries']) == false) {
        displayError($error_code = 2200, $format = $_GET["format"]);
        exit();
    }
}

function checkEmptyRate($currency_code)
{
    $currency = searchCurrency($currency_code, "../src/data/rates.xml");
    if ($currency["rate"] === "") {
        displayError("2300", $format = $_GET["format"]);
        exit();
    }
}
