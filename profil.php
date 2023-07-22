<?php
  session_start(); // démarrage de la session
  include("fonction/fonctions.php"); // chargement des fonctions
  deconnexion(); // fonction de de déconnexion

  if(isset($_POST["supprimer"])) // Si l'utilisateur a cliqué sur le bouton de suppression d'un commentaire, alors...
  {
    suppression_ligne(connexionBDD("vert_galant", "root", ""), 4, $_POST["supprimer"]); // le commentaire est supprimé
  }
  // =========================================================
  // ========================= HEAD ==========================
  // =========================================================
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="language" content="fr" />
    <meta name="keywords" content="compte, gestion, profil, informations" />
    <?php
        // =================================================================================================================================
        // ====== VERIFICATION DE L'EXISTENCE DU COMPTE : l'ID CONTENU DANS $_GET["id_compte"] CORRESPOND T-IL A UN COMPTE EXISTANT ? ======
        // =================================================================================================================================
        $compte_existant = false; // on suppose que le compte n'existe pas
        if(isset($_GET["id_compte"]) && !is_null(recuperation_ligne(connexionBDD("vert_galant", "root", ""), 1, $_GET["id_compte"]))) // Si la variable $_GET["id_compte"] n'est pas vide et que ce qui est retourné via la fonction de récupération n'est pas vide, alors...
        {
          $compte = recuperation_ligne(connexionBDD("vert_galant", "root", ""), 1, $_GET["id_compte"]); // on sait qu'un compte existe correspondant à l'url de la page. on récupère ses informations
          $compte_existant = true; // on signale que le compte existe
        }
        if($compte_existant) // Le titre de la page dépend de l'existence d'un compte ou non
        {
          echo '<meta name="description" content="' . "profil de " . $compte["identifiant"] . '" />';
          echo "<title>" . "Profil de " . $compte["identifiant"] . "</title>";
        }
        else
        {
          echo '<meta name="description" content="page inexistante" />';
          echo "<title>La page demandée n'existe pas</title>";
        }
    ?>
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
      // ======================================================
      // ======= AFFICHAGE DU PROFIL DU COMPTE EXISTANT =======
      // ======================================================
      if($compte_existant) // Si la variable $_GET["id_compte"] est bien écrite et contient un id valide
      {
      ?>
        <!-- centre -->
        <section class="h-100 gradient-custom flex-grow-1">
          <div class="container mb-5">
            <h1 class="mt-5">Profil de <?php echo $compte["identifiant"] // affichage du titre de la page ?></h1>
            <hr>
            <div class="row">
              <div class="col-md-6 mx-auto">
                <div class="card border-primary">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div>
                        <img src="<?php echo recuperation_chemin(1, $compte["id_compte"]) // affichage de l'image de profil ?>" alt="Profile Picture" class="rounded-circle image_profil_grand">
                      </div>
                      <div class="ms-3">
                        <h4 class="card-title"><?php echo $compte["identifiant"]; // affichage de l'identifiant ?></h4>
                        <p class="card-text">Date d'inscription : <?php echo $compte["date_inscription"]; // affichage de la date d'inscription ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
                // ============================================
                // ======= AFFICHAGE DES AVIS DU COMPTE =======
                // ============================================
                ?>
                <h3 class="mt-4">Avis</h3>
                <?php $commentaires = recuperation_table(connexionBDD("vert_galant", "root", ""), 4, $_GET["id_compte"]); // récupération d'avis du profil ?>
                <ul class="list-group mt-2">
                  <?php
                  $nb_avis = 0; // Initialisation du nombre d'avis (aussi appelés commentaires)
                  foreach($commentaires as $commentaire)
                  {
                    if($commentaire["id_compte"] == $_GET["id_compte"]) // On vérifie pour chaque commentaire si il a été soumis par le compte du profil
                    {
                      $infos_livre = recuperation_ligne(connexionBDD("vert_galant", "root", ""), 2, $commentaire["id_livre"]); // récupération des informations du livre commenté
                      ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?php echo "livre.php?livre=" . $commentaire["id_livre"] . "#avis" ?>"><?php echo $infos_livre["titre"] // affichage du titre du livre et lien vers la page de celui-ci, pour voir l'avis?></a>
                        <?php
                          if(isset($_SESSION["id_compte"]))
                          {
                              if($_GET["id_compte"] == $_SESSION["id_compte"])
                              {
                        ?>
                        <form action="" method="post">
                          <button type="submit" value="<?php echo $commentaire["id_livre"] ?>" class="btn btn-danger btn-sm" name ="<?php echo "supprimer" ?>"><i class="bi bi-trash"></i></button>
                        </form>
                        <?php
                              }
                          }
                        ?>
                      </li>
                      <?php
                      $nb_avis++; // on incrémente le compteur d'avis pour chaque avis soumis par le compte
                    }
                  }
                  if($nb_avis == 0) // Si le compte n'a envoyé aucun avis...
                  {
                    echo "Aucun avis posté pour le moment"; // le message ci-contre s'affiche
                  }
                  ?>
                </ul>
              </div>
            </div>
          </div>
        </section>
      <?php
      }
      // ========================================================================================================================
      // ======= AFFICHAGE DANS LE CAS OU LA VARIABLE $_GET["id_compte"] n'est pas présente ou contient une fausse valeur =======
      // ========================================================================================================================
      else // puisqu'il n'y a pas de compte existant sur la page
      {
        page_erreur("La page demandée n'existe pas"); // s'affiche une page d'erreur
      }
      ?>
      <!-- pied de page -->
      <?php
      include("footer.php");
      ?>
    <!-- chargement du javascript -->
    <script src="js/bootstrap_bundle_min.js"></script>
  </body>
</html>
