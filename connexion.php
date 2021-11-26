<?php
    require_once 'inc/init.inc.php';
    
    // Si il est déja connecter il n'a rien a faire ici
    if(connect())
    {
        header('location: profil.php');
    }

    // Si l'indice 'action' est définit dans l'URL et qu'il y a pour valeur 'deconnexion', cela veux dire que l'internaute veux se déconnecter
    if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
    {
        unset($_SESSION['membre']);
        header('location: index.php');
    }
    //echo '<pre>'; print_r($_SESSION); echo '</pre>';

    if(isset($_POST['pseudo_email'], $_POST['password']))
    {
        $verifCredentials = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
        $verifCredentials->bindValue(":pseudo", $_POST['pseudo_email'], PDO::PARAM_STR);
        $verifCredentials->bindValue(":email", $_POST['pseudo_email'], PDO::PARAM_STR);
        $verifCredentials->execute();

        if($verifCredentials->rowCount()) // rowCount() retourne le nombre de résultats suite à la requete SQL
        {
            // echo "Pseudo / email existant en BDD";
            $user = $verifCredentials->fetch(PDO::FETCH_ASSOC);
            //Comparaison des mots de passe
            //Si les mots de passe sont crypté en BDD, nous pouvons les vérifiéer avec la fonction :
            // password_verify() : fonction prédéfinie permettant de comparer une clé de hachage à une chaine de caractères
            //Si le mot de passe saisie dans le formulaire correspond à la clé de hachage stockée en BDD, on entre dans la condition IF
            if(password_verify($_POST['password'], $user['mdp']))
            {
                // On entre dans la condition seulement dans le cas ou l'internaute a saisi le bom email/pseudo et le bon mot de passe, donc il a saisi les bons identifiants
                $_SESSION['user'] = array();
                foreach($user as $key => $value)
                {
                    if($key != 'mdp')
                    {
                        $_SESSION['user'][$key] = $value;
                    }
                    // la SESSION est accessible depuis n'importe ou dans le site, ce qui permet d'être authentifié sur n'importe quelle page du site
                }
                header('location: profil.php');
            }
            else
            {
                $error = "Identifiants invalide.";
            }
        }
        else
        {
            $error = "Identifiants invalide.";
        }
    }


    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';
?>

            <h1 class="text-center my-5">Identifiez-vous</h1>
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
            <form action="" method="post" class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4 mx-auto">
                <div class="mb-3">
                    <label for="pseudo_email" class="form-label">Nom d'utilisateur / Email</label>
                    <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" placeholder="Saisir votre Email ou votre nom d'utilisateur">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Saisir votre mot de passe">
                </div>
                <div>
                    <p class="text-end mb-0"><a href="" class="alert-link text-dark">Pas encore de compte ? Cliquez ici</a></p>
                    <p class="text-end m-0 p-0"><a href="" class="alert-link text-dark">Mot de passe oublié ?</a></p>
                </div>
                <input type="submit" name="submit" value="Continuer" class="btn btn-dark mb-5">
            </form>
<?php
    require_once 'inc/footer.inc.php';
?>
