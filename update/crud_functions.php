<?php

function handlePostRequest()
{
    $currency = isset($_GET['cur']) ? $_GET['cur'] : "No currency specified";
    $action = isset($_GET['action']) ? $_GET['action'] : "No action specified";

    // Add your logic here to handle the POST request based on these parameters

    echo "Currency: $currency, Action: $action";
}

function handlePutRequest()
{

}

function handleDeleteRequest()
{

}