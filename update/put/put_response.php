<?php

function respondPutRequest($currency_history)
{
    if ($_GET["format"] == "xml") {
        respondPutXmlRequest($currency_history);
    } else if ($_GET["format"] == "json") {
        respondPutJsonRequest($currency_history);
    }
}

function respondPutXmlRequest($currency_history)
{
    header("Content-Type: application/xml");
    $dom = new DOMDocument();

    $action = $dom->createElement("action");
    $action->setAttribute("type", $_GET["action"]);
    $dom->appendChild($action);

    $at = $dom->createElement("at", ($currency_history["at"]));
    $action->appendChild($at);

    if ($currency_history["status"] == "outdated") {
        $rate = $dom->createElement("rate", strval($currency_history["rate"]));
        $action->appendChild($rate);
    } else {
        $rate = $dom->createElement("rate", "already up to date");
        $action->appendChild($rate);
    }

    $old_rate = $dom->createElement("old_rate", strval($currency_history["old_rate"]));
    $action->appendChild($old_rate);

    $curr = $dom->createElement("curr");
    $action->appendChild($curr);

    $code = $dom->createElement("code", strval($currency_history["code"]));
    $curr->appendChild($code);

    $name = $dom->createElement("name", strval($currency_history["name"]));
    $curr->appendChild($name);

    $loc = $dom->createElement("loc", strval($currency_history["loc"]));
    $curr->appendChild($loc);

    echo $dom->saveXML();
}

function respondPutJsonRequest($currency_history)
{
    header('Content-Type: application/json');
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

    echo json_encode($response, JSON_PRETTY_PRINT);
}