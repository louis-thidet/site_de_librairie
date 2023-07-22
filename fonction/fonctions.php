<?php

// ----------------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------- FONCTIONS COMMUNES -------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------------------------------------------------------------

// ========================================
// ==== CONNEXION A LA BASE DE DONNEES ====
// ========================================
function connexionBDD($bdd, $login, $mdp) // sont pris en arguments le nom de la base de données, le compte utilisé pour s'y connecter, et son mot de passe
{
    try
    {
        $connex = new PDO("mysql" . ":host=localhost" . ";dbname=" . $bdd, $login, $mdp); // tentative de connexion à la base de données
    }
    catch(PDOException $e)
    {
        die('Erreur : ' . $e -> getMessage()); // récupération d'une éventuelle erreur
    }
    return $connex; // retourne l'objet PDO connectant à la base de données
}

// ========================================
// ====== RECUPERATIONS D'UNE TABLE =======
// ========================================
function recuperation_table($connex, $type_table) // sont pris en arguments la connexion à la BDD et le type de table à récupérer
{
    if($type_table == 1) // Si $type_table == 1, on récupère la table des comptes 
    {
        $requete = "SELECT * FROM comptes"; // requête SQL envoyée au serveur
        $requete = $connex -> prepare($requete); // la requête est préparée avant d'être exécutée, pour éviter les injections SQL
        $requete->execute(); // exécution de la requête
        $comptes = array(); // création d'un $comptes
        while ($donnees = $requete->fetch()) // Tant qu'il reste des données dans la table, elles sont entrées dans le tableau $comptes
        {
            $comptes[$donnees["id_compte"]] = array(
                "id_compte" => $donnees["id_compte"],
                "prenom" => $donnees["prenom"],
                "nom" => $donnees["nom"],
                "email" => $donnees["email"],
                "identifiant" => $donnees["identifiant"],
                "mot_de_passe" => $donnees["mot_de_passe"],
                "date_inscription" => $donnees["date_inscription"],
                "is_admin" => $donnees["is_admin"]);
        }
        return $comptes; // le tableau $compte est retourné
    }
    else if($type_table == 2) // Si $type_table == 2, on récupère la table des livres 
    {
        $requete = "SELECT * FROM livres"; // Le fonctionnement de la récupération de la table des livres ne diffère pas de la récupération de la table des comptes
        $requete = $connex -> prepare($requete);
        $requete->execute();
        $livres = array();
        while ($donnees = $requete->fetch())
        {
            $livres[$donnees["id_livre"]] = array(
                "id_livre" => $donnees["id_livre"],
                "titre" => $donnees["titre"],
                "auteur" => $donnees["auteur"],
                "prix" => $donnees["prix"],
                "categorie" => $donnees["categorie"],
                "mots_cles" => $donnees["mots_cles"],
                "editeur" => $donnees["editeur"],
                "nb_pages" => $donnees["nb_pages"],
                "format" => $donnees["format"],
                "largeur" => $donnees["largeur"],
                "longueur" => $donnees["longueur"],
                "epaisseur" => $donnees["epaisseur"],
                "poids" => $donnees["poids"],
                "EAN" => $donnees["EAN"],
                "ISBN" => $donnees["ISBN"],
                "description" => $donnees["description"]);
        }
        ksort($livres);  // permet de s'assurer que le tableau reste bien trié en fonction de l'ordre croissant des id
        return $livres;
    }
    else if($type_table == 4) // Si $type_table == 4, on récupère la table des commentaires
    {
        $requete = "SELECT * FROM commentaires";
        $requete = $connex -> prepare($requete);
        $requete->execute();
        $commentaires = array();
        $i = 1; // Les commentaires n'ont pas de colonne id, car la clé primaire de leur table est une combinaison des clés étrangères id_compte et id_livre. Pour les besoins du php, on crée un tableau $commentaires dans lequel la clé de chaque commentaire dépend de son ordre dans la table
        while ($donnees = $requete->fetch())
        {
            $commentaires[$i] = array(
                "id_compte" => $donnees["id_compte"],
                "id_livre" => $donnees["id_livre"],
                "texte" => $donnees["texte"],
                "date" => $donnees["date"]);
            $i++; // A chaque récolte d'une nouvelle ligne du tableau, $i s'incrémente, pour donner la clé de la prochaine ligne
        }
        return $commentaires;
    }
    else // Si une autre valeur que 1, 2 ou 4 est entrée en arguments, on récupère la table des actualités
    {
        $requete = "SELECT * FROM actualites";
        $requete = $connex -> prepare($requete);
        $requete->execute();
        $actualites = array();
        while ($donnees = $requete->fetch())
        {
            $actualites[$donnees["id_actualite"]] = array(
                "id_actualite" => $donnees["id_actualite"],
                "texte" => $donnees["texte"],
                "titre" => $donnees["titre"],
                "date" => $donnees["titre"]);
        }
        ksort($actualites); // permet de s'assurer que le tableau reste bien trié en fonction de l'ordre croissant des id
        return $actualites;
    } 
}

