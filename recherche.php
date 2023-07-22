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
    <meta name="description" content="recherche de livres" />
    <meta name="language" content="fr" />
    <meta name="keywords" content="livre, achats, information" />
    <title>Recherche de livre</title>
    <!-- chargement des icônes -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="images/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="images/fontawesome/css/brands.css" rel="stylesheet">
    <link href="images/fontawesome/css/solid.css" rel="stylesheet">
    <!-- chargement des styles -->
    <link href="css/bootstrap_min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
      .image_recherche {
      object-fit: contain;
      max-height: 200px;
      width: 100%;
      }
    </style>
  </head>
  <body class="d-flex flex-column min-vh-100">
    <!-- navigation-->
    <?php
      include("nav.php");
      
      // ==================================================================
      // ========== CAS OU LA VARIABLE $_GET["categorie"] EXISTE ==========
      // ==================================================================
      if(isset($_GET["categorie"]) && ($_GET["categorie"] == "Roman ou nouvelles" || $_GET["categorie"] == "Poésie" || $_GET["categorie"] == "Théâtre" ||
      $_GET["categorie"] == "Essai" || $_GET["categorie"] == "Philosophie" || $_GET["categorie"] == "Art" ||
      $_GET["categorie"] == "Histoire" || $_GET["categorie"] == "Musique" || $_GET["categorie"] == "Littérature scientifique" ||
      $_GET["categorie"] == "Autre")) // Si l'une des catégories est comprise dans la variable $_GET["categorie"], on charge tous les livres de cette catégorie
      {
        ?>
          <!-- centre -->
          <section class="h-100 gradient-custom flex-grow-1">
            <div class="container">
              <h1 class="mt-5">Livres correspondant à la catégorie <?php echo $_GET["categorie"] // titre de la page ?></h1>
              <div class="row mt-4">
                <?php
                  $livres = recuperation_table(connexionBDD("vert_galant", "root", ""), 2); // récupération de la table des livres
                  foreach ($livres as $livre)
                  {
                    if ($livre["categorie"] == $_GET["categorie"]) // on vérifie si la catégorie de chaque livre est celle de la variable $_GET["categorie"]. Si oui, on va l'afficher
                    {
                    ?>
                      <div class="col-md-3">
                        <div class="card mb-3">
                          <div class="card-body">
                            <h5 class="card-title"><?php echo $livre["titre"] // titre du livre ?></h5>
                            <div class="col-md-1 image_recherche mb-3">
                              <a href=<?php echo "livre.php?livre=" . $livre["id_livre"] // lien vers la page du livre ?>>
                                <img src="<?php echo recuperation_chemin(2, $livre["id_livre"]) // affichage de l'image du livre ?>" class="image_recherche" style="height: 200px;" alt="<?php $livre["titre"] ?>">
                              </a>
                            </div>
                            <?php
                              if (strlen($livre["description"]) > 200) // Si la description du livre fait plus de 200 caractères, alors...
                              {
                                  $livre["description"] = substr($livre["description"], 0, 197) . "..."; // on la raccourcie en lui prélevant 197 auxquels on ajoute des points de suspension...
                              }
                              ?>
                            <p class="card-text"><?php echo $livre["description"] // affichage de la description ?></p>
                            <a href="<?php echo "livre.php?livre=" . $livre["id_livre"] // bouton vers la page du livre?>" class="btn btn-success">Voir le livre</a>
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                  }
                  ?>
              </div>
            </div>
          </section>
        <?php
      }
      // ===============================================================
      // ========== CAS OU LA VARIABLE $_GET["filtre"] EXISTE ==========
      // ===============================================================
      else if(isset($_GET["filtre"]))
      {
        ?>
          <!-- centre -->
          <section class="h-100 gradient-custom flex-grow-1">
            <div class="container">
              <h1 class="mt-5">Livres correspondant à la recherche <?php echo "«" . $_GET["filtre"] . "»" // titre de la page ?></h1>
              <div class="row mt-4">
                <?php
                  $livres = recuperation_table(connexionBDD("vert_galant", "root", ""), 2); // récupération de la table des livres
                  foreach($livres as $livre) // Pour chaque livre on récupère des informations de type chaîne de caractère, que l'on passe en minuscule, pour les comparer au contenu de la variable $_GET["filtre"], lui aussi passé en minuscule
                  {
                    $filtre = strtolower($_GET["filtre"]);
                    $mots_cles = strtolower(($livre["mots_cles"]));
                    $titre = strtolower($livre["titre"]);
                    $auteur = strtolower($livre["auteur"]);
                    $categorie = strtolower($livre["categorie"]);
                    if(str_contains($titre, $filtre) || str_contains($auteur, $filtre) || str_contains($mots_cles, $filtre) || str_contains($categorie, $filtre)) // Si le titre, l'auteur, un des mots-clés ou une catégorie du livre est compris dans le filtre, on affiche le livre
                    {
                      if(strlen($livre["titre"]) > 28) // Si le titre du livre fait plus de 28 caractères...
                      {
                          $livre["titre"] = substr($livre["titre"],0,25) . "..."; // On le raccourcie à 25 et on y ajoute des points de suspension
                      }
                      ?>
                        <div class="col-md-3">
                          <div class="card mb-3">
                            <div class="card-body">
                              <h5 class="card-title"><?php echo $livre["titre"] // affichage du titre du livre?></h5>
                              <div class="col-md-1 image_recherche mb-3">
                                <a href=<?php echo "livre.php?livre=" . $livre["id_livre"] // lien vers la page du livre?>>
                                <img src="<?php echo recuperation_chemin(2, $livre["id_livre"]) // affichage de l'image du livre?>" class="image_recherche" style="height: 200px;" alt="<?php $livre["titre"] ?>">
                              </a>
                              </div>
                              <?php
                                if (strlen($livre["description"]) > 200)
                                {
                                    $livre["description"] = substr($livre["description"], 0, 197) . "...";
                                }
                                ?>
                              <p class="card-text"><?php echo $livre["description"] ?></p>
                              <a href="<?php echo "livre.php?livre=" . $livre["id_livre"]?>" class="btn btn-success">Voir le livre</a>
                            </div>
                          </div>
                        </div>
                      <?php
                    }
                  }
                  ?>
              </div>
            </div>
          </section>
        <?php
      }
      // ==============================================================================================
      // ===== CAS OU NI LA VARIABLE $_GET["categorie"] NI LA VARIABLE $_GET["filtre"] N'EXISTENT =====
      // ==============================================================================================
      else 
      {
          page_erreur("La page demandée n'existe pas");
      }
        ?>
    <!-- pied de page -->
    <?php
      include("footer.php");
      ?>
    <!-- chargement du javascript -->
    <script src="js/bootstrap_bundle_min.js" async></script>
  </body>
</html>