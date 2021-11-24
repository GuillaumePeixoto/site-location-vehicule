<?php
    require_once 'inc/init.inc.php';



    if(isset($_GET['id_vehicule']) && !empty($_GET['id_vehicule']))
    {
        $data = $bdd->prepare("SELECT * FROM vehicule WHERE id_vehicule = :id_vehicule");
        $data->bindValue(':id_vehicule',$_GET['id_vehicule'], PDO::PARAM_INT);
        $data->execute();
        if($data->rowCount())
        {
            $product = $data->fetch(PDO::FETCH_ASSOC);
            // $other_products = $bdd->prepare('SELECT * FROM vehicule WHERE categorie = :categorie AND id_vehicule != :id_vehicule ORDER BY RAND() LIMIT 3');
            // $other_products->bindValue(':categorie', $product['categorie'],PDO::PARAM_STR);
            // $other_products->bindValue(':id_vehicule',$product['id_vehicule'], PDO::PARAM_INT);
            // $other_products->execute();
            // $products = $other_products->fetchAll(PDO::FETCH_ASSOC);
            // $vehicule_history = array();
            // if(!isset($_COOKIE['vehicule_history'])) {
            //     setcookie('vehicule_history', json_encode($vehicule_history), time()+ 365*24*3600);
            //     $data = json_decode($_COOKIE['vehicule_history'], true);
            // }
            // else
            // {
            //     $data = json_decode($_COOKIE['vehicule_history'], true);
            // }
            
            // array_push($data, $_GET['id_vehicule']);
            // setcookie('vehicule_history',json_encode($data), time()+ 365*24*3600);
        }
        else
        {
            header('location: index.php');
        }
    }
    else
    {
        header('location: index.php');
    }

    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';
?>
<main class="container">
    <h1 class="text-center my-5">Détails du vehicule</h1>

    <div class="row mb-5">
        <div class="bg-white shadow-sm rounded d-flex zone-card-fiche-produit">

            <a href="<?= $product['photo'] ?>" data-lightbox="image" data-title="<?= $product['photo'] ?>" data-alt="image" class="d-flex">
                <img src="<?= $product['photo'] ?>" class="img-produit-fiche my-auto" alt="<?= $product['titre'] ?>">
            </a>

            <div class="col-12 col-sm-12 col-md-12 col-lg-9 card-body d-flex flex-column justify-content-center zone-card-body">
                <h5 class="card-title text-center fw-bold my-3"><?= $product['titre'] ?></h5>
                <p class="card-text"><?= $product['description'] ?></p>
                <p class="card-text fw-bold"><?= $product['prix_journalier']." €" ?></p>
                <p class="card-text">
                    <form action="panier.php" method="post" class="row g-3">

                        <input type="hidden" name="id_vehicule" id="id_vehicule" value="<?= $product['id_vehicule'] ?>">

                    </form>
                    
                </p>
            </div>
        </div>
        <p class="mt-1"><a href="boutique.php" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à la boutique</a></p>
    </div>
    <!-- <div>
        <h3>vehicules similaires</h3>
        <div class="container d-flex flex-wrap justify-content-around my-5">
            <?php
                foreach($products as $prod)
                {
                    echo "<a href='fiche_produit.php?id_vehicule=$prod[id_vehicule]' class='liens-nouveautes m-2 img-nouveautes col-3 shadow-sm rounded d-flex'><img src='$prod[photo]' class='w-100 align-self-center' alt='$prod[titre]'></a>";
                }
            ?>
        </div>


    </div> -->
</main>

<?php
    require_once 'inc/footer.inc.php';
?> 