// ==========================================================================================
// ===== RECUPERATION DES INFORMATIONS D'UNE SEULE LIGNE (OU D'UNE LISTE DE SOUHAITS) =======
// ==========================================================================================
function recuperation_ligne($connex, $type_table, $filtre) // sont pris en arguments la connexion à la BDD, le type de table à récupérer et la valeur qui sert à filtrer la ligne souhaitée (les lignes, dans le cas de la liste de souhaits)
{
    if($type_table == 1) // Si $type_table == 1, on récupère un compte
    {
        $requete = "SELECT * FROM comptes WHERE id_compte = :id_compte"; // On récupère les colonnes de la table des comptes là où se trouve l'id d'un seul compte
        $requete = $connex -> prepare($requete);
        $requete->execute(array("id_compte" => $filtre)); // la requête se fait sur la valeur entrée dans $filtre, qui doit être l'id d'un compte
        if ($donnees = $requete->fetch()) // Si la requête est valide, et donc que la valeur de $filtre correspond à un id de compte existant, alors...
        {
            $compte = array( // on récolte toutes les informations du compte
                "id_compte" => $donnees["id_compte"],
                "prenom" => $donnees["prenom"],
                "nom" => $donnees["nom"],
                "email" => $donnees["email"],
                "identifiant" => $donnees["identifiant"],
                "mot_de_passe" => $donnees["mot_de_passe"],
                "date_inscription" => $donnees["date_inscription"],
                "is_admin" => $donnees["is_admin"]);
    
            return $compte; // le compte est retourné
        }
    }
    else if($type_table == 2) // Si $type_table == 2, on récupère un livre
    {
        $requete = "SELECT * FROM livres WHERE id_livre = :id_livre"; // le fonctionnement de la récupération d'un livre est identique à celui de la récupération d'un compte
        $requete = $connex -> prepare($requete);
        $requete->execute(array("id_livre" => $filtre));
        if($donnees = $requete->fetch())
        {
            $livre = array(
                "id_livre" => $donnees["id_livre"],
                "titre" => $donnees["titre"],
                "auteur" => $donnees["auteur"],
                "prix" => $donnees["prix"],
                "categorie" => $donnees["categorie"],
                "mots_cles" => $donnees["mots_cles"],
                "editeur" => $donnees["editeur"],
                "nb_pages" => $donnees["nb_pages"],
                "format" => $donnees["format"],
                "largeur" => $donnees["largeur"],
                "longueur" => $donnees["longueur"],
                "epaisseur" => $donnees["epaisseur"],
                "poids" => $donnees["poids"],
                "EAN" => $donnees["EAN"],
                "ISBN" => $donnees["ISBN"],
                "description" => $donnees["description"]);
        
            return $livre;
        }
    }
    else if($type_table == 3) // Si $type_table == 3, on récupère la liste de souhaits d'un compte
    {
        $requete = "SELECT * FROM livres_souhaites WHERE id_compte = :id_compte";
        $requete = $connex -> prepare($requete);
        $requete->execute(array("id_compte" => $filtre));
        $liste_souhaits = array();
        while ($donnees = $requete->fetch()) // Sont récupérées dans $liste_souhaits toutes les lignes correspondant à l'id_compte entré en argument, donc tous les livres souhaités par le propriétaire du compte
        {
            $liste_souhaits[$donnees["id_livre"]] = array(
                "id_compte" => $donnees["id_compte"],
                "id_livre" => $donnees["id_livre"]);
        }
        return $liste_souhaits;
    }
    else if($type_table == 4)  // Si $type_table == 4, on récupère un commentaire
    {
        $requete = "SELECT * FROM commentaires WHERE id_compte = :id_compte";
        $requete = $connex -> prepare($requete);
        $requete->execute(array("id_compte" => $filtre));
        if($donnees = $requete->fetch())
        {
            $commentaire = array(
                "id_compte" => $donnees["id_compte"],
                "id_livre" => $donnees["id_livre"],
                "texte" => $donnees["texte"],
                "date" => date('d-m-Y'));
        
            return $commentaire;
        }
    }
    else // Si $type_table == 5, on récupère une actualité
    {
        $requete = "SELECT * FROM actualites WHERE id_actualite = :id_actualite";
        $requete = $connex -> prepare($requete);
        $requete->execute(array("id_actualite" => $filtre));
        if($donnees = $requete->fetch())
        {
            $actualite = array(
                "id_actualite" => $donnees["id_actualite"],
                "titre" => $donnees["titre"],
                "texte" => $donnees["texte"],
                "date" => date('d-m-Y'));
        
            return $actualite;
        }
    }
}

