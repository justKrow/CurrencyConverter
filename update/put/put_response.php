<?php

function respondPutRequest($currency_history)
{
    // Determine the format of the response and call the appropriate function
    if ($_GET["format"] == "xml") {
        respondPutXmlRequest($currency_history);
    } else if ($_GET["format"] == "json") {
        respondPutJsonRequest($currency_history);
    }
}

function respondPutXmlRequest($currency_history)
{
    // Set response header for XML
    header("Content-Type: application/xml");

    // Create a new DOMDocument instance
    $dom = new DOMDocument();

    // Create the root 'action' element
    $action = $dom->createElement("action");
    $action->setAttribute("type", $_GET["action"]); // Set 'type' attribute for action
    $dom->appendChild($action);

    // Add 'at' element indicating the time of action
    $at = $dom->createElement("at", ($currency_history["at"]));
    $action->appendChild($at);

    // Add 'rate' element based on currency update status
    if ($currency_history["status"] == "outdated") {
        $rate = $dom->createElement("rate", strval($currency_history["rate"])); // New rate if outdated
    } else {
        $rate = $dom->createElement("rate", "already up to date"); // Indicate already up to date if not outdated
    }
    $action->appendChild($rate);

    // Add 'old_rate' element indicating the previous rate
    $old_rate = $dom->createElement("old_rate", strval($currency_history["old_rate"]));
    $action->appendChild($old_rate);

    // Add 'curr' element containing currency details
    $curr = $dom->createElement("curr");
    $action->appendChild($curr);

    // Add sub-elements for currency details: code, name, loc
    $code = $dom->createElement("code", strval($currency_history["code"]));
    $curr->appendChild($code);
    $name = $dom->createElement("name", strval($currency_history["name"]));
    $curr->appendChild($name);
    $loc = $dom->createElement("loc", strval($currency_history["loc"]));
    $curr->appendChild($loc);

    // Output the XML response
    echo $dom->saveXML();
}

function respondPutJsonRequest($currency_history)
{
    // Set response header for JSON
    header('Content-Type: application/json');

    // Prepare JSON response array
    $response = [
        "action" => $_GET["action"],
        "at" => $currency_history["at"],
        "rate" => ($currency_history["status"] == "outdated") ? strval($currency_history["rate"]) : "already up to date",
        "old_rate" => strval($currency_history["old_rate"]),
        "curr" => [
            "code" => strval($currency_history["code"]),
            "name" => strval($currency_history["name"]),
            "loc" => strval($currency_history["loc"])
        ]
    ];

    // Output the JSON response
    echo json_encode($response, JSON_PRETTY_PRINT);
}
