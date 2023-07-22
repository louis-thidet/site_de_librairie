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
    <meta name="language" content="fr" />
    <meta name="keywords" content="livre, achats, information" />
    <?php
        // ===========================================================================================================================
        // ====== VERIFICATION DE L'EXISTENCE DU LIVRE : l'ID CONTENU DANS $_GET["livre"] CORRESPOND T-IL A UN LIVRE EXISTANT ? ======
        // ===========================================================================================================================
        $livre_existant = false; // on suppose que le compte n'existe pas
        if(isset($_GET["livre"]) && !is_null(recuperation_ligne(connexionBDD("vert_galant", "root", ""), 2, $_GET["livre"]))) // Si la variable $_GET["livre"] existe et n'est pas vide, et que ce qui est retourné via la fonction de récupération n'est pas vide, alors...
        {
          $livre = recuperation_ligne(connexionBDD("vert_galant", "root", ""), 2, $_GET["livre"]); // on sait qu'un livre existe correspondant à l'url de la page. on récupère ses informations
          $livre_existant = true; // on signale que le compte existe
        }
        if($livre_existant) // Le titre de la page dépend de l'existence d'un compte ou non
        {
          echo "<meta name='description' content='page du livre " . $livre["titre"] . "' />";
          echo "<title>" . $livre["titre"] . "</title>";

          // ====================================================================
          // ========================= AJOUT AU PANIER ==========================
          // ====================================================================
          if(isset($_POST[$livre["id_livre"]]) && isset($_POST["quantite"])) // Cela signifie que l'utilisateur a cliqué sur ajouter au panier
          {
            if($_POST["quantite"] != 0) // S'il a entré 0 dans la case quantité, on ne poursuit pas l'opération
            {
              if(isset($_SESSION["_" . $livre["id_livre"]])) // Si une variable $_SESSION appelée _ . $livre["id_livre"] existe, c'est que l'utilisateur a déjà ajouté au panier le livre, mais qu'il en ajoute 1 ou plusieurs autres exemplaires au panier
              {
                $_SESSION["_" . $livre["id_livre"]] += $_POST["quantite"]; // La quantité du livre mise dans le panier est contenue dans la variable, et elle est mise à jour
              }
              else // Si l'utilisateur n'avait pas déjà le livre dans le panier...
              {
                $_SESSION["_" . $livre["id_livre"]] = $_POST["quantite"]; // Alors une variable est créée prenant la quantité qu'il vient d'entrer en valeur
              }
            }
          }
          // Remarque : les autres conditions liées aux actions de l'utilisateur sont situées après l'inclusion de la barre de navigation, ce qui les rend plus lisibles.
          // Ce n'était pas possible pour la condition d'ajout au panier, parce qu'autrement l'affichage du premier ajout d'un livre ne s'effectue qu'après un rechargement de la page
        }
        else
        {
          echo "<meta name='description' content='page inexistante' />";
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
    <!-- navigation -->
    <?php
        include("nav.php"); // chargement de la barre de navigation

        // ======================================================
        // ======= AFFICHAGE DE LA PAGE DU LIVRE EXISTANT =======
        // ======================================================
        if($livre_existant) // Si cette variable existe, et donc que la variable $_GET["livre"] existe et contient l'id d'un livre qui existe...
        {

          // =================================================================================
          // ========================= AJOUT A LA LISTE DE SOUHAITS ==========================
          // =================================================================================
          if(isset($_POST["souhait" . $livre["id_livre"]])) // Si une telle variable existe, cela signifie que l'utilisateur a appuyé sur le bouton d'ajout à la liste de souhaits
          {
            ajout_liste_souhaits(connexionBDD("vert_galant", "root", ""), $livre["id_livre"], $_SESSION["id_compte"]); // On ajoute le livre à la liste de souhaits de l'utilisateur connecté, via les id
          }
          // ===========================================================================
          // ========================= AJOUT D'UN COMMENTAIRE ==========================
          // ===========================================================================
          if(isset($_POST["commentaire" . $livre["id_livre"]])) // Si cette variable existe, cela signifie que l'utilisateur a appuyé sur le bouton pour envoyer son avis sur le livre
          {
            $commentaires = recuperation_table(connexionBDD("vert_galant", "root", ""), 4); // récupération de la table des commentaires
    
            // Filtrage du tableau $commentaires pour ne retenir que les commentaires liés au livre de la page
            $id_livre = $livre["id_livre"];
            $commentaires = array_filter($commentaires, function ($filtrage) use ($id_livre)
            {
                return $filtrage["id_livre"] == $id_livre;
            });

            // Vérification de si l'utilisateur a déjà commenté ou non sur le livre. S'il a déjà commenté, il ne peut pas envoyer un autre commentaire le concernant
            $deja_commente = false; // on suppose qu'il n'a pas commenté
            foreach($commentaires as $commentaire) // Pour chaque commentaire
            {
              if($commentaire["id_compte"] == $_SESSION["id_compte"]) // on vérifie si l'id du posteur est identique à celui de l'utilisateur connecté
              {
                $deja_commente = true; // S'il l'est, alors l'utilisateur a déjà commenté
              }
            }
            if(!$deja_commente) // Si l'utilisateur n'a pas déjà commenté, alors le commentaire qu'il a écrit est entré dans la base de données
            {
              ajout_commentaire(connexionBDD("vert_galant", "root", ""), $livre["id_livre"], $_SESSION["id_compte"], $_POST["commentaire"]); // La fonction d'ajout de commentaire prend à la fois en arguments l'id du livre et l'id du compte parce qu'ils forment à eux-deux la clé primaire du commentaire
            }
        }
        // ===========================================================================
        // ====================== SUPPRESSION D'UN COMMENTAIRE =======================
        // ===========================================================================
        if(isset($_SESSION["id_compte"]) && isset($_POST["supprimer" . $_SESSION["id_compte"]])) // Si une telle variable existe, cela signifie que l'utilisateur a appuyé sur le bouton de suppression du commentaire
        {
          $requete = "DELETE FROM commentaires WHERE id_compte = :id_compte AND id_livre = :id_livre";
          $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
          $requete->execute(array("id_compte" => $_SESSION["id_compte"],
                                  "id_livre" => $livre["id_livre"]));
        }

        // =========================================================================================
        // ============================= AFFICHAGE DE LA PAGE DU LIVRE =============================
        // =========================================================================================

            // ===========================================================================
            // ================== SECTION DES INFORMATIONS SUR LE LIVRE ==================
            // ===========================================================================
        ?>
          <!-- centre -->
          <section class="py-5">
            <div class="container px-4 px-lg-5">
              <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6 text-center">
                  <img class="card-img-top image_livre shadow-lg" src="<?php echo recuperation_chemin(2, $livre["id_livre"]) // affichage de l'image du livre ?>" alt="<?php echo $livre["titre"] ?>">
                </div>
                <div class="col-md-6">
                  <h3 class="display-6 mt-3 text-success"><?php echo $livre["auteur"] // affichage de l'auteur du livre ?></h3>
                  <h1 class="display-6 fw-bolder mt-2"><?php echo $livre["titre"] // affichage du titre du livre ?></h1>
                  <div class="fs-5 mb-4">
                    <span>
                    <?php
                      $livre["prix"] = number_format($livre["prix"], 2, '.', ''); // On formate l'image pour que le prix soit correctement affiché
                      echo $livre["prix"] . " €"
                      ?>
                    </span>
                  </div>
                  <p class="lead " style="font-size: calc(1.1rem );"><?php echo $livre["description"] // affichage de la description du livre ?></p>
                  <form action="<?php echo "livre.php?livre=" . $livre["id_livre"] ?>" method="post">
                    <div class="d-flex">
                      <input class="form-control text-center me-3" id="inputQuantity" type="num" name="quantite" value="1" style="max-width: 3rem" />
                      <div class="d-flex flex-wrap">
                        <button class="btn btn-outline-success flex-shrink-0 me-2 mb-2" type="submit" name="<?php echo $livre["id_livre"] // bouton d'ajout au panier?>">
                        <i class="bi-cart-fill me-1"></i>
                        Ajouter au panier
                        </button>
                  </form>
                  <?php
                    if(isset($_SESSION["id_compte"]))  // Si l'utilisateur est connecté, le bouton d'ajout à la liste de souhait le fait rester sur la page, et le livre va s'ajouter à sa liste de souhaits
                    {
                      $lien = "";
                    }
                    else // tandis que si l'utilisateur n'est pas connecté, le bouton d'ajout à la liste de souhaits le redirige vers la page de connexion/inscription
                    {
                      $lien = "login.php?inscription";
                    }
                  ?>
                  <form action="<?php echo $lien ?>" method="post">
                        <button class="btn btn-outline-danger flex-shrink-0 me-2 mb-2" type="submit" name="<?php echo "souhait" . $livre["id_livre"] // bouton d'ajout à la liste de souhaits ?>">
                        <i class="bi-cart-fill me-1"></i>
                        Ajouter à la liste de souhaits
                        </button>
                      </div>
                    </div>
                  </form>
                  <div class="specs-box border p-3 mt-3">
                    <h4 class="fw-bold mb-3">Détails du produit</h4>
                    <div class="mb-2"><strong>Editeur :</strong> <?php echo $livre["editeur"]?></div>
                    <div class="mb-2"><strong>Dimensions et poids :</strong> <?php echo $livre["largeur"]?> x <?php echo $livre["epaisseur"]?> x <?php echo $livre["longueur"] . " cm (" . $livre["poids"] . " g)"?></div>
                    <div class="mb-2"><strong>Nombre de pages et format :</strong> <?php echo $livre["nb_pages"] . " pages (" . $livre["format"] . ")" ?></div>
                    <div class="mb-2"><strong>ISBN :</strong> <?php echo $livre["ISBN"]?></div>
                  </div>
                </div>
              </div>
            </div>
            <?php
            // ===========================================================================
            // ================== SECTION DES COMMENTAIRES SUR LE LIVRE ==================
            // ===========================================================================
            ?>
            <div class="container mt-3">
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <div class="row gx-4 gx-lg-5 align-items-center">
                    <div class="col-md-6 text-center">
                    </div>
                    <div class="col-md-6">
                    </div>
                  </div>
                  <div class="text-center">
                    <button class="btn btn-success mt-4" type="button" data-bs-toggle="collapse" data-bs-target="#commentariesCollapse" id="avis"> <!-- Affichage des avis -->
                    Afficher les avis
                    </button>
                  </div>
                  <div id="commentariesCollapse" class="collapse mt-4">
                    <h3>Avis</h3>
                    <?php
                      $commentaires = recuperation_table(connexionBDD("vert_galant", "root", ""), 4); // récupération de la table des commentaires
                      $id_livre = $livre["id_livre"]; // filtrage sur l'id du livre de la page pour n'afficher que les commentaires liés à celui-ci
                      $commentaires = array_filter($commentaires, function ($filtrage) use ($id_livre)
                      {
                          return $filtrage["id_livre"] == $id_livre;
                      });

                      if(!empty($commentaires)) // On vérifie que la base de données comprend des commentaires. Si le résultat de la condition n'est pas null, elle en comprend bien
                      {
                        $nb_commentaires = count($commentaires); // On compte le nombre de commentaires existant
                        $i = 1;
                        foreach ($commentaires as $commentaire) // On affiche les informations de chaque commentaire
                        {
                          echo '<div>';
                          $posteur = recuperation_ligne(connexionBDD("vert_galant", "root", ""), 1, $commentaire["id_compte"]); // récupération des informations du posteur du commentaire

                          $chemin = recuperation_chemin(1, $commentaire["id_compte"]); // recherche d'une image de profil
                          
                          echo "<form method='post' action=''>"; // formulaire du bouton de suppression du commentaire

                          // affichage du commentaire
                          echo '<img class="image_profil_nav rounded-circle" src="' . $chemin .'" alt="image de profil">' . "&nbsp;&nbsp;" . "<a class='text-decoration-none text-success' href='profil.php?id_compte=" . $posteur["id_compte"]  . "'>" . $posteur["identifiant"] . "</a>" . " le ". $commentaire["date"];
                          
                          if(isset($_SESSION["id_compte"]) && $posteur["id_compte"] == $_SESSION["id_compte"]) // Si l'utilisateur est connecté et qu'un commentaire est le sien, un bouton s'affiche pour qu'il puisse le supprimer
                          {
                          ?>
                            <button type="submit" class="btn btn-danger btn-sm mb-2" data-mdb-toggle="tooltip" name ="<?php echo "supprimer" . $commentaire["id_compte"] ?>">
                            <i class="bi bi-trash"></i>
                            </button>
                          <?php
                          }
                          echo '<p class="mt-3">' . $commentaire["texte"] . '</p>';
                          echo '</div>';
                          if($i != $nb_commentaires)
                          {
                            echo "<hr>";
                          }
                          echo "</form>";
                          $i++;
                        }
                      }
                      else // Si aucun avis n'a été publié, s'affiche le message suivant
                      {
                        echo "<p>Aucun avis n'a encore été publié</p>";
                      }
                    ?>
                  </div>
                </div>
              </div>
              <?php
                if(isset($_SESSION["id_compte"])) // Si l'utilisateur est connecté, le bouton d'envoi d'avis le fait rester sur la page, et l'avis (s'il n'en a pas déjà soumis un) va s'ajouter à la page...
                {
                  $lien = "";
                }
                else // tandis que si l'utilisateur n'est pas connecté, le bouton d'envoi de commentaire le redirige vers la page de connexion/inscription
                {
                  $lien = "login.php?inscription";
                }
              ?>
              <form method="post" action="<?php echo $lien ?>" class="mt-3">
                <div class="mb-3">
                  <label for="commentaryInput" class="form-label">Écrire un avis</label>
                  <textarea class="form-control" id="commentaryInput" name="commentaire" rows="3" placeholder="Donnez votre avis sur le livre"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="<?php echo "commentaire" . $livre["id_livre"] ?>">Envoyer</button>
              </form>
            </div>
          </section>
        <?php
          // =======================================================
          // ====== RECOMMANDATION DE LECTURE LIÉES AU LIVRE =======
          // =======================================================
          
          $livres = recuperation_table(connexionBDD("vert_galant", "root", ""), 2);
          
          foreach ($livres as $une_recommandation) // Chaque livre enregistré est une potentielle recommandation. C'en est une s'il a un ou des mots-clés identiques au livre de la page
          {
            if($une_recommandation["id_livre"] != $livre["id_livre"])
            {
              strtolower($une_recommandation["mots_cles"]);
              strtolower($livre["mots_cles"]);
              $une_recommandation_mots_cles = explode(", ", $une_recommandation["mots_cles"]); // scinde la valeurs mots_cles en un tableau de mots clés
              $livre_mots_cles = explode(", ", $livre["mots_cles"]); // idem
              $mots_cles_communs = array_intersect($une_recommandation_mots_cles, $livre_mots_cles); // conserve les mots clés en commun entre le livre et la potentielle recommandation
              if (!empty($mots_cles_communs) || $une_recommandation["auteur"] == $livre["auteur"]) // Si le tableau des mots-clés en commun n'est pas vide, ou que les auteurs des livres sont les mêmes...
              {
                  $recommandations[] = $une_recommandation; // alors $une_recommandation est effectivement une recommandation, et donc on l'entre dans le tableau des recommandations
              }
            }
          }
          if(isset($recommandations)) // SI des livres recommandables existent, alors...
          {
            if (count($recommandations) > 4) // S'il y a plus de quatre livres recommandables, alors...
            {
                $cle_recommandations = array_rand($recommandations, 4); // On prend quatre livres au hasard dans le tableau des recommandations via leurs clés
                foreach($cle_recommandations as $cle)
                {
                    $recommandations_selectionnees[] = $recommandations[$cle]; // Un nouveau tableau est crée, prenant en valeur chaque recommandation récupérée aléatoirement, via sa clé
                }
            }
            else // Si moins de quatre livres sont recommandables, alors...
            {
              $recommandations_selectionnees = $recommandations; // les recommandations sont toujours les mêmes
            }
          ?>
          <section class="py-3 bg-light">
            <div class="container px-4 px-lg-5 mt-5">
              <h2 class="fw-bolder mb-4">Ces livres pourraient vous plaire...</h2>
              <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                  foreach ($recommandations_selectionnees as $recommandation)
                  {
                  ?>
                    <div class="col mb-5">
                      <div class="card h-100">
                        <a href="livre.php?livre=<?php echo $recommandation["id_livre"] ?>">
                          <img class="card-img-top border" src="<?php echo recuperation_chemin(2, $recommandation["id_livre"]) // affichage de l'image du livre ?>" alt="<?php echo $recommandation["titre"] ?>">
                        </a>
                        <div class="card-body p-4">
                          <div class="text-center">
                            <h5 class="fw-bolder"><?php echo $recommandation['titre']; ?></h5>
                            <?php
                              $recommandation["prix"] = number_format($recommandation["prix"], 2, '.', '');
                              echo $recommandation['prix'] . " €";
                            ?>
                          </div>
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
        // ========================================================================================================================
        // ======= AFFICHAGE DANS LE CAS OU LA VARIABLE $_GET["livre"] n'est pas présente ou contient une fausse valeur =======
        // ========================================================================================================================
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