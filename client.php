<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "lib/nusoap.php";
 
$client = new nusoap_client("currency.wsdl", true);
$error  = $client->getError();
 
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}
$foreign='on';
 $key = 0.0808297;
 $amount = 100;
$charge=$client->call("service.getPrice",array("rate"=>$key,"amount"=>$amount,"foreign"=>$foreign));
 
if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
        print_r($charge);
    echo "</pre>";
} else {
    $error = $client->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    } else {
        echo "<h2>Main</h2>";
        echo $charge;
    }
}
 
// show soap request and response
echo "<h2>Request</h2>";
echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
echo "<h2>Response</h2>";
echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>";