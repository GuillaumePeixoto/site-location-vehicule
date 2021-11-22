<?php
    require_once 'inc/init.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'inc/nav.inc.php';

    $products = $bdd->prepare('select * from vehicule');
    $products->execute();
    $prods = $products->fetchAll(PDO::FETCH_ASSOC);


?>
<div class="container">
    <h1 class="text-center my-5">Nos voitures</h1>

    <p class="my-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus, labore. Dolor voluptatem nobis ea deleniti, sit possimus eligendi iure recusandae rem eius. Doloribus delectus quas, tempore rem laboriosam nesciunt pariatur velit, illum sint, necessitatibus ea eaque provident. Cupiditate alias repellat aliquid veniam quibusdam corrupti, non odit asperiores illo eligendi necessitatibus! Fugiat quo in provident minus ullam praesentium natus amet sequi delectus quia incidunt beatae rem, labore quisquam pariatur accusantium exercitationem enim suscipit consequatur dolorum animi commodi saepe? Eos quas, aliquid blanditiis officia ipsum natus ea. Porro officiis qui totam unde dignissimos nesciunt repudiandae possimus numquam pariatur placeat! Magnam et aperiam hic officiis? Veniam, laborum voluptate nemo, qui tempore voluptates sed at, suscipit facere sint totam eos beatae nam aperiam molestiae! Asperiores non officia cupiditate itaque sapiente fuga earum illo quibusdam? Adipisci quia aliquid laboriosam saepe, dignissimos eos expedita molestiae quaerat nisi quae ratione provident, optio ad. Recusandae iure hic culpa!</p>

        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <?php
                foreach($prods as $product)
                {
                    if(strlen($product['description']) > 50)
                    {
                        $description = substr($product['description'], 0, 50)."...";
                    }
                    else
                    {
                        $description = $product['description'];
                    }
                    echo "<div class='col-4 d-flex'>
                            <div class='card shadow-sm rounded my-auto h-100 w-100'>
                                <a href='fiche_produit.php?id_vehicule=$product[id_vehicule]' class='h-75 d-flex'><img src='$product[photo]' class='card-img-top my-auto img-fluid' style='max-height: 100%' alt='$product[titre]'></a>
                                <div class='card-body d-flex flex-column justify-content-center w-100'>
                                    <h5 class='card-title text-center'><a href='fiche_produit.php?id_vehicule=$product[id_vehicule]' class='alert-link text-dark titre-produit-boutique'>$product[titre]</a></h5>
                                    <p class='card-text  text-center'>$description</p>
                                    <p class='card-text fw-bold  text-center'>$product[prix_journalier] € / Jour</p>
                                    <p class='card-text text-center'><a href='fiche_produit.php?id_vehicule=$product[id_vehicule]' class='btn btn-outline-dark'>En savoir plus</a></p>
                                </div>
                            </div>
                        </div>";

                }
            ?>  
        </div>
</div>
<?php
    require_once 'inc/footer.inc.php';
?>
 