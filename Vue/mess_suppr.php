<?php
    if (empty($sousmenucompte)){
        $entete = entete("Mon site / Mes Messages");
        $menu = menu("moncompte");
        if (isset($_SESSION["iduser"])){
            $sousmenu = sousmenu("mesmess");
            $contenu = messlire($_SESSION["idmess"]);
        } else {
            $contenu= "Vous n'êtes pas connectés ! Connectez vous avant.";
        }
        $pied = pied();
    }

    include 'gabarit.php';
    
    
        
function messlire($idmess){
    ob_start();
    
	require ('Modele/connexion.php');
        
        $q=$bdd->prepare("DELETE FROM messages WHERE id='$idmess'");
        $q -> execute();

        echo "<br/><br/>Le message a bien été supprimé.";

    $meslire = ob_get_clean();
    return $meslire;
}
?>