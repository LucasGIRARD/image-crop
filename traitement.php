<?php

function returnCorrectFunction($ext) {
    $function = "";
    switch ($ext) {
        case "png":
            $function = "imagecreatefrompng";
            break;
        case "jpeg":
            $function = "imagecreatefromjpeg";
            break;
        case "jpg":
            $function = "imagecreatefromjpeg";
            break;
        case "gif":
            $function = "imagecreatefromgif";
            break;
        case "bmp":
            $function = "imagecreatefrombmp";
            break;
    }
    return $function;
}

function imagecreatefrombmp($filename) {
    //Ouverture du fichier en mode binaire
    if (!$f1 = fopen($filename, "rb"))
        return FALSE;

    //1 : Chargement des ent?tes FICHIER
    $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
    if ($FILE['file_type'] != 19778)
        return FALSE;

    //2 : Chargement des ent?tes BMP
    $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' .
                    '/Vcompression/Vsize_bitmap/Vhoriz_resolution' .
                    '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
    $BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
    if ($BMP['size_bitmap'] == 0)
        $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
    $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
    $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
    $BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
    $BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
    $BMP['decal'] = 4 - (4 * $BMP['decal']);
    if ($BMP['decal'] == 4)
        $BMP['decal'] = 0;

    //3 : Chargement des couleurs de la palette
    $PALETTE = array();
    if ($BMP['colors'] < 16777216) {
        $PALETTE = unpack('V' . $BMP['colors'], fread($f1, $BMP['colors'] * 4));
    }

    //4 : Cr?ation de l'image
    $IMG = fread($f1, $BMP['size_bitmap']);
    $VIDE = chr(0);

    $res = imagecreatetruecolor($BMP['width'], $BMP['height']);
    $P = 0;
    $Y = $BMP['height'] - 1;
    while ($Y >= 0) {
        $X = 0;
        while ($X < $BMP['width']) {
            if ($BMP['bits_per_pixel'] == 24)
                $COLOR = unpack("V", substr($IMG, $P, 3) . $VIDE);
            elseif ($BMP['bits_per_pixel'] == 16) {
                $COLOR = unpack("n", substr($IMG, $P, 2));
                $COLOR[1] = $PALETTE[$COLOR[1] + 1];
            } elseif ($BMP['bits_per_pixel'] == 8) {
                $COLOR = unpack("n", $VIDE . substr($IMG, $P, 1));
                $COLOR[1] = $PALETTE[$COLOR[1] + 1];
            } elseif ($BMP['bits_per_pixel'] == 4) {
                $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                if (($P * 2) % 2 == 0)
                    $COLOR[1] = ($COLOR[1] >> 4); else
                    $COLOR[1] = ($COLOR[1] & 0x0F);
                $COLOR[1] = $PALETTE[$COLOR[1] + 1];
            }
            elseif ($BMP['bits_per_pixel'] == 1) {
                $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                if (($P * 8) % 8 == 0)
                    $COLOR[1] = $COLOR[1] >> 7;
                elseif (($P * 8) % 8 == 1)
                    $COLOR[1] = ($COLOR[1] & 0x40) >> 6;
                elseif (($P * 8) % 8 == 2)
                    $COLOR[1] = ($COLOR[1] & 0x20) >> 5;
                elseif (($P * 8) % 8 == 3)
                    $COLOR[1] = ($COLOR[1] & 0x10) >> 4;
                elseif (($P * 8) % 8 == 4)
                    $COLOR[1] = ($COLOR[1] & 0x8) >> 3;
                elseif (($P * 8) % 8 == 5)
                    $COLOR[1] = ($COLOR[1] & 0x4) >> 2;
                elseif (($P * 8) % 8 == 6)
                    $COLOR[1] = ($COLOR[1] & 0x2) >> 1;
                elseif (($P * 8) % 8 == 7)
                    $COLOR[1] = ($COLOR[1] & 0x1);
                $COLOR[1] = $PALETTE[$COLOR[1] + 1];
            }
            else
                return FALSE;
            imagesetpixel($res, $X, $Y, $COLOR[1]);
            $X++;
            $P += $BMP['bytes_per_pixel'];
        }
        $Y--;
        $P+=$BMP['decal'];
    }

    //Fermeture du fichier
    fclose($f1);

    return $res;
}

    session_start();
    $tempImage = "temp/" . $_SESSION['crop']['temp'];
    $imageD = "resultat/" . $_SESSION['crop']['temp'];
    $imageDThumb = "resultat/T" . $_SESSION['crop']['temp'];
    $angle = $_POST['angle'];
    $difH = 0;
    $difW = 0;


    $split = explode('.', $tempImage);
    $ext = end($split);

    $function = returnCorrectFunction($ext);
    $image = $function($tempImage);


    list($imageBaseWidth, $imageBaseHeight) = getimagesize($tempImage);

    $ratioW = $imageBaseWidth / 100;
    $ratioH = $imageBaseHeight / 100;
    if ($ratioH > $ratioW) {
        $ratio = $ratioH;
    } else {
        $ratio = $ratioW;
    }
    $blackTop = $_POST['blackTop'] * $ratio;
    $blackBottom = $_POST['blackBottom'] * $ratio;
    $blackLeft = $_POST['blackLeft'] * $ratio;
    $blackRight = $_POST['blackRight'] * $ratio;

    $tempModifWidth = $_POST["imageNewWidth"];
    $tempModifHeight = $_POST["imageNewHeight"];
    $modifWidth = $tempModifWidth * $ratio;
    $modifHeight = $tempModifHeight * $ratio;

    if ($modifHeight > $modifWidth) {
        $newHeight = $modifHeight;
        $newWidth = $modifHeight;
    } else {
        $newHeight = $modifWidth;
        $newWidth = $modifWidth;
    }

    if ($angle != 0 && $angle != 360) {
        $x1 = imagesx($image);
        $y1 = imagesy($image);
        $white = imageColorAllocate($image, 255, 255, 255);
        $image = imagerotate($image, $angle, $white);
        $x2 = imagesx($image);
        $y2 = imagesy($image);
        if ($modifHeight < $x2) {
            $difH = ($x2 - $x1) / 2;
        } else {
            $imageBaseHeight = $x2;
        }
        if ($modifWidth < $y2) {
            $difW = ($y2 - $y1) / 2;
        } else {

            $imageBaseWidth = $y2;
        }
    }

    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imageantialias($newImage, true);

    $bg = imagecolorallocate($newImage, 255, 255, 255);
    imagefill($newImage, 0, 0, $bg);


    if ($blackTop > 0) {
        $src_x = $blackTop + $difH;
    } else {
        $src_x = 0 + $difH;
    }

    if ($blackLeft > 0) {
        $src_y = $blackLeft + $difW;
    } else {
        $src_y = 0 + $difW;
    }
    if ($blackTop > 0 || $blackBottom > 0) {
        $src_h = $modifHeight;
    } else {
        $src_h = $imageBaseWidth;
    }
    if ($blackLeft > 0 || $blackRight > 0) {
        $src_w = $modifWidth;
    } else {
        $src_w = $imageBaseWidth;
    }



    $dst_x = ($newHeight / 2) - ($src_h / 2);
    $dst_y = ($newWidth / 2) - ($src_w / 2);
    $dst_h = $src_h;
    $dst_w = $src_w;

    imagecopyresampled($newImage, $image, $dst_y, $dst_x, $src_y, $src_x, $dst_w, $dst_h, $src_w, $src_h);

    $xr = 0;
    $yr = 0;
    $src_hr = $newHeight;
    $src_wr = $newWidth;
    $dst_hwr1 = 500;
    $dst_hwr2 = 100;

    $dst_imager1 = imagecreatetruecolor($dst_hwr1, $dst_hwr1);
    $dst_imager2 = imagecreatetruecolor($dst_hwr2, $dst_hwr2);
    imageantialias($dst_imager1, true);
    imageantialias($dst_imager2, true);
    imagecopyresized($dst_imager1, $newImage, $xr, $yr, $xr, $yr, $dst_hwr1, $dst_hwr1, $src_wr, $src_hr);
    imagecopyresized($dst_imager2, $newImage, $xr, $yr, $xr, $yr, $dst_hwr2, $dst_hwr2, $src_wr, $src_hr);


    imagejpeg($dst_imager1, $imageD, 100);
    imagejpeg($dst_imager2, $imageDThumb, 100);

    imagedestroy($image);
    $_SESSION['crop']['i']++;

    if ($_SESSION['crop']['i'] + 1 <= count($_SESSION['crop']['files']['name'])) {
        header('Location: resize.php');
    } else {
        echo "traitement fini.";
    }

?>