// ==================================================
// ====== SUPPRESSION D'UNE LIGNE D'UNE TABLE =======
// ==================================================
function suppression_ligne($connex, $type_table, $filtre)
{
    if($type_table == 1) // Si $type_table == 1, une ligne est supprimée dans comptes
    {
        $requete = "DELETE FROM comptes WHERE id_compte = :id_compte";
        $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
        $requete->execute(array("id_compte" => $filtre)); // l'id du compte est compris dans $filtre. L'actualité est supprimée

        $requete = "DELETE FROM commentaires WHERE id_compte = :id_compte";
        $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
        $requete->execute(array("id_compte" => $filtre)); // suppresion des commentaires du compte

        $requete = "DELETE FROM livres_souhaites WHERE id_compte = :id_compte";
        $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
        $requete->execute(array("id_compte" => $filtre)); // suppresion des livres souhaités du compte

        $chemin = recuperation_chemin(1, $filtre); // récupération du chemin de l'image du compte
        if($chemin != "images/nav/profil_defaut.png") // si l'image de profil n'est pas l'image par défaut
        {
            unlink($chemin); // suppression de l'image
        }

        $requete = "SELECT MAX(id_compte) AS id_max FROM comptes";
        $requete = connexionBDD("vert_galant", "root", "")->prepare($requete);
        $requete->execute();
        $id_max = $requete->fetch(); // obtention du plus grand id de livre après la suppression
        
        $id_max = $id_max['id_max'];
        
        $requete = "ALTER TABLE comptes AUTO_INCREMENT = " . ($id_max + 1);
        $requete = connexionBDD("vert_galant", "root", "")->prepare($requete);
        $requete->execute(); // mise à jour de l'incrément. Grâce à cela, l'id d'un livre supprimé peut redevenir utilisable
    }
    else if($type_table == 2) // Si $type_table == 2, une ligne est supprimée dans livres
    {
        $requete = "DELETE FROM livres WHERE id_livre = :id_livre";
        $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
        $requete->execute(array("id_livre" => $filtre)); // l'id du livre est compris dans $filtre. L'actualité est supprimée
        
        $requete = "DELETE FROM commentaires WHERE id_livre = :id_livre";
        $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
        $requete->execute(array("id_livre" => $filtre)); // les commentaires du livre sont supprimés

        $requete = "DELETE FROM livres_souhaites WHERE id_livre = :id_livre";
        $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
        $requete->execute(array("id_livre" => $filtre)); // les souhaits sont supprimés

        $chemin = recuperation_chemin(2, $filtre); // récupération du chemin de l'image du livre
        unlink($chemin); // suppression de l'image

        $requete = "SELECT MAX(id_livre) AS id_max FROM livres";
        $requete = connexionBDD("vert_galant", "root", "")->prepare($requete);
        $requete->execute();
        $id_max = $requete->fetch(); // obtention du plus grand id de livre après la suppression
        
        $id_max = $id_max['id_max'];
        
        $requete = "ALTER TABLE livres AUTO_INCREMENT = " . ($id_max + 1);
        $requete = connexionBDD("vert_galant", "root", "")->prepare($requete);
        $requete->execute(); // mise à jour de l'incrément. Grâce à cela, l'id d'un livre supprimé peut redevenir utilisable
    }
    else if($type_table == 3) // Si $type_table == 3, une ligne est supprimée dans souhaits
    {
        $requete = "DELETE FROM livres_souhaites WHERE id_livre = :id_livre AND id_compte = :id_compte";
        $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
        $requete->execute(array("id_livre" => $filtre,
                                "id_compte" => $_SESSION["id_compte"]));
    }
    else if($type_table == 4) // Si $type_table == 4, une ligne est supprimée dans commentaires
    {
        $requete = "DELETE FROM commentaires WHERE id_compte = :id_compte AND id_livre = :id_livre";
        $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
        $requete->execute(array("id_compte" => $_SESSION["id_compte"],
                                "id_livre" => $filtre));
    }
    else // Si $type_table == 5, une ligne est supprimée dans actualites
    {
        $requete = "DELETE FROM actualites WHERE id_actualite = :id_actualite";
        $requete = connexionBDD("vert_galant", "root", "") -> prepare($requete);
        $requete->execute(array("id_actualite" => $filtre)); // l'id de l'actualité est compris dans $filtre. L'actualité est supprimée
        
        $requete = "SELECT MAX(id_actualite) AS id_max FROM actualites";
        $requete = connexionBDD("vert_galant", "root", "")->prepare($requete);
        $requete->execute();
        $id_max = $requete->fetch(); // obtention du plus grand id d'actualité après la suppression
        
        $id_max = $id_max['id_max'];
        
        $requete = "ALTER TABLE actualites AUTO_INCREMENT = " . ($id_max + 1);
        $requete = connexionBDD("vert_galant", "root", "")->prepare($requete);
        $requete->execute(); // mise à jour de l'incrément. Grâce à cela, l'id d'une actualité supprimée peut redevenir utilisable
    }
}

