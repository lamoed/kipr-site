<?php

class Captcha extends Library
{
    private $salt = 'O1FHtAJhml';
    private $image;
    
    function create($picpath, $font) {
        $this->image = @imagecreatefrompng($picpath);
        if($this->image) {

            $sizex = imagesx($this->image);
            $sizey = imagesy($this->image);

            $im = imagecreate($sizex, $sizey);

            $color1 = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
            $color2 = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
            $color3 = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
            $color4 = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));

            $text = $this->generateWord(4);

            $margin = ($sizex * 65) / 100;
            $fontsize = $sizex - $margin;
            $fontwidth = ($fontsize * 65) / 100;
            $centery = ($sizey + $fontsize) / 2;
            $angle = 15;

            imagecopyresized($im, $this->image, 0, 0, 0, 0, $sizex, $sizey, $sizex, $sizey);
            imagettftext($im, $fontsize, mt_rand( - $angle, $angle), $fontwidth / 3, $centery, $color1, $font, $text[0]);
            imagettftext($im, $fontsize, mt_rand( - $angle, $angle), $fontwidth,     $centery, $color2, $font, $text[1]);
            imagettftext($im, $fontsize, mt_rand( - $angle, $angle), $fontwidth * 2, $centery, $color3, $font, $text[2]);
            imagettftext($im, $fontsize, mt_rand( - $angle, $angle), $fontwidth * 3, $centery, $color4, $font, $text[3]);

            $this->sessionAdd($text);
            
            header('Content-type: image/png');
            imagepng($im);
            imagedestroy($im);

        } else {
            throw new Exception('Изображение для заднего фона отсутствует');
        }
    }
    
    public function sessionCheck($value) {
        session_start();
        if(!empty($_SESSION['Cap'])
                  && $_SESSION['Cap'] == md5($value . $this->salt)) {
            $_SESSION['Cap'] = array();
            unset($_SESSION['Cap']);
            return true;
        } else {
            return false;
        }
    }
    
    private function sessionAdd($word) {
        session_start();
        $_SESSION['Cap'] = md5($word . $this->salt);
    }
    
    private function generateWord($len = 10) {
        $chars = '0123456789';
//        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
//        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        for($i = 0; $i < $len; $i++) {
            $pos = rand(0, strlen($chars) - 1);
            $string .= $chars{$pos};
        }
        return $string;
    }
}