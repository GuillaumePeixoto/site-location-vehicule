<?php
    require_once 'inc/init.inc.php';

    /*
        1. Controller en PHP que l'on réceptionne bien toutes les données saisie dans le formulaire
        2. Faites en sorte d'informer l'internaute si le pseudo est indisponible (déja dans la BDD)
        3. Faites en sorte d'informer l'internaute si l'email est indisponible (déja dans la BDD)
        4. Faites en sorte que si les mots de passes ne correspondent pas
        5. Faites en sorte d'informer l'internaute si les politiques de confidentialité ne sont pas cochés
        6. Réaliser le traitement PHP + SQL afin d'insérer un nouveau membre dans la BDD à la validation du formulaire si il est correct ( Prepare + Bindvalue )

    */
    //echo '<pre>'; echo print_r($_POST); echo '</pre>';

    // Si il est déja connecter il n'a rien a faire ici
    if(connect())
    {
        header('location: profil.php');
    }


    $all_input_err = "";
    $irequal_mdp = "";
    $pcd_accepted = "";
    $email_already_exist = "";
    $pseudo_already_exist = "";
    if(isset($_POST['sexe']) && isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['email']) && isset($_POST['prenom']) && isset($_POST['nom']))   
    {
        if( $_POST['password'] !== $_POST['confirm_password'] )
        {
            $irequal_mdp = "<span class='text-danger fw-bold position-absolute mt-0'>Les mots de passe ne sont pas identiques</span>";
        }
        if(!empty($_POST['email']))
        {
            $requete="select * from membre where email = :email;";
            $donnees=array(":email"=>$_POST['email']);
			$select= $bdd->prepare($requete);
			$select->execute($donnees);
			$email_exist=$select->fetch();
            if($email_exist != false)
            { 
                $email_already_exist = '<span class="text-danger fw-bold position-absolute mt-0">Cette adresse est déja utilisé</span>';
            }
            elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $email_already_exist = '<span class="text-danger fw-bold position-absolute mt-0">Adresse mail incorrect</span>';
            }
        }
        if(!empty($_POST['pseudo']))
        {
            $requete="select * from user where pseudo = :pseudo;";
            $donnees=array(":pseudo"=>$_POST['pseudo']);
			$select= $bdd->prepare($requete);
			$select->execute($donnees);
			$pseudo_exist=$select->fetch();
            if($pseudo_exist != false)
            {
                $pseudo_already_exist = '<span class="text-danger fw-bold position-absolute mt-0">Ce pseudo est déja utilisé</span>';
            }
        }
        
        if(!isset($_POST['pdc']))
        {
            $pcd_accepted = "<span class='text-danger fw-bold mt-0'>Veuillez accepter les politiques de confidentialité </span>";
        }

        if(!empty($_POST['sexe']) && !empty($_POST['pseudo']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['email']) && !empty($_POST['prenom']) && !empty($_POST['nom']) && $_POST['pdc'] == 'accepted')  
        {
            $all_input_err = "";

            $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            // password_hash : fonction prédéfinie permettant de créer une clé de hachage pour le mot de passe dans la BDD
            // arguments : password_hash(mot_de_passe, TYPE DE CRYPTAGE);
            $requete = $bdd->prepare("insert into membre values(null,:pseudo,:password,:nom,:prenom,:email,:sexe,'client',NOW())");
            $requete->bindValue(':pseudo',$_POST['pseudo'],PDO::PARAM_STR);
            $requete->bindValue(':password',$_POST['password'],PDO::PARAM_STR);
            $requete->bindValue(':nom',$_POST['nom'],PDO::PARAM_STR);
            $requete->bindValue(':prenom',$_POST['prenom'],PDO::PARAM_STR);
            $requete->bindValue(':email',$_POST['email'],PDO::PARAM_STR);
            $requete->bindValue(':sexe',$_POST['sexe'],PDO::PARAM_STR);
            $requete->execute();

            // On stock dans le fichier de session de l'utilisateur un message de validation
            $_SESSION['validation_inscription'] = "Félicitations ! Vous êtes mainteanant inscrit ! Vous pouvez dès à présent vous connecter !";

            // Après l'insertion, on redirige l'internaute vers la page de connexion
            header("location: connexion.php");
        }
        $all_input_err = "<span class='text-danger ms-2 fw-bold align-middle'>Veuillez remplir tous les champs</span>";
    }





    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';
?>
<main class="container">
    <h1 class="text-center my-5">Créer votre compte</h1>

    <form class="row g-3 mb-5" method="post">
        <div class="col-md-2">
            <label for="sexe" class="form-label">Civilité</label>
            <select type="text" class="form-control" id="sexe" name="sexe">
                <option value="f">Madame</otpion>
                <option value="m">Monsieur</option>
            </select>
        </div>
        <div class="mb-1 col-md-5">
            <label for="pseudo" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control <?php if($pseudo_already_exist != ""){ echo 'border-danger';} ?>" id="pseudo" name="pseudo" value="<?php if(isset($_POST['pseudo'])){ echo $_POST['pseudo'];}?>">
            <?= $pseudo_already_exist ?>
        </div>
        <div class="mb-1 col-md-5">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control <?php if($email_already_exist != ""){ echo 'border-danger';} ?>" id="email" name="email" placeholder="Saisir votre adresse email" value="<?php if(isset($_POST['email'])){ echo $_POST['email'];}?>">
            <?= $email_already_exist ?>
        </div>
        <div class="mb-1 col-md-6">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control  <?php if($irequal_mdp != ""){ echo 'border-danger';} ?>" id="password" name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password'];}?>">
            <?= $irequal_mdp ?>
        </div>
        <div class="mb-1 col-md-6">
            <label for="confirm_password" class="form-label">Confirmer votre mot de passe</label>
            <input type="password" class="form-control <?php if($irequal_mdp != ""){ echo 'border-danger';} ?>" id="confirm_password" name="confirm_password" value="<?php if(isset($_POST['confirm_password'])){ echo $_POST['confirm_password'];}?>">
        </div>
        <div class="mb-1 col-md-6">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Saisir votre prénom" value="<?php if(isset($_POST['prenom'])){ echo $_POST['prenom'];}?>">
        </div>
        <div class="mb-1 col-md-6">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Saisir votre nom" value="<?php if(isset($_POST['nom'])){ echo $_POST['nom'];}?>">
        </div>
        <div class="mb-1 col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="gridCheck" name="pdc" value="accepted" <?php if(isset($_POST['pdc'])){ echo "checked";}?>>
                <label class="form-check-label" for="gridCheck">
                Accepter les <a href="" class="alert-link text-dark">politiques de confidentialité  <?= $pcd_accepted ?></a>  
                </label>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-dark">Continuer</button> <?= $all_input_err ?>
        </div>
    </form>
</main>
<?php
    require_once 'inc/footer.inc.php';
?>

