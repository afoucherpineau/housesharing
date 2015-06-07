<?php
    if (empty($sousmenucompte)){
        $entete = entete("Mon site / Mes Messages");
        $menu = menu("moncompte");
        if (isset($_SESSION["iduser"])){
            $sousmenu = sousmenu("mesmess");
            $contenu = mesmess($_SESSION["iduser"]);
        } else {
            $contenu= "Vous n'êtes pas connectés ! Connectez vous avant.";
        }
        $pied = pied();
    }

    include 'gabarit.php';
    
    
        
function mesmess($iduser){
    ob_start();
    ?>
    <br/> <br/>
    Bienvenue <?php echo stripslashes(htmlentities(trim($_SESSION['identifiant']))); ?> !<br /><br />
<?php
require ('Modele/connexion.php');

// on prépare une requete SQL cherchant tous les titres, les dates ainsi que l'auteur des messages pour le membre connecté
$sql = 'SELECT titre, date, user.identifiant as expediteur, messages.id as id_message FROM messages, user WHERE id_destinataire="'.$_SESSION['iduser'].'" AND id_expediteur=user.iduser ORDER BY date DESC';
// lancement de la requete SQL
$req=$bdd->query($sql);
$count=$req;
$ligne = $count-> fetch();
// On compte le nombre de lignes
$i=0;
while (!empty($ligne)){
    $ligne = $count-> fetch(); 
    $i++;
}
$nb = $i;

if ($nb == 0) {
	echo 'Vous n\'avez aucun message.';
}
else {
	// si on a des messages, on affiche la date, un lien vers la page lire.php ainsi que le titre et l'auteur du message
        $sql = 'SELECT titre, date, user.identifiant as expediteur, messages.id as id_message FROM messages, user WHERE id_destinataire="'.$_SESSION['iduser'].'" AND id_expediteur=user.iduser ORDER BY date DESC';
        // lancement de la requete SQL
        $req=$bdd->query($sql);
        $data = $req-> fetch();
	while (!empty($data)) {
	echo $data['date'] , ' - <a href="index.php?cible=mess_lire&id_message='.$data['id_message'].'">' , stripslashes(htmlentities(trim($data['titre']))) , '</a> [ Message de ' , stripslashes(htmlentities(trim($data['expediteur']))) , ' ]<br />';
        $data = $req-> fetch();
	}
}
?>
<br /><a href="index.php?cible=mess_envoyer">Envoyer un message</a>
<?php
    $mesmess = ob_get_clean();
    return $mesmess;
}
?>