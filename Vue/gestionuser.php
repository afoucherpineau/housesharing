<?php
$entete = entete("Mon site / Gestion");
$menu = menu("gestion");
if (isset($_SESSION["iduser"])){
    if (admin($_SESSION["iduser"])=='1'){
        $sousmenu = sousmenu("gestionuser");
        $contenu = "<br/><br/>";
        $contenu .= suppruser();
        $contenu .= afficheruser();
    } else {
        $contenu = "Vous n'êtes pas administrateur. Vous ne pouvez donc pas accéder à cette page.";
    }

} else {
    $contenu .= "Vous n'êtes pas connectés ! Connectez vous avant.";
}

$pied = pied();

include 'gabarit.php';

// Afficher l'ensemble des utilisateurs sous forme de tableau
function afficheruser(){
    ob_start();
    require ('Modele/connexion.php');
    $q=$bdd->query("SELECT * FROM user WHERE admin!='1'");
    $ligne = $q-> fetch();
    echo "<br/>"; ?>
    <fieldset id='infocon'>
    <legend>Liste des utilisateurs (hors administrateurs)</legend>

    <table>
        <tr>
            <td>ID</td>
            <td>Identifiant</td>
            <td>Prénom</td>
            <td>Nom</td>
            <td>Mail</td>
        </tr>
    <?php do { ?>
        <tr>
            <td><?php echo $ligne['iduser'];?></td>
            <td><?php echo $ligne['identifiant'];?></td>
            <td><?php echo $ligne['prenom'];?></td>
            <td><?php echo $ligne['nom'];?></td>
            <td><?php echo $ligne['mail'];?></td>
        </tr>
        <?php $ligne = $q-> fetch();
    } while (!empty($ligne)); ?>
    </table>
    </fieldset>
    <?php
    $afficheruser=ob_get_clean();
    return$afficheruser;
}

// supprimer un utilisateur
function suppruser(){
    ob_start();
    require ('Modele/connexion.php');
    if (empty($_POST)){
        echo formuser();
    } else {
        $iduser = htmlspecialchars($_POST["suppr"],ENT_QUOTES);
        $q=$bdd->prepare("DELETE FROM user WHERE iduser='$iduser'");
        $q -> execute();
        $r=$bdd->prepare("DELETE FROM appart WHERE iduser='$iduser'");
        $r -> execute();
        echo formuser();
        echo "L'utilisateur a été supprimé.<br/><br/>";
    }
    $suppruser=ob_get_clean();
    return $suppruser;
}

// Formulaire de suppression
function formuser(){
    ob_start();
    require ('Modele/connexion.php');
    $q=$bdd->query("SELECT * FROM user WHERE admin!='1'");
    $ligne = $q-> fetch(); ?>
    <fieldset id='infocon'>
    <legend>Supprimer un utilisateur</legend>
        <form method="post">
            <div class="middle">
            <div class="centre_colonne">
                <label for="suppr">Selectionner l'utilisateur à supprimer :</label><br />
                <select name="suppr">
                <?php do {?>
                <option value=<?php echo $ligne['iduser']; ?>><?php echo($ligne['prenom']." ".$ligne['nom']." ; Mail : ".$ligne['mail']." ; ID : ".$ligne['iduser']." "); ?></option>
                <?php
                $ligne = $q-> fetch();
                } while (!empty($ligne)); ?>
                </select>

                <br/>
            <input type="submit" name="Valider" value="Supprimer"/><br/>
            </div>
            </div>
        </form>
    </fieldset>
    <?php
    $formuser=ob_get_clean();
    return$formuser;
}