<?php
    require_once 'inc/init.inc.php';
    if(!connect())
    {
        header('location: connexion.php');
    }
    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';
    if(isset($_SESSION['achat_success']))
    {
?>

    <h1 class="text-center my-5">FELICITATIONS !</h1>

    <h4 class="text-center"> Votre commande <strong>n°<?= $_SESSION['num_commande'] ?></strong> a bien été validée !</h4>
    <p class="text-center">Un mail de confirmation vous a été envoyé.</p>
    
    <?php 
        unset($_SESSION['achat_success']);
        }
        $commandes = $bdd->query("SELECT * FROM commande WHERE user_id = ".$_SESSION['user']['id_user']);
        $mes_commandes = $commandes->fetchAll(PDO::FETCH_ASSOC);
        foreach($mes_commandes as $commande)
        {
            $is_ready = date('D d M Y, G:i:s', strtotime($commande['date']));
            $day_before = date('D d M Y, G:i:s', strtotime('-1 day'));
            if($is_ready < $day_before)
            {
                $modify = $bdd->query('UPDATE commande SET etat = "envoyé" WHERE id_commande = '.$commande['id_commande']);
            }
        }
        $i = 0;
    ?>
	<h3 class="mb-0 mt-5 fw-bold text-center">Vos commandes</h3>
    <div class='accordion my-5' id='accordionExample'>
    <?php
        foreach($mes_commandes as $commande)
        { 
            $i += 1; ?>
        
            <div class="accordion-item my-3 rounded" id="commandes">
                <h2 class="accordion-header" id="heading<?= $i ?>">
                <button class="accordion-button collapsed rounded flex-wrap" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="false" aria-controls="collapse<?= $i ?>">
                    <p class='m-0 col-md-8 col-10'>Commande N°<?= $commande['id_commande'] ?> |  Etat : <?= $commande['etat'] ?></p>
                    <span class=' col-md-3 col-10 commande_status'> <?= date_format(date_create($commande['date']), 'D d M Y, G:i:s') ?> </span>
                </button>
                </h2>
                <div id="collapse<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $i ?>" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        
                        <?php
                            $details = $bdd->query("SELECT * FROM details_commande WHERE commande_id = $commande[id_commande]");
                            $detail_prods = $details->fetchAll(PDO::FETCH_ASSOC);
                            
                            foreach($detail_prods as $detail_prod)
                            { 
                                $prod = $bdd->query("SELECT * FROM article WHERE id_article = $detail_prod[article_id]");
                                $article = $prod->fetch(PDO::FETCH_ASSOC);
                                
                                if(strlen($article['description']) > 50)
                                {
                                    $description = substr($article['description'], 0, 50)."...";
                                }
                                else
                                {
                                    $description = $article['description'];
                                }

                                ?>
                                    <div class="py-2 mx-2 d-flex flex-row flex-wrap">
                                        <div class="col-md-1 col-4 d-flex commande_prod_photo">
                                            <img class="img_prod" src="<?= $article['photo'] ?>">
                                        </div>
                                        <div class="col-md-5 col-8 col-xs commande_prod_info">
                                            <span class="fs-5 fw-bold"><?= $article['titre'] ?></span>
                                            <span><?= $description ?></span>
                                        </div>
                                        <div class="col-md-6 col-12 commande_prod_quantity">
                                            <span class="fs-5 fw-bold text-end"> Prix unitaire : <?= $article['prix'] ?> €</span>
                                            <span class="fs-5 text-end fw-bold"> Quantité : <?= $detail_prod['quantite'] ?></span>
                                            <span class="fs-5 fw-bold text-end"> Prix total : <?= $article['prix'] * $detail_prod['quantite'] ?> €</span>
                                        </div>
                                    </div>
                                    <hr>
                                <?php
                            }

                        ?>
                        <div>
                            <p class="text-end mt-2 mb-0 fs-4 fw-bold">Montant total : <?= $commande['montant'] ?> €</p>
                        </div>
                    </div>
                </div>
            </div>
            
    <?php } ?>
    </div>

<?php
    require_once 'inc/footer.inc.php';
?>