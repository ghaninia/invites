<?php
namespace App\Controllers;
use App\Core\Session;
class CaptchaController
{
    public function index()
    {
        header("Content-Type:image/png") ;
        // initialise image with dimensions of 160 x 45 pixels
        $image = @imagecreatetruecolor(105, 40 ) or die("Cannot Initialize new GD image stream");
        // set background and allocate drawing colours
        $background = imagecolorallocate($image,  255, 255,255);
        imagefill($image, 0, 0, $background);
        $linecolor = imagecolorallocate($image,252,252,252);
        $textcolor = imagecolorallocate($image,0,0,40);
        // draw random lines on canvas
        for($i=0; $i < 8; $i++) {
            imagesetthickness($image, rand(1,3));
            imageline($image, rand(0,160), 0, rand(0,160), 45, $linecolor);
        }

        // using a mixture of TTF fonts
        $fonts = array();
        $fonts[] = __DIR__."/../../Public/fonts/captcha-font/1_captcha_font.ttf" ;
        $fonts[] = __DIR__."/../../Public/fonts/captcha-font/2_captcha_font.ttf";

        // add random digits to canvas using random black/white colour
        $digit = '';
        for($x = 10 ; $x <= 100 ; $x += 20) {
            $digit .= ($num = rand(0 , 9));
            imagettftext($image, 15 , rand(-30,30), $x , rand(20,35), $textcolor, $fonts[array_rand($fonts)], $num);
        }
        // record digits in session variable
        Session::put(["captcha" =>  strtolower($digit) ]) ;
        // display image and clean up
        imagepng($image);
        imagedestroy($image);
    }
}