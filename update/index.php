<?php
include("post/post_handling.php");
include("post/post_response.php");
include("put/put_handling.php");
include("put/put_response.php");
include("delete/delete_handling.php");
include("validate_CRUD.php");
include("../src/utils/validate.php");
include("../src/data/config.php");
include_once("../src/utils/validation.php");
include("../src/utils/xml_tweaks.php");
include("../src/core/request_data.php");
include("../src/core/generate_rates_XML.php");

@date_default_timezone_set("GMT");


// checkFormat();
// checkCrudParameters();

// switch ($_SERVER["REQUEST_METHOD"]) {
//     case "POST":
//         handlePostRequest($_GET["cur"], 1, "/var/www/html/CurrencyConverter/src/data/rates.xml");
//         break;
//     case "PUT":
//         handlePutRequest();
//         print "Put";
//         break;
//     case "DELETE":
//         handleDeleteRequest();
//         print "delete";
// }
// handleDeleteRequest("GBP");
$timestamp = handlePostRequest($_GET["cur"], "../src/data/rates.xml");
respondPostResquest($timestamp);