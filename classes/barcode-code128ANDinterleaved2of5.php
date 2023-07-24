<?php
class Code128Creator
{
    private string $initSeq = '11010000100';  // Initialization sequence for the barcode Code 128A
    private string $termSeq = '1100011101011';   // Termination sequence for the barcode Code 128A
	
	private $imgHeight;

    // Barcode sequence pattern + value for Code128
	private $barcodeSeq = [
		"00" => ["SP", "SP", "00", "11011001100"],
		"01" => ["!", "!", "01", "11001101100"],
		"02" => ["\"", "\"", "02", "11001100110"],
		"03" => ["#", "#", "03", "10010011000"],
		"04" => ["$", "$", "04", "10010001100"],
		"05" => ["%", "%", "05", "10001001100"],
		"06" => ["&", "&", "06", "10011001000"],
		"07" => ["'", "'", "07", "10011000100"],
		"08" => ["(", "(", "08", "10001100100"],
		"09" => [")", ")", "09", "11001001000"],
		"10" => ["*", "*", "10", "11001000100"],
		"11" => ["+", "+", "11", "11000100100"],
		"12" => [",", ",", "12", "10110011100"],
		"13" => ["-", "-", "13", "10011011100"],
		"14" => [".", ".", "14", "10011001110"],
		"15" => ["/", "/", "15", "10111001100"],
		"16" => ["0", "0", "16", "10011101100"],
		"17" => ["1", "1", "17", "10011100110"],
		"18" => ["2", "2", "18", "11001110010"],
		"19" => ["3", "3", "19", "11001011100"],
		"20" => ["4", "4", "20", "11001001110"],
		"21" => ["5", "5", "21", "11011100100"],
		"22" => ["6", "6", "22", "11001110100"],
		"23" => ["7", "7", "23", "11101101110"],
		"24" => ["8", "8", "24", "11101001100"],
		"25" => ["9", "9", "25", "11100101100"],
		"26" => [":", ":", "26", "11100100110"],
		"27" => [";", ";", "27", "11101100100"],
		"28" => ["<", "<", "28", "11100110100"],
		"29" => ["=", "=", "29", "11100110010"],
		"30" => [">", ">", "30", "11011011000"],
		"31" => ["?", "?", "31", "11011000110"],
		"32" => ["@", "@", "32", "11000110110"],
		"33" => ["A", "A", "33", "10100011000"],
		"34" => ["B", "B", "34", "10001011000"],
		"35" => ["C", "C", "35", "10001000110"],
		"36" => ["D", "D", "36", "10110001000"],
		"37" => ["E", "E", "37", "10001101000"],
		"38" => ["F", "F", "38", "10001100010"],
		"39" => ["G", "G", "39", "11010001000"],
		"40" => ["H", "H", "40", "11000101000"],
		"41" => ["I", "I", "41", "11000100010"],
		"42" => ["J", "J", "42", "10110111000"],
		"43" => ["K", "K", "43", "10110001110"],
		"44" => ["L", "L", "44", "10001101110"],
		"45" => ["M", "M", "45", "10111011000"],
		"46" => ["N", "N", "46", "10111000110"],
		"47" => ["O", "O", "47", "10001110110"],
		"48" => ["P", "P", "48", "11101110110"],
		"49" => ["Q", "Q", "49", "11010001110"],
		"50" => ["R", "R", "50", "11000101110"],
		"51" => ["S", "S", "51", "11011101000"],
		"52" => ["T", "T", "52", "11011100010"],
		"53" => ["U", "U", "53", "11011101110"],
		"54" => ["V", "V", "54", "11101011000"],
		"55" => ["W", "W", "55", "11101000110"],
		"56" => ["X", "X", "56", "11100010110"],
		"57" => ["Y", "Y", "57", "11101101000"],
		"58" => ["Z", "Z", "58", "11101100010"],
		"59" => ["[", "[", "59", "11100011010"],
		"60" => ["\\", "\\", "60", "11101111010"],
		"61" => ["]", "]", "61", "11001000010"],
		"62" => ["SPACE", "SPACE", "62", "11110001010"],
		"63" => ["_", "_", "63", "10100110000"],
		"64" => ["NUL", "`", "64", "10100001100"],
		"65" => ["SOH", "a", "65", "10010110000"],
		"66" => ["STX", "b", "66", "10010000110"],
		"67" => ["ETX", "c", "67", "10000101100"],
		"68" => ["EOT", "d", "68", "10000100110"],
		"69" => ["ENQ", "e", "69", "10110010000"],
		"70" => ["ACK", "f", "70", "10110000100"],
		"71" => ["BEL", "g", "71", "10011010000"],
		"72" => ["BS", "h", "72", "10011000010"],
		"73" => ["HT", "I", "73", "10000110100"],
		"74" => ["LF", "j", "74", "10000110010"],
		"75" => ["VT", "k", "75", "11000010010"],
		"76" => ["FF", "l", "76", "11001010000"],
		"77" => ["CR", "m", "77", "11110111010"],
		"78" => ["SO", "n", "78", "11000010100"],
		"79" => ["SI", "o", "79", "10001111010"],
		"80" => ["DLE", "p", "80", "10100111100"],
		"81" => ["DC1", "q", "81", "10010111100"],
		"82" => ["DC2", "r", "82", "10010011110"],
		"83" => ["DC3", "s", "83", "10111100100"],
		"84" => ["DC4", "t", "84", "10011110100"],
		"85" => ["NAK", "u", "85", "10011110010"],
		"86" => ["SYN", "v", "86", "11110100100"],
		"87" => ["ETB", "w", "87", "11110010100"],
		"88" => ["CAN", "x", "88", "11110010010"],
		"89" => ["EM", "y", "89", "11011011110"],
		"90" => ["SUB", "z", "90", "11011110110"],
		"91" => ["ESC", "{", "91", "11110110110"],
		"92" => ["FS", "|", "92", "10101111000"],
		"93" => ["GS", "}", "93", "10100011110"],
		"94" => ["RS", "~", "94", "10001011110"],
		"95" => ["US", "DEL", "95", "10111101000"],
		"96" => ["FNC 3", "FNC 3", "96", "10111100010"],
		"97" => ["FNC 2", "FNC 2", "97", "11110101000"],
		"98" => ["SHIFT", "SHIFT", "98", "11110100010"],
		"99" => ["CODE C", "CODE C", "99", "10111011110"],
		"100" => ["CODE B", "FNC 4", "CODE B", "10111101110"],
		"101" => ["FNC 4", "CODE A", "CODE A", "11101011110"],
		"102" => ["FNC 1", "FNC 1", "FNC 1", "11110101110"],
		"103" => ["Start Code A", "Start Code A", "Start A", "11010000100"],
		"104" => ["Start Code B", "Start Code B", "Start B", "11010010000"],
		"105" => ["Start Code C", "Start Code C", "Start C", "11010011100"],
		"106" => ["Stop", "Stop", "Stop", "1100011101011"]
	];

