<?php
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

@date_default_timezone_set("GMT");

checkCrudParameters();

if (!file_exists("../src/data/rates.xml")) {
    try {
        writeXmlRates("../src/data/rates.xml");
    } catch (Exception $e) {
        displayError($error_code = 1500, $format = $_GET["format"]);
        exit();
    }
}

if (isRateOutDated("GBP", date("Y-m-d H:i:s"), "src/data/rates.xml", 2)) {
    writeXmlRates("../src/data/rates.xml");
}

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (handlePostRequest("../src/data/rates.xml")) {
            respondPostResquest();
        }
        break;
    case "PUT":
        $currency_history = handlePutRequest();
        respondPutRequest($currency_history);
        break;
    case "DELETE":
        if (handleDelRequest("../src/data/rates.xml")) {
            responseDeleteRequest();
        }
}