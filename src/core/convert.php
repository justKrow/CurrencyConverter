<?php

function calculate()
{
    $xml = simplexml_load_file("src/data/rates.xml");
    $from_currency = searchCurrency(strtoupper($_GET["from"]), "src/data/rates.xml");
    $to_currency = searchCurrency(strtoupper($_GET["to"]), "src/data/rates.xml");

    if (isRateOutDated($xml["ts"], date("Y-m-d H:i:s"), "src/data/rates.xml")) {
        writeXmlRates("src/data/rates.xml");
    }


    $from_currency_details = [
        "code" => strval($from_currency->code),
        "rate" => (float) ($from_currency["rate"]),
        "curr" => strval($from_currency->curr),
        "loc" => strval($from_currency->loc),
        "amnt" => (float) $_GET["amnt"],
    ];

    $to_currency_details = [
        "code" => strval($to_currency->code),
        "rate" => (float) ($to_currency["rate"]),
        "curr" => strval($to_currency->curr),
        "loc" => strval($to_currency->loc),
    ];

    $exchange_rate = $to_currency_details["rate"] / $from_currency_details["rate"];
    $to_currency_details["amnt"] = $exchange_rate * $from_currency_details["amnt"];

    $convert_details = [
        "at" => formatDate($xml["ts"]),
        "rate" => $exchange_rate,
        "from" => $from_currency_details,
        "to" => $to_currency_details,
    ];

    return $convert_details;
}