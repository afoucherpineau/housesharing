<?php

    require ('Modele/connexion.php');

    // Controleur pour gérer le formulaire de connexion des utilisateurs
    global $erreur;
    if(isset($_GET['cible']) && $_GET['cible']=="verif") { // L'utilisateur vient de valider le formulaire de connexion
        if(!empty($_POST['identifiant']) && !empty($_POST['mdp'])){ // L'utilisateur a rempli tous les champs du formulaire
            include("Modele/utilisateurs.php");

            $reponse = mdp($bdd,$_POST['identifiant']);
            if ($reponse->rowcount()==0){  // L'utilisateur n'a pas été trouvé dans la base de données
                $erreur = "Utilisateur inconnu";
                include("Vue/accueil.php");
            } else { // utilisateur trouvé dans la base de données
                $ligne = $reponse->fetch();
                if(sha1($_POST['mdp'])!=$ligne['mdp']){ // Le mot de passe entré ne correspond pas à celui stocké dans la base de données
                    $erreur = "Mot de passe incorrect";
                    include("Vue/accueil.php");
                } else { // mot de passe correct, on affiche la page d'accueil
                    $_SESSION = $ligne;
                    include("Vue/accueil.php");
                }
            }
        } else { // L'utilisateur n'a pas rempli tous les champs du formulaire
            $erreur = "Veuillez remplir tous les champs";
            include("Vue/accueil.php");
        }
    } else { // La page de connexion par défaut
        include("Vue/non_connecte.php");
        echo("formulaire non validé");
    }
?>