// =======================================================
// ====== AFFICHAGE D'UNE PAGE DE MESSAGE D'ERREUR =======
// =======================================================
function page_erreur($message) // affichage d'une page d'erreur en fonction de la chaîne de caractère passée en argument. Utilisé pour l'affichage des pages où l'utilisateur ne devrait pas aller ou qui n'existent pas
{
?>
    <!-- centre -->
    <section class="h-100 gradient-custom flex-grow-1">    
    <div class="container">
        <div class="row">
        <div class="col-12 text-center">
            <div class="mt-5 mb-5">&nbsp;</div>
            <h1 class="mt-5 mb-5"><?php echo $message // affichage de la chaîne de caractère?></h1>
            <form action="index.php" method="post">
            <button type="submit" class="btn btn-success mb-5">Retour à l'accueil</button>
            </form>
        </div>
        </div>
    </div>
    </section>
<?php
}

// =====================================================
// ====== VERIFICATION DE L'EXTENSION DE L'IMAGE =======
// =====================================================
function recuperation_chemin($type_image, $id) // le type d'image = le dossier dans lequel l'image va être cherchée, et l'id = le nom de l'image
{
    if($type_image == 1) // image de profil
    {
        $chemin = "images/comptes/";
    }
    else // image de livre
    {
        $chemin = "images/livres/";
    }

    if(file_exists($chemin . $id . ".jpg")) { // Si un fichier existe dans le chemin et avec l'extension .jpg, alors...
        $chemin = $chemin . $id . ".jpg"; // on retourne le chemin vers le jpg
    } else if(file_exists($chemin . $id . ".jpeg")) {
        $chemin = $chemin . $id . ".jpeg";
    } else if(file_exists($chemin . $id . ".png")) {
        $chemin = $chemin . $id . ".png";
    } else if(file_exists($chemin . $id . ".gif")) {
        $chemin = $chemin . $id . ".gif";
    }
    else // le cas restant est celui de l'image de profil qui n'a pas été choisie, et donc est celle de base
    {
        $chemin = "images/nav/profil_defaut.png";
    }
    return $chemin; // le chemin pour obtenir l'image est retourné
}

// ======================================
// ====== DECONNEXION D'UN COMPTE =======
// ======================================
function deconnexion()
{
    if(isset($_POST["deconnexion"])) // Si l'utilisateur clique sur le bouton déconnexion du nav, alors...
    {
        unset($_SESSION); // le tableau $_SESSION disparaît
        session_destroy(); // et la session est détruite, donc l'utilisateur est déconnecté
    }
}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------- FONCTIONS D'AJOUT DE LIGNES DANS LA BASE DE DONNEES ---------------------------------------------------
// ----------------------------------------------------------------------------------------------------------------------------------------------------------

