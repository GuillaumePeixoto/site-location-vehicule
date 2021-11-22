<?php
    require_once 'inc/init.inc.php';



    if(isset($_GET['id_article']) && !empty($_GET['id_article']))
    {
        $data = $bdd->prepare("SELECT * FROM article WHERE id_article = :id_article");
        $data->bindValue(':id_article',$_GET['id_article'], PDO::PARAM_INT);
        $data->execute();
        if($data->rowCount())
        {
            $product = $data->fetch(PDO::FETCH_ASSOC);
            $other_products = $bdd->prepare('SELECT * FROM article WHERE categorie = :categorie AND id_article != :id_article ORDER BY RAND() LIMIT 3');
            $other_products->bindValue(':categorie', $product['categorie'],PDO::PARAM_STR);
            $other_products->bindValue(':id_article',$product['id_article'], PDO::PARAM_INT);
            $other_products->execute();
            $products = $other_products->fetchAll(PDO::FETCH_ASSOC);
            $article_history = array();
            if(!isset($_COOKIE['article_history'])) {
                setcookie('article_history', json_encode($article_history), time()+ 365*24*3600);
                $data = json_decode($_COOKIE['article_history'], true);
            }
            else
            {
                $data = json_decode($_COOKIE['article_history'], true);
            }
            
            array_push($data, $_GET['id_article']);
            setcookie('article_history',json_encode($data), time()+ 365*24*3600);
        }
        else
        {
            header('location: boutique.php');
        }
    }
    else
    {
        header('location: boutique.php');
    }

    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';
?>

            <h1 class="text-center my-5">Détails de l'article</h1>

            <div class="row mb-5">
                <div class="bg-white shadow-sm rounded d-flex zone-card-fiche-produit">

                    <a href="<?= $product['photo'] ?>" data-lightbox="image" data-title="<?= $product['photo'] ?>" data-alt="image" class="d-flex">
                        <img src="<?= $product['photo'] ?>" class="img-produit-fiche my-auto" alt="<?= $product['titre'] ?>">
                    </a>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-9 card-body d-flex flex-column justify-content-center zone-card-body">
                        <h5 class="card-title text-center fw-bold my-3"><?= $product['titre'] ?></h5>
                        <p class="card-text"><?= $product['description'] ?></p>
                        <p class="card-text fw-bold">Taille : <?= $product['taille'] ?></p>
                        <p class="card-text fw-bold">Couleur : <?= $product['couleur'] ?></p>
                        <p class="card-text fw-bold"><?= $product['prix']." €" ?></p>
                        <p class="card-text">
                            <form action="panier.php" method="post" class="row g-3">
                            <!--
                                A la validation du formulaire, on redirige l'internaute vers la page panier (attribut action) et les données saisie dans le formulaire seront 
                                accessible sur la page panier.php ( Quantité + id_produit )
                             -->
                                <input type="hidden" name="id_article" id="id_article" value="<?= $product['id_article'] ?>">
                                <div class="col-12 col-sm-7 col-md-4 col-lg-4 col-xl-4">
                                    
                                        
                                    <?php
                                        if($product['stock'] == 0)
                                        {
                                            echo "<span class='text-danger'> Rupture de stock </span>";
                                        }
                                        else
                                        {
                                            echo "<label for='autoSizingSelect'>Quantité</label><select class='form-select mb-1' id='autoSizingSelect' name='quantite'>";
                                                    for($i = 1; $i <= $product['stock']; $i++)
                                                    {
                                                        if($i < 6)
                                                        {
                                                                echo "<option value='$i'>$i</option>";
                                                        }
                                                    }
                                            echo "</select>";
                                            if($product['stock'] < 10)
                                            {
                                                echo "<span class='text-danger'> Attention il ne reste plus que $product[stock] en stock </span>";
                                            }
                                            else
                                            {
                                                echo "<span class='text-success'> En stock </span>";
                                            }
                                            echo '<div class="col-sm mt-1">
                                                    <input type="submit" class="btn btn-dark" value="Ajouter au panier">
                                                </div>';
                                        }
                                    ?>
                                </div>



                                
                            </form>
                            
                        </p>
                    </div>
                </div>
                <p class="mt-1"><a href="boutique.php" class="text-dark alert-link"><i class="bi bi-arrow-left-circle-fill"></i> Retour à la boutique</a></p>
            </div>
            <div>
                <h3>Articles similaires</h3>
                <div class="container d-flex flex-wrap justify-content-around my-5">
                    <?php
                        foreach($products as $prod)
                        {
                            echo "<a href='fiche_produit.php?id_article=$prod[id_article]' class='liens-nouveautes m-2 img-nouveautes col-3 shadow-sm rounded d-flex'><img src='$prod[photo]' class='w-100 align-self-center' alt='$prod[titre]'></a>";
                        }
                    ?>
                </div>


            </div>


<?php
    require_once 'inc/footer.inc.php';
?> 