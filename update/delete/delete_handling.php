<?php

function handleDelRequest($xml_file_path)
{
    try {
        if (file_exists('../src/data/live_countries.json')) {
            $live_countries = json_decode(file_get_contents('../src/data/live_countries.json'), true);
        } else {
            $live_countries = [];
        }

        if ($_GET["cur"] === "GBP") {
            displayError($error_code = 2400, $format = $_GET["format"]);
            exit();
        }

        if (in_array($_GET["cur"], $live_countries) == false) {
            displayError($error_code = 2200, $format = $_GET["format"]);
            exit();
        }

        $dom = new DOMDocument();
        $dom->load($xml_file_path);
        $xpath = new DOMXPath($dom);

        // Check if the currency already exists
        $query = sprintf("//Currency[code='%s']", $_GET["cur"]);
        $entries = $xpath->query($query);

        if ($entries->length > 0) {
            // Currency exists, update 'live' attribute
            foreach ($entries as $entry) {
                $entry->setAttribute('live', 0);
            }
            if (($key = array_search($_GET["cur"], $live_countries)) !== false) {
                unset($live_countries[$key]);
            }
            $live_countries = array_values($live_countries);
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