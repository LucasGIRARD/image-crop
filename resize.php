<?php
$error = false;
session_start();

$allowedExtensions = array("jpg", "jpeg", "gif", "png", "bmp");

if (isset($_FILES["photo"])) {
    $_SESSION['crop']['files'] = $_FILES["photo"];
    $_SESSION['crop']['i'] = 0;

    foreach ($_FILES["photo"]['name'] as $key => $value) {
        if ($_FILES["photo"]['error'][$key] == 0) {
            $fileName = $_FILES["photo"]['name'][$key];
            $fileNameArray = explode(".", strtolower($fileName));
            $extension = end($fileNameArray);

            if (!is_uploaded_file($_FILES["photo"]['tmp_name'][$key])) {
                $error = true;
                $message = "une erreur lors de l'upload du fichier " . $fileName . " s'est produit.";
            } else {
                if (!preg_match('/image/i', $_FILES["photo"]['type'][$key]) && !in_array($extension, $allowedExtensions)) {
                    $error = true;
                    $message = $fileName . "n'est pas reconnu comme une image!";
                } else {
                    $uploaddir = "temp";
                    $path = $uploaddir . "/" . $fileName;

                    if (!move_uploaded_file($_FILES["photo"]['tmp_name'][$key], $path)) {
                        $error = true;
                        $message = "Impossible de copier le fichier " . $fileName . " dans $uploaddir";
                    } else {
                        $message = "Le fichier " . $fileName . "  a bien été uploadé";
                    }
                }
            }
        } else {
            $error = true;
            $messageErreurPHP = array("",
                "Le fichier téléchargé excède la taille authorisé par le serveur. <!--(php.ini => upload_max_filesize) -->",
                "Le fichier téléchargé excède la taille authorisé par le formulaire. <!--(HTML => MAX_FILE_SIZE) -->",
                "Le fichier n'a été que partiellement téléchargé. <!-- (max_execution_time) -->",
                "Aucun fichier n'a été téléchargé.",
                "",
                "Le fichier n'a pas été téléchargé. <!--(serveur => dossier temporaire) -->",
                "Le fichier n'a pas été téléchargé. <!--(serveur => droit d'écriture) -->",
                "Le fichier n'a pas été téléchargé. <!--(serveur => extension PHP -->");
            $message = $messageErreurPHP[$_FILES["photo"]['error'][$key]];
        }
    }

    if ($error) {
        echo $message;
        exit();
    }
}
    $fileName = $_SESSION['crop']['files']['name'][$_SESSION['crop']['i']];
     $_SESSION['crop']['temp'] = $fileName;
?>
<!DOCTYPE>
<html>
<head>
    <link rel="stylesheet" media="screen" type="text/css" href="main.css" />
    <title></title>
    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="js/jQueryRotate.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</head>
<body onload="load();">
    <div style="padding-top:70px">
        <div id="body" style="position:relative;width:10px;margin: auto;">
            <div id="blackTop" style="height:1px;width:0px;top:-1px;left:0px;background-color:black;position:absolute;z-index:10;"></div>
            <div id="blackBottom" style="height:1px;width:0px;top:-1px;left:0px;background-color:black;position:absolute;z-index:10;"></div>
            <div id="blackLeft" style="height:0px;width:1px;top:-1px;left:-1px;background-color:black;position:absolute;z-index:10;"></div>
            <div id="blackRight" style="height:0px;width:1px;top:-1px;left:0px;background-color:black;position:absolute;z-index:10;"></div>
        </div>
        <div style="height:140px;">
            <img id="photo" style="max-height:100px;max-width:100px;" alt="" src="temp/<?php echo $fileName; ?>" />
        </div>

        <table style="margin: auto;">
            <tr>
                <td><b>Width:</b></td>
                <td><span id="widthValue"></span></td>
            </tr>
            <tr>
                <td><b>Height:</b></td>
                <td><span id="heightValue"></span></td>
            </tr>
            <tr>
                <td><b>Angle:</b></td>
                <td><span id="angleValue"></span>°</td>
            </tr>
        </table>
        unité :<input size="2" value="1" id="unit" onblur="changeUnit();" />
        <br />
        haut : <button onclick="heightTopMore();">+</button><button onclick="heightTopLess();">-</button>
        <br />
        bas : <button onclick="heightBottomMore();">+</button><button onclick="heightBottomLess();">-</button>
        <br />
        gauche : <button onclick="widthLeftMore();">+</button><button onclick="widthLeftLess();">-</button>
        <br />
        droite : <button onclick="widthRightMore();">+</button><button onclick="widthRightLess();">-</button>
        <br />
        pivoter : <button onclick="turnMore();">+</button><button onclick="turnLess();">-</button>
    </div>
    <div>
        <form name="hidden" action="traitement.php" method="POST">
            <input type='hidden' name='imageNewWidth' value="" />
            <input type='hidden' name='imageNewHeight' value="" />
            <input type='hidden' name='blackTop' value="" />
            <input type='hidden' name='blackBottom' value="" />
            <input type='hidden' name='blackLeft' value="" />
            <input type='hidden' name='blackRight' value="" />
            <input type='hidden' name='angle' value="" />
            <input type='hidden' name='action' value="resize" />
            <input type="submit" onmouseover="loadingValues();" value="valider" />
        </form>
    </div>
</body>
</html>