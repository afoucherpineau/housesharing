<?php
    require ('Modele/connexion.php');


    // fonction qui cherche le mot de passe d'un utilisateur avec un identifiant dans la base de données
    function mdp($db,$identifiant){
        $id=htmlspecialchars($identifiant,ENT_QUOTES);
        $reponse = $db->query('SELECT * FROM user WHERE identifiant="'.$id.'"');
        return $reponse;
    }

    // fonction qui cherche le mot de passe d'un utilisateur avec un identifiant dans la base de données
    function utilisateurs($db){
        $reponse = $db->query('SELECT identifiant FROM user');
        return $reponse;
    }

    
?>
