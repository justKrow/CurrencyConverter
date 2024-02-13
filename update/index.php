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
        writeXmlRates("../src/data/rates.xml");
    } catch (Exception $e) {
        // Handle errors
        displayError($error_code = 1500, $format = $_GET["format"]);
        exit();
    }
}

// Check if GBP rate is outdated, if yes, update rates XML
if (isRateOutDated("GBP", date("Y-m-d H:i:s"), "src/data/rates.xml", 2)) {
    writeXmlRates("../src/data/rates.xml");
}

// Handle different HTTP request methods
switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        // Handle POST request
        if (handlePostRequest("../src/data/rates.xml")) {
            // Respond to POST request
            respondPostResquest();
        }
        break;
    case "PUT":
        // Handle PUT request
        $currency_history = handlePutRequest();
        // Respond to PUT request
        respondPutRequest($currency_history);
        break;
    case "DELETE":
        // Handle DELETE request
        if (handleDelRequest("../src/data/rates.xml")) {
            // Respond to DELETE request
            responseDeleteRequest();
        }
        break;
}