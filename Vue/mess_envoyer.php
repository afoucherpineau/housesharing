<?php
    if (empty($sousmenucompte)){
        $entete = entete("Mon site / Mes Messages");
        $menu = menu("moncompte");
        if (isset($_SESSION["iduser"])){
            $sousmenu = sousmenu("mesmess");
            $contenu = messenvoyer($_SESSION["iduser"]);
        } else {
            $contenu= "Vous n'êtes pas connectés ! Connectez vous avant.";
        }
        $pied = pied();
    }

    include 'gabarit.php';
    
    
        
function messenvoyer($iduser){
    ob_start();

// on teste si le formulaire a bien été soumis
if (isset($_POST['go']) && $_POST['go'] == 'Envoyer') {
	if (empty($_POST['destinataire']) || empty($_POST['titre']) || empty($_POST['message'])) {
	$erreur = 'Au moins un des champs est vide.';
	}
	else {
        require ('Modele/connexion.php');

	// si tout a été bien rempli, on insère le message dans notre table SQL
        $iduser=$_SESSION['iduser'];
        $req = $bdd->prepare('INSERT INTO messages(id_expediteur, id_destinataire, date, titre, message) VALUES(?,?,?,?,?)');
                        $req->execute(array(
                        $iduser,
                        htmlspecialchars($_POST['destinataire'],ENT_QUOTES),
                        date("Y-m-d H:i:s"),
                        htmlspecialchars($_POST['titre'],ENT_QUOTES),
                        htmlspecialchars($_POST['message'],ENT_QUOTES),
                        ));
        echo "Le message a bien été envoyé";
	}
} else {
?>
<br/><br/>
Envoyer un message :<br /><br />

<?php
require ('Modele/connexion.php');

// on prépare une requete SQL selectionnant tous les login des membres du site en prenant soin de ne pas selectionner notre propre login, le tout, servant à alimenter le menu déroulant spécifiant le destinataire du message
$iduser = $_SESSION['iduser'];
$req=$bdd->query("SELECT * FROM user WHERE iduser!='$iduser'");

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
	// si aucun membre n'a été trouvé, on affiche tout simplement aucun formulaire
	echo 'Vous êtes le seul membre inscrit.';
}
else {
	// si au moins un membre qui n'est pas nous même a été trouvé, on affiche le formulaire d'envoie de message
	?>
	<form action="index.php?cible=mess_envoyer" method="post">
        <form method="post">
            <?php $req=$bdd->query("SELECT * FROM user WHERE iduser!='$iduser'");
            $data = array();
            $data = $req-> fetch();?>
	Pour : <select name="destinataire">
	<?php
	// on alimente le menu déroulant avec les login des différents membres du site
        while (!empty($data)) {
	echo '<option value='.$data["iduser"].'>'.stripslashes(htmlentities(trim($data['identifiant']))).'</option>';
        $data = $req-> fetch();
	}
	?>
	</select><br />
	Titre : <input type="text" name="titre" value="<?php if (isset($_POST['titre'])) echo stripslashes(htmlentities(trim($_POST['titre']))); ?>"><br />
	Message : <textarea name="message"><?php if (isset($_POST['message'])) echo stripslashes(htmlentities(trim($_POST['message']))); ?></textarea><br />
	<input type="submit" name="go" value="Envoyer">
	</form>
	<?php
}
?>
</select>

<?php
// si une erreur est survenue lors de la soumission du formulaire, on l'affiche
if (isset($erreur)) echo '<br /><br />',$erreur;
}
?>

    <?php
    $mesmess = ob_get_clean();
    return $mesmess;
}
?>