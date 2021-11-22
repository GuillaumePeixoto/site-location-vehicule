<?php
    require_once 'inc/init.inc.php';

    //unset($_SESSION['panier']);


    if(isset($_GET['action']) && $_GET['action'] == "supprimer")
    {
        if(isset($_GET['id_article']) && !empty($_GET['id_article']))
        {
            deletePanier($_GET['id_article']);
        }
    }

    if(isset($_POST['id_article'], $_POST['quantite']))
    {
        $data = $bdd->prepare('select * from article WHERE id_article = :id_article');
        $data->bindValue(':id_article',$_POST['id_article'], PDO::PARAM_INT);
        $data->execute();
        $product = $data->fetch(PDO::FETCH_ASSOC);
        addPanier($product['id_article'], $product['photo'], $product['titre'], $_POST['quantite'], $product['prix'], $product['stock']);
        header("location: panier.php");
    }

    $quantite_total = 0;

    if(isset($_POST['payer']))
    {
        for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++)
        {
            $r = $bdd->query("SELECT * FROM article WHERE id_article = ".$_SESSION['panier']['id_article'][$i]);
            $product = $r->fetch(PDO::FETCH_ASSOC);
            //echo '<pre>'; print_r($product); echo '</pre>';
            if($product['stock'] < $_SESSION['panier']['quantite'][$i])
            {
                // echo "stock en BDD : <span class='badge bg-success'>$product[stock]</span>";
                // echo "Quantité demandée : <span class='badge bg-success'>".$_SESSION['panier']['quantite'][$i]."</span>";
                if($product['stock'] > 0)
                {
                    $_SESSION['panier']['quantite'][$i] = $product['stock'];
                    $_SESSION['panier']['stock'][$i] = $product['stock'];
                    $msg = "La quantité de l'article <strong>".$_SESSION['panier']['titre'][$i]."</strong> a été réduite suite à une réduction de notre stock. Veuillez vérifier vos achats.";

                }
                else // Sinon le stock est à 0, rupture de stock
                {
                    $msg = "L'article <strong>".$_SESSION['panier']['titre'][$i]."</strong> est actuellement en rupture de stock ";
                    deletePanier($_SESSION['panier']['id_article'][$i]);
                }
                quantitePanier();
                $error = true;
            }
            
        }

        if(!isset($error))
        {
            $bdd->query("INSERT INTO commande (user_id, montant, date) VALUES (".$_SESSION['user']['id_user'].",'".montantTotal()."', NOW())");
            // permet de récupérer la dernière clé primaire inséré en BDD, ici la dernière id commande
            $idCommande = $bdd->lastInsertId();

            for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++)
            {
                $bdd->query("INSERT INTO details_commande (commande_id, article_id, quantite, prix) VALUES ($idCommande ,".$_SESSION['panier']['id_article'][$i].",".$_SESSION['panier']['quantite'][$i].", ".$_SESSION['panier']['prix'][$i].")");
                $bdd->query("UPDATE article SET stock = stock - ".$_SESSION['panier']['quantite'][$i]." WHERE id_article = ".$_SESSION['panier']['id_article'][$i]);
            }
            unset($_SESSION['panier']);
            $_SESSION['num_commande'] = $idCommande;
            quantitePanier();
            header("location: validation_commande.php");
            $_SESSION['achat_success'] = true;
        }
    }

    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';
    
?>
            <h1 class="text-center my-5">Votre panier</h1>
            <?php 
            if(isset($_SESSION['not_enough_quantity']))
            {
                echo $_SESSION['not_enough_quantity'];
                unset($_SESSION['not_enough_quantity']);
            }
            if(isset($msg)):
            ?>
                <p class="bg-success col-md-6 mx-auto p-3 text-center text-white my-3 rounded"><?= $msg; ?></p>
            <?php endif;
            if(!empty($_SESSION['panier']['id_article'])){ for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++){ ?>
                <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 mx-auto d-flex justify-content-center shadow-sm px-0 mt-2">
                    <div class="col-md-2 bg-white p-2 d-flex">
                        <a class="d-flex" href="fiche_produit.php?id_article=<?= $_SESSION['panier']['id_article'][$i] ?>"><img src="<?= $_SESSION['panier']['photo'][$i] ?>" alt="<?= $_SESSION['panier']['titre'][$i] ?>" class="img-panier align-self-center"></a>
                    </div>
                    <div class="col-md-6 bg-white d-flex flex-column justify-content-center p-2">
                        <h4><a href="fiche_produit.php?id_article=<?= $_SESSION['panier']['id_article'][$i] ?>" class="alert-link text-dark titre-produit-panier"><?= $_SESSION['panier']['titre'][$i] ?></a></h4>
                        
                        <?php
                        if($_SESSION['panier']['stock'][$i] < 10)
                        {
                            echo "<p class='text-danger fw-bold fst-italic'> Attention il ne reste plus que ".$_SESSION['panier']['stock'][$i]." article(s) en stock </p>";
                        }
                        else
                        {
                            echo "<p class='text-success fw-bold fst-italic'> En stock </p>";
                        }
                        ?>
                        </p>
                        <p>Quantité : <?= $_SESSION['panier']['quantite'][$i] ?></p>
                        <p class="mb-0"><a href="?action=supprimer&id_article=<?= $_SESSION['panier']['id_article'][$i] ?>" class="alert-link text-dark liens-supp-produit-panier">Supprimer</a></p>
                    </div>
                    <div class="col-md-4 bg-white d-flex justify-content-end align-items-center p-2">
                        <p class="fw-bold mb-0"> <?= $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i]?> €</p>
                    </div>
                </div>
            <?php } ?>
            <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 d-flex justify-content-end align-items-center shadow-sm px-0 py-3 bg-white mt-2 mb-3">
                <h5 class="m-0 px-2 fw-bold">Sous total (<?= $_SESSION['total_prod'] ?> articles) : <?= montantTotal() ?> €</h5>
            </div>
            <div class="container col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 p-0 text-end mb-5">
                <?php if(connect()): ?>  
                    <form action="" method="post">
                        <input type="submit" class="btn btn-dark" value="FINALISER LA COMMANDE" name="payer">
                    </form>
                <?php else: ?>
                    <a href="connexion.php" class="btn btn-dark">IDENTIFIER-VOUS</a>
                <?php endif; ?>
                
            </div>
            
            <?php }else{ ?>
                <h3 class="text-center">Votre panier est vide. <a href="<?= URL ?>boutique.php">Cliquez ici pour être rediriger vers la boutique</a></h3>

            <?php } ?>


<?php
    require_once 'inc/footer.inc.php';
?> 