<?php

function displayConvertResult($curreny_exchange_details, $format)
{
    if ($format == "xml") {
        displayConvertXmlResult($curreny_exchange_details);
    }
    if ($format == "json") {
        displayConvertJsonResult($curreny_exchange_details);
    }
}

function displayConvertXmlResult($curreny_exchange_details)
{
    header("Content-Type: application/xml");
    $dom = new DOMDocument();

    $conv = $dom->createElement("conv");
    $dom->appendChild($conv);
    $at = $dom->createElement("at", $curreny_exchange_details["at"]);
    $conv->appendChild($at);
    $rate = $dom->createElement("rate", $curreny_exchange_details["rate"]);
    $conv->appendChild($rate);

    $from = $dom->createElement("from");
    $conv->appendChild($from);
    $code = $dom->createElement("code", $curreny_exchange_details["from"]["code"]);
    $from->appendChild($code);
    $curr = $dom->createElement("curr", $curreny_exchange_details["from"]["curr"]);
    $from->appendChild($curr);
    $loc = $dom->createElement("loc", $curreny_exchange_details["from"]["loc"]);
    $from->appendChild($loc);
    $amnt = $dom->createElement("amnt", strval($curreny_exchange_details["from"]["amnt"]));
    $from->appendChild($amnt);

    $to = $dom->createElement("to");
    $conv->appendChild($to);
    $code = $dom->createElement("code", $curreny_exchange_details["to"]["code"]);
    $to->appendChild($code);
    $curr = $dom->createElement("curr", $curreny_exchange_details["to"]["curr"]);
    $to->appendChild($curr);
    $loc = $dom->createElement("loc", $curreny_exchange_details["to"]["loc"]);
    $to->appendChild($loc);
    $amnt = $dom->createElement("amnt", $curreny_exchange_details["to"]["amnt"]);
    $to->appendChild($amnt);

    echo $dom->saveXML();
}

function displayConvertJsonResult($curreny_exchange_details)
{
    header("Content-type: application/json");
    $response = [
        "at" => $curreny_exchange_details["at"],
        "rate" => $curreny_exchange_details["rate"],
        "from" => [
            "code" => $curreny_exchange_details["from"]["code"],
            "curr" => $curreny_exchange_details["from"]["curr"],
            "loc" => $curreny_exchange_details["from"]["loc"],
            "amnt" => $curreny_exchange_details["from"]["amnt"],
        ],
        "to" => [
            "code" => $curreny_exchange_details["to"]["code"],
            "curr" => $curreny_exchange_details["to"]["curr"],
            "loc" => $curreny_exchange_details["to"]["loc"],
            "amnt" => $curreny_exchange_details["to"]["amnt"],
        ]
    ];

    echo json_encode($response);
}