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
        $_SESSION['panier']['id_article'] = [];
        $_SESSION['panier']['photo'] = [];
        $_SESSION['panier']['titre'] = [];
        $_SESSION['panier']['quantite'] = [];
        $_SESSION['panier']['prix'] = [];
        $_SESSION['panier']['stock'] = [];
    }
}

function quantitePanier()
{
    if(isset($_SESSION['panier']))
    {
        $nb_prod = count($_SESSION['panier']['id_article']);
        $_SESSION['total_prod'] = 0;
        for($i = 0; $i < $nb_prod; $i++)
        {
            $_SESSION['total_prod'] += $_SESSION['panier']['quantite'][$i];
        }
    }else
    {
        $_SESSION['total_prod'] = 0;
    }
    
}


function addPanier($id_article, $photo, $titre, $quantite, $prix, $stock)
{
    // On vérifie si le panier est crée dans la session ou pas
    createPanier();
    $positionProduit = array_search($id_article, $_SESSION['panier']['id_article']);

    if($positionProduit !== false)
    {
        if( $_SESSION['panier']['quantite'][$positionProduit] + $quantite < $stock)
        {
            $_SESSION['panier']['quantite'][$positionProduit] += $quantite;
        }
        else
        {
            $_SESSION['panier']['quantite'][$positionProduit] = $_SESSION['panier']['stock'][$positionProduit];
            $_SESSION['not_enough_quantity'] = "<div class='d-flex'><span class='badge bg-danger text-center fs-4 text-white p-4 mx-auto'>Il n'y a pas assez de quantité pour ". $_SESSION['panier']['titre'][$positionProduit].". nous vous avons donc selectionné l'entièreté du stock disponible</span></div>";
        }
        
    }
    else
    {
        $_SESSION['panier']['id_article'][] = $id_article;
        $_SESSION['panier']['photo'][] = $photo;
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;
        $_SESSION['panier']['stock'][] = $stock;
    }

    quantitePanier();
}
    /*
        Exemple :
        ['panier'] => {
            ['id_article'] => array(
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
    $nb_prod = count($_SESSION['panier']['id_article']);
    for($i = 0; $i < $nb_prod; $i++)
    {
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
    }
    return $total;
}

function deletePanier($id_article)
{
    $positionProduit = array_search($id_article, $_SESSION['panier']['id_article']);

    if($positionProduit !== false)
    {
        array_splice($_SESSION['panier']['id_article'], $positionProduit, 1);
        array_splice($_SESSION['panier']['photo'], $positionProduit, 1);
        array_splice($_SESSION['panier']['titre'], $positionProduit, 1);
        array_splice($_SESSION['panier']['quantite'], $positionProduit, 1);
        array_splice($_SESSION['panier']['prix'], $positionProduit, 1);
        array_splice($_SESSION['panier']['stock'], $positionProduit, 1);
    }
    quantitePanier();
}


?>