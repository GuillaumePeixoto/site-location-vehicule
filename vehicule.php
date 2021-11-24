<?php
    require_once 'inc/init.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';

    $products = $bdd->prepare('select * from vehicule');
    $products->execute();
    $prods = $products->fetchAll(PDO::FETCH_ASSOC);

    $toutes_les_agences = $bdd->query("SELECT * FROM agence");
    $agences = $toutes_les_agences->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
    <h1 class="text-center my-5">Nos voitures</h1>

    <p class="my-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus, labore. Dolor voluptatem nobis ea deleniti, sit possimus eligendi iure recusandae rem eius. Doloribus delectus quas, tempore rem laboriosam nesciunt pariatur velit, illum sint, necessitatibus ea eaque provident. Cupiditate alias repellat aliquid veniam quibusdam corrupti, non odit asperiores illo eligendi necessitatibus! Fugiat quo in provident minus ullam praesentium natus amet sequi delectus quia incidunt beatae rem, labore quisquam pariatur accusantium exercitationem enim suscipit consequatur dolorum animi commodi saepe? Eos quas, aliquid blanditiis officia ipsum natus ea. Porro officiis qui totam unde dignissimos nesciunt repudiandae possimus numquam pariatur placeat! Magnam et aperiam hic officiis? Veniam, laborum voluptate nemo, qui tempore voluptates sed at, suscipit facere sint totam eos beatae nam aperiam molestiae! Asperiores non officia cupiditate itaque sapiente fuga earum illo quibusdam? Adipisci quia aliquid laboriosam saepe, dignissimos eos expedita molestiae quaerat nisi quae ratione provident, optio ad. Recusandae iure hic culpa!</p>


    <div id="recherche_avancer" class="col-12 mb-4">
        <form class="d-flex flex-wrap col-12 text-center" method="post">
            <div class="my-2 col-xl-4 col-12 border-end">
                <label for="ville" class="form-label text-black"><i class="bi bi-geo-alt-fill"></i> Adresse de départ</label>
                <select class="d-block p-2 mx-auto border-0" name="ville" id="ville">
                <?php
                    foreach($agences as $agence)
                    {
                        if(isset($_POST['ville']) && $_POST['ville'] == $agence['id_agence'])
                        {
                            echo "<option value='$agence[id_agence]' selected>$agence[titre]</option>";
                        }
                        else
                        {
                            echo "<option value='$agence[id_agence]'>$agence[titre]</option>";                            
                        }
                    }

                ?>
                </select>
            </div>
            <div class=" col-xl-4 col-12 my-3 border-end">
                <label for="depart_location" class="form-label text-black"><i class="bi bi-calendar"></i> Date de départ</label>
                <input type="datetime-local" name="depart_location" id="depart_location" class="d-block mx-auto p-2 border-0" min="<?php echo date('Y-m-d').'T'.date('H:i') ?>" value="<?php if(isset($_POST['depart_location'])){ echo $_POST['depart_location']; } ?>">
            </div>
            <div class=" col-xl-4 col-12 my-3">
                <label for="fin_location" class="form-label text-black"><i class="bi bi-calendar"></i> Fin de location</label>
                <input type="datetime-local" name="fin_location" id="fin_location" class="d-block mx-auto p-2 border-0"  min="<?php echo date('Y-m-d').'T'.date('H:i') ?>" value="<?php if(isset($_POST['fin_location'])){ echo $_POST['fin_location']; } ?>">
            </div>
            <div class=" col-xl-6 col-12 my-3">
                <label for="prix_min" class="form-label text-black"><i class="bi bi-calendar"></i> Prix minimum</label>
                <input type="number" name="prix_min" id="prix_min" class="d-block mx-auto p-2 border-0"  value="<?php if(isset($_POST['prix_min'])){ echo $_POST['prix_min']; } ?>">
            </div>
            <div class=" col-xl-6 col-12 my-3">
                <label for="prix_max" class="form-label text-black"><i class="bi bi-calendar"></i> Prix maximum</label>
                <input type="number" name="prix_max" id="prix_max" class="d-block mx-auto p-2 border-0" value="<?php if(isset($_POST['prix_max'])){ echo $_POST['prix_max']; } ?>">
            </div>
            <div class="mt-3 col-xl-12 col-12">
                <input type="submit" class="py-3 fs-5 h-100 w-100 text-white bg-success rounded-end border-0" name="rechercher" id="rechercher" value="Rechercher">
            </div>
        </form>
    </div>



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
                        <div class='card shadow-sm rounded my-auto h-100 w-100 card-car'>
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
    require_once 'inc/footer.inc.php';
?>
 