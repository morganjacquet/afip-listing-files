<?php
//listing contien toutes les fonction essentiel pour le listing et la récupération d'information des fichiers
include 'listing.php';

//si le chemin de mon fichier de téléchargement n'est pas défini alors il ce trouve dans le même répertoire
if (empty($download_script)) {
    $download_script = "download.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de dossiers et fichiers "/<?=basename(getcwd())?>"</title>
    <!-- Fichier CSS Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <!-- Fichier CSS Datatable -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css" integrity="sha512-BMbq2It2D3J17/C7aRklzOODG1IQ3+MHw3ifzBHMBwGO/0yUqYmsStgBjI0z5EYlaDEFnvYV7gNYdD3vFLRKsA==" crossorigin="anonymous" />
    <!-- Fichier CSS FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" />
    <style>
        .sorting_disabled {
            background : none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Liste de dossiers et fichiers "/<?=basename(getcwd())?>"</h1>
        <table id="list_files" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="5%" class="text-center">
                        <div class="form-check">
                            <input type="checkbox" id="check_all">
                            <label class="form-check-label" for="check_all"> Tous</label>
                        </div>
                    </th>
                    <th width="5%">Types</th>
                    <th>Noms</th>
                    <th>Dossiers parent</th>
                    <th>Chemins</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // récupération de la liste des fichiers du dossier
                    foreach (list_file(getcwd()) as $value) {
                        // on récupère les infos des fichier un par un 
                        $value_file = get_info_file($value);
                ?>
                <tr>
                    <td class="text-center">
                        <div class="form-check">
                            <input type="checkbox" class="check_file" name="files" data-type="<?=$value_file['type'];?>" data-parent-hash="<?=$value_file['hash_parent_directory'];?>" data-path-hash="<?=$value_file['hash_path'];?>" value="<?=$value_file['hash_path_complete'];?>">
                        </div>
                    </td>
                    <td class="text-center">
                        <i class="<?=$value_file['icon'];?> fa-2x"></i>
                        <p style="display: none;"><?=$value_file['type'];?></p>
                    </td>
                    <td><?=$value_file['name'];?></td>
                    <td><?=$value_file['parent_directory'];?></td>
                    <td><?=$value_file['path'];?></td>
                    <td class="text-center">
                        <a href="<?=$value_file['path'];?>" target="_blank"><i class="far fa-eye" style="font-size: 18px;"></i></a>  
                        <?=$value_file['type'] != 'directory' ? '<a href="'. $download_script . '?path=' . getcwd() . '&files[]=' . $value_file['hash_path_complete'] . '" download><i class="fas fa-file-download" style="font-size: 18px;"></i></a>' : '<a href="'. $download_script . '?path=' . $value_file['path_complete'] . '"><i class="fas fa-file-archive" style="font-size: 18px;"></i></a>';?>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        <div class="row text-center" style="padding: 10px 0px 50px;">
            <a href="javascript:void(0)" class="download-multi-file btn btn-primary"> Télécharger la séléction</a>
        </div>
    </div>
</body>
<!-- Fichier JS Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- Fichier JS Bootstrap -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
<!-- Fichier JS Datatable -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<!-- Fichier JS Datable Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js" integrity="sha512-F0E+jKGaUC90odiinxkfeS3zm9uUT1/lpusNtgXboaMdA3QFMUez0pBmAeXGXtGxoGZg3bLmrkSkbK1quua4/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Fichier JS FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" integrity="sha512-fzff82+8pzHnwA1mQ0dzz9/E0B+ZRizq08yZfya66INZBz86qKTCt9MLU0NCNIgaMJCgeyhujhasnFUsYMsi0Q==" crossorigin="anonymous"></script>
<script>
$(document).ready( function () {
    // Mis en forme de la liste des fcihier dans table avec datatableJS
    $('#list_files').DataTable({
        "order": [],//par défault je ne trie aucune colone
        "columnDefs": [
            {
                "targets": 0,//on prend la première colone (checkbox)
                "orderable": false//je désactive le trie sur cette colone
            },
            {
                "targets": 5,//on prend la dernière colone
                "orderable": false//je désactive le trie sur cette colone
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/fr_fr.json'//lien pour la traduction de la lib Datable en fr
        },
        "pageLength": 200,//j'autorise l'affiche de 200 ligne directement pour éviter la pagination
        "paging": false//je désactive la pagination
    });

    // Check toutes les checkbox
    $("#check_all").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $(".check_file").click(function () {
        if ($(this).data("type") == 'directory') {
            $("input[data-parent-hash='"+$(this).data("path-hash")+"']").trigger( "click" );
        }
    });

    // bouton de téléchargement de tout les fichier séléctioné
    $(".download-multi-file").click(function(){
        var datas = { 'files' : [] };

        // récuperation de tout les fichier séléctionner
        $.each($("input[name='files']:checked"), function(){            
            datas['files'].push($(this).val());
        });

        // si il n'y a pas de fichier séléctioner je retourne une erreur
        if (datas['files'] == "") {
            alert('Aucun fichier séléctioné...');
            return;
        }
        
        // lien vers la page de téléchargement je passe en GET tout les fichier a télécharger
        var link = "<?=$download_script;?>?path=<?=getcwd();?>&files[]=" + datas['files'].join("&files[]=");

        // je créé un lien virtuel pour ouvrire la page de téléchargement dans un nouvelle onglet
        var element = document.createElement('a');
        element.setAttribute('href', link);
        element.setAttribute('target', "_blank");

        element.style.display = 'none';
        document.body.appendChild(element);

        element.click();

        // suppression du lien virtuel
        document.body.removeChild(element);
    });
});
</script>
</html>