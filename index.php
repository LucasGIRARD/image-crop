<!DOCTYPE>
<html>
    <head>
        <title></title>
    </head>
    <body>

    <?php
    if (isset($_POST['nombre']) && $_POST['nombre'] != "") {
        ?>
        <form action="resize.php" enctype="multipart/form-data" method="POST" >
            <?php
            $form = '';
            for ($i=1; $i <= $_POST['nombre']; $i++) {
                $form .= '<label for="photo'.$i.'">photo '.$i.':</label> <input type="file" id="photo'.$i.'" name="photo[]" /> <br /><br />';
            }
            $form .=  '<input type="hidden" name="nombre" value="'.$_POST['nombre'].'" />';
            echo $form;
            ?>
            <input type="submit" value="valider" />
        </form>
        <?php
    } else {
        ?>
        <form method="POST" >
            <label for="nombre">nombre de photo :</label> <input type="text" id="nombre" name="nombre" value="1" /> <br /><br />
            <input type="submit" value="valider" />
        </form>
        <?php
    }
    ?>
    </body>
</html>
