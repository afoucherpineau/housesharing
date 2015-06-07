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

	// on prépare une requete SQL selectionnant la date, le titre et l'expediteur du message que l'on souhaite lire, tout en prenant soin de vérifier que le message appartient bien au membre connecté
        $iduser = $_SESSION['iduser'];
        $count=$bdd->query("SELECT titre, date, message, identifiant FROM messages INNER JOIN user ON user.iduser=messages.id_expediteur WHERE id_destinataire='$iduser'");
        $ligne = $count-> fetch();
        // On compte le nombre de lignes
        $i=0;
        while (!empty($ligne)){
            $ligne = $count-> fetch(); 
            $i++;
        }
        $nb = $i;

	if ($nb == 0) {
	echo 'Aucun message reconnu.';
	}
	else {
	// si le message a été trouvé, on l'affiche
            $iduser = $_SESSION['iduser'];
            $req=$bdd->query("SELECT titre, date, message, identifiant FROM messages INNER JOIN user ON user.iduser=messages.id_expediteur WHERE id_destinataire='$iduser'");
            $data = $req-> fetch();
	echo '<br/><br/>'.$data['date'] , ' - ' , stripslashes(htmlentities(trim($data['titre']))) , '</a> [ Message de ' , stripslashes(htmlentities(trim($data['identifiant']))) , ' ]<br /><br />';
	echo nl2br(stripslashes(htmlentities(trim($data['message']))));

	// on affiche également un lien permettant de supprimer ce message de la boite de réception
	echo '<br /><br /><a href="index.php?cible=mess_suppr&id_message='.$idmess.'">Supprimer ce message</a>';
	}

?>

    <?php
    $mesmess = ob_get_clean();
    return $mesmess;
}
?>