// ======================================
// ========== AJOUT D'UN LIVRE ==========
// ======================================
function ajout_livre($connex, $titre, $auteur, $editeur, $description, $prix, $categorie, $mots_cles, $format, $largeur, $longueur, $epaisseur, $poids, $EAN, $ISBN, $nb_pages) // les informations du livre ajouté sont prises en arguments
{
    $requete = $connex -> prepare("INSERT INTO livres (titre, auteur, editeur, description, prix, categorie, mots_cles, format, largeur, longueur, epaisseur, poids, EAN, ISBN, nb_pages) VALUE (:titre, :auteur, :editeur, :description, :prix, :categorie, :mots_cles, :format, :largeur, :longueur, :epaisseur, :poids, :EAN, :ISBN, :nb_pages)"); // requête SQL d'insertion
    $requete -> execute(array(
        "titre" => $titre,
        "auteur" => $auteur,
        "editeur" => $editeur,
        "description" => $description,
        "prix" => $prix,
        "categorie" => $categorie,
        "mots_cles" => $mots_cles,
        "format" => $format,
        "largeur" => $largeur,
        "longueur" => $longueur,
        "epaisseur" => $epaisseur,
        "poids" => $poids,
        "EAN" => $EAN,
        "ISBN" => $ISBN,
        "nb_pages" => $nb_pages)); // exécution de la requête
}

// =============================================================
// ========== AJOUT A LA LISTE DE SOUHAITS D'UN LIVRE ==========
// =============================================================
function ajout_liste_souhaits($connex, $id_livre, $id_compte) // les informations du livre souhaité sont prises en arguments
{
    $requete = "SELECT * FROM livres_souhaites WHERE id_livre = :id_livre AND id_compte = :id_compte"; // requête pour rechercher un compte correspondant aux identifiants entrés
    $requete = $connex -> prepare($requete);

    $requete->execute(array("id_livre" => $id_livre, "id_compte" => $id_compte)); // application des valeurs entrées par l'utilisateur aux paramètres de la requête

    if (!$requete->fetch()) // Si la requête est valide, et donc qu'un compte correspond aux identifiants existe, alors...
    { 
        $requete = "INSERT INTO livres_souhaites (id_livre, id_compte) VALUE (:id_livre, :id_compte)"; // requête SQL d'insertion
        $requete = $connex -> prepare($requete); 
        $requete -> execute(array(
            "id_livre" => $id_livre,
            "id_compte" => $id_compte)); // exécution de la requête
    }
}

// ============================================
// ========== AJOUT D'UN COMMENTAIRE ==========
// ============================================
function ajout_commentaire($connex, $id_livre, $id_compte, $commentaire) // les informations du commentaire sont prises en arguments
{
    $requete = "INSERT INTO commentaires (id_livre, id_compte, texte, date) VALUE (:id_livre, :id_compte, :commentaire, :date)"; // requête SQL d'insertion
    $requete = $connex -> prepare($requete); 
    $requete -> execute(array(
        "id_livre" => $id_livre,
        "id_compte" => $id_compte,
        "commentaire" => $commentaire,
        "date" => date('d-m-Y'))); // exécution de la requête
}

// ============================================
// ========== AJOUT D'UNE ACTUALITÉ ===========
// ============================================
function ajout_actualite($connex, $texte, $titre) // les informations de l'actualité sont prises en arguments
{
    $requete = "INSERT INTO actualites (texte, titre, date) VALUE (:texte, :titre, :date)"; // requête SQL d'insertion
    $requete = $connex -> prepare($requete); 
    $requete -> execute(array(
        "texte" => $texte,
        "titre" => $titre,
        "date" => date('d-m-Y'))); // exécution de la requête
}

// ----------------------------------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------- FONCTIONS DE CONNEXION, D'INSCRIPTION ET GESTION DES COMPTES ----------------------------------------------
// ----------------------------------------------------------------------------------------------------------------------------------------------------------

