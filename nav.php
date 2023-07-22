<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <!-- logo de la librairie Vert-galant -->
        <a class="navbar-brand mt-2 mt-lg-0" href="index.php">
        <img src="images/nav/vert_galant_nav.png" height="40" alt="logo" loading="lazy"/>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
        </button>
        <!-- menu déroulant -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- lien vers l'accueil -->
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="index.php">Accueil</a>
                </li>
                <!-- lien vers les actualités -->
                <li class="nav-item">
                    <a class="nav-link" href="actualites.php">Actualités</a>
                </li>
                <!-- liens vers les catégories de livres -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Catégories
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="recherche.php?categorie=Roman ou nouvelles">Roman ou nouvelles</a></li>
                        <li><a class="dropdown-item" href="recherche.php?categorie=Poésie">Poésie</a></li>
                        <li><a class="dropdown-item" href="recherche.php?categorie=Théâtre">Théâtre</a></li>
                        <li><a class="dropdown-item" href="recherche.php?categorie=Essai">Essai</a></li>
                        <li><a class="dropdown-item" href="recherche.php?categorie=Philosophie">Philosophie</a></li>
                        <li><a class="dropdown-item" href="recherche.php?categorie=Art">Art</a></li>
                        <li><a class="dropdown-item" href="recherche.php?categorie=Histoire">Histoire</a></li>
                        <li><a class="dropdown-item" href="recherche.php?categorie=Musique">Musique</a></li>
                        <li><a class="dropdown-item" href="recherche.php?categorie=Littérature scientifique">Littérature scientifique</a></li>
                        <li><a class="dropdown-item" href="recherche.php?categorie=Autre">Autre</a></li>
                    </ul>
                </li>
            </ul>
            <!-- barre de recherche -->
            <form class="d-flex me-4" action="recherche.php" method="get">
                <input class="form-control me-2" style="width:280px" type="text" placeholder="Chercher un livre, auteur, thème" name="filtre">
                <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <!-- bouton vers le panier -->
                <li class="nav-item me-2">
                    <a class="nav-link" href="panier.php">
                    <i class="fas fa-shopping-cart fa-lg">
                    <?php
                        $quantite = 0; // On initialise la quantité d'articles dans le panier de l'utilisateur
                        if(isset($_SESSION)) // Si une session est initialisée, alors...
                        {
                          foreach ($_SESSION as $cle => $valeur) // Pour chaque variable, on récupère la clé. Si la variable de session correspond à un id de livre, alors la valeur renferme la quantité d'exemplaires de ce livre que l'utilisateur a mise dans son panier
                          {
                            if (strpos($cle, '_') === 0 && is_numeric(substr($cle, 1))) // Si le nom de la variable de session (la clé) a un "_" situé en son début et que le reste du nom est numérique, on sait que ce nom est un id de livre, donc...
                            {
                              $quantite += $valeur; // ...on sait que la valeur de la variable est une quantité : on l'ajoute à la quantité d'articles
                            }
                          }
                        }
                      ?>
                    <span class="badge bg-success text-white ms-1 rounded-pill"><?php echo $quantite // la quantité d'articles ajoutés au panier est affichée ?></span>
                    </i>
                    </a>
                </li>
            </ul>
            <!-- gestion du compte -->
            <ul class="navbar-nav mb-2 mb-lg-0">
              <?php
                if (isset($_SESSION["identifiant"]) && isset($_SESSION["mot_de_passe"])) // Si la session contient un identifiant et un mot de passe, c'est que l'utilisateur est connecté, donc on affiche les liens vers la gestion du compte
                {
                  ?>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="compte.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <?php $chemin = recuperation_chemin(1, $_SESSION["id_compte"]); // récupération du chemin de l'image de profil ?>
                      <img src="<?php echo $chemin // affichage de l'image de profil ?>" class='rounded-circle image_profil_nav' alt='image_profil' loading='lazy'/>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="compte.php">Mon compte</a></li>
                      <li><a class="dropdown-item" href="compte.php?souhaits">Liste de souhaits</a></li>
                      <li><a class="dropdown-item" href="compte.php?options">Options</a></li>
                    <?php
                    if($_SESSION["is_admin"] == 1) // Si l'utilisateur est un administrateur, s'affichent aussi les liens vers la gestion du site
                    {
                      ?>
                        <li><hr class='dropdown-divider'></li>
                        <li><a class='dropdown-item' href='compte.php?gestion_livres'>Gestion des livres</a></li>
                        <li><a class='dropdown-item' href='compte.php?gestion_actualites'>Gestion des actualités</a></li>
                        <li><a class='dropdown-item' href='compte.php?gestion_comptes'>Gestion des comptes</a></li>
                      <?php
                    }
                    ?>
                    <li><hr class='dropdown-divider'></li>
                      <li>
                        <form method='post' action='index.php'>
                          <input type='hidden' name='deconnexion'>
                          <button type='submit' class='dropdown-item' name='submit'>Déconnexion</button>
                        </form>
                      </li>
                    </ul>
                  </li>
                  <?php
                }
                else // Si l'utilisateur n'est pas connecté, s'affichent des liens vers la connexion ou l'inscription
                {
                  ?>
                    <ul class='navbar-nav me-auto mb-2 mb-lg-0'>
                      <li class='nav-item dropdown'>
                        <a class='nav-link dropdown-toggle' href='' role='button' data-bs-toggle='dropdown' aria-expanded='false'>Se connecter</a>
                        <ul class='dropdown-menu'>
                          <li><a class='dropdown-item' href='login.php?connexion'>Connexion</a></li>
                          <li><hr class='dropdown-divider'></li>
                          <li><a class='dropdown-item' href='login.php?inscription'>Créer un compte</a></li>
                        </ul>
                      </li>
                    </ul>
                  <?php
                }
              ?>
            </ul>
        </div>
    </div>
</nav>