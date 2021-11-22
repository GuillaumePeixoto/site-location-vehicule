<?php
    require_once 'inc/init.inc.php';

    // Si il est déja connecter il n'a rien a faire ici


    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';

    if(!connect())
    {
        header('location: index.php');
    }
?>



<!-- tenter d'afficher 'Bonjour pseudo' sur la page en passant par la session -->

<h1 class="text-center my-5">Bonjour <span class="text-danger"><?= $_SESSION['membre']['pseudo'] ?></span></h1>

<!-- Réaliser une page profil affichant les données personnelle de l'utilisateur stockées dans le fichier session avec le design de votre choix -->

<?php 
    $commandes = $bdd->query('SELECT * FROM commande WHERE membre_id = '.$_SESSION['membre']['id_membre']);
    if($commandes->rowCount())
    {
        echo "<div class='d-flex mb-3'><a class='mx-auto btn btn-success' href='".URL."validation_commande.php'>Mes locations</a></div>";
    }

?>
<div class="card mx-auto col-4 mb-5 ">
  <img src="https://picsum.photos/250/250" class="card-img-top w-50 rounded-circle mx-auto p-3" alt="...">
  <div class="card-body p-0 mt-2">
    <table class="table text-center rounded mb-0">
        <?php
            foreach($_SESSION['membre'] as $index => $value)
            {
                if($index != 'id_membre' && $index != 'statut' && $index != 'civilite') 
                {
                    echo "<tr><td><strong>".ucfirst(str_replace('_',' ',$index))."</strong></td><td>$value</td></tr>";
                }
                if($index == 'civilite')
                {
                    if($value == 'm')
                    {
                        echo "<tr><td><strong>".ucfirst(str_replace('_',' ',$index))."</strong></td><td>Homme</td></tr>";
                    }
                    else
                    {
                        echo "<tr><td><strong>".ucfirst(str_replace('_',' ',$index))."</strong></td><td>Femme</td></tr>";
                    }
                }
            }
        ?>
    </table>
  </div>
</div>


<?php
    require_once 'inc/footer.inc.php';
?> 