// ========================================
// ========== CREATION DU COMPTE ==========
// ========================================
function creation_compte($connex, $prenom, $nom, $email, $identifiant, $mot_de_passe)
{
    date_default_timezone_set('Europe/Paris'); // Pour que la date d'inscription soit à coup sûr celle de France, il faut paramétrer dans php le fuseau horaire de Paris

    $requete = "INSERT INTO comptes (prenom, nom, email, identifiant, mot_de_passe, date_inscription) VALUE (:prenom, :nom, :email, :identifiant, :mot_de_passe, :date_inscription)"; // requête d'insertion des infos du compte
    $requete = $connex -> prepare($requete);
    $requete -> execute(array("prenom" => $prenom,
                            "nom" => $nom,
                            "email" => $email,
                            "identifiant" => $identifiant,
                            "mot_de_passe" => $mot_de_passe,
                            "date_inscription" => date('d-m-Y'))); // exécution de la requête

    $id_compte = $connex->lastInsertId(); // récupération de l'id assigné au compte pour que l'utilisateur puisse directement utiliser son compte après sa création, sans se reconnecter. $id_compte va être assigné à $_SESSION["id_compte"]
    return $id_compte;
}

// ========================================
// ========== CONNEXION AU SITE ===========
// ========================================
function test_connexion($connex)
{
    $requete = "SELECT * FROM comptes WHERE identifiant = :identifiant AND mot_de_passe = :mot_de_passe"; // requête pour rechercher un compte correspondant aux identifiants entrés
    $requete = $connex -> prepare($requete);

    $requete->execute(array("identifiant" => $_POST["identifiant"], "mot_de_passe" => $_POST["mot_de_passe"])); // application des valeurs entrées par l'utilisateur aux paramètres de la requête

    if($donnees = $requete->fetch()) // Si la requête est valide, et donc qu'un compte correspond aux identifiants existe, alors...
    {
        $informations = array( // les informations du compte sont récupérées
            "id_compte" => $donnees["id_compte"],
            "prenom" => $donnees["prenom"],
            "nom" => $donnees["nom"],
            "email" => $donnees["email"],
            "identifiant" => $donnees["identifiant"],
            "mot_de_passe" => $donnees["mot_de_passe"],
            "date_inscription" => $donnees["date_inscription"],
            "is_admin" => $donnees["is_admin"]
        );
        return $informations; // Le tableau contenant les informations est retourné
    }
}

// =====================================================
// ====== VERIFICATION DES IDENTIFIANTS DOUBLONS =======
// =====================================================
function verif_doublon_identifiant($connex) // Cette requête permet de vérifier l'entrée d'un identifiant doublon lors de l'inscription d'un compte
{
    $requete = "SELECT identifiant FROM comptes WHERE identifiant = :identifiant"; // La requête permet de chercher si un identifiant correspondant à celui désiré par l'utilisateur existe déjà
    $requete = $connex -> prepare($requete);

    $requete->execute(array("identifiant" => $_POST["identifiant"])); // application de la valeur entrée par l'utilisateur à la requête SQL

    if ($requete->fetch())
    {
        return true; // Si la requête, qui recherche l'identifiant désiré par l'utilisateur dans la base de données, est valide (donc trouve quelque chose), c'est que l'identifiant entré par l'utilisateur existe déjà, donc l'inscription n'aura pas lieu
    }
    else
    {
        return false;
    }
}

// ===============================================
// ====== MISE A JOUR DES DONNEES DU COMPTE ======
// ===============================================
function maj_donnees($connex, $type_maj, $entree)
{
    if($type_maj == 2) // Si $type_maj == 2, il y a mise à jour de l'image de l'adresse email
    {
        $requete = "UPDATE comptes SET email = :email WHERE id_compte = :id_compte";
        $requete = $connex -> prepare($requete);
        $requete->execute(array("email" => $entree, "id_compte" => $_SESSION["id_compte"]));
        $_SESSION["email"] = $entree;
    }
    else if($type_maj == 3) // Si $type_maj == 3, il y a mise à jour du mot de passe
    {
        $requete = "UPDATE comptes SET mot_de_passe = :mot_de_passe WHERE id_compte = :id_compte";
        $requete = $connex -> prepare($requete);
        $requete->execute(array("mot_de_passe" => $entree, "id_compte" => $_SESSION["id_compte"]));
        $_SESSION["mot_de_passe"] = $entree;
    }
}

?>