<?php
    require_once '../inc/init.inc.php';

    if(!adminConnect())
    {
        header('location: '.URL.'connexion.php');
    }

    if(isset($_GET['action']) && $_GET['action'] == 'suppression')
    {
        if(isset($_GET['id_agence']) && !empty($_GET['id_agence']))
        {
            $supprimer = $bdd->prepare('DELETE FROM agence where id_agence = :id_agence');
            $supprimer->bindValue(':id_agence',$_GET['id_agence'],PDO::PARAM_INT);
            $supprimer->execute();
            $sup_success = '<p class="alert bg-success mx-auto mt-2 col-5 text-center"> Suppression du vehicule n°<strong>'.$_GET['id_agence'].'</strong>&nbsp;réalisé avec succès !</p>';
            $_GET['action'] = 'affichage';
        }
        else
        {
            header('location:'.URL.'admin/gestion_agence.php?action=affichage');
        }
    }

    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        if(isset($_GET['id_agence']) && !empty($_GET['id_agence']))
        {
            $agenceActuel = $bdd->prepare("select * from agence where id_agence = :id_agence");
            $agenceActuel->bindValue(':id_agence',$_GET['id_agence'],PDO::PARAM_INT);
            $agenceActuel->execute();
            if($agenceActuel->rowCount())
            {
                $agence = $agenceActuel->fetch(PDO::FETCH_ASSOC);

                $id_agence = (isset($agence['id_agence'])) ? $agence['id_agence'] : '';
                $titre = (isset($agence['titre'])) ? $agence['titre'] : '';
                $description = (isset($agence['description'])) ? $agence['description'] : '';
                $adresse = (isset($agence['adresse'])) ? $agence['adresse'] : '';
                $ville = (isset($agence['ville'])) ? $agence['ville'] : '';
                $code_postal = (isset($agence['code_postal'])) ? $agence['code_postal'] : '';
                $photo = (isset($agence['photo'])) ? $agence['photo'] : '';

            }
            else
            {
                header('location:'.URL.'admin/gestion_agence.php?action=affichage');
            }
        }
        else
        {
            header('location:'.URL.'admin/gestion_agence.php?action=affichage');
        }
    }


    if(isset($_POST['titre'], $_POST['description'], $_POST['adresse'], $_POST['ville'], $_POST['code_postal']))
    {
        if(!empty($_FILES['photo']['name']))
        {
            $nomPhoto = $_POST['titre'].'-'.$_FILES['photo']['name'];


            $photoBdd = URL.'assets/img/'.$nomPhoto;

            $photoDossier = RACINE_SITE.'assets/img/'.$nomPhoto;

            copy($_FILES['photo']['tmp_name'], $photoDossier);
        }else{
            if(isset($photo))
            {
                $photoBdd = $photo;
            }
            else
            {
                $photoBdd = '';
            }
            
        }
        
        if(!empty($_POST['titre']) &&  !empty($_POST['description']) && !empty($_POST['adresse']) && !empty($_POST['ville']) && !empty($_POST['code_postal']))
        {
            if(isset($_GET['action']) && $_GET['action'] == 'ajout')
            {
                $requete = $bdd->prepare("insert into agence values(null,:titre,:adresse,:ville,:code_postal,:description,:photo)");
            }
            elseif(isset($_GET['action']) && $_GET['action'] == 'modification')
            {
                $requete = $bdd->prepare("UPDATE agence SET titre = :titre, adresse = :adresse, ville = :ville ,code_postal = :code_postal, description = :description, photo = :photo WHERE id_agence = :id_agence");
                $requete->bindValue(':id_agence',$_GET['id_agence'],PDO::PARAM_INT);
            }
            $requete->bindValue(':titre',$_POST['titre'],PDO::PARAM_STR);
            $requete->bindValue(':adresse',$_POST['adresse'],PDO::PARAM_STR);
            $requete->bindValue(':ville',$_POST['ville'],PDO::PARAM_STR);
            $requete->bindValue(':code_postal',$_POST['code_postal'],PDO::PARAM_STR);
            $requete->bindValue(':description',$_POST['description'],PDO::PARAM_STR);
            $requete->bindValue(':photo',$photoBdd,PDO::PARAM_STR);
            $requete->execute();

            if(isset($_GET['action']) && $_GET['action'] == 'ajout')
            {
                $msg = "L'agence <strong>$_POST[titre]</strong> a bien été enregistré.";
            }
            elseif(isset($_GET['action']) && $_GET['action'] == 'modification')
            {
                $msg = "L'agence N°<strong>$_POST[id_agence] $_POST[titre]</strong> a bien été modifié.";
            }
        }

    }

    $admin_page = 'agence';

    require_once '../inc/admin_inc/header.inc.php';
    require_once '../inc/admin_inc/nav.inc.php';

?>

<div class="d-flex col-md-3 mx-auto flex-column mt-2">
    <a href="?action=affichage" class="btn btn-outline-primary">Liste des agences</a>
    <a href="?action=ajout" class="btn btn-outline-info mt-2">Ajout d'une agence</a>
