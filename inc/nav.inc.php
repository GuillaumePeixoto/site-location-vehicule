<?php
    require_once 'accounts.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark" aria-label="Eighth navbar example">
    <div class="container">
        <a class="navbar-brand" href="<?= URL ?>index.php"><img src="<?= URL ?>assets/img/logo.png" alt="logo" class="logo-gif"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample07">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                <?php if(connect()) : ?>

                <li class="nav-item">
                    <a class="nav-link active hover_effect" aria-current="page" href="<?= URL ?>profil.php">Mon compte</a>
                </li>

                <?php else: ?>
                
                <li class="nav-item">
                    <button type="button" id="popup_inscription_button" class="btn-account p-2 hover_effect" data-bs-toggle="modal" data-bs-target="#popup_inscription">
                        Créer votre compte
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" id="popup_connexion_button" class="btn-account p-2 hover_effect" data-bs-toggle="modal" data-bs-target="#popup_connexion">
                        Identifiez-vous
                    </button>
                </li>

                <?php endif; ?>
                
                <li class="nav-item">
                    <a class="nav-link active hover_effect" aria-current="page" href="<?= URL ?>vehicule.php">Liste des véhicules</a>
                </li>
                <?php if(adminConnect()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="dropdown07" data-bs-toggle="dropdown" aria-expanded="false">BACKOFFICE</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown07">
                            <li><a class="dropdown-item hover_effect" href="<?= URL ?>admin/gestion_vehicule.php">Gestion Vehicule</a></li>
                            <li><a class="dropdown-item hover_effect" href="<?= URL ?>admin/gestion_user.php">Gestion user</a></li>
                            <li><a class="dropdown-item hover_effect" href="<?= URL ?>admin/gestion_location.php">Gestion reservation</a></li>
                            <li><a class="dropdown-item hover_effect" href="<?= URL ?>admin/gestion_agence.php">Gestion agence</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if(connect()) : ?>
                <span class="d-flex flex-row justify-content-center align-items-center">
                    <?= '<a class="text-white me-2" href="profil.php">Bonjour, <span class="text-warning">'.$_SESSION['membre']['pseudo'].'</span></a>' ; ?>
                    <a href="?action=deconnexion" class="text-white btn btn-danger"><i class="bi bi-box-arrow-right"></i> Déconnexion </a>
                </span>
            <?php endif; ?>
            <!-- <form>
            <input class="form-control" type="text" placeholder="Rechercher" aria-label="Search">
            </form> -->
        </div>
    </div>
</nav>

</header>
<main id="main_content">

