<?php
    require_once 'inc/init.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';

// ------------------------------------------ FORMULAIRE ---------------------------------------------------

    if(!empty($_POST['ville']) || !empty($_POST['depart_location']) || !empty($_POST['fin_location']) || !empty($_POST['prix_min']) || !empty($_POST['prix_max']) || !empty($_POST['marque']) || !empty($_POST['modele']))
    {
        $requete = 'select * from vehicule WHERE ';
        $more_param = false;
        if(!empty($_POST['ville']))
        {
            $requete .= ' agence_id = :agence_id';
            $more_param = true;
        }

        if(!empty($_POST['depart_location']) && !empty($_POST['fin_location']) )
        {
            if($more_param)
            {
                $requete .= ' AND ';
            }
            //$requete .= "id_vehicule NOT IN(select vehicule_id from commande where date_heure_depart < :date_heure_depart AND date_heure_depart > :date_heure_depart AND date_heure_fin < :date_heure_fin AND date_heure_fin > :date_heure_fin )";
            $requete .= "id_vehicule NOT IN(select vehicule_id from commande where ( :date_heure_depart between date_heure_depart AND date_heure_fin ) OR ( :date_heure_fin between date_heure_depart AND date_heure_fin ) OR ( :date_heure_depart < date_heure_depart  AND :date_heure_fin > date_heure_fin ))";
            $more_param = true;
        }

        elseif(!empty($_POST['depart_location']))
        {
            if($more_param)
            {
                $requete .= ' AND ';
            }
            $more_param = true;
            $requete .= "id_vehicule NOT IN(select vehicule_id from commande where date_heure_depart < :date_heure_depart AND date_heure_fin > :date_heure_depart )";
        }

        elseif(!empty($_POST['fin_location']))
        {
            if($more_param)
            {
                $requete .= ' AND ';
            }
            $requete .= "id_vehicule NOT IN(select vehicule_id from commande where date_heure_depart < :date_heure_fin AND date_heure_fin > :date_heure_fin )";
            $more_param = true;
        }

        if(!empty($_POST['prix_min']))
        {
            if($more_param)
            {
                $requete .= ' AND ';
            }
            $requete .= ' prix_journalier >= :prix_min';
            $more_param = true;
        }

        if(!empty($_POST['prix_max']))
        {
            if($more_param)
            {
                $requete .= ' AND ';
            }
            $requete .= ' prix_journalier <= :prix_max';
            $more_param = true;
        }

        if(!empty($_POST['marque']))
        {
            if($more_param)
            {
                $requete .= ' AND ';
            }
            $requete .= ' marque = :marque';
            $more_param = true;
        }

        if(!empty($_POST['modele']))
        {
            if($more_param)
            {
                $requete .= ' AND ';
            }
            $requete .= ' modele = :modele';
            $more_param = true;
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
        $filter_vehicules->bindValue(':date_heure_fin', $_POST['fin_location'],PDO::PARAM_STR);
    }
    if(!empty($_POST['prix_min']))
    {
        $filter_vehicules->bindValue(':prix_min', $_POST['prix_min'],PDO::PARAM_INT);
    }
    if(!empty($_POST['prix_max']))
    {
        $filter_vehicules->bindValue(':prix_max', $_POST['prix_max'],PDO::PARAM_INT);
    }
    if(!empty($_POST['marque']))
    {
        $filter_vehicules->bindValue(':marque', $_POST['marque'],PDO::PARAM_STR);
    }
    if(!empty($_POST['modele']))
    {
        $filter_vehicules->bindValue(':modele', $_POST['modele'],PDO::PARAM_STR);
    }
    
    $filter_vehicules->execute();

    if(isset($_POST['ville']) || isset($_POST['depart_location']) || isset($_POST['fin_location']) || isset($_POST['prix_min']) || isset($_POST['prix_max']) || isset($_POST['marque']) || isset($_POST['modele']))
    {
        if($filter_vehicules->rowCount() == 0)
        {
            $nb_search = "Aucun véhicule ne correspond à votre recherche";
        }
        elseif($filter_vehicules->rowCount() == 1)
        {
            $nb_search = "1 véhicule correspond à votre recherche";
        }
        elseif($filter_vehicules->rowCount() > 1)
        {
            $nb_search = $filter_vehicules->rowCount()." véhicules correspondent à votre recherche";
        }
    }

    $prods = $filter_vehicules->fetchAll(PDO::FETCH_ASSOC);


// ----------------------------------------- SELECT ------------------------------------------------

    $products_marque = $bdd->prepare('select DISTINCT marque from vehicule');
    $products_marque->execute();
    $prods_marque = $products_marque->fetchAll(PDO::FETCH_ASSOC);

    $products_modele = $bdd->prepare('select DISTINCT modele, marque from vehicule');
    $products_modele->execute();
    $prods_modele = $products_modele->fetchAll(PDO::FETCH_ASSOC);


    $toutes_les_agences = $bdd->query("SELECT * FROM agence");
    $agences = $toutes_les_agences->fetchAll(PDO::FETCH_ASSOC);


?>
<div class="container">
    <h1 class="text-center my-5">Nos voitures</h1>

    <div id="recherche_avancer" class="col-12 mb-4">
        <form class="d-flex flex-wrap col-12 text-center" method="post">
            <div class="my-3 col-xl-4 col-12 border_separator">
                <label for="ville" class="form-label text-black fw-bold"><i class="bi bi-geo-alt-fill"></i> Adresse de départ</label>
                <select class="d-block p-2 mx-auto border-0 col-xl-8 col-lg-10 col-md-8 col-10" name="ville" id="ville">
                    <option value="">Choisir une agence</option>
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
            <div class=" col-xl-4 col-lg-6 col-12 my-3 border_separator">
                <label for="depart_location" class="form-label text-black fw-bold"><i class="bi bi-calendar"></i> Date de départ</label>
                <input type="datetime-local" name="depart_location" id="depart_location" class="d-block mx-auto p-2 border-0 col-10 col-md-8" min="<?php echo date('Y-m-d').'T'.date('H:i') ?>" value="<?php if(isset($_POST['depart_location'])){ echo $_POST['depart_location']; } ?>">
            </div>
            <div class=" col-xl-4 col-lg-6 col-12 my-3">
                <label for="fin_location" class="form-label text-black fw-bold"><i class="bi bi-calendar"></i> Fin de location</label>
                <input type="datetime-local" name="fin_location" id="fin_location" class="d-block mx-auto p-2 border-0 col-10 col-md-8"  min="<?php echo date('Y-m-d').'T'.date('H:i') ?>" value="<?php if(isset($_POST['fin_location'])){ echo $_POST['fin_location']; } ?>">
            </div>
            <div class=" col-xl-3 col-lg-6 col-12 my-3 border_separator">
                <label for="prix_min" class="form-label text-black fw-bold"><i class="bi bi-tag-fill"></i> Prix minimum</label>
                <input type="number" name="prix_min" id="prix_min" class="d-block mx-auto p-2 border-0 col-10 col-md-8"  value="<?php if(isset($_POST['prix_min'])){ echo $_POST['prix_min']; } ?>">
            </div>
            <div class=" col-xl-3 col-lg-6 col-12 my-3 border_separator">
                <label for="prix_max" class="form-label text-black fw-bold"><i class="bi bi-tag-fill"></i> Prix maximum</label>
                <input type="number" name="prix_max" id="prix_max" class="d-block mx-auto p-2 border-0 col-10 col-md-8" value="<?php if(isset($_POST['prix_max'])){ echo $_POST['prix_max']; } ?>">
            </div>
            <div class="my-3 col-xl-3 col-lg-6 col-12 border_separator">
                <label for="ville" class="form-label text-black fw-bold"><i class="fas fa-car"></i> Marque</label>
                <select class="d-block p-2 mx-auto border-0 col-10 col-md-8" name="marque" id="marque">
                    <option value="">Choisir une marque</option>
                    <?php
                        foreach($prods_marque as $prod)
                        {
                            if(isset($_POST['marque']) && $_POST['marque'] == $prod['marque'])
                            {
                                echo "<option class='$prod[marque]' value='$prod[marque]' selected>$prod[marque]</option>";
                            }
                            else
                            {
                                echo "<option class='$prod[marque]' value='$prod[marque]'>$prod[marque]</option>";                            
                            }
                        }

                    ?>
                </select>
            </div>
            <div class="my-3 col-xl-3 col-lg-6 col-12">
                <label for="ville" class="form-label text-black fw-bold"><i class="fas fa-car"></i> Modele</label>
                <select class="d-block p-2 mx-auto border-0 col-10 col-md-8" name="modele" id="modele">
                    <option value="">Choisir un modele</option>
                    <?php
                        foreach($prods_modele as $prod)
                        {
                            if(isset($_POST['modele']) && $_POST['modele'] == $prod['modele'])
                            {
                                echo "<option class='$prod[marque]' value='$prod[modele]' selected>$prod[modele]</option>";
                            }
                            else
                            {
                                echo "<option class='$prod[marque]' value='$prod[modele]'>$prod[modele]</option>";                            
                            }
                        }

                    ?>
                </select>
            </div>
            <div class="mt-3 col-xl-12 col-12">
                <input type="submit" class="py-3 fs-5 h-100 w-100 text-white bg-success rounded-end border-0" name="rechercher" id="rechercher" value="Rechercher">
            </div>
        </form>
    </div>

    <h3 class="text-center my-3"><?php if(isset($nb_search)){ echo  $nb_search; } ?></h3>

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
                echo "<div class='col-lg-4 col-md-6 col-12 d-flex'>
                        <div class='card shadow-sm rounded my-auto h-100 w-100 card-car'>
                            <a href='fiche_produit.php?id_vehicule=$product[id_vehicule]' class='h-75 d-flex'><img src='$product[photo]' class='card-img-top my-auto img-fluid' style='max-height: 100%' alt='$product[titre]'></a>
                            <div class='card-body d-flex flex-column justify-content-center w-100'>
                                <h5 class='card-title text-center'><a href='fiche_produit.php?id_vehicule=$product[id_vehicule]' class='alert-link text-dark titre-produit-boutique'>$product[titre]</a></h5>
                                <p class='card-text mb-2 text-center'>$description</p>

                                    <p class='text-center mb-2'>Agence :</p>";
                                    $name_agency = $bdd->query('SELECT titre FROM agence WHERE id_agence ='.$product['agence_id']);
                                    $agence_name = $name_agency->fetch(PDO::FETCH_ASSOC);
                                    echo "<p class='text-center fw-bold mb-2'>$agence_name[titre]</p>";
                                    
                                echo "<p class='card-text fw-bold  text-center'>$product[prix_journalier] € / Jour</p>
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
<script>
    $(document).ready(function() {
        $('#marque').on('change', function() {
            $('#marque').val();
            $("#marque option").removeClass("d-none");
        });
    });
</script>
 