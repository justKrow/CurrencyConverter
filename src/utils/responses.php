<?php

// Function to display currency conversion result
function displayConvertResult($currency_exchange_details, $format)
{
    // Check the format and call appropriate function
    if ($format == "xml") {
        displayConvertXmlResult($currency_exchange_details);
    }
    if ($format == "json") {
        displayConvertJsonResult($currency_exchange_details);
    }
}

// Function to display currency conversion result in XML format
function displayConvertXmlResult($currency_exchange_details)
{
    // Set header for XML content
    header("Content-Type: application/xml");

    // Create a new DOMDocument
    $dom = new DOMDocument();

    // Create root element 'conv'
    $conv = $dom->createElement("conv");
    $dom->appendChild($conv);

    // Append 'at' element
    $at = $dom->createElement("at", $currency_exchange_details["at"]);
    $conv->appendChild($at);

    // Append 'rate' element
    $rate = $dom->createElement("rate", $currency_exchange_details["rate"]);
    $conv->appendChild($rate);

    // Append 'from' element
    $from = $dom->createElement("from");
    $conv->appendChild($from);

    // Append elements inside 'from'
    $from->appendChild($dom->createElement("code", $currency_exchange_details["from"]["code"]));
    $from->appendChild($dom->createElement("curr", $currency_exchange_details["from"]["curr"]));
    $from->appendChild($dom->createElement("loc", $currency_exchange_details["from"]["loc"]));
    $from->appendChild($dom->createElement("amnt", strval($currency_exchange_details["from"]["amnt"])));

    // Append 'to' element
    $to = $dom->createElement("to");
    $conv->appendChild($to);

    // Append elements inside 'to'
    $to->appendChild($dom->createElement("code", $currency_exchange_details["to"]["code"]));
    $to->appendChild($dom->createElement("curr", $currency_exchange_details["to"]["curr"]));
    $to->appendChild($dom->createElement("loc", $currency_exchange_details["to"]["loc"]));
    $to->appendChild($dom->createElement("amnt", $currency_exchange_details["to"]["amnt"]));

    // Output XML
    echo $dom->saveXML();
}

// Function to display currency conversion result in JSON format
function displayConvertJsonResult($currency_exchange_details)
{
    // Set header for JSON content
    header("Content-type: application/json");

    // Prepare response array
    $response = [
        "conv" => [
            "at" => $currency_exchange_details["at"],
            "rate" => $currency_exchange_details["rate"],
            "from" => [
                "code" => $currency_exchange_details["from"]["code"],
                "curr" => $currency_exchange_details["from"]["curr"],
                "loc" => $currency_exchange_details["from"]["loc"],
                "amnt" => $currency_exchange_details["from"]["amnt"],
            ],
            "to" => [
                "code" => $currency_exchange_details["to"]["code"],
                "curr" => $currency_exchange_details["to"]["curr"],
                "loc" => $currency_exchange_details["to"]["loc"],
                "amnt" => $currency_exchange_details["to"]["amnt"],
            ]
        ]
    ];

    // Output JSON with pretty printing
    echo json_encode($response, JSON_PRETTY_PRINT);
}