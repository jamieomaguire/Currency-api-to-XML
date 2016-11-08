<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Array of currency codes
$currencyCodes = array(
    "CAD", "CHF", "CNY", "DKK", "EUR", "GBP", "HDK", "HUF",
    "INR", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "RUB",
    "SEK", "SGD", "THB", "TRY", "USD", "ZAR"
);

// Set access key and parameters
$access_key = '46814163859edfcfba1033626231638c';

$live = 'live';
$list = 'list';

// Store data
$json = file_get_contents('http://www.apilayer.net/api/'.$live.'?access_key='.$access_key.'&format=1');

// Decode Json into an object
$object = json_decode($json, true);

// Array of currency ISO codes and names
// $currenciesList = $object['currencies'];
$rates = $object['quotes'];
$timestamp = $object['timestamp'];


// Iterate over object and echo currency codes that match the codes in array
foreach ($rates as $key => $value) {
    foreach ($currencyCodes as $ccode) {
        $keyCode = substr($key, -3);
        if ($keyCode == $ccode) {
            echo $keyCode . ' : ' . $value . ' timestamp: ' . $timestamp . '<br />';
        }
    }
}
