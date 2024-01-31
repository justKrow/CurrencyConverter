<?php
function callAPI($end_point)
{
    $base_url = "https://api.currencyapi.com/";
    $url = $base_url . $end_point;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function generateXMLFile($rates_data, $currencies_data)
{
    $dom = new DOMDocument("1.0", "UTF-8");
    $root = $dom->createElement("rates");
    $dom->appendChild($root);
    $root->setAttribute("last_updated_at", $rates_data["meta"]["last_updated_at"]);
    $root->setAttribute("base", "GBP");

    foreach ($rates_data["data"] as $code => $rate_info) {
        $currency_element = $dom->createElement("currency");
        $currency_element->appendChild($dom->createElement("code", $code));
        $currency_element->setAttribute("rate", $rate_info["value"]);

        if (isset($currencies_data["data"][$code])) {
            $details = $currencies_data["data"][$code];
            $countries_string = implode(", ", $details["countries"]);
            $currency_element->appendChild($dom->createElement("curr", $details["name"]));
            $currency_element->appendChild($dom->createElement("loc", $countries_string));
        }

        $root->appendChild($currency_element);
    }

    // Save the XML to a file or return as a string
    $dom->save("src/data/output.xml"); // to save
    // return $dom->saveXML(); // to return as string
}


