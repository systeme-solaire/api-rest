<?php
session_start();

// Générer une chaîne aléatoire
$chars = 'ABCDEFGHJKLMNPRSTUVWXYZ23456789';
$captchaText = '';
for ($i = 0; $i < 5; $i++) {
    $captchaText .= $chars[rand(0, strlen($chars) - 1)];
}

// Stocker la valeur + timestamp en session
$_SESSION['captcha'] = [
    'code' => $captchaText,
    'time' => time()
];

// Créer une image
    $box = imagettfbbox(24, 0, dirname(__FILE__) . "/GFSDidotBold.otf", "88888");
    $boxwidth = abs(round($box[4] - $box[0]) * 1.2);
    $boxheight = abs(round($box[5] - $box[1]) * 1.2);
    $width = round($boxwidth * 1.2);
    $height = round($boxheight * 1.4);

//$width = 120;
//$height = 40;
$image = @imagecreatetruecolor($width, $height);

// Couleurs
$bgColor   = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgColor);

$linecolor = imagecolorallocate($image, 0x33, 0x88, 0xBB);
$textColor = imagecolorallocate($image, 0, 0, 0);
$noiseColor= imagecolorallocate($image, 150, 150, 150);

// Ajouter du bruit
for ($i = 0; $i < 50; $i++) {
    imageellipse($image, rand(0,$width), rand(0,$height), 1, 1, $noiseColor);
}

for($i=0; $i < 8; $i++) {
    imagesetthickness($image, rand(1, 3));
    imageline($image, rand(0, $width), 0, rand(0, $width), $height, $linecolor);
}


// Ajouter le texte
//imagestring($image, 6, 25, 10, $captchaText, $textColor);


    $textcolor1 = imagecolorallocate($image, 0x00, 0x00, 0x00);
    $textcolor2 = imagecolorallocate($image, 0x22, 0x22, 0x22);
    // paint digits on canvas
    for($i=0; $i < 5; $i++) {
      $x = ceil($i * 20);
      $angle = rand(-20, 20);
      $color = (rand() % 2) ? $textcolor1 : $textcolor2;
      $xpos = round($width/10 + $x);
      $shim = ($height - $boxheight)/2; // don't ask
      $ypos = rand(intval($boxheight - $shim), intval($boxheight + $shim))+5;
      imagettftext($image, 24, $angle, $xpos, $ypos, $color, dirname(__FILE__) . "/GFSDidotBold.otf", $captchaText[$i]);
    }

// Sortie image
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>