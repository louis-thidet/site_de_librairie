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
    <meta name="keywords" content="compte, gestion, profil, informations" />
    <?php
      if(isset($_GET["achats"]))
      {
        echo '<meta name="description" content="achats" />';
        echo "<title>Achats</title>";
      }
      else if(isset($_GET["souhaits"]))
      {
        echo '<meta name="description" content="liste de souhaits" />';
        echo "<title>Liste de souhaits</title>";
      }
      else if(isset($_GET["options"]))
      {
        echo '<meta name="description" content="options" />';
        echo "<title>Options</title>";
      }
      else if(isset($_GET["gestion_actualites"]))
      {
        echo '<meta name="description" content="gestion des actualités" />';
        echo "<title>Options</title>";
      }
      else if(isset($_GET["gestion_comptes"]))
      {
        echo '<meta name="description" content="gestion des comptes" />';
        echo "<title>Gestion des comptes</title>";
      }
      else if(isset($_GET["gestion_livres"]))
      {
        echo '<meta name="description" content="gestion des livres" />';
        echo "<title>Gestion des livres</title>";
      }
      else
      {
        echo '<meta name="description" content="mon compte" />';
        echo "<title>Mon compte</title>";
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
    <?php
      include("nav.php");

      // =======================================================================================
      // ================ AFFICHAGE DE LA PAGE POUR L'UTILISATEUR NON CONNECTÉ =================
      // =======================================================================================
      if(!isset($_SESSION["identifiant"]) && !isset($_SESSION["mot_de_passe"]))
      {
        if((isset($_GET["achats"]) || isset($_GET["souhaits"]) || isset($_GET["options"]) || isset($_GET["gestion_comptes"]) || isset($_GET["gestion_livres"]) || isset($_GET["gestion_actualites"]) || empty($_GET)))
        {
          page_erreur("Vous devez être connecté pour accéder à cette page"); // s'affiche dans le cas où l'utilisateur souhaite accéder à une page existante mais n'est pas connecté
        }
        else
        {
          page_erreur("La page demandée n'existe pas"); // s'affiche dans le cas où l'utilisateur souhaite accéder à une page qui n'existe pas
        }
      }
      // =======================================================================================
      // ================== AFFICHAGE DE LA PAGE POUR L'UTILISATEUR CONNECTÉ ===================
      // =======================================================================================

      // -------------------------------------------------------------------------------------------------------------------------------------------
      // ===================================================== AFFICHAGE DE LA PAGE MON COMPTE =====================================================
      // -------------------------------------------------------------------------------------------------------------------------------------------
      // Il s'agit de la page depuis laquelle l'utilisateur accède à toutes les autres sous-pages de compte.php
      else if(!isset($_GET["achats"]) && !isset($_GET["souhaits"]) && !isset($_GET["options"]) && !isset($_GET["gestion_comptes"]) && !isset($_GET["gestion_actualites"]) && !isset($_GET["gestion_livres"]))
      { // Si aucune variable $_GET n'est chargée, alors s'affiche ce qui suit
    ?>
        <!-- centre -->
        <section class="h-100 gradient-custom flex-grow-1">
          <div class="container">
            <h1 class="mt-5">Mon compte</h1>
            <div class="row mt-4">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="profile-picture me-4">
                        <img src='<?php echo recuperation_chemin(1, $_SESSION["id_compte"]) // affichage de l'image de profil?>' alt='image de profil' class='rounded-circle image_profil_grand'>
                      </div>
                      <div>
                        <h4 class='card-title'> <?php echo $_SESSION["prenom"] . " " . $_SESSION["nom"] // affichage du prénom et du nom de l'utilisateur ?></h4>
                        <a href="profil.php?id_compte=<?php echo $_SESSION["id_compte"] // lien vers le profil du compte de l'utilisateur ?>" class="btn btn-primary">Voir mon profil public</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-4 mb-4">
              <div class="col-lg-4">
                <a href="compte.php?achats" class="nav-link">
                  <div class="rectangle shadow-sm">
                    <h3>Achats</h3>
                    <p>Voyez l'historique de vos achats.</p>
                  </div>
                </a>
              </div>
              <div class="col-lg-4">
                <a href="compte.php?souhaits" class="nav-link">
                  <div class="rectangle shadow-sm">
                    <h3>Liste de souhaits</h3>
                    <p>Consultez votre liste de souhaits</p>
                  </div>
                </a>
              </div>
              <div class="col-lg-4">
                <a href="compte.php?options" class="nav-link">
                  <div class="rectangle shadow-sm">
                    <h3>Options</h3>
                    <p>Modifiez les informations de votre compte</p>
                  </div>
                </a>
              </div>
              <?php
                if($_SESSION["is_admin"] == 1) // si le compte de l'utilisateur est de type administrateur, il peut accéder aux rubriques gérant le contenu du site
                {
                ?>
                  <div class="col-lg-4">
                    <a href="compte.php?gestion_livres" class="nav-link">
                      <div class="rectangle shadow-sm" style="width: 100%;">
                        <h3>Gestion des livres</h3>
                        <p>Gérez les livres, leurs informations, leurs ajouts et suppressions</p>
                      </div>
                    </a>
                  </div>
                  <div class="col-lg-4">
                    <a href="compte.php?gestion_actualites" class="nav-link">
                      <div class="rectangle shadow-sm" style="width: 100%;">
                        <h3>Gestion des actualités</h3>
                        <p>Gérez les actualités du site</p>
                      </div>
                    </a>
                  </div>
                  <div class="col-lg-4">
                    <a href="compte.php?gestion_comptes" class="nav-link">
                      <div class="rectangle shadow-sm" style="width: 100%;">
                        <h3>Gestion des comptes</h3>
                        <p>Gérez les informations des comptes, leurs permissions, ou leur suppression</p>
                      </div>
                    </a>
                  </div>
              <?php
                }
                ?>
            </div>
          </div>
        </section>
        <?php
      }
      // ------------------------------------------------------------------------------------------------------------------------------------------
      // ===================================================== AFFICHAGE DES ACHATS DU COMPTE =====================================================
      // ------------------------------------------------------------------------------------------------------------------------------------------
      else if(isset($_GET["achats"])) // si la variable $_GET["achats] est présente est chargée...
      {
        page_erreur("Page non implémentée pour le moment");
      }
      // ------------------------------------------------------------------------------------------------------------------------------------------
      // ==================================================== GESTION DE LA LISTE DE SOUHAITS =====================================================
      // ------------------------------------------------------------------------------------------------------------------------------------------
      else if(isset($_GET["souhaits"])) // si la variable $_GET["souhaits] est présente est chargée...
      {
        ?>
        <!-- centre -->
        <section class="h-100 gradient-custom flex-grow-1">
          <div class="container">
            <h5 class="mt-5"><a class="text-decoration-none text-success"  href="compte.php">Mon compte</a> / Liste de souhaits</h5>
            <h1>Liste de souhaits</h1>
            <hr>
            <div class="row">
              <div class="col-md-12">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Titre</th>
                      <th>Action</th>
                      <th>Auteur</th>
                      <th>Prix</th>
                      <th>Editeur</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    // ===================================
                    // ====== SUPPRESSION DU LIVRE =======
                    // ===================================
                    $livres = recuperation_ligne(connexionBDD("vert_galant", "root", ""), 3, $_SESSION["id_compte"]); // récupération de la table des livres
                    if(count($livres) != 0) // S'il existe au moins un livre
                    {
                      for($i = 0; $i <= max(array_keys($livres)); $i++) // se lance une boucle parcourant un nombre de tours égal au plus grand id de livre
                      {
                        if(isset($_POST[$i])) // Si cette variable existe, cela signifie que l'utilisateur a cliqué sur le bouton de suppression du livre qui lui correspond
                        {
                          suppression_ligne(connexionBDD("vert_galant", "root", ""), 3, $i); // le livre est supprimé de la base de données
                        }
                      }
                    }
                    $livres = recuperation_ligne(connexionBDD("vert_galant", "root", ""), 3, $_SESSION["id_compte"]); // les livres sont chargés à nouveau pour que la liste des livres soit toujours actualisée. On devait charger les livres une première fois pour le cas où il y aurait une suppression

                    foreach ($livres as $livre) // On affiche en ligne les informations de chaque livre
                    {
                      $infos_livre = recuperation_ligne(connexionBDD("vert_galant", "root", ""), 2, $livre["id_livre"]); // récupération des infos du livre
                    ?>
                      <tr>
                        <td><a href="livre.php?livre=<?php echo $livre["id_livre"]?>"><?php echo $infos_livre["titre"] // titre du livre ?></a></td>
                        <td>
                        <form action="compte.php?souhaits" method="post">
                          <button class="btn btn-sm btn-danger mx-1" type="submit" name="<?php echo $livre["id_livre"] // bouton de suppression du livre ?>">Supprimer</button>
                        </form>
                        </td>
                        <td><?php echo $infos_livre["auteur"] // auteur du livre ?></td>
                        <td><?php echo $infos_livre["prix"] . " €" // prix du livre?></td>
                        <td><?php echo $infos_livre["editeur"] // éditeur du livre ?></td>
                      </tr>
                      <tr>
                    <?php
                    }
                  ?>
                  </tbody>
                </table>
                </div>
              </div>
            </div>
          </div>
        </section>
        <?php
      }
      // ------------------------------------------------------------------------------------------------------------------------------------------
      // =================================================== GESTION DES INFORMATIONS DU COMPTE ===================================================
      // ------------------------------------------------------------------------------------------------------------------------------------------
      else if(isset($_GET["options"])) // si la variable $_GET["options] est présente est chargée...
      {
        // ========================================
        // ===== ENVOI D'UNE IMAGE DE PROFIL ======
        // ========================================
        if(isset($_FILES["envoyer_image_compte"])) // Si l'utilisateur a envoyé une image de profil via le formulaire de gestion du compte
        {
          if($_FILES["envoyer_image_compte"]["error"] == 0) // Si l'envoi ne rencontre pas d'erreur
          {
            if($_FILES["envoyer_image_compte"]["size"] <= 1000000) // Si l'image ne fait pas plus de 1 mo
            {
                $infosfichier = pathinfo($_FILES["envoyer_image_compte"]["name"]); // récolte du nom du fichier
                $extension_upload = $infosfichier["extension"]; // récolte de l'extension du fichier
                $extensions_autorisees = array("jpg", "jpeg", "png", "gif"); // extensions autorisées pour le transfert
                if (in_array($extension_upload, $extensions_autorisees)) // Si le fichier comporte l'une des extensions autorisées, alors...
                {
                  if(file_exists("images/comptes/" . $_SESSION["id_compte"] . ".jpg")) // si un le fichier .jpg au nom de l'id du compte existe...
                  {
                    unlink("images/comptes/" . $_SESSION["id_compte"] . ".jpg"); // le fichier est supprimé
                  }
                  else if(file_exists("images/comptes/" . $_SESSION["id_compte"] . ".jpeg")) // si un le fichier .jpeg au nom de l'id du compte existe...
                  {
                    unlink("images/comptes/" . $_SESSION["id_compte"] . ".jpeg"); // le fichier est supprimé
                  }
                  else if(file_exists("images/comptes/" . $_SESSION["id_compte"] . ".png")) // si un le fichier .png au nom de l'id du compte existe...
                  {
                    unlink("images/comptes/" . $_SESSION["id_compte"] . ".png"); // le fichier est supprimé
                  }
                  else if(file_exists("images/comptes/" . $_SESSION["id_compte"] . ".gif")) // si un le fichier .gif au nom de l'id du compte existe...
                  {
                    unlink("images/comptes/" . $_SESSION["id_compte"] . ".gif"); // le fichier est supprimé
                  }
                  // Validation du fichier et stockage
                  $_SESSION["message_image3"] = "<div class='annonce_options_succes'>L'image de profil a été modifiée</div>"; // mise en mémoire d'un message de succès
                  move_uploaded_file($_FILES["envoyer_image_compte"]["tmp_name"], "images/comptes/" . $_SESSION["id_compte"] . "." . $infosfichier["extension"]); // puisque le transfert est un succès, l'image de profil est envoyée vers le dossier images/comptes
                  
                }
                else
                {
                  $_SESSION["message_image2"] = "<div class='annonce_options_erreur'>Seules les images au format jpg, jpeg, png ou gif sont acceptées</div>"; // mise en mémoire d'un message d'erreur
                }
              }
              else
              {
                $_SESSION["message_image1"] = "<div class='annonce_options_erreur'>La taille du fichier est trop importante (1 mo max)</div>"; // mise en mémoire d'un message d'erreur
              }
          }
        }
        // ==========================================
        // ===== MISE A JOUR DE L'ADRESSE MAIL ======
        // ==========================================
        if(isset($_POST["ancien_email"]) && isset($_POST["nouveau_email"]) && isset($_POST["confirmation_email"])) // Si l'utilisateur a entré un nouvel email et validé la confirmation de changement de mail (entrer son mot de passe)
        {
          if($_POST["ancien_email"] != "" && $_POST["nouveau_email"] != "" && $_POST["confirmation_email"] != "") // si l'utilisateur n'a pas laissé des champs vides
          {
            if($_POST["confirmation_email"] != $_SESSION["mot_de_passe"]) // Erreur si l'utilisateur n'a pas entré son mot de passe correctement dans la confirmation de changement
            {
              $_SESSION["message_email1"] = "<div class='annonce_options_erreur'>Mot de passe erroné</div>";
            }
            if(strlen($_POST["nouveau_email"]) < 3 || !str_contains($_POST["nouveau_email"],"@") || substr_count($_POST["nouveau_email"],"@") > 1) // Erreur si la nouvelle adresse email est invalide
            {
              $_SESSION["message_email2"] = "<div class='annonce_options_erreur'>Nouvelle adresse email invalide</div>";
            }
            if($_SESSION["email"] == $_POST["ancien_email"] && $_SESSION["mot_de_passe"] == $_POST["confirmation_email"] &&
                substr_count($_POST["nouveau_email"],"@") == 1 && strlen($_POST["nouveau_email"]) > 3 && str_contains($_POST["nouveau_email"],"@")) // Succès si la nouvelle adresse email est valide et que le mot de passe est correctement entré dans la confirmation
            {
              $_SESSION["message_email3"] = "<div class='annonce_options_succes'>L'adresse email a été modifiée</div>";
              maj_donnees(connexionBDD("vert_galant", "root", ""), 2, $_POST["nouveau_email"]); // l'email de l'utilisateur est mis à jour dans la base de données
            }
          }
        }
        // ========================================
        // ===== MISE A JOUR DU MOT DE PASSE ======
        // ========================================
        if(isset($_POST["ancien_mdp"]) && isset($_POST["nouveau_mdp"]) && isset($_POST["confirmation_mdp"])) // même fonctionnement que pour le changmement d'email
        {
          if($_POST["ancien_mdp"] != "" && $_POST["nouveau_mdp"] != "" && $_POST["confirmation_mdp"] != "")
          {
            if($_SESSION["mot_de_passe"] != $_POST["ancien_mdp"])
            {
              $_SESSION["message_mdp1"] = "<div class='annonce_options_erreur'>Ancien mot de passe erroné</div>";
            }
            if($_POST["nouveau_mdp"] != $_POST["confirmation_mdp"])
            {
              $_SESSION["message_mdp2"] = "<div class='annonce_options_erreur'>Confirmation de mot de passe erronée</div>";
            }
            if(strlen($_POST["nouveau_mdp"]) < 8 )
            {
              $_SESSION["message_mdp3"] = "<div class='annonce_options_erreur'>Nouveau mot de passe trop court</div>";
            }
            if($_SESSION["mot_de_passe"] == $_POST["ancien_mdp"] && $_POST["nouveau_mdp"] == $_POST["confirmation_mdp"] && strlen($_POST["nouveau_mdp"]) >= 8 )
            {
              $_SESSION["message_mdp4"] = "<div class='annonce_options_succes'>Le mot de passe a été modifiée</div>";
              maj_donnees(connexionBDD("vert_galant", "root", ""), 3, $_POST["nouveau_mdp"]);
            }
          }
        }
        // ===================================================
        // ===== FORMULAIRE DE MODIFICATION DES DONNEES ======
        // ===================================================
        ?>
        <div class="container">
          <h5 class="mt-5"><a class="text-decoration-none text-success"  href="compte.php">Mon compte</a> / Options</h5>
          <h1 class="mt-1">Options</h1>
          <div class="row mt-4">
            <hr>
            <div class="row">
              <div class="col-md-12">
                <h3>
                  <Area>
                  </Area>Adresse email
                </h3>
                <form method="post" action="compte.php?options" enctype="multipart/form-data">
                  <div class="mb-3">
                    <label class="form-label">Adresse email</label>
                    <input type="text" class="form-control bg-light" id="password" name="ancien_email" value="<?php echo $_SESSION["email"] // l'utilisateur n'a pas besoin d'écrire l'ancienne adresse email, elle est là par défaut ?>" readonly>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Nouvelle adresse email</label>
                    <input type="email" class="form-control" id="password" name="nouveau_email">
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Pour changer d'adresse, saisissez votre mot de passe :</label>
                    <input type="password" class="form-control" id="password" name="confirmation_email">
                  </div>
                  <h3>Mot de passe</h3>
                  <div class="mb-3">
                    <label for="password" class="form-label">Ancien mot de passe</label>
                    <input type="password" class="form-control" id="password" name="ancien_mdp">
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="nouveau_mdp">
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Confirmation du nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="confirmation_mdp">
                  </div>
                  <div class="mb-3">
                    <h3>Image de profil</h3>
                    <input type="file" class="form-control" name="envoyer_image_compte" id="profilePicture"/>
                  </div>
                  <div class="mb-3">
                      <img src="<?php echo recuperation_chemin(1, $_SESSION["id_compte"]) ?>" alt="image de profil" class="rounded-circle image_profil_grand">
                  </div>
                  <button type="submit" class="btn btn-primary mb-3">Valider les modifications</button>
                </form>
                <?php
                  // ========================================================
                  // ===== AFFICHAGE DES MESSAGES DE SUCCES / D'ERREUR ======
                  // ========================================================
                  // Messages relatifs à la mise en ligne d'une nouvelle image de profil
                  if(isset($_SESSION["message_image1"]))
                  {
                    echo $_SESSION["message_image1"];
                    unset($_SESSION["message_image1"]);
                  }
                  if(isset($_SESSION["message_image2"]))
                  {
                    echo $_SESSION["message_image2"];
                    unset($_SESSION["message_image2"]);
                  }
                  if(isset($_SESSION["message_image3"]))
                  {
                    echo $_SESSION["message_image3"];
                    unset($_SESSION["message_image3"]);
                  }
                  // Messages relatifs à un changement d'adresse email
                  if(isset($_SESSION["message_email1"]))
                  {
                    echo $_SESSION["message_email1"];
                    unset($_SESSION["message_email1"]);
                  }
                  if(isset($_SESSION["message_email2"]))
                  {
                    echo $_SESSION["message_email2"];
                    unset($_SESSION["message_email2"]);
                  }
                  if(isset($_SESSION["message_email3"]))
                  {
                    echo $_SESSION["message_email3"];
                    unset($_SESSION["message_email3"]);
                  }
                  // Messages relatifs à un changement de mot de passe
                  if(isset($_SESSION["message_mdp1"]))
                  {
                    echo $_SESSION["message_mdp1"];
                    unset($_SESSION["message_mdp1"]);
                  }
                  if(isset($_SESSION["message_mdp2"]))
                  {
                    echo $_SESSION["message_mdp2"];
                    unset($_SESSION["message_mdp2"]);
                  }
                  if(isset($_SESSION["message_mdp3"]))
                  {
                    echo $_SESSION["message_mdp3"];
                    unset($_SESSION["message_mdp3"]);
                  }
                  if(isset($_SESSION["message_mdp4"]))
                  {
                    echo $_SESSION["message_mdp4"];
                    unset($_SESSION["message_mdp4"]);
                  }
                  ?>
                <div class="mt-3"></div>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      // ------------------------------------------------------------------------------------------------------------------------------------------
      // ===================================================== PAGE DE GESTION DES ACTUALITES =====================================================
      // ------------------------------------------------------------------------------------------------------------------------------------------
      else if(isset($_GET["gestion_actualites"]) && $_SESSION["is_admin"] == 1) // si la variable $_GET["gestion_actualites] est présente est chargée et que l'utilisateur est un admin
      {
        // ===================================================
        // ========= FORMULAIRE D'AJOUT D'ACTUALITÉ ==========
        // ===================================================
        ?>
        <!-- centre -->
        <section class="h-100 gradient-custom flex-grow-1">
          <div class="container">
            <h5 class="mt-5"><a class="text-decoration-none text-success"  href="compte.php">Mon compte</a> / Gestion des actualités</h5>
            <h1 id="gestion-livres">Gestion des actualités</h1>
            <hr>
            <div class="mb-3">
              <h3>Ajouter une actualité</h3>
              <form method="post" action="compte.php?gestion_actualites">
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label for="titre" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="price" placeholder="Entrez le titre de l'actualité" name="titre" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label for="texte" class="form-label">Texte</label>
                    <textarea class="form-control" id="description" placeholder="Entrez le texte de l'actualité" name="texte" required></textarea>
                  </div>
                </div>
  
                <?php
                  $actualites = recuperation_table(connexionBDD("vert_galant", "root", ""), 5); // on récupère la table des actualités
                  
                  // ===================================
                  // ====== AJOUT DE L'ACTUALITE =======
                  // ===================================
                  if(isset($_POST["titre"]) && isset($_POST["texte"])) // si l'utilisateur a bien soumis le titre et le texte d'une actualité, alors...
                  {
                    ajout_actualite(connexionBDD("vert_galant", "root", ""), $_POST["texte"], $_POST["titre"]); // cette actualité est ajoutée à la table des actualités
                  }
                  ?>
                <button type="submit" class="btn btn-primary">Ajouter une actualité</button>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Action</th>
                    <th>Texte</th>
                    <th>Date d'ajout</th>
                  </tr>
                </thead>
                <tbody style='font-size:14px;'>
                  <?php
                    // ========================================
                    // ====== SUPPRESSION DE L'ACTUALITÉ ======
                    // ========================================
                    if(isset($_POST["supprimer"])) // si cette variable existe, c'est que l'utilisateur a choisi de supprimer une actualité (elle contient son id)
                    {
                      suppression_ligne(connexionBDD("vert_galant", "root", ""), 5, $_POST["supprimer"]);
                    }
                    // ===================================
                    // ====== AFFICHAGE DES LIVRES =======
                    // ===================================
                    $actualites = recuperation_table(connexionBDD("vert_galant", "root", ""),5); // Mise à jour après suppression ou ajout d'un livre

                    foreach ($actualites as $actualite) // chaque actualité est affichée
                    {
                      echo "<tr>";
                      echo "<td>" . $actualite["id_actualite"] . "</td>";
                      ?>
                      <?php
                      if(strlen($actualite["titre"]) > 50) // si le titre de l'actualité est supérieur à 50...
                      {
                        $actualite["titre"] = substr($actualite["titre"],0,47) . "..."; // Il est tronqué à 47 et on y ajoute des points de suspension
                      }
                      ?>
                      <td><a href="actualites.php#<?php echo $actualite["id_actualite"]?>"><?php echo $actualite["titre"] // affichage du titre de l'actualité, et lien de redirection vers celui-ci?></a></td>
                      <?php
                      echo "<td>";
                      echo "<form method='post' action='compte.php?gestion_actualites'>";
                      echo "<button class='btn btn-sm btn-danger mx-1 type='submit' name='supprimer' value='" . $actualite["id_actualite"] . "'>Supprimer</button>";
                      echo "</td>";
                      echo "<td>";
                      echo "<button type='button' class='btn btn-sm btn-primary' data-bs-toggle='popover' title='' data-bs-content='" . $actualite["texte"] . "'>Texte</button>"; // texte de l'actualité
                      echo "</td>";
                      echo "<td>" . $actualite["date"] . "</td>"; // date de publication de l'actualité 
                      echo "</td>";
                      echo "</tr>";
                    }
                    ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
        <?php
      }
      // ------------------------------------------------------------------------------------------------------------------------------------------
      // ======================================================= PAGE DE GESTION DES LIVRES =======================================================
      // ------------------------------------------------------------------------------------------------------------------------------------------
      else if(isset($_GET["gestion_livres"]) && $_SESSION["is_admin"] == 1) // si la variable $_GET["gestion_livres] est présente est chargée et que l'utilisateur est un admin
      {
        // =====================================================
        // ========= FORMULAIRE DE GESTION DES LIVRES ==========
        // =====================================================
      ?>
        <!-- centre -->
        <section class="h-100 gradient-custom flex-grow-1">
          <div class="container">
            <h5 class="mt-5"><a class="text-decoration-none text-success"  href="compte.php">Mon compte</a> / Gestion des livres</h5>
            <h1 id="gestion-livres">Gestion des livres</h1>
            <hr>
            <div class="mb-3">
              <h3>Ajouter un livre</h3>
              <form method="post" action="compte.php?gestion_livres" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="price" placeholder="Entrez le titre du livre" name="titre" required/>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="category" class="form-label">Auteur</label>
                    <input type="text" class="form-control" id="category" placeholder="Entrez le nom de l'auteur" name="auteur" required/>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="category" class="form-label">Catégorie</label>
                    <select class="form-select" id="category" name="categorie" required>
                      <option value="">Sélectionnez la catégorie</option>
                      <option value="Roman ou nouvelles">Roman ou nouvelles</option>
                      <option value="Poésie">Poésie</option>
                      <option value="Théâtre">Théâtre</option>
                      <option value="Essai">Essai</option>
                      <option value="Philosophie">Philosophie</option>
                      <option value="Art">Art</option>
                      <option value="Histoire">Histoire</option>
                      <option value="Musique">Musique</option>
                      <option value="Littérature scientifique">Littérature scientifique</option>
                      <option value="Autre">Autre</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="pages" class="form-label">Mots-clés</label>
                    <input type="text" class="form-control" id="pages" placeholder="Entrez des mots-clés séparés par une virgule" name="mots_cles" required/>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Prix</label>
                    <input type="text" class="form-control" id="price" placeholder="Entrez le prix du livre" name="prix" required/>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="publisher" class="form-label">Éditeur</label>
                    <input type="text" class="form-control" id="publisher" placeholder="Entrez l'éditeur du livre" name="editeur" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="pages" class="form-label">Nombre de pages</label>
                    <input type="text" class="form-control" id="pages" placeholder="Entrez le nombre de pages du livre" name="nb_pages" required/>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="length" class="form-label">Longueur</label>
                    <input type="text" class="form-control" id="length" placeholder="Entrez la longueur du livre" name="longueur" required/>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="width" class="form-label">Largeur</label>
                    <input type="text" class="form-control" id="width" placeholder="Entrez la largeur du livre" name="largeur" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="thickness" class="form-label">Épaisseur</label>
                    <input type="text" class="form-control" id="thickness" placeholder="Entrez l'épaisseur du livre" name="epaisseur" required/>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="length" class="form-label">Poids</label>
                    <input type="text" class="form-control" id="length" placeholder="Entrez le poids du livre" name="poids" required/>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="width" class="form-label">EAN</label>
                    <input type="text" class="form-control" id="width" placeholder="Entrez le code EAN du livre" name="EAN" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="thickness" class="form-label">ISBN</label>
                    <input type="text" class="form-control" id="thickness" placeholder="Entrez le code ISBN du livre" name="ISBN" required/>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="format" class="form-label">Format</label>
                    <select class="form-select" id="format" name="format" required>
                      <option value="">Sélectionnez le format</option>
                      <option value="Broché">Broché</option>
                      <option value="Relié">Relié</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="format" class="form-label">Image</label>
                    <input type="file" class="form-control" name="envoyer_image_livre" id="profilePicture" required/>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" placeholder="Entrez la description du livre" name="description" required></textarea>
                  </div>
                </div>
  
                <?php
                  $livres = recuperation_table(connexionBDD("vert_galant", "root", ""), 2); // récupération des informations sur les livres enregistrés
                  
                  // =============================
                  // ====== AJOUT DU LIVRE =======
                  // =============================
                  if(isset($_POST["titre"]) && isset($_POST["auteur"]) && isset($_POST["editeur"]) && isset($_POST["categorie"]) && isset($_POST["prix"]) && 
                    isset($_POST["description"]) && isset($_POST["nb_pages"]) && isset($_POST["longueur"]) && isset($_POST["largeur"]) && isset($_POST["epaisseur"]) && isset($_POST["poids"]) && 
                    isset($_POST["EAN"]) && isset($_POST["ISBN"]) && isset($_POST["mots_cles"]) && isset($_POST["format"])) // si l'utilisateur a entré dans le formulaire tout ce qu'il faut pour ajouteru un livre...
                  {
                    if(empty($livres)) // si aucun livre n'est enregistré dans la base de données, l'id du livre est 1 (L'incrément d'id est de 1 à la base, mais l'utilisateur peut ajouter des livres puis tous les supprimer)
                    {
                      $id_livre = 1;
                    }
                    else // sinon, l'id du livre est supérieur de 1 à celui le plus grand actuel
                    {
                      $id_livre = max(array_column($livres, 'id_livre'));
                      $id_livre++;
                    }
                  
                    ajout_livre(connexionBDD("vert_galant", "root", ""), $_POST["titre"], $_POST["auteur"], $_POST["editeur"], $_POST["description"], $_POST["prix"], $_POST["categorie"], 
                    $_POST["mots_cles"], $_POST["format"], $_POST["largeur"], $_POST["longueur"], $_POST["epaisseur"], $_POST["poids"], $_POST["EAN"], $_POST["ISBN"], $_POST["nb_pages"]);
                  } // le livre est ajouté àla base de données

                  // ======================================
                  // ===== ENVOI DE L'IMAGE DU LIVRE ======
                  // ======================================
                  if(isset($_FILES["envoyer_image_livre"])) // si l'utilisateur a envoyé une image de livre
                  {
                    if($_FILES["envoyer_image_livre"]["error"] == 0) // s'il n'y a pas d'erreur dans l'envoi
                    {
                      if($_FILES["envoyer_image_livre"]["size"] <= 2500000) // si l'image du livre ne fait pas plus de 2.5 mo
                      {
                          $infosfichier = pathinfo($_FILES["envoyer_image_livre"]["name"]); // récolte du nom du fichier
                          $extension_upload = $infosfichier["extension"]; // récolte de l'extension du fichier
                          $extensions_autorisees = array("jpg", "jpeg", "png", "gif"); // saisie des extensions de fichier autorisées
                          if (in_array($extension_upload, $extensions_autorisees)) // Si l'extension du fichier est comprise parmi celles autorisées, alors...
                          {
                            if(file_exists("images/livres/" . $id_livre . ".jpg")) // si un fichier .jpg au nom de l'id du livre existe...
                            {
                              unlink("images/livres/" . $id_livre . ".jpg"); // le fichier est supprimé
                            }
                            else if(file_exists("images/livres/" . $id_livre . ".jpeg")) // si un fichier .jpeg au nom de l'id du livre existe...
                            {
                              unlink("images/livres/" . $id_livre . ".jpeg"); // le fichier est supprimé
                            }
                            else if(file_exists("images/livres/" . $id_livre . ".png")) // si un fichier .png au nom de l'id du livre existe...
                            {
                              unlink("images/livres/" . $id_livre . ".png"); // le fichier est supprimé
                            }
                            else if(file_exists("images/livres/" . $id_livre . ".gif")) // si un fichier .gif au nom de l'id du livre existe...
                            {
                              unlink("images/livres/" . $id_livre . ".gif"); // le fichier est supprimé
                            }
                            // Validation du fichier et stockage
                            move_uploaded_file($_FILES["envoyer_image_livre"]["tmp_name"], "images/livres/" . $id_livre . "." . $infosfichier["extension"]); // le transfert est un succès et le fichier est envoyé dans images/livres
                            unset($_FILES["envoyer_image_livre"]);
                          }
                          else
                          {
                            echo "Seules les images sont acceptées !"; // mise en mémoire d'un message d'erreur
                          }
                        }
                        else
                        {
                          echo "La taille du fichier est trop importante (1 mo max) !";  // mise en mémoire d'un message d'erreur
                      }
                    }
                  }
                  ?>
                <button type="submit" class="btn btn-primary">Ajouter un livre</button>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Action</th>
                    <th>Auteur</th>
                    <th>Editeur</th>
                    <th>Prix</th>
                    <th>Catégorie</th>
                    <th>Mots-clés</th>
                    <th>Format</th>
                    <th>Dimensions</th>
                    <th>Poids</th>
                    <th>EAN</th>
                    <th>ISBN</th>
                  </tr>
                </thead>
                <tbody style='font-size:14px;'>
                  <?php
                    // ===================================
                    // ====== SUPPRESSION DU LIVRE =======
                    // ===================================
                      if(count($livres) != 0) // s'il existe au moins un livre
                      {
                        for($i = 0; $i <= max(array_keys($livres)); $i++) // se lance une boucle parcourant un nombre de tours égal au plus grand id de livre
                        {
                          if(isset($_POST[$i])) // Si cette variable existe, cela signifie que l'utilisateur a cliqué sur le bouton de suppression du livre qui lui correspond
                          {
                            suppression_ligne(connexionBDD("vert_galant", "root", ""), 2, $i); // suppression du livre de la base de donnéees
                          }
                        }
                      }
                    // ===================================
                    // ====== AFFICHAGE DES LIVRES =======
                    // ===================================
                    $livres = recuperation_table(connexionBDD("vert_galant", "root", ""), 2); // Mise à jour après suppression ou ajout d'un livre
                    
                    foreach ($livres as $livre)
                    {
                      echo "<tr>";
                      echo "<td>" . $livre["id_livre"] . "</td>";
                      ?>
                      <?php
                      if(strlen($livre["titre"]) > 30)
                      {
                        $livre["titre"] = substr($livre["titre"],0,27) . "...";
                      }
                      ?>
                      <td><a href="livre.php?livre=<?php echo $livre["id_livre"]?>"><?php echo $livre["titre"]?></a></td>
                      <?php
                      echo "<td>";
                      echo "<form method='post' action='compte.php?gestion_livres#gestion-livres'>";
                      echo "<button class='btn btn-sm btn-danger mx-1 type='submit' name='" . $livre["id_livre"] . "'>Supprimer</button>";
                      echo "</td>";
                      echo "<td>" . $livre["auteur"] . "</td>";
                      echo "<td>" . $livre["editeur"] . "</td>";
                      $livre["prix"] = number_format($livre["prix"], 2, '.', '');
                      echo "<td>" . $livre["prix"] . " € </td>";
                      echo "<td>" . $livre["categorie"] . "</td>";
                      echo "<td>";
                      echo "<button type='button' class='btn btn-sm btn-primary' data-bs-toggle='popover' title='' data-bs-content='" . $livre["mots_cles"] . "'>keywords</button>";
                      echo "</td>";
                      echo "<td>" . $livre["format"] . "</td>";
                      echo "<td>" . $livre["largeur"] . "x" . $livre["epaisseur"] . "x" . $livre["longueur"] . "</td>";
                      echo "<td>" . $livre["poids"] . " g" . "</td>";
                      echo "<td>" . $livre["EAN"] . "</td>";
                      echo "<td>" . $livre["ISBN"] . "</td>";
                      echo "</tr>";
                    }
                    ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      <?php
      }
      // ------------------------------------------------------------------------------------------------------------------------------------------
      // ====================================================== PAGE DE GESTION DES COMPTES =======================================================
      // ------------------------------------------------------------------------------------------------------------------------------------------
      else if(isset($_GET["gestion_comptes"]) && $_SESSION["is_admin"] == 1) // si la variable $_GET["gestion_comptes"] est présente est chargée et que l'utilisateur est un admin
      {
      ?>
        <!-- centre -->
        <section class="h-100 gradient-custom flex-grow-1">
          <div class="container">
            <h5 class="mt-5"><a class="text-decoration-none text-success" href="compte.php">Mon compte</a> / Gestion des comptes</h5>
            <h1 id="gestion-comptes" class="mb-4">Gestion des comptes</h1>
            <hr>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Action</th>
                    <th>Email</th>
                    <th>Type de compte</th>
                    <th>Date d'inscription</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                    $comptes = recuperation_table(connexionBDD("vert_galant", "root", ""), 1); // chargement des informations des comptes

                    // ================================================
                    // ====== MISE A JOUR DU STATUT D'UN COMPTE =======
                    // ================================================
                    for($i = 0; $i <= max(array_keys($comptes)); $i++)
                    {
                      if(isset($_POST[$i])) // si une variable correspondant à l'id d'un compte existe, c'est que l'admin cherche à changer les permissions d'un compte
                      {
                        if($comptes[$i]["is_admin"] == 1) // si l'utilisateur est un admin
                        {
                            $nouvelle_valeur = 0; // il va devenir un simple utilisateur
                        }
                        else // et vice-versa
                        {
                            $nouvelle_valeur = 1;
                        }
                            $requete = "UPDATE comptes SET is_admin = :is_admin WHERE id_compte = :lid";
                            $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
                            $requete->execute(array("is_admin" => $nouvelle_valeur, "lid" => $i)); // mise à jour du statut du compte dans la base de données
                      }
                    // ======================================
                    // ====== SUPPRESSION D'UN COMPTE =======
                    // ======================================
                      if(isset($_POST["supprimer".$i])) // si une variable correspondant à "supprimer" suivi de l'id d'un compte existe, c'est que l'admin cherche à supprimer un compte
                      {
                        suppression_ligne(connexionBDD("vert_galant", "root", ""), 1, $i); // et donc le compte est suppprimé de la base de données
                      }
                    }
                    
                    $comptes = recuperation_table(connexionBDD("vert_galant", "root", ""), 1); // mise à jour de la table des comptes après ajout ou suppression
                    
                    // ====================================================
                    // ====== AFFICHAGE DES INFORMATIONS DES LIVRES =======
                    // ====================================================
                    foreach ($comptes AS $compte)
                    {
                      if($compte["id_compte"] != $_SESSION["id_compte"])
                      {
                        echo "<tr>";
                        echo "<td>" . $compte["id_compte"] . "</td>";
                        echo "<td><a href='profil.php?id_compte=" . $compte["id_compte"] . "'>" . $compte["identifiant"] . "</a></td>";
                        $statut = $compte["is_admin"] == "1" ? "Admin" : "Utilisateur";
                        echo "<td>";
                        echo "<form method='post' action='compte.php?gestion_comptes#gestion-comptes'>";
                        echo "<button class='btn btn-sm btn-danger mx-1 type='submit' name='supprimer" . $compte["id_compte"] . "'>Supprimer</button>";
                        if ($compte["is_admin"] == 1)
                        {
                          echo "<button class='btn btn-sm btn-secondary mx-1' type='submit' name='" . $compte["id_compte"] . "'>Rétrograder</button>";
                        }
                        else
                        {
                          echo "<button class='btn btn-sm btn-success mx-1' type='submit' name='" . $compte["id_compte"] . "'>Promouvoir</button>";
                        }
                        echo "</form>";
                        echo "</td>";
                        echo "<td>" . $statut . "</td>";
                        echo "<td>" . $compte["email"] . "</td>";
                        echo "<td>" . $compte["date_inscription"] . "</td>";
                        echo "<td>" . $compte["prenom"] . "</td>";
                        echo "<td>" . $compte["nom"] . "</td>";
                        echo "</tr>";
                      }
                    }
                    ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      <?php
      }
      // ===========================================================================================================================
      // ====== PAGE S'AFFICHANT POUR L'UTILISATEUR CONNECTÉ NON ADMINISTRATEUR ESSAYANT D'ACCEDER A UNE PAGE ADMINISTRATEUR =======
      // ===========================================================================================================================
      else
      {
        page_erreur("Vous n'avez pas accès à cette page");
      }
      ?>
    <!-- pied de page -->
    <?php
      include("footer.php");
      ?>
    <!-- chargement du javascript -->
    <script src="js/bootstrap_bundle_min.js"></script>
    <!-- fonction js pour le fonctionnement des fenêtres popup -->
    <script>
      var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
      var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
      })
    </script>
  </body>
</html>
