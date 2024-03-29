<?php
// Include necessary files
include("post/post_handling.php");
include("post/post_response.php");
include("put/put_handling.php");
include("put/put_response.php");
include("delete/delete_handling.php");
include("delete/delete_reponse.php");
include("validate_CRUD.php");
include("../src/utils/validate.php");
include("../src/data/config.php");
include("../src/utils/validation.php");
include("../src/utils/xml_tweaks.php");
include("../src/core/request_data.php");
include("../src/core/generate_rates_XML.php");

// Set default timezone to GMT
@date_default_timezone_set("GMT");

// Validate CRUD parameters
checkCrudParameters();

// Check if rates XML file exists, if not, generate it
if (!file_exists("../src/data/rates.xml")) {
    try {
        writeXmlRates("../src/data/rates.xml", "../src/data/live_countries.json");
    } catch (Exception $e) {
        // Handle errors
        displayError($error_code = 1500, $format = $_GET["format"]);
        exit();
    }
}

// Handle different HTTP request methods
if ($_SERVER["REQUEST_METHOD"] == "POST" || $_GET["action"] == "post") {
    // Handle POST request
    if (handlePostRequest("../src/data/rates.xml")) {
        respondPostResquest();
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT" || $_GET["action"] == "put") {
    // Handle PUT request
    $currency_history = handlePutRequest();
    respondPutRequest($currency_history);
} elseif ($_SERVER["REQUEST_METHOD"] == "DELETE" || $_GET["action"] == "del") {
    // Handle DELETE request
    if (handleDelRequest("../src/data/rates.xml")) {
        responseDeleteRequest();
    }
}