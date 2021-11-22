<?php

$all_input_err = "";
$irequal_mdp = "";
$pcd_accepted = "";
$email_already_exist = "";
$pseudo_already_exist = "";


if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    unset($_SESSION['membre']);
}

if(isset($_POST['connexion']))
{

    if(isset($_POST['pseudo_email'], $_POST['password']))
    {
        $get_bdd_membre = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
        $get_bdd_membre->bindValue(":pseudo", $_POST['pseudo_email'], PDO::PARAM_STR);
        $get_bdd_membre->bindValue(":email", $_POST['pseudo_email'], PDO::PARAM_STR);
        $get_bdd_membre->execute();

        if($get_bdd_membre->rowCount()) // rowCount() retourne le nombre de résultats suite à la requete SQL
        {
            // echo "Pseudo / email existant en BDD";
            $membre = $get_bdd_membre->fetch(PDO::FETCH_ASSOC);
            //Comparaison des mots de passe
            //Si les mots de passe sont crypté en BDD, nous pouvons les vérifiéer avec la fonction :
            // password_verify() : fonction prédéfinie permettant de comparer une clé de hachage à une chaine de caractères
            //Si le mot de passe saisie dans le formulaire correspond à la clé de hachage stockée en BDD, on entre dans la condition IF
            if(password_verify($_POST['password'], $membre['mdp']))
            {
                // On entre dans la condition seulement dans le cas ou l'internaute a saisi le bom email/pseudo et le bon mot de passe, donc il a saisi les bons identifiants
                $_SESSION['membre'] = array();
                foreach($membre as $key => $value)
                {
                    if($key != 'mdp')
                    {
                        $_SESSION['membre'][$key] = $value;
                    }
                    // la SESSION est accessible depuis n'importe ou dans le site, ce qui permet d'être authentifié sur n'importe quelle page du site
                }
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
}
elseif(isset($_POST['inscription']))
{
    if(isset($_POST['civilite']) && isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['email']) && isset($_POST['prenom']) && isset($_POST['nom']))   
    {
        if( $_POST['password'] !== $_POST['confirm_password'] )
        {
            $irequal_mdp = "<span class='text-danger fw-bold position-absolute mt-1'>Les mots de passe ne sont pas identiques</span>";
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
                $email_already_exist = '<span class="text-danger fw-bold position-absolute mt-1">Cette adresse est déja utilisé</span>';
            }
            elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $email_already_exist = '<span class="text-danger fw-bold position-absolute mt-1">Adresse mail incorrect</span>';
            }
        }
        if(!empty($_POST['pseudo']))
        {
            $requete="select * from membre where pseudo = :pseudo;";
            $donnees=array(":pseudo"=>$_POST['pseudo']);
			$select= $bdd->prepare($requete);
			$select->execute($donnees);
			$pseudo_exist=$select->fetch();
            if($pseudo_exist != false)
            {
                $pseudo_already_exist = '<span class="text-danger fw-bold position-absolute mt-1">Ce pseudo est déja utilisé</span>';
            }
        }
        
        if(!isset($_POST['pdc']))
        {
            $pcd_accepted = "<span class='text-danger fw-bold mt-1'>Veuillez accepter les politiques de confidentialité </span>";
        }

        if(!empty($_POST['civilite']) && !empty($_POST['pseudo']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['email']) && !empty($_POST['prenom']) && !empty($_POST['nom']) && $_POST['pdc'] == 'accepted')  
        {
            $all_input_err = "";

            $_POST['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            // password_hash : fonction prédéfinie permettant de créer une clé de hachage pour le mot de passe dans la BDD
            // arguments : password_hash(mot_de_passe, TYPE DE CRYPTAGE);
            $requete = $bdd->prepare("insert into membre values(null,:pseudo,:password,:nom,:prenom,:email,:civilite,'client',NOW())");
            $requete->bindValue(':pseudo',$_POST['pseudo'],PDO::PARAM_STR);
            $requete->bindValue(':password',$_POST['password'],PDO::PARAM_STR);
            $requete->bindValue(':nom',$_POST['nom'],PDO::PARAM_STR);
            $requete->bindValue(':prenom',$_POST['prenom'],PDO::PARAM_STR);
            $requete->bindValue(':email',$_POST['email'],PDO::PARAM_STR);
            $requete->bindValue(':civilite',$_POST['civilite'],PDO::PARAM_STR);
            $requete->execute();

            $_SESSION['validation_inscription'] = "Félicitations ! Vous êtes mainteanant inscrit ! Vous pouvez dès à présent vous connecter !";


            $get_bdd_membre = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
            $get_bdd_membre->bindValue(":pseudo", $_POST['pseudo'], PDO::PARAM_STR);
            $get_bdd_membre->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
            $get_bdd_membre->execute();

            if($get_bdd_membre->rowCount()) // rowCount() retourne le nombre de résultats suite à la requete SQL
            {
                $membre = $get_bdd_membre->fetch(PDO::FETCH_ASSOC);
                if($_POST['password'] == $membre['mdp'])
                {
                    $_SESSION['membre'] = array();
                    foreach($membre as $key => $value)
                    {
                        if($key != 'mdp')
                        {
                            $_SESSION['membre'][$key] = $value;
                        }
                    }
                }
            }
        }
        else
        {
            $all_input_err = "<span class='text-danger ms-2 fw-bold align-middle'>Veuillez remplir tous les champs</span>";            
        }
    }
}
?>