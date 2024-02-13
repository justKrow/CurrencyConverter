<?php

// Function to handle response for POST request
function respondPostResquest()
{
    // Check format and call appropriate function
    if ($_GET["format"] == "xml") {
        respondPostXmlRequest();
    } else if ($_GET["format"] == "json") {
        respondPostJsonRequest();
    }
}

// Function to generate XML response for POST request
function respondPostXmlRequest()
{
    // Set header for XML content
    header("Content-Type: application/xml");

    // Search for currency in XML data
    $currency = searchCurrency($_GET["cur"], "../src/data/rates.xml");
    $dom = new DOMDocument();

    // Create root element 'action'
    $action = $dom->createElement("action");
    $action->setAttribute("type", $_GET["action"]);
    $dom->appendChild($action);

    // Append 'at' element
    $at = $dom->createElement("at");
    $action->appendChild($at);

    // Append 'rate' element
    $rate = $dom->createElement("rate", strval($currency["rate"]));
    $action->appendChild($rate);

    // Append 'curr' element
    $curr = $dom->createElement("curr");
    $action->appendChild($curr);

    // Append sub-elements under 'curr'
    $code = $dom->createElement("code", strval($currency->code));
    $curr->appendChild($code);
    $name = $dom->createElement("name", strval($currency->curr));
    $curr->appendChild($name);
    $loc = $dom->createElement("loc", strval($currency->loc));
    $curr->appendChild($loc);

    // Output XML
    echo $dom->saveXML();
}

// Function to generate JSON response for POST request
function respondPostJsonRequest()
{
    // Search for currency in XML data
    $currency = searchCurrency($_GET["cur"], "../src/data/rates.xml");

    // Set header for JSON content
    header("Content-Type: application/json");

    // Prepare response array
    $response = [
        "action" => [
            "type" => $_GET["action"],
            "at" => formatDate(date("Y-m-d H:i:s")),
            "rate" => strval($currency["rate"]),
            "curr" => [
                "code" => strval($currency->code),
                "name" => strval($currency->curr),
                "loc" => strval($currency->loc)
            ]
        ]
    ];

    // Output JSON with pretty printing
    echo json_encode($response, JSON_PRETTY_PRINT);
}
