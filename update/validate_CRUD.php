<?php

// Function to check CRUD parameters
function checkCrudParameters()
{
    // Check format, action, parameter match-up, and currency code
    checkFormat();
    checkAction();
    checkCrudParameterMatchUp();
    checkCurrencyCode();
}

// Function to check parameter match-up for CRUD operations
function checkCrudParameterMatchUp()
{
    // Check if required parameters are present
    if (count(array_intersect(PARAMS_CRUD, array_keys($_GET))) < 3) {
        // Display error if required parameters are missing
        displayError($error_code = 1000, $format = $_GET['format']);
        exit();
    } else if (count($_GET) < 3) {
        // Display error if too few parameters are present
        displayError($error_code = 1100, $format = $_GET['format']);
        exit();
    }
}

// Function to check if action parameter is valid
function checkAction()
{
    // Check if action parameter is present and valid
    if ((!isset($_GET["action"])) || (empty($_GET["action"]))) {
        $_GET["action"] = null;
        // Display error if action parameter is missing or empty
        displayError($error_code = 2000, $format = $_GET["format"]);
        exit();
    }
    if (in_array($_GET["action"], CRUD_OPTIONS) == false) {
        // Display error if action parameter is invalid
        displayError($error_code = 2000, $format = $_GET["format"]);
        exit();
    }
}

// Function to check if currency code is valid
function checkCurrencyCode()
{
    // Check if currency code is alphabetic and has length of 3
    if ((!ctype_alpha($_GET["cur"])) || (strlen($_GET["cur"]) != 3) || (!isset($_GET["action"]))) {
        // Display error if currency code is invalid
        displayError($error_code = 2100, $format = $_GET["format"]);
        exit();
    }
}

// Function to check if rate for a currency is empty
function checkEmptyRate($currency_code)
{
    // Search for currency in XML file
    $currency = searchCurrency($currency_code, "../src/data/rates.xml");
    // Display error if rate for currency is empty
    if ($currency["rate"] === "") {
        displayError("2300", $format = $_GET["format"]);
        exit();
    }
}
