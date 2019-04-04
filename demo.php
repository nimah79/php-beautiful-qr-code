<?php

/**
 * Beautiful QR Code generator
 * By NimaH79
 * NimaH79.ir.
 */
require __DIR__.'/phpqrcode/beautiful-qr-code.php';

$text = '@Radio_Nima';
$backgroundColor = 'ffffff';
$primaryColor = '303030';
$secondaryColor = 'ca012f';
$scale = 3; // Higher scale, higher quality and slower speed
$type = 'png'; // PNG is better, trust me :)

header('Content-type: image/png');

echo generateBeautifulQRCode($text, $backgroundColor, $primaryColor, $secondaryColor, $scale, $type);
