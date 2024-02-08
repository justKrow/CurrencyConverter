<?php

function respondPostResquest($timestamp)
{
    if ($_GET["format"] == "xml") {
        respondPostXmlRequest($timestamp);
    } else if ($_GET["format"] == "json") {
        respondPostJsonRequest($timestamp);
    }
}

function respondPostXmlRequest($timestamp)
{
    $currency = searchCurrency($_GET["cur"], "../src/data/rates.xml");
    header("Content-Type: application/xml");
    $dom = new DOMDocument();

    $action = $dom->createElement("action");
    $action->setAttribute("type", $_GET["action"]);
    $dom->appendChild($action);

    $at = $dom->createElement("at", strval($timestamp));
    $action->appendChild($at);

    $rate = $dom->createElement("rate", strval($currency["rate"]));
    $action->appendChild($rate);

    $curr = $dom->createElement("curr");
    $action->appendChild($curr);

    $code = $dom->createElement("code", strval($currency->code));
    $curr->appendChild($code);
    $name = $dom->createElement("name", strval($currency->curr));
    $curr->appendChild($name);
    $loc = $dom->createElement("loc", strval($currency->loc));
    $curr->appendChild($loc);

    echo $dom->saveXML();
}

function respondPostJsonRequest($timestamp)
{
    $currency = searchCurrency($_GET["cur"], "../src/data/rates.xml");
    header("Content-Type: application/json");
    $response = [
        "action" => [
            "type" => $_GET["action"],
            "at" => $timestamp,
            "rate" => strval($currency["rate"]),
            "curr" => [
                "code" => strval($currency->code),
                "name" => strval($currency->curr),
                "loc" => strval($currency->loc)
            ]
        ]
    ];

    echo json_encode($response, JSON_PRETTY_PRINT);
}
