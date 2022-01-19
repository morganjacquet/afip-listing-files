<?php

function array_flatten($array) { 
    //si c'est pas un tableau on renvois rien
    if (!is_array($array)) { 
        return false; 
    }

    $result = array();

    foreach ($array as $key => $value) { 
        if (is_array($value)) { 
            //si on tombe sur une autre dimention on merge la dimention dans notre tableau results
            $result = array_merge($result, array_flatten($value)); 
        } else { 
            //si on tombe sur une simple value on la merge dans notre tableau values
            $result = array_merge($result, array($key => $value));
        } 
    }

    //on retourne notre tableau mis a plat
    return $result; 
}

function list_file($ori_folder){
    //on scan le dossier courant
    $folder = scandir($ori_folder);

    //on supprime . et ..
    unset($folder[array_search('.', $folder, true)]);
    unset($folder[array_search('..', $folder, true)]);

    //si le dossier est vide on retourne rien
    if (count($folder) < 1){
        return;
    }

    //on boucle sur tous les fichier du dossier
    foreach($folder as $folder_value){
        if(is_dir($ori_folder.'/'.$folder_value)) {
            //si on detecte un sous dossier on le repasse dans notre fonction list_file
            $folder_result = list_file($ori_folder.'/'.$folder_value);
        } else {
            //si c'est juste un fichier on le reformate avec le chemin exact
            $folder_result = $ori_folder.'/'.$folder_value;
        }
        //on stock dans un tableau chaque lien de fichier
        $result[] = $folder_result;
    }

    //je retourne a plat mon tableau de chemin de fichier pour faciliter l'affichage
    return array_flatten($result);
}