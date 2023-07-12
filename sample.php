<?php
require_once('classes/barcode-interleaved2of5.php');

$barcodeCreator = new BarcodeCreator(50); // Initialize the BarcodeCreator Class with a Height of 50

$code = $_GET['code'] ?? ''; // Get the value of 'code' parameter from the GET request
$sanitizedCode = filter_var($code, FILTER_SANITIZE_STRING); // Sanitize the input

$barcodeCreator->create_barcode($sanitizedCode); // Create the Barcode
?>
