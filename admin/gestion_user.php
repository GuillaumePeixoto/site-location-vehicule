<?php
    require_once '../inc/init.inc.php';

    // Si l'internaute n'est pas admin, il n'a rien a faire sur cette page
    if(!adminConnect())
    {
        header('location: '.URL.'connexion.php');
    }

    $commandes = $bdd->query("SELECT * FROM commande WHERE user_id = ".$_SESSION['user']['id_user']);
    $mes_commandes = $commandes->fetchAll(PDO::FETCH_ASSOC);

    $admin_page = 'user';

    require_once '../inc/admin_inc/header.inc.php';
    require_once '../inc/admin_inc/nav.inc.php';

?>

<?php
    foreach($mes_commandes as $commande)
    { 
        foreach($detail_prods as $detail_prod)
        { 
            $prod = $bdd->query("SELECT * FROM article WHERE id_article = $detail_prod[article_id]");
            $article = $prod->fetch(PDO::FETCH_ASSOC);

        }

     } 

?>





<?php
    require_once '../inc/admin_inc/footer.inc.php';
?>