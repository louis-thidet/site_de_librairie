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
    <meta name="description" content="page d'inscription" />
    <meta name="language" content="fr" />
    <meta name="keywords" content="inscription, login, identifiant, mot de passe" />
    <title>Panier</title>
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
      
      // ================================================================
      // ======= VIDAGE DU PANIER ET AJOUT A LA LISTE DE SOUHAITS =======
      // ================================================================
      if (isset($_POST)) // Vérification de l'existence de variables $_POST
      {
        foreach ($_POST as $id_livre => $quantite) // On vérifie la nature de chaque variable $_POST
        {
          if(strpos($id_livre, 'supp') === 0) // Si la valeur de la variable commence par supp, on sait que l'utilisateur a cliqué sur le bouton de vidage du panier d'un article...
          {
            $id_livre = str_replace("supp","",$id_livre); // On retire "supp" de la variable
            unset($_SESSION["_" . $id_livre]); // On supprime la variable $_SESSION qui représentait l'article dans le panier et sa quantité
          }
          if(strpos($id_livre, 'souhait') === 0) // Si la valeur de la variable commence par souhait, on sait que l'utilisateur a cliqué sur le bouton d'ajout à la liste de souhaits
          {
            $id_livre = str_replace("souhait","",$id_livre); // On retire "souhait" de la variable
            ajout_liste_souhaits(connexionBDD("vert_galant", "root", ""), $id_livre, $_SESSION["id_compte"]); // On ajoute le livre à la liste de souhaits de l'utilisateur
          }
        }
      }
    ?>
    <!-- centre -->
    <section class="h-100 gradient-custom flex-grow-1">
      <div class="container py-5">
        <div class="row d-flex justify-content-center my-4">
          <!-- div comprenant les livres  -->
          <div class="col-md-8">
            <div class="card mb-4">
              <div class="card-header py-3">
                <?php
                  $nb_livres = 0;
                  $nb_ref = 0;
                  $total = 0;
                  if(isset($_SESSION)) // La condition vérifiant l'existence de variables $_SESSION est liée au fait que les articles ajoutés au panier sont conservés dans le panier par le biais de variables $_SESSION
                  {
                    foreach($_SESSION as $id_livre => $quantite)
                    {
                      if (strpos($id_livre, '_') === 0 && is_numeric(substr($id_livre, 1))) // Si la valeur conservée dans la variable commence par "_" et est numérique, c'est une variable liée à un article dans le panier
                      {
                        $id_livre = str_replace("_","",$id_livre); // on retire le "_" de la variable, et on obtient l'id du livre qui a été ajouté au panier
                        $livre = recuperation_ligne(connexionBDD("vert_galant", "root", ""), 2, $id_livre); // on récupère les informations sur le livre lié à l'id 
                        $nb_livres = $nb_livres + $quantite; // on garde en mémoire la quantité que l'utilisateur souhaite acheter de l'article
                        $nb_ref++; // la variable nb_ref sert à vérifier le nombre d'articles distincts (de références) présent dans le panier
                        $total = $total + ($livre["prix"]*$quantite);
                      }
                      // Remarque : cette condition if(isset($_SESSION)) et ce foreach sont dispensables dans la mesure où le calcul de nb_livres aurait pu être fait dans l'autre boucle foreach, qui va suivre
                      // mais elle est indispensable par rapport à nb_ref. Si on ne savait pas à l'avance le nombre de références présent dans le panier, on serait forcé d'afficher une barre de séparation des articles
                      // en-dessous du dernier article affiché (voir les lignes liés à la variable $flag_derniere_ref)
                    }
                  }
                ?>
                <h5 class="mb-0"><?php echo "Panier : " . $nb_livres . "  articles" ?></h5>
              </div>
              <div class="card-body">
                <?php
                  if(isset($_SESSION)) // même fonction que la première condition identique
                  {
                    $flag_derniere_ref = 0; // pour savoir quand on a atteint la dernière référence, et ne pas afficher une vaine barre de séparation des articles
                    foreach ($_SESSION as $id_livre => $quantite) // même fonction que la première boucle identique
                    {
                      if (strpos($id_livre, '_') === 0 && is_numeric(substr($id_livre, 1)))
                      {
                        $id_livre = str_replace("_","",$id_livre);
                        $livre = recuperation_ligne(connexionBDD("vert_galant", "root", ""), 2, $id_livre);

                        // La première boucle se différencie de la seconde par le fait qu'elle compte le nombre d'articles, et la seconde par le fait qu'elle affiche les informations des articles à l'écran
                        ?>
                        <div class="row">
                          <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                            <!-- Image -->
                            <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                              <a href="livre.php?livre=<?php echo $id_livre ?>"><img src="<?php echo recuperation_chemin(2, $livre["id_livre"]) ?>" class="w-75 image_livre_panier" /></a>
                            </div>
                          </div>
                          <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                            <!-- Data -->
                            <p><strong><?php echo $livre["titre"] ?></strong><?php echo " - " . $livre["auteur"] ?></p>
                            <p><?php echo "Prix de l'unité : " . $livre["prix"] . " €"?></p>
                            <form action="panier.php" method="post">
                              <button type="submit" class="btn btn-primary btn-sm me-1 mb-2" data-mdb-toggle="tooltip" name="<?php echo "supp" . $id_livre ?>"
                                title="Supprimer du panier">
                              <i class="fas fa-trash"></i>
                              </button>
                            </form>
                            <?php
                              if(isset($_SESSION["id_compte"]))
                              {
                                $lien = "";
                              }
                              else
                              {
                                $lien = "login.php?inscription";
                              }
                              ?>
                            <form action="<?php echo $lien ?>" method="post">
                              <button type="submit" class="btn btn-danger btn-sm mb-2" data-mdb-toggle="tooltip" name ="<?php echo "souhait" . $id_livre ?>"
                                title="Ajouter à la liste de souhaits">
                              <i class="fas fa-heart"></i>
                              </button>
                            </form>
                          </div>
                          <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                            <!-- Quantity -->
                            <div class="d-flex mb-4" style="max-width: 300px">
                              <button class="btn btn-primary px-3 me-2"
                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                              <i class="fas fa-minus"></i>
                              </button>
                              <div class="form-outline">
                                <input id="form1" min="0" name="quantite <?php echo $livre["prix"] ?>" value="<?php echo $quantite ?>" type="number" class="form-control" />
                                <label class="form-label" for="form1">Quantité</label>
                              </div>
                              <button class="btn btn-primary px-3 ms-2"
                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                              <i class="fas fa-plus"></i>
                              </button>
                            </div>
                            <!-- Price -->
                            <p class="text-start text-md-center">
                              <strong><?php echo "Prix : " . $quantite * $livre["prix"] . " €" ?></strong>
                            </p>
                          </div>
                        </div>
                        <?php
                        $flag_derniere_ref++;
                        if($flag_derniere_ref != $nb_ref)
                        {
                        ?>
                          <hr class="my-4" />
                        <?php
                        }
                      }
                    }
                  }
                  // Les lignes qui suivent sont celles de l'affichage du résumé du panier (coût total, bouton de validation de l'achat, etc.)
                  ?>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-header py-3">
                <h5 class="mb-0">Résumé</h5>
              </div>
              <div class="card-body">
                <?php
                  if($nb_livres != 0)
                  {
                  ?>
                    <ul class="list-group list-group-flush">
                      <li
                        class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                        Coût des articles
                        <span><?php echo $total . " €"?></span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Frais de livraison
                        <span>??? €</span>
                      </li>
                      <li
                        class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                        <div>
                          <strong>Montant total</strong>
                        </div>
                        <span><strong>??? €</strong></span>
                      </li>
                    </ul>
                    <button type="button" class="btn btn-primary btn-lg btn-block">
                    Passer la commande
                    </button>
                  <?php
                  }
                  else
                  {
                    echo "<div>";
                    echo "<strong>Panier vide</strong>";
                    echo "</div>";
                  }
                  ?>
              </div>
            </div>
          </div>
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