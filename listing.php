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
    unset($folder[array_search('.DS_Store', $folder, true)]);


    //si le dossier est vide on retourne rien
    if (count($folder) < 1){
        return;
    }

    //on boucle sur tous les fichier du dossier
    foreach($folder as $folder_value){
        if(is_dir($ori_folder.'/'.$folder_value)) {
            //si on detecte un sous dossier on le repasse dans notre fonction list_file
            $result[] = $ori_folder.'/'.$folder_value;
            $result[] = list_file($ori_folder.'/'.$folder_value);
        } else {
            //si c'est juste un fichier on le reformate avec le chemin exact
            $result[] = $ori_folder.'/'.$folder_value;
        }
    }

    //je retourne a plat mon tableau de chemin de fichier pour faciliter l'affichage
    return array_flatten($result);
}

function get_info_file($path_file){
    if (is_dir($path_file)) {
        $result = array(
            'type' => 'directory',
            'path' => str_replace(getcwd()."/", "", $path_file),
            'icon' => get_icon_file('directory'),
            'parent_directory' => str_replace(getcwd()."/", "", dirname($path_file)),
            'name' => basename($path_file)
        );
    } else {
        $result = array(
            'type' => mime_content_type($path_file),
            'path' => str_replace(getcwd()."/", "", $path_file),
            'parent_directory' => str_replace(getcwd()."/", "", dirname($path_file)),
            'icon' => get_icon_file(mime_content_type($path_file)),
            'name' => basename($path_file)
        );
    }

    if ($result['parent_directory'] == getcwd()) {
        $result['parent_directory'] = '-';
    }

    return $result;
}

function get_icon_file($type_file)
{
    switch ($type_file) {
        case 'text/html':
            return 'fab fa-html5';
            break;

        case 'text/x-php':
            return 'fas fa-file-code';
            break;

        case 'image/jpeg':
            return 'fas fa-image';
            break;

        case 'image/png':
            return 'fas fa-image';
            break;
        
        case 'video/mp4':
            return 'fas fa-film';
            break;

        case 'text/plain':
            return 'fas fa-file-alt';
            break;

        case 'text/x-shellscript':
            return 'fas fa-terminal';
            break;
        
        case 'application/pdf':
            return 'fas fa-file-pdf';
            break;
        
        case 'application/octet-stream':
            return 'fas fa-file';
            break;

        case 'text/x-perl':
            return 'fas fa-scroll';
            break;
        
        case 'image/svg+xml':
            return 'fas fa-bezier-curve';
            break;
        
        case 'directory':
            return 'fas fa-folder';
            break;
        
        case 'application/msword':
            return 'fas fa-file-word';
            break;
        
        case 'application/zip':
            return 'fas fa-file-archive';
            break;
        
        case 'audio/x-m4a':
            return 'fas fa-music';
            break;
        
        case 'video/quicktime':
            return 'fas fa-film';
            break;
        
        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheetapplication/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            return 'fas fa-file-excel';
            break;
        
        default:
            return 'fas fa-question-circle';
    }
}