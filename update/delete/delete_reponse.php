<?php

function responseDeleteRequest()
{
    if ($_GET["format"] == "xml") {
        responseDeleteXmlRequest();
    } else if ($_GET["format"] == "json") {
        responseDeleteRequest();
    }
}

function responseDeleteXmlRequest()
{
    header("Content-Type: application/xml");
    $dom = new DOMDocument();

    $action = $dom->createElement("action");
    $action->setAttribute("type", $_GET["action"]);
}

function responseDeleteJsonRequest()
{
}