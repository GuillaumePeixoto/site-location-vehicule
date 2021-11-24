<?php
//Fonction Internaute connecter
function connect(){
    if(!isset($_SESSION['membre']))
    {
        return false;
    }
    else
    {
        return true;
    }
}

//Fonction Administrateur Connecte
function adminConnect()
{
    if(connect() && $_SESSION['membre']['statut'] == 'admin')
    {
        return true;
    }
    else
    {
        return false;
    }

}

// FONCTION CREATION DE PANIER DANS LA SESSION
function createPanier()
{
    if(!isset($_SESSION['panier']))
    {
        $_SESSION['panier'] = [];
        $_SESSION['panier']['id_vehicule'] = [];
        $_SESSION['panier']['photo'] = [];
        $_SESSION['panier']['titre'] = [];
        $_SESSION['panier']['prix_journalier'] = [];
        $_SESSION['panier']['date_debut'] = [];
        $_SESSION['panier']['date_fin'] = [];
    }
}

function addPanier($id_vehicule, $photo, $titre, $prix_journalier, $date_debut, $date_fin)
{
    // On vérifie si le panier est crée dans la session ou pas
    createPanier();
    $positionProduit = array_search($id_vehicule, $_SESSION['panier']['id_vehicule']);

    if($positionProduit !== false)
    {
        return "Vous avez déja ajouter ce vehicule.";
    }
    else
    {
        $_SESSION['panier']['id_vehicule'][] = $id_vehicule;
        $_SESSION['panier']['photo'][] = $photo;
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['prix_journalier'][] = $prix_journalier;
        $_SESSION['panier']['date_debut'][] = $date_debut;
        $_SESSION['panier']['date_fin'][] = $date_fin;
    }

}
    /*
        Exemple :
        ['panier'] => {
            ['id_vehicule'] => array(
                0 => 1,
                1 => 12,
                2 => 56
            ),
            ['photo'] => array(
                0 => lien 1,
                1 => lien 2,
                2 => lien 3,
            )

    */
function montantTotal()
{
    $total = 0.00;
    $nb_prod = count($_SESSION['panier']['id_vehicule']);
    for($i = 0; $i < $nb_prod; $i++)
    {
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
    }
    return $total;
}

function deletePanier($id_vehicule)
{
    $positionProduit = array_search($id_vehicule, $_SESSION['panier']['id_vehicule']);

    if($positionProduit !== false)
    {
        array_splice($_SESSION['panier']['id_vehicule'], $positionProduit, 1);
        array_splice($_SESSION['panier']['photo'], $positionProduit, 1);
        array_splice($_SESSION['panier']['titre'], $positionProduit, 1);
        array_splice($_SESSION['panier']['quantite'], $positionProduit, 1);
        array_splice($_SESSION['panier']['prix'], $positionProduit, 1);
        array_splice($_SESSION['panier']['stock'], $positionProduit, 1);
    }
    quantitePanier();
}


?>