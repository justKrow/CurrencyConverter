<?php

function displayError($error_code, $format)
{
    if ($format == "xml") {
        displayXmlError($error_code);
    } else if ($format == "json") {
        displayJsonError($error_code);
    }
}
function displayXmlError($error_code)
{
    header('Content-Type: application/xml');
    $dom = new DOMDocument();
    $response = $dom->createElement('response');
    $dom->appendChild($response);

    $error = $dom->createElement('error');
    $response->appendChild($error);

    $error->appendChild($dom->createElement('code', $error_code));
    $error->appendChild($dom->createElement('message', ERROR_CODES[$error_code]));

    echo $dom->saveXML();
}

function displayJsonError($error_code)
{
    header('Content-Type: application/json');
    $error_array = [
        'error' => $error_code,
        'message' => ERROR_CODES[$error_code],
    ];
    echo json_encode($error_array);
}