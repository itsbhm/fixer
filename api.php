<?php
$endpoint = 'latest';
$base = 'EUR';
$token = 'YOUR-ACCESS-TOKEN';

$url = "http://data.fixer.io/api/{$endpoint}?base={$base}&access_key={$token}"; // API URL
function getInfo($hostUrl)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $hostUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // change to 1 to verify cert
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    $currencyRateList = curl_exec($ch);

    return $currencyRateList;
}

$currency = json_decode(getInfo($url) , true);

// API Response Status
$status = ($currency['success']);

if ($status == true) {

    $success = true;
    $base = 'USD';
    $exchangeProvider = 'Fixer';
    $timestamp = $currency['timestamp'];
    $date = $currency['date'];

    // Currency values
    $inr = $currency['rates']['INR'] / $currency['rates'][$base]; // India
    $sgd = $currency['rates']['SGD'] / $currency['rates'][$base]; // Singapore
    $aed = $currency['rates']['AED'] / $currency['rates'][$base]; // United Arab Emirates
    $gbp = $currency['rates']['GBP'] / $currency['rates'][$base]; // United Kingdom

    $resp = json_encode(array(
        'success' => $success,
        'base' => $base,
        'exchangeProvider' => $exchangeProvider,
        'date' => $date,
        'timestamp' => $timestamp,

        'rates' => array(
            'INR' => $inr,
            'SGD' => $sgd,
            'AED' => $aed,
            'GBP' => $gbp
        )
    ));

    echo ($resp);

} else {

    $success = false;
    $info = "Unable to handle exchange request";

    $resp = json_encode(array(
        'success' => $success,
        'info' => $info
    ));

    echo ($resp);
}