	public function __construct (int $imageHeight = 80) 
	{
		$this->imgHeight = $imageHeight;
	}
	
	
    /**
     * Looks up values of the barcodeSeq Array at a specific index
     *
     * @param string $value - The value that should be looked for
	 * @param int $index - The index at which should be looked at
     * @return int - Checksum of the input string
     */
	private function lookup_array($value, $index) {
        foreach ($this->barcodeSeq as $key => $barcode) {
            if (isset($barcode[$index]) && $barcode[$index] === $value) {
                return $key;
            }
        }
        return null;
    }

    /**
     * Calculates Interleaved 2/5 checksum of the number
     *
     * @param string $value - The string of digits for which the checksum should be calculated
     * @return int - Checksum of the input string
     */
    private function get_itf_checksum(string $value) : int
    {
        $checksum = 0;
        $valueLength = strlen($value);
        for ($i = 0; $i < $valueLength; $i++) {
            $checksum += $value[$i] * (($i % 2 == 0) ? 3 : 1);
        }

        return $checksum % 10 === 0 ? 0 : 10 - ($checksum % 10);
    }
	
    /**
     * Calculates Code128 checksum of the number
     *
     * @param string $value - The string of digits for which the checksum should be calculated
     * @return string - Checksum of the input string
     */
    private function get_code128_checksum(string $value) : string
    {
        $checksum = 0;
        for ($i = 1; $i <= strlen($value); $i++) {
			$index = $this->lookup_array($value[$i-1], 1);
            $checksum += $index*$i;
        }

        $checksum %= 103;
		return $this->barcodeSeq[$checksum][3];
    }

    /**
     * Calculates the Binary Sequence
     *
     * @param string $value - The string of digits for which the sequence should be calculated
     * @return string - Binary Sequence
     */
	private function get_sequence(string $value) : string
    {
		$sequence = "";
		$value .= $this->get_itf_checksum($value);
		
        for ($i = 0; $i < strlen($value); $i++) {
			$index = $this->lookup_array($value[$i], 1);
            $sequence .= $this->barcodeSeq[$index][3];
        }
		
		echo $sequence;
		$sequence .= $this->get_code128_checksum($value).$this->termSeq;
		
		return $this->initSeq.$sequence;
    }
	
    /**
     * Creates the Barcode based on the Binary Sequence
     *
     * @param string $value - The string of digits for which the sequence should be calculated
     * @return string - Binary Sequence
     */
	public function create_barcode(string $value) : void
	{
		$barcode = $this->get_sequence($value);
		$barcodeLength = strlen($barcode);
		
		$imgWidth = $barcodeLength*2; // Assigning the barcode length directly to the image width

		$padding = 0.05 * $imgWidth; // Padding on both sides
		$imgWidth += 2 * $padding; // Adjust the total width of the image including padding

		$img = imagecreate($imgWidth, $this->imgHeight);

		$black = imagecolorallocate($img, 0, 0, 0);  // Color for the barcode lines
		$white = imagecolorallocate($img, 255, 255, 255);  // Background color
		
		imagefilledrectangle($img, 0, 0, $imgWidth, $this->imgHeight, $white);

		$xPos = $padding;
		for ($i = 0; $i < $barcodeLength; $i++) {

			$lineColor = $barcode[$i] == '1' ? $black : $white; 

			imagefilledrectangle($img, $xPos, 0, $xPos+2, $this->imgHeight, $lineColor); 
			$xPos+=2; 
		}
		//header("Content-Type: image/png");  // Inform the browser that we're sending an image
		//imagepng($img);  // Output the image
		//imagedestroy($img); // Free up memory
	}
}
?>
