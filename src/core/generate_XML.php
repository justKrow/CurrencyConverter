<?php
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
    $root->setAttribute("last_updated_at", $meta["last_updated_at"]);
    $root->setAttribute("base", "GBP");

    foreach ($transformed_data as $currency) {
        $currency_element = $dom->createElement("Currency");
        $currency_element->appendChild($dom->createElement("code", $currency["code"]));
        $currency_element->setAttribute("rate", $currency["rate"]);
        $currency_element->appendChild($dom->createElement("curr", $currency["curr"]));
        $currency_element->appendChild($dom->createElement("loc", $currency["loc"]));

        $root->appendChild($currency_element);
    }

    return $dom->saveXML();
}