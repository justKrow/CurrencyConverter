<?php

// Function to handle response for DELETE request
function responseDeleteRequest()
{
    // Check format and call appropriate function
    if ($_GET["format"] == "xml") {
        responseDeleteXmlRequest();
    } else if ($_GET["format"] == "json") {
        responseDeleteJsonRequest();
    }
}

// Function to generate XML response for DELETE request
function responseDeleteXmlRequest()
{
    // Set header for XML content
    header("Content-Type: application/xml");

    // Create a new DOMDocument
    $dom = new DOMDocument();

    // Create root element 'action'
    $action = $dom->createElement("action");
    $action->setAttribute("type", $_GET["action"]);
    $dom->appendChild($action);

    // Append 'at' element with current date and time
    $at = $dom->createElement("at", formatDate(date("Y-m-d H:i:s")));
    $action->appendChild($at);

    // Append 'code' element with currency code
    $code = $dom->createElement("code", strval($_GET["cur"]));
    $action->appendChild($code);

    // Output XML
    echo $dom->saveXML();
}

// Function to generate JSON response for DELETE request
function responseDeleteJsonRequest()
{
    // Set header for JSON content
    header("Content-Type: application/json");

    // Prepare response array
    $response = [
        "action" => [
            "type" => $_GET["action"],
            "at" => formatDate(date("Y-m-d H:i:s")),
            "code" => strval($_GET["cur"])
        ]
    ];

    // Output JSON with pretty printing
    echo json_encode($response, JSON_PRETTY_PRINT);
}
