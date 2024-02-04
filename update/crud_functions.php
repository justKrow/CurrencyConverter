<?php


function handlePostRequest($currencyCode, $liveStatus, $xmlFilePath)
{
    $dom = new DOMDocument();
    $dom->load($xmlFilePath);
    $xpath = new DOMXPath($dom);

    // Check if the currency already exists
    $query = sprintf("//Currency[code='%s']", $currencyCode);
    $entries = $xpath->query($query);

    if ($entries->length > 0) {
        // Currency exists, update 'live' attribute
        foreach ($entries as $entry) {
            $entry->setAttribute('live', $liveStatus);
        }
    } else {
        // Currency doesn't exist, create a new element
        $currency = $dom->createElement("Currency");
        $currency->setAttribute('rate', '1'); // Placeholder rate, you should set this properly
        $currency->setAttribute('live', $liveStatus);
        $code = $dom->createElement("code", $currencyCode);
        $currency->appendChild($code);

        // Add other necessary elements like "curr", "loc" etc. here

        $dom->documentElement->appendChild($currency);
    }

    // Save the changes to the file
    if ($dom->save($xmlFilePath)) {
        print "Saved";
    }
    ;
}


function handlePutRequest()
{

}

function handleDeleteRequest()
{

}