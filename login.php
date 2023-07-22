<?php
    session_start(); // démarrage de la session
    include("fonction/fonctions.php"); // chargement des fonctions
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
	    <meta name="keywords" content="connexion, login, identifiant, mot de passe, inscription" />
        <?php
            if(isset($_GET["connexion"])) // Le titre de la page et sa description varie en fonction de l'url
            {
                echo "<meta name='description' content='page de connexion' />";
                echo "<title>Connexion</title>";
            }
            else if(isset($_GET["inscription"]))
            {
                echo "<meta name='description' content='page d'inscription' />";
                echo "<title>Inscription</title>";
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
    <body class="connexion-body">
    <?php
        // ---------------------------------------------------------------------------------------------------------------------------------
        // ------------------------------------------------------- PAGE DE CONNEXION -------------------------------------------------------
        // ---------------------------------------------------------------------------------------------------------------------------------
        if(isset($_GET["connexion"]))
        {
            // =========================================================
            // ================ TENTATIVE DE CONNEXION =================
            // =========================================================
            if(isset($_POST["identifiant"]) && isset($_POST["mot_de_passe"])) // Si l'utilisateur a posté un identifiant et un mot de passe, alors...
            {
            if(!is_null(test_connexion(connexionBDD("vert_galant", "root", "")))) // La fonction retourne les infos du compte s'il existe, donc on vérifie si elle retourne quelque chose
            {
                $informations = test_connexion(connexionBDD("vert_galant", "root", "")); // Puisque le compte existe, ses informations sont récupérées
                // Les informations du compte sont récupérées dans la session
                $_SESSION["id_compte"] = $informations["id_compte"];
                $_SESSION["prenom"] = $informations["prenom"];
                $_SESSION["nom"] = $informations["nom"];
                $_SESSION["email"] = $informations["email"];
                $_SESSION["identifiant"] = $informations["identifiant"]; 
                $_SESSION["mot_de_passe"] = $informations["mot_de_passe"];
                $_SESSION["date_inscription"] = $informations["date_inscription"];
                $_SESSION["is_admin"] = $informations["is_admin"];

                // la page annonce à l'utilisateur qu'il est connecté
            ?>
                <div class="login-page">
                    <div class="form">
                        <a href="index.php"> <img src="images/nav/vert_galant_nav.png" alt="Librairie Vert-galant" class="img-fluid mx-auto d-block mb-5" style="max-width: 100%"></a>
                        <div class="card-title mb-4 text-start fs-4 text-center">Vous êtes connecté</div>
                        <p class="message" style="color: grey; font-size:14px;">Vous allez être redirigé dans un instant</p>
                    </div>
                </div>
            <?php
                header("refresh: 2; url='index.php'"); // redirection de l'utilisateur vers la page d'accueil
            }
            else // Si la fonction ne retourne rien, alors...
            {
                $_SESSION["erreur"] = "ERREUR : identifiants invalides"; // On crée une variable d'erreur
                header("refresh: 0"); // on recharge la page. Le menu de connexion s'affichera à nouveau, avec l'erreur
            }
            }
            // =========================================================
            // =============== ETAT INITIAL DE LA PAGE =================
            // =========================================================
            else if(!isset($_SESSION["identifiant"]) && !isset($_SESSION["mot_de_passe"]))
            {
            ?>
                <div class='login-page'>
                    <div class='form'>
                        <form class='login-form' method='post' action=''>
                            <a href='index.php'> <img src='images/nav/vert_galant_nav.png' alt='Librairie Vert-galant' class='img-fluid mx-auto d-block mb-4' style='max-width: 100%'></a>
                            <div class='card-title mb-2 text-start fs-4'>Connexion</div>
                            <input type="text" placeholder="Nom d'utilisateur" name="identifiant"required/>
                            <input type='password' placeholder='Mot de passe' name='mot_de_passe'required/>
                            <button>Me connecter</button>
                            <p class='message'>Vous n'avez pas de compte ? <a href='login.php?inscription'>Créer un compte</a></p>
            <?php
                if(isset($_SESSION["erreur"])) // Si $_SESSION["erreur"] existe, c'est nécessairement que des identifiants invalides ont été entrés 
                { 
                    echo "<div class='erreur_login'>" . $_SESSION["erreur"] ."</div>"; // Un message d'erreur s'affiche
                    session_destroy(); // La session est détruite, parce qu'il n'est pas nécessaire que le message d'erreur qu'elle contient perdure si l'erreur n'est pas reproduite
                }
            ?>
                        </form>
                    </div>
                </div>
            <?php
            }
            // =========================================================
            // ====== AFFICHAGE POUR L'UTILISATEUR DEJA CONNECTE =======
            // =========================================================
            else
            {
            ?>
                <div class='login-page'>
                    <div class='form'>
                        <a href='index.php'><img src='images/nav/vert_galant_nav.png' alt='Librairie Vert-galant' class='img-fluid mx-auto d-block mb-5' style='max-width: 100%'></a>
                        <div class='card-title mb-5 text-start fs-4 text-center'>Vous êtes déjà connecté</div>
                        <form method='post' action='index.php'>
                            <button>Retour à l'accueil</button>
                        </form>
                    </div>
                </div>
            <?php
            }
        }
        // ---------------------------------------------------------------------------------------------------------------------------------
        // ------------------------------------------------------ PAGE D'INSCRIPTION -------------------------------------------------------
        // ---------------------------------------------------------------------------------------------------------------------------------
        else if(isset($_GET["inscription"]))
        {
            if(!isset($_SESSION["identifiant"]) && !isset($_SESSION["mot_de_passe"])) // Si l'utilisateur n'est pas connecté, alors...
            {
            // ==========================================================
            // ================ TENTATIVE D'INSCRIPTION =================
            // ==========================================================
                if(isset($_POST["prenom"]) && isset($_POST["nom"]) && isset($_POST["email"]) &&
                    isset($_POST["identifiant"]) && isset($_POST["mot_de_passe"])) // Si l'utilisateur a posté tout ce qu'il faut pour s'inscrire, alors...
                {
                    // ''''''''''''''''''''''''''''''''''''''''
                    // ..... MESSAGES D'ERREUR POTENTIELS .....
                    // ........................................
                    $nbErreurs = 0; // On part du principe qu'il n'y aura pas d'erreurs
                    
                    $nbMaxPrenom = 30;
                    $nbMaxNom = 50;
                    $nbMaxMail = 255;
                    $nbMaxIdentifiant = 100;
                    $nbMaxMdp = 100;
            
                    if($_POST["prenom"] == "")
                    {
                        $_SESSION["erreur" . $nbErreurs] = "Veuillez entrer votre prénom";
                        $nbErreurs++;
                    }
                    if($_POST["nom"] == "")
                    {
                        $_SESSION["erreur" . $nbErreurs] = "Veuillez entrer votre nom";
                        $nbErreurs++;
                    }
                    if(strlen($_POST["prenom"]) > $nbMaxPrenom )
                    {
                        $_SESSION["erreur" . $nbErreurs] = "Le prénom doit contenir un nombre de caractère inférieur à " . $nbMaxPrenom;
                        $nbErreurs++;
                    }
                    if(strlen($_POST["nom"]) > $nbMaxNom )
                    {
                        $_SESSION["erreur" . $nbErreurs] = "Le nom doit contenir un nombre de caractère inférieur à . " . $nbMaxNom;
                        $nbErreurs++;
                    }
                    if(strlen($_POST["email"]) > $nbMaxMail )
                    {
                        $_SESSION["erreur" . $nbErreurs] = "L'email doit contenir un nombre de caractère inférieur à " . $nbMaxMail;
                        $nbErreurs++;
                    }
                    if(strlen($_POST["identifiant"]) > $nbMaxIdentifiant )
                    {
                        $_SESSION["erreur" . $nbErreurs] = "Le nom d'utilisateur doit contenir un nombre de caractère inférieur à " . $nbMaxIdentifiant;
                        $nbErreurs++;
                    }
                    if(strlen($_POST["mot_de_passe"]) > $nbMaxMdp )
                    {
                        $_SESSION["erreur" . $nbErreurs] = "Le mot de passe doit contenir un nombre de caractère inférieur à " . $nbMaxMdp;
                        $nbErreurs++;
                    }
                    if(strlen($_POST["email"]) < 3 || !str_contains($_POST["email"],"@") || substr_count($_POST["email"],"@") > 1)
                    {
                        $_SESSION["erreur" . $nbErreurs] = "L'adresse email entrée est invalide";
                        $nbErreurs++;
                    }
                    if(strlen($_POST["identifiant"]) < 3)
                    {
                        $_SESSION["erreur" . $nbErreurs] = "L'identifiant doit contenir un nombre de caractère supérieur à 3";
                        $nbErreurs++;
                    }
                    if(strlen($_POST["mot_de_passe"]) < 8)
                    {
                        $_SESSION["erreur" . $nbErreurs] = "Le mot de passe doit contenir un nombre de caractère supérieur à 8";
                        $nbErreurs++;
                    }
                    if(verif_doublon_identifiant(connexionBDD("vert_galant", "root", "")))
                    {
                        $_SESSION["erreur" . $nbErreurs] = "Identifiant déjà existant. Veuillez en entrer un autre";
                    }
                    // ''''''''''''''''''''''''''''''''''''''
                    // ..... CREATION DE COMPTE REUSSIE .....
                    // ......................................
                    if($nbErreurs == 0 && !verif_doublon_identifiant(connexionBDD("vert_galant", "root", "")))
                    {
                        echo "<div class='login-page'>";
                        echo "<div class='form'>";
                        echo "<a href='index.php'> <img src='images/nav/vert_galant_nav.png' alt='Your Image' class='img-fluid mx-auto d-block mb-5' style='max-width: 100%'></a>";
                        echo "<div class='card-title mb-4 text-start fs-4 text-center'>Votre compte a bien été crée</div>";
                        echo "<p class='message' style='color: grey; font-size:14px;'>Vous allez être redirigé dans un instant</p>";
                        echo "</div>";
                        echo "</div>";
                        
                        // Les informations du compte sont récupérées dans la session
                        $_SESSION["id_compte"] = creation_compte(connexionBDD("vert_galant", "root", ""), $_POST["prenom"], $_POST["nom"], $_POST["email"], $_POST["identifiant"], $_POST["mot_de_passe"]);
            
            
                        $_SESSION["identifiant"] = $_POST["identifiant"];
                        $_SESSION["prenom"] = $_POST["prenom"];
                        $_SESSION["nom"] = $_POST["nom"];
                        $_SESSION["email"] = $_POST["email"];
                        $_SESSION["identifiant"] = $_POST["identifiant"]; 
                        $_SESSION["mot_de_passe"] = $_POST["mot_de_passe"];
                        $_SESSION["date_inscription"] = date('d-m-Y');
                        $_SESSION["is_admin"] = 0;
            
                        header("refresh: 2; url='index.php'");
                    }
                }
                // =========================================================
                // =============== ETAT INITIAL DE LA PAGE =================
                // =========================================================
                if(!isset($_SESSION["identifiant"]) && !isset($_SESSION["mot_de_passe"]))
                {
                ?>
                    <div class='login-page'>
                    <div class='form'>
                    <form class='login-form' method='post' action=''>
                    <a href='index.php'> <img src='images/nav/vert_galant_nav.png' alt='Your Image' class='img-fluid mx-auto d-block mb-4' style='max-width: 100%'></a>
                    <div class='card-title mb-2 text-start fs-4'>Inscription</div>
                    <div class='row'>
                        <div class='col'><input type='text' placeholder='Prénom' name='prenom'required/></div>
                        <div class='col'><input type='text' placeholder='Nom' name='nom'required/></div>
                    </div>
            
                    <input type='email' placeholder='Adresse email' name='email'required/>
                    <input type="text" placeholder="Nom d'utilisateur" name="identifiant"required/>
                    <input type='password' placeholder='Mot de passe' name='mot_de_passe'required/>
            
                    <button>M'inscrire</button>
                    <p class='message'>Vous avez déjà un compte ? <a href='login.php?connexion'>Se connecter</a></p>
                <?php
                for($i = 0; $i < 6; $i++)
                {
                    if(isset($_SESSION["erreur".$i])) // Si $_SESSION["erreur"] existe, c'est nécessairement que les identifiants qui ont été entrés sont invalides
                    {
                        echo "<div class='erreur_login text-left text-start'>" .$_SESSION["erreur".$i] ."</div>"; // un message d'erreur s'affiche donc
                        
                    }
                } 
                if(isset($_SESSION["erreur0"])) // destruction après, pour que tous les messages soient affichés
                {
                    session_destroy(); // la session est détruite, parce qu'il n'est pas nécessaire que le message d'erreur qu'elle contient perdure, si elle n'est pas reproduite
                }
                echo "</form>";
                echo "</div>";
                echo "</div>";
                }
            }
            // =========================================================
            // ====== AFFICHAGE POUR L'UTILISATEUR DEJA CONNECTE =======
            // =========================================================
            else
            {
            ?>
                <div class='login-page'>
                    <div class='form'>
                        <a href='index.php'><img src='images/nav/vert_galant_nav.png' alt='Librairie Vert-galant' class='img-fluid mx-auto d-block mb-5' style='max-width: 100%'></a>
                        <div class='card-title mb-5 text-start fs-4 text-center'>Vous êtes déjà connecté</div>
                        <form method='post' action='index.php'>
                            <button>Retour à l'accueil</button>
                        </form>
                    </div>
                </div>
            <?php
            }
        }
        // ========================================================================================
        // ====== AFFICHAGE DANS LE CAS OU L'UTILISATEUR ENTRE DE MAUVAISES VARIABLES $_GET =======
        // ========================================================================================
        else
        {
        ?>
        <div class='login-page'>
            <div class='form'>
                <a href='index.php'><img src='images/nav/vert_galant_nav.png' alt='Librairie Vert-galant' class='img-fluid mx-auto d-block mb-5' style='max-width: 100%'></a>
                <div class='card-title mb-5 text-start fs-4 text-center'>Erreur : cette page n'existe pas</div>
                <form method='post' action='index.php'>
                    <button>Retour à l'accueil</button>
                </form>
            </div>
        </div>
        <?php
        }

    ?>
    <!-- chargement du javascript -->
    <script src="js/bootstrap_bundle_min.js" async></script>
  </body>
</html>

