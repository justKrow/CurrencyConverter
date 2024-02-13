<?php

// Function to handle DELETE request
function handleDelRequest($xml_file_path)
{
    try {
        // Load live countries data
        if (file_exists('../src/data/live_countries.json')) {
            $live_countries = json_decode(file_get_contents('../src/data/live_countries.json'), true);
        } else {
            $live_countries = [];
        }

        // Check if currency is GBP, if so, display error and exit
        if ($_GET["cur"] === "GBP") {
            displayError($error_code = 2400, $format = $_GET["format"]);
            exit();
        }

        // Check if currency is a live currency, if not, display error and exit
        if (in_array($_GET["cur"], $live_countries) == false) {
            displayError($error_code = 2200, $format = $_GET["format"]);
            exit();
        }

        // Load XML file and create XPath object
        $dom = new DOMDocument();
        $dom->load($xml_file_path);
        $xpath = new DOMXPath($dom);

        // Check if the currency already exists in the XML data
        $query = sprintf("//Currency[code='%s']", $_GET["cur"]);
        $entries = $xpath->query($query);

        // If currency exists, update 'live' attribute and remove from live countries list
        if ($entries->length > 0) {
            foreach ($entries as $entry) {
                $entry->setAttribute('live', 0);
            }
            if (($key = array_search($_GET["cur"], $live_countries)) !== false) {
                unset($live_countries[$key]);
            }
            $live_countries = array_values($live_countries);
            file_put_contents('../src/data/live_countries.json', json_encode($live_countries, JSON_PRETTY_PRINT));
        } else {
            // If currency doesn't exist, display error and exit
            displayError($error_code = 1200, $format = $_GET["format"]);
        }

        // Save changes to XML file
        $dom->save($xml_file_path);

        // Return true to indicate successful handling of request
        return true;
    } catch (Exception $e) {
        // If an exception occurs, display error and exit
        displayError($error_code = 2500, $format = $_GET["format"]);
        exit();
    }
}
