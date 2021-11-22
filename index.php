<?php 
require_once 'inc/init.inc.php';
require_once 'inc/header.inc.php';
require_once 'inc/nav.inc.php';

// echo "<pre>"; var_dump($_POST); echo "</pre>";
// echo "<pre>"; var_dump($_SESSION); echo "</pre>";

$toutes_les_agences = $bdd->query("SELECT * FROM agence");
$agences = $toutes_les_agences->fetchAll(PDO::FETCH_ASSOC);

if(!empty($_POST['ville']) || !empty($_POST['depart_location']) || !empty($_POST['fin_location']))
{
    $requete = 'select * from vehicule WHERE';
    if(!empty($_POST['ville']))
    {
        $requete .= ' agence_id = :agence_id';
    }
    if(!empty($_POST['depart_location']))
    {
        $requete .= ' date_heure_depart = :date_heure_depart';
    }
    if(!empty($_POST['fin_location']))
    {
        $requete .= ' date_heure_fin = :date_heure_fin';
    }
}
else
{
    $requete = 'select * from vehicule';
}


$filter_vehicules = $bdd->prepare($requete);
if(!empty($_POST['ville']))
{
    $filter_vehicules->bindValue(':agence_id', $_POST['ville'],PDO::PARAM_INT);
}
if(!empty($_POST['depart_location']))
{
    $filter_vehicules->bindValue(':date_heure_depart', $_POST['depart_location'],PDO::PARAM_STR);
}
if(!empty($_POST['fin_location']))
{
    $filter_vehicules->bindValue(':date_heure_fin', $_POST['fin_location'],PDO::PARAM_INT);
}

$filter_vehicules->execute();
$prods = $filter_vehicules->fetchAll(PDO::FETCH_ASSOC);

var_dump($_POST);

?>

<div id="intro">
    <img class="img-fluid" style="width:100%" src="<?= URL ?>assets/img/intro.jpg">
    <div id="intro-text" class="w-100">
        <h1 class="text-center text-white mb-1">Bienvenue à bord</h1>
        <p class=" text-center text-white">Location de voiture 24h/24 et 7j/7</p>
    </div>
    <div id="recherche" class="col-sm-10 col-12">
        <form class="d-flex flex-wrap col-12" method="post">
            <div class="my-2 col-xl-3 col-12 border-end">
                <label for="ville" class="form-label text-black"><i class="bi bi-geo-alt-fill"></i> Adresse de départ</label>
                <select class="d-block p-2 mx-auto" name="ville" id="ville">
                <?php
                    foreach($agences as $agence)
                    {
                        echo "<option value='$agence[id_agence]'>$agence[titre]</option>";
                    }

                ?>
                </select>
            </div>
            <div class=" col-xl-3 col-12 my-2 border-end">
                <label for="depart_location" class="form-label text-black"><i class="bi bi-calendar"></i> Date de départ</label>
                <input type="datetime-local" name="depart_location" class="d-block mx-auto">
            </div>
            <div class=" col-xl-3 col-12 my-2">
                <label for="fin_location" class="form-label text-black"><i class="bi bi-calendar"></i> Fin de location</label>
                <input type="datetime-local" name="fin_location" class="d-block mx-auto">
            </div>
            <div class="mt-2 mt-xl-0 col-xl-3 col-12">
                <input type="submit" class="py-3 py-xl-0 fs-5 h-100 w-100 text-white bg-success rounded-end" name="chercher" id="chercher" value="Chercher">
            </div>
        </form>
    </div>
</div>

<?php
    if(isset($_POST['ville']) && isset($_POST['depart_location']) && isset($_POST['fin_location']))
    {
        ?>
        <div class="container">
            <h1 class="text-center my-5">Nos voitures</h1>
            <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
                <?php
                    foreach($prods as $product)
                    {
                        if(strlen($product['description']) > 50)
                        {
                            $description = substr($product['description'], 0, 50)."...";
                        }
                        else
                        {
                            $description = $product['description'];
                        }
                        echo "<div class='col-4 d-flex'>
                                <div class='card shadow-sm rounded my-auto h-100 w-100'>
                                    <a href='fiche_produit.php?id_vehicule=$product[id_vehicule]' class='h-75 d-flex'><img src='$product[photo]' class='card-img-top my-auto img-fluid' style='max-height: 100%' alt='$product[titre]'></a>
                                    <div class='card-body d-flex flex-column justify-content-center w-100'>
                                        <h5 class='card-title text-center'><a href='fiche_produit.php?id_vehicule=$product[id_vehicule]' class='alert-link text-dark titre-produit-boutique'>$product[titre]</a></h5>
                                        <p class='card-text  text-center'>$description</p>
                                        <p class='card-text fw-bold  text-center'>$product[prix_journalier] € / Jour</p>
                                        <p class='card-text text-center'><a href='fiche_produit.php?id_vehicule=$product[id_vehicule]' class='btn btn-outline-dark'>En savoir plus</a></p>
                                    </div>
                                </div>
                            </div>";
                    }
                ?>  
            </div>
        </div>
    <?php
    }
?>

<?php 
require_once 'inc/footer.inc.php';       
?>