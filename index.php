<?php
include 'download.php';
include 'listing.php';

if (isset($_POST['dl_button'])) {
    if (file_exists($_POST['dl_file'])) {
        download(basename($_POST['dl_file']) . PHP_EOL, $_POST['dl_file'], 10000);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing du dossier</title>
</head>
<body>
<ul>
    <?php
    //je boucle sur les fichier des dossier et sous dossier de mon dossier courant
    foreach (list_file(getcwd()) as $value) {
        //je reformate ma value pour avoir uniquement le chemin partant du dossier courant et pas de la base de ma machine
        $value = str_replace(getcwd()."/", "", $value);
    ?>
    <li>
        <form method="POST">
            <?=$value;?> <a href="<?=$value;?>">Voir le fichier</a>
            <input type="hidden" name="dl_file" value="<?=$value;?>">
            <input type="submit" name="dl_button" value="Télécharger">
        </form>
    </li>
    <?php
    unset($value);
    }
    ?>
</ul>
</body>
</html>