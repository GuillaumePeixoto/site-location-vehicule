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
            $name_agency = $bdd->query('SELECT titre FROM agence WHERE id_agence ='.$product['agence_id']);
            $agence_name = $name_agency->fetch(PDO::FETCH_ASSOC);
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
                <p class="card-text fw-bold"><?= $product['prix_journalier']." € / Jour" ?></p>
                <div class="d-flex flex-wrap flex-row">
                    <p class="mb-1 fw-bold">Agence :&nbsp;</p>
                    <p class="card-text"><?= $agence_name['titre']?></p>
                </div>

                <div class="d-flex flex-wrap flex-column">
                    <p class="mb-1 fw-bold">Description : </p>
                    <p class="card-text"> <?= $product['description'] ?></p>
                </div>
                <hr>
                <form action="panier.php" method="post" class="row">
                    <input type="hidden" name="id_vehicule" id="id_vehicule" value="<?= $product['id_vehicule'] ?>">
                    <div class="row justify-content-evenly align-items-center">
                        <h4 class="col-12 text-center mb-3">Date de réservation</h4>
                        <div class=" col-xl-4 col-12 my-2 border-end d-flex flex-column">
                            <label for="debut_location" class="form-label text-black mx-auto"><i class="bi bi-calendar"></i> Date de départ</label>
                            <input type="datetime-local" name="debut_location" id="debut_location" class="d-block rounded mx-auto p-2 border border-dark" min="<?php echo date('Y-m-d').'T'.date('H:i') ?>" value="<?php if(isset($_POST['depart_location'])){ echo $_POST['depart_location']; } ?>">
                        </div>
                        <div class=" col-xl-4 col-12 my-2 d-flex flex-column">
                            <label for="fin_location" class="form-label text-black mx-auto"><i class="bi bi-calendar"></i> Fin de location</label>
                            <input type="datetime-local" name="fin_location" id="fin_location" class="d-block rounded mx-auto p-2 border border-dark"  min="<?php echo date('Y-m-d').'T'.date('H:i') ?>" value="<?php if(isset($_POST['fin_location'])){ echo $_POST['fin_location']; } ?>">
                        </div>
                        <div class="mt-2 mt-xl-0 col-xl-4 col-12">
                            <input type="submit" class="py-1 fs-5 col-8 text-white bg-success rounded border-0" name="valider" id="valider" value="Valider">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <p class="mt-1"><a href="index.php" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à l'accueil</a></p>
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
<!-- 
<script type = "text/javascript" >
    // JavaScript program to illustrate 
    // calculation of no. of days between two date 

    // To set two dates to two variables
    var date1 = new Date("06/30/2019");
    var date2 = new Date("07/30/2019");
    
    // To calculate the time difference of two dates
    var Difference_In_Time = date2.getTime() - date1.getTime();
    
    // To calculate the no. of days between two dates
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
    
    //To display the final no. of days (result)
    document.write("Total number of days between dates  <br>"
                + date1 + "<br> and <br>" 
                + date2 + " is: <br> " 
                + Difference_In_Days);
  
</script> -->