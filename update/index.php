<?php
include("update/crud_functions.php");

@date_default_timezone_set("GMT");

print $_SERVER["REQUEST_METHOD"];
switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        print "post";
        handlePostRequest();
        break;
    case "PUT":
        handlePutRequest();
        print "Put";
        break;
    case "DELETE":
        handleDeleteRequest();
        print "delete";
}
