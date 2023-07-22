<?php
    session_start(); // démarrage de la session
    include("fonction/fonctions.php"); // chargement des fonctions
    deconnexion(); // fonction de de déconnexion

    // =========================================================
    // ========================= HEAD ==========================
    // =========================================================
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="page d'accueil" />
        <meta name="language" content="fr" />
	    <meta name="keywords" content="accueil" />
        <title>Accueil</title>
        <!-- chargement des icônes -->
        <link rel="icon" type="image/x-icon" href="images/favicon.ico" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="images/fontawesome/css/fontawesome.css" rel="stylesheet">
        <link href="images/fontawesome/css/brands.css" rel="stylesheet">
        <link href="images/fontawesome/css/solid.css" rel="stylesheet">
        <!-- chargement des styles -->
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/bootstrap_min.css" rel="stylesheet" />
    </head>
    <body>
        <!-- navigation-->
        <?php
            include("nav.php");

            // =======================================================================
            // ======== RECUPERATION DES INFORMATIONS SUR LES DERNIERS LIVRES ========
            // =======================================================================
            $livres = recuperation_table(connexionBDD("vert_galant", "root", ""), 2); // récupération de la table des livres
            $derniers_livres = array_slice($livres, -8, 8, true); // filtrage sur les 8 derniers livres ajoutés au site internet, en préservant les clés du tableau original
            $derniers_livres = array_reverse($derniers_livres); // inversion du filtrage obtenu, pour que le dernier livre obtenu soit celui affiché en haut à gauche sur la page

            // =========================================
            // ======== CAROUSEL DES NOUVEAUTÉS ========
            // =========================================
        ?>
        <!-- carousel des nouveautés-->
        <header class="container px-4 px-lg-5 mt-3">
            <div id="carousel" class="carousel slide w-100 carousel-max-width" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                <indication data-bs-target="#carousel" data-bs-slide-to="0" class="active"></indication>
                <indication data-bs-target="#carousel" data-bs-slide-to="1"></indication>
                <indication data-bs-target="#carousel" data-bs-slide-to="2"></indication>
                <indication data-bs-target="#carousel" data-bs-slide-to="3"></indication>
                <indication data-bs-target="#carousel" data-bs-slide-to="4"></indication>
                <indication data-bs-target="#carousel" data-bs-slide-to="5"></indication>
            </ol>
            <div class="carousel-inner h-100">

                <div class="carousel-item active h-100">
                    <a href="livre.php?livre=10"><img src="images/index/1.jpg" alt="Image 1" class="d-block w-100 h-100"></a>
                    <div class="carousel-caption">
                    </div>
                </div>
                
                <div class="carousel-item h-100">
                <a href="actualites.php#4"><img src="images/index/4.jpg" alt="Image 2" class="d-block w-100 h-100"></a>
                    <div class="carousel-caption">
                    </div>
                </div>
             
                <div class="carousel-item h-100">
                <a><img src="images/index/7.jpg" alt="Image 3" class="d-block w-100 h-100"></a>
                    <div class="carousel-caption">
                    </div>
                </div>
                
                <div class="carousel-item h-100">
                <a><img src="images/index/6.jpg" alt="Image 4" class="d-block w-100 h-100"></a>
                    <div class="carousel-caption">
                    </div>
                </div>
                
                <div class="carousel-item h-100">
                <a><img src="images/index/8.jpg" alt="Image 5" class="d-block w-100 h-100"></a>
                    <div class="carousel-caption">
                    </div>
                </div>
                
                <div class="carousel-item h-100">
                <a href="actualites.php#5"><img src="images/index/5.jpg" alt="Image 5" class="d-block w-100 h-100"></a>
                    <div class="carousel-caption">
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carousel" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
            </div>
        </header>

        <!-- section des dernières livres sortis-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-3">
                 <h2 class="text-center mb-4 fw-bolder">Dernières sorties</h2>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <?php
                        // ======================================================
                        // ======== AFFICHAGE DES DERNIERS LIVRES SORTIS ========
                        // ======================================================
                        foreach ($derniers_livres as $livre) // Pour chaque livre...
                        {
                            ?>
                                <div class="col mb-5">
                                    <div class="card h-100">
                                        <a href="livre.php?livre=<?php echo $livre["id_livre"] // lien de redirection vers la page du livre ?>">
                                            <?php $chemin = recuperation_chemin(2, $livre["id_livre"]); // récupération du chemin de l'image du livre ?>
                                            <img class="card-img-top image_livre_accueil border" src="<?php echo $chemin ?>" alt="<?php echo $livre["titre"] // affichage de l'image du livre ?>">
                                        </a>
                                        <div class="card-body p-4">
                                            <div class="text-center">
                                                <a class="text-decoration-none text-dark" href="livre.php?livre=<?php echo $livre["id_livre"] ?>"><h5 class="fw-bolder titre_livre_accueil"><?php echo $livre["titre"] // titre du livre ?></h5></a>
                                                <?php
                                                    $livre["prix"] = number_format($livre["prix"], 2, '.', ''); // formatage du prix du livre 
                                                    echo $livre["prix"] . " €" // affichage du livre avec la devise €;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </section>
        <!-- pied de page -->
        <?php
            include("footer.php");
        ?>
        <!-- chargement du javascript -->
        <script src="js/bootstrap_bundle_min.js" async></script>
        <script src="js/jquery-3_7_0.js" async></script>
    </body>
</html>
