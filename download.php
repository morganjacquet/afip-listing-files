<?php
include 'listing.php';

$result = array();

//je recupère la liste de tout mes fichier dans le repertoire
foreach (list_file($_GET['path']) as $value) {
    //je recupère les infos de chaque fichier
    $value_file = get_info_file($value);

    if (!empty($_GET['files'])) {
        //si un fichier est dans la liste des fichier a télécharger alors je stock ses infos dans un tableau
        if (in_array($value_file['hash_path_complete'], $_GET['files']) && $value_file['type'] != 'directory') {
            $result[] = $value_file;
        }
    } elseif ($value_file['type'] != 'directory') {
        $result[] = $value_file;
    }
}

if (count($result) > 1) {
    //si il y a plusieurs fichier alors je passe la liste dans la fonction de téléchargement d'un zip
    zipDownload($result);
} else {
    //si il n'y a qu'un seul fichier alors je le télécharge simplement avec ses infos
    download($result[0]['name'], $result[0]['path_complete'], $result[0]['size'], $result[0]['type']);
}

/**
 * Téléchargment d'un seul fichier
 *
 * @param string $name  file name
 * @param string $path  racine path file
 * @param int $size     size file
 * @param string $type  mime type file
 * @return void
 */
function download($name, $path, $size, $type)
{
    header('Content-Type: ' . $type);
    header('Content-Length: ' . $size);
    header('Content-disposition: attachment; filename=' . $name);
    header('Pragma: public');
    header("Cache-Control: no-cache, must-revalidate");
    header('Expires: 0');
    readfile($path);
    exit();
}

/**
 * Download multi file in zip archive
 *
 * @param array $files  array with hash path file to download in archive
 * @return void
 */
function zipDownload($files)
{
    $zip = new ZipArchive();

    //creation du zip dans tout les cas je l'appel "archive.zip"
    if ($zip->open("archive.zip", ZIPARCHIVE::CREATE )!==TRUE) {
        exit("Impossible de créé archive.zip");
    }

    //ajout des différent fichier dans le zip
    foreach ($files as $values_file) {
        $zip->addFile($values_file['path_complete'], $values_file['name']);
    }

    $zip->close();

    //téléchargment du zip
    if(file_exists('archive.zip')){
        header("Content-type: application/zip"); 
        header("Content-Disposition: attachment; filename=archive.zip");
        header("Content-length: " . filesize("archive.zip"));
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        readfile("archive.zip");
    }

    //suppression du zip sur le serveur après téléchargement
    if(file_exists('archive.zip')){
        unlink('archive.zip');
    }
}