# PHP 2/5 Interleaved Barcode Generator with Checksum

This PHP class generates 2/5 interleaved barcodes as images. It has been refactored and expanded from the original repository to include a checksum and other improvements. The library is compatible with PHP 7 and later versions.

## How to Use

Here's a sample usage:

```php
<?php
    include('classes/barcode-interleaved2of5.php');
    $barcodeCreator = new BarcodeCreator();
    $barcodeCreator->create_barcode('1234567890');
?>
```

The **`create_barcode()`** method will output an image of a 2/5 interleaved barcode directly in the browser for the input string of digits. The image's height can be adjusted by passing a value to the **`BarcodeCreator`** constructor, like so:

```php
<?php
    include('classes/barcode-interleaved2of5.php');
    $barcodeCreator = new BarcodeCreator(100); // Set the barcode image height to 100
    $barcodeCreator->create_barcode('1234567890');
?>
```

## Credits

This repository is a refactoring and expansion of https://github.com/fabioboris/php-interleaved-2-of-5.

**Original author:** Fabio Boris  
**Refactored and expanded by:** Nicolas Bachmaier
