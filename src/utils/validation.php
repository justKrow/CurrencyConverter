<?php

function displayError($error_code, $error_message, $format)
{
    if ($format == "xml") {
        displayXmlError($error_code, $error_message);
    } else if ($format == "json") {
        displayJsonError($error_code, $error_message);
    }
}
function displayXmlError($error_code, $error_message)
{
    header('Content-Type: application/xml');
    $dom = new DOMDocument();
    $response = $dom->createElement('response');
    $dom->appendChild($response);

    $error = $dom->createElement('error');
    $response->appendChild($error);

    $error->appendChild($dom->createElement('code', $error_code));
    $error->appendChild($dom->createElement('message', $error_message));

    echo $dom->saveXML();
}

function displayJsonError($error_code, $error_message)
{
    header('Content-Type: application/json');
    $error_array = [
        'error' => $error_code,
        'message' => $error_message
    ];
    echo json_encode($error_array);
}