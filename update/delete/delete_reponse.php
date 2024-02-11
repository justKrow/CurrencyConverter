<?php

function responseDeleteRequest()
{
    if ($_GET["format"] == "xml") {
        responseDeleteXmlRequest();
    } else if ($_GET["format"] == "json") {
        responseDeleteJsonRequest();
    }
}

function responseDeleteXmlRequest()
{
    header("Content-Type: application/xml");
    $dom = new DOMDocument();

    $action = $dom->createElement("action");
    $action->setAttribute("type", $_GET["action"]);
    $dom->appendChild($action);

    $at = $dom->createElement("at", formatDate(date("Y-m-d H:i:s")));
    $action->appendChild($at);

    $code = $dom->createElement("code", strval($_GET["cur"]));
    $action->appendChild($code);

    echo $dom->saveXML();
}

function responseDeleteJsonRequest()
{
    header("Content-Type: application/json");
    $response = [
        "action" => [
            "type" => $_GET["action"],
            "at" => formatDate(date("Y-m-d H:i:s")),
            "code" => strval($_GET["cur"])
        ]
    ];
    echo json_encode($response, JSON_PRETTY_PRINT);
}