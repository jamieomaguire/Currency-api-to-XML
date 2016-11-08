<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function generateRatesXML() {

    // Array of currency codes
    $currencyCodes = array(
        "CAD", "CHF", "CNY", "DKK", "EUR", "GBP", "HDK", "HUF",
        "INR", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "RUB",
        "SEK", "SGD", "THB", "TRY", "USD", "ZAR"
    );

    // Set access key and parameters
    $access_key = '';

    $live = 'live';

    // Store rates data
    $json = file_get_contents('http://www.apilayer.net/api/'.$live.'?access_key='.$access_key.'&format=1');

    // Decode Json into an object
    $object = json_decode($json, true);

    // Array of currency ISO codes and names
    $rates = $object['quotes'];
    $ts = $object['timestamp'];

    // XML variable
    $xml = new SimpleXMLElement('<rates />');


    // Iterate over object and echo currency codes that match the codes in array
    foreach ($rates as $key => $value) {
        foreach ($currencyCodes as $ccode) {
            $keyCode = substr($key, -3);
            if ($keyCode == $ccode) {
                $rate = $xml->addChild('rate');
                $rate->addAttribute('code', $keyCode);
                $rate->addAttribute('value', $value);
                $rate->addAttribute('ts', $ts);
            }
        }
    }

    $xml->asXML("./rates.xml");
}

function generateCountriesXML() {

    // Array of currency codes
    $currencyCodes = array(
        "CAD", "CHF", "CNY", "DKK", "EUR", "GBP", "HDK", "HUF",
        "INR", "JPY", "MXN", "MYR", "NOK", "NZD", "PHP", "RUB",
        "SEK", "SGD", "THB", "TRY", "USD", "ZAR"
    );

    // Set access key and parameters
    $access_key = '';
    $list = 'list';


    $country_uri = 'https://restcountries.eu/rest/v1/all';

    $request = file_get_contents($country_uri);

    $data = json_decode($request, true);

    // Store currency names data
    $jsonCList = file_get_contents('http://www.apilayer.net/api/'.$list.'?access_key='.$access_key.'&format=1');

    $cListObject = json_decode($jsonCList, true);

    $cListNames = $cListObject['currencies'];

    // echo '<pre>';
    // var_dump($cListNames);
    // echo '</pre>';

    // XML variable
    $xmlCountries = new SimpleXMLElement('<currencies />');

    // If the country currency codes match the hard coded array, add to xml
    foreach ($currencyCodes as $ccode) {
        $currency = $xmlCountries->addChild('currency');
        $currency->addChild('ccode', $ccode);

        foreach ($data as $country) {
           $currencies = implode(', ', $country['currencies']);
           $cntry = $country['name'];

           if ($currencies == $ccode) {
               foreach($cListNames as $key => $value){
                   if ($currencies == $key) {
                       if(!isset($currency->cname)) {
                           $currency->addChild('cname', $value);
                       }
                   }
               }
               $currency->addChild('cntry', $cntry);
           }
        }
    }
    $xmlCountries->asXML("./countries.xml");
}

// generateRatesXML()
generateCountriesXML();
