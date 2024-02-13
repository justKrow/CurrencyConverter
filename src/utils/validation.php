<?php

// Function to display error response
function displayError($error_code, $format)
{
    // Check the format and call appropriate function
    if ($format == "xml") {
        displayXmlError($error_code);
    } else if ($format == "json") {
        displayJsonError($error_code);
    }
}

// Function to display error response in XML format
function displayXmlError($error_code)
{
    // Set header for XML content
    header('Content-Type: application/xml');

    // Create a new DOMDocument
    $dom = new DOMDocument();
    $response = $dom->createElement('response');
    $dom->appendChild($response);

    // Create 'error' element
    $error = $dom->createElement('error');
    $response->appendChild($error);

    // Append 'code' and 'message' elements
    $error->appendChild($dom->createElement('code', $error_code));
    $error->appendChild($dom->createElement('message', ERROR_CODES[$error_code]));

    // Output XML
    echo $dom->saveXML();
}

// Function to display error response in JSON format
function displayJsonError($error_code)
{
    // Set header for JSON content
    header('Content-Type: application/json');

    // Prepare error array
    $error_array = [
        'error' => $error_code,
        'message' => ERROR_CODES[$error_code],
    ];

    // Output JSON with pretty printing
    echo json_encode($error_array, JSON_PRETTY_PRINT);
}
