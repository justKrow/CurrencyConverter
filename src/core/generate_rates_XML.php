<?php

function writeXmlRates()
{
    $rates_data = callAPI($end_point = "v3/latest?apikey=", $attribute = "&base_currency=GBP");
    $currencies_data = callAPI($end_point = "v3/currencies?apikey=", $attribute = null);

    $transformed_data = transformData($rates_data, $currencies_data);
    createXML($transformed_data, $rates_data["meta"]["last_updated_at"]);
}

function transformData($rates_data, $currencies_data)
{
    $transformed_data = [];
    foreach ($rates_data["data"] as $code => $rate_info) {
        $currency_details = [
            "code" => $code,
            "rate" => $rate_info["value"],
            "curr" => $currencies_data["data"][$code]["name"] ?? "",
            "loc" => isset($currencies_data["data"][$code]["countries"]) ? implode(", ", $currencies_data["data"][$code]["countries"]) : ""
        ];
        $transformed_data[] = $currency_details;
    }
    return $transformed_data;
}

function createXML($transformed_data, $meta)
{
    $dom = new DOMDocument("1.0", "UTF-8");
    $root = $dom->createElement("rates");
    $dom->appendChild($root);
    $root->setAttribute("ts", $meta);
    $root->setAttribute("base", "GBP");

    foreach ($transformed_data as $currency) {
        $currency_element = $dom->createElement("Currency");
        $currency_element->appendChild($dom->createElement("code", $currency["code"]));
        $currency_element->setAttribute("rate", $currency["rate"]);
        if (in_array($currency["code"], $GLOBALS['live_countries'])) {
            $currency_element->setAttribute('live', 1);
        } else {
            $currency_element->setAttribute('live', 0);
        }
        $currency_element->appendChild($dom->createElement("curr", $currency["curr"]));
        $currency_element->appendChild($dom->createElement("loc", $currency["loc"]));

        $root->appendChild($currency_element);
    }

    $dom->save(RATES_XML_FILE);
}

