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
        <meta name="description" content="actualite de la librairie" />
        <meta name="language" content="fr" />
	    <meta name="keywords" content="actualite, informations" />
        <title>Actualité</title>
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
    <body class="d-flex flex-column min-vh-100">
        <!-- navigation-->
        <?php
            include("nav.php");
        ?>
        <!-- centre -->
        <section class="container my-5 h-100 gradient-custom flex-grow-1">
            <div class="container">
                <?php
                    // =========================================================
                    // ========= CHARGEMENT ET AFFICHAGE DES ACTUALITÉS=========
                    // =========================================================
                    $actualites = recuperation_table(connexionBDD("vert_galant", "root", ""), 5);
                    foreach($actualites as $actualite)
                    {
                    ?>
                    <div>
                        <h2 class="mb-4" id="<?php echo $actualite["id_actualite"] ?>"><?php echo $actualite["titre"] // titre de l'actualité, et id pour que l'on y accède facilement depuis d'autres pages ?></h2>
                        <p class="lead"><?php echo $actualite ["texte"] // contenu de l'actualité ?></p>
                    </div>
                    <?php
                    }
                ?>
            </div>
        </section>
        <!-- pied de page -->
        <?php
            include("footer.php");
        ?>
        <!-- chargement du javascript -->
        <script src="js/bootstrap_bundle_min.js"></script>
    </body>
</html>