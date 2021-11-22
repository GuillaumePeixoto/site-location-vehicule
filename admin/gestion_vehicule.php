<?php
    require_once '../inc/init.inc.php';

    // Si l'internaute n'est pas admin, il n'a rien a faire sur cette page
    if(!adminConnect())
    {
        header('location: '.URL.'connexion.php');
    }

    if(isset($_GET['agence']) && !empty($_GET['agence']))
    {
        $agence_exist = $bdd->prepare("select * from agence where id_agence = :id_agence");
        $agence_exist->bindValue(':id_agence', $_GET['agence'], PDO::PARAM_INT);
        $agence_exist->execute();
        if($agence_exist->rowCount())
        {
            $agence_exist = true;
        }
        else
        {
            header('location: '.URL.'admin/gestion_vehicule.php');
        }
    }

    if(isset($_GET['action']) && $_GET['action'] == 'suppression')
    {
        if(isset($_GET['id_vehicule']) && !empty($_GET['id_vehicule']))
        {
            $supprimer = $bdd->prepare('DELETE FROM vehicule where id_vehicule = :id_vehicule');
            $supprimer->bindValue(':id_vehicule',$_GET['id_vehicule'],PDO::PARAM_INT);
            $supprimer->execute();
            $sup_success = '<p class="alert bg-success mx-auto mt-2 col-5 text-center"> Suppression du produit n°<strong>'.$_GET['id_vehicule'].'</strong>&nbsp;réalisé avec succès !</p>';
            $_GET['action'] = 'affichage';
        }
        else
        {
            header('location:'.URL.'admin/gestion_vehicule.php?action=affichage');
        }
    }

    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        if(isset($_GET['id_vehicule']) && !empty($_GET['id_vehicule']))
        {
            $unVehicule = $bdd->prepare("select * from vehicule where id_vehicule = :id_vehicule");
            $unVehicule->bindValue(':id_vehicule',$_GET['id_vehicule'],PDO::PARAM_INT);
            $unVehicule->execute();
            // Si la requete retourne au moins 1 résultat, cela veux dire que l'id du produit est connu en BDD
            if($unVehicule->rowCount())
            {
                $vehicule = $unVehicule->fetch(PDO::FETCH_ASSOC);

                // on stock chaque donnée du produit a modifier dans chaque variable
                $id_vehicule = (isset($vehicule['id_vehicule'])) ? $vehicule['id_vehicule'] : '';
                $titre = (isset($vehicule['titre'])) ? $vehicule['titre'] : '';
                $marque = (isset($vehicule['marque'])) ? $vehicule['marque'] : '';
                $modele = (isset($vehicule['modele'])) ? $vehicule['modele'] : '';
                $description = (isset($vehicule['description'])) ? $vehicule['description'] : '';
                $photo = (isset($vehicule['photo'])) ? $vehicule['photo'] : '';
                $prix_journalier = (isset($vehicule['prix_journalier'])) ? $vehicule['prix_journalier'] : '';
                $agence_id = (isset($vehicule['agence_id'])) ? $vehicule['agence_id'] : '';
            }
            else
            {
                header('location:'.URL.'admin/gestion_vehicule.php?action=affichage');
            }
        }
        else
        {
            header('location:'.URL.'admin/gestion_vehicule.php?action=affichage');
        }
    }


    if(isset($_POST['titre'], $_POST['marque'], $_POST['modele'], $_POST['description'], $_POST['prix_journalier']) && ( isset($_POST['agence_id']) || isset($_GET['agence'])))
    {
        // Traitement de fichier uploader
        if(!empty($_FILES['photo']['name']))
        {
            // On renomme l'image en concatenant la références saisie dans le formulaire et le nom de l'image d'origine piochée dans le $_FILES
            $nomPhoto = $_POST['prix_journalier'].'-'.$_FILES['photo']['name'];


            // on définit l'URL de l'image qui sera stockée en BDD
            $photoBdd = URL.'assets/img/'.$nomPhoto;

            // On définit le chemin physique de l'image qui sera copié dans le dossier
            $photoDossier = RACINE_SITE.'assets/img/'.$nomPhoto;

            // copy() fonction prédéfini permettant de copier un fichier uploadé
            // Argument : 1er : le nom temporaire du fichier | 2e : le chemin physique de l'image sur le serveur
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
        // Exo : réaliser le traitement PHP + SQL permettant d'insérer un nouveau produit dans la BDD à la validation du formulaire ( prepare + bindvalue + execute)
        if(!empty($_POST['titre']) && !empty($_POST['marque']) && !empty($_POST['modele']) &&  !empty($_POST['description']) && !empty($_POST['prix_journalier']) && (!empty($_POST['agence_id']) || !empty($_GET['agence']) ))
        {
            if(isset($_POST['agence_id']))
            {
                $agence_id = $_POST['agence_id'];
            }
            else
            {
                $agence_id = $_GET['agence'];
            }

            if(isset($_GET['action']) && $_GET['action'] == 'modification')
            {
                $requete = $bdd->prepare("UPDATE vehicule SET agence_id = :agence_id, titre = :titre, marque = :marque ,modele = :modele, description = :description, photo = :photo, prix_journalier = :prix_journalier WHERE id_vehicule = :id_vehicule");
                $requete->bindValue(':id_vehicule',$_GET['id_vehicule'],PDO::PARAM_INT);
            }
            else
            {
                $requete = $bdd->prepare("insert into vehicule values(null,:agence_id,:titre,:marque,:modele,:description, :photo,:prix_journalier)");
            }

            $requete->bindValue(':agence_id',$agence_id,PDO::PARAM_INT);
            $requete->bindValue(':titre',$_POST['titre'],PDO::PARAM_STR);
            $requete->bindValue(':marque',$_POST['marque'],PDO::PARAM_STR);
            $requete->bindValue(':modele',$_POST['modele'],PDO::PARAM_STR);
            $requete->bindValue(':description',$_POST['description'],PDO::PARAM_STR);
            $requete->bindValue(':photo',$photoBdd,PDO::PARAM_STR);
            $requete->bindValue(':prix_journalier',$_POST['prix_journalier'],PDO::PARAM_STR);
            $requete->execute();

            if(isset($_GET['action']) && $_GET['action'] == 'modification')
            {
                $_SESSION['msg'] = "Le vehicule N°<strong>$_GET[id_vehicule] $_POST[titre]</strong> a bien été modifié.";
            }
            else
            {
                $_SESSION['msg'] = "Le vehicule <strong>$_POST[titre]</strong> a bien été enregistré.";
            }
            header('location: '.URL.'admin/gestion_vehicule.php');            
        }

    }

    $admin_page = 'vehicule';

    require_once '../inc/admin_inc/header.inc.php';
    require_once '../inc/admin_inc/nav.inc.php';
?>

<?php if(isset($sup_success)){echo $sup_success;}?>

<?php
    $les_agences = $bdd->query("select * from agence");
    if($les_agences->rowCount())
    {
        $agences = $les_agences->fetchAll(PDO::FETCH_ASSOC);
    }

    if(isset($_GET['agence']) && !empty($_GET['agence']))
    {
        $cette_agence = $bdd->prepare("select * from agence WHERE id_agence = :id_agence");
        $cette_agence->bindValue(':id_agence',$_GET['agence'],PDO::PARAM_INT);
        $cette_agence->execute();
        if($cette_agence->rowCount())
        {
            $une_agence = $cette_agence->fetch(PDO::FETCH_ASSOC);
        }
    }
?>

<div class="accordion col-4 my-4" id="accordionExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        <?php if(isset($_GET['agence']) && !empty($_GET['agence'])){ echo $une_agence['titre']; }else{ echo "Agence";} ?>
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <?php
            foreach($agences as $agence)
            {
                echo "<p class='my-2'><a class='text-decoration-none' href='". URL."admin/gestion_vehicule.php?agence=$agence[id_agence]'>$agence[id_agence] - $agence[titre]</a></p>";
            }
        ?>
      </div>
    </div>
  </div>
</div>

<?php
    if(isset($_GET['agence']))
    {
        $affiche = $bdd->prepare("select * from vehicule WHERE agence_id = :agence_id");
        $affiche->bindValue(':agence_id', $_GET['agence'],PDO::PARAM_INT);
    }
    else
    {
        $affiche = $bdd->prepare("select * from vehicule");
    }
    $affiche->execute();
    $vehicules = $affiche->fetchAll(PDO::FETCH_ASSOC);
    if($affiche->rowCount())
    {
?>

        
    <div class="table-responsive">
        <table id="table-backoffice" class="table mt-2 table-bordered">
        <thead style="border-bottom: 2px solid black"><tr>
        <?php
        foreach($vehicules[0] as $index => $value)
        {
            if($index == "id_vehicule")
            {
                echo "<th class='text-center py-2 px-3'>Vehicule</th>";
            }
            elseif($index == "agence_id")
            {
                echo "<th class='text-center py-2 px-3'>Agence</th>";
            }
            else
            {
                echo "<th class='text-center py-2 px-3'>$index</th>";                 
            }
        
        }
        echo "<th class='text-center py-2 px-3'>Modifier</th><th class='text-center py-2 px-3'>Supprimer</th></tr></thead><tbody class='border border-dark'>";
        foreach($vehicules as $vehicule)
        {
            echo "<tr>";
            foreach($vehicule as $index => $value)
            {
                if($index == 'photo')
                {
                    echo "<td class='text-center align-middle'><img height='100px' src='$value'></td>";
                }
                elseif($index == 'agence_id')
                {
                    $agence_info = $bdd->query("select titre from agence where id_agence = $value");
                    $agence_titre = $agence_info->fetch(PDO::FETCH_ASSOC);
                    echo "<td class='text-center align-middle'>$value - $agence_titre[titre] </td>";
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
            <td class='align-middle '><a class='bg-success m-0 rounded d-flex text-center justify-content-center text-decoration-none' href='?action=modification&id_vehicule=<?= $vehicule['id_vehicule']?>'><i class='bi bi-pencil-square  p-3 fs-2 d-flex mx-0 text-white'></i></a></td>
            <td class='align-middle '><a class='bg-danger m-0 rounded d-flex text-center justify-content-center text-decoration-none' href='?action=suppression&id_vehicule=<?= $vehicule['id_vehicule']?>' onclick="return(confirm('Voulez vous réelement supprimer ce produit ?'))" ><i class='bi bi-trash p-3 fs-2 d-flex mx-0 text-white'></i></a></td>
            </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>

<?php
    }

    if(isset($_GET['action']) && $_GET['action'] == 'modification'){
        echo "<hr><h2 class='text-center'> Modification du vehicule n°$_GET[id_vehicule] </h2><hr>";
    }
    else{
        echo "<hr><h2 class='text-center'> Ajout d'un vehicule </h2><hr>";
    }
?>


<?php if(isset($_SESSION['msg'])){ echo "<p class='bg-success col-md-5 mx-auto p-3 text-center text-white mt-3 rounded'> $_SESSION[msg] </p>"; unset($_SESSION['msg']);}?> 

<!-- enctype="multipart/form-data" : attribut permettant de récupérer les informations d'un fichier uploadé via un formulaire -->
<form class="row g-3 my-3" enctype="multipart/form-data" method="post">
    <div class="col-6">
        <div class="my-2 col-12">
            <label for="titre" class="form-label fs-5">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" value="<?php if(isset($titre)){ echo $titre; }?>">
        </div>
        <div class="my-2 col-12">
            <label for="marque" class="form-label fs-5">Marque</label>
            <input type="text" class="form-control" id="marque" name="marque" value="<?php if(isset($marque)){ echo $marque; }?>">
        </div>
        <div class="my-2 col-12">
            <label for="modele" class="form-label fs-5">Modele</label>
            <input type="text" class="form-control" id="modele" name="modele" value="<?php if(isset($modele)){ echo $modele; }?>">
        </div>
        <div class="my-2 col-12">
            <label for="prix_journalier" class="form-label fs-5">Prix Journalier</label>
            <input type="text" class="form-control" id="prix_journalier" name="prix_journalier" value="<?php if(isset($prix_journalier)){ echo $prix_journalier; }?>">
        </div>
        <?php
        if(!isset($_GET['agence']))
        { ?>
        <div class="my-2 col-12">
            <label for="modele" class="form-label fs-5">Agence</label>
            <select type="text" class="form-control" id="agence_id" name="agence_id">
            <?php 
                foreach($agences as $agence)
                {
                    echo "<option value='$agence[id_agence]' >$agence[id_agence] - $agence[titre]</option>";
                }
            ?>
            </select>
        </div>
        <?php }
    ?>
    </div>
    <div class="col-6">

        <div class="my-2 col-md-12">
            <label for="photo" name="custom_photo" class="form-label fs-5">Photo</label>
            <input type="file" class="form-control add_photo" accept="image/*" id="photo" name="photo">
        </div>
        <?php if(isset($photo)){ echo "<div class='mb-1 col-md-12 row'><h3 class='col-12 mt-1 text-center'>Photo Actuel :</h3><img src='$photo' class='col-6 mx-auto' alt='$titre'></div>"; }?>
        
        <div class="mt-3 col-12">
            <label for="description" class="form-label fs-5">Description</label>
            <textarea type="text" class="form-control" rows="10" id="description" name="description"><?php if(isset($description)){ echo $description; }?></textarea>
        </div>

    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-dark">
            <?php 
                if(isset($_GET['action']) && $_GET['action'] == 'modification'){
                    echo "Modifier";
                }
                else
                {
                    echo "Ajouter";
                }
            ?>
        </button>
    </div>
</form>




<?php
    require_once '../inc/admin_inc/footer.inc.php';
?>