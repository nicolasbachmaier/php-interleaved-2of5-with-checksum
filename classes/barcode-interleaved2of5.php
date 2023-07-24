<?php
/**
 * Class BarcodeCreator
 *
 * This class generates 2/5 Interleaved barcodes with Checksum-Generation.
 * The barcode sequence is formed with a predefined pattern of thin (0) and thick (1) lines. 
 * The barcode can be output as an image in the browser.
 *
 * Author: Nicolas Bachmaier
 * Refactored from: https://github.com/fabioboris/php-interleaved-2-of-5
 */
class BarcodeCreator
{
    private string $initSeq = '0000';  // Initialization sequence for the barcode
    private string $termSeq = '100';   // Termination sequence for the barcode

    // Barcode sequence pattern for digits 0-9
    private $barcodeSeq = array(
        '00110', '10001', '01001', '11000', '00101', 
		'10100', '01100', '00011', '10010', '01010'
    );

    private int $imgHeight; // Height of the barcode image
    private float $thinLine = 2;  // Width of thin lines
    private float $thickLine = 6; // Width of thick lines
	
    /**
     * BarcodeCreator constructor.
     *
     * @param int $imageHeight - The height of the barcode image (default value: 50)
     */
	public function __construct(int $imageHeight = 50) {
		$this->imgHeight = $imageHeight;
		$scaleFactor = $imageHeight / 30;
		$this->thinLine *= $scaleFactor;
		$this->thickLine = $this->thinLine*2.5;
	}

    /**
     * Calculates checksum of the number
     *
     * @param string $value - The string of digits for which the checksum should be calculated
     * @return int - Checksum of the input string
     */
    private function get_checksum(string $value) : int
    {
        $checksum = 0;
        $valueLength = strlen($value);
        for ($i = 0; $i < $valueLength; $i++) {
            $checksum += $value[$i] * (($i % 2 == 0) ? 3 : 1);
        }

        return $checksum % 10 === 0 ? 0 : 10 - ($checksum % 10);
    }

    /**
     * Creates the 2/5 Interleaved Sequence necessary for the Barcode Generation
     *
     * Each digit of the input is replaced by a predefined sequence of 0s (thin lines) and 1s (thick lines).
     * A checksum digit is added to the end of the sequence, and the full sequence is padded with a start and end sequence.
     *
     * @param string $value - The input string of digits to be converted to a barcode sequence
     * @return string - The final barcode sequence
     */
    private function get_sequence(string $value) : string
    {
        $value .= $this->get_checksum($value);
        $valueLength = strlen($value);

        if ($valueLength % 2 != 0) {
            $value = '0' . $value;
        }

        $barcode = '';
        for ($i = 0; $i < $valueLength; $i += 2) {
            $seq1 = $this->barcodeSeq[$value[$i]];
            $seq2 = $this->barcodeSeq[$value[$i + 1]];

            for ($j = 0; $j < 5; $j++) {
                $barcode .= $seq1[$j] . $seq2[$j];
            }
        }

        return $this->initSeq . $barcode . $this->termSeq;
    }

    /**
     * Generates the barcode image and outputs it directly in the browser
     *
     * The barcode sequence is converted to an image where 0s represent thin lines and 1s represent thick lines.
     * The image is output in PNG format.
     *
     * @param string $value - The input string of digits to be converted to a barcode image
     */
    public function create_barcode(string $value) : void
    {
        $barcode = $this->get_sequence($value);
        $barcodeLength = strlen($barcode);
		
        $imgWidth = 0;
        for ($i = 0; $i < $barcodeLength; $i++) {
            $imgWidth += $barcode[$i] == '0' ? $this->thinLine : $this->thickLine;
        }
		
		$padding = 0.05 * $imgWidth; // Padding on both sides
		$imgWidth += 2 * $padding; // Adjust the total width of the image including padding


        $img = imagecreate($imgWidth, $this->imgHeight);

        $black = imagecolorallocate($img, 0, 0, 0);  // Color for the barcode lines
        $white = imagecolorallocate($img, 255, 255, 255);  // Background color
		
		imagefilledrectangle($img, 0, 0, $imgWidth, $this->imgHeight, $white);

        $xPos = $padding;
        for ($i = 0; $i < $barcodeLength; $i++) {

            $lineWeight = $barcode[$i] == '0' ? $this->thinLine : $this->thickLine;

            $xPos += $lineWeight;

            $lineColor = $i % 2 == 0 ? $black : $white;  // Alternating colors for the barcode lines

            imagefilledrectangle($img, $xPos - $lineWeight, 0, $xPos, $this->imgHeight, $lineColor);
        }
        header("Content-Type: image/png");  // Inform the browser that we're sending an image
        imagepng($img);  // Output the image
    }
}
?>