</div>

<?php if(isset($sup_success)){echo $sup_success;}?>

<?php
    if(isset($_GET['action']) && $_GET['action'] == 'affichage')
    {
?>

        <hr><h2 class="text-center"> Liste des article </h2><hr>

        <?php
            $affiche = $bdd->prepare("select * from agence");
            $affiche->execute();
            $agences = $affiche->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <span>Il y a <span class="text-white bg-success badge "><?= $affiche->rowCount(); ?></span> article(s)</span>
        <div class="table-responsive">
            <table id="table-backoffice" class="table mt-2 table-bordered">
            <thead style="border-bottom: 2px solid black"><tr>
            <?php
            foreach($agences[0] as $index => $value)
            {
                echo "<th class='text-center'>$index</th>";  
            }
            echo "<th class='text-center'>Modifier</th><th class='text-center'>Supprimer</th></tr></thead><tbody class='border border-dark'>";
            foreach($agences as $agence)
            {
                echo "<tr>";
                foreach($agence as $index => $value)
                {

                    if($index == 'photo')
                    {
                        echo "<td class='text-center align-middle'><img height='100px' src='$value'></td>";
                    }
                    elseif($index == 'description')
                    {
                        if(strlen($value) > 50)
                        {
                            echo "<td class='text-center align-middle'>".substr($value, 0, 50)."...</td>";
                        }
                        else
                        {
                            echo "<td class='text-center align-middle'>$value</td>";
                        }
                        
                    }
                    else
                    {
                        echo "<td class='text-center align-middle'>$value</td>";
                    }

                }
                ?>
                <td class='align-middle '><a class='bg-success m-0 rounded d-flex text-center justify-content-center text-decoration-none' href='?action=modification&id_agence=<?= $agence['id_agence']?>'><i class='bi bi-pencil-square  p-3 fs-2 d-flex mx-0 text-white'></i></a></td>
                <td class='align-middle '><a class='bg-danger m-0 rounded d-flex text-center justify-content-center text-decoration-none' href='?action=suppression&id_agence=<?= $agence['id_agence']?>' onclick="return(confirm('Voulez vous réelement supprimer ce produit ?'))" ><i class='bi bi-trash p-3 fs-2 d-flex mx-0 text-white'></i></a></td>
                </tr>
                <?php } ?>
  
                </tbody>
            </table>
        </div>

<?php
    } // Ordre des priorités des conditions
    elseif(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification'))
    {
?>
<?php 
    if($_GET['action'] == 'ajout'){
        echo "<hr><h2 class='text-center'> Ajout d'un article </h2><hr>";
    }
    elseif($_GET['action'] == 'modification'){
        echo "<hr><h2 class='text-center'> Modification de l'agence n°$_GET[id_agence] </h2><hr>";
    }
?>


<?php if(isset($msg)){ echo "<p class='bg-success col-md-5 mx-auto p-3 text-center text-white mt-3 rounded'> $msg </p>"; }?> 


<form class="row g-3 mb-5" enctype="multipart/form-data" method="post">

    <div class="mb-1 col-md-6">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php if(isset($titre)){ echo $titre; }?>">
    </div>
    <div class="mb-1 col-md-6">
        <label for="photo" class="form-label">Photo</label>
        <input type="file" class="form-control" id="photo" name="photo">
    </div>
    <?php if(isset($photo)){ echo "<div class='mb-1 col-md-12 row'><h3 class='col-12 mt-1 text-center'>Photo Actuel :</h3><img src='$photo' class='col-4 mx-auto' alt='$titre'></div>"; }?>
    <div class="mb-1 col-md-5">
        <label for="adresse" class="form-label">Adresse</label>
        <input type="text" class="form-control" id="adresse" name="adresse" value="<?php if(isset($adresse)){ echo $adresse; }?>">
    </div>
    <div class="mb-1 col-4">
        <label for="ville" class="form-label">Ville</label>
        <input type="text" class="form-control" id="ville" name="ville" value="<?php if(isset($ville)){ echo $ville; }?>">
    </div>
    <div class="mb-1 col-3">
        <label for="code_postal" class="form-label">Code Postal</label>
        <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?php if(isset($code_postal)){ echo $code_postal; }?>">
    </div>
    <div class="mb-1 col-12">
        <label for="description" class="form-label">Description</label>
        <textarea type="text" class="form-control" rows="10" id="description" name="description"><?php if(isset($description)){ echo $description; }?></textarea>
    </div>


    <div class="col-12">
        <button type="submit" class="btn btn-dark">
            <?php 
                if($_GET['action'] == 'ajout'){
                    echo "Ajouter";
                }
                elseif($_GET['action'] == 'modification'){
                    echo "Modifier";
                }
            ?>
        </button>
    </div>
</form>




<?php

    }
    require_once '../inc/admin_inc/footer.inc.php';
?>