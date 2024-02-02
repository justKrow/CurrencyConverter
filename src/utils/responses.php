<?php

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
    $amnt = $dom->createElement("amnt", $curreny_exchange_details["from"]["amnt"]);
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