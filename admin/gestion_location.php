<?php
    require_once '../inc/init.inc.php';

    // Si l'internaute n'est pas admin, il n'a rien a faire sur cette page
    if(!adminConnect())
    {
        header('location: '.URL.'connexion.php');
    }

    $commandes = $bdd->query("SELECT * FROM commande WHERE user_id = ".$_SESSION['user']['id_user']);
    $les_commandes = $commandes->fetchAll(PDO::FETCH_ASSOC);

    $admin_page = 'commande';

    require_once '../inc/admin_inc/header.inc.php';
    require_once '../inc/admin_inc/nav.inc.php';


    if(isset($_GET['commande']) && !empty($_GET['commande']))
    {
        $this_commande = $bdd->prepare("SELECT * FROM commande WHERE id_commande = :id_commande");
        $this_commande->bindValue(":id_commande", $_GET['commande'], PDO::PARAM_INT);
        $this_commande->execute();

        $cette_commande = $this_commande->fetch(PDO::FETCH_ASSOC);
        $details_commande = $bdd->query("SELECT * FROM details_commande WHERE commande_id = $cette_commande[id_commande]");
        $detail_commande = $details_commande->fetchAll(PDO::FETCH_ASSOC);

        foreach($detail_commande as $detail)
        {
            $prod = $bdd->query("SELECT * FROM article WHERE id_article = $detail[article_id]");
            $article = $prod->fetch(PDO::FETCH_ASSOC);
        ?>
            <div class="">
                

            </div>
        </div>

    <?php } }else{ ?>

<table class="table table-bordered mt-5">
    <thead>
        <?php
        foreach($les_commandes[0] as $index => $value)
        { 

            echo "<th class='text-center align-middle'>".ucfirst($index)."</th>";
        }
        
        ?>
        <th class="text-center align-middle">Modifier</th>
    </thead>
    <tbody>
        <?php
        foreach($les_commandes as $commande)
        { 
            $who = $bdd->query('SELECT * FROM user WHERE id_user = '.$commande['user_id']);
            $user = $who->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr class="position-static">
                <td class="position-relative text-center align-middle"><a href="?commande=<?= $commande['id_commande']?>" class="stretched-link text-decoration-none text-dark"><?= $commande['id_commande'] ?></a></td>
                <td class='text-center align-middle'><?= $commande['user_id'].' - '.$user['nom'].' '.$user['prenom'] ?></td>
                <td class='text-center align-middle'><?= $commande['montant'] ?></td>
                <td class='text-center align-middle'><?= $commande['date'] ?></td>
                <td class='text-center align-middle'><?= $commande['etat'] ?></td>
                <td class='text-center align-middle'><a class="btn btn-success" href="?commande=<?= $commande['id_commande']?>"><i class="bi bi-pencil-square"></i></a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<?php
    }
    require_once '../inc/admin_inc/footer.inc.php';
?>