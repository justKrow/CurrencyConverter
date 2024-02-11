<?php

function handleDelRequest($currency_code, $xml_file_path)
{
    try {
        if (file_exists('../src/data/live_countries.json')) {
            $live_countries = json_decode(file_get_contents('../src/data/live_countries.json'), true);
        } else {
            $live_countries = [];
        }

        if ($currency_code === "GBP") {
            displayError($error_code = 2400, $format = $_GET["format"]);
            exit();
        }

        if (in_array($currency_code, $live_countries) == false) {
            displayError($error_code = 2200, $format = $_GET["format"]);
            exit();
        }

        $dom = new DOMDocument();
        $dom->load($xml_file_path);
        $xpath = new DOMXPath($dom);

        // Check if the currency already exists
        $query = sprintf("//Currency[code='%s']", $currency_code);
        $entries = $xpath->query($query);

        if ($entries->length > 0) {
            // Currency exists, update 'live' attribute
            foreach ($entries as $entry) {
                $entry->setAttribute('live', 0);
            }
            $live_countries = array_filter($live_countries, static function ($element) use ($currency_code) {
                return $element !== $currency_code;
            });
            file_put_contents('../src/data/live_countries.json', json_encode($live_countries, JSON_PRETTY_PRINT));
        } else {
            displayError($error_code = 1200, $format = $_GET["format"]);
        }
        $dom->save($xml_file_path);
        return true;
    } catch (Exception $e) {
        displayError($error_code = 2500, $format = $_GET["format"]);
        exit();
    }
}