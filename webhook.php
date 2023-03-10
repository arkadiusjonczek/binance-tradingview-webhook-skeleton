<?php

// tradingview server ip's
$allowedIps = [
  '52.89.214.238',
  '34.212.75.30',
  '54.218.53.128',
  '52.32.178.7'
];

// only allow requests from tradingview servers
if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIps)) {
  die('not allowed');
}

$input = file_get_contents('php://input');

$inputArray = explode(',', $input);
$symbol     = $inputArray[0];
$side       = $inputArray[1];
$quantity   = 0.001; // increase if you want but first test your strategy (risk management)

$data = [
  'symbol'     => $symbol,
  'side'       => $side,
  'type'       => 'MARKET',
  'quantity'   => $quantity,
  'recvWindow' => '5000',
  'timestamp'  => floor(microtime(true) * 1000)
];

$totalParams = \http_build_query($data);

// add your binance api and secret key with permissions to trade
$apiKey    = '';
$secretKey = '';

// build signature and add to request body
// see https://binance-docs.github.io/apidocs/spot/en/#endpoint-security-type
$signature = hash_hmac('sha256', $totalParams, $secretKey);
$data['signature'] = $signature;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.binance.com/api/v3/order');
curl_setopt($ch, CURLOPT_HTTPHEADER, [sprintf('X-MBX-APIKEY: %s', $apiKey)]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, \http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);

$httpResponseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

// do some more stuff if you want e.g. save order values in db for data analysis
// but be beware of doing it after requests to reduce latency (slippage)
