<?php
    if (empty($sousmenucompte)){
        $entete = entete("Mon site / Mon Compte");
        $menu = menu("moncompte");
        if (isset($_SESSION["iduser"])){
            $sousmenu = sousmenu("mesinfos");
            $contenu = vuelog();
        } else {
            $contenu = "Vous n'êtes pas connectés ! Connectez vous avant.";
        }
       
        $pied = pied();
    }

    include 'gabarit.php';
    
function vuelog(){
    ob_start();
        //Connexion à la base de donnée
        require ('Modele/connexion.php');
        
        $iduser = $_SESSION["iduser"];
        $q=$bdd->query("SELECT * FROM user WHERE iduser='$iduser'");
        $ligne = $q-> fetch();
                   
        echo "<h1>Mes informations personnelles</h1><br/>";
        echo "<div id='abcd'>Nom : ".$ligne['nom']."<br/>Prénom : ".$ligne['prenom']."<br />Nom de compte : ".$ligne['identifiant']."<br />Mail : ".$ligne['mail']."<br/>Telephone : ".$ligne['telephone']."<br/></div><p9>";
        echo "<a href='index.php?cible=modicoor'><input type='submit' value='modifier coor.' id='modicoor'></a>";
        echo "<a href='index.php?cible=modimdp'><input type='submit' value='changer mdp' id='modimdp'></a></p9> ";

    $vuelog = ob_get_clean();
    return $vuelog;
}
    
    
?>
