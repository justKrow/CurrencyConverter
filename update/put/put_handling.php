<?php

function handlePutRequest()
{
    try {
        if ($_GET["cur"] === "GBP") {
            displayError($error_code = 2400, $format = $_GET["format"]);
            exit();
        }

        $currency = searchCurrency($_GET["cur"], "../src/data/rates.xml");

        if ($currency["live"] == 0) {
            displayError($error_code = 2200, $format = $_GET["format"]);
            exit();
        }

        $currency_history = [
            "at" => formatDate(date("Y-m-d H:i:s")),
            "old_rate" => strval($currency["rate"]),
            "code" => strval($currency->code),
            "name" => strval($currency->curr),
            "loc" => strval($currency->loc)
        ];
        if (isRateOutDated($_GET["cur"], date("Y-m-d H:i:s"), "../src/data/rates.xml", 2)) {
            writeXmlRates("../src/data/rates.xml");
            $currency = searchCurrency($_GET["cur"], "../src/data/rates.xml");
            $currency_history[] = ["status" => "outdated", "rate" => strval($currency["rate"])];
        } else {
            $currency_history[] = ["status" => "updated"];
        }
        return $currency_history;
    } catch (Exception $e) {
        displayError($error_code = 2500, $format = $_GET["format"]);
        exit();
    }
}