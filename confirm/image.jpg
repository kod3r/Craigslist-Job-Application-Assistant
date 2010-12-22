<?php
/* TODO: turn this into some real logging */
mail(
	"paigeat@devel.ws", 
	$_GET["ad"], 
	"read your message, and verified such by loading images in their mail client: \n".
	print_r($_SERVER, TRUE);
);
error_reporting(E_NONE);
// Create a 300x100 image
$im = imagecreatetruecolor(300, 100);
$red = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
$black = imagecolorallocate($im, 0x00, 0x00, 0x00);

// Make the background red
imagefilledrectangle($im, 0, 0, 299, 99, $red);

// Path to our ttf font file
$font_file = './arial.ttf';

// Draw the text 'PHP Manual' using font size 13
imagefttext($im, 13, 0, 105, 55, $black, $font_file, 'PHP Manual');

// Output image to the browser
header('Content-Type: image/png');

imagepng($im);
imagedestroy($im);
?>

