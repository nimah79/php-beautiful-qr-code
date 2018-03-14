<?php

/*
 * Beautiful QR Code generator
 * By NimaH79
 * NimaH79.ir
*/

require 'phpqrcode/qrlib.php';

$text = '@Radio_Nima';
$backgroundColor = 'ffffff';
$primaryColor = '303030';
$secondaryColor = 'ca012f';
$scale = 3; // Higher scale, higher quality and slower speed
$type = 'png'; // PNG is better, trust me :)

header('Content-type: image/png');

echo generateBeautifulQRCode($text, $backgroundColor, $primaryColor, $secondaryColor, $scale, $type);

function generateBeautifulQRCode($text, $backgroundColor, $primaryColor, $secondaryColor, $scale, $type) {
  $qr = QRcode::text($text);
  foreach($qr as $key => &$value) {
    $value = str_split($value);
  }
  $count = count($qr);
  $canvasSize = 500;
  $circleSize = ($canvasSize / ($count + 5));
  $imageX2 = imagecreatetruecolor($canvasSize * $scale, $canvasSize * $scale);
  $bg = hexColorAlloc($imageX2, $backgroundColor);
  $secondary = hexColorAlloc($imageX2, $secondaryColor);
  imagefill($imageX2, 0, 0, $bg);
  
  $col_ellipse = hexColorAlloc($imageX2, $primaryColor);
  
  for($i = 0; $i < $count; $i++) {
    if($i < 7) {
      for($j = 7; $j < $count - 7; $j++) {
        if($qr[$i][$j] == '1') {
          imagefilledellipse($imageX2, $circleSize * (3 + $j) * $scale, $circleSize * (3 + $i) * $scale, $circleSize * $scale, $circleSize * $scale, $col_ellipse);
        }
      }
    }
    elseif($i >= 7 && $i < $count - 7) {
      for($j = 0; $j < $count; $j++) {
        if($qr[$i][$j] == '1') {
          imagefilledellipse($imageX2, $circleSize * (3 + $j) * $scale, $circleSize * (3 + $i) * $scale, $circleSize * $scale, $circleSize * $scale, $col_ellipse);
        }
      }
    }
    else {
      for($j = 7; $j < $count; $j++) {
        if($qr[$i][$j] == '1') {
          imagefilledellipse($imageX2, $circleSize * (3 + $j) * $scale, $circleSize * (3 + $i) * $scale, $circleSize * $scale, $circleSize * $scale, $col_ellipse);
        }
      }
    }
  }
  
  imagefillroundedrectangle($imageX2, $circleSize * 2.5 * $scale, $circleSize * 2.5 * $scale, $circleSize * 9.5 * $scale, $circleSize * 9.5 * $scale, 25, $col_ellipse);
  imagefillroundedrectangle($imageX2, $circleSize * 3.5 * $scale, $circleSize * 3.5 * $scale, $circleSize * 8.5 * $scale, $circleSize * 8.5 * $scale, 25, $bg);
  imagefillroundedrectangle($imageX2, $circleSize * 4.5 * $scale, $circleSize * 4.5 * $scale, $circleSize * 7.5 * $scale, $circleSize * 7.5 * $scale, 25, $secondary);
  
  imagefillroundedrectangle($imageX2, $circleSize * ($count - 4.5) * $scale, $circleSize * 2.5 * $scale, $circleSize * ($count + 2.5) * $scale, $circleSize * 9.5 * $scale, 25, $col_ellipse);
  imagefillroundedrectangle($imageX2, $circleSize * ($count - 3.5) * $scale, $circleSize * 3.5 * $scale, $circleSize * ($count + 1.5) * $scale, $circleSize * 8.5 * $scale, 25, $bg);
  imagefillroundedrectangle($imageX2, $circleSize * ($count - 2.5) * $scale, $circleSize * 4.5 * $scale, $circleSize * ($count + 0.5) * $scale, $circleSize * 7.5 * $scale, 25, $secondary);
  
  imagefillroundedrectangle($imageX2, $circleSize * 2.5 * $scale, $circleSize * ($count - 4.5) * $scale, $circleSize * 9.5 * $scale, $circleSize * ($count + 2.5) * $scale, 25, $col_ellipse);
  imagefillroundedrectangle($imageX2, $circleSize * 3.5 * $scale, $circleSize * ($count - 3.5) * $scale, $circleSize * 8.5 * $scale, $circleSize * ($count + 1.5) * $scale, 25, $bg);
  imagefillroundedrectangle($imageX2, $circleSize * 4.5 * $scale, $circleSize * ($count - 2.5) * $scale, $circleSize * 7.5 * $scale, $circleSize * ($count + 0.5) * $scale, 25, $secondary);
  
  $imageOut = imagecreatetruecolor($canvasSize, $canvasSize);
  imagecopyresampled($imageOut, $imageX2, 0, 0, 0, 0, $canvasSize, $canvasSize, $canvasSize * $scale, $canvasSize * $scale);

  ob_start();
  if($type == 'png') {
    imagepng($imageOut);
  }
  else {
  	imagejpeg($imageOut);
  }
  $imageData = ob_get_clean();
  
  imagedestroy($imageX2);
  imagedestroy($imageOut);

  return $imageData;
}

function imagefillroundedrectangle($im, $x, $y, $cx, $cy, $rad, $col) {

    // Draw the middle cross shape of the rectangle
    imagefilledrectangle($im,$x,$y+$rad,$cx,$cy-$rad,$col);
    imagefilledrectangle($im,$x+$rad,$y,$cx-$rad,$cy,$col);

    $dia = $rad*2;

    // Fill in the rounded corners
    imagefilledellipse($im, $x+$rad, $y+$rad, $rad*2, $dia, $col);
    imagefilledellipse($im, $x+$rad, $cy-$rad, $rad*2, $dia, $col);
    imagefilledellipse($im, $cx-$rad, $cy-$rad, $rad*2, $dia, $col);
    imagefilledellipse($im, $cx-$rad, $y+$rad, $rad*2, $dia, $col);
}

function hexColorAlloc($im, $hex) {
  if(strlen($hex) == 1) {
    $hex = str_repeat($hex, 6);
  }
  elseif(strlen($hex) == 2) {
    $hex = str_repeat($hex, 3);
  }
  elseif(strlen($hex) == 3) {
    $hex = str_repeat($hex, 2);
  }
  $a = hexdec(substr($hex, 0, 2));
  $b = hexdec(substr($hex, 2, 2));
  $c = hexdec(substr($hex, 4, 2)); 
  return ImageColorAllocate($im, $a, $b, $c);
}