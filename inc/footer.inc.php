
</main>

<div class="modal fade" id="popup_connexion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Identifiez-vous</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php // si l'indice 'validation_inscription' est définit dans la session de l'utilisateur, alors on entre dans le IF et on affiche un message de validation
            if(isset($_SESSION['validation_inscription']))
            {
                ?><p class="bg-success col-md-5 mx-auto p-3 text-center text-white rounded mt-3"> <?= $_SESSION['validation_inscription'] ?> </p>  <?php
            }
            unset($_SESSION['validation_inscription']);
        ?>
            <?php // si l'indice 'validation_inscription' est définit dans la session de l'utilisateur, alors on entre dans le IF et on affiche un message de validation
            if(isset($error))
            {
                ?><p class="bg-danger col-md-3 mx-auto p-3 text-center text-white rounded mt-3"> <?= $error; ?> </p>  <?php
            }
        ?>
        <form action="" method="post" class="col-12 col-md-9 mx-auto">
            <div class="mb-3">
                <label for="pseudo_email" class="form-label">Nom d'utilisateur / Email</label>
                <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" placeholder="Saisir votre Email ou votre nom d'utilisateur">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Saisir votre mot de passe">
            </div>
            <input type="hidden" name="connexion" value="connexion">
            <div>
                <p class="text-end mb-0"><a href="#" class="alert-link text-dark">Pas encore de compte ? Cliquez ici</a></p>
                <p class="text-end m-0 p-0"><a href="#" class="alert-link text-dark">Mot de passe oublié ?</a></p>
            </div>
            <input type="submit" name="submit" value="Continuer" class="btn btn-dark mb-5">
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="popup_inscription" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Créer votre compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <div class="col-md-12">
                        <label for="sexe" class="form-label">Civilité</label>
                        <div class="d-flex flex-row">
                            <div class="p-2">
                                <input type="radio" id="homme" name="civilite" value="homme" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'homme'){ echo "checked"; }?>>
                                <label for="homme">Homme</label>
                            </div>
                            <div class="p-2">
                                <input type="radio" id="femme" name="civilite" value="femme" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'femme'){ echo "checked"; }?>>
                                <label for="femme">Femme</label>
                            </div>
                        </div>
                    </div>
                    <div class="my-3 col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Saisir votre prénom" value="<?php if(isset($_POST['prenom'])){ echo $_POST['prenom'];}?>">
                    </div>
                    <div class="my-3 col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Saisir votre nom" value="<?php if(isset($_POST['nom'])){ echo $_POST['nom'];}?>">
                    </div>

                    <div class="my-3 col-md-6">
                        <label for="pseudo" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control <?php if($pseudo_already_exist != ""){ echo 'border-danger';} ?>" id="pseudo" name="pseudo" value="<?php if(isset($_POST['pseudo'])){ echo $_POST['pseudo'];}?>">
                        <?= $pseudo_already_exist ?>
                    </div>
                    <div class="my-3 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control <?php if($email_already_exist != ""){ echo 'border-danger';} ?>" id="email" name="email" placeholder="Saisir votre adresse email" value="<?php if(isset($_POST['email'])){ echo $_POST['email'];}?>">
                        <?= $email_already_exist ?>
                    </div>
                    <div class="my-3 col-md-6">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control  <?php if($irequal_mdp != ""){ echo 'border-danger';} ?>" id="password" name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password'];}?>">
                        <?= $irequal_mdp ?>
                    </div>
                    <div class="my-3 col-md-6">
                        <label for="confirm_password" class="form-label">Confirmer votre mot de passe</label>
                        <input type="password" class="form-control <?php if($irequal_mdp != ""){ echo 'border-danger';} ?>" id="confirm_password" name="confirm_password" value="<?php if(isset($_POST['confirm_password'])){ echo $_POST['confirm_password'];}?>">
                    </div>
                    <div class="my-3 col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck" name="pdc" value="accepted" <?php if(isset($_POST['pdc'])){ echo "checked";}?>>
                            <label class="form-check-label" for="gridCheck">
                            Accepter les <a href="" class="alert-link text-dark">politiques de confidentialité  <?= $pcd_accepted ?></a>  
                            </label>
                        </div>
                    </div>
                    <input type="hidden" name="inscription" value="inscription">
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark">Continuer</button> <?= $all_input_err ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        <footer class="bg-dark text-white">
            <div class="d-flex align-items-center zone-footer">
                <div class="col-sm-6 col-md-6 zone-administratif">
                    <h4 class="text-center">Administratifs</h4>
                    <ul class="d-flex justify-content-center menu-footer mb-0">
                        <li><a href="">CGV</a></li>
                        <li><a href="">Mentions légales</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-md-6">
                    <h4 class="text-center">Mes réseaux</h4>
                    <p class="d-flex justify-content-center mb-0">
                        <a href=""><i class="bi bi-facebook icone-b"></i></a>
                        <a href=""><i class="bi bi-instagram icone-b"></i></a>
                        <a href=""><i class="bi bi-twitter icone-b"></i></a>
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-end div-footer-copyright">
                <p class="text-center zone-copyright py-3 mb-0">&copy; 2021 | Peixoto Guillaume</p>
            </div>
        </footer>
    <?php
        if(!empty($all_input_err) || !empty($irequal_mdp) || !empty($pcd_accepted) || !empty($email_already_exist) || !empty($pseudo_already_exist) || !empty($all_input_err))
        {
            ?>
            <script>
                // document.getElementById('popup_inscription').classList.add('show');
                $(document).ready(function(){
                    $('#popup_inscription').modal('show');
                });
            </script>
            
            <?php
        }
    ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="<?=URL?>js/lightbox.js"></script>
    </body>
</html>